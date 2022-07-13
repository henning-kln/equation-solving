<?php
if(!isset($z_require_erlaubt) || $z_require_erlaubt != true){
    die('<p align="center"><font color="red">config.php: Unerlaubter Aufruf des Skriptes!</font></p>');
}
?>
<?php
    // Datenbankverbindung: Server
    $z_config_db_server='127.0.0.1';
    $z_config_db_user='web126669';
    $z_config_db_passwort='dzbwwdmsg-9';
    $z_config_db_name='usr_web126669_2';

    //Prefix der Datenbanktabellen.
    //Das Prefix wird vor allem fuer Tabellen benoetigt, die User speichern, 
    //wie z.B. 'teilnehmer' oder 'admin'
    $z_config_db_tabellen_prefix = 'equ_';

    // der Name des Projektes, wie er den Nutzern angezeigt wird.
    $z_config_projekt = "Gleichungen l&ouml;sen";
    
    // das Verzeichnis, in dem das Projekt liegt.
    $z_config_root_verzeichnis = 'https://sibiwiki.de/gleichungen/';
    
	//legt fest, ob die Methode z_konsole(...) etwas ausgibt oder nicht.
    $z_config_konsole_ausgabe = true;
    
	//Timeout der Session in Sekunden
	$z_config_timeout_in_seconds = 10800;
	
	$z_config_mailadresse = 'kaibel@sibi-badhonnef.de';
	
	// legt fest, ob Token nur einmal verwendet werden duerfen.
	$z_config_token_nur_einmal = true;
	

	//styles
	$z_style_mobile = 'style_mobile.css';
	$z_style_desktop = 'style_desktop.css';
	
?>