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

if (isset($_GET['id']) && isset($_GET['act'])) {
    if ($_GET['act'] == "publish") {
        $auth      = new UserAuthenticator();
        $userId    = $auth->getUserId($_SESSION['user'], $_SESSION['pw']);
        $tdManager = new RemindListManager($userId);
        $tdManager->publishRemindList($_GET['id']);
    } else if ($_GET['act'] == "unpublish") {
        $auth      = new UserAuthenticator();
        $userId    = $auth->getUserId($_SESSION['user'], $_SESSION['pw']);
        $tdManager = new RemindListManager($userId);
        $tdManager->unpublishRemindList($_GET['id']);
    }
}

header('Location: home.php?act=allRemindlistReminders&id=' . $_GET['id']);
?>
