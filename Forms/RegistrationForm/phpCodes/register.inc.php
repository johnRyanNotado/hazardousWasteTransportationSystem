<?php
  session_start();
  include_once 'dbh.inc.php';

  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $mobileNum = mysqli_real_escape_string($conn, $_POST['mobileNum']);
  $denrID = mysqli_real_escape_string($conn, $_POST['denrID']);
  $companyName = mysqli_real_escape_string($conn, $_POST['companyName']);
  $region = mysqli_real_escape_string($conn, $_POST['region']);
  $city = mysqli_real_escape_string($conn, $_POST['city']);
  $barangay = mysqli_real_escape_string($conn, $_POST['barangay']);
  $streetName = mysqli_real_escape_string($conn, $_POST['streetName']);
  $houseNum = mysqli_real_escape_string($conn, $_POST['houseNum']);
  $accountNum = mysqli_real_escape_string($conn, $_POST['accountNum']);

  //check if empty
  if (empty($email) ||
      empty($mobileNum) || empty($denrID) || empty($companyName) || empty($region) ||
      empty($city) || empty($barangay) || empty($streetName) || empty($houseNum) || empty($accountNum)) {
        header("Location: ../RegistrationForm.php?signup=empty&clientID=$clientID&accountNum=$accountNum&mobileNum=$mobileNum&email=$email&region=$region&city=$city&barangay=$barangay&streetName=$streetName&houseNum=$houseNum&denrID=$denrID&companyName=$companyName");
        exit();
      }
  else {

      //check if mobile# is invalid
      if (!preg_match("/^[0-9]*$/", $mobileNum) || strlen($mobileNum) != 11) {
        header("Location: ../RegistrationForm.php?signup=invalidmobile&clientID=$clientID&accountNum=$accountNum&mobileNum=$mobileNum&email=$email&region=$region&city=$city&barangay=$barangay&streetName=$streetName&houseNum=$houseNum&denrID=$denrID&companyName=$companyName");
        exit();
      } else {
        // check if invalid email
        if (!preg_match("/^[0-9]*$/", $accountNum) || strlen($accountNum) != 10){
          header("Location: ../RegistrationForm.php?signup=invalidaccount&clientID=$clientID&accountNum=$accountNum&mobileNum=$mobileNum&email=$email&region=$region&city=$city&barangay=$barangay&streetName=$streetName&houseNum=$houseNum&denrID=$denrID&companyName=$companyName");
          exit();
        }
        else {
          $sql = "SELECT * FROM client
          WHERE
          clientEmailAddress = ?
          OR clientContactNum = ?
          OR denrGenID = ?;";
          $stmt = mysqli_stmt_init($conn);

          if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "sql error";
          }
          else {
            mysqli_stmt_bind_param($stmt, "sss", $email, $mobileNum, $denrID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if(!mysqli_fetch_assoc($result)){

              $clientId = 'GR-'.date('Y-m-dhis', time());
              $sql = "INSERT INTO client (clientID, clientEmailAddress,
                  clientCompanyName, clientContactNum, clientRegion,
                  clientCity, clientBarangay, clientStreetName, clientHouseNum, denrGenID)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
              $stmt = mysqli_stmt_init($conn);
              if (!mysqli_stmt_prepare($stmt, $sql)){
                echo "sql error";
              } else {
                mysqli_stmt_bind_param($stmt, "ssssssssss", $clientId, $email,
                  $companyName, $mobileNum, $region,
                  $city, $barangay, $streetName, $houseNum, $denrID);
                mysqli_stmt_execute($stmt);
              }

              $sql = "INSERT INTO account (accountNum, clientID)
                VALUES (?, ?);";
              $stmt = mysqli_stmt_init($conn);
              if (!mysqli_stmt_prepare($stmt, $sql)){
                echo "sql error";
              } else {
                mysqli_stmt_bind_param($stmt, "ss", $accountNum, $clientId);
                mysqli_stmt_execute($stmt);
              }
              header("Location: ../RegistrationForm.php?signup=success");
            }
            else {
              header("Location: ../RegistrationForm.php?update=credentialsmatch&clientID=$clientID&mobileNum=$mobileNum&email=$email&region=$region&city=$city&barangay=$barangay&streetName=$streetName&houseNum=$houseNum&denrID=$denrID&companyName=$companyName");
            }
          }
          }



        }

    }




 ?>
