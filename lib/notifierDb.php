
<?php

/***
* developer: sampath liyanage
* phone no: +94778514847
*/

include_once "dbCon.php";

/**
*contains functions needed to interact with database
*for authentication component
*/
class Notifier_DB extends DB_connection{
	
	public function __construct(){
                parent::__construct();
        }
	
        public function getReminders($date){
            $stmt = $this->prepareSqlStmt( 
                "SELECT remindList.title, remindList.description,
                        reminder.name, reminder.description, 
                        user.user_name, user.email
                FROM reminder,remindList,subcription,user
                WHERE reminder.remindList_id=remindList.id 
                AND subcription.remindList_id=remindList.id
                AND user.id=subcription.user_id
                AND remindList.public_token IS NOT NULL
                AND reminder.date_time=?");
            $stmt->bind_param('s', $date);
            return $this->getSqlResults($stmt);
        }
	
	
}
?>
