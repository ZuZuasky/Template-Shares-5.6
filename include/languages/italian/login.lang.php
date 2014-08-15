<?php
/*
+--------------------------------------------------------------------------
|   TS Special Edition v.5.5
|   ========================================
|   by xam
|   (c) 2005 - 2008 Template Shares Services
|   http://templateshares.net
|   ========================================
|   Web: http://templateshares.net
|   Time: December 11, 2008, 11:57 pm
|   Signature Key: TSSE48342008
|   Email: contact@templateshares.net
|   TS SE IS NOT FREE SOFTWARE!
+---------------------------------------------------------------------------
*/
/* 
TS Special Edition English Language File
Translation by xam Version: 0.3

*/

if(!defined('IN_TRACKER'))
  die('Hacking attempt!');

// login.php, takelogin.php
$language['login'] = array 
(
	'head'					=>'Login',
	'loginfirst'			=>'Spiacente, la pagina che stavi cercando di vedere <b>puo\' essere solo vista se sei loggato al tracker</b>. Sarai rediretto dopo aver effettuato il login.',
	'error1'				=>'ERRORE: Username o Password errati! Prova ancora o resetta la tua password cliccando <a href="recover.php">QUI</a>.<br>Hai ancora <b>{1}</b> tentativi prima che il tuo IP venga bannato.',	
	'info'					=>'<p><b>NOTA</b>: Devi avere i cookies attivati per effettuare il log in.<br /> [<b>{1}</b>] tentativi falliti causeranno il ban del tuo ip!</p>',
	'username'				=>'Username:',
	'password'				=>'Password:',
	'logout15'				=>'Sloggami dopo 15 minuti di inattivita\'',
	'securelogin'			=>'Login Sicuro',
	'login'					=>'LOGIN',
	'reset'					=>'RESETTA',
	'footer'				=>'<center><br /><p>Non hai un account? Clicka <a href="signup.php"><b>QUI</b></a> per registrarne <a href="signup.php"><b>GRATUITAMENTE</b></a> uno!<br /><br />Hai dimenticato la password? Resettala tramite <a href="recover.php"><b>email</b></a> o <a href="recoverhint.php"><b>domanda segreta</b></a>.<br /><br />Non hai ricevuto il codice di attivazione? Clicka <a href="'.$_SERVER['SCRIPT_NAME'].'?do=activation_code"><b>QUI</b></a>.<br /><br />Hai Domande? <a href="'.$BASEURL.'/contactus.php"><b>Contattaci!</b></a>.</p></center>',
	'banned'				=>'Questo account e\' stato BANNATO!',
	'pending'				=>'Devi attivare il tuo account prima!',
	'logged'				=>'Sei stato loggato con successo...',
	'resend'	=>'Reinvia codice di attivazione', // Added v3.9
	'resend2'	 =>'Inserisci l\'indirizzo email che corrisponde al tuo account {1}', // Added v3.9
	'resend3'	 =>'Reinvia', // Added v3.9
	'resend4'	 =>'L\'email che hai specificato non e\' valida. E\' gia\' presente nel nostro database.', // Added v3.9
);
?>