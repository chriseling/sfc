<?
	if(!in_array($_SESSION['user'],$super_trusted)) {
		return;
	}

	$sql = "select * from leaderboard where timestamp > '".date("Y-m-d",time()-60*60*24*3)."' order by timestamp";
	echo $sql;
	exit;
	$res = mysql_query($sql);
	$leader = array();
	while($mr = mysql_fetch_assoc($res2)) {
		$leader[$mr['player']][$mr['kind']] = $mr['points'];
	}
	
	if($_POST['g']) {
		$filter_sql = "and galaxy = '$_POST[g]'";
	}
	echo $sql;
	exit;
	
	$sql = "select * from map, (select max(timestamp) as max, galaxy, system, slot from map where timestamp > '".date("Y-m-d",time()-60*60*24*26)."' group by galaxy,system,slot) as max where map.timestamp = max.max and map.galaxy = max.galaxy and map.system=max.system and map.slot = max.slot and map.timestamp > '".date("Y-m-d",time()-60*60*24*26)."' $filter_sql";
	$res = mysql_query($sql);
	$fish = array();
	while($mr = mysql_fetch_assoc($res)) {
		if($_POST['g']) {
			$fish[$mr['system']] += $leader[$mr['player']]['fleet'];				
		} else {
			$fish[$mr['galaxy']] += $leader[$mr['player']]['fleet'];
		}
	}
	
	//echo $sql;
	
	arsort($fish);
	?>
    <table>
  <tr>
    <td>Galaxy</td>
    <td>Ships</td>
  </tr><?	
	foreach($fish as $coord => $school) { ?>

  <tr>
    <td><?=$coord;?></td>
    <td><?=$school;?></td>
  </tr>
  <? } ?>
</table>

		