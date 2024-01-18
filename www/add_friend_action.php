<?php
session_start();
include "config.php";
if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
if(isset($_POST['action'])) {
    if ($_POST['action'] == 'send_request') {

        $first_input = $_SESSION['user_id'];
        $second_input = $_POST['requested_who_id'];

        $sql_check = "SELECT * FROM Friend_requests WHERE request_author_id='$first_input' AND requested_who_id='$second_input';";
        $result_check = mysqli_query($link, $sql_check);
        if(mysqli_num_rows($result_check) === 0) {
            $sql = "INSERT INTO Friend_requests(request_author_id, requested_who_id) VALUES ('$first_input','$second_input');";
            $result = mysqli_query($link, $sql);
            if($result) {
                echo "Requested";
            }
        }
    }
}