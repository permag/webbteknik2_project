<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta content='width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;' name='viewport' />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="css/basic.css">
		<link rel="stylesheet" type="text/css" href="css/layout.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.css">
		<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
		<link rel="apple-touch-icon" href="img/icon57.png" />
		<link rel="apple-touch-icon" sizes="72x72" href="img/icon72.png" />
		<link rel="apple-touch-icon" sizes="114x114" href="img/icon114.png" />
		<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />
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
							<li class="active"><a href="#">Home</a></li>
							<li class=""><a href="#">menu 1</a></li>
							<li class=""><a href="#">menu 2</a></li>
						</ul>
					</div>

				</div>
			</div>
		</nav>



		<div class="container main">
			<h1>sillyPlay</h1>
		</div>

		<div class="container">
			
			<div class="leftMenu">
				LEFT
				<input type="text" id="searchPhotoTag" class="pull-left" />
				<button id="searchPhotoTagButton" class="pull-right">Find</button>
				<div id="searchPhotosContainer"></div>
			</div>

			<div class="mainMenu">
				<div id="alster">
					<div id="alsterPhotoFrame"></div>
					<div id="alsterQuoteFrame">"Uno var en cool revisor." -The Uno</div>
				</div>
			</div>


		</div>


		<!-- JavaScript files -->
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/init.js"></script>
	</body>
</html>