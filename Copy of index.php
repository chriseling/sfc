<?
	mysql_connect("localhost","lanseng","mickey2");
	mysql_select_db("lanseng_chi");
	
	$units = array("missile"=>true,"laser"=>true,"pulse"=>true,"particle"=>true,"decoy"=>true,"gauss"=>true,"large"=>true,"plasma"=>true,"hermes"=>true,"helios"=>true,"artemis"=>true,"atlas"=>true,"apollo"=>true,"charon"=>true,"herc"=>true,"dionysus"=>true,"poseidon"=>true,"gaia"=>true,"athena"=>true,"ares"=>true,"hades"=>true,"prometheus"=>true);
	
	$ships = array("hermes"=>true,"helios"=>true,"artemis"=>true,"atlas"=>true,"apollo"=>true,"charon"=>true,"herc"=>true,"dionysus"=>true,"poseidon"=>true,"gaia"=>true,"athena"=>true,"ares"=>true,"hades"=>true,"prometheus"=>true,"zeus"=>true);
	$defenses = array("missile"=>true,"laser"=>true,"pulse"=>true,"particle"=>true,"decoy"=>true,"gauss"=>true,"large"=>true,"plasma"=>true);

	
	$hull = array("missile"=>200,"laser"=>200,"pulse"=>800,"particle"=>800,"decoy"=>2000,"gauss"=>3500,"large"=>10000,"plasma"=>10000,"hermes"=>100,"helios"=>200,"artemis"=>400,"atlas"=>400,"apollo"=>850,"charon"=>800,"herc"=>1200,"dionysus"=>1600,"poseidon"=>2700,"gaia"=>3000,"athena"=>6000,"ares"=>7500,"hades"=>7000,"prometheus"=>11000);
	
	$shield = array("missile"=>20,"laser"=>25,"pulse"=>100,"particle"=>500,"decoy"=>2000,"gauss"=>200,"large"=>10000,"plasma"=>300,"hermes"=>0,"helios"=>1,"artemis"=>10,"atlas"=>10,"apollo"=>25,"charon"=>25,"herc"=>25,"dionysus"=>10,"poseidon"=>50,"gaia"=>100,"athena"=>200,"ares"=>500,"hades"=>400,"prometheus"=>500);
	
	$attack = array("missile"=>80,"laser"=>100,"pulse"=>250,"particle"=>150,"decoy"=>0,"gauss"=>1100,"large"=>0,"plasma"=>3000,"hermes"=>0,"helios"=>1,"artemis"=>50,"atlas"=>5,"apollo"=>150,"charon"=>1,"herc"=>5,"dionysus"=>1,"poseidon"=>400,"gaia"=>50,"athena"=>1000,"ares"=>1000,"hades"=>700,"prometheus"=>2000);
	
	
	$rf = array(
							
			"atlas"=>array("hermes"=>5,"helios"=>5,"charon"=>5),
			"herc"=>array("hermes"=>5,"helios"=>5,"charon"=>5),
			"artemis"=>array("hermes"=>5,"helios"=>5,"charon"=>5),
			"apollo"=>array("hermes"=>5,"helios"=>5,"charon"=>5,"atlas"=>3),
			"poseidon"=>array("hermes"=>5,"helios"=>5,"charon"=>5,"artemis"=>6,"missile"=>10),
			"athena"=>array("hermes"=>5,"helios"=>5,"charon"=>5),
			"hades"=>array("hermes"=>5,"helios"=>5,"atlas"=>3,"herc"=>3,"apollo"=>4,"poseidon"=>7,"athena"=>7),
			"prometheus"=>array("hermes"=>5,"helios"=>5,"charon"=>5,"laser"=>10,"hades"=>2),
			"ares"=>array("hermes"=>5,"helios"=>5,"charon"=>5,"missile"=>10,"laser"=>20,"pulse"=>10,"particle"=>10),
			"dionysus"=>array("hermes"=>5,"helios"=>5,"charon"=>5),
			"atlas"=>array("hermes"=>5,"helios"=>5,"charon"=>5));
	
	$opposite['us'] = "them";
	$opposite['them'] = "us";
	
	$ore = array("missile"=>2000,"laser"=>1500,"pulse"=>6000,"particle"=>2000,"decoy"=>10000,"gauss"=>20000,"large"=>50000,"plasma"=>50000,"hermes"=>0,"helios"=>0,"artemis"=>3000,"atlas"=>2000,"apollo"=>6000,"charon"=>4000,"herc"=>6000,"dionysus"=>10000,"poseidon"=>20000,"gaia"=>10000,"athena"=>45000,"ares"=>50000,"hades"=>30000,"prometheus"=>60000);
	
	$crystal = array("missile"=>0,"laser"=>500,"pulse"=>2000,"particle"=>6000,"decoy"=>10000,"gauss"=>15000,"large"=>50000,"plasma"=>50000,"hermes"=>1000,"helios"=>2000,"artemis"=>1000,"atlas"=>2000,"apollo"=>2500,"charon"=>4000,"herc"=>6000,"dionysus"=>6000,"poseidon"=>7000,"gaia"=>20000,"athena"=>15000,"ares"=>25000,"hades"=>40000,"prometheus"=>50000);
	
	$hydrogen = array("missile"=>0,"laser"=>0,"pulse"=>0,"particle"=>0,"decoy"=>0,"gauss"=>2000,"large"=>0,"plasma"=>30000,"hermes"=>0,"helios"=>500,"artemis"=>0,"atlas"=>0,"apollo"=>0,"charon"=>1000,"herc"=>0,"dionysus"=>2000,"poseidon"=>2000,"gaia"=>10000,"athena"=>0,"ares"=>15000,"hades"=>15000,"prometheus"=>15000);
	

	//print_r($_POST);
	
	if($_POST['button4'] == "Load Espionage Report") {
		$filled = array();
		$filled['textarea'] = $_POST['textarea'];
		$lines = split("\n",$_POST['textarea']);
		$report = array();
		$mapping = array("Missile Battery"=>"missile",
										 "Laser Cannon"=>"laser",
										 "Pulse Cannon"=>"pulse",
										 "Particle Cannon"=>"particle",
										 "Decoy"=>"decoy",
										 "Gauss Cannon"=>"gauss",
										 "Large Decoy"=>"large",
										 "Plasma Cannon"=>"plasma",
										 "Hermes Class Probe"=>"hermes",
										 "Helios Class Solar Satellite"=>"helios",
										 "Artemis Class Fighter"=>"artemis",
										 "Atlas Class Cargo"=>"atlas",
										 "Apollo Class Fighter"=>"apollo",
										 "Charon Class Transport"=>"charon",
										 "Hercules Class Cargo"=>"herc",
										 "Dionysus Class Recycler"=>"dionysus",
										 "Poseidon Class Cruiser"=>"poseidon",
										 "Gaia Class Colony"=>"gaia",
										 "Athena Class Battleship"=>"athena",
										 "Ares Class Bomber"=>"ares",
										 "Hades Class Battleship"=>"hades",
										 "Prometheus Class Destroyer"=>"prometheus",
										 "Armor Tech"=>"h",
										 "Weapons Tech"=>"a",
										 "Shield Tech"=>"s",
										 "ore"=>"ore",
										 "crystal"=>"crystal",
										 "hydrogen"=>"hydrogen"
										 );
										 
		foreach($lines as $line) {
			if(preg_match("/(.+) has\:/",$line,$matches)) {
				$filled['them_title'] = preg_replace("/,/","",$matches[1]);
			}
			if(preg_match("/^\* (.+)\: (.+)/",$line,$matches)) {
				$filled["them_".$mapping[$matches[1]]] = preg_replace("/,/","",$matches[2]);
			}
			
		}
		$_POST = $filled;
		$_POST['button'] = "Save Fleet";


	}
	
	
	if($_POST['button'] == "Save Fleet") {
		$save = array();
		foreach($_POST as $key => $val) {
			if(preg_match("/^them/",$key)) {
				$save[$key] = $val;
			}
		}
		if(preg_match("/(\d+)\:(\d+)\:(\d+)/",$_POST['them_title'],$matches)) {
			$galaxy = $matches[1];
			$system = $matches[2];
			$planet = $matches[3];
		}
		
		$sql = "replace into saved values ('',now(),'$galaxy','$system','$planet','$_POST[textarea]','$_POST[them_title]','".serialize($save)."','them')";
		//echo $sql;
		mysql_query($sql);
		$_POST['saved_them'] = mysql_insert_id();
	}
	
	
	if($_POST['button2'] == "Save Fleet") {
		$save = array();
		foreach($_POST as $key => $val) {
			if(preg_match("/^us/",$key)) {
				$save[$key] = $val;
			}
		}
		$sql = "replace into saved values ('',now(),'','','','','$_POST[us_title]','".serialize($save)."','us')";
		mysql_query($sql);
		$_POST['saved_us'] = mysql_insert_id();
	}
	
	$selected = array();
	$filled = array();
	
	if($_POST['saved_them']) {
		$id = $_POST['saved_them'];
		
		$sql = "select * from saved where id = '$id'";

		$res = mysql_query($sql);
		$mr = mysql_fetch_assoc($res);
		$report = $mr['report'];		
		$fleet = unserialize($mr['data']);
		foreach($fleet as $key=>$val) {
			$selected[$key][$val] = "selected";
			$filled[$key] = $val;
		}
	}



	if($_POST['saved_us']) {
		$id = $_POST['saved_us'];
		$sql = "select * from saved where id = '$id'";

		$res = mysql_query($sql);
		$mr = mysql_fetch_assoc($res);
		$fleet = unserialize($mr['data']);
		foreach($fleet as $key=>$val) {
			$selected[$key][$val] = "selected";
			$filled[$key] = $val;
		}
	}
	
	
	
	
	function lp($text) {
		echo "$text<br>\n";
		flush();
	}
	
	function selected($key,$val) {
		global $filled;
		if($filled[$key] == $val || $filled[$key]/10 == $val) {
			return "selected=\"selected\"";
		}
	}
	
	function result($team,$unit) {
		global $ave, $n, $filled;
		if(count($ave)) {
			$r = number_format($ave[$team][$unit]/$n,2);
			$index = $team."_$unit";
			if($r == 0 && $team == "them" && $filled[$index]) {
				$color = "#00ff00";
			} else if($r == $filled[$index] && $team == "us") {
				$color = "#00ff00";
			} else if($r == 0 && $team == "us" && $filled[$index]) {
				$color = "#ff0000";
			} else if($r == $filled[$index] && $team == "them" && $r > 0) {
				$color = "#ff0000";
			} else if($r > 0 && $team == "them") {
				$color = "#ffff00";
			} else if($r < $filled[$index] && $team == "us") {
				$color = "#ffff00";
			}
			if($color) {
				return "<span style=\"background-color:$color\">$r</span>";
			}
		} else {
			return "";
		}
	}
	
	function cost($team,$type,$resource) {
		global $costs, $n;
		if(count($costs)) {
			if($type == "debris") {
				return number_format($costs[$team][$type][$resource]/$n/100*30,0);
			} else {
				return number_format($costs[$team][$type][$resource]/$n,0);
			}
		} else {
			return "";
		}
	}
	
	function up($unit,$id,$team) {
		echo("$unit[name]($team:$id) $unit[attack]:$unit[shield]:$unit[hull]");
	}
	
	if($_POST['button3'] == "Simulate") {
		//$debug = 1;
		//lp("<strong>starting simulation</strong>");
		$wins = 0;
		$ave = array();
		$costs = array();
		if(!$_POST['them_a']) {
			$_POST['them_a'] = $_POST['us_a'];
		}
		if(!$_POST['them_s']) {
			$_POST['them_s'] = $_POST['us_s'];
		}
		if(!$_POST['them_h']) {
			$_POST['them_h'] = $_POST['us_h'];
		}

		if($debug) {
			$sims = 1;
		} else {
			$sims = 10;
		}
		for($n=0;$n<$sims;$n++) {
			//lp($n." loading battle field ".date("H:i:s",time())." ".microtime());
			set_time_limit(0);
			$battle = array();
			foreach($_POST as $key => $val) {
				if(preg_match("/(\w+)\_(\w+)/",$key,$matches)) {
					$team = $matches[1];
					$unit = $matches[2];
					//load battle field
	
					if($units[$unit]) {
						for($i=0;$i<$val;$i++) {
							$ui = $unit;
							/*
							if($attack[$ui] === "" || $shield[$ui] === "" || $hull[$ui] === "") {
								lp($unit);
								lp("$attack[$ui] $shield[$ui] $hull[$ui]");
							}*/
							$a = $attack[$ui]*(1+$_POST["$team"."_a"]/10);
							$s = $shield[$ui]*(1+$_POST["$team"."_s"]/10);
							$h = $hull[$ui]*(1+$_POST["$team"."_h"]/10);
							$battle[0][$team][] = array("name"=>$unit,"attack"=>$a,"shield"=>$s,"hull"=>$h);
						}
					}
				}
			}
			
			//lp($n." loaded battle field ".date("H:i:s",time())." ".microtime());

			
			//start 6 rounds of battle
			if($debug) {
				$stats = array();
			}
			$tally = array();
			for($i=0;$i<6;$i++) {
				//lp($n." battle round $i ".date("H:i:s",time())." ".microtime());
				
				$display_round = $i;
				if($debug) {
					lp("<strong>round $display_round</strong>");
				}
				
				include "round_summary.inc.php";
				
				//all shoot this round
				if($debug) {
					lp("<strong>dealing damage</strong>");
				}
				if(count($battle[$i])) {
					foreach($battle[$i] as $team => $us) {
						
						foreach($us as $uid => $unit) {
							//apply damage
							
							$rapid_fire = true;
							while($rapid_fire) {
								$target = rand(0,count($battle[$i][$opposite[$team]])-1);
								$enemy = &$battle[$i][$opposite[$team]][$target];
								$damage = $unit['attack'];
								if($debug) {
									$stats[$i][$team]['shots']++;
									$stats[$i][$team]['danage']+=$damage;
									up($unit,$uid,$team);
								}
								
								if($enemy['shield']>=$damage) {
									if($debug) {
										echo " fires $damage on ";
										up($enemy,$target,$opposite[$team]);
									}
									$enemy['shield'] -= $damage;
									
									if($debug) {
										$stats[$i][$opposite[$team]]['absorb']+=$damage;
										echo " suffers $damage shield damage";
									}
								} else {
									$hull_damage = $damage - $enemy['shield'];
									
									//print_r($enemy);
									
									//print_r($enemy);
									
									if($debug) {
										$stats[$i][$opposite[$team]]['absorb']+=$enemy['shield'];
										echo " fires $damage on ";
										up($enemy,$target,$opposite[$team]);
									}
									
									$enemy['hull'] = $enemy['hull'] - $hull_damage;
									
									if($debug) {
										echo " suffers $enemy[shield] shield and $hull_damage hull damage $enemy[hull]";
									}
																		
									$enemy['shield'] = 0;
								}
								if($debug) {
									echo "-> ";
									up($enemy,$target,$opposite[$team]);
								}
								
								$r = $rf[$unit['name']][$enemy['name']];
								//print_r($rf);
								
								
								if($r) {
									$rf_chance = rand(0,$r-1);
									if($rf_chance < $r - 1) {
										if($debug) {
											lp(" rapid fire suceeded");
										}
									} else {
										if($debug) {
											lp(" rapid fire failed");
										}
										$rapid_fire = false;
									}
								} else {
									if($debug) {
										lp(" no rapid fire");
									}
									$rapid_fire = false;
								}
		
							}
						}
					}	
				}
				
				if($debug) {
					lp("<strong>destroying ships</strong>");
				}
				//lp($n." destroying ships ".date("H:i:s",time())." ".microtime());
				//all shots fired, blow up ships and populate next round in one shot
				if(count($battle[$i])) {
					foreach($battle[$i] as $team => $us) {
						foreach($us as $uid => $unit) {
							$ui = $unit['name'];
							if($unit['hull'] > .7*$hull[$ui]*(1+$_POST["$team"."_h"]/10)) {
								$unit['shield'] = $shield[$ui]*(1+$_POST["$team"."_s"]/10);
								$battle[$i+1][$team][] = $unit;
								if($debug) {
									up($unit,$uid,$team);
									lp(" survived");
								}
							} else if ($unit['hull'] > 0) {
								$grim_reaper = rand(1,100);
								$hull_remaining = ceil($unit['hull']/$hull[$ui]/(1+$_POST["$team"."_h"]/10)*100);
								if($grim_reaper <= $hull_remaining) {
									if($debug) {
										up($unit,$uid,$team);
										lp(" survived $grim_reaper/$hull_remaining");
									}
									$unit['shield'] = $shield[$ui]*(1+$_POST["$team"."_s"]/10);
									if(!$unit['name']) {
										print_r($unit);
										exit;
									}
									$battle[$i+1][$team][] = $unit;
	
									if($debug) {
										up($unit,$uid,$team);
										lp(" restored");
									}
								} else {
									if($debug) {
										up($unit,$uid,$team);
										lp(" destroyed $grim_reaper/$hull_remaining");
										$stats[$i][$team]['destroyed'][] = $unit;
									}
									
								}
							} else {
								if($debug) {
									up($unit,$uid,$team);
									lp(" destroyed");
									$stats[$i][$team]['destroyed'][] = $unit;
								}
							}
						}
					}
				}
			}
			
			if($debug) {
				lp("<strong>final state</strong>");
			}
			
			include "round_summary.inc.php";
			$last_round = count($tally)-1;
			
			foreach($tally[$last_round] as $team => $us) {
				foreach($us as $unit => $qty) {
					$ave[$team][$unit] += $qty;
				}
			}
			
			foreach($tally[0] as $team => $us) {
				foreach($us as $unit => $qty) {
					$ui = $unit;
					$lost_qty = $qty - $tally[$last_round][$team][$unit];
					$costs[$team]['cost']['o'] += $qty*$ore[$ui];
					$costs[$team]['cost']['c'] += $qty*$crystal[$ui];
					$costs[$team]['cost']['h'] += $qty*$hydrogen[$ui];
					$costs[$team]['loss']['o'] += $lost_qty*$ore[$ui];
					$costs[$team]['loss']['c'] += $lost_qty*$crystal[$ui];
					$costs[$team]['loss']['h'] += $lost_qty*$hydrogen[$ui];
					if($ships[$unit]) {
						$costs[$team]['debris']['o'] += $lost_qty*$ore[$ui] + $lost_qty*$crystal[$ui];
					}
				}
			}
			
			
		
			if(!count($battle[$i]['them'])) {
				$win++;
			}
		}
		
		
	}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SFC Battle Simulator</title>
