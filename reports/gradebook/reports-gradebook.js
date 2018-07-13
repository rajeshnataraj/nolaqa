

function fn_gradebookexport(type,classid,reportname,studentid,unitmodid,schid,schtype)
{
    //alert('reports/gradebook/reports-gradebook-excel.php?id='+type+","+classid+","+reportname+","+studentid+","+unitmodid+","+schid+","+schtype);
   // if(type!=2)
	window.location='reports/gradebook/reports-gradebook-export.php?id='+type+","+classid+","+reportname+","+studentid+","+unitmodid+","+schid+","+schtype;
   // else
      //  window.location='reports/gradebook/reports-gradebook-excel.php?id='+type+","+classid+","+reportname+","+studentid+","+unitmodid+","+schid+","+schtype;
}
function fn_showtable(classid,left,top,gradeperiodid,innerclassid,innertop,innerleft,id)
{
	console.log(left+" ~ "+top);
	$('#scrollleft').val(left); 
	$('#scrolltop').val(top);
	$("#reports-gradebook-edit").hide("fade").remove();
	
	var dataparam = "oper=showtable&classid="+classid;
	$.ajax({
		type: 'post',
		url: 'reports/gradebook/reports-gradebook-gradebookajax.php',
		data: dataparam,
		//async:false,
		beforeSend: function(){
			$('#gradebook').html('<div align="center"><img width="60" height="60" src="img/questionloader.gif"></div>'); 	
			//showloadingalert("Loading, please wait.");
		},
		success:function(data) {		
			$('#gradebook').html(data);//Used to load the student details in the dropdown
			
			//closeloadingalert();
			$('#gradebook').effect("slide", {
				direction: "up",
				easing: "easeInOutSine",
				duration: 250
			},function(){
				var scrtop = ($('#gradebook').offset().top - 55) - $(window).scrollTop();
				$('html,body').animate({
					scrollTop: '+=' + (scrtop) + 'px'
				}, 'slow' );
			});
			
			//$('.fht-fixed-body').children('.fht-tbody').scrollTop($('#scrolltop').val());
			//$('.fht-fixed-body').children('.fht-tbody').scrollLeft($('#scrollleft').val());
			setTimeout("$('.fht-fixed-body').children('.fht-tbody').scrollTop($('#scrolltop').val())",500);
			setTimeout("$('.fht-fixed-body').children('.fht-tbody').scrollLeft($('#scrollleft').val())",800);
                        //alert(gradeperiodid);
                        if(id!=='' && id!=='0' && id!=='undefined' && id!==undefined)
                        {
                            fn_show(gradeperiodid,innerclassid,innertop,innerleft);
                        }
		}
	});
}

function fn_showiplpoints(id,classid,studentid,modunid,scheduleid,modorcustom)//mohan
{
	//alert(id+"~"+classid+"@"+studentid+"!"+modunid+"%"+scheduleid);
	var val = id+","+classid+","+studentid+","+modunid+","+scheduleid+","+modorcustom;
	removesections("#reports-gradebook-showinnertable");
    ajaxloadingalert('Loading, please wait.');
	setTimeout('showpageswithpostmethod("reports-gradebook-edit","reports/gradebook/reports-gradebook-edit.php","id='+val+'");',500);
}

function fn_addrubrics(id,classid,studentid,modunid,scheduleid)
{
	//alert(id+"~"+classid+"@"+studentid+"!"+modunid+"%"+scheduleid);
	var val = id+","+classid+","+studentid+","+modunid+","+scheduleid;
	removesections("#reports-gradebook-edit");
	showpageswithpostmethod("reports-gradebook-rubrics","reports/gradebook/reports-gradebook-rubrics.php","id="+val);
}

function fn_saveallperformance(classid,rotationid,scheduletype,scheduleid,customid)
{
	console.log("showperformance(classid="+classid+",rotationid="+rotationid+",scheduletype="+scheduletype+",scheduleid="+scheduleid+",customid="+customid+")")
	$.fancybox.showActivity();
	var dataparam = "oper=showperformance&classid="+classid+"&rotationid="+rotationid+"&scheduleid="+scheduleid+"&scheduletype="+scheduletype+"&customid="+customid;
        //alert(dataparam);
       
        $.ajax({
            type	: "POST",
            cache	: false,
            url		: "reports/gradebook/reports-gradebook-gradebookajax.php",
            data	: dataparam,
            success : function(data) {
                    $.fancybox(data,{'modal': true, 'showCloseButton': false, 'autoDimensions': false, 'width': 800, 'height': 510});
            }
	});
	return false;
	
}

function fn_saveperformance(classid,rotationid,scheduleid,scheduletype)
{
	console.log("fn_saveperformance(classid="+classid+",rotationid="+rotationid+",scheduletype="+scheduletype+",scheduleid="+scheduleid+")");
    var dataparam;
    /**************Activity code developed by Mohan M**************/
    if(scheduletype===10)
    {
            var stucount=$('#activitystucount').val();
            var assepoints='';
            for(var c=1;c<=stucount;c++)
            {
                    if(assepoints=='')
                    {
                            assepoints=( $('#activitytxt_'+c).val());
                    }
                    else
                    {
                            assepoints= assepoints+","+( $('#activitytxt_'+c).val());
                    }
            }
            dataparam = "oper=saveperformance&classid="+classid+"&rotationid="+rotationid+"&scheduleid="+scheduleid+"&scheduletype="+scheduletype+"&assepoints="+assepoints;
    //alert(dataparam);
    //exit;
    }
    /**************Activity code developed by Mohan M**************/
    else if(scheduletype===17)
    {
       /* rotationid as customcontent fld_id */

            var stucount=$('#stucount').val();
             var assepoints='';
            for(var c=1;c<=stucount;c++)
            {
                if(c == 1)
                {
                    assepoints=( $('#customtext_'+c).val());
                }
                else
                {
                    assepoints= assepoints+","+( $('#customtext_'+c).val());
                }

            }

       dataparam = "oper=saveperformance&classid="+classid+"&rotationid="+rotationid+"&scheduleid="+scheduleid+"&scheduletype="+scheduletype+"&assepoints="+assepoints;

    }

    else if(scheduletype===18 || scheduletype===23)
    {
        var chkvalg=$('#checkgdval').val(); 
        var chkvald=$('#checkdgval').val(); 

      //  if(parseInt(chkvalg)!=0)
      //  {
            var mm=0;
             var modorstuidsg='';
            $("input[id^=rubricscore_2]").each(function()
            {
                var misbasicid = $(this).attr('id').replace('rubricscore_2','');
                var fill=$('#perfmark_'+mm+'_2'+misbasicid).val();

                if(modorstuidsg=='')
                {
                    if(fill!='')
                    {
                         modorstuidsg=mm+'_2'+misbasicid+'_'+fill;
                    }
                }
                else
                {
                    if(fill!='')
                    {
                        modorstuidsg= modorstuidsg+","+mm+'_2'+misbasicid+'_'+fill;
                    }
                }
                mm++;
            })
       // }

      //  if(parseInt(chkvald)!=0)
      //  {
            var mm=0;
            var modorstuidsd='';
            $("input[id^=rubricscore_3]").each(function()
            {
                var misbasicidd = $(this).attr('id').replace('rubricscore_3','');
                var fill=$('#perfmark_'+mm+'_3'+misbasicidd).val();

                if(modorstuidsd=='')
                {
                    if(fill!='')
                    {
                        modorstuidsd=mm+'_3'+misbasicidd+'_'+fill;
                    }
                }
                else
                {
                    if(fill!='')
                    {
                        modorstuidsd= modorstuidsd+","+mm+'_3'+misbasicidd+'_'+fill;
                    }
                }
                mm++;
            })
       // }
        //alert(modorstuidsg+"---"+modorstuidsd)
     //exit;

        if(scheduletype===18)
        {
           rotationid=$('#misid').val();
        }
        dataparam = "oper=saveperformance&classid="+classid+"&rotationid="+rotationid+"&scheduleid="+scheduleid+"&scheduletype="+scheduletype+"&modorstuidsg="+modorstuidsg+"&modorstuidsd="+modorstuidsd+"&chkvalg="+chkvalg+"&chkvald="+chkvald;
    }

    else if(scheduletype===0)
    {
        var stucount=$('#stucount').val();
        var assepoints='';
        for(var c=1;c<=stucount;c++)
        {
            if(assepoints=='')
            {
                assepoints=( $('#ipltext_'+c).val());
            }
            else
            {
                assepoints= assepoints+","+( $('#ipltext_'+c).val());
            }
        }
       dataparam = "oper=saveperformance&classid="+classid+"&rotationid="+rotationid+"&scheduleid="+scheduleid+"&scheduletype="+scheduletype+"&assepoints="+assepoints;
    } 
    else
    {
        var modids= new Array();
        $('input:hidden[name^="modids"]').each(function() {
            modids.push($(this).val());
        });

        var modidssize=modids.length;
        var assepoints= new Array();
        for(var i=1;i<=modidssize;i++)
        { 
            for(var j=1;j<=3;j++)
            {
                assepoints.push($('#perfmark'+i+'_'+j).val());
            }
        }
        dataparam = "oper=saveperformance&classid="+classid+"&rotationid="+rotationid+"&scheduleid="+scheduleid+"&scheduletype="+scheduletype+"&assepoints="+assepoints;
		console.log(dataparam);
    }
    $.ajax({
        type: 'post',
        url: 'reports/gradebook/reports-gradebook-gradebookajax.php',
        data: dataparam,
        beforeSend: function(){
        },
        success:function(data)
        {

        }
    });
}

