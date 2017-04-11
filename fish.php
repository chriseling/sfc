<?
	if(!in_array($_SESSION['user'],$super_trusted)) {
		return;
	}

	$sql = "select * from leaderboard where kind = 'fleet' and timestamp > '".date("Y-m-d",time()-60*60*24*3)."'";

	$res = mysql_query($sql);
	$leader = array();
	while($mr = mysql_fetch_assoc($res)) {
		$leader[$mr['player']] = $mr['points'];
	}

	if($_GET['g']) {
		$filter_sql = "and map.galaxy = '$_GET[g]'";
	}
	
	$sql = "select distinct galaxy, system, slot, player from map where timestamp > '".date("Y-m-d",time()-60*60*24*26)."' $filter_sql";
	//echo $sql;
	
	$res = mysql_query($sql);
	$fish = array();
	$chips = array();
	while($mr = mysql_fetch_assoc($res)) {
		if($leader[$mr['player']]) {
			if($_GET['g']) {
				$fish[$mr['system']] += $leader[$mr['player']];				
				$chips[$mr['system']]++;
			} else {
				$fish[$mr['galaxy']] += $leader[$mr['player']];
				$chips[$mr['galaxy']]++;
			}
		}
	}
	
	//echo $sql;
	
	arsort($fish);
	?>
    <table>
  <tr>
    <td><strong>Galaxy <? if($_GET['g']) { echo $_GET['g']; } ?></strong></td>
    <td><strong>Ships</strong></td>
    <td><strong>Planets</strong></td>
  </tr><?	
	foreach($fish as $coord => $school) { ?>

  <tr>
    <td><?
    if($_GET['g']) {
			echo $_GET['g'].":".$coord;
		} else { ?>
			<a href="?page=fish&g=<?=$coord;?>"><?=$coord;?></a>
			<?
		}
			?></td>
    <td><?=$school;?></td>
    <td><?=$chips[$coord];?></td>
  </tr>
  <? } ?>
</table>

		