if(document.getElementByID)
{stdBrowser=true;}
else
{stdBrowser=false;}
function ajaxFunction()
{http_request=false;if(window.XMLHttpRequest)
{http_request=new XMLHttpRequest();if(http_request.overrideMimeType)
{http_request.overrideMimeType('text/html');}}
else if(window.ActiveXObject)
{try
{http_request=new ActiveXObject("Msxml2.XMLHTTP");}
catch(e)
{try
{http_request=new ActiveXObject("Microsoft.XMLHTTP");}
catch(e){}}}
if(!http_request)
{alert("Your browser broke!");return false;}
return http_request;};function ChangeLayer(showhide)
{if(stdBrowser||navigator.appName!="Microsoft Internet Explorer")
{document.getElementById('loading-layer').style.display=showhide;}
else
{document.all['loading-layer'].style.display=showhide;}};function showData()
{ChangeLayer('block');htmlRequest=ajaxFunction();if(htmlRequest==null)
{alert("Browser does not support HTTP Request");return;}
htmlRequest.onreadystatechange=function()
{if(htmlRequest.readyState==4)
{if(htmlRequest.status==200)
{document.getElementById("shoutbox_frame").innerHTML=htmlRequest.responseText;ChangeLayer('none');}}}
if(popupshoutbox!="yes")
{htmlRequest.open("GET",baseurl+"/shoutbox/outputinfo.php",true);}
else
{htmlRequest.open("GET",baseurl+"/shoutbox/outputinfo.php?popupshoutbox=yes",true);}
htmlRequest.send(null);};function saveData()
{htmlRequest=ajaxFunction();if(htmlRequest==null)
{alert("Browser does not support HTTP Request");return;}
if(document.shoutbox.shouter_comment.value==""||document.shoutbox.shouter_comment.value=="NULL")
{alert('You need to fill in message!');return;}
htmlRequest.onreadystatechange=function()
{if(htmlRequest.readyState==4)
{if(htmlRequest.status==200)
{if(htmlRequest.responseText!='')
{document.getElementById("errorarea").innerHTML=htmlRequest.responseText;return;}
document.getElementById("errorarea").innerHTML='';document.shoutbox.shouter_comment.value='';document.shoutbox.shouter_comment.focus();showData();}}}
htmlRequest.open('POST',baseurl+'/shoutbox/sendshout.php');htmlRequest.setRequestHeader('Content-Type','application/x-www-form-urlencoded');htmlRequest.setRequestHeader("Connection","close");var message='message='+escape(encodeURI(document.shoutbox.shouter_comment.value));htmlRequest.send(message);};function SmileIT(smile,form,text)
{document.forms[form].elements[text].value=document.forms[form].elements[text].value+" "+smile+" ";document.forms[form].elements[text].focus();};function popup(url)
{window.open(baseurl+'/shoutbox/'+url,'shoutbox','toolbar=no, scrollbars=no, resizable=no, width=560, height=150, top=50, left=50');return false;};showData();setInterval("showData()",30000);