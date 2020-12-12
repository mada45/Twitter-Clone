<div id="tweets">
<?php

require_once(__DIR__.'/../mysql.php');

$query = $db->prepare('SELECT tweets.*, sName, sUsername, sUserImage FROM tweets JOIN users ON iUserFk = iUserId WHERE bTweetActive = 1 ORDER BY iTweetId DESC LIMIT 10');
$query->execute();
$arrayRows = $query->fetchAll();

if( ! $arrayRows ){
    ?>
    <div class="nothing-to-display">
        <p>No tweets to display</p>
    </div>
    <?php
}

foreach($arrayRows as $arrayRow){
    $date = new DateTime("@$arrayRow->iTweetCreated")
    ?>
    <div id="tweet-<?=$arrayRow->iTweetId?>" class="tweet">
        <a href="#">
            <img class="tweet-img" src="images/<?=$arrayRow->sUserImage?>" alt="Profile Image of <?=$arrayRow->sUsername?>">
        </a>
        <div>
            <div>
                <div id="user-<?=$arrayRow->iUserFk?>" class="tweet-main-info">
                    <p><?=$arrayRow->sName?></p>
                    <p>@<?=$arrayRow->sUsername?></p>
                    <p>· <?=$date->format('Y-m-d H:i')?></p>
                    <i onclick="editDeletePopup(<?=$arrayRow->iUserFk?>)" class="fas fa-ellipsis-h"></i>
                </div>
                <p><?=$arrayRow->sTweetBody?></p>
            </div>
            <div>
                <div>
                    <i class="far fa-comment"></i>
                    <p><?=$arrayRow->iTotalComments?></p>
                </div>
                <div>
                    <i class="fas fa-retweet"></i>
                    <p><?=$arrayRow->iTotalRetweets?></p>
                </div>
                <div>
                    <i onclick="likeTweet(<?=$arrayRow->iTweetId?>)" class="far fa-heart"></i>
                    <p><?=$arrayRow->iTotalLikes?></p>
                </div>
                <i class="far fa-share-square"></i>
                <div class="edit-delete-tweet">
                    <button onclick="getEditTweet(<?=$arrayRow->iTweetId?>)">
                        <i class="fas fa-pen"></i>
                        <p>Edit</p>
                    </button>
                    <button onclick="deleteTweet(<?=$arrayRow->iTweetId?>)">
                        <i class="far fa-trash-alt"></i>
                        <p>Delete</p>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php
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
?>
</div>