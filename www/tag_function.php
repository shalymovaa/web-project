<?php 
include "config.php";
function get_tags($text, $link) {
    $words = explode(" ", $text);
    for ($i = 0; $i < count($words); $i++) {
        if(preg_match('/@[a-zA-Z0-9._]+/', $words[$i])) {
            list($decoration, $username) = explode("@", $words[$i]);
            $sql_get_tag_id = "SELECT * FROM Users WHERE username='$username';";
            $result_get_tag_id = mysqli_query($link, $sql_get_tag_id);
            if(mysqli_num_rows($result_get_tag_id) == 1) {
                $row_get_tag_id = mysqli_fetch_assoc($result_get_tag_id);
                $id_needed = $row_get_tag_id['id'];
                $words[$i] = '<a href="visiting.php?user_id='.$id_needed.'" style="text-decoration:underline;color:blue;">'.$words[$i].'</a>';
            }
        }
    }
    return implode(" ", $words);
}