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

//  signup.php and takesignup.php
$language['signup'] = array 
(
	'invalidinvitecode'				=>'Il codice dell\'invito specificato NON e\' valido, perche\' non e\' stato trovato nel nostro database.',
	'registration'					=>'Registrazione',
	'username'						=>'Username:',
	'allowedchars'					=>'Caratteri consentiti: (a-z), (A-Z), (0-9)',
	'ps'							=>'Sicurezza della Password:',
	'pap'							=>'Password:',
	'papr'							=>'Inserisci di nuovo la Password:',
	'sq'							=>'Domanda Segreta:',
	'ha'							=>'Risposta Segreta:',
	'hr0'							=>'Qual\'e\' il tuo piatto preferito?',
	'hr1'							=>'Qual\'e\' il nome del tuo animale domestico?',
	'hr2'							=>'Qual\'e\' il nome di tua madre?',
	'hainfo'						=>'Questa Risposta sara\' utile per resettare la tua password in caso te la dimenticassi.<br> Assicurati sia qualcosa che non puoi dimenticare!',
	'email'							=>'Email:',
	'emailinfo'						=>'Fornisci un indirizzo email valido.',
	'tzsetting'						=>'Impostazioni Orario (Timezone):',
	'tzsettinginfo'					=>'Abilita ore legale?<br>
Se il tuo timezone e\' differente da quello del tracker, puoi selezionarlo dalla lista seguente.<br>L\'orario GMT ora e\' ',
	'gender'						=>'Sesso',
	'male'							=>'Maschile',
	'female'						=>'Femminile',
	'verification'					=>'Verifica:',
	'verification2'					=>'<input type=checkbox name=uaverify value=yes> 	Ho letto e accetto lo <a href=useragreement.php><u><strong>User Agreement</strong></u></a>.<br><input type=checkbox name=rulesverify value=yes> 
Ho letto e accetto il <a href=rules.php><u><strong>REGOLAMENTO</strong>.</u></a><br><input type=checkbox name=faqverify value=yes> 
Accetto di leggere le <a href=faq.php><u><strong>FAQ</strong></u></a> prima di porre domande. <br><input type=checkbox name=ageverify value=yes> Dichiaro di avere almeno <a href=rules.php><u><strong>13</strong></u></a> anni.</td></tr>',
	'signup'						=>'Registrami! (PREMI SOLO UNA VOLTA)',
	'country'						=>'Nazione:',	
	'noagree'						=>'Spiacenti, non sei qualificato per esser registrato.',
	'invalidemail'					=>'Sembra che hai inserito un indirizzo email NON valido.',
	'invalidemail2'					=>'Questo indirizzo email risulta bannato!',	 // updated in v3.8
	'invalidemail3'					=>'Questo indirizzo email risulta essere gia\' in uso.',
	'nogender'						=>'Per favore seleziona il tuo sesso.',
	'hae1'							=>'Spiacenti, la Risposta Segreta e\' troppo corta (min 6 caratteri)',
	'hae2'							=>'Spiacenti, la Risposta Segreta non puo\' coincidere con lo username.',
	'une1'							=>'Spiacenti, Username troppo corto (min 3 caratteri)',
	'une2'							=>'Spiacenti, Username troppo lungo (max 12 caratteri)',
	'une3'							=>'Username NON valido!',
	'une4'							=>'Username esistente!',
	'passe1'						=>'La password non corrisponde! Riprova ancora.',
	'passe2'						=>'Spiacenti, password troppo corta (min 6 caratteri)',
	'passe3'						=>'Spiacenti, password troppo lunga (max 40 caratteri)',
	'passe4'						=>'Spiacenti, le password non puo\' coincidere con lo username',
	'welcomepmsubject'				=>'Benvenuto/a su {1}!',
	'welcomepmbody'					=>'Congratulationi {1},

	ora sei iscritto su {2}, benvenuto su {2}!
	
	Per favore leggi il REGOLAMENTO: ({3}/rules.php) e le nostre FAQ: ({3}/faq.php#dl8) e presentati nel Forum: ({3}/tsf_forums) 
	
	Buon divertimento.
	lo Staff di {2}',
	'verifiyemailsubject'			=>'Conferma di Registrazione per {1}',
	'verifiyemailbody'				=>'
Ciao {1},
Questa email ti e\' stata mandata da {2}/index.php.

Hai ricevuto questa email perche\' questa e\' stata usata per la registrazione al nostro forum.
Se non ti sei registrato sul nostro forum, per favore ignora questa email. 

------------------------------------------------
Istruzioni per l\'Attivazione
------------------------------------------------

Grazie per esserti registrato.
Richiediamo di confermare la tua registrazione per esser sicuri che l\'indirizzo email sia corretto. Questo a protezione da spam e abusi.

Per attivare il tuo account basta clickare il seguente link:

{2}/confirm.php?id={3}&secret={4}

(Gli utenti AOL devono copiare e incollare il link nel loro browser).

------------------------------------------------
Non funziona?
------------------------------------------------

se non riesci ad effettuare questa operazione clickando sul link, per favore visita questa pagina:

{2}/confirm.php?act=manual

ti verra\' chiesto il tuo USER ID, e la tua secret key, riportate di seguito:

User ID: {3}

Secret Key: {4}

Per favore copia e incolla, o digita questi numeri nei campi corrispondenti nel form.

Se continui a non riuscire a confermare il tuo account, e\' possibile che esso sia stato rimosso.
In questo caso, per favore contatta un amministratore per risolvere il problema.

Grazie per esserti registrato e buon divertimento!

Saluti,

lo Staff di {5}.
{2}/index.php
', // Updated in v4.1
	'autoconfirm'					=>'Concludi Registrazione!',
	'autoconfirm2'					=>'Per favore clicka <a href="{1}/confirm.php?id={2}&secret={3}">QUI</a> per concludere la tua finish Registrazione, Grazie!',
	'referrer'				=>'Referente (opzionale): ',
	'invalidreferrer'		=>'Nome Referente NON valido!',
	'eavailable'			=>'Email disponibile!', // Added v3.7
	'uavailable'			=>'Username disponibile!', // Added v3.7
	'checkavailability' =>'Controlla disponibilita\'', //Added v3.7
	'invalidbday'			=>'Data di Nascita NON valida!', //Added v3.7
);
?>