<?php
session_start();
include "config.php";
if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
if(isset($_POST['message'])) {
    $sending = $_SESSION['user_id'];
    $receiving = $_POST['sending_to'];
    $chat_id = $_POST['chat_id'];
    $message = $_POST['message'];
    
    
    $sql = "INSERT INTO Messages(sender_id, receiver_id, message,chat_id) VALUES ({$sending},{$receiving},'{$message}', {$chat_id});";
    $result = mysqli_query($link, $sql);
    if($result) {
        echo 'ok';
    }
}
?>