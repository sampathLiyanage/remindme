<?php
/***
* developer: sampath liyanage
* phone no: +94778514847
*/


include_once "lib/auth.php";
include_once "lib/reminders.php";

/**
* structure of a class that represents a form
*/
abstract class Form{
	protected $error=false;
	
	/*
	*function to get html code of a form
	*@output=>html code:string
	*/
	abstract public function getHtml();
	
	/*
	*function to submit a form
	*@input=>array of inputs to the form:array
	*@output=>if submission succeeded or not:bool
	*/
	abstract public function submit($array);
	
	/*
	*function to validate inputs of a submited form
	*@input=>array of inputs to to form:array
	*this should set parameters those represents the errors
	*@output=>validation:bool
	*/
	abstract protected function validateInputs($array);
}

/**
*represents the form required to create a Remind list
*/
class RemindListForm extends Form{
	
	protected $titleError='';
	protected $title='';
	protected $description='';
	protected $RemindListId='';
	
	public function getHtml(){
		$html= '<h3>Remind-List details</h3>
		<form id="RemindList" action="reminders.php?action=saveRemindList&" autocomplete="on" >
		<table>
		<tr><td>Title: </td><td><input type="text" name="title" value="'.$this->title.'"><br><font color="#FF0000">'.$this->titleError.'</font></td></tr>
		<tr><td>Desctiption: </td><td><textarea name="description" >'.$this->description.'</textarea></td></tr>
		<input type="hidden" name="action" value="saveRemindList">
		<input type="hidden" name="RemindListId" value="'.$this->RemindListId.'">
		<tr><td></td><td><input type="submit" name="submit" value="next" onclick="';
		
		$html.="$.ajax({
		url: 'reminders.php?'+$('#RemindList').serialize(),
		success: function(data) {
		$('#dialog').html(data);
		$( '#datepicker' ).datepicker({ dateFormat: 'yy-mm-dd' });
		}
		}); return false;";
		
		$html.='"></td></tr>
		</table>
		</form>';
		
		return $html;
	}
	
	public function submit($array){
		$this->validateInputs($array);
		if ($this->error){
			return false;
		} 
		$auth= new UserAuthenticator();
                $userId= $auth->getUserId($_SESSION['user'],$_SESSION['pw']);
                $tdManager= new RemindListManager($userId);
                $RemindList= new RemindList_temp($userId,$this->title,$this->description);
                $result=$tdManager->createRemindList($RemindList);

                if (!$result){
                        header( 'Location: 404.html' ) ;
                }

                return $result;
	}
	
	protected function validateInputs($array){
		foreach ($array as $key=>$element)  {
			$element=htmlspecialchars($element, ENT_QUOTES, 'UTF-8');
   			 if ($key == 'title') {
        		$this->title=$element;
                        } else if($key == 'description'){
                                $this->description=$element;
                        }
		}
		
		$this->validateTitle();
	}
	
	private function validateTitle(){
		if ($this->title==''){
			$this->error=true;
			$this->titleError="Title cannot be empty";
			return false;
		}
			
	}
}


/**
*represents the form required to create an Reminder for a Remind list
*/
class ReminderForm extends Form{
	protected $name='';
	protected $description='';
	protected $date='';
	protected $RemindListId='';
        protected $ReminderId='';
	
	protected $nameError='';
	protected $dateError='';
	
