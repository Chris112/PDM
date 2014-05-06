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
        <title>Project Storage</title>

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



        <?php displayHeader(); ?>


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


        <!-- Content start -->
        <div class="col-md-9">	
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Project Storage</h3>
                </div>
                <div class="panel-body">

                    <div class="col-md-3">          
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
                            echo "<center><a href=\"/PDM/ProjectStorage.php\"><button type=\"button\" class=\"btn btn-default btn-block\" href=\"#\">View Storage</button></center></a>";
                            echo "<center><button type=\"button\" class=\"btn btn-default btn-block\">Request Additional Storage</button></center>";
                            ?>
                        </div>
                    </div>


                    <div class="col-md-3" align="center">   
                        <h3> Upload Files </h3>
                        <form class="navbar-form" method="post" action="<?php echo $PHP_SELF; ?>">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">Filename:</span>
                                    <input type="text" class="form-control">
                                </div>
                                <br>
                                <div class="input-group">
                                    <span class="input-group-addon">Size:</span>
                                    <input type="text" class="form-control">
                                    <span class="input-group-addon">GB</span>
                                </div>
                                <br><br>
                                <button type="submit" class="btn btn-default">Submit</button>
                            </div>
                        </form>
                    </div>


                    <div class="col-md-3" align="center">   
                        <h3> Download Files </h3>
                        <div class="input-group">
                            <span class="input-group-addon">Project Name:</span>
                            <select class="form-control">

                                <?php
                                $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');

                                if (mysqli_connect_errno()) {
                                    echo "Failed to connect to MySQL: " . mysqli_connect_error();
                                }

                                $query = "SELECT filename FROM Files WHERE projID" . lookupProjName($_GET['action']);
                                $result = mysqli_query($con, $query);

                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<option>" . $row['filename'] . "</option>";
                                }
                                ?>



                            </select>
                        </div>
                        <br><br><br><br><br>
                        <button type="download" class="btn btn-default">Download</button>
                    </div> 




                </div>
            </div>
        </div>















        <!-- Content end -->










        <?php displayFooter(); ?>


        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/jquery-1.11.0.min.js"></script>
        <script src="js/bootstrap.min.js"></script>



    </body>

</html>