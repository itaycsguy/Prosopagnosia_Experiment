<?php
require_once("classes.php");
$myDBi = new DBi();
$sql_link = $myDBi->connect2sqli();
if(!isset($_COOKIE["subject_session"]))
{
	header('Location:'.Constants::index_page);
}
$status = Constants::SIMILARITY_UP_NUM;
$session_cookie = $_COOKIE["subject_session"];
$vec = $sql_link->query("SELECT * FROM `subjects` WHERE `session_id`='$session_cookie'");
$items = $vec->fetch_array(MYSQLI_ASSOC);
$session_id = $items['session_id'];
$phone = $items['phone'];
$gender = $items['gender'];
$hand = $items['hand'];
$width = $items['width'];
$task = $items['task_status'];
$after_ins = $items['after_ins_1'];
$index = $items['permut_index_1'];
$enable = $items['enable'];
$need_new_content = false;
$permut = array();
$famous_vectors = array();
if(is_null($session_id) or
	strcmp($enable,"0") == 0 or
	is_null($phone) or
	is_null($gender) or
	is_null($hand) or
	is_null($width)){
	header('Location:'.Constants::index_page);
}
if(strcmp($session_cookie,$session_id)!= 0){
	header('Location:'.Constants::index_page);
}
if(strcmp($task,Constants::FAMOUS_NUM) != 0){
	$link = $myDBi->where_to_link($task);
	if(!empty($_POST))
		echo $link;
	else
		header('Location:'.$link);
	return;
}
if(strcmp($after_ins,"1") == 0){
	$need_new_content = true;
	$vec = $sql_link->query("SELECT * FROM `permutations` WHERE `phone`='$phone' AND `task`='$task'");
	$items = $vec->fetch_array(MYSQLI_ASSOC);
	$permut = $items['permutation'];
	$permut = json_decode($permut);
	$permut = implode(",",$permut);
	
	$famous_vectors = $myDBi->make_famous_vectors();
}
if(!empty($_POST)){
	if(isset($_POST['inputs'])){
		$arr = json_decode($_POST['inputs']);
		$name = $arr[0];
		if(count($name) == 0 || strcmp($name,"") == 0){
			$name = '-';
		}
		$context = $arr[1];
		if(count($context) == 0 || strcmp($context,"") == 0){
			$context = '-';
		}
		$correct = $arr[2];
		$image = $arr[3];
		$time = $arr[4];
		$sql_link->query("INSERT INTO `famous` (`phone`,`image`,`name`,`context`,`time_inputs`,`correct`,`name_familiar`,`time_answer`) VALUES ('$phone','$image','$name','$context','$time','$correct','-','-')");
	}
	else if(isset($_POST['answer'])){
		$arr = json_decode($_POST['answer']);
		$answer = $arr[0];
		if(strcmp($answer,"1") == 0)
		{
			$answer = "מכיר";
		}
		$image = $arr[1];
		$correct = $arr[2];
		$time = $arr[3];
		$sql_link->query("INSERT INTO `famous` (`phone`,`image`,`name`,`context`,`time_inputs`,`correct`,`name_familiar`,`time_answer`) VALUES ('$phone','$image','-','-','-','$correct','$answer','$time')");
	}
	$count = $_POST['count'];
	if($count == 1){
		$sql_link->query("UPDATE `subjects` SET `after_ins_1`='1',`task_status`='$task' WHERE `session_id`='$session_cookie'");
	}
	if(isset($_POST['count']) and isset($_POST['max'])){
		if($_POST['count'] == ($_POST['max']-1)){
			// $status = Constants::FAMOUS_NUM;
			$sql_link->query("UPDATE `subjects` SET `task_status`='$status',`permut_index_1`='0',`after_ins_1`='0' WHERE `session_id`='$session_cookie'");
			echo Constants::similarity_page_up;
		}
		else
			$sql_link->query("UPDATE `subjects` SET `permut_index_1`='$count' WHERE `session_id`='$session_cookie'");
	}
	return;
}
?>
<!-- famous as anchor page -->
<!DOCTYPE html>
	<html lang="he" dir="rtl">
		<head>
			<meta http-equiv="Content-Type" content="text/html"/>
			<meta charset="utf-8"/>
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
			<meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=yes"/>
			<meta name="HandheldFriendly" content="true"/>
			<meta name="MobileOptimized" content="320"/>
			<meta name="description" content="show famous and ask about their identity"/>
			<meta name="Itay_Guy" content="B.Sc Student project in University Of Haifa - 2017"/>
			<link href="/recognitions/dev/style.css" rel="stylesheet"/>
			<link href="/recognitions/dev/famous.css" rel="stylesheet"/>
			<title>Famous</title>
				<style type="text/css"></style>
				<script type="text/javascript" src="/recognitions/dev/fixation.js"></script>
				<script type="text/javascript" src="/recognitions/dev/famous.js"></script>
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
										<input id="inp_to_edit" type="button" onclick="Form.transfer();" value="עריכת פרטים אישיים"/>
									</div>
								</td>
								<td id="myProgress">
									<div id="myBar"></div>
								</td>
								<td>
									<div class="transfer_place">
										<input type="hidden" id="inp_to_edit"/>
									</div>
								</td>
							</tr>
						</table>
						<div class="layout-center-1">
						<div class="layout-center-2">
						<div class="layout-center-3">
							<div id="section" style='margin:2.5em 2em 2em 2em;'>
								<table id="table" style="">
									<?if($need_new_content == true){?>
										<script type="text/javascript">
											//document.getElementById("inp_to_edit").disabled = true;
											var permutation = "<? echo $permut; ?>";
											var arr = "<? echo $famous_vectors[1]; ?>";
											var correctArr = "<? echo $famous_vectors[2]; ?>";
											var genderArr = "<? echo $famous_vectors[3]; ?>";
											Functions.gender = "<? echo $gender; ?>";
											Functions.hand = "<? echo $hand; ?>";
											Functions.img_width = "<? echo $width; ?>";
											Functions.img_idx = "<? echo $index; ?>";
											Functions.img_idx = parseInt(Functions.img_idx)+1;
											Functions.permut = permutation.split(",");
											Functions.imgs = arr.split(",");
											Functions.correct_imgs = correctArr.split(",");
											Functions.gender_imgs = genderArr.split(",");
											Functions.preload();
											Functions.changeContent(); // if we have to call the second step there is an additional field which is must to be in the DB.
										</script>
									<?}else{?>
										<tr>
											<td>
												<h1 class="h1_style"><strong>הוראות למטלה 1:</strong></h1>
											</td>
										</tr>
										<tr>
											<td>
												<?if(strcmp($gender,"בן") == 0){?>
													<h4 class="h4_style">יופיעו לפניך תמונות של אנשים.</h4>
													<h4 class="h4_style">חלק מהתמונות הם של מפורסמים שאתה אמור להכיר וחלק מהתמונות הם של אנשים לא מוכרים.</h4>
													<h4 class="h4_style">בכל תמונה תתבקש לציין את שמו של האדם ו/או את ההקשר (מהיכן אתה מכיר אותו).</h4>
													<h4 class="h4_style">במידה ואתה לא מכיר את האדם, לחץ על כפתור "אני לא מזהה".</h4>
												<?}else if(strcmp($gender,"בת") == 0){?>
													<h4 class="h4_style">יופיעו לפנייך תמונות של אנשים.</h4>
													<h4 class="h4_style">חלק מהתמונות הם של מפורסמים שאת אמורה להכיר וחלק מהתמונות הם של אנשים לא מוכרים.</h4>
													<h4 class="h4_style">בכל תמונה תתבקשי לציין את שמו של האדם ו/או את ההקשר (מהיכן את מכירה אותו).</h4>
													<h4 class="h4_style">במידה ואת לא מכירה את האדם, לחצי על כפתור "אני לא מזהה".</h4>
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
												<? 
												$famous_vectors = $myDBi->make_famous_vectors();
												$permut = $myDBi->make_permutation($famous_vectors[0]);
												$sql_link->query("INSERT INTO `permutations` (`phone`,`task`,`permutation`) VALUES ('$phone','$task','$permut')");
												$permut = json_decode($permut);
												$permut = implode(",",$permut);
												?>
												<script type="text/javascript">
													var permutation = "<? echo $permut; ?>";
													var arr = "<? echo $famous_vectors[1]; ?>";
													var correctArr = "<? echo $famous_vectors[2]; ?>";
													var genderArr = "<? echo $famous_vectors[3]; ?>";
													Functions.gender = "<? echo $gender; ?>";
													Functions.hand = "<? echo $hand; ?>";
													Functions.img_width = "<? echo $width; ?>";
													Functions.permut = permutation.split(",");
													Functions.imgs = arr.split(",");
													Functions.correct_imgs = correctArr.split(",");
													Functions.gender_imgs = genderArr.split(",");
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