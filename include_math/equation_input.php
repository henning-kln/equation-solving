
<?php 
    require_once('../include/werkzeuge.php');
    require_once("../include_math/equation_buttons.php");
    
    
    function equation_input($action, $taskId){
        $submit_button_text = "";
        $action_page= "error.php";
        $readonly = "readonly";
        
        if($action == 'save'){
            $submit_button_text = "speichern";
            $action_page = 'equation_save.php';
            $readonly = "";
        }
        // wenn man die Gleichung direkt loesen will
        if($action == 'solve'){
            $submit_button_text = "l&ouml;sen";
            $action_page = 'equation_solve.php';
        }
        if($action_page == "error.php"){
            echo "<br/><font color='red'><b>Error!</b></font></br>";
            return;
        }
        
?>    
    
    
	<table id="mainTable" >
	  <tr id="equationRow" style="background-color:white">
	    <th>Gleichung:</th>
	    <td style="background-color: lightGray;"><div id="fieldFormula1"></div></td>
	    <td class="equalitySign" id="nextStepEqualitySign" style="background-color: lightGray;">=</td>
	    <td style="background-color: lightGray;"><div id="fieldFormula2"></div></td>
	    <td></td>
	  </tr>
	  <tr style="background-color:white">
	    <th>Eingabe:</th>
	    <td><input type="text" class="math" id="fieldExpression1" <?php echo $readonly; ?>/></td>
	    <td class="equalitySign">=</td>
	    <td><input type="text" class="math" id="fieldExpression2" <?php echo $readonly; ?>/></td>
	    <td></td>
	  </tr>
	  <tr id="variableRow" style="background-color:white">
	    <th>Variable:</th>
	    <td colspan=3>
	    	Variable: 
	    	<input type="text" class="math" id="variable" value="x" style="width:50px"/>
	    </td>
	    <td></td>
	  </tr>
	  <tr style="background-color:white">
	    <th>Buttons:</th>
	    <td colspan=3 >
<?php 
    equation_buttons("x");
?>
	    </td>
	    <td></td>
	  </tr>
	  <tr id="intervalRow" style="background-color:white">
	      <th>Bereich:</th>
	      <td colspan=3>
	    	Wo ist die L&ouml;sung?<br/> 
	    	Im Bereich<br/>
	    	von <input type="text" class="math" id="leftSide" value="-100"  style="width:50px"/> 
	    	bis <input type="text" class="math" id="rightSide" value="100" style="width:50px"/> </td>
	    <td></td>
	  </tr>
	  <tr id="isSolvableRow" style="background-color:white">
	  	<th>l&ouml;sbar?</th>
	  	<td style="vertical-align:center;align:center" colspan = 3>
			<button class="button" type="button" id="buttonFindResults" onclick="findSolutions(true)">L&ouml;sbarkeit pr&uuml;fen</button>
	  	</td>  	
	    <td></td>
	  </tr>
	  <tr id="solveRow" style="background-color:white">
	  	<th><?php echo $submit_button_text;?></th>
	  	<td align="center" colspan = 3>
<?php 
if($action == 'save'){
?>	  	
			<form id="equation_input_form" onsubmit="return onFormSubmit(event);"> 
<?php 
} 
else{
?>
			<form id="equation_input_form" method='POST' action="<?php echo $action_page; ?>" onsubmit="return onFormSubmit(event);"> 
<?php 
} 
?>
				<button class="button" type="submit" id="buttonSolve" type="submit" style="font-size:large">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $submit_button_text;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
				<input type="hidden" id="hiddenEquation" name="equation"/>	
				<input type="hidden" id="hiddenVariable" name="variable"/>
				<input type="hidden" id="hiddenLeftSide" name="leftSide"/>
				<input type="hidden" id="hiddenRightSide" name="rightSide"/>
				<input type="hidden" id="hiddenTaskId" name="taskId" value="<?php echo $taskId; ?>"/>
			</form>
	  	</td>  	
	    <td></td>
	  </tr>
<?php 
if($action == "save"){
?>
	  <tr style="background: none"><td colspan=5 >&nbsp;</td></tr>
	  <tr style="background-color:white">
	  <td>&nbsp;</td>
	  <td colspan = 3>
        &nbsp;
        <?php 
            $db_abfrage = "SELECT token FROM equ_task WHERE id = '$taskId'";
            $ergebnis = z_db_sql_statement_ausfuehren($db_abfrage);
            $zeile = z_db_zeile_auslesen($ergebnis);
            $task_token = $zeile[0];
            z_html_button_link("Aufgabe starten", "task_details.php?token=$task_token")?>
        &nbsp;	  
	  </td>
	  <td>&nbsp;</td>
	  </tr>
<?php 
}
?>	  
	</table>
    <br/><br/>
    
    <script>
    
      // the fieldExpression, that currently has the focus
      var fieldExpressionWithFocus = null;

      variable = "x";
      
      const fieldExpression1 = document.getElementById('fieldExpression1');
      fieldExpression1.oninput = function () {
    	    fieldExpression1.value = fieldExpression1.value.toLowerCase();
    		updateFormulaAndResult();  
      }
      fieldExpression1.onfocus = function(){
    		fieldExpressionWithFocus = fieldExpression1;  
      }
      fieldExpression1.addEventListener("keyup", function(event) {
    	    if (event.key === "Enter") {
    	        fieldExpression2.focus();
    	    }
    	});
    
      fieldExpression1.focus();
      
      const fieldExpression2 = document.getElementById('fieldExpression2');  
      fieldExpression2.oninput = function () {
    	    fieldExpression2.value = fieldExpression2.value.toLowerCase();
    		updateFormulaAndResult();  
      }
      fieldExpression2.onfocus = function(){
    		fieldExpressionWithFocus = fieldExpression2;  
      }
      fieldExpression2.addEventListener("keyup", function(event) {
    	    if (event.key === "Enter") {
    	       fieldVariable.focus();
    	    }
    	});
    
      const fieldVariable = document.getElementById('variable');  
	  fieldVariable.onfocus = function() {
		 fieldVariable.setSelectionRange(0,1);
	  }
      fieldVariable.oninput = function () {
		 var str = fieldVariable.value;
		 if(str.length != 1){
			 alert("Variablen muessen genau einen Buchstaben haben.");
		     fieldVariable.value = "x";
		     variable = "x";
			 return;
		 }
    	 if (str === "e" || str === "E") {
		       alert("e ist als Variable nicht erlaubt\n(e ist die eulersche Zahl)");
		       fieldVariable.value = "x";
			     variable = "x";
		       return;
		}
		var letters = /^[A-Za-z]+$/;
		if (! str.match(letters)){
			   alert("nur a bis z sind moeglich.");
		       fieldVariable.value = "x";
			   variable = "x";
			   return;
  	    }
    	fieldVariable.value = fieldVariable.value.toLowerCase();
	    variable = fieldVariable.value;
    	let buttonURL = "../images/let"+fieldVariable.value.toUpperCase()+".png";
    	let get_adress = "../include_math/getVariableButtonImage.php?url="+buttonURL;
    	$.get(get_adress, function(data, status){
        	 document.getElementById("buttonVariableDiv").innerHTML = data;    
		});

      }
      fieldVariable.addEventListener("keyup", function(event) {
    	    if (event.key === "Enter") {
    	       fieldLeftSide.focus();
    	    }
    	});
    
      const fieldLeftSide = document.getElementById('leftSide');  
      fieldVariable.addEventListener("keyup", function(event) {
    	    if (event.key === "Enter") {
    	       fieldRightSide.focus();
    	    }
    	});
    
      const fieldRightSide = document.getElementById('rightSide');  
      fieldVariable.addEventListener("keyup", function(event) {
    	    if (event.key === "Enter") {
    	       fieldRightSide.focus();
    	    }
    	});
    
      const equationRow = document.getElementById('equationRow');
        
      const fieldFormula1 = document.getElementById('fieldFormula1');
      const fieldFormula2 = document.getElementById('fieldFormula2');

      function onFormSubmit(event){
    	  if(findSolutions(false) == false){
//    		  event.preventDefault();
    		  return false;
    	  }

    	  // collect the data that must be submitted
    	  document.getElementById('hiddenEquation').value = getEquationFromFieldExpressions();
    	  document.getElementById('hiddenVariable').value = getVariableFromFieldVariable();
    	  document.getElementById('hiddenLeftSide').value = fieldLeftSide.value;
    	  document.getElementById('hiddenRightSide').value = fieldRightSide.value;
    	  return true;
      }

<?php 
    if($action == 'save'){
?>    

    	  // define callback for submitting the form
          $(document).ready(function(){
    	        form = $("#equation_input_form");  
    	        form.submit(function(e){
    	          e.preventDefault();
    	          if(onFormSubmit(e) == false){return;}
    	          fieldFormula1.innerHTML = '';
    	          fieldFormula2.innerHTML = '';
    	          fieldExpression1.value = '';
    	          fieldExpression2.value = '';

    	          $.post("<?php echo $action_page;?>",form.serialize(),function(data,status){
    		    	  let formEquation = document.getElementById('hiddenEquation').value;
    		    	  let formVariable = document.getElementById('hiddenVariable').value;
    		    	  let formLeftSide = document.getElementById('hiddenLeftSide').value;
    		    	  let formRightSide = document.getElementById('hiddenRightSide').value;
    		    	  if(status == "success"){
        		    	//alle Zeichen ersetzen, die keine Ziffern sind.
        		    	let newEquationId = data.replace(/[^0-9]/g,'');;
    		    	  	displayEquationAsTR(formEquation, newEquationId, formVariable,formLeftSide,formRightSide,"neu", "delete", "lastRow");
    		    	  }
    		    	  else{
        		    	  alert("Speichern der Gleichung ist fehlgeschlagen.");
    		    	  }		
    			    
    			  });
    	        });
    	      });
<?php 
    } // end if
?>              
      
      function updateFormulaAndResult(){
    	    let result;
    	    let fieldFormula;
    	    let fieldExpression;
    	    let number = -1;
    	    
    	    if(fieldExpressionWithFocus == fieldExpression1){
    		    fieldFormula = fieldFormula1;
    		    fieldExpression = fieldExpression1;	 
    		    number = 1;
    	    }
    	    else if(fieldExpressionWithFocus == fieldExpression2){
    		    fieldFormula = fieldFormula2;
    		    fieldExpression = fieldExpression2;
    		    number = 2;
    	    }
    	    else{
    	    	alert("no expression field has focus");
    	    	return;
    	    }
    	    var mathExpression = convertToMathExpression(fieldExpression.value);
						
			display_math_expression_in_html_tag(mathExpression, fieldFormula);
    	  
    	}
      
        function getEquationFromFieldExpressions(){
        	return convertToMathExpression(fieldExpression1.value+"="+fieldExpression2.value);
        }
        
        function getVariableFromFieldVariable(){
        	return fieldVariable.value.toLowerCase();
        }
      
        function findSolutions(showPositiveMessages){
      		let equation = getEquationFromFieldExpressions();
      		let variable = getVariableFromFieldVariable();
      		// area where a solution is searched
      		
      		let start = parseFloat(fieldLeftSide.value);
      		let end = parseFloat(fieldRightSide.value);
    
        	let solutions = findSolutionsInInterval(equation, variable, start, end);
        	if(solutions == null){
        		// there was an error 
        		// the error was displayed before
        		return false;
        	}
        	if(solutions.length == 0){
        		alert(unescape("Keine Loesung im Bereich "+start+" bis "+end));
        		return false;
        	}
        	//alert(solutions);
        	if(solutions.length == 1){
        		if(showPositiveMessages) 
<?php 
if($action == 'save'){
?>
            		alert(unescape("Eine Loesung")+"\n"+solutions[0].toFixed(3));
<?php 
}else{
?>
        		    alert(unescape("Eine Loesung"));					
<?php 
}
?>
        		return true;
        	}
        	if(showPositiveMessages){
<?php 
if($action == 'save'){
?>
				 let loesungenText = "";
				 for(i=0; i<solutions.length; i++)
					 loesungenText += solutions[i].toFixed(3)+"\n";
             	 alert(unescape(""+solutions.length+" Loesungen")+"\n"+loesungenText);
<?php 
}else{
?>
             	 alert(unescape(""+solutions.length+" Loesungen"));
<?php 
}
?>
        	}
        	return true;
        }
      
    </script>
<?php 
}
?>