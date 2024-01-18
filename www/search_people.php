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
        <title>Search</title>
        <script type="text/javascript" src="js/jquery.js"></script>
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

        <?php
        function check_status($checking_id, $link) {
            $first_input = $_SESSION['user_id'];

            $sql_check_request = "SELECT * FROM Friend_requests WHERE request_author_id LIKE '$first_input' AND requested_who_id LIKE '$checking_id';";
            $result_check_request = mysqli_query($link, $sql_check_request);
            if(mysqli_num_rows($result_check_request) === 1) {
                return 'Requested';
            }

            $sql_check_request = "SELECT * FROM Friends WHERE (friend1_id LIKE '$first_input' AND friend2_id LIKE '$checking_id') OR (friend1_id LIKE '$checking_id' AND friend2_id LIKE '$first_input');";
            $result_check_request = mysqli_query($link, $sql_check_request);
            if(mysqli_num_rows($result_check_request) === 1) {
                return 'Friends';
            }  
            return 'Stranger';          
        }  
        ?>
      

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

            <div id="container_search"> 
                <div id="search_wrapper">   
                    <form action="search_people.php" method="post">
                        <div id="search_text">
                            <input placeholder='Search by login'  name="login_search" id="input" ></input>
                        </div>
    
                        <div id="search_send">
                            <input type="submit" value='Search' class="submit-btn" name="go_search">
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid-container"> 

                <?php
                    if (isset($_SESSION['searching_data'])) {
                        $searching = $_SESSION['searching_data'];
                        $sql = "SELECT * FROM Users WHERE username LIKE '%$searching%';";
                        $result = mysqli_query($link, $sql);
                        
                        if(mysqli_num_rows($result) < 1){ 
                        ?>
                            <div class="grid-item">
                                <br>No matches found<br>
                            </div>
                        <?php
                        }else{
                            while($row = mysqli_fetch_assoc($result)) {
                                if ($row['id'] === $_SESSION['user_id']) {
                                    ?>
                                        <div class="grid-item">
                                            <img src="<?php if($row['profile_pic'] !='') { echo 'profile_pics/'.$row['profile_pic'];}else{ echo 'profile_pics/blank_profile.png';} ?>">
                                            <br><a href="home.php"><?php echo $row['firstname'].'  '.$row['lastname'];?></a><br>
                                        </div>
                                    <?php
                                }else{
                                    ?>
                                        <div class="grid-item">
                                                <img src="<?php if($row['profile_pic'] !='') { echo 'profile_pics/'.$row['profile_pic'];}else{ echo 'profile_pics/blank_profile.png';}?>">
                                                <br><?php echo $row['firstname'].' '.$row['lastname'];?><br>
                                                <?php 
                                                    if (check_status($row['id'], $link) == 'Stranger') { 
                                                ?>
                                                    <button data-userid="<?php echo $row['id'];?>" id="del_<?php echo $row['id']; ?>" name="friend_request" class="friend_request">Add</button>  
                                                <?php
                                                    }else{
                                                ?>
                                                    <button data-userid="<?php echo $row['id'];?>" id="del" disabled="true"><?php echo check_status($row['id'], $link); ?></button>  
                                                <?php
                                                    }
                                                ?>
                                        </div>                                        
                                    <?php
                                }
                        
                            }
                        }    

                        unset($_SESSION['searching_data']);
                    }else if (isset($_POST['login_search']) && isset($_POST['go_search'])) {
                        
                        function validate($data) {
                            $data = trim($data);
                            $data = stripcslashes($data);
                            $data = htmlspecialchars($data);
                            return $data;
                        }
                        $login_search = validate($_POST['login_search']);
                        

                        if (empty($login_search)) {
                            header('Location: search_people.php');
                            exit();
                        }else {
                            $sql = "SELECT * FROM Users WHERE username LIKE '%$login_search%';";
                            $result = mysqli_query($link, $sql);
                            
                            if(mysqli_num_rows($result) < 1){ 
                            ?>
                                <div class="grid-item">
                                    <br>No matches found<br>
                                </div>
                            <?php
                            }else{
                                while($row = mysqli_fetch_assoc($result)) {
                                    if ($row['id'] === $_SESSION['user_id']) {
                                        ?>
                                            <div class="grid-item">
                                                <img src="<?php if($row['profile_pic'] !='') { echo 'profile_pics/'.$row['profile_pic'];}else{ echo 'profile_pics/blank_profile.png';}?>">
                                                <br><a href="home.php"><?php echo $row['firstname'].'  '.$row['lastname'];?></a><br>
                                            </div>
                                        <?php
                                    }else{
                                        ?>
                                            <div class="grid-item">
                                                <img src="<?php if($row['profile_pic'] !='') { echo 'profile_pics/'.$row['profile_pic'];}else{ echo 'profile_pics/blank_profile.png';}?>">
                                                <br><?php echo $row['firstname'].' '.$row['lastname'];?><br>
                                                <?php 
                                                    if (check_status($row['id'], $link) == 'Stranger') { 
                                                ?>
                                                    <button data-userid="<?php echo $row['id'];?>" id="del_<?php echo $row['id']; ?>" name="friend_request" class="friend_request">Add</button>  
                                                <?php
                                                    }else{
                                                ?>
                                                    <button data-userid="<?php echo $row['id'];?>" id="del" disabled="true"><?php echo check_status($row['id'], $link); ?></button>  
                                                <?php
                                                    }
                                                ?>                                      
                                            </div> 
                                        <?php
                                    }
                                }
                            }
                        }
                    }
                ?>

                <script>
                    $(document).ready(function() {
                        $(document).on('click','.friend_request', function (){

                        var requested_who_id = $(this).data('userid');

                        var action = 'send_request';

                        $.post(
                            "add_friend_action.php",
                            {
                                action:action,
                                requested_who_id:requested_who_id,
                            },
                            function(data) {
                                $('#del_'+requested_who_id).html(data);
                                $('#del_'+requested_who_id).prop("disabled",true);
                            }
                        );
                        });
                    });
                    
                </script>                
            </div>
        </div>

        
    
    </body>
</html>