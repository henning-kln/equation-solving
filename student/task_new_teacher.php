<?php
$z_require_erlaubt = true;
require_once('header_ohne_session_start.php');
?>

	<script>

    function tokenButtonNum(num){
          let text = num.toString();
          let tokenInput = document.getElementById('token');
      	  tokenInput.value = tokenInput.value + text;
    }
    
    function tokenButtonDelete(){
      let tokenInput = document.getElementById('token');
	  let text = tokenInput.value;
	  tokenInput.value = text.substring(0, text.length-1);
    }

    </script>
	<p>
		<b>Gib hier den Code der Aufgabe ein.</b><br/>
		<i>Den Code musst du von deiner Lehrerin / deinem Lehrer bekommen haben.</i>
	</p>
		<input type="image" src="../images/num0.png" onclick="tokenButtonNum(0);return false"/>
		<input type="image" src="../images/num1.png" onclick="tokenButtonNum(1);return false"/>
		<input type="image" src="../images/num2.png" onclick="tokenButtonNum(2);return false"/>
		<input type="image" src="../images/num3.png" onclick="tokenButtonNum(3);return false"/>
		<input type="image" src="../images/num4.png" onclick="tokenButtonNum(4);return false"/>
		<input type="image" src="../images/num5.png" onclick="tokenButtonNum(5);return false"/>
		<br/>
		<input type="image" src="../images/num6.png" onclick="tokenButtonNum(6);return false"/>
		<input type="image" src="../images/num7.png" onclick="tokenButtonNum(7);return false"/>
		<input type="image" src="../images/num8.png" onclick="tokenButtonNum(8);return false"/>
		<input type="image" src="../images/num9.png" onclick="tokenButtonNum(9);return false"/>
		<input type="image" src="../images/del.png" onclick="tokenButtonDelete();return false"/>
		<br/>
 	<form action="task.php" method="GET">
    	<input type="text" id="token" name="token" value="" length=10 readonly>
		<br/><br/>
    	<button class="button" style="font-size:large;">los geht's!</button>
    </form>
<?php     
    require_once('footer.php');
?>
