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
Translation by xam Version: 0.9

*/

if(!defined('IN_TRACKER'))
  die('Hacking attempt!');

// TSF FORUMS (all files)
$language['tsf_forums'] = array 
(
	'forum'			=>'Forum',
	'threads'			=>'Discussioni',
	'posts'			=>'Posts',
	'lastpost'			=>'Ultimo Post',
	'stats'			=>'<b>Statistiche Board</b>',
	'stats_info'		=>'I nostri utenti hanno fatto un totale di <b>{1}</b> posts in <b>{2}</b> discussioni.<br>
							Abbiamo <b>{3}</b> utenti registrati.<br>
							Benvenuto al nostro ultimo utente registrato, <b>{4}</b>',
	'activeusers'	=>'<b>{1}</b> utenti attivi negli ultimi <b>{2}</b> minuti:<br>',
	'by'				=>'di',
	'invalidfid'		=>'Il forum specificato non esiste.',
	'invalid_tid'		=>'La discussione specificata non esiste.',
	'invalid_post'	=>'Il post specificato non esiste.',
	'noforumsyet'	=>'Non ci sono ancora forum registrati!',
	'lastpost_never'=>'Mai',
	'guest'			=>'Visitatore',
	'whosonline'		=>'<b>Chi e\' Online</b>',
	'new_posts'		=>'Il Forum contiene nuovi posts',
	'no_new_posts'=>'Il Forum non contiene nuovi posts',
	'forum_locked'	=>'Il Forum e\' chiuso',
	't_new_posts'	=>'Nuovi posts',
	't_no_new_posts'=>'Nessun nuovo posts',
	'thread_locked'=>'Discussione Chiusa',
	'new_thread'	=>'Crea Discussione',
	'mark_read'		=>'Segna questo forum come letto',
	'thread'			=>'Titolo Discussione',
	'author'			=>'Autore',
	'replies'			=>'Risposte',
	'views'			=>'Visite',
	'stickythread'	=>'Discussione Importante!',
	'status2'			=>'Status',	//Changed name in v5.0
	'pages'			=>'Pagine: ',
	'multithread'	=>'Discussione Multi-Pagina',
	'new_thread'	=>'Nuova Discussione',
	'new_reply'		=>'Rispondi',
	'post_edited'	=>'Grazie, il post e\' stato modificato.',
	'message'		=>'Messaggio',
	'new_thread_in'=>'Nuova discussione in {1}',
	'mod_options'	=>'Opzioni Moderatore:',
	'mod_options_c'=>'<b>Chiudi Discussione:</b> previeni ulteriori posts.',
	'mod_options_s'=>'<b>Discussione in Rilievo:</b> metti discussione in rilievo.',
	'mod_options_cc'=>'Apri / Chiudi Discussione',
	'mod_options_ss'=>'In Rilievo / Non in Rilievo',
	'mod_options_m'=>'Sposta Discussione',
	'mod_options_dd'=>'Elimina Discussione',
	'new_thread_head'=>'Crea una nuova Discussione',
	'new_reply_head'=>'Rispondi',
	'new_reply_head2'=>'Rispondi alla Discussione: ',
	'cant_post'	=>'Non puoi postare in questo forum.',
	'too_short'	=>'Il messaggio o il titolo e\' troppo corto!',
	'thread_created'=>'La nuova discussione e\' stata creata.',
	'no_thread'		=>'Non ci sono discussioni da mostrare.',
	'editedby'		=>'<p class=\"smalltext\">Questo post e\' stato modificato per l\'ultima volta il: {1} {2} da {3}</p>',
	'reply_post'		=>'Rispondi',
	'quote_post'	=>'Quota',
	'report_post'	=>'Segnala Abuso',
	'edit_post'		=>'Modifica',
	'edit_this_post'=>'Modifica Post',
	'a_post'			=>'un post',
	'delete_post'	=>'Elimina',
	'pm_post'		=>'Invia PM',
	'profile_post'	=>'Profilo',
	'redirect_last_post'=>'Ti stiamo reindirizzando all\'ultimo post...',
	'post'				=>'Post: ',
	're'					=>'R: ',
	'jump_text'		=>'Salta al Forum: ',
	'go_button'		=>'Vai',
	'usergroup'		=>'Gruppo: ',
	'jdate'			=>'Registrato il: ',
	'status'			=>'Stato: ',
	'totalposts'		=>'Posts :',
	'user_offline'	=>'<font color="red">Offline</font>',
	'user_online'	=>'<font color="green">Online</font>',
	'post_done'		=>'La tua risposta e\' stata salvata...',
	'thread_closed'	 =>'Spiacente, questa discussione e\' stata chiusa!',
	'yes'				=>'Si\', sono sicuro!',
	'no'				=>'No, torna indietro!',
	'cancel'			=>'Annulla',
	'mod_del_thread'=>'Elimina Discussione: {1}',
	'mod_del_thread_2'=>'Sei sicuro di eliminare questa discussione?<br> Una volta eliminata non potra\' essere ripristinata!',
	'mod_del_post'=>'Elimina Post: {1}',
	'mod_del_post_2'=>'Sei sicuro di eliminare questo post?<br> Una volta eliminato non potra\' essere ripristinato!',
	'mod_move'		=>'Select New Forum: ',
	'warningmsg'=>'<a href="'.$BASUERL.'/admin/settings.php?action=forumsettings">Il Forum e\' Attualmente Chiuso.</a>',
	'search_results'	=>'Risultati Ricerca: ',
	'search'			=>'Cerca',
	'title'				=>'Cerca nei Forums',
	'title1'			=>'Cerca per KeyWord',
	'title2'			=>'Cerca per User Name',
	'title3'			=>'Opzioni Ricerca',
	'option1'			=>'Keyword(s):',
	'option2'			=>'Cerca nell\'intero post',
	'option3'			=>'Cerca solo nei titoli',
	'option4'			=>'User Name:',
	'option5'			=>'Trova Posts per Utente',
	'option6'			=>'Trova discussioni create dall\' Utente',
	'option7'			=>'Nome Esatto',
	'option8'			=>'Cerca nei Forums',
	'button_1'		=>'Cerca Ora!',
	'button_2'		=>'Resetta i campi',
	'select1'			=>'Cerca in tutti i Forums aperti',
	'searcherror'	=>'Spiacente, nessun risultato trovato.<br> Prova con altri termini.',
	'searcherror2'	=>'Non hai inserito nessun termine per la ricerca.',
	'searcherror3'	=>'Uno o piu\' dei tuoi termini erano inferiori alla lunghezza minima consentita. Il minimo numero di caratteri e\' {1} ',
	'searcherror4'	=>'Errore. Torna indietro e riprova.',
	'searchresults'	=>'Grazie, la tua ricerca e\' stata inviata, ora verrano visualizzati i risultati.',
	'markforumread'=>'Il forum selezionato e\' segnato come "letto".',
	'markforumsread'=>'Il forum selezionato e\' segnato come "letto".',
	'markallread'	=>'Segna tutti i forums come "letti"',
	'country'			=>'Nazione: ',
	'tooltip'			=>'<strong>Ultima Visita:</strong> {1}<br><strong>Download:</strong> {2}<br><strong>Upload:</strong> {3}<br><strong>Ratio:</strong> {4}<br>',//Updated v4.1
	'a_error1'		=>'L\' allegato selezionato non esiste.',
	'a_error2'		=>'L\'upload e\' fallito. Scegli un altro file e prova ancora.',
	'a_error3'		=>'Il tipo di file che hai uppato non e\' accettato. Rimuovi l\'allegato o scegli un file diverso.',
	'a_error4'		=>'Il file uppato e\' troppo pesante. La massima grandezza per file e\' {1}.',
	'a_error5'		=>'Questo file e\' gia\' uppato.Scegli un altro file.',
	'a_info'			=>'File Allegati',
	'a_size'			=>'Grandezza: ',
	'a_count'		=>'Downloads: ',
	'attachment'	=>'Allegato:',
	'a_remove'		=>'Seleziona questa casella per rimuovere gli allegati da questo post.',
	'deny'				=>'Questo utente desidera rimanere anonimo!',
	'thread_review'=>'Aggiornamento Topic (Prima i piu\' nuovi)', // Added v3.6
	'posted_by'		=>'Postato da', // Added v3.6
	'quick_reply'	=>'Risposta Veloce', // Added v3.6
	'post_reply'		=>'Rispondi', // Added v3.6
	'preview_reply'	 =>'Anteprima Post', // Added v3.6
	'search_forum'	=>'Cerca in questo forum', // Added v3.6
	'click_hold_edit'=>'(Tieni premuto per modificare)', // Added v3.6
	'ajax_loading' =>'Caricando. <br />Attendi un istante..', // Added v3.6
	'saving_changes' =>'Salvando Cambiamenti..', // Added v3.6
	'noperm'			=>'Accesso negato!', // Added v3.6
	'posted'			=>'Postato', //Added v3.7
	'announcements'=>'Annuncio:', //Added v3.7
	'atitle'			=>'Annunci del forum', //Added v3.7
	'invalidaid'	=>'L\'annuncio specificato non esiste.', //Added v3.7
	'gotolastpost'	=>'Vai all\'ultimo post', //Added v3.7
	'deleteposts'	=>'Elimina Post(s)', //Added v3.7
	'deletethreads'	 =>'Elimina Discussione(i)', //Added v3.7
	'subs'				=>'Sottoscrivi a questa discussione', //Added v3.8
	'delsubs'			=>'Rimuovi sottoscrizione a questa discussione', //Added v3.8
	'asubs'			=>'Sei gia\' sottoscritto a questa discussione!',  //Added v3.8
	'dsubs'			=>'La sottoscrizione alla discussione e\' stata aggiunta.', //Added v3.8
	'msubs'			=>'Caro {1},

Sei sottoscritto alla discussione {2}, nella quale e\' stato postato un nuovo messaggio. L\'ultimo utente che ha scritto era {3}.

Per visitare la discussione, ti preghiamo di visitare questo link:
{4}/tsf_forums/showthread.php?tid={5}

Cordialmente,
lo Staff di {6}.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Informazione per rimuovere la sottoscrizione:

Per rimuovere la sottoscrizione dalla discussione, visita questa pagina:
{4}/tsf_forums/subscription.php?do=removesubscription&tid={5}', //Added v3.8
	'rsubs'			=>'La sottoscrizione a questo thread e\' stata rimossa!', //Added v3.8
	'isubs'				=>'Sarai notificato via email quando qualcuno rispondera\' alla discussione.', //Added v3.8
	'goadvanced'	=>'Avanzate', //Added v3.9	
	'sforums'	 =>'Subforums', //Added v3.9
	'tbdays'=>'Compleanni di oggi', //Added v3.9
	'tbdayss'=>'<b>{1}</b> utenti celebrano il loro compleanno oggi!',  //Added v3.9
	'rate1'=>'Vota Discussione',//Added v4.0
	'rate2'=>'Vota questa discussione',//Added v4.0
	'rateop5'=>'Ottima',//Added v4.0
	'rateop4'=>'Buona',//Added v4.0
	'rateop3'=>'Normale',//Added v4.0
	'rateop2'=>'Scadente',//Added v4.0
	'rateop1'=>'Pessima',//Added v4.0
	'ratenow'=>'Vota Ora',//Added v4.0
	'rateresult1'=>'Il tuo voto e\' stato aggiunto!',//Added v4.0
	'rateresult2'=>'Hai gia\' votato questa discussione.',//Added v4.0	
	'rateresult3'=>'Hai selezionato un\'opzione invalida.',//Added v4.0
	'rateresult4'=>'Hash del Post NON valido!', //Added v4.0
	'sticky'=>'<strong>Importante:</strong> ', //Added v4.0
	'tratingimgalt'=>'Valutazione Discussione: {1} voti, {2} di media.',//Added v4.0
	'showandclose'=>'Mostra discussione e Chiudi la finestra',//Added v4.0
	'poll1'=>'Posta un Sondaggio',//Added v4.0
	'poll2'=>'Si\', posta un sondaggio con questa discussione',//Added v4.0
	'poll3'=>'Numero opzioni sondaggio:',//Added v4.0
	'poll4'=>'Domanda sondaggio:',//Added v4.0
	'poll5'=>'Opzioni sondaggio',//Added v4.0
	'poll6'=>'Opzione {1}',	//Added v4.0
	'poll7'=>'Invia nuovo sondaggio!',//Added v4.0
	'poll8'=>'Completa la lista delle opzioni. Minimo 2 opzioni.',//Added v4.0
	'poll9'=>'Non puoi aggiungere un sondaggio in questa discussione perche\' ne ha gia\' uno.',//Added v4.0
	'poll10'=>'Grazie per aver postato! Sarai rediretto al post. Sei hai optato per un sondaggio, ora potrai crearlo.',//Added v4.0
	'poll11'=>'Hai gia\' votato in questo sondaggio!',//Added v4.0
	'poll12'=>'Questo sondaggio e\' chiuso',//Added v4.0
	'poll13'=>'Non puoi votare in questo sondaggio!',//Added v4.0
	'poll14'=>'Vedi risultati sondaggio: ',//Added v4.0
	'poll15'=>'Voti: ',//Added v4.0
	'poll16'=>'Modifica Sondaggio',//Added v4.0
	'poll17'=>'Sondaggio',//Added v4.0
	'poll18'=>'Vedi risultati sondaggio',//Added v4.0
	'poll19'=>'Vota Ora',//Added v4.0
	'poll20'=>'Non hai selezionato alcun\'opzione. Premi "indietro" per tornare alla pagina precedente e scegliere un\'opzione.',//Added v4.0
	'poll21'=>'Sondaggio invalido!',//Added v4.0
	'poll22'=>'Sondaggio chiuso',//Added v4.0
	'poll23'=>'Per chiudere questo sondaggio, seleziona questa casella.<br>Nota: Chiudendo il sondaggio non sara\' piu\' possibile votare. Comunque gli utenti potranni rispondere alla discussione',//Added v4.0
	'modlist'=>'Moderatore(i): {1}',//Added v4.1
	'hidden'=>'<i><b>Nascosto</b></i>',//Added v4.1
	'fpassword'=>'Gli Amministratori richiedo la password per accedere a questo forum!',//Added v4.1
	'fpassword2'=>'Per favore inserisci la password: questo richiede i cookies abilitati!',//Added v4.1
	'fpassword3'=>'Login',//Added v4.1
	'modnotice1'			=>'Messaggio Moderatore:',//Added v4.1
	'modnotice2'			=>'Seleziona il checkbox per rimuovere il messaggio moderatore.',//Added v4.1
	'starter'=>'Creatore Discussione',//Added in v5.0
	'rating'=>'Valutazioni',//Added in v5.0
	'foptions'=>'Opzioni Forum',//Added in v5.0
	'toptions'=>'Opzioni Discussioni',//Added in v5.0
	'pthread'=>'Stampa questa Discussione',//Added in v5.0
	'ethread'=>'Invia per Email questa Discussione',//Added in v5.0
	'ethreadh'=>'Segnala Discussione ad un amico',//Added in v5.0
	'fname'=>'Nome Amico:',//Added in v5.0
	'femail'=>'Email Amico:',//Added in v5.0
	'tsubject'=>'Oggetto:',//Added in v5.0
	'tmsg'=>'Messaggio:',//Added in v5.0
	'tmsgh'=>'Ho pensato che ti potesse interessare questa pagina: {1}

Da,
{2}
	',
	'tmsgs'=>'{1},

Questo e un messaggio da {2} ( {3} ) da {4} - ( {5} ).

Il messaggio e il seguente:

{6}

{4} Il forum non e responsabile per i messaggi inviati attraverso questo sistema.',//Added in v5.0
	'picons1'	=>	'Icona Post: ',//Added in v5.0
	'picons2'	=>	'Puoi scegliere un altra icona per il tuo messaggio usando la seguente lista:',//Added in v5.0
	'pcions3'	=>	'Nessuna Icona',//Added in v5.0
	'sthread' => 'Cerca in questo Topic',//Added v5.3	
	'mop1'=>'Apri Topic',//Added v5.3
	'mop2'=>'Chiudi Topic',//Added v5.3
	'mop3'=>'Topic in Rilievo',//Added v5.3
	'mop4'=>'Topic NON in Rilievo',//Added v5.3
	'mop5'=>'Unisci i Topic',//Added v5.3
	'mop6'=>'Topic Destinazione',//Added v5.3
	'mergeerror'=>'Errore: Topic Sorgente e Destinazione coincidono.',//Added v5.3
	'top'=>'Top',//Added v5.3
	'thank'=>'Il seguente user ringrazia {1} per questa discussione:',//Added v5.6
	'thanks'=>'I seguenti users {1} ringraziano {2} per questa discussione:',//Added v5.6
	'thanked'=>'Hai gia\' ringraziato per questo post!',//Added v5.6
);
?>