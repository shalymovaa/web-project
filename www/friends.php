<?php
session_start();
include "config.php";
if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

function get_chat_id($link, $me, $get) {

    //check if there is already a chat with this user 
    $sql_check_messages = "SELECT * FROM Chats WHERE (speaker1_id LIKE '$get' and speaker2_id LIKE '$me') or (speaker1_id LIKE '$me' and speaker2_id LIKE '$get');";
    $result_check_messages = mysqli_query($link, $sql_check_messages);
    if(mysqli_num_rows($result_check_messages) === 1) {
        $row_check_messages = mysqli_fetch_assoc($result_check_messages);
        return $row_check_messages['id'];
    //if there are no messages with this user then open a new one
    }else{
        $sql_create_chat = "INSERT INTO Chats(speaker1_id, speaker2_id) VALUES('$me', '$get');";
        $result_create_chat = mysqli_query($link, $sql_create_chat);
        return $result_create_chat->insert_id;
    }

}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Friends</title>
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

        <div class="container2">
            <div class="grid-container">
                    <?php
                        $me = $_SESSION['user_id'];
                        $sql = "SELECT * FROM Friends WHERE (friend1_id LIKE '$me') or (friend2_id LIKE '$me');";
                        $result = mysqli_query($link, $sql);

                        if($result) {
                            if(mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)) {
                                    if ($row['friend1_id'] === $_SESSION['user_id']) {
                                        $get = $row['friend2_id'];
                                        $sql_get_details = "SELECT * FROM Users WHERE id LIKE '$get';";
                                        $result_get_details = mysqli_query($link, $sql_get_details);
                                        $row_get_details = mysqli_fetch_assoc($result_get_details);
                                        ?>
                                            <div class="grid-item" id="my_friend_<?php echo $row_get_details['id'];?>">
                                                <img src="<?php if($row_get_details['profile_pic'] !='') { echo 'profile_pics/'.$row_get_details['profile_pic'];}else{ echo 'profile_pics/blank_profile.png';} ?>">
                                                <br>
                                                <a href="visiting.php?user_id=<?php echo $row_get_details['id'];?>"><?php echo $row_get_details['firstname'].'  '.$row_get_details['lastname'];?></a>
                                                <br>

                                                <a href="open_message.php?user_id=<?php echo $row_get_details['id'];?>&chat_id=<?php echo get_chat_id($link, $me, $get);?>">
                                                    <button id="mes" >Message</button>
                                                </a>

                                                <button data-userid="<?php echo $row_get_details['id'];?>" id="del" class="delete_friend_<?php echo $row_get_details['id'];?>">Delete</button>
                                            </div>
                                        <?php
                                    }else if ($row['friend2_id'] === $_SESSION['user_id']) {
                                        $get = $row['friend1_id'];
                                        $sql_get_details = "SELECT * FROM Users WHERE id LIKE '$get';";
                                        $result_get_details = mysqli_query($link, $sql_get_details);
                                        $row_get_details = mysqli_fetch_assoc($result_get_details);
                                        ?>
                                            <div class="grid-item" id="my_friend_<?php echo $row_get_details['id'];?>">
                                                <img src="<?php if($row_get_details['profile_pic'] !='') { echo 'profile_pics/'.$row_get_details['profile_pic'];}else{ echo 'profile_pics/blank_profile.png';} ?>">
                                                <br>
                                                <a href="visiting.php?user_id=<?php echo $row_get_details['id'];?>"><?php echo $row_get_details['firstname'].'  '.$row_get_details['lastname'];?></a>
                                                <br>

                                                <a href="open_message.php?user_id=<?php echo $row_get_details['id'];?>&chat_id=<?php echo get_chat_id($link, $me, $get);?>">
                                                    <button id="mes" >Message</button>
                                                </a>

                                                <button data-userid="<?php echo $row_get_details['id'];?>" id="del" class="delete_friend_<?php echo $row_get_details['id'];?>">Delete</button>
                                            </div>
                                        <?php
                                    }
                                }
                            }else{
                                echo  '<p>No friends added</p>';
                            }
                        }                        
                    ?>
            </div>
        </div>

        
        <script>
            $(document).ready(function() {                
                $(document).on('click','#del', function (){

                    var who_id = $(this).data('userid');
                    var action = 'delete_friend';
                    $.post(
                        "friends_back.php",
                        {
                            action:action,
                            who_id:who_id,
                        },
                        function(data) {
                            $('div#my_friend_'+who_id).remove();
                        }
                    );
                });
            });
        </script>
    </body>
</html>