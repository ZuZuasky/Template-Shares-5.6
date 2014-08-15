<?php
/*
************************************************
*==========[TS Special Edition v.5.6]==========*
************************************************
*              Special Thanks To               *
*        DrNet - wWw.SpecialCoders.CoM         *
*          Vinson - wWw.Decode4u.CoM           *
*    MrDecoder - wWw.Fearless-Releases.CoM     *
*           Fynnon - wWw.BvList.CoM            *
*==============================================*
*   Note: Don't Modify Or Delete This Credit   *
*     Next Target: TS Special Edition v5.7     *
*     TS SE WILL BE ALWAYS FREE SOFTWARE !     *
************************************************
*/
require_once('global.php');  
gzip();
dbconn();  
loggedinorreturn();
maxsysop ();
$lang->load('ff');

stdhead($lang->ff['head']);  
begin_main_frame();  
print("<table border=1 width=100% cellspacing=0 cellpadding=5><tr><td class=colhead align=center><font size=3>".strtoupper($SITENAME)." ".$lang->ff['head']."</font>\n</td></tr>");  
print("<tr><td align=center>");  
?>  
<script language="JavaScript1.2">    
function addEngine(name,ext,cat)  
{  
  if ((typeof window.sidebar == "object") && (typeof  
  window.sidebar.addSearchEngine == "function"))  
  {  
    window.sidebar.addSearchEngine(  
      baseurl + "/misc/"+name+".src",  
      baseurl + "/misc/"+name+"."+ext,  
      name,  
      cat );  
  }  
  else  
  {  
  alert(l_ff);  
  }  
}  
</script>  
  
<?=$lang->ff['info'];?>  
<ul>  
  <li><a class=altlink href="javascript:addEngine('templateshares', 'gif', 'Torrent Search')"><?=sprintf($lang->ff['info2'], $SITENAME);?></a></li>  
  <li><a class=altlink href="javascript:addEngine('templateshares_forums', 'gif', 'Forums Search')"><?=sprintf($lang->ff['info3'], $SITENAME);?></a></li>  
</ul>  
<?  
print($lang->ff['info4']);
print("</td></tr></table>");  
end_frame();    
end_main_frame();  
stdfoot();  
?>
