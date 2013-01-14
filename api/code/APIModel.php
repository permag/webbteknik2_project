<?php
	class APIModel {

		private $_db = null;

		public function __construct($db) {
			$this->_db = $db;
		}

		/**
		 * [getAlsters]
		 */
		public function getAlsters() {

			$stmt = $this->_db->select('SELECT * FROM Alster WHERE lng != ""');
			if ($stmt == null) {
				return 'error500';
			}

			$alstersArray = array();

			while ($obj = $stmt->fetch(\PDO::FETCH_OBJ)) {
				$alster = new stdClass();
				$alster->alsterId 	= htmlspecialchars($obj->alsterId);
				$alster->alsterUrl 	= htmlspecialchars($obj->alsterUrl);
				$alster->alphaId 	= htmlspecialchars($obj->alphaId);
				$alster->lat 		= htmlspecialchars($obj->lat);
				$alster->lng 		= htmlspecialchars($obj->lng);

				$alstersArray[] = $alster;
			}
			$stmt = null;

			return $alstersArray;
		}


		/**
		 * FORMAT
		 */
		
		/**
		 * To format
		 */
		public function toFormat(array $data, $format) {
			if ($format == 'json') {
				return $this->toJSON($data);
			
			} else if ($format == 'xml') {
				return $this->toXML($data);
			} 
		} 

		/**
		 * To JSON
		 */
		public function toJSON(array $data) {
			return json_encode($data, JSON_NUMERIC_CHECK);
		}

		/**
		 * To XML
		 */
		public function toXML(array $data) {
			$xml = '<?xml version="1.0" encoding="utf-8"?>';
			$xml .= '<alsters>';
			for ($i = 0; $i < count($data); $i++) {
				$xml .= '<alster>';

				if (isset($data[$i]->alsterId)) {
					$xml .= '<alsterId>'.$data[$i]->alsterId.'</alsterId>';
				}
				if (isset($data[$i]->alsterUrl)) {
					$xml .= '<alsterUrl>'.$data[$i]->alsterUrl.'</alsterUrl>';
				}
				if (isset($data[$i]->alphaId)) {
					$xml .= '<alphaId>'.$data[$i]->alphaId.'</alphaId>';
				}
				if (isset($data[$i]->lat)) {
					$xml .= '<lat>'.$data[$i]->lat.'</lat>';
				}
				if (isset($data[$i]->lng)) {
					$xml .= '<lng>'.$data[$i]->lng.'</lng>';
				}

				$xml .= '</alster>';
			}
			$xml .= '</alsters>';

			return $xml;
		}

	}