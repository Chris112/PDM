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
        <div align="center">
            <h3> Projects within the <?php echo lookupFacName($_SESSION['currUser']['facID']); ?> faculty</h3> 
        </div>

        <?php displayNavbar() ?>



        <!-- Create a new panel for every project that logged in user should be able to see -->
        <div class="container">
            <div class="row">
                <?php
                $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
                // Check connection has been made successfuly
                if (mysqli_connect_errno()) {
                    echo "Failed to connect to MySQL: " . mysqli_connect_error();
                }

                // Select all projects from the faculty that currUser is from and print them out in panels
                $result = mysqli_query($con, "SELECT * FROM Projects WHERE facID={$_SESSION['currUser']['facID']}");

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


