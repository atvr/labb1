<?php
	include_once 'src/models/User.php';
	include_once 'src/models/CookieDAL.php';
	include_once 'src/models/MysqlConnectionDetails.php';
	include_once 'src/models/UserDAL.php';
	
	class LoginValidator {
		
		//@var CookieDAL DAL klass för att prata med databasens tabell cookies.
		private $cookieDAL;
		//@var UserDAL DAL klass för att prata med databasens tabell users.
		private $userDAL;
		//@var string Krypteringsalgoritmen lösenord ska hashas med. 
		private static $hashAlgo = 'sha512';
		
		function __construct(MysqlConnectionDetails $conDetails) {
			$this->cookieDAL = new CookieDAL($conDetails);
			$this->userDAL = new UserDAL($conDetails);
		}
		
		//@return bool Finns en användare i databasen med givet lösenord?
		
		public function IsUserValid($usernameString, $passwordString) {	
			if(!$this->userDAL->DoesUserExist($usernameString)) {
				return FALSE;
			}
			$passwordHash = $this->MakeHash($passwordString);
			$dbhash = $this->userDAL->GetHashForUser($usernameString);
			if($passwordHash != $dbhash) {
				return FALSE;
			}
			return TRUE;
		}
		
		//@return bool Är kakan ok eller försöker en elak användare att luras?
		public function IsCookieValid($cookieUsernameString, $cookiePasswordHash, $userAgentString) {
			$dbCookieExpire = $this->cookieDAL->GetCookieExpireTimeForUser($cookieUsernameString);
			if(!$dbCookieExpire) {
				return FALSE;
			}
			
			if($dbCookieExpire < time()) {
				return FALSE;
			}
			$dbpasshash = $this->userDAL->GetHashForUser($cookieUsernameString);
			if(!$dbpasshash) {
				return FALSE;
			}
			$dbpasshashMixed = $this->MakeHash($dbpasshash . $userAgentString);
			if($dbpasshashMixed != $cookiePasswordHash) {
				return FALSE;
			}
			return TRUE;
		}
		
		//Lägger till en kaka i databasen.
		public function AddCookie($usernameString, $expireInt) {
			return $this->cookieDAL->AddCookie($usernameString, $expireInt);
		}
		
		/*
		public function AddUser($usernameString, $passwordString) {
			$hashed = $this->MakeHash($passwordString);
			return $this->userDAL->AddUser($usernameString, $hashed);
		}
		*/
		
		/* 
		 * @param string sträng som ska hashas.
		 * @return string hashad sträng.
		 */
		public function MakeHash($someString) {
			return hash(self::$hashAlgo, $someString);
		}
	}

?>