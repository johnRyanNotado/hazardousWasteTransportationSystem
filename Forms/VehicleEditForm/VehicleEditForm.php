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
    <title>Vehicles</title>
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Footer.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/General.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Header.css">
    <link rel="stylesheet" type="text/css" href="VehicleEditForm.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href=
    "https://secureservercdn.net/198.71.233.110/s0i.8f2.myftpupload.com/wp-content/uploads/2020/03/cropped-5x3-1-32x32.png">


    <?php

      $sql = "SELECT vehiclePlate, vehicleCapacity, vehicleType, vehicleStatus, vehicleID FROM vehicle
      ORDER BY vehicleType, vehicleStatus, vehicleCapacity;";
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
            <p class="inputTitle">Vehicle ID</p>
            <?php

            if(isset($_GET['vehicleID'])){
              echo '<input class="vehiclePlate" type="text" name="vehicleID" placeholder="Vehicle ID" maxlength="18" value='.$_GET['vehicleID'].'>';
            }
            else {
              echo '<input class="vehicleID" type="text" name="vehicleID" placeholder="Vehicle ID" maxlength="18">';
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
        <form class="vehicleEditForm" action="phpCodes\vehicleEdit.inc.php" method="post">
          <input type="hidden" name="vehicleID" value="<?php echo $vehicleID ?>">
          <p class="inputTitle">Plate number</p>
          <?php

          if(isset($_GET['vehiclePlate'])){
            $vehiclePlate = $_GET['vehiclePlate'];
            echo '<input class="vehiclePlate" type="text" name="vehiclePlate" placeholder="Plate Number" maxlength="8" value="'.$vehiclePlate.'">';
          }
          else {
            echo '<input class="vehiclePlate" type="text" name="vehiclePlate" placeholder="Plate Number" maxlength="8">';
          }
           ?>

          <p class="inputTitle">Capacity (TONS)</p>
          <?php

          if(isset($_GET['vehicleCapacity'])){
            echo '<input class="vehicleCapacity" type="text" name="vehicleCapacity" placeholder="Capacity" maxlength="4" value='.$_GET['vehicleCapacity'].'>';
          }
          else {
            echo '<input class="vehicleCapacity" type="text" name="vehicleCapacity" placeholder="Capacity" maxlength="4">';
          }
           ?>
          <p class="inputTitle">Vehicle Type</p>
          <div class="radioButtonsContainerType">
            <div class="radio">
              <?php
              if(isset($_GET['vehicleType'])){
                if($_GET['vehicleType'] == 'V'){
                  echo '<input type="radio" name="vehicleType" value="V" checked>';
                  echo '<label for="Create">Van</label>';
                  echo '</div>';
                  echo '<div class="radio" style="padding-left: 36px;">';
                  echo '<input type="radio" name="vehicleType" value="T">';
                }
                else if ($_GET['vehicleType'] == 'T'){
                  echo '<input type="radio" name="vehicleType" value="V">';
                  echo '<label for="Create">Van</label>';
                  echo '</div>';
                  echo '<div class="radio" style="padding-left: 36px;">';
                  echo '<input type="radio" name="vehicleType" value="T" checked>';
                }
              }
              else {
                echo '<input type="radio" name="vehicleType" value="V">';
                echo '<label for="Create">Van</label>';
                echo '</div>';
                echo '<div class="radio" style="padding-left: 36px;">';
                echo '<input type="radio" name="vehicleType" value="T">';
              }

               ?>
              <label for="Update">Truck</label>
            </div>
          </div>
          <p class="inputTitle">Vehicle Status</p>
          <div class="radioButtonsContainerStat">
            <div class="radio">
              <?php
              if(isset($_GET['vehicleStatus'])){
                if($_GET['vehicleStatus'] == 'A'){
                  echo '<input type="radio" name="vehicleStatus" value="A" checked>';
                  echo '<label for="vehicleStatus">Active</label>';
                  echo '</div>';
                  echo '<div class="radio"  style="padding-left: 15px;">';
                  echo '<input type="radio" name="vehicleStatus" value="I">';
                }
                else if ($_GET['vehicleStatus'] == 'I'){
                  echo '<input type="radio" name="vehicleStatus" value="A">';
                  echo '<label for="vehicleStatus">Active</label>';
                  echo '</div>';
                  echo '<div class="radio"  style="padding-left: 15px;">';
                  echo '<input type="radio" name="vehicleStatus" value="I" checked>';
                }
              }
              else {
                echo '<input type="radio" name="vehicleStatus" value="A">';
                echo '<label for="vehicleStatus">Active</label>';
                echo '</div>';
                echo '<div class="radio"  style="padding-left: 15px;">';
                echo '<input type="radio" name="vehicleStatus" value="I">';
              }

               ?>
              <label for="vehicleStatus">Inactive</label>
            </div>
          </div>
          <hr style="margin-bottom: 6px; width: 100px;">
          <hr style="margin-bottom: 6px; width: 200px;">
          <div class="inputTitle" style="text-align: center;">Actions</div>
          <div class="radioButtonsContainer">
            <div class="radio" style="text-align: center;">
              <input type="radio" name="action" value="Create">
              <label for="Create">Create</label>
            </div>
            <div class="radio" style="text-align: center;">
              <input type="radio" name="action" value="Update">
              <label for="Update">Update</label>
            </div>
            <div class="radio" style="text-align: center;">
              <input type="radio" name="action" value="Delete">
              <label for="Delete">Delete</label>
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
          } elseif (strpos($fullUrl, "update=vehicledontexist") == true){
            echo "<p class='error'>Vehicle doesn't exist!</p>";
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
            Vehicle ID
          </div>
          <div class="wasteIDHeader">
            Plate No.
          </div>
          <div class="wasteTypeHeader">
            Capacity
          </div>
          <div class="vehicleStatusHeader">
            Type
          </div>
          <div class="vehicleCapacityHeader">
            Status
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
          let vehiclePlate = document.createElement('div');
          let vehicleCapacity = document.createElement('div');
          let vehicleType = document.createElement('div');
          let vehicleStatus = document.createElement('div');
          let vehicleID = document.createElement('div');

          vehiclePlate.innerText = jsArray[i]['vehiclePlate'];
          vehicleCapacity.innerText = jsArray[i]['vehicleCapacity'] + ' TONS';
          vehicleType.innerText = jsArray[i]['vehicleType'];
          if (jsArray[i]['vehicleType'] == 'T'){
            vehicleType.innerText = 'Truck';
          }
          else if (jsArray[i]['vehicleType'] == 'V'){
            vehicleType.innerText = 'Van';
          }
          else {
            vehicleType.innerText = 'Unknown';
          }

          if (jsArray[i]['vehicleStatus'] == 'A'){
            vehicleStatus.innerText = 'Active';
          }
          else if (jsArray[i]['vehicleStatus'] == 'I'){
            vehicleStatus.innerText = 'Inactive';
          }
          else {
            vehicleStatus.innerText = 'Unknown';
          }

          vehicleID.innerText = jsArray[i]['vehicleID'];

          vehicleDataContent.append(vehicleID);
          vehicleDataContent.append(vehiclePlate);
          vehicleDataContent.append(vehicleCapacity);
          vehicleDataContent.append(vehicleType);
          vehicleDataContent.append(vehicleStatus);
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
