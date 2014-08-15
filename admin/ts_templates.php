<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  if (!defined ('SETTING_PANEL_TSSEv56'))
  {
    exit ('Direct initialization of this file is not allowed. Please use settings panel.');
  }

  define ('TS_T_VERSION', 'v0.2 by xam');
  if ($do == 'ts_templates_new')
  {
    if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
    {
      $name = $_POST['name'];
      $title = $_POST['title'];
      $template = $_POST['template'];
      if (((!empty ($name) AND !empty ($title)) AND !empty ($template)))
      {
        sql_query ('INSERT INTO ts_templates (name,title,template,template_orj) VALUES (' . sqlesc ($name) . ',' . sqlesc ($title) . ',' . sqlesc ($template) . ',' . sqlesc ($template) . ')');
        $_message = 'New Template (' . mysql_insert_id () . ') has been added!';
      }
    }

    if (!isset ($_message))
    {
      $where = array ('Cancel' => $_SERVER['SCRIPT_NAME'] . '?do=ts_templates');
      echo jumpbutton ($where);
      _form_header_open_ ('Manage Templates - New', 2);
      echo '
		<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
		<input type="hidden" name="do" value="ts_templates_new">		
		<tr>
			<td align="right" valign="top"><b>Template Name:<b/></td>
			<td align="left"><input type="text" name="name" size="30" class="bginput"></td>
		</tr>
		<tr>
			<td align="right" valign="top"><b>Template Title:<b/></td>
			<td align="left"><input type="text" name="title" size="30" class="bginput"></td>
		</tr>
		<tr>
			<td align="right" valign="top"><b>Template Content:<b/></td>
			<td align="left"><textarea name="template" cols="60" rows="10"></textarea></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="submit" value="save template"> <input type="reset" value="reset template"></td></td>
		</tr>
		</form>';
      _form_header_close_ ();
      exit ();
    }
  }

  if ($do == 'ts_templates_update')
  {
    $templateid = intval ($_POST['templateid']);
    $template = unhtmlspecialchars ($_POST['template']);
    $query = sql_query ('SELECT * FROM ts_templates WHERE templateid = ' . sqlesc ($templateid));
    if ((0 < mysql_num_rows ($query) AND !empty ($template)))
    {
      (sql_query ('UPDATE ts_templates SET template = ' . sqlesc ($template) . ' WHERE templateid = ' . sqlesc ($templateid)) OR sqlerr (__FILE__, 77));
      $_message = '' . 'Template id: ' . $templateid . ' (' . @mysql_result ($query, 0, 'name') . '/' . @mysql_result ($query, 0, 'title') . ') has been updated!';
    }
  }

  if ($do == 'ts_templates_view')
  {
    $templateid = intval ($_GET['templateid']);
    $query = sql_query ('SELECT * FROM ts_templates WHERE templateid = ' . sqlesc ($templateid));
    if (0 < mysql_num_rows ($query))
    {
      $template = mysql_fetch_assoc ($query);
      $where = array ('Cancel' => $_SERVER['SCRIPT_NAME'] . '?do=ts_templates');
      echo jumpbutton ($where);
      _form_header_open_ ('Manage Templates - Edit');
      echo '
		<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
		<input type="hidden" name="do" value="ts_templates_update">
		<input type="hidden" name="templateid" value="' . $templateid . '">
		<tr>
			<td align="left" class="subheader">Template ID: ' . $template['templateid'] . '<br />Template Name: ' . $template['name'] . '<br />Title: ' . $template['title'] . '</td>
		</tr>
		<tr>
			<td align="center"><textarea name="template" rows="30" cols="110">' . htmlspecialchars_uni ($template['template']) . '</textarea></td>
		</tr>
		<tr>
			<td align="center">
			<input type="submit" value="update template"> <input type="reset" value="reset template"></td>
		</tr>
		</form>';
      _form_header_close_ ();
      exit ();
    }
  }

  $query = sql_query ('SELECT * FROM ts_templates ORDER by name, title');
  while ($templates = mysql_fetch_assoc ($query))
  {
    $output .= '<option value="' . $templates['templateid'] . '" >' . $templates['name'] . ' --> ' . $templates['title'] . '</option>';
  }

  $where = array ('Create New Template' => $_SERVER['SCRIPT_NAME'] . '?do=ts_templates_new');
  echo jumpbutton ($where);
  _form_header_open_ ('Manage Templates');
  echo '
	<form method="GET" action="' . $_SERVER['SCRIPT_NAME'] . '">
	<input type="hidden" name="do" value="ts_templates_view">
	<tr>
		<td valign="top">' . (isset ($_message) ? '<font color="red"><b>' . $_message . '</b></font><br />' : '') . 'Please select a template to edit:<br /><select name="templateid" size="13" style="width: 350px;" class="bginput" ondblclick="this.form.submit();">' . $output . '</select>
		<br /><br /><input type="submit" value="edit selected template" class="hoptobutton">
		</td>		
	</tr>
	</form>';
  _form_header_close_ ();
?>
