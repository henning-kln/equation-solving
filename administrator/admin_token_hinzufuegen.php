<?php
	$z_require_erlaubt = true;
	require_once('header.php');

	function select_fuer_alle_db_tabellen(){
		$alle_tabellen = z_db_alle_tabellen_namen();
		$anzahl = sizeof($alle_tabellen);
		echo "<select name='tabelle' size='$anzahl'>\n";
		foreach($alle_tabellen AS $tabelle){
			echo "<option value='$tabelle'>$tabelle</option>\n";
		}
		echo "</select>\n";
	}
	
?>
	<h1><?php echo "$z_user"; ?>: Token hinzuf&uuml;gen</h1>
	<p>Gib' die Tabelle an, zu der Token hinzugef&uuml;gt werden sollen, und die Anzahl der neuen Token.</p>
	<p> 
		Die neuen Token werden dann angezeigt.<br/>
	</p>
	<form action='admin_token_hinzufuegen_bearbeiten.php' method='post'>
		<p>
			Tabelle:<br/>
			<?php select_fuer_alle_db_tabellen();?>
		</p>
		<p>
			Anzahl (max. 100):<br/>			
			<input type="text" size="24" value="40" maxlength="50" name="anzahl">
		</p>
		<p>
			L&auml;nge der Tokens (zwischen 5 und 10):<br/>			
			<input type="text" size="24" maxlength="50" value="6" name="laenge">
		</p>
		<p>
			<button class="button" type="submit">Token erzeugen</button>
		</p> 
	</form>
						
<?php
	require_once('footer.php');			
?>