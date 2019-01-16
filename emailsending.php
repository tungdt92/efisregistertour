<?php
//if (php_sapi_name() != 'cli') {
//    throw new Exception('This application must be run on the command line.');
//}
ob_start();
session_start();

require __DIR__ . '/vendor/autoload.php';
require './constants.php';
require './utils.php';


if(precheckMailBody() == false){
    echo 'URL không đúng';
    session_destroy();
    exit;
}
/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient() {
    $client = new Google_Client();
    $client->setApplicationName('Send Email tour info');
    $client->setScopes(Google_Service_Gmail::GMAIL_SEND);
    $client->setAuthConfig('emailcredentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = 'emailtoken.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
}

function sendEmail($service) {
    
    try {
        $strSubject = "Đăng  ký ".$_SESSION['store_sheet'];
        $strRawMessage = "From: He thong dang ky tour online<hellotour2019@gmail.com>\r\n";
        $strRawMessage .= "To: Thuy<efistourregister@gmail.com>\r\n";
//        $strRawMessage .= "CC: Bar<bar@gmail.com>\r\n";
        $strRawMessage .= "Subject: =?utf-8?B?" . base64_encode($strSubject) . "?=\r\n";
        $strRawMessage .= "MIME-Version: 1.0\r\n";
        $strRawMessage .= "Content-Type: text/html; charset=utf-8\r\n";
        $strRawMessage .= "Content-Transfer-Encoding: base64" . "\r\n\r\n";
        $strRawMessage .= $_SESSION['messagebodyemail'] . "\r\n";
        // The message needs to be encoded in Base64URL
        $mime = rtrim(strtr(base64_encode($strRawMessage), '+/', '-_'), '=');
        $msg = new Google_Service_Gmail_Message();
        $msg->setRaw($mime);
        //The special value **me** can be used to indicate the authenticated user.
        $service->users_messages->send("me", $msg);
    } catch (Exception $e) {
        print "An error occurred: " . $e->getMessage();
    }
}

// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Gmail($client);
sendEmail($service);
echo 'Dang ky tour thanh cong';

session_destroy();