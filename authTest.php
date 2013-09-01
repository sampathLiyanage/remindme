<?php

include_once "authDb.php";
include_once "auth.php";

class Auth_DBTest  extends PHPUnit_Framework_TestCase{
        static $authDb;
        
        public static function setUpBeforeClass(){
                //empty the table
		self::$authDb= new Auth_DB;
		self::$authDb->deleteAllUsers();
                
        }
        
        function testCreateUser(){
		
		/////////user exist
	        $result= self::$authDb->isUserExist('sampath');
		var_dump($result);
		
		//adding a new user
		$result= self::$authDb->createUser('sampath','password','plbsam@gmail.com');
		$this->assertTrue($result);
		
		//adding a new user with exsisting username
		$result= self::$authDb->createUser('sampath','passwrd','plbsm@gmail.com');
		$this->assertTrue(!$result);
		
		//adding a new user with exsisting email address
		$result= self::$authDb->createUser('samath','paswrd','plbsam@gmail.com');
		$this->assertTrue(!$result);
	}
	
	function testIsUserExist(){
	        //user exist
	        $result= self::$authDb->isUserExist('sampath');
		$this->assertTrue($result);
		
		//user doesn't exist
		$result= self::$authDb->isUserExist('sampath1');
		$this->assertTrue(!$result);
	}
	
	function testIsEmailExist(){
	        //email exist
	        $result= self::$authDb->isEmailExist('plbsam@gmail.com');
		$this->assertTrue($result);
		
		//email doesn't exist
		$result= self::$authDb->isEmailExist('plbsam1@gmail.com');
		$this->assertTrue(!$result);
	}
	
	function testAuthenticate(){
	        //correct user data
	        $pw=md5('password');
	        $result= self::$authDb->authenticate('sampath',$pw);
		$this->assertTrue($result);
		
		//incorrect username
		$pw=md5('password');
	        $result= self::$authDb->authenticate('sampath1',$pw);
		$this->assertTrue(!$result);
		
		//incorrect password
		$pw=md5('password1');
	        $result= self::$authDb->authenticate('sampath',$pw);
		$this->assertTrue(!$result);
	}
	
}

class UserCreatorTest extends PHPUnit_Framework_TestCase
{
      static $userCreator;
        
        public static function setUpBeforeClass(){
                //empty the table
                $authDb= new Auth_DB;
	        $result= $authDb->deleteAllUsers();
	        
		self::$userCreator = new UserCreator;
        }
	
	
	function testCreateUser(){
	
		
		//adding a new user
		$report= self::$userCreator->createUser('sampath','password','plbsam@gmail.com');
		$result=$report->report;
		$this->assertTrue($result=="successfull");
		
		//adding a new user with exsisting username
		$report= self::$userCreator->createUser('sampath','passwor','plbsm@mail.com');
		$result=$report->report;
		$this->assertTrue($result=="Username already exists");
		
		//adding a new user with exsisting email
		$report= self::$userCreator->createUser('sampath1','psswor','plbsam@gmail.com');
		$result=$report->report;
		$this->assertTrue($result=="Email address already exists");
		
		//adding a new user with exsisting email and username
		$report= self::$userCreator->createUser('sampath','psswor','plbsam@gmail.com');
		$result=$report->report;
		$this->assertTrue($result=="Username already exists");
		
	}
}


class UserAuthenticatorTest extends PHPUnit_Framework_TestCase{
        
        static $auth;
        
        public static function setUpBeforeClass(){
		self::$auth= new UserAuthenticator;
		//empty the table
		$authDb= new Auth_DB;
	        $result= $authDb->deleteAllUsers();
		//adding a new user
		$authDb->createUser('sampath','password','plbsam@gmail.com');
	}
	
	
        
        function testAuth(){
                //with correct username, password
                $result=self::$auth->authWithPasswd('sampath','password');
                $this->assertTrue($result);
                $result=self::$auth->authWithPwHash('sampath',md5('password'));
                $this->assertTrue($result);
                
                //with wrong username
                $result=self::$auth->authWithPasswd('sampath1','password');
                $this->assertTrue(!$result);
                $result=self::$auth->authWithPwHash('sampath1',md5('password'));
                $this->assertTrue(!$result);
                
                //with wrong password
                $result=self::$auth->authWithPasswd('sampath','password1');
                $this->assertTrue(!$result);
                $result=self::$auth->authWithPwHash('sampath',md5('password1'));
                $this->assertTrue(!$result);
                
                //with wrong username and password
                $result=self::$auth->authWithPasswd('sampath1','password1');
                $this->assertTrue(!$result);
                $result=self::$auth->authWithPwHash('sampath1',md5('password1'));
                $this->assertTrue(!$result);
        }
        
}




?>
