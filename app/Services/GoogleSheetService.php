<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;

class GoogleSheetService
{
    private Sheets $sheets;
    private string $spreadsheetId;

    public function __construct()
    {
        $client = new Client();
        $client->setApplicationName('Solarteq Project Report');
        $client->setAuthConfig(storage_path('app/google/service-account.json'));
        $client->setScopes([Sheets::SPREADSHEETS_READONLY]);

        $this->sheets = new Sheets($client);
        $this->spreadsheetId = env('GOOGLE_SHEET_ID');
    }

    public function getRange(string $range): array
    {
        $response = $this->sheets->spreadsheets_values->get($this->spreadsheetId, $range);
        return $response->getValues() ?? [];
    }
}
