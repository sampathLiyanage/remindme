<?php
/***
* developer: sampath liyanage
* phone no: +94778514847
*/


include_once "lib/auth.php";
include_once "lib/events.php";

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
*represents the form required to create a todo list
*/
class TodoListForm extends Form{
	
	protected $titleError='';
	protected $title='';
	protected $description='';
	protected $todoListId='';
	
	public function getHtml(){
		$html= '<h3>Todo List details</h3>
		<form id="todoList" action="todoLists.php?action=saveTodoList&" autocomplete="on" >
		<table>
		<tr><td>Title: </td><td><input type="text" name="title" value="'.$this->title.'"><br><font color="#FF0000">'.$this->titleError.'</font></td></tr>
		<tr><td>Desctiption: </td><td><textarea name="description" >'.$this->description.'</textarea></td></tr>
		<input type="hidden" name="action" value="saveTodoList">
		<input type="hidden" name="todoListId" value="'.$this->todoListId.'">
		<tr><td></td><td><input type="submit" name="submit" value="next" onclick="';
		
		$html.="$.ajax({
		url: 'todoLists.php?'+$('#todoList').serialize(),
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
                $tdManager= new TodoListManager($userId);
                $todoList= new TodoList_temp($userId,$this->title,$this->description);
                $result=$tdManager->createTodoList($todoList);

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
*represents the form required to create an event for a todo list
*/
class TodoEventForm extends Form{
	protected $name='';
	protected $description='';
	protected $date='';
	protected $todoListId='';
        protected $todoEventId='';
	
	protected $nameError='';
	protected $dateError='';
	
	public function getHtml(){
		$html= '<h3>Event details</h3>
                  <form id="event" action="todoLists.php?action=addEventToLatest&" autocomplete="on" >
                  <table>
                  <tr><td>Name: </td><td><input type="name" name="name" value="'.$this->name.'"><br><font color="#FF0000">'.$this->nameError.'</font></td></tr>
                  <tr><td>Desctiption: </td><td><textarea name="description" >'.$this->description.'</textarea></td></tr>
                  <tr><td>Date: </td><td><input id="datepicker" name="date" value="'.$this->date.'"><br><font color="#FF0000">'.$this->dateError.'</font></td></tr>
                  <input type="hidden" name="todoListId" value="'.$this->todoListId.'">
                  <input type="hidden" name="action" value="addEventToLatest">
                  <tr><td></td><td><input type="submit" name="submit" value="save and add another" onclick="';

        $html.="$.ajax({
        url: 'todoLists.php?'+$('#event').serialize()+'&submit=saveAndAdd',
        success: function(data) {
        $('#dialog').html(data);
        $( '#datepicker' ).datepicker({ dateFormat: 'yy-mm-dd' });
        }
        }); return false;\"";
        
        $html.='></td></tr><tr><td></td><td><input type="submit" name="submit" value="close" onclick="';
        
        $html.="location.href='home.php?act=alltodolistEvents&id=62".$this->todoEventId."'; return false;";
        
        $html.='"></td></tr>';
        $html.='</table>
                        </form>';
	
		return $html;
	}
	
	/*
	*set todo list id
	*/
	public function setTdListIdtoLatest(){
		$auth= new UserAuthenticator();
		$userId= $auth->getUserId($_SESSION['user'],$_SESSION['pw']);
		$tdManager= new TodoListManager($userId);
		if ($tdManager===false){
			header( 'Location: 404.html' ) ;
		}
		
		$todoList= $tdManager->getLatestTodoList();
		
		if ($todoList==false){
			
			header( 'Location: 404.html' ) ;
		}
		$this->todoListId=$todoList->id;
	}
        
        /*
         * set todo list id
         */
        public function setTdListId($id){
            $this->todoListId=$id;
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
        $tdManager= new TodoListManager($userId);
        $todoList= $tdManager->getTodoListOwned($this->todoListId);
        $result=$todoList->addEvent(new TodoList_event_temp($this->todoListId,$this->name,$this->description,$this->date));
        
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
			} else if($key == 'todoListId'){
				$this->todoListId=$element;
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
		if ($this->todoListId==''){
			$this->error=true;
			return false;
		}
	}
}

/*
 * represents a todo list edit form
 */
