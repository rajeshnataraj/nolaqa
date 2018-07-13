var ajaxCurrentRequest=null;
var ajaxcnt=0;

//If ITC_URL is not defined, which should never be the case in new code, set it to the empty string to make it compatible with existing code
//If ITC_URL is defined, there is no problem
if (ITC_URL == undefined){
    ITC_URL = "";
}
window.log = function () {
	log.history = log.history || [];
	log.history.push(arguments);
	if(this.console) {
		arguments.callee = arguments.callee.caller;
		var a = [].slice.call(arguments);
		(typeof console.log === "object" ? log.apply.call(console.log, console, a) : console.log.apply(console, a))
	}
};
(function (b) {
	function c() {}
	for(var d = "assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,timeStamp,profile,profileEnd,time,timeEnd,trace,warn".split(","), a; a = d.pop();) {
		b[a] = b[a] || c
	}
})((function () {
	try {
		console.log();
		return window.console;
	} catch(err) {
		return window.console = {};
	}
})());

var currentRequest = null;
// place any jQuery/helper plugins in here, instead of separate, slower script files.
$(document).ready(function () {
	
	jQuery.fn.ForceNumericOnly = function(){
		return this.each(function()
		{
			$(this).keydown(function(e)
			{
				var key = e.charCode || e.keyCode || 0;
				// allow backspace, tab, delete, arrows, numbers and keypad numbers ONLY
				return (
					key == 8 || 
					key == 9 ||
					key == 46 || key == 190 || key == 110 ||
					(key >= 37 && key <= 40) ||
					(key >= 48 && key <= 57) ||
					(key >= 96 && key <= 105)
				);
			});
		});
	};
	
	$.expr[":"].contains = $.expr.createPseudo(function(arg) {
		return function( elem ) {
			return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
		};
	});
				
	//to show the loading cursor for every ajax call
	$('body').ajaxStart(function() {
		
		$(this).css({'cursor':'wait'})
	}).ajaxStop(function() {
		$(this).css({'cursor':'default'})
	}).ajaxError(function(event, jqxhr, settings, exception) {
		if(exception != 'abort') {		
			//window.location="loginv2.php";
		}
		if(jqxhr.status==302)
	 	{
		 	window.location="index.php";
	 	}
	}).ajaxComplete(function() {
            $(".tipsy").remove();
		/***for the purpose of the dim class needs to works on the IE***/
	   if( navigator.appName=='Microsoft Internet Explorer')
	   {
		  $('.dim').attr('disabled', true);
		  $(".dim").prop('onclick', null);
		  $(".dim").prop('click', null);
			 
	   }
	});

	/*
	$.ajaxSetup({ cache: false,
	  error: function(jqXHR, textStatus, errorThrown) {
    	if(jqXHR.status==0 && ajaxcnt==0)
       	{
			ajaxCurrentRequest=this;
		  	$.fn.checknet();
		 	ajaxcnt++;
	   	}
	} });*/
	
	// To launch the main menu for the first time
	function initialLoadOut() {
		$.ajax({
			url: ITC_URL + "/main-menu.php",
			beforeSend: function(){
				ajaxloadingalert('Loading, please wait.');
			},
			success: function (data) {
				$('body').append(data);
				$("#home").effect("slide", {
					direction: "up",
					easing: "easeOutSine",
					duration: 400
				});
				$("#home").css('z-index', '899');
				ajaxclosingalert();
				//launchDashboard();
			},
			dataType: 'html'
		});
	}
	
	// To launch the dashboard page first time and whenever the top bar is clicked.
	initialLoadOut();

	// Setting the click event for top bar. When clicked dashboard will be loaded.
	$('.dash').click(function () {
		//launchDashboard();
	});
	
	$('.navUserIcon').focus(function() {
        this.blur();
    });
	
	// Common button event to load the pages when the menu button is clicked.
	$(document).delegate('.mainBtn', 'click', function () {
		
		
		$('.remarkContainer').remove();
		
		var upNext = $(this).attr('id');
		var id = $(this).attr('name');

		var thisSectionName = "#" + $(this).closest('section[class!="black-overlay"]').attr("id");
		var folder = upNext.split("-");
		
		if(folder.length == 1){
			upNextfolder = folder[0].substring(3)+"/"+upNext.substring(3);		
		}
		else if(folder.length == 2){
			upNextfolder = folder[0].substring(3)+"/"+folder[1]+"/"+upNext.substring(3);
		}
		else if(folder.length == 3){
			upNextfolder = folder[0].substring(3)+"/"+folder[1]+"/"+upNext.substring(3);
		}
		else {
			upNextfolder = upNext.substring(3);
		}
		
		var currentSection = "#" + upNext.substring(3);
		
		function bringIt() {
			
		currentRequest=	$.ajax({
				url: upNextfolder+ ".php",
				data:"id="+id,
				type:'POST',
				beforeSend: function(){
					ajaxloadingalert('Loading, please wait.');
					if(currentRequest != null)
					{
						currentRequest.abort()
					}
				},
				success: function (data) {
					$(currentSection).remove();
					$('.ui-effects-wrapper').remove();
					$('body').append(data);
                                        pagetop();
					ajaxclosingalert();
					
					$(currentSection).addClass("blueWindow1").hide();
					
					var parentZ = []; 					
					$('section[class!="black-overlay"]').each(function(index, element) {
						parentZ[index] = $(this).css('z-index');	
					});
					
					$('section[class!="black-overlay"]').each(function(index, element) {
                       /* if(index != 0){
							$(this).css('z-index', (parseInt(parentZ[index-1]) - 1));	
						}*/
						
						if(index > 0){
							$(this).removeClass('blueWindow1').removeClass('blueWindow2');
							$(this).find('p:lt(2)').removeAttr('class');
							if(index%2==0){
								$(this).addClass('blueWindow1');
								$(this).find('p:lt(2)').each(function(index, element) {
                                    if( index <= 1) { 
										if(index == 0) {
											$(this).addClass('lightTitle');	
										}
										else {
											$(this).addClass('lightSubTitle');		
										}
									}
                                });
							}
							else {
								$(this).addClass('blueWindow2');
								$(this).find('p:lt(2)').each(function(index, element) {
									if( index <= 1) { 
										if(index == 0) {
											$(this).addClass('darkTitle');	
										}
										else {
											$(this).addClass('darkSubTitle');		
										}
									}
                                });
							}
						}
                    });
					
					
					
					$(currentSection).effect("slide", {
						direction: "up",
						easing: "easeInOutSine",
						duration: 250
					},function(){
						var scrtop = ($(currentSection).offset().top - 55) - $(window).scrollTop();
					 	$('html,body').animate({
							scrollTop: '+=' + (scrtop) + 'px'
						}, 'slow' );
					});
					
					$('#'+upNext).removeClass("dim");
					 
				},
				dataType: 'html'
			});
		}
		
		$(thisSectionName).nextAll('section[class!="black-overlay"]').hide("fade").remove();  
		bringIt();  
	});
	
});

/*--- fn_cancel ---*/
/*--- For All cancel functions ---*/     
function fn_cancel(id)
{
	//$(".uploadify").uploadify("destroy");
	$('#'+id).nextAll('section').hide("fade").remove();
}


function pagetop()
{ 
    var dataparam = "oper=pagetop";
    
    $.ajax({
                type: 'POST',
		url:'scroll.php',
		data: dataparam,
		
		success:function(ajaxdata){		
                        $('body').append(ajaxdata);
		}
        });
	
}
