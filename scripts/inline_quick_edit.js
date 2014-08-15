function TSQuickEditPost(GetIDValue,GetRedirectURL)
{originalhtml=TSGetID(GetIDValue).innerHTML;cID=GetIDValue.replace(/post_message_/,"");originalID=GetIDValue;AdvancedEdit=GetRedirectURL;TSGetID(originalID).innerHTML='<img src="./tsf_forums/images/spinner.gif" border="0" class="inlineimg"> <strong>'+l_pleasewait+'</strong>';GETRequest('action=quick_edit&cid='+cID,'ts_ajax.php');}
function TSQuickEditPostCancel()
{TSGetID(originalID).innerHTML=originalhtml;}
function createXMLHttpRequest()
{xmlHttp=false;try
{xmlHttp=new XMLHttpRequest();}
catch(e)
{var _ieModelos=new Array('MSXML2.XMLHTTP.5.0','MSXML2.XMLHTTP.4.0','MSXML2.XMLHTTP.3.0','MSXML2.XMLHTTP','Microsoft.XMLHTTP');for(var i=0;i<_ieModelos.length&&!success;i++)
{try
{xmlHttp=new ActiveXObject(_ieModelos[i]);}
catch(e)
{}}}
if(!xmlHttp)
{alert(l_ajaxerror2);return false;}}
function GETRequest(query,filename)
{createXMLHttpRequest();var queryString=filename+"?"+query;xmlHttp.onreadystatechange=handleStateChange;xmlHttp.open("GET",queryString,true);xmlHttp.send(null);}
function POSTRequest(query,url)
{createXMLHttpRequest();xmlHttp.open("POST",url,true);xmlHttp.onreadystatechange=handleStateChangeP;xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");xmlHttp.send(query);}
function handleStateChange()
{if(xmlHttp.readyState==4)
{if(xmlHttp.status==200)
{parseResults();}}}
function handleStateChangeP()
{if(xmlHttp.readyState==4)
{if(xmlHttp.status==200)
{parseResults2();}}}
function parseResults2()
{result=xmlHttp.responseText;if(result.match(/<error>(.*)<\/error>/))
{message=result.match(/<error>(.*)<\/error>/);if(!message[1])
{message[1]=l_ajaxerror;}
alert(l_updateerror+message[1]);document.quick_edit_form.submit.value=l_quick_save_button;document.quick_edit_form.submit.disabled=false;}
else
{TSGetID(originalID).innerHTML=result;}}
function parseResults()
{result=xmlHttp.responseText;if(result.match(/<error>(.*)<\/error>/))
{message=result.match(/<error>(.*)<\/error>/);if(!message[1])
{message[1]=l_ajaxerror;}
alert(l_updateerror+message[1]);TSQuickEditPostCancel();}
else
{TSQuickForm=bbcodes+'<br /><form name="quick_edit_form" onsubmit="return false;"><textarea style="width: 100%; height: 150px;" name="newContent">'+result+'</textarea><br /><input type="submit" name="submit" value="'+l_quick_save_button+'" onclick="TSQuickSavePost()"> <input type="button" value="'+l_quick_adv_button+'" onclick="jumpto(\''+AdvancedEdit+'\')"> <input type="button" value="'+l_quick_cancel_button+'" onclick="TSQuickEditPostCancel()"></form>';oldDiv=TSGetID(originalID);newDiv=document.createElement(oldDiv.tagName);newDiv.id=oldDiv.id;newDiv.className=oldDiv.className;newDiv.innerHTML=TSQuickForm;oldDiv.parentNode.replaceChild(newDiv,oldDiv);}}
function TSQuickSavePost()
{document.quick_edit_form.submit.disabled=true;document.quick_edit_form.submit.value=l_pleasewait;var cbobject=document.quick_edit_form.newContent.value;POSTRequest('action=save_quick_edit&text='+encodeURIComponent(cbobject)+'&cid='+cID,'ts_ajax.php');}