var Functions = {
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
	"imgs_c":[],
	"imgs_d":[],
	"numbers_to_train":	4,
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
				//document.getElementById("countdown").innerHTML = --countdown;
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
			//elem.innerHTML = "<img id='img'/>";
			//var img_item = document.getElementById("img");
			//document.getElementById("wait").innerHTML = "בכדי להתחיל באימון:";
			if(Functions.gender == "בן")
			{
				elem.innerHTML = "<h2><strong>לחץ על רווח בכדי להתחיל באימון.</strong></h2>";
				//img_item.src = "/recognitions/dev/gif_male.gif";
				//img_item.alt = "לחץ רווח";
				document.addEventListener("keydown",Functions.beginTask);
			}
			else if(Functions.gender == "בת")
			{
				elem.innerHTML = "<h2><strong>לחצי על רווח בכדי להתחיל באימון.</strong></h2>";
				//img_item.src = "/recognitions/dev/gif_female.gif";
				//img_item.alt = "לחצי רווח";
				document.addEventListener("keydown",Functions.beginTask);
			}
		},21000); // 21 sec included 0
	},
	"preload":function(){
		for(var i = 0;i < Functions.imgs_a.length;i++)
		{
			var myImgPath_a = "/recognitions/different_expressions/"+Functions.imgs_a[Functions.permut[i]];
			var myImgPath_b = "/recognitions/different_expressions/"+Functions.imgs_b[Functions.permut[i]];
			var myImgPath_c = "/recognitions/different_expressions/"+Functions.imgs_c[Functions.permut[i]];
			var img_a = new Image();
			var img_b = new Image();
			var img_c = new Image();
			img_a.src = myImgPath_a; // make the preload
			img_b.src = myImgPath_b; // make the preload
			img_c.src = myImgPath_c; // make the preload
		}
	},
	"beginTask":function(event){
		if(event.keyCode == 32) // space key is pressed.
		{
			document.removeEventListener("keydown",Functions.beginTask);
			// document.getElementById("inp_to_edit").disabled = true;
			Functions.changeContent();
		}
	},
	"changeContent":function(){
		var str = "<tr><td><h2><strong>מי מבין התמונות התחתונות מרגיש אותו רגש כמו האדם בתמונה העליונה? </td></strong></h2>";
		str += "<tr><td><img id='curr_img_top' alt='shape'/></td></tr>";
		str += "<tr><td><img id='curr_img_left' alt='shape' style='padding-left:5px;padding-right:5px;'/><img id='curr_img_right' alt='shape' style='padding-left:5px;padding-right:5px;'/></td></tr>";
		if(Functions.hand == "ימין")
		{
			str += "<tr style='text-align: center;'><td><strong><label>ימין - צ'</label></strong></td></tr>";
			str += "<tr style='text-align: center;'><td><strong><label>שמאל - ז'</label></strong></td></tr>";
		}
		else
		{
			str += "<tr style='text-align: center;'><td><strong><label>ימין - ז'</label></strong></td></tr>";
			str += "<tr style='text-align: center;'><td><strong><label>שמאל - צ'</label></strong></td></tr>";
		}
		document.getElementById("table").innerHTML = str;
		var curr_img_top = document.getElementById("curr_img_top");
		var curr_img_left = document.getElementById("curr_img_left");
		var curr_img_right = document.getElementById("curr_img_right");
		curr_img_top.src = "/recognitions/different_expressions/"+Functions.imgs_a[Functions.permut[Functions.img_idx]];
		curr_img_left.src = "/recognitions/different_expressions/"+Functions.imgs_b[Functions.permut[Functions.img_idx]];
		curr_img_right.src = "/recognitions/different_expressions/"+Functions.imgs_c[Functions.permut[Functions.img_idx]];
		// console.log(Functions.img_width);
		curr_img_top.width = Functions.img_width;
		curr_img_top.height = Functions.img_width;
		curr_img_left.width = Functions.img_width;
		curr_img_left.height = Functions.img_width;
		//curr_img_left.style.paddingRight = Functions.img_width/2;
		curr_img_right.width = Functions.img_width;
		curr_img_right.height = Functions.img_width;
		//curr_img_right.style.paddingLeft = Functions.img_width/2;
		document.addEventListener("keydown",Form.ajax);
		Functions.clock_begin();
	},
	"uploadRest":function(){
		// document.getElementById("inp_to_edit").disabled = false;
		var str = "";
		str += "<tr><td><h1 class='h1_style'><strong>הפסקה! </strong></h1></td></tr>";
		str += "<tr><td><h4 class='h4_style'>להמשך הניסוי:</h4></td></tr>";
		if(Functions.gender == "בן")
		{
			str += "<tr><td><img alt='לחץ רווח' src='/recognitions/dev/gif_male.gif'/></td></tr>";
		}
		else if(Functions.gender == "בת")
		{
			str += "<tr><td><img alt='לחץ רווח' src='/recognitions/dev/gif_female.gif'/></td></tr>";
		}
		document.getElementById("table").innerHTML = str;
		document.addEventListener("keydown",Functions.beginTask);
	},
	"putFeedback":function(event){
		var str = "<tr><td><h1 class='h1_style' style='padding-top:6em;'><strong>";
		var correct_flag = false;
		if(Functions.imgs_d[Functions.img_idx-1] == "ימין")
		{
			if(event.keyCode == 77)
			{
				correct_flag = true;
			}
		}
		else if(Functions.imgs_d[Functions.img_idx-1] == "שמאל")
		{
			if(event.keyCode == 90)
			{
				correct_flag = true;
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
		//document.getElementById("table").innerHTML = str;
		/*
		setTimeout(function(){
			Functions.changeContent();
		},1500);
		*/
		var doctype_data = document.getElementById("fix").innerHTML;
		var object = document.getElementById('fix');
		object.innerHTML = "";
		//object.style.backgroundColor = "black";
		object.style.backgroundColor = "#dcdcdc";
		object.innerHTML = '<div class="layout-center-3"><img src="/recognitions/dev/fixation.png" width="150px" height="150px" style="padding-top:20em;padding-left:0px;"/></div>';
		setTimeout(function(){
			setTimeout(function(){
				object.innerHTML = doctype_data;
				Functions.changeContent();
			},500);
		},200);
	}
};
var Form = {
	"ajax":function(event){
		document.removeEventListener("keydown",Form.ajax);
		window.blur();
		Functions.clock_stop();
		// alert(event.keyCode);
		if(event.keyCode != 90 && event.keyCode != 77)
		{
			document.addEventListener("keydown",Form.ajax);
			return;
		}
		var isTraining = 0; // false
		Functions.need_feedback = false;
		var img1 = Functions.imgs_a[Functions.permut[Functions.img_idx]];
		var img2 = Functions.imgs_b[Functions.permut[Functions.img_idx]];
		var img3 = Functions.imgs_c[Functions.permut[Functions.img_idx]];
		var correctAns = Functions.imgs_d[Functions.permut[Functions.img_idx]];
		if(Functions.img_idx < Functions.numbers_to_train && Functions.stage == 0) // 3 default
		{
			isTraining = 1; // true
			Functions.need_feedback = true;
		}
		// console.log("Functions.img_idx = "+Functions.img_idx+" <-> Functions.numbers_to_train = "+Functions.numbers_to_train+" <-> Functions.stage = "+Functions.stage);
		var packeta = "answers="+JSON.stringify([event.keyCode,img1,img2,img3,correctAns,isTraining,Functions.clock])+"&count="+Functions.img_idx+"&max="+(Functions.imgs_a.length-1)+"&stage="+Functions.stage+"&num_rows="+(Functions.imgs_a.length-1);
		var httpc = new XMLHttpRequest();
		var url = "/recognitions/dev/expressions.php";
		httpc.open("POST", url, true);
		httpc.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		// var err_flag = false;
		httpc.onreadystatechange = function(){
			// console.log("httpc.readyState = "+httpc.readyState+", httpc.status = "+httpc.status);
			/*
			if(httpc.status == 500)
			{
				err_flag = true;
				Form.ajax(event);
				return;
			}
			*/
			if(httpc.readyState == 4 && httpc.status == 200)
			{
				// console.log(httpc.responseText);
				var addr = httpc.responseText.split(",");
				console.log(addr[0]);
				if(addr[0] == "need_revalidation")
				{
					window.location.href = addr[1];
					return;
				}
				else if(Functions.stage == 2 && Functions.img_idx == (Functions.imgs_a.length-1))
				{
					window.location.href = httpc.responseText;
					return;
				}
				else if(Functions.img_idx == (Functions.imgs_a.length-1))
				{
					// console.log("stage = "+Functions.stage+", num_idx = "+Functions.img_idx);
					Functions.stage++;
					if(Functions.need_feedback == true)
					{
						Functions.putFeedback(event);
						Functions.img_idx = 0;
					}
					else
					{
						Functions.img_idx = 0;
						Functions.permut = shuffleOf(Functions.permut);
						var doctype_data = document.getElementById("fix").innerHTML;
						var object = document.getElementById('fix');
						object.innerHTML = "";
						//object.style.backgroundColor = "black";
						object.style.backgroundColor = "#dcdcdc";
						object.innerHTML = '<div class="layout-center-3"><img src="/recognitions/dev/fixation.png" width="150px" height="150px" style="padding-top:20em;padding-left:0px;"/></div>';
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
					// if(err_flag == false)
					Functions.img_idx++;
					if(Functions.need_feedback == true)
					{
						Functions.putFeedback(event);
					}
					else
					{
						if(Functions.stage == 1 && Functions.img_idx == 1)
						{
							Functions.uploadRest();
						}
						else
						{
							var doctype_data = document.getElementById("fix").innerHTML;
							var object = document.getElementById('fix');
							object.innerHTML = "";
							//object.style.backgroundColor = "black";
							object.style.backgroundColor = "#dcdcdc";
							object.innerHTML = '<div class="layout-center-3"><img src="/recognitions/dev/fixation.png" width="150px" height="150px" style="padding-top:20em;padding-left:0px;"/></div>';
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
		window.location.href = "/recognitions/dev/details.php?returnTask='10'";
	}
};