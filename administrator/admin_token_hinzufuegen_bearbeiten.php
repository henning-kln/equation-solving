<?php
	$z_require_erlaubt = true;
	require_once('header.php');
	
	if(!isset($_POST['tabelle']) || !isset($_POST['anzahl']) || !isset($_POST['laenge'])){
		z_html_warnung("Angaben fehlen!");
		z_html_button_link("Zur&uuml;ck", "admin_token_hinzufuegen.php");
		require("footer.php");
		die();
	}
	
	$tabelle = $_POST['tabelle'];
	$anzahl = $_POST['anzahl'];
	$laenge = $_POST['laenge'];
	
	if($anzahl < 1 || $anzahl > 100){
		z_html_warnung("Anzahl muss zwischen 1 und 100 sein!");
		z_html_button_link("Zur&uuml;ck", "admin_token_hinzufuegen.php");
		require("footer.php");
		die();
	}
	
	if($laenge < 5 || $laenge > 10){
		z_html_warnung("L&auml;nge muss zwischen 5 und 10 sein!");
		z_html_button_link("Zur&uuml;ck", "admin_token_hinzufuegen.php");
		require("footer.php");
		die();
	}
	
	echo "<h1>Neue Token f&uuml;r die Tabelle <i>$tabelle</i></h1>\n";
	$heute = z_zeit_heute();
	$jetzt = z_zeit_jetzt();
	echo "<p>Erzeugt am $heute um $jetzt Uhr von $z_user_login</p>";
	z_html_warnung("DIREKT SPEICHERN!!!</b>");
	
	echo "<p>\n";
	for($i=0; $i<$anzahl; $i++){
		$neuer_token = z_token_finden($tabelle, $laenge);
		$db_abfrage = " INSERT INTO $tabelle (token, token_verbraucht) ".
		              " VALUES ('$neuer_token','0')";
		z_db_sql_statement_ausfuehren($db_abfrage);
		echo "$neuer_token<br/>\n";
	}
	echo "</p>\n";
	
	require_once('footer.php');			
?>