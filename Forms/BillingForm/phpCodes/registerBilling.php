<?php
  session_start();
  include "dbh.inc.php";


  $action = $_POST['button'];
  $requestID = $_POST['requestID'];

  if($action == 'Find'){
    findThisTransportation($requestID);
  }
  if ($action == 'Cancel') {
    header("Location: ../BillingForm.php?");
    exit();
  }
  if ($action == 'Register') {
    $requestID = $_POST['requestIDSet'];
    if(empty($requestID)){
      header("Location: ../BillingForm.php?register=notfound");
      exit();
    }
    registerThisBill($requestID);
  }


  function findThisTransportation($requestID){
    include "dbh.inc.php";
    $sql = "SELECT f.failureReport, s.spillReport, t.transportationStatus, t.uRequestID, a.accountNum, c.clientCompanyName
    FROM failure f
      RIGHT JOIN transportation t
        ON f.fTransportationID = t.TransportationID
      LEFT JOIN spill s
        ON s.sTransportationID = t.transportationID
      LEFT JOIN unchange u
        ON u.uRequestID = t.uRequestID
      LEFT JOIN request r
        ON r.requestID = u.uRequestID
      LEFT JOIN client c
        ON c.clientID = r.clientID
      LEFT JOIN account a
        ON a.clientID = c.clientID
    WHERE t.uRequestID = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)){
      echo "sql error";
    }
    else {
      // check if the waste already exist in the database
      mysqli_stmt_bind_param($stmt, "s", $requestID);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);

      if($row = mysqli_fetch_assoc($result)){
          $transportationStatus = $row['transportationStatus'];
          $failureReport = $row['failureReport'];
          $spillReport = $row['spillReport'];
          $accountNum = $row['accountNum'];
          $clientCompanyName = $row['clientCompanyName'];

          $sql = "SELECT SUM(amount) as wasteTotal
          FROM wasteAssignment
          WHERE requestID = ?;";
          $stmt = mysqli_stmt_init($conn);

          if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "sql error";
          }
          else {
            // check if the waste already exist in the database
            mysqli_stmt_bind_param($stmt, "s", $requestID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if($row = mysqli_fetch_assoc($result)){
              $wasteTotal = $row['wasteTotal'];
              $requestIDSet = $requestID;
              header("Location: ../BillingForm.php?register=found&requestID=$requestID&requestIDSet=$requestIDSet&clientCompanyName=$clientCompanyName&accountNum=$accountNum&transportationStatus=$transportationStatus&wasteTotal=$wasteTotal&failureReport=$failureReport&spillReport=$spillReport&transportationIDSet=$transportationIDSet");
            }
            else {
              header("Location: ../BillingForm.php?register=notfound1&requestID=$requestID");
              exit();
            }
          }
      }
      else {
        header("Location: ../BillingForm.php?register=notfound2&requestID=$requestID");
        exit();
      }
    }
  }




  function registerThisBill($requestID){
    include "dbh.inc.php";

    $deliveryFee = $_POST['deliveryFee'];
    $penaltyFee = $_POST['penaltyFee'];
    $referenceNumber = 'RF-'.date('Y-mdhis');



    if(empty($deliveryFee)){
      header("Location: ../BillingForm.php?register=empty&transportationID=$transportationID");
    }
    else{


      $sql = "SELECT accountNum
      FROM account a
        LEFT JOIN client c
          ON a.clientID = c.clientID
        LEFT JOIN request r
          ON r.clientID = c.clientID
      WHERE r.requestID = ?;";
      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        // check if the waste already exist in the database
        mysqli_stmt_bind_param($stmt, "s", $requestID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($result)){
          $accountNum = $row['accountNum'];
        }
        else {
          echo 'error';
        }
      }


      $sql = "INSERT INTO bill (deliveryFee, penaltyFee, referenceNum, requestID, accountNum) VALUE (?, ?, ?, ?, ?)";
      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        mysqli_stmt_bind_param($stmt, "sssss", $deliveryFee, $penaltyFee, $referenceNumber, $requestID, $accountNum);
        mysqli_stmt_execute($stmt);
        header("Location: ../BillingForm.php?bill=success&transportationID=$transportationID");
    }
  }
}
