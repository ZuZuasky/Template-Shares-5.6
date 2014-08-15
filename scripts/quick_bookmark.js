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

function TSajaxquickbookmark(BookmarkID, Action)
{
	var pars = 'ajax_quick_bookmark=1&action='+Action+'&torrentid='+intval(BookmarkID);
	new Ajax.Request(baseurl+"/bookmarks.php",
	{
		parameters: pars,
		method: "POST",
		contentType: "application/x-www-form-urlencoded",
		encoding: charset,
		onLoading: function()
		{
			Element.hide('bookmark-done-layer');
			Element.show('bookmark-loading-layer');
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
				// Nothing To do.
			}
			Element.hide('bookmark-loading-layer');
			Element.show('bookmark-done-layer');
			
		},
		onException: function(req,exception)
		{
			alert(l_ajaxerror+"\n\n"+exception);
			Element.hide('bookmark-done-layer');
			Element.hide('bookmark-loading-layer');
		},
		onFailure: function ()
		{
			alert(l_ajaxerror);
			Element.hide('bookmark-done-layer');
			Element.hide('bookmark-loading-layer');
		}
	});
}