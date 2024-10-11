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
    <title>Update Transportation</title>
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Footer.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/General.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Header.css">
    <link rel="stylesheet" type="text/css" href="UpdateThisTransportation.css">
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
        <form class="home_form" action="phpCodes/home.php" method="post">
          <button type="submit" name="home_button">Home</button>
        </form>
        <form class="logout_form" action="phpCodes/logout.php" method="post">
          <button type="submit" name="logout_button">Logout</button>
        </form>
      </div>
    </header>
    <?php

      if(isset($_GET['transportationID'])){
        $transportationID=$_GET['transportationID'];
      }

      if(empty($transportationID)){
        header("Location: http://localhost:4000/HazardousWasteProj/Forms/UpdateTransportationForm/UpdateTransportationForm.php");
        exit();
      }


      $sql = "SELECT r.requestID, r.denrRefCode, ts.tsdDenrID, ts.tsdName, c.clientID, c.clientCompanyName,
      c.clientRegion, c.clientCity, c.clientBarangay, c.clientStreetName, c.clientEmailAddress,
      c.clientHouseNum, ts.tsdRegion, ts.tsdCity, ts.tsdBarangay, ts.tsdStreetName, ts.tsdHouseNum, t.dateOfActualPickUp,
      t.transportationStatus, eA.employeeAssignmentID
      FROM employeeAssignment eA
        RIGHT JOIN transportation t
          ON eA.transportationID = t.transportationID
        LEFT JOIN unchange u
          ON u.uRequestID = t.uRequestID
        LEFT JOIN request r
          ON u.uRequestID = r.requestID
        LEFT JOIN client c
          ON r.clientID = c.clientID
        LEFT JOIN tsd ts
          ON r.tsdID = ts.tsdID
      WHERE t.transportationID = ?
      ;";

      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        mysqli_stmt_bind_param($stmt, "s", $transportationID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $allData = array();
        $row = mysqli_fetch_assoc($result);
      }

      $requestID = $row['requestID'];
      $denrRefCode = $row['denrRefCode'];
      $tsdDenrID = $row['tsdDenrID'];
      $tsdName = $row['tsdName'];
      $clientID = $row['clientID'];
      $clientCompanyName = $row['clientCompanyName'];
      $clientEmailAddress= $row['clientEmailAddress'];
      $clientAddress = $row['clientHouseNum'].", ".$row['clientStreetName'].", ".$row['clientBarangay'].", ".$row['clientCity'].", Region: ".$row['clientRegion'];
      $tsdAddress = $row['tsdHouseNum'].", ".$row['tsdStreetName'].", ".$row['tsdBarangay'].", ".$row['tsdCity'].", Region: ".$row['tsdRegion'];
      $dateOfActualPickUp = $row['dateOfActualPickUp'];
      $transportationStatus = $row['transportationStatus'];
      $employeeAssignmentID = $row['employeeAssignmentID'];

      if($transportationStatus == "F") {
        $sql = "SELECT * FROM failure WHERE fTransportationID = ?
        ;";

        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)){
          echo "sql error";
        }
        else {
          mysqli_stmt_bind_param($stmt, "s", $transportationID);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
          $row = mysqli_fetch_assoc($result);
          $dateReturned = $row['dateReturned'];
          $failureReport = $row['failureReport'];
        }
      }
      else if ($transportationStatus == "S"){
        $sql = "SELECT * FROM spill WHERE sTransportationID = ?
        ;";

        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)){
          echo "sql error";
        }
        else {
          mysqli_stmt_bind_param($stmt, "s", $transportationID);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
          $row = mysqli_fetch_assoc($result);
          $dateOfIncident = $row['dateOfIncident'];
          $dateOfFiling = $row['dateOfFiling'];
          $spillReport = $row['spillReport'];
          $spillRegion = $row['spillRegion'];
          $spillCity = $row['spillCity'];
          $spillBarangay = $row['spillBarangay'];
          $spillStreetName = $row['spillStreetName'];
        }
      }
      else if ($transportationStatus == "D"){
        $sql = "SELECT * FROM delivered WHERE dTransportationID = ?
        ;";

        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)){
          echo "sql error";
        }
        else {
          mysqli_stmt_bind_param($stmt, "s", $transportationID);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
          $row = mysqli_fetch_assoc($result);
          $dateDelivered = $row['dateDelivered'];
        }
      }

      // getting employee and waste data
      // wastes
      $sql = "SELECT wA.wasteID, w.wasteName, wA.Amount
      FROM transportation t
        RIGHT JOIN unchange u
          ON u.uRequestID = t.uRequestID
        RIGHT JOIN request r
          ON u.uRequestID = r.RequestID
        RIGHT JOIN wasteAssignment wA
          ON wA.requestID = r.requestID
        LEFT JOIN waste w
          ON w.wasteID = wA.wasteID
      WHERE t.transportationID = ?
      ;";

      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        mysqli_stmt_bind_param($stmt, "s", $transportationID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $wasteList = array();
        if (mysqli_num_rows($result) > 0){
          while ($row = mysqli_fetch_assoc($result)){
            $wasteList[] = $row;
          }
        }
      }

      // attendants
      $sql = "SELECT e.employeeLastName, e.employeeFirstName,
      e.employeeID, e.employeeContactNum, e.employeeEmail
      FROM transportation t
        RIGHT JOIN employeeAssignment eA
           ON eA.transportationID = t.transportationID
        RIGHT JOIN attendantAssignment aA
           ON aA.eAssignmentID = eA.employeeAssignmentID
        LEFT JOIN employee e
           ON e.employeeID = aA.aEmployeeID
      WHERE t.transportationID = ?
      ;";

      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        mysqli_stmt_bind_param($stmt, "s", $transportationID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $attendantsList = array();
        if (mysqli_num_rows($result) > 0){
          while ($row = mysqli_fetch_assoc($result)){
            $attendantsList[] = $row;
          }
        }
      }

      // driver
      $sql = "SELECT e.employeeID, e.employeeLastName, e.employeeFirstName, e.employeeContactNum, e.employeeEmail,
      vA.vehicleID, v.vehiclePlate
      FROM transportation t
        RIGHT JOIN employeeAssignment eA
           ON eA.transportationID = t.transportationID
        RIGHT JOIN vehicleAssignment vA
           ON vA.eAssignmentID = eA.employeeAssignmentID
        RIGHT JOIN employee e
           ON e.employeeID = vA.dEmployeeID
        RIGHT JOIN vehicle v
           ON v.vehicleID = vA.vehicleID
      WHERE t.transportationID = ?
      ;";

      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        mysqli_stmt_bind_param($stmt, "s", $transportationID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $vehicleAssignmentList = array();
        if (mysqli_num_rows($result) > 0){
          while ($row = mysqli_fetch_assoc($result)){
            $vehicleAssignmentList[] = $row;
          }
        }
      }

     ?>
    <main>
      <form action="phpCodes/updateThisTransportation.inc.php" method="post">
        <input type="hidden" name="employeeAssignmentID" value="<?php echo $employeeAssignmentID; ?>">
        <input type="hidden" name="transportationStatusSet" value="<?php echo $transportationStatus; ?>">
        <div class="biggestContainer">

          <div class="titleContainer">
            <div class="actionsTitle">
              <p style="padding-top: 0;">Transportation Update</p>
            </div>
            <hr style="margin-bottom: 20px;">
          </div>
          <div class="boxesContainer">
            <div class="updateBox">
              <div class="cannotChange">
                <div class="leftPart">
                  <p class="inputTitle">Transportation ID</p>


                  <?php

                  if(isset($transportationID)){
                    echo '<input class="transportationID" type="text" name="transportationID" placeholder="Transportation ID" maxlength="18" id="employeeID" value="'.$transportationID.'" readonly>';
                  }
                  else {
                    echo '<input class="transportationID" type="text" name="transportationID" placeholder="Transportation ID" maxlength="18" id="employeeID">';
                  }
                   ?>
                   <p class="inputTitle">Request ID</p>
                   <?php
                     if(isset($requestID)){
                       echo '<input class="requestID" type="text" name="requestID" placeholder="Request ID" maxlength="18" value="'.$requestID.'" readonly>';
                     }
                     else {
                       echo '<input class="requestID" type="text" name="requestID" placeholder="Request ID" maxlength="18">';
                     }
                    ?>
                    <p class="inputTitle">Denr Ref Code</p>
                    <?php
                      if(isset($denrRefCode)){
                        echo '<input class="denrRefCode" type="text" name="denrRefCode" placeholder="DENR Reference Code" maxlength="20" value="'.$denrRefCode.'" readonly>';
                      }
                      else {
                        echo '<input class="denrRefCode" type="text" name="denrRefCode" placeholder="DENR Reference Code" maxlength="20">';
                      }
                     ?>

                </div>
                <div class="rightPart">
                  <p class="inputTitle">Client ID</p>
                  <?php
                    if(isset($clientID)){
                      echo '<input class="clientID" type="text" name="clientID" placeholder="Client ID" maxlength="18" value="'.$clientID.'" readonly>';
                    }
                    else {
                      echo '<input class="clientID" type="text" name="clientID" placeholder="Client ID" maxlength="18">';
                    }
                   ?>

                    <p class="inputTitle">Company Name</p>
                    <?php
                      if(isset($clientCompanyName)){
                        echo '<input class="clientCompanyName" type="text" name="clientCompanyName" placeholder="Company Name" maxlength="40" value="'.$clientCompanyName.'" readonly>';
                      }
                      else {
                        echo '<input class="clientCompanyName" type="text" name="clientCompanyName" placeholder="Company Name" maxlength="40">';
                      }
                     ?>
                     <p class="inputTitle">Company Email</p>
                     <?php
                       if(isset($clientEmailAddress)){
                         echo '<input class="clientName" type="text" name="clientName" placeholder="Client Name" maxlength="20" value="'.$clientEmailAddress.'" readonly>';
                       }
                       else {
                         echo '<input class="clientName" type="text" name="clientName" placeholder="Client Email" maxlength="20">';
                       }
                      ?>


                </div>
              </div>
                <div class="cannotChange">
                  <div class="leftPart">

                     <p class="inputTitle">TSD ID</p>
                     <?php
                       if(isset($tsdDenrID)){
                         echo '<input class="requestID" type="text" name="requestID" placeholder="TSD ID" maxlength="18" value="'.$tsdDenrID.'" readonly>';
                       }
                       else {
                         echo '<input class="requestID" type="text" name="requestID" placeholder="TSD ID" maxlength="18">';
                       }
                      ?>
                      <p class="inputTitle">TSD Name</p>
                      <?php
                        if(isset($tsdName)){
                          echo '<input class="tsdName" type="text" name="tsdName" placeholder="TSD Name" maxlength="18" value="'.$tsdName.'" readonly>';
                        }
                        else {
                          echo '<input class="tsdName" type="text" name="tsdName" placeholder="TSD Name" maxlength="18">';
                        }
                       ?>

                  </div>
                  <div class="rightPart">
                    <p class="inputTitle">Client Address</p>
                    <?php

                    if(isset($clientAddress)){
                      echo '<input class="clientAddress" type="text" name="clientAddress" placeholder="Client Address" maxlength="100" id="employeeID" value="'.$clientAddress.'">';
                    }
                    else {
                      echo '<input class="clientAddress" type="text" name="clientAddress" placeholder="Client Address" maxlength="100" id="employeeID">';
                    }
                     ?>

                    <p class="inputTitle">TSD Address</p>
                    <?php
                      if(isset($tsdAddress)){
                        echo '<input class="clientID" type="text" name="clientID" placeholder="Client ID" maxlength="18" value="'.$tsdAddress.'" readonly>';
                      }
                      else {
                        echo '<input class="clientID" type="text" name="clientID" placeholder="Client ID" maxlength="18">';
                      }
                     ?>
                  </div>


              </div>


            </div>
            <div class="vehicleBox">
              <div class="vehicleBoxHeader" id="vehicleBoxHeader">
                <p>Vehicle ID</p>
                <p>Plate Number</p>
                <p>Driver ID</p>
                <p>Driver Name</p>
                <p>Contact Number</p>
                <p>Email Address</p>
              </div>
              <div class="vehicleBoxContent" id="vehicleBoxContent">

              </div>
            </div>
            <div class="attendantBox">
              <div class="attendantBoxHeader" id="attendantBoxHeader">
                <p>
                  Attendant ID
                </p>
                <p>
                  Attendant Name
                </p>
                <p>
                  Contact Number
                </p>
                <p>
                  Email Address
                </p>
              </div>
              <div class="attendantBoxContent" id="attendantBoxContent">

              </div>
            </div>
            <div class="wasteBox">
              <div class="wasteBoxHeader" id="wasteBoxHeader">
                <p class="">
                  Waste ID
                </p>
                <p>
                  Waste Name
                </p>
                <p class="">
                  Waste Amount (TONS)
                </p>

              </div>
              <div class="wasteBoxContent" id="wasteBoxContent">

              </div>
            </div>

            <div class="boxBelow">
              <div class="changeable">

                <div class="leftPartC">
                  <p class="inputTitle">Date of Pickup</p>

                  <?php
                    if(isset($dateOfActualPickUp)){
                      echo '<input class="dateOfActualPickUp" type="date" name="dateOfActualPickUp" value="'.$dateOfActualPickUp.'"  readonly>';
                    }
                    else {
                      echo '<input class="dateOfActualPickUp" type="date" name="dateOfActualPickUp" >';
                    }
                   ?>

                   <p class="inputTitle">Transportation Status</p>
                   <?php
                   $status = ['Pending', 'Delivered', 'Failure', 'Spill'];

                     if(isset($transportationStatus)){
                       if($transportationStatus == 'P'){
                         $transportationEquavalentWord = 'Pending';
                       }
                       else if($transportationStatus == 'D'){
                         $transportationEquavalentWord = 'Delivered';
                       }
                       else if($transportationStatus == 'F'){
                         $transportationEquavalentWord = 'Failure';
                       }
                       else if($transportationStatus == 'S'){
                         $transportationEquavalentWord = 'Spill';
                       }
                       echo '<select name="transportationStatus" placeholder="Status" id="status" onchange="displayAppropriateForm()">';
                       foreach($status as $state){
                         if($state == $transportationEquavalentWord){
                           echo "<option value=$state selected>$state</option>";
                         }
                         else {
                           echo "<option value=$state>$state</option>";
                         }
                       }
                     }
                     else {
                       echo '<select name="transportationStatus" placeholder="Status" id="status" onchange="displayAppropriateForm()">';
                       foreach($status as $state){
                           echo "<option value=$state>$state</option>";
                       }
                     }
                    ?>
                    </select>
                </div>

                </select>


              </div>

                <hr style="width: 100%;">
              <div class="cannotChangeBelow">
                <div class="leftPart" id="delivered" style="display: none;">
                  <p class="inputTitle">Date of delivered</p>
                  <?php
                    if(isset($dateDelivered)){
                      echo '<input class="dateDelivered" type="date" name="dateDelivered" value="'.$dateDelivered.'">';
                    }
                    else {
                      echo '<input class="dateDelivered" type="date" name="dateDelivered" >';
                    }
                   ?>
                </div>
                <div class="leftPart" id="spill" style="display: none;">
                  <p class="inputTitle">Date of Incident</p>
                  <?php
                    if(isset($dateOfIncident)){
                      echo '<input class="dateOfIncident" type="date" name="dateOfIncident" value="'.$dateOfIncident.'">';
                    }
                    else {
                      echo '<input class="dateOfIncident" type="date" name="dateOfIncident" >';
                    }
                   ?>
                   <p class="inputTitle">Date of Filing Report</p>
                   <?php
                     if(isset($dateOfFiling)){
                       echo '<input class="dateOfFiling" type="date" name="dateOfFiling" value="'.$dateOfFiling.'">';
                     }
                     else {
                       echo '<input class="dateOfFiling" type="date" name="dateOfFiling" >';
                     }
                    ?>
                    <p class="inputTitle">Spill Report</p>
                    <?php
                      if(isset($spillReport)){
                        echo '<textarea name="spillReport" cols="40" rows="10" placeholder="Type..." >'.$spillReport.'</textarea>';
                      }
                      else {
                        echo '<textarea name="spillReport" placeholder="Type..." cols="40" rows="10"></textarea>';
                      }
                     ?>

                     <p class="inputTitle">Region</p>
                       <?php
                       $regions = ['NCR', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII', 'XIII', 'CAR', 'MIMAROPA'];
                         if(isset($spillRegion)){
                          echo '<select name="spillRegion" placeholder="Region" readonly>';
                           foreach($regions as $region){
                             if($region == $spillRegion){
                               echo "<option value=$region selected>$region</option>";
                             }
                             else {
                               echo "<option value=$region>$region</option>";
                             }
                           }
                         }
                         else {
                           echo '<select name="spillRegion" placeholder="Region">';
                           foreach($regions as $region){
                               echo "<option value=$region>$region</option>";
                           }
                         }
                        ?>
                     </select>
                     <p class="inputTitle">City</p>
                     <?php
                       if(isset($spillCity)){
                         echo '<input class="spillCity" type="text" name="spillCity" placeholder="City" maxlength="30" value="'.$spillCity.'" readonly>';
                       }
                       else {
                         echo '<input class="spillCity" type="text" name="spillCity" placeholder="City" maxlength="30">';
                       }
                      ?>
                     <p class="inputTitle">Barangay</p>
                     <?php
                       if(isset($spillBarangay)){
                         echo '<input class="spillBarangay" type="text" name="spillBarangay" placeholder="Barangay" maxlength="30" value="'.$spillBarangay.'" readonly>';
                       }
                       else {
                         echo '<input class="spillBarangay" type="text" name="spillBarangay" placeholder="Barangay" maxlength="30">';
                       }
                      ?>
                     <p class="inputTitle">Street Name</p>
                     <?php
                       if(isset($spillStreetName)){
                         echo '<input class="spillStreetName" type="text" name="spillStreetName" placeholder="Street Name" maxlength="30" value="'.$spillStreetName.'" readonly>';
                       }
                       else {
                         echo '<input class="spillStreetName" type="text" name="spillStreetName" placeholder="Street Name" maxlength="30">';
                       }
                      ?>

                </div>
                <div class="leftPart" id="failure" style="display: none;">
                  <p class="inputTitle">Date of Return</p>
                  <?php
                    if(isset($dateReturned)){
                      echo '<input class="dateOfIncident" type="date" name="dateReturned" value="'.$dateReturned.'">';
                    }
                    else {
                      echo '<input class="dateOfIncident" type="date" name="dateReturned" >';
                    }
                   ?>
                    <p class="inputTitle">Failure Report</p>
                    <?php
                      if(isset($failureReport)){
                        echo '<textarea name="failureReport" cols="40" rows="10" placeholder="Type...">"'.$failureReport.'"</textarea>';
                      }
                      else {
                        echo '<textarea name="failureReport" placeholder="Type..." cols="40" rows="10"></textarea>';
                      }
                     ?>

                </div>

              </div>


            </div>
            <div class="buttons" id="buttons" style="display: flex; flex-direction: row; justify-content: center; column-gap: 15px;">

              <button type="submit" id="confirmButton" name="actionBottom" value="Update" style="width:100px">Update</button>
              <button type="submit" id="confirmButton" name="actionBottom" value="Delete" style="width:100px">Delete</button>
              <button type="submit" id="confirmButton" name="actionBottom" value="Go back" style="width:100px">Go back</button>

            </div>

            <div class="" style="display: flex; flex-direction: row; justify-content: center; column-gap: 15px; margin-botttom: 50px;">
              <?php
              //Getting url for error checking
                $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                if (strpos($fullUrl, "register=empty") == true){
                  echo "<p class='error'>You did not fill in all the required fields!</p>";
                } else if (strpos($fullUrl, "register=empty") == true){
                  echo "<p class='error'>The record cannot be deleted!</p>";
                } else if (strpos($fullUrl, "update=Deleted") == true){
                  echo "<p class='success'>Record!</p>";
                } else if (strpos($fullUrl, "update=Updated") == true){
                  echo "<p class='success'>Record Updated!</p>";
                } else if (strpos($fullUrl, "update=cannotBeDeleted") == true){
                  echo "<p class='error'>Record cannot be deleted!</p>";
                }

               ?>
            </div>

          </div>

        </div>
      </form>

      <script type="text/javascript">
        function displayAppropriateForm(){
          let status = document.getElementById('status').value;
          if (status == 'Delivered'){
            document.getElementById('delivered').style.display = '';
          }
          else {
            document.getElementById('delivered').style.display = 'none';
          }
          if (status == 'Spill'){
            document.getElementById('spill').style.display = '';
          }
          else {
            document.getElementById('spill').style.display = 'none';
          }
          if (status == 'Failure'){
            document.getElementById('failure').style.display = '';
          }
          else {
            document.getElementById('failure').style.display = 'none';
          }
         }

         function displayWasteData(){
           wasteArray = <?php echo json_encode($wasteList); ?>;
           for(i = 0; i < wasteArray.length; i++){
             let wasteID = document.createElement('p');
             let wasteAmount = document.createElement('p');
             let wasteName = document.createElement('p');


            wasteID.innerText = wasteArray[i]['wasteID'];
            wasteName.innerText = wasteArray[i]['wasteName'];
            wasteAmount.innerText = wasteArray[i]['Amount'];

            wasteBoxContent.append(wasteID);
            wasteBoxContent.append(wasteName);
            wasteBoxContent.append(wasteAmount);
           }
         }

         function displayAttendantData(){
           attendantArray = <?php echo json_encode($attendantsList); ?>;
            for(i = 0; i < attendantArray.length; i++){
             let attendantID = document.createElement('p');
             let attendantName = document.createElement('p');
             let attendantContactNum = document.createElement('p');
             let attendantEmail = document.createElement('p');

            attendantID.innerText = attendantArray[i]['employeeID'];
            attendantName.innerText = attendantArray[i]['employeeLastName'] + ', ' + attendantArray[i]['employeeFirstName'];
            attendantEmail.innerText = attendantArray[i]['employeeEmail'];
            attendantContactNum.innerText = attendantArray[i]['employeeContactNum'];

            attendantBoxContent.append(attendantID);
            attendantBoxContent.append(attendantName);
            attendantBoxContent.append(attendantContactNum);
            attendantBoxContent.append(attendantEmail);
           }
         }

         function displayVehicleData(){
           vehicleAssignmentArray = <?php echo json_encode($vehicleAssignmentList); ?>;
           for(i = 0; i < vehicleAssignmentArray.length; i++){
             let vehicleID = document.createElement('p');
             let plateNumber = document.createElement('p');
             let driverID = document.createElement('p');
             let driverName = document.createElement('p');
             let driverContactNum = document.createElement('p');
             let driverEmail = document.createElement('p');

             vehicleID.innerText = vehicleAssignmentArray[i]['vehicleID'];
             plateNumber.innerText = vehicleAssignmentArray[i]['vehiclePlate'];
             driverID.innerText = vehicleAssignmentArray[i]['employeeID'];
             driverName.innerText = vehicleAssignmentArray[i]['employeeLastName'] + ', ' + vehicleAssignmentArray[i]['employeeFirstName'];
             driverContactNum.innerText = vehicleAssignmentArray[i]['employeeEmail'];
             driverEmail.innerText = vehicleAssignmentArray[i]['employeeContactNum'];

             vehicleBoxContent.append(vehicleID);
             vehicleBoxContent.append(plateNumber);
             vehicleBoxContent.append(driverID);
             vehicleBoxContent.append(driverName);

             vehicleBoxContent.append(driverEmail);
             vehicleBoxContent.append(driverContactNum);
           }
         }

        const wasteBoxContent = document.getElementById('wasteBoxContent');
        const attendantsBoxContent = document.getElementById('attendantsBoxContent');
        const vehicleBoxContent = document.getElementById('vehicleBoxContent');

        displayWasteData();
        displayAttendantData();
        displayVehicleData();
        displayAppropriateForm();
      </script>
    </main>
    <footer class="footer">
      <p>Working towards sustainability for a better and safer environment.
        | © 2020 JM Ecotech Solutions Company. All Rights Reserved.
      </p>
    </footer>
  </body>
</html>
