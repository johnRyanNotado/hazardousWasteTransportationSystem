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
    <title>Update Contract</title>
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Footer.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/General.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Header.css">
    <link rel="stylesheet" type="text/css" href="ContractReportForm.css">
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

      if(isset($_GET['contractID'])){
        $contractID=$_GET['contractID'];
      }

      if(empty($contractID)){
        header("Location: http://localhost:4000/HazardousWasteProj/Forms/ContractUpdateForm/ContractUpdateForm.php?");
        exit();
      }

      $sql = "SELECT c.contractID, c.startDate, c.endDate, c.numOfRequest, c.preTerminationFee, c.contractStatus, c.contractDescription,
      cl.clientID, cl.clientCompanyName, cl.clientEmailAddress, cl.clientRegion, cl.clientCity, cl.clientBarangay, cl.clientStreetName, cl.clientHouseNum
      FROM contract c
        LEFT JOIN client cl
          ON cl.clientID = c.clientID
      WHERE c.contractID = ?
      ;";

      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        mysqli_stmt_bind_param($stmt, "s", $contractID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)){
          $startDate = $row['startDate'];
          $endDate = $row['endDate'];
          $numOfRequest = $row['numOfRequest'];
          $preTerminationFee = $row['preTerminationFee'];
          $contractStatus = $row['contractStatus'];
          $contractStatusSet = $row['contractStatus'];
          $clientEmailAddress = $row['clientEmailAddress'];
          $clientID = $row['clientID'];
          $clientCompanyName = $row['clientCompanyName'];
          $contractDescription = $row['contractDescription'];
          $clientAddress = $row['clientHouseNum'].' '.$row['clientStreetName'].', '.$row['clientBarangay'].', '.$row['clientCity'].' - '.$row['clientRegion'];
        }
      }

      if(isset($contractStatus)){
        if($contractStatus == 'T'){
          $sql = "SELECT t.terminationDate
          FROM contract c
            LEFT JOIN terminatedContract t
              ON c.contractID = t.tContractID
          WHERE c.contractID = ?
          ;";

          $stmt = mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "sql error";
          }
          else {
            mysqli_stmt_bind_param($stmt, "s", $contractID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)){
              $terminationDate = $row['terminationDate'];
            }
          }
        }
      }


      $sql = "SELECT r.requestID, r.specDateOfPickUp, r.requestStatus, t.transportationID, t.dateOfActualPickUp, t.transportationStatus
      FROM contract c
        RIGHT JOIN request r
          ON r.contractID = c.contractID
        LEFT JOIN unchange u
          ON u.uRequestID = r.requestID
        LEFT JOIN transportation t
          ON t.uRequestID = u.uRequestID
      WHERE c.contractID = ?
      ;";

      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        mysqli_stmt_bind_param($stmt, "s", $contractID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $requestData = array();
        while($row = mysqli_fetch_assoc($result)){
          $requestData[] = $row;
        }
      }

     ?>
    <main>
      <form action="phpCodes/ContractReport.inc.php" method="post">
        <input type="hidden" name="contractStatusSet" value="<?php echo $contractStatusSet; ?>">
        <div class="biggestContainer">

          <div class="titleContainer">
            <div class="actionsTitle">
              <p style="padding-top: 0;">Contract Update</p>
            </div>
            <hr style="margin-bottom: 20px;">
          </div>
          <div class="boxesContainer">
            <div class="updateBox">
              <div class="cannotChange">
                <div class="leftPart">
                  <p class="inputTitle">Contract ID</p>


                  <?php

                  if(isset($contractID)){
                    echo '<input class="contractID" type="text" name="contractID" placeholder="Contract ID" maxlength="18" id="employeeID" value="'.$contractID.'" readonly>';
                  }
                  else {
                    echo '<input class="contractID" type="text" name="contractID" placeholder="ContractID" maxlength="18" id="employeeID">';
                  }
                   ?>
                   <p class="inputTitle">Start Date</p>
                   <?php
                     if(isset($startDate)){
                       echo '<input class="startDate" type="date" name="startDate" placeholder="Start Date" maxlength="18" value="'.$startDate.'" readonly>';
                     }
                     else {
                       echo '<input class="startDate" type="date" name="startDate" placeholder="Start Date" maxlength="18">';
                     }
                    ?>
                    <p class="inputTitle">End Date</p>
                    <?php
                      if(isset($endDate)){
                        echo '<input class="endDate" type="date" name="endDate" placeholder="End Date" maxlength="18" value="'.$endDate.'" readonly>';
                      }
                      else {
                        echo '<input class="endDate" type="date" name="endDate" placeholder="End Date" maxlength="18">';
                      }
                     ?>

                </div>
                <div class="rightPart">
                  <p class="inputTitle">No# of Request</p>
                  <?php
                    if(isset($numOfRequest)){
                      echo '<input class="numOfRequest" type="text" name="numOfRequest" placeholder="Num of Request" maxlength="18" value="'.$numOfRequest.'" readonly>';
                    }
                    else {
                      echo '<input class="numOfRequest" type="text" name="numOfRequest" placeholder="Num of Request" maxlength="18">';
                    }
                   ?>

                    <p class="inputTitle">Pre-Termination Fee</p>
                    <?php
                      if(isset($preTerminationFee)){
                        echo '<input class="preTerminationFee" type="text" name="preTerminationFee" placeholder="Pre-Termination Fee" maxlength="40" value="'.$preTerminationFee.'" readonly>';
                      }
                      else {
                        echo '<input class="preTerminationFee" type="text" name="preTerminationFee" placeholder="Pre-Termination Fee" maxlength="40">';
                      }
                     ?>
                     <p class="inputTitle">Contract Status</p>

                     <?php
                     $status = ['Pending', 'Terminated', 'Finished'];

                       if(isset($contractStatus)){
                         if($contractStatus == 'P'){
                           $selectedStatus = "Pending";
                         }
                         else if($contractStatus == 'T'){
                           $selectedStatus = "Terminated";
                         }
                         else if($contractStatus == 'F'){
                           $selectedStatus = "Finished";
                         }
                         echo '<select placeholder="Status" disabled>';
                         foreach($status as $state){
                           if($state == $selectedStatus){
                             echo "<option value=$state selected>$state</option>";
                           }
                           else {
                             echo "<option value=$state>$state</option>";
                           }
                         }
                       }
                       else {
                         echo '<select placeholder="Status">';
                         foreach($status as $state){
                             echo "<option value=$state>$state</option>";
                         }
                       }
                      ?>
                       </select>


                </div>
              </div>
                <div class="cannotChange">
                  <div class="leftPart">

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
                     <p class="inputTitle">Client Email</p>
                     <?php

                     if(isset($clientEmailAddress)){
                       echo '<input class="clientEmailAddress" type="text" name="clientEmailAddress" placeholder="Client Email" maxlength="100" id="employeeID" value="'.$clientEmailAddress.'">';
                     }
                     else {
                       echo '<input class="clientEmailAddress" type="text" name="clientEmailAddress" placeholder="Client Email" maxlength="100" id="employeeID">';
                     }
                      ?>


                  </div>


              </div>


            </div>
            <div class="boxBelow">
              <p>Contract Description</p>
              <?php
              if(isset($contractDescription)){
                echo '<textarea name="contractDescription" cols="80" rows="10" placeholder="Type..." readonly>"'.$contractDescription.'"</textarea>';
              }
              else {
                echo '<textarea name="contractDescription" cols="80" rows="10" placeholder="Type..." readonly></textarea>';
              }
              ?>
            </div>
            <div class="vehicleBox">
              <div class="vehicleBoxHeader" id="requestBoxHeader">
                <p>Request ID</p>
                <p>Spec. Date</p>
                <p>Request Status</p>
                <p>Transportation ID</p>
                <p>Actual Date</p>
                <p>Tansportation Status</p>
              </div>
              <div class="vehicleBoxContent" id="requestBoxContent">

              </div>
            </div>

            <div class="boxBelow">
              <div class="changeable">

                <div class="leftPartC">
                  <p class="inputTitle">Update Contract Status</p>

                  <?php
                  $status = ['Pending', 'Terminated', 'Finished'];

                    if(isset($contractStatus)){
                      if($contractStatus == 'P'){
                        $selectedStatus = "Pending";
                      }
                      else if($contractStatus == 'T'){
                        $selectedStatus = "Terminated";
                      }
                      else if($contractStatus == 'F'){
                        $selectedStatus = "Finished";
                      }
                      echo '<select name="contractStatus" placeholder="Status" id="status" onchange="displayAppropriateForm()">';
                      foreach($status as $state){
                        if($state == $selectedStatus){
                          echo "<option value=$state selected>$state</option>";
                        }
                        else {
                          echo "<option value=$state>$state</option>";
                        }
                      }
                    }
                    else {
                      echo '<select name="contractStatus" placeholder="Status">';
                      foreach($status as $state){
                          echo "<option value=$state>$state</option>";
                      }
                    }
                   ?>
                    </select>



                </div>
                <div class="leftPart" id='terminationDate' style="margin-top: 20px; display: none;">
                  <p class="inputTitle">Termination Date</p>
                  <?php
                    if(!empty($terminationDate)){
                      echo '<input type="date" name="terminationDate" id="terminationDate" value="'.$terminationDate.'">';
                    }
                    else {
                      echo '<input type="date" name="terminationDate">';
                    }

                   ?>
                </div>

                </select>


              </div>


            </div>
            <div class="buttons" id="buttons" style="display: flex; flex-direction: row; justify-content: center; column-gap: 15px;">

              <button type="submit" id="confirmButton" name="actionBottom" value="Update" style="width:100px">Update</button>
              <button type="submit" id="confirmButton" name="actionBottom" value="Go back" style="width:100px">Go back</button>

            </div>

            <div class="" style="display: flex; flex-direction: row; justify-content: center; column-gap: 15px; margin-botttom: 50px;">
              <?php
              //Getting url for error checking
                $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                if (strpos($fullUrl, "contract=cannotBeUpdated") == true){
                  echo "<p class='error'>Contract cannot be updated!</p>";
                } else if (strpos($fullUrl, "contract=updated") == true){
                  echo "<p class='success'>Record Updated!</p>";
                } else if (strpos($fullUrl, "contract=alreadyPending") == true){
                  echo "<p class='error'>Record is already pending!</p>";
                } else if (strpos($fullUrl, "update=empty") == true){
                  echo "<p class='error'>You did not fill in all the required fields!</p>";
                }

               ?>
            </div>

          </div>

        </div>
      </form>

      <script type="text/javascript">
      function displayAppropriateForm(){
        let status = document.getElementById('status').value;
        if (status == 'Terminated'){
          document.getElementById('terminationDate').style.display = '';
        }
        else {
          document.getElementById('terminationDate').style.display = 'none';
        }
       }

       function displayRequestData(){
         const requestBoxContent = document.getElementById('requestBoxContent');
         let jsRequestArray = <?php echo json_encode($requestData); ?>;
         for(i = 0; i < jsRequestArray.length; i++){
           let requestID = document.createElement('div');
           let specDateOfPickUp = document.createElement('div');
           let requestStatus = document.createElement('div');
           let transportationID = document.createElement('div');
           let dateOfActualPickUp = document.createElement('div');
           let transportationStatus = document.createElement('div');

           requestID.innerText = jsRequestArray[i]['requestID'];
           specDateOfPickUp.innerText = jsRequestArray[i]['specDateOfPickUp'];

           if(jsRequestArray[i]['requestStatus'] == 'U'){
             requestStatus.innerText = 'Unchanged';
           }
           else {
             requestStatus.innerText = 'Cancelled';
           }

           if(jsRequestArray[i]['transportationID'] == undefined){
             transportationID.innerText = 'Empty';
           }
           else{
             transportationID.innerText = jsRequestArray[i]['transportationID'];
           }

           if(jsRequestArray[i]['dateOfActualPickUp'] == undefined){
             dateOfActualPickUp.innerText = 'Empty';
           }
           else {
             dateOfActualPickUp.innerText = jsRequestArray[i]['dateOfActualPickUp'];
           }

           if(jsRequestArray[i]['transportationStatus'] == undefined){
             transportationStatus.innerText = 'Empty';
           }
           else {
             if(jsRequestArray[i]['transportationStatus'] == 'D') {
               transportationStatus.innerText = 'Delivered';
             }
             else if (jsRequestArray[i]['transportationStatus'] == 'P') {
               transportationStatus.innerText = 'Pending';
             }
             else if (jsRequestArray[i]['transportationStatus'] == 'S') {
               transportationStatus.innerText = 'Spill';
             }
             else if (jsRequestArray[i]['transportationStatus'] == 'F') {
               transportationStatus.innerText = 'Failure';
             }
           }


           requestBoxContent.append(requestID);
           requestBoxContent.append(specDateOfPickUp);
           requestBoxContent.append(requestStatus);
           requestBoxContent.append(transportationID);
           requestBoxContent.append(dateOfActualPickUp);
           requestBoxContent.append(transportationStatus);
           }
         }



       displayRequestData();
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
