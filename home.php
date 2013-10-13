<?php
/***
* developer: sampath liyanage
* phone no: +94778514847
*/

/***
* home page
*/

include_once "authenticate.php";
include_once "uiTheme.php";
include_once 'myRemindersPage.php';
include_once 'subscriptionsPage.php';
include_once 'profilePage.php';

$page=new JQTheme($_GET);
echo $page->getHtml();
?>
