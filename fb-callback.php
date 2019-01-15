<?php
ob_start();
session_start();

require_once __DIR__ . '/vendor/autoload.php';
require './constants.php';
require './utils.php';

if(precheckRegisterUrl()==false){
    session_destroy();
    exit;
}

$fb = new Facebook\Facebook([
    'app_id' => APPID, // Replace {app-id} with your app id
    'app_secret' => APPSECRET,
    'default_graph_version' => 'v3.2',
        ]);

/* GET ACCESTOKEN================================ */
$helper = $fb->getRedirectLoginHelper();
if (isset($_GET['state'])) {
    $helper->getPersistentDataHandler()->set('state', $_GET['state']);
}
try {
    $accessToken = $helper->getAccessToken();
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

if (!isset($accessToken)) {
    if ($helper->getError()) {
        header('HTTP/1.0 401 Unauthorized');
        echo "Error: " . $helper->getError() . "\n";
        echo "Error Code: " . $helper->getErrorCode() . "\n";
        echo "Error Reason: " . $helper->getErrorReason() . "\n";
        echo "Error Description: " . $helper->getErrorDescription() . "\n";
    } else {
        header('HTTP/1.0 400 Bad Request');
        echo 'Bad request';
    }
    exit;
}

// Logged in
//echo '<h3>Access Token</h3>';
//var_dump($accessToken->getValue());
// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
//echo '<h3>Metadata</h3>';
//var_dump($tokenMetadata);
// Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId(APPID); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
$tokenMetadata->validateExpiration();

if (!$accessToken->isLongLived()) {
    // Exchanges a short-lived access token for a long-lived one
    try {
        $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
        exit;
    }

    echo '<h3>Long-lived</h3>';
    var_dump($accessToken->getValue());
}

$_SESSION['fb_access_token'] = (string) $accessToken;

// User is logged in with a long-lived access token.
// You can redirect them to a members-only page.
//header('Location: https://example.com/members.php');

/* GET USER INFO================================ */
try {
    // Returns a `Facebook\FacebookResponse` object
    $response = $fb->get('/me?fields=id,name,link', $accessToken->getValue());
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

$user = $response->getGraphUser();

//echo 'Name: ' . $user['name'];
// OR
echo 'USER INFO================================<br>';
echo 'Name: ' . $user->getName() . '<br/>';
echo 'ID: ' . $user->getId() . '<br/>';
echo 'Link: ' . $user->getLink() . '<br/>';

$_SESSION['user_name'] = (string) $user->getName();
$_SESSION['user_link'] = (string) $user->getLink();

/* GET GROUPS INFO================================ */
/* make the API call */
try {
    // Returns a `Facebook\FacebookResponse` object
    $response = $fb->get(
            '/' . $user->getId() . '/groups', $accessToken->getValue());
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

echo 'GROUP INFO================================<br>';
$graphEdge = $response->getGraphEdge();

$isMemberOfGroup = false;
foreach ($graphEdge as $item) {
    echo 'Group ' . $item->getField('name') . ' Id:' . $item->getField('id') . '<br/>';
    if ($item->getField('id') == EFISGROUPID) {
        $isMemberOfGroup = true;
        break;
    }
}
$_SESSION['is_member'] = $isMemberOfGroup;

if ($isMemberOfGroup) {
    redirect('./form/registrationform.php');
} else {
    echo  'Bạn  không  phải  là  thành  viên  CLB';
    session_destroy();
    exit;
}
/* handle the result */


