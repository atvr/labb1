<?php
    class User {
		   
		public $Username;
		public $UserAgent;
		
		public function __construct($username) {
			$this->Username = $username;
			$this->UserAgent = $_SERVER['HTTP_USER_AGENT'];
		}
		
		
    }
?>