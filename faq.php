<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show_faq_errors ()
  {
    global $faq_errors;
    global $lang;
    if (0 < count ($faq_errors))
    {
      $errors = implode ('<br />', $faq_errors);
      echo '
			<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
			<tr>
				<td class="thead">
					' . $lang->global['error'] . '
				</td>
			</tr>
			<tr>
				<td>
					<font color="red">
						<strong>
							' . $errors . '
						</strong>
					</font>
				</td>
			</tr>
			</table>
			<br />
		';
    }

  }

  require_once 'global.php';
  gzip ();
  dbconn ();
  define ('TSFAQ_VERSION', '1.2.3');
  include_once INC_PATH . '/functions_security.php';
  $lang->load ('faq');
  $do = (isset ($_GET['do']) ? htmlspecialchars_uni ($_GET['do']) : (isset ($_POST['do']) ? htmlspecialchars_uni ($_POST['do']) : ''));
  $faq_errors = array ();
  stdhead ($lang->faq['faqtitle']);
  echo '<script language="javascript" type="text/javascript" src="' . $BASEURL . '/scripts/animatedcollapse.js?v=' . O_SCRIPT_VERSION . '"></script>';
  if ($do == 'search')
  {
    $words = trim ($_GET['words']);
    $searchtype = ($_GET['searchtype'] == 'titles' ? 'titles' : 'all');
    if ((empty ($words) OR strlen ($words) < 3))
    {
      $faq_errors[] = $lang->faq['searcherror'];
    }
    else
    {
      if ($searchtype == 'titles')
      {
        $extra = 'name LIKE \'%' . mysql_real_escape_string ($words) . '%\'';
      }
      else
      {
        $extra = '(name LIKE \'%' . mysql_real_escape_string ($words) . '%\' OR description LIKE \'%' . mysql_real_escape_string ($words) . '%\')';
      }

      $query = sql_query ('SELECT id,name,description FROM ts_faq WHERE type = \'2\' AND ' . $extra . ' ORDER By disporder ASC');
      if (mysql_num_rows ($query) == 0)
      {
        $faq_errors[] = $lang->faq['searcherror'];
      }
      else
      {
        echo '
				<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
					<tr>
						<td class="thead">
							' . $lang->faq['results'] . '
						</td>
					</tr>
					<tr>
						<td>
							<ul>';
        while ($faq = mysql_fetch_assoc ($query))
        {
          echo '
				<li><a href="javascript:collapse' . $faq['id'] . '.slideit()"><font color="red">' . $faq['name'] . '</font></a></li>
				<div id="faq' . $faq['id'] . '" style="padding: 0px 0px 0px 0px; margin: 0px 0px 0px 15px;">' . $faq['description'] . '<br /></div>
				<script type="text/javascript">
					var collapse' . $faq['id'] . '=new animatedcollapse("faq' . $faq['id'] . '", 850, true)
				</script>';
        }

        echo '
						</ul>
					</td>
				</tr>
			</table><br />';
      }
    }
  }

  if ($do == 'view')
  {
    $id = intval ($_GET['id']);
    if (!is_valid_id ($id))
    {
      $faq_errors[] = $lang->faq['faqerror'];
    }
    else
    {
      $query = sql_query ('' . 'SELECT a.id,a.name,a.description,b.name as title FROM ts_faq a LEFT JOIN ts_faq b ON (a.pid=b.id) WHERE a.type = \'2\' AND a.pid = \'' . $id . '\' ORDER By a.disporder ASC');
      if (mysql_num_rows ($query) == 0)
      {
        $faq_errors[] = $lang->faq['faqerror'];
      }
      else
      {
        echo '
				<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
					<tr>
						<td class="thead">
							' . $lang->faq['faqtitle'] . '
						</td>
					</tr>
					<tr>
						<td>
							';
        $uldone = false;
        while ($faq = mysql_fetch_assoc ($query))
        {
          if (!$uldone)
          {
            echo '
						<ul><strong>' . $faq['title'] . '</strong>';
          }

          echo '
						<li><a href="javascript:collapse' . $faq['id'] . '.slideit()"><font color="red">' . $faq['name'] . '</font></a></li>
						<div id="faq' . $faq['id'] . '" style="padding: 0px 0px 0px 0px; margin: 0px 0px 0px 15px;">' . $faq['description'] . '<br /></div>
						<script type="text/javascript">
							var collapse' . $faq['id'] . '=new animatedcollapse("faq' . $faq['id'] . '", 850, true)
						</script>';
          $uldone = true;
        }

        echo '
						</ul>
					</td>
				</tr>
			</table><br />';
      }
    }
  }

  show_faq_errors ();
  echo '
<form method="get" action="' . $_SERVER['SCRIPT_NAME'] . '">
<input type="hidden" name="do" value="search" />
<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
	<tr>
		<td class="thead">
			' . $lang->faq['search'] . '
		</td>
	</tr>
	<tr>
		<td>
		' . $lang->faq['words'] . ' <input type="text" name="words" size="30" value="' . (isset ($words) ? htmlspecialchars_uni ($words) : '') . '" />
		' . $lang->faq['searchin'] . '
		<select name="searchtype">
			<option value="all"' . ($searchtype == 'all' ? ' selected="selected"' : '') . '>' . $lang->faq['searchin1'] . ' </option>
			<option value="titles"' . ($searchtype == 'titles' ? ' selected="selected"' : '') . '>' . $lang->faq['searchin2'] . ' </option>
		</select>
		 <input type="submit" value="' . $lang->faq['dosearch'] . ' " />
		  <input type="reset" value="' . $lang->faq['reset'] . '" />
		</td>
	</tr>
</table>
</form>
<br />
';
  ($query = sql_query ('SELECT id, name FROM ts_faq WHERE type = \'1\' ORDER By disporder ASC') OR sqlerr (__FILE__, 190));
  if (0 < mysql_num_rows ($query))
  {
    echo '
	<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
		<tr>
			<td class="thead">
				' . $lang->faq['faqtitle'] . '
			</td>
		</tr>
		<tr>
			<td>
				<ul>
	';
    while ($faq = mysql_fetch_assoc ($query))
    {
      echo '<li><a href="' . $_SERVER['SCRIPT_NAME'] . '?do=view&amp;id=' . $faq['id'] . '"><u>' . $faq['name'] . '</u></a></li>';
    }

    echo '
				</ul>
			</td>
		</tr>
	</table>';
  }

  stdfoot ();
?>
