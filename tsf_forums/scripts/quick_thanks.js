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

function TSFajaxquickthanks(ThreadID, PostID, RemoveThanks)
{
	var pars = 'action=thanks&tid='+intval(ThreadID)+'&pid='+intval(PostID);
	if (RemoveThanks)
	{
		pars += '&removethanks=true';
	}
	new Ajax.Request(baseurl+"/tsf_forums/tsf_ajax.php",
	{
		parameters: pars,
		method: "POST",
		contentType: "application/x-www-form-urlencoded",
		encoding: charset,
		onLoading: function()
		{
			Element.show('loading-layerT');
		},
		onSuccess: function(transport)
		{
			var result = transport.responseText;
			
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
				$('thanks_zone_'+PostID).setAttribute('class', 'none');
				$('show_thanks_'+PostID).innerHTML = result;
				if (RemoveThanks)
				{
					Element.hide('remove_thanks_button_'+PostID);
					Element.show('thanks_button_'+PostID);
				}
				else
				{
					Element.hide('thanks_button_'+PostID);
					Element.show('remove_thanks_button_'+PostID);
				}
			}
			Element.hide('loading-layerT');
		},
		onException: function(req,exception)
		{
			alert(l_ajaxerror+"\n\n"+exception);
			Element.hide('loading-layerT');
		},
		onFailure: function ()
		{
			alert(l_ajaxerror);
			Element.hide('loading-layerT');
		}
	});
}