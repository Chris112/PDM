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
        <title>Pending Request</title>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="assets/icons/icon.ico">

        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/sticky-footer-navbar.css" rel="stylesheet">    
    </head>
    <body>



        <?php displayHeader(); ?>




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
                        <li><a href="ApproveRequests.php">Approve Requests</a></li>
                        <li class="active"><a href="PendingRequests.php">Pending Requests</a></li>
                        <li><a href="StorageRequest.php">Storage Request</a></li>
                        <br><br>
                        <li><a href="logout.php">Log out</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Nav bar end -->




        <!-- Content -->
        <!-- Main Panel area -->
        <div class="col-md-9">	
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Pending Requests</h3>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <tr>
                            <th>Project Name</th>
                            <th>Requested Amount (GB)</th>
                            <th>Reason</th>
                            <th>Request Date</th>
                            <th>Status</th>
                        </tr>
                        <tr>
                            <td>Networking</td>
                            <td>125</td>
                            <td>New information about Networking</td>
                            <td>6/10/2013</td>
                            <td>Approved</td>
                        </tr>
                        <tr>
                            <td>Networking</td>
                            <td>100</td>
                            <td>Technology being replaced</td>
                            <td>14/02/2014</td>
                            <td>Approved</td>
                        </tr>
                        <tr>
                            <td>Networking</td>
                            <td>1500</td>
                            <td>High res pictures require more space</td>
                            <td>19/04/2014</td>
                            <td>Declined</td>
                        </tr>
                        <tr>
                            <td>Software Engineering</td>
                            <td>500</td>
                            <td>New research methods</td>
                            <td>27/04/2014</td>
                            <td>Pending</td>
                        </tr>
                        <tr>
                            <td>Software Engineering</td>
                            <td>50</td>
                            <td></td>
                            <td>22/05/2014</td>
                            <td>Pending</td>
                        </tr>
                    </table>
                </div>
            </div>
            <br><br>
        </div>



        <!-- Footer -->
        <?php displayFooter(); ?>




        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="js/jquery-1.11.0.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
