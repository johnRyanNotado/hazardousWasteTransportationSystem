<?php
  session_start();
  include "phpCodes/dbh.inc.php";

  if(!isset($_SESSION['name'])){
    header("Location: http://localhost:4000/HazardousWasteProj/Forms/AccessForm/AccountAccessForm.php");
  }
 ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Request Form</title>
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Footer.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/General.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Header.css">
    <link rel="stylesheet" type="text/css" href="ClientRequestForm.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href=
    "https://secureservercdn.net/198.71.233.110/s0i.8f2.myftpupload.com/wp-content/uploads/2020/03/cropped-5x3-1-32x32.png">

  </head>
  <body>
    <?php


      $sql = "SELECT * FROM waste;";
      $result = mysqli_query($conn, $sql);
      $wasteData = array();
      if (mysqli_num_rows($result) > 0){
        while ($row = mysqli_fetch_assoc($result)) {
          $wasteData[] = $row;
        }
      }
      else{
        echo "There are no data yet!";
      }

      $sql = "SELECT * FROM tsd;";
      $result = mysqli_query($conn, $sql);
      $tsdData = array();
      if (mysqli_num_rows($result) > 0){
        while ($row = mysqli_fetch_assoc($result)) {
          $tsdData[] = $row;
        }
      }
      else{
        echo "There are no data yet!";
      }


     ?>
    <header>
      <div class="logo_box">
        <img class="logo_header" src="HeaderImages\ECOTECH-LOGO.png">
      </div>
      <div class="tabs_box">
        <div class="tabs_box">
          <div class="username_container">
            <p><?php echo $_SESSION['name']; ?></p>
          </div>
          <form class="home_form" action="phpCodes/home.php" method="post">
            <button type="submit" name="home_button">Home</button>
          </form>
          <form class="logout_form" action="phpCodes/logout.php" method="post">
            <button type="submit" name="logout_button">Logout</button>
          </form>
        </div>
      </div>
    </header>
    <main>
      <form class="requestForm" action="phpCodes\requestForm.inc.php" method="post">
        <div class="titleContainer" style="display: block; margin-top:80px; margin-bottom: 20px;">
          <div class="actionsTitle" style='display: flex; justify-content: center;'>
            <p style="padding-top: 0;">Transportation Request</p>
          </div>
          <hr style="margin-bottom: 0px;">
        </div>
      <div class="displayContainer">

                <div class="wasteAssignmentContainer">

                  <div class="requestDataContainer">
                    <div class="requestFormDateBox">
                      <div class="datePart">
                        <p class="inputTitle">DENR ID</p>
                        <?php
                          if(isset($_GET['denrGenID'])){
                            echo '<input class="denrGenID" type="text" name="denrGenID" placeholder="DENR ID" maxlength="20" value="'.$_GET['denrGenID'].'" readonly>';
                          }
                          else {
                            echo '<input class="denrGenID" type="text" name="denrGenID" placeholder="DENR ID" maxlength="20">';
                          }
                         ?>
                      </div>
                    </div>
                    <div style="display: flex; flex-direction: row; column-gap: 15px;">
                      <button type="submit" name="findButton" value="find" style="margin-bottom: 20px;">Find</button>
                      <button type="submit" name="findButton" value="cancel" style="margin-bottom: 20px;">Cancel</button>
                    </div>

                    <div class="requestFormDateBox">
                      <div class="datePart">
                        <p class="inputTitle">Date</p>
                        <?php
                          if(isset($_GET['specDateOfPickUp'])){
                            echo '<input class="specDateOfPickUp" type="date" name="specDateOfPickUp" value="'.$_GET['specDateOfPickUp'].'">';
                          }
                          else {
                            echo '<input class="specDateOfPickUp" type="date" name="specDateOfPickUp">';
                          }
                          ?>
                      </div>

                    </div>
                    <div class="confirmationContainer">
                      <button type="submit" name="findButton" value="confirm"style="background-color: lightgreen; width: 200px;">Confirm</button>
                      <a href="http://localhost:4000/HazardousWasteProj/Forms/RequestMainInterfaceForm/RequestMainInterfaceForm.php?"
                      >Go back</a>
                    </div>

                    <div style="display: flex; flex-direction: row; white-space: nowrap; margin: 20px;">
                      <?php
                        $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                        if (strpos($fullUrl, "client=dontexist") == true){
                          echo "<p class='error'>This client is not in the database.</p>";
                        } elseif (strpos($fullUrl, "client=empty") == true){
                          echo "<p class='error'>You did not fill in all the required fields!</p>";
                        } elseif (strpos($fullUrl, "register=invalidname") == true){
                          echo "<p class='error'>TSD Name must be 'a-z', 'A-Z'!</p>";
                        } elseif (strpos($fullUrl, "register=success") == true){
                          echo "<p class='success'>Success!</p>";
                        } elseif (strpos($fullUrl, "register=invalidContract") == true){
                          echo "<p class='error'>TSD Name must be 'a-z', 'A-Z'!</p>";
                        }
                       ?>
                    </div>
                  </div>

                </div>


        <div class="containerForm">

          <div class="identificationFormContainer">

              <div class="requestFormBox">
                <div class="identificationFlex">
                  <div class="identificationPart">
                    <p class="inputTitle">Company</p>
                    <?php
                      if(isset($_GET['clientCompanyName'])){
                        echo '<input class="clientCompanyName" type="text" name="clientCompanyName" placeholder="Company Name" maxlength="30" value="'.$_GET['clientCompanyName'].'" readonly>';
                      }
                      else {
                        echo '<input class="clientCompanyName" type="text" name="clientCompanyName" placeholder="Company Name" maxlength="30">';
                      }
                      ?>
                    <p class="inputTitle">Contact #</p>
                    <?php
                      if(isset($_GET['clientContactNum'])){
                        echo '<input class="clientContactNum" type="text" name="clientContactNum" placeholder="(09xxxxxxxxx)" maxlength="11" value="'.$_GET['clientContactNum'].'"readonly>';
                      }
                      else {
                        echo '<input class="clientContactNum" type="text" name="clientContactNum" placeholder="(09xxxxxxxxx)" maxlength="11">';
                      }
                     ?>

                    <p class="inputTitle">Email</p>
                    <?php
                      if(isset($_GET['clientEmail'])){
                        echo '<input class="clientEmail" type="text" name="clientEmail" placeholder="Email" maxlength="30" value="'.$_GET['clientEmail'].'" readonly>';
                      }
                      else {
                        echo '<input class="clientEmail" type="text" name="clientEmail" placeholder="Email" maxlength="30">';
                      }
                      ?>
                  </div>
                  <div class="addressPart">
                    <p class="inputTitle">Region</p>

                      <?php
                      $regions = ['NCR', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII', 'XIII', 'CAR', 'MIMAROPA'];
                        if(isset($_GET['clientRegion'])){
                          echo '<input type="hidden" name="clientRegionSet" value="'.$_GET['clientRegion'].'">';
                          echo '<select name="clientRegion" placeholder="Region" disabled>';

                          foreach($regions as $region){
                            if($region == $_GET['clientRegion']){
                              echo "<option value=$region selected>$region</option>";
                            }
                            else {
                              echo "<option value=$region>$region</option>";
                            }
                          }
                        }
                        else {
                          echo '<input type="hidden" name="clientRegionSet">';
                          echo '<select name="clientRegion" placeholder="Region">';
                          foreach($regions as $region){
                              echo "<option value=$region>$region</option>";
                          }
                        }
                       ?>

                    </select>
                    <p class="inputTitle">City</p>
                    <?php
                      if(isset($_GET['clientCity'])){
                        echo '<input class="clientCity" type="text" name="clientCity" placeholder="City" maxlength="30" value="'.$_GET['clientCity'].'" readonly>';
                      }
                      else {
                        echo '<input class="clientCity" type="text" name="clientCity" placeholder="City" maxlength="30">';
                      }
                     ?>
                    <p class="inputTitle">Barangay</p>
                    <?php
                      if(isset($_GET['clientBarangay'])){
                        echo '<input class="clientBarangay" type="text" name="clientBarangay" placeholder="Barangay" maxlength="30" value="'.$_GET['clientBarangay'].'" readonly>';
                      }
                      else {
                        echo '<input class="clientBarangay" type="text" name="clientBarangay" placeholder="Barangay" maxlength="30">';
                      }
                     ?>
                    <p class="inputTitle">Street Name</p>
                    <?php
                      if(isset($_GET['clientStreetName'])){
                        echo '<input class="clientStreetName" type="text" name="clientStreetName" placeholder="Street Name" maxlength="30" value="'.$_GET['clientStreetName'].'" readonly>';
                      }
                      else {
                        echo '<input class="clientStreetName" type="text" name="clientStreetName" placeholder="Street Name" maxlength="30">';
                      }
                     ?>
                    <p class="inputTitle">House Number</p>
                    <?php
                      if(isset($_GET['clientHouseNum'])){
                        echo '<input class="clientHouseNum" type="text" name="clientHouseNum" placeholder="House Number" maxlength="5" value="'.$_GET['clientHouseNum'].'" readonly>';
                      }
                      else {
                        echo '<input class="clientHouseNum" type="text" name="clientHouseNum" placeholder="House Number" maxlength="5">';
                      }
                     ?>

                  </div>

                </div>
              </div>

              <div class="requestFormBox">
                <div class="identificationFlex" style="grid-template-columns: 1fr; padding-left: 40px;padding-right: 40px;">
                  <div class="identificationPart" >
                    <p class="inputTitle">Contract ID</p>
                    <?php
                      if(isset($_GET['contractID'])){
                        echo '<input class="contractID" type="text" name="contractID" placeholder="Contract ID (Optional)" maxlength="18" value="'.$_GET['contractID'].'">';
                      }
                      else {
                        echo '<input class="contractID" type="text" name="contractID" placeholder="Contract ID (Optional)" maxlength="18">';
                      }
                     ?>
                    <p class="inputTitle">TSD DENR ID</p>
                    <?php

                        echo '<input type="hidden" name="clientRegionSet">';
                        echo '<select name="tsdID" placeholder="Region" >';
                        foreach($tsdData as $tsd){
                            $tsdID = $tsd['tsdID'];
                            $tsdDenrID = $tsd['tsdDenrID'];
                            $tsdName = $tsd['tsdName'];
                            echo "<option value=$tsdID style='widht: 10px;'>$tsdDenrID : $tsdName</option>";
                        }

                     ?>

                   </select>

                  </div>

                </div>
              </div>

          </div>
        </div>


      </div>


      </form>

    </main>
    <footer class="footer">
      <p>Working towards sustainability for a better and safer environment.
        | © 2020 JM Ecotech Solutions Company. All Rights Reserved.
      </p>
    </footer>




    </script>


  </body>
</html>
