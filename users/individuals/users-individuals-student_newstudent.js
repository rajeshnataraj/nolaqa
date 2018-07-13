/*----
    fn_changecity()
	Function to show the city drop down
----*/
function fn_changecity(statevalue){
	 
	var dataparam = "oper=changecity&statevalue="+statevalue;
	$.ajax({
		type: 'post',
		url: 'users/individuals/users-individuals-student_newstudentdb.php',
		data: dataparam,
		beforeSend: function(){
			$('#divddlcity').html('<img src="img/loader.gif" width="120"  border="0" />'); 
		},
		success:function(ajaxdata) {
			$('#divddlcity').html(ajaxdata);
			if($("#cit").attr('class')=='field row error')
				$('#ddlcity').valid();
		}
		
	});	
}
/*----
    fn_loaddistrict()
	Function to show the district drop down
----*/
function fn_loaddistrict(cityname){
		
	var dataparam = "oper=changedistrict&cityname="+cityname+"&statevalue="+$('#ddlstate').val();
	$.ajax({
		type: 'post',
		url: 'users/individuals/users-individuals-student_newstudentdb.php',
		data: dataparam,
		beforeSend: function(){
			$('#divddldist').html('<img src="img/loader.gif" width="120"  border="0" />'); 
		},
		success:function(ajaxdata) {
			$('#divddldist').html(ajaxdata);
			if($("#dit").attr('class')=='field row error')
				$('#ddldist').valid();
		}
	});	
}
/*----
    fn_loadschool()
	Function to show the school drop down
----*/
function fn_loadschool(state,city){
	var distid = $('#ddldist').val();
	var dataparam = "oper=changeschool&distid="+distid+"&state="+state+"&city="+city;
	$.ajax({
		type: 'post',
		url: 'users/individuals/users-individuals-student_newstudentdb.php',
		data: dataparam,
		beforeSend: function(){
			$('#divddlshl').html('<img src="img/loader.gif" width="120"  border="0" />'); 
		},
		success:function(ajaxdata) {
			$('#divddlshl').html(ajaxdata);
			if($("#shl").attr('class')=='field row error')
				$('#ddlshl').valid();
		}
	});	
}
/*----
    fn_loadschoolusrcount()
	Function to show School List
----*/

function fn_loadschoolusrcount(schid) {
	
	var dataparam = "oper=loadusercount&schid="+schid;
	$.ajax({
		type: 'post',
		url: 'users/individuals/users-individuals-student_newstudentdb.php',
		data: dataparam,
		success:function(ajaxdata) {
			$('#divruser').html(ajaxdata);
		}
	});
}
/*----
    fn_changecity1()
	Function to show city1 drop down
----*/
function fn_changecity1(statevalue){
	var dataparam = "oper=changecity1&statevalue="+statevalue;
	$.ajax({
		type: 'post',
		url: 'users/individuals/users-individuals-student_newstudentdb.php',
		data: dataparam,
		beforeSend: function(){
			$('#divddlcity1').html('<img src="img/loader.gif" />'); 
		},
		success:function(ajaxdata) {
			$('#divddlcity1').html(ajaxdata);
		}
		
	});	
}
/*----
    fn_changezip1()
	Function to show zip1 drop down
----*/
function fn_changezip1(cityvalue){
	var dataparam = "oper=changezip1&cityvalue="+cityvalue+"&statevalue="+$('#ddlstate1').val();
	$.ajax({
		type: 'post',
		url: 'users/individuals/users-individuals-student_newstudentdb.php',
		data: dataparam,
		beforeSend: function(){
			$('#divddlzip1').html('<img src="img/loader.gif"  />'); 
		},
		success:function(ajaxdata) {
			$('#divddlzip1').html(ajaxdata);
		}
		
	});	
}

