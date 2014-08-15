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
Translation by xam Version: 1.1

*/

if(!defined('IN_TRACKER'))
  die('Hacking attempt!');

// details.php
$language['details'] = array 
(
	'insertcomment'				=>'Inserisci Commento',
	'report'					=>'Reporta',
	'bookmark'					=>'Preferiti',
	'removebookmark'			=>'Rimuovi Preferiti',
	'viewsnatches'				=>'Guarda Scaricati',
	'editorrent'				=>'Modifica questo Torrent',
	'unknown'					=>'Sconosciuto',
	'userip'					=>'UTENTE/IP',
	'conn'						=>'CONN.',
	'up'						=>'UP',
	'urate'						=>'U.RATE',
	'down'						=>'DOWN',
	'drate'						=>'D.RATE',
	'ratio'						=>'RATIO',
	'done'						=>'FATTO',
	'since'						=>'DAL',
	'idle'						=>'PAUSA',
	'client'					=>'CLIENT',
	'yes'						=>'Si\'',
	'no'						=>'No',
	'inf'						=>'Inf.',
	'detailsfor'				=>'Dettagli per il torrent " {1} "',
	'uploaded'					=>'Uppato con Successo!',
	'uploadednote'				=>'<p> Puoi iniziare a seedare ora. <b>NOTA :</b> questo torrent non sara\' visibile fino a che non sarai in Seed!</p>',
	'edited'					=>'Modificato con successo!',
	'goback'					=>'<p><b>Torna <a href="{1}">indietro</a>.</b></p>',
	'singleresult'				=>'<div class=success>La tua ricerca per " {1} " ha dato solo un risultato:</div>',
	'bookmarked'				=>'<div class=success>Preferito aggiunto!</div>',
	'bookmarked2'				=>'<div class=error>Non hai bisogno di due preferiti uguali vero?</div>',
	'bookmarked3'				=>'<div class=error>Preferito cancellato!</div>',	
	'download'					=>'Download',
	'hitrunwarning'				=>'<font color="#ff0532"><p><b><u>I tuoi permessi di download sono stati rimossi!! Scarica un vecchio torrent per alzare il tuo ratio</font></u></b></p><p>Il tuo ratio e\' <b><font color="#ff0532">{1}</b></font> - e cio\' significa che hai uppato solo <b><font color="#ff0532">{2}</b></font> del totale scaricato.<p>E\' importante mantenere un buon ratio, per far andare i torrents piu\' veloci a tutti.</p><p><font color="#ff0532"><b>Consiglio: </b></font>Puoi alzare il tuo ratio lasciando i torrents in seeding dopo averli scaricati.<p>Devi mantenere un ratio minimo di <b><font color="#ff0532">{3}</b></font> o i tuoi permessi di download saranno tolti.</td></tr>',
	'hitrunwarning2'			=>'<font color="#ff0532"><p><b><u>FAI ATTENZIONE AL TUO RATIO!!</font></u></b></p><p>Il tuo ratio e\' <b><font color="#ff0532">{1}</b></font> - e cio\' significa che hai uppato solo <b><font color="#ff0532">{2}</b></font> di quello che hai scaricato.<p>E\' importante mantenere un buon ratio, per far andare i torrents piu\' veloci a tutti.</p><p><font color="#ff0532"><b>Consiglio: </b></font>Puoi alzare il tuo ratio lasciando i torrents in seeding dopo averli scaricati.<p>Devi mantenere un ratio minimo di <b><font color="#ff0532">{3}</b></font> o i tuoi permessi di download verranno tolti.<p><a class="index" href="download.php?id={4}"><font color=#ff0532>> Clicca qui per scaricare il torrent <</a></font></p></td></tr>',
	'nodlpermission'			=>'Non hai il permesso di scaricare.',
	'infohash'					=>'Hash Info',
	'description'				=>'Descrizione',
	'viewnfo'					=>'Visualizza NFO',
	'visible'					=>'Visibile',
	'visible2'					=>'NO (morto)',
	'banned'					=>'Bannato',
	'sticky'					=>'Raccomandato',
	'type'						=>'Tipo',
	'type2'						=>'(non selezionato)',
	'lastactivity'				=>'Ultima attivita\'',
	'activity'					=>'Attivita\'',
	'size'						=>'Dimensione',
	'bytes'						=>'bytes',
	'noneyet'					=>'non ancora (ha bisogno di almeno {1} voti e ha ',
	'none'						=>'nessuno',
	'only'						=>'solo',
	'novotes'					=>'Nessun voto ancora',
	'invalid'					=>'non valido?',
	'added'						=>'Aggiunto',
	'views'						=>'Visto Volte',
	'hits'						=>'Hits',
	'snatched'					=>'Completato',
	'snatched2'					=>'volte',
	'snatched3'					=>'<--- Clicca qui per vedere chi ha completato',
	'progress'					=>'Progresso',
	'uppedby'					=>'Uploader',
	'numfiles'					=>'Numero file<br /><a href="details.php?id={1}&filelist=1{2}#filelist" class="sublink">[guarda la lista]</a>',
	'numfiles2'					=>'{1} file(s)',
	'numfiles3'					=>'Numero file',
	'path'						=>'Percorso',
	'filelist'					=>'Lista File</a><br /><a href="details.php?id={1}{2}" class="sublink">[Nascondi Lista]</a>',
	'askreseed'					=>'Reseed',
	'askreseed2'				=>'Clicka <a href=takereseed.php?reseedid={1}><b>QUI</b></a> per chiedere per un reseed!',
	'peers'						=>'Peers<br /><a href="details.php?id={1}&dllist=1{2}#seeders" class="sublink">[leggi lista]</a>',
	'peers2'					=>'{1} seeder(s), {2} leecher(s) = {3} peer(s) totali',
	'peersb'					=>'Peers',
	'peers3'					=>'{1} seeder(s), {2} leecher(s) = {3} peer(s) total<br /><font color=red>Spiacenti, permesso negato!</font>',
	'seeders'					=>'Seeders</a><br /><a href="details.php?id={1}{2}" class="sublink">[Nascondi Lista]</a>',
	'seeders2'					=>'Seeder(s)',
	'leechers'					=>'Leechers</a><br /><a href="details.php?id={1}{2}" class="sublink">[Nascondi Lista]</a>',
	'leechers2'					=>'Leecher(s)',
	'nothanksyet'				=>'Nessun Grazie aggiunto ancora!',
	'thanksby'					=>'Grazie da:',
	'torrentinfo'					=>'Info Torrent',
	'commentsfor'				=>'Commenti per il torrent "{1}"',
	'nocommentsyet'			=>'Non ci sono commenti ancora. Commenta per primo!',
	'quickcomment'			=>'<b>Commenta Veloce</b>',
	't_link'						=>'IMDB/Web Link', // Changed v3.6
	't_image'						=>'Immagine Torrent',
	'lastupdate'					=>'Ultimo Aggiornamento', // Added v3.7
	'warnexternal'				=>'Attenzione!!!\n----------------\nStai per scaricare un torrent ESTERNO cio\' significa che non verranno contate le statistiche di Upload e di Download!\n\nClicka \"OK\" per continuare il download!', // Added v3.9
	'close'=>'Chiudi Commenti',//Added v4.1
	'open'=>'Apri Commenti',//Added v4.1
	'dltorrent'=>'Download Torrent',//Added in v5.0
	'comments'=>'Commenti',//Added in v5.0
	'na'=>'N/A',//Added in v5.0
	'scene3'=>'Pre-Time',//Added in v5.2
	'newrating'=>'Grazie per aver votato.. Hai votato: ',//Added in v5.3
	'alreadyvotes'=>'Hai gia\' votato!',//Added in v5.3
	'ratedetails'=>'Punteggio: {1} con {2} voto(i).',//Added in v5.3
	'bigfile'	=>	 '<b>Il numero di file ({1}) in questo torrent e\' troppo elevato per mostrare la lista file!</b>',//Added in v5.3
	);
?>