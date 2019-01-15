<?php
    ob_start();
    session_start();
    require './utils.php';
    /** @var type $_GET */
    $store_sheet = $_GET['sheet'];
    if(empty($store_sheet)){
        echo 'URL không đúng';
        exit();
    }else{
        $_SESSION['store_sheet'] = $store_sheet;
    }
    echo $_SESSION['store_sheet'];
    redirect('./fb-login.php');
    

