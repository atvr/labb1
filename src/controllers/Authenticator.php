<?php
	/*include_once "../views/HTMLPage.php";
	include_once "../models/User.php";*/
	include_once "src/views/HTMLPage.php";
	include_once "src/models/User.php"; //??? Varför funkar det senare och inte det första? Den relativa sökvägen borde vara den rätta.
	include_once "src/models/MysqlConnectionDetails.php";
	include_once "src/controllers/LoginValidator.php";
	
	class Authenticator {
		// @var HTMLPage Huvudvyn för applikationen.
		private $mainView; 
		//@var LoginValidator Valideringskontroll för att kolla användare mot databas.
		private $loginValidator;
		
		function __construct(HTMLPage $htmlPage, MysqlConnectionDetails $conDetails) {
			$this->mainView = $htmlPage;
			$this->loginValidator = new LoginValidator($conDetails);
		}
		
		//Kollar skickade uppgifter och loggar in en användare.
	    	public function Login() {
	    		$username = $this->mainView->GetUsername();
			$password = $this->mainView->GetPassword();
			
	    		if($username == null) {
	    			$this->mainView->SetMsgForUser("Inloggning misslyckades. Användarnamn saknas.");
					return;
	    		}
				elseif ($password == null) {
					$this->mainView->SetMsgForUser("Inloggning misslyckades. Lösenord saknas.");
					return;
				}

	    		if(!$this->loginValidator->IsUserValid($username, $password)) {
	    			$this->mainView->SetMsgForUser("Inloggningen misslyckades. Felaktigt användarnamn eller lösenord.");
	    		}
				else {
					$user = new User($username, $this->mainView->GetUserAgent());
					$this->mainView->SetLoggedInUser($user);
					$this->mainView->SetMsgForUser("Inloggningen lyckades. Inloggad som $username.");
					if($this->mainView->UserClickedStayLoggedIn()) {
						$password = $this->loginValidator->MakeHash($password);
						$hashed = $this->loginValidator->MakeHash($password . $this->mainView->GetUserAgent());
						$expire = time()+60*60*24*3;
						$this->mainView->SetLoginCookie($username, $hashed, $expire);
						$this->loginValidator->AddCookie($username, $expire);
					}
				}
	    	}
			//Kollar cookie och loggar in användare.
			public function LoginWithCookie() {
				$cookieUsername = $this->mainView->GetUsernameFromCookie();
				$cookiePassword = $this->mainView->GetPasswordFromCookie();
				//Försvårar inloggning genom stöld av cookies genom att tvinga den obehörige att använda samma useragent.	
				$userAgent = $this->mainView->GetUserAgent();
				$authenticated = $this->loginValidator->IsCookieValid($cookieUsername, $cookiePassword, $userAgent);
				if($authenticated) {
					$user = new User($cookieUsername, $userAgent);
					$this->mainView->SetLoggedInUser($user);
					$this->mainView->SetMsgForUser("Inloggning lyckades via cookies.");
				}
			}
			
			// Kör igång kontrollen och kollar loginstatus och eventuell postad info.
			public function Run() {
				if(!$this->mainView->IsUserLoggedIn()) {
					//Logga in med kaka
					if($this->mainView->DoesUserWantToLoginWithCookie()) {
						$this->LoginWithCookie();
					}
					//Logga in
					if($this->mainView->DoesUserWantToLogin()) {
						$this->Login();
					}
				}
				//Logga ut
				elseif($this->mainView->DoesUserWantToLogout()) {
						$this->mainView->Logout();
						return;
				}
				//Försvåra obehörigs användning av sessionskakan.
				$user = $this->mainView->GetUser();
				if($user != null) {
					$agent = $user->UserAgent;
					if($this->mainView->GetUserAgent() != $agent) {
						$this->mainView->Logout();
					}
				}
			}
	    }
	
?>