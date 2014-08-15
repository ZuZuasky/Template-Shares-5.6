<?
/***********************************************/
/*     TS Special Edition v.5.6 [Nulled]       */
/*              Special Thanks To              */
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*         Fynnon - wWw.BvList.CoM             */
/*          Aser - wWw.BvList.CoM              */
/***********************************************/

  class checkphpsafemode
  {
    var $ZavaZingo = null;
    var $TavaZingo = null;
    var $Havai = null;
    var $PokeMon = null;
    function checkphpsafemode ()
    {
      if (ini_get ('safe_mode'))
      {
        critical_error ('Please disable PHP Safe Mode to continue installation!');
        exit ();
      }

    }
  }

  function mksecret ($length = 20)
  {
    $str = '';
    $set = array ('a', 'A', 'b', 'B', 'c', 'C', 'd', 'D', 'e', 'E', 'f', 'F', 'g', 'G', 'h', 'H', 'i', 'I', 'j', 'J', 'k', 'K', 'l', 'L', 'm', 'M', 'n', 'N', 'o', 'O', 'p', 'P', 'q', 'Q', 'r', 'R', 's', 'S', 't', 'T', 'u', 'U', 'v', 'V', 'w', 'W', 'x', 'X', 'y', 'Y', 'z', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    $str;
    $i = 1;
    while ($i <= $length)
    {
      $ch = rand (0, count ($set) - 1);
      $str .= $set[$ch];
      ++$i;
    }

    return $str;
  }

  function get_date_time ()
  {
    return date ('Y-m-d H:i:s');
  }

  function sqlesc ($value)
  {
    if (get_magic_quotes_gpc ())
    {
      $value = stripslashes ($value);
    }

    if (!is_numeric ($value))
    {
      $value = '\'' . mysql_real_escape_string ($value) . '\'';
    }

    return $value;
  }

  function insert_admin ()
  {
    $db = connect_db ();
    if (!empty ($db))
    {
      critical_error (implode ('<br />', $db) . '<br />There seems to be one or more errors with the database configuration information that you supplied. Click <a href="install.php?step=2">here</a> to to back step 2.');
    }

    $secretkey = mksecret ();
    $secret = '\'' . mysql_real_escape_string ($secretkey) . '\'';
    $username = '\'' . mysql_real_escape_string ($_SESSION['username']) . '\'';
    $passhash = '\'' . mysql_real_escape_string (md5 ($secretkey . $_SESSION['password'] . $secretkey)) . '\'';
    $email = '\'' . mysql_real_escape_string ($_SESSION['email']) . '\'';
    $status = '\'' . mysql_real_escape_string ('confirmed') . '\'';
    $usergroup = '\'' . mysql_real_escape_string ('8') . '\'';
    $added = '\'' . mysql_real_escape_string (get_date_time ()) . '\'';
    (mysql_query ('' . 'INSERT INTO users (username, passhash, secret, email, status, usergroup, added) VALUES (' . $username . ', ' . $passhash . ', ' . $secret . ', ' . $email . ', ' . $status . ', ' . $usergroup . ', ' . $added . ')') OR critical_error (mysql_errno () . ' : ' . mysql_error ()));
    $id = mysql_insert_id ();
    $sechash = md5 ($_SESSION['SITENAME']);
    $pincode = md5 (md5 ($sechash) . md5 ($_SESSION['pincode']));
    mysql_query ('INSERT INTO pincode SET pincode = ' . sqlesc ($pincode) . ', sechash = ' . sqlesc ($sechash) . ', area = 1');
    $filename = ROOT_PATH . 'config/STAFFTEAM';
    $somecontent = $_SESSION['username'] . ':' . $id;
    if (is_writable ($filename))
    {
      $handle = @fopen ($filename, 'w');
      @fwrite ($handle, $somecontent);
      @fclose ($handle);
    }

  }

  function writeconfig ($configname, $config)
  {
    $configname = basename ($configname);
    $path = ROOT_PATH . 'config/' . $configname;
    if ((!file_exists ($path) OR !is_writable ($path)))
    {
      critical_error ('' . $path . ' isn\'t writable! Please check chmod settings.');
    }

    $data = @serialize ($config);
    if (empty ($data))
    {
      critical_error ('' . $path . ' is corrupted! Please re-upload it in binary mode!');
    }

    $fp = @fopen ($path, 'w');
    if (!$fp)
    {
      critical_error ('' . $path . ' is corrupted! Please re-upload it in binary mode!');
    }

    $Res = @fwrite ($fp, $data);
    if (empty ($Res))
    {
      critical_error ('' . $path . ' isn\'t writable! Please check chmod settings.');
    }

    fclose ($fp);
    return true;
  }

  function get_ext ($file)
  {
    $file = strtolower (substr (strrchr ($file, '.'), 1));
    return $file;
  }

  function replace_url ($url)
  {
    return str_replace (array ('http://www.', 'http://', 'www.'), '', $url);
  }

  function critical_error ($message)
  {
    echo html_header ('A critical error has occured.', '<span style="color: darkred; font-weight: bold;">' . $message . '</span>');
    echo html_footer ();
    exit ();
  }

  function next_button ($step, $message = '', $error = false)
  {
    return '<br /><table width="100%" border="0" cellpadding="4" cellspacing="0" align="center"><tr><td class="subheader"><span style="float: right"><input type="button" value="NEXT" class=button onclick="' . (!$error ? 'window.location=\'install.php?step=' . $step . '\'' : 'alert(\'The installer has detected some problems, which will not allow ' . SCRIPT_VERSION . ' to operate correctly. Please correct these issues and then refresh the page.\')') . '"></span>' . $message . '</td></tr></table>';
  }

  function cmessage ($message, $good)
  {
    if ($good)
    {
      $yesno = '<b><font color="darkgreen">YES</font></b>';
    }
    else
    {
      $yesno = '<b><font color="darkred">NO</font></b>';
    }

    return '<tr><td width="85%" align="left">' . $message . '</td><td class="req" width="15%" align="center">' . $yesno . '</td></tr>';
  }


  function insert_session ($values)
  {
    foreach ($values as $name => $value)
    {
      unset ($_SESSION[$name]);
      $_SESSION[$name] = $value;
    }

  }

  function connect_db ()
  {
    $errors = array ('1' => '<li>Don\'t leave any fields blank!</li>', '2' => '<li>Could not connect to the database server at \'' . (isset ($_POST['mysql_host']) ? htmlspecialchars (trim ($_POST['mysql_host'])) : (isset ($_SESSION['mysql_host']) ? $_SESSION['mysql_host'] : 'empty')) . '\' with the supplied username and password.<br>Are you sure the hostname and user details are correct?</li>', '3' => '<li>Could not select the database \'' . (isset ($_POST['mysql_db']) ? htmlspecialchars (trim ($_POST['mysql_db'])) : (isset ($_SESSION['mysql_db']) ? $_SESSION['mysql_db'] : 'empty')) . '\'.<br>Are you sure it exists and the specified username and password have access to it?</li>', '4' => '<li>The passwords you entered do not match.</li>');
    $link = @mysql_connect ($_SESSION['mysql_host'], $_SESSION['mysql_user'], $_SESSION['mysql_pass']);
    $db_selected = @mysql_select_db ($_SESSION['mysql_db'], $link);
    if (!$link)
    {
      $error[] = $errors['2'];
    }

    if (!$db_selected)
    {
      $error[] = $errors['3'];
    }

    if (0 < count ($error))
    {
      return $error;
    }

  }

  function redirect ($message, $url, $wait = 3)
  {
    exit ('
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html lang="en">
	<head>
	<title>' . $message . '</title>
	<meta http-equiv="refresh" content="' . $wait . ';URL=' . $url . '">
	<link rel="stylesheet" href="' . ROOT_PATH . 'install/style/style.css" type="text/css" media="screen" />
	</head>
	<body>
	<br />
	<br />
	<br />
	<br />
	<div style="margin: auto auto; width: 50%" align="center">
	<table border="0" cellspacing="0" cellpadding="4" class="tborder">
	<tr>
	<td class="trow1" align="center"><p><font color="#000000">' . $message . '</font></p></td>
	</tr>
	<tr>
	<td class="trow2" align="right"><a href="' . $url . '">
	<span class="smalltext">Please click here if your browser does not automatically redirect you.</span></a></td>
	</tr>
	</table>
	</div>
	</body>
	</html>
	');
  }

  function html_header ($title = 'Lateam\'s and lafouine022 Installation Wizard', $content = '', $step = '')
  {
    return '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>' . $title . '</title>
			<link rel="stylesheet" href="' . ROOT_PATH . 'install/style/style.css" type="text/css" media="screen" />
		</head>
		<body>

<div id="wrapper">
		<div id="header">
			<div id="logo"></div>
<div id="searcharea">' . date ('F j, Y, g:i a') . '<br>Lateam\'s and lafouine022 Installation Wizard v.' . INSTALL_VERSION . '<br><br><b>' . $step . '</b></div>
		</div>

				<div id="bodyarea">




<table class="outer"width="100%" border="0" cellpadding="10" cellspacing="0">
							<tr>
								<td class="thead" align="center"><font size="2"><b>' . $title . '</b></font></td>
							</tr>
							<tr>
								<td>
									' . ($content ? $content . '
								</td>
							</tr>
						</table>' : '') . '';
  }

  function html_footer ()
  {
    return '
						<br />


					<center><a href="https://xamisass.net/?' . INSTALL_URL . '" target="_blank">' . SCRIPT_VERSION . '</a></strong></font> &copy; ' . date ('Y') . '</center><br>

				</body>
	</html>';
  }

  function step_0 ()
  {
    $_step_0_contents = '
	This wizard will install and configure a copy of ' . SCRIPT_VERSION . ' on your server.
	<p>Now that you\'ve uploaded the ' . SCRIPT_VERSION . ' files, the database and settings need to be created and imported. Below is an outline of what is going to be completed during installation.</p>
	<ul>
	<li>' . SCRIPT_VERSION . ' requirements checked,</li>
	<li>Configuration of database engine,</li>
	<li>Creation of database tables,</li>
	<li>Popularite tables,</li>
	<li>Basic script settings configured,</li>
	<li>Creation of an administrator account to manage your script,</li>
	<li>Finishing Setup.</li>
	</ul>
	Before we go any further, please ensure that all the files have been uploaded in binary mode, and that the folders "CONFIG" and "CACHE" has suitable permissions to allow this script to write to it (0777 should be sufficient).<br /><br />

	' . SCRIPT_VERSION . ' requires PHP 4.1.2 or better and an MYSQL database.<br /><br />

	<b>You will also need the following information that your webhost can provide:</b><br />
	<ul>
	<li> Any linux (unix), windows webserver running Apache will work. IIS may work but is not recommended, some users might have trouble with file permissions when running IIS.</li>
	<li><b>The Apache webserver version 1.3 or greater.</b></li>
	<ul><li>Short Open Tag support.</li></ul>
	<ul><li>The ability to change directory permissions to 777 or to change ownership of directories to be owned by the webserver process.</li></ul>
	<li><b>MYSQL 4.1 or greater.</b></li>
	<ul><li> Your MYSQL database name.</li></ul>
	<ul><li> Your MYSQL username.</li></ul>
	<ul><li> Your MYSQL password.</li></ul>
	<ul><li> Your MYSQL host address (usually localhost).</li></ul>
	<li><b>PHP version 4.1 or greater.</b></li>
	<ul><li> Ioncube Loader support for PHP.</li></ul>
	<ul><li> PHP session support.</li></ul>
	</ul>
	<div class=warnbox>Using this installer will delete any current ' . SCRIPT_VERSION . ' database if you are using the same table prefix.</div>
	<br />
	After each step has successfully been completed, click Next button to move on to the next step.' . next_button (1);
    echo html_header ('Welcome to Lateam\'s and lafouine022 installation wizard for ' . SCRIPT_VERSION, $_step_0_contents, 'Welcome Screen');
    echo html_footer ();
  }

  function step_1 ()
  {
    clearstatcache ();
    $_folders = array ('admin/backup', 'cache', 'config', 'error_logs', 'include/avatars', 'torrents', 'torrents/images', 'tsf_forums/uploads');
    $_files = array ('admin/adminnotes.txt', 'admin/ads.txt', 'admin/quicklinks.txt', 'include/config_announce.php');
    $__chmod_error = false;
    $_step_1_contents = '
	In this step, the ' . SCRIPT_VERSION . ' installer will determine if your system meets the requirements for the server environment. To use ' . SCRIPT_VERSION . ', you must have PHP with MySQL support and write-permissions on certain directories/files.<br /><br />';
    $canContinue = 1;
    $good = ('4.1.2' <= phpversion () ? 1 : 0);
    $canContinue = ($canContinue AND $good);
    $return .= cmessage ('PHP version >= 4.1.2: ', $good);
    $_SESSION['testing_string'] = 'Just a Test!';
    $good = ($_SESSION['testing_string'] === 'Just a Test!' ? 1 : 0);
    $canContinue = ($canContinue AND $good);
    $return .= cmessage ('PHP session support:', $good);
    $good = (function_exists ('mysql_connect') ? 1 : 0);
    $canContinue = ($canContinue AND $good);
    $return .= cmessage ('MySQL support exists: ', $good);
    if (!$canContinue)
    {
      $__chmod_error = true;
    }

    $_step_1_contents .= '
	<table width="100%" border="0" cellpadding="4" cellspacing="0" align="center">
		<tr>
			<td class="colhead" colspan="2" width="100%" align="left">Requirements Check</td>
		</tr>
		<tr>
			<td class="subheader" width="75%" align="left">Function / Feature / Requirement</td>
			<td class="subheader" width="25%" align="center">Available</td>
		</tr>
		' . $return . '
		</table><br />';
    $_step_1_contents .= '
	<table width="100%" border="0" cellpadding="4" cellspacing="0" align="center">
		<tr>
			<td class="colhead" colspan="2" width="100%" align="left">Checking Directory Chmod Permissions</td>
		</tr>
		<tr>
			<td class="subheader" width="75%" align="left">Directory</td>
			<td class="subheader" width="25%" align="center">Writable</td>
		</tr>
	';
    sort ($_folders);
    foreach ($_folders as $folder)
    {
      $__dir = ROOT_PATH . $folder;
      $_step_1_contents .= '
		<tr>
			<td width="85%" align="left">' . str_replace (ROOT_PATH, '', $__dir) . '</td>';
      if ((!is_writable ($__dir) OR !is_dir ($__dir)))
      {
        $_step_1_contents .= '
			<td align="center" width="15%"><b><font color="darkred">NO</font></b></td>
		</tr>';
        $__chmod_error = true;
        continue;
      }
      else
      {
        $_step_1_contents .= '
			<td align="center" width="15%"><b><font color="darkgreen">YES</font></b></td>
		</tr>';
        continue;
      }
    }

    $_step_1_contents .= '
	</table><br />';
    $_step_1_contents .= '
	<table width="100%" border="0" cellpadding="4" cellspacing="0" align="center">
		<tr>
			<td class="colhead" colspan="2" width="100%" align="left">Checking File Chmod Permissions</td>
		</tr>
		<tr>
			<td class="subheader" width="75%" align="left">File</td>
			<td class="subheader" width="25%" align="center">Writable</td>
		</tr>
	';
    if ($handle = opendir (ROOT_PATH . 'cache/'))
    {
      while (false !== $file = readdir ($handle))
      {
        if ((((($file != '.' AND $file != '..') AND $file != '.htaccess') AND $file != 'htaccess') AND get_ext ($file) != 'html'))
        {
          array_push ($_files, 'cache/' . $file);
          continue;
        }
      }

      closedir ($handle);
    }

    if ($handle = opendir (ROOT_PATH . 'config/'))
    {
      while (false !== $file = readdir ($handle))
      {
        if (((((($file != '.' AND $file != '..') AND $file != '.htaccess') AND $file != 'htaccess') AND get_ext ($file) != 'html') AND $file != 'paypal_config.php'))
        {
          array_push ($_files, 'config/' . $file);
          continue;
        }
      }

      closedir ($handle);
    }

    if ($handle = opendir (ROOT_PATH . 'error_logs/'))
    {
      while (false !== $file = readdir ($handle))
      {
        if ((($file != '.' AND $file != '..') AND get_ext ($file) == 'php'))
        {
          array_push ($_files, 'error_logs/' . $file);
          continue;
        }
      }

      closedir ($handle);
    }

    sort ($_files);
    foreach ($_files as $file)
    {
      $__file = ROOT_PATH . $file;
      $_step_1_contents .= '
		<tr>
			<td width="85%" align="left">' . str_replace (ROOT_PATH, '', $__file) . '</td>';
      if ((!is_writable ($__file) OR !is_file ($__file)))
      {
        $_step_1_contents .= '
			<td align="center" width="15%"><b><font color="darkred">NO</font></b></td>
		</tr>';
        $__chmod_error = true;
        continue;
      }
      else
      {
        $_step_1_contents .= '
			<td align="center" width="15%"><b><font color="darkgreen">YES</font></b></td>
		</tr>';
        continue;
      }
    }

    $_step_1_contents .= '
	</table>';
    if (!$__chmod_error)
    {
      $_step_1_contents .= next_button (2, 'Congratulations, no errors found!');
    }
    else
    {
      $_step_1_contents .= next_button (2, ' Lateam\'s and lafouine022 installer has detected some problems with your server environment, which will not allow ' . SCRIPT_VERSION . ' to operate correctly. Please correct these issues and then refresh the page to re-check your environment.', true);
    }

    echo html_header ('Welcome to  Lateam\'s and lafouine022 installation wizard for ' . SCRIPT_VERSION, '
	' . $_step_1_contents . '
	', 'Requirements Check');
    echo html_footer ();
  }

  function step_2 ()
  {
    $error = array ();
    $errors = array ('1' => '<li>Don\'t leave any fields blank!</li>', '2' => '<li>Could not connect to the database server at \'' . (isset ($_POST['mysql_host']) ? htmlspecialchars (trim ($_POST['mysql_host'])) : (isset ($_SESSION['mysql_host']) ? $_SESSION['mysql_host'] : 'empty')) . '\' with the supplied username and password.<br>Are you sure the hostname and user details are correct?</li>', '3' => '<li>Could not select the database \'' . (isset ($_POST['mysql_db']) ? htmlspecialchars (trim ($_POST['mysql_db'])) : (isset ($_SESSION['mysql_db']) ? $_SESSION['mysql_db'] : 'empty')) . '\'.<br>Are you sure it exists and the specified username and password have access to it?</li>', '4' => '<li>The passwords you entered do not match.</li>');
    if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
    {
      $mysql_host = htmlspecialchars (trim ($_POST['mysql_host']));
      $mysql_user = htmlspecialchars (trim ($_POST['mysql_user']));
      $mysql_pass = htmlspecialchars (trim ($_POST['mysql_pass']));
      $mysql_db = htmlspecialchars (trim ($_POST['mysql_db']));
      if ((((empty ($mysql_host) OR empty ($mysql_user)) OR empty ($mysql_pass)) OR empty ($mysql_db)))
      {
        $error[] = $errors['1'];
      }

      $link = @mysql_connect ($mysql_host, $mysql_user, $mysql_pass);
      if (!$link)
      {
        $error[] = $errors['2'];
      }

      $db_selected = @mysql_select_db ($mysql_db, $link);
      if (!$db_selected)
      {
        $error[] = $errors['3'];
      }

      if (empty ($error))
      {
        $values = array ('mysql_host' => $mysql_host, 'mysql_user' => $mysql_user, 'mysql_pass' => $mysql_pass, 'mysql_db' => $mysql_db);
        insert_session ($values);
        redirect ('Database configuration has been saved successfully.', 'install.php?step=3');
      }
    }

    if (0 < count ($error))
    {
      foreach ($error as $_e)
      {
        $_step_2_contents = '<font color="red"><b>' . $_e . '</b></font><br />';
      }
    }

    $_step_2_contents .= '
	The ' . SCRIPT_VERSION . ' installer needs some information about your database to finish the installation. If you do not know this information, then please contact your website host or administrator. Please note that this is probably <strong>NOT</strong> the same as your <strong>FTP</strong> login information!<br /><br />
	<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '"?step=2">
	<input type="hidden" name="step" value="2">
	<table border="0" width="100%" align="center" cellpadding="4">
		<tr>
			<td align="right" class="subheader"><div align="right">Database Host: </div></td>
			<td><input name="mysql_host" id="input" type="text" value="' . (isset ($_POST['mysql_host']) ? htmlspecialchars ($_POST['mysql_host']) : 'localhost') . '" onblur="if (this.value == \'\') this.value = \'localhost\';" onfocus="if (this.value == \'localhost\') this.value = \'\';"></td>
		</tr>
		<tr>
			<td align="right" class="subheader"><div align="right">Database Username: </div></td>
			<td><input name="mysql_user" id="input" type="text" value="' . (isset ($_POST['mysql_user']) ? htmlspecialchars ($_POST['mysql_user']) : 'root') . '" onblur="if (this.value == \'\') this.value = \'root\';" onfocus="if (this.value == \'root\') this.value = \'\';"></td>
		</tr>
		<tr>
			<td align="right" class="subheader"><div align="right">Database Password: </div></td>
			<td><input name="mysql_pass" id="input" type="password" value=""></td>
		</tr>
		<tr>
			<td align="right" class="subheader"><div align="right">Database Name: </div></td>
			<td><input name="mysql_db" id="input" type="text" value="' . (isset ($_POST['mysql_db']) ? htmlspecialchars ($_POST['mysql_db']) : '') . '"></td>
		</tr>
		<tr>
			<td align="right" colspan="2"><br /><table width="100%" border="0" cellpadding="4" cellspacing="0" align="center"><tr><td class="subheader"><span style="float: right"><input type="submit" value="NEXT" class=button></td></tr></table></td>
		</tr>
	</table>
	</form>';
    echo html_header ('Welcome to the installation wizard for ' . SCRIPT_VERSION, '
	' . $_step_2_contents . '
	', 'Database Configuration');
    echo html_footer ();
  }

  function step_3 ()
  {
    $db = connect_db ();
    if (!empty ($db))
    {
      critical_error (implode ('<br />', $db) . '<br />There seems to be one or more errors with the database configuration information that you supplied. Click <a href="install.php?step=2">here</a> to to back step 2.');
    }

  require_once THIS_ROOT_PATH . 'create_tables.php';
    if (count ($ts_tables) < 1)
    {
      critical_error ('Connection Error: ts_tables');
    }

    echo html_header ('Welcome to  Lateam\'s and lafouine022 installation wizard for ' . SCRIPT_VERSION, FALSE, 'Table Creation');
    echo '<table border="0" align="center" cellpadding="4" class="okbox" width=100%>';
    ob_flush ();
    flush ();
    $count = 0;
    $dberror = false;
    foreach ($ts_tables as $val)
    {
      @preg_match ('#CREATE TABLE (\\S+) \\(#i', $val, $match);
      if (($match[1] AND !$dberror))
      {
        ++$count;
        @mysql_query ('DROP TABLE IF EXISTS ' . $match[1]);
        echo '<tr><td align=right>(' . $count . ') Creating table:</td>
			<td align=left><strong>' . $match[1] . '</strong> => ';
        ob_flush ();
        flush ();
      }

      (@mysql_query ($val) OR $dberror = true);
      if (($match[1] AND !$dberror))
      {
        echo '<font color=green>done</td></tr>
';
        ob_flush ();
        flush ();
      }

      if ($dberror)
      {
        echo '<font color=red size=2>error!!!</font></td></tr>
			<tr><td colspan=3><p><div class=warnbox><strong>' . mysql_errno () . ' : ' . mysql_error () . '</td></tr>';
        echo next_button (3, 'The installer has detected some problems with your server environment, which will not allow ' . SCRIPT_VERSION . ' to operate correctly. Please correct these issues and then refresh the page to re-check your environment.', true);
        ob_flush ();
        flush ();
        break;
      }
    }

    if (!$dberror)
    {
      echo '</td></tr></table>
		' . next_button (4, 'All tables (' . $count . ') have been created, click Next to populate them.');
      ob_flush ();
      flush ();
    }

    echo '</td></tr></table>';
    echo html_footer ();
    ob_flush ();
    flush ();
  }

  function step_4 ()
  {
    $db = connect_db ();
    if (!empty ($db))
    {
      critical_error (implode ('<br />', $db) . '<br />There seems to be one or more errors with the database configuration information that you supplied. Click <a href="install.php?step=2">here</a> to to back step 2.');
    }


    require_once THIS_ROOT_PATH . 'insert.php';
    if (count ($_queries) < 1)
    {
      critical_error ('Connection Error: ts_tables2');
    }

    echo html_header ('Welcome to Lateam\'s and lafouine022 installation wizard for ' . SCRIPT_VERSION, FALSE, 'Populate Tables');
    echo '                                                  ';
    echo '                                                  ';
    echo '                                                  ';
    echo '                                                  ';
    echo '                                                  ';
    ob_flush ();
    flush ();
    $_dberrorr = false;
    $_errors = array ();
    foreach ($_queries as $_query)
    {
      if (!mysql_query ($_query))
      {
        $_errors[] = $_query;
        $_dberrorr = true;
        break;
      }
    }

    if (!$_dberrorr)
    {
      echo next_button (5, 'The default data has successfully been inserted into the database.');
      echo '</td></tr></table>';
      echo html_footer ();
      echo '                                                  ';
      echo '                                                  ';
      echo '                                                  ';
      echo '                                                  ';
      echo '                                                  ';
      ob_flush ();
      flush ();
      return null;
    }

    echo '<span style="color:red;"><b>Mysql Error: ' . mysql_errno () . ' : ' . mysql_error () . '</b></span><br /><br />' . htmlspecialchars (implode ('<br />', $_errors));
    echo next_button (3, 'The installer has detected some problems with your server environment, which will not allow ' . SCRIPT_VERSION . ' to operate correctly. Please correct these issues and then refresh the page to re-check your environment. Click <a href="install.php?step=3">here</a> to to back step 3.', true);
    echo '</td></tr></table>';
    echo html_footer ();
    ob_flush ();
    flush ();
  }

  function step_5 ()
  {
    $errors = array ('1' => '<li>Don\'t leave any fields blank!</li>', '2' => '<li>Could not connect to the database server at \'' . (isset ($_POST['mysql_host']) ? htmlspecialchars (trim ($_POST['mysql_host'])) : (isset ($_SESSION['mysql_host']) ? $_SESSION['mysql_host'] : 'empty')) . '\' with the supplied username and password.<br>Are you sure the hostname and user details are correct?</li>', '3' => '<li>Could not select the database \'' . (isset ($_POST['mysql_db']) ? htmlspecialchars (trim ($_POST['mysql_db'])) : (isset ($_SESSION['mysql_db']) ? $_SESSION['mysql_db'] : 'empty')) . '\'.<br>Are you sure it exists and the specified username and password have access to it?</li>', '4' => '<li>The passwords you entered do not match.</li>');
    $_MFile = ROOT_PATH . 'config/MAIN';
    if ((!is_writable ($_MFile) OR !is_file ($_MFile)))
    {
      critical_error ('I can\'t continue. Please check chmod permission of the following file: <b>' . $_MFile . '</b><br />');
    }

    if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
    {
      $tracker_name = htmlspecialchars (trim ($_POST['tracker_name']));
      $tracker_url = htmlspecialchars (trim ($_POST['tracker_url']));
      $announce_url = htmlspecialchars (trim ($_POST['announce_url']));
      $contact_email = htmlspecialchars (trim ($_POST['contact_email']));
      if ((((empty ($tracker_name) OR empty ($tracker_url)) OR empty ($announce_url)) OR empty ($contact_email)))
      {
        $error[] = $errors['1'];
      }

      if (0 < count ($error))
      {
        foreach ($error as $err)
        {
          $_step_5_contents .= $err;
        }
      }
      else
      {
        $values = array ('SITENAME' => $tracker_name, 'BASEURL' => $tracker_url, 'announce_urls' => $announce_url, 'SITEEMAIL' => $contact_email);
        insert_session ($values);
        $DATABASE['mysql_host'] = $_SESSION['mysql_host'];
        $DATABASE['mysql_user'] = $_SESSION['mysql_user'];
        $DATABASE['mysql_pass'] = $_SESSION['mysql_pass'];
        $DATABASE['mysql_db'] = $_SESSION['mysql_db'];
        writeconfig ('DATABASE', $DATABASE);
        $MAIN['BASEURL'] = $_SESSION['BASEURL'];
        $MAIN['SITENAME'] = $_SESSION['SITENAME'];
        $MAIN['announce_urls'] = $_SESSION['announce_urls'];
        $MAIN['SITEEMAIL'] = $_SESSION['SITEEMAIL'];
        $MAIN['site_online'] = 'yes';
        writeconfig ('MAIN', $MAIN);
        $FORUMCP['f_forum_online'] = 'no';
        writeconfig ('FORUMCP', $FORUMCP);
        redirect ('Basic Tracker Settings has been saved successfully.', 'install.php?step=6');
      }
    }

    $_step_5_contents .= '
		<form method="post" action="install.php?step=5" name="save_settings" id="save_settings">
		<input type="hidden" name="step" value="5">
		<table border="0" width="100%" align="left" cellpadding="4">
		<thead>
		  <tr>
		   <td align="right" class="subheader"><div align="right">Tracker Name: </div></td>
		   <td><input name="tracker_name" id="input" type="text" value="' . (isset ($_POST['tracker_name']) ? htmlspecialchars ($_POST['tracker_name']) : INSTALL_URL) . '" onblur="if (this.value == \'\') this.value = \'' . INSTALL_URL . '\';" onfocus="if (this.value == \'' . INSTALL_URL . '\') this.value = \'\';" size="50"></td></tr>
		  <tr>
		   <td align="right" class="subheader"><div align="right">Tracker URL: </div></td>
		   <td><input name="tracker_url" id="input" type="text" value="' . (isset ($_POST['tracker_url']) ? htmlspecialchars ($_POST['tracker_url']) : 'http://' . INSTALL_URL) . '" onblur="if (this.value == \'\') this.value = \'http://' . INSTALL_URL . '\';" onfocus="if (this.value == \'http://' . INSTALL_URL . '\') this.value = \'\';" size="50"></td></tr>
		  <tr>
		   <td align="right" class="subheader"><div align="right">Announce URL: </div></td>
		   <td><input name="announce_url" id="input" type="text" value="' . (isset ($_POST['announce_url']) ? htmlspecialchars ($_POST['announce_url']) : 'http://' . INSTALL_URL . '/announce.php') . '" onblur="if (this.value == \'\') this.value = \'http://' . INSTALL_URL . '/announce.php\';" onfocus="if (this.value == \'http://' . INSTALL_URL . '/announce.php\') this.value = \'\';" size="50"></td></tr>
		  <tr>
		   <td align="right" class="subheader"><div align="right">Contact Email: </div></td>
		   <td><input name="contact_email" id="input" type="text" value="' . (isset ($_POST['contact_email']) ? htmlspecialchars ($_POST['contact_email']) : 'contact@' . INSTALL_URL . '') . '" onblur="if (this.value == \'\') this.value = \'contact@' . INSTALL_URL . '\';" onfocus="if (this.value == \'contact@' . INSTALL_URL . '\') this.value = \'\';" size="50"></td></tr>
		<tr>
		 <td align="right" colspan="2">
			<table width="100%" border="0" cellpadding="4" cellspacing="0" align="center"><tr><td class="subheader"><span style="float: right"><input type="submit" value="NEXT" class=button></td></tr></table>
		 </td>
		 </tr>
		 </form>
		 </table>';
    echo html_header ('Welcome to the installation wizard for ' . SCRIPT_VERSION, '
	' . $_step_5_contents . '
	', 'Basic Tracker Configuration');
    echo html_footer ();
  }

  function step_6 ()
  {
    $db = connect_db ();
    if (!empty ($db))
    {
      critical_error (implode ('<br>', $db));
    }

    $errors = array ('1' => '<li>Don\'t leave any fields blank!</li>', '2' => '<li>Could not connect to the database server at \'' . (isset ($_POST['mysql_host']) ? htmlspecialchars (trim ($_POST['mysql_host'])) : (isset ($_SESSION['mysql_host']) ? $_SESSION['mysql_host'] : 'empty')) . '\' with the supplied username and password.<br>Are you sure the hostname and user details are correct?</li>', '3' => '<li>Could not select the database \'' . (isset ($_POST['mysql_db']) ? htmlspecialchars (trim ($_POST['mysql_db'])) : (isset ($_SESSION['mysql_db']) ? $_SESSION['mysql_db'] : 'empty')) . '\'.<br>Are you sure it exists and the specified username and password have access to it?</li>', '4' => '<li>The passwords you entered do not match.</li>');
    if (strtoupper ($_SERVER['REQUEST_METHOD']) == 'POST')
    {
      $username = htmlspecialchars (trim ($_POST['username']));
      $password = trim ($_POST['password']);
      $password2 = trim ($_POST['password2']);
      $pincode = trim ($_POST['pincode']);
      $email = htmlspecialchars (trim ($_POST['email']));
      if (((((empty ($username) OR empty ($password)) OR empty ($password2)) OR empty ($pincode)) OR empty ($email)))
      {
        $error[] = $errors['1'];
      }

      if ($password != $password2)
      {
        $error[] = $errors['4'];
      }

      if (0 < count ($error))
      {
        foreach ($error as $err)
        {
          $_step_6_contents .= $err;
        }
      }
      else
      {
        $values = array ('username' => $username, 'password' => $password, 'pincode' => $pincode, 'email' => $email);
        insert_session ($values);
        insert_admin ();
        redirect ('Administrator Account has been saved successfully.', 'install.php?step=7');
      }
    }

    $_step_6_contents .= '
	<form method="post" action="install.php?step=6" name="save_admin" id="save_admin">
	<input type="hidden" name="step" value="6">
	<table border="0" width="100%" align="left" cellpadding="4">
	';
    $_step_6_contents .= '
	  <tr>
	   <td align="right" class="subheader"><div align="right">Username: </div></td>
	   <td><input name="username" id="input" type="text" value="' . (isset ($_POST['username']) ? htmlspecialchars ($_POST['username']) : 'admin') . '" onblur="if (this.value == \'\') this.value = \'Admin\';" onfocus="if (this.value == \'Admin\') this.value = \'\';" size="50"></td></tr>';
    $_step_6_contents .= '
	  <tr>
	   <td align="right" class="subheader"><div align="right">Password: </div></td>
	   <td><input name="password" id="input" type="password" value="" size="50"></td></tr>';
    $_step_6_contents .= '
	  <tr>
	   <td align="right" class="subheader"><div align="right">Re-Type Password: </div></td>
	   <td><input name="password2" id="input" type="password" value="" size="50"></td></tr>';
    $_step_6_contents .= '
	  <tr>
	   <td align="right" class="subheader"><div align="right">Pincode: </div></td>
	   <td><input name="pincode" id="input" type="password" value="" size="50"></td></tr>';
    $_step_6_contents .= '
	  <tr>
	   <td align="right" class="subheader"><div align="right">Email Address: </div></td>
	   <td><input name="email" id="input" type="text" value="' . (isset ($_POST['email']) ? htmlspecialchars ($_POST['email']) : '') . '" size="50"></td></tr>';
    $_step_6_contents .= '
	<tr>
	 <td align="right" colspan="2">
	 <table width="100%" border="0" cellpadding="4" cellspacing="0" align="center"><tr><td class="subheader"><span style="float: right"><input type="submit" value="NEXT" class=button></td></tr></table>
	 </td></tr>
	 </form></table>';
    echo html_header ('Welcome to the installation wizard for ' . SCRIPT_VERSION, '
	' . $_step_6_contents . '
	', 'Administrator Setup');
    echo html_footer ();
  }

  function step_7 ()
  {

   $_step_7_contents = SCRIPT_VERSION . ' has successfully been installed and configured correctly. The bvlist Group thanks you for your support and we hope to see you around the community forums if you need help or wish to become a part of the bvlist community. <br><br><div class=warnbox>After a successful login, please goto settings panel and configurate your tracker otherwise TS SE won\'t work correctly! (first update all cache files, inside settingspanel)<br><br>Click <a href="http://' . INSTALL_URL . '">here</a> to login.<br><br>DO NOT FORGET TO DELETE INSTALL FOLDER !!!<br><br>if you like what i do then please donate<br>greets from Lateam<br><br><center><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="GU36Y5HC95UPY">
<input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
<img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
</form>
</center>';
    echo html_header ('Welcome to the installation wizard for ' . SCRIPT_VERSION, '
	' . $_step_7_contents . '
	', 'Finish Setup');
    echo html_footer ();
  }

  @error_reporting (0);
  @ini_set ('error_reporting', '0');
  @ini_set ('display_errors', '0');
  @ini_set ('display_startup_errors', '0');
  @ini_set ('ignore_repeated_errors', '1');
  @ini_set ('log_errors', '0');
  @set_magic_quotes_runtime (0);
  @ignore_user_abort (1);
  if ((function_exists ('set_time_limit') AND get_cfg_var ('safe_mode') == 0))
  {
    @set_time_limit (120);
  }

  @ini_set ('session.gc_maxlifetime', '3600');
  @ini_set ('short_open_tag', 1);
  @session_cache_expire (90);
  @session_name ('TSSE_Session');
  @session_start ();
  define ('INSTALL_VERSION', '1.1');
  define ('SHORT_SCRIPT_VERSION', '5.6');
  define ('SCRIPT_VERSION', 'TS Special Edition v.5.6 (nulled by aser, Special Thanks To: DrNet, MrDecoder,Lateam,lafouine022, Fynnon - wWw.BvList.CoM)');
  define ('INSTALL_URL', (!empty ($_SERVER['SERVER_NAME']) ? replace_url ($_SERVER['SERVER_NAME']) : (!empty ($_SERVER['HTTP_HOST']) ? replace_url ($_SERVER['HTTP_HOST']) : '')));
  define ('INSTALL_IP', (!empty ($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : (!empty ($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '')));
  define ('THIS_ROOT_PATH', './');
  define ('ROOT_PATH', './../');

  $SafeModeCheck = new CheckPHPSafeMode ();
  if (file_exists ('install.lock'))
  {
    critical_error ('Please delete install/install.lock file to continue installation.');
  }

  $__step = (isset ($_POST['step']) ? intval ($_POST['step']) : (isset ($_GET['step']) ? intval ($_GET['step']) : 0));
  $_banned = array ('xam', 'xam');
  foreach ($_banned as $_ban)
  {
    if ((@preg_match ('' . '/\\b' . $_ban . '\\b/i', INSTALL_URL) OR @preg_match ('' . '/\\b' . $_ban . '\\b/i', INSTALL_IP)))
    {
      critical_error ('Security Error!');
      continue;
    }
  }

  if (!ini_get ('allow_url_fopen'))
  {
    critical_error ('Configuration Error: allow_url_fopen must be turned on for this script to work!');
  }




  switch ($__step)
  {
    case 0:
    {
      step_0 ();
      exit ();
      break;
    }

    case 1:
    {
      step_1 ();
      exit ();
      break;
    }

    case 2:
    {
      step_2 ();
      exit ();
      break;
    }

    case 3:
    {
      step_3 ();
      exit ();
    }

    case 4:
    {
      step_4 ();
      exit ();
      break;
    }

    case 5:
    {
      step_5 ();
      exit ();
      break;
    }

    case 6:
    {
      step_6 ();
      exit ();
      break;
    }

    case 7:
    {
      step_7 ();
      exit ();
      break;
    }

    default:
    {
      step_0 ();
      exit ();
      break;
    }
  }

?>
