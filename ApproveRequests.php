<?php
session_start();

if (empty($_SESSION[$access])) {
    header("location:login.php");
    die();
} 
include 'dbFunctions.php';
include 'commonElements.php';
?>







<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Process Requests</title>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="assets/icons/icon.ico">

        <!-- Bootstrap core CSS -->
        <?php importCoreCSS() ?>
        
    </head>
    <body>
        <?php displayHeader();?>
        
         <!-- Nav bar -->
        <div class="col-md-2">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <ul class="nav nav-pills nav-stacked">
                        <?php
                        // Only display Faculty Selection link if currUser is admin or approver
                        if ($_SESSION['currUser']['site_level'] > 1) {
                            echo "<li><a href=\"FacultySelection.php\">Faculty Selection</a></li>";
                        }
                        ?>
                        <li><a href="ProjectSelection.php">Project Selection</a></li>
                        <li class="active"><a href="ApproveRequests.php">Approve Requests</a></li>
                        <li><a href="PendingRequests.php">Pending Requests</a></li>
                        <li><a href="StorageRequests.php">Storage Request</a></li>
                        <br><br>
                        <li><a href="logout.php">Log out</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Nav bar end -->
        
        
        
	
	<?php
        
        $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
        // Check connection
        if (mysqli_connect_errno()) {
           echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

	$projects = mysqli_query($con, "SELECT * FROM Projects");

	while ($pro = mysqli_fetch_array($projects)) {


	          echo "<div class=\"col-md-9\">	
                        <div class=\"panel panel-primary\">
                	<div class=\"panel-heading\">
                   	<h3 class=\"panel-title\"> $pro[0] </h3>
                	</div>
                	<div class=\"panel-body\">  
                        <table class=\"table\">
        	        <tr>
                	<th>User ID</th>
                    	<th>Name</th>
			<th>Email</th>
			<th>Password</th>
                    	</tr>";
                  
                            $result = mysqli_query($con, "SELECT * FROM Requests r WHERE r.projID = 1");

	                    while ($row = mysqli_fetch_array($result)) {
        	                echo "<tr>";
                	        echo "<td>" . $row['userID'] . "</td>";
	                        echo "<td>" . $row['request_type'] . "</td>";
        	                echo "<td>" . $row['status'] . "</td>";
                	        echo "<td>" . $row['increase_amount'] . "</td>";
				echo "<td>" . $row['date_opened'] . "</td>";
				echo "<td>" . $row['date_closed'] . "</td>";
	                        echo "</tr>";
        	            }

	                echo "</table>
                              </div>
                              </div>
                              <br><br>
                              </div>";

	}
        
        mysqli_close($con);
        ?>
        
        <!-- Footer -->
        <div id="footer">
            <div class="container" align="center">
                <p class="text-muted">Copywrite Don Squad Storage Manager DSSM � Curtin</p>
            </div>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="js/jquery-1.11.0.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>