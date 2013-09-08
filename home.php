
<?php
include_once "lib/auth.php";
include_once "authenticate.php";
?>

<!doctype html>
<html lang="us">
<head>
	<meta charset="utf-8">
	<title>EventShare</title>
	<link href="css/sunny/jquery-ui-1.10.3.custom.css" rel="stylesheet"/>
	<script src="js/jquery-1.9.1.js"></script>
	<script src="js/jquery-ui-1.10.3.custom.js"></script>
        <script>
          $(function() {
            $( "#tabs" ).tabs();
            
            $( "input[type=submit], a, button" )
              .button()
              .click(function( event ) {
                event.preventDefault();
            });
            
            $( "#menu" ).menu();
            $( "#datepicker" ).datepicker();
          });
          </script>
          
          <script>
        function loadXMLDoc(action)
        {
        var xmlhttp;
        if (window.XMLHttpRequest)
          {// code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp=new XMLHttpRequest();
          }
        else
          {// code for IE6, IE5
          xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
          }
        xmlhttp.onreadystatechange=function()
          {
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
            document.getElementById("rightpanel").innerHTML=xmlhttp.responseText;
            
             $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
            }
          }
        xmlhttp.open("GET",action,true);
        xmlhttp.send();
        }
        </script>
	
</head>
<body>
<div id="tabs">
  <ul>
    <li><a href="#events">My Events</a></li>
    <li><a href="#subcriptions">Subcriptions</a></li>
    <li><a href="#profile">Profile</a></li>
    <div style="float:right">
        <?php echo "user: ".$_SESSION['user']." "; ?><input type="submit" value="logout" onclick="location.href='logout.php';">
    </div>
  </ul>
  <div id="events" style="height:500px">
    <div id="events_menu" style="float:left" >
         <ul id="menu" style="width: 150px; ">
          <li>
            <a href="#">Todo List</a>
            <ul>
               <li><a href="#" onclick="loadXMLDoc('todoLists.php?action=createTodoListForm')">Create New Todo List</a></li>
               <li><a href="#">List All Todo Lists</a></li>
            </ul>
          </li>
          
          <li class="ui-state-disabled"><a href="#">Weekly Time Tables</a></li>
          <li class="ui-state-disabled"><a href="#">Monthly Time Tables</a></li>
          <li class="ui-state-disabled"><a href="#">Repeated Event List</a></li>
          <li class="ui-state-disabled"><a href="#">Single Events</a></li>
        </ul>
    </div>
    <div id="rightpanel" style="margin:5%;float:left">Wel-come to event page</div>
  </div>
  <div id="subcriptions">
    <?php include "subcriptions.php"; ?>
  </div>
  <div id="profile">
    <?php include "profile.php"; ?>
  </div>
</div>

</body>
</html>