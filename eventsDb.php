
<?php
include_once "dbCon.php";

class Events_DB extends DB_connection{
	
	public function __construct(){
                parent::__construct();
        }
        
        /*################# functions for "schedule" table #################*/
	
	/*
	*for creating schedules
	*@input=> user id:int, title:string, date expire:dateTime
	*@output=> if query changed the database:bool
	*/
	public function createSchedule($userId, $title, $dateExpire){
		
		$stmt = $this->prepareSqlStmt( "INSERT INTO schedule (user_id, title, date_created, date_updated, date_expire) 
		VALUES (?,?,CURRENT_TIMESTAMP, CURRENT_TIMESTAMP ,?)");
		$stmt->bind_param('sss', $userId, $title, $dateExpire);
		return $stmt->execute();
	}
	
	
	/*
	*for changing a schedule
	*@input=> shedule id:int, user id:int, title:string, date expire:dateTime
	*@output=> if query changed the database:bool
	*/
	public function changeSchedule($id, $title, $dateExpire){
		
		$stmt = $this->prepareSqlStmt( "UPDATE schedule 
		SET title=?, date_updated=CURRENT_TIMESTAMP, date_expire=? WHERE id=?");
		$stmt->bind_param('sss', $title, $dateExpire, $id);
		return $stmt->execute();
	}
	
	
	/*
	*for fetching all schedules of a user
	*@input=> user id:int
	*@output=> list of schedules: sql result set
	*/
	public function getSchedulesOfOwner($userId){
		
		$stmt = $this->prepareSqlStmt( "SELECT * FROM schedule WHERE user_id=?");
		$stmt->bind_param('s', $userId);
		$stmt->execute();
		return $stmt->get_result();
	}
	
	
        /*
	*for deleting a schedule
	*@input=> schedule id:int
	*@output=> if query changed the database:bool
	*/
	public function deleteSchedule($scheduleId){
		
		$stmt = $this->prepareSqlStmt( "DELETE FROM schedule WHERE id=?");
		$stmt->bind_param('s', $scheduleId);
		return $stmt->execute();
	}
	
	
	
	
	/*################# functions for "schedule_subcription" table #################*/
	
	/*
	*for subcribing a schedule
	*@input=> user id:int, schedule_id:int
	*@output=> if query changed the database:bool
	*/
	public function subcribeSchedule($userId, $scheduleId){
	
		$stmt = $this->prepareSqlStmt( "INSERT INTO schedule_subcription values (?,?)");
		$stmt->bind_param('ss', $userId, $scheduleId);
		return $stmt->execute();
	}
	
	
	/*
	*for unsubcribing a schedule
	*@input=> user id:int, schedule_id:int
	*@output=> if query changed the database:bool
	*/
	public function unSubcribeSchedule($userId, $scheduleId){
	
		$stmt = $this->prepareSqlStmt( "DELETE FROM schedule_subcription WHERE user_id=? AND shedule_id=?");
		$stmt->bind_param('ss', $userId, $scheduleId);
		return $stmt->execute();
	}
	
	
	/*
	*for getting subcriptions by user id
	*@input=> user id:int
	*@output=> list of schedule ids: sql result set
	*/
	public function getSchSubcriptions($userId){
	
		$stmt = $this->prepareSqlStmt( "SELECT * FROM schedule_subcription WHERE user_id=?");
		$stmt->bind_param('s', $userId);
		$stmt->execute();
		return $stmt->get_result();
	}
	
	
	/*
	*deleting all the schedules
	*for testing only!
	*/
	public function emptyDb(){
	        $stmt = $this->prepareSqlStmt( "DELETE FROM schedule");
		return $stmt->execute();
	}
}
?>