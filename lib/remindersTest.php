<?php
/***
* developer: sampath liyanage
* phone no: +94778514847
*/

include_once "authDb.php";
include_once "RemindersDb.php";
include_once "Reminders.php";

class Reminders_DBTest  extends PHPUnit_Framework_TestCase{
        static $RemindersDb;
        static $authDb;
        static $userId;
        
        public static function setUpBeforeClass(){
                //empty the RemindList table
		self::$RemindersDb= new Reminders_DB;
		$result=self::$RemindersDb->emptyDb();
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
        }
        
        public static function tearDownAfterClass(){
                $result=self::$authDb->deleteAllUsers();
		self::assertTrue($result);
        }
        
        
        function testCreateRemindList(){
		
		//adding new RemindLists
		
		$result= self::$RemindersDb->createRemindList(self::$userId, 'my RemindList 1', 'descriptn 1');
		$this->assertTrue($result);
		$result= self::$RemindersDb->createRemindList(self::$userId, 'my RemindList 2', 'descriptn 2');
		$this->assertTrue($result);
		
		//getting the created Remind List
		$result= self::$RemindersDb->getLatestRemindList(self::$userId);
		$row = $result->fetch_array(MYSQLI_NUM);
		$this->assertTrue($row[1]==self::$userId);
		$this->assertTrue($row[2]=='my RemindList 2');
		$this->assertTrue($row[3]=='descriptn 2');
		
	}
	
	

	public function testgetRemindListsOfOwner(){
		$result= self::$RemindersDb->getRemindListsOfOwner(self::$userId);
		while ($row = $result->fetch_array(MYSQLI_NUM))
                {       
                         $id=$row[0];
                         $title=$row[2]."changed";
                         $descriptn=$row[3]."changed";
                         
                         //changing an exsisting RemindList;
		        $r= self::$RemindersDb->changeRemindList($id, $title,$descriptn);
		        $this->assertTrue($r);
		        
		        //getting the changed RemindList;
		        $r= self::$RemindersDb->getRemindListOfOwner(self::$userId, $id);
		        $row1 = $r->fetch_array(MYSQLI_NUM);
		        $this->assertTrue($row1[0]==$id);
		        
	        }
	
	}
	
	
	
	
        
        public function testcreateReminder(){
                $result= self::$RemindersDb->getRemindListsOfOwner(self::$userId);
		while ($row = $result->fetch_array(MYSQLI_NUM))
                {       
                         $id=$row[0];
                         
                        //create Reminders for RemindLists
		        $r= self::$RemindersDb->createReminder($id, 'Reminder 1', 'description 1', '2013-12-12' );
		        $this->assertTrue($r);
		        $r= self::$RemindersDb->createReminder($id, 'Reminder 2', 'description 2', '2013-12-12' );
		        $this->assertTrue($r);
	        }
        }
        
        
        
          /*
        *for testing three functions
        *tests changeReminder(x);
        *tests getReminders(x);
        *tests deletReminder(x);
        */
        public function testGetAndChangeReminders(){
                $result= self::$RemindersDb->getRemindListsOfOwner(self::$userId);
		while ($row = $result->fetch_array(MYSQLI_NUM))
                {       
                        $RemindListId=$row[0];
                        
                        //get Reminders
                        $result1= self::$RemindersDb->getReminders($RemindListId);
                        
                        while ($row1 = $result1->fetch_array(MYSQLI_NUM))
                        {       
                                 $ReminderId=$row1[0];
                                 
                                 //change an Reminder
		                 $r= self::$RemindersDb->changeReminder($ReminderId, 'Reminder - changed', 'description  changed', '2013-12-12' );
		                 $this->assertTrue($r);

		                 //deletes an Reminder
		                 $r= self::$RemindersDb->deleteReminder($ReminderId);
		                 $this->assertTrue($r);
	                }
	        }
        }
        
        
       
        

        
        public function testdeleteRemindList(){
		$result= self::$RemindersDb->getRemindListsOfOwner(self::$userId);
		while ($row = $result->fetch_array(MYSQLI_NUM))
                {       
                         $id=$row[0];
		        
		        //delete a RemindList
		        $r= self::$RemindersDb->deleteRemindList($id);
		        $this->assertTrue($r);
	        }
	
	}
}



/**
*test functions related to RemindLists in Reminders.php
*/
class RemindListTest  extends PHPUnit_Framework_TestCase{
        static $RemindersDb;
        static $authDb;
        static $userId;
        
        public static function setUpBeforeClass(){
                //empty the RemindList table
		self::$RemindersDb= new Reminders_DB;
		$result=self::$RemindersDb->emptyDb();
		self::assertTrue($result);
		
		//empty the user table
		self::$authDb= new Auth_DB;
		$result= self::$authDb->deleteAllUsers();
		self::assertTrue($result);
		
		//create a user
		$result=self::$authDb->createUser('sampath', 'password', 'plbsam@gmail.com');
		self::assertTrue($result);
                
                //store the user's id
                $result= self::$authDb->getUserId('sampath',md5('password'));
		$row = $result->fetch_array(MYSQLI_NUM);
		self::$userId= $row[0];
        }
        
