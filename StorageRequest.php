<?php
session_start();
include 'dbFunctions.php';
include 'commonElements.php';


if (isset($_POST['reset'])) {
    echo "Not yet implemented.";
}

//Create Request Button
if (isset($_POST['submit'])) {

    // Find out if user is a PI/DM/Appr
    if ($_SESSION['currUser']['site_level'] == 2) {
        $userLevel = "Approver";
    } else if ($_SESSION['currUser']['site_level'] == 1) {
        // find out curr users permissions on selected project
        $selectedProject = $_POST['projSelection'];
        $usersPermissions = lookupUserPermission($_SESSION['currUser']['userID'], $selectedProject);
        if ($usersPermissions == 4) { // assert: user is a DM so request is to his PI
            $userLevel = "DM";
        } else if ($usersPermissions == 5) { // assert: user is a PI so request is to his approver
            $userLevel = "PI";
        }
    }


    if ($userLevel == "Approver") { // Creating a request for an Approver
        $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');

        /* Required info for a request */
        $userID = $_SESSION['currUser']['userID'];
        $facID = $_SESSION['currUser']['facID'];
        if (is_numeric($_POST['incAmount']) AND $_POST['incAmount'] <= 100000000) { // some arbitrary max so user doesn't enter a billion digits
            $incAmount = $_POST['incAmount'];
            $validInput = true;
        } else {
            $validInput = false;
        }
        $dateCreated = "now()";
        $reason = $_POST['reasonBox'];

        if ($validInput) {
            $query = "INSERT INTO Requests(userID, facID, increase_amount, date_opened, reason ) 
			      VALUES ($userID, $facID, $incAmount, $dateCreated, \"$reason\")";
            $result = mysqli_query($con, $query);
        } else {
            $validSubmit = false;
        }
        if ($result) {
            $validSubmit = true;
        } else {
            $validSubmit = false;
        }
        mysqli_close($con);
    } else if ($userLevel == "PI") { // Creating a request for a PI
        $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');

        /* Required info for a request */
        $userID = $_SESSION['currUser']['userID'];
        $facID = $_SESSION['currUser']['facID'];
        $projID = $_POST['projSelection'];
        if (is_numeric($_POST['incAmount']) AND $_POST['incAmount'] < 100000000) { // some arbitrary max so user doesn't enter a billion digits
            $incAmount = $_POST['incAmount'];
            $validInput = true;
        } else {
            $validInput = false;
        }
        $dateCreated = "now()";
        $reason = $_POST['reasonBox'];

        if ($validInput) {
            $query = "INSERT INTO Requests(userID, projID, facID, increase_amount, date_opened, reason ) 
			      VALUES ($userID, $projID, $facID, $incAmount, $dateCreated, \"$reason\")";
            $result = mysqli_query($con, $query);
            if ($result) {
                $validSubmit = true;
            } else {
                $validSubmit = false;
            }
        } else {
            $validSubmit = false;
        }

        mysqli_close($con);
    } else if ($userLevel == "DM") { // Creating a request for a DM
        $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');

        /* Required info for a request */
        $userID = $_SESSION['currUser']['userID'];
        $facID = $_SESSION['currUser']['facID'];
        $projID = $_POST['projSelection'];

        if (is_numeric($_POST['incAmount']) AND $_POST['incAmount'] < 100000000) { // some arbitrary max so user doesn't enter a billion digits
            $incAmount = $_POST['incAmount'];
            $validInput = true;
        } else {
            $validInput = false;
        }
        $dateCreated = "now()";
        $reason = $_POST['reasonBox'];

        if ($validInput) {
            $query = "INSERT INTO Requests(userID, projID, facID, increase_amount, date_opened, reason ) 
			      VALUES ($userID, $projID, $facID, $incAmount, $dateCreated, \"$reason\")";
            $result = mysqli_query($con, $query);
            if ($result) {
                $validSubmit = true;
            } else {
                $validSubmit = false;
            }
        } else {
            $validSubmit = false;
        }

        mysqli_close($con);
    }
}
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Storage Request</title>

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
        <?php displayHeader(); ?>

        <!-- Left hand side nav bar -->
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
                        <li><a href="PendingRequests.php">Pending Requests</a></li>
                        <li class="active"><a href="StorageRequest.php">Storage Request</a></li>
                        <li><a href="Notifications.php">Notifications</a></li>
                        <br><br>                        
                        <li><a href="logout.php">Log out</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Nav bar end -->


        <div class="container" align="center">
            <div class="col-md-7" align="center">	
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Request Form for Additional Storage</h3>
                    </div>
                    <div class="panel-body">
                        <div align="center">   
                            <form action = "<?php echo $PHP_SELF; ?>" method = "post">
                                <div class="form-group">
                                    <div class="input-group">
                                        <?php
                                        if ($_SESSION['currUser']['site_level'] == 1) { // Selecting a Project if user is a DM or PI otherwise selecting a faculty
                                            echo "<span class = \"input-group-addon\">Project:</span>";
                                        } else {
                                            echo "<span class = \"input-group-addon\">Faculty:</span>";
                                        }
                                        ?>

                                        <select class="form-control" name="projSelection">
                                            <?php
                                            $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
                                            if ($_SESSION['currUser']['site_level'] == 1) { // DM or PI at this stage: get all projects that user has permissions > 4
                                                $query = "SELECT * FROM User_Projects WHERE userID={$_SESSION['currUser']['userID']} AND permissions >=4";
                                                $result = mysqli_query($con, $query);
                                                while ($row = mysqli_fetch_array($result)) {
                                                    echo "<option value='{$row['projID']}'>" . lookupProjName($row['projID']) . "</option>";
                                                }
                                            } else if ($_SESSION['currUser']['site_level'] == 2) { // Approver: get the faculty they are from
                                                $query = "SELECT name FROM Faculties WHERE approver={$_SESSION['currUser']['userID']}";
                                                $result = mysqli_query($con, $query);
                                                $row = mysqli_fetch_array($result);
                                                echo "<option value='{$row['name']}'>" . $row['name'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <br>
                                    <div class="input-group">
                                        <span class="input-group-addon">Additional Storage Required:</span>
                                        <input type="text" name="incAmount" class="form-control" required="required">
                                        <span class="input-group-addon">GB</span>
                                    </div>
                                    <br>
                                    <div class="input-group">
                                        <span class="input-group-addon" rows="3">Comment:</span>
                                        <input type="text" name="reasonBox" placeholder="Please enter the reason for this request..." required="required" class="form-control">
                                    </div>
                                    <?php
                                    if (isset($_POST['submit'])) {
                                        if ($validSubmit) {
                                            echo "<br><br><font color=\"green\">Successfully created request.</font><br><br>";
                                        } else {
                                            echo "<br><br><font color=\"red\">Failed to create new request.</font><br><br>";
                                        }
                                    } else {
                                        echo "<br><br><br><br>";
                                    }
                                    ?>
                                    <button type="submit" name="submit" class="btn btn-default">Submit</button>
                                    <button type="submit" name="reset" class="btn btn-default">Reset</button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php displayFooter() ?>


        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="js/jquery-1.11.0.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>