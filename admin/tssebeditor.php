<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function code ($block)
  {
    global $PHP_SELF;
    switch ($block)
    {
      case 'header':
      {
        echo '
			<html>
			<head>
			<title>Basic File Management</title>
			<script type="text/javascript">
				function jumpto(url,message)
				{
					if (typeof message != "undefined")
					{
						document.getElementById("jumpto").style.display = "block"; 
					}
					window.location = url;
				};
			</script>
			</head>
			<body>
			<table border=0 cellspacing=0 cellpadding=10 width=100% align=center>
			<tr><td>
			<table border=1 cellspacing=0 cellpadding=10 width=100% align=center>
			<tr><td class="thead" align=center colspan="2">Basic File Management </td></tr>
			<tr><td class="main" colspan="2">';
        break;
      }

      case 'footer':
      {
        echo '
			</td></tr>			
			</table>
			</td></tr></table></body></html>';
        break;
      }

      case 'qe':
      {
        echo '<p align=right><input type="button" class="hoptobutton" value="File Listing" onClick="jumpto(\'' . $PHP_SELF . '?do=filemanagement&action=editor&qe=dir&dir=' . $GLOBALS[dir] . '&s=' . $GLOBALS[s] . '\')"> <input type="button" class="hoptobutton" value="Current dir: ' . realpath ($GLOBALS[dir]) . '"></p>';
        break;
      }

      default:
      {
        echo '<br /><b>error</b><br />';
      }
    }

  }

  function qe ()
  {
    global $HTTP_POST_FILES;
    if (!$GLOBALS[qe])
    {
      $GLOBALS[dir] = '.';
    }

    if (!$GLOBALS[dir])
    {
      $GLOBALS[dir] = '.';
    }

    code ('qe');
    if (!$GLOBALS[qe])
    {
      directory ('.');
    }

    if ($GLOBALS[qe] == 'dir')
    {
      directory ($GLOBALS[dir]);
    }

    if ($GLOBALS[qe] == 'edit')
    {
      edit ($GLOBALS[file]);
    }

    if ($GLOBALS[qe] == 'save')
    {
      save ($GLOBALS[file]);
    }

    if ($GLOBALS[qe] == 'mkdir')
    {
      newdir ($GLOBALS[dir] . '/' . $GLOBALS[file]);
    }

    if ($GLOBALS[qe] == 'rename')
    {
      ren ($GLOBALS[file]);
    }

    if ($GLOBALS[qe] == 'del')
    {
      del ($GLOBALS[file]);
    }

    if ($GLOBALS[qe] == 'copy')
    {
      copyf ($GLOBALS[file], $GLOBALS[newname]);
    }

    if ($GLOBALS[qe] == 'upload')
    {
      if (minimum_version ('4.1.0'))
      {
        $uploadedfile = $_FILES['userfile']['tmp_name'];
        $filename = $_FILES['userfile']['name'];
      }
      else
      {
        $uploadedfile = $HTTP_POST_FILES['userfile']['tmp_name'];
        $filename = $HTTP_POST_FILES['userfile']['name'];
      }

      upload ($uploadedfile, $filename);
    }

  }

  function directory ($mydir)
  {
    global $PHP_SELF;
    unset ($subdirs);
    unset ($files);
    $dir = opendir ($mydir);
    print '<table border=1 cellspacing=0 cellpadding=5 width=100%>';
    print '<tr><td class="subheader">Directory / File</td><td class="subheader" align="center">Size</td><td class="subheader" colspan="4" align="center">Available Actions</td></tr>';
    while ($file = readdir ($dir))
    {
      $filename = $mydir . '/' . $file;
      $dirname = $mydir;
      if ((is_dir ($filename) AND $file != '.'))
      {
        $subdirs[count ($subdirs)] = $file;
      }

      if (!is_dir ($filename))
      {
        $files[count ($files)] = $file;
        continue;
      }
    }

    sort ($files);
    sort ($subdirs);
    $i = 0;
    while ($i < count ($subdirs))
    {
      $dirname = $mydir . '/' . $subdirs[$i];
      print '<tr><td class="filenames">';
      print '<img src="images/folder.png" style="vertical-align: middle;" border="0" alt="current folder" title="current folder"> <a href="' . $PHP_SELF . '?do=filemanagement&action=editor&qe=dir&dir=' . $dirname . '&s=' . $GLOBALS[s] . '"><font color="red" size="3"><strong>' . $subdirs[$i] . '</strong></font></a>';
      if ($subdirs[$i] == '..')
      {
        print ' (parent dir)';
      }

      print '</td><td class="filelinks"> </td>';
      print '<td class="filelinks" align="center" colspan=4><a href="' . $PHP_SELF . '?do=filemanagement&action=editor&qe=dir&dir=' . $dirname . '&s=' . $GLOBALS[s] . '"><img src="images/enter.png" border="0" alt="edit this file" title="enter this directory"></a></td>';
      print '</tr>';
      ++$i;
    }

    $i = 0;
    while ($i < count ($files))
    {
      $filename = $mydir . '/' . $files[$i];
      print '<tr><td class="filenames">';
      print '<a href="' . $PHP_SELF . '?do=filemanagement&action=editor&qe=edit&file=' . $filename . '&dir=' . $mydir . '&s=' . $GLOBALS[s] . '"><b>' . $files[$i] . '</b></a><br />';
      print '</td><td class="filelinks" align="center">' . mksize (filesize ($filename)) . '</td>';
      print '<td class="filelinks" align="center"><a href="' . $PHP_SELF . '?do=filemanagement&action=editor&qe=edit&dir=' . $mydir . '&file=' . $filename . '&s=' . $GLOBALS[s] . '"><img src="images/edit.png" border="0" alt="edit this file" title="edit this file"></a></td>';
      print '<td class="filelinks" align="center"><a href="' . $PHP_SELF . '?do=filemanagement&action=editor&qe=rename&dir=' . $mydir . '&file=' . $filename . '&s=' . $GLOBALS[s] . '"><img src="images/rename.png" border="0" alt="edit this file" title="rename this file"></a></td><td class="filelinks" align="center"><a href="' . $PHP_SELF . '?do=filemanagement&action=editor&qe=del&dir=' . $mydir . '&file=' . $filename . '&s=' . $GLOBALS[s] . '"><img src="images/delete.png" border="0" alt="delete this file" title="delete this file"></a></td>';
      print '<td class="filelinks" align="center"><a href="' . $PHP_SELF . '?do=filemanagement&action=editor&qe=copy&dir=' . $mydir . '&file=' . $filename . '&s=' . $GLOBALS[s] . '"><img src="images/copy.png" border="0" alt="edit this file" title="copy this file"></a></td></tr>';
      ++$i;
    }

    print '</table>';
    closedir ($dir);
    echo '<hr color=\'black\' size=\'1\'><form action=\'' . $PHP_SELF . '?do=filemanagement&action=editor\' method=\'post\'>' . 'Create a new file: <input type="text" name="file" value="file.html" id="specialboxn" class="bginput"><input type="hidden" name="s" value="' . $GLOBALS[s] . '"><input type="hidden" name="qe" value="edit"><input type="hidden" name="dir" value="' . $mydir . '"><input type="hidden" name="create" value="1"><input type="submit" value="create"></form>';
    echo '<form action="' . $PHP_SELF . '?do=filemanagement&action=editor" method="post">Create a new directory: <input type="text" name="file" value="newdir" id="specialboxn" class="bginput"><input type="hidden" name="s" value="' . $GLOBALS[s] . '"><input type="hidden" name="qe" value="mkdir"><input type="hidden" name="dir" value="' . $mydir . '"><input type="hidden" name="create" value="1"><input type="submit" value="make new directory"></form>';
    echo '' . '<br />
<form action="' . $PHP_SELF . '?do=filemanagement&action=editor" method="post" enctype="multipart/form-data">
<input type="hidden" name="s" value="' . $GLOBALS[s] . '"><input type="hidden" name="qe" value="upload"><input type="hidden" name="dir" value="' . $mydir . '"><input type="hidden" name="MAX_FILE_SIZE" value="1000000">Upload a file: <input name="userfile" type="file"><input type="submit" value="upload"></form>';
  }

  function edit ($file)
  {
    global $PHP_SELF;
    if (is_dir ($file))
    {
      directory ($file);
      chdir ($file);
      return 0;
    }

    global $create;
    if ($create)
    {
      $file = $GLOBALS[dir] . '/' . $file;
    }

    $exists = file_exists ($file);
    if (($create != 1 OR $exists))
    {
      $fp = fopen ($file, 'r');
      $con = fread ($fp, filesize ($file));
      fclose ($fp);
    }

    echo '<tr><td class=\'subheader\'>Editing File: <font color=\'red\'>' . basename ($file) . '</font></td></tr>
';
    echo '' . '<tr><td><form action=\'' . $PHP_SELF . '?do=filemanagement&action=editor&qe=save\' method=\'post\'>
';
    echo '' . '<input type=\'hidden\' name=\'file\' value=\'' . $file . '\'>
';
    echo '<textarea rows=\'60\' cols=\'150\' name=\'txt\' id=\'specialboxta\'>
';
    echo $con;
    echo '</textarea>
';
    echo '<input type="hidden" name="s" value="' . $GLOBALS[s] . '">' . '
';
    echo '<input type="hidden" name="qe" value="save">' . '
';
    echo '<input type="hidden" name="dir" value="' . $GLOBALS[dir] . '">' . '
';
    echo '<input type=\'submit\' value=\'save\' class=button>';
    echo '</form></td></tr>';
  }

  function save ($file)
  {
    global $PHP_SELF;
    $GLOBALS[txt] = stripslashes ($GLOBALS[txt]);
    $fp = fopen ($file, 'w');
    fwrite ($fp, $GLOBALS[txt]);
    fclose ($fp);
    echo '<br /><b>saved</b><br /><a href="' . $PHP_SELF . '?do=filemanagement&action=editor&qe=dir&dir=' . $GLOBALS[dir] . '&s=' . $GLOBALS[s] . '">back to file listing</a><br />';
  }

  function converthtml ($html, $direction)
  {
    switch ($direction)
    {
      case 1:
      {
        $html = eregi_replace ('</text' . 'area>', '&lt;/text' . 'area&gt;', $html);
        break;
      }

      case 2:
      {
        $html = eregi_replace ('&lt;/text' . 'area&gt;', '</text' . 'area>', $html);
        break;
      }

      default:
      {
        echo '<br />an error has occured in calling the convertHTML function<br />';
      }
    }

    return $html;
  }

  function newdir ($dirname)
  {
    global $PHP_SELF;
    if (!mkdir ($dirname, 511))
    {
      echo '<br /><b>error: failed to create directory -- check your permissions</b><br />';
      return 0;
    }

    echo '<br /><b>directory created</b><br />do you want to:<br /><a href="' . $PHP_SELF . '?do=filemanagement&action=editor&qe=dir&dir=' . $GLOBALS[dir] . '&s=' . $GLOBALS[s] . '">return to the directory you were in before?</a><br /><a href="' . $PHP_SELF . '?do=filemanagement&action=editor&qe=dir&dir=' . $dirname . '&s=' . $GLOBALS[s] . '">enter the directory you just created?</a><br />';
  }

  function ren ($file)
  {
    global $PHP_SELF;
    if (!$GLOBALS[rento])
    {
      echo '
			<form action="' . $PHP_SELF . '?do=filemanagement&action=editor" method="post"><input type="hidden" name="qe" value="rename"><input type="hidden" name="s" value="' . $GLOBALS[s] . '"><input type="hidden" name="file" value="' . $GLOBALS[file] . '"><input type="hidden" name="dir" value="' . $GLOBALS[dir] . '">
			<tr><td class="subheader" colspan="2"><b>Rename File</b></td></tr>
			<tr><td>Original Filename:</td><td><font color="red">' . basename ($file) . '</font></td></tr>
			<tr><td>Enter New Filename:</td><td><input type="text" name="rento" value="' . $GLOBALS[dir] . '/' . basename ($file) . '" id="specialboxg"></td></tr>
			<tr><td>Action</td><td><input type="submit" value="rename" class=button></td></tr></form>';
      return 0;
    }

    if (!rename ($file, $GLOBALS[rento]))
    {
      echo '<br /><b>Rename Failed!</b>: Check your file permissions and filenames!<br />';
      return 0;
    }

    echo '<br /><b>File Renamed!</b>:<br /><a href="' . $PHP_SELF . '?do=filemanagement&action=editor&qe=dir&dir=' . $GLOBALS[dir] . '&s=' . $GLOBALS[s] . '">Return to file listing</a><br />';
  }

  function del ($file)
  {
    global $PHP_SELF;
    if (!$GLOBALS[confirm])
    {
      echo '<tr><td class="subheader" align="center">Delete File: <font color="red">' . basename ($file) . '</font></td></tr>
		<tr><td align="center"><b>Are you sure you want to delete following file? <font color="red">' . $file . '</font></b><br /><br />
		<p><a href="' . $PHP_SELF . '?do=filemanagement&action=editor&qe=del&file=' . $file . '&dir=' . $GLOBALS[dir] . '&s=' . $GLOBALS[s] . '&confirm=1"><font color="red">Yes, I am sure I would like to delete this file.</font></a></p>
		<p><a href="' . $PHP_SELF . '?do=filemanagement&action=editor&qe=dir&dir=' . $GLOBALS[dir] . '&s=' . $GLOBALS[s] . '">No, I would not like to delete this file</a></p></td></tr>';
      return 0;
    }

    if (!unlink ($file))
    {
      echo '<br /><b>File deletion failed!</b>: Check your file permissions and given filenames: <br /><font color="gray">' . $file . '</font><br />';
      return 0;
    }

    echo '<br /><b>File ' . $file . ' deleted</b>:<br /><a href="' . $PHP_SELF . '?do=filemanagement&action=editor&qe=dir&dir=' . $GLOBALS[dir] . '&s=' . $GLOBALS[s] . '">Return to file listing</a><br />';
  }

  function copyf ($file, $newname)
  {
    global $PHP_SELF;
    if (!$newname)
    {
      echo '
			<form action="' . $PHP_SELF . '?do=filemanagement&action=editor&" method="post"><input type="hidden" name="qe" value="copy"><input type="hidden" name="s" value="' . $GLOBALS[s] . '"><input type="hidden" name="dir" value="' . $GLOBALS[dir] . '"><input type="hidden" name="file" value="' . $GLOBALS[file] . '">
			<tr><td class="subheader" colspan="2"><b>Copy File</b></tr></td>
			<tr><td>Source Filename:</td><td>' . basename ($file) . '</td></tr>
			<tr><td>Destination filename:</td><td><input type="text" name="newname" id="specialboxg" value="' . $GLOBALS[dir] . '/' . basename ($file) . '"></td></tr>
			<tr><td>Action</td><td><input type="submit" value="copy" class=button></td></tr></form>';
      return 0;
    }

    if (!copy ($file, $newname))
    {
      echo '<br /><b>File copy failed!</b>: Check your directory and file  permissions and filenames!<br />';
      return 0;
    }

    echo '<br /><b>File ' . $file . ' copied to ' . $newname . '</b><br /><a href="' . $PHP_SELF . '?do=filemanagement&action=editor&qe=dir&dir=' . $GLOBALS[dir] . '&s=' . $GLOBALS[s] . '">Return to file listing</a><br />';
  }

  function minimum_version ($vercheck)
  {
    $minver = explode ('.', $vercheck);
    $curver = explode ('.', phpversion ());
    if ((($curver[0] < $minver[0] OR ($curver[0] == $minver[0] AND $curver[1] < $minver[1])) OR (($curver[0] == $minver[0] AND $curver[1] == $minver[1]) AND $curver[2][0] < $minver[2][0])))
    {
      return false;
    }

    return true;
  }

  function updir ($mydir)
  {
    $newdir = substr ($mydir, 0, strrpos ($mydir, '/'));
    return $newdir;
  }

  function upload ($infile, $inname)
  {
    $result = copy ($infile, $GLOBALS['dir'] . '/' . $inname);
    if ($result)
    {
      echo '<br /><b>file uploaded</b><br />';
    }

    directory ($GLOBALS['dir']);
  }

  if (!defined ('SETTING_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('TSSEBEDITOR_VERSION', 'v.0.3');
  if ($HTTP_COOKIE_VARS)
  {
    while (list ($key, $val) = @each ($HTTP_COOKIE_VARS))
    {
      $GLOBALS[$key] = $val;
    }
  }

  if ($HTTP_GET_VARS)
  {
    while (list ($key, $val) = @each ($HTTP_GET_VARS))
    {
      $GLOBALS[$key] = $val;
    }
  }

  if ($HTTP_POST_VARS)
  {
    while (list ($key, $val) = @each ($HTTP_POST_VARS))
    {
      $GLOBALS[$key] = $val;
    }
  }

  if ($HTTP_POST_FILES)
  {
    while (list ($key, $val) = @each ($HTTP_POST_FILES))
    {
      $GLOBALS[$key] = $val;
    }
  }

  if ($HTTP_SESSION_VARS)
  {
    while (list ($key, $val) = @each ($HTTP_SESSION_VARS))
    {
      $GLOBALS[$key] = $val;
    }
  }

  $GLOBALS['PHP_SELF'] = $_SERVER['SCRIPT_NAME'];
  code ('header');
  qe ();
  code ('footer');
?>
