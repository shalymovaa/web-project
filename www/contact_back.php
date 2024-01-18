<?php 
session_start();
include "config.php";
if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['email_subject'])&& isset($_POST['email_text'])) {
    function validate($data) {
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }    
    $email_subject = validate($_POST['email_subject']);
    $email_text = validate($_POST['email_text']);

    if (empty($email_subject)) {
        $_SESSION['email_status'] = 'Please choose the email subject';
        header("Location: contact.php");
        exit();
    }else if (empty($email_text)) {
        $_SESSION['email_status'] = 'Please enter an email text';
        header("Location: contact.php");
        exit();
    }else{
        if (isset($_FILES['email_pic'])) {

            $img_name = $_FILES['email_pic']['name'];
            $img_size = $_FILES['email_pic']['size'];
            $tmp_name = $_FILES['email_pic']['tmp_name'];
            $error = $_FILES['email_pic']['error'];
        
            if ($error === 0) {
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_lc = strtolower($img_ex);
        
                $allowed_exs = array("jpg", "jpeg", "png");
                if (in_array($img_ex_lc, $allowed_exs)) {
                    $img_name = $_FILES['email_pic']['name'];
                }else{
                    $_SESSION['email_status'] = "You cannot upload files of this type";
                    header("Location: contact.php");
                    exit();
                }
            }else{
                $_SESSION['email_status'] = "Unknown error occurred!";
                header("Location: contact.php");
                exit();
            }
        }else
        {
            $img_name = "white.png";
        }

    }
    $to = "m_dastan99@mail.ru";# ,somebodyelse@example.com"
    $subject = $email_subject;
    $subject = "=?utf-8?B?".base64_encode($subject)."?=";
    
    $message = "
    <html>
    <head>
    <title>HTML email</title>
    </head>
    <body>
    <table>
    <tr>
    <th>Firstname</th>
    <th>Lastname</th>
    </tr>
    <tr>
    <td>".$_SESSION['user_firstname']."</td>
    <td>".$_SESSION['user_lastname']."</td>
    </tr>
    </table>
    <p>".$email_text."</p>
    <p><img src='".$img_name."'></p>    
    </body>
    </html>
    ";
    
    
    $headers = "From: <".$_SESSION['user_email'].">". "\r\n";
    $headers .= "Reply-to: <".$_SESSION['user_email'].">". "\r\n";
    $headers .= "Content-type:text/html;charset=utf-8" . "\r\n";
    

    

    if(empty($_SESSION['email_count'])){
        $_SESSION['email_count'] = 0;
    }
    if($_SESSION['email_count'] == 0){     
        mail($to,$subject,$message,$headers);
        $_SESSION['email_status'] = "Your email has been sent!";
        header("Location: contact.php");
        $_SESSION['email_count']++;
    }else{
        $_SESSION['email_status'] = "Your email has already been sent!<br>Try again later... ";
        header("Location: contact.php");
        exit();        
    }
}
else
{
    header("Location: contact.php");
    $_SESSION['email_status'] = "Error!";
    exit();
}
?>

