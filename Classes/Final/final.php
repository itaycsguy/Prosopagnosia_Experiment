<?php
require_once("classes.php");
$myDBi = new DBi();
$sql_link = $myDBi->connect2sqli();
if(!isset($_COOKIE["subject_session"]))
{
	if(!empty($_POST))
		echo Constants::index_page;
	else
		header('Location:'.Constants::index_page);
	return;
}
$session_cookie = $_COOKIE["subject_session"];
$vec = $sql_link->query("SELECT * FROM `subjects` WHERE `session_id`='$session_cookie'");
$items = $vec->fetch_array(MYSQLI_ASSOC);
$session_id = $items['session_id'];
$task = $items['task_status'];
$enable = $items['enable'];
if(is_null($session_id) or strcmp($enable,"0") == 0)
{
	if(!empty($_POST))
		echo Constants::index_page;
	else
		header('Location:'.Constants::index_page);
	return;
}
if(strcmp($session_cookie,$session_id)!= 0)
{
	if(!empty($_POST))
		echo Constants::index_page;
	else
		header('Location:'.Constants::index_page);
	return;
}
if(strcmp($task,Constants::FINAL_NUM) != 0)
{
	$link = $myDBi->where_to_link($task);
	if(!empty($_POST))
		echo $link;
	else
		header('Location:'.$link);
	return;
}
$sql_link->query("UPDATE  `subjects` SET `enable`='0' WHERE `session_id`='$session_cookie'");
$gender = $items['gender'];
?>
<!-- final as anchor page -->
<!DOCTYPE html>
	<html lang="he" dir="rtl">
		<head>
			<meta http-equiv="Content-Type" content="text/html"/>
			<meta charset="utf-8"/>
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
			<meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=yes"/>
			<meta name="HandheldFriendly" content="true"/>
			<meta name="MobileOptimized" content="320"/>
			<meta name="description" content="final page,Thanks"/>
			<meta name="Itay_Guy" content="B.Sc Student project in University Of Haifa - 2017"/>
			<link href="/recognitions/dev/style.css" rel="stylesheet"/>
			<link href="/recognitions/dev/final.css" rel="stylesheet"/>
			<title>Final</title>
				<style type="text/css"></style>
			<script type="text/javascript">
				function redirect() {
					window.location = "./index.php";
				}
			</script>
		</head>
			<body>
				<div id="layout-top"></div>
				<div id="layout-middle">
					<div id="layout-main">
						<div class="layout-center-0">
							<table>
								<tr>
									<td>
										<div class="transfer_place">
											<input id="inp_to_edit" type="button" value="עריכת פרטים אישיים"/>
										</div>
									</td>
									<td id="myProgress">
										<div id="myBar"></div>
									</td>
									<td>
										<div class="transfer_place">
											<input id="inp_to_edit" type="hidden"/>
										</div>
									</td>
								</tr>
							</table>
						<div class="layout-center-1">
						<div class="layout-center-2">
						<div class="layout-center-3">
						<div id="section" style='margin:3em 2em 2em 2em;'>
							<table id="table">
								<tr>
									<td class="position" colspan="2">
										<h1 class="h1_style"><strong id="putThanks">המבחן הסתיים.</strong></h1>
										<h1 class="h1_style"><strong id="putThanks">תודה על השתתפותך בניסוי!</strong></h1>
										<img src="/recognitions/dev/end_smile.jpg" style="padding: 0 0 0 30%;" width="150px" height="150px"/>
									</td>
								</tr>
								<tr>
									<td>
										<input type="button" id="inp_to_out" value="לחץ בכדי לצאת" onclick="redirect();"/>
									</td>
								</tr>
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