<?php
require_once("classes.php");
$myDBi = new DBi();
$sql_link = $myDBi->connect2sqli();
if(!isset($_COOKIE["subject_session"])) {
	header('Location:'.Constants::index_page);
	return;
}
$status = Constants::GLOBAL_NUM;
$session_cookie = $_COOKIE["subject_session"];
$vec = $sql_link->query("SELECT * FROM `subjects` WHERE `session_id`='$session_cookie'");
$items = $vec->fetch_array(MYSQLI_ASSOC);
$session_id = $items['session_id'];
$phone = $items['phone'];
$gender = $items['gender'];
$hand = $items['hand'];
$width = $items['width'];
$task = $items['task_status'];
$after_ins = $items['after_ins_faces'];
$curr_type = $items['faces_type'];
$enable = $items['enable'];
$need_new_content = false;
$faces_vectors = $myDBi->make_faces_vectors();
$index_1 = 0;
$index_2 = 0;
$index_3 = 0;
if(is_null($session_id) or strcmp($enable,"0") == 0
	or is_null($phone) or is_null($gender)
	or is_null($hand) or is_null($width)) {
	header('Location:'.Constants::index_page);
	return;
}
if(strcmp($session_cookie,$session_id)!= 0) {
	header('Location:'.Constants::index_page);
	return;
}
if(strcmp($task,Constants::FACES_ANGLES_NUM) != 0) {
	$link = $myDBi->where_to_link($task);
	if(!empty($_POST))
		echo $link;
	else
		header('Location:'.$link);
	return;
}
if(strcmp($after_ins,"1") == 0) {
	$need_new_content = true;
	$shoes1 = $sql_link->query("SELECT MAX(next_index) AS max FROM `faces_1` WHERE `phone`='$phone'");
	$shoes1_vec = $shoes1->fetch_array(MYSQLI_ASSOC);
	$shoes2 = $sql_link->query("SELECT MAX(next_index) AS max FROM `faces_2` WHERE `phone`='$phone'");
	$shoes2_vec = $shoes2->fetch_array(MYSQLI_ASSOC);
	$shoes3 = $sql_link->query("SELECT MAX(next_index) AS max FROM `faces_3` WHERE `phone`='$phone'");
	$shoes3_vec = $shoes3->fetch_array(MYSQLI_ASSOC);
	$index_1 = $shoes1_vec['max'];
	if(is_null($index_1)) {
		$index_1 = 0;
	} else {
		$index_1 = 3*intval($index_1);// using A(i) = 3*A(i-1)
	}
	$index_2 = $shoes2_vec['max'];
	if(is_null($index_2)) {
		$index_2 = 0;
	} else {
		$index_2 = intval($index_2-1);
	}
	$index_3 = $shoes3_vec['max'];
	if(is_null($index_3)) {
		$index_3 = 0;
	} else {
		$index_3 = intval($index_3)-1;
	}
} else {
	$sql_link->query("UPDATE `subjects` SET `after_ins_faces`='1',`faces_type`='1' WHERE `session_id`='$session_cookie'");
}
if(!empty($_POST)) {
	$arr = json_decode($_POST['answers']);
	$type = intval($arr[0]);
	$answer = $arr[1];
	if($type == 1) {
		$imgL = $arr[2];
		$imgM = $arr[3];
		$imgR = $arr[4];
		$correct = $arr[5];
		$is_training = $arr[6];
		$clock = $arr[7];
		$next_idx = $arr[8];
		$sql_link->query("INSERT INTO `faces_1` (`phone`,`imgLeft`,`imgMiddle`,`imgRight`,`answer`,`correct`,`IS_TRAINING`,`time`,`next_index`) VALUES ('$phone','$imgL','$imgM','$imgR','$answer','$correct','$is_training','$clock','$next_idx')");
		$sql_link->query("UPDATE `subjects` SET `faces_type`='1' WHERE `session_id`='$session_cookie'");
	} else if($type == 2) {
		$image = $arr[2];
		$correct = $arr[3];
		$is_training = $arr[4];
		$clock = $arr[5];
		$next_idx = $arr[6];
		$sql_link->query("INSERT INTO `faces_2` (`phone`,`img`,`answer`,`correct`,`IS_TRAINING`,`time`,`next_index`) VALUES ('$phone','$image','$answer','$correct','$is_training','$clock','$next_idx')");
		$sql_link->query("UPDATE `subjects` SET `faces_type`='2' WHERE `session_id`='$session_cookie'");
	} else if($type == 3) {
		$image = $arr[2];
		$correct = $arr[3];
		$is_training = $arr[4];
		$clock = $arr[5];
		$next_idx = $arr[6];
		$sql_link->query("INSERT INTO `faces_3` (`phone`,`img`,`answer`,`correct`,`IS_TRAINING`,`time`,`next_index`) VALUES ('$phone','$image','$answer','$correct','$is_training','$clock','$next_idx')");
		$sql_link->query("UPDATE `subjects` SET `faces_type`='3' WHERE `session_id`='$session_cookie'");
	}
	
	$v_next = intval($next_idx);
	if($type == 1 && $v_next <= 7) {
		echo "reg";
	} else if($type == 2 && $v_next <= 19) {
		echo "block1";
	} else if($type == 3 && $v_next <= 19) {
		echo "block2";
	} else if($type == -1){
		$sql_link->query("UPDATE `subjects` SET `task_status`='$status',`after_ins_faces`='0',`faces_type`='1' WHERE `session_id`='$session_cookie'");
		echo Constants::global_page;
		return;
	}
	return;
}
?>
<!-- faces up as anchor page -->
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
			<meta name="Itay_Guy" content="B.Sc Student project in University Of Haifa - 2017"/>
			<link href="/recognitions/dev/style.css" rel="stylesheet"/>
			<link href="/recognitions/dev/faces.css" rel="stylesheet"/>
			<title>Faces</title>
				<style type="text/css"></style>
				<script type="text/javascript" src="/recognitions/dev/fixation.js"></script>
				<script type="text/javascript" src="/recognitions/dev/shuffle.js"></script>
				<script type="text/javascript" src="/recognitions/dev/faces.js"></script>
				<script type="text/javascript" src="/recognitions/dev/assist_functions.js"></script>
		</head>
			<body>
				<div id="layout-top"></div>
				<div id="layout-middle">
					<div id="layout-main">
						<div class="layout-center-0">
						<div class="layout-center-1">
						<div class="layout-center-2" id='fix'>
						<table>
							<tr>
								<td>
									<div class="transfer_place">
										<input id="inp_to_edit" type="button" onclick="Form.transfer();" value="עריכת פרטים אישיים"/>
									</div>
								</td>
								<td id="myProgress">
									<div id="myBar"></div>
								</td>
								<td>
									<div class="transfer_place">
										<input  type="hidden" id="inp_to_edit"/>
									</div>
								</td>
							</tr>
						</table>
						<div class="layout-center-3">
							<div id="section" style='margin:2.5em 2em 2em 2em;'>
								<table id="table">
									<?if($need_new_content == true){?>
										<script type="text/javascript">
											//document.getElementById("inp_to_edit").disabled = true;
											var type1a = "<? echo $faces_vectors[0][1]; ?>";
											var type1b = "<? echo $faces_vectors[0][3]; ?>";
											var type1c = "<? echo $faces_vectors[0][5]; ?>";
											var type2a = "<? echo $faces_vectors[1][1]; ?>";
											var type2b = "<? echo $faces_vectors[1][3]; ?>";
											var type3a = "<? echo $faces_vectors[2][1]; ?>";
											var type3b = "<? echo $faces_vectors[2][3]; ?>";
											
											Functions.gender = "<? echo $gender; ?>";
											Functions.hand = "<? echo $hand; ?>";
											Functions.img_width = "<? echo $width; ?>";
											
											Functions.imgs_a_type1 = type1a.split(",");
											Functions.imgs_b_type1 = type1b.split(",");
											Functions.imgs_c_type1 = type1c.split(",");
											Functions.img_idx_1 = "<? echo $index_1; ?>";
											Functions.img_idx_1 = parseInt(Functions.img_idx_1);
											
											Functions.imgs_a_type2 = type2a.split(",");
											Functions.imgs_b_type2 = type2b.split(",");
											Functions.img_idx_2 = "<? echo $index_2; ?>";
											Functions.img_idx_2 = parseInt(Functions.img_idx_2);
											
											Functions.imgs_a_type3 = type3a.split(",");
											Functions.imgs_b_type3 = type3b.split(",");
											Functions.img_idx_3 = "<? echo $index_3; ?>";
											Functions.img_idx_3 = parseInt(Functions.img_idx_3);
											
											Functions.type = "<? echo $curr_type; ?>";
											Functions.type = parseInt(Functions.type);
											
											Functions.preload();
											if(Functions.type == 1){
												Functions.showPreview1();
											} else if(Functions.type == 2) {
												if(Functions.img_idx_2 == 0) {
													Functions.showPreview2();
												} else {
													Functions.showQuestions2();
												}
											} else if(Functions.type == 3) {
												if(Functions.img_idx_3 == 0) {
													showPreview3();
												} else {
													Functions.showQuestions3();
												}
											} else {
												console.log("No type task is picked!");
											}
										</script>
									<?}else{?>
										<tr>
											<td>
												<h1 class="h1_style"><strong>הוראות למטלה 5:</strong></h1>
												<h4 class="h4_style">בשלב הראשון של משימה זו, עלייך להתבונן היטב בקבוצה של תמונות של פרצופים.</h4>
												<h4 class="h4_style">לאחריהן, יופיע מקבץ של תמונות של פרצופים שונים.</h4>
												<?if(strcmp($gender,"בן") == 0){?>
														<h4 class="h4_style">אתה מתבקש לציין מי מהפרצופים הללו הופיעו קודם.</h4>
												<?}else if(strcmp($gender,"בת") == 0){?>
														<h4 class="h4_style">את מתבקשת לציין מי מהפרצופים הללו הופיעו קודם.</h4>
												<?}?>
											</td>
										</tr>
										<tr>
											<td>
												<?
												if(strcmp($gender,"בן") == 0)
												{?>
													<h4 class="h4_style" id="make_bold"><strong>השתדל לענות כמה שיותר מדויק בזמן הקצר ביותר</strong></h4>
												<?}else if(strcmp($gender,"בת") == 0){?>
													<h4 class="h4_style" id="make_bold"><strong>השתדלי לענות כמה שיותר מדויק בזמן הקצר ביותר</strong></h4>
												<?}?>
											</td>
										</tr>
										<tr>
											<td id="frame">
												<label id='waiting'><strong>נא להמתין בזמן הטעינה...</strong></label>
												<br>
												<img alt='' src='/recognitions/dev/loading-blue.gif' width="150px" height="150px"/>
												<!--<label id="countdown">20</label> usage to show timer instead-->
											</td>
										</tr>
										<tr>
											<td>
												<script type="text/javascript">
													//document.getElementById("inp_to_edit").disabled = true;
													var type1a = "<? echo $faces_vectors[0][1]; ?>";
													var type1b = "<? echo $faces_vectors[0][3]; ?>";
													var type1c = "<? echo $faces_vectors[0][5]; ?>";
													var type2a = "<? echo $faces_vectors[1][1]; ?>";
													var type2b = "<? echo $faces_vectors[1][3]; ?>";
													var type3a = "<? echo $faces_vectors[2][1]; ?>";
													var type3b = "<? echo $faces_vectors[2][3]; ?>";

													Functions.gender = "<? echo $gender; ?>";
													Functions.hand = "<? echo $hand; ?>";
													Functions.img_width = "<? echo $width; ?>";
													
													Functions.imgs_a_type1 = type1a.split(",");
													Functions.imgs_b_type1 = type1b.split(",");
													Functions.imgs_c_type1 = type1c.split(",");
													Functions.img_idx_1 = 0;
													
													Functions.imgs_a_type2 = type2a.split(",");
													Functions.imgs_b_type2 = type2b.split(",");
													Functions.img_idx_2 = 0;
													
													Functions.imgs_a_type3 = type3a.split(",");
													Functions.imgs_b_type3 = type3b.split(",");
													Functions.img_idx_3 = 0;
													
													Functions.type = 1; // initial value
													
													Functions.preload();
													Functions.countDown();
													Functions.putGIF();
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