<?php
/***
 * developer: sampath liyanage
 * phone no: +94778514847
 */

include_once "remindersDb.php";

/**
 *represent the error report of a function call
 */
class Report
{
    public $isError = true;
    public $report = "Input Error";
}


/**
 *represents an Reminder before saving in the database
 */
class Reminder_temp
{
    private $RemindListId;
    public $name;
    public $description;
    public $dateTime;
    public function __construct($RemindListId, $name, $description, $dateTime)
    {
        $this->RemindListId = $RemindListId;
        $this->name         = $name;
        $this->description  = $description;
        $this->dateTime     = $dateTime;
    }
}


/**
 *represents an Reminder after saving in the database
 */
class Reminder extends Reminder_temp
{
    public $id;
    public function __construct($RemindListId, $id, $name, $description, $dateTime)
    {
        $this->id = $id;
        parent::__construct($RemindListId, $name, $description, $dateTime);
    }
}


/**
 *represent a RemindList before saving in the database
 */
class RemindList_temp
{
    public $userId;
    public $title;
    public $description;
    public function __construct($userId, $title, $description)
    {
        $this->userId      = $userId;
        $this->title       = $title;
        $this->description = $description;
    }
}


/**
 *represents error report of an Reminder
 */
class ReminderReport extends Report
{
    public $name = '';
    public $description = '';
    public $date = '';
}


/**
 *represent a RemindList before after in the database
 */
class RemindList extends RemindList_temp
{
    public $id;
    public $dateCreated;
    public $dateUpdated;
    public $publicKey;
    public function __construct($userId, $id, $title, $description, $dateCreated, $dateUpdated, $publicKey)
    {
        $this->id          = $id;
        $this->dateCreated = $dateCreated;
        $this->dateUpdated = $dateUpdated;
        $this->publicKey   = $publicKey;
        parent::__construct($userId, $title, $description);
    }

