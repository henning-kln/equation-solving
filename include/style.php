<?php

	/**
	 * gibt die Seite zurueck, z.B. "chat.php"
	 * @return mixed
	 */
	function z_style_get_aktuelle_seite(){
		$seiteURI = $_SERVER['REQUEST_URI'];
		$splits = explode("/",$seiteURI);
		$seite = $splits[count($splits)-1];
		if($seite == '' || $seite == ' '){
			$seite = 'index.php';
		}
		//echo "Seite: $seite<br/>";
		return $seite;
	
	}
	
	function z_style_ist_aktuelle_seite($seite){
		return (z_style_get_aktuelle_seite() == $seite); 
	}
	
	
	/*
	 * gibt den aktuell verwendeten Style zurueck.
	 * Schaut dafuer in $_COOKIE['style'] nach.
	 */
	function z_style_aktueller_style(){
		global $z_style_mobile;
		
		// default style: mobile
		$the_style = $z_style_mobile;
		
		// im cookie nachschauen, ob schon ein style gespeichert ist!
		if(isset($_COOKIE['style'])){
			$the_style = $_COOKIE['style'];
		}
		return $the_style;
	}	
?>