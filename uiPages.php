<?php
/***
* developer: sampath liyanage
* phone no: +94778514847
*/

/***
 * This page contains classes those represent pages
 */
include_once "authenticate.php";
include_once 'lib/events.php';
include_once 'lib/auth.php';

abstract class Page{
    
    /*
     * return html of the page
     */
    abstract public function getHtml();
    
}

class TodoListsPage extends Page{
    
    
    public function getHtml(){
       $auth=new UserAuthenticator();
            $tdManager= new TodoListManager($auth->getUserId($_SESSION['user'], $_SESSION['pw']));
            $todolists=$tdManager->getTodoListsOwned();

            if ($todolists!=false){
                    $html='<div id="accordion" style="float:left; margin-left:20%; width:50%">';
                    $i=1;

                    foreach ($todolists as $tdlist) {

                            $html.='<h3><table ><tr><td width="70%">'.$tdlist->title.'</td>
                            <td ><input type="submit"  value="Show" onclick="location.href=\'home.php?act=alltodolistEvents&id='.$tdlist->id.'\'"/></td>
                            <td><input type="submit" value="Edit" onclick="location.href=\'home.php?act=TdListEditForm&id='.$tdlist->id.'\'"/></td>				
                            <td><input type="submit" value="Delete" onclick="$( \'#dialog-confirm\' ).dialog({
                            
                            buttons: {
                              \'Delete\': function() {
                                location.href=\'deleteTodolist.php?id='.$tdlist->id.'\';
                              },
                              Cancel: function() {
                                $( this ).dialog( \'close\' );
                              }
                            }
                          });"/></td>
                            </tr></table></h3>
                            <div>

                            <p>
                            <b>Created on: '.$tdlist->dateCreated.'</b><br>
                            <b>Last update: '.$tdlist->dateUpdated.'</b><br><br>
                            '.$tdlist->description.'
                            </p></div>';
                            $i++;

                    }

                    $html.='</div><div style="display: none;" id="dialog-confirm" title="Delete Todo List?">This action will delete this todo list and all the events in this todo list. Are you sure?
                      </div>';
            } else{
                    $html.="<h3 style='margin-left:20%;'>Todo lists are empty</h3>";
            }
            return $html;
    }
    
}

class TodoEventsPage extends Page{
    private $todoListId;
    
    //input is $_GET variable
    public function __construct($array) {
        if (!isset($array['id'])){
            header( 'Location: 404.html' ) ;
        }
        
        $this->todoListId=$array['id'];
    }
    
    public function getHtml() {
        $auth=new UserAuthenticator();
            $tdManager= new TodoListManager($auth->getUserId($_SESSION['user'], $_SESSION['pw']));
            $todolist=$tdManager->getTodoListOwned($this->todoListId);
            $events=$todolist->getEvents();

            $html='<div><div align="center" style="border:1px solid black; margin-left:20%"><p>To do list title: <b>'.$todolist->title.'
            </b>&emsp;&emsp;&emsp;Created on : <b>'.$todolist->dateCreated.'
            </b>&emsp;&emsp;&emsp;Updated on : <b>'.$todolist->dateUpdated.'</p>
            <div align="right"><table><tr><td><input type="submit" value="Add new event" onclick="location.href=\'home.php?act=newTodoEventForm&id='.$this->todoListId.'\'"/></td>
            <td><input type="submit" value="Publish" onclick="location.href=\'deleteTodoEvent.php?eid=\'"/></td></tr></table></div>   
            </div></div><br>';

            if ($events!==false){
                    $html.='
                    <div id="accordion" style="float:left; margin-left:20%; width:30%">';
                    $i=1;
                    foreach ($events as $event) {

                            $html.='<h3><table ><tr><td width="70%">'.$event->dateTime.'</td>
                            <td><input type="submit" value="Edit" onclick="location.href=\'home.php?act=tdEventEditForm&eid='.$event->id.'&tdid='.$todolist->id.'\'"/></td>
                            <td><input type="submit" value="Delete" onclick="$( \'#dialog-confirm\' ).dialog({
                            
                            buttons: {
                              \'Delete\': function() {
                                location.href=\'deleteTodoEvent.php?eid='.$event->id.'&tdid='.$todolist->id.'\';
                              },
                              Cancel: function() {
                                $( this ).dialog( \'close\' );
                              }
                            }
                          });"/></td></tr></table></h3>
                            <div >
                            <p>
                            <u><b>'.$event->name.'</b></u><br>'.$event->description.'
                            </p></div>';
                            $i++;

                    }
                    $html.='</div><div style="display: none;" id="dialog-confirm" title="Delete Todo List Item?">This action will delete this event from the todo list. Are you sure?
                      </div>';
            } else{
                    $html.="<h3 style='margin-left:20%;'>Todo list is empty</h3>";
            }

            $html.='<div id="datepicker" style="float:right"></div>';
            return $html;
    }
}
?>
