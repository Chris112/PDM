<?php
session_start();


if (empty($_SESSION[$access])) {
    header("location:login.php");
}

if ($_SESSION['currUser']['site_level'] == 1 AND ! userNotPartOfProject($_SESSION['currUser']['userID'])) { // redirect user if he is not part of spoecific project
    header("location:ProjectSelection.php");
}

include 'dbFunctions.php';
include 'commonElements.php';

function userNotPartOfProject($userID) {
    $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
    $query = "SELECT * FROM User_Projects WHERE userID=$userID AND projID=" . lookupProjID2($_GET['action']);
    $result = mysqli_query($con, $query);
    $test = mysqli_fetch_array($result);
    if (!$test) {
        return false;
    } else {
        return true;
    }
}

function lookupProjID2($projName) {
    $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    } else {
        $query = "SELECT projID FROM Projects WHERE name=\"$projName\"";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        mysqli_close($con);
        return $row['projID'];
    }
}
?>


<!DOCTYPE html>

<html lang="en">


    <?php
    $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
// Check connection
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    if (isset($_POST['addManager'])) {
        echo "YEeeeeees";
    }

//Permissions
    if (isset($_POST['saveChanges'])) {

        $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
        $collabQuery = "SELECT * FROM User_Projects WHERE projID = " . lookupProjID($_GET['action']) . " AND permissions <= 3";
        $collaborators = mysqli_query($con, $collabQuery);
        $collabCount = mysqli_num_rows($collaborators); // number of collaborators on project
        for ($i = 1; $i <= $collabCount; $i++) {
            $collab = mysqli_fetch_array($collaborators, MYSQLI_ASSOC);

            if (isset($_POST['read' . $i]) AND isset($_POST['write' . $i])) { // both are set
                giveRead($collab['userID']);
                giveWrite($collab['userID']);
            } else if (!isset($_POST['read' . $i]) AND ! isset($_POST['write' . $i])) { // neither are set
                takeRead($collab['userID']);
                takeWrite($collab['userID']);
            } else if (!isset($_POST['read' . $i]) AND isset($_POST['write' . $i])) { // read is not set but write is
                giveWriteTakeRead($collab['userID']);
            } else if (isset($_POST['read' . $i]) AND ! isset($_POST['write' . $i])) { // write is set but not read
                giveReadTakeWrite($collab['userID']);
            }
        }
        $RWchanges = true;
    }


    if (isset($_POST['addCollab'])) {
        if (is_numeric($_POST['textBox'])) {

            $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
            $projID = lookupProjID($_GET['action']);
            $siteLevelOfUser = getSiteLevel($_POST['textBox']);
            echo "Entered user has a site_level of: " . $siteLevelOfUser . ".<br>";

            if ($siteLevelOfUser == 1) { // only add if they are a Researcher
                $query = "INSERT INTO User_Projects (userID, projID, facID, permissions) VALUES ({$_POST['textBox']}, $projID, {$_SESSION['currUser']['facID']}, 0)";
                $result = mysqli_query($con, $query);
                if (!$result) {
                    echo "unable to add user as a collaborator.";
                } else {
                    $validAddCollab = true;
                    echo "added user.";
                }
            } else {
                echo "Unable to add user as collaborator as they are not a researcher";
            }
        } else {
            echo "Text box did not contain an int.";
            $validAddCollab = false;
        }
    }


    if (isset($_POST['removeCollab'])) {
        if (is_numeric($_POST['textBox'])) {
            $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
            $projID = lookupProjID($_GET['action']);

            $query = "DELETE FROM User_Projects WHERE userID=" . $_POST['textBox'];
            $result = mysqli_query($con, $query);
            if (!$result) {
                $validAddCollab = false;
                echo "unable to remove collaborator.<br>";
            } else {
                $validRemoveCollab = true;
                echo "Removed user.<br>";
            }
        } else {
            $validAddCollab = false;
        }
    }
    ?>






    <head>

        <meta http-equiv = "Content-Type" content = "text/html; charset=utf-8" />

        <title>Specific Project page</title>



        <meta charset = "utf-8">
        <meta http-equiv = "X-UA-Compatible" content = "IE=edge">
        <meta name = "viewport" content = "width=device-width, initial-scale=1">
        <meta name = "description" content = "">
        <meta name = "author" content = "">
        <link rel = "shortcut icon" href = "assets/ico/icon.ico">


        <!--Bootstrap core CSS -->
        <?php importCoreCSS()
        ?>
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
                        <li><a href="StorageRequest.php">Storage Request</a></li>
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
                                . "Total: " . $total['total_space'] . "GB<br>";

                                echo "Used: " . $used['used_space'] . "GB <br>";
                                echo "Free: " . $free['free_space'] . "GB </center></font> </div>";

                                $percent = floor($used['used_space'] / $total['total_space'] * 100);
                                echo "<div class=\"progress\">";
                                echo "<div class = \"progress-bar\" role = \"progressbar\" aria-valuenow = " . ($percent) . " aria-valuemin = \"0\" aria-valuemax = \"100\" style = \"width: " . ($percent) . "%;\">";
                                echo ($percent) . "%";
                                echo "<br> </div> </div>";

                                echo "<center><a href=\"/PDM/ProjectStorage.php?action=" . $_GET['action'] . "\"><button type=\"button\" class=\"btn btn-default btn-block\" href=\"#\">View Storage</button></center></a>";
                                echo "<a href=\"StorageRequest.php\"><center><button type=\"button\" class=\"btn btn-default btn-block\">Request Additional Storage</button></a>";
                                ?>
                            </div> 
                        </div>

                        <div class="col-md-4">
                            <div class="data-managers" align="center">

                                <h3>Data Managers</h3>

                                <?php
                                $dataManagers = mysqli_query($con, "SELECT * FROM User_Projects WHERE projID = " . lookupProjID($_GET['action']) . " AND permissions > 3");

                                echo "<table class=\"table\"><tr><th>User ID</th><th>Name</th></tr>";

                                while ($row = mysqli_fetch_array($dataManagers)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['userID'] . "</td>";
                                    echo "<td>" . lookupUserName($row['userID']) . "</td>";
                                    echo "</tr>";
                                }

                                echo "</table><br>";
