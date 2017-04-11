<?
	$ip_address = "72.52.91.69";
	$player = "tillerman";
	$world = 1000000182962;
	
	include "functions.inc.php";
	
	
	lp("starting");

	//sleep(rand(1,10)*60);
	
	//human();
	
	function leader($type,$file) {
		
		$sql = "replace into leaderboard values ";
		preg_match_all("#(<tr class='entity.+?'>.+?</tr>)#",$file,$matches);
		//print_r($matches);
		if(count($matches[1]) != 100) {
			lp("leaderboard error");
			mail("babam@fu4ever.com","leaderboard error",$file);
			exit;
		}
		
		foreach($matches[1] as $row) {
			$row = preg_replace('#<a href="/ranks.+?</a>#',"",$row);
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
			$sql .= "('$type','$data[rank]','".mysql_real_escape_string($data['name'])."','".mysql_real_escape_string($data['tag'])."','$data[points]',now(),'$data[status]'),";
		}
		mysql_query(rtrim($sql,","));
		echo mysql_error();
		
	}


	$types = array("building","fleet","research","defense");
	
	foreach($types as $type) {
		for($i=0;$i<5000;$i+=100) {
			lp("loading $type $i");
			$url = "http://playstarfleet.com/leaderboard?current_planet=$world&amp;division=$i&amp;entity=user&amp;type=$type";
			
			$content = sfc_get($url);
			leader($type,preg_replace("/\n/","",$content));

			human();
			set_time_limit(0);
		}	
	}

	/*mysql_query("delete from galaxy where timestamp < ''".date("Y-m-d H:i:s",time()-60*60*24)."'");
	mysql_query("delete from map where timestamp < ''".date("Y-m-d H:i:s",time()-60*60*24*14)."'");
	*/

	//include "juicy.php";
	
	$sql = "insert into leaderboard_archive (select * from leaderboard where timestamp < '".date("y-m-d",time()-60*60*24*30)."')";
?>