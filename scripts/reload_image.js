function reload ()
{
	TSGetID('regimage').src = baseurl+"/include/class_tscaptcha.php?" + (new Date()).getTime() + "&width=132&height=50";
	return;
};