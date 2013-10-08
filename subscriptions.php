<?php
/***
* developer: sampath liyanage
* phone no: +94778514847
*/

/***
* this handles request from clients related to Remind lists
* when a user creates a Remind list as a sequence of form filling
*/

include_once "uiForms.php";
//check authentication
include_once "authenticate.php";


//if action is not set
if (!(isset($_GET['action']))){
        header( 'Location: 404.html' );
}

$action=$_GET['action'];

//if user wants to create a new Remind list
if ($action=='newsubscription'){
       $sForm=new SubscriptionForm();
       echo $sForm->getHtml();
} else if ($action=="subscribe"){
       $sForm=new SubscriptionForm();
       $sForm->submit($_GET);
       echo $sForm->getHtml();
}




?>




