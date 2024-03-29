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

function TSajaxquickcomment(TorrentID)
{
	var message = $('message').value;
	var pars = 'ajax_quick_comment=1&id='+intval(TorrentID)+'&text='+urlencode(message);
	new Ajax.Request(baseurl+"/ts_ajax.php",
	{
		parameters: pars,
		method: "POST",
		contentType: "application/x-www-form-urlencoded",
		encoding: charset,
		onLoading: function()
		{
			Element.show('loading-layer');
			$('comment').quickcomment.disabled=true;
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
				var NewDiv = document.createElement('div');
				NewDiv.setAttribute('id','PostedReply');
				NewDiv.innerHTML = result;
				$('ajax_comment_preview').appendChild(NewDiv);
				$('message').value='';
			}
			Element.hide('loading-layer');
			$('comment').quickcomment.disabled=false;	
		},
		onException: function(req,exception)
		{
			alert(l_ajaxerror+"\n\n"+exception);
			Element.hide('loading-layer');
			$('comment').quickcomment.disabled=false;
		},
		onFailure: function ()
		{
			alert(l_ajaxerror);
			Element.hide('loading-layer');
			$('comment').quickcomment.disabled=false;
		}
	});
}