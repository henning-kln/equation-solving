<?php
	$z_require_erlaubt = true;

	require('header_ohne_text_mit_session_start.php');
	
	function tabellen_kopf_ausgeben(){
		echo("<table border=1><tr><th>Login</th><th>Token</th><th>Token verbraucht</th><th>Info</th></tr>");
	}
	
	if(!isset($_GET['login'])){
		z_html_warnung("Login-Namen eingeben!");
		die();
	}
	if(!isset($_GET['tabelle']) || $_GET['tabelle'] == null || $_GET['tabelle'] == ""){
		z_html_warnung("Tabelle ausw&auml;hlen!");
		die();
	}
	$login = z_db_text_erzeugen($_GET['login']);
	if(strlen($login) == 0){
		z_html_warnung("Mindestens einen Buchstaben eingeben!");
		die();
	}
	$tabelle = $_GET['tabelle'];
	$query = " SELECT login, token, token_verbraucht FROM $tabelle ".
	         " WHERE login LIKE '%$login%' ";
	$ergebnis = z_db_sql_statement_ausfuehren($query);
	tabellen_kopf_ausgeben();
	while($zeile = z_db_zeile_auslesen($ergebnis)){
		$vollstaendiges_login = $zeile['login'];
		$token = "NULL";
		if(isset($zeile['token']) && $zeile['token'] != null){
			$token = $zeile['token'];
		}
		$token_verbraucht = $zeile['token_verbraucht'];
		echo "<tr>\n";
		echo "<td>$vollstaendiges_login</td>\n";
		echo "<td>$token</td>\n";
		$checked = "";
		if($token_verbraucht == 1){
			$checked = 'checked';
		}
		echo "<td><input type='checkbox' id='checkbox_$token' name='checkbox_$token'  value='checkbox_$token' $checked></td>\n";
		echo "<td id='td_info_$token'></td>\n";
		echo "</tr>\n";
		$zusaetzliche_variablen_array = array("tabelle" => $tabelle, "token" => $token);
		z_ajax_change("checkbox_$token", "admin_suchen_token_verbraucht_aendern.php", "GET", "token_verbraucht", $zusaetzliche_variablen_array, "td_info_$token");
	}
	echo "</table>\n";
?>