/*----
    fn_createstudent()
	Function to save the student details
----*/
function fn_createstudent(id){
	
	var state = $('#ddlstate').val();
	var city = $('#ddlcity').val();
	var distid = $('#ddldist').val();
	var shlid = $('#ddlshl').val();
		
	var fname = $('#fname').val();
	var lname = $('#lname').val();
	var uname = $('#uname').val();
	var password = $('#txtpassword').val();
	var ddlgrade = $('#ddlgrade').val();
	
	var gfname = $('#gfname').val();
	var glname = $('#glname').val();
	var email = $('#email').val();
	var address1 = $('#address1').val();
	var state1 = $('#ddlstate1').val();
	var city1 = $('#ddlcity1').val();
	var zipcode1 = $('#ddlzip1').val();
	var officeno = $('#officeno').val();
	var mobileno = $('#mobileno').val();
	var homeno = $('#homeno').val();
	var photo = $('#hiduploadfile').val();

	
	if(id==0){	
		actionmsg = "Saving";
		alertmsg = "Student account is created successfully";
	}
	else{
		actionmsg = "Updating";
		alertmsg = "Student account is updated successfully";
	}
	
	if($("#validate").validate().form())
	{
        var dataparam = "oper=savestudent&state="+state+"&city="+city+"&distid="+distid+"&shlid="+shlid+"&fname="+escapestr(fname)+"&lname="+escapestr(lname)+"&uname="+escapestr(uname)+"&password="+password+"&ddlgrade="+ddlgrade+"&gfname="+escapestr(gfname)+"&glname="+escapestr(glname)+"&email="+email+"&address1="+escapestr(address1)+"&state1="+state1+"&city1="+city1+"&zipcode1="+zipcode1+"&officeno="+officeno+"&mobileno="+mobileno+"&homeno="+homeno+"&photo="+photo+"&tags="+escapestr($('#form_tags_newstudent').val())+"&id="+id;
        //var dataparam = "oper=savestudent&state="+state+"&city="+city+"&distid="+distid+"&shlid="+shlid+"&fname="+escapestr(fname)+"&lname="+escapestr(lname)+"&ddlgrade="+ddlgrade+"&gfname="+escapestr(gfname)+"&glname="+escapestr(glname)+"&address1="+escapestr(address1)+"&state1="+state1+"&city1="+city1+"&zipcode1="+zipcode1+"&officeno="+officeno+"&mobileno="+mobileno+"&homeno="+homeno+"&photo="+photo+"&tags="+escapestr($('#form_tags_newstudent').val())+"&id="+id;
		$.ajax({
			type: 'post',
			url: 'users/individuals/users-individuals-student_newstudentdb.php',
			data: dataparam,
			beforeSend: function(){
				showloadingalert(actionmsg+", please wait.");	
			},
			success:function(data) {
				if(data=="success"){
					$('.lb-content').html(alertmsg);					
					setTimeout('closeloadingalert();',1000);				
					setTimeout("removesections('#users-individuals');",1500);
                    setTimeout(function(){
                        $("#users-individuals_profile, #users-individuals-student_newstudent, #users-individuals-settings").remove();
                    }, 1500);
					setTimeout('showpages("users-individuals-student","users/individuals/users-individuals-student.php");',1500);
				}
				else if(data=="fail"){
					$('.lb-content').html("Incorrect Data");
					setTimeout('closeloadingalert()',1000);
				}
			}
			
		});
	}
}


