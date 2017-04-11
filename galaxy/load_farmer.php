<?
	$ip_address = "72.52.91.71";
	$player = "jamespkork";
	
	//include "functions.inc.php";
	
	$file	= implode(file("practice9.html",FILE_IGNORE_NEW_LINES));
	preg_match("#(<div id='user_planets'>.+?)<div id='user_stats' class='user_stats'>#",$file,$matches);
	$ps = $matches[1];
	preg_match_all("#planet_name'><a href=\"/\?activate_planet=(\d+)#",$ps,$matches);
	$planets = $matches[1];
	$homeworld = array_shift($planets);
	preg_match("#<div class='planet_coordinates'>.+?(\d+:\d+:\d+)</a>#",$ps,$matches);
	list($galaxy,$system,$slot) = split(":",$matches[1]);
	
	
	foreach($planets as $planet) {
		$url = "http://playstarfleet.com/fleet?current_planet=$planet";
		$file = implode(file("practice10.html",FILE_IGNORE_NEW_LINES));
		preg_match_all("#<input.+?name=\"(.+?)\".+?value=\"(\w+?)\"#",$file,$matches);
		$inputs = array();
		$form = array();
		foreach($matches[1] as $i => $key) {
			$inputs[$key] = $matches[2][$i];
		}
		
		if(preg_match("#Hercules Class Cargo\s+</span>\s+<span class='quantity'>\s+x<span id='ship_quantity_$matches[1]_max'>(\d+)</span>#",$file,$matches)) {
			$hercs = $matches[1];
			$cargo = $hercs * 25000;
			if($cargo > 
		}
		
		$form['galaxy'] = $galaxy;
		$form['max_crystal'] =	$inputs['max_crystal'];
		$form['max_hydrogen'] =	$inputs['max_hydrogen'];
		$form['max_ore'] =	$inputs['max_ore'];
		$form['mission_option'] =	'Transport';
		$form['planet'] =	$slot;
		$form['planet_type'] =	'planet';
		$form['send_crystal'] =	115153;
		$form['send_hydrogen'] =	58103;
		$form['send_ore'] =	233924;
		foreach($inputs as $key => $val) {
			if(preg_match("/^ship_quantities\[(\d+)\]/",$key,$matches)) {
				if(preg_match("#Hercules Class Cargo\s+</span>\s+<span class='quantity'>\s+x<span id='ship_quantity_$matches[1]_max'>(\d+)</span>#",$file,$matches)) {
					$form[$key] = $matches[1];
				} else {
					$form[$key] = 0;
				}
			}
		}
		$form['solar_system'] =	$system;
		$form['speed'] =	10;
		$form['universe_speed'] =	3600;
		$form['worker_ids'] = "";
		
		print_r($form);
		/*
		 [galaxy] => 7
    [solar_system] => 99
    [planet] => 6
    [receive_planet] => 1000000650157
    [ship_quantities[1168728674]] => 0
    [ship_quantities[960214949]] => 0
    [max_ore] => 233924
    [send_ore] => 0
    [max_crystal] => 115153
    [send_crystal] => 0
    [max_hydrogen] => 59009
    [send_hydrogen] => 0
    [worker_ids] => Deploy
    [mission_option] => Warp
    [commit] => 3600

		
			*/
		exit;
	}
	
	exit;
		
	lp("starting");

	sleep(rand(1,10)*60);
	
	human();
	

?>