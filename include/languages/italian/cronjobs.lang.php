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

// cronjobs.php New since v3.6
$language['cronjobs'] = array 
(
	'r_subject'	=> 'Regalo dal Sistema Referenti!',
	'r_message' => 'Salve,

	Grazie per aver usato il Sistema Referenti.

	Hai guadagnato {1} crediti.

	Cordialmente.',// Updated in v5.4
	'invite_subject'	 => 'Invito Automatico!',
	'invite_message'	=> 'Congratulazioni, hai ricevuto {1} inviti.

	Se vuoi invitare un tuo amico, clicca [url=invite.php?id={2}]QUI[/url].',// Updated in v5.4
	'donor_subject'	=> 'Status Donatore rimosso.',
	'donor_message'	=>	 'Salve,
	
	Il tuo status DONATORE e\' scaduto ed e\' stato rimosso dal sistema. 
	
	Vogliamo ringraziarti ancora per il supporto fornito. 
	
	Se vuoi rinnovare il tuo stato, clicca [url=donate.php]QUI[/url]. 
	
	Cordialmente.',// Updated in v5.4
	'vip_subject'	=>'Status VIP rimosso dal sistema.',
	'vip_message'	=>'Salve,
	
	Il tuo status VIP e\' scaduto ed e\' stato rimosso dal sistema.
	
	Diventa ancora VIP donando ancora.
	
	Cordialmente.',// Updated in v5.4
	'promote_subject'	 =>'Account Promosso!',
	'promote_message'	=>'Congratulazioni sei stato Auto-Promosso a [b]Power User[/b]. :)',
	'demote_subject'	=>'Account Demoddato!',
	'demote_message'	=>'Sei stato demoddato da [b]Power User[/b] to [b]User[/b] perche\' il tuo share ratio e\' basso {1}',
	'rwarning_subject'	 =>'L\'Ammonizione e\' stata rimossa.',
	'rwarning_message'	=>'La tua ammonizione e\' stata rimossa.',
	'lwarningr_subject'	=>'La tua ammonizione e\' stata rimossa.',
	'lwarningr_message'	=>'La tua ammonizione e\' stata rimossa.',
	'lwarning_subject'	 =>'Sei stato Ammonito!',
	'lwarning_message'	=>'Sei stato ammonito perche\' il tuo ratio e\' basso. Devi avere un ratio di {1} prima di {2} settimane o verrai bannato.',
	'hr_warn_subject'=>'Ammonizione Hit and Run!',//Added in v5.5
	'hr_warn_message'=>'[b]{1}[/b],

Sei stato ammonito per aver fatto Hit & Run sul seguente torrent:
[b]{2}[/b]

Hai fatto da Seed per [b]{3}[/b] ora(e) ma si deve farlo per [b]{4}[/b] ora(e).

Per favore fai da Seed per questo torrent o sarai ammonito presto di nuovo.
Se non hai questo torrent sul tuo computer, per favore clicka sul seguente link per scaricarlo e rimetterti in seed.
[b]{5}[/b]

Tutti i torrents devono esser seedati per un minimo di [b]{6}[/b] ora(e) dopo averli finiti altrimenti gli user avranno un [b]+1[/b] nel conto delle ammonizioni per torrent.
Nota: Una volta che il totale delle ammonizioni raggiungeranno il limite globale (default 7), l\'account sara\' sospeso.

Grazie per la comprensione e il supporto.
Buona giornata.'//Added in v5.5
);
?>