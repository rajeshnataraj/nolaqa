<?php
@include("sessioncheck.php");

$date=date("Y-m-d H:i:s");

$id = isset($method['id']) ? $method['id'] : '0';
$id = explode(",",$id);

$type='1';
//echo $uid.' '.$schoolid.' '.$indid;
$tempclassid = $id[1];
$assingnmentid = '';
$rubrics = array();
$temprubrics = dbSelect("itc_class_expmis_rubricmaster", array("fld_delstatus" => 0, "fld_class_id" => $tempclassid, "fld_created_by" => $uid));
foreach ($temprubrics as $temprubric){
//    print_r($temprubric);
    if(count($temprubric) > 1) {
        if((int)$temprubric["fld_schedule_type"] == 18){
            $misindass = dbSelect("itc_class_indasmission_master", array("fld_id" => $temprubric["fld_schedule_id"], "fld_class_id" => $tempclassid, "fld_delstatus" => 0, "fld_lock" => 0, "fld_flag" => 1));
            if(count($misindass[0]) > 1){
                $rubrics[] = $temprubric;
            }
        }
        elseif((int)$temprubric["fld_schedule_type"] != 17 && (int)$temprubric["fld_schedule_type"] != 19 && (int)$temprubric["fld_schedule_type"] != 20){
            $expindass = dbSelect("itc_class_indasexpedition_master", array("fld_id" => $temprubric["fld_schedule_id"], "fld_class_id" => $tempclassid, "fld_delstatus" => 0, "fld_lock" => 0, "fld_flag" => 1));
            if(count($expindass[0]) > 1){
                $rubrics[] = $temprubric;
            }
        }
        else{
			if((int)$temprubric["fld_schedule_type"] == 17){
				
				$exprotational = dbSelect("itc_class_rotation_expschedule_mastertemp", array("fld_id" => $temprubric["fld_schedule_id"],  "fld_class_id" => $tempclassid,  "fld_delstatus" => 0));

					if (count($exprotational[0]) > 1) {

					foreach ($exprotational as $exprotationalvalue) {
						
						$expstudentcount = dbSelect("itc_class_rotation_expschedulegriddet", array("fld_schedule_id" => $temprubric["fld_schedule_id"], "fld_expedition_id" => $temprubric["fld_expmisid"]));
						if(count($expstudentcount[0]) > 1){
							$rubrics[] = $temprubric;
						}
					}
				}
			}
			elseif((int)$temprubric["fld_schedule_type"] == 19 || (int)$temprubric["fld_schedule_type"] == 20){
				
				$exprotational = dbSelect("itc_class_rotation_mission_mastertemp", array("fld_id" => $temprubric["fld_schedule_id"],  "fld_class_id" => $tempclassid,  "fld_delstatus" => 0));

					if (count($exprotational[0]) > 1) {

					foreach ($exprotational as $exprotationalvalue) {
						
						$expstudentcount = dbSelect("itc_class_rotation_mission_schedulegriddet", array("fld_schedule_id" => $temprubric["fld_schedule_id"], "fld_mission_id" => $temprubric["fld_expmisid"]));
						if(count($expstudentcount[0]) > 1){
							$rubrics[] = $temprubric;
						}
					}
				}
			}
        }
    }
}
//print_r($rubrics);



