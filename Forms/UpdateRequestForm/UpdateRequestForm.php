<?php
  session_start();

  if(!isset($_SESSION['name'])){
    header("Location: http://localhost:4000/HazardousWasteProj/Forms/AccessForm/AccountAccessForm.php");
  }
 ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Update Request</title>
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Footer.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/General.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Header.css">
    <link rel="stylesheet" type="text/css" href="UpdateRequestForm.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href=
    "https://secureservercdn.net/198.71.233.110/s0i.8f2.myftpupload.com/wp-content/uploads/2020/03/cropped-5x3-1-32x32.png">

  </head>
  <body>
      <?php
        include "phpCodes/dbh.inc.php";

        $sql = "SELECT r.requestID, c.clientID, r.denrRefCode, r.dateNAGen, r.dateIssuanceAckLet, c.denrGenID, r.specDateOfPickUp, c.clientCompanyName,
        r.contractID, c.clientContactNum, c.clientEmailAddress, c.clientRegion,
        c.clientCity, c.clientBarangay, c.clientStreetName, c.clientHouseNum, t.tsdName, t.tsdDenrID, t.tsdRegion, t.tsdCity,
        t.tsdBarangay, t.tsdStreetName, t.tsdHouseNum
        FROM request r
          LEFT JOIN client c
            ON r.clientID = c.clientID
          LEFT JOIN tsd t
            ON r.tsdID = t.tsdID
        WHERE r.requestStatus = 'U' AND (denrRefCode IS NULL OR r.dateNAGen IS NULL OR r.dateIssuanceAckLet IS NULL OR r.dateNAGen = 0000-00-00 OR r.dateIssuanceAckLet = 0000-00-00)
        ORDER BY r.specDateOfPickUp, c.clientRegion, c.clientCity, c.clientBarangay, c.clientStreetName, c.clientHouseNum, c.clientCompanyName;";

        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)){
          echo "sql error";
        }
        else {
          $result = mysqli_query($conn, $sql);
          $unverifiedData = array();
          if (mysqli_num_rows($result) > 0){
            while ($row = mysqli_fetch_assoc($result)) {
              $unverifiedData[] = $row;
            }
          }
        }


        $sql = "SELECT r.requestID, c.clientID, r.denrRefCode, r.dateNAGen, r.dateIssuanceAckLet, c.denrGenID, r.specDateOfPickUp, c.clientCompanyName,
        r.contractID, c.clientContactNum, c.clientEmailAddress, c.clientRegion,
        c.clientCity, c.clientBarangay, c.clientStreetName, c.clientHouseNum, t.tsdName, t.tsdDenrID, t.tsdRegion, t.tsdCity,
        t.tsdBarangay, t.tsdStreetName, t.tsdHouseNum
        FROM request r
          LEFT JOIN client c
            ON r.clientID = c.clientID
          LEFT JOIN tsd t
            ON r.tsdID = t.tsdID
        WHERE r.requestStatus = 'C'
        ORDER BY r.specDateOfPickUp, c.clientRegion, c.clientCity, c.clientBarangay, c.clientStreetName, c.clientHouseNum, c.clientCompanyName;";

        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)){
          echo "sql error";
        }
        else {
          $result = mysqli_query($conn, $sql);
          $cancelledData = array();
          if (mysqli_num_rows($result) > 0){
            while ($row = mysqli_fetch_assoc($result)) {
              $cancelledData[] = $row;
            }
          }
        }


        $sql = "SELECT r.requestID, c.clientID, r.denrRefCode, r.dateNAGen, r.dateIssuanceAckLet, c.denrGenID, r.specDateOfPickUp, c.clientCompanyName,
        r.contractID, c.clientContactNum, c.clientEmailAddress, c.clientRegion,
        c.clientCity, c.clientBarangay, c.clientStreetName, c.clientHouseNum,t.tsdName, t.tsdDenrID, t.tsdRegion, t.tsdCity,
        t.tsdBarangay, t.tsdStreetName, t.tsdHouseNum
        FROM request r
          LEFT JOIN client c
            ON r.clientID = c.clientID
          LEFT JOIN tsd t
            ON r.tsdID = t.tsdID
        ORDER BY r.specDateOfPickUp, c.clientRegion, c.clientCity, c.clientBarangay, c.clientStreetName, c.clientHouseNum, c.clientCompanyName;";

        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)){
          echo "sql error";
        }
        else {
          $result = mysqli_query($conn, $sql);
          $allData = array();
          if (mysqli_num_rows($result) > 0){
            while ($row = mysqli_fetch_assoc($result)) {
              $allData[] = $row;
            }
          }
        }

        $requestStatusOld;
        if(isset($_GET['requestStatus'])){
          $requestStatusOld = $_GET['requestStatus'];
        }
        ?>

      <header>
        <div class="logo_box">
          <img class="logo_header" src="HeaderImages\ECOTECH-LOGO.png">
        </div>
        <div class="tabs_box">
          <div class="username_container">
            <p><?php echo $_SESSION['name']; ?></p>
          </div>
          <form class="home_form" action="phpCodes/home.php" method="post">
            <button type="submit" name="home_button">Home</button>
          </form>
          <form class="logout_form" action="phpCodes/logout.php" method="post">
            <button type="submit" name="logout_button">Logout</button>
          </form>
        </div>
      </header>
      <main style="margin-top: 60px">
        <div class="Form">
          <div class="employeeRegisterContainer">
            <div class="titleContainer">
              <div class="actionsTitle">
                <p style="padding-top: 0;">Update Request</p>
              </div>
              <hr style="margin-bottom: 20px;">
            </div>
            <form class="get" action="phpCodes/getThisRequest.php" method="post">
              <div class="actionBox">
                <div class="actionDataForm">

                    <p class="inputTitle">Request ID</p>
                    <?php

                    if(isset($_GET['requestID'])){
                      $requestIDSet = $_GET['requestID'];

                      echo '<input class="requestID" type="text" name="requestID" placeholder="Request ID" maxlength="18" id="employeeID" value="'.$_GET['requestID'].'">';
                    }
                    else {
                      echo '<input class="requestID" type="text" name="requestID" placeholder="Request ID" maxlength="18" id="employeeID">';
                    }
                     ?>

                    <div class="placeholder">
                      <?php
                      //Getting url for error checking
                        $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                        if (strpos($fullUrl, "find=dontexist") == true){
                          echo "<p class='error'>This request is not in the database.</p>";
                        } elseif (strpos($fullUrl, "find=notmtach") == true){
                          echo "<p class='error'>*This field is required -></p>";
                        } elseif (strpos($fullUrl, "register=invalidname") == true){
                          echo "<p class='error'>Name must be 'a-z', 'A-Z'!</p>";
                        } elseif (strpos($fullUrl, "register=invalidmobile") == true){
                          echo "<p class='error'>Invalid Contact#!</p>";
                        } elseif (strpos($fullUrl, "update=success") == true){
                          echo "<p class='success'>Registration Successful!</p>";
                        } elseif (strpos($fullUrl, "update=emptyheader") == true){
                          echo "<p class='error'>You did not fill in all the required fields!</p>";
                        } elseif (strpos($fullUrl, "update=credentialsmatch") == true){
                          echo "<p class='error'>These redentials matched other records!</p>";
                        } elseif (strpos($fullUrl, "update=Updated") == true){
                          echo "<p class='success'>Record Updated!</p>";
                        } elseif (strpos($fullUrl, "update=Deleted") == true){
                          echo "<p class='success'>Record Deleted!</p>";
                        } elseif (strpos($fullUrl, "find=empty") == true){
                          echo "<p class='error'>This field is required</p>";
                        } if (strpos($fullUrl, "signup=empty") == true){
                          echo "<p class='error'>You did not fill in all fields!</p>";
                        } elseif (strpos($fullUrl, "signup=invalidname") == true){
                          echo "<p class='error'>Name must be 'a-z', 'A-Z'!</p>";
                        } elseif (strpos($fullUrl, "signup=invalidemail") == true){
                          echo "<p class='error'>Invalid email!</p>";
                        } elseif (strpos($fullUrl, "signup=passwordnotmatched") == true){
                          echo "<p class='error'>Password did not match!</p>";
                        } elseif (strpos($fullUrl, "signup=invalidmobile") == true){
                          echo "<p class='error'>Invalid Mobile#!</p>";
                        } elseif (strpos($fullUrl, "signup=success") == true){
                          echo "<p class='success'>Success!</p>";
                        } elseif (strpos($fullUrl, "update=cannotBeDeleted") == true){
                          echo "<p class='error'>Record cannot be deleted!</p>";
                        }

                       ?>
                    </div>
                    <div class="submit">
                      <button type="submit" name="button" style="padding-left: 30px; padding-right: 30px;">Find</button>
                    </div>
                </div>

              </div>
            </form>

            <form class="employeeEditForm" action="phpCodes\requestEdit.inc.php" method="post">
              <input type="hidden" name="requestIDSet" value="<?php echo $requestIDSet; ?>">
              <input type="hidden" name="requestStatusOld" value="<?php echo $requestStatusOld; ?>">
              <div class="employeeEditBox">
                <div class="employeeDataForm">
                    <p class="inputTitle">Denr ID</p>
                    <?php
                      if(isset($_GET['denrGenID'])){
                        echo '<input class="denrGenID" type="text" name="denrGenID" placeholder="DENR ID" maxlength="20" value="'.$_GET['denrGenID'].'" readonly>';
                      }
                      else {
                        echo '<input class="denrGenID" type="text" name="denrGenID" placeholder="DENR ID" maxlength="20">';
                      }
                     ?>
                     <p class="inputTitle">Company Name</p>
                     <?php
                       if(isset($_GET['clientCompanyName'])){
                         echo '<input class="clientCompanyName" type="text" name="clientCompanyName" placeholder="Company Name" maxlength="40" value="'.$_GET['clientCompanyName'].'" readonly>';
                       }
                       else {
                         echo '<input class="clientCompanyName" type="text" name="clientCompanyName" placeholder="Company Name" maxlength="40">';
                       }
                      ?>
                    <p class="inputTitle">Spec Date</p>

                    <?php
                      if(isset($_GET['specDateOfPickUp'])){
                        echo '<input class="specDateOfPickUp" type="date" name="specDateOfPickUp" placeholder="(09xxxxxxxxx)" value="'.$_GET['specDateOfPickUp'].'">';
                      }
                      else {
                        echo '<input class="specDateOfPickUp" type="date" name="specDateOfPickUp" placeholder="(09xxxxxxxxx)">';
                      }
                     ?>
                     <p class="inputTitle">Denr Ref Code</p>
                     <?php
                       if(isset($_GET['denrRefCode'])){
                         echo '<input class="denrRefCode" type="text" name="denrRefCode" placeholder="DENR Reference Code" maxlength="20" value="'.$_GET['denrRefCode'].'">';
                       }
                       else {
                         echo '<input class="denrRefCode" type="text" name="denrRefCode" placeholder="DENR Reference Code" maxlength="20">';
                       }
                      ?>
                      <p class="inputTitle">N. of Acceptance</p>
                      <?php
                        if(isset($_GET['dateNAGen'])){
                          echo '<input class="dateNAGen" type="date" name="dateNAGen" value="'.$_GET['dateNAGen'].'">';
                        }
                        else {
                          echo '<input class="dateNAGen" type="date" name="dateNAGen">';
                        }
                       ?>
                     <p class="inputTitle">Letter Of Acknowledgement</p>
                     <?php
                       if(isset($_GET['dateIssuanceAckLet'])){
                         echo '<input class="dateIssuanceAckLet" type="date" name="dateIssuanceAckLet" value="'.$_GET['dateIssuanceAckLet'].'">';
                       }
                       else {
                         echo '<input class="dateIssuanceAckLet" type="date" name="dateIssuanceAckLet">';
                       }
                      ?>

                    <p class="inputTitle">TSD ID</p>
                    <?php
                      if(isset($_GET['tsdDenrID'])){
                        echo '<input class="email" type="text" name="tsdDenrID" placeholder="TSD ID" maxlength="30" value="'.$_GET['tsdDenrID'].'" style="background-color: 	rgb(232,232,232);"readonly>';
                      }
                      else {
                        echo '<input class="email" type="text" name="tsdDenrID" placeholder="TSD ID" maxlength="30">';
                      }
                     ?>
                     <p class="inputTitle">TSD Name</p>
                     <?php
                       if(isset($_GET['tsdName'])){
                         echo '<input class="tsdName" type="text" name="tsdName" placeholder="TSD Name" maxlength="30" value="'.$_GET['tsdName'].'" style="background-color: 	rgb(232,232,232);"readonly>';
                       }
                       else {
                         echo '<input class="tsdName" type="text" name="tsdName" placeholder="TSD Name" maxlength="30">';
                       }
                      ?>

                       <p class="inputTitle">Contract ID</p>
                       <?php
                         if(isset($_GET['contractID'])){
                           if($_GET['contractID'] == "") {
                             $contractshit = "N/A";
                           }
                           else {
                             $contractshit = $_GET['contractID'];
                           }
                           echo '<input class="contractID" type="text" name="contractID" placeholder="Contract ID" maxlength="18" value="'.$contractshit.'" style="background-color: 	rgb(232,232,232);"readonly>';
                         }
                         else {
                           echo '<input class="contractID" type="text" name="contractID" placeholder="Contract ID" maxlength="18" value="N/A" style="background-color: 	rgb(232,232,232);"readonly>';
                         }
                        ?>
                       <p class="inputTitle">Request Status</p>
                       <div class="radioButtonsContainer">
                         <div class="radio">
                           <?php
                           if(isset($_GET['requestStatus'])){
                             if($_GET['requestStatus'] == 'U'){
                               echo '<input type="radio" name="action" value="U" id="action" checked>';
                               echo '<label for="Driver">Unchanged</label>';
                               echo '</div>';
                               echo '<div class="radio">';
                               echo '<input type="radio" name="action" value="C" id="action">';
                             }
                             else if ($_GET['requestStatus'] == 'C'){
                               echo '<input type="radio" name="action" value="U" id="action">';
                               echo '<label for="Driver">Unchanged</label>';
                               echo '</div>';
                               echo '<div class="radio">';
                               echo '<input type="radio" name="action" value="C" id="action" checked>';
                             }
                           }
                           else {
                             echo '<input type="radio" name="action" value="U" id="action" checked>';
                             echo '<label for="Driver">Unchanged</label>';
                             echo '</div>';
                             echo '<div class="radio">';
                             echo '<input type="radio" name="action" value="C" id="action">';
                           }

                            ?>
                           <label for="Attendant">Cancelled</label>
                         </div>
                       </div>
                  </div>

                </div>
                <div class="employeeSubtypeForm" id="employeeSubtypeForm">
                </div>

                <div class="radioButtonsContainer" style="margin-top: 0px; margin-bottom: 14px;">
                  <div class="placeholder" >
                    <p class="inputTitle">Actions Available</p>
                  </div>
                  <div class="radio">
                    <input type="radio" name="actionBottom" value="Update" id="actionTop" onclick="getThisEmployee()">
                    <label for="Create">Update</label>
                  </div>

                  <div class="radio">
                    <input type="radio" name="actionBottom" value="Delete" id="actionTop" onclick="getThisEmployee()">
                    <label for="Update">Delete</label>
                  </div>
                </div>
              <div class="confirmationContainer">
                <button type="submit" id="confirmButton">Confirm</button>
                <a href="http://localhost:4000/HazardousWasteProj/Forms/RequestMainInterfaceForm/RequestMainInterfaceForm.php?"
                >Go back</a>
              </div>
              </form>
            </div>
          </script>

        </div>
        <div class="Report">
          <div class="titleContainer">
            <div class="actionsTitle">
              <p style="padding-top: 0;">Request Data</p>
            </div>
            <hr style="margin-bottom: 20px;">
          </div>
          <div class="reportBox">
            <div class="tableHeader" id="tableHeader">

            </div>
            <div class="tableContent" id="tableContent">

            </div>
          </div>
          <div class="radioButtonsContainer">
            <div class="radio">
              <input type="radio" name="choiceReport" value="Unverified" id="choiceReport" onclick="choiceReportChange()" checked>
              <label for="Create">Unverified</label>
            </div>

            <div class="radio">
              <input type="radio" name=choiceReport value="Cancelled" id="choiceReport" onclick="choiceReportChange()">
              <label for="Update">Cancelled</label>
            </div>

            <div class="radio">
              <input type="radio" name=choiceReport value= "All" id="choiceReport" onclick="choiceReportChange()">
              <label for="Update">All</label>
            </div>
          </div>
        </div>
      <script type="text/javascript">
        let jsArray;
        let choiceType;
        let tableHeader = document.getElementById('tableHeader');
        let tableContent = document.getElementById('tableContent');

        function displayAllData(jsArray, choiceType){
          tableContent.className = 'tableContent';
          tableHeader.className = 'tableHeader';
          createAllHeader();
          for(i = 0; i < jsArray.length; i++){

            let requestID = document.createElement('p');
            let clientID = document.createElement('p');
            let specDateOfPickUp = document.createElement('p');
            let denrRefCode = document.createElement('p');
            let dateNAgen = document.createElement('p');
            let dateIssuanceAckLet = document.createElement('p');
            let denrGenID = document.createElement('p');
            let clientCompanyName = document.createElement('p');
            let clientContactNum = document.createElement('p');
            let clientEmail = document.createElement('p');
            let clientRegion = document.createElement('p');
            let clientCity = document.createElement('p');
            let clientBarangay = document.createElement('p');
            let clientStreetName = document.createElement('p');
            let clientHouseNum = document.createElement('p');
            let tsdDenrID = document.createElement('p');
            let tsdName = document.createElement('p');
            let tsdRegion = document.createElement('p');
            let tsdCity = document.createElement('p');
            let tsdStreetName = document.createElement('p');
            let tsdBarangay = document.createElement('p');
            let tsdHouseNum = document.createElement('p');
            let contractID = document.createElement('p');

            requestID.innerText = jsArray[i]['requestID'];
            clientID.innerText = jsArray[i]['clientID'];
            specDateOfPickUp.innerText = jsArray[i]['specDateOfPickUp'];
            denrRefCode.innerText = jsArray[i]['denrRefCode'];
            dateNAgen.innerText = jsArray[i]['dateNAGen'];
            dateIssuanceAckLet.innerText = jsArray[i]['dateIssuanceAckLet'];
            denrGenID.innerText = jsArray[i]['denrGenID'];
            clientCompanyName.innerText = jsArray[i]['clientCompanyName'];
            clientContactNum.innerText = jsArray[i]['clientContactNum'];
            clientEmail.innerText = jsArray[i]['clientEmail'];
            clientRegion.innerText = jsArray[i]['clientRegion'];
            clientCity.innerText = jsArray[i]['clientCity'];
            clientStreetName.innerText = jsArray[i]['clientStreetName'];
            clientBarangay.innerText = jsArray[i]['clientBarangay'];
            clientHouseNum.innerText = jsArray[i]['clientHouseNum'];
            tsdDenrID.innerText = jsArray[i]['tsdDenrID'];
            tsdName.innerText = jsArray[i]['tsdName'];
            tsdRegion.innerText = jsArray[i]['tsdRegion'];
            tsdCity.innerText = jsArray[i]['tsdCity'];
            tsdStreetName.innerText = jsArray[i]['tsdStreetName'];
            tsdBarangay.innerText = jsArray[i]['tsdBarangay'];
            tsdHouseNum.innerText = jsArray[i]['tsdHouseNum'];
            if(jsArray[i]['contractID'] != undefined){
              contractID.innerText = jsArray[i]['contractID'];
            }
            else {
              contractID.innerText = 'N/A';
            }



            tableContent.append(requestID);
            tableContent.append(clientID);
            tableContent.append(specDateOfPickUp);
            tableContent.append(denrRefCode);
            tableContent.append(dateNAgen);
            tableContent.append(dateIssuanceAckLet);
            tableContent.append(denrGenID);
            tableContent.append(clientCompanyName);
            tableContent.append(clientContactNum);
            tableContent.append(clientEmail);
            tableContent.append(clientRegion);
            tableContent.append(clientCity);
            tableContent.append(clientBarangay);
            tableContent.append(clientStreetName);
            tableContent.append(clientHouseNum);
            tableContent.append(tsdDenrID);
            tableContent.append(tsdName);
            tableContent.append(tsdRegion);
            tableContent.append(tsdCity);
            tableContent.append(tsdBarangay);
            tableContent.append(tsdStreetName);
            tableContent.append(tsdHouseNum);

            tableContent.append(contractID);


          }
        }

        function createAllHeader(){
          tableHeader.innerHTML = '';
          let arrayOfHeaderTitle = ['Request ID','Client ID', 'Spec Date', 'DENR Ref Code',
           'N. of Acceptance','Ack Letter', 'DENR ID', 'Company',
           'Contact#', 'Email', 'Region', 'City', 'Barangay', 'Street', 'HouseNum', 'TSD ID','TSD Name', 'TSD Region',
           'TSD City', 'TSD Barangay', 'TSD Street Name', 'TSD House #', 'Contract ID'];
          writeHeader(arrayOfHeaderTitle);
        }

        function writeHeader(arrayOfHeaderTitle){
          for(i = 0; i < arrayOfHeaderTitle.length; i++){
            let header = document.createElement('div');
            header.innerText = arrayOfHeaderTitle[i];
            tableHeader.append(header);
          }
        }

        function choiceReportChange() {
            tableContent.innerHTML = '';
            tableHeader.innerHTML = '';
            choiceType = document.querySelector('input[name="choiceReport"]:checked').value;
            if (choiceType == 'All'){
              jsArray = <?php echo json_encode($allData); ?>;
            }
            else if (choiceType == 'Cancelled') {
              jsArray = <?php echo json_encode($cancelledData); ?>;
            }
            else if (choiceType == 'Unverified'){
              jsArray = <?php echo json_encode($unverifiedData); ?>;
            }
            displayAllData(jsArray, choiceType);
        }

        choiceReportChange();

        // update part

        function getThisEmployee(){
            let employeeID = document.getElementById('employeeID').value;
        }

      </script>

  </main>

  </body>
</html>
