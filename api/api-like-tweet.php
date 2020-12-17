<?php
session_start();
if(! isset($_SESSION['userId'])){
    sendError(401, 'You have to log in first', __LINE__);
}

if(! isset($_POST['tweetId'])){
    sendError(400, 'Missing tweet id', __LINE__);
}

if(! ctype_digit($_SESSION['userId'])){
    sendError(400, 'Session id is not valid', __LINE__);
}

if(! ctype_digit($_POST['tweetId'])){
    sendError(401, 'Tweet id is not valid', __LINE__);
}

require_once __DIR__ .'/../mysql.php';

try{
    $query = $db->prepare('INSERT INTO tweetlikes VALUES(:tweetId, :sessionId)');
    $query->bindValue('sessionId', $_SESSION['userId']);
    $query->bindValue('tweetId', $_POST['tweetId']);
    $query->execute();

    if($query->rowCount() == 0){
        sendError(500, 'Could not insert like', __LINE__);
    }

    header('content-Type:applcation/json');
    echo '{"sessionId":"'.$_SESSION['userId'].'", "tweetIdToLike":"'.$_POST['tweetId'].'"}';
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