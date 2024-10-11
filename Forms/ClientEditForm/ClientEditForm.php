<?php
  session_start();
  if(!isset($_SESSION['name'])){
    header("Location: http://localhost:4000/HazardousWasteProj/Forms/AccessForm/AccountAccessForm.php");
  }
 ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Client Update</title>
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Footer.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/General.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Header.css">
    <link rel="stylesheet" type="text/css" href="ClientEditForm.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href=
    "https://secureservercdn.net/198.71.233.110/s0i.8f2.myftpupload.com/wp-content/uploads/2020/03/cropped-5x3-1-32x32.png">

  </head>
  <body>


        <?php
          include "phpCodes/dbh.inc.php";


          $sql = "SELECT c.clientID, c.denrGenID, c.clientCompanyName, c.clientContactNum, c.clientEmailAddress,
          c.clientRegion, c.clientCity, c.clientBarangay, c.clientStreetName, c.clientHouseNum, a.accountNum
          FROM client c
            LEFT JOIN account a
              ON a.clientID = c.clientID
          ORDER BY clientCompanyName, clientRegion;";
          $result = mysqli_query($conn, $sql);
          $attendantData = array();
          if (mysqli_num_rows($result) > 0){
            while ($row = mysqli_fetch_assoc($result)) {
              $companyData[] = $row;
            }
          }


          $sql = "SELECT c.clientID, c.denrGenID, c.clientCompanyName, c.clientContactNum, c.clientEmailAddress,
          c.clientRegion, c.clientCity, c.clientBarangay, c.clientStreetName, c.clientHouseNum, a.accountNum
          FROM client c
            LEFT JOIN account a
              ON a.clientID = c.clientID
          ORDER BY clientRegion, clientCity, clientBarangay, clientStreetName, clientHouseNum;";
          $result = mysqli_query($conn, $sql);
          $driverData = array();
          if (mysqli_num_rows($result) > 0){
            while ($row = mysqli_fetch_assoc($result)) {
              $addressData[] = $row;
            }
          }

         $dateLicenseExpiration = date('Ymd');
         $dateLicenseRegistration = date('Ymd');
         if(isset($_GET['dateLicenseRegistration'])){

             $dateLicenseExpiration = $_GET['dateLicenseExpiration'];
             $dateLicenseRegistration = $_GET['dateLicenseRegistration'];
         }
          ?>

        <header>
          <div class="logo_box">
            <img class="logo_header" src="HeaderImages\ECOTECH-LOGO.png">
          </div>
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
        </header>
        <main style="margin-top: 60px">
          <div class="Form">
            <div class="employeeRegisterContainer">
              <div class="titleContainer">
                <div class="actionsTitle">
                  <p style="padding-top: 0;">Update Client</p>
                </div>
                <hr style="margin-bottom: 20px;">
              </div>
              <form class="get" action="phpCodes/getThisClient.php" method="post">
                <div class="actionBox">
                  <div class="actionDataForm">

                      <p class="inputTitle">Client ID</p>
                      <?php

                      if(isset($_GET['clientID'])){
                        $clientIDSet = $_GET['clientID'];
                        echo '<input class="clientID" type="text" name="clientID" placeholder="Client ID" maxlength="18" id="employeeID" value="'.$_GET['clientID'].'">';
                      }
                      else {
                        echo '<input class="clientID" type="text" name="clientID" placeholder="Client ID" maxlength="18" id="employeeID">';
                      }
                       ?>

                      <div class="placeholder">
                        <?php
                        //Getting url for error checking
                          $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                          if (strpos($fullUrl, "find=dontexist") == true){
                            echo "<p class='error'>This client is not in the database.</p>";
                          } elseif (strpos($fullUrl, "find=notmtach") == true){
                            echo "<p class='error'>*This field is required -></p>";
                          } elseif (strpos($fullUrl, "register=invalidname") == true){
                            echo "<p class='error'>Name must be 'a-z', 'A-Z'!</p>";
                          } elseif (strpos($fullUrl, "register=invalidmobile") == true){
                            echo "<p class='error'>Invalid Contact#!</p>";
                          } elseif (strpos($fullUrl, "update=success") == true){
                            echo "<p class='success'>Registration Successful!</p>";
                          } elseif (strpos($fullUrl, "update=emptyheader") == true){
                            echo "<p class='error'>You did not fill in all the required fields!</p>";
                          } elseif (strpos($fullUrl, "update=credentialsmatch") == true){
                            echo "<p class='error'>These redentials matched other records!</p>";
                          } elseif (strpos($fullUrl, "update=Updated") == true){
                            echo "<p class='success'>Record Updated!</p>";
                          } elseif (strpos($fullUrl, "update=Deleted") == true){
                            echo "<p class='success'>Record Deleted!</p>";
                          } elseif (strpos($fullUrl, "find=empty") == true){
                            echo "<p class='error'>This field is required</p>";
                          } if (strpos($fullUrl, "signup=empty") == true){
                            echo "<p class='error'>You did not fill in all fields!</p>";
                          } elseif (strpos($fullUrl, "signup=invalidname") == true){
                            echo "<p class='error'>Name must be 'a-z', 'A-Z'!</p>";
                          } elseif (strpos($fullUrl, "signup=invalidemail") == true){
                            echo "<p class='error'>Invalid email!</p>";
                          } elseif (strpos($fullUrl, "signup=passwordnotmatched") == true){
                            echo "<p class='error'>Password did not match!</p>";
                          } elseif (strpos($fullUrl, "signup=invalidmobile") == true){
                            echo "<p class='error'>Invalid Mobile#!</p>";
                          } elseif (strpos($fullUrl, "signup=success") == true){
                            echo "<p class='success'>Success!</p>";
                          } elseif (strpos($fullUrl, "update=cannotBeDeleted") == true){
                            echo "<p class='error'>Record cannot be deleted!</p>";
                          }

                         ?>
                      </div>
                      <div class="submit">
                        <button type="submit" name="button" style="padding-left: 30px; padding-right: 30px;">Find</button>
                      </div>
                  </div>

                </div>
              </form>

              <form class="employeeEditForm" action="phpCodes\clientEdit.inc.php" method="post">
                <input type="hidden" name="clientIDSet" value="<?php echo $clientIDSet; ?>">
                <div class="employeeEditBox">
                  <div class="employeeDataForm">
                      <p class="inputTitle">Denr ID</p>
                      <?php
                        if(isset($_GET['denrID'])){
                          echo '<input class="denrGenID" type="text" name="denrGenID" placeholder="DENR ID" maxlength="20" value="'.$_GET['denrID'].'">';
                        }
                        else {
                          echo '<input class="denrGenID" type="text" name="denrGenID" placeholder="DENR ID" maxlength="20">';
                        }
                       ?>
                       <p class="inputTitle">Company Name</p>
                       <?php
                         if(isset($_GET['companyName'])){
                           echo '<input class="clientCompanyName" type="text" name="clientCompanyName" placeholder="Company Name" maxlength="40" value="'.$_GET['companyName'].'">';
                         }
                         else {
                           echo '<input class="clientCompanyName" type="text" name="clientCompanyName" placeholder="Company Name" maxlength="40">';
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

                      <p class="inputTitle">Email</p>
                      <?php
                        if(isset($_GET['email'])){
                          echo '<input class="email" type="text" name="email" placeholder="Email" maxlength="30" value="'.$_GET['email'].'">';
                        }
                        else {
                          echo '<input class="email" type="text" name="email" placeholder="Email" maxlength="30">';
                        }
                       ?>
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
                  <div class="employeeSubtypeForm" id="employeeSubtypeForm">
                  </div>

                  <div class="radioButtonsContainer" style="margin-top: 0px; margin-bottom: 14px;">
                    <div class="placeholder" >
                      <p class="inputTitle">Actions Available</p>
                    </div>
                    <div class="radio">
                      <input type="radio" name="actionBottom" value="Update" id="actionTop" onclick="getThisEmployee()">
                      <label for="Create">Update</label>
                    </div>

                    <div class="radio">
                      <input type="radio" name="actionBottom" value="Delete" id="actionTop" onclick="getThisEmployee()">
                      <label for="Update">Delete</label>
                    </div>
                  </div>
                <div class="confirmationContainer">
                  <button type="submit" id="confirmButton">Confirm</button>
                  <a href="http://localhost:4000/HazardousWasteProj/Forms/ClientMainInterfaceForm/ClientMainInterfaceForm.php?"
                  >Go back</a>
                </div>
                </form>
              </div>
            </script>

          </div>
          <div class="Report">
            <div class="titleContainer">
              <div class="actionsTitle">
                <p style="padding-top: 0;">Client Data</p>
              </div>
              <hr style="margin-bottom: 20px;">
            </div>
            <div class="reportBox">
              <div class="tableHeader" id="tableHeader">

              </div>
              <div class="tableContent" id="tableContent">

              </div>
            </div>
            <div class="radioButtonsContainer">
              <div class="radio">
                <input type="radio" name="choiceReport" value="Company" id="choiceReport" onclick="choiceReportChange()" checked>
                <label for="Create">Company</label>
              </div>

              <div class="radio">
                <input type="radio" name=choiceReport value="Address" id="choiceReport" onclick="choiceReportChange()">
                <label for="Update">Address</label>
              </div>
            </div>
          </div>
        <script type="text/javascript">
          let jsArray;
          let choiceType;
          let tableHeader = document.getElementById('tableHeader');
          let tableContent = document.getElementById('tableContent');

          function displayAllData(jsArray, choiceType){
            tableContent.className = 'tableContent';
            tableHeader.className = 'tableHeader';
            createAllHeader();
            for(i = 0; i < jsArray.length; i++){

              let clientID = document.createElement('p');
              let denrGenID = document.createElement('p');
              let clientCompanyName = document.createElement('p');
              let clientAccountNum = document.createElement('p');
              let clientContactNum = document.createElement('p');
              let clientEmail = document.createElement('p');
              let clientRegion = document.createElement('p');
              let clientCity = document.createElement('p');
              let clientBarangay = document.createElement('p');
              let clientStreetName = document.createElement('p');
              let clientHouseNum = document.createElement('p');


              clientID.innerText = jsArray[i]['clientID'];
              denrGenID.innerText = jsArray[i]['denrGenID'];
              clientCompanyName.innerText = jsArray[i]['clientCompanyName'];

              clientAccountNum.innerText = jsArray[i]['accountNum'];
              clientContactNum.innerText = jsArray[i]['clientContactNum'];
              clientEmail.innerText = jsArray[i]['clientEmail'];
              clientRegion.innerText = jsArray[i]['clientRegion'];
              clientCity.innerText = jsArray[i]['clientCity'];
              clientStreetName.innerText = jsArray[i]['clientStreetName'];
              clientBarangay.innerText = jsArray[i]['clientBarangay'];
              clientHouseNum.innerText = jsArray[i]['clientHouseNum'];


              tableContent.append(clientID);
              tableContent.append(denrGenID);
              tableContent.append(clientCompanyName);
              tableContent.append(clientAccountNum);
              tableContent.append(clientContactNum);
              tableContent.append(clientEmail);
              tableContent.append(clientRegion);
              tableContent.append(clientCity);
              tableContent.append(clientBarangay);
              tableContent.append(clientStreetName);
              tableContent.append(clientHouseNum);

            }
          }

          function createAllHeader(){
            tableHeader.innerHTML = '';
            let arrayOfHeaderTitle = ['Client ID', 'DENR ID', 'Company Name', 'AccountNum', 'Contact #',
             'Email', 'Region', 'City', 'Barangay', 'Street', 'HouseNum'];
            writeHeader(arrayOfHeaderTitle);
          }

          function writeHeader(arrayOfHeaderTitle){
            for(i = 0; i < arrayOfHeaderTitle.length; i++){
              let header = document.createElement('div');
              header.innerText = arrayOfHeaderTitle[i];
              tableHeader.append(header);
            }
          }

          function choiceReportChange() {
              tableContent.innerHTML = '';
              tableHeader.innerHTML = '';
              choiceType = document.querySelector('input[name="choiceReport"]:checked').value;
              if (choiceType == 'Address') {
                jsArray = <?php echo json_encode($addressData); ?>;
              }
              else if (choiceType == 'Company'){
                jsArray = <?php echo json_encode($companyData); ?>;
              }
              displayAllData(jsArray, choiceType);
          }

          choiceReportChange();

          // update part

          function getThisEmployee(){
              let employeeID = document.getElementById('employeeID').value;
          }

        </script>

    </main>

  </body>
</html>
