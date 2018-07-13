/*----
    getCurrentUserName()
	Function to print the user name.
	0 -> First Student, 1 -> Second Student, -1 -> Both Student
----*/
function getCurrentUserName(index)
{	
	var dataparam = "oper=showname&testerid="+$('#hidtesterid').val()+"&testerid1="+$('#hidtesterid1').val()+"&index="+index;	
	$.ajax({
		type: 'post',
		url: '../../assignment/science/assignment-science-playerajax.php',
		data: dataparam,
		async:false,
		success:function(ajaxdata) {
			document.write(ajaxdata);
		}
	});
	
}

/*----
    getClassInfoStr()
----*/
function getClassInfoStr()
{
	document.write();
}

/*----
    getUserVar()
	Function to print the values for the particular variable
----*/
function getUserVar(variable)
{
	var dataparam = "oper=printvariables&scheduleid="+$('#hidscheduleid').val()+"&scheduletype="+$('#hidscheduletype').val()+"&moduleid="+$('#hidmoduleid').val()+"&testerid="+$('#hidtesterid').val()+"&testerid1="+$('#hidtesterid1').val()+"&printvariable="+variable;	
	$.ajax({
		type: 'post',
		url: '../../assignment/science/assignment-science-playerajax.php',
		data: dataparam,
		async:false,
		success:function(ajaxdata) {
			document.write(ajaxdata);
		}
	});
}