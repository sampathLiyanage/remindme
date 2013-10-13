<?php
/***
 * developer: sampath liyanage
 * phone no: +94778514847
 */

/***
 * this handles requests from clients related to Subscriptions
 */

include_once "lib/auth.php";

//check authentication
/***
 * dont use normal authentication as subscription widget needs this script
 */
session_start();
if (isset($_SESSION['user']) && isset($_SESSION['pw'])) {
    $auth = new UserAuthenticator();
    if (!($auth->authWithPwHash($_SESSION['user'], $_SESSION['pw']))) {
        echo "<a href='http://localhost/remindme' target='_blank'><b>Please login first</b></a>";
        exit;
    }
} else {
    echo "<a href='http://localhost/remindme' target='_blank'><b>Please login first</b></a>";
    exit;
}

include_once "uiForms.php";

//if action is not set
if (!(isset($_GET['action']))) {
    header('Location: 404.html');
}

$action = $_GET['action'];

//if user wants to get a subscription form
if ($action == 'newsubscription') {
    $sForm = new SubscriptionForm();
    echo $sForm->getHtml();
}
//if user wants to submit a subscription form
else if ($action == "subscribe") {
    $sForm = new SubscriptionForm();
    $sForm->submit($_GET);
    echo $sForm->getHtml();
}
?>