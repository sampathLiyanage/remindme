<?php 
/***
* developer: sampath liyanage
* phone no: +94778514847
*/

include_once "authenticate.php";
include_once 'lib/events.php';
include_once 'uiPages.php';

/*
* html code for showing events created by a user
*@output=> html code:string
*/
function getMyeventsPageHtml(){
	
	$html=  '<div id=leftPanel style="float:left">
	<ul id="menu" style="width: 150px; ">
	<li>
	<a href="#">Todo Lists</a>
	<ul>
	<li><a id="create-user" href="#" onclick="location.href=\'home.php?act=createTodoListForm\'">Create New Todo List</a></li>
	<li><a href="#" onclick="location.href=\'home.php?act=allTodoLists\'"> List All Todo Lists</a></li>
	</ul>
	</li>
	
	<li class="ui-state-disabled"><a href="#">Time Tables</a></li>
	<li class="ui-state-disabled"><a href="#">Schedules</a></li>
	</ul></div>';
	
	//if user needs to create a new todo list
	if (isset($_GET['act']) && $_GET['act']=="createTodoListForm"){
	
		$html.="<script>showUrlInDialog('todoLists.php?action=createTodoListForm', 'new todo list')</script>";
	}
        
        if (isset($_GET['act']) && $_GET['act']=="TdListEditForm"){
	
		$html.="<script>showUrlInDialog('todoLists.php?action=TdListEditForm&id=".$_GET['id']."','edit to do list')</script>";
	}
	
        if (isset($_GET['act']) && isset($_GET['eid']) && isset($_GET['tdid']) && $_GET['act']=="tdEventEditForm"){
	
		$html.="<script>showUrlInDialog('todoLists.php?action=tdEventEditForm&eid=".$_GET['eid']."&tdid=".$_GET['tdid']."','edit to do list'); </script>";
	}
        
        if (isset($_GET['act']) && $_GET['act']=="newTodoEventForm"){
	
		$html.="<script>showUrlInDialog('todoLists.php?action=newTodoEventForm&id=".$_GET['id']."','New Event')</script>";
	}
        
	//if user needs to see all the todo list created by him
	else if (isset($_GET['act']) && $_GET['act']=="allTodoLists"){
		$todolistPage=new TodoListsPage();
                $html.=$todolistPage->getHtml();
	}
	
	//if user needs to see all the events in a todo list created by him
	else if (isset($_GET['act']) && $_GET['act']=="alltodolistEvents"){
		$todoeventsPage=new TodoEventsPage($_GET);
                $html.=$todoeventsPage->getHtml();
	}
	
	//return html of the page
	return $html;
}

?>
