/**
 * Created by barney on 2017-03-16.
 */
(function(jQuery)
{
    jQuery.fn.clock = function(options)
    {
        var _this = this;
        setInterval( function() {
            var d = new Date();
            var seconds = d.getSeconds();
            seconds  = seconds<10 ? '0'+seconds : seconds;
            //jQuery(_this).find(".sec").html(seconds+'&nbsp;');

            var month = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"][d.getMonth()];
            var day = day<10 ? '0'+ d.getDate() : d.getDate();
            var weekday = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"][d.getDay()];
            var today = weekday + ' ' + month + ' ' + day  ;
            jQuery(_this).find(".day").html(today);
        }, 1000 );

//    setInterval( function() {
//      var d = new Date();
//	  var hours = d.getHours();
//      var mins = d.getMinutes();
//      jQuery(_this).find(".hour").html(hours+':');
//      var meridiem = hours<12 ? 'a.m.':'p.m.';
//      jQuery(_this).find('.meridiem').html(meridiem);
//    }, 1000 );
//
//    setInterval( function() {
//      var d = new Date();
//	  var mins = d.getMinutes();
//	  mins  = mins<10 ? '0'+mins : mins;
//      jQuery(_this).find(".min").html(mins+':');
//    }, 1000 );
    }
})
(jQuery);

/*! Idle Timer - v0.9.2 - 2013-01-06
 * https://github.com/mikesherov/jquery-idletimer
 * Copyright (c) 2013 Paul Irish; Licensed MIT */
(function(e){e.idleTimer=function(t,i,d){d=e.extend({startImmediately:!0,idle:!1,enabled:!0,timeout:3e4,events:"mousemove keydown DOMMouseScroll mousewheel mousedown touchstart touchmove"},d),i=i||document;var l=e(i),a=l.data("idleTimerObj")||{},o=function(t){"number"==typeof t&&(t=void 0);var l=e.data(t||i,"idleTimerObj");l.idle=!l.idle;var a=+new Date-l.olddate;if(l.olddate=+new Date,l.idle&&d.timeout>a)return l.idle=!1,clearTimeout(e.idleTimer.tId),d.enabled&&(e.idleTimer.tId=setTimeout(o,d.timeout)),void 0;var m=e.Event(e.data(i,"idleTimer",l.idle?"idle":"active")+".idleTimer");e(i).trigger(m)},m=function(e){var t=e.data("idleTimerObj")||{};t.enabled=!1,clearTimeout(t.tId),e.off(".idleTimer")};if(a.olddate=a.olddate||+new Date,"number"==typeof t)d.timeout=t;else{if("destroy"===t)return m(l),this;if("getElapsedTime"===t)return+new Date-a.olddate}l.on(e.trim((d.events+" ").split(" ").join(".idleTimer ")),function(){var t=e.data(this,"idleTimerObj");clearTimeout(t.tId),t.enabled&&(t.idle&&o(this),t.tId=setTimeout(o,t.timeout))}),a.idle=d.idle,a.enabled=d.enabled,a.timeout=d.timeout,d.startImmediately&&(a.tId=setTimeout(o,a.timeout)),l.data("idleTimer","active"),l.data("idleTimerObj",a)},e.fn.idleTimer=function(t,i){return i||(i={}),this[0]&&e.idleTimer(t,this[0],i),this}})(jQuery);


/* Gumby JS */

