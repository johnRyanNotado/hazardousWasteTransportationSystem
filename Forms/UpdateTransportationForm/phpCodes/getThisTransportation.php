<?php
  session_start();
  include "dbh.inc.php";
  $transportationID = $_POST['transportationID'];

  if(empty($transportationID)){
    header("Location: ../UpdateTransportationForm.php?find=empty");
  }
  else{
      $sql = "SELECT r.requestID, c.clientID, c.clientCompanyName,
      r.denrRefCode, t.dateOfActualPickUp, t.transportationStatus
      FROM client c
        RIGHT JOIN request r
          ON c.clientID = r.clientID
        RIGHT JOIN unchange u
          ON u.uRequestID = r.requestID
        RIGHT JOIN transportation t
          ON u.uRequestID = t.uRequestID
      WHERE t.transportationID = ?";
      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        // check if the waste already exist in the database
        mysqli_stmt_bind_param($stmt, "s", $transportationID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($result)){
            $requestID = $row['requestID'];
            $clientID = $row['clientID'];
            $dateOfActualPickUp = $row['dateOfActualPickUp'];
            $denrRefCode = $row['denrRefCode'];
            $clientCompanyName = $row['clientCompanyName'];
            $transportationStatus = $row['transportationStatus'];

            header("Location: ../UpdateTransportationForm.php?find=exist&transportationID=$transportationID&requestID=$requestID&clientID=$clientID&dateOfActualPickUp=$dateOfActualPickUp&denrRefCode=$denrRefCode&clientCompanyName=$clientCompanyName&transportationStatus=$transportationStatus");
        }
        else{
          header("Location: ../UpdateTransportationForm.php?find=dontexist&transportationID=$transportationID");
        }
      }
  }

 ?>
