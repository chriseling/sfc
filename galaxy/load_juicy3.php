<?
	$ip_address = "72.52.91.67";
	$player = "yegg";
	
	$worlds = array(1000000058754,1000000104604,1000000098914,1000000093198);

	include "functions.inc.php";

	lp("starting");

	human();
			
	$sql = "select distinct galaxy as g, system as s from `index`";
	$res = mysql_query($sql);
	$fleets = array();
	while($mr = mysql_fetch_assoc($res)) {
		$fleets[] = array($mr['g'],$mr['s']);
	}
	
	$df_msg = "";
	
	foreach($fleets as $s) {
		$galaxy = $s[0];
		$world = $worlds[array_rand($worlds)];
		$system = $s[1];
		if($system > 0 && $system < 500) {
			lp("reading $galaxy $system");
			$hour = date("H",time());
			$url = "http://playstarfleet.com/galaxy/show?current_planet=$world&galaxy=$galaxy&solar_system=$system";
			$content = sfc_get($url);
			galaxy(preg_replace("/\n/","",$content));
		} else {
			lp("skipping $galaxy $system");
		}
		human();
		set_time_limit(0);
		include "juicy.php";
	}
	
?>
