<?php
  session_start();
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
    <link rel="stylesheet" type="text/css" href="UpdateTransportationForm.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href=
    "https://secureservercdn.net/198.71.233.110/s0i.8f2.myftpupload.com/wp-content/uploads/2020/03/cropped-5x3-1-32x32.png">

  </head>
  <body>
    <?php
      include "phpCodes/dbh.inc.php";

      $sql = "SELECT t.transportationID, r.requestID, r.contractID, c.clientID, c.clientCompanyName,
      r.denrRefCode, t.dateOfActualPickUp, t.transportationStatus
      FROM client c
        RIGHT JOIN request r
          ON c.clientID = r.clientID
        RIGHT JOIN unchange u
          ON u.uRequestID = r.requestID
        RIGHT JOIN transportation t
          ON u.uRequestID = t.uRequestID
      WHERE t.transportationStatus = 'P'
      ORDER BY t.dateOfActualPickUp;";

      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        $result = mysqli_query($conn, $sql);
        $pendingData = array();
        if (mysqli_num_rows($result) > 0){
          while ($row = mysqli_fetch_assoc($result)) {
            $pendingData[] = $row;
          }
        }
      }


      $sql = "SELECT t.transportationID, r.requestID, c.clientID, r.contractID, c.clientCompanyName,
      r.denrRefCode, t.dateOfActualPickUp, t.transportationStatus
      FROM client c
        RIGHT JOIN request r
          ON c.clientID = r.clientID
        RIGHT JOIN unchange u
          ON u.uRequestID = r.requestID
        RIGHT JOIN transportation t
          ON u.uRequestID = t.uRequestID
      WHERE t.transportationStatus = 'D'
      ORDER BY t.dateOfActualPickUp;";

      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        $result = mysqli_query($conn, $sql);
        $deliveredData = array();
        if (mysqli_num_rows($result) > 0){
          while ($row = mysqli_fetch_assoc($result)) {
            $deliveredData[] = $row;
          }
        }
      }

      $sql = "SELECT t.transportationID, r.requestID, r.contractID, c.clientID, c.clientCompanyName,
      r.denrRefCode, t.dateOfActualPickUp, t.transportationStatus
      FROM client c
        RIGHT JOIN request r
          ON c.clientID = r.clientID
        RIGHT JOIN unchange u
          ON u.uRequestID = r.requestID
        RIGHT JOIN transportation t
          ON u.uRequestID = t.uRequestID
      WHERE t.transportationStatus = 'F'
      ORDER BY t.dateOfActualPickUp;";

      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        $result = mysqli_query($conn, $sql);
        $failureData = array();
        if (mysqli_num_rows($result) > 0){
          while ($row = mysqli_fetch_assoc($result)) {
            $failureData[] = $row;
          }
        }
      }

      $sql = "SELECT t.transportationID, r.requestID, r.contractID, c.clientID, c.clientCompanyName,
      r.denrRefCode, t.dateOfActualPickUp, t.transportationStatus
      FROM client c
        RIGHT JOIN request r
          ON c.clientID = r.clientID
        RIGHT JOIN unchange u
          ON u.uRequestID = r.requestID
        RIGHT JOIN transportation t
          ON u.uRequestID = t.uRequestID
      WHERE t.transportationStatus = 'S'
      ORDER BY t.dateOfActualPickUp;";

      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        $result = mysqli_query($conn, $sql);
        $spillData = array();
        if (mysqli_num_rows($result) > 0){
          while ($row = mysqli_fetch_assoc($result)) {
            $spillData[] = $row;
          }
        }
      }



      $sql = "SELECT t.transportationID, r.requestID, r.contractID, c.clientID, c.clientCompanyName,
      r.denrRefCode, t.dateOfActualPickUp, t.transportationStatus
      FROM client c
        RIGHT JOIN request r
          ON c.clientID = r.clientID
        RIGHT JOIN unchange u
          ON u.uRequestID = r.requestID
        RIGHT JOIN transportation t
          ON u.uRequestID = t.uRequestID
      ORDER BY t.dateOfActualPickUp;";

      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        $result = mysqli_query($conn, $sql);
        $allData = array();
        if (mysqli_num_rows($result) > 0){
          while ($row = mysqli_fetch_assoc($result)) {
            $allData[] = $row;
          }
        }
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
              <p style="padding-top: 0;">Transportation Request</p>
            </div>
            <hr style="margin-bottom: 20px;">
          </div>
          <form class="get" action="phpCodes/getThisTransportation.php" method="post">
            <div class="actionBox">
              <div class="actionDataForm">

                  <p class="inputTitle">Transportation ID</p>
                  <?php

                  if(isset($_GET['transportationID'])){
                    $transportationIDSet = $_GET['transportationID'];
                    echo '<input class="transportationID" type="text" name="transportationID" placeholder="Transportation ID" maxlength="18" id="employeeID" value="'.$_GET['transportationID'].'">';
                  }
                  else {
                    echo '<input class="transportationID" type="text" name="transportationID" placeholder="Transportation ID" maxlength="18" id="employeeID">';
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
                      }

                     ?>
                  </div>
                  <div class="submit">
                    <button type="submit" name="button" style="width: 100px;">Find</button>
                  </div>
              </div>

            </div>
          </form>

          <form class="employeeEditForm" action="phpCodes\UpdateTransportation.inc.php" method="post">
            <input type="hidden" name="transportationIDSet" value="<?php echo $transportationIDSet; ?>">
            <div class="employeeEditBox">
              <div class="employeeDataForm">
                  <p class="inputTitle">Request ID</p>
                  <?php
                    if(isset($_GET['requestID'])){
                      echo '<input class="requestID" type="text" name="requestID" placeholder="Request ID" maxlength="18" value="'.$_GET['requestID'].'" readonly>';
                    }
                    else {
                      echo '<input class="requestID" type="text" name="requestID" placeholder="Request ID" maxlength="18">';
                    }
                   ?>
                  <p class="inputTitle">Client ID</p>
                  <?php
                    if(isset($_GET['clientID'])){
                      echo '<input class="clientID" type="text" name="clientID" placeholder="Client ID" maxlength="18" value="'.$_GET['clientID'].'" readonly>';
                    }
                    else {
                      echo '<input class="clientID" type="text" name="clientID" placeholder="Client ID" maxlength="18">';
                    }
                   ?>
                   <p class="inputTitle">Company Name</p>
                   <?php
                     if(isset($_GET['clientCompanyName'])){
                       echo '<input class="clientCompanyName" type="text" name="clientCompanyName" placeholder="Company Name" maxlength="40" value="'.$_GET['clientCompanyName'].'" readonly>';
                     }
                     else {
                       echo '<input class="clientCompanyName" type="text" name="clientCompanyName" placeholder="Company Name" maxlength="40">';
                     }
                    ?>
                    <p class="inputTitle">Denr Ref Code</p>
                    <?php
                      if(isset($_GET['denrRefCode'])){
                        echo '<input class="denrRefCode" type="text" name="denrRefCode" placeholder="DENR Reference Code" maxlength="20" value="'.$_GET['denrRefCode'].'" readonly>';
                      }
                      else {
                        echo '<input class="denrRefCode" type="text" name="denrRefCode" placeholder="DENR Reference Code" maxlength="20">';
                      }
                     ?>
                  <p class="inputTitle">Date of Pickup</p>

                  <?php
                    if(isset($_GET['dateOfActualPickUp'])){
                      echo '<input class="dateOfActualPickUp" type="date" name="dateOfActualPickUp" value="'.$_GET['dateOfActualPickUp'].'"  readonly>';
                    }
                    else {
                      echo '<input class="dateOfActualPickUp" type="date" name="dateOfActualPickUp" >';
                    }
                   ?>

                  <p class="inputTitle">Transportation Status</p>

                  <?php
                  $status = ['Pending', 'Delivered', 'Failure', 'Spill'];

                    if(isset($_GET['transportationStatus'])){
                      if($_GET['transportationStatus'] == 'P'){
                        $selectedStatus = "Pending";
                      }
                      else if($_GET['transportationStatus'] == 'D'){
                        $selectedStatus = "Delivered";
                      }
                      else if($_GET['transportationStatus'] == 'F'){
                        $selectedStatus = "Failure";
                      }
                      else if($_GET['transportationStatus'] == 'S'){
                        $selectedStatus = "Spill";
                      }
                      echo '<select name="transportationStatus" placeholder="Status" disabled>';
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
                      echo '<select name="transportationStatus" placeholder="Status">';
                      foreach($status as $state){
                          echo "<option value=$state>$state</option>";
                      }
                    }
                   ?>
                    </select>
                </div>

              </div>
              <div class="employeeSubtypeForm" id="employeeSubtypeForm">
              </div>
            <div class="confirmationContainer">
              <div class="buttons" id="buttons">

                <button type="submit" id="confirmButton" name="actionBottom" value="See" style="width:100px">See</button>

              </div>


              <a href="http://localhost:4000/HazardousWasteProj/Forms/TransportationMainInterfaceForm/TransportationMainInterfaceForm.php?"
              >Go back</a>
            </div>
            </form>
          </div>
        </script>

      </div>
      <div class="Report">
        <div class="titleContainer">
          <div class="actionsTitle">
            <p style="padding-top: 0;">Transportation Data</p>
          </div>
          <hr style="margin-bottom: 20px;">
        </div>
        <div class="reportBox" style="height: 517px;">
          <div class="tableHeader" id="tableHeader">

          </div>
          <div class="tableContent" id="tableContent">

          </div>
        </div>
        <div class="radioButtonsContainer">
          <div class="radio">
            <input type="radio" name="choiceReport" value="Pending" id="choiceReport" onclick="choiceReportChange()" checked>
            <label for="Create">Pending</label>
          </div>

          <div class="radio">
            <input type="radio" name=choiceReport value="Delivered" id="choiceReport" onclick="choiceReportChange()">
            <label for="Update">Delivered</label>
          </div>

          <div class="radio">
            <input type="radio" name=choiceReport value="Failure" id="choiceReport" onclick="choiceReportChange()">
            <label for="Update">Failure</label>
          </div>

          <div class="radio">
            <input type="radio" name=choiceReport value="Spill" id="choiceReport" onclick="choiceReportChange()">
            <label for="Update">Spill</label>
          </div>

          <div class="radio">
            <input type="radio" name=choiceReport value= "All" id="choiceReport" onclick="choiceReportChange()">
            <label for="Update">All</label>
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

          let transportationID = document.createElement('p');
          let requestID = document.createElement('p');
          let clientID = document.createElement('p');
          let dateOfActualPickUp = document.createElement('p');
          let transportationStatus = document.createElement('p');
          let denrRefCode = document.createElement('p');
          let clientCompanyName = document.createElement('p');
          let contractID = document.createElement('p');

          transportationID.innerText = jsArray[i]['transportationID'];
          requestID.innerText = jsArray[i]['requestID'];
          clientID.innerText = jsArray[i]['clientID'];
          dateOfActualPickUp.innerText = jsArray[i]['dateOfActualPickUp'];
          if(jsArray[i]['transportationStatus'] == 'P') {
            transportationStatus.innerText = 'Pending';
          }
          else if (jsArray[i]['transportationStatus'] == 'D') {
            transportationStatus.innerText = 'Delivered';
          }
          else if (jsArray[i]['transportationStatus'] == 'S') {
            transportationStatus.innerText = 'Spill';
          }
          else if (jsArray[i]['transportationStatus'] == 'F') {
            transportationStatus.innerText = 'Failure';
          }
          denrRefCode.innerText = jsArray[i]['denrRefCode'];
          clientCompanyName.innerText = jsArray[i]['clientCompanyName'];

          if(jsArray[i]['contractID'] == undefined){
            contractID.innerText = 'N/A';
          }
          else {
            contractID.innerText = jsArray[i]['contractID'];
          }

          tableContent.append(transportationID);
          tableContent.append(dateOfActualPickUp);
          tableContent.append(transportationStatus);
          tableContent.append(denrRefCode);
          tableContent.append(clientCompanyName);
          tableContent.append(requestID);
          tableContent.append(clientID);
          tableContent.append(contractID);
        }
      }

      function createAllHeader(){
        tableHeader.innerHTML = '';
        let arrayOfHeaderTitle = ['TransportationID', 'Date of Pickup', 'Status', 'DENR Ref Code','Company Name', 'Request ID','Client ID','Contract ID'];
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
          if (choiceType == 'Pending'){
            jsArray = <?php echo json_encode($pendingData); ?>;
          }
          else if (choiceType == 'Delivered') {
            jsArray = <?php echo json_encode($deliveredData); ?>;
          }
          else if (choiceType == 'Failure'){
            jsArray = <?php echo json_encode($failureData); ?>;
          }
          else if (choiceType == 'Spill'){
            jsArray = <?php echo json_encode($spillData); ?>;
          }
          else if (choiceType == 'All'){
            jsArray = <?php echo json_encode($allData); ?>;
          }
          displayAllData(jsArray, choiceType);
      }



      choiceReportChange();
    </script>

</main>

  </body>
</html>
