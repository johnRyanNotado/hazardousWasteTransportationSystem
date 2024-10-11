<?php
  session_start();

  $action = $_POST['button'];
  if($action == 'Find'){
    $clientID = $_POST['clientID'];
    if(empty($clientID)){
      header("Location: ../BillingReportForm.php?register=empty");
      exit();
    }
    else {
      findThisClient($clientID);
    }
  }
  else if ($action == 'See'){
    $clientID = $_POST['clientIDSet'];
    if(empty($clientID)){
      header("Location: ../BillingReportForm.php?register=empty");
      exit();
    }
    else {
      header("Location: http://localhost:4000/HazardousWasteProj/Forms/LedgerReport/LedgerReportForm.php?&clientID=$clientID");
    }
  }






  function findThisClient($clientID){
    include "dbh.inc.php";

    $sql = "SELECT clientCompanyName,clientEmailAddress, clientContactNum
    FROM client
    WHERE clientID = ?;";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sql)){
      echo "sql error";
    }
    else {
      mysqli_stmt_bind_param($stmt, "s", $clientID);
      mysqli_stmt_execute($stmt);

      $result = mysqli_stmt_get_result($stmt);

      if($row = mysqli_fetch_assoc($result)){

        $clientIDSet = $clientID;
        $clientCompanyName = $row['clientCompanyName'];
        $clientLastName = $row['clientLastName'];
        $clientEmailAddress = $row['clientEmailAddress'];
        $clientContactNum = $row['clientContactNum'];

        header("Location: ../BillingReportForm.php?client=found&clientID=$clientID&clientCompanyName=$clientCompanyName&clientEmailAddress=$clientEmailAddress&clientContactNum=$clientContactNum&clientIDSet=$clientIDSet");
        exit();

      }
      else {
        header("Location: ../BillingReportForm.php?client=notFound");
        exit();
      }
    }
  }
