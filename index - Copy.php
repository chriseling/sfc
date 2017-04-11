<?
	session_start();
	include "db.php";
	
	if($_POST['button'] == "Login") {
		if(!$_POST['email']) {
			$message = "email required.";
		} else if(!$_POST['password']) {
			$message = "password required.";
		} else {
			$sql = "select * from users where email = '$_POST[email]' and password = '".md5($_POST['password'])."'";
			$res = mysql_query($sql);
			if(mysql_num_rows($res)) {
				$mr = mysql_fetch_assoc($res);
				if(!$mr['active']) {
					$message = "please check your email to activate your account";
				} else {
					$_SESSION['user'] = $_POST['email'];
					$message = "login successful";
					header("Location:  http://starfleetcalc.com/");
					//$_GET['page'] = "battle";
				}
			} else {
				$message = "invalid login";
			}
		}
	}
	if($_GET['page'] == "logout") {
		isset($_SESSION['user']) ? session_unset($_SESSION['user']) : true;
		setcookie("user",false,time()-3600);
		setcookie("session",false,time()-3600);
	}
	
	//print_r($_SERVER);
	if($_SESSION['user']) {
		$user = $_SESSION['user'];
	} else {
		$user = session_id();
	}

	mysql_query("insert into counter values (now(),'$user','$_SERVER[REMOTE_ADDR]','$_SERVER[HTTP_REFERER]','$_SERVER[HTTP_USER_AGENT]')");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SFC Tools</title>
<link href="global.css" rel="stylesheet" type="text/css" />
<script src="js/prototype.js" type="text/javascript"></script>
<script src="js/scriptaculous.js" type="text/javascript"></script>
<?
	$gth = gmdate("H",time());
	$gtm = gmdate("i",time());
	$gts = gmdate("s",time());
?>
<script type="text/javascript">
		var seconds = 1000;
		var minutes = 60*seconds;
		var hours = 60*minutes;
		var fleet_started = false;
		
		var timers = {}; 
		function timeString(h, m, s) {
		 return h.toPaddedString(2) + ':' + m.toPaddedString(2) + ':' + s.toPaddedString(2);
		} 
		function makeTimer(id, seconds_from_start, seconds_until_end, callback) {
		if (!timers[id]) {
		timers[id] = { 'retries': 0 }
		}
		
		var start_time = new Date();
		start_time.setUTCHours(<?=$gth;?>,<?=$gtm;?>,<?=$gts;?>);
		start_time.setSeconds(start_time.getSeconds() - seconds_from_start);
		
		var end_time = new Date();
		end_time.setUTCHours(<?=$gth;?>,<?=$gtm;?>,<?=$gts;?>);
		end_time.setSeconds(end_time.getSeconds() + seconds_until_end);
		
		updateTimer(start_time, end_time, id, callback);
		}
		
		function makeTimer2(id, seconds_from_start, seconds_until_end, callback) {
		if (!timers[id]) {
		timers[id] = { 'retries': 0 }
		}
		
		var start_time = new Date();
		start_time.setSeconds(start_time.getSeconds() - seconds_from_start);
		
		var end_time = new Date();
		end_time.setSeconds(end_time.getSeconds() + seconds_until_end);
		
		updateTimer2(start_time, end_time, id, callback);
		}
		
		function updateTimer(start_time, end_time, id, callback) {
		var now = new Date();
		var t = end_time - now;
		
		progress = $(id + '_progress');
		percent = $(id + '_percent');
		countdown = $(id + '_countdown');
		timer_text = $(id + '_timer_text');
		
		if (!progress || !percent) {
		return;
		}
		
		if (t < 0) {
		if (timers[id]['retries'] == 0) {
		
		if(callback) {
		setTimeout(callback, 5000 * Math.pow(2, timers[id]['retries']));
		timers[id]['retries'] += 1
		timer_text.innerHTML = "updating...";
		}
		else {
		timer_text.innerHTML = "-";
		}
		
		} else {
		timer_text.innerHTML = "try again later";
		}
		
		//progress.setStyle({ width: "100%" });
		
		return;
		}
		
		var h = Math.floor(t / hours);
		t -= h*hours;
		
		var m = Math.floor(t / minutes);
		t -= m*minutes;
		
		var s = Math.floor(t / seconds);
		
		pct_completed = ((now - start_time) / (end_time - start_time))
		pct = Math.floor(100 * pct_completed) + "%";
		//progress.setStyle({ width: pct });
		percent.innerHTML = pct
		countdown.innerHTML = timeString(h, m, s);
		//countdown.innerHTML = timeString(now.getUTCHours(), now.getMinutes(), now.getSeconds());
		//update_speedup_cost(id, pct_completed);
		
		
		if (timers[id]['handle']) { clearTimeout(timers[id]['handle']); }
		timers[id]['handle'] = setTimeout(function() { updateTimer(start_time, end_time, id, callback) }, 1000);
		} 
		
		function updateTimer2(start_time, end_time, id, callback) {
		var now = new Date();
		var t = end_time - now;
		
		progress = $(id + '_progress');
		percent = $(id + '_percent');
		countdown = $(id + '_countdown');
		timer_text = $(id + '_timer_text');
		
		if (!progress || !percent) {
		return;
		}
		
		if (t < 0) {
		if (timers[id]['retries'] == 0) {
		
		if(callback) {
		setTimeout(callback, 5000 * Math.pow(2, timers[id]['retries']));
		timers[id]['retries'] += 1
		timer_text.innerHTML = "updating...";
		}
		else {
		timer_text.innerHTML = "-";
		}
		
		} else {
		timer_text.innerHTML = "try again later";
		}
		
		//progress.setStyle({ width: "100%" });
		
		return;
		}
		
		var h = Math.floor(t / hours);
		t -= h*hours;
		
		var m = Math.floor(t / minutes);
		t -= m*minutes;
		
		var s = Math.floor(t / seconds);
		
		pct_completed = ((now - start_time) / (end_time - start_time))
		pct = Math.floor(100 * pct_completed) + "%";
		//progress.setStyle({ width: pct });
		percent.innerHTML = pct
		//countdown.innerHTML = timeString(h, m, s);
		countdown.innerHTML = timeString(now.getUTCHours(), now.getMinutes(), now.getSeconds());
		//update_speedup_cost(id, pct_completed);
		
		if (timers[id]['handle']) { clearTimeout(timers[id]['handle']); }
		timers[id]['handle'] = setTimeout(function() { updateTimer2(start_time, end_time, id, callback) }, 1000);
		} 
