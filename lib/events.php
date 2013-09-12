<?php
/***
* developer: sampath liyanage
* phone no: +94778514847
*/


include_once "eventsDb.php";


/**
*represent the error report of a function call
*/
class Report{
        public $isError=true;
        public $report="Input Error";
}



/**
*represents a reminder before saving in the database
*/	
class TodoList_reminder_temp{
	private $eventId;
	public $dateTime;
	
	public function __construct($eventId, $dateTime){
	        $this->eventId=$eventId;
                $this->dateTime=$dateTime;
        }	
}

/**
*represents a reminder after saving in the database
*/
class TodoList_reminder extends TodoList_reminder_temp{
        public $id;
	
	public function __construct($eventId, $id, $dateTime){
	        $this->id=$id;
                $this->dateTime=$dateTime;
                parent::__construct($eventId, $dateTime);
        }	
}


/**
*represents an event before saving in the database
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

/**
*represents an event after saving in the database
*/
class TodoList_event extends TodoList_event_temp{

       
	
	public function __construct($todoListId, $id, $name, $description, $dateTime){
                $this->id=$id;
                parent::__construct($todoListId, $name, $description, $dateTime);
        }
        
        /*
        *get reminder of an event
        *@input=>event id:int
        *@output=> reminder:TodoList_Reminder OR false if no results:bool
        */
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
        
        /*
        *add reminder of an event
        *@input=>reminder:TodoList_Reminder_Temp
        *@output=>if the reminder added successfully:bool
        */
        public function addReminder($reminder){
                $eventsDb=new Events_DB;
                $result=$eventsDb->addTodoReminder($this->id, $reminder->dateTime);
                return $result;
        }
        
        /*
        *remove reminder of an event
        *@input=>reminder id:int
        *@output=>if the reminder removed successfully:bool
        */
        public function removeReminder($id){
                $eventsDb=new Events_DB;
                $result=$eventsDb->deleteTodoReminder($id);
                return $result;
        }
	
}


/**
*represent a todoList before saving in the database
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

/**
*represents error report of an event
*/
class EventReport extends Report{
           public     $name='';
           public     $description='';
           public      $date='';
}

/**
*represent a todoList before after in the database
*/        
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
        
        /*
        *get all the events in a todo list
        *@output=>array of all the events:TodoList_Event array OR false if fails:bool 
        */
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
        
        /*
        *add event to a todo list
        *@input=>event:TodoList_event_temp
        *@output=>if the event added successfully:bool
        */
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
        
        /*
        *remove event from a todo list
        *@input=>event id:int
        *@output=>if the event removed successfully:bool
        */
        public function removeEvent($eventId){
                $eventsDb=new Events_DB;
                $result=$eventsDb->confirmTdEventOwnership($this->id, $eventId);
                if ($result){
                	return $eventsDb->deleteTodoEvent($eventId);
                }
                return false;
        
        }
        
        /*
        *change event of a todo list
        *@input=>event:TodoList_event_temp
        *@output=>if the event change successfully:bool
        */
        public function changeEvent($event){
                $eventsDb=new Events_DB;
                $result=$eventsDb->changeTodoEvent($event->id, $event->name, $event->description, $event->dateTime);
                return $result;
        }
        
        //************to be shifted to another component****************//
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
        
        /*
        *create a todo list
        *@input=>todo list: TodoList_Temp
        *@output=>if todo list added successfully:bool
        */
        public function createTodoList($todoList){
                $report=new Report;
                
                $eventsDb=new Events_DB;
                $result=$eventsDb->createTodoList($this->userId, $todoList->title, $todoList->description);
                return $result;
        }
        
        /*
        *change a todo list
        *@input=>todo list: TodoList_Temp
        *@output=>if todo list changed successfully:bool
        */
        public function changeTodoList($todoList){
                $eventsDb=new Events_DB;
                $result=$eventsDb->changeTodoList($todoList->id, $todoList->title, $todoList->description);
                return $result;
        }
        
        /*
        *change a todo list
        *@input=>todo list id: int
        *@output=>if todo list deleted successfully:bool
        */
        public function deleteTodoList($todoListId){
                $eventsDb=new Events_DB;
                $result=$eventsDb->confirmTodolistOwnership($this->userId, $todoListId);
                if ($result){
                	return $eventsDb->deleteTodoList($todoListId);
                }
                return false;
        }
        
        /*
        *get the todo list added last
        *@output=>todo list:TodoList OR false if fails:bool
        */
        public function getLatestTodoList(){
                $eventsDb=new Events_DB;
                $result=$eventsDb->getLatestTodoList($this->userId);
                $row = $result->fetch_array(MYSQLI_NUM);
                $this->todoLists= new TodoList($row[1], $row[0],$row[2],$row[3],$row[4],$row[5]);
                return $this->todoLists;
        }
        
        /*
        *get all the todo list of a user
        *@output=>array of todo lists:TodoList array 
        *@output=>false if fails:bool
        */
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
        
        /*
        *get a todo list of a user by id
        *@output=>todo lists:TodoList 
        *@output=>false if fails:bool
        */
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
        
        /************ tobe shisted to another component*************/
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
