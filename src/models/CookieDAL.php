<?php

	include_once 'src/models/MysqlConnectionDetails.php';
	
    class CookieDAL {
    	
		//@var MysqlConnectionDetails Anslutningsinfo.
		private $conDetails;
		
		function __construct(MysqlConnectionDetails $connectionDetails) {
			$this->conDetails = $connectionDetails;
		}
		
		private function connect() {
			return mysqli_connect($this->conDetails->dbhost, $this->conDetails->dbuser, $this->conDetails->dbpass, $this->conDetails->db);
		}
		
		//gör en sql query
		private function doQuery($queryString) {
			$con = $this->connect();
			$result = mysqli_query($con, $queryString);
			if(!$result)
				echo mysqli_error($con);
			mysqli_close($con);
			return $result;
		}
		
		/*
		 * @param string $usernameString: användarnamn
		 * @param int $expireInt: utgångstid
		 */
		public function AddCookie($usernameString, $expireInt) {
			if(!$this->doesUserHaveCookie($usernameString)) {
				$q = "insert into labdb.cookies(username, expire) values('$usernameString', $expireInt)";
			}
			else {
				$q = "update labdb.cookies set expire=$expireInt where username='$usernameString'";
			}
			$result = $this->doQuery($q);
			return $result;
		}
		
		/*
		 * @param string $usernameString användarnamn
		 * @return bool(FALSE) eller sträng med tiden då användaren senast loggade in.
		 */
		 
		public function GetCookieExpireTimeForUser($usernameString) {
			if(!$this->doesUserHaveCookie($usernameString)) {
				return FALSE;
			}
			else {
				$q = "select expire from labdb.cookies where username='$usernameString'";
				$result = $this->doQuery($q);
				$result = mysqli_fetch_array($result);
				return $result['expire'];
			}
		}
		
		/*
		* @param string $usernameString användarnamn
		* @return bool Finns en användare med i labdb.cookies?
		*/
		private function doesUserHaveCookie($usernameString) {
			$q = "select username from labdb.cookies where username='$usernameString'";
			$result = $this->doQuery($q);
			$result = mysqli_fetch_array($result);
			if(!$result){
				return FALSE;
			}
			else {
				return TRUE;
			}
		}
    }
?>