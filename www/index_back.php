<?php 
session_start();
include "config.php";

function validate($data) {
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function check_presence($data) {
    if ($data != '') {
        return true;
    }
    return false;
}
    //give 3 tries to input the password
    if ($_SESSION['username_input'] == '') {
        file_put_contents('try.txt', 3);
    } 
    if ( isset($_POST['username']) && isset($_POST['password'])) { //fields are filled

        /*========================================= clears out the information =========================================*/
        $username = validate($_POST['username']);
        $password = validate($_POST['password']);
        /*========================================= clears out the information =========================================*/

        /*============== if there is information that was not enterred ==============*/
        if (empty($username)) {
            $_SESSION['login_status'] = 'username is required'; //for the error message display
            header("Location: index.php"); //goes back
            exit();
        }else if (empty($password)) {
            $_SESSION['login_status'] = 'password is required'; //for the error message display
            header("Location: index.php"); //goes back
            exit();
        /*============== if there is information that was not enterred ==============*/

        /*============================== if all the needed information was enterred ==============================*/
        }else{
            /*++++++++++++++++++ getting the values that match from the table ++++++++++++++++++*/
            $sql = "SELECT * FROM Users WHERE username='$username';";
            $result = mysqli_query($link, $sql);
            $row_cnt = $result->num_rows;
            /*++++++++++++++++++ getting the values that match from the table ++++++++++++++++++*/

            if ($row_cnt === 1) {//if match found
                $row = mysqli_fetch_assoc($result);
                if ($row['username'] == $username && $row['password']== $password) { // check again that it is what we need 
                    $_SESSION['user_id'] = $row['id'];// to use on the next page 
                    $_SESSION['user_firstname'] = $row['firstname']; 
                    $_SESSION['user_lastname'] = $row['lastname']; 
                    $_SESSION['user_username'] = $row['username'];
                    $_SESSION['user_birthday'] = $row['dob'];
                    $_SESSION['user_city'] = $row['city'];

                    if(check_presence($row['education'])) {
                        $_SESSION['user_education'] = $row['education'];
                    }
                    if(check_presence($row['job'])) {
                        $_SESSION['user_job'] = $row['job'];
                    }
                    if(check_presence($row['movie'])) {
                        $_SESSION['user_movie'] = $row['movie'];
                    }
                    if(check_presence($row['book'])) {
                        $_SESSION['user_book'] = $row['book'];
                    }
                    if(check_presence($row['profile_pic'])) {
                        $_SESSION['user_profile_picture'] = $row['profile_pic'];
                    }
                    unset($_SESSION['login_status']);
                    header("Location: main_page.php");//moving to the account
                    exit();
                }else if ($row['username'] == $username && $row['password'] != $password && file_get_contents('try.txt') > 1) {
                    file_put_contents('try.txt', file_get_contents('try.txt')-1);
                    $_SESSION['login_status'] = 'Incorrect password';
                    $_SESSION['username_input'] = $username; 
                    header('Location: index.php');
                    exit();
                }else if ($row['username'] == $username && $row['password'] != $password && file_get_contents('try.txt') == 1){
                    unset($_SESSION['username_input']);
                    $_SESSION['login_status'] = 'You have used all your chances. Try again later';
                    $_SESSION['stop_session'] = True;
                    header('Location: index.php');
                    exit();
                }

            }else{ // match not found
                $_SESSION['login_status'] = 'Incorrect username input'; // either you do not exits or incorrectly enterred info
                header("Location: index.php"); //returns to sign_in page 
                exit();
            }
        }
        /*============================== if all the needed information was enterred ==============================*/
    }else{
        header("Location: index.php");//if all fields are empty
        exit();
    }
?>