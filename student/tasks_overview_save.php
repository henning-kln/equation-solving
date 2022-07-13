<?php
ob_start();
$z_require_erlaubt = true;
require_once('header_ohne_session_start.php');
require_once('cookies.php');

    $token_array = cookie_get_tokens();
    //z_konsole($token_array);
    $task_overview_text = "\n";
    foreach ($token_array as $token_task_work){
        $sql_statement = 
            " SELECT t.timestamp, tw.timestamp, tw.nickname ".
            " FROM equ_task t RIGHT JOIN equ_task_work tw ".
            " ON t.id = tw.equ_task_id ".
            " WHERE tw.token = '$token_task_work' ORDER BY t.timestamp DESC,tw.timestamp DESC";
        $ergebnis = z_db_sql_statement_ausfuehren($sql_statement);
        $zeile = z_db_zeile_auslesen($ergebnis);
        $task_date = "---";
        if(isset($zeile[0]) && $zeile[0] !== False)
            $task_date = z_datum_aus_db($zeile[0]);
        $task_work_date = z_datum_aus_db($zeile[1]);
        $nickname = $zeile[2];
        
        $task_overview_text.="Aufgabe vom: <b>$task_date</b>; bearbeitet am: $task_work_date von $nickname&nbsp;&nbsp;<br/>\n";
        $task_overview_text.="$z_config_root_verzeichnis"."student/task_display.php?token=$token_task_work&nbsp;&nbsp;&nbsp;<br/><br/>\n\n";
    }
?>
	
	<script>
        $(document).ready(function() {
            selectText('task_overview_textarea');
            $("#task_overview_textarea").focus(function() 
              { 
                selectText('task_overview_textarea');
              } 
        	);
        });

        function selectText(containerid) {
            if (document.selection) { // IE
                var range = document.body.createTextRange();
                range.moveToElementText(document.getElementById(containerid));
                range.select();
            } else if (window.getSelection) {
                var range = document.createRange();
                range.selectNode(document.getElementById(containerid));
                window.getSelection().removeAllRanges();
                window.getSelection().addRange(range);
            }
        }
	</script>
	<p>
		<b>Deine Aufgaben:</b>
	</p>
	<p>
		<i>Du kannst den markierten Text in die Zwischenablage einf&uuml;gen<br/>und dann z.B. in eine Kursnotizbuch-Seite oder in eine Mail einf&uuml;gen.</i><br/>
	</p>

	<div id="task_overview_textarea" contenteditable="true" style="background-color:white; width: 500px;height: 400px;border: 1px solid #ccc;padding: 5px;resize: both;overflow: auto;">
		<?php echo $task_overview_text;?>
	</div>	
	
<?php    
    require_once('footer.php');
    ob_end_flush();
?>
