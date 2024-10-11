<?php
  include "dbh.inc.php";
  session_start();

  $requestID = $_POST['requestID'];
  if(empty($requestID)){
    header("Location: ../wasteAssignmentForm.php?register=empty");
  }
  else{
    $sql = "SELECT * FROM request WHERE requestID = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)){
      echo "sql error";
    }
    else {
      // check if the REQUEST already exist in the database
      mysqli_stmt_bind_param($stmt, "s", $requestID);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);

      if($row = mysqli_fetch_assoc($result)){
        processThis($requestID);
      }
      else {
        header("Location: ../WasteAssignmentForm.php?request=dontexist");
      }
    }
  }


  function processThis($requestID){
    $action = $_POST['findButton'];
    $wasteID = $_POST['wasteID'];
    $wasteAmount = $_POST['wasteAmount'];

    if($action == 'find'){
      header("Location: ../WasteAssignmentForm.php?request=exist&requestID=$requestID");
    }

    else if ($action == 'cancel') {
      header("Location: ../WasteAssignmentForm.php?");
    }

    else if ($action == 'Delete') {
      deleteThisWaste($requestID, $wasteID, $wasteAmount);
    }

    else if ($action == 'Add') {
      addThisWaste($requestID, $wasteID, $wasteAmount);
    }

    else if ($action == 'confirm'){
      header("Location: http://localhost:4000/HazardousWasteProj/Forms/RequestMainInterfaceForm/RequestMainInterfaceForm.php");
    }

  }


  function deleteThisWaste($requestID, $wasteID, $wasteAmount){

    if (empty($wasteID) || empty($wasteAmount)){
      header("Location: ../WasteEditForm.php?register=empty&requestID=$requestID");
    }
    else {
      //delete func
      include "dbh.inc.php";
      $sql = "SELECT * FROM wasteAssignment WHERE wasteID = ? AND requestID = ?;";
      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        // check if the waste already exist in the database
        mysqli_stmt_bind_param($stmt, "ss", $wasteID , $requestID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($result)){
          $sql = "DELETE FROM wasteAssignment WHERE wasteID = ? AND requestID = ?;";
          $stmt = mysqli_stmt_init($conn);

          if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "sql error";
          }
          else {
            mysqli_stmt_bind_param($stmt, "ss", $wasteID , $requestID);
            mysqli_stmt_execute($stmt);
            header("Location: ../WasteAssignmentForm.php?delete=success&requestID=$requestID");
          }
        }
        else {
          header("Location: ../WasteAssignmentForm.php?update=wastedontexist&requestID=$requestID");
        }
      }
    }
  }

  function addThisWaste($requestID, $wasteID, $wasteAmount){
    if (empty($wasteID) || empty($wasteAmount) || empty($requestID)){
      header("Location: ../WasteEditForm.php?register=empty&requestID=$requestID");
    }
    else {
      //delete func
      include "dbh.inc.php";
      $sql = "SELECT * FROM wasteAssignment WHERE wasteID = ? AND requestID = ?;";
      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        // check if the waste already exist in the database
        mysqli_stmt_bind_param($stmt, "ss", $wasteID , $requestID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if(!mysqli_fetch_assoc($result)){
          $sql = "INSERT INTO wasteAssignment (wasteID, requestID, amount) VALUES (?, ?, ?)";
          $stmt = mysqli_stmt_init($conn);

          if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "sql error";
          }
          else {
            mysqli_stmt_bind_param($stmt, "sss", $wasteID , $requestID, $wasteAmount);
            mysqli_stmt_execute($stmt);
            header("Location: ../WasteAssignmentForm.php?add=success&requestID=$requestID");
          }
        }
        else {
          header("Location: ../WasteAssignmentForm.php?add=wasteAlreadyListed&requestID=$requestID");
        }
      }
    }
  }
 ?>
