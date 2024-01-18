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
                    <div id="fl">
                        <img id="avahome" src="<?php if(isset($_SESSION['user_profile_picture'])) { echo 'profile_pics/'.$_SESSION['user_profile_picture'];}else{ echo 'profile_pics/blank_profile.png';} ?>"/>
                    </div>

                    <div id="fl1">
                        <p id="name1"><?php echo $_SESSION['user_firstname'].'  '.$_SESSION['user_lastname'];?></p>

                        <p id="txt">Birthday: <?php echo $_SESSION['user_birthday'];?> </p>
                        
                        <p id="txt">City: <?php echo $_SESSION['user_city'];?> </p>

                        <?php if (isset($_SESSION['user_education'])) { ?>
                            <p id="txt">Place of study: <?php echo $_SESSION['user_education'];?></p>
                        <?php } ?>

                        <?php if (isset($_SESSION['user_job'])) { ?>
                            <p id="txt">Job: <?php echo $_SESSION['user_job'];?></p>
                        <?php } ?>
                        
                        <?php if (isset($_SESSION['user_book'])) { ?>
                            <p id="txt">Favourite book: <?php echo '"'.$_SESSION['user_book'].'"';?></p>
                        <?php } ?>

                        <?php if (isset($_SESSION['user_movie'])) { ?>
                            <p id="txt">Favourite movie: <?php echo '"'.$_SESSION['user_movie'].'"';?></p>
                        <?php } ?>
                    </div>
                </div>

                <div id="container_new_post">
                    <form action="" enctype="multipart/form-data" method="POST">
                        <div id="new_post_text" style="margin-left:17px;">
                            <input type="text" id="inp" placeholder="New Post" name="textline"  size="30">
                        </div>

                        <div id="new_post_buttons">
                            <div id="button_space1">
                                <label for="files" class="attach-btn"><img src="attach.png" style="width: 23px;height: 23px; margin-top: 5px;"></label>
                                <input id="files" style="visibility:hidden;" type="file" name="post_pic[]" multiple>
                            </div>
                            <div id="button_space2">
                                <input type="submit" class="submit-btn-new" value="Publish" name="publish">
                            </div>
                        </div>
                    </form>
                </div>
                <?php
                    date_default_timezone_set('Kazakhstan/Almaty');
                    $author=$_SESSION['user_id'];
                    $date = date('Y/m/d h:i:s ', time());
                    
                    if (isset($_POST['publish'])) {
                        
                        if (!empty($_POST['textline']) || !empty($_FILES['post_pic'])) {
                        $txt=$_POST['textline'];
                        $sql = "INSERT INTO Posts(author_id,text, created_at) VALUES ('$author','$txt','$date');";
                        $result = mysqli_query($link, $sql);
                        $query="SELECT * FROM Posts WHERE author_id= '$author' and text='$txt' and created_at='$date' ;";
                        $result1 = mysqli_query($link, $query);
                        $row = mysqli_fetch_assoc($result1);
                        $post=$row['id'];
                        }
                        if (isset($_FILES['post_pic'])) {
                            $totalfiles = count($_FILES['post_pic']['name']);

                            // Looping over all files
                            for($i=0;$i<$totalfiles;$i++){
                                $filename = $_FILES['post_pic']['name'][$i];
                                $img_ex = pathinfo($filename, PATHINFO_EXTENSION);
                                $img_ex_lc = strtolower($img_ex);
                                $new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;//this is the name to insert into the db
                                // Upload files and store in database
                                if(move_uploaded_file($_FILES["post_pic"]["tmp_name"][$i],'posts/'.$new_img_name)){
                                // Image db insert sql
                                    $insert = "INSERT into Attachments(post_id,file) values('$post','$new_img_name');";
                                    if(mysqli_query($link, $insert)){
                                        echo 'Data inserted successfully';
                                    }
                                    else{
                                        echo 'Error: '.mysqli_error($link);
                                     }
                                }
                                else{
                                    echo 'Error in uploading file - '.$_FILES['post_pic']['name'][$i].'<br/>';
                                } 
                            }
                        }
                    }        
                ?>

                <?php
                    $cnt=0;
                    $cnt1=0;
                    $author=$_SESSION['user_id'];
                    $sql = "SELECT * FROM Posts WHERE author_id ='$author' ORDER BY created_at DESC;";
                    $result_search = mysqli_query($link, $sql);
                    $row_cnt_search = $result_search->num_rows;
                    if ($row_cnt_search > 0) {
                        while ($row = mysqli_fetch_assoc($result_search)) {?>
                            <div id="news">
                                <img id="ava" src="<?php if(isset($_SESSION['user_profile_picture'])) { echo 'profile_pics/'.$_SESSION['user_profile_picture'];}else{ echo 'profile_pics/blank_profile.png';} ?>"/>
                                <div id="name">
                                    <a href="home.php">
                                        <?php echo $_SESSION['user_firstname'].'  '.$_SESSION['user_lastname'];?>
                                        <br>
                                        <?php echo '@'.$_SESSION['user_username'];?>
                                    </a>
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
                                    if (mysqli_num_rows($sql)>0){  ?>
                                        <input type="image" data-userid="<?php echo $temp; ?>" id="like" class="like_<?php echo $temp;?>" src="red_heart.png" height="30px" width="30px"/>
                                    <?php 
                                    }else{
                                    ?>
                                        <input type="image" data-userid="<?php echo $temp; ?>" id="like" class="like_<?php echo $temp;?>" src="white_heart.png" height="30px" width="30px"/>
                                    <?php
                                    }?>
                                    <?php //считываем количество лайков каждого поста//
                                    $query = "SELECT * FROM Likes WHERE post_id='$temp';";
                                    $res= mysqli_query($link, $query);                                        
                                    $row_cnt = $res->num_rows;?>
                                    <span class="likecount_<? echo $temp;?>"> <?php echo $row_cnt;?></span>
                                    <div id="all_comments"  class="all_comments_<?php echo $temp;?>">
                                        <?php
                                            $query = "SELECT * FROM Comments WHERE post_id='$temp' ORDER BY created_at DESC;";
                                            $res= mysqli_query($link, $query);   
                                            while ($rowcom = mysqli_fetch_assoc($res)){
                                                $comauthor=$rowcom['author_id'];
                                                $sql="SELECT * FROM Users WHERE id='$comauthor';";
                                                $res4=mysqli_query($link, $sql); 
                                                $rowc = mysqli_fetch_assoc($res4);?>
                                                <div class="comment_div_<?php $temp;?>" id="commentdiv">
                                                    <?php
                                                    if ($rowc['id'] === $_SESSION['user_id']) {?>
                                                        <?php echo '<a href="home.php">@'.$rowc['username'].'</a>:';echo str_repeat("&nbsp;", 5); ?><?php echo get_tags($rowcom['text'],$link);?>
                                                    <?php
                                                    }else {?>
                                                        <?php echo '<a href="visiting.php?user_id='.$rowc['id'].'">@'.$rowc['username'].'</a>:';echo str_repeat("&nbsp;", 5); ?><?php echo get_tags($rowcom['text'],$link);?>
                                                    <?php
                                                    }
                                                    ?>
                                                        <br>
                                                        <?echo $rowcom['created_at'];?>
                                                </div>
                                            <?php } ?>
                                    </div>
                                    <br>
                                    <form>
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

        


        </div>
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
                                //$( cl1 ).append( response );
                                // scrollToBottom('#all_comments');
	                            $(".comment_"+temp).val("");
                            }
                        );
                    }    
                });
            });    
        </script> 

        <div class="left_nav">
            <?php if(isset($_SESSION['user_city'])) {
                echo "<p id='txt'>WEATHER</p>       
                <div id='requests'>
                    <div class='weatherWidget' ></div>
                </div>";} ?>
            <p id="txt">VISITORS</p>
            <div id="requests">


                <?php
                    $me = $_SESSION['user_id'];
                    $sql_check_visitors ="SELECT DISTINCT guest_id FROM Visitors WHERE host_id='$me' AND guest_id!='$me' AND visitied_at >= DATE_SUB(CURRENT_DATE, INTERVAL 1 week) ORDER BY visitied_at DESC;";
                    $result_check_visitors = mysqli_query($link, $sql_check_visitors);
                    if(mysqli_num_rows($result_check_visitors) > 0) {
                        while($row_check_visitors = mysqli_fetch_assoc($result_check_visitors)) {
                            $get = $row_check_visitors['guest_id'];

                            $sql_get_details = "SELECT * FROM Users WHERE id LIKE '$get';";
                            $result_get_details = mysqli_query($link, $sql_get_details);
                            $row_get_details = mysqli_fetch_assoc($result_get_details);
                            ?>
                                <img id="ava" src="<?php if($row_get_details['profile_pic'] !='') { echo 'profile_pics/'.$row_get_details['profile_pic'];}else{ echo 'profile_pics/blank_profile.png';} ?>"/>
                                <a href="visiting.php?user_id=<?php echo $row_get_details['id'];?>">
                                    <div id="name">
                                        <?php echo $row_get_details['firstname'].'  '.$row_get_details['lastname'];?>
                                        <br><?php echo '@'.$row_get_details['username'];?>
                                    </div>
                                </a>
                                <br>
                            <?php
                        }
                    }else{
                        ?>
                        <p>There were no visitors in a past week</p>
                        <?php
                    }

                ?>
            </div>
        </div>

        <script>
            window.weatherWidgetConfig =  window.weatherWidgetConfig || [];
            window.weatherWidgetConfig.push({
                selector:".weatherWidget",
                apiKey:"FKLFMPVBWPN5MMH3BEA8YJVGY", //Sign up for your personal key
                location:"<?php echo $_SESSION['user_city'];?>" , //Enter an address
                unitGroup:"metric", //"us" or "metric"
                forecastDays:5, //how many days forecast to show
                title:"<?php echo $_SESSION['user_city'];?>" , //optional title to show in the 
                showTitle:true, 
                showConditions:true
            });
            
            (function() {
            var d = document, s = d.createElement('script');
            s.src = 'https://www.visualcrossing.com/widgets/forecast-simple/weather-forecast-widget-simple.js';
            s.setAttribute('data-timestamp', +new Date());
            (d.head || d.body).appendChild(s);
            })();

        </script>
    </body>
</html>