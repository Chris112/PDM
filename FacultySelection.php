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



        <?php displayHeader() ?>

        <div class="col-md-2">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <ul class="nav nav-pills nav-stacked">
                        <li class="active"><a href="#">Faculty Selection</a></li>
                        <li class="ProjectSelection.php"><a href=\"#">Project Selection</a></li>
                        <li><a href="#">Approve Requests</a></li>
                        <li><a href="#">Pending Requests</a></li>
                        <li><a href="storageRequest.php">Storage Request</a></li>
                        <br><br>
                        <li><a href="logout.php">Log out</a></li>
                    </ul>
                </div>
            </div>
        </div>



        <!-- Content -->
        <!-- Main Panel area -->
        <div class="col-md-9">	
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Faculties</h3>
                </div>
                <div class="panel-body">
                    <?php
                    $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
                    // Check connection
                    if (mysqli_connect_errno()) {
                        echo "Failed to connect to MySQL: " . mysqli_connect_error();
                    }

                    $result = mysqli_query($con, "SELECT * FROM Faculties");

                    echo "<table class=\"table\">
                    <tr>
                    <th>ID</th>
                    <th>Faculty</th>
                    <th>Faculty Approver</th>
                    <th>Free (GB)</th>
                    <th>Used (GB)</th>
                    <th>Total (GB)</th>
                    </tr>";

                    $sumTotal = 0;
                    $sumUsed = 0;
                    $sumFree = 0;
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['facID'] . "</td>";
                        echo "<td><a href=\"Faculty.php?action=" . $row['name'] . "\">" . $row['name'] . "</a></td>";
                        echo "<td>" . lookupUserName($row['approver']) . "</td>";
                        echo "<td>" . $row['free_space'] . "</td>";
                        echo "<td>" . $row['used_space'] . "</td>";
                        echo "<td>" . $row['total_space'] . "</td>";

                        $sumTotal += $row['free_space'];
                        $sumUsed += $row['used_space'];
                        $sumFree += $row['total_space'];
                    }
                    echo "<tr>";
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "<td><b>" . $sumTotal . "</b></td>";
                    echo "<td><b>" . $sumUsed . "</b></td>";
                    echo "<td><b>" . $sumFree . "</b></td>";
                    echo "</tr>";
                    echo "</table>";

                    mysqli_close($con);
                    ?>
                </div>
            </div>
            <br><br>
        </div>
        <!-- Content end -->



        <?php displayFooter(); ?>



        <!-- Include all compiled plugins (below), or include individual files as needed -->

        <script src="js/jquery-1.11.0.min.js"></script>
        <script src="js/bootstrap.min.js"></script>

    </body>







</html>



