<?php
	$z_require_erlaubt = true;
	require('header_ohne_session_start.php');
?>
		<h1>Darstellung &auml;ndern</h1>
		<p>
			bevorzugte Darstellung: 
			<select id="style_select" name="style" size="1">
				<option value='<?php echo $z_style_mobile; ?>' <?php if($z_style_aktueller_style == $z_style_mobile){echo 'selected';} ?>>
					Smartphone
				</option>
				<option value='<?php echo $z_style_desktop; ?>' <?php if($z_style_aktueller_style == $z_style_desktop){echo 'selected';} ?>>
					Desktop oder Tablet
				</option>
			</select>
		</p>

		<script type="text/javascript">
			var select = document.getElementById('style_select');
			
			function displaySpeichern(gewaehlter_style){
			    $.get("../include/style_change.php",
			    	    {style:gewaehlter_style},
			    	    function(data, status){
			    	       location.reload();
			    	    });  	    
			}
			
			select.onchange = function() {
				theStyle = this.options[this.selectedIndex].value;
			    displaySpeichern(theStyle);
			}			
		</script>


					
<?php		
		require("footer.php");
?>
