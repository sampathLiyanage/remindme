<?php

include_once "authDb.php";
include_once "eventsDb.php";
include_once "events.php";

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
                $result= self::$authDb->getUserId('sampath', md5('password'));
		$row = $result->fetch_array(MYSQLI_NUM);
		self::$userId= $row[0];
        }
        
        public static function tearDownAfterClass(){
                $result=self::$authDb->deleteAllUsers();
		self::assertTrue($result);
        }
        
        
        function testCreateSchedule(){
		
		//adding new schedules
		
		$result= self::$eventsDb->createSchedule(self::$userId, 'my schedule 1', 'descriptn 1');
		$this->assertTrue($result);
		$result= self::$eventsDb->createSchedule(self::$userId, 'my schedule 2', 'descriptn 2');
		$this->assertTrue($result);
	}
	
	

	public function testgetSchedulesOfOwner(){
		$result= self::$eventsDb->getSchedulesOfOwner(self::$userId);
		while ($row = $result->fetch_array(MYSQLI_NUM))
                {       
                         $id=$row[0];
                         $title=$row[2]."changed";
                         $descriptn=$row[3]."changed";
                         
                         //changing an exsisting schedule;
		        $r= self::$eventsDb->changeSchedule($id, $title,$descriptn);
		        $this->assertTrue($r);
		        
		        //getting the changed schedule;
		        $r= self::$eventsDb->getScheduleOfOwner(self::$userId, $id);
		        $row1 = $r->fetch_array(MYSQLI_NUM);
		        $this->assertTrue($row1[0]==$id);
		        
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
		        
		        //get subciptions by userId
		        $r= self::$eventsDb->getSchSubcriptionsByUid(self::$userId);
		        while ($row1 = $r->fetch_array(MYSQLI_NUM)){
		                $shdId=$row1[1];
		                $this->assertTrue($shdId==$id);
		                
		                ////get subciptions by scheduleId
		                $r1= self::$eventsDb->getSchSubcriptionsBySid($shdId);
		                while ($row2 = $r1->fetch_array(MYSQLI_NUM)){
		                        $userId=$row2[0];
		                        $this->assertTrue(self::$userId==$userId);
		                }
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
        *for testing four functions
        *tests changeSchReminder(x);
        *tests getSchReminders(x);
        *tests deletSchReminder(x);
        *tests getSchReminderUser(x);
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
                                         
                                         //check if userid is correct
		                         $result3= self::$eventsDb->getSchReminderUser($reminderId);
		                         $row3 = $result3->fetch_array(MYSQLI_NUM);
		                         $this->assertTrue($row3[0]==self::$userId);
		                         
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



/**
*test functions related to schedules in events.php
*/
class ScheduleTest  extends PHPUnit_Framework_TestCase{
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
                $result= self::$authDb->getUserId('sampath',md5('password'));
		$row = $result->fetch_array(MYSQLI_NUM);
		self::$userId= $row[0];
        }
        
        public static function tearDownAfterClass(){
                $result=self::$authDb->deleteAllUsers();
		self::assertTrue($result);
		
		$result=self::$eventsDb->emptyDb();
		self::assertTrue($result);
        }
        
        public function testSchedule(){
                //create a new schedule
                $scheduleManager=new ScheduleManager(self::$userId);
                $schedule=new Schedule_temp(self::$userId, 'testing schedule 1', 'this schedule is created for testing');
                $result=$scheduleManager->createSchedule($schedule);
                self::assertTrue(!($result->isError));
                
                
                //add an event to a schedule
                $scheduleManager=new ScheduleManager(self::$userId);
                $schedules=$scheduleManager->getSchedulesOwned();
                $schedule=$schedules[0];
                $this->assertTrue(!($schedule===false));
                $this->assertTrue($schedule->id!=null);
                $scheduleId=$schedule->id; //id should be saved for get the events of that schedule next
                $this->assertTrue($schedule->userId==self::$userId);
                $this->assertTrue($schedule->title=='testing schedule 1');
                $this->assertTrue($schedule->description=='this schedule is created for testing');
                $this->assertTrue($schedule->dateCreated!=null);
                $this->assertTrue($schedule->dateUpdated!=null);
                
                $event=new Schedule_event_temp(self::$userId, "mid evolution", "get ready for the evolution", "2013-09-20");
                $result=$schedule->addEvent($event);
                self::assertTrue($result);
                
                //read the event
                $scheduleManager=new ScheduleManager(self::$userId);
                $schedule=$scheduleManager->getScheduleOwned($scheduleId);
                $events=$schedule->getEvents();
                $event=$events[0];
                $this->assertTrue(!($event===false));
                $this->assertTrue($event->id!=null);
                $eventId=$event->id; //id should be saved for get the events of that schedule next
                $this->assertTrue($event->name=="mid evolution");
                $this->assertTrue($event->description=="get ready for the evolution");
                $this->assertTrue($event->dateTime=="2013-09-20 00:00:00");
                
                
                //change the event
                $scheduleManager=new ScheduleManager(self::$userId);
                $schedule=$scheduleManager->getScheduleOwned($scheduleId);
                $events=$schedule->getEvents();
                $event=$events[0];
                $event->description="get ready for the evolution(postponed a week)";
                $event->dateTime="2013-09-27 00:00:00";
                $schedule->changeEvent($event);
                $events=$schedule->getEvents();
                $event=$events[0];
                $this->assertTrue(!($event===false));
                $this->assertTrue($event->id!=null);
                $eventId=$event->id; //id should be saved for get the events of that schedule next
                $this->assertTrue($event->name=="mid evolution");
                $this->assertTrue($event->description=="get ready for the evolution(postponed a week)");
                $this->assertTrue($event->dateTime=="2013-09-27 00:00:00");
                
                //change the schedule
                $scheduleManager=new ScheduleManager(self::$userId);
                $schedule=$scheduleManager->getScheduleOwned($scheduleId);
                $schedule->title="testing schedule 1 changed";
                $schedule->description="description changed";
                $schedule->dateExpire='2013-09-27 00:00:00';
                var_dump($schedule->dateExpire);
                $scheduleManager->changeSchedule($schedule);
                $schedules=$scheduleManager->getSchedulesOwned();
                $schedule=$schedules[0];
                var_dump($schedule);
                $this->assertTrue(!($schedule===false));
                $this->assertTrue($schedule->id!=null);
                $scheduleId=$schedule->id; //id should be saved for get the events of that schedule next
                $this->assertTrue($schedule->userId==self::$userId);
                $this->assertTrue($schedule->title=="testing schedule 1 changed");
                $this->assertTrue($schedule->description=="description changed");
                $this->assertTrue($schedule->dateCreated!=null);
                $this->assertTrue($schedule->dateUpdated!=null);
        }
}

?>
