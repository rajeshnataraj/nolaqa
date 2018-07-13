

function fn_showstate() {

    $('#loadstate').show();
    $('#dpdocuments').hide();
    $('#divdocgrades').hide();
    $('#loadstandards').hide();
    $('#innerstandard').hide();

    var dataparam = "oper=showstate";

    $.ajax({
        type: 'post',
        url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
        data: dataparam,
        success: function(data) {
            $('#loadstate').html(data);//Used to load the student details in the dropdown
        }
    });

}
/*----
 fn_showdocuments()
 Function to load documents from AB API
 stid -> State Id
 ----*/
function fn_showdocuments(stid)
{
    $('#divdocgrades').hide();
    $('#loadstandards').hide();
    $('#innerstandard').hide();

    var dataparam = "oper=showdocuments&stid=" + stid;
    $.ajax({
        type: 'post',
        url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
        data: dataparam,
        success: function(data) {
            $('#dpdocuments').show();
            $('#dpdocuments').html(data);//Used to load the student details in the dropdown
        }
    });
}

/*----
 fn_showgrades()
 Function to load grades from AB API
 stid -> State Id
 ----*/
function fn_showgrades(subid)
{
    $('#loadstandards').hide();
    $('#innerstandard').hide();
    var dataparam = "oper=showgrades&subid=" + subid;
    $.ajax({
        type: 'post',
        url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
        data: dataparam,
        success: function(data) {
            $('#divdocgrades').show();
            $('#divdocgrades').html(data);//Used to load the student details in the dropdown
        }
    });
}
//function fn_showstandards(gradeid)
//{
//   
//    $('#loadstandards').show();
//    $('#innerstandard').hide(); 
//      $('#loadtitles').hide();
//    $('#loadproducts').hide();
//    var dataparam = "oper=showstandrads&gradeids="+gradeid;
//    $.ajax({
//           type: 'post',
//           url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
//           data: dataparam,
//           success:function(data) {
//                $('#loadstandards').html(data);//Used to load the student details in the dropdown
//           }
//    });
//}
function fn_showinnerstandards(gradeid) {
    $('#innerstandard').show();
    var dataparam = "oper=showinnerstandrads&gradeids=" + gradeid;
    $.ajax({
        type: 'post',
        url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
        data: dataparam,
		beforeSend: function() {
            $('#loadinnerstandards').html('<img src="img/loader.gif" width="200"  border="0" />');
        },
        success: function(data) {

            $('#loadinnerstandards').html(data);//Used to load the student details in the dropdown
        }
    });
}
function fn_showtitles()
{

    var guid = [];
    $("input:checked").each(function() {
        var pid = $(this).val();
        guid.push(pid);
    });
    $('#loadproducts').hide();
    var dataparam = "oper=showtitles";
    $.ajax({
        type: 'post',
        url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
        data: dataparam,
        success: function(data)
        {
            $('#loadtitles').show();
            $('#loadtitles').html(data);

//                if(guid==0)
//                {
//                    $('#loadtitles').hide();
//                }
//                else if(guid!='' || guid=='on')
//                {
//                    $('#loadtitles').show();
//                    $('#loadtitles').html(data);//Used to load the student details in the dropdown
//                }
        }
    });

}
/*----
 fn_saveselect()
 Function to save the selected products
 ----*/
function fn_showproducts(type)
{
    if (type != 5) {
        $('#destinationdiv').hide();
        $('#taskdiv').hide();
        $('#loadresource').hide();
    }
    var dataparam = "oper=showproducts&type=" + type;

    $.ajax({
        type: 'post',
        url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
        data: dataparam,
        success: function(data) {
            $('#loadproducts').show();
            $('#loadproducts').html(data);//Used to load the student details in the dropdown
        }
    });
}






/************Expedition code start here**************/

