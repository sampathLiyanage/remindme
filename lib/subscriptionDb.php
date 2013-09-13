<?php
/***
* developer: sampath liyanage
* phone no: +94778514847
*/

include_once "dbCon.php";

/**
 * database functions for subcription manager
 */
class Subscription_DB extends DB_connection{
	public function __construct(){
		parent::__construct();
	}
	
	/*
	*for subcribing a todoList
	*@input=> user id:int, todoList_id:int
	*@output=> if query changed the database:bool
	*/
	public function subcribeTodoList($userId, $todoListId){
	
		$stmt = $this->prepareSqlStmt( "INSERT INTO todoList_subcription values (?,?)");
		$stmt->bind_param('ss', $userId, $todoListId);
		return $stmt->execute();
	}
	
	
	/*
	*for unsubcribing a todoList
	*@input=> user id:int, todoList_id:int
	*@output=> if query changed the database:bool
	*/
	public function unSubcribeTodoList($userId, $todoListId){
	
		$stmt = $this->prepareSqlStmt( "DELETE FROM todoList_subcription WHERE user_id=? AND todoList_id=?");
		$stmt->bind_param('ss', $userId, $todoListId);
		return $stmt->execute();
	}
	
	
	/*
	*for getting subcriptions by user id
	*@input=> user id:int
	*@output=> list of todoList ids: sql result set
	*/
	public function getTodoSubcriptionsByUid($userId){
	
		$stmt = $this->prepareSqlStmt( "SELECT * FROM todoList_subcription WHERE user_id=?");
		$stmt->bind_param('s', $userId);
		return $this->getSqlResults($stmt);
	}
	
	/*
	*for getting subcriptions by user id
	*@input=> user id:int
	*@output=> list of todoList ids: sql result set
	*/
	public function getTodoSubcriptionsBySid($todoListId){
	
		$stmt = $this->prepareSqlStmt( "SELECT * FROM todoList_subcription WHERE todoList_id=?");
		$stmt->bind_param('s', $todoListId);
		return $this->getSqlResults($stmt);
	}
	
	/*
	 *deleting all the todoLists
	*for testing only!
	*/
	public function emptyDb(){
		$stmt = $this->prepareSqlStmt( "DELETE FROM todoList_subcription");
		return $stmt->execute();
	}
}

?>