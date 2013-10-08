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
	*for subcribing a remindList
	*@input=> user id:int, remindList_id:int
	*@output=> if query changed the database:bool
	*/
	public function subcribe($userId, $remindListId){
                
		$stmt = $this->prepareSqlStmt( "INSERT INTO subcription values (?,?)");
		$stmt->bind_param('ss', $userId, $remindListId);
		return $stmt->execute();
	}
	
        /*
	*for getting public key of a remind list
	*@input=> public key of remind list: string
	*@output=> remind list id: int
	*/
        public function getIdFromKey($publicKey){
            $stmt = $this->prepareSqlStmt( "SELECT id FROM remindList WHERE public_token=?");
            $stmt->bind_param('s', $publicKey);
            return $this->getSqlResults($stmt);
        }
        
        /*
	*for getting remind list id of a public key
	*@input=> remind list id: int
	*@output=> public key of remind list: string
	*/
        public function getKeyFromId($id){
            $stmt = $this->prepareSqlStmt( "SELECT public_token FROM remindList WHERE id=?");
            $stmt->bind_param('s', $id);
            return $this->getSqlResults($stmt);
        }
        
	/*
	*for unsubcribing a remindList
	*@input=> user id:int, remindList_id:int
	*@output=> if query changed the database:bool
	*/
	public function unSubcribe($userId, $remindListId){
	
		$stmt = $this->prepareSqlStmt( "DELETE FROM subcription WHERE user_id=? AND remindList_id=?");
		$stmt->bind_param('ss', $userId, $remindListId);
		return $stmt->execute();
	}
	
	
	/*
	*for getting subcriptions by user id
	*@input=> user id:int
	*@output=> list of remindList ids: sql result set
	*/
	public function getSubcriptionsByUid($userId){
	
		$stmt = $this->prepareSqlStmt( "SELECT * FROM remindList,subcription WHERE subcription.user_id=? AND remindList_id=id AND remindList.public_token IS NOT NULL");
		$stmt->bind_param('s', $userId);
		return $this->getSqlResults($stmt);
	}
        
        public function getSubsByUid($userId,$start,$limit){
	
		$stmt = $this->prepareSqlStmt( "SELECT * FROM remindList,subcription WHERE remindList_id=id  AND subcription.user_id=? AND remindList.public_token IS NOT NULL ORDER BY remindList_id DESC LIMIT ?,?");
		$stmt->bind_param('sss', $userId,$start,$limit);
		return $this->getSqlResults($stmt);
	}
	
	
	/*
	*for getting subcriptions by list id
	*@input=> user id:int
	*@output=> list of remindList ids: sql result set
	*/
	public function getSubcriptionsBySid($remindListId){
	
		$stmt = $this->prepareSqlStmt( "SELECT * FROM subcription WHERE remindList_id=?");
		$stmt->bind_param('s', $remindListId);
		return $this->getSqlResults($stmt);
	}
	
        public function getSubscribedReminders($userId, $rlistId){
                $stmt = $this->prepareSqlStmt( "SELECT 
                    reminder.id, reminder.remindList_id, reminder.name, reminder.description, reminder.date_time
                    FROM subcription,remindList,reminder WHERE
                    subcription.remindList_id=remindList.id AND reminder.remindList_id=remindList.id
                    AND subcription.user_id=? AND remindList.id=?");
		$stmt->bind_param('ss', $userId, $rlistId);
		return $this->getSqlResults($stmt);
        }
        
        
        public function getSubReminderForPage($userId, $rlistId, $start, $limit){
            $stmt = $this->prepareSqlStmt( "SELECT 
                reminder.id, reminder.remindList_id, reminder.name, reminder.description, reminder.date_time 
                FROM subcription,remindList,reminder WHERE
                subcription.remindList_id=remindList.id AND reminder.remindList_id=remindList.id
                AND subcription.user_id=? AND remindList.id=?
                ORDER BY reminder.date_time ASC LIMIT ?,?");
            $stmt->bind_param('ssss', $userId, $rlistId, $start, $limit);
            return $this->getSqlResults($stmt);
        }
        
        public function checkToken($token){
            $stmt = $this->prepareSqlStmt( "SELECT COUNT(*) FROM remindList WHERE public_token=?");
            $stmt->bind_param('s', $token);
            return $this->getSqlResults($stmt);
        }
        
        
        public function isSubscribed($token, $userId){
            $stmt = $this->prepareSqlStmt( "SELECT COUNT(*) FROM remindList,subcription WHERE public_token=? 
                AND subcription.user_id=remindList.user_id
                AND subcription.remindList_id=remindList.id 
                AND subcription.user_id=?");
            $stmt->bind_param('ss', $token, $userId);
            return $this->getSqlResults($stmt);
        }
        
	/*
	 *deleting all the remindLists
	*for testing only!
	*/
	public function emptyDb(){
		$stmt = $this->prepareSqlStmt( "DELETE FROM subcription");
		return $stmt->execute();
	}
}

?>
