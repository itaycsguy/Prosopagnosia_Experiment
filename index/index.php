<?php
require_once("classes.php");
if(!empty($_POST))
{
	$password = $_POST['pw'];
	$myDBi = new DBi();
	$sql_link = $myDBi->connect2sqli();
	$vec = $sql_link->query("SELECT * FROM `subjects` WHERE `phone`='$password'");
	$passwordObj = $vec->fetch_array(MYSQLI_ASSOC);
	$correctPassword = $passwordObj['phone'];
	$status = $passwordObj['task_status'];
	$enable = $passwordObj['enable'];
	if(is_null($correctPassword))
	{
		echo "ex_err";
		return;
	}
	if(strcmp($password,$correctPassword) == 0 and strcmp($enable,"1") == 0)
	{
		$aDay = 86400*30;
		$value = md5($password+time());
		setcookie("subject_session",$value,time()+$aDay);
		$sql_link->query("UPDATE `subjects` SET `session_id`='$value',`enable`='1' WHERE `phone`='$password'");
		if(count($status) == 0 || strcmp($status,"") == 0 || strcmp($status,"0") == 0)
		{
			$status = Constants::DETAILS_NUM;
			$sql_link->query("UPDATE `subjects` SET `task_status`='$status' WHERE `phone`='$password'");
			echo Constants::details_page;
		}
		else
		{
			$arr = $myDBi->where_to_link($status);
			$sql_link->query("UPDATE `subjects` SET `session_id`='$value',`task_status`='$status' WHERE `phone`='$password'");
			echo $arr;
		}	
		return;
	}
	else if(strcmp($enable,"1") != 0)
	{
		echo "blocked";
		return;
	}
	echo "err";
	return;
}
?>
<!-- login as anchor page -->
<!DOCTYPE html>
	<html lang="he" dir="rtl">
		<head>
			<meta http-equiv="Content-Type" content="text/html"/>
			<meta charset="utf-8"/>
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
			<meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=yes"/>
			<meta name="HandheldFriendly" content="true"/>
			<meta name="MobileOptimized" content="320"/>
			<meta name="description" content=""/>
			<meta name="Itay_Guy" content=""/>
			<link id="csslink" href="/recognitions/dev/style.css" rel="stylesheet"/>
			<link id="csslink" href="/recognitions/dev/login.css" rel="stylesheet"/>
			<title>Login</title>
				<style type="text/css"></style>
				<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
				<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
				<script type="text/javascript" src="/recognitions/dev/login.js"></script>
		</head>
		<body>
		<!--<div id="layout-top"></div>-->
		<div id="main">
		<div id="main-center-cell" style="box-shadow: inset 0 0 0 1em #808080;">
			<div id="section">
			<table id="table">
				<!--
				<div id="main">
				<div id="main-center-cell">
				-->
				<div id="login-cont">
					<div class="pw-title">אין חיבור. נידרשת סיסמא :</div>
					<div class="pw-input-cont table">
						<div class="table-row">
							<div class="table-cell pw-textarea-cont">
								<input id="pw-textarea" type="password" maxlength="100" autofocus="true" autocomplete="off" placeholder="הכנס סיסמא כאן"/>
							</div>
						</div>
					<div class="table-row">
					<div class="table-cell pw-send-cont">
						<input id="pw-send" type="button" value="כניסה"/></div>
					</div>
					</div>
				</div>
			</table><!-- .end outter table -->
			</div><!-- .end section -->
		</div><!-- .end main-center-cell -->
	</div><!-- .end main -->
	<!--<div id="layout-bottom"></div>-->
	<script type="text/javascript" src="/recognitions/dev/details.js"></script>
	<script type="text/javascript"></script>
	</body>
	</html>