<?php
require 'src/facebook.php';
require_once('fb.php');

if (!$user) {
	header('location: index.php');
}


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
			                <li class="active"><a href="main.php">Create</a></li>
			                <li class=""><a href="global.php">Global</a></li>
						</ul>
					</div>

				</div>
			</div>
		</nav>


		<div class="container main">
		<?php
			if ($user) {
				echo '<img src="https://graph.facebook.com/'.$user.'/picture">';
				echo $user_profile['first_name'] . ' ' . $user_profile['last_name'];
			}
		?>
		</div>

		<div class="container">
			
			<div class="leftMenu">
				<h3>PHOTOS</h3>
				<input type="text" id="searchPhotoTag" class="pull-left" />
				<button id="searchPhotoTagButton" class="pull-left">Find</button>
				<div id="searchPhotosArea"></div>

				<h3>QUOTES</h3>
				<input type="text" id="searchQuoteTag" class="pull-left" />
				<button id="searchQuoteTagButton" class="pull-left">Find</button>
				<div id="searchQuoteTagArea"></div>
			</div>

			<div class="mainMenu">
				<div id="alster">
					<div id="alsterPhotoFrameBorder"><div id="alsterPhotoFrame"></div></div>
					<div id="alsterQuoteFrame">"Uno var en cool revisor." -The Uno</div>
				</div>
				<button id="finalize" class="btn btn-large btn-primary pull-right">FINALIZE</button>
			</div>

		</div>
		
		<div id="finalizeDialog"></div>

		<!-- JavaScript files -->
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
		<script src="js/jquery.plugin.html2canvas.min.js"></script>
   		<script src="js/html2canvas.min.js"></script>
   		<script src="js/jquery.masonry.min.js"></script>
   		<script src="js/finalize.js"></script>
		<script src="js/init.js"></script>
	</body>
</html>