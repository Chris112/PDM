<?php
session_start();

// if (empty($_SESSION[$access])) {
//    header("location:login.php");
//    die();
//} 
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
	//If you're an admin, table should show requests made by all approvers
	//Admin Code
	if ($_SESSION['currUser']['site_level'] == 3)
	{
        	$con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
        	// Check connection
        	if (mysqli_connect_errno()) {
         	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
        	}

		$faculty = mysqli_query($con, "SELECT * FROM Faculties");

		while ($fac = mysqli_fetch_array($faculty)) {
			
			if ($fac['approver'] == NULL)
			{
				$approver = "No Approver";
			}
			else
			{
				$a = mysqli_query($con,"SELECT * FROM Users u WHERE u.userID = " . $fac['approver']);
				$app = mysqli_fetch_array($a);
				$approver = $app['name'];
			}

		        echo "<div class=\"col-md-9\">	
        	              <div class=\"panel panel-primary\">
                	        <div class=\"panel-heading\">
                   		<h3 class=\"panel-title\"> Requests from approver for faculty: " . $fac['name'] . "(" . $approver . ")" . " </h3>
                		</div>
                		<div class=\"panel-body\">  
                        	<table class=\"table\">
        	        	<tr>
				<th>Increase Amount</th>
				<th>Status</th>
				<th>Date Opened</th>
				<th>Date Closed</th>
        	            	</tr>";
                  
	                $result = mysqli_query($con, "SELECT * FROM Requests r WHERE r.facID = " . $fac['facID'] . " AND r.userID = " . $app['userID'] . " AND r.projID IS NULL");

	                while ($row = mysqli_fetch_array($result)) {
        		       	echo "<tr>";
				echo "<td>" . $row['increase_amount'] . "</td>";
                	        echo "<td>" . $row['status'] . "</td>";
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
	}
	//if you're an Approver, table show show requests made by each PI of the faculty
	//Approver Code
	if ($_SESSION['currUser']['site_level'] == 2)
	{      
        	$con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
        	// Check connection
        	if (mysqli_connect_errno()) {
         	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
        	}

		$projects = mysqli_query($con, "SELECT * FROM Projects p WHERE p.facID = " . $_SESSION['currUser']['facID']);

		while ($pro = mysqli_fetch_array($projects)) {
			

			$p = mysqli_query($con,"SELECT * FROM Users u WHERE u.userID = " . $pro['prim_invest']);
			$pi = mysqli_fetch_array($p);

		        echo "<div class=\"col-md-9\">	
        	              <div class=\"panel panel-primary\">
                	        <div class=\"panel-heading\">
                   		<h3 class=\"panel-title\"> Requests from primary investigator for " . $pro['name'] . "(" . $pi['name'] . ")" . " </h3>
                		</div>
                		<div class=\"panel-body\">  
                        	<table class=\"table\">
        	        	<tr>
				<th>Increase Amount</th>
				<th>Status</th>
				<th>Date Opened</th>
				<th>Date Closed</th>
        	            	</tr>";
                  
	                $result = mysqli_query($con, "SELECT * FROM Requests r WHERE r.projID = " . $pro['projID'] . " AND r.userID = " . $pi['userID']);

	                while ($row = mysqli_fetch_array($result)) {
        		       	echo "<tr>";
				echo "<td>" . $row['increase_amount'] . "</td>";
                	        echo "<td>" . $row['status'] . "</td>";
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
        }

	//if you're a PI, table should show requests made by the DM(s) of the project you're PI
	//PI code
	if ($_SESSION['currUser']['site_level'] == 1)
	{      
        	$con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
        	// Check connection
        	if (mysqli_connect_errno()) {
         	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
        	}

		$projects = mysqli_query($con, "SELECT * FROM Projects");

		while ($pro = mysqli_fetch_array($projects)) {
			

			$p = mysqli_query($con,"SELECT * FROM Users u WHERE u.userID = " . $pro['prim_invest']);
			$pi = mysqli_fetch_array($p);

		        echo "<div class=\"col-md-9\">	
        	              <div class=\"panel panel-primary\">
                	        <div class=\"panel-heading\">
                   		<h3 class=\"panel-title\"> Requests from primary investigator for " . $pro['name'] . "(" . $pi['name'] . ")" . " </h3>
                		</div>
                		<div class=\"panel-body\">  
                        	<table class=\"table\">
        	        	<tr>
				<th>Increase Amount</th>
				<th>Status</th>
				<th>Date Opened</th>
				<th>Date Closed</th>
        	            	</tr>";
                  
	                $result = mysqli_query($con, "SELECT * FROM Requests r WHERE r.projID = " . $pro['projID'] . " AND r.userID = " . $pi['userID']);

	                while ($row = mysqli_fetch_array($result)) {
        		       	echo "<tr>";
				echo "<td>" . $row['increase_amount'] . "</td>";
                	        echo "<td>" . $row['status'] . "</td>";
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
        }


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