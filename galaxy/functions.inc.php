<?

	date_default_timezone_set('America/Los_Angeles');

	include "db.php";

	function lp($string) {
		echo $string . "<br>\n";
	}
	
	function human() {
		$delay = rand(3,5);
		lp("human delay $delay");	
		sleep($delay);	
	}
	
	function sfc_get($url) {
		global $player, $ip_address;
		global $once;
		$useragent="Mozilla/5.0 (Windows NT 6.1; WOW64; rv:6.0) Gecko/20100101 Firefox/6.0 ";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_INTERFACE, $ip_address);
		curl_setopt($ch, CURLOPT_COOKIEJAR, "/home/sfc/public_html/galaxy/cookies/$player.txt");
		curl_setopt($ch, CURLOPT_COOKIEFILE, "/home/sfc/public_html/galaxy/cookies/$player.txt");
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
		curl_setopt($ch,CURLOPT_AUTOREFERER,true);
		curl_setopt($ch,CURLOPT_HEADER,true);
		curl_setopt($ch,CURLOPT_VERBOSE,true);
		
		curl_setopt($ch,CURLINFO_HEADER_OUT,true);
		
		curl_setopt($ch, CURLOPT_REFERER, "http://playstarfleet.com");
		curl_setopt($ch, CURLOPT_POST, true);
		preg_match("/\?(.+)/",$url,$matches);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $matches[1]);
		$return = curl_exec($ch);
		curl_close($ch);
		
		if(!$once) {

			//mail("babam@fu4ever.com","running $player",$return);
			$once = true;
		}
		
		return $return;
	}

	function hunter($content) {
		global $player;
		preg_match("/Solar System (\d+):(\d+)/",$content,$matches);
		$galaxy = $matches[1];
		$system = $matches[2];
		
		$content = preg_replace("#<table class='debris_table'>.+?<td class='resources'>(.+?)</td>.+?</table>#","$1",$content);
		$content = preg_replace('#<a href="/ranks.+?</a>#',"",$content);		
		
		preg_match_all("#(<tr id='planet.+?</tr>)#",$content,$matches);
		
		if(count($matches[1]) < 15) {
			lp("hunter error");
			//mail("babam@fu4ever.com","hunter error",$content);
			if(preg_match("/captcha/",$content)) {
				mail("6503877606@txt.att.net","hunter captcha $player","hunter captcha $player");
			}
			exit;
		}
		$sql = "insert into hunter values ";
		foreach($matches[1] as $row) {
			$data = array();
			if(preg_match("#<tr id='planet\_(\w+)#",$row,$matches)) {
				$data['slot'] = $matches[1];
			}
			if(preg_match("#<td class='name'>.+?<span class=\"\w+\">(.+?)</span>#",$row,$matches)) {
				$data['planet'] = $matches[1];
			}
			if(preg_match("#<span class='activity'>(.+?)</span>#",$row,$matches)) {
				$data['activity'] = $matches[1];
			}
			
			 
			if(preg_match("#<span class='symbols'>(.+?)</span>#",$row,$m)) {
				$data['symbol'] = $m[1];	
			}
			
			if(preg_match("#<td class='player'>.+?<span class=\"not_attackable\">.+?>(.+?)<.+?<.*?span style=\"color: \S+?\">(.+?)</span>.+?</span>#",$row,$matches)) {
				$data['player'] = mysql_escape_string($matches[1]);			
				$data['rank'] = preg_replace("/#/","",preg_replace("/,/","",$matches[2]));
			}
			

			if($data['slot']) {
				$sql .= "('$galaxy','$system','$data[slot]','".mysql_escape_string($data['planet'])."','".mysql_escape_string($data['player'])."','$data[rank]','$data[symbol]','$data[activity]',now()),";			
			}
			foreach($data as $key => $val) {
					$data[$key] = trim($val);
				}
			//print_r($data);
		}
		
		mysql_query(rtrim($sql,","));
		//echo $sql;
	}
	
	function debris($content) {
		
		preg_match("/Solar System (\d+):(\d+)/",$content,$matches);
		$galaxy = $matches[1];
		$system = $matches[2];
		
		$content = preg_replace("#<table class='debris_table'>.+?<td class='resources'>(.+?)</td>.+?</table>#","$1",$content);
		
		//echo $content;
		preg_match_all("#(<tr id='planet.+?</tr>)#",$content,$matches);
		//print_r($matches);
		if(count($matches[1]) < 15) {
			lp("debris error");
			mail("babam@fu4ever.com","debris error",$content);
			if(preg_match("/captcha/",$content)) {
				mail("6503877606@txt.att.net","debris captcha $player","debris captcha $player");
			}
			exit;
		}
		$sql = "insert into debris values ";
		foreach($matches[1] as $row) {
			$data = array();
			if(preg_match("#<tr id='planet\_(\w+)#",$row,$matches)) {
				$data['slot'] = $matches[1];
			}
			if(preg_match("#<td class='name'>.+?<span class=\"\w+\">(.+?)</span>#",$row,$matches)) {
				$data['planet'] = $matches[1];
			}
			if(preg_match("#<span class='activity'>(.+?)</span>#",$row,$matches)) {
				$data['activity'] = $matches[1];
			}
			
			if(preg_match("#<td class='debris'>(.+?)</td>#",$row,$matches)) {
				$data['debris'] = $matches[1];
			}
					
			if(preg_match("#<td class='player'>.+?<span class=\"\w+\">(.+?)<.*?span style=\"color: \S+?\">(.+?)</span>.+?</span>#",$row,$matches)) {
				$data['player'] = $matches[1];			
				$data['rank'] = preg_replace("/#/","",preg_replace("/,/","",$matches[2]));
				if(preg_match("#<span class='symbols'>(.+?)</span>#",$matches[0],$m)) {
					$data['symbol'] = $m[1];	
				}
			}
			if($data['debris']) {
				$sql .= "('$galaxy','$system','$data[slot]','".mysql_escape_string($data['planet'])."','".mysql_escape_string($data['player'])."','$data[rank]','$data[symbol]','$data[activity]','$data[debris]',now()),";			
			}
			foreach($data as $key => $val) {
					$data[$key] = trim($val);
				}
			//print_r($data);
		}
		
		mysql_query(rtrim($sql,","));
		//echo $sql;
	}
	
	function galaxy($content) {
		global $df_msg;
		global $player;
		preg_match("/Solar System (\d+):(\d+)/",$content,$matches);
		$galaxy = $matches[1];
		$system = $matches[2];
		
		$content = preg_replace("#<table class='debris_table'>.+?<td class='resources'>(.+?)</td>.+?</table>#","$1",$content);
		
		//echo $content;
		preg_match_all("#(<tr id='planet.+?</tr>)#",$content,$matches);
		//print_r($matches);
		if(count($matches[1]) < 15) {
			lp("page error $player");
			//mail("babam@fu4ever.com","page error $player",$content);
			if(preg_match("/captcha/",$content)) {
				mail("6503877606@txt.att.net","page captcha $player","page captcha $player");
			}
			exit;
		}
		$sql = "insert into galaxy values ";
		//print_r($matches);
		foreach($matches[1] as $row) {
			$data = array();
			if(preg_match("#<tr id='planet\_(\w+)#",$row,$matches)) {
				$data['slot'] = $matches[1];
			}
			if(preg_match("#<td class='name'>.+?<span class=\"not_attackable\">(.+?)</span>#",$row,$matches)) {
				$data['planet'] = mysql_escape_string($matches[1]);
			}
			if(preg_match("#<span class='activity'>(.+?)</span>#",$row,$matches)) {
				$data['activity'] = $matches[1];
			}
			
			if(preg_match("#<td class='debris'>(.+?)</td>#",$row,$matches)) {
				$data['debris'] = $matches[1];
				preg_match("#(\S+?)<br />.+?(\S+?)\s#",$data['debris'],$matches);
				if(strlen($matches[1]) > 6 || strlen($matches[2]) > 6) {
					$df_msg .= "$galaxy:$system:$data[slot] - $matches[1] $matches[2]\n<br>";
				}
			}
			
			if(preg_match("#<span class='symbols'>(.+?)</span>#",$row,$m)) {
				$data['symbol'] = $m[1];	
			}
			
			if(preg_match("#<td class='player'>.+?<span class=\"not_attackable\">.+?>(.+?)<.+?<.*?span style=\"color: \S+?\">(.+?)</span>.+?</span>#",$row,$matches)) {
				$data['player'] = mysql_escape_string($matches[1]);			
				$data['rank'] = preg_replace("/#/","",preg_replace("/,/","",$matches[2]));
			}
			//echo "<pre>";
			//print_r($data);
			//exit;
			if($data['planet']) {
				$sql .= "('$galaxy','$system','$data[slot]','$data[planet]','$data[player]','$data[rank]','$data[symbol]','$data[activity]',now()),";			
			}
			
			foreach($data as $key => $val) {
				$data[$key] = trim($val);
			}
		}
		
		
		
		mysql_query(rtrim($sql,","));
		//echo $sql;
}
	
	function inactive($content) {
		global $df_msg;
		global $player;
		preg_match("/Solar System (\d+):(\d+)/",$content,$matches);
		$galaxy = $matches[1];
		$system = $matches[2];
		
		$content = preg_replace("#<table class='debris_table'>.+?<td class='resources'>(.+?)</td>.+?</table>#","$1",$content);
		$content = preg_replace('#<a href="/ranks.+?</a>#',"",$content);
		
		//echo $content;
		preg_match_all("#(<tr id='planet.+?</tr>)#",$content,$matches);
		//print_r($matches);
		if(count($matches[1]) < 15) {
			lp("page error");
			//mail("babam@fu4ever.com","page error $player",$content);
			if(preg_match("/captcha/",$content)) {
				mail("6503877606@txt.att.net","page captcha $player","page captcha $player");
			}
			exit;
		}
		$sql = "replace into inactive values ";
		//print_r($matches);
		foreach($matches[1] as $row) {
			$data = array();
			if(preg_match("#<tr id='planet\_(\w+)#",$row,$matches)) {
				$data['slot'] = $matches[1];
			}
			if(preg_match("#<td class='name'>.+?<span class=\"not_attackable\">(.+?)</span>#",$row,$matches)) {
				$data['planet'] = mysql_escape_string($matches[1]);
			}
			if(preg_match("#<span class='activity'>(.+?)</span>#",$row,$matches)) {
				$data['activity'] = $matches[1];
			}
			
			if(preg_match("#<td class='debris'>(.+?)</td>#",$row,$matches)) {
				$data['debris'] = $matches[1];
				preg_match("#(\S+?)<br />.+?(\S+?)\s#",$data['debris'],$matches);
				if(strlen($matches[1]) > 6 || strlen($matches[2]) > 6) {
					$df_msg .= "$galaxy:$system:$data[slot] - $matches[1] $matches[2]\n<br>";
				}
			}
			
			if(preg_match("#<span class='symbols'>(.+?)</span>#",$row,$m)) {
				$data['symbol'] = $m[1];	
			}
			
			if(preg_match("#<td class='player'>.+?<span class=\"not_attackable\">.+?>(.+?)<.+?<.*?span style=\"color: \S+?\">(.+?)</span>.+?</span>#",$row,$matches)) {
				$data['player'] = mysql_escape_string($matches[1]);			
				$data['rank'] = preg_replace("/#/","",preg_replace("/,/","",$matches[2]));
			}
			//echo "<pre>";
			//print_r($data);
			//exit;
			if($data['planet']) {
				$sql .= "('$data[player]','$data[rank]','$data[symbol]',now()),";			
			}
			
			foreach($data as $key => $val) {
				$data[$key] = trim($val);
			}
		}
		
		
		
		mysql_query(rtrim($sql,","));
		//echo $sql;
}

	
	function map($content) {
		global $player;
			preg_match("/Solar System (\d+):(\d+)/",$content,$matches);
			$galaxy = $matches[1];
			$system = $matches[2];
			
			$content = preg_replace("#<table class='debris_table'>.+?<td class='resources'>(.+?)</td>.+?</table>#","$1",$content);
			$content = preg_replace('#<a href="/ranks.+?</a>#',"",$content);
			
			
			//echo $content;
			preg_match_all("#(<tr id='planet.+?</tr>)#",$content,$matches);
			//print_r($matches);
			if(count($matches[1]) < 15) {
				lp("page error");
				mail("babam@fu4ever.com","map error $player",$content);
				if(preg_match("/captcha/",$content)) {
					mail("6503877606@txt.att.net","page captcha $player","page captcha $player");
				}
				exit;
			}
			$sql = "insert into map values ";
			$sql2 = "replace into index values ";
			//print_r($matches);
			foreach($matches[1] as $row) {
				$data = array();
				if(preg_match("#<tr id='planet\_(\w+)#",$row,$matches)) {
					$data['slot'] = $matches[1];
				}
				if(preg_match("#<td class='name'>.+?<span class=\"not_attackable\">(.+?)</span>#",$row,$matches)) {
					$data['planet'] = mysql_escape_string($matches[1]);
				}
				if(preg_match("#<span class='activity'>(.+?)</span>#",$row,$matches)) {
					$data['activity'] = $matches[1];
				}
				
				
				if(preg_match("#<td class='alliance'>(.+?)</td>#",$row,$matches)) {
					if(preg_match("#<a.+?>(.+?)</a>#",$matches[1],$m)) {
						$data['alliance'] = mysql_escape_string($m[1]);
					}
				}

				
				if(preg_match("#<td class='debris'>(.+?)</td>#",$row,$matches)) {
					$data['debris'] = $matches[1];
					preg_match("#(\S+?)<br />.+?(\S+?)#",$data['debris'],$matches);
					if(strlen($matches[1]) > 6 || strlen($matches[2]) > 6) {
						//mail("babam@fu4ever.com","large df","$galaxy $system $data[slot] $matches[1] $matches[2]");
					}
				}
				
				if(preg_match("#<span class='symbols'>(.+?)</span>#",$row,$m)) {
					$data['symbol'] = $m[1];	
				}
				
				 
				
				if(preg_match("#<td class='player'>.+?<span class=\"not_attackable\">.+?>(.+?)<.+?<.*?span style=\"color: \S+?\">(.+?)</span>.+?</span>#",$row,$matches)) {
					$data['player'] = mysql_escape_string($matches[1]);			
					$data['rank'] = preg_replace("/#/","",preg_replace("/,/","",$matches[2]));
				}
				foreach($data as $key => $val) {
					$data[$key] = trim($val);
				}
				//echo "<pre>";
				//print_r($data);
				
				if($data['planet']) {
					$sql .= "('$galaxy','$system','$data[slot]','$data[planet]','$data[player]','$data[alliance]','$data[rank]','$data[symbol]','$data[activity]',now()),";	
					if($data['rank'] < 8001) {
						$sql2 .= "('$data[player]','$galaxy','$system','$data[slot]',now(),'$data[rank]'),";
					}
				}
				
			}


			mysql_query(rtrim($sql,","));
			mysql_query(rtrim($sql2,","));
			//echo $sql;
			//exit;
	}
	
?>
