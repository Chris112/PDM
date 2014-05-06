<?php
	session_start();
	include 'dbFunctions.php';
	include 'commonElements.php';

	$con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$userID = $_SESSION['currUser']['userID'];
	$projID = 1;
	$facID = 1;
	$reqType = 1;
	$status = 0;
	$incAmount = 1;
	$dateOpened = mysqli_query($con, "SELECT NOW() as 'now'");
	$date = mysqli_fetch_array($dateOpened);
	$dateClosed = NULL;
	$reason = 'reason here';

	mysqli_query($con, "INSERT INTO Requests(userID, projID, facID, request_type, 
						status , increase_amount, date_opened, date_closed, reason ) 
						VALUES ($userID, $projID, $facID, $reqType, $status, $incAmount, $date, 2014-05-07, 'reason here')");

	/*

	function sendRequest($userID, $projID, $facID, $reqType, $status, $incAmount, $date, $dateClosed)
	{
		mysqli_query($con, "INSERT INTO Requests ($userID, $projID, $facID, $reqType, $status, $incAmount, $date, $dateClosed)");
	}
	
	$userID = $_SESSION['currUser']['userID'];
	$projID = 1;
	$facID = 1;
	$reqType = 1;
	$status = 0;
	$incAmount = 1;
	$dateOpened = mysqli_query($con, "SELECT NOW() as 'now'");
	$date = mysqli_fetch_array($dateOpened);
	$dateClosed = NULL;

	sendRequest($userID, $projID, $facID, $reqType, $status, $incAmount, $dateOpened, $dateClosed);*/

	

	mysqli_close($con);
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
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/sticky-footer-navbar.css" rel="stylesheet">
		<?php importCoreCSS() ?>
		<style type="text/css">
			#panel panel-primary
			{
				width: 960px;
				background: #FFFFFF;
				margin: 100 auto;
				border: 1px solid #000000;
				text-align: left;
				height: 720px;
				max-width: 960px;
				max-height: 720px;
			}
		</style>
	</head>
	<body>
		<?php displayHeader();?>
		
		<!-- Left hand side nav bar -->
		<!-- Nav bar -->
		<div class="col-md-2">
			<div class="panel panel-primary">
				<div class="panel-body">
					<ul class="nav nav-pills nav-stacked">
						<?php
							// Only display Faculty Selection link if currUser is admin or approver
							if ($_SESSION['currUser']['site_level'] > 1)
							{
								echo "<li><a href=\"FacultySelection.php\">Faculty Selection</a></li>";
							}
						?>
						<li><a href="ProjectSelection.php">Project Selection</a></li>
						<li><a href="ApproveRequests.php">Approve Requests</a></li>
						<li><a href="PendingRequests.php">Pending Requests</a></li>
						<li class="active"><a href="StorageRequest.php">Storage Request</a></li>
						<br><br>                        <li><a href="logout.php">Log out</a></li>
					</ul>
				</div>
			</div>
		</div>
		
		<!-- Nav bar end -->
		<div class="col-md-9">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Request for Additional Storage Form</h3>
				</div>
				<br><br>
				<div id="panel-body">
					<form class="navbar-form" method="post" action="<?php echo $PHP_SELF; ?>">
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-addon">Project Name:</span>
								<select class="form-control">
									<option>1</option>
									<option>2</option>
									<option>3</option>
									<option>4</option>
									<option>5</option>
								</select>
							</div>
							<br>
							<div class="input-group">
								<span class="input-group-addon">Additional Storage Required:</span>
								<input type="text" class="form-control">
								<span class="input-group-addon">GB</span>
							</div>
							<br>
							<div class="input-group">
								<span class="input-group-addon">Comment:</span>
								<textarea class="form-control" rows="6" placeholder="Please enter the reason for this request..."></textarea>
							</div>
							<br><br>
							<button type="submit" class="btn btn-default">Submit</button>
							<button type="button" class="btn btn-default">Cancel</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="js/jquery-1.11.0.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="js/jquery-1.11.0.min.js"></script>
	</body>
</html>