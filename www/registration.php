<?php
session_start();

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Ur Fellow</title>
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png"> 
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png"> 
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png"> 
        <link rel="manifest" href="/site.webmanifest"> 
        <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5"> 
        <meta name="msapplication-TileColor" content="#da532c"> 
        <meta name="theme-color" content="#ffffff">
        <link rel="stylesheet" type="text/css" href="style_index.css" media="screen" />
        <script src="main.js"></script>
    </head>

    <body>
        <div class="container1"></div>

        <div class="container2">
            <div class="login_form">
                <div id="register_form" >
                    <h2>Register</h2>
                    <form action="registration_back.php" method="POST" id="register" class="input-group" enctype="multipart/form-data">

                        <div id="first">
                            <div id="form_right">
                                <input type="text" class="input-field" placeholder="First Name" name="firstname" >
                                <input type="email" class="input-field" placeholder="Email" name="email" >
                                <input type="date" class="input-field" placeholder="Date of Birth" name="birthday" >
                                <input type="text" class="input-field" placeholder="City" name="city" >
                            </div>
    
                            <div id="section">
                                <input type="text" class="input-field" placeholder="Last Name" name="lastname" >
                                <input type="tel" class="input-field" placeholder="Phone number" name="phone" >
                                male: <input type="radio" name="gender" value="m" id="gender_radio">
                                female: <input type="radio" name="gender" value="f" id="gender_radio">
                            </div>
                        </div>

                        <hr>
                        
                        <div id="section">
                            <div id="form_right">
                                <input type="text" class="input-field" placeholder="Username" name="username" >
                            </div>
    
                            <div id="form_left">
                                <input type="password" class="input-field" placeholder="Enter Password" name="password" >
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div id="section">
                            <div id="form_right">
                                <input type="text" class="input-field" placeholder="School/Uni" name="education" >
                                <input type="text" class="input-field" placeholder="Favorite Movie" name="movie" >
                            </div>
    
                            <div id="form_left">
                                <input type="text" class="input-field" placeholder="Job" name="job" >
                                <input type="text" class="input-field" placeholder="Favorite Book" name="book" >
                            </div>
                        </div>
                        <hr>
                        <input type="file" name="profile_pic">
                        <br>

                        <?php if (isset($_SESSION['registration_status']))  { ?>
                            <p class="pop_message"> <?php echo $_SESSION['registration_status']; ?> </p>
                        <?php }
                        session_unset();
                        session_destroy();
                        ?>
                        <button type="submit" class="submit-btn" name="save">Register</button>
                        <p>Already a member? <a href="index.php">Sign In</a></p>

                    </form>
                </div>

            </div>

        </div>
    </body>
</html>