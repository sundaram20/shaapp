<?php 
	class DbConnect {
		private $host = 'ls-235f49fc2901dbe9e4e44f452f1c69fb1a321fad.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com:3306 (MySQL)';
		private $dbName = 'websocket';
		private $user = 'websocket';
		private $pass = 'Websocket#321';

		public function connect() {
			try {
				$conn = new PDO('mysql:host=' . $this->host . '; dbname=' . $this->dbName, $this->user, $this->pass);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return $conn;
			} catch( PDOException $e) {
				echo 'Database Error: ' . $e->getMessage();
			}
		}
	}
 ?>