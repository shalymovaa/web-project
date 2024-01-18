<?php
$db_server = 'localhost';
$db_username = 'root';
$db_password = 'Kamila1910!';
$db_name = 'final';
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect($db_server, $db_username, $db_password, $db_name);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>