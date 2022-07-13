<?php
	$z_require_erlaubt = true;
	require_once('header.php');

?>
	<h1><?php echo "$z_user"; ?>: Home</h1>
	<p> Hallo <?php echo "$z_user_login"?>, das kannst du alles tun:
	</p>
	<p>
		<?php require('menu.php'); ?>
	</p>
						
<?php
	require_once('footer.php');			
?>