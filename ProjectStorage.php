<?php
session_start();

if (empty($_SESSION[$access])) {
    header("location:login.php");
    die();
}
include 'dbFunctions.php';
include 'commonElements.php';


// calculations done here to check if enough space to upload a file
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



if (isset($_POST['deleteButton'])) {

    removeFileFromProj($_GET['action'], $used['used_space'], $free['free_space'], $_POST['filename']);
    $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
    $query = "DELETE FROM Files WHERE filename=\"{$_POST['filename']}\"";
    $deleteResult = mysqli_query($con, $query);
}




if (isset($_POST['submit'])) {
    if ($_POST['size'] <= $free['free_space']) {
        if (strcmp($_POST['name'], "") > 0) {
// INSERT INTO query
            $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
            $query = "INSERT INTO Files(userID, projID, filename, size, date_uploaded) VALUES("
                    . "{$_SESSION['currUser']['userID']}," .
                    lookupProjID($_GET['action']) . ", \"" .
                    $_POST['name'] . "\"," .
                    $_POST['size'] . "," .
                    "now())";
            $insertResult = mysqli_query($con, $query);
            if (!$insertResult) {
                die('Error: ' . mysqli_error($con));
            }
            addFileToProJ($_GET['action'], $_POST['size'], $used['used_space'], $free['free_space']);
        } else {
            //   echo "Name can not be empty.";
        }
    } else {
        // echo "Not enough space to upload.";
    }
}

// calculations done here to check if enough space to upload a file
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
?>


<!DOCTYPE html>

<html lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Project Storage</title>

        <meta charset="utf-8"> 
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="assets/ico/icon.ico">

        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">








    </head>
    <body>
        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">File Download</h4>
                    </div>
                    <div class="modal-body">
                        File successfully downloaded.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Model 1 -->


        <?php displayHeader();
        ?>


        <div class="col-md-2">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <ul class="nav nav-pills nav-stacked">
                        <li><a href="FacultySelection.php">Faculty Selection</a></li>
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
        <?php
        if (!isset($_GET['action'])) {
            $showPage = true;
        } else {
            $showPage = false;
        }
        ?>

        <!-- Content start -->
        <div class="col-md-9">	
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Project Storage for <?php echo $_GET['action']; ?></h3>
                </div>
                <div class="panel-body">

                    <div class="col-md-3">          
                        <div class="pie-chart">
                            <?php
//PIE CHART 
                            echo "<div class=\"well well-sm\" ><font size=\"4\"> <center>"
                            . "Total:" . $total['total_space'] . " GB<br> "
                            . "Used:" . $used['used_space'] . " GB<br> "
                            . "Free: " . $free['free_space'] . " GB</center></font></div>";

                            $percent = floor($used['used_space'] / $total['total_space'] * 100);
                            echo "<div class=\"progress\">";
                            echo "<div class = \"progress-bar\" role = \"progressbar\" aria-valuenow = " . ($percent) . " aria-valuemin = \"0\" aria-valuemax = \"100\" style = \"width: " . ($percent) . "%;\">";
                            echo ($percent) . "%";
                            echo "<br> </div> </div>";

                            echo "<center><a href=\"StorageRequest.php\"><button type=\"button\" class=\"btn btn-default btn-block\">Request Additional Storage</button></a></center><br>";
                            ?>

                            <div align="center">
                                <form action="<?php $_PHP_SELF ?>" method="POST">
                                    <div class="control-group">
                                        <label class="control-label" for="Fruitname1" align="center">Do you wish to delete a file?</label>
                                        <select id="Fruitname1" name="filename"  required="required" align="center">
                                            <option value="">File Name</option>
                                            <?php
                                            $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
                                            $query = "SELECT * FROM Files WHERE projID=" . lookupProjID($_GET['action']);
                                            $result = mysqli_query($con, $query);
                                            while ($row = mysqli_fetch_array($result)) {
                                                echo "<option value='{$row['filename']}'>" . $row['filename'] . " - " . $row['size'] . "GB</option>";
                                            }
                                            ?> 
                                        </select>
                                    </div>
                                    <br>

                                    <?php
                                    if (isset($_POST['deleteButton'])) {
                                        if ($deleteResult) {
                                            echo "<center> ";
                                            echo $_POST['filename'] . " has been deleted.";
                                            echo "</center><br><br>";

                                            //removeFileFromProj($_GET['action'], $_POST['filename'], $_POST['size'])
                                        } else {
                                            echo "Unable to remove {$_POST['filename']} from current project.";
                                            echo "<br><br>";
                                        }
                                    } else {
                                        echo "<br><br><br>";
                                    }
                                    ?>
                                    <button class="btn btn-danger btn-block" type="submit" value="deleteButton" name="deleteButton">Delete file</button>
                                </form>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4" align="center">   
                        <h3> Upload Files </h3>
                        <form class="navbar-form" method="post" action="<?php echo $PHP_SELF; ?>">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">Filename:</span>
                                    <input type="text" name="name" class="form-control">
                                </div>
                                <br>
                                <div class="input-group">
                                    <span class="input-group-addon">Size:</span>
                                    <input type="text" name="size" class="form-control">
                                    <span class="input-group-addon">GB</span>
                                </div>
                                <br><br>

                                <button class="btn btn-success btn-block" type="submit" value="submit" name="submit">Upload file</button>


                                <br><br>
                            </div>
                        </form>
                        <?php
                        if (isset($_POST['submit'])) {
                            if ($insertResult) {
                                echo "File successfully uploaded.";
                            } else {
                                echo "Failed to upload file.";
                            }
                        }
                        ?>
                    </div>


                    <div class="col-md-4" align="center">   
                        <h3> Download Files </h3>
                        <div class="input-group">
                            <span class="input-group-addon">Project Name:</span>
                            <select class="form-control">
                                <option value="">File Name</option>
                                <?php
                                $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');

                                if (mysqli_connect_errno()) {
                                    echo "Failed to connect to MySQL: " . mysqli_connect_error();
                                }

                                $query = "SELECT * FROM Files WHERE projID=" . lookupProjID($_GET['action']);
                                $result = mysqli_query($con, $query);

                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<option>" . $row['filename'] . " - " . $row['size'] . "GB</option>";
                                }
                                ?>

                            </select>
                        </div>
                        <br><br><br><br><br>


                        <button class="btn btn-primary b" data-toggle="modal" data-target="#myModal">
                            Download Selected File
                        </button>
                    </div> <!-- End of Download column -->
                </div>
            </div>
        </div>
        <!-- Content end -->




        <?php displayFooter(); ?>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="js/jquery-1.11.0.min.js"></script> 
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
