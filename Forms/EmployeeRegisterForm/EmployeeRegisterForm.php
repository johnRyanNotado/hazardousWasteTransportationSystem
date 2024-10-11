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
    <link rel="stylesheet" type="text/css" href="EmployeeRegisterForm.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href=
    "https://secureservercdn.net/198.71.233.110/s0i.8f2.myftpupload.com/wp-content/uploads/2020/03/cropped-5x3-1-32x32.png">

  </head>
  <body>
    <?php
    $dateLicenseExpiration = date('Ymd');
    $dateLicenseRegistration = date('Ymd');
    if(isset($_GET['dateLicenseRegistration'])){

        $dateLicenseExpiration = $_GET['dateLicenseRegistration'];
        $dateLicenseRegistration = $_GET['dateLicenseExpiration'];
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
        <form class="logout_form" action="phpCodes/home.php" method="post">
          <button type="submit" name="home_button">Home</button>
        </form>
        <form class="logout_form" action="includes/logout.php" method="post">
          <button type="submit" name="logout_button">Logout</button>
        </form>
      </div>
    </header>
    <main>
      <div class="employeeRegisterContainer">
        <div class="titleContainer">
          <div class="actionsTitle">
            <p style="padding-top: 0;">Register Employee</p>
          </div>
          <hr style="margin-bottom: 20px;">
        </div>

        <form class="employeeRegisterForm" action="phpCodes\employeeRegister.inc.php" method="post">
          <input type="hidden" name="employeeID" value="<?php echo $employeeID ?>">
          <div class="employeeRegisterBox">
            <div class="employeeDataForm">
              <div class="employeeRegisterFormLeftPart">
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
                <div class="radioButtonsContainer">
                  <div class="radio">
                    <?php
                    if(isset($_GET['employeeType'])){
                      if($_GET['employeeType'] == 'D'){
                        echo '<input type="radio" name="action" value="D" id="action" onclick="changeSubtypeForm()" checked>';
                        echo '<label for="Driver">Driver</label>';
                        echo '</div>';
                        echo '<div class="radio">';
                        echo '<input type="radio" name="action" value="A" id="action" onclick="changeSubtypeForm()">';

                      }
                      else if ($_GET['employeeType'] == 'A'){
                        echo '<input type="radio" name="action" value="D" id="action" onclick="changeSubtypeForm()">';
                        echo '<label for="Driver">Driver</label>';
                        echo '</div>';
                        echo '<div class="radio">';
                        echo '<input type="radio" name="action" value="A" id="action" onclick="changeSubtypeForm()" checked>';
                      }
                    }
                    else {
                      echo '<input type="radio" name="action" value="D" id="action" onclick="changeSubtypeForm()">';
                      echo '<label for="Driver">Driver</label>';
                      echo '</div>';
                      echo '<div class="radio">';
                      echo '<input type="radio" name="action" value="A" id="action" onclick="changeSubtypeForm()">';
                    }

                     ?>
                    <label for="Attendant">Attendant</label>
                  </div>
                  </div>

              </div>
              <div class="employeeRegisterFormRightPart">
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

            </div>
            <div id="employeeSubtypeForm">
            </div>
            <div class="confirmationContainer" style="display: flex; flex-direction: column; row-gap: 5px; align-items: center;">


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
                    console.log('employeeType');
        try{
          employeeType = document.querySelector('input[name="action"]:checked').value;
        }
        catch(err){
          return
        }
        finally {
          employeeSubtypeForm.innerHTML = '';

          if (employeeType == 'D'){
            console.log('employeeType');
            displayDriverForm();
            console.log('employeeType');
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
        inputDateLicReg.value = '<?php echo $dateLicenseRegistration;?>';

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

      <?php
      //Getting url for error checking
        $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        if (strpos($fullUrl, "register=empty") == true){
          echo "<p class='error'>You did not fill in all fields!</p>";
        } elseif (strpos($fullUrl, "register=invalidname") == true){
          echo "<p class='error'>Name must be 'a-z', 'A-Z'!</p>";
        } elseif (strpos($fullUrl, "register=invalidmobile") == true){
          echo "<p class='error'>Invalid Contact#!</p>";
        } elseif (strpos($fullUrl, "update=success") == true){
          echo "<p class='success'>Registration Successful!</p>";
        } elseif (strpos($fullUrl, "register=recordalreadyexist") == true){
          echo "<p class='error'>Record Already Exist!</p>";
        } elseif (strpos($fullUrl, "license=cannotbe") == true){
          echo "<p class='error'>The license registration/expiration date is invalid!</p>";
        }
       ?>
    </main>
    <footer class="footer">
      <p>Working towards sustainability for a better and safer environment.
        | © 2020 JM Ecotech Solutions Company. All Rights Reserved.
      </p>
    </footer>
  </body>
</html>
