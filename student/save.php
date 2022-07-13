<?php
$z_require_erlaubt = true;

require_once('header_ohne_session_start.php');
require_once('../include_math/equation_solve.php');

$all_infos_avalaible = true;
if(!isset($_POST['token_task'])){
    echo("<h1><font color='red'>token_task fehlt!</font></h1>");
    $all_infos_avalaible = false;
}
if(!isset($_POST['token_task_work'])){
    echo("<h1><font color='red'>token_task_work fehlt!</font></h1>");
    $all_infos_avalaible = false;
}

if($all_infos_avalaible == true){
    $token_task = $_POST['token_task'];
    $token_task_work = $_POST['token_task_work'];

    // set task_work as finished
    $update_statement = 
    " UPDATE equ_task_work SET finished = 1 WHERE token = '$token_task_work'";
    z_db_sql_statement_ausfuehren($update_statement);
    
    // get number of equations from database
    $select_statement =
    " SELECT nickname FROM equ_task_work WHERE token = '$token_task_work' ";
    $result = z_db_sql_statement_ausfuehren($select_statement);
    $zeile = z_db_zeile_auslesen($result);
    $nickname = $zeile[0];
    
    $link = $z_config_root_verzeichnis."student/task_display.php?token=".$token_task_work;
    
?>
	<h1><?php echo $nickname; ?>, deine L&ouml;sungen sind gespeichert!</h1>
	<p>
		<b>Speichere den folgenden Link, um deine Bearbeitung wieder aufrufen zu k&ouml;nnen:</b><br/>
		<input type="text" readonly id="link_text" size=80 value="<?php echo($link);?>">
	</p>
	<p>
		<b>Hier klicken, um zu deiner L&ouml;sung zu kommen:</b><br/>
		<a href="<?php echo($link);?>"><?php echo($link);?></a>
	</p>
    <script>
    	$(document).ready(function() {
            $("#link_text").focus(function() { 
                $(this).select(); 
            }); 
    	});  
    	document.getElementById("solve_form").addEventListener("submit", function(event){
            let nickname =  document.getElementById("nickname").value;
            if(nickname.length < 3){
                alert("Der Nickname muss mindestens 3 Buchstaben haben!");
	      		event.preventDefault()
            }
		});        	
	</script>
	
<?php     
} // end if($all_infos_avalaible == true)
require_once('footer.php');
?>
