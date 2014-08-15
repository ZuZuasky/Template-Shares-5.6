<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function update_categories_cache ()
  {
    global $cache;
    $query = sql_query ('SELECT * FROM categories WHERE type = \'c\' ORDER by name,id');
    while ($_c = mysql_fetch_assoc ($query))
    {
      $_ccache[] = $_c;
    }

    $query = sql_query ('SELECT * FROM categories WHERE type = \'s\' ORDER by name,id');
    while ($_c = mysql_fetch_assoc ($query))
    {
      $_ccache2[] = $_c;
    }

    $content = var_export ($_ccache, true);
    $content2 = var_export ($_ccache2, true);
    $_filename = TSDIR . '/' . $cache . '/categories.php';
    $_cachefile = @fopen ('' . $_filename, 'w');
    $_cachecontents = '<?php
/** TS Generated Cache#7 - Do Not Alter
 * Cache Name: Categories
 * Generated: ' . gmdate ('r') . '
*/

';
    $_cachecontents .= '' . '$_categoriesC = ' . $content . ';

';
    $_cachecontents .= '' . '$_categoriesS = ' . $content2 . ';
?>';
    @fwrite ($_cachefile, $_cachecontents);
    @fclose ($_cachefile);
  }

  function getimages ($select = '')
  {
    global $rootpath;
    global $pic_base_url;
    global $table_cat;
    global $BASEURL;
    $dir = TSDIR . '/' . $pic_base_url . $table_cat . '/';
    $imgdir_ = $BASEURL . '/' . $pic_base_url . $table_cat . '/';
    $ext = array ('gif', 'jpg', 'png', 'bmp');
    $str = '
	<select name=\'image\' OnChange=\'javascript:document.forms[0].showimage.src="' . $imgdir_ . '"+this.value+""\'>';
    if ($handle = opendir ($dir))
    {
      while (false !== $file = readdir ($handle))
      {
        if ((($file != '.' AND $file != '..') AND in_array (strtolower (get_extension ($file)), $ext)))
        {
          $str .= '
				<option value="' . $file . '"' . ($file == $select ? ' SELECTED' : '') . '>' . $file . '</option>';
          continue;
        }
      }

      $str .= '
		</select>';
      closedir ($handle);
      return $str;
    }

  }

  function get_category_list ($cid = 0, $selectname = 'cid')
  {
    $categories = '<select name="' . $selectname . '">
	<option value="0">--select category--</option>';
    ($query = sql_query ('SELECT id, name FROM categories WHERE type = \'c\'') OR sqlerr (__FILE__, 82));
    while ($cat = mysql_fetch_assoc ($query))
    {
      $categories .= '<option value="' . intval ($cat['id']) . '"' . ($cid == $cat['id'] ? ' selected="selected"' : '') . '>' . htmlspecialchars_uni ($cat['name']) . '</option>';
    }

    $categories .= '</select>';
    return $categories;
  }

  function show__errors ()
  {
    global $_errors;
    global $lang;
    if (0 < count ($_errors))
    {
      $errors = implode ('<br />', $_errors);
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

  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('C_VERSION', '1.0 by xam');
  $do = (isset ($_POST['do']) ? htmlspecialchars ($_POST['do']) : (isset ($_GET['do']) ? htmlspecialchars ($_GET['do']) : ''));
  $what = (isset ($_POST['what']) ? htmlspecialchars ($_POST['what']) : (isset ($_GET['what']) ? htmlspecialchars ($_GET['what']) : ''));
  $id = (isset ($_POST['id']) ? intval ($_POST['id']) : (isset ($_GET['id']) ? intval ($_GET['id']) : ''));
  $cid = (isset ($_POST['cid']) ? intval ($_POST['cid']) : (isset ($_GET['cid']) ? intval ($_GET['cid']) : ''));
  $_errors = array ();
  if ($do == 'new')
  {
    if ($what == 'save')
    {
      $name = htmlspecialchars_uni ($_POST['name']);
      $image = htmlspecialchars_uni ($_POST['image']);
      $cat_desc = htmlspecialchars_uni ($_POST['cat_desc']);
      $vip = ($_POST['vip'] == 'yes' ? 'yes' : 'no');
      $type = (0 < $cid ? 's' : 'c');
      if ((empty ($name) OR empty ($image)))
      {
        $_errors[] = 'Dont leave any fields blank!';
      }
      else
      {
        (sql_query ('INSERT INTO categories (name,image,cat_desc,vip,type,pid) VALUES (' . sqlesc ($name) . ',' . sqlesc ($image) . ',' . sqlesc ($cat_desc) . ',' . sqlesc ($vip) . ',' . sqlesc ($type) . ',' . sqlesc ($cid) . ')') OR sqlerr (__FILE__, 135));
        update_categories_cache ();
        redirect ('admin/index.php?act=category', 'New Category has been added!');
        exit ();
      }
    }

    stdhead ('Manage Tracker Categories - Add Category');
    $where = array ('Cancel' => $_this_script_);
    show__errors ();
    echo jumpbutton ($where);
    _form_header_open_ ('Add Category', 5);
    echo '
	<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
	<input type="hidden" name="act" value="category">
	<input type="hidden" name="do" value="new">	
	<input type="hidden" name="what" value="save">';
    echo '
	<tr>
		<td align="right" class="trow1" width="30%">Category Name</td>
		<td align="left"><input type="text" name="name" value="' . $name . '" size="50"></td>
	</tr>
	<tr>
		<td align="right" class="trow1" width="20%">Category Description</td>
		<td align="left"><input type="text" name="cat_desc" value="' . $cat_desc . '" size="50"></td>
	</tr>
	<tr>
		<td align="right" class="trow1" width="20%">Sub-Category</td>
		<td align="left">' . get_category_list ($cid) . '</td>
	</tr>
	<tr>
		<td align="right" class="trow1" width="30%">Category Image</td>
		<td align="left">' . getimages ($image) . ' <img src="" id="showimage"></td>
	</tr>	
	<tr>
		<td align="right" class="trow1" width="10%">Is Vip Category?</td>
		<td align="left"><select name="vip"><option value="no"' . ($vip == 'no' ? ' SELECTED' : '') . '>no</option><option value="yes"' . ($vip == 'yes' ? ' SELECTED' : '') . '>yes</option></select></td>
	</tr>
	<tr>
		<td align="center" class="trow1" colspan="2"><input type="submit" value="Save"> <input type="reset" value="Reset Fields"></td>
	</tr>
	</form>';
    _form_header_close_ ();
    stdfoot ();
    exit ();
  }
  else
  {
    if ($do == 'delete')
    {
      if ($what == 'sure')
      {
        sql_query ('DELETE FROM categories WHERE id = ' . sqlesc ($id) . ' LIMIT 1');
        update_categories_cache ();
        redirect ('admin/index.php?act=category', 'Category has been deleted!');
      }
      else
      {
        stderr ('Sanity Check', 'Are you sure you want to delete this category? <a href="' . $_this_script_ . '&do=delete&id=' . $id . '&what=sure">YES</a> / <a href="' . $_this_script_ . '">NO</a>', false);
      }
    }
    else
    {
      if ($do == 'edit')
      {
        if ($what == 'save')
        {
          $name = htmlspecialchars_uni ($_POST['name']);
          $image = htmlspecialchars_uni ($_POST['image']);
          $cat_desc = htmlspecialchars_uni ($_POST['cat_desc']);
          $vip = ($_POST['vip'] == 'yes' ? 'yes' : 'no');
          $type = (0 < $cid ? 's' : 'c');
          if ((empty ($name) OR empty ($image)))
          {
            $_errors[] = 'Dont leave any fields blank!';
          }
          else
          {
            sql_query ('' . 'UPDATE categories SET type = \'' . $type . '\', pid = \'' . $cid . '\', name = ' . sqlesc ($name) . ', image = ' . sqlesc ($image) . ', cat_desc = ' . sqlesc ($cat_desc) . ', vip = ' . sqlesc ($vip) . '  WHERE id = ' . sqlesc ($id));
            update_categories_cache ();
            redirect ('admin/index.php?act=category', 'Category has been updated!');
            exit ();
          }
        }

        $query = sql_query ('SELECT * FROM categories WHERE id = ' . sqlesc ($id));
        if (mysql_num_rows ($query) == 0)
        {
          stderr ('Error', 'There is no category with this ID!');
        }

        $categoryname = mysql_result ($query, 0, 'name');
        $name = (!empty ($name) ? $name : $categoryname);
        $image = (!empty ($image) ? $image : mysql_result ($query, 0, 'image'));
        $cat_desc = (!empty ($cat_desc) ? $cat_desc : mysql_result ($query, 0, 'cat_desc'));
        $vip = (!empty ($vip) ? $vip : mysql_result ($query, 0, 'vip'));
        $type = mysql_result ($query, 0, 'type');
        $pid = mysql_result ($query, 0, 'pid');
        stdhead ('Manage Tracker Categories - Edit');
        $where = array ('Cancel' => $_this_script_);
        show__errors ();
        echo jumpbutton ($where);
        _form_header_open_ ('Edit Category "' . $categoryname . '"', 5);
        echo '
	<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
	<input type="hidden" name="act" value="category">
	<input type="hidden" name="do" value="edit">	
	<input type="hidden" name="what" value="save">
	<input type="hidden" name="id" value="' . $id . '">';
        echo '
	<tr>
		<td align="right" class="trow1" width="30%">Category Name</td>
		<td align="left"><input type="text" name="name" value="' . $name . '" size="50"></td>
	</tr>
	<tr>
		<td align="right" class="trow1" width="20%">Category Description</td>
		<td align="left"><input type="text" name="cat_desc" value="' . $cat_desc . '" size="50"></td>
	</tr>
	<tr>
		<td align="right" class="trow1" width="20%">Sub-Category</td>
		<td align="left">' . get_category_list (($type == 'c' ? 0 : $pid), 'cid') . '</td>
	</tr>
	<tr>
		<td align="right" class="trow1" width="30%">Category Image</td>
		<td align="left">' . getimages ($image) . ' <img src="" id="showimage"></td>
	</tr>	
	<tr>
		<td align="right" class="trow1" width="10%">Is Vip Category?</td>
		<td align="left"><select name="vip"><option value="no"' . ($vip == 'no' ? ' SELECTED' : '') . '>no</option><option value="yes"' . ($vip == 'yes' ? ' SELECTED' : '') . '>yes</option></select></td>
	</tr>
	<tr>
		<td align="center" class="trow1" colspan="2"><input type="submit" value="Save"> <input type="reset" value="Reset Fields"></td>
	</tr>
	</form>';
        _form_header_close_ ();
        stdfoot ();
        exit ();
      }
      else
      {
        if ($do == 'add_subcategory')
        {
          if (($what == 'save' AND is_valid_id ($cid)))
          {
            $name = htmlspecialchars_uni ($_POST['name']);
            $image = htmlspecialchars_uni ($_POST['image']);
            $cat_desc = htmlspecialchars_uni ($_POST['cat_desc']);
            $vip = ($_POST['vip'] == 'yes' ? 'yes' : 'no');
            $type = 's';
            if ((empty ($name) OR empty ($image)))
            {
              $_errors[] = 'Dont leave any fields blank!';
            }
            else
            {
              (sql_query ('INSERT INTO categories (name,image,cat_desc,vip,type,pid) VALUES (' . sqlesc ($name) . ',' . sqlesc ($image) . ',' . sqlesc ($cat_desc) . ',' . sqlesc ($vip) . ',' . sqlesc ($type) . ',' . sqlesc ($cid) . ')') OR sqlerr (__FILE__, 292));
              update_categories_cache ();
              redirect ('admin/index.php?act=category', 'New Sub-Category has been added!');
              exit ();
            }
          }

          $query = sql_query ('SELECT name FROM categories WHERE type = \'c\' AND id = ' . sqlesc ($cid));
          if (mysql_num_rows ($query) == 0)
          {
            stderr ('Error', 'There is no category with this ID!');
          }

          $categoryname = mysql_result ($query, 0, name);
          stdhead ('Manage Tracker Categories - Add Sub-Category');
          $where = array ('Cancel' => $_this_script_);
          show__errors ();
          echo jumpbutton ($where);
          _form_header_open_ ('Add Sub-Category to "' . $categoryname . '"', 5);
          echo '
	<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '">
	<input type="hidden" name="act" value="category">
	<input type="hidden" name="do" value="add_subcategory">	
	<input type="hidden" name="what" value="save">';
          echo '
	<tr>
		<td align="right" class="trow1" width="30%">Category Name</td>
		<td align="left"><input type="text" name="name" value="' . $name . '" size="50"></td>
	</tr>
	<tr>
		<td align="right" class="trow1" width="20%">Category Description</td>
		<td align="left"><input type="text" name="cat_desc" value="' . $cat_desc . '" size="50"></td>
	</tr>
	<tr>
		<td align="right" class="trow1" width="20%">Sub-Category</td>
		<td align="left">' . get_category_list ((!empty ($_POST['cid']) ? intval ($_POST['cid']) : $cid)) . '</td>
	</tr>
	<tr>
		<td align="right" class="trow1" width="30%">Category Image</td>
		<td align="left">' . getimages ($image) . ' <img src="" id="showimage"></td>
	</tr>	
	<tr>
		<td align="right" class="trow1" width="10%">Is Vip Category?</td>
		<td align="left"><select name="vip"><option value="no"' . ($vip == 'no' ? ' SELECTED' : '') . '>no</option><option value="yes"' . ($vip == 'yes' ? ' SELECTED' : '') . '>yes</option></select></td>
	</tr>
	<tr>
		<td align="center" class="trow1" colspan="2"><input type="submit" value="Save"> <input type="reset" value="Reset Fields"></td>
	</tr>
	</form>';
          _form_header_close_ ();
          stdfoot ();
          exit ();
        }
      }
    }
  }

  stdhead ('Manage Tracker Categories');
  $where = array ('Create New Category' => $_this_script_ . '&do=new');
  echo jumpbutton ($where);
  _form_header_open_ ('Manage Tracker Categories', 6);
  echo '
<tr>
<td align="center" class="subheader" width="3%">ID</td>
<td align="center" class="subheader" width="50%">Name</td>
<td align="center" class="subheader" width="7%">Image</td>
<td align="left" class="subheader" width="25%">Description</td>
<td align="center" class="subheader" width="5%">Is Vip Category?</td>
<td align="center" class="subheader" width="10%">Action</td>
</tr>';
  $query = sql_query ('SELECT * FROM categories WHERE type = \'s\'');
  $subcategories = array ();
  while ($subcat = mysql_fetch_assoc ($query))
  {
    if (0 < $subcat['pid'])
    {
      $subcategories[$subcat['pid']] = $subcategories[$subcat['pid']] . '<tr><td align="center">' . $subcat['name'] . ' <a href="' . $BASEURL . '/browse.php?cat=' . $subcat['id'] . '"><img src="' . $BASEURL . '/' . $pic_base_url . 'viewnfo.gif" title="View Sub-Category" alt="View Sub-Category" border="0" class="inlineimg" width="10" height="10"></a>&nbsp;&nbsp;<a href="' . $_this_script_ . '&do=edit&id=' . $subcat['id'] . '"><img src="' . $BASEURL . '/' . $pic_base_url . 'edit.gif" title="Edit Sub-Category" alt="Edit Sub-Category" border="0" class="inlineimg" width="10" height="10"></a>&nbsp;&nbsp;<a href="' . $_this_script_ . '&do=delete&id=' . $subcat['id'] . '"><img src="' . $BASEURL . '/' . $pic_base_url . 'delete.gif" title="Delete Sub-Category" alt="Delete Sub-Category" border="0" class="inlineimg" width="10" height="10"></a></td></tr>';
      continue;
    }
  }

  $query = sql_query ('SELECT * FROM categories WHERE type=\'c\'');
  if (mysql_num_rows ($query) == 0)
  {
    echo '<tr><td colspan="6">There is no registered category yet.</td></tr>';
  }
  else
  {
    while ($category = mysql_fetch_assoc ($query))
    {
      echo '
		<tr>
		<td align="center">' . $category['id'] . '</td>
		<td align="center">
		<table width="100%"><tr><td class="tborder" align="center">Main Category</td></tr><tr><td align="center">
		<b>' . $category['name'] . '</b> <a href="' . $_this_script_ . '&amp;do=add_subcategory&amp;cid=' . $category['id'] . '">[add subcategory]</a></td></tr><table width="100%"><tr><td class="subheader" align="center">Sub-categories</td></tr>' . ($subcategories[$category['id']] ? $subcategories[$category['id']] : '<tr><td align="center">There is no sub-category!</td></tr>') . '</table></td>
		<td align="center"><img src="' . $BASEURL . '/' . $pic_base_url . $table_cat . '/' . $category['image'] . '" border="0" alt="' . $category['name'] . '" title="' . $category['name'] . '"></td>
		<td align="left">' . $category['cat_desc'] . '</td>
		<td align="center"><font color="' . ($category['vip'] == 'yes' ? 'green' : 'red') . '">' . $category['vip'] . '</font></td>
		<td align="center"><a href="' . $BASEURL . '/browse.php?cat=' . $category['id'] . '"><img src="' . $BASEURL . '/' . $pic_base_url . 'viewnfo.gif" title="View Category" alt="View Category" border="0"></a>&nbsp;&nbsp;<a href="' . $_this_script_ . '&do=edit&id=' . $category['id'] . '"><img src="' . $BASEURL . '/' . $pic_base_url . 'edit.gif" title="Edit Category" alt="Edit Category" border="0"></a>&nbsp;&nbsp;<a href="' . $_this_script_ . '&do=delete&id=' . $category['id'] . '"><img src="' . $BASEURL . '/' . $pic_base_url . 'delete.gif" title="Delete Category" alt="Delete Category" border="0"></a></td>
		</tr>';
    }
  }

  _form_header_close_ ();
  echo '<p>' . jumpbutton ($where) . '</p>';
  stdfoot ();
?>
