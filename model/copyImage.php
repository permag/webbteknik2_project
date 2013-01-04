<?php
	session_start();

	$imageUrl = $_GET['imageUrl'];
	$externalUserId = $_SESSION['activeUserId'];
	$folder = '../temp_image/';

	$ext = pathinfo($imageUrl, PATHINFO_EXTENSION);
	if ($ext == null || $ext == '') {
		$ext = 'jpg';
	}

	copy($imageUrl, $folder.$externalUserId.'.'.$ext);
	
	echo $externalUserId.'.'.$ext;
?>