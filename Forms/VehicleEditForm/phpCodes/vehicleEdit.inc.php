<?php
  session_start();
  include_once "dbh.inc.php";
  $vehicleID = mysqli_real_escape_string($conn, $_POST['vehicleID']);
  $vehiclePlate = mysqli_real_escape_string($conn, $_POST['vehiclePlate']);
  $vehicleCapacity = mysqli_real_escape_string($conn, $_POST['vehicleCapacity']);
  $vehicleType = mysqli_real_escape_string($conn, $_POST['vehicleType']);
  $vehicleStatus = mysqli_real_escape_string($conn, $_POST['vehicleStatus']);
  $action = mysqli_real_escape_string($conn, $_POST['action']);
  if(empty($action)){
    header("Location: ../VehicleEditForm.php?register=empty");
  }
  else if ($action == 'Delete'){
    if (empty($vehiclePlate)){
      header("Location: ../VehicleEditForm.php?register=empty");
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
        mysqli_stmt_bind_param($stmt, "s", $vehicleID);
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
    if (empty($vehicleCapacity) || empty($vehiclePlate) || empty($vehicleType) || empty($vehicleCapacity) || empty($vehicleCapacity)) {
      header("Location: ../VehicleEditForm.php?register=empty");
    }
    else if ($action == 'Update') {
      $sql = "SELECT * FROM vehicle WHERE vehicleID = ?;";
      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        // check if the waste already exist in the database
        mysqli_stmt_bind_param($stmt, "s", $vehicleID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($result)){
          $sql = "UPDATE vehicle SET vehiclePlate = ?, vehicleCapacity = ?, vehicleType = ?, vehicleStatus = ? WHERE vehicleID = ?;";
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
      $sql = "SELECT * FROM vehicle WHERE vehiclePlate = ?;";
      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else{
        // check if the vehicle already exist in the database
        mysqli_stmt_bind_param($stmt, "s", $vehiclePlate);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($result)){
          header("Location: ../VehicleEditForm.php?register=recordalreadyexist");
        }
        else{
          // if unique, vehicle is registered
          $vehicleID = 'VE-'.date('Y-mdhis');
          $sql = "INSERT INTO vehicle (vehicleID, vehiclePlate, vehicleCapacity, vehicleType, vehicleStatus) VALUES (?, ?, ?, ?, ?);";
          $stmt = mysqli_stmt_init($conn);

          if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "sql error";
          }
          else {
            mysqli_stmt_bind_param($stmt, "sssss", $vehicleID, $vehiclePlate, $vehicleCapacity, $vehicleType, $vehicleStatus);
            mysqli_stmt_execute($stmt);
          }
          header("Location: ../VehicleEditForm.php?register=success");
        }
      }
    }
  }
