<?php 
	/**
	 * Wenn noch kein Style in $_COOKIE['style'] festgelegt ist, 
	 * dann oeffnet dieser Code ein modales Fenster, 
	 * mit dem der Style abgefragt und festgelegt wird. 
	 */
?>
	<!-- Style fuer die modale Abfrage -->
	<style>
		/* modaler Inhalt, um abzufragen, ob Desktop oder Mobil angezeigt werden soll */
		/* The Modal (background) */
		div#modale_style_abfrage {
		    display: none; /* Hidden by default */
		    position: fixed; /* Stay in place */
		    z-index: 1; /* Sit on top */
		    padding-top: 60px; /* Location of the box */
		    left: 0;
		    top: 0;
		    width: 100%; /* Full width */
		    height: 100%; /* Full height */
		    overflow: auto; /* Enable scroll if needed */
		    background-color: rgb(0,0,0); /* Fallback color */
		    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
		}
		
		
		/* Modal Content */
		div#modale_style_abfrage_inhalt {
		    background-color: #fefefe;
		    margin: auto;
		    padding: 20px;
			text-align:center;
		    border: 1px solid #888;
		    width: 80%;
		    font-weight: bold;
		    font-size: 125%;
		}
		
		/* The Modal-Button */
		.modale_style_abfrage_button {
		    color: #a0a0a0;
		    font-weight: bold;
		}
		
		.modale_style_abfrage_button:hover,
		.modale_style_abfrage_button:focus {
		    color: #000000;
		    text-decoration: none;
		    cursor: pointer;
		}
	</style>	

	<!-- modale Abfrage, ob Desktop oder Mobil -->
	<div id="modale_style_abfrage">
	  <div id="modale_style_abfrage_inhalt">
	    <div>Was f&uuml;r ein Ger&auml;t benutzt du?<br/><i></i>(hier anklicken!)</i></div><br/>
	    <div class="modale_style_abfrage_button" id="modale_style_abfrage_button_desktop">Computer</div>
	    <br/>
	    <div class="modale_style_abfrage_button" id="modale_style_abfrage_button_smartphone">Smartphone</div>
	  </div>
	</div>
	<!-- Ende der modalen Abfrage -->


<?php 
	require_once("../include/config.php");
?>

	<script type="text/javascript">
		function openModal(){
		    modal.style.display = "block";
		}
	
		// Get the modal
		var modal = document.getElementById('modale_style_abfrage');
		
		var buttonDesktop = document.getElementById("modale_style_abfrage_button_desktop");
		var buttonSmartphone = document.getElementById("modale_style_abfrage_button_smartphone");

		function displaySpeichern(gewaehlter_style){
			//alert("Typ: "+displayTyp);
		    $.get("../include/style_change.php",
		    	    {style:gewaehlter_style},
		    	    function(data, status){
		    	       //alert(data + "\nStatus: " + status);
		    	       location.reload();
		    	    });  	    
		}
		
		buttonDesktop.onclick = function() {
		    modal.style.display = "none";
		    displaySpeichern("<?php echo $z_style_desktop; ?>");
		}
		
		buttonSmartphone.onclick = function() {
		    modal.style.display = "none";
		    displaySpeichern("<?php echo $z_style_mobile; ?>");
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
		    if (event.target == modal) {
		        modal.style.display = "none";
		    }
		}
	</script>

	
<?php
	// wenn der Cookie 'style' nicht gesetzt ist, dann wird die modale Abfrage geoeffnet.
	if(!isset($_COOKIE['style'])){
?>
		<script type='text/javascript'>
			openModal();
		</script>
			
<?php 
	}
?>
