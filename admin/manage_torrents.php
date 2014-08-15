<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


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

  function get_torrent_flags ($torrents)
  {
    global $BASEURL;
    global $pic_base_url;
    global $lang;
    global $rootpath;
    $isfree = ($torrents['free'] == 'yes' ? '<img src="' . $BASEURL . '/' . $pic_base_url . 'freedownload.gif" class="inlineimg" alt="' . $lang->browse['freedownload'] . '" title="' . $lang->browse['freedownload'] . '" />' : '');
    $issilver = ($torrents['silver'] == 'yes' ? '<img src="' . $BASEURL . '/' . $pic_base_url . 'silverdownload.gif" class="inlineimg" alt="' . $lang->browse['silverdownload'] . '" title="' . $lang->browse['silverdownload'] . '" />' : '');
    $isrequest = ($torrents['isrequest'] == 'yes' ? '<img src="' . $BASEURL . '/' . $pic_base_url . 'isrequest.gif" class="inlineimg" alt="' . $lang->browse['requested'] . '" title="' . $lang->browse['requested'] . '" />' : '');
    $isnuked = ($torrents['isnuked'] == 'yes' ? '<img src="' . $BASEURL . '/' . $pic_base_url . 'isnuked.gif" class="inlineimg" alt="' . sprintf ($lang->browse['nuked'], $torrents['WhyNuked']) . '" title="' . sprintf ($lang->browse['nuked'], $torrents['WhyNuked']) . '" />' : '');
    $issticky = ($torrents['sticky'] == 'yes' ? '<img src="' . $BASEURL . '/' . $pic_base_url . 'sticky.gif" alt="' . $lang->browse['sticky'] . '" title="' . $lang->browse['sticky'] . '" />' : '');
    $anonymous = ($torrents['anonymous'] == 'yes' ? '<img src="' . $BASEURL . '/' . $pic_base_url . 'chatpost.gif" alt="Anonymous torrent" title="Anonymous torrent" />' : '');
    $isbanned = ($torrents['banned'] == 'yes' ? '<img src="' . $BASEURL . '/' . $pic_base_url . 'disabled.gif" alt="Banned torrent" title="Banned torrent" />' : '');
    $isexternal = (($torrents['ts_external'] == 'yes' AND $_GET['tsuid'] != $torrents['id']) ? '<a onclick=\'ts_show("loading-layer")\' href=\'' . $BASEURL . '/include/ts_external_scrape/ts_update.php?id=' . intval ($torrents['id']) . '\'><img src=\'' . $BASEURL . '/' . $pic_base_url . 'external.gif\' class=\'inlineimg\'  border=\'0\' alt=\'' . $lang->browse['update'] . '\' title=\'' . $lang->browse['update'] . '\' /></a>' : ((isset ($_GET['tsuid']) AND $_GET['tsuid'] == $torrents['id']) ? '<img src=\'' . $BASEURL . '/' . $pic_base_url . 'input_true.gif\' class=\'inlineimg\' border=\'0\' alt=\'' . $lang->browse['updated'] . '\' title=\'' . $lang->browse['updated'] . '\' />' : ''));
    $isvisible = ($torrents['visible'] == 'yes' ? '<img src="' . $BASEURL . '/' . $pic_base_url . 'input_true.gif" class="inlineimg" alt="Active Torrent" title="Active Torrent" />' : '<img src="' . $BASEURL . '/' . $pic_base_url . 'input_error.gif" class="inlineimg" alt="Dead Torrent" title="Dead Torrent" />');
    $isdoubleupload = ($torrents['doubleupload'] == 'yes' ? '<img src="' . $BASEURL . '/' . $pic_base_url . 'x2.gif" alt="' . $lang->browse['dupload'] . '" title="' . $lang->browse['dupload'] . '" class="inlineimg" />' : '');
    $isclosed = ($torrents['allowcomments'] == 'no' ? '<img src="' . $BASEURL . '/' . $pic_base_url . 'commentpos.gif" alt="Closed for Comment Posting" title="Closed for Comment Posting" class="inlineimg" />' : '');
    return '' . $isvisible . ' ' . $isfree . ' ' . $issilver . ' ' . $isrequest . ' ' . $isnuked . ' ' . $issticky . ' ' . $isexternal . ' ' . $anonymous . ' ' . $isbanned . ' ' . $isdoubleupload . ' ' . $isclosed;
  }

  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('MT_VERSION', 'v0.8 by xam');
  $do = (isset ($_POST['do']) ? htmlspecialchars ($_POST['do']) : (isset ($_GET['do']) ? htmlspecialchars ($_GET['do']) : ''));
  $_errors = array ();
  $browsecategory = (isset ($_GET['browsecategory']) ? intval ($_GET['browsecategory']) : (isset ($_POST['browsecategory']) ? intval ($_POST['browsecategory']) : ''));
  $searchword = (isset ($_GET['searchword']) ? $_GET['searchword'] : (isset ($_POST['searchword']) ? $_POST['searchword'] : ''));
  $searchtype = (isset ($_GET['searchtype']) ? $_GET['searchtype'] : (isset ($_POST['searchtype']) ? $_POST['searchtype'] : ''));
  if (($browsecategory != '' AND is_valid_id ($browsecategory)))
  {
    $query = sql_query ('' . 'SELECT type FROM categories WHERE id = ' . $browsecategory);
    if (0 < mysql_num_rows ($query))
    {
      $cate_type = mysql_result ($query, 0, 'type');
      if ($cate_type == 's')
      {
        $extraquery1 = '' . ' WHERE category = ' . $browsecategory;
        $extraquery2 = '' . ' WHERE t.category = ' . $browsecategory . ' ';
        $extralink = '' . 'browsecategory=' . $browsecategory . '&amp;';
      }
      else
      {
        $query = sql_query ('' . 'SELECT id FROM categories WHERE pid = ' . $browsecategory);
        while ($sub_cats = mysql_fetch_assoc ($query))
        {
          $array_cats[] = $sub_cats['id'];
        }

        $extraquery1 = ' WHERE category IN (0,' . implode (',', $array_cats) . ')';
        $extraquery2 = ' WHERE t.category IN (0,' . implode (',', $array_cats) . ')';
        $extralink = '' . 'browsecategory=' . $browsecategory . '&amp;';
      }
    }
  }

  if ($searchword != '')
  {
    if ($extraquery1)
    {
      $extraquery1 .= ' AND (name LIKE ' . sqlesc ('%' . $searchword . '%') . ')';
      $extraquery2 .= ' AND (t.name LIKE ' . sqlesc ('%' . $searchword . '%') . ') ';
      $extralink .= 'searchword=' . htmlspecialchars_uni ($browsecategory) . '&amp;';
    }
    else
    {
      $extraquery1 .= ' WHERE (name LIKE ' . sqlesc ('%' . $searchword . '%') . ')';
      $extraquery2 .= ' WHERE (t.name LIKE ' . sqlesc ('%' . $searchword . '%') . ') ';
      $extralink .= 'searchword=' . htmlspecialchars_uni ($searchword) . '&amp;';
    }
  }

  if ($searchtype != '')
  {
    switch ($searchtype)
    {
      case 'deadonly':
      {
        if ($extraquery1)
        {
          $extraquery1 .= ' AND (visible = \'no\' OR (seeders=0 AND leechers=0))';
          $extraquery2 .= ' AND (t.visible = \'no\' OR (t.seeders=0 AND t.leechers=0))';
          $extralink .= 'searchtype=' . htmlspecialchars_uni ($searchtype) . '&amp;';
        }
        else
        {
          $extraquery1 .= ' WHERE (visible = \'no\' OR (seeders=0 AND leechers=0))';
          $extraquery2 .= ' WHERE (t.visible = \'no\' OR (t.seeders=0 AND t.leechers=0)) ';
          $extralink .= 'searchtype=' . htmlspecialchars_uni ($searchtype) . '&amp;';
        }

        break;
      }

      case 'internal':
      {
        if ($extraquery1)
        {
          $extraquery1 .= ' AND ts_external = \'no\'';
          $extraquery2 .= ' AND ts_external = \'no\' ';
          $extralink .= 'searchtype=' . htmlspecialchars_uni ($searchtype) . '&amp;';
        }
        else
        {
          $extraquery1 .= ' WHERE ts_external = \'no\'';
          $extraquery2 .= ' WHERE ts_external = \'no\' ';
          $extralink .= 'searchtype=' . htmlspecialchars_uni ($searchtype) . '&amp;';
        }

        break;
      }

      case 'external':
      {
        if ($extraquery1)
        {
          $extraquery1 .= ' AND ts_external = \'yes\'';
          $extraquery2 .= ' AND ts_external = \'yes\' ';
          $extralink .= 'searchtype=' . htmlspecialchars_uni ($searchtype) . '&amp;';
        }
        else
        {
          $extraquery1 .= ' WHERE ts_external = \'yes\'';
          $extraquery2 .= ' WHERE ts_external = \'yes\' ';
          $extralink .= 'searchtype=' . htmlspecialchars_uni ($searchtype) . '&amp;';
        }

        break;
      }

      case 'silver':
      {
        if ($extraquery1)
        {
          $extraquery1 .= ' AND silver = \'yes\'';
          $extraquery2 .= ' AND silver = \'yes\' ';
          $extralink .= 'searchtype=' . htmlspecialchars_uni ($searchtype) . '&amp;';
        }
        else
        {
          $extraquery1 .= ' WHERE silver = \'yes\'';
          $extraquery2 .= ' WHERE silver = \'yes\' ';
          $extralink .= 'searchtype=' . htmlspecialchars_uni ($searchtype) . '&amp;';
        }

        break;
      }

      case 'free':
      {
        if ($extraquery1)
        {
          $extraquery1 .= ' AND free = \'yes\'';
          $extraquery2 .= ' AND free = \'yes\' ';
          $extralink .= 'searchtype=' . htmlspecialchars_uni ($searchtype) . '&amp;';
        }
        else
        {
          $extraquery1 .= ' WHERE free = \'yes\'';
          $extraquery2 .= ' WHERE free = \'yes\' ';
          $extralink .= 'searchtype=' . htmlspecialchars_uni ($searchtype) . '&amp;';
        }

        break;
      }

      case 'recommend':
      {
        if ($extraquery1)
        {
          $extraquery1 .= ' AND sticky = \'yes\'';
          $extraquery2 .= ' AND sticky = \'yes\' ';
          $extralink .= 'searchtype=' . htmlspecialchars_uni ($searchtype) . '&amp;';
        }
        else
        {
          $extraquery1 .= ' WHERE sticky = \'yes\'';
          $extraquery2 .= ' WHERE sticky = \'yes\' ';
          $extralink .= 'searchtype=' . htmlspecialchars_uni ($searchtype) . '&amp;';
        }

        break;
      }

      case 'doubleuploads':
      {
        if ($extraquery1)
        {
          $extraquery1 .= ' AND doubleupload = \'yes\'';
          $extraquery2 .= ' AND doubleupload = \'yes\' ';
          $extralink .= 'searchtype=' . htmlspecialchars_uni ($searchtype) . '&amp;';
          break;
        }
        else
        {
          $extraquery1 .= ' WHERE doubleupload = \'yes\'';
          $extraquery2 .= ' WHERE doubleupload = \'yes\' ';
          $extralink .= 'searchtype=' . htmlspecialchars_uni ($searchtype) . '&amp;';
        }
      }
    }
  }

  if ($do == 'update')
  {
    if (is_valid_id ($_POST['page']))
    {
      $page = $_GET['page'] = intval ($_POST['page']);
    }

    $torrentid = $_POST['torrentid'];
    $actiontype = $_POST['actiontype'];
    $category = intval ($_POST['category']);
    if (empty ($actiontype))
    {
      $_errors[] = 'Please select action type!';
    }
    else
    {
      if ((!is_array ($torrentid) OR count ($torrentid) < 1))
      {
        $_errors[] = 'Please select a torrent!';
      }
      else
      {
        $torrentids = implode (',', $torrentid);
        if ($torrentids)
        {
          switch ($actiontype)
          {
            case 'move':
            {
              if (is_valid_id ($category))
              {
                (sql_query ('UPDATE torrents SET category = ' . sqlesc ($category) . ('' . ' WHERE id IN (' . $torrentids . ')')) OR sqlerr (__FILE__, 250));
              }
              else
              {
                $_errors[] = 'Invalid Category!';
              }

              break;
            }

            case 'delete':
            {
              require_once INC_PATH . '/functions_deletetorrent.php';
              foreach ($torrentid as $id)
              {
                deletetorrent ($id);
              }

              break;
            }

            case 'sticky':
            {
              (sql_query ('' . 'UPDATE torrents SET sticky = IF(sticky = \'yes\', \'no\', \'yes\') WHERE id IN (0,' . $torrentids . ')') OR sqlerr (__FILE__, 265));
              break;
            }

            case 'free':
            {
              (sql_query ('' . 'UPDATE torrents SET free = IF(free = \'yes\', \'no\', \'yes\') WHERE id IN (0,' . $torrentids . ')') OR sqlerr (__FILE__, 268));
              break;
            }

            case 'silver':
            {
              (sql_query ('' . 'UPDATE torrents SET silver = IF(silver = \'yes\', \'no\', \'yes\') WHERE id IN (0,' . $torrentids . ')') OR sqlerr (__FILE__, 271));
              break;
            }

            case 'visible':
            {
              (sql_query ('' . 'UPDATE torrents SET visible = IF(visible = \'yes\', \'no\', \'yes\') WHERE id IN (0,' . $torrentids . ')') OR sqlerr (__FILE__, 274));
              break;
            }

            case 'anonymous':
            {
              (sql_query ('' . 'UPDATE torrents SET anonymous = IF(anonymous = \'yes\', \'no\', \'yes\') WHERE id IN (0,' . $torrentids . ')') OR sqlerr (__FILE__, 277));
              break;
            }

            case 'banned':
            {
              (sql_query ('' . 'UPDATE torrents SET banned = IF(banned = \'yes\', \'no\', \'yes\') WHERE id IN (0,' . $torrentids . ')') OR sqlerr (__FILE__, 280));
              break;
            }

            case 'nuke':
            {
              (sql_query ('' . 'UPDATE torrents SET isnuked = IF(isnuked = \'yes\', \'no\', \'yes\') WHERE id IN (0,' . $torrentids . ')') OR sqlerr (__FILE__, 283));
              break;
            }

            case 'doubleupload':
            {
              (sql_query ('' . 'UPDATE torrents SET doubleupload = IF(doubleupload = \'yes\', \'no\', \'yes\') WHERE id IN (0,' . $torrentids . ')') OR sqlerr (__FILE__, 286));
              break;
            }

            case 'openclose':
            {
              (sql_query ('' . 'UPDATE torrents SET allowcomments = IF(allowcomments = \'yes\', \'no\', \'yes\') WHERE id IN (0,' . $torrentids . ')') OR sqlerr (__FILE__, 289));
            }
          }

          if (($_POST['return'] == 'yes' AND !empty ($_POST['return_address'])))
          {
            $returnto = fix_url ($_POST['return_address']);
            redirect ($returnto);
            exit ();
          }
        }
        else
        {
          $_errors[] = 'I can not implode torrent ids!';
        }
      }
    }
  }

  stdhead ('Manage Torrents', true, 'supernote');
  show__errors ();
  $what = $_GET['what'];
  if ((empty ($what) OR $what == 'asc'))
  {
    $what = 'desc';
  }
  else
  {
    $what = 'asc';
  }

  $orderby = 't.added ' . strtoupper ($what);
  $allowedlist = array ('name', 'owner', 'category', 'date');
  if ((isset ($_GET['orderby']) AND in_array ($_GET['orderby'], $allowedlist)))
  {
    $orderby = 't.' . $_GET['orderby'] . ' ' . strtoupper ($what);
    $link = 'orderby=' . htmlspecialchars ($_GET['orderby']) . '&amp;what=' . htmlspecialchars ($_GET['what']) . '&amp;';
  }

  $query = sql_query ('' . 'SELECT * FROM torrents' . $extraquery1);
  $count = mysql_num_rows ($query);
  $torrentsperpage = ($CURUSER['torrentsperpage'] != 0 ? intval ($CURUSER['torrentsperpage']) : $ts_perpage);
  list ($pagertop, $pagerbottom, $limit) = pager ($torrentsperpage, $count, $_this_script_ . '&amp;' . $link . $extralink);
  require_once INC_PATH . '/functions_category.php';
  $catdropdown = ts_category_list ('category', $category, '<option value="0">Select Category</option>');
  $catdropdown2 = ts_category_list ('browsecategory', $browsecategory, '<option value="0">--select category--</option>');
  $searchtype_dropdown = '
