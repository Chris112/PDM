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
                        <li><a href="Notifications.php">Notifications</a></li>
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


                        <?php
                        $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
                        // Check connection
                        if (mysqli_connect_errno()) {
                            echo "Failed to connect to MySQL: " . mysqli_connect_error();
                        }

                        $currUserSiteLevel = $_SESSION['currUser']['site_level'];
                        $isPI = testIfPI($_SESSION['currUser']['userID']);

                        if ($currUserSiteLevel == 3) { // If currUser is admin, display all requests
                            $query = "SELECT * FROM Requests ORDER BY date_opened";
                        } else if ($currUserSiteLevel == 2) { // If user is an Approver, don't display Project Name
                            $query = "SELECT * FROM Requests WHERE userID={$_SESSION['currUser']['userID']} ORDER BY date_opened";
                        } else if ($currUserSiteLevel == 1) { // If user is a Researcher, display all requests they've made for all projects.
                            $query = "SELECT * FROM Requests WHERE userID={$_SESSION['currUser']['userID']} ORDER BY date_opened";
                        }
                        $result = mysqli_query($con, $query);

                        echo "<table class=\"table\">
                    <tr>";
                        if ($currUserSiteLevel == 1) {
                            echo "<th>Project Name</th>";
                        }
                        echo "<th>Requested Amount (GB)</th>
                    <th>Reason</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    </tr>";

                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            if ($currUserSiteLevel == 1) {
                                echo "<td>" . lookupProjName($row['projID']) . "</td>";
                            }
                            echo "<td>" . $row['increase_amount'] . "</td>";
                            echo "<td>" . $row['reason'] . "</td>";
                            echo "<td>" . $row['date_opened'] . "</td>";
                            if ($row['status'] == 0) {
                                echo "<td>Pending</td>";
                            } else if ($row['status'] == 1) {
                                echo "<td>Approved</td>";
                            } else {
                                echo "<td>Declined</td>";
                            }
                            echo "</tr>";
                        }
                        echo "</table>";
                        mysqli_close($con);
                        ?>
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
