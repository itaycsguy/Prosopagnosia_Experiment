<?php
require_once("classes.php");
$myDBi = new DBi();
$sql_link = $myDBi->connect2sqli();
if(!isset($_COOKIE["subject_session"]))
{
	header('Location:'.Constants::index_page);
}
$status = Constants::FINAL_NUM;
$session_cookie = $_COOKIE["subject_session"];
$vec = $sql_link->query("SELECT * FROM `subjects` WHERE `session_id`='$session_cookie'");
$items = $vec->fetch_array(MYSQLI_ASSOC);
$session_id = $items['session_id'];
$phone = $items['phone'];
$gender = $items['gender'];
$hand = $items['hand'];
$width = $items['width'];
$task = $items['task_status'];
$after_ins = $items['after_ins_6'];
$index = $items['permut_index_6'];
$stage = $items['stage_6'];
$enable = $itemsp['enable'];
$need_new_content = false;
$permut = array();
$global_vectors = array();
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
if(strcmp($task,Constants::EXPRESSIONS_NUM) != 0){
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
	
	$expression_vectors = $myDBi->make_expressions_vectors();
}
if(!empty($_POST)){
	if(isset($_POST['answers'])){
		$arr = json_decode($_POST['answers']);
		$ans = $arr[0];
		if(strcmp($ans,"90") == 0){
			$ans = "שמאל";
		}
		else if(strcmp($ans,"77") == 0){
			$ans = "ימין";
		}
		$image1 = $arr[1];
		$image2 = $arr[2];
		$image3 = $arr[3];
		$correct = $arr[4];
		$isTrain = $arr[5];
		$time = $arr[6];
		
		$sql_link->query("INSERT INTO `expressions` (`phone`,`relative_image`,`image_left`,`image_right`,`answer`,`correct`,`IS_TRAINING`,`time`) VALUES ('$phone','$image1','$image2','$image3','$ans','$correct','$isTrain','$time')");
		
	}
	$count = $_POST['count'];
	if($count == 0) { // beginning
		$sql_link->query("UPDATE `subjects` SET `after_ins_6`='1',`task_status`='$task',`stage_6`='0' WHERE `session_id`='$session_cookie'");
	}
	$count = (intval($count)+1)."";
	$stage = $_POST['stage'];
	$sql_link->query("UPDATE `subjects` SET `stage_6`='$stage' WHERE `session_id`='$session_cookie'");
	if(isset($_POST['count']) and isset($_POST['max']) && isset($_POST['stage'])){
		if($_POST['count'] == $_POST['max'] && $stage == 2){
			$sql_link->query("UPDATE `subjects` SET `permut_index_6`='0',`after_ins_6`='0',`stage_6`='0',`task_status`='$status' WHERE `session_id`='$session_cookie'");
			echo Constants::final_page;
		}
		else if($_POST['count'] == $_POST['max']){
			$count = $_POST['count'];
			$sql_link->query("UPDATE `subjects` SET `permut_index_6`='0',`stage_6`=`stage_6`+1 WHERE `session_id`='$session_cookie'");
		}
		else{
			$sql_link->query("UPDATE `subjects` SET `permut_index_6`='$count' WHERE `session_id`='$session_cookie'");
		}
	}
	return;
}
?>
<!-- expressions as anchor page -->
<!DOCTYPE html>
	<html lang="he" dir="rtl">
		<head>
			<meta http-equiv="Content-Type" content="text/html"/>
			<meta charset="utf-8"/>
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
			<meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=yes"/>
			<meta name="HandheldFriendly" content="true"/>
			<meta name="MobileOptimized" content="320"/>
			<meta name="description" content="show three faces and the patient need to decide which of the bottom faces express the same as at the top"/>
			<meta name="Itay_Guy" content="B.Sc Student project in University Of Haifa - 2017"/>
			<link id="csslink" href="/recognitions/dev/style.css" rel="stylesheet"/>
			<link id="csslink" href="/recognitions/dev/expressions.css" rel="stylesheet"/>
			<title>Expressions</title>
				<style type="text/css"></style>
				<script type="text/javascript" src="/recognitions/dev/fixation.js"></script>
				<script type="text/javascript" src="/recognitions/dev/shuffle.js"></script>
				<script type="text/javascript" src="/recognitions/dev/expressions.js"></script>
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
										<input type="hidden" id="inp_to_edit"/>
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
											var permutation = "<? echo $permut; ?>";
											var arr_a = "<? echo $expression_vectors[1]; ?>";
											var arr_b = "<? echo $expression_vectors[3]; ?>";
											var arr_c = "<? echo $expression_vectors[5]; ?>";
											var arr_d = "<? echo $expression_vectors[7]; ?>";
											Functions.gender = "<? echo $gender; ?>";
											Functions.hand = "<? echo $hand; ?>";
											Functions.img_width = "<? echo $width; ?>";
											Functions.img_idx = "<? echo $index; ?>";
											Functions.stage = "<? echo $stage; ?>";
											Functions.permut = permutation.split(",");
											Functions.imgs_a = arr_a.split(",");
											Functions.imgs_b = arr_b.split(",");
											Functions.imgs_c = arr_c.split(",");
											Functions.imgs_d = arr_d.split(",");
											Functions.preload();
											Functions.changeContent();
										</script>
									<?}else{?>
										<tr>
											<td>
												<h1 class="h1_style"><strong>הוראות למטלה 8:</strong></h1>
												<h4 class="h4_style">במטלה שלפניך יוצגו שלוש תמונות של פרצופים, אחד למעלה ושתיים למטה.</h4>
												<h4 class="h4_style">עליך לציין מי מבין הפרצופים שלמטה (הימני או השמאלי) מביע את אותו הרגש כמו האדם בתמונה העליונה.</h4>
											</td>
										</tr>
										<tr>
											<td>
											<?
												if(strcmp($gender,"בן") == 0)
												{?>
													<h4 class="h4_style">אם בחרת בצד ימין לחץ על <img alt="צ" src="/recognitions/dev/m.png" width="30px" height="30px"/></h4>
													<h4 class="h4_style">אם בחרת בצד שמאל לחץ על <img alt="ז" src="/recognitions/dev/z.png" width="30px" height="30px"/></h4>
												<?}
												else if(strcmp($gender,"בת") == 0)
												{?>
													<h4 class="h4_style">אם בחרת בצד ימין לחצי על <img alt="צ" src="/recognitions/dev/m.png" width="30px" height="30px"/></h4>
													<h4 class="h4_style">אם בחרת בצד שמאל לחצי על <img alt="ז" src="/recognitions/dev/z.png" width="30px" height="30px"/></h4>
												<?}
											?>
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
												$expression_vectors = $myDBi->make_expressions_vectors("GLOBAL");
												$permut = $myDBi->make_permutation($expression_vectors[0]);
												$sql_link->query("INSERT INTO `permutations` (`phone`,`task`,`permutation`) VALUES ('$phone','$task','$permut')");
												$permut = json_decode($permut);
												$permut = implode(",",$permut);
												?>
												<script type="text/javascript">
													var permutation = "<? echo $permut; ?>";
													var arr_a = "<? echo $expression_vectors[1]; ?>";
													var arr_b = "<? echo $expression_vectors[3]; ?>"; // outter
													var arr_c = "<? echo $expression_vectors[5]; ?>"; // outter
													var arr_d = "<? echo $expression_vectors[7]; ?>"; // outter
													Functions.gender = "<? echo $gender; ?>";
													Functions.hand = "<? echo $hand; ?>";
													Functions.img_width = "<? echo $width; ?>";
													Functions.permut = permutation.split(",");
													console.log("Functions.permut = "+Functions.permut);
													Functions.imgs_a = arr_a.split(",");
													Functions.imgs_b = arr_b.split(",");
													Functions.imgs_c = arr_c.split(",");
													Functions.imgs_d = arr_d.split(",");
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