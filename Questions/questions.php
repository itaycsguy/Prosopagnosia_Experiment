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
$status = $items['task_status'];
$enable = $items['enable'];
$refresh = false;
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
if(strcmp($status,Constants::QUESTIONS_NUM) != 0)
{
	$link = $myDBi->where_to_link($status);
	if(!empty($_POST))
		echo $link;
	else
		header('Location: '.$link);
	return;
}
$ques = $sql_link->query("SELECT * FROM `questions` WHERE `phone`='$phone'");
$checked_arr = array();
$last_val = "";
while($checked = $ques->fetch_array(MYSQLI_ASSOC))
{
	array_push($checked_arr,$checked['question'].",");
	array_push($checked_arr,$checked['answer'].",");
	$last_val = $checked['answer'];
}
if(!empty($checked_arr))
{
	$checked_arr[count($checked_arr)-1] = $last_val;
	$checked_arr = implode($checked_arr);
}
if(!empty($_POST))
{
	$arr = json_decode($_POST["questions"]);
	$length = count($arr);
	for($i = 0;$i < $length;$i+=2)
	{
		$name = $arr[$i];
		$value = $arr[$i+1];
		$sql_link->query("INSERT INTO `questions` (`phone`,`question`,`answer`) VALUES ('$phone','$name','$value')");
	}
	$status = Constants::SCALING_NUM;
	$sql_link->query("UPDATE `subjects` SET `task_status`='$status' WHERE `session_id`='$session_cookie'");
	echo Constants::scaling_page;
	return;
}
?>
<!-- questions as anchor page -->
<!DOCTYPE html>
	<html lang="he" dir="rtl">
		<head>
			<meta http-equiv="Content-Type" content="text/html"/>
			<meta charset="utf-8"/>
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
			<meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=yes"/>
			<meta name="HandheldFriendly" content="true"/>
			<meta name="MobileOptimized" content="320"/>
			<meta name="description" content="patient's parent questions table"/>
			<meta name="Itay_Guy" content="B.Sc Student project in University Of Haifa - 2017"/>
			<link id="csslink" href="style.css" rel="stylesheet"/>
			<link id="csslink" href="questions.css" rel="stylesheet"/>
			<title>Patient's Parent Questions</title>
				<style type="text/css"></style>
			<script type="text/javascript" src="/recognitions/dev/questions.js"></script>
		</head>
			<body>
				<div id="layout-top"></div>
				<div id="layout-middle">
					<div id="layout-main">
						<div class="layout-center-0">
						<!--
						<table>
							<tr>
								<td>
									<div class="transfer_place">
										<input id="inp_to_edit" type="button" onclick="Form.transfer();" value="עריכת פרטים אישיים"/>
									</div>
								</td>
							</tr>
						</table>
						-->
						<div class="layout-center-1">
						<div class="layout-center-2">
						<div class="layout-center-3">
							<div id="section">
								<table id="table">
									<tr class="stright_right">
										<strong><h3>הערה חשובה: בשלב זה ההורה מתבקש לענות על השאלות.</h3></strong>
									</tr>
								</table>
								<table class="shadow">
									<thead>
										<th class="h_style">
											<label class="style adjacent">האם ילד זה שונה מילדים אחרים בדרכים הבאות:</label>
										</th>
										<th class="h_style fit_sizes">
											<label class="style adjacent">לא</label>
										</th>
										<th class="h_style">
											<label class="style adjacent">במידה מסוימת</label>
										</th>
										<th class="h_style fit_sizes">
											<label class="style adjacent">כן</label>
										</th>
									</thead>
									<tbody>
										<tr id="1">
											<td class="style q_style">
												<label class="style adjacent"><strong>1.</strong> הוא שמרן או זהיר?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="1" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="1" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="1" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>	
										</tr>
										<tr id="2">
											<td class="style q_style">
												<label class="style adjacent"><strong>2.</strong> הוא נחשב "פרופסור מפוזר" מול ילדים אחרים?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="2" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="2" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="2" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
										</tr>
										<tr id="3">
											<td class="style q_style">
												<label class="style adjacent"><strong>3.</strong> חי בעולם משלו עם נושאי עניין נוקשים ואידיוסינקרטיים?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="3" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="3" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="3" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
										</tr>
										<tr id="4">
											<td class="style q_style">
												<label class="style adjacent"><strong>4.</strong>	לומד עובדות על נושאים שונים אבל לא באמת מבין את משמעותן?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="4" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="4" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="4" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
										</tr>
										<tr id="5">
											<td class="style q_style">
												<label class="style adjacent"><strong>5.</strong>	מבין מטאפורות,מטבעות לשון וסמלים באופן פשטני?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="5" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="5" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="5" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
										</tr>
										<tr id="6">
											<td class="style q_style">
												<label class="style adjacent"><strong>6.</strong>	בעל סגנון תקשורת חריג-רובוטי, פורמלי, מיושן או מוקפד?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="6" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="6" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="6" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
										</tr>
										<tr id="7">
											<td class="style q_style">
												<label class="style adjacent"><strong>7.</strong>	ממציא מילים וביטויים יוצאי דופן?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="7" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="7" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="7" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
										</tr>
										<tr id="8">
											<td class="style q_style">
												<label class="style adjacent"><strong>8.</strong>	בעל קול או דיבור שונה/מוזר?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="8" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="8" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="8" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
										</tr>
										<tr id="9">
											<td class="style q_style">
												<label class="style adjacent"><strong>9.</strong>	משמיע קולות באופן לא רצוני - קריאות, נהמות, מצמוצים, זעקות וכו"?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="9" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="9" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="9" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
										</tr>
										<tr id="10">
											<td class="style q_style">
												<label class="style adjacent"><strong>10.</strong>	טוב באופן מפתיע בדברים מסוימים וגרוע באופן מפתיע באחרים?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="10" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="10" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="10" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
										</tr>
										<tr id="11">
											<td class="style q_style">
												<label class="style adjacent"><strong>11.</strong>	משתמש בשפה חופשית אך לא מתאים את עצמו להקשר החברתי או לשומעים שונים?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="11" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="11" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="11" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
										</tr>
										<tr id="12">
											<td class="style q_style">
												<label class="style adjacent"><strong>12.</strong>	נעדר אמפטיה - לא מבין רגשות של אחרים?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="12" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="12" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="12" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
										</tr>
										<tr id="13">
											<td class="style q_style">
												<label class="style adjacent"><strong>13.</strong>	משמיע הערות נאיביות או מביכות?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="13" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="13" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="13" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
										</tr>
										<tr id="14"> 
											<td class="style q_style">
												<label class="style adjacent"><strong>14.</strong>	בעל מבט שונה?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="14" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="14" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="14" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
										</tr>
										<tr id="15">
											<td class="style q_style">
												<label class="style adjacent"><strong>15.</strong>	מעוניין להיות חברותי אך נכשל ביצירת קשרים?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="15" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="15" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="15" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
										</tr>
										<tr id="16">
											<td class="style q_style">
												<label class="style adjacent"><strong>16.</strong>	יכול להיות עם ילדים אחרים רק בתנאים שלו?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="16" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="16" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="16" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
										</tr>
										<tr id="17">
											<td class="style q_style">
												<label class="style adjacent"><strong>17.</strong>	אין לו חברים טובים?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="17" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="17" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="17" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
										</tr>
										<tr id="18">
											<td class="style q_style">
												<label class="style adjacent"><strong>18.</strong>	חסר היגיון פשוט?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="18" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="18" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="18" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
										</tr>
										<tr id="19">
											<td class="style q_style">
												<label class="style adjacent"><strong>19.</strong>	חלש במשחקים, לא יודע לשתף פעולה בקבוצה, "מכניס גולים עצמיים"?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="19" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="19" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="19" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
										</tr>
										<tr id="20">
											<td class="style q_style">
												<label class="style adjacent"><strong>20.</strong>	מסורבל, בעל קואורדינציה חלשה, תנועות וג"סטות מוזרות?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="20" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="20" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="20" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
										</tr>
										<tr id="21">
											<td class="style q_style">
												<label class="style adjacent"><strong>21.</strong>	בעל תנועות גוף ופנים לא רצוניות?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="21" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="21" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="21" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
										</tr>
										<tr id="22">
											<td class="style q_style">
												<label class="style adjacent"><strong>22.</strong>	יש לו קשיים בסיום מטלות יום יומיות בשל פעולות או מחשבות חוזרות?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="22" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="22" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="22" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
										</tr>
										<tr id="23">
											<td class="style q_style">
												<label class="style adjacent"><strong>23.</strong>	בעל הרגלים מיוחדים - מתנגד לשינויים, נצמד למוכר?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="23" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="23" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="23" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
										</tr>
										<tr id="24">
											<td class="style q_style">
												<label class="style adjacent"><strong>24.</strong>	בעל קשר ייחודי ויוצא דופן לחפצים?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="24" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="24" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="24" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
										</tr>
										<tr id="25">
											<td class="style q_style">
												<label class="style adjacent"><strong>25.</strong>	ילדים אחרים מציקים לו?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="25" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="25" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="25" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
										</tr>
										<tr id="26">
											<td class="style q_style">
												<label class="style adjacent"><strong>26.</strong>	בעל הבעות פנים יוצאות דופן?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="26" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="26" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="26" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
										</tr>
										<tr id="27">
											<td class="style q_style">
												<label class="style adjacent"><strong>27.</strong>	בעל תנועות גוף יוצאות דופן?</label>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="27" value="no" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="27" value="maybe" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
											<td class="style radio_style">
												<input class="q_group" autocomplete="off" id="inp_radio" type="radio" name="27" value="yes" onclick="Functions.markSelectedQuestion(this)"/>
											</td>
										</tr>
										<!-- continue more questions here -->
									</tbody>
								</table><!-- .end outter table -->
								<table>
									<tr>
										<td>
											<div class="continue_place">
												<input id="inp_continue" type="button" onclick="Form.ajax();" value="המשך"/>
											</div>
										</td>
									</tr>
								</table><!-- .end outter table -->
								<script type="text/javascript">
									var items = "<? 
									if(!empty($checked_arr))
										print_r($checked_arr); 
									else
										echo "";
									?>";
									if(items.length > 0)
										Functions.make_checks(items);
									/*
									var refresh = "<? echo $refresh; ?>";
									if(refresh == true)
									{
										var gender = "<? echo $gender; ?>";
										if(gender == "בן")
										{
											if(confirm("האם אתה מעוניין להמשיך במשימה?") == false)
											{
												var path = "<? echo Constants::index_page; ?>";
												window.location.href = path;
											}
										}
										else if(gender == "בת")
										{
											if(confirm("האם את מעוניינת להמשיך במשימה?") == false)
											{
												var path = "<? echo Constants::index_page; ?>";
												window.location.href = path;
											}
										}
									}
									*/
								</script>
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