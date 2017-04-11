<?
	include "functions.inc.php";

	$hour = date("H",time());
	$minute = date("i",time());
	
	
	$pid = floor($minute/12)+($hour%4+1)*2;
	$pid = $pid%5;
	
	switch($pid) {
		case 0:
			$ip_address = "72.52.91.67";
			$player = "hollyjo";
			$worlds = array(1000000036516,1000000280140,1000000693653);
			break;
		case 1:
			$ip_address = "72.52.91.68";
			$player = "tillerman";
			$worlds = array(1000000058768,1000000175920,1000000085918,1000000090466);
			break;
		case 2:
			$ip_address = "72.52.91.69";
			$player = "lordbelgarion";
			$worlds = array(1000000449471,1000000134050,1000000204044,1000000799287);
			break;
		case 3:
			$ip_address = "72.52.91.70";
			$player = "yegg";
			$worlds = array(1000000058754,1000000104604,1000000098914,1000000093198);
			break;
		case 4:
			$ip_address = "72.52.91.71";
			$player = "opustwo";
			$worlds = array(1000000595457,1000000508477,1000000581759,1000000492569);
			break;
		case 5:
			$ip_address = "72.52.91.72";
			$player = "aaronjsdad";
			$worlds = array(1000000502265,1000000553701,1000000419577,1000000425725);
			break;
		case 6:
			$ip_address = "72.52.91.73";
			$player = "kobayashi";
			$worlds = array(1000000193312,1000000184546,1000000476811,1000000188382);
			break;
	}

	$world = $worlds[array_rand($worlds)];	

	sleep(rand(1,10)*60);

	$start = $pid * 500;

	$sql = "select * from systems_of_interest limit $start,500";

	lp("starting $player $ip_address $pid $hour $minute $galaxy");

	$res = mysql_query($sql);
	while($mr = mysql_fetch_assoc($res)) {
		$galaxy = $mr['galaxy'];
		$s = $mr['system'];
		if($galaxy > 35 || $galaxy < 1) {
			return;
		}
		lp("reading $galaxy $s");
		if($s > 0 && $s < 500 && $galaxy > 0 && $galaxy < 500) {
			$url = "http://playstarfleet.com/galaxy/show?current_planet=$world&galaxy=$galaxy&solar_system=$s";			
			$content = sfc_get($url);
			inactive(preg_replace("/\n/","",$content));

		} else {
			lp("skipping $galaxy $s");
		}
		human();

		set_time_limit(0);
	}

	//include "juicy.php";
?>