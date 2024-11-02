<?php

namespace App\Http\Controllers;

use App\Exports\UserClaimsExport;
use App\Models\CsRotation;
use App\Models\CustomerService;
use App\Models\UserClaim;
use App\Models\Voucher;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_ClearValuesRequest;
use Google_Service_Sheets_ValueRange;

class UserClaimController extends Controller
{
    private $sheets;
    private $spreadsheetId;
    private $maxRetries = 3;
    private $retryDelay = 2;
    private $sheetName;

    public function __construct()
    {
        $this->spreadsheetId = config('services.google.spreadsheet_id');
        $this->sheetName = config('services.google.sheet_name');

        if (app()->environment('production')) {
            $this->initGoogleSheets();
        }
    }

    private function initGoogleSheets()
    {
        try {
            $client = new Google_Client();

            // Gunakan environment variable untuk kredensial
            $credentials = json_decode(base64_decode(env('GOOGLE_CREDENTIALS_JSON')), true);

            if (!$credentials) {
                throw new \Exception('Invalid Google credentials configuration');
            }

            $client->setAuthConfig($credentials);
            $client->setApplicationName(env('APP_NAME'));
            $client->addScope([
                Google_Service_Sheets::SPREADSHEETS,
                Google_Service_Sheets::DRIVE,
                Google_Service_Sheets::DRIVE_FILE
            ]);

            // Konfigurasi Guzzle yang aman
            $client->setHttpClient(new GuzzleClient([
                'timeout' => 30,
                'connect_timeout' => 15,
                'verify' => true,  // Aktifkan SSL verification
                'http_errors' => true,
            ]));

            $this->sheets = new Google_Service_Sheets($client);

            if (!empty($this->spreadsheetId)) {
                $this->validateAndInitializeSheet();
            }
        } catch (\Exception $e) {
            $this->logError('Google Sheets initialization failed', $e);
            $this->sheets = null;
        }
    }

    private function validateAndInitializeSheet()
    {
        $this->retryOperation(function () {
            // Get spreadsheet metadata
            $spreadsheet = $this->sheets->spreadsheets->get($this->spreadsheetId);
            \Log::info('Successfully retrieved spreadsheet: ' . $spreadsheet->getProperties()->getTitle());

            // Check if our sheet exists
            $sheetExists = false;
            foreach ($spreadsheet->getSheets() as $sheet) {
                if ($sheet->getProperties()->getTitle() === $this->sheetName) {
                    $sheetExists = true;
                    break;
                }
            }

            if (!$sheetExists) {
                throw new \Exception("Sheet '{$this->sheetName}' not found in spreadsheet");
            }

            // Verify read/write permissions by reading A1
            $range = sprintf('%s!A2', $this->sheetName);
            $this->sheets->spreadsheets_values->get($this->spreadsheetId, $range);
            \Log::info('Successfully validated sheet access');
        });
    }

    private function getSheetRange($range = 'A:E')
    {
        return sprintf('%s!%s', $this->sheetName, $range);
    }

    private function syncToGoogleSheets(UserClaim $userClaim)
    {
        if (!$this->sheets) {
            \Log::warning('Google Sheets service not initialized. Skipping sync.');
            return;
        }

        try {
            $this->retryOperation(function () use ($userClaim) {
                $range = $this->getSheetRange();

                // Get existing data to determine the new row number
                $response = $this->sheets->spreadsheets_values->get($this->spreadsheetId, $range);
                $rows = $response->getValues() ?? [];
                $rowNumber = 1;  // Start from 1 since header is at row 1

                // Prepare the new row data
                $values = [
                    [
                        $rowNumber,  // Nomor urut
                        $userClaim->user_name,
                        $userClaim->user_whatsapp,
                        $userClaim->voucher->name,
                        $userClaim->created_at->format('Y-m-d H:i:s')
                    ]
                ];

                // Insert data at A2 (right after headers)
                $insertRange = $this->getSheetRange('A2');
                $body = new Google_Service_Sheets_ValueRange([
                    'values' => $values
                ]);

                $params = [
                    'valueInputOption' => 'RAW',
                    'insertDataOption' => 'INSERT_ROWS'
                ];

                // Insert at specific position (A2) instead of appending
                $this->sheets->spreadsheets_values->update(
                    $this->spreadsheetId,
                    $insertRange,
                    $body,
                    ['valueInputOption' => 'RAW']
                );

                // Update all row numbers
                $this->updateRowNumbers();

                \Log::info('Successfully synced claim ID ' . $userClaim->id . ' to Google Sheets');
            });
        } catch (\Exception $e) {
            $this->logGoogleSheetsError($e);
        }
    }

    private function updateRowNumbers()
    {
        // Get all existing data
        $range = $this->getSheetRange('A2:E');
        $response = $this->sheets->spreadsheets_values->get($this->spreadsheetId, $range);
        $rows = $response->getValues() ?? [];

        if (empty($rows)) {
            return;
        }

        // Update row numbers
        $updatedRows = [];
        foreach ($rows as $index => $row) {
            $row[0] = $index + 1;  // Update nomor urut
            $updatedRows[] = $row;
        }

        // Update the sheet with new row numbers
        $body = new Google_Service_Sheets_ValueRange([
            'values' => $updatedRows
        ]);

        $this->sheets->spreadsheets_values->update(
            $this->spreadsheetId,
            $this->getSheetRange('A2'),
            $body,
            ['valueInputOption' => 'RAW']
        );
    }