/*function fn_showdestination(productids)
{

    var dataparam = "oper=showdestination&expid=" + productids;
    // alert(dataparam);
    $.ajax({
        type: 'post',
        url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
        data: dataparam,
        success: function(data) {
            // alert(data);
            $('#destinationdiv').show();
            $('#destinationdiv').html(data);
        }
    });
}

function fn_showtasks()
{
    var destids = [];

    $("div[id^=list10_]").each(function()
    {
        var guid = $(this).attr('id').replace('list10_', '');
        destids.push(guid);

    });

    var dataparam = "oper=showtasks&destids=" + destids;
    $.ajax({
        type: 'post',
        url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
        data: dataparam,
        success: function(data) {
            $('#taskdiv').html(data);//Used to load the student details in the dropdown
        }
    });
}



function fn_showresource()
{
    var taskids = [];

    $("div[id^=list12_]").each(function()
    {
        var guid = $(this).attr('id').replace('list12_', '');
        taskids.push(guid);

    });

    $('#taskresource').show();

    var dataparam = "oper=showresource&taskids=" + taskids;
    $.ajax({
        type: 'post',
        url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
        data: dataparam,
        success: function(data) {
            $('#loadresource').show();
            $('#loadresource').html(data);//Used to load the student details in the dropdown
        }
    });
}
*/
/*----
 fn_saveselect()
 Function to save the selected products
 ----*/
function fn_saveselect(alignmenttype) {


    var prdtypes = [];
    var dataparam;

    var guids = [];
    $("input:checkbox[name=deepinnerid]:checked").each(function() {
        var pid = $(this).val();
        guids.push(pid);
    });

    $("div[id^=list6_]").each(function()
    {
        var guid = $(this).attr('id').replace('list6_', '');
        prdtypes.push(guid);

    });

    var prdids = [];
    $("div[id^=list8_]").each(function()
    {
        var guid = $(this).attr('id').replace('list8_', '');
        prdids.push(guid);
    });
    var resprdids = [];
    $("div[id^=list12_]").each(function()
    {
        var guid = $(this).attr('id').replace('list12_', '');
        resprdids.push(guid);
    });

    for (var j = 0; j < prdids.length; j++)
    {
        var prdidtyp = prdids[j];
        var typeid = prdidtyp.split('_');

        var expprdids = [];
        var produids = [];
        /*if (typeid[1] == 5)
        {
            var deastprdids = [];
            $("div[id^=list10_]").each(function()
            {
                var guid = $(this).attr('id').replace('list10_', '');
                deastprdids.push(guid);
            });
            var taskprdids = [];
            $("div[id^=list12_]").each(function()
            {
                var guid = $(this).attr('id').replace('list12_', '');
                taskprdids.push(guid);
            });
            var resprdids = [];
            $("div[id^=list12_]").each(function()
            {
                var guid = $(this).attr('id').replace('list12_', '');
                resprdids.push(guid);
            });
            //alert(resprdids);
            var guids = [];
            $("input:checkbox[name=deepinnerid]:checked").each(function() {
                var pid = $(this).val();
                guids.push(pid);
            });
            alert(guids);
            var stateid = $('#stateid').val();
            var documentid = $('#documentsubid').val();
            var grades = $('#grades').val();

            if (stateid == '')
            {
                $.Zebra_Dialog("Please Select State", {'type': 'information', 'buttons': false, 'auto_close': 1000});

                return false;
            }
            if (documentid == '')
            {
                $.Zebra_Dialog("Please Select Documents", {'type': 'information', 'buttons': false, 'auto_close': 1000});

                return false;
            }
            if (grades == '')
            {
                $.Zebra_Dialog("Please Select Grades", {'type': 'information', 'buttons': false, 'auto_close': 1000});

                return false;
            }
            if (guids == '')
            {
                $.Zebra_Dialog("Please Select Checkbox to Make Alignment", {'type': 'information', 'buttons': false, 'auto_close': 1000});

                return false;
            }
            if (prdtypes == '')
            {
                $.Zebra_Dialog("Please Select Titles", {'type': 'information', 'buttons': false, 'auto_close': 1000});

                return false;
            }
            if (prdids == '')
            {
                $.Zebra_Dialog("Please Select Product", {'type': 'information', 'buttons': false, 'auto_close': 1000});

                return false;
            }
            if (deastprdids == '')
            {
                $.Zebra_Dialog("Please Select Destination", {'type': 'information', 'buttons': false, 'auto_close': 1000});

                return false;
            }
            if (taskprdids == '')
            {
                $.Zebra_Dialog("Please Select Task", {'type': 'information', 'buttons': false, 'auto_close': 1000});

                return false;
            }
            if (resprdids == '')
            {
                $.Zebra_Dialog("Please Select Resource", {'type': 'information', 'buttons': false, 'auto_close': 1000});

                return false;
            }
        }
        else
        {*/

            var guids = [];
            $("input:checkbox[name=deepinnerid]:checked").each(function() {
                var pid = $(this).val();
                guids.push(pid);
            });
//alert(guids);
            var stateid = $('#stateid').val();
            var documentid = $('#documentsubid').val();
            var grades = $('#grades').val();

            if (stateid == '')
            {
                $.Zebra_Dialog("Please Select State", {'type': 'information', 'buttons': false, 'auto_close': 1000});

                return false;
            }
            if (documentid == '')
            {
                $.Zebra_Dialog("Please Select Documents", {'type': 'information', 'buttons': false, 'auto_close': 1000});

                return false;
            }
            if (grades == '')
            {
                $.Zebra_Dialog("Please Select Grades", {'type': 'information', 'buttons': false, 'auto_close': 1000});

                return false;
            }
            if (guids == '')
            {
                $.Zebra_Dialog("Please Select Checkbox to Make Alignment", {'type': 'information', 'buttons': false, 'auto_close': 1000});

                return false;
            }
            if (prdtypes == '')
            {
                $.Zebra_Dialog("Please Select Titles", {'type': 'information', 'buttons': false, 'auto_close': 1000});

                return false;
            }
            if (prdids == '')
            {
                $.Zebra_Dialog("Please Select Product", {'type': 'information', 'buttons': false, 'auto_close': 1000});

                return false;
            }
        //} // else ends 

    } // for ends

    dataparam = "oper=makecorrelation&ptype=" + prdtypes + "&guids=" + guids + "&productid=" + prdids + "&resid=" + resprdids;
    //alert(dataparam);
    $.ajax({
        type: 'post',
        url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
        data: dataparam,
        beforeSend: function()
        {
            showloadingalert('Loading, please wait...');
        },
        success: function(data) {

            var employeeData = JSON.parse(data);
            fn_createalignment(employeeData, alignmenttype);
        }
    });

}