function fn_updatestudent(id){

    var state = $('#ddlstate').val();
    var city = $('#ddlcity').val();
    var distid = $('#ddldist').val();
    var shlid = $('#ddlshl').val();

    var fname = $('#fname').val();
    var lname = $('#lname').val();
    //var uname = $('#uname').val();
    //var password = $('#txtpassword').val();
    var ddlgrade = $('#ddlgrade').val();

    var gfname = $('#gfname').val();
    var glname = $('#glname').val();
    //var email = $('#email').val();
    var address1 = $('#address1').val();
    var state1 = $('#ddlstate1').val();
    var city1 = $('#ddlcity1').val();
    var zipcode1 = $('#ddlzip1').val();
    var officeno = $('#officeno').val();
    var mobileno = $('#mobileno').val();
    var homeno = $('#homeno').val();
    var photo = $('#hiduploadfile').val();


    if(id==0){
        actionmsg = "Saving";
        alertmsg = "Student account is created successfully";
    }
    else{
        actionmsg = "Updating";
        alertmsg = "Student account is updated successfully";
    }

    if($("#validate").validate().form())
    {
        //var dataparam = "oper=savestudent&state="+state+"&city="+city+"&distid="+distid+"&shlid="+shlid+"&fname="+escapestr(fname)+"&lname="+escapestr(lname)+"&uname="+escapestr(uname)+"&password="+password+"&ddlgrade="+ddlgrade+"&gfname="+escapestr(gfname)+"&glname="+escapestr(glname)+"&email="+email+"&address1="+escapestr(address1)+"&state1="+state1+"&city1="+city1+"&zipcode1="+zipcode1+"&officeno="+officeno+"&mobileno="+mobileno+"&homeno="+homeno+"&photo="+photo+"&tags="+escapestr($('#form_tags_newstudent').val())+"&id="+id;
        var dataparam = "oper=updatestudent&state="+state+"&city="+city+"&distid="+distid+"&shlid="+shlid+"&fname="+escapestr(fname)+"&lname="+escapestr(lname)+"&ddlgrade="+ddlgrade+"&gfname="+escapestr(gfname)+"&glname="+escapestr(glname)+"&address1="+escapestr(address1)+"&state1="+state1+"&city1="+city1+"&zipcode1="+zipcode1+"&officeno="+officeno+"&mobileno="+mobileno+"&homeno="+homeno+"&photo="+photo+"&tags="+escapestr($('#form_tags_newstudent').val())+"&id="+id;
        $.ajax({
            type: 'post',
            url: 'users/individuals/users-individuals-student_newstudentdb.php',
            data: dataparam,
            beforeSend: function(){
                showloadingalert(actionmsg+", please wait.");
            },
            success:function(data) {
                if(data=="success"){
                    $('.lb-content').html(alertmsg);
                    setTimeout('closeloadingalert();',1000);
                    setTimeout("removesections('#users-individuals');",1500);
                    setTimeout(function(){
                        $("#users-individuals_profile, #users-individuals-student_newstudent, #users-individuals-settings").remove();
                    }, 1500);
                    setTimeout('showpages("users-individuals-student","users/individuals/users-individuals-student.php");',1500);
                }
                else if(data=="fail"){
                    $('.lb-content').html("Incorrect Data");
                    setTimeout('closeloadingalert()',1000);
                }
            }

        });
    }
}

/*----
    fn_deletstudent()
	Function to delet the student details
----*/
function fn_deletstudent(id){
	
	var dataparam = "oper=deletstudent&editid="+id;
	actionmsg = "Deleting";
	alertmsg = "Student account is deleted successfully";
	
	$.Zebra_Dialog('Deleting this User will also delete all associated records. Are you sure you want to Delete the User?',
		{
			'type': 'confirmation',
			'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
					
				$.ajax({
					type: 'post',
					url: 'users/individuals/users-individuals-student_newstudentdb.php',
					data: dataparam,
					beforeSend: function(){
						showloadingalert(actionmsg+", please wait.");	
					},
					success:function() {
						$('.lb-content').html(alertmsg);
							setTimeout('closeloadingalert();',1000);					
							setTimeout("removesections('#users-individuals');",1500);
							setTimeout('showpages("users-individuals-student","users/individuals/users-individuals-student.php");',1500);
					}
				});	
			}}
		]
	});
}
/*----
    fn_changestatecity()
	Function to change the state and city
----*/
function fn_changestatecity(){
		var dataparam = "oper=changestatecity&schid="+$('#ddlshl').val();		
		$.ajax({
			type: 'post',
			url: 'users/individuals/users-individuals-student_newstudentdb.php',
			data: dataparam,
			beforeSend: function(){
			},
			success:function(data) {				
				data = data.split('~');
				$('#stat').html(data[0]);
				$('#cit').html(data[1]);
				$('#dit').html(data[2]);
			}
			
		});
} 
 //sort/filter the student list other than the “tag�? feature START



 //sort/filter the student list other than the “tag�? feature START
            
