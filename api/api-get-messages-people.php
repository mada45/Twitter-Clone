<?php

session_start();

if(!isset($_SESSION['userId'])){
    sendError(400, 'Missing session id', __LINE__);
}

if(!ctype_digit($_SESSION['userId'])){
    sendError(400, 'Session id is invalid', __LINE__);
}

require_once __DIR__.'/../mysql.php';

try{

    $query = $db->prepare('SELECT iUserId, sName, sUsername, sUserImage FROM users JOIN followers ON iFolloweeFk = iUserId WHERE iFollowerFk = :sessionId');
    $query->bindValue('sessionId', $_SESSION['userId']);
    $query->execute();
    $arrayRows = $query->fetchAll();

    if(!$arrayRows){
        sendError(500, 'Cannot find users', __LINE__);
    }

    header('Content-Type:application/json');
    echo json_encode($arrayRows);

}catch(Exception $ex){
    sendError(500, 'Contact system admin', __LINE__);
}

##############################################################################
##############################################################################
##############################################################################
function sendError($errorCode, $errorString, $errorLine){
    http_response_code($errorCode);
    header('Content-Type: application/json');
    echo '{"message":"'.$errorString.'", "line":"'.$errorLine.'"}';
    exit();
}