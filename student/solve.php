<?php
ob_start();

$z_require_erlaubt = true;

require_once('header_ohne_session_start.php');
require_once('../include_math/equation_solve.php');
require_once('../include/passwort.php');
require_once('cookies.php');

$all_infos_avalaible = true;
if(!isset($_POST['token_task'])){
    echo("<h1><font color='red'>token_task fehlt!</font></h1>");
    $all_infos_avalaible = false;
}
if(!isset($_POST['token_task_work'])){
    echo("<h1><font color='red'>token_task_work fehlt!</font></h1>");
    $all_infos_avalaible = false;
}
if(!isset($_POST['equation_number'])){
    echo("<h1><font color='red'>equation_number fehlt!</font></h1>");
    $all_infos_avalaible = false;
}

if($all_infos_avalaible == true){
    $token_task = $_POST['token_task'];
    $token_task_work = $_POST['token_task_work'];
    $equation_number = $_POST['equation_number'];

    //TODO zurueck zur letzten Gleichung ermoeglichen.
    $previous_equation_number = $equation_number - 1;
    $next_equation_number = $equation_number + 1;
    
    // wenn die erste Gleichung bearbeitet wird: Ein Cookie dafuer eintragen.
    if($equation_number == 1){
        cookie_insert_token($token_task_work);
    }
    $nickname = "unbekannt";
    if(isset($_POST['nickname'])){
        $nickname = z_db_text_erzeugen($_POST['nickname']);
        if(strlen($nickname)<3 ){
            z_html_warnung("Der Nickname muss mindestens 3 Buchstaben haben!");
            z_html_button_link("zurueck", "task.php?token=$token_task");
            require_once('footer.php');
            die();
        }
        if($nickname == "unbekannt" ){
            z_html_warnung("unbekannt ist als Nickname nicht erlaubt.");
            z_html_button_link("zurueck", "task.php?token=$token_task");
            require_once('footer.php');
            die();
        }
        if(z_passwort_enthaelt_ungeeignete_zeichen($nickname)){
            z_html_warnung("Dollar, Leerzeichen, Backslash, Anf&uuml;hrungszeichen und Semikolon sind im Nickname nicht erlaubt.");
            z_html_button_link("zurueck", "task.php?token=$token_task");
            require_once('footer.php');
            die();
        }
        $statement= $z_db_verbindung->prepare(
            "SELECT COUNT(*) AS anzahl FROM equ_task t, equ_task_work tw WHERE t.id = tw.equ_task_id AND tw.nickname = ? AND t.token = ? AND tw.token != ?"
            );
        $statement->bind_param("sii",$nickname,$token_task, $token_task_work);
        $statement->execute();
        $result = $statement->get_result();
        $db_zeile = $result->fetch_assoc();
        $statement->close();
        if($db_zeile['anzahl'] > 0){
            z_html_warnung("$nickname hat schon jemand anders!");
            z_html_button_link("zurueck", "task.php?token=$token_task");
            require_once('footer.php');
            die();
        }
        $statement = $z_db_verbindung->prepare(
            "UPDATE equ_task_work SET nickname = ? WHERE token = ?"
            );
        $statement->bind_param("si",$nickname,$token_task_work);
        $statement->execute();
        $statement->close();
        
    }
    else{
        // kein Nickname in $_POST
        // den Nickname aus der DB auslesen
        $statement= $z_db_verbindung->prepare(
            "SELECT nickname AS nickname FROM equ_task_work WHERE token = ?"
            );
        $statement->bind_param("i",$token_task_work);
        $statement->execute();
        $result = $statement->get_result();
        $zeile = $result->fetch_assoc();
        $nickname = $zeile['nickname'];
        if(strlen($nickname) <2){
            $nickname = "unbekannt";
        }
    }
    
    
    
    // get number of equations from database
    $select_statement =
    " SELECT COUNT(eq.id) AS number_of_equations ".
    " FROM equ_task ta , equ_equation eq ".
    " WHERE ta.id = eq.equ_task_id ".
    " AND ta.token = '$token_task' ";
    
    $result = z_db_sql_statement_ausfuehren($select_statement);
    
    $zeile = z_db_zeile_auslesen($result);
    
    $number_of_equations = $zeile[0];

    $equation_number_minus_one = $equation_number - 1;
    
    $sql_statement = 
        " SELECT equ.id AS equation_id, equ.equation AS equation, equ.variable AS variable, ".
        "        equ.intervalLeft AS intervalLeft, equ.intervalRight AS intervalRight, ".
        "        wo.id AS task_work_id ".
        " FROM equ_equation equ, equ_task ta, equ_task_work wo ".
        " WHERE equ.equ_task_id = ta.id AND ta.id = wo.equ_task_id ".
        " AND ta.token = '$token_task' AND wo.token = '$token_task_work' ".
        " ORDER BY equ.id ".
        " LIMIT 1 OFFSET $equation_number_minus_one";
    //echo "$sql_statement<br/>";
    $result = z_db_sql_statement_ausfuehren($sql_statement);
    $zeile = z_db_zeile_auslesen($result);
    $equation_id = $zeile['equation_id'];
    $equation = $zeile['equation'];
    $variableToUpperCase = $zeile['variable'];
    $intervalLeft = $zeile['intervalLeft'];
    $intervalRight = $zeile['intervalRight'];
    $task_work_id = $zeile['task_work_id'];
    
    //echo "$equation_number_minus_one: $equation<br/>";
    
    $solution_id = -1;
    
    // get the solution_id, if there is already a solution for equation_id and task_work_id
    $sql_statement = 
        " SELECT s.id ".
        " FROM equ_solution s".
        " WHERE s.equ_equation_id = $equation_id AND s.equ_task_work_id = $task_work_id ";
    $result = z_db_sql_statement_ausfuehren($sql_statement);
    if(z_db_zeilen_zahl($result) > 0){
        $solution_id = z_db_zeile_auslesen($result)[0];
    }
    else{
        // insert new solution
        $sql_statement = 
            " INSERT INTO equ_solution(equ_equation_id,equ_task_work_id) ".
            " VALUES('$equation_id','$task_work_id')";
        z_db_sql_statement_ausfuehren($sql_statement);
    
        $sql_statement = 
            " SELECT MAX(so.id) ".
            " FROM equ_solution so ";
        $result = z_db_sql_statement_ausfuehren($sql_statement);
        $solution_id = z_db_zeile_auslesen($result)[0];

        // die Aufgaben-Gleichung als ersten L&ouml;sungsschritt eintragen:
        $insert_statement =
            " INSERT INTO equ_step(equation,equ_solution_id) VALUES ('$equation','$solution_id')";
        z_db_sql_statement_ausfuehren($insert_statement);
        
        
    }
    equation_solve($equation_id, $equation, $variableToUpperCase, $intervalLeft, $intervalRight, $solution_id, $nickname);
        
    if($equation_number !== $number_of_equations ){  
?>    
	<table style="background-color: white" >
      <tr>
      	<td>
      		<p>
<?php 
if($equation_number > 1){
?>
        	<form action="solve.php" method="POST">
        		<div style="text-align: center;">
        			<button  class="button">Zur&uuml;ck</button>
        		</div>
        		<input type="hidden" name="token_task_work" value = "<?php echo $token_task_work; ?>">	
        		<input type="hidden" name="token_task" value = "<?php echo $token_task; ?>">	
        		<input type="hidden" name="equation_number" value = "<?php echo $previous_equation_number; ?>">	
        	</form>
<?php 
}else{
    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
}
?>
        	</p>
      	</td>
      	<td>
      		<p>
        	<form action="solve.php" method="POST">
        		<div style="text-align: center;">
        			<button  class="button">Zur <?php echo $next_equation_number?>. Gleichung (von <?php echo $number_of_equations;?>)</button>
        		</div>
        		<input type="hidden" name="token_task_work" value = "<?php echo $token_task_work; ?>">	
        		<input type="hidden" name="token_task" value = "<?php echo $token_task; ?>">	
        		<input type="hidden" name="equation_number" value = "<?php echo $next_equation_number; ?>">	
        	</form>
        	</p>
      	</td>
      </tr>	
	</table>

<?php     
    } // end if($equation_number !== $number_of_equations )
    else{
?>            
	<table style="background-color: white">
      <tr>
      	<td>
      		<p>
        	<form action="solve.php" method="POST">
        		<div style="text-align: center;">
        			<button  class="button">Zur&uuml;ck</button>
        		</div>
        		<input type="hidden" name="token_task_work" value = "<?php echo $token_task_work; ?>">	
        		<input type="hidden" name="token_task" value = "<?php echo $token_task; ?>">	
        		<input type="hidden" name="equation_number" value = "<?php echo $previous_equation_number; ?>">	
        	</form>
        	</p>
      	</td>
      	<td>
      		<p>
            <form action="save.php" method="POST">
                <div style="text-align: center;">
                	<button class="button">speichern</button>
        		</div>
        		<input type="hidden" name="token_task_work" value = "<?php echo $token_task_work; ?>">	
        		<input type="hidden" name="token_task" value = "<?php echo $token_task; ?>">	
    		</form>
        	</p>
      	</td>
      </tr>	
	</table>
<?php 
    } // end else
} // end if($all_infos_avalaible == true)
require_once('footer.php');

ob_end_flush();
?>
