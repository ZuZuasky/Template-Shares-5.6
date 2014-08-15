<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  class tsquickbbcodeeditor
  {
    var $ScriptVersion = '0.2 by xam';
    var $FormName = null;
    var $TextAreaName = 'message';
    var $SmilieDivName = 'show_smilies';
    var $ColorsDivName = 'show_colors';
    var $MaxSmilies = '66';
    var $SmiliesPerTr = '3';
    var $SmiliePath = null;
    var $ImagePath = null;
    function tsquickbbcodeeditor ()
    {
    }

    function generatesmilies ()
    {
      global $rootpath;
      global $cache;
      global $smilies;
      require_once $rootpath . '/' . $cache . '/smilies.php';
      $count = 0;
      $showsmilies = '<tr>';
      foreach ($smilies as $code => $name)
      {
        if ($count < $this->MaxSmilies)
        {
          if ($count % $this->SmiliesPerTr == 0)
          {
            $showsmilies .= '</tr><tr>';
          }

          $showsmilies .= '
				<td style="padding: 2px;" class="none" align="center">
					<a href="#" onclick="TSInsert(\'' . str_replace ('\'', '\\\'', $code) . '\', \'\', \'' . $this->FormName . '\', \'' . $this->TextAreaName . '\'); TSShow_panel(\'' . $this->SmilieDivName . '\'); return false;"><img src="' . $this->SmiliePath . $name . '" style="border: medium none;" alt="" title="" border="0" /></a>
				</td>';
        }

        ++$count;
      }

      $showsmilies = '
		<div id="' . $this->SmilieDivName . '" style="border: 1px solid rgb(187, 187, 187); background: rgb(233, 232, 242) none repeat scroll 0% 0%; overflow: auto; display: none; position: absolute; width: 140px; height: 144px;">
			<table width="120" border="0" cellpadding="0" cellspacing="0">
				<tbody>
					' . $showsmilies . '
				</tbody>
			</table>
		</div>
		';
      unset ($count);
      unset ($code);
      unset ($name);
      unset ($smilies);
      return $showsmilies;
    }

    function generatejavascript ()
    {
      global $lang;
      return '
		<script type="text/javascript">
			function TSInsert(aTag,eTag,TSformname,TStextareaname)
			{
				var input=document.forms[TSformname].elements[TStextareaname];
				input.focus();
				if(typeof document.selection != \'undefined\')
				{
					var range=document.selection.createRange();
					var insText=range.text;
					range.text=aTag+insText+eTag;
					range=document.selection.createRange();
					if(insText.length==0)
					{
						range.move(\'character\',aTag.length+insText.length+eTag.length);
					}
					else
					{
						range.moveStart(\'character\',aTag.length+insText.length+eTag.length);
					}
					range.select();
				}
				else if(typeof input.selectionStart!=\'undefined\')
				{
					var start=input.selectionStart;
					var end=input.selectionEnd;
					var insText=input.value.substring(start,end);
					input.value=input.value.substr(0,start)+aTag+insText+eTag+input.value.substr(end);
					var pos;
					if(insText.length==0)
					{
						pos=start+aTag.length+insText.length+eTag.length;
					}
					else
					{
						pos=start+aTag.length+insText.length+eTag.length;
					}
					input.selectionStart=pos;
					input.selectionEnd=pos;
				}
				else
				{
					var pos;
					var re=new RegExp(\'^[0-9]{0,3}$\');
					while(!re.test(pos))
					{
						pos=prompt("Insert at position (0.."+input.value.length+"):","0");
					}
					if(pos>input.value.length)
					{
						pos=input.value.length;
					}
					var insText=prompt("Please you enter the text which can be formatted:");
					input.value=input.value.substr(0,pos)+aTag+insText+eTag+input.value.substr(pos);
				}
			}

			function setColor(Color)
			{
				TSInsert(\'[color=\'+Color+\']\', \'[/color]\', \'' . $this->FormName . '\', \'' . $this->TextAreaName . '\');
				TSShow_panel(\'' . $this->ColorsDivName . '\');
			}

			function TSShow_panel(PanelID)
			{
				if (document.getElementById(PanelID).style.display == "none")
				{		
					document.getElementById(PanelID).style.display = "inline";
				}
				else
				{
					document.getElementById(PanelID).style.display = "none";
				}
			}

			function TSEnterURL(PanelID)
			{
				var URL = prompt(\'' . $lang->quick_editor['enterlink'] . '\', \'http://\');		
				if (!URL || URL == \'\' || URL == \'http://\')
				{
					return false;
				}
				var URLDescription = prompt(\'' . $lang->quick_editor['enterlink2'] . '\', \'\');
				if (URLDescription)
				{
					TSInsert(\'[URL=\'+URL+\']\'+URLDescription+\'[/URL]\', \'\', \'' . $this->FormName . '\', PanelID);
				}
				else
				{
					TSInsert(\'[URL]\'+URL+\'[/URL]\', \'\', \'' . $this->FormName . '\', PanelID);
				}
			}

			function TSInsertImage(PanelID)
			{
				var IMAGE = prompt(\'' . $lang->quick_editor['enterimage'] . '\', \'http://\');
				if (!IMAGE || IMAGE == \'\' || IMAGE == \'http://\')
				{
					return false;
				}
				TSInsert(\'[IMG]\'+IMAGE+\'[/IMG]\', \'\', \'' . $this->FormName . '\', PanelID);
			}

			function TSEnterEmail(PanelID)
			{
				var EMAIL = prompt(\'' . $lang->quick_editor['enteremail'] . '\', \'\');		
				if (!EMAIL || EMAIL == \'\')
				{
					return false;
				}
				var EMAILDescription = prompt(\'' . $lang->quick_editor['enteremail2'] . '\', \'\');
				if (EMAILDescription)
				{
					TSInsert(\'[EMAIL=\'+EMAIL+\']\'+EMAILDescription+\'[/EMAIL]\', \'\', \'' . $this->FormName . '\', PanelID);
				}
				else
				{
					TSInsert(\'[EMAIL]\'+EMAIL+\'[/EMAIL]\', \'\', \'' . $this->FormName . '\', PanelID);
				}
			}

			function TSIncreaseTextarea(TextareaName)
			{
				CurrentHeight = document.getElementById(TextareaName).style.height;
				CurrentHeight = parseInt(CurrentHeight.replace(/px/i, ""));
				NewHeight = CurrentHeight+100+"px";
				document.getElementById(TextareaName).style.height = NewHeight;
			}

			function TSDecreaseTextarea(TextareaName)
			{
				CurrentHeight = document.getElementById(TextareaName).style.height;
				CurrentHeight = parseInt(CurrentHeight.replace(/px/i, ""));
				NewHeight = CurrentHeight-100+"px";
				document.getElementById(TextareaName).style.height = NewHeight;
			}
		</script>
		';
    }

    function generatecss ()
    {
      return '
		<style type="text/css">
			.borderit img
			{
				border: 1px solid #ccc;
				filter:progid:DXImageTransform.Microsoft.Alpha(opacity=40);
				-moz-opacity: 0.6;
			}
			.borderit:hover img
			{
				border: 1px solid navy;
				filter:progid:DXImageTransform.Microsoft.Alpha(opacity=100);
				-moz-opacity: 1;
			}
			.borderit:hover
			{
				color: red;
			}
		</style>
		';
    }

    function generatebbcode ()
    {
      global $lang;
      return '
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td colspan="5" class="none">
					<div style="float: right;">
						<div class="borderit">
							<a href="#" onclick="TSDecreaseTextarea(\'' . $this->TextAreaName . '\'); return false;"><img src="' . $this->ImagePath . 'codebuttons/resize_0.gif" alt="' . $lang->quick_editor['dsize'] . '" title="' . $lang->quick_editor['dsize'] . '" /></a>
						</div>
						<div class="borderit">
							<a href="#" onclick="TSIncreaseTextarea(\'' . $this->TextAreaName . '\'); return false;"><img src="' . $this->ImagePath . 'codebuttons/resize_1.gif" alt="' . $lang->quick_editor['isize'] . '" title="' . $lang->quick_editor['isize'] . '" /></a>
						</div>
					</div>
					<table border="0" cellpadding="1" cellspacing="0">
						<tr>
							<td class="none">
								<a href="#" onclick="TSInsert(\'[b]\',\'[/b]\',\'' . $this->FormName . '\',\'' . $this->TextAreaName . '\'); return false;" class="borderit"><img src="' . $this->ImagePath . 'codebuttons/bold.gif" alt="' . $lang->quick_editor['bold'] . '" title="' . $lang->quick_editor['bold'] . '" /></a>
							</td>
							<td class="none">
								<a href="#" onclick="TSInsert(\'[i]\',\'[/i]\',\'' . $this->FormName . '\',\'' . $this->TextAreaName . '\'); return false;" class="borderit"><img src="' . $this->ImagePath . 'codebuttons/italic.gif" alt="' . $lang->quick_editor['italic'] . '" title="' . $lang->quick_editor['italic'] . '" /></a>
							</td>
							<td class="none">
								<a href="#" onclick="TSInsert(\'[u]\',\'[/u]\',\'' . $this->FormName . '\',\'' . $this->TextAreaName . '\'); return false;" class="borderit"><img src="' . $this->ImagePath . 'codebuttons/underline.gif" alt="' . $lang->quick_editor['underline'] . '" title="' . $lang->quick_editor['underline'] . '" /></a>
							</td>
							<td class="none">
								<img src="' . $this->ImagePath . 'codebuttons/sep.gif" alt="" title="" />
							</td>
							<td class="none">
								<a href="#" onclick="TSInsert(\'[align=left]\',\'[/align]\',\'' . $this->FormName . '\',\'' . $this->TextAreaName . '\'); return false;" class="borderit"><img src="' . $this->ImagePath . 'codebuttons/align_left.gif" alt="' . $lang->quick_editor['a1'] . '" title="' . $lang->quick_editor['a1'] . '" /></a>
							</td>
							<td class="none">
								<a href="#" onclick="TSInsert(\'[center]\',\'[/center]\',\'' . $this->FormName . '\',\'' . $this->TextAreaName . '\'); return false;" class="borderit"><img src="' . $this->ImagePath . 'codebuttons/align_center.gif" alt="' . $lang->quick_editor['a2'] . 'r" title="' . $lang->quick_editor['a2'] . '" /></a>
							</td>
							<td class="none">
								<a href="#" onclick="TSInsert(\'[align=right]\',\'[/align]\',\'' . $this->FormName . '\',\'' . $this->TextAreaName . '\'); return false;" class="borderit"><img src="' . $this->ImagePath . 'codebuttons/align_right.gif" alt="' . $lang->quick_editor['a3'] . '" title="' . $lang->quick_editor['a3'] . '" /></a>
							</td>
							<td class="none">
								<a href="#" onclick="TSInsert(\'[align=justify]\',\'[/align]\',\'' . $this->FormName . '\',\'' . $this->TextAreaName . '\'); return false;" class="borderit"><img src="' . $this->ImagePath . 'codebuttons/align_justify.gif" alt="' . $lang->quick_editor['a4'] . '" title="' . $lang->quick_editor['a4'] . '" /></a>
							</td>
							<td class="none">
								<img src="' . $this->ImagePath . 'codebuttons/sep.gif" alt="" title="" />
							</td>
							<td class="none">
								<a href="#" onclick="TSInsert(\'[quote]\',\'[/quote]\',\'' . $this->FormName . '\',\'' . $this->TextAreaName . '\'); return false;" class="borderit"><img src="' . $this->ImagePath . 'codebuttons/quote.gif" alt="' . $lang->quick_editor['quote'] . '" title="' . $lang->quick_editor['quote'] . '" /></a>
							</td>
							<td class="none">
								<a href="#" onclick="TSInsert(\'[code]\',\'[/code]\',\'' . $this->FormName . '\',\'' . $this->TextAreaName . '\'); return false;" class="borderit"><img src="' . $this->ImagePath . 'codebuttons/code.gif" alt="' . $lang->quick_editor['code'] . '" title="' . $lang->quick_editor['code'] . '" /></a>
							</td>
							<td class="none">
								<a href="#" onclick="TSInsert(\'[php]\',\'[/php]\',\'' . $this->FormName . '\',\'' . $this->TextAreaName . '\'); return false;" class="borderit"><img src="' . $this->ImagePath . 'codebuttons/php.gif" alt="' . $lang->quick_editor['php'] . '" title="' . $lang->quick_editor['php'] . '" /></a>
							</td>
							<td class="none">
								<a href="#" onclick="TSInsert(\'[sql]\',\'[/sql]\',\'' . $this->FormName . '\',\'' . $this->TextAreaName . '\'); return false;" class="borderit"><img src="' . $this->ImagePath . 'codebuttons/sql.gif" alt="' . $lang->quick_editor['sql'] . '" title="' . $lang->quick_editor['sql'] . '" /></a>
							</td>
							<td class="none">
								<img src="' . $this->ImagePath . 'codebuttons/sep.gif" alt="" title="" />
							</td>
							<td class="none">
								<div>
									<a href="#" onclick="TSShow_panel(\'' . $this->ColorsDivName . '\'); return false;" class="borderit"><img src="' . $this->ImagePath . 'codebuttons/color.gif" alt="' . $lang->quick_editor['colors'] . '" title="' . $lang->quick_editor['colors'] . '" /></a>
								</div>
								<iframe width="154" height="104" id="' . $this->ColorsDivName . '" name="' . $this->ColorsDivName . '" src="' . $this->ImagePath . 'codebuttons/color.html" frameborder="0" vspace="0" hspace="0" marginwidth="0" marginheight="0" scrolling="no" style="display: none; position:	absolute;"></iframe>
							</td>
							<td class="none">
								<img src="' . $this->ImagePath . 'codebuttons/sep.gif" alt="" title="" />
							</td>
							<td class="none">
								<a href="#" onclick="TSEnterURL(\'' . $this->TextAreaName . '\'); return false;" class="borderit"><img src="' . $this->ImagePath . 'codebuttons/link.gif" alt="' . $lang->quick_editor['link'] . '" title="' . $lang->quick_editor['link'] . '" /></a>
							</td>
							<td class="none">
								<a href="#" onclick="TSInsertImage(\'' . $this->TextAreaName . '\'); return false;" class="borderit"><img src="' . $this->ImagePath . 'codebuttons/image.gif" alt="' . $lang->quick_editor['image'] . '" title="' . $lang->quick_editor['image'] . '" /></a>
							</td>
							<td class="none">
								<a href="#" onclick="TSEnterEmail(\'' . $this->TextAreaName . '\'); return false;" class="borderit"><img src="' . $this->ImagePath . 'codebuttons/email.gif" alt="' . $lang->quick_editor['email'] . '" title="' . $lang->quick_editor['email'] . '" /></a>
							</td>
							<td class="none">
								<img src="' . $this->ImagePath . 'codebuttons/sep.gif" alt="" title="" />
							</td>
							<td class="none">
								<div>
									<a href="#" onclick="TSShow_panel(\'' . $this->SmilieDivName . '\'); return false;" class="borderit"><img src="' . $this->ImagePath . 'codebuttons/smilies.gif" alt="' . $lang->quick_editor['smilies'] . '" title="' . $lang->quick_editor['smilies'] . '" /></a>
								</div>
								' . $this->GenerateSmilies () . '
							</td>							
							' . (preg_match ('@tsf_forums@Ui', $_SERVER['SCRIPT_NAME']) ? '
							<td class="none">
								<img src="' . $this->ImagePath . 'codebuttons/sep.gif" alt="" title="" />
							</td>
							<td class="none">
								<a href="#" onclick="TSInsert(\'[hide]\',\'[/hide]\',\'' . $this->FormName . '\',\'' . $this->TextAreaName . '\'); return false;" class="borderit"><img src="' . $this->ImagePath . 'codebuttons/hide.gif" alt="' . $lang->quick_editor['hide'] . '" title="' . $lang->quick_editor['hide'] . '" /></a>
							</td>
							' : '') . '
						</tr>
					</table>
				</td>	
			</tr>	
		</table>
		';
    }
  }

?>
