var Functions = {
	"name":"",
	"gender":"",
	"block_comp":function(){
		document.getElementById("inp_continue").style.cursor = "default";
		document.getElementById("inp_continue").disabled = true;
	},
	"manage":function(event){
		if(event.keyCode != 13) // enter is pressed
		{
			return;
		}
		document.removeEventListener("keydown",Functions.manage);
		Form.ajax(event);
	},
	"changeContent":function(){
		document.removeEventListener("keydown",Functions.changeContent);
		/*
		var str = "<tr><td><h1 class='h1_style'>שלום";
		str += " "+Functions.name+"!</h1></td></tr><br><br>";
		str += "<tr><td><h2 class='h2_style'>ניהול נבדקים: </h2></td></tr><br><br>";
		str += "<tr><td> הוספה <input class='dump' name='radio_in' type='radio' id='new'/> קיים <input class='dump' name='radio_in' type='radio' id='exist'/> איפוס מלא <input class='dump' name='radio_in' type='radio' id='reset'/> מחיקה <input class='dump' name='radio_in' type='radio' id='rem'/> מחיקת נבדקים <input class='dump' name='radio_in' type='radio' id='clear' onclick='Functions.block_comp();'/></td></tr><br>";
		str += "<tr><td><input type='text' autocomplete='off' placeholder='מספר מזהה' id='inp_continue'/></td></tr>";
		str += "<br><tr><td><input type='button' id='dis' name='dis' value='התנתק' onclick='Form.ajax(this);'/></h3></td></tr>";
		var con = document.getElementById("table");
		con.innerHTML = str;
		if(Functions.gender == "זכר")
		{
			document.getElementById("dis").value = "התנתק";
		}
		else if(Functions.gender == "נקבה")
		{
			document.getElementById("dis").value = "התנתקי";
		}
		document.addEventListener("keydown",Functions.manage);
		*/
		window.location = "/recognitions/dev/ManagerInterface.php"; // intermediate solution
	},
	"byebyePage":function(){
		var str = "<tr><td><h1 class='h1_style'>";
		if(Functions.gender == "זכר")
		{
			str += "אתה מנותק.";
		}
		else if(Functions.gender == "נקבה")
		{
			str += "את מנותקת.";
		}
		str += "</h1></td></tr><br><br>";
		str += "<tr><td><h2 class='h2_style'>שיהיה המשך יום נפלא!</h2></td></tr><br><tr><td><img src='/recognitions/dev/cute.jpg' width='150px' height='150px'/></td></tr>";
		document.getElementById('table').innerHTML = str;
	}
};
var Form = {
	"ajax":function(event){
		// collect and build the packet:
		// window.blur();
		var classes = document.getElementsByClassName("dump");
		var obj_value = "";
		var obj_name = "";
		var index = -1;
		for(var i = 0;i < classes.length;i++)
		{
			if(classes[i].checked == true)
			{
				obj_name = classes[i].id;
				obj_value = document.getElementById("inp_continue").value;
				index = i;
			}
		}
		var packeta = "";
		//alert(document.getElementById("inp").name);
		if(index != -1)
		{
			packeta = obj_name+"="+obj_value;
		}
		else if(event.name == "dis")
		{
			// Functions.byebyePage();
			// return;
			packeta = "disconnect=1";
		}
		else
		{
			try{
				if(document.getElementById("inp").name == "connect")
				{
					packeta = "pass="+document.getElementById("inp").value;
				}
			}
			catch(exp)
			{
				alert("חובה לבחור פעולה.");
				document.addEventListener("keydown",Functions.manage);
				return;
			}
		}
		var httpc = new XMLHttpRequest();
		var url = "/recognitions/dev/manager.php";
		httpc.open("POST", url, true);
		httpc.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		httpc.onreadystatechange = function(){
			if(httpc.readyState == 4 && httpc.status == 200)
			{
				//alert(httpc.responseText);
				var is_problem_occur = false;
				var arr = httpc.responseText.split(",");
				if(httpc.responseText == "err")
				{
					alert("שגיאת שרת!");
					is_problem_occur = true;
					// return;
				}
				else if(httpc.responseText == "p_err")
				{
					alert("סיסמא שגויה!");
					is_problem_occur = true;
					// return;
				}
				else if(httpc.responseText == "inserted" || httpc.responseText == "deleted" || httpc.responseText == "full_reseted" || httpc.responseText == "cleared" || httpc.responseText == "not_ex" || httpc.responseText == "ex")
				{
					if(httpc.responseText == "not_ex")
						alert("המשתמש איננו קיים במערכת!");
					else if(httpc.responseText == "ex")
						alert("המשתמש קיים במערכת!");
					else
					{
						alert("בוצע!");
						if(httpc.responseText == "cleared")
						{
							document.getElementById("inp_continue").style.cursor = "pointer";
							document.getElementById("inp_continue").disabled = false;
						}
					}
					Functions.changeContent();
				}
				else if(httpc.responseText == "empty")
				{
					alert("שדה ריק.");
					Functions.changeContent();
					is_problem_occur = true;
				}
				else if(arr[0] == "ok")
				{
					Functions.name = arr[1];
					Functions.gender = arr[2];
					Functions.changeContent();
				}
				else if(httpc.responseText == "disconnected")
				{
					Functions.byebyePage();
				}
				if(is_problem_occur == true)
				{
					document.addEventListener("keydown",Functions.manage);
				}
			}
		}
		// alert(packeta);
		httpc.send(packeta);
	}
};