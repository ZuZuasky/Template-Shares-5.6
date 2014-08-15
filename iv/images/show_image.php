<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  @session_name ('TSSE_Session');
  @session_start ();
  if (!isset ($_GET['image']))
  {
    exit ();
  }

  header ('Cache-Control: no-cache, must-revalidate');
  header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  define ('SI_VERSION', '0.2 by xam');
  $answers = array ();
  $allowed_images = array ('png', 'gif', 'jpg');
  if ($handle = opendir ('.'))
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
    if (count ($answers))
    {
      if ($image = $_SESSION['show_images'][intval ($_GET['image']) - 1])
      {
        $im = imagecreatefromjpeg ($answers[$image - 1]);
        header ('Content-type: image/jpeg');
        imagejpeg ($im);
        imagedestroy ($im);
      }
    }
  }

  exit ();
?>
