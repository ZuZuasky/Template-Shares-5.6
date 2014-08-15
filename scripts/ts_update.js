var http_request=false;
function UpdateExternalTorrent(url,parameters,tid)
{
	torrentid=tid;
	oldDiv3=TSGetID('isexternal_'+torrentid);
	newDiv3=document.createElement(oldDiv3.tagName);
	newDiv3.id=oldDiv3.id;
	newDiv3.className=oldDiv3.className;
	newDiv3.innerHTML='<img src="'+dimagedir+'/ajax-loader.gif" class="inlineimg" border="0" alt="'+l_pleasewait+'" title="'+l_pleasewait+'">&nbsp;'+l_pleasewait;
	oldDiv3.parentNode.replaceChild(newDiv3,oldDiv3);
	http_request=false;
	if(window.XMLHttpRequest)
	{
		http_request=new XMLHttpRequest();
		if(http_request.overrideMimeType)
		{
			http_request.overrideMimeType('text/html');
		}
	}
	else if(window.ActiveXObject)
	{
		try
		{
			http_request=new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch(e)
		{
			try
			{
				http_request=new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e)
			{
			}
		}
	}
	if(!http_request)
	{
		show_error_message(l_ajaxerror2);
		return false;
	}
	http_request.onreadystatechange=tsUpdate;
	http_request.open('POST',url,true);
	http_request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http_request.setRequestHeader("Content-length",parameters.length);
	http_request.setRequestHeader("Connection","close");
	http_request.send(parameters);
}

function tsUpdate()
{
	if(http_request.readyState==4)
	{
		if(http_request.status==200)
		{
			var result=http_request.responseText;
			changeText(result);
		}
		else
		{
			show_error_message(l_ajaxerror);
		}
	}
}

function changeText(ajaxResult)
{
	if(ajaxResult.match(/<error>(.*)<\/error>/))
	{
		message=ajaxResult.match(/<error>(.*)<\/error>/);
		if(!message[1])
		{
			message[1]=l_ajaxerror;
		}
		show_error_message(l_updateerror+message[1]);
	}
	else
	{
		update=ajaxResult.split('|');
		oldDiv1=TSGetID('seeders_'+update[2]);
		newDiv1=document.createElement(oldDiv1.tagName);
		newDiv1.id=oldDiv1.id;
		newDiv1.className=oldDiv1.className;
		newDiv1.innerHTML=update[0];
		oldDiv1.parentNode.replaceChild(newDiv1,oldDiv1);
		oldDiv2=TSGetID('leechers_'+update[2]);
		newDiv2=document.createElement(oldDiv2.tagName);
		newDiv2.id=oldDiv2.id;
		newDiv2.className=oldDiv2.className;
		newDiv2.innerHTML=update[1];
		oldDiv2.parentNode.replaceChild(newDiv2,oldDiv2);
		oldDiv3=TSGetID('isexternal_'+update[2]);
		newDiv3=document.createElement(oldDiv3.tagName);
		newDiv3.id=oldDiv3.id;
		newDiv3.className=oldDiv3.className;
		newDiv3.innerHTML='<img src="'+dimagedir+'input_true.gif">';
		oldDiv3.parentNode.replaceChild(newDiv3,oldDiv3);
	}
}

function show_error_message(message)
{
	oldDiv4=TSGetID('isexternal_'+torrentid);
	newDiv4=document.createElement(oldDiv4.tagName);
	newDiv4.id=oldDiv4.id;newDiv4.className=oldDiv4.className;
	newDiv4.innerHTML='';
	oldDiv4.parentNode.replaceChild(newDiv4,oldDiv4);
	alert(message);
}