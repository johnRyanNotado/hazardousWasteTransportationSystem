<?php
  session_start();
  include "dbh.inc.php";

  $action = $_POST['button'];
  $accountNum = $_POST['accountNum'];


  if($action == 'Find'){
    findThisBilling($accountNum);
  }
  else if ($action == 'Cancel') {
    header("Location: ../PaymentForm.php?");
  }
  else if ($action == 'Register') {
    $accountNumSet = $_POST['accountNumSet'];
    if(empty($accountNumSet)){
      header("Location: ../PaymentForm.php?register=empty");
      exit();
    }
    registerThisPayment($accountNumSet);
  }


  function findThisBilling($accountNum){
    include "dbh.inc.php";
    $sql = "SELECT c.clientCompanyName, c.clientID
    FROM account a
      RIGHT JOIN client c
        ON c.clientID = a.clientID
    WHERE accountNum = ?;";

    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)){
      echo "sql error";
    }
    else {
      mysqli_stmt_bind_param($stmt, "s", $accountNum);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);

      if($row = mysqli_fetch_assoc($result)){
          $clientID = $row['clientID'];
          $clientCompanyName = $row['clientCompanyName'];
          $accountNumSet = $accountNum;

          $sql = "SELECT SUM(b.deliveryFee) as totaldelivery, SUM(b.penaltyFee) as totalpenalty
          FROM account a
            RIGHT JOIN bill b
              ON b.accountNum = a.accountNum
          WHERE a.accountNum = ?;";
          $stmt = mysqli_stmt_init($conn);

          if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "sql error";
          }
          else {
            // check if the waste already exist in the database
            mysqli_stmt_bind_param($stmt, "s", $accountNum);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if($row = mysqli_fetch_assoc($result)){
                $amountDue = $row['totaldelivery'] + $row['totalpenalty'];
                echo $row['totalpayment'];
            }

          }
          $sql = "SELECT SUM(p.amountPaid) as totalpayment
          FROM payment p
            RIGHT JOIN account a
              ON p.accountNum = a.accountNum
          WHERE a.accountNum = ?;";
          $stmt = mysqli_stmt_init($conn);

          if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "sql error";
          }
          else {
            // check if the waste already exist in the database
            mysqli_stmt_bind_param($stmt, "s", $accountNum);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if($row = mysqli_fetch_assoc($result)){
                $amountDue = $amountDue - $row['totalpayment'];
                echo $row['totalpayment'];
            }

          }

          header("Location: ../PaymentForm.php?register=found&accountNum=$accountNum&clientID=$clientID&clientCompanyName=$clientCompanyName&accountNumSet=$accountNumSet&amountDue=$amountDue");
          exit();
      }
      else {
        header("Location: ../PaymentForm.php?register=notfound&accountNum=$accountNum");
        exit();
      }
    }

  }


  function registerThisPayment($accountNum){
    include "dbh.inc.php";
    $dateOfPayment = $_POST['dateOfPayment'];
    $amountPaid= $_POST['amountPaid'];
    $paymentID = 'PM-'.date('Y-mdhis');
    $clientID = $_POST['clientIDSet'];

    if(empty($amountPaid) || empty($dateOfPayment)){
      header("Location: ../PaymentForm.php?register=empty&accountNum=$accountNum");
      exit();
    }
    else{
      $sql = "INSERT INTO payment (paymentID, amountPaid, dateOfPayment, accountNum, clientID) VALUE (?, ?, ?, ?, ?)";
      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        mysqli_stmt_bind_param($stmt, "sssss", $paymentID, $amountPaid, $dateOfPayment, $accountNum, $clientID);
        mysqli_stmt_execute($stmt);
        header("Location: ../PaymentForm.php?payment=success&accountNum=$accountNum");
    }
  }
}
