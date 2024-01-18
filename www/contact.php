<?php
session_start();
include "config.php";
if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Contact</title>
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png"> 
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png"> 
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png"> 
        <link rel="manifest" href="/site.webmanifest"> 
        <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5"> 
        <meta name="msapplication-TileColor" content="#da532c"> 
        <meta name="theme-color" content="#ffffff">
        <link rel="stylesheet" type="text/css" href="style.css" media="screen" />
        <script src="main.js"></script>
        <script src="https://kit.fontawesome.com/f6dcf461c1.js" crossorigin="anonymous"></script>
        
        


       
    </head>

    <body>
      

        <div class="right_nav">

            <div id="logo_main_page">
                <img src='circle.png'>
                <img src='words.png'>
            </div>
            
           

            <div id="navigation">
                
                    <a data-id="1"  href="home.php"><img id="iconhome" src="home.png" > Home</a>
                    <a data-id="2" href="friends.php"><img id="iconfriends" src="friends.png" > Friends</a>
                    <a data-id="3" href="messages.php"><img id="iconmessages" src="messages.png" > Messages</a>
                    <a data-id="4" href="main_page.php"><img id="iconnews" src="news.png" > News</a>
                    <a data-id="5" href="contact.php"><img id="iconcontact" src="contact.png" style="height: 20px;width: 20px;opacity: 0.9;"> Contact</a>
                    <a data-id="6" href="settings.php"><img id="iconsettings" src="settings.png" > Settings</a>
                    <a data-id="7" href="logout.php"><img id="iconlogout" src="logout.png" > Log out</a>
                    
            </div>

        </div>

        <div class="left_nav1">
            <br>
            <br>

            <div id="requests1"> <div id="name1" style="color: #FF4924;">Send Email:</div></div>       
                <div id="requests1">                 

                    <div>
                        <form action="contact_back.php" method="post">
                            <label for="email_subject" id="name1">
                                Subject of the email:  
                            </label>  
                            <select id="input" name="email_subject" style="width:215px;" class="brown" >
                                    <option value="volvo">Report Technical issue</option>
                                    <option value="saab">Ask a relevant question</option>
                                    <option value="fiat">File a Complaint </option>
                                    <option value="audi">Business Inquiry</option>
                            </select>
                            <p id="name1">
                                Text to be sent : <input type="text" id="input" placeholder="Text" class="brown" name="email_text" size="32">
                            </p> 
                            <label for="email_pic" id="name1">
                                Attach the screenshot of your problem:
                            </label><input id="files" type="file" name="email_pic" >                            
                            <?php if (isset($_SESSION['email_status']))  { ?>
                            <p class="pop_message"><?php echo $_SESSION['email_status'];} ?> </p>   
                        
                            
                            <input type="submit" class="submit-btn" value="Send" style="float:right">                                        
                            

                        </form>
                    </div>

                </div>
                <br>
                <br>
                <br>
                
                <div id="requests1"> <div id="name1" style="color: #FF4924;">About Us:</div></div>        
                <div id="requests1">  
                                 

                    <p id="name1">The Developers:
                    <div style="font-size:20px;">
                        <div>Sarshaeva Kamila</div> 
                        <div>Shalymova Aigerim</div>  
                        <div>Mursakhanov Dastan</div>
                    </div>
                    
                            </p>               

                </div>
                <div id="requests1">  
                <p id="name1">This website has been made by KBTU students for WEB PROGRAMMING PROJECT.<br>
                  For any additional info send an email with your question in it, or chat 
                    directly with our <a href="visiting.php?user_id=1" style="color: #FF4924;">Admin</a>.<br>
                    For business inquiry send a message or simply call us at<a href="tel:87478147249" style="color: #FF4924;"> +7-747-814-72-49</a></p>

                <br></div>
                <br>                
                <br>
                

                </div>
                

            </div>          
            
            
                



            
        </div>

        <div class="left_nav">            
            <p id="name1">Our Address:</p>
            <div id="requests">            
            Almaty, Kazakhstan 
            <br>Tole bi, 59 st., 1st floor<br>
            <div style="width: 100%"><iframe width="100%" height="250" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" 
            src="https://maps.google.com/maps?width=100%25&amp;height=500&amp;hl=en&amp;q=Tole%20Bi%20Street%2059,%20Almaty%20050000+(KBTU)&amp;t=&amp;z=16&amp;ie=UTF8&amp;iwloc=B&amp;output=embed">
            </iframe></div>
                         
            
            </div>
            
        </div>
       
    </body>
  
    
  
</html>