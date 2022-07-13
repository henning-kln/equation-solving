<?php
ob_start();
$z_require_erlaubt = true;
require_once('header_ohne_session_start.php');
require_once('cookies.php');

?>
	<h1>Deine Aufgaben:</h1>
	<p>
		<?php z_html_button_link("Aufgaben speichern (z.B. im Kursnotizbuch)", "tasks_overview_save.php");?>
	</p>	
	<p>
		<i>Hier findest du die Aufgaben, die auf diesem Ger&auml;t bearbeitet wurden.</i><br/>
		<i style="font-size:small">Wenn du den Browserverlauf l&ouml;scht, werden die gespeicherten Aufgaben gel&ouml;scht.</i><br/>
	</p>
	<table style="background-color:white">
		<tr>
			<th>&nbsp;Aufgabe vom&nbsp;</th>		
			<th>&nbsp;bearbeitet von&nbsp;</th>		
			<th>&nbsp;bearbeitet am&nbsp;</th>		
			<th>&nbsp;Details&nbsp;</th>		
		</tr>
<?php     
    $token_array = cookie_get_tokens();
    //z_konsole($token_array);
    foreach ($token_array as $token_task_work){
        $sql_statement = 
            " SELECT t.timestamp, tw.timestamp, tw.nickname ".
            " FROM equ_task t RIGHT JOIN equ_task_work tw ".
            " ON t.id = tw.equ_task_id ".
            " WHERE tw.token = '$token_task_work' ";
        $ergebnis = z_db_sql_statement_ausfuehren($sql_statement);
        $zeile = z_db_zeile_auslesen($ergebnis);
        $task_date = "---";
        if(isset($zeile[0]) && $zeile[0] !== False)
            $task_date = z_datum_aus_db($zeile[0]);
        $task_work_date = z_datum_aus_db($zeile[1]);
        $nickname = $zeile[2];
?>
    		<tr>
    			<td><?php echo $task_date;?></td>
    			<td><?php echo $nickname;?></td>
    			<td><?php echo $task_work_date;?></td>
    			<td >
    				<form action="task_display.php" method="get">
    					<button class='button'>anzeigen</button>
    					<input type='hidden' name='token' value='<?php echo $token_task_work?>' />
    				</form>
    			</td>
    		</tr>

<?php         
        
    }
?>
	</table>
<?php    
    require_once('footer.php');
    ob_end_flush();
?>
