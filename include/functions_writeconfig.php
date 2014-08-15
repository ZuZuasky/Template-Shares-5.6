<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function writeconfig ($configname = '', $config = array ())
  {
    global $rootpath;
    $path = CONFIG_DIR . '/' . $configname;
    if ((!file_exists ($path) OR !is_writable ($path)))
    {
      trigger_error ('TS SE Critical Error: Failed to read/write config file: ' . $configname . '.');
    }

    $data = @serialize ($config);
    if (!$data)
    {
      trigger_error ('TS SE Critical Error: Failed to serialize config file: ' . $configname . '.');
    }

    if (function_exists ('file_put_contents'))
    {
      if (!$writedata = @file_put_contents ($path, $data))
      {
        trigger_error ('TS SE Critical Error: Failed to write config file: ' . $configname . '.');
        return null;
      }

      if (!$fp = @fopen ($path, 'w'))
      {
        trigger_error ('TS SE Critical Error: Failed to open config file: ' . $configname . '.');
      }

      if (!$Res = @fwrite ($fp, $data))
      {
        trigger_error ('TS SE Critical Error: Failed to write config file: ' . $configname . '.');
      }

      @fclose ($fp);
    }

  }

  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

?>
