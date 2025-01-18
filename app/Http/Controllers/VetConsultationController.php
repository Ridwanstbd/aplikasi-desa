<?php
// VetConsultationController.php
namespace App\Http\Controllers;

use App\Models\VetConsultation;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_ClearValuesRequest;
use Google_Service_Sheets_ValueRange;

class VetConsultationController extends Controller
{
    private $sheets;
    private $spreadsheetId;
    private $maxRetries = 3;
    private $retryDelay = 2;
    private $sheetName;

    public function __construct()
    {
        $this->spreadsheetId = config('services.consultations_spreadsheet_id');
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

    private function getSheetRange($range = 'A:H')
    {
        return sprintf('%s!%s', $this->sheetName, $range);
    }

    private function syncToGoogleSheets(VetConsultation $VetConsultation)
    {
        if (!$this->sheets) {
            \Log::warning('Google Sheets service not initialized. Skipping sync.');
            return;
        }

        try {
            $this->retryOperation(function () use ($VetConsultation) {
                $range = $this->getSheetRange();

                // Get existing data to determine the new row number
                $response = $this->sheets->spreadsheets_values->get($this->spreadsheetId, $range);
                $rows = $response->getValues() ?? [];
                $rowNumber = 1;  // Start from 1 since header is at row 1

                // Prepare the new row data
                $values = [
                    [
                        $rowNumber,  // Nomor urut
                        $VetConsultation->full_name,
                        $VetConsultation->address,
                        $VetConsultation->phone_number,
                        $VetConsultation->consultation_date,
                        $VetConsultation->notes,
                        $VetConsultation->created_at->format('Y-m-d H:i:s'),
                        $VetConsultation->updated_at->format('Y-m-d H:i:s')
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

                \Log::info('Successfully synced claim ID ' . $VetConsultation->id . ' to Google Sheets');
            });
        } catch (\Exception $e) {
            $this->logGoogleSheetsError($e);
        }
    }

    private function updateRowNumbers()
    {
        // Get all existing data
        $range = $this->getSheetRange('A2:H');
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

    public function syncAllVetConsultToGoogleSheets()
    {
        try {
            if (!$this->sheets) {
                throw new \Exception('Google Sheets service not initialized');
            }

            // Get all claims sorted by created_at DESC
            $vetconsultations = VetConsultation::with('voucher')
                ->orderBy('created_at', 'desc')
                ->get();

            $values = [];

            foreach ($vetconsultations as $index => $VetConsultation) {
                $values[] = [
                    $index + 1,  // Nomor urut
                    $VetConsultation->full_name,
                    $VetConsultation->address,
                    $VetConsultation->phone_number,
                    $VetConsultation->consultation_date,
                    $VetConsultation->notes,
                    $VetConsultation->created_at->format('Y-m-d H:i:s'),
                    $VetConsultation->updated_at->format('Y-m-d H:i:s')
                ];
            }

            $this->retryOperation(function () use ($values) {
                // Clear existing data (except headers)
                $clearRange = $this->getSheetRange('A2:H');
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
                ->route('vet-consult.index')
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

    // Menampilkan daftar konsultasi
    public function index()
    {
        $consultations = VetConsultation::all();
        return view('pages.admin.vet_consultations.index', compact('consultations'));
    }

    // Menyimpan konsultasi baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone_number' => 'required|string',
            'consultation_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $consultation = VetConsultation::create($validated);

        if (app()->environment('production')) {
            $this->syncToGoogleSheets($consultation);
        }
        return redirect()
            ->back()
            ->with('success', 'Konsultasi berhasil dikirim. Silakan menunggu balasan kami.');
    }

    // Menghapus konsultasi
    public function destroy($id)
    {
        $consultation = VetConsultation::findOrFail($id);
        $consultation->delete();
        return redirect()->route('vet-consult.index')->with('success', 'Konsultasi berhasil dihapus.');
    }
}
