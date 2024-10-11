<?php
  session_start();

  include "dbh.inc.php";;



  $actionBottom = $_POST['actionBottom'];
  $contractID = $_POST['contractID'];
  $contractStatus = $_POST['contractStatus'];

  if($actionBottom == 'Go back'){
    header("Location: http://localhost:4000/HazardousWasteProj/Forms/ContractUpdateForm/ContractUpdateForm.php?");
    exit();
  }
  else {
    if(isOldStatusPending($contractStatus, $contractID)){
      updateThisContract($contractID, $contractStatus);
    }
    else {
      header("Location: http://localhost:4000/HazardousWasteProj/Forms/ContractReportForm/ContractReportForm.php?contract=cannotBeUpdated&contractID=$contractID");
      exit();
    }
  }


  function updateThisContract($contractID, $contractStatus){
    include "dbh.inc.php";;

    if($contractStatus == 'F'){
      $sql = "UPDATE contract SET contractStatus = 'F'
      WHERE contractID = ?;";
      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        mysqli_stmt_bind_param($stmt, "s", $contractID);
        mysqli_stmt_execute($stmt);
        header("Location: http://localhost:4000/HazardousWasteProj/Forms/ContractReportForm/ContractReportForm.php?contract=updated&contractID=$contractID");
        exit();
      }
    }


    if($contractStatus = 'T'){
      $terminationDate = $_POST['terminationDate'];
      $preTerminationFee = $_POST['preTerminationFee'];

      if(empty($terminationDate)){
        header("Location: http://localhost:4000/HazardousWasteProj/Forms/ContractReportForm/ContractReportForm.php?update=empty&contractID=$contractID");
        exit();
      }
      // update contract table
      $sql = "UPDATE contract SET contractStatus = 'T'
      WHERE contractID = ?;";
      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        mysqli_stmt_bind_param($stmt, "s", $contractID);
        mysqli_stmt_execute($stmt);

        // insert termination table
        $sql = "INSERT INTO terminatedContract (tContractID, terminationDate) VALUES (?, ?);";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)){
          echo "sql error";
        }
        else {
          mysqli_stmt_bind_param($stmt, "ss", $contractID, $terminationDate);
          mysqli_stmt_execute($stmt);

          $sql = "SELECT a.accountNum
          FROM contract c
            LEFT JOIN client cl
              ON cl.clientID = c.clientID
            LEFT JOIN account a
              ON a.clientID = c.clientID
          WHERE c.contractID = ?;";

          $stmt = mysqli_stmt_init($conn);

          if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "sql error";
          }
          else {
            mysqli_stmt_bind_param($stmt, "s", $contractID);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            if($row = mysqli_fetch_assoc($result)){
              $accountNum = $row['accountNum'];
            }
          }



          $referenceNum = 'RF-'.date('Y-mdhis');
          // insert billing
          $sql = "INSERT INTO bill (referenceNum, tContractID, penaltyFee, accountNum) VALUES (?, ?, ?, ?);";

          $stmt = mysqli_stmt_init($conn);

          if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "sql error";
          }
          else {
            mysqli_stmt_bind_param($stmt, "ssss", $referenceNum, $contractID, $preTerminationFee, $accountNum);
            mysqli_stmt_execute($stmt);
            header("Location: http://localhost:4000/HazardousWasteProj/Forms/ContractReportForm/ContractReportForm.php?contract=updated&contractID=$contractID");
            exit();
          }

        }
      }
    }

  }



  function isOldStatusPending($contractStatus, $contractID){
    $contractStatusOld = $_POST['contractStatusSet'];
    if ($contractStatusOld == 'P'){
      if($contractStatus == 'Pending'){
        header("Location: http://localhost:4000/HazardousWasteProj/Forms/ContractReportForm/ContractReportForm.php?contract=alreadyPending&contractID=$contractID");
        exit();
      }
      return true;
    }
    else {
      return false;
    }
  }
