<?php
/***
 * developer: sampath liyanage
 * phone no: +94778514847
 */

/***
 * this file can be used as action of the form that deletes a Remind list Reminder
 * a get request should be sent to this
 * get parameters 'tdid' (Remind list id) and 'eid' (Reminder id) should be set before
 */

include_once "lib/auth.php";
include_once 'lib/reminders.php';
include_once "authenticate.php";

//if reminder-list id and reminder id is set
if (isset($_GET['tdid']) && isset($_GET['eid'])) {
    $auth      = new UserAuthenticator();
    $userId    = $auth->getUserId($_SESSION['user'], $_SESSION['pw']);
    $tdManager = new RemindListManager($userId);
    //if no remind-list returned
    if ($tdManager !== false) {
        $Remindlist = $tdManager->getRemindListOwned($_GET['tdid']);
        //if reminder-list id is not invalid
        if ($Remindlist !== false) {
            $Remindlist->removeReminder($_GET['eid']);
        }
    }
}

//redirect to reminders page
header('Location: home.php?act=allRemindlistReminders&id=' . $_GET['tdid']);
?>
