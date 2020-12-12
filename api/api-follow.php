<?php
session_start();
if(! isset($_POST['userId'])){
    sendError(400, 'user id missing', __LINE__);
}
if(! isset($_SESSION['userId'])){
    sendError(400, 'session user id missing', __LINE__);
}
if(! ctype_digit($_POST['userId'])){
    sendError(400, 'user id is not valid', __LINE__);
}
if(! ctype_digit($_SESSION['userId'])){
    sendError(400, 'session user id is not valid', __LINE__);
}

require_once __DIR__.'/../mysql.php';

try{
    $query = $db->prepare('INSERT INTO followers VALUES(:iFollowerFk, :iFolloweeFk)');
    $query->bindValue('iFollowerFk', $_SESSION['userId']);
    $query->bindValue('iFolloweeFk', $_POST['userId']);
    $query->execute();

    if($query->rowCount() == 0){
        sendError(400, 'Follow could not be saved', __LINE__);
    }

    $query = $db->prepare('INSERT INTO chats VALUES(:iChatId, :iChatFirstUserFk, :iChatSecondUserFk)');
    $query->bindValue('iChatId', null);
    $query->bindValue('iChatFirstUserFk', $_SESSION['userId']);
    $query->bindValue('iChatSecondUserFk', $_POST['userId']);
    $query->execute();

    if($query->rowCount() == 0){
        sendError(400, 'Chat could not be inserted', __LINE__);
    }

    header('Content-Type:application/json');
    echo '{"sessionUserId":"'.$_SESSION['userId'].'", "userId":"'.$_POST['userId'].'"}';

}catch(Exception $ex){
    echo $ex;
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