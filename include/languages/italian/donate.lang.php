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
Translation by xam Version: 0.5
*/

if(!defined('IN_TRACKER'))
  die('Hacking attempt!');

// donate.php
$language['donate'] = array
(
	'header'				=>'Pagina Donazioni',
	'welcome'				=>'Benvenuto nella Pagina Donazioni di {1}</h2>',
	'donation'				=>'{1} {2} Donazione',
	'thanks'				=>'Grazie per il tuo interesse a donare. <br>
Il nostro sito e\' NO-PROFIT. Tutte le donazioni ci servono per pagare il nostro server.<br>La quantita\' non conta, qualsiasi donazione e\' apprezzata.<br><center><b><br>Seleziona la quantita\' da donare e clicca sul link di Paypal qui sotto!</b><br><br>',
	'item_name'				=>'Donazione da {1} (UID: {2})',
	'chooseamount'			=>'---Scegli Quantita\' da Donare---',
	'otheramount'			=>'Altra Quantita\'',	
	'paypal_error1'			=>'C\'e\' un errore nella connessione a Paypal... (Error NO: PA_1)',
	'paypal_error2'			=>'<p class=error><b>STATO:</b> FALLITO! Hai gia\' donato.</p>',
	'paypal_error3'			=>'Contattaci per segnalare questo errore! (Error NO: PA_2) Azione LOGGATA.',
	'paypal_error4'			=>'Contattaci per segnalare questo errore! (Error NO: TE_1)',
	'paypal_head'			=>'Grazie per la tua donazione! Il pagamento e\' avvenuto con successo!',
	'paypal_subheader'		=>'<h3><b><br><font color=blue>AGGIORNAMENTO AUTOMATICO ACCOUNT</b></h3></font>',
	'paypal_info'			=>'<h3><b><font color=darkgreen>Grazie per la tua donazione! Il pagamento e\' avvenuto con successo.</b></font></h3><br><h3><b>DETTAGLI PAGAMENTO <font color=red>(Per favore, stampa questa pagina. Una email ti sara\' inviata con tutti i dettagli.)</font></b></h3>',
	'paypal_results'		=>'<ul>
	<li><b>NOME:</b> {1} {2}</li>
	<li><b>EMAIL:</b> {3}</li>
	<li><b>OGGETTO:</b> {4}</li>
	<li><b>TOTALE:</b> {5}</li>
	<li><b>VALUTA:</b> {6}</li>
	<li><b>STATUS:</b> {7}</li></ul>',
	'paypal_dur'			=>'{1} settimane',
	'paypal_msg_subject'	=>'Grazie per aver donato!',
	'paypal_msg_body'		=>'Caro {1}
				
	Grazie per il tuo supporto a {2}!
	La tua donazione ci aiuta nel mantenimento del sito!

	NOTA: Il tuo stato DONATORE rimarra\' per {3} e puo\' essere trovato nel tuo UserCP.

	Vogliamo ringraziarti ancora una volta per il supporto dato,
	Coi migliori riguardi,
	Lo Staff di {2} ',
	'paypal_finish'			=>'<p class=success><b>STATO:</b> SUCCESSO! <ul><li> Inviti: +{1}</li> <li> Totale Upload: +{2} GB</li> <li> Punti Bonus: +{3}</li> <li> Stato DONATORE: {4}</li></ul></p>',
	'donorlist'				=>'Lista Donatori - TOP 20', // Added in v3.8
	'ipninfo'					=>'Le donazioni saranno eseguite tramite Paypal IPN, questo significa che verrai accreditato automaticamente.',// Added in v3.8
	'processing'				=>'Attendere... Stiamo processando la tua richiesta...', // Added in v3.8
	'supportusdonate'	 =>'Aiutaci - Dona', // Added in v3.8
	'select1'	=>'Seleziona Metodo di Pagamento', // Added in v3.8
	'donatebutton'	 =>'Dona', // Added in v3.8
	'donatebutton2'	=>'Resetta', // Added in v3.8
	'donotlist'	=>'Lista Donatori - TOP 20', // Added in v3.8
	'promotions'	 =>'Promozioni', // Added in v3.8
	'donatexreceive' =>'Dona {1} {2} e ricevi:', // Added in v3.8
	'donatex'	 =>'Dona {1} {2}', // Added in v3.8
	'q1'=>'settimane di Status {1}', // Added in v3.8
	'q2' => 'GB in Upload', // Added in v3.8
	'q3' =>'Inviti', // Added in v3.8
	'q4'=>'Punti Bonus', // Added in v3.8
	'default'	 =>'
	<div align="center" class="subheader"><b>Default per tutte le donazioni:</b></div><br>Dona e Ricevi:
	<ul>
	<li>Immunita\' al BAN per ratio basso</li>
	<li>Stella donatore nell\'username</li>
	</ul>', // Added in v3.8
	'wiretransfer'	=>'Wire Transfer', //Added in v3.8
	'thanks1'	 =>'Grazie per il tuo AIUTO',//Added in v3.8
	'thanks2'	 =>'Il tuo pagamento e\' andato a buon fine e sarai promosso VIP al più presto.<br> Per favore inviaci il tuo ID di MoneyBookers e l\'ID del pagamento così che possiamo accreditarti.<br><br> Grazie ancora!',//Added in v3.8
	'received'=>'Avete Donato: ',//Added in v5.3
	'targetamount'=>'Obiettivo: ',//Added in v5.3
	'stilltogo'=>'Mancano ancora: ',//Added in v5.3
	'clicktodonate'=>'Clicka <a href="{1}/donate.php" onclick="window.opener.location.href=this;window.close();return false;"><b>QUI</b></a> per donare',//Added in v5.3
	'systemmessage'=>'Donando {1}, hai dato una sicurezza al futuro del Sito, GRAZIE!'//Added in v5.3
);