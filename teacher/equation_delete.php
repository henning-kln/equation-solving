<?php
$z_require_erlaubt = true;
if(!isset($z_require_erlaubt) || $z_require_erlaubt != true){
    z_html_warnung('<p>Unerlaubter Aufruf des Skriptes!</p>');
    die();
}

// z_user wird aus dem Verzeichnis abgeleitet, in dem die Datei liegt!
$z_user = 	basename(dirname(__FILE__));

require_once('../include/config.php');
$z_user_mit_db_prefix = $z_config_db_tabellen_prefix.$z_user;

require_once('../include/db_verbindung.php');
require_once('../include/session_funktionen.php');
require_once('../include/werkzeuge.php');
require_once('../include/konsole.php');

date_default_timezone_set("Europe/Berlin");

z_session_start();

$teacher_id = z_session_get_user_id();
if($teacher_id == null){
    die();
}
    
$equation_id = $_POST['equation_id'];

$statement = $z_db_verbindung->prepare(
    " SELECT equ_teacher_id AS teacher_id FROM equ_task t RIGHT JOIN equ_equation e ON t.id = e.equ_task_id WHERE e.id = ?"
    );
$statement->bind_param("s",$equation_id);
$statement->execute();
$result = $statement->get_result();
$zeile = $result->fetch_assoc();
$statement->close();
if($zeile == null){
    echo "keine teacher_id zu equation_id $equation_id gefunden";
    die();
}
if($teacher_id != $zeile['teacher_id']){
    echo "falsche teacher_id -> keine Berechtigung zum Loeschen.";
    die();
}

    
$success = "failed";

$statement = $z_db_verbindung->prepare(
    " DELETE FROM equ_equation WHERE id = ?"
    );
$statement->bind_param("s",$equation_id);
$statement->execute();
if($statement->affected_rows>0){
    $success = "success";
}
$statement->close();

echo $success;
?>