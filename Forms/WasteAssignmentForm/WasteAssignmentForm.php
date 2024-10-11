<?php
  session_start();
  if(!isset($_SESSION['name'])){
    header("Location: http://localhost:4000/HazardousWasteProj/Forms/AccessForm/AccountAccessForm.php");
  }
 ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Assign Waste</title>
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Footer.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/General.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Header.css">
    <link rel="stylesheet" type="text/css" href="WasteAssignmentForm.css">
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


        $sql = "SELECT * FROM waste;";
        $result = mysqli_query($conn, $sql);
        $wasteData = array();
        if (mysqli_num_rows($result) > 0){
          while ($row = mysqli_fetch_assoc($result)) {
            $wasteData[] = $row;
          }
        }





        $requestID =$_GET['requestID'];

        $sql = "SELECT w.wasteID, w.wasteName, wa.amount
        FROM wasteAssignment wa
          LEFT JOIN waste w
            ON w.wasteID = wa.wasteID
        WHERE wa.requestID = ?;";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "s", $requestID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $wasteAssigmentData = array();
        if (mysqli_num_rows($result) > 0){
          while ($row = mysqli_fetch_assoc($result)) {
            $wasteAssignmentData[] = $row;
          }
        }


       ?>
       <form class="" action="phpCodes/wasteAssign.inc.php" method="post">
         <div class="everything">
           <div class="requestDataContainer">
             <div class="requestFormDateBox">
                 <p class="inputTitle">Request ID</p>
                 <?php
                   if(isset($_GET['requestID'])){
                     echo '<input class="requestID" type="text" name="requestID" placeholder="Request ID" maxlength="20" value="'.$requestID.'" readonly>';
                   }
                   else {
                     echo '<input class="requestID" type="text" name="requestID" placeholder="Request ID" maxlength="20">';
                   }
                  ?>
             </div>
             <div style="display: flex; flex-direction: row; column-gap: 15px;">
               <button type="submit" name="findButton" value="find" style="margin-bottom: 20px;">Find</button>
               <button type="submit" name="findButton" value="cancel" style="margin-bottom: 20px;">Cancel</button>
             </div>
           </div>

           <div class="wasteAssignmentContainer">
            <div class="wasteAssignment" id='wasteAssignment'>
                <div class="wasteEditBox" style="padding: 15px;">
                    <p class="inputTitle"style='text-align: center;'>Waste ID</p>
                    <select name="wasteID" id="wasteOptions" placeholder="wasteOptions">
                      <?php
                        foreach($wasteData as $row){
                          $wasteID = $row['wasteID'];
                          $wasteName = $row['wasteName'];
                          echo "<option value=$wasteID>$wasteID : $wasteName</option>";
                        }
                       ?>
                    </select>
                    <p class="inputTitle" >Waste Amount (TONS)</p>
                    <input class="wasteAmountInput" id="wasteAmount" value="1" type="number" name="wasteAmount" placeholder="Waste Amount" min="1" max="50">
                    <div class="rowGrid">
                      <button type="submit" name="findButton" value="Add">Add</button>
                      <button type="submit" name="findButton" value="Delete">Delete</button>
                    </div>

                </div>

              </div>
            <div class="wasteDataBox" style="width:500px; height: 264px">
              <div class="wasteDataHeader">
                <div class="wasteIDHeader">
                  Waste ID
                </div>
                <div class="wasteAmountHeader">
                  Waste Name
                </div>
                <div class="wasteAmountHeader">
                  Amount
                </div>
              </div>

              <div class="wasteDataContent" id="wasteDataContent">
              </div>
            </div>
           </div>
           <div class="confirmationContainer">
             <button type="submit" name="findButton" value="confirm"style="background-color: lightgreen; width: 200px;">Confirm</button>
             <a href="http://localhost:4000/HazardousWasteProj/Forms/RequestMainInterfaceForm/RequestMainInterfaceForm.php?"
             >Go back</a>
           </div>
         </div>
         <?php
         //Getting url for error checking
           $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

           if (strpos($fullUrl, "register=empty") == true){
             echo "<p class='error'>You did not fill in all the required fields.</p>";
           } elseif (strpos($fullUrl, "update=wastedontexist") == true){
             echo "<p class='error'>Waste is not listed!</p>";
           } elseif (strpos($fullUrl, "add=success") == true){
             echo "<p class='success'>Waste added!</p>";
           } elseif (strpos($fullUrl, "delete=success") == true){
             echo "<p class='success'>Waste deleted!</p>";
           } elseif (strpos($fullUrl, "add=wasteAlreadyListed") == true){
             echo "<p class='error'>Waste is already listed!</p>";
           }
          ?>

       </form>

       <script type="text/javascript">
         function displayWasteAssignment(){
           let jsArray = <?php echo json_encode($wasteAssignmentData); ?>;
           for(i = 0; i < jsArray.length; i++){
             let wasteID = document.createElement('div');
             wasteID.innerText = jsArray[i]['wasteID'];
             let wasteName = document.createElement('div');
             wasteName.innerText = jsArray[i]['wasteName'];
             let wasteAmount = document.createElement('div');
             wasteAmount.innerText = jsArray[i]['amount'];
             wasteDataContent.append(wasteID);
             wasteDataContent.append(wasteName);
             wasteDataContent.append(wasteAmount);
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
