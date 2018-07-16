var Functions = {
	"retTask":"1",
	"isNumber":function(num){
		if(num.length == 0)
			return false;
		for(var i = 0;i < num.length;i++)
		{
			if(num[i] < '0' || num[i] > '9')
				return false;
		}
		return true;
	},
	"verify":function(value,level){
		var ans = false;
		switch(level)
		{
			case 1:	// name:
					var ret = Functions.isNumber(value);
					if(!ret && value.length > 0)
					{
						ans = true;
					}
					
					break;
			case 2:	// age:
					var ret = Functions.isNumber(value);
					if(ret)
					{
						var num = parseInt(value);
						if(num => 4 && num <= 12)
							ans = true;
					}
					break;
			case 3:	// parents:
					var ret = Functions.isNumber(value);
					if(!ret && value.length > 0)
					{
						ans = true;
					}
					break;
			case 4:	// phone: -> at this case email address is verified too.
					/*
					// a way to validate this free context parameter.
					if(value.length > 0)
						ans = true; // no validation is to be apply here because it is a free context.
					*/
					ans = true; // optional.
					break;
		}
		return ans;
	},
	"drawContoursOf":function(object,section){ // #1 - server , #0 - client
		if(section == 1)
			object.style.borderColor = "red";
		else
			if(object.style.borderColor == "red")
				object.style.borderColor = "#bbb";
	},
	"make_editable":function(){
		var name_obj = document.getElementById("inp_name");
		name_obj.disabled = false;
		var age_obj = document.getElementById("inp_age");
		age_obj.disabled = false;
		var gender_obj = document.getElementById("inp_gender");
		gender_obj.disabled = false;
		var hand_obj = document.getElementById("inp_hand");
		hand_obj.disabled = false;
		var parent_obj = document.getElementById("inp_parent");
		parent_obj.disabled = false;
		var phone_obj = document.getElementById("inp_phone");
		phone_obj.disabled = false;
		document.getElementById("edit").disabled = true;
		document.getElementById("edit").style.cursor = "default";
	}
};
var Form = {
	"ajax":function(){
		// collect and build the packet:
		window.blur();
		var name = document.getElementById("inp_name");
		name.disabled = true;
		name.style.cursor = "default";
		Functions.drawContoursOf(name,0);
		var age = document.getElementById("inp_age");
		age.disabled = true;
		age.style.cursor = "default";
		Functions.drawContoursOf(age,0);
		var gender = document.getElementById("inp_gender");
		gender.disabled = true;
		gender.style.cursor = "default";
		Functions.drawContoursOf(gender,0);
		var hand = document.getElementById("inp_hand");
		hand.disabled = true;
		hand.style.cursor = "default";
		Functions.drawContoursOf(hand,0);
		var parents = document.getElementById("inp_parent");
		parents.disabled = true;
		parents.style.cursor = "default";
		Functions.drawContoursOf(parents,0);
		var phone = document.getElementById("inp_phone");
		phone.disabled = true;
		phone.style.cursor = "default";
		Functions.drawContoursOf(phone,0);
		
		// continue button disable and default cursor:
		var but = document.getElementById("click_continue");
		but.disabled = true;
		but.style.cursor = "default";
		
		var retName = Functions.verify(name.value,1);
		// alert(retName);
		var retAge = Functions.verify(age.value,2);
		// alert(retAge);
		var retParent = Functions.verify(parents.value,3);
		// alert(retParent);
		var retPhone = Functions.verify(phone.value,4);
		// alert(retPhone);
		var stayFlag = false;
		var packeta = "details=";
		if(retName&&retAge&&retParent&&retPhone)
		{
			if(Functions.retTask != "1" && Functions.retTask != "")
				packeta += JSON.stringify([name.value,age.value,gender.value,hand.value,parents.value,phone.value,Functions.retTask]);
			else
				packeta += JSON.stringify([name.value,age.value,gender.value,hand.value,parents.value,phone.value]);
		}
		else
		{
			if(!retName)
			{
				Functions.drawContoursOf(name,1);
				name.disabled = false;
				name.style.cursor = "text";
				stayFlag = true;
			}
			if(!retAge)
			{
				Functions.drawContoursOf(age,1);
				age.disabled = false;
				age.style.cursor = "text";
				stayFlag = true;
			}
			if(!retParent)
			{
				Functions.drawContoursOf(parents,1);
				parents.disabled = false;
				parents.style.cursor = "text";
				stayFlag = true;
			}
			if(!retPhone)
			{
				Functions.drawContoursOf(phone,1);
				phone.disabled = false;
				phone.style.cursor = "text";
				stayFlag = true;
			}
			if(stayFlag == true)
			{
				document.getElementById("click_continue").disabled = false;
				document.getElementById("click_continue").style.cursor = "pointer";
				document.getElementById("edit").disabled = false;
				document.getElementById("edit").style.cursor = "pointer";
				return;
			}
		}
		var httpc = new XMLHttpRequest();
		var url = "/recognitions/dev/details.php";
		httpc.open("POST", url, true);
		httpc.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		httpc.onreadystatechange = function(){
			if(httpc.readyState == 4 && httpc.status == 200)
			{
				window.location.href = httpc.responseText;
			}
		}
		// alert(packeta);
		httpc.send(packeta);
	},
	"backToTask":function(){
		var to = "";
		switch(Functions.retTask)
		{
			case "2":	to = "/recognitions/dev/questions.php";	
						break;
			case "3":	to = "/recognitions/dev/scaling.php";	
						break;
			case "4":	to = "/recognitions/dev/famous.php";	
						break;
			case "5":	to = "/recognitions/dev/similarity_up.php";	
						break;
			case "6":	to = "/recognitions/dev/similarity_inv.php";	
						break;
			case "7":	to = "/recognitions/dev/SessionStarter.php";	
						break;	
			case "8":	to = "/recognitions/dev/global.php";	
						break;
			case "9":	to = "/recognitions/dev/local.php";	
						break;
			case "10":	to = "/recognitions/dev/expressions.php";	
						break;
		}
		if(to != "")
			window.location.href = to;
	}
};