        public static function tearDownAfterClass(){
                $result=self::$authDb->deleteAllUsers();
		self::assertTrue($result);
		
		$result=self::$RemindersDb->emptyDb();
		self::assertTrue($result);
        }
        
        public function testRemindList(){
                //create a new RemindList
                $RemindListManager=new RemindListManager(self::$userId);
                $RemindList=new RemindList_temp(self::$userId, 'testing RemindList 1', 'this RemindList is created for testing');
                $result=$RemindListManager->createRemindList($RemindList);
                $this->assertTrue($result);
                
                //get created Remind list
                $RemindList=$RemindListManager->getLatestRemindList();
                
                //publish the Remind list
                $RemindListManager->publishRemindList($RemindList->id);
                $RemindList=$RemindListManager->getRemindListOwned($RemindList->id);
                
                $this->assertTrue($RemindList->publicKey!=NULL);
                $this->assertTrue($RemindList->userId==self::$userId);
                $this->assertTrue($RemindList->userId==self::$userId);
		$this->assertTrue($RemindList->title=='testing RemindList 1');
		$this->assertTrue($RemindList->description=='this RemindList is created for testing');
                
                
                
                
                //add an Reminder to a RemindList
                $RemindListManager=new RemindListManager(self::$userId);
                $RemindLists=$RemindListManager->getRemindListsOwned();
                $RemindList=$RemindLists[0];
                $this->assertTrue(!($RemindList===false));
                $this->assertTrue($RemindList->id!=null);
                $RemindListId=$RemindList->id; //id should be saved for get the Reminders of that RemindList next
                $this->assertTrue($RemindList->userId==self::$userId);
                $this->assertTrue($RemindList->title=='testing RemindList 1');
                $this->assertTrue($RemindList->description=='this RemindList is created for testing');
                $this->assertTrue($RemindList->dateCreated!=null);
                $this->assertTrue($RemindList->dateUpdated!=null);
                
                $Reminder=new Reminder_temp(self::$userId, "mid evolution", "get ready for the evolution", "2013-09-20");
                $result=$RemindList->addReminder($Reminder);
                self::assertTrue(!$result->isError);
                
                //read the Reminders
                $RemindListManager=new RemindListManager(self::$userId);
                $RemindList=$RemindListManager->getRemindListOwned($RemindListId);
                $Reminders=$RemindList->getReminders();
                $Reminder=$Reminders[0];
                $this->assertTrue(!($Reminder===false));
                $this->assertTrue($Reminder->id!=null);
                $ReminderId=$Reminder->id; //id should be saved for get the Reminders of that RemindList next
                $this->assertTrue($Reminder->name=="mid evolution");
                $this->assertTrue($Reminder->description=="get ready for the evolution");
                $this->assertTrue($Reminder->dateTime=="2013-09-20 00:00:00");
                
                
                //read an Reminder
                $Reminder1=$RemindList->getReminder($ReminderId);
                $this->assertTrue($Reminder1->name=="mid evolution");
                $this->assertTrue($Reminder1->description=="get ready for the evolution");
                $this->assertTrue($Reminder1->dateTime=="2013-09-20 00:00:00");
                
                
                //change the Reminder
                $RemindListManager=new RemindListManager(self::$userId);
                $RemindList=$RemindListManager->getRemindListOwned($RemindListId);
                $Reminders=$RemindList->getReminders();
                $Reminder=$Reminders[0];
                $Reminder->description="get ready for the evolution(postponed a week)";
                $Reminder->dateTime="2013-09-27 00:00:00";
                $RemindList->changeReminder($Reminder);
                $Reminders=$RemindList->getReminders();
                $Reminder=$Reminders[0];
                $this->assertTrue(!($Reminder===false));
                $this->assertTrue($Reminder->id!=null);
                $ReminderId=$Reminder->id; //id should be saved for get the Reminders of that RemindList next
                $this->assertTrue($Reminder->name=="mid evolution");
                $this->assertTrue($Reminder->description=="get ready for the evolution(postponed a week)");
                $this->assertTrue($Reminder->dateTime=="2013-09-27 00:00:00");
                
                //change the RemindList
                $RemindListManager=new RemindListManager(self::$userId);
                $RemindList=$RemindListManager->getRemindListOwned($RemindListId);
                $RemindList->title="testing RemindList 1 changed";
                $RemindList->description="description changed";
                $RemindList->dateExpire='2013-09-27 00:00:00';
                $RemindListManager->changeRemindList($RemindList);
                $RemindLists=$RemindListManager->getRemindListsOwned();
                $RemindList=$RemindLists[0];
                $this->assertTrue(!($RemindList===false));
                $this->assertTrue($RemindList->id!=null);
                $RemindListId=$RemindList->id; //id should be saved for get the Reminders of that RemindList next
                $this->assertTrue($RemindList->userId==self::$userId);
                $this->assertTrue($RemindList->title=="testing RemindList 1 changed");
                $this->assertTrue($RemindList->description=="description changed");
                $this->assertTrue($RemindList->dateCreated!=null);
                $this->assertTrue($RemindList->dateUpdated!=null);
        }
}

?>