function fn_createalignment(stdassetid, alignmenttype) {
    //alert(stdassetid);

    var stdlen = stdassetid.length;

    for (var i = 0; i < stdassetid.length; i++) {

        var assetseparation = stdassetid[i].split("~");

        var dataparam = "oper=correlationsignature&passetguid=" + assetseparation[1] + "&standguids=" + assetseparation[0] + "&icnt=" + i + "&ptype=" + assetseparation[2] + "&prdid=" + assetseparation[3];
        $.ajax({
            type: 'post',
            url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
            data: dataparam,
            success: function(data) {

                var icount = data.split("~");
                var ival = icount[0];
                var apiurl = icount[1];
                var ptype = icount[2];
                var prdid = icount[3];
                var standardid = icount[4];
                var prdassetid = icount[5];
                var stateid = $('#stateid').val();
                var documentid = $('#documentsubid').val();
                var grades = $('#grades').val();

                var guid = [];
                $("input:checkbox[name=deepinnerid]:checked").each(function() {
                    var pid = $(this).val();
                    guid.push(pid);
                });
                if (alignmenttype === 1) // create alignment
                {
                    fn_alignmentcompleted(apiurl, ival, stdlen, ptype, prdid, stateid, documentid, grades, standardid, prdassetid); // 
                }
                else if (alignmenttype === 2) // delete alignment
                {
                    fn_alignmentdeleted(apiurl, ival, stdlen, ptype, prdid, stateid, documentid, grades, standardid, prdassetid, alignmenttype);
                }

            }
        });
    }
}

function fn_alignmentcompleted(apicall, ival, stdlen, ptype, prdid, stateid, documentid, grades, standardid, prdassetid) {
    var tot = stdlen - 1;
    //alert(tot+"    "+ival);
    $.ajax({
        type: 'post',
        url: apicall,
        dataType: "json",
        success: function(data) {

            closeloadingalert();
            fn_savealignment(ptype, prdid, stateid, documentid, grades, standardid, prdassetid, ival, stdlen);
        }
    }).fail(function($xhr) {

        if ($xhr.status == 409)
        {
            if (ival == tot) {
                closeloadingalert();
                $.Zebra_Dialog("Alignment Exists ! \n\
                                            Please change the parameters and create a new one or exclude the existing one.", {'type': 'information', 'buttons': false, 'auto_close': 4500});
                return false;
            }
        }

    });


}