//$dataManagers = mysqli_query($con, "SELECT * FROM User_Projects WHERE projID = " . lookupProjID($_GET['action']) . " AND permissions > 3");
//while ($row = mysqli_fetch_array($dataManagers)) {
//    if ($row['userID'] == $_SESSION['currUser']['userID']) {
//        echo "<form action=\"Project.php?action={$_GET['action']}\" method=\"post\">";
                                echo "<center><button type=\"submit\" name=\"addManager\" class=\"btn btn-default btn-block\">Add Data Manager</button>";
//      echo "</form>";
                                echo "<button type=\"submit\" class=\"btn btn-default btn-block\">Remove Data Manager</button></center>";
//}
//}
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
                      <th>User ID</th>
                      <th>Name</th>
                      <th><center>Read</center></th>
                      <th><center>Write</center></th>
                      </tr>";
                                $count = 1;
                                while ($row = mysqli_fetch_array($collaborators)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['userID'] . "</td>";
                                    echo "<td>" . lookupUserName($row['userID']) . "</td>";
                                    echo "<form action=\"" . $PHP_SELF . "?action={$_GET['action']}\" method=\"post\"> ";
                                    if ($row['permissions'] == 3) {
                                        /*  echo "<td> <center><input type=\"checkbox\" name=\"read" . $count . "\" checked=\"checked\"/></center> </td>";
                                          echo "<td> <center><input type=\"checkbox\" name=\"write" . $count . "\" checked=\"checked\"/></center> </td>";
                                          } else if ($row['permissions'] == 2) {
                                          echo "<td> <center><input type=\"checkbox\" name=\"read" . $count . "\"/></center> </td>";
                                          echo "<td> <center><input type=\"checkbox\" name=\"write" . $count . "\" checked=\"checked\"/></center> </td>";
                                          } else if ($row['permissions'] == 1) {
                                          echo "<td> <center><input type=\"checkbox\" name=\"read" . $count . "\" checked=\"checked\"/></center> </td>";
                                          echo "<td> <center><input type=\"checkbox\" name=\"write" . $count . "\"/></center> </td>";
                                          } else if ($row['permissions'] == 0) {
                                          echo "<td> <center><input type=\"checkbox\" name=\"read" . $count . "\"/></center> </td>";
                                          echo "<td> <center><input type=\"checkbox\" name=\"write" . $count . "\"/></center> </td>";

                                         */


                                        echo "<td><input type=\"checkbox\"";
                                        if ($_SESSION['currUser']['site_level'] == 1 AND lookupUserPermission($_SESSION['currUser']['userID']) < 4) {
                                            echo " onclick=\"return false\" onkeydown=\"return false\"";
                                        }
                                        echo " name=\"read" . $count . "\" checked=\"checked\"></td>";

                                        echo "<td><input type=\"checkbox\"";
                                        if ($_SESSION['currUser']['site_level'] == 1 AND lookupUserPermission($_SESSION['currUser']['userID']) < 4) {
                                            echo " onclick=\"return false\" onkeydown=\"return false\"";
                                        }
                                        echo " name=\"write" . $count . "\" checked=\"checked\"></td>";
                                    } else if ($row['permissions'] == 2) {

                                        echo "<td><input type=\"checkbox\"";
                                        if ($_SESSION['currUser']['site_level'] == 1 AND lookupUserPermission($_SESSION['currUser']['userID']) < 4) {
                                            echo " onclick=\"return false\" onkeydown=\"return false\"";
                                        }
                                        echo " name=\"read" . $count . "\"></td>";

                                        echo "<td><input type=\"checkbox\"";
                                        if ($_SESSION['currUser']['site_level'] == 1 AND lookupUserPermission($_SESSION['currUser']['userID']) < 4) {
                                            echo " onclick=\"return false\" onkeydown=\"return false\"";
                                        }
                                        echo " name=\"write" . $count . "\" checked=\"checked\"></td>";
                                    } else if ($row['permissions'] == 1) {

                                        echo "<td><input type=\"checkbox\"";
                                        if ($_SESSION['currUser']['site_level'] == 1 AND lookupUserPermission($_SESSION['currUser']['userID']) < 4) {
                                            echo " onclick=\"return false\" onkeydown=\"return false\"";
                                        }
                                        echo "name=\"read" . $count . "\" checked=\"checked\"></td>";

                                        echo "<td><input type=\"checkbox\"";
                                        if ($_SESSION['currUser']['site_level'] == 1 AND lookupUserPermission($_SESSION['currUser']['userID']) < 4) {
                                            echo " onclick=\"return false\" onkeydown=\"return false\"";
                                        }
                                        echo "name=\"write" . $count . "\"></td>";
                                    } else if ($row['permissions'] == 0) {

                                        echo "<td><input type=\"checkbox\"";
                                        if ($_SESSION['currUser']['site_level'] == 1 AND lookupUserPermission($_SESSION['currUser']['userID']) < 4) {
                                            echo " onclick=\"return false\" onkeydown=\"return false\"";
                                        }
                                        echo " name=\"read" . $count . "\"></td>";

                                        echo "<td><input type=\"checkbox\"";
                                        if ($_SESSION['currUser']['site_level'] == 1 AND lookupUserPermission($_SESSION['currUser']['userID']) < 4) {
                                            echo " onclick=\"return false\" onkeydown=\"return false\"";
                                        }
                                        echo "name=\"write" . $count . "\"></td>";
                                    }
                                    echo "</tr>";
                                    $count++;
                                }

                                echo "</table>";
                                echo "<br>";

                                mysqli_close($con);
                                ?>

                                <?php
                                if ($_SESSION['currUser']['site_level'] >= 2 OR lookupUserPermission($_SESSION['currUser']['userID']) >= 4) { // only display if user has permissions of 4+ or site_level 2+
                                    echo "<button type = \"submit\" name = \"saveChanges\" class = \"btn btn-default btn-block\">Update R/W Changes</button>
                                    </form>";
                                    
                                    if ($RWchanges) {
                                        echo "Changed successfully updated.<br><br>";
                                    } else {
                                    echo "<br><br>";
                                    }
                                        
                                    echo "<form action = \"" . $PHP_SELF . "\" method = \"post\">
                                    <div class = \"input-group\">
                                    <span class = \"input-group-addon\">UserID:</span>
                                    <input type = \"text\" name = \"textBox\" required = \"required\" class = \"form-control\">
                                    </div>
                                    <button type = \"submit\" name = \"addCollab\" class = \"btn btn-default btn-block\">Add Collaborator</button>
                                    <button type = \"submit\" name = \"removeCollab\" class = \"btn btn-default btn-block\">Remove Collaborator</button>
                                    <button type = \"submit\" name = \"promoteDM\" class = \"btn btn-default btn-block\">Promote to Data Manager</button>
                                    </form>";
                                }
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
