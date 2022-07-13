<?php
	$z_require_erlaubt = true;

	require('header_ohne_text_mit_session_start.php');
	
	if(!isset($_GET['token'])){
		z_html_warnung("Token angeben!");
		die();
	}
	if(!isset($_GET['token_verbraucht'])){
		z_html_warnung("token_verbraucht angeben!");
		die();
	}
	if(!isset($_GET['tabelle']) || $_GET['tabelle'] == null || $_GET['tabelle'] == ""){
		z_html_warnung("Tabelle angeben!");
		die();
	}
	$token = $_GET['token'];
	$token_verbraucht = $_GET['token_verbraucht'];
	$tabelle = $_GET['tabelle'];
	
	if(strtolower($token) == "null"){
		z_html_warnung("Token ist <i>NULL</i> und kann nicht verwendet werden.");
		die();
	}
	
	$query = " UPDATE $tabelle SET token_verbraucht = '$token_verbraucht' WHERE token = '$token'"; 
	$ergebnis = z_db_sql_statement_ausfuehren($query);
	$ergebnis = "Das Token kann wieder benutzt werden.";
	if($token_verbraucht == '1'){
		$ergebnis = "Das Token kann nicht mehr benutzt werden.";
	}
	echo "$ergebnis";
?>