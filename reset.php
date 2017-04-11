<?
	//print_r($_POST);
	if($_POST['button'] == "Save") {
		if(!$_POST['password']) {
			$message = "a new password is required.";
		} else {
			$sql = "update users set password = '".md5($_POST['password'])."' where email = '".base64_decode($_POST['id'])."' and password = '$_POST[code]'";
			$res = mysql_query($sql);
			if(mysql_affected_rows()) {
				$message = "your password has been reset, please <a href=\"?page=login\">log in</a>.";
			} else {
				$message = "there was a problem, please contact <a href=\"mailto:info@starfleetcalc.com\">info@starfleetcalc.com</a>";
			}
		}
	}
?>
<form id="form1" name="form1" method="post" action="">
	<input type="hidden" name="code" value="<?=$_GET['code'];?>" />
  <input type="hidden" name="id" value="<?=$_GET['id'];?>" />
  <table>
    <tr>
      <td>new password</td>
      <td><label>
        <input type="password" name="password" id="password" />
      </label></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><label>
        <input type="submit" name="button" id="button" value="Save" />
      </label><br />
<span style="color:#F00;"><?=$message;?></span></td>
    </tr>
  </table>
</form>
