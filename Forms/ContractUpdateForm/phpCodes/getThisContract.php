<?php
  session_start();
  include "dbh.inc.php";
  $contractID = $_POST['contractID'];

  if(empty($contractID)){
    header("Location: ../ContractUpdateForm.php?find=empty");
  }
  else{
      $sql = "SELECT cl.clientID, cl.clientCompanyName,
      cl.denrGenID, c.contractStatus
      FROM client cl
        RIGHT JOIN contract c
          ON c.clientID = cl.clientID
      WHERE c.contractID = ?";
      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "sql error";
      }
      else {
        // check if the waste already exist in the database
        mysqli_stmt_bind_param($stmt, "s", $contractID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($result)){
            $clientID = $row['clientID'];
            $denrGenID = $row['denrGenID'];
            $clientCompanyName = $row['clientCompanyName'];
            $contractStatus = $row['contractStatus'];

            header("Location: ../ContractUpdateForm.php?find=exist&contractIDSet=$contractID&contractID=$contractID&clientID=$clientID&denrGenID=$denrGenID&clientCompanyName=$clientCompanyName&contractStatus=$contractStatus");
        }
        else{
          header("Location: ../ContractUpdateForm.php?find=dontexist&contractID=$contractID");
        }
      }
  }

 ?>
