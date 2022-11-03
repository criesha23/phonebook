<?php
	class config {
		private $host = "localhost";
		private $username = "root";
		private $password = '';
		private $database = "db_phonebook";
		private $conn = null;
		
		public function __construct() {
			$this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
		}
		
		public function connect() {
			return $this->conn;
		}
	}	

	function base_url() {
		return 'http://'.$_SERVER['HTTP_HOST'].'/phonebook/';
	}
?>