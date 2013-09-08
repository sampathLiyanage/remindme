<?php
include_once "lib/auth.php";
include_once "lib/events.php";
//check authentication
include_once "authenticate.php";

/*############## functions related to drawing html code on the page ###########################*/

/**
*to be called if request has no action
*/
function error(){
        echo "<h1>something went wrong!!!</h1>";
        exit(0); 
}


function drawTdListCreateForm($titleError=''){
        $htmlCode= '
                  <form id="todoList" action="todoLists.php?action=saveTodoList&" autocomplete="on" >
                  <table>
                  <tr><td>Title: </td><td><input type="text" name="title">'.$titleError.'</td></tr>
                  <tr><td>Desctiption: </td><td><textarea name="description"></textarea></td></tr>
                  <input type="hidden" name="action" value="saveTodoList">
                  <tr><td></td><td><input type="submit" name="submit" value="next" onclick="';
                  
        $htmlCode.="loadXMLDoc('todoLists.php?'+$('#todoList').serialize()); return false;";
        $htmlCode.='"></td></tr>
                        </table>
                        </form>';
        echo $htmlCode;
}


function drawEventCreateForm($todoListId, $report){
        if ($report==null){
                $nameError='';
                $dateError='';
        } else{
                
                $nameError="<p style='color:#FF0000'>".$report->name."</p>";
                $dateError="<p style='color:#FF0000'>".$report->date."</p>";
        }
        $htmlCode= '
                  <form id="event" action="todoLists.php?action=addEventToLatest&" autocomplete="on" >
                  <table>
                  <tr><td>Name: </td><td><input type="name" name="name">'.$nameError.'</td></tr>
                  <tr><td>Desctiption: </td><td><textarea name="description"></textarea></td></tr>
                  <tr><td>Date: </td><td><input id="datepicker" name="date">'.$dateError.'</td></tr>
                  <input type="hidden" name="todoListId" value="'.$todoListId.'">
                  <input type="hidden" name="action" value="addEventToLatest">
                  <tr><td></td><td><input type="submit" name="submit" value="save and add another" onclick="';
                  
        $htmlCode.="loadXMLDoc('todoLists.php?'+$('#event').serialize()+'&submit=saveAndAdd'); return false;";
        $htmlCode.='"></td></tr>';
        $htmlCode.='<tr><td></td><td><input type="submit" name="submit" value="saveAndFinish" onclick="';
        $htmlCode.="loadXMLDoc('todoLists.php?'+$('#event').serialize()+'&submit=saveAndFinish'); return false;";
        $htmlCode.='"></td></tr>';
        $htmlCode.='</table>
                        </form>';
        echo $htmlCode;
}

/*######################## functions to interact with library classes)################################*/

function saveTodoList(){
       
        $auth= new UserAuthenticator();
        $userId= $auth->getUserId($_SESSION['user'],$_SESSION['pw']);
        $tdManager= new TodoListManager($userId);
        $todoList= new TodoList_temp($userId,$_GET['title'],$_GET['description']);
        $report=$tdManager->createTodoList($todoList);
        if ($report->isError){
                drawTdListCreateForm("<p style='color:#FF0000'>".$report->report."</p>");
        } else{
                echo  "<p style='color:#b0e0e6'>Todo List  added successfully. Add events to the Todo List now</p>";
                addEventToLatest();
        }
}

function addEventToLatest($report=null){
        $auth= new UserAuthenticator();
        $userId= $auth->getUserId($_SESSION['user'],$_SESSION['pw']);
        $tdManager= new TodoListManager($userId);
        $todoList= $tdManager->getLatestTodoList();
        
        drawEventCreateForm($todoList->id,$report);
}

//returns report object of a newly created event
function saveEvent(){
        $auth= new UserAuthenticator();
        $userId= $auth->getUserId($_SESSION['user'],$_SESSION['pw']);
        $tdManager= new TodoListManager($userId);
        $todoList= $tdManager->getTodoListOwned($_GET['todoListId']);
        $report=$todoList->addEvent(new TodoList_event_temp($_GET['todoListId'],$_GET['name'],$_GET['description'],$_GET['date']));
        if ($report->isError){
                echo  "<p style='color:#FF0000'>".$report->report."</p>";
        } else{
                echo  "<p style='color:#b0e0e6'>Event added successfully</p>";
        }
        return $report;
}

/*############################### functions for handling requests from clients #################*/

//if action is not set
if (!(isset($_GET['action']))){
        error(); 
}

$action=$_GET['action'];

if ($action=='createTodoListForm'){
       drawTdListCreateForm();
}

else if ($action=='saveTodoList'){
       saveTodoList();
}

else if ($action=='addEventToLatest'){
        if (!(isset($_GET['submit']))){
                error(); 
        }
        $submit=$_GET['submit'];
        
        if ($submit=="saveAndAdd"){
                addEventToLatest(saveEvent());
        } else if ($submit=="saveAndFinish"){
                $report=saveEvent();
                if($report->isError){
                        addEventToLatest($report);
                } else{
                        echo "<p style='color:#b0e0e6'>Todo List Created</p>";
                }
        }
}


?>




