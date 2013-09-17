<?php
    class User {
    	/* Onödig klass egentligen. Särskilt variabeln för lösenord. Men tänker mig att man kanske ska utveckla
    	   applikationen vidare. Läste ett meddelande att man behövde en userklass i handledningschatten. Den gör iaf
		   ingen skada. Kanske är det bad practise att ha lösenord i plaintext i applikationens sessionsvariabel utifall 
		   att obehörig lyckas injicera egen kod i applikationen. Feedback? */
		   
		public $Username;
		public $Password;
		
		public function __construct($username, $password) {
			$this->Username = $username;
			$this->Password = $password;
		}
		
		
    }
?>