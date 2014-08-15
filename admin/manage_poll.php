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

  define ('MP_VERSION', '0.1 by xam');
  $lang->load ('poll');
  $action = (isset ($_GET['action']) ? $_GET['action'] : (isset ($_POST['action']) ? $_POST['action'] : 'showlist'));
  if (($action == 'updatepoll' AND is_valid_id ($_POST['pollid'])))
  {
    $_queries = array ();
    $pollid = intval ($_POST['pollid']);
    ($Query = sql_query ('SELECT * FROM ' . TSF_PREFIX . 'poll WHERE pollid = \'' . $pollid . '\' AND fortracker = \'1\'') OR sqlerr (__FILE__, 28));
    $pollinfo = mysql_fetch_assoc ($Query);
    if (!$pollinfo['pollid'])
    {
      stderr ($lang->global['error'], $lang->poll['invalid']);
    }

    if (strlen (trim ($_POST['pollquestion'])) < 2)
    {
      stderr ($lang->global['error'], $lang->poll['error']);
    }
    else
    {
      $_queries[] = 'question = ' . sqlesc (htmlspecialchars_uni ($_POST['pollquestion']));
    }

    $numberoptions = 0;
    $optionarray = array ();
    foreach ($_POST['options'] as $left => $right)
    {
      if ($right != '')
      {
        ++$numberoptions;
        $optionarray[] = htmlspecialchars_uni ($right);
        continue;
      }
    }

    if ($numberoptions < 2)
    {
      stderr ($lang->global['error'], $lang->poll['error']);
    }
    else
    {
      $_queries[] = 'options = ' . sqlesc (implode ('~~~', $optionarray));
    }

    $votecount = 0;
    $votearray = array ();
    foreach ($_POST['pollvotes'] as $left => $right)
    {
      ++$votecount;
      $votearray[] = 0 + $right;
    }

    $savevotearray = array ();
    $voters = 0;
    $i = 0;
    while ($i < $numberoptions)
    {
      if ($votearray[$i])
      {
        $voters += $votearray[$i];
        $savevotearray[] = $votearray[$i];
      }
      else
      {
        $savevotearray[] = '0';
      }

      ++$i;
    }

    $_queries[] = 'votes = ' . sqlesc (implode ('~~~', $savevotearray));
    $_queries[] = 'active = \'' . ($_POST['closepoll'] == 'yes' ? '0' : '1') . '\'';
    $_queries[] = 'numberoptions = \'' . intval ($numberoptions) . '\'';
    $_queries[] = 'timeout = \'' . intval ($_POST['timeout']) . '\'';
    $_queries[] = 'voters = \'' . intval ($voters) . '\'';
    $_queries[] = 'public = \'' . ($_POST['public'] == '1' ? '1' : '0') . '\'';
    (sql_query ('UPDATE ' . TSF_PREFIX . 'poll SET ' . implode (',', $_queries) . ' WHERE pollid = \'' . $pollid . '\' AND fortracker = \'1\'') OR sqlerr (__FILE__, 94));
    $action = 'showlist';
  }

  if (($action == 'polledit' AND is_valid_id ($_GET['pollid'])))
  {
    $pollid = intval ($_GET['pollid']);
    ($Query = sql_query ('SELECT * FROM ' . TSF_PREFIX . 'poll WHERE pollid = \'' . $pollid . '\' AND fortracker = \'1\'') OR sqlerr (__FILE__, 101));
    $pollinfo = mysql_fetch_assoc ($Query);
    if (!$pollinfo['pollid'])
    {
      stderr ($lang->global['error'], $lang->poll['invalid']);
    }

    if (10 < $pollinfo['numberoptions'])
    {
      $pollinfo['numberoptions'] = 10;
    }

    if (!$pollinfo['active'])
    {
      $pollinfo['closed'] = 'checked="checked"';
    }

    if ($pollinfo['public'])
    {
      $show['makeprivate'] = true;
      $pollinfo['public'] = 'checked="checked"';
    }

    $pollinfo['postdate'] = my_datee ($dateformat, $pollinfo['dateline']);
    $pollinfo['posttime'] = my_datee ($timeformat, $pollinfo['dateline']);
    $splitoptions = explode ('~~~', $pollinfo['options']);
    $splitvotes = explode ('~~~', $pollinfo['votes']);
    $counter = 0;
    while ($counter++ < $pollinfo['numberoptions'])
    {
      $pollinfo['numbervotes'] += $splitvotes[$counter - 1];
    }

    $counter = 0;
    $pollbits = '';
    $pollinfo['question'] = htmlspecialchars_uni ($pollinfo['question']);
    while ($counter++ < $pollinfo['numberoptions'])
    {
      $option['question'] = htmlspecialchars_uni ($splitoptions[$counter - 1]);
      $option['votes'] = $splitvotes[$counter - 1];
      $option['number'] = $counter;
      $pollbits .= '
		<tr>
			<td class="none"><label for="opt' . $option['number'] . '">' . sprintf ($lang->poll['option'], $option['number']) . ':<br /><input type="text" class="bginput"  name="options[' . $option['number'] . ']" id="opt' . $option['number'] . '" value="' . $option['question'] . '" size="50" /></label></td>
			<td class="none"><label for="vot' . $option['number'] . '">' . $lang->poll['votes'] . ':<br /><input type="text" class="bginput" name="pollvotes[' . $option['number'] . ']" id="vot' . $option['number'] . '" value="' . $option['votes'] . '" size="5" /></label></td>
		</tr>';
    }

    if (0 < 10)
    {
      $show['additional_option1'] = $pollinfo['numberoptions'] < 10;
      $show['additional_option2'] = $pollinfo['numberoptions'] < 10 - 1;
    }
    else
    {
      $show['additional_option1'] = true;
      $show['additional_option2'] = true;
    }

    $poll = '
	<form action="' . $_this_script_ . '&amp;action=updatepoll&amp;pollid=' . $pollid . '" method="post">
	<input type="hidden" name="action" value="updatepoll" />
	<input type="hidden" name="pollid" value="' . $pollid . '" />

	<table cellpadding="5" cellspacing="0" border="0" width="100%" align="center">
	<tr>
	<td class="thead">	
	' . $lang->poll['editpoll'] . '
	</td>
	</tr>
	<tr>
	<td class="panelsurround" align="center">
	<div class="panel">
	<div align="left">	

	<fieldset class="fieldset">
	<legend>' . $lang->poll['question'] . '</legend>
	<table cellpadding="3" cellspacing="0" border="0">
	<tr>
		<td class="none" colspan="2">
			<input type="text" class="bginput" name="pollquestion" value="' . $pollinfo['question'] . '" id="pollquestion" size="50" maxlength="185" />
		</td>
	</tr>
	</table>
	</fieldset>

	<fieldset class="fieldset">
	<legend>' . $lang->poll['options'] . '</legend>
	<table cellpadding="3" cellspacing="0" border="0">
	' . $pollbits . '

	' . ($show['additional_option1'] ? '
	<tr>
	<td colspan="2" class="none"><label for="add1">' . $lang->poll['ao1'] . ':<br /><input type="text" class="bginput" name="options[]" id="add1" size="50" /></label></td>
	</tr>
	' : '') . ($show['additional_option2'] ? '<tr>
	<td colspan="2" class="none"><label for="add2">' . $lang->poll['ao2'] . ':<br /><input type="text" class="bginput" name="options[]" id="add2" size="50" /></label></td>
	</tr>
	' : '') . '
	<tr>
	<td colspan="2" class="none">' . $lang->poll['sort'] . '</td>
	</tr>
	</table>
	</fieldset>

	<fieldset class="fieldset">
	<legend>' . $lang->poll['timeout'] . '</legend>
	<table cellpadding="3" cellspacing="0" border="0" width="100%">
	<tr>
	<td class="none">' . $lang->poll['timeout2'] . '</td>
	</tr>
	<tr>
	<td class="none"><label for="poll_timeout">' . $lang->poll['close'] . ' <input type="text" class="bginput" name="timeout" value="' . $pollinfo['timeout'] . '" size="5" id="poll_timeout" /> ' . sprintf ($lang->poll['after'], $pollinfo['postdate']) . '</label></td>
	</tr>
	</table>
	</fieldset>

	' . ($show['makeprivate'] ? '
	<fieldset class="fieldset">
	<legend>' . $lang->poll['options'] . '</legend>
	<table cellpadding="3" cellspacing="0" border="0" width="100%">
	<tr>
	<td class="none"><label for="cb_public"><input type="checkbox" name="public" value="1" id="cb_public" tabindex="1" ' . $pollinfo['public'] . ' />' . $lang->poll['public'] . '</label></td>
	</tr>
	</table>
	</fieldset>
	' : '') . '

	<fieldset class="fieldset">
	<legend>' . $lang->poll['close'] . '</legend>
	<table cellpadding="3" cellspacing="0" border="0" width="100%">
	<tr>
	<td class="none"><label for="cb_closepoll"><input type="checkbox" name="closepoll" value="yes" id="cb_closepoll" ' . $pollinfo['closed'] . ' />' . $lang->poll['close2'] . '</label></td>
	</tr>
	<tr>
	<td class="none">' . $lang->poll['closenote'] . '</td>
	</tr>
	</table>
	</fieldset>

	</div>
	</div>

	<div style="margin-top:5px">
	<input type="submit" class="button" name="sbutton" accesskey="s" value="' . $lang->poll['save'] . '" />
	<input type="reset" class="button" value="' . $lang->poll['reset'] . '" />
	</div>

	</td>
	</tr>
	</table>

	</form>';
    stdhead ();
    echo $poll;
    stdfoot ();
    exit ();
  }

  if ($action == 'createpoll')
  {
    if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
    {
      if (strlen (trim ($_POST['pollquestion'])) < 2)
      {
        stderr ($lang->global['error'], $lang->poll['error']);
      }

      $question = htmlspecialchars_uni ($_POST['pollquestion']);
      $numberoptions = 0;
      $optionarray = array ();
      foreach ($_POST['options'] as $left => $right)
      {
        if ($right != '')
        {
          ++$numberoptions;
          $optionarray[] = htmlspecialchars_uni ($right);
          continue;
        }
      }

      if ($numberoptions < 2)
      {
        stderr ($lang->global['error'], $lang->poll['error']);
      }

      $votearray = array ();
      $i = 0;
      while ($i < $numberoptions)
      {
        $votearray[] = '0';
        ++$i;
      }

      (sql_query ('INSERT INTO ' . TSF_PREFIX . 'poll VALUES (NULL, ' . sqlesc ($question) . ', \'' . TIMENOW . '\', ' . sqlesc (implode ('~~~', $optionarray)) . ', ' . sqlesc (implode ('~~~', $votearray)) . ', \'1\', \'' . intval ($numberoptions) . '\', \'' . intval ($_POST['timeout']) . '\', \'' . intval ($_POST['multiple']) . '\', \'0\', \'' . intval ($_POST['public']) . '\', \'0\', \'1\')') OR sqlerr (__FILE__, 300));
      $action = 'showlist';
    }
    else
    {
      $i = 0;
      $options = '';
      while ($i++ < 10)
      {
        $options .= '<tr><td class="none"><label for="opt' . $i . '">' . sprintf ($lang->poll['option'], $i) . ':<br><input class="bginput" name="options[' . $i . ']" id="opt' . $i . '" value="" size="50" type="text"></label></td></tr>';
      }

      stdhead ($lang->poll['createpoll']);
      echo '
		<form action="' . $_this_script_ . '&amp;action=createpoll" method="post">
		<input name="action" value="createpoll" type="hidden" />

		<table width="100%" align="center" border="0" cellpadding="5" cellspacing="0">
		<tbody><tr>
		<td class="thead">	
		' . $lang->poll['createpoll'] . '
		</td>
		</tr>
		<tr>
		<td class="panelsurround" align="center">
		<div class="panel">
		<div align="left">	

		<fieldset class="fieldset">

		<legend>' . $lang->poll['question'] . '</legend>
		<table border="0" cellpadding="3" cellspacing="0">
		<tbody><tr>
		<td class="none" colspan="2">
		<input class="bginput" name="pollquestion" value="" id="pollquestion" size="50" maxlength="185" type="text" />
		</td>
		</tr>
		</tbody></table>

		</fieldset>

		<fieldset class="fieldset">
		<legend>' . $lang->poll['options'] . '</legend>
		<table border="0" cellpadding="3" cellspacing="0">

		<tbody>
		' . $options . '
		<tr>
		<td colspan="2" class="none">' . $lang->poll['sort'] . '</td>
		</tr>
		</tbody></table>

		</fieldset>

		<fieldset class="fieldset">
		<legend>' . $lang->poll['timeout'] . '</legend>
		<table width="100%" border="0" cellpadding="3" cellspacing="0">
		<tbody><tr>
		<td class="none">' . $lang->poll['timeout2'] . '</td>
		</tr>

		<tr>
		<td class="none"><label for="poll_timeout">' . $lang->poll['close'] . ' <input class="bginput" name="timeout" value="0" size="5" id="poll_timeout" type="text"> ' . sprintf ($lang->poll['after'], my_datee ($dateformat, time ())) . '</label></td>
		</tr>
		</tbody></table>
		</fieldset>

		<fieldset class="fieldset">
		<legend>' . $lang->poll['multiple'] . '</legend>

		<table width="100%" border="0" cellpadding="3" cellspacing="0">
		<tbody><tr>
		<td class="none"><label for="cb_multiple"><input type="checkbox" name="multiple" value="1" id="cb_multiple" tabindex="1" />' . $lang->poll['multiple2'] . '</label></td>
		</tr>
		</tbody></table>
		</fieldset>

		<fieldset class="fieldset">
		<legend>' . $lang->poll['votes'] . '</legend>

		<table width="100%" border="0" cellpadding="3" cellspacing="0">
		<tbody><tr>
		<td class="none"><label for="cb_public"><input name="public" value="1" id="cb_public" tabindex="1" type="checkbox">' . $lang->poll['public'] . '</label></td>
		</tr>
		</tbody></table>
		</fieldset>		

		</div>
		</div>

		<div style="margin-top: 5px;">
		<input class="button" name="sbutton" accesskey="s" value="' . $lang->poll['save'] . '" type="submit">
		<input class="button" value="' . $lang->poll['reset'] . '" type="reset">
		</div>

		</td>
		</tr>
		</tbody></table>

		</form>
		';
      stdfoot ();
      exit ();
    }
  }

  if (($action == 'deletepoll' AND is_valid_id ($_GET['pollid'])))
  {
    $pollid = intval ($_GET['pollid']);
    ($Query = sql_query ('SELECT pollid FROM ' . TSF_PREFIX . 'poll WHERE pollid = \'' . $pollid . '\' AND fortracker = \'1\'') OR sqlerr (__FILE__, 408));
    $pollinfo = mysql_fetch_assoc ($Query);
    if (!$pollinfo['pollid'])
    {
      stderr ($lang->global['error'], $lang->poll['invalid']);
    }

    (sql_query ('DELETE FROM ' . TSF_PREFIX . 'poll WHERE pollid = \'' . $pollid . '\' AND fortracker = \'1\'') OR sqlerr (__FILE__, 415));
    (sql_query ('DELETE FROM ' . TSF_PREFIX . 'pollvote WHERE pollid = \'' . $pollid . '\'') OR sqlerr (__FILE__, 416));
    $action = 'showlist';
  }

  if ($action == 'showlist')
  {
    stdhead ($lang->poll['polls']);
    echo '
	<script type="text/javascript">
		function MakeSure()
		{
			var answer = confirm("' . $lang->poll['sure'] . '")
			if (answer)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	</script>
	<div style="float: right; padding-bottom: 3px;"><input type="button" value="' . $lang->poll['createpoll'] . '" onclick="jumpto(\'' . $_this_script_ . '&amp;action=createpoll\'); return flase;" /></div>
	';
    _form_header_open_ ($lang->poll['polls'], 5);
    ($Query = sql_query ('SELECT pollid, question, votes, dateline, active FROM ' . TSF_PREFIX . 'poll WHERE fortracker = \'1\' ORDER BY dateline DESC') OR sqlerr (__FILE__, 441));
    echo '
	<tr>
		<td class="subheader" width="55%" align="left">' . $lang->poll['question'] . '</td>
		<td class="subheader" width="15%" align="center">' . $lang->poll['created'] . '</td>
		<td class="subheader" width="5%" align="center">' . $lang->poll['votes'] . '</td>
		<td class="subheader" width="10%" align="center">' . $lang->poll['status'] . '</td>
		<td class="subheader" width="15%" align="center">' . $lang->poll['action'] . '</td>
	</tr>
	';
    if (0 < mysql_num_rows ($Query))
    {
      while ($Poll = mysql_fetch_assoc ($Query))
      {
        echo '
			<tr>
				<td width="55%" align="left">' . htmlspecialchars_uni ($Poll['question']) . '</td>
				<td width="15%" align="center">' . my_datee ($dateformat, $Poll['dateline']) . ' ' . my_datee ($timeformat, $Poll['dateline']) . '</td>
				<td width="5%" align="center">' . array_sum (explode ('~~~', $Poll['votes'])) . '</td>
				<td width="10%" align="center">' . ($Poll['active'] == '0' ? '<font color="red">' . $lang->poll['disable'] : '<font color="green">' . $lang->poll['active']) . '</font></td>
				<td width="15%" align="center"><a href="' . $_this_script_ . '&amp;action=polledit&amp;pollid=' . $Poll['pollid'] . '">' . $lang->poll['editpoll'] . '</a> - <a href="' . $_this_script_ . '&amp;action=deletepoll&amp;pollid=' . $Poll['pollid'] . '" onclick="return MakeSure();">' . $lang->poll['deletepoll'] . '</a></td>
			</tr>
			';
      }
    }

    _form_header_close_ ();
    stdfoot ();
    exit ();
  }

?>
