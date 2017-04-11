<?
	include "functions.inc.php";

	$sql = "select * from leaderboard where kind = 'fleet' and timestamp > '".date("Y-m-d",time()-24*60*60)."' group by player order by timestamp asc";
	$res = mysql_query($sql);
	$fleet = array();
	while($mr = mysql_fetch_assoc($res)) {
		$mr['points'] = preg_replace("/ \(\w+\)/","",$mr['points']);
		$fleet[$mr['player']] = $mr['points'];
	}

	$sql = "select * from galaxy where timestamp > '".date("Y-m-d-",time()-60*60*24*40)."' order by timestamp";

	$res = mysql_query($sql);
	$data = array();
	while($mr = mysql_fetch_assoc($res)) {
		$player = trim($mr['player']);
		if(preg_match("/v/",$mr['status'])) {
			if($mr['timestamp'] < $data[$player]['min'] || !$data[$player]['min']) {
				$data[$player]['min'] = $mr['timestamp'];
				$data[$player]['days'] = number_format((strtotime($data[$player]['max']) - strtotime($data[$player]['min']))/60/60/24,1);
			}
			if($mr['timestamp'] > $data[$player]['max'] || !$data[$player]['max']) {
				$data[$player]['max'] = $mr['timestamp'];
				$data[$player]['days'] = number_format((strtotime($data[$player]['max']) - strtotime($data[$player]['min']))/60/60/24,1);			
			}
			$coord = "[$mr[galaxy]:$mr[system]:$mr[slot]]";
			$data[$player]['coords'][$coord] = true;			
		} else {
			unset($data[$player]);
		}
	}
	
	
	$msg = "<table cellpadding=\"5\"><tr><th>player</th><th>entered/exited</th><th>duration</th><th>coordinates</th><th>ships</th></tr>\n";
	foreach($data as $player => $d) {
		if($d['days'] >= 30 && $d['days'] < 36) {
			$color = "bgcolor=\"#f00\"";
		} else {
			$color = "";
		}
		$msg .= "<tr $color><td>$player</td><td>$d[min]<br>$d[max]</td><td>$d[days] days</td><td>";
		foreach($d['coords'] as $coord => $dud) {
			$msg .= "$coord\n<br>";
		}
		$msg .= "</td><td>$fleet[$player]</td></tr>\n";
	}
	$msg .= "</table>";
	
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From:  babam@fu4ever.com' . "\r\n";	
	mail("fu@fu4ever.com","vacation report",$msg,$headers);

?>