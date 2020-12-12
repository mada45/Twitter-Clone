<?php

session_start();

if( ! isset($_SESSION['userId'])){
    sendError(401, 'You have to log in first', __LINE__);
}

if( ! ctype_digit($_SESSION['userId'])){
    sendError(400, 'User id is invalid', __LINE__);
}

require_once(__DIR__.'/../mysql.php');

try{

$query = $db->prepare('SELECT iUserId FROM users WHERE iUserId = :iUserId LIMIT 1');
$query->bindValue('iUserId', $_SESSION['userId']);
$query->execute();

if(!$query->rowCount()){
    sendError(500, 'Cannot find user', __LINE__);
}

header('Content-Type:application/json');
echo '{"userId":"'.$_SESSION['userId'].'"}';

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