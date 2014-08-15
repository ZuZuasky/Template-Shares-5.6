/***********************************************
* AnyLink Drop Down Menu- © Dynamic Drive (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit http://www.dynamicdrive.com/ for full source code
***********************************************/
/* Modified by xam. https://templateshares.net */

var defaultMenuWidth="150px"
var menuwidth='150px' //default menu width
var menubgcolor='black'  //menu bgcolor
var disappeardelay=250  //menu disappear speed onmouseout (in miliseconds)
var hidemenu_onclick="yes" //hide menu when user clicks within menu?

var menu1=new Array();
menu1[0]='<a href="' + baseurl + '/admin/index.php">' + l_staffpanel + '</a>'

var menu2=new Array();
menu2[0]='<a href="' + baseurl + '/admin/index.php">' + l_staffpanel + '</a>'
menu2[1]='<a href="' + baseurl + '/admin/index.php?act=statistics">' + l_trackerstats + '</a>'

if (typeof menu3 == "undefined")
{
	var menu3=new Array();
}
menu3[0]='<a href="' + baseurl + '/admin/index.php">' + l_staffpanel + '</a>'
menu3[1]='<a href="' + baseurl + '/admin/settings.php">' + l_trackersettings + '</a>'
menu3[2]='<a href="' + baseurl + '/admin/index.php?act=statistics">' + l_trackerstats + '</a>'

var menu4=new Array();
menu4[0]='<a href="' + baseurl + '/tsf_forums/">' + l_forums + '</a>'
menu4[1]='<a href="' + baseurl + '/tsf_forums/tsf_search.php?action=getnew">' + l_newposts + '</a>'
menu4[2]='<a href="' + baseurl + '/tsf_forums/tsf_search.php?action=daily">' + l_newdaily + '</a>'
menu4[3]='<a href="' + baseurl + '/tsf_forums/tsf_search.php">' + l_search + '</a>'

var menu5=new Array();
menu5[0]='<a href="' + baseurl + '/browse.php">' + l_browse + '</a>'
menu5[1]='<a href="' + baseurl + '/ts_tags.php">' + l_searchcloud + '</a>'
menu5[2]='<a href="' + baseurl + '/stats.php">' + l_torrentstats + '</a>'
menu5[3]='<a href="' + baseurl + '/browse.php?special_search=mybookmarks">' + l_bookmarks + '</a>'
menu5[4]='<a href="' + baseurl + '/browse.php?special_search=myreseeds">' + l_reseeds + '</a>'
menu5[5]='<a href="' + baseurl + '/browse.php?special_search=weaktorrents">' + l_weektorrents + '</a>'
menu5[6]='<a href="' + baseurl + '/ts_subtitles.php">' + l_subtitle + '</a>'
menu5[7]='<a href="' + baseurl + '/badusers.php">' + l_badusers + '</a>'

var menu6=new Array();
menu6[0]='<a href="' + baseurl + '/viewrequests.php">' + l_viewreq + '</a>'

var menu7=new Array();
menu7[0]='<a href="' + baseurl + '/usercp.php">' + l_ucphome + '</a>'
menu7[1]='<a href="' + baseurl + '/messages.php">' + l_ucppm + '</a>'
menu7[2]='<a href="' + baseurl + '/browse.php?special_search=mytorrents">' + l_ucpyourtorrents + '</a>'
menu7[3]='<a href="' + baseurl + '/referrals.php">' + l_referrals + '</a>'

var menu8=new Array();
menu8[0]='<a href="' + baseurl + '/topten.php?type=1">' + l_top10users + '</a>'
menu8[1]='<a href="' + baseurl + '/topten.php?type=2">' + l_top10torrents + '</a>'
menu8[2]='<a href="' + baseurl + '/topten.php?type=3">' + l_top10countries + '</a>'
menu8[3]='<a href="' + baseurl + '/topten.php?type=4">' + l_top10peers + '</a>'
menu8[4]='<a href="' + baseurl + '/topten.php?type=5">' + l_forums + '</a>'

var menu9=new Array();
menu9[0]='<a href="' + baseurl + '/rules.php">' + l_helprules + '</a>'
menu9[1]='<a href="' + baseurl + '/faq.php">' + l_helpfaq + '</a>'
menu9[2]='<a href="' + baseurl + '/links.php">' + l_helpusefulllinks + '</a>'
menu9[3]='<a href="' + baseurl + '/ts_tutorials.php">' + l_tutorial + '</a>'

var menu10=new Array();
menu10[0]='<a href="' + baseurl + '/staff.php">' + l_staffteam + '</a>'
menu10[1]='<a href="' + baseurl + '/contactstaff.php">' + l_staffcontact + '</a>'

