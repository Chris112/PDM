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
        <title>Project Selection</title>

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

        <!-- Display header -->
        <?php displayHeader() ?>
        <!-- End display header -->

        <!-- Printing the title of the page --> 
        <div align="center">
            <?php
            // If the user is a researcher, print projects they're apart of
            if ($_SESSION['currUser']['site_level'] == 1) {
                echo "<h3>Projects that you are a part of</h3>";
                $access = "researcher";

                //If the user is an approver, print the projects of their faculty
            } else if ($_SESSION['currUser']['site_level'] == 2) {
                echo "<h3>Projects within the " . lookupFacName($_SESSION['currUser']['facID']) . " faculty</h3> ";
                $access = "approver";

                // If the user is an admin, check $_GET['action'].
            } else if ($_SESSION['currUser']['site_level'] == 3) {

                //      If action exists, print the projects of the action faculty
                if ($_GET['action']) {
                    echo "<h3>Projects within the " . $_GET['action'] . " faculty</h3> ";
                    $access = "action";

                    //      If the action does not exist, print all projects from all faculties.
                } else {
                    echo "<h3>All Projects from all Faculties</h3>";
                    $access = "all";
                }
            }
            ?>
        </div>
        <!-- End printing title of page -->

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
                        <li class="active"><a href="#">Project Selection</a></li>
                        <li><a href="#">Approve Requests</a></li>
                        <li><a href="#">Pending Requests</a></li>
                        <li><a href="storageRequest.php">Storage Request</a></li>
                        <br><br>
                        <li><a href="logout.php">Log out</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Nav bar end -->



        <!-- Create a new panel for every project that logged in user should be able to see -->
        <div class="container">
            <div class="row">
                <?php
                $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
                // Check connection has been made successfuly
                if (mysqli_connect_errno()) {
                    echo "Failed to connect to MySQL: " . mysqli_connect_error();
                }

                if ($access == "researcher") {
                    // find all projIDs that currUser is associated to then print out their project info.
                    // find all projIDs that currUser is part of
                    $query0 = "SELECT projID FROM User_Projects WHERE userID={$_SESSION['currUser']['userID']}";
                    $result0 = mysqli_query($con, $query0);
                    $projCount = mysqli_num_rows($result0);

                    $query = ("SELECT * FROM Projects WHERE ");

                    // for every project that user is on, add it's projID to result query
                    $row0 = mysqli_fetch_array($result0);
                    for ($i = 0; $i < $projCount; $i++) {
                        $query .= "projID=" . $row0['projID'];

                        // Do not add OR on the last iteration.
                        if ($i != $projCount - 1) {
                            $query .= " OR ";
                        }
                        // Get next projID that user is part of
                        $row0 = mysqli_fetch_array($result0);
                    }

                    // Query is now complete and returns all projects that user is part of
                    $result = mysqli_query($con, $query);
                } else if ($access == "approver") {
                    $result = mysqli_query($con, "SELECT * FROM Projects WHERE facID={$_SESSION['currUser']['facID']}");
                } else if ($access == "action") {
                    $result = mysqli_query($con, "SELECT * FROM Projects WHERE facID=" . lookupFacID($_GET['action']));
                } else if ($access == "all") {
                    $result = mysqli_query($con, "SELECT * FROM Projects ORDER BY facID");
                }

                // Let user know if query failed or returned no results.
                if (!$result || mysqli_num_rows($result) == 0) {
                    echo "No results found.";
                } else {
                    // Loop though all projects and lookup information about them
                    while ($row = mysqli_fetch_array($result)) {

                        echo "<div class=\"col-md-4\">";
                        echo "<div class=\"panel panel-primary\">";
                        echo "<div align=\"center\">";
                        echo "<h2>";
                        // If title is too big, cut it at 21 and add ... at end
                        if (strlen($row['name']) < 21) {
                            print $row['name'] . "</h2>";
                        } else {
                            print substr($row['name'], 0, 21) . "...</h2>";
                        }

                        echo "<p> <b>Principal Investigator: </b>" . lookupUserName($row['prim_invest']);
                        echo "<br><b>Description:</b> ";
                        // Only print the first 100 characters of the project description
                        print substr($row['description'], 0, 100);
                        echo "...";
                        echo "</p><a class=\"btn btn-default\" href=\"#\" role=\"button\">View details &raquo;</a></p>";
                        echo "</div> </div> </div>";
                    }// end while
                }

                mysqli_close($con);
                ?>

            </div>
        </div>

        <?php displayFooter(); ?>

        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/jquery-1.11.0.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>



</html>


