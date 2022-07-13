/*
Copyright (C) 2021  Andreas Kaibel

This program is free software; you can redistribute it and/or modify it 
under the terms of the GNU General Public License as published by the Free Software Foundation; 
either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
See the GNU General Public License for more details.

For a copy of the GNU General Public License see <http://www.gnu.org/licenses/>. 

Get source-code at GitHub: https://github.com/akaibel/equation-solving
*/

/**
 * 
 * @param {*} expression_string the mathematical expression (as string) to display
 * @param {*} html_tag the tag where the string should be displayed
 */
function display_math_expression_in_html_tag(mathExpression, html_tag){
	
	  //var mathExpression = convertToMathExpression(expression_string);
	
	let node = null;

	try {
		// export the expression to LaTeX
		node = math.parse(mathExpression);
		const latex = node ? node.toTex({parenthesis: parenthesis, implicit: implicit}) : ''
		//console.log('LaTeX expression:', latex)

		// display and re-render the expression
		MathJax.typesetClear();
		html_tag.innerHTML = '';
		html_tag.appendChild(mj(latex));
	}
    catch (err) {html_tag.innerHTML = err.message;}
}


// returns the formula-display for an expression as a <td>-Tag
function getFormulaAsTD(expression){
	expression = convertToMathExpression(expression);
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


     
    /**
	 * TODO: This function should be used everywhere, where possible.
     *       Because it can do about everything needed.
     *       D.h. das ist die eierlegende Wollmilchsau!!
	 * displays an equation as TR 
	 * BEFORE the TR with ID beforeTableRowId
     * if beforeTableRowId is null or "null", then the new TR is not appended, but it is returned.
     * parameters: currentEquation,equationId, variable,intervalLeft,intervalRight,headerText, action, beforeTableRowId
     * action is one of "delete", "task" "solution", "correctSolution", "wrongSolution", "none"     * 
	 */ 
	function displayEquationAsTR(currentEquation,equationId, variable,intervalLeft,intervalRight,headerText, action, beforeTableRowId){
     	  //alert(currentEquation+"\n"+variable+"\n"+intervalLeft+"\n"+intervalRight+"\n"+beforeTableRowId);

     	  	
    	  let newTableRow = document.createElement("TR");
		  if(action == "task"){
			newTableRow.style.backgroundColor = "lightgray";
		  }
		  if(beforeTableRowId !== null && beforeTableRowId !== "null" )
		  {
  		    let beforeTableRow = document.getElementById(beforeTableRowId);
		  	let rowParent = beforeTableRow.parentNode;
    	  	rowParent.insertBefore(newTableRow, beforeTableRow);
		  	newTableRow.id = "equation"+equationId;
		  }
    	  let headerTH = document.createElement("TH");
		  headerTH.innerHTML = headerText;
		  if(action == "task"){
			headerTH.style.backgroundColor = "lightgray";
		  }
    	  
    	  let equalitySignTD = document.createElement("TD");
    	  equalitySignTD.classList.add('equalitySign');
    	  equalitySignTD.innerHTML = "=";
    	  
    	  let leftSideTD = getFormulaAsTD(getSideOfEquation(currentEquation,1));
    	  let rightSideTD = getFormulaAsTD(getSideOfEquation(currentEquation,2));

		  let actionTD = document.createElement("TD");
		  if(action == "solution"){
			  let solutionButton = '<button class="button" onclick="showSolutions(\''+currentEquation+'\',\''+variable+'\',\''+intervalLeft+'\',\''+intervalRight+'\');return false;" style="font-size:10pt;">L&ouml;sung(en)</button>';
			  actionTD.innerHTML = solutionButton;
		  }
		  else if(action == "delete"){
			  let deleteButton = '<button class="button" onclick="deleteEquation(\''+equationId+'\'); return false" style="font-size:10pt;">l&ouml;schen</button>';
			  actionTD.innerHTML = deleteButton;
		  }
		  else if(action == "correctSolution"){
			  actionTD.innerHTML = smileyCorrect;
		  }
		  else if(action == "falseSolution"){
			  actionTD.innerHTML = smileyFalse;
		  }
		  else if(action == "none" || action == "task"){
			  actionTD.innerHTML = "&nbsp;";
		  }
		  else{
			  actionTD.innerHTML = "Fehler in displayEquationAsTR";  
		  }

    	  newTableRow.appendChild(headerTH);
    	  newTableRow.appendChild(leftSideTD);
    	  newTableRow.appendChild(equalitySignTD);
    	  newTableRow.appendChild(rightSideTD);
		  newTableRow.appendChild(actionTD);

		  return newTableRow;
      }

	  function showSolutions(equation,variable,intervalLeftString,intervalRightString){
		let solutions = findSolutionsInInterval(equation,variable,Number(intervalLeftString),Number(intervalRightString));
		let solutionsDisplayString = "";
		for(i=0; i<solutions.length; i++){
			solutionsDisplayString += parseFloat(solutions[i]).toFixed(3)+"\n";
		}
		alert(solutionsDisplayString);
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
    		  appendCurrentExpressionToSolution();
    	  }
    	  else{
    		  fieldCheckIsEqualResult.innerHTML = "falsch! "+smileyFalse;	
    		  tdCheckIsEqualResultSymbol.innerHTML = smileyFalse;
    	  }
    	  //focus on Left side
    	  fieldExpression2.blur();
    	  fieldExpression1.focus();
      }

  	  function deleteEquation(equationId){
  		let reallyDelete = confirm("Soll die Gleichung wirklich geloescht werden?");
  		if(reallyDelete == false){
  			return;
  		}
  		$.post('../teacher/equation_delete.php',  
          	   { equation_id: equationId }, 
          	   function(data) {
  				  if(data != "success"){
  				      alert("Die Aufgabe wurde schon bearbeitet.\nSie kann deswegen nicht mehr geloescht werden.");        					
  				  }
  				  else{
  					//rowParent.removeChild(tableRow);
  					let tableRow = document.getElementById("equation"+equationId);
  					tableRow.parentNode.removeChild(tableRow);
  					//alert("geloescht");
  					
  				}
  		});

  		//alert("delete: "+equationId);
  	  }

    
    
      

