<?php
/**
* Filename.......: class.html.mime.mail.inc
* Project........: HTML Mime mail class
* Last Modified..: $Date: 2002/07/24 13:14:10 $
* CVS Revision...: $Revision: 1.4 $
* Copyright......: 2001, 2002 Richard Heyes
* 
* @version $Id: html.mime.mail.php,v 1.5 2005/04/28 11:57:00 CelloG Exp $;
*/

require_once(dirname(__FILE__) . '/mimepart.php');

class HtmlMimeMail
{
	/**
	* The html part of the message
    * @var string
    */
	var $html;

	/**
	* The text part of the message(only used in TEXT only messages)
	* @var string
	*/
	var $text;

	/**
	* The main body of the message after building
	* @var string
	*/
	var $output;

	/**
	* The alternative text to the HTML part (only used in HTML messages)
	* @var string
	*/
	var $html_text;

	/**
	* An array of embedded images/objects
	* @var array
	*/
	var $html_images;

	/**
	* An array of recognised image types for the findHtmlImages() method
	* @var array
	*/
	var $image_types;

	/**
	* Parameters that affect the build process
	* @var array
	*/
	var $build_params;

	/**
	* Array of attachments
	* @var array
	*/
	var $attachments;

	/**
	* The main message headers
	* @var array
	*/
	var $headers;

	/**
	* Whether the message has been built or not
	* @var boolean
	*/
	var $is_built;
	
	/**
    * The return path address. If not set the From:
	* address is used instead
	* @var string
    */
	var $return_path;
	
	/**
    * Array of information needed for smtp sending
	* @var array
    */
	var $smtp_params;
	var $smtpArr;
	
	/**
	* Last Send Result
	*/
	var $lastResult;
	
	/**
	* Last Send To
	*/
	var $lastSendTo;

	/**
	* no send only use in debug
	*/
	var $noSend=false;

	/**
	* email log object
	* @var object
	*/
	var $log;

/**
* Constructor function. Sets the headers
* if supplied.
*/

