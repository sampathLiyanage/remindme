<?php
/***
* developer: sampath liyanage
* phone no: +94778514847
*/

/***
 * This page contains classes those represent pages
 */

include_once 'lib/reminders.php';
include_once 'lib/subscription.php';
include_once 'lib/auth.php';


abstract class Page{
    
    /*
     * return html of the page
     */
    abstract public function getHtml();
    
}

class RemindListsPage extends Page{
    
    
    public function getHtml($pageNo=1){
            $auth=new UserAuthenticator();
            $tdManager= new RemindListManager($auth->getUserId($_SESSION['user'], $_SESSION['pw']));
            $remindlists=$tdManager->getRemindListsOwned();
             $itemsPerPage=4;
             $noOfPages=(count($remindlists)-1)/$itemsPerPage +1;
             $tdManager= new RemindListManager($auth->getUserId($_SESSION['user'], $_SESSION['pw']));
             $remindlists=$tdManager->getListsOwned(($pageNo-1)*$itemsPerPage,$itemsPerPage);
            
            $html=  '<div id=leftPanel style="float:left">
            <ul id="menu" style="width: 150px; ">
            <li ><a href="#" onclick="location.href=\'home.php?act=allRemindLists\'">Show all lists</a></li>
            <li ><a href="#" onclick="location.href=\'home.php?act=newRemindListForm\'">Create new list</a></li>
            </ul></div>';

            if ($remindlists!=false){
                $html.='<div style="float:left; margin-left:20%; width:50%">page:';
                
                for($i=1; $i<=$noOfPages; $i++){
                    $html.='<a onclick="location.href=\'home.php?act=remindLists&page='.$i.'\'">'.$i.'</a>';
                 }
                   $html.= '<br><br></div>';
                    $html.='<div id="accordion" style="float:left; margin-left:20%; width:50%">';
                
                    $i=1;

                    foreach ($remindlists as $tdlist) {

                            $html.='<h3><table ><tr><td width="70%">'.$tdlist->title.'</td>
                            <td ><input type="submit"  value="Show" onclick="location.href=\'home.php?act=allRemindlistReminders&id='.$tdlist->id.'\'"/></td>
                            <td><input type="submit" value="Edit" onclick="location.href=\'home.php?act=TdListEditForm&id='.$tdlist->id.'\'"/></td>				
                            <td><input type="submit" value="Delete" onclick="$( \'#dialog-confirm\' ).dialog({
                            
                            buttons: {
                              \'Delete\': function() {
                                location.href=\'deleteRemindlist.php?id='.$tdlist->id.'\';
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
                    $html.='</div>';
                    
                    $html.='<div style="display: none;" id="dialog-confirm" title="Delete Remind List?">This action will delete this Remind list and all the Reminders in this Remind list. Are you sure?
                      </div>';
            } else{
                    $html.="<h3 style='margin-left:20%;'>Remind lists are empty</h3>";
            }
            return $html;
    }
    
}

class RemindersPage extends Page{
    protected $remindListId;
    
    //input is $_GET variable
    public function __construct($array) {
        if (!isset($array['id'])){
            header( 'Location: 404.html' ) ;
        }
        
        $this->remindListId=$array['id'];
    }
    
    public function getHtml($pageNo=1) {
            $auth=new UserAuthenticator();
            $tdManager= new RemindListManager($auth->getUserId($_SESSION['user'], $_SESSION['pw']));
            $remindlist=$tdManager->getRemindListOwned($this->remindListId);
            $reminders=$remindlist->getAllReminders();
            $itemsPerPage=4;
            $noOfPages=(count($reminders)-1)/$itemsPerPage +1;
            
            $tdManager= new RemindListManager($auth->getUserId($_SESSION['user'], $_SESSION['pw']));
            $remindlist=$tdManager->getRemindListOwned($this->remindListId);
            $reminders=$remindlist->getReminders(($pageNo-1)*$itemsPerPage,$itemsPerPage);
            
            $subscriber= new SubscribeHandler($auth->getUserId($_SESSION['user'], $_SESSION['pw']));
            
            $key=$subscriber->getKeyFromId($this->remindListId);
            if ($key===false){
                $pubStatus="unpublished";
                $publishButton="publish";
                $pbuttonUrl="publish.php?id=".$this->remindListId."&act=publish";
                $token="-";
            } else{
                $token=$key;
                $pubStatus="published";
                $publishButton="unpublish";
                $pbuttonUrl="publish.php?id=".$this->remindListId."&act=unpublish";
            }
            
            //menu
            $html=  '<div id=leftPanel style="float:left">
            <ul id="menu" style="width: 150px; ">
            <li ><a href="#" onclick="location.href=\'home.php?act=newReminderForm&id='.$this->remindListId.'\'">Add new Reminder</a></li>
            <li ><a href="#" onclick="location.href=\''.$pbuttonUrl.'\'">'.$publishButton.'</a></li>';
            
            if ($publishButton=="unpublish"){
                $html.='<li ><a href="htmlButton.php?token='.$token.'" target="_blank">HTML Button</a></li>';
            }
            
            $html.='<li ><a href="#" onclick="location.href=\'home.php?act=allRemindLists\'">Back</a></li>
            </ul></div>';
            
            $html.='<div><div align="center" style="border:1px solid black; margin-left:20%">
                <table><tr>
                <td>Reminder list title: <b>'.$remindlist->title.'</b></td>
                <td>&emsp;&emsp;&emsp;Created on : <b>'.$remindlist->dateCreated.'</b></td> 
                <td>&emsp;&emsp;&emsp;Updated on : <b>'.$remindlist->dateUpdated.'</td>
                </tr><tr>
                <td>Publish status : <b>'.$pubStatus.'</b></td> 
                <td>&emsp;&emsp;&emsp;public token : <b>'.$token.'</td>
                </tr>
                
                </table>
            </div></div><br>';

            if ($reminders!==false){
                $html.='<div style="float:left; margin-left:20%; width:50%">page:';
               
                for($i=1; $i<=$noOfPages; $i++){
                    $html.='<a onclick="location.href=\'home.php?act=reminders&id='.$this->remindListId.'&page='.$i.'\'">'.$i.'</a>';
                 }
                   $html.= '<br><br></div>';
                   
                    $html.='
                    <div id="accordion" style="float:left; margin-left:20%; width:30%">';
                    $i=1;
                    foreach ($reminders as $reminder) {
                             $reminder->dateTime=substr($reminder->dateTime, 0, 10);
                            $html.='<h3><table ><tr><td width="70%">'.$reminder->dateTime.'</td>
                            <td><input type="submit" value="Edit" onclick="location.href=\'home.php?act=tdReminderEditForm&eid='.$reminder->id.'&tdid='.$remindlist->id.'\'"/></td>
                            <td><input type="submit" value="Delete" onclick="$( \'#dialog-confirm\' ).dialog({
                            
                            buttons: {
                              \'Delete\': function() {
                                location.href=\'deleteReminder.php?eid='.$reminder->id.'&tdid='.$remindlist->id.'\';
                              },
                              Cancel: function() {
                                $( this ).dialog( \'close\' );
                              }
                            }
                          });"/></td></tr></table></h3>
                            <div >
                            <p>
                            <u><b>'.$reminder->name.'</b></u><br>'.$reminder->description.'
                            </p></div>';
                            $i++;

                    }
                    $html.='</div><div style="display: none;" id="dialog-confirm" title="Delete Remind List Item?">This action will delete this Reminder from the Remind list. Are you sure?
                      </div>';
            } else{
                    $html.="<h3 style='margin-left:20%;'>Remind list is empty</h3>";
            }

            $html.='<div id="datepicker" style="float:right"></div>';
            return $html;
    }
}


