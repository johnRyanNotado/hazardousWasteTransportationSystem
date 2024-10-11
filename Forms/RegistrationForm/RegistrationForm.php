<?php
  session_start();
 ?>

<!DOCTYPE html>
<html>
  <head>
    <title>Client Registration</title>
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Footer.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/General.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Header.css">
    <link rel="stylesheet" type="text/css" href="RegistrationForm.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href=
    "https://secureservercdn.net/198.71.233.110/s0i.8f2.myftpupload.com/wp-content/uploads/2020/03/cropped-5x3-1-32x32.png">

  </head>
  <body>
    <header>
      <div class="logo_box">
        <img class="logo_header" src="HeaderImages\ECOTECH-LOGO.png">
      </div>
      <div class="tabs_box">
        <div class="username_container">
          <p class="username_container"><?php echo $_SESSION['name']; ?></p>
        </div>
        <form class="logout_form" action="phpCodes/home.php" method="post">
          <button type="submit" name="home_button">Home</button>
        </form>
        <form class="logout_form" action="phpCodes/logout.php" method="post">
          <button type="submit" name="logout_button">Logout</button>
        </form>
      </div>
    </header>

    <main>
      <div class="main">
        <div class="titleRow">
          Client Registration
        </div>
        <div class="inputRow">

          <form class="registerForm" action="phpCodes/register.inc.php" method="POST">
            <div class="upperRegistration">
              <div class="upperLeft">
                <p class="inputTitle">Company Name</p>
                <?php
                  if(isset($_GET['companyName'])){
                    echo '<input class="companyName" type="text" name="companyName" placeholder="Company Name" maxlength="40" value="'.$_GET['companyName'].'">';
                  }
                  else {
                    echo '<input class="companyName" type="text" name="companyName" placeholder="Company Name" maxlength="40">';
                  }
                 ?>
                 <p>DENR Generator ID</p>
                 <?php
                   if(isset($_GET['denrID'])){
                     echo '<input class="denrID" type="text" name="denrID" placeholder="DENR Generator ID" maxlength="20" value="'.$_GET['denrID'].'">';
                   }
                   else {
                     echo '<input class="denrID" type="text" name="denrID" placeholder="DENR Generator ID" maxlength="20">';
                   }
                  ?>

              </div>
              <div class="upperRight">
                <p>Email</p>
                <?php
                  if(isset($_GET['email'])){
                    echo '<input class="email" type="text" name="email" placeholder="Email" maxlength="30" value="'.$_GET['email'].'">';
                  }
                  else {
                    echo '<input class="email" type="text" name="email" placeholder="Email" maxlength="30">';
                  }
                ?>
                <p class="inputTitle">Contact #</p>
                <?php
                  if(isset($_GET['mobileNum'])){
                    echo '<input class="mobileNum" type="text" name="mobileNum" placeholder="(09xxxxxxxxx)" maxlength="11" value="'.$_GET['mobileNum'].'">';
                  }
                  else {
                    echo '<input class="mobileNum" type="text" name="mobileNum" placeholder="(09xxxxxxxxx)" maxlength="11">';
                  }
                 ?>

              </div>
            </div>
            <p class="addressTitle">Address</p>
            <div class="lowerRegistration">
              <div class="addressRegistration">
                <div class="addressRow">
                  <p>Region</p>
                  <select name="region" placeholder="Region">
                    <?php
                    $regions = ['NCR', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII', 'XIII', 'CAR', 'MIMAROPA'];
                      if(isset($_GET['region'])){
                        foreach($regions as $region){
                          if($region == $_GET['region']){
                            echo "<option value=$region selected>$region</option>";
                          }
                          else {
                            echo "<option value=$region>$region</option>";
                          }
                        }
                      }
                      else {
                        foreach($regions as $region){
                            echo "<option value=$region>$region</option>";
                        }
                      }
                     ?>
                  </select>
                  <p class="inputTitle">City</p>
                  <?php
                    if(isset($_GET['city'])){
                      echo '<input class="city" type="text" name="city" placeholder="City" maxlength="30" value="'.$_GET['city'].'">';
                    }
                    else {
                      echo '<input class="city" type="text" name="city" placeholder="City" maxlength="30">';
                    }
                   ?>
                   <p class="inputTitle">Barangay</p>
                   <?php
                     if(isset($_GET['barangay'])){
                       echo '<input class="barangay" type="text" name="barangay" placeholder="Barangay" maxlength="30" value="'.$_GET['barangay'].'">';
                     }
                     else {
                       echo '<input class="barangay" type="text" name="barangay" placeholder="Barangay" maxlength="30">';
                     }
                    ?>
                    <p class="inputTitle">Street Name</p>
                  <?php
                    if(isset($_GET['streetName'])){
                      echo '<input class="streetName" type="text" name="streetName" placeholder="Street Name" maxlength="30" value="'.$_GET['streetName'].'">';
                    }
                    else {
                      echo '<input class="streetName" type="text" name="streetName" placeholder="Street Name" maxlength="30">';
                    }
                   ?>
                   <p class="inputTitle">House Number</p>
                   <?php
                     if(isset($_GET['houseNum'])){
                       echo '<input class="houseNum" type="text" name="houseNum" placeholder="House Number" maxlength="5" value="'.$_GET['houseNum'].'">';
                     }
                     else {
                       echo '<input class="houseNum" type="text" name="houseNum" placeholder="House Number" maxlength="5">';
                     }
                    ?>

                </div>
              </div>
              <div class="upperRight" style="align-items: flex-start; margin-top: 14px;">
                <p class="" style="margin-top: 8px;">Account Number</p>
                <?php
                  if(isset($_GET['accountNum'])){
                    echo '<input class="houseNum" type="text" name="accountNum" placeholder="Account Number (10 digits)" maxlength="10" value="'.$_GET['accountNum'].'">';
                  }
                  else {
                    echo '<input class="houseNum" type="text" name="accountNum" placeholder="Account Number (10 digits)" maxlength="10">';
                  }
                 ?>
              </div>

              <div class="registerConfirmation">

                <button type="submit" class="registerButton" >Register</button>
                <a href="http://localhost:4000/HazardousWasteProj/Forms/ClientMainInterfaceForm/ClientMainInterfaceForm.php?"
                >Go back</a>
                <?php
                //Getting url for error checking
                  $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                  if (strpos($fullUrl, "signup=empty") == true){
                    echo "<p class='error'>You did not fill in all fields!</p>";
                  } elseif (strpos($fullUrl, "signup=invalidname") == true){
                    echo "<p class='error'>Name must be 'a-z', 'A-Z'!</p>";
                  } elseif (strpos($fullUrl, "signup=invalidaccount") == true){
                    echo "<p class='error'>Invalid account!</p>";
                  } elseif (strpos($fullUrl, "signup=passwordnotmatched") == true){
                    echo "<p class='error'>Password did not match!</p>";
                  } elseif (strpos($fullUrl, "signup=invalidmobile") == true){
                    echo "<p class='error'>Invalid Mobile#!</p>";
                  } elseif (strpos($fullUrl, "signup=success") == true){
                    echo "<p class='success'>Success!</p>";
                  } elseif (strpos($fullUrl, "update=credentialsmatch") == true){
                    echo "<p class='error'>These redentials matched other records!</p>";
                  }
                ?>
              </div>

            </div>

          </form>

        </div>

      </div>


    </main>

    <footer class="footer">
      <p>Working towards sustainability for a better and safer environment.
        | © 2020 JM Ecotech Solutions Company. All Rights Reserved.
      </p>
    </footer>
  </body>
</html>
