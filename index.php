<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  define ('THIS_SCRIPT', 'index.php');
  include("offline.php");
  require_once 'global.php';
  define ('TS_BLOG_VERSION', '1.4.1');
  define ('IN_PLUGIN_SYSTEM', true);
  gzip ();
  dbconn (true);
  maxsysop ();
  include_once INC_PATH . '/functions_security.php';
  if ($ref == 'yes')
  {
    $uref = (!empty ($_SERVER['HTTP_REFERER']) ? htmlspecialchars_uni ($_SERVER['HTTP_REFERER']) : '');
    if (((!empty ($uref) AND !preg_match ('/' . basename ($_SERVER['HTTP_HOST']) . '/i', $uref)) AND (empty ($_COOKIE['referrer']) OR $_COOKIE['referrer'] != $uref)))
    {
      setcookie ('referrer', $uref, TIMENOW + 3600);
      sql_query ('REPLACE INTO referrer set referrer_url = ' . sqlesc ($uref));
    }
  }

  $lang->load ('index');
  require_once INC_PATH . '/plugins/ts_plugin_config.php';
  $defaulttemplate = ts_template ();
  $is_mod = is_mod ($usergroups);
  stdhead (sprintf ($lang->index['welcome'], $SITENAME), TRUE, 'collapse', '<script type="text/javascript" src="./scripts/quick_editor.js?v=' . O_SCRIPT_VERSION . '"></script>');
  $_header = '
  <script src="peel/peel.js" type="text/javascript"></script>

<table align="center" cellpadding="0" cellspacing="0" width="100%">
	<tbody>
		<tr valign="top">
';
  $_footer = '
		</tr>
	</tbody>
</table>
';
  $_div = '
<div style="padding-bottom: ' . $_div_padding_bottom . ';">
	<table align="center" border="0" cellpadding="6" cellspacing="0" width="100%">
		<thead>
			<tr>
				<td class="thead" colspan="0">
					{1}
					<span class="smalltext"><strong>' . $_title_bracket . ' {2}</strong></span>
				</td>
			</tr>
		</thead>
		{3}
			<tr>
				<td>
					{4}
				</td>
			</tr>
		</tbody>
	</table>
