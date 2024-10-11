<?php
  session_start();
  include "dbh.inc.php";

  $vehicleID = $_POST['vehicleID'];


  $sql = "SELECT * FROM vehicle WHERE vehicleID = ?;";
  $stmt = mysqli_stmt_init($conn);

  if (!mysqli_stmt_prepare($stmt, $sql)){
    echo "sql error";
  }
  else {
    mysqli_stmt_bind_param($stmt, "s", $vehicleID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($result)){
      $vehiclePlate = $row['vehiclePlate'];
      $vehicleCapacity = $row['vehicleCapacity'];
      $vehicleType = $row['vehicleType'];
      $vehicleStatus = $row['vehicleStatus'];

      header("Location: ../VehicleEditForm.php?find=vehicleexist&vehicleID=$vehicleID&vehiclePlate=$vehiclePlate&vehicleCapacity=$vehicleCapacity&vehicleType=$vehicleType&vehicleStatus=$vehicleStatus");
    }
    else {
      header("Location: ../VehicleEditForm.php?update=vehicledoentexist");
    }
  }
 ?>
