<?
	$sql = "update users set active = 1 where email = '".base64_decode($_GET['code'])."'";
	mysql_query($sql);
	//echo $sql;
?>
<span style="color:#F00;">your account has been activated, please log in.</span>