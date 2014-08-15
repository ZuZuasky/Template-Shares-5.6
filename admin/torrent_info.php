<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function httperr ($code = 404)
  {
    $sapi_name = php_sapi_name ();
    if (($sapi_name == 'cgi' OR $sapi_name == 'cgi-fcgi'))
    {
      header ('Status: 404 Not Found');
    }
    else
    {
      header ('HTTP/1.1 404 Not Found');
    }

    exit ();
  }

  function print_array ($array, $offset_symbol = '|--', $offset = '', $parent = '')
  {
    if (!is_array ($array))
    {
      echo ('' . '[') . $array . '] is not an array!<br />';
      return null;
    }

    reset ($array);
    switch ($array['type'])
    {
      case 'string':
      {
        printf ('<li><div class=string> - <span class=icon>[STRING]</span> <span class=title>[%s]</span> <span class=length>(%d)</span>: <span class=value>%s</span></div></li>', $parent, $array['strlen'], $array['value']);
        break;
      }

      case 'integer':
      {
        printf ('<li><div class=integer> - <span class=icon>[INT]</span> <span class=title>[%s]</span> <span class=length>(%d)</span>: <span class=value>%s</span></div></li>', $parent, $array['strlen'], $array['value']);
        break;
      }

      case 'list':
      {
        printf ('<li><div class=list> + <span class=icon>[LIST]</span> <span class=title>[%s]</span> <span class=length>(%d)</span></div>', $parent, $array['strlen']);
        echo '<ul>';
        print_array ($array['value'], $offset_symbol, $offset . $offset_symbol);
        echo '</ul></li>';
        break;
      }

      case 'dictionary':
      {
        printf ('<li><div class=dictionary> + <span class=icon>[DICT]</span> <span class=title>[%s]</span> <span class=length>(%d)</span></div>', $parent, $array['strlen']);
        while (list ($key, $val) = each ($array))
        {
          if (is_array ($val))
          {
            echo '<ul>';
            print_array ($val, $offset_symbol, $offset . $offset_symbol, $key);
            echo '</ul>';
            continue;
          }
        }

        echo '</li>';
        break;
      }

      default:
      {
        while (list ($key, $val) = each ($array))
        {
          if (is_array ($val))
          {
            print_array ($val, $offset_symbol, $offset, $key);
            continue;
          }
        }

        break;
      }
    }

  }

  if (!defined ('STAFF_PANEL_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('TI_VERSION', '0.2 by xam');
  require_once INC_PATH . '/benc.php';
  $id = (int)$_GET['id'];
  if (!$id)
  {
    print_no_permission ();
  }

  ($res = sql_query ('SELECT name FROM torrents WHERE id = ' . sqlesc ($id)) OR sqlerr (__FILE__, 97));
  $row = mysql_fetch_array ($res);
  $fn = TSDIR . '/' . $torrent_dir . '/' . $id . '.torrent';
  if (((!$row OR !is_file ($fn)) OR !is_readable ($fn)))
  {
    httperr ();
  }

  stdhead ('Torrent Info');
  echo '
';
  echo '<s';
  echo 'tyle type="text/css"><!--

/* list styles */
ul ul { margin-left: 15px; }
ul, li { padding: 0px; margin: 0px; list-style-type: none; color: #000; font-weight: normal;}
ul a, li a { color: #009; text-decoration: none; font-weight: normal; }
li { display: inline; } /* fix for IE blank line bug */
ul > li { display: list-item; }

li div.string  {padding: 3px;}
li div.integer {padding: 3px;}
';
  echo '
li div.dictionary {padding: 3px;}
li div.list {padding: 3px;}
li div.string span.icon {color:#090;padding: 2px;}
li div.integer span.icon {color:#990;padding: 2px;}
li div.dictionary span.icon {color:#909;padding: 2px;}
li div.list span.icon {color:#009;padding: 2px;}

li span.title {font-weight: bold;}

--></style>

';
  begin_main_frame ();
  print '' . '<div align=center><h1>' . $row['name'] . '</h1>';
  $dict = bdec_file ($fn, 1024 * 1024);
  print '<table width=750 border=1 cellspacing=0 cellpadding=5><td>';
  $dict['value']['info']['value']['pieces']['value'] = '0x' . bin2hex (substr ($dict['value']['info']['value']['pieces']['value'], 0, 25)) . '...';
  echo '<ul id=colapse>';
  print_array ($dict, '*', '', 'root');
  echo '</ul>';
  print '</td></table>';
  echo '

';
  echo '<s';
  echo 'cript type="text/javascript" language="javascript1.2"><!--
var openLists = [], oIcount = 0;
function compactMenu(oID,oAutoCol,oPlMn,oMinimalLink) {
	if( !document.getElementsByTagName || !document.childNodes || !document.createElement ) { return; }
	var baseElement = document.getElementById( oID ); if( !baseElement ) { return; }
	compactChildren( baseElement, 0, oID, oAutoCol, oPlMn, baseEleme';
  echo 'nt.tagName.toUpperCase(), oMinimalLink && oPlMn );
}
function compactChildren( oOb, oLev, oBsID, oCol, oPM, oT, oML ) {
	if( !oLev ) { oBsID = escape(oBsID); if( oCol ) { openLists[oBsID] = []; } }
	for( var x = 0, y = oOb.childNodes; x < y.length; x++ ) { if( y[x].tagName ) {
		//for each immediate LI child
		var theNextUL = y[x].getElementsByTagName( oT )[0];
		if( theNextUL ) {
			//coll';
  echo 'apse the first UL/OL child
			theNextUL.style.display = \'none\';
			//create a link for expanding/collapsing
			var newLink = document.createElement(\'A\');
			newLink.setAttribute( \'href\', \'#\' );
			newLink.onclick = new Function( \'clickSmack(this,\' + oLev + \',\\\'\' + oBsID + \'\\\',\' + oCol + \',\\\'\' + escape(oT) + \'\\\');return false;\' );
			//wrap everything upto the child U/OL in the link
			if( o';
  echo 'ML ) { var theHTML = \'\'; } else {
				var theT = y[x].innerHTML.toUpperCase().indexOf(\'<\'+oT);
				var theA = y[x].innerHTML.toUpperCase().indexOf(\'<A\');
				var theHTML = y[x].innerHTML.substr(0, ( theA + 1 && theA < theT ) ? theA : theT );
				while( !y[x].childNodes[0].tagName || ( y[x].childNodes[0].tagName.toUpperCase() != oT && y[x].childNodes[0].tagName.toUpperCase() != \'A\' ) ) {
					y[x';
  echo '].removeChild( y[x].childNodes[0] ); }
			}
			y[x].insertBefore(newLink,y[x].childNodes[0]);
			y[x].childNodes[0].innerHTML = oPM + theHTML.replace(/^\\s*|\\s*$/g,\'\');
			theNextUL.MWJuniqueID = oIcount++;
			compactChildren( theNextUL, oLev + 1, oBsID, oCol, oPM, oT, oML );
} } } }
function clickSmack( oThisOb, oLevel, oBsID, oCol, oT ) {
	if( oThisOb.blur ) { oThisOb.blur(); }
	oThisOb ';
  echo '= oThisOb.parentNode.getElementsByTagName( unescape(oT) )[0];
	if( oCol ) {
		for( var x = openLists[oBsID].length - 1; x >= oLevel; x-=1 ) { if( openLists[oBsID][x] ) {
			openLists[oBsID][x].style.display = \'none\'; if( oLevel != x ) { openLists[oBsID][x] = null; }
		} }
		if( oThisOb == openLists[oBsID][oLevel] ) { openLists[oBsID][oLevel] = null; }
		else { oThisOb.style.display = \'block\'';
  echo '; openLists[oBsID][oLevel] = oThisOb; }
	} else { oThisOb.style.display = ( oThisOb.style.display == \'block\' ) ? \'none\' : \'block\'; }
}
function stateToFromStr(oID,oFStr) {
	if( !document.getElementsByTagName || !document.childNodes || !document.createElement ) { return \'\'; }
	var baseElement = document.getElementById( oID ); if( !baseElement ) { return \'\'; }
	if( !oFStr && typeof(oFStr) != \'';
  echo 'undefined\' ) { return \'\'; } if( oFStr ) { oFStr = oFStr.split(\':\'); }
	for( var oStr = \'\', l = baseElement.getElementsByTagName(baseElement.tagName), x = 0; l[x]; x++ ) {
		if( oFStr && MWJisInTheArray( l[x].MWJuniqueID, oFStr ) && l[x].style.display == \'none\' ) { l[x].parentNode.getElementsByTagName(\'a\')[0].onclick(); }
		else if( l[x].style.display != \'none\' ) { oStr += (oStr?\':\':\'\') + l[x].M';
  echo 'WJuniqueID; }
	}
	return oStr;
}
function MWJisInTheArray(oNeed,oHay) { for( var i = 0; i < oHay.length; i++ ) { if( oNeed == oHay[i] ) { return true; } } return false; }
function selfLink(oRootElement,oClass,oExpand) {
	if(!document.getElementsByTagName||!document.childNodes) { return; }
	oRootElement = document.getElementById(oRootElement);
	for( var x = 0, y = oRootElement.getElementsByT';
  echo 'agName(\'a\'); y[x]; x++ ) {
		if( y[x].getAttribute(\'href\') && !y[x].href.match(/#$/) && getRealAddress(y[x]) == getRealAddress(location) ) {
			y[x].className = (y[x].className?(y[x].className+\' \'):\'\') + oClass;
			if( oExpand ) {
				oExpand = false;
				for( var oEl = y[x].parentNode, ulStr = \'\'; oEl != oRootElement && oEl != document.body; oEl = oEl.parentNode ) {
					if( oEl.tagName && ';
  echo 'oEl.tagName == oRootElement.tagName ) { ulStr = oEl.MWJuniqueID + (ulStr?(\':\'+ulStr):\'\'); } }
				stateToFromStr(oRootElement.id,ulStr);
} } } }
function getRealAddress(oOb) { return oOb.protocol + ( ( oOb.protocol.indexOf( \':\' ) + 1 ) ? \'\' : \':\' ) + oOb.hostname + ( ( typeof(oOb.pathname) == typeof(\' \') && oOb.pathname.indexOf(\'/\') != 0 ) ? \'/\' : \'\' ) + oOb.pathname + oOb.search; }

compactM';
  echo 'enu(\'colapse\',false,\'\');
//--></script>



';
  end_main_frame ();
  stdfoot ();
?>
