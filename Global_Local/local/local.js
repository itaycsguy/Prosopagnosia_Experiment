var Functions = {
	"new_permut":false,
	"need_feedback":false,
	"stage":0,
	"clock":0,
	"gender":"בן",
	"hand":"ימין",
	"img_width":300,
	"permut":[],
	"img_idx":0,
	"imgs_a":[],
	"imgs_b":[],
	"numbers_to_train":	10,
	"breaks_num":0,
	"clock_begin":function(){
		Functions.clock = Date.now();
	},
	"clock_stop":function(){
		Functions.clock = Date.now() - Functions.clock;
	},
	"countDown":function(){
		var countdown = 20;
		setInterval(function(){
			if(countdown > 0)
			{
				--countdown;
			}
			else
			{
				return;
			}
		},1000);
	},
	"putGIF":function(){
		var elem = document.getElementById("frame");
		setTimeout(function(){
			if(Functions.gender == "בן")
			{
				elem.innerHTML = "<h2><strong>לחץ על רווח בכדי להתחיל באימון.</strong></h2>";
				document.addEventListener("keydown",Functions.beginTask);
			}
			else if(Functions.gender == "בת")
			{
				elem.innerHTML = "<h2><strong>לחצי על רווח בכדי להתחיל באימון.</strong></h2>";
				document.addEventListener("keydown",Functions.beginTask);
			}
		},21000); // 21 sec included 0
	},
	"preload":function(){
		for(var i = 0;i < Functions.imgs_a.length;i++)
		{
			var myImgPath_a = "/recognitions/global_local/"+Functions.imgs_a[Functions.permut[i]];
			var img_a = new Image();
			img_a.src = myImgPath_a; // make the preload
		}
	},
	"beginTask":function(event){
		if(event.keyCode == 32) // space key is pressed.
		{
			document.removeEventListener("keydown",Functions.beginTask);
			Functions.changeContent();
		}
	},
	"changeContent":function(){
		var str = "<tr><td><h2><strong>מהי הצורה הקטנה?</strong></h2>";
		str += "</td></tr><tr><td><img id='curr_img1' alt='shape'/></td></tr>";
		if(Functions.hand == "ימין")
		{
			str += "<tr style='text-align: center;'><td><strong><p><img src='/recognitions/dev/arrow_assign_sq_z.png'></p></strong></td></tr>";
			str += "<tr style='text-align: center;'><td><strong><p><img src='/recognitions/dev/arrow_assign_cy_m.png'></p></strong></td></tr>";
		}
		else
		{
			str += "<tr style='text-align: center;'><td><strong><p><img src='/recognitions/dev/arrow_assign_sq_m.png'></p></strong></td></tr>";
			str += "<tr style='text-align: center;'><td><strong><p><img src='/recognitions/dev/arrow_assign_cy_z.png'></p></strong></td></tr>";
		}
		document.getElementById("table").innerHTML = str;
		var currImg1 = document.getElementById("curr_img1");
		currImg1.src = "/recognitions/global_local/"+Functions.imgs_a[Functions.permut[Functions.img_idx]];
		currImg1.width = Functions.img_width;
		currImg1.height = Functions.img_width;
		document.addEventListener("keydown",Form.ajax);
		Functions.clock_begin();
	},
	"uploadRest":function(){
		var str = "";
		str += "<tr><td><h1 class='h1_style'><strong>הפסקה! </strong></h1></td></tr>";
		if(Functions.breaks_num > 0)
		{
			str += "<tr><td><h4 class='h4_style'>תזכורת:</h4></td></tr>";
			str += "<tr><td><h4>במטלה תופיע צורה גדולה שמורכבת מצורות קטנות</h4></td></tr>";
			str += "<tr><td><h4>עליך להחליט מהי הצורה של הצורה הקטנה</h4></td></tr>";
		}
		str += "<tr><td><h4>להמשך הניסוי:</h4></td></tr>";
		if(Functions.gender == "בן")
		{
			str += "<tr><td><img alt='לחץ רווח' src='/recognitions/dev/gif_male.gif'/></td></tr>";
		}
		else if(Functions.gender == "בת")
		{
			str += "<tr><td><img alt='לחץ רווח' src='/recognitions/dev/gif_female.gif'/></td></tr>";
		}
		Functions.breaks_num++;
		document.getElementById("table").innerHTML = str;
		document.addEventListener("keydown",Functions.beginTask);
	},
	"putFeedback":function(event){
		var str = "<tr><td><h1 class='h1_style' style='padding-top:4em;'><strong>";
		var correct_flag = false;
		console.log(Functions.imgs_b[Functions.permut[Functions.img_idx]]);
		if(Functions.imgs_b[Functions.permut[Functions.img_idx-1]] == "ריבוע")
		{
			console.log(event.keyCode);
			if(Functions.hand == "ימין")
			{
				if(event.keyCode == 90)
				{
					correct_flag = true;
				}
			}
			else if(Functions.hand == "שמאל")
			{
				if(event.keyCode == 77)
				{
					correct_flag = true;
				}
			}
		}
		else if(Functions.imgs_b[Functions.permut[Functions.img_idx-1]] == "עיגול")
		{
			console.log(event.keyCode);
			if(Functions.hand == "ימין")
			{
				if(event.keyCode == 77)
				{
					correct_flag = true;
				}
			}
			else if(Functions.hand == "שמאל")
			{
				if(event.keyCode == 90)
				{
					correct_flag = true;
				}
			}
		}
		if(correct_flag == true)
		{
			str += "נכון!";
		}
		else
		{
			str += "לא נכון!";
		}
		str += "</strong></h1></td></tr>";
		document.getElementById("table").innerHTML = str;
		setTimeout(function(){
			var doctype_data = document.getElementById("fix").innerHTML;
			var object = document.getElementById('fix');
			object.innerHTML = "";
			object.style.backgroundColor = "#dcdcdc";
			object.innerHTML = '<div class="layout-center-3"><img src="/recognitions/dev/fixation.png" width="150px" height="150px" style="padding-top:11.5em;"/></div>';
			setTimeout(function(){
				setTimeout(function(){
					object.innerHTML = doctype_data;
					Functions.changeContent();
				},500);
			},200);
		},1500);
	}
};
var Form = {
	"ajax":function(event){
		document.removeEventListener("keydown",Form.ajax);
		window.blur();
		Functions.clock_stop();
		if(event.keyCode != 90 && event.keyCode != 77)
		{
			document.addEventListener("keydown",Form.ajax);
			return;
		}
		var isTraining = 0; // false
		Functions.need_feedback = false;
		var img1 = Functions.imgs_a[Functions.permut[Functions.img_idx]];
		var correctAns = Functions.imgs_b[Functions.permut[Functions.img_idx]];
		console.log("img1="+img1+",correctAns="+correctAns);
		if(Functions.img_idx < Functions.numbers_to_train && Functions.stage < 3) // 3 default
		{
			isTraining = 1; // true
			Functions.need_feedback = true;
		}
		var packeta = "answers="+JSON.stringify([event.keyCode,img1,correctAns,isTraining,Functions.clock])+"&count="+Functions.img_idx+"&max="+(Functions.imgs_a.length-1)+"&stage="+Functions.stage+"&num_rows="+(Functions.imgs_a.length-1);
		if(Functions.new_permut == true){
			packeta += "&permut="+Functions.permut;
			Functions.new_permut = false;
		}
		var httpc = new XMLHttpRequest();	
		console.log("packeta =  "+packeta);
		var url = "/recognitions/dev/local.php";
		httpc.open("POST", url, true);
		httpc.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		var err_flag = false;
		httpc.onreadystatechange = function(){
			if(httpc.readyState == 4 && httpc.status == 200)
			{
				var addr = httpc.responseText.split(",");
				if(addr[0] == "need_revalidation")
				{
					window.location.href = addr[1];
					return;
				}
				else if(Functions.stage == 29 && Functions.img_idx == (Functions.imgs_a.length-1))
				{
					window.location.href = httpc.responseText;
					return;
				}
				else if(Functions.img_idx == (Functions.imgs_a.length-1))
				{
					Functions.stage++;
					if(Functions.need_feedback == true)
					{
						Functions.img_idx++;
						Functions.putFeedback(event);
						Functions.img_idx = 0;
					}
					else
					{
						Functions.img_idx = 0;
						Functions.permut = shuffleOf(Functions.permut);
						Functions.new_permut = true;
						var doctype_data = document.getElementById("fix").innerHTML;
						var object = document.getElementById('fix');
						object.innerHTML = "";
						//object.style.backgroundColor = "black";
						object.style.backgroundColor = "#dcdcdc";
						object.innerHTML = '<div class="layout-center-3"><img src="/recognitions/dev/fixation.png" width="150px" height="150px" style="padding-top:11.5em;"/></div>';
						setTimeout(function(){
							setTimeout(function(){
								object.innerHTML = doctype_data;
								Functions.changeContent();
							},500);
						},200);
					}
				}
				else
				{
					Functions.img_idx++;
					if(Functions.need_feedback == true)
					{
						Functions.putFeedback(event);
					}
					else
					{
						var restFlag = false;
						for(var j = 1;j <= 12;j++)
						{
							if(Functions.stage == 4*j)
							{
								restFlag = true;
								j = 13;
							}
						}
						if(restFlag == true && Functions.img_idx == 1)
						{
							Functions.uploadRest();
						}
						else
						{
							var doctype_data = document.getElementById("fix").innerHTML;
							var object = document.getElementById('fix');
							object.innerHTML = "";
							object.style.backgroundColor = "#dcdcdc";
							object.innerHTML = '<div class="layout-center-3"><img src="/recognitions/dev/fixation.png" width="150px" height="150px" style="padding-top:11.5em;"/></div>';
							setTimeout(function(){
								setTimeout(function(){
									object.innerHTML = doctype_data;
									Functions.changeContent();
								},500);
							},200);
						}
					}
				}
			}
		}
		httpc.send(packeta);
	},
	"transfer":function(){
		window.location.href = "/recognitions/dev/details.php?returnTask='9'";
	}
};