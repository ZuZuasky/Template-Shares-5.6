var is_regexp=(window.RegExp)?true:false;

function toggle_collapse(objid)
{
	if(!is_regexp)
	{
		return false;
	}
	obj=TSGetID('collapseobj_'+objid);
	img=TSGetID('collapseimg_'+objid);
	cel=TSGetID('collapsecel_'+objid);
	if(!obj)
	{
		if(img)
		{
			img.style.display='none';
		}
		return false;
	}
	if(obj.style.display=='none')
	{
		obj.style.display='';
		save_collapsed(objid,false);
		if(img)
		{
			img_re=new RegExp("_collapsed\\.gif$");
			img.src=img.src.replace(img_re,'.gif');
		}
		if(cel)
		{
			cel_re=new RegExp("^(thead|tcat)(_collapsed)$");
			cel.className=cel.className.replace(cel_re,'$1');
		}
	}
	else
	{
		obj.style.display='none';
		save_collapsed(objid,true);
		if(img)
		{
			img_re=new RegExp("\\.gif$");
			img.src=img.src.replace(img_re,'_collapsed.gif');
		}
		if(cel)
		{
			cel_re=new RegExp("^(thead|tcat)$");
			cel.className=cel.className.replace(cel_re,'$1_collapsed');
		}
	}
	return false;
}

function save_collapsed(objid,addcollapsed)
{
	var collapsed=fetch_cookie('ts_collapse');
	var tmp=new Array();
	if(collapsed!=null)
	{	
		collapsed=collapsed.split('\n');
		for(var i in collapsed)
		{
			if(collapsed[i]!=objid&&collapsed[i]!='')
			{
				tmp[tmp.length]=collapsed[i];
			}
		}
	}
	if(addcollapsed)
	{
		tmp[tmp.length]=objid;
	}
	expires=new Date();
	expires.setTime(expires.getTime()+(1000*86400*365));
	set_cookie('ts_collapse',tmp.join('\n'),expires);
}

function fetch_cookie(name)
{
	cookie_name=name+'=';
	cookie_length=document.cookie.length;
	cookie_begin=0;
	while(cookie_begin<cookie_length)
	{
		value_begin=cookie_begin+cookie_name.length;
		if(document.cookie.substring(cookie_begin,value_begin)==cookie_name)
		{
			var value_end=document.cookie.indexOf(';',value_begin);
			if(value_end==-1)
			{
				value_end=cookie_length;
			}
			return unescape(document.cookie.substring(value_begin,value_end));
		}
		cookie_begin=document.cookie.indexOf(' ',cookie_begin)+1;
		if(cookie_begin==0)
		{
			break;
		}		
	}	
	return null;
}

function set_cookie(name,value,expires)
{
	document.cookie=name+'='+escape(value)+'; path=/'+(typeof expires!='undefined'?'; expires='+expires.toGMTString():'');
}