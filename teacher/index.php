<?php
	$z_require_erlaubt = true;

	// z_user wird aus dem Verzeichnis abgeleitet, in dem die Datei liegt!
	$z_user = 	basename(dirname(__FILE__));
	
	require('../include/config.php');
	$z_user_mit_db_prefix = $z_config_db_tabellen_prefix.$z_user;
	
	require('../include/db_verbindung.php');
	require('../include/werkzeuge.php');
	require('../include/konsole.php');
	require('../include/passwort.php');
		
	if(isset($_POST['login']) && isset($_POST['passwort'])){
		$login = $_POST['login'];
		$ergebnis = z_passwort_pruefen($_POST['login'], $_POST['passwort']);
		if($ergebnis != "OK"){
			require('header_ohne_session_start.php');
			echo "<p><font color='red'><b>$ergebnis</b></font></p>\n";
			z_html_button_link("Zur&uuml;ck", "index.php");
			require('footer.php');	
			die();
		}
		// Anmeldedaten in der Session speichern
		require_once('../include/session_funktionen.php');
		z_session_user_id_eintragen($z_user_id);
		require_once 'header_ohne_session_start.php';
?>		 
		<h1>Willkommen!</h1>
		<p>Hallo <b><?php echo "$login"; ?></b>,<br/>als Lehrer:in kannst du hier neue Aufgaben erstellen und deine Aufgaben anschauen.</p>
		<p>
			<b>...eine neue Aufgabe erstellen:</b><br/>
			Aufgaben k&ouml;nnen aus mehreren Gleichungen bestehen!<br/>
			- <i>neue Aufgabe</i>  im Men&uuml; klicken.<br/>
			- Die Gleichungen nacheinander eingeben.<br/>
			- Bei jeder Gleichung <i>L&ouml;sbarkeit pr&uuml;fen</i> klicken.<br/>
			- Bei jeder Gleichung <i>speichern</i> klicken.<br/><br/>
			<b>Vorsicht beim Speichern:</b><br/>
			Man kann die Gleichungen hinterher nicht mehr ver&auml;ndern!
		</p>
		<p>
			<b>...eine Aufgabe in der Klasse stellen:</b><br/>
			- <i>meine Aufgaben</i> im Men&uuml; klicken<br/>
			- <i>Details</i> klicken.
		</p>
		<p>
			<b>...eine Aufgabe per Mail verschicken:</b><br/>
			- <i>meine Aufgaben</i> im Men&uuml; klicken<br/>
			- <i>Details</i> klicken.<br/>
			- Den Link zur Aufgabe kopieren und den Sch&uuml;ler:innen zuschicken.
		</p>
		<p>
			<b>...die L&ouml;sungswege der Sch&uuml;ler:innen anschauen:</b><br/>
			- <i>meine Aufgaben</i> im Men&uuml; klicken<br/>
			- <i>Details</i> klicken.<br/>
			- Bei <i>Work in progress (unten!) entweder eine Nummer oder alle anklicken.</i>
		</p>
<?php
		require_once("footer.php");
		die();
	}
	else{
		require_once('header_ohne_session_start.php');
?>
		<h1><?php echo "$z_user"; ?>: Login</h1>
		<form action="index.php" method="post">
			<p>
				Loginname:<br>
				<input type="text" size="24" maxlength="50" name="login">
			</p>
			<p>
				Passwort:<br>
				<input type="password" size="24" maxlength="50" name="passwort">
			</p>
			<p>
				<?php z_html_button_submit("login"); ?>
			</p> 
		</form>
<?php 			
		require_once('footer.php');
	}
?>	