function fn_alignmentdeleted(apicall, ival, stdlen, ptype, prdid, stateid, documentid, grades, standardid, prdassetid, alignmenttype) {
    var tot = stdlen - 1;

    $.ajax({
        type: 'DELETE',
        url: apicall,
        dataType: "json",
        success: function(data) {
            closeloadingalert();
            fn_savedeletedalignment(ptype, prdid, stateid, documentid, grades, standardid, prdassetid, ival, stdlen, alignmenttype);
        }
    }).fail(function($xhr) {

        closeloadingalert();

    });


}

function fn_savealignment(ptype, prdid, stateid, documentid, grades, standardid, prdassetid, ival, stdlen, alignmenttype)
{
    var tot = stdlen - 1;
    var dataparam = "oper=savealignment&ptype=" + ptype + "&prdid=" + prdid + "&stateid=" + stateid + "&documentid=" + documentid + "&grades=" + grades + "&guid=" + standardid + "&prdassetid=" + prdassetid + "&alignmenttype=" + alignmenttype;

    $.ajax({
        type: 'post',
        url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
        data: dataparam,
        success: function()
        {
            if (ival == tot)
            {
                $.Zebra_Dialog("Alignment Created Successfully !", {'type': 'information', 'buttons': false, 'auto_close': 1000});
                return false;
            }
        }
    });
}

function fn_savedeletedalignment(ptype, prdid, stateid, documentid, grades, standardid, prdassetid, ival, stdlen, alignmenttype)
{
    var tot = stdlen - 1;
    var dataparam = "oper=savealignment&ptype=" + ptype + "&prdid=" + prdid + "&stateid=" + stateid + "&documentid=" + documentid + "&grades=" + grades + "&guid=" + standardid + "&prdassetid=" + prdassetid + "&alignmenttype=" + alignmenttype;

    $.ajax({
        type: 'post',
        url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
        data: dataparam,
        success: function()
        {
            if (ival == tot)
            {
                $.Zebra_Dialog("Alignment Excluded !", {'type': 'information', 'buttons': false, 'auto_close': 1000});
                return false;
            }
        }
    });
}

function fn_checkall() {
    $('#selecctall').click(function(event) {  //on click
        if (this.checked) { // check select status
            $('.checkbox-class').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"              
            });
        } else {
            $('.checkbox-class').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                      
            });
        }
    });

}

/*----
 fn_movealllistitems()
 Function to move all items from lest to right and right to left
 ----*/
/*function fn_movealllistitems(leftlist, rightlist, id, courseid)
 {
 
 if (id == 0)
 {
 $("div[id^=" + leftlist + "_]").each(function()
 {
 var clas = $(this).attr('class');
 var temp = $(this).attr('id').replace(leftlist, rightlist);
 
 $(this).attr('id', temp);
 $('#' + rightlist).append($(this));
 
 if ($(this).attr('class') == 'draglinkleft') {
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
 var clas = $('#' + leftlist + '_' + courseid).attr('class');
 
 if (clas == "draglinkleft")
 {
 $('#' + rightlist).append($('#' + leftlist + ' #' + leftlist + '_' + courseid));
 $('#' + leftlist + '_' + courseid).removeClass('draglinkleft').addClass('draglinkright');
 
 var temp = $('#' + leftlist + '_' + courseid).attr('id').replace(leftlist, rightlist);
 var ids = 'id';
 $('#' + leftlist + '_' + courseid).attr(ids, temp);
 }
 else
 {
 $('#' + leftlist).append($('#' + rightlist + ' #' + rightlist + '_' + courseid));
 $('#' + rightlist + '_' + courseid).removeClass('draglinkright').addClass('draglinkleft');
 
 var temp = $('#' + rightlist + '_' + courseid).attr('id').replace(rightlist, leftlist);
 var ids = 'id';
 $('#' + rightlist + '_' + courseid).attr(ids, temp);
 }
 }
 
 if (leftlist == "list5" || leftlist == "list6" && rightlist == "list6" || rightlist == "list5")
 {
 var typeids = [];
 $("div[id^=list6_]").each(function()
 {
 var guid = $(this).attr('id').replace('list6_', '');
 typeids.push(guid);
 
 
 });
 fn_showproducts(typeids);
 }
 if (leftlist == "list7" || leftlist == "list8" && rightlist == "list8" || rightlist == "list7")
 {
 
 var typeid = courseid.split('_');
 
 if (typeid[1] == 5)
 {
 fn_showdestination(courseid);
 }
 else
 {
 fn_showstate();
 }
 }
 if (leftlist == "list9" || leftlist == "list10" && rightlist == "list10" || rightlist == "list9")
 {
 //$('#viewreportdiv').show();
 $('#taskdiv').show();
 fn_showtasks();
 }
 if (leftlist == "list11" || leftlist == "list12" && rightlist == "list12" || rightlist == "list11")
 {
 // $('#viewreportdiv').show();
 $('#resourcediv').show();
 fn_showresource();
 }
 if (leftlist == "list13" || leftlist == "list14" && rightlist == "list14" || rightlist == "list13")
 {
 fn_showstate();
 }
 }
 */

