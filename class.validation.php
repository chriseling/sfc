<?php
/**
* Html Form Validation
* @author Hill <s843098@tpts5.seed.net.tw>
* @version 2006/10/25 10:31:00
*/

Class Validation {

/**
*	1 找到有效的 host 並有 MX record
*	2 有效的 host 但沒有 MX record
*	3 不管 host 只通過是否有效字串
*	@var int email 的檢查等級
*/
var $checkEmailLevel = 2;
var $email_checkdnsrr=array();
var $email_gethostbyname=array();
var $email_gethostbyaddr=array();


/**
* 確認 E-mail
*/
function checkmail(&$a0){
//E-MAIL 基本過濾------Start
$a0=str_replace(' ','',trim($a0));
$a0=strtolower($a0);
//E-MAIL 基本過濾------End

//檢查是否有 checkdnsrr 函式------Start
	if(function_exists('checkdnsrr')):
		$funCheckDnsrr='checkdnsrr';
	else:
		include_once(ROOT_INCLUDE.'function.checkdnsrr_winnt.php');
		$funCheckDnsrr='checkdnsrr_winNT';
	endif;
//檢查是否有 checkdnsrr 函式------End

//進階檢查------Start
unset($res);
if( $hoststr=substr(strstr($a0,'@'),1) && strlen($a0)>2 ):
//為dns查詢做 Cache------Start
	if(!isset($this->email_checkdnsrr[$hoststr])){
		$this->email_checkdnsrr[$hoststr] = call_user_func($funCheckDnsrr,$hoststr);
		if($this->email_checkdnsrr[$hoststr]){
			$res = 1;
		}
	}
	if(!isset($this->email_gethostbyname[$hoststr]) && !$res){
		$this->email_gethostbyname[$hoststr] = gethostbyname($hoststr);
	}
	$long = ip2long($hoststr);
//為dns查詢做 Cache------End

	 if( $this->email_checkdnsrr[$hoststr] ):
	 	$res=1;
	 elseif( $this->email_gethostbyname[$hoststr]!=$hoststr ):
	 	$res=2;
	 elseif( !($long == -1 || $long === false || $long == $hoststr) ):
	 	if(!isset($this->email_gethostbyaddr[$hoststr])){
	 		$this->email_gethostbyaddr[$hoststr] = gethostbyaddr($hoststr);
	 	}
		if( $this->email_gethostbyaddr[$hoststr]!=$hoststr ) $res=2;
	 	else $res=3;
	 else:
	 	$res=3;//有 @ 字元
	 endif;
else:
$res=0; //沒有 @ 字元
endif;
//進階檢查------End

if(!$res)return false;
if($res > $this->checkEmailLevel){
	return false;
}else{
	return true;
}
}

/**
* 設定 E-mail 確認等級
*/
function setCheckEmailLevel($level){
	$this->checkEmailLevel = $level;
}



/**
* Check email by Regular Expression
* @param string $ml Email string
* @param int $maxSize Email string length
* @return bool
*/
function checkEmailByReg(&$ml,$maxSize=null){
	$ml = trim($ml);
	if(is_numeric($maxSize)){
		if(strlen($ml)>$maxSize){
			return false;
		}
	}
	$reg = '/^(?:[^(\040)<>@,;:".\\\[\]\000-\037\x80-\xff]+(?![^(\040)<>@,;:".\\\[\]\000-\037\x80-\xff])|"[^\\\x80-\xff\n\015"]*(?:\\[^\x80-\xff][^\\\x80-\xff\n\015"]*)*")(?:\.(?:[^(\040)<>@,;:".\\\[\]\000-\037\x80-\xff]+(?![^(\040)<>@,;:".\\\[\]\000-\037\x80-\xff])|"[^\\\x80-\xff\n\015"]*(?:\\[^\x80-\xff][^\\\x80-\xff\n\015"]*)*"))*@(?:[^(\040)<>@,;:".\\\[\]\000-\037\x80-\xff]+(?![^(\040)<>@,;:".\\\[\]\000-\037\x80-\xff])|\[(?:[^\\\x80-\xff\n\015\[\]]|\\[^\x80-\xff])*\])(?:\.(?:[^(\040)<>@,;:".\\\[\]\000-\037\x80-\xff]+(?![^(\040)<>@,;:".\\\[\]\000-\037\x80-\xff])|\[(?:[^\\\x80-\xff\n\015\[\]]|\\[^\x80-\xff])*\]))*$/';
	if (!preg_match($reg,$ml)) {
		return false;
	}else{
		return true;
	}
}




/**
* 確認 身份證字號
*/
function checkTaiwanIDCard(&$id){
$id = strtoupper(trim($id));
$t0=1;
$t1=array('A','B','C','D','E','F','G','H','J','K','L','M','N','P','Q','R','S','T','U','V','X','Y','W','Z','I','O');
$t2=array('10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35');
$t3=0;
foreach($t1 as $k=>$v){
	if($id[0]==$t1[$k]){
		$t3=$k;$t0=0;
	}
}
$t4 = $t2[$t3][0] + ($t2[$t3][1]*9);
$t5 = 0;
$idNum = strlen($id);
for($i=0;$i<$idNum;$i++){
	$t5 = $t5 + $id[$i]* (9-$i);
}
$t6 = $id[9];
$t7 = $t4+$t5+$t6;
if($t7%10 != 0 || $t0){
	return false;
}else{
	return true;
}
}

/**
* 確認統一編號
*/
function checkTaiwanInvoiceID($id){
	$inv = new CheckTaiwanInvoiceID();
	$isValid = $inv->isValid($id);
	return $isValid;
}

/**
*	同時確認身份字證 跟 統編
*/
function checkTaiwanIDCardAndInvoiceID($id){
	if($this->checkTaiwanIDCard($id)){
		return true;
	}
	if($this->checkTaiwanInvoiceID($id)){
		return true;
	}
	return false;
}

/**
* Check English Domain
*/
function checkEngDomain(&$val,$isIncludeHttp=false,$cleanHttp=false){
	$val = trim($val);
	if($isIncludeHttp){
		$prefixArr = array('http://', 'https://', 'ftp://', 'ftps://');
		$tmpval = $val;
		foreach($prefixArr as $a){
			$strlenA = strlen($a);
			if( strtolower(substr( $val, 0, $strlenA )) == $a ){
				$tmpval = substr( $val, $strlenA, strlen($val) );
			}
		}
		if($cleanHttp){	$val = $tmpval;	}
	}else{
		$tmpval = $val;
	}
	if( preg_match('/^([a-zA-Z0-9_-]([a-zA-Z0-9\._-]{0,61}[a-zA-Z0-9\._-])?\.)+[a-zA-Z]{2,6}$/', $tmpval) || 
		$this->checkIpv4($tmpval)
	  ){
		return true;
	}else{
		return false;
	}
}


/**
* Check String
* @param string $val check string
* @param int $min minimum length
* @prarm int $max maximal length
* @param string $reg string regular express
* @param boolean $regBool true: $val need match $reg. false: $val need not match $reg.
* @return boolean true is valid. false is not valid.
*/
function checkString(&$val,$min=1,$max=null,$reg=null,$regBool=true){
	$val = trim($val);
	$len = strlen($val);
	if($len < $min) return false;
	if(!empty($max) && $len > $max) return false;
	if($reg){
		if($regBool){
			if(!preg_match($reg,$val)) return false;
		}else{
			if(preg_match($reg,$val)) return false;
		}
	}
	return true;
}

/**
* Check Number
* @param string $val check number
* @param int $min minimum size
* @param int $max maximal size
* @param int $minLen minimum length
* @param int $maxLen maximal length
* @param string $checkType [int|float|stringInt|stringFloat]
* @return boolean true is valid. false is not valid.
*/
function checkNumber(&$val,$min=null,$max=null,$minLen=null,$maxLen=null,$checkType=null){
	$val = trim($val);
	if(!$minLen && !strlen($val))return true;
	include_once(dirname(__FILE__) .'/class.letter.php');
	$val = Letter::f_zen2han($val);
	if( !is_numeric($val) )return false;
	if(is_numeric($min) && $val<$min)return false;
	if(is_numeric($max) && $val>$max)return false;
	if(is_numeric($minLen) && strlen($val)<$minLen)return false;
	if(is_numeric($maxLen) && strlen($val)>$maxLen)return false;
	if($checkType){
		switch($checkType){
			case 'int':
				if(!is_int($val/1)){return false;}
				break;
			case 'float':
				if(!is_float($val/1)){return false;}
				break;
			case 'stringInt': //if the number over php int range
				if(strpos($val,'.')!==false){return false;}
				break;
			case 'stringFloat':
				if(strpos($val,'.')===false){return false;}
				break;
		}
	}
	if(strpos($checkType,'string')===false){
		if(strpos($val,'.')!==false){ //this will useful by auto gen sql
			settype($val, 'float');
		}else{
			settype($val, 'integer'); //2147483647
		}
	}
	return true;
}

/**
* 確認 生日
*/
function checkBirthday($y,$m,$d){
	$now = mktime();
	$mostOld = $now - (60*60*24*365*150);
	if(!$this->checkMyDate($y,$m,$d,$mostOld,$now))return false;
	return true;
}

/**
* Check Date
*/
function checkMyDate($y=1970,$m=1,$d=1,$minTimestamp=null,$maxTimestamp=null){
	if(	!$this->checkNumber($y,1,null,1,null,'int') || 
		!$this->checkNumber($m,1,12,1,2,'int') || 
		!$this->checkNumber($d,1,31,1,2,'int')
		)
		return false;
	if(!checkdate($m,$d,$y))return false;
	$thisTimestamp = mktime(0,0,0,$m,$d,$y);
	if($minTimestamp && $thisTimestamp<$minTimestamp)return false;
	if($maxTimestamp && $thisTimestamp>$maxTimestamp)return false;
	return true;
}

/**
* Check Range Date
*/
function checkDateSmallBig($yStart,$mStart,$dStart,$yEnd,$mEnd,$dEnd,$minTimestamp=null,$maxTimestamp=null,$equal=false){
	if( !$this->checkMyDate($yStart,$mStart,$dStart,$minTimestamp,$maxTimestamp) )return false;
	if( !$this->checkMyDate($yEnd,$mEnd,$dEnd,$minTimestamp,$maxTimestamp) )return false;
	$timestamp1 = mktime(0,0,0,$mStart,$dStart,$yStart);
	$timestamp2 = mktime(0,0,0,$mEnd,$dEnd,$yEnd);
	if($equal===true && $timestamp1==$timestamp2) return true;
	if($timestamp1<$timestamp2) return true;
	return false;
}

/**
* Check Time
*/
	function checkTime($hour=0,$min=0,$sec=0,$minSec=null,$maxSec=null){
		if(!$this->checkNumber($hour,0,23,1,2,'int'))return false;
		if(!$this->checkNumber($min,0,59,1,2,'int'))return false;
		if(!$this->checkNumber($sec,0,59,1,2,'int'))return false;
		$totalSec = $this->countTimeSec($hour,$min,$sec);
		if($minSec && $totalSec<$minSec)return false;
		if($maxSec && $totalSec>$maxSec)return false;
		return true;
	}
	function countTimeSec($hour=0,$min=0,$sec=0){
		$totalSec = $sec + ($min * 60) + ($hour * 60 *60);
		return $totalSec;
	}
	function checkTimeSmallBig($sH,$sI,$sS,$eH,$eI,$eS,$minSec=null,$maxSec=null,$equal=false){
		if( !$this->checkTime($sH,$sI,$sS,$minSec,$maxSec) || !$this->checkTime($eH,$eI,$eS,$minSec,$maxSec) ){
			return false;
		}
		$timestamp1 = $this->countTimeSec($sH,$sI,$sS);
		$timestamp2 = $this->countTimeSec($eH,$eI,$eS);
		if($equal===true && $timestamp1==$timestamp2) return true;
		if($timestamp1<$timestamp2) return true;
		return false;
	}

/**
* check datetime vaild
*/
	function checkDateTime($year,$mon,$day,$hour,$min,$sec,$minTimestamp=null,$maxTimestamp=null){
		if(!$this->checkMyDate($year,$mon,$day)){
			return false;
		}
		if(!$this->checkTime($hour,$min,$sec)){
			return false;
		}
		if( $minTimestamp || $maxTimestamp ){
			$thisTimestamp = mktime($hour,$min,$sec,$mon,$day,$year);
			if($minTimestamp && $thisTimestamp<$minTimestamp)return false;
			if($maxTimestamp && $thisTimestamp>$maxTimestamp)return false;
		}
		return true;
	}

/**
* check datetime small big
*/
	function checkDateTimeSmallBig($sY,$sM,$sD,$sH,$sI,$sS,$eY,$eM,$eD,$eH,$eI,$eS,$minTimestamp=null,$maxTimestamp=null,$equal=false){
		if( !$this->checkDateTime($sY,$sM,$sD,$sH,$sI,$sS,$minTimestamp,$maxTimestamp) || !$this->checkDateTime($eY,$eM,$eD,$eH,$eI,$eS,$minTimestamp,$maxTimestamp) ){
			return false;
		}
		$timestamp1 = mktime($sH,$sI,$sS,$sM,$sD,$sY);
		$timestamp2 = mktime($eH,$eI,$eS,$eM,$eD,$eY);
		if($equal===true && $timestamp1==$timestamp2) return true;
		if($timestamp1<$timestamp2) return true;
		return false;
	}


/**
* Check ip
*/
	function checkIpv4(&$arg1){
		include_once('class.ip.php');
		$args = &func_get_args();
		$args[0] = &$arg1;
		$ip = new GetIP();
		return call_user_method_array('checkIpv4',$ip,$args);
	}

/**
* Check Mask
*/
	function checkIpv4Mask(&$arg1){
		include_once('class.ip.php');
		$args = &func_get_args();
		$args[0] = &$arg1;
		$ip = new GetIP();
		return call_user_method_array('checkIpv4Mask',$ip,$args);
	}

/**
* Check Iprange
*/
	function checkIpSmallBig(&$arg1,&$arg2){
		include_once('class.ip.php');
		$args = &func_get_args();
		$args[0] = &$arg1;
		$args[1] = &$arg2;
		$ip = new GetIP();
		return call_user_method_array('checkIpSmallBig',$ip,$args);
	}

/**
* Check Mac
* @ignore preg_match('/^\w{1,2}\:\w{1,2}\:\w{1,2}\:\w{1,2}\:\w{1,2}\:\w{1,2}$/', $mac1)
* @param string $mac MAC address string
* @param string $split MAC address split character
* @param boolean $isConvert is or not auto convert MAC string format
* @param string $style MAC string format
* @return boolean true is valid. false is not valid.
*/
	function checkMac(&$mac, $split=':', $isConvert=true, $style = null){
		$mac = trim($mac);
		if(empty($mac)) return false;
		preg_match('/^([0-9a-fA-F]{2})[-'.$split.' ]?([0-9a-fA-F]{2})[-'.$split.' ]?([0-9a-fA-F]{2})[-'.$split.' ]?([0-9a-fA-F]{2})[-'.$split.' ]?([0-9a-fA-F]{2})[-'.$split.' ]?([0-9a-fA-F]{2})$/', $mac, $arr);
		if(strlen($arr[0]) != strlen($mac)){
			return false;
		}else{
			if($isConvert===true){
				if(is_null($style))$style = '%02X'.$split.'%02X'.$split.'%02X'.$split.'%02X'.$split.'%02X'.$split.'%02X';
				$mac = sprintf( $style, hexdec($arr[1]), hexdec($arr[2]), hexdec($arr[3]), hexdec($arr[4]), hexdec($arr[5]), hexdec($arr[6]));
			}
			return true;
		}
	}

} //end class



/**
*	檢查統編的 CLASS
*/
class CheckTaiwanInvoiceID{

	function isValid($idvalue){
		$tmp = '12121241';
	    $sum = 0;
	    $re = "/^\d{8}$/";
	   if (!preg_match($re,$idvalue)) {
	       return false;
	    }
	   for ($i=0; $i< 8; $i++) {
	     $s1 = substr($idvalue,$i,1);
	     $s2 = substr($tmp,$i,1);
	     $sum += $this->_cal($s1*$s2);
	   }
	   if (!$this->_valid($sum)) {
	      if (substr($idvalue,6,1)=='7') return($this->_valid($sum+1));
	   }
	   return($this->_valid($sum));
	}

	function _cal($n) {
	   $sum=0;
	   while ($n!=0) {
	      $sum += ($n % 10);
	      $n = ($n - $n%10) / 10;  // 取整數
	   }
	   return $sum;
	}

	function _valid($n) {
		return ($n%10 == 0)?true:false;
	}
}



?>