//$dbCon->select("itc_class_expmis_rubricmaster", array("fld_class_id" => ))
?>

    <script language="javascript" type="text/javascript">


        function fn_movealllistitems(leftlist,rightlist,id,courseid,typeid,stype, rubid)
        {
            var list9 = [];
            $("div[id^=list9_]").each(function(){
                list9.push($(this).attr('id').replace('list9_',''));
            });
            var list10 = [];
            $("div[id^=list10_]").each(function(){
                list10.push($(this).attr('id').replace('list10_',''));
            });
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

                if(leftlist=="list3" || leftlist=="list4" && rightlist=="list4" || rightlist=="list3"  )
                {
                    var list4 = [];

                    $("div[id^=list4_]").each(function(){
                        list4.push($(this).attr('id').replace('list4_',''));
                    });

                    if(list4!='')
                    {
                        fn_showrubricstmt(courseid,rubid,typeid,stype);
                    }
                    else
                    {
                        $('#rubricstmt').hide();
                        $('#viewreportdiv').hide();
                    }
                }
            }
            else
            {

                var clas=$('#'+leftlist+'_'+id).attr('class');
                if(clas=="draglinkleft")
                {
                    $('#'+rightlist).append($('#'+leftlist+' #'+leftlist+'_'+id));
                    $('#'+leftlist+'_'+id).removeClass('draglinkleft').addClass('draglinkright');
                    var temp = $('#'+leftlist+'_'+id).attr('id').replace(leftlist,rightlist);
                    var ids='id';
                    $('#'+leftlist+'_'+id).attr(ids,temp);
                }
                else
                {
                    $('#'+leftlist).append($('#'+rightlist+' #'+rightlist+'_'+id));
                    $('#'+rightlist+'_'+id).removeClass('draglinkright').addClass('draglinkleft');
                    var temp = $('#'+rightlist+'_'+id).attr('id').replace(rightlist,leftlist);
                    var ids='id';
                    $('#'+rightlist+'_'+id).attr(ids,temp);
                }

            }

            if(leftlist=="list9" || leftlist=="list10" && rightlist=="list10" || rightlist=="list9"  )
            {
                var list10 = [];

                $("div[id^=list10_]").each(function(){
                    list10.push($(this).attr('id').replace('list10_',''));
                });

                if(list10!='')
                {
                    fn_showrubricstmt(courseid,rubid,typeid,stype);
                }
                else
                {
                    $('#rubricstmt').hide();
                    $('#viewreportdiv').hide();
                }
            }

            if(leftlist=="list3" || leftlist=="list4" && rightlist=="list4" || rightlist=="list3"  )
            {
                var list4 = [];

                $("div[id^=list4_]").each(function(){
                    list4.push($(this).attr('id').replace('list4_',''));
                });

                if(list4!='')
                {
                    fn_showrubricstmt(courseid,rubid,typeid,stype);
                }
                else
                {
                    $('#rubricstmt').hide();
                    $('#viewreportdiv').hide();
                }
            }
        }


        function fn_showrubricstmt(expid,rubid,typeid,stype)
        {
            var clsid=$("#hidclassid").val();
            var dataparam = "oper=showrubricstmt&expid="+expid+"&rubid="+rubid+"&type="+typeid+"&schid="+stype+"&classid="+clsid;
            console.log(dataparam);

            var list10 = [];

            $("div[id^=list10_]").each(function(){
                list10.push($(this).attr('id').replace('list10_',''));
            });
            $.ajax({
                type: 'post',
                url: 'library/rubric/library-rubric-gradestudentrubricajax.php',
                data: dataparam,
                beforeSend: function(){
                },
                success:function(data) {
                    $('#reports-pdfviewer').hide();
                    $('#rubricstmt').show();
                    $('#rubricstmt').html(data);//Used to load the student details in the dropdown
                    $('#viewreportdiv').show();
                    if(list10.length < 2){
                        $('html, body').animate({
                            scrollTop: $(window).scrollTop() + 125
                        }, 'slow');
                    }
                }
            });
        }

        function fn_showstudents(expid,clsid,rubid,schid,stype)
        {

            $('#viewreportdiv').hide();
            $('#rubricstmt').hide();
            var schtype = '1';

            var dataparam = "oper=showstudent&clsid="+clsid+"&expid="+expid+"&rubid="+rubid+"&schid="+schid+"&stype="+stype;
            console.log(dataparam);
            $.ajax({
                type: 'post',
                url: 'reports/rubric/reports-rubric-teacherajax.php',
                data: dataparam,
                beforeSend: function(){
                    $('#studentdiv').html('<img src="img/loader.gif" width="200"  border="0" />');
                },
                success:function(data) {
                    $('#reports-pdfviewer').hide();
                    $('#gradingrubricmain').slideDown();
                    $('#studentdiv').show();
                    $('#studentdiv').html(data);//Used to load the student details in the dropdown
					$('html,body').animate({ scrollTop: 9999 }, 'slow');
                }
            });
        }
        function fn_viewdigirubric(expid,clsid,rubid,list10,schid,stype)
        {
            $('#viewreportdiv').hide();
            $('#studentdiv').hide();
            $('#rubricstmt').hide();
            var val2 = expid+"~"+clsid+"~"+rubid+"~"+list10+"~"+schid;
            console.log(val2);
            //var dataparam = "oper=showrubric&expid="+expid+"&type="+type+"&clsid="+clsid+"&schid="+schid;
            setTimeout('removesections("#reports-digitalrubric");',100);
            setTimeout('removesections("#reports-pdfviewer");',100);

            oper="digitalrubricreport";
            filename='digitalrubricreport' + new Date().getTime();
//
//
            ajaxloadingalert('Loading, please wait.');
            setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val2+'&oper='+oper+'&filename='+filename+'");',350);

        }

        function fn_resetrubric(id,expid)
        {
            var txtscore = [];
            $.Zebra_Dialog('Are you sure you want to reset all entered values to empty ?',
                {
                    'type':     'confirmation',
                    'buttons':  [
                        {caption: 'No', callback: function() { }},
                        {caption: 'Yes', callback: function() {
                            $("input[id^=txtscore-]").each(function()
                            {
                                txtscore.push($(this).attr('id').replace('txtscore-',''));
                                var tscore = '';
                                for(var i=0; i<txtscore.length; i++)
                                {
                                    var text = txtscore[i];
                                    var res = text.split(",");
                                    if(tscore=='')
                                    {
                                        var textscore=res[0];
                                    }
                                    else
                                    {
                                        var textscore=textscore+","+res[0];
                                    }

                                    $('#txtscore-'+textscore).val('');
                                    $('#rubrictxtoldval_'+textscore).val('');
                                }
                            });
                            $("textarea.commentbox").val('');
                            $('.studentscore').text('');
                            $('#totalscore').val('');
                            $('.centerText').removeClass("td_select");
                        }},
                    ]
                });
        }

    </script>
    <section data-type='2home' id='library-rubric-gradestudentrubric'>
        <div class="container">
            <div class="row">
                <div class="twelve columns">
                    <p class="darkTitle">Grading Rubrics</p>
                    <!--<p class="dialogSubTitleLight">Choose a reporting category to view its individual reports.</p>-->
                    <p class="dialogSubTitle">Select a Grading Rubric From The List Below</p>
                </div>
            </div>

            <div class="row buttons rowspacer">

                <!--- Completion Report Code Start Here -->
                <?php
                foreach ($rubrics as $newr) {

//                    $newr = array();
//                    if((string)$r["fld_schedule_type"]=='15')
//                    {
//                        $qrystudent= $ObjDB->QueryObject("SELECT b.fld_student_id as studentid FROM itc_class_indasexpedition_master AS a LEFT JOIN itc_class_exp_student_mapping AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_user_master AS c ON c.fld_id=b.fld_student_id
//                                          WHERE a.fld_class_id='".$r["fld_class_id"]."' AND b.fld_schedule_id='".$r["fld_schedule_id"]."' AND a.fld_exp_id='".$r["fld_expmisid"]."'
//                                          AND a.fld_flag='1' AND b.fld_flag='1' AND c.fld_profile_id = '10'  AND c.fld_activestatus = '1'  AND c.fld_delstatus = '0'  group by studentid");//b.fld_student_id NOT IN(SELECT fld_student_id FROM itc_exp_rubric_rpt_stu_mapping where fld_rubric_rpt_id='".$rubricid."' AND fld_delstatus='0') AND
//                    }
//                    else if((string)$r["fld_schedule_type"]=='23' || (string)$r["fld_schedule_type"]=='18')
//                    {
//                        $qrystudent= $ObjDB->QueryObject("SELECT b.fld_student_id as studentid FROM itc_class_indasmission_master AS a LEFT JOIN itc_class_mission_student_mapping AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_user_master AS c ON c.fld_id=b.fld_student_id
//                                                  WHERE a.fld_class_id='".$r["fld_class_id"]."' AND b.fld_schedule_id='".$r["fld_schedule_id"]."' AND a.fld_mis_id='".$r["fld_expmisid"]."'
//                                                  AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_flag='1' AND c.fld_profile_id = '10'  AND c.fld_activestatus = '1'  AND c.fld_delstatus = '0'  group by studentid");
//                    }
//                    else if((string)$r["fld_schedule_type"]=='20')
//                    {
//                        $qrystudent= $ObjDB->QueryObject("SELECT b.fld_student_id as studentid FROM itc_class_rotation_modexpschedulegriddet AS a LEFT JOIN itc_class_rotation_modexpschedule_student_mappingtemp AS b ON a.fld_student_id=b.fld_student_id LEFT JOIN itc_user_master AS c ON c.fld_id=b.fld_student_id
//                                                WHERE a.fld_class_id='".$r["fld_class_id"]."' AND b.fld_schedule_id='".$r["fld_schedule_id"]."' AND a.fld_module_id='".$r["fld_expmisid"]."'
//                                                AND a.fld_type='2'  AND a.fld_flag='1' AND b.fld_flag='1' AND c.fld_profile_id = '10'  AND c.fld_activestatus = '1'  AND c.fld_delstatus = '0'  group by studentid");//b.fld_student_id NOT IN(SELECT fld_student_id FROM itc_exp_rubric_rpt_stu_mapping where fld_rubric_rpt_id='".$rubricid."' AND fld_delstatus='0') AND
//                    }
//                    else
//
////                        LEFT JOIN itc_class_rotation_expscheduledate AS d ON b.fld_schedule_id=d.fld_schedule_id
//                    {
//                        $qrystudent= $ObjDB->QueryObject("SELECT b.fld_student_id as studentid FROM itc_class_rotation_expschedulegriddet AS a
//                                                WHERE a.fld_class_id='".$r["fld_class_id"]."' AND b.fld_schedule_id='".$r["fld_schedule_id"]."'  AND e.fld_id='".$r["fld_schedule_id"]."'  AND a.fld_expedition_id='".$r["fld_expmisid"]."'
//                                                AND a.fld_flag='1'AND b.fld_flag='1' AND c.fld_profile_id = '10'  AND c.fld_activestatus = '1'  AND c.fld_delstatus = '0'  AND e.fld_delstatus = '0'");//b.fld_student_id NOT IN(SELECT fld_student_id FROM itc_exp_rubric_rpt_stu_mapping where fld_rubric_rpt_id='".$rubricid."' AND fld_delstatus='0') AND
//                    }
//                    if($qrystudent->num_rows>0){
//                        $newr = $r;
////                        while($row = $qrystudent->fetch_assoc())
////                        {
////                            $temp = json_encode($row);
////
////                        }
//                        echo'
//                        <script>
//                            console.log("'.$r["fld_expmisid"].' '.(string)($qrystudent->num_rows).' ");
//                        </script>
//                        ';
//
//                        echo '<a class="skip btn main" href="#reports" onclick="fn_showstudents(\''.$newr["fld_expmisid"].'\', \''.$newr["fld_class_id"].'\', \''.$newr["fld_rubric_id"].'\', \''.$newr["fld_schedule_id"].'\', \''.$newr["fld_schedule_type"].'\');">
//                                <div class="icon-synergy-tests"></div>
//                                <div class="onBtn tooltip" original-title="'.$newr["fld_class_name"].' / '.$newr["fld_expmisname"].'">'.$newr["fld_rubric_name"].'</div></a>';
//                }
                    echo '<a class="skip btn main" href="#reports" onclick="fn_showstudents(\''.$newr["fld_expmisid"].'\', \''.$newr["fld_class_id"].'\', \''.$newr["fld_rubric_id"].'\', \''.$newr["fld_schedule_id"].'\', \''.$newr["fld_schedule_type"].'\');">
                                <div class="icon-synergy-tests"></div>
                                <div class="onBtn tooltip" original-title="'.$newr["fld_schedule_name"].' / '.$newr["fld_expmisname"].'">'.$newr["fld_rubric_name"].'</div></a>';

//                    $qry = $ObjDB->QueryObject("SELECT a.fld_rubric_id AS rubricid, a.fld_rubric_name AS rubname, a.fld_id AS tempid
//                                                FROM itc_class_expmis_rubricmaster AS a
//                                                         LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_schedule_id=b.fld_id
//                                                                WHERE a.fld_class_id='".$r["fld_class_id"]."' AND a.fld_schedule_id='".$r["fld_schedule_id"]."' AND a.fld_expmisid='".$r["fld_expmisid"]."' AND b.fld_delstatus='0'
//                                                                AND a.fld_schedule_type='".$r["fld_schedule_type"]."' AND a.fld_delstatus='0'
//                                                                GROUP BY rubricid");
//
//                    if($qry->num_rows>0){
//                        while($row = $qry->fetch_assoc())
//                        {
//                            extract($row);

//                        }
//                    }
                    //echo $r["fld_rubric_name"].' '.$r["fld_class_name"].' '.$r["fld_schedule_name"].' '.$r["fld_expmisname"].'<br>';
                }
                ?>

            </div>

            <div class='row formBase rowspacer' id="gradingrubricmain" style="display: none;">
                <div class='eleven columns centered insideForm' >
                    <form name="rubricforms" id="rubricforms">

                        <!--Shows Student -->
                        <div class="row rowspacer">
                            <div class='twelve columns'>
                                <div id="studentdiv" style="display:none">

                                </div>
                            </div>
                        </div>
                        <!--Shows Student -->

                        <!--Shows Rubric Statement -->
                        <div class="row rowspacer">
                            <div id="rubricstmt" style="display:none">

                            </div>
                        </div>
                        <!--Shows Rubric Statement -->
                    </form>
                </div>
            </div>
        </div>
        <br><br><Br>

    </section>
<?php
@include("footer.php");