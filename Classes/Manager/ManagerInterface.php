<?php
    require_once("classesQ.php");
    session_start();

    $data_base = new SQLDB();
    $sql_link = $data_base->connect2Sqli();

    if (isset($_REQUEST["phone"]) && isset($_REQUEST["action"]))
    {
        $phone = $_REQUEST["phone"];
        $action = $_REQUEST["action"];

        if (strcmp($action,"add") == 0)
        {
            $result = $sql_link->query("SELECT * FROM subjects WHERE `phone`='".$phone."'");
            if ($row = mysqli_fetch_array($result))
                echo "ערך מקסימלי!";
            else
            {
                $sql_link->query("INSERT INTO `subjects` (`phone`,`enable`) VALUES ('".$phone."','1')");
                echo "התווסף!";
            }
        }
        else if (strcmp($action,"delete") == 0)
        {
			$sql_link->query("DELETE FROM `subjects` WHERE `phone`='$phone'");
			$sql_link->query("DELETE FROM `questions` WHERE `phone`='$phone'");
			$sql_link->query("DELETE FROM `permutations` WHERE `phone`='$phone'");
			$sql_link->query("DELETE FROM `famous` WHERE `phone`='$phone'");
			$sql_link->query("DELETE FROM `similarity` WHERE `phone`='$phone'");
			$sql_link->query("DELETE FROM `local` WHERE `phone`='$phone'");
			$sql_link->query("DELETE FROM `global` WHERE `phone`='$phone'");
			$sql_link->query("DELETE FROM `expressions` WHERE `phone`='$phone'");
			
			/*
			//Kassim work
			$sql_link->query("DELETE FROM `test5_type1` WHERE `subject_id`='$phone'");
			$sql_link->query("DELETE FROM `test5_type2` WHERE `subject_id`='$phone'");
			$sql_link->query("DELETE FROM `test5_type3` WHERE `subject_id`='$phone'");
			$sql_link->query("DELETE FROM `test6_type1` WHERE `subject_id`='$phone'");
			$sql_link->query("DELETE FROM `test6_type2` WHERE `subject_id`='$phone'");
			$sql_link->query("DELETE FROM `test6_type3` WHERE `subject_id`='$phone'");
			*/
			
			$sql_link->query("DELETE FROM `faces_1` WHERE `subject_id`='$phone'");
			$sql_link->query("DELETE FROM `faces_2` WHERE `subject_id`='$phone'");
			$sql_link->query("DELETE FROM `faces_3` WHERE `subject_id`='$phone'");
			$sql_link->query("DELETE FROM `shoes_1` WHERE `subject_id`='$phone'");
			$sql_link->query("DELETE FROM `shoes_2` WHERE `subject_id`='$phone'");
			$sql_link->query("DELETE FROM `shoes_3` WHERE `subject_id`='$phone'");
            echo "נמחק!";
        }
        else if (strcmp($action,"ban") == 0)
        {
            $sql_link->query("UPDATE `subjects` SET `enable`='0' WHERE `phone`='".$phone."'");
            echo "נחסם!";
        }
		else if(strcmp($action,"resetLast") == 0)
		{
			$res = $sql_link->query("SELECT * FROM `subjects` WHERE `phone`='$phone'");
			$vec = $res->fetch_array(MYSQLI_ASSOC);
			$task_status = $vec['task_status'];
			$made = false;
			switch($task_status) 
			{
				case('1'):
				case('2'):
				case('3'): 	$sql_link->query("UPDATE `subjects` SET `task_status`='1' WHERE `phone`='$phone'"); //details
							$made = true;
							break;
				case('4'):	$sql_link->query("UPDATE `subjects` SET `permut_index_1`='0',`after_ins_1`='0' WHERE `phone`='$phone'");
							$sql_link->query("DELETE FROM `famous` WHERE `phone`='$phone'");
							$made = true;
							break;
				case('5'):	$sql_link->query("UPDATE `subjects` SET `permut_index_2`='0',`after_ins_2`='0',`stage_2`='0' WHERE `phone`='$phone'");
							$sql_link->query("DELETE FROM `similarity` WHERE `phone`='$phone' AND `UP_OR_INV`='UP'");
							$made = true;
							break;
				case('6'):	$sql_link->query("UPDATE `subjects` SET `permut_index_3`='0',`after_ins_3`='0',`stage_3`='0' WHERE `phone`='$phone'");
							$sql_link->query("DELETE FROM `similarity` WHERE `phone`='$phone' AND `UP_OR_INV`='INV'");
							$made = true;
							break;
				case('7'):	$sql_link->query("UPDATE `subjects` SET `shoes_type`='0',`after_ins_shoes`='0' WHERE `phone`='$phone'");
							$sql_link->query("DELETE FROM `shoes_1` WHERE `subject_id`='$phone'");
							$sql_link->query("DELETE FROM `shoes_2` WHERE `subject_id`='$phone'");
							$sql_link->query("DELETE FROM `shoes_3` WHERE `subject_id`='$phone'");
							$made = true;
							break;
				case('8'):	$sql_link->query("UPDATE `subjects` SET `faces_type`='0',`after_ins_faces`='0' WHERE `phone`='$phone'");
							$sql_link->query("DELETE FROM `faces_1` WHERE `subject_id`='$phone'");
							$sql_link->query("DELETE FROM `faces_2` WHERE `subject_id`='$phone'");
							$sql_link->query("DELETE FROM `faces_3` WHERE `subject_id`='$phone'");
							$made = true;
							break;
				case('9'):	$sql_link->query("UPDATE `subjects` SET `permut_index_4`='0',`after_ins_4`='0',`stage_4`='0' WHERE `phone`='$phone'");
							$sql_link->query("DELETE FROM `global` WHERE `phone`='$phone'");
							$made = true;
							break;
				case('10'):	$sql_link->query("UPDATE `subjects` SET `permut_index_5`='0',`after_ins_5`='0',`stage_5`='0' WHERE `phone`='$phone'");
							$sql_link->query("DELETE FROM `local` WHERE `phone`='$phone'");
							$made = true;
							break;
				case('11'): $sql_link->query("UPDATE `subjects` SET `permut_index_6`='0',`after_ins_6`='0',`stage_6`='0' WHERE `phone`='$phone'");
							$sql_link->query("DELETE FROM `expressions` WHERE `phone`='$phone'");
							
							break;
			}
			if($made) {
				echo "אופס!";
			} else {
				echo "איפוס נכשל!";
			}
		}
        else if (strcmp($action,"reset") == 0)
        {
            //$sql_link->query("UPDATE `subjects` SET `enable`='1' WHERE `phone`='".$phone."'");
			$res = $sql_link->query("SELECT * FROM `subjects` WHERE `phone`='$phone'");
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
			$sql_link->query("DELETE FROM `subjects` WHERE `phone`='$phone'");
			//$sql_link->query("DELETE FROM `questions` WHERE `phone`='$phone'");
			$sql_link->query("DELETE FROM `permutations` WHERE `phone`='$phone'");
			$sql_link->query("DELETE FROM `famous` WHERE `phone`='$phone'");
			$sql_link->query("DELETE FROM `similarity` WHERE `phone`='$phone'");
			$sql_link->query("DELETE FROM `local` WHERE `phone`='$phone'");
			$sql_link->query("DELETE FROM `global` WHERE `phone`='$phone'");
			$sql_link->query("DELETE FROM `expressions` WHERE `phone`='$phone'");
			
			$sql_link->query("DELETE FROM `test5_type1` WHERE `subject_id`='$phone'");
			$sql_link->query("DELETE FROM `test5_type2` WHERE `subject_id`='$phone'");
			$sql_link->query("DELETE FROM `test5_type3` WHERE `subject_id`='$phone'");
			$sql_link->query("DELETE FROM `test6_type1` WHERE `subject_id`='$phone'");
			$sql_link->query("DELETE FROM `test6_type2` WHERE `subject_id`='$phone'");
			$sql_link->query("DELETE FROM `test6_type3` WHERE `subject_id`='$phone'");
			$sql_link->query("INSERT INTO `subjects` (`phone`) VALUES ('$phone')");
			$sql_link->query("UPDATE `subjects` SET `name`='$his_name',`age`='$his_age',`task_status`='0',`gender`='$his_gender',`hand`='$his_hand',`width`='$his_width',`parent_name`='$his_p_name',`communication`='$his_comm',`time`='$his_time',`date`='$his_date',`enable`='1' WHERE `phone`='$phone'");
            echo "אופס!";
        }
		else if(strcmp($action,"allow") == 0)
		{
			$res = $sql_link->query("SELECT * FROM `subjects` WHERE `phone`='$phone'");
			$vec = $res->fetch_array(MYSQLI_ASSOC);
			$his_task_status = $vec['task_status'];
			if(strcmp($his_task_status,"12") == 0)
			{
				$his_name = $vec['name'];
				$his_gender = $vec['gender'];
				$his_hand = $vec['hand'];
				$his_age = $vec['age'];
				$his_width = $vec['width'];
				$his_p_name = $vec['parent_name'];
				$his_comm = $vec['conmmunication'];
				$his_time = $vec['time'];
				$his_date = $vec['date'];
				$sql_link->query("DELETE FROM `subjects` WHERE `phone`='$phone'");
				//$sql_link->query("DELETE FROM `questions` WHERE `phone`='$phone'");
				$sql_link->query("DELETE FROM `permutations` WHERE `phone`='$phone'");
				$sql_link->query("DELETE FROM `famous` WHERE `phone`='$phone'");
				$sql_link->query("DELETE FROM `similarity` WHERE `phone`='$phone'");
				$sql_link->query("DELETE FROM `local` WHERE `phone`='$phone'");
				$sql_link->query("DELETE FROM `global` WHERE `phone`='$phone'");
				$sql_link->query("DELETE FROM `expressions` WHERE `phone`='$phone'");
				
				$sql_link->query("DELETE FROM `test5_type1` WHERE `subject_id`='$phone'");
				$sql_link->query("DELETE FROM `test5_type2` WHERE `subject_id`='$phone'");
				$sql_link->query("DELETE FROM `test5_type3` WHERE `subject_id`='$phone'");
				$sql_link->query("DELETE FROM `test6_type1` WHERE `subject_id`='$phone'");
				$sql_link->query("DELETE FROM `test6_type2` WHERE `subject_id`='$phone'");
				$sql_link->query("DELETE FROM `test6_type3` WHERE `subject_id`='$phone'");
				$sql_link->query("INSERT INTO `subjects` (`phone`) VALUES ('$phone')");
				$sql_link->query("UPDATE `subjects` SET `name`='$his_name',`age`='$his_age',`gender`='$his_gender',`hand`='$his_hand',`width`='$his_width',`parent_name`='$his_p_name',`communication`='$his_comm',`time`='$his_time',`date`='$his_date',`enable`='1' WHERE `phone`='$phone'");
			}
			else
			{
				$sql_link->query("UPDATE `subjects` SET `enable`='1' WHERE `phone`='".$phone."'");
			}
			echo "אופשר!";
		}
        unset($_REQUEST["phone"]);
        unset($_REQUEST["action"]);

        header("Refresh:0");
    }

    $result = $sql_link->query("SELECT * FROM subjects");
