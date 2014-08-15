<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  define ('aImaGe_TS_SE', 'v.0.2_by_xam');
  $xqQsTPaCzzRE = strtoupper (htmlspecialchars ($_SERVER['HTTP_HOST']));
  $eGzzQ3_bQtSeVVv = imagecreatefrompng (base64_decode ('b2ZmbGluZS5wbmc='));
  $yXtSExaMqZ290O = imagesx ($eGzzQ3_bQtSeVVv);
  $xyXetSExaMqZ290O121 = imagesy ($eGzzQ3_bQtSeVVv);
  $yXtSExaMqZ290O1 = imagecreatetruecolor ($yXtSExaMqZ290O, $xyXetSExaMqZ290O121);
  imagecopyresampled ($yXtSExaMqZ290O1, $eGzzQ3_bQtSeVVv, 0, 0, 0, 0, $yXtSExaMqZ290O, $xyXetSExaMqZ290O121, $yXtSExaMqZ290O, $xyXetSExaMqZ290O121);
  $yXetSExaMqZ290O12 = imagecolorallocate ($yXtSExaMqZ290O1, 555, 555, 555);
  imagestring ($yXtSExaMqZ290O1, 5, 250, 5, $xqQsTPaCzzRE, $yXetSExaMqZ290O12);
  header (base64_decode ('Q29udGVudC1UeXBlOiBpbWFnZS9qcGVn'));
  imagejpeg ($yXtSExaMqZ290O1);
  echo ' ';
?>
