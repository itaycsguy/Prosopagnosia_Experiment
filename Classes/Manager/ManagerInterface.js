function performAction(phone,action)
{
    var xmlHTTP = new XMLHttpRequest();
    xmlHTTP.onreadystatechange = function()
    {
	    if (this.readyState == 4 && this.status == 200)
	    {
            var i = this.responseText.indexOf('!');
            if (this.responseText.substring(0,i).localeCompare("added") == 0)
                alert("בוצע בהצלחה.");
            else
                alert(this.responseText.substring(0,i)+"!");

            window.location.reload(); 
            return;   
	    }
    }
    xmlHTTP.open("GET", "/recognitions/dev/ManagerInterface.php?phone="+phone+"&action="+action , true);
    xmlHTTP.send();
}

function add()
{
    var phone = document.getElementById("phoneToAdd").value;
    if (phone.length < 10)
        alert("מזהה לא תקין. אנא נסה שנית.");
    else
        performAction(phone,"add");
}

function deleteSubject(subject)
{
    performAction(subject,"delete");
    //alert("DELETE " + subject);
}

function resetApart(subject)
{
    performAction(subject,"resetLast");
    //alert("Reset " + subject);
}
 
function resetSubject(subject)
{
    performAction(subject,"reset");
    //alert("Reset " + subject);
}

function banSubject(subject)
{
    performAction(subject,"ban");
    //alert("Ban " + subject);
}

function allowSubject(subject)
{
	performAction(subject,"allow");
}

function AllowOnlyNumbers(e)
{
    e = (e) ? e : window.event;
    var key = null;

    var charsKeys = [97, 65, 99, 67, 118, 86, 115, 83, 112, 80];
    var specialKeys = [8, 9, 27, 13, 35, 36, 37, 39, 46, 45];
            
    key = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;

    if (key && key < 48 || key > 57)
    {
        if ((e.ctrlKey && charsKeys.indexOf(key) != -1) || (navigator.userAgent.indexOf("Firefox") != -1 && ((e.ctrlKey && e.keyCode && e.keyCode > 0 && key >= 112 && key <= 123) || (e.keyCode && e.keyCode > 0 && key && key >= 112 && key <= 123))))
        {
            return true;
        }
        else if (specialKeys.indexOf(key) != -1)
        {
            if ((key == 39 || key == 45 || key == 46))
            {
                return (navigator.userAgent.indexOf("Firefox") != -1 && e.keyCode != undefined && e.keyCode > 0);
            }
            else if (e.shiftKey && (key == 35 || key == 36 || key == 37))
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        else
        {
            return false;
        }
    }
    else
    {
        return true;
    }
}
function byebyePage(){
		var str = "<tr><td><h1 class='h1_style'>";
		str += "המערכת מנותקת.";
		str += "</h1></td></tr><br><br>";
		str += "<tr><td><h2 class='h2_style'>שיהיה המשך יום נפלא!</h2></td></tr><br><tr><td><img src='/recognitions/dev/cute.jpg' width='150px' height='150px'/></td></tr>";
		document.getElementById('section').innerHTML = str;
		setTimeout(function(){
			document.cookie = "manager_session" + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
			window.location = "/recognitions/dev/manager.php";
		},3000);
	}