<?php
  session_start();
  include_once "dbh.inc.php";

  $employeeID = 'EM-'.date('Y-mdhis');
  $employeeLastName = $_POST['employeeLastName'];
  $employeeFirstName = $_POST['employeeFirstName'];
  $employeeContactNum = $_POST['employeeContactNum'];
  $employeeEmail = $_POST['employeeEmail'];
  $employeeRegion = $_POST['employeeRegion'];
  $employeeCity = $_POST['employeeCity'];
  $employeeBarangay = $_POST['employeeBarangay'];
  $employeeStreetName = $_POST['employeeStreetName'];
  $employeeHouseNum = $_POST['employeeHouseNum'];
  $employeeType = $_POST['action'];

  if ($employeeType == 'D'){
    $dateLicenseRegistration = $_POST['dateLicenseRegistration'];
    $dateLicenseExpiration = $_POST['dateLicenseExpiration'];
    if ($dateLicenseExpiration < $dateLicenseRegistration){
      header("Location: ../EmployeeRegisterForm.php?license=cannotbe&employeeID=$employeeID&employeeFirstName=$employeeFirstName&employeeLastName=$employeeLastName&employeeContactNum=$employeeContactNum&employeeEmail=$employeeEmail&employeeRegion=$employeeRegion&employeeCity=$employeeCity&employeeBarangay=$employeeBarangay&employeeStreetName=$employeeStreetName&employeeHouseNum=$employeeHouseNum&employeeType=$employeeType&dateLicenseRegistration=$dateLicenseRegistration&dateLicenseExpiration=$dateLicenseExpiration");
      exit();
    }
    else {
      if (empty($dateLicenseExpiration) || empty($dateLicenseRegistration)) {
        echo "shit";
        header("Location: ../EmployeeRegisterForm.php?register=empty&employeeID=$employeeID&employeeFirstName=$employeeFirstName&employeeLastName=$employeeLastName&employeeContactNum=$employeeContactNum&employeeEmail=$employeeEmail&employeeRegion=$employeeRegion&employeeCity=$employeeCity&employeeBarangay=$employeeBarangay&employeeStreetName=$employeeStreetName&employeeHouseNum=$employeeHouseNum&employeeType=$employeeType&dateLicenseRegistration=$dateLicenseRegistration&dateLicenseExpiration=$dateLicenseExpiration");
      }
    }
  }


  if (empty($employeeID) || empty($employeeLastName) || empty($employeeFirstName) || empty($employeeContactNum) ||
  empty($employeeEmail) || empty($employeeRegion) || empty($employeeCity) || empty($employeeBarangay)
  || empty($employeeStreetName ) || empty($employeeHouseNum) || empty($employeeType)){
    header("Location: ../EmployeeRegisterForm.php?register=empty&employeeID=$employeeID&employeeFirstName=$employeeFirstName&employeeLastName=$employeeLastName&employeeContactNum=$employeeContactNum&employeeEmail=$employeeEmail&employeeRegion=$employeeRegion&employeeCity=$employeeCity&employeeBarangay=$employeeBarangay&employeeStreetName=$employeeStreetName&employeeHouseNum=$employeeHouseNum&employeeType=$employeeType&dateLicenseRegistration=$dateLicenseRegistration&dateLicenseExpiration=$dateLicenseExpiration");

  }
  else {
    // check if invalid name
    if (!preg_match("/^([a-zA-Z' ]+)$/",$employeeFirstName) || !preg_match("/^([a-zA-Z' ]+)$/", $employeeLastName)){
      header("Location: ../EmployeeRegisterForm.php?register=invalidnameheader&employeeID=$employeeID&employeeFirstName=$employeeFirstName&employeeLastName=$employeeLastName&employeeContactNum=$employeeContactNum&employeeEmail=$employeeEmail&employeeRegion=$employeeRegion&employeeCity=$employeeCity&employeeBarangay=$employeeBarangay&employeeStreetName=$employeeStreetName&employeeHouseNum=$employeeHouseNum&employeeType=$employeeType&dateLicenseRegistration=$dateLicenseRegistration&dateLicenseExpiration=$dateLicenseExpiration");
    }
    else {
      if (!preg_match("/^([0-9]+)$/", $employeeContactNum) || strlen($employeeContactNum) != 11) {
        header("Location: ../EmployeeRegisterForm.php?register=invalidmobile&employeeID=$employeeID&employeeFirstName=$employeeFirstName&employeeLastName=$employeeLastName&employeeContactNum=$employeeContactNum&employeeEmail=$employeeEmail&employeeRegion=$employeeRegion&employeeCity=$employeeCity&employeeBarangay=$employeeBarangay&employeeStreetName=$employeeStreetName&employeeHouseNum=$employeeHouseNum&employeeType=$employeeType&dateLicenseRegistration=$dateLicenseRegistration&dateLicenseExpiration=$dateLicenseExpiration");
      }
      else {
        //check if record already exist

        $sql = "SELECT * FROM employee WHERE (employeeFirstName = ? AND employeeLastName = ?)  OR employeeContactNum = ? OR employeeEmail = ?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)){
          echo "sql error";
        }
        else {
          // check if the waste already exist in the database
          mysqli_stmt_bind_param($stmt, "ssss", $employeeFirstName, $employeeLastName, $employeeContactNum, $employeeEmail);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);

          if(!mysqli_fetch_assoc($result)){
            $sql = "INSERT INTO employee (employeeID, employeeLastName, employeeFirstName, employeeContactNum,
                employeeEmail, employeeRegion, employeeCity, employeeBarangay, employeeStreetName, employeeHouseNum,
                employeeType) VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";

            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $sql)){
              echo "sql error";
            }
            else {

              mysqli_stmt_bind_param($stmt, "sssssssssss", $employeeID, $employeeLastName, $employeeFirstName,
              $employeeContactNum, $employeeEmail, $employeeRegion,  $employeeCity, $employeeBarangay, $employeeStreetName,
              $employeeHouseNum, $employeeType);
              mysqli_stmt_execute($stmt);

              if ($employeeType == 'D') {
                $sql = "INSERT INTO driver (dEmployeeID, dateLicenseRegistration, dateLicenseExpiration) VALUES (?,?,?);";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)){
                  echo "sql error";
                }
                else {
                  mysqli_stmt_bind_param($stmt, "sss", $employeeID, $dateLicenseRegistration, $dateLicenseExpiration);
                  mysqli_stmt_execute($stmt);
                }
              }
              if ($employeeType == 'A') {
                $sql = "INSERT INTO attendant (aEmployeeID) VALUES (?);";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)){
                  echo "sql error";
                }
                else {
                  mysqli_stmt_bind_param($stmt, "s", $employeeID);
                  mysqli_stmt_execute($stmt);
              }

            }
            header("Location: ../EmployeeRegisterForm.php?update=success");
          }
        }

        else {
          header("Location: ../EmployeeRegisterForm.php?register=recordalreadyexist&employeeID=$employeeID&employeeFirstName=$employeeFirstName&employeeLastName=$employeeLastName&employeeContactNum=$employeeContactNum&employeeEmail=$employeeEmail&employeeRegion=$employeeRegion&employeeCity=$employeeCity&employeeBarangay=$employeeBarangay&employeeStreetName=$employeeStreetName&employeeHouseNum=$employeeHouseNum&employeeType=$employeeType&dateLicenseRegistration=$dateLicenseRegistration&dateLicenseExpiration=$dateLicenseExpiration");
        }
      }
    }
    }
  }
