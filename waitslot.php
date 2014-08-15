<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  require_once 'global.php';
  include_once INC_PATH . '/readconfig_waitslot.php';
  $lang->load ('faq');
  if ($_GET['type'] == 'wait')
  {
    $w1 = sprintf ($lang->faq['wait'], $ratio1, $upload1, $delay1);
    $w2 = sprintf ($lang->faq['wait'], $ratio2, $upload2, $delay2);
    $w3 = sprintf ($lang->faq['wait'], $ratio3, $upload3, $delay3);
    $w4 = sprintf ($lang->faq['wait'], $ratio4, $upload4, $delay4);
  }
  else
  {
    if ($_GET['type'] == 'slot')
    {
      $w1 = sprintf ($lang->faq['slot'], $ratio5, $upload5, $slot1);
      $w2 = sprintf ($lang->faq['slot'], $ratio6, $upload6, $slot2);
      $w3 = sprintf ($lang->faq['slot'], $ratio7, $upload7, $slot3);
      $w4 = sprintf ($lang->faq['slot'], $ratio8, $upload8, $slot4);
    }
    else
    {
      exit ();
    }
  }

  $pic = imagecreate (500, 100);
  $col1 = imagecolorallocate ($pic, 255, 0, 0);
  $col2 = imagecolorallocate ($pic, 255, 128, 64);
  $col3 = imagecolorallocate ($pic, 0, 128, 0);
  $col4 = imagecolorallocate ($pic, 0, 128, 192);
  $colX = imagecolorallocate ($pic, 255, 255, 255);
  imagefilledrectangle ($pic, 0, 0, 500, 100, $colX);
  imagestring ($pic, 3, 5, 20, $w1, $col1);
  imagestring ($pic, 3, 5, 40, $w2, $col2);
  imagestring ($pic, 3, 5, 60, $w3, $col3);
  imagestring ($pic, 3, 5, 80, $w4, $col4);
  header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header ('Content-type: image/jpeg');
  imagejpeg ($pic);
  imagedestroy ($pic);
?>
