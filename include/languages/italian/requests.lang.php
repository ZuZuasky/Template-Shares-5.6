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
Translation by xam Version: 0.6

*/

if(!defined('IN_TRACKER'))
  die('Hacking attempt!');

// viewrequests.php, viewoffers.php and offcomment.php
$language['requests'] = array 
(
	
	'offline'						=>'Spiacente, le sezioni Richieste/Offerte sono momentaneamente chiuse',
	'noofferid'						=>'Nessuna Offerta con questo ID!',
	'noreqid'						=>'Nessuna Richiesta con questo ID!',
	'addtitle'						=>'Aggiungi un commento a: {1}',
	'addtitle1'						=>'Aggiungi un commento',
	'addtitle2'						=>'Commenti all\'Offerta: {1}',
	'addtitle3'						=>'Commenti alla Richiesta: {1}',
	'addtitle4'						=>'Aggiungi un commento alla Richiesta',
	'addtitle5'						=>'Modifica Commento',
	'addtitle6'						=>'Aggiungi Richiesta',
	'edittitle'						=>'Modifica Commento',
	'deletetitle'					=>'Elimina Commento',
	'sure'							=>'Stai per eliminare questo commento. Clicka <a href=?action=delete&cid={1}&sure=1{2}>QUI</a>, se sei sicuro.',
	'goback'						=>'Indietro',
	'rhead'							=>'Pagina Richieste',
	'reqrules'						=>'Regole Richeste',
	'reqrulesinfo1'					=>'Per fare richieste devi avere un ratio minimo di <b>0.8</b> e devi avere uppato almeno <b>10 GB</b>.<br> La Richiesta inoltre ti costera\'  <b><a class=altlink href=mybonus.php>5 Punti Karma</a></b>....<br><br>',
	'permissionerror'				=>'<font color="red">Tu <b>non</b> hai abbastanza requisiti per fare una Richiesta.</font>',
	'searchbeforereq'				=>'Per favore, cerca tra i torrents prima di fare una Richiesta!',
	'in'							=>'in',
	'alltypes'						=>'(tutti tipi)',
	'incdead'						=>'includi torrents morti',
	'addreqinfo'					=>'Le richieste sono per gli utenti con un buon ratio e che hanno uppato almeno 10GB... Condividi e riceverai!',
	'field1'						=>'Titolo:',
	'field2'						=>'(seleziona una categoria)',
	'field3'						=>'Immagine:',
	'field4'						=>'(Link diretto all\'immagine)',
	'field5'						=>'Descrizione',
	'field6'						=>'Categoria',
	'field7'						=>'Richiesta',
	'field8'						=>'Aggiunto',
	'field9'						=>'Richiesto da',
	'field10'						=>'Mostra TUTTI',
	'field11'						=>'Modifica Richiesta',
	'field12'						=>'Elimina Richiesta',
	'field13'						=>'Resetta Richiesta',
	'field14'						=>'Vota per questa Richiesta:',
	'field15'						=>'Vota',
	'field16'						=>'Riporta Richiesta',
	'field17'						=>'<b>Torrent ID:</b>',
	'field18'						=>'Completa Richiesta',
	'field19'						=>'Questa Richiesta e\' stata completata:',
	'error1'						=>'Devi inserire un titolo!',
	'error2'						=>'Devi selezionare una categoria dove mettere la Richiesta!',
	'error3'						=>'Devi inserire una descrizione!',
	'error4'						=>'L\'immagine DEVE essere in jpg, gif o png.',
	'error5'						=>'Nessuna Richiesta con questo ID!',
	'error6'						=>'Qualcosa e\' sbagliato con questo url.<br> L\'URL <u>deve</u> essere: <b>{1}/details.php?id=(torrent id)</b>',
	'error7'						=>'Nessuna Offerta con questo ID!',
	'rhead2'						=>'Dettagli Richiesta: ',
	'by'							=>'da:',
	'ratio'							=>'ratio',
	'at'							=>'a',
	'gmt'							=>'GMT',
	'edit'							=>'Modifica',
	'delete'						=>'Elimina',
	'report'						=>'Reporta',
	'pm'							=>'PM',
	'profile'						=>'Profilo',
	'editedby'						=>'Modificato da',
	'addcomment'					=>'Aggiungi Commento',
	'rhead3'						=>'Modifica Richiesta',
	'staffonly'						=>'Solo Staff',
	'filled'						=>'Completata:',
	'filledbyid'					=>'Completata da ID:',
	'filledurl'						=>'Torrent URL:',
	'rhead4'						=>'Richiesta Completata',
	'filledmsg'						=>'La tua Richiesta, [b]{1}[/b] e\' stata completata da [b]{2}[/b].
	
	Puoi scaricare la tua Richiesta da [b][url={3}]{3}[/url][/b].
	Non scordare di ringraziare l\'uploader.
	
	Se per qualche ragione non e\' quello che hai richiesto, resetta la tua Richiesta in modo che qualcun\'altro possa completarla da [b][url={4}/viewrequests.php?do=reset_request&rid={5}]questo[/url][/b] link.
	
	[b]NON[/b] seguire questo link a meno che tu non sia sicuro che non e\' cio\' che avevi richiesto.',
	'filledmsgsubject'				=>'Richiesta Completata!',
	'filledvotemsg'					=>'La Richiesta alla quale hai votato [b]{1}[/b] e\' stata completata da [b]{2}[/b].
	
	Puoi scaricarla da [b][url={3}]{3}[/url][/b].
	
	Non dimenticare di ringraziare l\'uploader.',
	'filledvotesubject'				=>'La Richiesta {1} e\' stata appena uppata!',
	'voters'						=>'Votanti',	
	'username'						=>'Username',
	'ul'							=>'Uppati',
	'dl'							=>'Scaricati',
	'sratio'						=>'Share Ratio',
	'sanity'						=>'Stai per eliminare questa Richiesta. Clicka <a href={1}?id={2}&del_req=1&sure=1>QUI</a>, se sei sicuro.',
	'rtitle'						=>'Titolo Richiesta',
	'makereq'						=>'Crea una Richiesta',
	'viewreq'						=>'Vedi le mie Richieste',
	'hidefilled'					=>'Nascondi Completate',
	'onlyfilled'					=>'Solo Completate',
	'lookoffer'						=>'Guarda nella <a href=viewoffers.php>Sezione Offerte</a> prima di fare una Richiesta!',
	'viewselected'					=>'vedi solo le selezionate',
	'searchreq'						=>'Cerca Richieste',
	'filled?'						=>'Completata?',
	'filledby'						=>'Completata da',
	'votes'							=>'Voti',
	'selectall'						=>'seleziona tutti',
	'unselectall'					=>'deseleziona tutti',
	'deleteselected'				=>'elimina selezionati',
	'nothingfound'					=>'Trovato Niente!',
	'orphaned'						=>'(orfana)',
	'ohead'							=>'Sezione Offerte',
	'searchbeforeoffer'				=>'Cerca tra i torrent prima di fare un\'Offerta!',
	'oinfo'							=>'Le offerte sono aperte a tutti gli utenti!',
	'offer'							=>'Offerta',
	'offeredby'						=>'Offerto da',
	'dupeoffer'						=>'Questa Offerta esiste gia\'!',
	'otitle'						=>'Dettagli Offerta:',
	'status'						=>'Status:',
	'pending'						=>'In Attesa',
	'denied'						=>'Non Permesso',
	'allowed'						=>'Permesso',
	'editoffer'						=>'Modifica Offerta',
	'deleteoffer'					=>'Elimina Offerta',
	'allow'							=>'Permetti',
	'votesdecide'					=>'Decisione ai Voti',
	'for'							=>'Per',
	'against'						=>'Contro',
	'reportoffer'					=>'Reporta Offerta',
	'offerallowed'					=>'Offerta Accettata:',
	'offerallowed2'					=>'Questa Offerta e\' stata accettata! Uppala il prima possibile.',
	'offerallowed3'					=>'Se hai votato per questa Offerta, verrai informato via MP quando sara\' uppata!',
	'offermsg'						=>'{1} ti ha permesso di uppare [b][url={2}/viewoffers.php?id={3}&off_details=1]{4}[/url][/b]. 
	
	Troverai una nuova opzione nella pagina di upload.
	
	Grazie.
	',
	'offersubject'					=>'La tua Offerta e\' stata accettata!',
	'nooffervotes'					=>'Nessun voto ancora...',
	'offermsg2'						=>'La tua Offerta e\' stata accettata. 
	
	Hai il permesso di uppare [b][url={1}/viewoffers.php?id={2}&off_details=1]{3}[/url][/b].
	
	Troverai una nuova opzione nella pagina di upload.
	
	Grazie.',
	'offermsg3'						=>'La tua Offerta non e\' stata accettata.
	
	Non hai il permesso di uppare [b][url={1}/viewoffers.php?id={2}&off_details=1]{3}[/url][/b].
	
	La tua Offerta verra\' eliminata.
	
	Grazie.',
	'offersubject2'					=>'La tua Offerta {1} e\' stata votata!',
	'offervotes'					=>'Risultato Voti: ',
	'sanityoffer'					=>'Stai per eliminare questa Offerta. Clicka <a class=altlink href=viewoffers.php?id={1}&del_offer=1&sure=1>QUI</a>, se sei sicuro.',
	'addoffer'						=>'Aggiungi Offerta',
	'viewreq'						=>'Mostra Richieste',
	'searchoffer'					=>'Cerca Offerte:',
	'comm'							=>'Comm.',
	'addedby'						=>'Aggiunta da',
	'shoutbOT'          =>'C\'e\' una nuova Richiesta: {1} da {2}.',//Added v4.1
	'already_voted'	=>'Sembra che hai gia\' votato per questa Richiesta, puoi votare una sola volta!', //Added v4.3
	'not_voted_yet'	=>'Non ci sono voti per questa Richiesta.', //Added v4.3
	'f_image_not_filled'=>'La Richiesta NON e\' stata completata.',//Added v4.3
	'f_image_filled'=>'La Richiesta e\' stata completata.',//Added v4.3
	'add_vote'=>'Aggiungi Voto',//Added v4.3
	'remove_vote'=>'Rimuovi Voto',//Added v4.3
	'are_you_sure'=>'Sei sicuro di voler cancellare questa Richiesta?',//Added v4.3
	'can_not_add'	=>'Hai gia\' compilato una Richiesta, puoi fare una sola Richiesta alla volta.',//Added v4.3
	'no_perm'=>'Spiacente, non hai il permesso per aggiungere una Richiesta!',//Added v4.3
	'action'=>'Azione(i)',//Added v4.3
	'return'=>'Ritorna alle Richieste',//Added v4.3
	'view_details'=>'Guarda i dettagli del torrent',//Added v4.3
	'words'=>'Cerca: ',//Added v4.3
	'searcherror'	=>'Spiacenti, ma non ci sono risultati per la tua ricerca. Per favore riprova con un\'altra ricerca.',
	'searcherror2'	=>'Non hai inserito nessune termine di ricerca. Devi inserire qualche termine di ricerca. (Min. 3 caratteri)',
	'nothingfound'=>'Non ci sono richieste da mostrare!',
);

?>