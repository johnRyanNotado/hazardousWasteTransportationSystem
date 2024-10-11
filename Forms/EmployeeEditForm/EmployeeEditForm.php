<?php
  session_start();
  if(!isset($_SESSION['name'])){
    header("Location: http://localhost:4000/HazardousWasteProj/Forms/AccessForm/AccountAccessForm.php");
  }
 ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Register Employee</title>
    <title>ContractForm</title>
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Footer.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/General.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Header.css">
    <link rel="stylesheet" type="text/css" href="EmployeeEditForm.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href=
    "https://secureservercdn.net/198.71.233.110/s0i.8f2.myftpupload.com/wp-content/uploads/2020/03/cropped-5x3-1-32x32.png">

  </head>
  <body>
    <?php
      include "phpCodes/dbh.inc.php";

      $sql = "SELECT * FROM employee ORDER BY employeeLastName, employeeFirstName;";
      $result = mysqli_query($conn, $sql);
      $allData = array();
      if (mysqli_num_rows($result) > 0){
        while ($row = mysqli_fetch_assoc($result)) {
          $allData[] = $row;
        }
      }

      $sql = "SELECT * FROM employee WHERE employeeType = 'A';";
      $result = mysqli_query($conn, $sql);
      $attendantData = array();
      if (mysqli_num_rows($result) > 0){
        while ($row = mysqli_fetch_assoc($result)) {
          $attendantData[] = $row;
        }
      }


      $sql = "SELECT employeeID, employeeLastName, employeeFirstName, employeeContactNum,
      employeeEmail, employeeRegion, employeeCity, employeeBarangay, employeeStreetName,
      employeeHouseNum, dateLicenseRegistration, dateLicenseExpiration
        FROM employee, driver
        WHERE employeeType = 'D' AND employeeID = dEmployeeID
        ORDER BY dateLicenseExpiration DESC;";
      $result = mysqli_query($conn, $sql);
      $driverData = array();
      if (mysqli_num_rows($result) > 0){
        while ($row = mysqli_fetch_assoc($result)) {
          $driverData[] = $row;
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
    <main>
      <div class="Form">
        <div class="employeeRegisterContainer">
          <div class="titleContainer">
            <div class="actionsTitle">
              <p style="padding-top: 0;">Update Employee</p>
            </div>
            <hr style="margin-bottom: 20px;">
          </div>
          <form class="get" action="phpCodes/getThisEmployee.php" method="post">
            <div class="actionBox">
              <div class="actionDataForm">

                  <p class="inputTitle">Employee ID</p>
                  <?php

                  if(isset($_GET['employeeID'])){
                    $employeeIDSet = $_GET['employeeID'];
                    echo '<input class="employeeID" type="text" name="employeeID" placeholder="Employee ID" maxlength="18" id="employeeID" value="'.$_GET['employeeID'].'">';
                  }
                  else {
                    echo '<input class="employeeID" type="text" name="employeeID" placeholder="Employee ID" maxlength="18" id="employeeID">';
                  }
                   ?>

                  <div class="placeholder">
                    <?php
                    //Getting url for error checking
                      $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                      if (strpos($fullUrl, "find=dontexist") == true){
                        echo "<p class='error'>This employee is not in the database.</p>";
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
                        echo "<p class='error'>This redentials matched other records!</p>";
                      } elseif (strpos($fullUrl, "update=Updated") == true){
                        echo "<p class='success'>Record Updated!</p>";
                      } elseif (strpos($fullUrl, "update=Deleted") == true){
                        echo "<p class='success'>Record Deleted!</p>";
                      } elseif (strpos($fullUrl, "find=empty") == true){
                        echo "<p class='success'</p>";
                      } elseif (strpos($fullUrl, "update=emptyheader") == true){
                        echo "<p class='error'>You did not fill in all the required fields!</p>";
                      } elseif (strpos($fullUrl, "update=credentialsmatch") == true){
                        echo "<p class='error'>This redentials matched other records!</p>";
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

          <form class="employeeEditForm" action="phpCodes\employeeEdit.inc.php" method="post">
            <input type="hidden" name="employeeIDSet" value="<?php echo $employeeIDSet ?>">
            <div class="employeeEditBox">
              <div class="employeeDataForm">
                  <p class="inputTitle">First Name</p>
                  <?php
                    if(isset($_GET['employeeFirstName'])){
                      echo '<input class="employeeFirstName" type="text" name="employeeFirstName" placeholder="First Name" maxlength="15" value="'.$_GET['employeeFirstName'].'">';
                    }
                    else {
                      echo '<input class="employeeFirstName" type="text" name="employeeFirstName" placeholder="First Name" maxlength="15">';
                    }
                   ?>

                  <p class="inputTitle">Last Name</p>

                  <?php
                    if(isset($_GET['employeeLastName'])){
                      echo '<input class="employeeLastName" type="text" name="employeeLastName" placeholder="Last Name" maxlength="15" value="'.$_GET['employeeLastName'].'">';
                    }
                    else {
                      echo '<input class="employeeLastName" type="text" name="employeeLastName" placeholder="Last Name" maxlength="15">';
                    }
                   ?>

                  <p class="inputTitle">Contact #</p>

                  <?php
                    if(isset($_GET['employeeContactNum'])){
                      echo '<input class="employeeLastName" type="text" name="employeeContactNum" placeholder="(09xxxxxxxxx)" maxlength="11" value="'.$_GET['employeeContactNum'].'">';
                    }
                    else {
                      echo '<input class="employeeContactNum" type="text" name="employeeContactNum" placeholder="(09xxxxxxxxx)" maxlength="11">';
                    }
                   ?>

                  <p class="inputTitle">Email</p>
                  <?php
                    if(isset($_GET['employeeEmail'])){
                      echo '<input class="employeeEmail" type="text" name="employeeEmail" placeholder="Email" maxlength="30" value="'.$_GET['employeeEmail'].'">';
                    }
                    else {
                      echo '<input class="employeeEmail" type="text" name="employeeEmail" placeholder="Email" maxlength="30">';
                    }
                   ?>

                  <div class="placeholder">
                    <p class="inputTitle">Employee Type</p>
                  </div>
                  <?php
                    if(isset($_GET['employeeType'])){
                      if($_GET['employeeType'] == 'A'){
                        $employeeType = "Attendant";
                      }
                      else if ($_GET['employeeType'] == 'D'){
                        $employeeType = 'Driver';
                      }
                      echo '<input class="employeeEmail" type="text" name="employeeType" placeholder="Employee Type" maxlength="30" value="'.$employeeType.'" readonly>';
                      echo '<input type="hidden" name="action" value="'.$_GET['employeeType'].'">';
                    }
                    else {
                      echo '<input class="employeeEmail" type="text" name="action" placeholder="Employee Type" maxlength="30" readonly>';
                      echo '<input type="hidden" name="action" value="">';
                    }
                   ?>
                  <p>Region</p>
                  <select name="employeeRegion" placeholder="Region">
                  <?php
                  $regions = ['NCR', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII', 'XIII', 'CAR', 'MIMAROPA'];
                    if(isset($_GET['employeeRegion'])){
                      foreach($regions as $region){
                        if($region == $_GET['employeeRegion']){
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
                      if(isset($_GET['employeeCity'])){
                        echo '<input class="employeeCity" type="text" name="employeeCity" placeholder="City" maxlength="30" value="'.$_GET['employeeCity'].'">';
                      }
                      else {
                        echo '<input class="employeeCity" type="text" name="employeeCity" placeholder="City" maxlength="30">';
                      }
                     ?>
                    <p class="inputTitle">Barangay</p>
                    <?php
                      if(isset($_GET['employeeCity'])){
                        echo '<input class="employeeBarangay" type="text" name="employeeBarangay" placeholder="Barangay" maxlength="30" value="'.$_GET['employeeBarangay'].'">';
                      }
                      else {
                        echo '<input class="employeeBarangay" type="text" name="employeeBarangay" placeholder="Barangay" maxlength="30">';
                      }
                     ?>

                    <p class="inputTitle">Street Name</p>
                    <?php
                      if(isset($_GET['employeeCity'])){
                        echo '<input class="employeeStreetName" type="text" name="employeeStreetName" placeholder="Street Name" maxlength="30" value="'.$_GET['employeeStreetName'].'">';
                      }
                      else {
                        echo '<input class="employeeStreetName" type="text" name="employeeStreetName" placeholder="Street Name" maxlength="30">';
                      }
                     ?>
                    <p class="inputTitle">House Number</p>
                    <?php
                      if(isset($_GET['employeeCity'])){
                        echo '<input class="employeeHouseNum" type="text" name="employeeHouseNum" placeholder="House Number" maxlength="5" value="'.$_GET['employeeHouseNum'].'">';
                      }
                      else {
                        echo '<input class="employeeHouseNum" type="text" name="employeeHouseNum" placeholder="House Number" maxlength="5">';
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
              <div class="confirmationContainer" style="display: flex; flex-direction: column; row-gap: 5px;">


                <button type="submit" id="confirmButton">Confirm</button>
                <a href="http://localhost:4000/HazardousWasteProj/Forms/EmployeeActionsForm/EmployeeActionsForm.php"
                >Go back</a>

              </div>

            </form>
          </div>
        <script type="text/javascript">
          let employeeSubtypeForm = document.getElementById('employeeSubtypeForm');
          let innerForm = document.createElement('div');
          let employeeType;

          function changeSubtypeForm() {
            try{
              employeeType = document.querySelector('input[name="action"]:checked').value;
            }
            catch(err){
              return
            }
            finally {
              employeeSubtypeForm.innerHTML = '';

              if (employeeType == 'D'){
                displayDriverForm();
              }
              else if (employeeType == 'A'){
                displayAttendantForm();
              }
            }
          }

          function displayDriverForm(){
            innerForm.innerHTML = '';
            innerForm.className = 'innerFormDriverBox';

            let licRegLabel = document.createElement('p');
            licRegLabel.innerText = "Date of License Registration";

            let inputDateLicReg = document.createElement('input');
            inputDateLicReg.type = 'date';
            inputDateLicReg.name = 'dateLicenseRegistration';
            inputDateLicReg.value = '<?php echo $dateLicenseRegistration; ?>';

            let licExpLabel = document.createElement('p');
            licExpLabel.innerText = "Date of License Expiration";

            let inputDateLicExp = document.createElement('input');
            inputDateLicExp.type = 'date';
            inputDateLicExp.name = 'dateLicenseExpiration';
            inputDateLicExp.value = '<?php echo $dateLicenseExpiration; ?>';

            innerForm.append(licRegLabel);
            innerForm.append(inputDateLicReg);
            innerForm.append(licExpLabel);
            innerForm.append(inputDateLicExp);
            employeeSubtypeForm.append(innerForm);
          }

          function displayAttendantForm(){
            innerForm.innerHTML = '';
          }

          changeSubtypeForm();
        </script>

      </div>
      <div class="Report">
        <div class="titleContainer">
          <div class="actionsTitle">
            <p style="padding-top: 0;">Employee Data</p>
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
            <input type="radio" name="choiceReport" value="Driver" id="choiceReport" onclick="choiceReportChange()">
            <label for="Create">Driver</label>
          </div>

          <div class="radio">
            <input type="radio" name=choiceReport value="Attendant" id="choiceReport" onclick="choiceReportChange()">
            <label for="Update">Attendant</label>
          </div>

          <div class="radio">
            <input type="radio" name=choiceReport value= "All" id="choiceReport" onclick="choiceReportChange()" checked>
            <label for="Update">All</label>
          </div>
        </div>
      </div>
    </main>
    <script type="text/javascript">
      let jsArray;
      let choiceType;
      let tableHeader = document.getElementById('tableHeader');
      let tableContent = document.getElementById('tableContent');

      function displayAllData(jsArray, choiceType){
        tableContent.className = 'tableContent';
        tableHeader.className = 'tableHeader';
        if (choiceType == 'All'){
          createAllHeader();
        }
        else if (choiceType == 'Attendant'){
          createAttendantHeader();
        }
        for(i = 0; i < jsArray.length; i++){

          let employeeID = document.createElement('p');
          let employeeLastName = document.createElement('p');
          let employeeFirstName = document.createElement('p');
          let employeeContactNum = document.createElement('p');
          let employeeEmail = document.createElement('p');
          let employeeRegion = document.createElement('p');
          let employeeCity = document.createElement('p');
          let employeeBarangay = document.createElement('p');
          let employeeStreetName = document.createElement('p');
          let employeeHouseNum = document.createElement('p');
          let employeeType = document.createElement('p');
          let licDateRegistration = document.createElement('p');
          let licDateExpiration = document.createElement('p');

          employeeID.innerText = jsArray[i]['employeeID'];
          employeeLastName.innerText = jsArray[i]['employeeLastName'];
          employeeFirstName.innerText = jsArray[i]['employeeFirstName'];
          employeeContactNum.innerText = jsArray[i]['employeeContactNum'];
          employeeEmail.innerText = jsArray[i]['employeeEmail'];
          employeeRegion.innerText = jsArray[i]['employeeRegion'];
          employeeCity.innerText = jsArray[i]['employeeCity'];
          employeeBarangay.innerText = jsArray[i]['employeeBarangay'];
          employeeStreetName.innerText = jsArray[i]['employeeStreetName'];
          employeeHouseNum.innerText = jsArray[i]['employeeHouseNum'];
          if(jsArray[i]['employeeType']== 'D'){
            employeeType.innerText = 'Driver';
          } else {
            employeeType.innerText = 'Attendant';
          }

          tableContent.append(employeeID);
          tableContent.append(employeeLastName);
          tableContent.append(employeeFirstName);
          tableContent.append(employeeContactNum);
          tableContent.append(employeeEmail);
          tableContent.append(employeeRegion);
          tableContent.append(employeeCity);
          tableContent.append(employeeBarangay);
          tableContent.append(employeeStreetName);
          tableContent.append(employeeHouseNum);
          tableContent.append(employeeType);

        }
      }

      function displayDriverData(jsArray, choiceType){
        tableContent.className = 'driverContent';
        tableHeader.className = 'driverHeader';
        createDriverHeader();

        for(i = 0; i < jsArray.length; i++){

          let employeeID = document.createElement('p');
          let employeeLastName = document.createElement('p');
          let employeeFirstName = document.createElement('p');
          let dateLicenseRegistration = document.createElement('p');
          let dateLicenseExpiration = document.createElement('p');
          let employeeContactNum = document.createElement('p');
          let employeeEmail = document.createElement('p');
          let employeeRegion = document.createElement('p');
          let employeeCity = document.createElement('p');
          let employeeBarangay = document.createElement('p');
          let employeeStreetName = document.createElement('p');
          let employeeHouseNum = document.createElement('p');
          let employeeType = document.createElement('p');
          let licDateRegistration = document.createElement('p');
          let licDateExpiration = document.createElement('p');

          employeeID.innerText = jsArray[i]['employeeID'];
          employeeLastName.innerText = jsArray[i]['employeeLastName'];
          employeeFirstName.innerText = jsArray[i]['employeeFirstName'];
          dateLicenseRegistration.innerText = jsArray[i]['dateLicenseRegistration'];
          dateLicenseExpiration.innerText = jsArray[i]['dateLicenseExpiration'];
          employeeContactNum.innerText = jsArray[i]['employeeContactNum'];
          employeeEmail.innerText = jsArray[i]['employeeEmail'];
          employeeRegion.innerText = jsArray[i]['employeeRegion'];
          employeeCity.innerText = jsArray[i]['employeeCity'];
          employeeBarangay.innerText = jsArray[i]['employeeBarangay'];
          employeeStreetName.innerText = jsArray[i]['employeeStreetName'];
          employeeHouseNum.innerText = jsArray[i]['employeeHouseNum'];
          employeeType.innerText = jsArray[i]['employeeType'];

          tableContent.append(employeeID);
          tableContent.append(employeeLastName);
          tableContent.append(employeeFirstName);
          tableContent.append(dateLicenseRegistration);
          tableContent.append(dateLicenseExpiration);
          tableContent.append(employeeContactNum);
          tableContent.append(employeeEmail);
          tableContent.append(employeeRegion);
          tableContent.append(employeeCity);
          tableContent.append(employeeBarangay);
          tableContent.append(employeeStreetName);
          tableContent.append(employeeHouseNum);
          tableContent.append(employeeType);

        }
      }

      function createAttendantHeader(){
        let arrayOfHeaderTitle = ['Attendant ID', 'Last Name', 'First Name', 'Contact#',
         'Email', 'Region', 'City', 'Barangay', 'Street', 'HouseNum', 'Type'];
        writeHeader(arrayOfHeaderTitle);
      }

      function createDriverHeader(){
        let arrayOfHeaderTitle = ['Driver ID', 'Last Name', 'First Name', 'LicRegistration', 'LicExpiration', 'Contact#',
         'Email', 'Region', 'City', 'Barangay', 'Street', 'HouseNum', 'Type'];
        writeHeader(arrayOfHeaderTitle);
      }

      function createAllHeader(){
        tableHeader.innerHTML = '';
        let arrayOfHeaderTitle = ['Employee ID', 'Last Name', 'First Name', 'Contact#',
         'Email', 'Region', 'City', 'Barangay', 'Street', 'HouseNum', 'Type'];
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
          if (choiceType == 'All'){
            jsArray = <?php echo json_encode($allData); ?>;
            displayAllData(jsArray, choiceType);
          }
          else if (choiceType == 'Attendant') {
            jsArray = <?php echo json_encode($attendantData); ?>;
            displayAllData(jsArray, choiceType);
          }
          else if (choiceType == 'Driver'){
            jsArray = <?php echo json_encode($driverData); ?>;
            displayDriverData(jsArray, choiceType);
          }
      }

      choiceReportChange();

      // update part

      function getThisEmployee(){
          let employeeID = document.getElementById('employeeID').value;
      }

    </script>
  </body>
</html>