function fn_showstudents(schoolid,distid){
    
    if(schoolid=='0' && distid=='0')
    {
        var schoolid=$('#schoolid').val();
        var distid=$('#districtid').val();
    }
    
    
    $("#users-individuals-student_delstudent").hide();
    var dataparam = "oper=showstudents&distid="+distid+"&schoolid="+schoolid;		
    $.ajax({
            type: 'post',
            url: 'users/individuals/users-individuals-student_newstudentdb.php',
            data: dataparam,
            success:function(data) {
            $("#loadstudents").hide();
            $("#studentlist").show();
            $("#studentlist").html(data);
            }
     });
}


function fn_session(schoolid,distid){
   
    var dataparam = "oper=session&distid="+distid+"&schoolid="+schoolid;		
    $.ajax({
            type: 'post',
            url: 'users/individuals/users-individuals-student_newstudentdb.php',
            data: dataparam,
            success:function(data) {
            
            }
     });
}
/*----
    fn_selradio()
	Function to select radio value 
----*/
function fn_selradio(){
    if ($("#tag").prop("checked")) {
       var radval=5;
       var stuid=$('#form_tags_student').val();
   }
   else if($("#search").prop("checked")){
       var radval=6;
       var stuid=$('#form_tags_studentname').val();

   }
   else if($("#classname").prop("checked")){
       var radval=7;
       var stuid=$('#selectstu').val();
       //alert(stuid);
   }
   else if($("#gradelevel").prop("checked")){
       var radval=8;
       var stuid=$('#selectgradestu').val();
    
   }
    
    var dataparam="oper=seldelstudents&studid="+stuid+"&radval="+radval;
    $.ajax({
            type: 'post',
            url: 'users/individuals/users-individuals-student_newstudentdb.php',
            data: dataparam,
            beforeSend: function(){
            },
            success:function(ajaxdata) {
               $('#dupstu').html(ajaxdata);
            
            }
     });
}
/*----
    fn_movealllistitems()
	Function to move all items from lest to right and right to left
----*/
function fn_movealllistitems(leftlist,rightlist,id,courseid)
{
		
	if(id == 0)
	{
		$("div[id^="+leftlist+"_]").each(function()
		{
			var clas = $(this).attr('class');
			var temp = $(this).attr('id').replace(leftlist,rightlist);
			
			$(this).attr('id',temp);
			$('#'+rightlist).append($(this));
			
			if($(this).attr('class') == 'draglinkleft') {
				$(this).removeClass("draglinkleft draglinkright");
				$(this).addClass("draglinkright");
			} else {
				$(this).removeClass("draglinkleft draglinkright");
				$(this).addClass("draglinkleft");				
			}
		});
	}
	else
	{
		var clas=$('#'+leftlist+'_'+courseid).attr('class');
		
		if(clas=="draglinkleft")
		{
			$('#'+rightlist).append($('#'+leftlist+' #'+leftlist+'_'+courseid));
			$('#'+leftlist+'_'+courseid).removeClass('draglinkleft').addClass('draglinkright');
			
			var temp = $('#'+leftlist+'_'+courseid).attr('id').replace(leftlist,rightlist);					
			var ids='id';
			$('#'+leftlist+'_'+courseid).attr(ids,temp);
		}
		else 
		{	
			$('#'+leftlist).append($('#'+rightlist+' #'+rightlist+'_'+courseid));
			$('#'+rightlist+'_'+courseid).removeClass('draglinkright').addClass('draglinkleft');
		
			var temp = $('#'+rightlist+'_'+courseid).attr('id').replace(rightlist,leftlist);					
			var ids='id';
			$('#'+rightlist+'_'+courseid).attr(ids,temp);
                        
		}
	}
        	
}
function fn_deletestudents(){
    var sid = [];
    actionmsg = "Deleting";
    alertmsg = "Student account is deleted successfully";
    $("div[id^=list4_]").each(function()
    {
            var studentid = $(this).attr('id').replace('list4_','');
            sid.push(""+studentid+"");


    });
    var dataparam = "oper=delstudents&sid="+sid;		
    $.ajax({
            type: 'post',
            url: 'users/individuals/users-individuals-student_newstudentdb.php',
            data: dataparam,
            beforeSend: function(){
             showloadingalert(actionmsg+", please wait.");	
            },
            success:function(data) {
                $('.lb-content').html(alertmsg);
                    setTimeout('closeloadingalert();',1000);					
                    setTimeout("removesections('#users-individuals');",1500);
                    setTimeout('showpages("users-individuals-student","users/individuals/users-individuals-student.php");',1500);
                
            
            }
     });
 
    
}
function fn_chngeload(){
    $("#users-individuals-student").load("users/individuals/users-individuals-student.php #users-individuals-student > *", function(){
                                                              
     });
     setTimeout("removesections('#users-individuals-student_delstudent');",1500);
      setTimeout('showpages("users-individuals-student","users/individuals/users-individuals-student.php");',1500);

}


