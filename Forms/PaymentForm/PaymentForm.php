<?php
  session_start();
  if(!isset($_SESSION['name'])){
    header("Location: http://localhost:4000/HazardousWasteProj/Forms/AccessForm/AccountAccessForm.php");
  }
 ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Payment Form</title>
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Footer.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/General.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Header.css">
    <link rel="stylesheet" type="text/css" href="PaymentForm.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href=
    "https://secureservercdn.net/198.71.233.110/s0i.8f2.myftpupload.com/wp-content/uploads/2020/03/cropped-5x3-1-32x32.png">

  </head>
  <body>
    <?php
      if(isset($_GET['accountNum'])){
        $accountNum = $_GET['accountNum'];
      }
      if(isset($_GET['accountNumSet'])){
        $accountNumSet = $_GET['accountNumSet'];
      }
      if(isset($_GET['deliveryFee']) || isset($_GET['penaltyFee'])){
        // $penaltyFee = $_GET['penaltyFee'];
        // $deliveryFee = $_GET['deliveryFee'];
      }
      if(isset($_GET['clientID'])){
        $clientID = $_GET['clientID'];
      }
      if(isset($_GET['clientCompanyName'])){
        $clientCompanyName = $_GET['clientCompanyName'];
      }
      if(isset($_GET['amountDue'])){
        $amountDue = $_GET['amountDue'];
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
      <div class="main">
        <div class="billingFormContainer">
          <form class="" action="phpCodes/registerPayment.php" method="post">
            <input type="hidden" name="accountNumSet" value="<?php echo $accountNumSet; ?>">
            <input type="hidden" name="clientIDSet" value="<?php echo $clientID; ?>">
            <div class="actionsTitle">
              <p>Payment Form</p>
            </div>
            <hr style="margin-bottom: 20px;">
            <div class="findTransportBox">
              <div class="inputGrid">
                <p class="inputTitle">Account Number</p>
                <?php
                  if(isset($accountNum)){
                    echo '<input class="accountNum" type="text" name="accountNum" placeholder="Account Number" maxlength="10" value="'.$accountNum.'" readonly>';
                  }
                  else {
                    echo '<input class="accountNum" type="text" name="accountNum" placeholder="Account Number" maxlength="10">';
                  }
                 ?>
              </div>

            </div>
            <div class="buttons">
              <button type="submit" name="button" value="Find">Find</button>
              <button type="submit" name="button" value="Cancel">Cancel</button>
            </div>

            <div class="findTransportBox">
              <div class="inputGrid">
                <p class="inputTitle">Amount Paid</p>
                <?php
                  if(isset($amountPaid)){
                    echo '<input class="amountPaid" type="number" name="amountPaid" placeholder="Amount Paid" maxlength="18" value="'.$amountPaid.'" readonly>';
                  }
                  else {
                    echo '<input class="amountPaid" type="number" name="amountPaid" placeholder="Amount Paid" maxlength="18">';
                  }
                 ?>

                  <p class="inputTitle">Date Of Payment</p>
                  <?php
                    if(isset($dateOfPayment)){
                      echo '<input class="dateOfPayment" type="date" name="dateOfPayment" value="'.$dateOfPayment.'" readonly>';
                    }
                    else {
                      echo '<input class="dateOfPayment" type="date" name="dateOfPayment">';
                    }
                   ?>
                 </div>


        </div>
        <div class="buttons" style="flex-direction: column; row-gap: 6px;">
          <button type="submit" name="button" value="Register">Pay</button>
          <a href="http://localhost:4000/HazardousWasteProj/Forms/BillingMainInterfaceForm/BillingMainInterfaceForm.php"
          >Go back</a>
          <?php
          //Getting url for error checking
            $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

            if (strpos($fullUrl, "register=empty") == true){
              echo "<p class='error'>You did not fill in all fields!</p>";
            } elseif (strpos($fullUrl, "update=success") == true){
              echo "<p class='success'>Payment recorded!</p>";
            } else if (strpos($fullUrl, "update=alreadyexist") == true){
              echo "<p class='error'>There is already a payment for this bill!</p>";
            } else if (strpos($fullUrl, "register=notfound") == true){
              echo "<p class='error'>Account not found!</p>";
            } else if (strpos($fullUrl, "payment=success") == true){
              echo "<p class='success'>Payment recorded!</p>";
            } else if (strpos($fullUrl, "payment=alreadyexist") == true){
              echo "<p class='error'>There is already a payment for this bill!</p>";
            }
          ?>
        </div>



      </form>

      </div>
        <div class="" style="margin-top: 0;">
          <div class="actionsTitle">
            <p>Account Data</p>
          </div>

          <hr style="margin-bottom: 20px;">
          <div class="transportationDataBox" style="width: 410px;">
            <div class="inputGrid">
              <p class="inputTitle">Company Name</p>
              <?php
                if(isset($clientCompanyName)){
                  echo '<input class="deliveryFee" type="text" name="clientCompanyName" placeholde="Company Name" maxlength="40" value="'.$clientCompanyName.'" readonly>';
                }
                else {
                  echo '<input class="deliveryFee" type="text" name="clientCompanyName" placeholder="Company Name" maxlength="40"  readonly>';
                }
               ?>
               <p class="inputTitle">Client ID</p>
               <?php
                 if(isset($clientID)){
                   echo '<input class="amountDue" type="text" name="clientID" placeholder="Client ID" maxlength="18" value="'.$clientID.'" readonly>';
                 }
                 else {
                   echo '<input class="amountDue" type="text" name="clientID" placeholder="Client ID" maxlength="18" readonly>';
                 }
                ?>

               <p class="inputTitle">Amount Due</p>
               <?php
                 if(isset($amountDue)){
                   echo '<input class="amountDue" type="number" name="amountDue" placeholder="Amount Due" maxlength="30" value="'.$amountDue.'" readonly>';
                 }
                 else {
                   echo '<input class="amountDue" type="number" name="amountDue" placeholder="Amount Due" maxlength="30" readonly>';
                 }
                ?>
            </div>
          </div>


      </div>

    </div>
    </main>
    <footer class="footer">
      <p>Working towards sustainability for a better and safer environment.
        | © 2020 JM Ecotech Solutions Company. All Rights Reserved.
      </p>
    </footer>
  </body>
</html>
