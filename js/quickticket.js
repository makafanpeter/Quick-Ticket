/*
	\global  Variable that tells you which website you are on for easy migration! ... maybe.
*/
var SITE = "http://www.research.pdx.edu/~thath/qt";
/*
	\brief Function to get ldap data from odin user name 
	\detail This function sends the odin username to the ldap-info.php script to look up directory info.  This server side script returns a JSON object, and we use JQuery to set the information.
	\params tab Tells the function which tab it is currently on
*/
function sendOdin(tab)
{
	var ldap_info = [];
    var odin = $('#'+tab+' #odin').val();

	if (odin != ''){
	$.getJSON(SITE+'/bin/ldap-info.php?odin='+odin, function(data) {
		$('#'+tab+' #name').val(data.cn);
		$('#'+tab+' #email').val(data.mail);
		$('#'+tab+' #phone').val(data.telephoneNumber);
		$('#'+tab+' #dept').val(data.ou);
		$('#'+tab+' #room').val(data.roomNumber);
	});
	}else{
		$('#'+tab+' #name').val('');
		$('#'+tab+' #email').val('');
		$('#'+tab+' #phone').val('');
		$('#'+tab+' #dept').val('');
		$('#'+tab+' #room').val('');
	}
}

//this function sends the email address to the ldap-info.php script and returns 
//the user info based off of the email address. 
//
//Currently I am not using this script because it causes functionality issues (after you have looked
//some one up with their odin, if you try to change their email to a non psu email, it clears the form
function sendEmail()
{
    var email_element = document.getElementById('email');
    var email = email_element.value;
    if (email == '')
        {
            document.getElementById('email').innerHTML = '';
            return;
        }
    params = email;
    request = new ajaxRequest();
    request.open("GET", SITE+"/bin/ldap-info.php?email="+params ,true);
    //request.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8")

    request.onreadystatechange = function()
        {
            if (this.readyState == 4)
                {
                    if (this.status == 200){
                            if (this.responseText){
                                    var data = this.responseText.split("#");
                                    var name = decodeURIComponent(data[0]);
                                    var odin = decodeURIComponent(data[1]);
                                    var phone = decodeURIComponent(data[2]);
                                    var room = decodeURIComponent(data[3]);
                                    var dept = decodeURIComponent(data[4]);
                                    document.getElementById('name').value = name;
                                    document.getElementById('odin').value = odin;
                                    document.getElementById('phone').value = phone;
                                    document.getElementById('room').value = room
                                    document.getElementById('dept').value = dept;
                                } else {
                                    alert("Ajax error: No data received");
                                }
                        } else {
                            alert("Ajax error: " + this.statusText);
                        }
                }
        }
    request.send();
}
/*
	This is the function that creates the ticket.  It rounds up all of the 
	form variables in the HTML doc and sends it to the 'rt-create.php' script
	as a POST variable.  
	
	rt-create.php then creates the ticket and spits out the ticket number as a
	HTML hyperlink.
	
*/
function createTicket(tab){

	var formdata = new Object();
	document.getElementById('ticketnumber').innerHTML = "<img src=\"images/loading.gif\" />Loading...";
	
	formdata.odin = $('#'+tab+' .user-data #odin').val();
	formdata.name = $('#'+tab+' .user-data #name').val();
	formdata.email = $('#'+tab+' .user-data #email').val();
	formdata.phone = $('#'+tab+' .user-data #phone').val();
	formdata.subject = $('#'+tab+' .user-data #subject').val();
	formdata.dept = $('#'+tab+' .user-data #dept').val();
	formdata.room = $('#'+tab+' .user-data #room').val();
	formdata.description = $('#'+tab+' .user-data #description').val();
	formdata.status = $('#'+tab+' .user-data #status').val();
	formdata.creator = $('body #creator').html()
	formdata.refersTo = $('#'+tab+' #refersTo').val();
	
	if (tab == 'tabs-2'){	
		formdata.backup = $('#'+tab+' #backup').val();
		formdata.custType = $('#'+tab+' #custType').val();
		formdata.machineType = $('#'+tab+' #machineType').val();
		formdata.machineName = $('#'+tab+' #machineName').val();
		formdata.serialNumber = $('#'+tab+' #serialNumber').val();
		formdata.stage = $('#'+tab+' #stage').val();
		formdata.queue = 'uss-helpdesk-workbench';
	}else{
		formdata.queue = 'uss-helpdesk';
	}

	$.get(SITE+'/bin/echo.php', formdata, function(data) {
		alert(data);
	});
}
/*
	\brief Sets the the description field of form	
	\detail This function sets the subject and the body from the list of quick subjects.  It sends the "quick subject" field to the quickSubject.php and which spits out the body text.  The quick subject drop down values are how the subject line is set
	\params tab the corresponding tab that the description field is on.
*/
function setFormSubject(tab){

	var quickSubject = $('#'+tab+' #quickSubject').val();

	if (quickSubject == '') {
		$('#'+tab+' #description').val('');
		$('#'+tab+' #subject').val('');
		return;
	}
	
	$.get(SITE+'/bin/quickSubject.php', { subject: quickSubject}, function(data) {
		$('#'+tab+' #description').val(data);
		$('#'+tab+' #subject').val(quickSubject);
		return;
	});
	
}
/*
	This is the function that creates the AJAX object
*/
function ajaxRequest(){
try
{
    var request = new XMLHttpRequest();
}
catch(e1)
{
    try
    {
        request = new ActiveXObject("Microsoft.XMLHTTP");
    }
    catch(e2)
    {
        try
        {
			request = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch(e3)
        {
            request = false;
        }
    }
}
return request;
}
/*
	\detail This function spits out the workbench custom fields.  It has the ability
	to make the workbench custom fields appear whether you change the queue or the
	quick subject.  
	
	If you change the quick subject, it uses a REGEX pattern to see if the pattern 
	matches. 
	
	If you change the queue, the wrkbnchForms.php file just checks to see if queue
	is equal to 'uss-helpdesk-workbench'

	DEPRECATED!!
*/
function showWRKBNCHforms(){	

	var queue = document.getElementById('queue').value;
	var quickSubject = document.getElementById('quickSubject').value;

    request = new ajaxRequest();
    request.open("GET", "wrkbnchForms.php?queue="+queue+"&quickSubject="+quickSubject ,true);
	
	request.onreadystatechange = function()
        {
            if (this.readyState == 4)
                {
                    if (this.status == 200){
                            if (this.responseText){
                                    document.getElementById('WRKBNCHholder').innerHTML = this.responseText;
                                } else {
                                    alert("Ajax error: No data received");
                                }
                        } else {
                            alert("Ajax error: " + this.statusText);
                        }
                }
        }
    request.send();
}

function setQueue(){
	
	var queue = document.getElementById('queue').value;
	var quickSubject = document.getElementById('quickSubject').value;
	
	var subPattern = /\[WRKBENCH\]/g;
	var result = subPattern.test(quickSubject);
	
	if (result){
		document.getElementById('queue').value = 'uss-helpdesk-workbench';
	}else{
		document.getElementById('queue').value = 'uss-helpdesk';	
	}
	
}

function createAndResolve(){
	
	var createAndResolve = document.getElementById('createAndResolve').value;
	
	params = "subject="+createAndResolve;
	request = new ajaxRequest();
	request.open("POST", "../bin/quickSubject.cgi",true);
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded; ");
	request.setRequestHeader("Content-length", params.length);
	request.setRequestHeader("Connection", "close");

    
    request.onreadystatechange = function()
        {
            if (this.readyState == 4)
                {
                    if (this.status == 200){
                            if (this.responseText){
                                    document.getElementById('description').value = this.responseText;
									document.getElementById('subject').value = createAndResolve;
									if (this.responseText == ''){
										document.getElementById('status').value = 'new';
									} else {
										document.getElementById('status').value = 'resolved';
										}
                                } else {
                                    alert("Ajax error: No data received");
                                }
                        } else {
                            alert("Ajax error: " + this.statusText);
                        }
                }
        }
    request.send(params);
}

function clearForm(){
	$(':input').each(function(){
    	switch(this.type){
    	case 'password':
    	case 'select-multiple':
    	case 'select-one':
    	case 'text':
    	case 'textarea':
    		$(this).val('');
    		break;
    	case 'checkbox':
    	case 'radio':
    		this.checked = false;
    		break;
    	}
	});
}
