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

// recover.php, recoverhint.php
$language['recover'] = array 
(
	'error'						=>'Per favore, effettua prima il logout.',
	'error2'					=>'Indirizzo email NON valido!',
	'error3'					=>'L\'Indirizzo email NON e\' stato trovato nel nostro database.',
	'body'						=>'Salve,

Qualcuno, sperando sia tu, ha richiesto che la password per l\'account associato a questo indirizzo email ({1}) venga resettata.

La richiesta e\' partita da {2}.

Se non sei stato tu, ignora e per favore non rispondere a questa email.

Se desideri confermare questa richiesta, per favore segui questo:

{3}/recover.php?id={4}&secret={5}

(Gli utenti AOL devono copiare e incollare il link nel loro browser).

Dopo aver fatto questo, la tua password sara\' resettata e ti sara\' mandata tramite email.

------------------------------------------------
Non funziona?
------------------------------------------------

se non riesci ad effettuare questa operazione clickando sul link, per favore visita questa pagina:

{3}/recover.php?act=manual

ti verra\' chiesto il tuo USER ID, e la tua secret key, riportate di seguito:

User ID: {4}

Secret Key: {5}

Per favore copia e incolla, o digita questi numeri nei campi corrispondenti nel form.

Se continui a non riuscire a confermare il tuo account, e\' possibile che esso sia stato rimosso.
In questo caso, per favore contatta un amministratore per risolvere il problema.

Grazie per esserti registrato e buon divertimento!

Saluti,

lo Staff di {6} .', // Updated v4.1
	'subject'					=>'Conferma del reset della password per {1} !',
	'invalidcodeorid'			=>'Il CODICE/ID specificato non e\' valido o non e\' stato trovato nel nostro database.',
	'invalidcode2'				=>'Il CODICE/ID specificato non e\' valido perche\' non e\' stato trovato nel nostro database.',
	'invalidcode3'				=>'Il CODICE/ID specificato non e\' valido perche\' non corrisponde.',
	'body2'						=>'Salve,

come da tua richiesta e\' stata generata una nuova password per il tuo account.

Di seguito sono riportate le informazioni registrate:

    UserName: {1}
    Password:  {2}

Effettua ora il login andando su {3}/login.php

Saluti,
lo Staff di {4} .',
	'subject2'					=>'Dettagli account per {1}',
	'head'						=>'Recupero Username o Password persi',
	'errortype1'				=>'ERRORE: Indirizzo email non corretto! Per favore riprova. Hai ancora <b>{1}</b> tentativi.',	
	'errortype3'				=>'ERRORE: Username non corretto! Per favore riprova. Hai ancora <b>{1}</b> tentativi.',
	'info'						=>'<p align=center>Usa il form seguente per richiedere il reset della tua password e per ricevere i nuovi dati tramite email.</p> <p align=center>(Dovrai seguire le istruzioni contenute nella email di conferma.)</p><p align=center><b>Nota: {1}</b> tentativi falliti porteranno al ban del tuo IP!</p> ',
	'fieldemail'				=>'Email di Registrazione:',
	'info2'						=>'<p align=center><b>Nota:</b> Saranno ricercati solo gli Utenti che hanno inserito la domanda segreta con relativa risposta!</p><p align=center><b>{1}</b> tentativi falliti porteranno al ban del tuo IP!</p>',
	'fieldusername'				=>'Username:',
	'denyaccessforstaff'		=>'Sfortunatamente, lo Staff non ha il permesso per recuperare i dati attraverso il sistema Recover-Hint.
	Per favore recupera il tuo account via email o contatta lo Staff Leader.',
	'info3'						=>'<p align=center>Per favore inserisci la risposta corretta alla domanda segreta.</p>',
	'sq'						=>'Domanda Segreta:',
	'ha'						=>'Risposta Segreta:',
	'hr0'						=>'Qual\'e\' il tuo piatto preferito?',
	'hr1'						=>'Qual\'e\' il nome del tuo animale domestico?',
	'hr2'						=>'Qual\'e\' il nome di tua madre?',
	'invalidanswer'				=>'Risposta NON valida!',
	'generated1'				=>'Nuova Password Generata!',
	'generated2'				=>'La tua nuova Password e\' <input type="text" value="{1}"> (Vai al <a href={2}/login.php>login</a>)',
	'msent'	=>'Il tuo username e i dettagli su come resettare la tua password sono stati inviati tramite email.',//Added v3.9.0
);
?>