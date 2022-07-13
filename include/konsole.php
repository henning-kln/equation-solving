<?php

	/**
	 * z_konsole kann man mit einem oder zwei Parametern aufrufen:
	 * ein Parameter: Variable, die ausgegeben werden soll.
	 * zwei Parameter: erst eine Erklaerung (String!), dann die Variable.
	 * In config.php wird festgelegt, ob die Konsolenausgabe gemacht wird oder nicht.
	 */
	function z_konsole(){
		global $z_config_konsole_ausgabe;
		if($z_config_konsole_ausgabe){
			$anzahl_argumente = func_num_args();
			if($anzahl_argumente == 0){
				z_html_warnung("FEHLER in z_konsole(...): Parameter fehlen!");
				return;
			}
			if($anzahl_argumente == 1){
				$erklaerung = "";
				$variable = func_get_arg(0);
			}
			if($anzahl_argumente == 2){
				$erklaerung = func_get_arg(0);
				$variable = func_get_arg(1);
			}
			if($anzahl_argumente > 2){
				echo "<font color='red'>FEHLER in z_konsole(...): mehr als 2 Parameter!</font>\n";
				return;
			}
				
			if($erklaerung != ""){
				$erklaerung = $erklaerung.": ";
			}
			echo "<font color='red'>";
			if(is_array($variable)){
				echo "--- $erklaerung\n";
				print_r($variable);
				echo " ---";
			}
			else{
				echo"--- ".$erklaerung.strval($variable)." ---";
			}
			echo "</font><br/>\n";
		}
	}
	

?>