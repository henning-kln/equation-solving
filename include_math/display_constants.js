      // technical variables for MathJax
      // possible values: 'keep', 'auto', 'all'
      const parenthesis = 'keep';	
      // possible values: 'hide', 'show'
      const implicit = 'hide';
          
      const mj = function (tex) {
    	    return MathJax.tex2svg(tex, {em: 16, ex: 6, display: false});
      }
