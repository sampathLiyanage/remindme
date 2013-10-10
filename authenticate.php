<?php
/***
* developer: sampath liyanage
* phone no: +94778514847
*/

/**
*this script should be included in all the php scripts which can be accesed by outside users
*this checks if the user is loged in and redirects if not
*use "include_once"
*/

include_once "lib/auth.php";

//check authentication

session_start();
if(isset($_SESSION['user']) && isset($_SESSION['pw'])){
        $auth=new UserAuthenticator();
        if(!($auth->authWithPwHash($_SESSION['user'], $_SESSION['pw']))){
                header( 'Location: login.php' ) ;
                exit;
        } 
} else{
        header( 'Location: login.php' ) ;
        exit;
}


?>
