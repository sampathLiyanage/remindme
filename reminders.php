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
if (!(isset($_GET['action']))) {
    header('Location: 404.html');
}

$action = $_GET['action'];
//if user wants to create a new Remind list
if ($action == 'newRemindListForm') {
    $RemindlistForm = new RemindListForm();
    echo $RemindlistForm->getHtml();
}
//if user wants to get remindlist edit form
else if ($action == 'TdListEditForm') {
    $RemindlistForm = new RemindListEditForm($_GET['id']);
    echo $RemindlistForm->getHtml();
}
//if user submits an edited remind-list
else if ($action == 'editRemindList') {
    $RemindlistForm = new RemindListEditForm($_GET['RemindListId']);
    $RemindlistForm->submit($_GET);
    echo $RemindlistForm->getHtml();
}
//if user needs to get reminders edit form
else if ($action == 'tdReminderEditForm') {
    $ReminderForm = new ReminderEditForm($_GET['tdid'], $_GET['eid']);
    echo $ReminderForm->getHtml();
}
//if user submits reminder edit form
else if ($action == 'editReminder') {
    $ReminderForm = new ReminderEditForm($_GET['RemindListId'], $_GET['ReminderId']);
    $ReminderForm->submit($_GET);
    echo $ReminderForm->getHtml();
}
//if user needs to get new reminder form
else if ($action == 'newReminderForm') {
    $ReminderForm = new ReminderForm();
    $ReminderForm->setTdListId($_GET['id']);
    echo $ReminderForm->getHtml();
}
//if user wants to save a Remindlist
else if ($action == 'saveRemindList') {
    $RemindlistForm = new RemindListForm();
    if ($RemindlistForm->submit($_GET)) {
        echo "<h4><font color='#008000'>Remind list saved successfully. add Reminders now</font></h4>";
        $ReminderForm = new ReminderForm();
        $ReminderForm->setTdListIdtoLatest();
        echo $ReminderForm->getHtml();
    } else {
        echo "<h4><font color='#FF0000'>Input Error</font></h4>";
        echo $RemindlistForm->getHtml();
    }
}
//if user wants to add Reminder to latest Remind list created
else if ($action == 'addReminderToLatest') {
    $ReminderForm = new ReminderForm();
    if ($ReminderForm->submit($_GET)) {
        echo "<h4><font color='#008000'>Reminder saved successfully. add another Reminder or close</font></h4>";
        $ReminderForm->resetAllFields();
    }
    echo $ReminderForm->getHtml();
}
?>