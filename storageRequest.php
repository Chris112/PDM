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

	function sendRequest($userID, $projID, $facID, $reqType, $status, $incAmount, $dateOpened, $dateClosed) 
	{
		mysqli_query($con,"INSERT INTO Requests ($userID, $projID, $facID, $reqType, $status, $incAmount, now(), $dateClosed)");
	}

	$userID = $_SESSION['currUser']['userID'];
	$projID = $_POST[""];
	$facID = $_POST[""];
	$reqType = $_POST[""];
	$status = $_POST[""];
	$incAmount = $_POST["storageReq"];
	$dateOpened = mysqli_query($con, "SELECT NOW() as 'now'");
	$date = mysqli_fetch_array($dateOpened);
	echo $date['now'];

	$dateClosed = NULL;

	mysqli_close($con);
?>
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
		<!-- Custom styles for footer -->
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
			<div class="panel panel-primary">
				<div class="panel-body">
					<ul class="nav nav-pills nav-stacked">
						<li class="active"><a href="#">Project Selection</a></li>
						<li><a href="#">Approve Requests</a></li>
						<li><a href="#">Pending Requests</a></li>
						<li><a href="storageRequest.php">Storage Request</a></li>
						<br><br>
						<li><a href="#">Log out</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-9">	
			<div class="panel panel-primary">	
				<div id="panel-body">
					<?php
						
						
					?>
					<form method="post" action="<?php echo $PHP_SELF;?>"> 
						<p><strong>Storage Request Form</strong></p>
						<p> The purpose of this form is to request changes to your requirements.</p>
						<p>
							<label for="textfield">Project Name:</label>
							<input name="textfield" type="text" disabled="disabled" id="textfield" value="[project's name]">
						</p>
						<p>
							<label for="projDataMan">Project Data Manager:</label>
							<input name="projDataMan" type="text" disabled="disabled" id="projDataMan" value=" [manager's name]">
						</p>
						<p>
							<label for="projStorCap">Project Storage Capacity:</label>
							<input name="projStorCap" type="text" disabled="disabled" id="projStorCap" value="[size]" size="10"> 
							MB
						</p>
						<p>
							<label for="storageReq">Additional Storage Required:</label>
							<input name="storageReq" type="text" required="required" id="textfield2" size="10">
							MB
						</p>
						<p>
							<label for="textarea">Comment:<br></label>
							<textarea name="textarea" id="textarea" cols="45" rows="3"></textarea>
						</p>
						<p>
							<input type="submit" name="submitButton" id="submitButton" value="Submit">
							<input type="button" name="cancelButton" id="cancelButton" value="Cancel">
						</p>
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
