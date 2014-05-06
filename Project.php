<?php
session_start();
if (empty($_SESSION[$access])) {
    header("location:login.php");
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
                        <li class="active"><a href="ProjectSelection.php">Project Selection</a></li>
                        <li><a href="ApproveRequests.php">Approve Requests</a></li>
                        <li><a href="PendingRequests.php">Pending Requests</a></li>
                        <li><a href="StorageRequests.php">Storage Request</a></li>
                        <br><br>
                        <li><a href="logout.php">Log out</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Nav bar end -->


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

                                $totalQuery = "SELECT total_space FROM Projects WHERE name=\"" . $_GET['action'] . "\"";
                                $usedQuery = "SELECT used_space FROM Projects WHERE name=\"" . $_GET['action'] . "\"";
                                $freeQuery = "SELECT free_space FROM Projects WHERE name=\"" . $_GET['action'] . "\"";
                                $totalResult = mysqli_query($con, $totalQuery);
                                $usedResult = mysqli_query($con, $usedQuery);
                                $freeResult = mysqli_query($con, $freeQuery);
                                $total = mysqli_fetch_array($totalResult, MYSQLI_ASSOC);
                                $free = mysqli_fetch_array($freeResult, MYSQLI_ASSOC);
                                $used = mysqli_fetch_array($usedResult, MYSQLI_ASSOC);


                                //PIE CHART
                                echo "<div class=\"well well-sm\" ><font size=\"4\"> <center>"
                                . "Total:" . $total['total_space'] . " GB<br> "
                                . "Used:" . $used['used_space'] . " GB<br> "
                                . "Free: " . $free['free_space'] . " GB</center></font></div>";
                                echo "<center><button type=\"button\" class=\"btn btn-default btn-block\">View Storage</button></center>";
                                echo "<center><button type=\"button\" class=\"btn btn-default btn-block\">Request Additional Storage</button></center>";
                                ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="data-managers" align="center">

                                <h3>Data Managers</h3>

                                <?php
                                $dataManagers = mysqli_query($con, "SELECT * FROM User_Projects WHERE projID = " . lookupProjID($_GET['action']) . " AND permissions > 3");

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
                                echo "<center><button type=\"button\" class=\"btn btn-default btn-block\">Add Data Manager</button>";
                                echo "<button type=\"button\" class=\"btn btn-default btn-block\">Remove Data Manager</button></center>";
                                ?>
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="collaborators" align="center">
                                <h3>Collaborators</h3>

<?php
$collaborators = mysqli_query($con, "SELECT * FROM User_Projects WHERE projID = " . lookupProjID($_GET['action']) . " AND permissions <= 3");

echo "<table class=\"table\">
                      <tr>
                      <th>Name</th>
                      <th><center>Read</center></th>
                      <th><center>Write</center></th>
                      </tr>";

while ($row = mysqli_fetch_array($collaborators)) {
    echo "<tr>";
    echo "<td>" . lookupUserName($row['userID']) . "</td>";
    if ($row['permissions'] == 3) {
        echo "<td> <center><input type=\"checkbox\" name=\"read" . $row['userID'] . "\" checked=\"checked\"/></center> </td>";
        echo "<td> <center><input type=\"checkbox\" name=\"write" . $row['userID'] . "\" checked=\"checked\"/></center> </td>";
    } else if ($row['permissions'] == 2) {
        echo "<td> <center><input type=\"checkbox\" name=\"read" . $row['userID'] . "\"/></center> </td>";
        echo "<td> <center><input type=\"checkbox\" name=\"write" . $row['userID'] . "\" checked=\"checked\"/></center> </td>";
    } else if ($row['permissions'] == 1) {
        echo "<td> <center><input type=\"checkbox\" name=\"read" . $row['userID'] . "\" checked=\"checked\"/></center> </td>";
        echo "<td> <center><input type=\"checkbox\" name=\"write" . $row['userID'] . "\"/></center> </td>";
    } else {
        echo "<td> <center><input type=\"checkbox\" name=\"read" . $row['userID'] . "\"/></center> </td>";
        echo "<td> <center><input type=\"checkbox\" name=\"write" . $row['userID'] . "\"/></center> </td>";
    }
    echo "</tr>";
}

echo "</table>";
echo "<br>";

echo "<center><button type=\"button\" class=\"btn btn-default btn-block\">Add Collaborator</button></center>";
echo "<center><button type=\"button\" class=\"btn btn-default btn-block\">Remove Collaborator</button></center>";
echo "<center><button type=\"button\" class=\"btn btn-default btn-block\">Promote to Data Manager</button></center>";
echo "<center><button type=\"button\" name=\"SaveChanges\" class=\"btn btn-default btn-block\">Save Changes</button></center>";


echo "filter_input(INPUT_POST, read" . $row['userID'] . ")";

if (isset($_POST['SaveChanges'])) {
    while ($row = mysqli_fetch_array($collaborators)) {
        if (isset($_POST['read' . $row['userID']]) and isset($_POST['write' . $row['userid']])) {
            if ($_POST['permissions'] != 3) {
                mysqli_query($con, "ALTER TABLE User_Projects SET permissions=3 WHERE userID=" . $row['userID']);
            }
        } else if (!isset($_POST['read' . $row['userID']]) and isset($_POST['write' . $row['userid']])) {
            if ($_POST['permissions'] != 2) {
                mysqli_query($con, "ALTER TABLE User_Projects SET permissions=2 WHERE userID=" . $row['userID']);
            }
        } else if (isset($_POST['read' . $row['userID']]) and ! isset($_POST['write' . $row['userid']])) {
            if ($_POST['permissions'] != 1) {
                mysqli_query($con, "ALTER TABLE User_Projects SET permissions=1 WHERE userID=" . $row['userID']);
            }
        } else {
            if ($_POST['permissions'] != 0) {
                mysqli_query($con, "ALTER TABLE User_Projects SET permissions=0 WHERE userID=" . $row['userID']);
            }
        }
    }
}


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
