<?php

class EmailLog{

var $path;
var $enable;
var $saveBody;
var $saveError;
var $saveRepeatSendData;
var $isSaveBodyInList;
var $timePattern;
var $mark;

	function EmailLog($path=null){
		if(!$path)$path=((defined('ROOT_DB'))?ROOT_DB:'').'mail.log/';
		$this->setPath($path);
		$this->enable = true;
		$this->saveBody = true;
		$this->saveError = true;
		$this->saveRepeatSendData = true;
		$this->isSaveBodyInList = false;
		$this->timePattern = 'm';
	}

	function setPath($path){
		$this->path = $this->EndWithSlash($path);
	}

	function EndWithSlash($str)
	{
		$str1 = str_replace('\\','/',$str);
		if (substr($str1,strlen($str1)-1,1) != "/")
			$str = $str.'/';
		return $str;
	}

	function setEnable($bool){
		$this->enable = $bool;
	}

	function setSaveBody($bool){
		$this->saveBody = $bool;
	}

	function setSaveError($bool){
		$this->saveError = $bool;
	}

	function setSaveRepeatSendData($bool){
		$this->saveRepeatSendData = $bool;
	}

	function setIsSaveBodyInList($bool){
		$this->isSaveBodyInList = $bool;
	}

	function setTimePattern($string){
		$this->timePattern = $string;
	}

	function save($source){
		if(!$this->enable) return;
		$m=Chr(127);
		$dir = $this->makeFolder($this->path.date($this->timePattern));
		
		$list['status'] =($source->lastResult)? 'success':'error';

		$microtime = microtime();
		$list['mark'] =  '.'.date('Ymd_His_').substr(md5($source->output),0,3).substr($microtime,strlen($microtime)-3,3);
		$this->mark = $list['mark'];

		$list['from'] = ' '. $this->filterString($source->headers['From']);
		$list['to'] = ' '. $this->filterString($source->lastSendTo);
		$list['subject'] = ' '. $this->filterString($source->headers['Subject']);
		$list['time'] = ' '. date('Y/m/d H:i:s');
		$list['ip'] = ' '. $_SERVER['REMOTE_ADDR'];

		$listFile[] = $dir.'mail.list.log';

		if($list['status'] == 'error'){
			$listFile[] = $dir.'error.mail.list.log';
		}

		$bodyFile = $dir.$list['status'].$list['mark'].'.htm';
		$serializeFile = $dir.$list['status'].'.serialize'.$list['mark'].'.ser';

		if($this->saveError){
			$list['error'] = $this->filterString($source->errors);
		}
		if($this->saveBody){
			$body = nl2br($source->text).'<hr>'.nl2br($source->html_text).'<hr>'.$source->html;
			if($this->isSaveBodyInList){
				$list['body'] = $this->filterString($body);
			}else{
				$txt="<table border=1 cellpadding=1 cellspacing=1>
						<tr><td>Status : </td><td>
								".$list['status']."
							</td></tr>
						<tr><td>Errors : </td><td>
								".$this->filterString($source->errors)."
							</td></tr>
						<tr><td>From : </td><td>
								".htmlspecialchars($list['from'], ENT_QUOTES)."
							</td></tr>
						<tr><td>To : </td><td>
								".str_replace(',','<br>',htmlspecialchars($source->lastSendTo, ENT_QUOTES))."
							</td></tr>
						<tr><td>Cc : </td><td>
								".str_replace(',','<br>',htmlspecialchars($source->headers['Cc'], ENT_QUOTES))."
							</td></tr>
						<tr><td>Bcc : </td><td>
								".str_replace(',','<br>',htmlspecialchars($source->headers['Bcc'], ENT_QUOTES))."
							</td></tr>
						<tr><td>Subject : </td><td>
								".$list['subject']."
							</td></tr>
						<tr><td>Time : </td><td>
								".$list['time']." (".$list['ip'].")
							</td></tr>
					</table>
					";
				$txt.=$body;
				$this->writeFile($bodyFile,$txt);
			} // end if($this->isSaveBodyInList)
		} // end if($this->saveBody)
		
		if($this->saveRepeatSendData){
			$serializeArr = array(
					'to'=>$source->lastSendTo,
					'headers'=>$source->headers,
					'output'=>$source->output,
					'build_params'=>$source->build_params,
					'text'=>$source->text,
					'html_text'=>$source->html_text,
					'html'=>$source->html,
					);
			$serializeArr=$this->base64_serialize($serializeArr);
			$this->writeFile($serializeFile,$serializeArr);
		}
		foreach($listFile as $lFile){
			$fp=fopen($lFile, 'a');
			fputs($fp,join($m,$list)."\r\n");
			fclose($fp);
		}
	}

	function filterString($string){
		return preg_replace("/\cM\n|\n|\r\n/","<br />",$string);
	}

	function makeFolder($dir,$isDeny=null){
		if (!is_dir($dir)){
				// create the cache folder
				$temp = explode('/',str_replace('\\','/',$dir));
				$cur_dir = '';
				$countTemp = count($temp);
				for($i=0;$i<$countTemp;$i++)
				{
					$cur_dir .= $temp[$i].'/';
					if (!is_dir($cur_dir))
					{
						// PROTECT the current cache folder
						if (@mkdir($cur_dir)&&($cur_dir!=getcwd()))
						{
							$this->writeFile($cur_dir.'.htaccess','Deny from all');
		            		$this->writeFile($cur_dir.'index.html','<script>history.back()</script>');
						}
					}
				}
			}
		return realpath($dir).((strstr(strtoupper(PHP_OS),'WIN'))?'\\':'/');
	}

	function writeFile($filename,$contents){
		$fp = @fopen($filename,'w+');
		if ($fp)
		{
			fputs($fp,$contents,strlen($contents));
			fclose($fp);
		}
	}

	function base64_serialize($ary){
	   if (is_array($ary)){
	       foreach ($ary as $k => $v){
	           if (is_array($v)){
	               $ritorno[base64_encode($k)]=$this->base64_serialize($v);
	           }else{
	               $ritorno[base64_encode($k)]=base64_encode($v);
	           }
	       }
	   }else{
	       return false;
	   }
	   return serialize ($ritorno);
	}
}

?>