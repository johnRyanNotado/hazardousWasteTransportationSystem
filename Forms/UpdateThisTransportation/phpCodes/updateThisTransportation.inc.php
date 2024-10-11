<?php
  session_start();
  include "dbh.inc.php";

  $transportationID = $_POST['transportationID'];
  $transportationStatus = $_POST['transportationStatus'];
  $dateOfActualPickUp = $_POST['dateOfActualPickUp'];

  $actionBottom = $_POST['actionBottom'];

  if($actionBottom == 'Delete'){
    deleteThisTransportation($transportationID);
  }
  else if($actionBottom == 'Update'){
    updateThisTransportation($transportationID);
    echo "shit";
  }
  else if($actionBottom == 'Go back') {
    header("Location: http://localhost:4000/HazardousWasteProj/Forms/UpdateTransportationForm/UpdateTransportationForm.php?&transportationID=$transportationID");
  }


  function deleteThisTransportation($transportationID){
    include "dbh.inc.php";

    $employeeAssignmentID = $_POST['employeeAssignmentID'];
    $sql = "SELECT vA.dEmployeeID, aA.aEmployeeID
      FROM transportation t
      RIGHT JOIN employeeAssignment eA
        ON eA.transportationID = t.transportationID
      RIGHT JOIN vehicleAssignment vA
        ON vA.eAssignmentID = eA.employeeAssignmentID
      LEFT JOIN attendantAssignment aA
        ON aA.eAssignmentID = eA.employeeAssignmentID
      WHERE eA.employeeAssignmentID = ?
    ";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
      echo "sql error";
    }
    else {
      mysqli_stmt_bind_param($stmt, "s", $employeeAssignmentID);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);

      if(mysqli_fetch_assoc($result) > 0){
        header("Location: ../UpdateThisTransportationForm.php?update=cannotBeDeleted&transportationID=$transportationID");
      }
      else {
        $sql = "SELECT * FROM bill WHERE b.transportationID = ?
        ";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)){
          echo "sql error";
        }
        else {
          mysqli_stmt_bind_param($stmt, "s", $transportationID);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
          if(mysqli_fetch_assoc($result) > 0){
            header("Location: ../UpdateThisTransportationForm.php?update=cannotBeDeleted&transportationID=$transportationID");
          }
          else {
            $sql = "DELETE FROM transportation WHERE transportationID = ?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)){
              echo "sql error";
            }
            else {
              mysqli_stmt_bind_param($stmt, "s", $transportationID);
              mysqli_stmt_execute($stmt);
            }
            header("Location: ../UpdateThisTransportationForm.php?update=Deleted&transportationID=$transportationID");
          }
        }
      }
    }
  }


