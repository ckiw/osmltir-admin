<?php
# *** LICENSE ***
# This file is part of BlogoText.
# http://lehollandaisvolant.net/blogotext/
#
# 2006      Frederic Nassar.
# 2010-2013 Timo Van Neerden <ti-mo@myopera.com>
#
# BlogoText is free software, you can redistribute it under the terms of the
# Creative Commons Attribution-NonCommercial 2.0 France Licence
#
# Also, any distributors of non-official releases MUST warn the final user of it, by any visible way before the download.
# *** LICENSE ***

if ( !file_exists('../config/user.php') || !file_exists('../config/prefs.php') ) {
	header('Location: install.php');
}

$GLOBALS['BT_ROOT_PATH'] = '../';
require_once '../inc/inc.php';
error_reporting($GLOBALS['show_errors']);

$max_attemps = 10; // max attempts before blocking login page
$wait_time = 30;   // time to wait before unblocking login page, in minutes

// Acces LOG
if (isset($_POST['nom_utilisateur'])) {
	// IP
	$ip = htmlspecialchars($_SERVER["REMOTE_ADDR"]);
	// Proxy IPs, if exists.
	$ip .= (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? '_'.htmlspecialchars($_SERVER['HTTP_X_FORWARDED_FOR']) : '';
	$curent_time = date('r'); // heure : Wed, 18 Jan 2012 20:42:12 +0100
	$data = '<?php die(\'no.\'); // '.$curent_time.' - '.$ip.' - '.((check_session()===TRUE) ? 'login succes' : 'login fail') ."?> \n";
	file_put_contents($GLOBALS['BT_ROOT_PATH'].$GLOBALS['dossier_config'].'/'.'xauthlog.php', $data, FILE_APPEND);
}

if (check_session() === TRUE) { // return to index if session is already open.
	header('Location: index.php');
	exit;
}


// Auth checking :
if (isset($_POST['_verif_envoi']) and valider_form() === TRUE) { // OK : getting in.
	$ip = (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? htmlspecialchars($_SERVER['HTTP_X_FORWARDED_FOR']) : htmlspecialchars($_SERVER['REMOTE_ADDR']);
	$_SESSION['user_id'] = $_POST['nom_utilisateur'].hash_password($_POST['mot_de_passe'], $GLOBALS['salt']).md5($_SERVER['HTTP_USER_AGENT'].$ip); // set special hash
	usleep(100000); // 100ms sleep to avoid bruteforce

	if (!empty($_POST['stay_logged'])) { // if user wants to stay logged
		$user_id = hash_password($GLOBALS['mdp'].$GLOBALS['identifiant'].$GLOBALS['salt'], md5($_SERVER['HTTP_USER_AGENT'].$ip.$GLOBALS['salt']));
		setcookie('BT-admin-stay-logged', $user_id, time()+365*42*60*60, null, null, false, true);

	} else {
		$_SESSION['stay_logged_mode'] = 0;
		session_regenerate_id(true);
	}
	fichier_ip();
	header('Location: index.php');

} else { // On sort…
		// …et affiche la page d'auth
		afficher_top('Identification');
		echo '<div id="axe">'."\n";
		echo '<div id="pageauth">'."\n";
		echo '<h1>'.$GLOBALS['nom_application'].'</h1>'."\n";
		echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'">'."\n";
		echo '<div id="auth">'."\n";
		echo '<p><label for="nom_utilisateur">'.ucfirst($GLOBALS['lang']['label_identifiant']).'</label>'."\n";
		echo '<input class="text" type="text" id="nom_utilisateur" name="nom_utilisateur" value="" /></p>'."\n";
		echo '<p><label for="mot_de_passe">'.ucfirst($GLOBALS['lang']['label_motdepasse']).'</label>';
		echo '<input class="text" type="password" id="mot_de_passe" name="mot_de_passe" value="" /></p>'."\n";
		if (isset($GLOBALS['connexion_captcha']) and ($GLOBALS['connexion_captcha'] == "1")) {
			echo js_reload_captcha(1);
			echo '<p><label for="word">'.ucfirst($GLOBALS['lang']['label_word_captcha']).'</label>';
			echo '<input class="text" type="text" id="word" name="word" value="" /></p>'."\n";
			echo '<p><a href="#" onclick="new_freecap();return false;" title="'.$GLOBALS['lang']['label_changer_captcha'].'"><img src="../inc/freecap/freecap.php" id="freecap"></a></p>'."\n";
		}

		echo '<p class="sinline"><input type="checkbox" id="stay_logged" name="stay_logged" /><label for="stay_logged">'.$GLOBALS['lang']['label_stay_logged'].'</label>'."\n";
		echo '</p>'."\n";
		echo '<input class="inpauth blue-square" type="submit" name="submit" value="'.$GLOBALS['lang']['connexion'].'" />'."\n";
		echo '<input type="hidden" name="_verif_envoi" value="1" />'."\n";
		echo '</div>'."\n";
		echo '</form>'."\n";
}

function valider_form() {
	$mot_de_passe_ok = $GLOBALS['mdp'].$GLOBALS['identifiant'];
	$mot_de_passe_essai = hash_password($_POST['mot_de_passe'], $GLOBALS['salt']).$_POST['nom_utilisateur'];
	// first test password
	if ($mot_de_passe_essai != $mot_de_passe_ok) {
		return FALSE;
	}
	// then test captcha
	if (isset($GLOBALS['connexion_captcha']) and ($GLOBALS['connexion_captcha'] == "1")) { // si captcha activé
		if ( empty($_SESSION['freecap_word_hash']) or empty($_POST['word']) or (sha1(strtolower($_POST['word'])) != $_SESSION['freecap_word_hash']) ) {
			return FALSE;
		}
		$_SESSION['freecap_word_hash'] = FALSE; // reset captcha word
	}

	return TRUE;
}

footer();
?>
