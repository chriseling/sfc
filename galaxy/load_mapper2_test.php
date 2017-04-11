<?
	$ip_address = "72.52.91.69";
	$player = "tillerman";

	$worlds = array(1000000058768,1000000175920,1000000085918,1000000090466);
	$world = $worlds[array_rand($worlds)];

	include "functions.inc.php";
	
	
	lp("starting");
	
	//sleep(rand(1,10)*60);


	//$day = date("d",time());
	$day = ($day-1)%7;
	//$day = 3;
	if($day > 6) {
		return;
	}
	
	$day = 6 - $day;
	
	//7 12 17 22
	//$hour = date("H",time());
	//$hour = 7;
	
	$hour = (($hour - 3) / 4) - 1;
	if($hour < 0 || $hour > 4) {
		return;
	}

	//lp("hour $hour");

	$gseed = $day + $hour * 7;

	for($i=0;$i<10;$i++) {
		
		$gsn = $gseed + $i*35;
		//lp("gsn $gsn");
		if($gsn > 349) {
			return;
		}
		$galaxy = ceil($gsn / 10);
		$system = ($gsn % 10) * 50 + 1;
		//lp("reading $galaxy $system");
		for($j=0;$j<50;$j++) {
			$s = $system + $j;
			if($s > 0 && $s < 500 && $galaxy > 0 && $galaxy < 500) {
				lp("reading $galaxy $s");
				
				//$hour = date("H",time());
				$url = "http://playstarfleet.com/galaxy/show?current_planet=$world&galaxy=$galaxy&solar_system=$s";				
				//$content = sfc_get($url);
				//map(preg_replace("/\n/","",$content));
			} else {
				lp("skipping $galaxy $s");
			}
			human();
			set_time_limit(0);
		}	

	}

	//include "juicy.php";
?>
