<?php 
/***
* developer: sampath liyanage
* phone no: +94778514847
*/

include_once "authenticate.php";

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
	
		$html.="<script>showUrlInDialog('todoLists.php?action=createTodoListForm')</script>";
	}
	
	//if user needs to see all the todo list created by him
	else if (isset($_GET['act']) && $_GET['act']=="allTodoLists"){
		$auth=new UserAuthenticator();
		$tdManager= new TodoListManager($auth->getUserId($_SESSION['user'], $_SESSION['pw']));
		$todolists=$tdManager->getTodoListsOwned();
	
		if ($todolists!=false){
			$html.='<div id="accordion" style="float:left; margin-left:20%; width:50%">';
			$i=1;
	
			foreach ($todolists as $tdlist) {
	
				$html.='<h3><table ><tr><td width="70%">'.$tdlist->title.'</td>
				<td ><input type="submit"  value="Show" onclick="location.href=\'home.php?act=alltodolistEvents&id='.$tdlist->id.'\'"/></td>
				<td><input type="submit" value="Delete" onclick="location.href=\'deleteTodolist.php?id='.$tdlist->id.'\'"/></td></tr></table></h3>
				<div>
					
				<p>
				<b>Created on: '.$tdlist->dateCreated.'</b><br>
				<b>Last update: '.$tdlist->dateUpdated.'</b><br><br>
				'.$tdlist->description.'
				</p></div>';
				$i++;
	
			}
	
			$html.='</div>';
		} else{
			$html.="<h3 style='margin-left:20%;'>Todo lists are empty</h3>";
		}
	}
	
	//if user needs to see all the events in a todo list created by him
	else if (isset($_GET['act']) && $_GET['act']=="alltodolistEvents" && isset($_GET['id'])){
		$auth=new UserAuthenticator();
		$tdManager= new TodoListManager($auth->getUserId($_SESSION['user'], $_SESSION['pw']));
		$todolist=$tdManager->getTodoListOwned($_GET['id']);
		$events=$todolist->getEvents();
	
		$html.='<div align="center" style="border:1px solid black; margin-left:20%"><p>To do list title: <b>'.$todolist->title.'
		</b>&emsp;&emsp;&emsp;Created on : <b>'.$todolist->dateCreated.'
		</b>&emsp;&emsp;&emsp;Updated on : <b>'.$todolist->dateUpdated.'</p></div><br>';
	
		if ($events!==false){
			$html.='
			<div id="accordion" style="float:left; margin-left:20%; width:30%">';
			$i=1;
			foreach ($events as $event) {
	
				$html.='<h3><table ><tr><td width="70%">'.$event->dateTime.'</td>
				<td><input type="submit" value="Delete" onclick="location.href=\'deleteTodoEvent.php?eid='.$event->id.'&tdid='.$todolist->id.'\'"/></td></tr></table></h3>
				<div >
				<p>
				<u><b>'.$event->name.'</b></u><br>'.$event->description.'
				</p></div>';
				$i++;
	
			}
			$html.='</div>';
		} else{
			$html.="<h3 style='margin-left:20%;'>Todo list is empty</h3>";
		}
	
		$html.='<div id="datepicker" style="float:right"></div>';
	}
	
	//return html of the page
	return $html;
}

?>
