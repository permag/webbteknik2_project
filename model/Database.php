<?php

	class Database {

		private $_pdo = null;

		/**
		 * CREATE SQLite DATABASE
		 */
		public function createDatabase($database) {

			try {
				$this->_pdo = new PDO($database);
				$this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			catch (PDOException $e) {
				die('Error: ' . $e->getMessage());
			}

			$query = "CREATE TABLE IF NOT EXISTS Alster 
						(alsterId INTEGER PRIMARY KEY,
						 externalUserId INTEGER,
						 alsterUrl TEXT,
						 alphaId TEXT,
						 lng REAL,
						 lat REAL)";

			try {
				$stmt = $this->_pdo->prepare($query);
				if (!$stmt->execute()) {
					die('Error. Could not create table.');
				}
			}
			catch (PDOException $e) {
				die('Error. ' . $e->getMessage());
			}
		}


		public function insert($query, Array $param) {
			return $this->insertUpdateDelete($query, $param);
		}
		public function update($query, Array $param) {
			return $this->insertUpdateDelete($query, $param);
		}
		public function delete($query, Array $param) {
			return $this->insertUpdateDelete($query, $param);
		}

		/**
		 * [insertUpdateDelete description]
		 * @param  [type] $query [description]
		 * @param  Array  $param [description]
		 * @return [type]        [description]
		 */
		private function insertUpdateDelete($query, Array $param) {
			try {
				$ret = 0;
				$stmt = $this->_pdo->prepare($query);

				if (!$stmt->execute($param)) {
					die('Error. Database error.');
				}
				$ret = $stmt->rowCount();
				$stmt = null;

				return $ret;
			}
			catch (PDOException $e) {
				die('Error. ' . $e->getMessage());
			}
		}

		/**
		 * [select description]
		 * @param  [type] $sqlQuery [description]
		 * @param  Array  $param    [description]
		 * @return [type]           [description]
		 */
		public function select($sqlQuery, Array $param = null) {
			try {
				$stmt = $this->_pdo->prepare($sqlQuery);
				if (!$stmt->execute($param)) {
					die('Error. Database error.');
				}
				return $stmt;

			} catch (PDOException $e) {
				die('Error. ' . $e->getMessage());
			}
		}

		/**
		 * Last inserted id
		 * @return int, number of last id
		 */
		public function lastInsertId() {
			try {
				return $this->_pdo->lastInsertId();

			} catch (PDOException $e) {
				die('Error. ' . $e->getMessage());
			}
		}

	}
