<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>

<?



if(!$_GET['cg']) {
	$_GET['cg'] = 12;
}

if(!$_GET['cs']) {
	$_GET['cs'] = 341;
}



include "functions.inc.php";

	if($_GET['galaxy'] || $_GET['player']) {
		include "db.php";
	
		if($_GET['galaxy']) {
			$filter .= "and galaxy = '$_GET[galaxy]'";
		}
		
		if($_GET['system']) {
			$filter .= "and system = '$_GET[system]'";
		}
		
		
		if($_GET['slot']) {
			$filter .= "and slot = '$_GET[slot]'";
		}
		
		
		if(!$_GET['player']) {
			$sql = "select * from hunter where timestamp > '".date("Y-m-d H:i:s",time()-60*60*24*14)."' $filter";
		} else {
			$sql = "select * from hunter where timestamp > '".date("Y-m-d H:i:s",time()-60*60*24*30)."' and player like '%".mysql_escape_string($_GET['player'])."%'";
		}
		
		//echo $sql;
		$res = mysql_query($sql);
		$data = array();
		$actives = array();
		$totos = array();
		$days = array();
		//echo "<pre>";
		//echo mysql_num_rows($res);
		//exit;
		while($mr = mysql_fetch_assoc($res)) {

			$time = strtotime($mr['timestamp']);
			//print_r($mr);
			//$data[$day][$hour] .= ".$mr[activity] ";

			$matches = array();
			preg_match("/(\d+)/",$mr['activity'],$matches);
			$ago = $matches[1];
			
	

			for($m=0;$m<4;$m++) {
				$t = $time-$m*15*60;
				$hour = date("H",$t).":".str_pad(floor(date("i",$t)/60*4)*15,2,"0");
				$day = date("m/d",$t);
				$days[$day]++;
				$totos[$hour]++;
				/*
				if($mr['activity'] == "(*)") {
					$actives[$hour]++;	
				}
				if($mr['activity']) {
					$data[$day][$hour] .= "$mr[activity]<br>";
				} else {
					$data[$day][$hour] .= ".";
				}*/
				
				
				if(($ago - $m*15) < 15 && $mr['activity'] && $mr['activity'] != "(*)") {
					$data[$day][$hour] .= "*";
					$actives[$hour]++;
				}
				
				if($mr['activity'] == "(*)" && ($m == 0 || $m == 1)) {
					$data[$day][$hour] .= "*";
					$actives[$hour]++;
				}
				
				if($mr['activity'] && (($ago - $m*15) < 15 && ($ago - $m*15) > 0))  {
					//$data[$day][$hour] .= date("H:i:s",$time - $ago*60);
				}
				if (($ago - $m*15) > 15 || !$mr['activity']) {
					$data[$day][$hour] .= ".";
				}
				//echo "$hour $day -$ago- $m<br>";
			}

				
			
			
		}
		//echo "<pre>";
		//print_r($data);
		//echo "</pre>";
		//print_r($actives);
		//print_r($totos);
		//exit;
		 ?>
  <table border="1" cellpadding="3">
  <tr>
    <th>Hour</th>
    <?

		
			foreach(array_keys($days) as $day) { ?>
				<td><?=$day;?></td>
			<? }
		?>
  </tr>
  <?

	for($i=0;$i<24;$i++) {
		if($i < 10) {
			$i = "0".$i;
		}	
		for($j=0;$j<60;$j+=15) {
			if($j == 0) {
				$j = "00";
			}
			
			$hour = $i.":".$j;
		?>
    <tr bgcolor="#ff<?
			if($totos[$hour]) {
				echo dechex(255-floor(($actives[$hour]/$totos[$hour])*255));
				echo dechex(255-floor(($actives[$hour]/$totos[$hour])*255));		
			} else {
				echo "f";
			}
      ?>">
      <td><?=$hour;?></td>
      <? foreach(array_keys($days) as $day) { ?>
      <td><?=$data[$day][$hour];?></td>
      <? } ?>
    </tr>
  <? } } ?>
</table>

<? return; }


 



	$sql = "select * from leaderboard where kind = 'fleet' and timestamp > '".date("Y-m-d",time()-60*60*24)."' order by timestamp desc";
	//echo $sql;
	$res = mysql_query($sql);
	$fleet = array();
	while($mr = mysql_fetch_assoc($res)) {
		$mr['player'] = preg_replace("/ \(\w+\)/","",$mr['player']);
		$mr['points'] = preg_replace("/,/","",$mr['points']);		
		$mr['player'] = preg_replace("/".preg_quote("\'",'/')."/","'",$mr['player']);		
		
		$fleet[$mr['player']] = $mr['points'];
	}


