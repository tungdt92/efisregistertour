<?php

require __DIR__ . '/vendor/autoload.php';

if (php_sapi_name() != 'cli') {
    throw new Exception('This application must be run on the command line.');
}

function addNewSheet($service, $spreadsheetId) {
    $requests = [
        // Change the spreadsheet's title.
        new Google_Service_Sheets_Request([
            'addSheet' => [
                'properties' => [
                    'title' => 'tdtnewsheet'
                ]
            ]
                ])
    ];

// Add additional requests (operations) ...
    $batchUpdateRequest = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
        'requests' => $requests
    ]);

    $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
//    $findReplaceResponse = $response->getReplies()[1]->getFindReplace();
    printf("%s replacements made.\n",
            $response->properties->sheetId);
}

function getServiceAcountClient() {
    $KEY_FILE_LOCATION =__DIR__ .'/efis register tour-3ad269a7bda0.json';
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

// Prints the names and majors of students in a sample spreadsheet:
// https://docs.google.com/spreadsheets/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms/edit
//$spreadsheetId = '1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms';
//
//https://docs.google.com/spreadsheets/d/1jSY-Qfq18ynu_IYUgGSDNJe4NbgmfRcOBU0vb_3VxWA/edit?usp=sharing
$spreadsheetId = '1jSY-Qfq18ynu_IYUgGSDNJe4NbgmfRcOBU0vb_3VxWA';

addNewSheet($service, $spreadsheetId);

//$range = 'Class Data!A2:E';
//
//$response = $service->spreadsheets_values->get($spreadsheetId, $range);
//$values = $response->getValues();
//
//if (empty($values)) {
//    print "No data found.\n";
//} else {
//    print "Name, Major:\n";
//    foreach ($values as $row) {
//        // Print columns A and E, which correspond to indices 0 and 4.
//        printf("%s, %s\n", $row[0], $row[4]);
//    }
//}