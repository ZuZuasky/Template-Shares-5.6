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
Translation by xam Version: 1.4

*/

if(!defined('IN_TRACKER'))
  die('Hacking attempt!');

// upload.php and takeupload.php
$language['upload'] = array 
(
	'anonymous'		=>'Anonimo',
	'nfoerror1'			=>'0-byte NFO!',
	'nfoerror2'			=>'L\'NFO e\' troppo grande! Massimo 65,535 bytes!',
	'nfoerror3'			=>'Upload NFO fallito!',
	'selectcategory'	=>'Devi scegliere una categoria per il torrent!',
	'dicterror1'			=>'Non nel dizionario!',
	'dicterror2'			=>'Nel dizionario manca una chiave!',
	'dicterror3'			=>'Entrata non valida nel dizionario!',
	'dicterror4'			=>'Tipo di entrata non valida nel dizionario!',
	'dicterror5'			=>'Grandezza file mancante!',
	'dicterror6'			=>'Nessun file!',	
	'dicterror7'			=>'Errore nel nome del file!',
	'fileerror1'			=>'Nome File NON valido!',
	'fileerror2'			=>'Nome File NON valido (non e\' .torrent)!',
	'uploaderror1'		=>'Impossibile uppare il torrent!',
	'uploaderror2'		=>'File Vuoto!',
	'uploaderror3'		=>'Cos\'hai uppato?',
	'sqlerror1'			=>'Torrent gia\' uppato!',
	'sqlerror2'			=>'ERRORE Mysql: ',
	'invalidannounceurl'=>'URL Announce NON valida, dev\'essere: ',
	'invalidpieces'		=>'Pezzi NON validi!',
	'dhterror'				=>'I Torrents MultiTracker non sono accettati!',
	'writelog1'			=>'Il Torrent {1} ({2}) e\' stato uppato da un utente Anonimo.',
	'writelog2'			=>'Il Torrent {2} ({2}) e\' stato uppato da {3}.',
	'offermessage'		=>'L\'offerta per la quale hai votato: "{1}" e\' stata uppata da {2}.
	
	Puoi scaricare il torrent cliccando [url={3}/details.php?id={4}]QUI[/url]',
	'offersubject'		=>'L\'Offerta {1} e\' stata appena uppata!',
	'emailbody'			=>'Salve,

Un nuovo torrent e\' stato uppato.

Nome: {1}
Grandezza: {2}
Categoria: {3}
Uppato da: {4}

Descrizione
-------------------------------------------------------------------------------
{5}
-------------------------------------------------------------------------------

Puoi usare il link qui sotto per scaricare il torrent (potresti aver bisogno di loggarti).

{6}/details.php?id={7}

Cordialmente,
lo Staff di {8}.',
	'emailsubject'		=>'{1} Nuovo Torrent - {2}',
	'mailerror'			=>'Il torrent e\' stato uppato. NON AGGIORNARE LA PAGINA!	
								C\'e\' stato pero\' un problema nell\'invio delle email. Contatta un amministratore per segnalare il problema!',
	'head'					=>'Uppa un Torrent',
	'info'					=>'L\'announce URL e\': <b>{1}</b><br>',
	'alert1'				=>'<b>NOTA:</b> Per seeddare questo torrent devi riscaricarlo, dopo averlo uppato sul tracker, e metterlo in seed!<br>',
	'alert2'				=>'<b>ATTENZIONE</b>: La directory dei Torrent non puo\' esser aggiornata. Per favore contatta un amministratore a riguardo!<br>',
	'alert3'				=>'<b>ATTENZIONE</b>: La grandezza Max. del Torrent non e\' stata settata. Per favore contatta un amministratore a riguardo!<br>',
	'field1'				=>'File Torrent',			
	'field2'				=>'Nome Torrent',
	'field3'				=>'(Preso dal nome del file se non immesso. <b>Usa nomi descrittivi.</b>)',
	'field4'				=>'NFO File',
	'field5'				=>'(<b>Opzionale.</b> Puo\' essere visto solo dai Power Users. </b> inserisci solo la fine del file <b>.nfo</b>)',
	'field6'				=>'Descrizione:', // Changed in 3.7
	'field7'				=>'(scegline una)',
	'field8'				=>'Tipo',
	'field9'				=>'Le Tue Offerte',
	'field10'				=>'Offerta',
	'field11'				=>' Se stai uppando un\'offerta, seleziona questa opzione.',
	'field12'				=>'Non mostrare il mio nome nella pagina dei torrent.',
	'field13'				=>'In Rilievo',
	'field14'				=>'Metti in Rilievo questo torrent.',
	'field15'				=>'Offensivo',
	'field16'				=>'Seleziona questa opzione se il tuo torrent potrebbe essere offensivo per i minori.',
	'field17'				=>'Ho letto le regole prima di uppare.',
	'field18'				=>'Uppa',
	'field19'				=>'NFO Ripper',
	'field20'				=>'Seleziona questo per Rippare l\'NFO.',	
	'uploaderform'		=>'Per favore clicka <a href=uploaderform.php>QUI</a> per riempire il form di upload.',
	'mindesclimit'		=>'La descrizione e\' troppo piccola. Minimo 10 caratteri!',
	'silver'				=>'Silver Download',
	'silver2'				=>'50% delle statistiche di download saranno registrate!',
	'field21'				=>'<input type="radio" name="uploadtype" onclick="toggleuploadmode(1)" checked>Immagine Upload (url)<br>
								<input type="radio" name="uploadtype" onclick="toggleuploadmode(0)">Immagine Upload (file)',
	'field22'				=>'IMDB/Web Link',
	'field23'				=>'Incolla l\'url all\'immagine',
	'invalid_url'			=>'Upload immagine fallito!',
	'invalid_url_empty'=>'L\'URL non puo\' essere vuota!',
	'invalid_url_link'	=>'L\'URL deve iniziare con: http://',
	'invalid_url_imdb'	=>'Url di IMDB invalida deve iniziare con: http://www.imdb.com/title/',
	'curl_error'			=>'URL Error!',
	'remote_failed'		=>'Estensione immagine non valida!',
	'invalid_image'		=>'Estensione immagine non valida! Tipi concessi: {1}',
	'shoutbOT'          =>'[b]Nuovo Torrent[/b] [url={1}]{2}[/url] e\' stato uppato da {3}.', // Updated in v4.2
	'fileerror3'			=>'Estensione file non valida (non e\' .nfo)!',  // Added v3.6
	'showprogress'		=>'Il torrent e\' in upload. Questo puo\' durare qualche minuto.<br>NON CHIUDERE QUESTA FINESTRA!!', // Added v3.6
	'atypes'				=>'<b>Tipi file accettati: Jpg, Gif, Png</b>',  // Added v3.6
	'freesilvererror'		=>'Non puoi selezionare entrambi i tipi di bonus.', // Added v3.6
	'nforippempty'		=>'Non dimenticare di vedere gli NFO.', // Added v3.6
	'field0'				=>'URL del tracker', //Added v3.7
	'trackerurlinfo'		=>'Puoi anche uppare torrent di tracker pubblici!', //Added v3.7
	'externalerror'		=>'Non hai i permessi necessari per uppare un torrent esterno!', // Addded v3.9
	'sbum'				=>'Per favore usa questo form per verificare che il torrent che stai per postare, non sia gia stato pubblicato! I doppi torrent saranno bannati automaticamente dal sistema!', // Added v4.2
	'u_step'				=>'Upload Step: ', //Added v4.3
	's_results'			=>'Risultati Ricerca', //Added v4.3
	's_results_title'		=>'Sei sicuro di aver cercato prima di uppare il tuo torrent? Sono stati trovati i seguenti torrent che sembrano esser simili al tuo; per favore controlla prima di uppare un nuovo torrent.<br><br>
Se sei sicuro che il tuo torrent sia corretto, puoi continuare con l\'upload.', // Added 4.3
	'n_step'				=>'Prossimo Passo',//Added v4.3
	's_button1'			=>'NO, Non Voglio Continuare il mio Upload!',//Added v4.3
	's_button2'			=>'SI\', Voglio Continuare il mio Upload!',//Added v4.3
	'finfoh'			=>'Aggiungi il file info',//Added v4.3
	'finfo'				=>'File Info',//Added in v5.0
	'video'			=>'Video:',//Added in v5.0
	'audio'			=>'Audio:',//Added in v5.0
	'codec'			=>'Codec',//Added in v5.0
	'bitrate'			=>'Bitrate',//Added in v5.0
	'resulation'		=>'Risoluzione',//Added in v5.0
	'length'			=>'Lunghezza',//Added in v5.0
	'quality'			=>'Qualita\'',//Added in v5.0
	'language'		=>'Linguaggio',//Added in v5.0
	'frequency'=>'Frequenza',//Added in v5.0
	'enote'=>'Se non conosci questa informazione, usa <a href="http://www.headbands.com/gspot/" target="_blank">GSpot</a>',//Added in v5.0
	'fierror'=>'Il tuo torrent e stato inserito,comunque puoi inserire un nuovo file Info perche e stato inserito da qualcun altro, perfavore modifica i dettagli del torrent per cambiare il file info.',//Added in v5.0
	'scene'=>'Offerte',//Added in v5.2
	'scene2'=>'Controlla che il tuo torrent sia tra le offerte.',//Added in v5.2
);
?>