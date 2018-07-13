/*

 Function to escape string

 */

function escapestr(str){

    return escape(str.replace(/<script[^>]*>([\s\S]*?)<\/script[^>]*>/,""));

}



/*Function to search list box

 */

function search_list(listboxid,list){



    var filter = $(listboxid).val();



    if(filter) {

        $(list).find("div[class^='dragItemLable']:not(:contains(" + filter + "))").parent('div').slideUp();

        $(list).find("div[class^='dragItemLable']:contains(" + filter + ")").parent('div').slideDown();

    } else {

        $(list).find("div").slideDown();

    }

}



function getPageName(url) {

    var currurl = url;

    var index = currurl.lastIndexOf("/") + 1;

    var filenameWithExtension = currurl.substr(index);

    var filename = filenameWithExtension.split(".")[0]; // <-- added this line

    return filename;                                    // <-- added this line

}



/*

 Function to show the dual login window

 */

function showduallogin(){



    var dataparam = "oper=dualloginform&";



    $.ajax({

        url: 'duallogin-ajax.php',

        data: dataparam,

        cache:false,

        type:"POST",

        beforeSend: function(){

            showloadingalert('Loading, please wait.');

        },

        success: function (data) {

            $('.lb-content').html(data);

            $('.lb-content').css({'padding':'10px'});

        }

    });

}



/*

 Function to log in the second student

 */

function fn_duallogin()

{

    if($("#frmduallogin").validate().form())

    {

        var dataparam = "oper=duallogin&username="+$('#dualusername').val()+"&password="+$('#dualpassword').val();

        $.ajax({

            type: "POST",

            url: 'duallogin-ajax.php',

            data: dataparam,

            beforeSend:function()

            {

                //showalert("Loading... , please wait.");

            },

            success: function(data)

            {

                if(data=='success')

                {

                    //alert("Student logged in successfully!");

                    window.location="index.php";

                }

                else

                {

                    alert("Invalid username/password");

                }

            }

        });

    }

}



var lightbox_overlay = '<section style="display:none" class="black-overlay"><div class="alert-cotent"><img height="60" width="60" src="img/loadinggif.gif" ></img></div></section>';



// Container for the lightbox

/*

 Function to load the lightbox

 alertmessage - message to be shown in the lightbox

 */

function ajaxloadingalert(alertmessage){

    var cssObjOuter = {

        'height' :250,

        'width' :$('body').width()-20,

        'overflow':'hidden'

    };

    var cssObjInner = {

        'margin-left' : $(window).width() * .45,

        'margin-top' : ($(window).height()) * .1

    };



    $('body').append(lightbox_overlay);

    $(".black-overlay").css(cssObjOuter);



    $(".alert-cotent").css(cssObjInner);

    $(".black-overlay").fadeIn();



    var scrtop = ($('.black-overlay').offset().top) - $(window).scrollTop();

    $('html,body').stop().animate({

        scrollTop: '+=' + (scrtop-450)+ 'px'

    }, 500);

}



/*

 Function to close the lightbox

 */

function ajaxclosingalert(){

    $(".black-overlay").delay(200).slideUp(400);

    setTimeout('$(".alert-cotent").remove();$(".black-overlay").remove();',300);

}



/*

 Function to show load alert the ajax pages.

 */



function showloadingalert(msg)

{

    var loading_overlay = '<section style="display:none" class="white-overlay"><div class="lb-content"></div></section>';

    $("body").css('overflow','hidden');

    var cssObjOuter = {

        'width' : $(window).width(),

        'height' : $(window).height(),

        'overflow':'hidden',

        'top':0,

        'left':0

    };

    var cssObjInner = {

        'margin-left' : $(window).width() * .40,

        'margin-top' : ($(window).height()) * .4

    };



    $('body').append(loading_overlay);

    $(".white-overlay").css(cssObjOuter);



    $(".lb-content").css(cssObjInner);

    $(".white-overlay").fadeIn(200);

    $(".lb-content").html(msg);

}