function fn_savepoints(type,left,top)
{
    top = $('#myTable06').parent('div').scrollTop();
    left = $('#myTable06').parent('div').scrollLeft();
    var rubricsids = "";
    var rubricspointsearned = "";
    var rubricspointspossible = "";
    var sessiplids = "";
    var pointsearned = "";
    var pointspossible = "";
    var actid = "";
    var actpoint = "";
    var actpossible = "";
    var contid = "";
    var contpoint = "";
    var contpossible = "";
    var testid = "";
    var testpoint = "";
    var testpossible = "";
    var questid = "";
    var questsessid = "";
    var questpoint = "";
    var questpossible = "";
    var questtype = "";
    var exptypeid = "";
    var exppoint = "";
    var exppossible = "";
    var exptype = "";

    var exptesttypeid = "";
    var exptestpoint = "";
    var exptestpossible = "";
    var exptesttype = "";

    var mistypeid = "";
    var mispoint = "";
    var mispossible = "";
    var mistype = "";

    var mistesttypeid = "";
    var mistestpoint = "";
    var mistestpossible = "";
    var mistesttype = "";
        
    /**************************Expedition and Module schedule Code start here by Mohan**********************/
    var modcontid = "";
    var modcontpoint = "";
    var modcontpossible = "";
    
    var expmodtesttypeid = "";
    var expmodtesttype = "";
    var expmodtestpoint = "";
    var expmodtestpossible = "";
    
    var modsessiplids = "";
    var modpointsearned = "";
    var modpointspossible = "";
    
    //var expmodpoint = "";
    //var expmodpossible = "";
    //var expmodtype = "";
    
    /**************************Expedition and Module schedule Code start here by Mohan**********************/
        
    //math connection	
    var earned=[];
    var possible=[];
    var possiblepoi=[];
    var earnedear=[];
    var ear=[];
    var checkflag=$('#checkflag').val();
	
    if(type==0)
    {

            $("input[id^=earned_]").each(function()
            {
                      earnedear.push($(this).attr('id').replace('earned_',''));
                    var ear=$(this).val();
                    earned.push(ear);
                    //alert(earnedear);
                });
                $("input[id^=possible_]").each(function()
                { 
                    possible.push($(this).attr('id').replace('possible_',''));
                    //alert(possible);
                    var poss=$(this).val();
                    possiblepoi.push(poss);
                    // alert(possible.length);

                });

              //first value is greater the second value      
                for(i=0;i<possible.length;i++)
                {
                        //alert(earnedear[i]);
                        // alert(earned[i]+"."+possiblepoi[i]);
                        // if(earned[i] > possiblepoi[i])
                    if(parseInt(earned[i])> parseInt(possiblepoi[i]))
                    {
                        //alert("test ok ");
                        //alert("if ");
                       // $('#earned_'+earnedear[i]).val('');

                       $.Zebra_Dialog("Points Earned Must be lesser then or equal to Points Possible", { 'type': 'information', 'buttons':  false, 'auto_close': 3500  });
                       $('#earned_'+earnedear[i]).focus();
                            return false;
                    }
                    else if(parseInt(earned[i])>= parseInt(possiblepoi[i]))
                    {
                        //alert('else if1');
                        //alert($('#earned_'+earnedear[i]).val());
                        // $('#earned_'+earnedear[i]).val('');

                        //$('#earned_'+earnedear[i]).val(0);
                        //alert($('#earned_'+earnedear[i]).val());

                        //alert(earned[i]);
                        // earned[i]='';
                        //alert(earned[i]);
                        //alert("else test ok");
                    }
                    else if(parseInt(earned[i])== parseInt(possiblepoi[i]))
                    {
                        //alert('else if2');
                    }

                }

                //math connection for first value is greater the second value              
                  if(checkflag ==1 )
                  {
                      //alert('hai');
                        var first=document.getElementById('cgaearned').value;
                        var second=document.getElementById('cgapossible').value; 

                        if(parseInt(first)> parseInt(second))
                        {
                            $.Zebra_Dialog("Math Connection Points Earned Must be lesser then or equal to Points Possible", { 'type': 'information', 'buttons':  false, 'auto_close': 3500  });
                            //$('#cgaearned').val('');
                             $('#cgaearned').focus();
                            return false;

                        }
                        else if(parseInt(first)>= parseInt(second))
                        {

                        }


//                            if($('#cgaearned').val()=="")
//                                {
//                                    $.Zebra_Dialog("Please enter the value for points earned1", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
//                                    $('#cgaearned').focus();
//                                    return false;
//                                }
                            }
               //all text box have a empty
//                        for(i=0;i<possible.length;i++)
//                        {
//                            if($('#earned_'+earnedear[i]).val()==""){ 
//                                
//                                $.Zebra_Dialog("Please enter the value for points earned2", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
//                                $('#earned_'+earnedear[i]).focus();
//                                return false;
//
//                            }
//                        }

            $("input[id^=earned_]").each(function()
            {

                    iplid = $(this).attr('id').replace('earned_','');
                    if($('#teacher_'+iplid).val()==1)
                    {
                            point = $(this).val();
                            possible = $('#possible_'+iplid).val();
                            if(point=='')
                            {
                                    point='1111';
                            }

                            if(sessiplids == ''){
                                    sessiplids = iplid;
                            }
                            else {
                                    sessiplids = sessiplids +"~"+ iplid;	
                            }

                            if(pointsearned == ''){
                                    pointsearned = point;
                            }
                            else {
                                    pointsearned = pointsearned +"~"+ point;	
                            }

                            if(pointspossible == ''){
                                    pointspossible = possible;
                            }
                            else {
                                    pointspossible = pointspossible +"~"+ possible;	
                            }
                    }
            });

            $("input[id^=rearned_]").each(function()
            {
                    rubricsid = $(this).attr('id').replace('rearned_','');
                    if($('#rteacher_'+rubricsid).val()==1)
                    {
                            rubricspoint = $(this).val();
                            rubricspossible = $('#rpossible_'+rubricsid).val();
                            if(rubricspoint=='')
                            {
                                    rubricspoint='0';
                            }

                            if(rubricsids == ''){
                                    rubricsids = rubricsid;
                            }
                            else {
                                    rubricsids = rubricsids +"~"+ rubricsid;	
                            }

                            if(rubricspointsearned == ''){
                                    rubricspointsearned = rubricspoint;
                            }
                            else {
                                    rubricspointsearned = rubricspointsearned +"~"+ rubricspoint;	
                            }

                            if(rubricspointspossible == ''){
                                    rubricspointspossible = rubricspossible;
                            }
                            else {
                                    rubricspointspossible = rubricspointspossible +"~"+ rubricspossible;	
                            }
                    }
            });
    }
    else if(type==10)
    {
            $("input[id^=actearned_]").each(function()
            {
                    actid = $(this).attr('id').replace('actearned_','');
                    actpoint = $(this).val();
                    actpossible = $('#actpossible_'+actid).val();
            });
    }
    else if(type==9)
    {
            $("input[id^=testearned_]").each(function()
            {
                    testid = $(this).attr('id').replace('testearned_','');
                    testpoint = $(this).val();
                    testpossible = $('#testpossible_'+testid).val();
            });
    }
    else if(type==8)
    {
            $("input[id^=contearned_]").each(function()
            {
                    contid = $(this).attr('id').replace('contearned_','');
                    contpoint = $(this).val();
                    contpossible = $('#contpossible_'+contid).val();
            });
    }
    else if(type==17)
    {
            $("input[id^=contearned_]").each(function()
            {
                    contid = $(this).attr('id').replace('contearned_','');
                    contpoint = $(this).val();
                    contpossible = $('#contpossible_'+contid).val();
            });
    }
    else if(type==15)
    {
            /*$("input[id^=expearned_]").each(function()
            {
                    expbasicid = $(this).attr('id').replace('expearned_','');

                    if($('#expteacher_'+expbasicid).val()==1)
                    {
                            point = $(this).val();
                            possible = $('#exppossible_'+expbasicid).val();


                            var exid = expbasicid.split('_');
                            if(exptypeid == ''){
                                    exptypeid = exid[0];
                            }
                            else {
                                    exptypeid = exptypeid +"~"+ exid[0];	
                            }

                            if(exptype == ''){
                                    exptype = exid[1];
                            }
                            else {
                                    exptype = exptype +"~"+ exid[1];	
                            }

                            if(exppoint == ''){
                                    exppoint = point;
                            }
                            else {
                                    exppoint = exppoint +"~"+ point;	
                            }

                            if(exppossible == ''){
                                    exppossible = possible;
                            }
                            else {
                                    exppossible = exppossible +"~"+ possible;	
                            }
                    }
            });*/
            //alert(exptypeid+" ~ "+exptype+" ~ "+exppoint+" ~ "+exppossible);

    /*****Teacher Points earned code start here for pre/post test*****/
            $("input[id^=exptestearned_]").each(function()
            {
                    expbasicid = $(this).attr('id').replace('exptestearned_','');

                    if($('#exptestteacher_'+expbasicid).val()==1)
                    {
                            tpoint = $(this).val();
                            tpossible = $('#exptestpossible_'+expbasicid).val();


                            var exid = expbasicid.split('_');
                            if(exptesttypeid == ''){
                                    exptesttypeid = exid[0];
                            }
                            else {
                                    exptesttypeid = exptesttypeid +"~"+ exid[0];	
                            }

                            if(exptesttype == ''){
                                    exptesttype = exid[1];
                            }
                            else {
                                    exptesttype = exptesttype +"~"+ exid[1];	
                            }

                            if(exptestpoint == ''){
                                    exptestpoint = tpoint;
                            }
                            else {
                                    exptestpoint = exptestpoint +"~"+ tpoint;	
                            }

                            if(exptestpossible == ''){
                                    exptestpossible = tpossible;
                            }
                            else {
                                    exptestpossible = exptestpossible +"~"+ tpossible;	
                            }
                    }
            });
            //alert(exptesttypeid+" ~ "+exptesttype+" ~ "+exptestpoint+" ~ "+exptestpossible);
    /*****Teacher Points earned code End here for pre/post test*****/
    }

    else if(type==19)
    {
            //exppoint=$('#expearned').val();
            //exppossible=$('#exppossible').val();
            //exptype=2;

            /*****Teacher Points earned code start here for pre/post test*****/
            $("input[id^=exptestearned_]").each(function()
            {		
                    expbasicid = $(this).attr('id').replace('exptestearned_','');

                    if($('#exptestteacher_'+expbasicid).val()==1)
                    {
                            tpoint = $(this).val();
                            tpossible = $('#exptestpossible_'+expbasicid).val();


                            var exid = expbasicid.split('_');
                            if(exptesttypeid == ''){
                                    exptesttypeid = exid[0];
                            }
                            else {
                                    exptesttypeid = exptesttypeid +"~"+ exid[0];	
                            }

                            if(exptesttype == ''){
                                    exptesttype = exid[1];
                            }
                            else {
                                    exptesttype = exptesttype +"~"+ exid[1];	
                            }

                            if(exptestpoint == ''){
                                    exptestpoint = tpoint;
                            }
                            else {
                                    exptestpoint = exptestpoint +"~"+ tpoint;	
                            }

                            if(exptestpossible == ''){
                                    exptestpossible = tpossible;
                            }
                            else {
                                    exptestpossible = exptestpossible +"~"+ tpossible;	
                            }
                    }
            });
            //alert(exptesttypeid+" ~ "+exptesttype+" ~ "+exptestpoint+" ~ "+exptestpossible);

        /*****Teacher Points earned code End here for pre/post test*****/
    }
    /*********Mission report Code Start Here Developed By Mohan M 16-7-2015*************/	
    else if(type==18)
    {
        $("input[id^=misearned_]").each(function()
        {
            misbasicid = $(this).attr('id').replace('misearned_','');

            if($('#misteacher_'+misbasicid).val()==1)
            {
                point = $(this).val();
                possible = $('#mispossible_'+misbasicid).val();

                var miid = misbasicid.split('_');
                if(mistypeid == ''){
                    mistypeid = miid[0];
                }
                else {
                    mistypeid = mistypeid +"~"+ miid[0];	
                }

                if(mistype == ''){
                    mistype = miid[1];
                }
                else {
                    mistype = mistype +"~"+ miid[1];	
                }

                if(mispoint == ''){
                    mispoint = point;
                }
                else {
                    mispoint = mispoint +"~"+ point;	
                }

                if(mispossible == ''){
                    mispossible = possible;
                }
                else {
                    mispossible = mispossible +"~"+ possible;	
                }
            }
        });

        // alert(mistesttypeid+" ~ "+mistesttype+" ~ "+mistestpoint+" ~ "+mistestpossible);
        /*****Teacher Points earned code End here for pre/post test*****/
    }
    
    else if(type==23)
    {
        $("input[id^=misearned_]").each(function()
        {
            misbasicid = $(this).attr('id').replace('misearned_','');

            if($('#misteacher_'+misbasicid).val()==1)
            {
                point = $(this).val();
                possible = $('#mispossible_'+misbasicid).val();

                var miid = misbasicid.split('_');
                if(mistypeid == ''){
                    mistypeid = miid[0];
                }
                else {
                    mistypeid = mistypeid +"~"+ miid[0];	
                }

                if(mistype == ''){
                    mistype = miid[1];
                }
                else {
                    mistype = mistype +"~"+ miid[1];	
                }

                if(mispoint == ''){
                    mispoint = point;
                }
                else {
                    mispoint = mispoint +"~"+ point;	
                }

                if(mispossible == ''){
                    mispossible = possible;
                }
                else {
                    mispossible = mispossible +"~"+ possible;	
                }
            }
        });

        $("input[id^=mistestearned_]").each(function()
        {
            misbasicid = $(this).attr('id').replace('mistestearned_','');

            if($('#mistestteacher_'+misbasicid).val()==1)
            {
                point = $(this).val();
                possible = $('#mistestpossible_'+misbasicid).val();

                var miid = misbasicid.split('_');
                if(mistesttypeid == ''){
                    mistesttypeid = miid[0];
                }
                else {
                    mistesttypeid = mistesttypeid +"~"+ miid[0];
                }

                if(mistesttype == ''){
                    mistesttype = miid[1];
                }
                else {
                    mistesttype = mistesttype +"~"+ miid[1];
                }

                if(mistestpoint == ''){
                    mistestpoint = point;
                }
                else {
                    mistestpoint = mistestpoint +"~"+ point;
                }

                if(mistestpossible == ''){
                    mistestpossible = possible;
                }
                else {
                    mistestpossible = mistestpossible +"~"+ possible;
                }
            }
        });
    }
    /*********Mission report Code Start Here Developed By Mohan M 16-7-2015*************/	
        
    else if(type==7)
    {
        $("input[id^=qusetearned_]").each(function()
        {
            qucontentid = $(this).attr('id').replace('qusetearned_','');
            //alert(qucontentid);
            if($('#qusetteacher_'+qucontentid).val()==1)
            {
                point = $(this).val();
                possible = $('#qusetpossible_'+qucontentid).val();
                if(point=='')
                {
                        point='1111';
                }

                var quid = qucontentid.split('-');
                if(questid == ''){
                        questid = quid[0];
                }
                else {
                        questid = questid +"~"+ quid[0];	
                }

                if(questsessid == ''){
                        questsessid = quid[1];
                }
                else {
                        questsessid = questsessid +"~"+ quid[1];	
                }

                if(questtype == ''){
                        questtype = quid[2];
                }
                else {
                        questtype = questtype +"~"+ quid[2];	
                }

                if(questpoint == ''){
                        questpoint = point;
                }
                else {
                        questpoint = questpoint +"~"+ point;	
                }

                if(questpossible == ''){
                        questpossible = possible;
                }
                else {
                        questpossible = questpossible +"~"+ possible;	
                }
            }
        });
        //alert(questid+" ~ "+questpoint+" ~ "+questpossible);
    }
    
    else if(type==1 || type==2 || type==3  || type==4 || type==5 || type==6)
    {
        for(i=1;i<4;i++)
        {
            $("input[id^=earned"+i+"_]").each(function()
            {
                sessionid = $(this).attr('id').replace('earned'+i+'_','');
                if($('#teacher'+i+'_'+sessionid).val()==1)
                {
                    earnedpoint = $(this).val();
                    var m=sessiplids.lastIndexOf(",");
                    var newsessiplids=sessiplids.substr(m+1,10);
                    var n=pointsearned.lastIndexOf(",");
                    var newpointsearned=pointsearned.substr(n+1,10);
                    var o=pointspossible.lastIndexOf(",");
                    var newpointspossible=pointspossible.substr(o+1,10);
                    //alert(earnedpoint);
                    //alert(newpointsearned);
                    possible = $('#possible'+i+'_'+sessionid).val();
                    if(earnedpoint=='')
                    {
                            earnedpoint='1111';
                    }

                    if(newsessiplids == ''){
                            sessiplids = sessiplids+sessionid;
                    }
                    else {
                            sessiplids = sessiplids +"~"+ sessionid;	
                    }

                    if(newpointsearned == ''){
                            pointsearned = pointsearned+earnedpoint;
                    }
                    else {
                            pointsearned = pointsearned +"~"+ earnedpoint;	
                    }

                    if(newpointspossible == ''){
                            pointspossible = pointspossible+possible;
                    }
                    else {
                            pointspossible = pointspossible +"~"+ possible;	
                    }
                }
            });
            sessiplids = sessiplids +",";
            pointsearned = pointsearned +",";
            pointspossible = pointspossible +",";
            //alert(pointsearned);
        }
        if(type==4 || type==6)
        {
            var diagiplids = "";
            var diagpointsearned = "";
            var diagpointspossible = "";

            $("input[id^=dearned_]").each(function()
            {
                diagid = $(this).attr('id').replace('dearned_','');
                newdiagid = $(this).attr('name').replace('dearned_','');
                //alert(diagid);
                if($('#dteacher_'+newdiagid).val()==1)
                {
                        diagpoint = $(this).val();
                        diagpossible = 100;
                        if(diagpoint=='')
                        {
                                diagpoint='1111';
                        }

                        if(diagiplids == ''){
                                diagiplids = diagid;
                        }
                        else {
                                diagiplids = diagiplids +"~"+ diagid;	
                        }

                        if(diagpointsearned == ''){
                                diagpointsearned = diagpoint;
                        }
                        else {
                                diagpointsearned = diagpointsearned +"~"+ diagpoint;	
                        }

                        if(diagpointspossible == ''){
                                diagpointspossible = diagpossible;
                        }
                        else {
                                diagpointspossible = diagpointspossible +"~"+ diagpossible;	
                        }
                }
            });
        }
    }
    
    /**************************Expedition and Module schedule Code start here by Mohan**********************/
    else if(type==20) //Expedition
    {
        //expmodpoint=$('#expmodearned').val();
       // expmodpossible=$('#expmodpossible').val();
       // expmodtype=2;

        /*****Teacher Points earned code start here for pre/post test*****/
        $("input[id^=expmodtestearned_]").each(function()
        {
            expbasicid = $(this).attr('id').replace('expmodtestearned_','');

            if($('#expmodtestteacher_'+expbasicid).val()==1)
            {
                tpoint = $(this).val();
                tpossible = $('#expmodtestpossible_'+expbasicid).val();


                var exid = expbasicid.split('_');
                if(expmodtesttypeid == '')
                {
                    expmodtesttypeid = exid[0];
                }
                else 
                {
                    expmodtesttypeid = expmodtesttypeid +"~"+ exid[0];	
                }

                if(expmodtesttype == '')
                {
                    expmodtesttype = exid[1];
                }
                else 
                {
                    expmodtesttype = expmodtesttype +"~"+ exid[1];	
                }

                if(expmodtestpoint == '')
                {
                    expmodtestpoint = tpoint;
                }
                else 
                {
                    expmodtestpoint = expmodtestpoint +"~"+ tpoint;	
                }

                if(expmodtestpossible == '')
                {
                    expmodtestpossible = tpossible;
                }
                else 
                {
                    expmodtestpossible = expmodtestpossible +"~"+ tpossible;	
                }
            }
        });
        //alert(expmodtesttypeid+" ~ "+expmodtesttype+" ~ "+expmodtestpoint+" ~ "+expmodtestpossible);
        /*****Teacher Points earned code End here for pre/post test*****/
    }
    else if(type==21)
    {
        var modorcustom=$('#hidmodcustom').val();
        if(modorcustom==1)// Module
        {
            for(i=1;i<4;i++)
            {
                $("input[id^=earned"+i+"_]").each(function()
                {
                    sessionid = $(this).attr('id').replace('earned'+i+'_','');
                    if($('#teacher'+i+'_'+sessionid).val()==1)
                    {
                        earnedpoint = $(this).val();
                        var m=modsessiplids.lastIndexOf(",");
                        var newmodsessiplids=modsessiplids.substr(m+1,10);
                        var n=modpointsearned.lastIndexOf(",");
                        var newmodpointsearned=modpointsearned.substr(n+1,10);
                        var o=modpointspossible.lastIndexOf(",");
                        var newmodpointspossible=modpointspossible.substr(o+1,10);
                      //  alert(earnedpoint);
                       // alert(newmodpointsearned);
                        possible = $('#possible'+i+'_'+sessionid).val();
                        if(earnedpoint=='')
                        {
                            earnedpoint='1111';
                        }

                        if(newmodsessiplids == ''){
                            modsessiplids = modsessiplids+sessionid;
                        }
                        else {
                            modsessiplids = modsessiplids +"~"+ sessionid;	
                        }

                        if(newmodpointsearned == ''){
                            modpointsearned = modpointsearned+earnedpoint;
                        }
                        else {
                            modpointsearned = modpointsearned +"~"+ earnedpoint;	
                        }

                        if(newmodpointspossible == ''){
                            modpointspossible = modpointspossible+possible;
                        }
                        else {
                            modpointspossible = modpointspossible +"~"+ possible;	
                        }
                    }
                });
                modsessiplids = modsessiplids +",";
                modpointsearned = modpointsearned +",";
                modpointspossible = modpointspossible +",";
                //alert(modpointsearned);
            }
        }
        else if(modorcustom==8) // Custom Content
        {
            $("input[id^=expmodcontearned_]").each(function()
            {
                modcontid = $(this).attr('id').replace('expmodcontearned_','');
                modcontpoint = $(this).val();
                modcontpossible = $('#expmodcontpossible_'+contid).val();
            });
        }
    }   
    /**************************Expedition and Module schedule Code End here by Mohan**********************/  
    
    var dataparam = "oper=savepoints&type="+type+"&classid="+$('#hidclassid').val()+"&studentid="+$('#hidstudentid').val()+"&scheduleid="+$('#hidscheduleid').val()+"&unitmodid="+$('#hidunitmodid').val()+"&sessiplids="+sessiplids+"&pointsearned="+pointsearned+"&pointspossible="+pointspossible+"&rubricsids="+rubricsids+"&rubricspointsearned="+rubricspointsearned+"&rubricspointspossible="+rubricspointspossible+"&diagids="+diagiplids+"&diagpointsearned="+diagpointsearned+"&diagpointspossible="+diagpointspossible+"&actid="+actid+"&actpoint="+actpoint+"&actpossible="+actpossible+"&cgamark="+$('#cgaearned').val()+"&testid="+testid+"&testpoint="+testpoint+"&testpossible="+testpossible+"&contid="+contid+"&contpoint="+contpoint+"&contpossible="+contpossible+"&questid="+questid+"&questpoint="+questpoint+"&questpossible="+questpossible+"&questsessid="+questsessid+"&questtype="+questtype+"&exptesttypeid="+exptesttypeid+"&exptesttype="+exptesttype+"&exptestpoint="+exptestpoint+"&exptestpossible="+exptestpossible+"&mistypeid="+mistypeid+"&mistype="+mistype+"&mispoint="+mispoint+"&mispossible="+mispossible+"&expmodtesttypeid="+expmodtesttypeid+"&expmodtesttype="+expmodtesttype+"&expmodtestpoint="+expmodtestpoint+"&expmodtestpossible="+expmodtestpossible+"&modsessiplids="+modsessiplids+"&modpointsearned="+modpointsearned+"&modpointspossible="+modpointspossible+"&modcontid="+modcontid+"&modcontpoint="+modcontpoint+"&modcontpossible="+modcontpossible+"&modorcustom="+modorcustom + "&mistesttypeid=" + mistesttypeid + "&mistesttype=" + mistesttype + "&mistestpoint=" + mistestpoint + "&mistestpossible=" + mistestpossible;//"&exptypeid="+exptypeid+"&exptype="+exptype+"&exppoint="+exppoint+"&exppossible="+exppossible+"&expmodtype="+expmodtype+"&expmodpoint="+expmodpoint+"&expmodpossible="+expmodpossible

    //return false;
    var classid = $('#hidclassid').val();
    var gradeperiodid = $('#hidgradeperiodid').val();

    $.ajax({
        type: 'post',
        url: 'reports/gradebook/reports-gradebook-gradebookajax.php',
        data: dataparam,
        async:false,
        success:function(data) {		
            removesections("#reports-gradebook");
            fn_showtable(classid,0,0,gradeperiodid,classid,top,left,1);                        
            //fn_show(gradeperiodid,classid,top,left);
        }
    });
}

