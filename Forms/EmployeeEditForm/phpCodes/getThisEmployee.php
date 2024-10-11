<?php
  session_start();
  include "dbh.inc.php";
  $employeeID = $_POST['employeeID'];

  if(empty($employeeID)){
    header("Location: ../EmployeeEditForm.php?find=empty");
  }
  else{
      $sql = "SELECT * FROM employee WHERE employeeID = ?";
      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        // check if the waste already exist in the database
        mysqli_stmt_bind_param($stmt, "s", $employeeID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($result)){
            $employeeLastName = $row['employeeLastName'];
            $employeeFirstName = $row['employeeFirstName'];
            $employeeContactNum = $row['employeeContactNum'];
            $employeeEmail = $row['employeeEmail'];
            $employeeRegion = $row['employeeRegion'];
            $employeeCity = $row['employeeCity'];
            $employeeBarangay = $row['employeeBarangay'];
            $employeeStreetName = $row['employeeStreetName'];
            $employeeHouseNum = $row['employeeHouseNum'];
            $employeePassword = $row['employeePassword'];
            $employeeType = $row['employeeType'];
            if($employeeType == 'A'){
              header("Location: ../EmployeeEditForm.php?find=exist&employeeID=$employeeID&employeeFirstName=$employeeFirstName&employeeLastName=$employeeLastName&employeeContactNum=$employeeContactNum&employeeEmail=$employeeEmail&employeeRegion=$employeeRegion&employeeCity=$employeeCity&employeeBarangay=$employeeBarangay&employeeStreetName=$employeeStreetName&employeeHouseNum=$employeeHouseNum&employeeType=$employeeType&employeePassword=$employeePassword");
            }
            else if ($employeeType == 'D') {
              $sql = "SELECT * FROM driver WHERE dEmployeeID = ?";
              $stmt = mysqli_stmt_init($conn);
              if (!mysqli_stmt_prepare($stmt, $sql)){
                echo "sql error";
              }
              else {
                mysqli_stmt_bind_param($stmt, "s", $employeeID);
                mysqli_stmt_execute($stmt);
                $driverResult = mysqli_stmt_get_result($stmt);
                if($driverRow = mysqli_fetch_assoc($driverResult)){
                    $dateLicenseRegistration = $driverRow['dateLicenseRegistration'];
                    $dateLicenseExpiration = $driverRow['dateLicenseExpiration'];

                    header("Location: ../EmployeeEditForm.php?find=exist&employeeID=$employeeID&employeeFirstName=$employeeFirstName&employeeLastName=$employeeLastName&employeeContactNum=$employeeContactNum&employeeEmail=$employeeEmail&employeeRegion=$employeeRegion&employeeCity=$employeeCity&employeeBarangay=$employeeBarangay&employeeStreetName=$employeeStreetName&employeeHouseNum=$employeeHouseNum&employeeType=$employeeType&employeePassword=$employeePassword&dateLicenseRegistration=$dateLicenseRegistration&dateLicenseExpiration=$dateLicenseExpiration");

                }
                else {
                  echo 'this driver exists in the employee table but not in the driver table';
                }
              }
            }

        }
        else{
          header("Location: ../EmployeeEditForm.php?find=dontexist&employeeID=$employeeID");
        }
      }
  }





 ?>
