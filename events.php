
<?php
include_once "eventsDb.php";




/**
*represents a reminder
*/	
class Schedule_reminder{
        public $id;
	public $dateTime;
	public $eventId;
	
	public function __construct($eventId, $dateTime){
                $this->dateTime=$dateTime;
        }	
}


/**
*represents an event
*/	
class Schedule_event{
	public $id;
	public $name;
	public $description;
	public $dateTime;
	private $reminders;
	
	public function __construct($name, $description, $dateTime){
                $this->name=$name;
                $this->description=$description;
                $this->dateTime=$dateTime;
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
                                 $this->reminders[$i]=new Schedule_reminder($row[1],$row[2]);
                                 $this->reminders[$i]->id=$row[0];
                                 
                                 $i=$i+1;
                        }
                        return $this->reminders;
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
class Schedule{
	public $id;
	public $userId;
	public $title;
	public $description;
	public $dateCreated;
	public $dateUpdated;
	public $dateExpire;
	private $events;
	private $subcriptions;
	
	public function __construct($userId, $title, $description, $dateExpire){
                $this->userId=$userId;
                $this->title=$title;
                $this->description=$description;
                $this->dateExpire=$dateExpire;
        }
        
        public function getEvents($scheduleId){
                $eventsDb=new Events_DB;
                $result=$eventsDb->getSchReminders($scheduleId);
                if ($result===false){
                        return false;
                } else{
                        $i=0;
                        while ($row = $result->fetch_array(MYSQLI_NUM))
                        {       
                                 $this->events[$i]=new Schedule_event($row[1],$row[2],$row[3]);
                                 $this->events[$i]->id=$row[0];
                                 
                                 $i=$i+1;
                        }
                        return $this->events;
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
*represent a schedule manager
*/
class ScheduleManager{
        private $userId;
        private $shedules;
        private $sheduleIds;
        
        public function __construct($userId){
                $this->userId=$userId;
        }
        
        public function createSchedule($schedule){
                $eventsDb=new Events_DB;
                $result=$eventsDb->createSchEvent($this->userId, $schedule->title, $schedule->description, $schedule->dateExpire);
                return $result;
        }
        
        public function changeSchedule($schedule){
                $eventsDb=new Events_DB;
                $result=$eventsDb->changeSchedule($schedule->id, $schedule->title, $schedule->description, $schedule->dateExpire);
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
                                 $this->schedules[$i]= new Schedule($row[1],$row[2],$row[3],$row[6]);
                                 $this->schedules[$i]->id=$row[0];
                                 $this->schedules[$i]->dataCreated=$row[4];
                                 $this->schedules[$i]->dataUpdated=$row[5];
                                 $i=$i+1;
                        }
                        return $this->schedules;
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
