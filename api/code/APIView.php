<?php
	class APIView {

		/**
		 * 
		 */
		public function checkForError($ret, $id = true) {
			$retCode = false;
			if ($ret === 'error400' || $id == null) {
				$retCode = 400;  
			}
			else if ($ret === 'error404') {
 				$retCode = 404; 
			} 
			else if ($ret === 'error500') {
				$retCode = 500;  
			}
			else {	
				$retCode = null;
			}
			return $retCode;
		}


		/**
		 * SET HEADER CONTENT TYPE
		 */
		public function setContentType($type) {
			if ($type == 'json') {
				header('Content-type: application/json; charset=utf-8');
			}
			else if ($type == 'xml') {
				header('Content-type: application/xml; charset=utf-8'); 
			}
		}

		/**
		 * SET HEADER STATUS CODE
		 */
		public function setStatusCode($code) {
			if ($code == 200) {
				header('HTTP/1.1 200 OK');
			} 
			else if ($code == 201) {
				header('HTTP/1.1 201 Created');
			}
			else if ($code == 400) {
				header('HTTP/1.1 400 Bad Request');
			}
			else if ($code == 404) {
				header('HTTP/1.1 404 Not Found');
			}
			else if ($code == 500) {
				header('HTTP/1.1 500 Internal Server Error');
			}
		}

		/**
		 * GET ERROR MESSAGES
		 */
		public function getErrorMessage($code) {
			if ($code == 400) {
				return '{"error" : "Please check parameters and syntax of request and try again."}';
			}
			else if ($code == 404) {
				return '{"error" : "Resource was not found."}';
			}
			else if ($code == 500) {
				return '{"error" : "Server error. Please try again."}';
			}
		}

		/**
		 * ACCEPTED FORMATS
		 */
		public function getAccept() {
			$headers = apache_request_headers();
			$accept = strtolower($headers['Accept']);

			if ($accept == 'application/json') {
				return 'json';
			}
			else if ($accept == 'application/xml') {
				return 'xml';
			}
		}

	}