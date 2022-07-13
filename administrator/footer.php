<?php
	if(!isset($z_require_erlaubt) || $z_require_erlaubt != true){ 
		z_html_warnung('<p>Unerlaubter Aufruf des Skriptes!</p>');
		die();
	}
	
	require("../include/scroll_button.php");

	require_once("../include/config.php");
?>

			<br/>
			<div style="font-size:75%">
				<table border=0 width=100%>
					<tr>
						<td align="left">
							Kontakt: <a href='mailto:<?php echo "$z_config_mailadresse"?>'><?php echo "$z_config_mailadresse"?></a>
						</td>
						<td align="left">
							<a href="datenschutz.php">Datenschutz</a>
						</td>
						<td align="right">
							<a href="impressum.php">Impressum</a>
						</td>
					</tr>
				</table>
			</div>
		</div>	<!-- end innerwrapper, started in header.php-->
	</div>		<!-- end wrapper, started in header.php -->	

<?php 
	require('../include/style_auswahl_abfrage.php');
?>

	
	</body>
</html>