function fn_showschoolind(distid)
{ 
    
    var dataparam = "oper=showschools&distid="+distid;
    
    $.ajax({
            type: 'post',
            url: 'users/individuals/users-individuals-student_newstudentdb.php',
            data: dataparam,
            beforeSend: function(){
            },
            success:function(data) {
                    $('#schooldiv').show();   
                    $('#schooldiv').html(data);//Used to load the student details in the dropdown
            }
    });
}

function fn_schoolpurchasestu()
{ 
    
    var dataparam = "oper=schoolpurchase";
    
    $.ajax({
            type: 'post',
            url: 'users/individuals/users-individuals-student_newstudentdb.php',
            data: dataparam,
            beforeSend: function(){
            },
            success:function(data) {
                    $('#districtind').hide();
                    $('#shpurchase').show();   
                    $('#purchasediv').html(data);
            }
    });
}

function fn_homepurchasestu(){
    
    $("#users-individuals-student_delstudent").hide();
    var dataparam = "oper=homepurchasestu";		
    $.ajax({
            type: 'post',
            url: 'users/individuals/users-individuals-student_newstudentdb.php',
            data: dataparam,
            success:function(data) {
            $("#studentlist").html(data);
            }
     });
}

/* new code for dashboard icons or list */

function fn_details()  //  details icon image codeing
{
    
    if ($("#tag").prop("checked") || $("#search").prop("checked")) 
    {
        $('#details_icon_studentlist').show();
        $('#large_icon_studentlist').hide();

        $('#large_icon_recordlist').hide();
        $('#details_icon_titleview').show();
        $('#details_icon_recordlist').show();
        
        setTimeout("removesections('#loadstudents_details_icon');",1500);

        $('#loadstudents').hide();
        $('#details_icon_loadstudents').show();
        $('#listview').val(1);
    }
    else if($("#classname").prop("checked"))
    {
    
        var id = $('#classids').val();
        var dataparam = "oper=detailsviewicon&classid="+id;	

        $.ajax({
                type: 'post',
                url: 'users/individuals/users-individuals-student_newstudentdb.php',
                data: dataparam,
                success:function(data) {
                $('#loadstudents').hide();
                $('#studentlist').hide()
                $('#loadstudents_details_icon').show();
                $('#loadstudents_details_icon').html(data);
                $('#listview').val(1);
                
                }
         });
            
    }
    
    else if($("#gradelevel").prop("checked"))
    {
    var gradid= $('#selectgradestu').val();
    var dataparam = "oper=gradestudentsdetails&gradeid="+gradid; 
            $.ajax({
                type: 'post',
                url: 'users/individuals/users-individuals-student_newstudentdb.php',
                data: dataparam,
                success:function(data) {
                $("#loadstudents").hide();
                $("#studentlist").hide();
                $("#details_icon_gradeloadstudents").show();
                $("#details_icon_gradeloadstudents").html(data);
                $('#listview').val(1);
                }
             });
    }
    setTimeout("removesections('#users-individuals-student');",500);
    
}
function fn_large()    //large icon image coding
{
    if ($("#tag").prop("checked") || $("#search").prop("checked")) 
    {
        $('#details_icon_studentlist').hide();
        $('#details_icon_loadstudents').hide();
        $('#large_icon_studentlist').show();
        
        $('#details_icon_recordlist_desc').hide();
        $('#details_icon_titleview').hide();
        $('#details_icon_recordlist').hide();
        $('#large_icon_recordlist').show();
        $('#listview').val(2);
    }
    else if($("#classname").prop("checked"))
    {
    
        var id = $('#classids').val();
        var dataparam = "oper=clsstudents&classid="+id;

        $.ajax({
            type: 'post',
            url: 'users/individuals/users-individuals-student_newstudentdb.php',
            data: dataparam,
            success:function(data) {
            $('#loadstudents_details_icon').hide();
            $('#details_icon_loadstudents').hide();
            $("#studentlist").show();
            $('#studentlist').html(data);
            $('#listview').val(2);
            }
        });
            
    }
    else if($("#gradelevel").prop("checked"))
    {
    var gradid= $('#selectgradestu').val();
    var dataparam = "oper=gradestudents&gradeid="+gradid; 	
            $.ajax({
                type: 'post',
                url: 'users/individuals/users-individuals-student_newstudentdb.php',
                data: dataparam,
                success:function(data) {
                $("#details_icon_gradeloadstudents").hide();
                $("#details_gradeloadstudent_filter").hide();
                $("#studentlist").show();
                $("#studentlist").html(data);
                $('#listview').val(2); 
                setTimeout("removesections('#studentlist');",500);
                }
             });
    }

    
    
    setTimeout("removesections('#users-individuals-student');",500);
}

