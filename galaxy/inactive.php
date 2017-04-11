<?
	
	include "functions.inc.php";
	
	mysql_query("truncate table `index`");
	mysql_query("truncate table `systems_of_interest`");	
	
	
	$sql = "SELECT * FROM `leaderboard` WHERE kind = 'building' and timestamp > '".date("Y-m-d",time()-24*60*60*2)."'";
	
	$res = mysql_query($sql);
	//$fleet = array();
	$players = array();
	while($mr = mysql_fetch_assoc($res)) {
		//$mr['points'] = preg_replace("/ \(\w+\)/","",$mr['points']);
		//$fleet[] = $mr['points'];
		$players[] = $mr['player'];
	}
	
	$sql = "replace into `index` (select galaxy, system, player from map where player in (";
	for($i=0;$i<count($players);$i++) {
		$sql .= "'".mysql_escape_string($players[$i])."',";
		if($i && $i%100==0) {
			mysql_query(rtrim($sql,",")."))");
			$sql = "replace into `index` (select galaxy, system, player from map where player in (";
		}
	}

	mysql_query(rtrim($sql,",")."))");
	
	$done = false;
	while(!$done) {
		//find most popular
		$sql = "SELECT galaxy,system, count(*) as toto FROM `index` group by galaxy, system ORDER BY count(*) desc";
		$res = mysql_query($sql);
		$mr = mysql_fetch_assoc($res);
		if(mysql_num_rows($res) < 2) {
			$done = true;
		}
		
		//lp("$mr[galaxy] $mr[system] has $mr[toto] residents");
		
		//find residents
		$sql2 = "select * from `index` where galaxy = '$mr[galaxy]' and system = '$mr[system]'";
		$res2 = mysql_query($sql2);
	
		//add system to interest
		mysql_query("insert into systems_of_interest values ('$mr[galaxy]','$mr[system]')");

		//remove extra systems
		$sql3 = "delete from `index` where player in (";
		while($mr2 = mysql_fetch_assoc($res2)) {
			$sql3 .= "'".mysql_escape_string($mr2['player'])."',";
		}
		mysql_query(rtrim($sql3,",").")");
		//lp("deleted ".mysql_affected_rows()." extra systems");
	}
	
	mysql_query("delete from inactive where timestamp < '".date("Y-m-d",time()-60*60*24*7)."'");

?>
