<?php
  session_start();
  include_once "dbh.inc.php";

  $clientID = $_POST['clientIDSet'];
  $denrID = $_POST['denrGenID'];
  $companyName = $_POST['clientCompanyName'];
  $mobileNum = $_POST['mobileNum'];
  $email = $_POST['email'];
  $region = $_POST['region'];
  $city = $_POST['city'];
  $barangay = $_POST['barangay'];
  $streetName = $_POST['streetName'];
  $houseNum = $_POST['houseNum'];
  $actionBottom = $_POST['actionBottom'];


  if (empty($clientID) || empty($email) ||
      empty($mobileNum) || empty($denrID) || empty($companyName) || empty($region) ||
      empty($city) || empty($barangay) || empty($streetName) || empty($houseNum) || empty($actionBottom)) {
        header("Location: ../ClientEditForm.php?signup=empty&clientID=$clientID&mobileNum=$mobileNum&email=$email&region=$region&city=$city&barangay=$barangay&streetName=$streetName&houseNum=$houseNum&denrID=$denrID&companyName=$companyName");
      }
  else {
      //check if mobile# is invalid
      if (!preg_match("/^[0-9]*$/", $mobileNum) || strlen($mobileNum) != 11) {
        header("Location: ../ClientEditForm.php?signup=invalidmobile&clientID=$clientID&mobileNum=$mobileNum&email=$email&region=$region&city=$city&barangay=$barangay&streetName=$streetName&houseNum=$houseNum&denrID=$denrID&companyName=$companyName");
      } else {
        // check if invalid email
        if (filter_var(!$email, FILTER_VALIDATE_EMAIL)){
          header("Location: ../ClientEditForm.php?signup=invalidemail&clientID=$clientID&mobileNum=$mobileNum&email=$email&region=$region&city=$city&barangay=$barangay&streetName=$streetName&houseNum=$houseNum&denrID=$denrID&companyName=$companyName");
        }
        else {

        if($actionBottom == 'Update') {
          $sql = "SELECT * FROM client WHERE clientID != ? AND (clientEmailAddress = ? OR clientContactNum = ? OR denrGenID = ?);";
          $stmt = mysqli_stmt_init($conn);

          if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "sql error";
          }
          else {
            // check if the credentials have other match already exist in the database
            mysqli_stmt_bind_param($stmt, "ssss", $clientID, $email, $mobileNum, $denrID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_fetch_assoc($result) == 0){

              $sql = "UPDATE client SET clientEmailAddress = ?,
                  clientCompanyName = ?, clientContactNum = ?, clientRegion = ?,
                  clientCity = ?, clientBarangay = ?, clientStreetName = ?, clientHouseNum = ?, denrGenID = ? WHERE clientID = ?";
              $stmt = mysqli_stmt_init($conn);
              if (!mysqli_stmt_prepare($stmt, $sql)){
                echo "sql error";
              }
              else {
                mysqli_stmt_bind_param($stmt, "ssssssssss", $email, $companyName, $mobileNum, $region, $city, $barangay, $streetName, $houseNum, $denrID, $clientID);
                mysqli_stmt_execute($stmt);
                header("Location: ../ClientEditForm.php?update=Updated&clientID=$clientID&mobileNum=$mobileNum&email=$email&region=$region&city=$city&barangay=$barangay&streetName=$streetName&houseNum=$houseNum&denrID=$denrID&companyName=$companyName");
              }
            }
            else {
              header("Location: ../ClientEditForm.php?update=credentialsmatch&clientID=$clientID&mobileNum=$mobileNum&email=$email&region=$region&city=$city&barangay=$barangay&streetName=$streetName&houseNum=$houseNum&denrID=$denrID&companyName=$companyName");
            }
          }
        }
         // deleting employee record
        else if ($actionBottom == 'Delete') {

          $sql =
          "SELECT *
          FROM request r, payment p
          WHERE r.clientID = ? OR p.clientID = ?";
          $stmt = mysqli_stmt_init($conn);

          if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "sql error";
          }
          else {
            mysqli_stmt_bind_param($stmt, "ss", $clientID,$clientID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_fetch_assoc($result) > 0){
              header("Location: ../ClientEditForm.php?update=cannotBeDeleted&clientID=$clientID&firstName=$firstName&lastName=$lastName&mobileNum=$mobileNum&email=$email&region=$region&city=$city&barangay=$barangay&streetName=$streetName&houseNum=$houseNum&denrID=$denrID&companyName=$companyName");
            }
            else {
              $sql = "DELETE FROM client WHERE clientID = ?";
              $stmt = mysqli_stmt_init($conn);
              if (!mysqli_stmt_prepare($stmt, $sql)){
                echo "sql error";
              }
              else {
                mysqli_stmt_bind_param($stmt, "s", $clientID);
                mysqli_stmt_execute($stmt);
              }
              header("Location: ../ClientEditForm.php?update=Deleted&clientID=$clientID&firstName=$firstName&lastName=$lastName&mobileNum=$mobileNum&email=$email&region=$region&city=$city&barangay=$barangay&streetName=$streetName&houseNum=$houseNum&denrID=$denrID&companyName=$companyName");
            }
          }
        }
        }
      }

  }