</div>
';
  $_left_header = array ('<td style="padding-right: ' . $_left_plugin_padding_right . ';" width="' . $_left_plugin_width . '" class="none">', '</td>');
  $_middle_header = array ('<td valign="top" class="none">', '</td>');
  $_right_header = array ('<td style="padding-left: ' . $_right_plugin_padding_left . '" valign="top" width="' . $_right_plugin_width . '" class="none">', '</td>');
  $_curuser_usergroup = ((!$CURUSER['usergroup'] OR !$CURUSER) ? '[0]' : '[' . $CURUSER['usergroup'] . ']');
  $_contents = $_header;
  require_once TSDIR . '/' . $cache . '/plugins.php';
  if (0 < count ($Plugins_LEFT))
  {
    $__width = $_left_plugin_width - 10;
    $__cute = 20;
    $_contents .= $_left_header[0];
    foreach ($Plugins_LEFT as $_results)
    {
      $show_content = false;
      $_perm_1 = $_results['permission'];
      if (($_perm_1 === '[guest]' AND $_curuser_usergroup === '[0]'))
      {
        $show_content = true;
      }
      else
      {
        if (($_perm_1 === '[all]' OR strstr ($_perm_1, $_curuser_usergroup)))
        {
          $show_content = true;
        }
      }

      if ($show_content)
      {
        if ($_results['content'] != '')
        {
          $_contents .= str_replace (array ('{1}', '{2}', '{3}', '{4}'), array (ts_collapse (str_replace (' ', '_', $_results['name']), 1), $_results['description'], ts_collapse (str_replace (' ', '_', $_results['name']), 2), $_results['content']), $_div);
          continue;
        }
        else
        {
          if (file_exists (INC_PATH . '/plugins/' . $_results['name'] . '.php'))
          {
            include_once INC_PATH . '/plugins/' . $_results['name'] . '.php';
            $_contents .= str_replace (array ('{1}', '{2}', '{3}', '{4}'), array (ts_collapse (str_replace (' ', '_', $_results['name']), 1), $_results['description'], ts_collapse (str_replace (' ', '_', $_results['name']), 2), ${$_results['name']}), $_div);
            continue;
          }

          continue;
        }

        continue;
      }
    }

    $_contents .= $_left_header[1];
    unset ($Plugins_LEFT);
    unset ($_perm_1);
  }

  $show_content = false;
  if (0 < count ($Plugins_MIDDLE))
  {
    $__width = $_left_plugin_width * 2 + 130;
    $__cute = 180;
    $_contents .= $_middle_header[0];
    foreach ($Plugins_MIDDLE as $_results)
    {
      $show_content = false;
      $_perm_2 = $_results['permission'];
      if (($_perm_2 === '[guest]' AND $_curuser_usergroup === '[0]'))
      {
        $show_content = true;
      }
      else
      {
        if (($_perm_2 === '[all]' OR strstr ($_perm_2, $_curuser_usergroup)))
        {
          $show_content = true;
        }
      }

      if ($show_content)
      {
        if ($_results['content'] != '')
        {
          $_contents .= str_replace (array ('{1}', '{2}', '{3}', '{4}'), array (ts_collapse (str_replace (' ', '_', $_results['name']), 1), $_results['description'], ts_collapse (str_replace (' ', '_', $_results['name']), 2), $_results['content']), $_div);
          continue;
        }
        else
        {
          if (file_exists (INC_PATH . '/plugins/' . $_results['name'] . '.php'))
          {
            include_once INC_PATH . '/plugins/' . $_results['name'] . '.php';
            $_contents .= str_replace (array ('{1}', '{2}', '{3}', '{4}'), array (ts_collapse (str_replace (' ', '_', $_results['name']), 1), $_results['description'], ts_collapse (str_replace (' ', '_', $_results['name']), 2), ${$_results['name']}), $_div);
            continue;
          }

          continue;
        }

        continue;
      }
    }

    $_contents .= $_middle_header[1];
    unset ($Plugins_MIDDLE);
    unset ($_perm_2);
  }

  $show_content = false;
  if (0 < count ($Plugins_RIGHT))
  {
    $__width = $_right_plugin_width - 10;
    $__cute = 20;
    $_contents .= $_right_header[0];
    foreach ($Plugins_RIGHT as $_results)
    {
      $show_content = false;
      $_perm_3 = $_results['permission'];
      if (($_perm_3 === '[guest]' AND $_curuser_usergroup === '[0]'))
      {
        $show_content = true;
      }
      else
      {
        if (($_perm_3 === '[all]' OR strstr ($_perm_3, $_curuser_usergroup)))
        {
          $show_content = true;
        }
      }

      if ($show_content)
      {
        if ($_results['content'] != '')
        {
          $_contents .= str_replace (array ('{1}', '{2}', '{3}', '{4}'), array (ts_collapse (str_replace (' ', '_', $_results['name']), 1), $_results['description'], ts_collapse (str_replace (' ', '_', $_results['name']), 2), $_results['content']), $_div);
          continue;
        }
        else
        {
          if (file_exists (INC_PATH . '/plugins/' . $_results['name'] . '.php'))
          {
            include_once INC_PATH . '/plugins/' . $_results['name'] . '.php';
            $_contents .= str_replace (array ('{1}', '{2}', '{3}', '{4}'), array (ts_collapse (str_replace (' ', '_', $_results['name']), 1), $_results['description'], ts_collapse (str_replace (' ', '_', $_results['name']), 2), ${$_results['name']}), $_div);
            continue;
          }

          continue;
        }

        continue;
      }
    }

    $_contents .= $_right_header[1];
    unset ($Plugins_RIGHT);
    unset ($_perm_3);
  }

  echo $_contents . $_footer;
  stdfoot ();
?>
