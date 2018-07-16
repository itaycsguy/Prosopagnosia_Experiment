$(function(){
	var $pwSend = $("#pw-send");
	var $pwTextarea = $("#pw-textarea");
	var $buttons = $pwSend.add($pwTextarea);

	var enableButtons = function(enable){
		if((typeof $buttons).toLowerCase() !== "object" || $buttons.length == 0)
			return;
		if(!enable) {
			$buttons.prop({"disabled":"disabled"});
		}
		else
		{
			$buttons.prop({"disabled":"0"});
			$buttons.removeProp("disabled");
		}
	};

	$pwSend.click(function(){
		var v = $pwTextarea.val();
		if (v && v.length > 0)
		{
			// send form
			enableButtons(false);
			var login_data = {"pw":v};
			$.ajax({
				url: "/recognitions/dev/index.php",
				data: login_data,
				processData: true,
				method: "POST",
				dataType: "text"
			}).done(function(ret){
				// if that comes there's an error that occurs:
				if(ret == "err")
				{
					alert("הסיסמא שגויה!");
					window.location.reload(true);
					enableButtons(true);					
				}
				else if(ret == "ex_err")
				{
					alert("הסיסמא לא קיימת במערכת! פנה למנהל המערכת לצורך רישום.");
					window.location.reload(true);
					enableButtons(true);
				}
				else if(ret == "blocked")
				{
					alert('סיימת לבצע את המטלה/נחסמת ע"י מנהל המערכת.');
					window.location.reload(true);
					enableButtons(true);
				}
				else
				{
					window.location.href = ret;
				}
			}).fail(function(xhr, status, err){
				alert("שגיאת שרת!");
				enableButtons(true);
			}).always(function(){

			});
		}
	});
	// bind enter to "send" button click
	$pwTextarea.on("keydown", function(e){
		if (e.which == 13 || e.which == 10)
			$pwSend.click();
	});
});