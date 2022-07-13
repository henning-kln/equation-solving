<?php
  require_once("../include_math/equation_buttons.php");

  function equation_solve($equation_id, $equation, $variable, $intervalLeftSide, $intervalRightSide, $solution_id, $nickname){  
      $variableToLowerCase = strtolower($variable);
      
?>

    <br/>  
    <table id="mainTable" style="background-color:white">
      <tr>
      	<td colspan=5>
      		<h1><?php if($nickname != null)echo "$nickname: "; ?>L&ouml;se die Gleichung Schritt f&uuml;r Schritt!</h1>
        	<p><i>Die Gleichung hat <b><span  id="numberOfSolutions">2</span></b> L&ouml;sung(en)&nbsp;&nbsp;&nbsp;</i><i style="font-size:small">im Intervall <?php echo "[$intervalLeftSide|$intervalRightSide]"; ?></i></p>
        </td>
      </tr> 
      <tr id="rowSeparatingTaskAndCalculation">
      	<td colspan=5></td>
      </tr>
      <tr id="rowSeparatingCalculationAndNextStep">
      	<td colspan=5></td>
      </tr>
      <tr id="nextStepTableRow">
        <th>n&auml;chster<br/>Schritt:</th>
        <td><div id="fieldFormula1"></div></td>
        <td class="equalitySign" id="nextStepEqualitySign">=</td>
        <td><div id="fieldFormula2"></div></td>
        <td id="checkIsEqualResultSymbol"></td>
      </tr>
      <tr>
        <th>Eingabe:</th>
        <td><input type="text" id="fieldExpression1" readonly/></td>
        <td class="equalitySign">=</td>
        <td><input type="text" id="fieldExpression2" readonly/></td>
        <td></td>
      </tr>
      <tr>
        <th>Buttons:</th>
        <td colspan=3>
<?php 
    equation_buttons($variable);
?>
        </td>
        <td></td>
      </tr>
      <tr>
      	<th>richtig?</th>
      	<td style="vertical-align:middle;align:center" colspan = 3>
    			<p>
    			<button class="button" id="buttonCheckIsEqual" onclick="checkIsEqual()">pr&uuml;fen</button>
    			</p>
    		
      		<div id="checkIsEqualResult"></div>
      	</td>  	
        <td></td>
      </tr>
    </table>
    
    <br/>
    
    <script>
    
      const smileyCorrect = "&#x1F60A;";
      const smileyFalse = "&#x1F615;";
	  const unEqual = "&ne;";
      const colorFalse = "#FFB6C1";
      
      
      const equation = "<?php echo($equation);?>";
      var variable = "<?php echo($variableToLowerCase); ?>";
      var stepOfCalculation = 0;
      
      // results (array of float) of the left side of the current equation
      var results1;
      // results (array of float) of the right side of the current equation 
      var results2;
      
      // the fieldExpression, that currently has the focus
      var fieldExpressionWithFocus = null;
    

    
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
    	        checkIsEqual();
    	    }
    	});
    
      const rowSeparatingTaskAndCalculation = document.getElementById('rowSeparatingTaskAndCalculation');
      const rowSeparatingCalculationAndNextStep = document.getElementById('rowSeparatingCalculationAndNextStep');
      const nextStepTableRow = document.getElementById('nextStepTableRow');
      
      
      const fieldFormula1 = document.getElementById('fieldFormula1');
      const tdCheckEqualitySign = document.getElementById('nextStepEqualitySign');
      const fieldFormula2 = document.getElementById('fieldFormula2');
      
      const fieldCheckIsEqualResult = document.getElementById('checkIsEqualResult');
      const tdCheckIsEqualResultSymbol = document.getElementById('checkIsEqualResultSymbol');
        
      
      // display task:
      // task is displayed as one (or more) TR before rowSeperatingTaskAndCalculation
      $(document).ready(function(){          
      	 displayTaskEquation(equation);
<?php
//TODO
    $db_abfrage = "SELECT equation FROM equ_step WHERE equ_solution_id = '$solution_id' ORDER BY id ";
    $ergebnis = z_db_sql_statement_ausfuehren($db_abfrage);
    while($zeile = z_db_zeile_auslesen($ergebnis)){
        $equation = $zeile[0];
        echo "appendSolutionStepToSolutionDisplay('$equation');\n";
    }
?>
      });
      
      const solutions = findSolutions();
    
      const spanNumberOfSolutions = document.getElementById('numberOfSolutions');
      if(solutions === null){
    	  	spanNumberOfSolutions.innerHTML = "keine!!! ";	  
      }
      else{
      	spanNumberOfSolutions.innerHTML = ""+solutions.length;
      }
      const variablesValues = [];
      var i;
      if(solutions !== null){
          for(i=0; i<solutions.length; i++){
        	  variablesValues.push({<?php echo(strtolower($variable)); ?>: solutions[i]});
          }
      }
      
      function displayTaskEquation(equation){
    	  	displayEquationAsTR(equation,"task", "","0","0","Aufgabe", "task", "rowSeparatingTaskAndCalculation");         
      }
        
      function appendSolutionStepToSolutionDisplay(equation){
		  if(stepOfCalculation != 0){
			  
			  let solutionStep = equation;
			      
    		  //alert(solutionStep);
              //display as new table row            
              displayEquationAsTR(solutionStep,"_step", "","0","0","", "correctSolution", "rowSeparatingCalculationAndNextStep");              
		  }
    	  fieldExpression1.value = "";
    	  fieldExpression2.value = "";
    	  
    	  fieldFormula1.innerHTML = "";
    	  fieldFormula2.innerHTML = "";
    	  
    	  results1 = [];
    	  results2 = [];
    	  
    	  fieldExpression1.focus();
    	  stepOfCalculation += 1;
      }
    	
      function getSideOfEquation(equation,side){
    	  try{
    		  return equation.split("=")[side-1];		  		  
    	  }
    	  catch(err){
    		  alert("error in getSideOfEquation("+equation+","+side+")");
    		  return "";
    	  }
    	  
      }
      
      // method checks, whether the results of both sides are equal
      // this check is only APPROXIMATE!
      // if the difference is smaller than 0.001, then the result is considered equal
      function checkIsEqual(){
    	  let isEqual = false;
    	  var i;
    	  for(i=0; i<results1.length; i++){
    		  if(Math.abs(results1[i]-results2[i]) < 0.001){
    			  isEqual = true;
    		  }
    	  }
    	  if(isEqual){
    		  fieldCheckIsEqualResult.innerHTML = "richtig! "+smileyCorrect;
    		  tdCheckEqualitySign.innerHTML = "=";
    		  nextStepTableRow.style.backgroundColor = "white"; 
    		  saveSolutionStepToDataBase(fieldExpression1.value,fieldExpression2.value);
    		  let equation = convertToMathExpression(fieldExpression1.value)+"="+convertToMathExpression(fieldExpression2.value);
			  //alert(equation);
    		  appendSolutionStepToSolutionDisplay(equation);
    	  }
    	  else{
    		  fieldCheckIsEqualResult.innerHTML = "falsch! "+smileyFalse;	
    		  tdCheckEqualitySign.innerHTML = unEqual;
    		  tdCheckIsEqualResultSymbol.innerHTML = smileyFalse;
    		  nextStepTableRow.style.backgroundColor = colorFalse; 
    	  }
    	  //focus on Left side
    	  fieldExpression2.blur();
    	  fieldExpression1.focus();
      }

      function saveSolutionStepToDataBase(equationLeftSide,equationRightSide){
		  let mathExpressionLeft = convertToMathExpression(equationLeftSide);
		  let mathExpressionRight = convertToMathExpression(equationRightSide); 
		  let solutionStep = mathExpressionLeft +"="+mathExpressionRight;

		  // save to database
		  $.get("insert_solution_step_in_db.php", {step: solutionStep, solution_id:<?php echo $solution_id;?>})
		  .done(function( data ) {
			  //alert("Meldung: "+data);
		  });
      }
    
      
      // returns the formula-display for an expression as a <td>-Tag
      function getFormulaAsTD(expression){
    	    let node = null;
    
    	    try {
    	      // parse the expression
    	      node = math.parse(expression)
    	    }
    	    catch (err) {
    	      alert("getFormulaAsTD("+expression+"): Expression cannot be converted.");
    	      return "Error";
    	    }
    	    
    	    let formulaNode = document.createElement("TD");
    	    try {
    	      // export the expression to LaTeX
    	      const latex = node ? node.toTex({parenthesis: parenthesis, implicit: implicit}) : ''
    	      //console.log('LaTeX expression:', latex)
    
    	      // display and re-render the expression
    	      MathJax.typesetClear();
    	      formulaNode.appendChild(mj(latex));
    	    }
    	    catch (err) {
    		      alert("getFormulaAsTD("+expression+"): Expression cannot be converted.");
    		      formulaNode.innerHTML = "Error";	    	
    	    }
    	    return formulaNode;
    	  
      }
      
      function updateFormulaAndResult(){
    	    let results = [];
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
    
    	    fieldCheckIsEqualResult.innerHTML = "ungepr&uuml;ft";
    	    tdCheckIsEqualResultSymbol.innerHTML = "";
    	    
    	    let node = null;
    
    	    try {
    	      // parse the expression
    	      // replace all ':' by '/'
    	      node = math.parse(mathExpression);
    
    	      // evaluate the results of the expression
    	      var i;
    	      for(i=0; i<variablesValues.length; i++){
    		      results.push(math.format(node.compile().evaluate(variablesValues[i])));
    	      }
        	    if(number == 1){
        		    results1 = results;	    	
        	    }
        	    else if(number == 2){
        		    results2 = results;	    	
        	    }
    	    }
    	    catch (err) {
    	      results = [];
    	    }
    	    try {
    	      // export the expression to LaTeX
    	      node = math.parse(mathExpression);
    	      const latex = node ? node.toTex({parenthesis: parenthesis, implicit: implicit}) : ''
    	      //console.log('LaTeX expression:', latex)
    
    	      // display and re-render the expression
    	      MathJax.typesetClear();
    	      fieldFormula.innerHTML = '';
    	      fieldFormula.appendChild(mj(latex));
    	    }
    	    catch (err) {}
    	  
    	}
      
      	function findSolutions(){
      		let variable = "<?php echo $variable; ?>";
      		// area where a solution is searched
      		
      		let start = parseFloat("<?php echo $intervalLeftSide; ?>");
      		let end = parseFloat("<?php echo $intervalRightSide; ?>");
    
        	let solutions = findSolutionsInInterval(equation, variable, start, end);
        	if(solutions == null){
        		// there was an error 
        		// the error was displayed before
        		return null;
        	}
        	if(solutions.length == 0){
        		alert("Keine Lösung im Bereich "+start+" bis "+end);
        		return solutions;
        	}
    //    	if(solutions.length == 1){
    //   		alert("Eine Lösung im Bereich "+start+" bis "+end);
    //    		return solutions;
    //    	}
    //    	alert(""+solutions.length+" Lösungen");
          	return solutions;
      	}  
    </script>
    
<?php 
  } // end function
?>