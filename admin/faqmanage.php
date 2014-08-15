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

  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('TSFAQMANAGE_VERSION', '1.3.2 by xam');
  $lang->load ('faq');
  $do = (isset ($_GET['do']) ? htmlspecialchars_uni ($_GET['do']) : (isset ($_POST['do']) ? htmlspecialchars_uni ($_POST['do']) : ''));
  $subdo = (isset ($_GET['subdo']) ? htmlspecialchars_uni ($_GET['subdo']) : (isset ($_POST['subdo']) ? htmlspecialchars_uni ($_POST['subdo']) : ''));
  $id = (isset ($_GET['id']) ? intval ($_GET['id']) : (isset ($_POST['id']) ? intval ($_POST['id']) : ''));
  $faq_errors = array ();
  stdhead ($lang->faq['faqtitle'], true, '', '
<script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/element/element-beta-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/container/container-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/menu/menu-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/button/button-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/editor/editor-min.js"></script>', '
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.6.0/build/menu/assets/skins/sam/menu.css" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.6.0/build/button/assets/skins/sam/button.css" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.6.0/build/container/assets/skins/sam/container.css" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.6.0/build/editor/assets/skins/sam/editor.css" />');
  if ($do == 'view')
  {
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
						<li><a href="javascript:collapse' . $faq['id'] . '.slideit()"><font color="red">' . $faq['name'] . '</font></a> [<a href="' . $_this_script_ . '&amp;do=edit&amp;id=' . $faq['id'] . '">Edit</a>] [<a href="' . $_this_script_ . '&amp;do=delete&amp;id=' . $faq['id'] . '" onclick="return confirmdelete()">Delete</a>]</li>
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

  if ($do == 'savedisplayorder')
  {
    $orders = $_POST['disporder'];
    if (!is_array ($orders))
    {
      $faq_errors[] = 'Empty FAQ order(s)!';
    }
    else
    {
      foreach ($orders as $id => $order)
      {
        sql_query ('UPDATE ts_faq SET disporder = ' . sqlesc ($order) . ' WHERE id = ' . sqlesc ($id));
      }
    }
  }

  if ($do == 'delete')
  {
    if (!is_valid_id ($id))
    {
      $faq_errors[] = $lang->faq['faqerror'];
    }
    else
    {
      sql_query ('DELETE FROM ts_faq WHERE id = ' . sqlesc ($id));
      sql_query ('DELETE FROM ts_faq WHERE pid = ' . sqlesc ($id));
    }
  }

  if ($do == 'new')
  {
    if ($subdo == 'save')
    {
      print_r ($_POST);
      exit ();
      $name = trim ($_POST['name']);
      $description = trim ($_POST['description']);
      $disporder = intval ($_POST['disporder']);
      if (empty ($name))
      {
        $faq_errors[] = 'Please fill all fields!';
      }
      else
      {
        sql_query ('INSERT INTO ts_faq (type,name,description,disporder) VALUES (\'1\',' . sqlesc ($name) . ',' . sqlesc ($description) . ',' . sqlesc ($disporder) . ')');
        header ('Location: ' . $_this_script_);
        exit ();
      }

      show_faq_errors ();
    }

    $where = array ('Cancel' => $_this_script_);
    echo '
	<form method="post" action="' . $_this_script_ . '">
	<input type="hidden" name="do" value="new">
	<input type="hidden" name="subdo" value="save">
	' . jumpbutton ($where) . '
	<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
		<tr>
			<td class="thead" colspan="2">
				Add New FAQ Item
			</td>
		</tr>
		<tr>
			<td align="right" valign="top">
				Title
			</td>
			<td align="left">
				<input type="text" name="name" value="' . htmlspecialchars_uni ($name) . '" style="width: 745px;">
			</td>
		</tr>

		<tr>
			<td align="right" valign="top">
				Description
			</td>
			<td align="left">
				<textarea name="description" id="description">' . htmlspecialchars_uni ($description) . '</textarea>				
			</td>
		</tr>

		<tr>
			<td align="right" valign="top">
				Display Order
			</td>
			<td align="left">
				<input type="text" name="disporder" value="' . $disporder . '" size="4">
			</td>
		</tr>

		<tr>
			<td align="center" colspan="3">
				<input type="submit" value="save"> <input type="reset" value="reset">
			</td>
		</tr>
	</table>
	</form>
	<script>
		(function() {
			var Dom = YAHOO.util.Dom,
				Event = YAHOO.util.Event;
			
			var myConfig = {
				height: "300px",
				width: "600px",
				dompath: true,
				focusAtStart: true,
				handleSubmit: true
			};
			
			var myEditor = new YAHOO.widget.Editor("description", myConfig);
			myEditor._defaultToolbar.buttonType = "basic";
			myEditor.render();
			
		})();
	</script>
	';
    stdfoot ();
    exit ();
  }

  if ($do == 'add')
  {
    if ($subdo == 'save')
    {
      $name = trim ($_POST['name']);
      $description = trim ($_POST['description']);
      $disporder = intval ($_POST['disporder']);
      $pid = intval ($_POST['pid']);
      if (empty ($name))
      {
        $faq_errors[] = 'Please fill all fields!';
      }
      else
      {
        sql_query ('INSERT INTO ts_faq (type,name,description,disporder,pid) VALUES (\'2\',' . sqlesc ($name) . ',' . sqlesc ($description) . ',' . sqlesc ($disporder) . ',' . sqlesc ($pid) . ')');
        header ('Location: ' . $_this_script_);
        exit ();
      }
    }

    if (!is_valid_id ($id))
    {
      $faq_errors[] = $lang->faq['faqerror'];
      show_faq_errors ();
    }
    else
    {
      ($query = sql_query ('SELECT * FROM ts_faq WHERE type = \'1\'') OR sqlerr (__FILE__, 264));
      if (mysql_num_rows ($query) == 0)
      {
        $faq_errors[] = $lang->faq['faqerror'];
        show_faq_errors ();
      }
      else
      {
        show_faq_errors ();
        $categories = '<select name="pid">';
        while ($faq = mysql_fetch_assoc ($query))
        {
          $categories .= '<option value="' . $faq['id'] . ($id == $faq['id'] ? ' selected="selected"' : '') . '">' . $faq['name'] . '</option>';
        }

        $categories .= '</select>';
        $where = array ('Cancel' => $_this_script_);
        echo '
			<form method="post" action="' . $_this_script_ . '">
			<input type="hidden" name="do" value="add">
			<input type="hidden" name="subdo" value="save">
			<input type="hidden" name="id" value="' . $id . '">
			' . jumpbutton ($where) . '
			<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
				
				<tr>
					<td class="thead" colspan="2">
						Add Child FAQ Item
					</td>
				</tr>

				<tr>
					<td align="right" valign="top">
						Category
					</td>
					<td align="left">
						' . $categories . '
					</td>
				</tr>

				<tr>
					<td align="right" valign="top">
						Title
					</td>
					<td align="left">
						<input type="text" name="name" value="' . htmlspecialchars_uni ($name) . '" style="width: 745px;">
					</td>
				</tr>

				<tr>
					<td align="right" valign="top">
						Description
					</td>
					<td align="left">
						<textarea style="height: 250px; width: 750px;" name="description" id="description">' . htmlspecialchars_uni ($description) . '</textarea>						
					</td>
				</tr>

				<tr>
					<td align="right" valign="top">
						Display Order
					</td>
					<td align="left">
						<input type="text" name="disporder" value="' . $disporder . '" size="4">
					</td>
				</tr>

				<tr>
					<td align="center" colspan="3">
						<input type="submit" value="save"> <input type="reset" value="reset">
					</td>
				</tr>
			</table>
			</form>
			<script>
				(function() {
					var Dom = YAHOO.util.Dom,
						Event = YAHOO.util.Event;
					
					var myConfig = {
						height: "300px",
						width: "600px",
						dompath: true,
						focusAtStart: true,
						handleSubmit: true
					};
					
					var myEditor = new YAHOO.widget.Editor("description", myConfig);
					myEditor._defaultToolbar.buttonType = "basic";
					myEditor.render();
					
				})();
			</script>
			';
      }
    }

    stdfoot ();
    exit ();
  }

  if ($do == 'edit')
  {
    if (($subdo == 'save' AND is_valid_id ($id)))
    {
      $type = intval ($_POST['type']);
      $name = trim ($_POST['name']);
      $description = trim ($_POST['description']);
      $disporder = intval ($_POST['disporder']);
      $pid = intval ($_POST['pid']);
      if ((empty ($name) OR ($type == 2 AND empty ($description))))
      {
        $faq_errors[] = 'Please fill all fields!';
      }
      else
      {
        (sql_query ('UPDATE ts_faq SET type = ' . sqlesc ($type) . ', name = ' . sqlesc ($name) . ', description = ' . sqlesc ($description) . ', disporder=' . sqlesc ($disporder) . ', pid = ' . sqlesc ($pid) . ' WHERE id = ' . sqlesc ($id)) OR sqlerr (__FILE__, 381));
        header ('Location: ' . $_this_script_);
        exit ();
      }
    }

    if (!is_valid_id ($id))
    {
      $faq_errors[] = $lang->faq['faqerror'];
      show_faq_errors ();
    }
    else
    {
      ($firstquery = sql_query ('SELECT * FROM ts_faq WHERE id = ' . sqlesc ($id)) OR sqlerr (__FILE__, 394));
      if (mysql_num_rows ($firstquery) == 0)
      {
        $faq_errors[] = $lang->faq['faqerror'];
        show_faq_errors ();
      }
      else
      {
        $editfaq = mysql_fetch_assoc ($firstquery);
        show_faq_errors ();
        if ($editfaq['type'] == 2)
        {
          ($query2 = sql_query ('SELECT * FROM ts_faq WHERE type = \'1\' ORDER By disporder ASC') OR sqlerr (__FILE__, 406));
          $categories = '				
				<tr>
					<td align="right" valign="top">
						Category
					</td>
					<td align="left">
					<select name="pid">';
          while ($cat = mysql_fetch_assoc ($query2))
          {
            $categories .= '<option value="' . $cat['id'] . ($editfaq['pid'] == $cat['id'] ? ' selected="selected"' : '') . '">' . $cat['name'] . '</option>';
          }

          $categories .= '
				</select>
				</td>
				</tr>';
        }
        else
        {
          $categories = '<input type="hidden" name="pid" value="' . $editfaq['pid'] . '">';
        }

        $where = array ('Cancel' => $_this_script_);
        echo '
			<form method="post" action="' . $_this_script_ . '">
			<input type="hidden" name="do" value="edit">
			<input type="hidden" name="subdo" value="save">
			<input type="hidden" name="id" value="' . $id . '">
			<input type="hidden" name="type" value="' . $editfaq['type'] . '">
			' . jumpbutton ($where) . '
			<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">				
				<tr>
					<td class="thead" colspan="2">
						Edit FAQ Item: ' . htmlspecialchars_uni ($editfaq['name']) . '
					</td>
				</tr>				
				' . $categories . '
				<tr>
					<td align="right" valign="top">
						Title
					</td>
					<td align="left">
						<input  style="width: 745px;"  type="text" name="name" value="' . (!empty ($name) ? htmlspecialchars_uni ($name) : htmlspecialchars_uni ($editfaq['name'])) . '">
					</td>
				</tr>

				<tr>
					<td align="right" valign="top">
						Description
					</td>
					<td align="left">						
						<textarea style="height: 250px; width: 750px;" name="description" id="description">' . (!empty ($description) ? htmlspecialchars_uni ($description) : $editfaq['description']) . '</textarea>
					</td>
				</tr>

				<tr>
					<td align="right" valign="top">
						Display Order
					</td>
					<td align="left">
						<input type="text" name="disporder" value="' . (!empty ($disporder) ? htmlspecialchars_uni ($disporder) : $editfaq['disporder']) . '" size="4">
					</td>
				</tr>

				<tr>
					<td align="center" colspan="3">
						<input type="submit" value="save"> <input type="reset" value="reset">
					</td>
				</tr>
			</table>
			</form>
			<script>
				(function() {
					var Dom = YAHOO.util.Dom,
						Event = YAHOO.util.Event;
					
					var myConfig = {
						height: "300px",
						width: "600px",
						dompath: true,
						focusAtStart: true,
						handleSubmit: true
					};
					
					var myEditor = new YAHOO.widget.Editor("description", myConfig);
					myEditor._defaultToolbar.buttonType = "basic";
					myEditor.render();
					
				})();
			</script>
			';
      }
    }

    stdfoot ();
    exit ();
  }

  show_faq_errors ();
  $where = array ('Add New FAQ Item' => $_this_script_ . '&amp;do=new');
  ($query = sql_query ('SELECT disporder, id, name FROM ts_faq WHERE type = \'1\' ORDER By disporder ASC') OR sqlerr (__FILE__, 510));
  if (0 < mysql_num_rows ($query))
  {
    echo '
	<script type="text/javascript">
		function confirmdelete()
		{
			ht = document.getElementsByTagName("html");
			ht[0].style.filter = "progid:DXImageTransform.Microsoft.BasicImage(grayscale=1)";
			if (confirm("Are you sure you want to delete this FAQ item?"))
			{
				return true;
			}
			else
			{
				ht[0].style.filter = "";
				return false;
			}
		};
	</script>
	<form method="post" action="' . $_this_script_ . '">
	<input type="hidden" name="do" value="savedisplayorder">
	' . jumpbutton ($where) . '
	<table class="main" border="1" cellspacing="0" cellpadding="5" width="100%">
		<tr>
			<td class="thead" colspan="3">
				' . $lang->faq['faqtitle'] . '
			</td>
		</tr>
		<tr>
			<td class="subheader">
				Title
			</td>
			<td class="subheader" align="center">
				Display Order
			</td>
			<td class="subheader" align="center">
				Action
			</td>
		</tr>
	';
    while ($faq = mysql_fetch_assoc ($query))
    {
      echo '
		<tr>
			<td>
				<a href="' . $_this_script_ . '&amp;do=view&amp;id=' . $faq['id'] . '">' . $faq['name'] . '</a>
			</td>
			<td align="center">
				<input type="text" name="disporder[' . $faq['id'] . ']" value="' . $faq['disporder'] . '" size="3">
			</td>
			<td align="center">
				[<a href="' . $_this_script_ . '&amp;do=edit&amp;id=' . $faq['id'] . '">Edit</a>] [<a href="' . $_this_script_ . '&amp;do=add&amp;id=' . $faq['id'] . '">Add Child FAQ Item</a>] [<a href="' . $_this_script_ . '&amp;do=delete&amp;id=' . $faq['id'] . '" onclick="return confirmdelete()">Delete</a>]
			</td>
		</tr>';
    }

    echo '
		<tr>
			<td colspan="3" align="center"><input type="submit" value="Save Display Order">
		</tr>
	</table>
	</form>';
  }
  else
  {
    stdmsg ('Error', 'There is no FAQ items yet. Click <a href="' . $_this_script_ . '&amp;do=new">here</a> to create one', false);
  }

  stdfoot ();
?>