//$sql = "replace into ip values (now(),'$_SERVER[REMOTE_ADDR]')";
//mysql_query($sql);

if($_GET['action'] == "stop") {
	//mysql_query("delete from stalking where galaxy = '$_GET[g]' and system = '$_GET[s]'");
}

if($_GET['action'] == "start") {
	//mysql_query("insert into stalking values ('$_GET[g]','$_GET[s]')");
}

?>
<table>
  <tr>
    <td><table>
  <tr>
    <td>G</td>
    <td>Player</td>
    <td>Rank</td>
    <td>Ships</td>    
  </tr>
  <?
	if($_GET['all']) {
	$sql = "select * from hunter where rank < 5000 and player <> '' group by galaxy, system, slot, player order by rank";
	} else {
		$sql = "select * from hunter where timestamp > '".date("Y-m-d H:i:s",time()-60*60*24)."' and rank < 5000 and player <> '' group by galaxy, system, slot, player order by rank";
	}
	
	//echo $sql;
	$res = mysql_query($sql);
	$stalking = array();
	
	while($mr = mysql_fetch_assoc($res)) { 
	$player = trim($mr['player']);
	$mr['player'] = $player;
	?>
  <tr>
    <td>[<?=$mr['galaxy'];?>:<?=$mr['system'];?>:<?=$mr['slot'];?>]</td>
    <td><a href="?player=<?=$mr['player'];?>&cg=<?=$_GET['cg'];?>&cs=<?=$_GET['cs'];?>"><?=$mr['player'];?></a></td>
    <td><a href="?galaxy=<?=$mr['galaxy'];?>&system=<?=$mr['system'];?>&slot=<?=$mr['slot'];?>&cg=<?=$_GET['cg'];?>&cs=<?=$_GET['cs'];?>"><?=$mr['rank'];?></a></td>
    <td><?=$fleet[$player];?></td>
    <? /*<td><?
    	if($stalking[$mr['galaxy']][$mr['system']]) {?>
				<a href="?action=stop&g=<?=$mr['galaxy'];?>&s=<?=$mr['system'];?>&cg=<?=$_GET['cg'];?>&cs=<?=$_GET['cs'];?>">x</a>
			<? } else { ?>
				<a href="?action=start&g=<?=$mr['galaxy'];?>&s=<?=$mr['system'];?>&cg=<?=$_GET['cg'];?>&cs=<?=$_GET['cs'];?>">watch</a>				
			<? }
		?></td>*/?>
  </tr>
  <? } ?>
