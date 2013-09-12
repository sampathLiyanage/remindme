
<?php
/***
* developer: sampath liyanage
* phone no: +94778514847
*/

include_once "dbCon.php";

class Events_DB extends DB_connection{
	
	public function __construct(){
                parent::__construct();
        }
        
       
         
        
        /*################# functions for "todoList" table #################*/
	
     
	/*
	*for creating todoLists
	*@input=> user id:int, title:string
	*@output=> if query changed the database:bool
	*/
	public function createTodoList($userId, $title, $description){
		
		$stmt = $this->prepareSqlStmt( "INSERT INTO todoList (user_id, title, description, date_created, date_updated) 
		VALUES (?,?,?,CURRENT_TIMESTAMP, CURRENT_TIMESTAMP );");
		$stmt->bind_param('sss', $userId, $title, $description);
		return $stmt->execute();
	}
	
	
	/*
	*for fetching latest  todoList of a user
	*@input=> user id:int
	*@output=> a todoList: sql result set
	*/
	public function getLatestTodoList($userId){
	        $stmt = $this->prepareSqlStmt( "SELECT * FROM todoList WHERE user_id=? ORDER BY id DESC");
		$stmt->bind_param('s', $userId);
	    return $this->getSqlResults($stmt);
	}
	
	
	/*
	*for changing a todoList
	*@input=> todoList id:int, user id:int, title:string, date expire:dateTime
	*@output=> if query changed the database:bool
	*/
	public function changeTodoList($id, $title, $description){
		
		$stmt = $this->prepareSqlStmt( "UPDATE todoList 
		SET title=?, description=?, date_updated=CURRENT_TIMESTAMP WHERE id=?");
		$stmt->bind_param('sss', $title, $description, $id);
		return $stmt->execute();
	}
	
	
	/*
	*for fetching all todoLists of a user
	*@input=> user id:int
	*@output=> list of todoLists: sql result set
	*/
	public function getTodoListsOfOwner($userId){
		
		$stmt = $this->prepareSqlStmt( "SELECT * FROM todoList WHERE user_id=? ORDER BY id DESC");
		$stmt->bind_param('s', $userId);
		return $this->getSqlResults($stmt);
	}
	
	
	/*
	*for fetching l todoList of a user

	*@input=> user id:int, todoList id:int
	*@output=> todoList: sql result set
	*/
	public function getTodoListOfOwner($userId, $todoListId){
		
		$stmt = $this->prepareSqlStmt( "SELECT * FROM todoList WHERE user_id=? AND id=?");
		$stmt->bind_param('ss', $userId, $todoListId);
		return $this->getSqlResults($stmt);
	}
	
	
	/*
	 *confirm ownership of a todo list
	
	*@input=> user id:int, todoList id:int
	*@output=> whether the user is the owner or not:boot
	*/
	public function confirmTodolistOwnership($userId, $todoListId){
	
		$stmt = $this->prepareSqlStmt( "SELECT * FROM todoList WHERE user_id=? AND id=?");
		$stmt->bind_param('ss', $userId, $todoListId);
		return $stmt->execute();
	}
	
	
        /*
	*for deleting a todoList
	*@input=> todoList id:int
	*@output=> if query changed the database:bool
	*/
	public function deleteTodoList($todoListId){
		
		$stmt = $this->prepareSqlStmt( "DELETE FROM todoList WHERE id=?");
		$stmt->bind_param('s', $todoListId);
		return $stmt->execute();
	}
	
	
	/*
	*deleting all the todoLists
	*for testing only!
	*/
	public function emptyDb(){
	        $stmt = $this->prepareSqlStmt( "DELETE FROM todoList");
		return $stmt->execute();
	}
		
	
	
	/*################# functions for "todoList_event" table #################*/
	
	/*
	*for creating a todoList's event
	*@input=> todoList id:int, name:string, description:string, dateTime:string
	*@output=> if query changed the database:bool
	*/
	public function createTodoEvent($todoListId, $name, $decription, $dateTime){
	
		$stmt = $this->prepareSqlStmt( "INSERT INTO todoList_event (todoList_id, name, description, date_time)
		 values (?,?,?,?)");
		$stmt->bind_param('ssss', $todoListId, $name, $decription, $dateTime);
		return $stmt->execute();
	}
	
	
	/*
	*for changing a todoList
	*@input=> todoList id:int, user id:int, title:string, date expire:dateTime
	*@output=> if query changed the database:bool
	*/
	public function changeTodoEvent($id, $name, $description, $datetime){
		
		$stmt = $this->prepareSqlStmt( "UPDATE todoList_event 
		SET name=?, description=?, date_time=? WHERE id=?");
		$stmt->bind_param('ssss', $name, $description, $datetime, $id);
		return $stmt->execute();
	}
	
	
	/*
	*for getting events by todoList id
	*@input=> todoList id:int
	*@output=> list of events: sql result set
	*/
	public function getTodoEvents($todoListId){
	
		$stmt = $this->prepareSqlStmt( "SELECT * FROM todoList_event WHERE todoList_id=? ORDER BY date_time ASC");
		$stmt->bind_param('s', $todoListId);
		return $this->getSqlResults($stmt);
	}
	
	
	/*
	 *confirm ownership of a todo list
	
	*@input=> user id:int, todoList id:int
	*@output=> whether the user is the owner or not:boot
	*/
	public function confirmTdEventOwnership($todoListId, $eventId){
	
		$stmt = $this->prepareSqlStmt( "SELECT * FROM todoList_event WHERE id=? AND todoList_id=?");
		$stmt->bind_param('ss', $eventId, $todoListId);
		return $stmt->execute();
	}
	
	/*
	*for deleting an event
	*@input=> event id:int
	*@output=> if query changed the database:bool
	*/
	public function deleteTodoEvent($eventId){
		
		$stmt = $this->prepareSqlStmt( "DELETE FROM todoList_event WHERE id=?");
		$stmt->bind_param('s', $eventId);
		return $stmt->execute();
	}
	
	
	/*################# functions for "todoList_reminder" table #################*/
	
	/*
	*for creating a event's reminder
	*@input=> todoList event's id:int,  reminder's dateTime:string
	*@output=> if query changed the database:bool
	*/
	public function addTodoReminder($eventId, $dateTime){
	
		$stmt = $this->prepareSqlStmt( "INSERT INTO todo_event_reminder (todo_event_id, date_time)
		 values (?,?)");
		$stmt->bind_param('ss', $eventId, $dateTime);
		return $stmt->execute();
	}
	
	/*
	*for getting reminders by event id
	*@input=> event id:int
	*@output=> list of reminders: sql result set
	*/
	public function getTodoReminders($eventId){
	
		$stmt = $this->prepareSqlStmt( "SELECT * FROM todo_event_reminder WHERE todo_event_id=?");
		$stmt->bind_param('s', $eventId);
		return $this->getSqlResults($stmt);
	}
	
	/*
	*for deleting a reminder
	*@input=> reminder id:int
	*@output=> if query changed the database:bool
	*/
	public function deleteTodoReminder($reminderId){
		
		$stmt = $this->prepareSqlStmt( "DELETE FROM todo_event_reminder WHERE id=?");
		$stmt->bind_param('s', $reminderId);
		return $stmt->execute();
	}
	
	/*
	*for get user_id of a reminder
	*@input=> reminder id:int
	*@output=> user id: sql result set
	*/
	public function getTodoReminderUser($reminderId){
	        $stmt = $this->prepareSqlStmt( "SELECT user_id FROM todoList, todoList_event, todo_event_reminder
	                                        WHERE todo_event_reminder.id=?
	                                        AND todo_event_reminder.todo_event_id=todoList_event.id
	                                        AND todoList_event.todoList_id=todoList.id");
	        $stmt->bind_param('s', $reminderId);
		return $this->getSqlResults($stmt);
	}
}
?>
