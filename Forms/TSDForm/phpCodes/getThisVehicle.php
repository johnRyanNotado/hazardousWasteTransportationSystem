<?php
  session_start();
  include "dbh.inc.php";

  $tsdID = $_POST['tsdID'];


  $sql = "SELECT tsdID, tsdName, tsdDenrID, tsdRegion, tsdCity, tsdBarangay, tsdStreetName, tsdHouseNum
  FROM tsd
  WHERE tsdID = ?";
  $stmt = mysqli_stmt_init($conn);

  if (!mysqli_stmt_prepare($stmt, $sql)){
    echo "sql error";
  }
  else {
    mysqli_stmt_bind_param($stmt, "s", $tsdID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($result)){
      $tsdID = $row['tsdID'];
      $tsdName = $row['tsdName'];
      $tsdDenrID = $row['tsdDenrID'];
      $tsdRegion = $row['tsdRegion'];
      $tsdCity = $row['tsdCity'];
      $tsdBarangay = $row['tsdBarangay'];
      $tsdStreetName = $row['tsdStreetName'];
      $tsdHouseNum = $row['tsdHouseNum'];
      header("Location: ../TSDForm.php?find=TSDexist&tsdID=$tsdID&tsdName=$tsdName&tsdDenrID=$tsdDenrID&tsdRegion=$tsdRegion&tsdCity=$tsdCity&tsdBarangay=$tsdBarangay&tsdStreetName=$tsdStreetName&tsdHouseNum=$tsdHouseNum");
    }
    else {
      header("Location: ../TSDForm.php?update=TSDdoentexist");
    }
  }
 ?>
