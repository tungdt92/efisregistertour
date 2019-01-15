<?php

function redirect($url, $statusCode = 303) {
    header('Location: ' . $url, true, $statusCode);
    die();
}

function precheckRegisterUrl(){
    if(empty($_SESSION['store_sheet'])){
        echo 'URL  không  đúng';
        session_destroy();
        exit;
    }
}

function precheckMemberOfGroup(){
    if (empty($_SESSION['is_member']) || $_SESSION['is_member'] == false){
        echo  'Bạn  không  phải  là  thành  viên  CLB';
        session_destroy();
        exit;
    }
}