/*----
 ------------------------------------------------------------------------------------------------------------------------------------------------------------------
 ----*/
function fn_newclass(tid, titletype)
{
    var dataparam = "oper=newclassnameform&titleid=" + tid + "&titletype=" + titletype;

    $.fancybox.showActivity();
    $.ajax({
        type: "POST",
        cache: false,
        url: "tools/correlation/tools-correlation-correlationtoolajax.php",
        data: dataparam,
        success: function(data) {
            $.fancybox(data, {'modal': true, 'autoDimensions': false, 'width': 480, 'autoScale': true, 'height': 200, 'scrolling': 'no'});
            $.fancybox.resize();
        }
    });

    return false;

}

function fn_cancelclassform()
{
    $.fancybox.close();
}

function fn_saveclassform(tid)
{
    if ($("#classnameextendforms").validate().form())
    {

        var classnametxt = $('#txtclassname').val();
        $.ajax({
            type: "POST",
            cache: false,
            url: "tools/correlation/tools-correlation-correlationtoolajax.php",
            data: "oper=saveclasstxt&classnametxt=" + escapestr(classnametxt) + "&tid=" + tid,
            success: function(data) {

                if (data == 'fail')
                {
                    //showloadingalert("Either change the module name or module version number.");
                    //setTimeout('closeloadingalert()', 2000);
                    return false;
                }
                else if (data == 'success')
                {
                    fn_cancelclassform();

                    var dataparam = "oper=loadclassname";
                    $.ajax({
                        url: "tools/correlation/tools-correlation-correlationtoolajax.php",
                        data: dataparam,
                        type: "POST",
                        beforeSend: function() {
                            // showloadingalert(actionmsg+", please wait.");	
                        },
                        success: function(data) {
                            $('#classnameload').html(data);

                        },
                    });
                }
                else if (data == 'update')
                {
                    fn_cancelclassform();
                    setTimeout('removesections("#tools-correlation-correlationtool");', 500);
                    setTimeout('showpages("tools-correlation-correlationtoolasset","tools/correlation/tools-correlation-correlationtoolasset.php");', 1000);
                }


            }

        });
    }

}


