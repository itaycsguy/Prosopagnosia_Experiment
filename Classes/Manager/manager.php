<?php
require_once("classes.php");
$myDBi = new DBi();
$sql_link = $myDBi->connect2sqli();
$res = $sql_link->query("SELECT * FROM `manager`");
$myRes = $res->fetch_array(MYSQLI_ASSOC);

if(is_null($myRes))
{
	echo "err";
	return;
}
$name = $myRes['name'];
$gender = $myRes['gender'];
$correct_password = $myRes['password'];
$last_up_time = $myRes['last_up_time'];
$session_cookie = $myRes['session'];
$connected = $myRes['connected'];
$arr = array("ok,",$name.",",$gender);
$arr = implode($arr);

$need_new_content = false;
if(isset($_COOKIE["manager_session"]) and strcmp($_COOKIE["manager_session"],$session_cookie) == 0 and strcmp($connected,"1") == 0)
{
	$need_new_content = true;
}

// print_r($_POST);
if(!empty($_POST))
{
	if(isset($_POST['pass']))
	{
		$password = $_POST['pass'];
		if(strcmp($password,$correct_password) == 0)
		{
			$time = date("h:i:sa");
			$date = date("d.m.y");
			$aDay = 86400*30;
			$session = md5($password+time());
			setcookie("manager_session",$session,time()+$aDay);
			$sql_link->query("UPDATE `manager` SET `last_up_time`='$time',`last_up_date`='$date',`session`='$session',`connected`='1'");
			echo $arr;
			return;
		}
		echo "p_err";
		return;
	}
	else if(isset($_POST['new']))
	{
		if($_POST['new'] == "")
		{
			echo "empty";
			return;
		}
		$new_phone = $_POST['new'];
		$res = $sql_link->query("SELECT * FROM `subjects` WHERE `phone`='$new_phone'");
		$client = $res->fetch_array(MYSQLI_ASSOC);
		$client_phone = $client['phone'];
		if(is_null($client_phone))
		{
			echo "not_ex";
			return;
		}
		$sql_link->query("INSERT INTO `subjects` (`phone`,`enable`) VALUES ('$new_phone','1')");
		echo "inserted";
		return;
	}
	else if(isset($_POST['rem']))
	{
		if($_POST['rem'] == "")
		{
			echo "empty";
			return;
		}
		$rem_phone = $_POST['rem'];
		$res = $sql_link->query("SELECT * FROM `subjects` WHERE `phone`='$rem_phone'");
		$client = $res->fetch_array(MYSQLI_ASSOC);
		$client_phone = $client['phone'];
		if(is_null($client_phone))
		{
			echo "not_ex";
			return;
		}
		$sql_link->query("DELETE FROM `subjects` WHERE `phone`='$rem_phone'");
		$sql_link->query("DELETE FROM `questions` WHERE `phone`='$rem_phone'");
		$sql_link->query("DELETE FROM `permutations` WHERE `phone`='$rem_phone'");
		$sql_link->query("DELETE FROM `famous` WHERE `phone`='$rem_phone'");
		$sql_link->query("DELETE FROM `similarity` WHERE `phone`='$rem_phone'");
		$sql_link->query("DELETE FROM `local` WHERE `phone`='$rem_phone'");
		$sql_link->query("DELETE FROM `global` WHERE `phone`='$rem_phone'");
		$sql_link->query("DELETE FROM `expressions` WHERE `phone`='$rem_phone'");
		
		$sql_link->query("DELETE FROM `test5_type1` WHERE `subject_id`='$rem_phone'");
		$sql_link->query("DELETE FROM `test5_type2` WHERE `subject_id`='$rem_phone'");
		$sql_link->query("DELETE FROM `test5_type3` WHERE `subject_id`='$rem_phone'");
		$sql_link->query("DELETE FROM `test6_type1` WHERE `subject_id`='$rem_phone'");
		$sql_link->query("DELETE FROM `test6_type2` WHERE `subject_id`='$rem_phone'");
		$sql_link->query("DELETE FROM `test6_type3` WHERE `subject_id`='$rem_phone'");
		echo "deleted";
		return;
	}
	else if(isset($_POST['reset']))
	{
		if($_POST['reset'] == "")
		{
			echo "empty";
			return;
		}
		$reset_phone = $_POST['reset'];
		$res = $sql_link->query("SELECT * FROM `subjects` WHERE `phone`='$reset_phone'");
		$client = $res->fetch_array(MYSQLI_ASSOC);
		$client_phone = $client['phone'];
		if(is_null($client_phone))
		{
			echo "not_ex";
			return;
		}
		$res = $sql_link->query("SELECT * FROM `subjects` WHERE `phone`='$reset_phone'");
		$vec = $res->fetch_array(MYSQLI_ASSOC);
		$his_name = $vec['name'];
		$his_gender = $vec['gender'];
		$his_hand = $vec['hand'];
		$his_age = $vec['age'];
		$his_width = $vec['width'];
		$his_p_name = $vec['parent_name'];
		$his_comm = $vec['conmmunication'];
		$his_time = $vec['time'];
		$his_date = $vec['date'];
		$sql_link->query("DELETE FROM `subjects` WHERE `phone`='$reset_phone'");
		$sql_link->query("DELETE FROM `questions` WHERE `phone`='$reset_phone'");
		$sql_link->query("DELETE FROM `permutations` WHERE `phone`='$reset_phone'");
		$sql_link->query("DELETE FROM `famous` WHERE `phone`='$reset_phone'");
		$sql_link->query("DELETE FROM `similarity` WHERE `phone`='$reset_phone'");
		$sql_link->query("DELETE FROM `local` WHERE `phone`='$reset_phone'");
		$sql_link->query("DELETE FROM `global` WHERE `phone`='$reset_phone'");
		$sql_link->query("DELETE FROM `expressions` WHERE `phone`='$reset_phone'");
		
		$sql_link->query("DELETE FROM `test5_type1` WHERE `subject_id`='$reset_phone'");
		$sql_link->query("DELETE FROM `test5_type2` WHERE `subject_id`='$reset_phone'");
		$sql_link->query("DELETE FROM `test5_type3` WHERE `subject_id`='$reset_phone'");
		$sql_link->query("DELETE FROM `test6_type1` WHERE `subject_id`='$reset_phone'");
		$sql_link->query("DELETE FROM `test6_type2` WHERE `subject_id`='$reset_phone'");
		$sql_link->query("DELETE FROM `test6_type3` WHERE `subject_id`='$reset_phone'");
		$sql_link->query("INSERT INTO `subjects` (`phone`) VALUES ('$reset_phone')");
		$sql_link->query("UPDATE `subjects` SET `name`='$his_name',`age`='$his_age',`gender`='$his_gender',`hand`='$his_hand',`width`='$his_width',`parent_name`='$his_p_name',`communication`='$his_comm',`time`='$his_time',`date`='$his_date',`enable`='1' WHERE `phone`='$reset_phone'");
		echo "full_reseted";
		return;
	}
	else if(isset($_POST['clear']))
	{
		$res = $sql_link->query("SELECT * FROM `subjects`");
		$client = $res->fetch_array(MYSQLI_ASSOC);
		$client_phone = $client['phone'];
		if(is_null($client_phone))
		{
			echo "not_ex";
			return;
		}
		$sql_link->query("DELETE FROM `subjects`");
		$sql_link->query("DELETE FROM `questions`");
		$sql_link->query("DELETE FROM `permutations`");
		$sql_link->query("DELETE FROM `famous`");
		$sql_link->query("DELETE FROM `similarity`");
		$sql_link->query("DELETE FROM `local`");
		$sql_link->query("DELETE FROM `global`");
		$sql_link->query("DELETE FROM `expressions`");
		
		$sql_link->query("DELETE FROM `test5_type1`");
		$sql_link->query("DELETE FROM `test5_type2`");
		$sql_link->query("DELETE FROM `test5_type3`");
		$sql_link->query("DELETE FROM `test6_type1`");
		$sql_link->query("DELETE FROM `test6_type2`");
		$sql_link->query("DELETE FROM `test6_type3`");
		echo "cleared";
	}
	else if(isset($_POST['exist']))
	{
		if($_POST['exist'] == "")
		{
			echo "empty";
			return;
		}
		$exist_phone = $_POST['exist'];
		$res = $sql_link->query("SELECT * FROM `subjects` WHERE `phone`='$exist_phone'");
		$client = $res->fetch_array(MYSQLI_ASSOC);
		$client_phone = $client['phone'];
		if(is_null($client_phone))
		{
			echo "not_ex";
			return;
		}
		echo "ex";
		return;
	}
	else if(isset($_POST['disconnect']))
	{
		if($_POST['disconnect'] == "")
		{
			echo "empty";
			return;
		}
		$sql_link->query("UPDATE `manager` SET `connected`='0'");
		echo "disconnected";
		return;
	}
	return;
}
?>
<!-- manager as anchor page -->
<!DOCTYPE html>
	<html lang="he" dir="rtl">
		<head>
			<meta http-equiv="Content-Type" content="text/html"/>
			<meta charset="utf-8"/>
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
			<meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=yes"/>
			<meta name="HandheldFriendly" content="true"/>
			<meta name="MobileOptimized" content="320"/>
			<meta name="description" content="patient details"/>
			<meta name="Itay_Guy" content="B.Sc Student project in University Of Haifa - 2017"/>
			<link id="csslink" href="/recognitions/dev/style.css" rel="stylesheet"/>
			<link id="csslink" href="/recognitions/dev/manager.css" rel="stylesheet"/>
			<title>Manager</title>
				<style type="text/css"></style>
			<script type="text/javascript" src="/recognitions/dev/manager.js"></script>
		</head>
			<body>
				<div id="layout-top"></div>
				<div id="layout-middle">
					<div id="layout-main">
						<div class="layout-center-0">
						<div class="layout-center-1">
						<div class="layout-center-2">
						<div class="layout-center-3">
							<div id="section">
								<table id="table">
									<?if($need_new_content == true){?>
										<script type='text/javascript'>
											Functions.name = "<? echo $name; ?>";
											Functions.gender = "<? echo $gender; ?>";
											Functions.changeContent();
										</script>
									<?}else{?>
									<tr>
										<td>
											<h1 class="h1_style">ברוכים הבאים לממשק הניהול של מעבדת חינוך מיוחד:</h1>
										</td>
									</tr>
									<br><br>
									<tr>
										<td>
											<h3 class="h3_style">כניסה: <input type="password" autocomplete='off' placeholder='סיסמת ניהול' id="inp" name="connect"/></h3>
											<script type="text/javascript">
												document.addEventListener("keydown",Functions.manage);
											</script>
										</td>
									</tr>
									<?}?>
								</table><!-- .end outter table -->
							</div><!-- .end outter div section -->
						</div><!-- .layout-center-3 -->
						</div><!-- .layout-center-2 -->
						</div><!-- .layout-center-1 -->
						</div><!-- .layout-center-0 -->
					</div><!-- #layout-main -->
				</div><!-- #layout-middle -->
				<div id="layout-bottom"></div>
				<script type="text/javascript"></script>
			</body></html>