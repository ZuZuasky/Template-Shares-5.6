<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  $lang->load ('modrules');
  define ('NcodeImageResizer', true);
  if ((strtoupper ($_SERVER['REQUEST_METHOD']) == 'GET' AND $_GET['do'] == 'delete'))
  {
    $id = intval ($_GET['id']);
    (sql_query ('DELETE FROM rules WHERE id = ' . sqlesc ($id)) OR sqlerr (__FILE__, 25));
    unset ($id);
  }

  if ((strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST' AND $_POST['do'] == 'save'))
  {
    $_ugs = '';
    if (0 < count ($_POST['usergroups']))
    {
      foreach ($_POST['usergroups'] as $_ug)
      {
        if (is_valid_id ($_ug))
        {
          $_ugs .= '[' . $_ug . ']';
          continue;
        }
      }
    }
    else
    {
      $_ugs = '[0]';
    }

    $title = trim ($_POST['title']);
    $text = trim ($_POST['text']);
    $id = intval ($_POST['id']);
    (sql_query ('UPDATE rules SET title = ' . sqlesc ($title) . ', text = ' . sqlesc ($text) . ', usergroups = ' . sqlesc ($_ugs) . ' WHERE id = ' . sqlesc ($id)) OR sqlerr (__FILE__, 50));
    unset ($_ugs);
    unset ($_ug);
    unset ($title);
    unset ($text);
    unset ($id);
  }

  if ((strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST' AND $_POST['do'] == 'new'))
  {
    $error = array ();
    $__ugs = '';
    if (0 < count ($_POST['usergroups']))
    {
      foreach ($_POST['usergroups'] as $__ug)
      {
        if (is_valid_id ($__ug))
        {
          $__ugs .= '[' . $__ug . ']';
          continue;
        }
      }
    }
    else
    {
      $__ugs = '[0]';
    }

    $_title = trim ($_POST['title']);
    $_text = trim ($_POST['text']);
    if ((empty ($_title) OR empty ($_text)))
    {
      $error[] = $lang->modrules['error'];
    }
    else
    {
      (sql_query ('INSERT INTO rules (title, text, usergroups) VALUES (' . sqlesc ($_title) . ', ' . sqlesc ($_text) . ', ' . sqlesc ($__ugs) . ')') OR sqlerr (__FILE__, 81));
      unset ($_title);
      unset ($_text);
      unset ($__ugs);
      unset ($__ug);
    }
  }

  $ugarray = array ();
  $query2 = sql_query ('SELECT gid, title, namestyle FROM usergroups WHERE isbanned != \'yes\'');
  while ($gid = mysql_fetch_assoc ($query2))
  {
    $ugarray[] = array ('gid' => $gid['gid'], 'namestyle' => get_user_color ($gid['title'], $gid['namestyle']));
  }

  unset ($gid);
  stdhead ($lang->modrules['title']);
  echo '
<div id="new_rule" style="display: ' . (count ($error) == 0 ? 'none' : 'inline') . ';">
<form method="POST" action="' . $_this_script_ . '&do=new">
<input type="hidden" name="do" value="new" />
<table width="100%" align="center" border="0" cellpadding="5" cellspacing="0">
	<tr>
		<td class="thead" align="center">' . $lang->modrules['new'] . '</td>
	</tr>
	<tr>
		<td>
			<fieldset>
			<legend>' . $lang->modrules['new'] . '</legend>
			' . (0 < count ($error) ? '<font color="red">' . $error[0] . '</font><br /><br />' : '') . '
			' . $lang->modrules['title2'] . '<br />
			<input type="text" name="title" size="99" value="' . ($_title ? htmlspecialchars_uni ($_title) : '') . '" /><br /><br />
			' . $lang->modrules['title3'] . '<br />
			<textarea name="text" rows="6" cols="99">' . ($_text ? htmlspecialchars_uni ($_text) : '') . '</textarea><br /><br />
			' . $lang->modrules['title4'];
  $__sgids = '	
		<table border="0" cellspacing="0" cellpadding="2" width="100%">
			<tr>';
  $_count = 1;
  foreach ($ugarray as $_nothing => $_gids)
  {
    if ($_count % 5 == 1)
    {
      $__sgids .= '</tr></td>';
    }

    $__sgids .= '	
		<td style="border: 0"><input type="checkbox" name="usergroups[]" value="' . $_gids['gid'] . '"' . (($__ugs AND preg_match ('#\\[' . $_gids['gid'] . '\\]#U', $__ugs)) ? ' checked="checked"' : '') . ' /></td>
		<td style="border: 0">' . $_gids['namestyle'] . '</td>';
    ++$_count;
  }

  $__sgids .= '</tr></table>';
  echo $__sgids . '
		<br />
		<input type="submit" value="' . $lang->modrules['save'] . '" />
		</fieldset>
	</td>
</tr>
</table>
</form>
<br />
</div>
';
  echo '
<script type="text/javascript">
	function create_new_rule()
	{
		document.getElementById("new_rule_button").style.display="none";
		document.getElementById("new_rule").style.display="inline";
	}
</script>
<span style="float: right; margin-bottom: 5px;" id="new_rule_button"><input type="button" onclick="create_new_rule(); return false;" value="' . $lang->modrules['new'] . '"></span>';
  _form_header_open_ ($lang->modrules['title']);
  ($query = sql_query ('SELECT * FROM rules ORDER BY id') OR sqlerr (__FILE__, 151));
  if (0 < mysql_num_rows ($query))
  {
    echo '
	<script type="text/javascript">
		function confirm_rule_delete(RuleID)
		{
			if (confirm("' . $lang->modrules['confirm'] . '"))
			{
				window.location = "' . $_this_script_ . '&do=delete&id="+RuleID;
			}
			else
			{
				return false;
			}
		}
		function edit_rule(RuleID)
		{
			if (document.getElementById("inputtitle_"+RuleID).style.display == "none")
			{
				document.getElementById("title_"+RuleID).style.display="none";
				document.getElementById("inputtitle_"+RuleID).style.display="inline";
				document.getElementById("text_"+RuleID).style.display="none";
				document.getElementById("textareaipnut_"+RuleID).style.display="inline";
			}
			else
			{
				document.getElementById("title_"+RuleID).style.display="inline";
				document.getElementById("inputtitle_"+RuleID).style.display="none";
				document.getElementById("text_"+RuleID).style.display="inline";
				document.getElementById("textareaipnut_"+RuleID).style.display="none";
			}
		}
	</script>
	';
    while ($rule = mysql_fetch_assoc ($query))
    {
      $sgids = '
		<fieldset>
		<legend>' . $lang->modrules['title4'] . '</legend>
			<table border="0" cellspacing="0" cellpadding="2" width="100%">
				<tr>';
      $count = 1;
      foreach ($ugarray as $nothing => $gids)
      {
        if ($count % 5 == 1)
        {
          $sgids .= '</tr></td>';
        }

        $sgids .= '	
			<td style="border: 0"><input type="checkbox" name="usergroups[]" value="' . $gids['gid'] . '"' . (preg_match ('#\\[' . $gids['gid'] . '\\]#U', $rule['usergroups']) ? ' checked="checked"' : '') . ' /></td>
			<td style="border: 0">' . $gids['namestyle'] . '</td>';
        ++$count;
      }

      $sgids .= '</tr></table></fieldset>';
      echo '
		<form method="POST" action="' . $_this_script_ . '&do=save&id=' . $rule['id'] . '#title_' . $rule['id'] . '">
		<input type="hidden" name="id" value="' . $rule['id'] . '" />
		<input type="hidden" name="do" value="save" />
			<tr>
				<td class="subheader"><span id="title_' . $rule['id'] . '">' . format_comment ($rule['title']) . '</span> <span style="display: none;" id="inputtitle_' . $rule['id'] . '"><input type="text" name="title" size="119" value="' . htmlspecialchars_uni ($rule['title']) . '" /></span></td>
			</tr>
			<tr>
				<td><span style="float: right;" id="links_' . $rule['id'] . '"><a href="' . $_this_script_ . '&do=edit&id=' . $rule['id'] . '" onclick="edit_rule(' . $rule['id'] . '); return false;">' . $lang->modrules['edit'] . '</a> | <a href="' . $_this_script_ . '&do=delete&id=' . $rule['id'] . '" onclick="confirm_rule_delete(' . $rule['id'] . '); return false;">' . $lang->modrules['delete'] . '</a></span> <span id="text_' . $rule['id'] . '">' . format_comment ($rule['text']) . '</span> <span style="display: none;" id="textareaipnut_' . $rule['id'] . '"><textarea name="text" rows="6" cols="99">' . htmlspecialchars_uni ($rule['text']) . '</textarea><br />' . $sgids . '<br /><input type="submit" value="' . $lang->modrules['save'] . '" /> <input type="reset" value="' . $lang->modrules['reset'] . '" /></span></td>
			</tr>
		</form>
		';
      unset ($nothing);
      unset ($sgids);
      unset ($gids);
      unset ($count);
    }
  }

  _form_header_close_ ();
  stdfoot ();
?>
