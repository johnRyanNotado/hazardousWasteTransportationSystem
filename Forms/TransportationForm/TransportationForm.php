<?php
  session_start();
  if(!isset($_SESSION['name'])){
    header("Location: http://localhost:4000/HazardousWasteProj/Forms/AccessForm/AccountAccessForm.php");
  }
 ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Transportation Form</title>
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Footer.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/General.css">
    <link rel="stylesheet" type="text/css" href="../../GeneralFile/General/Header.css">
    <link rel="stylesheet" type="text/css" href="TransportationForm.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href=
    "https://secureservercdn.net/198.71.233.110/s0i.8f2.myftpupload.com/wp-content/uploads/2020/03/cropped-5x3-1-32x32.png">

    <style media="screen">

    .inputTitle {
      font-size: 14px;
    }
    </style>

  </head>
  <body>
    <?php
      include "phpCodes/dbh.inc.php";


      if(isset($_GET['requestID'])){
        $requestID = $_GET['requestID'];
        $requestIDSet = $requestID;
        $sql = "SELECT * FROM wasteAssignment WHERE requestID = ?;";
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
      }


     ?>
    <header>
      <div class="logo_box">
        <img class="logo_header" src="HeaderImages\ECOTECH-LOGO.png">
      </div>
      <div class="tabs_box">
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
      </div>
    </header>
    <main>
      <form class="requestForm" action="phpCodes\transportation.inc.php" method="post">
        <input type="hidden" name="requestIDSet" value="<?php echo $requestIDSet; ?>">
        <div class="titleContainer" style="display: block; margin-top:80px; margin-bottom: 20px;">
          <div class="actionsTitle" style='display: flex; justify-content: center; margin-left: 680px;'>
            <p style="padding-top: 0;">Transportation Request</p>
          </div>

        </div>
      <div class="displayContainer">

                <div class="wasteAssignmentContainer">

                  <div class="requestDataContainer">
                    <div class="requestFormDateBox">
                      <div class="datePart">
                        <p class="inputTitle">Request ID</p>
                        <?php
                          if(isset($_GET['requestID'])){
                            echo '<input class="requestID" type="text" name="requestID" placeholder="Request ID" maxlength="18" value="'.$_GET['requestID'].'" readonly>';
                          }
                          else {
                            echo '<input class="requestID" type="text" name="requestID" placeholder="Request ID" maxlength="18">';
                          }
                         ?>
                      </div>
                    </div>
                    <div style="display: flex; flex-direction: row; column-gap: 15px;">
                      <button type="submit" name="findButton" value="find" style="margin-bottom: 20px;">Find</button>
                      <button type="submit" name="findButton" value="cancel" style="margin-bottom: 20px;">Cancel</button>
                    </div>

                    <div class="requestFormDateBox">
                      <div class="datePart">
                        <p class="inputTitle">Date</p>
                        <?php
                          if(isset($_GET['dateOfActualPickUp'])){
                            echo '<input class="dateOfActualPickUp" type="date" name="dateOfActualPickUp" value="'.$_GET['dateOfActualPickUp'].'" readonly>';
                          }
                          else {
                            echo '<input class="dateOfActualPickUp" type="date" name="dateOfActualPickUp">';
                          }
                          ?>
                      </div>

                    </div>
                    <div class="confirmationContainer">
                      <button type="submit" name="findButton" value="confirm"style="background-color: lightgreen; width: 200px;">Confirm</button>
                      <a href="http://localhost:4000/HazardousWasteProj/Forms/TransportationMainInterfaceForm/TransportationMainInterfaceForm.php?"
                      >Go back</a>

                      <div style="display: flex; flex-direction: row; white-space: nowrap; margin: 20px;">
                        <?php
                          $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                          if (strpos($fullUrl, "register=empty") == true){
                            echo "<p class='error'>You did not fill in all the required fields!</p>";
                          } elseif (strpos($fullUrl, "transport=alreadyExist") == true){
                            echo "<p class='error'>Already has a transportation record</p>";
                          } elseif (strpos($fullUrl, "register=invalidname") == true){
                            echo "<p class='error'>TSD Name must be 'a-z', 'A-Z'!</p>";
                          } elseif (strpos($fullUrl, "request=dontexist") == true){
                            echo "<p class='error'>Not found</p>";
                          }
                         ?>
                      </div>
                    </div>

                      <div class="wasteDataBox">
                        <div class="wasteDataHeader">
                          <div class="wasteIDHeader">
                            Waste ID
                          </div>
                          <div class="wasteAmountHeader">
                            Amount
                          </div>
                        </div>

                        <div class="wasteDataContent" id="wasteDataContent">
                        </div>
                      </div>
                  </div>


                </div>


        <div class="containerForm">

          <div class="identificationFormContainer">

              <div class="requestFormBox">
                <div class="identificationFlex">
                  <div class="identificationPart">
                    <p class="inputTitle">Company</p>
                    <?php
                      if(isset($_GET['clientCompanyName'])){
                        echo '<input class="clientCompanyName" type="text" name="clientCompanyName" placeholder="Company Name" maxlength="30" value="'.$_GET['clientCompanyName'].'" readonly>';
                      }
                      else {
                        echo '<input class="clientCompanyName" type="text" name="clientCompanyName" placeholder="Company Name" maxlength="30">';
                      }
                      ?>
                    <p class="inputTitle">DENR ID</p>
                    <?php
                      if(isset($_GET['denrGenID'])){
                        echo '<input class="denrGenID" type="text" name="denrGenID" placeholder="First Name" maxlength="20" value="'.$_GET['denrGenID'].'" readonly>';
                      }
                      else {
                        echo '<input class="denrGenID" type="text" name="denrGenID" placeholder="First Name" maxlength="20">';
                      }
                     ?>
                    <p class="inputTitle">Ref Code</p>
                    <?php
                      if(isset($_GET['denrRefCode'])){
                        echo '<input class="denrRefCode" type="text" name="denrRefCode" placeholder="DENR Reference Code" maxlength="15" value="'.$_GET['denrRefCode'].'" readonly>';
                      }
                      else {
                        echo '<input class="denrRefCode" type="text" name="denrRefCode" placeholder="DENR Reference Code" maxlength="15">';
                      }
                     ?>
                     <p class="inputTitle">Spec Date</p>

                     <?php
                       if(isset($_GET['specDateOfPickUp'])){
                         echo '<input class="specDateOfPickUp" type="date" name="specDateOfPickUp" placeholder="(09xxxxxxxxx)" value="'.$_GET['specDateOfPickUp'].'"readonly>';
                       }
                       else {
                         echo '<input class="specDateOfPickUp" type="date" name="specDateOfPickUp" placeholder="(09xxxxxxxxx)">';
                       }
                      ?>
                      <p class="inputTitle">TSD DENR ID</p>
                      <?php
                        if(isset($_GET['tsdDenrID'])){
                          echo '<input class="tsdDenrID" type="text" name="tsdDenrID" placeholder="TSD ID" maxlength="20" value="'.$_GET['tsdDenrID'].'"readonly>';
                        }
                        else {
                          echo '<input class="tsdDenrID" type="text" name="tsdDenrID" placeholder="TSD ID" maxlength="20">';
                        }
                       ?>
                      <p class="inputTitle">TSD Name</p>
                      <?php
                        if(isset($_GET['tsdName'])){
                          echo '<input class="tsdName" type="text" name="tsdName" placeholder="TSD FACILITY Name" maxlength="40" value="'.$_GET['tsdName'].'"readonly>';
                        }
                        else {
                          echo '<input class="tsdName" type="text" name="tsdName" placeholder="TSD FACILITY Name" maxlength="40">';
                        }
                       ?>

                  </div>
                    <div class="identificationPart">

                       <p class="inputTitle">Region</p>
                         <?php
                         $regions = ['NCR', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII', 'XIII', 'CAR', 'MIMAROPA'];
                           if(isset($_GET['tsdRegion'])){
                            echo '<select name="clientRegion" placeholder="Region" readonly>';
                             foreach($regions as $region){
                               if($region == $_GET['tsdRegion']){
                                 echo "<option value=$region selected>$region</option>";
                               }
                               else {
                                 echo "<option value=$region>$region</option>";
                               }
                             }
                           }
                           else {
                             echo '<select name="clientRegion" placeholder="Region">';
                             foreach($regions as $region){
                                 echo "<option value=$region>$region</option>";
                             }
                           }
                          ?>
                       </select>
                       <p class="inputTitle">City</p>
                       <?php
                         if(isset($_GET['clientCity'])){
                           echo '<input class="clientCity" type="text" name="clientCity" placeholder="City" maxlength="30" value="'.$_GET['clientCity'].'" readonly>';
                         }
                         else {
                           echo '<input class="clientCity" type="text" name="clientCity" placeholder="City" maxlength="30">';
                         }
                        ?>
                       <p class="inputTitle">Barangay</p>
                       <?php
                         if(isset($_GET['clientBarangay'])){
                           echo '<input class="clientBarangay" type="text" name="clientBarangay" placeholder="Barangay" maxlength="30" value="'.$_GET['clientBarangay'].'" readonly>';
                         }
                         else {
                           echo '<input class="clientBarangay" type="text" name="clientBarangay" placeholder="Barangay" maxlength="30">';
                         }
                        ?>
                       <p class="inputTitle">Street Name</p>
                       <?php
                         if(isset($_GET['clientStreetName'])){
                           echo '<input class="clientStreetName" type="text" name="clientStreetName" placeholder="Street Name" maxlength="30" value="'.$_GET['clientStreetName'].'" readonly>';
                         }
                         else {
                           echo '<input class="clientStreetName" type="text" name="clientStreetName" placeholder="Street Name" maxlength="30">';
                         }
                        ?>
                       <p class="inputTitle">House #</p>
                       <?php
                         if(isset($_GET['clientHouseNum'])){
                           echo '<input class="clientHouseNum" type="text" name="clientHouseNum" placeholder="House Number" maxlength="5" value="'.$_GET['clientHouseNum'].'" readonly>';
                         }
                         else {
                           echo '<input class="clientHouseNum" type="text" name="clientHouseNum" placeholder="House Number" maxlength="5">';
                         }
                        ?>

                    </div>
                      <div class="identificationPart">

                         <p class="inputTitle"> TSD Region</p>

                           <?php
                           $regions = ['NCR', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII', 'XIII', 'CAR', 'MIMAROPA'];
                             if(isset($_GET['tsdRegion'])){
                               echo '<select name="tsdRegion" placeholder="Region" readonly>';
                               foreach($regions as $region){
                                 if($region == $_GET['tsdRegion']){
                                   echo "<option value=$region selected>$region</option>";
                                 }
                                 else {
                                   echo "<option value=$region>$region</option>";
                                 }
                               }
                             }
                             else {
                               echo '<select name="tsdRegion" placeholder="Region">';
                               foreach($regions as $region){
                                   echo "<option value=$region>$region</option>";
                               }
                             }
                            ?>
                         </select>
                         <p class="inputTitle">TSD City</p>
                         <?php
                           if(isset($_GET['tsdCity'])){
                             echo '<input class="tsdCity" type="text" name="tsdCity" placeholder="City" maxlength="30" value="'.$_GET['tsdCity'].'"readonly>';
                           }
                           else {
                             echo '<input class="tsdCity" type="text" name="tsdCity" placeholder="City" maxlength="30">';
                           }
                          ?>
                         <p class="inputTitle">TSD Barangay</p>
                         <?php
                           if(isset($_GET['tsdBarangay'])){
                             echo '<input class="tsdBarangay" type="text" name="tsdBarangay" placeholder="Barangay" maxlength="30" value="'.$_GET['tsdBarangay'].'"readonly>';
                           }
                           else {
                             echo '<input class="tsdBarangay" type="text" name="tsdBarangay" placeholder="Barangay" maxlength="30">';
                           }
                          ?>
                         <p class="inputTitle">TSD Street Name</p>
                         <?php
                           if(isset($_GET['tsdStreetName'])){
                             echo '<input class="tsdStreetName" type="text" name="tsdStreetName" placeholder="Street Name" maxlength="30" value="'.$_GET['tsdStreetName'].'"readonly>';
                           }
                           else {
                             echo '<input class="tsdStreetName" type="text" name="tsdStreetName" placeholder="Street Name" maxlength="30">';
                           }
                          ?>
                         <p class="inputTitle">TSD House #</p>
                         <?php
                           if(isset($_GET['tsdHouseNum'])){
                             echo '<input class="tsdHouseNum" type="text" name="tsdHouseNum" placeholder="House Number" maxlength="5" value="'.$_GET['tsdHouseNum'].'"readonly>';
                           }
                           else {
                             echo '<input class="tsdHouseNum" type="text" name="tsdHouseNum" placeholder="House Number" maxlength="5">';
                           }
                          ?>

                      </div>
                    </div>

              </div>


          </div>
        </div>


      </div>


      </form>
      <script type="text/javascript">
        function displayWasteAssignment(){
          let jsArray = <?php echo json_encode($wasteAssignmentData); ?>;
          for(i = 0; i < jsArray.length; i++){
            let wasteID = document.createElement('div');
            wasteID.innerText = jsArray[i]['wasteID'];
            let wasteAmount = document.createElement('div');
            wasteAmount.innerText = jsArray[i]['amount'];
            wasteDataContent.append(wasteID);
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




    </script>


  </body>
</html>
