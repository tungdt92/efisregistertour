<?php

//if (php_sapi_name() != 'cli') {
//    throw new Exception('This application must be run on the command line.');
//}

session_start();

require __DIR__ . '/vendor/autoload.php';
require './constants.php';
require './utils.php';

if (precheckRegisterUrl() == false || precheckMemberOfGroup() == false) {
    session_destroy();
    exit;
}

function myArrayContainsWord(array $myArray, $word) {
    foreach ($myArray as $element) {
        printf ("element %s\n", $element->title);
        if ($element->properties->title == $word) {
            return true;
        }
    }
    return false;
}

function checkSheetExist($service, $spreadsheetId, $sheetname) {
    $spreadSheet = $service->spreadsheets->get($spreadsheetId);
    $sheets = $spreadSheet->getSheets();

    if (myArrayContainsWord($sheets, $sheetname)) {
        printf ("sheet found\n");
        return true;
    }
    printf ("sheet not found\n");
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
    $array = array(
        'name' => 'Tên',
        'class' => 'Lớp',
        'numtour' => 'Số tour',
        'role' => 'Vai trò',
        'starttime' => 'Bắt đầu',
        'endtime' => 'Kết thúc',
        'phonenum' => 'Số đt',
        'vehicle' => 'Phương tiện',
        'fbname' => 'Tên FB',
        'fburl' => 'Địa chỉ FB',);
    appendNewRow($service, $spreadsheetId, $sheetname, $array);
}

function appendNewRow($service, $spreadsheetId, $sheetname, $array) {
    $range = $sheetname . '!A1:J';
    $values = [
        [$array['name'], $array['class'], $array['numtour'], $array['role'], $array['starttime'], $array['endtime'], $array['phonenum'], $array['vehicle'],$array['fbname'], $array['fburl']],
    ];
    $requestBody = new Google_Service_Sheets_ValueRange([
        'values' => $values
    ]);
    $option = ["valueInputOption" => "RAW"];
    $response = $service->spreadsheets_values->append($spreadsheetId, $range, $requestBody, $option);

// TODO: Change code below to process the `response` object:
    echo '<pre>', var_export($response, true), '</pre>', "\n";
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
$sheetname = $_SESSION['store_sheet'];

$record = array(
    'name' => $_POST["name"],
    'class' => $_POST["class"],
    'numtour' => $_POST["tour_num"],
    'role' => $_POST["role"],
    'starttime' => $_POST["starttime"],
    'endtime' => $_POST["endtime"],
    'phonenum' => $_POST["phonenum"],
    'vehicle' => $_POST["vehicle"],
    'fburl' => $_SESSION['user_link'],
    'fbname' => $_SESSION['user_name'],
);

if (checkSheetExist($service, $spreadsheetId, $sheetname) == true) {
    appendNewRow($service, $spreadsheetId, $sheetname, $record);
} else {
    addNewSheet($service, $spreadsheetId, $sheetname);
    appendNewRow($service, $spreadsheetId, $sheetname, $record);
}

exit;


