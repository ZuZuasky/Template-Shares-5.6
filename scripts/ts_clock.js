function show_ts_clock()
{	
	var localTime = new Date();
	var ms = localTime.getTime() 
			 + (localTime.getTimezoneOffset() * 30000)
			 + TimezoneOffset * 3600000;

	var time = new Date(ms);
	var hour = time.getHours();
	var minute = time.getMinutes();
	var second = time.getSeconds();
	var temp = ((hour < 10) ? "0" : "") + hour;
	temp += ((minute < 10) ? ":0" : ":") + minute;
	temp += ((second < 10) ? ":0" : ":") + second;
	document.getElementById('showTSclock').innerHTML=temp;
	id = setTimeout("show_ts_clock()",1000);
 };