<?php

session_start();

if( ! isset($_POST['tweetBody']) ){
  sendError(400, 'missing tweet', __LINE__);
}

if( strlen($_POST['tweetBody']) < 1 ){
  sendError(400, 'tweet must be at least 1 characters', __LINE__);
}
if( strlen($_POST['tweetBody']) > 140 ){
  sendError(400, 'tweet cannot be longer than 140 characters', __LINE__);
}

if( ! isset($_SESSION['userId']) ){
  sendError(400, 'you have to log in first', __LINE__);
}

if( ! ctype_digit($_SESSION['userId']) ){
  sendError(400, 'user id not valid', __LINE__);
}


require_once( __DIR__.'/../mysql.php' );

try{

  $query = $db->prepare('INSERT INTO tweets VALUES( :iTweetId, :iUserFk, :sTweetBody, UNIX_TIMESTAMP(), :iTotalLikes, :iTotalComments, :iTotalRetweets, :bTweetActive )');
  $query->bindValue('iTweetId', null);
  $query->bindValue('iUserFk', $_SESSION['userId']);
  $query->bindValue('sTweetBody', $_POST['tweetBody']);
  $query->bindValue('iTotalLikes', 0);
  $query->bindValue('iTotalComments', 0);
  $query->bindValue('iTotalRetweets', 0);
  $query->bindValue('bTweetActive', 1);
  $query->execute();
  $lastTweetId = $db->lastInsertId();

  header('Content-Type: application/json');
  echo '{"tweetId":"'.$lastTweetId.'", "tweetBody":"'.$_POST['tweetBody'].'", "username":"'.$_SESSION['username'].'", "name":"'.$_SESSION['name'].'", "userImage":"'.$_SESSION['profileImage'].'", "userId":"'.$_SESSION['userId'].'"}';

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