<?php
session_start();
include "config.php";
if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

function check_for_messages($link, $me, $get) {
    $sql_check_messages = "SELECT * FROM Messages WHERE (sender_id='$me' AND receiver_id='$get') OR (sender_id='$get' AND receiver_id='$me') ORDER BY sent_at DESC LIMIT 1;";
    $result_check_messages = mysqli_query($link, $sql_check_messages);
    if(mysqli_num_rows($result_check_messages) > 0){
        $row_check_messages = mysqli_fetch_assoc($result_check_messages);
        return $row_check_messages['message'];
    }else{
        return '';
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Messages</title>
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
        

        <div class="container_messages">
                <?php
                    $me = $_SESSION['user_id'];
                    $sql = "SELECT * FROM Chats WHERE (speaker1_id LIKE '$me') or (speaker2_id LIKE '$me');";
                    $result = mysqli_query($link, $sql);

                    if ($result) {
                        if (mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                                if ($row['speaker1_id'] === $_SESSION['user_id']) {
                                    $get = $row['speaker2_id'];
    
                                    $sql_get_details = "SELECT * FROM Users WHERE id='$get';";
                                    $result_get_details = mysqli_query($link, $sql_get_details);
                                    if ($result_get_details) {
                                        $row_get_details = mysqli_fetch_assoc($result_get_details);
                                        ?>
                                            <a href="open_message.php?user_id='<?php echo $row_get_details['id'];?>'&chat_id='<?php echo $row['id'];?>'">
                                                <div class="message_wrapper">
                                                    <div>
                                                        <img id="messenger_photo" src="<?php if($row_get_details['profile_pic'] !='') { echo 'profile_pics/'.$row_get_details['profile_pic'];}else{ echo 'profile_pics/blank_profile.png';} ?>"/>
                                                        <div id="message_details">
                                                            <p id="messenger"><?php echo $row_get_details['firstname'].'  '.$row_get_details['lastname'];?></p>
                                                            <p><?php echo check_for_messages($link, $me, $get); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        <?php
                                    }
                                    
                                }else if ($row['speaker2_id'] === $_SESSION['user_id']) {
                                    $get = $row['speaker1_id'];
                                    $sql_get_details = "SELECT * FROM Users WHERE id='$get';";
                                    $result_get_details = mysqli_query($link, $sql_get_details);
                                    if ($result_get_details) {
                                        $row_get_details = mysqli_fetch_assoc($result_get_details);
                                        ?>
                                            <a href="open_message.php?user_id='<?php echo $row_get_details['id'];?>'&chat_id='<?php echo $row['id'];?>'">
                                                <div class="message_wrapper">
                                                    <div>
                                                        <img id="messenger_photo" src="<?php if($row_get_details['profile_pic'] !='') { echo 'profile_pics/'.$row_get_details['profile_pic'];}else{ echo 'profile_pics/blank_profile.png';} ?>"/>
                                                        <div id="message_details">
                                                            <p id="messenger"><?php echo $row_get_details['firstname'].'  '.$row_get_details['lastname'];?></p>
                                                            <p><?php echo check_for_messages($link, $me, $get); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        <?php
                                    }
                                    
                                }
                            }
                        }else{
                            echo  '<p>No messages exchanged.</p>';
                        }
                        
                    }
                    
                    
                ?>         
        </div>

    </body>
</html>