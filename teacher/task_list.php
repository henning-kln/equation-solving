<?php
	$z_require_erlaubt = true;
	require_once('header.php');
	require_once('../include_math/equation_input.php');

	$teacher_id = $z_user_id;
	
	// delete tasks that don't have an equation
	$delete_statement =
	" DELETE FROM equ_task ".
	" WHERE equ_teacher_id = $teacher_id ".
	" AND id NOT IN ".
	" ( SELECT equ_task_id FROM equ_equation )";
	//z_html_warnung($delete_statement);
	z_db_sql_statement_ausfuehren($delete_statement);
	
	
	$select_statement = 
	   " SELECT id, titel, text, token, timestamp FROM equ_task WHERE equ_teacher_id = '$teacher_id' ORDER BY timestamp DESC";
	// echo $select_statement."<br/><br/>";
	$result = z_db_sql_statement_ausfuehren($select_statement);

	
?>
    <h1>Meine Aufgaben:</h1>
    <table bgcolor="white">
    	<tr>
    		<th>Datum</th>
    		<th>Text</th>
    		<th colspan=2>&nbsp;</th>
    	</tr>
<?php 
	
    while($zeile= z_db_zeile_auslesen($result)){
        $task_id = $zeile[0];    
        $titel = $zeile[1];
        $text = $zeile[2];
        $token = $zeile[3];
        $datum= z_datum_aus_db($zeile[4]);
?>
    	<tr>
    		<td><?php echo $datum; ?></td>
    		<td><?php echo $text; ?></td>
    		<td>
    		<?php z_html_button_link("starten", "task_details.php?token=$token") ?>
        	</td>
        	<td>
    		<?php z_html_button_link("bearbeiten", "task_create.php?token=$token") ?>
    		</td>		
<!--     		<td><a href="task_details.php?token=<?php echo $token; ?>">Details</a></td> -->
    		<!-- <td><a href="task_delete.php?token=<?php echo $token; ?>">l&ouml;schen</a></td> -->
    	</tr>
		
<?php      
        
    } // end while
?>
    	
    </table>



<?php
    require_once('footer.php');			
?>