function updateThisTransportation($transportationID){
  include "dbh.inc.php";

  $dateOfActualPickUp = $_POST['dateOfActualPickUp'];
  $transportationStatusOld = $_POST['transportationStatusSet'];
  if ($_POST['transportationStatus'] == 'Delivered'){
    $transportationStatusNew = 'D';
  } else if ($_POST['transportationStatus'] == 'Pending'){
    $transportationStatusNew = 'P';
  } else if ($_POST['transportationStatus'] == 'Failure'){
    $transportationStatusNew = 'F';
  } else if ($_POST['transportationStatus'] == 'Spill'){
    $transportationStatusNew = 'S';
  }

  checkIfEmpty($transportationID, $transportationStatusNew);
  echo $transportationStatusNew;
  $sql = "UPDATE transportation SET dateOfActualPickUp = ?, transportationStatus = ? WHERE transportationID = ?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)){
    echo "sql error";
  }
  else {
    mysqli_stmt_bind_param($stmt, "sss", $dateOfActualPickUp,
    $transportationStatusNew, $transportationID,);
    mysqli_stmt_execute($stmt);
  }

  if($transportationStatusNew != $transportationStatusOld){
    deleteOldSubtype($transportationID, $transportationStatusOld);

    if($transportationStatusNew == 'P'){
      header("Location: ../UpdateThisTransportationForm.php?update=Updated&transportationID=$transportationID");
    }
    else if($transportationStatusNew == 'D'){
      $dateDelivered = $_POST['dateDelivered'];
      $sql = "INSERT INTO delivered (dTransportationID, dateDelivered) VALUES (?, ?);";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        mysqli_stmt_bind_param($stmt, "ss", $transportationID, $dateDelivered);
        mysqli_stmt_execute($stmt);
        header("Location: ../UpdateThisTransportationForm.php?update=Updated&transportationID=$transportationID");
      }
    }
    else if($transportationStatusNew == 'F'){
      $dateReturned = $_POST['dateReturned'];
      $failureReport = $_POST['failureReport'];
      $sql = "INSERT INTO failure (fTransportationID, failureReport, dateReturned) VALUES (?, ?, ?);";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        mysqli_stmt_bind_param($stmt, "sss", $transportationID, $failureReport, $dateReturned);
        mysqli_stmt_execute($stmt);
        header("Location: ../UpdateThisTransportationForm.php?update=Updated&transportationID=$transportationID");
      }
    }
    else if($transportationStatusNew == 'S'){
      $spillReport = $_POST['spillReport'];
      $dateOfIncident = $_POST['dateOfIncident'];
      $dateOfFiling = $_POST['dateOfFiling'];
      $spillRegion = $_POST['spillRegion'];
      $spillCity = $_POST['spillCity'];
      $spillBarangay = $_POST['spillBarangay'];
      $spillStreetName = $_POST['spillStreetName'];
      $sql = "INSERT INTO spill (sTransportationID, spillReport, dateOfIncident, dateOfFiling, spillRegion,
      spillCity, spillBarangay, spillStreetName) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        mysqli_stmt_bind_param($stmt, "ssssssss", $transportationID, $spillReport, $dateOfIncident, $dateOfFiling,
        $spillRegion, $spillCity, $spillBarangay, $spillStreetName);
        mysqli_stmt_execute($stmt);
        header("Location: ../UpdateThisTransportationForm.php?update=Updated&transportationID=$transportationID");
      }
    }

  }
  else {
    if($transportationStatusNew == 'P'){
      header("Location: ../UpdateThisTransportationForm.php?update=Updated&transportationID=$transportationID");
    }
    else if($transportationStatusNew == 'D'){
      $dateDelivered = $_POST['dateDelivered'];
      $sql = "UPDATE delivered SET dateDelivered = ? WHERE dTransportationID = ? ;";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        mysqli_stmt_bind_param($stmt, "ss", $dateDelivered, $transportationID);
        mysqli_stmt_execute($stmt);
        header("Location: ../UpdateThisTransportationForm.php?update=Updated&transportationID=$transportationID");
      }
    }
    else if($transportationStatusNew == 'F'){
      $dateReturned = $_POST['dateReturned'];
      $failureReport = $_POST['failureReport'];
      $sql = "UPDATE falure SET failureReport = ?, dateReturned = ? WHERE fTransportationID = ?;";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        mysqli_stmt_bind_param($stmt, "sss", $failureReport, $dateReturned, $transportationID);
        mysqli_stmt_execute($stmt);
        header("Location: ../UpdateThisTransportationForm.php?update=Updated&transportationID=$transportationID");
      }
    }
    else if($transportationStatusNew == 'S'){
      $spillReport = $_POST['spillReport'];
      $dateOfIncident = $_POST['dateOfIncident'];
      $dateOfFiling = $_POST['dateOfFiling'];
      $spillRegion = $_POST['spillRegion'];
      $spillCity = $_POST['spillCity'];
      $spillBarangay = $_POST['spillBarangay'];
      $spillStreetName = $_POST['spillStreetName'];
      $sql = "UPDATE spill SET spillReport = ?, dateOfIncident = ?, dateOfFiling = ?, spillRegion = ?,
      spillCity = ?, spillBarangay = ?, spillStreetName = ? WHERE sTransportationID = ?;";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        mysqli_stmt_bind_param($stmt, "ssssssss", $spillReport, $dateOfIncident, $dateOfFiling,
        $spillRegion, $spillCity, $spillBarangay, $spillStreetName, $transportationID);
        mysqli_stmt_execute($stmt);
        header("Location: ../UpdateThisTransportationForm.php?update=Updated&transportationID=$transportationID");
      }
    }

  }
}


function deleteOldSubtype($transportationID, $transportationStatusOld){
  include "dbh.inc.php";
  if ($transportationStatusOld == 'D'){
    $tableName = 'delivered';
    $primaryKey = 'dTransportationID';
  }
  else if ($transportationStatusOld == 'F'){
    $tableName = 'failure';
    $primaryKey = 'fTransportationID';
  }
  else if ($transportationStatusOld == 'S'){
    $tableName = 'spill';
    $primaryKey = 'sTransportationID';
  }
  else if ($transportationStatusOld == 'P'){
    return;
  }
  $sql = "DELETE FROM $tableName WHERE $primaryKey = ?;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)){
    echo "sql error";
  }
  else {
    mysqli_stmt_bind_param($stmt, "s", $transportationID);
    mysqli_stmt_execute($stmt);
  }
  return;
}

function checkIfEmpty($transportationID, $transportationStatusNew){
  if ($transportationStatusNew == 'F'){
    $failureReport = $_POST['failureReport'];
    if(empty($failureReport)){
      header("Location: ../UpdateThisTransportationForm.php?register=empty&transportationID=$transportationID");
      exit();
    }
  }
  if ($transportationStatusNew == 'S'){
    $spillReport = $_POST['spillReport'];
    $dateOfIncident = $_POST['dateOfIncident'];
    $spillRegion = $_POST['spillRegion'];
    $spillCity = $_POST['spillCity'];
    $spillBarangay = $_POST['spillBarangay'];
    $spillStreetName = $_POST['spillStreetName'];
    if(empty($dateOfIncident) || empty($spillReport) || empty($spillRegion) || empty($spillCity) || empty($spillBarangay) || empty($spillStreetName)){
      header("Location: ../UpdateThisTransportationForm.php?register=empty&transportationID=$transportationID");
      exit();
    }
  }
  if ($transportationStatusNew == 'D'){
    $dateDelivered = $_POST['dateDelivered'];
    if(empty($dateDelivered)){
      header("Location: ../UpdateThisTransportationForm.php?register=empty&transportationID=$transportationID");
      exit();
    }
  }
}
