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
	
	//for both subcriptions and unsubcriptions and getting subcriptions
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
