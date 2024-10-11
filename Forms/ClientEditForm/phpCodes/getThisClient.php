<?php
  session_start();
  include "dbh.inc.php";
  $clientID = $_POST['clientID'];

  if(empty($clientID)){
    header("Location: ../ClientEditForm.php?find=empty");
  }
  else{
      $sql = "SELECT * FROM client WHERE clientID = ?";
      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        // check if the waste already exist in the database
        mysqli_stmt_bind_param($stmt, "s", $clientID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($result)){
            $email = $row['clientEmailAddress'];
            $mobileNum = $row['clientContactNum'];
            $denrID = $row['denrGenID'];
            $companyName = $row['clientCompanyName'];
            $region = $row['clientRegion'];
            $city = $row['clientCity'];
            $barangay = $row['clientBarangay'];
            $streetName = $row['clientStreetName'];
            $houseNum = $row['clientHouseNum'];


            header("Location: ../ClientEditForm.php?find=exist&clientID=$clientID&mobileNum=$mobileNum&email=$email&region=$region&city=$city&barangay=$barangay&streetName=$streetName&houseNum=$houseNum&denrID=$denrID&companyName=$companyName");
        }
        else{
          header("Location: ../ClientEditForm.php?find=dontexist&clientID=$clientID");
        }
      }
  }





 ?>
