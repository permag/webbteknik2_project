<?php
	header( "Content-type: application/json"); 
	require_once('Database.php');

	$outputJSON = array();

	$db = new Database();
	$db->createDatabase('sqlite:../data/sillyPlayDB.sqlite');
	$stmt = $db->select('SELECT * FROM Alster WHERE lng != ""', array());
	$stmt->setFetchMode(PDO::FETCH_ASSOC); 
	while($row = $stmt->fetch()) {
		array_push($outputJSON,$row);
	}

	echo json_encode($outputJSON);
?>