    public function syncAllClaimsToGoogleSheets()
    {
        try {
            if (!$this->sheets) {
                throw new \Exception('Google Sheets service not initialized');
            }

            // Get all claims sorted by created_at DESC
            $claims = UserClaim::with('voucher')
                ->orderBy('created_at', 'desc')
                ->get();

            $values = [];

            foreach ($claims as $index => $claim) {
                $values[] = [
                    $index + 1,  // Nomor urut
                    $claim->user_name,
                    $claim->user_whatsapp,
                    $claim->voucher->name,
                    $claim->created_at->format('Y-m-d H:i:s')
                ];
            }

            $this->retryOperation(function () use ($values) {
                // Clear existing data (except headers)
                $clearRange = $this->getSheetRange('A2:E');
                $this->sheets->spreadsheets_values->clear(
                    $this->spreadsheetId,
                    $clearRange,
                    new Google_Service_Sheets_ClearValuesRequest()
                );

                // Update with new sorted data
                $body = new Google_Service_Sheets_ValueRange(['values' => $values]);

                $this->sheets->spreadsheets_values->update(
                    $this->spreadsheetId,
                    $this->getSheetRange('A2'),
                    $body,
                    ['valueInputOption' => 'RAW']
                );
            });

            return redirect()
                ->route('user-claims.index')
                ->with('success', 'Data berhasil disinkronkan ke Google Sheets');
        } catch (\Exception $e) {
            $this->logError('Sync to Google Sheets failed', $e);

            if (app()->environment('production')) {
                return back()->with('error', 'Terjadi kesalahan saat sinkronisasi. Silakan coba lagi.');
            }

            return back()->with('error', $e->getMessage());
        }
    }

    private function logGoogleSheetsError(\Exception $e)
    {
        $errorMessage = $e->getMessage();
        $errorDetails = json_decode($errorMessage, true);

        if ($errorDetails && isset($errorDetails['error'])) {
            \Log::error('Google Sheets error: ' . json_encode($errorDetails['error'], JSON_PRETTY_PRINT));
        } else {
            \Log::error('Google Sheets error: ' . $errorMessage);
        }
    }

    private function logError($message, \Exception $e)
    {
        \Log::error($message, [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => app()->environment('local') ? $e->getTraceAsString() : null
        ]);
    }

    private function retryOperation(callable $operation)
    {
        $attempts = 0;
        $lastException = null;

        while ($attempts < $this->maxRetries) {
            try {
                return $operation();
            } catch (\Exception $e) {
                $lastException = $e;
                $attempts++;

                $this->logGoogleSheetsError($e);

                if ($attempts < $this->maxRetries) {
                    \Log::warning("Attempt {$attempts} failed. Retrying in {$this->retryDelay} seconds...");
                    sleep($this->retryDelay);
                }
            }
        }

        throw $lastException;
    }

    public function show($slug)
    {
        $voucher = Voucher::where('slug', $slug)->first();

        return view('pages.claim-voucher.index', compact('voucher'));
    }

    public function claim(Request $request, $slug)
    {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'user_whatsapp' => 'required|string|max:20',
        ]);

        $voucher = Voucher::where('slug', $slug)->first();

        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }
        $whatsappNumber = $request->input('user_whatsapp');
        $whatsappNumber = ltrim($whatsappNumber, '0');
        $whatsappNumber = preg_replace('/^62/', '', $whatsappNumber);
        $whatsappNumber = "62$whatsappNumber";

        $userClaim = $voucher->userClaims()->create([
            'user_name' => $request->input('user_name'),
            'user_whatsapp' => $whatsappNumber,
        ]);
        $this->syncToGoogleSheets($userClaim);

        $rotation = CsRotation::first();
        if (!$rotation) {
            $rotation = CsRotation::create(['current_cs_id' => 1]);
        }
        $customerService = CustomerService::find($rotation->current_cs_id);
        if (!$customerService) {
            return redirect()->back()->with('error', 'Customer service tidak ditemukan.');
        }
        $minCsId = CustomerService::min('id');

        $nextCsId = CustomerService::where('id', '>', $rotation->current_cs_id)
            ->min('id');
        if (!$nextCsId) {
            $nextCsId = $minCsId;
        }

        $rotation->update(['current_cs_id' => $nextCsId]);
        $whatsappMessage = 'Halo Kak ' . $customerService->name . " saya mau klaim voucher loyalty card DISKON ONGKIR dan FREE GIFT SPESIAL, mohon dibantu\u{00A0}proses\u{00A0}ya ...";

        $whatsappUrl = 'https://wa.me/' . $customerService->phone . '?text=' . urlencode($whatsappMessage);

        return redirect($whatsappUrl);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = UserClaim::with('voucher')->orderBy('created_at', 'desc');

            // Get the DataTables query
            $datatable = DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('voucher_name', function ($row) {
                    return $row->voucher ? $row->voucher->name : 'N/A';
                })
                ->editColumn('created_at', function ($row) {
                    return \Carbon\Carbon::parse($row->created_at)->format('d M Y H:i');
                })
                ->filterColumn('voucher_name', function ($query, $keyword) {
                    $query->whereHas('voucher', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                });

            return $datatable->make(true);
        }

        return view('pages.admin.user-claims.index');
    }

    public function export(Request $request)
    {
        return Excel::download(new UserClaimsExport($request), 'user_claims_' . date('Y-m-d_H-i-s') . '.xlsx');
    }
}