    /*
     *get all the Reminders in a Remind list
     *@output=>array of all the Reminders:Reminder array OR false if fails:bool 
     */
    public function getAllReminders()
    {
        $RemindersDb = new Reminders_DB;
        $result      = $RemindersDb->getAllReminders($this->id);
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
     *get all the Reminders in a Remind list
     *@output=>array of all the Reminders:Reminder array OR false if fails:bool 
     */
    public function getReminders($start, $limit)
    {
        $RemindersDb = new Reminders_DB;
        $result      = $RemindersDb->getReminders($this->id, $start, $limit);
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
     *get an Reminder from a a Remind list
     *@input=>Reminder id:int
     *@output=>the Reminder:Reminder  OR false if fails:bool 
     */
    public function getReminder($ReminderId)
    {
        $RemindersDb = new Reminders_DB;
        $result      = $RemindersDb->getReminder($this->id, $ReminderId);
        if ($result === false) {
            return false;
        } else {
            $row      = $result->fetch_array(MYSQLI_NUM);
            $Reminder = new Reminder($row[1], $row[0], $row[2], $row[3], $row[4]);
            return $Reminder;
        }
    }
    
    /*
     *add Reminder to a Remind list
     *@input=>Reminder:Reminder_temp
     *@output=>if the Reminder added successfully:bool
     */
    public function addReminder($Reminder)
    {
        $report = new ReminderReport;
        //check errors in "name" field
        if (trim($Reminder->name) == '') {
            $report->name = "name should not be empty";
            return $report;
        }
        //check errors in "date" field
        if (trim($Reminder->dateTime) == '') {
            $report->date = "date should not be empty";
            return $report;
        }
        $RemindersDb = new Reminders_DB;
        $result      = $RemindersDb->createReminder($this->id, $Reminder->name, $Reminder->description, $Reminder->dateTime);
        if ($result) {
            $report->isError = false;
        }
        return $report;
    }
    
    /*
     *remove Reminder from a Remind list
     *@input=>Reminder id:int
     *@output=>if the Reminder removed successfully:bool
     */
    public function removeReminder($ReminderId)
    {
        $RemindersDb = new Reminders_DB;
        $result      = $RemindersDb->confirmReminderOwnership($this->id, $ReminderId);
        if ($result) {
            return $RemindersDb->deleteReminder($ReminderId);
        }
        return false;
    }
    
    /*
     *change Reminder of a Remind list
     *@input=>Reminder:Reminder_temp
     *@output=>if the Reminder change successfully:bool
     */
    public function changeReminder($Reminder)
    {
        $RemindersDb = new Reminders_DB;
        $result      = $RemindersDb->changeReminder($Reminder->id, $Reminder->name, $Reminder->description, $Reminder->dateTime);
        return $result;
    }
}


/**
 *represent a RemindList manager
 */
class RemindListManager
{
    private $userId;
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /*
     *create a Remind list
     *@input=>Remind list: RemindList_Temp
     *@output=>if Remind list added successfully:bool
     */
    public function createRemindList($RemindList)
    {
        $report      = new Report;
        $RemindersDb = new Reminders_DB;
        $result      = $RemindersDb->createRemindList($this->userId, $RemindList->title, $RemindList->description);
        return $result;
    }
    
    /*
     *change a Remind list
     *@input=>Remind list: RemindList_Temp
     *@output=>if Remind list changed successfully:bool
     */
    public function changeRemindList($RemindList)
    {
        $RemindersDb = new Reminders_DB;
        $result      = $RemindersDb->changeRemindList($RemindList->id, $RemindList->title, $RemindList->description);
        return $result;
    }
    
    /*
     *change a Remind list
     *@input=>Remind list id: int
     *@output=>if Remind list deleted successfully:bool
     */
    public function deleteRemindList($RemindListId)
    {
        $RemindersDb = new Reminders_DB;
        $result      = $RemindersDb->confirmRemindlistOwnership($this->userId, $RemindListId);
        if ($result) {
            return $RemindersDb->deleteRemindList($RemindListId);
        }
        return false;
    }
    
    /*
     *get the Remind list added last
     *@output=>Remind list:RemindList OR false if fails:bool
     */
    public function getLatestRemindList()
    {
        $RemindersDb       = new Reminders_DB;
        $result            = $RemindersDb->getLatestRemindList($this->userId);
        $row               = $result->fetch_array(MYSQLI_NUM);
        $this->RemindLists = new RemindList($row[1], $row[0], $row[2], $row[3], $row[4], $row[5], $row[6]);
        return $this->RemindLists;
    }
    
    /*
     *get all the Remind list of a user
     *@output=>array of Remind lists:RemindList array 
     *@output=>false if fails:bool
     */
    public function getRemindListsOwned()
    {
        $RemindersDb = new Reminders_DB;
        $result      = $RemindersDb->getRemindListsOfOwner($this->userId);
        if ($result === false) {
            return false;
        } else {
            $i = 0;
            while ($row = $result->fetch_array(MYSQLI_NUM)) {
                $this->RemindLists[$i] = new RemindList($row[1], $row[0], $row[2], $row[3], $row[4], $row[5], $row[6]);
                $i                     = $i + 1;
            }
            return $this->RemindLists;
        }
    }
    
    /*
     *get limited no of Remind lists of a user
     *@input=>start index:int, no of reminders:int
     *@output=>array of Remind lists:RemindList array 
     *@output=>false if fails:bool
     */
    public function getListsOwned($start, $limit)
    {
        $RemindersDb = new Reminders_DB;
        $result      = $RemindersDb->getListsOfOwner($this->userId, $start, $limit);
        if ($result === false) {
            return false;
        } else {
            $i = 0;
            while ($row = $result->fetch_array(MYSQLI_NUM)) {
                $this->RemindLists[$i] = new RemindList($row[1], $row[0], $row[2], $row[3], $row[4], $row[5], $row[6]);
                $i                     = $i + 1;
            }
            return $this->RemindLists;
        }
    }
    
    /*
     *get a Remind list of a user by id
     *@input=>remind list id:int
     *@output=>Remind lists:RemindList 
     *@output=>false if fails:bool
     */
    public function getRemindListOwned($RemindListId)
    {
        $RemindersDb = new Reminders_DB;
        $result      = $RemindersDb->getRemindListOfOwner($this->userId, $RemindListId);
        if ($result === false) {
            return false;
        } else {
            $row        = $result->fetch_array(MYSQLI_NUM);
            $RemindList = new RemindList($row[1], $row[0], $row[2], $row[3], $row[4], $row[5], $row[6]);
            return $RemindList;
        }
    }
    
    /*
     *publish a Remind list
     *@output=>false if fails:bool
     */
    public function publishRemindList($RemindListId)
    {
        $RemindersDb = new Reminders_DB;
        //create random string that is not in table
        $strCreated  = false;
        while (!$strCreated) {
            $length  = 10;
            $charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            $str     = '';
            $count   = strlen($charset);
            while ($length--) {
                $str .= $charset[mt_rand(0, $count - 1)];
            }
            if ($RemindersDb->isRemindlistKey($str) === false) {
                $strCreated = true;
            }
        }
        $result = $RemindersDb->publishRemindList($this->userId, $RemindListId, $str);
        return $result;
    }
    
    /*
     *publish a Remind list
     *@output=>false if fails:bool
     */
    public function unpublishRemindList($RemindListId)
    {
        $RemindersDb = new Reminders_DB;
        $str         = NULL;
        $result      = $RemindersDb->publishRemindList($this->userId, $RemindListId, $str);
        return $result;
    }
}
?>
