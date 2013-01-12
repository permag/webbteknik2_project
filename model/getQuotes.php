<?php
	header('Content-type: application/xml; charset=UTF-8');
	require_once('Database.php');

	$searchType = 'AUTHOR'; //$_GET['searchtype'];
	$searchTerm = urlencode(trim($_GET['query']));
	// XML data back.
	$urlApi = 'http://www.stands4.com/services/v2/quotes.php?uid=2543&tokenid=Xor0DOW0C4Ag1Iay&searchtype=' . $searchType . '&query=' . $searchTerm;

	// first, try get data from DB; if data is there and not old.
	$db = new Database();
  	$db->createDatabase('sqlite:../data/sillyPlayDB.sqlite');
  	$searchTerm = urldecode($searchTerm);
  	date_default_timezone_set('Europe/Berlin');
  	$dateNow = date('Y-m-d H:i:s');


// $db->insert("INSERT INTO Quote (searchTerm, quote, author) VALUES (:searchTerm, :quote, :author)", 
// 	array(':searchTerm' => 'Olle Fredriksson', ':quote' => 'Det var det!', ':author' => 'Olle Fredriksson'));

  	$stmt = $db->select("SELECT * FROM Quote WHERE searchTerm = :searchTerm OR nextUpdate < :dateNow", 
  		array(':searchTerm' => $searchTerm,
  			  ':dateNow' => $dateNow));

  	$count = 0;
  	$output = '<?xml version="1.0" encoding="UTF-8"?>';
  	$output .= '<results>';
  	while ($row = $stmt->fetch()) {
  		$count++;
  		$output .= '<result>';
  		$output .=    '<quote>' .$row['quote']. '</quote>';
  		$output .=    '<author>' .$row['author']. '</author>';
  		$output .= '</result>';
  	}
  	$output .= '</results>';

  	if ($count > 0) {
  		echo $output;
  	}





	// collect data
	//$data = file_get_contents($urlApi);

	//echo $data;


