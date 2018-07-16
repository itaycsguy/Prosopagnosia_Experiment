var Functions = {
	"clock":0,
	"gender":"בן",
	"hand":"ימין",
	"img_width":300,
	"permut":[],
	"img_idx":0,
	"imgs":[],
	"correct_imgs":[],
	"gender_imgs":[],
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
			//elem.innerHTML = "<img id='img'/>"; // another gif option
			var img_item = document.getElementById("img");
			if(Functions.gender == "בן")
			{
				elem.innerHTML = "<h2><strong>לחץ על רווח להמשך.</strong></h2>";
				//img_item.src = "/recognitions/dev/gif_male.gif";
				//img_item.alt = "לחץ רווח";
				document.addEventListener("keydown",Functions.beginTask);
			}
			else if(Functions.gender == "בת")
			{
				elem.innerHTML = "<h2><strong>לחצי על רווח להמשך.</strong></h2>";
				//img_item.src = "/recognitions/dev/gif_female.gif";
				//img_item.alt = "לחצי רווח";
				document.addEventListener("keydown",Functions.beginTask);
			}
		},21000); // 21 sec included 0
	},
	"preload":function(){
		var loagImd = new Image();
		loagImd.src = "/recognitions/dev/loading-blue.gif";
		for(var i = 0;i < Functions.imgs.length;i++)
		{
			var myImgPath = "/recognitions/famous_pictures/"+Functions.imgs[Functions.permut[i]];
			var img = new Image();
			img.src = myImgPath; // make the preload
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
		//console.log(Functions.img_idx);
		var str = "<tr><td>";
		if(Functions.gender == 'בן')
		{
			str += "<h2><strong>האם אתה מזהה את הדמות?</strong></h2>";
		}
		else if(Functions.gender == 'בת')
		{
			str += "<h2><strong>האם את מזהה את הדמות?</strong></h2>";
		}
		str += "</td></tr><tr><td><img id='curr_img' alt='famous'/>";
		str += "</td></tr><tr><input autocomplete='off' id='nameInput' type='text' onkeydown='Functions.changeLabel();' onpaste='Functions.changeLabel();' placeholder='שם..' name='name'/><input autocomplete='off' id='contextInput' type='text' onkeydown='Functions.changeLabel();' onpaste='Functions.changeLabel();' placeholder='הקשר..' name='context'/></td></tr>";
		document.getElementById("table").innerHTML = str;
		document.getElementById("table").innerHTML += "<tr><td><input type='button' id='choiceButton' onclick='Form.ajax(this);' value='אני לא מזהה'/></td></tr>";
		var currImg = document.getElementById("curr_img");
		currImg.src = "/recognitions/famous_pictures/"+Functions.imgs[Functions.permut[Functions.img_idx]];
		// console.log(Functions.img_width);
		currImg.width = Functions.img_width;
		currImg.height = Functions.img_width;
		Functions.clock_begin();
	},
	"changeLabel":function(event){
		setTimeout(function(){
			var butt = document.getElementById("choiceButton");
			var name = document.getElementById("nameInput");
			var context = document.getElementById("contextInput");
			//console.log(name.value.length);

			if(name.value.length > 0 || context.value.length > 0)
			{
				butt.value = "המשך";
			}
			else
			{
				butt.value = "אני לא מזהה";
			}
		},2);
	},
	"uploadSecondStep":function(){
		var str = "<div id='padup' style=''>";
		var len_up = (document.getElementById("curr_img").height) + "px";
		if(!Functions.correct_imgs[Functions.permut[Functions.img_idx]].includes("לא מוכר"))
		{
			if(Functions.gender_imgs[Functions.permut[Functions.img_idx]] == "male")
			{
				str += "<tr><td><h1 id='placeLabel'>זה היה "+Functions.correct_imgs[Functions.permut[Functions.img_idx]]+"</h1></td></tr>";
				if(Functions.gender == "בן")
				{
					str += "<tr><td><h2>אתה מכיר אותו?</h2></td></tr>";
				}
				else if(Functions.gender == "בת")
				{
					str += "<tr><td><h2>את מכירה אותו?</h2></td></tr>";
				}
			}
			else if(Functions.gender_imgs[Functions.permut[Functions.img_idx]] == "female")
			{
				str += "<tr><td><h1 id='placeLabel'>זאת היתה "+Functions.correct_imgs[Functions.permut[Functions.img_idx]] +"</h1></td></tr>";
				if(Functions.gender == "בן")
				{
					str += "<tr><td><h2>אתה מכיר אותה?</h2></td></tr>";
				}
				else if(Functions.gender == "בת")
				{
					str += "<tr><td><h2>את מכירה אותה?</h2></td></tr>";
				}
			}
			str += "<br></strong></h1></td></tr><br><br><tr><td><input type='button' onclick='Form.ajax(this);' id='choiceButtonYes' value='כן'/><input type='button' onclick='Form.ajax(this);' id='choiceButtonNo' value='לא'/></td></tr>";
		}
		else
		{
			if(Functions.gender_imgs[Functions.permut[Functions.img_idx]] == "male")
			{
				str += "<tr><td><h1>זה אינו אדם מוכר</h1></td></tr>";
			}
			else if(Functions.gender_imgs[Functions.permut[Functions.img_idx]] == "female")
			{
				str += "<tr><td><h1>זאת אינה אישה מוכרת</h1></td></tr>";
			}
			str += "<br></strong></h1></td></tr><br><br><tr><td><input type='button' onclick='Form.ajax(this);' id='choiceButtonYes' value='המשך'/></td></tr>";
		}
		document.getElementById("table").innerHTML = str + "</div>";
		if(document.getElementById("placeLabel") != null) {
			document.getElementById("padup").style.paddingTop = len_up/3;	
		} else {
			document.getElementById("padup").style.paddingTop = len_up;
		}
	}
};
var Form = {
	"ajax":function(event){
		window.blur();
		Functions.clock_stop();
		var packeta = "";
		if(event.value == "אני לא מזהה")
		{
			event.disabled = true;
			event.style.cursor = "default";
			event.style.backgroundColor = "white";
			var name = document.getElementById("nameInput");
			var context = document.getElementById("contextInput");
			name.disabled = true;
			context.disabled = true;
			name.style.cursor = "default";
			context.style.cursor = "default";
			Functions.clock_begin();
			Functions.uploadSecondStep();
			return;
		}
		else if(event.value == "המשך")
		{
			event.disabled = true;
			event.style.cursor = "default";
			event.style.backgroundColor = "white";
			var correct = Functions.correct_imgs[Functions.permut[Functions.img_idx]];
			var img = Functions.imgs[Functions.permut[Functions.img_idx]];
			var time = Functions.clock;
			packeta += "inputs="+JSON.stringify(["","",correct,img,time])+"&count="+Functions.img_idx+"&max="+Functions.imgs.length;
		}
		else if(event.value == "כן" || event.value == "לא")
		{
			var yes = document.getElementById("choiceButtonYes");
			var no = document.getElementById("choiceButtonNo");
			yes.style.backgroundColor = "white";
			no.style.backgroundColor = "white";
			yes.disabled = true;
			no.disabled = true;
			yes.style.cursor = "default";
			no.style.cursor = "default";
			var answer = event.value;
			var img = Functions.imgs[Functions.permut[Functions.img_idx]];
			var correct_img = Functions.correct_imgs[Functions.permut[Functions.img_idx]];
			var time = Functions.clock;
			packeta += "answer="+JSON.stringify([answer,img,correct_img,time])+"&count="+Functions.img_idx+"&max="+(Functions.imgs.length-1);
		}
		var httpc = new XMLHttpRequest();
		var url = "/recognitions/dev/famous.php";
		httpc.open("POST", url, true);
		httpc.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		httpc.onreadystatechange = function(){
			if(httpc.status == 500)
			{
				Form.ajax(event);
				return;
			}
			if(httpc.readyState == 4 && httpc.status == 200)
			{
				// console.log(httpc.responseText);
				var addr = httpc.responseText.split(",");
				// console.log(addr[0]);
				if(addr[0] == "need_revalidation")
				{
					window.location.href = httpc.responseText;
					return;
				}
				if(Functions.img_idx == (Functions.imgs.length-1))
				{
					window.location.href = httpc.responseText;
					return;
				}
				else
				{
					Functions.img_idx++;
					Functions.changeContent();
				}
			}
		}
		httpc.send(packeta);		
	},
	"transfer":function(){
		window.location.href = "/recognitions/dev/details.php?returnTask='4'";
	}
};