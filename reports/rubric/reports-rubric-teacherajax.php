<?php
if($_SERVER['SERVER_NAME'] == "localhost"){     include($_SERVER["DOCUMENT_ROOT"]."/live/sessioncheck.php"); }else{     @include("sessioncheck.php"); }

$date = date("Y-m-d H:i:s");
$date1 = date("Y-m-d 00:00:00");
$oper = isset($method['oper']) ? $method['oper'] : '';

if($oper=="showstudent" and $oper != " " )
{
$expid = isset($method['expid']) ? $method['expid'] : '';
$classid = isset($method['clsid']) ? $method['clsid'] : '0';
$rubid = isset($method['rubid']) ? $method['rubid'] : '0';
$scheduleid = isset($method['schid']) ? $method['schid'] : '0';
$scheduletype = isset($method['stype']) ? $method['stype'] : '0';

$tempsched = $scheduleid.'_'.$scheduletype;
//echo $expid.' - '.$rubid.' - '.$classid.' - '.$scheduleid.' - '.$scheduletype;


?>

                <input type="hidden" id="rubschedid2" value="<?php echo $scheduleid; ?>">
                <input type="hidden" id="classid2" value="<?php echo $classid; ?>">
    <style>
        .studentsbtn{
            margin:20px 10px;
            width:140px;

            font-size:  12px; letter-spacing:-0.1px; word-spacing:-0.2px;  text-align:center;   word-wrap: break-word;     line-height: 16px; font-weight:bold;
            display:inline-block; padding:10px; border-radius:6px;
            background: #cbcbcb;
            background-image: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zd…AiIHk9IjAiIHdpZHRoPSIxIiBoZWlnaHQ9IjEiIGZpbGw9InVybCgjZzIyOSkiIC8+PC9zdmc+);
            background: -moz-linear-gradient(50% 0% -90deg, #ebf7f8 0%, #cdcece 93%, #cbcbcb 100%);
            background: -o-linear-gradient(-90deg, #ebf7f8 0%, #cdcece 93%, #cbcbcb 100%);
            background: -webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(0, #ebf7f8), color-stop(0.93, #cdcece), color-stop(1, #cbcbcb));
            background: -webkit-linear-gradient(-90deg, #ebf7f8 0%, #cdcece 93%, #cbcbcb 100%);
            background: linear-gradient(180deg, #ebf7f8 0%, #cdcece 93%, #cbcbcb 100%);
            border: 1px solid #999;
            -webkit-box-shadow: 0 2px 3px rgba(0,0,0,.3), inset 0 0 2px rgba(255,255,255,.65), inset 0 -1px 2px rgba(0,0,0,.3);
            box-shadow: 0 2px 3px rgba(0,0,0,.3), inset 0 0 2px rgba(255,255,255,.65), inset 0 -1px 2px rgba(0,0,0,.3);
            color: #24475f;
            cursor:pointer;
        }
        .studentsbtn:hover{
            -webkit-box-shadow: inset 0 1px 1px #fff, 0 1px 2px rgba(0,0,0,0.31);
            box-shadow: inset 0 1px 1px #fff, 0 1px 2px rgba(0,0,0,0.31);
            background: #ccc;
            background: -moz-linear-gradient(top, #fff 0%, #ddd 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#fff), color-stop(100%,#ddd));
            background: -webkit-linear-gradient(top, #fff 0%,#ddd 100%);
            background: -o-linear-gradient(top, #fff 0%,#ddd 100%);
            background: -ms-linear-gradient(top, #fff 0%,#ddd 100%);
            background: linear-gradient(top, #fff 0%,#ddd 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#dddddd',GradientType=0 );
            color: #143344;
        }

    </style>
<script type="text/javascript" language="javascript">
    $(function() {
        $('#testrailvisible0').slimscroll({
            width: '412px',
            height:'380px',
            size: '3px',
            railVisible: true,
            allowPageScroll: false,
            railColor: '#F4F4F4',
            opacity: 1,
            color: '#d9d9d9',
            wheelStep: 1,

        });
        $('#testrailvisible1').slimscroll({
            width: '412px',
            height:'380px',
            size: '3px',
            railVisible: true,
            allowPageScroll: false,
            railColor: '#F4F4F4',
            opacity: 1,
            color: '#d9d9d9',
            wheelStep: 1,
        });
        $("#list9").sortable({
            connectWith: ".droptrue1",
            dropOnEmpty: true,
            items: "div[class='draglinkleft']",
            receive: function(event, ui) {
                $("div[class=draglinkright]").each(function(){
                    if($(this).parent().attr('id')=='list9'){
                        fn_movealllistitems('list9','list10',$(this).children(":first").attr('id'));
                    }
                });
            }
        });

        $( "#list10" ).sortable({
            connectWith: ".droptrue1",
            dropOnEmpty: true,
            receive: function(event, ui) {
                $("div[class=draglinkleft]").each(function(){
                    if($(this).parent().attr('id')=='list10'){
                        fn_movealllistitems('list9','list10',$(this).children(":first").attr('id'));
                    }
                });
            }
        });
        $(".dragWell").parent().css("padding-bottom", "10px");

    });


    function fn_saverubric(id,expid)
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
            var schid=$("#rubschedid2").val();
            var classid=$("#classid2").val();

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
</script>

<div class="row rowspacer" id="studentlist">
    <div class='six columns'>
        <div class="dragndropcol">
            <?php


            //echo $expid.' - '.$classid.' - '.$scheduleid.' - '.$scheduletype;
            if($scheduletype=='15')
            {
                $qrystudent= $ObjDB->QueryObject("SELECT b.fld_student_id AS studentid,c.fld_fname AS firstname,c.fld_lname AS lastname ,c.fld_username as username, CONCAT(c.fld_fname,' ',c.fld_lname) AS sname FROM itc_class_indasexpedition_master AS a
                                          LEFT JOIN itc_class_exp_student_mapping AS b ON a.fld_id=b.fld_schedule_id
                                          LEFT JOIN itc_user_master AS c ON c.fld_id=b.fld_student_id
                                          WHERE a.fld_class_id='".$classid."' AND b.fld_schedule_id='".$scheduleid."' AND a.fld_exp_id='".$expid."'  AND a.fld_flag='1' AND b.fld_flag='1' 
                                              AND c.fld_profile_id = '10'  AND c.fld_activestatus = '1'  AND c.fld_delstatus = '0'  group by studentid ORDER BY sname ASC");//b.fld_student_id NOT IN(SELECT fld_student_id FROM itc_exp_rubric_rpt_stu_mapping where fld_rubric_rpt_id='".$rubricid."' AND fld_delstatus='0') AND
            }
            else if($scheduletype=='23' || $scheduletype=='18')
            {
                $qrystudent= $ObjDB->QueryObject("SELECT b.fld_student_id AS studentid,c.fld_fname AS firstname,c.fld_lname AS lastname ,c.fld_username as username, CONCAT(c.fld_fname,' ',c.fld_lname) AS sname FROM itc_class_indasmission_master AS a
                                                  LEFT JOIN itc_class_mission_student_mapping AS b ON a.fld_id=b.fld_schedule_id
                                                  LEFT JOIN itc_user_master AS c ON c.fld_id=b.fld_student_id
                                                  WHERE a.fld_class_id='".$classid."' AND b.fld_schedule_id='".$scheduleid."' AND a.fld_mis_id='".$expid."'  AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_flag='1' 
                                                      AND c.fld_profile_id = '10'  AND c.fld_activestatus = '1'  AND c.fld_delstatus = '0'  group by studentid ORDER BY sname ASC");
            }
            else if($scheduletype=='19')
            {
                $qrystudent= $ObjDB->QueryObject("SELECT b.fld_student_id AS studentid,c.fld_fname AS firstname,c.fld_lname AS lastname ,c.fld_username as username, 
                                             CONCAT(c.fld_fname,' ',c.fld_lname) AS sname FROM itc_class_rotation_mission_schedulegriddet AS a
                                                LEFT JOIN itc_class_rotation_mission_student_mappingtemp AS b ON a.fld_student_id=b.fld_student_id
                                                LEFT JOIN itc_user_master AS c ON c.fld_id=b.fld_student_id
                                                WHERE a.fld_class_id='".$classid."' AND b.fld_schedule_id='".$scheduleid."' AND a.fld_mission_id='".$expid."' AND a.fld_flag='1' AND b.fld_flag='1' 
                                                AND c.fld_profile_id = '10'  AND c.fld_activestatus = '1'  AND c.fld_delstatus = '0'  group by studentid ORDER BY sname ASC");//b.fld_student_id NOT IN(SELECT fld_student_id FROM itc_exp_rubric_rpt_stu_mapping where fld_rubric_rpt_id='".$rubricid."' AND fld_delstatus='0') AND
            }
            else if($scheduletype=='20')
            {
                $qrystudent= $ObjDB->QueryObject("SELECT b.fld_student_id AS studentid,c.fld_fname AS firstname,c.fld_lname AS lastname ,c.fld_username as username, 
                                             CONCAT(c.fld_fname,' ',c.fld_lname) AS sname FROM itc_class_rotation_modexpschedulegriddet AS a
                                                LEFT JOIN itc_class_rotation_modexpschedule_student_mappingtemp AS b ON a.fld_student_id=b.fld_student_id
                                                LEFT JOIN itc_user_master AS c ON c.fld_id=b.fld_student_id
                                                WHERE a.fld_class_id='".$classid."' AND b.fld_schedule_id='".$scheduleid."' AND a.fld_module_id='".$expid."' AND a.fld_type='2'  AND a.fld_flag='1' AND b.fld_flag='1' 
                                                AND c.fld_profile_id = '10'  AND c.fld_activestatus = '1'  AND c.fld_delstatus = '0'  group by studentid ORDER BY sname ASC");//b.fld_student_id NOT IN(SELECT fld_student_id FROM itc_exp_rubric_rpt_stu_mapping where fld_rubric_rpt_id='".$rubricid."' AND fld_delstatus='0') AND
            }
            else
            {
                $qrystudent= $ObjDB->QueryObject("SELECT b.fld_student_id AS studentid,c.fld_fname AS firstname,c.fld_lname AS lastname ,c.fld_username as username, 
                                             CONCAT(c.fld_fname,' ',c.fld_lname) AS sname FROM itc_class_rotation_expschedulegriddet AS a
                                                LEFT JOIN itc_class_rotation_expschedule_student_mappingtemp AS b ON a.fld_student_id=b.fld_student_id
                                                LEFT JOIN itc_user_master AS c ON c.fld_id=b.fld_student_id
                                                WHERE a.fld_class_id='".$classid."' AND b.fld_schedule_id='".$scheduleid."' AND a.fld_expedition_id='".$expid."'  AND a.fld_flag='1' AND b.fld_flag='1' 
                                                AND c.fld_profile_id = '10'  AND c.fld_activestatus = '1'  AND c.fld_delstatus = '0'  group by studentid ORDER BY sname ASC");//b.fld_student_id NOT IN(SELECT fld_student_id FROM itc_exp_rubric_rpt_stu_mapping where fld_rubric_rpt_id='".$rubricid."' AND fld_delstatus='0') AND
            }


            ?>
            <div class="dragtitle">Students Available (<span id="nostudentleftdiv"> <?php echo $qrystudent->num_rows;?></span>)</div>
            <div class="draglinkleftSearch" id="s_list9" >
                <dl class='field row'>
                    <dt class='text'>
                        <input placeholder='Search' type='text' id="list_9_search" name="list_9_search" onKeyUp="search_list(this,'#list9');" />
                    </dt>
                </dl>
            </div><br>
            <div class="dragWell" id="testrailvisible0">
                <div id="list9" class="dragleftinner droptrue1">
                    <?php
                    if($qrystudent->num_rows > 0){
                        while($rowsstudent = $qrystudent->fetch_assoc()){
                            extract($rowsstudent);
                            ?>
                            <div class="draglinkleft" id="list9_<?php echo $studentid; ?>" >
                                <div class="dragItemLable tooltip" id="<?php echo $studentid; ?>" title="<?php echo $username;?>"><?php echo $firstname." ".$lastname; ?></div>
                                <div class="clickable" id="clck_<?php echo $studentid;?>" onclick="fn_movealllistitems('list9','list10',<?php echo $studentid;?>,<?php echo $expid; ?>,'<?php echo $scheduletype; ?>','<?php echo $tempsched; ?>','<?php echo $rubid; ?>');"></div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="dragAllLink studentsbtn"  style="float:left;" onclick="fn_movealllistitems('list9','list10',0,<?php echo $expid; ?>,'<?php echo $scheduletype; ?>','<?php echo $tempsched; ?>','<?php echo $rubid; ?>');">Add All Students <b style="margin-left:4px; font-size: 110%;">&#9654</b></div>
        </div>
    </div>
    <div class='six columns'>
        <div class="dragndropcol">
            <div class="dragtitle">Students In Your Rubric</div>
            <div class="draglinkleftSearch" id="s_list10" >
                <dl class='field row'>
                    <dt class='text'>
                        <input placeholder='Search' type='text' id="list_10_search" name="list_10_search" onKeyUp="search_list(this,'#list10');" />
                    </dt>
                </dl>
            </div><br>
            <div class="dragWell" id="testrailvisible1">
                <div id="list10" class="dragleftinner droptrue1">
                    <?php

                    if($qrystudent->num_rows > 0){
                        while($rowsstudent = $qrystudent->fetch_assoc()){
                            extract($rowsstudent);
                            ?>
                            <div class="draglinkright" id="list10_<?php echo $studentid; ?>">
                                <div class="dragItemLable tooltip" id="<?php echo $studentid; ?>" title="<?php echo $username;?>"><?php echo $firstname." ".$lastname; ?></div>
                                <div class="clickable" id="clck_<?php echo $studentid; ?>" onclick="fn_movealllistitems('list9','list10',<?php echo $studentid; ?>,<?php echo $expid; ?>,<?php echo $scheduletype; ?>,'<?php echo $tempsched; ?>','<?php echo $rubid; ?>');"></div>
                            </div>
                        <?php }
                    }
                    ?>
                </div>
            </div>
            <div class="dragAllLink studentsbtn"  style="float:right;" onclick="fn_movealllistitems('list10','list9',0,<?php echo $expid; ?>,'<?php echo $scheduletype; ?>','<?php echo $tempsched; ?>','<?php echo $rubid; ?>');"><b style="margin-right:4px; font-size: 110%;">&#9664</b> Remove All Students</div>
        </div>
    </div>
</div>

<?php
}
?>