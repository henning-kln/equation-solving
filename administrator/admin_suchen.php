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
	
	if(!isset($_POST['tabelle'])){
?>
		<h1><?php echo "$z_user"; ?>: Suchen</h1>
		<p>Hier kannst man einzelne User suchen.</p>
		<p> 
			<b>In welcher Tabelle willst du suchen?</b>
		</p>
		<form action='admin_suchen.php' method='post'>
			<p>
				<?php select_fuer_alle_db_tabellen();?>
			</p>
			<p>
				<button class="button" type="submit">Tabelle ausw&auml;hlen</button>
			</p> 
		</form>
<?php 
	}	// end if
	else{
		$tabelle = $_POST['tabelle'];
?>
		<h1>Suchen in der Tabelle <?php echo "$tabelle"; ?></h1>
		<p>gesuchter Login-Name:</p>
		<input id="such_name" type="text" name="such_name" size="20" max_size="50">
		<p><b>Ergebnisse:</b></p>
		<p><i>Teil des Login-Namens eingeben<br/>und dann <b>Enter</b>!</i></p>
		<p id="such_ergebnis">
		</p>
		
<?php 		
		$zusaetzliche_variablen_array = array('tabelle' => $tabelle);
		z_ajax_enter("such_name", "admin_suchen_ergebnisse.php", "GET", "login", $zusaetzliche_variablen_array, "such_ergebnis");

	}  // end else
?>						
<?php
	require_once('footer.php');			
?>