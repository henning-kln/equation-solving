<?php
    $z_require_erlaubt = true;
    require_once('header.php');
?>
<?php 	
	$teacher_id = $z_user_id;
	if(!isset($_GET['token'])){
	    z_fehlermeldung("Token unbekannt!");
	    die();
	}
	
	$token = z_db_text_erzeugen($_GET['token']);

	$select_statement =
	" SELECT t.id, t.titel, t.text,t.token, t.timestamp ".
	" FROM equ_task t ".
	" WHERE t.token = '$token'";
	
	// echo $select_statement."<br/><br/>";
	$result = z_db_sql_statement_ausfuehren($select_statement);
	$zeile = z_db_zeile_auslesen($result);

	$statement= $z_db_verbindung->prepare(
	    " SELECT t.id AS id, t.titel AS titel, t.text AS text,t.token AS token, t.timestamp AS timestamp, equ_teacher_id AS teacher_id ".
	    " FROM equ_task t ".
	    " WHERE t.token = ?"
	);
	$statement->bind_param("s",$token);
	$statement->execute();
	$result = $statement->get_result();
	$zeile = $result->fetch_assoc();
	$statement->close();
	if($zeile === NULL){
	    z_fehlermeldung("Token unbekannt!");
	    require_once('footer.php');
	    die();
	}
	//pruefen, ob diese Aufgabe dem Lehrer ueberhaupt gehoert.
	if($zeile['teacher_id'] != z_session_get_user_id())	{
	    z_fehlermeldung("Diese Aufgabe hast du nicht gestellt!");
	    require_once('footer.php');
	    die();
	}
	$task_id = $zeile['id'];
	$titel = $zeile['titel'];
	$text = $zeile['text'];
	$token = $zeile['token'];
	$datum= z_datum_aus_db($zeile['timestamp']);	
	
	
?>
	<br/>
    <h1>Aufgabe:</h1>
    <table style="background-color:white">
    	<tr>
    		<th>Link:</th>
    		<td style="font-size: x-large; font-weight: bold">sibiwiki.de/gl</td>
    	</tr>
    	<tr>
    		<th><br/>Code:<br/><br/></th>
    		<td style="font-size: xxx-large; font-weight: bold"><?php echo $token; ?></td>
    	</tr>
    	<tr>
    		<th>Datum:</th>
    		<td><?php echo $datum; ?></td>
    	</tr>
    	<tr>
    		<th>Link:<br/><i style="font-size:small;">(zum Verschicken)</i></th>
    		<td>
    			<input type="text" readonly id="link_text" size=80 value="<?php echo($z_config_root_verzeichnis."student/task.php?token=".$token);?>">
    		</td>
    	</tr>
    	<tr>
    		<th>Text:</th>
    		<td><?php echo $text; ?></td>
		</tr>
    </table>

    <h1>Gleichungen zu dieser Aufgabe:</h1>
    <table style="background-color:white">
    	<tr id="lastRow"></tr>
    </table>
		
<?php 
    $select_statement =
        " SELECT e.equation, e.variable, e.intervalLeft, e.intervalRight, e.id ".
        " FROM equ_equation e ".
        " WHERE e.equ_task_id= '$task_id'";
//    echo "<br/><br/>".$select_statement."<br/><br/>";
    $result = z_db_sql_statement_ausfuehren($select_statement);
    $equationCount = z_db_zeilen_zahl($result);
?>
    <script>
    	$(document).ready(function() {
            $("#link_text").focus(function() 
                    { 
                      $(this).select(); 
                    } 
              	);        	


<?php    
    $gleichungs_nummer = 1;
    while($zeile= z_db_zeile_auslesen($result)){
        $equation = $zeile[0];    
        $variableToUpperCase = $zeile[1];
        $variableToLowerCase = strtolower($variableToUpperCase);
        $intervalLeft = $zeile[2];
        $intervalRight = $zeile[3];
        $equationId = $zeile[4];
?>
			displayEquationAsTR('<?php echo $equation;?>','<?php echo $equationId;?>','<?php echo $variableToLowerCase;?>','<?php echo $intervalLeft;?>','<?php echo $intervalRight;?>','<?php echo "$gleichungs_nummer.";?>','solution','lastRow');
<?php 
        
        $gleichungs_nummer+=1;   
    } // end while
?>
    	}); // end $(document).ready
	</script>
	<!-- die Bearbeitungen anzeigen -->
    <script>
        var time_element;
        var timer;
        
        var interval_number;
        var abfrage_intervall_in_milli = 5000;		

		function minutesAndSeconds(seconds){
			let minutes = Math.floor(seconds/60);
			let sec = ""+seconds%60;
			if(sec.length == 1){
				sec = "0"+sec;
			}
			return ""+minutes+":"+sec;
		}
        
      //wird in regelmaessigen abstaenden aufgerufen
        function timerFunction(){
            let seconds = Math.round(interval_number*abfrage_intervall_in_milli/1000);
            let address = "task_details_bearbeitungen.php?task_id=<?php echo $task_id;?>&task_token=<?php echo $token; ?>&equation_count=<?php echo $equationCount;?>&time="+minutesAndSeconds(seconds);
        	$.get(address,function(data,status){
        			document.getElementById('bearbeitungen').innerHTML = data;
        	    	interval_number++;
            	    	
        	  });				
        }

        $(document).ready(function() {
        	interval_number = 0;
        	time_element = document.getElementById('time');
        	timerFunction();
        	timer = setInterval(timerFunction,abfrage_intervall_in_milli);
        });

    </script>
	<div id="bearbeitungen"></div>
		
<?php
	require_once('footer.php');			
?>
