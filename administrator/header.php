<?php


	if(!isset($z_require_erlaubt) || $z_require_erlaubt != true){ 
		echo("<font color='red'><p>Unerlaubter Aufruf des Skriptes!</p></font>");
		die();
	}	
	
	// z_user wird aus dem Verzeichnis abgeleitet, in dem die Datei liegt!
	$z_user = 	basename(dirname(__FILE__));
	
	require('../include/config.php');
	$z_user_mit_db_prefix = $z_config_db_tabellen_prefix.$z_user;
	
	require_once('../include/db_verbindung.php');
	require_once('../include/session_funktionen.php');
	require_once('../include/werkzeuge.php');
	require_once('../include/konsole.php');
	date_default_timezone_set("Europe/Berlin");
	
	//echo "Session wird gestartet!<br/>\n";
	z_session_start();
	require('header_ohne_session_start.php');
?>

