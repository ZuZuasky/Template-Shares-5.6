function intval( mixed_var, base )
{
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: stensi
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // *     example 1: intval('Kevin van Zonneveld');
    // *     returns 1: 0
    // *     example 2: intval(4.2);
    // *     returns 2: 4
    // *     example 3: intval(42, 8);
    // *     returns 3: 42
    // *     example 4: intval('09');
    // *     returns 4: 9
 
    var tmp;
 
    if( typeof( mixed_var ) == 'string' ){
        tmp = parseInt(mixed_var*1);
        if(isNaN(tmp) || !isFinite(tmp)){
            return 0;
        } else{
            return tmp.toString(base || 10);
        }
    } else if( typeof( mixed_var ) == 'number' && isFinite(mixed_var) ){
        return Math.floor(mixed_var);
    } else{
        return 0;
    }
}

function urlencode( str )
{
    // http://kevin.vanzonneveld.net
    // +   original by: Philip Peterson
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // *     example 1: urlencode('Kevin van Zonneveld!');
    // *     returns 1: 'Kevin+van+Zonneveld%21'
                                     
    var ret = str;
    
    ret = ret.toString();
    ret = encodeURIComponent(ret);
    ret = ret.replace(/%20/g, '+');
 
    return ret;
}

function TSajaxquickreply(ThreadID,PostCount,PageNumber)
{
	var message = $('message').value;
	var closethread='0';
	var stickthread='0';
	if($('quickreply').closethread.checked==true)
	{
		var closethread='1';
	}
	if($('quickreply').stickthread.checked==true)
	{
		var stickthread='1';
	}
	var pars = 'ajax_quick_reply=1&closethread='+closethread+'&stickthread='+stickthread+'&tid='+intval(ThreadID)+'&postcount='+intval(PostCount)+'&message='+urlencode(message)+'&page='+intval(PageNumber);
	new Ajax.Request(baseurl+"/ts_ajax.php",
	{
		parameters: pars,
		method: "POST",
		contentType: "application/x-www-form-urlencoded",
		encoding: charset,
		onLoading: function()
		{
			Element.show('loading-layerS');
			$('quickreply').quickreplybutton.value=l_pleasewait;
			$('quickreply').quickreplybutton.disabled=true;
		},
		onSuccess: function(response)
		{
			var result = response.responseText;
			if(result.match(/<error>(.*)<\/error>/))
			{
				message=result.match(/<error>(.*)<\/error>/);
				if(!message[1])
				{
					message[1]=l_ajaxerror;
				}
				alert(l_updateerror+message[1]);
			}
			else
			{
				var NewDiv = document.createElement('div');
				NewDiv.setAttribute('id','PostedReply');
				NewDiv.innerHTML = result;
				$('ajax_quick_reply').appendChild(NewDiv);
				$('message').value='';
			}
			Element.hide('loading-layerS');
			$('quickreply').quickreplybutton.value=l_newreply;
			$('quickreply').quickreplybutton.disabled=false;
		},
		onException: function(req,exception)
		{
			alert(l_ajaxerror+"\n\n"+exception);
			Element.hide('loading-layerS');
			$('quickreply').quickreplybutton.value=l_newreply;
			$('quickreply').quickreplybutton.disabled=false;
		},
		onFailure: function ()
		{
			alert(l_ajaxerror);
			Element.hide('loading-layerS');
			$('quickreply').quickreplybutton.value=l_newreply;
			$('quickreply').quickreplybutton.disabled=false;
		}
	});
}