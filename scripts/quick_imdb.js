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

function TS_IMDB(TorrentID)
{
	var pars = 'tid='+intval(TorrentID);
	new Ajax.Request(baseurl+"/ts_ajax5.php",
	{		
		parameters: pars,
		method: "POST",
		contentType: "application/x-www-form-urlencoded",
		encoding: charset,
		onLoading: function()
		{
			$('imdbupdatebutton').innerHTML = l_pleasewait;
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
				$('imdbupdatebutton').innerHTML = l_ajaxerror;
			}
			else
			{
				$('imdbdetails').innerHTML = result;
				$('imdbupdatebutton').innerHTML = l_updated;
				new Effect.Highlight($('imdbdetails').parentNode, { startcolor: '#ffff99',endcolor: '#ffffff' });
			}
		},		
		onFailure: function ()
		{
			alert(l_ajaxerror);
			$('imdbupdatebutton').innerHTML = l_refresh;
		}
	});
}