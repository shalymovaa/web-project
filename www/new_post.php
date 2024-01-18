<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

date_default_timezone_set('Kazakhstan/Almaty');
$author=$_SESSION['user_id'];
$date = date('Y/m/d h:i:s a', time());

if ((!empty($_FILES['post_pic']) && !empty($_POST['textline'])) || (!empty($_FILES['post_pic']) && empty($_POST['textline']) ) ){
    $txt=$_POST['textline'];
    if (isset($_FILES['post_pic'])) {

        $img_name = $_FILES['post_pic']['name'];
        $img_size = $_FILES['post_pic']['size'];
        $tmp_name = $_FILES['post_pic']['tmp_name'];
        $error = $_FILES['post_pic']['error'];
    
        if ($error === 0) {
            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);
    
            $allowed_exs = array("jpg", "jpeg", "png");
            if (in_array($img_ex_lc, $allowed_exs)) {
                $new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;//this is the name to insert into the db
                $img_upload_path = 'posts/'.$new_img_name;
                move_uploaded_file($tmp_name, $img_upload_path);
            }else{
                $_SESSION['upload_status'] = "You cannot upload files of this type";
                header("Location: home.php");
            }
        }else{
            $_SESSION['upload_status'] = "Unknown error occurred!";
            header("Location: home.php");
        }
    }  
    $query="SELECT id FROM Posts WHERE author_id= '$author' and text='$txt' and created_at='$date' ";
    $result = mysqli_query($link, $query);
    while ($row = mysqli_fetch_row($result)) {
        $post=$row[0];
    }

    $sql1 = "INSERT INTO Attachments(post_id,file) VALUES ('$post','$new_img_name');";
    $result = mysqli_query($link, $sql1);
    if ($result) {
        $_SESSION['upload_status'] = 'You have successfully updated table attach';
    }else {
        $_SESSION['upload_status'] = 'Sorry. Attach';
    }
}





if (isset($_POST['publish'])) {
    
        $txt=$_POST['textline'];
        $sql = "INSERT INTO Posts(author_id,text, created_at) VALUES ('$author','$txt','$date')";
        $result = mysqli_query($link, $sql);
        if ($result) {
            echo "its ok";
            
            
        }else {
            echo "Sorry";
          
            
        }
        
    
}
?>