<?php
	include_once "src/views/HTMLPage.php";
	include_once 'src/controllers/Authenticator.php';
	include_once "src/models/MysqlConnectionDetails.php";
	
    class Application {
    	
		//databasinställningar för hela applikationen.
		private static $dbhost = '1.1.1.1';
		private static $db = 'github';
		private static $dbuser = 'github';
		private static $dbpass = 'github';
	
		// @var HTMLPage Huvudvyn för applikationen.
		private $mainView;
		// @var Authenticator Kontroll som sysslar med att authenticera användare.
		private $authenticator;
		
    		function __construct() {
    			$this->mainView = new HTMLPage();
			$conDetails = new MysqlConnectionDetails(self::$dbhost,self::$db,self::$dbuser,self::$dbpass);
			$this->authenticator = new Authenticator($this->mainView,$conDetails);
    		}
		

		public function Run() {
			$this->authenticator->Run();
			$this->mainView->GetPage();
		}
    }
?>