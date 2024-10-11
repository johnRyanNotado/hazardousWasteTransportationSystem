<?php
  session_start();
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
    <link rel="stylesheet" type="text/css" href="ContractUpdateForm.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href=
    "https://secureservercdn.net/198.71.233.110/s0i.8f2.myftpupload.com/wp-content/uploads/2020/03/cropped-5x3-1-32x32.png">

  </head>
  <body>
    <?php
      include "phpCodes/dbh.inc.php";

      $sql = "SELECT c.contractID, c.startDate, c.endDate, c.numOfRequest,
      c.preTerminationFee, c.contractStatus, c.clientID, cl.clientCompanyName
      FROM contract c
        INNER JOIN client cl
          ON cl.clientID = c.clientID
      WHERE c.contractStatus = 'P'
      ORDER BY c.startDate desc";

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

      $sql = "SELECT c.contractID, c.startDate, c.endDate, c.numOfRequest,
      c.preTerminationFee, c.contractStatus, c.clientID, cl.clientCompanyName
      FROM contract c
        RIGHT JOIN client cl
          ON cl.clientID = c.clientID
      WHERE c.contractStatus = 'T'
      ORDER BY c.startDate desc";

      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        $result = mysqli_query($conn, $sql);
        $terminatedData = array();
        if (mysqli_num_rows($result) > 0){
          while ($row = mysqli_fetch_assoc($result)) {
            $terminatedData[] = $row;
          }
        }
      }

      $sql = "SELECT c.contractID, c.startDate, c.endDate, c.numOfRequest,
      c.preTerminationFee, c.contractStatus, c.clientID, cl.clientCompanyName
      FROM contract c
        RIGHT JOIN client cl
          ON cl.clientID = c.clientID
      WHERE c.contractStatus = 'F'
      ORDER BY c.startDate desc";

      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        $result = mysqli_query($conn, $sql);
        $finishedData = array();
        if (mysqli_num_rows($result) > 0){
          while ($row = mysqli_fetch_assoc($result)) {
            $finishedData[] = $row;
          }
        }
      }

      $sql = "SELECT c.contractID, c.startDate, c.endDate, c.numOfRequest,
      c.preTerminationFee, c.contractStatus, c.clientID, cl.clientCompanyName
      FROM contract c
        LEFT JOIN client cl
          ON cl.clientID = c.clientID
      ORDER BY c.startDate desc";

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

      $contractIDSet;
      if(isset($_GET['contractIDSet'])){
        $contractIDSet = $_GET['contractIDSet'];
      }
      else{
        $contractIDSet = "";
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
              <p style="padding-top: 0;">Contract Update</p>
            </div>
            <hr style="margin-bottom: 20px;">
          </div>
          <form class="get" action="phpCodes/getThisContract.php" method="post">
            <div class="actionBox">
              <div class="actionDataForm">

                  <p class="inputTitle">Contract ID</p>
                  <?php

                  if(isset($_GET['contractID'])){
                    echo '<input class="contractID" type="text" name="contractID" placeholder="Contract ID" maxlength="18" id="employeeID" value="'.$_GET['contractID'].'">';
                  }
                  else {
                    echo '<input class="contractID" type="text" name="contractID" placeholder="Contract ID" maxlength="18" id="employeeID">';
                  }
                   ?>

                  <div class="placeholder">
                    <?php
                    //Getting url for error checking
                      $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                      if (strpos($fullUrl, "find=dontexist") == true){
                        echo "<p class='error'>The contract is not in the database.</p>";
                      } elseif (strpos($fullUrl, "find=notmatch") == true){
                        echo "<p class='error'>*This field is required -></p>";
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

          <form class="employeeEditForm" action="phpCodes\ContractReport.inc.php" method="post">
            <input type="hidden" name="contractIDSet" value="<?php echo $contractIDSet; ?>">
            <div class="employeeEditBox">
              <div class="employeeDataForm">
                  <p class="inputTitle">Client ID</p>
                    <?php
                    if(isset($_GET['clientID'])){
                      echo '<input class="clientID" type="text" name="clientID" placeholder="Client ID" maxlength="18" value="'.$_GET['clientID'].'" readonly>';
                    }
                    else {
                      echo '<input class="clientID" type="text" name="clientID" placeholder="Client ID" maxlength="18">';
                    }
                   ?>
                    <p class="inputTitle">DENR ID</p>
                    <?php
                      if(isset($_GET['denrGenID'])){
                        echo '<input class="denrGenID" type="text" name="denrGenID" placeholder="DENR ID" maxlength="20" value="'.$_GET['denrGenID'].'" readonly>';
                      }
                      else {
                        echo '<input class="denrGenID" type="text" name="denrGenID" placeholder="DENR ID" maxlength="20">';
                      }
                     ?>
                  <p class="inputTitle">Contract Status</p>

                  <?php
                  $status = ['Pending', 'Terminated', 'Finished'];

                    if(isset($_GET['contractStatus'])){
                      if($_GET['contractStatus'] == 'P'){
                        $selectedStatus = "Pending";
                      }
                      else if($_GET['contractStatus'] == 'T'){
                        $selectedStatus = "Terminated";
                      }
                      else if($_GET['contractStatus'] == 'F'){
                        $selectedStatus = "Finished";
                      }
                      echo '<select name="contractStatus" placeholder="Status" disabled>';
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

              </div>
              <div class="employeeSubtypeForm" id="employeeSubtypeForm">
              </div>
            <div class="confirmationContainer">
              <div class="buttons" id="buttons">

                <button type="submit" id="confirmButton" name="actionBottom" value="See" style="width:100px">See</button>

              </div>


              <a href="http://localhost:4000/HazardousWasteProj/Forms/RequestMainInterfaceForm/RequestMainInterfaceForm.php?"
              >Go back</a>
            </div>
            </form>
          </div>
        </script>

      </div>
      <div class="Report">
        <div class="titleContainer">
          <div class="actionsTitle">
            <p style="padding-top: 0;">Contract Data</p>
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
            <input type="radio" name=choiceReport value="Terminated" id="choiceReport" onclick="choiceReportChange()">
            <label for="Update">Terminated</label>
          </div>

          <div class="radio">
            <input type="radio" name=choiceReport value="Finished" id="choiceReport" onclick="choiceReportChange()">
            <label for="Update">Finished</label>
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

          let contractID = document.createElement('p');
          let startDate = document.createElement('p');
          let endDate = document.createElement('p');
          let numOfRequest = document.createElement('p')
          let preTerminationFee = document.createElement('p');
          let contractStatus = document.createElement('p');
          let clientCompanyName = document.createElement('p');
          let clientID = document.createElement('p');

          contractID.innerText = jsArray[i]['contractID'];
          startDate.innerText = jsArray[i]['startDate'];
          endDate.innerText = jsArray[i]['endDate'];
          preTerminationFee.innerText = jsArray[i]['preTerminationFee'];
          numOfRequest.innerText = jsArray[i]['numOfRequest'];
          if(jsArray[i]['contractStatus'] == 'P') {
            contractStatus.innerText = 'Pending';
          }
          else if (jsArray[i]['contractStatus'] == 'T') {
            contractStatus.innerText = 'Terminated';
          }
          else if (jsArray[i]['contractStatus'] == 'F') {
            contractStatus.innerText = 'Finished';
          }
          clientCompanyName.innerText = jsArray[i]['clientCompanyName'];
          clientID.innerText = jsArray[i]['clientID'];


          tableContent.append(contractID);
          tableContent.append(startDate);
          tableContent.append(endDate);
          tableContent.append(numOfRequest);
          tableContent.append(preTerminationFee);
          tableContent.append(contractStatus);
          tableContent.append(clientCompanyName);
          tableContent.append(clientID);
        }
      }

      function createAllHeader(){
        tableHeader.innerHTML = '';
        let arrayOfHeaderTitle = ['Contract ID', 'Start Date', 'End Date', 'Num# of Request','Pre-Termination Fee', 'Status', 'Company Name','Client ID'];
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
          else if (choiceType == 'Terminated') {
            jsArray = <?php echo json_encode($terminatedData); ?>;
          }
          else if (choiceType == 'Finished'){
            jsArray = <?php echo json_encode($finishedData); ?>;
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