class TodoListEditForm extends TodoListForm{
    private $html;
    private $submit;
    public function __construct($todoListId) {
        $this->submit=false;
        $auth= new UserAuthenticator();
        $userId= $auth->getUserId($_SESSION['user'],$_SESSION['pw']);
        $tdManager= new TodoListManager($userId);
        $todoList=$tdManager->getTodoListOwned($todoListId);
	$this->title=$todoList->title;
	$this->description=$todoList->description;
	$this->todoListId=$todoList->id;
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
                $tdManager= new TodoListManager($userId);
                $todoList= $tdManager->getTodoListOwned($this->todoListId);
                $todoList->title=$this->title;
                $todoList->description=$this->description;
                $result=$tdManager->changeTodoList($todoList);

                if (!$result){
                        header( 'Location: 404.html' ) ;
                }

                return $result;
	}
        
        private function setHtml(){
		$this->html= '<h3>Todo List details</h3>
		<form id="todoList" action="todoLists.php?action=saveTodoList&" autocomplete="on" >
		<table>
		<tr><td>Title: </td><td><input type="text" name="title" value="'.$this->title.'"><br><font color="#FF0000">'.$this->titleError.'</font></td></tr>
		<tr><td>Desctiption: </td><td><textarea name="description" >'.$this->description.'</textarea></td></tr>
		<input type="hidden" name="action" value="editTodoList">
		<input type="hidden" name="todoListId" value="'.$this->todoListId.'">
		<tr><td></td><td><input type="submit" name="submit" value="save" onclick="';
		
		$this->html.="$.ajax({
		url: 'todoLists.php?'+$('#todoList').serialize()+'&action=editTodoList',
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
                $this->html='<h3>Todo List Saved</h3><input type="submit" name="submit" value="Close" 
                    onclick="location.href=\'home.php?act=allTodoLists\'">';
                
            } else {
                $this->setHtml();
            }
            
            
            return $this->html;
        }
}


/*
 * represents a todo list event edit form
 */
class TodoEventEditForm extends TodoEventForm{
    private $html;
    private $submit;
    public function __construct($todoListId, $eventId) {
        $this->submit=false;
        $auth= new UserAuthenticator();
        $userId= $auth->getUserId($_SESSION['user'],$_SESSION['pw']);
        $tdManager= new TodoListManager($userId);
        $todoList=$tdManager->getTodoListOwned($todoListId);
        $event=$todoList->getEvent($eventId);
	$this->name=$event->name;
	$this->description=$event->description;
	$this->date=$event->dateTime;
        $this->todoEventId=$event->id;
        $this->todoListId=$todoListId;       
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
                $tdManager= new TodoListManager($userId);
                $todoList= $tdManager->getTodoListOwned($this->todoListId);
                if ($todoList===false){
                        header( 'Location: 404.html' ) ;
                }
                
                $event=$todoList->getEvent($this->todoEventId);
                if ($event===false){
                        header( 'Location: 404.html' ) ;
                }
                
                $event->name=$this->name;
                $event->description=$this->description;
                $event->dateTime=$this->date;
                
                $result=$todoList->changeEvent($event);

                if (!$result){
                        header( 'Location: 404.html' ) ;
                }

                return $result;
	}
        
        public function setHtml(){
		$this->html= '<h3>Event details</h3>
                  <form id="event" action="todoLists.php?action=addEventToLatest&" autocomplete="on" >
                  <table>
                  <tr><td>Name: </td><td><input type="name" name="name" value="'.$this->name.'"><br><font color="#FF0000">'.$this->nameError.'</font></td></tr>
                  <tr><td>Desctiption: </td><td><textarea name="description" >'.$this->description.'</textarea></td></tr>
                  <tr><td>Date: </td><td><input id="datepicker" name="date" value="'.$this->date.'"><br><font color="#FF0000">'.$this->dateError.'</font></td></tr>
                  <input type="hidden" name="todoListId" value="'.$this->todoListId.'">
                  <input type="hidden" name="todoEventId" value="'.$this->todoEventId.'">
                  <input type="hidden" name="action" value="editTodoEvent">
                  <tr><td></td><td><input type="submit" name="submit" value="save" onclick="';

                $this->html.="$.ajax({
                url: 'todoLists.php?'+$('#event').serialize(),
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
                $this->html='<h3>Event Saved</h3><input type="submit" name="submit" value="Close" 
                    onclick="location.href=\'home.php?act=alltodolistEvents&id='.$this->todoListId.'\'">';
                
            } else {
                $this->setHtml();
            }
            
            
            return $this->html;
        }
}


?>
