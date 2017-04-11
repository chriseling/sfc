<?
	
	if($_POST['saved']) {
		$sql = "select * from arrival where id = '$_POST[saved]' and who = '$user'";
		$res = mysql_query($sql);
		$mr = mysql_fetch_assoc($res);
		$_POST['textarea'] = $mr['scan'];
	}
	
	if($_POST['button4'] == "Load Source" || $_POST['saved']) {
		
		$file = stripslashes($_POST['textarea']);
		$file = preg_replace("/\n/","",$file);
		
		preg_match("#selected.+?planet_name.+?>.+?>(.+?)</a>.+?coordinates.+?(\w+?):(\w+?):(\w+?)#",$file,$matches);
		$title = "$matches[2]:$matches[3]:$matches[4] $matches[1]";
		

		preg_match("#<td class='resource'>Time</td>\s+<td class='amount'>(.+?)</td>#",$file,$matches);
		/*<td class='resource'>Time</td>\s+<td class='amount'>(.+)</td>*/
		$gt = $matches[1];
		$fields = preg_split("/:/",$gt);
		$gth = $fields[0];
		$gtm = $fields[1];
		$gts = $fields[2];
		
		
		
		preg_match_all("@(<div id='\w+' class='js_timer'>.+?makeTimer\('\w+', (.+?), (\w+), null\);.+?</div>).+?<td class='mission_type'>(.+?)</td>.+?<td class='origin.*?'>(.+?)</td>.*?<td class='destination.*?'>(.+?)</td>.+?<td class='fleet'>(.+?)</td>@",$file,$matches);
		if($_POST['button4'] == "Load Source") {
			$sql = "insert into arrival values ('','$title','$_POST[textarea]','$user')";
			$res = mysql_query($sql);
		}
	} else {
		$_POST['textarea'] = "";
	}

	if(!$gt) {
		$gth = "00";
		$gtm = "00";
		$gts = "00";
	}

	if(!$_POST['travel_time']) {
		$_POST['travel_time'] = "00:00:00";
	}

	$speeds = array("hermes"=>1000000000,"helios"=>0,"artemis"=>12500,"atlas"=>5000,"apollo"=>10000,"charon"=>10000,"hercules"=>7500,"dionysus"=>2000,"poseidon"=>15000,"gaia"=>2500,"athena"=>10000,"ares"=>4000,"hades"=>10000,"prometheus"=>5000,"zeus"=>100);
	$drives = array("hermes"=>"Jet Drive","helios"=>"","artemis"=>"Jet Drive","atlas"=>"Jet Drive","apollo"=>"Pulse Drive","charon"=>"Pulse Drive","herc"=>"Jet Drive","dionysus"=>"Jet Drive","poseidon"=>"Pulse Drive","gaia"=>"Pulse Drive","athena"=>"Warp Drive","ares"=>"Pulse Drive","hades"=>"Warp Drive","prometheus"=>"Warp Drive","zeus"=>"Warp Drive");

	if($_POST['pulse'] >= 5) {
		$speeds["atlas"] = 10000;
		$drives["atlas"] = "Pulse Drive";
	}
	if($_POST['warp'] >= 8) {
		$speeds["ares"] = 5000;
		$drives["ares"] = "Warp Drive";
	}

	function reverse($src,$dst,$fleet) {
		global $speeds, $drives;
		if(preg_match("/(\d+)\:(\d+)\:(\d+)/",$src,$matches)) {
			$src_galaxy = $matches[1];
			$src_system = $matches[2];
			$src_planet = $matches[3];
		}
		if(preg_match("/(\d+)\:(\d+)\:(\d+)/",$dst,$matches)) {
			$dst_galaxy = $matches[1];
			$dst_system = $matches[2];
			$dst_planet = $matches[3];
		}
		preg_match_all("/alt=\"(.+?)\"/",$fleet,$matches);
		$slowest = "9000000000";
		//print_r($matches);
		foreach($matches[1] as $ship) {
			$ship = strtolower(preg_replace("/_class/","",$ship));
			if($speeds[$ship] < $slowest) {
				$slowest = $speeds[$ship];
				$slowest_name = $ship;
			}
		}
		
	}
	//print_r($matches[1]);

	/*
	
		
		
	
		
		

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
		
		
		
		
		
		
		
		
		
		  <td>&nbsp;</td>
    <td><strong>Win</strong></td>
    <td><strong>
     
    </strong></td>
		
		<td>&nbsp;</td>
    <td><strong>Debris:</strong></td>
    <td></td>
		
		
		*/
	
	
?>

