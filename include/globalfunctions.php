<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function format_urls ($s, $target = '_blank')
  {
    return preg_replace ('/(\\A|[^=\\]\'"a-zA-Z0-9])((http|ftp|https|ftps|irc):\\/\\/[^()<>\\s]+)/i', '' . '\\1<a href="\\2" target="' . $target . '">\\2</a>', $s);
  }

  function anonymize ($s, $target)
  {
    global $BASEURL;
    require_once INC_PATH . '/readconfig_redirect.php';
    if ((((!empty ($s) AND !preg_match ('#gallery.(signatures|posts)#i', $s)) AND preg_match_all ('/(\\<a href=")(.[^"]*)/i', $s, $matches)) AND $GLOBALS['REDIRECT']['redirect'] == 'yes'))
    {
      $ignore_protocol = explode (',', str_replace (' ', '', $GLOBALS['REDIRECT']['protocol']));
      $localdomains = ($GLOBALS['REDIRECT']['localaddresses'] ? explode (' ', $GLOBALS['REDIRECT']['localaddresses']) : array ($_SERVER['SERVER_NAME']));
      $preg_search = $preg_replace = array ();
      foreach ($matches[2] as $key => $serverurl)
      {
        if ($parsed_url = @parse_url ($serverurl) !== false)
        {
          $servername = $parsed_url['host'];
          if ((in_array ($parsed_url['scheme'], $ignore_protocol) OR (isset ($parsed_url['fragment']) OR !isset ($parsed_url['host']))))
          {
            continue;
          }

          foreach ($localdomains as $localdomain)
          {
            if (substr ($localdomain, 0, 1) == '.')
            {
              if (preg_match ('' . '/' . $localdomain . '$/i', $servername))
              {
                continue;
              }

              continue;
            }
          }
        }
        else
        {
          continue;
        }

        $anonymurl = $BASEURL . '/redirector.php?url=' . $serverurl;
        $preg_search['' . $serverurl] = '' . '<a href="' . $serverurl;
        $preg_replace['' . $serverurl] = '<a target="_blank" href="' . $anonymurl;
      }

      if ($preg_search)
      {
        $s = str_replace ($preg_search, $preg_replace, $s);
      }
    }

    return $s;
  }

  function parse_quotes ($message)
  {
    global $lang;
    $pattern = array ('#\\[quote=(?:&quot;|"|\')?(.*?)["\']?(?:&quot;|"|\')?\\](.*?)\\[\\/quote\\](
?|
?)#si', '#\\[quote\\](.*?)\\[\\/quote\\](
?|
?)#si');
    $replace = array ('</p><div class="quote_header">' . htmlentities ('\\1') . ' ' . $lang->global['quote'] . ('' . '</div><div class="quote_body">$2</div><p>'), '</p><div class="quote_header">' . $lang->global['quote2'] . ('' . '</div><div class="quote_body">$1</div><p>'));
    while ((preg_match ($pattern[0], $message) OR preg_match ($pattern[1], $message)))
    {
      $message = preg_replace ($pattern, $replace, $message);
    }

    $find = array ('#<div class="quote_body">(
?|
?)#', '#(
?|
?)</div>#');
    $replace = array ('<div class="quote_body">', '</div>');
    $message = preg_replace ($find, $replace, $message);
    return $message;
  }

  function ts_remove_badwords ($check)
  {
    global $badwords;
    $badword = @explode (',', $badwords);
    foreach ($badword as $b)
    {
      $r = $b[0] . @str_repeat ('*', @strlen ($b) - 2) . $b[strlen ($b) - 1];
      if (function_exists ('str_ireplace'))
      {
        $check = @str_ireplace ($b, $r, $check);
        continue;
      }
      else
      {
        $check = @str_replace ($b, $r, $check);
        continue;
      }
    }

    return $check;
  }

  function ts_wordwrap ($message, $max = '136')
  {
    return preg_replace ('' . '#(?>[^\\s&/<>"\\-\\.\\[\\]]{' . $max . '})#', '' . '$0 ', $message);
  }

  function parse_list ($text, $type = '')
  {
    if ($type)
    {
      switch ($type)
      {
        case 'A':
        {
          $listtype = 'upper-alpha';
          break;
        }

        case 'a':
        {
          $listtype = 'lower-alpha';
          break;
        }

        case 'I':
        {
          $listtype = 'upper-roman';
          break;
        }

        case 'i':
        {
          $listtype = 'lower-roman';
          break;
        }

        case '1':
        {
        }

        default:
        {
        }
      }

      $listtype = 'decimal';
      break;
    }
    else
    {
      $listtype = '';
    }

    $text = preg_replace ('#^(\\s|<br>|<br />)+#si', '', $text);
    $bullets = preg_split ('#\\s*\\[\\*\\]#s', $text, 0 - 1, PREG_SPLIT_NO_EMPTY);
    if (empty ($bullets))
    {
      return '

';
    }

    $output = '';
    foreach ($bullets as $bullet)
    {
      $output .= handle_bbcode_list_element ($bullet);
    }

    if ($listtype)
    {
      return '<ol style="list-style-type: ' . $listtype . '">' . $output . '</ol>';
    }

    return '' . '<ul>' . $output . '</ul>';
  }

  function handle_bbcode_list_element ($text)
  {
    return '' . '<li>' . $text . '</li>
';
  }

  function unhtmlspecialchars ($text, $doUniCode = false)
  {
    if ($doUniCode)
    {
      $text = preg_replace ('/&#([0-9]+);/esiU', 'convert_int_to_utf8(\'\\1\')', $text);
    }

    return str_replace (array ('&lt;', '&gt;', '&quot;', '&amp;'), array ('<', '>', '"', '&'), $text);
  }

  function check_email ($email)
  {
    return preg_match ('#^[a-z0-9.!\\#$%&\'*+-/=?^_`{|}~]+@([0-9.]+|([^\\s\'"<>]+\\.+[a-z]{2,6}))$#si', $email);
  }

  function parse_email ($link = '', $text = '')
  {
    $rightlink = trim ($link);
    if (empty ($rightlink))
    {
      $rightlink = trim ($text);
    }

    $rightlink = str_replace (array ('`', '"', '\'', '['), array ('&#96;', '&quot;', '&#39;', '&#91;'), $rightlink);
    if ((!trim ($link) OR $text == $rightlink))
    {
      $tmp = unhtmlspecialchars ($rightlink);
      if (55 < strlen ($tmp))
      {
        $text = htmlspecialchars_uni (substr ($tmp, 0, 36) . '...' . substr ($tmp, 0 - 14));
      }
    }

    $rightlink = str_replace ('  ', '', $rightlink);
    if (check_email ($rightlink))
    {
      return '' . '<a href="mailto:' . $rightlink . '">' . $text . '</a>';
    }

    return $text;
  }

  function strip_front_back_whitespace ($text, $max_amount = 1, $strip_front = true, $strip_back = true)
  {
    $max_amount = intval ($max_amount);
    if ($strip_front)
    {
      $text = preg_replace ('#^(( |\\t)*((<br>|<br />)[\\r\\n]*)|\\r\\n|\\n|\\r){0,' . $max_amount . '}#si', '', $text);
    }

    if ($strip_back)
    {
      $text = strrev (preg_replace ('#^(((>rb<|>/ rb<)[\\n\\r]*)|\\n\\r|\\n|\\r){0,' . $max_amount . '}#si', '', strrev (rtrim ($text))));
    }

    return $text;
  }

  function fetch_block_height ($code)
  {
    $numlines = max (substr_count ($code, '
'), substr_count ($code, '<br />')) + 1;
    if (30 < $numlines)
    {
      $numlines = 30;
    }
    else
    {
      if ($numlines < 1)
      {
        $numlines = 1;
      }
    }

    return $numlines * 16 + 18;
  }

  function php_tag ($code)
  {
    $code = strip_front_back_whitespace ($code, 1);
    $codefind1 = array ('<br>', '<br />');
    $codereplace1 = array ('', '');
    $codefind2 = array ('&gt;', '&lt;', '&quot;', '&amp;', '&#91;', '&#93;');
    $codereplace2 = array ('>', '<', '"', '&', '[', ']');
    $code = rtrim (str_replace ($codefind1, $codereplace1, $code));
    $blockheight = fetch_block_height ($code);
    $code = str_replace ($codefind2, $codereplace2, $code);
    if (!preg_match ('#<\\?#si', $code))
    {
      $code = '' . '<?php BEGIN__TSSE__CODE__SNIPPET ' . $code . ' 
END__TSSE__CODE__SNIPPET ?>';
      $addedtags = true;
    }
    else
    {
      $addedtags = false;
    }

    $oldlevel = error_reporting (0);
    $code = highlight_string ($code, true);
    error_reporting ($oldlevel);
    if ($addedtags)
    {
      $search = array ('#&lt;\\?php( |&nbsp;)BEGIN__TSSE__CODE__SNIPPET( |&nbsp;)#siU', '#(<(span|font)[^>]*>)&lt;\\?(</\\2>(<\\2[^>]*>))php( |&nbsp;)BEGIN__TSSE__CODE__SNIPPET( |&nbsp;)#siU', '#END__TSSE__CODE__SNIPPET( |&nbsp;)\\?(>|&gt;)#siU');
      $replace = array ('', '\\4', '');
      $code = preg_replace ($search, $replace, $code);
    }

    $code = preg_replace ('/&amp;#([0-9]+);/', '&#$1;', $code);
    $code = str_replace (array ('[', ']'), array ('&#91;', '&#93;'), $code);
    return '' . '<div style="width: 660px; margin:10px; margin-top:5px"><div class="codetop" style="width: 650px; border: 1px inset;">PHP:</div><div class="codemain" dir="ltr" style="border: 1px inset; margin: 0px;	padding: 6px;overflow: auto;width: 650px;height: ' . $blockheight . 'px;text-align: left;"><code style="white-space:nowrap"><!-- php buffer start-->' . $code . '<!-- php buffer end --></code></div></div>';
  }

  function code_tag ($code)
  {
    $code = str_replace (array ('<br>', '<br />', '\\"'), array ('', '', '"'), $code);
    $code = strip_front_back_whitespace ($code, 1);
    $blockheight = fetch_block_height ($code);
    return '' . '<div style="width: 660px; margin:10px; margin-top:5px"><div class="codetop" style="width: 650px; border: 1px inset;">CODE:</div><pre class="codemain" dir="ltr" style="margin: 0px;padding: 6px;border: 1px inset;width: 650px;height: ' . $blockheight . 'px;text-align: left;overflow: auto">' . $code . '</pre></div>';
  }

  function sql_tag ($sql)
  {
    $sql = preg_replace ('/^<br>/', '', $sql);
    $sql = preg_replace ('#^<br />#', '', $sql);
    $sql = preg_replace ('/^\\s+/', '', $sql);
    if (!preg_match ('' . '/\\s+$/', $sql))
    {
      $sql = $sql . ' ';
    }

    $sql = str_replace (('' . '$'), '&#36;', $sql);
    $blockheight = fetch_block_height ($sql);
    $sql = preg_replace ('#(=|\\+|\\-|&gt;|&lt;|~|==|\\!=|LIKE|NOT LIKE|REGEXP)#i', '<span style=\'color:orange\'>\\1</span>', $sql);
    $sql = preg_replace ('#(MAX|AVG|SUM|COUNT|MIN)\\(#i', '<span style=\'color:blue\'>\\1</span>(', $sql);
    $sql = preg_replace ('#(FROM|INTO)\\s{1,}(\\S+?)\\s{1,}((\\w+)\\s{0,})#i', '<span style=\'color:green\'>\\1</span> <span style=\'color:orange\'>\\2</span> <span style=\'color:orange\'>\\3</span>', $sql);
    $sql = preg_replace ('#(?<=join)\\s{1,}(\\S+?)\\s{1,}(\\w+)\\s{0,}#i', ' <span style=\'color:orange\'>\\1</span> <span style=\'color:orange\'>\\2</span> ', $sql);
    $sql = preg_replace ('!(&quot;|&#39;|&#039;)(.+?)(&quot;|&#39;|&#039;)!i', '<span style=\'color:red\'>\\1\\2\\3</span>', $sql);
    $sql = preg_replace ('#\\s{1,}(AND|OR|ON)\\s{1,}#i', ' <span style=\'color:blue\'>\\1</span> ', $sql);
    $sql = preg_replace ('#(LEFT|JOIN|WHERE|MODIFY|CHANGE|AS|DISTINCT|IN|ASC|DESC|ORDER BY)\\s{1,}#i', '<span style=\'color:green\'>\\1</span> ', $sql);
    $sql = preg_replace ('#LIMIT\\s*(\\d+)(?:\\s*([,])\\s*(\\d+))*#i', '<span style=\'color:green\'>LIMIT</span> <span style=\'color:orange\'>\\1\\2 \\3</span>', $sql);
    $sql = preg_replace ('#(SELECT|INSERT|UPDATE|DELETE|ALTER TABLE|CREATE TABLE|DROP)#i', '<span style=\'color:blue;font-weight:bold\'>\\1</span>', $sql);
    return '' . '</p><div style="width: 660px; margin:10px; margin-top:5px"><div class="codetop" style="width: 650px; border: 1px inset;">SQL:</div><div dir="ltr" style="border: 1px inset; margin: 0px; padding: 6px; overflow: auto; height:' . $blockheight . 'px; width: 650px; text-align: left;" class="codemain"><code>' . $sql . '</code></div></div><p>';
  }

  function xss_clean ($text)
  {
    static $global_find = array (0 => '/javascript/si', 1 => '/vbscript/si', 2 => '/&(?![a-z0-9#]+;)/si');
    static $global_replace = array (0 => 'java script', 1 => 'vb script', 2 => '&amp;');
    $text = preg_replace ($global_find, $global_replace, $text);
    return $text;
  }

  function format_comment ($s, $htmlspecialchars_uni = true, $noshoutbox = true, $xss_clean = true, $show_smilies = true, $imagerel = 'posts')
  {
    global $smilies;
    global $CURUSER;
    global $BASEURL;
    global $redirect;
    global $rootpath;
    global $pic_base_url;
    global $lang;
    global $cache;
    if (!defined ('NcodeImageResizer'))
    {
      define ('NcodeImageResizer', true);
    }

    $target = ($noshoutbox ? '_self' : '_blank');
    $s = ts_remove_badwords ($s);
    if ($htmlspecialchars_uni)
    {
      $s = htmlspecialchars_uni ($s);
    }

    if ($xss_clean)
    {
      $s = xss_clean ($s);
    }

    if ($noshoutbox)
    {
      preg_match_all ('#\\[(code|php|sql)\\](.*?)\\[/\\1\\](
?|
?)#si', $s, $code_matches, PREG_SET_ORDER);
      $s = preg_replace ('#\\[(code|php|sql)\\](.*?)\\[/\\1\\](
?|
?)#si', '{{ts-code}}
', $s);
    }

    if ((!preg_match ('/\\[nfo\\](.*?)\\[\\/nfo\\]/is', $s) AND $show_smilies))
    {
      require_once $rootpath . '/' . $cache . '/smilies.php';
      @reset ($smilies);
      while (list ($code, $url) = @each ($smilies))
      {
        $s = str_replace ($code, '<img border="0" src="' . $BASEURL . '/' . $pic_base_url . 'smilies/' . $url . '" alt="' . htmlspecialchars ($code) . '" class="inlineimg" />', $s);
      }
    }

    $simple_search = array ('/\\[b\\]((\\s|.)+?)\\[\\/b\\]/is', '/\\[marquee\\]((\\s|.)+?)\\[\\/marquee\\]/is', '/\\[blink\\]((\\s|.)+?)\\[\\/blink\\]/is', '/\\[i\\]((\\s|.)+?)\\[\\/i\\]/is', '/\\[h\\]((\\s|.)+?)\\[\\/h\\]/is', '/\\[u\\]((\\s|.)+?)\\[\\/u\\]/is', '/\\[img\\]((http|https):\\/\\/[^\\s\'"<>]+(\\.(jpg|gif|png)))\\[\\/img\\]/is', '/\\[img=((http|https):\\/\\/[^\\s\'"<>]+(\\.(jpg|gif|png)))\\]/is', '/\\[color=([a-zA-Z]+)\\]((\\s|.)+?)\\[\\/color\\]/is', '/\\[color=(#[a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9])\\]((\\s|.)+?)\\[\\/color\\]/is', '/\\[url=([^()<>\\s]+?)\\]((\\s|.)+?)\\[\\/url\\]/is', '/\\[url\\]([^()<>\\s]+?)\\[\\/url\\]/is', '/\\[font=([a-zA-Z ,]+)\\]((\\s|.)+?)\\[\\/font\\]/is', '/\\[pre\\](.*?)\\[\\/pre\\]/is', '/\\[nfo\\](.*?)\\[\\/nfo\\]/is', '#\\[size=(xx-small|x-small|small|medium|large|x-large|xx-large)\\](.*?)\\[/size\\]#si', '#\\[align=(left|center|right|justify)\\](.*?)\\[/align\\]#si', '#\\[email\\](.*?)\\[/email\\]#ei', '#\\[email=(.*?)\\](.*?)\\[/email\\]#ei', '#\\[youtube\\](.*?)\\[/youtube\\]#i', '#\\[swf\\](.*?)\\[/swf\\]#i',  '#\\[audio\\](.*?)\\[/audio\\]#i');
    $imgtag = ($noshoutbox ? '<a href="\\1" rel="gallery.' . ($imagerel == 'signatures' ? 'signatures' : 'posts') . '"><img border="0" src="\\1" alt="" title="" onload="NcodeImageResizer.' . ($imagerel == 'signatures' ? 'createOnSigs' : 'createOn') . '(this);" /></a>' : '\\1');
   
 $imgtag2 = ($noshoutbox ? '

"<object type="application/x-shockwave-flash" data="dewplayer-multi.swf?son=$1" width="200" height="20">
<param name="allowScriptAccess" value="never" />
<param name="play" value="true" />
<param name="movie" value="dewplayer-multi.swf?son=$1" />
<param name="menu" value="false" />
<param name="quality" value="high" />
<param name="scalemode" value="noborder" />
<param name="wmode" value="transparent" />
<param name="bgcolor" value="#FFFFFF" />
</object>
' : '' . 'http://$1');
  



  $youtubetag = ($noshoutbox ? '<object width="425" height="350"><param name="movie" value="http://www.youtube.com/v/' . ('' . '$1') . '"></param><embed src="http://www.youtube.com/v/' . ('' . '$1') . '" type="application/x-shockwave-flash" width="425" height="350"></embed></object>' : '' . 'http://www.youtube.com/v/$1');
    $swf = ($noshoutbox ? '<object type="application/x-shockwave-flash" data="' . ('' . '$1') . '" width="400" height="300">  <param name="showfullscreen" value="1&autostart=false&advertise=1" /><param name="movie" value="' . ('' . '$1') . '" /><param name="showfullscreen" value="1" /><param name="allowscriptaccess" value="always" /></object>' : '' . 'http://$1');
    $simple_replace = array ('<b>\\1</b>', '<marquee style="font-family:Book Antiqua; color: #FFFFFF" bgcolor="#000080" scrollamount="5" loop="infinite">\\1</marquee>', '<blink>\\1</blink>', '<i>\\1</i>', '<h3>\\1</h3>', '<u>\\1</u>', $imgtag, $imgtag, '<font color="\\1">\\2</font>', '<font color="\\1">\\2</font>', '' . '<a href="\\1" target="' . $target . '">\\2</a>', '' . '<a href="\\1" target="' . $target . '">\\1</a>', '<font face="\\1">\\2</font>', '<pre>' . '\\1' . '</pre>', '<tt><span style="white-space: nowrap;"><font face="MS Linedraw" size="2" style="font-size: 10pt; line-height: 10pt">\\1</font></span></tt>', '' . '<span style="font-size: $1;">$2</span>', '' . '<p style="text-align: $1;">$2</p>', '' . 'parse_email(\'$1\')', '' . 'parse_email(\'$1\', \'$2\')', $youtubetag, $swf, $imgtag2);
    $s = preg_replace ($simple_search, $simple_replace, $s);
    if (!defined ('TS_CUSTOM_BBCODE'))
    {
      define ('TS_CUSTOM_BBCODE', true);
    }

    include_once INC_PATH . '/ts_custom_bbcode.php';
    $s = ts_custom_bbcode ($s);
    if ($noshoutbox)
    {
      $s = parse_quotes ($s);
    }

    $s = format_urls ($s, $target);
    $s = anonymize ($s, $target);
    $s = nl2br ($s);
    if (((isset ($code_matches) AND 0 < count ($code_matches)) AND $noshoutbox))
    {
      foreach ($code_matches as $text)
      {
        if (strtolower ($text[1]) == 'code')
        {
          $code = code_tag ($text[2]);
        }
        else
        {
          if (strtolower ($text[1]) == 'php')
          {
            $code = php_tag ($text[2]);
          }
          else
          {
            if (strtolower ($text[1]) == 'sql')
            {
              $code = sql_tag ($text[2]);
            }
          }
        }

        $s = preg_replace ('#\\{\\{ts-code\\}\\}
?#', $code, $s, 1);
      }
    }

    if ($noshoutbox)
    {
      while (preg_match ('#\\[list\\](.*?)\\[/list\\]#esi', $s))
      {
        $s = preg_replace ('#\\[list\\](.*?)\\[/list\\](
?|
?)#esi', '' . 'parse_list(\'$1\')
', $s);
      }

      while (preg_match ('#\\[list=(a|A|i|I|1)\\](.*?)\\[/list\\](
?|
?)#esi', $s))
      {
        $s = preg_replace ('#\\[list=(a|A|i|I|1)\\](.*?)\\[/list\\]#esi', '' . 'parse_list(\'$2\', \'$1\')
', $s);
      }
    }

    $s = ts_wordwrap ($s);
    if (preg_match ('/\\[hide\\](.*?)\\[\\/hide\\]/is', $s))
    {
      while (preg_match ('#\\[hide\\](.*?)\\[\\/hide\\](
?|
?)#si', $s))
      {
        if (!defined ('IS_THIS_USER_POSTED'))
        {
          $s = preg_replace ('#\\[hide\\](.*?)\\[\\/hide\\](
?|
?)#si', show_notice ($lang->global['h1'], true, $lang->global['h2'], ''), $s);
          continue;
        }
        else
        {
          $s = preg_replace ('#\\[hide\\](.*?)\\[\\/hide\\](
?|
?)#si', show_notice ('$1', false, $lang->global['h3'], ''), $s);
          continue;
        }
      }
    }

    return $s;
  }

  @error_reporting (E_ALL & ~E_NOTICE);
  @ini_set ('error_reporting', E_ALL & ~E_NOTICE);
  @ini_set ('display_errors', '0');
  @ini_set ('log_errors', '1');
  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

?>
