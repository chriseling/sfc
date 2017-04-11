<?

	include "db.php";

	$sql = "insert into fleet values ";
	if($_POST['button']) {
		$file = split("\n",$_POST['textarea']);
		foreach($file as $line) {
			if(preg_match("/(\d+)\t(.+)\t(.+)\t(.+)/",$line,$matches)) {
				$fleet = preg_replace("/,/","",$matches[4]);
				$sql .= "('$matches[1]','".mysql_real_escape_string($matches[2])."','".mysql_real_escape_string($matches[3])."','$fleet',now()),";
			}
		}
		mysql_query(rtrim($sql,","));
		echo mysql_error();
	}



?>

<form name="form1" method="post" action="">
  <label for="textarea"></label>
  <textarea name="textarea" id="textarea" cols="45" rows="5"></textarea>
  <input type="submit" name="button" id="button" value="Submit">
</form>
