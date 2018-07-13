<?php
@include("sessioncheck.php");

$date=date("Y-m-d H:i:s");

$id = isset($method['id']) ? $method['id'] : '0';
$id = explode(",",$id);

$type='1';
//print_r($id);
//echo $uid.' '.$schoolid.' '.$indid;
$clasid = '';
$assingnmentid = '';
$rubrics = array();
$rows = dbSelectSimple("itc_class_student_mapping", "fld_student_id", $uid);
foreach ($rows as $r){
    $tempclass = dbSelect("itc_class_master", array("fld_id" => $r["fld_class_id"], "fld_delstatus" => 0, "fld_archive_class" => 0, "fld_lock" => 0 ));
    if(count($tempclass[0]) > 1){
        $tempclassid = $tempclass[0]["fld_id"];

        $temprubrics = dbSelect("itc_class_expmis_rubricmaster", array("fld_delstatus" => 0, "fld_class_id" => $tempclassid));
        foreach ($temprubrics as $temprubric){
            if(count($temprubric) > 1) {
                if((int)$temprubric["fld_schedule_type"] == 18){
                    $misindass = dbSelect("itc_class_indasmission_master", array("fld_id" => $temprubric["fld_schedule_id"], "fld_delstatus" => 0, "fld_lock" => 0, "fld_flag" => 1));
                    if(count($misindass[0]) > 1){
                        $rubrics[] = $temprubric;
                    }
                }else{
                    $expindass = dbSelect("itc_class_indasexpedition_master", array("fld_id" => $temprubric["fld_schedule_id"], "fld_delstatus" => 0, "fld_lock" => 0, "fld_flag" => 1));
                    if(count($expindass[0]) > 1){
                        $rubrics[] = $temprubric;
                    }
                }
            }
        }
    }
}


//print_r($classes);


//$dbCon->select("itc_class_expmis_rubricmaster", array("fld_class_id" => ))
?>

    <script language="javascript" type="text/javascript">

        function fn_showsch(clsid,type)
        {
            $('#viewreportdiv').hide();
            $('#showexp').hide();
            $('#showrub').hide();
            $('#studentdiv').hide();
            $('#rubricstmt').hide();
            var dataparam = "oper=showschedule&clsid="+clsid+"&type="+type;
            console.log(dataparam);
            $.ajax({
                type: 'post',
                url: 'library/rubric/library-rubric-studentajax.php',
                data: dataparam,
                beforeSend: function(){
                    $('#showsch').html('<img src="img/loader.gif" width="200"  border="0" />');
                },
                success:function(data)
                {
                    $('#showsch').show();
                    $('#showsch').html(data);//Used to load the student details in the dropdown
                }
            });
        }


        function fn_showexp(schid,type)
        {
            $('#viewreportdiv').hide();
            $('#showrub').hide();
            $('#studentdiv').hide();
            $('#rubricstmt').hide();

            var clsid=$("#classid").val();
            var dataparam = "oper=showexpedition&schid="+schid+"&type="+type+"&clsid="+clsid;
            console.log(dataparam);
            $.ajax({
                type: 'post',
                url: 'library/rubric/library-rubric-studentajax.php',
                data: dataparam,
                beforeSend: function(){
                    $('#showexp').html('<img src="img/loader.gif" width="200"  border="0" />');
                },
                success:function(data) {
                    $('#showexp').show();
                    $('#showexp').html(data);//Used to load the student details in the dropdown
                }
            });
        }

        function fn_showrubric(expid,type)
        {
            $('#viewreportdiv').hide();
            $('#studentdiv').hide();
            $('#rubricstmt').hide();
            var clsid=$("#classid").val();
            var schid=$("#schid").val();

            var dataparam = "oper=showrubric&expid="+expid+"&type="+type+"&clsid="+clsid+"&schid="+schid;
            $.ajax({
                type: 'post',
                url: 'library/rubric/library-rubric-studentajax.php',
                data: dataparam,
                beforeSend: function(){
                    $('#showrub').html('<img src="img/loader.gif" width="200"  border="0" />');
                },
                success:function(data) {
                    $('#showrub').show();
                    $('#showrub').html(data);//Used to load the student details in the dropdown
                }
            });
        }
        function fn_viewdigirubric(expid,clsid,rubid,list10,schid)
        {
            $('#viewreportdiv').hide();
            $('#studentdiv').hide();
            $('#rubricstmt').hide();
            $('#reports-pdfviewer').html("").remove();
            removesections("#reports-pdfviewer");
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
                    foreach ($rubrics as $r){
                        //echo $r["fld_rubric_name"].' '.$r["fld_class_name"].' '.$r["fld_schedule_name"].' '.$r["fld_expmisname"].'<br>';
                        echo '<a class="skip btn main" href="#reports" onclick="fn_viewdigirubric(\''.$r["fld_expmisid"].'\', \''.$r["fld_class_id"].'\', \''.$r["fld_rubric_id"].'\', \''.$uid.'\', \''.$r["fld_schedule_id"].'_'.$r["fld_schedule_type"].'\');">
                                <div class="icon-synergy-tests"></div>
                                <div class="onBtn tooltip" original-title="'.$r["fld_class_name"].' / '.$r["fld_expmisname"].'">'.$r["fld_rubric_name"].'</div>
                            </a>';
                    }
                ?>

            </div>
        </div>

    </section>
<?php
@include("footer.php");