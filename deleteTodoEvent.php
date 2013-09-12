<?php
/***
* developer: sampath liyanage
* phone no: +94778514847
*/

/***
* this file can be used as action of the form that deletes a todo list event
* a get request should be sent to this
* get parameters 'tdid' (todo list id) and 'eid' (event id) should be set before
*/


include_once "lib/auth.php";
include_once 'lib/events.php';
include_once "authenticate.php";


if (isset($_GET['tdid']) && isset($_GET['eid'])){
	$auth=new UserAuthenticator();
	$userId=$auth->getUserId($_SESSION['user'], $_SESSION['pw']);
	$tdManager= new TodoListManager($userId);
	$todolist=$tdManager->getTodoListOwned($_GET['tdid']);
	if ($todolist!==false){
		$todolist->removeEvent($_GET['eid']);
	}
}

header( 'Location: home.php?act=alltodolistEvents&id='.$_GET['tdid']) ;

?>
