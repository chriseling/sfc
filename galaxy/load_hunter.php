<?
	$ip_address = "72.52.91.67";
	$player = "yegg";
	
	$worlds = array(1000000058754,1000000104604,1000000098914,1000000093198);
	$world = $worlds[array_rand($worlds)];
	
	include "functions.inc.php";
	
	
	lp("starting");

	sleep(rand(1,10)*60);

	human();
	
	$sql = "select * from stalking";
	$res = mysql_query($sql);
	$fleets = array();
	while($mr = mysql_fetch_assoc($res)) {
		$fleets[] = array($mr['galaxy'],$mr['system']);
	}
	
	
	foreach($fleets as $s) {
		$galaxy = $s[0];
		$system = $s[1];
		
		if($galaxy == 34 || $galaxy == 9) {
			continue;
		}

		lp("reading $galaxy $system");

		$url = "http://playstarfleet.com/galaxy/show?current_planet=$world&galaxy=$galaxy&solar_system=$system";				

		$content = sfc_get($url);
	
		hunter(preg_replace("/\n/","",$content));
		
		human();
	}

?>
