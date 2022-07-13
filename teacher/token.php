<?php
	$z_require_erlaubt = true;
	require('header_ohne_session_start.php');
?>
	<h1>Registrierung als <?php echo "$z_user"; ?> f&uuml;r <?php echo "$z_config_projekt"; ?></h1>

	<p>
		Hier kannst du dich mit deinem Token registrieren.
	</p>
	<p>
		<form action="token_registrierung.php" method="POST">
			<table style="background-color:white; border-width:0px; width:auto">
				<tr>
					<td align = 'right'><b>Token:&nbsp;</b><input type="text" size=10 name="token"></td> 
				</tr>
			</table>
			<br/>
			<?php z_html_button_submit("registrieren"); ?>
		</form>
	</p>
	<p>
		<i>
			Du bist schon registriert?<br/>
			<?php z_html_button_link('Login', 'index.php'); ?>
		</i>
	</p>


<?php
	  require('footer.php');
?>	

