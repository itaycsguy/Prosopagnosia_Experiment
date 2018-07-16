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
$enable = $items['enable'];
if(is_null($session_id)or strcmp($enable,"0") == 0)
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
$phone = $items['phone'];
$name = $items['name'];
$age = $items['age'];
$parent = $items['parent_name'];
$gender = $items['gender'];
$hand = $items['hand'];
$commu = $items['communication'];
$task = $items['task_status'];
if(!empty($_POST))
{
	// $_POST datums correctness are validated by the client.
	$arr = json_decode($_POST["details"]);
	$name = $arr[0];
	if(count($name) == 0 || strcmp($name,"") == 0)
	{
		$name = '-';
	}
	$age = $arr[1];
	if(count($age) == 0 || strcmp($age,"") == 0)
	{
		$age = '-';
	}
	$gender = $arr[2];
	$hand = $arr[3];
	$parent = $arr[4];
	if(count($parent) == 0 || strcmp($parent,"") == 0)
	{
		$parent = '-';
	}
	$communication = $arr[5]; //  could be an email/cellphone number.
	if(count($communication) == 0 || strcmp($communication,"") == 0)
	{
		$communication = '-';
	}
	$time = date("h:i:sa");
	$date = date("d/m/y");
	$status = Constants::QUESTIONS_NUM;
	$sql_link->query("UPDATE `subjects` SET `name`='$name',`age`='$age',`gender`='$gender',`hand`='$hand',`parent_name`='$parent',`communication`='$communication',`time`='$time',`date`='$date',`task_status`='$status' WHERE `session_id`='$session_cookie'");
	//print_r($arr);
	if(count($arr) == 7)
	{
		$st = $arr[6];
		$sql_link->query("UPDATE `subjects` SET `task_status`='$st' WHERE `session_id`='$session_cookie'");
		echo $myDBi->where_to_link($st);
		return;
	}
	echo Constants::questions_page;
	return;
}
?>
<!-- details as anchor page -->
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
			<link id="csslink" href="/recognitions/dev/details.css" rel="stylesheet"/>
			<title>Patient Details</title>
				<style type="text/css"></style>
			<script type="text/javascript" src="/recognitions/dev/details.js"></script>
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
										<td class="position" colspan="2">
											<h1 class="h1_style"><strong>ברוך הבא - נא מלא את פרטייך:</strong></h1>
											<h4 class="h4_style">שים לב שהפרטים נכונים ועדכניים<span class="pad_edit"><input type="button" id="edit" value="עריכה" onclick="Functions.make_editable();" disabled="true"/></span></h4>
										</td>
									</tr>
									<tr>
										<td class="position">
											<label class="style">שם: </label>
										</td>
										<td>
											<input autocomplete="off" placeholder="חובה" onfocus="" id="inp_name"/>
											<script type="text/javascript">
												var name = "<? echo $name; ?>";
												if(name.length > 0)
												{
													var name_obj = document.getElementById("inp_name");
													name_obj.value = name;
													name_obj.disabled = true;
												}
											</script>
										</td>
									</tr>
									<tr>
										<td class="position">
											<label class="style">גיל: </label>
										</td>
										<td>
											<input autocomplete="off" placeholder="חובה" onfocus="" id="inp_age"/>
											<script type="text/javascript">
												var age = "<? echo $age; ?>";
												if(age.length > 0)
												{
													var age_obj = document.getElementById("inp_age");
													age_obj.value = age;
													age_obj.disabled = true;
												}
											</script>
										</td>
									</tr>
									<tr>
										<td class="position">
											<label class="style">בן/בת: </label>
										</td>
										<td>
											<select id="inp_gender">
														<option value="בן" selected>בן</option>
														<option value="בת">בת</option>
													</select>
											<script type="text/javascript">
												document.getElementById("inp_gender").style.cursor = "pointer";
												var gender = "<? echo $gender; ?>";
												if(gender.length > 0)
												{
													var gender_obj = document.getElementById("inp_gender");
													gender_obj.value = gender;
													gender_obj.disabled = true;
												}
											</script>
										</td>
									</tr>
									<tr>
										<td class="position">
											<label class="style">היד בה אתה כותב: </label>
										</td>
										<td>
											<select id="inp_hand">
														<option value="ימין" selected>ימין</option>
														<option value="שמאל">שמאל</option>
													</select>
											<script type="text/javascript">
												document.getElementById("inp_hand").style.cursor = "pointer";
												var hand = "<? echo $hand; ?>";
												if(hand.length > 0)
												{
													var hand_obj = document.getElementById("inp_hand");
													hand_obj.value = hand;
													hand_obj.disabled = true;
												}
											</script>
										</td>
									</tr>
									<tr>
										<td class="position">
											<label class="style">שם ההורה: </label>
										</td>
										<td>
											<input autocomplete="off" placeholder="חובה" onfocus="" id="inp_parent"/>
											<script type="text/javascript">
												var parent = "<? echo $parent; ?>";
												if(parent.length > 0)
												{
													var parent_obj = document.getElementById("inp_parent");
													parent_obj.value = parent;
													parent_obj.disabled = true;
												}
											</script>
										</td>
									</tr>
									<tr>
										<td class="position">
											<label class="style">דרך התקשרות נוספת: </label>
										</td>
										<td>
											<input autocomplete="off" placeholder="לא חובה" onfocus="" id="inp_phone"/>
											<script type="text/javascript">
												var commu = "<? echo $commu; ?>";
												if(commu.length > 0)
												{
													var phone_obj = document.getElementById("inp_phone");
													phone_obj.value = commu;
													phone_obj.disabled = true;	
												}
											</script>
										</td>
									</tr>
									<tr>
										<td class="position">
											<input type="button" id="click_continue" value="המשך" onclick="Form.ajax();"/>
											<script type="text/javascript">
												var returnTask = "<? if(isset($_GET['returnTask'])){
																	echo $_GET['returnTask'];
																}else{
																	echo "";
																} ?>";
												if(returnTask != "")
												{
													Functions.retTask = "<? echo $task; ?>";
													document.getElementById("click_continue").value = "חזרה למטלה";	
												}
											</script>
										</td>
									</tr>
									<script type="text/javascript">
										if(name.length > 0 || age.length > 0 || parent.length > 0 || commu.length > 0)
										{
											document.getElementById("edit").disabled = false;
											document.getElementById("edit").style.cursor = "pointer";
										}
										else
										{
											document.getElementById("edit").disabled = true;
											document.getElementById("edit").style.cursor = "default";
										}
									</script>
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