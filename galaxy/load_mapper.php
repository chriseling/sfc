<?
	include "functions.inc.php";

	$hour = date("H",time());
	$minute = date("i",time());
	
	//$pid = $hour%4*2+floor($minute/30);
	$pid = date("w",time());
	$ip_address = "72.52.91.66";
	$worlds = array(1000000101102,1000000416707,1000000456233,1000000435451,1000000531341);
	$player = "cryton";
	
	/*
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
	}*/

	$world = $worlds[array_rand($worlds)];	

	sleep(rand(1,10)*60);

	$galaxy = $pid + 1 + (floor($hour/4)-1)*7;
	
	lp("starting $player $ip_address $pid $hour $minute $galaxy");
	print_r($worlds);

	for($i=0;$i<10;$i++) {
		if($galaxy > 35 || $galaxy < 1) {
			return;
		}
		$system = $i*100%500 + floor($i/5)*50 + 1;
		lp("reading $galaxy $system");

		for($j=0;$j<50;$j++) {
			$s = $system + $j;
			if($s > 0 && $s < 500 && $galaxy > 0 && $galaxy < 500) {
				//lp("reading $galaxy $s");
				

				$url = "http://playstarfleet.com/galaxy/show?current_planet=$world&galaxy=$galaxy&solar_system=$s";			
				$content = sfc_get($url);
				map(preg_replace("/\n/","",$content));
			} else {
				lp("skipping $galaxy $s");
			}
			human();
			
			set_time_limit(0);
		}

	}

	//include "juicy.php";
?>