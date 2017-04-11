<?

	include "db.php";	
	
	$sql = "select * from leaderboard where kind = 'fleet' and timestamp > '".date("Y-m-d",time()-24*60*60)."' order by player, timestamp asc";
	echo $sql;
	$res = mysql_query($sql);
	$fleet = array();
	$rank = array();
	while($mr = mysql_fetch_assoc($res)) {
		$mr['points'] = preg_replace("/ \(\w+\)/","",$mr['points']);
		$fleet[$mr['player']] = $mr['points'];
		$rank[$mr['player']] = $mr['rank'];		
	}
	//print_r($fleet);
	

	$sql = "select * from juicy where timestamp > '".date("Y-m-d H:i:s",time()-60*60*24*7)."' and hour(timestamp) in (0,6,12,18) order by player, timestamp";
	echo $sql;
	
	$res = mysql_query($sql);
	$current = "";
	$jucies = array();
	$ranks = array();
	$actives = array();
	$counts = array();
	$inactives = "";
	$cs = array();
	//$home_gals = array(2,3,5,6,7,8,9,10,11,12,13,14,34,20,21,19);
	$found = false;
	
	while($mr = mysql_fetch_assoc($res)) {
		$player = trim($mr['player']);
		$overall[$player] = $mr['points'];
		$status[$player] = $mr['status'];

		if($player != $current_player) {
			$current_player = $player;
			$lowest = $mr['points'];
		} else {
			if(!$mr['status'] && $mr['points'] == $history['points']) {
				$juicies[$player] += strtotime($mr['timestamp']) - strtotime($history['timestamp']);
			}
			$counts[$player]++;
		}
		$history['status'] = $mr['status'];
		$history['points'] = $mr['points'];
		$history['timestamp'] = $mr['timestamp'];		
	}
	
	
	$msg .= "<strong>easy targets</strong><br><table  cellpadding=3><tr><th>player</th><th>rank</th><th>ships</th><th>idled</th><th>coords</th></tr>\n";
	arsort($juicies);
	
	foreach($juicies as $player => $velocity) {
		if($velocity > 201600 && $fleet[$player] > 0) {
			
			$sql = "select * from map where player = '$player' and timestamp > '".date("Y-m-d",time()-60*60*24*7)."'";
			$res2 = mysql_query($sql);
			$coords = "";

			$cs = array();
			if($res2) {
				while($mr2 = mysql_fetch_assoc($res2)) {
						$c = "[$mr2[galaxy]:$mr2[system]:$mr2[slot]] ";
						if(!$cs[$c]) {
							$coords .= "[$mr2[galaxy]:$mr2[system]:$mr2[slot]] ";
							$cs[$coords] = true;
						}
				}
			}
			

				$msg .= "<tr><td>$player</td><td>$rank[$player]</td><td>$fleet[$player]</td><td>".number_format($velocity/60/60,1)."hours / week</td><td>$coords</td></tr>\n";
				$found = true;

		}
	}
	$msg .= "</table>";

	if($found) {
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From:  babam@fu4ever.com' . "\r\n";	
		mail("fu@fu4ever.com","easy targets report",$msg,$headers);
	}
	
	mysql_query("delete from juicy_report where timestamp < '".date("Y-m-d H:i:s",time()-60*60*24*14)."'");
?>
