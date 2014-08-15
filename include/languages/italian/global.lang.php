<?php
/*
+--------------------------------------------------------------------------
|  TS Special Edition v.5.6 
|   ========================================
|   by xam
|   (c) 2005 - 2008 Template Shares Services
|   http://templateshares.net
|   ========================================
|   Web: http://templateshares.net
|   Time: January 22, 2009, 11:27 pm
|   Signature Key: TSSE48342009
|   Email: contact@templateshares.net
|   TS SE IS NOT FREE SOFTWARE!
+---------------------------------------------------------------------------
*/
/* 
TS Special Edition English Language File
Translation by xam Version: 1.9

*/

if(!defined('IN_TRACKER'))
  die('Hacking attempt!');

//  Funtions.php, global.php, redirector.php, redirector_footer.php shoutbox.php and proxydetector.php Messages Messages
$language['global'] = array 
(
	'error'							=>'C\'e\' stato un errore!', // Global Error Messages
	'permission'					=>'Sfortunatamente non hai i permessi per visualizzare questa pagina.',
	'notavailable'				=>'Sfortunatamente questa opzione e\' disabilitata al momento.',
	'nopermission'				=>'Spiacenti, permesso negato!',
	'permissionlogmessage'	=>'Scoperto accesso non autorizzato.<br />Alla pagina: {1},<br /> String: {2} <br />Username: {3},<br />IP: {4}.<br />Questo accesso non voluto e\' stato bloccato con successo.',
	'print_no_permission'		=>'<table border="0" cellspacing="1" cellpadding="4" class="tborder">
<tr>
<td class="thead"><span class="smalltext"><strong>{1}</strong></span></td>
</tr>
<tr>
<td class="trow1"><!-- start: error_nopermission_loggedin -->
Questo accesso non voluto e\' stato bloccato con successo.
<ol>
	<li>Il tuo Account e\' stato sospeso o sei stato bannato dall\'accesso a questa risorsa.</li>
	<li>Non hai il permesso di accedere a questa pagina. Stai provando ad accedere a pagine dell\'amministrazione o a fare ricerche che non puoi fare? Controlla nelle regole se hai la possibilita\' di fare questo</li>
	<li>Il tuo Account e\' in attesa di attivazione o moderazione.</li>
	<li>{2}</li>
</ol>
<!-- end: error_nopermission_loggedin --></td>
</tr>
</table>',
	'print_no_permission_i' =>'Hai la possibilita\' di contattarci riguardo questo errore.',
	'invalidid'					=>'ID NON valido!',
	'invalididlogged'		=>'ID NON valido! Per ragioni di sicurezza, questa azione sara\' inserita nei Log!',
	'invalididlogged2'		=>'<div class="error" align="center"><b>Errore: ID NON valido! Per ragioni di sicurezza, questa azione sara\' inserita nei Log!</b></div>',
	'invalididlogmsg'		=>'Tentativo di ID NON valido: URL: {1} - Username: {2} - IP : {3} a {4}.',
	'noresultswiththisid'	=>'Non ci sono risultati con questo ID!',
	'invalidimagecode'		=>'La stringa inserita per la verifica tramite immagine non corrisponde a quella visualizzata.<br />Hai ancora <b>{1}</b> tentativi rimasti.',
	'nouserid'				=>'Nessuno User con questo ID!',
	'nousername'			=>'Nessuno User con questo NOME!',
	'notorrentid'				=>'Nessun torrent con questo ID!',
	'notorrentname'		=>'Nessun torrent con questo NOME!',
	'accountdisabled'		=>'<b><font color="red">Questo Account e\' stato disabilitato!</font></b>',
	'sorry'						=>'Spiacenti',
	'invalidaction'			=>'Azione sconosciuta!',
	'dberror'					=>'Azione sconosciuta',
	'trylater'					=>'C\'e\' stato un errore, per favore riprova piu\' tardi.',
	'nothingfound'			=>'Non e\' stato trovato nulla',
	'accessdenied'			=>'Accesso Negato!!!',
	'permissiondenied'		=>'Permesso Negato!!!',
	'flooderror'				=>'Questo tracker richiede che si attenda <b>{1}</b> secondi per mandare {2}. per favore riprova tra <b>{3}</b> secondi.',
	'dontleavefieldsblank'	=>'Per favore non lasciare in bianco i campi richiesti!',
	'allfieldsrequired'		=>'Tutti i campi sono richiesti!',
	'viptorrent'				=>'Tutti i campi sono richiesti! <b><a href="donate.php">VIP</a></b>.',
	'torrentbanned'		=>'Questo torrent e\' stato bannato!',// Global Error Messages
	'welcomeback'			=>'Bentornato/a,', // Header Messages
	'logout'					=>'[logout]',
	'ratio'						=>'Ratio:',
	'bonus'					=>'Bonus:',
	'uploaded'				=>'Up:',
	'downloaded'			=>'Down:',
	'whencompleted'		=>'Completato',
	'donate'					=>'Clicka QUI per donare',
	'inboxnonew'			=>'Inbox (Nessun nuovo messaggio)',
	'enterusername'		=>'Inserisci Username',
	'inboxnew'				=>'Inbox (C\'e\' un nuovo messaggio dalla tua ultima visita, clicka QUI per leggerlo.)', // Header Messages
	'home'					=>'Home', // Menu
	'forums'					=>'Forums',	
	'browse'					=>'TORRENT',	
	'requests'				=>'Richieste/Offerte',	
	'upload'					=>'Upload',	
	'usercp'					=>'User CP',	
	'Blog'						=>'Blog SBT',
	'top10'					=>'Top-10',	
	'help'						=>'Help',	
	'extra'					=>'Extra',	
	'staff'						=>'Staff',
	'redirect'					=>'Ora sarai rediretto...',
	'msgsend'				=>'Messaggio inviato correttamente!',
	'staffmenu'				=>'Staff Menu',// Menu
	'fakeaccount'			=>'Messaggio inviato correttamente!', 
	// Funtions.php, global.php, redirector.php, redirector_footer.php, shoutbox.php and proxydetector.php Messages Messages
	'alreadylogged'			=>'Sei gia\' loggato!',
	'nowaitmessage'		=>'Clicka QUI se non vuoi attendere oltre.',
	'cachedmessage'		=>'<div align="center" class="smalltext">Questo contenuto e\' stato registrato il <strong>{1}</strong>. Le Statistiche si aggiornano ogni <strong>{2}</strong> minuti.</div>',	
	'browsermessage'		=>'<p class="error" align="justify">Se hai i cookies abilitati e non puoi ancora far il login, probabilemente e\' successo qualcosa che ha creato problemi con i cookie. Ti suggeriamo di cancellare i cookies e provare di nuovo. Per cancellare i cookies in Internet Explorer, vai in Opzioni > Opzioni Internet e clicka su Cancella Cookies. Nota: questo cancellera\' tutti i cookies salvati.</p>',
	'mailerror'				=>'Non e\' possibile mandare email. Per favore contatta un Amministratore riguardo questo errore.',
	'success'					=>'Successo',
	'mailsent'				=>'Una email di conferma e\' stata mandata a <b>{1}</b>. Una email di conferma e\' stata mandata a',
	'mailsent2'				=>'I nuovi dettagli dell\'Account sono stati mandati a <b>{1}</b>. Per favore attendi qualche minuto.',
	'xlocked'					=>'{1} bloccato! (il numero massimo di {1} tentativi falliti e\' stato raggiunto durante la re-autenticazione)',
	'xlocked2'				=>'Siamo portati a credere che stai tentando di imbrogliare il nostro sistema, quindi il tuo IP sara\' bannato!<br /><br />Clicka {1}QUI</a> per compilare il form di Richiesta Unban!',
	'warning'					=>'Attenzione!',	
	'accountwarn'			=>"Dal tuo ultimo login, un utente ha provato ad accedere al tuo account.\nDi seguito ci sono i dettagli:\n\nUsername: {1}\nPassword: {2} (MD5: {3})\n\nIP: {4}\nHostname: {5}\n\nSe credi che ci siano problemi per favore contatta lo Staff..\nGrazie.",
	'incorrectlogin'			=>'<b>Errore</b>: Username o password non corretti!!<br /><br />non ricordi la Password? <b>{1}Clicka QUI</a></b> per recuperarla!',
	'invitedisabled'			=>'Il Sistema Inviti e\' attualmente disabilitato.',
	'inviteonly'				=>'Devi esser invitato per poterti registrare!',
	'signupdisabled'		=>'Le Registrazioni sono attualmente disabilitate.',
	'signuplimitreached'	=>'l limite utenti e\' stato raggiunto. Accounts Inattivi subiranno il prunning periodicamente, per favore riprova un\'altra volta...',
	'nodupeaccount'		=>'L\'IP {1} e\' gia\' usato da un altro account! Non sono ammessi doppi Account.',
	'nodupeaccount2'		=>'Spiacenti, l\'IP e\' gia\' in uso, se credi che sia un errore contattaci!',
	'secimage'				=>'Immagine di sicurezza:<br />(Case sensitive: attenzione alle maiuscole)',	
	'seccode'				=>'Codice di sicurezza: ',
	'slots'						=>'Slots: <font color="white">{1}</font>&nbsp;&nbsp;',
	'serverload'				=>'<html><head><meta http-equiv="refresh" content="5 {1}"></head><body><table border=0 width=100% height=100%><tr><td><h3 align=center>Il carico del Server e\' davvero pesante al momento. Sto riprovando, per favore attendere...</h3></td></tr></table></body></html>',
	'toomanyusers'			=>'Troppi users connessi. Per favore fai il Refresh sul tuo browser per riprovare.',
	'ipbanned'				=>'<html><body><h1>403 Forbidden</h1>IP non autorizzato.</body></html>',
	'trackerclosed'			=>'<font color="red"><b>Spiacenti, il Sito e\' down per manutenzione, per favore riprova piu\' tardi...</b></font>',
	'newmessage'			=>'Inbox (C\'e\' un nuovo messaggio dalla tua ultima visita, clicka qui per leggere.)',
	'nonewmessage'		=>'Inbox (Nessun nuovo messaggio)',
	'annoucementempty'	=>'Vuoto!',
	'nonewannoucement'	=>'Non ci sono nuovi Annunci al momento.',
	'edit'						=>'Modifica',
	'deletecomment'		=>'Cancella',
	'vieworj'					=>'Vedi originale',
	'lastedited'				=>'Ultimo Edit di ',
	'sendmessageto'		=>'Manda Messaggio a ',
	'reportcomment'		=>'Reporta questo commento',
	'type'						=>'Tipo',
	'name'					=>'Nome',
	'added'					=>'Aggiunto',
	'dl'							=>'DL',
	'wait'						=>'Attendere',
	'visible'					=>'Visibile',
	'avprogress'				=>'Salute', // Changed v3.6
	'progress'				=>'Progresso',
	'speed'					=>'Velocita\'',
	'notraffic'				=>'Nessun traffico',
	'size'						=>'Dimensione',
	'ttl'						=>'TTL',
	'free'						=>'Free',
	'rec'						=>'Rec.',
	'views'					=>'Visite',
	'hits'						=>'Hits',
	'lastaction'				=>'Ultima Azione',
	'leechers'				=>'Leechers',
	'seeders'					=>'Seeders',
	'snatched'				=>'Completi',
	'uploader'				=>'Uploader',
	'action'					=>'Azione',
	'none'						=>'Nessuno',
	'greenyes'				=>'<font color="green">Si\'</font>',
	'redno'					=>'<font color="red">No</font>',
	'yes'						=>'<b>Si\'</b>',
	'no'						=>'<b>No</b>',
	'anonymous'			=>'<i>[Anonimo]</i>',
	'unknown'				=>'<i>(sconosciuto)</i>',
	'freedownload'			=>'<b>Free Torrent</b> (Solo le statistiche di Upload saranno registrate!)',
	'newtorrent'				=>'<b>Nuovo Torrent</b> (nuova release)',
	'disabled'					=>'Disabilitato',
	'parked'					=>'Il tuo Account e\' parcheggiato.',
	'legend'					=>'<fieldset class="fieldset"><legend><b>Legend</b></legend><center>
&nbsp;<b><font color="darkred">Staff Leader</font>&nbsp;&nbsp;<font color=#2587A7>Sysop</font>&nbsp;&nbsp;<font color=#B000B0>Admin</font>&nbsp;&nbsp;<font color=#ff5151>Moderator</font>&nbsp;&nbsp;<font color=#6464FF>Uploader</font>&nbsp;&nbsp;<font color=#009F00>VIP</font>&nbsp;&nbsp;<font color=#f9a200>Power User</font>&nbsp;&nbsp;<font color=black>User</font>&nbsp;&nbsp;Donor\'s<img src="{1}star.gif" border=0 style="vertical-align: middle;">&nbsp;&nbsp;Warned Users<img src="{1}warned.gif" border=0 style="vertical-align: middle;">&nbsp;&nbsp;Banned Users<img src="{1}disabled.gif" border=0 style="vertical-align: middle;"></b></center></fieldset>',
	'pagedown'				=>'Spiacente, questa pagina e\' down per manutenzione, per favore riprova piu\' tardi...',//
	'pleasewait'				=>'Per favore attendere...',
	'sqlerror'					=>'ERRORE SQL',
	'sqlerror2'				=>'C\'e\' stato un errore!. Per favore contatta un Amministratore a riguardo.',
	'quote'					=>'ha scritto: ',
	'quote2'					=>'Quota: ',
	'quote3'					=>'QUOTA',
	'code'						=>'CODICE',
	'user'						=>'User',
	'poweruser'				=>'Power User',
	'vip'						=>'VIP',
	'uploader'				=>'Uploader',
	'moderator'				=>'Moderator',
	'sysop'					=>'SysOp',
	'administrator'			=>'Administrator',
	'staffleader'				=>'Staff Leader',
	'guest'					=>'Guest',
	'supermod'				=>'Super Moderator',
	'awaitingactivation'	=>'Aspettando Attivazione',
	'banned'					=>'Banned',
	'betatester'				=>'Beta Tester',
	'sendtousername'		=>'Invia a (username): ',
	'subject'					=>'Oggetto:',
	'message'				=>'Il tuo Messaggio:',
	'pmspace'				=>'usato dello spazio per i PM.',
	'reached_warning'		=>'Attenzione. Hai raggiunto il limite dei messaggi.',
	'reached_warning2'	=>'Per poter ricevere messaggi devi cancellarne qualcuno tra i vecchi.',
	'pmlimitmsg'				=>'Hai <strong>{1}</strong> messaggi archiviati, su un totale di <strong>{2}</strong> concessi.',
	'pmmsg'					=>'{1} contiene {2} messaggi.',
	'moresmiles'				=>'Altri Smiles',
	'moresmilestitle'		=>'Altri Smiles',
	'color'						=>'Colore',
	'font'						=>'Carattere',
	'size'						=>'Dimensione',
	'closealltags'			=>'Chiudi tutti i tags',
	'list'						=>'LISTA',
	'finduser'					=>'Trova User',
	'redirectto'				=>'Rediretta',
	'invalidlink'				=>'Link NON valido?',
	'clicktoreport'			=>'Clicka qui per inoltrare un report',
	'shouterror'				=>'Spiacenti, non sei autorizzato a postare Shout!',
	'proxydetected'		=>'Individuato Proxy server. Non sono ammesse registrazioni tramite Proxy.', // Funtions.php, global.php, redirector.php, redirector_footer.php, shoutbox.php and proxydetector.php Messages Messages
	'buttonsearch'			=>'Cerca', // submit buttons
	'buttoncheckall'		=>'Seleziona tutto',
	'buttonsave'			=>'Salva',
	'buttonreset'			=>'Resetta',
	'buttonpreview'		=>'Anteprima',
	'buttonshout'			=>'Shout',
	'buttonclear'			=>'Cancella',
	'buttonrate'				=>'Vota',
	'buttonthanks'			=>'Grazie!',
	'buttonsubmit'			=>'Inserisci',
	'buttonrevert'			=>'Annulla Modifiche',
	'buttonselect'			=>'Seleziona un User',
	'buttonclosewindow'	=>'Chiudi Finestra',
	'buttondelete'			=>'Cancella!',
	'buttonlogin'			=>'Login',
	'buttongo'				=>'Vai!',
	'buttongoback'			=>'Torna indietro!',
	'buttonrecover'		=>'Recupera',
	'buttonreport'			=>'Inoltra Report!',
	'buttonremoveframe'	=>'Rimuovi',
	'buttonsend'			=>'Invia', // submit buttons
	'imgdonated'			=>'Donato', // Image titles
	'imgdisabled'			=>'Questo account e\' stato disabilitato!',
	'imgwarned'				=>'Ammonito',
	'imgupdated'			=>'Aggiornato',
	'imgshowhide'			=>'Mostra/Nascondi',
	'imgnew'					=>'Nuovo',	// Image titles
	'modnotice'				=>'<strong><a href="userdetails.php?id={1}"><span style="color: darkred;"><strong><em>{2}</em></strong></span></a> ha editato questo post il {3} perche\':</strong>
	<br /><p>{4}</p>',
	'usergroup'			=>'Usergroup:',
	'smilies'				=>'Smiles',
	'postoptions'		=>'Opzioni Post:',
	'title'					=>'Titolo:',
	'silverdownload'	=>'<b>Silver Torrent</b> (solo il 50% delle statistiche di download saranno registrate!)',
	'started'				=>'Iniziato',
	'imgcommentpos'	=> 'Commenti Disabilitati!',
	'imgsendpmpos'	=> 'PM Disabilitati!',
	'imgchatpost'		=> 'Shoutbox/Chat Disabilitate!',	
	'imgdownloadpos'	=>'Download Disabilitato!',
	'imguploadpos'		=> 'Upload Disabilitato!',
	'previous'			=> 'Precedente',
	'first'					=> 'Primo',
	'next'					=> 'Successivo',
	'last'					=>' Ultimo',
	'navigation'			=> 'Pagina {1} di {2} - Mostrati i risultati da {3} a {4} di {5}',
	'secimagehint'		=>'Immagine troppo difficile da leggere? Clicka qui per caricarne una nuova.',
	'weaktorrents'		=>'Torrent Deboli (Torrents che ha bisogno di seeders)',
	'isnuked'				=>'<b>Nuked</b> (torrent segnato come nuked)',
	'isrequest'			=>'<b>Richista</b> (torrent richiesto)',
	'nukedetails'		=>'Questo torrent e\' segnato come Nuked. motivo: {1}',
	'year'					=>'Anno',
	'years'				=>'Anni',
	'month'				=>'Mese',
	'months'				=>'Mesi',
	'week'				=>'Settimana',
	'weeks'				=>'Settimane',
	'day'					=>'Giorno',
	'days'					=>'Giorni',
	'hour'					=>'Ora',
	'hours'				=>'Ore',
	'minute'				=>'Minuto',
	'minutes'				=>'Minuti',
	'second'				=>'Secondo',
	'seconds'			=>'Secondi',
	'GMT'					=>'GMT',
	'today'				=>'Oggi',
	'yesterday'			=>'Ieri',
	'noactiveusersonline'	=>'Non ci son stati users attivi negli ultimi 15 minuti.',
	'logout_error'		=>'C\'e\' stato un errore durante il logout. Clicka <a href="logout.php?logouthash={1}" target="_self">QUI</a> per fare il logout.', // Added v3.6
	'click_to_add'		=>'Clicka uno smile da inserire nel tuo messaggio', // Added v3.6
	'smilies_listing'		=>'Lista Smiles', // Added v3.6
	'more_smilies'		=>'Altri Smiles', // Added v3.6 // Updated v3.7
	'loading'				=>'Caricando. Per favore attendere...', // Added v3.6
	'external'				=>'(esterno)', //Added v3.7
	'updateexternal'	=>'Aggiorna torrent esterno', //Added v3.7
	'externalupdated'	=>'Il torrent esterno e\' stato aggiornato...', //Added v3.7
	'recentlyupdated'	=>'Questo torrent e\' stato gia\' aggiornato...', //Added v3.7
	'seclisten'			=>'Avvia l\'audio e scrivi i numeri che senti.', //Added v3.7
	'refresh'				=>'Aggiorna', //Added v3.7
	'noenter'				=>'Sfortunatamente questo bottone e\' stato disabilitato!\n\nPer favore usa il bottone \'Shout\' !', //Added v3.7
	'newmessagebox'	=>'C\'e\' un nuovo messaggio dalla tua ultima visita, clicka OK per leggerlo', // Added v3.8
	'connectablealert' => 'Risulti come NON Connesso su {1} dei tuoi torrents. Per favore leggi nel <a href="{2}">Forum</a> o nelle <a href="{3}">FAQ</a> per trovare soluzione', // Added v3.8
	'advancedbutton'	=>'Vai Avanti', // Added v3.9
	'quickmenu'	=>'Menu\' Veloce', // Added v3.9
	'qinfo1' => 'Guarda Profilo Pubblico', //Added v3.9
	'qinfo2' => 'Invia un PM a {1}', //Added v3.9
	'qinfo3' => 'Trova tutti i Post di {1}', //Added v3.9
	'qinfo4' => 'Trova tutti i Topic di {1}', //Added v3.9
	'qinfo5' => 'Aggiungi {1} agli Amici', //Added v3.9
	'qinfo6' => 'Edita User', //Added v3.9
	'qinfo7' => 'Ammonisci User', //Added v3.9
	'qinfo8' =>'Trova tutti i commenti di {1}', //Added v3.9
	'qinfo9' =>'Trova tutte le Release di {1}', //Added v3.9
	'vkeyword'=>'Per favore usa la Tastiera Virtuale per inserire/cambiare la tua Password/Pincode!', // Added v4.1
	'warningweeks'								=>'{1} week(s).',// Added v4.2
	'warningmessage2'					=>"Sei stato [url=rules.php#warning]Ammonito[/url] per {1} da {2}\n\nMotivo: {3}",// Added v4.2
	'modcommentwarning2'				=>"{1} - Ammonito per {2} da {3}\nReason: {4}\n{5}",// Added v4.2
	'warningsubject'					=>'Sei stato Ammonito!',// Added v4.2
	'modcommentwarningremovedby'		=>"{1} - Ammonizione rimossa da {2}\n{3}",// Added v4.2
	'warningremovedbysubject'			=>'L\'Ammonizione e\' stata rimossa.',// Added v4.2
	'warningremovedbymessage'			=>'L\'Ammonizione e\' stata rimossa da {1}',// Added v4.2
	'gotopage'	=>'Vai alla Pagina',//Added v4.3
	'snotice'		=>'Avviso: ',//Added v4.3
	'times'		=>'volta(e)',//Added v4.3
	'cancel'		=>'Cancella',//Added in v5.0
	'sys_message'=>'Messaggio di Sistema', //Added in v5.1
	'show_results' => 'Mostra Risultati {1} da {2} di {3}',//Added in v5.3
	'showing_results' => 'Mostra Risultati {1} da {2} di {3}',//Added in v5.3
	'first_page'	=>'Prima Pagina',//Added in v5.3
	'last_page'	=>'Ultima Pagina',//Added in v5.3
	'next_page'	=>'Prossima Pagina',//Added in v5.3
	'prev_page'	=>'Pagina Precedente',//Added in v5.3
	'buttonthanks2'=>'Rimuovi il tuo Ringraziamento',//Added in v5.4
	'storrent'=>'Cerca Torrent',//Added in v5.4
	'storrent2'=>'Keyword(s):',//Added in v5.4
	'unregistered'=>'Non sei registrato al nostro portale, per favore <a href="'.$BASEURL.'/signup.php?"><u>registrati</u></a> o esegui il <a href="'.$BASEURL.'/login.php?"><u>login</u></a> per accedere',//Added in v5.6
	'h1' => 'Devi rispondere al post per visualizzare il contenuto nascosto.',//Added in v5.6
	'h2'	=> 'Contenuto Nascosto',//Added in v5.6
	'h3'	=>	'Contenuto Visibile',//Added in v5.6
);
?>