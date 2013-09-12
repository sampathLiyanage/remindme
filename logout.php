<?php
/***
* developer: sampath liyanage
* phone no: +94778514847
*/

/***
*logout script
*/

session_start();
unset($_SESSION["user"]); 
unset($_SESSION["pw"]);
header("Location: login.php");
?>
