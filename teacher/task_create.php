<?php
	$z_require_erlaubt = true;
	require_once('header.php');
	require_once('../include_math/equation_input.php');
	//require_once('equation_save_display_equations.php');
	
	$teacher_id = $z_user_id;

	$task_wird_bearbeitet = false;
	
	$token = null;
    $task_id = -1;
    
    if(isset($_GET['token'])){
	    $task_wird_bearbeitet = true;
    }
	else{
	   $task_wird_bearbeitet = false;
	}

	$task_text = "";
	if($task_wird_bearbeitet == true){
	   $token = $_GET['token'];
	   $statement= $z_db_verbindung->prepare(
	       " SELECT id AS task_id, text AS text, equ_teacher_id AS teacher_id FROM equ_task ".
	       " WHERE token = ?"
	   );
	   $statement->bind_param("s",$token);
	   $statement->execute();
	   $result = $statement->get_result();
	   $zeile = $result->fetch_assoc();
	   $statement->close();
	   if($zeile === NULL){
	       z_html_warnung("Eine Aufgabe f&uuml;r diese Nummer gibt es nicht.");
	       require_once('footer.php');
	       die();
	   }
	   
	   //pruefen, ob diese Aufgabe dem Lehrer ueberhaupt gehoert.
	   if($zeile['teacher_id'] != $teacher_id)	{
	       z_fehlermeldung("Diese Aufgabe hast du nicht gestellt!");
	       require_once('footer.php');
	       die();
	   }
	   
	   $task_id = $zeile['task_id'];
	   $task_text = $zeile['text'];
	   
	}
	else{
    	$token = z_token_finden('equ_task',6);
    	$insert_statement =
    	" INSERT into equ_task(equ_teacher_id,token) ".
    	" VALUES('$teacher_id','$token')";
    	// echo $insert_statement."<br/><br/>";
    	z_db_sql_statement_ausfuehren($insert_statement);
    	
    	$select_statement =
    	" SELECT MAX(id) FROM equ_task WHERE equ_teacher_id = '$teacher_id'";
    	// echo $select_statement."<br/><br/>";
    	$result = z_db_sql_statement_ausfuehren($select_statement);
    	$task_id = z_db_zeile_auslesen($result)[0];
    	// echo "task_id: ".$task_id."<br/><br/>";
    	$task_text = "Hier die Aufgabenbeschreibung und ggf. Hinweise einf&uuml;gen! Max. 200 Zeichen. Mit RETURN die Aufgabenbeschreibung speichern!";
	}
?>

<h1>Neue Aufgabe:</h1>
<br/>

<h2>Link zu dieser Aufgabe:</h2>
<input type="text" readonly id="link_text" size=60 value="<?php echo($z_config_root_verzeichnis."student/task.php?token=".$token);?>"><br/>
<i style="font-size:small;">Diesen Link den Sch&uuml;lern schicken!</i>
<br/>

<h2>Aufgabentext (max. 200 Zeichen):</h2>
<textarea id="task_text" name="task_text" rows="5" cols="60" maxlength="200" onfocus="task_text_onfocus()">
<?php echo $task_text;?>
</textarea>		
<i style="font-size:small;"><div id="task_text_saved">mit Enter speichern!</div></i>
<br/>
<?php
    $select_statement =
        " SELECT e.equation, e.variable, e.intervalLeft, e.intervalRight, e.id ".
        " FROM equ_equation e ".
        " WHERE e.equ_task_id= '$task_id'";
//    echo "<br/><br/>".$select_statement."<br/><br/>";
    $result = z_db_sql_statement_ausfuehren($select_statement);
    //$equationCount = z_db_zeilen_zahl($result);
?>
<script>
    $(document).ready(function() {
        $("#link_text").focus(function() { $(this).select(); } );
        
<?php    
    $zaehler = 1; 
    while($zeile= z_db_zeile_auslesen($result)){
        $equation = $zeile[0];    
        $variableToUpperCase = $zeile[1];
        $variableToLowerCase = strtolower($variableToUpperCase);
        $intervalLeft = $zeile[2];
        $intervalRight = $zeile[3];
        $equationId = $zeile[4];
?>
		displayEquationAsTR('<?php echo $equation;?>','<?php echo $equationId;?>', '<?php echo $variableToLowerCase;?>','<?php echo $intervalLeft;?>','<?php echo $intervalRight;?>','<?php echo "$zaehler. Gleichung";?>', 'delete', 'lastRow');
		
<?php 
        $zaehler += 1;
    }   // end while
?>
    });


	function task_text_onfocus(){
		task_text_saved.innerHTML = "mit ENTER speichern!";
		let task_textarea = document.getElementById("task_text");
		task_textarea.select();
	}

	</script>
<?php 
    $zusaetzliche_variablen = array("task_id" => "$task_id");
    z_ajax_blur("task_text", "task_text_save.php", "POST", "task_text", $zusaetzliche_variablen, "task_text_saved");
    z_ajax_enter("task_text", "task_text_save.php", "POST", "task_text", $zusaetzliche_variablen, "task_text_saved");

?>


<br/>
<table style="background-color:lightgray">
	<tr id="lastRow"></tr>
</table>
<h2>Gleichung eingeben:</h2>
<?php
    equation_input("save", $task_id);
?>
<?php
	require_once('footer.php');			
?>
