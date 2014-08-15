<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function createwavefile ($word)
  {
    global $rootpath;
    $sound_language = '.english';
    $word = strtolower ($word);
    $sound_word = '';
    $i = 0;
    while ($i < strlen ($word))
    {
      $sound_letter = implode ('', file (INC_PATH . '/captcha_fonts/sound/' . $word[$i] . $sound_language . '.wav'));
      if (strpos ($sound_letter, 'data') === false)
      {
        return false;
      }

      $sound_word .= substr ($sound_letter, strpos ($sound_letter, 'data') + 8) . str_repeat (chr (128), rand (700, 710) * 8);
      ++$i;
    }

    $sound_header = array (16, 0, 0, 0, 1, 0, 1, 0, 64, 31, 0, 0, 64, 31, 0, 0, 1, 0, 8, 0, 100, 97, 116, 97);
    $data_size = strlen ($sound_word);
    $file_size = $data_size + 36;
    $i = 0;
    while ($i < $data_size)
    {
      $sound_word[$i] = chr (ord ($sound_word[$i]) + rand (0 - 1, 1));
      $i += rand (1, 10);
    }

    header ('Content-type: audio/x-wav');
    header ('Content-Length: ' . $file_size);
    echo 'RIFF';
    echo chr ($file_size & 255);
    echo chr (($file_size & 65280) >> 8);
    echo chr (($file_size & 16711680) >> 16);
    echo chr (($file_size & 4278190080) >> 24);
    echo 'WAVEfmt ';
    foreach ($sound_header as $char)
    {
      echo chr ($char);
    }

    echo chr ($data_size & 255);
    echo chr (($data_size & 65280) >> 8);
    echo chr (($data_size & 16711680) >> 16);
    echo chr (($data_size & 4278190080) >> 24);
    echo $sound_word;
    exit ();
  }

  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('TS_L', '0.1 by xam');
?>
