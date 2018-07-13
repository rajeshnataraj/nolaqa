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
$rows = dbSelect("itc_class_master", array("fld_delstatus" => 0, "fld_archive_class" => 0, "fld_lock" => 0 ));
//print_r($rows);
foreach ($rows as $r){
    if(count($r) > 1){
        $tempclassid = $r["fld_id"];
        $temprubric = dbSelect("itc_class_expmis_rubricmaster", array("fld_delstatus" => 0, "fld_class_id" => $tempclassid));
        if(count($temprubric[0]) > 1) {
            $rubrics[] = $temprubric[0];
        }
    }
}

//print_r($rubrics);


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
        function fn_viewrubric(rubid,stype)
        {
            $('#viewreportdiv').hide();
            $('#studentdiv').hide();
            $('#rubricstmt').hide();
            var clsid=$("#classid").val();
            var expid=$("#expid").val();
            var schid=$("#schid").val();
            schid = schid + "_" + stype;
            var list10 = '<?php echo $uid; ?>';

            var val = expid+"~"+clsid+"~"+rubid+"~"+list10+"~"+schid;
            //var dataparam = "oper=showrubric&expid="+expid+"&type="+type+"&clsid="+clsid+"&schid="+schid;
            console.log(val);
            setTimeout('removesections("#reports-digitalrubric");',500);
            oper="digitalrubricreport";
            filename='digitalrubricreport' + new Date().getTime();


            ajaxloadingalert('Loading, please wait.');
            setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+filename+'");',500);

//            $.ajax({
//                type: 'post',
//                url: 'library/rubric/library-rubric-gradestudentrubricajax.php',
//                data: dataparam,
//                beforeSend: function(){
//                    $('#showrub').html('<img src="img/loader.gif" width="200"  border="0" />');
//                },
//                success:function(data) {
//                    $('#showrub').show();
//                    $('#showrub').html(data);//Used to load the student details in the dropdown
//                }
//            });
        }

        function fn_showstudents(expid,clsid,rubid,schid)
        {

            $('#viewreportdiv').hide();
            $('#rubricstmt').hide();
            var schtype = '1';

            var dataparam = "oper=showstudent&clsid="+clsid+"&expid="+expid+"&rubid="+rubid+"&type="+schtype+"&schid="+schid;
            console.log(dataparam);
            $.ajax({
                type: 'post',
                url: 'library/rubric/library-rubric-gradestudentrubricajax.php',
                data: dataparam,
                beforeSend: function(){
                    $('#studentdiv').html('<img src="img/loader.gif" width="200"  border="0" />');
                },
                success:function(data) {
                    $('#studentdiv').show();
                    $('#studentdiv').html(data);//Used to load the student details in the dropdown
                }
            });
        }

    </script>
    <section data-type='2home' id='library-rubric-gradestudentrubric'>
        <div class="container">
            <div class="row">
                <div class="twelve columns">
                    <p class="darkTitle">Grading Rubrics</p>
                    <p class="dialogSubTitle">Select a Grading Rubric From The List Below</p>
                </div>
            </div>

            <div class='row rowspacer'>
                <div class='span10 offset1' id="msgdiv">
                    <table class='table table-hover table-striped table-bordered setbordertopradius'>
                        <thead class='tableHeadText'>
                        <tr style="cursor: default;">
                            <th style="padding-left:15px; width:28%;">Grading Rubric</th>
                            <th style="padding-left:15px; width:23%;">Class</th>
                            <th style="padding-left:15px; width:23%;">Schedule</th>
                            <th style="padding-left:15px; width:23%;">Expedition / Mission</th>
                        </tr>
                        </thead>
                    </table>
                    <div style="max-height:400px;width:100%;margin-bottom:0px;" id="tablecontents7" >
                        <table style="margin-bottom:0px;" class='table table-hover table-striped table-bordered bordertopradiusremove'>
                            <tbody>
                            <?php
                                if(count($rubrics) < 1){
                                    echo '<tr><td colspan="4">No Grading Rubrics Found</td></tr>';
                                }else{
                                    foreach ($rubrics as $r){
                                        //echo $r["fld_rubric_name"].' '.$r["fld_class_name"].' '.$r["fld_schedule_name"].' '.$r["fld_expmisname"].'<br>';
                                        echo '<tr onclick="fn_showstudents(\''.$r["fld_expmisid"].'\', \''.$r["fld_class_id"].'\', \''.$r["fld_rubric_id"].'\', \''.$r["fld_schedule_id"].'~'.$r["fld_schedule_type"].'\');">
                                            <td style="padding-left:15px; font-weight:bold; width:28%;" >'.$r["fld_rubric_name"].'</td>
                                            <td style="padding-left:15px; width:23%;">'.$r["fld_class_name"].'</td>
                                            <td style="padding-left:15px; width:23%;">'.$r["fld_schedule_name"].'</td>
                                            <td style="padding-left:15px; width:23%;">'.$r["fld_expmisname"].'</td>
                                        </tr>';
                                    }
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <div class='row formBase rowspacer'>
                <div class='eleven columns centered insideForm'>
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

    </section>
<?php
@include("footer.php");