
<?php
include_once "eventsDb.php";




/**
*represents a reminder
*/	

class Schedule_reminder_temp{
	private $eventId;
	public $dateTime;
	
	
	public function __construct($eventId, $dateTime){
	        $this->eventId=$eventId;
                $this->dateTime=$dateTime;
        }	
}

class Schedule_reminder extends Schedule_reminder_temp{
        public $id;
	
	public function __construct($eventId, $id, $dateTime){
	        $this->id=$id;
                $this->dateTime=$dateTime;
                parent::__construct($eventId, $dateTime);
        }	
}


/**
*represents an event
*/	
class Schedule_event_temp{
	private $scheduleId;
	public $name;
	public $description;
	public $dateTime;
	
	public function __construct($scheduleId, $name, $description, $dateTime){
	        $this->scheduleId=$scheduleId;
                $this->name=$name;
                $this->description=$description;
                $this->dateTime=$dateTime;
        }
}


class Schedule_event extends Schedule_event_temp{
	public $id;
	
	public function __construct($scheduleId, $id, $name, $description, $dateTime){
                $this->id=$id;
                parent::__construct($scheduleId, $name, $description, $dateTime);
        }
        
        public function getReminders($eventId){
                $eventsDb=new Events_DB;
                $result=$eventsDb->getSchReminders($eventId);
                if ($result===false){
                        return false;
                } else{
                        $i=0;
                        while ($row = $result->fetch_array(MYSQLI_NUM))
                        {       
                                 $reminders[$i]=new Schedule_reminder($row[1], $row[0], $row[2],$row[3]);
                                 $i=$i+1;
                        }
                        return $reminders;
                }
        }
        
        public function addReminder($reminder){
                $eventsDb=new Events_DB;
                $result=$eventsDb->addSchReminder($this->id, $reminder->dateTime);
                return $result;
        }
        
        public function removeReminder($id){
                $eventsDb=new Events_DB;
                $result=$eventsDb->deleteSchReminder($id);
                return $result;
        }
	
}


/**
*represent a schedule
*/
class Schedule_temp{
	public $userId;
	public $title;
	public $description;
	
	public function __construct($userId, $title, $description){
                $this->userId=$userId;
                $this->title=$title;
                $this->description=$description;
        }      
}

class Schedule extends Schedule_temp{
	public $id;
	public $dateCreated;
	public $dateUpdated;
	
	public function __construct($userId, $id, $title, $description, $dateCreated, $dateUpdated){
                $this->id=$id;
                $this->dateCreated=$dateCreated;
                $this->dateUpdated=$dateUpdated;
                
                parent::__construct($userId, $title, $description);
        }
        
        public function getEvents(){
                $eventsDb=new Events_DB;
                $result=$eventsDb->getSchEvents($this->id);
                if ($result===false){
                        return false;
                } else{
                        $i=0;
                        while ($row = $result->fetch_array(MYSQLI_NUM))
                        {       
                                 $events[$i]=new Schedule_event($row[1],$row[0],$row[2],$row[3],$row[4]);
                               
                                 $i=$i+1;
                        }
                        return $events;
                }
        }
        
        public function addEvent($event){
                $eventsDb=new Events_DB;
                $result=$eventsDb->createSchEvent($this->id, $event->name, $event->description, $event->dateTime);
                return $result;
        }
        
        public function removeEvent($id){
                $eventsDb=new Events_DB;
                $result=$eventsDb->deleteSchEvent($id);
                return $result;
        }
        
        public function changeEvent($event){
                $eventsDb=new Events_DB;
                $result=$eventsDb->changeSchEvent($event->id, $event->name, $event->description, $event->dateTime);
                return $result;
        }
        
        public function subcribe($userId){
                $eventsDb=new Events_DB;
                $result=$eventsDb->subcribeSchedule($this->id, $userId);
                return $result;
        }
        
        public function unSubcribe($userId){
                $eventsDb=new Events_DB;
                $result=$eventsDb->unSubcribeSchedule($this->id, $userId);
                return $result;
        }
        
        public function getSubcriptions(){
                $eventsDb=new Events_DB;
                $result=$eventsDb->getSchSubcriptionsBySid($this->id);
                if ($result===false){
                        return false;
                } else{
                        $i=0;
                        while ($row = $result->fetch_array(MYSQLI_NUM))
                        {       
                                 $this->subcriptions[$i]= $row[1];
                                 
                                 $i=$i+1;
                        }
                        return $this->subcriptions;
                }
        }
        
}

/**
*represent the report of a function call
*/
class Report{
        public $isError=true;
        public $report="Input Error";
}
        
/**
*represent a schedule manager
*/
class ScheduleManager{
       
        
        private $userId;
        
        public function __construct($userId){
                $this->userId=$userId;
        }
        
        public function createSchedule($schedule){
                $report=new Report;
                
                //check errors in "title" field
                if(trim($schedule->title)==''){
                        $report->report="title should not be empty";
                        return $report; 
                }
                
                $eventsDb=new Events_DB;
                $result=$eventsDb->createSchedule($this->userId, $schedule->title, $schedule->description);
                if ($result){
                        $report->isError=false;
                }
                return $report;
        }
        
        public function changeSchedule($schedule){
                $eventsDb=new Events_DB;
                $result=$eventsDb->changeSchedule($schedule->id, $schedule->title, $schedule->description);
                return $result;
        }
        
        public function deleteSchedule($scheduleId){
                $eventsDb=new Events_DB;
                $result=$eventsDb->deleteSchedule($scheduleId);
                return $result;
        }
        
        
         public function getSchedulesOwned(){
                $eventsDb=new Events_DB;
                $result=$eventsDb->getSchedulesOfOwner($this->userId);
                if ($result===false){
                        return false;
                } else{
                        $i=0;
                        while ($row = $result->fetch_array(MYSQLI_NUM))
                        {       
                                 $this->schedules[$i]= new Schedule($row[1], $row[0],$row[2],$row[3],$row[4],$row[5]);
                                 $i=$i+1;
                        }
                        return $this->schedules;
                }
        }
        
        public function getScheduleOwned($scheduleId){
                $eventsDb=new Events_DB;
                $result=$eventsDb->getScheduleOfOwner($this->userId, $scheduleId);
                if ($result===false){
                        return false;
                } else{
                       
                       $row = $result->fetch_array(MYSQLI_NUM);
                       $schedule= new Schedule($row[1], $row[0],$row[2],$row[3],$row[4],$row[5]);
                       return $schedule;
                }
        }
        
        public function getScheduleIdsSubcribed(){
                $eventsDb=new Events_DB;
                $result=$eventsDb->getSchSubcriptionsByUid($this->userId);
                if ($result===false){
                        return false;
                } else{
                        $i=0;
                        while ($row = $result->fetch_array(MYSQLI_NUM))
                        {       
                                 $this->sheduleIds[$i]= $row[1];
                                 $i=$i+1;
                        }
                        return $this->sheduleIds;
                }
        }
}

?>