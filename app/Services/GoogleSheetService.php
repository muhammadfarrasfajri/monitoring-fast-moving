<?php

namespace App\Services;

use Google_Client;
use Google_Service_Sheets;

class GoogleSheetService
{
    protected $client;
    protected $service;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setApplicationName('Laravel Google Sheets');
        $this->client->setScopes([Google_Service_Sheets::SPREADSHEETS_READONLY]);
        $this->client->setAuthConfig(storage_path('app/google-credentials.json')); // Path ke credentials Google

        $this->service = new Google_Service_Sheets($this->client);
    }

    public function getSheetData($spreadsheetId, $range)
    {
        $response = $this->service->spreadsheets_values->get($spreadsheetId, $range);
        return $response->getValues();
    }
}
