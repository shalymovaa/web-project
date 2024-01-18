<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['action'])) {
    if ($_POST['action'] === 'delete_friend') {
        $friend = $_POST['who_id'];
        $me = $_SESSION['user_id'];

        $sql_delete_friend = "DELETE FROM Friends WHERE (friend1_id='$friend' AND friend2_id='$me') OR (friend1_id='$me' AND friend2_id='$friend');";
        $result_delete_friend = mysqli_query($link, $sql_delete_friend);
        if($result_delete_friend) {
            echo 'ok';
        }
    }
}
