<?php
  session_start();
  include "dbh.inc.php";
  $transportationID = $_POST['transportationIDSet'];
  $actionBottom = $_POST['actionBottom'];

  if(empty($transportationID)){
    header("Location: ../UpdateTransportationForm.php?find=dontexist&transportationID=$transportationID");
  }
  else {
    if($actionBottom == 'See'){
      header("Location: http://localhost:4000/HazardousWasteProj/Forms/UpdateThisTransportation/UpdateThisTransportationForm.php?&transportationID=$transportationID");
    }
  }
