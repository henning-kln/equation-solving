<?php
	if(!isset($z_require_erlaubt) || $z_require_erlaubt != true){ 
		die('<p align="center"><font color="red">db_verbindung.php: Unerlaubter Aufruf des Skriptes!</font></p>');
	}
	
	$z_db_verbindung=mysqli_connect($z_config_db_server,$z_config_db_user,$z_config_db_passwort,$z_config_db_name);
	
	if(!$z_db_verbindung){
		echo "Verbindungsdaten in <b>db_verbindung.php</b> &uuml;berpr&uuml;fen!<br>";
		echo "dbServer: $z_config_db_server<br>";
		die("Server kann nicht erreicht werden.");
	}
	if (mysqli_connect_errno()){
		echo "Verbindungsdaten in <b>db_verbindung.php</b> &uuml;berpr&uuml;fen!";
		die("Datenbank kann nicht angesprochen werden.");
	}
	mysqli_set_charset ($z_db_verbindung , 'utf8');
	
	function z_db_sql_statement_ausfuehren($db_abfrage){
		global $z_db_verbindung;
		//z_konsole("z_db_sql_statement_ausfuehren($db_abfrage)");
		$ergebnis=mysqli_query($z_db_verbindung, $db_abfrage);
		if(!$ergebnis){
			echo mysqli_error($z_db_verbindung);
			die();
		}
		return $ergebnis;
		
	}
	
	/**
	 * liest die naechste Zeile des Ergebnisses einer Datenbankabfrage aus.
	 * @param  $ergebnis
	 */
	function z_db_zeile_auslesen($ergebnis){
		return mysqli_fetch_array($ergebnis);
	}
	
	/**
	 * gibt zurueck, wie viele Zeilen das Ergebnis einer Datenbankabfrage hat.
	 * @param  $ergebnis
	 */
	function z_db_zeilen_zahl($ergebnis){
		return mysqli_num_rows($ergebnis);		
	}
	
	/**
	 * erzeugt einen Text, der datenbank-sicher ist.
	 * @param $text
	 * @return string
	 */
	function z_db_text_erzeugen($text){
		global $z_db_verbindung;
		$text1 = mysqli_real_escape_string($z_db_verbindung, $text);
		$text2 = htmlspecialchars($text1, null, 'UTF-8',FALSE);
		return $text2;		
	}
	
	
	/**
	 * 
	 * @return Array aller Tabellennamen mit dem Prefix der Anwendung.
	 */
	function z_db_alle_tabellen_namen()
	{
		global $z_config_db_tabellen_prefix;
		$query = "SHOW TABLES";
		if($z_config_db_tabellen_prefix != null && $z_config_db_tabellen_prefix != ""){
			$query = 
				" SELECT TABLE_NAME ".
				" FROM INFORMATION_SCHEMA.tables ".
				" WHERE TABLE_NAME LIKE '$z_config_db_tabellen_prefix%' ";
		}
		//echo "$query<br/>\n";
		$tableList = array();
		$ergebnis = z_db_sql_statement_ausfuehren($query);
		while($zeile = z_db_zeile_auslesen($ergebnis)){
			$tableList[] = $zeile[0];
		}
		return $tableList;
	}
	
?>