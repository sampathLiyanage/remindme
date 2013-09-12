<?php
/***
* developer: sampath liyanage
* phone no: +94778514847
*/

include_once "authDb.php";
include_once "eventsDb.php";
include_once "events.php";

class Events_DBTest  extends PHPUnit_Framework_TestCase{
        static $eventsDb;
        static $authDb;
        static $userId;
        
        public static function setUpBeforeClass(){
                //empty the todoList table
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
        
        
        function testCreateTodoList(){
		
		//adding new todoLists
		
		$result= self::$eventsDb->createTodoList(self::$userId, 'my todoList 1', 'descriptn 1');
		$this->assertTrue($result);
		$result= self::$eventsDb->createTodoList(self::$userId, 'my todoList 2', 'descriptn 2');
		$this->assertTrue($result);
		
		//getting the created todo List
		$result= self::$eventsDb->getLatestTodoList(self::$userId);
		$row = $result->fetch_array(MYSQLI_NUM);
		$this->assertTrue($row[1]==self::$userId);
		$this->assertTrue($row[2]=='my todoList 2');
		$this->assertTrue($row[3]=='descriptn 2');
		
	}
	
	

	public function testgetTodoListsOfOwner(){
		$result= self::$eventsDb->getTodoListsOfOwner(self::$userId);
		while ($row = $result->fetch_array(MYSQLI_NUM))
                {       
                         $id=$row[0];
                         $title=$row[2]."changed";
                         $descriptn=$row[3]."changed";
                         
                         //changing an exsisting todoList;
		        $r= self::$eventsDb->changeTodoList($id, $title,$descriptn);
		        $this->assertTrue($r);
		        
		        //getting the changed todoList;
		        $r= self::$eventsDb->getTodoListOfOwner(self::$userId, $id);
		        $row1 = $r->fetch_array(MYSQLI_NUM);
		        $this->assertTrue($row1[0]==$id);
		        
	        }
	
	}
	
	
	
	
        
        public function testcreateTodoEvent(){
                $result= self::$eventsDb->getTodoListsOfOwner(self::$userId);
		while ($row = $result->fetch_array(MYSQLI_NUM))
                {       
                         $id=$row[0];
                         
                        //create events for todoLists
		        $r= self::$eventsDb->createTodoEvent($id, 'event 1', 'description 1', '2013-12-12' );
		        $this->assertTrue($r);
		        $r= self::$eventsDb->createTodoEvent($id, 'event 2', 'description 2', '2013-12-12' );
		        $this->assertTrue($r);
	        }
        }
        
        
        
          /*
        *for testing three functions
        *tests changeTodoEvent(x);
        *tests getTodoEvents(x);
        *tests deletTodoEvent(x);
        */
        public function testGetAndChangeEvents(){
                $result= self::$eventsDb->getTodoListsOfOwner(self::$userId);
		while ($row = $result->fetch_array(MYSQLI_NUM))
                {       
                        $todoListId=$row[0];
                        
                        //get events
                        $result1= self::$eventsDb->getTodoEvents($todoListId);
                        
                        while ($row1 = $result1->fetch_array(MYSQLI_NUM))
                        {       
                                 $eventId=$row1[0];
                                 
                                 //change an event
		                 $r= self::$eventsDb->changeTodoEvent($eventId, 'event - changed', 'description  changed', '2013-12-12' );
		                 $this->assertTrue($r);

		                 //deletes an event
		                 $r= self::$eventsDb->deleteTodoEvent($eventId);
		                 $this->assertTrue($r);
	                }
	        }
        }
        
        
        /*
        *for testing four functions
        *tests changeTodoReminder(x);
        *tests getTodoReminders(x);
        *tests deletTodoReminder(x);
        *tests getTodoReminderUser(x);
        */
        public function testGetChangeDeletReminders(){
                //create some entries in database
                $this->testcreateTodoEvent();
                
                $result= self::$eventsDb->getTodoListsOfOwner(self::$userId);
		while ($row = $result->fetch_array(MYSQLI_NUM))
                {       
                        $todoListId=$row[0];
                        
                        //get events
                        $result1= self::$eventsDb->getTodoEvents($todoListId);
                        
                        
                        while ($row1 = $result1->fetch_array(MYSQLI_NUM))
                        {       
                                 $eventId=$row1[0];
                                 
                                 
                                 //add a reminder
		                 $r= self::$eventsDb->addTodoReminder($eventId, '2013-12-12' );
		                 $this->assertTrue($r);
		                 
		                 //get reminders
                                $result2= self::$eventsDb->getTodoReminders($eventId);
                                
                                while ($row2 = $result2->fetch_array(MYSQLI_NUM))
                                {       
                                         $reminderId=$row2[0];
                                         
                                         //check if userid is correct
		                         $result3= self::$eventsDb->getTodoReminderUser($reminderId);
		                         $row3 = $result3->fetch_array(MYSQLI_NUM);
		                         $this->assertTrue($row3[0]==self::$userId);
		                         
                                         //delete a reminder
	                                 $r= self::$eventsDb->deleteTodoReminder($reminderId);
	                                 $this->assertTrue($r);
	                        }
	                }
	        }
        }
        
        

        
        public function testdeleteTodoList(){
		$result= self::$eventsDb->getTodoListsOfOwner(self::$userId);
		while ($row = $result->fetch_array(MYSQLI_NUM))
                {       
                         $id=$row[0];
		        
		        //delete a todoList
		        $r= self::$eventsDb->deleteTodoList($id);
		        $this->assertTrue($r);
	        }
	
	}
}



/**
*test functions related to todoLists in events.php
*/
class TodoListTest  extends PHPUnit_Framework_TestCase{
        static $eventsDb;
        static $authDb;
        static $userId;
        
