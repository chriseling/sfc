<?
	//print_r($_POST);
	
	//include_once('mail/html.mime.mail.php');
	include_once('class.validation.php');
	
	
	if($_POST['button2'] == "Register") {
		if(!$_POST['email']) {
			$message = "email required.";
		} else if(!$_POST['password']) {
			$message = "password required.";
		} else {
			$sql = "select * from users where email = '$_POST[email]'";
			$res = mysql_query($sql);
			if(mysql_num_rows($res)) {
				$message = "email address already registered, try resetting your password";
			} else {
				$sql = "insert into users values ('','$_POST[email]','".md5($_POST['password'])."',now(),0)";
				$res = mysql_query($sql);
				$message = "your account has been created, please check your email to activate your account";
				//$sendEmail = new HtmlMimeMail();
				//$sendEmail->setReturnPath('info@starfleetcalc.com');
				//$sendEmail->setSMTPParams('localhost');
				$vn = new Validation(); //class.validation.php
		
				$mail = array();
				$mail['form'] = ( $vn->checkEmailByReg($_REQUEST['email']) )? $_REQUEST['email'] : 'none';
				$mail['from'] = "info@starfleetcalc.com";
				$mail['subject'] = 'starfleetcalc.com activation';
				
				$mail['body'] = "welcome to starfleetcalc.com, please click here to activate your email:\n\nhttp://starfleetcalc.com/?page=activate&code=".base64_encode($_POST['email']);
				
				$mail['to'] = $_POST['email'];
				
				//$sendEmail->setFrom($mail['from']);
				//$sendEmail->setSubject($mail['subject']);
				//$sendEmail->setText($mail['body']);
				//$result = $sendEmail->send(array($mail['to']),'smtp');
				mail($mail['to'],$mail['subject'],$mail['body'],"From:  info@starfleetcalc.com");
			}
		}
	}
	
	if($_POST['button3'] == "Forgot My Password") {
		if(!$_POST['email']) {
			$message = "email required.";
		} else {
			$message = "please check your email to reset your account password.";
			//$sendEmail = new HtmlMimeMail();
			//$sendEmail->setReturnPath('info@starfleetcalc.com');
			//$sendEmail->setSMTPParams('localhost');
			$vn = new Validation(); //class.validation.php
	
			$mail = array();
			$mail['form'] = ( $vn->checkEmailByReg($_REQUEST['email']) )? $_REQUEST['email'] : 'none';
			$mail['from'] = "info@starfleetcalc.com";
			$mail['subject'] = 'starfleetcalc.com password reset';
			$sql = "select * from users where email = '$_POST[email]'";
			//echo $sql;
			$res = mysql_query($sql);
			$mr = mysql_fetch_assoc($res);
			$mail['body'] = "click here to reset your password:\n\nhttp://starfleetcalc.com/?page=reset&id=".base64_encode($_POST['email'])."&code=".$mr['password'];
			
			$mail['to'] = $_POST['email'].",info@starfleetcalc.com";
			
			//$sendEmail->setFrom($mail['from']);
			//$sendEmail->setSubject($mail['subject']);
			//$sendEmail->setText($mail['body']);
			//$result = $sendEmail->send(array($mail['to']),'smtp');
			mail($mail['to'],$mail['subject'],$mail['body'],"From:  info@starfleetcalc.com");
		}
	}
?><form name="form1" method="post" action="/?page=login">
  <table>
    <tr>
      <td>email</td>
      <td><label>
        <input type="text" name="email" id="email">
      </label></td>
    </tr>
    <tr>
      <td>password</td>
      <td><input type="password" name="password" id="password"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="button" id="button" value="Login">
      <input type="submit" name="button2" id="button2" value="Register">
      <input type="submit" name="button3" id="button3" value="Forgot My Password"><br><span style="color:#F00;">
<?=$message;?></span></td>
    </tr>
  </table>
</form>
