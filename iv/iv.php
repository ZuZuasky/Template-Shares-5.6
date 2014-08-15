<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  define ('IV_VERSION', '0.3 by xam');
  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face="verdana" size="2" color="darkred"><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  if ((isset ($_SESSION['error_count']) AND 5 <= $_SESSION['error_count']))
  {
    stdmsg ($lang->global['error'], $lang->global['signupdisabled']);
    stdfoot ();
    exit ();
  }

  if (!isset ($_SESSION['correct_image_entered']))
  {
    if (!$_GET['selected'])
    {
      $amount_of_images_to_show = 4;
      $answer_position = rand (1, $amount_of_images_to_show);
      $answers = array ();
      $allowed_images = array ('png', 'gif', 'jpg');
      if ($handle = opendir ('./iv/images/'))
      {
        while (false !== $file = readdir ($handle))
        {
          if (((((($file != '.' AND $file != '..') AND $file != 'show_image.php') AND $file != '.htaccess') AND $file != 'thumbs.db') AND in_array (strtolower (substr (strrchr ($file, '.'), 1)), $allowed_images)))
          {
            $answers[] = $file;
            continue;
          }
        }

        closedir ($handle);
      }

      $amount = count ($answers);
      $the_answer = rand (1, $amount);
      $i = 1;
      while ($i < $amount_of_images_to_show)
      {
        if ($answer_position == $i)
        {
          $show_image[] = $the_answer;
        }

        $tmp = rand (1, $amount);
        while (($tmp == $the_answer OR (is_array ($show_image) AND in_array ($tmp, $show_image))))
        {
          $tmp = rand (1, $amount);
        }

        $show_image[] = $tmp;
        ++$i;
      }

      if ($answer_position == $i)
      {
        $show_image[] = $the_answer;
      }

      $_SESSION['answer_position'] = $answer_position;
      $_SESSION['show_images'] = $show_image;
      $question = substr ($answers[$the_answer - 1], 0, 0 - 4);
    }
    else
    {
      if ($_GET['selected'] == $_SESSION['answer_position'])
      {
        $_SESSION['correct_image_entered'] = 1;
      }
      else
      {
        $badchoice = 1;
        unset ($_SESSION[correct_image_entered]);
        if (isset ($_SESSION['error_count']))
        {
          ++$_SESSION['error_count'];
        }
        else
        {
          $_SESSION['error_count'] = 1;
        }
      }
    }

    if (!isset ($_SESSION['correct_image_entered']))
    {
      $lang->load ('iv');
      unset ($_SESSION[correct_image_entered]);
      if ($badchoice)
      {
        echo '
			<script type="text/javascript">
				alert("' . $lang->iv['error2'] . '");
			</script>
			' . show_notice (sprintf ($lang->iv['error1'], $_SERVER['SCRIPT_NAME']), true, $lang->iv['title'], '');
      }
      else
      {
        echo '
			<table cellpadding="5" cellspacing="0" border="0" width="100%" align="center">
				<tr>
					<td class="thead">' . $lang->iv['title'] . '</td>
				</tr>			
				<tr>
					<td align="center"><br />
						<div class="panel" style="width:70%">
							' . ($answers ? '
							<a href="' . $_SERVER['SCRIPT_NAME'] . '?selected=1"><img src="' . $BASEURL . '/iv/images/show_image.php?image=1" border="0" alt="" /></a>
							<a href="' . $_SERVER['SCRIPT_NAME'] . '?selected=2"><img src="' . $BASEURL . '/iv/images/show_image.php?image=2" border="0" alt="" /></a>
							<a href="' . $_SERVER['SCRIPT_NAME'] . '?selected=3"><img src="' . $BASEURL . '/iv/images/show_image.php?image=3" border="0" alt="" /></a>
							<a href="' . $_SERVER['SCRIPT_NAME'] . '?selected=4"><img src="' . $BASEURL . '/iv/images/show_image.php?image=4" border="0" alt="" /></a>						
							<br /><br />
							' . $lang->iv['image'] . '<br /><b>' . htmlspecialchars ($question) . '</b>
							<br /><br />
							<input type="button" value="' . $lang->iv['refresh'] . '" onclick="jumpto(\'' . $_SERVER['SCRIPT_NAME'] . '\'); return false;" />
							' : '') . '
						</div>
					</td>
				</tr>
			</table>';
      }

      stdfoot ();
      exit ();
    }
  }

?>