</head>
<body style="font-family:Georgia, 'Times New Roman', Times, serif; font-size:11px;">
<?
?>
<form method="post" action="">

	<table border="0" cellpadding="3" cellspacing="2" bgcolor="#eeeeee">
  <tr>
    <td colspan="4" rowspan="2" align="right" valign="bottom" bgcolor="#dddddd">&nbsp;</td>
    <td align="center" bgcolor="#dddddd"><strong>THEM</strong></td>
    <td align="center" bgcolor="#dddddd"><strong>US</strong></td>
    <td colspan="3" rowspan="<?=count($units)+6;?>" align="left" valign="top" bgcolor="#dddddd" ><strong>
      <label>
        <input type="submit" name="button4" id="button4" value="Load Espionage Report" />
        </label><br />
      
      <label>
        <textarea name="textarea" id="textarea" cols="40" rows="57"><?=$report;?></textarea>
        <br />
        </label>
      
    </strong></td>
    </tr>
  <tr>
    <td bgcolor="#dddddd"><select name="saved_them" id="saved_them" onChange="submit()">
        <option value="">-- saved fleet --</option>
    	<?
				$sql = "select * from saved where type = 'them' order by id desc";
				$res = mysql_query($sql);
				
				while($mr = mysql_fetch_assoc($res)) {
					if($_POST['saved_them'] == $mr['id']) {
						$select = "selected=\"selected\"";
					} else {
						$select = "";
					}
					echo "<option value=\"$mr[id]\" $select>$mr[title]</option>";
				}
			?>
    </select></td>
    <td bgcolor="#dddddd">
      
      <select name="saved_us" id="saved_us" onChange="submit()">
        <option value="">-- saved fleet --</option>
        <?
				$select = "";
				$sql = "select * from saved where type = 'us' order by id desc";
				$res = mysql_query($sql);
				while($mr = mysql_fetch_assoc($res)) {
					if($_POST['saved_us'] == $mr['id']) {
						$select = "selected=\"selected\"";
					} else {
						$select = "";
					}

					echo "<option value=\"$mr[id]\" $select>$mr[title]</option>";
				}
			?>
      </select></td>

    </tr>
  <tr>
    <td bgcolor="#dddddd"><strong>Attack</strong></td>
    <td bgcolor="#dddddd"><strong>Shield</strong></td>
    <td bgcolor="#dddddd"><strong>Hull</strong></td>
    <td align="right" bgcolor="#dddddd"><strong>Unit</strong></td>
	  <td bgcolor="#dddddd">w:
      
	      <select name="them_a" id="them_a">
      <? for($i=0;$i<20;$i++) { 

				echo "<option value=\"$i\" ".selected("them_a",$i).">$i"."0%</option>";
			} ?>
      	
	      </select>
	    o: <input name="them_ore" type="text" value="<?=$filled['them_ore'];?>" /><br />
	    s:
	    <select name="them_s" id="them_s">
        <? for($i=0;$i<20;$i++) { 

				echo "<option value=\"$i\" ".selected("them_s",$i).">$i"."0%</option>";
			} ?>
      </select>
	    c: <input name="them_crystal" type="text" value="<?=$filled['them_crystal'];?>"  /><br />
	    a: 
	    <select name="them_h" id="them_h">
        <? for($i=0;$i<20;$i++) { 

				echo "<option value=\"$i\" ".selected("them_h",$i).">$i"."0%</option>";
			} ?>
      </select>
	    h: <input name="them_hydrogen" type="text" value="<?=$filled['them_hydrogen'];?>"  /></td>
	  <td bgcolor="#dddddd">w:
	    <select name="us_a" id="us_a">
	      <? for($i=0;$i<20;$i++) { 

				echo "<option value=\"$i\" ".selected("us_a",$i).">$i"."0%</option>";
			} ?>
	      </select>
	    <br />
	    s: 
	    <select name="us_s" id="us_s">
	      <? for($i=0;$i<20;$i++) {
	
				echo "<option value=\"$i\" ".selected("us_s",$i).">$i"."0%</option>";
			} ?>
	      </select>
	    <br />
	    a: 
	    <select name="us_h" id="us_h">
	      <? for($i=0;$i<20;$i++) { 

				echo "<option value=\"$i\" ".selected("us_h",$i).">$i"."0%</option>";
			} ?>
      </select></td>
	  </tr>
    	<tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td align="right" bgcolor="#dddddd">&nbsp;</td>
	  <td><strong>Planet / Attacking Fleet</strong></td>
	  <td><strong>Fleet</strong></td>
	  </tr>
  <?
	$i=0;
		foreach($units as $unit => $dud) { 
			?>
	

	<tr>
	  <td><?=$attack[$unit];?></td>
	  <td><?=$shield[$unit];?></td>
	  <td><?=$hull[$unit];?></td>
    <td align="right" bgcolor="#dddddd"><strong>
      <?=$unit;?>
    </strong></td>
    <td><input name="them_<?=$unit;?>" type="text" tabindex="<?=$i+1;?>" size="3" value="<?=$filled["them_$unit"];?>"> <input name="them_rn_<?=$unit;?>" type="text" tabindex="<?=$i+1;?>" size="3" value="<?=$filled["them_rn_$unit"];?>"> <?=result("them",$unit);?></td>
    <td><input name="us_<?=$unit;?>" type="text" tabindex="<?=$i+count($units)+1;?>" size="3"  value="<?=$filled["us_$unit"];?>"> <?=result("us",$unit);?></td>

    </tr>
	
	<?	$i++;}
	?>
  <tr>
    <td>&nbsp;</td>
    <td><strong>Win</strong></td>
    <td><strong>
      <?
      if($n) {
				lp(number_format($win / $n * 100,0) . "%");
			}?>
    </strong></td>
    <td align="right" bgcolor="#dddddd"><strong>total</strong></td>
    <td bgcolor="#dddddd"><div align="left">o:<?=cost("them","cost","o");?> c:<?=cost("them","cost","c");?> h:<?=cost("them","cost","h");?> </div></td>
    <td bgcolor="#dddddd"><div align="left">o:<?=cost("us","cost","o");?> c:<?=cost("us","cost","c");?> h:<?=cost("us","cost","h");?> </div></td>

    </tr>
  <tr>
    <td>&nbsp;</td>
    <td><strong>Debris:</strong></td>
    <td><?=cost("them","debris","o");?></td>
    <td align="right" bgcolor="#dddddd">&nbsp;</td>
    <td align="right" bgcolor="#dddddd"><div align="left">o:<?=cost("them","loss","o");?> c:<?=cost("them","loss","c");?> h:<?=cost("them","loss","h");?> </div></td>
    <td align="right" bgcolor="#dddddd"><div align="left">o:<?=cost("us","loss","o");?> c:<?=cost("us","loss","c");?> h:<?=cost("us","loss","h");?> </div></td>

    </tr>
    <?
			if(!$_POST['gt_1']) {
				$_POST['gt_1'] = "00:00:00";
			}
			if(!$_POST['gt_2']) {
				$_POST['gt_2'] = "00:00:00";
			}
			if(!$_POST['remain_1']) {
				$_POST['remain_1'] = "00:00:00";
			}
			if(!$_POST['remain_2']) {
				$_POST['remain_2'] = "00:00:00";
			}
			if(!$_POST['travel_time']) {
				$_POST['travel_time'] = "00:00:00";
			}
			if(preg_match("/(\d+)\:(\d+)\:(\d+)/",$filled["them_title"],$matches)) {
				$galaxy = $matches[1];
				$system = $matches[2];
				$planet = $matches[3];
			}
			
			if(!$_POST['src'] && $galaxy) {
				$_POST['src'] = "[$galaxy:$system:$planet]";
			}
			if(!$_POST['dst']) {
				$_POST['dst'] = "[8:1:1]";
			}
			
		?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td bgcolor="#dddddd">&nbsp;</td>
    <td colspan="2" align="center" bgcolor="#dddddd"><label>
      <input style="width:400px; height:2em; font-size:18px; font-weight:bold;" type="submit" name="button3" id="button3" value="Simulate">
    </label></td>
		<td>Their Attack:</td>
		<td><strong>SRC</strong>
		  <input name="src" type="text" id="src" value="<?=$_POST['src'];?>" size="8" /></td>
		<td><strong>DST
		  <input name="dst" type="text" id="dst" value="<?=$_POST['dst'];?>" size="8" />
		</strong></td>
    </tr>
    
  <tr>
    <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td rowspan="2" align="right" bgcolor="#dddddd"><strong>Save Fleet As</strong></td>
	  <td rowspan="2"><label>
	    <input type="text" name="them_title" id="textfield" value="<?=$filled["them_title"];?>">
	    <br>
	    <input type="submit" name="button" id="button" value="Save Fleet">
    </label></td>
	  <td rowspan="2"><input type="text" name="us_title" id="textfield2" value="<?=$filled["us_title"];?>">
	    <br>
	    <input type="submit" name="button2" id="button2" value="Save Fleet"></td>
		<td>Remaining	  </td>
		<td><input name="remain_1" type="text" id="remain_1" value="<?=$_POST['remain_1'];?>" size="8" /></td>
		<td><input name="remain_2" type="text" id="remain_2" value="<?=$_POST['remain_2'];?>" size="8" /></td>
	  </tr>
    
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Game Time</td>
    <td><input name="gt_1" type="text" id="gt_1" value="<?=$_POST['gt_1'];?>" size="8" /></td>
    <td><input name="gt_2" type="text" id="gt_2" value="<?=$_POST['gt_2'];?>" size="8" /></td>
  </tr>
  <?
		$base = strtotime("1970-01-01 00:00:00");
		$today = strtotime("00:00:00");		
		$at_1 = strtotime($_POST['remain_1']) - $today + strtotime($_POST['gt_1']);
		$at_2 = strtotime($_POST['remain_2']) - $today + strtotime($_POST['gt_2']);		
		
		$lines = preg_split("/\n/",$report);
		$drive_techs = array();
		foreach($lines as $line) {
			if(preg_match("/(\w+ Drive)\: (\d+)/",$line,$matches)) {
				$drive_techs[$matches[1]] = $matches[2];
			}
		}
		
		if(!count($drives)) {
			$launch_time = "tech level espionage required";
		}
		
		
		$speeds = array(0,0,0,0,0,0,0,0,1000000000,0,12500,5000,10000,10000,7500,2000,15000,2500,10000,4000,10000,5000);
		$drives = array("","","","","","","","","Jet Drive","","Jet Drive","Jet Drive","Pulse Drive","Pulse Drive","Jet Drive","Jet Drive","Pulse Drive","Pulse Drive","Warp Drive","Pulse Drive","Warp Drive","Warp Drive");
		if($drive_techs["Pulse Drive"] >= 5) {
			$speeds[11] = 10000;
			$drives[11] = "Pulse Drive";
		}
		if($drive_techs['Warp Drive'] >= 8) {
			$speeds[19] = 5000;
			$drives[19] = "Warp Drive";
		}

		/*
		$slowest = "";
		$slowest_speed = 10000000000;
		foreach($_POST as $key => $val) {
			if(preg_match("/them_rn_(\w+)/",$key,$matches) && $val) {
				$unit = $matches[1];
				$ui = array_search($unit,$units);
				$drive = $drives[$ui];

				if($drive == "Jet Drive") {
					$speed = $speeds[$ui]*(1+$drive_techs[$drive]/10);
				}
				if($drive == "Pulse Drive") {
					$speed = $speeds[$ui]*(1+$drive_techs[$drive]/10*2);
				}
				if($drive == "Warp Drive") {
					$speed = $speeds[$ui]*(1+$drive_techs[$drive]/10*3);					
				}
				if($speed < $slowest_speed) {
					$slowest_speed = $speed;
					$slowest = $unit;
				}
			}
		}
		lp("slowest unit is $slowest at $slowest_speed");
		
		$coords = array();
		if(preg_match("/(\d+)\:(\d+)\:(\d+)/",$_POST['src'],$matches)) {
			$coords['src']['g'] = $matches[1];
			$coords['src']['s'] = $matches[2];
			$coords['src']['p'] = $matches[3];			
		}
		if(preg_match("/(\d+)\:(\d+)\:(\d+)/",$_POST['dst'],$matches)) {
			$coords['dst']['g'] = $matches[1];
			$coords['dst']['s'] = $matches[2];
			$coords['dst']['p'] = $matches[3];
		}
		$distance = "";
		if($coords['src']['g'] != $coords['dst']['g']) {
			$distance = abs($coords['src']['g'] - $coords['dst']['g'])*20000;
		} else if($coords['src']['s'] != $coords['dst']['s']) {
			$distance = abs($coords['src']['s'] - $coords['dst']['s'])*95+2700;
		} else if($coords['src']['p'] != $coords['dst']['p']) {
			$distance = abs($coords['src']['p'] - $coords['dst']['p'])*5 + 1000;
		}
		lp("distance: $distance");
		$throttle = 1;  //100%
		$time = 3500/1*sqrt(10*$distance/$slowest_speed)+10;		
		$hours = floor($time/3600);
		$minutes = floor(($time-$hours*3600)/60);
		$seconds = floor($time-$hours*3600-$minutes*60);
		lp("time: $hours:$minutes:$seconds");
		*/

	?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right" bgcolor="#dddddd">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Attack Time      </td>
    <td><?=date("H:i:s",$at_1);?></td>
    <td><?=date("H:i:s",$at_2);?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right" bgcolor="#dddddd">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Travel Time      </td>
    <td colspan="2"><input name="travel_time" type="text" id="travel_time" value="<?=$_POST['travel_time'];?>" size="8" /> 
      Launch @ <?=$launch_time;?></td>
    </tr>		
  </table>

</form>

</body>
</html>
