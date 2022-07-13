<?php
	if (! isset ( $z_require_erlaubt ) || $z_require_erlaubt != true) {
		die ( '<p align="center"><font color="red">werkzeuge.php: Unerlaubter Aufruf des Skriptes!</font></p>' );
	}
?>

<script src="https://unpkg.com/mathjs@8.1.0/lib/browser/math.js"></script>
<script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
<script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-svg.js"></script>
<script src="../include_math/solve.js"></script>
<script src="../include_math/display_constants.js"></script>
<script src="../include_math/display.js"></script>
<script src="../include_math/display_functions.js"></script>
<?php require_once('../include_math/display_functions.php')?>

