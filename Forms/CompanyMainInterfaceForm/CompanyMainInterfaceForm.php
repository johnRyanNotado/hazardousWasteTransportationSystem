<?php
  session_start();

  if(!isset($_SESSION['name'])){
    header("Location: http://localhost:4000/HazardousWasteProj/Forms/AccessForm/AccountAccessForm.php");
  }
 ?>

<!DOCTYPE html>
<html>
  <head>
    <title>Company Admin</title>
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Footer.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/General.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Header.css">
    <link rel="stylesheet" type="text/css" href="CompanyMainInterfaceForm.css">
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
        <form class="logout_form" action="phpCodes/tsd.php" method="post">
          <button type="submit" name="tsd_button">TSD</button>
        </form>
        <form class="logout_form" action="phpCodes/vehicles.php" method="post">
          <button type="submit" name="logout_button">Vehicles</button>
        </form>
        <form class="logout_form" action="phpCodes/waste.php" method="post">
          <button type="submit" name="waste_button">Waste</button>
        </form>
        <form class="logout_form" action="phpCodes/logout.php" method="post">
          <button type="submit" name="logout_button">Logout</button>
        </form>
      </div>
    </header>
    <main>
      <div class="">
        <div class="actionsTitle">
          <p>Actions Available</p>
        </div>
        <hr style="margin-bottom: 20px;">
        <div class="action_box">
          <div class="action_container" style="background-color:rgb(140, 223, 214); ">
            <form class="requestHistory_form" action="phpCodes/billing.php" method="post">
              <button class="action_buttons" type="submit" name="button">Billing</button>
            </form>
          </div>

          <div class="action_container" style="background-color:rgb(109, 192, 213); ">
            <form class="createRequest_form" action="phpCodes/transportation.php" method="post">

              <button class="action_buttons" type="submit" name="button">Transportations</button>
            </form>
          </div>
          <div class="action_container" style="background-color:rgb(178, 206, 222); ">
            <form class="cancelRequest_form" action="phpCodes/request.php" method="post">

              <button class="action_buttons" type="submit" name="button">Request</button>
            </form>
          </div>


          <div class="action_container" style="background-color:rgb(186, 210, 159); ">
            <form class="requestHistory_form" action="phpCodes/client.php" method="post">
              <button class="action_buttons" type="submit" name="button">Client</button>
            </form>
          </div>


          <div class="action_container" style="background-color:rgb(128, 138, 159); ">
            <form class="requestHistory_form" action="phpCodes/employees.php" method="post">
              <button class="action_buttons" type="submit" name="button">Employees</button>
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
