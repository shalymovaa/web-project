<?php
    session_start();
    include_once('config.php');
    if(!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }
    $chat_id = $_POST['chat_id'];
    $output = "";
    $sql_messages = "SELECT * FROM Messages WHERE chat_id={$chat_id} ORDER BY sent_at ASC";
    $result_messages = mysqli_query($link, $sql_messages);
    if ($result_messages) {
        while($row_messages = mysqli_fetch_assoc($result_messages)) {
            if ($row_messages['sender_id'] === $_SESSION['user_id']) { 
                $output.='<div id="my_message" class="chat_messages">'.$row_messages['message'].'</div>';
            }else {
                $output.='<div id="their_message" class="chat_messages">'.$row_messages['message'].'</div>';                
            }
        }
        echo $output; 
    }
    
?>