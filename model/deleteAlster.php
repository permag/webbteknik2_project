<?php
	session_start();
	require_once('Database.php');

	$alsterId = $_GET['alsterId'];
	$imgUrl = basename($_GET['imgUrl']);
	$externalUserId = $_SESSION['activeUserId'];

	if (isset($externalUserId)) {
		$db = new Database();
		$db->createDatabase('sqlite:../data/sillyPlayDB.sqlite');

		$query = "DELETE FROM Alster WHERE alsterId = :alsterId AND externalUserId = :externalUserId";
		$param = array(':alsterId' => $alsterId, ':externalUserId' => $externalUserId); 
		$ret = $db->delete($query, $param);
		if ($ret > 0) {
			unlink('../alster/' . $imgUrl);
			echo 1;
		}
	}
?>