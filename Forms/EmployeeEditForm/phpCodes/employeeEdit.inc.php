<?php
  session_start();
  include_once "dbh.inc.php";

  $employeeID = $_POST['employeeIDSet'];
  $employeeLastName = $_POST['employeeLastName'];
  $employeeFirstName = $_POST['employeeFirstName'];
  $employeeContactNum = $_POST['employeeContactNum'];
  $employeeEmail = $_POST['employeeEmail'];
  $employeeRegion = $_POST['employeeRegion'];
  $employeeCity = $_POST['employeeCity'];
  $employeeBarangay = $_POST['employeeBarangay'];
  $employeeStreetName = $_POST['employeeStreetName'];
  $employeeHouseNum = $_POST['employeeHouseNum'];
  $action = $_POST['action'];
  echo $action;
  $actionBottom = $_POST['actionBottom'];

  if ($action == 'D'){
    $dateLicenseRegistration = $_POST['dateLicenseRegistration'];
    $dateLicenseExpiration = $_POST['dateLicenseExpiration'];
    if ($dateLicenseExpiration < $dateLicenseRegistration){
      header("Location: ../EmployeeRegisterForm.php?license=cannotbe");
    } // put iddown
    else {
      if (empty($dateLicenseExpiration) || empty($dateLicenseRegistration)) {
        echo "shit";
        header("Location: ../EmployeeEditForm.php?update=empty");
      }
    }
  }


  if (empty($employeeID) || empty($employeeLastName) || empty($employeeFirstName) || empty($employeeContactNum) ||
  empty($employeeEmail) || empty($employeeRegion) || empty($employeeCity) || empty($employeeBarangay)
  || empty($employeeStreetName ) || empty($employeeHouseNum) || empty($action) || empty($actionBottom))
  {
    header("Location: ../EmployeeEditForm.php?update=emptyheadert&employeeID=$employeeID&employeeFirstName=$employeeFirstName&employeeLastName=$employeeLastName&employeeContactNum=$employeeContactNum&employeeEmail=$employeeEmail&employeeRegion=$employeeRegion&employeeCity=$employeeCity&employeeBarangay=$employeeBarangay&employeeStreetName=$employeeStreetName&employeeHouseNum=$employeeHouseNum&employeeType=$action&dateLicenseRegistration=$dateLicenseRegistration&dateLicenseExpiration=$dateLicenseExpiration");
  }
  else {
    // check if invalid name
    if (!preg_match("/^([a-zA-Z' ]+)$/",$employeeFirstName) || !preg_match("/^([a-zA-Z' ]+)$/", $employeeLastName)){
      header("Location: ../EmployeEditForm.php?register=invalidname&employeeID=$employeeID&employeeFirstName=$employeeFirstName&employeeLastName=$employeeLastName&employeeContactNum=$employeeContactNum&employeeEmail=$employeeEmail&employeeRegion=$employeeRegion&employeeCity=$employeeCity&employeeBarangay=$employeeBarangay&employeeStreetName=$employeeStreetName&employeeHouseNum=$employeeHouseNum&employeeType=$action&dateLicenseRegistration=$dateLicenseRegistration&dateLicenseExpiration=$dateLicenseExpiration");
    }
    else {
      if (!preg_match("/^([0-9]+)$/", $employeeContactNum) || strlen($employeeContactNum) != 11) {
        header("Location: ../EmployeeEditForm.php?register=invalidmobile&employeeID=$employeeID&employeeFirstName=$employeeFirstName&employeeLastName=$employeeLastName&employeeContactNum=$employeeContactNum&employeeEmail=$employeeEmail&employeeRegion=$employeeRegion&employeeCity=$employeeCity&employeeBarangay=$employeeBarangay&employeeStreetName=$employeeStreetName&employeeHouseNum=$employeeHouseNum&employeeType=$actione&dateLicenseRegistration=$dateLicenseRegistration&dateLicenseExpiration=$dateLicenseExpiration");
      }
      else {

        if($actionBottom == 'Update')  {
          $sql = "SELECT * FROM employee WHERE employeeID != ? AND ((employeeFirstName = ? AND employeeLastName = ?) OR employeeEmail = ? OR employeeContactNum = ?);";
          $stmt = mysqli_stmt_init($conn);

          if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "sql error";
          }
          else {
            // check if the credentials have other match already exist in the database
            mysqli_stmt_bind_param($stmt, "sssss", $employeeID, $employeeFirstName, $employeeLastName, $employeeEmail, $employeeContactNum);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_fetch_assoc($result) == 0){

              $sql = "SELECT employeeType FROM employee WHERE employeeID = ?;";
              $stmt = mysqli_stmt_init($conn);
              mysqli_stmt_prepare($stmt, $sql);
              mysqli_stmt_bind_param($stmt, "s", $employeeID);
              mysqli_stmt_execute($stmt);
              $result = mysqli_stmt_get_result($stmt);

              $data = mysqli_fetch_assoc($result);
              // check if the updated version has a new employee type
              if ($action == $data['employeeType']){
                echo "shit3";
                $sql = "UPDATE employee SET employeeLastName = ?, employeeFirstName = ?, employeeContactNum = ?,
                    employeeEmail = ?, employeeRegion = ?, employeeCity = ?, employeeBarangay = ?, employeeStreetName = ?, employeeHouseNum = ?,
                    employeeType = ? WHERE employeeID = ?;";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)){
                  echo "sql error";
                }
                else {
                  mysqli_stmt_bind_param($stmt, "sssssssssss", $employeeLastName, $employeeFirstName,
                  $employeeContactNum, $employeeEmail, $employeeRegion,  $employeeCity, $employeeBarangay, $employeeStreetName,
                  $employeeHouseNum, $action, $employeeID);
                  mysqli_stmt_execute($stmt);

                  if ($action == 'D') {
                    $sql = "UPDATE driver SET dateLicenseRegistration = ?, dateLicenseExpiration =? WHERE dEmployeeID = ?;";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)){
                      echo "sql error";
                    }
                    else {
                      mysqli_stmt_bind_param($stmt, "sss", $dateLicenseRegistration, $dateLicenseExpiration, $employeeID);
                      mysqli_stmt_execute($stmt);
                    }

                  }
                  header("Location: ../EmployeeEditForm.php?update=Updated&employeeID=$employeeID&employeeFirstName=$employeeFirstName&employeeLastName=$employeeLastName&employeeContactNum=$employeeContactNum&employeeEmail=$employeeEmail&employeeRegion=$employeeRegion&employeeCity=$employeeCity&employeeBarangay=$employeeBarangay&employeeStreetName=$employeeStreetName&employeeHouseNum=$employeeHouseNum&employeeType=$action&dateLicenseRegistration=$dateLicenseRegistration&dateLicenseExpiration=$dateLicenseExpiration");
                }
              }
              else {
                // if new type is driver
                  if ($action == 'D') {
                    echo "shit2";
                    $sql = "DELETE FROM attendant WHERE aEmployeeID = ?";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)){
                      echo "sql error";
                    }
                    else {
                      mysqli_stmt_bind_param($stmt, "s", $employeeID);
                      mysqli_stmt_execute($stmt);
                    }

                    $sql = "UPDATE employee SET employeeLastName = ?, employeeFirstName = ?, employeeContactNum = ?,
                        employeeEmail = ?, employeeRegion = ?, employeeCity = ?, employeeBarangay = ?, employeeStreetName = ?, employeeHouseNum = ?,
                        employeeType = ?, WHERE employeeID = ?;";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)){
                      echo "sql error";
                    }
                    else {
                      mysqli_stmt_bind_param($stmt, "sssssssssss", $employeeLastName, $employeeFirstName,
                      $employeeContactNum, $employeeEmail, $employeeRegion,  $employeeCity, $employeeBarangay, $employeeStreetName,
                      $employeeHouseNum, $action, $employeeID);
                      mysqli_stmt_execute($stmt);

                      $sql = "UPDATE driver SET dateLicenseRegistration = ?, dateLicenseExpiration =? WHERE dEmployeeID = ?;";
                      $stmt = mysqli_stmt_init($conn);
                      if (!mysqli_stmt_prepare($stmt, $sql)){
                        echo "sql error";
                      }
                      else {
                        mysqli_stmt_bind_param($stmt, "sss", $dateLicenseRegistration, $dateLicenseExpiration, $employeeID);
                        mysqli_stmt_execute($stmt);
                      }
                    header("Location: ../EmployeeEditForm.php?update=Updated");
                    }
                  }

                  else if ($action == 'A'){
                    echo "shit";
                    $sql = "UPDATE employee SET employeeLastName = ?, employeeFirstName = ?, employeeContactNum = ?,
                        employeeEmail = ?, employeeRegion = ?, employeeCity = ?, employeeBarangay = ?, employeeStreetName = ?, employeeHouseNum = ?,
                        employeeType = ? WHERE employeeID = ?;";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)){
                      echo "sql error";
                    }
                    else {
                      mysqli_stmt_bind_param($stmt, "sssssssssss", $employeeLastName, $employeeFirstName,
                      $employeeContactNum, $employeeEmail, $employeeRegion,  $employeeCity, $employeeBarangay, $employeeStreetName,
                      $employeeHouseNum, $action, $employeeID);
                      mysqli_stmt_execute($stmt);

                        $sql = "INSERT INTO attendant (aEmployeeID) VALUES (?);";
                        $stmt = mysqli_stmt_init($conn);
                        if (!mysqli_stmt_prepare($stmt, $sql)){
                          echo "sql error";
                        }
                        else {
                          mysqli_stmt_bind_param($stmt, "s", $employeeID);
                          mysqli_stmt_execute($stmt);
                        }

                      $sql = "DELETE FROM driver WHERE dEmployeeID = ?;";
                      $stmt = mysqli_stmt_init($conn);
                      if (!mysqli_stmt_prepare($stmt, $sql)){
                        echo "sql error";
                      }
                      else {
                        mysqli_stmt_bind_param($stmt, "s", $employeeID);
                        mysqli_stmt_execute($stmt);
                      }
                      header("Location: ../EmployeeEditForm.php?update=Updated");



                    }
                  }
                }
        }
            else {
              header("Location: ../EmployeeEditForm.php?update=credentialsmatch&employeeID=$employeeID&employeeFirstName=$employeeFirstName&employeeLastName=$employeeLastName&employeeContactNum=$employeeContactNum&employeeEmail=$employeeEmail&employeeRegion=$employeeRegion&employeeCity=$employeeCity&employeeBarangay=$employeeBarangay&employeeStreetName=$employeeStreetName&employeeHouseNum=$employeeHouseNum&employeeType=$action&dateLicenseRegistration=$dateLicenseRegistration&dateLicenseExpiration=$dateLicenseExpiration");
            }
          }
        }
         // deleting employee record
        else if ($actionBottom == 'Delete') {

          if($action == 'A'){
            $sql = "SELECT * FROM attendantAssignment WHERE aEmployeeID = ?";
          }
          else if ($action == 'D'){
            $sql = "SELECT * FROM vehicleAssignment WHERE dEmployeeID = ?";
          }

          $stmt = mysqli_stmt_init($conn);

          if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "sql error";
          }
          else {
            mysqli_stmt_bind_param($stmt, "s", $employeeID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_fetch_assoc($result) > 0){
              header("Location: ../EmployeeEditForm.php?update=cannotBeDeleted&employeeID=$employeeID&employeeFirstName=$employeeFirstName&employeeLastName=$employeeLastName&employeeContactNum=$employeeContactNum&employeeEmail=$employeeEmail&employeeRegion=$employeeRegion&employeeCity=$employeeCity&employeeBarangay=$employeeBarangay&employeeStreetName=$employeeStreetName&employeeHouseNum=$employeeHouseNum&employeeType=$action&dateLicenseRegistration=$dateLicenseRegistration&dateLicenseExpiration=$dateLicenseExpiration");
            }
            else {

              if ($action == 'D') {
                $sql = "DELETE FROM driver WHERE dEmployeeID = ?";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)){
                  echo "sql error";
                }
                else {
                  mysqli_stmt_bind_param($stmt, "s", $employeeID);
                  mysqli_stmt_execute($stmt);
                }
              }
              if ($action == 'A') {
                $sql = "DELETE FROM attendant WHERE aEmployeeID = ?";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)){
                  echo "sql error";
                }
                else {
                  mysqli_stmt_bind_param($stmt, "s", $employeeID);
                  mysqli_stmt_execute($stmt);
                }
              }
              $sql = "DELETE FROM employee WHERE employeeID = ?";
              $stmt = mysqli_stmt_init($conn);
              if (!mysqli_stmt_prepare($stmt, $sql)){
                echo "sql error";
              }
              else {
                mysqli_stmt_bind_param($stmt, "s", $employeeID);
                mysqli_stmt_execute($stmt);
              }
              header("Location: ../EmployeeEditForm.php?update=Deleted");

            }
          }
        }
      }
    }
  }
