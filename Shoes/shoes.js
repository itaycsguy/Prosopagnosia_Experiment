
var Functions = {
	"type":1,
	"clock":0,
	"gender":"בן",
	"hand":"ימין",
	"img_width":300,
	"imgs_a_type1":[],
	"imgs_b_type1":[],
	"imgs_c_type1":[],
	"img_idx_1":0,
	"imgs_a_type2":[],
	"imgs_b_type2":[],
	"img_idx_2":0,
	"imgs_a_type3":[],
	"imgs_b_type3":[],
	"img_idx_3":0,
	"numbers_to_train_1":1,
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
				//document.getElementById("countdown").innerHTML = --countdown;
				countdown--;
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
				elem.innerHTML = "<h2><strong>נא לחצי על רווח בכדי להתחיל באימון!</strong></h2>";
				document.addEventListener("keydown",Functions.beginTask);
			}
		},21000); // 21 sec included 0
	},
	"preload":function(){
		for(var i = 0;i < Functions.imgs_a_type1.length;i++)
		{
			var myImgPath_a = "/recognitions/test5/"+Functions.imgs_a_type1[i];
			var myImgPath_b = "/recognitions/test5/"+Functions.imgs_b_type1[i];
			var myImgPath_c = "/recognitions/test5/"+Functions.imgs_c_type1[i];
			var img_a = new Image();
			var img_b = new Image();
			var img_c = new Image();
			img_a.src = myImgPath_a; // make the preload
			img_b.src = myImgPath_b; // make the preload
			img_c.src = myImgPath_c; // make the preload
		}
		for(var i = 0;i < Functions.imgs_a_type2.length;i++)
		{
			var myImgPath_a = "/recognitions/test5/"+Functions.imgs_a_type2[i];
			var myImgPath_b = "/recognitions/test5/"+Functions.imgs_b_type2[i];
			var img_a = new Image();
			var img_b = new Image();
			img_a.src = myImgPath_a; // make the preload
			img_b.src = myImgPath_b; // make the preload
		}
		for(var i = 0;i < Functions.imgs_a_type3.length;i++)
		{
			var myImgPath_a = "/recognitions/test5/"+Functions.imgs_a_type3[i];
			var myImgPath_b = "/recognitions/test5/"+Functions.imgs_b_type3[i];
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
			Functions.showPreview1();
		}
	},
	"showPreview1":function(){
		console.log("showPreview in");
		console.log(Functions.img_idx_1);
		var str = "";
		if(Functions.gender == "בן") {
			str = "<tr><td><h2><strong>נסה לזכור.</strong></h2>";
		} else {
			str = "<tr><td><h2><strong>נסי לזכור.</strong></h2>";
		}
		var img = Functions.imgs_a_type1; // factory
		str += "<tr><td><img src=" + "/recognitions/test5/" + img[Functions.img_idx_1++] + " id='curr_img' alt='face' width='" + Functions.img_width + "px' height='" + Functions.img_width + "px'/></td></tr>";
		document.getElementById("table").innerHTML = str;
		var curr_img = document.getElementById("curr_img");
		var func = function(curr_img){
			var localCounter = 0;
			var timeout = setTimeout(function(){},3000);
			var myPreview = window.setInterval(function(){
				if(localCounter == 2){
					Functions.showQuestions1();
					clearInterval(myPreview);
				} else {
					curr_img.width = Functions.img_width;
					curr_img.height = Functions.img_width;
					curr_img.src = "/recognitions/test5/" + img[Functions.img_idx_1++];
					localCounter++;
				}
			},3000);
		}
		func(curr_img);
	},
	"showQuestions1":function(){
		var str = "<tr><td><h2><strong>איזה נעל הופיעה?</strong></h2></td></tr>";
		str += "<tr><td><h4><strong>[נא ללחוץ על מספר התמונה המתאים]</strong></h4></td></tr>";
		var img = Functions.imgs_b_type1; // factory
		//console.log(Functions.img_idx_1);
		str += "<tr><td><img src=" + "/recognitions/test5/" + img[Functions.img_idx_1-2] + " id='curr_img1' width='" + (3*Functions.img_width) + "px' height='" + Functions.img_width + "px'/></td></tr>";
		str += "<br><tr><td><strong><label style='padding-left:33%;'><img src='/recognitions/dev/3.png'></label><label><img src='/recognitions/dev/2.png'></label><label style='padding-right:33%;'><img src='/recognitions/dev/1.png'></label></strong></td></tr>";
		//str += "<br><tr><td><strong><label style='padding-left:33%;'>-3-</label><label>-2-</label><label style='padding-right:33%;'>-1-</label></strong></td></tr>";
		document.getElementById("table").innerHTML = str;
		document.addEventListener("keydown",Form.ajax);
		Functions.clock_begin();
	},
	"showPreview2":function(){
		var str = "";
		if(Functions.gender == "בן") {
			str = "<tr><td><h2><strong>נסה לזכור.</strong></h2>";
		} else {
			str = "<tr><td><h2><strong>נסי לזכור.</strong></h2>";
		}
		var img = Functions.imgs_a_type2; // factory
		console.log(img);
		console.log(Functions.img_idx_2);
		str += "<tr><td><img src=" + "/recognitions/test5/" + img[Functions.img_idx_2] + " id='curr_img' alt='face'/></td></tr>";
		//str += "<tr><td><img src=" + "/recognitions/test5/" + img[Functions.img_idx_2] + " id='curr_img' alt='face' width='" + (3*Functions.img_width) + "px' height='" + (3*Functions.img_width) + "px'/></td></tr>";
		Functions.img_idx_2++;
		document.getElementById("table").innerHTML = str;
		var curr_img = document.getElementById("curr_img");
		var func = function(curr_img){
			var timeout = setTimeout(function(){
				Functions.showQuestions2();
			},20000);
		}
		func(curr_img);
	},
	"showQuestions2":function(){
		var str = "<tr><td><h2><strong>איזה נעל הופיעה?</strong></h2></td></tr>";
		str += "<tr><td><h4><strong>[נא ללחוץ על מספר התמונה המתאים]</strong></h4></td></tr>";
		var img = Functions.imgs_a_type2; // factory
		str += "<tr><td><img src=" + "/recognitions/test5/" + img[Functions.img_idx_2] + " id='curr_img1' width='" + (3*Functions.img_width) + "px' height='" + Functions.img_width + "px'/></td></tr>";
		str += "<br><tr><td><strong><label style='padding-left:33%;'><img src='/recognitions/dev/3.png'></label><label><img src='/recognitions/dev/2.png'></label><label style='padding-right:33%;'><img src='/recognitions/dev/1.png'></label></strong></td></tr>";
		//str += "<br><tr><td><strong><label style='padding-left:33%;'>-3-</label><label>-2-</label><label style='padding-right:33%;'>-1-</label></strong></td></tr>";
		Functions.img_idx_2++;
		document.getElementById("table").innerHTML = str;
		document.addEventListener("keydown",Form.ajax);
		Functions.clock_begin();
	},
	"showPreview3":function(){
		//console.log("showPreview in");
		var str = "";
		if(Functions.gender == "בן") {
			str = "<tr><td><h2><strong>נסה לזכור.</strong></h2>";
		} else {
			str = "<tr><td><h2><strong>נסי לזכור.</strong></h2>";
		}
		var img = Functions.imgs_a_type3;
		str += "<tr><td><img src=" + "/recognitions/test5/" + img[Functions.img_idx_3] + " id='curr_img' alt='face'/></td></tr>";
		//str += "<tr><td><img src=" + "/recognitions/test5/" + img[Functions.img_idx_3] + " id='curr_img' alt='face' width='" + (3*Functions.img_width) + "px' height='" + (3*Functions.img_width) + "px'/></td></tr>";
		Functions.img_idx_3++;
		document.getElementById("table").innerHTML = str;
		var curr_img = document.getElementById("curr_img");
		var func = function(curr_img){
			var timeout = setTimeout(function(){
				Functions.showQuestions3();
			},20000);
		}
		func(curr_img);
	},
	"showQuestions3":function(){
		var str = "<tr><td><h2><strong>איזה נעל הופיעה?</strong></h2></td></tr>";
		str += "<tr><td><h4><strong>[נא ללחוץ על מספר התמונה המתאים]</strong></h4></td></tr>";
		var img = Functions.imgs_a_type3; // factory
		str += "<tr><td><img src=" + "/recognitions/test5/" + img[Functions.img_idx_3] + " id='curr_img1' width='" + (3*Functions.img_width) + "px' height='" + Functions.img_width + "px'/></td></tr>";
		str += "<br><tr><td><strong><label style='padding-left:33%;'><img src='/recognitions/dev/3.png'></label><label><img src='/recognitions/dev/2.png'></label><label style='padding-right:33%;'><img src='/recognitions/dev/1.png'></label></strong></td></tr>";
		//str += "<br><tr><td><strong><label style='padding-left:33%;'>-3-</label><label>-2-</label><label style='padding-right:33%;'>-1-</label></strong></td></tr>";
		Functions.img_idx_3++;
		document.getElementById("table").innerHTML = str;
		document.addEventListener("keydown",Form.ajax);
		Functions.clock_begin();
	}
};
var Form = {
	"ajax":function(event){
		if(event.keyCode != 97 &&
			event.keyCode != 98 &&
			event.keyCode != 99) {
			return;// 97 = '1',97 = '2', 98 = '3'
		}
		var key_in = (99-event.keyCode+1);
		document.removeEventListener("keydown",Form.ajax);
		window.blur();
		Functions.clock_stop();
		var isTraining = 0; // false
		var packeta = "";
		var img = [],imgL = [],imgM = [],imgR = [];
		if(Functions.type == 1) {
			if(parseInt(Functions.img_idx_1/3) <= Functions.numbers_to_train_1) {
				isTraining = 1; // true
			}
			img = Functions.imgs_a_type1;
			imgL = img[Functions.img_idx_1-3];
			imgM = img[Functions.img_idx_1-2];
			imgR = img[Functions.img_idx_1-1];
			var shoes_1_index = Functions.img_idx_1/3;
			packeta = "answers="+JSON.stringify([Functions.type,key_in,imgL,imgM,imgR,Functions.imgs_c_type1[shoes_1_index-1],isTraining,Functions.clock,shoes_1_index]);
		} else if(Functions.type == 2) {
			img = Functions.imgs_a_type2;
			packeta = "answers="+JSON.stringify([Functions.type,key_in,img[Functions.img_idx_2-1],Functions.imgs_b_type2[Functions.img_idx_2-1],isTraining,Functions.clock,Functions.img_idx_2]);
		} else if(Functions.type == 3) {
			img = Functions.imgs_a_type3;
			if(Functions.img_idx_3 == 19) {
				Functions.type = -1; // end of life
			}
			packeta = "answers="+JSON.stringify([Functions.type,key_in,img[Functions.img_idx_3-1],Functions.imgs_b_type3[Functions.img_idx_3-1],isTraining,Functions.clock,Functions.img_idx_3]);
		}
		console.log(packeta);
		var httpc = new XMLHttpRequest();
		var url = "/recognitions/dev/shoes.php";
		httpc.open("POST", url, true);
		httpc.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		httpc.onreadystatechange = function(){
			if(httpc.readyState == 4 && httpc.status == 200) {
				var res = httpc.responseText;
				console.log("resssssss="+res);
				if(res == "reg") {
					if(Functions.type == 1 && (Functions.img_idx_1) == 21) {
						Functions.type = 2;
						Functions.img_idx_1 = 0;
						Functions.showPreview2();
					} else {
						Functions.showPreview1();
					}
				} else if(res == "block1") {
					if(Functions.type == 2 && Functions.img_idx_2 == 19) {
						Functions.type = 3;
						Functions.img_idx_2 = 0;
						Functions.showPreview3();
					} else {
						Functions.showQuestions2();
					}
				} else if(res == "block2") {
					Functions.showQuestions3();
				} else if(Functions.type == -1 && Functions.img_idx_3 == 19){
					window.location.href = res;
					return;
				}
			}
		}
		httpc.send(packeta);
	},
	"transfer":function(){
		window.location.href = "/recognitions/dev/details.php?returnTask='7'";
	}
};