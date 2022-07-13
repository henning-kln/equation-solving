<?php
    $z_require_erlaubt = true;
    require_once('header_ohne_text_mit_session_start.php');
?>
<?php
    $task_text = $_POST['task_text'];
    $task_id = $_POST['task_id'];

    // update task_text in database
    $update_statement = 
        " UPDATE equ_task SET text = '$task_text' WHERE id='$task_id'";
    z_db_sql_statement_ausfuehren($update_statement);

    //echo ($update_statement);
    
    echo "Aufgabenstellung gespeichert.";
?>

