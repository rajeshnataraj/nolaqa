    <?php
 if($_SERVER['SERVER_NAME'] == "localhost"){     include($_SERVER["DOCUMENT_ROOT"]."/live/sessioncheck.php"); }else{     @include("sessioncheck.php"); }

$date = date("Y-m-d H:i:s");
$date1 = date("Y-m-d 00:00:00");
$oper = isset($method['oper']) ? $method['oper'] : '';

if($oper=="showschedule" and $oper != " " )
{
        $clsid = isset($method['clsid']) ? $method['clsid'] : '';
        $type = isset($method['type']) ? $method['type'] : '';
        ?> 
       Schedule
            <dl class='field row'>
                <div class="selectbox">
                    <input type="hidden" name="schid" id="schid" value="" onchange="fn_showexp(this.value,<?php echo $type; ?>);" />
                    <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                       <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Schedule</span> 
                       <b class="caret1"></b>
                   </a>
                   <div class="selectbox-options">
                       <input type="text" class="selectbox-filter" placeholder="Search Schedule">
                       <ul role="options" style="width:100%">
                           <?php

                           if($type=='4'){
                               $qry = $ObjDB->QueryObject("SELECT w.* FROM (
                                                                (SELECT a.fld_id AS schid, a.fld_schedule_name AS schedulename,18 AS exptype,
                                                                a.fld_id AS assingnmentid FROM itc_class_indasmission_master AS a
                                                                LEFT JOIN itc_class_master AS b ON b.fld_id=a.fld_class_id
                                                                WHERE a.fld_class_id='".$clsid."' AND  a.fld_delstatus='0' AND a.fld_flag='1' ) 
                                                        UNION ALL
                                                                (SELECT a.fld_id AS sch, a.fld_schedule_name AS schedulename,19 AS exptype,
                                                                a.fld_id AS assingnmentid FROM itc_class_rotation_mission_mastertemp AS a
                                                                LEFT JOIN itc_class_master AS b ON b.fld_id=a.fld_class_id
                                                                WHERE a.fld_class_id='".$clsid."' AND  a.fld_delstatus='0' AND a.fld_flag='1' ))AS w group by w.assingnmentid ORDER BY w.schedulename
                                                ");
                           }
                           else {
                               $qry = $ObjDB->QueryObject("SELECT w.* FROM (
                                                    (SELECT a.fld_id AS schid, a.fld_schedule_name AS schedulename,15 AS exptype,a.fld_id AS assingnmentid
                                                    FROM itc_class_indasexpedition_master AS a
                                                LEFT JOIN itc_class_master AS b ON b.fld_id=a.fld_class_id
                                                WHERE a.fld_class_id='" . $clsid . "' AND  a.fld_delstatus='0' AND a.fld_flag='1' AND a.fld_createdby='" . $uid . "'
                                                AND b.fld_delstatus = '0'  AND b.fld_flag = '1') 
                                                UNION ALL
                                                    (SELECT a.fld_id AS sch, a.fld_schedule_name AS schedulename,17 AS exptype,a.fld_id AS assingnmentid 
                                                    FROM itc_class_rotation_expschedule_mastertemp AS a
                                                LEFT JOIN itc_class_master AS b ON b.fld_id=a.fld_class_id
                                                WHERE a.fld_class_id='" . $clsid . "' AND  a.fld_delstatus='0' AND a.fld_flag='1' AND a.fld_createdby='" . $uid . "'
                                                    AND b.fld_delstatus = '0'  AND b.fld_flag = '1')
                                                UNION ALL
                                                    (SELECT a.fld_id AS sch,a.fld_schedule_name AS schedulename, 20 AS exptype,a.fld_id AS assingnmentid
                                                    FROM itc_class_rotation_modexpschedule_mastertemp AS a
                                                    WHERE a.fld_class_id='" . $clsid . "' AND a.fld_delstatus='0' AND a.fld_flag='1' AND a.fld_createdby='" . $uid . "')
                                                    )AS w GROUP BY w.assingnmentid ORDER BY w.schedulename
                                                ");
                           }
                            if($qry->num_rows>0)
                            {
                                while($row = $qry->fetch_assoc())
                                {
                                    extract($row);
                                    ?>
                                        <li><a tabindex="-1" href="#" data-option="<?php echo $schid."~".$exptype;?>"><?php echo $schedulename; ?></a></li>
                                    <?php
                                }
                            }
                            ?>      
                       </ul>
                   </div>
                </div>
            </dl>
        

    <?php 
} 



