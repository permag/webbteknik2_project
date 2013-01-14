<?php 
	require_once('Toro.php');
	require_once('../model/Database.php');
	require_once('code/APIModel.php');
	require_once('code/APIView.php');

	/**
     * ROUTING SCRIPT for GET, POST, PUT, DELETE 
     */

	// Init
	$init = new Init();
	$init->routes();

	/**
	 * INIT
	 */
	class Init {

		protected $_db = null;
		protected $_apiModel = null;
		protected $_apiView = null;

		/**
		 * NEW OBJECT INSTANCES
		 */
		public function __construct() {
			// DB
			$this->_db = new Database();
			$this->_db->createDatabase('sqlite:../data/sillyPlayDB.sqlite');
			// API Data Model
			$this->_apiModel = new APIModel($this->_db);
			// API View
			$this->_apiView = new APIView(); 
		}

		/**
		 * CREATE ROUTES
		 */
		public function routes() {
			// ROUTE URL's
			$method = strtolower($_SERVER['REQUEST_METHOD']);

			if ($method == 'get') {
				Toro::serve(array(
					'/alsters' => 'Alsters',
					'/alsters.:ext' => 'Alsters'
				));
			}
		}
	}

	/**
     *  ALSTERS
     */
	class Alsters extends Init {

		/**
		* GET ALSTERS
		*/
		function get($format = 'json') {
			// GET all alsters
			$back = null;
			$ret = $this->_apiModel->getAlsters();

			if ($code = $this->_apiView->checkForError($ret)) { // if true - errors
				$this->_apiView->setStatusCode($code);
				$back = $this->_apiView->getErrorMessage($code); 
			
			} else { // no errors
				$this->_apiView->setStatusCode(200);
				$this->_apiView->setContentType($format);
				$back = $this->_apiModel->toFormat($ret, $format);
			}

			echo $back;
		}
	}
