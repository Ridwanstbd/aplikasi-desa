<?php

namespace App\Http\Controllers;

use App\Models\Leads;
use GuzzleHttp\Client as GuzzleClient;
use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_ClearValuesRequest;
use Google_Service_Sheets_ValueRange;

class LeadsController extends Controller
{
    private $sheets;
    private $spreadsheetId;
    private $maxRetries = 3;
    private $retryDelay = 2;
    private $sheetName;

    public function __construct()
    {
        $this->spreadsheetId = config('services.leads_spreadsheet_id');
        $this->sheetName = config('services.google.sheet_name');

        if (app()->environment('production')) {
            $this->initGoogleSheets();
        }
    }

    private function initGoogleSheets()
    {
        try {
            $client = new Google_Client();
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

            $client->setHttpClient(new GuzzleClient([
                'timeout' => 30,
                'connect_timeout' => 15,
                'verify' => true,
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
            $spreadsheet = $this->sheets->spreadsheets->get($this->spreadsheetId);
            \Log::info('Successfully retrieved spreadsheet: ' . $spreadsheet->getProperties()->getTitle());

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

            $range = sprintf('%s!A2', $this->sheetName);
            $this->sheets->spreadsheets_values->get($this->spreadsheetId, $range);
            \Log::info('Successfully validated sheet access');
        });
    }

    private function getSheetRange($range = 'A:M')
    {
        return sprintf('%s!%s', $this->sheetName, $range);
    }

    public function syncLeadToGoogleSheets(Leads $lead)
    {
        if (!$this->sheets) {
            \Log::warning('Google Sheets service not initialized. Skipping sync.');
            return;
        }

        try {
            $this->retryOperation(function () use ($lead) {
                $range = $this->getSheetRange();

                // Get existing data
                $response = $this->sheets->spreadsheets_values->get($this->spreadsheetId, $range);
                $rows = $response->getValues() ?? [];

                // Prepare the new row data
                $values = [
                    [
                        count($rows) + 1,  // Row number
                        $lead->name,
                        $lead->phone,
                        $lead->address,
                        $lead->product->name,
                        $lead->detail_order,
                        $lead->time_order,
                        $lead->province,
                        $lead->regency,
                        $lead->district,
                        $lead->village,
                        $lead->created_at->format('Y-m-d H:i:s'),
                        $lead->updated_at->format('Y-m-d H:i:s')
                    ]
                ];

                // Append the new data
                $body = new Google_Service_Sheets_ValueRange([
                    'values' => $values
                ]);

                $this->sheets->spreadsheets_values->append(
                    $this->spreadsheetId,
                    $this->getSheetRange(),
                    $body,
                    ['valueInputOption' => 'RAW', 'insertDataOption' => 'INSERT_ROWS']
                );

                \Log::info('Successfully synced lead ID ' . $lead->id . ' to Google Sheets');
            });
        } catch (\Exception $e) {
            $this->logGoogleSheetsError($e);
        }
    }

    public function syncAllLeadsToGoogleSheets()
    {
        try {
            if (!$this->sheets) {
                throw new \Exception('Google Sheets service not initialized');
            }

            $leads = Leads::with('product')
                ->orderBy('created_at', 'desc')
                ->get();

            $values = [];
            foreach ($leads as $index => $lead) {
                $values[] = [
                    $index + 1,
                    $lead->name,
                    $lead->phone,
                    $lead->address,
                    $lead->product->name,
                    $lead->detail_order,
                    $lead->time_order,
                    $lead->province,
                    $lead->regency,
                    $lead->district,
                    $lead->village,
                    $lead->created_at->format('Y-m-d H:i:s'),
                    $lead->updated_at->format('Y-m-d H:i:s')
                ];
            }

            $this->retryOperation(function () use ($values) {
                // Clear existing data (except headers)
                $clearRange = $this->getSheetRange('A2:M');
                $this->sheets->spreadsheets_values->clear(
                    $this->spreadsheetId,
                    $clearRange,
                    new Google_Service_Sheets_ClearValuesRequest()
                );

                // Update with new data
                $body = new Google_Service_Sheets_ValueRange(['values' => $values]);
                $this->sheets->spreadsheets_values->update(
                    $this->spreadsheetId,
                    $this->getSheetRange('A2'),
                    $body,
                    ['valueInputOption' => 'RAW']
                );
            });

            return redirect()
                ->route('leads.index')
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

    public function index()
    {
        $leads = Leads::latest()->get();
        return view('pages.admin.leads.index', compact('leads'));
    }

    public function destroy($id)
    {
        Leads::find($id)->delete();
        return redirect()->route('leads.index')->with('success', 'Leads Berhasil dihapus!');
    }
}
