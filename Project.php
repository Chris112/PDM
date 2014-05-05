<?php
session_start();
if (empty($_SESSION[$access])) {
    header("location:login.php");
    //dieHard3();
}
include 'dbFunctions.php';
include 'commonElements.php';
?>



<!DOCTYPE html>

<html lang="en">

    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <title>Specific Project page</title>



        <meta charset="utf-8"> 

        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <meta name="viewport" content="width=device-width, initial-scale=1"> 

        <meta name="description" content="">

        <meta name="author" content="">

        <link rel="shortcut icon" href="assets/ico/icon.ico">



        <!-- Bootstrap core CSS -->

        <?php importCoreCSS() ?>

    </head>

    <body>

        <?php displayHeader() ?>

        <?php displayNavbar() ?>
        
        
        <!-- Main Panel area -->
        <div class="col-md-9">	
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3>Project Title</h3>
                </div>
                
                <div class="col-md-3">          
                    <div class="pie-chart">
                      <?php
                      $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
                      // Check connection
                      if (mysqli_connect_errno()) {
                          echo "Failed to connect to MySQL: " . mysqli_connect_error();
                      }

                      $result = mysqli_query($con, "SELECT * FROM Users");

                      //PIE CHART

                     mysqli_close($con);
                     ?>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="data-managers">
                      
                        <h3>Data Managers</h3>
   
                        <?php
                        $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
                        // Check connection
                        if (mysqli_connect_errno()) {
                          echo "Failed to connect to MySQL: " . mysqli_connect_error();
                        }

                         $result = mysqli_query($con, "SELECT * FROM Users");

                        echo "<table class=\"table\">
                        <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        </tr>";

                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['userID'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "</tr>";
                        }

                        echo "</table>";

                        mysqli_close($con);
                        ?>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="collaborators">
                    <h3>Collaborators</h3>

                      <?php
                      $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
                      // Check connection
                      if (mysqli_connect_errno()) {
                          echo "Failed to connect to MySQL: " . mysqli_connect_error();
                      }

                      $result = mysqli_query($con, "SELECT * FROM Users");

                      echo "<table class=\"table\">
                      <tr>
                      <th>Name</th>
                      <th><center>Read</center></th>
                      <th><center>Write</center></th>
                      </tr>";

                     while ($row = mysqli_fetch_array($result)) {
                         echo "<tr>";
<<<<<<< HEAD
                         echo "<td>" . lookupUserName($row['userID']) . "</td>";
                         if ($row['permissions'] = 3) {
                            echo "<td> <center><input type=\"checkbox\" class=\"read\" checked=\"checked\"/></center> </td>";
                            echo "<td> <center><input type=\"checkbox\" class=\"write\" checked=\"checked\"/></center> </td>";                               
                         }
                         else if ($row['permissions'] = 2) {
                            echo "<td> <center><input type=\"checkbox\" class=\"read\"/></center> </td>";
                            echo "<td> <center><input type=\"checkbox\" class=\"write\" checked=\"checked\"/></center> </td>";
                         }
                          else if ($row['permissions'] = 1) {
                            echo "<td> <center><input type=\"checkbox\" class=\"read\" checked=\"checked\"/></center> </td>";
                            echo "<td> <center><input type=\"checkbox\" class=\"write\"/></center> </td>";
                         }
                         else {
                            echo "<td> <center><input type=\"checkbox\" class=\"read\"/></center> </td>";
                            echo "<td> <center><input type=\"checkbox\" class=\"write\"/></center> </td>";
                         }
                         echo "</tr>"; 
                         
=======
                         echo "<td>" . $row['name'] . "</td>";
                         echo "<td> <center><input type=\"checkbox\" class=\"checkbox\"/></center> </td>";
                         echo "<td> <center><input type=\"checkbox\" class=\"checkbox\"/></center> </td>";
                         echo "</tr>";
>>>>>>> parent of 4d3a9be... backup before installing extended bootstrap
                     }

                     echo "</table>";
                     echo "<br><br>";
                     echo "<button type=\"submit\" class=\"btn btn-default\">Submit</button>";
                     echo "<button type=\"submit\" class=\"btn btn-default\">Submit</button>";
                     
                     mysqli_close($con);
                     ?>
                    </div>
                </div>
                
            </div>
   
        </div>





        <?php displayFooter(); ?>

        <!-- Include all compiled plugins (below), or include individual files as needed -->

        <script src="js/jquery-1.11.0.min.js"></script>
        <script src="js/bootstrap.min.js"></script>

    </body>







</html>



