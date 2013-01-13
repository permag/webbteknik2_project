<?php
	session_start();
	require_once('Database.php');

	$activeUserId = $_SESSION['activeUserId'];
	$start = $_GET['start'];
	$take = $_GET['take'];
	//db
	$db = new Database();
	$db->createDatabase('sqlite:../data/sillyPlayDB.sqlite');

	$stmt = $db->select('SELECT * FROM Alster WHERE externalUserId = :externalUserId 
						ORDER BY alsterId DESC
						LIMIT :start, :take', 
						array(':externalUserId' => $activeUserId,
							  ':start' 			=> $start,
							  'take' 			=> $take));

	$alstersJSON = array();

	while ($row = $stmt->fetch()) {
		$alstersJSON[] = $row;
	}
	$stmt = null;

	echo json_encode($alstersJSON);