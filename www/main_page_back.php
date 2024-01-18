<?php
session_start();
include "config.php";
if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
if (isset($_POST['login_search']) && isset($_POST['go_search'])) {

    function validate($data) {
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $login_search = validate($_POST['login_search']);

    if (empty($login_search)) {
        header('Location: main_page.php');
        exit();
    }else{
        $_SESSION['searching_data'] = $login_search;
        header('Location: search_people.php');
        exit();
    }
}
//accepting a friend request
if (isset($_POST['action'])) {
    $friend = $_POST['who_id'];
    $me = $_SESSION['user_id'];
    //if the action is to accept the request
    if ($_POST['action'] === 'accept_request') {
        $sql_delete_friend_request = "DELETE FROM Friend_requests WHERE request_author_id='$friend' AND requested_who_id='$me';";
        $result_delete_friend_request = mysqli_query($link, $sql_delete_friend_request);

        $sql_accept_request = "INSERT INTO Friends (friend1_id, friend2_id) VALUES ('$friend', '$me');";
        $result_accept_request = mysqli_query($link, $sql_accept_request);

        if($result_delete_friend_request and $result_accept_request) {
            echo 'ok';
        }
    //if the action is to decline a request
    }else if ($_POST['action'] === 'decline_request') {
        $sql_delete_friend_request = "DELETE FROM Friend_requests WHERE request_author_id='$friend' AND requested_who_id='$me';";
        $result_delete_friend_request = mysqli_query($link, $sql_delete_friend_request);
        if($result_delete_friend_request) {
            echo 'ok';
        }
    }else if ($_POST['action'] == 'send_request') {

        $first_input = $_SESSION['user_id'];
        $second_input = $_POST['request_who_id'];

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