function closeloadingalert()

{

    $(".white-overlay").fadeOut(200);

    $("body").css('overflow','');

    setTimeout('$(".lb-content").remove();$(".white-overlay").remove();',200);

}



/*

 Function to load the page when button clicked.

 */

function showpages(pagename,pagelink){



    var currentSection = "#"+pagename;

    var pagelink = pagelink.split("?");



    $.ajax({

        url: pagelink[0],

        cache:false,

        async:false,

        type: "POST",

        data: pagelink[1],

        beforeSend: function(){

            if(pagelink[0].indexOf("assignment-sigmath-test") >= 0) {

                console.log("inside");

                ajaxloadingalert('Loading, please wait.');

            }

        },

        success: function (data) {

            if(pagelink[0].indexOf("assignment-sigmath-test") >= 0) {

                ajaxclosingalert();

            }

            $('body').append(data);



            $(currentSection).addClass("blueWindow1").hide();



            var parentZ = [];

            $('section[class!="black-overlay"]').each(function(index, element) {

                parentZ[index] = $(this).css('z-index');

            });



            $('section[class!="black-overlay"]').each(function(index, element) {

				/*if(index != 0){

				 $(this).css('z-index', (parseInt(parentZ[index-1]) - 1));

				 }

				 */

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

                easing: "easeOutSine",

                duration: 450

            },function(){

                var scrtop = ($(currentSection).offset().top - 55) - $(window).scrollTop();



                $('html,body').animate({

                    scrollTop: '+=' + (scrtop) + 'px'

                }, 'slow');

            });



            var thisZ = parentZ - 1;

            $(currentSection).css('z-index', thisZ);



			/*if(pagename=="class-newclass-viewschedule_create")

			 {

			 setTimeout(function(){



			 if($('#missingrot').val()!="")

			 {

			 fn_generatenew($('#missingrot').val());

			 //alert($('#missingrot').val());

			 }



			 },1000);

			 }*/

        },

        dataType: 'html'

    });

}





