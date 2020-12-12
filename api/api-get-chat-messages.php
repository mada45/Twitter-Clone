<?php
session_start();

if(!isset($_SESSION['userId'])){
    sendError(400, 'Session id missing', __LINE__);
}

if(!isset($_POST['userId'])){
    sendError(400, 'User id missing', __LINE__);
}

if(!ctype_digit($_SESSION['userId'])){
    sendError(400, 'Session id is invalid', __LINE__);
}

if(!ctype_digit($_POST['userId'])){
    sendError(400, 'User id is invalid', __LINE__);
}

require_once __DIR__.'/../mysql.php';

try{
    $query = $db->prepare('SELECT iChatId FROM chats WHERE :sessionId = iChatFirstUserFk AND :userId = iChatSecondUserFk OR :sessionId = iChatSecondUserFk AND :userId = iChatFirstUserFk LIMIT 1');
    $query->bindValue(':sessionId', $_SESSION['userId']);
    $query->bindValue(':userId', $_POST['userId']);
    $query->execute();
    $arraRow = $query->fetch();

    if(!$arraRow){
        sendError(500, 'Cannot find chat', __LINE__);
    }

    print_r($arraRow);
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