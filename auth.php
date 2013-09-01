
<?php
include_once "authDb.php";

/**
*to return as feedback for signup data inputs
*/
class SignupReport{
		public $username;
		public $password;
		public $email;
		public $gToken;
		public $isError;
		public $report;
	}


/**
*controls user creations
*/	
class UserCreator{
	private $report;
	
	/*
	*to create a user
	*@input-> username:string, password:string
	*@output-> singup_report:SingupReport
	*/
	public function createUser($username,$passwd,$email){
		$this->report= new SignupReport;
		$this->report->isError=false;
		$this->validateUname($username); 
		$this->validatePass($passwd);
		$this->validateEmail($email);
		
		$authDb= new Auth_DB;
		//check if username already exists
		if($authDb->isUserExist($username)){
			$this->report->isError=true;
			$this->report->username=false;
			$this->report->report="Username already exists";
		}
		
		//check if username already exists
		elseif($authDb->isEmailExist($email)){
			$this->report->isError=true;
			$this->report->username=false;
			$this->report->report="Email address already exists";
		}
		
		//if signup data are validated
		elseif (!($this->report->isError)){
			echo "testing";
			$result=$authDb->createUser($username,$passwd,$email);
			if (!$result){
				$this->report->isError=true;
				$this->report->username=false;
				$this->report->report="failed";
			}else{
				$this->report->report="successfull";
			}
		}
		return $this->report;
	}
	
	private function validateUname($username){
	//@@tobe implemented
		$this->report->username=true;
	}
	
	private function validatePass($passwd){
	//@@tobe implemented
		$this->report->pass=true;
	}
	
	private function validateEmail($email){
	//@@tobe implemented
		$this->report->gmail=true;
	}
	
}


/**
*controls user authentication
*/
class UserAuthenticator{
	
	private $authDb;
	public function __construct(){
		$this->authDb=new Auth_DB;
	}
	
	public function authWithPasswd($username,$passwd){
		$passwdHash=md5($passwd);
		return $this->authWithPwHash($username,$passwdHash);
	} 
	
	public function authWithPwHash($username,$passwdHash){
		return $this->authDb->authenticate($username,$passwdHash);
	}
}
?>
