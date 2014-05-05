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
        <div class="container">
            
        <div class="col-md-10">	
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <?php
                    echo "<h3>" . $_GET['action'] . "</h3>";
                    ?>
                </div>
                <div class="panel-body">
                
                <div class="col-md-4">          
                    <div class="pie-chart">
                      <?php
                      $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
                      // Check connection
                      if (mysqli_connect_errno()) {
                          echo "Failed to connect to MySQL: " . mysqli_connect_error();
                      }

                      $result = mysqli_query($con, "SELECT * FROM Users");

                      //PIE CHART
                     echo "<div class=\"well well-sm\" ><font size=\"4\"> <center>Available: XX GB<br> Used: XX GB<br> Free: XX GB</center></font></div>";
                     echo "<center><button type=\"button\" class=\"btn btn-default\">View Storage</button></center>";
                     echo "<center><button type=\"button\" class=\"btn btn-default\">Request Additional Storage</button></center>";

                     mysqli_close($con);
                     ?>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="data-managers">
                      
                        <h3>Data Managers</h3>
   
                        <?php
                        $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
                        // Check connection
                        if (mysqli_connect_errno()) {
                          echo "Failed to connect to MySQL: " . mysqli_connect_error();
                        }
                         
                        
                         $dataManagers = mysqli_query($con, "SELECT * FROM User_Projects WHERE projID = " . lookupProjID($_GET['action']) . " AND permissions > 3" );

                        echo "<table class=\"table\">
                        <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        </tr>";

                        while ($row = mysqli_fetch_array($dataManagers)) {
                            echo "<tr>";
                            echo "<td>" . $row['userID'] . "</td>";
                            echo "<td>" . lookupUserName($row['userID']) . "</td>";
                            echo "</tr>";
                        }

                        echo "</table>";
                        echo "<br>";
                        echo "<center><button type=\"button\" class=\"btn btn-default\">Add Data Manager</button></center></center>";
                        echo "<center><button type=\"button\" class=\"btn btn-default\">Remove Data Manager</button></center>";
                        
                        mysqli_close($con);
                        ?>
                    </div>
                </div>
                    
              
                <div class="col-md-4">
                    <div class="collaborators">
                    <h3>Collaborators</h3>

                      <?php
                      $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
                      // Check connection
                      if (mysqli_connect_errno()) {
                          echo "Failed to connect to MySQL: " . mysqli_connect_error();
                      }

                      $collaborators = mysqli_query($con, "SELECT * FROM User_Projects WHERE projID = " . lookupProjID($_GET['action']) . " AND permissions <= 3" );

                      echo "<table class=\"table\">
                      <tr>
                      <th>Name</th>
                      <th><center>Read</center></th>
                      <th><center>Write</center></th>
                      </tr>";

                     while ($row = mysqli_fetch_array($collaborators)) {
                         echo "<tr>";
                         echo "<td>" . lookupUserName($row['userID']) . "</td>";
                         echo "<td> <center><input type=\"checkbox\" class=\"checkbox\"/></center> </td>";
                         echo "<td> <center><input type=\"checkbox\" class=\"checkbox\"/></center> </td>";
                         echo "</tr>";
                     }

                     echo "</table>";
                     echo "<br>";
                     echo "<center><button type=\"button\" class=\"btn btn-default\">Add Collaborator</button></center>";
                     echo "<center><button type=\"button\" name=\"testing\" class=\"btn btn-default\">Remove Collaborator</button></center>";                    
                     echo "<center><button type=\"button\" class=\"btn btn-default\">Promote to Data Manager</button></center>";
                    // if(isset($_POST['testing'])) {
                     //    echo "<center><button type=\"button\" class=\"btn btn-default\">Promote to Data Manager</button></center>";
                    // }
                     
                     
                     mysqli_close($con);
                     ?>
                    </div>
                </div>
                
            </div>
            </div>
        </div>
    
   </div>
        




        <br><br><br>
        <?php displayFooter(); ?>

        <!-- Include all compiled plugins (below), or include individual files as needed -->

        <script src="js/jquery-1.11.0.min.js"></script>
        <script src="js/bootstrap.min.js"></script>

    </body>







</html>
