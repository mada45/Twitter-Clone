        <article class="mid-heading">
            <h2>Home</h2>
            <i class="far fa-star"></i>
        </article>
        <form onsubmit="tweet();return false">
            <a href="#">
                <img class="tweet-img" src="images/<?= $_SESSION['profileImage'] ?>" alt="Profile Image of <?=$_SESSION['name']?>">
            </a>
            <div>
                <textarea oninput="enableTweetBtn()" type="text" name="tweetBody" placeholder="What's happening?"></textarea>
                <div>
                    <div>
                        <i class="far fa-image"></i>
                        <i class="fas fa-film"></i>
                        <i class="far fa-chart-bar"></i>
                        <i class="far fa-smile"></i>
                        <i class="far fa-calendar-alt"></i>
                    </div>
                <button id="tweet-btn" class="tweet-btn-disabled">Tweet</button>
                </div>
            </div>
        </form>
        
        <?php require_once('index-home-tweets.php') ?>