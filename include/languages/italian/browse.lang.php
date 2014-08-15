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
Translation by xam Version: 1.1

*/

if(!defined('IN_TRACKER'))
  die('Hacking attempt!');

// browse.php *** RE-CODED SINCE v3.9 ***
$language['browse'] = array
(
	'bykeyword'	 =>'Keyword(s)',
	'alltypes'		=>'(Tutte le categorie)',
	'tryagain'	=>'Prova un\'altra stringa di ricerca.',
	't_name'		=>'Nome del Torrent',
	't_description'=>'Descrizione del Torrent',
	't_both'	=>'Nome e Descrizione',
	't_uploader' =>'Uploader',
	'in'	 =>'in',
	'tsearch'	 =>'Ricerca Torrent',
	'tcategory' => 'Categorie Tracker',
	'downloadinfo'=> 'Download torrent: {1}',
	'detailsinfo'	=> 'Guarda Dettagli Torrent: {1}',
	'categoryinfo'=> 'Guarda Categorie: {1}',	
	'info3'	 => 'Completato da {1} Utenti',	
	'newtorrent'	 =>'Nuovo torrent',
	'freedownload'	=>'Free Torrent (verranno registrate solo le statistiche in Upload!)',
	'silverdownload' =>'Silver torrent (verra\' registrato solo il 50% delle statistiche in Download!).',
	'requested'	=>'Questo torrent e\' stato rischiesto.',
	'nuked'	=>'Questo torrent e\' stato segnato come Nuked! Motivo: {1}',	
	'download'	=>'Download torrent',
	'viewtorrent'	=>'Guarda dettagli torrent',	
	'viewcomments'	=>'Guarda Commenti',	
	'viewsnatch'	=>'Guarda la lista di chi ha completato',
	'tinfo'		=>'Guarda le info del torrent',
	'edit'	=>'Edita torrent',
	'nuke'	 =>'Nuke torrent',
	'delete'	=>'Cancella torrent',
	'nopreview'	=>'Non c\'e\' nessuna locandina per questo torrent!',
	'sticky'	=>'Torrent Raccomandato',
	'updating'	=>'Statistiche del torrent in aggiornamento...',
	'update'	=>'Torrent esterno! Clicka qui per aggiornare le statistiche del torrent!',
	'updated'	=>'Le statistiche del torrent sono state aggiornate!',
	'show_daily_torrents' => 'Guarda Torrent Giornalieri',
	'show_weekly_torrents' => 'Guarda Torrent Settimanali',
	'show_montly_torrents' => 'Guarda Torrent Mensili',
	'show_dead_torrents' => 'Guarda Torrent Morti',
	'show_recommend_torrents' => 'Guarda Torrent Raccomandati',
	'show_free_torrents' => 'Guarda Torrent Free',
	'show_silver_torrents' => 'Guarda Torrent Silver',
	'show_external_torrents' => 'Guarda Torrent Esterni',
	'sastype'	 =>'Seleziona il tipo di ricerca ',
	'btitle'	=>'Cerca Torrent',
	't_image'	 =>'Clicka per guardare nelle dimensioni originali',
	'warnexternal'				=>'Attenzione!!!\n----------------\nStai per scaricare un Torrent Esterno, questo significa che le statistiche non saranno registrate!\n\nClicka \"OK\" per continuare!',
	'sortby1'=>'Ordina per', // Added v4.0
	'sortby2'=>'Lista file', // Added v4.0
	'sortby3'=>'Commenti', // Added v4.0
	'sortby4'=>'Seeders', // Added v4.0
	'sortby5'=>'Leechers', // Added v4.0
	'sortby6'=>'Dimensioni', // Added v4.0
	'sortby7'=>'Completati', // Added v4.0
	'sortby8'=>'Uploader', // Added v4.0
	'sortby9'=>'Raccommandati', // Added v4.0
	'orderby1'=>'Ordine', // Added v4.0
	'orderby2'=>'Discendente', // Added v4.0
	'orderby3'=>'Ascendente', // Added v4.0
	'sobutton'=>'Mostra Torrents', // Added v4.0
	'serror'=>'Attenzione Errore!\nUno o piu\' termini di ricerca sono piu\' corti della lunghezza minima.\nLa lunghezza minima della stringa di ricerca e\' di 3 caratteri.\n\nRicerca Terminata!', // Added v4.0
	'dupload'=>'Il Seed di questo torrent fornisce doppie statistiche di upload!',//Changed in v4.2
	'legend_browse' =>'
<img src="|link|freedownload.gif" border="0" class="inlineimg" onMouseover="ddrivetip(\'<font color=#347C17>I Torrent Free aggiornano solo le statistiche di Upload.</font>\', 300)"; onMouseout="hideddrivetip()">&nbsp;&nbsp;
<img src="|link|silverdownload.gif" border="0" class="inlineimg" onMouseover="ddrivetip(\'<font color=#347C17>I Torrent Silver aggiornano le statistiche di download del 50%.</font>\', 300)"; onMouseout="hideddrivetip()">&nbsp;&nbsp;
<img src="|link|isnuked.gif" border="0" class="inlineimg" onMouseover="ddrivetip(\'<font color=#347C17>I Torrent Nuked sono torrent che hanno Problemi e verranno presto cancellati.</font>\', 300)"; onMouseout="hideddrivetip()">&nbsp;&nbsp;
<img src="|link|isrequest.gif" border="0" class="inlineimg" onMouseover="ddrivetip(\'<font color=#347C17>I Torrent Richiesti sono quelli che hanno soddisfatto una Richiesta.</font>\', 300)"; onMouseout="hideddrivetip()">&nbsp;&nbsp;
<img src="|link|x2.gif" border="0" class="inlineimg" onMouseover="ddrivetip(\'<font color=#347C17>I Torrent con Doppio Upload per chi fa da Seed e da Reseed.</font>\', 300)"; onMouseout="hideddrivetip()">&nbsp;&nbsp;
<img src="|link|external.gif" height="12" width="12" border="0" class="inlineimg" onMouseover="ddrivetip(\'<font color=#347C17>I Torrent Esterni non forniscono statistiche per il sito.</font>\', 300)"; onMouseout="hideddrivetip()">&nbsp;&nbsp;
<img src="|link|sticky.gif" height="12" width="12" border="0" class="inlineimg" onMouseover="ddrivetip(\'<font color=#347C17>I Torrent Raccomandati sono quelli consigliati dallo Staff.</font>\', 300)"; onMouseout="hideddrivetip()">&nbsp;&nbsp;
<img src="|link|down1.gif" height="12" width="12" border="0" class="inlineimg" onMouseover="ddrivetip(\'<font color=#347C17>>L\'icona per il Download del Torrent e\' disponibile per quegli utenti che hanno la possibilita\' di farne il download direttamente, ma non dimenticate comunque di ringraziare i Releaser.</font>\', 300)"; onMouseout="hideddrivetip()">&nbsp;&nbsp;
', // Added v4.2
	'b_info'	=>'<b>Legenda: </b>',// Added v4.2
	'f_options'=>'<b>Opzioni Filtro</b>',// Added v4.2
	'show_double_upload_torrents'=>'Mostra i Torrent con doppio upload',// Added v4.2
	'type'=>'Tipo',//Added in v5.0
	'speed'=>'Velocita\'',//Added in v5.0
	'external'=>'(Esterno)',//Added in v5.0
	'notraffic'=>'(No Traffic)',//Added in v5.0
	't_genre'=>'IMDB Genre',//Added in v5.0
	'quickedit'=>'Edit Veloce Nome Torrent', //Added in v5.1
	'f_leech_h' => 'Giorni di Free Leech',//Added in v5.1
	'f_leech'	=>	 'Tutti i torrents sono Free tra {1} - {2}. (Per favore non scaricare facendo il prendi e scappa, resta in seed!)', //Added in v5.1
	's_leech_h' => 'Giorni di Silver Leech',//Added in v5.1
	's_leech'	=>	 'Tutti i torrents sono Silver tra {1} - {2}. (Per favore non scaricare facendo il prendi e scappa, resta in seed!)', //Added in v5.1
	'd_leech_h' => 'Giorni di Doppio Upload',//Added in v5.1
	'd_leech'	=>	 'Tutti i torrents hanno Doppio Upload (x2) tra {1} - {2}. (Per favore non scaricare facendo il prendi e scappa, resta in seed!)', //Added in v5.1
	'scene3'=>'<b>Offerte:</b> {1}',//Added in v5.2
	'scene4'=>'Mostra le tue offerte',//Added in v5.2
	'show_latest'=>'Mostra gli ultimi torrents',//Added in v5.4
);
?>