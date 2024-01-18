<?php
session_start();
include "config.php";
if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Settings</title>
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png"> 
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png"> 
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png"> 
        <link rel="manifest" href="/site.webmanifest"> 
        <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5"> 
        <meta name="msapplication-TileColor" content="#da532c"> 
        <meta name="theme-color" content="#ffffff">
        <link rel="stylesheet" type="text/css" href="style.css" media="screen" />
        <script src="main.js"></script>
        <script src="https://kit.fontawesome.com/f6dcf461c1.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="right_nav">
            <div id="logo_main_page">
                <img src='circle.png'>
                <img src='words.png'>
            </div>
            <div id="info">
                <img id="ava" src="<?php if(isset($_SESSION['user_profile_picture'])) { echo 'profile_pics/'.$_SESSION['user_profile_picture'];}else{ echo 'profile_pics/blank_profile.png';} ?>"/>
                <div id="name"><?php echo $_SESSION['user_firstname'].'  '.$_SESSION['user_lastname'];?><br><?php echo '@'.$_SESSION['user_username'];?></div>
            </div><br>

            <div id="navigation">
                
                    <a data-id="1"  href="home.php"><img id="iconhome" src="home.png" > Home</a>
                    <a data-id="2" href="friends.php"><img id="iconfriends" src="friends.png" > Friends</a>
                    <a data-id="3" href="messages.php"><img id="iconmessages" src="messages.png" > Messages</a>
                    <a data-id="4" href="main_page.php"><img id="iconnews" src="news.png" > News</a>
                    <a data-id="5" href="contact.php"><img id="iconcontact" src="contact.png" style="height: 20px;width: 20px;opacity: 0.9;"> Contact</a>
                    <a data-id="6" href="settings.php"><img id="iconsettings" src="settings.png" > Settings</a>
                    <a data-id="7" href="logout.php"><img id="iconlogout" src="logout.png" > Log out</a>
                    
                  
            </div>

        </div>

        <div class="container">
            
            <div id="container_change_password">
                
                <form action="settings_back.php" method="post">
                    <p>Change password</p>
                    <input type="password" id="inp" placeholder="New Password" name="new_password" size="30">
                    <input type="password" id="inp" placeholder="Repeat Password" name="new_password_again" size="30">
                    <?php if (isset($_SESSION['password_change_status']))  { ?>
                            <p class="pop_message"> <?php echo $_SESSION['password_change_status']; ?> </p>
                        <?php }
                        unset($_SESSION['password_change_status']);
                        ?> 
                    <p><input type="submit" class="submit-btn-new" value="Change" name="change"></p>
                </form>
            </div>
        </div>
    </body>
</html>