<?php

/**
*this script should be included in all the php scripts
*this checks if the user is loged in
*user "include_once"
*/


//check authentication

session_start();
if(isset($_SESSION['user']) && isset($_SESSION['pw'])){
        $auth=new UserAuthenticator();
        if(!($auth->authWithPwHash($_SESSION['user'], $_SESSION['pw']))){
                header( 'Location: login.php' ) ;
        } 
} else{
        header( 'Location: login.php' ) ;
}
?>
