function blackBackFixation(contents){
	var object = document.getElementById('fix');
	object.innerHTML = "";
	object.style.backgroundColor = "black";
	setTimeout(function(){
		object.style.backgroundColor = "#dcdcdc";
		object.innerHTML = '<div><img src="/recognitions/dev/fixation.png" width="100px" height="100px"/></div>';
		setTimeout(function(){
			object.innerHTML = contents;
		},250);
	},1000);
}