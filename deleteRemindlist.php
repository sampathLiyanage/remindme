<?php
/***
 * developer: sampath liyanage
 * phone no: +94778514847
 */

/***
 * this file can be used as action of the form that deletes a Remind list
 * a get request should be sent to this
 * get parameters 'id' (Remind list id) should be set before
 */

include_once "lib/auth.php";
include_once 'lib/reminders.php';
include_once "authenticate.php";

//if reminder-list id is set
if (isset($_GET['id'])) {
    $auth      = new UserAuthenticator();
    $userId    = $auth->getUserId($_SESSION['user'], $_SESSION['pw']);
    $tdManager = new RemindListManager($userId);
    //if no remind-list returned
    if ($tdManager !== false) {
        $Remindlist = $tdManager->deleteRemindList($_GET['id']);
    }
}

//redirect to reminder-list page
header('Location: home.php?act=allRemindLists');
?>