	public function getHtml(){
		$html= '<h3>Reminder details</h3>
                  <form id="Reminder" action="reminders.php?action=addReminderToLatest&" autocomplete="on" >
                  <table>
                  <tr><td>Name: </td><td><input type="name" name="name" value="'.$this->name.'"><br><font color="#FF0000">'.$this->nameError.'</font></td></tr>
                  <tr><td>Desctiption: </td><td><textarea name="description" >'.$this->description.'</textarea></td></tr>
                  <tr><td>Date: </td><td><input id="datepicker" name="date" value="'.$this->date.'"><br><font color="#FF0000">'.$this->dateError.'</font></td></tr>
                  <input type="hidden" name="RemindListId" value="'.$this->RemindListId.'">
                  <input type="hidden" name="action" value="addReminderToLatest">
                  <tr><td></td><td><input type="submit" name="submit" value="save and add another" onclick="';

        $html.="$.ajax({
        url: 'reminders.php?'+$('#Reminder').serialize()+'&submit=saveAndAdd',
        success: function(data) {
        $('#dialog').html(data);
        $( '#datepicker' ).datepicker({ dateFormat: 'yy-mm-dd' });
        }
        }); return false;\"";
        
        $html.='></td></tr><tr><td></td><td><input type="submit" name="submit" value="close" onclick="';
        
        $html.="location.href='home.php?act=allRemindlistReminders&id=".$this->RemindListId."'; return false;";
        
        $html.='"></td></tr>';
        $html.='</table>
                        </form>';
	
		return $html;
	}
	
	/*
	*set Remind list id
	*/
	public function setTdListIdtoLatest(){
		$auth= new UserAuthenticator();
		$userId= $auth->getUserId($_SESSION['user'],$_SESSION['pw']);
		$tdManager= new RemindListManager($userId);
		if ($tdManager===false){
			header( 'Location: 404.html' ) ;
		}
		
		$RemindList= $tdManager->getLatestRemindList();
		
		if ($RemindList==false){
			
			header( 'Location: 404.html' ) ;
		}
		$this->RemindListId=$RemindList->id;
	}
        
        /*
         * set Remind list id
         */
        public function setTdListId($id){
            $this->RemindListId=$id;
        }
	
	/*
	*reset all the fields of a form. 
	*if a submited form object is reused again to create new form this shoul be called
	*/
	public function resetAllFields(){
		$this->name='';
		$this->description='';
		$this->date='';
		
		$this->nameError='';
		$this->dateError='';
	}
	
	public function submit($array){
		$this->validateInputs($array);
		if ($this->error){
			return false;
		}
		
		$auth= new UserAuthenticator();
        $userId= $auth->getUserId($_SESSION['user'],$_SESSION['pw']);
        $tdManager= new RemindListManager($userId);
        $RemindList= $tdManager->getRemindListOwned($this->RemindListId);
        $result=$RemindList->addReminder(new Reminder_temp($this->RemindListId,$this->name,$this->description,$this->date));
        
        if (!$result){
        	header( 'Location: 404.html' ) ;
        }
        
        return $result;
	}
	
	protected function validateInputs($array){
		
		foreach ($array as $key=>$element) {
			$element=htmlspecialchars($element, ENT_QUOTES, 'UTF-8');
			if ($key == 'name') {
				$this->name=$element;
			} else if($key == 'description'){
				$this->description=$element;
			} else if($key == 'date'){
				$this->date=$element;
			} else if($key == 'RemindListId'){
				$this->RemindListId=$element;
			}
		}
		$this->validateName();
		$this->validateDate();
		$this->validateListId();
	}
	
	private function validateName(){
		if ($this->name==''){
			$this->error=true;
			$this->nameError="Name cannot be empty";
			return false;
		}
	}
	
	private function validateDate(){
		if ($this->date==''){
			$this->error=true;
			$this->dateError="Date cannot be empty";
			return false;
		}
	}
	
	private function validateListId(){
		if ($this->RemindListId==''){
			$this->error=true;
			return false;
		}
	}
}

/*
 * represents a Remind list edit form
 */
class RemindListEditForm extends RemindListForm{
    private $html;
    private $submit;
    public function __construct($RemindListId) {
        $this->submit=false;
        $auth= new UserAuthenticator();
        $userId= $auth->getUserId($_SESSION['user'],$_SESSION['pw']);
        $tdManager= new RemindListManager($userId);
        $RemindList=$tdManager->getRemindListOwned($RemindListId);
	$this->title=$RemindList->title;
	$this->description=$RemindList->description;
	$this->RemindListId=$RemindList->id;
        $this->setHtml();
    }
    
    public function submit($array){
                $this->submit=true;
		$this->validateInputs($array);
                if ($this->error){
                    return false;
                }
		$auth= new UserAuthenticator();
                $userId= $auth->getUserId($_SESSION['user'],$_SESSION['pw']);
                $tdManager= new RemindListManager($userId);
                $RemindList= $tdManager->getRemindListOwned($this->RemindListId);
                $RemindList->title=$this->title;
                $RemindList->description=$this->description;
                $result=$tdManager->changeRemindList($RemindList);

                if (!$result){
                        header( 'Location: 404.html' ) ;
                }

                return $result;
	}
        
