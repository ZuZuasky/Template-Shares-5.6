var messageEditor=Class.create();
if(imagepath=="undefined"||imagepath==""||imagepath==null)
{
	var imagepath="images/"
}
if(baseurl=="undefined"||baseurl==""||baseurl==null)
{
	var baseurl="/"
}
messageEditor.prototype=
	{openTags:new Array(),initialize:function(textarea,options)
{this.options=options;if(this.options)
{if(!this.options.lang)
{return false;}
if(!this.options.rtl)
{this.options.rtl=0;}}

this.fonts=new Array();
this.fonts["Arial"]="Arial";
this.fonts["Arial Black"]="Arial Black";
this.fonts["Arial Narrow"]="Arial Narrow";
this.fonts["Book Antiqua"]="Book Antiqua";
this.fonts["Century Gothic"]="Century Gothic";
this.fonts["Comic Sans MS"]="Comic Sans MS";
this.fonts["Courier New"]="Courier New";
this.fonts["Fixedsys"]="Fixedsys";
this.fonts["Franklin Gothic Medium"]="Franklin Gothic Medium";
this.fonts["Garamond"]="Garamond";
this.fonts["Georgia"]="Georgia";
this.fonts["Impact"]="Impact";
this.fonts["Lucida Console"]="Lucida Console";
this.fonts["Lucida Sans Unicode"]="Lucida Sans Unicode";
this.fonts["Microsoft Sans Serif"]="Microsoft Sans Serif";
this.fonts["Palatino Linotype"]="Palatino Linotype";
this.fonts["System"]="System";
this.fonts["Tahoma"]="Tahoma";
this.fonts["Times New Roman"]="Times New Roman";
this.fonts["Trebuchet MS"]="Trebuchet MS";
this.fonts["Verdana"]="Verdana";

this.sizes=new Array();
this.sizes["xx-small"]=this.options.lang.size_xx_small;
this.sizes["x-small"]=this.options.lang.size_x_small;
this.sizes["small"]=this.options.lang.size_small;
this.sizes["medium"]=this.options.lang.size_medium;
this.sizes["x-large"]=this.options.lang.size_x_large;
this.sizes["xx-large"]=this.options.lang.size_xx_large;

this.colors=new Array();
this.colors["#ffffff"]=this.options.lang.color_white;
this.colors["#000"]=this.options.lang.color_black;
this.colors["#FF0000"]=this.options.lang.color_red;
this.colors["#FFFF00"]=this.options.lang.color_yellow;
this.colors["#FFC0CB"]=this.options.lang.color_pink;
this.colors["#008000"]=this.options.lang.color_green;
this.colors["#FFA500"]=this.options.lang.color_orange;
this.colors["#800080"]=this.options.lang.color_purple;
this.colors["#0000FF"]=this.options.lang.color_blue;
this.colors["#F5F5DC"]=this.options.lang.color_beige;
this.colors["#A52A2A"]=this.options.lang.color_brown;
this.colors["#008080"]=this.options.lang.color_teal;
this.colors["#000080"]=this.options.lang.color_navy;
this.colors["#800000"]=this.options.lang.color_maroon;
this.colors["#32CD32"]=this.options.lang.color_limegreen;

this.textarea=textarea;
Event.observe(window,"load",this.showEditor.bindAsEventListener(this));},showEditor:function()
{oldTextarea=$(this.textarea);
this.textarea+="_new";
editor=document.createElement("div");
editor.style.position="relative";
editor.className="editor";
if(this.options&&this.options.width)
{w=this.options.width;}
else if(oldTextarea.style.width)
{w=oldTextarea.style.width;}
else if(oldTextarea.clientWidth)
{w=oldTextarea.clientWidth+"px";}
else
{w="540px";}
if(this.options&&this.options.height)
{w=this.options.height;}
else if(oldTextarea.style.height)
{h=oldTextarea.style.height;}
else if(oldTextarea.clientHeight)
{h=oldTextarea.clientHeight+"px";}
else
{h="400px";}

editor.style.width="560px";
if (TSSE.browser == "ie")
{
	editor.style.height="435px";
}
else
{
	editor.style.height="396px";
}

editor.style.padding="3px";

toolBar=document.createElement("div");
toolBar.style.height="36px";
toolBar.style.position="relative";

textFormatting=document.createElement("div");
textFormatting.style.position="absolute";
textFormatting.style.width="100%";

if(this.options.rtl==1)
{
	textFormatting.style.right=0;
}
else
{
	textFormatting.style.left=0;
}
toolBar.appendChild(textFormatting);

fontSelect=document.createElement("select");
fontSelect.style.margin="2px";
fontSelect.id="font";
fontSelect.options[fontSelect.options.length]=new Option(this.options.lang.font,"-");
fontSelect.style.height="23px";
for(font in this.fonts)
{
	fontSelect.options[fontSelect.options.length]=new Option(this.fonts[font],font);
	fontSelect.options[fontSelect.options.length-1].style.fontFamily=font;
}
Event.observe(fontSelect,"change",this.changeFont.bindAsEventListener(this));
textFormatting.appendChild(fontSelect);

sizeSelect=document.createElement("select");
sizeSelect.style.margin="2px";
sizeSelect.id="size";
sizeSelect.options[sizeSelect.options.length]=new Option(this.options.lang.size,"-");
sizeSelect.style.height="23px";
for(size in this.sizes)
{
	sizeSelect.options[sizeSelect.options.length]=new Option(this.sizes[size],size);
	sizeSelect.options[sizeSelect.options.length-1].style.fontSize=size;
}
Event.observe(sizeSelect,"change",this.changeSize.bindAsEventListener(this));
textFormatting.appendChild(sizeSelect);

colorSelect=document.createElement("select");
colorSelect.style.margin="2px";
colorSelect.id="color";
colorSelect.options[colorSelect.options.length]=new Option(this.options.lang.color,"-");
colorSelect.style.height="23px";
for(color in this.colors)
{
	colorSelect.options[colorSelect.options.length]=new Option(this.colors[color],color);
	colorSelect.options[colorSelect.options.length-1].style.backgroundColor=color;
	colorSelect.options[colorSelect.options.length-1].style.color=color;
}
Event.observe(colorSelect,"change",this.changeColor.bindAsEventListener(this));
textFormatting.appendChild(colorSelect);

closeBar=document.createElement("div");closeBar.style.position="absolute";if(this.options.rtl==1)
{closeBar.style.left=0;}
else
{closeBar.style.right=0;}
var closeButton=document.createElement("img");
closeButton.id="close_tags";
closeButton.src=""+imagepath+"codebuttons/close_tags.gif";
closeButton.title="";closeButton.className="toolbar_normal";
closeButton.height=22;
closeButton.width=80;
closeButton.style.margin="2px";
closeButton.style.visibility='hidden';
Event.observe(closeButton,"mouseover",this.toolbarItemHover.bindAsEventListener(this));
Event.observe(closeButton,"mouseout",this.toolbarItemOut.bindAsEventListener(this));
Event.observe(closeButton,"click",this.toolbarItemClick.bindAsEventListener(this));
closeBar.appendChild(closeButton);
toolBar.appendChild(closeBar);
editor.appendChild(toolBar);

toolbar2=document.createElement("div");
toolbar2.style.height="28px";
toolbar2.style.position="relative";

formatting=document.createElement("div");
formatting.style.position="absolute";
formatting.style.width="100%";
formatting.style.whiteSpace="nowrap";
if(this.options.rtl==1)
{
	formatting.style.right=0;
}
else
{
	formatting.style.left=0;
}
toolbar2.appendChild(formatting);
this.insertStandardButton(formatting,"b",""+imagepath+"codebuttons/bold.gif","b","",this.options.lang.title_bold);
this.insertStandardButton(formatting,"i",""+imagepath+"codebuttons/italic.gif","i","",this.options.lang.title_italic);
this.insertStandardButton(formatting,"u",""+imagepath+"codebuttons/underline.gif","u","",this.options.lang.title_underline);
this.insertSeparator(formatting);
this.insertStandardButton(formatting,"align_left",""+imagepath+"codebuttons/align_left.gif","align","left",this.options.lang.title_left);
this.insertStandardButton(formatting,"align_center",""+imagepath+"codebuttons/align_center.gif","align","center",this.options.lang.title_center);
this.insertStandardButton(formatting,"align_right",""+imagepath+"codebuttons/align_right.gif","align","right",this.options.lang.title_right);
this.insertSeparator(formatting);
this.insertStandardButton(formatting,"list_num",""+imagepath+"codebuttons/list_num.gif","list","1",this.options.lang.title_numlist);
this.insertStandardButton(formatting,"list_bullet",""+imagepath+"codebuttons/list_bullet.gif","list","",this.options.lang.title_bulletlist);
this.insertSeparator(formatting);
this.insertStandardButton(formatting,"img",""+imagepath+"codebuttons/image.gif","image","",this.options.lang.title_image);
this.insertStandardButton(formatting,"url",""+imagepath+"codebuttons/link.gif","url","",this.options.lang.title_hyperlink);
this.insertStandardButton(formatting,"email",""+imagepath+"codebuttons/email.gif","email","",this.options.lang.title_email);
this.insertSeparator(formatting);
this.insertStandardButton(formatting,"quote",""+imagepath+"codebuttons/quote.gif","quote","",this.options.lang.title_quote);
this.insertSeparator(formatting);
this.insertStandardButton(formatting,"code",""+imagepath+"codebuttons/code.gif","code","",this.options.lang.title_code);
this.insertStandardButton(formatting,"php",""+imagepath+"codebuttons/php.gif","php","",this.options.lang.title_php);
this.insertStandardButton(formatting,"sql",""+imagepath+"codebuttons/sql.gif","sql","",this.options.lang.title_sql);
this.insertSeparator(formatting);
this.insertStandardButton(formatting,"youtube",""+imagepath+"codebuttons/youtube.gif","youtube","",this.options.lang.title_youtube);
this.insertSeparator(formatting);
this.insertStandardButton(formatting,"undo",""+imagepath+"codebuttons/undo.gif","undo","",this.options.lang.undo);
this.insertStandardButton(formatting,"redo",""+imagepath+"codebuttons/redo.gif","redo","",this.options.lang.redo);

editor.appendChild(toolbar2);
areaContainer=document.createElement("div");
areaContainer.style.clear="both";
subtract=subtract2=0;
if(TSSE.browser!="ie"||(TSSE.browser=="ie"&&TSSE.useragent.indexOf('msie 7.')!=-1))
{
	subtract=subtract2=8;
}
areaContainer.style.height=parseInt(editor.style.height)-parseInt(toolBar.style.height)-parseInt(toolbar2.style.height)-subtract+"px";
areaContainer.style.width=parseInt(editor.style.width)-subtract2+"px";
textInput=document.createElement("textarea");
textInput.id=this.textarea;
textInput.onkeydown=function () { countclik(TSGetID('message_new')) };
textInput.name=oldTextarea.name+"_new";
textInput.style.height=parseInt(areaContainer.style.height)+"px";
textInput.style.width=parseInt(areaContainer.style.width)+"px";
if(oldTextarea.value!='')
{textInput.value=oldTextarea.value;}
if(oldTextarea.tabIndex)
{textInput.tabIndex=oldTextarea.tabIndex;}
areaContainer.appendChild(textInput);editor.appendChild(areaContainer);if(oldTextarea.form)
{Event.observe(oldTextarea.form,"submit",this.closeTags.bindAsEventListener(this));Event.observe(oldTextarea.form,"submit",this.updateOldArea.bindAsEventListener(this));}
oldTextarea.style.visibility="hidden";oldTextarea.style.position="absolute";oldTextarea.style.top="-1000px";oldTextarea.id+="_old";this.oldTextarea=oldTextarea;oldTextarea.parentNode.insertBefore(editor,oldTextarea);Event.observe(textInput,"keyup",this.updateOldArea.bindAsEventListener(this));Event.observe(textInput,"blur",this.updateOldArea.bindAsEventListener(this));},updateOldArea:function(e)
{this.oldTextarea.value=$(this.textarea).value;},insertStandardButton:function(into,id,src,insertText,insertExtra,alt)
{var button=document.createElement("img");button.id=id;button.src=src;button.alt=alt;button.title=alt;button.insertText=insertText;button.insertExtra=insertExtra;button.className="toolbar_normal";button.height=20;button.width=21;button.style.margin="2px";Event.observe(button,"mouseover",this.toolbarItemHover.bindAsEventListener(this));Event.observe(button,"mouseout",this.toolbarItemOut.bindAsEventListener(this));Event.observe(button,"click",this.toolbarItemClick.bindAsEventListener(this));into.appendChild(button);},insertSeparator:function(into)
{var separator=document.createElement("img");separator.style.margin="2px";separator.src=""+imagepath+"codebuttons/sep.gif";separator.style.verticalAlign="top";separator.className="toolbar_sep";into.appendChild(separator);},toolbarItemOut:function(e)
{element=TSSE.eventElement(e);if(!element)
{return false;}
if(element.insertText)
{if(element.insertExtra)
{insertCode=element.insertText+"_"+element.insertExtra;}
else
{insertCode=element.insertText;}
if(TSSE.inArray(insertCode,this.openTags))
{DomLib.addClass(element,"toolbar_clicked");}}
DomLib.removeClass(element,"toolbar_hover");},toolbarItemHover:function(e)
{element=TSSE.eventElement(e);if(!element)
{return false;}
DomLib.addClass(element,"toolbar_hover");},toolbarItemClick:function(e)
{element=TSSE.eventElement(e);if(!element)
{return false;}
if(element.id=="close_tags")
{this.closeTags();}
else
{this.insertMyCode(element.insertText,element.insertExtra);}},changeFont:function(e)
{element=TSSE.eventElement(e);if(!element)
{return false;}
this.insertMyCode("font",element.options[element.selectedIndex].value);if(this.getSelectedText($(this.textarea)))
{element.selectedIndex=0;}},changeSize:function(e)
{element=TSSE.eventElement(e);if(!element)
{return false;}
this.insertMyCode("size",element.options[element.selectedIndex].value);if(this.getSelectedText($(this.textarea)))
{element.selectedIndex=0;}},changeColor:function(e)
{element=TSSE.eventElement(e);if(!element)
{return false;}
this.insertMyCode("color",element.options[element.selectedIndex].value);if(this.getSelectedText($(this.textarea)))
{element.selectedIndex=0;}},insertList:function(type)
{list="";do
{listItem=prompt(this.options.lang.enter_list_item,"");if(listItem!=""&&listItem!=null)
{list=list+"[*]"+listItem+"\n";}}
while(listItem!=""&&listItem!=null);if(list=="")
{return false;}
if(type)
{list="[list="+type+"]\n"+list;}
else
{list="[list]\n"+list;}
list=list+"[/list]\n";this.performInsert(list,"",true,false);},insertURL:function()
{selectedText=this.getSelectedText($(this.textarea));url=prompt(this.options.lang.enter_url,"http://");if(url)
{if(!selectedText)
{title=prompt(this.options.lang.enter_url_title,"");}
else
{title=selectedText;}
if(title)
{this.performInsert("[url="+url+"]"+title+"[/url]","",true,false);}
else
{this.performInsert("[url]"+url+"[/url]","",true,false);}}},insertEmail:function()
{selectedText=this.getSelectedText($(this.textarea));email=prompt(this.options.lang.enter_email,"");if(email)
{if(!selectedText)
{title=prompt(this.options.lang.enter_email_title,"");}
else
{title=selectedText;}
if(title)
{
	this.performInsert("[email="+email+"]"+title+"[/email]","",true,false);
}
else
{
	this.performInsert("[email]"+email+"[/email]","",true,false);
}
}
},insertIMG:function()
	{
		image=prompt(this.options.lang.enter_image,"http://");
		if(image)
		{
			this.performInsert("[img]"+image+"[/img]","",true);
		}
	},
	TSUndo:function()
	{
		undo(TSGetID('message_new'));
	},
	TSRedo:function()
	{
		redo(TSGetID('message_new'));
	}
	,insertMyCode:function(code,extra)
{
	switch(code)
	{
		case "undo":
			this.TSUndo();
		break;
		case "redo":
			this.TSRedo();
		break;
		case"list":
			this.insertList(extra);
		break;
		case"url":
			this.insertURL();
		break;
		case"image":
			this.insertIMG();
		break;
		case"email":
			this.insertEmail();
		break;
		default:
			var already_open=false;
			var no_insert=false;
			if(extra)
			{
				var full_tag=code+"_"+extra;
			}
			else
			{
				var full_tag=code;
			}
			var newTags=new Array();
			for(var i=0;i<this.openTags.length;++i)
			{
				if(this.openTags[i])
				{
					exploded_tag=this.openTags[i].split("_");
					if(exploded_tag[0]==code)
					{
						already_open=true;
						this.performInsert("[/"+exploded_tag[0]+"]","",false);
						if($(this.openTags[i]))
						{
							$(this.openTags[i]).className="toolbar_normal";
						}
						if(this.openTags[i]==full_tag)
						{
							no_insert=true;
						}
					}
					else
					{
						newTags[newTags.length]=this.openTags[i];
					}
				}
			}
		this.openTags=newTags;
		var do_insert=false;
		if(extra!=""&&extra!="-"&&no_insert==false)
			{
				start_tag="["+code+"="+extra+"]";
				end_tag="[/"+code+"]";
				do_insert=true;
			}
		else if(!extra&&already_open==false)
		{start_tag="["+code+"]";end_tag="[/"+code+"]";do_insert=true;}
		if(do_insert==true)
		{if(!this.performInsert(start_tag,end_tag,true))
		{TSSE.arrayPush(this.openTags,full_tag);$('close_tags').style.visibility='';}
		else if($(full_tag))
		{DomLib.removeClass($(full_tag),"toolbar_clicked");}}
	}
if(this.openTags.length==0)
{$('close_tags').style.visibility='hidden';}},getSelectedText:function(element)
{element.focus();if(document.selection)
{var selection=document.selection;var range=selection.createRange();if((selection.type=="Text"||selection.type=="None")&&range!=null)
{return range.text;}}
else if(element.selectionEnd)
{var select_start=element.selectionStart;var select_end=element.selectionEnd;if(select_end<=2)
{select_end=element.textLength;}
var start=element.value.substring(0,select_start);var middle=element.value.substring(select_start,select_end);return middle;}},performInsert:function(open_tag,close_tag,is_single,ignore_selection)
{var is_closed=true;if(!ignore_selection)
{var ignore_selection=false;}
if(!close_tag)
{var close_tag="";}
var textarea=$(this.textarea);textarea.focus();if(document.selection)
{var selection=document.selection;var range=selection.createRange();if(ignore_selection!=false)
{selection.collapse;}
if((selection.type=="Text"||selection.type=="None")&&range!=null&&ignore_selection!=true)
{if(close_tag!=""&&range.text.length>0)
{var keep_selected=true;range.text=open_tag+range.text+close_tag;}
else
{var keep_selected=false;if(is_single)
{is_closed=false;}
range.text=open_tag;}
range.select();}
else
{textarea.value+=open_tag;}}
else if(textarea.selectionEnd)
{var select_start=textarea.selectionStart;var select_end=textarea.selectionEnd;var scroll_top=textarea.scrollTop;if(select_end<=2)
{select_end=textarea.textLength;}
var start=textarea.value.substring(0,select_start);var middle=textarea.value.substring(select_start,select_end);var end=textarea.value.substring(select_end,textarea.textLength);if(select_end-select_start>0&&ignore_selection!=true&&close_tag!="")
{var keep_selected=true;middle=open_tag+middle+close_tag;}
else
{var keep_selected=false;if(is_single)
{is_closed=false;}
middle=open_tag;}
textarea.value=start+middle+end;if(keep_selected==true&&ignore_selection!=true)
{textarea.selectionStart=select_start;textarea.selectionEnd=select_start+middle.length;}
else if(ignore_selection!=true)
{textarea.selectionStart=select_start+middle.length;textarea.selectionEnd=textarea.selectionStart;}
textarea.scrollTop=scroll_top;}
else
{textarea.value+=open_tag;if(is_single)
{is_closed=false;}}
this.updateOldArea();textarea.focus();return is_closed;},closeTags:function()
{if(this.openTags[0])
{while(this.openTags[0])
{tag=TSSE.arrayPop(this.openTags);exploded_tag=tag.split("_");this.performInsert("[/"+exploded_tag[0]+"]","",false);if($(exploded_tag[0]))
{tag=$(exploded_tag[0]);if(tag.type=="select-one")
{tag.selectedIndex=0;}
else
{DomLib.removeClass($(tag),"toolbar_clicked");}}}}
$(this.textarea).focus();$('close_tags').style.visibility='hidden';this.openTags=new Array();},setToolbarItemState:function(id,state)
{element=$(id);if(element&&element!=null)
{element.className="toolbar_"+state;}},bindSmilieInserter:function(id)
{if(!$(id))
{return false;}
smilies=DomLib.getElementsByClassName($(id),"img","smilie");if(smilies.length>0)
{for(var i=0;i<smilies.length;++i)
{var smilie=smilies[i];smilie.onclick=this.insertSmilie.bindAsEventListener(this);smilie.style.cursor="pointer";}}},openGetMoreSmilies:function(editor)
{TSSE.popupWindow(baseurl+'/moresmiles.php?action=smilies&popup=true&editor='+editor,'sminsert',500,280);},insertSmilie:function(e)
{element=TSSE.eventElement(e);if(!element||!element.alt)
{return false;}
this.performInsert(element.alt,"",true,false);},insertAttachment:function(aid)
{this.performInsert("[attachment="+aid+"]","",true,false);}
};

function iObject() {
  this.i;
  return this;
}

var myObject=new iObject();
myObject.i=0;
var myObject2=new iObject();
myObject2.i=0;
store_text=new Array();

store_text[0]="";

function countclik(tag) {
  myObject.i++;
  var y=myObject.i;
  var x=tag.value;
  store_text[y]=x;
}

function undo(tag) {
  if ((myObject2.i)<(myObject.i)) {
    myObject2.i++;
  } else {
    //
  }
  var z=store_text.length;
  z=z-myObject2.i;
  if (store_text[z]) {
  	tag.value=store_text[z];
  } else {
  	tag.value=store_text[0];
  }
}

function redo(tag) {
  if((myObject2.i)>1) {
    myObject2.i--;
  } else {
    //
  }
  var z=store_text.length;
  z=z-myObject2.i;
  if (store_text[z]) {
    tag.value=store_text[z];
  } else {
  tag.value=store_text[0];
  }
}