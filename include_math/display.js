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

  let replaceCursorPosition;

	document.addEventListener('DOMContentLoaded', function(event) {
  		replaceCursorPosition = false;
		
		// beende das Script, wenn es fieldExpression1 oder fieldExpression2 gar nicht gibt
		if(typeof(fieldExpression1) === 'undefined' || typeof(fieldExpression2) === 'undefined') return;
		
	    fieldExpression1.value = " ";
		fieldExpression2.value = " ";

  		fieldExpression1.oninput = function () {
	    	fieldExpression1.value = fieldExpression1.value.toLowerCase();
			updateFormulaAndResult();
		}  
		
		fieldExpression1.onfocus = function(){
			fieldExpressionWithFocus = fieldExpression1; 
			if(fieldExpressionWithFocus.value === ""){
				//console.log("empty 1");
			    fieldExpressionWithFocus.value = " ";	  
			}
			let cursorpos = getCursorPosition(fieldExpressionWithFocus);
			//console.log("cursor1: "+cursorpos);
			if(cursorpos == 0){
				cursorpos = fieldExpressionWithFocus.value.length-1;
				fieldExpressionWithFocus.setSelectionRange(cursorpos,cursorpos+1);
			}
			if(fieldExpressionWithFocus.value === " "){
				//console.log("blank 1");
			    fieldExpressionWithFocus.setSelectionRange(0,1);	  
			}
		}  

		fieldExpression1.addEventListener("keyup", function(event) {
		    if (event.key === "Enter") {
		        fieldExpression2.focus();
		    }
		});


  		fieldExpression2.oninput = function () {
	    	fieldExpression2.value = fieldExpression2.value.toLowerCase();
			updateFormulaAndResult();
		}  
		
		fieldExpression2.onfocus = function(){
			fieldExpressionWithFocus = fieldExpression2;  
			if(fieldExpressionWithFocus.value === ""){
				//console.log("empty 2");
			    fieldExpressionWithFocus.value = " ";	  
			}
			let cursorpos = getCursorPosition(fieldExpressionWithFocus);
			//console.log("cursor2: "+cursorpos);
			if(cursorpos == 0){
				cursorpos = fieldExpressionWithFocus.value.length-1;
				fieldExpressionWithFocus.setSelectionRange(cursorpos,cursorpos+1);
			}
			if(fieldExpressionWithFocus.value === " "){
				//console.log("blank 2");
			    fieldExpressionWithFocus.setSelectionRange(0,1);	  
			}
		}  

		fieldExpression2.addEventListener("keyup", function(event) {
		    if (event.key === "Enter") {
		        fieldExpression1.focus();
		    }
		});

  		fieldExpression2.focus();
  		fieldExpression1.focus();

	});

  


  // inserts text into fieldExpressionWithFocus
  // selects numSelectedLetters starting at selectionStartPosition
  function insertIntoFieldExpressionWithFocus(text,selectionStartPosition,numSelectedLetters){
	  if(fieldExpressionWithFocus == null){return;}
	  if(text.length>1) selectionStartPosition -= 1;
      fieldExpressionWithFocus.focus();
	  checkReplaceCursorPosition();
	  let cursorPosition = getCursorPosition(fieldExpressionWithFocus);
	  fieldExpressionWithFocus.value = insertAtIndex(fieldExpressionWithFocus.value, cursorPosition-1, text);
	  updateFormulaAndResult();
	  setCursorPosition(fieldExpressionWithFocus, cursorPosition+selectionStartPosition+1);
	  fieldExpressionWithFocus.setSelectionRange(cursorPosition+selectionStartPosition,cursorPosition+selectionStartPosition+numSelectedLetters);	  
  }

  function checkReplaceCursorPosition(){
	if(replaceCursorPosition == true){
	  replaceCursorPosition = false;
	  if(fieldExpressionWithFocus == null){return;}
	  let cursorPosition = getCursorPosition(fieldExpressionWithFocus);
	  let text = fieldExpressionWithFocus.value;
	  fieldExpressionWithFocus.value = text.substring(0, cursorPosition-1) + text.substring(cursorPosition);
      if(cursorPosition < 1) cursorPosition = 1;
	  setCursorPosition(fieldExpressionWithFocus,cursorPosition);	
	}
  }

  function buttonNum(num){
	  insertIntoFieldExpressionWithFocus(num.toString(),0,1);	
  }
  
  function buttonNumPI(num){
	  insertIntoFieldExpressionWithFocus('pi',2,1);	
  }
  
  function buttonNumE(num){
	  insertIntoFieldExpressionWithFocus('e',0,1);	
  }
  
  function buttonNumVariable(){
	  insertIntoFieldExpressionWithFocus(variable,0,1);	
  }
  
  function buttonNumComma(){
	  insertIntoFieldExpressionWithFocus(",",0,1);	
  }
  
  function buttonSemicolon(){
	  insertIntoFieldExpressionWithFocus(";",0,1);	
  }
  
  // insert sqrt(0) into fieldExpressionWithFocus at the cursorposition. 
  // set the cursor on the 0. 
  function buttonInsertSqrt(){
	  insertIntoFieldExpressionWithFocus("wurzel(2)",7,1);
	  replaceCursorPosition = true;
  }
  
  function buttonInsertPow(){
	  insertIntoFieldExpressionWithFocus("^2",1,1);
	  replaceCursorPosition = true;
  }
  
  function buttonInsertPlus(){
	  insertIntoFieldExpressionWithFocus("+2",1,1);
	  replaceCursorPosition = true;
  }

  function buttonInsertMinus(){
	  insertIntoFieldExpressionWithFocus("-2",1,1);
	  replaceCursorPosition = true;
  }

  function buttonInsertMult(){
	  insertIntoFieldExpressionWithFocus("*2",1,1);
	  replaceCursorPosition = true;
  }

  function buttonInsertDiv(){
	  insertIntoFieldExpressionWithFocus(":2",1,1);
	  replaceCursorPosition = true;
  }

  function buttonInsertLog(){
	  insertIntoFieldExpressionWithFocus("log(2;7)",4,1);
	  replaceCursorPosition = true;
  }
  
  function buttonInsertFract(){
	  insertIntoFieldExpressionWithFocus("/2",1,1);
	  replaceCursorPosition = true;
  }
  
  function buttonInsertNthRoot(){
	  insertIntoFieldExpressionWithFocus("ntewurzel(3;5)",10,1);
	  replaceCursorPosition = true;
  }
  
  function buttonInsertSin(){
	  insertIntoFieldExpressionWithFocus("sin(2)",4,1);
	  replaceCursorPosition = true;
  }

  function buttonInsertCos(){
	  insertIntoFieldExpressionWithFocus("cos(2)",4,1);
	  replaceCursorPosition = true;
  }

  function buttonInsertTan(){
	  insertIntoFieldExpressionWithFocus("tan(2)",4,1);
	  replaceCursorPosition = true;
  }

  function buttonBracketOpen(){
	  insertIntoFieldExpressionWithFocus("(2)",1,1);
	  replaceCursorPosition = true;
  }
  
  function buttonBracketClose(){
	  insertIntoFieldExpressionWithFocus(")",1,0);
  }
  
  function buttonBack(){
	  replaceCursorPosition = false;
	  let cursorPosition = getCursorPosition(fieldExpressionWithFocus);
      cursorPosition -= 2;
	  if(cursorPosition <0){
		cursorPosition = 0;
    	setCursorPosition(fieldExpressionWithFocus,cursorPosition);
  	    fieldExpressionWithFocus.setSelectionRange(cursorPosition,cursorPosition+1);	  
        return;
	  }
	  setCursorPosition(fieldExpressionWithFocus,cursorPosition);
	  fieldExpressionWithFocus.setSelectionRange(cursorPosition,cursorPosition+1);	  
  }
  
  function buttonForward(){
	  replaceCursorPosition = false;
	  let cursorPosition = getCursorPosition(fieldExpressionWithFocus);
      if(cursorPosition > fieldExpressionWithFocus.value.length-1){
		cursorPosition -= 1;
	  }
	  setCursorPosition(fieldExpressionWithFocus,cursorPosition);
	  fieldExpressionWithFocus.setSelectionRange(cursorPosition,cursorPosition+1);	  
  }

  function buttonDelete(){
	  let cursorPosition = getCursorPosition(fieldExpressionWithFocus);
	  let currentLetter = fieldExpressionWithFocus.value.charAt(cursorPosition-1);
	  if(currentLetter === ' '){
		fieldExpressionWithFocus.focus();
		return;
	  } 
	  replaceCursorPosition = true;
	  checkReplaceCursorPosition();
	  cursorPosition = getCursorPosition(fieldExpressionWithFocus);
	  
	  fieldExpressionWithFocus.setSelectionRange(cursorPosition-1,cursorPosition);	 
	  updateFormulaAndResult(); 
  }

  function insertAtIndex(text, index, inserted_text)
  {
   let result = text.substring(0, index) + inserted_text + text.substring(index);
   return result;
  }
  
  function setCursorPosition(oField, pos) {
	  // Modern browsers
	  if (oField.setSelectionRange) {
	    oField.focus();
	    oField.setSelectionRange(pos, pos);
	  
	  // IE8 and below
	  } else if (oField.createTextRange) {
	    let range = oField.createTextRange();
	    range.collapse(true);
	    range.moveEnd('character', pos);
	    range.moveStart('character', pos);
	    range.select();
	  }
	}

  
  function getCursorPosition (oField) {

	  // Initialize
	  let iCaretPos = 0;

	  // IE Support
	  if (document.selection) {

	    // Set focus on the element
	    oField.focus();

	    // To get cursor position, get empty selection range
	    let oSel = document.selection.createRange();

	    // Move selection start to 0 position
	    oSel.moveStart('character', -oField.value.length);

	    // The caret position is selection length
	    iCaretPos = oSel.text.length;
	  }

	  // Firefox support
	  else if (oField.selectionStart || oField.selectionStart == '0')
	    iCaretPos = oField.selectionDirection=='backward' ? oField.selectionStart : oField.selectionEnd;

	  // Return position
	  return iCaretPos;
	}

  