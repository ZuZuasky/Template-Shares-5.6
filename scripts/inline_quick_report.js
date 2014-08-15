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

function TSajaxquickreportcomment(cID)
{	
	var reason = $('reason'+cID).value;
	var pars = 'ajax_quick_report=1&action=reportcomment&do=save&siv=false&reportid='+intval(cID)+'&reason='+urlencode(reason);
	new Ajax.Request(baseurl+"/report.php",
	{
		parameters: pars,
		method: "POST",
		contentType: "application/x-www-form-urlencoded",
		encoding: charset,
		onLoading: function()
		{
			Element.show('report-loader-'+cID);
		},
		onSuccess: function(transport)
		{
			Element.hide('report-loader-'+cID);
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
				$('reason'+cID).value = '';
				Element.hide('report-loader-'+cID);
				$('report_message_'+cID).innerHTML = '<br /><table width="100%" border="0" cellpadding="3" cellspacing="0"><tr><td class="thead">'+ReportComment+'</td></tr><tr><td>'+ReportDone+'</td></tr><table>';
				Element.hide('report_image_'+cID);
			}
		},
		onException: function(req,exception)
		{
			alert(l_ajaxerror+"\n\n"+exception);
			Element.hide('report-loader-'+cID);
		},
		onFailure: function ()
		{
			alert(l_ajaxerror);
			Element.hide('report-loader-'+cID);
		}
	});
}

function TSReportComment(CommentID)
{
	if ($('report_message_'+CommentID).style.display == 'none')
	{
		Element.show('report_message_'+CommentID);
		OrjDivContent = $('report_message_'+CommentID).innerHTML = '<br /><form method="POST" action="report.php" name="report_message_'+CommentID+'" id="report_message_'+CommentID+'"><table width="100%" border="0" cellpadding="3" cellspacing="0"><tr><td class="thead">'+ReportComment+'</td></tr><tr><td>'+ReportReason+'<br /><textarea id="reason'+CommentID+'" name="reason'+CommentID+'" style="width: 600px; height: 60px;"></textarea><br /><input onclick="TSajaxquickreportcomment('+CommentID+'); return false;" type="submit" value="'+ReportComment+'" /> <img src="'+dimagedir+'ajax-loader.gif" class="inlineimg" id="report-loader-'+CommentID+'" border="0" style="display: none;" alt="" title="" /></td></tr></table></form>';
	}
	else
	{
		Element.hide('report_message_'+CommentID);
	}
}