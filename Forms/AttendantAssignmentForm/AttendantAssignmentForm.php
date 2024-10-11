<?php
  session_start();
 ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Attendant</title>
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Footer.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/General.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Header.css">
    <link rel="stylesheet" type="text/css" href="AttendantAssignmentForm.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href=
    "https://secureservercdn.net/198.71.233.110/s0i.8f2.myftpupload.com/wp-content/uploads/2020/03/cropped-5x3-1-32x32.png">

  </head>
  <body>
    <header>
      <div class="logo_box">
        <img class="logo_header" src="HeaderImages\ECOTECH-LOGO.png">
      </div>
      <div class="tabs_box">
        <div class="username_container">
          <p><?php echo $_SESSION['name']; ?></p>
        </div>
        <form class="logout_form" action="phpCodes/home.php" method="post">
          <button type="submit" name="home_button">Home</button>
        </form>
        <form class="logout_form" action="phpCodes/logout.php" method="post">
          <button type="submit" name="logout_button">Logout</button>
        </form>
      </div>
    </header>
    <main>
      <?php
        include "phpCodes/dbh.inc.php";
        $dateOfActualPickUp;
        if(isset($_GET['dateOfActualPickUp'])){
          $dateOfActualPickUp = $_GET['dateOfActualPickUp'];
        }

        $sql = "SELECT e.employeeLastName, e.employeeFirstName, e.employeeID
        FROM employee e
          RIGHT JOIN attendant a
            ON e.employeeID = a.aEmployeeID
          LEFT JOIN attendantAssignment aA
            ON a.aEmployeeID = aA.aEmployeeID
          LEFT JOIN employeeAssignment eA
            ON aA.eAssignmentID = eA.employeeAssignmentID
          LEFT JOIN transportation t
            ON t.transportationID = eA.transportationID
        WHERE t.dateOfActualPickUp IS NULL OR t.dateOfActualPickUp != ?";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "s", $dateOfActualPickUp);

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $attendantData = array();
        if (mysqli_num_rows($result) > 0){
          while ($rows = mysqli_fetch_assoc($result)) {
            $attendantData[] = $rows;
          }
        }

        $employeeAssignmentID;
        if(isset($_GET['employeeAssignmentID'])){
          $employeeAssignmentID= $_GET['employeeAssignmentID'];
        } else {
          $dateOfActualPickUp[] = null;
        }

        $sql = "SELECT e.employeeLastName, e.employeeFirstName, e.employeeID
        FROM employee e
          RIGHT JOIN attendant a
            ON e.employeeID = a.aEmployeeID
          RIGHT JOIN attendantAssignment aA
            ON a.aEmployeeID = aA.aEmployeeID
          WHERE aA.eAssignmentID = ?;";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "s", $employeeAssignmentID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $attendantAssigmentData = array();
        if (mysqli_num_rows($result) > 0){
          while ($rows = mysqli_fetch_assoc($result)) {
            $attendantAssignmentData[] = $rows;
          }
        } else {
          $attendantAssignmentData[] =null;
        }
       ?>
       <form class="" action="phpCodes/attendantAssignment.inc.php" method="post" style="margin-top: 100px;">
         <input type="hidden" name="employeeAssignmentID" value="<?php echo $employeeAssignmentID ?>">
         <input type="hidden" name="dateOfActualPickUp" value="<?php echo $dateOfActualPickUp ?>">
         <div class="everything">
           <div class="requestDataContainer">
             <div class="requestFormDateBox">
                 <p class="inputTitle">Transportation ID</p>
                 <?php
                   if(isset($_GET['transportationID'])){
                     echo '<input class="transportationID" type="text" name="transportationID" placeholder="Transportation ID" maxlength="20" value="'.$_GET['transportationID'].'" readonly>';
                   }
                   else {
                     echo '<input class="transportationID" type="text" name="transportationID" placeholder="Transportation ID" maxlength="20">';
                   }
                  ?>
             </div>
             <div style="display: flex; flex-direction: row; column-gap: 15px;">
               <button type="submit" name="findButton" value="Find" style="margin-bottom: 20px;">Find</button>
               <button type="submit" name="findButton" value="Cancel" style="margin-bottom: 20px;">Cancel</button>
             </div>
           </div>

           <div class="wasteAssignmentContainer">
            <div class="wasteAssignment" id='wasteAssignment'>
                <div class="wasteEditBox" style="padding:0; width: 500px; row-gap: 20px;">
                    <p class="inputTitle"style='text-align: center; margin-top: 20px;'>Attendant</p>
                    <select name="attendantID" id="wasteOptions" placeholder="wasteOptions">
                      <?php
                        foreach($attendantData as $row){
                          $attendantID = $row['employeeID'];
                          $attendantLastName = $row['employeeLastName'];
                          $attendantFirstName = $row['employeeFirstName'];
                          echo "<option value=$attendantID>$attendantID: $attendantFirstName $attendantLastName</option>";
                        }
                       ?>
                    </select>
                    <div class="rowGrid">
                      <button type="submit" name="findButton" value="Add">Add</button>
                      <button type="submit" name="findButton" value="Delete">Delete</button>
                    </div>

                </div>

              </div>
            <div class="wasteDataBox" style="padding:0; width: 500px;">
              <div class="wasteDataHeader" style="grid-template-columns: 1fr 1fr;">
                <div class="wasteIDHeader">
                  Attendant ID
                </div>
                <div class="wasteAmountHeader " style="grid-template-columns: 1fr 1fr;">
                  Attendant Name
                </div>
              </div>

              <div class="wasteDataContent" id="wasteDataContent">
              </div>
            </div>
           </div>
           <div class="confirmationContainer">
             <button type="submit" name="findButton" value="Confirm" style="width: 100px;">Exit</button>

           </div>
         </div>
         <?php
         //Getting url for error checking
           $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

           if (strpos($fullUrl, "register=empty") == true){
             echo "<p class='error'>You did not fill in all the required fields.</p>";
           } elseif (strpos($fullUrl, "attendant=deleted") == true){
             echo "<p class='success'>Records deleted!</p>";
           } elseif (strpos($fullUrl, "attendant=added") == true){
             echo "<p class='success'>Record added!</p>";
           } elseif (strpos($fullUrl, "attendant=exist") == true){
             echo "<p class='success'>Record found!</p>";
           } elseif (strpos($fullUrl, "attendant=dontexist") == true){
             echo "<p class='error'>Transportation record not found.</p>";
           } elseif (strpos($fullUrl, "attendant=alreadyexist") == true){
             echo "<p class='error'>The attendant is already listed.</p>";
           }
          ?>

       </form>

       <script type="text/javascript">
         function displayWasteAssignment(){
           let jsArray = <?php echo json_encode($attendantAssignmentData); ?>;
           for(i = 0; i < jsArray.length; i++){
             let attendantID = document.createElement('div');
             let attendantName = document.createElement('div');

             let attendantFirstName = jsArray[i]['employeeFirstName'];
             let attendantLastName = jsArray[i]['employeeLastName'];
             attendantName.innerText = attendantFirstName + " " + attendantLastName;
             attendantID.innerText = jsArray[i]['employeeID'];
             wasteDataContent.append(attendantID);
             wasteDataContent.append(attendantName);
           }
         }

         const wasteDataContent = document.getElementById('wasteDataContent');
         displayWasteAssignment();
       </script>
    </main>
    <footer class="footer">
      <p>Working towards sustainability for a better and safer environment.
        | © 2020 JM Ecotech Solutions Company. All Rights Reserved.
      </p>
    </footer>
  </body>
</html>
