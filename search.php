<?
	if(!in_array($_SESSION['user'],$trusted)) {
		return;
	}

	if($_POST['button']=="Search") {
		foreach($_POST as $key => $val) {
			$_POST[$key] = mysql_real_escape_string($val);
		}
		if($_POST['player']) {
			$filter_sql .= "and player like '%$_POST[player]%'";
		}
		if($_POST['alliance']) {
			$filter_sql .= "and alliance like '%$_POST[alliance]%'";
		}
		
		if($_POST['planet']) {
			$filter_sql .= "and planet like '%$_POST[planet]%'";
		}
		if($_POST['range']) {
			$left = $_POST['ls']-$_POST['range'];
			$right = $_POST['ls']+$_POST['range'];
			$filter_sql .= "and map.galaxy = '$_POST[lg]' and map.system >= '$left' and map.system <= '$right'";
			
		}	
		$go = true;
		if(!$filter_sql) {
			$msg = "search required";
			$go = false;
		}
		if($_POST['range'] > 50) {
			$msg = "maximum range of 50 systems";
			$go = false;
		}
	}
	
?>
<span style="color: rgb(255, 0, 0); "><?=$msg;?></span>
<form method="post" action="">
  <table>
    <tr>
      <td>Player</td>
      <td><input type="text" value="<?=$_POST['player'];?>" name="player" id="player" /></td>
    </tr>
    <tr>
      <td>Alliance</td>
      <td><input type="text" value="<?=$_POST['alliance'];?>" name="alliance" id="alilance" /></td>
    </tr>
    <tr>
      <td>Planet</td>
      <td><input type="text" value="<?=$_POST['planet'];?>" name="planet" id="planet" /></td>
    </tr>
    <tr>
      <td>Local</td>
      <td><table>
        <tr>
          <td>Location</td>
          <td>within
            <input name="range" type="text" value="<?=$_POST['range'];?>" id="range2" size="3" maxlength="2" />
            systems of
            <input name="lg" type="text" value="<?=$_POST['lg'];?>" id="textfield2" size="3" maxlength="2" />
            :
            <input name="ls" type="text" value="<?=$_POST['ls'];?>" id="textfield2" size="4" maxlength="3" /></td>
        </tr>
        </table></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="button" id="button" value="Search" /></td>
    </tr>
  </table>
  <?
  if($_POST['button']=="Search" && $go) {
		
		
		
		
		?>
  <style type="text/css">

		td {
			text-align:center;
		}
		td img {
			height:15px;
			width:15px;
		}
	</style>
  <table>
    <tr>
      <td><strong>Coords</strong></td>
      <td><strong>Planet</strong></td>
      <td><strong>Player</strong></td>
      <td><strong>Rank</strong></td>
      <td><p><strong>Status</strong></p></td>
      <td><strong>Alliance</strong></td>
      <td><strong>Ships</strong></td>
      <td><strong>Defense</strong></td>
      <td><strong>Research</strong></td>
      <td><strong>Building</strong></td>
    </tr>
    <?
			$sql = "select * from map where 1 $filter_sql limit 5000";
			if($_SESSION['user'] == "babam@fu4ever.com") {
				//echo $sql;
			}
			$res = mysql_query($sql);

			$players = array();

			if(mysql_num_rows($res)) {
				while($mr = mysql_fetch_assoc($res)) {
					$players[$mr['player']] = true;
				}
				mysql_data_seek($res,0);
			}
			
			
			$sql = "select * from leaderboard where timestamp > '".date("Y-m-d H:i:s",time()-60*60*24*7)."' and player in (";
			foreach(array_keys($players) as $p) {
				$sql .= "'".mysql_escape_string($p)."',";
			}
			$sql = rtrim($sql,",").")";
			
			$sql .= "order by player, timestamp";
		

		if($_SESSION['user'] == "babam@fu4ever.com") {
				//echo $sql;
			}
			
			$res2 = mysql_query($sql);
			$leader = array();
			if($res2) {
				while($mr = mysql_fetch_assoc($res2)) {
					$leader[$mr['player']][$mr['kind']] = $mr['points'];
				}
			}

			if(mysql_num_rows($res)) {
				$found = array();
				
				while($mr = mysql_fetch_assoc($res)) {
						$coor = "[$mr[galaxy]:$mr[system]:$mr[slot]]";
						if(!$found[$coor]) {
								$found[$coor] = true;
								$mr['ships'] = $leader[$mr['player']]['fleet'];
								$mr['defense'] = $leader[$mr['player']]['defense'];
								$mr['research'] = $leader[$mr['player']]['research'];
								$mr['building'] = $leader[$mr['player']]['building'];
							 ?>
				<tr>
					<td>[<?=$mr['galaxy'];?>:<?=$mr['system'];?>:<?=$mr['slot'];?>]</td>
					<td><?=$mr['planet'];?></td>
					<td><?=$mr['player'];?></td>
					<td><?=$mr['rank'];?></td>
					<td><?=$mr['status'];?></td>
					<td><?=$mr['alliance'];?></td>
					<td><? if($mr['ships']) {  echo "<img src=\"images/artemis_class.png\" /> "; echo $mr['ships']; } ?></td>
					<td><? if($mr['defense']) {  echo "<img src=\"images/plasma_class.png\" /> "; echo $mr['defense']; } ?></td>
					<td><? if($mr['research']) {  echo "<img src=\"images/espionage_icon.png\" /> "; echo $mr['research']; } ?></td>
					<td><? if($mr['building']) {  echo "<img src=\"images/harvest_icon.png\" /> "; echo $mr['building']; } ?></td>
				</tr>
    <? } } } else { ?>
    <tr><td colspan="8">no results found</td></tr>
    <? } ?>
  </table>
  <? } ?>
</form>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-215470-43");
pageTracker._trackPageview();
} catch(err) {}</script>
