<?
	
	include "functions.inc.php";
	
	$sql = "select * from leaderboard where kind = 'fleet' and timestamp > '".date("Y-m-d",time()-24*60*60*2)."' group by player order by timestamp asc";
	$res = mysql_query($sql);
	$fleet = array();
	while($mr = mysql_fetch_assoc($res)) {
		$mr['points'] = preg_replace("/ \(\w+\)/","",$mr['points']);
		$fleet[$mr['player']] = $mr['points'];
	}
	
	//echo $sql;
	$sql = "select * from inactive order by player, timestamp";
	//echo $sql;
	$res = mysql_query($sql);
	$current = "";
	$jucies = array();
	$ranks = array();
	$actives = array();
	$counts = array();
	$inactives = "";
	$cs = array();
	$found = false;
	//$home_gals = array(2,3,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,27,28,29);
	
	$msg = "<strong>new inactives</strong><br><table cellpadding=3><tr><th>coordinates</th><th>player</th><th>RSP</th><th>ships</th></tr>\n";
	while($mr = mysql_fetch_assoc($res)) {
		$player = trim($mr['player']);
		$rank[$player] = $mr['rank'];
		$status[$player] = $mr['status'];
		
		if($player != $current_player) {
			$current_player = $player;
			$history = array();
		} else {
			$inactive_found = false;		
			if($history['status'] && preg_match("/i/",$mr['status']) && !preg_match("/i/",$history['status']) && strtotime($mr['timestamp']) > time()-60*60*2) {
				$inactive_found = true;
			}
			if($history['status'] && preg_match("/I/",$mr['status']) && preg_match("/v/",$history['status']) && strtotime($mr['timestamp']) > time()-60*60*2) {
				$inactive_found = true;
			}

			
			if($inactive_found) {
				$sql = "select * from map where player = '$mr[player]' and timestamp > '".date("Y-m-d",time()-60*60*24*7)."' group by galaxy, system, slot";
				//echo $sql;
				$res2 = mysql_query($sql);
				$coords = "";
				$care = false;
				if($res2) {
					while($mr2 = mysql_fetch_assoc($res2)) {
						//if(in_array($mr2['galaxy'],$home_gals)) {
							$care = true;
							$coords .= "<a href=\"http://playstarfleet.com/galaxy/show?galaxy=$mr2[galaxy]&solar_system=$mr2[system]\">[$mr2[galaxy]:$mr2[system]:$mr2[slot]]</a> ";
						//}
					}
				}
				if($care) {
					$msg .= "<tr><td>$coords</td><td>$player $mr[status]</td><td>$rank[$player]</td><td>$fleet[$player]</td></tr>\n";
					$found = true;
				}
			}
			
			
		}
		$history['status'] = $mr['status'];
		$history['timestamp'] = $mr['timestamp'];		
	}
	$msg .= "</table>";

	if($found) {
		//mail("babam@fu4ever.com, zacharykeokihong@fu4ever.com","new inactive","$inactives", "From: babam@fu4ever.com \r\n");
		//mail("spot@fu4ever.com","new inactive","$inactives", "From: babam@fu4ever.com \r\n");
		//$msage .= "times dropped, coords, rank\n";
		
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From:  babam@fu4ever.com' . "\r\n";	

		mail("babam@fu4ever.com","new inactive",$msg,$headers);
		//echo $msg;
	}
	

?>
