<?php
	header('Content-type: application/xml; charset=UTF-8');
	require_once('Database.php');

	$searchType = 'AUTHOR'; //$_GET['searchtype'];
	$searchTerm = urlencode(trim(strtolower(($_GET['query']))));
	// XML data back.
	$urlApi = 'http://www.stands4.com/services/v2/quotes.php?uid=2543&tokenid=Xor0DOW0C4Ag1Iay&searchtype=' . $searchType . '&query=' . $searchTerm;
  	$searchTerm = urldecode($searchTerm);
  	// db connect
	$db = new Database();
  	$db->createDatabase('sqlite:../data/sillyPlayDB.sqlite');


  	/**
  	 * First, try get data from DB; if data is there and not old.
  	 */
  	date_default_timezone_set('Europe/Berlin');
  	$dateNow = date('Y-m-d H:i:s');

  	$stmt = $db->select("SELECT * FROM Quote WHERE searchTerm = :searchTerm AND nextUpdate > :dateNow", 
  		array(':searchTerm' => $searchTerm,
  			  ':dateNow' => $dateNow));

  	$count = 0;
  	$output = '<?xml version="1.0" encoding="UTF-8"?>';
  	$output .= '<results>';
  	while ($row = $stmt->fetch()) {
  		$count++;
  		$output .= '<result>';
  		$output .=    '<quote>' .str_replace('&', '&amp;', $row['quote']). '</quote>';
  		$output .=    '<author>' .str_replace('&', '&amp;', $row['author']). '</author>';
  		$output .= '</result>';
  	}
  	$output .= '</results>';

  	/**
  	 * Data exists in database - use it.
  	 */
  	if ($count > 0) {
  		echo $output;  	
  	} 
  	else if ($count < 1) {
	  	/**
	  	 *  If no data in database or old - delete old data - get new data and insert in db - and display new data
	  	 */
		
		// time settings
		$timestamp = strtotime(date('Y-m-d H:i:s', strtotime($dateNow)) . ' + 1 week');
		$nextUpdate = date('Y-m-d H:i:s', $timestamp);

		//collect data from API in XML
		// xml and xpath
		$data = file_get_contents($urlApi);
  		$xml = new DOMDocument();
  		$xml->loadXML($data);
		$xpath = new DOMXPath($xml);
		$resultArray = array();

		// before deleting old date in DB, check API response gives data.
		$quoteTagExists = $xml->getElementsByTagName('quote'); 
		// data from API exists
		if ($quoteTagExists->length > 0) {

			// delete old data if any
			$db->delete("DELETE FROM Quote WHERE searchTerm = :searchTerm",
				array(':searchTerm' => $searchTerm));

			function insertQuote($db, $searchTerm, $quote, $author, $nextUpdate) {
				$db->insert("INSERT INTO Quote (searchTerm, quote, author, nextUpdate) 
							 VALUES (:searchTerm, :quote, :author, :nextUpdate)",
							 array(':searchTerm' => $searchTerm, 
							 	   ':quote' => $quote, 
							 	   ':author' => $author,
							 	   ':nextUpdate' => $nextUpdate));
			}

			// insert each xml element in DB
			foreach ($xpath->query('//results/result') as $result) {
				$quote = trim($xpath->query('.//quote[1]', $result)->item(0)->nodeValue);
				$author = trim($xpath->query('.//author[1]', $result)->item(0)->nodeValue);

				// insert each quote in DB
				if (($quote != null || $quote != '') && ($author != null || $author != '')) {
					insertQuote($db, $searchTerm, $quote, $author, $nextUpdate);
				}
			}
		}
 		
 		echo $data;
	}

