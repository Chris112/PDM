<?php
	session_start();

	function displayHeader() {
		echo "<div class=\"page-header panel-primary\">";
		echo "<div align=\"center\">";
		echo "<h1>The Dons Squad <small> <br>Storage Manager</h1>";
		echo "<p class=\"navbar-text\">Signed in as <b>" . $_SESSION['currUser']['name'] . "</b></p>";
		echo "<p class=\"navbar-text\">User ID: <b>" . $_SESSION['currUser']['userID'] . "</b></p>";
		echo "<p class=\"navbar-text\">";
		
		if (!is_null($_SESSION['currUser']['facID'])) {
			if (mysqli_connect_errno()) {
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
			$currUserFacID = $_SESSION['currUser']['facID'];
			echo "Faculty: <b> " . lookupFacName($currUserFacID) . "</b>";
		} else {
			echo "User is not part of any faculty.";
		}
		echo "</p>";
		echo "<br><br>";
		echo "</div>";
		echo "</div>";
	}

	function displayNavbar() {
		echo "<div class=\"col-md-2\">";
		echo "<div class=\"panel panel-primary\">";
		echo "<div class=\"panel-body\">";
		echo "<ul class=\"nav nav-pills nav-stacked\">";
		echo "<li class=\"active\"><a href=\"#\">Faculty Selection</a></li>";
		echo "<li class=\"ProjectSelection.php\"><a href=\"#\">Project Selection</a></li>";  
		echo "<li><a href=\"#\">Approve Requests</a></li>";
		echo "<li><a href=\"#\">Pending Requests</a></li>";
		echo "<li><a href=\"storageRequest.php\">Storage Request</a></li>";
		echo "<br><br>";
		echo "<li><a href=\"logout.php\">Log out</a></li>";
		echo "</ul>";
		echo "</div>";
		echo "</div>";
		echo "</div>";
	}

	function displayFooter() {
		echo "<div id = \"footer\">";
		echo "<div class = \"container\" align = \"center\">";
		echo "<p class = \"text-muted\">Copywrite Don Squad Storage Manager DSSM 頃urtin</p>";
		echo "</div>";
		echo "</div>";
	}

	function importCoreCSS() {
		echo "<link href = \"css/bootstrap.min.css\" rel = \"stylesheet\">";
		echo "<link href = \"css/sticky-footer-navbar.css\" rel = \"stylesheet\">";
	}
?>