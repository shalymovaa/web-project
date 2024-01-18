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
        <title>Message</title>
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

            <div id="talking_to">
                <?php
                    $user_id = htmlspecialchars($_GET['user_id']);
                    $chat_id = htmlspecialchars($_GET['chat_id']);
                    $sql = "SELECT * FROM Users WHERE id={$user_id}";
                    $result = mysqli_query($link, $sql);
                    if($result) {
                        $row = mysqli_fetch_assoc($result);
                    }
                ?>
                <img id="ava" src="<?php if($row['profile_pic'] !='') { echo 'profile_pics/'.$row['profile_pic'];}else{ echo 'profile_pics/blank_profile.png';} ?>"/>
                <div id="name">
                    <a href="visiting.php?user_id=<?php echo $row['id'];?>"><?php echo $row['firstname'].'  '.$row['lastname'];?><br><?php echo '@'.$row['username'];?></a>
                </div>
            </div>
    
            <div class="chat_wrapper">
                
            </div>

            <div id="new_message">
                <form>
                    <div id="message_text">
                        <input placeholder='Your Message' name="the_message" class="the_message" id="inp">
                        <input type="text" class="chat_id_number" name="chat_id_number" value="<?php echo $chat_id; ?>" hidden>
                        <input type="text" class="user_id_number" name="user_id_number" value="<?php echo $user_id; ?>" hidden>
                    </div>

                    <div id="message_send">
                        <button class="submit-btn" id="send_message" style="margin-top: 5px;">Send</button>
                    </div>
                </form>
            </div>

            <script>
                    $(document).ready(function() {
                        messagesBox = document.querySelector(".chat_wrapper");
                        messagesBox.onmouseenter= ()=>{
                            messagesBox.classList.add("active");
                        }

                        messagesBox.onmouseleave= ()=>{
                            messagesBox.classList.remove("active");
                        }
                        function scrollToBottom(){
                        messagesBox.scrollTop = messagesBox.scrollHeight;
                        }
                        $("button").on('click',function(e) {
                            e.preventDefault();
                            var message = $('#inp').val();
                            var chat_id = $('.chat_id_number').val();
                            var sending_to = $('.user_id_number').val();
                            $.post("send_message_back.php", {
                            message:message,
                            chat_id:chat_id,
                            sending_to:sending_to
                            },function(data) {
                                $('#inp').val('');
                                scrollToBottom();
                            });
                        }); 
                        
                        setInterval(()=>{
                            var chat_id = $('.chat_id_number').val();
                            $.post("show_message_back.php",{
                                chat_id:chat_id
                            },function(data){
                                $('div.chat_wrapper').html(data);
                                if (!messagesBox.classList.contains("active")) {//keep showing down if i'm not scrolling up
                                    scrollToBottom();
                                }
                                
                            });
                        },500);
                    });

                    

                    
            </script>

        </div>

    </body>
</html>