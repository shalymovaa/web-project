<?php
session_start();
include "config.php";
if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
include "tag_function.php";

if (isset($_POST['action'])){
    
    if ($_POST['action']==='comment'){
        if (isset($_POST['comment'])){
            
            $temp=$_POST['temp'];
            $author=$_SESSION['user_id'];
            $txt=$_POST['comment'];
            $res3=mysqli_query($link,"INSERT INTO Comments (post_id,author_id,text) VALUES ('$temp','$author','$txt');");
            
            $select=mysqli_query($link,"SELECT * FROM Comments WHERE post_id='$temp' and author_id='$author' and text='$txt' ORDER BY created_at DESC limit 1;");

            if($select){
                $rowcom=mysqli_fetch_assoc($select);
                
                echo "<div class='comment_div_".$temp."' id='commentdiv'>"."<a href='home.php'>"."@".$_SESSION['user_username']."</a>".":    ".get_tags($rowcom['text'],$link)."<br>".$rowcom['created_at']."</div>";
            
            }}}
        
        }

        
        



        
        

?>