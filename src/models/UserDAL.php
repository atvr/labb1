<?php

	include_once 'src/models/MysqlConnectionDetails.php';
    class UserDAL {
	//@var MysqlConnectionDetails anslutningsinfo.
	private $conDetails; 
		
		function __construct(MysqlConnectionDetails $connectionDetails) {
			$this->conDetails = $connectionDetails;
		}
		
		// @return mysqlanslutning
		private function connect() {
			return mysqli_connect($this->conDetails->dbhost, $this->conDetails->dbuser, $this->conDetails->dbpass, $this->conDetails->db);
		}
		
		/*
		public function GetAllUsers() {
			$q = "SELECT * FROM labdb.users;";
			$result = $this->doQuery($q);
			while($row = mysqli_fetch_array($result)){
				echo $row['username'] . " " . $row['password'];
  				echo "<br>";
			}
		}*/
		
		private function doQuery($queryString) {
			$con = $this->connect();
			$result = mysqli_query($con, $queryString);
			if(!$result)
				echo mysqli_error($con);
			mysqli_close($con);
			return $result;
		}
		
		// @return bool Om databas operationen lyckades.
		public function AddUser($username, $password) {
			$q = "insert into labdb.users(username, password) values('$username', '$password')";
			$result = self::doQuery($q);
			return $result;
		}
		
		// @return bool Om databas operationen lyckades.
		public function DeleteUser($username) {
			$q = "delete from labdb.users where username='$username'";
			$result = self::doQuery($q);
			return $result;
		}
		
		// @return bool(FALSE) Om databas operationen misslyckades eller string med lösenordshash.
		public function GetHashForUser($username) {
			$q = "select password from labdb.users where username='$username'";
			$result = self::doQuery($q);
			$result = mysqli_fetch_array($result);
			if(!$result){
				return FALSE;
			}
			else {
				return $result['password'];
			}
		}
		
		//@return bool Finns en användare?
		public function DoesUserExist($username) {
			$q = "select username from labdb.users where username='$username'";
			$result = self::doQuery($q);
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