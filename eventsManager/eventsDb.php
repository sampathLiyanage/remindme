
<?php
include_once "dbCon.php";

class Events_DB extends DB_connection{
	
	public function __construct(){
                parent::__construct();
        }
	
	/*
	*for creating schedules
	*@input=> user id:int, title:string, date created:dateTime, date expire:dateTime
	*@output=> if query changed the database:bool
	*/
	public function createSchedule($userId, $title, $dateCreated, $dateExpire){
		
		$stmt = $this->prepareSqlStmt( "INSERT INTO schedule (user_id, title, date_created, date_expire) VALUES(?,?,?,?)");
		$stmt->bind_param('ssss', $userId, $title, $dateCreated, $dateExpire);
		return $stmt->execute();
	}
	
	
	/*
	*for checking if a user name exists
	*@input=> username:string
	*@output=> if username exists:bool
	*/
	/*public function isUserExist($username){
		$stmt = $this->prepareSqlStmt( "SELECT COUNT(*) FROM user WHERE user_name=?");
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$result=$stmt->get_result();
		$row = $result->fetch_row();
		if($row[0]==1){
			return true;
		}else{
			return false;
		}
	}
	
	/*
	*for checking if an email address exists
	*@input=> email address:string
	*@output=> if email address exists:bool
	*/
	/*public function isEmailExist($email){
		$stmt = $this->prepareSqlStmt( "SELECT COUNT(*) FROM user WHERE email=?");
		$stmt->bind_param('s', $email);
		$stmt->execute();
		$result=$stmt->get_result();
		$row = $result->fetch_row();
		if($row[0]==1){
			return true;
		}else{
			return false;
		}
	}
	
	
	/*
	*for checking if a user name exists
	*@input=> username:string, passwdHash:stirng
	*@output=> if username,passwdhash matches:bool
	*/
	/*public function authenticate($username,$passwdHash){
		$stmt = $this->prepareSqlStmt( "SELECT COUNT(*) FROM user WHERE user_name=? AND password=?");
		$stmt->bind_param('ss', $username,$passwdHash);
		$stmt->execute();
		$result=$stmt->get_result();
		$row = $result->fetch_row();
		if($row[0]==1){
			return true;
		}else{
			return false;
		}
	}*/
	
	/*
	*empty the events database
	*for testing only
	*/
	public function emptyDb(){
	        $stmt = $this->prepareSqlStmt( "DELETE FROM schedule");
		return $stmt->execute();
	}
}
?>
