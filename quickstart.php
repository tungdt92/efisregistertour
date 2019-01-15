<?php
require './constants.php';
require __DIR__ . '/vendor/autoload.php';

if (php_sapi_name() != 'cli') {
    throw new Exception('This application must be run on the command line.');
}

function myArrayContainsWord(array $myArray, $word) {
    foreach ($myArray as $element) {
        if ($element->title == $word) {
            return true;
        }
    }
    return false;
}

function checkSheetExist($service, $spreadsheetId, $sheetname) {
    $sheetInfo = $service->spreadsheets->get($spreadsheetId);
    $allsheet_info = $sheetInfo['sheets'];
    $idCats = array_column($allsheet_info, 'properties');

    if (myArrayContainsWord($idCats, $sheetname)) {
        printf("tdtnewsheet sheet found\n");
        return true;
    }
    return false;
}

function addNewSheet($service, $spreadsheetId, $sheetname) {

    $requests = [
        // Change the spreadsheet's title.
        new Google_Service_Sheets_Request([
            'addSheet' => [
                'properties' => [
                    'title' => $sheetname
                ]
            ]
                ])
    ];

// Add additional requests (operations) ...
    $batchUpdateRequest = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
        'requests' => $requests
    ]);

    $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
}

function getServiceAcountClient() {
    $KEY_FILE_LOCATION = __DIR__ . '/efis register tour-3ad269a7bda0.json';
    // Create and configure a new client object.
    $client = new Google_Client();
    $client->setApplicationName("Efis Register Tour");
    $client->setAuthConfig($KEY_FILE_LOCATION);
    $client->setScopes(Google_Service_Sheets::SPREADSHEETS);
    return $client;
}

// Get the API client and construct the service object.
$client = getServiceAcountClient();
$service = new Google_Service_Sheets($client);
$spreadsheetId = GOOGLESHEETID;





addNewSheet($service, $spreadsheetId);

