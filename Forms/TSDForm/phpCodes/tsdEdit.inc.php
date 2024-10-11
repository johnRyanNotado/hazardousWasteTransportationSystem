<?php
  session_start();
  include_once "dbh.inc.php";
  $tsdID = $_POST['tsdID'];
  $tsdName = $_POST['tsdName'];
  $tsdDenrID =$_POST['tsdDenrID'];
  $tsdRegion = $_POST['tsdRegion'];
  $tsdCity = $_POST['tsdCity'];
  $tsdBarangay = $_POST['tsdBarangay'];
  $tsdStreetName = $_POST['tsdStreetName'];
  $tsdHouseNum = $_POST['tsdHouseNum'];
  $action = mysqli_real_escape_string($conn, $_POST['action']);

  if(empty($action)){
    header("Location: ../TSDForm.php?register=empty");
  }
  else if ($action == 'Delete'){
    if (empty($tsdName)){
      header("Location: ../TSDForm.php?register=empty");
    }
    else {
      //delete func
      $sql = "SELECT * FROM vehicle WHERE vehicleID = ?;";
      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        // check if the waste already exist in the database
        mysqli_stmt_bind_param($stmt, "s", $tsdID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($result)){
          $sql = "DELETE FROM vehicle WHERE vehiclePlate = ?";
          $stmt = mysqli_stmt_init($conn);

          if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "sql error";
          }
          else {
            mysqli_stmt_bind_param($stmt, "s", $vehicleID);
            mysqli_stmt_execute($stmt);
            header("Location: ../VehicleEditForm.php?delete=success");
          }
        }
        else {
          header("Location: ../VehicleEditForm.php?update=vehicledontexist");
        }
      }
    }
  }
  else {
    if (empty($tsdName) || empty($tsdDenrID) || empty($tsdRegion) || empty($tsdCity) || empty($tsdBarangay) || empty($tsdHouseNum) || empty($tsdStreetName)) {
      header("Location: ../TSDForm.php?register=empty");
    }
    else if ($action == 'Update') {
      $sql = "SELECT * FROM tsd WHERE tsdID = ?;";
      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        // check if the waste already exist in the database
        mysqli_stmt_bind_param($stmt, "s", $tsdID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($result)){
          $sql = "UPDATE tsd SET tsdName = ?, tsdDe = ?, vehicleType = ?, vehicleStatus = ? WHERE vehicleID = ?;";
          $stmt = mysqli_stmt_init($conn);

          if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "sql error";
          }
          else {
            mysqli_stmt_bind_param($stmt, "sssss", $vehiclePlate, $vehicleCapacity, $vehicleType, $vehicleStatus, $vehicleID);
            mysqli_stmt_execute($stmt);
            header("Location: ../VehicleEditForm.php?update=success");
          }
        }
        else {
          header("Location: ../VehicleEditForm.php?update=vehicledontexist");
        }
      }
    }
    else if ($action == 'Create'){
      $sql = "SELECT * FROM tsd WHERE tsdDenrID = ?;";
      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else{
        // check if the vehicle already exist in the database
        mysqli_stmt_bind_param($stmt, "s", $tsdDenrID);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($result)){
          header("Location: ../VehicleEditForm.php?register=recordalreadyexist");
        }
        else{
          // if unique, vehicle is registered
          $tsdID = 'TR-'.date('Y-mdhis');
          $sql = "INSERT INTO tsd (tsdID, tsdName, tsdDenrID, tsdRegion, tsdCity, tsdBarangay, tsdStreetName, tsdHouseNum) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
          $stmt = mysqli_stmt_init($conn);

          if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "sql error";
          }
          else {
            mysqli_stmt_bind_param($stmt, "ssssssss", $tsdID, $tsdName, $tsdDenrID, $tsdRegion, $tsdCity, $tsdBarangay, $tsdStreetName, $tsdHouseNum);
            mysqli_stmt_execute($stmt);
          }
          header("Location: ../TSDForm.php?register=success");
        }
      }
    }
  }
