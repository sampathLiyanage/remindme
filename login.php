<!doctype html>
<html lang="us">
<head>
	<meta charset="utf-8">
	<title>jQuery UI Example Page</title>
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
<div id="centerdiv" >
<div  style="float:left" id="login_div">
<form action="login.php" autocomplete="on">
  <table>
  <tr><td>E-mail: </td><td><input type="email" name="email"></td></tr>
  <tr><td>Password: </td><td><input type="password" name="passwd"></td></tr>
  <tr><td></td><td><input type="submit" value="login"></td><tr>
  </table>
</form> </div>

<div style="float:right" class="center" id="signup_div">
<form action="signup.php" autocomplete="on">
  <table>
  <tr><td>User Name: </td><td><input type="text" name="username"></td></tr>
  <tr><td>E-mail: </td><td><input type="email" name="email"></td></tr>
  <tr><td>Password: </td><td><input type="password" name="passwd"></td></tr>
  <tr><td></td><td><input type="submit" value="Sign-Up"></td></tr>
  </table>
</form> 
</div></div>
</body>
</html>
