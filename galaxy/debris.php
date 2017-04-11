<?
	$ip_address = "72.52.91.67";
	$player = "yegg";
	
	include "functions.inc.php";
	
	
	lp("starting");


	human();
	

	for($i=0;$i<1000;$i++) {
		$galaxy = 6;
		$system = 230;
		lp("reading $galaxy $system");
		
		$url = "http://playstarfleet.com/galaxy/show?current_planet=1000000098914&galaxy=$galaxy&solar_system=$system";
		$content = sfc_get($url);
		debris(preg_replace("/\n/","",$content));
		human();
		set_time_limit(0);
	}

?>
