<?php
session_start();

class Constants{
	public const index_page = '/recognitions/dev/index.php';
	public const INDEX_NUM = "0";
	public const details_page = '/recognitions/dev/details.php';
	public const DETAILS_NUM = "1";
	public const questions_page = '/recognitions/dev/questions.php';
	public const QUESTIONS_NUM = "2";
	public const scaling_page = '/recognitions/dev/scaling.php';
	public const SCALING_NUM = "3";
	public const famous_page = '/recognitions/dev/famous.php';
	public const FAMOUS_NUM = "4";
	public const similarity_page_up = '/recognitions/dev/similarity_up.php';
	public const similarity_page_inv = '/recognitions/dev/similarity_inv.php';
	public const SIMILARITY_UP_NUM = "5";
	public const SIMILARITY_INV_NUM = "6";
	public const shoe_angles_page = '/recognitions/dev/shoes.php';
	public const SHOE_ANGLES_NUM = "7";
	public const faces_angles_page = '/recognitions/dev/faces.php';
	public const FACES_ANGLES_NUM = "8";
	/*
	public const shoe_angles_page = '/recognitions/dev/SessionStarter.php'; // test  5 cover~!
	public const SHOE_ANGLES_NUM = "7";
	public const faces_angles_page = '/recognitions/dev/SessionStarter1.php'; // test 6 cover~!
	public const FACES_ANGLES_NUM = "8";
	*/
	public const global_page = '/recognitions/dev/global.php';
	public const GLOBAL_NUM = "9";
	public const local_page = '/recognitions/dev/local.php';
	public const LOCAL_NUM = "10";
	public const expressions_page = '/recognitions/dev/expressions.php';
	public const EXPRESSIONS_NUM = "11";
	public const final_page = '/recognitions/dev/final.php';
	public const FINAL_NUM = "12";
}
class DBi{
	private $sql_link;
	public function connect2sqli(){
		if(is_resource($this->sql_link) && get_resource_type($this->sql_link) === 'mysql link' && $this->sql_link->connect_errno == 0)
		{
			return $this->sql_link;
		}
		else{
			$this->sql_link = new mysqli('127.0.0.1','your-db-domain-name','your-db-password','your-db-domain-name');
			if(!$this->sql_link || $this->sql_link->connect_errno != 0)
			{
				unset($this->$sql_link);
				return false;
			}
			else
			{
				return $this->sql_link;
			}
		}
	}
	public function make_famous_vectors()
	{
		$imgs = $this->sql_link->query("SELECT * FROM `famous_pictures`");
		$arr = array();
		$correct_img_names = array();
		$img_gender = array();
		$last_val = "";
		$last_img_name = "";
		$last_img_gender = "";
		while(($vec = $imgs->fetch_array(MYSQLI_ASSOC)))
		{
			// $arr[] = $vec['img'];
			array_push($arr,$vec['img'].",");
			$last_val = $vec['img'];
			array_push($correct_img_names,$vec['name'].",");
			$last_img_name = $vec['name'];
			array_push($img_gender,$vec['gender'].",");
			$last_img_gender = $vec['gender'];
		}
		$arr[count($arr)-1] = $last_val;
		$img_count = count($arr)-1;
		$correct_img_names[count($correct_img_names)-1] = $last_img_name;
		$img_gender[count($img_gender)-1] = $last_img_gender;
		$arr = implode($arr);// json_encode($arr);
		$correct_img_names = implode($correct_img_names);
		$img_gender = implode($img_gender);
		$ret = array($img_count,$arr,$correct_img_names,$img_gender);
		return $ret;
	}
	public function make_similarity_vectors($task_type)
	{
		$img = "";
		if(strcmp($task_type,"UP") == 0)
		{
			$imgs = $this->sql_link->query("SELECT * FROM `faces_pictures` WHERE `inverted`='no'");
		}
		else if(strcmp($task_type,"INV") == 0)
		{
			$imgs = $this->sql_link->query("SELECT * FROM `faces_pictures` WHERE `inverted`='yes'");
		}
		$arr_a = array();
		$arr_b = array();
		$last_val_a = "";
		$last_val_b = "";
		while(($vec = $imgs->fetch_array(MYSQLI_ASSOC)))
		{
			// $arr[] = $vec['img'];
			array_push($arr_a,$vec['picture_a'].",");
			$last_val_a = $vec['picture_a'];
			array_push($arr_b,$vec['picture_b'].",");
			$last_val_b = $vec['picture_b'];
		}
		$arr_a[count($arr_a)-1] = $last_val_a;
		$arr_b[count($arr_b)-1] = $last_val_b;
		$img_count_a = count($arr_a)-1;
		$img_count_b = count($arr_b)-1;
		$arr_a = implode($arr_a);// json_encode($arr);
		$arr_b = implode($arr_b);// json_encode($arr);
		$ret = array($img_count_a,$arr_a,$img_count_b,$arr_b);
		return $ret;
	}
	public function make_shoes_vectors() {
		$matrix1 = $this->sql_link->query("SELECT * FROM `shoesType1`");
		$vec1 = $this->getType1($matrix1);
		//$a = print_r($vec1);
		$matrix2 = $this->sql_link->query("SELECT * FROM `shoesType2`");
		$vec2 = $this->getType23($matrix2);
		//$b = print_r($vec2);
		$matrix3 = $this->sql_link->query("SELECT * FROM `shoesType3`");
		$vec3 = $this->getType23($matrix3);
		//$c = print_r($vec3);
		//exit($a."\n".$b."\n".$c);
		return array($vec1,$vec2,$vec3);
	}
	public function make_faces_vectors() {
		$matrix1 = $this->sql_link->query("SELECT * FROM `facesType1`");
		$vec1 = $this->getType1($matrix1);
		//$a = print_r($vec1);
		$matrix2 = $this->sql_link->query("SELECT * FROM `facesType2`");
		$vec2 = $this->getType23($matrix2);
		//$b = print_r($vec2);
		$matrix3 = $this->sql_link->query("SELECT * FROM `facesType3`");
		$vec3 = $this->getType23($matrix3);
		//$c = print_r($vec3);
		//exit($a."\n".$b."\n".$c);
		$res = array($vec1,$vec2,$vec3);
		return $res;
	}
	private function getType23($matrix) {
		$arr_preview = array();
		$last_val_preview = "";
		$arr_questions = array();
		$last_val_questions = "";
		while(($vec = $matrix->fetch_array(MYSQLI_ASSOC))) {
			array_push($arr_preview,$vec['image'].",");
			$last_val_preview = $vec['image'];
			array_push($arr_questions,$vec['correctAnswer'].",");
			$last_val_questions = $vec['correctAnswer'];
		}
		$arr_preview[count($arr_preview)-1] = $last_val_preview;
		$arr_questions[count($arr_questions)-1] = $last_val_questions;
		
		$img_count_preview = count($arr_preview)-1;
		$img_count_questions = count($arr_questions)-1;
		$arr_preview = implode($arr_preview);// json_encode($arr);
		$arr_questions = implode($arr_questions);// json_encode($arr);
		$ret = array($img_count_preview,$arr_preview,$img_count_questions,$arr_questions);
		return $ret;
	}
	private function getType1($matrix) {
		$arr_preview = array();
		$last_val_preview = "";
		$arr_questions = array();
		$last_val_questions = "";
		$arr_correct_ans = array();
		$last_val_correct_ans = "";
		$arr_type = array();
		$arr_type_val = "";
		while(($vec = $matrix->fetch_array(MYSQLI_ASSOC))) {
			array_push($arr_preview,$vec['previewImg1'].",");
			array_push($arr_preview,$vec['previewImg2'].",");
			array_push($arr_preview,$vec['previewImg3'].",");
			$last_val_preview = $vec['previewImg3'];
			array_push($arr_questions,$vec['question1'].",");
			array_push($arr_questions,$vec['question2'].",");
			array_push($arr_questions,$vec['question3'].",");
			$last_val_questions	 = $vec['question3'];
			array_push($arr_correct_ans,$vec['correctAnswer1'].",");
			array_push($arr_correct_ans,$vec['correctAnswer2'].",");
			array_push($arr_correct_ans,$vec['correctAnswer3'].",");
			$last_val_correct_ans = $vec['correctAnswer3'];
			array_push($arr_type,$vec['type'].",");
			$arr_type_val = $vec['type'];
		}
		$arr_preview[count($arr_preview)-1] = $last_val_preview;
		$arr_questions[count($arr_questions)-1] = $last_val_questions;
		$arr_correct_ans[count($arr_correct_ans)-1] = $last_val_correct_ans;
		$arr_type[count($arr_type)-1] = $arr_type_val;
		
		$img_count_preview = count($arr_preview)-1;
		$img_count_questions = count($arr_questions)-1;
		$img_count_correct_ans = count($arr_correct_ans)-1;
		$img_count_type = count($arr_type)-1;
		$arr_preview = implode($arr_preview);// json_encode($arr);
		$arr_questions = implode($arr_questions);// json_encode($arr);
		$arr_correct_ans = implode($arr_correct_ans);// json_encode($arr);
		$arr_type = implode($arr_type);// json_encode($arr);
		$ret = array($img_count_preview,$arr_preview,$img_count_questions,$arr_questions,$img_count_correct_ans,$arr_correct_ans,$img_count_type,$arr_type);
		return $ret;
	}
	public function make_global_local_vectors($task_type)
	{
		$imgs = $this->sql_link->query("SELECT * FROM `global_local`");
		$arr_a = array();
		$arr_b = array(); // correct
		$arr_c = array();
		$last_val_a = "";
		$last_val_b = "";
		$last_val_c = "";
		while(($vec = $imgs->fetch_array(MYSQLI_ASSOC)))
		{
			// $arr[] = $vec['img'];
			array_push($arr_a,$vec['image'].",");
			$last_val_a = $vec['image'];
			array_push($arr_b,$vec['shape_out'].",");
			$last_val_b = $vec['shape_out'];
			array_push($arr_c,$vec['shape_in'].",");
			$last_val_c = $vec['shape_in'];
		}
		$arr_a[count($arr_a)-1] = $last_val_a;
		$arr_b[count($arr_b)-1] = $last_val_b;
		$arr_c[count($arr_c)-1] = $last_val_c;
		$img_count_a = count($arr_a)-1;
		$img_count_b = count($arr_b)-1;
		$img_count_c = count($arr_c)-1;
		$arr_a = implode($arr_a);// json_encode($arr);;
		$arr_b = implode($arr_b);// json_encode($arr);
		$arr_c = implode($arr_c);// json_encode($arr);
		$ret = array($img_count_a,$arr_a,$img_count_b,$arr_b,$img_count_c,$arr_c);
		return $ret;
	}
	public function make_expressions_vectors()
	{
		$imgs = $this->sql_link->query("SELECT * FROM `expressions_pictures`");
		$arr_a = array();
		$arr_b = array(); // correct
		$arr_c = array();
		$arr_d = array();
		$last_val_a = "";
		$last_val_b = "";
		$last_val_c = "";
		$last_val_d = "";
		while(($vec = $imgs->fetch_array(MYSQLI_ASSOC)))
		{
			// $arr[] = $vec['img'];
			array_push($arr_a,$vec['picture_a'].",");
			$last_val_a = $vec['picture_a'];
			array_push($arr_b,$vec['picture_b'].",");
			$last_val_b = $vec['picture_b'];
			array_push($arr_c,$vec['picture_c'].",");
			$last_val_c = $vec['picture_c'];
			// array_push($arr_c,$vec['correct'].",");
			// $last_val_c = $vec['correct'];
		}
		$arr_a[count($arr_a)-1] = $last_val_a;
		$arr_b[count($arr_b)-1] = $last_val_b;
		$arr_c[count($arr_c)-1] = $last_val_c;
		// $arr_d[count($arr_d)-1] = $last_val_d;
		$img_count_a = count($arr_a)-1;
		$img_count_b = count($arr_b)-1;
		$img_count_c = count($arr_c)-1;
		// $img_count_d = count($arr_c)-1;
		$arr_a = implode($arr_a);// json_encode($arr);
		$arr_b = implode($arr_b);// json_encode($arr);
		$arr_c = implode($arr_c);// json_encode($arr);
		// $arr_d = implode($arr_d);// json_encode($arr);
		$ret = array($img_count_a,$arr_a,$img_count_b,$arr_b,$img_count_c,$arr_c); // ,$img_count_d,$arr_d);
		return $ret;
	}
	public function make_permutation($count)
	{
		$permut = range(0,$count);
		shuffle($permut);
		$permut = json_encode($permut);
		return $permut;
	}
	
