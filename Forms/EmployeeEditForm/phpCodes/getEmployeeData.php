<?php
  include "dbc.inc.php";

  $sql = "SELECT * FROM waste;";
  $result = mysqli_query($conn, $sql);
  echo "piece of shit";
  $data = array();
  if (mysqli_num_rows($result) > 0){
    while ($row = mysqli_fetch_assoc($result)) {
      $data[] = $row;
    }
    session_start();
    $_SESSION['wasteData'] = $data[];
  }
  else{
    echo "There are no data yet!";
  }

 ?>
