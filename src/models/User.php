<?php
    class User {
    	
		public $Username;
		public $UserAgent;
		
		/*
		 * @param string $usernameString användarnamn.
		 * @param string $userAgentString användaragent.
		 */
		public function __construct($usernameString, $userAgentString) {
			$this->Username = $usernameString;
			$this->UserAgent = $userAgentString;
		}
    }
?>