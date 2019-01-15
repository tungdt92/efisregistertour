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

$helper = $fb->getRedirectLoginHelper();

$permissions = ['groups_access_member_info', 'user_link']; // Optional permissions
$loginUrl = $helper->getLoginUrl(FBCALLBACK, $permissions);

redirect($loginUrl);


