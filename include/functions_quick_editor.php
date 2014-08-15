<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function ts_show_bbcode_links ($TSformname = 'quickreply', $TStextareaname = 'message')
  {
    global $BASEURL;
    global $pic_base_url;
    global $lang;
    $_links_ = '
	<a href="javascript:insert(\'[b]\', \'[/b]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\');"><img src="' . $BASEURL . '/' . $pic_base_url . 'codebuttons/bold.gif" border="0" alt="' . $lang->quick_editor['bold'] . '" title="' . $lang->quick_editor['bold'] . '" /></a>
	<a href="javascript:insert(\'[i]\', \'[/i]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\');"><img src="' . $BASEURL . '/' . $pic_base_url . 'codebuttons/italic.gif" border="0" alt="' . $lang->quick_editor['italic'] . '" title="' . $lang->quick_editor['italic'] . '" /></a>
	<a href="javascript:insert(\'[u]\', \'[/u]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\');"><img src="' . $BASEURL . '/' . $pic_base_url . 'codebuttons/underline.gif" border="0" alt="' . $lang->quick_editor['underline'] . '" title="' . $lang->quick_editor['underline'] . '" /></a>
	<a href="javascript:insert(\'[url]\', \'[/url]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\');"><img src="' . $BASEURL . '/' . $pic_base_url . 'codebuttons/link.gif" border="0" alt="' . $lang->quick_editor['link'] . '" title="' . $lang->quick_editor['link'] . '" /></a>
	<a href="javascript:insert(\'[img]\', \'[/img]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\');"><img src="' . $BASEURL . '/' . $pic_base_url . 'codebuttons/image.gif" border="0" alt="' . $lang->quick_editor['image'] . '" title="' . $lang->quick_editor['image'] . '" /></a>
	<a href="javascript:insert(\'[swf]\', \'[/swf]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\');"><img src="' . $BASEURL . '/' . $pic_base_url . 'codebuttons/bbcode_flash.gif" border="0" alt="' . $lang->quick_editor['swf'] . '" title="' . $lang->quick_editor['swf'] . '" /></a>
	
<a href="javascript:insert(\'[blink]\', \'[/blink]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\');"><img src="' . $BASEURL . '/' . $pic_base_url . 'codebuttons/bbcode_blink.gif" border="0" alt="' . $lang->quick_editor['blink'] . '" title="' . $lang->quick_editor['blink'] . '" /></a>
         
 <a href="javascript:insert(\'[size=xx-large]\', \'[/size]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\');"><img src="' . $BASEURL . '/' . $pic_base_url . 'codebuttons/xgros.png" border="0" alt="' . $lang->quick_editor['size'] . '" title="' . $lang->quick_editor['size'] . '" /></a>
        <a href="javascript:insert(\'[email]\', \'[/email]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\');"><img src="' . $BASEURL . '/' . $pic_base_url . 'codebuttons/email.gif" border="0" alt="' . $lang->quick_editor['email'] . '" title="' . $lang->quick_editor['email'] . '" /></a>
	<a href="javascript:insert(\'[quote]\', \'[/quote]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\');"><img src="' . $BASEURL . '/' . $pic_base_url . 'codebuttons/quote.gif" border="0" alt="' . $lang->quick_editor['quote'] . '" title="' . $lang->quick_editor['quote'] . '" /></a>
	<a href="javascript:insert(\'[code]\', \'[/code]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\');"><img src="' . $BASEURL . '/' . $pic_base_url . 'codebuttons/code.gif" border="0" alt="' . $lang->quick_editor['code'] . '" title="' . $lang->quick_editor['code'] . '" /></a>';
    return $_links_;
  }




  function ts_show_shoutbox_bbcode_links ($TSformname = 'shoutbox', $TStextareaname = 'shoutbox')
  {
    $colors = array ('black' => '#000000', 'blue' => '#1818A0', 'green' => '#00FF00', 'orange' => '#FF8040', 'pink' => '#FF00FF', 'red' => '#FF0000', 'yellow' => '#FFFF00');
    $_links_ = '
        
	<img src="pic/codebuttons/bold.gif" alt="Insert bold text" title="Insert bold text" onClick="insert(\'[b]\', \'[/b]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\');" />
	<img src="pic/codebuttons/italic.gif" alt="Insert italic text" title="Insert italic text" onClick="insert(\'[i]\', \'[/i]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\');" />
	<img src="pic/codebuttons/underline.gif" alt="Insert underline text" title="Insert underline text" onClick="insert(\'[u]\', \'[/u]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\');" />
        <img src="pic/codebuttons/align_left.gif" alt="gauche" title="gauche" onClick="insert(\'[align=left]\', \'[/align]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\');" />
        <img src="pic/codebuttons/align_center.gif" alt="centrer" title="centrer" onClick="insert(\'[center]\', \'[/center]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\');" />
        <img src="pic/codebuttons/align_right.gif" alt="right" title="right" onClick="insert(\'[align=right]\', \'[/align]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\');" />
        <img src="pic/codebuttons/align_justify.gif" alt="justify" title="justify" onClick="insert(\'[align=justify]\', \'[/align]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\');" />
        <img src="pic/codebuttons/quote.gif" alt="quote" title="quote" onClick="insert(\'[quote]\', \'[/quote]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\');" />
        <img src="pic/codebuttons/bbcode_flash.gif" alt="swf" title="swf" onClick="insert(\'[swf]\', \'[/swf]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\');" />
        <img src="pic/codebuttons/bbcode_audio.gif" alt="audio" title="audio" onClick="insert(\'[audio]\', \'[/audio]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\');" />
<img src="pic/codebuttons/bbcode_blink.gif" alt="blink" title="blink" onClick="insert(\'[blink]\', \'[/blink]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\');" />  
 <img src="pic/codebuttons/youtube.gif" alt="youtube" title="youtube" onClick="insert(\'[youtube]\', \'[/youtube]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\');" />
     <img src="pic/codebuttons/image.gif" alt="image" title="image" onClick="insert(\'[img]\', \'[/img]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\');" />
    <img src="pic/codebuttons/gros.png" alt="size" title="taille" onClick="insert(\'[size=x-large]\', \'[/size]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\');" />
     <img src="pic/codebuttons/xgros.png" alt="size" title="taille" onClick="insert(\'[size=xx-large]\', \'[/size]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\');" />
        
        

	';
    return $_links_;
  }

  function ts_load_colors_shoutbox ($TSformname = 'shoutbox', $TStextareaname = 'shoutbox', $colors = array ('black' => '#000000', 'blue' => '#1818A0', 'green' => '#00FF00', 'orange' => '#FF8040', 'pink' => '#FF00FF', 'red' => '#FF0000', 'yellow' => '#FFFF00'))
  {
    global $lang;
    global $BASEURL;
    global $pic_base_url;
    $showcolors = '
	<div style="display: none; margin-right: 13px; margin-bottom: 3px;" id="show_TScolors">
		<table width="100%" border="0" cellpadding="3" cellspacing="0">
			<tr>
				<td class="none" align="right">';
    foreach ($colors as $colorname => $colorcode)
    {
      $showcolors .= '' . '<img src="' . $BASEURL . '/' . $pic_base_url . 'codebuttons/' . $colorname . '.gif" class="Shighlightit" onClick="insert(\'[color=' . $colorcode . ']\', \'[/color]\', \'' . $TSformname . '\', \'' . $TStextareaname . '\')" />';
    }

    $showcolors .= '
				</td>
			</tr>
		</table>
	</div>';
    return $showcolors;
  }

  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('Q_EDITOR', 'v0.6 by xam');
?>
