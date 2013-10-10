<?php

/***++++++
* developer: sampath liyanage
* phone no: +94778514847
*/


include_once "notifierDb.php";

class Message{
    public $to;
    public $from;
    public $subject;
    public $body;
    
    public function __construct($to, $from, $subject, $body) {
        $this->to=$to;
        $this->from=$from;
        $this->subject=$subject;
        $this->body=$body;
    }
    
    public function send(){
        echo $this->to."<br>";
        echo $this->subject."<br>";
        echo $this->body."<br>";
        echo $this->from."<br><br>";
        mail($this->to,$this->subject,$this->body,"From:" . $this->from);    
    }
}


class Notifier{
    public function sendMail($date){
        $reminders=$this->getReminders($date);
        
        if ($reminders===false){
            return false;
        } 
        
        $messages=$this->prepairMessages($reminders);
        
        if ($messages===false){
            return false;
        }
        
       $this->sendMessages($messages);
        return true;
    }
    
    private function getReminders($date){
        $nDb= new Notifier_DB();
        $reminders=$nDb->getReminders($date);
        return $reminders;
    }
    
    private function prepairMessages($reminders){
        
        $i=0;
        while ($row = $reminders->fetch_array(MYSQLI_NUM))
        {       
                 $to=$row[5];
                 $from="reminders@remindme.com";
                 $subject=$row[0]." - ".$row[2];
                 $body="Dear ".$row[4].",<br>You have new reminder<br><br>";
                 $body.="<b><u>".$row[0]."</b></u><br>".$row[1]."<br><br>";
                 $body.="<b><u>".$row[2]."</b></u><br>".$row[3]."<br><br>";
                 
                 $messages[$i]=new Message($to,$from,$subject,$body);
                 
                 $i=$i+1;
        }
        return $messages;
               
    }
    
    private function sendMessages($messages){
        foreach ($messages as $message){
            $message->send();
        }
    }
}
?>
