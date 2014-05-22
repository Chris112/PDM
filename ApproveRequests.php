<?php
session_start();

if (empty($_SESSION[$access])) {
    header("location:login.php");
    die();
}
include 'dbFunctions.php';
include 'commonElements.php';



if (isset($_POST['commentAdd'])) {
    if (is_numeric($_POST['adminReqID'])) {
        $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
        $reqID = $_POST['adminReqID'];
        $comment = $_POST['adminComment'];

        $query = "UPDATE Requests SET adminComment=\"$comment\" WHERE reqID=$reqID";
        $result = mysqli_query($con, $query);
        if ($result) {
            $validComment = true;
        } else {
            $validComment = false;
        }
    } else {
        $validComment = false;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Process Requests</title>

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
                        <li><a href="ProjectSelection.php">Project Selection</a></li>
                        <li class="active"><a href="ApproveRequests.php">Approve Requests</a></li>
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


        <div class="col-md-9">

            <?php
            $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
// Check connection
            if (mysqli_connect_errno()) {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
            }

//If you're an admin, table should show requests made by all approvers
//Admin Code
            if ($_SESSION['currUser']['site_level'] == 3) {

                $faculty = mysqli_query($con, "SELECT * FROM Faculties");

                echo "
        	              <div class=\"panel panel-primary\">
                	        <div class=\"panel-heading\">
                   		<h3 class=\"panel-title\"> Requests from Approvers </h3>
                		</div>
                		<div class=\"panel-body\">
                        	<table class=\"table\">
        	        	<tr>
                                <th>Approver</th>
                                <th>Request ID</th>
				<th>Increase Amount</th>
				<th>Status</th>
				<th>Date Opened</th>
                                <th>Reason</th>
                                <th>Admin Comment</th>
        	            	</tr>";

                $validApprove1 = false;
                $validApprove2 = false;
                $validApprove3 = false;
                $validApprove4 = false;
                $validApprove5 = false;
                $validApprove6 = false;

                while ($fac = mysqli_fetch_array($faculty)) {

                    if ($fac['approver'] == NULL) {
                        $approver = "No Approver";
                    } else {
                        $a = mysqli_query($con, "SELECT * FROM Users u WHERE u.userID = " . $fac['approver']);
                        $app = mysqli_fetch_array($a);
                        $approver = $app['name'];
                    }

                    if (isset($_POST['approve'])) {
                        $check = mysqli_query($con, "SELECT * FROM Requests r WHERE r.facID = " . $fac['facID'] . "
                                               AND r.userID = " . $app['userID'] . " AND r.projID IS NULL && r.reqID = " . $_POST['textReqID']
                                . " AND r.status = 0");
                        if (mysqli_fetch_array($check)) {
                            $validApprove1 = true;
                            $textReqID = $_POST['textReqID'];
                            mysqli_query($con, "UPDATE Requests SET status = 1, date_closed = now() WHERE reqID = " . $textReqID);
                            $requestamount = mysqli_query($con, "SELECT increase_amount FROM Requests WHERE reqID = " . $textReqID);


                            $amount = mysqli_fetch_array($requestamount);
                            mysqli_query($con, "UPDATE Faculties SET total_space = total_space + " .
                                    $amount['increase_amount'] . " WHERE facID = " . $fac['facID']);
                            mysqli_query($con, "UPDATE Faculties SET free_space = free_space + " .
                                    $amount['increase_amount'] . " WHERE facID = " . $fac['facID']);

                            // create notification for the user that just had his request accepted
                            $result3 = mysqli_query($con, "SELECT userID FROM Requests WHERE reqID = $textReqID");
                            $requesterID = mysqli_fetch_array($result3, MYSQLI_ASSOC);
                            $string = "INSERT into Notifications(userID, info, date) values ({$requesterID['userID']}, \"Your request for storage has been approved!\", now())";
                            mysqli_query($con, $string);
                        }
                    }

                    if (isset($_POST['decline'])) {
                        $check = mysqli_query($con, "SELECT * FROM Requests r WHERE r.facID = " . $fac['facID'] . "
                                               AND r.userID = " . $app['userID'] . " AND r.projID IS NULL && r.reqID = " . $_POST['textReqID']
                                . " AND r.status = 0");
                        if (mysqli_fetch_array($check)) {
                            $validApprove2 = true;
                            $textReqID = $_POST['textReqID'];
                            mysqli_query($con, "UPDATE Requests SET status = 2, date_closed = now() WHERE reqID = " . $textReqID);

                            // create notification for the user that just had his request declined
                            $result3 = mysqli_query($con, "SELECT userID FROM Requests WHERE reqID = $textReqID");
                            $requesterID = mysqli_fetch_array($result3, MYSQLI_ASSOC);
                            $string = "INSERT into Notifications(userID, info, date) values ({$requesterID['userID']}, \"Your request for storage has been declined.\", now())";
                            mysqli_query($con, $string);
                        }
                    }

                    $result = mysqli_query($con, "SELECT * FROM Requests r WHERE r.facID = " . $fac['facID'] . " 
                                                  AND r.userID = " . $app['userID'] . " AND r.projID IS NULL AND r.status = 0");

                    if ($result) {
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo "<td>" . $approver . "</td>";
                            echo "<td>" . $row['reqID'] . "</td>";
                            echo "<td>" . $row['increase_amount'] . "</td>";
                            if ($row['status'] == 0) {
                                echo "<td>Pending</td>";
                            } else if ($row['status'] == 1) {
                                echo "<td>Approved</td>";
                            } else if ($row['status'] == 0) {
                                echo "<td>Declined</td>";
                            }

                            echo "<td>" . $row['date_opened'] . "</td>";
                            echo "<td>" . substr($row['reason'], 0, 20) . "</td>";
                            echo "<td>" . substr($row['adminComment'], 0, 40) . "</td>";
                            echo "</tr>";
                        }
                    }

                    echo "</table>
        	              </div>
                	      
                              <br><br>
                              </div>";
                }

                mysqli_close($con);
            }
//if you're an Approver, table show show requests made by each PI of the faculty
//Approver Code
            if ($_SESSION['currUser']['site_level'] == 2) {

                $projects = mysqli_query($con, "SELECT * FROM Projects p WHERE p.facID = " . $_SESSION['currUser']['facID']);

                echo "
        	              <div class=\"panel panel-primary\">
                	        <div class=\"panel-heading\">
                   		<h3 class=\"panel-title\"> Requests from Primary Investigators </h3>
                		</div>
                		<div class=\"panel-body\">
                        	<table class=\"table\">
        	        	<tr>
                                <th>PI Name</th>
                                <th>Project Name</th>
                                <th>Request ID</th>
				<th>Increase Amount</th>
				<th>Status</th>
				<th>Date Opened</th>
				<th>Date Closed</th>
                                <th>Reason</th>
        	            	</tr>";

                while ($pro = mysqli_fetch_array($projects)) {


                    $p = mysqli_query($con, "SELECT * FROM Users u WHERE u.userID = " . $pro['prim_invest']);
                    $pi = mysqli_fetch_array($p);

                    if (isset($_POST['approve'])) {
                        $check = mysqli_query($con, "SELECT * FROM Requests r WHERE r.projID = " . $pro['projID'] . "
                                               AND r.userID = " . $pi['userID'] . " AND r.reqID = " . $_POST['textReqID'] . " AND r.status = 0");
                        $result = mysqli_fetch_array($check);
                        if ($result) {
                            $validApprove3 = true;
                            $textReqID = $_POST['textReqID'];
                            mysqli_query($con, "UPDATE Requests SET status = 1, date_closed = now() WHERE reqID = " . $textReqID);

                            $requestamount = mysqli_query($con, "SELECT increase_amount FROM Requests WHERE reqID = " . $textReqID);
                            $amount = mysqli_fetch_array($requestamount);


                            mysqli_query($con, "UPDATE Projects SET total_space = total_space + "
                                    . $amount['increase_amount'] . " WHERE projID = " . $pro['projID']);

                            mysqli_query($con, "UPDATE Projects SET free_space = free_space + "
                                    . $amount['increase_amount'] . " WHERE projID = " . $pro['projID']);

                            mysqli_query($con, "UPDATE Faculties SET used_space = used_space + " .
                                    $amount['increase_amount'] . " WHERE facID = " . $pro['facID']);


                            // create notification for the user that just had his request accepted
                            $result3 = mysqli_query($con, "SELECT userID FROM Requests WHERE reqID = $textReqID");
                            $requesterID = mysqli_fetch_array($result3, MYSQLI_ASSOC);
                            $string = "INSERT into Notifications(userID, info, date) values ({$requesterID['userID']}, \"Your request for storage has been approved!\", now())";
                            mysqli_query($con, $string);
                        }
                    }

                    if (isset($_POST['decline'])) {
                        $check = mysqli_query($con, "SELECT * FROM Requests r WHERE r.projID = " . $pro['projID'] . "
                                               AND r.userID = " . $pi['userID'] . " AND r.reqID = " . $_POST['textReqID'] . " AND r.status = 0");
                        $result = mysqli_fetch_array($check);
                        if ($result) {
                            $validApprove4 = true;
                            $textReqID = $_POST['textReqID'];
                            mysqli_query($con, "UPDATE Requests SET status = 2, date_closed = now() WHERE reqID = " . $textReqID);

                            // create notification for the user that just had his request accepted
                            $result3 = mysqli_query($con, "SELECT userID FROM Requests WHERE reqID = $textReqID");
                            $requesterID = mysqli_fetch_array($result3, MYSQLI_ASSOC);
                            $string = "INSERT into Notifications(userID, info, date) values ({$requesterID['userID']}, \"Your request for storage has been declined.\", now())";
                            mysqli_query($con, $string);
                        }
                    }

                    $result = mysqli_query($con, "SELECT * FROM Requests r WHERE r.projID = " . $pro['projID'] . " AND r.userID = " . $pi['userID'] .
                            " AND r.status = 0");

                    if ($result) {
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo "<td>" . $pi['name'] . "</td>";
                            echo "<td>" . $pro['name'] . "</td>";
                            echo "<td>" . $row['reqID'] . "</td>";
                            echo "<td>" . $row['increase_amount'] . "</td>";
                            if ($row['status'] == 0) {
                                echo "<td>Pending</td>";
                            } else if ($row['status'] == 1) {
                                echo "<td>Approved</td>";
                            } else if ($row['status'] == 0) {
                                echo "<td>Declined</td>";
                            }
                            echo "<td>" . $row['date_opened'] . "</td>";
                            echo "<td>" . $row['date_closed'] . "</td>";
                            echo "<td>" . $row['reason'] . "</td>";
                            echo "</tr>";
                        }
                    }

                    echo "</table>
        	              </div>
                	      
                              
                              </div>";
                }

                mysqli_close($con);
            }

//if you're a Pi, table should show requests made by the DM(s) of the project you're PI
//To be a PI site level must be 1 and user project permissions must be 5
//PI code
            if ($_SESSION['currUser']['site_level'] == 1) {

                $userid = $_SESSION['currUser']['userID'];

                $piprojects = mysqli_query($con, "SELECT * FROM Projects p WHERE p.prim_invest = " . $userid);

                echo "
        	              <div class=\"panel panel-primary\">
                	        <div class=\"panel-heading\">
                   		<h3 class=\"panel-title\"> Requests from Data Managers </h3>
                		</div>
                		<div class=\"panel-body\">  
                        	<table class=\"table\">
        	        	<tr>
                                <th>Project Name</th>
				<th>Request ID</th>
				<th>Increase Amount</th>
				<th>Status</th>
				<th>Date Opened</th>
				<th>Date Closed</th>
                                <th>Reason</th>
        	            	</tr>";

                while ($pro = mysqli_fetch_array($piprojects)) {

                    if (isset($_POST['approve'])) {
                        // check to see if the reqID is in the table
                        $query = "SELECT * FROM Requests r WHERE r.projID = " . $pro['projID'] . " AND r.userID IN
							(SELECT userID FROM User_Projects u WHERE u.projID = r.projID AND u.permissions =4)
                                                         AND r.reqID = " . $_POST['textReqID'] . " AND r.status = 0";
                        $check = mysqli_query($con, $query);
                        if ($check) {
                            $result = mysqli_fetch_array($check);
                            if ($result) {
                                $textReqID = $_POST['textReqID'];
                                $result = mysqli_query($con, "UPDATE Requests SET status = 1, date_closed = now() WHERE reqID = " . $textReqID);
                                if ($result) {
                                    $validApprove5 = true;
                                }
                            }
                        }

                        // create notification for the user that just had his request accepted
                        $result3 = mysqli_query($con, "SELECT userID FROM Requests WHERE reqID = $textReqID");
                        $requesterID = mysqli_fetch_array($result3, MYSQLI_ASSOC);
                        $string = "INSERT into Notifications(userID, info, date) values ({$requesterID['userID']}, \"Your request for storage has been approved!\", now())";
                        mysqli_query($con, $string);
                    }

                    if (isset($_POST['decline'])) {
                        // check to see if the reqID is in the table
                        $query = "SELECT * FROM Requests r WHERE r.projID = " . $pro['projID'] . " AND r.userID IN
							(SELECT userID FROM User_Projects u WHERE u.projID = r.projID AND u.permissions =4)
                                                         AND r.reqID = " . $_POST['textReqID'] . " AND r.status = 0";
                        $check = mysqli_query($con, $query);
                        if ($check) {
                            $result = mysqli_fetch_array($check);
                            if ($result) {
                                $textReqID = $_POST['textReqID'];
                                $result = mysqli_query($con, "UPDATE Requests SET status = 2, date_closed = now() WHERE reqID = " . $textReqID);
                                if ($result) {
                                    $validApprove6 = true;
                                    // create notification for the user that just had his request accepted
                                    $result3 = mysqli_query($con, "SELECT userID FROM Requests WHERE reqID = $textReqID");
                                    $requesterID = mysqli_fetch_array($result3, MYSQLI_ASSOC);
                                    $string = "INSERT into Notifications(userID, info, date) values ({$requesterID['userID']}, \"Your request for storage has been declined.\", now())";
                                    mysqli_query($con, $string);
                                }
                            }
                        }
                    }

                    $dmrequest = mysqli_query($con, "SELECT * FROM Requests r WHERE r.projID = " . $pro['projID'] . " AND r.userID IN 
							(SELECT userID FROM User_Projects u WHERE u.projID = r.projID AND u.permissions = 4) AND
                                                         r.status = 0");


                    if ($dmrequest) {
                        while ($row = mysqli_fetch_array($dmrequest)) {
                            echo "<tr>";
                            echo "<td>" . $pro['name'] . "</td>";
                            echo "<td>" . $row['reqID'] . "</td>";
                            echo "<td>" . $row['increase_amount'] . "</td>";
                            if ($row['status'] == 0) {
                                echo "<td>Pending</td>";
                            } else if ($row['status'] == 1) {
                                echo "<td>Approved</td>";
                            } else if ($row['status'] == 0) {
                                echo "<td>Declined</td>";
                            }
                            echo "<td>" . $row['date_opened'] . "</td>";
                            echo "<td>" . $row['date_closed'] . "</td>";
                            echo "<td>" . $row['reason'] . "</td>";
                            echo "</tr>";
                        }
                    }


                    echo "</table>
        	              </div>

                              </div>";
                }

                mysqli_close($con);
            }

            echo "</div>";
            ?>           

            <div class="col-md-3" align="center">
                <div class="panel panel-primary">
                    <div class="panel-heading panel-primary">
                        <h3 class="panel-title"> Respond to Requests </h3>
                    </div>
                    <div class="panel-body">
                        <form action="<?php echo $PHP_SELF; ?>" method="post"><br>
                            <input class="form-control" type="text" name="textReqID"  placeholder="Enter Request ID"><br>

                            <input class = "btn btn-default btn-block" type="submit" name="approve" value="Approve Request">
                            <input class = "btn btn-default btn-block" type="submit" name="decline" value="Decline Request">
<?php
if (isset($_POST['approve'])) {
    if ($validApprove1 == true or $validApprove3 == true or $validApprove5 == true) {
        echo "<font color=\"green\"> Successfully approved request!</font><br><br>";
    } else {
        echo "<font color=\"red\"> Unable to approve request.</font><br><br>";
    }
} else {
    echo "<br><br><br>";
}

if (isset($_POST['decline'])) {
    if ($validApprove2 == true or $validApprove4 == true or $validApprove6 == true) {
        echo "<font color=\"green\"> Successfully declined request!</font><br><br>";
    } else {
        echo "<font color=\"red\"> Unable to decline request.</font><br><br>";
    }
} else {
    echo "<br><br><br>";
}
?>

                        </form></div></div></div>


                            <?php
                            if ($_SESSION['currUser']['site_level'] == 3) {
                                echo '
            <div class="col-md-3" align="center">
                <div class="panel panel-primary">
                    <div class="panel-heading panel-primary">
                        <h3 class="panel-title"> Add Comments </h3>
                    </div>
                    <div class="panel-body">
                        <form action="' . $PHP_SELF . '" method="post"><br>
                            <input class="form-control" type="text" name="adminReqID" required="required"  placeholder="Enter Request ID"><br>
                            <input class="form-control" type="text" name="adminComment" required="required"  placeholder="Enter Comment"><br>
                            <input class = "btn btn-default btn-block" type="submit" name="commentAdd" value="Add Comment">';

                                if (isset($_POST['commentAdd'])) {
                                    if ($validComment) {
                                        echo "<font color=\"green\"> Successfully added comment to request!</font><br><br>";
                                    } else {
                                        echo "<font color=\"red\"> Unable to comment to request.</font><br><br>";
                                    }
                                } else {
                                    echo "<br><br><br>";
                                }

                                echo '</form></div></div></div>';
                            }
                            ?>

            <?php displayFooter(); ?>
            <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
            <script src="js/jquery-1.11.0.min.js"></script>
            <!-- Include all compiled plugins (below), or include individual files as needed -->
            <script src="js/bootstrap.min.js"></script>
    </body>
</html>