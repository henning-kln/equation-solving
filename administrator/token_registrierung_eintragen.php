<?php
	$z_require_erlaubt = true;

	require('header_ohne_session_start.php');
	require('../include/passwort.php');
	
	$token = ($_POST["token"]);
	$login = ($_POST["login"]);
	$passwort = ($_POST["passwort1"]);
	$passwort2 = ($_POST["passwort2"]);	
	function zurueckButtonAusgeben(){
		global $token, $login, $passwort;
?>
	<form action='token_registrierung.php' method='post'>
<?php
		z_html_input_hidden("token", $token);
		z_html_input_hidden("login", $login);
		z_html_input_hidden("passwort", $passwort);
		z_html_input_hidden("postDaten", "ja");
		z_html_button_submit("Zur&uuml;ck");
?>		
	</form>
<?php 		
	}
	
	if(z_passwort_enthaelt_ungeeignete_zeichen($login) == true){
	    z_html_warnung("Der Login-Name darf keine Leerzeichen, Dollarzeichen, Semikolons, Anfuehrungszeichen und Backslashes enthalten.");
	    zurueckButtonAusgeben();
	    require_once 'footer.php';
	    die();
	}
	
	if(strlen($login) < 3)
	{
	    z_html_warnung("Der Login-Name muss mindestens 3 Zeichen haben!");
	    zurueckButtonAusgeben();
	    require_once 'footer.php';
	    die();
	}
	
	// Ueberpruefen, ob die Passwoerter identisch sind.
	if($passwort != $passwort2)
	{
	    z_html_warnung("Die Passw&ouml;rter sind nicht identisch."); 
		zurueckButtonAusgeben();
	  	require_once 'footer.php';
		die();
	}
	
	if(z_passwort_enthaelt_ungeeignete_zeichen($passwort) == true){
      z_html_warnung("Das Passwort darf keine Leerzeichen, Dollarzeichen, Semikolons, Anfuehrungszeichen und Backslashes enthalten.");
	  zurueckButtonAusgeben();
	  require_once 'footer.php';
	  die();		
	}
	
	if(strlen($passwort) < 5)
	{
		z_html_warnung("Das Passwort muss mindestens 5 Zeichen haben!");
		zurueckButtonAusgeben();
	  	require_once 'footer.php';
		die();
	}
	
	// checken, ob der Login-Name schon vergeben ist.
	$statement = $z_db_verbindung->prepare(
	   " SELECT u.id AS id FROM $z_user_mit_db_prefix u WHERE u.login = ? AND u.token != ?"
    );
	$statement->bind_param("ss",$login,$token);
	$statement->execute();
	$result = $statement->get_result();
	$zeile = $result->fetch_assoc();
	$statement->close();
	if($zeile != null){
		z_html_warnung("Das Login $login ist schon vergeben!");
		zurueckButtonAusgeben();
		require_once 'footer.php';
		die();
	}
	
	$passwortSHA1 = SHA1($passwort);

	// checken, ob es den Nutzer schon gibt
	$statement = $z_db_verbindung->prepare( 
		" SELECT u.id AS id FROM $z_user_mit_db_prefix u ".
		" WHERE u.token = ?"
	);
	$statement->bind_param("s",$token);
	$statement->execute();
	$result = $statement->get_result();
	$zeile = $result->fetch_assoc();
	$statement->close();
	if($zeile == null){
	    z_html_warnung("Ung&uuml;ltiges Token!");
	    zurueckButtonAusgeben();
	    require_once 'footer.php';
	    die();
	    
	}

	$id = $zeile['id'];
	
	$statement = $z_db_verbindung->prepare(
	    "UPDATE $z_user_mit_db_prefix SET passwort = ?, login = ?, token_verbraucht = 1 WHERE token = ?"
	);
	$statement->bind_param("sss",$passwortSHA1,$login,$token);
	$statement->execute();
	$statement->close();
	
// 	$update_query = "
// 		UPDATE $z_user_mit_db_prefix
// 		SET passwort = '$passwortSHA1', login = '$login', token_verbraucht = 1
// 		WHERE token = '$token'
// 	";	
	//echo "$update_query<br/>\n";
//	$ergebnis=z_db_sql_statement_ausfuehren($update_query);
?>
	<p>
		<b>Du hast dich erfolgreich registriert!</b><br/>
		<i>Mach am besten ein Foto von dieser Seite - dann hast du deine Daten!
	</p>
	<p>
		<table border=1>
			<tr>
				<td>Link:</td>
				<td><?php echo "$z_config_root_verzeichnis"; echo "$z_user"; echo "/index.php"?></td>
			</tr>
			<tr>
				<td>Login:</td>
				<td><?php echo "$login"; ?></td>
			</tr>
<?php 
			if($z_config_token_nur_einmal == false){
?>
				<tr>
					<td>Token (wenn du das Passwort-Vergessen hast...):</td>
					<td><?php echo "$token"; ?></td>
				</tr>
				<tr>
					<td>Link zur Token-Registrierung:</td>
					<td><?php echo "$z_config_root_verzeichnis"; echo "$z_user"; echo "/token.php"?></td>
				</tr>
<?php 
			}
?>
		</table>

	<form action='index.php' method='post'>
		<p>
			<button class='button' type='submit'>
				<b>Zum Login</b>
			</button>
		</p>
	</form>	
<?php	
	require("footer.php");

?>