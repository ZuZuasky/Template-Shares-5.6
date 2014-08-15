<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function show ($aid, $subject, $message, $added, $by, $class)
  {
    global $SITENAME;
    global $BASEURL;
    $defaulttemplate = ts_template ();
    ob_start ();
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en" />
<head>
<meta http-equiv="Content-Type" content="text/html; charset=';
    echo $charset;
    echo '" />
<link rel="stylesheet" href="';
    echo $BASEURL;
    echo '/include/templates/';
    echo $defaulttemplate;
    echo '/style/style.css" type="text/css" media="screen" />
<title>';
    echo $SITENAME;
    echo ' - Announcement: ';
    echo $subject;
    echo ' - ';
    echo $added;
    echo ' - ';
    echo $by;
    echo '</title>

';
    echo '<s';
    echo 'cript language="JavaScript1.2">

// Drop-in content box- By Dynamic Drive
// For full source code and more DHTML scripts, visit http://www.dynamicdrive.com
// This credit MUST stay intact for use

var ie=document.all
var dom=document.getElementById
var ns4=document.layers
var calunits=document.layers? "" : "px"

var bouncelimit=32 //(must be divisible by 8)
var direction="up"

functi';
    echo 'on initbox(){
if (!dom&&!ie&&!ns4)
return
crossobj=(dom)?document.getElementById("dropin").style : ie? document.all.dropin : document.dropin
scroll_top=(ie)? truebody().scrollTop : window.pageYOffset
crossobj.top=scroll_top-250+calunits
crossobj.visibility=(dom||ie)? "visible" : "show"
dropstart=setInterval("dropin()",50)
}

function dropin(){
scroll_top=(ie)? truebody().scrollTop : win';
    echo 'dow.pageYOffset
if (parseInt(crossobj.top)<100+scroll_top)
crossobj.top=parseInt(crossobj.top)+40+calunits
else{
clearInterval(dropstart)
bouncestart=setInterval("bouncein()",50)
}
}

function bouncein(){
crossobj.top=parseInt(crossobj.top)-bouncelimit+calunits
if (bouncelimit<0)
bouncelimit+=8
bouncelimit=bouncelimit*-1
if (bouncelimit==0){
clearInterval(bouncestart)
}
}

functio';
    echo 'n dismissbox(){
if (window.bouncestart) clearInterval(bouncestart)
crossobj.visibility="hidden"
window.location="';
    echo $BASEURL;
    echo '/admin/announcements.php";
}

function redo(){
bouncelimit=32
direction="up"
initbox()
}

function truebody(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}
window.onload=initbox
</script>

</head>

<body>
<!-- announcement start #';
    echo $aid;
    echo ' -->
<div id="dropin" style="position:absolute;visibility:hidden;left:300px;top:100px;width:500px;height:100px;background-color:#F5F5F5">
<table border="0" cellpadding="0" cellspacing="0" width="650">
<tbody><tr><td class="none" style="padding: 2px 0 0 10px; background: red">
<font color=black><b>ANNOUNCEMENT TITLE:</b> ';
    echo $subject;
    echo '</font> -- <b>CREATED ON:</b> ';
    echo $added;
    echo ' -- <b>BY:</b> ';
    echo $by;
    echo '</b> -- <b>TO CLASS:</b> ';
    echo $class;
    echo '</font></td>
<td width="50" align="right" class="none" style="padding: 2px; background: red"><a href="#" onClick="dismissbox();return false"><img src=';
    echo $BASEURL;
    echo '/';
    echo $pic_base_url;
    echo 'close.jpg></a></td></tr>
<tr><td colspan="2" class=none width="650" style="padding: 0 0 0 10px;">
<p>
';
    echo format_comment ($message);
    echo '</p>
</td></tr></tbody></table>
</div>
<!-- announcement end #';
    echo $aid;
    echo '-->
