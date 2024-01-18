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
        <title>Home</title>
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

        <div class="left_nav1">
          
                <div id="requests1">
                    <?php
                        $user_id = htmlspecialchars($_GET['user_id']);
                        $sql = "SELECT * FROM Users WHERE id={$user_id}";
                        $result = mysqli_query($link, $sql);
                        if($result) {
                            //all the information from the owner of the page that we are visiting
                            $row = mysqli_fetch_assoc($result);
                        }
                        //writing down the visitation into the table
                        $me = $_SESSION['user_id'];
                        $sql_visiting = "INSERT INTO Visitors(host_id, guest_id) VALUES('$user_id', '$me');";
                        $result_visiting = mysqli_query($link, $sql_visiting);
                        //writing down the visitation into the table
                    ?>

                    <div id="fl">
                        <img id="avahome" src="<?php if($row['profile_pic'] !='') { echo 'profile_pics/'.$row['profile_pic'];}else{ echo 'profile_pics/blank_profile.png';} ?>"/>
                    </div>

                    <div id="fl1">
                        <p id="name1"><?php echo $row['firstname'].'  '.$row['lastname'];?></p>

                        <p id="txt">Birthday: <?php echo $row['dob'];?> </p>
                        
                        <p id="txt">City: <?php echo $row['city'];?> </p>

                        <?php if ($row['education'] != '') { ?>
                            <p id="txt">Place of study: <?php echo $row['education'];?></p>
                        <?php } ?>

                        <?php if ($row['job'] != '') { ?>
                            <p id="txt">Job: <?php echo $row['job'];?></p>
                        <?php } ?>
                        
                        <?php if ($row['book'] != '') { ?>
                            <p id="txt">Favourite book: <?php echo '"'.$row['book'].'"';?></p>
                        <?php } ?>

                        <?php if ($row['movie'] != '') { ?>
                            <p id="txt">Favourite movie: <?php echo '"'.$row['movie'].'"';?></p>
                        <?php } ?>
                    </div>

                </div>
            
                <?php
                    $cnt=0;
                    $cnt1=0;
                    $me = $_SESSION['user_id'];
                    $author=$user_id;
                    $sql = "SELECT * FROM Posts WHERE author_id ='$author' ORDER BY created_at DESC;";
                    $result_search = mysqli_query($link, $sql);
                    $row_cnt_search = $result_search->num_rows;
                    if ($row_cnt_search > 0) {
                    while ($row1 = mysqli_fetch_assoc($result_search)) {?>
                        <div id="news">
                            <img id="ava" src="<?php if(isset($row['profile_pic'])) { echo 'profile_pics/'.$row['profile_pic'];}else{ echo 'profile_pics/blank_profile.png';} ?>"/>
                            <div id="name">
                                <a href="visiting.php?user_id=<?php echo $user_id;?>">
                                    <?php echo $row['firstname'].'  '.$row['lastname'];?>
                                    <br><?php echo '@'.$row['username'];?>
                                </a>
                            </div>
                                    <br><div id="name"><?php echo $row['created_at'];?></div>
                                    <p id="txt">
                                        <?php 
                                            echo get_tags($row1['text'],$link);
                                        ?>
                                    </p>
                                    <?php 
                                        $temp = $row1['id'];
                                        $sql_company = "SELECT * FROM Attachments WHERE post_id='$temp';";
                                        $result_company = mysqli_query($link, $sql_company);
                                        while ($row_company = mysqli_fetch_assoc($result_company)){
                                    ?>
                                        <img id="post" src="<?php echo 'posts/'.$row_company['file'];?>"/>
                                    <?php } ?>
                                    <br>

                                        
                                    
                                        
                                    <?php    
                                    $sql=mysqli_query($link,"SELECT * FROM Likes WHERE post_id= '$temp' and who_liked_id='$me'");
                                        if (mysqli_num_rows($sql)>0){  ?>
                                            <input type="image" data-userid="<?php echo $temp; ?>" id="like" class="like_<?php echo $temp;?>" src="red_heart.png" height="30px" width="30px"/>
                                            <?php 
                                        }
                                        else{?>
                                            <input type="image" data-userid="<?php echo $temp; ?>" id="like" class="like_<?php echo $temp;?>" src="white_heart.png" height="30px" width="30px"/>
                                            <?php }?>
                                            
                                        
                                        
                                    

                                    <?php //считываем количество лайков каждого поста//
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
                                            $sql="SELECT * FROM Users WHERE id='$comauthor' ;";
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
                                                <?echo $rowcom['created_at'];?>
                                            </div>
                                        <?php }
                                        
                                    
                                    ?>
                                    </div>
                                    <br>
                                    <form >
                                    <textarea  class="comment_<?php echo $temp; ?>"    placeholder='Add Your Comment'></textarea>
                                    <div class="btn">
                                    <input type="submit" data-userid="<?php echo $temp; ?>"  id="submitcomment" value='Comment'>
                                    
                                    </div>
                                    </form>
                                    

                                </div>
                            <?php 
                            $cnt=$cnt+1 ;
                            $cnt1=$cnt1+1; } ?>
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
    </body>
</html>