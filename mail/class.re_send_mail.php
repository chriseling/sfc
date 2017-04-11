<?php
/**
*	重寄 email 用
*/
require_once(dirname(__FILE__) . '/html.mime.mail.php');

class ReSendMail{
var $mail;
var $debug;

	function ReSendMail($mail=null)
	{
		if(is_object($mail)){
			$this->mail = $this->_clone($mail);
		}else{
			$this->mail = new HtmlMimeMail();
			$this->mail->setSMTPParams('seed.net.tw');
			$this->mail->setReturnPath('s843098@tpts5.seed.net.tw');
		}
	}

/**
*	重寄一封
*/
	function reSendOne($file){
		$arr = file_get_contents($file);
		$arr = $this->base64_unserialize($arr);
		$this->mail->headers = $arr['headers'];
		$this->mail->output = $arr['output'];
		$this->mail->build_params = $arr['build_params'];
		$this->mail->is_built = true;
	//為了讓 LOG CLASS 抓的到原始資料------Start
		$this->mail->text = $arr['text'];
		$this->mail->html_text = $arr['html_text'];
		$this->mail->html = $arr['html'];
	//為了讓 LOG CLASS 抓的到原始資料------End
		$res = $this->mail->send($arr['to'], 'smtp');
		if(!$res){
			$this->debug('send error: '.$file);
		}
		return $res;
	}

/**
*	重寄很多封
*/
	function reSendMany($arr){
		foreach($arr as $a){
			$this->reSendOne($a);
		}
	}

/**
*	整個log檔重寄
*/
	function reSendByLog($logFile,$serializeDir){
		$arr = file($logFile);
		$m=Chr(127);
		foreach($arr as $a){
			$a = trim($a);
			$b = explode($m,$a);
			$file = $b[0].'.serialize'.$b[1].'.ser';
			$path = $serializeDir.$file;
			if(file_exists($path)){
				$this->reSendOne($a);
			}else{
				$this->debug('When reSendByLog File is not exists :'.$path);
			}
		}
	}

/**
*	重寄一個目錄
* @param string $dir 需要最後的斜線
* @param bool $isOnlyError
*/
	function reSendOneDir($dir,$isOnlyError=true){
		$prefix = ($isOnlyError)?'error.':'';
		$fileList = glob(realpath($dir).'/'.$prefix.'*.ser');
		$this->debug('When reSendOneDir those files will send :'.print_r($fileList,true));
		$this->reSendMany($fileList);
	}

/**
*	複製物件
*/
	function _clone($obj)
	{
		if(is_null($obj)) $obj=$this;
		if (version_compare(phpversion(), '5.0') < 0) {
			return $obj;
		} else {
			return @clone($obj);
		}
	}

/**
*	解碼
*/
	function base64_unserialize($str){
	   $ary = unserialize($str);
	   if (is_array($ary)){
	       foreach ($ary as $k => $v){
	           if (is_array(unserialize($v))){
	               $ritorno[base64_decode($k)]=$this->base64_unserialize($v);
	           }else{
	               $ritorno[base64_decode($k)]=base64_decode($v);
	           }
	       }
	   }else{
	       return false;
	   }
	   return $ritorno;
	}

/**
*	DEBUG
*/
	function debug($txt){
		if($this->debug){
			echo $txt."<br>\n";
		}
	}

}
?>