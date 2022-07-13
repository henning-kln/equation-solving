<?php
	$z_require_erlaubt = true;
	require("header_ohne_session_start.php");
?>
<?php
	$token = "";
	if(isset($_POST['token'])){
		$token = z_db_text_erzeugen($_POST['token']);
	}
	
	$post_daten = "nein";
	if(isset($_POST['postDaten'])){
		$post_daten = "ja";
		$post_login = z_db_text_erzeugen($_POST['login']);	
		$post_nickname = z_db_text_erzeugen($_POST['nickname']);
		$post_passwort = z_db_text_erzeugen($_POST['passwort']);	
	}
?>


<?php

	$statement= $z_db_verbindung->prepare(
	    "SELECT u.id AS id, u.login AS login, u.nickname AS nickname, u.passwort AS passwort, u.token_verbraucht AS verbraucht
	    FROM $z_user_mit_db_prefix u
	    WHERE u.token = ?"
	    );
	$statement->bind_param("s",$token);
	$statement->execute();
	$result = $statement->get_result();
	$zeile = $result->fetch_assoc();
	$statement->close();
	
	// kein ERgebnis -> Das Token gibt es nciht.
	if($zeile == null){
		z_html_warnung("ung&uuml;ltiges Token!");
		z_html_button_link("Zur&uuml;ck", "token.php");
		require 'footer.php';
		die();
	}
	// checken, ob das Token schon verbraucht wurde.
	if($z_config_token_nur_einmal == true){
	    $verbraucht = $zeile['verbraucht'];
	    if($verbraucht == 1){
	        z_html_warnung("Das Token $token ist schon verbraucht!");
	        z_html_button_link("Zur&uuml;ck", "token.php");
	        require 'footer.php';
	        die();
	    }
	}

	
	if($post_daten == 'ja'){
		$login = $post_login;
		$nickname = $post_nickname;
		$passwort = $post_passwort;
	}
	else{
		$login = "";
		if(isset($zeile['login'])){
			$login = $zeile['login'];
		}
		$nickname = "";
		if(isset($zeile['nickname'])){
		    $nickname = $zeile['nickname'];
		}
		$passwort = "";
	}
	
		
?>
		<h1>Registriere dich als Lehrer:in!</h1>
		<p>
			<b>Gib dir einen Login-Namen, einen Nickname und ein Passwort!</b><br/>
			<i>Den Nickname sehen die Sch&uuml;ler:innen, d.h. es eignet sich z.B. "Frau Schmitz".</i>"
		</p>

		<br/>

		<form action="token_registrierung_eintragen.php" method="POST">		
			<div class="table">
				<div class="tr">
					<div class="td" style="font-size: 90%">Login-Name: * </div>
					<div class="td">
						<input type="text" size="20" name="login" value="<?php echo "$login"; ?>">
						<input type="hidden" name="token" value="<?php echo $token; ?>">
					</div>
				</div>
				<div class="tr">
					<div class="td" style="font-size: 90%">Anzeige-Name: * </div>
					<div class="td">
						<input type="text" size="20" name="nickname" value="<?php echo "$nickname"; ?>">
					</div>
				</div>
				<div class="tr">
					<div class="td"  style="font-size: 90%">Passwort: * </div>
					<div class="td">
						<input type="password" name="passwort1" value="<?php echo "$passwort"; ?>" size="20">
					</div>
				</div>				
				<div class="tr">
					<div class="td"  style="font-size: 90%">Passwort<br/>wiederholen: *</div>
					<div class="td">
						<input type="password" name="passwort2" value="" size="20">
					</div>
				</div>				
			</div>
			<br>	
			<?php z_html_button_submit("registrieren"); ?>
		</form>
<?php
		require("footer.php");
?>
