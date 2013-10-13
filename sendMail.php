<?php
/***
* developer: sampath liyanage
* phone no: +94778514847
*/

/**
 * to be called by a corn job from the server to send messages
 */
include "lib/notifier.php";

$notifier=new Notifier();
$notifier->sendMail();
?>
