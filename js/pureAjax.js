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