<?php
  session_start();
  if(!isset($_SESSION['name'])){
    header("Location: http://localhost:4000/HazardousWasteProj/Forms/AccessForm/AccountAccessForm.php");
  }
 ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Contract Form</title>
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Footer.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/General.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Header.css">
    <link rel="stylesheet" type="text/css" href="ContractForm.css">
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
          <p><?php echo $_SESSION['name']; ?></p>
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
      <div class="actionsTitle" >
        <p style="padding-top:82px;">Contract Form</p>
      </div>
      <hr style="margin-bottom: 20px;">
      <form class="" action="phpCodes/registerThisContract.inc.php" method="post">
        <div class="contractFormBox">
          <div class="somthing">
            <div class="leftPart">
              <p class="inputTitle">Client DENR ID</p>
              <?php
              if(isset($_GET['denrGenID'])){
                echo '<input type="text" name="denrGenID" placeholder="Client" value="'.$_GET['denrGenID'].'">';
              }
              else {
                echo '<input type="text" name="denrGenID" placeholder="Client">';
              }

               ?>

              <p>Start Date</p>
              <?php
              if(isset($_GET['startDate'])){
                echo '<input type="date" name="startDate" value="'.$_GET['startDate'].'">';
              }
              else {
                echo '<input type="date" name="startDate">';
              }

               ?>
              <p>End Date</p>
              <?php
              if(isset($_GET['endDate'])){
                echo '<input type="date" name="endDate" value="'.$_GET['endDate'].'">';
              }
              else {
                echo '<input type="date" name="endDate">';
              }

               ?>

              <p>Number of Request</p>
              <?php
              if(isset($_GET['numOfRequest'])){
                echo '<input type="number" name="numOfRequest" placeholder="Number Of Request" value="'.$_GET['numOfRequest'].'">';
              }
              else {
                echo '<input type="number" name="numOfRequest" placeholder="Number Of Request">';
              }
              ?>
              <p>Pre-Termination Fee</p>
              <?php
              if(isset($_GET['preTerminationFee'])){
                echo '<input type="number" name="preTerminationFee" placeholder="Pre-Termination Fee" value="'.$_GET['preTerminationFee'].'">';
              }
              else {
                echo '<input type="number" name="preTerminationFee" placeholder="Pre-Termination Fee">';
              }
              ?>
          </div>
          <div class="buttonsContainer">
            <button type="submit" name="button" style="width:150px;">Register</button>
            <a href="http://localhost:4000/HazardousWasteProj/Forms/RequestMainInterfaceForm/RequestMainInterfaceForm.php">Go back</a>
            <div style="display: flex; flex-direction: row; white-space: nowrap; margin: 6px;">
              <?php
                $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                if (strpos($fullUrl, "register=empty") == true){
                  echo "<p class='error'>You did not fill in all the required fields!</p>";
                } elseif (strpos($fullUrl, "clientDoesNotExist") == true){
                  echo "<p class='error'>Client does not exist!</p>";
                } elseif (strpos($fullUrl, "contract=registered") == true){
                  echo "<p class='success'>Successful!</p>";
                }
               ?>
            </div>
          </div>
          </div>
          <div class="rightPart">
            <p>Contract Description</p>
            <?php
            if(isset($_GET['contractDescription'])){
              echo '<textarea name="contractDescription" cols="20" rows="22" placeholder="Type..." >"'.$_GET['contractDescription'].'"</textarea>';
            }
            else {
              echo '<textarea name="contractDescription" cols="20" rows="22" placeholder="Type..." ></textarea>';
            }
            ?>
          </div>
        </div>
      </form>
    </main>
    <footer class="footer">
      <p>Working towards sustainability for a better and safer environment.
        | © 2020 JM Ecotech Solutions Company. All Rights Reserved.
      </p>
    </footer>
  </body>
</html>
