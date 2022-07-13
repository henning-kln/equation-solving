<?php
function z_session_user_id_eintragen($id) {
	//z_konsole("z_session_user_id_eintragen($id)");
	global $z_user,$z_user_mit_db_prefix, $_SESSION;
	session_start();
	// Anmeldedaten in der Session speichern
	$z_user_id_bezeichnung = $z_user . "_id";
	$_SESSION [$z_user_id_bezeichnung] = $id;
	
	// login in Session eintragen
	$db_query = "SELECT login FROM $z_user_mit_db_prefix WHERE id = $id";
	//konsole("z_session_user_id_eintragen",$db_query);
	$ergebnis = z_db_sql_statement_ausfuehren($db_query);
	$zeile = z_db_zeile_auslesen($ergebnis);
	$login = $zeile['login'];
	$z_user_login_bezeichnung = $z_user."_login";
	$_SESSION[$z_user_login_bezeichnung] = $login;

	//z_user_id und z_user_login als globale Variablen festlegen.
	$GLOBALS["z_user_id"] = $id;
	$GLOBALS["z_user_login"] = $login;
	
	
	//z_konsole("SESSION[$z_user_id_bezeichnung] = $id");
	$timestamp_bezeichnung = $z_user . "_timestamp";
	// timestamp in der Session setzen
	$_SESSION [$timestamp_bezeichnung] = time ();
}

function z_session_get_user_id() {
	global $z_user, $_SESSION;
	$z_user_id_bezeichnung = $z_user . "_id";
	//echo "z_user: $z_user<br/>";
	//echo "z_user_id_bezeichnung: $z_user_id_bezeichnung<br/>";
	//z_konsole($_SESSION);
	$id = null;
	if(isset($_SESSION[$z_user_id_bezeichnung])){
		$id = $_SESSION[$z_user_id_bezeichnung];
	}
	else{
		//echo "keine id in der Session gespeichert!<br/>\n";
	}
	return $id;
}

function z_session_get_user_login() {
	global $z_user, $_SESSION;
	$z_user_login_bezeichnung = $z_user . "_login";
	$login = null;
	if(isset($_SESSION[$z_user_login_bezeichnung])){
		$login = $_SESSION[$z_user_login_bezeichnung];
	}
	else{
		//echo "kein login in der Session gespeichert!<br/>\n";
	}
	return $login;
}

function z_session_status_print() {
	$status = session_status ();
	echo "session_status(): ";
	if ($status == PHP_SESSION_DISABLED) {
		echo $status . ": disabled";
	} else if ($status == PHP_SESSION_NONE) {
		echo $status . ": none";
	} else if ($status == PHP_SESSION_ACTIVE) {
		echo $status . ": active";
	} else {
		echo $status . ": unknown!";
	}
	
	echo "<br/>\n";
}

function z_session_start() {
	global $z_user, $_SESSION;
	
	session_start ();
// 	z_konsole("z_session_start");
// 	z_konsole($_SESSION);
	
	// $z_user_id und $z_user_login aus der Session auslesen und als Globale Variable anbieten.
	$z_user_id = z_session_get_user_id();
	$GLOBALS["z_user_id"] = $z_user_id;
	//echo ("z_session_start(): z_user_id = $z_user_id<br/>");
	$z_user_login = z_session_get_user_login();
	$GLOBALS["z_user_login"] = $z_user_login;
	
	// ueberpruefen, ob es $z_user_id gibt
	if ($z_user_id == NULL || $z_user_id < 0 || $z_user_id == "") {
// 		$seitenname = basename($_SERVER['PHP_SELF']);
// 		if($seitenname != "index.php"){
			session_destroy ();
			$z_require_erlaubt = true;
			require("../$z_user/header_ohne_session_start.php");
			echo ("<h1>Erst einloggen!</h1>\n");
			echo "<a href='index.php'>zum Login</a>\n";
			$z_require_erlaubt = true;
			require ('../include/config.php');
			require_once ("../$z_user/footer.php");
			die ();
// 		}
	}
	
	if (z_session_timeout_erreicht()) {
		//echo "Session wird zerstoert!<br/>\n";
		session_destroy ();
		z_konsole("2");
		$z_require_erlaubt = true;
		require("../$z_user/header_ohne_session_start.php");
		echo ("<h1>Zeit&uuml;berschreitung!</h1>");
		echo "<a href='index.php'>zum Login</a>";
		require_once ("../$z_user/footer.php");
		die ();
	}
	
}

/**
 * ueberprueft, ob ein Timeout erreicht ist.
 * wenn kein Timeout: setzt ggf. $_SESSION['timestamp'] neu, return false
 * wenn Timeout: return true.
 */
function z_session_timeout_erreicht(){
	global $z_user, $_SESSION;
	$timestamp_bezeichnung = $z_user . "_timestamp";
	$z_config_timeout_in_seconds = 10800;
	$jetzt = time();
	//echo "timeout_in_seconds: ".$z_config_timeout_in_seconds;
	if(!isset($_SESSION[$timestamp_bezeichnung])){
		$_SESSION[$timestamp_bezeichnung] = $jetzt;
	}
	$session_time = $_SESSION[$timestamp_bezeichnung];
	//echo "timestamp_bezeichnung: $timestamp_bezeichnung<br/>\n";
	//echo "session_time: $session_time<br/>\n";
	$timeoutTime = $session_time + $z_config_timeout_in_seconds;
	//echo "timeoutTime: $timeoutTime<br>";
	if( $timeoutTime < $jetzt) {
		return true;
	}
	else{
		$_SESSION[$timestamp_bezeichnung] = $jetzt;
		return false;
	}
}
?>