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
        $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <title>Redirecting...</title>
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                font-family: Arial, sans-serif;
                flex-direction: column;
                text-align: center;
            }
            #redirect-message {
                margin-bottom: 20px;
            }
            .whatsapp-link {
                color: #25D366;
                text-decoration: none;
                font-weight: bold;
                padding: 10px 20px;
                border: 2px solid #25D366;
                border-radius: 5px;
                display: inline-flex;
                align-items: center;
                gap: 8px;
            }
            .whatsapp-link:hover {
                background-color: #25D366;
                color: white;
            }
            .whatsapp-link svg {
                vertical-align: middle;
            }
        </style>
    </head>
    <body>
        <div id="redirect-message">
            <p>Silahkan klik tombol di bawah untuk konfirmasi via WhatsApp</p>
            <a href="' . $whatsappUrl . '" class="whatsapp-link" target="_blank" onclick="redirectHome()">
                <span>Buka WhatsApp</span>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
            </a>
        </div>
        <script>
            function redirectHome() {
                setTimeout(function() {
                    window.location.href = "' . route('home') . '";
                }, 500);
            }
        </script>
    </body>
    </html>';
        return response($html);
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