	function htmlMimeMail()
	{
		/**
        * Initialise some variables.
        */
		$this->html_images = array();
		$this->headers     = array();
		$this->is_built    = false;

		/**
        * If you want the auto load functionality
		* to find other image/file types, add the
		* extension and content type here.
        */
		$this->image_types = array(
									'gif'	=> 'image/gif',
									'jpg'	=> 'image/jpeg',
									'jpeg'	=> 'image/jpeg',
									'jpe'	=> 'image/jpeg',
									'bmp'	=> 'image/bmp',
									'png'	=> 'image/png',
									'tif'	=> 'image/tiff',
									'tiff'	=> 'image/tiff',
									'swf'	=> 'application/x-shockwave-flash'
								  );

		/**
        * Set these up
        */
		$this->build_params['html_encoding'] = 'quoted-printable';
		$this->build_params['text_encoding'] = '7bit';
		$this->build_params['html_charset']  = 'ISO-8859-1';
		$this->build_params['text_charset']  = 'ISO-8859-1';
		$this->build_params['head_charset']  = 'ISO-8859-1';
		$this->build_params['text_wrap']     = 998;

		/**
        * Defaults for smtp sending
        */
		$helo = $this->getDefaultHello();
		$this->smtp_params['host'] = 'localhost';
		$this->smtp_params['port'] = 25;
		$this->smtp_params['helo'] = $helo;
		$this->smtp_params['auth'] = false;
		$this->smtp_params['user'] = '';
		$this->smtp_params['pass'] = '';

		/**
        * Make sure the MIME version header is first.
        */
		$this->headers['MIME-Version'] = '1.0';
	}

/**
* Get default hello
*/
	function getDefaultHello(){
		if (!empty($_SERVER['HTTP_HOST'])) {
			$helo = $_SERVER['HTTP_HOST'];
		} elseif (!empty($_SERVER['SERVER_NAME'])) {
			$helo = $_SERVER['SERVER_NAME'];
		} else {
			$helo = '127.0.0.1';
		}
		return $helo;
	}

/**
* Get Class EmailLog
*/
	function &getLogObject($path=null){
		include_once(dirname(__FILE__). '/class.email.log.php');
		$log = new EmailLog($path);
		$this->log = &$log;
		return $this->log;
	}

/**
* Get join Email with name
*/
	function getJoinNameEmail($email,$name=null){
		if($name) $name = '"'.str_replace('"','',$name).' "';
		$emailFilter = array('"','<','>',' ');
		$email = '<'.str_replace($emailFilter, '', trim($email)).'>';
		return $name.$email;
	}


/**
* This function will read a file in
* from a supplied filename and return
* it. This can then be given as the first
* argument of the the functions
* add_html_image() or add_attachment().
*/
	function getFile($filePath)
	{
		if(!is_file($filePath)){
			return false;
		}
		return file_get_contents($filePath);
	}

/**
* Accessor to set the CRLF style
*/
	function setCrlf($crlf = "\n")
	{
		if (!defined('CRLF')) {
			define('CRLF', $crlf, true);
		}

		if (!defined('MAIL_MIMEPART_CRLF')) {
			define('MAIL_MIMEPART_CRLF', $crlf, true);
		}
	}

/**
* Accessor to set the SMTP parameters
*/
	function setSMTPParams($host = null, $port = null, $helo = null, $auth = null, $user = null, $pass = null)
	{
		if (!is_null($helo)) $this->smtp_params['helo'] = $helo;
		else $helo = $this->getDefaultHello();

		if(is_null($port)) $port = 25;
		if (!is_null($host)) $this->smtpArr[$host]=array(
											"host"=>$host,
											'port'=>$port,
											'helo'=>$helo,
											'auth'=>$auth,
											'user'=>$user,
											'pass'=>$pass,
											);
	}

/**
* Accessor function to set the text encoding
*/
	function setTextEncoding($encoding = '7bit')
	{
		$this->build_params['text_encoding'] = $encoding;
	}

/**
* Accessor function to set the HTML encoding
*/
	function setHtmlEncoding($encoding = 'quoted-printable')
	{
		$this->build_params['html_encoding'] = $encoding;
	}

/**
* Accessor function to set the text charset
*/
	function setTextCharset($charset = 'ISO-8859-1')
	{
		$this->build_params['text_charset'] = $charset;
	}

/**
* Accessor function to set the HTML charset
*/
	function setHtmlCharset($charset = 'ISO-8859-1')
	{
		$this->build_params['html_charset'] = $charset;
	}

/**
* Accessor function to set the header encoding charset
*/
	function setHeadCharset($charset = 'ISO-8859-1')
	{
		$this->build_params['head_charset'] = $charset;
	}

/**
* Accessor function to set the text wrap count
*/
	function setTextWrap($count = 998)
	{
		$this->build_params['text_wrap'] = $count;
	}

/**
* Accessor to set a header
*/
	function setHeader($name, $value)
	{
		if(strlen($name) && strlen($value))
			$this->headers[$name] = $value;
	}
	function &getHeader($name){
		return $this->headers[$name];
	}

/**
* Accessor to add a Subject: header
*/
	function setSubject($subject)
	{
		$this->headers['Subject'] = $subject;
	}

/**
* Accessor to add a From: header
*/
	function setFrom($from)
	{
		$this->headers['From'] = $from;
	}

/**
* Accessor to set the return path
*/
	function setReturnPath($return_path)
	{
		$this->return_path = $return_path;
	}

/**
* Disposition-Notification-To
* @param  mixed  $email
*/
	function setNotify($email)
	{
		$email = $this->getGoodEmail($email);
		$this->setHeader('Disposition-Notification-To',$email);
	}

/**
* Reply-to
* @param  mixed  $email
*/
	function setReply($email)
	{
		$email = $this->getGoodEmail($email);
		$this->setHeader('Reply-to',$email);
	}

/**
* Accessor to add a Cc: header
*/
	function setCc($email)
	{
		$email = $this->getGoodEmail($email);
		$this->setHeader('Cc',$email);
	}
	function appendCc($email){
		$email = $this->getGoodEmail($email);
		$cc = &$this->getHeader('Cc');
		if(strlen($cc)){
			$email = $cc .','. $email;
		}
		$this->setHeader('Cc',$email);
	}

/**
* Accessor to add a Bcc: header
*/
	function setBcc($email)
	{	
		$email = $this->getGoodEmail($email);
		$this->setHeader('Bcc',$email);
	}
	function appendBcc($email){
		$email = $this->getGoodEmail($email);
		$bcc = &$this->getHeader('Bcc');
		if(strlen($bcc)){
			$email = $bcc .','. $email;
		}
		$this->setHeader('Bcc',$email);
	}
/**
*	Get Good Email
*/
	function getGoodEmail($email){
		if(!is_array($email))$email=explode(',',$email);
		$email=array_map('trim',$email);
		$email=array_diff($email,array(''));
		$email=join(',',$email);
		return $email;
	}

/**
* Adds plain text. Use this function
* when NOT sending html email
*/
	function setText($text = '')
	{
		$this->text = $text;
	}

/**
* Adds a html part to the mail.
* Also replaces image names with
* content-id's.
*/
	function setHtml($html, $text = null, $images_dir = null)
	{
		$this->html      = $html;
		$this->html_text = $text;

		if (isset($images_dir)) {
			$this->_findHtmlImages($images_dir);
		}
	}