<select name="searchtype">
	<option value="0">--select search type--</option>
';
  foreach (array ('deadonly' => 'Show Dead Torrents', 'internal' => 'Show Internal Torrents', 'external' => 'Show External Torrents', 'silver' => 'Show Silver Torrents', 'free' => 'Show Free Torrents', 'recommend' => 'Show Recommend Torrents', 'doubleuploads' => 'Show x2 Torrents') as $valuename => $description)
  {
    $searchtype_dropdown .= '
	<option value="' . $valuename . '"' . ($searchtype == $valuename ? ' selected="selected"' : '') . '>' . $description . '</option>';
  }

  $searchtype_dropdown .= '
</select>';
  _form_header_open_ ('Select Category');
  echo '
<form method="post" action="' . $_this_script_ . '">
<tr><td>Search Word(s): <input type="text" name="searchword" value="' . htmlspecialchars_uni ($searchword) . '"> ' . $catdropdown2 . ' ' . $searchtype_dropdown . ' <input type="submit" value="Search"></td></tr>
';
  _form_header_close_ ();
  echo '</form><br />';
  echo $pagertop;
  _form_header_open_ ('Manage Torrents', 6);
  echo '
<form method="post" action="' . $_this_script_ . '" name="update">
<input type="hidden" name="do" value="update">
<input type="hidden" name="page" value="' . intval ($_GET['page']) . '">
<input type="hidden" name="searchword" value="' . htmlspecialchars_uni ($searchword) . '">
<input type="hidden" name="browsecategory" value="' . $browsecategory . '">
<tr>
	<td class="subheader"><a href="' . $_this_script_ . '&amp;orderby=name&amp;what=' . $what . (isset ($_GET['page']) ? '&amp;page=' . intval ($_GET['page']) : '') . '">Name</a></td>
	<td class="subheader" align="center">Flags</td>
	<td class="subheader"><a href="' . $_this_script_ . '&amp;orderby=owner&amp;what=' . $what . (isset ($_GET['page']) ? '&amp;page=' . intval ($_GET['page']) : '') . '">Uploader</a></td>
	<td class="subheader"><a href="' . $_this_script_ . '&amp;orderby=category&amp;what=' . $what . (isset ($_GET['page']) ? '&amp;page=' . intval ($_GET['page']) : '') . '">Category</a></td>
	<td class="subheader" align="center"><a href="' . $_this_script_ . '&amp;orderby=added&amp;what=' . $what . (isset ($_GET['page']) ? '&amp;page=' . intval ($_GET['page']) : '') . '">Added</a></td>	
	<td class="subheader" align="center"><input type="checkbox" checkall="group" onclick="javascript: return select_deselectAll (\'update\', this, \'group\');"></td>	
