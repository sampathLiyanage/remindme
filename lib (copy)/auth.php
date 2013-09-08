
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
		$this->validateEmail($email);
		$this->validatePass($passwd);
		$this->validateUname($username);
		
		if ($this->report->isError){
		        return $this->report;
		}
		
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
	        if ($username==''){
	                $this->report->username=false;
	                $this->report->report="username shouldn't be empty";
	                $this->report->isError=true;
	        } else{
		        $this->report->username=true;
		}
	}
	
	private function validatePass($passwd){
	         if ($passwd==''){
	                $this->report->username=false;
	                $this->report->report="password shouldn't be empty";
	                $this->report->isError=true;
	        } else{
		        $this->report->username=true;
		}
	}
	
	private function validateEmail($email){
	         if ($email==''){
	                $this->report->username=false;
	                $this->report->report="email shouldn't be empty";
	                $this->report->isError=true;
	        } else{
		        $this->report->username=true;
		}
	}
	
}


/**
*controls user authentication
*/
class UserAuthenticator{
	
	private $authDb;
	public function __construct(){
		$this->authDb=new Auth_DB();
	}
	
	public function authWithPasswd($username,$passwd){
		$passwdHash=md5($passwd);
		return $this->authWithPwHash($username,$passwdHash);
	} 
	
	public function authWithPwHash($username,$passwdHash){
		return $this->authDb->authenticate($username,$passwdHash);
	}
	
	public function getUserId($username,$passwdHash){
	        $result= $this->authDb->getUserId($username,$passwdHash);
	        $row = $result->fetch_array(MYSQLI_NUM);
		return $row[0];
	}
}
?>