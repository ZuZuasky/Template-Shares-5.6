<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function insert_editor ($subject = true, $subjectvalue = '', $textarevalue = '', $head1 = '', $head2 = '', $postoptionstitle = '', $postoptions = '', $preview = true, $extrasubject = '', $buttonname = '', $javascript = '', $textareasubject = '')
  {
    global $rootpath;
    global $pic_base_url;
    global $smilies;
    global $BASEURL;
    global $lang;
    require_once INC_PATH . '/class_template.php';
    $new_ts_template = new ts_template ();
    $ts_template = $new_ts_template->get_ts_template ('editor');
    $scriptpath = $BASEURL . '/scripts/';
    $imagepath = $BASEURL . '/' . $pic_base_url;
    $trackerimagepath = $BASEURL . '/' . $pic_base_url;
    eval ($ts_template['begin']);
    if ($subject == true)
    {
      eval ($ts_template['subject']);
    }

    if (!empty ($extrasubject))
    {
      foreach ($extrasubject as $left => $right)
      {
        eval ($ts_template['extra_subject']);
      }
    }

    $count = 0;
    $getsmilies = '';
    foreach ($smilies as $a => $b)
    {
      if ($count < 52)
      {
        if (($count AND $count % 4 == 0))
        {
          $getsmilies .= '</tr><tr>';
        }

        $getsmilies .= '
			<td class="none">
				<img style="cursor: pointer;" src="' . $trackerimagepath . 'smilies/' . $b . '" class="smilie" alt="' . $a . '" border="0">
			</td>
			';
        ++$count;
        continue;
      }
    }

    eval ($ts_template['content']);
    if (!empty ($postoptions))
    {
      foreach ($postoptions as $p => $v)
      {
        if ((!empty ($p) AND !empty ($v)))
        {
          $str .= '
			<tr>
				<td class="trow2" with="20%">
					<strong>' . $postoptionstitle[$p] . '</strong>
				</td>
				<td class="trow2">
					' . $v . '
				</td>
			</tr>
			';
          continue;
        }
      }
    }

    eval ($ts_template['finish']);
    $str .= '
		<script type="text/javascript">
			var imagepath = "' . $imagepath . '"
			var usephptag = "yes"
		</script>
		';
    $str .= '
		<script type="text/javascript" src="' . $scriptpath . 'prototype.lite.js?v=' . O_SCRIPT_VERSION . '"></script>
		<script type="text/javascript" src="' . $scriptpath . 'general.js?v=' . O_SCRIPT_VERSION . '"></script>';
    $lang->load ('editor');
    $str .= '
		<script type="text/javascript">
			' . $lang->editor['editor'] . '
		</script>
		<script type="text/javascript" src="' . $scriptpath . 'editor.js?v=' . O_SCRIPT_VERSION . '"></script>
		<script type="text/javascript">	
			var clickableEditor = new messageEditor("message", {lang: editor_language, rtl: 0});
			clickableEditor.bindSmilieInserter("clickable_smilies");
		</script>
		<!-- end editor -->
	';
    return $str;
  }

  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('E_VERSION', 'v.0.9');
  require_once $rootpath . '/' . $cache . '/smilies.php';
?>
