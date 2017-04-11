<?
			if(count($battle[$i]['them'])) {
				foreach($battle[$i]['them'] as $unit) {
					$tally[$i]['them'][$unit['name']]++;
				}
			}

			if(count($battle[$i]['us'])) {
				foreach($battle[$i]['us'] as $unit) {
					$tally[$i]['us'][$unit['name']]++;
				}
			}
			
			if($debug) {
				$qty_string['them'] = "them:  ";
				$qty_string['us'] = "us:  ";
				$lost_string['them'] = "they lost:  ";
				$lost_string['us'] = "we lost:  ";			
				if(count($tally[$i])) {
					foreach($tally[$i] as $team => $us) {
						foreach($us as $name => $qty) {
							$qty_string[$team] .= "$qty"."x $name ";
							
						}
					}
				}
				//$qty_string['them'] .= " o:".$costs[$i]['them']['ore']." c:".$costs[$i]['them']['crystal']." h:".$costs[$i]['them']['hydrogen']." $:".$costs[$i]['them']['dollars'];
				//$qty_string['us'] .= " o:".$costs[$i]['us']['ore']." c:".$costs[$i]['us']['crystal']." h:".$costs[$i]['us']['hydrogen']." $:".$costs[$i]['us']['dollars'];
				
				if($i > 0) {
					foreach($tally[0] as $team => $us) {
						foreach($us as $name => $qty) {
							$lost_qty = $qty - $tally[$i][$team][$name];
							$lost_string[$team] .= "$lost_qty"."x $name ";
							$ui = array_search($name,$units);
							}
					}
				}
				//$lost_string['them'] .= " o:".$lost[$i]['them']['ore']." c:".$lost[$i]['them']['crystal']." h:".$lost[$i]['them']['hydrogen']." $:".$lost[$i]['them']['dollars'];
				//$lost_string['us'] .= " o:".$lost[$i]['us']['ore']." c:".$lost[$i]['us']['crystal']." h:".$lost[$i]['us']['hydrogen']." $:".$lost[$i]['us']['dollars'];
				
				if($debug) {
					lp($qty_string['them']);
					lp($qty_string['us']);
					lp($lost_string['them']);
					lp($lost_string['us']);
				}
			}

      ?>