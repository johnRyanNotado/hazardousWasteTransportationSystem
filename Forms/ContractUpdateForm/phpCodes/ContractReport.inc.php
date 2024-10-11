<?php
  session_start();
  include "dbh.inc.php";
  $contractID = $_POST['contractIDSet'];
  $actionBottom = $_POST['actionBottom'];

  if(empty($contractID)){
    header("Location: ../ContractUpdateForm.php?find=dontexist&contractID=$contractID");
    exit();
  }
  else {
    if($actionBottom == 'See'){
      header("Location: http://localhost:4000/HazardousWasteProj/Forms/ContractReportForm/ContractReportForm.php?&contractID=$contractID");
    }
  }
