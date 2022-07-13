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

<!--
Copyright (C) 2021  Andreas Kaibel

This program is free software; you can redistribute it and/or modify it 
under the terms of the GNU General Public License as published by the Free Software Foundation; 
either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
See the GNU General Public License for more details.

For a copy of the GNU General Public License see <http://www.gnu.org/licenses/>. 

Get source-code at GitHub: https://github.com/akaibel/equation-solving
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
	<head>
		  <noscript>
		    <meta http-equiv="refresh" content="0; noscript.php" />
		  </noscript>		
		<script  type="text/javascript"  src="../include/jquery-3.2.1.min.js"></script>	
<?php 
    require_once('../include_math/math_scripts.php');
?>	
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1" />		
		<link rel="stylesheet" type="text/css" href="../style/<?php echo "$z_style_aktueller_style"; ?>"/>
		<link rel="stylesheet" href="../style/main.css">

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

