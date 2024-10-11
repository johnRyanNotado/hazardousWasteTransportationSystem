<?php
  session_start();
  include_once "dbh.inc.php";

  $requestID = $_POST['requestIDSet'];
  $specDateOfPickUp = $_POST['specDateOfPickUp'];
  $tsdDenrID = $_POST['tsdDenrID'];
  $tsdName = $_POST['tsdName'];
  $dateNAGen = $_POST['dateNAGen'];
  $dateIssuanceAckLet = $_POST['dateIssuanceAckLet'];
  $denrRefCode = $_POST['denrRefCode'];
  $requestStatus = $_POST['action'];
  $denrGenID = $_POST['denrGenID'];
  $clientCompanyName = $_POST['clientCompanyName'];
  $actionBottom = $_POST['actionBottom'];
  $reqeustStatusOld = $_POST['requestStatusOld'];

  $contractID = $_POST['contractID'];

  if (empty($requestID) || empty($specDateOfPickUp) ||empty($requestStatus) ||
      empty($actionBottom)) {
        header("Location: ../UpdateRequestForm.php?update=emptyheader&requestID=$requestID&contractID=$contractID&denrGenID=$denrGenID&clientCompanyName=$clientCompanyName&specDateOfPickUp=$specDateOfPickUp&tsdDenrID=$tsdDenrID&tsdName=$tsdName&denrRefCode=$denrRefCode&dateNAGen=$dateNAGen&dateIssuanceAckLet=$dateIssuanceAckLet&requestStatus=$requestStatus");
    }
  else {
    if($actionBottom == 'Update') {
      $sql = "SELECT * FROM request WHERE denrRefCode = ? AND requestID != ?;";
      $stmt = mysqli_stmt_init($conn);


      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        // check if the credentials have other match already exist in the database
        mysqli_stmt_bind_param($stmt, "ss", $denrRefCode, $requestID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_fetch_assoc($result) < 1){
          echo $reqeustStatusOld." ".$requestStatus." ".$requestID;
          checkIfNewStatus($reqeustStatusOld, $requestStatus, $requestID);
          $sql = "UPDATE request SET  specDateOfPickUp = ?, dateNAGen = ?, dateIssuanceAckLet = ?, denrRefCode = ?, requestStatus = ?, contractID = ? WHERE requestID = ?";
          $stmt = mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "sql error";
          }
          else {
            mysqli_stmt_bind_param($stmt, "sssssss", $specDateOfPickUp,
            $dateNAGen, $dateIssuanceAckLet, $denrRefCode, $requestStatus, $contractID,
            $requestID);
            mysqli_stmt_execute($stmt);
            header("Location: ../UpdateRequestForm.php?update=Updated&requestID=$requestID&contractID=$contractID&denrGenID=$denrGenID&clientCompanyName=$clientCompanyName&specDateOfPickUp=$specDateOfPickUp&tsdDenrID=$tsdDenrID&tsdName=$tsdName&denrRefCode=$denrRefCode&dateNAGen=$dateNAGen&dateIssuanceAckLet=$dateIssuanceAckLet&requestStatus=$requestStatus");
          }
        }
        else {
          header("Location: ../UpdateRequestForm.php?update=credentialsmatch&requestID=$requestID&contractID=$contractID&denrGenID=$denrGenID&clientCompanyName=$clientCompanyName&specDateOfPickUp=$specDateOfPickUp&tsdDenrID=$tsdDenrID&tsdName=$tsdName&denrRefCode=$denrRefCode&dateNAGen=$dateNAGen&dateIssuanceAckLet=$dateIssuanceAckLet&requestStatus=$requestStatus");
        }
      }
    }
     // deleting request record
    else if ($actionBottom == 'Delete') {

      $sql =
      "SELECT *
      FROM wasteAssignment w
      WHERE w.requestID = ?";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        mysqli_stmt_bind_param($stmt, "s",$requestID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_fetch_assoc($result) > 0){
          header("Location: ../UpdateRequestForm.php?update=cannotBeDeleted&requestID=$requestID&contractID=$contractID&denrGenID=$denrGenID&clientCompanyName=$clientCompanyName&specDateOfPickUp=$specDateOfPickUp&tsdDenrID=$tsdDenrID&tsdName=$tsdName&denrRefCode=$denrRefCode&dateNAGen=$dateNAGen&dateIssuanceAckLet=$dateIssuanceAckLet&requestStatus=$requestStatus");
          exit();
        }
        else {
          $sql = "DELETE FROM unchange WHERE uRequestID = ?";
          $stmt = mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "sql error";
          }
          else {
            mysqli_stmt_bind_param($stmt, "s", $requestID);
            mysqli_stmt_execute($stmt);
            $sql = "DELETE FROM request WHERE requestID = ?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)){
              echo "sql error";
            }
            else {
              mysqli_stmt_bind_param($stmt, "s", $requestID);
              mysqli_stmt_execute($stmt);
            }
          }
          header("Location: ../UpdateRequestForm.php?update=Deleted&requestID=$requestID&contractID=$contractID&denrGenID=$denrGenID&clientCompanyName=$clientCompanyName&specDateOfPickUp=$specDateOfPickUp&tsdDenrID=$tsdDenrID&tsdName=$tsdName&denrRefCode=$denrRefCode&dateNAGen=$dateNAGen&dateIssuanceAckLet=$dateIssuanceAckLet&requestStatus=$requestStatus");
          exit();
        }
      }
    }
  }

  function checkIfNewStatus($requestStatusOld, $requestStatus, $requestID){
    include "dbh.inc.php";
    if($requestStatus != $requestStatusOld) {
      if($requestStatusOld == "U"){

        $sql = "SELECT * FROM transportation WHERE uRequestID = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)){
          echo "sql error";
        }
        else {
          mysqli_stmt_bind_param($stmt, "s", $requestID);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);

          if(mysqli_fetch_assoc($result) > 0){
            header("Location: ../UpdateRequestForm.php?update=cannotBeDeleted&requestID=$requestID&contractID=$contractID&denrGenID=$denrGenID&clientCompanyName=$clientCompanyName&specDateOfPickUp=$specDateOfPickUp&tsdDenrID=$tsdDenrID&tsdName=$tsdName&denrRefCode=$denrRefCode&dateNAGen=$dateNAGen&dateIssuanceAckLet=$dateIssuanceAckLet&requestStatus=$requestStatus");
            exit();
          }
          else {
            $sql = "DELETE FROM unchange WHERE uRequestID = ?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)){
              echo "sql error";
            }
            else {
              mysqli_stmt_bind_param($stmt, "s", $requestID);
              mysqli_stmt_execute($stmt);
              return;
            }
          }
        }

      }
      else if($requestStatusOld == 'C'){
        $sql = "INSERT INTO unchange (uRequestID) VALUES (?)";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)){
          echo "sql error";
        }
        else {
          mysqli_stmt_bind_param($stmt, "s", $requestID);
          mysqli_stmt_execute($stmt);
          return;
        }
      }
    }
  }
