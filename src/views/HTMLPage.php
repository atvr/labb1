<?php
	
    class HTMLPage {
		// En massa statiska strängar för att undvika strängberoenden i koden.
		
		// @var string användarnamn, används av cookie, post och html generering
		private static $userNameString = "username";
		// @var string password, används av cookie, post och html generering
		private static $passwordString = "password";
		// @var string för kalla på User objekt i session
		private static $inSessionUser = "LoggedInUser";
		// @var string för att lagra meddelande i session.
		private static $msgString = "MsgForUser";
		// @var string Lokala sökvägen för applikationen. I syfte att användas för kakor.
		private static $localPath = "/labb2/";
		// @var string Domänen för applikationen. I syfte att användas för kakor.
		private static $domain = "atvr.net";
		
		// @var string Querysträngar för inloggning respektive utloggning.
		public static $queryStringForLogin = "login";
		public static $queryStringForLogout = "logout";
		
		// @var string Postvärde för att använda cookielogin
		public static $postVarForCookies = "stayloggedin";
		
		/*
		 * @return string: En html sida.
		 */
		public function GetPage() {
			session_start();
			$title = self::getTitle();
			$body = self::getBody();
			echo "<!DOCTYPE html>
					<html>
						<head>
							<title>$title</title>
							<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
						</head>
						<body>
							$body
						</body>
					</html>";
		}
		
		/*
		 * @return User: Den inloggade användaren eller null.
		 */
		 
		public function GetLoggedInUser() {
			$this->makeSureSessionIsStarted();
			return $_SESSION[self::$inSessionUser];
		}
		
		/*
		 * @return string: En html body
		 */
		 
		private function getBody() {
			$timestring = $this->getLNUTimeString();
			$body = "<h1>av222bns labb 2.</h1>";
			$user = $this->GetLoggedInUser();
			$msg = $this->getAndDeleteMsgForUser();
			if($user != null) {
				$body .= "<h2>Inloggad som $user->Username.</h2>";
				if($msg != null) {
					$body .= "<p>$msg</p>";
				}
				$body .= "<p><a href='?logout'>Logga ut.</a></p>";
			}
			else {
				$body .= "<h2>Inte inloggad.</h2>
						<fieldset>";
				if($msg != null) {
					$body .= "<p>$msg</p>";
				}
				
				$cookiesPost = self::$postVarForCookies;
				$unamePost = self::$userNameString;
				$pwPost = self::$passwordString;
				$body .= "<legend>Logga in - Skriv ditt användarnamn och lösenord.</legend>
							<form action='?login' method='post' enctype='multipart/form-data'>
								<p><label>Användarnamn:</label>
								<input type=\"text\" name=\"$unamePost\"/></p>
								<p><label>Lösenord:</label>
								<input type=\"password\" name=\"$pwPost\"/></p>
								<p><label>Fortsätt vara inloggad:</label><input type='checkbox' name='$cookiesPost' /></p>
								<p><input type='submit' value='Skicka'/></p>
							</form>
						</fieldset>";
			}
			$body .= 
					"
					<p>$timestring</p>";
			return $body;
		}

		/*
		 * @return bool: Om det finns en inloggad användare.
		 */
		 
		public function IsUserLoggedIn() {
			$this->makeSureSessionIsStarted();
			if(isset($_SESSION[self::$inSessionUser])) return TRUE;
			else return FALSE;
		}
		
		/*
		 * @return string: En html title.
		 */
		 
		private function getTitle() {
			$title = "Labb 1: ";
			
			if($this->IsUserLoggedIn()) {
				$title .= "Inloggad.";
			}
			else {
				$title .= "Inte inloggad.";
			}
			return $title;
		}
		
		/*
		 * @return string: En tidssträng enligt formatet exemplifierat här: http://1dv408.b-zeal.net/
		 */
		 
		private function getLNUTimeString()
		{
			setlocale(LC_TIME, "sv_SE.utf8");
			date_default_timezone_set("Europe/Stockholm");
			$timedata = explode(" ", strftime("%A %d %B %Y %X"));
			$nameWeekday = ucfirst($timedata[0]);
			$numDayOfMonth = $timedata[1];
			if (substr($numDayOfMonth, 0, 1) == "0") {
				$numDayOfMonth = substr($numDayOfMonth, 1,1);
			}
			$nameMonth = ucfirst($timedata[2]);
			$numYear = $timedata[3];
			$numTimeOfDay = $timedata[4];
			return "$nameWeekday, den $numDayOfMonth $nameMonth, år $numYear. Klockan är [$numTimeOfDay].";
		}
		

		/* @param string msgForUser meddelande till användaren.
		 */
		public function SetMsgForUser($msgString) {
			$this->makeSureSessionIsStarted();
			$_SESSION[self::$msgString] = $msgString;
		}
		
		/*
		 * Ser till att en session är igång.
		 */
		 
		private function makeSureSessionIsStarted() {
			if(!$_SESSION) {
				session_start();
			}
		}
		
		/*
		 * @return string meddelande till användaren
		 */
		private function getAndDeleteMsgForUser() {
			$this->makeSureSessionIsStarted();
			if(isset($_SESSION[self::$msgString])) {
				$msg = $_SESSION[self::$msgString];
				unset($_SESSION[self::$msgString]);
				return $msg;
			}
			else return null;
		}
		
		/*
		 * @return string Hämtar användarnamn från Post.
		 */
		public function GetUsername() {
			return $_POST[self::$userNameString];
		}
		
		/*
		 * @return string Hämtar lösenord från Post.
		 */
		public function GetPassword() {
			return $_POST[self::$passwordString];
		}
		
		/*
		 * @param string $usernameString: ett användarnamn.
		 * @param string $passwordString: ett lösenord.
		 * @param int $expire: när kakan ska gå ut.
		 */
		public function SetLoginCookie($usernameString, $passwordString, $expire) {
			setcookie(self::$userNameString, $usernameString, $expire,self::$localPath,self::$domain,FALSE,TRUE);
			setcookie(self::$passwordString, $passwordString, $expire,self::$localPath,self::$domain,FALSE,TRUE);	
		}
		
		public function SetLoggedInUser(User $user) {
			$this->makeSureSessionIsStarted();
			$_SESSION[self::$inSessionUser] = $user;
		}
		
		// @return string UserAgent för klienten.
		public function GetUserAgent() {
			return $_SERVER['HTTP_USER_AGENT'];
		}
		
		// @return bool om användaren klickat i stayloggedin checkboxen i formen han skickar med post.
		public function UserClickedStayLoggedIn() {
			if(isset($_POST[self::$postVarForCookies])) {
				return TRUE;
			}
			else {
				return FALSE;
			}
		}
		
		public function GetPasswordFromCookie() {
			return $_COOKIE[self::$passwordString];
		}
		
		public function GetUsernameFromCookie() {
			return $_COOKIE[self::$userNameString];
		}
		
		// Loggar ut och tar bort kakor om kakor finns.
		public function Logout() {
			$this->makeSureSessionIsStarted();
			unset($_SESSION[self::$inSessionUser]);
			$this->SetMsgForUser("Du har loggats ut.");
			if(count($_COOKIE) > 1) {
				$expire = time()-3600;
				setcookie(self::$userNameString, "", $expire,self::$localPath,self::$domain,FALSE,TRUE);
				setcookie(self::$passwordString, "", $expire,self::$localPath,self::$domain,FALSE,TRUE);
				}
		}
		
		// @return bool: Om användaren vill logga in med kaka.
		public function DoesUserWantToLoginWithCookie() {
			if(isset($_COOKIE[self::$userNameString]) && isset($_COOKIE[self::$passwordString])) {
				return TRUE;
			}
			return FALSE;
		}
		
		// @return bool: Om användaren vill logga in.
		public function DoesUserWantToLogin() {
			if($_SERVER['QUERY_STRING'] == self::$queryStringForLogin) {
				return TRUE;
			}
			return FALSE;
		}
		
		// @return bool: Om användaren vill logga ut.
		public function DoesUserWantToLogout() {
			if($_SERVER['QUERY_STRING'] == self::$queryStringForLogout) {
				return TRUE;
			}
			return FALSE;
		}
		
		// @return User: Den inloggade användaren eller null om ingen inloggad användare finns.
		public function GetUser() {
			$this->makeSureSessionIsStarted();
			return $_SESSION[self::$inSessionUser];
		}
		
		
	}