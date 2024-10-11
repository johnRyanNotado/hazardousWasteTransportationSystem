<?php
  session_start();
  if(!isset($_SESSION['name'])){
    header("Location: http://localhost:4000/HazardousWasteProj/Forms/AccessForm/AccountAccessForm.php");
  }
 ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Vehicle Assignment</title>
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Footer.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/General.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Header.css">
    <link rel="stylesheet" type="text/css" href="../../Forms/WasteAssignmentForm/WasteAssignmentForm.css">
    <link rel="stylesheet" type="text/css" href="VehicleAssignmentForm.css">
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
      <?php
        include "phpCodes/dbh.inc.php";

        $dateOfActualPickUp;
        if(isset($_GET['dateOfActualPickUp'])){
          $dateOfActualPickUp = $_GET['dateOfActualPickUp'];
        }
        $employeeAssignmentID;
        if(isset($_GET['employeeAssignmentID'])){
          $employeeAssignmentID= $_GET['employeeAssignmentID'];
        }

        $sql = "SELECT v.vehicleID, v.vehiclePlate, v.vehicleType, v.vehicleCapacity
        FROM vehicle v
        WHERE vehicleStatus = 'A';";

        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $vehicleData = array();

        if (mysqli_num_rows($result) > 0){
          while ($rows = mysqli_fetch_assoc($result)){
            $vehicleData[] = $rows;
          }
        }



        $sql = "SELECT e.employeeID, e.employeeType, e.employeeFirstName, e.employeeLastName
        FROM employee e
          RIGHT JOIN driver d
            ON d.dEmployeeID = e.employeeID
        WHERE d.dateLicenseExpiration > ?;";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "s", $dateOfActualPickUp);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $driverData = array();
        if (mysqli_num_rows($result) > 0){
          while ($rows = mysqli_fetch_assoc($result)) {
            $driverData[] = $rows;
          }
        }

        $requestID;
        if(isset($_GET['requestID'])){
          $requestID = $_GET['requestID'];
        }


        $sql = "SELECT * FROM wasteAssigNment WHERE requestID = ?;";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "s", $requestID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $wasteAssigmentData = array();
        if (mysqli_num_rows($result) > 0){
          while ($rows = mysqli_fetch_assoc($result)) {
            $wasteAssignmentData[] = $rows;
          }
        } else {
          $wasteAssigmentData[] =null;
        }



        $sql = "SELECT v.vehicleID, v.vehiclePlate, v.vehicleCapacity, e.employeeLastName, e.employeeFirstName
        FROM employee e
          RIGHT JOIN driver d
            ON e.employeeID = d.dEmployeeID
          RIGHT JOIN vehicleAssignment vA
            ON d.dEmployeeID = vA.dEmployeeID
            LEFT JOIN vehicle v
            ON v.vehicleID = vA.vehicleID
          WHERE vA.eAssignmentID = ?;";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "s", $employeeAssignmentID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $vehicleAssigmentData = array();
        if (mysqli_num_rows($result) > 0){
          while ($rows = mysqli_fetch_assoc($result)) {
            $vehicleAssignmentData[] = $rows;
          }
        } else {
          $vehicleAssignmentData[] =null;
        }



       ?>
       <form class="" action="phpCodes/vehicleAssignment.inc.php" method="post">
         <input type="hidden" name="employeeAssignmentID" value="<?php echo $employeeAssignmentID ?>">
         <input type="hidden" name="requestID" value="<?php echo $requestID ?>">
         <input type="hidden" name="dateOfActualPickUp" value="<?php echo $dateOfActualPickUp ?>">
         <div class="everything" style="margin-top:80px; width: 1500px;">
           <div class="requestDataContainer">
             <div class="requestFormDateBox">
                 <p class="inputTitle">Transportation ID</p>
                 <?php
                   if(isset($_GET['transportationID'])){
                     echo '<input class="transportationID" type="text" name="transportationID" placeholder="Transportation ID" maxlength="20" value="'.$_GET['transportationID'].'" readonly>';
                   }
                   else {
                     echo '<input class="transportationID" type="text" name="transportationID" placeholder="Transportation ID" maxlength="20">';
                   }
                  ?>
             </div>
             <div style="display: flex; flex-direction: row; column-gap: 15px;">
               <button type="submit" name="findButton" value="Find" style="margin-bottom: 20px;">Find</button>
               <button type="submit" name="findButton" value="Cancel" style="margin-bottom: 20px;">Cancel</button>
             </div>
           </div>

           <div class="wasteAssignmentContainer" style="width: 100%;">
            <div class="wasteAssignment" id='wasteAssignment'>
                <div class="wasteEditBox" >
                    <p class="inputTitle"style='text-align: center;'>Vehicle</p>
                    <select name="vehicleID" placeholder="Vehicle Options">
                      <?php
                        foreach($vehicleData as $row){
                          $vehicleID = $row['vehicleID'];
                          $vehicleType = $row['vehicleType'];
                          $vehicleCapacity = $row['vehicleCapacity'];
                          $vehiclePlate= $row['vehiclePlate'];
                          echo "<option value=$vehicleID>$vehicleType : $vehiclePlate : $vehicleCapacity TONS</option>";
                        }
                       ?>
                    </select>
                    <p class="inputTitle" >Driver</p>
                    <select name="driverID" placeholder="Driver Options">
                      <?php

                        echo "<script>console.log('shit')</script>";
                        echo '<script>console.log("'.$driverData[0].'")</script>';
                        foreach($driverData as $row){
                          $dEmployeeID = $row['employeeID'];
                          $employeeType = $row['employeeType'];
                          $employeeLastName = $row['employeeLastName'];
                          $employeeFirstName = $row['employeeFirstName'];
                          echo "<option value=$dEmployeeID>$employeeType : $employeeFirstName $employeeLastName </option>";
                        }
                       ?>
                    </select>
                    <div class="rowGrid">
                      <button type="submit" name="findButton" value="Add">Add</button>
                      <button type="submit" name="findButton" value="Delete">Delete</button>
                    </div>

                </div>

              </div>
              <div class="vehicleDataBox" style="padding-bottom: 25px;">
                <div class="vehicleDataHeader">
                  <div class="wasteIDHeader">
                    Vehicle ID
                  </div>
                  <div class="wasteAmountHeader">
                    Plate Number
                  </div>
                  <div class="wasteAmountHeader">
                    Capacity
                  </div>
                  <div class="wasteAmountHeader">
                    Driver Name
                  </div>
                </div>

                <div class="vehicleDataContent" id="vehicleDataContent">
                </div>
              </div>
            <div class="wasteDataBox" style="padding-bottom: 25px; grid-template-columns: 1fr 1fr;">
              <div class="wasteDataHeader" style="grid-template-columns: 1fr 1fr;">
                <div class="wasteIDHeader">
                  Waste ID
                </div>
                <div class="wasteAmountHeader">
                  Amount
                </div>
              </div>

              <div class="wasteDataContent" id="wasteDataContent" style="grid-template-columns: 1fr 1fr;">
              </div>
            </div>
           </div>
           <div class="confirmationContainer">
            <button type="submit" name="findButton" value="Next" style=" width: 100px;">Next</button>
             <a href="http://localhost:4000/HazardousWasteProj/Forms/TransportationMainInterfaceForm/TransportationMainInterfaceForm.php?"
             >Go back</a>
           </div>
         </div>
         <?php
         //Getting url for error checking
           $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

           if (strpos($fullUrl, "register=empty") == true){
             echo "<p class='error'>You did not fill in all the required fields.</p>";
           } elseif (strpos($fullUrl, "vehicle=dontexist") == true){
             echo "<p class='error'>Record not found</p>";
           } elseif (strpos($fullUrl, "add=success") == true){
             echo "<p class='success'>Waste added!</p>";
           } elseif (strpos($fullUrl, "delete=success") == true){
             echo "<p class='success'>Waste deleted!</p>";
           } elseif (strpos($fullUrl, "add=wasteAlreadyListed") == true){
             echo "<p class='error'>Waste is already listed!</p>";
           } elseif (strpos($fullUrl, "vehicle=vehicleAlreadyAssigned") == true){
             echo "<p class='error'>This vehicle is already assigned!</p>";
           } elseif (strpos($fullUrl, "vehicle=driverAlreadyAssigned") == true){
             echo "<p class='error'>This driver is already assigned!</p>";
           }
          ?>

       </form>

       <script type="text/javascript">

         function displayWasteAssignment(){
           let jsWasteArray = <?php echo json_encode($wasteAssignmentData); ?>;
           for(i = 0; i < jsWasteArray.length; i++){
             let wasteID = document.createElement('div');
             wasteID.innerText = jsWasteArray[i]['wasteID'];
             let wasteAmount = document.createElement('div');
             wasteAmount.innerText = jsWasteArray[i]['amount'];
             wasteDataContent.append(wasteID);
             wasteDataContent.append(wasteAmount);
           }
         }

         function displayVehicleAssignment(){

           let jsVehicleArray = <?php echo json_encode($vehicleAssignmentData); ?>;
           for(i = 0; i < jsVehicleArray.length; i++){
             let vehicleID = document.createElement('div');
             let vehiclePlate = document.createElement('div');
             let vehicleCapacity = document.createElement('div');
             let driver = document.createElement('div');
             vehicleID.innerText = jsVehicleArray[i]['vehicleID'];
             vehiclePlate.innerText = jsVehicleArray[i]['vehiclePlate'];
             vehicleCapacity.innerText = jsVehicleArray[i]['vehicleCapacity'];
             driver.innerText = jsVehicleArray[i]['employeeFirstName']+ " " + jsVehicleArray[i]['employeeLastName'];

             vehicleDataContent.append(vehicleID);
             vehicleDataContent.append(vehiclePlate);
             vehicleDataContent.append(vehicleCapacity);
             vehicleDataContent.append(driver);
             }
           }


         const vehicleDataContent = document.getElementById('vehicleDataContent');
         const wasteDataContent = document.getElementById('wasteDataContent');
         displayWasteAssignment();
         displayVehicleAssignment();
       </script>
    </main>
    <footer class="footer">
      <p>Working towards sustainability for a better and safer environment.
        | © 2020 JM Ecotech Solutions Company. All Rights Reserved.
      </p>
    </footer>
  </body>
</html>
