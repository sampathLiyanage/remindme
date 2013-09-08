<?php

include_once "lib/auth.php";

//check authentication
include_once "authenticate.php";
?>
<script>
function loadXMLDoc()
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
    document.getElementById("events").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","schedules.php",true);
xmlhttp.send();
}
</script>

<ul id="menu" style="width: 150px; ">
  <li>
    <a href="#">To Do List</a>
    <ul>
       <li><a href="#" onclick="loadXMLDoc()">Create New Todo List</a></li>
       <li><a href="#">List All Todo Lists</a></li>
    </ul>
  </li>
  
  <li class="ui-state-disabled"><a href="#">Weekly Time Tables</a></li>
  <li class="ui-state-disabled"><a href="#">Monthly Time Tables</a></li>
  <li class="ui-state-disabled"><a href="#">Repeated Event List</a></li>
  <li class="ui-state-disabled"><a href="#">Single Events</a></li>
</ul>


