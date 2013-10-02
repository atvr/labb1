<?php
	//Klass med anslutnings info.
	class MysqlConnectionDetails{
		
		public $dbhost;
	    	public $db;
		public $dbuser;
		public $dbpass;
		
		/*
		 * @param string $dbhost: IP för mysqlserver.
		 * @param string $db: databasnamn.
		 * @param string $dbuser: databasanvändare.
		 * @param string $dbpass: databaslösenord.
		 */
		function __construct($dbhost,$db,$dbuser,$dbpass) {
			$this->db = $db;
			$this->dbhost = $dbhost;
			$this->dbpass = $dbpass;
			$this->dbuser = $dbuser;
		}
	}

?>