</tr>
';
  $quickmenus = $str = '';
  $is_mod = is_mod ($usergroups);
  $lang->load ('browse');
  $query = sql_query ('' . 'SELECT t.*, u.username, g.namestyle, c.name as categoryname, c.cat_desc FROM torrents t LEFT JOIN users u on (t.owner=u.id) LEFT JOIN usergroups g ON (u.usergroup=g.gid) LEFT JOIN categories c ON (t.category=c.id)' . $extraquery2 . ' ORDER by ' . $orderby . ' ' . $limit);
  while ($torrent = mysql_fetch_assoc ($query))
  {
    $str .= '
	<tr>
		<td><a href="#" id="quickmenu' . $torrent['id'] . '" />' . $torrent['name'] . '</a> 
		<div id="loading-layer" style="position: absolute; display:none; left:500px; width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000"><div style="font-weight:bold" id="loading-layer-text" class="small">Updating...</div><br /><img src="' . $BASEURL . '/' . $pic_base_url . 'await.gif" border="0" alt="" /></div></td>
		<td align="center">' . get_torrent_flags ($torrent) . '</td>
		<td><a href="' . $BASEURL . '/userdetails.php?id=' . $torrent['owner'] . '">' . get_user_color ($torrent['username'], $torrent['namestyle']) . '</a></td>
		<td><a href="' . $BASEURL . '/browse.php?category=' . $torrent['category'] . '" alt="' . $torrent['cat_desc'] . '" title="' . $torrent['cat_desc'] . '" />' . $torrent['categoryname'] . '</td>
		<td align="center">' . my_datee ($dateformat, $torrent['added']) . ' ' . my_datee ($timeformat, $torrent['added']) . '</td>
		<td align="center"><input type="checkbox" name="torrentid[]" value="' . $torrent['id'] . '" checkme="group"></td>
	</tr>';
    $seolink3 = ts_seo ($torrent['id'], $torrent['name'], 'd');
    $seolink2 = ts_seo ($torrent['id'], $torrent['name'], 's');
    $downloadinfo = sprintf ($lang->browse['downloadinfo'], $torrent['name']);
    $quicmenus .= '
	<script type="text/javascript">
			menu_register("quickmenu' . $torrent['id'] . '");
		</script>
		<div id="quickmenu' . $torrent['id'] . '_menu" class="menu_popup" style="display:none;">				
			<table border="1" cellspacing="0" cellpadding="2">
			  <tr>
				<td colspan="2" align="center" class="thead"><b>' . $lang->global['quickmenu'] . '</b></td>
			  </tr>
			  <tr>
				<td class="subheader"><a href="' . $seolink3 . '" title="' . $downloadinfo . '" alt="' . $downloadinfo . '" /><b>' . $lang->browse['download'] . '</b></a></td>
				<td rowspan="' . ($is_mod ? 7 : 3) . '" align="center" valign="middle"><div align="center">' . (!empty ($torrent['t_image']) ? '<a href="javascript:popImage(\'' . $torrent['t_image'] . '\',\'Image Preview\')"><img src="' . $torrent['t_image'] . '" border="0" height="150" width="150" alt="' . $lang->browse['t_image'] . '" title="' . $lang->browse['t_image'] . '" \\>' : $lang->browse['nopreview']) . '</div></td>
			  </tr>
			  <tr>
				<td class="subheader"><a href="' . $seolink2 . '"><b>' . $lang->browse['viewtorrent'] . '</b></a></td>
			  </tr>
			  <tr>
				<td class="subheader"><a href="' . $seolink2 . '#startcomments"><b>' . $lang->browse['viewcomments'] . '</b></a></td>
			  </tr>' . ($is_mod ? '
			  <tr>
				<td class="subheader"><a href="' . $BASEURL . '/admin/index.php?act=torrent_info&amp;id=' . $torrent['id'] . '"><b>' . $lang->browse['tinfo'] . '</b></a></td>
			  </tr>
			  <tr>
				<td class="subheader"><a href="' . $BASEURL . '/edit.php?id=' . $torrent['id'] . '"><b>' . $lang->browse['edit'] . '</b></a></td>
			  </tr>
			  <tr>
				<td class="subheader"><a href="' . $BASEURL . '/admin/index.php?act=nuketorrent&amp;id=' . $torrent['id'] . '"><b>' . $lang->browse['nuke'] . '</b></a></td>
			  </tr>
			  <tr>
				<td class="subheader"><a href="' . $BASEURL . '/admin/index.php?act=fastdelete&amp;id=' . $torrent['id'] . '"><b>' . $lang->browse['delete'] . '</b></a></td></td>
			  </tr>' : '') . '
		  </table>
		</div>';
  }

  echo $str;
  echo '
