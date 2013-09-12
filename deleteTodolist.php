<?php

/***
* developer: sampath liyanage
* phone no: +94778514847
*/

/***
* this file can be used as action of the form that deletes a todo list
* a get request should be sent to this
* get parameters 'id' (todo list id) should be set before
*/

include_once "lib/auth.php";
include_once 'lib/events.php';
include_once "authenticate.php";

if (isset($_GET['id'])){
	$auth=new UserAuthenticator();
	$userId=$auth->getUserId($_SESSION['user'], $_SESSION['pw']);
	$tdManager= new TodoListManager($userId);
	$todolist=$tdManager->deleteTodoList($_GET['id']);
}

header( 'Location: home.php?act=allTodoLists') ;

?>
