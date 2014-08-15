<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function print_status ($supported)
  {
    if ($supported)
    {
      echo '<span style="color: #00f">Yes!</span>';
      return null;
    }

    echo '<span style="color: #f00; font-weight: bold">No</span>';
  }

  if (!defined ('SETTING_PANEL_TSSEv56'))
  {
    exit ('Direct initialization of this file is not allowed. Please use settings panel.');
  }

  echo '<html>
<head>
  <title>Securimage Test Script</title>
</head>

<body>

<p>
  This script will test your PHP installation to see if Image Verification script will run on your server.
</p>

<ul>
  <li>
    ';
  echo '<s';
  echo 'trong>GD Support:</strong>
    ';
  print_status ($gd_support = extension_loaded ('gd'));
  echo '  </li>
  ';
  if ($gd_support)
  {
    $gd_info = gd_info ();
  }
  else
  {
    $gd_info = array ();
  }

  echo '  ';
  if ($gd_support)
  {
    echo '  <li>
    ';
    echo '<s';
    echo 'trong>GD Version:</strong>
    ';
    echo $gd_info['GD Version'];
    echo '  </li>
  ';
  }

  echo '  <li>
    ';
  echo '<s';
  echo 'trong>TTF Support (FreeType):</strong>
    ';
  print_status (($gd_support AND $gd_info['FreeType Support']));
  echo '    ';
  if (($gd_support AND $gd_info['FreeType Support'] == false))
  {
    echo '    <br />No FreeType support.  Cannot use TTF fonts, but you can use GD fonts
    ';
  }

  echo '  </li> 
  <li>
    ';
  echo '<s';
  echo 'trong>JPEG Support:</strong>
    ';
  print_status (($gd_support AND $gd_info['JPG Support']));
  echo '  </li>
  <li>
    ';
  echo '<s';
  echo 'trong>PNG Support:</strong>
    ';
  print_status (($gd_support AND $gd_info['PNG Support']));
  echo '  </li>
  <li>
    ';
  echo '<s';
  echo 'trong>GIF Read Support:</strong>
    ';
  print_status (($gd_support AND $gd_info['GIF Read Support']));
  echo '  </li>
  <li>
    ';
  echo '<s';
  echo 'trong>GIF Create Support:</strong>
    ';
  print_status (($gd_support AND $gd_info['GIF Create Support']));
  echo '  </li>
 
</ul>
</body>
</html>';
?>