?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html" />
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=yes" />
    <meta name="HandheldFriendly" content="true" />
    <meta name="MobileOptimized" content="320" />
    <meta name="description" content="patient's parent questions table" />
    <meta name="Qasim_Sobeh" content="B.Sc Student project in University Of Haifa - 2017" />
    <link id="csslink" href="style.css" rel="stylesheet" />
    <link id="csslink" href="ManagerInterface.css" rel="stylesheet" />
    <title>Manager Interface</title>
    <style type="text/css"></style>
    <script type="text/javascript" src="/recognitions/dev/ManagerInterface.js"></script>
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
			<table>
				<tr>
					<td>
						<h2 class=''>ניהול נבדקים:</h2>
					</td>
				</tr>
				<br>
			</table>
            <table id="changable">
                <?php
                echo "<tr>";
                echo "<th class=\"h_style highlightAdding\" colspan=\"3\"> <label class=\"style adjacent\"><strong>הוספת נבדק חדש:</strong></label></th>";
                echo "<th class=\"h_style highlightAdding\"> <input name=\"phone\" onkeypress=\"return AllowOnlyNumbers(event);\" id=\"phoneToAdd\" placeholder=\"מזהה\"> </th>";
                echo "<th class=\"h_style highlightAdding\" colspan=\"5\"> <button type=\"button\" style=\"width: 100%;\" onclick=\"add()\"')\">הוספה</button></th>";
                echo "</tr><tr>";
                echo "<th class=\"h_style\" style=''> <label class=\"style adjacent\"><strong>שם הנבדק</strong></label></th>";
                echo "<th class=\"h_style\" style=''> <label class=\"style adjacent\"><strong>שם ההורה</strong></label></th>";
                echo "<th class=\"h_style\" style=''> <label class=\"style adjacent\"><strong>מצב</strong></label></th>";
                echo "<th class=\"h_style\" style=''> <label class=\"style adjacent\"><strong>ססמא</strong></label></th>";
                echo "<th class=\"h_style\" colspan=\"5\" style=''> <label class=\"style adjacent\"><strong>פעולות</strong></label></th>";
                echo "</tr>";

                while($row = mysqli_fetch_array($result))
                {
                    $active = $row['enable'];
					$ts = $row['task_status'];
                    if (strcmp($ts,"12") == 0)
					{
                        $active = "סיים";
					}
					else if(strcmp($ts,"12") != 0)
					{
						if(strcmp($active,"1") == 0)
							$active = "פעיל";
						else
							$active = "חסום";
					}
                    echo "<tr>";
                        echo "<td class=\"style q_style\"> <label class=\"style adjacent\">" . $row['name'] . "</label></td>";
                        echo "<td class=\"style q_style\"> <label class=\"style adjacent\">" . $row['parent_name'] . "</label></td>";
                        echo "<td class=\"style q_style\"> <label class=\"style adjacent\">" . $active . "</label></td>";
                        echo "<td class=\"style q_style\"> <label class=\"style adjacent\">" . $row['phone'] . "</label></td>";
						echo "<td class=\"style q_style\"> <button type=\"button\" onclick=\"allowSubject('".$row['phone']."')\">אפשר</button></td>";
						echo "<td class=\"style q_style\"> <button type=\"button\" onclick=\"banSubject('".$row['phone']."')\">חסום</button></td>";
						echo "<td class=\"style q_style\"> <button type=\"button\" onclick=\"resetApart('".$row['phone']."')\">אפס</button></td>";
						echo "<td class=\"style q_style\"> <button type=\"button\" onclick=\"resetSubject('".$row['phone']."')\">אפס להתחלה</button></td>";
                        echo "<td class=\"style q_style\"> <button type=\"button\" onclick=\"deleteSubject('".$row['phone']."')\">מחק</button></td>";
                    echo "</tr>";
                }
                ?>
            </table>
			<table>
				<tr>
					<td>
						<input type='button' id='inp_dis' name='dis' value='התנתק' onclick='byebyePage();'/>
					</td>
				</tr>
			</table>
        </div><!-- .end outter div section -->
    </div><!-- .layout-center-3 -->
    </div><!-- .layout-center-2 -->
    </div><!-- .layout-center-1 -->
    </div><!-- .layout-center-0 -->
    </div><!-- #layout-main -->
    </div><!-- #layout-middle -->
    <div id="layout-bottom"></div>
    <script type="text/javascript"></script>
</body>
</html>