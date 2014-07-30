<?php
	$pasaload_host			= '173.194.86.27';
	$pasaload_user			= 'root';
	$pasaload_password		= 'root';
	$pasaload_db			= 'smsdb';
	$pasaload_link			= mysql_connect($pasaload_host,$pasaload_user,$pasaload_password) or die(mysql_error() . "<h3>Could not connect to MySQL!</h3>\n");
	$pasaload_tx_tbl		= 'demo';	
	
	mysql_select_db($pasaload_db, $pasaload_link) or die("<h3>Could not select pasaload database!</h3>");
?>