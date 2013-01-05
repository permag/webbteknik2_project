<?php
require 'src/facebook.php';
require_once('fb.php');

?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta content='width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;' name='viewport' />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="css/basic.css">
		<link rel="stylesheet" type="text/css" href="css/layout.css">
		<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
		<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAj0d4GRxrwk3TTU2Vs481ozrXNE0Wu648&sensor=true"></script>

		<title>sillyPlay</title>
	</head>
	<body>

		<!-- NAVBAR -->
		<nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="brand" href="./">sillyPlay</a>
					<div class="nav-collapse collapse">
						<ul class="nav">
			                <li class=""><a href="index.php">Home</a></li>
			                <li class=""><a href="main.php">Create</a></li>
			                <li class="active"><a href="global.php">Global</a></li>
						</ul>
					</div>

				</div>
			</div>
		</nav>


		<div id="map"></div>
		<div id="shareDialog"></div>

		<script>
			var tool = { activeUserId: 0 };
			$(function(){
				tool.activeUserId = <?php echo $_SESSION['activeUserId']; ?>;
			});
		</script>

		<!-- JavaScript files -->
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/oms.min.js"></script>
		<script src="js/global.js"></script>
	</body>
</html>