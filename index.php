<?php
session_start();

if( ! $_SESSION['userId']){
    http_response_code(401);
    header('Location: signup.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <script src="https://kit.fontawesome.com/7021d3e2a4.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700;900&display=swap" rel="stylesheet">
    <title>Twitter</title>
</head>
<body>
    <!-- LEFT SECTION START -->
    <section id="left">
        <div>
            <i class="fab fa-twitter"></i>
        </div>
        <div data-id="home" class="active">
            <i class="fas fa-home"></i>
            Home
        </div>
        <div data-id="explore">
            <i data-id="explore" class="fas fa-hashtag"></i>
            Explore
        </div>
        <div data-id="notifications">
            <i data-id="notifications" class="far fa-bell"></i>
            Notifications
        </div>
        <div onclick="getMessagesPeople()" data-id="messages">
            <i data-id="messages" class="far fa-envelope"></i>
            Messages
        </div>
        <div data-id="bookmarks">
            <i data-id="bookmarks" class="far fa-bookmark"></i>
            Bookmarks
        </div>
        <div data-id="lists">
            <i data-id="lists" class="fas fa-list-ul"></i>
            Lists
        </div>
        <div data-id="profile">
            <i data-id="profile" class="far fa-user"></i>
            Profile
        </div>
        <div data-id="more">
            <i data-id="more" class="fas fa-ellipsis-h"></i>
            More
        </div>
        <button>Tweet</button>
        <a href="logout.php">Log Out</a>

        <article>
            <img src="images/<?= $_SESSION['profileImage'] ?>" alt="Profile Image of <?=$_SESSION['name']?>">
            <div>
                <p><?=$_SESSION['name']?></p>
                <p>@<?=$_SESSION['username']?></p>
            </div>
            <i class="fas fa-angle-down"></i>
        </article>
    </section>
    <!-- LEFT SECTION END -->
    <!-- MID SECTION START -->
    <section class="mid" id="home">
        <?php require_once('components/index-home.php') ?>
    </section>
    <section class="mid" id="messages">
        <?php require_once('components/index-message-middle.php') ?>
    </section>
    <!-- MID SECTION END -->
    <!-- RIGHT SECTION START -->
    <section id="right">
        <?php require_once('components/index-right.php') ?>
    </section>
    <!-- <section id="right">
        <?php require_once('components/index-messages-right.php') ?>
    </section> -->
    <!-- RIGHT SECTION END -->
    <script src="app.js"></script>
</body>
</html>