function fn_saverubric2(id,expid)
{
    var ids = [];
    var score=[];
    var comments=[];

    if($("#rubricforms").validate().form()) //Validates the Rubric Form
    {
        var list10 = [];
        $("div[id^=list10_]").each(function(){
            list10.push($(this).attr('id').replace('list10_',''));
        });
        if(list10!='')
        {
            if(id!='undefined' && id!=0 && id!=''){ //Works in Editing module
                actionmsg = "Saving";
                alertmsg = "Rubric has been Saved Successfully";
            }
            else { //Works in Creating a New Module
                actionmsg = "Saving";
                alertmsg = "Rubric has been Saved Successfully";
            }
        }
        else
        {
            $.Zebra_Dialog("Please select the student for Grade Student", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
            return false;
        }

        $('input[id^=rubrictxtoldval_]').each(function()
        {
            score.push($(this).val());
        });

        // Code by barney related to #23153
        $('.commentbox').each(function() {
            comments.push($(this).val());
        });

        $("input[id^=ids_]").each(function()
        {
            ids.push($(this).attr('id').replace('ids_',''));
        });
        var schid=$("#rubschedid").val();
        var classid=$("#classid").val();

        // Code by barney related to #23153
        console.log(comments);
        var dataparam = "oper=saverubricval&list10="+list10+"&expid="+expid+"&rubid="+id+"&ids="+ids+"&classid="+classid+"&schid="+schid+"&score="+score+"&comments="+comments;
        console.log(dataparam);

        $.ajax({
            url: 'library/rubric/library-rubric-gradestudentrubricajax.php',
            data: dataparam,
            type: "POST",
            beforeSend: function(){
                showloadingalert("Saving, please wait...");
            },
            success: function (data)
            {
                if(data=="success") //Works if the data saved in db
                {
                    setTimeout('closeloadingalert()',500);
                    console.log(data);
                }else{
                    setTimeout('closeloadingalert()',500);
                    console.log(data);
                }
            },
            error: function (data) {
                alert(data);

            }
        });
    }
}

// function fn_saverubrics(id)
// {
// 	var dataparam = "oper=saverubrics&rubricsname="+escapestr($('#txtrubricsname').val())+"&rubricsid="+id+"&classid="+$('#hidclassid').val()+"&studentid="+$('#hidstudentid').val()+"&scheduleid="+$('#hidscheduleid').val()+"&unitmodid="+$('#hidunitmodid').val()+"&pointspossible="+$('#txtrubricspoints').val();
// 	var alertmsg = "";
// 	var actionmsg = "";
//
// 	if($("#rubricsforms").validate().form())
// 	{
// 		if(id != 'undefined'){
// 			actionmsg = "Updating";
// 			alertmsg = "Rubrics has been updated successfully";
// 			failedmsg = "Updating rubrics detail has been failed";
// 		}
// 		else {
// 			actionmsg = "Saving";
// 			alertmsg = "Rubrics has been created successfully";
// 			failedmsg = "Creating rubrics has been failed";
// 		}
//
// 		$.ajax({
// 			type: 'post',
// 			url: 'reports/gradebook/reports-gradebook-gradebookajax.php',
// 			data: dataparam,
// 			beforeSend: function(){
// 				showloadingalert(actionmsg+", please wait.");
// 			},
// 			success:function(data) {
// 				if(data=="success")
// 				{
// 					var val = "0,"+$('#hidclassid').val()+","+$('#hidstudentid').val()+","+$('#hidunitmodid').val()+","+$('#hidscheduleid').val();
// 					//alert(val);
// 					$('.lb-content').html(alertmsg);
// 					setTimeout('closeloadingalert()',500);
// 					removesections("#reports-gradebook");
// 					showpageswithpostmethod("reports-gradebook-edit","reports/gradebook/reports-gradebook-edit.php","id="+val);
// 				}
// 				else
// 				{
// 					$('.lb-content').html(failedmsg);
// 					setTimeout('closeloadingalert()',500);
// 				}
// 			}
// 		});
// 	}
// }



function fn_showperiod()
{
	removesections("#reports-gradebook");
	$('#txtgradename').val('');
	$('#startdate1').val('');
	$('#enddate1').val('');
	$('#txtgradename').parents('dl').removeClass('error');
	$('#txtgradename').addClass('valid').removeClass('error');
	$('#startdate1').parents('dl').removeClass('error');
	$('#startdate1').addClass('valid').removeClass('error');
	$('#enddate1').parents('dl').removeClass('error');
	$('#enddate1').addClass('valid').removeClass('error');
	
	$('#showperioddiv').show();
	$("#showperioddiv").load("reports/gradebook/reports-gradebook.php #showperioddiv > *",{"id":0}, function(){
		formvalid();
	});
}

function fn_saveperiod(id)
{	
	if($("#frmgrade").validate().form())
	{
		var dataparam = "oper=savegradeperiod&classid="+$('#classid').val()+"&editid="+id+"&gradename="+$('#txtgradename').val()+"&startdate1="+$('#startdate1').val()+"&enddate1="+$('#enddate1').val();
		$.ajax({
			type: "POST",
			url: 'reports/gradebook/reports-gradebook-gradebookajax.php',
			data: dataparam,
			beforeSend:function()
			{
				showloadingalert("Loading, please wait.");
			},
			success: function(data)
			{
                            var classflag=$('#classflag').val();
                           // alert("flag:"+classflag);
                            if(classflag==1)
                            {
                                var clsid=$('#classid').val()+","+classflag; 
                               // alert(clsid);
                                $("#showperioddiv").load("reports/gradebook/reports-gradebook.php #showperioddiv > *",{"id":0+","+clsid}, function(){
					formvalid();
				});
                            }
                            else
                            {
				$("#showperioddiv").load("reports/gradebook/reports-gradebook.php #showperioddiv > *",{"id":0}, function(){
					formvalid();
				});
                            }
				closeloadingalert();
				if(id==0)
					showloadingalert("Created successfully.");
				else
					showloadingalert("Updated successfully.");
				setTimeout("closeloadingalert();",1000);
				
				fn_showtable($('#classid').val(),0,0);
			}
		});
	}
}

function formvalid()
{
	$("#startdate1").datepicker( {
		onSelect: function(dateText,inst){
			$(this).parents().parents().removeClass('error');
		}
	});
	
	$("#enddate1").datepicker( {
		onSelect: function(dateText,inst){
			$(this).parents().parents().removeClass('error');
		}
	});
	
	$(function(){
		$("#frmgrade").validate({
			ignore: "",
			errorElement: "dd",
			errorPlacement: function(error, element) {
				$(element).parents('dl').addClass('error');
				error.appendTo($(element).parents('dl'));
				error.addClass('msg');
				window.scroll(0,($('dd').offset().top)-50);
			},
			rules: { 
				txtgradename: { required: true, lettersonly: true },
				startdate1: { required: true },
				enddate1: { required: true, greaterThan: "#startdate1" }
			}, 
			messages: { 
				txtgradename: { required: "Please type Grade Period Name", lettersonly:"Please enter letters and numbers only" },
				startdate1:{  required: "Select the start date" },
				enddate1:{  required: "Select the end date", greaterThan: "Must be greater than Start date." }
			},
			highlight: function(element, errorClass, validClass) {
				$(element).parents('dl').addClass(errorClass);
				$(element).addClass(errorClass).removeClass(validClass);
			},
			unhighlight: function(element, errorClass, validClass) {
				if($(element).attr('class') == 'error'){
					$(element).parents('dl').removeClass(errorClass);
					$(element).removeClass(errorClass).addClass(validClass);
				}
			},
			onkeyup: false,
			onblur: true
		});
	});
}

function fn_showupdate(id)
{	
	$("#showperioddiv").load("reports/gradebook/reports-gradebook.php #showperioddiv > *",{"id":id}, function(){
		formvalid();
	});
}


function fn_show(gradeperiodid,classid,top,left)
{
	var val = gradeperiodid+","+classid+","+top+","+left;
	console.log("gradeperiod="+val);
        ajaxloadingalert('Loading, please wait.');
	setTimeout('showpageswithpostmethod("reports-gradebook-showinnertable","reports/gradebook/reports-gradebook-showinnertable.php","id='+val+'");',500);
}

function fn_remove(id)
{
	var dataparam = "oper=remove&id="+id;
	$.Zebra_Dialog('Are you sure you want to delete the selected grading period?',
	{
		'type': 'confirmation',
		'buttons': [
			{caption: "No, don't delete.", callback: function() { }},
			{caption: 'Yes delete.', callback: function() {
				
				$.ajax({
					type: "POST",
					url: 'reports/gradebook/reports-gradebook-gradebookajax.php',
					data: dataparam,
					beforeSend:function()
					{
						showloadingalert("Checking, please wait.");
					},
					success: function(data)
					{
						$('.lb-content').html("Deleted successfully");
						setTimeout('closeloadingalert()',1000);
						
						fn_showtable($('#classid').val(),0,0);
						fn_showperiod();
					}
				});
			}
		}]
	});
	
	
	var val = gradeperiodid+","+classid;
	removesections("#reports-gradebook");
        ajaxloadingalert('Loading, please wait.');
	setTimeout('showpageswithpostmethod("reports-gradebook-showinnertable","reports/gradebook/reports-gradebook-showinnertable.php","id='+val+'");',1000);
}

function fn_maxpoints(){
   
    var modids= new Array();
    $('input:hidden[name^="modids"]').each(function() {
        modids.push($(this).val());
    });
   var checkboxeid = new Array();
    $("input:checked").each(function() {
       checkboxeid.push($(this).val());

    });
    var maxrk=$('#perfrpoints').val(); 
    var mark=maxrk.split(',');
    var modidssize=modids.length;
    var chckedsize=checkboxeid.length ;
   for(var i=1;i<=modidssize;i++)
    { 
        var pcount=i;
        for(var j=0;j<chckedsize;j++)
        {
        	
            var fill=$('#perfmark'+i+'_'+checkboxeid[j]).val();
              
            if(fill=='')
            {
            $('#perfmark'+i+'_'+checkboxeid[j]).val(mark[pcount]);
            pcount=pcount+3;  
            }
        }
    }
     
    
}
function fn_assignpoints(){
	
    var modids= new Array();
    $('input:hidden[name^="modids"]').each(function() {
        modids.push($(this).val());
    });
    
    
    var checkboxeid = new Array();
    $("input:checked").each(function() {
       checkboxeid.push($(this).val());
    });
    
    var textval=$('#textvlaue').val();
    var modidssize=modids.length;
    var chckedsize=checkboxeid.length;

   
    for(var i=1;i<=modidssize;i++)
    { 
        for(var j=0;j<chckedsize;j++)
        {
        	
            var fill=$('#perfmark'+i+'_'+checkboxeid[j]).val();
              
            if(fill=='')
            {
            $('#perfmark'+i+'_'+checkboxeid[j]).val(textval);
            }
        }
    }
 }
function fn_performance(classid,rotationid,scheduleid,scheduletype)
{
   var checkboxeid = new Array();
        $("input:checked").each(function() {
           checkboxeid.push($(this).val());
        });
        var dataparam = "oper=assignperformance&classid="+classid+"&rotationid="+rotationid+"&scheduleid="+scheduleid+"&scheduletype="+scheduletype+"&checkboxeid="+checkboxeid;
	$.ajax({
		type	: "POST",
		cache	: false,
		url	: "reports/gradebook/reports-gradebook-gradebookajax.php",
		data	: dataparam,
		success : function(data) {
			$.fancybox(data,{'modal': true, 'showCloseButton': false, 'autoDimensions': false, 'width': 800, 'height': 520});
		}
	});
       //alert(checkboxeid);                 //alert(performanceval);
}
function fn_chekvalue(id){

$("#performancechck_"+id).attr("style","");


	  if($('#performancechck_'+id).attr('checked')){

             $("#savebutt").show();
        }
        else{

             $("#savebutt").hide();
        }
}
function fn_cleartextval(asstype,asseval){
	
	
  // var test=asstype.length;
   //alert(asstype);

	var maxrk=$('#perfrpoints').val(); 
    var mark=maxrk.split(',');
    //alert(mark);
	
	var child = $('#mytabledata tr').length;
	for(var i=0;i<asstype;i++)
      { 
		var pcount=i;
		for(var j=1;j<=child;j++){

		if(j==1 && $('#perfmark'+asstype+'_'+j==1)){
			if(parseInt(asseval) > parseInt(mark[0]))
			{
				$('#perfmark'+asstype+'_'+j).val('');
}
		}

		//alert(mark[1]);

		if(j==2 && $('#perfmark'+asstype+'_'+j==2)){
			//alert($('#perfmark'+asstype+'_'+j).val() +" test   "+ parseInt(mark[1]));

			if(parseInt(asseval) > parseInt(mark[1]))
			{
				$('#perfmark'+asstype+'_'+j).val('');
			}
		}
		if(j==3 && $('#perfmark'+asstype+'_'+j==3)){
			if(parseInt(asseval) > parseInt(mark[2]))
			{
				$('#perfmark'+asstype+'_'+j).val('');
			}
		}

            //alert(('#perfmark'+asstype+'_'+j)); 
          // alert(mark[pcount]);
           pcount=pcount+3;
       }
	 }
//alert(asstype);


}


/*--- Save and Update the  Rubric Code Start Here For EXPEDITION MISSION EXPEDITION SCHEDULE****************/
function fn_showrubricpoints(type,classid,studentid,expid,rubricid,scheduleid)
{
	//alert(type+"~"+classid+"@"+studentid+"!"+expid+"%"+rubricid);
    var val = type+","+classid+","+studentid+","+expid+","+rubricid+","+scheduleid;
console.log(val);
    if(type==16 || type==18 || type==20 || type==21 || type==24 || type==25)
    {
        removesections("#reports-gradebook-edit");
    }
    else
    {
        removesections("#reports-gradebook-showinnertable");
    }

    ajaxloadingalert('Loading, please wait.');
     setTimeout('showpageswithpostmethod("reports-gradebook-rubricedit","reports/gradebook/reports-gradebook-rubricedit.php","id='+val+'");',500);
    //
    //
    // var val = "9"+","+classid+","+studentid+","+expid+","+rubricid+","+scheduleid;
    //
    // if(type==16 || type==20 || type==21 || type==24 || type==25)
    // {
    //     removesections("#reports-gradebook-edit");
    // }
    // else
    // {
    //     removesections("#reports-gradebook-showinnertable");
    // }
    //
    // ajaxloadingalert('Loading, please wait.');
    //setTimeout('showpageswithpostmethod("library-rubric-gradestudentrubric","library/rubric/library-rubric-gradestudentrubric.php","id='+val+'");',500);

    // var dataparam = "oper=showperformance&expid="+expid+"&rubid="+rubricid+"&type="+type;
    // console.log(dataparam);
    // $.ajax({
    //     type: 'post',
    //     url: 'reports/gradebook/reports/gradebook/reports-gradebook-gradebookajax.php',
    //     data: dataparam,
    //     beforeSend: function(){
    //     },
    //     success:function(data) {
    //         $('#rubricstmt').show();
    //         $('#rubricstmt').html(data);//Used to load the student details in the dropdown
    //         $('#viewreportdiv').show();
    //     }
    // });
}


//auto save when click a category

function fn_showdeststmt(id,weight,rubid,destid,rubnameid,type)
{
    var rubrictxt = $("#rubrictxt-" + rubid + "-" + id);

    var multi=id*weight;
    var list10 = [];
    var textvalu=[];
    //multiply the value
    console.log(rubrictxt.html());
    if(rubrictxt.hasClass("td_select")) {
        $('#txtscore-' + rubid).val(multi);
    }else{
        $('#txtscore-'+rubid).val('');
    }

    //get the textbox value
    $('input:text[name=txtscore]').each(function() {
        textvalu.push($(this).val());
    });
    //get the selected student id
    $("div[id^=list10_]").each(function(){
        list10.push($(this).attr('id').replace('list10_',''));
    });


    var totalscore=  $('#totalscore').val();

    var prescore=  $('#rubrictxtoldval_'+rubid).val();
    var curscore=  multi;

    if(totalscore=='')
    {
        totalscore=0;
    }
    if(prescore=='')
    {
        prescore=0;
    }

    var tscore=parseInt(totalscore)-parseInt(prescore);

    if(rubrictxt.hasClass("td_select")) {
        $('#rubrictxtoldval_'+rubid).val(curscore);
        var ftscore=parseInt(tscore)+parseInt(curscore);
    }else{
        $('#rubrictxtoldval_'+rubid).val('');
        var ftscore=parseInt(tscore);
    }
    $('#totalscore').val(ftscore);
    $('#studentscore').html(ftscore);


}

//
// function fn_showdeststmt(id,weight,rubid,destid,rubnameid,typeid)
// {
//
//     var rubrictxt = $("#rubrictxt-" + rubid + "-" + id);
//
//     var multi=id*weight;
//     var list10 = [];
//     var textvalu=[];
//     //multiply the value
//     console.log(rubrictxt.html());
//     if(rubrictxt.hasClass("td_select")) {
//         $('#txtscore-' + rubid).val(multi);
//     }else{
//         $('#txtscore-'+rubid).val('');
//     }
//
//     //get the textbox value
//     $('input:text[name=txtscore]').each(function() {
//         textvalu.push($(this).val());
//     });
//     //get the selected student id
//     $("div[id^=list10_]").each(function(){
//         list10.push($(this).attr('id').replace('list10_',''));
//     });
//
//
//     var totalscore=  $('#totalscore').val();
//
//     var prescore=  $('#rubrictxtoldval_'+rubid).val();
//     var curscore=  multi;
//
//     if(totalscore=='')
//     {
//         totalscore=0;
//     }
//     if(prescore=='')
//     {
//         prescore=0;
//     }
//
//     var tscore=parseInt(totalscore)-parseInt(prescore);
//
//     if(rubrictxt.hasClass("td_select")) {
//         $('#rubrictxtoldval_'+rubid).val(curscore);
//         var ftscore=parseInt(tscore)+parseInt(curscore);
//     }else{
//         $('#rubrictxtoldval_'+rubid).val('');
//         var ftscore=parseInt(tscore);
//     }
//     $('#totalscore').val(ftscore);
//     $('#studentscore').html(ftscore);
//     //
//     // var multi=id*weight;
//     // var list10 = [];
//     // var textvalu=[];
//     // //multiply the value
//     // $('#txtscore-'+rubid).val(multi);
//     //
//     // //get the textbox value
//     // $('input:text[name=txtscore]').each(function() {
//     //     textvalu.push($(this).val());
//     // });
//     //
//     // var totalscore=  $('#totalscore').val();
//     //
//     // var prescore=  $('#rubrictxtoldval_'+rubid).val();
//     // var curscore=  multi;
//     //
//     // if(totalscore=='')
//     // {
//     //     totalscore=0;
//     // }
//     // if(prescore=='')
//     // {
//     //     prescore=0;
//     // }
//     //
//     // var tscore=parseInt(totalscore)-parseInt(prescore);
//     // var ftscore=parseInt(tscore)+parseInt(curscore);
//     // //alert(totalscore+" - "+prescore+" - "+curscore+" score- "+ftscore);
//     //
//     // $('#totalscore').val(ftscore);
//     // $('#rubrictxtoldval_'+rubid).val(curscore);
//     // $('#studentscore').html(ftscore);
//     var dataparam = "oper=saverubric&expid="+$('#expid').val()+"&classid="+$('#classid').val()+"&scheduleid="+$('#scheduleid').val()+"&txtscore="+multi+"&rubnameid="+rubnameid+"&ruborderid="+rubid+"&destid="+destid+"&studentid="+$('#studentid').val()+"&typeid="+typeid+"&cellid="+id;
//     //alert(dataparam);
//
//     $.ajax({
//             url: "reports/gradebook/reports-gradebook-gradebookajax.php",
//             data: dataparam,
//             type: "POST",
//             beforeSend: function(){
//                     showloadingalert("Saving, please wait...");
//             },
//             success: function (data) {
//                     if(data=="success") //Works if the data saved in db
//                     {
//                         setTimeout('closeloadingalert();',500);
//                     }
//                     else
//                     {
//                         setTimeout('closeloadingalert();',500);
//                     }
//             },
//     });
// }

function fn_highlight(cellid,rubid)
{
        var otherval = [];
        $('#rubrictxt-'+rubid+'-'+cellid).toggleClass("td_select");

        for(a=0;a<=4;a++)
        {
                if(parseInt(a)!=cellid)
                {
                        otherval.push(a);
                }

        }
        for(b=0;b<otherval.length;b++)
        {
                $('#rubrictxt-'+rubid+'-'+otherval[b]).removeClass("td_select");
        }
}

function fn_saverubric(id,expid)
{
    var top = $('#myTable06').parent('div').scrollTop();
    var left = $('#myTable06').parent('div').scrollLeft();
    var gradeperiodid = $('#hidgradeperiodid').val();
    var classid=$('#classid').val();
    
    removesections("#reports-gradebook");
    fn_showtable(classid,0,0,gradeperiodid,classid,top,left,1);
}

function fn_saverubricforexp(rubid, type,classid,studentid,expid,schid) // Mohan M Developed for Remove rubric for Expedition 5-7-2016
{
	if(type==16)
	{
		type=15;
	}
	else if(type==21)
	{
		type=19;
	}
	else if(type==25)
	{
		type=20;
	}
	else if(type==18)
	{
		type=18;
	}
	else
	{
		type=23;
	}
    var val = type+","+classid+","+studentid+","+expid+","+schid;

    // Code by barney related to #23153
    var comments=[];
    $('.commentbox').each(function() {
        comments.push($(this).val());
    });
    console.log(comments);

    var score=[];
    $('input[id^=rubrictxtoldval_]').each(function()
    {
        score.push($(this).val());
    });

    var ids = [];
    $("input[id^=ids_]").each(function()
    {
        ids.push($(this).attr('id').replace('ids_',''));
    });
    var dataparam = "oper=saverubricval&list10="+studentid+"&ids="+ids+"&expid="+expid+"&rubid="+rubid+"&classid="+classid+"&schid="+schid+"&score="+score+"&comments="+comments;
    console.log(dataparam);

    $.ajax({
        url: 'library/rubric/library-rubric-gradestudentrubricajax.php',
        data: dataparam,
        type: "POST",
        beforeSend: function(){
            showloadingalert("Saving, please wait...");
        },
        success: function (data)
        {
            if(data=="success") //Works if the data saved in db
            {
                setTimeout('closeloadingalert()',500);
                console.log(data);
            }else{
                setTimeout('closeloadingalert()',500);
                console.log(data);
            }
        },
        error: function (data) {
            alert(data);

        }
    });


    // var top = $('#myTable06').parent('div').scrollTop();
    // var left = $('#myTable06').parent('div').scrollLeft();
    // var gradeperiodid = $('#hidgradeperiodid').val();
    // var valone = gradeperiodid+","+classid;
    
    // removesections("#reports-gradebook");
    // fn_showtable(classid,0,0,gradeperiodid,classid,top,left,1);
    
	//removesections("#reports-gradebook");
	//removesections("#reports-gradebook-showinnertable");
	
	//showpageswithpostmethod("reports-gradebook-showinnertable","reports/gradebook/reports-gradebook-showinnertable.php","id="+valone);
	//showpageswithpostmethod("reports-gradebook-edit","reports/gradebook/reports-gradebook-edit.php","id="+val);
}
/*--- Save and Update the  Rubric Code Start Here For EXPEDITION MISSION EXPEDITION SCHEDULE****************/

function fn_assigncustompoints()
{
 var customtextvalue=$('#customtextvlaue').val();  
 var stucount=$('#stucount').val();
 
    for(var c=1;c<=stucount;c++)
    {
     
        //var fill=$('#customtext_'+c).val();
       // if(fill=='')
        //{
            $('#customtext_'+c).val(customtextvalue);
       // }
    }
}

/**************Activity code developed by Mohan M**************/
function fn_assignactivitypoints(maxpoints)
{
    var customtextvalue=$('#activitytextvlaue').val();  
    var stucount=$('#activitystucount').val();
 
    for(var c=1;c<=stucount;c++)
    {
        //var fill=$('#activitytxt_'+c).val();
        // if(fill=='')
        //{
        $('#activitytxt_'+c).val(customtextvalue);
        // }
    }
   
}

function ChkValidChar(id)
{
    var txtbx = document.getElementById(id).value;
    var nexttxtbx = $('#activitymaxpoints').val();
    if(parseInt(txtbx) > parseInt(nexttxtbx)) // true
    {
        document.getElementById(id).value = "";
    }
}
/**************Activity code developed by Mohan M**************/

function fn_assigniplpoints()
{
 var ipltextvalue=$('#ipltextvlaue').val();  
 var stucount=$('#stucount').val();
   for(var c=1;c<=stucount;c++)
    {

        var fill=$('#ipltext_'+c).val();
        if(fill=='')
        {
            $('#ipltext_'+c).val(ipltextvalue);
        }
    }
}
function fn_custommaxpoints()
{
 var custommaxpoints = $('#custommaxpoints').val();   
 var stucount=$('#stucount').val();
 for(var c=1;c<=stucount;c++)
    {
     
        var fill=$('#customtext_'+c).val();
        if(fill=='')
        {
            $('#customtext_'+c).val(custommaxpoints);
        }
    }
        
}
/**************Activity code developed by Mohan M**************/
function fn_activitymaxpoints(custommaxpoints)
{
    //var custommaxpoints ='50';   
    var stucount=$('#activitystucount').val();
    for(var c=1;c<=stucount;c++)
    {

        var fill=$('#activitytxt_'+c).val();
        if(fill=='' || fill=='0')
        {
            $('#activitytxt_'+c).val(custommaxpoints);
        }
    }
}
/**************Activity code developed by Mohan M**************/
function fn_iplmaxpoints()
{
 var iplmaxpoints = 100;   
 var stucount=$('#stucount').val();
 for(var c=1;c<=stucount;c++)
    {
 
        var fill=$('#ipltext_'+c).val();
        if(fill=='')
        {
            $('#ipltext_'+c).val(iplmaxpoints);
}
    }
 
}

/**********Mission Schedule Max points code start here Developed by Mohan M 30-05-2016*************/
function fn_chekgvalue(id)
{
    var chkval=$('#checkgdval').val();  
    if($('#gradingrubric_1'). prop("checked") == true)
    {
        //alert("Checkbox is checked.");
         var finchkval=parseInt(chkval)+parseInt(id); 
        $('#checkgval').val('1');
    }
    else
    {
        var finchkval=parseInt(chkval)-parseInt(id);
        //alert("Checkbox is NOT checked.");
        $('#checkgval').val('0');
    }
    
    var showbtn= $('#checkgdval').val(finchkval);
    var chkval=$('#checkgdval').val();   var chkdgval=$('#checkdgval').val(); 
    if((parseInt(chkval)==0) && (parseInt(chkdgval)==0))
    {
       $("#savebutt").hide();
    }
    else
    {
       $("#savebutt").show();
    }
}


function fn_chekdvalue(id)
{
    var chkval=$('#checkdgval').val();  
    if($('#debrief_1'). prop("checked") == true)
    {
        //alert("Checkbox is checked.");
         var finchkval=parseInt(chkval)+parseInt(id); 
        $('#checkdval').val('1');
    }
    else
    {
        var finchkval=parseInt(chkval)-parseInt(id);
        //alert("Checkbox is NOT checked.");
        $('#checkdval').val('0');
    }
    var showbtn=$('#checkdgval').val(finchkval);
    var chkval=$('#checkgdval').val();   var chkdgval=$('#checkdgval').val(); 
    if((parseInt(chkval)==0) && (parseInt(chkdgval)==0))
    {
       $("#savebutt").hide();
    }
    else
    {
       $("#savebutt").show();
    }
}

function ChkValidCharmis(id)
{
    var txtbx = document.getElementById(id).value;
    var nexttxtbx = $('#maxpointsmis').val();
    
    // var chkvald=$('#teachertype').val(); 
     //var add=1;
    if(parseInt(txtbx) > parseInt(nexttxtbx)) // true
    {
        //alert(txtbx+" > "+nexttxtbx);
        document.getElementById(id).value = "";
       // var finchkval=parseInt(chkvald)-parseInt(add); 
    }
    /*else
    {
         var finchkval=parseInt(chkvald)+parseInt(add); 
    }
    
    $('#teachertype').val(finchkval);
    
    var chkva=$('#teachertype').val(); 
    
    if(parseInt(chkva)==0)
    {
       $("#savebutt").hide();
    }
    else
    {
       $("#savebutt").show();
    }
    */
}

function fn_assignpointsgmis(type,val)
{
    var stucount = $('#modcountmis').val();
    var textvalg=$('#textvlaueg').val();
    var textvald=$('#textvlaued').val();
    var chkvalg=$('#checkgdval').val(); 
    var chkvald=$('#checkdgval').val(); 
    if(type==1) // Grading Rubric
    {
        if(parseInt(chkvalg)!=0)
        {
            var mm=0;
            $("input[id^=rubricscore_2]").each(function()
            {
               var misbasicid = $(this).attr('id').replace('rubricscore_2','');
                
                //console.log('#perfmark_'+mm+'_2'+misbasicid);
               
                var fill=$('#perfmark_'+mm+'_2'+misbasicid).val();

                if(fill=='')
                {
                    $('#perfmark_'+mm+'_2'+misbasicid).val(textvalg);
                }
                mm++;
            })
        }
    }
    else if(type==2) // Debrief
    {
        if(parseInt(chkvald)!=0)
        {
            var mm=0;
            $("input[id^=rubricscore_3]").each(function()
            {
               var misbasicid = $(this).attr('id').replace('rubricscore_3','');
                
                //console.log('#perfmark_'+mm+'_2'+misbasicid);
               
                var fill=$('#perfmark_'+mm+'_3'+misbasicid).val();

                if(fill=='')
                {
                    $('#perfmark_'+mm+'_3'+misbasicid).val(textvald);
                }
                mm++;
                
            })
        }
    }
}

function fn_maxpointsmis(type,val)
{
    var stucount = $('#modcountmis').val();
    var chkvalg=$('#checkgdval').val(); 
    var chkvald=$('#checkdgval').val(); 
    
    if(type==1) // Grading Rubric
    {
        if(parseInt(chkvalg)!=0)
        {
            var mm=0;
            $("input[id^=rubricscore_2]").each(function()
            {
               var misbasicid = $(this).attr('id').replace('rubricscore_2','');
                
                //console.log('#perfmark_'+mm+'_2'+misbasicid);
               
                var fill=$('#perfmark_'+mm+'_2'+misbasicid).val();

                if(fill=='')
                {
                    $('#perfmark_'+mm+'_2'+misbasicid).val(val);
                }
                mm++;
            })
        }
    }
    else if(type==2) // Debrief
    {
        if(parseInt(chkvald)!=0)
        {
            var mm=0;
            $("input[id^=rubricscore_3]").each(function()
            {
               var misbasicid = $(this).attr('id').replace('rubricscore_3','');
                
                //console.log('#perfmark_'+mm+'_2'+misbasicid);
               
                var fill=$('#perfmark_'+mm+'_3'+misbasicid).val();

                if(fill=='')
                {
                    $('#perfmark_'+mm+'_3'+misbasicid).val(val);
                }
                mm++;
                
            })
        }
    } 
}


/**********Mission Schedule Max points code End here Developed by Mohan M 30-05-2016*************/