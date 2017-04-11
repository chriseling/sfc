<?
	$ip_addresses = array("72.52.91.66","72.52.91.67","72.52.91.68","72.52.91.69","72.52.91.70","72.52.91.71","72.52.91.72");
	
	$players = array("whodat","keoki","cryton","kobayashi","opustwo","aaronjsdad","hollyjo","airjordan","baboom","spot","rossi","matteus","babam","py","jonan","james","daodao","muphdyver","cksol","victoria","helly","oos");
	
	include "functions.inc.php";
	
	
	lp("starting");

	human();
	
	foreach($players as $player) {
		$url = "http://playstarfleet.com/";
		$i = array_rand($ip_addresses);
		$ip_address = $ip_addresses[$i];
		lp("beating $player $ip_address");
		$content = sfc_get($url);
		if(preg_match("/Join Now/",$content)) {
			mail("babam@fu4ever.com","heartbeat failed $player",$content);
		} else {
			mail("babam@fu4ever.com","heartbeated $player",$content);
		}
		human();
		set_time_limit(0);

	}


?>
