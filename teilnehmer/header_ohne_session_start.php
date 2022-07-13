<?php
	if(!isset($z_require_erlaubt) || $z_require_erlaubt != true){ 
		echo("<font color='red'><p>header_ohne_session_start.php: Unerlaubter Aufruf des Skriptes!</p></font><br/>\n");
		die();
	}	

	// z_user wird aus dem Verzeichnis abgeleitet, in dem die Datei liegt!
	$z_user = 	basename(dirname(__FILE__));
	
	require('../include/config.php');
	$z_user_mit_db_prefix = $z_config_db_tabellen_prefix.$z_user;
	
	require_once('../include/db_verbindung.php');
	require_once('../include/session_funktionen.php');
	require_once('../include/werkzeuge.php');
	require_once('../include/konsole.php');
	require_once('../include/style.php');
	$z_style_aktueller_style = z_style_aktueller_style();
	
	date_default_timezone_set("Europe/Berlin");
	
	
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		  <noscript>
		    <meta http-equiv="refresh" content="0; noscript.php" />
		  </noscript>		
		<script  type="text/javascript"  src="../include/jquery-3.2.1.min.js"></script>	
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1" />		
		<link rel="stylesheet" type="text/css" href="../style/<?php echo "$z_style_aktueller_style"; ?>"/>
		<title><?php echo "$z_config_projekt: $z_user"; ?></title>
	</head>
	<body>
	
	<div class="wrapper">
		<div class="innerwrapper">	
		<br/>
			<div class="wrappermainav">
				<div id="mainnavwrapper">
					<ul id="mainlevel-nav">
					
<?php 
	require 'menu.php';
?>
					</ul>
				<div id="mainnavwrapclear"></div>
			</div>
		</div>

