<?php
    $z_require_erlaubt = true;
    require_once('header_ohne_text_mit_session_start.php');
    
    if(!isset($_GET['task_id'])){
        z_fehlermeldung("task_id fehlt");
    }
    $task_id = $_GET['task_id'];
    
    if(!isset($_GET['equation_count'])){
        z_fehlermeldung("equation_count fehlt");
    }
    $equationCount = $_GET['equation_count'];
    
    if(!isset($_GET['task_token'])){
        z_fehlermeldung("task_token fehlt");
    }
    $task_token = $_GET['task_token'];
    
    if(!isset($_GET['time'])){
        z_fehlermeldung("time fehlt");
    }
    $time = $_GET['time'];
    
    $anzeigenColSpan = $equationCount + 1;
    
?>
	
	<h1>Work in progress... (<?php echo $time;?>)</h1>
	<table bgcolor="white">
		<tr>
			<th>Nickname</th>
			<th>Fortschritt</th>
			<th>Datum</th>
			<th colspan=<?php echo $anzeigenColSpan; ?>>anzeigen</th>
		</tr>
		
<?php
    $select_statement = " SELECT tw.nickname, tw.timestamp, tw.token, COUNT(s.id), tw.finished ".
                        " FROM equ_task_work tw, equ_solution s ".
                        " WHERE tw.equ_task_id = '$task_id' ".
                        " AND tw.id = s.equ_task_work_id ".
                        " GROUP BY tw.id ".
                        " ORDER BY tw.nickname ASC, tw.timestamp DESC ";
    $ergebnis = z_db_sql_statement_ausfuehren($select_statement);
    while($zeile = z_db_zeile_auslesen($ergebnis)){
        $nickname = $zeile[0];
        $datum = z_datum_aus_db($zeile[1]);
        $token_task_work = $zeile[2];
        $count_solutions = $zeile[3];
        $finished = $zeile[4];
        $progress = "".$count_solutions;
        if($finished == 1){
            $progress = "fertig";
        }
?>
		<tr>
			<td><?php echo $nickname; ?></td>
			<td><?php echo $progress; ?></td>
			<td><?php echo $datum; ?></td>
<?php 
                for($i=1;$i<=$count_solutions;$i++){
                    echo "<td>\n";
                    z_html_button_link("$i", "../student/task_display.php?number=$i&token=$token_task_work&task_token=$task_token");
                    echo "</td>\n";
                }
                for($i=$count_solutions+1;$i<=$equationCount;$i++){
                    echo "<td></td>\n";
                }
                echo "<td>";
                z_html_button_link("alle", "../student/task_display.php?number=alle&token=$token_task_work&task_token=$task_token");
                echo "</td>";
?>			
		</tr>
<?php         
    }
?>
	</table>
	
