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
function getSubscriptionsPageHtml(){
        
    if(isset($_GET['act']) && $_GET['act']=="allSubReminders" && isset($_GET['id']) && isset($_GET['page'])){
            $RemindersPage=new SubcribedRemindersPage($_GET);
            $html=$RemindersPage->getHtml($_GET['page']);
            return $html;
            
        } else if(isset($_GET['act']) && $_GET['act']=="allSubReminders" && isset($_GET['id'])){
            $RemindersPage=new SubcribedRemindersPage($_GET);
            $html=$RemindersPage->getHtml();
            return $html;
            
        } else if (isset($_GET['act']) && $_GET['act']=="allSubscriptions" ){
            $subPage=new subscriptionPage();
            return $subPage->getHtml();
            
        } else if(isset($_GET['act']) && $_GET['act']=="newsubscription"){
            $html="<script>showUrlInDialog('subscriptions.php?action=newsubscription' ,'Subscribe')</script>";
            return $html;
            
        } else{
            $subPage=new subscriptionPage();
            return $subPage->getHtml();
        }
}

?>
