<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Ur Fellow</title>
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png"> 
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png"> 
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png"> 
        <link rel="manifest" href="/site.webmanifest"> 
        <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5"> 
        <meta name="msapplication-TileColor" content="#da532c"> 
        <meta name="theme-color" content="#ffffff">

        <link rel="stylesheet" type="text/css" href="style_index.css" media="screen" />
        <script src="main.js"></script>
    </head>

    <body>
        <div class="container1"></div>

        <div class="container2">
            <div id="image_centering">
                <img id="logo_image_index" src="Logo1.png">

                <div class="login_form">
                    <div id="log_in_form">
                        <h2>Log In</h2>
                        <form  action="index_back.php" method="POST" id="login" class="input-group">
                            <input type="text" class="input-field" placeholder="Username" name="username" value="<?php if (isset($_SESSION['username_input'])) {echo $_SESSION['username_input'];}?>">
                            <input type="password" class="input-field" placeholder="Enter Password" name="password">
                            <br>
                            <?php if (isset($_SESSION['login_status']))  { ?>
                                <p class="pop_message"> <?php echo $_SESSION['login_status']; ?> </p>
                            <?php }
                            if (isset($_SESSION['stop_session'])) {
                                session_unset();
                                session_destroy();
                            }
                            ?> 
                            <button type="submit" class="submit-btn">Log In</button>
                            <p>Dont have an <a href="registration.php">Account</a> yet?</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>