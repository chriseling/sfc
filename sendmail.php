<?php
/**
* Send mail
*/

/**
* include
*/
include_once('mail/html.mime.mail.php');
include_once('class.validation.php');

/**
* global
*/
$sendEmail = &new HtmlMimeMail();
$sendEmail->setReturnPath('info@starfleetcalc.com');
$sendEmail->setSMTPParams('localhost');
$vn = &new Validation(); //class.validation.php

/**
* run

fields

user, email, subject, msg

*/
$mail = array();
$mail['form'] = ( $vn->checkEmailByReg($_REQUEST['email']) )? $_REQUEST['email'] : 'none';
$mail['from'] = '"'.$_REQUEST['user'].'"<'.$mail['form'].'>';
$mail['subject'] = 'starfleetcalc.com activation';

$mail['body'] = "welcome to starfleetcalc.com, please click here to activate your email:
								
http://starfleetcalc.com/activate.php?code=".base64_encode($_POST['id']);

$mail['to'] = $_POST['email'];

$sendEmail->setFrom($mail['from']);
$sendEmail->setSubject($mail['subject']);
$sendEmail->setText($mail['body']);
$result = $sendEmail->send(array($mail['to']),'smtp');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>FARALLON</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</title>
<script language="javascript">
	
	<?php
		if($result){
			echo 'alert("Message Sent");';
		}else{
			echo 'alert("Message Failed");';
		}
	?>
	history.go(-1);
</script>
</head>
</html>
