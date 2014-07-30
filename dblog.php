<?php
require('dbconnect.php');

function insertCreditLog($vamount, $vorigin, $vdestination, $vdatetime) {
	global $pasaload_tx_tbl;	
	global $pasaload_link;
	mysql_query("insert into `$pasaload_tx_tbl` (`amount`,`origin`,`destination`,`datetime`) values ($vamount,'$vorigin','$vdestination','$vdatetime');", $pasaload_link);
	return mysql_insert_id($pasaload_link);
}


function dblog($vrawtext) {
	define('LOG_FAIL'	,  0);
	define('LOG_INVALID', -1);
	define('PATTERN_SMART', "/(?P<day>\d*)-(?P<month>.{3})\s(?P<time>.{8}):P(?P<amount>\d*) has been transferred from (?P<origin>\d*) to (?P<destination>\d*)./");
	define('PATTERN_GLOBE', "/You have just been shared P(?P<amount>\d*) by\s(?P<origin>\d*).\sP(\d*) load will expire in (\d*) day\(s\). TransID: (\d*) (?P<month>\d*)\/(?P<day>\d*)\/(?P<year>\d*) (?P<time>.{7})./");
	define('PATTERN_SUN', "/You have been given Php(?P<amount>\d*) by (?P<origin>\d*)./");
	define('PATTERN_MOBILE', "/^(?<country>0|63|\+63)(?<telco>9\d{2})(?<number>\d{7})$/");
	$patterns = array('SMART' => PATTERN_SMART, 'GLOBE' => PATTERN_GLOBE, 'SUN' => PATTERN_SUN);
	$subject = $vrawtext['Message'];
	foreach ($patterns as $telco => $pattern) {
		if (preg_match($pattern, $subject, $matches)) {
			switch ($telco) {
				case 'SMART':
					$date = date("Y-m-d H:i:s", strtotime($matches['month'] . " " . $matches['day'] . ", " . date('Y') . " " . $matches['time']));
					break;
				case 'GLOBE':
					$date = date("Y-m-d H:i:s", strtotime($matches['year'] . "-" . $matches['month'] . "-" . $matches['day'] . " " . $matches['time']));
					break;
				default:
					$date = date("Y-m-d H:i:s", strtotime($vrawtext['Received']));		
			}
			
			$id = insertCreditLog($matches['amount'], $matches['origin'], $matches['destination'], $date);
			if ($id > 0) {
				if (preg_match(PATTERN_MOBILE, $matches['origin'], $mobile_matches)) {
					$mobile = '63' . $mobile_matches['telco'] . $mobile_matches['number'];
					return $mobile . " " . "$id" . " " . $matches['amount'];
				}	
				else
					return LOG_INVALID;
			}
			else
				return LOG_INVALID;
		}
	}
	return LOG_FAIL;
}
?>