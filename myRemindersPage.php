<?php
/***
 * developer: sampath liyanage
 * phone no: +94778514847
 */

include_once "authenticate.php";
include_once 'lib/reminders.php';
include_once "uiPages.php";

/*
 * html code for showing Reminders created by a user
 *@output=> html code:string
 */
function getMyRemindersPageHtml()
{
    $html = '';
    //if user needs to create a new Remind list
    if (isset($_GET['act']) && $_GET['act'] == "newRemindListForm") {
        $html = "<script>showUrlInDialog('reminders.php?action=newRemindListForm', 'new Remind list')</script>";
    } else if (isset($_GET['act']) && $_GET['act'] == "TdListEditForm") {
        $html = "<script>showUrlInDialog('reminders.php?action=TdListEditForm&id=" . $_GET['id'] . "','edit to do list')</script>";
    } else if (isset($_GET['act']) && isset($_GET['eid']) && isset($_GET['tdid']) && $_GET['act'] == "tdReminderEditForm") {
        $html = "<script>showUrlInDialog('reminders.php?action=tdReminderEditForm&eid=" . $_GET['eid'] . "&tdid=" . $_GET['tdid'] . "','edit to do list'); </script>";
    } else if (isset($_GET['act']) && $_GET['act'] == "newReminderForm") {
        $html = "<script>showUrlInDialog('reminders.php?action=newReminderForm&id=" . $_GET['id'] . "','New Reminder')</script>";
    }
    //if user needs to see all the Remind list created by him
    else if (isset($_GET['act']) && isset($_GET['page']) && $_GET['act'] == "remindLists") {
        $RemindlistPage = new RemindListsPage();
        $html           = $RemindlistPage->getHtml($_GET['page']);
    }
    //if user needs to see all the Remind list created by him
    else if (isset($_GET['act']) && $_GET['act'] == "allRemindLists") {
        $RemindlistPage = new RemindListsPage();
        $html           = $RemindlistPage->getHtml();
    }
    //if user needs to see all the Remind list created by him
    else if (isset($_GET['act']) && isset($_GET['page']) && $_GET['act'] == "reminders") {
        $RemindersPage = new RemindersPage($_GET);
        $html          = $RemindersPage->getHtml($_GET['page']);
    }
    //if user needs to see all the Reminders in a Remind list created by him
    else if (isset($_GET['act']) && $_GET['act'] == "allRemindlistReminders") {
        $RemindersPage = new RemindersPage($_GET);
        $html          = $RemindersPage->getHtml();
    }
    //if user needs to see all the Remind list created by him
    else {
        $RemindlistPage = new RemindListsPage();
        $html           = $RemindlistPage->getHtml();
    }
    //return html of the page
    return $html;
}
?>
