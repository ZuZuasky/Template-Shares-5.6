function TSShowTorrents(TypE)
{
	var pars = 'type='+TypE;
	new Ajax.Request(baseurl+"/ts_ajax3.php",
	{
		parameters: pars,
		method: "POST",
		contentType: "application/x-www-form-urlencoded",
		encoding: charset,
		onLoading: function()
		{
			Element.show('loadingimg');
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
				Element.hide('loadingimg');
			}
			else
			{
				$('TSShowLatestTorrents').innerHTML = result;
				Element.hide('loadingimg');
			}
		},		
		onFailure: function ()
		{
			alert(l_ajaxerror);
			Element.hide('loadingimg');
		}
	});
}