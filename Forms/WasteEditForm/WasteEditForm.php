<?php
  session_start();
  include "phpCodes/dbh.inc.php";
  if(!isset($_SESSION['name'])){
    header("Location: http://localhost:4000/HazardousWasteProj/Forms/AccessForm/AccountAccessForm.php");
  }
 ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Insert Waste</title>
    <title>ContractForm</title>
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Footer.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/General.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Header.css">
    <link rel="stylesheet" type="text/css" href="WasteEditForm.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href=
    "https://secureservercdn.net/198.71.233.110/s0i.8f2.myftpupload.com/wp-content/uploads/2020/03/cropped-5x3-1-32x32.png">


    <?php

      $sql = "SELECT * FROM waste;";
      $result = mysqli_query($conn, $sql);
      $data = array();
      if (mysqli_num_rows($result) > 0){
        while ($row = mysqli_fetch_assoc($result)) {
          $data[] = $row;
        }
      }
      else{
        echo "There are no data yet!";
      }

     ?>




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
        <form class="logout_form" action="includes/logout.php" method="post">
          <button type="submit" name="logout_button">Logout</button>
        </form>
      </div>
    </header>
    <main>
      <div class="wasteEditContainer">
        <div class="titleContainer">
          <div class="actionsTitle">
            <p style="padding-top: 0;">Actions Available</p>
          </div>
          <hr style="margin-bottom: 20px;">
        </div>
        <div class="wasteEditBox">
          <form class="wasteEditForm" action="phpCodes\WasteEdit.inc.php" method="post">
            <p class="inputTitle">Waste ID</p>
            <input class="wasteIdInput" type="text" name="wasteID" placeholder="Waste ID" maxlength="4">
            <p class="inputTitle">Waste Name</p>
            <input class="wasteNameInput" type="text" name="wasteName" placeholder="Waste Name" maxlength="30">

            <div class="radioButtonsContainer">
              <div class="radio">
                <input type="radio" name="action" value="Create">
                <label for="Create">Create</label>
              </div>
              <div class="radio">
                <input type="radio" name="action" value="Update">
                <label for="Update">Update</label>
              </div>
              <div class="radio">
                <input type="radio" name="action" value="Delete">
                <label for="Delete">Delete</label>
              </div>
            </div>

            <button type="submit" id="confirmButton">Confirm</button>
          </form>
        </div>

        <?php
        //Getting url for error checking
          $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

          if (strpos($fullUrl, "register=empty") == true){
            echo "<p class='error'>You did not fill in all required fields!</p>";
          } elseif (strpos($fullUrl, "update=wastedontexist") == true){
            echo "<p class='error'>Waste don't exist!</p>";
          } elseif (strpos($fullUrl, "update=success") == true){
            echo "<p class='success'>Update Successful!</p>";
          } elseif (strpos($fullUrl, "delete=success") == true){
            echo "<p class='success'>Delete Successful!</p>";
          } elseif (strpos($fullUrl, "register=success") == true){
            echo "<p class='success'>Create Successful!</p>";
          } elseif (strpos($fullUrl, "register=recordalreadyexist") == true){
            echo "<p class='error'>Record Already Exist!</p>";
          }
         ?>
      </div>
      <div class="wasteDataBox" id="wasteDataBox">
        <div class="wasteDataHeader">
          <div class="wasteIDHeader">
            Waste ID
          </div>
          <div class="wasteNameHeader">
            Waste Name
          </div>
        </div>

        <div class="wasteDataContent" id="wasteDataContent">
        </div>

      </div>

    </main>
    <footer class="footer">
      <p>Working towards sustainability for a better and safer environment.
        | © 2020 JM Ecotech Solutions Company. All Rights Reserved.
      </p>
    </footer>
    <script type="text/javascript">
      function displayData(jsArray){
        console.log(jsArray);

        for(i = 0; i < jsArray.length; i++){
          let wasteIDDiv = document.createElement('div');
          let wasteNameDiv = document.createElement('div');
          wasteIDDiv.innerText = jsArray[i]['wasteID'];
          wasteNameDiv.innerText = jsArray[i]['wasteName'];
          wasteDataContent.append(wasteIDDiv);
          wasteDataContent.append(wasteNameDiv);
        }
      }
      // function callIt(){
      const wasteDataContent = document.getElementById('wasteDataContent');
      let jsArray = <?php echo json_encode($data); ?>;
      displayData(jsArray);
      // }

    </script>
  </body>
</html>
