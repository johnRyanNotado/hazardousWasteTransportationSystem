<?php
  session_start();
  if(!isset($_SESSION['name'])){
    header("Location: http://localhost:4000/HazardousWasteProj/Forms/AccessForm/AccountAccessForm.php");
  }
 ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Bill Report</title>
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Footer.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/General.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Header.css">
    <link rel="stylesheet" type="text/css" href="BillingReportForm.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href=
    "https://secureservercdn.net/198.71.233.110/s0i.8f2.myftpupload.com/wp-content/uploads/2020/03/cropped-5x3-1-32x32.png">

  </head>
  <body>
    <?php
      include "phpCodes/dbh.inc.php";
      $clientIDSet = '';
      if(isset($_GET['clientIDSet'])){
        $clientIDSet = $_GET['clientIDSet'];
      }
      $clientID = '';
      if(isset($_GET['clientID'])){
        $clientID = $_GET['clientID'];
      }
      $clientCompanyName = '';
      if(isset($_GET['clientCompanyName'])){
        $clientCompanyName = $_GET['clientCompanyName'];
      }
      $clientEmailAddress = '';
      if(isset($_GET['clientEmailAddress'])){
        $clientEmailAddress = $_GET['clientEmailAddress'];
      }
      $clientContactNum = '';
      if(isset($_GET['clientContactNum'])){
        $clientContactNum = $_GET['clientContactNum'];
      }


      $sql = "SELECT b.referenceNum, b.deliveryFee, b.penaltyFee,
       b.deliveryFee + b.penaltyFee as 'Total Fee',
      t.transportationID, t.transportationStatus, t.uRequestID,
      c.clientID, c.clientCompanyName
      FROM bill b
        LEFT JOIN request r
          ON r.requestID = b.requestID
        LEFT JOIN unchange u
          ON u.uRequestID = r.requestID
        LEFT JOIN transportation t
          ON u.uRequestID = t.uRequestID
        LEFT JOIN client c
          ON c.clientID = r.clientID
      WHERE b.tContractID IS NULL;";

      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        $result = mysqli_query($conn, $sql);
        $billingData = array();
        if (mysqli_num_rows($result) > 0){
          while ($row = mysqli_fetch_assoc($result)) {
            $billingData[] = $row;
          }
        }
      }

      $sql = "SELECT b.referenceNum, b.deliveryFee, b.penaltyFee,
       b.deliveryFee + b.penaltyFee as 'Total Fee',
      t.terminationDate, c.contractID, c.contractStatus,
      cl.clientID, cl.clientCompanyName
      FROM bill b
        LEFT JOIN terminatedContract t
          ON t.tContractID = b.tContractID
        LEFT JOIN contract c
          ON c.contractID = t.tContractID
        LEFT JOIN client cl
          ON cl.clientID = c.clientID
      WHERE b.requestID IS NULL;";

      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        $result = mysqli_query($conn, $sql);
        $contractData = array();
        if (mysqli_num_rows($result) > 0){
          while ($row = mysqli_fetch_assoc($result)) {
            $contractData[] = $row;
          }
        }
      }



      $sql = "SELECT p.paymentID, p.amountPaid, p.dateOfPayment, p.clientID, p.accountNum, c.clientCompanyName
      FROM payment p
        LEFT JOIN account a
          ON a.accountNum = p.accountNum
        LEFT JOIN client c
          ON c.clientID = a.clientID;";

      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        $result = mysqli_query($conn, $sql);
        $paymentData = array();
        if (mysqli_num_rows($result) > 0){
          while ($row = mysqli_fetch_assoc($result)) {
            $paymentData[] = $row;
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
              <p style="padding-top: 0;">Bill Report</p>
            </div>
            <hr style="margin-bottom: 20px;">
          </div>
          <form class="get" action="phpCodes/billingReport.inc.php" method="post">
            <input type="hidden" name="clientIDSet" value="<?php echo $clientIDSet;?>">
            <script type="text/javascript">
              console.log("<?php echo $clientIDSet;?>");
            </script>
            <div class="actionBox">
              <div class="actionDataForm">

                  <p class="inputTitle">Client ID</p>
                  <?php

                  if(isset($clientID)){
                    echo '<input class="clientID" type="text" name="clientID" placeholder="Client ID" maxlength="18" id="Client ID" value="'.$clientID.'">';
                  }
                  else {
                    echo '<input class="clientID" type="text" name="clientID" placeholder="Client ID" maxlength="18" id="Client ID">';
                  }
                   ?>

                  <div class="placeholder">
                    <?php
                    //Getting url for error checking
                      $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                      if (strpos($fullUrl, "register=empty") == true){
                        echo "<p class='error'>You did not fill in all the required fields.</p>";
                      } else if (strpos($fullUrl, "client=notFound") == true){
                        echo "<p class='error'>Record not found.</p>";
                      } else if (strpos($fullUrl, "client=found") == true){
                        echo "<p class='success'>Record found!</p>";
                      }

                     ?>
                  </div>
                  <div class="submit">
                    <button type="submit" name="button" style="width: 100px" value="Find">Find</button>
                  </div>
              </div>

            </div>


            <div class="employeeEditBox">
              <div class="employeeDataForm">
                  <p class="inputTitle">Company Name</p>
                  <?php
                    if(isset($clientCompanyName)){
                      echo '<input class="clientCompanyName" type="text" name="clientCompanyName" placeholder="Company Name" maxlength="40" value="'.$clientCompanyName.'" readonly>';
                    }
                    else {
                      echo '<input class="clientCompanyName" type="text" name="clientCompanyName" placeholder="Request ID" maxlength="40"  readonly>';
                    }
                   ?>
                     <p class="inputTitle">Contact #</p>
                     <?php
                       if(isset($clientContactNum)){
                         echo '<input class="clientContactNum" type="text" name="clientContactNum" placeholder="(09xxxxxxxxx)" maxlength="11" value="'.$clientContactNum.'" readonly>';
                       }
                       else {
                         echo '<input class="clientContactNum" type="text" name="clientContactNum" placeholder="(09xxxxxxxxx)" maxlength="11" readonly>';
                       }
                      ?>
                     <p class="inputTitle">Email</p>
                     <?php
                       if(isset($clientEmailAddress)){
                         echo '<input class="clientEmailAddress" type="text" name="clientEmailAddress" placeholder="Email" maxlength="30" value="'.$clientEmailAddress.'" readonly>';
                       }
                       else {
                         echo '<input class="clientEmailAddress" type="text" name="clientEmailAddress" placeholder="Email" maxlength="30" readonly> ';
                       }
                      ?>

                </div>

              </div>
              <div class="employeeSubtypeForm" id="employeeSubtypeForm">
              </div>
            <div class="confirmationContainer">
              <div class="buttons" id="buttons">

                <button type="submit" id="confirmButton" name="button" value="See" style="width:100px">See</button>

              </div>


              <a href="http://localhost:4000/HazardousWasteProj/Forms/BillingMainInterfaceForm/BillingMainInterfaceForm.php"
              >Go back</a>
            </div>
            </form>
          </div>
        </script>

      </div>
      <div class="Report">
        <div class="titleContainer">
          <div class="actionsTitle">
            <p style="padding-top: 0;">Bills and Payment Data</p>
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
            <input type="radio" name="choiceReport" value="Billing" id="choiceReport" onclick="choiceReportChange()" checked>
            <label for="Create">Transportation Bill</label>
          </div>
          <div class="radio">
            <input type="radio" name="choiceReport" value="Contract" id="choiceReport" onclick="choiceReportChange()">
            <label for="Update">Contract Bill</label>
          </div>

          <div class="radio">
            <input type="radio" name="choiceReport" value="Payment" id="choiceReport" onclick="choiceReportChange()">
            <label for="Update">Payment</label>
          </div>
        </div>
      </div>
    <script type="text/javascript">
      let jsArray;
      let choiceType;
      let tableHeader = document.getElementById('tableHeader');
      let tableContent = document.getElementById('tableContent');

      function displayPaymentData(jsArray, choiceType){
        tableContent.className = 'tableContent';
        tableHeader.className = 'tableHeader';
        createPaymentHeader();
        for(i = 0; i < jsArray.length; i++){

          let referenceNum = document.createElement('p');
          let amountPaid = document.createElement('p');
          let totalFee = document.createElement('p');
          let dateOfPayment = document.createElement('p');
          let paymentID = document.createElement('p');
          let clientCompanyName = document.createElement('p');
          let clientID = document.createElement('p');
          let transportationID = document.createElement('p');
          let contractID = document.createElement('p');


          referenceNum.innerText = jsArray[i]['referenceNum'];
          amountPaid.innerText = jsArray[i]['amountPaid'];
          totalFee.innerText = jsArray[i]['Total Due'];
          dateOfPayment.innerText = jsArray[i]['dateOfPayment'];
          paymentID.innerText = jsArray[i]['paymentID'];
          clientCompanyName.innerText = jsArray[i]['clientCompanyName'];
          clientID.innerText = jsArray[i]['clientID'];
          if(jsArray[i]['transportationID'] == undefined){
            transportationID.innerText = 'N/A';
          }
          else{
            transportationID.innerText = jsArray[i]['transportationID'];
          }
          if(jsArray[i]['tContractID'] == undefined){
          contractID.innerText = "N/A";
          }
          else {
          contractID.innerText = jsArray[i]['tContractID'];
          }




          tableContent.append(referenceNum);
          tableContent.append(amountPaid);
          tableContent.append(totalFee);
          tableContent.append(dateOfPayment);
          tableContent.append(paymentID);
          tableContent.append(clientCompanyName);
          tableContent.append(clientID);
          tableContent.append(transportationID);
          tableContent.append(contractID);
        }
      }

      function displayBillingData(jsArray, choiceType){
        tableContent.className = 'tableContent';
        tableHeader.className = 'tableHeader';
        createBillingHeader();

        for(i = 0; i < jsArray.length; i++){

          let referenceNum = document.createElement('p');
          let deliveryFee = document.createElement('p');
          let penaltyFee = document.createElement('p');
          let totalFee = document.createElement('p');
          let transportationStatus = document.createElement('p');
          let clientCompanyName = document.createElement('p');
          let clientID = document.createElement('p');
          let transportationID = document.createElement('p');
          let requestID = document.createElement('p');


          referenceNum.innerText = jsArray[i]['referenceNum'];
          deliveryFee.innerText = jsArray[i]['deliveryFee'];
          penaltyFee.innerText = jsArray[i]['penaltyFee'];
          totalFee.innerText = jsArray[i]['Total Fee'];
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
          clientCompanyName.innerText = jsArray[i]['clientCompanyName'];
          clientID.innerText = jsArray[i]['clientID'];
          transportationID.innerText = jsArray[i]['transportationID'];
          requestID.innerText = jsArray[i]['uRequestID'];



          tableContent.append(referenceNum);
          tableContent.append(deliveryFee);
          tableContent.append(penaltyFee);
          tableContent.append(totalFee);
          tableContent.append(transportationStatus);
          tableContent.append(clientCompanyName);
          tableContent.append(clientID);
          tableContent.append(transportationID);
          tableContent.append(requestID);
        }
      }

      function displayContractData(jsArray, choiceType){
        tableContent.className = 'tableContent';
        tableHeader.className = 'tableHeader';
        createContractHeader();

        for(i = 0; i < jsArray.length; i++){

          let referenceNum = document.createElement('p');
          let deliveryFee = document.createElement('p');
          let penaltyFee = document.createElement('p');
          let totalFee = document.createElement('p');
          let contractStatus = document.createElement('p');
          let clientCompanyName = document.createElement('p');
          let clientID = document.createElement('p');
          let contractID = document.createElement('p');
          let terminationDate = document.createElement('p');


          referenceNum.innerText = jsArray[i]['referenceNum'];
          deliveryFee.innerText = jsArray[i]['deliveryFee'];
          penaltyFee.innerText = jsArray[i]['penaltyFee'];
          totalFee.innerText = jsArray[i]['Total Fee'];
          if(jsArray[i]['contractStatus'] == 'P') {
            contractStatus.innerText = 'Pending';
          }
          else if (jsArray[i]['contractStatus'] == 'F') {
            contractStatus.innerText = 'Finished';
          }
          else if (jsArray[i]['contractStatus'] == 'T') {
            contractStatus.innerText = 'Terminated';
          }
          clientCompanyName.innerText = jsArray[i]['clientCompanyName'];
          clientID.innerText = jsArray[i]['clientID'];
          contractID.innerText = jsArray[i]['contractID'];
          terminationDate.innerText = jsArray[i]['terminationDate'];



          tableContent.append(referenceNum);
          tableContent.append(deliveryFee);
          tableContent.append(penaltyFee);
          tableContent.append(totalFee);
          tableContent.append(contractStatus);
          tableContent.append(clientCompanyName);
          tableContent.append(clientID);
          tableContent.append(contractID);
          tableContent.append(terminationDate);
        }
      }


      function createBillingHeader(){
        tableHeader.innerHTML = '';
        let arrayOfHeaderTitle = ['Reference Number', 'Delivery Fee', 'Penalty Fee',
        'Total Fee', 'Transportation Status', 'Company Name', 'Client ID', 'Transportation ID', 'Request ID'];
        writeHeader(arrayOfHeaderTitle);
      }

      function createPaymentHeader(){
        tableHeader.innerHTML = '';
        let arrayOfHeaderTitle = ['Amount Paid',
        'Date of Payment', 'Payment ID', 'Company Name', 'Client ID', 'Transportation ID', 'Contract ID'];
        writeHeader(arrayOfHeaderTitle);
      }

      function createContractHeader(){
        tableHeader.innerHTML = '';
        let arrayOfHeaderTitle = ['Reference Number', 'Delivery Fee', 'Penalty Fee',
        'Total Fee', 'Contract Status', 'Company Name', 'Client ID', 'Contract ID', 'Termination Date'];
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
          if (choiceType == 'Billing'){
            jsArray = <?php echo json_encode($billingData); ?>;
            displayBillingData(jsArray);
          }
          else if (choiceType == 'Payment') {
            jsArray = <?php echo json_encode($paymentData); ?>;
            displayPaymentData(jsArray);
          }
          else if (choiceType == 'Contract') {
            jsArray = <?php echo json_encode($contractData); ?>;
            displayContractData(jsArray);
          }
      }



      choiceReportChange();
    </script>

</main>

  </body>
</html>
