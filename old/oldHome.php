<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Home</title>
	<link href="/Styles/home.css" rel="stylesheet" type="text/css" />

	<style type="text/css">

		body, html 
		{
  			height: 100%;
		}

		#right 
		{
 			height: 100%;
		}
		#container 
		{
			width: 960px;
			background: #FFFFFF;
			margin: 0 auto;
			border: 1px solid #000000;
			text-align: left;
			height: 720px;
			max-width: 960px;
			max-height: 720px;
		} 

		#sidebar 
		{
	float: left;
	width: 150px;
	background: #EBEBEB;
	padding: 10px 10px 15px 20px;
	top: 150px;
	position: absolute;
	left: 10px;
	height: 400px;
	overflow-y: auto;
	border: 1px solid #DDDDDD;
	border-radius: 4px 0 4px 0;
		} 
		
		#content 
		{
			width: 720px;
			background: #EBEBEB;
			padding: 10px;
			top: 150px;
			position: absolute;
			left: 200px;
			height:400px;
			overflow-y:auto;
			
			border: 1px solid #DDDDDD;
    		border-radius: 4px 0 4px 0;
		}
		
	</style>
</head>
<body>
<div class="container">
	
		<div class="header">
			<a href="#"><img src="images/DSSM Logo.png" alt="Insert Logo Here" name="Insert_logo" width="180" height="90" id="Insert_logo" style="background-color: #C6D580; display:block;" /></a>
		</div> 
		
  <div id="sidebar"> <!-- Change to links --> 
		 	<p><a href="home.php" title="Home">Home</a></p> 
 			<p><a href="notifications.php" title="Notifications">Notifications</a></p>
 			<p><a href="storageRequest.php" title="Storage Request" target="_blank">Request Storage</a></p>
   	 		
   	 		<p>Coming Soonaa</p>
   	 		<p>Coming Soon</p>
 			<p>Coming Soon</p>
		</div> 
	
    

  	<div class id="content">
				<div class="storageInfo">
        	<p><strong>[Project Title]</strong></p>
        	<table width="707" border="1">
        	  <tr>
        	    <th width="256" height="116" scope="col">
                <!-- the pie section-->
                <div class="canvas-container">
            		<canvas id="pie_chart"></canvas>
        		</div>

                <!--- till here -->
        	    <th width="435" scope="col"><table width="339" border="1">
        	      <tr>
        	        <td><table width="339" border="1">
        	          <tr>
        	            <th width="163" scope="row">Available</th>
        	            <td width="94">[db] GB</td>
        	            <td width="60">[val] %</td>
      	            </tr>
        	          <tr>
        	            <th scope="row">Used</th>
        	            <td>[db] GB</td>
        	            <td>[val] %</td>
      	            </tr>
        	          <tr>
        	            <th scope="row">Capacity</th>
        	            <td>[db] GB</td>
      	            </tr>
      	          </table></td>
      	        </tr>
        	    </table>
       	        <a href="storageRequest.php" title="Request Storage" target="_blank">Request Storage</a></th>
      	    </tr>
      	  </table>
        </div><!-- end storageInfo class -->
				
				<div class="permissions">
					<p>&nbsp;</p>
   	  				<p><strong>Researcher(s) Permissions</strong></p>
	  				<table width="690" border="1">
						<tr>
							<th width="383" scope="col">Name</th>
							<th width="60" scope="col">Read</th>
							<th width="60" scope="col">Write</th>
							<th width="159" scope="col">Revoke</th>
						</tr>
						<tr>
							<td>[db]</td>
							<td><input type="checkbox" name="readCheckbox" id="readCheckbox"></td>
							<td><input type="checkbox" name="writeCheckbox" id="writeCheckbox"></td>
							<td><a href="#" title="Revoke Access" target="_blank">Revoke Access</a></td>
						</tr>
					</table>
	  				<p>
		  				<input type="button" name="add" id="addButton" value="Add Researcher">
					</p>
				</div><!-- end permissions class -->
			
  </div><!-- end content class -->
</div><!-- end container class -->
	
<script src="Chart.js-master/Chart.js">//Get the context of the canvas element we want to select
		var data = [
		{
			value: 20,
			color:"#637b85"
		},
		{
			value : 30,
			color : "#2c9c69"
		},
		{
			value : 40,
			color : "#dbba34"
		},
		{
			value : 10,
			color : "#c62f29"
		}
	 
		];
		var canvas = document.getElementById("pie_chart");
		var ctx = canvas.getContext("2d");
		new Chart(ctx).Doughnut(data);
	</script>

	
</body>
</html>
