<?php

include_once "authDb.php";
include_once "eventsDb.php";

class Events_DBTest  extends PHPUnit_Framework_TestCase{
        static $eventsDb;
        static $authDb;
        static $userId;
        
        public static function setUpBeforeClass(){
                //empty the schedule table
		self::$eventsDb= new Events_DB;
		$result=self::$eventsDb->emptyDb();
		self::assertTrue($result);
		
		//empty the user table
		self::$authDb= new Auth_DB;
		$result= self::$authDb->deleteAllUsers();
		self::assertTrue($result);
		
		//create a user
		$result=self::$authDb->createUser('sampath', 'password', 'plbsam@gmail.com');
		self::assertTrue($result);
                
                //store the user's id
                $result= self::$authDb->getUserId('sampath');
		$row = $result->fetch_array(MYSQLI_NUM);
		self::$userId= $row[0];
        }
        
        public static function tearDownAfterClass(){
                $result=self::$authDb->deleteAllUsers();
		self::assertTrue($result);
        }
        
        
        function testCreateSchedule(){
		
		//adding new schedules
		
		$result= self::$eventsDb->createSchedule(self::$userId, 'my schedule 1','2013-12-12');
		$this->assertTrue($result);
		$result= self::$eventsDb->createSchedule(self::$userId, 'my schedule 2','2013-12-13');
		$this->assertTrue($result);
	}
	
	

	public function testgetSchedulesOfOwner(){
		$result= self::$eventsDb->getSchedulesOfOwner(self::$userId);
		while ($row = $result->fetch_array(MYSQLI_NUM))
                {       
                         $id=$row[0];
                         $title=$row[2]."changed";
                         $date=$row[5];
                         
                         //changing an exsisting schedule;
		        $r= self::$eventsDb->changeSchedule($id, $title, $date);
		        $this->assertTrue($r);
		        
	        }
	
	}
	
	/*
	*for testing 3 functions
	*tests subcribeSchedule();
	*tests unSubcribeSchedule();
	*tests getSchSubcriptions();
	*/
        public function testSchSubcriptions(){
                $result= self::$eventsDb->getSchedulesOfOwner(self::$userId);
		while ($row = $result->fetch_array(MYSQLI_NUM))
                {       
                         $id=$row[0];
                         
                         //subcribe schedule;
		        $r= self::$eventsDb->subcribeSchedule(self::$userId, $id);
		        $this->assertTrue($r);
		        
		        //get subciptions
		        $r= self::$eventsDb->getSchSubcriptions(self::$userId);
		        while ($row1 = $r->fetch_array(MYSQLI_NUM)){
		                $shdId=$row[0];
		                $this->assertTrue($shdId==$id);
		        }
		       
		        //unsubcribe schelule
		        $r= self::$eventsDb->unSubcribeSchedule(self::$userId, $id);
		        $this->assertTrue($r);
		        
	        }
        }
        
        public function testcreateSchEvent(){
                $result= self::$eventsDb->getSchedulesOfOwner(self::$userId);
		while ($row = $result->fetch_array(MYSQLI_NUM))
                {       
                         $id=$row[0];
                         
                        //create events for schedules
		        $r= self::$eventsDb->createSchEvent($id, 'event 1', 'description 1', '2013-12-12' );
		        $this->assertTrue($r);
		        $r= self::$eventsDb->createSchEvent($id, 'event 2', 'description 2', '2013-12-12' );
		        $this->assertTrue($r);
	        }
        }
        
        
        
          /*
        *for testing three functions
        *tests changeSchEvent(x);
        *tests getSchEvents(x);
        *tests deletSchEvent(x);
        */
        public function testGetAndChangeEvents(){
                $result= self::$eventsDb->getSchedulesOfOwner(self::$userId);
		while ($row = $result->fetch_array(MYSQLI_NUM))
                {       
                        $scheduleId=$row[0];
                        
                        //get events
                        $result1= self::$eventsDb->getSchEvents($scheduleId);
                        
                        while ($row1 = $result1->fetch_array(MYSQLI_NUM))
                        {       
                                 $eventId=$row1[0];
                                 
                                 //change an event
		                 $r= self::$eventsDb->changeSchEvent($eventId, 'event - changed', 'description  changed', '2013-12-12' );
		                 $this->assertTrue($r);

		                 //deletes an event
		                 $r= self::$eventsDb->deleteSchEvent($eventId);
		                 $this->assertTrue($r);
	                }
	        }
        }
        
        
        /*
        *for testing 3 functions
        *tests changeSchReminder(x);
        *tests getSchReminders(x);
        *tests deletSchReminder(x);
        */
        public function testGetChangeDeletReminders(){
                //create some entries in database
                $this->testcreateSchEvent();
                
                $result= self::$eventsDb->getSchedulesOfOwner(self::$userId);
		while ($row = $result->fetch_array(MYSQLI_NUM))
                {       
                        $scheduleId=$row[0];
                        
                        //get events
                        $result1= self::$eventsDb->getSchEvents($scheduleId);
                        
                        
                        while ($row1 = $result1->fetch_array(MYSQLI_NUM))
                        {       
                                 $eventId=$row1[0];
                                 
                                 
                                 //add a reminder
		                 $r= self::$eventsDb->addSchReminder($eventId, '2013-12-12' );
		                 $this->assertTrue($r);
		                 
		                 //get reminders
                                $result2= self::$eventsDb->getSchReminders($eventId);
                                
                                while ($row2 = $result2->fetch_array(MYSQLI_NUM))
                                {       
                                         $reminderId=$row2[0];
                                         
                                         //delete a reminder
	                                 $r= self::$eventsDb->deleteSchReminder($reminderId);
	                                 $this->assertTrue($r);
	                        }
	                }
	        }
        }
        
        

        
        public function testdeleteSchedule(){
		$result= self::$eventsDb->getSchedulesOfOwner(self::$userId);
		while ($row = $result->fetch_array(MYSQLI_NUM))
                {       
                         $id=$row[0];
		        
		        //delete a schedule
		        $r= self::$eventsDb->deleteSchedule($id);
		        $this->assertTrue($r);
	        }
	
	}
}




?>
