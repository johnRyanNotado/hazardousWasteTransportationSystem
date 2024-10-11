<?php

  session_start();


  include "dbh.inc.php";

  $action = $_POST['findButton'];
  $transportationID = $_POST['transportationID'];

  if($action == "Find"){
    if(empty($transportationID)){
      header("Location: ../AttendantssignmentForm.php?register=empty");
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

  else if ($action == "Confirm"){
    header("Location: http://localhost:4000/HazardousWasteProj/Forms/TransportationMainInterfaceForm/TransportationMainInterfaceForm.php?");
  }

 function findThisTransportation($transportationID){
    include "dbh.inc.php";
    $sql = "SELECT t.dateOfActualPickUp, eA.employeeAssignmentID
    FROM transportation t
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
      $employeeAssignmentID = $row['employeeAssignmentID'];
      header("Location: ../AttendantAssignmentForm.php?attendant=exist?&transportationID=$transportationID&dateOfActualPickUp=$dateOfActualPickUp&employeeAssignmentID=$employeeAssignmentID");
    }
    else {
      header("Location: ../AttendantAssignmentForm.php?vehicle=dontexist&transportationID=$transportationID");
    }

 }


 function addThisVehicleAssignment($transportationID){
   findIfAttendantIsAlreadyAssigned($transportationID);
   include "dbh.inc.php";
   $employeeAssignmentID = $_POST['employeeAssignmentID'];
   $attendantID = $_POST['attendantID'];
   $dateOfActualPickUp = $_POST['dateOfActualPickUp'];
   if(empty($employeeAssignmentID) || empty($attendantID) || empty($transportationID) || empty($dateOfActualPickUp)){
     header("Location: ../AssignmentAssignmentForm.php?register=empty&transportationID=$transportationID&dateOfActualPickUp=$dateOfActualPickUp&employeeAssignmentID=$employeeAssignmentID");
   }
   else {
     $sql = "SELECT * FROM transportation WHERE transportationID = ?;";
     $stmt = mysqli_stmt_init($conn);
     mysqli_stmt_prepare($stmt, $sql);
     mysqli_stmt_bind_param($stmt, "s", $transportationID);
     mysqli_stmt_execute($stmt);
     $result = mysqli_stmt_get_result($stmt);
     if ($row = mysqli_fetch_assoc($result)){
       $sql = "INSERT INTO attendantAssignment (aEmployeeID, eAssignmentID) VALUES (?,?)";
       $stmt = mysqli_stmt_init($conn);
       mysqli_stmt_prepare($stmt, $sql);
       mysqli_stmt_bind_param($stmt, "ss", $attendantID, $employeeAssignmentID);
       mysqli_stmt_execute($stmt);
       header("Location: ../AttendantAssignmentForm.php?attendant=added?&transportationID=$transportationID&dateOfActualPickUp=$dateOfActualPickUp&employeeAssignmentID=$employeeAssignmentID");
     }
     else {
       header("Location: ../AttendantAssignmentForm.php?attendant=dontexist&transportationID=$transportationID&dateOfActualPickUp=$dateOfActualPickUp&employeeAssignmentID=$employeeAssignmentID");
     }
   }
 }




 function deleteThisVehicleAssignment($transportationID){
   include "dbh.inc.php";
   $employeeAssignmentID = $_POST['employeeAssignmentID'];
   $attendantID = $_POST['attendantID'];
   $dateOfActualPickUp = $_POST['dateOfActualPickUp'];
   if(empty($employeeAssignmentID) || empty($transportationID) || empty($dateOfActualPickUp)){
     header("Location: ../VehicleAssignmentForm.php?register=empty&transportationID=$transportationID&dateOfActualPickUp=$dateOfActualPickUp&employeeAssignmentID=$employeeAssignmentID");
   }
   else {
     $sql = "SELECT * FROM transportation WHERE transportationID = ?;";
     $stmt = mysqli_stmt_init($conn);
     mysqli_stmt_prepare($stmt, $sql);
     mysqli_stmt_bind_param($stmt, "s", $transportationID);
     mysqli_stmt_execute($stmt);
     $result = mysqli_stmt_get_result($stmt);
     if ($row = mysqli_fetch_assoc($result)){
       $sql = "DELETE FROM attendantAssignment WHERE eAssignmentID = ?;";
       $stmt = mysqli_stmt_init($conn);
       mysqli_stmt_prepare($stmt, $sql);
       mysqli_stmt_bind_param($stmt, "s", $employeeAssignmentID);
       mysqli_stmt_execute($stmt);
       header("Location: ../AttendantAssignmentForm.php?vehicle=deleted?&transportationID=$transportationID&dateOfActualPickUp=$dateOfActualPickUp&employeeAssignmentID=$employeeAssignmentID");
     }

     else {
       header("Location: ../AttendantAssignmentForm.php?attendant=dontexist&transportationID=$transportationID&dateOfActualPickUp=$dateOfActualPickUp&employeeAssignmentID=$employeeAssignmentID");
     }
   }
 }

 function findIfAttendantIsAlreadyAssigned($transportationID){
   include "dbh.inc.php";
   $employeeAssignmentID = $_POST['employeeAssignmentID'];
   $attendantID = $_POST['attendantID'];
   $dateOfActualPickUp = $_POST['dateOfActualPickUp'];
   $sql = "SELECT *
   FROM attendantAssignment
   WHERE eAssignmentID = ? AND aEmployeeID = ?;";

   $stmt = mysqli_stmt_init($conn);
   mysqli_stmt_prepare($stmt, $sql);
   mysqli_stmt_bind_param($stmt, "ss", $employeeAssignmentID, $attendantID);
   mysqli_stmt_execute($stmt);
   $result = mysqli_stmt_get_result($stmt);
   if ($row = mysqli_fetch_assoc($result)){
     header("Location: ../AttendantAssignmentForm.php?attendant=alreadyexist&transportationID=$transportationID&dateOfActualPickUp=$dateOfActualPickUp&employeeAssignmentID=$employeeAssignmentID");
     exit();
   }
 }
