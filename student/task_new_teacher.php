<?php
$z_require_erlaubt = true;
require_once('header_ohne_session_start.php');
?>
<head>
	<script>

    function tokenButtonNum(num){
          let text = num.toString();
          let tokenInput = document.getElementById('token');
      	  tokenInput.value = tokenInput.value + text;
    }
    
    function tokenButtonDelete(){
      let tokenInput = document.getElementById('token');
	  let text = tokenInput.value;
	  tokenInput.value = text.substring(0, text.length-1);
    }

    </script>
    <meta name="viewport" value="initial-scale">
    <link rel="apple-touch-icon" sizes="57x57" href="/gleichungen/icons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/gleichungen/icons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/gleichungen/icons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/gleichungen/icons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/gleichungen/icons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/gleichungen/icons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/gleichungen/icons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/gleichungen/icons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/gleichungen/icons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/gleichungen/icons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/gleichungen/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/gleichungen/icons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/gleichungen/icons/favicon-16x16.png">
    <link rel="manifest" href="/gleichungen/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/gleichungen/icons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <script>
        let pwaSupport = false;
        if ("serviceWorker" in navigator) {
            pwaSupport = true; // iOS 11 oder neuer
            window.addEventListener("load", function() {
                navigator.serviceWorker
                .register("/gleichungen/sw.js")
                .then(res => console.log("service worker registered"))
                .catch(err => console.log("service worker not registered", err))
            })
        }
    </script>
	</head>
	<p>
		<b>Gib hier den Code der Aufgabe ein.</b><br/>
		<i>Den Code musst du von deiner Lehrerin / deinem Lehrer bekommen haben.</i>
	</p>
		<input type="image" src="../images/num0.png" onclick="tokenButtonNum(0);return false"/>
		<input type="image" src="../images/num1.png" onclick="tokenButtonNum(1);return false"/>
		<input type="image" src="../images/num2.png" onclick="tokenButtonNum(2);return false"/>
		<input type="image" src="../images/num3.png" onclick="tokenButtonNum(3);return false"/>
		<input type="image" src="../images/num4.png" onclick="tokenButtonNum(4);return false"/>
		<input type="image" src="../images/num5.png" onclick="tokenButtonNum(5);return false"/>
		<br/>
		<input type="image" src="../images/num6.png" onclick="tokenButtonNum(6);return false"/>
		<input type="image" src="../images/num7.png" onclick="tokenButtonNum(7);return false"/>
		<input type="image" src="../images/num8.png" onclick="tokenButtonNum(8);return false"/>
		<input type="image" src="../images/num9.png" onclick="tokenButtonNum(9);return false"/>
		<input type="image" src="../images/del.png" onclick="tokenButtonDelete();return false"/>
		<br/>
 	<form action="task.php" method="GET">
    	<input type="text" id="token" name="token" value="" length=10 readonly>
		<br/><br/>
    	<button class="button" style="font-size:large;">los geht's!</button>
    </form>
<?php     
    require_once('footer.php');
?>
