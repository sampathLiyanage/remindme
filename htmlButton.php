<?php

if (isset($_GET['token'])){
    echo htmlspecialchars('<div id="remindme">');
    echo '<br>';
    echo htmlspecialchars('<a href="http://localhost/remindme/subscriptions.php?action=subscribe&token='. $_GET['token'].'" onclick="window.open(this.href,\'popUpWindow\',\'height=200,width=300,,scrollbars=yes,menubar=no\'); return false;">');
    echo '<br>';
    echo htmlspecialchars('<img src="http://localhost/remindme/images/remindMe.png" style="width: 50px; height: 50px"></img>');
    echo '<br>';
    echo htmlspecialchars('</a></div>');
}
?>
