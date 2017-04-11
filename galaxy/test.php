<?
	require_once("functions.inc.php");

	$ip_address = "72.52.91.66";
	$glob = glob("cookies/*.txt");
	foreach($glob as $player) {
		preg_match("/(\w+)\.txt/",$player,$matches);
		$player = $matches[1];
		$url = "http://playstarfleet.com/";
		$content = sfc_get($url);
		lp("testing $player");
		if(preg_match("/Join Now/",$content)) {
			lp("login failed");
		}
		human();
	}
	