</script>


</head>
<body style="font-family: calibri, Georgia, 'Times New Roman', Times, serif; font-size:13px;">
	<div id="container" style="width:1000px; padding-left:20px;">
	<div id="header" style="clear:both;">
  	
	<ul id="nav">
  	<li><a href="?page=battle">battle simulator</a></li>
  	<li><a href="?page=arrival">arrival calculator</a></li>
    <li><a href="?page=flight">flight calculator</a></li>
    <? 
		$trusted = array('babam@fu4ever.com','monstergtguy@hotmail.com','sparks@fu4ever.com','oos@fu4ever.com','spotwhitman@gmail.com','zacharykeokihong@fu4ever.com');
		$super_trusted = array('babam@fu4ever.com','spotwhitman@gmail.com','zacharykeokihong@fu4ever.com');
		
		
		if(in_array($_SESSION['user'],$trusted)) { ?>
    <li><a href="?page=search">galaxy search</a></li>
    <? } 
    if(in_array($_SESSION['user'],$super_trusted)) { ?>
    <li><a href="?page=fish">fish finder</a></li>
    <? } if($_SESSION['user']) {?>
  	<li><a href="?page=logout">logout (<?=$_SESSION['user'];?>)</a></li> 
    <? } else {?>
   	<li><a href="?page=login">login / register</a></li> 
    <? } ?>
    <li><a href="mailto:info@starfleetcalc.com">contact us (info@starfleetcalc.com)</a></li>    
  </ul>
  <div style="float:right; width:200px;">
		  <p><strong>Live Game Clock</strong></p>
        <p>
          <div id='1000110051371' class='js_timer'>
            <div id='1000110051371_bar' class='bar'>            
              <div id='1000110051371_progress' class='progress' style=' display:none;'></div>
              <div id='1000110051371_timer_text' class='timer_text'>
                <span id='1000110051371_percent' class='percent' style=" display:none;"></span>
                <span class='percent'></span><span id='1000110051371_countdown' style="font-size:36px;" class='countdown'>
                04:17:13
                </span><span class='percent'></span>
                <br />
              </div>
            </div>
          </div>
        <script type='text/javascript'>
          makeTimer2('1000110051371', 129, 15433, null);
        </script></p>
       </div>
	</div>
  
  <div style="clear:both;"></div><br />
  Welcome to starfleetcalc.com, online calculator tools for Starfleet Commander. All data is erased when you close your browser window.  Register for a free account if you wish to save your  data.<br />
  <br />
  <script type="text/javascript"><!--
google_ad_client = "ca-pub-7001290489646619";
/* SFC */
google_ad_slot = "2251171075";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>

  <span style="color:#F00;">
<?=$message;?></span>
  <br />




  <?
		if(!$_GET['page']) {
			$_GET['page'] = "battle";
		}
		if(file_exists("$_GET[page].php")) {
			include "$_GET[page].php";
		}

	?>
  <script type="text/javascript"><!--
google_ad_client = "ca-pub-7001290489646619";
/* SFC2 */
google_ad_slot = "4887901021";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-23086454-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>
