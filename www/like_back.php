<?php

session_start();
include "config.php";
if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['action'])){
    
    if ($_POST['action']==='like'){
        
        $temp=$_POST['temp'];
        $author=$_SESSION['user_id'];
        $sql=mysqli_query($link,"SELECT * FROM Likes WHERE post_id= '$temp' and who_liked_id='$author';");
        if (mysqli_num_rows($sql)>0){
            $res1=mysqli_query($link,"DELETE FROM Likes WHERE post_id = '$temp' AND who_liked_id='$author'; ");
        }
        if (mysqli_num_rows($sql)==0){
            $res2=mysqli_query($link,"INSERT INTO Likes (post_id,who_liked_id) VALUES ('$temp','$author');");

        }
        if($res1){
            echo 'unliked';
        }
        if($res2){
            echo 'liked';
        }
    }
}


        
        

?>