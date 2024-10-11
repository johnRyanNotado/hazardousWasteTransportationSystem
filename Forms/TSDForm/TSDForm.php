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
    <title>TSD Facilities</title>
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Footer.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/General.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Header.css">
    <link rel="stylesheet" type="text/css" href="TSDForm.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href=
    "https://secureservercdn.net/198.71.233.110/s0i.8f2.myftpupload.com/wp-content/uploads/2020/03/cropped-5x3-1-32x32.png">


    <?php

      $sql = "SELECT tsdID, tsdName, tsdDenrID, tsdRegion, tsdCity, tsdBarangay, tsdStreetName, tsdHouseNum
      FROM tsd
      ORDER BY tsdName;";
      $result = mysqli_query($conn, $sql);
      $data = array();
      if (mysqli_num_rows($result) > 0){
        while ($row = mysqli_fetch_assoc($result)) {
          $data[] = $row;
        }
      }

      if(isset($_GET['vehicleID'])){
        $vehicleID = $_GET['vehicleID'];
      } else {
        $vehicleID = 'N/A';
      }
     ?>




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
        <form class="logout_form" action="includes/logout.php" method="post">
          <button type="submit" name="logout_button">Logout</button>
        </form>
      </div>
    </header>
    <main>
      <div class="wasteEditContainer">
        <div class="titleContainer">
          <div class="actionsTitle">
            <p style="padding-top: 0;">Actions Available</p>
          </div>
          <hr style="margin-bottom: 20px;">
        </div>
        <form class="getThisVehicle" action="phpCodes/getThisVehicle.php" method="post">
          <div class="vehicleFindBox">
            <p class="inputTitle">TSD ID</p>
            <?php

            if(isset($_GET['tsdID'])){
              echo '<input class="vehiclePlate" type="text" name="tsdID" placeholder="TSD ID" maxlength="18" value='.$_GET['tsdID'].'>';
            }
            else {
              echo '<input class="vehicleID" type="text" name="tsdID" placeholder="TSD ID" maxlength="18">';
            }
             ?>

            <div class="PlaceHolder">

            </div>
            <div class="submitButtonContainer">
              <button type="submit" name="button" style="padding-left: 30px; padding-right: 30px;">Find</button>
            </div>

          </div>
        </form>
        <div class="vehicleEditBox">
        <form class="vehicleEditForm" action="phpCodes\tsdEdit.inc.php" method="post">
          <input type="hidden" name="tsdID" value="<?php echo $tsdID ?>">
          <p class="inputTitle">TSD Name</p>
          <?php

          if(isset($_GET['tsdName'])){
            echo '<input class="vehiclePlate" type="text" name="tsdName" placeholder="TSD Name" maxlength="40" value="'.$_GET['tsdName'].'">';
          }
          else {
            echo '<input class="vehiclePlate" type="text" name="tsdName" placeholder="TSD Name" maxlength="40">';
          }
           ?>

          <p class="inputTitle">TSD DENR ID</p>
          <?php

          if(isset($_GET['tsdDenrID'])){
            echo '<input class="vehicleCapacity" type="text" name="tsdDenrID" placeholder="DENR ID" maxlength="20" value='.$_GET['tsdDenrID'].'>';
          }
          else {
            echo '<input class="vehicleCapacity" type="text" name="tsdDenrID" placeholder="DENR ID" maxlength="20">';
          }
           ?>
           <p class="inputTitle">TSD Region</p>
           <select name="tsdRegion" placeholder="Region">
             <?php
             $regions = ['NCR', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII', 'XIII', 'CAR', 'MIMAROPA'];
               if(isset($_GET['tsdRegion'])){
                 foreach($regions as $region){
                   if($region == $_GET['tsdRegion']){
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
            <p class="inputTitle">TSD City</p>
            <?php

            if(isset($_GET['tsdCity'])){
              echo '<input class="vehicleCapacity" type="text" name="tsdCity" placeholder="DENR ID" maxlength="30" value='.$_GET['tsdCity'].'>';
            }
            else {
              echo '<input class="vehicleCapacity" type="text" name="tsdCity" placeholder="DENR ID" maxlength="30">';
            }
             ?>
             <p class="inputTitle">TSD Barangay</p>
             <?php

             if(isset($_GET['tsdBarangay'])){
               echo '<input class="vehicleCapacity" type="text" name="tsdBarangay" placeholder="Barangay" maxlength="30" value='.$_GET['tsdBarangay'].'>';
             }
             else {
               echo '<input class="vehicleCapacity" type="text" name="tsdBarangay" placeholder="Barangay" maxlength="30">';
             }
              ?>
              <p class="inputTitle">TSD Street</p>
              <?php

              if(isset($_GET['tsdStreetName'])){
                echo '<input class="vehicleCapacity" type="text" name="tsdStreetName" placeholder="Street" maxlength="30" value='.$_GET['tsdStreetName'].'>';
              }
              else {
                echo '<input class="vehicleCapacity" type="text" name="tsdStreetName" placeholder="Street" maxlength="30">';
              }
               ?>
               <p class="inputTitle">TSD House Num#</p>
               <?php

               if(isset($_GET['tsdHouseNum'])){
                 echo '<input class="vehicleCapacity" type="text" name="tsdHouseNum" placeholder="House Number" maxlength="5" value='.$_GET['tsdHouseNum'].'>';
               }
               else {
                 echo '<input class="vehicleCapacity" type="text" name="tsdHouseNum" placeholder="House Number" maxlength="5">';
               }
                ?>

          <hr style="margin-bottom: 6px; width: 100px;">
          <hr style="margin-bottom: 6px; width: 200px;">
          <div class="inputTitle" style="text-align: center;">Actions</div>
          <div class="radioButtonsContainer">
            <div class="radio" style="text-align: center;">
              <input type="radio" name="action" value="Create">
              <label for="Create">Create</label>
            </div>
          </div>
          <div class="">

          </div>
          <div class="submitButtonContainer">
            <button type="submit" id="confirmButton" style="width: 150px;">Confirm</button>
          </div>

        </form>
      </div>

        <?php
        //Getting url for error checking
          $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

          if (strpos($fullUrl, "register=empty") == true){
            echo "<p class='error'>You did not fill in all required fields!</p>";
          } elseif (strpos($fullUrl, "update=TSDdontexist") == true){
            echo "<p class='error'>Record doesn't exist!</p>";
          } elseif (strpos($fullUrl, "update=success") == true){
            echo "<p class='success'>Update Successful!</p>";
          } elseif (strpos($fullUrl, "delete=success") == true){
            echo "<p class='success'>Deletion Successful!</p>";
          } elseif (strpos($fullUrl, "register=success") == true){
            echo "<p class='success'>Creation Successful!</p>";
          } elseif (strpos($fullUrl, "register=recordalreadyexist") == true){
            echo "<p class='error'>Record Already Exist!</p>";
          }
         ?>
      </div>
      <div class="vehicleDataBox">
        <div class="vehicleDataHeader">
          <div class="vehicleIDHeader">
            TSD ID
          </div>
          <div class="wasteIDHeader">
            TSD Name
          </div>
          <div class="wasteTypeHeader">
            TSD DENR ID
          </div>
          <div class="vehicleStatusHeader">
            Region
          </div>
          <div class="vehicleCapacityHeader">
            City
          </div>
          <div class="vehicleCapacityHeader">
            Barangay
          </div>
          <div class="vehicleCapacityHeader">
            Street
          </div>
          <div class="vehicleCapacityHeader">
            House Number
          </div>
        </div>

        <div class="vehicleDataContent" id="vehicleDataContent">
        </div>

      </div>

    </main>
    <footer class="footer">
      <p>Working towards sustainability for a better and safer environment.
        | © 2020 JM Ecotech Solutions Company. All Rights Reserved.
      </p>
    </footer>
    <script type="text/javascript">
      function displayData(jsArray){
        console.log(jsArray);

        for(i = 0; i < jsArray.length; i++){
          let tsdID = document.createElement('div');
          let tsdName = document.createElement('div');
          let tsdDenrID = document.createElement('div');
          let tsdRegion = document.createElement('div');
          let tsdCity = document.createElement('div');
          let tsdBarangay = document.createElement('div');
          let tsdStreetName = document.createElement('div');
          let tsdHouseNum = document.createElement('div');

          tsdID.innerText = jsArray[i]['tsdID'];
          tsdName.innerText = jsArray[i]['tsdName'];
          tsdDenrID.innerText = jsArray[i]['tsdDenrID'];

          tsdRegion.innerText = jsArray[i]['tsdRegion'];
          tsdCity.innerText = jsArray[i]['tsdCity'];
          tsdBarangay.innerText = jsArray[i]['tsdBarangay'];
          tsdStreetName.innerText = jsArray[i]['tsdStreetName'];
          tsdHouseNum.innerText = jsArray[i]['tsdHouseNum'];

          vehicleDataContent.append(tsdID);
          vehicleDataContent.append(tsdName);
          vehicleDataContent.append(tsdDenrID);
          vehicleDataContent.append(tsdRegion);
          vehicleDataContent.append(tsdCity);
          vehicleDataContent.append(tsdBarangay);
          vehicleDataContent.append(tsdStreetName);
          vehicleDataContent.append(tsdHouseNum);
        }
      }
      // function callIt(){
      const vehicleDataContent = document.getElementById('vehicleDataContent');
      let jsArray = <?php echo json_encode($data); ?>;
      displayData(jsArray);
      // }

    </script>
  </body>
</html>
