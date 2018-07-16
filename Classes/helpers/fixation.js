function fixation(elem,fixation_time_ms)
{
	var src_path = '/recognitions/dev/fixation.png';
	elem.style.backgroundColor = "white";
	elem.innerHTML = '<img id="myFix">'; // default width and height
	document.getElementById("myFix").src = src_path;
	document.getElementById("myFix").width = 200;
	document.getElementById("myFix").height = 200;
	setTimeout(function(){
		// TODO - call from here to some function!
	},fixation_time_ms);
	return;
}