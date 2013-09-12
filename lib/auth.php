<?php

/***++++++
* developer: sampath liyanage
* phone no: +94778514847
*/


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
	
	/*
	*validate a user name chosen when sign up
	*@input=> username:string
	*/
	private function validateUname($username){
	        if ($username==''){
	                $this->report->username=false;
	                $this->report->report="username shouldn't be empty";
	                $this->report->isError=true;
	        } else{
		        $this->report->username=true;
		}
	}
	
	/*
	*validate a password chosen when sign up
	*@input=> password:string
	*/
	private function validatePass($passwd){
	         if ($passwd==''){
	                $this->report->username=false;
	                $this->report->report="password shouldn't be empty";
	                $this->report->isError=true;
	        } else{
		        $this->report->username=true;
		}
	}
	
	/*
	*validate an email chosen when sign up
	*@input=> password:string
	*/
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
	
	/*
	*authenticate a username and password
	*@input=> username:string, password:string
	*@output=> bool: if authenticated or not
	*/
	public function authWithPasswd($username,$passwd){
		$passwdHash=md5($passwd);
		return $this->authWithPwHash($username,$passwdHash);
	} 
	
	/*
	*authenticate a username and password hash
	*@input=> username:string, password hash:string
	*@output=> bool: if authenticated or not
	*/
	public function authWithPwHash($username,$passwdHash){
		return $this->authDb->authenticate($username,$passwdHash);
	}
	
	/*
	*get user id from a username and password hash
	*@input=> username:string, password hash:string
	*@output=> user id:int
	*/
	public function getUserId($username,$passwdHash){
	        $result= $this->authDb->getUserId($username,$passwdHash);
	        $row = $result->fetch_array(MYSQLI_NUM);
		return $row[0];
	}
}
?>
