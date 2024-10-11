<?php
  session_start();

  try {
      $button = $_POST['findButton'];
  }
  catch(err){

  }
  finally {
    if($button == 'find'){
      findThisClient();
    } else if ($button == 'confirm') {
      checkThisRequest();
    } else if ($button == 'cancel') {
      header("Location: ../ClientRequestFormNA.php?");
    }
  }



  function findThisClient(){
    include "dbh.inc.php";
    $denrGenID = $_POST['denrGenID'];
    $sql = "SELECT * FROM client WHERE denrGenID = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)){
      echo "sql error";
    }
    else {
      // check if the waste already exist in the database
      mysqli_stmt_bind_param($stmt, "s", $denrGenID);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);

      if($row = mysqli_fetch_assoc($result)){
        $denrGenID = $row['denrGenID'];
        $clientContactNum = $row['clientContactNum'];
        $clientEmail = $row['clientEmailAddress'];
        $clientCompanyName = $row['clientCompanyName'];
        $clientRegion = $row['clientRegion'];
        $clientCity = $row['clientCity'];
        $clientBarangay = $row['clientBarangay'];
        $clientStreetName = $row['clientStreetName'];
        $clientHouseNum = $row['clientHouseNum'];

        header("Location: ../ClientRequestFormNA.php?client=exist&denrGenID=$denrGenID&clientCompanyName=$clientCompanyName&clientContactNum=$clientContactNum&clientEmail=$clientEmail&clientRegion=$clientRegion&clientCity=$clientCity&clientBarangay=$clientBarangay&clientStreetName=$clientStreetName&clientHouseNum=$clientHouseNum");
      } else {
        header("Location: ../ClientRequestFormNA.php?client=dontexist");
      }

    }
  }

  function checkThisRequest(){
    include "dbh.inc.php";
    $denrGenID = $_POST['denrGenID'];
    $clientContactNum = $_POST['clientContactNum'];
    $clientCompanyName = $_POST['clientCompanyName'];
    $clientEmail = $_POST['clientEmail'];
    $clientRegion = $_POST['clientRegionSet'];
    $clientCity = $_POST['clientCity'];
    $clientBarangay = $_POST['clientBarangay'];
    $clientStreetName = $_POST['clientStreetName'];
    $clientHouseNum = $_POST['clientHouseNum'];
    $specDateOfPickUp = $_POST['specDateOfPickUp'];

    $tsdID = $_POST['tsdID'];
    $contractID = $_POST['contractID'];
    if(empty($denrGenID) || empty($clientCompanyName) || empty($clientContactNum) || empty($clientEmail) ){
    // || empty($clientRegion) || empty($clientCity) || empty($clientBarangay) || empty($clientStreetName) || empty($clientHouseNum) ||
    // empty($specDateOfPickUp)){
      header("Location: ../ClientRequestFormNA.php?client=empty&denrGenID=$denrGenID&clientCompanyName=$clientCompanyName&clientContactNum=$clientContactNum&clientEmail=$clientEmail&clientRegion=$clientRegion&clientCity=$clientCity&clientBarangay=$clientBarangay&clientStreetName=$clientStreetName&clientHouseNum=$clientHouseNum&specDateOfPickUp=$specDateOfPickUp");

    }
    else {
      $sql = "SELECT * FROM client WHERE denrGenID = ?";
      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        // check if the client already exist in the database
        mysqli_stmt_bind_param($stmt, "s", $denrGenID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($result)){
          if($contractID != ""){
            $sql = "SELECT * FROM contract WHERE contractID = ?";
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $sql)){
              echo "sql error";
            }
            else {
              // check if the ccontract already exist in the database
              mysqli_stmt_bind_param($stmt, "s", $contractID);
              mysqli_stmt_execute($stmt);
              $result = mysqli_stmt_get_result($stmt);
              if($row = mysqli_fetch_assoc($result)){
                registerThisRequest($row['clientID']);
              }
              else {
                header("Location: ../ClientRequestFormNA.php?client=invalidContract&denrGenID=$denrGenID&clientContactNum=$clientContactNum&clientCompanyName=$clientCompanyName&clientEmail=$clientEmail&clientRegion=$clientRegion&clientCity=$clientCity&clientBarangay=$clientBarangay&clientStreetName=$clientStreetName&clientHouseNum=$clientHouseNum&specDateOfPickUp=$specDateOfPickUp&clientID=$clientID&contractID=$contractID");
                exit();
              }
            }
          }
          else {
              registerThisRequest($row['clientID']);
          }

        }
        else {
          header("Location: ../ClientRequestFormNA.php?client=dontexist&denrGenID=$denrGenID");
        }
      }
    }
  }

  function registerThisRequest($clientID){
    include "dbh.inc.php";
    $specDateOfPickUp = $_POST['specDateOfPickUp'];
    $tsdID = $_POST['tsdID'];
    $contractID = $_POST['contractID'];
    if($contractID == ""){
      $contractID = null;
    }

        $requestID = 'RQ-'.date('Y-mdhis');
        $sql = "INSERT INTO request (requestID, specDateOfPickUp, clientID, tsdID, contractID) VALUES (?,?,?,?,?)";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)){
          echo "sql error";
        }
        else {
          // check if the client already exist in the database
          mysqli_stmt_bind_param($stmt, "sssss", $requestID, $specDateOfPickUp, $clientID, $tsdID, $contractID);
          mysqli_stmt_execute($stmt);

          $sql = "INSERT INTO unchange (uRequestID) VALUES (?)";
          $stmt = mysqli_stmt_init($conn);

          if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "sql error";
          }
          else {
            // check if the client already exist in the database
            mysqli_stmt_bind_param($stmt, "s", $requestID);
            mysqli_stmt_execute($stmt);

            header("Location: http://localhost:4000/HazardousWasteProj/Forms/WasteAssignmentForm/WasteAssignmentForm.php?register=success&requestID=$requestID&denrGenID=$denrGenID&clientContactNum=$clientContactNum&clientEmail=$clientEmail&clientRegion=$clientRegion&clientCity=$clientCity&clientBarangay=$clientBarangay&clientStreetName=$clientStreetName&clientHouseNum=$clientHouseNum&specDateOfPickUp=$specDateOfPickUp");
          }
        }

  }