	function setReBuilder(){
		$this->is_built = false;
	}

/**
* Function for extracting images from
* html source. This function will look
* through the html code supplied by add_html()
* and find any file that ends in one of the
* extensions defined in $obj->image_types.
* If the file exists it will read it in and
* embed it, (not an attachment).
*
* @author Dan Allen
*/
	function _findHtmlImages($images_dir)
	{
		// Build the list of image extensions
		while (list($key,) = each($this->image_types)) {
			$extensions[] = $key;
		}

		preg_match_all('/(?:"|\')([^"\']+\.('.implode('|', $extensions).'))(?:"|\')/Ui', $this->html, $images);

		for ($i=0; $i<count($images[1]); $i++) {
			if (file_exists($images_dir . $images[1][$i])) {
				$html_images[] = $images[1][$i];
				$this->html = str_replace($images[1][$i], basename($images[1][$i]), $this->html);
			}
		}

		if (!empty($html_images)) {

			// If duplicate images are embedded, they may show up as attachments, so remove them.
			$html_images = array_unique($html_images);
			sort($html_images);
	
			for ($i=0; $i<count($html_images); $i++) {
				if ($image = $this->getFile($images_dir.$html_images[$i])) {
					$ext = substr($html_images[$i], strrpos($html_images[$i], '.') + 1);
					$content_type = $this->image_types[strtolower($ext)];
					$this->addHtmlImage($image, basename($html_images[$i]), $content_type);
				}
			}
		}
	}

/**
* Adds an image to the list of embedded
* images.
*/
	function addHtmlImage($file, $name='', $c_type='application/octet-stream')
	{
		$cid = md5(uniqid(time()));
		if(!strlen($name)) $name=$cid.'img.gif';
		$this->html_images[$name] = array(
										'body'   => $file,
										'name'   => $name,
										'c_type' => $c_type,
										'cid'    => $cid
									);
	}


/**
* Adds a file to the list of attachments.
*/
	function addAttachment($file, $name='', $c_type='application/octet-stream', $encoding = 'base64')
	{	
		if(!strlen($name))$name='att'.count($this->attachments);
		$this->attachments[$name] = array(
									'body'		=> $file,
									'name'		=> $name,
									'c_type'	=> $c_type,
									'encoding'	=> $encoding
								  );
	}

/**
* Clear list of attachments.
*/
	function ClearAttachments($key=null)
	{
		if(strlen($key)){
			unset($this->html_images[$key]);
			unset($this->attachments[$key]);
		}else{
			$this->html_images = array();
			$this->attachments = array();
		}
	}

/**
* Adds a text subpart to a mime_part object
*/
	function &_addTextPart(&$obj, $text)
	{
		$params['content_type'] = 'text/plain';
		$params['encoding']     = $this->build_params['text_encoding'];
		$params['charset']      = $this->build_params['text_charset'];
		if (is_object($obj)) {
			return $obj->addSubpart($text, $params);
		} else {
			return new Mail_mimePart($text, $params);
		}
	}

/**
* Adds a html subpart to a mime_part object
*/
	function &_addHtmlPart(&$obj)
	{
		$params['content_type'] = 'text/html';
		$params['encoding']     = $this->build_params['html_encoding'];
		$params['charset']      = $this->build_params['html_charset'];
		if (is_object($obj)) {
			return $obj->addSubpart($this->html, $params);
		} else {
			return new Mail_mimePart($this->html, $params);
		}
	}

/**
* Starts a message with a mixed part
*/
	function &_addMixedPart()
	{
		$params['content_type'] = 'multipart/mixed';
		return new Mail_mimePart('', $params);
	}

/**
* Adds an alternative part to a mime_part object
*/
	function &_addAlternativePart(&$obj)
	{
		$params['content_type'] = 'multipart/alternative';
		if (is_object($obj)) {
			return $obj->addSubpart('', $params);
		} else {
			return new Mail_mimePart('', $params);
		}
	}

/**
* Adds a html subpart to a mime_part object
*/
	function &_addRelatedPart(&$obj)
	{
		$params['content_type'] = 'multipart/related';
		if (is_object($obj)) {
			return $obj->addSubpart('', $params);
		} else {
			return new Mail_mimePart('', $params);
		}
	}

/**
* Adds an html image subpart to a mime_part object
*/
	function &_addHtmlImagePart(&$obj, $value)
	{
		$params['content_type'] = $value['c_type'];
		$params['encoding']     = 'base64';
		$params['disposition']  = 'inline';
		$params['dfilename']    = $value['name'];
		$params['cid']          = $value['cid'];
		$obj->addSubpart($value['body'], $params);
	}

/**
* Adds an attachment subpart to a mime_part object
*/
	function &_addAttachmentPart(&$obj, $value)
	{
		$params['content_type'] = $value['c_type'];
		$params['encoding']     = $value['encoding'];
		$params['disposition']  = 'attachment';
		$params['dfilename']    = $value['name'];
		$obj->addSubpart($value['body'], $params);
	}

/**
* Builds the multipart message from the
* list ($this->_parts). $params is an
* array of parameters that shape the building
* of the message. Currently supported are:
*
* $params['html_encoding'] - The type of encoding to use on html. Valid options are
*                            "7bit", "quoted-printable" or "base64" (all without quotes).
*                            7bit is EXPRESSLY NOT RECOMMENDED. Default is quoted-printable
* $params['text_encoding'] - The type of encoding to use on plain text Valid options are
*                            "7bit", "quoted-printable" or "base64" (all without quotes).
*                            Default is 7bit
* $params['text_wrap']     - The character count at which to wrap 7bit encoded data.
*                            Default this is 998.
* $params['html_charset']  - The character set to use for a html section.
*                            Default is ISO-8859-1
* $params['text_charset']  - The character set to use for a text section.
*                          - Default is ISO-8859-1
* $params['head_charset']  - The character set to use for header encoding should it be needed.
*                          - Default is ISO-8859-1
*/
	function buildMessage($params = array())
	{
		if (!empty($params)) {
			while (list($key, $value) = each($params)) {
				$this->build_params[$key] = $value;
			}
		}

		if (!empty($this->html_images)) {
			foreach ($this->html_images as $value) {
				$this->html = str_replace($value['name'], 'cid:'.$value['cid'], $this->html);
			}
		}

		$null        = null;
		$attachments = !empty($this->attachments) ? true : false;
		$html_images = !empty($this->html_images) ? true : false;
		$html        = !empty($this->html)        ? true : false;
		$text        = isset($this->text)         ? true : false;

		switch (true) {
			case $text AND !$attachments:
				$message = &$this->_addTextPart($null, $this->text);
				break;

			case !$text AND $attachments AND !$html:
				$message = &$this->_addMixedPart();
				foreach($this->attachments as $att){
					$this->_addAttachmentPart($message, $att);
				}
				break;

			case $text AND $attachments:
				$message = &$this->_addMixedPart();
				$this->_addTextPart($message, $this->text);
				foreach($this->attachments as $att){
					$this->_addAttachmentPart($message, $att);
				}
				break;

			case $html AND !$attachments AND !$html_images:
				if (!is_null($this->html_text)) {
					$message = &$this->_addAlternativePart($null);
					$this->_addTextPart($message, $this->html_text);
					$this->_addHtmlPart($message);
				} else {
					$message = &$this->_addHtmlPart($null);
				}
				break;

			case $html AND !$attachments AND $html_images:
				unset($rel);
				if (!is_null($this->html_text)) {
					$message = &$this->_addAlternativePart($null);
					$this->_addTextPart($message, $this->html_text);
					$rel = &$this->_addRelatedPart($message);
				} else {
					$message = &$this->_addRelatedPart($null);
					$rel = &$message;
				}
				$this->_addHtmlPart($rel);
				foreach($this->html_images as $img){
					$this->_addHtmlImagePart($rel, $img);
				}
				break;

			case $html AND $attachments AND !$html_images:
				$message = &$this->_addMixedPart();
				if (!is_null($this->html_text)) {
					$alt = &$this->_addAlternativePart($message);
					$this->_addTextPart($alt, $this->html_text);
					$this->_addHtmlPart($alt);
				} else {
					$this->_addHtmlPart($message);
				}
				foreach($this->attachments as $att){
					$this->_addAttachmentPart($message, $att);
				}
				break;

			case $html AND $attachments AND $html_images:
				unset($rel);
				$message = &$this->_addMixedPart();
				if (!is_null($this->html_text)) {
					$alt = &$this->_addAlternativePart($message);
					$this->_addTextPart($alt, $this->html_text);
					$rel = &$this->_addRelatedPart($alt);
				} else {
					$rel = &$this->_addRelatedPart($message);
				}
				$this->_addHtmlPart($rel);
				foreach($this->html_images as $img){
					$this->_addHtmlImagePart($rel, $img);
				}
				foreach($this->attachments as $att){
					$this->_addAttachmentPart($message, $att);
				}
				break;

		}

		if (isset($message)) {
			$output = $message->encode();
			$this->output   = $output['body'];
			$this->headers  = array_merge($this->headers, $output['headers']);

			// Add message ID header
			srand((double)microtime()*10000000);
			$message_id = sprintf('<%s.%s@%s>', base_convert(time(), 10, 36), base_convert(rand(), 10, 36), !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME']);
			$this->headers['Message-ID'] = $message_id;

			$this->is_built = true;
			return true;
		} else {
			return false;
		}
	}

/**
* Function to encode a header if necessary
* according to RFC2047
*/
/*
	function _encodeHeader($input, $charset = 'ISO-8859-1')
	{
		preg_match_all('/(\w*[\x80-\xFF]+\w*)/', $input, $matches);
		foreach ($matches[1] as $value) {
			$replacement = preg_replace('/([\x80-\xFF])/e', '"=" . strtoupper(dechex(ord("\1")))', $value);
			$input = str_replace($value, '=?' . $charset . '?Q?' . $replacement . '?=', $input);
		}
		
		return $input;
	}
*/	
	function _encodeHeader ($string,$default_charset='ISO-8859-1') {
      if (ereg("([\200-\377]|=\\?)", $string)) { // Encode only if the string contains 8-bit characters or =?
         
         // First the special characters
         $string = str_replace("=", "=3D", $string);
         $string = str_replace("?", "=3F", $string);
         $string = str_replace("_", "=5F", $string);
         $string = str_replace(" ", "_", $string);

		 for ( $ch = 127 ; $ch <= 255 ; $ch++ ) {
		    $replace = chr($ch);
		    $insert = sprintf("=%02X", $ch);
            $string = str_replace($replace, $insert, $string);
         }

         $newstring = '=?'.$default_charset.'?Q?'.$string.'?=';
         return $newstring;
      }

      return $string;
   }


/**
* Sends the mail.
*
* @param  array  $to
* @param  string $type OPTIONAL
* @return mixed
*/
	function send($to, $type = 'mail')
	{
		if(!empty($this->noSend)){
			trigger_error('noSend open mode : so mail not send!');
			return true;
		}

		if (!defined('CRLF')) {
			$this->setCrlf($type == 'mail' ? "\n" : "\r\n");
		}

		if (!$this->is_built) {
			$this->buildMessage();
		}

		$this->lastSendTo = $this->getGoodEmail($to);

		switch ($type) {
			case 'mail':
				$subject = '';
				if (!empty($this->headers['Subject'])) {
					$subject = $this->_encodeHeader($this->headers['Subject'], $this->build_params['head_charset']);
				}

				// Get flat representation of headers
				foreach ($this->headers as $name => $value) {
					if(strcmp('Subject',$name)==0)continue;
					$headers[] = $name . ': ' .$value;
				}

				if (!empty($this->return_path)) {
					$this->lastResult = mail($this->lastSendTo, $subject, $this->output, implode(CRLF, $headers), '-f' . $this->return_path);
				} else {
					$this->lastResult = mail($this->lastSendTo, $subject, $this->output, implode(CRLF, $headers));
				}
				break;

			case 'smtp':
				unset($this->errors);
				require_once(dirname(__FILE__) . '/smtp.php');
				require_once(dirname(__FILE__) . '/rfc822.php');
				
				// Parse recipients argument for internet addresses------Start
				$AllRecipients = array_merge(explode(',',$this->lastSendTo), explode(',',$this->headers['Cc']), explode(',',$this->headers['Bcc']));
				$AllRecipients = array_diff($AllRecipients,array(''));
				$AllRecipients = array_values(array_unique($AllRecipients));
				foreach ($AllRecipients as $recipient) {
					$addresses = Mail_RFC822::parseAddressList($recipient, $this->smtp_params['helo'], null, false);
					foreach ($addresses as $address) {
						$smtp_recipients[] = sprintf('%s@%s', $address->mailbox, $address->host);
					}
				}
				unset($AllRecipients); // These are reused
				unset($addresses); // These are reused
				unset($address);   // These are reused
				// Parse recipients argument for internet addresses------End

				// Get flat representation of headers, parsing
				// Cc and Bcc as we go
				foreach ($this->headers as $name => $value) {
					if ($name == 'Bcc') {
						continue;
					}
					if ($name != 'Subject'){
						$headers[] = $name . ': ' .$value;
					}else{
						$headers[] = $name . ': ' . $this->_encodeHeader($value, $this->build_params['head_charset']);
					}
				}
				// Add To header
				$headers[] = 'To: '. $this->lastSendTo;

				// Add headers to send_params
				$send_params['headers']    = $headers;
				$send_params['recipients'] = $smtp_recipients;
				$send_params['body']       = $this->output;

				// Setup return path
				if (isset($this->return_path)) {
					$send_params['from'] = $this->return_path;
				} elseif (!empty($this->headers['From'])) {
					$from = Mail_RFC822::parseAddressList($this->headers['From']);
					$send_params['from'] = sprintf('%s@%s', $from[0]->mailbox, $from[0]->host);
				} else {
					$send_params['from'] = 'postmaster@' . $this->smtp_params['helo'];
				}

				// Send it
				if(!count($this->smtpArr)){ //local smtp
					$smtp = &smtp::connect($this->smtp_params);
					if (!$smtp->send($send_params)) {
						$this->errors = $smtp->errors;
						$this->errors[] = $this->smtp_params;
					}
				}else{ //more smtp
					foreach($this->smtpArr as $k=>$v){
						$smtp = &smtp::connect($v);
						if (!$smtp->send($send_params)) {
							$this->errors[$k] = $smtp->errors;
							$this->errors[$k][] = $v;
						}else{
							unset($this->errors);
							break;
						}
					}
				}
				if(!empty($this->errors)){
					$this->errors = print_r($this->errors,true);
					$this->lastResult = false;
				}else{
					$this->lastResult = true;
				}
				break;

			default:
				trigger_error('error send type!');
				break;
		}
		if(is_object($this->log)){
			$this->log->save($this);
		}
		return $this->lastResult;
	}

/**
* Use this method to return the email
* in message/rfc822 format. Useful for
* adding an email to another email as
* an attachment. there's a commented
* out example in example.php.
*/
	function getRFC822($recipients, $type = 'mail')
	{
		// Make up the date header as according to RFC822
		$this->setHeader('Date', date('D, d M y H:i:s O'));

		if (!defined('CRLF')) {
			$this->setCrlf($type == 'mail' ? "\n" : "\r\n");
		}

		if (!$this->is_built) {
			$this->buildMessage();
		}

		// Return path ?
		if (isset($this->return_path)) {
			$headers[] = 'Return-Path: '. $this->return_path;
		}

		// Get flat representation of headers
		foreach ($this->headers as $name => $value) {
			$headers[] = $name . ': '. $value;
		}
		$headers[] = 'To: '. $this->getGoodEmail($recipients);
		return implode(CRLF, $headers) . CRLF . CRLF . $this->output;
	}
} // End of class.
?>
