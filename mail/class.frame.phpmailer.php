<?php
/**
*	PhpMailer Frame
*/
require_once(dirname(__FILE__) . '/html.mime.mail.php');

class FramePHPMailer extends HtmlMimeMail{

var $From;
var $FromName;
var $Subject;
var $Body;
var $to = array();
var $cc = array();
var $bcc = array();

	function AddAddress($email, $name=null){
		$this->to[] = $this->getJoinNameEmail($email,$name);
	}

	function AddCC($email, $name=null){
		$this->cc[] = $this->getJoinNameEmail($email,$name);
	}

	function AddBCC($email, $name=null){
		$this->bcc[] = $this->getJoinNameEmail($email,$name);
	}

	function AddAttachment($path, $name = "", $encoding = "base64", $type = "application/octet-stream"){
		if(!is_file($path)){
			$args = &func_get_args();
			call_user_func_array(array(parent,'addAttachment'), $args);
		}else{
			if(!strlen($name))$name = basename($path);
			parent::addAttachment(file_get_contents($path),$name,$type,$encoding);
		}
	}

	function Send(){
		$args = &func_get_args();
		if(count($args)){
			return call_user_func_array(array(HtmlMimeMail,'send'), $args);
		}
		if(count($this->cc))$this->setCc($this->cc);
		if(count($this->bcc))$this->setBcc($this->bcc);
		if(!empty($this->From)) $this->setFrom($this->getJoinNameEmail($this->From,$this->FromName));
		if(!empty($this->Subject)) $this->setSubject($this->Subject);
		if(!empty($this->Body)){
			if(strpos($this->Body,'<') && strpos($this->Body,'>')){
				$this->setHtml($this->Body);
			}else{
				$this->setText($this->Body);
			}
		}
		return parent::send($this->to,'smtp');
	}

	function ClearAddresses(){
		$this->to = array();
	}

	function ClearAllRecipients(){
		$this->to = array();
        $this->cc = array();
        $this->bcc = array();
	}
}
?>