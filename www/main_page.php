<?php
session_start();
include "config.php";
if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
include "tag_function.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>News</title>
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
                <img id="ava" src="<?php if(isset($_SESSION['user_profile_picture'])) { echo 'profile_pics/'.$_SESSION['user_profile_picture'];}else{ echo 'profile_pics/blank_profile.png';} ?>">
                <div id="name">
                    <?php echo $_SESSION['user_firstname'].'  '.$_SESSION['user_lastname'];?>
                    <br>
                    <?php echo '@'.$_SESSION['user_username'];?>
                </div>
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

            <!-- Search engine -->
            <div id="container_search">  
                    <form action="main_page_back.php" method="post">
                        <div id="search_text">
                            <input placeholder='Search by login'  name="login_search" id="input"></input>
                        </div>
    
                        <div id="search_send">
                            <input type="submit" value='Search' class="submit-btn" name="go_search">
                        </div>
                    </form>
            </div>
            <!-- Search engine -->
            
            <?php
                $cnt=0;
                $cnt1=0;
                $author=$_SESSION['user_id'];
                $sql = "SELECT * FROM Posts WHERE (author_id ='$author' or author_id in(SELECT friend1_id FROM Friends WHERE friend2_id='$author')or author_id in(SELECT friend2_id FROM Friends WHERE friend1_id='$author')) ORDER BY created_at DESC;";
                $result_search = mysqli_query($link, $sql);
                $row_cnt_search = $result_search->num_rows;

                if ($row_cnt_search > 0) {
                    while ($row = mysqli_fetch_assoc($result_search)) {
                        $auth=$row['author_id'];
                        $query="SELECT * FROM Users WHERE id='$auth';";
                        $result3=mysqli_query($link, $query);
                        $row6=mysqli_fetch_assoc($result3);
                        ?>
                        <div id="news">
                            <img id="ava" src="<?php if(!empty($row6['profile_pic'])) { echo 'profile_pics/'.$row6['profile_pic'];}else{ echo 'profile_pics/blank_profile.png';} ?>"/>
                            <div id="name">
                                <?php
                                if ($row6['id'] === $_SESSION['user_id']) {?>
                                    <a href="home.php">
                                        <?php echo $row6['firstname'].'  '.$row6['lastname'];?>
                                        <br>
                                        <?php echo '@'.$row6['username'];?>
                                    </a>
                                <?php
                                }else{?>
                                    <a href="visiting.php?user_id=<?php echo $row6['id'];?>">
                                        <?php echo $row6['firstname'].'  '.$row6['lastname'];?>
                                        <br>
                                        <?php echo '@'.$row6['username'];?>
                                    </a>
                                <?php
                                }
                                ?>
                                
                            </div>
                            <br>
                            <div id="name">
                                <?php echo $row['created_at'];?>
                            </div>
                            <p id="txt">
                                <?php echo get_tags($row['text'],$link);?>
                            </p>
                            <?php 
                                $temp = $row['id'];
                                $sql_company = "SELECT * FROM Attachments WHERE post_id='$temp';";
                                $result_company = mysqli_query($link, $sql_company);
                                while ($row_company = mysqli_fetch_assoc($result_company)){
                            ?>
                                    <img id="post" src="<?php echo 'posts/'.$row_company['file'];?>"/>
                                <?php } ?>
                                    <br> 
                                    <?php    
                                        $sql=mysqli_query($link,"SELECT * FROM Likes WHERE post_id= '$temp' and who_liked_id='$author'");
                                        if (mysqli_num_rows($sql)>0){  
                                    ?>
                                        <input type="image" data-userid="<?php echo $temp; ?>" id="like" class="like_<?php echo $temp;?>" src="red_heart.png" height="30px" width="30px"/>
                                        <?php 
                                    }else{?>
                                        <input type="image" data-userid="<?php echo $temp; ?>" id="like" class="like_<?php echo $temp;?>" src="white_heart.png" height="30px" width="30px"/>
                                    <?php }?>
                                    <?php
                                    //считываем количество лайков каждого поста//
                                    $query = "SELECT * FROM Likes WHERE post_id='$temp';";
                                    $res= mysqli_query($link, $query);                                        
                                    $row_cnt = $res->num_rows;?>
                                    <span class="likecount_<? echo $temp;?>"> <?php echo $row_cnt;?></span>
                                    <div id="all_comments" style="max-height: 200px;overflow-y:auto;" class="all_comments_<?php echo $temp;?>">
                                        <?php
                                        $query = "SELECT * FROM Comments WHERE post_id='$temp';";
                                        $res= mysqli_query($link, $query);   
                                        while ($rowcom = mysqli_fetch_assoc($res)){
                                        $comauthor=$rowcom['author_id'];
                                        $sql="SELECT * FROM Users WHERE id='$comauthor';";
                                        $res4=mysqli_query($link, $sql); 
                                        $rowc = mysqli_fetch_assoc($res4);?>
                                        <div class="comment_div_<?php $temp;?>" id="commentdiv">
                                            <?php
                                            /* is it my comment? */
                                            if ($rowc['id'] === $_SESSION['user_id']) {?>
                                                <?php echo '<a href="home.php">@'.$rowc['username'].'</a>:';echo str_repeat("&nbsp;", 5); ?><?php echo get_tags($rowcom['text'],$link);?>
                                            <?php
                                            /* is it someone else's comment? */
                                            }else {?>
                                                <?php echo '<a href="visiting.php?user_id='.$rowc['id'].'">@'.$rowc['username'].'</a>:';echo str_repeat("&nbsp;", 5); ?><?php echo get_tags($rowcom['text'],$link);?>
                                            <?php
                                            }
                                            ?>
                                            <br>
                                            <?php echo $rowcom['created_at'];?>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <br>
                                    <form >
                                        <textarea  class="comment_<?php echo $temp; ?>" placeholder='Add Your Comment'></textarea>
                                        <div class="btn">
                                            <input type="submit" data-userid="<?php echo $temp; ?>"  id="submitcomment" value='Comment'>
                                        </div>
                                    </form>
                        </div>
                        <?php 
                        $cnt=$cnt+1 ;
                        $cnt1=$cnt1+1; 
                    } ?>
                <?php } ?>
                
                
            <script>
                    $(document).ready(function() {
                        
                        $(document).on('click','#like',function(){
                            var temp=$(this).data('userid');
                            var action='like';
                            var likecount=$('.likecount_'+temp).text();
                            $.post(
                                "like_back.php",
                                {
                                    action:action,
                                    temp:temp,
                                },
                                function(data){
                                    
                                    
                                    if (data=='liked'){
                                        cl='.like_'+temp;
                                        $(cl).attr('src','red_heart.png');
                                        $('.likecount_'+temp).html(parseInt(likecount)+1);
                                        }
                                    if (data=='unliked'){
                                        cl='.like_'+temp;
                                        $(cl).attr('src','white_heart.png');
                                        $('.likecount_'+temp).html(parseInt(likecount)-1);
                                    }
                                    
                                }
                                    
                            );
                        });

                    
                        
                        
                    $(document).on('click','#submitcomment',function(e){
                            e.preventDefault();
                            console.log("da");
                            var temp=$(this).data('userid');
                            console.log(temp);
                            var action='comment';
                            
                            var comment = $(".comment_"+temp).val();
                            console.log(comment);
                            if (comment && temp){
                            
                            $.post(
                                "comment_back.php",
                                {
                                    action:action,
                                    temp:temp,
                                    comment:comment,
                                },
                                function(response){
                                    cl1='.all_comments_'+temp;
                                    $(cl1).after(response);
                                    
                                    // scrollToBottom('#all_comments');
                                    $(".comment_"+temp).val("");
                                
                                    
                                }
                                    
                            );
                        }    
                    });
                });    
            </script>

        </div>

        <!-- Part with requests and suggestions -->
        <div class="left_nav">
            <p id="txt">REQUESTS</p>
            <div class="all_requests">
            <?php
                $me = $_SESSION['user_id'];
                /* search for all my requests */
                $sql_get_requests = "SELECT * FROM friend_requests WHERE requested_who_id='$me';";
                $result_get_requests = mysqli_query($link, $sql_get_requests);
                if(mysqli_num_rows($result_get_requests) > 0) {
                    while($row_get_requests = mysqli_fetch_assoc($result_get_requests)) {
                        $get = $row_get_requests['request_author_id']; // who is requesting me
                        /* get the details about the person requesting */
                        $sql_get_details = "SELECT * FROM Users WHERE id={$get};";
                        $result_get_details = mysqli_query($link, $sql_get_details);
                        $row_get_details = mysqli_fetch_assoc($result_get_details);
                        ?>
                            <div id="requests" class="my_friend_request_<?php echo $row_get_details['id'];?>">
                                <img id="ava" src="<?php if($row_get_details['profile_pic'] !='') { echo 'profile_pics/'.$row_get_details['profile_pic'];}else{ echo 'profile_pics/blank_profile.png';} ?>"/>
                                <div id="name">
                                    <a href="visiting.php?user_id=<?php echo $row_get_details['id'];?>">
                                        <?php echo $row_get_details['firstname'].'  '.$row_get_details['lastname'];?>
                                        <br>
                                        <?php echo '@'.$row_get_details['username'];?>
                                    </a>
                                </div>
                                <br>
                                <button data-userid="<?php echo $row_get_details['id'];?>" id="accept_friend" class="submit-btn">Accept</button>
                                <button data-userid="<?php echo $row_get_details['id'];?>" id="decline_friend" class="submit-btn">Decline</button>
                            </div>
                        <?php                        
                    }
                }else{
                    ?>
                    <div id="requests">
                        <p>No requests</p>
                    </div>
                    <?php
                }
            ?>
            </div>

            

            <?php
                //not to spend time going through alternatives of friend1_id is me or friend2_id is me
                //the function gives the name of the column that $for_which_id_check is NOT kept in
                function check_which_is_which($result_array, $first_col_name, $second_col_name, $for_which_id_check) {
                    if($result_array[$first_col_name] == $for_which_id_check) {
                        return $second_col_name;
                    }else{
                        return $first_col_name;
                    }
                }
                //check if you have already made friends 
                $sql_check_friends = "SELECT * FROM Friends WHERE friend1_id='$me' OR friend2_id='$me' ORDER BY RAND() LIMIT 1;";
                $result_check_friends = mysqli_query($link, $sql_check_friends);
                if(!empty($result_check_friends) && mysqli_num_rows($result_check_friends) > 0) {
                    $row_check_friends = mysqli_fetch_assoc($result_check_friends);
                    $get = $row_check_friends[check_which_is_which($row_check_friends, 'friend1_id', 'friend2_id', $me)];

                    $sql_random_friend_of_friend = "SELECT * FROM  
                    Friends  
                    WHERE (friend1_id='$get' AND friend2_id!='$me') OR (friend1_id!='$me' AND friend2_id='$get')OR(friend1_id!='$me' AND friend2_id!='$me' ) 
                    AND friend1_id IN ((SELECT request_author_id FROM Friend_requests WHERE request_author_id='$get' AND requested_who_id!='$me'),(SELECT requested_who_id FROM Friend_requests WHERE requested_who_id!='$get' AND request_author_id!='$me')) 
                    AND friend2_id IN ((SELECT request_author_id FROM Friend_requests WHERE request_author_id='$get' AND requested_who_id!='$me'), 
                    (SELECT requested_who_id FROM Friend_requests WHERE requested_who_id!='$get' AND request_author_id!='$me'))  ORDER BY RAND() LIMIT 1;";
                    $result_random_friend_of_friend = mysqli_query($link, $sql_random_friend_of_friend);
                    if(!empty($result_random_friend_of_friend) && mysqli_num_rows($result_random_friend_of_friend) > 0) {
                        $row_random_friend_of_friend = mysqli_fetch_assoc($result_random_friend_of_friend);
                        $suggest_id = $row_random_friend_of_friend[check_which_is_which($row_random_friend_of_friend, 'friend1_id', 'friend2_id', $get)];
                        $sql_get_details = "SELECT * FROM Users WHERE id={$suggest_id};";
                        $result_get_details = mysqli_query($link, $sql_get_details);
                        $row_get_details = mysqli_fetch_assoc($result_get_details);
                        ?>
                            <div class="suggestion_<?php echo $row_get_details['id'];?>">
                            <?php echo "<script>console.log('friend of a friend');</script>"; ?>
                                <p id="txt">SUGGESTIONS</p>
                                <div id="suggestions">
                                    <img id="ava" src="<?php if($row_get_details['profile_pic'] !='') { echo 'profile_pics/'.$row_get_details['profile_pic'];}else{ echo 'profile_pics/blank_profile.png';} ?>"/>
                                    <div id="name">
                                        <a href="visiting.php?user_id=<?php echo $row_get_details['id'];?>">
                                            <?php echo $row_get_details['firstname'].'  '.$row_get_details['lastname'];?>
                                            <br>
                                            <?php echo '@'.$row_get_details['username'];?>
                                        </a>
                                    </div>
                                    <button data-userid="<?php echo $row_get_details['id'];?>" id="request_friend_sugg_<?php echo $row_choose_random_user['id'];?>" class="submit-btn-add">Add to friends </button>
                                    <button data-userid="<?php echo $row_get_details['id'];?>" id="skip_the_sugg" class="submit-btn">Skip</button>
                                </div>
                            </div>
                        <?php
                    }else{
                         //if my friends don't have any other friends                        
                         $sql_choose_random_user = "SELECT * FROM Users WHERE id !='$me' AND id NOT IN(SELECT friend1_id FROM Friends WHERE friend2_id='$me')
                         AND id NOT IN(SELECT friend2_id FROM Friends WHERE friend1_id='$me')AND id NOT IN(SELECT request_author_id FROM Friend_requests WHERE requested_who_id='$me')
                         AND id NOT IN(SELECT requested_who_id FROM Friend_requests WHERE request_author_id='$me') ORDER BY RAND() LIMIT 1";
                         $result_choose_random_user = mysqli_query($link, $sql_choose_random_user);
                         if(mysqli_num_rows($result_choose_random_user) > 0) {
                         if($result_choose_random_user) {
                             $row_choose_random_user = mysqli_fetch_assoc($result_choose_random_user);
                             ?>
                                 <div class="suggestion_<?php echo $row_choose_random_user['id'];?>">
                                 <?php echo "<script>console.log('friend does not have any friends');</script>"; ?>
                                     <p id="txt">SUGGESTIONS</p>
                                     <div id="suggestions" >
                                         <img id="ava" src="<?php if($row_choose_random_user['profile_pic'] !='') { echo 'profile_pics/'.$row_choose_random_user['profile_pic'];}else{ echo 'profile_pics/blank_profile.png';} ?>"/>
                                         <div id="name">
                                             <a href="visiting.php?user_id=<?php echo $row_choose_random_user['id'];?>">
                                                 <?php echo $row_choose_random_user['firstname'].'  '.$row_choose_random_user['lastname'];?>
                                                 <br>
                                                 <?php echo '@'.$row_choose_random_user['username'];?>
                                             </a>
                                         </div>
                                         <button data-userid="<?php echo $row_choose_random_user['id'];?>" id="request_friend_sugg_<?php echo $row_choose_random_user['id'];?>" class="submit-btn-add">Add to friends </button>
                                         <button data-userid="<?php echo $row_choose_random_user['id'];?>" id="skip_the_sugg" class="submit-btn">Skip</button>
                                     </div>
                                 </div>
                             <?php
                         }
                         }else
                         {?>
                             <p id="txt">SUGGESTIONS</p>
                             <div id="suggestions">
                             <p>No suggestions</p>
                         </div>
                         <?php
                         }
                     }
                 
                     
                
                    
                }else{
                    $sql_check_requests = "SELECT * FROM Friend_requests WHERE request_author_id='$me' OR requested_who_id='$me' ORDER BY RAND() LIMIT 1;";
                    $result_check_requests = mysqli_query($link, $sql_check_requests);
                    //Check if have any requests
                    if(!empty($result_check_requests) && mysqli_num_rows($result_check_requests) > 0) {                        
                        $row_check_requests = mysqli_fetch_assoc($result_check_requests);
                        $get = $row_check_requests[check_which_is_which($row_check_requests, 'request_author_id', 'requested_who_id', $me)];
                        $sql_choose_random_user = "SELECT*FROM Users where id!='$me' AND id NOT IN(SELECT request_author_id FROM Friend_requests WHERE request_author_id!='$me'AND requested_who_id='$me')
                        AND id NOT IN(SELECT requested_who_id FROM Friend_requests WHERE request_author_id='$me'AND requested_who_id!='$me')  ORDER BY RAND() LIMIT 1;";
                        $result_choose_random_user = mysqli_query($link, $sql_choose_random_user);
                        if(mysqli_num_rows($result_choose_random_user) > 0) {
                        if($result_choose_random_user) {
                            $row_choose_random_user = mysqli_fetch_assoc($result_choose_random_user);
                            ?>
                            <div class="suggestion_<?php echo $row_choose_random_user['id'];?>">
                                <?php echo "<script>console.log('i do not hava any friends');</script>"; ?>
                                <p id="txt">SUGGESTIONS</p>
                                <div id="suggestions" class="suggestion_<?php echo $row_choose_random_user['id'];?>">
                                    <img id="ava" src="<?php if($row_choose_random_user['profile_pic'] !='') { echo 'profile_pics/'.$row_choose_random_user['profile_pic'];}else{ echo 'profile_pics/blank_profile.png';} ?>"/>
                                    <div id="name">
                                        <a href="visiting.php?user_id=<?php echo $row_choose_random_user['id'];?>">
                                            <?php echo $row_choose_random_user['firstname'].'  '.$row_choose_random_user['lastname'];?>
                                            <br>
                                            <?php echo '@'.$row_choose_random_user['username'];?>
                                        </a>
                                    </div>
                                    <button data-userid="<?php echo $row_choose_random_user['id'];?>" id="request_friend_sugg_<?php echo $row_choose_random_user['id'];?>" class="submit-btn-add">Add to friends </button>
                                    <button data-userid="<?php echo $row_choose_random_user['id'];?>" id="skip_the_sugg" class="submit-btn">Skip</button>
                                </div>
                            </div>
                        <?php
                        }
                        }else{
                            ?><p id="txt">SUGGESTIONS</p>
                            <div id="suggestions">
                            <p>No suggestions</p>
                        </div>
                        <?php

                        }
                    }else{
                        $sql_choose_random_user = "SELECT*FROM Users where id!='$me' ORDER BY RAND() LIMIT 1;";
                        $result_choose_random_user = mysqli_query($link, $sql_choose_random_user);
                        if(mysqli_num_rows($result_choose_random_user)){
                        if($result_choose_random_user) {
                            $row_choose_random_user = mysqli_fetch_assoc($result_choose_random_user);
                            ?>
                            <div class="suggestion_<?php echo $row_choose_random_user['id'];?>">
                                <?php echo "<script>console.log('i do not hava any friends');</script>"; ?>
                                <p id="txt">SUGGESTIONS</p>
                                <div id="suggestions" class="suggestion_<?php echo $row_choose_random_user['id'];?>">
                                    <img id="ava" src="<?php if($row_choose_random_user['profile_pic'] !='') { echo 'profile_pics/'.$row_choose_random_user['profile_pic'];}else{ echo 'profile_pics/blank_profile.png';} ?>"/>
                                    <div id="name">
                                        <a href="visiting.php?user_id=<?php echo $row_choose_random_user['id'];?>">
                                            <?php echo $row_choose_random_user['firstname'].'  '.$row_choose_random_user['lastname'];?>
                                            <br>
                                            <?php echo '@'.$row_choose_random_user['username'];?>
                                        </a>
                                    </div>
                                    <button data-userid="<?php echo $row_choose_random_user['id'];?>" id="request_friend_sugg_<?php echo $row_choose_random_user['id'];?>" class="submit-btn-add">Add to friends </button>
                                    <button data-userid="<?php echo $row_choose_random_user['id'];?>" id="skip_the_sugg" class="submit-btn">Skip</button>
                                </div>
                            </div>
                            <?php
                        }
                    }else{
                        ?><p id="txt">SUGGESTIONS</p>
                            <div id="suggestions">
                            <p>No suggestions</p>
                        </div>
                        <?php
                    }
                }
                }
            ?>
            
        </div>
        <!-- Part with requests and suggestions -->
        
        <script>
            $(document).ready(function() {                
                $(document).on('click','#accept_friend', function (){
                    var who_id = $(this).data('userid');
                    var empty = '<div id="requests"><p>No requests</p></div>';
                    var action = 'accept_request';
                    $.post(
                        "main_page_back.php",
                        {
                            action:action,
                            who_id:who_id,
                        },
                        function(data) {
                            $('div.my_friend_request_'+who_id).remove();
                            if($('div.all_requests').children().length < 1) {
                                $('div.all_requests').html(empty);
                            }
                        }
                    );
                });
                $(document).on('click','#decline_friend', function (){
                    var who_id = $(this).data('userid');
                    var empty = '<div id="requests"><p>No requests</p></div>';
                    var action = 'decline_request';
                    $.post(
                        "main_page_back.php",
                        {
                            action:action,
                            who_id:who_id,
                        },
                        function(data) {
                            $('div.my_friend_request_'+who_id).remove();
                            if($('div.all_requests').children().length < 1) {
                                $('div.all_requests').html(empty);
                            }
                        }
                    );
                });

                $(document).on('click','.submit-btn-add', function (){

                    var request_who_id = $(this).data('userid');

                    var action = 'send_request';

                    $.post(
                        "main_page_back.php",
                        {
                            action:action,
                            request_who_id:request_who_id,
                        },
                        function(data) {
                            $('#request_friend_sugg_'+request_who_id).html(data);
                            $('#request_friend_sugg_'+request_who_id).prop("disabled",true);
                        }
                    );
                });

                $(document).on('click','#skip_the_sugg', function (){
                    var who_id = $(this).data('userid');
                    $('div.suggestion_'+who_id).remove();
                });
            });
        </script>


    </body>
</html>
