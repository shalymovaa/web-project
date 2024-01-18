<?php 
session_start();
include "config.php";

    if (isset($_POST['gender']) && isset($_POST['lastname']) && isset($_POST['city']) && isset($_POST['birthday']) && isset($_POST['firstname']) && isset($_POST['username']) && isset($_POST['phone']) && isset($_POST['email']) && isset($_POST['password'])) {
        function validate($data) {
            $data = trim($data);
            $data = stripcslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        $firstname = validate($_POST['firstname']);
        $lastname = validate($_POST['lastname']);
        $email = validate($_POST['email']);
        $phone = validate($_POST['phone']);
        $birthday = validate($_POST['birthday']);
        $city = validate($_POST['city']);
        $gender = validate($_POST['gender']);
        $username = validate($_POST['username']);
        $password = validate($_POST['password']);

        //optional data
        $education = $_POST['education'];
        $movie = $_POST['movie'];
        $job = $_POST['job'];
        $book = $_POST['book'];

        if (empty($username)) {
            $_SESSION['registration_status'] = 'Username is required';
            header("Location: registration.php");
            exit();
        }else if (empty($gender)) {
            $_SESSION['registration_status'] = 'Gender is required';
            header("Location: registration.php");
            exit();
        }else if (empty($city)) {
            $_SESSION['registration_status'] = 'City name is required';
            header("Location: registration.php");
            exit();
        }else if (empty($birthday)) {
            $_SESSION['registration_status'] = 'Date of Birth is required';
            header("Location: registration.php");
            exit();
        }else if (empty($lastname)) {
            $_SESSION['registration_status'] = 'Lastname is required';
            header("Location: registration.php");
            exit();
        }else if (empty($firstname)) {
            $_SESSION['registration_status'] = 'Firstname is required';
            header("Location: registration.php");
            exit();
        }else if (empty($phone)) {
            $_SESSION['registration_status'] = 'Phone Number is required';
            header("Location: registration.php");
            exit();
        }else if (empty($email)) {
            $_SESSION['registration_status'] = 'Email address is required';
            header("Location: registration.php");
            exit();
        }else if (empty($password)) {
            $_SESSION['registration_status'] = 'Password is required';
            header("Location: registration.php");
            exit();
        }else{
            
            // checking if username is already being used 
            $sql = "SELECT * FROM Users WHERE (username LIKE '$username');";
            $result_username = mysqli_query($link, $sql);
            $row_cnt_username = $result_username->num_rows;
            if ($row_cnt_username > 0) {
                $_SESSION['registration_status'] = 'Username is taken';
                header("Location: registration.php");
                exit();
            }

            // checking email address is already being used
            $sql = "SELECT * FROM Users WHERE (email LIKE '$email');";
            $result_email = mysqli_query($link, $sql);
            $row_cnt_email = $result_email->num_rows;
            if ($row_cnt_email > 0) {
                $_SESSION['registration_status'] = 'Email address is taken';
                header("Location: registration.php");
                exit();
            }

            // checking is the phone number is already being used
            $sql = "SELECT * FROM Users WHERE (phone LIKE '$phone');";
            $result_phone = mysqli_query($link, $sql);
            $row_cnt_phone = $result_phone->num_rows;
            if ($row_cnt_phone > 0) {
                $_SESSION['registration_status'] = 'Phone number is taken';
                header("Location: registration.php");
                exit();
            }
            //========================================image===========================================
            if (file_exists($_FILES['profile_pic']['tmp_name']) || is_uploaded_file($_FILES['profile_pic']['tmp_name'])) {

                $img_name = $_FILES['profile_pic']['name'];
                $img_size = $_FILES['profile_pic']['size'];
                $tmp_name = $_FILES['profile_pic']['tmp_name'];
                $error = $_FILES['profile_pic']['error'];
            
                if ($error === 0) {
                    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                    $img_ex_lc = strtolower($img_ex);
            
                    $allowed_exs = array("jpg", "jpeg", "png");
                    if (in_array($img_ex_lc, $allowed_exs)) {
                        $new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;//this is the name to insert into the db
                        $img_upload_path = 'profile_pics/'.$new_img_name;
                        move_uploaded_file($tmp_name, $img_upload_path);
                    }else{
                        $_SESSION['registration_status'] = "You cannot upload files of this type";
                        header("Location: registration.php");
                        exit();
                    }
                }else{
                    $_SESSION['registration_status'] = "Unknown error occurred. Try another photo!";
                    header("Location: registration.php");
                    exit();
                }
            }/* else{

            } */
            //========================================image===========================================


            if ($row_cnt_username == 0 and $row_cnt_email == 0 and $row_cnt_phone == 0) {                
                $sql = "INSERT INTO Users(firstname, lastname, dob, gender, city, username, password, email, phone, education, job, movie, book, profile_pic) VALUES ('$firstname','$lastname','$birthday','$gender','$city','$username','$password','$email','$phone','$education','$job','$movie','$book','$new_img_name');";
                $result = mysqli_query($link, $sql);
                if ($result) {
                    $_SESSION['registration_status'] = 'You have successfully registered';
                }else {
                    $_SESSION['registration_status'] = 'Sorry. Check information';
                }
                header("Location: registration.php");
                exit();
            }
        }
    }else{
        header("Location: registration.php");
        exit();
    }
?>