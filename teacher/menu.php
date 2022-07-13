<?php 
	$z_require_erlaubt = true;
	require('../include/config.php');
?>

<!-- <li><a href='index.php'>Login</a></li> -->
<?php 
if(z_session_get_user_id() == null){
?>
<li><a href='index.php'>Login</a></li>
<li><a href='token.php'>Token</a></li>
<?php 
}else{
?>
<li><a href='task_list.php'>meine Aufgaben</a></li>
<li><a href='task_create.php'>neue Aufgabe</a></li>
<li><a href='einstellungen.php'>Einstellungen</a></li>
<li><a href='howto.php'>Howto</a></li>
<li><a href='logout.php'>Logout</a></li>
<?php 
}
?>