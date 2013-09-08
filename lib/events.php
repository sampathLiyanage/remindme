
<?php
include_once "eventsDb.php";


/**
*represent the report of a function call
*/
class Report{
        public $isError=true;
        public $report="Input Error";
}



/**
*represents a reminder
*/	

class TodoList_reminder_temp{
	private $eventId;
	public $dateTime;
	
	
	public function __construct($eventId, $dateTime){
	        $this->eventId=$eventId;
                $this->dateTime=$dateTime;
        }	
}

class TodoList_reminder extends TodoList_reminder_temp{
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
class TodoList_event_temp{

	private $todoListId;
	public $name;
	public $description;
	public $dateTime;
	
	public function __construct($todoListId, $name, $description, $dateTime){
	        $this->todoListId=$todoListId;
                $this->name=$name;
                $this->description=$description;
                $this->dateTime=$dateTime;
        }
}


class TodoList_event extends TodoList_event_temp{

       
	
	public function __construct($todoListId, $id, $name, $description, $dateTime){
                $this->id=$id;
                parent::__construct($todoListId, $name, $description, $dateTime);
        }
        
        public function getReminders($eventId){
                $eventsDb=new Events_DB;
                $result=$eventsDb->getTodoReminders($eventId);
                if ($result===false){
                        return false;
                } else{
                        $i=0;
                        while ($row = $result->fetch_array(MYSQLI_NUM))
                        {       
                                 $reminders[$i]=new TodoList_reminder($row[1], $row[0], $row[2],$row[3]);
                                 $i=$i+1;
                        }
                        return $reminders;
                }
        }
        
        public function addReminder($reminder){
                $eventsDb=new Events_DB;
                $result=$eventsDb->addTodoReminder($this->id, $reminder->dateTime);
                return $result;
        }
        
        public function removeReminder($id){
                $eventsDb=new Events_DB;
                $result=$eventsDb->deleteTodoReminder($id);
                return $result;
        }
	
}


/**
*represent a todoList
*/
class TodoList_temp{
	public $userId;
	public $title;
	public $description;
	
	public function __construct($userId, $title, $description){
                $this->userId=$userId;
                $this->title=$title;
                $this->description=$description;
        }      
}

class EventReport extends Report{
           public     $name='';
           public     $description='';
           public      $date='';
}
        
class TodoList extends TodoList_temp{
        
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
                $result=$eventsDb->getTodoEvents($this->id);
                if ($result===false){
                        return false;
                } else{
                        $i=0;
                        while ($row = $result->fetch_array(MYSQLI_NUM))
                        {       
                                 $events[$i]=new TodoList_event($row[1],$row[0],$row[2],$row[3],$row[4]);
                               
                                 $i=$i+1;
                        }
                        return $events;
                }
        }
        
        public function addEvent($event){
                
                $report=new EventReport;
                //check errors in "name" field
                if(trim($event->name)==''){
                        $report->name="name should not be empty";
                        return $report; 
                }
                
                //check errors in "date" field
                if(trim($event->dateTime)==''){
                        $report->date="date should not be empty";
                        return $report; 
                }
                
                
                $eventsDb=new Events_DB;
                $result=$eventsDb->createTodoEvent($this->id, $event->name, $event->description, $event->dateTime);
                if ($result){
                        $report->isError=false;
                }
                return $report;
        }
        
        public function removeEvent($id){
                $eventsDb=new Events_DB;
                $result=$eventsDb->deleteTodoEvent($id);
                return $result;
        }
        
        public function changeEvent($event){
                $eventsDb=new Events_DB;
                $result=$eventsDb->changeTodoEvent($event->id, $event->name, $event->description, $event->dateTime);
                return $result;
        }
        
        public function subcribe($userId){
                $eventsDb=new Events_DB;
                $result=$eventsDb->subcribeTodoList($this->id, $userId);
                return $result;
        }
        
        public function unSubcribe($userId){
                $eventsDb=new Events_DB;
                $result=$eventsDb->unSubcribeTodoList($this->id, $userId);
                return $result;
        }
        
        public function getSubcriptions(){
                $eventsDb=new Events_DB;
                $result=$eventsDb->getTodoSubcriptionsBySid($this->id);
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
*represent a todoList manager
*/
class TodoListManager{
       
        
        private $userId;
        
        public function __construct($userId){
                $this->userId=$userId;
        }
        
        public function createTodoList($todoList){
                $report=new Report;
                
                //check errors in "title" field
                if(trim($todoList->title)==''){
                        $report->report="title should not be empty";
                        return $report; 
                }
                
                $eventsDb=new Events_DB;
                $result=$eventsDb->createTodoList($this->userId, $todoList->title, $todoList->description);
                if ($result){
                        $report->isError=false;
                }
                return $report;
        }
        
        public function changeTodoList($todoList){
                $eventsDb=new Events_DB;
                $result=$eventsDb->changeTodoList($todoList->id, $todoList->title, $todoList->description);
                return $result;
        }
        
        public function deleteTodoList($todoListId){
                $eventsDb=new Events_DB;
                $result=$eventsDb->deleteTodoList($todoListId);
                return $result;
        }
        
        public function getLatestTodoList(){
                $eventsDb=new Events_DB;
                $result=$eventsDb->getLatestTodoList($this->userId);
                $row = $result->fetch_array(MYSQLI_NUM);
                $this->todoLists= new TodoList($row[1], $row[0],$row[2],$row[3],$row[4],$row[5]);
                return $this->todoLists;
        }
        
        
         public function getTodoListsOwned(){
                $eventsDb=new Events_DB;
                $result=$eventsDb->getTodoListsOfOwner($this->userId);
                if ($result===false){
                        return false;
                } else{
                        $i=0;
                        while ($row = $result->fetch_array(MYSQLI_NUM))
                        {       
                                 $this->todoLists[$i]= new TodoList($row[1], $row[0],$row[2],$row[3],$row[4],$row[5]);
                                 $i=$i+1;
                        }
                        return $this->todoLists;
                }
        }
        
        public function getTodoListOwned($todoListId){
                $eventsDb=new Events_DB;
                $result=$eventsDb->getTodoListOfOwner($this->userId, $todoListId);
                if ($result===false){
                        return false;
                } else{
                       
                       $row = $result->fetch_array(MYSQLI_NUM);
                       $todoList= new TodoList($row[1], $row[0],$row[2],$row[3],$row[4],$row[5]);
                       return $todoList;
                }
        }
        
        public function getTodoListIdsSubcribed(){
                $eventsDb=new Events_DB;
                $result=$eventsDb->getTodoSubcriptionsByUid($this->userId);
                if ($result===false){
                        return false;
                } else{
                        $i=0;
                        while ($row = $result->fetch_array(MYSQLI_NUM))
                        {       
                                 $this->todoListIds[$i]= $row[1];
                                 $i=$i+1;
                        }
                        return $this->todoListIds;
                }
        }
}

?>