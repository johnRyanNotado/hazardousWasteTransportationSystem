<?php
  session_start();
  if(isset($_SESSION['name'])){
    header("Location: http://localhost:4000/HazardousWasteProj/Forms/CompanyMainInterfaceForm/CompanyMainInterfaceForm.php");
  }
 ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Log in</title>
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Footer.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/General.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Header.css">
    <link rel="stylesheet" type="text/css" href="AccountAccessForm.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href=
    "https://secureservercdn.net/198.71.233.110/s0i.8f2.myftpupload.com/wp-content/uploads/2020/03/cropped-5x3-1-32x32.png">
  </head>
  <body>
      <header>
        <div class="logo_box">
          <img class="logo_header" src="AccessFormImages\ECOTECH-LOGO.png">
        </div>
        <div class="tabs_box">
          <div class="Home_tab">
            <a class="home_link" href="http://jmecotech.ph/#banner-section">Home</a>
          </div>
          <div class="About_us_tab">
            <a href="http://jmecotech.ph/#about-us">About us </a>
          </div>
          <div class="Services_tab">
            <a href="http://jmecotech.ph/#services">Services</a>
          </div>
          <div class="P_and_T_tab">
            <a href="http://jmecotech.ph/#technologies">Process & Technology</a>
          </div>
          <div class="Company_news_tab">
            <a href="http://jmecotech.ph/#news">Company News</a>
          </div>
          <div class="Contact_us_tab">
            <a href="http://jmecotech.ph/#contact-us">Contact Us</a>
          </div>
        </div>
      </header>
      <main>
        <section class="login_box">
          <form class="log_in_grid" action="phpCodes\accessAccount.php" method="post">
            <p class="title_box">HAZARDOUS WASTE TRANSPORTATION</p>
            <input class="adminUsername" name="adminUsername" type="text" placeholder="ID"/>
            <input class="adminPassword" name="adminPassword" type="password" placeholder="Password"/>
            <button class="log_in_button" type="submit">Log in</button>
          </form>
          <div class="error">
            <?php
              $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

              if (strpos($fullUrl, "login=notmatch") == true){
                echo "<p class='error'>The username and password you entered did not match any account!</p>";
              } else if (strpos($fullUrl, "login=empty") == true){
                echo "<p class='error'>You did not fill in all the required fields!</p>";
              }
             ?>
          </div>
        </section>
      </main>

      <footer class="footer">
        <p>Working towards sustainability for a better and safer environment.
          | © 2020 JM Ecotech Solutions Company. All Rights Reserved.
        </p>
      </footer>

  </body>
</html>
