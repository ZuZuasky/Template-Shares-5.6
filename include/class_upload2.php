<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  class upload
  {
    var $file_src_name = null;
    var $file_src_name_body = null;
    var $file_src_name_ext = null;
    var $file_src_mime = null;
    var $file_src_size = null;
    var $file_src_error = null;
    var $file_src_pathname = null;
    var $file_src_temp = null;
    var $file_dst_path = null;
    var $file_dst_name = null;
    var $file_dst_name_body = null;
    var $file_dst_name_ext = null;
    var $file_dst_pathname = null;
    var $image_src_x = null;
    var $image_src_y = null;
    var $image_src_bits = null;
    var $image_src_pixels = null;
    var $image_src_type = null;
    var $image_dst_x = null;
    var $image_dst_y = null;
    var $image_supported = null;
    var $file_is_image = null;
    var $uploaded = null;
    var $no_upload_check = null;
    var $processed = null;
    var $error = null;
    var $log = null;
    var $file_new_name_body = null;
    var $file_name_body_add = null;
    var $file_new_name_ext = null;
    var $file_safe_name = null;
    var $mime_check = null;
    var $mime_magic_check = null;
    var $no_script = null;
    var $file_auto_rename = null;
    var $dir_auto_create = null;
    var $dir_auto_chmod = null;
    var $dir_chmod = null;
    var $file_overwrite = null;
    var $file_max_size = null;
    var $image_resize = null;
    var $image_convert = null;
    var $image_x = null;
    var $image_y = null;
    var $image_ratio = null;
    var $image_ratio_crop = null;
    var $image_ratio_fill = null;
    var $image_ratio_pixels = null;
    var $image_ratio_no_zoom_in = null;
    var $image_ratio_no_zoom_out = null;
    var $image_ratio_x = null;
    var $image_ratio_y = null;
    var $image_max_width = null;
    var $image_max_height = null;
    var $image_max_pixels = null;
    var $image_max_ratio = null;
    var $image_min_width = null;
    var $image_min_height = null;
    var $image_min_pixels = null;
    var $image_min_ratio = null;
    var $jpeg_quality = null;
    var $jpeg_size = null;
    var $preserve_transparency = null;
    var $image_is_transparent = null;
    var $image_transparent_color = null;
    var $image_background_color = null;
    var $image_default_color = null;
    var $image_is_palette = null;
    var $image_brightness = null;
    var $image_contrast = null;
    var $image_threshold = null;
    var $image_tint_color = null;
    var $image_overlay_color = null;
    var $image_overlay_percent = null;
    var $image_negative = null;
    var $image_greyscale = null;
    var $image_text = null;
    var $image_text_direction = null;
    var $image_text_color = null;
    var $image_text_percent = null;
    var $image_text_background = null;
    var $image_text_background_percent = null;
    var $image_text_font = null;
    var $image_text_position = null;
    var $image_text_x = null;
    var $image_text_y = null;
    var $image_text_padding = null;
    var $image_text_padding_x = null;
    var $image_text_padding_y = null;
    var $image_text_alignment = null;
    var $image_text_line_spacing = null;
    var $image_reflection_height = null;
    var $image_reflection_space = null;
    var $image_reflection_color = null;
    var $image_reflection_opacity = null;
    var $image_flip = null;
    var $image_rotate = null;
    var $image_crop = null;
    var $image_bevel = null;
    var $image_bevel_color1 = null;
    var $image_bevel_color2 = null;
    var $image_border = null;
    var $image_border_color = null;
    var $image_frame = null;
    var $image_frame_colors = null;
    var $image_watermark = null;
    var $image_watermark_position = null;
    var $image_watermark_x = null;
    var $image_watermark_y = null;
    var $allowed = null;
    var $forbidden = null;
    var $translation = null;
    var $language = null;
    function init ()
    {
      $this->file_new_name_body = '';
      $this->file_name_body_add = '';
      $this->file_new_name_ext = '';
      $this->file_safe_name = true;
      $this->file_overwrite = false;
      $this->file_auto_rename = true;
      $this->dir_auto_create = true;
      $this->dir_auto_chmod = true;
      $this->dir_chmod = 511;
      $this->mime_check = true;
      $this->mime_magic_check = false;
      $this->no_script = true;
      $val = trim (ini_get ('upload_max_filesize'));
      $last = strtolower ($val[strlen ($val) - 1]);
      switch ($last)
      {
        case 'g':
        {
          $val *= 1024;
        }

        case 'm':
        {
          $val *= 1024;
        }

        case 'k':
        {
          $val *= 1024;
        }
      }

      $this->file_max_size = $val;
      $this->image_resize = false;
      $this->image_convert = '';
      $this->image_x = 150;
      $this->image_y = 150;
      $this->image_ratio = false;
      $this->image_ratio_crop = false;
      $this->image_ratio_fill = false;
      $this->image_ratio_pixels = false;
      $this->image_ratio_no_zoom_in = false;
      $this->image_ratio_no_zoom_out = false;
      $this->image_ratio_x = false;
      $this->image_ratio_y = false;
      $this->jpeg_quality = 85;
      $this->jpeg_size = null;
      $this->preserve_transparency = false;
      $this->image_is_transparent = false;
      $this->image_transparent_color = null;
      $this->image_background_color = null;
      $this->image_default_color = '#ffffff';
      $this->image_is_palette = false;
      $this->image_max_width = null;
      $this->image_max_height = null;
      $this->image_max_pixels = null;
      $this->image_max_ratio = null;
      $this->image_min_width = null;
      $this->image_min_height = null;
      $this->image_min_pixels = null;
      $this->image_min_ratio = null;
      $this->image_brightness = null;
      $this->image_contrast = null;
      $this->image_threshold = null;
      $this->image_tint_color = null;
      $this->image_overlay_color = null;
      $this->image_overlay_percent = null;
      $this->image_negative = false;
      $this->image_greyscale = false;
      $this->image_text = null;
      $this->image_text_direction = null;
      $this->image_text_color = '#FFFFFF';
      $this->image_text_percent = 100;
      $this->image_text_background = null;
      $this->image_text_background_percent = 100;
      $this->image_text_font = 5;
      $this->image_text_x = null;
      $this->image_text_y = null;
      $this->image_text_position = null;
      $this->image_text_padding = 0;
      $this->image_text_padding_x = null;
      $this->image_text_padding_y = null;
      $this->image_text_alignment = 'C';
      $this->image_text_line_spacing = 0;
      $this->image_reflection_height = null;
      $this->image_reflection_space = 2;
      $this->image_reflection_color = '#ffffff';
      $this->image_reflection_opacity = 60;
      $this->image_watermark = null;
      $this->image_watermark_x = null;
      $this->image_watermark_y = null;
      $this->image_watermark_position = null;
      $this->image_flip = null;
      $this->image_rotate = null;
      $this->image_crop = null;
      $this->image_bevel = null;
      $this->image_bevel_color1 = '#FFFFFF';
      $this->image_bevel_color2 = '#000000';
      $this->image_border = null;
      $this->image_border_color = '#FFFFFF';
      $this->image_frame = null;
      $this->image_frame_colors = '#FFFFFF #999999 #666666 #000000';
      $this->forbidden = array ();
      $this->allowed = array ('application/rar', 'application/x-rar-compressed', 'application/arj', 'application/excel', 'application/gnutar', 'application/octet-stream', 'application/pdf', 'application/powerpoint', 'application/postscript', 'application/plain', 'application/rtf', 'application/vocaltec-media-file', 'application/wordperfect', 'application/x-bzip', 'application/x-bzip2', 'application/x-compressed', 'application/x-excel', 'application/x-gzip', 'application/x-latex', 'application/x-midi', 'application/x-msexcel', 'application/x-rtf', 'application/x-sit', 'application/x-stuffit', 'application/x-shockwave-flash', 'application/x-troff-msvideo', 'application/x-zip-compressed', 'application/xml', 'application/zip', 'application/msword', 'application/mspowerpoint', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint', 'application/vnd.ms-word', 'application/vnd.ms-word.document.macroEnabled.12', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-word.template.macroEnabled.12', 'application/vnd.openxmlformats-officedocument.wordprocessingml.template', 'application/vnd.ms-powerpoint.template.macroEnabled.12', 'application/vnd.openxmlformats-officedocument.presentationml.template', 'application/vnd.ms-powerpoint.addin.macroEnabled.12', 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12', 'application/vnd.openxmlformats-officedocument.presentationml.slideshow', 'application/vnd.ms-powerpoint.presentation.macroEnabled.12', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/vnd.ms-excel.addin.macroEnabled.12', 'application/vnd.ms-excel.sheet.binary.macroEnabled.12', 'application/vnd.ms-excel.sheet.macroEnabled.12', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel.template.macroEnabled.12', 'application/vnd.openxmlformats-officedocument.spreadsheetml.template', 'audio/*', 'image/*', 'video/*', 'multipart/x-zip', 'multipart/x-gzip', 'text/richtext', 'text/plain', 'text/xml');
    }

    function upload ($file, $lang = 'en_GB')
    {
      $this->file_src_name = '';
      $this->file_src_name_body = '';
      $this->file_src_name_ext = '';
      $this->file_src_mime = '';
      $this->file_src_size = '';
      $this->file_src_error = '';
      $this->file_src_pathname = '';
      $this->file_src_temp = '';
      $this->file_dst_path = '';
      $this->file_dst_name = '';
      $this->file_dst_name_body = '';
      $this->file_dst_name_ext = '';
      $this->file_dst_pathname = '';
      $this->image_src_x = null;
      $this->image_src_y = null;
      $this->image_src_bits = null;
      $this->image_src_type = null;
      $this->image_src_pixels = null;
      $this->image_dst_x = 0;
      $this->image_dst_y = 0;
      $this->uploaded = true;
      $this->no_upload_check = false;
      $this->processed = true;
      $this->error = '';
      $this->log = '';
      $this->allowed = array ();
      $this->forbidden = array ();
      $this->file_is_image = false;
      $this->init ();
      $info = null;
      $this->translation = array ();
      $this->translation['file_error'] = 'File error. Please try again.';
      $this->translation['local_file_missing'] = 'Local file doesn\'t exist.';
      $this->translation['local_file_not_readable'] = 'Local file is not readable.';
      $this->translation['uploaded_too_big_ini'] = 'File upload error (the uploaded file exceeds the upload_max_filesize directive in php.ini).';
      $this->translation['uploaded_too_big_html'] = 'File upload error (the uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form).';
      $this->translation['uploaded_partial'] = 'File upload error (the uploaded file was only partially uploaded).';
      $this->translation['uploaded_missing'] = 'File upload error (no file was uploaded).';
      $this->translation['uploaded_unknown'] = 'File upload error (unknown error code).';
      $this->translation['try_again'] = 'File upload error. Please try again.';
      $this->translation['file_too_big'] = 'File too big.';
      $this->translation['no_mime'] = 'MIME type can\'t be detected.';
      $this->translation['incorrect_file'] = 'Incorrect type of file.';
      $this->translation['image_too_wide'] = 'Image too wide.';
      $this->translation['image_too_narrow'] = 'Image too narrow.';
      $this->translation['image_too_high'] = 'Image too high.';
      $this->translation['image_too_short'] = 'Image too short.';
      $this->translation['ratio_too_high'] = 'Image ratio too high (image too wide).';
      $this->translation['ratio_too_low'] = 'Image ratio too low (image too high).';
      $this->translation['too_many_pixels'] = 'Image has too many pixels.';
      $this->translation['not_enough_pixels'] = 'Image has not enough pixels.';
      $this->translation['file_not_uploaded'] = 'File not uploaded. Can\'t carry on a process.';
      $this->translation['already_exists'] = '%s already exists. Please change the file name.';
      $this->translation['temp_file_missing'] = 'No correct temp source file. Can\'t carry on a process.';
      $this->translation['source_missing'] = 'No correct uploaded source file. Can\'t carry on a process.';
      $this->translation['destination_dir'] = 'Destination directory can\'t be created. Can\'t carry on a process.';
      $this->translation['destination_dir_missing'] = 'Destination directory doesn\'t exist. Can\'t carry on a process.';
      $this->translation['destination_path_not_dir'] = 'Destination path is not a directory. Can\'t carry on a process.';
      $this->translation['destination_dir_write'] = 'Destination directory can\'t be made writeable. Can\'t carry on a process.';
      $this->translation['destination_path_write'] = 'Destination path is not a writeable. Can\'t carry on a process.';
      $this->translation['temp_file'] = 'Can\'t create the temporary file. Can\'t carry on a process.';
      $this->translation['source_not_readable'] = 'Source file is not readable. Can\'t carry on a process.';
      $this->translation['no_create_support'] = 'No create from %s support.';
      $this->translation['create_error'] = 'Error in creating %s image from source.';
      $this->translation['source_invalid'] = 'Can\'t read image source. Not an image?.';
      $this->translation['gd_missing'] = 'GD doesn\'t seem to be present.';
      $this->translation['watermark_no_create_support'] = 'No create from %s support, can\'t read watermark.';
      $this->translation['watermark_create_error'] = 'No %s read support, can\'t create watermark.';
      $this->translation['watermark_invalid'] = 'Unknown image format, can\'t read watermark.';
      $this->translation['file_create'] = 'No %s create support.';
      $this->translation['no_conversion_type'] = 'No conversion type defined.';
      $this->translation['copy_failed'] = 'Error copying file on the server. copy() failed.';
      $this->translation['reading_failed'] = 'Error reading the file.';
      $this->lang = $lang;
      if (($this->lang != 'en_GB' AND file_exists ('lang/class.upload.' . $lang . '.php')))
      {
        $translation = null;
        include 'lang/class.upload.' . $lang . '.php';
        if (is_array ($translation))
        {
          $this->translation = array_merge ($this->translation, $translation);
        }
        else
        {
          $this->lang = 'en_GB';
        }
      }

      $this->image_supported = array ();
      if ($this->gdversion ())
      {
        if (imagetypes () & IMG_GIF)
        {
          $this->image_supported['image/gif'] = 'gif';
        }

        if (imagetypes () & IMG_JPG)
        {
          $this->image_supported['image/jpg'] = 'jpg';
          $this->image_supported['image/jpeg'] = 'jpg';
          $this->image_supported['image/pjpeg'] = 'jpg';
        }

        if (imagetypes () & IMG_PNG)
        {
          $this->image_supported['image/png'] = 'png';
          $this->image_supported['image/x-png'] = 'png';
        }

        if (imagetypes () & IMG_WBMP)
        {
          $this->image_supported['image/bmp'] = 'bmp';
          $this->image_supported['image/x-ms-bmp'] = 'bmp';
          $this->image_supported['image/x-windows-bmp'] = 'bmp';
        }
      }

      if (empty ($this->log))
      {
        $this->log .= '<b>system information</b><br />';
        $inis = ini_get_all ();
        $open_basedir = (((array_key_exists ('open_basedir', $inis) AND array_key_exists ('local_value', $inis['open_basedir'])) AND !empty ($inis['open_basedir']['local_value'])) ? $inis['open_basedir']['local_value'] : false);
        $gd = ($this->gdversion () ? $this->gdversion (true) : 'GD not present');
        $supported = trim ((in_array ('png', $this->image_supported) ? 'png' : '') . ' ' . (in_array ('jpg', $this->image_supported) ? 'jpg' : '') . ' ' . (in_array ('gif', $this->image_supported) ? 'gif' : '') . ' ' . (in_array ('bmp', $this->image_supported) ? 'bmp' : ''));
        $this->log .= '-&nbsp;GD version              : ' . $gd . '<br />';
        $this->log .= '-&nbsp;supported image types   : ' . (!empty ($supported) ? $supported : 'none') . '<br />';
        $this->log .= '-&nbsp;open_basedir            : ' . (!empty ($open_basedir) ? $open_basedir : 'no restriction') . '<br />';
        $this->log .= '-&nbsp;language                : ' . $this->lang . '<br />';
      }

      if (!$file)
      {
        $this->uploaded = false;
        $this->error = $this->translate ('file_error');
      }

      if (!is_array ($file))
      {
        if (empty ($file))
        {
          $this->uploaded = false;
          $this->error = $this->translate ('file_error');
        }
        else
        {
          $this->no_upload_check = TRUE;
          $this->log .= '<b>' . $this->translate ('source is a local file') . ' ' . $file . '</b><br />';
          if (($this->uploaded AND !file_exists ($file)))
          {
            $this->uploaded = false;
            $this->error = $this->translate ('local_file_missing');
          }

          if (($this->uploaded AND !is_readable ($file)))
          {
            $this->uploaded = false;
            $this->error = $this->translate ('local_file_not_readable');
          }

          if ($this->uploaded)
          {
            $this->file_src_pathname = $file;
            $this->file_src_name = basename ($file);
            $this->log .= '- local file name OK<br />';
            ereg ('\\.([^\\.]*$)', $this->file_src_name, $extension);
            if (is_array ($extension))
            {
              $this->file_src_name_ext = strtolower ($extension[1]);
              $this->file_src_name_body = substr ($this->file_src_name, 0, strlen ($this->file_src_name) - strlen ($this->file_src_name_ext) - 1);
            }
            else
            {
              $this->file_src_name_ext = '';
              $this->file_src_name_body = $this->file_src_name;
            }

            $this->file_src_size = (file_exists ($file) ? filesize ($file) : 0);
            $info = getimagesize ($this->file_src_pathname);
            $this->file_src_mime = ((is_array ($info) AND array_key_exists ('mime', $info)) ? $info['mime'] : null);
            if (empty ($this->file_src_mime))
            {
              $mime = ((is_array ($info) AND array_key_exists (2, $info)) ? $info[2] : null);
              $this->file_src_mime = ($mime == IMAGETYPE_GIF ? 'image/gif' : ($mime == IMAGETYPE_JPEG ? 'image/jpeg' : ($mime == IMAGETYPE_PNG ? 'image/png' : ($mime == IMAGETYPE_BMP ? 'image/bmp' : null))));
            }

            if ((empty ($this->file_src_mime) AND function_exists ('mime_content_type')))
            {
              $this->file_src_mime = mime_content_type ($this->file_src_pathname);
            }

            $this->file_src_error = 0;
            if (array_key_exists ($this->file_src_mime, $this->image_supported))
            {
              $this->file_is_image = true;
              $this->image_src_type = $this->image_supported[$this->file_src_mime];
            }
          }
        }
      }
      else
      {
        $this->log .= '<b>source is an uploaded file</b><br />';
        if ($this->uploaded)
        {
          $this->file_src_error = $file['error'];
          switch ($this->file_src_error)
          {
            case 0:
            {
              $this->log .= '- upload OK<br />';
              break;
            }

            case 1:
            {
              $this->uploaded = false;
              $this->error = $this->translate ('uploaded_too_big_ini');
              break;
            }

            case 2:
            {
              $this->uploaded = false;
              $this->error = $this->translate ('uploaded_too_big_html');
              break;
            }

            case 3:
            {
              $this->uploaded = false;
              $this->error = $this->translate ('uploaded_partial');
              break;
            }

            case 4:
            {
              $this->uploaded = false;
              $this->error = $this->translate ('uploaded_missing');
              break;
            }

            default:
            {
              $this->uploaded = false;
              $this->error = $this->translate ('uploaded_unknown');
            }
          }
        }

        if ($this->uploaded)
        {
          $this->file_src_pathname = $file['tmp_name'];
          $this->file_src_name = $file['name'];
          if ($this->file_src_name == '')
          {
            $this->uploaded = false;
            $this->error = $this->translate ('try_again');
          }
        }

        if ($this->uploaded)
        {
          $this->log .= '- file name OK<br />';
          ereg ('\\.([^\\.]*$)', $this->file_src_name, $extension);
          if (is_array ($extension))
          {
            $this->file_src_name_ext = strtolower ($extension[1]);
            $this->file_src_name_body = substr ($this->file_src_name, 0, strlen ($this->file_src_name) - strlen ($this->file_src_name_ext) - 1);
          }
          else
          {
            $this->file_src_name_ext = '';
            $this->file_src_name_body = $this->file_src_name;
          }

          $this->file_src_size = $file['size'];
          $this->file_src_mime = $file['type'];
          if (array_key_exists ($this->file_src_mime, $this->image_supported))
          {
            $this->file_is_image = true;
            $this->image_src_type = $this->image_supported[$this->file_src_mime];
            $info = @getimagesize ($this->file_src_pathname);
          }
        }
      }

      if ($this->file_is_image)
      {
        if (is_array ($info))
        {
          $this->image_src_x = $info[0];
          $this->image_src_y = $info[1];
          $this->image_src_pixels = $this->image_src_x * $this->image_src_y;
          $this->image_src_bits = (array_key_exists ('bits', $info) ? $info['bits'] : null);
        }
        else
        {
          $this->log .= '- can\'t retrieve image information. open_basedir restriction in place?<br />';
        }
      }

      $this->log .= '- source variables<br />';
      $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;file_src_name         : ' . $this->file_src_name . '<br />';
      $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;file_src_name_body    : ' . $this->file_src_name_body . '<br />';
      $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;file_src_name_ext     : ' . $this->file_src_name_ext . '<br />';
      $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;file_src_pathname     : ' . $this->file_src_pathname . '<br />';
      $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;file_src_mime         : ' . $this->file_src_mime . '<br />';
      $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;file_src_size         : ' . $this->file_src_size . ' (max= ' . $this->file_max_size . ')<br />';
      $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;file_src_error        : ' . $this->file_src_error . '<br />';
      if ($this->file_is_image)
      {
        $this->log .= '- source file is an image<br />';
        $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;image_src_x           : ' . $this->image_src_x . '<br />';
        $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;image_src_y           : ' . $this->image_src_y . '<br />';
        $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;image_src_pixels      : ' . $this->image_src_pixels . '<br />';
        $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;image_src_type        : ' . $this->image_src_type . '<br />';
        $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;image_src_bits        : ' . $this->image_src_bits . '<br />';
      }

    }

    function gdversion ($full = false)
    {
      static $gd_version = null;
      static $gd_full_version = null;
      if ($gd_version === null)
      {
        if (function_exists ('gd_info'))
        {
          $gd = gd_info ();
          $gd = $gd['GD Version'];
          $regex = '/([\\d\\.]+)/i';
        }
        else
        {
          ob_start ();
          phpinfo (8);
          $gd = ob_get_contents ();
          ob_end_clean ();
          $regex = '/\\bgd\\s+version\\b[^\\d

]+?([\\d\\.]+)/i';
        }

        if (preg_match ($regex, $gd, $m))
        {
          $gd_full_version = (string)$m[1];
          $gd_version = (double)$m[1];
        }
        else
        {
          $gd_full_version = 'none';
          $gd_version = 0;
        }
      }

      if ($full)
      {
        return $gd_full_version;
      }

      return $gd_version;
    }

    function rmkdir ($path, $mode = 511)
    {
      return (is_dir ($path) OR ($this->rmkdir (dirname ($path), $mode) AND $this->_mkdir ($path, $mode)));
    }

    function _mkdir ($path, $mode = 511)
    {
      $old = umask (0);
      $res = @mkdir ($path, $mode);
      umask ($old);
      return $res;
    }

    function translate ($str, $tokens = array ())
    {
      if (array_key_exists ($str, $this->translation))
      {
        $str = $this->translation[$str];
      }

      if ((is_array ($tokens) AND 0 < sizeof ($tokens)))
      {
        $str = vsprintf ($str, $tokens);
      }

      return $str;
    }

    function imagecreatenew ($x, $y, $fill = true, $trsp = false)
    {
      if ((2 <= $this->gdversion () AND !$this->image_is_palette))
      {
        $dst_im = imagecreatetruecolor ($x, $y);
        if ((empty ($this->image_background_color) OR $trsp))
        {
          imagealphablending ($dst_im, false);
          imagefilledrectangle ($dst_im, 0, 0, $x, $y, imagecolorallocatealpha ($dst_im, 0, 0, 0, 127));
        }
      }
      else
      {
        $dst_im = imagecreate ($x, $y);
        if (((($fill AND $this->image_is_transparent) AND empty ($this->image_background_color)) OR $trsp))
        {
          imagefilledrectangle ($dst_im, 0, 0, $x, $y, $this->image_transparent_color);
          imagecolortransparent ($dst_im, $this->image_transparent_color);
        }
      }

      if ((($fill AND !empty ($this->image_background_color)) AND !$trsp))
      {
        sscanf ($this->image_background_color, '#%2x%2x%2x', $red, $green, $blue);
        $background_color = imagecolorallocate ($dst_im, $red, $green, $blue);
        imagefilledrectangle ($dst_im, 0, 0, $x, $y, $background_color);
      }

      return $dst_im;
    }

    function imagetransfer ($src_im, $dst_im)
    {
      if (is_resource ($dst_im))
      {
        imagedestroy ($dst_im);
      }

      $dst_im = &$src_im;
      return $dst_im;
    }

    function imagecopymergealpha (&$dst_im, &$src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct = 0)
    {
      $dst_x = (int)$dst_x;
      $dst_y = (int)$dst_y;
      $src_x = (int)$src_x;
      $src_y = (int)$src_y;
      $src_w = (int)$src_w;
      $src_h = (int)$src_h;
      $pct = (int)$pct;
      $dst_w = imagesx ($dst_im);
      $dst_h = imagesy ($dst_im);
      $y = $src_y;
      while ($y < $src_h)
      {
        $x = $src_x;
        while ($x < $src_w)
        {
          if ((((0 <= $x AND $x <= $dst_w) AND 0 <= $y) AND $y <= $dst_h))
          {
            $dst_pixel = imagecolorsforindex ($dst_im, imagecolorat ($dst_im, $x + $dst_x, $y + $dst_y));
            $src_pixel = imagecolorsforindex ($src_im, imagecolorat ($src_im, $x + $src_x, $y + $src_y));
            $src_alpha = 1 - $src_pixel['alpha'] / 127;
            $dst_alpha = 1 - $dst_pixel['alpha'] / 127;
            $opacity = $src_alpha * $pct / 100;
            if ($opacity <= $dst_alpha)
            {
              $alpha = $dst_alpha;
            }

            if ($dst_alpha < $opacity)
            {
              $alpha = $opacity;
            }

            if (1 < $alpha)
            {
              $alpha = 1;
            }

            if (0 < $opacity)
            {
              $dst_red = round ($dst_pixel['red'] * $dst_alpha * (1 - $opacity));
              $dst_green = round ($dst_pixel['green'] * $dst_alpha * (1 - $opacity));
              $dst_blue = round ($dst_pixel['blue'] * $dst_alpha * (1 - $opacity));
              $src_red = round ($src_pixel['red'] * $opacity);
              $src_green = round ($src_pixel['green'] * $opacity);
              $src_blue = round ($src_pixel['blue'] * $opacity);
              $red = round (($dst_red + $src_red) / ($dst_alpha * (1 - $opacity) + $opacity));
              $green = round (($dst_green + $src_green) / ($dst_alpha * (1 - $opacity) + $opacity));
              $blue = round (($dst_blue + $src_blue) / ($dst_alpha * (1 - $opacity) + $opacity));
              if (255 < $red)
              {
                $red = 255;
              }

              if (255 < $green)
              {
                $green = 255;
              }

              if (255 < $blue)
              {
                $blue = 255;
              }

              $alpha = round ((1 - $alpha) * 127);
              $color = imagecolorallocatealpha ($dst_im, $red, $green, $blue, $alpha);
              imagesetpixel ($dst_im, $x + $dst_x, $y + $dst_y, $color);
            }
          }

          ++$x;
        }

        ++$y;
      }

      return true;
    }

    function process ($server_path = null)
    {
      $this->error = '';
      $this->processed = true;
      $return_mode = false;
      $return_content = null;
      if ((empty ($server_path) OR is_null ($server_path)))
      {
        $this->log .= '<b>process file and return the content</b><br />';
        $return_mode = true;
      }
      else
      {
        if (strtolower (substr (PHP_OS, 0, 3)) === 'win')
        {
          if (substr ($server_path, 0 - 1, 1) != '\\')
          {
            $server_path = $server_path . '\\';
          }
        }
        else
        {
          if (substr ($server_path, 0 - 1, 1) != '/')
          {
            $server_path = $server_path . '/';
          }
        }

        $this->log .= '<b>process file to ' . $server_path . '</b><br />';
      }

      if ($this->uploaded)
      {
        if ($this->file_max_size < $this->file_src_size)
        {
          $this->processed = false;
          $this->error = $this->translate ('file_too_big');
        }
        else
        {
          $this->log .= '- file size OK<br />';
        }

        if ($this->no_script)
        {
          if (((((substr ($this->file_src_mime, 0, 5) == 'text/' OR strpos ($this->file_src_mime, 'javascript') !== false) AND substr ($this->file_src_name, 0 - 4) != '.txt') OR preg_match ('/\\.(php|pl|py|cgi|asp)$/i', $this->file_src_name)) OR empty ($this->file_src_name_ext)))
          {
            $this->file_src_mime = 'text/plain';
            $this->log .= '- script ' . $this->file_src_name . ' renamed as ' . $this->file_src_name . '.txt!<br />';
            $this->file_src_name_ext .= (empty ($this->file_src_name_ext) ? 'txt' : '.txt');
          }
        }

        if (($this->mime_magic_check AND function_exists ('mime_content_type')))
        {
          $detected_mime = mime_content_type ($this->file_src_pathname);
          if ($this->file_src_mime != $detected_mime)
          {
            $this->log .= '- MIME type detected as ' . $detected_mime . ' but given as ' . $this->file_src_mime . '!<br />';
            $this->file_src_mime = $detected_mime;
          }
        }

        if (($this->mime_check AND empty ($this->file_src_mime)))
        {
          $this->processed = false;
          $this->error = $this->translate ('no_mime');
        }
        else
        {
          if ((($this->mime_check AND !empty ($this->file_src_mime)) AND strpos ($this->file_src_mime, '/') !== false))
          {
            list ($m1, $m2) = explode ('/', $this->file_src_mime);
            $allowed = false;
            foreach ($this->allowed as $k => $v)
            {
              list ($v1, $v2) = explode ('/', $v);
              if ((($v1 == '*' AND $v2 == '*') OR ($v1 == $m1 AND ($v2 == $m2 OR $v2 == '*'))))
              {
                $allowed = true;
                break;
              }
            }

            foreach ($this->forbidden as $k => $v)
            {
              list ($v1, $v2) = explode ('/', $v);
              if ((($v1 == '*' AND $v2 == '*') OR ($v1 == $m1 AND ($v2 == $m2 OR $v2 == '*'))))
              {
                $allowed = false;
                break;
              }
            }

            if (!$allowed)
            {
              $this->processed = false;
              $this->error = $this->translate ('incorrect_file');
            }
            else
            {
              $this->log .= '- file mime OK : ' . $this->file_src_mime . '<br />';
            }
          }
          else
          {
            $this->log .= '- file mime OK : ' . $this->file_src_mime . '<br />';
          }
        }

        if ($this->file_is_image)
        {
          if ((is_numeric ($this->image_src_x) AND is_numeric ($this->image_src_y)))
          {
            $ratio = $this->image_src_x / $this->image_src_y;
            if ((!is_null ($this->image_max_width) AND $this->image_max_width < $this->image_src_x))
            {
              $this->processed = false;
              $this->error = $this->translate ('image_too_wide');
            }

            if ((!is_null ($this->image_min_width) AND $this->image_src_x < $this->image_min_width))
            {
              $this->processed = false;
              $this->error = $this->translate ('image_too_narrow');
            }

            if ((!is_null ($this->image_max_height) AND $this->image_max_height < $this->image_src_y))
            {
              $this->processed = false;
              $this->error = $this->translate ('image_too_high');
            }

            if ((!is_null ($this->image_min_height) AND $this->image_src_y < $this->image_min_height))
            {
              $this->processed = false;
              $this->error = $this->translate ('image_too_short');
            }

            if ((!is_null ($this->image_max_ratio) AND $this->image_max_ratio < $ratio))
            {
              $this->processed = false;
              $this->error = $this->translate ('ratio_too_high');
            }

            if ((!is_null ($this->image_min_ratio) AND $ratio < $this->image_min_ratio))
            {
              $this->processed = false;
              $this->error = $this->translate ('ratio_too_low');
            }

            if ((!is_null ($this->image_max_pixels) AND $this->image_max_pixels < $this->image_src_pixels))
            {
              $this->processed = false;
              $this->error = $this->translate ('too_many_pixels');
            }

            if ((!is_null ($this->image_min_pixels) AND $this->image_src_pixels < $this->image_min_pixels))
            {
              $this->processed = false;
              $this->error = $this->translate ('not_enough_pixels');
            }
          }
          else
          {
            $this->log .= '- no image properties available, can\'t enforce dimension checks : ' . $this->file_src_mime . '<br />';
          }
        }
      }
      else
      {
        $this->error = $this->translate ('file_not_uploaded');
        $this->processed = false;
      }

      if ($this->processed)
      {
        $this->file_dst_path = $server_path;
        $this->file_dst_name = $this->file_src_name;
        $this->file_dst_name_body = $this->file_src_name_body;
        $this->file_dst_name_ext = $this->file_src_name_ext;
        if ($this->image_convert != '')
        {
          $this->file_dst_name_ext = $this->image_convert;
          $this->log .= '- new file name ext : ' . $this->image_convert . '<br />';
        }

        if ($this->file_new_name_body != '')
        {
          $this->file_dst_name_body = $this->file_new_name_body;
          $this->log .= '- new file name body : ' . $this->file_new_name_body . '<br />';
        }

        if ($this->file_new_name_ext != '')
        {
          $this->file_dst_name_ext = $this->file_new_name_ext;
          $this->log .= '- new file name ext : ' . $this->file_new_name_ext . '<br />';
        }

        if ($this->file_name_body_add != '')
        {
          $this->file_dst_name_body = $this->file_dst_name_body . $this->file_name_body_add;
          $this->log .= '- file name body add : ' . $this->file_name_body_add . '<br />';
        }

        if ($this->file_safe_name)
        {
          $this->file_dst_name_body = str_replace (array (' ', '-'), array ('_', '_'), $this->file_dst_name_body);
          $this->file_dst_name_body = ereg_replace ('[^A-Za-z0-9_]', '', $this->file_dst_name_body);
          $this->log .= '- file name safe format<br />';
        }

        $this->log .= '- destination variables<br />';
        if ((empty ($this->file_dst_path) OR is_null ($this->file_dst_path)))
        {
          $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;file_dst_path         : n/a<br />';
        }
        else
        {
          $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;file_dst_path         : ' . $this->file_dst_path . '<br />';
        }

        $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;file_dst_name_body    : ' . $this->file_dst_name_body . '<br />';
        $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;file_dst_name_ext     : ' . $this->file_dst_name_ext . '<br />';
        $image_manipulation = ($this->file_is_image AND (((((((((((((((((($this->image_resize OR $this->image_convert != '') OR is_numeric ($this->image_brightness)) OR is_numeric ($this->image_contrast)) OR is_numeric ($this->image_threshold)) OR !empty ($this->image_tint_color)) OR !empty ($this->image_overlay_color)) OR !empty ($this->image_text)) OR $this->image_greyscale) OR $this->image_negative) OR !empty ($this->image_watermark)) OR is_numeric ($this->image_rotate)) OR is_numeric ($this->jpeg_size)) OR !empty ($this->image_flip)) OR !empty ($this->image_crop)) OR !empty ($this->image_border)) OR 0 < $this->image_frame) OR 0 < $this->image_bevel) OR $this->image_reflection_height));
        if ($image_manipulation)
        {
          if ($this->image_convert == '')
          {
            $this->file_dst_name = $this->file_dst_name_body . (!empty ($this->file_dst_name_ext) ? '.' . $this->file_dst_name_ext : '');
            $this->log .= '- image operation, keep extension<br />';
          }
          else
          {
            $this->file_dst_name = $this->file_dst_name_body . '.' . $this->image_convert;
            $this->log .= '- image operation, change extension for conversion type<br />';
          }
        }
        else
        {
          $this->file_dst_name = $this->file_dst_name_body . (!empty ($this->file_dst_name_ext) ? '.' . $this->file_dst_name_ext : '');
          $this->log .= '- no image operation, keep extension<br />';
        }

        if (!$return_mode)
        {
          if (!$this->file_auto_rename)
          {
            $this->log .= '- no auto_rename if same filename exists<br />';
            $this->file_dst_pathname = $this->file_dst_path . $this->file_dst_name;
          }
          else
          {
            $this->log .= '- checking for auto_rename<br />';
            $this->file_dst_pathname = $this->file_dst_path . $this->file_dst_name;
            $body = $this->file_dst_name_body;
            $cpt = 1;
            while (@file_exists ($this->file_dst_pathname))
            {
              $this->file_dst_name_body = $body . '_' . $cpt;
              $this->file_dst_name = $this->file_dst_name_body . (!empty ($this->file_dst_name_ext) ? '.' . $this->file_dst_name_ext : '');
              ++$cpt;
              $this->file_dst_pathname = $this->file_dst_path . $this->file_dst_name;
            }

            if (1 < $cpt)
            {
              $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;auto_rename to ' . $this->file_dst_name . '<br />';
            }
          }

          $this->log .= '- destination file details<br />';
          $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;file_dst_name         : ' . $this->file_dst_name . '<br />';
          $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;file_dst_pathname     : ' . $this->file_dst_pathname . '<br />';
          if ($this->file_overwrite)
          {
            $this->log .= '- no overwrite checking<br />';
          }
          else
          {
            if (@file_exists ($this->file_dst_pathname))
            {
              $this->processed = false;
              $this->error = $this->translate ('already_exists', array ($this->file_dst_name));
            }
            else
            {
              $this->log .= '- ' . $this->file_dst_name . ' doesn\'t exist already<br />';
            }
          }
        }
      }
      else
      {
        $this->processed = false;
      }

      if (!empty ($this->file_src_temp))
      {
        $this->log .= '- use the temp file instead of the original file since it is a second process<br />';
        $this->file_src_pathname = $this->file_src_temp;
        if (!file_exists ($this->file_src_pathname))
        {
          $this->processed = false;
          $this->error = $this->translate ('temp_file_missing');
        }
      }
      else
      {
        if (!$this->no_upload_check)
        {
          if (!is_uploaded_file ($this->file_src_pathname))
          {
            $this->processed = false;
            $this->error = $this->translate ('source_missing');
          }
        }
        else
        {
          if (!file_exists ($this->file_src_pathname))
          {
            $this->processed = false;
            $this->error = $this->translate ('source_missing');
          }
        }
      }

      if (!$return_mode)
      {
        if (($this->processed AND !file_exists ($this->file_dst_path)))
        {
          if ($this->dir_auto_create)
          {
            $this->log .= '- ' . $this->file_dst_path . ' doesn\'t exist. Attempting creation:';
            if (!$this->rmkdir ($this->file_dst_path, $this->dir_chmod))
            {
              $this->log .= ' failed<br />';
              $this->processed = false;
              $this->error = $this->translate ('destination_dir');
            }
            else
            {
              $this->log .= ' success<br />';
            }
          }
          else
          {
            $this->error = $this->translate ('destination_dir_missing');
          }
        }

        if (($this->processed AND !is_dir ($this->file_dst_path)))
        {
          $this->processed = false;
          $this->error = $this->translate ('destination_path_not_dir');
        }

        $hash = md5 ($this->file_dst_name_body . rand (1, 1000));
        if (($this->processed AND !$f = @fopen ($this->file_dst_path . $hash . '.' . $this->file_dst_name_ext, 'a+')))
        {
          if ($this->dir_auto_chmod)
          {
            $this->log .= '- ' . $this->file_dst_path . ' is not writeable. Attempting chmod:';
            if (!@chmod ($this->file_dst_path, $this->dir_chmod))
            {
              $this->log .= ' failed<br />';
              $this->processed = false;
              $this->error = $this->translate ('destination_dir_write');
            }
            else
            {
              $this->log .= ' success<br />';
              if (!$f = @fopen ($this->file_dst_path . $hash . '.' . $this->file_dst_name_ext, 'a+'))
              {
                $this->processed = false;
                $this->error = $this->translate ('destination_dir_write');
              }
              else
              {
                @fclose ($f);
              }
            }
          }
          else
          {
            $this->processed = false;
            $this->error = $this->translate ('destination_path_write');
          }
        }
        else
        {
          if ($this->processed)
          {
            @fclose ($f);
          }

          @unlink ($this->file_dst_path . $hash . '.' . $this->file_dst_name_ext);
        }

        if (((!$this->no_upload_check AND empty ($this->file_src_temp)) AND !file_exists ($this->file_src_pathname)))
        {
          $this->log .= '- attempting creating a temp file:';
          $hash = md5 ($this->file_dst_name_body . rand (1, 1000));
          if (move_uploaded_file ($this->file_src_pathname, $this->file_dst_path . $hash . '.' . $this->file_dst_name_ext))
          {
            $this->file_src_pathname = $this->file_dst_path . $hash . '.' . $this->file_dst_name_ext;
            $this->file_src_temp = $this->file_src_pathname;
            $this->log .= ' file created<br />';
            $this->log .= '    temp file is: ' . $this->file_src_temp . '<br />';
          }
          else
          {
            $this->log .= ' failed<br />';
            $this->processed = false;
            $this->error = $this->translate ('temp_file');
          }
        }
      }

      if ($this->processed)
      {
        if ($image_manipulation)
        {
          if (($this->processed AND !$f = @fopen ($this->file_src_pathname, 'r')))
          {
            $this->processed = false;
            $this->error = $this->translate ('source_not_readable');
          }
          else
          {
            @fclose ($f);
          }

          $this->log .= '- image resizing or conversion wanted<br />';
          if ($this->gdversion ())
          {
            switch ($this->image_src_type)
            {
              case 'jpg':
              {
                if (!function_exists ('imagecreatefromjpeg'))
                {
                  $this->processed = false;
                  $this->error = $this->translate ('no_create_support', array ('JPEG'));
                }
                else
                {
                  $image_src = @imagecreatefromjpeg ($this->file_src_pathname);
                  if (!$image_src)
                  {
                    $this->processed = false;
                    $this->error = $this->translate ('create_error', array ('JPEG'));
                  }
                  else
                  {
                    $this->log .= '- source image is JPEG<br />';
                  }
                }

                break;
              }

              case 'png':
              {
                if (!function_exists ('imagecreatefrompng'))
                {
                  $this->processed = false;
                  $this->error = $this->translate ('no_create_support', array ('PNG'));
                }
                else
                {
                  $image_src = @imagecreatefrompng ($this->file_src_pathname);
                  if (!$image_src)
                  {
                    $this->processed = false;
                    $this->error = $this->translate ('create_error', array ('PNG'));
                  }
                  else
                  {
                    $this->log .= '- source image is PNG<br />';
                  }
                }

                break;
              }

              case 'gif':
              {
                if (!function_exists ('imagecreatefromgif'))
                {
                  $this->processed = false;
                  $this->error = $this->translate ('no_create_support', array ('GIF'));
                }
                else
                {
                  $image_src = @imagecreatefromgif ($this->file_src_pathname);
                  if (!$image_src)
                  {
                    $this->processed = false;
                    $this->error = $this->translate ('create_error', array ('GIF'));
                  }
                  else
                  {
                    $this->log .= '- source image is GIF<br />';
                  }
                }

                break;
              }

              case 'bmp':
              {
                if (!method_exists ($this, 'imagecreatefrombmp'))
                {
                  $this->processed = false;
                  $this->error = $this->translate ('no_create_support', array ('BMP'));
                }
                else
                {
                  $image_src = @$this->imagecreatefrombmp ($this->file_src_pathname);
                  if (!$image_src)
                  {
                    $this->processed = false;
                    $this->error = $this->translate ('create_error', array ('BMP'));
                  }
                  else
                  {
                    $this->log .= '- source image is BMP<br />';
                  }
                }

                break;
              }

              default:
              {
                $this->processed = false;
                $this->error = $this->translate ('source_invalid');
              }
            }
          }
          else
          {
            $this->processed = false;
            $this->error = $this->translate ('gd_missing');
          }

          if (($this->processed AND $image_src))
          {
            if (empty ($this->image_convert))
            {
              $this->log .= '- setting destination file type to ' . $this->file_src_name_ext . '<br />';
              $this->image_convert = $this->file_src_name_ext;
            }

            if (!in_array ($this->image_convert, $this->image_supported))
            {
              $this->image_convert = 'jpg';
            }

            if (((($this->image_convert != 'png' AND $this->image_convert != 'gif') AND !empty ($this->image_default_color)) AND empty ($this->image_background_color)))
            {
              $this->image_background_color = $this->image_default_color;
            }

            if (!empty ($this->image_background_color))
            {
              $this->image_default_color = $this->image_background_color;
            }

            if (empty ($this->image_default_color))
            {
              $this->image_default_color = '#FFFFFF';
            }

            $this->image_src_x = imagesx ($image_src);
            $this->image_src_y = imagesy ($image_src);
            $this->image_dst_x = $this->image_src_x;
            $this->image_dst_y = $this->image_src_y;
            $gd_version = $this->gdversion ();
            $ratio_crop = null;
            if (!imageistruecolor ($image_src))
            {
              $this->log .= '- image is detected as having a palette<br />';
              $this->image_is_palette = true;
              $this->image_transparent_color = imagecolortransparent ($image_src);
              if ((0 <= $this->image_transparent_color AND $this->image_transparent_color < imagecolorstotal ($image_src)))
              {
                $this->image_is_transparent = true;
                $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;palette image is detected as transparent<br />';
              }

              $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;convert palette image to true color<br />';
              $transparent_color = imagecolortransparent ($image_src);
              if ((0 <= $transparent_color AND $transparent_color < imagecolorstotal ($image_src)))
              {
                $rgb = imagecolorsforindex ($image_src, $transparent_color);
                $transparent_color = $rgb['red'] << 16 | $rgb['green'] << 8 | $rgb['blue'];
                imagecolortransparent ($image_src, imagecolorallocate ($image_src, 0, 0, 0));
              }

              $true_color = imagecreatetruecolor ($this->image_src_x, $this->image_src_y);
              imagealphablending ($image_src, false);
              imagesavealpha ($image_src, true);
              imagecopy ($true_color, $image_src, 0, 0, 0, 0, $this->image_src_x, $this->image_src_y);
              $image_src = $this->imagetransfer ($true_color, $image_src);
              if (0 <= $transparent_color)
              {
                $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;preserve transparency<br />';
                imagealphablending ($image_src, false);
                imagesavealpha ($image_src, true);
                $x = 0;
                while ($x < $this->image_src_x)
                {
                  $y = 0;
                  while ($y < $this->image_src_y)
                  {
                    if (imagecolorat ($image_src, $x, $y) == $transparent_color)
                    {
                      imagesetpixel ($image_src, $x, $y, 127 << 24);
                    }

                    ++$y;
                  }

                  ++$x;
                }
              }

              $this->image_is_palette = false;
            }

            if ($this->image_resize)
            {
              $this->log .= '- resizing...<br />';
              if ($this->image_ratio_x)
              {
                $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;calculate x size<br />';
                $this->image_dst_x = round ($this->image_src_x * $this->image_y / $this->image_src_y);
                $this->image_dst_y = $this->image_y;
              }
              else
              {
                if ($this->image_ratio_y)
                {
                  $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;calculate y size<br />';
                  $this->image_dst_x = $this->image_x;
                  $this->image_dst_y = round ($this->image_src_y * $this->image_x / $this->image_src_x);
                }
                else
                {
                  if (is_numeric ($this->image_ratio_pixels))
                  {
                    $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;calculate x/y size to match a number of pixels<br />';
                    $pixels = $this->image_src_y * $this->image_src_x;
                    $diff = sqrt ($this->image_ratio_pixels / $pixels);
                    $this->image_dst_x = round ($this->image_src_x * $diff);
                    $this->image_dst_y = round ($this->image_src_y * $diff);
                  }
                  else
                  {
                    if ((((($this->image_ratio OR $this->image_ratio_crop) OR $this->image_ratio_fill) OR $this->image_ratio_no_zoom_in) OR $this->image_ratio_no_zoom_out))
                    {
                      $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;check x/y sizes<br />';
                      if ((((!$this->image_ratio_no_zoom_in AND !$this->image_ratio_no_zoom_out) OR ($this->image_ratio_no_zoom_in AND ($this->image_x < $this->image_src_x OR $this->image_y < $this->image_src_y))) OR (($this->image_ratio_no_zoom_out AND $this->image_src_x < $this->image_x) AND $this->image_src_y < $this->image_y)))
                      {
                        $this->image_dst_x = $this->image_x;
                        $this->image_dst_y = $this->image_y;
                        if ($this->image_ratio_crop)
                        {
                          if (!is_string ($this->image_ratio_crop))
                          {
                            $this->image_ratio_crop = '';
                          }

                          $this->image_ratio_crop = strtolower ($this->image_ratio_crop);
                          if ($this->image_src_y / $this->image_y < $this->image_src_x / $this->image_x)
                          {
                            $this->image_dst_y = $this->image_y;
                            $this->image_dst_x = intval ($this->image_src_x * ($this->image_y / $this->image_src_y));
                            $ratio_crop = array ();
                            $ratio_crop['x'] = $this->image_dst_x - $this->image_x;
                            if (strpos ($this->image_ratio_crop, 'l') !== false)
                            {
                              $ratio_crop['l'] = 0;
                              $ratio_crop['r'] = $ratio_crop['x'];
                            }
                            else
                            {
                              if (strpos ($this->image_ratio_crop, 'r') !== false)
                              {
                                $ratio_crop['l'] = $ratio_crop['x'];
                                $ratio_crop['r'] = 0;
                              }
                              else
                              {
                                $ratio_crop['l'] = round ($ratio_crop['x'] / 2);
                                $ratio_crop['r'] = $ratio_crop['x'] - $ratio_crop['l'];
                              }
                            }

                            $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;ratio_crop_x         : ' . $ratio_crop['x'] . ' (' . $ratio_crop['l'] . ';' . $ratio_crop['r'] . ')<br />';
                            if (is_null ($this->image_crop))
                            {
                              $this->image_crop = array (0, 0, 0, 0);
                            }
                          }
                          else
                          {
                            $this->image_dst_x = $this->image_x;
                            $this->image_dst_y = intval ($this->image_src_y * ($this->image_x / $this->image_src_x));
                            $ratio_crop = array ();
                            $ratio_crop['y'] = $this->image_dst_y - $this->image_y;
                            if (strpos ($this->image_ratio_crop, 't') !== false)
                            {
                              $ratio_crop['t'] = 0;
                              $ratio_crop['b'] = $ratio_crop['y'];
                            }
                            else
                            {
                              if (strpos ($this->image_ratio_crop, 'b') !== false)
                              {
                                $ratio_crop['t'] = $ratio_crop['y'];
                                $ratio_crop['b'] = 0;
                              }
                              else
                              {
                                $ratio_crop['t'] = round ($ratio_crop['y'] / 2);
                                $ratio_crop['b'] = $ratio_crop['y'] - $ratio_crop['t'];
                              }
                            }

                            $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;ratio_crop_y         : ' . $ratio_crop['y'] . ' (' . $ratio_crop['t'] . ';' . $ratio_crop['b'] . ')<br />';
                            if (is_null ($this->image_crop))
                            {
                              $this->image_crop = array (0, 0, 0, 0);
                            }
                          }
                        }
                        else
                        {
                          if ($this->image_ratio_fill)
                          {
                            if (!is_string ($this->image_ratio_fill))
                            {
                              $this->image_ratio_fill = '';
                            }

                            $this->image_ratio_fill = strtolower ($this->image_ratio_fill);
                            if ($this->image_src_x / $this->image_x < $this->image_src_y / $this->image_y)
                            {
                              $this->image_dst_y = $this->image_y;
                              $this->image_dst_x = intval ($this->image_src_x * ($this->image_y / $this->image_src_y));
                              $ratio_crop = array ();
                              $ratio_crop['x'] = $this->image_dst_x - $this->image_x;
                              if (strpos ($this->image_ratio_fill, 'l') !== false)
                              {
                                $ratio_crop['l'] = 0;
                                $ratio_crop['r'] = $ratio_crop['x'];
                              }
                              else
                              {
                                if (strpos ($this->image_ratio_fill, 'r') !== false)
                                {
                                  $ratio_crop['l'] = $ratio_crop['x'];
                                  $ratio_crop['r'] = 0;
                                }
                                else
                                {
                                  $ratio_crop['l'] = round ($ratio_crop['x'] / 2);
                                  $ratio_crop['r'] = $ratio_crop['x'] - $ratio_crop['l'];
                                }
                              }

                              $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;ratio_fill_x         : ' . $ratio_crop['x'] . ' (' . $ratio_crop['l'] . ';' . $ratio_crop['r'] . ')<br />';
                              if (is_null ($this->image_crop))
                              {
                                $this->image_crop = array (0, 0, 0, 0);
                              }
                            }
                            else
                            {
                              $this->image_dst_x = $this->image_x;
                              $this->image_dst_y = intval ($this->image_src_y * ($this->image_x / $this->image_src_x));
                              $ratio_crop = array ();
                              $ratio_crop['y'] = $this->image_dst_y - $this->image_y;
                              if (strpos ($this->image_ratio_fill, 't') !== false)
                              {
                                $ratio_crop['t'] = 0;
                                $ratio_crop['b'] = $ratio_crop['y'];
                              }
                              else
                              {
                                if (strpos ($this->image_ratio_fill, 'b') !== false)
                                {
                                  $ratio_crop['t'] = $ratio_crop['y'];
                                  $ratio_crop['b'] = 0;
                                }
                                else
                                {
                                  $ratio_crop['t'] = round ($ratio_crop['y'] / 2);
                                  $ratio_crop['b'] = $ratio_crop['y'] - $ratio_crop['t'];
                                }
                              }

                              $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;ratio_fill_y         : ' . $ratio_crop['y'] . ' (' . $ratio_crop['t'] . ';' . $ratio_crop['b'] . ')<br />';
                              if (is_null ($this->image_crop))
                              {
                                $this->image_crop = array (0, 0, 0, 0);
                              }
                            }
                          }
                          else
                          {
                            if ($this->image_src_y / $this->image_y < $this->image_src_x / $this->image_x)
                            {
                              $this->image_dst_x = $this->image_x;
                              $this->image_dst_y = intval ($this->image_src_y * ($this->image_x / $this->image_src_x));
                            }
                            else
                            {
                              $this->image_dst_y = $this->image_y;
                              $this->image_dst_x = intval ($this->image_src_x * ($this->image_y / $this->image_src_y));
                            }
                          }
                        }
                      }
                      else
                      {
                        $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;doesn\'t calculate x/y sizes<br />';
                        $this->image_dst_x = $this->image_src_x;
                        $this->image_dst_y = $this->image_src_y;
                      }
                    }
                    else
                    {
                      $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;use plain sizes<br />';
                      $this->image_dst_x = $this->image_x;
                      $this->image_dst_y = $this->image_y;
                    }
                  }
                }
              }

              $image_dst = $this->imagecreatenew ($this->image_dst_x, $this->image_dst_y);
              if (2 <= $gd_version)
              {
                $res = imagecopyresampled ($image_dst, $image_src, 0, 0, 0, 0, $this->image_dst_x, $this->image_dst_y, $this->image_src_x, $this->image_src_y);
              }
              else
              {
                $res = imagecopyresized ($image_dst, $image_src, 0, 0, 0, 0, $this->image_dst_x, $this->image_dst_y, $this->image_src_x, $this->image_src_y);
              }

              $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;resized image object created<br />';
              $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;image_src_x y        : ' . $this->image_src_x . ' x ' . $this->image_src_y . '<br />';
              $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;image_dst_x y        : ' . $this->image_dst_x . ' x ' . $this->image_dst_y . '<br />';
            }
            else
            {
              $image_dst = &$image_src;
            }

            if ((!empty ($this->image_crop) OR !is_null ($ratio_crop)))
            {
              if (is_array ($this->image_crop))
              {
                $vars = $this->image_crop;
              }
              else
              {
                $vars = explode (' ', $this->image_crop);
              }

              if (sizeof ($vars) == 4)
              {
                $ct = $vars[0];
                $cr = $vars[1];
                $cb = $vars[2];
                $cl = $vars[3];
              }
              else
              {
                if (sizeof ($vars) == 2)
                {
                  $ct = $vars[0];
                  $cr = $vars[1];
                  $cb = $vars[0];
                  $cl = $vars[1];
                }
                else
                {
                  $ct = $vars[0];
                  $cr = $vars[0];
                  $cb = $vars[0];
                  $cl = $vars[0];
                }
              }

              if (0 < strpos ($ct, '%'))
              {
                $ct = $this->image_dst_y * (str_replace ('%', '', $ct) / 100);
              }

              if (0 < strpos ($cr, '%'))
              {
                $cr = $this->image_dst_x * (str_replace ('%', '', $cr) / 100);
              }

              if (0 < strpos ($cb, '%'))
              {
                $cb = $this->image_dst_y * (str_replace ('%', '', $cb) / 100);
              }

              if (0 < strpos ($cl, '%'))
              {
                $cl = $this->image_dst_x * (str_replace ('%', '', $cl) / 100);
              }

              if (0 < strpos ($ct, 'px'))
              {
                $ct = str_replace ('px', '', $ct);
              }

              if (0 < strpos ($cr, 'px'))
              {
                $cr = str_replace ('px', '', $cr);
              }

              if (0 < strpos ($cb, 'px'))
              {
                $cb = str_replace ('px', '', $cb);
              }

              if (0 < strpos ($cl, 'px'))
              {
                $cl = str_replace ('px', '', $cl);
              }

              $ct = (int)$ct;
              $cr = (int)$cr;
              $cb = (int)$cb;
              $cl = (int)$cl;
              if (!is_null ($ratio_crop))
              {
                if (array_key_exists ('t', $ratio_crop))
                {
                  $ct += $ratio_crop['t'];
                }

                if (array_key_exists ('r', $ratio_crop))
                {
                  $cr += $ratio_crop['r'];
                }

                if (array_key_exists ('b', $ratio_crop))
                {
                  $cb += $ratio_crop['b'];
                }

                if (array_key_exists ('l', $ratio_crop))
                {
                  $cl += $ratio_crop['l'];
                }
              }

              $this->log .= '- crop image : ' . $ct . ' ' . $cr . ' ' . $cb . ' ' . $cl . ' <br />';
              $this->image_dst_x = $this->image_dst_x - $cl - $cr;
              $this->image_dst_y = $this->image_dst_y - $ct - $cb;
              if ($this->image_dst_x < 1)
              {
                $this->image_dst_x = 1;
              }

              if ($this->image_dst_y < 1)
              {
                $this->image_dst_y = 1;
              }

              $tmp = $this->imagecreatenew ($this->image_dst_x, $this->image_dst_y);
              imagecopy ($tmp, $image_dst, 0, 0, $cl, $ct, $this->image_dst_x, $this->image_dst_y);
              if (((($ct < 0 OR $cr < 0) OR $cb < 0) OR $cl < 0))
              {
                if (!empty ($this->image_background_color))
                {
                  sscanf ($this->image_background_color, '#%2x%2x%2x', $red, $green, $blue);
                  $fill = imagecolorallocate ($tmp, $red, $green, $blue);
                }
                else
                {
                  $fill = imagecolorallocatealpha ($tmp, 0, 0, 0, 127);
                }

                if ($ct < 0)
                {
                  imagefilledrectangle ($tmp, 0, 0, $this->image_dst_x, 0 - $ct, $fill);
                }

                if ($cr < 0)
                {
                  imagefilledrectangle ($tmp, $this->image_dst_x + $cr, 0, $this->image_dst_x, $this->image_dst_y, $fill);
                }

                if ($cb < 0)
                {
                  imagefilledrectangle ($tmp, 0, $this->image_dst_y + $cb, $this->image_dst_x, $this->image_dst_y, $fill);
                }

                if ($cl < 0)
                {
                  imagefilledrectangle ($tmp, 0, 0, 0 - $cl, $this->image_dst_y, $fill);
                }
              }

              $image_dst = $this->imagetransfer ($tmp, $image_dst);
            }

            if ((2 <= $gd_version AND !empty ($this->image_flip)))
            {
              $this->image_flip = strtolower ($this->image_flip);
              $this->log .= '- flip image : ' . $this->image_flip . '<br />';
              $tmp = $this->imagecreatenew ($this->image_dst_x, $this->image_dst_y);
              $x = 0;
              while ($x < $this->image_dst_x)
              {
                $y = 0;
                while ($y < $this->image_dst_y)
                {
                  if (strpos ($this->image_flip, 'v') !== false)
                  {
                    imagecopy ($tmp, $image_dst, $this->image_dst_x - $x - 1, $y, $x, $y, 1, 1);
                  }
                  else
                  {
                    imagecopy ($tmp, $image_dst, $x, $this->image_dst_y - $y - 1, $x, $y, 1, 1);
                  }

                  ++$y;
                }

                ++$x;
              }

              $image_dst = $this->imagetransfer ($tmp, $image_dst);
            }

            if ((2 <= $gd_version AND is_numeric ($this->image_rotate)))
            {
              if (!in_array ($this->image_rotate, array (0, 90, 180, 270)))
              {
                $this->image_rotate = 0;
              }

              if ($this->image_rotate != 0)
              {
                if (($this->image_rotate == 90 OR $this->image_rotate == 270))
                {
                  $tmp = $this->imagecreatenew ($this->image_dst_y, $this->image_dst_x);
                }
                else
                {
                  $tmp = $this->imagecreatenew ($this->image_dst_x, $this->image_dst_y);
                }

                $this->log .= '- rotate image : ' . $this->image_rotate . '<br />';
                $x = 0;
                while ($x < $this->image_dst_x)
                {
                  $y = 0;
                  while ($y < $this->image_dst_y)
                  {
                    if ($this->image_rotate == 90)
                    {
                      imagecopy ($tmp, $image_dst, $y, $x, $x, $this->image_dst_y - $y - 1, 1, 1);
                    }
                    else
                    {
                      if ($this->image_rotate == 180)
                      {
                        imagecopy ($tmp, $image_dst, $x, $y, $this->image_dst_x - $x - 1, $this->image_dst_y - $y - 1, 1, 1);
                      }
                      else
                      {
                        if ($this->image_rotate == 270)
                        {
                          imagecopy ($tmp, $image_dst, $y, $x, $this->image_dst_x - $x - 1, $y, 1, 1);
                        }
                        else
                        {
                          imagecopy ($tmp, $image_dst, $x, $y, $x, $y, 1, 1);
                        }
                      }
                    }

                    ++$y;
                  }

                  ++$x;
                }

                if (($this->image_rotate == 90 OR $this->image_rotate == 270))
                {
                  $t = $this->image_dst_y;
                  $this->image_dst_y = $this->image_dst_x;
                  $this->image_dst_x = $t;
                }

                $image_dst = $this->imagetransfer ($tmp, $image_dst);
              }
            }

            if ((2 <= $gd_version AND ((is_numeric ($this->image_overlay_percent) AND 0 < $this->image_overlay_percent) AND !empty ($this->image_overlay_color))))
            {
              $this->log .= '- apply color overlay<br />';
              sscanf ($this->image_overlay_color, '#%2x%2x%2x', $red, $green, $blue);
              $filter = imagecreatetruecolor ($this->image_dst_x, $this->image_dst_y);
              $color = imagecolorallocate ($filter, $red, $green, $blue);
              imagefilledrectangle ($filter, 0, 0, $this->image_dst_x, $this->image_dst_y, $color);
              $this->imagecopymergealpha ($image_dst, $filter, 0, 0, 0, 0, $this->image_dst_x, $this->image_dst_y, $this->image_overlay_percent);
              imagedestroy ($filter);
            }

            if ((2 <= $gd_version AND ((((($this->image_negative OR $this->image_greyscale) OR is_numeric ($this->image_threshold)) OR is_numeric ($this->image_brightness)) OR is_numeric ($this->image_contrast)) OR !empty ($this->image_tint_color))))
            {
              $this->log .= '- apply tint, light, contrast correction, negative, greyscale and threshold<br />';
              if (!empty ($this->image_tint_color))
              {
                sscanf ($this->image_tint_color, '#%2x%2x%2x', $tint_red, $tint_green, $tint_blue);
              }

              imagealphablending ($image_dst, true);
              $y = 0;
              while ($y < $this->image_dst_y)
              {
                $x = 0;
                while ($x < $this->image_dst_x)
                {
                  if ($this->image_greyscale)
                  {
                    $pixel = imagecolorsforindex ($image_dst, imagecolorat ($image_dst, $x, $y));
                    $r = $g = $b = round (0.212499999999999994448885 * $pixel['red'] + 0.715400000000000035882408 * $pixel['green'] + 0.0720999999999999974242826 * $pixel['blue']);
                    $color = imagecolorallocatealpha ($image_dst, $r, $g, $b, $pixel['alpha']);
                    imagesetpixel ($image_dst, $x, $y, $color);
                  }

                  if (is_numeric ($this->image_threshold))
                  {
                    $pixel = imagecolorsforindex ($image_dst, imagecolorat ($image_dst, $x, $y));
                    $c = round ($pixel['red'] + $pixel['green'] + $pixel['blue']) / 3 - 127;
                    $r = $g = $b = ($this->image_threshold < $c ? 255 : 0);
                    $color = imagecolorallocatealpha ($image_dst, $r, $g, $b, $pixel['alpha']);
                    imagesetpixel ($image_dst, $x, $y, $color);
                  }

                  if (is_numeric ($this->image_brightness))
                  {
                    $pixel = imagecolorsforindex ($image_dst, imagecolorat ($image_dst, $x, $y));
                    $r = max (min (round ($pixel['red'] + $this->image_brightness * 2), 255), 0);
                    $g = max (min (round ($pixel['green'] + $this->image_brightness * 2), 255), 0);
                    $b = max (min (round ($pixel['blue'] + $this->image_brightness * 2), 255), 0);
                    $color = imagecolorallocatealpha ($image_dst, $r, $g, $b, $pixel['alpha']);
                    imagesetpixel ($image_dst, $x, $y, $color);
                  }

                  if (is_numeric ($this->image_contrast))
                  {
                    $pixel = imagecolorsforindex ($image_dst, imagecolorat ($image_dst, $x, $y));
                    $r = max (min (round (($this->image_contrast + 128) * $pixel['red'] / 128), 255), 0);
                    $g = max (min (round (($this->image_contrast + 128) * $pixel['green'] / 128), 255), 0);
                    $b = max (min (round (($this->image_contrast + 128) * $pixel['blue'] / 128), 255), 0);
                    $color = imagecolorallocatealpha ($image_dst, $r, $g, $b, $pixel['alpha']);
                    imagesetpixel ($image_dst, $x, $y, $color);
                  }

                  if (!empty ($this->image_tint_color))
                  {
                    $pixel = imagecolorsforindex ($image_dst, imagecolorat ($image_dst, $x, $y));
                    $r = min (round ($tint_red * $pixel['red'] / 169), 255);
                    $g = min (round ($tint_green * $pixel['green'] / 169), 255);
                    $b = min (round ($tint_blue * $pixel['blue'] / 169), 255);
                    $color = imagecolorallocatealpha ($image_dst, $r, $g, $b, $pixel['alpha']);
                    imagesetpixel ($image_dst, $x, $y, $color);
                  }

                  if (!empty ($this->image_negative))
                  {
                    $pixel = imagecolorsforindex ($image_dst, imagecolorat ($image_dst, $x, $y));
                    $r = round (255 - $pixel['red']);
                    $g = round (255 - $pixel['green']);
                    $b = round (255 - $pixel['blue']);
                    $color = imagecolorallocatealpha ($image_dst, $r, $g, $b, $pixel['alpha']);
                    imagesetpixel ($image_dst, $x, $y, $color);
                  }

                  ++$x;
                }

                ++$y;
              }
            }

            if ((2 <= $gd_version AND !empty ($this->image_border)))
            {
              if (is_array ($this->image_border))
              {
                $vars = $this->image_border;
                $this->log .= '- add border : ' . implode (' ', $this->image_border) . '<br />';
              }
              else
              {
                $this->log .= '- add border : ' . $this->image_border . '<br />';
                $vars = explode (' ', $this->image_border);
              }

              if (sizeof ($vars) == 4)
              {
                $ct = $vars[0];
                $cr = $vars[1];
                $cb = $vars[2];
                $cl = $vars[3];
              }
              else
              {
                if (sizeof ($vars) == 2)
                {
                  $ct = $vars[0];
                  $cr = $vars[1];
                  $cb = $vars[0];
                  $cl = $vars[1];
                }
                else
                {
                  $ct = $vars[0];
                  $cr = $vars[0];
                  $cb = $vars[0];
                  $cl = $vars[0];
                }
              }

              if (0 < strpos ($ct, '%'))
              {
                $ct = $this->image_dst_y * (str_replace ('%', '', $ct) / 100);
              }

              if (0 < strpos ($cr, '%'))
              {
                $cr = $this->image_dst_x * (str_replace ('%', '', $cr) / 100);
              }

              if (0 < strpos ($cb, '%'))
              {
                $cb = $this->image_dst_y * (str_replace ('%', '', $cb) / 100);
              }

              if (0 < strpos ($cl, '%'))
              {
                $cl = $this->image_dst_x * (str_replace ('%', '', $cl) / 100);
              }

              if (0 < strpos ($ct, 'px'))
              {
                $ct = str_replace ('px', '', $ct);
              }

              if (0 < strpos ($cr, 'px'))
              {
                $cr = str_replace ('px', '', $cr);
              }

              if (0 < strpos ($cb, 'px'))
              {
                $cb = str_replace ('px', '', $cb);
              }

              if (0 < strpos ($cl, 'px'))
              {
                $cl = str_replace ('px', '', $cl);
              }

              $ct = (int)$ct;
              $cr = (int)$cr;
              $cb = (int)$cb;
              $cl = (int)$cl;
              $this->image_dst_x = $this->image_dst_x + $cl + $cr;
              $this->image_dst_y = $this->image_dst_y + $ct + $cb;
              if (!empty ($this->image_border_color))
              {
                sscanf ($this->image_border_color, '#%2x%2x%2x', $red, $green, $blue);
              }

              $tmp = $this->imagecreatenew ($this->image_dst_x, $this->image_dst_y);
              $background = imagecolorallocatealpha ($tmp, $red, $green, $blue, 0);
              imagefilledrectangle ($tmp, 0, 0, $this->image_dst_x, $this->image_dst_y, $background);
              imagecopy ($tmp, $image_dst, $cl, $ct, 0, 0, $this->image_dst_x - $cr - $cl, $this->image_dst_y - $cb - $ct);
              $image_dst = $this->imagetransfer ($tmp, $image_dst);
            }

            if (is_numeric ($this->image_frame))
            {
              if (is_array ($this->image_frame_colors))
              {
                $vars = $this->image_frame_colors;
                $this->log .= '- add frame : ' . implode (' ', $this->image_frame_colors) . '<br />';
              }
              else
              {
                $this->log .= '- add frame : ' . $this->image_frame_colors . '<br />';
                $vars = explode (' ', $this->image_frame_colors);
              }

              $nb = sizeof ($vars);
              $this->image_dst_x = $this->image_dst_x + $nb * 2;
              $this->image_dst_y = $this->image_dst_y + $nb * 2;
              $tmp = $this->imagecreatenew ($this->image_dst_x, $this->image_dst_y);
              imagecopy ($tmp, $image_dst, $nb, $nb, 0, 0, $this->image_dst_x - $nb * 2, $this->image_dst_y - $nb * 2);
              $i = 0;
              while ($i < $nb)
              {
                sscanf ($vars[$i], '#%2x%2x%2x', $red, $green, $blue);
                $c = imagecolorallocate ($tmp, $red, $green, $blue);
                if ($this->image_frame == 1)
                {
                  imageline ($tmp, $i, $i, $this->image_dst_x - $i - 1, $i, $c);
                  imageline ($tmp, $this->image_dst_x - $i - 1, $this->image_dst_y - $i - 1, $this->image_dst_x - $i - 1, $i, $c);
                  imageline ($tmp, $this->image_dst_x - $i - 1, $this->image_dst_y - $i - 1, $i, $this->image_dst_y - $i - 1, $c);
                  imageline ($tmp, $i, $i, $i, $this->image_dst_y - $i - 1, $c);
                }
                else
                {
                  imageline ($tmp, $i, $i, $this->image_dst_x - $i - 1, $i, $c);
                  imageline ($tmp, $this->image_dst_x - $nb + $i, $this->image_dst_y - $nb + $i, $this->image_dst_x - $nb + $i, $nb - $i, $c);
                  imageline ($tmp, $this->image_dst_x - $nb + $i, $this->image_dst_y - $nb + $i, $nb - $i, $this->image_dst_y - $nb + $i, $c);
                  imageline ($tmp, $i, $i, $i, $this->image_dst_y - $i - 1, $c);
                }

                ++$i;
              }

              $image_dst = $this->imagetransfer ($tmp, $image_dst);
            }

            if (0 < $this->image_bevel)
            {
              if (empty ($this->image_bevel_color1))
              {
                $this->image_bevel_color1 = '#FFFFFF';
              }

              if (empty ($this->image_bevel_color2))
              {
                $this->image_bevel_color2 = '#000000';
              }

              sscanf ($this->image_bevel_color1, '#%2x%2x%2x', $red1, $green1, $blue1);
              sscanf ($this->image_bevel_color2, '#%2x%2x%2x', $red2, $green2, $blue2);
              $tmp = $this->imagecreatenew ($this->image_dst_x, $this->image_dst_y);
              imagecopy ($tmp, $image_dst, 0, 0, 0, 0, $this->image_dst_x, $this->image_dst_y);
              imagealphablending ($tmp, true);
              $i = 0;
              while ($i < $this->image_bevel)
              {
                $alpha = round ($i / $this->image_bevel * 127);
                $c1 = imagecolorallocatealpha ($tmp, $red1, $green1, $blue1, $alpha);
                $c2 = imagecolorallocatealpha ($tmp, $red2, $green2, $blue2, $alpha);
                imageline ($tmp, $i, $i, $this->image_dst_x - $i - 1, $i, $c1);
                imageline ($tmp, $this->image_dst_x - $i - 1, $this->image_dst_y - $i, $this->image_dst_x - $i - 1, $i, $c2);
                imageline ($tmp, $this->image_dst_x - $i - 1, $this->image_dst_y - $i - 1, $i, $this->image_dst_y - $i - 1, $c2);
                imageline ($tmp, $i, $i, $i, $this->image_dst_y - $i - 1, $c1);
                ++$i;
              }

              $image_dst = $this->imagetransfer ($tmp, $image_dst);
            }

            if (($this->image_watermark != '' AND file_exists ($this->image_watermark)))
            {
              $this->log .= '- add watermark<br />';
              $this->image_watermark_position = strtolower ($this->image_watermark_position);
              $watermark_info = getimagesize ($this->image_watermark);
              $watermark_type = (array_key_exists (2, $watermark_info) ? $watermark_info[2] : null);
              $watermark_checked = false;
              if ($watermark_type == IMAGETYPE_GIF)
              {
                if (!function_exists ('imagecreatefromgif'))
                {
                  $this->error = $this->translate ('watermark_no_create_support', array ('GIF'));
                }
                else
                {
                  $filter = @imagecreatefromgif ($this->image_watermark);
                  if (!$filter)
                  {
                    $this->error = $this->translate ('watermark_create_error', array ('GIF'));
                  }
                  else
                  {
                    $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;watermark source image is GIF<br />';
                    $watermark_checked = true;
                  }
                }
              }
              else
              {
                if ($watermark_type == IMAGETYPE_JPEG)
                {
                  if (!function_exists ('imagecreatefromjpeg'))
                  {
                    $this->error = $this->translate ('watermark_no_create_support', array ('JPEG'));
                  }
                  else
                  {
                    $filter = @imagecreatefromjpeg ($this->image_watermark);
                    if (!$filter)
                    {
                      $this->error = $this->translate ('watermark_create_error', array ('JPEG'));
                    }
                    else
                    {
                      $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;watermark source image is JPEG<br />';
                      $watermark_checked = true;
                    }
                  }
                }
                else
                {
                  if ($watermark_type == IMAGETYPE_PNG)
                  {
                    if (!function_exists ('imagecreatefrompng'))
                    {
                      $this->error = $this->translate ('watermark_no_create_support', array ('PNG'));
                    }
                    else
                    {
                      $filter = @imagecreatefrompng ($this->image_watermark);
                      if (!$filter)
                      {
                        $this->error = $this->translate ('watermark_create_error', array ('PNG'));
                      }
                      else
                      {
                        $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;watermark source image is PNG<br />';
                        $watermark_checked = true;
                      }
                    }
                  }
                  else
                  {
                    if ($watermark_type == IMAGETYPE_BMP)
                    {
                      if (!method_exists ($this, 'imagecreatefrombmp'))
                      {
                        $this->error = $this->translate ('watermark_no_create_support', array ('BMP'));
                      }
                      else
                      {
                        $filter = @$this->imagecreatefrombmp ($this->image_watermark);
                        if (!$filter)
                        {
                          $this->error = $this->translate ('watermark_create_error', array ('BMP'));
                        }
                        else
                        {
                          $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;watermark source image is BMP<br />';
                          $watermark_checked = true;
                        }
                      }
                    }
                    else
                    {
                      $this->error = $this->translate ('watermark_invalid');
                    }
                  }
                }
              }

              if ($watermark_checked)
              {
                $watermark_width = imagesx ($filter);
                $watermark_height = imagesy ($filter);
                $watermark_x = 0;
                $watermark_y = 0;
                if (is_numeric ($this->image_watermark_x))
                {
                  if ($this->image_watermark_x < 0)
                  {
                    $watermark_x = $this->image_dst_x - $watermark_width + $this->image_watermark_x;
                  }
                  else
                  {
                    $watermark_x = $this->image_watermark_x;
                  }
                }
                else
                {
                  if (strpos ($this->image_watermark_position, 'r') !== false)
                  {
                    $watermark_x = $this->image_dst_x - $watermark_width;
                  }
                  else
                  {
                    if (strpos ($this->image_watermark_position, 'l') !== false)
                    {
                      $watermark_x = 0;
                    }
                    else
                    {
                      $watermark_x = ($this->image_dst_x - $watermark_width) / 2;
                    }
                  }
                }

                if (is_numeric ($this->image_watermark_y))
                {
                  if ($this->image_watermark_y < 0)
                  {
                    $watermark_y = $this->image_dst_y - $watermark_height + $this->image_watermark_y;
                  }
                  else
                  {
                    $watermark_y = $this->image_watermark_y;
                  }
                }
                else
                {
                  if (strpos ($this->image_watermark_position, 'b') !== false)
                  {
                    $watermark_y = $this->image_dst_y - $watermark_height;
                  }
                  else
                  {
                    if (strpos ($this->image_watermark_position, 't') !== false)
                    {
                      $watermark_y = 0;
                    }
                    else
                    {
                      $watermark_y = ($this->image_dst_y - $watermark_height) / 2;
                    }
                  }
                }

                imagecopyresampled ($image_dst, $filter, $watermark_x, $watermark_y, 0, 0, $watermark_width, $watermark_height, $watermark_width, $watermark_height);
              }
              else
              {
                $this->error = $this->translate ('watermark_invalid');
              }
            }

            if (!empty ($this->image_text))
            {
              $this->log .= '- add text<br />';
              $src_size = $this->file_src_size / 1024;
              $src_size_mb = number_format ($src_size / 1024, 1, '.', ' ');
              $src_size_kb = number_format ($src_size, 1, '.', ' ');
              $src_size_human = (1024 < $src_size ? $src_size_mb . ' MB' : $src_size_kb . ' kb');
              $this->image_text = str_replace (array ('[src_name]', '[src_name_body]', '[src_name_ext]', '[src_pathname]', '[src_mime]', '[src_size]', '[src_size_kb]', '[src_size_mb]', '[src_size_human]', '[src_x]', '[src_y]', '[src_pixels]', '[src_type]', '[src_bits]', '[dst_path]', '[dst_name_body]', '[dst_name_ext]', '[dst_name]', '[dst_pathname]', '[dst_x]', '[dst_y]', '[date]', '[time]', '[host]', '[server]', '[ip]', '[gd_version]'), array ($this->file_src_name, $this->file_src_name_body, $this->file_src_name_ext, $this->file_src_pathname, $this->file_src_mime, $this->file_src_size, $src_size_kb, $src_size_mb, $src_size_human, $this->image_src_x, $this->image_src_y, $this->image_src_pixels, $this->image_src_type, $this->image_src_bits, $this->file_dst_path, $this->file_dst_name_body, $this->file_dst_name_ext, $this->file_dst_name, $this->file_dst_pathname, $this->image_dst_x, $this->image_dst_y, date ('Y-m-d'), date ('H:i:s'), $_SERVER['HTTP_HOST'], $_SERVER['SERVER_NAME'], $_SERVER['REMOTE_ADDR'], $this->gdversion (true)), $this->image_text);
              if (!is_numeric ($this->image_text_padding))
              {
                $this->image_text_padding = 0;
              }

              if (!is_numeric ($this->image_text_line_spacing))
              {
                $this->image_text_line_spacing = 0;
              }

              if (!is_numeric ($this->image_text_padding_x))
              {
                $this->image_text_padding_x = $this->image_text_padding;
              }

              if (!is_numeric ($this->image_text_padding_y))
              {
                $this->image_text_padding_y = $this->image_text_padding;
              }

              $this->image_text_position = strtolower ($this->image_text_position);
              $this->image_text_direction = strtolower ($this->image_text_direction);
              $this->image_text_alignment = strtolower ($this->image_text_alignment);
              if (((!is_numeric ($this->image_text_font) AND 4 < strlen ($this->image_text_font)) AND substr (strtolower ($this->image_text_font), 0 - 4) == '.gdf'))
              {
                $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;try to load font ' . $this->image_text_font . '... ';
                if ($this->image_text_font = @imageloadfont ($this->image_text_font))
                {
                  $this->log .= 'success<br />';
                }
                else
                {
                  $this->log .= 'error<br />';
                  $this->image_text_font = 5;
                }
              }

              $text = explode ('
', $this->image_text);
              $char_width = imagefontwidth ($this->image_text_font);
              $char_height = imagefontheight ($this->image_text_font);
              $text_height = 0;
              $text_width = 0;
              $line_height = 0;
              $line_width = 0;
              foreach ($text as $k => $v)
              {
                if ($this->image_text_direction == 'v')
                {
                  $h = $char_width * strlen ($v);
                  if ($text_height < $h)
                  {
                    $text_height = $h;
                  }

                  $line_width = $char_height;
                  $text_width += $line_width + ($k < sizeof ($text) - 1 ? $this->image_text_line_spacing : 0);
                  continue;
                }
                else
                {
                  $w = $char_width * strlen ($v);
                  if ($text_width < $w)
                  {
                    $text_width = $w;
                  }

                  $line_height = $char_height;
                  $text_height += $line_height + ($k < sizeof ($text) - 1 ? $this->image_text_line_spacing : 0);
                  continue;
                }
              }

              $text_width += 2 * $this->image_text_padding_x;
              $text_height += 2 * $this->image_text_padding_y;
              $text_x = 0;
              $text_y = 0;
              if (is_numeric ($this->image_text_x))
              {
                if ($this->image_text_x < 0)
                {
                  $text_x = $this->image_dst_x - $text_width + $this->image_text_x;
                }
                else
                {
                  $text_x = $this->image_text_x;
                }
              }
              else
              {
                if (strpos ($this->image_text_position, 'r') !== false)
                {
                  $text_x = $this->image_dst_x - $text_width;
                }
                else
                {
                  if (strpos ($this->image_text_position, 'l') !== false)
                  {
                    $text_x = 0;
                  }
                  else
                  {
                    $text_x = ($this->image_dst_x - $text_width) / 2;
                  }
                }
              }

              if (is_numeric ($this->image_text_y))
              {
                if ($this->image_text_y < 0)
                {
                  $text_y = $this->image_dst_y - $text_height + $this->image_text_y;
                }
                else
                {
                  $text_y = $this->image_text_y;
                }
              }
              else
              {
                if (strpos ($this->image_text_position, 'b') !== false)
                {
                  $text_y = $this->image_dst_y - $text_height;
                }
                else
                {
                  if (strpos ($this->image_text_position, 't') !== false)
                  {
                    $text_y = 0;
                  }
                  else
                  {
                    $text_y = ($this->image_dst_y - $text_height) / 2;
                  }
                }
              }

              if (!empty ($this->image_text_background))
              {
                sscanf ($this->image_text_background, '#%2x%2x%2x', $red, $green, $blue);
                if ((((2 <= $gd_version AND is_numeric ($this->image_text_background_percent)) AND 0 <= $this->image_text_background_percent) AND $this->image_text_background_percent <= 100))
                {
                  $filter = imagecreatetruecolor ($text_width, $text_height);
                  $background_color = imagecolorallocate ($filter, $red, $green, $blue);
                  imagefilledrectangle ($filter, 0, 0, $text_width, $text_height, $background_color);
                  $this->imagecopymergealpha ($image_dst, $filter, $text_x, $text_y, 0, 0, $text_width, $text_height, $this->image_text_background_percent);
                  imagedestroy ($filter);
                }
                else
                {
                  $background_color = imagecolorallocate ($image_dst, $red, $green, $blue);
                  imagefilledrectangle ($image_dst, $text_x, $text_y, $text_x + $text_width, $text_y + $text_height, $background_color);
                }
              }

              $text_x += $this->image_text_padding_x;
              $text_y += $this->image_text_padding_y;
              $t_width = $text_width - 2 * $this->image_text_padding_x;
              $t_height = $text_height - 2 * $this->image_text_padding_y;
              sscanf ($this->image_text_color, '#%2x%2x%2x', $red, $green, $blue);
              if ((((2 <= $gd_version AND is_numeric ($this->image_text_percent)) AND 0 <= $this->image_text_percent) AND $this->image_text_percent <= 100))
              {
                if ($t_width < 0)
                {
                  $t_width = 0;
                }

                if ($t_height < 0)
                {
                  $t_height = 0;
                }

                $filter = $this->imagecreatenew ($t_width, $t_height, false, true);
                $text_color = imagecolorallocate ($filter, $red, $green, $blue);
                foreach ($text as $k => $v)
                {
                  if ($this->image_text_direction == 'v')
                  {
                    imagestringup ($filter, $this->image_text_font, $k * ($line_width + ((0 < $k AND $k < sizeof ($text)) ? $this->image_text_line_spacing : 0)), $text_height - 2 * $this->image_text_padding_y - ($this->image_text_alignment == 'l' ? 0 : ($t_height - strlen ($v) * $char_width) / ($this->image_text_alignment == 'r' ? 1 : 2)), $v, $text_color);
                    continue;
                  }
                  else
                  {
                    imagestring ($filter, $this->image_text_font, ($this->image_text_alignment == 'l' ? 0 : ($t_width - strlen ($v) * $char_width) / ($this->image_text_alignment == 'r' ? 1 : 2)), $k * ($line_height + ((0 < $k AND $k < sizeof ($text)) ? $this->image_text_line_spacing : 0)), $v, $text_color);
                    continue;
                  }
                }

                $this->imagecopymergealpha ($image_dst, $filter, $text_x, $text_y, 0, 0, $t_width, $t_height, $this->image_text_percent);
                imagedestroy ($filter);
              }
              else
              {
                $text_color = imagecolorallocate ($image_dst, $red, $green, $blue);
                foreach ($text as $k => $v)
                {
                  if ($this->image_text_direction == 'v')
                  {
                    imagestringup ($image_dst, $this->image_text_font, $text_x + $k * ($line_width + ((0 < $k AND $k < sizeof ($text)) ? $this->image_text_line_spacing : 0)), $text_y + $text_height - 2 * $this->image_text_padding_y - ($this->image_text_alignment == 'l' ? 0 : ($t_height - strlen ($v) * $char_width) / ($this->image_text_alignment == 'r' ? 1 : 2)), $v, $text_color);
                    continue;
                  }
                  else
                  {
                    imagestring ($image_dst, $this->image_text_font, $text_x + ($this->image_text_alignment == 'l' ? 0 : ($t_width - strlen ($v) * $char_width) / ($this->image_text_alignment == 'r' ? 1 : 2)), $text_y + $k * ($line_height + ((0 < $k AND $k < sizeof ($text)) ? $this->image_text_line_spacing : 0)), $v, $text_color);
                    continue;
                  }
                }
              }
            }

            if ($this->image_reflection_height)
            {
              $this->log .= '- add reflection : ' . $this->image_reflection_height . '<br />';
              $image_reflection_height = $this->image_reflection_height;
              if (0 < strpos ($image_reflection_height, '%'))
              {
                $image_reflection_height = $this->image_dst_y * str_replace ('%', '', $image_reflection_height / 100);
              }

              if (0 < strpos ($image_reflection_height, 'px'))
              {
                $image_reflection_height = str_replace ('px', '', $image_reflection_height);
              }

              $image_reflection_height = (int)$image_reflection_height;
              if ($this->image_dst_y < $image_reflection_height)
              {
                $image_reflection_height = $this->image_dst_y;
              }

              if (empty ($this->image_reflection_opacity))
              {
                $this->image_reflection_opacity = 60;
              }

              $tmp = $this->imagecreatenew ($this->image_dst_x, $this->image_dst_y + $image_reflection_height + $this->image_reflection_space, true);
              $transparency = $this->image_reflection_opacity;
              imagecopy ($tmp, $image_dst, 0, 0, 0, 0, $this->image_dst_x, $this->image_dst_y + ($this->image_reflection_space < 0 ? $this->image_reflection_space : 0));
              if (0 < $image_reflection_height + $this->image_reflection_space)
              {
                if (!empty ($this->image_background_color))
                {
                  sscanf ($this->image_background_color, '#%2x%2x%2x', $red, $green, $blue);
                  $fill = imagecolorallocate ($tmp, $red, $green, $blue);
                }
                else
                {
                  $fill = imagecolorallocatealpha ($tmp, 0, 0, 0, 127);
                }

                imagefill ($tmp, round ($this->image_dst_x / 2), $this->image_dst_y + $image_reflection_height + $this->image_reflection_space - 1, $fill);
              }

              $y = 0;
              while ($y < $image_reflection_height)
              {
                $x = 0;
                while ($x < $this->image_dst_x)
                {
                  $pixel_b = imagecolorsforindex ($tmp, imagecolorat ($tmp, $x, $y + $this->image_dst_y + $this->image_reflection_space));
                  $pixel_o = imagecolorsforindex ($image_dst, imagecolorat ($image_dst, $x, $this->image_dst_y - $y - 1 + ($this->image_reflection_space < 0 ? $this->image_reflection_space : 0)));
                  $alpha_o = 1 - $pixel_o['alpha'] / 127;
                  $alpha_b = 1 - $pixel_b['alpha'] / 127;
                  $opacity = $alpha_o * $transparency / 100;
                  if (0 < $opacity)
                  {
                    $red = round (($pixel_o['red'] * $opacity + $pixel_b['red'] * $alpha_b) / ($alpha_b + $opacity));
                    $green = round (($pixel_o['green'] * $opacity + $pixel_b['green'] * $alpha_b) / ($alpha_b + $opacity));
                    $blue = round (($pixel_o['blue'] * $opacity + $pixel_b['blue'] * $alpha_b) / ($alpha_b + $opacity));
                    $alpha = $opacity + $alpha_b;
                    if (1 < $alpha)
                    {
                      $alpha = 1;
                    }

                    $alpha = round ((1 - $alpha) * 127);
                    $color = imagecolorallocatealpha ($tmp, $red, $green, $blue, $alpha);
                    imagesetpixel ($tmp, $x, $y + $this->image_dst_y + $this->image_reflection_space, $color);
                  }

                  ++$x;
                }

                if (0 < $transparency)
                {
                  $transparency = $transparency - $this->image_reflection_opacity / $image_reflection_height;
                }

                ++$y;
              }

              $this->image_dst_y = $this->image_dst_y + $image_reflection_height + $this->image_reflection_space;
              $image_dst = $this->imagetransfer ($tmp, $image_dst);
            }

            if (((is_numeric ($this->jpeg_size) AND 0 < $this->jpeg_size) AND ($this->image_convert == 'jpeg' OR $this->image_convert == 'jpg')))
            {
              $this->log .= '- JPEG desired file size : ' . $this->jpeg_size . '<br />';
              ob_start ();
              imagejpeg ($image_dst, '', 75);
              $buffer = ob_get_contents ();
              ob_end_clean ();
              $size75 = strlen ($buffer);
              ob_start ();
              imagejpeg ($image_dst, '', 50);
              $buffer = ob_get_contents ();
              ob_end_clean ();
              $size50 = strlen ($buffer);
              ob_start ();
              imagejpeg ($image_dst, '', 25);
              $buffer = ob_get_contents ();
              ob_end_clean ();
              $size25 = strlen ($buffer);
              $mgrad1 = 25 / ($size50 - $size25);
              $mgrad2 = 25 / ($size75 - $size50);
              $mgrad3 = 50 / ($size75 - $size25);
              $mgrad = ($mgrad1 + $mgrad2 + $mgrad3) / 3;
              $q_factor = round ($mgrad * ($this->jpeg_size - $size50) + 50);
              if ($q_factor < 1)
              {
                $this->jpeg_quality = 1;
              }
              else
              {
                if (100 < $q_factor)
                {
                  $this->jpeg_quality = 100;
                }
                else
                {
                  $this->jpeg_quality = $q_factor;
                }
              }

              $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;JPEG quality factor set to ' . $this->jpeg_quality . '<br />';
            }

            $this->log .= '- converting...<br />';
            switch ($this->image_convert)
            {
              case 'gif':
              {
                if (imageistruecolor ($image_dst))
                {
                  $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;true color to palette<br />';
                  $mask = array (array ());
                  $x = 0;
                  while ($x < $this->image_dst_x)
                  {
                    $y = 0;
                    while ($y < $this->image_dst_y)
                    {
                      $pixel = imagecolorsforindex ($image_dst, imagecolorat ($image_dst, $x, $y));
                      $mask[$x][$y] = $pixel['alpha'];
                      ++$y;
                    }

                    ++$x;
                  }

                  sscanf ($this->image_default_color, '#%2x%2x%2x', $red, $green, $blue);
                  $x = 0;
                  while ($x < $this->image_dst_x)
                  {
                    $y = 0;
                    while ($y < $this->image_dst_y)
                    {
                      if (0 < $mask[$x][$y])
                      {
                        $pixel = imagecolorsforindex ($image_dst, imagecolorat ($image_dst, $x, $y));
                        $alpha = $mask[$x][$y] / 127;
                        $pixel['red'] = round ($pixel['red'] * (1 - $alpha) + $red * $alpha);
                        $pixel['green'] = round ($pixel['green'] * (1 - $alpha) + $green * $alpha);
                        $pixel['blue'] = round ($pixel['blue'] * (1 - $alpha) + $blue * $alpha);
                        $color = imagecolorallocate ($image_dst, $pixel['red'], $pixel['green'], $pixel['blue']);
                        imagesetpixel ($image_dst, $x, $y, $color);
                      }

                      ++$y;
                    }

                    ++$x;
                  }

                  if (empty ($this->image_background_color))
                  {
                    imagetruecolortopalette ($image_dst, true, 255);
                    $transparency = imagecolorallocate ($image_dst, 254, 1, 253);
                    imagecolortransparent ($image_dst, $transparency);
                    $x = 0;
                    while ($x < $this->image_dst_x)
                    {
                      $y = 0;
                      while ($y < $this->image_dst_y)
                      {
                        if (120 < $mask[$x][$y])
                        {
                          imagesetpixel ($image_dst, $x, $y, $transparency);
                        }

                        ++$y;
                      }

                      ++$x;
                    }
                  }

                  unset ($mask);
                }

                break;
              }

              case 'jpg':
              {
              }

              case 'bmp':
              {
                $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;fills in transparency with default color<br />';
                sscanf ($this->image_default_color, '#%2x%2x%2x', $red, $green, $blue);
                $transparency = imagecolorallocate ($image_dst, $red, $green, $blue);
                $x = 0;
                while ($x < $this->image_dst_x)
                {
                  $y = 0;
                  while ($y < $this->image_dst_y)
                  {
                    $pixel = imagecolorsforindex ($image_dst, imagecolorat ($image_dst, $x, $y));
                    if ($pixel['alpha'] == 127)
                    {
                      imagesetpixel ($image_dst, $x, $y, $transparency);
                    }
                    else
                    {
                      if (0 < $pixel['alpha'])
                      {
                        $alpha = $pixel['alpha'] / 127;
                        $pixel['red'] = round ($pixel['red'] * (1 - $alpha) + $red * $alpha);
                        $pixel['green'] = round ($pixel['green'] * (1 - $alpha) + $green * $alpha);
                        $pixel['blue'] = round ($pixel['blue'] * (1 - $alpha) + $blue * $alpha);
                        $color = imagecolorclosest ($image_dst, $pixel['red'], $pixel['green'], $pixel['blue']);
                        imagesetpixel ($image_dst, $x, $y, $color);
                      }
                    }

                    ++$y;
                  }

                  ++$x;
                }

                break;
              }

              default:
              {
                break;
              }
            }

            $this->log .= '- saving image...<br />';
            switch ($this->image_convert)
            {
              case 'jpeg':
              {
              }

              case 'jpg':
              {
                if (!$return_mode)
                {
                  $result = @imagejpeg ($image_dst, $this->file_dst_pathname, $this->jpeg_quality);
                }
                else
                {
                  ob_start ();
                  $result = @imagejpeg ($image_dst, '', $this->jpeg_quality);
                  $return_content = ob_get_contents ();
                  ob_end_clean ();
                }

                if (!$result)
                {
                  $this->processed = false;
                  $this->error = $this->translate ('file_create', array ('JPEG'));
                }
                else
                {
                  $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;JPEG image created<br />';
                }

                break;
              }

              case 'png':
              {
                imagealphablending ($image_dst, false);
                imagesavealpha ($image_dst, true);
                if (!$return_mode)
                {
                  $result = @imagepng ($image_dst, $this->file_dst_pathname);
                }
                else
                {
                  ob_start ();
                  $result = @imagepng ($image_dst);
                  $return_content = ob_get_contents ();
                  ob_end_clean ();
                }

                if (!$result)
                {
                  $this->processed = false;
                  $this->error = $this->translate ('file_create', array ('PNG'));
                }
                else
                {
                  $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;PNG image created<br />';
                }

                break;
              }

              case 'gif':
              {
                if (!$return_mode)
                {
                  $result = @imagegif ($image_dst, $this->file_dst_pathname);
                }
                else
                {
                  ob_start ();
                  $result = @imagegif ($image_dst);
                  $return_content = ob_get_contents ();
                  ob_end_clean ();
                }

                if (!$result)
                {
                  $this->processed = false;
                  $this->error = $this->translate ('file_create', array ('GIF'));
                }
                else
                {
                  $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;GIF image created<br />';
                }

                break;
              }

              case 'bmp':
              {
                if (!$return_mode)
                {
                  $result = $this->imagebmp ($image_dst, $this->file_dst_pathname);
                }
                else
                {
                  ob_start ();
                  $result = $this->imagebmp ($image_dst);
                  $return_content = ob_get_contents ();
                  ob_end_clean ();
                }

                if (!$result)
                {
                  $this->processed = false;
                  $this->error = $this->translate ('file_create', array ('BMP'));
                }
                else
                {
                  $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;BMP image created<br />';
                }

                break;
              }

              default:
              {
                $this->processed = false;
                $this->error = $this->translate ('no_conversion_type');
              }
            }

            if ($this->processed)
            {
              if (is_resource ($image_src))
              {
                imagedestroy ($image_src);
              }

              if (is_resource ($image_dst))
              {
                imagedestroy ($image_dst);
              }

              $this->log .= '&nbsp;&nbsp;&nbsp;&nbsp;image objects destroyed<br />';
            }
          }
        }
        else
        {
          $this->log .= '- no image processing wanted<br />';
          if (!$return_mode)
          {
            if (!copy ($this->file_src_pathname, $this->file_dst_pathname))
            {
              $this->processed = false;
              $this->error = $this->translate ('copy_failed');
            }
          }
          else
          {
            $return_content = @file_get_contents ($this->file_src_pathname);
            if ($return_content === FALSE)
            {
              $this->processed = false;
              $this->error = $this->translate ('reading_failed');
            }
          }
        }
      }

      if ($this->processed)
      {
        $this->log .= '- <b>process OK</b><br />';
      }

      $this->init ();
      if ($return_mode)
      {
        return $return_content;
      }

    }

    function clean ()
    {
      $this->log .= '<b>cleanup</b><br />';
      $this->log .= '- delete temp file ' . $this->file_src_pathname . '<br />';
      @unlink ($this->file_src_pathname);
    }

    function imagecreatefrombmp ($filename)
    {
      if (!$f1 = fopen ($filename, 'rb'))
      {
        return false;
      }

      $file = unpack ('vfile_type/Vfile_size/Vreserved/Vbitmap_offset', fread ($f1, 14));
      if ($file['file_type'] != 19778)
      {
        return false;
      }

      $bmp = unpack ('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel' . '/Vcompression/Vsize_bitmap/Vhoriz_resolution' . '/Vvert_resolution/Vcolors_used/Vcolors_important', fread ($f1, 40));
      $bmp['colors'] = pow (2, $bmp['bits_per_pixel']);
      if ($bmp['size_bitmap'] == 0)
      {
        $bmp['size_bitmap'] = $file['file_size'] - $file['bitmap_offset'];
      }

      $bmp['bytes_per_pixel'] = $bmp['bits_per_pixel'] / 8;
      $bmp['bytes_per_pixel2'] = ceil ($bmp['bytes_per_pixel']);
      $bmp['decal'] = $bmp['width'] * $bmp['bytes_per_pixel'] / 4;
      $bmp['decal'] -= floor ($bmp['width'] * $bmp['bytes_per_pixel'] / 4);
      $bmp['decal'] = 4 - 4 * $bmp['decal'];
      if ($bmp['decal'] == 4)
      {
        $bmp['decal'] = 0;
      }

      $palette = array ();
      if ($bmp['colors'] < 16777216)
      {
        $palette = unpack ('V' . $bmp['colors'], fread ($f1, $bmp['colors'] * 4));
      }

      $im = fread ($f1, $bmp['size_bitmap']);
      $vide = chr (0);
      $res = imagecreatetruecolor ($bmp['width'], $bmp['height']);
      $P = 0;
      $Y = $bmp['height'] - 1;
      while (0 <= $Y)
      {
        $X = 0;
        while ($X < $bmp['width'])
        {
          if ($bmp['bits_per_pixel'] == 24)
          {
            $color = unpack ('V', substr ($im, $P, 3) . $vide);
          }
          else
          {
            if ($bmp['bits_per_pixel'] == 16)
            {
              $color = unpack ('n', substr ($im, $P, 2));
              $color[1] = $palette[$color[1] + 1];
            }
            else
            {
              if ($bmp['bits_per_pixel'] == 8)
              {
                $color = unpack ('n', $vide . substr ($im, $P, 1));
                $color[1] = $palette[$color[1] + 1];
              }
              else
              {
                if ($bmp['bits_per_pixel'] == 4)
                {
                  $color = unpack ('n', $vide . substr ($im, floor ($P), 1));
                  if ($P * 2 % 2 == 0)
                  {
                    $color[1] = $color[1] >> 4;
                  }
                  else
                  {
                    $color[1] = $color[1] & 15;
                  }

                  $color[1] = $palette[$color[1] + 1];
                }
                else
                {
                  if ($bmp['bits_per_pixel'] == 1)
                  {
                    $color = unpack ('n', $vide . substr ($im, floor ($P), 1));
                    if ($P * 8 % 8 == 0)
                    {
                      $color[1] = $color[1] >> 7;
                    }
                    else
                    {
                      if ($P * 8 % 8 == 1)
                      {
                        $color[1] = ($color[1] & 64) >> 6;
                      }
                      else
                      {
                        if ($P * 8 % 8 == 2)
                        {
                          $color[1] = ($color[1] & 32) >> 5;
                        }
                        else
                        {
                          if ($P * 8 % 8 == 3)
                          {
                            $color[1] = ($color[1] & 16) >> 4;
                          }
                          else
                          {
                            if ($P * 8 % 8 == 4)
                            {
                              $color[1] = ($color[1] & 8) >> 3;
                            }
                            else
                            {
                              if ($P * 8 % 8 == 5)
                              {
                                $color[1] = ($color[1] & 4) >> 2;
                              }
                              else
                              {
                                if ($P * 8 % 8 == 6)
                                {
                                  $color[1] = ($color[1] & 2) >> 1;
                                }
                                else
                                {
                                  if ($P * 8 % 8 == 7)
                                  {
                                    $color[1] = $color[1] & 1;
                                  }
                                }
                              }
                            }
                          }
                        }
                      }
                    }

                    $color[1] = $palette[$color[1] + 1];
                  }
                  else
                  {
                    return FALSE;
                  }
                }
              }
            }
          }

          imagesetpixel ($res, $X, $Y, $color[1]);
          ++$X;
          $P += $bmp['bytes_per_pixel'];
        }

        --$Y;
        $P += $bmp['decal'];
      }

      fclose ($f1);
      return $res;
    }

    function imagebmp (&$im, $filename = '')
    {
      if (!$im)
      {
        return false;
      }

      $w = imagesx ($im);
      $h = imagesy ($im);
      $result = '';
      if (!imageistruecolor ($im))
      {
        $tmp = imagecreatetruecolor ($w, $h);
        imagecopy ($tmp, $im, 0, 0, 0, 0, $w, $h);
        imagedestroy ($im);
        $im = &$tmp;
      }

      $biBPLine = $w * 3;
      $biStride = $biBPLine + 3 & ~3;
      $biSizeImage = $biStride * $h;
      $bfOffBits = 54;
      $bfSize = $bfOffBits + $biSizeImage;
      $result .= substr ('BM', 0, 2);
      $result .= pack ('VvvV', $bfSize, 0, 0, $bfOffBits);
      $result .= pack ('VVVvvVVVVVV', 40, $w, $h, 1, 24, 0, $biSizeImage, 0, 0, 0, 0);
      $numpad = $biStride - $biBPLine;
      $y = $h - 1;
      while (0 <= $y)
      {
        $x = 0;
        while ($x < $w)
        {
          $col = imagecolorat ($im, $x, $y);
          $result .= substr (pack ('V', $col), 0, 3);
          ++$x;
        }

        $i = 0;
        while ($i < $numpad)
        {
          $result .= pack ('C', 0);
          ++$i;
        }

        --$y;
      }

      if ($filename == '')
      {
        echo $result;
      }
      else
      {
        $file = fopen ($filename, 'wb');
        fwrite ($file, $result);
        fclose ($file);
      }

      return true;
    }
  }

  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

?>
