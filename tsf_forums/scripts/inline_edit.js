var inlineEditor=Class.create();function HTMLchars(text)
{text=text.replace(new RegExp("&(?!#[0-9]+;)","g"),"&amp;");text=text.replace(/</g,"&lt;");text=text.replace(/>/g,"&gt;");text=text.replace(/"/g,"&quot;");text=text.replace(/  /g,"&nbsp;&nbsp;");return text;}
function unHTMLchars(text)
{text=text.replace(/&lt;/g,"<");text=text.replace(/&gt;/g,">");text=text.replace(/&nbsp;/g," ");text=text.replace(/&quot;/g,"\"");text=text.replace(/&amp;/g,"&");return text;}
var DomLib={addClass:function(element,name)
{if(element)
{if(element.className!="")
{element.className+=" "+name;}
else
{element.className=name;}}},removeClass:function(element,name)
{if(element.className==element.className.replace(" ","-"))
{element.className=element.className.replace(name,"");}
else
{element.className=element.className.replace(" "+name,"");}},getElementsByClassName:function(oElm,strTagName,strClassName)
{var arrElements=(strTagName=="*"&&document.all)?document.all:oElm.getElementsByTagName(strTagName);var arrReturnElements=new Array();strClassName=strClassName.replace(/\-/g,"\\-");var oRegExp=new RegExp("(^|\\s)"+strClassName+"(\\s|$)");var oElement;for(var i=0;i<arrElements.length;i++)
{oElement=arrElements[i];if(oRegExp.test(oElement.className))
{arrReturnElements.push(oElement);}}
return(arrReturnElements)},getPageScroll:function()
{var yScroll;if(self.pageYOffset)
{yScroll=self.pageYOffset;}
else if(document.documentElement&&document.documentElement.scrollTop)
{yScroll=document.documentElement.scrollTop;}
else if(document.body)
{yScroll=document.body.scrollTop;}
arrayPageScroll=new Array('',yScroll);return arrayPageScroll;},getPageSize:function()
{var xScroll,yScroll;if(window.innerHeight&&window.scrollMaxY)
{xScroll=document.body.scrollWidth;yScroll=window.innerHeight+window.scrollMaxY;}
else if(document.body.scrollHeight>document.body.offsetHeight)
{xScroll=document.body.scrollWidth;yScroll=document.body.scrollHeight;}
else
{xScroll=document.body.offsetWidth;yScroll=document.body.offsetHeight;}
var windowWidth,windowHeight;if(self.innerHeight)
{windowWidth=self.innerWidth;windowHeight=self.innerHeight;}
else if(document.documentElement&&document.documentElement.clientHeight)
{windowWidth=document.documentElement.clientWidth;windowHeight=document.documentElement.clientHeight;}
else if(document.body)
{windowWidth=document.body.clientWidth;windowHeight=document.body.clientHeight;}
var pageHeight,pageWidth;if(yScroll<windowHeight)
{pageHeight=windowHeight;}
else
{pageHeight=yScroll;}
if(xScroll<windowWidth)
{pageWidth=windowWidth;}
else
{pageWidth=xScroll;}
var arrayPageSize=new Array(pageWidth,pageHeight,windowWidth,windowHeight);return arrayPageSize;}}
inlineEditor.prototype={initialize:function(url,options)
{this.url=url;this.elements=new Array();this.currentElement='';this.options=options;if(!options.className)
{alert('You need to specify a className in the options.');return false;}
this.className=options.className;if(options.spinnerImage)
{this.spinnerImage=options.spinnerImage;}
this.elements=DomLib.getElementsByClassName(document,"*",options.className);if(this.elements)
{for(var i=0;i<this.elements.length;i++)
{if(this.elements[i].id)
{this.makeEditable(this.elements[i]);}}}
return true;},makeEditable:function(element)
{if(element.title!="")
{element.title=element.title+" ";}
if(!this.options.lang_click_edit)
{this.options.lang_click_edit="(Click and hold to edit)";}
element.title=element.title+this.options.lang_click_edit;element.onmousedown=this.onMouseDown.bindAsEventListener(this);return true;},onMouseDown:function(e)
{var element=Event.element(e);Event.stop(e);if(this.currentElement!='')
{return false;}
if(typeof(element.id)=="undefined"&&typeof(element.parentNode.id)!="undefined")
{element.id=element.parentNode.id;}
this.currentElement=element.id;this.timeout=setTimeout(this.showTextbox.bind(this),1200);document.onmouseup=this.onMouseUp.bindAsEventListener(this);return false;},onMouseUp:function(e)
{clearTimeout(this.timeout);Event.stop(e);return false;},onButtonClick:function(id)
{if($(id))
{this.currentElement=id;this.showTextbox();}
return false;},showTextbox:function()
{this.element=$(this.currentElement);if(typeof(this.element.parentNode)=="undefined"||typeof(this.element.id)=="undefined")
{return false;}
this.oldValue=this.element.innerHTML;this.testNode=this.element.parentNode;if(!this.testNode)
{return false;}
this.cache=this.testNode.innerHTML;this.textbox=document.createElement("input");this.textbox.style.width="95%";this.textbox.maxlength="85";this.textbox.className="textbox";this.textbox.type="text";Event.observe(this.textbox,"blur",this.onBlur.bindAsEventListener(this));Event.observe(this.textbox,"keypress",this.onKeyUp.bindAsEventListener(this));this.textbox.setAttribute("autocomplete","off");this.textbox.name="value";this.textbox.index=this.element.index;this.textbox.value=unHTMLchars(this.oldValue);Element.remove(this.element);this.testNode.innerHTML='';this.testNode.appendChild(this.textbox);this.textbox.focus();return true;},onBlur:function(e)
{this.hideTextbox();return true;},onKeyUp:function(e)
{if(e.keyCode==Event.KEY_RETURN)
{this.hideTextbox();}
else if(e.keyCode==Event.KEY_ESC)
{this.cancelEdit();}
return true;},onSubmit:function(e)
{this.hideTextbox();return true;},hideTextbox:function()
{Event.stopObserving(this.textbox,"blur",this.onBlur.bindAsEventListener(this));var newValue=this.textbox.value;if(typeof(newValue)!="undefined"&&newValue!=''&&HTMLchars(newValue)!=this.oldValue)
{this.testNode.innerHTML=this.cache;this.element=$(this.currentElement);this.element.innerHTML=newValue;this.element.onmousedown=this.onMouseDown.bindAsEventListener(this);this.lastElement=this.currentElement;postData="value="+encodeURIComponent(newValue);if(this.spinnerImage)
{this.showSpinner();}
idInfo=this.element.id.split("_");if(idInfo[0]&&idInfo[1])
{postData=postData+"&"+idInfo[0]+"="+idInfo[1];}
new ajax(this.url,{method:'post',postBody:postData,onComplete:this.onComplete.bind(this)});}
else
{Element.remove(this.textbox);this.testNode.innerHTML=this.cache;this.element=$(this.currentElement);this.element.onmousedown=this.onMouseDown.bindAsEventListener(this);}
this.currentElement='';return true;},cancelEdit:function()
{Element.remove(this.textbox);this.testNode.innerHTML=this.cache;this.element=$(this.currentElement);this.element.onmousedown=this.onMouseDown.bindAsEventListener(this);this.currentCurrentElement='';},onComplete:function(request)
{if(request.responseText.match(/<error>(.*)<\/error>/))
{message=request.responseText.match(/<error>(.*)<\/error>/);this.element.innerHTML=this.oldValue;if(!message[1])
{message[1]="An unknown error occurred.";}
alert('There was an error performing the update.\n\n'+message[1]);}
else if(request.responseText)
{this.element.innerHTML=HTMLchars(request.responseText);}
if(this.spinnerImage)
{this.hideSpinner();}
this.currentIndex=-1;return true;},showSpinner:function()
{if(!this.spinnerImage)
{return false;}
if(!this.spinner)
{this.spinner=document.createElement("img");this.spinner.src=this.spinnerImage;if(saving_changes)
{this.spinner.alt=saving_changes;}
else
{this.spinner.alt="Saving changes..";}
this.spinner.style.verticalAlign="middle";this.spinner.style.paddingRight="3px";}
this.testNode.insertBefore(this.spinner,this.testNode.firstChild);return true;},hideSpinner:function()
{if(!this.spinnerImage)
{return false;}
Element.remove(this.spinner);return true;}}