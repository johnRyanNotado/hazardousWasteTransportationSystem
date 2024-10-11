<?php
  session_start();
  include 'dbh.inc.php';

  $action = $_POST['findButton'];

  if($action == 'find'){
    findThisRequest();
  }
  else if ($action == 'cancel'){
    header("Location: ../TransportationForm.php?");
  }
  else if ($action == 'confirm'){
    checkThisTransportation();
  }



  function findThisRequest(){
    include "dbh.inc.php";
    $requestID = $_POST['requestID'];
    if(empty($requestID)){
      header("Location: ../TransportationForm.php?register=empty");
    }
    else {

      $sql = "SELECT c.clientCompanyName, c.denrGenID, r.denrRefCode, r.specDateOfPickUp, t.tsdDenrID,
      t.tsdName, c.clientRegion, c.clientCity, c.clientBarangay, c.clientStreetName, c.clientHouseNum,
      t.tsdRegion, t.tsdCity, t.tsdBarangay, t.tsdStreetName, t.tsdHouseNum
      FROM request r
        LEFT JOIN client c
          ON r.clientID = c.clientID
        LEFT JOIN tsd t
          ON t.tsdID = r.tsdID
      WHERE r.requestID = ?
        AND r.dateNAGen IS NOT NULL
        AND r.denrRefCode IS NOT NULL
        AND r.dateIssuanceAckLet IS NOT null
        AND r.dateNAGen != 0000-00-00
        AND r.dateIssuanceAckLet != 0000-00-00
        AND r.requestStatus != 'C'";

      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        // check if the waste already exist in the database
        mysqli_stmt_bind_param($stmt, "s", $requestID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($result)){


          $clientCompanyName = $row['clientCompanyName'];
          $denrGenID = $row['denrGenID'];
          $denrRefCode= $row['denrRefCode'];
          $specDateOfPickUp = $row['specDateOfPickUp'];
          $tsdDenrID = $row['tsdDenrID'];
          $tsdName = $row['tsdName'];
          $clientRegion = $row['clientRegion'];
          $clientCity = $row['clientCity'];
          $clientBarangay = $row['clientBarangay'];
          $clientStreetName = $row['clientStreetName'];
          $clientHouseNum = $row['clientHouseNum'];
          $tsdRegion = $row['tsdRegion'];
          $tsdCity = $row['tsdCity'];
          $tsdBarangay = $row['tsdBarangay'];
          $tsdStreetName = $row['tsdStreetName'];
          $tsdHouseNum = $row['tsdHouseNum'];

          header("Location: ../TransportationForm.php?client=exist&requestID=$requestID&denrGenID=$denrGenID&clientCompanyName=$clientCompanyName&denrRefCode=$denrRefCode&clientRegion=$clientRegion&clientCity=$clientCity&clientBarangay=$clientBarangay&clientStreetName=$clientStreetName&clientHouseNum=$clientHouseNum&specDateOfPickUp=$specDateOfPickUp&tsdDenrID=$tsdDenrID&tsdName=$tsdName&tsdRegion=$tsdRegion&tsdCity=$tsdCity&tsdBarangay=$tsdBarangay&tsdStreetName=$tsdStreetName&tsdHouseNum=$tsdHouseNum");
        } else {
          header("Location: ../TransportationForm.php?request=dontexist");
        }

          }
        }
      }

  function checkThisTransportation(){
    include "dbh.inc.php";

    $requestIDSet = $_POST['requestIDSet'];
    if (empty($requestIDSet)){
      header("Location: ../TransportationForm.php?request=dontexist");
      exit();
    }

    $requestID = $_POST['requestID'];
    $dateOfActualPickUp = $_POST['dateOfActualPickUp'];

    $clientCompanyName = $_POST['clientCompanyName'];
    $denrGenID = $_POST['denrGenID'];
    $denrRefCode= $_POST['denrRefCode'];
    $specDateOfPickUp = $_POST['specDateOfPickUp'];
    $tsdDenrID = $_POST['tsdDenrID'];
    $tsdName = $_POST['tsdName'];
    $clientRegion = $_POST['clientRegion'];
    $clientCity = $_POST['clientCity'];
    $clientBarangay = $_POST['clientBarangay'];
    $clientStreetName = $_POST['clientStreetName'];
    $clientHouseNum = $_POST['clientHouseNum'];
    $tsdRegion = $_POST['tsdRegion'];
    $tsdCity = $_POST['tsdCity'];
    $tsdBarangay = $_POST['tsdBarangay'];
    $tsdStreetName =$_POST['tsdStreetName'];
    $tsdHouseNum = $_POST['tsdHouseNum'];


    if(empty($requestIDSet) || empty($dateOfActualPickUp) || $dateOfActualPickUp == 0000-00-00){
      header("Location: ../TransportationForm.php?register=empty&requestID=$requestID&denrGenID=$denrGenID&clientCompanyName=$clientCompanyName&denrRefCode=$denrRefCode&clientRegion=$clientRegion&clientCity=$clientCity&clientBarangay=$clientBarangay&clientStreetName=$clientStreetName&clientHouseNum=$clientHouseNum&specDateOfPickUp=$specDateOfPickUp&tsdDenrID=$tsdDenrID&tsdName=$tsdName&tsdRegion=$tsdRegion&tsdCity=$tsdCity&tsdBarangay=$tsdBarangay&tsdStreetName=$tsdStreetName&tsdHouseNum=$tsdHouseNum");
    }
    else {
      $sql = "SELECT * FROM transportation t, unchange u WHERE t.uRequestID = u.uRequestID AND u.uRequestID = ?";

      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        mysqli_stmt_bind_param($stmt, "s", $requestID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($result)){
          header("Location: ../TransportationForm.php?transport=alreadyExist&requestID=$requestID&denrGenID=$denrGenID&clientCompanyName=$clientCompanyName&denrRefCode=$denrRefCode&clientRegion=$clientRegion&clientCity=$clientCity&clientBarangay=$clientBarangay&clientStreetName=$clientStreetName&clientHouseNum=$clientHouseNum&specDateOfPickUp=$specDateOfPickUp&tsdDenrID=$tsdDenrID&tsdName=$tsdName&tsdRegion=$tsdRegion&tsdCity=$tsdCity&tsdBarangay=$tsdBarangay&tsdStreetName=$tsdStreetName&tsdHouseNum=$tsdHouseNum&dateOfActualPickUp=$dateOfActualPickUp");
        }
        else {
          $transportationID = "TR-".date('Y-mdhis');
          $sql = "INSERT INTO transportation (transportationID, dateOfActualPickup, uRequestID) VALUES (?, ?, ?)";

          $stmt = mysqli_stmt_init($conn);

          if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "sql error";
          }
          else {
            mysqli_stmt_bind_param($stmt, "sss", $transportationID, $dateOfActualPickUp, $requestID);
            mysqli_stmt_execute($stmt);

            $employeeAssignmentID = "EA-".date('Y-mdhis');
            $sql = "INSERT INTO employeeAssignment (employeeAssignmentID, transportationID) VALUES (?, ?)";
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $sql)){
              echo "sql error";
            }
            else {
              mysqli_stmt_bind_param($stmt, "ss", $employeeAssignmentID, $transportationID);
              mysqli_stmt_execute($stmt);
              header("Location: http://localhost:4000/HazardousWasteProj/Forms/VehicleAssignmentForm/VehicleAssignmentForm.php?&requestID=$requestID&dateOfActualPickUp=$dateOfActualPickUp&employeeAssignmentID=$employeeAssignmentID&transportationID=$transportationID");
            }
        }
      }
    }
  }
}
