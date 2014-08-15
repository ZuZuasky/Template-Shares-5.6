<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function get_country_data ()
  {
    global $id;
    unset ($GLOBALS[country]);
    $query = sql_query ('SELECT * FROM countries WHERE id = ' . sqlesc ($id));
    if (mysql_num_rows ($query) == 0)
    {
      stderr ('Error', 'No country with this id!');
    }
    else
    {
      $arr = mysql_fetch_array ($query);
    }

    $GLOBALS['country'] = $arr;
  }

  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('MC_VERSION', 'New Manage Countries Mod v.0.2 by xam');
  $action = (isset ($_POST['action']) ? htmlspecialchars ($_POST['action']) : (isset ($_GET['action']) ? htmlspecialchars ($_GET['action']) : 'show'));
  $do = (isset ($_POST['do']) ? htmlspecialchars ($_POST['do']) : (isset ($_GET['do']) ? htmlspecialchars ($_GET['do']) : ''));
  $name = (isset ($_POST['name']) ? htmlspecialchars ($_POST['name']) : (isset ($_GET['name']) ? htmlspecialchars ($_GET['name']) : ''));
  $flagpic = (isset ($_POST['flagpic']) ? htmlspecialchars ($_POST['flagpic']) : (isset ($_GET['flagpic']) ? htmlspecialchars ($_GET['flagpic']) : ''));
  $id = (isset ($_POST['id']) ? intval ($_POST['id']) : (isset ($_GET['id']) ? intval ($_GET['id']) : ''));
  if (($action != 'show' AND $action != 'new'))
  {
    int_check ($id, true);
  }

  if (($action == 'edit' AND empty ($do)))
  {
    get_country_data ();
    stdhead ('Edit Country for ' . $country['name']);
    echo '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">';
    echo '<tbody><tr><td><table class="tback" border="0" cellpadding="6" cellspacing="0" width="100%"><tbody><tr><td class="colhead" colspan="4" align="center">Edit Country for ' . $country['name'] . '</td></tr>';
    echo '<tr class="subheader"><td width="10%" align="center">Country ID</td><td width="40%" align="left">Country Name</td><td width="50%" align="center">Country Flag</td></tr><tr>';
    $hidden_values = array ('<input type="hidden" name="id" value="' . $id . '">', '<input type="hidden" name="action" value="edit">', '<input type="hidden" name="do" value="save">');
    $values = array ('<td align="center">' . $id . '</td>', '<td align="left"><input type="text" name="name" value="' . $country['name'] . '" id="specialboxn"></td>', '<td align="left"><img src="' . $BASEURL . '/' . $pic_base_url . 'flag/' . $country['flagpic'] . '" border="0" title="' . $country['name'] . '" alt="' . $country['name'] . '" style="vertical-align: middle;"> <input type="text" name="flagpic" value="' . $country['flagpic'] . '" id="specialboxn">');
    _form_open_ ($values, $hidden_values);
    _form_close_ ();
    echo '</td></tr></table></table>';
    stdfoot ();
    return 1;
  }

  if (($action == 'edit' AND $do == 'save'))
  {
    get_country_data ();
    if ((empty ($name) OR empty ($flagpic)))
    {
      stderr ('Error', 'Don\'t leave any fields blank!');
    }

    $path = TSDIR . '/' . $pic_base_url . 'flag/' . $flagpic;
    if (!@file_exists ($path))
    {
      stderr ('Error', 'There is no country flag under "' . $path . '"');
    }

    (sql_query ('UPDATE countries SET name = ' . sqlesc ($name) . ', flagpic = ' . sqlesc ($flagpic) . ' WHERE id = ' . sqlesc ($id)) OR sqlerr (__FILE__, 60));
    redirect ('admin/index.php?act=country&#c' . $id);
    return 1;
  }

  if (($action == 'delete' AND empty ($do)))
  {
    stderr ('Sanity Check', 'Are you sure you want to delete the country? <a href="' . $_this_script_ . '&action=delete&id=' . $id . '&do=delete">yes</a> / <a href="' . $_this_script_ . '">no</a>', false);
    return 1;
  }

  if (($action == 'delete' AND $do == 'delete'))
  {
    sql_query ('DELETE FROM countries WHERE id = ' . sqlesc ($id) . ' LIMIT 1');
    redirect ('admin/index.php?act=country');
    return 1;
  }

  if (($action == 'new' AND $do == 'save'))
  {
    if ((empty ($name) OR empty ($flagpic)))
    {
      stderr ('Error', 'Dont leave any fields blank!');
    }

    $path = TSDIR . '/' . $pic_base_url . 'flag/' . $flagpic;
    if (!@file_exists ($path))
    {
      stderr ('Error', 'There is no country flag under "' . $path . '"');
    }

    sql_query ('INSERT INTO countries (name,flagpic) VALUES (' . sqlesc ($name) . ', ' . sqlesc ($flagpic) . ')');
    $i_id = mysql_insert_id ();
    redirect ('admin/index.php?act=country&#c' . $i_id);
    return 1;
  }

  if (($action == 'new' AND empty ($do)))
  {
    stdhead ('Register A New Country');
    echo '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">';
    echo '<tbody><tr><td><table class="tback" border="0" cellpadding="6" cellspacing="0" width="100%"><tbody><tr><td class="colhead" colspan="4" align="center">Register A New Country</td></tr>';
    echo '<tr class="subheader"><td width="50%" align="left">Country Name</td><td width="50%" align="center">Country Flag</td></tr><tr>';
    $hidden_values = array ('<input type="hidden" name="action" value="new">', '<input type="hidden" name="do" value="save">');
    $values = array ('<td align="left"><input type="text" name="name" value="" id="specialboxn"></td>', '<td align="left"><input type="text" name="flagpic" value="" id="specialboxn">');
    _form_open_ ($values, $hidden_values);
    _form_close_ ();
    echo '</td></tr></table></table>';
    stdfoot ();
    return 1;
  }

  if ($action == 'show')
  {
    stdhead ('Manage Countries');
    echo '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">';
    echo '<tbody><tr><td><table class="tback" border="0" cellpadding="6" cellspacing="0" width="100%"><tbody><tr><td class="colhead" colspan="4" align="center">Registered Countries (click <a href="' . $_this_script_ . '&action=new">here</a> to register a new country)</td></tr>';
    echo '<tr class="subheader"><td width="20%" align="center">Country ID</td><td width="40%" align="left">Country Name</td><td width="20%" align="center">Country Flag</td><td width="20%" align="center">Action</td></tr>';
    $query = sql_query ('SELECT * FROM countries');
    while ($res = mysql_fetch_array ($query))
    {
      echo '<tr><td align="center"><a name="c' . $res['id'] . '"></a>' . $res['id'] . '</td><td align="left">' . $res['name'] . '</td><td align="center"><img src="' . $BASEURL . '/' . $pic_base_url . 'flag/' . $res['flagpic'] . '" border="0" title="' . $res['name'] . '" alt="' . $res['name'] . '"></td><td align="center"><a href="' . $_this_script_ . '&action=edit&id=' . $res['id'] . '">Edit</a> / <a href="' . $_this_script_ . '&action=delete&id=' . $res['id'] . '">Delete</a></td></tr>';
    }

    echo '</table></table>';
    stdfoot ();
  }

?>
