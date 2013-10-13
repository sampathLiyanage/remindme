<?php
/***
 * developer: sampath liyanage
 * phone no: +94778514847
 */

include_once "subscriptionDb.php";

/**
 * handles subscriptions
 */
class SubscribeHandler
{
    private $userId;
    private $scrDb;
    public function __construct($userId)
    {
        $this->userId = $userId;
        $this->scrDb  = new Subscription_DB();
    }

    /*
     * for subscribing a Remind list by a user
     * @input=>Remind list id:int
     * @output=>if successfull or not:bool
     */
    public function subscribe($publicKey)
    {
        $result = $this->scrDb->getIdFromKey($publicKey);
        if ($result === false) {
            return false;
        }
        $row          = $result->fetch_array(MYSQLI_NUM);
        $RemindListId = $row[0];
        $result       = $this->scrDb->subcribe($this->userId, $RemindListId);
        return $result;
    }
    
    /*
     * for unsubscribing a Remind list by a user
     * @input=>Remind list id:int
     * @output=>if successfull or not:bool
     */
    public function unsubscribe($RemindListId)
    {
        $result = $this->scrDb->unSubcribe($this->userId, $RemindListId);
        return $result;
    }
    
    /*
     * for getting all the subscribers of a Remind
     * @input=>Remind list id:int
     * @output=>sql table rows:sql result set
     * @output=>false if output is 0 rows or error:bool
     */
    public function getSubscribedUsers($RemindListId)
    {
        $result = $this->scrDb->getSubcriptionsBySid($RemindListId);
        return $result;
    }
    
    /*
     * for getting all the Remindlists subscribed by a user
     * @output=>sql table rows:sql result set
     * @output=>false if output is 0 rows or error:bool
     */
    public function getSubscribedLists()
    {
        $result = $this->scrDb->getSubcriptionsByUid($this->userId);
        return $result;
    }
    
    /*
     * get remind list from remind list id
     * @input=>remind list id:int
     * @output=>sql row:sql result row
     * @output=>false if failse:bool
     */
    public function getSubscribedRemindList($rlistId)
    {
        $result = $this->scrDb->getSubscribedRemindList($this->userId, $rlistId);
        if ($result === false) {
            return false;
        }
        $row = $result->fetch_array(MYSQLI_NUM);
        return $row;
    }
    
    /*
     * get reminders from a list id
     * @input=>reminder list id:int
     * @output=>reminders:array of Reminder
     * @output=>false if fails:bool
     */
    public function getSubscribedReminders($id)
    {
        $result = $this->scrDb->getSubscribedReminders($this->userId, $id);
        if ($result === false) {
            return false;
        } else {
            $i = 0;
            while ($row = $result->fetch_array(MYSQLI_NUM)) {
                $Reminders[$i] = new Reminder($row[1], $row[0], $row[2], $row[3], $row[4]);
                $i             = $i + 1;
            }
            return $Reminders;
        }
    }
    
    /*
     * get limited no of reminders
     * @input=>reminder list id:int, start index:int, no of entries:int
     * @output=>reminders:array of Reminder
     * @output=>false if fails:bool
     */
    public function getSubReminderForPage($rlistId, $start, $limit)
    {
        $result = $this->scrDb->getSubReminderForPage($this->userId, $rlistId, $start, $limit);
        if ($result === false) {
            return false;
        } else {
            $i = 0;
            while ($row = $result->fetch_array(MYSQLI_NUM)) {
                $Reminders[$i] = new Reminder($row[1], $row[0], $row[2], $row[3], $row[4]);
                $i             = $i + 1;
            }
            return $Reminders;
        }
    }
    
    /*
     * get limited no of remind-lists of a user
     * @input=>start index:int, no of entries:int
     * @output=>reminders:array of RemindLists
     * @output=>false if fails:bool
     */
    public function getListsForPage($start, $limit)
    {
        $sDb    = new Subscription_DB();
        $result = $sDb->getSubsByUid($this->userId, $start, $limit);
        if ($result === false) {
            return false;
        } else {
            $i = 0;
            while ($row = $result->fetch_array(MYSQLI_NUM)) {
                $RemindLists[$i] = new RemindList($row[1], $row[0], $row[2], $row[3], $row[4], $row[5], $row[6]);
                $i               = $i + 1;
            }
            return $RemindLists;
        }
    }
    
    /*
     * get public token of a remind-list
     * @input=>reminder list id:int
     * @output=>token:string
     * @output=>false if fails:bool
     */
    public function getKeyFromId($id)
    {
        $result = $this->scrDb->getKeyFromId($id);
        if ($result === false) {
            return false;
        }
        $row   = $result->fetch_array(MYSQLI_NUM);
        $token = $row[0];
        if ($token == NULL) {
            return false;
        }
        return $token;
    }
    
    /*
     * check if a token is valid
     * @input=>token:string
     * @output=>if the token is valid:bool
     */
    public function isToken($token)
    {
        $result = $this->scrDb->checkToken($token);
        if ($result === false) {
            return false;
        }
        $row = $result->fetch_array(MYSQLI_NUM);
        if ($row[0] === 1) {
            return true;
        }
        return false;
    }
    
    /*
     * check if a user is already subscribed to a public token
     * @input=>public token:string
     * @output=>subscribed or not:bool
     */
    public function isSubscribed($token)
    {
        $result = $this->scrDb->isSubscribed($token, $this->userId);
        if ($result === false) {
            return false;
        }
        $row = $result->fetch_array(MYSQLI_NUM);
        if ($row[0] === 1) {
            return true;
        }
        return false;
    }
}
?>
