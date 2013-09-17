<?php
	include_once '../models/User.php';
    class Authenticator {
    	public static function Login() {
    		if($_POST['username'] == null) {
    			self::setMsgForUser("Inloggning misslyckades. Användarnamn saknas.");
				return;
    		}
			elseif ($_POST['password'] == null) {
				self::setMsgForUser("Inloggning misslyckades. Lösenord saknas.");
				return;
			}	
			$username = $_POST['username'];
			$password = $_POST['password'];
			
    		if($username != "Admin" || $password != "Password") {
    			self::setMsgForUser("Inloggningen misslyckades. Felaktigt användarnamn eller lösenord.");
    		}
			else {
				session_start();
				$user = new User($username, $password);
				$_SESSION['LoggedInUser'] = $user;
				self::setMsgForUser("Inloggningen lyckades. Inloggad som $username.");
				if($_POST['stayloggedin'] != null) {
					$expire = time()+60*60*24*3;
					setcookie("username", $username, $expire,'/','localhost',FALSE,TRUE);
					setcookie("password", hash("sha512",$password), $expire,'/','localhost',FALSE,TRUE);	
				}
			}
    	}
		
		public static function LoginCookie() {
			$cookieUsername = $_COOKIE['username'];
			$cookiePassword = $_COOKIE['password'];
			
			if($cookieUsername == "Admin" && $cookiePassword == hash("sha512","Password")) {
				$user = new User($cookieUsername, "Password");
				$_SESSION['LoggedInUser'] = $user;
			}
		}
		
		
		private static function setMsgForUser($msg) {
			session_start();
			$_SESSION["MsgForUser"] = $msg;
		}
		
		public static function Logout() {
			$_SESSION['LoggedInUser'] = null;
			$_SESSION['MsgForUser'] = "Du har loggats ut.";
			if(count($_COOKIE) > 1) {
				$expire = time()-3600;
				setcookie("username", "", $expire,'/','localhost',FALSE,TRUE);
				setcookie("password", "", $expire,'/','localhost',FALSE,TRUE);
				}
		}
		
		/*Hade ursprungligen denna kod i index.php, men tyckte det var dålig/obefintlig enkapsulering och flyttade
		den därför hit. Nackdel med att köra den här är att det alltid skapas ett Authenticator object. Vilket är
		att föredra? Ingetdera kanske, men feedback uppskattas.*/
		public static function CheckForLoginInfo() {
			session_start();
			if($_SESSION['LoggedInUser'] == null && $_COOKIE['username'] != null && $_COOKIE['password'] != null) {
				self::LoginCookie();
			}
			if($_SERVER['QUERY_STRING'] == "login" && $_SESSION['LoggedInUser'] == null) {
				self::Login();
			}
			elseif($_SERVER['QUERY_STRING'] == "logout" && $_SESSION['LoggedInUser'] != null) {
				self::Logout();
			}
		}	
    }
	
?>