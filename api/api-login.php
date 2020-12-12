<?php

if( ! isset($_POST['email']) ){
  sendError(400, 'missing email', __LINE__);
}
if( ! isset($_POST['password']) ){
  sendError(400, 'missing password', __LINE__);
}

if( ! filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL ) ){
  sendError(400, 'email is not valid', __LINE__);
}

require_once( __DIR__.'/../mysql.php' );

try{

  $query = $db->prepare('SELECT * FROM users WHERE sEmail = :sEmail LIMIT 1');
  $query->bindValue('sEmail', $_POST['email']);
  $query->execute();
  $arrayRow = $query->fetch();

  if( ! $arrayRow ){
    sendError(500, 'User cannot be found', __LINE__);
  }

  if( $arrayRow->sEmail == $_POST['email'] && password_verify($_POST['password'], $arrayRow->sPassword)){
    session_start();
    $_SESSION['userId'] = $arrayRow->iUserId;
    $_SESSION['name'] = $arrayRow->sName;
    $_SESSION['username'] = $arrayRow->sUsername;
    $_SESSION['email'] = $arrayRow->sEmail;
    $_SESSION['totalTweets'] = $arrayRow->iTotalTweets;
    $_SESSION['totalFollows'] = $arrayRow->iTotalFollows;
    $_SESSION['totalFollowers'] = $arrayRow->iTotalFollowers;
    $_SESSION['profileImage'] = $arrayRow->sUserImage;
    header('Content-Type: application/json');
    echo '{"userId":"'.$_SESSION['userId'].'"}';
    exit();
  }

  sendError(400, 'Your email or password is not correct', __LINE__);

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

