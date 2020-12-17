<?php

if( ! isset($_GET['username'])){
    sendError(400, 'missing username', __LINE__);
}

if( strlen($_GET['username']) < 1 ){
    sendError(400, 'username must be at least 1 characters', __LINE__);
}

if( strlen($_GET['username']) > 100 ){
    sendError(400, 'username must be less then 100 characters', __LINE__);
}

require_once(__DIR__.'/../mysql.php');

try{

    $query = $db->prepare('SELECT iUserId, sUsername, sName, sUserImage FROM users WHERE sUsername LIKE :searchInput LIMIT 10');
    $query->bindValue('searchInput', $_GET['username'].'%');
    $query->execute();
    $arrayRows = $query->fetchAll();

    // might not need this in a search
    if( ! $arrayRows){
        sendError(400, 'cannot find any user', __LINE__);
    }
        
    header('Content-Type: application/json');
    echo json_encode($arrayRows);
        
}catch(Exception $ex){
    sendError(400, 'Contact system admin', __LINE__);
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