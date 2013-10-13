<?php
/***
 * developer: sampath liyanage
 * phone no: +94778514847
 */

include_once "authenticate.php";

/*
 * html code for showing events subcribed by a user
 * @output=> html code:string
 */
function getSubscriptionsPageHtml()
{
    //the reminders in a subscribed remind-list for one page
    if (isset($_GET['act']) && $_GET['act'] == "allSubReminders" && isset($_GET['id']) && isset($_GET['page'])) {
        $RemindersPage = new SubcribedRemindersPage($_GET);
        $html          = $RemindersPage->getHtml($_GET['page']);
        return $html;
    }
    //all the reminders in a subscribed remind-list
    else if (isset($_GET['act']) && $_GET['act'] == "allSubReminders" && isset($_GET['id'])) {
        $RemindersPage = new SubcribedRemindersPage($_GET);
        $html          = $RemindersPage->getHtml();
        return $html;
    }
    //all the remind list subscribed
    else if (isset($_GET['act']) && $_GET['act'] == "allSubscriptions") {
        $subPage = new subscriptionPage();
        return $subPage->getHtml();
    }
    //new subscription form
    else if (isset($_GET['act']) && $_GET['act'] == "newsubscription") {
        $html = "<script>showUrlInDialog('subscriptions.php?action=newsubscription' ,'Subscribe')</script>";
        return $html;
    }
    //default
    else {
        $subPage = new subscriptionPage();
        return $subPage->getHtml();
    }
}
?>