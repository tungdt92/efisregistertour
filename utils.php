<?php

function redirect($url, $statusCode = 303) {
    header('Location: ' . $url, true, $statusCode);
    die();
}

function precheckRegisterUrl(){
    if(empty($_SESSION['store_sheet'])){
        echo 'URL  không  đúng';
        return false;
    }
    return true;
}

function precheckMemberOfGroup(){
    if (empty($_SESSION['is_member']) || $_SESSION['is_member'] == false){
        echo  'Bạn  không  phải  là  thành  viên  CLB';
        return false;
    }
    return true;
}

function precheckMailBody(){
    if(empty($_SESSION['messagebodyemail']))
    {
        return false;
    }
    return true;
}