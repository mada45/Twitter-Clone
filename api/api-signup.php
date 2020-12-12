<?php

if( ! isset($_POST['username']) ){
  sendError(400, 'missing username', __LINE__);
}

if( ! isset($_POST['name']) ){
  sendError(400, 'missing name', __LINE__);
}

if( ! isset($_POST['email']) ){
  sendError(400, 'missing email', __LINE__);
}
if( ! isset($_POST['password']) ){
  sendError(400, 'missing password', __LINE__);
}
if( ! isset($_POST['confirmPassword']) ){
  sendError(400, 'missing confirmPassword', __LINE__);
}

if( strlen($_POST['username']) < 2 ){
  sendError(400, 'username must be at least 2 characters', __LINE__);
}
if( strlen($_POST['username']) > 15 ){
  sendError(400, 'username cannot be longer than 50 characters', __LINE__);
}

if( strlen($_POST['name']) < 2 ){
  sendError(400, 'name must be at least 2 characters', __LINE__);
}
if( strlen($_POST['name']) > 50 ){
  sendError(400, 'name cannot be longer than 50 characters', __LINE__);
}

if( ! filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL ) ){
  sendError(400, 'email is not valid', __LINE__);
}

if( strlen($_POST['password']) < 6 ){
  sendError(400, 'password must be at least 6 characters', __LINE__);
}

if( strlen($_POST['password']) > 18 ){
  sendError(400, 'password cannot be longer than 18 characters', __LINE__);
}

if( $_POST['password'] !=  $_POST['confirmPassword'] ){
  sendError(400, 'passwords do not match', __LINE__);
}

require_once( __DIR__.'/../mysql.php' );

try{

  $query = $db->prepare('SELECT sEmail FROM users WHERE sEmail = :sEmail LIMIT 1');
  $query->bindValue(':sEmail', $_POST['email']);
  $query->execute();

  if( $query->rowCount() ){
    sendError(500, 'Email already exists', __LINE__);
  }

  $query = $db->prepare('INSERT INTO users VALUES(:iUserId, :sName, :sUsername, :sEmail, :sPassword, UNIX_TIMESTAMP(), :bUserActive, :sVerificationCode, :iTotalTweets, :iTotalFollows, :iTotalFollowers, :sUserImage)');
  $query->bindValue('iUserId', null);
  $query->bindValue('sName', $_POST['name']);
  $query->bindValue('sUsername', $_POST['username']);
  $query->bindValue('sEmail', $_POST['email']);
  $query->bindValue('sPassword', password_hash($_POST['password'], PASSWORD_DEFAULT));
  $query->bindValue('bUserActive', 1);
  $query->bindValue('sVerificationCode', getUuid());
  $query->bindValue('iTotalTweets', 0);
  $query->bindValue('iTotalFollows', 0);
  $query->bindValue('iTotalFollowers', 0);
  $query->bindValue('sUserImage', '1.jpg');
  $query->execute();
  $lastInsertedId = $db->lastInsertId();

  session_start();
  $_SESSION['name'] = $_POST['name'];
  $_SESSION['username'] = $_POST['username'];
  $_SESSION['email'] = $_POST['email'];


  $query = $db->prepare('SELECT iUserId, sUserImage FROM users WHERE sEmail = :sEmail AND bUserActive = 1 LIMIT 1');
  $query->bindValue('sEmail', $_POST['email']);
  $query->execute();
  $arrayRow = $query->fetch();

  $_SESSION['userId'] = $arrayRow->iUserId;
  $_SESSION['profileImage'] = $arrayRow->sUserImage;

  header('Content-Type: application/json');
  echo '{"userId":"'.$_SESSION['userId'].'"}';

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

function getUuid() {
  return sprintf(
    '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    // 32 bits for "time_low"
    mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

    // 16 bits for "time_mid"
    mt_rand( 0, 0xffff ),

    // 16 bits for "time_hi_and_version",
    // four most significant bits holds version number 4
    mt_rand( 0, 0x0fff ) | 0x4000,

    // 16 bits, 8 bits for "clk_seq_hi_res",
    // 8 bits for "clk_seq_low",
    // two most significant bits holds zero and one for variant DCE1.1
    mt_rand( 0, 0x3fff ) | 0x8000,

    // 48 bits for "node"
    mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
  );
}