<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  function send_test_mail_extra ($from = '', $to = '', $subject = '', $body = '', $debug = 'no')
  {
    global $success;
    global $SITEEMAIL;
    global $rootpath;
    if (!$rootpath)
    {
      $rootpath = './';
    }

    require INC_PATH . '/smtp/smtp.lib.php';
    $mail = new smtp ();
    if ($debug == 'yes')
    {
      $mail->debug (true);
    }
    else
    {
      $mail->debug (false);
    }

    $mail->open (smtpaddress, smtpport);
    $mail->auth (accountname, accountpassword);
    $mail->from ($SITEEMAIL);
    $mail->to ($to);
    $mail->subject ($subject);
    $mail->body ($body);
    $mail->send ();
    $mail->close ();
    print '' . $success;
  }

  function send_test_mail_default ($to, $fromname, $fromemail, $subject, $body)
  {
    global $SITENAME;
    global $SITEEMAIL;
    global $smtp;
    global $smtp_host;
    global $smtp_port;
    global $smtp_from;
    if (strtoupper (substr (PHP_OS, 0, 3) == 'WIN'))
    {
      $eol = '
';
      $windows = true;
    }
    else
    {
      if (strtoupper (substr (PHP_OS, 0, 3) == 'MAC'))
      {
        $eol = '
';
      }
      else
      {
        $eol = '
';
      }
    }

    $mid = md5 (uniqid (rand (), true));
    $name = $_SERVER['SERVER_NAME'];
    $headers .= '' . 'From: ' . $fromname . ' <' . $fromemail . '>' . $eol;
    $headers .= '' . 'Reply-To: ' . $fromname . ' <' . $fromemail . '>' . $eol;
    $headers .= '' . 'Return-Path: ' . $fromname . ' <' . $fromemail . '>' . $eol;
    $headers .= '' . 'Message-ID: <' . $mid . ' thesystem@' . $name . '>' . $eol;
    $headers .= 'X-Mailer: PHP v' . phpversion () . $eol;
    $headers .= 'MIME-Version: 1.0' . $eol;
    $headers .= 'X-Sender: PHP' . $eol;
    if ($multiple)
    {
      $headers .= '' . 'Bcc: ' . $multiplemail . '.' . $eol;
    }

    if ($smtp == 'yes')
    {
      ini_set ('SMTP', $smtp_host);
      ini_set ('smtp_port', $smtp_port);
      if ($windows)
      {
        ini_set ('sendmail_from', $smtp_from);
      }
    }

    (@mail ($to, $subject, $body, $headers) OR bark ('Unable to send mail. Please check your SMTP settings or contact your host!'));
    ini_restore (SMTP);
    ini_restore (smtp_port);
    if ($windows)
    {
      ini_restore (sendmail_from);
    }

  }

  function bark ($msg)
  {
    global $header;
    global $footer;
    print '' . $header;
    print '' . '<tr><td align=right><font color=red><b>ERROR:</b></td><td><b><font color=red>' . $msg . '</b></font> [<A HREF="javascript:history.go(-1)"><b><u>Go Back</u></b></A>]</tr></td>';
    print '' . $footer;
    exit ();
  }

  require ($rootpath ? $rootpath : './') . 'global.php';
  include_once INC_PATH . '/readconfig_smtp.php';
  gzip ();
  dbconn ();
  loggedinorreturn ();
  maxsysop ();
  $version = 'SMTP CHECK v.0.7';
  $action = (isset ($_POST['action']) ? htmlspecialchars ($_POST['action']) : 'showform');
  $type = ($_POST['sendtype'] ? htmlspecialchars ($_POST['sendtype']) : '');
  if ($usergroups['issupermod'] != 'yes')
  {
    bark ('Access denied.');
  }

  $defaulttemplate = ts_template ();
  $header = '' . '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>' . $version . '</title>
<link rel="stylesheet" href="' . $BASEURL . '/include/templates/' . $defaulttemplate . '/style/style.css" type="text/css" media="screen" />
</head>
<body><p>
<table border=1 cellspacing=0 cellpadding=10 bgcolor=black width=100%><tr><td style=\'padding: 10px; background: black\' class=text>
<font color=white><center>' . $version . '</font></center></td></tr></table></p>
<table border=1 cellspacing=0 cellpadding=10 width=100%>';
  $footer = '</table></body></html>';
  $testmail = '' . 'Hi,

