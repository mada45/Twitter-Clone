<?php

session_start();

if( ! isset($_SESSION['userId'])){
    sendError(401, 'You have to log in first', __LINE__);
}

if( ! isset($_POST['deleteTweetId'])){
    sendError(400, 'Delete tweet id cannot be found', __LINE__);
}

if( ! ctype_digit($_SESSION['userId'])){
    sendError(400, 'User id is invalid', __LINE__);
}

if( ! ctype_digit($_POST['deleteTweetId'])){
    sendError(400, ' Tweet id is invalid', __LINE__);
}

require_once(__DIR__.'/../mysql.php');

try{

    $query = $db->prepare('UPDATE tweets SET bTweetActive = 0 WHERE iUserFk = :sessionUserId AND iTweetId = :deleteTweetId');
    $query->bindValue('sessionUserId', $_SESSION['userId']);
    $query->bindValue('deleteTweetId', $_POST['deleteTweetId']);
    $query->execute();

    if( $query->rowCount() == 0 ){
        sendError(400, 'Can\'t find tweet to delete', __LINE__);
    }

    header('Content-Type:application/json');
    echo '{"tweetId":"'.$_POST['deleteTweetId'].'"}';


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