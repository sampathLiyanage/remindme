<?php
/***
* developer: sampath liyanage
* phone no: +94778514847
*/

include_once "authDb.php";
include_once 'subscriptionDb.php';
include_once 'subscription.php';
include_once 'eventsDb.php';

/**
 * tests for the class Subscription_DB in subscriptionDb.php
 */
class Subscription_DBTest extends PHPUnit_Framework_TestCase{
	static $scrDb;
	static $authDb;
	static $userId;
	static $tdListId;
	
	public static function setUpBeforeClass(){
		//empty the todoList table
		self::$scrDb= new Subscription_DB();
		$result=self::$scrDb->emptyDb();
		self::assertTrue($result);
	
		//empty the user table
		self::$authDb= new Auth_DB;
		$result= self::$authDb->deleteAllUsers();
		self::assertTrue($result);
	
		//create a user
		$result=self::$authDb->createUser('sampath', 'password', 'plbsam@gmail.com');
		self::assertTrue($result);
	
		//store the user's id
		$result= self::$authDb->getUserId('sampath', md5('password'));
		$row = $result->fetch_array(MYSQLI_NUM);
		self::$userId= $row[0];
		
		//create todo lists and save todolist id
		$eventsDb= new Events_DB;
		$result= $eventsDb->createTodoList(self::$userId, 'my todoList 1', 'descriptn 1');
		self::assertTrue($result);
                $result=$eventsDb->getLatestTodoList(self::$userId);
		$row = $result->fetch_array(MYSQLI_NUM);
		self::$tdListId=$row[0];
                
                //publish the todo list
                $result=$eventsDb->publishTodoList(self::$userId, self::$tdListId, "abcde12345");
                self::assertTrue($result);
	}
	
	
	
	/*
	*for testing 3 functions
	*tests subcribeTodoList();
	*tests unSubcribeTodoList();
	*tests getTodoSubcriptions();
	*/
        public function testTodoSubcriptions(){  
                        $id=self::$tdListId;
                        
                        
                         //subcribe todoList;
                        $r=self::$scrDb->getTdKeyFromId(self::$tdListId);
                        $row = $r->fetch_array(MYSQLI_NUM);
                        $key=$row[0];
                        $r=self::$scrDb->getTdIdFromKey($key);
                        $row = $r->fetch_array(MYSQLI_NUM);
                        $idOfKey=$row[0];
		        $r= self::$scrDb->subcribeTodoList(self::$userId, $idOfKey);
		        $this->assertTrue($r);
		        
		        //get subciptions by userId
		        $r= self::$scrDb->getTodoSubcriptionsByUid(self::$userId);
		        while ($row1 = $r->fetch_array(MYSQLI_NUM)){
		                $shdId=$row1[1];
		                $this->assertTrue($shdId==$id);
		                
		                ////get subciptions by todoListId
		                $r1= self::$scrDb->getTodoSubcriptionsBySid($shdId);
		                while ($row2 = $r1->fetch_array(MYSQLI_NUM)){
		                        $userId=$row2[0];
		                        $this->assertTrue(self::$userId==$userId);
		                }
		        }
		       
		        //unsubcribe todoelule
		        $r= self::$scrDb->unSubcribeTodoList(self::$userId, $id);
		        $this->assertTrue($r);
		        
	       
        }
	
	public static function tearDownAfterClass(){
		$result=self::$authDb->deleteAllUsers();
		self::assertTrue($result);
	}
}

/**
 * tests for the class SubscriptionManager in subscription.php
 */
class TdListSubscribeHandlerTest extends PHPUnit_Framework_TestCase{
	static $scrMan;
	static $authDb;
	static $userId;
	static $tdListId;
	
	public static function setUpBeforeClass(){
		//empty the todoList table
		$scrDb= new Subscription_DB();
		$result=$scrDb->emptyDb();
		self::assertTrue($result);
		
		
		
		//empty the user table
		self::$authDb= new Auth_DB;
		$result= self::$authDb->deleteAllUsers();
		self::assertTrue($result);
	
		//create a user
		$result=self::$authDb->createUser('sampath', 'password', 'plbsam@gmail.com');
		self::assertTrue($result);
	
		//store the user's id
		$result= self::$authDb->getUserId('sampath', md5('password'));
		$row = $result->fetch_array(MYSQLI_NUM);
		self::$userId= $row[0];
	
		//create todo lists and save todolist id
		$eventsDb= new Events_DB;
		$result= $eventsDb->createTodoList(self::$userId, 'my todoList 1', 'descriptn 1');
		self::assertTrue($result);
		$result=$eventsDb->getLatestTodoList(self::$userId);
		$row = $result->fetch_array(MYSQLI_NUM);
		self::$tdListId=$row[0];
                
                //publish the todo list
                $result=$eventsDb->publishTodoList(self::$userId, self::$tdListId, "abcde12345");
                self::assertTrue($result);
	}
	
		/*
		*for testing 3 functions
		*tests subcribeTodoList();
		*tests unSubcribeTodoList();
		*tests getTodoSubcriptions();
		*/
        public function testTodoSubcriptions(){  
        	//create subscriptionHandler
        	self::$scrMan= new TdListSubscribeHandler(self::$userId);
        	
                $id=self::$tdListId;
                 //subcribe todoList;
		        $r= self::$scrMan->subscribe("abcde12345");
		        $this->assertTrue($r);
		        
		        //get subciptions by userId
		        $r= self::$scrMan->getSubscribedTdLists();
		        $this->assertTrue($r!=false);
		        while ($row1 = $r->fetch_array(MYSQLI_NUM)){
		                $shdId=$row1[1];
		                $this->assertTrue($shdId==$id);
		                
		                ////get subciptions by todoListId
		                $r1= self::$scrMan->getSubscribedUsers($id);
		                while ($row2 = $r1->fetch_array(MYSQLI_NUM)){
		                        $userId=$row2[0];
		                        $this->assertTrue(self::$userId==$userId);
		                }
		        }
		       
		        //unsubcribe todoelule
		        $r= self::$scrMan->unsubscribe( $id);
		        $this->assertTrue($r);
		        
	       
        }
         
		public static function tearDownAfterClass(){
			$result=self::$authDb->deleteAllUsers();
			self::assertTrue($result);
		}
}
