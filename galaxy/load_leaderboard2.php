<?
	/*$ip_address = "72.52.91.72";
	$player = "lordbelgarion";
	
	$worlds = array(1000000449471,1000000134050,1000000204044,1000000799287);
	$world = $worlds[array_rand($worlds)];
	*/
	
	$ip_address = "72.52.91.69";
	$player = "aaronjsdad";
	
	$worlds = array(1000000502265,1000000553701,1000000419577,1000000425725);
	$world = $worlds[array_rand($worlds)];
	
	
	include "functions.inc.php";
	
	
	lp("starting");

	
	human();
	
	function leader($type,$file,$haps = false) {
		
		if($haps) {
			$sql = "replace into haps values ";
		} else {
			$sql = "replace into juicy values ";
		}
		preg_match_all("#(<tr class='entity.+?'>.+?</tr>)#",$file,$matches);
		//print_r($matches);
		if(count($matches[1]) < 99) {
			lp("leaderboard error");
			mail("babam@fu4ever.com","leaderboard error",$file);
			exit;
		}
		
		foreach($matches[1] as $row) {
			$data = array();
			if(preg_match("#<td class='rank'>(.+?)</td>#",$row,$matches)) {
				$data['rank'] = trim($matches[1]);
				$data['rank'] = preg_replace("/,/","",$data['rank']);
			}
			if(preg_match("#<td class='name'>.+?>(.+?)</a>(.+?)</td>#",$row,$matches)) {
				$data['name'] = trim($matches[1]);
				$data['status'] = trim($matches[2]);
			}
			if(preg_match("#<td class='tag'>.+?>(.+?)</a>.+?</td>#",$row,$matches)) {
				$data['tag'] = trim($matches[1]);
			}
			if(preg_match("#<td class='points'>(.+?)</td>#",$row,$matches)) {
				$data['points'] = trim($matches[1]);
				$data['points'] = preg_replace("/,/","",$data['points']);
			}
			if($haps) {
			$sql .= "('$type','$data[rank]','".mysql_real_escape_string($data['name'])."','".mysql_real_escape_string($data['tag'])."','$data[points]',now(),'$data[status]'),";
			} else {
				$sql .= "('".mysql_real_escape_string($data['name'])."','$data[points]',now(),'$data[status]'),";
			}
		}
		mysql_query(rtrim($sql,","));
		echo mysql_error();
		
	}
	
	$types = array("building");
	$type_count = array("building"=>15000);	
	
	foreach($types as $type) {
		for($i=0;$i<$type_count[$type];$i+=100) {
			lp("loading $type $i");
			$url = "http://playstarfleet.com/leaderboard?current_planet=$world&amp;division=$i&amp;entity=user&amp;type=$type";
			$content = sfc_get($url);
			leader($type,preg_replace("/\n/","",$content));
			human();
			set_time_limit(0);
		}	
	}

	include "juicy.php";
	
	$ip_address = "72.52.91.72";
	$player = "lordbelgarion";
	
	$worlds = array(1000000449471,1000000134050,1000000204044,1000000799287);
	$world = $worlds[array_rand($worlds)];

	$types = array("destroyed_ships");
	$type_count = array("destroyed_ships"=>5000);	
	
	foreach($types as $type) {
		for($i=0;$i<$type_count[$type];$i+=100) {
			lp("loading $type $i");
			$url = "http://playstarfleet.com/leaderboard?current_planet=$world&amp;division=$i&amp;entity=user&amp;type=$type";
			$content = sfc_get($url);
			leader($type,preg_replace("/\n/","",$content),true);
			human();
			set_time_limit(0);
		}	
	}

	include "rubicon.php";
	
?>