function fn_movealllistitems(leftlist, rightlist, id, courseid)
{
    if (id == 0)
    {
        $("div[id^=" + leftlist + "_]").each(function()
        {
            if (!$(this).hasClass('dim')) {
                var clas = $(this).attr('class');
                var temp = $(this).attr('id').replace(leftlist, rightlist);

                $(this).attr('id', temp);
                $('#' + rightlist).append($(this));

                if ($(this).attr('class') == 'draglinkleft') {
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
        var clas = $('#' + leftlist + '_' + courseid).attr('class');
        //alert(rightlist);

        if (clas == "draglinkleft")
        {
            $('#' + rightlist).append($('#' + leftlist + ' #' + leftlist + '_' + courseid));
            $('#' + leftlist + '_' + courseid).removeClass('draglinkleft').addClass('draglinkright');
            var temp = $('#' + leftlist + '_' + courseid).attr('id').replace(leftlist, rightlist);
            var ids = 'id';
            $('#' + leftlist + '_' + courseid).attr(ids, temp);
        }
        else
        {
            $('#' + leftlist).append($('#' + rightlist + ' #' + rightlist + '_' + courseid));
            $('#' + rightlist + '_' + courseid).removeClass('draglinkright').addClass('draglinkleft');
            var temp = $('#' + rightlist + '_' + courseid).attr('id').replace(rightlist, leftlist);
            var ids = 'id';
            $('#' + rightlist + '_' + courseid).attr(ids, temp);
        }
    }

    /* Move product count show details start line */
    if (leftlist == "list3" || rightlist == "list4" && leftlist == "list4" || rightlist == "list3")
    {
        var list3 = [];
        $("div[id^=list3_]").each(function() {
            list3.push($(this).attr('id').replace('list3_', ''));
        });
        $('#leftgrade').html(list3.length);

        var list4 = [];
        $("div[id^=list4_]").each(function() {
            list4.push($(this).attr('id').replace('list4_', ''));
        });
        $('#rightgrade').html(list4.length);

    }

    if (leftlist == "list5" || leftlist == "list6" && rightlist == "list6" || rightlist == "list5")
    {
        var list5 = [];
        $("div[id^=list5_]").each(function() {
            list5.push($(this).attr('id').replace('list5_', ''));
        });
        $('#leftsubject').html(list5.length);

        var list6 = [];
        $("div[id^=list6_]").each(function() {
            list6.push($(this).attr('id').replace('list6_', ''));
        });
        $('#rightsubject').html(list6.length);

    }

    if (leftlist == "list13" || rightlist == "list14" && leftlist == "list14" || rightlist == "list13")
    {
        var list13 = [];
        $("div[id^=list13_]").each(function() {
            list13.push($(this).attr('id').replace('list13_', ''));
        });
        $('#leftprdset').html(list13.length);

        var list14 = [];
        $("div[id^=list14_]").each(function() {
            list14.push($(this).attr('id').replace('list14_', ''));
        });
        $('#rightprdset').html(list14.length);

    }
    /* Move product count show details end line */
    if (leftlist == "list5" || leftlist == "list6" && rightlist == "list6" || rightlist == "list5")
    {
        var typeids = [];
        $("div[id^=list6_]").each(function()
        {
            var guid = $(this).attr('id').replace('list6_', '');
            typeids.push(guid);


        });
        fn_showproducts(typeids);
    }
    if (leftlist == "list7" || leftlist == "list8" && rightlist == "list8" || rightlist == "list7")
    {

        //var typeid = courseid.split('_');
         
       // fn_showstate();

        //if (typeid[1] == 5)
       // {
          //  fn_showdestination(courseid);
       // }
       // else
       // {
            fn_showstate();
       // }
    }
    if (leftlist == "list9" || leftlist == "list10" && rightlist == "list10" || rightlist == "list9")
    {
        //$('#viewreportdiv').show();
        $('#taskdiv').show();
        fn_showtasks();
    }
    if (leftlist == "list11" || leftlist == "list12" && rightlist == "list12" || rightlist == "list11")
    {
        // $('#viewreportdiv').show();
        $('#resourcediv').show();
        fn_showresource();
    }
    if (leftlist == "list13" || leftlist == "list14" && rightlist == "list14" || rightlist == "list13")
    {
        fn_showstate();
    }
}

/* productset new form */
function fn_newproductset()
{
    $.fancybox.showActivity();
    $.ajax({
        type: "POST",
        cache: false,
        url: "tools/correlation/tools-correlation-correlationtoolajax.php",
        data: "oper=newproductset",
        success: function(data) {
            $.fancybox(data, {'modal': true, 'autoDimensions': false, 'width': 480, 'autoScale': true, 'height': 177, 'scrolling': 'no'});
            $.fancybox.resize();
        }
    });

    return false;

}

function fn_cancelproductsetform()
{
    $.fancybox.close();
}

function fn_saveproductsetform()
{
    if ($("#classnameextendforms").validate().form())
    {

        var classnametxt = $('#txtclassname').val();
        $.ajax({
            type: "POST",
            cache: false,
            url: "tools/correlation/tools-correlation-correlationtoolajax.php",
            data: "oper=saveproductsettxt&classproductsettxt=" + escapestr(classnametxt),
            success: function(data)
            {
                // alert(data);
                if (data == 'fail')
                {
                    //showloadingalert("Either change the module name or module version number.");
                    //setTimeout('closeloadingalert()', 2000);
                    return false;
                }
                else
                {
                    //alert(data);
                    fn_cancelproductsetform();

                    var dataparam = "oper=loadproductsetname";
                    $.ajax({
                        url: "tools/correlation/tools-correlation-correlationtoolajax.php",
                        data: dataparam,
                        type: "POST",
                        beforeSend: function() {
                            // showloadingalert(actionmsg+", please wait.");	
                        },
                        success: function(data) {
                            $('#productsetnameload').html(data);

                        },
                    });
                }
            }

        });
    }

}

/******** new code for productinset start line *****/
function fn_saveproduct(id, pid)
{
	if ($("#assetsforms").validate().form())
    {
    //var id=0;
    var proname = $('#productname').val();
    var protiletype = $('#selectui').val();
    ;
    var proversion = $('#productversion').val();
    var assetname = $('#assetname').val();
    var assetid = $('#assetid').val();
    var list14 = [];	 //module id
    var list6 = [];	 //unit id
    var list4 = [];	 //ipl id
    $("div[id^=list14_]").each(function()
    {
        list14.push($(this).attr('name').replace('list14_', ''));
    });

    $("div[id^=list6_]").each(function()
    {
        list6.push($(this).attr('id').replace('list6_', ''));
    });

    $("div[id^=list4_]").each(function()
    {
        list4.push($(this).attr('id').replace('list4_', ''));
    });
    var dataparam = "oper=saveproduct" + "&proname=" + proname + "&protiletype=" + protiletype + "&proversion=" + proversion + "&assetname=" + escapestr(assetname) + "&assetid=" + assetid + "&list14=" + list14 + "&list6=" + list6 + "&list4=" + list4 + "&id=" + id + "&pid=" + pid;
    //alert("proName:" + dataparam);
    $.ajax({
        type: 'post',
        url: "tools/correlation/tools-correlation-correlationtoolajax.php",
        data: dataparam,
        beforeSend: function() {
            //showloadingalert("please wait.");	
        },
        success: function(data) {
            setTimeout('removesections("#tools-correlation-correlationtool");', 500);
            setTimeout('showpages("tools-correlation-correlationtoolasset","tools/correlation/tools-correlation-correlationtoolasset.php");', 1000);
        },
    });

}
}

/******** new code for productinset end line *****/
function fn_loadproductdetail(titletype)
{

    var dataparam = "oper=showproductdetail&titletype=" + titletype;
    $.ajax({
        type: 'post',
        url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
        data: dataparam,
        beforeSend: function() {
            $('#showproduct').html('<img src="img/loader.gif" width="200"  border="0" />');
        },
        success: function(data) {
            $('#showproduct').html(data);//Used to load the student details in the dropdown
        }
    });

}
function fn_deleteproduct(pid, tid, titletype)
{
    $.Zebra_Dialog('Are you sure you want to delete?',
            {
                'type': 'confirmation',
                'buttons': [
                    {caption: 'No', callback: function() {
                        }},
                    {caption: 'Yes', callback: function() {

                            var dataparam = "oper=deleteproduct&productid=" + pid + "&titleid=" + tid + "&titletype=" + titletype;
                            $.ajax({
                                url: "tools/correlation/tools-correlation-correlationtoolajax.php",
                                data: dataparam,
                                type: "POST",
                                beforeSend: function() {
                                    showloadingalert("Checking, please wait.");
                                },
                                success: function(ajaxdata) {
                                    //alert(ajaxdata);
                                    if (ajaxdata == "success") //Works if Product Deleted
                                    {
                                        $('.lb-content').html("Products has been Deleted Successfully");
                                        setTimeout('closeloadingalert()', 500);

                                        setTimeout('removesections("#tools-correlation-correlationtool");', 500);
                                        setTimeout('showpages("tools-correlation-correlationtoolasset","tools/correlation/tools-correlation-correlationtoolasset.php");', 1000);
                                    }
                                    else if (ajaxdata == "exists") //Works if Product is Assigned
                                    {
                                        closeloadingalert();
                                        $.Zebra_Dialog("You can't delete this products as it is in use", {'type': 'information', 'buttons': false, 'auto_close': 2000});
                                    }
                                    else
                                    {
                                        $('.lb-content').html("Deleting has been Failed"); //Works if the process fails in query.
                                        setTimeout('closeloadingalert()', 500);
                                    }
                                },
                            });
                        }
                    }]
            });
}

