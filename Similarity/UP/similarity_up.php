<?php
require_once("classes.php");
$myDBi = new DBi();
$sql_link = $myDBi->connect2sqli();
if(!isset($_COOKIE["subject_session"]))
{
	header('Location:'.Constants::index_page);
	return;
}
$status = Constants::SIMILARITY_INV_NUM;
$session_cookie = $_COOKIE["subject_session"];
$vec = $sql_link->query("SELECT * FROM `subjects` WHERE `session_id`='$session_cookie'");
$items = $vec->fetch_array(MYSQLI_ASSOC);
$session_id = $items['session_id'];
$phone = $items['phone'];
$gender = $items['gender'];
$hand = $items['hand'];
$width = $items['width'];
$task = $items['task_status'];
$after_ins = $items['after_ins_2'];
$index = $items['permut_index_2'];
$stage = $items['stage_2'];
$enable = $items['enable'];
$need_new_content = false;
$permut = array();
$similarity_vectors = array();
if(is_null($session_id) or
	strcmp($enable,"0") == 0 or
	is_null($phone) or
	is_null($gender) or
	is_null($hand) or
	is_null($width)){
	header('Location:'.Constants::index_page);
	return;
}
if(strcmp($session_cookie,$session_id)!= 0){
	header('Location:'.Constants::index_page);
	return;
}
if(strcmp($task,Constants::SIMILARITY_UP_NUM) != 0){
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
	
	$similarity_vectors = $myDBi->make_similarity_vectors('UP');
}
if(!empty($_POST)){
	if(isset($_POST['answers'])){
		$arr = json_decode($_POST['answers']);
		$ans = $arr[0];
		if(strcmp($hand,"ימין") == 0){
			if(strcmp($ans,"90") == 0){
				$ans = "זהים"; //0
			}
			else if(strcmp($ans,"77") == 0){
				$ans = "שונים"; //1
			}
		}
		else if(strcmp($hand,"שמאל") == 0){
			if(strcmp($ans,"90") == 0){
				$ans = "שונים"; //1
			}
			else if(strcmp($ans,"77") == 0){
				$ans = "זהים"; //0
			}
		}
		$image_left = $arr[1];
		$image_right = $arr[2];
		$isTrain = $arr[3];
		$time = $arr[4];
		$sql_link->query("INSERT INTO `similarity` (`phone`,`image_left`,`image_right`,`answer`,`UP_OR_INV`,`IS_TRAINING`,`time`) VALUES ('$phone','$image_left','$image_right','$ans','UP','$isTrain','$time')");
	}
	$count = $_POST['count'];
	if($count == 0) {// beginning
		$sql_link->query("UPDATE `subjects` SET `after_ins_2`='1',`stage_2`='0' WHERE `session_id`='$session_cookie'");
	}
	$count = (intval($count)+1)."";
	$stage = $_POST['stage'];
	$sql_link->query("UPDATE `subjects` SET `stage_2`='$stage' WHERE `session_id`='$session_cookie'");
	if(isset($_POST['count']) and isset($_POST['max']) && isset($_POST['stage'])){
		if($_POST['count'] == $_POST['max'] && $stage == 3){
			$sql_link->query("UPDATE `subjects` SET `permut_index_2`='0',`after_ins_2`='0',`stage_2`='0',`task_status`='$status' WHERE `session_id`='$session_cookie'");
			echo Constants::similarity_page_inv;
		}
		else if($_POST['count'] == $_POST['max']){
			$count = $_POST['count'];
			$sql_link->query("UPDATE `subjects` SET `permut_index_2`='0',`stage_2`=`stage_2`+1 WHERE `session_id`='$session_cookie'");
		}
		else{
			$sql_link->query("UPDATE `subjects` SET `permut_index_2`='$count' WHERE `session_id`='$session_cookie'");
		}
	}
	return;
}
?>
<!-- similarity up as anchor page -->
<!DOCTYPE html>
	<html lang="he" dir="rtl">
		<head>
			<meta http-equiv="Content-Type" content="text/html"/>
			<meta charset="utf-8"/>
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
			<meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=yes"/>
			<meta name="HandheldFriendly" content="true"/>
			<meta name="MobileOptimized" content="320"/>
			<meta name="description" content="show two image of faces than the user have to decide if they are similar or different"/>
			<meta name="Itay_Guy" content="B.Sc Student project in University Of Haifa - 2017"/>
			<link href="/recognitions/dev/style.css" rel="stylesheet"/>
			<link href="/recognitions/dev/similarity.css" rel="stylesheet"/>
			<title>Similarity</title>
				<style type="text/css"></style>
				<script type="text/javascript" src="/recognitions/dev/fixation.js"></script>
				<script type="text/javascript" src="/recognitions/dev/shuffle.js"></script>
				<script type="text/javascript" src="/recognitions/dev/similarity_up.js"></script>
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
											var permutation = "<? echo $permut; ?>";
											var arr_a = "<? echo $similarity_vectors[1]; ?>";
											var arr_b = "<? echo $similarity_vectors[3]; ?>";
											Functions.gender = "<? echo $gender; ?>";
											Functions.hand = "<? echo $hand; ?>";
											Functions.img_width = "<? echo $width; ?>";
											Functions.img_idx = "<? echo $index; ?>";
											Functions.stage = "<? echo $stage; ?>";
											Functions.permut = permutation.split(",");
											Functions.imgs_a = arr_a.split(",");
											Functions.imgs_b = arr_b.split(",");
											Functions.preload();
											Functions.changeContent();
										</script>
									<?}else{?>
										<tr>
											<td>
												<h1 class="h1_style"><strong>הוראות למטלה 2:</strong></h1>
												<?if(strcmp($gender,"בן") == 0)
												{?>
													<h4 class="h4_style">במטלה הזו יוצגו בפניך שתי תמונות של פנים, אחת ליד השנייה ותתבקש להחליט האם התמונות זהות או שונות.</h4>
												<?}else{?>	
													<h4 class="h4_style">במטלה זו יוצגו בפנייך שתי תמונות של פנים, אחת ליד השנייה ותתבקשי להחליט האם התמונות זהות או שונות.</h4>
												<?}?>
											</td>
										</tr>
										<tr>
											<td>
												<?if(strcmp($gender,"בן") == 0)
												{
													if(strcmp($hand,"ימין") == 0)
													{?>
														<h4 class="h4_style">אם הן זהות לחץ על <img alt="ז" src="/recognitions/dev/z.png" width="30px" height="30px"/></h4>
														<h4 class="h4_style">אם הן שונות לחץ על <img alt="צ" src="/recognitions/dev/m.png" width="30px" height="30px"/></h4>
													<?}
													else if(strcmp($hand,"שמאל") == 0)
													{?>
														<h4 class="h4_style">אם הן זהות לחץ על <img alt="צ" src="/recognitions/dev/m.png" width="30px" height="30px"/></h4>
														<h4 class="h4_style">אם הן שונות לחץ על <img alt="ז" src="/recognitions/dev/z.png" width="30px" height="30px"/></h4>
													<?}
												}
												else if(strcmp($gender,"בת") == 0)
												{
													if(strcmp($hand,"ימין") == 0)
													{?>
														<h4 class="h4_style">אם הן זהות לחצי על <img alt="ז" src="/recognitions/dev/z.png" width="30px" height="30px"/></h4>
														<h4 class="h4_style">אם הן שונות לחצי על <img alt="צ" src="/recognitions/dev/m.png" width="30px" height="30px"/></h4>
													<?}
													else if(strcmp($hand,"שמאל") == 0)
													{?>
														<h4 class="h4_style">אם הן זהות לחצי על <img alt="צ" src="/recognitions/dev/m.png" width="30px" height="30px"/></h4>
														<h4 class="h4_style">אם הן שונות לחצי על <img alt="ז" src="/recognitions/dev/z.png" width="30px" height="30px"/></h4>

													<?}?>
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
												$similarity_vectors = $myDBi->make_similarity_vectors('UP');
												$permut = $myDBi->make_permutation($similarity_vectors[0]);
												$sql_link->query("INSERT INTO `permutations` (`phone`,`task`,`permutation`) VALUES ('$phone','$task','$permut')");
												$permut = json_decode($permut);
												$permut = implode(",",$permut);
												?>
												<script type="text/javascript">
													var permutation = "<? echo $permut; ?>";
													var arr_a = "<? echo $similarity_vectors[1]; ?>";
													var arr_b = "<? echo $similarity_vectors[3]; ?>";
													Functions.gender = "<? echo $gender; ?>";
													Functions.hand = "<? echo $hand; ?>";
													Functions.img_width = "<? echo $width; ?>";
													Functions.permut = permutation.split(",");
													console.log("Functions.permut = "+Functions.permut);
													Functions.imgs_a = arr_a.split(",");
													Functions.imgs_b = arr_b.split(",");
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