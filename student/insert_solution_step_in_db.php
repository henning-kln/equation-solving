
<?php
    $z_require_erlaubt = true;
    require('../include/config.php');
    require_once('../include/db_verbindung.php');
    require_once('../include/werkzeuge.php');
    
    $step = z_db_text_erzeugen($_GET['step']);
    $solution_id = z_db_text_erzeugen($_GET['solution_id']);
    //echo $step."<br/>".$solution_id."<br/>";
    
    $sql_statement = 
        " INSERT INTO equ_step(equation,equ_solution_id) ".
        " VALUES('$step','$solution_id')";
    z_db_sql_statement_ausfuehren($sql_statement);
?>
    