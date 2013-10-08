
<?php
/***
* developer: sampath liyanage
* phone no: +94778514847
*/

/***
* home page
*/


include_once "authenticate.php";
include_once "uiTheme.php";
include_once 'myRemindersPage.php';
include_once 'subscriptionsPage.php';
include_once 'profilePage.php';

echo '<script src="js/form.js"></script>
				    <script src="js/pureAjax.js"></script>
				    <script>function showUrlInDialog(url,title){
						  var tag = $("<div id=\'dialog\' title=\'"+ title +"\' style=\'width:100%\'></div>");
						  $.ajax({
						    url: url,
						    success: function(data) {
						      tag.html(data).dialog({modal: true,width: 400,height:400}).dialog(\'open\');
						      $( "#datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
						    }
						  });
						}</script>
                                     
					<script>
          $(function() {
            
            $( "#accordion" ).accordion();
             $( "#accordion1" ).accordion();
            $( "#menu" ).menu();
            $( "#menu1" ).menu();
            $( "#tabs" ).tabs();
            $( "input[type=submit], a, button" )
              .button()
              .click(function( Reminder ) {
                Reminder.prReminderDefault();
            });
            
            
            
            $( "#datepicker" ).datepicker();
          });
          </script>';
echo getSubscriptionsPageHtml();
 

?>


