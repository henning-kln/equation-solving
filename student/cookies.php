<?php
	if(!isset($z_require_erlaubt) || $z_require_erlaubt != true){ 
		die('<p align="center"><font color="red">config.php: Unerlaubter Aufruf des Skriptes!</font></p>');
	}
	
	$cookie_max_number_of_entries= 20;
	$cookie_name = "SIBI_Gleichungen";
	$cookie_duration_in_days = 30;
	$cookie_separator = ",";
	
	// inserts an token into the cookie at the first position.
	// if there are too many tokens, then the last one is removed.
	function cookie_insert_token($token){
	    global $cookie_max_number_of_entries, $cookie_name, $cookie_duration_in_days, $cookie_separator;
	    if(!isset($_COOKIE[$cookie_name])){
	        //z_konsole("neuer Cookie: $cookie_name");
	        setcookie($cookie_name, $token, time() + (86400 * $cookie_duration_in_days), "/"); // 86400 = 1 day
	    }
	    else{
	        $cookie_content = $_COOKIE[$cookie_name];
	        $tokenArray = explode(",", $cookie_content);
	        $cookie_content_new = "";
	        if(sizeof($tokenArray)<$cookie_max_number_of_entries){
	            $cookie_content_new = $token.",".$cookie_content;
	        }
	        else{
	            $cookie_content_new = $token.",".substr($cookie_content, 0, strrpos($cookie_content, ","));
	        }
	        setcookie($cookie_name, $cookie_content_new, time() + (86400 * $cookie_duration_in_days), "/"); // 86400 = 1 day
	    }
	}
	
	// returns an Array with all tokens
	function cookie_get_tokens(){
	    global $cookie_name, $cookie_duration_in_days, $cookie_separator;
	    $tokenArray = array();
	    if(!isset($_COOKIE[$cookie_name])){
	        //z_konsole("kein Cookie");
	        return $tokenArray;
	    }
	    else{
	        $cookie_content =  $_COOKIE[$cookie_name];
	        $tokenArray = explode($cookie_separator, $cookie_content);
	        // update cookie lifetime
	        setcookie($cookie_name, $cookie_content, time() + (86400 * $cookie_duration_in_days), "/"); // 86400 = 1 day
	        return $tokenArray;
	    }        
	}
?>
	