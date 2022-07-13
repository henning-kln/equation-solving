<?php
    $z_require_erlaubt = true;
    require_once("header_ohne_text_mit_session_start.php");
    
    $equation = $_POST['equation'];
    $variableToUpperCase = $_POST['variable'];
    // the interval, where the solution is searched for
    $intervalLeft = $_POST['leftSide'];
    $intervalRight = $_POST['rightSide'];
    $taskId = $_POST['taskId'];
    
    $token = z_token_finden('equ_equation',8);
    
    // insert new equation into database
    $insert_statement = 
        " INSERT INTO equ_equation(equation,variable,intervalLeft,intervalRight,token,equ_task_id)".
        " VALUES('$equation','$variableToUpperCase','$intervalLeft','$intervalRight','$token','$taskId')";
    //echo ("<br/><br/>".$insert_statement."<br/><br/>");
    z_db_sql_statement_ausfuehren($insert_statement);
    
    
    // die id der neu eingefuegten Gleichung zurueckgeben
    $select_statement = "SELECT MAX(id) FROM equ_equation";
    $ergebnis = z_db_sql_statement_ausfuehren($select_statement);
    $zeile = z_db_zeile_auslesen($ergebnis);
    $text = "".strval($zeile[0]);
    echo $text;
    

?>