function fn_newstudent(){
    
    setTimeout("removesections('#users-individuals-student');",500);
    setTimeout('showpages("users-individuals-student_newstudent","users/individuals/users-individuals-student_newstudent.php");',500);
    
}

function fn_delstudent(){
    setTimeout("removesections('#users-individuals-student');",500);
    setTimeout('showpages("users-individuals-student_newstudent","users/individuals/users-individuals-student_delstudent.php");',500);
}
function fn_profile(btn){
	var studentBtn = $(btn);
	var student = studentBtn.attr("name");
    var dataparam = "student="+student;
    console.log(dataparam);

    $.ajax({
        type: 'post',
        url: 'users/individuals/profile.php',
        data: dataparam,
        beforeSend: function(){
        },
        success:function(data) {
            $("#users-individuals_profile, #users-individuals-student_newstudent, #users-individuals-settings").remove();
            setTimeout('showpageswithpostmethod("profile","users/individuals/profile.php?student='+student+'","");',200);
        }
    });
}
function fn_studentclick(id,sdistid,sshlid,suserid) // click student then display student details
{
    var dataparam = "oper=studentclick&id="+id+"&daistid="+sdistid+"&sshlid="+sshlid+"&suserid="+suserid;

     $.ajax({
            type: 'post',
            url: 'users/individuals/profile.php',
            data: dataparam,
            beforeSend: function(){
            },
            success:function(data) {
				setTimeout("removesections('#users-individuals-student');",200);
				setTimeout('showpageswithpostmethod("profile","users/individuals/profile.php","id='+id+'");',200);
            }
    });
}

