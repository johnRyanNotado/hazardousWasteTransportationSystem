<?php

  session_start();


  include "dbh.inc.php";

  $action = $_POST['findButton'];

  $transportationID = $_POST['transportationID'];
  if($action == "Find"){
    if(empty($transportationID)){
      header("Location: ../VehicleAssignmentForm.php?register=empty");
    }
    else{
      findThisTransportation($transportationID);
    }
  }
  else if ($action == "Cancel"){
    header("Location: ../VehicleAssignmentForm.php?");
  }
  else if ($action == "Add"){
    addThisVehicleAssignment($transportationID);
  }

  else if($action == "Delete"){
    deleteThisVehicleAssignment($transportationID);
  }

  else if ($action == "Next"){
    goToAttendantAssignment($transportationID);
  }




function goToAttendantAssignment($transportationID){
  $employeeAssignmentID = $_POST['employeeAssignmentID'];
  $dEmployeeID = $_POST['driverID'];
  $vehicleID = $_POST['vehicleID'];
  $dateOfActualPickUp = $_POST['dateOfActualPickUp'];
  header("Location: http://localhost:4000/HazardousWasteProj/Forms/AttendantAssignmentForm/AttendantAssignmentForm.php?&transportationID=$transportationID&dateOfActualPickUp=$dateOfActualPickUp&employeeAssignmentID=$employeeAssignmentID");
}

 function findThisTransportation($transportationID){
    include "dbh.inc.php";
    $sql = "SELECT t.dateOfActualPickUp, r.requestID, eA.employeeAssignmentID
    FROM request r
    RIGHT JOIN unchange u
      ON r.requestID = u.uRequestID
    RIGHT JOIN transportation t
      ON t.uRequestID = u.uRequestID
    LEFT JOIN employeeAssignment eA
      ON t.transportationID = eA.transportationID
    WHERE t.transportationID = ?;";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "s", $transportationID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
      $dateOfActualPickUp = $row['dateOfActualPickUp'];
      $requestID = $row['requestID'];
      $employeeAssignmentID = $row['employeeAssignmentID'];
      header("Location: ../VehicleAssignmentForm.php?vehicle=exist?&transportationID=$transportationID&dateOfActualPickUp=$dateOfActualPickUp&requestID=$requestID&employeeAssignmentID=$employeeAssignmentID");
    }
    else {
      header("Location: ../VehicleAssignmentForm.php?vehicle=dontexist&transportationID=$transportationID");
    }

 }


 function addThisVehicleAssignment($transportationID){
   findIfVehicleIsAlreadyAssigned($transportationID);
   findIfDriverIsAlreadyAssigned($transportationID);
   include "dbh.inc.php";
   $employeeAssignmentID = $_POST['employeeAssignmentID'];
   $dEmployeeID = $_POST['driverID'];
   $vehicleID = $_POST['vehicleID'];
   $requestID = $_POST['requestID'];
   $dateOfActualPickUp = $_POST['dateOfActualPickUp'];
   if(empty($employeeAssignmentID) || empty($dEmployeeID) || empty($vehicleID) || empty($transportationID) || empty($dateOfActualPickUp)){
     header("Location: ../VehicleAssignmentForm.php?register=empty&transportationID=$transportationID&dateOfActualPickUp=$dateOfActualPickUp&requestID=$requestID&employeeAssignmentID=$employeeAssignmentID");
   }
   else {
     $sql = "SELECT * FROM transportation WHERE transportationID = ?;";
     $stmt = mysqli_stmt_init($conn);
     mysqli_stmt_prepare($stmt, $sql);
     mysqli_stmt_bind_param($stmt, "s", $transportationID);
     mysqli_stmt_execute($stmt);
     $result = mysqli_stmt_get_result($stmt);
     if ($row = mysqli_fetch_assoc($result)){
       $sql = "INSERT INTO vehicleAssignment (vehicleID, dEmployeeID, eAssignmentID) VALUES (?,?,?)";
       $stmt = mysqli_stmt_init($conn);
       mysqli_stmt_prepare($stmt, $sql);
       mysqli_stmt_bind_param($stmt, "sss", $vehicleID, $dEmployeeID, $employeeAssignmentID);
       mysqli_stmt_execute($stmt);
       header("Location: ../VehicleAssignmentForm.php?vehicle=added?&transportationID=$transportationID&dateOfActualPickUp=$dateOfActualPickUp&requestID=$requestID&employeeAssignmentID=$employeeAssignmentID");
     }
     else {
       header("Location: ../VehicleAssignmentForm.php?vehicle=dontexist&transportationID=$transportationID&dateOfActualPickUp=$dateOfActualPickUp&requestID=$requestID&employeeAssignmentID=$employeeAssignmentID");
     }
   }
 }


 function deleteThisVehicleAssignment($transportationID){
   include "dbh.inc.php";
   $employeeAssignmentID = $_POST['employeeAssignmentID'];
   $dEmployeeID = $_POST['driverID'];
   $vehicleID = $_POST['vehicleID'];
   $requestID = $_POST['requestID'];
   $dateOfActualPickUp = $_POST['dateOfActualPickUp'];
   if(empty($employeeAssignmentID) || empty($transportationID)){
     header("Location: ../VehicleAssignmentForm.php?register=empty&transportationID=$transportationID&dateOfActualPickUp=$dateOfActualPickUp&requestID=$requestID&employeeAssignmentID=$employeeAssignmentID");
   }
   else {
     $sql = "SELECT * FROM transportation WHERE transportationID = ?;";
     $stmt = mysqli_stmt_init($conn);
     mysqli_stmt_prepare($stmt, $sql);
     mysqli_stmt_bind_param($stmt, "s", $transportationID);
     mysqli_stmt_execute($stmt);
     $result = mysqli_stmt_get_result($stmt);
     if ($row = mysqli_fetch_assoc($result)){
       $sql = "DELETE FROM vehicleAssignment WHERE eAssignmentID = ?;";
       $stmt = mysqli_stmt_init($conn);
       mysqli_stmt_prepare($stmt, $sql);
       mysqli_stmt_bind_param($stmt, "s", $employeeAssignmentID);
       mysqli_stmt_execute($stmt);
       header("Location: ../VehicleAssignmentForm.php?vehicle=deleted?&transportationID=$transportationID&dateOfActualPickUp=$dateOfActualPickUp&requestID=$requestID&employeeAssignmentID=$employeeAssignmentID");
     }

     else {
       header("Location: ../VehicleAssignmentForm.php?vehicle=dontexist&transportationID=$transportationID&dateOfActualPickUp=$dateOfActualPickUp&requestID=$requestID&employeeAssignmentID=$employeeAssignmentID");
     }
   }
 }

