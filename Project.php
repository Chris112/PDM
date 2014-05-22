<?php
session_start();
include 'dbFunctions.php';
include 'commonElements.php';

if (empty($_SESSION[$access])) {
    header("location:login.php");
}

if ($_SESSION['currUser']['site_level'] == 1 AND ! userNotPartOfProject($_SESSION['currUser']['userID'])) { // redirect user if he is not part of spoecific project
    header("location:ProjectSelection.php");
}

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
                // create notification for the user that functions they have received
                $requesterID = $collab['userID'];
                $string = "INSERT into Notifications(userID, info, date) values ($requesterID, \"You have been given Read and Write access on the project {$_GET['action']}\", now())";
                mysqli_query($con, $string);
            } else if (!isset($_POST['read' . $i]) AND ! isset($_POST['write' . $i])) { // neither are set
                takeRead($collab['userID']);
                takeWrite($collab['userID']);
                // create notification for the user that functions they have received
                $requesterID = $collab['userID'];
                $string = "INSERT into Notifications(userID, info, date) values ($requesterID, \"You have been lost Read and Write access on the project {$_GET['action']}\", now())";
                mysqli_query($con, $string);
            } else if (!isset($_POST['read' . $i]) AND isset($_POST['write' . $i])) { // read is not set but write is
                giveWriteTakeRead($collab['userID']);
                // create notification for the user that functions they have received
                $requesterID = $collab['userID'];
                $string = "INSERT into Notifications(userID, info, date) values ($requesterID, \"You now have write access but not read on the project {$_GET['action']}\", now())";
                mysqli_query($con, $string);
            } else if (isset($_POST['read' . $i]) AND ! isset($_POST['write' . $i])) { // write is set but not read
                giveReadTakeWrite($collab['userID']);
                // create notification for the user that functions they have received
                $requesterID = $collab['userID'];
                $string = "INSERT into Notifications(userID, info, date) values ($requesterID, \"You now have read access but not write on the project {$_GET['action']}\", now())";
                mysqli_query($con, $string);
            }
        }
        $RWchanges = true;
    }

    if (isset($_POST['addCollab'])) {
        if (is_numeric($_POST['textBox2'])) {

            $targetsID = $_POST['textBox2'];
            $targetsFacID = lookupUsersFacID($targetsID);
            // can't add a researcher form another faculty.
            //echo "TARGETS FAC ID: " . $targetsFacID . ".<br>";
            //echo "CURRENT PROJECTS(" . $_GET['action'] . ") FACID: " . lookupFacIDOfProj($_GET['action']) . ".<br>";
            if ($targetsFacID == lookupFacIDOfProj($_GET['action'])) {
                $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
                $projID = lookupProjID($_GET['action']);
                $siteLevelOfUser = getSiteLevel($_POST['textBox2']);
                if ($siteLevelOfUser == 1) { // only add if they are a Researcher
                    $query = "INSERT INTO User_Projects (userID, projID, facID, permissions) VALUES ({$_POST['textBox2']}, $projID, {$_SESSION['currUser']['facID']}, 0)";
                    $result = mysqli_query($con, $query);
                    if ($result) {
                        $validAddCollab = true;
                        // create notification for the user that just been added to the project
                        $requesterID = $_POST['textBox2'];
                        $string = "INSERT into Notifications(userID, info, date) values ($requesterID, \"You have been added to the project {$_GET['action']}!\", now())";
                        mysqli_query($con, $string);
                    }
                } else {
                    $validAddCollab = false;
                }
            } else {
                $validAddCollab = false;
            }
        } else {
            $validAddCollab = false;
        }
    }

    if (isset($_POST['removeCollab'])) {
        if (is_numeric($_POST['textBox2'])) {
            $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
            $projID = lookupProjID($_GET['action']);
            $query = "DELETE FROM User_Projects WHERE userID={$_POST['textBox2']} AND projID=$projID AND permissions<4";
            $result = mysqli_query($con, $query);
            if ($result) {
                $validRemoveCollab = true;
                // create notification for the user that just been removed from project
                $requesterID = $_POST['textBox2'];
                $string = "INSERT into Notifications(userID, info, date) values ($requesterID, \"You have been removed from the project {$_GET['action']}\", now())";
                mysqli_query($con, $string);
            }
        } else {
            $validRemoveCollab = false;
        }
    }

    if (isset($_POST['promoteToDM'])) {
        if (is_numeric($_POST['textBox2'])) {
            $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
            $projID = lookupProjID($_GET['action']);
            $targetUserID = $_POST['textBox2'];
            $query = "UPDATE User_Projects SET permissions=4 WHERE userID=$targetUserID AND projID=$projID";
            $result = mysqli_query($con, $query);
            if ($result) {
                $validPromotion = true;
                // create notification for the user that just been promoted
                $requesterID = $targetUserID;
                $string = "INSERT into Notifications(userID, info, date) values ($requesterID, \"You have been promoted to Data Manager level on the project {$_GET['action']}\", now())";
                mysqli_query($con, $string);
            }
        } else {
            $validPromotion = false;
        }
    }

    if (isset($_POST['DemoteToColab'])) {
        if (is_numeric($_POST['textBox'])) {
            $yourSiteLevel = $_SESSION['currUser']['site_level'];
            $yourPerm = lookupUserPermission($_SESSION['currUser']['userID'], lookupProjID($_GET['action']));
            $targetsPerm = lookupUserPermission($_POST['textBox'], lookupProjID($_GET['action']));

            //echo "You have site level " . $yourSiteLevel . " with project permission " . $yourPerm . ", your target demotee has perm" . $targetsperm;

            if (($yourPerm == 5 AND $targetsPerm == 4) OR $yourSiteLevel > 1) { // cant demote another DM if you are a DM
                $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
                $projID = lookupProjID($_GET['action']);
                $query = "UPDATE User_Projects SET permissions=3 WHERE userID={$_POST['textBox']} AND projID=$projID";
                $result = mysqli_query($con, $query);
                if ($result) {
                    $validDemotion = true;
                    // create notification for the user that just been promoted
                    $requesterID = $_POST['textBox'];
                    $string = "INSERT into Notifications(userID, info, date) values ($requesterID, \"You have been demoted to Collaborator level on the project {$_GET['action']}\", now())";
                    mysqli_query($con, $string);
                } else {
                    $validDemotion = false;
                }
            } else {
                $validDemotion = false;
            }
        } else {
            $validDemotion = false;
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
                        <li><a href="StorageRequest.php">Storage Request</a></li>
                        <li><a href="Notifications.php">Notifications</a></li>
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
                        echo "<h3>" . "Project ID:" . lookupProjID($_GET['action']) . " - " . $_GET['action'] . "</h3>";
                        ?>
                    </div>
                    <div class="panel-body">

                        <div class="col-md-4">     
                            <h4>Principal Investigator:<br><small> <?php echo "ID: " . lookupProjPI($_GET['action']) . " Name: " . lookupUserName(lookupProjPI($_GET['action'])) ?> </small></h4>
                            <div class="status">
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

                                //PIE CHART SHOULD GO HERE
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
                                if ($_SESSION['currUser']['site_level'] >= 2 OR lookupUserPermission($_SESSION['currUser']['userID'], lookupProjID($_GET['action'])) >= 4) { // only display if user has permissions of 4+ or site_level 2+
                                    echo "<a href=\"StorageRequest.php\"><center><button type=\"button\" class=\"btn btn-default btn-block\">Request Additional Storage</button></a>";
                                }
                                ?>
                            </div> 
                        </div>

                        <div class="col-md-4">
                            <div class="data-managers" align="center">

                                <h3>Data Managers</h3>
                                <?php
                                $dataManagers = mysqli_query($con, "SELECT * FROM User_Projects WHERE projID = " . lookupProjID($_GET['action']) . " AND permissions = 4");
                                echo "<table class=\"table\"><tr><th>User ID</th><th>Name</th></tr>";
                                while ($row = mysqli_fetch_array($dataManagers)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['userID'] . "</td>";
                                    echo "<td>" . lookupUserName($row['userID']) . "</td>";
                                    echo "</tr>";
                                }
                                echo "</table><br>";

                                if ($_SESSION['currUser']['site_level'] >= 2 OR lookupUserPermission($_SESSION['currUser']['userID'], lookupProjID($_GET['action'])) >= 4) { // only display if user has permissions of 4+ or site_level 2+
                                    echo "<form action = \"" . $PHP_SELF . "\" method = \"post\">
                                    <div class = \"input-group\">
                                    <span class = \"input-group-addon\">UserID:</span>
                                    <input type = \"text\" name = \"textBox\" required = \"required\" class = \"form-control\">
                                    </div>
                                    <button type = \"submit\" name = \"DemoteToColab\" class = \"btn btn-default btn-block\">Demote to Collaborator</button>
                                    </form>";
                                }
                                if ($validDemotion) {
                                    echo "<font color=\"green\">" . lookupUserName($_POST['textBox']) . " has been demoted successfully</font><br><br>";
                                } else
                                    "<br>";
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
                                        echo "<td><input type=\"checkbox\"";
                                        if ($_SESSION['currUser']['site_level'] == 1 AND lookupUserPermission($_SESSION['currUser']['userID'], lookupProjID($_GET['action'])) < 4) {
                                            echo " onclick=\"return false\" onkeydown=\"return false\"";
                                        }
                                        echo " name=\"read" . $count . "\" checked=\"checked\"></td>";
                                        echo "<td><input type=\"checkbox\"";
                                        if ($_SESSION['currUser']['site_level'] == 1 AND lookupUserPermission($_SESSION['currUser']['userID'], lookupProjID($_GET['action'])) < 4) {
                                            echo " onclick=\"return false\" onkeydown=\"return false\"";
                                        }
                                        echo " name=\"write" . $count . "\" checked=\"checked\"></td>";
                                    } else if ($row['permissions'] == 2) {
                                        echo "<td><input type=\"checkbox\"";
                                        if ($_SESSION['currUser']['site_level'] == 1 AND lookupUserPermission($_SESSION['currUser']['userID'], lookupProjID($_GET['action'])) < 4) {
                                            echo " onclick=\"return false\" onkeydown=\"return false\"";
                                        }
                                        echo " name=\"read" . $count . "\"></td>";
                                        echo "<td><input type=\"checkbox\"";
                                        if ($_SESSION['currUser']['site_level'] == 1 AND lookupUserPermission($_SESSION['currUser']['userID'], lookupProjID($_GET['action'])) < 4) {
                                            echo " onclick=\"return false\" onkeydown=\"return false\"";
                                        }
                                        echo " name=\"write" . $count . "\" checked=\"checked\"></td>";
                                    } else if ($row['permissions'] == 1) {
                                        echo "<td><input type=\"checkbox\"";
                                        if ($_SESSION['currUser']['site_level'] == 1 AND lookupUserPermission($_SESSION['currUser']['userID'], lookupProjID($_GET['action'])) < 4) {
                                            echo " onclick=\"return false\" onkeydown=\"return false\"";
                                        }
                                        echo "name=\"read" . $count . "\" checked=\"checked\"></td>";

                                        echo "<td><input type=\"checkbox\"";
                                        if ($_SESSION['currUser']['site_level'] == 1 AND lookupUserPermission($_SESSION['currUser']['userID'], lookupProjID($_GET['action'])) < 4) {
                                            echo " onclick=\"return false\" onkeydown=\"return false\"";
                                        }
                                        echo "name=\"write" . $count . "\"></td>";
                                    } else if ($row['permissions'] == 0) {
                                        echo "<td><input type=\"checkbox\"";
                                        if ($_SESSION['currUser']['site_level'] == 1 AND lookupUserPermission($_SESSION['currUser']['userID'], lookupProjID($_GET['action'])) < 4) {
                                            echo " onclick=\"return false\" onkeydown=\"return false\"";
                                        }
                                        echo " name=\"read" . $count . "\"></td>";
                                        echo "<td><input type=\"checkbox\"";
                                        if ($_SESSION['currUser']['site_level'] == 1 AND lookupUserPermission($_SESSION['currUser']['userID'], lookupProjID($_GET['action'])) < 4) {
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
                                if ($_SESSION['currUser']['site_level'] >= 2 OR lookupUserPermission($_SESSION['currUser']['userID'], lookupProjID($_GET['action'])) >= 4) { // only display if user has permissions of 4+ or site_level 2+
                                    echo "<button type = \"submit\" name = \"saveChanges\" class = \"btn btn-default btn-block\">Update R/W Changes</button>
                                    </form>";

                                    if ($RWchanges) {
                                        echo "<font color=\"green\">Changes has been updated successfully.</font><br><br>";
                                    } else {
                                        echo "<br><br>";
                                    }

                                    echo "<form action = \"" . $PHP_SELF . "\" method = \"post\">
                                    <div class = \"input-group\">
                                    <span class = \"input-group-addon\">UserID:</span>
                                    <input type = \"text\" name = \"textBox2\" required = \"required\" class = \"form-control\">
                                    </div>
                                    <button type = \"submit\" name = \"addCollab\" class = \"btn btn-default btn-block\">Add Collaborator</button>
                                    <button type = \"submit\" name = \"removeCollab\" class = \"btn btn-default btn-block\">Remove Collaborator</button>
                                    <button type = \"submit\" name = \"promoteToDM\" class = \"btn btn-default btn-block\">Promote to Data Manager</button>
                                    </form>";

                                    if (isset($_POST['addCollab'])) {
                                        if ($validAddCollab) {
                                            echo "<font color=\"green\">" . lookupUserName($_POST['textBox2']) . " has been added successfully</font><br><br>";
                                        } else {
                                            echo "<font color=\"red\">" . lookupUserName($_POST['textBox2']) . " was unable to be added.</font><br><br>";
                                        }
                                    }
                                    if (isset($_POST['removeCollab'])) {
                                        if ($validRemoveCollab) {
                                            echo "<font color=\"green\">" . lookupUserName($_POST['textBox2']) . " has been removed successfully</font><br><br>";
                                        } else {
                                            echo "<font color=\"red\">" . lookupUserName($_POST['textBox2']) . " was unable to be removed.</font><br><br>";
                                        }
                                    }

                                    if (isset($_POST['promoteToDM'])) {
                                        if ($validPromotion) {
                                            echo "<font color=\"green\">" . lookupUserName($_POST['textBox2']) . " has been promoted successfully</font><br><br>";
                                        } else {
                                            echo "<font color=\"red\">" . lookupUserName($_POST['textBox2']) . " was unable to be promoted.</font><br><br>";
                                        }
                                    }
                                    "<br>";
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