if($oper=="showexpedition" and $oper != " " )
{
    $type = isset($method['type']) ? $method['type'] : '';
        if($type==1 || $type==2)
        {
                $schid = isset($method['schid']) ? $method['schid'] : '';
                $classid = isset($method['clsid']) ? $method['clsid'] : '';

                $schid =explode("~",$schid );
                $scheduleid=$schid[0];
                $scheduletype=$schid[1];
        }
        else
        {
            if($type=='4'){
                $scheduletype = "18";
                $schid = isset($method['schid']) ? $method['schid'] : '';
                $classid = isset($method['clsid']) ? $method['clsid'] : '';

                $schid =explode("~",$schid );
                //$classid=$schid[0];
                $scheduleid=$schid[0];
                $scheduletype=$schid[1];
            }else{

                $schid = isset($method['schid']) ? $method['schid'] : '';
                $classid = isset($method['clsid']) ? $method['clsid'] : '';

                $schid =explode("~",$schid );
                $classid=$schid[0];
                $scheduleid=$schid[1];
                $scheduletype=$schid[2];
            }
        }
       if($scheduletype == "18" || $scheduletype == "23"|| $type == "4"){
           $tempname = "Mission";
       }else{
           $tempname = "Expedition";
       }

    //echo $classid.' '.$scheduletype;
        echo $tempname
        ?>
            <dl class='field row'>
                <div class="selectbox">
                    <input type="hidden" name="expid" id="expid" value="" onchange="fn_showrubric(this.value,<?php echo $type; ?>);" />
                    <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                       <span class="selectbox-option input-medium" data-option="" style="width:97%">Select <?php echo $tempname; ?></span>
                       <b class="caret1"></b>
                   </a>
                   <div class="selectbox-options">
                       <input type="text" class="selectbox-filter" placeholder="Search <?php echo $tempname; ?>">
                       <ul role="options" style="width:100%">
                           <?php
        
        if($scheduletype=='15')
        {
                $qry = $ObjDB->QueryObject("SELECT a.fld_exp_id AS expid,b.fld_exp_name AS expname FROM itc_class_indasexpedition_master AS a
                                                    LEFT JOIN itc_exp_master AS b ON b.fld_id=a.fld_exp_id
                                                    WHERE a.fld_class_id='".$classid."' AND a.fld_id='".$scheduleid."' AND a.fld_delstatus='0' AND a.fld_flag='1' AND b.fld_delstatus='0' 
                                                    AND b.fld_flag='1' AND a.fld_createdby='".$uid."' group by expid ORDER BY expname");
        }
        else if($scheduletype=='23' || $scheduletype=='18')
        {
            $qry = $ObjDB->QueryObject("SELECT a.fld_mis_id AS expid, b.fld_mis_name AS expname FROM itc_class_indasmission_master AS a
                                            LEFT JOIN itc_mission_master AS b ON b.fld_id=a.fld_mis_id
                                            WHERE a.fld_class_id='".$classid."' AND a.fld_id='".$scheduleid."' AND a.fld_delstatus='0' AND a.fld_flag='1' AND b.fld_delstatus='0' 
                                            AND b.fld_flag='1' AND a.fld_createdby='".$uid."' group by expid ORDER BY expname");

        }
        else if($scheduletype=='20')
        {
            $qry = $ObjDB->QueryObject("SELECT a.fld_module_id AS expid,b.fld_exp_name AS expname FROM itc_class_rotation_modexpschedulegriddet AS a
                                            LEFT JOIN itc_exp_master AS b ON b.fld_id=a.fld_module_id
                                            WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_type='2' AND a.fld_flag='1' AND b.fld_delstatus='0' 
                                            AND b.fld_flag='1' AND a.fld_createdby='".$uid."' group by expid ORDER BY expname");
            
        }
        else if($scheduletype=='19')
        {
                $qry = $ObjDB->QueryObject("SELECT a.fld_mission_id AS expid, b.fld_mis_name AS expname FROM itc_class_rotation_mission_schedulegriddet AS a
                                                    LEFT JOIN itc_mission_master AS b ON b.fld_id=a.fld_mission_id
                                                    LEFT JOIN itc_class_rotation_mission_mastertemp AS c ON a.fld_schedule_id=c.fld_id 
                                                    WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_flag='1' AND b.fld_delstatus='0' AND c.fld_delstatus='0' 
                                                    AND b.fld_flag='1' AND a.fld_createdby='".$uid."' group by expid ORDER BY expname");

        }
        else
        {
                $qry = $ObjDB->QueryObject("SELECT a.fld_expedition_id AS expid, b.fld_exp_name AS expname FROM itc_class_rotation_expschedulegriddet AS a
                                                    LEFT JOIN itc_exp_master AS b ON b.fld_id=a.fld_expedition_id
                                                    LEFT JOIN itc_class_rotation_expschedule_mastertemp AS c ON a.fld_schedule_id=c.fld_id 
                                                    WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_flag='1' AND b.fld_delstatus='0' AND c.fld_delstatus='0' 
                                                    AND b.fld_flag='1' AND a.fld_createdby='".$uid."' group by expid ORDER BY expname");
        }
                          
                            if($qry->num_rows>0){
                                while($row = $qry->fetch_assoc())
                                {
                                    extract($row);
                                    ?>
                                        <li><a tabindex="-1" href="#" data-option="<?php echo $expid;?>"><?php echo $expname; ?></a></li>
                                    <?php
                                }
                            }
                            ?>      
                       </ul>
                   </div>
                </div>
            </dl>
    <?php 
} 


if($oper=="showrubric" and $oper != " " )
{
       
        $type = isset($method['type']) ? $method['type'] : '';

    if($type==1 || $type==2)
    {
        $expid = isset($method['expid']) ? $method['expid'] : '';
        $schid = isset($method['schid']) ? $method['schid'] : '';
        $classid = isset($method['clsid']) ? $method['clsid'] : '';

        $schid =explode("~",$schid );
        $scheduleid=$schid[0];
        $scheduletype=$schid[1];
    }
    else
    {
        if($type=='4'){
            $scheduletype = "18";
            $expid = isset($method['expid']) ? $method['expid'] : '';
            $schid = isset($method['schid']) ? $method['schid'] : '';
            $classid = isset($method['clsid']) ? $method['clsid'] : '';

            $schid =explode("~",$schid );
            //$classid=$schid[0];
            $scheduleid=$schid[0];
            $scheduletype=$schid[1];
        }else{

            $expid = isset($method['expid']) ? $method['expid'] : '';
            $schid = isset($method['schid']) ? $method['schid'] : '';
            $classid = isset($method['clsid']) ? $method['clsid'] : '';

            $schid =explode("~",$classid );
            $classid=$schid[0];
            $scheduleid=$schid[1];
            $scheduletype=$schid[2];
        }
    }
    if($scheduletype == "18" || $scheduletype == "23"|| $type == "4"){
        $tempname = "Mission";
    }else{
        $tempname = "Expedition";
    }

        ?> 
        Rubric
            <dl class='field row'>
                <div class="selectbox">
                    <input type="hidden" name="rubid" id="rubid" value="" onchange="fn_showstudent(this.value,<?php echo $type; ?>);" />
                    <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                       <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Rubric</span> 
                       <b class="caret1"></b>
                   </a>
                   <div class="selectbox-options">
                       <input type="text" class="selectbox-filter" placeholder="Search Rubric">
                       <ul role="options" style="width:100%">
                           <?php
        
        if($scheduletype=='15')
        {
                  $qry = $ObjDB->QueryObject("SELECT a.fld_rubric_id AS rubricid, a.fld_rubric_name AS rubname 
                                                FROM itc_class_expmis_rubricmaster AS a
                                                         LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_schedule_id=b.fld_id 
                                                                WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_expmisid='".$expid."' AND b.fld_delstatus='0'
                                                                AND a.fld_schedule_type='".$scheduletype."' AND a.fld_delstatus='0' 
                                                                GROUP BY rubricid");
        }
        else if($scheduletype=='23' || $scheduletype=='18')
        {
            $qry = $ObjDB->QueryObject("SELECT a.fld_rubric_id AS rubricid, a.fld_rubric_name AS rubname 
                                                FROM itc_class_expmis_rubricmaster AS a
                                                         LEFT JOIN itc_class_indasmission_master AS b ON a.fld_schedule_id=b.fld_id 
                                                                WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_expmisid='".$expid."' AND b.fld_delstatus='0'
                                                                AND a.fld_schedule_type='".$scheduletype."' AND a.fld_delstatus='0' 
                                                                GROUP BY rubricid");

        }
        else if($scheduletype=='20')
        {
            $qry = $ObjDB->QueryObject("SELECT a.fld_rubric_id AS rubricid, a.fld_rubric_name AS rubname 
                                            FROM itc_class_expmis_rubricmaster AS a
                                            WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_expmisid='".$expid."'
                                            AND a.fld_schedule_type='".$scheduletype."' AND a.fld_delstatus='0' 
                                            GROUP BY rubricid");
        }
        else if($scheduletype=='19')
        {
            $qry = $ObjDB->QueryObject("SELECT a.fld_rubric_id AS rubricid, a.fld_rubric_name AS rubname 
                                                FROM itc_class_expmis_rubricmaster AS a
                                                         LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
                                                                WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_expmisid='".$expid."' AND b.fld_delstatus='0'
                                                                AND a.fld_schedule_type='".$scheduletype."' AND a.fld_delstatus='0' 
                                                                GROUP BY rubricid");
        }
        else
        {
                $qry = $ObjDB->QueryObject("SELECT a.fld_rubric_id AS rubricid, a.fld_rubric_name AS rubname 
                                                FROM itc_class_expmis_rubricmaster AS a
                                                         LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
                                                                WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_expmisid='".$expid."' AND b.fld_delstatus='0'
                                                                AND a.fld_schedule_type='".$scheduletype."' AND a.fld_delstatus='0' 
                                                                GROUP BY rubricid");
        }
        
                           
                            if($qry->num_rows>0){
                                while($row = $qry->fetch_assoc())
                                {
                                    extract($row);
//                                    echo '<h2>'.$rubricid.' '.$rubname.'</h2><br>';
                                            ?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $rubricid;?>"><?php echo $rubname; ?></a></li>
                                            <?php
                                }
                            }
                                else
                                { ?>
                                        <li><?php echo "No Rubric"; ?></li>
                                <?php
                                }
                            ?>      
                       </ul>
                   </div>
                </div>
            </dl>
        

    <?php 
} 




if($oper=="showstudent" and $oper != " " )
{
        $type = isset($method['type']) ? $method['type'] : '';
        $expid = isset($method['expid']) ? $method['expid'] : '';
        $rubid = isset($method['rubid']) ? $method['rubid'] : '0';
        
        if($type==1 || $type==2)
        {
                $classid = isset($method['clsid']) ? $method['clsid'] : '';
                $schid = isset($method['schid']) ? $method['schid'] : '';

                $schid =explode("~",$schid );
                $scheduleid=$schid[0];
                $scheduletype=$schid[1];
        }
        else
        {
            $classid = isset($method['clsid']) ? $method['clsid'] : '';
                $clasid = isset($method['clsid']) ? $method['clsid'] : '';
                $schid =explode("~",$clasid );
                $classid=$schid[0];
                $scheduleid=$schid[1];
                $scheduletype=$schid[2];      
        }
        $tempsched = $scheduleid.'_'.$scheduletype;
       
       
       
        ?> 
    <script type="text/javascript" language="javascript">
        $(function() {
                $('#testrailvisible0').slimscroll({
                        width: '410px',
                        height:'366px',
                        size: '3px',
                        railVisible: true,
                        allowPageScroll: false,
                        railColor: '#F4F4F4',
                        opacity: 1,
                        color: '#d9d9d9',
                        wheelStep: 1,

                });
                $('#testrailvisible1').slimscroll({
                        width: '410px',
                        height:'366px',
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
              
        });																	
    </script>
    <div class="row rowspacer" id="studentlist">
      <div class='six columns'>
          <div class="dragndropcol">
              <input hidden id="studentsclassid" val ="<?php echo $classid; ?>" />"
          <?php


//          echo $expid.' - '.$classid.' - '.$scheduleid.' - '.$scheduletype;
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
        else if($scheduletype=='20')
        {
            $qrystudent= $ObjDB->QueryObject("SELECT b.fld_student_id AS studentid,c.fld_fname AS firstname,c.fld_lname AS lastname ,c.fld_username as username, 
                                             CONCAT(c.fld_fname,' ',c.fld_lname) AS sname FROM itc_class_rotation_modexpschedulegriddet AS a
                                                LEFT JOIN itc_class_rotation_modexpschedule_student_mappingtemp AS b ON a.fld_student_id=b.fld_student_id
                                                LEFT JOIN itc_user_master AS c ON c.fld_id=b.fld_student_id
                                                WHERE a.fld_class_id='".$classid."' AND b.fld_schedule_id='".$scheduleid."' AND a.fld_module_id='".$expid."' AND a.fld_type='2'  AND a.fld_flag='1' AND b.fld_flag='1' 
                                                AND c.fld_profile_id = '10'  AND c.fld_activestatus = '1'  AND c.fld_delstatus = '0'  group by studentid ORDER BY sname ASC");//b.fld_student_id NOT IN(SELECT fld_student_id FROM itc_exp_rubric_rpt_stu_mapping where fld_rubric_rpt_id='".$rubricid."' AND fld_delstatus='0') AND
        }
        else if($scheduletype=='19')
        {
            $qrystudent= $ObjDB->QueryObject("SELECT b.fld_student_id AS studentid,c.fld_fname AS firstname,c.fld_lname AS lastname ,c.fld_username as username, 
                                             CONCAT(c.fld_fname,' ',c.fld_lname) AS sname FROM itc_class_rotation_mission_schedulegriddet AS a
                                                LEFT JOIN itc_class_rotation_mission_student_mappingtemp AS b ON a.fld_student_id=b.fld_student_id
                                                LEFT JOIN itc_user_master AS c ON c.fld_id=b.fld_student_id
                                                WHERE a.fld_class_id='".$classid."' AND b.fld_schedule_id='".$scheduleid."' AND a.fld_mission_id='".$expid."'  AND a.fld_flag='1' AND b.fld_flag='1' 
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
              <div class="dragtitle">Students available</div>
                  <div class="draglinkleftSearch" id="s_list9" >
                     <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_9_search" name="list_9_search" onKeyUp="search_list(this,'#list9');" />
                          </dt>
                      </dl>
                  </div>
                  <div class="dragWell" id="testrailvisible0" >
                      <div id="list9" class="dragleftinner droptrue1">
                       <?php 		
                         if($qrystudent->num_rows > 0){													
                              while($rowsstudent = $qrystudent->fetch_assoc()){
                                  extract($rowsstudent);
                                  ?>
                              <div class="draglinkleft" id="list9_<?php echo $studentid; ?>" >
                                  <div class="dragItemLable tooltip" id="<?php echo $studentid; ?>" title="<?php echo $username;?>"><?php echo $firstname." ".$lastname; ?></div>
                                  <div class="clickable" id="clck_<?php echo $studentid;?>" onclick="fn_movealllistitems('list9','list10',<?php echo $studentid;?>,<?php echo $expid; ?>,<?php echo $scheduletype; ?>,'<?php echo $tempsched; ?>');"></div>
                              </div>
                      <?php 
                              }
                          }
                      ?>
                      </div>
                  </div>
          </div>
      </div>
      <div class='six columns'>
              <div class="dragndropcol">
                  <div class="dragtitle">Students in your Rubric</div>
                  <div class="draglinkleftSearch" id="s_list10" >
                     <dl class='field row'>
                          <dt class='text'>
                              <input placeholder='Search' type='text' id="list_10_search" name="list_10_search" onKeyUp="search_list(this,'#list10');" />
                          </dt>
                      </dl>
                  </div>
                   <div class="dragWell" id="testrailvisible1">
                      <div id="list10" class="dragleftinner droptrue1">
                          <?php 
        
                             if($qrystudent->num_rows > 0){													
                                 while($rowsstudent = $qrystudent->fetch_assoc()){
                                     extract($rowsstudent);
                                         ?>
                                     <div class="draglinkright" id="list10_<?php echo $studentid; ?>">
                                         <div class="dragItemLable tooltip" id="<?php echo $studentid; ?>" title="<?php echo $username;?>"><?php echo $firstname." ".$lastname; ?></div>
                                         <div class="clickable" id="clck_<?php echo $studentid; ?>" onclick="fn_movealllistitems('list9','list10',<?php echo $studentid; ?>,<?php echo $expid; ?>,<?php echo $scheduletype; ?>,'<?php echo $tempsched; ?>');"></div>
                                     </div>
                              <?php }
                             }
                          ?>
                      </div>
                  </div>

          </div>
      </div>
    </div> 
     <?php
}


if($oper=="showrubricstmt" and $oper != " " )
{
   
$expeditionid = isset($method['expid']) ? $method['expid'] : '0';
$rubid = isset($method['rubid']) ? $method['rubid'] : '0';
$type = isset($method['type']) ? $method['type'] : '';


    if($type==1 || $type==2)
    {
        $clasid = isset($method['classid']) ? $method['classid'] : '0';
        $schid = isset($method['schid']) ? $method['schid'] : '';

        $schid =explode("~",$schid );
        $scheduleid=$schid[0];
        $scheduletype=$schid[1];

    }
    else {
        $classid = isset($method['classid']) ? $method['classid'] : '';
        if (strpos($classid, "~") > 0) {
            $schid = explode("~", $classid);
            $clasid = $schid[0];
            $scheduleid = $schid[1];
            $scheduletype = $schid[2];
        } else {
            $clasid = isset($method['classid']) ? $method['classid'] : '0';
            $schid = isset($method['schid']) ? $method['schid'] : '';
            $schid = explode("_", $schid);
            $scheduleid=$schid[0];
            $scheduletype=$schid[1];
        }
    }
//    print_r($schid);
    $studentid = isset($method['list10']) ? ($method['list10']) : '0';

    $createbtn = "Save";
$resetbtn = "Reset"; 	// created line chandra
$finishbtn = "Finish"; // created line chandra
    //echo "<bR><hr>".$classid.' '.$clasid.'<br><hr><br>';
	$schedid=$ObjDB->SelectSingleValue("SELECT fld_schedule_id FROM itc_class_expmis_rubricmaster WHERE fld_expmisid='".$expeditionid."' AND fld_rubric_id='".$rubid."'  AND fld_class_id='".$classid."'  AND fld_created_by='".$$uid."'");
    if($scheduletype=='23' || $scheduletype=='18' || $scheduletype=='19') {
		$totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_master WHERE fld_mis_id='".$expeditionid."' AND fld_rubric_id='".$rubid."' AND fld_delstatus='0'");
        
        //$classid=$ObjDB->SelectSingleValue("SELECT fld_class_id FROM itc_missch_rubric_rpt WHERE fld_mis_id='".$expeditionid."' AND fld_rubric_nameid='".$rubid."'");

    }else{
        
		$totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master WHERE fld_exp_id='".$expeditionid."' AND fld_rubric_id='".$rubid."' AND fld_delstatus='0'");
        
        //$classid=$ObjDB->SelectSingleValue("SELECT fld_class_id FROM itc_expsch_rubric_rpt WHERE fld_exp_id='".$expeditionid."' AND fld_rubric_nameid='".$rubid."'");

    }

   // echo $expeditionid.' - '.$rubid.' - '.$classid.' - '.$schedid.' - '.$scheduletype;
    //$schedid = '411';
//
//        ->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master WHERE fld_exp_id='".$expeditionid."' AND fld_rubric_id='".$rubid."' AND fld_delstatus='0'");
                              
?>

    <style>
        table td.grcategory, table td.desctd, table th.grcategory, table th.thdesctd, table td.scoretd, table th.scoretd{
            overflow-x: hidden;
            word-break: normal;
            word-wrap: break-word;
            letter-spacing: -0.1px;
            word-spacing: -0.1px;
            padding:6px 3px !important;
            text-align: center;
            vertical-align: middle !important;
        }
        table td.grcategory, table td.desctd, table td.scoretd{
            font-size: 13px;
            font-size: 12.5px;
            line-height: 15px;
        }
        table th.grcategory, table th.thdesctd{
            font-size:  14px;
            word-spacing:-0.2px;
            line-height: 20px;
            font-weight:bold;
            text-transform: capitalize;
        }
        table td.desctd{
            width:11%;
            width:10.9%;
            max-width:75px;
        }
        table td.scoretd{
            width:7%;
            width:6.9%;
            max-width:65px;
            text-align: center;
            font-weight:bold;
            font-size: 14px;
            margin-left:-2px;
        }
        table td.grcategory{
            width:15%;
            width:14.9%;
            max-width:75px;
            font-weight:bold;
        }
        .standardsbtn{
            font-size:  12px; letter-spacing:-0.1px; word-spacing:-0.2px;  text-align:center;   word-wrap: break-word;     line-height: 16px; font-weight:bold;
            display:inline-block; padding:6px; border-radius:6px;
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
        }
        .standardsbtn:hover{
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
        .standardslist{
            font-size:13px;
            color:#fff;
            letter-spacing:+0.2px;
            word-spacing:+0.2px;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-weight:bold;
            line-height:16px;
            text-align:left;
            display:block;
            padding:12px 24px;
        }

    </style>
          <div class='row rowspacer' id="wholeclassbuttons">
                <div class='twelve columns'>
                        <div class='six columns'>
                        </div>
                        <div class='six columns'>
                                <div class="row" style="margin-left:-64px;">
                                        <input type="hidden" name="totalscore" id="totalscore" value="0">
                                     <table>
                                         <tr>
                                           <td style="min-width:80px;"> Final Score: &nbsp;</td> <td id='studentscore' class='studentscore'> </td>
                                             <td style=" min-width:80px;"> &nbsp;<?php echo " / ".$totscore; ?></td>
                                             <td style="padding: 0 3px; "> <input class="darkButton" type="button" id="btnstep2" style="float: none; height: 34px; width: 76px;" value="View" onClick="fn_printdigrubric('<?php echo $rubid;?>','<?php echo $expeditionid;?>','<?php echo $scheduletype;?>');" />
                                             <td style="padding: 0 3px; "> <input class="darkButton" type="button" id="btnstep2" style="float: none; height: 34px; width: 76px;" value="<?php echo $resetbtn;?>" onClick="fn_resetrubric('<?php echo $rubid;?>','<?php echo $expeditionid;?>','<?php echo $scheduletype;?>');" />
                                             <td style="padding: 0 3px; "> <input class="darkButton" type="button" id="btnstep2" style="float: none; height: 34px; width: 76px;" value="<?php echo $createbtn;?>" onClick="fn_saverubric('<?php echo $rubid;?>','<?php echo $expeditionid;?>','<?php echo $scheduletype;?>');" />
                                             </td>
                                         </tr>
                                     </table>
                            </div> 
                     </div> 
                </div>
         </div>
            <div class='formBase rowspacer'>
            <div class='rowspacer formBase'>
                <input type="hidden" id="rubschedid" value="<?php echo $schedid; ?>">
                <input type="hidden" id="classid" value="<?php echo $classid; ?>">
                    <div id="expsetting" class='row rowspacer'>  
                        <div class='span10 offset1'>
                            <table class='table table-hover table-striped table-bordered setbordertopradius' id="mytable" >
                                <thead >
                                    <tr style="cursor:default;">
                                        <th class="grcategory" width="15%">Category</th>
                                        <th width="11%" class='centerText thdesctd'>4</th>
                                        <th  width="11%" class='centerText thdesctd'>3</th>
                                        <th  width="11%" class='centerText thdesctd'>2</th>
                                        <th  width="11%" class='centerText thdesctd'>1</th>
                                        <th  width="11%" class='centerText thdesctd'>0</th>
                                        <th width="7%" class='centerText scoretd'>Weight</th>
                                        <th width="7%" class='centerText scoretd'>Score</th>
                                        <!-- Code by barney related to #23153-->
                                        <th width="15%" class='centerText'>Comment</th>
                                    </tr>
                                </thead>
                            </table>
                            <div style="min-height:500px; max-height:90vh; overflow-y: auto;" class="rubrictable">
                                  <table class='table table-hover table-striped table-bordered setbordertopradius ' id="mytable" >
                                <?php

                                if($scheduletype=='23' || $scheduletype=='18' || $scheduletype=='19') {
                                    $qrydest=$ObjDB->QueryObject("SELECT a.fld_id as destid,a.fld_dest_name as destname 
                                                                FROM itc_mis_rubric_dest_master as a WHERE a.fld_mis_id='".$expeditionid."' 
                                                                AND a.fld_rubric_name_id='".$rubid."' AND a.fld_delstatus='0'");

                                }else{
                                    $qrydest=$ObjDB->QueryObject("SELECT a.fld_id as destid,a.fld_dest_name as destname 
                                                                FROM itc_exp_rubric_dest_master as a WHERE a.fld_exp_id='".$expeditionid."' 
                                                                AND a.fld_rubric_name_id='".$rubid."' AND a.fld_delstatus='0'");

                                }

                                
                                    if($qrydest->num_rows > 0){
                                         while($row=$qrydest->fetch_assoc()){
                                             extract($row);
                                             $dname[]=$destname;
                                             $did[]=$destid;

                                         }
                                    }
                                    for($i=0;$i<sizeof($did);$i++) 
                                    {
                                        
                                      ?>
                                <tbody> 
                                <style> 
                                    .bcolor{background: #F1F1F3;} m{ text-decoration: overline;}
                                    .table tr td:first-child {   padding-left: 20px;    }
                                          .td_select{    background-color:#99ccff !important; font-weight: bold;  /*border:solid #0066ff; */} 
                                </style>
                                <script>
                                    console.log("Schedule Type: <?=$scheduletype;?>");
                                </script>

                                          <?php

                                          if($scheduletype=='23' || $scheduletype=='18' || $scheduletype=='19') {
                                              $qryviewexp_rubric=$ObjDB->QueryObject("SELECT c.fld_id as rubricid, a.fld_id as destid,a.fld_dest_name as destname, c.fld_category as category,c.fld_four as four,c.fld_three as three,
                                                                                        c.fld_two as two,c.fld_one as one, c.fld_zer as zer ,c.fld_weight as weight,c.fld_score as score 
                                                                                        FROM itc_mis_rubric_dest_master as a
                                                                                            LEFT JOIN itc_mis_rubric_master AS c ON c.fld_destination_id=a.fld_id 
                                                                                            WHERE c.fld_rubric_id='".$rubid."' AND c.fld_mis_id='".$expeditionid."' 
                                                                                            AND c.fld_destination_id='".$did[$i]."'  AND a.fld_delstatus='0' 
                                                                                            AND c.fld_delstatus='0' ");//AND c.fld_created_by='".$uid1."'

                                          }else{
                                              $qryviewexp_rubric=$ObjDB->QueryObject("SELECT c.fld_id as rubricid, a.fld_id as destid,a.fld_dest_name as destname, c.fld_category as category,c.fld_four as four,c.fld_three as three,
                                                                                        c.fld_two as two,c.fld_one as one, c.fld_zer as zer ,c.fld_weight as weight,c.fld_score as score  
                                                                                        FROM itc_exp_rubric_dest_master as a
                                                                                            LEFT JOIN itc_exp_rubric_master AS c ON c.fld_destination_id=a.fld_id 
                                                                                            WHERE c.fld_rubric_id='".$rubid."' AND c.fld_exp_id='".$expeditionid."' 
                                                                                            AND c.fld_destination_id='".$did[$i]."' AND a.fld_delstatus='0' 
                                                                                            AND c.fld_delstatus='0' ");//AND c.fld_created_by='".$uid1."'
                                          }
?>

                                <tr class="bcolor">

                                    <?php

                                    if($scheduletype=='23' || $scheduletype=='18' || $scheduletype=='19') {
                                        $tempdesttitle="INTERVAL";

                                    }else{
                                        $tempdesttitle="DESTINATION";
                                    }

                                    ?>



                                    <td style="font-size:  14px; letter-spacing:-0.1px; word-spacing:-0.2px;     word-wrap: break-word;     line-height: 20px; font-weight:bold;"> <?php echo $tempdesttitle; ?> <?php echo $i+1;?></td>
                                    <td colspan="8" style="font-size:  14px; letter-spacing:-0.1px; word-spacing:-0.2px;     word-wrap: break-word;     line-height: 20px; font-weight:bold;"><?php echo $dname[$i];?></td>


                                </tr>
                                <?php


                                              
                                            if($qryviewexp_rubric->num_rows > 0) 
                                            {
                                                $cnt=1;
                                                while($row=$qryviewexp_rubric->fetch_assoc())
                                                {
                                                 extract($row);
                                                 
                                                  //placeholders array
                                                     $placeholders = array('•', '♦', '◘','*');//'>', '<'
                                                    //replace values array
                                                    $replace = array('<br/>•', '<br/>♦', '<br/>◘','<br/>*');//'greater than', 'less than'
                                                  
                                                 $category = str_replace($placeholders, $replace, $category);
                                                 $category = str_replace(',', '', $category);
                                                 $four = str_replace($placeholders, $replace, $four);
                                                 $three = str_replace($placeholders, $replace, $three);
                                                 $two = str_replace($placeholders, $replace, $two);
                                                 $one = str_replace($placeholders, $replace, $one);
                                                 $zer = str_replace($placeholders, $replace, $zer);
                                                    ?>
                                                    <tr class="Btn destrubrow"  id="exp-rubric-<?php echo $rubricid; ?>" >
                                                        <td class="grcategory"  width="15%" id="rubrictxt-<?php echo $rubricid; ?>" ><b class="cattitle"><?php echo $category ;?></b><br><br>
                                                             <a class="standardsbtn"  onclick="fn_viewstandards('<?php echo $scheduletype; ?>', '<?php echo $rubricid; ?>', this);">View Standards</a>
                                                         </td>
                                                        <td  class="centerText desctd" id="rubrictxt-<?php echo $rubricid; ?>-4"  onclick="fn_highlight('4','<?php echo $rubricid; ?>');fn_showdeststmt('4','<?php echo $weight ;?>','<?php echo $rubricid; ?>','<?php echo $destid; ?>','<?php echo $rubid; ?>','<?php echo $scheduletype; ?>');this.disabled='disabled';"  ><?php echo $four ;?></td>
                                                        <td class="centerText desctd" id="rubrictxt-<?php echo $rubricid; ?>-3"  onclick="fn_highlight('3','<?php echo $rubricid; ?>');fn_showdeststmt('3','<?php echo $weight ;?>','<?php echo $rubricid; ?>','<?php echo $destid; ?>','<?php echo $rubid; ?>','<?php echo $scheduletype; ?>');this.disabled='disabled';"  ><?php echo $three ;?></td>
                                                        <td  class="centerText desctd" id="rubrictxt-<?php echo $rubricid; ?>-2"  onclick="fn_highlight('2','<?php echo $rubricid; ?>');fn_showdeststmt('2','<?php echo $weight ;?>','<?php echo $rubricid; ?>','<?php echo $destid; ?>','<?php echo $rubid; ?>','<?php echo $scheduletype; ?>');this.disabled='disabled';"  ><?php echo $two ;?></td>
                                                        <td  class="centerText desctd" id="rubrictxt-<?php echo $rubricid; ?>-1"  onclick="fn_highlight('1','<?php echo $rubricid; ?>');fn_showdeststmt('1','<?php echo $weight ;?>','<?php echo $rubricid; ?>','<?php echo $destid; ?>','<?php echo $rubid; ?>','<?php echo $scheduletype; ?>');this.disabled='disabled';"  ><?php echo $one ;?></td>
                                                        <td  class="centerText desctd" id="rubrictxt-<?php echo $rubricid; ?>-0"  onclick="fn_highlight('0','<?php echo $rubricid; ?>');fn_showdeststmt('0','<?php echo $weight ;?>','<?php echo $rubricid; ?>','<?php echo $destid; ?>','<?php echo $rubid; ?>','<?php echo $scheduletype; ?>');this.disabled='disabled';"  ><?php echo $zer ;?></td>
                                                        <td  class="scoretd" id="rubrictxt-<?php echo $rubricid; ?>" ><?php echo "X".$weight ;?></td>
                                                        <td  class="scoretd" id="rubrictxt-<?php echo $rubricid; ?>" >
															<input type="hidden" name="rubrictxtoldval_<?php echo $rubricid; ?>" id="rubrictxtoldval_<?php echo $rubricid; ?>" value="">
                                                            <input  type='text' id="txtscore-<?php echo $rubricid; ?>" readonly="" name="txtscore" maxlength="3" min="0" max="<?php //echo $scoree ;?>" value="<?php //echo $score ;?>" onkeypress="return isNumber(event)"  style="min-width:12px; max-width:16px; text-align:right; border: none; background: transparent;  padding-right:4px; font-size:13px; font-size:14px;   margin-top: -1px; font-weight: bold; color:#369;"><?php echo "/ ".$score ;?>
															<input type="hidden" id="ids_<?php echo $rubricid."~".$destid."~".$weight."~".$type; ?>" name="ids" value="">
                                                        </td>
                                                        <!-- Code by barney related to #23153-->
                                                        <td width="15%" id="rubrictxt-<?php echo $rubricid; ?>" style="padding: 0; border: 1px solid #DDD; border-right: none; margin: 0; line-height: 0; background: #fff; vertical-align: top !important;">
                                                            <textarea class="commentbox" spellcheck="true" maxlength="140" style="font-size: 11.5px;  line-height: 1.4em;       height: inherit;  min-height: 140px;    width: calc(100% - 14px); color:#333; padding:10px 7px;" id="txtcomment-<?php echo $rubricid; ?>" name="txtcomment" placeholder="Enter New Grading Rubric Comment"></textarea>
                                                        </td>
                                                    </tr>

                                                    <?php
                                                   
                                                }
                                            }
                                            else{           ?>
                                                    <tr id="exp-rubric-0">
                                                        <td colspan="8" align="center">  </td>
                                                    </tr> 
                                                    <?php }   ?>
                                      </tbody>
                                        <script type='text/javascript'>



                                            function fn_printdigrubric(rubid, expid, stype)
                                            {
                                                if(stype == 19) stype = 18;
                                                $("#reports-pdfviewer").remove();
                                                var ids = [];
                                                var score=[];
                                                var comments=[];

                                                if($("#rubricforms").validate().form()) //Validates the Rubric Form
                                                {
                                                    var list10 = [];
                                                    $("div[id^=list10_]").each(function(){
                                                        list10.push($(this).attr('id').replace('list10_',''));
                                                    });

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
													var tempschedid = $("#rubschedid2").val();
													var classid = $("#classid2").val();
                                                    var schid=tempschedid + '_' + stype;
                                                    var rubricid='<?php echo $rubid; ?>';
                                                    var val = expid+"~"+classid+"~"+rubricid+"~"+list10+"~"+schid;

                                                    // Code by barney related to #23153
//                                                    console.log(comments);
//                                                    var val3 = "oper=digitalrubricreport&list10="+list10+"&expid="+expid+"&rubid="+id+"&ids="+ids+"&classid="+classid+"&schid="+schid+"&score="+score+"&comments="+comments;
//                                                    console.log(val3);
                                                    setTimeout('removesections("#reports-digitalrubric");',500);
                                                    oper="digitalrubricreport";
                                                    filename='digitalrubricreport' + new Date().getTime();
                                                    console.log('aaaaaa("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+filename+'");');


                                                    ajaxloadingalert('Loading, please wait.');
                                                    setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+filename+'");',500);

                                                }
                                            }

                                        </script>
                                     <?php   
                                     }
                                    ?>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
           
            </div> 
 
<script type="text/javascript" language="javascript">
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
           return false;
        }
        return true;
    }
        
        function fn_highlight(cellid,rubid)
        {
                var otherval = []; 
                $('#rubrictxt-'+rubid+'-'+cellid).toggleClass("td_select");  
                
                for(var a=0;a<=4;a++)
                {
                        if(parseInt(a)!=cellid)
                        {
                                otherval.push(a);  
                        }
                    
                }
                for(var b=0;b<otherval.length;b++)
                {
                        $('#rubrictxt-'+rubid+'-'+otherval[b]).removeClass("td_select"); 
                }
        }

    $('.rubrictable').slimscroll({
        height:'auto',
        size: '5px',
        railVisible: true,
        allowPageScroll: true,
        railColor: '#F4F4F4',
        opacity: 9,
        color: '#88ABC2'
    });
    function fn_viewstandards(stype, rubid, standardsbtn){
        var rubcategory = $(standardsbtn).parent().find(".cattitle").text() + " Academic Standards";
        var val = 'schedtype=' + stype + "&rubid=" + rubid;
        console.log(val);

            $.ajax({
                type: 'POST',
                url: 'reports/rubric/reports-rubric-standards.php',
                data: val,
                success: function (data) {
                    $.Zebra_Dialog(data,
                        {
                            'type':     'information',
                            'buttons':  [
                                {caption: 'Close', callback: function() {
                                    console.log("a");
                                }}
                            ],
                            'title': rubcategory,
                            width: 560
                        });
                }
            });
    }
</script>
    
    <?php    
}




/*--- Save and Update the Rubric ---NOt Work this code */
if($oper=="saverubric")
{
    try{
        
        $type = isset($method['type']) ? $method['type'] : '';
        $rubid = isset($method['rubnameid']) ? $method['rubnameid'] : '0'; 
        $expid = isset($method['expid']) ? $method['expid'] : '0'; 
       

        $studentid = isset($method['list10']) ? ($method['list10']) : '0'; 
        $score = isset($method['txtscore']) ? $method['txtscore'] : '0'; 
        $ruborderid = isset($method['ruborderid']) ? $method['ruborderid'] : '0'; 
        $destid = isset($method['destid']) ? $method['destid'] : '0'; 


        $stuid=explode(",",$studentid);
        $sco=explode(",",$score); 
            
            
        if($type==1 || $type==2)
        {
                $clasid = isset($method['classid']) ? $method['classid'] : '0';
                $schid = isset($method['schid']) ? $method['schid'] : '';

                $schid =explode("~",$schid );
                $scheduleid=$schid[0];
                $scheduletype=$schid[1];
            
        }
        else
        {
                $classid = isset($method['classid']) ? $method['classid'] : '';
                $schid =explode("~",$classid);
                $clasid=$schid[0];
                $scheduleid=$schid[1];
                $scheduletype=$schid[2]; 
                
        }
        if($scheduletype=='15')
        {
                    //save class Name
                $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_rpt WHERE fld_exp_id='".$expid."' AND fld_class_id='".$clasid."' AND fld_schedule_id='".$scheduleid."'
                                                            AND fld_rubric_nameid='".$rubid."' AND fld_created_by='".$uid."' AND fld_delstatus = '0'");
                if($cnt==0)
                {
                   $maxid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_exp_rubric_rpt (fld_class_id, fld_exp_id, fld_rubric_nameid, fld_created_by, fld_created_date, fld_district_id, fld_school_id, fld_user_id,fld_profile_id,fld_schedule_id) 
                                                            VALUES ('".$clasid."', '".$expid."', '".$rubid."', '".$uid."', '".$date."','".$sendistid."','".$schoolid."','".$indid."','".$sessmasterprfid."','".$scheduleid."')");
                }
                else
                {
                   $ObjDB->NonQuery("UPDATE itc_exp_rubric_rpt SET fld_class_id='".$clasid."', fld_exp_id='".$expid."', fld_updated_by='".$uid."', 
                                                      fld_updated_date='".$date."' WHERE fld_rubric_nameid='".$rubid."'  AND fld_class_id='".$clasid."' AND fld_schedule_id='".$scheduleid."'  AND fld_delstatus = '0' ");
                   $maxid=$cnt;
                }

               /*rubric stmt*/

                for($i=0;$i<sizeof($stuid);$i++)
                {
                    $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_rubric_rpt_statement 
                                                            WHERE fld_dest_id='".$destid."' AND fld_rubric_id='".$ruborderid."' AND fld_rubric_rpt_id='".$maxid."'
                                                            AND fld_exp_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid[$i]."'");
                    if($cnt==0)
                    {
                            $ObjDB->NonQuery("INSERT INTO itc_exp_rubric_rpt_statement (fld_rubric_rpt_id, fld_dest_id, fld_rubric_id, fld_score, fld_created_by, fld_created_date, fld_rubric_nameid, fld_exp_id, fld_student_id) 
                                                                         VALUES ('".$maxid."', '".$destid."', '".$ruborderid."', '".$score."', '".$uid."', '".$date."', '".$rubid."', '".$expid."', '".$stuid[$i]."')");
                    }
                    else
                    {
                           $ObjDB->NonQuery("UPDATE itc_exp_rubric_rpt_statement 
                                                SET fld_score='".$score."', fld_delstatus='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
                                                WHERE fld_dest_id='".$destid."' AND fld_rubric_id='".$ruborderid."' AND fld_rubric_rpt_id='".$maxid."'
                                                AND fld_exp_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid[$i]."'");
                    }
                }    
        }
        else
        {
                
                //save class Name
                $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_expsch_rubric_rpt WHERE fld_exp_id='".$expid."' AND fld_class_id='".$clasid."' AND fld_schedule_id='".$scheduleid."'
                                                            AND fld_rubric_nameid='".$rubid."' AND fld_created_by='".$uid."' AND fld_delstatus = '0'");
                if($cnt==0)
                {
                   $maxid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_expsch_rubric_rpt (fld_class_id, fld_exp_id, fld_rubric_nameid, fld_created_by, fld_created_date, fld_district_id, fld_school_id, fld_user_id,fld_profile_id,fld_schedule_id) 
                                                            VALUES ('".$clasid."', '".$expid."', '".$rubid."', '".$uid."', '".$date."','".$sendistid."','".$schoolid."','".$indid."','".$sessmasterprfid."','".$scheduleid."')");
                }
                else
                {
                   $ObjDB->NonQuery("UPDATE itc_expsch_rubric_rpt SET fld_class_id='".$clasid."', fld_exp_id='".$expid."', fld_updated_by='".$uid."', 
                                                      fld_updated_date='".$date."' WHERE fld_rubric_nameid='".$rubid."'  AND fld_class_id='".$clasid."' AND fld_schedule_id='".$scheduleid."' AND fld_delstatus = '0' ");
                   $maxid=$cnt;
                }

               /*rubric stmt*/

                for($i=0;$i<sizeof($stuid);$i++)
                {
                    $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_expsch_rubric_rpt_statement 
                                                            WHERE fld_dest_id='".$destid."' AND fld_rubric_id='".$ruborderid."' AND fld_rubric_rpt_id='".$maxid."'
                                                            AND fld_exp_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid[$i]."'");
                    if($cnt==0)
                    {
                            $ObjDB->NonQuery("INSERT INTO itc_expsch_rubric_rpt_statement (fld_rubric_rpt_id, fld_dest_id, fld_rubric_id, fld_score, fld_created_by, fld_created_date, fld_rubric_nameid, fld_exp_id, fld_student_id) 
                                                                         VALUES ('".$maxid."', '".$destid."', '".$ruborderid."', '".$score."', '".$uid."', '".$date."', '".$rubid."', '".$expid."', '".$stuid[$i]."')");
                    }
                    else
                    {
                           $ObjDB->NonQuery("UPDATE itc_expsch_rubric_rpt_statement 
                                                SET fld_score='".$score."', fld_delstatus='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
                                                WHERE fld_dest_id='".$destid."' AND fld_rubric_id='".$ruborderid."' AND fld_rubric_rpt_id='".$maxid."'
                                                AND fld_exp_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid[$i]."'");
                    }
                }
        }
        echo "success";
    }
    catch(Exception $e)
    {
        echo "fail";
    }
}

/*** Save and update the Rubric new created by chandru update by MOhan M ***/
if($oper=="saverubricval")
{
    error_reporting(E_ALL);
	try
	{
		$studentid = isset($method['list10']) ? ($method['list10']) : '0'; 
		$expid = isset($method['expid']) ? ($method['expid']) : '0'; 
		$rubid = isset($method['rubid']) ? ($method['rubid']) : '0';
		$ids = isset($method['ids']) ? ($method['ids']) : '0';
        $score = isset($method['score']) ? ($method['score']) : '0';

        // Code by barney related to #23153
        $comments = isset($method['comments']) ? ($method['comments']) : '0';
        $comments=explode(",",$comments);

		$score=explode(",",$score);
		$stuid=explode(",",$studentid);
		$ids=explode(",",$ids);

		$id = '';
		$type = '';

		for($i=0;$i<sizeof($ids);$i++)
		{
			$idval  = explode('~',$ids[$i]); 
			if($id=='')
			{
				$type=$idval[3];
			}
			else
			{
				$type=$type.",".$idval[3];
			}
		}

        if($type==15)
        {
            $type=15;
        }
        else if($type==16)
        {
            $type=15;
        }
        else if($type==21)
        {
            $type=18;
        }
        else if($type==19)
        {
            $type=18;
        }
        else if($type==25)
        {
            $type=20;
        }
        else if($type==24)
        {
            $type=18;
        }
        else if($type==18)
        {
            $type=18;
        }
        else
        {
            $type=15;
        }
		if($type==1 || $type==2)
		{
			$clasid = isset($method['classid']) ? $method['classid'] : '0';
            $classid = isset($method['classid']) ? $method['classid'] : '0';
			$schid = isset($method['schid']) ? $method['schid'] : '';

			$schid =explode("~",$schid );
			$scheduleid=$schid[0];
			$scheduletype=$schid[1];

		}
		else
		{
			$classid = isset($method['classid']) ? $method['classid'] : '';
			if(strpos($classid, "~") > 0){
                $schid =explode("~",$classid);
                $clasid=$schid[0];
                $scheduleid=$schid[1];
                $scheduletype=$schid[2];
            }else{
                $clasid = isset($method['classid']) ? $method['classid'] : '0';
                $schid = isset($method['schid']) ? $method['schid'] : '';
                $scheduleid = $schid;
                $scheduletype = $type;
            }

		}
        $clasid = $classid;
        if($scheduletype=='15' || $scheduletype=='23')
        {
            //save class Name
            $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_rpt WHERE fld_exp_id='".$expid."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'
														AND fld_rubric_nameid='".$rubid."' AND fld_created_by='".$uid."' AND fld_delstatus = '0'");
            if($cnt==0)
            {
                $maxid2=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_exp_rubric_rpt (fld_class_id, fld_exp_id, fld_rubric_nameid, fld_created_by, fld_created_date, fld_district_id, fld_school_id, fld_user_id,fld_profile_id,fld_schedule_id) 
														VALUES ('".$classid."', '".$expid."', '".$rubid."', '".$uid."', '".$date."','".$sendistid."','".$schoolid."','".$indid."','".$sessmasterprfid."','".$scheduleid."')");
            }
            else
            {
                $ObjDB->NonQuery("UPDATE itc_exp_rubric_rpt SET fld_class_id='".$classid."', fld_exp_id='".$expid."', fld_updated_by='".$uid."', 
												  fld_updated_date='".$date."' WHERE fld_rubric_nameid='".$rubid."'  AND fld_created_by='".$uid."'  AND fld_schedule_id='".$scheduleid."' AND fld_delstatus = '0' ");
                $maxid2=$cnt;
            }

            //save class Name
            $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_expsch_rubric_rpt WHERE fld_exp_id='".$expid."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'
														AND fld_rubric_nameid='".$rubid."' AND fld_created_by='".$uid."' AND fld_delstatus = '0'");
            if($cnt==0)
            {
                $maxid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_expsch_rubric_rpt (fld_class_id, fld_exp_id, fld_rubric_nameid, fld_created_by, fld_created_date, fld_district_id, fld_school_id, fld_user_id,fld_profile_id,fld_schedule_id) 
														VALUES ('".$classid."', '".$expid."', '".$rubid."', '".$uid."', '".$date."','".$sendistid."','".$schoolid."','".$indid."','".$sessmasterprfid."','".$scheduleid."')");
            }
            else
            {
                $ObjDB->NonQuery("UPDATE itc_expsch_rubric_rpt SET fld_class_id='".$classid."', fld_exp_id='".$expid."', fld_updated_by='".$uid."', 
												  fld_updated_date='".$date."' WHERE fld_rubric_nameid='".$rubid."' AND fld_created_by='".$uid."'   AND fld_schedule_id='".$scheduleid."' AND fld_delstatus = '0' ");
                $maxid=$cnt;
            }

            /*rubric stmt*/

            for($j=0;$j<sizeof($stuid);$j++)
            {
                for($i=0;$i<sizeof($ids);$i++)
                {
                    $idval  = explode('~',$ids[$i]);

                    if($score[$i] == '')
                    {
                        $score[$i] = 'NULL';
                    }

                    $rubweight = $ObjDB->SelectSingleValueInt("SELECT fld_weight FROM itc_exp_rubric_master 
                                                                                        WHERE fld_id='".$idval[0]."' AND fld_destination_id='".$idval[1]."' 
                                                                                        AND fld_exp_id='".$expid."' AND fld_delstatus='0'");

                    if($score[$i]!='NULL')
                    {
                        if($score[$i]==0)
                        {
                            $cellid=0;
                        }
                        else
                        {
                            $cellid=($score[$i]/$rubweight);
                        }

                    }
                    else
                    {
                        $cellid='NULL';
                    }


                    $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_expsch_rubric_rpt_statement 
															WHERE fld_dest_id='".$idval[1]."' AND fld_rubric_id='".$idval[0]."' AND fld_rubric_rpt_id='".$maxid."'
															AND fld_exp_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid[$j]."'");

                    if($cnt==0)
                    {
                        $ObjDB->NonQuery("INSERT INTO itc_expsch_rubric_rpt_statement (fld_rubric_rpt_id, fld_dest_id, fld_rubric_id, fld_score, fld_created_by, fld_created_date, fld_rubric_nameid, fld_exp_id, fld_student_id, fld_hightlight_cell, fld_comment) 
                                                                                                 VALUES ('".$maxid."', '".$idval[1]."', '".$idval[0]."', '".$score[$i]."', '".$uid."', '".$date."', '".$rubid."', '".$expid."', '".$stuid[$j]."', '".$cellid."', '".$comments[$i]."')");
                    }
                    else
                    {
                        $ObjDB->NonQuery("UPDATE itc_expsch_rubric_rpt_statement 
                                                                                        SET fld_score='".$score[$i]."', fld_comment='".$comments[$i]."', fld_delstatus='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."', fld_hightlight_cell = '".$cellid."'  
												WHERE fld_dest_id='".$idval[1]."' AND fld_rubric_id='".$idval[0]."' AND fld_rubric_rpt_id='".$maxid."'
												AND fld_exp_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid[$j]."'");
                    }


                    $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_rpt_statement 
															WHERE fld_dest_id='".$idval[1]."' AND fld_rubric_id='".$idval[0]."' AND fld_rubric_rpt_id='".$maxid2."'
															AND fld_exp_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid[$j]."'");

                    if($cnt==0)
                    {
                        $ObjDB->NonQuery("INSERT INTO itc_exp_rubric_rpt_statement (fld_rubric_rpt_id, fld_dest_id, fld_rubric_id, fld_score, fld_created_by, fld_created_date, fld_rubric_nameid, fld_exp_id, fld_student_id, fld_hightlight_cell, fld_comment) 
                                                                                                 VALUES ('".$maxid2."', '".$idval[1]."', '".$idval[0]."', '".$score[$i]."', '".$uid."', '".$date."', '".$rubid."', '".$expid."', '".$stuid[$j]."', '".$cellid."', '".$comments[$i]."')");
                    }
                    else
                    {
                        $ObjDB->NonQuery("UPDATE itc_exp_rubric_rpt_statement 
                                                                                        SET fld_score='".$score[$i]."', fld_comment='".$comments[$i]."', fld_delstatus='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."', fld_hightlight_cell = '".$cellid."'  
												WHERE fld_dest_id='".$idval[1]."' AND fld_rubric_id='".$idval[0]."' AND fld_rubric_rpt_id='".$maxid2."'
												AND fld_exp_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid[$j]."'");
                    }

                }
            }
        }
        else if($scheduletype=='18' || $scheduletype == '20' || $scheduletype == '19')
        {

            //save class Name
            $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_rubric_rpt WHERE fld_mis_id='".$expid."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'
														AND fld_rubric_nameid='".$rubid."' AND fld_created_by='".$uid."' AND fld_delstatus = '0'");
            if($cnt==0)
            {
                $maxid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_mis_rubric_rpt (fld_class_id, fld_mis_id, fld_rubric_nameid, fld_created_by, fld_created_date, fld_district_id, fld_school_id, fld_user_id,fld_profile_id,fld_schedule_id) 
														VALUES ('".$classid."', '".$expid."', '".$rubid."', '".$uid."', '".$date."','".$sendistid."','".$schoolid."','".$indid."','".$sessmasterprfid."','".$scheduleid."')");
            }
            else
            {
                $ObjDB->NonQuery("UPDATE itc_mis_rubric_rpt SET fld_class_id='".$classid."', fld_mis_id='".$expid."', fld_updated_by='".$uid."', 
												  fld_updated_date='".$date."' WHERE fld_rubric_nameid='".$rubid."'  AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_delstatus = '0' ");
                $maxid2=$cnt;
            }
            //save class Name
            $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_missch_rubric_rpt WHERE fld_mis_id='".$expid."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'
														AND fld_rubric_nameid='".$rubid."' AND fld_created_by='".$uid."' AND fld_delstatus = '0'");
            if($cnt==0)
            {
                $maxid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_missch_rubric_rpt (fld_class_id, fld_mis_id, fld_rubric_nameid, fld_created_by, fld_created_date, fld_district_id, fld_school_id, fld_user_id,fld_profile_id,fld_schedule_id) 
														VALUES ('".$classid."', '".$expid."', '".$rubid."', '".$uid."', '".$date."','".$sendistid."','".$schoolid."','".$uid."','".$sessmasterprfid."','".$scheduleid."')");
            }
            else
            {
                $ObjDB->NonQuery("UPDATE itc_missch_rubric_rpt SET fld_class_id='".$classid."', fld_mis_id='".$expid."', fld_updated_by='".$uid."', 
												  fld_updated_date='".$date."' WHERE fld_rubric_nameid='".$rubid."'  AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_delstatus = '0' ");
                $maxid=$cnt;
            }

            /*rubric stmt*/

            for($j=0;$j<sizeof($stuid);$j++)
            {
                for($i=0;$i<sizeof($ids);$i++)
                {
                    $idval  = explode('~',$ids[$i]);

                    if($score[$i] == '')
                    {
                        $score[$i] = 'NULL';
                    }

                    $rubweight = $ObjDB->SelectSingleValueInt("SELECT fld_weight FROM itc_mis_rubric_master 
                                                                                        WHERE fld_id='".$idval[0]."' AND fld_destination_id='".$idval[1]."' 
                                                                                        AND fld_mis_id='".$expid."' AND fld_delstatus='0'");

                    if($score[$i]!='NULL')
                    {
                        if($score[$i]==0)
                        {
                            $cellid=0;
                        }
                        else
                        {
                            $cellid=($score[$i]/$rubweight);
                        }

                    }
                    else
                    {
                        $cellid='NULL';
                    }


                    $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_missch_rubric_rpt_statement 
															WHERE fld_dest_id='".$idval[1]."' AND fld_rubric_id='".$idval[0]."' AND fld_rubric_rpt_id='".$maxid."'
															AND fld_mis_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid[$j]."'");

                    if($cnt==0)
                    {
                        $ObjDB->NonQuery("INSERT INTO itc_missch_rubric_rpt_statement (fld_rubric_rpt_id, fld_dest_id, fld_rubric_id, fld_score, fld_created_by, fld_created_date, fld_rubric_nameid, fld_mis_id, fld_student_id, fld_hightlight_cell, fld_comment) 
                                                                                                 VALUES ('".$maxid."', '".$idval[1]."', '".$idval[0]."', '".$score[$i]."', '".$uid."', '".$date."', '".$rubid."', '".$expid."', '".$stuid[$j]."', '".$cellid."', '".$comments[$i]."')");
                    }
                    else
                    {
                        $ObjDB->NonQuery("UPDATE itc_missch_rubric_rpt_statement 
                                                                                        SET fld_score='".$score[$i]."', fld_comment='".$comments[$i]."', fld_delstatus='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."', fld_hightlight_cell = '".$cellid."'  
												WHERE fld_dest_id='".$idval[1]."' AND fld_rubric_id='".$idval[0]."' AND fld_rubric_rpt_id='".$maxid."'
												AND fld_mis_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid[$j]."'");
                    }


                    $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_rubric_rpt_statement 
															WHERE fld_dest_id='".$idval[1]."' AND fld_rubric_id='".$idval[0]."' AND fld_rubric_rpt_id='".$maxid2."'
															AND fld_mis_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid[$j]."'");

                    if($cnt==0)
                    {
                        $ObjDB->NonQuery("INSERT INTO itc_mis_rubric_rpt_statement (fld_rubric_rpt_id, fld_dest_id, fld_rubric_id, fld_score, fld_created_by, fld_created_date, fld_rubric_nameid, fld_mis_id, fld_student_id, fld_hightlight_cell, fld_comment) 
                                                                                                 VALUES ('".$maxid2."', '".$idval[1]."', '".$idval[0]."', '".$score[$i]."', '".$uid."', '".$date."', '".$rubid."', '".$expid."', '".$stuid[$j]."', '".$cellid."', '".$comments[$i]."')");
                    }
                    else
                    {
                        $ObjDB->NonQuery("UPDATE itc_mis_rubric_rpt_statement 
                                                                                        SET fld_score='".$score[$i]."', fld_comment='".$comments[$i]."', fld_delstatus='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."', fld_hightlight_cell = '".$cellid."'  
												WHERE fld_dest_id='".$idval[1]."' AND fld_rubric_id='".$idval[0]."' AND fld_rubric_rpt_id='".$maxid2."'
												AND fld_mis_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid[$j]."'");
                    }

                }
            }
        }
        else
        {
            //save class Name
            $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_rpt WHERE fld_exp_id='".$expid."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'
														AND fld_rubric_nameid='".$rubid."' AND fld_created_by='".$uid."' AND fld_delstatus = '0'");
            if($cnt==0)
            {
                $maxid2=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_exp_rubric_rpt (fld_class_id, fld_exp_id, fld_rubric_nameid, fld_created_by, fld_created_date, fld_district_id, fld_school_id, fld_user_id,fld_profile_id,fld_schedule_id) 
														VALUES ('".$classid."', '".$expid."', '".$rubid."', '".$uid."', '".$date."','".$sendistid."','".$schoolid."','".$indid."','".$sessmasterprfid."','".$scheduleid."')");
            }
            else
            {
                $ObjDB->NonQuery("UPDATE itc_exp_rubric_rpt SET fld_class_id='".$classid."', fld_exp_id='".$expid."', fld_updated_by='".$uid."', 
												  fld_updated_date='".$date."' WHERE fld_rubric_nameid='".$rubid."'  AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_delstatus = '0' ");
                $maxid2=$cnt;
            }

            //save class Name
            $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_expsch_rubric_rpt WHERE fld_exp_id='".$expid."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'
														AND fld_rubric_nameid='".$rubid."' AND fld_created_by='".$uid."' AND fld_delstatus = '0'");
            if($cnt==0)
            {
                $maxid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_expsch_rubric_rpt (fld_class_id, fld_exp_id, fld_rubric_nameid, fld_created_by, fld_created_date, fld_district_id, fld_school_id, fld_user_id,fld_profile_id,fld_schedule_id) 
														VALUES ('".$classid."', '".$expid."', '".$rubid."', '".$uid."', '".$date."','".$sendistid."','".$schoolid."','".$indid."','".$sessmasterprfid."','".$scheduleid."')");
            }
            else
            {
                $ObjDB->NonQuery("UPDATE itc_expsch_rubric_rpt SET fld_class_id='".$classid."', fld_exp_id='".$expid."', fld_updated_by='".$uid."', 
												  fld_updated_date='".$date."' WHERE fld_rubric_nameid='".$rubid."'  AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_delstatus = '0' ");
                $maxid=$cnt;
            }

            /*rubric stmt*/

            for($j=0;$j<sizeof($stuid);$j++)
            {
                for($i=0;$i<sizeof($ids);$i++)
                {
                    $idval  = explode('~',$ids[$i]);

                    if($score[$i] == '')
                    {
                        $score[$i] = 'NULL';
                    }

                    $rubweight = $ObjDB->SelectSingleValueInt("SELECT fld_weight FROM itc_exp_rubric_master 
                                                                                        WHERE fld_id='".$idval[0]."' AND fld_destination_id='".$idval[1]."' 
                                                                                        AND fld_exp_id='".$expid."' AND fld_delstatus='0'");

                    if($score[$i]!='NULL')
                    {
                        if($score[$i]==0)
                        {
                            $cellid=0;
                        }
                        else
                        {
                            $cellid=($score[$i]/$rubweight);
                        }

                    }
                    else
                    {
                        $cellid='NULL';
                    }


                    $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_expsch_rubric_rpt_statement 
															WHERE fld_dest_id='".$idval[1]."' AND fld_rubric_id='".$idval[0]."' AND fld_rubric_rpt_id='".$maxid."'
															AND fld_exp_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid[$j]."'");

                    if($cnt==0)
                    {
                        $ObjDB->NonQuery("INSERT INTO itc_expsch_rubric_rpt_statement (fld_rubric_rpt_id, fld_dest_id, fld_rubric_id, fld_score, fld_created_by, fld_created_date, fld_rubric_nameid, fld_exp_id, fld_student_id, fld_hightlight_cell, fld_comment) 
                                                                                                 VALUES ('".$maxid."', '".$idval[1]."', '".$idval[0]."', '".$score[$i]."', '".$uid."', '".$date."', '".$rubid."', '".$expid."', '".$stuid[$j]."', '".$cellid."', '".$comments[$i]."')");
                    }
                    else
                    {
                        $ObjDB->NonQuery("UPDATE itc_expsch_rubric_rpt_statement 
                                                                                        SET fld_score='".$score[$i]."', fld_comment='".$comments[$i]."', fld_delstatus='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."', fld_hightlight_cell = '".$cellid."'  
												WHERE fld_dest_id='".$idval[1]."' AND fld_rubric_id='".$idval[0]."' AND fld_rubric_rpt_id='".$maxid."'
												AND fld_exp_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid[$j]."'");
                    }


                    $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_rpt_statement 
															WHERE fld_dest_id='".$idval[1]."' AND fld_rubric_id='".$idval[0]."' AND fld_rubric_rpt_id='".$maxid2."'
															AND fld_exp_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid[$j]."'");

                    if($cnt==0)
                    {
                        $ObjDB->NonQuery("INSERT INTO itc_exp_rubric_rpt_statement (fld_rubric_rpt_id, fld_dest_id, fld_rubric_id, fld_score, fld_created_by, fld_created_date, fld_rubric_nameid, fld_exp_id, fld_student_id, fld_hightlight_cell, fld_comment) 
                                                                                                 VALUES ('".$maxid2."', '".$idval[1]."', '".$idval[0]."', '".$score[$i]."', '".$uid."', '".$date."', '".$rubid."', '".$expid."', '".$stuid[$j]."', '".$cellid."', '".$comments[$i]."')");
                    }
                    else
                    {
                        $ObjDB->NonQuery("UPDATE itc_exp_rubric_rpt_statement 
                                                                                        SET fld_score='".$score[$i]."', fld_comment='".$comments[$i]."', fld_delstatus='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."', fld_hightlight_cell = '".$cellid."'  
												WHERE fld_dest_id='".$idval[1]."' AND fld_rubric_id='".$idval[0]."' AND fld_rubric_rpt_id='".$maxid2."'
												AND fld_exp_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid[$j]."'");
                    }

                }
            }
        }
		echo "success";
	}
	catch(Exception $e)
	{
	    print_r($e);
		echo "fail";
	}
	
}