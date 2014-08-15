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

// invite.php
$language['invite'] = array 
(
	'failed'					=>'Fallito',
	'success'					=>'Successo',
	'head'						=>'Sistema Inviti',
	'status'					=>'Stato corrente degli inviti',
	'noinvitesyet'				=>'Nessun invito.',
	'username'					=>'Utente',
	'email'						=>'Email',
	'lastseen'					=>'Ultima Visita',
	'uploaded'					=>'Upload',
	'downloaded'				=>'Download',
	'ratio'						=>'Ratio',
	'status2'					=>'Status',
	'never'						=>'mai',
	'confirmed'					=>'Confermato',
	'pending'					=>'Non Confermato',
	'status3'					=>'Stato corrente degli inviti inviati',
	'nooutyet'					=>'Nessun invito inviato al momento.',
	'hash'						=>'Hash Inviti',
	'senddate'				=>'Data invio',
	'info'						=>'Invita qualcuno a registrarsi su {1}.',
	'button'					=>'Invita qualcuno',
	'field1'					=>'Email del tuo amico:',
	'field2'					=>'* deve essere valida!',
	'field3'					=>'Se vuoi mandare un messaggio al tuo amico personalizzato, inseriscilo qui:',
	'field4'					=>'Hai altri {1} inviti.',
	'button2'					=>'Manda Invito',
	'button3'					=>'Ripulisci',
	'noinvitesleft'				=>'Non hai altri inviti!!<br><br>Seeda un torrent e avrai inviti!',
	'invitesystemoff'			=>'Mi dispiace, il sistema di inviti e\' disattivato. Riprova piu\' tardi.',
	'alert'						=>'ATTENZIONE! Il sistema inviti e\' al momento disattivato!',
	'invalidemail'				=>'L\'email specificata non e\' valida.',
	'invalidemail2'				=>'L\'email specificata non e\' valida, e\' gia\' stata trovata nel nostro database.',
	'nonote'					=>'Nessuna nota',
	'subject'					=>'Sei stato invitato a registrarti su {1}!',
	'message'					=>'Ciao,

Sei stato invitato da {1} a registrarti su "{2}".

Il tuo link di registrazione e\' {3}/signup.php?invitehash={4}&type=invite

Attenzione: devi accettare l\'invito entro {5} giorni altrimenti il link non sara\' piu\' valido.

Speriamo di vederti presto!

Cordiali Saluti,
lo Staff di {2} 

{1} ha lasciato la seguente nota:
----------------------------------------------------
{6}
----------------------------------------------------
',
	'error'						=>'C\'e\' stato un errore. Riprova piu\' tardi.',
	'sent'						=>'Grazie. Il tuo invito e\' stato inviato con successo a questo utente: {1}',
	'manuellink'				=>'Il link di registrazione del tuo amico e\' il seguente:<br> {1}/signup.php?invitehash={2}&type=invite',
	'selecttype'				=>'Seleziona il tipo di invito: ',
	'type1' => 'Email (Automatico)',
	'type2' => 'Manuale (Copia e Incolla)',
	'typebutton'	=>'prosegui',
	'action'			=>'Elimina',
	'actionbutton'	=>'Elimina selezionato',
	'invitedeadtime'=>'Validita\' invito',
	'added'			=>'Aggiunto',
	'default_invite_msg' => 'Ciao Amico,

Guarda questo sito!! 

Buona giornata.',//Added in v5.1
);
?>