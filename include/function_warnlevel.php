<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function get_warn_level ($warn_level = 0)
  {
    global $BASEURL;
    global $pic_base_url;
    global $lang;
    require INC_PATH . '/readconfig_cleanup.php';
    $_image_path = $BASEURL . '/' . $pic_base_url . 'warn/';
    if ($warn_level <= 0)
    {
      $_show_image = $_image_path . 'warn0.gif';
      $warn_percent = 0;
    }
    else
    {
      if ($ban_user_limit <= $warn_level)
      {
        $_show_image = $_image_path . 'warn5.gif';
        $warn_percent = 100;
      }
      else
      {
        $warn_percent = ($warn_level ? sprintf ('%.0f', $warn_level / $ban_user_limit * 100) : 0);
        if (100 < $warn_percent)
        {
          $warn_percent = 100;
        }

        if (81 <= $warn_percent)
        {
          $_show_image = $_image_path . 'warn5.gif';
        }
        else
        {
          if (61 <= $warn_percent)
          {
            $_show_image = $_image_path . 'warn4.gif';
          }
          else
          {
            if (41 <= $warn_percent)
            {
              $_show_image = $_image_path . 'warn3.gif';
            }
            else
            {
              if (21 <= $warn_percent)
              {
                $_show_image = $_image_path . 'warn2.gif';
              }
              else
              {
                if (1 <= $warn_percent)
                {
                  $_show_image = $_image_path . 'warn1.gif';
                }
                else
                {
                  $_show_image = $_image_path . 'warn0.gif';
                }
              }
            }
          }
        }
      }
    }

    if ($warn_percent < 1)
    {
      $warn_percent = 0;
    }

    return $lang->global['imgwarned'] . ': (' . $warn_percent . '%) <img src="' . $_show_image . '" alt="" border="0" class="inlineimg" />';
  }

?>