	public function where_to_link($status)
	{
		$addr = "";
		switch($status)
		{
			case Constants::INDEX_NUM:			$addr = Constants::index_page;
												break;
			case Constants::DETAILS_NUM:		$addr = Constants::details_page;
												break;
			case Constants::QUESTIONS_NUM:		$addr = Constants::questions_page;
												break;
			case Constants::SCALING_NUM:		$addr = Constants::scaling_page;
												break;
			case Constants::FAMOUS_NUM:			$addr = Constants::famous_page;
												break;
			case Constants::SIMILARITY_UP_NUM:	$addr = Constants::similarity_page_up;
												break;
			case Constants::SIMILARITY_INV_NUM:	$addr = Constants::similarity_page_inv;
												break;
			case Constants::SHOE_ANGLES_NUM:	$addr = Constants::shoe_angles_page;
												break;
			case Constants::FACES_ANGLES_NUM:	$addr = Constants::faces_angles_page;
												break;
			case Constants::GLOBAL_NUM:			$addr = Constants::global_page;
												break;
			case Constants::LOCAL_NUM:			$addr = Constants::local_page;
												break;
			case Constants::EXPRESSIONS_NUM:	$addr = Constants::expressions_page;
												break;
			case Constants::FINAL_NUM:			$addr = Constants::final_page;
												break;
			default: $addr = Constants::index_page;
		}
		return $addr;
	}
}
?>
