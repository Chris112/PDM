<!DOCTYPE html>
<html lang="en">
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Home</title>
		
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
        <div class="page-header">
            <div align="center">
                <h1>The Dons Squad <small> Storage Manager</h1>
            </div>
        </div>

        <!-- Left hand side nav bar -->
        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="#">Project Selection</a></li>
                <li><a href="#">Approve Requests</a></li>
                <li><a href="#">Pending Requests</a></li>
                <li><a href="storageRequest.php">Storage Request</a></li>
            </ul>
        </div>

        <!-- Main Panel area -->
        <div class="col-md-9">	
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Project 1</h3>
                </div>
                <div class="panel-body">
                    <?php
                    $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
                    // Check connection
                    if (mysqli_connect_errno()) {
                        echo "Failed to connect to MySQL: " . mysqli_connect_error();
                    }

                    $result = mysqli_query($con, "SELECT * FROM Users");

                    echo "<table class=\"table\">
                    <tr>
                    <th>User ID</th>
                    <th>Name</th>
					<th>Email</th>
					<th>Password</th>
                    </tr>";

                    while ($row = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['userID'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
						echo "<td>" . $row['email'] . "</td>";
						echo "<td>" . $row['password'] . "</td>";
                        echo "</tr>";
                    }

                    echo "</table>";

                    mysqli_close($con);
                    ?>
                </div>
            </div>
            <br><br>
        </div>

        <!-- Footer -->
        <div id="footer">
            <div class="container" align="center">
                <p class="text-muted">Copywrite Don Squad Storage Manager DSSM © Curtin</p>
            </div>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="js/jquery-1.11.0.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
