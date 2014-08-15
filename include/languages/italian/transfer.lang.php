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

// Transfer.php
$language['transfer'] = array 
(
	'head'		=> 'Dati di Transferimento',
	'field1'	=>	 'Username: ',
	'field2'	=>	 'Totale trasferimento:',
	'button'	=>	 'Trasferisci',
	'head2'	=>	 'Status/Info',
	'info'		=> '1) Inserisci username <br>2) Inserisci Totale Trasferito <br> 3) Clicca sul bottone \'trasferisci\'.<br><br>Note: Max. {1} consentito.',
	'info2'		=> 'Il trasferimento e\' avvenuto con successo.<br> L\'Utente {1} ha ricevuto {2} in Upload da te.<br> Grazie.',
	'noway'	=>	 'Non hai altro upload da trasferire.<br> Uppa un torrent o rimani in seed per averne altro.',
	'noway2'	 =>'Non puoi auto-inviarti upload!',
	'msgsubject' => 'Trasferisci Upload!',
	'msgbody'	=>'Ciao {1},

	{2} Ti ha inviato {3} di upload, che si aggiunge al tuo stato attuale.

	buona giornata.
	',
	'noway3'=>'Puoi Trasferire solo una volta al giorno, riprova domani!', //Added v4.1
	'noway4'=>'Questo utente ha gia\' ricevuto un trasferimento da un altro utente oggi.. Prova domani!', //Added v4.1
	'goback'=>'Clicca QUI per tornare indietro', //Added v4.3
	'head3'=>'Calcola', //Added v4.3
	'result'=>'Risultato: ', //Added v4.3
	'amount'=>'Quantita\': ', //Added v4.3
);
?>