        public static function setUpBeforeClass(){
                //empty the todoList table
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
        
        public function testTodoList(){
                //create a new todoList
                $todoListManager=new TodoListManager(self::$userId);
                $todoList=new TodoList_temp(self::$userId, 'testing todoList 1', 'this todoList is created for testing');
                $result=$todoListManager->createTodoList($todoList);
                $this->assertTrue($result);
                //get created todo list
                $todoList=$todoListManager->getLatestTodoList();
                $this->assertTrue($todoList->userId==self::$userId);
		$this->assertTrue($todoList->title=='testing todoList 1');
		$this->assertTrue($todoList->description=='this todoList is created for testing');
                
                //add an event to a todoList
                $todoListManager=new TodoListManager(self::$userId);
                $todoLists=$todoListManager->getTodoListsOwned();
                $todoList=$todoLists[0];
                $this->assertTrue(!($todoList===false));
                $this->assertTrue($todoList->id!=null);
                $todoListId=$todoList->id; //id should be saved for get the events of that todoList next
                $this->assertTrue($todoList->userId==self::$userId);
                $this->assertTrue($todoList->title=='testing todoList 1');
                $this->assertTrue($todoList->description=='this todoList is created for testing');
                $this->assertTrue($todoList->dateCreated!=null);
                $this->assertTrue($todoList->dateUpdated!=null);
                
                $event=new TodoList_event_temp(self::$userId, "mid evolution", "get ready for the evolution", "2013-09-20");
                $result=$todoList->addEvent($event);
                self::assertTrue(!$result->isError);
                
                //read the event
                $todoListManager=new TodoListManager(self::$userId);
                $todoList=$todoListManager->getTodoListOwned($todoListId);
                $events=$todoList->getEvents();
                $event=$events[0];
                $this->assertTrue(!($event===false));
                $this->assertTrue($event->id!=null);
                $eventId=$event->id; //id should be saved for get the events of that todoList next
                $this->assertTrue($event->name=="mid evolution");
                $this->assertTrue($event->description=="get ready for the evolution");
                $this->assertTrue($event->dateTime=="2013-09-20 00:00:00");
                
                
                //change the event
                $todoListManager=new TodoListManager(self::$userId);
                $todoList=$todoListManager->getTodoListOwned($todoListId);
                $events=$todoList->getEvents();
                $event=$events[0];
                $event->description="get ready for the evolution(postponed a week)";
                $event->dateTime="2013-09-27 00:00:00";
                $todoList->changeEvent($event);
                $events=$todoList->getEvents();
                $event=$events[0];
                $this->assertTrue(!($event===false));
                $this->assertTrue($event->id!=null);
                $eventId=$event->id; //id should be saved for get the events of that todoList next
                $this->assertTrue($event->name=="mid evolution");
                $this->assertTrue($event->description=="get ready for the evolution(postponed a week)");
                $this->assertTrue($event->dateTime=="2013-09-27 00:00:00");
                
                //change the todoList
                $todoListManager=new TodoListManager(self::$userId);
                $todoList=$todoListManager->getTodoListOwned($todoListId);
                $todoList->title="testing todoList 1 changed";
                $todoList->description="description changed";
                $todoList->dateExpire='2013-09-27 00:00:00';
                $todoListManager->changeTodoList($todoList);
                $todoLists=$todoListManager->getTodoListsOwned();
                $todoList=$todoLists[0];
                $this->assertTrue(!($todoList===false));
                $this->assertTrue($todoList->id!=null);
                $todoListId=$todoList->id; //id should be saved for get the events of that todoList next
                $this->assertTrue($todoList->userId==self::$userId);
                $this->assertTrue($todoList->title=="testing todoList 1 changed");
                $this->assertTrue($todoList->description=="description changed");
                $this->assertTrue($todoList->dateCreated!=null);
                $this->assertTrue($todoList->dateUpdated!=null);
        }
}

?>
