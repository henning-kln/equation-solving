<?php
	$z_require_erlaubt = true;
	require('header.php');
	require('../include/passwort.php');	
	
	if(isset($_POST['nickname'])){
	    $nickname = z_db_text_erzeugen($_POST['nickname']);
	    if(strlen($nickname)<2){
	        echo "<p><font color='red'><h1>Der Nickname muss mind. 2 Buchstaben haben.</h1></font></p>\n";
	    }
	    else{
	       $update_statement = "UPDATE equ_teacher SET nickname = '$nickname' WHERE id = '$z_user_id'";
	       z_db_sql_statement_ausfuehren($update_statement);
	       echo "<p><font color='red'><h1>neuer Nickname: $nickname</h1></font></p>\n";
	    }
	}
	
	if(isset($_POST['altes_passwort']) &&isset($_POST['passwort1']) && isset($_POST['passwort2'])){
	    if(strlen($_POST['altes_passwort']) > 0){
	       $ergebnis = z_passwort_aendern($_POST['altes_passwort'], $_POST['passwort1'], $_POST['passwort2']);
		   echo "<p><font color='red'><h1>$ergebnis</h1></font></p>\n";
	    }
	}
?>
		<h1>Darstellung &auml;ndern</h1>
		<p>
			bevorzugte Darstellung: 
			<select id="style_select" name="style" size="1">
				<option value='<?php echo $z_style_mobile; ?>' <?php if($z_style_aktueller_style == $z_style_mobile){echo 'selected';} ?>>
					Smartphone
				</option>
				<option value='<?php echo $z_style_desktop; ?>' <?php if($z_style_aktueller_style == $z_style_desktop){echo 'selected';} ?>>
					Desktop oder Tablet
				</option>
			</select>
		</p>

		<script type="text/javascript">
			var select = document.getElementById('style_select');
			
			function displaySpeichern(gewaehlter_style){
			    $.get("../include/style_change.php",
			    	    {style:gewaehlter_style},
			    	    function(data, status){
			    	       location.reload();
			    	    });  	    
			}
			
			select.onchange = function() {
				theStyle = this.options[this.selectedIndex].value;
			    displaySpeichern(theStyle);
			}			
		</script>


		<h1>Anzeigename / Passwort &auml;ndern</h1>
<?php
		$query = "
			SELECT u.login, u.token, u.nickname
			FROM $z_user_mit_db_prefix u
			WHERE u.id = '$z_user_id'";
	
		$ergebnis=z_db_sql_statement_ausfuehren($query);
		$zeile = z_db_zeile_auslesen($ergebnis);
		
		$login = $zeile['login'];
		$nickname = $zeile['nickname'];
		$token = $zeile['token'];
?>
		<form action="einstellungen.php" method="POST">		
			<table style="background-color:white">
				<tr>
					<td><b>Login-Name:</b></td>
					<td><?php echo $login; ?></td>
				</tr>
				<tr>
					<td><b>Anzeige-Name (z.B. Lehrerk&uuml;rzel):</b><br/><i>Den sehen die Sch&uuml;ler</i></td>
					<td><input type="text" name="nickname" value="<?php echo $nickname; ?>" /></td>
				</tr>
				<tr>
					<td><b>bisheriges Passwort:</b></td>
					<td><input type="password" name="altes_passwort" size="20"></td>
				</tr>				
				<tr>
					<td><b>neues Passwort:</b></td>
					<td><input type="password" name="passwort1" size="20"></td>
				</tr>				
				<tr>
					<td><b>neues Passwort wiederholen:</b></td>
					<td><input type="password" name="passwort2" size="20"></td>
				</tr>
			</table>				
			<p><?php z_html_button_submit("&uuml;bernehmen"); ?></p>
					
<?php		
		require("footer.php");
?>
