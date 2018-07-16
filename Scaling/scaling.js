var Functions = {
	"drawLine":function(){
		var canvas = document.getElementById('line');
		if(canvas.getContext){
			var width = canvas.width;
			var ctx = canvas.getContext('2d');
			ctx.fillStyle = 'rgb(255,215,0)';
			ctx.fillRect(0,0,width,15);
		}
	},
	"changeCanvasWidth":function(event){
		var canvas = document.getElementById('line');
		//if(canvas.getContext) {
			var ctx = canvas.getContext('2d');
			if(event.keyCode == 38 && canvas.width < 600) // 40 - up: bigger.
			{
				canvas.width = canvas.width+1;
				Functions.drawLine();
			}
			else if(event.keyCode == 40 && canvas.width > 150) // 38 - down: smaller.
			{
				canvas.width = canvas.width-1;
				Functions.drawLine();
			}
		//}
	},
	"getWidthLine":function(){
		var width = document.getElementById("line").width;
		return width;
	}
};
var Form = {
	"ajax":function(){
		// collect and build the packet:
		window.blur();
		var line = document.getElementById("line");
		line.disabled = true;
		var finish = document.getElementById("inp_finish");
		finish.disabled = true;
		finish.style.cursor = "default";
		finish.style.backgroundColor = "#fff7cc";
		var packeta = "width=";
		packeta += line.width;
		var httpc = new XMLHttpRequest();
		var url = "/recognitions/dev/scaling.php";
		httpc.open("POST", url, true);
		httpc.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		httpc.onreadystatechange = function(){
			if(httpc.readyState == 4 && httpc.status == 200)
			{
				// console.log(document.cookie);
				window.location.href = httpc.responseText;
			}
		}
		document.removeEventListener("keydown",Functions.changeCanvasWidth);
		// alert(packeta);
		httpc.send(packeta);
	},
	"transfer":function(){
		window.location.href = "/recognitions/dev/details.php?returnTask='1'";
	}
};