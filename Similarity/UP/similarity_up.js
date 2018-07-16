
var Functions = {
	"stage":0,
	"clock":0,
	"gender":"בן",
	"hand":"ימין",
	"img_width":300,
	"permut":[],
	"img_idx":0,
	"imgs_a":[],
	"imgs_b":[],
	"pairOrderArr":[],
	"numbers_to_train":6,
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
				document.getElementById("countdown").innerHTML = --countdown;
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
				elem.innerHTML = "<h2><strong>נא לחצי על רווח בכדי להתחיל באימון!</strong></h2>";
				//img_item.src = "/recognitions/dev/gif_female.gif";
				//img_item.alt = "לחצי רווח";
				document.addEventListener("keydown",Functions.beginTask);
			}
		},21000); // 21 sec included 0
	},
	"preload":function(){
		for(var i = 0;i < Functions.imgs_a.length;i++)
		{
			var myImgPath_a = "/recognitions/faces_pictures/"+Functions.imgs_a[Functions.permut[i]];
			var myImgPath_b = "/recognitions/faces_pictures/"+Functions.imgs_b[Functions.permut[i]];
			var img_a = new Image();
			var img_b = new Image();
			img_a.src = myImgPath_a; // make the preload
			img_b.src = myImgPath_b; // make the preload
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
		var str = "<tr><td><h2><strong>האם הפרצופים זהים או שונים? </strong></h2>";
		str += "</td></tr><tr><td><img id='curr_img1' alt='face'/></td><td><img id='curr_img2' alt='face'/></td></tr>";
		str += "<tr><td style='padding-top:3em'></td></tr>";
		if(Functions.hand == "ימין")
		{
			str += "<tr style='text-align: left;'><td><strong><label>זהים - ז'</label></strong></td></tr>";
			str += "<tr style='text-align: left;'><td><strong><label>שונים - צ'</label></strong></td></tr>";
		}
		else
		{
			str += "<tr style='text-align: left;'><td><strong><label>זהים - צ'</label></strong></td></tr>";
			str += "<tr style='text-align: left;'><td><strong><label>שונים - ז'</label></strong></td></tr>";
		}
		document.getElementById("table").innerHTML = str;
		var currImg1 = document.getElementById("curr_img1");
		var currImg2 = document.getElementById("curr_img2");
		try{
			if(Functions.pairOrderArr[Functions.img_idx] == 0)
			{
				currImg1.src = "/recognitions/faces_pictures/"+Functions.imgs_a[Functions.permut[Functions.img_idx]];
				currImg2.src = "/recognitions/faces_pictures/"+Functions.imgs_b[Functions.permut[Functions.img_idx]];
			}
			else // this is: Functions.pairOrderArr[Functions.img_idx] == 1
			{
				currImg2.src = "/recognitions/faces_pictures/"+Functions.imgs_a[Functions.permut[Functions.img_idx]];
				currImg1.src = "/recognitions/faces_pictures/"+Functions.imgs_b[Functions.permut[Functions.img_idx]];
			}
		}
		catch(err)
		{
			if(Math.random() < 0.5)
			{
				Functions.pairOrderArr.push(0); // 0 = left arr.
				currImg1.src = "/recognitions/faces_pictures/"+Functions.imgs_a[Functions.permut[Functions.img_idx]];
				currImg2.src = "/recognitions/faces_pictures/"+Functions.imgs_b[Functions.permut[Functions.img_idx]];
			}
			else
			{
				Functions.pairOrderArr.push(1); // 1 = right arr.
				currImg2.src = "/recognitions/faces_pictures/"+Functions.imgs_a[Functions.permut[Functions.img_idx]];
				currImg1.src = "/recognitions/faces_pictures/"+Functions.imgs_b[Functions.permut[Functions.img_idx]];	
			}	
		}
		// console.log(Functions.img_width);
		currImg1.width = Functions.img_width;
		currImg1.height = Functions.img_width;
		// console.log(Functions.img_width);
		currImg2.width = Functions.img_width;
		currImg2.height = Functions.img_width;
		
		//document.getElementById("table").style.top = (window.innerHeight/2 + 1)-(Functions.img_width/2) + "px";
		document.addEventListener("keydown",Form.ajax);
		Functions.clock_begin();
	},
	"uploadRest":function(){
		// document.getElementById("inp_to_edit").disabled = false;
		var str = "";
		str += "<tr><td><h1 class='h1_style'><strong>הפסקה! </strong></h1></td></tr>";
		str += "<tr><td><h4 class='h4_style'>תזכורת להוראות המטלה:</h4></td></tr>";
		if(Functions.gender == "בן")
		{
			str += "<tr><td><h4>במטלה הזו יוצגו בפניך שתי תמונות של פנים, אחת ליד השנייה ותתבקש להחליט האם התמונות זהות או שונות.</h4></td></tr>";
			if(Functions.hand == "ימין")
			{
				str += "<tr><td><h4>אם הן זהות לחץ על <img alt='ז' src='/recognitions/dev/z.png' width='30px' height='30px'/></h4></td></tr>";
				str += "<tr><td><h4>אם הן שונות לחץ על <img alt='צ' src='/recognitions/dev/m.png' width='30px' height='30px'/></h4></td></tr>";
			}
			else
			{
				str += "<tr><td><h4>אם הן זהות לחץ על <img alt='צ' src='/recognitions/dev/m.png' width='30px' height='30px'/></h4></td></tr>";
				str += "<tr><td><h4>אם הן שונות לחץ על <img alt='ז' src='/recognitions/dev/z.png' width='30px' height='30px'/></h4></td></tr>";
			}
		}
		else
		{
			str += "<tr><td><h4>במטלה זו יוצגו בפנייך שתי תמונות של פנים, אחת ליד השנייה ותתבקשי להחליט האם התמונות זהות או שונות.</h4></td></tr>";
			if(Functions.hand == "ימין")
			{
				str += "<tr><td><h4>אם הן זהות לחצי על <img alt='ז' src='/recognitions/dev/z.png' width='20px' height='20px'/></h4></td></tr>";
				str += "<tr><td><h4>אם הן שונות לחצי על <img alt='צ' src='/recognitions/dev/m.png' width='30px' height='30px'/></h4></td></tr>";
			}
			else
			{
				str += "<tr><td><h4>אם הן זהות לחצי על <img alt='צ' src='/recognitions/dev/m.png' width='30px' height='30px'/></h4></td></tr>";
				str += "<tr><td><h4>אם הן שונות לחצי על <img alt='ז' src='/recognitions/dev/z.png' width='30px' height='30px'/></h4></td></tr>";
			}
		}
		str += "<tr><td><h4>למעבר להמשך הניסוי:</h4></td></tr>";
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
		var img1 = "";
		var img2 = "";
		if(Functions.pairOrderArr[Functions.img_idx] == 0)
		{
			img1 = Functions.imgs_a[Functions.permut[Functions.img_idx]];
			img2 = Functions.imgs_b[Functions.permut[Functions.img_idx]];
		}
		else
		{
			img2 = Functions.imgs_a[Functions.permut[Functions.img_idx]];
			img1 = Functions.imgs_b[Functions.permut[Functions.img_idx]];
		}
		if(Functions.img_idx < Functions.numbers_to_train && Functions.stage == 0) // 3 default
			isTraining = 1; // true
		// console.log("Functions.img_idx = "+Functions.img_idx+" <-> Functions.numbers_to_train = "+Functions.numbers_to_train+" <-> Functions.stage = "+Functions.stage);
		var packeta = "answers="+JSON.stringify([event.keyCode,img1,img2,isTraining,Functions.clock])+"&count="+Functions.img_idx+"&max="+(Functions.imgs_a.length-1)+"&stage="+Functions.stage+"&num_rows="+(Functions.imgs_a.length-1);
		var httpc = new XMLHttpRequest();
		var url = "/recognitions/dev/similarity_up.php";
		httpc.open("POST", url, true);
		httpc.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		httpc.onreadystatechange = function(){
			// console.log("httpc.readyState = "+httpc.readyState+", httpc.status = "+httpc.status);
			/*
			if(httpc.status == 500)
			{
				Form.ajax(event);
				return;
			}
			*/
			if(httpc.readyState == 4 && httpc.status == 200)
			{
				// console.log(httpc.responseText);
				var addr = httpc.responseText.split(",");
				console.log(addr[0]);
				Functions.img_idx++;
				if(addr[0] == "need_revalidation")
				{
					window.location.href = addr[1];
					return;
				}
				else if(Functions.stage == 3 && Functions.img_idx == Functions.imgs_a.length)
				{
					window.location.href = httpc.responseText;
					return;
				}
				else if(Functions.img_idx == Functions.imgs_a.length)
				{
					Functions.permut = shuffleOf(Functions.permut);
					if(Functions.stage == 0 && Functions.img_idx == Functions.numbers_to_train)
					{
						Functions.stage++;
						Functions.img_idx = 0;
						Functions.uploadRest(); // need a rest before continue experiment.
					}
					else
					{
						Functions.stage++;
						Functions.img_idx = 0;
						var doctype_data = document.getElementById("fix").innerHTML;
						var object = document.getElementById('fix');
						object.innerHTML = "";
						//object.style.backgroundColor = "black";
						object.style.backgroundColor = "#dcdcdc";
						object.innerHTML = '<div class="layout-center-3"><img src="/recognitions/dev/fixation.png" width="150px" height="150px" style="padding-top:11.5em;padding-right:2.4em;"/></div">';
						setTimeout(function(){
							setTimeout(function(){
								object.innerHTML = doctype_data;
								Functions.changeContent();
							},500);
						},200);
					}
				}
				else if(Functions.stage == 0 && Functions.img_idx == Functions.numbers_to_train)
				{
					
					Functions.uploadRest(); // need a rest before continue experiment.
				}
				else
				{
					var doctype_data = document.getElementById("fix").innerHTML;
					var fixation_pos = document.getElementById("curr_img1");
					var object = document.getElementById('fix');
					object.innerHTML = "";
					//object.style.backgroundColor = "black";
					object.style.backgroundColor = "#dcdcdc";
					object.innerHTML = '<div class="layout-center-3"><img src="/recognitions/dev/fixation.png" width="150px" height="150px" style="padding-top:11.5em;padding-right:2.4em;"/></div">';
					setTimeout(function(){
						setTimeout(function(){
							object.innerHTML = doctype_data;
							Functions.changeContent();
						},500);
					},200);
				}
			}
		}
		httpc.send(packeta);
	},
	"transfer":function(){
		window.location.href = "/recognitions/dev/details.php?returnTask='5'";
	}
};