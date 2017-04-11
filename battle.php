<?
	
	$is = array("missile","laser","pulse","particle","decoy","gauss","large","plasma","hermes","helios","artemis","atlas","apollo","charon","herc","dionysus","poseidon","gaia","athena","ares","hades","prometheus","zeus","hephaestus");
	
	$units = array("missile"=>0,"laser"=>1,"pulse"=>2,"particle"=>3,"decoy"=>4,"gauss"=>5,"large"=>6,"plasma"=>7,"hermes"=>8,"helios"=>9,"artemis"=>10,"atlas"=>11,"apollo"=>12,"charon"=>13,"herc"=>14,"dionysus"=>15,"poseidon"=>16,"gaia"=>17,"athena"=>18,"ares"=>19,"hades"=>20,"prometheus"=>21,"zeus"=>22,"hephaestus"=>23);
	
	$ships = array("hermes"=>true,"helios"=>true,"artemis"=>true,"atlas"=>true,"apollo"=>true,"charon"=>true,"herc"=>true,"dionysus"=>true,"poseidon"=>true,"gaia"=>true,"athena"=>true,"ares"=>true,"hades"=>true,"prometheus"=>true,"zeus"=>true,"hephaestus"=>true);
	
	$defenses = array("missile"=>true,"laser"=>true,"pulse"=>true,"particle"=>true,"decoy"=>true,"gauss"=>true,"large"=>true,"plasma"=>true);

	
	$hull = array("missile"=>200,"laser"=>200,"pulse"=>800,"particle"=>800,"decoy"=>2000,"gauss"=>3500,"large"=>10000,"plasma"=>10000,"hermes"=>100,"helios"=>200,"artemis"=>400,"atlas"=>400,"apollo"=>850,"charon"=>800,"herc"=>1200,"dionysus"=>1600,"poseidon"=>2700,"gaia"=>3000,"athena"=>6000,"ares"=>7500,"hades"=>7000,"prometheus"=>11000,"zeus"=>900000,"hephaestus"=>4000000);
	
	$shield = array("missile"=>20,"laser"=>25,"pulse"=>100,"particle"=>500,"decoy"=>2000,"gauss"=>200,"large"=>10000,"plasma"=>300,"hermes"=>0,"helios"=>1,"artemis"=>10,"atlas"=>10,"apollo"=>25,"charon"=>25,"herc"=>25,"dionysus"=>10,"poseidon"=>50,"gaia"=>100,"athena"=>200,"ares"=>500,"hades"=>400,"prometheus"=>500,"zeus"=>50000,"hephaestus"=>150000);
	
	$attack = array("missile"=>80,"laser"=>100,"pulse"=>250,"particle"=>150,"decoy"=>0,"gauss"=>1100,"large"=>0,"plasma"=>3000,"hermes"=>0,"helios"=>1,"artemis"=>50,"atlas"=>5,"apollo"=>150,"charon"=>1,"herc"=>5,"dionysus"=>1,"poseidon"=>400,"gaia"=>50,"athena"=>1000,"ares"=>1000,"hades"=>700,"prometheus"=>2000,"zeus"=>200000,"hephaestus"=>0);
	
	$cargo = array("hermes"=>5,"artemis"=>50,"atlas"=>5000,"apollo"=>100,"charon"=>100,"herc"=>25000,"dionysus"=>20000,"poseidon"=>800,"gaia"=>7500,"athena"=>1500,"ares"=>500,"hades"=>750,"prometheus"=>2000,"zeus"=>1000000,"hephaestus"=>1000000000);
	
	if($user == "babam@fu4ever.com") {
		//$debug = true;
	}
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
			"atlas"=>array("hermes"=>5,"helios"=>5,"charon"=>5),
			"zeus"=>array("prometheus"=>5,"hades"=>15,"ares"=>25,"athena"=>30,"poseidon"=>33,"gauss"=>50,"apollo"=>100,"particle"=>100,"pulse"=>100,"artemis"=>200,"laser"=>200,"missile"=>250,"atlas"=>250,"dionysus"=>250,"gaia"=>250,"charon"=>1250,"hercules"=>250,"hermes"=>1250,"helios"=>1250));
	
	$opposite['us'] = "them";
	$opposite['them'] = "us";
	
	$ore = array("missile"=>2000,"laser"=>1500,"pulse"=>6000,"particle"=>2000,"decoy"=>10000,"gauss"=>20000,"large"=>50000,"plasma"=>50000,"hermes"=>0,"helios"=>0,"artemis"=>3000,"atlas"=>2000,"apollo"=>6000,"charon"=>4000,"herc"=>6000,"dionysus"=>10000,"poseidon"=>20000,"gaia"=>10000,"athena"=>45000,"ares"=>50000,"hades"=>30000,"prometheus"=>60000,"zeus"=>5000000,"hephaestus"=>20000000);
	
	$crystal = array("missile"=>0,"laser"=>500,"pulse"=>2000,"particle"=>6000,"decoy"=>10000,"gauss"=>15000,"large"=>50000,"plasma"=>50000,"hermes"=>1000,"helios"=>2000,"artemis"=>1000,"atlas"=>2000,"apollo"=>2500,"charon"=>4000,"herc"=>6000,"dionysus"=>6000,"poseidon"=>7000,"gaia"=>20000,"athena"=>15000,"ares"=>25000,"hades"=>40000,"prometheus"=>50000,"zeus"=>4000000,"hephaestus"=>20000000);
	
	$hydrogen = array("missile"=>0,"laser"=>0,"pulse"=>0,"particle"=>0,"decoy"=>0,"gauss"=>2000,"large"=>0,"plasma"=>30000,"hermes"=>0,"helios"=>500,"artemis"=>0,"atlas"=>0,"apollo"=>0,"charon"=>1000,"herc"=>0,"dionysus"=>2000,"poseidon"=>2000,"gaia"=>10000,"athena"=>0,"ares"=>15000,"hades"=>15000,"prometheus"=>15000,"zeus"=>1000000,"hephaestus"=>10000000);
	

	
	if($_POST['button4'] == "Load Espionage Report") {
		$filled = array();
		$filled['textarea'] = $_POST['textarea'];
		$_POST['textarea'] = preg_replace("/\\\'/","",$_POST['textarea']);
		$_POST['textarea'] = preg_replace("/;/","",$_POST['textarea']);
		$lines = preg_split("/\n/",$_POST['textarea']);

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
										 "Gaia Class Colony Ship"=>"gaia",
										 "Athena Class Battleship"=>"athena",
										 "Ares Class Bomber"=>"ares",
										 "Hades Class Battleship"=>"hades",
										 "Prometheus Class Destroyer"=>"prometheus",
										 "Zeus Class"=>"zeus",
										 "Hephaestus Class Attack Platform"=>"hephaestus",
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
		$saved_us = $_POST['saved_us'];
		$_POST = $filled;
		$_POST['button'] = "Save Fleet";
		$_POST['saved_us'] = $saved_us;

	}
	
	
	if($_POST['button'] == "Save Fleet" || $_POST['button3'] == "Simulate") {
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
		
		$sql = "replace into saved values ('',now(),'$galaxy','$system','$planet','$_POST[textarea]','".stripslashes(trim($_POST[them_title]))."','".serialize($save)."','them','$user')";
		//echo $sql;
		mysql_query($sql);
		$_POST['saved_them'] = mysql_insert_id();
	}
	
	
	if($_POST['button2'] == "Save Fleet"|| $_POST['button3'] == "Simulate") {
		$save = array();
		foreach($_POST as $key => $val) {
			if(preg_match("/^us/",$key)) {
				$save[$key] = $val;
			}
		}
		$sql = "replace into saved values ('',now(),'','','','','".stripslashes(trim($_POST[us_title]))."','".serialize($save)."','us','$user')";

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
		//global $ave, $n, $filled;
		global $remain, $units;
		$index = $team."_$unit";

		if($_POST[$index]) {
			$r = $remain[$team][$units[$unit]];
			if(!$r) {
				$r = 0;
			}
			if($r == 0 && $team == "them" && $_POST[$index]) {
				$color = "#C8FAA9";
			} else if($r == $_POST[$index] && $team == "us") {
				$color = "#C8FAA9";
			} else if($r == 0 && $team == "us" && $_POST[$index]) {
				$color = "#F99191";
			} else if($r == $_POST[$index] && $team == "them" && $r > 0) {
				$color = "#F99191";
			} else if($r > 0 && $team == "them") {
				$color = "#FAFCA7";
			} else if($r < $_POST[$index] && $team == "us") {
				$color = "#FAFCA7";
			} else if($_POST[$index]) {
				$color = "#CCCCCC";
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
		global $is, $attack;
		echo($is[$unit['u']]."($team:$id) ".$attack[$is[$unit['u']]]*(1+$_POST["$team"."_a"]/10).":$unit[s]:$unit[h]");
	}
	
	if($_POST['button3'] == "Simulate") {
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

		$sims = 1;
		for($n=0;$n<$sims;$n++) {
			$battle = array();
			foreach($_POST as $key => $val) {
				if(preg_match("/(\w+)\_(\w+)/",$key,$matches) && $val) {
					$team = $matches[1];
					$unit = $matches[2];
	
					if(in_array($unit,$is)) {
						$ui = $units[$unit];
						$s = $shield[$unit]*(1+$_POST["$team"."_s"]/10);
						$h = $hull[$unit]*(1+$_POST["$team"."_h"]/10);

						for($i=0;$i<$val;$i++) {
							$battle[$team][] = array("u"=>$ui,"s"=>$s,"h"=>$h);
						}
					}
				}
			}
			
			for($i=0;$i<6;$i++) {
				if(count($battle["us"]) && count($battle["them"])) {
					foreach($battle as $team => $us) {
							foreach($us as $uid => $unit) {
								$rapid_fire = true;
								while($rapid_fire) {
									$target = rand(0,count($battle[$opposite[$team]])-1);
									$enemy = &$battle[$opposite[$team]][$target];
									$damage = $attack[$is[$unit['u']]]*(1+$_POST["$team"."_a"]/10);
									
									if($enemy['s']>=$damage) {
										if($damage > $shield[$is[$enemy['u']]]*(1+$_POST[$opposite[$team]."_s"]/10)*.01) {
											$enemy['s'] -= $damage;
										} 
									} else {
										$hull_damage = $damage - $enemy['s'];
										$enemy['h'] = $enemy['h'] - $hull_damage;
										$enemy['s'] = 0;
									}
									
									$r = $rf[$is[$unit['u']]][$is[$enemy['u']]];
									if($r) {
										$rf_chance = rand(0,$r-1);
										if(!($rf_chance < $r - 1)) {
											$rapid_fire = false;
										}
									} else {
										$rapid_fire = false;
									}
			
								}
							}
						}	
					}
				if(count($battle["us"]) && count($battle["them"])) {
					foreach($battle as $team => $us) {
							foreach($us as $uid => $unit) {
								$victim = &$battle[$team][$uid];
								$ui = $is[$unit['u']];
								if($unit['h'] > .7*$hull[$ui]*(1+$_POST["$team"."_h"]/10)) {
									$victim['s'] = $shield[$ui]*(1+$_POST["$team"."_s"]/10);
								} else if ($unit['h'] > 0) {
									$grim_reaper = rand(1,100);
									$hull_remaining = ceil($unit['h']/$hull[$ui]/(1+$_POST["$team"."_h"]/10)*100);
									if($grim_reaper <= $hull_remaining) {
										$victim['s'] = $shield[$ui]*(1+$_POST["$team"."_s"]/10);
									} else {
										unset($battle[$team][$uid]);
									}
								} else {
									unset($battle[$team][$uid]);
								}
							}
						}
					}
					if(count($battle["us"])) {
						$battle["us"] = array_values($battle["us"]);
					}
					if(count($battle["them"])) {
						$battle["them"] = array_values($battle["them"]);	
					}
				}
			
			if($debug) {
				echo memory_get_usage();
				lp("<strong>final state</strong>");
			}
			
			//include "round_summary.inc.php";
			//$last_round = count($tally)-1;
			
			/*foreach($tally[$last_round] as $team => $us) {
				foreach($us as $unit => $qty) {
					$ave[$team][$unit] += $qty;
				}
			}*/
			
			$remain = array();
			foreach($battle as $team => $us) {
				for($i=0;$i<count($us);$i++) {
					$remain[$team][$us[$i]['u']]++;
				}
			}
			
			if(count($remain["them"]) == 0) {
				$win++;
			}
			//print_r($remain);
		
			foreach($_POST as $key => $val) {
				if(preg_match("/(\w+)\_(\w+)/",$key,$matches) && $val) {
					$team = $matches[1];
					$unit = $matches[2];
					if(in_array($unit,$is)) {
						$qty = $val;
						$remain_qty = $remain[$team][$units[$unit]];
						if(!$remain_qty) {
							$remain_qty = 0;
						}
						$lost_qty = $qty - $remain_qty;
						//echo "$unit $qty - $lost_qty = $remain_qty";
						$ui = $unit;
						$costs[$team]['cost']['o'] += $qty*$ore[$ui];
						$costs[$team]['cost']['c'] += $qty*$crystal[$ui];
						$costs[$team]['cost']['h'] += $qty*$hydrogen[$ui];
						$costs[$team]['loss']['o'] += $lost_qty*$ore[$ui];
						$costs[$team]['loss']['c'] += $lost_qty*$crystal[$ui];
						$costs[$team]['loss']['h'] += $lost_qty*$hydrogen[$ui];
						
						if($ships[$ui]) {
							$costs[$team]['debris']['o'] += $lost_qty*$ore[$ui];
							$costs[$team]['debris']['c'] += $lost_qty*$crystal[$ui];
							$costs[$team]['debris']['oc'] += $lost_qty*$ore[$ui] + $lost_qty*$crystal[$ui];
							$costs['total']['debris']['o'] += $lost_qty*$ore[$ui];
							$costs['total']['debris']['c'] += $lost_qty*$crystal[$ui];
							$costs['total']['debris']['oc'] += $lost_qty*$ore[$ui] + $lost_qty*$crystal[$ui];
						}
						if($team == "us") {
							$costs[$team]['cargo']['o'] += $remain_qty*$cargo[$ui];	
						}
						
					}
				}
			}
			//$string = print_r($_POST,true);
			//$string .= print_r($costs,true);
			//mail("babam@fu4ever.com","battle sim",$string);
		}
	}

?>
<form method="post" action="">

	<table border="0" cellpadding="3" cellspacing="2" bgcolor="#eeeeee">
  <tr>
	
	  <td align="right" bgcolor="#dddddd">&nbsp;</td>
	  <td bgcolor="#F99191"><strong>Them</strong></td>
	  <td bgcolor="#7AB8FC"><strong>Us</strong></td>
	  <td rowspan="<?=count($units)+2;?>" valign="top">
      <div style="background-color:#F99191; padding:15px;"><strong>THEM</strong>
	    <p>
	      <select name="saved_them" id="saved_them" onchange="submit()">
	        <option value="">-- saved fleet --</option>
	        <?
				$sql = "select * from saved where type = 'them' and who = '$user' order by id desc";
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
          </select>
          <br />
          <input type="text" name="them_title" id="textfield" value="<?=$filled["them_title"];?>" />
          <input type="submit" name="button" id="button" value="Save Fleet" />
	    </p>
	    <table>
	      <tr>
	        <td>armor:</td>
	        <td><select name="them_h" id="them_h">
	          <? for($i=0;$i<20;$i++) { 

				echo "<option value=\"$i\" ".selected("them_h",$i).">$i"."</option>";
			} ?>
	          </select></td>
	        <td>o: </td>
	        <td><input name="them_ore" type="text" value="<?=$filled['them_ore'];?>" /></td>
          </tr>
	      <tr>
	        <td>weapon:</td>
	        <td><select name="them_a" id="them_a">
	          <? for($i=0;$i<20;$i++) { 

				echo "<option value=\"$i\" ".selected("them_a",$i).">$i"."</option>";
			} ?>
	          </select></td>
	        <td>c:</td>
	        <td><input name="them_crystal" type="text" value="<?=$filled['them_crystal'];?>"  /></td>
          </tr>
	      <tr>
	        <td>shield:</td>
	        <td><select name="them_s" id="them_s">
	          <? for($i=0;$i<20;$i++) { 

				echo "<option value=\"$i\" ".selected("them_s",$i).">$i"."</option>";
			} ?>
	          </select></td>
	        <td>h:</td>
	        <td><input name="them_hydrogen" type="text" value="<?=$filled['them_hydrogen'];?>"  /></td>
          </tr>
        </table>

	    <table>
	      <tr>
	        <td align="right"><strong>value</strong></td>
	        <td>o:</td>
	        <td><?=cost("them","cost","o");?></td>
	        <td>c:</td>
	        <td><?=cost("them","cost","c");?></td>
	        <td>h:</td>
	        <td><?=cost("them","cost","h");?></td>
          </tr>
	      <tr>
	        <td align="right"><strong>losses</strong></td>
	        <td>o:</td>
	        <td><?=cost("them","loss","o");?></td>
	        <td>c:</td>
	        <td><?=cost("them","loss","c");?></td>
	        <td>h:</td>
	        <td><?=cost("them","loss","h");?></td>
          </tr>
	      <tr>
	        <td align="right"><strong>debris</strong></td>
     
 
          <td>o:</td>
          <td><?=cost("them","debris","o");?></td>
          <td>c:</td>
          <td><?=cost("them","debris","c");?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
     
	        
          </tr>
        </table>
        </div>
        <div style="background-color:#7AB8FC; padding:15px;">
	    <strong>US</strong>
	    <p>
	      <select name="saved_us" id="saved_us" onchange="submit()">
	        <option value="">-- saved fleet --</option>
	        <?
				$select = "";
				$sql = "select * from saved where type = 'us' and who = '$user' order by id desc";
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
          </select>
          <br />
          <input type="text" name="us_title" id="textfield2" value="<?=$filled["us_title"];?>" />
          <input type="submit" name="button2" id="button2" value="Save Fleet" />
	    </p>
	    <table>
	      <tr>
	        <td>armor: </td>
	        <td><select name="us_h" id="us_h">
	          <? for($i=0;$i<20;$i++) { 

				echo "<option value=\"$i\" ".selected("us_h",$i).">$i"."</option>";
			} ?>
	          </select></td>
          </tr>
	      <tr>
	        <td>weapon:</td>
	        <td><select name="us_a" id="us_a">
	          <? for($i=0;$i<20;$i++) { 

				echo "<option value=\"$i\" ".selected("us_a",$i).">$i"."</option>";
			} ?>
	          </select></td>
          </tr>
	      <tr>
	        <td>shield:</td>
	        <td><select name="us_s" id="us_s">
	          <? for($i=0;$i<20;$i++) {
	
				echo "<option value=\"$i\" ".selected("us_s",$i).">$i"."</option>";
			} ?>
	          </select></td>
          </tr>
      </table>
        <table>
          <tr>
            <td align="right"><strong>value</strong></td>
            <td>o:</td>
            <td><?=cost("us","cost","o");?></td>
            <td>c:</td>
            <td><?=cost("us","cost","c");?></td>
            <td>h:</td>
            <td><?=cost("us","cost","h");?></td>
          </tr>
          <tr>
            <td align="right"><strong>losses</strong></td>
            <td>o:</td>
            <td><?=cost("us","loss","o");?></td>
            <td>c:</td>
            <td><?=cost("us","loss","c");?></td>
            <td>h:</td>
            <td><?=cost("us","loss","h");?></td>
          </tr>
          <tr>
            <td align="right"><strong>debris</strong></td>
            <td>o:</td>
            <td><?=cost("us","debris","o");?></td>
            <td>c:</td>
            <td><?=cost("us","debris","c");?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            
          </tr>
        </table>
        </div>
        <div style="background-color:#C8FAA9; padding:15px">
        
          <input style="width:400px; height:2em; font-size:18px; font-weight:bold;" type="submit" name="button3" id="button3" value="Simulate" />
       <br />

        <table>
          <tr>
            <td><strong>Win</strong></td>
            <td><?
      		if($n) {
				echo number_format($win / $n * 100,0) . "%";
			}?>
            </td>
          </tr>
          <tr>
            <td><strong>Plunder</strong></td>
            <? 
				$plunder_avail = ($filled['them_ore']+$filled['them_crystal']+$filled['them_hydrogen'])/2;
				
			?>
            <td>
			<? if($n) { ?>
			<? if($win / $n == 0) { ?>
            0 / <?=number_format($plunder_avail,0);?> (0% plundered)
            <? 
				$plunder_percent = 0;
			   } else if($costs["us"]["cargo"]["o"]/$n > $plunder_avail) { ?>
				<?=number_format($plunder_avail,0);?> / <?=number_format($plunder_avail,0);?> (100% plundered)
            <? 
				$plunder_percent = 1;
			} else { ?>
			<?=cost("us","cargo","o");?> / <?=number_format($plunder_avail,0);?>
 (<?=number_format($costs["us"]["cargo"]["o"]/$n / $plunder_avail *100,1);?>% plundered - need <?=ceil(($plunder_avail-$costs["us"]["cargo"]["o"]/$n)/25000);?> hercs)
 				<? 
				
				$plunder_percent = $costs["us"]["cargo"]["o"] / $n / $plunder_avail;
				} ?>
 			<? } ?>
 			</td>
          </tr>
          <tr>
            <td><strong>Debris</strong></td>
            <td>			<? if($n) { ?> <?=cost("total","debris","oc");?> (<?=ceil($costs['total']['debris']['oc']/$n/20000*.3);?> dios)<? } ?></td>
          </tr>
          <tr>
            <td><strong>Profit/Loss</strong></td>
            <td>			<? if($n) { ?>
            <table><tr>
            <td>o:</td>
            <td><?=number_format($costs["total"]["debris"]["o"]*.3/$n + $plunder_percent*$filled['them_ore']/2 - $costs['us']['loss']['o']/$n,0);?></td>
            <td>c:</td>
            <td><?=number_format($costs["total"]["debris"]["c"]*.3/$n + $plunder_percent*$filled['them_crystal']/2 - $costs['us']['loss']['c']/$n,0);?></td>
            <td>h:</td>
            <td><?=number_format($plunder_percent*$filled['them_hydrogen']/2 - $costs['us']['loss']['h']/$n,0);?></td>
          </tr></table>
            
       
             
              <? } ?></td>
          </tr>
        </table>
        </div>
        <p>&nbsp;</p>
        <? /*
      <table width="200" border="0">
        <tr bgcolor="#eeeeee">
          <td>Their Attack:</td>
          <td><strong>SRC</strong>
            <input name="src" type="text" id="src" value="<?=$_POST['src'];?>" size="8" /></td>
          <td><strong>DST
            <input name="dst" type="text" id="dst" value="<?=$_POST['dst'];?>" size="8" />
          </strong></td>
        </tr>
        <tr bgcolor="#eeeeee">
          <td>Remaining </td>
          <td><input name="remain_1" type="text" id="remain_1" value="<?=$_POST['remain_1'];?>" size="8" /></td>
          <td><input name="remain_2" type="text" id="remain_2" value="<?=$_POST['remain_2'];?>" size="8" /></td>
        </tr>
        <tr bgcolor="#eeeeee">
          <td>Game Time</td>
          <td><input name="gt_1" type="text" id="gt_1" value="<?=$_POST['gt_1'];?>" size="8" /></td>
          <td><input name="gt_2" type="text" id="gt_2" value="<?=$_POST['gt_2'];?>" size="8" /></td>
        </tr>
        <tr bgcolor="#eeeeee">
          <td>Attack Time </td>
          <td><?=date("H:i:s",$at_1);?></td>
          <td><?=date("H:i:s",$at_2);?></td>
        </tr>
        <tr bgcolor="#eeeeee">
          <td>Travel Time </td>
          <td colspan="2"><input name="travel_time" type="text" id="travel_time" value="<?=$_POST['travel_time'];?>" size="8" />
            Launch @
            <?=$launch_time;?></td>
        </tr>
      </table>*/?></td>
	  <td  rowspan="<?=count($units)+1;?>" valign="top"><strong>
	    <label>
	      <input type="submit" name="button4" id="button4" value="Load Espionage Report" />
        </label>
	    <br />
        <label>
          <textarea name="textarea" id="textarea" cols="40" rows="47"><?=$report;?>
        </textarea>
        </label>
	  </strong></td>
	  </tr>
  <?
	$i=0;
		foreach($units as $unit => $dud) { 
			?>
	

	<tr>
	 
    <td align="right" bgcolor="#dddddd" nowrap="nowrap"><strong>
      <?=$unit;?> <img src="images/<?=$unit;?>_class.png" />
    </strong></td>
    <td nowrap="nowrap"><input name="them_<?=$unit;?>" type="text" tabindex="<?=$i+1;?>" size="3" value="<?=$filled["them_$unit"];?>"> <? /*<input name="them_rn_<?=$unit;?>" type="text" tabindex="<?=$i+1;?>" size="3" value="<?=$filled["them_rn_$unit"];?>">*/?> <?=result("them",$unit);?></td>
    <td nowrap="nowrap"><input name="us_<?=$unit;?>" type="text" tabindex="<?=$i+count($units)+1;?>" size="3"  value="<?=$filled["us_$unit"];?>"> <?=result("us",$unit);?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
	
	<?	$i++;}
	?>
  <?
			

	?>
  </table>

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
