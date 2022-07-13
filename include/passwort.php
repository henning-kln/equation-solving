<?php
	require_once('../include/config.php');
	require_once('../include/db_verbindung.php');
	require_once('../include/werkzeuge.php');

	/**
	 * ueberprueft, ob Text passwort-ungeeignete Zeichen enthaelt.
	 * ueberprueft Text auf PHP-Steuerzeichen und SQL-Steuerzeichen
	 * $ " ' \  ; und Leerzeichen
	 *
	 */
	function z_passwort_enthaelt_ungeeignete_zeichen($text){
	    if(strpos($text,"\$") !== false) return true;
	    if(strpos($text,"\"") !== false) return true;
	    if(strpos($text,"'") !== false) return true;
	    if(strpos($text,"\\") !== false) return true;
	    if(strpos($text,";") !== false) return true;
	    if(strpos($text," ") !== false) return true;
	}
	
    /**
	 * 
	 * @param  $login
	 * @param $passwort
	 * @return "OK", wenn alles ok ist. Sonst eine Fehlermeldung
	 */
	function z_passwort_pruefen($login, $passwort){
		global $z_user_id, $z_user_mit_db_prefix,$z_db_verbindung;

		if(z_passwort_enthaelt_ungeeignete_zeichen($login)){
		    return "Login darf keine Leerzeichen, Dollarzeichen, Semikolons, Anfuehrungszeichen und Backslashes enthalten.";
		}
		if(z_passwort_enthaelt_ungeeignete_zeichen($passwort)){
		    return "Passwort darf keine Leerzeichen, Dollarzeichen, Semikolons, Anfuehrungszeichen und Backslashes enthalten.";
		}
// 		if(z_text_besteht_nur_aus_buchstaben_und_zahlen($login) == false || z_text_besteht_nur_aus_buchstaben_und_zahlen($passwort) == false){
// 			return "Login und Passwort d&uuml;rfen nur Buchstaben und Ziffern enthalten.";
// 		}
		$passwortSHA1 = SHA1($passwort);
		$statement= $z_db_verbindung->prepare(
		    "SELECT id, passwort FROM $z_user_mit_db_prefix WHERE login = ?"
		);
		$statement->bind_param("s",$login);
		$statement->execute();
		$result = $statement->get_result();
		$db_zeile = $result->fetch_assoc();
		$statement->close();
		if($db_zeile === NULL){
		    return "Zugriff verweigert";
		}
		//loginname und id des Nutzers auslesen
		$id = $db_zeile['id'];
		$passwortDB = $db_zeile['passwort'];
		if($id == null || $passwortDB != $passwortSHA1){
			return "Zugriff verweigert.";
		}
		$GLOBALS['z_user_id'] = $id;
		return "OK";
	}
	
	/**
	 * traegt ein Passwort in die DB ein, vorausgesetzt es genuegt den Anforderungen.
	 * @param  $passwort
	 * @return 
	 */
	function z_passwort_in_db_eintragen($passwort){
		global $z_user_id, $z_user_mit_db_prefix,$z_db_verbindung;
		$passwort = z_db_text_erzeugen($passwort);
		if(z_passwort_enthaelt_ungeeignete_zeichen($passwort)){
		    return "Passwort darf keine Leerzeichen, Dollarzeichen, Semikolons, Anfuehrungszeichen und Backslashes enthalten.";
		}
		if(strlen($passwort) < 5){
			return "Das Passwort muss mindestens 5 Zeichen haben!";
		}
		$passwortSHA1 = SHA1($passwort);
		$statement = $z_db_verbindung->prepare(
		  "UPDATE $z_user_mit_db_prefix SET passwort =? WHERE id =?"
		);
		$statement->bind_param("si",$passwortSHA1,$z_user_id);
		$statement->execute();
		$statement->close();
		return "Passwort wurde ge&auml;ndert!";
		
	}

	/**
	 * aendert das Passwort, wenn es den Anforderungen genuegt.
	 * @param  $bisheriges_passwort
	 * @param $passwort1
	 * @param $passwort2
	 * @return string Ergebnismeldung
	 */
	function z_passwort_aendern($bisheriges_passwort, $passwort1, $passwort2){
		global $z_user_id, $z_user_mit_db_prefix, $z_db_verbindung;
		if($passwort1 != $passwort2){
		    return "Die neuen Passw&ouml;rter stimmen nicht &uuml;berein!";
		}
		if(z_passwort_enthaelt_ungeeignete_zeichen($passwort1)){
		    return "Passwort darf keine Leerzeichen, Dollarzeichen, Semikolons, Anfuehrungszeichen und Backslashes enthalten.";
		}
		$statement = $z_db_verbindung->prepare(
		    " SELECT passwort FROM $z_user_mit_db_prefix WHERE id = ?"
		);
		$statement->bind_param("i",$z_user_id);
		$statement->execute();
		$result = $statement->get_result();
		$zeile = $result->fetch_assoc();
		$statement->close();
		if($zeile['passwort'] != SHA1($bisheriges_passwort)){
			return "Das bisherige Passwort stimmt nicht!";
		}
		return z_passwort_in_db_eintragen($passwort1);
	}
?>