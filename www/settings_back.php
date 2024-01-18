<?php 
session_start();
include "config.php";
if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
    $user_id = $_SESSION['user_id'];

    if ( isset($_POST['new_password'])  && isset($_POST['new_password_again']) && isset($_POST['change'])) { //fields are filled

        /*========================================= clears out the information =========================================*/
        function validate($data) {
            $data = trim($data);
            $data = stripcslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        $password = validate($_POST['new_password']);
        $password_repeated = validate($_POST['new_password_again']);
        /*========================================= clears out the information =========================================*/

        /*============== if there is information that was not enterred ==============*/
        if (empty($password)) {
            $_SESSION['password_change_status'] = 'Password is required'; //for the error message display
            header("Location: settings.php"); //goes back
            exit();
        }else if (empty($password_repeated)) {
            $_SESSION['password_change_status'] = 'Repeat password is required'; //for the error message display
            $_SESSION['password_input'] = $password;
            header("Location: settings.php"); //goes back
            exit();
        }else if ($password != $password_repeated) {
            $_SESSION['password_change_status'] = 'Passwords did not match'; //for the error message display
            if (isset($_SESSION['password_input'])) {
                unset($_SESSION['password_input']);
            }
            header("Location: settings.php"); //goes back
            exit();
        /*============== if there is information that was not enterred ==============*/

        /*============================== if all the needed information was enterred ==============================*/
        }else{
            $sql = "UPDATE Users SET password='$password' WHERE id='$user_id';";
            $result = mysqli_query($link, $sql);
            if($result) {
                $_SESSION['password_change_status'] = 'Password was successfully changed'; //for the error message display
                header("Location: settings.php");
                exit();
            }else{
                $_SESSION['password_change_status'] = 'Sorry could not change password. Try later'; //for the error message display
                header("Location: settings.php");
                exit();
            }
            
        }
        /*============================== if all the needed information was enterred ==============================*/
    }else{
        header("Location: settings.php");//if all fields are empty
        exit();
    }
?>