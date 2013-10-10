<?php
/***
* developer: sampath liyanage
* phone no: +94778514847
*/

/**
*represent the basic structure of a theme
*/

abstract class Theme{
	protected $html;
	
	function __construct(){
		$this->html=$this->getHeader();
	}
	
	/*
	* get html at the top of the page
	* this html code should be used in many pages
	* @output=>html:string
	*/
	abstract protected function getHeader();
	
	/*
	* get html at the bottom of the page
	* this html code should be used in many pages
	* @output=>html:string
	*/
	abstract protected function getFooter();
	
	/*
	*add html to the middle of the page
	*input:$html:string
	*/
	public function addHtml($html){
		$this->html.=$html;
	}
	
	/*
	*get html of the page
	*@output:string
	*/
	public function getHtml(){
		$this->html.=$this->getFooter();
		return $this->html;
	}
}


/**
 * theme created with JQuery
 */
class JQTheme extends Theme{
	private $urlPara='?';
        private $tab=0;
	function __construct($array){
                foreach ($array as $key=>$element){
                    
                    $element=htmlspecialchars($element, ENT_QUOTES, 'UTF-8');
                    $this->urlPara.=$key.'='.$element.'&';
                }
                 if (isset($_COOKIE["tab"])){
                    $this->tab=$_COOKIE["tab"];
                }
		parent::__construct();
               
	}
	
	
	public function addRemindersTabHtml($html){
		$this->html.='<div id="Reminders" style="height:600px">'.$html.'</div>';
	}
	
	public function addSubscriptionsTabHtml($html){
		$this->html.='<div id="subcriptions" style="height:600px">'.$html.'</div>';
	}
	
	public function addProfileTabHtml($html){
		$this->html.='<div id="profile" style="height:600px">'.$html.'</div>';
	}
	
	protected function getHeader(){
		return '<!doctype html>
				<html lang="us">
				<head>
					<meta charset="utf-8">
					<title>RemindMe</title>
					<link href="css/sunny/jquery-ui-1.10.3.custom.css" rel="stylesheet"/>
					<script src="js/jquery-1.9.1.js"></script>
					<script src="js/jquery-ui-1.10.3.custom.js"></script>
                                        <script src="js/cookie.js"></script>
				    <script src="js/form.js"></script>
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
           $( "#tabs" ).tabs( "option", "active", '.$this->tab.' );
               
          });
          </script>
		</head>
		<body>
		<div id="tabs" style="height:700px">
		  <ul>
                  
		    <li><a id="tab1" href="rem.php'.$this->urlPara.'" onclick="$.cookie(\'tab\', 0);">Reminders</a></li>
		    <li><a id="tab2" href="sub.php'.$this->urlPara.'" onclick="$.cookie(\'tab\', 1);">Subcriptions</a></li>
		    <!--<li><a id="tab3" href="#profile'.$this->urlPara.'" onclick="$.cookie(\'tab\', 2);">Profile</a></li>-->
		    <div style="float:right">
		        user: '.$_SESSION["user"].' '.'<input type="submit" value="logout" onclick="location.href=\'logout.php\';">
		    </div>
		  </ul>';
	}
	
	
	protected function getFooter(){
		return ' 
			</div>
                        
			</body>
                        
			</html>';
	}
	
}



?>
