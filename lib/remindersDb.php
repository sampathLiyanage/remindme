
<?php
/***
 * developer: sampath liyanage
 * phone no: +94778514847
 */

include_once "dbCon.php";

class Reminders_DB extends DB_connection
{
    public function __construct()
    {
        parent::__construct("reminderManager", "EfqXpjpNGP2heCKb");
    }

    
    /*################# functions for "remindList" table #################*/
    
    /*
     *for creating RemindLists
     *@input=> user id:int, title:string
     *@output=> if query changed the database:bool
     */
    public function createRemindList($userId, $title, $description)
    {
        $stmt = $this->prepareSqlStmt("INSERT INTO remindList (user_id, title, description, date_created, date_updated) 
		VALUES (?,?,?,CURRENT_TIMESTAMP, CURRENT_TIMESTAMP );");
        $stmt->bind_param('sss', $userId, $title, $description);
        return $stmt->execute();
    }
    
    /*
     *for fetching latest  remindList of a user
     *@input=> user id:int
     *@output=> a remindList: sql result set
     */
    public function getLatestRemindList($userId)
    {
        $stmt = $this->prepareSqlStmt("SELECT * FROM remindList WHERE user_id=? ORDER BY id DESC");
        $stmt->bind_param('s', $userId);
        return $this->getSqlResults($stmt);
    }
    
    /*
     *for changing a remindList
     *@input=> remindList id:int, user id:int, title:string, date expire:dateTime
     *@output=> if query changed the database:bool
     */
    public function changeRemindList($id, $title, $description)
    {
        $stmt = $this->prepareSqlStmt("UPDATE remindList 
		SET title=?, description=?, date_updated=CURRENT_TIMESTAMP WHERE id=?");
        $stmt->bind_param('sss', $title, $description, $id);
        return $stmt->execute();
    }
    
    /*
     *for fetching all RemindLists of a user
     *@input=> user id:int
     *@output=> list of RemindLists: sql result set
     */
    public function getRemindListsOfOwner($userId)
    {
        $stmt = $this->prepareSqlStmt("SELECT * FROM remindList WHERE user_id=? ORDER BY id DESC");
        $stmt->bind_param('s', $userId);
        return $this->getSqlResults($stmt);
    }
    
    /*
     *for fetching limited no of RemindLists of a user
     *@input=> user id:int, start index:int, no of reminders:int
     *@output=> list of RemindLists: sql result set
     */
    public function getListsOfOwner($userId, $start, $limit)
    {
        $stmt = $this->prepareSqlStmt("SELECT * FROM remindList WHERE user_id=?  ORDER BY id DESC LIMIT ?,?");
        $stmt->bind_param('sss', $userId, $start, $limit);
        return $this->getSqlResults($stmt);
    }
    
    /*
     *for fetching l remindList of a user
     
     *@input=> user id:int, remindList id:int
     *@output=> remindList: sql result set
     */
    public function getRemindListOfOwner($userId, $RemindListId)
    {
        $stmt = $this->prepareSqlStmt("SELECT * FROM remindList WHERE user_id=? AND id=?");
        $stmt->bind_param('ss', $userId, $RemindListId);
        return $this->getSqlResults($stmt);
    }
    
    /*
     *confirm ownership of a Remind list
     
     *@input=> user id:int, remindList id:int
     *@output=> whether the user is the owner or not:boot
     */
    public function confirmRemindlistOwnership($userId, $RemindListId)
    {
        $stmt = $this->prepareSqlStmt("SELECT * FROM remindList WHERE user_id=? AND id=?");
        $stmt->bind_param('ss', $userId, $RemindListId);
        return $stmt->execute();
    }
    
    /*
     *for deleting a remindList
     *@input=> remindList id:int
     *@output=> if query changed the database:bool
     */
    public function deleteRemindList($RemindListId)
    {
        $stmt = $this->prepareSqlStmt("DELETE FROM remindList WHERE id=?");
        $stmt->bind_param('s', $RemindListId);
        return $stmt->execute();
    }
    
    /*
     *for publishing a Remind list
     *@input=> userId:int, remindList id:int, key:string
     *@output=> if query changed the database:bool
     */
    public function publishRemindList($userId, $RemindListId, $str)
    {
        $stmt = $this->prepareSqlStmt("UPDATE remindList SET public_token=? WHERE id=? AND user_id=?");
        $stmt->bind_param('sss', $str, $RemindListId, $userId);
        return $stmt->execute();
    }
    
    /*
     *to check a Remind list key exists
     *@input=> key:string
     *@output=> if exists:bool
     */
    public function isRemindlistKey($str)
    {
        $stmt = $this->prepareSqlStmt("SELECT *  FROM remindList WHERE public_token=? ");
        $stmt->bind_param('s', $str);
        return $this->getSqlResults($stmt);
    }
    
    /*
     *deleting all the RemindLists
     *for testing only!
     */
    public function emptyDb()
    {
        $stmt = $this->prepareSqlStmt("DELETE FROM remindList");
        return $stmt->execute();
    }
    
    
    /*################# functions for "reminder" table #################*/
    
    /*
     *for creating a remindList's reminder
     *@input=> remindList id:int, name:string, description:string, dateTime:string
     *@output=> if query changed the database:bool
     */
    public function createReminder($RemindListId, $name, $decription, $dateTime)
    {
        $stmt = $this->prepareSqlStmt("INSERT INTO reminder (remindList_id, name, description, date_time)
		 values (?,?,?,?)");
        $stmt->bind_param('ssss', $RemindListId, $name, $decription, $dateTime);
        return $stmt->execute();
    }
    
    /*
     *for changing a remindList
     *@input=> remindList id:int, user id:int, title:string, date expire:dateTime
     *@output=> if query changed the database:bool
     */
    public function changeReminder($id, $name, $description, $datetime)
    {
        $stmt = $this->prepareSqlStmt("UPDATE reminder 
		SET name=?, description=?, date_time=? WHERE id=?");
        $stmt->bind_param('ssss', $name, $description, $datetime, $id);
        return $stmt->execute();
    }
    
    /*
     *for getting all Reminders by remindList id
     *@input=> remindList id:int
     *@output=> list of Reminders: sql result set
     */
    public function getAllReminders($RemindListId)
    {
        $stmt = $this->prepareSqlStmt("SELECT * FROM reminder WHERE remindList_id=? ORDER BY date_time ASC");
        $stmt->bind_param('s', $RemindListId);
        return $this->getSqlResults($stmt);
    }
    
    /*
     *for getting Reminders by remindList id
     *@input=> remindList id:int, first index:int, no of entries:int
     *@output=> list of Reminders: sql result set
     */
    public function getReminders($RemindListId, $start, $limit)
    {
        $stmt = $this->prepareSqlStmt("SELECT * FROM reminder WHERE remindList_id=? ORDER BY date_time ASC LIMIT ?,?");
        $stmt->bind_param('sss', $RemindListId, $start, $limit);
        return $this->getSqlResults($stmt);
    }
    
    /*
     *for getting an reminder by remindList id and reminder id
     *@input=> remindList id:int
     *@output=> list of Reminders: sql result set
     */
    public function getReminder($RemindListId, $ReminderId)
    {
        $stmt = $this->prepareSqlStmt("SELECT * FROM reminder WHERE remindList_id=? AND id=?");
        $stmt->bind_param('ss', $RemindListId, $ReminderId);
        return $this->getSqlResults($stmt);
    }
    
    /*
     *confirm ownership of a Remind list
     
     *@input=> user id:int, remindList id:int
     *@output=> whether the user is the owner or not:boot
     */
    public function confirmReminderOwnership($RemindListId, $ReminderId)
    {
        $stmt = $this->prepareSqlStmt("SELECT * FROM reminder WHERE id=? AND remindList_id=?");
        $stmt->bind_param('ss', $ReminderId, $RemindListId);
        return $stmt->execute();
    }
    
    /*
     *for deleting an reminder
     *@input=> reminder id:int
     *@output=> if query changed the database:bool
     */
    public function deleteReminder($ReminderId)
    {
        $stmt = $this->prepareSqlStmt("DELETE FROM reminder WHERE id=?");
        $stmt->bind_param('s', $ReminderId);
        return $stmt->execute();
    }
}
?>