        private function setHtml(){
		$this->html= '<h3>Remind-List details</h3>
		<form id="RemindList" action="reminders.php?action=saveRemindList&" autocomplete="on" >
		<table>
		<tr><td>Title: </td><td><input type="text" name="title" value="'.$this->title.'"><br><font color="#FF0000">'.$this->titleError.'</font></td></tr>
		<tr><td>Desctiption: </td><td><textarea name="description" >'.$this->description.'</textarea></td></tr>
		<input type="hidden" name="action" value="editRemindList">
		<input type="hidden" name="RemindListId" value="'.$this->RemindListId.'">
		<tr><td></td><td><input type="submit" name="submit" value="save" onclick="';
		
		$this->html.="$.ajax({
		url: 'reminders.php?'+$('#RemindList').serialize()+'&action=editRemindList',
		success: function(data) {
		$('#dialog').html(data);
		$( '#datepicker' ).datepicker({ dateFormat: 'yy-mm-dd' });
		}
		}); return false;";
		
		$this->html.='"></td></tr>
		</table>
		</form>';
		
	}
        
        public function getHtml() {
            if (!$this->error && $this->submit){
                $this->html='<h3>Remind List Saved</h3><input type="submit" name="submit" value="Close" 
                    onclick="location.href=\'home.php?act=allRemindLists\'">';
                
            } else {
                $this->setHtml();
            }
            
            
            return $this->html;
        }
}


/*
 * represents a Remind list Reminder edit form
 */
class ReminderEditForm extends ReminderForm{
    private $html;
    private $submit;
    public function __construct($RemindListId, $ReminderId) {
        $this->submit=false;
        $auth= new UserAuthenticator();
        $userId= $auth->getUserId($_SESSION['user'],$_SESSION['pw']);
        $tdManager= new RemindListManager($userId);
        $RemindList=$tdManager->getRemindListOwned($RemindListId);
        $Reminder=$RemindList->getReminder($ReminderId);
	$this->name=$Reminder->name;
	$this->description=$Reminder->description;
	$this->date=$Reminder->dateTime;
        $this->ReminderId=$Reminder->id;
        $this->RemindListId=$RemindListId;       
        $this->setHtml();
    }
    
    public function submit($array){
                $this->submit=true;
		$this->validateInputs($array);
                if ($this->error){
                    return false;
                }
		$auth= new UserAuthenticator();
                $userId= $auth->getUserId($_SESSION['user'],$_SESSION['pw']);
                $tdManager= new RemindListManager($userId);
                $RemindList= $tdManager->getRemindListOwned($this->RemindListId);
                if ($RemindList===false){
                        header( 'Location: 404.html' ) ;
                }
                
                $Reminder=$RemindList->getReminder($this->ReminderId);
                if ($Reminder===false){
                        header( 'Location: 404.html' ) ;
                }
                
                $Reminder->name=$this->name;
                $Reminder->description=$this->description;
                $Reminder->dateTime=$this->date;
                
                $result=$RemindList->changeReminder($Reminder);

                if (!$result){
                        header( 'Location: 404.html' ) ;
                }

                return $result;
	}
        
        public function setHtml(){
		$this->html= '<h3>Reminder details</h3>
                  <form id="Reminder" action="reminders.php?action=addReminderToLatest&" autocomplete="on" >
                  <table>
                  <tr><td>Name: </td><td><input type="name" name="name" value="'.$this->name.'"><br><font color="#FF0000">'.$this->nameError.'</font></td></tr>
                  <tr><td>Desctiption: </td><td><textarea name="description" >'.$this->description.'</textarea></td></tr>
                  <tr><td>Date: </td><td><input id="datepicker" name="date" value="'.$this->date.'"><br><font color="#FF0000">'.$this->dateError.'</font></td></tr>
                  <input type="hidden" name="RemindListId" value="'.$this->RemindListId.'">
                  <input type="hidden" name="ReminderId" value="'.$this->ReminderId.'">
                  <input type="hidden" name="action" value="editReminder">
                  <tr><td></td><td><input type="submit" name="submit" value="save" onclick="';

                $this->html.="$.ajax({
                url: 'reminders.php?'+$('#Reminder').serialize(),
                success: function(data) {
                $('#dialog').html(data);
                $( '#datepicker' ).datepicker({ dateFormat: 'yy-mm-dd' });
                }
                }); return false;";
                $this->html.='"></td></tr>';
                $this->html.='</table>
                                </form>';
	
		
	}
        
        public function getHtml() {
            if (!$this->error && $this->submit){
                $this->html='<h3>Reminder Saved</h3><input type="submit" name="submit" value="Close" 
                    onclick="location.href=\'home.php?act=allRemindlistReminders&id='.$this->RemindListId.'\'">';
                
            } else {
                $this->setHtml();
            }
            
            
            return $this->html;
        }
}


?>
