<?php
	header('Content-type: application/json');

	$source = $_GET['source'];
	$url = 	'http://www.iheartquotes.com/api/v1/random?source='. $source .'&format=json';
	echo '['.(file_get_contents($url)).']';
?>