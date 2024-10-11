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
    <title>Bill Report</title>
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Footer.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/General.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Header.css">
    <link rel="stylesheet" type="text/css" href="LedgerReportForm.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href=
    "https://secureservercdn.net/198.71.233.110/s0i.8f2.myftpupload.com/wp-content/uploads/2020/03/cropped-5x3-1-32x32.png">

  </head>
  <body>
    <?php

    $clientID = $_GET['clientID'];

    $sql = "SELECT c.clientID,c.denrGenID, c.clientCompanyName, a.accountNum
    FROM client c
      LEFT JOIN account a
        ON a.clientID = c.clientID
    WHERE c.clientID = ?;";

    $stmt = mysqli_stmt_init($conn);


    if (!mysqli_stmt_prepare($stmt, $sql)){
      echo "sql error";
    }
    else {
      mysqli_stmt_bind_param($stmt, "s", $clientID);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $row = mysqli_fetch_assoc($result);
      $clientData = $row;
      $accountNum = $row['accountNum'];
    }

    $sql = "SELECT b.deliveryFee, b.penaltyFee, b.referenceNum,
    b.deliveryFee + b.penaltyFee as 'totalFee', r.specDateOfPickUp
    FROM account a
      RIGHT JOIN bill b
        ON a.accountNum = b.accountNum
      LEFT JOIN request r
        ON r.requestID = b.requestID
    WHERE b.tContractID IS NULL AND a.accountNum = ?
    ORDER BY r.specDateOfPickUp;";

    $stmt = mysqli_stmt_init($conn);


    if (!mysqli_stmt_prepare($stmt, $sql)){
      echo "sql error";
    }
    else {
      mysqli_stmt_bind_param($stmt, "s", $accountNum);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $billingData = array();
      if (mysqli_num_rows($result) > 0){
        while ($row = mysqli_fetch_assoc($result)) {
          $billingData[] = $row;
        }
      }
    }

    $sql = "SELECT b.deliveryFee, b.penaltyFee, b.referenceNum, t.terminationDate,
    b.deliveryFee + b.penaltyFee as 'totalFee'
    FROM client cl
      RIGHT JOIN contract c
        ON c.clientID = cl.clientID
      RIGHT JOIN terminatedContract t
        ON t.tContractID = c.contractID
      RIGHT JOIN bill b
        ON b.tContractID = t.tContractID
    WHERE b.requestID IS NULL AND c.clientID = ?
    ORDER BY t.terminationDate;";

    $stmt = mysqli_stmt_init($conn);


    if (!mysqli_stmt_prepare($stmt, $sql)){
      echo "sql error";
    }
    else {
      mysqli_stmt_bind_param($stmt, "s", $clientID);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $contractData = array();
      if (mysqli_num_rows($result) > 0){
        while ($row = mysqli_fetch_assoc($result)) {
          $contractData[] = $row;
        }
      }
    }
    $sql = "SELECT SUM(b.deliveryFee) 'totalDeliveryFee', SUM(b.penaltyFee) 'totalPenaltyFee'
    FROM account a
      RIGHT JOIN bill b
        ON b.accountNum = a.accountNum
    WHERE a.accountNum = ?;";

    $stmt = mysqli_stmt_init($conn);

    $totalDeliveryFee = 0;
    $totalPenaltyFee = 0;
    if (!mysqli_stmt_prepare($stmt, $sql)){
      echo "sql error";
    }
    else {
      mysqli_stmt_bind_param($stmt, "s", $accountNum);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      if ($row = mysqli_fetch_assoc($result)) {
          $totalDeliveryFee += $row['totalDeliveryFee'];
          $totalPenaltyFee += $row['totalPenaltyFee'];
      }
    }

    $sql = "SELECT SUM(p.amountPaid) 'totalAmountPaid'
    FROM account a
      RIGHT JOIN payment p
        ON p.accountNum = a.accountNum
    WHERE a.accountNum = ?;";

    $stmt = mysqli_stmt_init($conn);

    $totalAmountPaid = 0;
    if (!mysqli_stmt_prepare($stmt, $sql)){
      echo "sql error";
    }
    else {
      mysqli_stmt_bind_param($stmt, "s", $accountNum);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      if ($row = mysqli_fetch_assoc($result)) {
          $totalAmountPaid += $row['totalAmountPaid'];
      }
    }


    $sql = "SELECT p.accountNum, p.paymentID, p.amountPaid, p.dateOfPayment
    FROM client cl
      RIGHT JOIN payment p
        ON p.clientID = cl.clientID
    WHERE cl.clientID = ?
    ORDER BY p.dateOfPayment;";

    $stmt = mysqli_stmt_init($conn);


    if (!mysqli_stmt_prepare($stmt, $sql)){
      echo "sql error";
    }
    else {
      mysqli_stmt_bind_param($stmt, "s", $clientID);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $paymentData = array();
      if (mysqli_num_rows($result) > 0) {
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
        <form class="logout_form" action="phpCodes/home.php" method="post">
          <button type="submit" name="home_button">Home</button>
        </form>
        <form class="logout_form" action="phpCodes/logout.php" method="post">
          <button type="submit" name="logout_button">Logout</button>
        </form>
      </div>
    </header>
    <main>
      <div class="biggestContainer">
        <div class="clientInfoBox">
          <div class="boxHeader">
            Client Information
          </div>
          <div class="boxBody">
            <div id="titleContainerClient">

            </div>
            <div id="contentContainerCient">

            </div>
          </div>
        </div>
        <div class="clientStatementOfAccountBox">
          <div class="boxHeader">
            Statement Of Account
          </div>
          <div class="boxBody">
            <div id="titleContainerSOA">

            </div>
            <div id="contentContainerSOA">

            </div>
          </div>
        </div>
        <div class="clientListOfBillBox">
          <div class="boxHeader">
            Transportation Bills
          </div>
          <div class="boxBodyBill" id="contentContainerBills">
            <p style="text-decoration: underline;">
              Reference Number
            </p>
            <p style="text-decoration: underline;">
              Delivery Fee
            </p>
            <p style="text-decoration: underline;">
              Penalty Fee
            </p>
            <p  style="text-decoration: underline;">
              Total Fee
            </p>
            <p  style="text-decoration: underline;">
              Date Of Pickup
            </p>
          </div>
        </div>
        <div class="clientListOfBillBox">
          <div class="boxHeader">
            Contract Termination Bills
          </div>
          <div class="boxBodyBill" id="contentContainerContract">
            <p style="text-decoration: underline;">
              Reference Number
            </p>
            <p style="text-decoration: underline;">
              Delivery Fee
            </p>
            <p style="text-decoration: underline;">
              Penalty Fee
            </p>
            <p  style="text-decoration: underline;">
              Total Fee
            </p>
            <p  style="text-decoration: underline;">
              Termination Date
            </p>
          </div>
        </div>
        <div class="clientListOfPaymentBox">
          <div class="boxHeader">
            Payments
          </div>
          <div class="boxBodyPayment" id="contentContainerPayment">
            <p style="text-decoration: underline;">
              Account Number
            </p>
            <p style="text-decoration: underline;">
              Payment ID
            </p>
            <p style="text-decoration: underline;">
              Amount Paid
            </p>
            <p style="text-decoration: underline;">
              Payment Date
            </p>
          </div>
        </div>
      </div>
    </main>
    <footer class="footer">
      <p>Working towards sustainability for a better and safer environment.
        | © 2020 JM Ecotech Solutions Company. All Rights Reserved.
      </p>
    </footer>

    <script type="text/javascript">
    const billingData= <?php echo json_encode($billingData); ?>;
    const clientData= <?php echo json_encode($clientData); ?>;
    const paymentData= <?php echo json_encode($paymentData); ?>;

    const contractData= <?php echo json_encode($contractData); ?>;

    function displayClientData(){
      const titleContainerClient = document.getElementById('titleContainerClient');
      const contentContainerClient = document.getElementById('contentContainerClient');

      const clientID = document.createElement('p');
      const denrGenID = document.createElement('p');
      const clientCompanyName = document.createElement('p');

      const clientAccountNumber = document.createElement('p');

      clientID.innerText = "<?php echo $clientID; ?>";
      denrGenID.innerText = clientData['denrGenID'];
      clientCompanyName.innerText = clientData['clientCompanyName'];
      clientAccountNumber.innerText = clientData['accountNum'];

      contentContainerCient.append(clientID);
      contentContainerCient.append(denrGenID);
      contentContainerCient.append(clientCompanyName);

      contentContainerCient.append(clientAccountNumber);
    }

    function displaySOAData(){
      const titleContainerSoa = document.getElementById('titleContainerSOA');
      const contentContainerSOA = document.getElementById('contentContainerSOA');

      const totalBills = document.createElement('p');
      const totalAmountPaid = document.createElement('p');
      const totalAmountObligation = document.createElement('p');

      const totalBillVar = parseFloat('<?php echo $totalDeliveryFee + $totalPenaltyFee ?>');
      const totalPaymentVar = parseFloat('<?php echo $totalAmountPaid ?>');
      const totalAmountObligationVar = parseFloat(('<?php echo $totalDeliveryFee + $totalPenaltyFee ?>') - '<?php echo $totalAmountPaid ?>');
      totalBills.innerText = "PHP " + totalBillVar.toFixed(2);
      totalAmountPaid.innerText = "PHP " + totalPaymentVar.toFixed(2);
      totalAmountObligation.innerText = "PHP " + totalAmountObligationVar.toFixed(2);


      contentContainerSOA.append(totalBills);
      contentContainerSOA.append(totalAmountPaid);
      contentContainerSOA.append(totalAmountObligation);
    }

    function displayBillsData(){
      const contentContainerBills = document.getElementById('contentContainerBills');

      for(i = 0; i < billingData.length; i++){
        let indivRefNum = document.createElement('p');
        let indivDelFeeData = document.createElement('p');
        let indivPenFeeData = document.createElement('p');
        let indivTotalFee = document.createElement('p');
        let indivDateOfPickUp = document.createElement('p');

        let indivTotalFeeVar = billingData[i]['totalFee'];
        indivRefNum.innerText = billingData[i]['referenceNum'];
        indivDelFeeData.innerText = "PHP " + billingData[i]['deliveryFee'];
        indivPenFeeData.innerText = "PHP " + billingData[i]['penaltyFee'];
        indivTotalFee.innerText = "PHP " + indivTotalFeeVar.toFixed(2);
        indivDateOfPickUp.innerText = billingData[i]['specDateOfPickUp'];

        contentContainerBills.append(indivRefNum);
        contentContainerBills.append(indivDelFeeData);
        contentContainerBills.append(indivPenFeeData);
        contentContainerBills.append(indivTotalFee);
        contentContainerBills.append(indivDateOfPickUp);
      }
    }

    function displayContractData(){
      const contentContainerBills = document.getElementById('contentContainerContract');

      for(i = 0; i < contractData.length; i++){
        let indivRefNum = document.createElement('p');
        let indivDelFeeData = document.createElement('p');
        let indivPenFeeData = document.createElement('p');
        let indivTotalFee = document.createElement('p');
        let indivTerminationDate = document.createElement('p');

        let indivTotalFeeVar = contractData[i]['totalFee'];
        indivRefNum.innerText = contractData[i]['referenceNum'];
        indivDelFeeData.innerText = "PHP " + contractData[i]['deliveryFee'];
        indivPenFeeData.innerText = "PHP " + contractData[i]['penaltyFee'];
        indivTotalFee.innerText = "PHP " + indivTotalFeeVar.toFixed(2);
        indivTerminationDate.innerText = contractData[i]['terminationDate'];

        contentContainerBills.append(indivRefNum);
        contentContainerBills.append(indivDelFeeData);
        contentContainerBills.append(indivPenFeeData);
        contentContainerBills.append(indivTotalFee);
        contentContainerBills.append(indivTerminationDate);
      }
    }



    function displayPaymentData(){
      const contentContainerPayment = document.getElementById('contentContainerPayment');

      for(i = 0; i < paymentData.length; i++){
        let indivRefNum = document.createElement('p');
        let indivPaymentID = document.createElement('p');
        let indivAmountPaid = document.createElement('p');

        let indivDateOfPayment = document.createElement('p');

        indivRefNum.innerText = paymentData[i]['accountNum'];
        indivPaymentID.innerText = paymentData[i]['paymentID'];
        indivAmountPaid.innerText = "PHP " + paymentData[i]['amountPaid'];
        indivDateOfPayment.innerText = paymentData[i]['dateOfPayment'];

        if(paymentData[i]['paymentID'] != undefined){
          contentContainerPayment.append(indivRefNum);
          contentContainerPayment.append(indivPaymentID);
          contentContainerPayment.append(indivAmountPaid);
          contentContainerPayment.append(indivDateOfPayment);
        }
      }
    }


    function insertTitles(){
      const clientDataTitles = ['Client ID', 'DENR ID', 'Company Name', 'Account Number'];
      const clientSOATitles = ['Total Bills', 'Total Amount Paid', 'Total Amount of Obligation'];

      for(i = 0; i < clientDataTitles.length; i++){
        let title = document.createElement('p');
        title.innerText = clientDataTitles[i];
        titleContainerClient.append(title);
      }
      for(i = 0; i < clientSOATitles.length; i++){
        let title = document.createElement('p');
        title.innerText = clientSOATitles[i];
        titleContainerSOA.append(title);
      }
    }

    insertTitles();
    displayClientData();
    displaySOAData();
    displayBillsData();
    displayContractData();
    displayPaymentData();
    </script>
  </body>
</html>