</body>
</html>
';
    ob_end_flush ();
  }

  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('NcodeImageResizer', true);
  define ('B_VERSION', 'v.0.6');
  unset ($action);
  unset ($do);
  $action = (isset ($_POST['action']) ? htmlspecialchars ($_POST['action']) : (isset ($_GET['action']) ? htmlspecialchars ($_GET['action']) : 'show'));
  $do = (isset ($_POST['do']) ? htmlspecialchars ($_POST['do']) : (isset ($_GET['do']) ? htmlspecialchars ($_GET['do']) : ''));
  if (($_POST['previewpost'] AND !empty ($_POST['message'])))
  {
    $avatar = get_user_avatar ($CURUSER['avatar']);
    $prvp = '<table border="0" cellspacing="0" cellpadding="4" class="none" width="100%">
	<tr>
	<td class="thead" colspan="2"><strong><h2>' . $lang->global['buttonpreview'] . '</h2></strong></td>
	</tr>
	<tr><td class="tcat" width="20%" align="center" valign="middle">' . $avatar . '</td><td class="tcat" width="80%" align="left" valign="top">' . format_comment ($_POST['message']) . '</td>
	</tr></table><br />';
  }

  if ($action == 'show')
  {
    $countrows = number_format (tsrowcount ('id', 'announcements'));
    $page = 0 + $_GET['page'];
    $perpage = 5;
    list ($pagertop, $pagerbottom, $limit) = pager ($ts_perpage, $countrows, $_SERVER['SCRIPT_NAME'] . '?act=announcements&action=show&');
    stdhead ('Announcements ' . B_VERSION);
    ($res = sql_query ('SELECT * FROM announcements ORDER by added DESC ' . $limit) OR sqlerr (__FILE__, 139));
    $where = array ('New Announcement' => $_SERVER['SCRIPT_NAME'] . '?act=announcements&action=add');
    echo '<tr><td colspan=6 align=left>' . jumpbutton ($where) . '</tr></td>';
    _form_header_open_ ('Announcements');
    echo '<table border=1 cellspacing=0 cellpadding=5 width=100%>';
    echo '<tr><td class=subheader align=center>ID</td><td class=subheader align=left>SUBJECT</td><td class=subheader align=left>MESSAGE</td><td class=subheader align=center>ADDED</td><td class=subheader align=center>MIN.CLASS</td><td class=subheader align=center>ACTION</td></tr>';
    if (1 <= mysql_num_rows ($res))
    {
      require_once INC_PATH . '/functions_mkprettytime.php';
      while ($arr = mysql_fetch_array ($res))
      {
        echo '<tr><td align=center>' . $arr['id'] . '</td><td align=left>' . $arr['subject'] . '</td><td align=left><textarea id=specialboxnn rows=10 READONLY>' . $arr['message'] . '</textarea></td><td align=center>' . $arr['added'] . ' <br />(' . mkprettytime (time () - strtotime ($arr['added'])) . ') <br />by ' . $arr['by'] . '</td><td align=center>' . get_user_class_name ($arr['minclassread']) . '</td><td align=center><a href=' . $_SERVER['SCRIPT_NAME'] . '?act=announcements&action=edit&id=' . $arr['id'] . '>edit</a> / <a href=' . $_SERVER['SCRIPT_NAME'] . '?act=announcements&action=delete&id=' . $arr['id'] . '>delete</a> / <a href=' . $_SERVER['SCRIPT_NAME'] . '?act=announcements&action=see&id=' . $arr['id'] . '>show</a></td></tr>';
      }
    }
    else
    {
      echo '<tr><td colspan=6>Nothing Found..</td></tr>';
    }

    echo $pagerbottom;
    _form_header_close_ ();
  }
  else
  {
    if ($action == 'see')
    {
      $id = (isset ($_GET['id']) ? (int)$_GET['id'] : (int)$_POST['id']);
      int_check ($id, true);
      ($res = sql_query ('SELECT * FROM announcements WHERE id = ' . sqlesc ($id)) OR sqlerr (__FILE__, 163));
      $arr = mysql_fetch_array ($res);
      show ($arr['id'], $arr['subject'], $arr['message'], $arr['added'], $arr['by'], get_user_class_name ($arr['minclassread']));
      exit ();
    }
    else
    {
      if ($action == 'add')
      {
        if (($do == 'save' AND empty ($prvp)))
        {
          $added = get_date_time ();
          $subject = htmlspecialchars_uni ($_POST['subject']);
          $message = trim ($_POST['message']);
          $minclassread = $_POST['minclassread'];
          if (((empty ($subject) OR empty ($message)) OR ($minclassread != '-' AND !is_valid_id ($minclassread))))
          {
            redirect ('admin/index.php?act=announcements&action=add', 'Don\'t leave any fields blank..');
          }

          if ($minclassread == '-')
          {
            $query = 'UPDATE users SET announce_read = \'no\' WHERE enabled = \'yes\' AND status = \'confirmed\'';
            $insert = 'INSERT INTO announcements (subject, message, added, minclassread) VALUES (' . sqlesc ($subject) . ', ' . sqlesc ($message) . ', ' . sqlesc ($added) . ', 0)';
          }
          else
          {
            $query = 'UPDATE users SET announce_read = \'no\' WHERE enabled = \'yes\' AND status = \'confirmed\' AND usergroup = ' . $minclassread;
            $insert = 'INSERT INTO announcements (subject, message, added, minclassread) VALUES (' . sqlesc ($subject) . ', ' . sqlesc ($message) . ', ' . sqlesc ($added) . ', ' . sqlesc ($minclassread) . ')';
          }

          (sql_query ($query) OR sqlerr (__FILE__, 187));
          (sql_query ($insert) OR sqlerr (__FILE__, 188));
          redirect ('admin/index.php?act=announcements', 'The announcement has been added..');
          exit ();
        }

        $selectbox = _selectbox_ (NULL, 'minclassread', true, 'any usergroup (all)', $_POST['minclassread']);
        stdhead ('Announcements ' . B_VERSION);
        define ('IN_EDITOR', true);
        include_once INC_PATH . '/editor.php';
        $str = '<form method="post" name="compose" action="' . $_SERVER['SCRIPT_NAME'] . '">
		<input type="hidden" name="act" value="announcements">
		<input type="hidden" name="action" value="add">
		<input type="hidden" name="do" value="save">';
        if (!empty ($prvp))
        {
          $str .= $prvp;
        }

        $str .= insert_editor (true, $_POST['subject'], $_POST['message'], 'Create Announcement', '' . 'Select Usergroup: ' . $selectbox);
        $str .= '</form>';
        echo $str;
      }
      else
      {
        if ($action == 'delete')
        {
          $id = (isset ($_GET['id']) ? (int)$_GET['id'] : (int)$_POST['id']);
          int_check ($id, true);
          $sure = (string)$_GET['sure'];
          if (!$sure)
          {
            stderr ('Delete Announcement!', 'Sanity check: You are about to delete an Announcement. Click <a href=' . $_SERVER['SCRIPT_NAME'] . '?act=announcements&action=delete&id=' . $id . '&sure=yes>here</a> if you are sure. (<a href="' . $_SERVER['SCRIPT_NAME'] . '?act=announcements">cancel</a>)', false);
          }
          else
          {
            (sql_query ('DELETE FROM announcements WHERE id = ' . sqlesc ($id)) OR sqlerr (__FILE__, 215));
          }

          redirect ('admin/index.php?act=announcements', 'announcement has been deleted..');
        }
        else
        {
          if ($action == 'edit')
          {
            $id = (isset ($_GET['id']) ? (int)$_GET['id'] : (int)$_POST['id']);
            int_check ($id, true);
            if (($do == 'save' AND empty ($prvp)))
            {
              $by = htmlspecialchars_uni ($_POST['by']);
              $subject = htmlspecialchars_uni ($_POST['subject']);
              $message = trim ($_POST['message']);
              $minclassread = $_POST['minclassread'];
              if (((empty ($subject) OR empty ($message)) OR ($minclassread != '-' AND !is_valid_id ($minclassread))))
              {
                redirect ('admin/index.php?act=announcements&action=edit&id=' . $id, 'Don\'t leave any fields blank..');
              }

              (sql_query ('UPDATE announcements SET `by` = ' . sqlesc ($by) . ', subject = ' . sqlesc ($subject) . ', message = ' . sqlesc ($message) . ', minclassread = ' . sqlesc (($minclassread == '-' ? '0' : $minclassread)) . ' WHERE id = ' . sqlesc ($id)) OR sqlerr (__FILE__, 229));
              if ($_POST['reset'] == 'yes')
              {
                if ($minclassread == '-')
                {
                  $query = 'UPDATE users SET announce_read = \'no\' WHERE enabled = \'yes\' AND status = \'confirmed\'';
                }
                else
                {
                  $query = 'UPDATE users SET announce_read = \'no\' WHERE enabled = \'yes\' AND status = \'confirmed\' AND usergroup = ' . $minclassread;
                }

                (sql_query ($query) OR sqlerr (__FILE__, 241));
              }

              redirect ('admin/index.php?act=announcements', 'Update successfull..');
              exit ();
            }

            ($res = sql_query ('SELECT * FROM announcements WHERE id = ' . sqlesc ($id)) OR sqlerr (__FILE__, 246));
            if (mysql_num_rows ($res) == 0)
            {
              stderr ('Error', 'Invalid Link!');
            }
            else
            {
              $arr = mysql_fetch_array ($res);
            }

            $selectbox = '<table border="0" width="100%" cellspacing="0" cellpadding="3">';
            $selectbox .= '<tr><td>Select Usergroup:</td><td>' . _selectbox_ (NULL, 'minclassread', true, 'any usergroup (all)', (empty ($_POST['minclassread']) ? $arr['minclassread'] : $_POST['minclassread'])) . '</td></tr>';
            $selectbox .= '<tr><td>Creator:</td><td><input type="text" name="by" id="specialboxn" maxlength="64" value="' . $arr['by'] . '"></td></tr>';
            $selectbox .= '<tr><td>Mark Unread:</td><td><input type="checkbox" name="reset" value="yes"> check this to mark all users as unread.</td></tr>';
            $selectbox .= '</table>';
            stdhead ('Announcements ' . B_VERSION);
            define ('IN_EDITOR', true);
            include_once INC_PATH . '/editor.php';
            $str = '<form method="post" name="compose" action="' . $_SERVER['SCRIPT_NAME'] . '">
	<input type="hidden" name="act" value="announcements">
	<input type="hidden" name="action" value="edit">
	<input type="hidden" name="do" value="save">
	<input type="hidden" name="id" value="' . $id . '">';
            if (!empty ($prvp))
            {
              $str .= $prvp;
            }

            $str .= insert_editor (true, (empty ($_POST['subject']) ? $arr['subject'] : $_POST['subject']), (empty ($_POST['message']) ? $arr['message'] : $_POST['message']), 'Edit Announcement', $selectbox);
            $str .= '</form>';
            echo $str;
          }
        }
      }
    }
  }

  echo '</table>';
  stdfoot ();
?>
