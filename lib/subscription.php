<?php
/***
* developer: sampath liyanage
* phone no: +94778514847
*/



/**
 * handles subscriptions
 */
class TdListSubscribeHandler{
	private $userId;
	private $scrDb;
	
	public function __construct($userId){
			$this->userId=$userId;
			$this->scrDb=new Subscription_DB();
	}
	
	/*
	 * for subscribing a todo list by a user
	 * @input=>todo list id:int
	 * @output=>if successfull or not:bool
	 */
	public function subscribe($publicKey){
                $result=$this->scrDb->getTdIdFromKey($publicKey);
                if ($result===false){
                    return false;
                }
                $row=$result->fetch_array(MYSQLI_NUM);
                $todoListId=$row[0];
		$result=$this->scrDb->subcribeTodoList($this->userId, $todoListId);
		return $result;
	}
	
	/*
	* for unsubscribing a todo list by a user
	* @input=>todo list id:int
	* @output=>if successfull or not:bool
	*/
	public function unsubscribe($todoListId){
		$result=$this->scrDb->unSubcribeTodoList($this->userId, $todoListId);
		return $result;
	}
	
	/*
	* for getting all the subscribers of a todo
	* @input=>todo list id:int
	* @output=>sql table rows:sql result set
	* @output=>false if output is 0 rows or error:bool
	*/
	public function getSubscribedUsers($todoListId){
		$result=$this->scrDb->getTodoSubcriptionsBySid($todoListId);
		return $result;
	}
	
	/*
	* for getting all the todolists subscribed by a user
	* @output=>sql table rows:sql result set
	* @output=>false if output is 0 rows or error:bool
	*/
	public function getSubscribedTdLists(){
		$result=$this->scrDb->getTodoSubcriptionsByUid($this->userId);
		return $result;
	}
}

?>