class subscriptionPage extends Page{
     public function getHtml($pageNo=1){
            $auth=new UserAuthenticator();
            $sManager= new SubscribeHandler($auth->getUserId($_SESSION['user'], $_SESSION['pw']));
            $remindlists=$sManager->getSubscribedLists();
             $itemsPerPage=4;
             $noOfPages=(count($remindlists)-1)/$itemsPerPage +1;
             $sManager= new SubscribeHandler($auth->getUserId($_SESSION['user'], $_SESSION['pw']));
             $remindlists=$sManager->getListsForPage(($pageNo-1)*$itemsPerPage,$itemsPerPage);
            
            $html=  '
                <div id=leftPanel style="float:left">
            <ul id="menu1" style="width: 150px; ">
            <li ><a href="#" onclick="location.href=\'home.php?act=allSubscriptions\'; ">Show Subscriptions</a></li>
            <li ><a href="#" onclick="location.href=\'home.php?act=newsubscription\'">Subscribe</a></li>
            </ul></div>';

            if ($remindlists!=false){
                $html.='<div style="float:left; margin-left:20%; width:50%">page:';
                
                for($i=1; $i<=$noOfPages; $i++){
                    $html.='<a onclick="location.href=\'home.php?act=remindLists&page='.$i.'\'">'.$i.'</a>';
                 }
                   $html.= '<br><br></div>';
                    $html.='<div id="accordion1" style="float:left; margin-left:20%; width:50%">';
                
                    $i=1;

                    foreach ($remindlists as $tdlist) {

                            $html.='<h3><table ><tr><td width="70%">'.$tdlist->title.'</td>
                            <td ><input type="submit"  value="Show" onclick="location.href=\'home.php?act=allSubReminders&id='.$tdlist->id.'&title='.$tdlist->title.'\';"/></td>
                            <td><input type="submit" value="unsubscribe" onclick="$( \'#dialog-confirm1\' ).dialog({
                            
                            buttons: {
                              \'Unsubscribe\': function() {
                                location.href=\'unsubscribe.php?id='.$tdlist->id.'\';
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
                    $html.='</div>';
                    
                    $html.='<div style="display: none;" id="dialog-confirm1" title="Unsubscribe Remind List?">This action will unsubscribe this Remind list and all the Reminders in this Remind list. Are you sure?
                      </div>';
            } else{
                    $html.="<h3 style='margin-left:20%;'>Remind lists are empty</h3>";
            }
            return $html;
    }
}

class SubcribedRemindersPage extends Page{
            
        protected $remindListId;
        protected $remindListTitle='-';
    
         //input is $_GET variable
         public function __construct($array) {
        if (!isset($array['id'])){
            header( 'Location: 404.html' ) ;
        }
        
        $this->remindListId=$array['id'];
        $this->remindListTitle=$array['title'];
    }

        public function getHtml($pageNo=1) {
        
            
            
            $auth=new UserAuthenticator();
            $sManager= new SubscribeHandler($auth->getUserId($_SESSION['user'], $_SESSION['pw']));
            $reminders=$sManager->getSubscribedReminders($this->remindListId);
            $itemsPerPage=4;
            $noOfPages=(count($reminders)-1)/$itemsPerPage +1;
            
            $sManager= new SubscribeHandler($auth->getUserId($_SESSION['user'], $_SESSION['pw']));
            $reminders=$sManager->getSubReminderForPage($this->remindListId,($pageNo-1)*$itemsPerPage,$itemsPerPage);
  
            
            
            $key=$sManager->getKeyFromId($this->remindListId);
            if ($key===false){
                $pubStatus="unpublished";
                $publishButton="publish";
                $pbuttonUrl="publish.php?id=".$this->remindListId."&act=publish";
                $token="-";
            } else{
                $token=$key;
                $pubStatus="published";
                $publishButton="unpublish";
                $pbuttonUrl="publish.php?id=".$this->remindListId."&act=unpublish";
            }
            
            //menu
            $html=  '<div id=leftPanel style="float:left">
            <ul id="menu1" style="width: 150px; ">
            <li ><a href="#" onclick="location.href=\'home.php?act=allRemindLists\'">Back</a></li>
            </ul></div>';
            
            $html.='<div><div align="center" style="border:1px solid black; margin-left:20%">
                <table><tr>
                <td>Reminder list title: <b>'.$this->remindListTitle.'</b></td>
                </tr><tr>
                <td>public token : <b>'.$token.'</td>
                </tr>
                
                </table>
            </div></div><br>';
            if ($reminders!==false){
                $html.='<div style="float:left; margin-left:20%; width:50%">page:';
                
                for($i=1; $i<=$noOfPages; $i++){
                    $html.='<a onclick="location.href=\'home.php?act=allSubReminders&id='.$this->remindListId.'&title='.$this->remindListTitle.'&page='.$i.'\'">'.$i.'</a>';
                 }
                   $html.= '<br><br></div>';
                   
                    $html.='
                    <div id="accordion1" style="float:left; margin-left:20%; width:30%">';
                    $i=1;
                    foreach ($reminders as $reminder) {
                            $reminder->dateTime=substr($reminder->dateTime, 0, 10);
                            $html.='<h3><table ><tr><td width="70%">'.$reminder->dateTime.'</td>
                            </tr></table></h3>
                            <div >
                            <p>
                            <u><b>'.$reminder->name.'</b></u><br>'.$reminder->description.'
                            </p></div>';
                            $i++;

                    }
                    $html.='</div><div style="display: none;" id="dialog-confirm" title="Delete Remind List Item?">This action will delete this Reminder from the Remind list. Are you sure?
                      </div>';
            } else{
                    $html.="<h3 style='margin-left:20%;'>Remind list is empty</h3>";
            }

            $html.='<div id="datepicker" style="float:right"></div>';
            return $html;
    }
}
?>



    
