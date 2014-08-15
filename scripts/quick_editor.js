function insert(aTag,eTag,TSformname,TStextareaname)
{var input=document.forms[TSformname].elements[TStextareaname];input.focus();if(typeof document.selection!='undefined')
{var range=document.selection.createRange();var insText=range.text;range.text=aTag+insText+eTag;range=document.selection.createRange();if(insText.length==0)
{range.move('character',aTag.length+insText.length+eTag.length);}
else
{range.moveStart('character',aTag.length+insText.length+eTag.length);}
range.select();}
else if(typeof input.selectionStart!='undefined')
{var start=input.selectionStart;var end=input.selectionEnd;var insText=input.value.substring(start,end);input.value=input.value.substr(0,start)+aTag+insText+eTag+input.value.substr(end);var pos;if(insText.length==0)
{pos=start+aTag.length+insText.length+eTag.length;}
else
{pos=start+aTag.length+insText.length+eTag.length;}
input.selectionStart=pos;input.selectionEnd=pos;}
else
{var pos;var re=new RegExp('^[0-9]{0,3}$');while(!re.test(pos))
{pos=prompt("Insert at position (0.."+input.value.length+"):","0");}
if(pos>input.value.length)
{pos=input.value.length;}
var insText=prompt("Please you enter the text which can be formatted:");input.value=input.value.substr(0,pos)+aTag+insText+eTag+input.value.substr(pos);}}
function setsmile(smiley,TSformname,TStextareaname)
{document.forms[TSformname].elements[TStextareaname].focus();document.forms[TSformname].elements[TStextareaname].value=document.forms[TSformname].elements[TStextareaname].value+smiley;document.forms[TSformname].elements[TStextareaname].focus();}