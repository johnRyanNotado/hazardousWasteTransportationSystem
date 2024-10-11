<?php
  session_start();
  include_once "dbh.inc.php";
  $wasteID = mysqli_real_escape_string($conn, $_POST['wasteID']);
  $wasteName = mysqli_real_escape_string($conn, $_POST['wasteName']);

  if(!isset($_POST['action'])){
    header("Location: ../WasteEditForm.php?editwaste=actionNull");
  }
  else{
    $action = $_POST['action'];
  }
  if ($action == 'Delete'){
    if (empty($wasteID)){
      header("Location: ../WasteEditForm.php?register=empty");
    }
    else {
      //delete func
      $sql = "SELECT * FROM waste WHERE wasteID = ?;";
      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        // check if the waste already exist in the database
        mysqli_stmt_bind_param($stmt, "s", $wasteID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($result)){
          $sql = "DELETE FROM waste WHERE wasteID = ?";
          $stmt = mysqli_stmt_init($conn);

          if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "sql error";
          }
          else {
            mysqli_stmt_bind_param($stmt, "s", $wasteID);
            mysqli_stmt_execute($stmt);
            header("Location: ../WasteEditForm.php?delete=success");
          }
        }
        else {
          header("Location: ../WasteEditForm.php?update=wastedontexist");
        }
      }
    }
  }
  else {
    if (empty($wasteID) || empty($wasteName)) {
      header("Location: ../WasteEditForm.php?register=empty");
    }
    else if ($action == 'Update') {
      $sql = "SELECT * FROM waste WHERE wasteID = ?;";
      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        // check if the waste already exist in the database
        mysqli_stmt_bind_param($stmt, "s", $wasteID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($result)){
          $sql = "UPDATE waste SET wasteName = ? WHERE wasteID = ?";
          $stmt = mysqli_stmt_init($conn);

          if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "sql error";
          }
          else {
            mysqli_stmt_bind_param($stmt, "ss", $wasteName, $wasteID);
            mysqli_stmt_execute($stmt);
            header("Location: ../WasteEditForm.php?update=success");
          }
        }
        else {
          header("Location: ../WasteEditForm.php?update=wastedontexist");
        }
      }
    }
    else if ($action == 'Create'){
      $sql = "SELECT * FROM waste WHERE wasteID = ?;";
      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else{
        // check if the waste already exist in the database
        mysqli_stmt_bind_param($stmt, "s", $wasteID);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($result)){
          header("Location: ../WasteEditForm.php?register=recordalreadyexist&wasteID=$wasteID&wasteName=$wasteName");
        }
        else{
          // if unique, waste is registered
          $sql = "INSERT INTO waste (wasteID, wasteName) VALUES (?, ?);";
          $stmt = mysqli_stmt_init($conn);

          if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "sql error";
          }
          else {
            mysqli_stmt_bind_param($stmt, "ss", $wasteID, $wasteName);
            mysqli_stmt_execute($stmt);
          }
          header("Location: ../WasteEditForm.php?register=success");
        }
      }
    }
  }