<script type="text/javascript">
function check_it(wHAT)
{
	if (wHAT.value == "move")
	{
		document.getElementById("movetorrent").style.display = "block";
	}
	else
	{
		document.getElementById("movetorrent").style.display = "none";
	}
}
</script>
<tr>
	<td colspan="6" align="center">
		<p id="selectaction" style="display:block;">
			Select Action: 
			<select name="actiontype" onchange="check_it(this)">
				<option value="0">Select action</option>
				<option value="move">Move selected torrents</option>
				<option value="delete">Delete selected torrents</option>
				<option value="sticky">Sticky/Unsticky selected torrents</option>
				<option value="free">Set Free/NonFree selected torrents</option>
				<option value="silver">Set Silver/NonSilver selected torrents</option>
				<option value="visible">Set Visible/Unvisible selected torrents</option>
				<option value="anonymous">Anonymize/Non Anonymize selected torrents</option>
				<option value="banned">Ban/UnBan selected torrents</option>
				<option value="nuke">Nuke/UnNuke selected torrents</option>
				<option value="doubleupload">Set Double Upload YES/NO</option>
				<option value="openclose">Open/Close for Comment Posting</option>
			</select> 
		</p>
		<p id="movetorrent" style="display:none;">
			Select Category: ' . $catdropdown . '
		</p> 
		<p id="doaction" style="display:block;">
			<input type="submit" value="do it"> <input type="reset" value="reset fields">
		</p>
	</td>
</tr>
</form>';
  _form_header_close_ ();
  echo $quicmenus . '
	<script type="text/javascript">
		menu.activate(true);
	</script>
	<div id="loading-layer" style="position: absolute; display:none; left:500px; width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000"><div style="font-weight:bold" id="loading-layer-text" class="small">' . $lang->browse['updating'] . '</div><br /><img src="' . $BASEURL . '/' . $pic_base_url . 'await.gif" border="0" alt="" /></div>
	';
  echo $pagerbottom;
  stdfoot ();
?>