function fn_first(status) // codeing for asc and desc
{
    var listview=$('listview').val();
    
    if ($("#tag").prop("checked"))
    {
       var radval=5;
    }
    else if($("#search").prop("checked"))
    {
        var radval=6;
    }
    else if($("#classname").prop("checked"))
    {
        var radval=7;
    }
    else if($("#gradelevel").prop("checked"))
    {
        var radval=8;
    }
   
    if ($("#tag").prop("checked") || $("#search").prop("checked")) 
    {
        var first=$('#firstname').val();
        var last=$('#lastname').val();
        var user=$('#username').val();
        var password=$('#password').val();
        
        if(status=='0')
        {
            if(first=='0')
            {
                var filter='1';
            }
            else
            {
                var filter='0';
            }
        }
        else if(status=='1')
        {
            if(last=='0')
            {
                var filter='1';
            }
            else
            {
                var filter='0';
            }
        }
        else if(status=='2')
        {
            if(user=='0')
            {
                var filter='1';
            }
            else
            {
                var filter='0';
            }
        }
        else if(status=='0')
        {
            if(password=='3')
            {
                var filter='1';
            }
            else
            {
                var filter='0';
            }
        }
        var dataparam = "oper=firsname&filter="+filter+"&status="+status+"&radval="+radval;
        $.ajax({
                type: 'post',
                url: 'users/individuals/users-individuals-student_newstudentdb.php',
                data: dataparam,
                beforeSend: function(){
                },
                success:function(data) 
                {
                    $('#details_icon_recordlist').hide();
                    $('#details_icon_recordlist_desc').show();   
                    $('#details_icon_recordlist_desc').html(data);//Used to load the student details in the dropdown

                    $('#firstname').val(filter);
                    $('#lastname').val(filter);
                    $('#username').val(filter);
                    $('#password').val(filter);
                    $('#listview').val(1);
                }
        });
    }
    else if($("#classname").prop("checked"))
    {
        var first=$('#classfirstname').val();
        var last=$('#classlastname').val();
        var user=$('#classusername').val();
        var password=$('#classpassword').val();
          var clsid = $('#classids').val();
    
        if(status=='0')
        {
            if(first=='0')
            {
                var filter='1';
            }
            else
            {
                var filter='0';
            }
        }
        else if(status=='1')
        {
            if(last=='0')
            {
                var filter='1';
            }
            else
            {
                var filter='0';
            }
        }
        else if(status=='2')
        {
            if(user=='0')
            {
                var filter='1';
            }
            else
            {
                var filter='0';
            }
        }
        else if(status=='0')
        {
            if(password=='3')
            {
                var filter='1';
            }
            else
            {
                var filter='0';
            }
        }
        
        var dataparam = "oper=firsname&filter="+filter+"&status="+status+"&radval="+radval+"&classid="+clsid;

        $.ajax({
                type: 'post',
                url: 'users/individuals/users-individuals-student_newstudentdb.php',
                data: dataparam,
                beforeSend: function(){
                },
                success:function(data) 
                {
                    $('#details_icon_recordlist').hide();
                    $('#loadstudents_details_icon').hide();  
                    $('#details_icon_loadstudents').show();   
                    $('#details_icon_loadstudents').html(data);//Used to load the student details in the dropdown

                    $('#classfirstname').val(filter);
                    $('#classlastname').val(filter);
                    $('#classusername').val(filter);
                    $('#classpassword').val(filter);
                    $('#listview').val(1);
                }
        });
    }
    
    else if($("#gradelevel").prop("checked"))
    {
        var first=$('#gradefirstname').val();
        var last=$('#gradelastname').val();
        var user=$('#gradeusername').val();
        var password=$('#gradepassword').val();
        var gradeid = $('#selectgradestu').val();
    
        if(status=='0')
        {
            if(first=='0')
            {
                var filter='1';
            }
            else
            {
                var filter='0';
            }
        }
        else if(status=='1')
        {
            if(last=='0')
            {
                var filter='1';
            }
            else
            {
                var filter='0';
            }
        }
        else if(status=='2')
        {
            if(user=='0')
            {
                var filter='1';
            }
            else
            {
                var filter='0';
            }
        }
        else if(status=='0')
        {
            if(password=='3')
            {
                var filter='1';
            }
            else
            {
                var filter='0';
            }
        }
        
        var dataparam = "oper=firsname&filter="+filter+"&status="+status+"&radval="+radval+"&gradeid="+gradeid;

        $.ajax({
                type: 'post',
                url: 'users/individuals/users-individuals-student_newstudentdb.php',
                data: dataparam,
                beforeSend: function(){
                },
                success:function(data) 
                {
                    $('#details_icon_gradeloadstudents').hide(); 
                    $('#studentlist').hide();
                    $('#details_gradeloadstudent_filter').show();
                    $('#details_gradeloadstudent_filter').html(data);//Used to load the student details in the dropdown
                    

                    $('#gradefirstname').val(filter);
                    $('#gradelastname').val(filter);
                    $('#gradeusername').val(filter);
                    $('#gradepassword').val(filter);
                    $('#listview').val(1);
                }
        });
    }
}

