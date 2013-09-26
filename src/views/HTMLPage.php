<?php
	
    class HTMLPage {
		
		public static function GetPage() {
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
		
		
		private static function getBody() {
			$timestring = self::getLNUTimeString();
			$body = "<h1>av222bns labb 1.</h1>";
			$user = $_SESSION['LoggedInUser'];
			$msg = $_SESSION['MsgForUser'];
			if($user != null) {
				$body .= "<h2>Inloggad som $user->Username.</h2>";
				if($msg != null) {
					$_SESSION['MsgForUser'] = null;
					$body .= "<p>$msg</p>";
				}
				$body .= "<p><a href='?logout'>Logga ut.</a></p>";
			}
			else {
				$body .= "<h2>Inte inloggad.</h2>
						<fieldset>";
				if($msg != null) {
					$_SESSION['MsgForUser'] = null;
					$body .= "<p>$msg</p>";
				}
						
				$body .= "<legend>Logga in - Skriv ditt användarnamn och lösenord.</legend>
							<form action='?login' method='post' enctype='multipart/form-data'>
								<p><label>Användarnamn:</label>
								<input type=\"text\" name=\"username\"/></p>
								<p><label>Lösenord:</label>
								<input type=\"password\" name=\"password\"/></p>
								<p><label>Fortsätt vara inloggad:</label><input type='checkbox' name='stayloggedin' /></p>
								<p><input type='submit' value='Skicka'/></p>
							</form>
						</fieldset>";
			}
			$body .= 
					"
					<p>$timestring</p>";
			return $body;
		}
		
		private static function getTitle() {
			$title = "Labb 1: ";
			
			if($_SESSION['LoggedInUser'] != null) {
				$title .= "Inloggad.";
			}
			else {
				$title .= "Inte inloggad.";
			}
			
			return $title;
		}
		
		
		private static function getLNUTimeString()
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
			//return $nameWeekday . ", den " . $numDayOfMonth . " " . $nameMonth . " år " . $numYear . ". Klockan är [" . $numTimeOfDay . "].";
			return "$nameWeekday, den $numDayOfMonth $nameMonth, år $numYear. Klockan är [$numTimeOfDay].";
		}
	}