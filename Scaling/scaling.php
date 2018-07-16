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
$phone = $items['phone'];
$gender = $items['gender'];
$width = $items['width'];
$status = $items['task_status'];
$enable = $items['enable'];
if(is_null($session_id) or strcmp($enable,"0") == 0|| is_null($phone))
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
if(strcmp($status,Constants::SCALING_NUM) != 0)
{
	$link = $myDBi->where_to_link($status);
	if(!empty($_POST))
		echo $link;
	else
		header('Location:'.$link);
	return;
}
if(!empty($_POST))
{
	$status = Constants::FAMOUS_NUM;
	$width = (floatval($_POST['width'])/8.5)*6;
	$sql_link->query("UPDATE `subjects` SET `width`='$width',`task_status`='$status' WHERE `session_id`='$session_cookie'");
	echo Constants::famous_page;
	return;
}
?>
<!-- scaling as anchor page -->
<!DOCTYPE html>
	<html lang="he" dir="rtl">
		<head>
			<meta http-equiv="Content-Type" content="text/html"/>
			<meta charset="utf-8"/>
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
			<meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=yes"/>
			<meta name="HandheldFriendly" content="true"/>
			<meta name="MobileOptimized" content="320"/>
			<meta name="description" content="scale the screen pixels to correspond this to our pixel's size aim"/>
			<meta name="Itay_Guy" content="B.Sc Student project in University Of Haifa - 2017"/>
			<link id="csslink" href="/recognitions/dev/style.css" rel="stylesheet"/>
			<link id="csslink" href="/recognitions/dev/scaling.css" rel="stylesheet"/>
			<title>Screen Scaling</title>
				<style type="text/css"></style>
			<script type="text/javascript" src="/recognitions/dev/scaling.js"></script>
			<script type="text/javascript">
				window.onload = function(){
					Functions.drawLine();
					document.addEventListener("keydown",Functions.changeCanvasWidth);
				};
			</script>
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
									<tr>
										<td>
											<h1>תהליך כיול: </h1>
											<h2>מטרת תהליך זה הינה לאפשר התאמה בין התמונות שיוצגו לבין המסך שמציג אותן.</h2>
											<?if(strcmp($gender,"בן") == 0){?>
												<h3><strong>נא התאם את הקו התחתון לרוחב של כרטיס (אשראי, רשיון רכב וכו'...) </strong></h3>
											<?}else if(strcmp($gender,"בת") == 0){?>
												<h3><strong>נא התאימי את הקו התחתון לרוחב של כרטיס (אשראי, רשיון רכב וכו'...) </h3>
											<?}?>
											<div style="padding:5em;">
												<h3>הגדלה = <img alt="חץ למעלה" src="/recognitions/dev/arrowtop.png" width="20" height="20"/></h3>
												<h3>הקטנה = <img alt="חץ למטה" src="/recognitions/dev/arrowdown.png" width="20" height="20"/></h3>
											</td>
										</tr>
										<tr>
											<td class='line_in_middle'>
												<canvas class='style' id='line' width='300' height='15'></canvas>
											</td>
										</tr>
										<tr>
											<td class='height_to_canvas'>
												<input type='button' id='inp_finish' value='סיים' onclick='Form.ajax();'/>
												<br>
											</td>
										</tr>
										</div>
									<tr>
										<td>
											<hr>
											<h4>הערה: נא להשאר עם אותו המסך לאורך כל תהליך הניסוי.</h4>
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