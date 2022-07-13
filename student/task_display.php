<script src="https://unpkg.com/mathjs@8.1.0/lib/browser/math.js"></script>
<script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
<script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-svg.js"></script>

<?php
$z_require_erlaubt = true;
require_once('header_ohne_session_start.php');

function show_equation($equation){
    echo "$equation<br/>\n";
}


if(!isset($_GET['token'])){
    echo("<h1><font color='red'>Token fehlt!</font></h1>");
}
else{
    $number = 'alle';
    if(isset($_GET['number'])){
        $number = $_GET['number'];
    }
    $teacher_task_token = NULL;
    if(isset($_GET['task_token'])){
        $teacher_task_token = $_GET['task_token'];
    }
    
    $token_task_work = z_db_text_erzeugen($_GET['token']);
    //echo $token_task;
    $select_statement = 
        " SELECT nickname, equ_task_id, id, timestamp ".
        " FROM equ_task_work ".
        " WHERE token = '$token_task_work'";
    $ergebnis = z_db_sql_statement_ausfuehren($select_statement);
    $zeile = z_db_zeile_auslesen($ergebnis);
    if($zeile === null){
        echo("<h1><font color='red'>ung&uuml;ltiges Token!</font></h1>");
    }
    else{
        $nickname = $zeile[0];
        $task_text = '---';
        $task_date = '---';
        $task_id = $zeile[1];
        //z_konsole($task_id);
        $task_work_id = $zeile[2];
        $task_work_date = z_datum_aus_db($zeile[3]);
        $number_of_equations = 1;
        
        if(isset($task_id)){
        
            $select_statement = 
                " SELECT ta.id AS task_id, ta.text AS task_text, ta.timestamp AS task_timestamp, COUNT(eq.id) AS number_of_equations, te.nickname as teacher_nickname ".
                " FROM (equ_teacher te RIGHT JOIN equ_task ta ".
                " ON te.id = ta.equ_teacher_id), equ_equation eq".
                " WHERE ta.id = eq.equ_task_id ".
                " AND ta.id = '$task_id' ".
                " GROUP BY ta.id ";
            ////z_konsole($select_statement);
            $ergebnis = z_db_sql_statement_ausfuehren($select_statement);
            $zeile = z_db_zeile_auslesen($ergebnis);
            $task_text = $zeile['task_text'];
            $task_timestamp = $zeile['task_timestamp'];
            $task_date = z_datum_aus_db($task_timestamp);
            
            $teacher_nickname = $zeile['teacher_nickname'];
            $number_of_equations = $zeile['number_of_equations'];
        
            echo "<p><b> Aufgabe von $teacher_nickname</b> ($task_date):</p>";    
            echo "<p><b> bearbeitet von: $nickname</b> ($task_work_date)</p>";
            echo "<p><b>Aufgabentext:</b><br/>$task_text</p>";
        }
        else{
            echo "<p><b> eigene Gleichung ($task_work_date)<b></p>";
        }
        $select_statement = 
            " SELECT e.equation, st.equation ".
            " FROM (equ_equation e RIGHT JOIN equ_solution s ON e.id = s.equ_equation_id), equ_step st ".
            " WHERE s.id = st.equ_solution_id ".
            " AND s.equ_task_work_id = '$task_work_id' ".
            " ORDER BY e.id, st.id ";
        //z_konsole($select_statement);
        $ergebnis = z_db_sql_statement_ausfuehren($select_statement);
        $equation_alt = "NULL";
        
        $gleichungen_zaehler = 0;
        $zeilen_nummer = 0;
        
        $display_javascript = "<script type='text/javascript'>\n".
                              "   $(document).ready(function() {\n ";
        while($zeile = z_db_zeile_auslesen($ergebnis)){
                $equation = $zeile[0];
                if($equation != $equation_alt){
                    $gleichungen_zaehler++;
                    $zeilen_nummer = 0;
                    $equation_alt = $equation;
                    $lastRowCurrent = "lastRow".$gleichungen_zaehler;
                    if($number == 'alle' || $gleichungen_zaehler == $number){
                        echo "<br/><b>".$gleichungen_zaehler.". L&ouml;sung von $nickname:</b><br/><br/>\n";
         ?>               
         				<table bgcolor="white">
                        	<tr id="<?php echo $lastRowCurrent; ?>"></tr>
                        </table>
                       
        <?php       
                    }
                }
                $step = $zeile[1];
                if($number == 'alle' || $gleichungen_zaehler == $number){
                    $zeilen_nummer_anzeige = "&nbsp;$zeilen_nummer.&nbsp;";
                    if($zeilen_nummer == 0){
                        $zeilen_nummer_anzeige = "Aufgabe";
                    }
                    $display_javascript.= "    displayEquationAsTR('$step','step','','0','0','$zeilen_nummer_anzeige','none','$lastRowCurrent');\n";
                    
                }
                $zeilen_nummer += 1;
        }  // end while
        $display_javascript.= "  });";
        $display_javascript.= "</script>\n";
        echo $display_javascript;        
    } // end else
} // end else
 
// if this page was called by a teacher,
// then show button for return
if($teacher_task_token != NULL){
?>
	<br/>
	<form action="../teacher/task_details.php?token=" method="get">
		<input type=hidden name="token" value="<?php echo $teacher_task_token;?>" />
<?php 
        z_html_button_submit("zur&uuml;ck");
?>		
	</form>
<?php 
}



require_once('footer.php');
?>
