<?php
ob_start();
// diese Seite ermoeglicht eine Gleichung zu loesen, ohne dass eine Aufgabe vom Lehrer gestellt wurde.
// sie wird von equation_create.php aufgerufen.

// damit die Loesung gespeichert (und muehelos wieder angezeigt) werden kann, 
// wird eine Zeile in equ_task_work erzeugt. 

$z_require_erlaubt = true;

require_once('header_ohne_session_start.php');
require_once('../include_math/equation_solve.php');
require_once('cookies.php');

$all_infos_avalaible = true;
if(!isset($_POST['equation'])){
    echo("<h1><font color='red'>equation fehlt!</font></h1>");
    $all_infos_avalaible = false;
}
if(!isset($_POST['variable'])){
    echo("<h1><font color='red'>variable fehlt!</font></h1>");
    $all_infos_avalaible = false;
}
if(!isset($_POST['leftSide'])){
    echo("<h1><font color='red'>leftSide fehlt!</font></h1>");
    $all_infos_avalaible = false;
}
if(!isset($_POST['rightSide'])){
    echo("<h1><font color='red'>rightSide fehlt!</font></h1>");
    $all_infos_avalaible = false;
}


if($all_infos_avalaible == true){
    $equation = $_POST['equation'];
    $variableToUpperCase = $_POST['variable'];
    $intervalLeft = $_POST['leftSide'];
    $intervalRight = $_POST['rightSide'];
        
    $nickname = "eigene Gleichung";    

    if(isset($_POST['nickname'])){
        $nickname = z_db_text_erzeugen($_POST['nickname']);
    }
    
    $token_task_work = z_token_finden("equ_task_work", 10);

    $insert_statement = " INSERT INTO equ_task_work(token,nickname) VALUES ('$token_task_work','$nickname')";
    //z_konsole($insert_statement);
    z_db_sql_statement_ausfuehren($insert_statement);
    
    $select_statement = " SELECT id FROM equ_task_work WHERE token = '$token_task_work'";
    $ergebnis = z_db_sql_statement_ausfuehren($select_statement);
    $task_work_id = z_db_zeile_auslesen($ergebnis)[0];
    cookie_insert_token($token_task_work);

    
    $solution_id = -1;
    
    // get the solution_id, if there is already a solution for equation_id and task_work_id
    $sql_statement = 
        " SELECT s.id ".
        " FROM equ_solution s".
        " WHERE s.equ_task_work_id = $task_work_id ";
    $result = z_db_sql_statement_ausfuehren($sql_statement);
    if(z_db_zeilen_zahl($result) > 0){
        $solution_id = z_db_zeile_auslesen($result)[0];
    }
    else{
        // insert new solution
        $insert_statement = 
            " INSERT INTO equ_solution(equ_task_work_id) ".
            " VALUES('$task_work_id')";
        //z_konsole($insert_statement);
        z_db_sql_statement_ausfuehren($insert_statement);
    
        $sql_statement = 
            " SELECT id ".
            " FROM equ_solution ".
            " WHERE equ_task_work_id = '$task_work_id'";
        $result = z_db_sql_statement_ausfuehren($sql_statement);
        $solution_id = z_db_zeile_auslesen($result)[0];

        // die Aufgaben-Gleichung als ersten L&ouml;sungsschritt eintragen:
        $insert_statement =
            " INSERT INTO equ_step(equation,equ_solution_id) VALUES ('$equation','$solution_id')";
        //z_konsole($insert_statement);
        z_db_sql_statement_ausfuehren($insert_statement);
        
        
    }
    
    equation_solve(null, $equation, $variableToUpperCase, $intervalLeft, $intervalRight, $solution_id,"");
        
?>

	<table style="background-color:white">
      <tr>
      	<td>
      		<p>            
                <form action="save.php" method="POST">
                    <div style="text-align: center;">
                    	<button class="button">speichern</button>
            		</div>
            		<input type="hidden" name="token_task_work" value = "<?php echo $token_task_work; ?>">	
            		<input type="hidden" name="token_task" value = "null">	
        		</form>
        	</p>
      	</td>
      </tr>	
	</table>


<?php 
} // end if($all_infos_avalaible == true)
require_once('footer.php');

ob_end_flush();
?>