<form method="post" action="">

	<table border="0" cellpadding="3" cellspacing="2" bgcolor="#eeeeee">
  <tr>
    <td valign="top"><p><strong>Instructions:</strong></p>
      <ol>
        <li>Enter your fleet's travel time to target in the box to the below.</li>
        <li>Copy and paste the moon scan or fleets page &quot;page source&quot; in the box to the right</li>
        <li>Hit &quot;Load Source&quot; and the system will calculate launch times for you.</li>
      </ol></td>
    <td valign="bottom" width="400"> 
      <p>view the moon scan or fleets page <strong>without</strong> using facebook (playstarfleet.com).  then press ctrl-u to view the page source.  copy that code into this box below.  you must sync your local PC time for this to work.</p></td>
    </tr>
  <tr>
    <td valign="top"><strong>
      <label>        </label>
    </strong>
      <table>
        <tr>
          <td height="76">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <? /*
          <td rowspan="3"><table>
            <tr>
              <td>&nbsp;</td>
              <td><strong>Enemy Drive Techs</strong></td>
              </tr>
            <tr>
              <td>Jet</td>
              <td><input name="jet" type="text" id="jet" size="3" value="<?=$_POST['jet'];?>" /></td>
              </tr>
            <tr>
              <td>Pulse</td>
              <td><input name="pulse" type="text" id="pulse" size="3" value="<?=$_POST['pulse'];?>" /></td>
              </tr>
            <tr>
              <td>Warp</td>
              <td><input name="warp" type="text" id="warp" size="3" value="<?=$_POST['warp'];?>" /></td>
              </tr>
            </table></td>*/?>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td colspan="2"><strong>Your Travel Time (For Launch Time Calculation)</strong></td>
          
        </tr>
        <tr>
          <td><strong>Game Time:</strong></td>
          <td><strong>
            <?=$gt;?>
          </strong></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><input name="travel_time" type="text" id="travel_time" size="10" value="<?=$_POST['travel_time'];?>" /></td>
        </tr>
        <tr>
          <td><strong>Time</strong></td>
          <td><strong>Type</strong></td>
          <td><strong>Origin</strong></td>
          <td><strong>Destination</strong></td>
          <td><strong>Fleet</strong></td>
          <td><p><strong>Arrival Time (</strong><strong> Launch Time)</strong></p></td>
          <td><strong> Return Time (Launch Time)</strong></td>
          <? /*<td><strong>Reverse Time (Intercept)</strong></td>*/?>
        </tr>
        <? 
				$color = "#ddd";
				if(is_array($matches[0]) && count($matches[0])) {
					$message = "";
				foreach($matches[0] as $i => $dud) {
					$seconds_passed = $matches[2][$i];	
					$seconds_left = $matches[3][$i];	
					$message .= $matches[4][$i] ." ". $matches[5][$i] ." ". $matches[6][$i] ." ".preg_replace("#/images/starfleet/ship_templates/(.+?.png)\?1\d+#","/images/$1",$matches[7][$i]). "<br>\n";
			?>
        <tr bgcolor="<?=$color;?>" style="background-color:<?=$color;?>">
          <td><?=$matches[1][$i];?></td>
          <td><?=$matches[4][$i];?></td>
          <td><?=$matches[5][$i];?></td>
          <td><?=$matches[6][$i];?></td>
          <td nowrap="nowrap"><?=preg_replace("#/images/starfleet/ship_templates/(.+?.png)\?1\d+#","/images/$1",$matches[7][$i]);?></td>
          <td nowrap="nowrap"><? //date("H:i:s",strtotime($gt) - $seconds_passed);?>
            <?=date("H:i:s",strtotime($gt) + $seconds_left);?>
            <br />
            <strong>(
              <?=date("H:i:s",strtotime($gt) + $seconds_left - (strtotime($_POST['travel_time']) - strtotime("00:00:00")));?>
              )</strong></td>
          <td nowrap="nowrap"><?=date("H:i:s",strtotime($gt) + $seconds_left*2 + $seconds_passed);?>
            <br />
            <strong>(
              <?=date("H:i:s",strtotime($gt) + $seconds_left*2 + $seconds_passed - (strtotime($_POST['travel_time']) - strtotime("00:00:00")));?>
              )</strong></td>
          <? /*<td><?=reverse($matches[4][$i],$matches[45][$i],$matches[6][$i]);?></td>*/?>
        </tr>
        <? 
			if($color == "#ddd") {
				$color = "#eee";
			} else {
				$color = "#ddd";
			}
		} mail("babam@fu4ever.com","[SFC] Arrival Parse",$message); }?>
      </table></td>
    <td valign="top"><strong>
      <label>
        <input type="submit" name="button4" id="button4" value="Load Source" />
        </label>
        <select name="saved" onchange="submit()">
        	<option value="">-- previous --</option>
          <?
						$sql = "select * from arrival where who = '$user'";
						$res = mysql_query($sql);
						if(mysql_num_rows($res)) {
							while($mr = mysql_fetch_assoc($res)) {
								if($_POST['saved'] == $mr['id']) {
									$selected = "selected = \"selected\"";
								} else {
									$selected = "";
								}
								
								?>
              <option <?=$selected;?> value="<?=$mr['id'];?>"><?=$mr['title'];?></option>
              <? } }
									
					?>
        </select>
      <br />
      <label>
        <textarea name="textarea" id="textarea" cols="40" rows="20"><?=stripslashes($_POST['textarea']);?></textarea>
        </label>
    </strong></td>
	  </tr>
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