(function ($) {

    var Gumby = function () {

        // Gumby data object for storing simpleUI classes and handlers
        var gumbyData = {},

            setGumbyData = function (key, value) {
                return gumbyData[key] = value;
            },

            getGumbyData = function (key) {
                return gumbyData[key] || false;
            },

            /**
             * Simple UI Elements
             * ------------------
             * UI elements that bind to an event, toggle
             * a class with possibility to run simple logic
             * on completion and test for specific conditions
             */
            simpleUI = {

                // simple UI elements holder object
                ui: [

                    // checkbox - check/uncheck (including hidden input) on click
                    {
                        selector: '.checkbox',
                        onEvent: 'click',
                        className: 'checked',
                        target: false,
                        condition: false,
                        // check/uncheck hidden checkbox input
                        complete: function ($e) {
                            var checked = $e.hasClass('checked');
                            $e.children('input').attr('checked', checked);
                        }
                    },

                    // radio - check/uncheck (including hidden input) on click
                    // also uncheck all others with same name
                    {
                        selector: '.radio',
                        onEvent: 'click',
                        className: 'checked',
                        target: false,
                        condition: false,
                        // check hidden radio input and uncheck others
                        complete: function ($e) {
                            var $input = $e.children('input'),
                                // radio buttons with matching names in the same group
                                $otherInputs = $('input[name="' + $input.attr('name') + '"]');

                            // ensure other radio buttons are not checked
                            $otherInputs.attr('checked', false).parent().removeClass('checked');

                            // check this one
                            $input.attr('checked', true).parent().addClass('checked');
                        }
                    },

                    // validation - add/remove error class dependent on value being present
                    // conditional method used to check for value
                    {
                        selector: 'form[data-form="validate"] .field',
                        onEvent: 'blur',
                        className: 'error',
                        target: false,
                        // check input is required and if so add/remove error class based on present value
                        condition: function ($e) {
                            var $child = $e.find('input, textarea').first(),
                                val = $child.val();

                            if(!$child.attr('required')) {
                                return false;
                            }

                            // email/regular validation
                            if (($child.attr('type') === 'email' && !val.match(/^[\w.%&=+$#!-']+@[\w.-]+\.[a-zA-Z]{2,4}$/)) || !val.length) {
                                $e.addClass('error');
                                return false;
                            }

                            $e.removeClass('error');
                        },
                        complete: false
                    },

                    // toggles - toggle active class on itself and selector in data-for
                    // on click
                    {
                        selector: '.toggle:not([data-on]), .toggle[data-on="click"]',
                        onEvent: 'click',
                        className: 'active',
                        target: function($e) {
                            return $e.add($($e.attr('data-for')));
                        },
                        condition: false,
                        complete: false
                    },

                    // on mouseover (will always add class) and mouseout (will always remove class)
                    {
                        selector: '.toggle[data-on="hover"]',
                        onEvent: 'mouseover mouseout',
                        className: 'active',
                        target: function($e) {
                            return $e.add($($e.attr('data-for')));
                        },
                        condition: false,
                        complete: false
                    }
                ],

                // initialize simple UI
                init: function () {

                    var x, ui, $e, callBack, conditionalCallBack, activeClass, targetName;

                    // loop round gumby UI elements applying active/inactive class logic
                    for (x in simpleUI.ui) {

                        ui = simpleUI.ui[x];
                        $e = $(ui.selector);
                        // complete call back
                        callBack = ui.complete && typeof ui.complete === 'function' ? ui.complete : false;
                        // conditional callback
                        conditionalCallBack = ui.condition && typeof ui.condition === 'function' ? ui.condition : false;
                        targetName = ui.target && typeof ui.target === 'function' ? ui.target : false;
                        activeClass = ui.className || false;

                        // store UI data
                        // replace spaces with dashes for GumbyData object reference
                        setGumbyData(ui.selector.replace(' ', '-'), {
                            'GumbyCallback' : callBack,
                            'GumbyConditionalCallBack' : conditionalCallBack,
                            'GumbyActiveClass' : activeClass,
                            'GumbyTarget' : targetName
                        });

                        // bind it all!
                        $(document).on(ui.onEvent, ui.selector, function (e) {
                            e.preventDefault();

                            var $this = $(this),
                                $target = $(this),
                                gumbyData = getGumbyData(e.handleObj.selector.replace(' ', '-')),
                                condition = true;

                            // if there is a conditional function test it here
                            // leaving if it returns false
                            if(gumbyData.GumbyConditionalCallBack) {
                                return condition = gumbyData.GumbyConditionalCallBack($this);
                            }

                            // no conditional or it passed so toggle class
                            if (gumbyData.GumbyActiveClass) {
                                // check for sepcified target
                                if(gumbyData.GumbyTarget) {
                                    $target = gumbyData.GumbyTarget($this);
                                }
                                $target.toggleClass(gumbyData.GumbyActiveClass);
                            }

                            // if complete call back present call it here
                            if (gumbyData.GumbyCallback) {
                                gumbyData.GumbyCallback($this);
                            }
                        });
                    }
                }
            },

            /**
             * Complex UI Elements
             * ------------------
             * UI elements that require logic passed the
             * capabilities of simple add/remove class.
             */
            complexUI = {

                // init separate complexUI elements
                init: function () {
                    complexUI.pickers();
                    complexUI.skipLinks();
                    complexUI.tabs();
                },

                // pickers - open picker on click and update <select> and picker label when option chosen
                pickers: function() {

                    // open picker on click
                    $(document).on('click', '.picker', function (e) {
                        e.preventDefault();

                        var $this = $(this),
                            openTimer = null;

                        // custom .picker style are removed on handheld devices using :after to insert hidden content and inform JS
                        if (window.getComputedStyle($this.get(0), ':after').getPropertyValue('content') === 'handheld') {
                            return false;
                        }

                        // mouseout for > 500ms will close picker
                        $this.hover(function () {
                            clearTimeout(openTimer);
                        }, function () {
                            var $this = $(this);
                            openTimer = setTimeout(function () {
                                $this.removeClass('open');
                            }, 500);
                        });

                        $this.toggleClass('open');
                    });

                    // clicking children elements should update hidden <select> and .picker active label
                    $(document).on('click', '.picker > ul > li', function (e) {
                        e.preventDefault();

                        var $this = $(this),
                            $parent = $this.parents('.picker'),
                            val = $this.children('a').html();

                        // update label
                        $parent.children('.toggle').html(val + '<span class="caret"></span>');

                        // update hidden select
                        $parent.find('option').attr('selected', false).eq($this.index()  +  1).attr('selected', true);
                    });
                },

                // skiplinks - slide to data-type content area on click of skiplink and on window load if hash present
                skipLinks: function () {
                    var skip = function () {

                        var skipTypeParts,
                            skipType,
                            $skipTos = $('[data-type]'),
                            $skipTo = false,
                            onWin = false,
                            $this = $(this);

                        if ($this.get(0) === window  && !window.location.hash) {
                            return false;
                        }

                        // initial load skip
                        if ($this.get(0) === window && window.location.hash) {
                            skipType = window.location.hash.replace('#', '');
                            onWin = true;
                        } else {
                            skipTypeParts = $this.attr('href').split('#');
                            skipType = skipTypeParts[skipTypeParts.length - 1];
                        }

                        // loop round potential data-type matches
                        $skipTos.each(function () {
                            // data-type can be multiple space separated values
                            var typeParts = $(this).attr('data-type').split(' '), x;

                            // find first match and break the each
                            for (x in typeParts) {
                                if (typeParts[x] === skipType) {
                                    $skipTo = $(this);
                                    return false;
                                }
                            }
                        });

                        if (!$skipTo.length) {
                            return false;
                        }

                        // scroll to skiplink
                        $('body,html').animate({
                            'scrollTop' : $skipTo.offset().top
                        }, 350);

                        // update hash if  not an initial hash load
                        if (onWin) {
                            window.location.hash = skipType;
                        }

                    };

                    // bind to skip links and window load
                    $(document).on('click', '.skiplink a, .skipnav ul li a, .skip', skip);
                    $(window).load(skip);
                },

                // tabs - activate tab and tab content on click as well as on window load if hash present
                tabs: function () {

                    var activateTab = function ($tab) {
                        var // this links tabs set
                            $tabs = $tab.parents('.tabs'),
                            // currently active tab
                            activeTab = {
                                'tab' : $tabs.find('ul').children('li.active'),
                                'content' : $tabs.find('div[data-tab].active')
                            },
                            // newly clicked tab
                            newTab = {
                                'tab' : $tab.parent('li'),
                                'content' : $tabs.find('[data-tab=' + $tab.attr('href').replace('#', '') + ']')
                            },
                            x, y;

                        // remove active class from tab and content
                        for (x in activeTab) {
                            activeTab[x].removeClass('active');
                        }

                        // add active class to tab and content
                        for (y in newTab) {
                            newTab[y].addClass('active');
                        }
                    }

                    // hook up tab links
                    $(document).on('click', '.tabs ul li a', function(e) {
                        activateTab($(this));
                    });

                    // hook up initial load active tab
                    if (window.location.hash) {
                        var $activeTab = $('a[href="'  +  window.location.hash  +  '"]');
                        if ($activeTab.length && $activeTab.parents('.tabs').length) {
                            activateTab($activeTab);
                        }
                    }
                }
            },

            // initialize Gumby
            init = function () {
                simpleUI.init();
                complexUI.init();
            };

        // return public methods
        return {
            i: init
        }
    }().i();

})(window.jQuery);





var ajaxCurrentRequest=null;
var ajaxcnt=0;
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

    $.ajaxSetup({ cache: false,
        error: function(jqXHR, textStatus, errorThrown) {
            if(jqXHR.status==0 && ajaxcnt==0)
            {
                ajaxCurrentRequest=this;
                $.fn.checknet();
                ajaxcnt++;
            }
        } });

    // To launch the main menu for the first time
    function initialLoadOut() {
        $.ajax({
            url: "main-menu.php",
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

var content_url = "itccontent.pitsco.com"
var cloudfront_url = "cloudfront.pitsco.com"

if(location.host == "localhost") {
    content_url = "localhost";
}
else {
    document.domain = 'pitsco.com';
}
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
        'width' : $('body').width(),
        'height' : $(window).height() - 90
    };

    $('body').append('<div id="divcustomlightbox" title="Synergy ITC"><div class="btnprevclose"><p class="dialogTitleFullScr">Preview</p><a href="javascript:void(0);" onclick="closefullscreenlesson()" class="icon-synergy-close-dark"></a></div><div id="divlbcontent"><iframe src="http://'+cloudfront_url+'/scormlib/rte.php?SCOInstanceID='+fldrnameinner+'&lessonid='+fldrnameinner+'&zipname='+fldrname+'&hostname='+location.host+'" width="100%"></iframe></div><div class="diviplbottom"></div></div>');

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

    //$('body').append('<div id="divcustomlightbox" title="Synergy ITC"><div class="btnprevclose"><p class="dialogTitleFullScr">Preview</p><a href="javascript:void(0);" onclick="closefullscreenlesson()" class="icon-synergy-close-dark"></a></div><div id="divlbcontent"><iframe src="http://'+content_url+'/webpdlesson/pdcontent.php" width="100%"></iframe></div><div class="diviplbottom"></div></div>');
    $('body').append('<div id="divcustomlightbox" title="Synergy ITC"><div class="btnprevclose"><a href="javascript:void(0);" onclick="closefullscreenlesson()" class="icon-synergy-close-dark" style="color:#24485f"></a></div><iframe src="http://'+cloudfront_url+'/webpdlesson/'+fldrname+'/'+pdfname+'/'+pdfname+'.html" width="100%" height="100%"></iframe></div>');

    $('#divcustomlightbox').css(cssObjOuter);
    $('#divlbcontent').css(cssObjInner);
    $('iframe').css({ 'width':$('#divlbcontent').width(), 'height' : $('#divlbcontent').height() });
    $('.btnprevclose').css({'height':'0'});
}
/* student level start created by chandru */
function showstudentlessonpd(fldrname,fldpdlessonid,pdfname,schid){
    window.open('http://itc.pitsco.com/library/pdlessons/library-pdlessons-studentviewscreen.php?fldrname='+fldrname+'&fldpdlessonid='+fldpdlessonid+'&pdfname='+pdfname+'&schid='+schid);
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
        ifrpath = 'http://'+cloudfront_url+'/moduleplay/assignmentplayer.php';
    }
    else if(type==3)
    {
        ifrpath = 'http://'+cloudfront_url+'/moduleplay/questsplayer.php';
    }
    else
    {
        ifrpath = 'http://'+cloudfront_url+'/moduleplay/moduleplayer.php';
    }

    $('body').append('<div id="divcustomlightbox" title="Synergy ITC"><iframe style="background-image: url(../img/loadinggif.gif);	background-repeat:no-repeat;background-position:center;" src="'+ifrpath+'?id='+escape(fldrname+','+$(window).height())+'&hostname='+location.host+'" width="100%" height="100%"></iframe></div>');
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




/**
 * jQuery Validation Plugin 1.9.0
 *
 * http://bassistance.de/jquery-plugins/jquery-plugin-validation/
 * http://docs.jquery.com/Plugins/Validation
 *
 * Copyright (c) 2006 - 2011 Jörn Zaefferer
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */

(function($) {

    $.extend($.fn, {
        // http://docs.jquery.com/Plugins/Validation/validate
        validate: function( options ) {

            // if nothing is selected, return nothing; can't chain anyway
            if (!this.length) {
                options && options.debug && window.console && console.warn( "nothing selected, can't validate, returning nothing" );
                return;
            }

            // check if a validator for this form was already created
            var validator = $.data(this[0], 'validator');
            if ( validator ) {
                return validator;
            }

            // Add novalidate tag if HTML5.
            this.attr('novalidate', 'novalidate');

            validator = new $.validator( options, this[0] );
            $.data(this[0], 'validator', validator);

            if ( validator.settings.onsubmit ) {

                var inputsAndButtons = this.find("input, button");

                // allow suppresing validation by adding a cancel class to the submit button
                inputsAndButtons.filter(".cancel").click(function () {
                    validator.cancelSubmit = true;
                });

                // when a submitHandler is used, capture the submitting button
                if (validator.settings.submitHandler) {
                    inputsAndButtons.filter(":submit").click(function () {
                        validator.submitButton = this;
                    });
                }

                // validate the form on submit
                this.submit( function( event ) {
                    if ( validator.settings.debug )
                    // prevent form submit to be able to see console output
                        event.preventDefault();

                    function handle() {
                        if ( validator.settings.submitHandler ) {
                            if (validator.submitButton) {
                                // insert a hidden input as a replacement for the missing submit button
                                var hidden = $("<input type='hidden'/>").attr("name", validator.submitButton.name).val(validator.submitButton.value).appendTo(validator.currentForm);
                            }
                            validator.settings.submitHandler.call( validator, validator.currentForm );
                            if (validator.submitButton) {
                                // and clean up afterwards; thanks to no-block-scope, hidden can be referenced
                                hidden.remove();
                            }
                            return false;
                        }
                        return true;
                    }

                    // prevent submit for invalid forms or custom submit handlers
                    if ( validator.cancelSubmit ) {
                        validator.cancelSubmit = false;
                        return handle();
                    }
                    if ( validator.form() ) {
                        if ( validator.pendingRequest ) {
                            validator.formSubmitted = true;
                            return false;
                        }
                        return handle();
                    } else {
                        validator.focusInvalid();
                        return false;
                    }
                });
            }

            return validator;
        },
        // http://docs.jquery.com/Plugins/Validation/valid
        valid: function() {
            if ( $(this[0]).is('form')) {
                return this.validate().form();
            } else {
                var valid = true;
                var validator = $(this[0].form).validate();
                this.each(function() {
                    valid &= validator.element(this);
                });
                return valid;
            }
        },
        // attributes: space seperated list of attributes to retrieve and remove
        removeAttrs: function(attributes) {
            var result = {},
                $element = this;
            $.each(attributes.split(/\s/), function(index, value) {
                result[value] = $element.attr(value);
                $element.removeAttr(value);
            });
            return result;
        },
        // http://docs.jquery.com/Plugins/Validation/rules
        rules: function(command, argument) {
            var element = this[0];

            if (command) {
                var settings = $.data(element.form, 'validator').settings;
                var staticRules = settings.rules;
                var existingRules = $.validator.staticRules(element);
                switch(command) {
                    case "add":
                        $.extend(existingRules, $.validator.normalizeRule(argument));
                        staticRules[element.name] = existingRules;
                        if (argument.messages)
                            settings.messages[element.name] = $.extend( settings.messages[element.name], argument.messages );
                        break;
                    case "remove":
                        if (!argument) {
                            delete staticRules[element.name];
                            return existingRules;
                        }
                        var filtered = {};
                        $.each(argument.split(/\s/), function(index, method) {
                            filtered[method] = existingRules[method];
                            delete existingRules[method];
                        });
                        return filtered;
                }
            }

            var data = $.validator.normalizeRules(
                $.extend(
                    {},
                    $.validator.metadataRules(element),
                    $.validator.classRules(element),
                    $.validator.attributeRules(element),
                    $.validator.staticRules(element)
                ), element);

            // make sure required is at front
            if (data.required) {
                var param = data.required;
                delete data.required;
                data = $.extend({required: param}, data);
            }

            return data;
        }
    });

// Custom selectors
    $.extend($.expr[":"], {
        // http://docs.jquery.com/Plugins/Validation/blank
        blank: function(a) {return !$.trim("" + a.value);},
        // http://docs.jquery.com/Plugins/Validation/filled
        filled: function(a) {return !!$.trim("" + a.value);},
        // http://docs.jquery.com/Plugins/Validation/unchecked
        unchecked: function(a) {return !a.checked;}
    });

// constructor for validator
    $.validator = function( options, form ) {
        this.settings = $.extend( true, {}, $.validator.defaults, options );
        this.currentForm = form;
        this.init();
    };

    $.validator.format = function(source, params) {
        if ( arguments.length == 1 )
            return function() {
                var args = $.makeArray(arguments);
                args.unshift(source);
                return $.validator.format.apply( this, args );
            };
        if ( arguments.length > 2 && params.constructor != Array  ) {
            params = $.makeArray(arguments).slice(1);
        }
        if ( params.constructor != Array ) {
            params = [ params ];
        }
        $.each(params, function(i, n) {
            source = source.replace(new RegExp("\\{" + i + "\\}", "g"), n);
        });
        return source;
    };

    $.extend($.validator, {

        defaults: {
            messages: {},
            groups: {},
            rules: {},
            errorClass: "error",
            validClass: "valid",
            errorElement: "div",
            focusInvalid: true,
            errorContainer: $( [] ),
            errorLabelContainer: $( [] ),
            onsubmit: true,
            ignore: ":hidden",
            ignoreTitle: false,
            onfocusin: function(element, event) {
                this.lastActive = element;

                // hide error label and remove error class on focus if enabled
                if ( this.settings.focusCleanup && !this.blockFocusCleanup ) {
                    this.settings.unhighlight && this.settings.unhighlight.call( this, element, this.settings.errorClass, this.settings.validClass );
                    this.addWrapper(this.errorsFor(element)).hide();
                }
            },
            onfocusout: function(element, event) {
                if ( !this.checkable(element) && (element.name in this.submitted || !this.optional(element)) ) {
                    this.element(element);
                }
            },
            onkeyup: function(element, event) {
                if ( element.name in this.submitted || element == this.lastElement ) {
                    this.element(element);
                }
            },
            onclick: function(element, event) {
                // click on selects, radiobuttons and checkboxes
                if ( element.name in this.submitted )
                    this.element(element);
                // or option elements, check parent select in that case
                else if (element.parentNode.name in this.submitted)
                    this.element(element.parentNode);
            },
            highlight: function(element, errorClass, validClass) {
                if (element.type === 'radio') {
                    this.findByName(element.name).addClass(errorClass).removeClass(validClass);
                } else {
                    $(element).addClass(errorClass).removeClass(validClass);
                }
            },
            unhighlight: function(element, errorClass, validClass) {
                if (element.type === 'radio') {
                    this.findByName(element.name).removeClass(errorClass).addClass(validClass);
                } else {
                    $(element).removeClass(errorClass).addClass(validClass);
                }
            }
        },

        // http://docs.jquery.com/Plugins/Validation/Validator/setDefaults
        setDefaults: function(settings) {
            $.extend( $.validator.defaults, settings );
        },

        messages: {
            required: "This field is required.",
            remote: "Please fix this field.",
            email: "Please enter a valid email address.",
            url: "Please enter a valid URL.",
            date: "Please enter a valid date.",
            dateISO: "Please enter a valid date (ISO).",
            number: "Please enter a valid number.",
            digits: "Please enter only digits.",
            creditcard: "Please enter a valid credit card number.",
            equalTo: "Please enter the same value again.",
            accept: "Please enter a value with a valid extension.",
            maxlength: $.validator.format("Please enter no more than {0} characters."),
            minlength: $.validator.format("Please enter at least {0} characters."),
            rangelength: $.validator.format("Please enter a value between {0} and {1} characters long."),
            range: $.validator.format("Please enter a value between {0} and {1}."),
            max: $.validator.format("Please enter a value less than or equal to {0}."),
            min: $.validator.format("Please enter a value greater than or equal to {0}.")
        },

        autoCreateRanges: false,

        prototype: {

            init: function() {
                this.labelContainer = $(this.settings.errorLabelContainer);
                this.errorContext = this.labelContainer.length && this.labelContainer || $(this.currentForm);
                this.containers = $(this.settings.errorContainer).add( this.settings.errorLabelContainer );
                this.submitted = {};
                this.valueCache = {};
                this.pendingRequest = 0;
                this.pending = {};
                this.invalid = {};
                this.reset();

                var groups = (this.groups = {});
                $.each(this.settings.groups, function(key, value) {
                    $.each(value.split(/\s/), function(index, name) {
                        groups[name] = key;
                    });
                });
                var rules = this.settings.rules;
                $.each(rules, function(key, value) {
                    rules[key] = $.validator.normalizeRule(value);
                });

                function delegate(event) {
                    var validator = $.data(this[0].form, "validator"),
                        eventType = "on" + event.type.replace(/^validate/, "");
                    validator.settings[eventType] && validator.settings[eventType].call(validator, this[0], event);
                }
                $(this.currentForm)
                    .validateDelegate("[type='text'], [type='password'], [type='file'], select, textarea, " +
                        "[type='number'], [type='search'] ,[type='tel'], [type='url'], " +
                        "[type='email'], [type='datetime'], [type='date'], [type='month'], " +
                        "[type='week'], [type='time'], [type='datetime-local'], " +
                        "[type='range'], [type='color'] ",
                        "focusin focusout keyup", delegate)
                    .validateDelegate("[type='radio'], [type='checkbox'], select, option", "click", delegate);

                if (this.settings.invalidHandler)
                    $(this.currentForm).bind("invalid-form.validate", this.settings.invalidHandler);
            },

            // http://docs.jquery.com/Plugins/Validation/Validator/form
            form: function() {
                this.checkForm();
                $.extend(this.submitted, this.errorMap);
                this.invalid = $.extend({}, this.errorMap);
                if (!this.valid())
                    $(this.currentForm).triggerHandler("invalid-form", [this]);
                this.showErrors();
                return this.valid();
            },

            checkForm: function() {
                this.prepareForm();
                for ( var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++ ) {
                    this.check( elements[i] );
                }
                return this.valid();
            },

            // http://docs.jquery.com/Plugins/Validation/Validator/element
            element: function( element ) {
                element = this.validationTargetFor( this.clean( element ) );
                this.lastElement = element;
                this.prepareElement( element );
                this.currentElements = $(element);
                var result = this.check( element );
                if ( result ) {
                    delete this.invalid[element.name];
                } else {
                    this.invalid[element.name] = true;
                }
                if ( !this.numberOfInvalids() ) {
                    // Hide error containers on last error
                    this.toHide = this.toHide.add( this.containers );
                }
                this.showErrors();
                return result;
            },

            // http://docs.jquery.com/Plugins/Validation/Validator/showErrors
            showErrors: function(errors) {
                if(errors) {
                    // add items to error list and map
                    $.extend( this.errorMap, errors );
                    this.errorList = [];
                    for ( var name in errors ) {
                        this.errorList.push({
                            message: errors[name],
                            element: this.findByName(name)[0]
                        });
                    }
                    // remove items from success list
                    this.successList = $.grep( this.successList, function(element) {
                        return !(element.name in errors);
                    });
                }
                this.settings.showErrors
                    ? this.settings.showErrors.call( this, this.errorMap, this.errorList )
                    : this.defaultShowErrors();
            },

            // http://docs.jquery.com/Plugins/Validation/Validator/resetForm
            resetForm: function() {
                if ( $.fn.resetForm )
                    $( this.currentForm ).resetForm();
                this.submitted = {};
                this.lastElement = null;
                this.prepareForm();
                this.hideErrors();
                this.elements().removeClass( this.settings.errorClass );
            },

            numberOfInvalids: function() {
                return this.objectLength(this.invalid);
            },

            objectLength: function( obj ) {
                var count = 0;
                for ( var i in obj )
                    count++;
                return count;
            },

            hideErrors: function() {
                this.addWrapper( this.toHide ).hide();
            },

            valid: function() {
                return this.size() == 0;
            },

            size: function() {
                return this.errorList.length;
            },

            focusInvalid: function() {
                if( this.settings.focusInvalid ) {
                    try {
                        $(this.findLastActive() || this.errorList.length && this.errorList[0].element || [])
                            .filter(":visible")
                            .focus()
                            // manually trigger focusin event; without it, focusin handler isn't called, findLastActive won't have anything to find
                            .trigger("focusin");
                    } catch(e) {
                        // ignore IE throwing errors when focusing hidden elements
                    }
                }
            },

            findLastActive: function() {
                var lastActive = this.lastActive;
                return lastActive && $.grep(this.errorList, function(n) {
                        return n.element.name == lastActive.name;
                    }).length == 1 && lastActive;
            },

            elements: function() {
                var validator = this,
                    rulesCache = {};

                // select all valid inputs inside the form (no submit or reset buttons)
                return $(this.currentForm)
                    .find("input, select, textarea")
                    .not(":submit, :reset, :image, [disabled]")
                    .not( this.settings.ignore )
                    .filter(function() {
                        !this.name && validator.settings.debug && window.console && console.error( "%o has no name assigned", this);

                        // select only the first element for each name, and only those with rules specified
                        if ( this.name in rulesCache || !validator.objectLength($(this).rules()) )
                            return false;

                        rulesCache[this.name] = true;
                        return true;
                    });
            },

            clean: function( selector ) {
                return $( selector )[0];
            },

            errors: function() {
                return $( this.settings.errorElement + "." + this.settings.errorClass, this.errorContext );
            },

            reset: function() {
                this.successList = [];
                this.errorList = [];
                this.errorMap = {};
                this.toShow = $([]);
                this.toHide = $([]);
                this.currentElements = $([]);
            },

            prepareForm: function() {
                this.reset();
                this.toHide = this.errors().add( this.containers );
            },

            prepareElement: function( element ) {
                this.reset();
                this.toHide = this.errorsFor(element);
            },

            check: function( element ) {
                element = this.validationTargetFor( this.clean( element ) );

                var rules = $(element).rules();
                var dependencyMismatch = false;
                for (var method in rules ) {
                    var rule = { method: method, parameters: rules[method] };
                    try {
                        var result = $.validator.methods[method].call( this, element.value.replace(/\r/g, ""), element, rule.parameters );

                        // if a method indicates that the field is optional and therefore valid,
                        // don't mark it as valid when there are no other rules
                        if ( result == "dependency-mismatch" ) {
                            dependencyMismatch = true;
                            continue;
                        }
                        dependencyMismatch = false;

                        if ( result == "pending" ) {
                            this.toHide = this.toHide.not( this.errorsFor(element) );
                            return;
                        }

                        if( !result ) {
                            this.formatAndAdd( element, rule );
                            return false;
                        }
                    } catch(e) {
                        this.settings.debug && window.console && console.log("exception occured when checking element " + element.id
                            + ", check the '" + rule.method + "' method", e);
                        throw e;
                    }
                }
                if (dependencyMismatch)
                    return;
                if ( this.objectLength(rules) )
                    this.successList.push(element);
                return true;
            },

            // return the custom message for the given element and validation method
            // specified in the element's "messages" metadata
            customMetaMessage: function(element, method) {
                if (!$.metadata)
                    return;

                var meta = this.settings.meta
                    ? $(element).metadata()[this.settings.meta]
                    : $(element).metadata();

                return meta && meta.messages && meta.messages[method];
            },

            // return the custom message for the given element name and validation method
            customMessage: function( name, method ) {
                var m = this.settings.messages[name];
                return m && (m.constructor == String
                        ? m
                        : m[method]);
            },

            // return the first defined argument, allowing empty strings
            findDefined: function() {
                for(var i = 0; i < arguments.length; i++) {
                    if (arguments[i] !== undefined)
                        return arguments[i];
                }
                return undefined;
            },

            defaultMessage: function( element, method) {
                return this.findDefined(
                    this.customMessage( element.name, method ),
                    this.customMetaMessage( element, method ),
                    // title is never undefined, so handle empty string as undefined
                    !this.settings.ignoreTitle && element.title || undefined,
                    $.validator.messages[method],
                    "<strong>Warning: No message defined for " + element.name + "</strong>"
                );
            },

            formatAndAdd: function( element, rule ) {
                var message = this.defaultMessage( element, rule.method ),
                    theregex = /\$?\{(\d+)\}/g;
                if ( typeof message == "function" ) {
                    message = message.call(this, rule.parameters, element);
                } else if (theregex.test(message)) {
                    message = jQuery.format(message.replace(theregex, '{$1}'), rule.parameters);
                }
                this.errorList.push({
                    message: message,
                    element: element
                });

                this.errorMap[element.name] = message;
                this.submitted[element.name] = message;
            },

            addWrapper: function(toToggle) {
                if ( this.settings.wrapper )
                    toToggle = toToggle.add( toToggle.parent( this.settings.wrapper ) );
                return toToggle;
            },

            defaultShowErrors: function() {
                for ( var i = 0; this.errorList[i]; i++ ) {
                    var error = this.errorList[i];
                    this.settings.highlight && this.settings.highlight.call( this, error.element, this.settings.errorClass, this.settings.validClass );
                    this.showLabel( error.element, error.message );
                }
                if( this.errorList.length ) {
                    this.toShow = this.toShow.add( this.containers );
                }
                if (this.settings.success) {
                    for ( var i = 0; this.successList[i]; i++ ) {
                        this.showLabel( this.successList[i] );
                    }
                }
                if (this.settings.unhighlight) {
                    for ( var i = 0, elements = this.validElements(); elements[i]; i++ ) {
                        this.settings.unhighlight.call( this, elements[i], this.settings.errorClass, this.settings.validClass );
                    }
                }
                this.toHide = this.toHide.not( this.toShow );
                this.hideErrors();
                this.addWrapper( this.toShow ).show();
            },

            validElements: function() {
                return this.currentElements.not(this.invalidElements());
            },

            invalidElements: function() {
                return $(this.errorList).map(function() {
                    return this.element;
                });
            },

            showLabel: function(element, message) {
                var label = this.errorsFor( element );
                if ( label.length ) {
                    // refresh error/success class
                    label.removeClass( this.settings.validClass ).addClass( this.settings.errorClass );

                    // check if we have a generated label, replace the message then
                    label.attr("generated") && label.html(message+"<span class='caret'></span>");
                } else {
                    // create label
                    label = $("<" + this.settings.errorElement + "/>")
                        .attr({"for":  this.idOrName(element), generated: true})
                        .addClass(this.settings.errorClass)
                        .html(message+"<span class='caret'></span>" || "");
                    if ( this.settings.wrapper ) {
                        // make sure the element is visible, even in IE
                        // actually showing the wrapped element is handled elsewhere
                        label = label.hide().show().wrap("<" + this.settings.wrapper + "/>").parent();
                    }
                    if ( !this.labelContainer.append(label).length )
                        this.settings.errorPlacement
                            ? this.settings.errorPlacement(label, $(element) )
                            : label.insertAfter(element);
                }
                if ( !message && this.settings.success ) {
                    label.text("");
                    typeof this.settings.success == "string"
                        ? label.addClass( this.settings.success )
                        : this.settings.success( label );
                }
                this.toShow = this.toShow.add(label);
            },

            errorsFor: function(element) {
                var name = this.idOrName(element);
                return this.errors().filter(function() {
                    return $(this).attr('for') == name;
                });
            },

            idOrName: function(element) {
                return this.groups[element.name] || (this.checkable(element) ? element.name : element.id || element.name);
            },

            validationTargetFor: function(element) {
                // if radio/checkbox, validate first element in group instead
                if (this.checkable(element)) {
                    element = this.findByName( element.name ).not(this.settings.ignore)[0];
                }
                return element;
            },

            checkable: function( element ) {
                return /radio|checkbox/i.test(element.type);
            },

            findByName: function( name ) {
                // select by name and filter by form for performance over form.find("[name=...]")
                var form = this.currentForm;
                return $(document.getElementsByName(name)).map(function(index, element) {
                    return element.form == form && element.name == name && element  || null;
                });
            },

            getLength: function(value, element) {
                switch( element.nodeName.toLowerCase() ) {
                    case 'select':
                        return $("option:selected", element).length;
                    case 'input':
                        if( this.checkable( element) )
                            return this.findByName(element.name).filter(':checked').length;
                }
                return value.length;
            },

            depend: function(param, element) {
                return this.dependTypes[typeof param]
                    ? this.dependTypes[typeof param](param, element)
                    : true;
            },

            dependTypes: {
                "boolean": function(param, element) {
                    return param;
                },
                "string": function(param, element) {
                    return !!$(param, element.form).length;
                },
                "function": function(param, element) {
                    return param(element);
                }
            },

            optional: function(element) {
                return !$.validator.methods.required.call(this, $.trim(element.value), element) && "dependency-mismatch";
            },

            startRequest: function(element) {
                if (!this.pending[element.name]) {
                    this.pendingRequest++;
                    this.pending[element.name] = true;
                }
            },

            stopRequest: function(element, valid) {
                this.pendingRequest--;
                // sometimes synchronization fails, make sure pendingRequest is never < 0
                if (this.pendingRequest < 0)
                    this.pendingRequest = 0;
                delete this.pending[element.name];
                if ( valid && this.pendingRequest == 0 && this.formSubmitted && this.form() ) {
                    $(this.currentForm).submit();
                    this.formSubmitted = false;
                } else if (!valid && this.pendingRequest == 0 && this.formSubmitted) {
                    $(this.currentForm).triggerHandler("invalid-form", [this]);
                    this.formSubmitted = false;
                }
            },

            previousValue: function(element) {
                return $.data(element, "previousValue") || $.data(element, "previousValue", {
                        old: null,
                        valid: true,
                        message: this.defaultMessage( element, "remote" )
                    });
            }

        },

        classRuleSettings: {
            required: {required: true},
            email: {email: true},
            url: {url: true},
            date: {date: true},
            dateISO: {dateISO: true},
            dateDE: {dateDE: true},
            number: {number: true},
            numberDE: {numberDE: true},
            digits: {digits: true},
            creditcard: {creditcard: true}
        },

        addClassRules: function(className, rules) {
            className.constructor == String ?
                this.classRuleSettings[className] = rules :
                $.extend(this.classRuleSettings, className);
        },

        classRules: function(element) {
            var rules = {};
            var classes = $(element).attr('class');
            classes && $.each(classes.split(' '), function() {
                if (this in $.validator.classRuleSettings) {
                    $.extend(rules, $.validator.classRuleSettings[this]);
                }
            });
            return rules;
        },

        attributeRules: function(element) {
            var rules = {};
            var $element = $(element);

            for (var method in $.validator.methods) {
                var value;
                // If .prop exists (jQuery >= 1.6), use it to get true/false for required
                if (method === 'required' && typeof $.fn.prop === 'function') {
                    value = $element.prop(method);
                } else {
                    value = $element.attr(method);
                }
                if (value) {
                    rules[method] = value;
                } else if ($element[0].getAttribute("type") === method) {
                    rules[method] = true;
                }
            }

            // maxlength may be returned as -1, 2147483647 (IE) and 524288 (safari) for text inputs
            if (rules.maxlength && /-1|2147483647|524288/.test(rules.maxlength)) {
                delete rules.maxlength;
            }

            return rules;
        },

        metadataRules: function(element) {
            if (!$.metadata) return {};

            var meta = $.data(element.form, 'validator').settings.meta;
            return meta ?
                $(element).metadata()[meta] :
                $(element).metadata();
        },

        staticRules: function(element) {
            var rules = {};
            var validator = $.data(element.form, 'validator');
            if (validator.settings.rules) {
                rules = $.validator.normalizeRule(validator.settings.rules[element.name]) || {};
            }
            return rules;
        },

        normalizeRules: function(rules, element) {
            // handle dependency check
            $.each(rules, function(prop, val) {
                // ignore rule when param is explicitly false, eg. required:false
                if (val === false) {
                    delete rules[prop];
                    return;
                }
                if (val.param || val.depends) {
                    var keepRule = true;
                    switch (typeof val.depends) {
                        case "string":
                            keepRule = !!$(val.depends, element.form).length;
                            break;
                        case "function":
                            keepRule = val.depends.call(element, element);
                            break;
                    }
                    if (keepRule) {
                        rules[prop] = val.param !== undefined ? val.param : true;
                    } else {
                        delete rules[prop];
                    }
                }
            });

            // evaluate parameters
            $.each(rules, function(rule, parameter) {
                rules[rule] = $.isFunction(parameter) ? parameter(element) : parameter;
            });

            // clean number parameters
            $.each(['minlength', 'maxlength', 'min', 'max'], function() {
                if (rules[this]) {
                    rules[this] = Number(rules[this]);
                }
            });
            $.each(['rangelength', 'range'], function() {
                if (rules[this]) {
                    rules[this] = [Number(rules[this][0]), Number(rules[this][1])];
                }
            });

            if ($.validator.autoCreateRanges) {
                // auto-create ranges
                if (rules.min && rules.max) {
                    rules.range = [rules.min, rules.max];
                    delete rules.min;
                    delete rules.max;
                }
                if (rules.minlength && rules.maxlength) {
                    rules.rangelength = [rules.minlength, rules.maxlength];
                    delete rules.minlength;
                    delete rules.maxlength;
                }
            }

            // To support custom messages in metadata ignore rule methods titled "messages"
            if (rules.messages) {
                delete rules.messages;
            }

            return rules;
        },

        // Converts a simple string to a {string: true} rule, e.g., "required" to {required:true}
        normalizeRule: function(data) {
            if( typeof data == "string" ) {
                var transformed = {};
                $.each(data.split(/\s/), function() {
                    transformed[this] = true;
                });
                data = transformed;
            }
            return data;
        },

        // http://docs.jquery.com/Plugins/Validation/Validator/addMethod
        addMethod: function(name, method, message) {
            $.validator.methods[name] = method;
            $.validator.messages[name] = message != undefined ? message : $.validator.messages[name];
            if (method.length < 3) {
                $.validator.addClassRules(name, $.validator.normalizeRule(name));
            }
        },

        methods: {

            // http://docs.jquery.com/Plugins/Validation/Methods/required
            required: function(value, element, param) {
                // check if dependency is met
                if ( !this.depend(param, element) )
                    return "dependency-mismatch";
                switch( element.nodeName.toLowerCase() ) {
                    case 'select':
                        // could be an array for select-multiple or a string, both are fine this way
                        var val = $(element).val();
                        return val && val.length > 0;
                    case 'input':
                        if ( this.checkable(element) )
                            return this.getLength(value, element) > 0;
                    default:
                        return $.trim(value).length > 0;
                }
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/remote
            remote: function(value, element, param) {
                if ( this.optional(element) )
                    return "dependency-mismatch";

                var previous = this.previousValue(element);
                if (!this.settings.messages[element.name] )
                    this.settings.messages[element.name] = {};
                previous.originalMessage = this.settings.messages[element.name].remote;
                this.settings.messages[element.name].remote = previous.message;

                param = typeof param == "string" && {url:param} || param;

                if ( this.pending[element.name] ) {
                    return "pending";
                }
                if ( previous.old === value ) {
                    return previous.valid;
                }

                previous.old = value;
                var validator = this;
                this.startRequest(element);
                var data = {};
                data[element.name] = value;
                $.ajax($.extend(true, {
                    url: param,
                    mode: "abort",
                    port: "validate" + element.name,
                    dataType: "json",
                    data: data,
                    success: function(response) {
                        validator.settings.messages[element.name].remote = previous.originalMessage;
                        var valid = response === true;
                        if ( valid ) {
                            var submitted = validator.formSubmitted;
                            validator.prepareElement(element);
                            validator.formSubmitted = submitted;
                            validator.successList.push(element);
                            validator.showErrors();
                        } else {
                            var errors = {};
                            var message = response || validator.defaultMessage( element, "remote" );
                            errors[element.name] = previous.message = $.isFunction(message) ? message(value) : message;
                            validator.showErrors(errors);
                        }
                        previous.valid = valid;
                        validator.stopRequest(element, valid);
                    }
                }, param));
                return "pending";
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/minlength
            minlength: function(value, element, param) {
                return this.optional(element) || this.getLength($.trim(value), element) >= param;
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/maxlength
            maxlength: function(value, element, param) {
                return this.optional(element) || this.getLength($.trim(value), element) <= param;
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/rangelength
            rangelength: function(value, element, param) {
                var length = this.getLength($.trim(value), element);
                return this.optional(element) || ( length >= param[0] && length <= param[1] );
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/min
            min: function( value, element, param ) {
                return this.optional(element) || value >= param;
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/max
            max: function( value, element, param ) {
                return this.optional(element) || value <= param;
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/range
            range: function( value, element, param ) {
                return this.optional(element) || ( value >= param[0] && value <= param[1] );
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/email
            email: function(value, element) {
                // contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
                return this.optional(element) || /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i.test(value);
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/url
            url: function(value, element) {
                // contributed by Scott Gonzalez: http://projects.scottsplayground.com/iri/
                return this.optional(element) || /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(value);
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/date
            date: function(value, element) {
                return this.optional(element) || !/Invalid|NaN/.test(new Date(value));
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/dateISO
            dateISO: function(value, element) {
                return this.optional(element) || /^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/.test(value);
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/number
            number: function(value, element) {
                return this.optional(element) || /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(value);
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/digits
            digits: function(value, element) {
                return this.optional(element) || /^\d+$/.test(value);
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/creditcard
            // based on http://en.wikipedia.org/wiki/Luhn
            creditcard: function(value, element) {
                if ( this.optional(element) )
                    return "dependency-mismatch";
                // accept only spaces, digits and dashes
                if (/[^0-9 -]+/.test(value))
                    return false;
                var nCheck = 0,
                    nDigit = 0,
                    bEven = false;

                value = value.replace(/\D/g, "");

                for (var n = value.length - 1; n >= 0; n--) {
                    var cDigit = value.charAt(n);
                    var nDigit = parseInt(cDigit, 10);
                    if (bEven) {
                        if ((nDigit *= 2) > 9)
                            nDigit -= 9;
                    }
                    nCheck += nDigit;
                    bEven = !bEven;
                }

                return (nCheck % 10) == 0;
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/accept
            accept: function(value, element, param) {
                param = typeof param == "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
                return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/equalTo
            equalTo: function(value, element, param) {
                // bind to the blur event of the target in order to revalidate whenever the target field is updated
                // TODO find a way to bind the event just once, avoiding the unbind-rebind overhead
                var target = $(param).unbind(".validate-equalTo").bind("blur.validate-equalTo", function() {
                    $(element).valid();
                });
                return value == target.val();
            }

        }

    });

// deprecated, use $.validator.format instead
    $.format = $.validator.format;

})(jQuery);

// ajax mode: abort
// usage: $.ajax({ mode: "abort"[, port: "uniqueport"]});
// if mode:"abort" is used, the previous request on that port (port can be undefined) is aborted via XMLHttpRequest.abort()
;(function($) {
    var pendingRequests = {};
    // Use a prefilter if available (1.5+)
    if ( $.ajaxPrefilter ) {
        $.ajaxPrefilter(function(settings, _, xhr) {
            var port = settings.port;
            if (settings.mode == "abort") {
                if ( pendingRequests[port] ) {
                    pendingRequests[port].abort();
                }
                pendingRequests[port] = xhr;
            }
        });
    } else {
        // Proxy ajax
        var ajax = $.ajax;
        $.ajax = function(settings) {
            var mode = ( "mode" in settings ? settings : $.ajaxSettings ).mode,
                port = ( "port" in settings ? settings : $.ajaxSettings ).port;
            if (mode == "abort") {
                if ( pendingRequests[port] ) {
                    pendingRequests[port].abort();
                }
                return (pendingRequests[port] = ajax.apply(this, arguments));
            }
            return ajax.apply(this, arguments);
        };
    }
})(jQuery);

// provides cross-browser focusin and focusout events
// IE has native support, in other browsers, use event caputuring (neither bubbles)

// provides delegate(type: String, delegate: Selector, handler: Callback) plugin for easier event delegation
// handler is only called when $(event.target).is(delegate), in the scope of the jquery-object for event.target
;(function($) {
    // only implement if not provided by jQuery core (since 1.4)
    // TODO verify if jQuery 1.4's implementation is compatible with older jQuery special-event APIs
    if (!jQuery.event.special.focusin && !jQuery.event.special.focusout && document.addEventListener) {
        $.each({
            focus: 'focusin',
            blur: 'focusout'
        }, function( original, fix ){
            $.event.special[fix] = {
                setup:function() {
                    this.addEventListener( original, handler, true );
                },
                teardown:function() {
                    this.removeEventListener( original, handler, true );
                },
                handler: function(e) {
                    arguments[0] = $.event.fix(e);
                    arguments[0].type = fix;
                    return $.event.handle.apply(this, arguments);
                }
            };
            function handler(e) {
                e = $.event.fix(e);
                e.type = fix;
                return $.event.handle.call(this, e);
            }
        });
    };
    $.extend($.fn, {
        validateDelegate: function(delegate, type, handler) {
            return this.bind(type, function(event) {
                var target = $(event.target);
                if (target.is(delegate)) {
                    return handler.apply(target, arguments);
                }
            });
        }
    });
})(jQuery);

$.validator.addMethod("letterswithbasicpunc", function(value, element) {
    return this.optional(element) || /^[a-z-.,()'\"\s]+$/i.test(value);
}, "Letters or punctuation only please");

// JavaScript Document
$.validator.addMethod("phone_number", function(value, element) {
    return this.optional(element) || value === "NA" ||
        value.match(/^[0-9]+$/);
}, "Please enter a valid phone number");

// For Address
$.validator.addMethod("letterswithbasicpunc", function(value, element) {
    return this.optional(element) || /^[a-z0-9-_.,#()&':;\"\s]+$/i.test(value);
}, "Letters, numbers and basic punctuation are allowed");

$.validator.addMethod("alphanumeric", function(value, element) {
    return this.optional(element) || /^\w+\s$/i.test(value);
}, "Letters, numbers, spaces or underscores are allowed");

// For first name and last names
$.validator.addMethod("lettersonly", function(value, element) {
    //return this.optional(element) || /^([ \u00c0-\u01ffa-zA-Z0-9-_.'\-])+\s*$/i.test(value);
    return this.optional(element) || /^([a-zA-Z0-9-_']+\s?)*$/i.test(value);
}, "Letters, numbers, spaces and underscores are allowed");

$.validator.addMethod("lettersonlyschedule", function(value, element) {
    //return this.optional(element) || /^([ \u00c0-\u01ffa-zA-Z0-9-_.'\-])+\s*$/i.test(value);
    return this.optional(element) || /^([a-zA-Z0-9-_']+\s?)*$/i.test(value);
}, "Symbols are not allowed");

// For Username Validation
$.validator.addMethod("chkusername", function(value, element) {
    return this.optional(element) || /^[A-Za-z0-9_!#$%&'*/=?^_+-{|}~]{3,100}$/i.test(value);
}, "Alphabetic characters, numbers, and valid e-mail address characters are allowed");

$.validator.addMethod('filesize', function(value, element, param) {
    return this.optional(element) || (element.files[0].size <= param)
});

$.validator.addMethod("placeholder", function(value, element) {
    return value!=$(element).attr("placeholder");
}, $.validator.messages.required);

$.validator.addMethod("quantity", function(value, element) {
    return !this.optional(element);
});

jQuery.validator.addMethod("greaterThan", function(value, element, params) {
    if (!/Invalid|NaN/.test(new Date(value))) {
        return new Date(value) >= new Date($(params).val());
    }
    return isNaN(value) && isNaN($(params).val()) || (Number(value) >= Number($(params).val()));
},'Must be greater than {0}.');

jQuery.fn.ForceNumericOnly = function(){
    return this.each(function(){
        $(this).keydown(function(e){
            var key = e.charCode || e.keyCode || 0;
            // allow backspace, tab, delete, arrows, numbers and keypad numbers ONLY
            return ( key == 8 || key == 9 || key == 46 || (key >= 37 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105));
        });
    });
};

function ampreplace(str){
    return str.replace(/&/g,"%26");
}

function trim(stringToTrim){
    return stringToTrim.replace(/^\s+|\s+$/g,"");
}

/*
 Script: GrowingInput.js
 Alters the size of an input depending on its content

 License:
 MIT-style license.

 Authors:
 Guillermo Rauch
 */

(function($){

    $.GrowingInput = function(element, options){

        var value, lastValue, calc;

        options = $.extend({
            min: 0,
            max: null,
            startWidth: 15,
            correction: 15
        }, options);

        element = $(element).data('growing', this);

        var self = this;
        var init = function(){
            calc = $('<span></span>').css({
                'float': 'left',
                'display': 'inline-block',
                'position': 'absolute',
                'left': -1000
            }).insertAfter(element);
            $.each(['font-size', 'font-family', 'padding-left', 'padding-top', 'padding-bottom',
                'padding-right', 'border-left', 'border-right', 'border-top', 'border-bottom',
                'word-spacing', 'letter-spacing', 'text-indent', 'text-transform'], function(i, p){
                calc.css(p, element.css(p));
            });
            element.blur(resize).keyup(resize).keydown(resize).keypress(resize);
            resize();
        };

        var calculate = function(chars){
            calc.text(chars);
            var width = calc.width();
            return (width ? width : options.startWidth) + options.correction;
        };

        var resize = function(){
            lastValue = value;
            value = element.val();
            var retValue = value;
            if(chk(options.min) && value.length < options.min){
                if(chk(lastValue) && (lastValue.length <= options.min)) return;
                retValue = str_pad(value, options.min, '-');
            } else if(chk(options.max) && value.length > options.max){
                if(chk(lastValue) && (lastValue.length >= options.max)) return;
                retValue = value.substr(0, options.max);
            }
            element.width(calculate(retValue));
            return self;
        };

        this.resize = resize;
        init();
    };

    var chk = function(v){ return !!(v || v === 0); };
    var str_repeat = function(str, times){ return new Array(times + 1).join(str); };
    var str_pad = function(self, length, str, dir){
        if (self.length >= length) return this;
        str = str || ' ';
        var pad = str_repeat(str, length - self.length).substr(0, length - self.length);
        if (!dir || dir == 'right') return self + pad;
        if (dir == 'left') return pad + self;
        return pad.substr(0, (pad.length / 2).floor()) + self + pad.substr(0, (pad.length / 2).ceil());
    };

})(jQuery);

/*
 Script: TextboxList.js
 Displays a textbox as a combination of boxes an inputs (eg: facebook tokenizer)

 Authors:
 Guillermo Rauch

 Note:
 TextboxList is not priceless for commercial use. See <http://devthought.com/projects/jquery/textboxlist/>.
 Purchase to remove this message.
 */

(function($){

    $.TextboxList = function(element, _options){

        var original, container, list, current, focused = false, index = [], blurtimer, events = {};
        var options = $.extend(true, {
            prefix: 'textboxlist',
            max: null,
            unique: false,
            uniqueInsensitive: true,
            endEditableBit: true,
            startEditableBit: true,
            hideEditableBits: true,
            inBetweenEditableBits: true,
            keys: {previous: 37, next: 39},
            bitsOptions: {editable: {}, box: {}},
            plugins: {},
            // tip: you can change encode/decode with JSON.stringify and JSON.parse
            encode: function(o){
                return $.grep($.map(o, function(v){
                    v = (chk(v[0]) ? v[0] : v[1]);
                    return chk(v) ? v.toString().replace(/,/, '') : null;
                }), function(o){ return o != undefined; }).join(',');
            },
            decode: function(o){ return o.split(','); }
        }, _options);

        element = $(element);

        var self = this;
        var init = function(){
            original = element.css('display', 'none').attr('autocomplete', 'off').focus(focusLast);
            container = $('<div class="'+options.prefix+'" />')
                .insertAfter(element)
                .click(function(e){
                    if ((e.target == list.get(0) || e.target == container.get(0)) && (!focused || (current && current.toElement().get(0) != list.find(':last-child').get(0)))) focusLast();
                });
            list = $('<ul class="'+ options.prefix +'-bits" />').appendTo(container);
            for (var name in options.plugins) enablePlugin(name, options.plugins[name]);
            afterInit();
        };

        var enablePlugin = function(name, options){
            self.plugins[name] = new $.TextboxList[camelCase(capitalize(name))](self, options);
        };

        var afterInit = function(){
            if (options.endEditableBit) create('editable', null, {tabIndex: original.tabIndex}).inject(list);
            addEvent('bitAdd', update, true);
            addEvent('bitRemove', update, true);
            $(document).click(function(e){
                if (!focused) return;
                if (e.target.className.indexOf(options.prefix) != -1){
                    if (e.target == $(container).get(0)) return;
                    var parent = $(e.target).parents('div.' + options.prefix);
                    if (parent.get(0) == container.get(0)) return;
                }
                blur();
            }).keydown(function(ev){
                if (!focused || !current) return;
                var caret = current.is('editable') ? current.getCaret() : null;
                var value = current.getValue()[1];
                var special = !!$.map(['shift', 'alt', 'meta', 'ctrl'], function(e){ return ev[e]; }).length;
                var custom = special || (current.is('editable') && current.isSelected());
                var evStop = function(){ ev.stopPropagation(); ev.preventDefault(); };
                switch (ev.which){
                    case 8:
                        if (current.is('box')){
                            evStop();
                            return current.remove();
                        }
                    case options.keys.previous:
                        if (current.is('box') || ((caret == 0 || !value.length) && !custom)){
                            evStop();
                            focusRelative('prev');
                        }
                        break;
                    case 46:
                        if (current.is('box')){
                            evStop();
                            return current.remove();
                        }
                    case options.keys.next:
                        if (current.is('box') || (caret == value.length && !custom)){
                            evStop();
                            focusRelative('next');
                        }
                }
            });
            setValues(options.decode(original.val()));
        };

        var create = function(klass, value, opt){
            if (klass == 'box'){
                if (chk(options.max) && list.children('.' + options.prefix + '-bit-box').length + 1 > options.max) return false;
                if (options.unique && $.inArray(uniqueValue(value), index) != -1) return false;
            }
            return new $.TextboxListBit(klass, value, self, $.extend(true, options.bitsOptions[klass], opt));
        };

        var uniqueValue = function(value){
            return chk(value[0]) ? value[0] : (options.uniqueInsensitive ? value[1].toLowerCase() : value[1]);
        }

        var add = function(plain, id, html, afterEl){
            var b = create('box', [id, plain, html]);
            if (b){
                if (!afterEl || !afterEl.length) afterEl = list.find('.' + options.prefix + '-bit-box').filter(':last');
                b.inject(afterEl.length ? afterEl : list, afterEl.length ? 'after' : 'top');
            }
            return self;
        };

        var focusRelative = function(dir, to){
            var el = getBit(to && $(to).length ? to : current).toElement();
            var b = getBit(el[dir]());
            if (b) b.focus();
            return self;
        };

        var focusLast = function(){
            var lastElement = list.children().filter(':last');
            if (lastElement) getBit(lastElement).focus();
            return self;
        };

        var blur = function(){
            if (! focused) return self;
            if (current) current.blur();
            focused = false;
            return fireEvent('blur');
        };

        var getBit = function(obj){
            return (obj.type && (obj.type == 'editable' || obj.type == 'box')) ? obj : $(obj).data('textboxlist:bit');
        };

        var getValues = function(){
            var values = [];
            list.children().each(function(){
                var bit = getBit(this);
                if (!bit.is('editable')) values.push(bit.getValue());
            });
            return values;
        };

        var setValues = function(values){
            if (!values) return;
            $.each(values, function(i, v){
                if (v) add.apply(self, $.isArray(v) ? [v[1], v[0], v[2]] : [v]);
            });
        };

        var update = function(){
            original.val(options.encode(getValues()));
        };

        var addEvent = function(type, fn){
            if (events[type] == undefined) events[type] = [];
            var exists = false;
            $.each(events[type], function(f){
                if (f === fn){
                    exists = true;
                    return;
                };
            });
            if (!exists) events[type].push(fn);
            return self;
        };

        var fireEvent = function(type, args, delay){
            if (!events || !events[type]) return self;
            $.each(events[type], function(i, fn){
                (function(){
                    args = (args != undefined) ? splat(args) : Array.prototype.slice.call(arguments);
                    var returns = function(){
                        return fn.apply(self || null, args);
                    };
                    if (delay) return setTimeout(returns, delay);
                    return returns();
                })();
            });
            return self;
        };

        var removeEvent = function(type, fn){
            if (events[type]){
                for (var i = events[type].length; i--; i){
                    if (events[type][i] === fn) events[type].splice(i, 1);
                }
            }
            return self;
        };

        var isDuplicate = function(v){
            return $.inArray(uniqueValue(v), index);
        };

        this.onFocus = function(bit){
            if (current) current.blur();
            clearTimeout(blurtimer);
            current = bit;
            container.addClass(options.prefix + '-focus');
            if (!focused){
                focused = true;
                fireEvent('focus', bit);
            }
        };

        this.onAdd = function(bit){
            if (options.unique && bit.is('box')) index.push(uniqueValue(bit.getValue()));
            if (bit.is('box')){
                var prior = getBit(bit.toElement().prev());
                if ((prior && prior.is('box') && options.inBetweenEditableBits) || (!prior && options.startEditableBit)){
                    var priorEl = prior && prior.toElement().length ? prior.toElement() : false;
                    var b = create('editable').inject(priorEl || list, priorEl ? 'after' : 'top');
                    if (options.hideEditableBits) b.hide();
                }
            }
        };

        this.onRemove = function(bit){
            if (!focused) return;
            if (options.unique && bit.is('box')){
                var i = isDuplicate(bit.getValue());
                if (i != -1) index = index.splice(i + 1, 1);
            }
            var prior = getBit(bit.toElement().prev());
            if (prior && prior.is('editable')) prior.remove();
            focusRelative('next', bit);
        };

        this.onBlur = function(bit, all){
            current = null;
            container.removeClass(options.prefix + '-focus');
            blurtimer = setTimeout(blur, all ? 0 : 200);
        };

        this.setOptions = function(opt){
            options = $.extend(true, options, opt);
        };

        this.getOptions = function(){
            return options;
        };

        this.getContainer = function(){
            return container;
        };

        this.isDuplicate = isDuplicate;
        this.addEvent = addEvent;
        this.removeEvent = removeEvent;
        this.fireEvent = fireEvent;
        this.create = create;
        this.add = add;
        this.getValues = getValues;
        this.plugins = [];
        init();

    };

    $.TextboxListBit = function(type, value, textboxlist, _options){

        var element, bit, prefix, typeprefix, close, hidden, focused = false, name = capitalize(type);
        var options = $.extend(true, type == 'box' ? {
                deleteButton: true
            } : {
                tabIndex: null,
                growing: true,
                growingOptions: {},
                stopEnter: true,
                addOnBlur: false,
                addKeys: [13]
            }, _options);

        this.type = type;
        this.value = value;

        var self = this;
        var init = function(){
            prefix = textboxlist.getOptions().prefix + '-bit';
            typeprefix = prefix + '-' + type;
            bit = $('<li />').addClass(prefix).addClass(typeprefix)
                .data('textboxlist:bit', self)
                .hover(function(){
                    bit.addClass(prefix + '-hover').addClass(typeprefix + '-hover');
                }, function(){
                    bit.removeClass(prefix + '-hover').removeClass(typeprefix + '-hover');
                });
            if (type == 'box'){
                bit.html(chk(self.value[2]) ? self.value[2] : self.value[1]).click(focus);
                if (options.deleteButton){
                    bit.addClass(typeprefix + '-deletable');
                    close = $('<a href="#" class="'+ typeprefix +'-deletebutton" />').click(remove).appendTo(bit);
                }
                bit.children().click(function(e){ e.stopPropagation(); e.preventDefault(); });
            } else {
                element = $('<input maxlength=50 type="text" class="'+ typeprefix +'-input" autocomplete="off" onkeypress="if(event.keyCode==43 || event.keyCode==44 ||event.keyCode==47 ||event.keyCode==126) {return false;}" />').val(self.value ? self.value[1] : '').appendTo(bit);
                if (chk(options.tabIndex)) element.tabIndex = options.tabIndex;
                if (options.growing) new $.GrowingInput(element, options.growingOptions);
                element.focus(function(){ focus(true); }).blur(function(){
                    blur(true);
                    if (options.addOnBlur) toBox();
                });
                if (options.addKeys || options.stopEnter){
                    element.keydown(function(ev){
                        if (!focused) return;
                        var evStop = function(){ ev.stopPropagation(); ev.preventDefault(); };
                        if (options.stopEnter && ev.which === 13) evStop();
                        if ($.inArray(ev.which, splat(options.addKeys)) != -1){
                            evStop();
                            toBox();
                        }
                    });
                }
            }
        };

        var inject = function(el, where){
            switch(where || 'bottom'){
                case 'top': bit.prependTo(el); break;
                case 'bottom': bit.appendTo(el); break;
                case 'before': bit.insertBefore(el); break;
                case 'after': bit.insertAfter(el); break;
            }
            textboxlist.onAdd(self);
            return fireBitEvent('add');
        };

        var focus = function(noReal){
            if (focused) return self;
            show();
            focused = true;
            textboxlist.onFocus(self);
            bit.addClass(prefix + '-focus').addClass(prefix + '-' + type + '-focus');
            fireBitEvent('focus');
            if (type == 'editable' && !noReal) element.focus();
            return self;
        };

        var blur = function(noReal){
            if (!focused) return self;
            focused = false;
            textboxlist.onBlur(self);
            bit.removeClass(prefix + '-focus').removeClass(prefix + '-' + type + '-focus');
            fireBitEvent('blur');
            if (type == 'editable'){
                if (!noReal) element.blur();
                if (hidden && !element.val().length) hide();
            }
            return self;
        };

        var remove = function(){
            blur();
            textboxlist.onRemove(self);
            bit.remove();
            return fireBitEvent('remove');
        };

        var show = function(){
            bit.css('display', 'block');
            return self;
        };

        var hide = function(){
            bit.css('display', 'none');
            hidden = true;
            return self;
        };

        var fireBitEvent = function(type){
            type = capitalize(type);
            textboxlist.fireEvent('bit' + type, self).fireEvent('bit' + name + type, self);
            return self;
        };

        this.is = function(t){
            return type == t;
        };

        this.setValue = function(v){
            if (type == 'editable'){
                element.val(chk(v[0]) ? v[0] : v[1]);
                if (options.growing) element.data('growing').resize();
            } else value = v;
            return self;
        };

        this.getValue = function(){
            return type == 'editable' ? [null, element.val(), null] : value;
        };

        if (type == 'editable'){
            this.getCaret = function(){
                var el = element.get(0);
                if (el.createTextRange){
                    var r = document.selection.createRange().duplicate();
                    r.moveEnd('character', el.value.length);
                    if (r.text === '') return el.value.length;
                    return el.value.lastIndexOf(r.text);
                } else return el.selectionStart;
            };

            this.getCaretEnd = function(){
                var el = element.get(0);
                if (el.createTextRange){
                    var r = document.selection.createRange().duplicate();
                    r.moveStart('character', -el.value.length);
                    return r.text.length;
                } else return el.selectionEnd;
            };

            this.isSelected = function(){
                return focused && (self.getCaret() !== self.getCaretEnd());
            };

            var toBox = function(){
                var value = self.getValue();
                var b = textboxlist.create('box', value);
                if (b){
                    b.inject(bit, 'before');
                    self.setValue([null, '', null]);
                    return b;
                }
                return null;
            };

            this.toBox = toBox;
        }

        this.toElement = function(){
            return bit;
        };

        this.focus = focus;
        this.blur = blur;
        this.remove = remove;
        this.inject = inject;
        this.show = show;
        this.hide = hide;
        this.fireBitEvent = fireBitEvent;
        init();
    };

    var chk = function(v){ return !!(v || v === 0); };
    var splat = function(a){ return $.isArray(a) ? a : [a]; };
    var camelCase = function(str){ return str.replace(/-\D/g, function(match){ return match.charAt(1).toUpperCase(); }); };
    var capitalize = function(str){ return str.replace(/\b[a-z]/g, function(A){ return A.toUpperCase(); }); };

    $.fn.extend({

        textboxlist: function(options){
            return this.each(function(){
                new $.TextboxList(this, options);
            });
        }

    });

})(jQuery);


/*
 Script: TextboxList.Autocomplete.js
 TextboxList Autocomplete plugin

 Authors:
 Guillermo Rauch

 Note:
 TextboxList is not priceless for commercial use. See <http://devthought.com/projects/jquery/textboxlist/>
 Purchase to remove this message.
 */

(function(){

    $.TextboxList.Autocomplete = function(textboxlist, _options){

        var index, prefix, method, container, list, values = [], searchValues = [], results = [], placeholder = false, current, currentInput, hidetimer, doAdd, currentSearch, currentRequest;
        var options = $.extend(true, {
            minLength: 1,
            maxResults: 500,
            insensitive: true,
            highlight: true,
            highlightSelector: null,
            mouseInteraction: true,
            onlyFromValues: false,
            queryRemote: false,
            remote: {
                url: '',
                param: 'search',
                extraParams: {},
                loadPlaceholder: 'Please wait...'
            },
            method: 'standard',
            placeholder: 'Type to receive suggestions'
        }, _options);

        var init = function(){
            textboxlist.addEvent('bitEditableAdd', setupBit)
                .addEvent('bitEditableFocus', search)
                .addEvent('bitEditableBlur', hide)
                .setOptions({bitsOptions: {editable: {addKeys: false, stopEnter: false}}});
            if ($.browser.msie) textboxlist.setOptions({bitsOptions: {editable: {addOnBlur: false}}});
            prefix = textboxlist.getOptions().prefix + '-autocomplete';
            method = $.TextboxList.Autocomplete.Methods[options.method];
            container = $('<div class="'+ prefix +'" />').width('100%').appendTo(textboxlist.getContainer()); //textboxlist.getContainer().width()
            //container = $('<div class="'+ prefix +'" />').width(textboxlist.getContainer().width().appendTo(textboxlist.getContainer()));
            if (chk(options.placeholder)) placeholder = $('<div class="'+ prefix +'-placeholder" />').html(options.placeholder).appendTo(container);
            list = $('<ul class="'+ prefix +'-results" />').appendTo(container).click(function(ev){
                ev.stopPropagation(); ev.preventDefault();
            });
        };

        var setupBit = function(bit){
            bit.toElement().keydown(navigate).keyup(function(){ search(); });
        };

        var search = function(bit){
            if (bit) currentInput = bit;
            if (!options.queryRemote && !values.length) return;
            var search = $.trim(currentInput.getValue()[1]);
            if (search.length < options.minLength) showPlaceholder();
            if (search == currentSearch) return;
            currentSearch = search;
            list.css('display', 'none');
            if (search.length < options.minLength) return;
            if (options.queryRemote){
                if (searchValues[search]){
                    values = searchValues[search];
                } else {
                    var data = options.remote.extraParams;
                    data[options.remote.param] = search;
                    if (currentRequest) currentRequest.abort();
                    currentRequest = $.ajax({
                        url: options.remote.url,
                        data: data,
                        type: 'POST',
                        dataType: 'json',
                        success: function(r){
                            searchValues[search] = r;
                            values = r;
                            showResults(search);
                        }
                    });
                }
            }
            showResults(search);
        };

        var showResults = function(search){
            var results = method.filter(values, search, options.insensitive, options.maxResults);
            if (textboxlist.getOptions().unique){
                results = $.grep(results, function(v){ return textboxlist.isDuplicate(v) == -1; });
            }
            hidePlaceholder();
            if (!results.length) return;
            blur();
            list.empty().css('display', 'block');
            $.each(results, function(i, r){ addResult(r, search); });
            if (options.onlyFromValues) focusFirst();
            results = results;
        };

        var addResult = function(r, searched){
            var element = $('<li class="'+ prefix +'-result" />').html(r[3] ? r[3] : r[1]).data('textboxlist:auto:value', r);
            element.appendTo(list);
            if (options.highlight) $(options.highlightSelector ? element.find(options.highlightSelector) : element).each(function(){
                if ($(this).html()) method.highlight($(this), searched, options.insensitive, prefix + '-highlight');
            });
            if (options.mouseInteraction){
                element.css('cursor', 'pointer').hover(function(){ focus(element); }).mousedown(function(ev){
                    ev.stopPropagation();
                    ev.preventDefault();
                    clearTimeout(hidetimer);
                    doAdd = true;
                }).mouseup(function(){
                    if (doAdd){
                        addCurrent();
                        currentInput.focus();
                        search();
                        doAdd = false;
                    }
                });
                if (!options.onlyFromValues) element.mouseleave(function(){ if (current && (current.get(0) == element.get(0))) blur(); });
            }
        };

        var hide = function(){
            hidetimer = setTimeout(function(){
                hidePlaceholder();
                list.css('display', 'none');
                currentSearch = null;
            }, $.browser.msie ? 150 : 0);
        };

        var showPlaceholder = function(){
            if (placeholder) placeholder.css('display', 'block');
        };

        var hidePlaceholder = function(){
            if (placeholder) placeholder.css('display', 'none');
        };

        var focus = function(element){
            if (!element || !element.length) return;
            blur();
            current = element.addClass(prefix + '-result-focus');
        };

        var blur = function(){
            if (current && current.length){
                current.removeClass(prefix + '-result-focus');
                current = null;
            }
        };

        var focusFirst = function(){
            return focus(list.find(':first'));
        };

        var focusRelative = function(dir){
            if (!current || !current.length) return self;
            return focus(current[dir]());
        };

        var addCurrent = function(){
            var value = current.data('textboxlist:auto:value');
            var b = textboxlist.create('box', value.slice(0, 3));
            if (b){
                b.autoValue = value;
                if ($.isArray(index)) index.push(value);
                currentInput.setValue([null, '', null]);
                b.inject(currentInput.toElement(), 'before');
            }
            blur();
            return self;
        };

        var navigate = function(ev){
            var evStop = function(){ ev.stopPropagation(); ev.preventDefault(); };
            switch (ev.which){
                case 38:
                    evStop();
                    (!options.onlyFromValues && current && current.get(0) === list.find(':first').get(0)) ? blur() : focusRelative('prev');
                    break;
                case 40:
                    evStop();
                    (current && current.length) ? focusRelative('next') : focusFirst();
                    break;
                case 13:
                    evStop();
                    if (current && current.length) addCurrent();
                    else if (!options.onlyFromValues){
                        var value = currentInput.getValue();
                        var b = textboxlist.create('box', value);
                        if (b){
                            b.inject(currentInput.toElement(), 'before');
                            currentInput.setValue([null, '', null]);
                        }
                    }
            }
        };

        this.setValues = function(v){
            values = v;
        };

        init();
    };

    $.TextboxList.Autocomplete.Methods = {

        standard: {
            filter: function(values, search, insensitive, max){
                var newvals = [], regexp = new RegExp('\\b' + escapeRegExp(search), insensitive ? 'i' : '');
                for (var i = 0; i < values.length; i++){
                    if (newvals.length === max) break;
                    if (regexp.test(values[i][1])) newvals.push(values[i]);
                }
                return newvals;
            },

            highlight: function(element, search, insensitive, klass){
                var regex = new RegExp('(<[^>]*>)|(\\b'+ escapeRegExp(search) +')', insensitive ? 'ig' : 'g');
                return element.html(element.html().replace(regex, function(a, b, c){
                    return (a.charAt(0) == '<') ? a : '<strong class="'+ klass +'">' + c + '</strong>';
                }));
            }
        }

    };

    var chk = function(v){ return !!(v || v === 0); };
    var escapeRegExp = function(str){ return str.replace(/([-.*+?^${}()|[\]\/\\])/g, "\\$1"); };

})(jQuery);


/* ==========================================================
 * bootstrap-formhelpers-selectbox.js
 * https://github.com/vlamanna/BootstrapFormHelpers
 * ==========================================================
 * Copyright 2012 Vincent Lamanna
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================== */


!function ($) {

    "use strict"; // jshint ;_;


    /* SELECTBOX CLASS DEFINITION
     * ========================= */

    var toggle = '[data-toggle=selectbox]'
        , SelectBox = function (element) {
    }

    SelectBox.prototype = {

        constructor: SelectBox

        , toggle: function (e) {


            // console.log('clicks');
            /*  var Classname=$(this).next('div').attr('class');
             var i1= ("document", $(document).height());
             Classname=$(this).next('div').attr('class');
             var totaldropli= $(this).next('div').find('ul > li ').length;

             var totalHeight=0;
             $(this).next('div').find('ul > li ').each(function() {
             totalHeight += $(this).outerHeight(true); // to include margins
             });
             $('.'+Classname).removeClass('classtopdropdown'); $('.'+Classname).removeAttr('style');
             console.log(i1);
             if(i1<=2000){

             if(totalHeight>60) { $('.'+Classname).addClass('classtopdropdown'); }

             /*else if(totalHeight<=60 && parseInt(totaldropli) <=5 ){
             var top=(totaldropli*40)+44; $('.'+Classname).css('top',-top); } */
            /*  }
             else{$('.'+Classname).removeClass('classtopdropdown'); $('.'+Classname).removeAttr('style'); }*/

            var $this = $(this)
                , $parent
                , isActive

            if ($this.is('.disabled, :disabled')) return

            $parent = getParent($this)

            isActive = $parent.hasClass('open')

            clearMenus()

            if (!isActive) {
                $parent.toggleClass('open')

                $parent.find('[role=options] > li > [data-option="' + $this.find('.selectbox-option').data('option') + '"]').focus()
            }


            try{
                var pagemaintop = $(document).height();
                var offs=$(this).next('div').offset();
                var Classname=$(this).next('div').attr('class');
                var hei =$(this).offset();
                var totaltop=hei.top+offs.top;
                var dropdownheight=$(this).height()+$(this).next('div').height();
                var bottom = $(this).next('div').closest('section[class!="black-overlay"]').position().top+$(this).next('div').closest('section[class!="black-overlay"]').outerHeight(true)

                var currsection=$(this).next('div').closest('section[class!="black-overlay"]').height();

                var totaldropli= $(this).next('div').find('ul > li ').length;
                var calculatedropdown=offs.top+$(this).next('div').height();
                //console.log(bottom);
                if(totaldropli>2 && parseInt(bottom)<=calculatedropdown)
                {

                    $(this).next('div').css('top',-dropdownheight);
                }
                else
                {
                    $(this).removeAttr('style');
                }

            }
            catch(e)
            {

            }
            return false
        }

        , filter: function(e) {
            var $this
                , $parent
                , $items

            $this = $(this)

            $parent = $this.closest('.selectbox')

            $items = $('[role=options] li', $parent)

            $items.css('display','none')

            //$items.filter(function() { return ($(this).text().toUpperCase().indexOf($this.val().toUpperCase()) != -1) }).show()
            $items.filter(function() { return ($this.val().toUpperCase() === $(this).text().toUpperCase().substr(0, $this.val().toUpperCase().length)) }).css('display','block')
        }

        , keydown: function (e) {
            var $this
                , $items
                , $active
                , $parent
                , isActive
                , index

            if (!/(38|40|27)/.test(e.keyCode) && !/[A-z]/.test(String.fromCharCode(e.which))) return

            $this = $(this)

            e.preventDefault()
            e.stopPropagation()

            if ($this.is('.disabled, :disabled')) return

            $parent = $this.closest('.selectbox')

            isActive = $parent.hasClass('open')

            if (!isActive || (isActive && e.keyCode == 27)) return $this.click()

            $items = $('[role=options] li a', $parent).filter(':visible')

            if (!$items.length) return

            $('body').off('mouseenter.selectbox.data-api', '[role=options] > li > a', SelectBox.prototype.mouseenter)

            index = $items.index($items.filter(':focus'))

            if (e.keyCode == 38 && index > 0) index--                                        // up
            if (e.keyCode == 40 && index < $items.length - 1) index++                        // down
            if (/[A-z]/.test(String.fromCharCode(e.which))) {
                var $subItems = $items.filter(function() { return ($(this).text().charAt(0).toUpperCase() == String.fromCharCode(e.which)) })
                var selectedIndex = $subItems.index($subItems.filter(':focus'))
                if (!~selectedIndex) index = $items.index($subItems)
                else if (selectedIndex >= $subItems.length - 1) index = $items.index($subItems)
                else index++
            }
            if (!~index) index = 0

            $items
                .eq(index)
                .focus()

            $('body').on('mouseenter.selectbox.data-api', '[role=options] > li > a', SelectBox.prototype.mouseenter)
        }

        , mouseenter: function (e) {
            var $this

            $this = $(this)

            if ($this.is('.disabled, :disabled')) return

            $this.focus()
        }

        , select: function (e) {
            var $this
                , $parent
                , $toggle
                , $input

            $this = $(this)

            e.preventDefault()
            e.stopPropagation()

            if ($this.is('.disabled, :disabled')) return

            $parent = $this.closest('.selectbox')
            $toggle = $parent.find('.selectbox-option')
            $input = $parent.find('input[type="hidden"]')

            $toggle.data('option', $this.data('option'))
            $toggle.html($this.html())

            $input.val($this.data('option'))
            $input.change()

            clearMenus()
        }

    }

    function clearMenus() {
        getParent($(toggle))
            .removeClass('open')
    }

    function getParent($this) {
        var selector = $this.attr('data-target')
            , $parent

        if (!selector) {
            selector = $this.attr('href')
            selector = selector && /#/.test(selector) && selector.replace(/.*(?=#[^\s]*$)/, '') //strip for ie7
        }

        $parent = $(selector)
        $parent.length || ($parent = $this.parent())

        return $parent
    }


    /* SELECTBOX PLUGIN DEFINITION
     * ========================== */

    $.fn.selectbox = function (option) {
        return this.each(function () {
            var $this = $(this)
                , data = $this.data('selectbox')
            this.type = 'selectbox';
            if (!data) $this.data('selectbox', (data = new SelectBox(this)))
            if (typeof option == 'string') data[option].call($this)
        })
    }

    $.fn.selectbox.Constructor = SelectBox

    $.valHooks.selectbox = {
        get: function(el) {
            return $(el).find('input[type="hidden"]').val();
        },
        set: function(el, val) {
            var $el = $(el);
            $el.find('input[type="hidden"]').val(val);
            $el.find('.selectbox-option').text(val);
        }
    }

    /* APPLY TO STANDARD SELECTBOX ELEMENTS
     * =================================== */

    $(function () {
        $('html')
            .on('click.selectbox.data-api', clearMenus)
        $('body')
            .on('click.selectbox.data-api touchstart.selectbox.data-api'  , toggle, SelectBox.prototype.toggle)
            .on('keydown.selectbox.data-api', toggle + ', [role=options]' , SelectBox.prototype.keydown)
            .on('mouseenter.selectbox.data-api', '[role=options] > li > a', SelectBox.prototype.mouseenter)
            .on('click.selectbox.data-api', '[role=options] > li > a', SelectBox.prototype.select)
            .on('click.selectbox.data-api', '.selectbox-filter', function (e) { return false })
            .on('propertychange.selectbox.data-api change.selectbox.data-api input.selectbox.data-api paste.selectbox.data-api', '.selectbox-filter', SelectBox.prototype.filter)
    })

}(window.jQuery);

// tipsy, facebook style tooltips for jquery
// version 1.0.0a
// (c) 2008-2010 jason frame [jason@onehackoranother.com]
// released under the MIT license

(function($) {

    function maybeCall(thing, ctx) {
        return (typeof thing == 'function') ? (thing.call(ctx)) : thing;
    };

    function isElementInDOM(ele) {
        while (ele = ele.parentNode) {
            if (ele == document) return true;
        }
        return false;
    };

    function Tipsy(element, options) {
        this.$element = $(element);
        this.options = options;
        this.enabled = true;
        this.fixTitle();

    };

    Tipsy.prototype = {
        show: function() {
            var title = this.getTitle();
            if (title && this.enabled) {
                var $tip = this.tip();

                $tip.find('.tipsy-inner')[this.options.html ? 'html' : 'text'](title);
                $tip[0].className = 'tipsy'; // reset classname in case of dynamic gravity
                $tip.remove().css({top: 0, left: 0, visibility: 'hidden', display: 'block'}).prependTo(document.body);

                var pos = $.extend({}, this.$element.offset(), {
                    width: this.$element[0].offsetWidth,
                    height: this.$element[0].offsetHeight
                });

                var actualWidth = $tip[0].offsetWidth,
                    actualHeight = $tip[0].offsetHeight,
                    gravity = maybeCall(this.options.gravity, this.$element[0]);

                var tp;
                switch (gravity.charAt(0)) {
                    case 'n':
                        tp = {top: pos.top + pos.height + this.options.offset, left: pos.left + pos.width / 2 - actualWidth / 2};
                        break;
                    case 's':
                        tp = {top: pos.top - actualHeight - this.options.offset, left: pos.left + pos.width / 2 - actualWidth / 2};
                        break;
                    case 'e':
                        tp = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left - actualWidth - this.options.offset};
                        break;
                    case 'w':
                        tp = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left + pos.width + this.options.offset};
                        break;
                }

                if (gravity.length == 2) {
                    if (gravity.charAt(1) == 'w') {
                        tp.left = pos.left + pos.width / 2 - 15;
                    } else {
                        tp.left = pos.left + pos.width / 2 - actualWidth + 15;
                    }
                }

                $tip.css(tp).addClass('tipsy-' + gravity);
                $tip.find('.tipsy-arrow')[0].className = 'tipsy-arrow tipsy-arrow-' + gravity.charAt(0);
                if (this.options.className) {
                    $tip.addClass(maybeCall(this.options.className, this.$element[0]));
                }

                if (this.options.fade) {
                    $tip.stop().css({opacity: 0, display: 'block', visibility: 'visible'}).animate({opacity: this.options.opacity});
                } else {
                    $tip.css({visibility: 'visible', opacity: this.options.opacity});
                }
            }
        },

        hide: function() {
            if (this.options.fade) {
                this.tip().stop().fadeOut(function() { $(this).remove(); });
            } else {
                this.tip().remove();
            }
        },

        fixTitle: function() {
            var $e = this.$element;
            if ($e.attr('title') || typeof($e.attr('original-title')) != 'string') {
                $e.attr('original-title', $e.attr('title') || '').removeAttr('title');
            }
        },

        getTitle: function() {
            var title, $e = this.$element, o = this.options;
            this.fixTitle();
            var title, o = this.options;
            if (typeof o.title == 'string') {
                title = $e.attr(o.title == 'title' ? 'original-title' : o.title);
            } else if (typeof o.title == 'function') {
                title = o.title.call($e[0]);
            }
            title = ('' + title).replace(/(^\s*|\s*$)/, "");
            return title || o.fallback;
        },

        tip: function() {
            if (!this.$tip) {
                this.$tip = $('<div class="tipsy"></div>').html('<div class="tipsy-arrow"></div><div class="tipsy-inner"></div>');
                this.$tip.data('tipsy-pointee', this.$element[0]);
            }
            return this.$tip;
        },

        validate: function() {
            if (!this.$element[0].parentNode) {
                this.hide();
                this.$element = null;
                this.options = null;
            }
        },

        enable: function() { this.enabled = true; },
        disable: function() { this.enabled = false; },
        toggleEnabled: function() { this.enabled = !this.enabled; }
    };

    $.fn.tipsy = function(options) {

        if (options === true) {
            return this.data('tipsy');
        } else if (typeof options == 'string') {
            var tipsy = this.data('tipsy');
            if (tipsy) tipsy[options]();
            return this;
        }

        options = $.extend({}, $.fn.tipsy.defaults, options);

        function get(ele) {
            var tipsy = $.data(ele, 'tipsy');
            if (!tipsy) {
                tipsy = new Tipsy(ele, $.fn.tipsy.elementOptions(ele, options));
                $.data(ele, 'tipsy', tipsy);
            }
            return tipsy;
        }

        function enter() {
            var tipsy = get(this);
            tipsy.hoverState = 'in';
            if (options.delayIn == 0) {
                tipsy.show();
            } else {
                tipsy.fixTitle();
                setTimeout(function() { if (tipsy.hoverState == 'in') tipsy.show(); }, options.delayIn);
            }
        };

        function leave() {
            var tipsy = get(this);
            tipsy.hoverState = 'out';
            if (options.delayOut == 0) {
                tipsy.hide();
            } else {
                setTimeout(function() { if (tipsy.hoverState == 'out') tipsy.hide(); }, options.delayOut);
            }
        };

        if (!options.live) this.each(function() { get(this); });

        if (options.trigger != 'manual') {
            var binder   = options.live ? 'live' : 'bind',
                eventIn  = options.trigger == 'hover' ? 'mouseenter' : 'focus',
                eventOut = options.trigger == 'hover' ? 'mouseleave' : 'blur';
            this[binder](eventIn, enter)[binder](eventOut, leave);
        }

        return this;

    };

    $.fn.tipsy.defaults = {
        className: null,
        delayIn: 0,
        delayOut: 0,
        fade: false,
        fallback: '',
        gravity: 'n',
        html: true,
        live: false,
        offset: 0,
        opacity: 0.8,
        title: 'title',
        trigger: 'hover'
    };

    $.fn.tipsy.revalidate = function() {
        $('.tipsy').each(function() {
            var pointee = $.data(this, 'tipsy-pointee');
            if (!pointee || !isElementInDOM(pointee)) {
                $(this).remove();
            }
        });
    };

    // Overwrite this method to provide options on a per-element basis.
    // For example, you could store the gravity in a 'tipsy-gravity' attribute:
    // return $.extend({}, options, {gravity: $(ele).attr('tipsy-gravity') || 'n' });
    // (remember - do not modify 'options' in place!)
    $.fn.tipsy.elementOptions = function(ele, options) {
        return $.metadata ? $.extend({}, options, $(ele).metadata()) : options;
    };

    $.fn.tipsy.autoNS = function() {
        return $(this).offset().top > ($(document).scrollTop() + $(window).height() / 2) ? 's' : 'n';
    };

    $.fn.tipsy.autoWE = function() {
        return $(this).offset().left > ($(document).scrollLeft() + $(window).width() / 2) ? 'e' : 'w';
    };

    /**
     * yields a closure of the supplied parameters, producing a function that takes
     * no arguments and is suitable for use as an autogravity function like so:
     *
     * @param margin (int) - distance from the viewable region edge that an
     *        element should be before setting its tooltip's gravity to be away
     *        from that edge.
     * @param prefer (string, e.g. 'n', 'sw', 'w') - the direction to prefer
     *        if there are no viewable region edges effecting the tooltip's
     *        gravity. It will try to vary from this minimally, for example,
     *        if 'sw' is preferred and an element is near the right viewable
     *        region edge, but not the top edge, it will set the gravity for
     *        that element's tooltip to be 'se', preserving the southern
     *        component.
     */
    $.fn.tipsy.autoBounds = function(margin, prefer) {
        return function() {
            var dir = {ns: prefer[0], ew: (prefer.length > 1 ? prefer[1] : false)},
                boundTop = $(document).scrollTop() + margin,
                boundLeft = $(document).scrollLeft() + margin,
                $this = $(this);

            if ($this.offset().top < boundTop) dir.ns = 'n';
            if ($this.offset().left < boundLeft) dir.ew = 'w';
            if ($(window).width() + $(document).scrollLeft() - $this.offset().left < margin) dir.ew = 'e';
            if ($(window).height() + $(document).scrollTop() - $this.offset().top < margin) dir.ns = 's';

            return dir.ns + (dir.ew ? dir.ew : '');
        }
    };

})(jQuery);


/*! Copyright (c) 2011 Piotr Rochala (http://rocha.la)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Version: 1.3.0
 *
 */
(function($) {

    jQuery.fn.extend({
        slimScroll: function(options) {

            var defaults = {

                // width in pixels of the visible scroll area
                width : 'auto',

                // height in pixels of the visible scroll area
                height : '250px',

                // width in pixels of the scrollbar and rail
                size : '7px',

                // scrollbar color, accepts any hex/color value
                color: '#000',

                // scrollbar position - left/right
                position : 'right',

                // distance in pixels between the side edge and the scrollbar
                distance : '1px',

                // default scroll position on load - top / bottom / $('selector')
                start : 'top',

                // sets scrollbar opacity
                opacity : .4,

                // enables always-on mode for the scrollbar
                alwaysVisible : false,

                // check if we should hide the scrollbar when user is hovering over
                disableFadeOut : false,

                // sets visibility of the rail
                railVisible : false,

                // sets rail color
                railColor : '#333',

                // sets rail opacity
                railOpacity : .2,

                // whether  we should use jQuery UI Draggable to enable bar dragging
                railDraggable : true,

                // defautlt CSS class of the slimscroll rail
                railClass : 'slimScrollRail',

                // defautlt CSS class of the slimscroll bar
                barClass : 'slimScrollBar',

                // defautlt CSS class of the slimscroll wrapper
                wrapperClass : 'slimScrollDiv',

                // check if mousewheel should scroll the window if we reach top/bottom
                allowPageScroll : false,

                // scroll amount applied to each mouse wheel step
                wheelStep : 20,

                // scroll amount applied when user is using gestures
                touchScrollStep : 200,

                // sets border radius
                borderRadius: '7px',

                // sets border radius of the rail
                railBorderRadius : '7px'
            };

            var o = $.extend(defaults, options);

            // do it for every element that matches selector
            this.each(function(){

                var isOverPanel, isOverBar, isDragg, queueHide, touchDif,
                    barHeight, percentScroll, lastScroll,
                    divS = '<div></div>',
                    minBarHeight = 30,
                    releaseScroll = false;

                // used in event handlers and for better minification
                var me = $(this);

                // ensure we are not binding it again
                if (me.parent().hasClass(o.wrapperClass))
                {
                    // start from last bar position
                    var offset = me.scrollTop();

                    // find bar and rail
                    bar = me.parent().find('.' + o.barClass);
                    rail = me.parent().find('.' + o.railClass);

                    getBarHeight();

                    // check if we should scroll existing instance
                    if ($.isPlainObject(options))
                    {
                        // Pass height: auto to an existing slimscroll object to force a resize after contents have changed
                        if ( 'height' in options && options.height == 'auto' ) {
                            me.parent().css('height', 'auto');
                            me.css('height', 'auto');
                            var height = me.parent().parent().height();
                            me.parent().css('height', height);
                            me.css('height', height);
                        }

                        if ('scrollTo' in options)
                        {
                            // jump to a static point
                            offset = parseInt(o.scrollTo);
                        }
                        else if ('scrollBy' in options)
                        {
                            // jump by value pixels
                            offset += parseInt(o.scrollBy);
                        }
                        else if ('destroy' in options)
                        {
                            // remove slimscroll elements
                            bar.remove();
                            rail.remove();
                            me.unwrap();
                            return;
                        }

                        // scroll content by the given offset
                        scrollContent(offset, false, true);
                    }

                    return;
                }

                // optionally set height to the parent's height
                o.height = (o.height == 'auto') ? me.parent().height() : o.height;

                // wrap content
                var wrapper = $(divS)
                    .addClass(o.wrapperClass)
                    .css({
                        position: 'relative',
                        overflow: 'hidden',
                        width: o.width,
                        height: o.height
                    });

                // update style for the div
                me.css({
                    overflow: 'hidden',
                    width: o.width,
                    height: o.height
                });

                // create scrollbar rail
                var rail = $(divS)
                    .addClass(o.railClass)
                    .css({
                        width: o.size,
                        height: '100%',
                        position: 'absolute',
                        top: 0,
                        display: (o.alwaysVisible && o.railVisible) ? 'block' : 'none',
                        'border-radius': o.railBorderRadius,
                        background: o.railColor,
                        opacity: o.railOpacity,
                        zIndex: 90
                    });

                // create scrollbar
                var bar = $(divS)
                    .addClass(o.barClass)
                    .css({
                        background: o.color,
                        width: o.size,
                        position: 'absolute',
                        top: 0,
                        opacity: o.opacity,
                        display: o.alwaysVisible ? 'block' : 'none',
                        'border-radius' : o.borderRadius,
                        BorderRadius: o.borderRadius,
                        MozBorderRadius: o.borderRadius,
                        WebkitBorderRadius: o.borderRadius,
                        zIndex: 99
                    });

                // set position
                var posCss = (o.position == 'right') ? { right: o.distance } : { left: o.distance };
                rail.css(posCss);
                bar.css(posCss);

                // wrap it
                me.wrap(wrapper);

                // append to parent div
                me.parent().append(bar);
                me.parent().append(rail);

                // make it draggable and no longer dependent on the jqueryUI
                if (o.railDraggable){
                    bar.bind("mousedown", function(e) {
                        var $doc = $(document);
                        isDragg = true;
                        t = parseFloat(bar.css('top'));
                        pageY = e.pageY;

                        $doc.bind("mousemove.slimscroll", function(e){
                            currTop = t + e.pageY - pageY;
                            bar.css('top', currTop);
                            scrollContent(0, bar.position().top, false);// scroll content
                        });

                        $doc.bind("mouseup.slimscroll", function(e) {
                            isDragg = false;hideBar();
                            $doc.unbind('.slimscroll');
                        });
                        return false;
                    }).bind("selectstart.slimscroll", function(e){
                        e.stopPropagation();
                        e.preventDefault();
                        return false;
                    });
                }

                // on rail over
                rail.hover(function(){
                    showBar();
                }, function(){
                    hideBar();
                });

                // on bar over
                bar.hover(function(){
                    isOverBar = true;
                }, function(){
                    isOverBar = false;
                });

                // show on parent mouseover
                me.hover(function(){
                    isOverPanel = true;
                    showBar();
                    hideBar();
                }, function(){
                    isOverPanel = false;
                    hideBar();
                });

                // support for mobile
                me.bind('touchstart', function(e,b){
                    if (e.originalEvent.touches.length)
                    {
                        // record where touch started
                        touchDif = e.originalEvent.touches[0].pageY;
                    }
                });

                me.bind('touchmove', function(e){
                    // prevent scrolling the page if necessary
                    if(!releaseScroll)
                    {
                        e.originalEvent.preventDefault();
                    }
                    if (e.originalEvent.touches.length)
                    {
                        // see how far user swiped
                        var diff = (touchDif - e.originalEvent.touches[0].pageY) / o.touchScrollStep;
                        // scroll content
                        scrollContent(diff, true);
                        touchDif = e.originalEvent.touches[0].pageY;
                    }
                });

                // set up initial height
                getBarHeight();

                // check start position
                if (o.start === 'bottom')
                {
                    // scroll content to bottom
                    bar.css({ top: me.outerHeight() - bar.outerHeight() });
                    scrollContent(0, true);
                }
                else if (o.start !== 'top')
                {
                    // assume jQuery selector
                    scrollContent($(o.start).position().top, null, true);

                    // make sure bar stays hidden
                    if (!o.alwaysVisible) { bar.hide(); }
                }

                // attach scroll events
                attachWheel();

                function _onWheel(e)
                {
                    // use mouse wheel only when mouse is over
                    if (!isOverPanel) { return; }

                    var e = e || window.event;

                    var delta = 0;
                    if (e.wheelDelta) { delta = -e.wheelDelta/120; }
                    if (e.detail) { delta = e.detail / 3; }

                    var target = e.target || e.srcTarget || e.srcElement;
                    if ($(target).closest('.' + o.wrapperClass).is(me.parent())) {
                        // scroll content
                        scrollContent(delta, true);
                    }

                    // stop window scroll
                    if (e.preventDefault && !releaseScroll) { e.preventDefault(); }
                    if (!releaseScroll) { e.returnValue = false; }
                }

                function scrollContent(y, isWheel, isJump)
                {
                    releaseScroll = false;
                    var delta = y;
                    var maxTop = me.outerHeight() - bar.outerHeight();

                    if (isWheel)
                    {
                        // move bar with mouse wheel
                        delta = parseInt(bar.css('top')) + y * parseInt(o.wheelStep) / 100 * bar.outerHeight();

                        // move bar, make sure it doesn't go out
                        delta = Math.min(Math.max(delta, 0), maxTop);

                        // if scrolling down, make sure a fractional change to the
                        // scroll position isn't rounded away when the scrollbar's CSS is set
                        // this flooring of delta would happened automatically when
                        // bar.css is set below, but we floor here for clarity
                        delta = (y > 0) ? Math.ceil(delta) : Math.floor(delta);

                        // scroll the scrollbar
                        bar.css({ top: delta + 'px' });
                    }

                    // calculate actual scroll amount
                    percentScroll = parseInt(bar.css('top')) / (me.outerHeight() - bar.outerHeight());
                    delta = percentScroll * (me[0].scrollHeight - me.outerHeight());

                    if (isJump)
                    {
                        delta = y;
                        var offsetTop = delta / me[0].scrollHeight * me.outerHeight();
                        offsetTop = Math.min(Math.max(offsetTop, 0), maxTop);
                        bar.css({ top: offsetTop + 'px' });
                    }

                    // scroll content
                    me.scrollTop(delta);

                    // fire scrolling event
                    me.trigger('slimscrolling', ~~delta);

                    // ensure bar is visible
                    showBar();

                    // trigger hide when scroll is stopped
                    hideBar();
                }

                function attachWheel()
                {
                    if (window.addEventListener)
                    {
                        this.addEventListener('DOMMouseScroll', _onWheel, false );
                        this.addEventListener('mousewheel', _onWheel, false );
                        this.addEventListener('MozMousePixelScroll', _onWheel, false );
                    }
                    else
                    {
                        document.attachEvent("onmousewheel", _onWheel)
                    }
                }

                function getBarHeight()
                {
                    // calculate scrollbar height and make sure it is not too small
                    barHeight = Math.max((me.outerHeight() / me[0].scrollHeight) * me.outerHeight(), minBarHeight);
                    bar.css({ height: barHeight + 'px' });

                    // hide scrollbar if content is not long enough
                    var display = barHeight == me.outerHeight() ? 'none' : 'block';
                    bar.css({ display: display });
                }

                function showBar()
                {
                    // recalculate bar height
                    getBarHeight();
                    clearTimeout(queueHide);

                    // when bar reached top or bottom
                    if (percentScroll == ~~percentScroll)
                    {
                        //release wheel
                        releaseScroll = o.allowPageScroll;

                        // publish approporiate event
                        if (lastScroll != percentScroll)
                        {
                            var msg = (~~percentScroll == 0) ? 'top' : 'bottom';
                            me.trigger('slimscroll', msg);
                        }
                    }
                    else
                    {
                        releaseScroll = false;
                    }
                    lastScroll = percentScroll;

                    // show only when required
                    if(barHeight >= me.outerHeight()) {
                        //allow window scroll
                        releaseScroll = true;
                        return;
                    }
                    bar.stop(true,true).fadeIn('fast');
                    if (o.railVisible) { rail.stop(true,true).fadeIn('fast'); }
                }

                function hideBar()
                {
                    // only hide when options allow it
                    if (!o.alwaysVisible)
                    {
                        queueHide = setTimeout(function(){
                            if (!(o.disableFadeOut && isOverPanel) && !isOverBar && !isDragg)
                            {
                                bar.fadeOut('slow');
                                rail.fadeOut('slow');
                            }
                        }, 1000);
                    }
                }

            });

            // maintain chainability
            return this;
        }
    });

    jQuery.fn.extend({
        slimscroll: jQuery.fn.slimScroll
    });

})(jQuery);


(function(b){b.Zebra_Dialog=function(g,l){var s={animation_speed:400,auto_close:!1,buttons:!0,custom_class:!1,keyboard:!1,max_height:0,message:"",modal:!0,overlay_close:!1,overlay_opacity:0.8,position:"center",title:"",type:"information",vcenter_short_message:!0,width:0,onClose:null},a=this;a.settings={};options={};"string"==typeof g&&(options.message=g);if("object"==typeof g||"object"==typeof l)options=b.extend(options,"object"==typeof g?g:l);a.init=function(){a.settings=b.extend({},s,options);a.isIE6= "explorer"==j.name&&6==j.version||!1;a.settings.modal&&(a.overlay=jQuery("<div>",{"class":"ZebraDialogOverlay"}).css({position:a.isIE6?"absolute":"fixed",left:0,top:0,opacity:a.settings.overlay_opacity,"z-index":10000}),a.settings.overlay_close&&a.overlay.bind("click",function(){a.close()}),a.overlay.appendTo("body"));a.dialog=jQuery("<div>",{"class":"ZebraDialog"+(a.settings.custom_class?" "+a.settings.custom_class:"")}).css({position:a.isIE6?"absolute":"fixed",left:0,top:0,"z-index":10000,visibility:"hidden"}); !a.settings.buttons&&a.settings.auto_close&&a.dialog.attr("id","ZebraDialog_"+Math.floor(9999999*Math.random()));var c=parseInt(a.settings.width);!isNaN(c)&&(c==a.settings.width&&c.toString()==a.settings.width.toString()&&0<c)&&a.dialog.css({width:a.settings.width});a.settings.title&&jQuery("<h3>",{"class":"ZebraDialog_Title"}).html(a.settings.title).appendTo(a.dialog);c=jQuery("<div>",{"class":"ZebraDialog_BodyOuter"+(!a.settings.title?" ZebraDialog_NoTitle":"")+(!m()?" ZebraDialog_NoButtons":"")}).appendTo(a.dialog); a.message=jQuery("<div>",{"class":"ZebraDialog_Body"+(""!=n()?" ZebraDialog_Icon ZebraDialog_"+n():"")});0<a.settings.max_height&&(a.message.css("max-height",a.settings.max_height),a.isIE6&&a.message.attr("style","height: expression(this.scrollHeight > "+a.settings.max_height+' ? "'+a.settings.max_height+'px" : "85px")'));a.settings.vcenter_short_message?jQuery("<div>").html(a.settings.message).appendTo(a.message):a.message.html(a.settings.message);a.message.appendTo(c);if(c=m()){var d=jQuery("<div>", {"class":"ZebraDialog_Buttons"}).appendTo(a.dialog);b.each(c,function(c,e){var h=jQuery("<a>",{href:"javascript:void(0)","class":"ZebraDialog_Button"+c});b.isPlainObject(e)?h.html(e.caption):h.html(e);h.bind("click",function(){void 0!=e.callback&&e.callback(a.dialog);a.close(void 0!=e.caption?e.caption:e)});h.appendTo(d)});jQuery("<div>",{style:"clear:both"}).appendTo(d)}a.dialog.appendTo("body");b(window).bind("resize",k);a.settings.keyboard&&b(document).bind("keyup",p);a.isIE6&&b(window).bind("scroll", q);!1!==a.settings.auto_close&&(a.dialog.bind("click",function(){clearTimeout(a.timeout);a.close()}),a.timeout=setTimeout(a.close,a.settings.auto_close));k();return a};a.close=function(c){a.settings.keyboard&&b(document).unbind("keyup",p);a.isIE6&&b(window).unbind("scroll",q);b(window).unbind("resize",k);a.overlay&&a.overlay.animate({opacity:0},a.settings.animation_speed,function(){a.overlay.remove()});a.dialog.animate({/*top:0,*/opacity:0},a.settings.animation_speed,function(){a.dialog.remove();if(a.settings.onClose&& "function"==typeof a.settings.onClose)a.settings.onClose(void 0!=c?c:"")})};var k=function(){var c=b(window).width(),d=b(window).height(),f=a.dialog.width(),e=a.dialog.height(),f={left:0,top:0,right:c-f,bottom:d-e,center:(c-f)/2,middle:(d-e)/2};a.dialog_left=void 0;a.dialog_top=void 0;a.settings.modal&&a.overlay.css({width:c,height:d});b.isArray(a.settings.position)&&(2==a.settings.position.length&&"string"==typeof a.settings.position[0]&&a.settings.position[0].match(/^(left|right|center)[\s0-9\+\-]*$/)&& "string"==typeof a.settings.position[1]&&a.settings.position[1].match(/^(top|bottom|middle)[\s0-9\+\-]*$/))&&(a.settings.position[0]=a.settings.position[0].toLowerCase(),a.settings.position[1]=a.settings.position[1].toLowerCase(),b.each(f,function(c,d){for(var b=0;2>b;b++){var e=a.settings.position[b].replace(c,d);e!=a.settings.position[b]&&(0==b?a.dialog_left=eval(e):a.dialog_top=eval(e))}}));if(void 0==a.dialog_left||void 0==a.dialog_top)a.dialog_left=f.center,a.dialog_top=f.middle;a.settings.vcenter_short_message&& (c=a.message.find("div:first"),d=c.height(),f=a.message.height(),d<f&&c.css({"padding-top":(f-d)/2}));a.dialog.css({left:a.dialog_left,top:a.dialog_top,visibility:"visible"});a.dialog.find("a[class^=ZebraDialog_Button]:first").focus();a.isIE6&&setTimeout(r,500)},r=function(){var c=b(window).scrollTop(),d=b(window).scrollLeft();a.settings.modal&&a.overlay.css({top:c,left:d});a.dialog.css({left:a.dialog_left+d,top:a.dialog_top+c})},m=function(){if(!0!==a.settings.buttons&&!b.isArray(a.settings.buttons))return!1; if(!0===a.settings.buttons)switch(a.settings.type){case "question":a.settings.buttons=["Yes","No"];break;default:a.settings.buttons=["Ok"]}return a.settings.buttons.reverse()},n=function(){switch(a.settings.type){case "confirmation":case "error":case "information":case "question":case "warning":return a.settings.type.charAt(0).toUpperCase()+a.settings.type.slice(1).toLowerCase();default:return!1}},p=function(c){27==c.which&&a.close();return!0},q=function(){r()},j={init:function(){this.name=this.searchString(this.dataBrowser)|| "";this.version=this.searchVersion(navigator.userAgent)||this.searchVersion(navigator.appVersion)||""},searchString:function(a){for(var d=0;d<a.length;d++){var b=a[d].string,e=a[d].prop;this.versionSearchString=a[d].versionSearch||a[d].identity;if(b){if(-1!=b.indexOf(a[d].subString))return a[d].identity}else if(e)return a[d].identity}},searchVersion:function(a){var b=a.indexOf(this.versionSearchString);if(-1!=b)return parseFloat(a.substring(b+this.versionSearchString.length+1))},dataBrowser:[{string:navigator.userAgent, subString:"MSIE",identity:"explorer",versionSearch:"MSIE"}]};j.init();return a.init()}})(jQuery);

/*!
 * jquery.fixedHeaderTable. The jQuery fixedHeaderTable plugin
 *
 * Copyright (c) 2011 Mark Malek
 * http://fixedheadertable.com
 *
 * Licensed under MIT
 * http://www.opensource.org/licenses/mit-license.php
 *
 * http://docs.jquery.com/Plugins/Authoring
 * jQuery authoring guidelines
 *
 * Launch  : October 2009
 * Version : 1.3
 * Released: May 9th, 2011
 *
 *
 * all CSS sizing (width,height) is done in pixels (px)
 */

(function ($) {

    $.fn.fixedHeaderTable = function (method) {

        // plugin's default options
        var defaults = {

            width:          '100%',
            height:         '100%',
            themeClass:     'fht-default',
            borderCollapse:  true,
            fixedColumns:    0, // fixed first columns
            fixedColumn:     false, // For backward-compatibility
            sortable:        false,
            autoShow:        true, // hide table after its created
            footer:          false, // show footer
            cloneHeadToFoot: false, // clone head and use as footer
            autoResize:      false, // resize table if its parent wrapper changes size
            create:          null // callback after plugin completes
        };

        var settings = {};

        // public methods
        var methods = {
            init: function (options) {
                settings = $.extend({}, defaults, options);

                // iterate through all the DOM elements we are attaching the plugin to
                return this.each(function () {
                    var $self = $(this), // reference the jQuery version of the current DOM element
                        self = this; // reference to the actual DOM element

                    if (helpers._isTable($self)) {
                        methods.setup.apply(this, Array.prototype.slice.call(arguments, 1));
                        $.isFunction(settings.create) && settings.create.call(this);
                    } else {
                        $.error('Invalid table mark-up');
                    }
                });
            },

            /*
             * Setup table structure for fixed headers and optional footer
             */
            setup: function (options) {
                var $self  = $(this),
                    self   = this,
                    $thead = $self.find('thead'),
                    $tfoot = $self.find('tfoot'),
                    $tbody = $self.find('tbody'),
                    $wrapper,
                    $divHead,
                    $divFoot,
                    $divBody,
                    $fixedHeadRow,
                    $temp,
                    tfootHeight = 0;

                settings.originalTable = $(this).clone();
                settings.includePadding = helpers._isPaddingIncludedWithWidth();
                settings.scrollbarOffset = helpers._getScrollbarWidth();
                settings.themeClassName = settings.themeClass;

                if (settings.width.search('%') > -1) {
                    var widthMinusScrollbar = $self.parent().width() - settings.scrollbarOffset;
                } else {
                    var widthMinusScrollbar = settings.width - settings.scrollbarOffset;
                }

                $self.css({
                    width: widthMinusScrollbar
                });


                if (!$self.closest('.fht-table-wrapper').length) {
                    $self.addClass('fht-table');
                    $self.wrap('<div class="fht-table-wrapper"></div>');
                }

                $wrapper = $self.closest('.fht-table-wrapper');

                if(settings.fixedColumn == true && settings.fixedColumns <= 0) {
                    settings.fixedColumns = 1;
                }

                if (settings.fixedColumns > 0 && $wrapper.find('.fht-fixed-column').length == 0) {
                    $self.wrap('<div class="fht-fixed-body"></div>');

                    var $fixedColumns = $('<div class="fht-fixed-column" ></div>').prependTo($wrapper),
                        $fixedBody	 = $wrapper.find('.fht-fixed-body');
                }

                $wrapper.css({
                    width: settings.width,
                    height: settings.height
                })
                    .addClass(settings.themeClassName);


                if (!$self.hasClass('fht-table-init')) {

                    $self.wrap('<div class="fht-tbody"></div>');

                }
                $divBody = $self.closest('.fht-tbody');

                var tableProps = helpers._getTableProps($self);

                helpers._setupClone($divBody, tableProps.tbody);

                if (!$self.hasClass('fht-table-init')) {
                    if (settings.fixedColumns > 0) {
                        $divHead = $('<div class="fht-thead"><table class="fht-table"></table></div>').prependTo($fixedBody);
                    } else {
                        $divHead = $('<div class="fht-thead"><table class="fht-table"></table></div>').prependTo($wrapper);
                    }

                    $divHead.find('table.fht-table').addClass(settings.originalTable.attr('class'));
                    $thead.clone().appendTo($divHead.find('table'));
                } else {
                    $divHead = $wrapper.find('div.fht-thead');
                }

                helpers._setupClone($divHead, tableProps.thead);

                $self.css({
                    'margin-top': -$divHead.outerHeight(true)-1 //sanjay
                });

                /*
                 * Check for footer
                 * Setup footer if present
                 */
                if (settings.footer == true) {

                    helpers._setupTableFooter($self, self, tableProps);

                    if (!$tfoot.length) {
                        $tfoot = $wrapper.find('div.fht-tfoot table');
                    }

                    tfootHeight = $tfoot.outerHeight(true);
                }

                var tbodyHeight = $wrapper.height() - 67 - tfootHeight - tableProps.border; //$thead.outerHeight(true)

                $divBody.css({
                    'height': tbodyHeight  //sanjay
                });

                $self.addClass('fht-table-init');

                if (typeof(settings.altClass) !== 'undefined') {
                    methods.altRows.apply(self);
                }

                if (settings.fixedColumns > 0) {
                    helpers._setupFixedColumn($self, self, tableProps);
                }

                if (!settings.autoShow) {
                    $wrapper.hide();
                }

                helpers._bindScroll($divBody, tableProps);

                return self;
            },

            /*
             * Resize the table
             * Incomplete - not implemented yet
             */
            resize: function(options) {
                var $self = $(this),
                    self  = this;
                return self;
            },

            /*
             * Add CSS class to alternating rows
             */
            altRows: function(arg1) {
                var $self       = $(this),
                    self            = this,
                    altClass        = (typeof(arg1) !== 'undefined') ? arg1 : settings.altClass;

                $self.closest('.fht-table-wrapper')
                    .find('tbody tr:odd:not(:hidden)')
                    .addClass(altClass);
            },

            /*
             * Show a hidden fixedHeaderTable table
             */
            show: function(arg1, arg2, arg3) {
                var $self		= $(this),
                    self  		= this,
                    $wrapper 	= $self.closest('.fht-table-wrapper');

                // User provided show duration without a specific effect
                if (typeof(arg1) !== 'undefined' && typeof(arg1) === 'number') {

                    $wrapper.show(arg1, function() {
                        $.isFunction(arg2) && arg2.call(this);
                    });

                    return self;

                } else if (typeof(arg1) !== 'undefined' && typeof(arg1) === 'string'
                    && typeof(arg2) !== 'undefined' && typeof(arg2) === 'number') {
                    // User provided show duration with an effect

                    $wrapper.show(arg1, arg2, function() {
                        $.isFunction(arg3) && arg3.call(this);
                    });

                    return self;

                }

                $self.closest('.fht-table-wrapper')
                    .show();
                $.isFunction(arg1) && arg1.call(this);

                return self;
            },

            /*
             * Hide a fixedHeaderTable table
             */
            hide: function(arg1, arg2, arg3) {
                var $self 		= $(this),
                    self		= this,
                    $wrapper 	= $self.closest('.fht-table-wrapper');

                // User provided show duration without a specific effect
                if (typeof(arg1) !== 'undefined' && typeof(arg1) === 'number') {
                    $wrapper.hide(arg1, function() {
                        $.isFunction(arg3) && arg3.call(this);
                    });

                    return self;
                } else if (typeof(arg1) !== 'undefined' && typeof(arg1) === 'string'
                    && typeof(arg2) !== 'undefined' && typeof(arg2) === 'number') {

                    $wrapper.hide(arg1, arg2, function() {
                        $.isFunction(arg3) && arg3.call(this);
                    });

                    return self;
                }

                $self.closest('.fht-table-wrapper')
                    .hide();

                $.isFunction(arg3) && arg3.call(this);



                return self;
            },

            /*
             * Destory fixedHeaderTable and return table to original state
             */
            destroy: function() {
                var $self    = $(this),
                    self     = this,
                    $wrapper = $self.closest('.fht-table-wrapper');

                $self.insertBefore($wrapper)
                    .removeAttr('style')
                    .append($wrapper.find('tfoot'))
                    .removeClass('fht-table fht-table-init')
                    .find('.fht-cell')
                    .remove();

                $wrapper.remove();

                return self;
            }

        }

        // private methods
        var helpers = {

            /*
             * return boolean
             * True if a thead and tbody exist.
             */
            _isTable: function($obj) {
                var $self = $obj,
                    hasTable = $self.is('table'),
                    hasThead = $self.find('thead').length > 0,
                    hasTbody = $self.find('tbody').length > 0;

                if (hasTable && hasThead && hasTbody) {
                    return true;
                }

                return false;

            },

            /*
             * return void
             * bind scroll event
             */
            _bindScroll: function($obj, tableProps) {
                var $self = $obj,
                    $wrapper = $self.closest('.fht-table-wrapper'),
                    $thead = $self.siblings('.fht-thead'),
                    $tfoot = $self.siblings('.fht-tfoot');

                $self.bind('scroll', function() {
                    if (settings.fixedColumns > 0) {
                        var $fixedColumns = $wrapper.find('.fht-fixed-column');

                        $fixedColumns.find('.fht-tbody table')
                            .css({
                                'margin-top': -$self.scrollTop()
                            });
                    }

                    $thead.find('table')
                        .css({
                            'margin-left': -this.scrollLeft
                        });

                    if (settings.footer || settings.cloneHeadToFoot) {
                        $tfoot.find('table')
                            .css({
                                'margin-left': -this.scrollLeft
                            });
                    }
                });
            },

            /*
             * return void
             */
            _fixHeightWithCss: function ($obj, tableProps) {
                if (settings.includePadding) {  // added the condition to check if its head or td tag - sanjay
                    $obj.css({
                        'height': 39,
                        'padding-top': 22
                    });
                    /*						$obj.css({
                     'height': $obj.height() + tableProps.border
                     });*/

                } else {
                    $obj.css({
                        'height': $obj.parent().height() + tableProps.border
                    });
                }
            },

            /*
             * return void
             */
            _fixWidthWithCss: function($obj, tableProps, width) {
                if (settings.includePadding) {
                    $obj.each(function(index) {
                        if($obj.is('td')){
                            $(this).css({
                                'width': 205
                            });
                        }else {
                            $(this).css({
                                'width': width == undefined ? $(this).width() + tableProps.border : width + tableProps.border
                            });
                        }
                    });
                } else {
                    $obj.each(function(index) {
                        $(this).css({
                            'width': width == undefined ? $(this).parent().width() + tableProps.border : width + tableProps.border
                        });
                    });
                }

            },

            /*
             * return void
             */
            _setupFixedColumn: function ($obj, obj, tableProps) {
                var $self		= $obj,
                    self			= obj,
                    $wrapper		= $self.closest('.fht-table-wrapper'),
                    $fixedBody		= $wrapper.find('.fht-fixed-body'),
                    $fixedColumn		= $wrapper.find('.fht-fixed-column'),
                    $thead			= $('<div class="fht-thead" style="height:68px;"><table class="fht-table"><thead><tr></tr></thead></table></div>'),
                    $tbody			= $('<div class="fht-tbody"><table class="fht-table"><tbody></tbody></table></div>'),
                    $tfoot			= $('<div class="fht-tfoot"><table class="fht-table"><tfoot><tr></tr></tfoot></table></div>'),
                    $firstThChildren,//	= $fixedBody.find('.fht-thead thead tr > *:first-child'),
                    $firstTdChildren,
                    fixedColumnWidth,//	= $firstThChild.outerWidth(true) + tableProps.border,
                    fixedBodyWidth		= $wrapper.width(),
                    fixedBodyHeight		= $fixedBody.find('.fht-tbody').height() - settings.scrollbarOffset,
                    $newRow;

                $thead.find('table.fht-table').addClass(settings.originalTable.attr('class'));
                $tbody.find('table.fht-table').addClass(settings.originalTable.attr('class'));
                $tfoot.find('table.fht-table').addClass(settings.originalTable.attr('class'));

                $firstThChildren = $fixedBody.find('.fht-thead thead tr > *:lt(' + settings.fixedColumns + ')');
                fixedColumnWidth = settings.fixedColumns * tableProps.border;
                $firstThChildren.each(function(index) {
                    fixedColumnWidth += $(this).outerWidth(true);
                });

                // Fix cell heights
                helpers._fixHeightWithCss($firstThChildren, tableProps);
                helpers._fixWidthWithCss($firstThChildren, tableProps);

                var tdWidths = [];
                $firstThChildren.each(function(index) {
                    tdWidths.push($(this).width());
                });

                firstTdChildrenSelector = 'tbody tr > *:not(:nth-child(n+' + (settings.fixedColumns + 1) + '))';
                $firstTdChildren = $fixedBody.find(firstTdChildrenSelector)
                    .each(function(index) {
                        helpers._fixHeightWithCss($(this), tableProps);
                        helpers._fixWidthWithCss($(this), tableProps, tdWidths[index % settings.fixedColumns] );
                    });

                // clone header
                $thead.appendTo($fixedColumn)
                    .find('tr')
                    .append($firstThChildren.clone());

                $tbody.appendTo($fixedColumn)
                    .css({
                        'margin-top': -1,
                        'height': fixedBodyHeight + tableProps.border
                    });

                var $newRow;
                $firstTdChildren.each(function(index) {
                    if (index % settings.fixedColumns == 0) {
                        $newRow = $('<tr></tr>').appendTo($tbody.find('tbody'));

                        if (settings.altClass && $(this).parent().hasClass(settings.altClass)) {
                            $newRow.addClass(settings.altClass);
                        }
                    }

                    $(this).clone()
                        .appendTo($newRow);
                });

                //fixedColumnWidth=200;
                // set width of fixed column wrapper
                $fixedColumn.css({
                    'height': 0,
                    'width': fixedColumnWidth + 12
                })


                // bind mousewheel events
                var maxTop = $fixedColumn.find('.fht-tbody .fht-table').height() - $fixedColumn.find('.fht-tbody').height();
                $fixedColumn.find('.fht-table').bind('mousewheel', function(event, delta, deltaX, deltaY) {
                    if (deltaY == 0) return;
                    var top = parseInt($(this).css('marginTop'), 10) + (deltaY > 0 ? 120 : -120);
                    if (top > 0) top = 0;
                    if (top < -maxTop) top = -maxTop;
                    $(this).css('marginTop', top);
                    $fixedBody.find('.fht-tbody').scrollTop(-top).scroll();
                    return false;
                });


                // set width of body table wrapper
                $fixedBody.css({
                    'width': fixedBodyWidth
                });

                // setup clone footer with fixed column
                if (settings.footer == true || settings.cloneHeadToFoot == true) {
                    var $firstTdFootChild = $fixedBody.find('.fht-tfoot tr > *:lt(' + settings.fixedColumns + ')');

                    helpers._fixHeightWithCss($firstTdFootChild, tableProps);
                    $tfoot.appendTo($fixedColumn)
                        .find('tr')
                        .append($firstTdFootChild.clone());
                    // Set (view width) of $tfoot div to width of table (this accounts for footers with a colspan)
                    footwidth = $tfoot.find('table').innerWidth();
                    $tfoot.css({
                        'top': settings.scrollbarOffset,
                        'width': footwidth
                    });
                }
            },

            /*
             * return void
             */
            _setupTableFooter: function ($obj, obj, tableProps) {

                var $self 		= $obj,
                    self  		= obj,
                    $wrapper 	= $self.closest('.fht-table-wrapper'),
                    $tfoot		= $self.find('tfoot'),
                    $divFoot	= $wrapper.find('div.fht-tfoot');

                if (!$divFoot.length) {
                    if (settings.fixedColumns > 0) {
                        $divFoot = $('<div class="fht-tfoot"><table class="fht-table"></table></div>').appendTo($wrapper.find('.fht-fixed-body'));
                    } else {
                        $divFoot = $('<div class="fht-tfoot"><table class="fht-table"></table></div>').appendTo($wrapper);
                    }
                }
                $divFoot.find('table.fht-table').addClass(settings.originalTable.attr('class'));

                switch (true) {
                    case !$tfoot.length && settings.cloneHeadToFoot == true && settings.footer == true:

                        var $divHead = $wrapper.find('div.fht-thead');

                        $divFoot.empty();
                        $divHead.find('table')
                            .clone()
                            .appendTo($divFoot);

                        break;
                    case $tfoot.length && settings.cloneHeadToFoot == false && settings.footer == true:

                        $divFoot.find('table')
                            .append($tfoot)
                            .css({
                                'margin-top': -tableProps.border
                            });

                        helpers._setupClone($divFoot, tableProps.tfoot);

                        break;
                }

            },

            /*
             * return object
             * Widths of each thead cell and tbody cell for the first rows.
             * Used in fixing widths for the fixed header and optional footer.
             */
            _getTableProps: function($obj) {
                var tableProp = {
                        thead: {},
                        tbody: {},
                        tfoot: {},
                        border: 0
                    },
                    borderCollapse = 1;

                if (settings.borderCollapse == true) {
                    borderCollapse = 2;
                }

                tableProp.border = ($obj.find('th:first-child').outerWidth() - $obj.find('th:first-child').innerWidth()) / borderCollapse;

                $obj.find('thead tr:first-child > *').each(function(index) {
                    tableProp.thead[index] = $(this).width() + tableProp.border;
                });

                $obj.find('tfoot tr:first-child > *').each(function(index) {
                    tableProp.tfoot[index] = $(this).width() + tableProp.border;
                });

                $obj.find('tbody tr:first-child > *').each(function(index) {
                    tableProp.tbody[index] = $(this).width() + tableProp.border;
                });

                return tableProp;
            },

            /*
             * return void
             * Fix widths of each cell in the first row of obj.
             */
            _setupClone: function($obj, cellArray) {
                var $self    = $obj,
                    selector = ($self.find('thead').length) ?
                        'thead tr:first-child > *' :
                        ($self.find('tfoot').length) ?
                            'tfoot tr:first-child > *' :
                            'tbody tr:first-child > *',
                    $cell;

                $self.find(selector).each(function(index) {
                    $cell = ($(this).find('div.fht-cell').length) ? $(this).find('div.fht-cell') : $('<div class="fht-cell"></div>').appendTo($(this));

                    $cell.css({
                        'width': 201//parseInt(cellArray[index])
                    });

                    /*
                     * Fixed Header and Footer should extend the full width
                     * to align with the scrollbar of the body
                     */
                    if (!$(this).closest('.fht-tbody').length && $(this).is(':last-child') && !$(this).closest('.fht-fixed-column').length) {
                        var padding = (($(this).innerWidth() - $(this).width()) / 2) + settings.scrollbarOffset;
                        $(this).css({
                            'padding-right': '5px', //padding
                        });
                    }
                });
            },

            /*
             * return boolean
             * Determine how the browser calculates fixed widths with padding for tables
             * true if width = padding + width
             * false if width = width
             */
            _isPaddingIncludedWithWidth: function() {
                var $obj 			= $('<table class="fht-table"><tr><td style="padding: 10px; font-size: 10px;">test</td></tr></table>'),
                    defaultHeight,
                    newHeight;

                $obj.addClass(settings.originalTable.attr('class'));
                $obj.appendTo('body');

                defaultHeight = $obj.find('td').height();

                $obj.find('td')
                    .css('height', $obj.find('tr').height());

                newHeight = $obj.find('td').height();
                $obj.remove();

                if (defaultHeight != newHeight) {
                    return true;
                } else {
                    return false;
                }

            },

            /*
             * return int
             * get the width of the browsers scroll bar
             */
            _getScrollbarWidth: function() {
                var scrollbarWidth = 0;

                if (!scrollbarWidth) {
                    if ($.browser.msie) {
                        var $textarea1 = $('<textarea cols="10" rows="2"></textarea>')
                                .css({ position: 'absolute', top: -1000, left: -1000 }).appendTo('body'),
                            $textarea2 = $('<textarea cols="10" rows="2" style="overflow: hidden;"></textarea>')
                                .css({ position: 'absolute', top: -1000, left: -1000 }).appendTo('body');
                        scrollbarWidth = $textarea1.width() - $textarea2.width() + 2; // + 2 for border offset
                        $textarea1.add($textarea2).remove();
                    } else {
                        var $div = $('<div />')
                            .css({ width: 100, height: 100, overflow: 'auto', position: 'absolute', top: -1000, left: -1000 })
                            .prependTo('body').append('<div />').find('div')
                            .css({ width: '100%', height: 200 });
                        scrollbarWidth = 100 - $div.width();
                        $div.parent().remove();
                    }
                }

                return scrollbarWidth;
            }

        }


        // if a method as the given argument exists
        if (methods[method]) {

            // call the respective method
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));

            // if an object is given as method OR nothing is given as argument
        } else if (typeof method === 'object' || !method) {

            // call the initialization method
            return methods.init.apply(this, arguments);

            // otherwise
        } else {

            // trigger an error
            $.error('Method "' +  method + '" does not exist in fixedHeaderTable plugin!');

        }

    };

})(jQuery);


/*
 * jQuery treeTable Plugin 3.0.0
 * http://ludo.cubicphuse.nl/jquery-treetable
 *
 * Copyright 2013, Ludo van den Boom
 * Dual licensed under the MIT or GPL Version 2 licenses.
 */
(function() {
    var $, Node, Tree, methods;

    $ = jQuery;

    Node = (function() {
        function Node(row, tree, settings) {
            var parentId;

            this.row = row;
            this.tree = tree;
            this.settings = settings;

            // TODO Ensure id/parentId is always a string (not int)
            this.id = this.row.data(this.settings.nodeIdAttr);

            // TODO Move this to a setParentId function?
            parentId = this.row.data(this.settings.parentIdAttr);
            if (parentId != null && parentId !== "") {
                this.parentId = parentId;
            }

            this.treeCell = $(this.row.children(this.settings.columnElType)[this.settings.column]);
            this.expander = $(this.settings.expanderTemplate);
            this.indenter = $(this.settings.indenterTemplate);
            this.children = [];
            this.initialized = false;
            this.treeCell.prepend(this.indenter);
        }

        Node.prototype.addChild = function(child) {
            return this.children.push(child);
        };

        Node.prototype.ancestors = function() {
            var ancestors, node;
            node = this;
            ancestors = [];
            while (node = node.parentNode()) {
                ancestors.push(node);
            }
            return ancestors;
        };

        Node.prototype.collapse = function() {
            this._hideChildren();
            this.row.removeClass("expanded").addClass("collapsed");
            this.expander.attr("title", this.settings.stringExpand);

            if (this.initialized && this.settings.onNodeCollapse != null) {
                this.settings.onNodeCollapse.apply(this);
            }

            return this;
        };

        // TODO destroy: remove event handlers, expander, indenter, etc.

        Node.prototype.expand = function() {
            if (this.initialized && this.settings.onNodeExpand != null) {
                this.settings.onNodeExpand.apply(this);
            }

            this.row.removeClass("collapsed").addClass("expanded");
            this._showChildren();
            this.expander.attr("title", this.settings.stringCollapse);

            return this;
        };

        Node.prototype.expanded = function() {
            return this.row.hasClass("expanded");
        };

        Node.prototype.hide = function() {
            this._hideChildren();
            this.row.hide();
            return this;
        };

        Node.prototype.isBranchNode = function() {
            if(this.children.length > 0 || this.row.data(this.settings.branchAttr) === true) {
                return true;
            } else {
                return false;
            }
        };

        Node.prototype.level = function() {
            return this.ancestors().length;
        };

        Node.prototype.parentNode = function() {
            if (this.parentId != null) {
                return this.tree[this.parentId];
            } else {
                return null;
            }
        };

        Node.prototype.removeChild = function(child) {
            var i = $.inArray(child, this.children);
            return this.children.splice(i, 1)
        };

        Node.prototype.render = function() {
            var settings = this.settings, target;

            if (settings.expandable === true && this.isBranchNode()) {
                this.indenter.html(this.expander);
                target = settings.clickableNodeNames === true ? this.treeCell : this.expander;
                target.unbind("click.treetable").bind("click.treetable", function(event) {
                    $(this).parents("table").treetable("node", $(this).parents("tr").data(settings.nodeIdAttr)).toggle();
                    return event.preventDefault();
                });
            }

            if (settings.expandable === true && settings.initialState === "expanded") {
                this.collapse();
            } else {
                this.expand();
            }

            this.indenter[0].style.paddingLeft = "" + (this.level() * settings.indent) + "px";

            return this;
        };

        Node.prototype.reveal = function() {
            if (this.parentId != null) {
                this.parentNode().reveal();
            }
            return this.expand();
        };

        Node.prototype.setParent = function(node) {
            if (this.parentId != null) {
                this.tree[this.parentId].removeChild(this);
            }
            this.parentId = node.id;
            this.row.data(this.settings.parentIdAttr, node.id);
            return node.addChild(this);
        };

        Node.prototype.show = function() {
            if (!this.initialized) {
                this._initialize();
            }
            this.row.show();
            if (this.expanded()) {
                this._showChildren();
            }
            return this;
        };

        Node.prototype.toggle = function() {
            if (this.expanded()) {
                this.collapse();
            } else {
                this.expand();
            }
            return this;
        };

        Node.prototype._hideChildren = function() {
            var child, _i, _len, _ref, _results;
            _ref = this.children;
            _results = [];
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                child = _ref[_i];
                _results.push(child.hide());
            }
            return _results;
        };

        Node.prototype._initialize = function() {
            this.render();
            if (this.settings.onNodeInitialized != null) {
                this.settings.onNodeInitialized.apply(this);
            }
            return this.initialized = true;
        };

        Node.prototype._showChildren = function() {
            var child, _i, _len, _ref, _results;
            _ref = this.children;
            _results = [];
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                child = _ref[_i];
                _results.push(child.show());
            }
            return _results;
        };

        return Node;
    })();

    Tree = (function() {
        function Tree(table, settings) {
            this.table = table;
            this.settings = settings;
            this.tree = {};

            // Cache the nodes and roots in simple arrays for quick access/iteration
            this.nodes = [];
            this.roots = [];
        }

        Tree.prototype.collapseAll = function() {
            var node, _i, _len, _ref, _results;
            _ref = this.nodes;
            _results = [];
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                node = _ref[_i];
                _results.push(node.collapse());
            }
            return _results;
        };

        Tree.prototype.expandAll = function() {
            var node, _i, _len, _ref, _results;
            _ref = this.nodes;
            _results = [];
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                node = _ref[_i];
                _results.push(node.expand());
            }
            return _results;
        };

        Tree.prototype.loadRows = function(rows) {
            var node, row, i;

            if (rows != null) {
                for (i = 0; i < rows.length; i++) {
                    row = $(rows[i]);

                    if (row.data(this.settings.nodeIdAttr) != null) {
                        node = new Node(row, this.tree, this.settings);
                        this.nodes.push(node);
                        this.tree[node.id] = node;

                        if (node.parentId != null) {
                            this.tree[node.parentId].addChild(node);
                        } else {
                            this.roots.push(node);
                        }
                    }
                }
            }

            return this;
        };

        Tree.prototype.move = function(node, destination) {
            // Conditions:
            // 1: +node+ should not be inserted as a child of +node+ itself.
            // 2: +destination+ should not be the same as +node+'s current parent (this
            //    prevents +node+ from being moved to the same location where it already
            //    is).
            // 3: +node+ should not be inserted in a location in a branch if this would
            //    result in +node+ being an ancestor of itself.
            if (node !== destination && destination.id !== node.parentId && $.inArray(node, destination.ancestors()) === -1) {
                node.setParent(destination);
                this._moveRows(node, destination);

                // Re-render parentNode if this is its first child node, and therefore
                // doesn't have the expander yet.
                if (node.parentNode().children.length === 1) {
                    node.parentNode().render();
                }
            }
            return this;
        };

        Tree.prototype.render = function() {
            var root, _i, _len, _ref;
            _ref = this.roots;
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                root = _ref[_i];

                // Naming is confusing (show/render). I do not call render on node from
                // here.
                root.show();
            }
            return this;
        };

        Tree.prototype._moveRows = function(node, destination) {
            var child, _i, _len, _ref, _results;
            node.row.insertAfter(destination.row);
            node.render();
            _ref = node.children;
            _results = [];
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                child = _ref[_i];
                _results.push(this._moveRows(child, node));
            }
            return _results;
        };

        Tree.prototype.unloadBranch = function(node) {
            var child, children, i;

            for (i = 0; i < node.children.length; i++) {
                child = node.children[i];

                // Recursively remove all descendants of +node+
                this.unloadBranch(child);

                // Remove child from DOM (<tr>)
                child.row.remove();

                // Clean up Tree object (so Node objects are GC-ed)
                delete this.tree[child.id];
                this.nodes.splice($.inArray(child, this.nodes), 1)
            }

            // Reset node's collection of children
            node.children = [];

            return this;
        };


        return Tree;
    })();

    // jQuery Plugin
    methods = {
        init: function(options) {
            var settings;

            settings = $.extend({
                branchAttr: "ttBranch",
                clickableNodeNames: false,
                column: 0,
                columnElType: "td", // i.e. 'td', 'th' or 'td,th'
                expandable: false,
                expanderTemplate: "<a href='#'>&nbsp;</a>",
                indent: 19,
                indenterTemplate: "<span class='indenter'></span>",
                initialState: "collapsed",
                nodeIdAttr: "ttId", // maps to data-tt-id
                parentIdAttr: "ttParentId", // maps to data-tt-parent-id
                stringExpand: "Expand",
                stringCollapse: "Collapse",

                // Events
                onInitialized: null,
                onNodeCollapse: null,
                onNodeExpand: null,
                onNodeInitialized: null
            }, options);

            return this.each(function() {
                var el, tree;

                tree = new Tree(this, settings);
                tree.loadRows(this.rows).render();

                el = $(this).addClass("treetable").data("treetable", tree);

                if (settings.onInitialized != null) {
                    settings.onInitialized.apply(tree);
                }

                return el;
            });
        },

        destroy: function() {
            return this.each(function() {
                return $(this).removeData("treetable").removeClass("treetable");
            });
        },

        collapseAll: function() {
            this.data("treetable").collapseAll();
            return this;
        },

        collapseNode: function(id) {
            var node = this.data("treetable").tree[id];

            if (node) {
                node.collapse();
            } else {
                throw new Error("Unknown node '" + id + "'");
            }

            return this;
        },

        expandAll: function() {
            this.data("treetable").expandAll();
            return this;
        },

        expandNode: function(id) {
            var node = this.data("treetable").tree[id];

            if (node) {
                node.expand();
            } else {
                throw new Error("Unknown node '" + id + "'");
            }

            return this;
        },

        loadBranch: function(node, rows) {
            rows = $(rows);
            rows.insertAfter(node.row);
            this.data("treetable").loadRows(rows);

            return this;
        },

        move: function(nodeId, destinationId) {
            var destination, node;

            node = this.data("treetable").tree[nodeId];
            destination = this.data("treetable").tree[destinationId];
            this.data("treetable").move(node, destination);

            return this;
        },

        node: function(id) {
            return this.data("treetable").tree[id];
        },

        reveal: function(id) {
            var node = this.data("treetable").tree[id];

            if (node) {
                node.reveal();
            } else {
                throw new Error("Unknown node '" + id + "'");
            }

            return this;
        },

        unloadBranch: function(node) {
            this.data("treetable").unloadBranch(node);
            return this;
        }
    };

    $.fn.treetable = function(method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            return $.error("Method " + method + " does not exist on jQuery.treetable");
        }
    };

    // Expose classes to world
    this.TreeTable || (this.TreeTable = {});
    this.TreeTable.Node = Node;
    this.TreeTable.Tree = Tree;
}).call(this);


(function(a){var b=(a.browser.msie?"paste":"input")+".mask",c=window.orientation!=undefined;a.mask={definitions:{9:"[0-9]",a:"[A-Za-z]","*":"[A-Za-z0-9]"},dataName:"rawMaskFn"},a.fn.extend({caret:function(a,b){if(this.length!=0){if(typeof a=="number"){b=typeof b=="number"?b:a;return this.each(function(){if(this.setSelectionRange)this.setSelectionRange(a,b);else if(this.createTextRange){var c=this.createTextRange();c.collapse(!0),c.moveEnd("character",b),c.moveStart("character",a),c.select()}})}if(this[0].setSelectionRange)a=this[0].selectionStart,b=this[0].selectionEnd;else if(document.selection&&document.selection.createRange){var c=document.selection.createRange();a=0-c.duplicate().moveStart("character",-1e5),b=a+c.text.length}return{begin:a,end:b}}},unmask:function(){return this.trigger("unmask")},mask:function(d,e){if(!d&&this.length>0){var f=a(this[0]);return f.data(a.mask.dataName)()}e=a.extend({placeholder:"_",completed:null},e);var g=a.mask.definitions,h=[],i=d.length,j=null,k=d.length;a.each(d.split(""),function(a,b){b=="?"?(k--,i=a):g[b]?(h.push(new RegExp(g[b])),j==null&&(j=h.length-1)):h.push(null)});return this.trigger("unmask").each(function(){function v(a){var b=f.val(),c=-1;for(var d=0,g=0;d<k;d++)if(h[d]){l[d]=e.placeholder;while(g++<b.length){var m=b.charAt(g-1);if(h[d].test(m)){l[d]=m,c=d;break}}if(g>b.length)break}else l[d]==b.charAt(g)&&d!=i&&(g++,c=d);if(!a&&c+1<i)f.val(""),t(0,k);else if(a||c+1>=i)u(),a||f.val(f.val().substring(0,c+1));return i?d:j}function u(){return f.val(l.join("")).val()}function t(a,b){for(var c=a;c<b&&c<k;c++)h[c]&&(l[c]=e.placeholder)}function s(a){var b=a.which,c=f.caret();if(a.ctrlKey||a.altKey||a.metaKey||b<32)return!0;if(b){c.end-c.begin!=0&&(t(c.begin,c.end),p(c.begin,c.end-1));var d=n(c.begin-1);if(d<k){var g=String.fromCharCode(b);if(h[d].test(g)){q(d),l[d]=g,u();var i=n(d);f.caret(i),e.completed&&i>=k&&e.completed.call(f)}}return!1}}function r(a){var b=a.which;if(b==8||b==46||c&&b==127){var d=f.caret(),e=d.begin,g=d.end;g-e==0&&(e=b!=46?o(e):g=n(e-1),g=b==46?n(g):g),t(e,g),p(e,g-1);return!1}if(b==27){f.val(m),f.caret(0,v());return!1}}function q(a){for(var b=a,c=e.placeholder;b<k;b++)if(h[b]){var d=n(b),f=l[b];l[b]=c;if(d<k&&h[d].test(f))c=f;else break}}function p(a,b){if(!(a<0)){for(var c=a,d=n(b);c<k;c++)if(h[c]){if(d<k&&h[c].test(l[d]))l[c]=l[d],l[d]=e.placeholder;else break;d=n(d)}u(),f.caret(Math.max(j,a))}}function o(a){while(--a>=0&&!h[a]);return a}function n(a){while(++a<=k&&!h[a]);return a}var f=a(this),l=a.map(d.split(""),function(a,b){if(a!="?")return g[a]?e.placeholder:a}),m=f.val();f.data(a.mask.dataName,function(){return a.map(l,function(a,b){return h[b]&&a!=e.placeholder?a:null}).join("")}),f.attr("readonly")||f.one("unmask",function(){f.unbind(".mask").removeData(a.mask.dataName)}).bind("focus.mask",function(){m=f.val();var b=v();u();var c=function(){b==d.length?f.caret(0,b):f.caret(b)};(a.browser.msie?c:function(){setTimeout(c,0)})()}).bind("blur.mask",function(){v(),f.val()!=m&&f.change()}).bind("keydown.mask",r).bind("keypress.mask",s).bind(b,function(){setTimeout(function(){f.caret(v(!0))},0)}),v()})}})})(jQuery)

//autoGrowInput
$.fn.autoGrowInput = function(o) {

    o = $.extend({
        maxWidth: 1000,
        minWidth: 0,
        comfortZone: 70
    }, o);

    this.filter('input:text').each(function(){

        var minWidth = o.minWidth || $(this).width(),
            val = '',
            input = $(this),
            testSubject = $('<tester/>').css({
                position: 'absolute',
                top: -9999,
                left: -9999,
                width: 'auto',
                fontSize: input.css('fontSize'),
                fontFamily: input.css('fontFamily'),
                fontWeight: input.css('fontWeight'),
                letterSpacing: input.css('letterSpacing'),
                whiteSpace: 'nowrap'
            }),
            check = function() {

                if (val === (val = input.val())) {return;}

                // Enter new content into testSubject
                var escaped = val.replace(/&/g, '&amp;').replace(/\s/g,' ').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                testSubject.html(escaped);

                // Calculate new width + whether to change
                var testerWidth = testSubject.width(),
                    newWidth = (testerWidth + o.comfortZone) >= minWidth ? testerWidth + o.comfortZone : minWidth,
                    currentWidth = input.width(),
                    isValidWidthChange = (newWidth < currentWidth && newWidth >= minWidth)
                        || (newWidth > minWidth && newWidth < o.maxWidth);

                // Animate width
                if (isValidWidthChange) {
                    input.width(newWidth);
                }

            };

        testSubject.insertAfter(input);

        $(this).bind('keyup keydown blur update', check);

    });

    return this;

};

/**
 *
 * Color picker
 * Author: Stefan Petre www.eyecon.ro
 *
 * Dual licensed under the MIT and GPL licenses
 *
 */
(function ($) {
    var ColorPicker = function () {
        var
            ids = {},
            inAction,
            charMin = 65,
            visible,
            tpl = '<div class="colorpicker"><div class="colorpicker_color"><div><div></div></div></div><div class="colorpicker_hue"><div></div></div><div class="colorpicker_new_color"></div><div class="colorpicker_current_color"></div><div class="colorpicker_hex"><input type="text" maxlength="6" size="6" /></div><div class="colorpicker_rgb_r colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_rgb_g colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_rgb_b colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_hsb_h colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_hsb_s colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_hsb_b colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_submit"></div></div>',
            defaults = {
                eventName: 'click',
                onShow: function () {},
                onBeforeShow: function(){},
                onHide: function () {},
                onChange: function () {},
                onSubmit: function () {},
                color: 'ff0000',
                livePreview: true,
                flat: false
            },
            fillRGBFields = function  (hsb, cal) {
                var rgb = HSBToRGB(hsb);
                $(cal).data('colorpicker').fields
                    .eq(1).val(rgb.r).end()
                    .eq(2).val(rgb.g).end()
                    .eq(3).val(rgb.b).end();
            },
            fillHSBFields = function  (hsb, cal) {
                $(cal).data('colorpicker').fields
                    .eq(4).val(hsb.h).end()
                    .eq(5).val(hsb.s).end()
                    .eq(6).val(hsb.b).end();
            },
            fillHexFields = function (hsb, cal) {
                $(cal).data('colorpicker').fields
                    .eq(0).val(HSBToHex(hsb)).end();
            },
            setSelector = function (hsb, cal) {
                $(cal).data('colorpicker').selector.css('backgroundColor', '#' + HSBToHex({h: hsb.h, s: 100, b: 100}));
                $(cal).data('colorpicker').selectorIndic.css({
                    left: parseInt(150 * hsb.s/100, 10),
                    top: parseInt(150 * (100-hsb.b)/100, 10)
                });
            },
            setHue = function (hsb, cal) {
                $(cal).data('colorpicker').hue.css('top', parseInt(150 - 150 * hsb.h/360, 10));
            },
            setCurrentColor = function (hsb, cal) {
                $(cal).data('colorpicker').currentColor.css('backgroundColor', '#' + HSBToHex(hsb));
            },
            setNewColor = function (hsb, cal) {
                $(cal).data('colorpicker').newColor.css('backgroundColor', '#' + HSBToHex(hsb));
            },
            keyDown = function (ev) {
                var pressedKey = ev.charCode || ev.keyCode || -1;
                if ((pressedKey > charMin && pressedKey <= 90) || pressedKey == 32) {
                    return false;
                }
                var cal = $(this).parent().parent();
                if (cal.data('colorpicker').livePreview === true) {
                    change.apply(this);
                }
            },
            change = function (ev) {
                var cal = $(this).parent().parent(), col;
                if (this.parentNode.className.indexOf('_hex') > 0) {
                    cal.data('colorpicker').color = col = HexToHSB(fixHex(this.value));
                } else if (this.parentNode.className.indexOf('_hsb') > 0) {
                    cal.data('colorpicker').color = col = fixHSB({
                        h: parseInt(cal.data('colorpicker').fields.eq(4).val(), 10),
                        s: parseInt(cal.data('colorpicker').fields.eq(5).val(), 10),
                        b: parseInt(cal.data('colorpicker').fields.eq(6).val(), 10)
                    });
                } else {
                    cal.data('colorpicker').color = col = RGBToHSB(fixRGB({
                        r: parseInt(cal.data('colorpicker').fields.eq(1).val(), 10),
                        g: parseInt(cal.data('colorpicker').fields.eq(2).val(), 10),
                        b: parseInt(cal.data('colorpicker').fields.eq(3).val(), 10)
                    }));
                }
                if (ev) {
                    fillRGBFields(col, cal.get(0));
                    fillHexFields(col, cal.get(0));
                    fillHSBFields(col, cal.get(0));
                }
                setSelector(col, cal.get(0));
                setHue(col, cal.get(0));
                setNewColor(col, cal.get(0));
                cal.data('colorpicker').onChange.apply(cal, [col, HSBToHex(col), HSBToRGB(col)]);
            },
            blur = function (ev) {
                var cal = $(this).parent().parent();
                cal.data('colorpicker').fields.parent().removeClass('colorpicker_focus');
            },
            focus = function () {
                charMin = this.parentNode.className.indexOf('_hex') > 0 ? 70 : 65;
                $(this).parent().parent().data('colorpicker').fields.parent().removeClass('colorpicker_focus');
                $(this).parent().addClass('colorpicker_focus');
            },
            downIncrement = function (ev) {
                var field = $(this).parent().find('input').focus();
                var current = {
                    el: $(this).parent().addClass('colorpicker_slider'),
                    max: this.parentNode.className.indexOf('_hsb_h') > 0 ? 360 : (this.parentNode.className.indexOf('_hsb') > 0 ? 100 : 255),
                    y: ev.pageY,
                    field: field,
                    val: parseInt(field.val(), 10),
                    preview: $(this).parent().parent().data('colorpicker').livePreview
                };
                $(document).bind('mouseup', current, upIncrement);
                $(document).bind('mousemove', current, moveIncrement);
            },
            moveIncrement = function (ev) {
                ev.data.field.val(Math.max(0, Math.min(ev.data.max, parseInt(ev.data.val + ev.pageY - ev.data.y, 10))));
                if (ev.data.preview) {
                    change.apply(ev.data.field.get(0), [true]);
                }
                return false;
            },
            upIncrement = function (ev) {
                change.apply(ev.data.field.get(0), [true]);
                ev.data.el.removeClass('colorpicker_slider').find('input').focus();
                $(document).unbind('mouseup', upIncrement);
                $(document).unbind('mousemove', moveIncrement);
                return false;
            },
            downHue = function (ev) {
                var current = {
                    cal: $(this).parent(),
                    y: $(this).offset().top
                };
                current.preview = current.cal.data('colorpicker').livePreview;
                $(document).bind('mouseup', current, upHue);
                $(document).bind('mousemove', current, moveHue);
            },
            moveHue = function (ev) {
                change.apply(
                    ev.data.cal.data('colorpicker')
                        .fields
                        .eq(4)
                        .val(parseInt(360*(150 - Math.max(0,Math.min(150,(ev.pageY - ev.data.y))))/150, 10))
                        .get(0),
                    [ev.data.preview]
                );
                return false;
            },
            upHue = function (ev) {
                fillRGBFields(ev.data.cal.data('colorpicker').color, ev.data.cal.get(0));
                fillHexFields(ev.data.cal.data('colorpicker').color, ev.data.cal.get(0));
                $(document).unbind('mouseup', upHue);
                $(document).unbind('mousemove', moveHue);
                return false;
            },
            downSelector = function (ev) {
                var current = {
                    cal: $(this).parent(),
                    pos: $(this).offset()
                };
                current.preview = current.cal.data('colorpicker').livePreview;
                $(document).bind('mouseup', current, upSelector);
                $(document).bind('mousemove', current, moveSelector);
            },
            moveSelector = function (ev) {
                change.apply(
                    ev.data.cal.data('colorpicker')
                        .fields
                        .eq(6)
                        .val(parseInt(100*(150 - Math.max(0,Math.min(150,(ev.pageY - ev.data.pos.top))))/150, 10))
                        .end()
                        .eq(5)
                        .val(parseInt(100*(Math.max(0,Math.min(150,(ev.pageX - ev.data.pos.left))))/150, 10))
                        .get(0),
                    [ev.data.preview]
                );
                return false;
            },
            upSelector = function (ev) {
                fillRGBFields(ev.data.cal.data('colorpicker').color, ev.data.cal.get(0));
                fillHexFields(ev.data.cal.data('colorpicker').color, ev.data.cal.get(0));
                $(document).unbind('mouseup', upSelector);
                $(document).unbind('mousemove', moveSelector);
                return false;
            },
            enterSubmit = function (ev) {
                $(this).addClass('colorpicker_focus');
            },
            leaveSubmit = function (ev) {
                $(this).removeClass('colorpicker_focus');
            },
            clickSubmit = function (ev) {
                var cal = $(this).parent();
                var col = cal.data('colorpicker').color;
                cal.data('colorpicker').origColor = col;
                setCurrentColor(col, cal.get(0));
                cal.data('colorpicker').onSubmit(col, HSBToHex(col), HSBToRGB(col), cal.data('colorpicker').el);
            },
            show = function (ev) {
                var cal = $('#' + $(this).data('colorpickerId'));
                cal.data('colorpicker').onBeforeShow.apply(this, [cal.get(0)]);
                var pos = $(this).offset();
                var viewPort = getViewport();
                var top = pos.top + this.offsetHeight;
                var left = pos.left;
                if (top + 176 > viewPort.t + viewPort.h) {
                    top -= this.offsetHeight + 176;
                }
                if (left + 356 > viewPort.l + viewPort.w) {
                    left -= 356;
                }
                cal.css({left: left + 'px', top: top + 'px'});
                if (cal.data('colorpicker').onShow.apply(this, [cal.get(0)]) != false) {
                    cal.show();
                }
                $(document).bind('mousedown', {cal: cal}, hide);
                return false;
            },
            hide = function (ev) {
                if (!isChildOf(ev.data.cal.get(0), ev.target, ev.data.cal.get(0))) {
                    if (ev.data.cal.data('colorpicker').onHide.apply(this, [ev.data.cal.get(0)]) != false) {
                        ev.data.cal.hide();
                    }
                    $(document).unbind('mousedown', hide);
                }
            },
            isChildOf = function(parentEl, el, container) {
                if (parentEl == el) {
                    return true;
                }
                if (parentEl.contains) {
                    return parentEl.contains(el);
                }
                if ( parentEl.compareDocumentPosition ) {
                    return !!(parentEl.compareDocumentPosition(el) & 16);
                }
                var prEl = el.parentNode;
                while(prEl && prEl != container) {
                    if (prEl == parentEl)
                        return true;
                    prEl = prEl.parentNode;
                }
                return false;
            },
            getViewport = function () {
                var m = document.compatMode == 'CSS1Compat';
                return {
                    l : window.pageXOffset || (m ? document.documentElement.scrollLeft : document.body.scrollLeft),
                    t : window.pageYOffset || (m ? document.documentElement.scrollTop : document.body.scrollTop),
                    w : window.innerWidth || (m ? document.documentElement.clientWidth : document.body.clientWidth),
                    h : window.innerHeight || (m ? document.documentElement.clientHeight : document.body.clientHeight)
                };
            },
            fixHSB = function (hsb) {
                return {
                    h: Math.min(360, Math.max(0, hsb.h)),
                    s: Math.min(100, Math.max(0, hsb.s)),
                    b: Math.min(100, Math.max(0, hsb.b))
                };
            },
            fixRGB = function (rgb) {
                return {
                    r: Math.min(255, Math.max(0, rgb.r)),
                    g: Math.min(255, Math.max(0, rgb.g)),
                    b: Math.min(255, Math.max(0, rgb.b))
                };
            },
            fixHex = function (hex) {
                var len = 6 - hex.length;
                if (len > 0) {
                    var o = [];
                    for (var i=0; i<len; i++) {
                        o.push('0');
                    }
                    o.push(hex);
                    hex = o.join('');
                }
                return hex;
            },
            HexToRGB = function (hex) {
                var hex = parseInt(((hex.indexOf('#') > -1) ? hex.substring(1) : hex), 16);
                return {r: hex >> 16, g: (hex & 0x00FF00) >> 8, b: (hex & 0x0000FF)};
            },
            HexToHSB = function (hex) {
                return RGBToHSB(HexToRGB(hex));
            },
            RGBToHSB = function (rgb) {
                var hsb = {
                    h: 0,
                    s: 0,
                    b: 0
                };
                var min = Math.min(rgb.r, rgb.g, rgb.b);
                var max = Math.max(rgb.r, rgb.g, rgb.b);
                var delta = max - min;
                hsb.b = max;
                if (max != 0) {

                }
                hsb.s = max != 0 ? 255 * delta / max : 0;
                if (hsb.s != 0) {
                    if (rgb.r == max) {
                        hsb.h = (rgb.g - rgb.b) / delta;
                    } else if (rgb.g == max) {
                        hsb.h = 2 + (rgb.b - rgb.r) / delta;
                    } else {
                        hsb.h = 4 + (rgb.r - rgb.g) / delta;
                    }
                } else {
                    hsb.h = -1;
                }
                hsb.h *= 60;
                if (hsb.h < 0) {
                    hsb.h += 360;
                }
                hsb.s *= 100/255;
                hsb.b *= 100/255;
                return hsb;
            },
            HSBToRGB = function (hsb) {
                var rgb = {};
                var h = Math.round(hsb.h);
                var s = Math.round(hsb.s*255/100);
                var v = Math.round(hsb.b*255/100);
                if(s == 0) {
                    rgb.r = rgb.g = rgb.b = v;
                } else {
                    var t1 = v;
                    var t2 = (255-s)*v/255;
                    var t3 = (t1-t2)*(h%60)/60;
                    if(h==360) h = 0;
                    if(h<60) {rgb.r=t1;	rgb.b=t2; rgb.g=t2+t3}
                    else if(h<120) {rgb.g=t1; rgb.b=t2;	rgb.r=t1-t3}
                    else if(h<180) {rgb.g=t1; rgb.r=t2;	rgb.b=t2+t3}
                    else if(h<240) {rgb.b=t1; rgb.r=t2;	rgb.g=t1-t3}
                    else if(h<300) {rgb.b=t1; rgb.g=t2;	rgb.r=t2+t3}
                    else if(h<360) {rgb.r=t1; rgb.g=t2;	rgb.b=t1-t3}
                    else {rgb.r=0; rgb.g=0;	rgb.b=0}
                }
                return {r:Math.round(rgb.r), g:Math.round(rgb.g), b:Math.round(rgb.b)};
            },
            RGBToHex = function (rgb) {
                var hex = [
                    rgb.r.toString(16),
                    rgb.g.toString(16),
                    rgb.b.toString(16)
                ];
                $.each(hex, function (nr, val) {
                    if (val.length == 1) {
                        hex[nr] = '0' + val;
                    }
                });
                return hex.join('');
            },
            HSBToHex = function (hsb) {
                return RGBToHex(HSBToRGB(hsb));
            },
            restoreOriginal = function () {
                var cal = $(this).parent();
                var col = cal.data('colorpicker').origColor;
                cal.data('colorpicker').color = col;
                fillRGBFields(col, cal.get(0));
                fillHexFields(col, cal.get(0));
                fillHSBFields(col, cal.get(0));
                setSelector(col, cal.get(0));
                setHue(col, cal.get(0));
                setNewColor(col, cal.get(0));
            };
        return {
            init: function (opt) {
                opt = $.extend({}, defaults, opt||{});
                if (typeof opt.color == 'string') {
                    opt.color = HexToHSB(opt.color);
                } else if (opt.color.r != undefined && opt.color.g != undefined && opt.color.b != undefined) {
                    opt.color = RGBToHSB(opt.color);
                } else if (opt.color.h != undefined && opt.color.s != undefined && opt.color.b != undefined) {
                    opt.color = fixHSB(opt.color);
                } else {
                    return this;
                }
                return this.each(function () {
                    if (!$(this).data('colorpickerId')) {
                        var options = $.extend({}, opt);
                        options.origColor = opt.color;
                        var id = 'collorpicker_' + parseInt(Math.random() * 1000);
                        $(this).data('colorpickerId', id);
                        var cal = $(tpl).attr('id', id);
                        if (options.flat) {
                            cal.appendTo(this).show();
                        } else {
                            cal.appendTo(document.body);
                        }
                        options.fields = cal
                            .find('input')
                            .bind('keyup', keyDown)
                            .bind('change', change)
                            .bind('blur', blur)
                            .bind('focus', focus);
                        cal
                            .find('span').bind('mousedown', downIncrement).end()
                            .find('>div.colorpicker_current_color').bind('click', restoreOriginal);
                        options.selector = cal.find('div.colorpicker_color').bind('mousedown', downSelector);
                        options.selectorIndic = options.selector.find('div div');
                        options.el = this;
                        options.hue = cal.find('div.colorpicker_hue div');
                        cal.find('div.colorpicker_hue').bind('mousedown', downHue);
                        options.newColor = cal.find('div.colorpicker_new_color');
                        options.currentColor = cal.find('div.colorpicker_current_color');
                        cal.data('colorpicker', options);
                        cal.find('div.colorpicker_submit')
                            .bind('mouseenter', enterSubmit)
                            .bind('mouseleave', leaveSubmit)
                            .bind('click', clickSubmit);
                        fillRGBFields(options.color, cal.get(0));
                        fillHSBFields(options.color, cal.get(0));
                        fillHexFields(options.color, cal.get(0));
                        setHue(options.color, cal.get(0));
                        setSelector(options.color, cal.get(0));
                        setCurrentColor(options.color, cal.get(0));
                        setNewColor(options.color, cal.get(0));
                        if (options.flat) {
                            cal.css({
                                position: 'relative',
                                display: 'block'
                            });
                        } else {
                            $(this).bind(options.eventName, show);
                        }
                    }
                });
            },
            showPicker: function() {
                return this.each( function () {
                    if ($(this).data('colorpickerId')) {
                        show.apply(this);

                    }
                });
            },
            hidePicker: function() {
                return this.each( function () {
                    if ($(this).data('colorpickerId')) {
                        $('#' + $(this).data('colorpickerId')).hide();
                    }
                });
            },
            setColor: function(col) {
                if (typeof col == 'string') {
                    col = HexToHSB(col);
                } else if (col.r != undefined && col.g != undefined && col.b != undefined) {
                    col = RGBToHSB(col);
                } else if (col.h != undefined && col.s != undefined && col.b != undefined) {
                    col = fixHSB(col);
                } else {
                    return this;
                }
                return this.each(function(){
                    if ($(this).data('colorpickerId')) {
                        var cal = $('#' + $(this).data('colorpickerId'));
                        cal.data('colorpicker').color = col;
                        cal.data('colorpicker').origColor = col;
                        fillRGBFields(col, cal.get(0));
                        fillHSBFields(col, cal.get(0));
                        fillHexFields(col, cal.get(0));
                        setHue(col, cal.get(0));
                        setSelector(col, cal.get(0));
                        setCurrentColor(col, cal.get(0));
                        setNewColor(col, cal.get(0));
                    }
                });
            }
        };
    }();
    $.fn.extend({
        ColorPicker: ColorPicker.init,
        ColorPickerHide: ColorPicker.hidePicker,
        ColorPickerShow: ColorPicker.showPicker,
        ColorPickerSetColor: ColorPicker.setColor
    });
})(jQuery)

/* http://keith-wood.name/countdown.html
 Countdown for jQuery v1.6.1.
 Written by Keith Wood (kbwood{at}iinet.com.au) January 2008.
 Available under the MIT (https://github.com/jquery/jquery/blob/master/MIT-LICENSE.txt) license.
 Please attribute the author if you use it. */

/* Display a countdown timer.
 Attach it with options like:
 $('div selector').countdown(
 {until: new Date(2009, 1 - 1, 1, 0, 0, 0), onExpiry: happyNewYear}); */

;(function($) { // Hide scope, no $ conflict

    /* Countdown manager. */
    function Countdown() {
        this.regional = []; // Available regional settings, indexed by language code
        this.regional[''] = { // Default regional settings
            // The display texts for the counters
            labels: ['Years', 'Months', 'Weeks', 'Days', 'Hours', 'Minutes', 'Seconds'],
            // The display texts for the counters if only one
            labels1: ['Year', 'Month', 'Week', 'Day', 'Hour', 'Minute', 'Second'],
            compactLabels: ['y', 'm', 'w', 'd'], // The compact texts for the counters
            whichLabels: null, // Function to determine which labels to use
            digits: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'], // The digits to display
            timeSeparator: ':', // Separator for time periods
            isRTL: false // True for right-to-left languages, false for left-to-right
        };
        this._defaults = {
            until: null, // new Date(year, mth - 1, day, hr, min, sec) - date/time to count down to
            // or numeric for seconds offset, or string for unit offset(s):
            // 'Y' years, 'O' months, 'W' weeks, 'D' days, 'H' hours, 'M' minutes, 'S' seconds
            since: null, // new Date(year, mth - 1, day, hr, min, sec) - date/time to count up from
            // or numeric for seconds offset, or string for unit offset(s):
            // 'Y' years, 'O' months, 'W' weeks, 'D' days, 'H' hours, 'M' minutes, 'S' seconds
            timezone: null, // The timezone (hours or minutes from GMT) for the target times,
            // or null for client local
            serverSync: null, // A function to retrieve the current server time for synchronisation
            format: 'dHMS', // Format for display - upper case for always, lower case only if non-zero,
            // 'Y' years, 'O' months, 'W' weeks, 'D' days, 'H' hours, 'M' minutes, 'S' seconds
            layout: '', // Build your own layout for the countdown
            compact: false, // True to display in a compact format, false for an expanded one
            significant: 0, // The number of periods with values to show, zero for all
            description: '', // The description displayed for the countdown
            expiryUrl: '', // A URL to load upon expiry, replacing the current page
            expiryText: '', // Text to display upon expiry, replacing the countdown
            alwaysExpire: false, // True to trigger onExpiry even if never counted down
            onExpiry: null, // Callback when the countdown expires -
            // receives no parameters and 'this' is the containing division
            onTick: null, // Callback when the countdown is updated -
            // receives int[7] being the breakdown by period (based on format)
            // and 'this' is the containing division
            tickInterval: 1 // Interval (seconds) between onTick callbacks
        };
        $.extend(this._defaults, this.regional['']);
        this._serverSyncs = [];
        // Shared timer for all countdowns
        function timerCallBack(timestamp) {
            var drawStart = (timestamp < 1e12 ? // New HTML5 high resolution timer
                (drawStart = performance.now ?
                    (performance.now() + performance.timing.navigationStart) : Date.now()) :
                // Integer milliseconds since unix epoch
                timestamp || new Date().getTime());
            if (drawStart - animationStartTime >= 1000) {
                plugin._updateTargets();
                animationStartTime = drawStart;
            }
            requestAnimationFrame(timerCallBack);
        }
        var requestAnimationFrame = window.requestAnimationFrame ||
            window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame ||
            window.oRequestAnimationFrame || window.msRequestAnimationFrame || null;
        // This is when we expect a fall-back to setInterval as it's much more fluid
        var animationStartTime = 0;
        if (!requestAnimationFrame || $.noRequestAnimationFrame) {
            $.noRequestAnimationFrame = null;
            setInterval(function() { plugin._updateTargets(); }, 980); // Fall back to good old setInterval
        }
        else {
            animationStartTime = window.animationStartTime ||
                window.webkitAnimationStartTime || window.mozAnimationStartTime ||
                window.oAnimationStartTime || window.msAnimationStartTime || new Date().getTime();
            requestAnimationFrame(timerCallBack);
        }
    }

    var Y = 0; // Years
    var O = 1; // Months
    var W = 2; // Weeks
    var D = 3; // Days
    var H = 4; // Hours
    var M = 5; // Minutes
    var S = 6; // Seconds

    $.extend(Countdown.prototype, {
        /* Class name added to elements to indicate already configured with countdown. */
        markerClassName: 'hasCountdown',
        /* Name of the data property for instance settings. */
        propertyName: 'countdown',

        /* Class name for the right-to-left marker. */
        _rtlClass: 'countdown_rtl',
        /* Class name for the countdown section marker. */
        _sectionClass: 'countdown_section',
        /* Class name for the period amount marker. */
        _amountClass: 'countdown_amount',
        /* Class name for the countdown row marker. */
        _rowClass: 'countdown_row',
        /* Class name for the holding countdown marker. */
        _holdingClass: 'countdown_holding',
        /* Class name for the showing countdown marker. */
        _showClass: 'countdown_show',
        /* Class name for the description marker. */
        _descrClass: 'countdown_descr',

        /* List of currently active countdown targets. */
        _timerTargets: [],

        /* Override the default settings for all instances of the countdown widget.
         @param  options  (object) the new settings to use as defaults */
        setDefaults: function(options) {
            this._resetExtraLabels(this._defaults, options);
            $.extend(this._defaults, options || {});
        },

        /* Convert a date/time to UTC.
         @param  tz     (number) the hour or minute offset from GMT, e.g. +9, -360
         @param  year   (Date) the date/time in that timezone or
         (number) the year in that timezone
         @param  month  (number, optional) the month (0 - 11) (omit if year is a Date)
         @param  day    (number, optional) the day (omit if year is a Date)
         @param  hours  (number, optional) the hour (omit if year is a Date)
         @param  mins   (number, optional) the minute (omit if year is a Date)
         @param  secs   (number, optional) the second (omit if year is a Date)
         @param  ms     (number, optional) the millisecond (omit if year is a Date)
         @return  (Date) the equivalent UTC date/time */
        UTCDate: function(tz, year, month, day, hours, mins, secs, ms) {
            if (typeof year == 'object' && year.constructor == Date) {
                ms = year.getMilliseconds();
                secs = year.getSeconds();
                mins = year.getMinutes();
                hours = year.getHours();
                day = year.getDate();
                month = year.getMonth();
                year = year.getFullYear();
            }
            var d = new Date();
            d.setUTCFullYear(year);
            d.setUTCDate(1);
            d.setUTCMonth(month || 0);
            d.setUTCDate(day || 1);
            d.setUTCHours(hours || 0);
            d.setUTCMinutes((mins || 0) - (Math.abs(tz) < 30 ? tz * 60 : tz));
            d.setUTCSeconds(secs || 0);
            d.setUTCMilliseconds(ms || 0);
            return d;
        },

        /* Convert a set of periods into seconds.
         Averaged for months and years.
         @param  periods  (number[7]) the periods per year/month/week/day/hour/minute/second
         @return  (number) the corresponding number of seconds */
        periodsToSeconds: function(periods) {
            return periods[0] * 31557600 + periods[1] * 2629800 + periods[2] * 604800 +
                periods[3] * 86400 + periods[4] * 3600 + periods[5] * 60 + periods[6];
        },

        /* Attach the countdown widget to a div.
         @param  target   (element) the containing division
         @param  options  (object) the initial settings for the countdown */
        _attachPlugin: function(target, options) {
            target = $(target);
            if (target.hasClass(this.markerClassName)) {
                return;
            }
            var inst = {options: $.extend({}, this._defaults), _periods: [0, 0, 0, 0, 0, 0, 0]};
            target.addClass(this.markerClassName).data(this.propertyName, inst);
            this._optionPlugin(target, options);
        },

        /* Add a target to the list of active ones.
         @param  target  (element) the countdown target */
        _addTarget: function(target) {
            if (!this._hasTarget(target)) {
                this._timerTargets.push(target);
            }
        },

        /* See if a target is in the list of active ones.
         @param  target  (element) the countdown target
         @return  (boolean) true if present, false if not */
        _hasTarget: function(target) {
            return ($.inArray(target, this._timerTargets) > -1);
        },

        /* Remove a target from the list of active ones.
         @param  target  (element) the countdown target */
        _removeTarget: function(target) {
            this._timerTargets = $.map(this._timerTargets,
                function(value) { return (value == target ? null : value); }); // delete entry
        },

        /* Update each active timer target. */
        _updateTargets: function() {
            for (var i = this._timerTargets.length - 1; i >= 0; i--) {
                this._updateCountdown(this._timerTargets[i]);
            }
        },

        /* Reconfigure the settings for a countdown div.
         @param  target   (element) the control to affect
         @param  options  (object) the new options for this instance or
         (string) an individual property name
         @param  value    (any) the individual property value (omit if options
         is an object or to retrieve the value of a setting)
         @return  (any) if retrieving a value */
        _optionPlugin: function(target, options, value) {
            target = $(target);
            var inst = target.data(this.propertyName);
            if (!options || (typeof options == 'string' && value == null)) { // Get option
                var name = options;
                options = (inst || {}).options;
                return (options && name ? options[name] : options);
            }

            if (!target.hasClass(this.markerClassName)) {
                return;
            }
            options = options || {};
            if (typeof options == 'string') {
                var name = options;
                options = {};
                options[name] = value;
            }
            this._resetExtraLabels(inst.options, options);
            $.extend(inst.options, options);
            this._adjustSettings(target, inst);
            var now = new Date();
            if ((inst._since && inst._since < now) || (inst._until && inst._until > now)) {
                this._addTarget(target[0]);
            }
            this._updateCountdown(target, inst);
        },

        /* Redisplay the countdown with an updated display.
         @param  target  (jQuery) the containing division
         @param  inst    (object) the current settings for this instance */
        _updateCountdown: function(target, inst) {
            var $target = $(target);
            inst = inst || $target.data(this.propertyName);
            if (!inst) {
                return;
            }
            $target.html(this._generateHTML(inst)).toggleClass(this._rtlClass, inst.options.isRTL);
            if ($.isFunction(inst.options.onTick)) {
                var periods = inst._hold != 'lap' ? inst._periods :
                    this._calculatePeriods(inst, inst._show, inst.options.significant, new Date());
                if (inst.options.tickInterval == 1 ||
                    this.periodsToSeconds(periods) % inst.options.tickInterval == 0) {
                    inst.options.onTick.apply(target, [periods]);
                }
            }
            var expired = inst._hold != 'pause' &&
                (inst._since ? inst._now.getTime() < inst._since.getTime() :
                    inst._now.getTime() >= inst._until.getTime());
            if (expired && !inst._expiring) {
                inst._expiring = true;
                if (this._hasTarget(target) || inst.options.alwaysExpire) {
                    this._removeTarget(target);
                    if ($.isFunction(inst.options.onExpiry)) {
                        inst.options.onExpiry.apply(target, []);
                    }
                    if (inst.options.expiryText) {
                        var layout = inst.options.layout;
                        inst.options.layout = inst.options.expiryText;
                        this._updateCountdown(target, inst);
                        inst.options.layout = layout;
                    }
                    if (inst.options.expiryUrl) {
                        window.location = inst.options.expiryUrl;
                    }
                }
                inst._expiring = false;
            }
            else if (inst._hold == 'pause') {
                this._removeTarget(target);
            }
            $target.data(this.propertyName, inst);
        },

        /* Reset any extra labelsn and compactLabelsn entries if changing labels.
         @param  base     (object) the options to be updated
         @param  options  (object) the new option values */
        _resetExtraLabels: function(base, options) {
            var changingLabels = false;
            for (var n in options) {
                if (n != 'whichLabels' && n.match(/[Ll]abels/)) {
                    changingLabels = true;
                    break;
                }
            }
            if (changingLabels) {
                for (var n in base) { // Remove custom numbered labels
                    if (n.match(/[Ll]abels[02-9]/)) {
                        base[n] = null;
                    }
                }
            }
        },

        /* Calculate interal settings for an instance.
         @param  target  (element) the containing division
         @param  inst    (object) the current settings for this instance */
        _adjustSettings: function(target, inst) {
            var now;
            var serverOffset = 0;
            var serverEntry = null;
            for (var i = 0; i < this._serverSyncs.length; i++) {
                if (this._serverSyncs[i][0] == inst.options.serverSync) {
                    serverEntry = this._serverSyncs[i][1];
                    break;
                }
            }
            if (serverEntry != null) {
                serverOffset = (inst.options.serverSync ? serverEntry : 0);
                now = new Date();
            }
            else {
                var serverResult = ($.isFunction(inst.options.serverSync) ?
                    inst.options.serverSync.apply(target, []) : null);
                now = new Date();
                serverOffset = (serverResult ? now.getTime() - serverResult.getTime() : 0);
                this._serverSyncs.push([inst.options.serverSync, serverOffset]);
            }
            var timezone = inst.options.timezone;
            timezone = (timezone == null ? -now.getTimezoneOffset() : timezone);
            inst._since = inst.options.since;
            if (inst._since != null) {
                inst._since = this.UTCDate(timezone, this._determineTime(inst._since, null));
                if (inst._since && serverOffset) {
                    inst._since.setMilliseconds(inst._since.getMilliseconds() + serverOffset);
                }
            }
            inst._until = this.UTCDate(timezone, this._determineTime(inst.options.until, now));
            if (serverOffset) {
                inst._until.setMilliseconds(inst._until.getMilliseconds() + serverOffset);
            }
            inst._show = this._determineShow(inst);
        },

        /* Remove the countdown widget from a div.
         @param  target  (element) the containing division */
        _destroyPlugin: function(target) {
            target = $(target);
            if (!target.hasClass(this.markerClassName)) {
                return;
            }
            this._removeTarget(target[0]);
            target.removeClass(this.markerClassName).empty().removeData(this.propertyName);
        },

        /* Pause a countdown widget at the current time.
         Stop it running but remember and display the current time.
         @param  target  (element) the containing division */
        _pausePlugin: function(target) {
            this._hold(target, 'pause');
        },

        /* Pause a countdown widget at the current time.
         Stop the display but keep the countdown running.
         @param  target  (element) the containing division */
        _lapPlugin: function(target) {
            this._hold(target, 'lap');
        },

        /* Resume a paused countdown widget.
         @param  target  (element) the containing division */
        _resumePlugin: function(target) {
            this._hold(target, null);
        },

        /* Pause or resume a countdown widget.
         @param  target  (element) the containing division
         @param  hold    (string) the new hold setting */
        _hold: function(target, hold) {
            var inst = $.data(target, this.propertyName);
            if (inst) {
                if (inst._hold == 'pause' && !hold) {
                    inst._periods = inst._savePeriods;
                    var sign = (inst._since ? '-' : '+');
                    inst[inst._since ? '_since' : '_until'] =
                        this._determineTime(sign + inst._periods[0] + 'y' +
                            sign + inst._periods[1] + 'o' + sign + inst._periods[2] + 'w' +
                            sign + inst._periods[3] + 'd' + sign + inst._periods[4] + 'h' +
                            sign + inst._periods[5] + 'm' + sign + inst._periods[6] + 's');
                    this._addTarget(target);
                }
                inst._hold = hold;
                inst._savePeriods = (hold == 'pause' ? inst._periods : null);
                $.data(target, this.propertyName, inst);
                this._updateCountdown(target, inst);
            }
        },

        /* Return the current time periods.
         @param  target  (element) the containing division
         @return  (number[7]) the current periods for the countdown */
        _getTimesPlugin: function(target) {
            var inst = $.data(target, this.propertyName);
            return (!inst ? null : (!inst._hold ? inst._periods :
                    this._calculatePeriods(inst, inst._show, inst.options.significant, new Date())));
        },

        /* A time may be specified as an exact value or a relative one.
         @param  setting      (string or number or Date) - the date/time value
         as a relative or absolute value
         @param  defaultTime  (Date) the date/time to use if no other is supplied
         @return  (Date) the corresponding date/time */
        _determineTime: function(setting, defaultTime) {
            var offsetNumeric = function(offset) { // e.g. +300, -2
                var time = new Date();
                time.setTime(time.getTime() + offset * 1000);
                return time;
            };
            var offsetString = function(offset) { // e.g. '+2d', '-4w', '+3h +30m'
                offset = offset.toLowerCase();
                var time = new Date();
                var year = time.getFullYear();
                var month = time.getMonth();
                var day = time.getDate();
                var hour = time.getHours();
                var minute = time.getMinutes();
                var second = time.getSeconds();
                var pattern = /([+-]?[0-9]+)\s*(s|m|h|d|w|o|y)?/g;
                var matches = pattern.exec(offset);
                while (matches) {
                    switch (matches[2] || 's') {
                        case 's': second += parseInt(matches[1], 10); break;
                        case 'm': minute += parseInt(matches[1], 10); break;
                        case 'h': hour += parseInt(matches[1], 10); break;
                        case 'd': day += parseInt(matches[1], 10); break;
                        case 'w': day += parseInt(matches[1], 10) * 7; break;
                        case 'o':
                            month += parseInt(matches[1], 10);
                            day = Math.min(day, plugin._getDaysInMonth(year, month));
                            break;
                        case 'y':
                            year += parseInt(matches[1], 10);
                            day = Math.min(day, plugin._getDaysInMonth(year, month));
                            break;
                    }
                    matches = pattern.exec(offset);
                }
                return new Date(year, month, day, hour, minute, second, 0);
            };
            var time = (setting == null ? defaultTime :
                (typeof setting == 'string' ? offsetString(setting) :
                    (typeof setting == 'number' ? offsetNumeric(setting) : setting)));
            if (time) time.setMilliseconds(0);
            return time;
        },

        /* Determine the number of days in a month.
         @param  year   (number) the year
         @param  month  (number) the month
         @return  (number) the days in that month */
        _getDaysInMonth: function(year, month) {
            return 32 - new Date(year, month, 32).getDate();
        },

        /* Determine which set of labels should be used for an amount.
         @param  num  (number) the amount to be displayed
         @return  (number) the set of labels to be used for this amount */
        _normalLabels: function(num) {
            return num;
        },

        /* Generate the HTML to display the countdown widget.
         @param  inst  (object) the current settings for this instance
         @return  (string) the new HTML for the countdown display */
        _generateHTML: function(inst) {
            var self = this;
            // Determine what to show
            inst._periods = (inst._hold ? inst._periods :
                this._calculatePeriods(inst, inst._show, inst.options.significant, new Date()));
            // Show all 'asNeeded' after first non-zero value
            var shownNonZero = false;
            var showCount = 0;
            var sigCount = inst.options.significant;
            var show = $.extend({}, inst._show);
            for (var period = Y; period <= S; period++) {
                shownNonZero |= (inst._show[period] == '?' && inst._periods[period] > 0);
                show[period] = (inst._show[period] == '?' && !shownNonZero ? null : inst._show[period]);
                showCount += (show[period] ? 1 : 0);
                sigCount -= (inst._periods[period] > 0 ? 1 : 0);
            }
            var showSignificant = [false, false, false, false, false, false, false];
            for (var period = S; period >= Y; period--) { // Determine significant periods
                if (inst._show[period]) {
                    if (inst._periods[period]) {
                        showSignificant[period] = true;
                    }
                    else {
                        showSignificant[period] = sigCount > 0;
                        sigCount--;
                    }
                }
            }
            var labels = (inst.options.compact ? inst.options.compactLabels : inst.options.labels);
            var whichLabels = inst.options.whichLabels || this._normalLabels;
            var showCompact = function(period) {
                var labelsNum = inst.options['compactLabels' + whichLabels(inst._periods[period])];
                return (show[period] ? self._translateDigits(inst, inst._periods[period]) +
                    (labelsNum ? labelsNum[period] : labels[period]) + ' ' : '');
            };
            var showFull = function(period) {
                var labelsNum = inst.options['labels' + whichLabels(inst._periods[period])];
                return ((!inst.options.significant && show[period]) ||
                (inst.options.significant && showSignificant[period]) ?
                    '<span class="' + plugin._sectionClass + '">' +
                    '<span class="' + plugin._amountClass + '">' +
                    self._translateDigits(inst, inst._periods[period]) + '</span><br/>' +
                    (labelsNum ? labelsNum[period] : labels[period]) + '</span>' : '');
            };
            return (inst.options.layout ? this._buildLayout(inst, show, inst.options.layout,
                    inst.options.compact, inst.options.significant, showSignificant) :
                ((inst.options.compact ? // Compact version
                    '<span class="' + this._rowClass + ' ' + this._amountClass +
                    (inst._hold ? ' ' + this._holdingClass : '') + '">' +
                    showCompact(Y) + showCompact(O) + showCompact(W) + showCompact(D) +
                    (show[H] ? this._minDigits(inst, inst._periods[H], 2) : '') +
                    (show[M] ? (show[H] ? inst.options.timeSeparator : '') +
                        this._minDigits(inst, inst._periods[M], 2) : '') +
                    (show[S] ? (show[H] || show[M] ? inst.options.timeSeparator : '') +
                        this._minDigits(inst, inst._periods[S], 2) : '') :
                    // Full version
                    '<span class="' + this._rowClass + ' ' + this._showClass + (inst.options.significant || showCount) +
                    (inst._hold ? ' ' + this._holdingClass : '') + '">' +
                    showFull(Y) + showFull(O) + showFull(W) + showFull(D) +
                    showFull(H) + showFull(M) + showFull(S)) + '</span>' +
                (inst.options.description ? '<span class="' + this._rowClass + ' ' + this._descrClass + '">' +
                    inst.options.description + '</span>' : '')));
        },

        /* Construct a custom layout.
         @param  inst             (object) the current settings for this instance
         @param  show             (string[7]) flags indicating which periods are requested
         @param  layout           (string) the customised layout
         @param  compact          (boolean) true if using compact labels
         @param  significant      (number) the number of periods with values to show, zero for all
         @param  showSignificant  (boolean[7]) other periods to show for significance
         @return  (string) the custom HTML */
        _buildLayout: function(inst, show, layout, compact, significant, showSignificant) {
            var labels = inst.options[compact ? 'compactLabels' : 'labels'];
            var whichLabels = inst.options.whichLabels || this._normalLabels;
            var labelFor = function(index) {
                return (inst.options[(compact ? 'compactLabels' : 'labels') +
                whichLabels(inst._periods[index])] || labels)[index];
            };
            var digit = function(value, position) {
                return inst.options.digits[Math.floor(value / position) % 10];
            };
            var subs = {desc: inst.options.description, sep: inst.options.timeSeparator,
                yl: labelFor(Y), yn: this._minDigits(inst, inst._periods[Y], 1),
                ynn: this._minDigits(inst, inst._periods[Y], 2),
                ynnn: this._minDigits(inst, inst._periods[Y], 3), y1: digit(inst._periods[Y], 1),
                y10: digit(inst._periods[Y], 10), y100: digit(inst._periods[Y], 100),
                y1000: digit(inst._periods[Y], 1000),
                ol: labelFor(O), on: this._minDigits(inst, inst._periods[O], 1),
                onn: this._minDigits(inst, inst._periods[O], 2),
                onnn: this._minDigits(inst, inst._periods[O], 3), o1: digit(inst._periods[O], 1),
                o10: digit(inst._periods[O], 10), o100: digit(inst._periods[O], 100),
                o1000: digit(inst._periods[O], 1000),
                wl: labelFor(W), wn: this._minDigits(inst, inst._periods[W], 1),
                wnn: this._minDigits(inst, inst._periods[W], 2),
                wnnn: this._minDigits(inst, inst._periods[W], 3), w1: digit(inst._periods[W], 1),
                w10: digit(inst._periods[W], 10), w100: digit(inst._periods[W], 100),
                w1000: digit(inst._periods[W], 1000),
                dl: labelFor(D), dn: this._minDigits(inst, inst._periods[D], 1),
                dnn: this._minDigits(inst, inst._periods[D], 2),
                dnnn: this._minDigits(inst, inst._periods[D], 3), d1: digit(inst._periods[D], 1),
                d10: digit(inst._periods[D], 10), d100: digit(inst._periods[D], 100),
                d1000: digit(inst._periods[D], 1000),
                hl: labelFor(H), hn: this._minDigits(inst, inst._periods[H], 1),
                hnn: this._minDigits(inst, inst._periods[H], 2),
                hnnn: this._minDigits(inst, inst._periods[H], 3), h1: digit(inst._periods[H], 1),
                h10: digit(inst._periods[H], 10), h100: digit(inst._periods[H], 100),
                h1000: digit(inst._periods[H], 1000),
                ml: labelFor(M), mn: this._minDigits(inst, inst._periods[M], 1),
                mnn: this._minDigits(inst, inst._periods[M], 2),
                mnnn: this._minDigits(inst, inst._periods[M], 3), m1: digit(inst._periods[M], 1),
                m10: digit(inst._periods[M], 10), m100: digit(inst._periods[M], 100),
                m1000: digit(inst._periods[M], 1000),
                sl: labelFor(S), sn: this._minDigits(inst, inst._periods[S], 1),
                snn: this._minDigits(inst, inst._periods[S], 2),
                snnn: this._minDigits(inst, inst._periods[S], 3), s1: digit(inst._periods[S], 1),
                s10: digit(inst._periods[S], 10), s100: digit(inst._periods[S], 100),
                s1000: digit(inst._periods[S], 1000)};
            var html = layout;
            // Replace period containers: {p<}...{p>}
            for (var i = Y; i <= S; i++) {
                var period = 'yowdhms'.charAt(i);
                var re = new RegExp('\\{' + period + '<\\}(.*)\\{' + period + '>\\}', 'g');
                html = html.replace(re, ((!significant && show[i]) ||
                (significant && showSignificant[i]) ? '$1' : ''));
            }
            // Replace period values: {pn}
            $.each(subs, function(n, v) {
                var re = new RegExp('\\{' + n + '\\}', 'g');
                html = html.replace(re, v);
            });
            return html;
        },

        /* Ensure a numeric value has at least n digits for display.
         @param  inst   (object) the current settings for this instance
         @param  value  (number) the value to display
         @param  len    (number) the minimum length
         @return  (string) the display text */
        _minDigits: function(inst, value, len) {
            value = '' + value;
            if (value.length >= len) {
                return this._translateDigits(inst, value);
            }
            value = '0000000000' + value;
            return this._translateDigits(inst, value.substr(value.length - len));
        },

        /* Translate digits into other representations.
         @param  inst   (object) the current settings for this instance
         @param  value  (string) the text to translate
         @return  (string) the translated text */
        _translateDigits: function(inst, value) {
            return ('' + value).replace(/[0-9]/g, function(digit) {
                return inst.options.digits[digit];
            });
        },

        /* Translate the format into flags for each period.
         @param  inst  (object) the current settings for this instance
         @return  (string[7]) flags indicating which periods are requested (?) or
         required (!) by year, month, week, day, hour, minute, second */
        _determineShow: function(inst) {
            var format = inst.options.format;
            var show = [];
            show[Y] = (format.match('y') ? '?' : (format.match('Y') ? '!' : null));
            show[O] = (format.match('o') ? '?' : (format.match('O') ? '!' : null));
            show[W] = (format.match('w') ? '?' : (format.match('W') ? '!' : null));
            show[D] = (format.match('d') ? '?' : (format.match('D') ? '!' : null));
            show[H] = (format.match('h') ? '?' : (format.match('H') ? '!' : null));
            show[M] = (format.match('m') ? '?' : (format.match('M') ? '!' : null));
            show[S] = (format.match('s') ? '?' : (format.match('S') ? '!' : null));
            return show;
        },

        /* Calculate the requested periods between now and the target time.
         @param  inst         (object) the current settings for this instance
         @param  show         (string[7]) flags indicating which periods are requested/required
         @param  significant  (number) the number of periods with values to show, zero for all
         @param  now          (Date) the current date and time
         @return  (number[7]) the current time periods (always positive)
         by year, month, week, day, hour, minute, second */
        _calculatePeriods: function(inst, show, significant, now) {
            // Find endpoints
            inst._now = now;
            inst._now.setMilliseconds(0);
            var until = new Date(inst._now.getTime());
            if (inst._since) {
                if (now.getTime() < inst._since.getTime()) {
                    inst._now = now = until;
                }
                else {
                    now = inst._since;
                }
            }
            else {
                until.setTime(inst._until.getTime());
                if (now.getTime() > inst._until.getTime()) {
                    inst._now = now = until;
                }
            }
            // Calculate differences by period
            var periods = [0, 0, 0, 0, 0, 0, 0];
            if (show[Y] || show[O]) {
                // Treat end of months as the same
                var lastNow = plugin._getDaysInMonth(now.getFullYear(), now.getMonth());
                var lastUntil = plugin._getDaysInMonth(until.getFullYear(), until.getMonth());
                var sameDay = (until.getDate() == now.getDate() ||
                (until.getDate() >= Math.min(lastNow, lastUntil) &&
                now.getDate() >= Math.min(lastNow, lastUntil)));
                var getSecs = function(date) {
                    return (date.getHours() * 60 + date.getMinutes()) * 60 + date.getSeconds();
                };
                var months = Math.max(0,
                    (until.getFullYear() - now.getFullYear()) * 12 + until.getMonth() - now.getMonth() +
                    ((until.getDate() < now.getDate() && !sameDay) ||
                    (sameDay && getSecs(until) < getSecs(now)) ? -1 : 0));
                periods[Y] = (show[Y] ? Math.floor(months / 12) : 0);
                periods[O] = (show[O] ? months - periods[Y] * 12 : 0);
                // Adjust for months difference and end of month if necessary
                now = new Date(now.getTime());
                var wasLastDay = (now.getDate() == lastNow);
                var lastDay = plugin._getDaysInMonth(now.getFullYear() + periods[Y],
                    now.getMonth() + periods[O]);
                if (now.getDate() > lastDay) {
                    now.setDate(lastDay);
                }
                now.setFullYear(now.getFullYear() + periods[Y]);
                now.setMonth(now.getMonth() + periods[O]);
                if (wasLastDay) {
                    now.setDate(lastDay);
                }
            }
            var diff = Math.floor((until.getTime() - now.getTime()) / 1000);
            var extractPeriod = function(period, numSecs) {
                periods[period] = (show[period] ? Math.floor(diff / numSecs) : 0);
                diff -= periods[period] * numSecs;
            };
            extractPeriod(W, 604800);
            extractPeriod(D, 86400);
            extractPeriod(H, 3600);
            extractPeriod(M, 60);
            extractPeriod(S, 1);
            if (diff > 0 && !inst._since) { // Round up if left overs
                var multiplier = [1, 12, 4.3482, 7, 24, 60, 60];
                var lastShown = S;
                var max = 1;
                for (var period = S; period >= Y; period--) {
                    if (show[period]) {
                        if (periods[lastShown] >= max) {
                            periods[lastShown] = 0;
                            diff = 1;
                        }
                        if (diff > 0) {
                            periods[period]++;
                            diff = 0;
                            lastShown = period;
                            max = 1;
                        }
                    }
                    max *= multiplier[period];
                }
            }
            if (significant) { // Zero out insignificant periods
                for (var period = Y; period <= S; period++) {
                    if (significant && periods[period]) {
                        significant--;
                    }
                    else if (!significant) {
                        periods[period] = 0;
                    }
                }
            }
            return periods;
        }
    });

// The list of commands that return values and don't permit chaining
    var getters = ['getTimes'];

    /* Determine whether a command is a getter and doesn't permit chaining.
     @param  command    (string, optional) the command to run
     @param  otherArgs  ([], optional) any other arguments for the command
     @return  true if the command is a getter, false if not */
    function isNotChained(command, otherArgs) {
        if (command == 'option' && (otherArgs.length == 0 ||
            (otherArgs.length == 1 && typeof otherArgs[0] == 'string'))) {
            return true;
        }
        return $.inArray(command, getters) > -1;
    }

    /* Process the countdown functionality for a jQuery selection.
     @param  options  (object) the new settings to use for these instances (optional) or
     (string) the command to run (optional)
     @return  (jQuery) for chaining further calls or
     (any) getter value */
    $.fn.countdown = function(options) {
        var otherArgs = Array.prototype.slice.call(arguments, 1);
        if (isNotChained(options, otherArgs)) {
            return plugin['_' + options + 'Plugin'].
            apply(plugin, [this[0]].concat(otherArgs));
        }
        return this.each(function() {
            if (typeof options == 'string') {
                if (!plugin['_' + options + 'Plugin']) {
                    throw 'Unknown command: ' + options;
                }
                plugin['_' + options + 'Plugin'].
                apply(plugin, [this].concat(otherArgs));
            }
            else {
                plugin._attachPlugin(this, options || {});
            }
        });
    };

    /* Initialise the countdown functionality. */
    var plugin = $.countdown = new Countdown(); // Singleton instance

})(jQuery);

/*
 * timeout-dialog.js v1.0.1, 01-03-2012
 *
 * @author: Rodrigo Neri (@rigoneri)
 *
 * (The MIT License)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */


/* String formatting, you might want to remove this if you already use it.
 * Example:
 *
 * var location = 'World';
 * alert('Hello {0}'.format(location));
 */
String.prototype.format = function() {
    var s = this,
        i = arguments.length;

    while (i--) {
        s = s.replace(new RegExp('\\{' + i + '\\}', 'gm'), arguments[i]);
    }
    return s;
};

!function($) {
    $.timeoutDialog = function(options) {

        var settings = {
            timeout: 1200,
            countdown: 60,
            title : 'Your session is about to expire!',
            message : 'You will be logged out in {0} seconds.',
            question: 'Do you want to stay signed in?',
            keep_alive_button_text: 'Yes, Keep me signed in',
            sign_out_button_text: 'No, Sign me out',
            keep_alive_url: '/keep-alive.php',
            logout_url: null,
            logout_redirect_url: '/',
            restart_on_yes: true,
            dialog_width: 350
        }

        $.extend(settings, options);

        var TimeoutDialog = {
            init: function () {
                this.setupDialogTimer();
            },

            setupDialogTimer: function() {
                var self = this;
                window.setTimeout(function() {
                    self.setupDialog();
                }, (settings.timeout - settings.countdown) * 1000);
            },

            setupDialog: function() {
                var self = this;
                self.destroyDialog();

                //alert(settings.countdown);
                $('<div id="timeout-dialog"><div style="font-weight:bold;font-size:16px;padding-bottom:10px;" id="timeout-message"><center>'+settings.title +'</center></div>' +
                    '<div id="timeout-message">' + settings.message.format('<span id="timeout-countdown">' + settings.countdown + '</span>') + '</div>' +
                    '<div id="timeout-question">' + settings.question + '</div>' +
                    '</div>')
                    .appendTo('body')
                    .dialog({
                        modal: true,
                        width: settings.dialog_width+30,
                        minHeight: 'auto',
                        zIndex: 10000,

                        closeOnEscape: false,
                        draggable: false,
                        resizable: false,
                        dialogClass: 'timeout-dialog',
                        hide: "explode",
                        //  title: settings.title,
                        buttons : {
                            'keep-alive-button' : {
                                text: settings.keep_alive_button_text,
                                id: "timeout-keep-signin-btn",
                                click: function() {
                                    self.keepAlive();
                                }
                            },
                            'sign-out-button' : {
                                text: settings.sign_out_button_text,
                                id: "timeout-sign-out-button",
                                click: function() {
                                    self.signOut(true);
                                }
                            }
                        }
                    });
                $('.ui-dialog-titlebar').remove();
                $('.ui-dialog-buttonpane').removeAttr('class');
                self.startCountdown();
            },

            destroyDialog: function() {
                if ($("#timeout-dialog").length) {
                    //$(this).dialog("destroy");
                    $('#timeout-dialog').remove();
                }
            },

            startCountdown: function() {
                var self = this,
                    counter = settings.countdown;

                this.countdown = window.setInterval(function() {
                    counter -= 1;
                    $("#timeout-countdown").html(counter);

                    if (counter <= 0) {
                        window.clearInterval(self.countdown);
                        self.signOut(false);
                    }

                }, 1000);
            },

            keepAlive: function() {
                var self = this;
                this.destroyDialog();
                window.clearInterval(this.countdown);

                $.get(settings.keep_alive_url, function(data) {

                    if (data == "OK") {
                        if (settings.restart_on_yes) {
                            self.setupDialogTimer();
                        }
                    }
                    else {
                        self.signOut(false);
                    }
                });
            },

            signOut: function(is_forced) {
                var self = this;
                this.destroyDialog();

                if (settings.logout_url != null) {
                    $.post(settings.logout_url, function(data){
                        self.redirectLogout(is_forced);
                    });
                }
                else {
                    self.redirectLogout(is_forced);
                }
            },

            redirectLogout: function(is_forced){
                var target = settings.logout_redirect_url + '?next=' + encodeURIComponent(window.location.pathname + window.location.search);
                if (!is_forced)
                    target += '&timeout=t';
                window.location = target;
            }
        };

        TimeoutDialog.init();
    };
}(window.jQuery);


/* Modernizr 2.6.2 (Custom Build) | MIT & BSD
 * Build: http://modernizr.com/download/#-csstransforms3d-csstransitions-shiv-cssclasses-prefixed-teststyles-testprop-testallprops-prefixes-domprefixes-load
 */
;window.Modernizr=function(a,b,c){function z(a){j.cssText=a}function A(a,b){return z(m.join(a+";")+(b||""))}function B(a,b){return typeof a===b}function C(a,b){return!!~(""+a).indexOf(b)}function D(a,b){for(var d in a){var e=a[d];if(!C(e,"-")&&j[e]!==c)return b=="pfx"?e:!0}return!1}function E(a,b,d){for(var e in a){var f=b[a[e]];if(f!==c)return d===!1?a[e]:B(f,"function")?f.bind(d||b):f}return!1}function F(a,b,c){var d=a.charAt(0).toUpperCase()+a.slice(1),e=(a+" "+o.join(d+" ")+d).split(" ");return B(b,"string")||B(b,"undefined")?D(e,b):(e=(a+" "+p.join(d+" ")+d).split(" "),E(e,b,c))}var d="2.6.2",e={},f=!0,g=b.documentElement,h="modernizr",i=b.createElement(h),j=i.style,k,l={}.toString,m=" -webkit- -moz- -o- -ms- ".split(" "),n="Webkit Moz O ms",o=n.split(" "),p=n.toLowerCase().split(" "),q={},r={},s={},t=[],u=t.slice,v,w=function(a,c,d,e){var f,i,j,k,l=b.createElement("div"),m=b.body,n=m||b.createElement("body");if(parseInt(d,10))while(d--)j=b.createElement("div"),j.id=e?e[d]:h+(d+1),l.appendChild(j);return f=["&#173;",'<style id="s',h,'">',a,"</style>"].join(""),l.id=h,(m?l:n).innerHTML+=f,n.appendChild(l),m||(n.style.background="",n.style.overflow="hidden",k=g.style.overflow,g.style.overflow="hidden",g.appendChild(n)),i=c(l,a),m?l.parentNode.removeChild(l):(n.parentNode.removeChild(n),g.style.overflow=k),!!i},x={}.hasOwnProperty,y;!B(x,"undefined")&&!B(x.call,"undefined")?y=function(a,b){return x.call(a,b)}:y=function(a,b){return b in a&&B(a.constructor.prototype[b],"undefined")},Function.prototype.bind||(Function.prototype.bind=function(b){var c=this;if(typeof c!="function")throw new TypeError;var d=u.call(arguments,1),e=function(){if(this instanceof e){var a=function(){};a.prototype=c.prototype;var f=new a,g=c.apply(f,d.concat(u.call(arguments)));return Object(g)===g?g:f}return c.apply(b,d.concat(u.call(arguments)))};return e}),q.csstransforms3d=function(){var a=!!F("perspective");return a&&"webkitPerspective"in g.style&&w("@media (transform-3d),(-webkit-transform-3d){#modernizr{left:9px;position:absolute;height:3px;}}",function(b,c){a=b.offsetLeft===9&&b.offsetHeight===3}),a},q.csstransitions=function(){return F("transition")};for(var G in q)y(q,G)&&(v=G.toLowerCase(),e[v]=q[G](),t.push((e[v]?"":"no-")+v));return e.addTest=function(a,b){if(typeof a=="object")for(var d in a)y(a,d)&&e.addTest(d,a[d]);else{a=a.toLowerCase();if(e[a]!==c)return e;b=typeof b=="function"?b():b,typeof f!="undefined"&&f&&(g.className+=" "+(b?"":"no-")+a),e[a]=b}return e},z(""),i=k=null,function(a,b){function k(a,b){var c=a.createElement("p"),d=a.getElementsByTagName("head")[0]||a.documentElement;return c.innerHTML="x<style>"+b+"</style>",d.insertBefore(c.lastChild,d.firstChild)}function l(){var a=r.elements;return typeof a=="string"?a.split(" "):a}function m(a){var b=i[a[g]];return b||(b={},h++,a[g]=h,i[h]=b),b}function n(a,c,f){c||(c=b);if(j)return c.createElement(a);f||(f=m(c));var g;return f.cache[a]?g=f.cache[a].cloneNode():e.test(a)?g=(f.cache[a]=f.createElem(a)).cloneNode():g=f.createElem(a),g.canHaveChildren&&!d.test(a)?f.frag.appendChild(g):g}function o(a,c){a||(a=b);if(j)return a.createDocumentFragment();c=c||m(a);var d=c.frag.cloneNode(),e=0,f=l(),g=f.length;for(;e<g;e++)d.createElement(f[e]);return d}function p(a,b){b.cache||(b.cache={},b.createElem=a.createElement,b.createFrag=a.createDocumentFragment,b.frag=b.createFrag()),a.createElement=function(c){return r.shivMethods?n(c,a,b):b.createElem(c)},a.createDocumentFragment=Function("h,f","return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&("+l().join().replace(/\w+/g,function(a){return b.createElem(a),b.frag.createElement(a),'c("'+a+'")'})+");return n}")(r,b.frag)}function q(a){a||(a=b);var c=m(a);return r.shivCSS&&!f&&!c.hasCSS&&(c.hasCSS=!!k(a,"article,aside,figcaption,figure,footer,header,hgroup,nav,section{display:block}mark{background:#FF0;color:#000}")),j||p(a,c),a}var c=a.html5||{},d=/^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i,e=/^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i,f,g="_html5shiv",h=0,i={},j;(function(){try{var a=b.createElement("a");a.innerHTML="<xyz></xyz>",f="hidden"in a,j=a.childNodes.length==1||function(){b.createElement("a");var a=b.createDocumentFragment();return typeof a.cloneNode=="undefined"||typeof a.createDocumentFragment=="undefined"||typeof a.createElement=="undefined"}()}catch(c){f=!0,j=!0}})();var r={elements:c.elements||"abbr article aside audio bdi canvas data datalist details figcaption figure footer header hgroup mark meter nav output progress section summary time video",shivCSS:c.shivCSS!==!1,supportsUnknownElements:j,shivMethods:c.shivMethods!==!1,type:"default",shivDocument:q,createElement:n,createDocumentFragment:o};a.html5=r,q(b)}(this,b),e._version=d,e._prefixes=m,e._domPrefixes=p,e._cssomPrefixes=o,e.testProp=function(a){return D([a])},e.testAllProps=F,e.testStyles=w,e.prefixed=function(a,b,c){return b?F(a,b,c):F(a,"pfx")},g.className=g.className.replace(/(^|\s)no-js(\s|$)/,"$1$2")+(f?" js "+t.join(" "):""),e}(this,this.document),function(a,b,c){function d(a){return"[object Function]"==o.call(a)}function e(a){return"string"==typeof a}function f(){}function g(a){return!a||"loaded"==a||"complete"==a||"uninitialized"==a}function h(){var a=p.shift();q=1,a?a.t?m(function(){("c"==a.t?B.injectCss:B.injectJs)(a.s,0,a.a,a.x,a.e,1)},0):(a(),h()):q=0}function i(a,c,d,e,f,i,j){function k(b){if(!o&&g(l.readyState)&&(u.r=o=1,!q&&h(),l.onload=l.onreadystatechange=null,b)){"img"!=a&&m(function(){t.removeChild(l)},50);for(var d in y[c])y[c].hasOwnProperty(d)&&y[c][d].onload()}}var j=j||B.errorTimeout,l=b.createElement(a),o=0,r=0,u={t:d,s:c,e:f,a:i,x:j};1===y[c]&&(r=1,y[c]=[]),"object"==a?l.data=c:(l.src=c,l.type=a),l.width=l.height="0",l.onerror=l.onload=l.onreadystatechange=function(){k.call(this,r)},p.splice(e,0,u),"img"!=a&&(r||2===y[c]?(t.insertBefore(l,s?null:n),m(k,j)):y[c].push(l))}function j(a,b,c,d,f){return q=0,b=b||"j",e(a)?i("c"==b?v:u,a,b,this.i++,c,d,f):(p.splice(this.i++,0,a),1==p.length&&h()),this}function k(){var a=B;return a.loader={load:j,i:0},a}var l=b.documentElement,m=a.setTimeout,n=b.getElementsByTagName("script")[0],o={}.toString,p=[],q=0,r="MozAppearance"in l.style,s=r&&!!b.createRange().compareNode,t=s?l:n.parentNode,l=a.opera&&"[object Opera]"==o.call(a.opera),l=!!b.attachEvent&&!l,u=r?"object":l?"script":"img",v=l?"script":u,w=Array.isArray||function(a){return"[object Array]"==o.call(a)},x=[],y={},z={timeout:function(a,b){return b.length&&(a.timeout=b[0]),a}},A,B;B=function(a){function b(a){var a=a.split("!"),b=x.length,c=a.pop(),d=a.length,c={url:c,origUrl:c,prefixes:a},e,f,g;for(f=0;f<d;f++)g=a[f].split("="),(e=z[g.shift()])&&(c=e(c,g));for(f=0;f<b;f++)c=x[f](c);return c}function g(a,e,f,g,h){var i=b(a),j=i.autoCallback;i.url.split(".").pop().split("?").shift(),i.bypass||(e&&(e=d(e)?e:e[a]||e[g]||e[a.split("/").pop().split("?")[0]]),i.instead?i.instead(a,e,f,g,h):(y[i.url]?i.noexec=!0:y[i.url]=1,f.load(i.url,i.forceCSS||!i.forceJS&&"css"==i.url.split(".").pop().split("?").shift()?"c":c,i.noexec,i.attrs,i.timeout),(d(e)||d(j))&&f.load(function(){k(),e&&e(i.origUrl,h,g),j&&j(i.origUrl,h,g),y[i.url]=2})))}function h(a,b){function c(a,c){if(a){if(e(a))c||(j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}),g(a,j,b,0,h);else if(Object(a)===a)for(n in m=function(){var b=0,c;for(c in a)a.hasOwnProperty(c)&&b++;return b}(),a)a.hasOwnProperty(n)&&(!c&&!--m&&(d(j)?j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}:j[n]=function(a){return function(){var b=[].slice.call(arguments);a&&a.apply(this,b),l()}}(k[n])),g(a[n],j,b,n,h))}else!c&&l()}var h=!!a.test,i=a.load||a.both,j=a.callback||f,k=j,l=a.complete||f,m,n;c(h?a.yep:a.nope,!!i),i&&c(i)}var i,j,l=this.yepnope.loader;if(e(a))g(a,0,l,0);else if(w(a))for(i=0;i<a.length;i++)j=a[i],e(j)?g(j,0,l,0):w(j)?B(j):Object(j)===j&&h(j,l);else Object(a)===a&&h(a,l)},B.addPrefix=function(a,b){z[a]=b},B.addFilter=function(a){x.push(a)},B.errorTimeout=1e4,null==b.readyState&&b.addEventListener&&(b.readyState="loading",b.addEventListener("DOMContentLoaded",A=function(){b.removeEventListener("DOMContentLoaded",A,0),b.readyState="complete"},0)),a.yepnope=k(),a.yepnope.executeStack=h,a.yepnope.injectJs=function(a,c,d,e,i,j){var k=b.createElement("script"),l,o,e=e||B.errorTimeout;k.src=a;for(o in d)k.setAttribute(o,d[o]);c=j?h:c||f,k.onreadystatechange=k.onload=function(){!l&&g(k.readyState)&&(l=1,c(),k.onload=k.onreadystatechange=null)},m(function(){l||(l=1,c(1))},e),i?k.onload():n.parentNode.insertBefore(k,n)},a.yepnope.injectCss=function(a,c,d,e,g,i){var e=b.createElement("link"),j,c=i?h:c||f;e.href=a,e.rel="stylesheet",e.type="text/css";for(j in d)e.setAttribute(j,d[j]);g||(n.parentNode.insertBefore(e,n),m(c,0))}}(this,document),Modernizr.load=function(){yepnope.apply(window,[].slice.call(arguments,0))};

/**
 * jquery.bookblock.js v2.0.1
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 *
 * Copyright 2013, Codrops
 * http://www.codrops.com
 */

;( function( $, window, undefined ) {

    'use strict';

    // global
    var $window = $(window),
        Modernizr = window.Modernizr;

    // https://gist.github.com/edankwan/4389601
    Modernizr.addTest('csstransformspreserve3d', function () {
        var prop = Modernizr.prefixed('transformStyle');
        var val = 'preserve-3d';
        var computedStyle;
        if(!prop) return false;

        prop = prop.replace(/([A-Z])/g, function(str,m1){ return '-' + m1.toLowerCase(); }).replace(/^ms-/,'-ms-');

        Modernizr.testStyles('#modernizr{' + prop + ':' + val + ';}', function (el, rule) {
            computedStyle = window.getComputedStyle ? getComputedStyle(el, null).getPropertyValue(prop) : '';
        });

        return (computedStyle === val);
    });

    /*
     * debouncedresize: special jQuery event that happens once after a window resize
     *
     * latest version and complete README available on Github:
     * https://github.com/louisremi/jquery-smartresize
     *
     * Copyright 2012 @louis_remi
     * Licensed under the MIT license.
     *
     * This saved you an hour of work?
     * Send me music http://www.amazon.co.uk/wishlist/HNTU0468LQON
     */
    var $event = $.event,
        $special,
        resizeTimeout;

    $special = $event.special.debouncedresize = {
        setup: function() {
            $( this ).on( "resize", $special.handler );
        },
        teardown: function() {
            $( this ).off( "resize", $special.handler );
        },
        handler: function( event, execAsap ) {
            // Save the context
            var context = this,
                args = arguments,
                dispatch = function() {
                    // set correct event type
                    event.type = "debouncedresize";
                    $event.dispatch.apply( context, args );
                };

            if ( resizeTimeout ) {
                clearTimeout( resizeTimeout );
            }

            execAsap ?
                dispatch() :
                resizeTimeout = setTimeout( dispatch, $special.threshold );
        },
        threshold: 150
    };

    $.BookBlock = function( options, element ) {
        this.$el = $( element );
        this._init( options );
    };

    // the options
    $.BookBlock.defaults = {
        // vertical or horizontal flip
        orientation : 'vertical',
        // ltr (left to right) or rtl (right to left)
        direction : 'ltr',
        // speed for the flip transition in ms
        speed : 1000,
        // easing for the flip transition
        easing : 'ease-in-out',
        // if set to true, both the flipping page and the sides will have an overlay to simulate shadows
        shadows : true,
        // opacity value for the "shadow" on both sides (when the flipping page is over it)
        // value : 0.1 - 1
        shadowSides : 0.2,
        // opacity value for the "shadow" on the flipping page (while it is flipping)
        // value : 0.1 - 1
        shadowFlip : 0.1,
        // if we should show the first item after reaching the end
        circular : false,
        // if we want to specify a selector that triggers the next() function. example: ´#bb-nav-next´
        nextEl : '',
        // if we want to specify a selector that triggers the prev() function
        prevEl : '',
        // autoplay. If true it overwrites the circular option to true
        autoplay : false,
        // time (ms) between page switch, if autoplay is true
        interval : 3000,
        // callback after the flip transition
        // old is the index of the previous item
        // page is the current item´s index
        // isLimit is true if the current page is the last one (or the first one)
        onEndFlip : function(old, page, isLimit) { return false; },
        // callback before the flip transition
        // page is the current item´s index
        onBeforeFlip : function(page) { return false; }
    };

    $.BookBlock.prototype = {
        _init : function(options) {
            // options
            this.options = $.extend( true, {}, $.BookBlock.defaults, options );
            // orientation class
            this.$el.addClass( 'bb-' + this.options.orientation );
            // items
            this.$items = this.$el.children( '.bb-item' ).hide();
            // total items
            this.itemsCount = this.$items.length;
            // current item´s index
            this.current = 0;
            // previous item´s index
            this.previous = -1;
            // show first item
            this.$current = this.$items.eq( this.current ).show();
            // get width of this.$el
            // this will be necessary to create the flipping layout
            this.elWidth = this.$el.width();
            var transEndEventNames = {
                'WebkitTransition': 'webkitTransitionEnd',
                'MozTransition': 'transitionend',
                'OTransition': 'oTransitionEnd',
                'msTransition': 'MSTransitionEnd',
                'transition': 'transitionend'
            };
            this.transEndEventName = transEndEventNames[Modernizr.prefixed( 'transition' )] + '.bookblock';
            // support css 3d transforms && css transitions && Modernizr.csstransformspreserve3d
            this.support = Modernizr.csstransitions && Modernizr.csstransforms3d && Modernizr.csstransformspreserve3d;
            // initialize/bind some events
            this._initEvents();
            // start slideshow
            if ( this.options.autoplay ) {
                this.options.circular = true;
                this._startSlideshow();
            }
        },
        _initEvents : function() {

            var self = this;

            if ( this.options.nextEl !== '' ) {
                $( this.options.nextEl ).on( 'click.bookblock touchstart.bookblock', function() { self._action( 'next' ); return false; } );
            }

            if ( this.options.prevEl !== '' ) {
                $( this.options.prevEl ).on( 'click.bookblock touchstart.bookblock', function() { self._action( 'prev' ); return false; } );
            }

            $window.on( 'debouncedresize', function() {
                // update width value
                self.elWidth = self.$el.width();
            } );

        },
        _action : function( dir, page ) {
            this._stopSlideshow();
            this._navigate( dir, page );
        },
        _navigate : function( dir, page ) {

            if ( this.isAnimating ) {
                return false;
            }

            // callback trigger
            this.options.onBeforeFlip( this.current );

            this.isAnimating = true;
            // update current value
            this.$current = this.$items.eq( this.current );

            if ( page !== undefined ) {
                this.current = page;
            }
            else if ( dir === 'next' && this.options.direction === 'ltr' || dir === 'prev' && this.options.direction === 'rtl' ) {
                if ( !this.options.circular && this.current === this.itemsCount - 1 ) {
                    this.end = true;
                }
                else {
                    this.previous = this.current;
                    this.current = this.current < this.itemsCount - 1 ? this.current + 1 : 0;
                }
            }
            else if ( dir === 'prev' && this.options.direction === 'ltr' || dir === 'next' && this.options.direction === 'rtl' ) {
                if ( !this.options.circular && this.current === 0 ) {
                    this.end = true;
                }
                else {
                    this.previous = this.current;
                    this.current = this.current > 0 ? this.current - 1 : this.itemsCount - 1;
                }
            }

            this.$nextItem = !this.options.circular && this.end ? this.$current : this.$items.eq( this.current );

            if ( !this.support ) {
                this._layoutNoSupport( dir );
            } else {
                this._layout( dir );
            }

        },
        _layoutNoSupport : function(dir) {
            this.$items.hide();
            this.$nextItem.show();
            this.end = false;
            this.isAnimating = false;
            var isLimit = dir === 'next' && this.current === this.itemsCount - 1 || dir === 'prev' && this.current === 0;
            // callback trigger
            this.options.onEndFlip( this.previous, this.current, isLimit );
        },
        // creates the necessary layout for the 3d structure
        _layout : function(dir) {

            var self = this,
                // basic structure: 1 element for the left side.
                $s_left = this._addSide( 'left', dir ),
                // 1 element for the flipping/middle page
                $s_middle = this._addSide( 'middle', dir ),
                // 1 element for the right side
                $s_right = this._addSide( 'right', dir ),
                // overlays
                $o_left = $s_left.find( 'div.bb-overlay' ),
                $o_middle_f = $s_middle.find( 'div.bb-flipoverlay:first' ),
                $o_middle_b = $s_middle.find( 'div.bb-flipoverlay:last' ),
                $o_right = $s_right.find( 'div.bb-overlay' ),
                speed = this.end ? 400 : this.options.speed;

            this.$items.hide();
            this.$el.prepend( $s_left, $s_middle, $s_right );

            $s_middle.css({
                transitionDuration: speed + 'ms',
                transitionTimingFunction : this.options.easing
            }).on( this.transEndEventName, function( event ) {
                if ( $( event.target ).hasClass( 'bb-page' ) ) {
                    self.$el.children( '.bb-page' ).remove();
                    self.$nextItem.show();
                    self.end = false;
                    self.isAnimating = false;
                    var isLimit = dir === 'next' && self.current === self.itemsCount - 1 || dir === 'prev' && self.current === 0;
                    // callback trigger
                    self.options.onEndFlip( self.previous, self.current, isLimit );
                }
            });

            if ( dir === 'prev' ) {
                $s_middle.addClass( 'bb-flip-initial' );
            }

            // overlays
            if (this.options.shadows && !this.end) {

                var o_left_style = (dir === 'next') ? {
                            transition: 'opacity ' + this.options.speed / 2 + 'ms ' + 'linear' + ' ' + this.options.speed / 2 + 'ms'
                        } : {
                            transition: 'opacity ' + this.options.speed / 2 + 'ms ' + 'linear',
                            opacity: this.options.shadowSides
                        },
                    o_middle_f_style = (dir === 'next') ? {
                            transition: 'opacity ' + this.options.speed / 2 + 'ms ' + 'linear'
                        } : {
                            transition: 'opacity ' + this.options.speed / 2 + 'ms ' + 'linear' + ' ' + this.options.speed / 2 + 'ms',
                            opacity: this.options.shadowFlip
                        },
                    o_middle_b_style = (dir === 'next') ? {
                            transition: 'opacity ' + this.options.speed / 2 + 'ms ' + 'linear' + ' ' + this.options.speed / 2 + 'ms',
                            opacity: this.options.shadowFlip
                        } : {
                            transition: 'opacity ' + this.options.speed / 2 + 'ms ' + 'linear'
                        },
                    o_right_style = (dir === 'next') ? {
                            transition: 'opacity ' + this.options.speed / 2 + 'ms ' + 'linear',
                            opacity: this.options.shadowSides
                        } : {
                            transition: 'opacity ' + this.options.speed / 2 + 'ms ' + 'linear' + ' ' + this.options.speed / 2 + 'ms'
                        };

                $o_middle_f.css(o_middle_f_style);
                $o_middle_b.css(o_middle_b_style);
                $o_left.css(o_left_style);
                $o_right.css(o_right_style);

            }

            setTimeout( function() {
                // first && last pages lift slightly up when we can't go further
                $s_middle.addClass( self.end ? 'bb-flip-' + dir + '-end' : 'bb-flip-' + dir );

                // overlays
                if ( self.options.shadows && !self.end ) {

                    $o_middle_f.css({
                        opacity: dir === 'next' ? self.options.shadowFlip : 0
                    });

                    $o_middle_b.css({
                        opacity: dir === 'next' ? 0 : self.options.shadowFlip
                    });

                    $o_left.css({
                        opacity: dir === 'next' ? self.options.shadowSides : 0
                    });

                    $o_right.css({
                        opacity: dir === 'next' ? 0 : self.options.shadowSides
                    });

                }
            }, 25 );
        },
        // adds the necessary sides (bb-page) to the layout
        _addSide : function( side, dir ) {
            var $side;

            switch (side) {
                case 'left':
                    /*
                     <div class="bb-page" style="z-index:102;">
                     <div class="bb-back">
                     <div class="bb-outer">
                     <div class="bb-content">
                     <div class="bb-inner">
                     dir==='next' ? [content of current page] : [content of next page]
                     </div>
                     </div>
                     <div class="bb-overlay"></div>
                     </div>
                     </div>
                     </div>
                     */
                    $side = $('<div class="bb-page"><div class="bb-back"><div class="bb-outer"><div class="bb-content"><div class="bb-inner">' + ( dir === 'next' ? this.$current.html() : this.$nextItem.html() ) + '</div></div><div class="bb-overlay"></div></div></div></div>').css( 'z-index', 102 );
                    break;
                case 'middle':
                    /*
                     <div class="bb-page" style="z-index:103;">
                     <div class="bb-front">
                     <div class="bb-outer">
                     <div class="bb-content">
                     <div class="bb-inner">
                     dir==='next' ? [content of current page] : [content of next page]
                     </div>
                     </div>
                     <div class="bb-flipoverlay"></div>
                     </div>
                     </div>
                     <div class="bb-back">
                     <div class="bb-outer">
                     <div class="bb-content">
                     <div class="bb-inner">
                     dir==='next' ? [content of next page] : [content of current page]
                     </div>
                     </div>
                     <div class="bb-flipoverlay"></div>
                     </div>
                     </div>
                     </div>
                     */
                    $side = $('<div class="bb-page"><div class="bb-front"><div class="bb-outer"><div class="bb-content"><div class="bb-inner">' + (dir === 'next' ? this.$current.html() : this.$nextItem.html()) + '</div></div><div class="bb-flipoverlay"></div></div></div><div class="bb-back"><div class="bb-outer"><div class="bb-content" style="width:' + this.elWidth + 'px"><div class="bb-inner">' + ( dir === 'next' ? this.$nextItem.html() : this.$current.html() ) + '</div></div><div class="bb-flipoverlay"></div></div></div></div>').css( 'z-index', 103 );
                    break;
                case 'right':
                    /*
                     <div class="bb-page" style="z-index:101;">
                     <div class="bb-front">
                     <div class="bb-outer">
                     <div class="bb-content">
                     <div class="bb-inner">
                     dir==='next' ? [content of next page] : [content of current page]
                     </div>
                     </div>
                     <div class="bb-overlay"></div>
                     </div>
                     </div>
                     </div>
                     */
                    $side = $('<div class="bb-page"><div class="bb-front"><div class="bb-outer"><div class="bb-content"><div class="bb-inner">' + ( dir === 'next' ? this.$nextItem.html() : this.$current.html() ) + '</div></div><div class="bb-overlay"></div></div></div></div>').css( 'z-index', 101 );
                    break;
            }

            return $side;
        },
        _startSlideshow : function() {
            var self = this;
            this.slideshow = setTimeout( function() {
                self._navigate( 'next' );
                if ( self.options.autoplay ) {
                    self._startSlideshow();
                }
            }, this.options.interval );
        },
        _stopSlideshow : function() {
            if ( this.options.autoplay ) {
                clearTimeout( this.slideshow );
                this.options.autoplay = false;
            }
        },
        // public method: flips next
        next : function() {
            this._action( this.options.direction === 'ltr' ? 'next' : 'prev' );
        },
        // public method: flips back
        prev : function() {
            this._action( this.options.direction === 'ltr' ? 'prev' : 'next' );
        },
        // public method: goes to a specific page
        jump : function( page ) {

            page -= 1;

            if ( page === this.current || page >= this.itemsCount || page < 0 ) {
                return false;
            }

            var dir;
            if( this.options.direction === 'ltr' ) {
                dir = page > this.current ? 'next' : 'prev';
            }
            else {
                dir = page > this.current ? 'prev' : 'next';
            }
            this._action( dir, page );

        },
        // public method: goes to the last page
        last : function() {
            this.jump( this.itemsCount );
        },
        // public method: goes to the first page
        first : function() {
            this.jump( 1 );
        },
        // public method: check if isAnimating is true
        isActive: function() {
            return this.isAnimating;
        },
        // public method: dynamically adds new elements
        // call this method after inserting new "bb-item" elements inside the BookBlock
        update : function () {
            var $currentItem = this.$items.eq( this.current );
            this.$items = this.$el.children( '.bb-item' );
            this.itemsCount = this.$items.length;
            this.current = $currentItem.index();
        },
        destroy : function() {
            if ( this.options.autoplay ) {
                this._stopSlideshow();
            }
            this.$el.removeClass( 'bb-' + this.options.orientation );
            this.$items.show();

            if ( this.options.nextEl !== '' ) {
                $( this.options.nextEl ).off( '.bookblock' );
            }

            if ( this.options.prevEl !== '' ) {
                $( this.options.prevEl ).off( '.bookblock' );
            }

            $window.off( 'debouncedresize' );
        }
    }

    var logError = function( message ) {
        if ( window.console ) {
            window.console.error( message );
        }
    };

    $.fn.bookblock = function( options ) {
        if ( typeof options === 'string' ) {
            var args = Array.prototype.slice.call( arguments, 1 );
            this.each(function() {
                var instance = $.data( this, 'bookblock' );
                if ( !instance ) {
                    logError( "cannot call methods on bookblock prior to initialization; " +
                        "attempted to call method '" + options + "'" );
                    return;
                }
                if ( !$.isFunction( instance[options] ) || options.charAt(0) === "_" ) {
                    logError( "no such method '" + options + "' for bookblock instance" );
                    return;
                }
                instance[ options ].apply( instance, args );
            });
        }
        else {
            this.each(function() {
                var instance = $.data( this, 'bookblock' );
                if ( instance ) {
                    instance._init();
                }
                else {
                    instance = $.data( this, 'bookblock', new $.BookBlock( options, this ) );
                }
            });
        }
        return this;
    };

} )( jQuery, window );