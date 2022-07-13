<?php
	if (! isset ( $z_require_erlaubt ) || $z_require_erlaubt != true) {
		die ( '<p align="center"><font color="red">werkzeuge.php: Unerlaubter Aufruf des Skriptes!</font></p>' );
	}
?>
<?php

    function z_fehlermeldung($fehlertext){
        echo "<p><font color='red'>$fehlertext</font></p>";
    }

	/**
	 * ueberprueft, ob eine Eingabe nur aus Buchstaben und Zahlen besteht.
	 * 
	 * @param object $text        	
	 * @return
	 *
	 */
	function z_text_besteht_nur_aus_buchstaben_und_zahlen($text) {
		if (strlen ( $text ) < 1) {
			return false;
		}
		$match = preg_match ( "/^[a-zA-Z0-9-]+$/", $text );
		if ($match == 0) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * ueberprueft Text auf PHP-Steuerzeichen und SQL-Steuerzeichen
	 * gemeint sind: $ " ' \  ; 
	 *
	 */
	function z_text_enthaelt_steuerzeichen($text){
	    if(strpos($text,"\$") !== false) return true;
	    if(strpos($text,"\"") !== false) return true;
	    if(strpos($text,"'") !== false) return true;
	    if(strpos($text,"\\") !== false) return true;
	    if(strpos($text,";") !== false) return true;
	}
	
	/**
	 * prueft, ob ein Text eine Mailadresse ist.
	 * @param  $mail
	 */
	function z_text_mailadresse($mail) {
		// Regex zum Filtern von falschen E-Mail Adressen
		if (! ereg ( '^[A-Za-z0-9]+([-_.]?[A-Za-z0-9])+@[A-Za-z0-9]+([-_.]?[A-Za-z0-9])+.[A-Za-z]{2,4}', $mail )) {
			return false;
		}
		return true;
	}
	
	/**
	 * gibt das aktuelle Datum zurueck
	 * Format yyyy-mm-dd, z.B.
	 * 2012-02-28
	 */
	function z_zeit_heute() {
		date_default_timezone_set ( "Europe/Berlin" );
		$timestamp = time ();
		$datum = date ( "Y-m-d", $timestamp );
		return $datum;
	}
	
	/**
	 * gibt das aktuelle Datum zurueck
	 * Format yyyy-mm-dd, z.B.
	 * 2012-02-28
	 */
	function z_zeit_jetzt() {
		date_default_timezone_set ( "Europe/Berlin" );
		$timestamp = time ();
		$uhrzeit = date("H:i",$timestamp);
		return $uhrzeit;
	}
	
	
	/**
	 * berechnet eine timestamp aus Datum und Zeit
	 * Beispielformat fuer das Datum: 31.06.2012
	 * Beispielformat fuer die Zeit: 17.00
	 */
	function z_zeit_timestamp($datum, $zeit) {
		$timestamp = strtotime ( $datum . " " . $zeit );
		return $timestamp;
	}
	
	/**
	 * berechnet ein datenbankfaehiges Datum aus einem timestamp
	 * Format fuer das Ergebnis: 2012-06-31
	 */
	function z_zeit_datum_fuer_db($timestamp) {
		$datum = date ( "Y-m-d", $timestamp );
		return $datum;
	}
	
	
	/**
	 * berechnet ein datenbankfaehige Zeit aus einem timestamp
	 * Format fuer das Ergebnis: 17:00:00
	 */
	function z_zeit_fuer_db($timestamp) {
		$zeit = date ( "H:i:s", $timestamp );
		return $zeit;
	}

	/**
	 * erzeugt aus einem datenbank-timestamp Datum und Uhrzeit im europaeischen format
	 * @param  $timestamp
	 * @return string
	 */
	function z_datum_zeit_aus_db($timestamp){
	   $datetime = DateTime::createFromFormat ( "Y-m-d H:i:s", $timestamp);
	   return $datetime->format('d.m.y, H:i:s');
	}
	
	/**
	 * erzeugt aus einem datenbank-timestamp Datum im europaeischen format
	 * @param  $timestamp
	 * @return string
	 */
	function z_datum_aus_db($timestamp){
	    if($timestamp == null){
	        return "---";
	    }
	    $datetime = DateTime::createFromFormat ( "Y-m-d H:i:s", $timestamp);
	    return $datetime->format('d.m.y');
	}
	
	function z_token_erzeugen($laenge)
	{
		$lng = $laenge;
		mt_srand(crc32(microtime()));
	
		//Welche Buchstaben benutzt werden sollen (Charset)
		//$buchstaben = "abcdefghijkmnpqrstuvwxyz123456789";
		$buchstaben = "0123456789";
		
		$str_lng = strlen($buchstaben)-1;
		$rand= "";
	
		for($i=0;$i<$lng;$i++)
			$rand.= $buchstaben[mt_rand(0, $str_lng)];
	
	
			return $rand;
	}
	
	function z_token_existiert_in($token, $tabelle){
		$query = "
		SELECT t.id AS id, t.verbraucht AS verbraucht
		FROM $tabelle t
		WHERE t.token = '$token'";
	
		$ergebnis=z_db_sql_statement_ausfuehren($query);
	
		$zeile = z_db_zeile_auslesen($ergebnis);
	
		$id = $zeile['id'];
		if($id != null && $id != ""){
			return true;
		}
		return false;
	}
	
	/**
	 * findet einen noch nicht vorhandenen Token in einer Datenbanktabelle
	 * und gibt ihn zurueck.
	 * Dafuer werden max. 1000 Versuche unternommen.
	 * @param $tabelle
	 * @param $laenge
	 * @return string
	 */
	function z_token_finden($tabelle, $laenge) {
		for($i=0; $i < 1000; $i++) 
			$neuerToken = z_token_erzeugen ($laenge);
			$test_query = "SELECT id FROM $tabelle WHERE token = '$neuerToken'";
			$test_ergebnis = z_db_sql_statement_ausfuehren ( $test_query );
			$test_zeile = z_db_zeile_auslesen ( $test_ergebnis );
			if ($test_zeile == null) {
				return $neuerToken;
			}
			return null;
		}
	
	/**
	 * erzeugt einen hidden input
	 */
	function z_html_input_hidden($variablenName, $value){
		echo "<input type='hidden' name='$variablenName' value='$value' />\n";
	}
	
	/**
	 * erzeugt einen Button, der einen Link ersetzen kann.
	 * (Das notwendige Formular wird direkt mit erzeugt.)
	 * @param $button_text
	 * @param $link_adresse
	 */
	function z_html_button_link($button_text, $link_adresse){
		echo "<form action='$link_adresse' method='post'>\n";
		echo "<button class='button' type='submit'>$button_text</button>\n";
		echo "</form>";	
	}
	
	function z_html_button_submit($text){
		echo "<button class='button' type='submit'>$text</button>\n";
	}
	
	/**
	 * gibt einen Text als Warnung aus (d.h. in rot)
	 * @param $text
	 */
	function z_html_warnung($text){
		echo "<p><font color='red'><b>$text</b></font></p>\n";
	}

	
		
	/**
	 * 
	 * BISHER NICHT GETESTET!!!
	 * Mit dieser Funktion wird ein AJAX-Aufruf bei "blur" gestartet, 
	 * d.h. z.B. wenn der User ein Input-Feld verlaesst  
	 * @param $input_id: die id des input-tags, aus dem Infos ausgelesen werden.
	 * @param $url: die URL, die mit diesen Daten aufgerufen werden sollen.
	 * @param $GET_OR_POST: "GET" oder "POST"
	 * @param $variable_name: Name der Variable, die mit GET bzw. POST uebergeben wird. Der Wert wird aus dem Input-Tag ausgelesen.
	 * @param $zusaetzliche_variablen_array: Zusaetzliche Variablen, die mit GET bzw. POST uebergeben werden. Kann auch NULL sein.
	 * @param $output_id: die id des output-tags, d.h. wo die Ergebnisse reingeschrieben werden.
	 */
	function z_ajax_blur($input_id, $url, $GET_OR_POST, $variable_name, $zusaetzliche_variablen_array, $output_id){
?>	
		<script type="text/javascript">	
		    $(document).ready(function(){
				$("#<?php echo $input_id;?>").blur(function(){
					//alert(this.value);
					theValue = this.value
					//alert(theValue);
					theInput = this;
					theOutput = $("#<?php echo $output_id?>");
					$.ajax({
						url: '<?php echo $url; ?>',
						type: '<?php echo strtoupper($GET_OR_POST); ?>',
						data: { 
							<?php echo $variable_name; ?>:theValue
							<?php 		
								if($zusaetzliche_variablen_array != NULL){
									foreach ($zusaetzliche_variablen_array as $variable => $value) {
										echo ",$variable:\"$value\"";
									}
								}
							?>	 
						},
						success: function(result){
							theOutput.html(result);
						},
						error: function(XMLHttpRequest, textStatus, errorThrown){ 
			                //alert("Status: " + textStatus); alert("Error: " + errorThrown);
							theOutput.value='FEHLER!';
						} 		                
					});
				});
		    });
		</script>
<?php 		
	}

	/**
	 * Mit dieser Funktion wird ein AJAX-Aufruf beim Anklicken eines Buttons gestartet, 
	 * @param $input_id: die id des input-tags, aus dem Infos ausgelesen werden.
	 * @param $url: die URL, die mit diesen Daten aufgerufen werden sollen.
	 * @param $GET_OR_POST: "GET" oder "POST"
	 * @param $variablen_array: Zusaetzliche Variablen, die mit GET bzw. POST uebergeben werden. Kann auch NULL sein.
	 * @param $output_id: die id des output-tags, d.h. wo die Ergebnisse reingeschrieben werden.
	 */
	function z_ajax_click($button_id, $url, $GET_OR_POST, $variablen_array, $output_id){
		$komma = "";
?>	
		<script type="text/javascript">	
		    $(document).ready(function(){
				$("#<?php echo $button_id;?>").click(function(){
					//alert(this.value);
					theValue = this.value
					//alert(theValue);
					theInput = this;
					theOutput = $("#<?php echo $output_id?>");
					$.ajax({
						url: '<?php echo $url; ?>',
						type: '<?php echo strtoupper($GET_OR_POST); ?>',
						data: { 
							<?php 		
								if($variablen_array != NULL){
									foreach ($variablen_array as $variable => $value) {
										echo $komma."$variable:\"$value\"";
										$komma=",";
									}
								}
							?>	 
						},
						success: function(result){
							theOutput.html(result);
						},
						error: function(XMLHttpRequest, textStatus, errorThrown){ 
			                //alert("Status: " + textStatus); alert("Error: " + errorThrown);
							theOutput.value='FEHLER!';
						} 		                
					});
				});
		    });
		</script>
<?php 		
	}
	
	
	/**
	 * @param $input_id: die id des input-tags, aus dem Infos ausgelesen werden.
	 * @param $url: die URL, die mit diesen Daten aufgerufen werden sollen.
	 * @param $GET_OR_POST: "GET" oder "POST"
	 * @param $variable_name: Name der Variable, die mit GET bzw. POST uebergeben wird. Der Wert wird aus dem Input-Tag ausgelesen.
	 * @param $zusaetzliche_variablen_array: Zusaetzliche Variablen, die mit GET bzw. POST uebergeben werden. Kann auch NULL sein.
	 * @param $output_id: die id des output-tags, d.h. wo die Ergebnisse reingeschrieben werden.
	 */
	function z_ajax_enter($input_id, $url, $GET_OR_POST, $variable_name, $zusaetzliche_variablen_array, $output_id){		
?>	
		<script type="text/javascript">	
		    $(document).ready(function(){
				$("#<?php echo $input_id;?>").keydown(function (e){
				    if(e.keyCode == 13){
						theValue = this.value
						//alert(theValue);
						theInput = this;
						theOutput = $("#<?php echo $output_id?>");
						$.ajax({
							url: '<?php echo $url; ?>',
							type: '<?php echo strtoupper($GET_OR_POST); ?>',
							data: { 
								<?php 
									echo "$variable_name:theValue";
									if($zusaetzliche_variablen_array != NULL){
										foreach ($zusaetzliche_variablen_array as $variable => $value) {
											echo ",$variable:\"$value\"";
										}
									}
								?>		 
							},
							success: function(result){
								theOutput.html(result);
							},
							error: function(XMLHttpRequest, textStatus, errorThrown){ 
				                //alert("Status: " + textStatus); alert("Error: " + errorThrown);
								theOutput.value='FEHLER!';
							} 		                
						});
				    }
				});
		    });
		</script>
<?php 		
	}
	
	/**
	 * @param $radio_checkbox_id: die id des radio- oder checkbox-tags, der geaendert wird.
	 * @param $url: die URL, die mit diesen Daten aufgerufen werden sollen.
	 * @param $GET_OR_POST: "GET" oder "POST"
	 * @param $variable_name: Name der Variable, die mit GET bzw. POST uebergeben wird. Als Wert wird 0 bzw. 1 uebergeben.
	 * @param $zusaetzliche_variablen_array: Zusaetzliche Variablen, die mit GET bzw. POST uebergeben werden. Kann auch NULL sein.
	 * @param $output_id: die id des output-tags, d.h. wo die Ergebnisse reingeschrieben werden.
	 */
	function z_ajax_change($radio_checkbox_id, $url, $GET_OR_POST, $variable_name, $zusaetzliche_variablen_array, $output_id){		
?>	
		<script type="text/javascript">	
		    $(document).ready(function(){
				$("#<?php echo $radio_checkbox_id;?>").change(function(){
					checked_value = 0;
					if($(this).is(":checked")) { 
						checked_value = 1;
					}
					theOutput = $("#<?php echo $output_id?>");
					$.ajax({
						url: '<?php echo $url; ?>',
						type: '<?php echo strtoupper($GET_OR_POST); ?>',
						data: { 
							<?php 
								echo "$variable_name:checked_value";
								if($zusaetzliche_variablen_array != NULL){
									foreach ($zusaetzliche_variablen_array as $variable => $value) {
										echo ",$variable:\"$value\"";
									}
								}
							?>		 
						},
						success: function(result){
							theOutput.html(result);
						},
						error: function(XMLHttpRequest, textStatus, errorThrown){ 
			                //alert("Status: " + textStatus); alert("Error: " + errorThrown);
							theOutput.value='FEHLER!';
						} 		                
					});
				});
		    });
		</script>
<?php 		
	}

	/**
	 * schickt ein Formular mit AJAX ab.
	 * @param $form_id die id des Formulars
	 * @param $url die Seite, die aufgerufen wird
	 * @param $GET_OR_POST 'GET' oder 'POST'
	 * @param $output_id die id des Elements, in dem das Ergebnis angezeigt werden soll.
	 */
	function z_ajax_form_submit($form_id, $url, $GET_OR_POST, $output_id){
?>
	    <script type="text/javascript">
	      $(document).ready(function(){
	        form = $("#<?php echo $form_id;?>");  
	        form.submit(function(e){
	          e.preventDefault();
	          $.<?php echo strtolower($GET_OR_POST);?>("<?php echo $url;?>",form.serialize(),function(msg){
	            $("#<?php echo $output_id;?>").html(msg);
	          });
	        });
	      });
	    </script>		
<?php 		
	}
?>