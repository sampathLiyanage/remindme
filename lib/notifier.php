<?php
/***
 * developer: sampath liyanage
 * phone no: +94778514847
 */

include_once "notifierDb.php";

/**
 * represents a message to send as a notification
 */
class Message
{
    public $to;
    public $from;
    public $subject;
    public $body;

    public function __construct($to, $from, $subject, $body)
    {
        $this->to      = $to;
        $this->from    = $from;
        $this->subject = $subject;
        $this->body    = $body;
    }
    
    /*
     * send message
     */
    public function send()
    {
        echo "to: " . $this->to . "<br>";
        echo "form: " . $this->from . "<br><br>";
        echo "subject: " . $this->subject . "<br><br>";
        echo "body: <br>" . $this->body . "<br>";
        mail($this->to, $this->subject, $this->body, "From:" . $this->from);
    }
}


/**
 * notification manager
 */
class Notifier
{
	private $date;
    /*
     * to be called by a corn operation from the server
     */
    public function sendMail()
    {
        //geting current date
        $date = date("Y-m-d");
        $this->date=$date;
        $date .= " 00:00:00";
        $reminders = $this->getReminders($date);
        if ($reminders === false) {
            return false;
        }
        $messages = $this->prepairMessages($reminders);
        if ($messages === false) {
            return false;
        }
        $this->sendMessages($messages);
        return true;
    }

    /*
     * get reminders to send for a date
     * @input=>date:String
     * @output=>reminders:array of Reminder
     */
    private function getReminders($date)
    {
        $nDb       = new Notifier_DB();
        $reminders = $nDb->getReminders($date);
        return $reminders;
    }
    
    /*
     * prepaire message format
     * @input=> reminders:array of Reminder
     * @output=> messages:array of Message
     */
    private function prepairMessages($reminders)
    {
        $i = 0;
        while ($row = $reminders->fetch_array(MYSQLI_NUM)) {
            $to      = $row[5];
            $from    = "reminders@remindme.com";
            $subject = $row[0] . " - " . $row[2];
            $body    = "Dear " . $row[4] . ",<br>You have a new reminder ";
            $body .= "For <b>" . $this->date . "</b><br><br>";
            $body .= "<b><u>" . $row[2] . "</b></u><b>" . " (" . $row[0] . ")</b><br><br>";
            $body .= "<b>" . $row[3] . "</b>";
            $messages[$i] = new Message($to, $from, $subject, $body);
            $i            = $i + 1;
        }
        return $messages;
    }
    
    /*
     * sending messages
     * @input=>messages:array of Message
     */
    private function sendMessages($messages)
    {
        foreach ($messages as $message) {
            $message->send();
        }
    }
}
?>
