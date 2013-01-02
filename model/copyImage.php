<?php
	$imageUrl = $_GET['imageUrl'];
	$folder = '../temp_image/';
	$tempImgFilename = basename($imageUrl);

	copy($imageUrl, $folder.$tempImgFilename);
	
	echo $tempImgFilename;
?>