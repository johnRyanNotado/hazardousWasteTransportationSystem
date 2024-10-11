<?php
  include "dbh.inc.php";

  $adminUsername = $_POST['adminUsername'];
  $adminPassword = $_POST['adminPassword'];

  if(empty($adminUsername) || empty($adminPassword)){
    header("Location: ../AccountAccessForm.php?login=empty");
  }
  else {
    $sql = "SELECT * FROM admin WHERE adminUsername = ? AND adminPassword = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)){
      echo "sql error";
    }
    else {
      mysqli_stmt_bind_param($stmt, "ss", $adminUsername, $adminPassword);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);

      if($row = mysqli_fetch_assoc($result)) {
          session_start();
          $_SESSION['name'] = $row['adminFirstName']." ".$row['adminLastName'];
          header("Location: http://localhost:4000/HazardousWasteProj/Forms/CompanyMainInterfaceForm/CompanyMainInterfaceForm.php");
          exit();
        }
      else {
        header("Location: ../AccountAccessForm.php?login=notmatch");
      }
    }
  }

?>
