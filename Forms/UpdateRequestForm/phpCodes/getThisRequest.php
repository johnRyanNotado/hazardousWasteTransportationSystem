<?php
  session_start();
  include "dbh.inc.php";
  $requestID = $_POST['requestID'];

  if(empty($requestID)){
    header("Location: ../UpdateRequestForm.php?find=empty");
  }
  else{
      $sql = "SELECT *
      FROM request r
        LEFT JOIN tsd t
         ON t.tsdID = r.tsdID
      WHERE requestID = ?";
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
            $clientID = $row['clientID'];
            $specDateOfPickUp = $row['specDateOfPickUp'];
            $tsdDenrID = $row['tsdDenrID'];
            $tsdName = $row['tsdName'];
            $tsdRegion = $row['tsdRegion'];
            $tsdCity = $row['tsdCity'];
            $tsdBarangay = $row['tsdBarangay'];
            $tsdStreetName = $row['tsdStreetName'];
            $tsdBarangay = $row['tsdBarangay'];
            $tsdHouseNum = $row['tsdHouseNum'];
            $dateNAGen = $row['dateNAGen'];
            $dateIssuanceAckLet = $row['dateIssuanceAckLet'];
            $denrRefCode = $row['denrRefCode'];
            $requestStatus = $row['requestStatus'];
            $contractID = $row['contractID'];

            $sql = "SELECT denrGenID, clientCompanyName FROM client WHERE clientID = ?";
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, "s", $clientID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $allData = array();
            $row = mysqli_fetch_assoc($result);
            $denrGenID = $row['denrGenID'];
            $clientCompanyName  = $row['clientCompanyName'];

            $denrGenID = $row['denrGenID'];
            $companyName = $row['clientCompanyName'];
            header("Location: ../UpdateRequestForm.php?find=exist&requestID=$requestID&denrGenID=$denrGenID&clientCompanyName=$clientCompanyName&specDateOfPickUp=$specDateOfPickUp&tsdDenrID=$tsdDenrID&tsdName=$tsdName&tsdRegion=$tsdRegion&tsdCity=$tsdCity&tsdBarangay=$tsdBarangay&tsdStreetName=$tsdStreetName&tsdHouseNum=$tsdHouseNum&denrRefCode=$denrRefCode&dateNAGen=$dateNAGen&dateIssuanceAckLet=$dateIssuanceAckLet&requestStatus=$requestStatus&contractID=$contractID");
        }
        else{
          header("Location: ../UpdateRequestForm.php?find=dontexist&requestID=$requestID");
        }
      }
  }





 ?>