var menu11=new Array();
menu11[0]='<a href="' + baseurl + '/userdetails.php">' + l_extraprofile + '</a>'
menu11[1]='<a href="' + baseurl + '/users.php">' + l_extramembers + '</a>'
menu11[2]='<a href="' + baseurl + '/friends.php">' + l_extrafriends + '</a>'
menu11[3]='<a href="' + baseurl + '/ts_lottery.php">' + l_lottery + '</a>'
menu11[4]='<a href="' + baseurl + '/getrss.php">' + l_extrarssfeed + '</a>'
menu11[5]='<a href="' + baseurl + '/invite.php">' + l_extrainvite + '</a>'
menu11[6]='<a href="' + baseurl + '/mybonus.php">' + l_extrabonus + '</a>'
menu11[7]='<a href="' + baseurl + '/donate.php">' + l_extradonate + '</a>'
menu11[8]='<a href="' + baseurl + '/logout.php" onclick="return log_out()">' + l_extralogout + '</a>'

var menu12=new Array();
menu12[0]='<a href="' + baseurl + '/upload.php">' + l_uploadtorrent + '</a>'
menu12[1]='<a href="' + baseurl + '/faq.php#37">' + l_uploadrules + '</a>'

/////No further editting needed

var ie4=document.all
var ns6=document.getElementById&&!document.all

if (ie4||ns6)
document.write('<div id="popitmenu" style="visibility:hidden;width:'+menuwidth+';background-color:'+menubgcolor+'" onmouseover="clearhidemenu()" onmouseout="dynamichide(event)"></div>')

function getposOffset(what, offsettype){
var totaloffset=(offsettype=="left")? what.offsetLeft : what.offsetTop;
var parentEl=what.offsetParent;
while (parentEl!=null){
totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
parentEl=parentEl.offsetParent;
}
return totaloffset;
}


function showhide(obj, e, visible, hidden, menuwidth){
if (ie4||ns6)
dropmenuobj.style.left=dropmenuobj.style.top="-500px"
if (menuwidth!=""){
dropmenuobj.widthobj=dropmenuobj.style
dropmenuobj.widthobj.width=menuwidth
}
if (e.type=="click" && obj.visibility==hidden || e.type=="mouseover")
obj.visibility=visible
else if (e.type=="click")
obj.visibility=hidden
}

function iecompattest(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function clearbrowseredge(obj, whichedge){
var edgeoffset=0
if (whichedge=="rightedge"){
var windowedge=ie4 && !window.opera? iecompattest().scrollLeft+iecompattest().clientWidth-15 : window.pageXOffset+window.innerWidth-15
dropmenuobj.contentmeasure=dropmenuobj.offsetWidth
if (windowedge-dropmenuobj.x < dropmenuobj.contentmeasure)
edgeoffset=dropmenuobj.contentmeasure-obj.offsetWidth
}
else{
var topedge=ie4 && !window.opera? iecompattest().scrollTop : window.pageYOffset
var windowedge=ie4 && !window.opera? iecompattest().scrollTop+iecompattest().clientHeight-15 : window.pageYOffset+window.innerHeight-18
dropmenuobj.contentmeasure=dropmenuobj.offsetHeight
if (windowedge-dropmenuobj.y < dropmenuobj.contentmeasure){ //move up?
edgeoffset=dropmenuobj.contentmeasure+obj.offsetHeight
if ((dropmenuobj.y-topedge)<dropmenuobj.contentmeasure) //up no good either?
edgeoffset=dropmenuobj.y+obj.offsetHeight-topedge
}
}
return edgeoffset
}

function populatemenu(what){
if (ie4||ns6)
dropmenuobj.innerHTML=what.join("")
}


function dropdownmenu(obj, e, menucontents, menuwidth){
if (window.event) event.cancelBubble=true
else if (e.stopPropagation) e.stopPropagation()
clearhidemenu()
dropmenuobj=document.getElementById? document.getElementById("popitmenu") : popitmenu
populatemenu(menucontents)

if (ie4||ns6){
showhide(dropmenuobj.style, e, "visible", "hidden", menuwidth)
dropmenuobj.x=getposOffset(obj, "left")
dropmenuobj.y=getposOffset(obj, "top")
dropmenuobj.style.left=dropmenuobj.x-clearbrowseredge(obj, "rightedge")+"px"
dropmenuobj.style.top=dropmenuobj.y-clearbrowseredge(obj, "bottomedge")+obj.offsetHeight+"px"
}

return clickreturnvalue()
}

function clickreturnvalue(){
if (ie4||ns6) return false
else return true
}

function contains_ns6(a, b) {
while (b.parentNode)
if ((b = b.parentNode) == a)
return true;
return false;
}

function dynamichide(e){
if (ie4&&!dropmenuobj.contains(e.toElement))
delayhidemenu()
else if (ns6&&e.currentTarget!= e.relatedTarget&& !contains_ns6(e.currentTarget, e.relatedTarget))
delayhidemenu()
}

function hidemenu(e){
if (typeof dropmenuobj!="undefined"){
if (ie4||ns6)
dropmenuobj.style.visibility="hidden"
}
}

function delayhidemenu(){
if (ie4||ns6)
delayhide=setTimeout("hidemenu()",disappeardelay)
}

function clearhidemenu(){
if (typeof delayhide!="undefined")
clearTimeout(delayhide)
}

if (hidemenu_onclick=="yes")
document.onclick=hidemenu