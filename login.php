<?php
/***
* developer: sampath liyanage
* phone no: +94778514847
*/

/***
* login page
* all the users who are not authenticated redirected here
*/

include_once "lib/auth.php";

$loginFailedDialog=false;
$submitReport=false;

session_start();

//if username and password are set as sessions
if(isset($_SESSION['user']) && isset($_SESSION['pw'])){
        $auth=new UserAuthenticator();
        //if user is authenticated
        if($auth->authWithPwHash($_SESSION['user'], $_SESSION['pw'])){
                header( 'Location: home.php' ) ;
        } 
        //if user is not authenticated
        else{
        	unset($_SESSION['user']);
        	unset($_SESSION['pw']);
        }
} 


//if user submit login details
else if(isset($_POST['username']) && isset($_POST['passwd']) && isset($_POST['submit']) && $_POST['submit']=="login"){
        $auth=new UserAuthenticator();
        //if user is authenticated
        if($auth->authWithPasswd($_POST['username'], $_POST['passwd'])){
                $_SESSION['user']=$_POST['username'];
                $_SESSION['pw']=md5($_POST['passwd']);
                header( 'Location: home.php' ) ;
        }
        //if user is not authenticated
        else{
                $loginFailedDialog=true;
        }
}

//if user submit signup details
else if(isset($_POST['username']) && isset($_POST['passwd']) && isset($_POST['email']) && isset($_POST['submit']) && $_POST['submit']=="signup"){
        $auth=new UserCreator();
        $report=$auth->createUser($_POST['username'],$_POST['passwd'],$_POST['email']);
        $submitReport=$report->report;
}
        

?>

<!doctype html>
<html lang="us">
<head>
	<meta charset="utf-8">
	<title>EventShare-Login</title>
	<link href="css/sunny/jquery-ui-1.10.3.custom.css" rel="stylesheet"/>
	<script src="js/jquery-1.9.1.js"></script>
	<script src="js/jquery-ui-1.10.3.custom.js"></script>
<style>
#centerdiv
{
margin-left:30%;
margin-top:10%;
width:40%;
background-color:#b0e0e6;
position:absolute;
}
</style>
	
</head>
<body>
<h1 align="center" style="color:#b0e0e6">EventShare</h1>
<div id="centerdiv" >
<div  style="float:left" id="login_div">
<form action="login.php" autocomplete="off" method="post">
  <table>
  <tr><td>User Name: </td><td><input type="text" name="username"></td></tr>
  <tr><td>Password: </td><td><input type="password" name="passwd"></td></tr>
  <tr><td></td><td><input type="submit" name="submit" value="login"></td><tr>
  </table>
</form> </div>

<div style="float:right" class="center" id="signup_div">
<form action="login.php" autocomplete="off" method="post">
  <table>
  <tr><td>User Name: </td><td><input type="text" name="username"></td></tr>
  <tr><td>E-mail: </td><td><input type="email" name="email"></td></tr>
  <tr><td>Password: </td><td><input type="password" name="passwd"></td></tr>
  <tr><td></td><td><input type="submit" name="submit" value="signup"></td></tr>
  </table>
</form> 
</div></div>

<?php
if ($loginFailedDialog){
      echo  "<script>$(function() {
    $( '#dialog' ).dialog();
  });</script>
  <div class='ui-state-highlight' id='dialog' title='Login Failed!!!'>
  <p>Please recheck your username and password</p>
  </div>";
}

else if (!($submitReport===false)){
       echo  "<script>$(function() {
    $( '#dialog' ).dialog();
  });</script>
  <div id='dialog' title='signup notification'>
  <p>".$submitReport."</p>
  </div>"; 
}
?>
</body>
</html>
