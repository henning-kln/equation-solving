<?php
	$z_require_erlaubt = true;

	require_once('header.php');	
   
	$db_query = "SELECT login FROM $z_user_mit_db_prefix WHERE id = $z_user_id";
	//echo "$db_query<br/>";
	$ergebnis = z_db_sql_statement_ausfuehren($db_query);
	$zeile = z_db_zeile_auslesen($ergebnis);
	$login = $zeile['login'];
	
    session_destroy();
   
   echo "<h1>Auf Wiedersehen $login!</h1> ";
   echo "<a href='index.php'>wieder einloggen</a>";
   
   require_once("footer.php");
   
?>