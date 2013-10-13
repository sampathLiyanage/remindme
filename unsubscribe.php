<?php
/***
 * developer: sampath liyanage
 * phone no: +94778514847
 */

/***
 * this file can be used as action of the form that unsubscribes a Remind list
 * a get request should be sent to this
 * get parameters 'id' (Remind list id) should be set before
 */

include_once "lib/auth.php";
include_once 'lib/reminders.php';
include_once "authenticate.php";
include_once 'lib/subscription.php';

if (isset($_GET['id'])) {
    $auth       = new UserAuthenticator();
    $userId     = $auth->getUserId($_SESSION['user'], $_SESSION['pw']);
    $sManager   = new SubscribeHandler($userId);
    $Remindlist = $sManager->unsubscribe($_GET['id']);
}
header('Location: home.php?act=allRemindLists');
?>