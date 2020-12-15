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

    $query = $db->prepare('SELECT iFolloweeFk FROM followers WHERE :sessionId = iFollowerFk');
    $query->bindValue('sessionId', $_SESSION['userId']);
    $query->execute();
    $arrayRows = $query->fetchAll();

    if(!$arrayRows){
        sendError(500, 'Cannot find users', __LINE__);
    }

    foreach($arrayRows as $arrayRow){
        $query = $db->prepare('SELECT iUserId, sName, sUsername, sUserImage FROM users WHERE :followeeId = iUserId LIMIT 10');
        $query->bindValue('followeeId', $arrayRow->iFolloweeFk);
        $query->execute();
        $newArrayRows = $query->fetchAll();

        if(!$newArrayRows){
            sendError(500, 'Cannot find users', __LINE__);
        }
        // foreach($newArrayRows as $newArrayRow){
        //     header('Content-Type:application/json');
        //     echo '{"userId":"'.$newArrayRow->iUserId.'", "name":"'.$newArrayRow->sName.'", "username":"'.$newArrayRow->sUsername.'", "userImage":"'.$newArrayRow->sUserImage.'"}';
        //     // echo json_encode($newArrayRow);
        // }
        
    }
    header('Content-Type:application/json');
    echo json_encode($newArrayRows);


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