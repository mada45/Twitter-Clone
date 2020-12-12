<?php

session_start();

if( ! isset($_SESSION['userId'])){
    sendError(400, 'User cannot be found', __LINE__);
}

if( ! isset($_POST['editTweetId'])){
    sendError(400, 'Edit tweet id cannot be found', __LINE__);
}

if( ! isset($_POST['editTweetBody'])){
    sendError(400, 'New tweet body cannot be found', __LINE__);
}

if( ! ctype_digit($_SESSION['userId'])){
    sendError(400, 'User id is invalid', __LINE__);
}

if( ! ctype_digit($_POST['editTweetId'])){
    sendError(400, 'Tweet id is invalid', __LINE__);
}

if( strlen($_POST['editTweetBody']) < 1 ){
    sendError(400, 'Tweet must be at least 1 character', __LINE__);
}

if( strlen($_POST['editTweetBody']) > 140 ){
    sendError(400, 'Tweet must be less than 140 characters', __LINE__);
}

require_once(__DIR__.'/../mysql.php');

try{

    $query = $db->prepare('UPDATE tweets SET sTweetBody = :sTweetBody WHERE iUserFk = :iUserFk AND iTweetId = :iTweetId');
    $query->bindValue('iUserFk', $_SESSION['userId']);
    $query->bindValue('iTweetId', $_POST['editTweetId']);
    $query->bindValue('sTweetBody', $_POST['editTweetBody']);
    $query->execute();

    if( $query->rowCount() == 0 ){
        sendError(400, 'Can\'t find tweet to edit', __LINE__);
    }

    header('Content-Type:application/json');
    echo '{"tweetId":"'.$_POST['editTweetId'].'", "tweetBody":"'.$_POST['editTweetBody'].'"}';


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