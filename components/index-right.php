        <form onsubmit="return false" autocomplete="off">
            <i class="fas fa-search"></i>
            <input type="text" name="username" placeholder="Search Twitter" onfocus="showSearchResults()" onblur="hideSearchResults()" oninput="startSearch()">
            <div id="search-results">
                <div>
                    <img src="images/travis.jpg" alt="Profile Image of <?=$arrayRow->sName?>">
                    <div>
                        <p>TRAVIS SCOTT</p>
                        <p>@trvisXX</p>
                    </div>
                </div>
                <div>
                    <img src="images/obama.jpg" alt="Profile Image of <?=$arrayRow->sName?>">
                    <div>
                        <p>Barack Obama</p>
                        <p>@BarackObama</p>
                    </div>
                </div>
            </div>
        </form>

        <article class="trends">
            <div>
                <h2>Trends for you</h2>
                <i class="fas fa-cog"></i>
            </div>

            <div>
                <div>
                    <p>Trending in Denmark</p>
                    <p>Trend</p>
                    <p>7823 Tweets</p>
                </div>
                <i class="fas fa-ellipsis-h"></i>
            </div>
            <div>
                <div>
                    <p>Trending in Denmark</p>
                    <p>Trend</p>
                    <p>7823 Tweets</p>
                </div>
                <i class="fas fa-ellipsis-h"></i>
            </div>
            <div>
                <div>
                    <p>Trending in Denmark</p>
                    <p>Trend</p>
                    <p>7823 Tweets</p>
                </div>
                <i class="fas fa-ellipsis-h"></i>
            </div>
            <div>
                <div>
                    <p>Trending in Denmark</p>
                    <p>Trend</p>
                    <p>7823 Tweets</p>
                </div>
                <i class="fas fa-ellipsis-h"></i>
            </div>
            <div>Show more</div>
        </article>

        <article class="who-to-follow">
            <div>
                <h2>Who to follow</h2>
            </div>

            <?php
            require_once(__DIR__.'/../mysql.php');

            try{
                $query = $db->prepare('SELECT iUserId, sUsername, sName, sUserImage FROM users WHERE iUserId != :sessionId');
                $query->bindValue('sessionId', $_SESSION['userId']);
                $query->execute();
                $arrayRows = $query->fetchAll();

                if(!$arrayRows){
                    ?>
                    <div class="nothing-to-display">
                        <p>No users to display</p>
                    </div>
                    <?php
                }

                foreach($arrayRows as $arrayRow){
                    ?>
                        <div>
                            <article>
                                <img src="images/<?=$arrayRow->sUserImage?>" alt="Profile Image of <?=$arrayRow->sName?>">
                                <div>
                                    <p><?=$arrayRow->sName?></p>
                                    <p>@<?=$arrayRow->sUsername?></p>
                                </div>
                                <div class="follow-btn" onclick="follow(<?=$arrayRow->iUserId?>)">Follow</div>
                            </article>
                        </div>
                    <?php
                }
            }catch(Exception $ex){
                sendError(500, 'Contact system admin', __LINE__);
            }
            ?>
            <div>Show more</div>
        </article>
    