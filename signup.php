<?php
session_start();

if( isset($_SESSION['userId'])){
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/signup.css">
    <script src="https://kit.fontawesome.com/7021d3e2a4.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700;900&display=swap" rel="stylesheet">
    <title>Twitter</title>
</head>
<body>
    <!-- CONTENT WRAPPER START -->
    <section id="content-wrapper">
        <!-- LEFT SECTION START -->
        <section id="left">
            <svg viewBox="0 0 24 24" ><path d="M23.643 4.937c-.835.37-1.732.62-2.675.733.962-.576 1.7-1.49 2.048-2.578-.9.534-1.897.922-2.958 1.13-.85-.904-2.06-1.47-3.4-1.47-2.572 0-4.658 2.086-4.658 4.66 0 .364.042.718.12 1.06-3.873-.195-7.304-2.05-9.602-4.868-.4.69-.63 1.49-.63 2.342 0 1.616.823 3.043 2.072 3.878-.764-.025-1.482-.234-2.11-.583v.06c0 2.257 1.605 4.14 3.737 4.568-.392.106-.803.162-1.227.162-.3 0-.593-.028-.877-.082.593 1.85 2.313 3.198 4.352 3.234-1.595 1.25-3.604 1.995-5.786 1.995-.376 0-.747-.022-1.112-.065 2.062 1.323 4.51 2.093 7.14 2.093 8.57 0 13.255-7.098 13.255-13.254 0-.2-.005-.402-.014-.602.91-.658 1.7-1.477 2.323-2.41z"></path></svg>
            <div>
                <div class="left-text-box">
                    <i class="fas fa-search"></i>
                    <p>Follow your interests.</p>
                </div>
                <div class="left-text-box">
                    <i class="fas fa-user-friends"></i>
                    <p>Hear what people are talking about.</p>
                </div>
                <div class="left-text-box">
                    <i class="far fa-comment"></i>
                    <p>Join the conversation.</p>
                </div>
            </div>
        </section>
        <!-- LEFT SECTION END -->
        <!-- RIGHT SECTION START -->
        <section id="right">
            <form id="login" onsubmit="login();return false;">
                <div>
                    <p>Email</p>
                    <input type="text" name="email" value="tothadam.97@gmail.com">
                </div>
                <div>
                    <p>Password</p>
                    <input type="password" name="password" value="123456">
                </div>
                <button>Log in</button>
            </form>
            <article id="signup">
                <div>
                    <i class="fab fa-twitter"></i>
                    <p>See what’s happening in the world right now</p>
                </div>
                <div>
                    <p>Join Twitter today.</p>
                    <button onclick="showModal()">Sign up</button>
                </div>
            </article>
        </section>
        <!-- RIGHT SECTION END -->
    </section>
    <!-- CONTENT WRAPPER END -->
    <!-- SIGN UP MODAL START -->
    <section id="signup-modal">
        <form onsubmit="validate();signUp();return false;">
            <div>
                <i class="fab fa-twitter"></i>
                <div id="close-modal-x" onclick="closeModal()">✕</div>
            </div>
            <p>Create your account</p>
            <div>
                <p>Name</p>
                <input type="text" name="name" data-type="string" data-min="2" data-max="50" value="Adam Toth">
            </div>
            <div>
                <p>Username</p>
                <input type="text" name="username" data-type="string" data-min="2" data-max="15" value="tth_dm">
            </div>
            <div>
                <p>Email</p>
                <input id="signup-email" type="text" name="email" data-type="email" data-min="2" data-max="100" value="tothadam.97@gmail.com">
            </div>
            <div>
                <p>Password</p>
                <input id="signup-password" type="password" name="password" data-type="string" data-min="6" data-max="18" value="123456">
            </div>
            <div>
                <p>Confirm password</p>
                <input id="signup-confirm-password" type="password" name="confirmPassword" data-type="string" data-min="6" data-max="18" value="123456">
            </div>
            <button>Sign up</button>
        </form>
        <section id="close-modal" onclick="closeModal()"></section>
    </section>
    <!-- SIGN UP MODAL END -->
    <!-- FOOTER START -->
    <footer>
        <a href="#">About</a>
        <a href="#">Help Center</a>
        <a href="#">Terms of Service</a>
        <a href="#">Privacy Policy</a>
        <a href="#">Cookie Policy</a>
        <a href="#">Ads Info</a>
        <a href="#">Blog</a>
        <a href="#">Status</a>
        <a href="#">Careers</a>
        <a href="#">Brand Resources</a>
    </footer>
    <!-- FOOTER END -->

    <script src="app.js"></script>
</body>
</html>