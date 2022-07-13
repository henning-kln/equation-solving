<?php 
	$z_require_erlaubt = true;
	require_once '../include/config.php';

	// wenn ein display mit GET uebergeben wurde
	if(isset($_GET['style'])){
		$GLOBALS['z_style_aktueller_style'] = $_GET['style'];
		$the_style = $GLOBALS['z_style_aktueller_style'];
		setcookie('style', $z_style_aktueller_style, time() + (86400 * 30), '/');
		//echo "<font color='red'>style_change: $the_style</font><br/>";
	}
?>