function fn_showclastudent(id) //  select class student then display student details
{
    var listview = $('#listview').val();
    if(listview=='2')
    {
        var dataparam = "oper=clsstudents&classid="+id;	
        $.ajax({
                type: 'post',
                url: 'users/individuals/users-individuals-student_newstudentdb.php',
                data: dataparam,
                success:function(data) {
                $("div#studentlist").hide();
                $('#details_icon_loadstudents').hide();
                $("div#loadstudents").show();
                $('#loadstudents').html(data);
                }
         });
    }
    else
    {
        var dataparam = "oper=detailsviewicon&classid="+id;
        $.ajax({
                type: 'post',
                url: 'users/individuals/users-individuals-student_newstudentdb.php',
                data: dataparam,
                success:function(data) {
                $("#studentlist").hide();
                $("#details_icon_loadstudents").hide();
                $("#details_icon_gradeloadstudents").hide();
                $("#studentlist").hide();
                $("#loadstudents_details_icon").show();
                $('#loadstudents_details_icon').html(data);
                }
         });
    }
}

function fn_showgradestudent(id) // select grade level then display grade level student
{
    var listview = $('#listview').val();

    if(listview=='2')
    {
        $("#users-individuals-student_delstudent").hide();
        var dataparam = "oper=gradestudents&gradeid="+id+"&distid="+$('#districtid').val()+"&schoolid="+$('#schoolid').val();
        $.ajax({
                type: 'post',
                url: 'users/individuals/users-individuals-student_newstudentdb.php',
                data: dataparam,
                success:function(data) {
                $("div#studentlist").hide();
                $('#details_icon_loadstudents').hide();
                $("div#loadstudents").show();
                $('#loadstudents').html(data);

                }
         });
    }
    else
    {
        var dataparam = "oper=gradestudentsdetails&gradeid="+id+"&distid="+$('#districtid').val()+"&schoolid="+$('#schoolid').val();		
        $.ajax({
             type: 'post',
             url: 'users/individuals/users-individuals-student_newstudentdb.php',
             data: dataparam,
             success:function(data) {
             $("#gradestudentsdetails").hide();
             $("#loadstudents_details_icon").hide();
             $("#details_gradeloadstudent_filter").hide();
             $("#details_icon_gradeloadstudents").hide();
             $("#studentlist").html(data);

             }
         });
    }
}