If you see this message, your SMTP function works great.

Have a nice day.';
  $success = '' . $header . ' <tr><td><b><font color=black>No error found however this does not mean the mail arrived 100%.<br />Use debug mode to see more results if you use external mail function.</b></font></tr></td> ' . $footer;
  if ($action == 'sendmailextra')
  {
    define ('smtpaddress', '' . $_POST['smtpaddress'], true);
    define ('smtpport', '' . $_POST['smtpport'], true);
    define ('accountname', '' . $_POST['accountname'], true);
    define ('accountpassword', '' . $_POST['accountpassword'], true);
    define ('email', '' . trim (htmlspecialchars ($_POST[email])) . '', true);
    send_test_mail_extra ('' . $SITEEMAIL, email, '' . $SITENAME . ' SMTP Testing Mail', '' . $testmail, '' . ($_POST['debug'] == 'yes' ? 'yes' : 'no') . '');
    unset ($action);
  }

  if ($action == 'sendmail')
  {
    function safe_email ($email)
    {
      return str_replace (array ('<', '>', '\\\'', '\\"', '\\\\'), '', $email);
    }

    $email = htmlspecialchars (trim ($_POST['email']));
    $email = safe_email ($email);
    if (!check_email ($email))
    {
      bark ('Invalid email address!');
    }

    if ($type == 'sendtypeextra')
    {
      print '' . $header;
      print '<form method=post action=mailtest.php>';
      print '<input type=hidden name=action value=sendmailextra>';
      print '' . '<input type=hidden name=email value=\'' . $email . '\'>';
      print '<tr><td align=right><font color=black>Outgoing mail (SMTP) address:</td><td><input type=text name=smtpaddress size=40> <font color=black><b>hint:</b> smtp.yourisp.com</td></tr>';
      print '<tr><td align=right><font color=black>Outgoing mail (SMTP) port:</td><td><input type=text name=smtpport size=40> <font color=black><b>hint:</b> 80</td></tr>';
      print '<tr><td align=right><font color=black>Account Name:</td><td><input type=text name=accountname size=40> <font color=black><b>hint:</b> yourname@yourisp.com</td></tr>';
      print '<tr><td align=right><font color=black>Account Password:</td><td><input type=password name=accountpassword size=40> <font color=black><b>hint:</b> your password goes here</td></tr>';
      print '<tr><td align=right><font color=black>Debug Mode?:</td><td><input type=radio name=debug value=yes><font color=black>yes <input type=radio name=debug value=no checked>no &nbsp;&nbsp;&nbsp;&nbsp;<font color=black><b>hint:</b> set \'yes\' to see more results after you click on the send button</td></tr>';
      print '<tr><td align=right><font color=black><b><u>WARNING:</u> Don\'t leave any fields blank!</b></td><td><input type=submit name=send value=\'Send test mail (PRESS ONLY ONCE)\'></form></td></tr>';
      print '' . $footer;
    }

    if ($type == 'sendtypedefault')
    {
      send_test_mail_default ($email, $SITENAME, $SITEEMAIL, '' . $SITENAME . ' SMTP Testing Mail', $testmail);
      print '' . $success;
    }

    unset ($action);
  }

  if ($action == 'showform')
  {
    print '' . $header;
    print '<form method=\'post\' action=\'mailtest.php\'>';
    print '<input type=\'hidden\' name=\'action\' value=\'sendmail\'>';
    print '<tr><td align=right><font color=black>Enter an email address to send a test mail:</td><td><input type=\'text\' name=\'email\' size=35><font color=black><b>hint:</b> yourname@hotmail.com</td></tr>';
    print '<tr><td align=right><font color=black>Select send method:</td><td><input type=\'radio\' name=\'sendtype\' value=\'sendtypedefault\' checked><font color=black>Use default PHP mail function.  <input type=\'radio\' name=\'sendtype\' value=\'sendtypeextra\'>Use external mail function (Your ISP or Your Host).</td></tr>';
    print '<tr><td align=right><font color=black><b><u>WARNING:</u> Don\'t leave any fields blank!</b></td><td><input type=\'submit\' name=\'sendmail\' value=\'Send test mail (PRESS ONLY ONCE)\'></form></td></tr>';
    print '' . $footer;
    unset ($action);
  }

?>
