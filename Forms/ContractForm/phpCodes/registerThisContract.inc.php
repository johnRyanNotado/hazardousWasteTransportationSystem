<?php
  $denrGenID = $_POST['denrGenID'];
  $startDate = $_POST['startDate'];
  $endDate = $_POST['endDate'];
  $numOfRequest = $_POST['numOfRequest'];
  $preTerminationFee = $_POST['preTerminationFee'];
  $contractDescription = $_POST['contractDescription'];

  if(empty($denrGenID) || empty($startDate) || empty($endDate) || empty($numOfRequest) || empty($preTerminationFee) || empty($contractDescription)){
    header("Location: http://localhost:4000/HazardousWasteProj/Forms/ContractForm/ContractForm.php?register=empty&denrGenID=$denrGenID&startDate=$startDate&endDate=$endDate&numOfRequest=$numOfRequest&preTerminationFee=$preTerminationFee&contractDescription=$contractDescription");
    exit();
  }
  else {
    $clientID = clientExist($denrGenID);
    if($clientID == 'null'){
      header("Location: http://localhost:4000/HazardousWasteProj/Forms/ContractForm/ContractForm.php?clientDoesNotExist&denrGenID=$denrGenID&startDate=$startDate&endDate=$endDate&numOfRequest=$numOfRequest&preTerminationFee=$preTerminationFee&contractDescription=$contractDescription");
      exit();
    }
    else {
      registerThisContract($clientID);
    }
  }


  function clientExist($denrGenID){
    include "dbh.inc.php";

    $sql = "SELECT clientID FROM client WHERE denrGenID = ?
    ;";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
      echo "sql error";
    }
    else {
      mysqli_stmt_bind_param($stmt, "s", $denrGenID);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        $clientID = $row['clientID'];
        return $clientID;
      }
      else {
        return 'null';
      }
    }
  }


  function registerThisContract($clientID){
    include "dbh.inc.php";
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $numOfRequest = $_POST['numOfRequest'];
    $preTerminationFee = $_POST['preTerminationFee'];
    $contractDescription = $_POST['contractDescription'];
    $contractID= 'CN-'.date('Y-mdhis');
    $sql = "INSERT INTO contract (contractID, endDate, startDate, numOfRequest, preTerminationFee, contractDescription, clientID) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
      echo "sql error";
    }
    else {
      mysqli_stmt_bind_param($stmt, "sssssss", $contractID, $endDate, $startDate, $numOfRequest, $preTerminationFee, $contractDescription, $clientID);
      mysqli_stmt_execute($stmt);
      header("Location: http://localhost:4000/HazardousWasteProj/Forms/ContractForm/ContractForm.php?contract=registered");
      exit();

      }
    }
