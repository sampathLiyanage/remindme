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
	 * for subscribing a Remind list by a user
	 * @input=>Remind list id:int
	 * @output=>if successfull or not:bool
	 */
	public function subscribe($publicKey){
                $result=$this->scrDb->getIdFromKey($publicKey);
                if ($result===false){
                    return false;
                }
                $row=$result->fetch_array(MYSQLI_NUM);
                $RemindListId=$row[0];
		$result=$this->scrDb->subcribe($this->userId, $RemindListId);
		return $result;
	}
	
	/*
	* for unsubscribing a Remind list by a user
	* @input=>Remind list id:int
	* @output=>if successfull or not:bool
	*/
	public function unsubscribe($RemindListId){
		$result=$this->scrDb->unSubcribe($this->userId, $RemindListId);
		return $result;
	}
	
	/*
	* for getting all the subscribers of a Remind
	* @input=>Remind list id:int
	* @output=>sql table rows:sql result set
	* @output=>false if output is 0 rows or error:bool
	*/
	public function getSubscribedUsers($RemindListId){
		$result=$this->scrDb->getSubcriptionsBySid($RemindListId);
		return $result;
	}
	
	/*
	* for getting all the Remindlists subscribed by a user
	* @output=>sql table rows:sql result set
	* @output=>false if output is 0 rows or error:bool
	*/
	public function getSubscribedTdLists(){
		$result=$this->scrDb->getSubcriptionsByUid($this->userId);
		return $result;
	}
}

?>
