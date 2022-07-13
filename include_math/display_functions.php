<script> 
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

</script>
