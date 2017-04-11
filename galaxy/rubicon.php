<?
	//return;
	
	$time = time();
	
	include "db.php";	
	
	/*$sql = "select * from leaderboard where kind = 'fleet' and timestamp > '".date("Y-m-d",time()-24*60*60)."' group by player order by timestamp asc";
	$res = mysql_query($sql);
	$fleet = array();
	while($mr = mysql_fetch_assoc($res)) {
		$mr['points'] = preg_replace("/ \(\w+\)/","",$mr['points']);
		$fleet[$mr['player']] = $mr['points'];
	}*/

	$types = array("building","destroyed_ships");

	


	//for($time = time();$time>time()-24*60*60;$time = $time-60*60) {
		$msg = "<strong>leaderboard watch</strong><br><table cellpadding=3><tr><th>player</th><th>kind</th><th>delta</th></tr>\n";
		$found = false;		
		foreach($types as $type) {
			if($type == "building") {
					$sql = "select * from juicy where timestamp > '".date("Y-m-d H:i:s",$time-60*30)."' and timestamp < '".date("Y-m-d H:i:s",$time)."' order by player, timestamp";
			} else {
				$sql = "select * from haps where timestamp > '".date("Y-m-d H:i:s",$time-60*30)."' and timestamp < '".date("Y-m-d H:i:s",$time)."' and `kind` = '$type'  order by player, timestamp";
			}
			
			$res = mysql_query($sql);
			
			while($mr = mysql_fetch_assoc($res)) {
				$player = trim($mr['player']);
				
				if($player != $current_player) {
					$current_player = $player;
					$history = array();
				} else {
					if($history['points'] && abs($history['points'] - $mr['points']) > 10000) {
						$delta = $mr['points'] - $history['points'];
						$msg .= "<tr><td>$player $mr[status]</td><td>$type</td><td>$delta</td></tr>\n";
						$found = true;
					}
				}
				$history['status'] = $mr['status'];
				$history['points'] = $mr['points'];
				$history['timestamp'] = $mr['timestamp'];		
			}
			$msg .= "</table>";
		}
		if($found) {
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From:  babam@fu4ever.com' . "\r\n";	
			mail("babam@fu4ever.com","leaderboard watch",$msg,$headers);
		}
	//}

	mysql_query("delete from haps where timestamp < '".date("Y-m-d H:i:s",$time-60*60*24)."'");

?>
