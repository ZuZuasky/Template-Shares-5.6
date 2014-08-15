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
Translation by xam Version: 0.1

*/

if(!defined('IN_TRACKER'))
  die('Hacking attempt!');

// port_check.php
$language['port_check'] = array 
(
	'head'		=>	'Port Checker (Test di Connettivita\')',
	'title'			=>	'Questo Test verifichera\' se la Porta da te specificata e\' aperta o meno.',
	'checking'	=>	'Sto testando la Porta ...',
	'good'		=>	'<font color="green">OK!</font> la Porta <b>{1}</b> e\' aperta e accetta Connessioni. Clicka <a href="'.$_SERVER['SCRIPT_NAME'].'">QUI</a> per testare un\'altra Porta.',
	'bad'			=>	'<font color="red">ERRORE!</font> la Porta <b>{1}</b> non risulta essere aperta! Per favore vai sul sito: www.portforward.com per maggiori informazioni riguardo la configurazione delle porte. Clicka <a href="'.$_SERVER['SCRIPT_NAME'].'">QUI</a> per testare un\'altra Porta.',
	'field1'		=>	'Porta Numero:',
	'field2'		=>	'Testa Porta',
);
?>