// JavaScript Document

/*----
    fn_movealllistitems()
	Function to be used for drag the items from one box to another
----*/
function fn_movealllistitems(leftlist,rightlist,id,courseid)
{	
	if(id == 0)
	{
		$("div[id^="+leftlist+"_]").each(function()
		{
			if(!$(this).hasClass('dim')){
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

	if($('#hidlicense').val()!='0'){

		if(id == 0) {
			if(rightlist=="list4"){
	
				var addall = 1;
				fn_load_modulesave($('#hidlicense').val(),id,addall);

			}
			else if(rightlist=="list3"){  
				var remall = 0;
				fn_load_modulesave($('#hidlicense').val(),id,remall); }
		}
		else { 

			if(rightlist=="list4" && clas=="draglinkleft" ){

			var addall = 5;
			fn_load_modulesave($('#hidlicense').val(),courseid,addall);

			}
			else if(rightlist=="list4" && clas!="draglinkleft"){
                           
var addall = 3;
			fn_load_modulesave($('#hidlicense').val(),courseid,addall);

}
			


		}
			
	}
        if(leftlist=="list3" ||rightlist=="list4" &&   leftlist=="list4" || rightlist=="list3"  )
        {
        var list3 = [];
	$("div[id^=list3_]").each(function(){
		list3.push($(this).attr('id').replace('list3_',''));
	});
	$('#leftmoddiv').html(list3.length);

	var list4 = [];
	$("div[id^=list4_]").each(function(){
		list4.push($(this).attr('id').replace('list4_',''));
	});
	$('#rightmoddiv').html(list4.length);

			fn_load_product($('#hidlicense').val(),'content'); //load product details list
                        fn_load_assessment($('#hidlicense').val()); //load assessment details list
			
        }
     
        if(leftlist=="list11" ||rightlist=="list12" &&   leftlist=="list12" || rightlist=="list11"  )
        {
            var list11 = [];
            $("div[id^=list11_]").each(function(){
                    list11.push($(this).attr('id').replace('list11_',''));
            });
            $('#leftquests').html(list11.length);

            var list12 = [];
            $("div[id^=list12_]").each(function(){
                    list12.push($(this).attr('id').replace('list12_',''));
            });
            $('#rightquests').html(list12.length);
			
			fn_load_product($('#hidlicense').val(),'content'); //load product details list
                        fn_load_assessment($('#hidlicense').val()); //load assessment details list
        }
        
        if(leftlist=="list13" ||rightlist=="list14" &&   leftlist=="list14" || rightlist=="list13"  )
        {
            var list13 = [];
            $("div[id^=list13_]").each(function(){
                    list13.push($(this).attr('id').replace('list13_',''));
            });
            $('#leftexpeditions').html(list13.length);

            var list14 = [];
            $("div[id^=list14_]").each(function(){
                    list14.push($(this).attr('id').replace('list14_',''));
            });
            $('#rightexpeditions').html(list14.length);
			
			fn_load_product($('#hidlicense').val(),'content'); // load product details list
                        fn_load_assessment($('#hidlicense').val()); //load assessment details list
        }
        
        if(leftlist=="list9" ||rightlist=="list10" &&   leftlist=="list10" || rightlist=="list9"  )
        {
            var list9 = [];
            $("div[id^=list9_]").each(function(){
                    list9.push($(this).attr('id').replace('list9_',''));
            });
            $('#leftassessments').html(list9.length);

            var list10 = [];
            $("div[id^=list10_]").each(function(){
                    list10.push($(this).attr('id').replace('list10_',''));
            });
            $('#rightassessments').html(list10.length);
        } 
     

	if(leftlist=="list5" || leftlist=="list6" && rightlist=="list6" || rightlist=="list5"  )
	{
            var list5 = [];
            $("div[id^=list5_]").each(function(){
		list5.push($(this).attr('id').replace('list5_',''));
            });
            $('#leftunits').html(list5.length);

            var list6 = [];
            $("div[id^=list6_]").each(function(){
		list6.push($(this).attr('id').replace('list6_',''));
            });
            $('#rightunits').html(list6.length);
        
	  fn_load_lessons($('#hidlicense').val());
	}
        
        if(leftlist=="list7" || leftlist=="list8" && rightlist=="list8" || rightlist=="list7"  )
	{
            var list7 = [];
            $("div[id^=list7_]").each(function(){
		list7.push($(this).attr('id').replace('list7_',''));
            });
            $('#leftipls').html(list7.length);

            var list8 = [];
            $("div[id^=list8_]").each(function(){
		list8.push($(this).attr('id').replace('list8_',''));
            });
            $('#rightipls').html(list8.length);
       
		fn_load_product($('#hidlicense').val(),'content'); // load product details list
                fn_load_assessment($('#hidlicense').val()); //load product details list
       
	}
        
        if(leftlist=="list15" || leftlist=="list16" && rightlist=="list16" || rightlist=="list15"  )
	{
            var list15 = [];
            $("div[id^=list15_]").each(function(){
		list15.push($(this).attr('id').replace('list15_',''));
            });
            $('#leftcourses').html(list15.length);

            var list16 = [];
            $("div[id^=list16_]").each(function(){
		list16.push($(this).attr('id').replace('list16_',''));
            });
            $('#rightcourses').html(list16.length);
            
		fn_load_pdlessons($('#hidlicense').val());
        }
        
        if(leftlist=="list23" || leftlist=="list24" && rightlist=="list24" || rightlist=="list23"  )
	{
            var list23 = [];
            $("div[id^=list23_]").each(function(){
		list23.push($(this).attr('id').replace('list23_',''));
            });
            $('#leftpdlessons').html(list23.length);

            var list24 = [];
            $("div[id^=list24_]").each(function(){
		list24.push($(this).attr('id').replace('list24_',''));
            });
            $('#rightpdlessons').html(list24.length);
		
		fn_load_product($('#hidlicense').val(),'content'); // load product details list
                fn_load_assessment($('#hidlicense').val()); //load assessment details list
        }
        
        if(leftlist=="list25" || leftlist=="list26" && rightlist=="list26" || rightlist=="list25"  )
	{
            var list25 = [];
            $("div[id^=list25_]").each(function(){
		list25.push($(this).attr('id').replace('list25_',''));
            });
            $('#leftmission').html(list25.length);
        
            var list26 = [];
            $("div[id^=list26_]").each(function(){
		list26.push($(this).attr('id').replace('list26_',''));
            });
            $('#rightmission').html(list26.length);
		
		fn_load_product($('#hidlicense').val(),'content'); // load product details list
                fn_load_assessment($('#hidlicense').val()); //load assessment details list
        }
        
        if(leftlist=="list31" || leftlist=="list32" && rightlist=="list32" || rightlist=="list31"  )
	{
            var list31 = [];
            $("div[id^=list31_]").each(function(){
		list31.push($(this).attr('id').replace('list31_',''));
            });
            $('#leftnondigi').html(list31.length);
        
            var list32 = [];
            $("div[id^=list32_]").each(function(){
		list32.push($(this).attr('id').replace('list32_',''));
            });
            $('#rightnondigi').html(list32.length);
		
		fn_load_product($('#hidlicense').val(),'content'); // load product details list
                
        }
        
        if(leftlist=="list27" || leftlist=="list28" && rightlist=="list28" || rightlist=="list27"  )
	{
            var list27 = [];
            $("div[id^=list27_]").each(function(){
		list27.push($(this).attr('id').replace('list27_',''));
            });
            $('#lefsosdocs').html(list27.length);
        
            var list28 = [];
            $("div[id^=list28_]").each(function(){
		list28.push($(this).attr('id').replace('list28_',''));
            });
            $('#rightsosdocs').html(list28.length);
        }
        
        
        if(leftlist=="list17" || leftlist=="list18" && rightlist=="list18" || rightlist=="list17"  )
	{
            var list17 = [];
            $("div[id^=list17_]").each(function(){
		list17.push($(this).attr('id').replace('list17_',''));
            });
            $('#leftsosunits').html(list17.length);

            var list18 = [];
            $("div[id^=list18_]").each(function(){
		list18.push($(this).attr('id').replace('list18_',''));
            });
            $('#rightsosunits').html(list18.length);
            
		fn_load_phases($('#hidlicense').val());
		fn_load_product($('#hidlicense').val(),'content'); // load product details list
	}
        
        if(leftlist=="list19" || leftlist=="list20" && rightlist=="list20" || rightlist=="list19"  )
	{
                var list19 = [];
            $("div[id^=list19_]").each(function(){
		list19.push($(this).attr('id').replace('list19_',''));
            });
            $('#leftsosphases').html(list19.length);

            var list20 = [];
            $("div[id^=list20_]").each(function(){
		list20.push($(this).attr('id').replace('list20_',''));
            });
            $('#rightsosphases').html(list20.length);
            
		fn_load_video($('#hidlicense').val());
	}
        
        if(leftlist=="list21" || leftlist=="list22" && rightlist=="list22" || rightlist=="list21"  )
	{
                var list21 = [];
            $("div[id^=list21_]").each(function(){
		list21.push($(this).attr('id').replace('list21_',''));
            });
            $('#leftsosvideos').html(list21.length);

            var list22 = [];
            $("div[id^=list22_]").each(function(){
		list22.push($(this).attr('id').replace('list22_',''));
            });
            $('#rightsosvideos').html(list22.length);
        }
	
	/*** pimproduct start line  ***/
	
	if(leftlist=="list29" || leftlist=="list30" && rightlist=="list30" || rightlist=="list29"  )
	{
            var list29 = [];
            $("div[id^=list29_]").each(function(){
		list29.push($(this).attr('id').replace('list29_',''));
            });
            $('#leftproduct').html(list29.length);

            var list30 = [];
            $("div[id^=list30_]").each(function(){
		list30.push($(this).attr('id').replace('list30_',''));
            });
            $('#rightproduct').html(list30.length);
	
}
	/*** pimproduct end lime ***/
}

/*----
    fn_createlicense()
	Function to be used for get the license details and send to license ajax page for save and edit
----*/
function fn_createlicense(id)
{
	var list4 = [];	 //module id
	var list6 = [];	 //unit id
	var list8 = [];	 //ipl id
	var list10 = []; //assessmentid	
	var list12 = []; //Questid	
	var list14 = []; //expdetion / destination id
        var list16= []; //courses
        var list18= []; //units
        var list20= []; //phases
        var list22= []; //videos
        var list24= []; //pd lessons
        var list26= []; //Missions
        var list28= []; //documents
	var list30= []; //Product
        var list32= []; //Nondigitalcontent
	var extids = [];
	
	$("div[id^=list4_]").each(function()
	{
		list4.push($(this).attr('name').replace('list4_',''));
	});	
	
	$("div[id^=list6_]").each(function()
	{
		list6.push($(this).attr('id').replace('list6_',''));
	});	
	
	$("div[id^=list10_]").each(function()
	{
		list10.push($(this).attr('id').replace('list10_',''));
	});		
		
	$("div[id^=list8_]").each(function()
	{
		list8.push($(this).attr('name').replace('list8_',''));
	});
		
	$("div[id^=list12_]").each(function()
	{
		list12.push($(this).attr('name').replace('list12_',''));
	});
	
	$("div[id^=list14_]").each(function()
	{
		list14.push($(this).attr('name').replace('list14_',''));
	});
	
         $("div[id^=list16_]").each(function()
        {
                list16.push($(this).attr('id').replace('list16_',''));
         });
	
         $("div[id^=list24_]").each(function()
        {
                list24.push($(this).attr('name').replace('list24_',''));
         });
        //pd
        
        //SOS
         $("div[id^=list18_]").each(function()
        {
                list18.push($(this).attr('id').replace('list18_',''));
         });
        
         $("div[id^=list20_]").each(function()
         {
                list20.push($(this).attr('name').replace('list20_',''));
         });
         
         $("div[id^=list22_]").each(function()
         {
                list22.push($(this).attr('name').replace('list22_',''));
         });
         
         $("div[id^=list26_]").each(function()
        {
                list26.push($(this).attr('name').replace('list26_',''));
         });
         
          $("div[id^=list32_]").each(function()
        {
                list32.push($(this).attr('id').replace('list32_',''));
         });
         
         $("div[id^=list28_]").each(function()
        {
                list28.push($(this).attr('name').replace('list28_',''));
         });
		//sim product
		$("div[id^=list30_]").each(function()
		{
				list30.push($(this).attr('name').replace('list30_',''));
		});
         
	$("input[id^=exid_]").each(function()
	{
		extids.push($(this).val());
	});
	
	var dataparam = "oper=savelicense"+"&licennsename="+$('#licennsename').val()+"&duration="+$('#duration').val()+"&amount="+$('#amount').val()+"&sales="+$('#sales').val()+"&month="+$('#hidmonth').val()+"&licensetype="+$('#hidlicensetype').val()+"&id="+id+"&list4="+list4+"&list6="+list6+"&list8="+list8+"&list12="+list12+"&list14="+list14+"&tags="+$('#form_tags_license').val()+"&extids="+extids+"&list16="+list16+"&list24="+list24+"&list26="+list26+"&list18="+list18+"&list20="+list20+"&list22="+list22+"&contenttype="+$('#contenttype').val()+"&list28="+list28+"&list10="+list10+"&list30="+list30+"&list32="+list32;
	if($("#createlicense").validate().form())
	{
					
			if(id!=0 && id!=undefined){
				actionmsg = "Updating";
				alertmsg = " License has been updated successfully"; 
			}
			else {
				actionmsg = "Saving";
				alertmsg = "License has been created successfully"; 
			}	 
			
			$.ajax({
				type: 'post',
				url: "licenses/newlicense/licenses-newlicense-newlicenseajax.php",
				data: dataparam,
				beforeSend: function(){
					showloadingalert(actionmsg+", please wait.");	
				},
				success: function (data) {					
					if(trim(data)=="success")
					{
						$('.lb-content').html(alertmsg);
						setTimeout('closeloadingalert()',1000);
						setTimeout('removesections("#home");',500);
						setTimeout('showpages("licenses","licenses/licenses.php");',500);
					}
					else if(trim(data)=="fail")
					{
						$('.lb-content').html("Incorrect Data");
						setTimeout('closeloadingalert()',1000);
					}					
				},
			});
		
	}
}

/*----
    fn_load_lessons()
	Function to be used for load the lesson 
----*/
function fn_load_lessons(id)
{
	var list6= [];
	$("div[id^=list6_]").each(function()  //get unit ids
	{
		list6.push($(this).attr('id').replace('list6_',''));
	});	
	var dataparam = "oper=loadlessons"+"&unitids="+list6+"&id="+id;	
	 $.ajax({
		 	type: 'post',
			url: "licenses/newlicense/licenses-newlicense-newlicenseajax.php",
			data: dataparam,
			success: function (data) {	
			$('#Ipls').html(data)	
			},
		});	
}

/*----
    fn_load_phases()
	Function to be used for load the phases
----*/
function fn_load_phases(id)
{
	var list18= [];
	$("div[id^=list18_]").each(function()  //get sosunit ids
	{
		list18.push($(this).attr('id').replace('list18_',''));
	});	
	var dataparam = "oper=loadphases"+"&unitids="+list18+"&id="+id;	
	 $.ajax({
		 	type: 'post',
			url: "licenses/newlicense/licenses-newlicense-newlicenseajax.php",
			data: dataparam,
			success: function (data) {	
			$('#phases').html(data)	
			},
		});	
}

/*----
    fn_load_videos()
	Function to be used for load the phases
----*/
function fn_load_video(id)
{
        var list18= [];
	$("div[id^=list18_]").each(function()  //get sosunit ids
	{
		list18.push($(this).attr('id').replace('list18_',''));
	});	
        
	var list20= [];
	$("div[id^=list20_]").each(function()  //get sosunit ids
	{
		list20.push($(this).attr('id').replace('list20_',''));
	});	
	var dataparam = "oper=loadvideo"+"&unitids="+list18+"&phaseids="+list20+"&id="+id;	
	 $.ajax({
		 	type: 'post',
			url: "licenses/newlicense/licenses-newlicense-newlicenseajax.php",
			data: dataparam,
			success: function (data) {	
			$('#video').html(data)	
                        fn_load_document(id);
			},
		});	
}


/*----
    fn_load_document()
	Function to be used for load the document
----*/
function fn_load_document(id)
{
        var list18= [];
	$("div[id^=list18_]").each(function()  //get sosunit ids
	{
		list18.push($(this).attr('id').replace('list18_',''));
	});	
        
	var list20= [];
	$("div[id^=list20_]").each(function()  //get sosphase ids
	{
		list20.push($(this).attr('id').replace('list20_',''));
	});	
	var dataparam = "oper=loaddocument"+"&unitids="+list18+"&phaseids="+list20+"&id="+id;	
	 $.ajax({
		 	type: 'post',
			url: "licenses/newlicense/licenses-newlicense-newlicenseajax.php",
			data: dataparam,
			success: function (data) {	
			$('#document').html(data)	
			},
		});	
}

/*----
    fn_load_lessons()
	Function to be used for load the lesson 
----*/
function fn_load_pdlessons(id)
{
	var list16= [];
	$("div[id^=list16_]").each(function()  //get unit ids
	{
		list16.push($(this).attr('id').replace('list16_',''));
	});	
	var dataparam = "oper=loadpdlessons"+"&courseids="+list16+"&id="+id;	
	 $.ajax({
		 	type: 'post',
			url: "licenses/newlicense/licenses-newlicense-newlicenseajax.php",
			data: dataparam,
			success: function (data) {	
			$('#pdlessons').html(data)	
			},
		});	
}

/*----
    fn_load_product()
	Function to be used for load the product 
----*/
function fn_load_product(id,type)
{
	var list6= []; // get unit ids
	$("div[id^=list6_]").each(function()
	{
		list6.push($(this).attr('id').replace('list6_',''));
	});	
	
	var list8= []; // get ipl ids
	$("div[id^=list8_]").each(function() 
	{
		list8.push($(this).attr('id').replace('list8_',''));
	});	
	
	var list4= []; // get module id
	$("div[id^=list4_]").each(function()  
	{
		list4.push($(this).attr('id').replace('list4_',''));
	});
	
	var list12= []; // get Quest id
	$("div[id^=list12_]").each(function() 
	{
		list12.push($(this).attr('id').replace('list12_',''));
	});
	
	var list14= []; // get expedition id
	$("div[id^=list14_]").each(function() 
	{
		list14.push($(this).attr('id').replace('list14_',''));
	});
	
	var list16= []; // get course id
	$("div[id^=list16_]").each(function()
	{
		list16.push($(this).attr('id').replace('list16_',''));
	});
	
	var list24= []; // get PD id
	$("div[id^=list24_]").each(function()  
	{
		list24.push($(this).attr('id').replace('list24_',''));
	});
	
	var list26= []; // get Mission id
	$("div[id^=list26_]").each(function()  
	{
		list26.push($(this).attr('id').replace('list26_',''));
	});
        
        var list32= []; // get Non digital id
	$("div[id^=list32_]").each(function()  
	{
		list32.push($(this).attr('id').replace('list32_',''));
	});
        
	var dataparam = "oper=loadproduct"+"&unitids="+list6+"&iplids="+list8+"&maduleids="+list4+"&expids="+list14+"&questids="+list12+"&pdids="+list24+"&missionids="+list26+"&courseids="+list16+"&id="+id+"&nondigitalcontentids="+list32;	
	//alert(dataparam);
	 $.ajax({
		 	type: 'post',
			url: "licenses/newlicense/licenses-newlicense-newlicenseajax.php",
			data: dataparam,
			success: function (data) {	
			
			if(type=='content')
			{
				var list29= []; // get PD id
				$("div[id^=list29_]").each(function()  //get unit ids
				{
					list29.push($(this).attr('id').replace('list29_',''));
				});
			}
				
			$('#product').html(data)
			if(type=='edit')
			{
				var list29= []; // get product left id
				$("div[id^=list29_]").each(function()  //get product left ids
				{
					list29.push($(this).attr('id').replace('list29_',''));
				});
			}
				
			fn_movealllistitems('list29','list30',0,0); // Move right side
		
			var left ='';
			for(var i=0; i<list29.length; i++)
			{
				var leftside = list29[i];
				var res = leftside.split(",");
				if(left=='')
				{
					var left1=res[0];
				}
				else
				{
					var left1=left1+","+res[0];
				}
				if(id!=0)
				{
					fn_movealllistitems('list29','list30',1,left1); // Move left side
				}
			}
				
			},
		});	
}


/*----
    fn_load_assessment()
	Function to be used for load the assessment
----*/
function fn_load_assessment(id)
{
		
	var list8= []; // get ipl ids
	$("div[id^=list8_]").each(function() 
	{
		list8.push($(this).attr('id').replace('list8_',''));
	});	
	
	var list4= []; // get module id
	$("div[id^=list4_]").each(function()  
	{
		list4.push($(this).attr('id').replace('list4_',''));
	});
	
	var list12= []; // get Quest id
	$("div[id^=list12_]").each(function() 
	{
		list12.push($(this).attr('id').replace('list12_',''));
	});
	
	var list14= []; // get expedition id
	$("div[id^=list14_]").each(function() 
	{
		list14.push($(this).attr('id').replace('list14_',''));
	});
	
	
	var list24= []; // get PD id
	$("div[id^=list24_]").each(function()  
	{
		list24.push($(this).attr('id').replace('list24_',''));
	});
	
	var list26= []; // get Mission id
	$("div[id^=list26_]").each(function()  
	{
		list26.push($(this).attr('id').replace('list26_',''));
	});
        
        
	var dataparam = "oper=loadassessment"+"&iplids="+list8+"&maduleids="+list4+"&expids="+list14+"&questids="+list12+"&pdids="+list24+"&missionids="+list26+"&id="+id;	
	//alert(dataparam);
	 $.ajax({
		 	type: 'post',
			url: "licenses/newlicense/licenses-newlicense-newlicenseajax.php",
			data: dataparam,
			success: function (data) {	
			   
			    $('#assessment').html(data);
				
			},
		});	
}


/*----
    fn_deletelicense()
	Function to be used for delete the license 
----*/
function fn_deletelicense(id)
{	     
	$.Zebra_Dialog('Are you sure you want to delete?',
	{
		'type': 'confirmation',
		'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {		
				 actionmsg = "Deleting";
				 alertmsg = "License has been deleted successfully"; 
				 alertmsg1 = "License cannot be deleted. Districts or schools are using this license"; 
				 var dataparam = "oper=deletelicense&id="+id;	
				  $.ajax({
					type: 'post',
					url: "licenses/newlicense/licenses-newlicense-newlicenseajax.php",
					data: dataparam,
					beforeSend: function(){
						showloadingalert(actionmsg+", please wait.");	
					},
					success: function (data) {
						if(data=="success")
						{
							$('.lb-content').html(alertmsg);	
							setTimeout('closeloadingalert()',1000);
							setTimeout('removesections("#home");',500);
							setTimeout('showpages("licenses","licenses/licenses.php")',500);
						}
						else if(data=="exists")
						{
							closeloadingalert();
							$.Zebra_Dialog(alertmsg1, { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
						}
						else
						{
							$('.lb-content').html("Deleting this license has been failed");
							setTimeout('closeloadingalert()',2000);
						}						
					},
				});	
	 		}
		}]
	});		
}


/*----
    fn_updatelicense()
	Function to be used for update the license details for distict,school and individual
----*/
function fn_updatelicense(id,type,hidlicenseid){			
		var ddllicense = '';
		var numusers ='';
		var startdate ='';
		var enddate ='';
		var renewal ='';
		var graceipl='';
		var gracemod='';
		var error=0;	
		var counterror=0;
		var rcount = '';	
		$("div[id^='lic']").each(function() {
			 var flag=0;  
			 if($(this).attr('id')!='licenselist'){
				 var i = $(this).attr('id').substring(3);
				 
				 if($('#checkbox'+i).is(':checked')){
					flag=1;
				 }
				 if($('#iplcount'+i).is(':visible')){
					 if($('#iplcount'+i).val()==0 || $('#iplcount'+i).val()==''){
						 error=1;
					 }
				 }
				 if($('#modcount'+i).is(':visible')){
					 if($('#modcount'+i).val()==0 || $('#modcount'+i).val()==''){
						 error=1;
					 }
				 }
				 if($('#noofusers' + i).val()==0 || $('#noofusers' + i).val()==''){
					 error=1;
				 }
				 if($('#sdate' + i).val()==0 || $('#sdate' + i).val()==''){
					 error=1;
				 }
				 if($('#errorcount'+i).val()==1){
					 counterror=1;
				 }
				 renewal+=flag+'~';
				 ddllicense+=$('#ddllic' + i).val()+'~';
				 numusers+=$('#noofusers' + i).val()+'~';	
				 startdate+=$('#sdate' + i).val()+'~';	
				 enddate+=$('#edate' + i).val()+'~';	
				 graceipl+=$('#iplcount' + i).val()+'~';	
				 gracemod+=$('#modcount' + i).val()+'~';	
				 rcount+=$('#renewalcount_' + i).val()+'~';	
			 }
		});	
		if(error==0 && counterror==0){
			var dataparam = "oper=updatelicense&ddllicense="+ddllicense+"&numusers="+numusers+"&startdate="+startdate+"&enddate="+enddate+"&graceipl="+graceipl+"&gracemod="+gracemod+"&renewal="+renewal+"&id="+id+"&type="+type+"&distid="+$('#hiddistid').val()+"&rcount="+rcount;
			$.ajax({
				type: 'post',
				url: "licenses/newlicense/licenses-newlicense-newlicenseajax.php",
				data: dataparam,
				beforeSend: function(){
					showloadingalert("Updating please wait.");	
				},
				success:function(data) {
					$('.lb-content').html("License has been updated successfully");					
					setTimeout('closeloadingalert()',1000);
					removesections("#licenses-newlicense-actions");
					showpageswithpostmethod("licenses-newlicense-viewlicenseholders","licenses/newlicense/licenses-newlicense-viewlicenseholders.php","id="+hidlicenseid);	
				}
				
			});
		}
		else{
			if(error==1){
				$.Zebra_Dialog("Please fill all the information about licenses.", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
				return false;
			}
			else if(counterror==1){
				$.Zebra_Dialog("Seats exceeds available student seats.", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
				return false;
			}
		}
		
}

function fn_loadextendcontent(licenseid,flag)
{
	var list4 = [];		//module/mathmodule id/quest id
        
	$("div[id^=list4_]").each(function()
	{
		list4.push($(this).attr('name').replace('list4_',''));
	});	
	$("div[id^=list12_]").each(function()
	{
		list4.push($(this).attr('name').replace('list12_',''));
	});
        $("div[id^=list14_]").each(function()
	{
		list4.push($(this).attr('name').replace('list14_',''));
	});
        
         $("div[id^=list26_]").each(function()
	{
		list4.push($(this).attr('name').replace('list26_',''));
	});
	if(list4=='' && flag!=1){
		$.Zebra_Dialog("Please select any Module/Mathmodule/Quest/Exppedition.", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
		return false;
	}
	var dataparam = "oper=loadextendcontent&licenseid="+licenseid+"&list4="+list4;	
	$.ajax({
		type: 'post',
		url: "licenses/newlicense/licenses-newlicense-newlicenseajax.php",
		data: dataparam,
		beforeSend: function(){			
		},
		success:function(data) {
			$('#extendcontent').html(data);
		}
		
	});	
}
/*----
    fn_load_modulesave()
	Function to be used for load the modules to save 
----*/
function fn_load_modulesave(licenseid,id,movemods)
{

	var list4= [];
	if(movemods == 1) {

		$("div[id^=list4_]").each(function()  //get unit ids
		{
			list4.push($(this).attr('name').replace('list4_',''));
		});
var chkid = id;


	}
	else if(movemods == 0)
	{
	        $("div[id^=list3_]").each(function()  //get unit ids
		{
			list4.push($(this).attr('name').replace('list3_',''));
		});
               var chkid = id;
	}
	else if(movemods == 5) {
	    list4 = id;
	    var chkid = 1;

	}
	else if(movemods == 3) {
	    list4 = id;
	    var chkid = 1;

	}
	
	var dataparam = "oper=loadmodulesave"+"&list4="+list4+"&licenseid="+licenseid+"&chkid="+chkid+"&movemods="+movemods;	
	 $.ajax({
		 	type: 'post',
			url: "licenses/newlicense/licenses-newlicense-newlicenseajax.php",
			data: dataparam,
			beforeSend: function(){
				   showloadingalert("Updating please wait.");	
				},
			success: function (data) {
				   setTimeout('closeloadingalert()',1000);	
			},
		});	
}