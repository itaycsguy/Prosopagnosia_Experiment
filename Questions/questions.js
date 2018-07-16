var Functions = {
	"collectSelectedLines":function(){
		var arr = [];
		var q_group = document.getElementsByClassName("q_group");
		for(var i = 0;i < q_group.length;i++)
		{
			if(q_group[i].checked == true)
			{
				arr.push(q_group[i].name);
				arr.push(q_group[i].value);
			}
			q_group[i].style.cursor = "default";
			q_group[i].disabled = true;
		}
		if(arr.length < 28)
		{
			if(false)//confirm("קיימות תשובות חסרות, ברצונך להמשיך?") == false) // they asked to force him fill each entry.
			{
				for(var i = 0;i < q_group.length;i++)
				{
					q_group[i].style.cursor = "pointer";
					q_group[i].disabled = false;
				}
				return null;
			}
		}
		return arr;
	},
	"markSelectedQuestion":function(object){
		var line = document.getElementById(object.name);
		line.style.backgroundColor = "lightblue";
	},
	"make_checks":function(items){
		var items_arr = items.split(",");
		var q_group = document.getElementsByClassName("q_group");
		for(var i = 0;i < items_arr.length;i+=2)
		{
			for(var j = 0;j < q_group.length;j++)
			{
				if(q_group[j].name == items_arr[i] && q_group[j].value == items_arr[i+1])
				{
					q_group[j].checked = true;
					Functions.markSelectedQuestion(q_group[j]);
				}
			}
		}
	}
};
var Form = {
	"ajax":function(){
		// collect and build the packet:
		window.blur();
		var name = document.getElementById("inp_continue");
		var img_vec = Functions.collectSelectedLines();
		if(img_vec == null)
		{
			return;
		}
		name.disabled = true;
		name.style.cursor = "default";
		name.style.backgroundColor = "#062e63";
		var packeta = "questions=";
		packeta += JSON.stringify(img_vec); // that vector goes by pairs begin in index 0.
		var httpc = new XMLHttpRequest();
		var url = "/recognitions/dev/questions.php";
		httpc.open("POST", url, true);
		httpc.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		httpc.onreadystatechange = function(){
			if(httpc.readyState == 4 && httpc.status == 200)
			{
				// console.log(httpc.responseText);
				window.location.href = httpc.responseText;
			}
		}
		// alert(packeta);
		httpc.send(packeta);
	},
	"transfer":function(){
		window.location.href = "/recognitions/dev/details.php?returnTask='1'";
	}
};