function findIfVehicleIsAlreadyAssigned($transportationID){
  include "dbh.inc.php";
  $employeeAssignmentID = $_POST['employeeAssignmentID'];
  $dEmployeeID = $_POST['driverID'];
  $vehicleID = $_POST['vehicleID'];
  $requestID = $_POST['requestID'];
  $dateOfActualPickUp = $_POST['dateOfActualPickUp'];
  $sql = "SELECT *
  FROM vehicleAssignment
  WHERE eAssignmentID = ? AND vehicleID = ?;";

  $stmt = mysqli_stmt_init($conn);
  mysqli_stmt_prepare($stmt, $sql);
  mysqli_stmt_bind_param($stmt, "ss", $employeeAssignmentID, $vehicleID);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  if ($row = mysqli_fetch_assoc($result)){
    header("Location: ../VehicleAssignmentForm.php?vehicle=vehicleAlreadyAssigned&transportationID=$transportationID&dateOfActualPickUp=$dateOfActualPickUp&requestID=$requestID&employeeAssignmentID=$employeeAssignmentID");
    exit();
  }
}
function findIfDriverIsAlreadyAssigned($transportationID){
  include "dbh.inc.php";
  $employeeAssignmentID = $_POST['employeeAssignmentID'];
  $dEmployeeID = $_POST['driverID'];
  $vehicleID = $_POST['vehicleID'];
  $requestID = $_POST['requestID'];
  $dateOfActualPickUp = $_POST['dateOfActualPickUp'];
  $sql = "SELECT *
  FROM vehicleAssignment
  WHERE eAssignmentID = ? AND dEmployeeID = ?;";

  $stmt = mysqli_stmt_init($conn);
  mysqli_stmt_prepare($stmt, $sql);
  mysqli_stmt_bind_param($stmt, "ss", $employeeAssignmentID, $dEmployeeID);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  if ($row = mysqli_fetch_assoc($result)){
    header("Location: ../VehicleAssignmentForm.php?vehicle=driverAlreadyAssigned&transportationID=$transportationID&dateOfActualPickUp=$dateOfActualPickUp&requestID=$requestID&employeeAssignmentID=$employeeAssignmentID");
    exit();
  }
}