function showpageswithpostmethod(pagename,pagelink,datas){

    var currentSection = "#"+pagename;



    currentRequest =$.ajax({

        url: pagelink,

        cache:false,

        async:false,

        type: "POST",

        data: datas,

        beforeSend: function(){

            ajaxloadingalert('Loading, please wait.');

            if(currentRequest != null)

            {

                currentRequest.abort()

            }

        },

        success: function (data) {

            //$(".uploadify").uploadify("destroy");



            $('body').append(data);

            ajaxclosingalert();

            $(currentSection).addClass("blueWindow1").hide();



            var parentZ = [];

            $('section[class!="black-overlay"]').each(function(index, element) {

                parentZ[index] = $(this).css('z-index');

            });



            $('section[class!="black-overlay"]').each(function(index, element) {

				/*if(index != 0){

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

                easing: "easeOutSine",

                duration: 200

            },function(){

                var scrtop = ($(currentSection).offset().top - 55) - $(window).scrollTop();



                $('html,body').animate({

                    scrollTop: '+=' + (scrtop) + 'px'

                }, 'slow');

            });



            var thisZ = parentZ - 1;

            $(currentSection).css('z-index', thisZ);

        },

        dataType: 'html'

    });

}



/*

 Event fired when the window is resized.

 1. dynamic width/height for lightbox

 2. for fullscreen lesson play

 */

$(window).resize(function() {

    if($('.black-overlay').length){

        var cssObjOuter = {

            'width' : $(window).width(),

            'height' : $(window).height()

        };

        var cssObjInner = {

            'margin-left' : $(window).width() * .40,

            'margin-top' : $(window).height() * .2

        };

        $(".black-overlay").css(cssObjOuter);

        $(".lb-content").css(cssObjInner);

    }



    if($('#divcustomlightbox').length){



        var cssObjOuter = {

            'display' : 'block',

            'width' : $('body').width(),

            'height' : $(window).height()

        };



        var cssObjInner = {

            'display' : 'block',

            'width' : $('body').width(),

            'height' : $(window).height() - 90

        };



        $('#divcustomlightbox').css(cssObjOuter);

        $('#divlbcontent').css(cssObjInner);

        if($('#divlbcontent').width()==null)

        {

            $('iframe').css({ 'width':$('#divcustomlightbox').width(), 'height' : $('#divcustomlightbox').height() });

        }

        else

        {

            $('iframe').css({ 'width':$('#divlbcontent').width(), 'height' : $('#divlbcontent').height() });

        }

    }

});



/*

 function to close the fullscreen lesson play

 */

function closefullscreenlesson(){

    $('body').css('overflow','auto');

    $('#divcustomlightbox').remove();

    $('#divlbcontent').remove();

    $('#expedition-fullscreecn-header').remove();

    $("html, body").animate({ scrollTop: $(document).height() }, "slow");

}

window.addEventListener("message", receiveMessage, false);
function receiveMessage(event)
{
    closefullscreenlesson();

    // ...
}



/*

 function for fullscreen lesson play

 */

function showfullscreenlesson(fldrname,fldrnameinner,lessonid){

    $('html, body').animate({scrollTop: '0px'}, 0);

    $('body').css('overflow','hidden');



    var cssObjOuter = {

        'display' : 'block',

        'width' : $('body').width(),

        'height' : $(window).height()

    };



    var cssObjInner = {

        'display' : 'block',

        'width' : $('body').width() - 20,

        'height' : $(window).height() - 120

    };



    $('body').append('<div id="divcustomlightbox" title="Synergy ITC"><div class="btnprevclose"><p class="dialogTitleFullScr">Preview</p><a href="javascript:void(0);" onclick="closefullscreenlesson()" class="icon-synergy-close-dark"></a></div><div id="divlbcontent"><iframe src="'+ CLOUDFRONT_URL+ '/scormlib/rte.php?SCOInstanceID='+fldrnameinner+'&lessonid='+fldrnameinner+'&zipname='+fldrname+'&hostname='+location.host+'" width="100%"></iframe></div><div class="diviplbottom"></div></div>');



    $('#divcustomlightbox').css(cssObjOuter);

    $('#divlbcontent').css(cssObjInner);

    $('iframe').css({ 'width':$('#divlbcontent').width(), 'height' : $('#divlbcontent').height() });

}



/*

 function for fullscreen lesson play

 */

function showfullscreenlessonpd(fldrname,fldpdlessonid,pdfname){

    //alert(fldrname+" "+pdfname);

    $('html, body').animate({scrollTop: '0px'}, 0);

    $('body').css('overflow','hidden');



    var cssObjOuter = {

        'display' : 'block',

        'width' : $('body').width(),

        'height' : $(window).height()

    };



    var cssObjInner = {

        'display' : 'block',

        'width' : $('body').width(),

        'height' : $(window).height() - 90

    };



    $('body').append('<div id="divcustomlightbox" title="Synergy ITC"><div class="btnprevclose"><a href="javascript:void(0);" onclick="closefullscreenlesson()" class="icon-synergy-close-dark" style="color:#24485f"></a></div><iframe src="'+ CLOUDFRONT_URL +'/webpdlesson/'+fldrname+'/'+pdfname+'/'+pdfname+'.html" width="100%" height="100%"></iframe></div>');



    $('#divcustomlightbox').css(cssObjOuter);

    $('#divlbcontent').css(cssObjInner);

    $('iframe').css({ 'width':$('#divlbcontent').width(), 'height' : $('#divlbcontent').height() });

    $('.btnprevclose').css({'height':'0'});

}

/* student level start created by chandru */

function showstudentlessonpd(fldrname,fldpdlessonid,pdfname,schid){

    window.open(ITC_URL + '/library/pdlessons/library-pdlessons-studentviewscreen.php?fldrname='+fldrname+'&fldpdlessonid='+fldpdlessonid+'&pdfname='+pdfname+'&schid='+schid);

}

/* student level end */



/*

 function to get the height of the window for module

 */

function getscreenheight(){

    return $(window).height();

}



/*

 function for fullscreen lesson play

 */

function showfullscreenmodule(fldrname,type){
    document.domain = 'pitsco.com';
    $('html, body').animate({scrollTop: '0px'}, 0);

    $('body').css('overflow','hidden');



    var cssObjOuter = {

        'display' : 'block',

        'width' : $('body').width(),

        'height' : $(window).height()

    };



    var inner_fldr = fldrname.split(",");

    var ifrpath = '';



    if(type==1)

    {

        ifrpath = CLOUDFRONT_URL+'/moduleplay/assignmentplayer.php';

    }

    else if(type==3)

    {

        ifrpath = CLOUDFRONT_URL+'/moduleplay/questsplayer.php';

    }

    else

    {

        ifrpath = CLOUDFRONT_URL+'/moduleplay/moduleplayer.php';

    }



    $('body').append('<div id="divcustomlightbox" title="Synergy ITC"><iframe style="background-image: url(../img/loadinggif.gif);  background-repeat:no-repeat;background-position:center;" src="'+ifrpath+'?id='+escape(fldrname+','+$(window).height())+'&hostname='+location.host+'" width="100%" height="100%"></iframe></div>');

	/*$('#loadImg').css({ 'width' : $(window).width(),

	 'height' : $(window).height()+100,

	 'position' : 'absolute',

	 'background' : 'url(img/loadinggif.gif) no-repeat center #24485F',

	 'top' : '-23%'

	 });*/

    $('#divcustomlightbox').css(cssObjOuter);

    $('iframe').css({ 'width':$('#divcustomlightbox').width(), 'height' : $('#divcustomlightbox').height() });





}

/*

 function for resizing the iframe according to the

 size of the question.

 */



function autoResize(id){

    var newheight;

    var newwidth;



    if(document.getElementById){

        newheight=document.getElementById(id).contentWindow.document .body.scrollHeight;

        newwidth=document.getElementById(id).contentWindow.document .body.scrollWidth;

    }



    document.getElementById(id).height= (newheight) + "px";

    document.getElementById(id).width= (newwidth) + "px";

}





/*

 function for removing section

 cursecid - id of the current section

 */



function removesections(id){

    $(id).nextAll('section[class!="black-overlay"]').hide("fade").remove();

}





$(document).delegate('.tag_well', 'click', function () {

    $('#'+$(this).children().attr('id')).focus();

});



function fn_click(id)

{

    if(id==0)

    {

        setTimeout('removesections("#home");',200);

        setTimeout('showpages("classlock","classlock.php")',400);

    }

    else if(id==1)/******this is for student lock************/

    {

        setTimeout('removesections("#home");',200);

        setTimeout('showpages("studentlock","studentlock.php")',400);

    }

    else if(id==2)/*****this is for message*********/

    {

        setTimeout('removesections("#home");',200);

        setTimeout('showpages("tools-message-message","tools/message/tools-message-message.php")',400);

        setTimeout('showpages("tools-message","tools/message/tools-message.php?id=0")',600);

    }

    else/*******This is for calendar***********/

    {

        setTimeout('removesections("#home");',200);

        setTimeout('showpages("tools-calendar-calendar","tools/calendar/tools-calendar-calendar.php")',400);

    }



}

/*Function for mouse over*/



function fn_mouseover(id)

{

    if(id==0)

    {

        $('#loaddiv').hover( function() {

            $('#classlok').show();

            $('#lok').hide();

            $('#cal').hide();

            $('#msg').hide();

        }).mouseleave(function() {

            $('#classlok').hide();

        });

    }

    else if(id==1)

    {

        $('#loaddiv').hover( function() {

            $('#lok').show();

            $('#classlok').hide();

            $('#cal').hide();

            $('#msg').hide();

        }).mouseleave(function() {

            $('#lok').hide();

        });

    }

    else if(id==2)

    {

        $('#loaddiv').hover( function() {

            $('#msg').show();

            $('#lok').hide();

            $('#cal').hide();

        }).mouseleave(function() {

            $('#msg').hide();

        });

    }

    else

    {

        $('#loaddiv').hover( function() {

            $('#cal').show();

            $('#msg').hide();

            $('#lok').hide();

        }).mouseleave(function() {

            $('#cal').hide();

        });

    }

}





var innercloading = '<img src="img/AjaxLoader.gif" width="60" style="margin:5% 0 0 44%;" />';





/* numbers only */

function isNumber(evt) {

    evt = (evt) ? evt : window.event;

    var charCode = (evt.which) ? evt.which : evt.keyCode;

    if (charCode > 31 && (charCode < 48 || charCode > 57)) {

        return false;

    }

    return true;

}



function trim(stringToTrim)

{

    return stringToTrim.replace(/^\s+|\s+$/g,"");

}



function ampreplace(str)

{

    return str.replace(/&/g,"%26");

}





function arraymove(arr, fromIndex, toIndex)

{

    element = arr[fromIndex];

    arr.splice(fromIndex, 1);

    arr.splice(toIndex, 0, element);

}





function arraycompare(arr1,arr2)

{

    arrresult=new Array();

    var arrresult=$(arr1).not(arr2).get();

    return arrresult;

}



/*function fn_Emptyexpedition() {

 alert("No Records Found...");



 //setTimeout("closeloadingalert();",500);

 setTimeout('removesections("#tools");',500);

 // setTimeout('removesections("#tools-passport-passport");',500);

 setTimeout('showpages("tools-passport-passport","tools/passport/tools-passport-passport.php"");',500);

 }*/

/*******Dash Board Class Progress********/

function fn_classpro(uid,schoolid,indid)

{

    $(".loading-div").show(); //show loading element



    $("#results" ).load( "ajaxpages/dashboardchart-ajax.php"); //load initial records



    //executes code below when user click on pagination links

    $("#results").on( "click", ".pagination a", function (e){

        e.preventDefault();

        $(".loading-div").show(); //show loading element

        var page = $(this).attr("data-page"); //get page number from link



        $("#results").load("ajaxpages/dashboardchart-ajax.php",{"page":page}, function(){ //get content from PHP page

            $(".loading-div").hide(); //once done, hide loading element

        });

    });



}



/*******Dash Board Class Progress********/ //fn_showclassdetail



/*******Missing assessment for student to display Teacher level starts ********/

function myFunction() {

    var dataparam = "oper=teacheralert";

    $.ajax({

        type: 'post',

        url: 'ajaxpages/testalert.php',

        data: dataparam,

        beforeSend: function(){



        },

        success:function(ajaxdata) {

            document.getElementById("myDropdown").classList.toggle("show");

            $('#myDropdown').html(ajaxdata);

            $("#upasses").css("display","inline");

            $("#downasses").css("display","none");

            if($('#myDropdown').hasClass("show"))

            {

                $("#downasses").css("display","inline");

                $("#upasses").css("display","none");

            }

        }

    });

}

function fn_testalert(){

    var dataparam = "oper=teacheralert";

    $.ajax({

        type: 'post',

        url: 'ajaxpages/testalert.php',

        data: dataparam,

        beforeSend: function(){



        },

        success:function(ajaxdata) {

            if(ajaxdata!=""){

                $('#teachermsg').show();

            }



        }

    });

}

/*******Missing assessment for student to display Teacher level ends ********/





function fn_close_licence_expiration_banner() {

    //send an ajax call to

    $.ajax({

        type: 'post',

        url: 'ajaxpages/disable_licence_expiration.php',

        success: function (ajaxdata) {

        }

    });

    //hide the dialog

    $('#licence_expiration_banner').hide();
}
