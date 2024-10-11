<?php
  session_start();
  if(!isset($_SESSION['name'])){
    header("Location: http://localhost:4000/HazardousWasteProj/Forms/AccessForm/AccountAccessForm.php");
  }
 ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Bill Form</title>
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Footer.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/General.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Header.css">
    <link rel="stylesheet" type="text/css" href="BillingForm.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href=
    "https://secureservercdn.net/198.71.233.110/s0i.8f2.myftpupload.com/wp-content/uploads/2020/03/cropped-5x3-1-32x32.png">

  </head>
  <body>
    <?php
      if(isset($_GET['requestID'])){
        $requestID = $_GET['requestID'];
      }
      if(isset($_GET['wasteTotal'])){
        $wasteTotal = $_GET['wasteTotal'];
      }
      if(isset($_GET['failureReport'])){
        $failureReport = $_GET['failureReport'];
      }
      if(isset($_GET['requestIDSet'])){
        $requestIDSet = $_GET['requestIDSet'];
      }
      if(isset($_GET['spillReport'])){
        $spillReport = $_GET['spillReport'];
      }
      if(isset($_GET['accountNum'])){
        $accountNum = $_GET['accountNum'];
      }
      if(isset($_GET['clientCompanyName'])){
        $clientCompanyName = $_GET['clientCompanyName'];
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
          <form class="" action="phpCodes/registerBilling.php" method="post">
            <input type="hidden" name="requestIDSet" value="<?php echo $requestIDSet; ?>">
            <div class="actionsTitle">
              <p>Billing Form</p>
            </div>
            <hr style="margin-bottom: 20px;">
            <div class="findTransportBox">
              <div class="inputGrid">
                <p class="inputTitle">Request ID</p>
                <?php
                  if(isset($requestID)){
                    echo '<input class="requestID" type="text" name="requestID" placeholder="Request ID" maxlength="18" value="'.$requestID.'" readonly>';
                  }
                  else {
                    echo '<input class="requestID" type="text" name="requestID" placeholder="Request ID" maxlength="18">';
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
                <p class="inputTitle">Delivery Fee</p>
                <?php
                  if(isset($deliveryFee)){
                    echo '<input class="deliveryFee" type="number" name="deliveryFee" placeholder="Delivery Fee" maxlength="18" value="'.$deliveryFee.'" readonly>';
                  }
                  else {
                    echo '<input class="deliveryFee" type="number" name="deliveryFee" placeholder="Delivery Fee" maxlength="18">';
                  }
                 ?>
                 <p class="inputTitle">Penalty Fee</p>
                 <?php
                   if(isset($penaltyFee)){
                     echo '<input class="penaltyFee" type="number" name="penaltyFee" placeholder="Penalty Fee" maxlength="18" value="'.$penaltyFee.'" readonly>';
                   }
                   else {
                     echo '<input class="penaltyFee" type="number" name="penaltyFee" placeholder="Penalty Fee" maxlength="18" value=0>';
                   }
                  ?>
              </div>


        </div>
        <div class="buttons" style="flex-direction: column; row-gap: 6px;">
          <button type="submit" name="button" value="Register">Register</button>
          <a href="http://localhost:4000/HazardousWasteProj/Forms/BillingMainInterfaceForm/BillingMainInterfaceForm.php"
          >Go back</a>
          <?php
          //Getting url for error checking
            $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

            if (strpos($fullUrl, "register=empty") == true){
              echo "<p class='error'>You did not fill in all fields!</p>";
            } elseif (strpos($fullUrl, "bill=success") == true){
              echo "<p class='success'>Bill recorded!</p>";
            } else if (strpos($fullUrl, "bill=alreadyexist") == true){
              echo "<p class='error'>There is already a bill for this transportation!</p>";
            }  else if (strpos($fullUrl, "register=notfound") == true){
              echo "<p class='error'>Transportation not found!</p>";
            }
          ?>
        </div>





      </form>

      </div>
        <div class="" style="margin-top: 0;">
          <div class="actionsTitle">
            <p>Request Data</p>
          </div>

          <hr style="margin-bottom: 20px;">
          <div class="transportationDataBox">
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
              <p class="inputTitle">Account Number</p>
              <?php
                if(isset($accountNum)){
                  echo '<input class="deliveryFee" type="text" name="accountNum" placeholder="Account Number" maxlength="18" value="'.$accountNum.'" readonly>';
                }
                else {
                  echo '<input class="deliveryFee" type="text" name="accountNum" placeholder="Account Number" maxlength="10"  readonly>';
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
                  echo '<select name="transportationStatus" placeholder="Status" disabled>';
                  foreach($status as $state){
                      echo "<option value=$state>$state</option>";
                  }
                }
               ?>
                </select>
              <p class="inputTitle">Waste Total (TONS)</p>
              <?php
                if(isset($wasteTotal)){
                  echo '<input class="deliveryFee" type="number" name="wasteTotal" placeholder="Delivery Fee" maxlength="18" value="'.$wasteTotal.'" readonly>';
                }
                else {
                  echo '<input class="deliveryFee" type="number" name="wasteTotal" placeholder="Delivery Fee" maxlength="18" value=0  readonly>';
                }
               ?>

            </div>
          </div>
          <div class="transportationDataBox" style="margin-top: 18px; display;">
            <p class="inputTitle" style="padding-left: 0px;">Report</p>
            <?php
              if(isset($failureReport)){
                echo '<textarea name="failureReport" cols="40" rows="10" placeholder="Type..."  readonly>'.$failureReport.'</textarea>';
              }
              elseif(isset($spillReport)){
                echo '<textarea name="spillReport" cols="40" rows="10" placeholder="Type..."  readonly>'.$spillReport.'</textarea>';
              }
              else {
                echo '<textarea name="failureReport" placeholder="Type..." cols="40" rows="10" readonly>N/A</textarea>';
              }
             ?>
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
