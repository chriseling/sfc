<?
	include "functions.inc.php";
	
	$sql = "insert into stalking values ";
	foreach($hunter_fleets as $h) {
		$sql .= "('$h[0]','$h[1]'),";
	}
	mysql_query(rtrim($sql,","));