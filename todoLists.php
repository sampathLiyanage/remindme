<?php
/***
* developer: sampath liyanage
* phone no: +94778514847
*/

/***
* this handles request from clients related to todo lists
* when a user creates a todo list as a sequence of form filling
*/

include_once "uiForms.php";
//check authentication
include_once "authenticate.php";


//if action is not set
if (!(isset($_GET['action']))){
        error(); 
}

$action=$_GET['action'];

//if user wants to create a new todo list
if ($action=='createTodoListForm'){
       $todolistForm=new TodoListForm();
       echo $todolistForm->getHtml();
}

else if($action=='TdListEditForm'){
       $todolistForm=new TodoListEditForm($_GET['id']);
       echo $todolistForm->getHtml();
}

else if($action=='editTodoList'){
       $todolistForm=new TodoListEditForm($_GET['todoListId']);
       $todolistForm->submit($_GET);
       echo $todolistForm->getHtml();
}

//if user wants to save a todolist
else if ($action=='saveTodoList'){
       $todolistForm=new TodoListForm();
       if ($todolistForm->submit($_GET)){
       		echo "<h4><font color='#008000'>todo list saved successfully. add events now</font></h4>";
	       	$eventForm=new TodoEventForm();
	       	$eventForm->setTdListIdtoLatest();
	       	echo $eventForm->getHtml();
       } else{
       		echo "<h4><font color='#FF0000'>Input Error</font></h4>";
       		echo $todolistForm->getHtml();
       }
}

//if user wants to add event to latest todo list created
else if ($action=='addEventToLatest'){
	$eventForm=new TodoEventForm();
	if($eventForm->submit($_GET)){
		echo "<h4><font color='#008000'>Event saved successfully. add another event or close</font></h4>";
		$eventForm->resetAllFields();
	} 
	echo $eventForm->getHtml();
}


?>