</table></td>
	
	<? /*<td><form id="form1" name="form1" method="get" action="">
	  <table>
	    <tr>
	      <td>Galaxy</td>
	      <td>
        <select name="cg" onchange="submit();">
        <? $sql = "select distinct g from fleets";
				$res = mysql_query($sql);
				while($mr = mysql_fetch_assoc($res)) { ?>
					<option <? if($mr['g'] == $_GET['cg']) { echo "selected"; } ?>><?=$mr['g'];?></option>
				<? }?>
        	</select>
	        </td>
	      </tr>
	    <tr>
	      <td>System</td>
	      <td> <select name="cs" onchange="submit();">
        	<option value="">--</option>
        <? $sql = "select distinct s from fleets where g = '$_GET[cg]'";
				$res = mysql_query($sql);
				while($mr = mysql_fetch_assoc($res)) { ?>
					<option <? if($mr['s'] == $_GET['cs']) { echo "selected"; } ?>><?=$mr['s'];?></option>
				<? }?>
        	</select></td>
	      </tr>
	    <tr>
	      <td>&nbsp;</td>
	      <td><input type="submit" name="button" id="button" value="Submit" /></td>
	      </tr>
	    </table>
	  </form></td>*/?>
  </tr>
  
</table>

<table>
  <tr>
    <td><table>
  <tr>
    <td>G</td>
    <td>Player</td>
    <td>Rank</td>
    <td>Ships</td>
  </tr>
  
  <?
	
	
	$sql = "SELECT * 
FROM galaxy
WHERE status = '' and timestamp > '".date("Y-m-d H:i:s",time()-60*60*24*7)."' and rank < 5000
GROUP BY galaxy, system, slot
ORDER BY rank ASC";
	$res = mysql_query($sql);
	//echo $sql;
	while($mr = mysql_fetch_assoc($res)) {
		$player = trim($mr['player']);
		 ?>
  <tr>
    <td>[<?=$mr['galaxy'];?>:<?=$mr['system'];?>:<?=$mr['slot'];?>]</td>
    <td><a href="?player=<?=$mr['player'];?>&cg=<?=$_GET['cg'];?>&cs=<?=$_GET['cs'];?>"><?=$mr['player'];?></a></td>
    <td><a href="?galaxy=<?=$mr['galaxy'];?>&system=<?=$mr['system'];?>&slot=<?=$mr['slot'];?>&cg=<?=$_GET['cg'];?>&cs=<?=$_GET['cs'];?>"><?=$mr['rank'];?></a></td>
    <td><?=$fleet[$player];?></td>
    <? /*&
    <td><?
    	if($stalking[$mr['galaxy']][$mr['system']]) {?>
				<a href="?action=stop&g=<?=$mr['galaxy'];?>&s=<?=$mr['system'];?>&cg=<?=$_GET['cg'];?>&cs=<?=$_GET['cs'];?>">x</a>
			<? } else { ?>
				<a href="?action=start&g=<?=$mr['galaxy'];?>&s=<?=$mr['system'];?>&cg=<?=$_GET['cg'];?>&cs=<?=$_GET['cs'];?>">watch</a>				
			<? }
		?></td>*/?>
  </tr>
  <? } ?>
</table></td>
	<td><?

	?></td>
	<? /*<td><form id="form1" name="form1" method="get" action="">
	  <table>
	    <tr>
	      <td>Galaxy</td>
	      <td>
        <select name="cg" onchange="submit();">
        <? $sql = "select distinct g from fleets";
				$res = mysql_query($sql);
				while($mr = mysql_fetch_assoc($res)) { ?>
					<option <? if($mr['g'] == $_GET['cg']) { echo "selected"; } ?>><?=$mr['g'];?></option>
				<? }?>
        	</select>
	        </td>
	      </tr>
	    <tr>
	      <td>System</td>
	      <td> <select name="cs" onchange="submit();">
        	<option value="">--</option>
        <? $sql = "select distinct s from fleets where g = '$_GET[cg]'";
				$res = mysql_query($sql);
				while($mr = mysql_fetch_assoc($res)) { ?>
					<option <? if($mr['s'] == $_GET['cs']) { echo "selected"; } ?>><?=$mr['s'];?></option>
				<? }?>
        	</select></td>
	      </tr>
	    <tr>
	      <td>&nbsp;</td>
	      <td><input type="submit" name="button" id="button" value="Submit" /></td>
	      </tr>
	    </table>
	  </form></td>*/?>
  </tr>
  
</table>



</body>
</html>
