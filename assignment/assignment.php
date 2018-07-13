<?php
@include("sessioncheck.php");
$completed='';
$_SESSION['moduleid']='';
$_SESSION['scheduleid']='';
$_SESSION['scheduletype']='';
$_SESSION['sessionid']='';
$_SESSION['type']='';
$id= isset($method['id']) ? $method['id'] : '0';
$sid= isset($method['sid']) ? $method['sid'] : '0';		
?>
<section data-type='2home' id='assignment'>
	<div class='container'>
        <div class='row'>
        	<div class="span10">
              <p class="dialogTitle">Assignments</p>
              <p class="dialogSubTitleLight">&nbsp;Select the schedule from the list to start the assignment.</p>
            </div>           
        </div>
        
        <style>
			 .ui-tooltip, .arrow:after {
			background: black;
			border: 2px solid white;
			}
			.ui-tooltip {
			padding: 10px 20px;
			color: white;
			
			font: bold 14px "Helvetica Neue", Sans-Serif;
			
			}
			.arrow {
			width: 70px;
			height: 16px;
			overflow: hidden;
			position: absolute;
			left: 50%;
			margin-left: -35px;
			bottom: -16px;
			}
			.arrow.top {
			top: -16px;
			bottom: auto;
			}
			.arrow.left {
			left: 20%;
			}
			.arrow:after {
			content: "";
			position: absolute;
			left: 20px;
			top: -20px;
			width: 25px;
			height: 25px;
			box-shadow: 6px 5px 9px -9px;
			-webkit-transform: rotate(45deg);
			-moz-transform: rotate(45deg);
			-ms-transform: rotate(45deg);
			-o-transform: rotate(45deg);
			tranform: rotate(45deg);
			}
			.arrow.top:after {
			top: auto;
			}
        </style>
        
		<script>
			
			$('#tablecontents4').slimscroll({
				height:'auto',
				railVisible: false,
				allowPageScroll: false,
				railColor: '#F4F4F4',
				opacity: 9,
				color: '#88ABC2',
                                size: '7px',
                                alwaysVisible: true,
                                wheelstep: 1
			});
		</script>  
                
        <div class='row rowspacer'>  
        	<form id="frmbasicinfo" name="frmbasicinfo">
                <input type="hidden" id="preassign" name="preassign" value="0" />
                <ul class="four columns" style="float:right; margin-bottom:-8px; margin-right:-60px">
                    <li class="field">
                        <label class="checkbox" for="check1" onclick="fn_loadpages()">
                            <input name="checkbox[]" id="check1" value="1" type="checkbox" style="display:none;" />
                            <span></span> Show the previous Assignments
                        </label>
                    </li>
                </ul>
            </form>
        </div>
        <div class='row' id='assign'>  
        	<div class='span10 offset1'>                                      
                <table id="test" class='table table-hover table-striped table-bordered setbordertopradius'>
                    <thead class='tableHeadText'>
                        <tr>
                            <th style="width:40%">Schedule Name</th>
                            <th style="width:35%">Assignment Name</th>
                            <th class='centerText'>Due Date</th>                                                                  
                        </tr>
                    </thead>
                </table>
				<script>
                    function fn_loadpages(){
                        removesections('#assignment');
                        var id = $('#preassign').val();
                        if(id==0)
                            $('#preassign').val(1);
                        else if(id==1)
                            $('#preassign').val(0);
                        showloadingalert("Loading, please wait.");
                        $("#assign").load("assignment/assignment.php #assign > *",{ sid:$('#preassign').val() },function(){
                            $('#tablecontents4').slimscroll({
                                height:'auto',
                                railVisible: false,
                                allowPageScroll: false,
                                railColor: '#F4F4F4',
                                opacity: 9,
                                color: '#88ABC2',
                                size: '7px',
                                alwaysVisible: true,
                                wheelstep: 1
                            });
                        });
                        closeloadingalert();
                    }
                </script>
                <?php
                if($sid=='0'){
                    $label = 'AND DATE(a.fld_start_date) <= DATE(NOW()) AND DATE(a.fld_end_date) >= DATE(NOW())';
                    $label1 = 'AND DATE(b.fld_startdate) <= DATE(NOW()) AND DATE(b.fld_enddate) >= DATE(NOW())';
                    $label2 = 'AND DATE(a.fld_startdate) <= DATE(NOW()) AND DATE(a.fld_enddate) >= DATE(NOW())';
                    $label3 = 'AND DATE(c.fld_startdate) <= DATE(NOW()) AND DATE(c.fld_enddate) >= DATE(NOW())';
                    $label4 = 'AND DATE(g.fld_startdate) <= DATE(NOW()) AND DATE(g.fld_enddate) >= DATE(NOW()) AND g.fld_flag=1';
                    $act = 'AND DATE(b.fld_start_date) <= DATE(NOW()) AND DATE(b.fld_end_date) >= DATE(NOW())';
                    $con =  'AND DATE(b.fld_start_date)=DATE(NOW())';
                }
                else{
                    $label = 'AND DATE(a.fld_start_date) <= DATE(NOW())';
                    $label1 = 'AND DATE(b.fld_startdate) <= DATE(NOW())';
                    $label2 = 'AND DATE(a.fld_startdate) <= DATE(NOW())';
                    $label3 = 'AND DATE(c.fld_startdate) <= DATE(NOW())';
                    $label4 = 'AND DATE(g.fld_startdate) <= DATE(NOW()) AND g.fld_flag=1';
                    $act='AND DATE(b.fld_start_date) <= DATE(NOW())';
                    $con='AND DATE(b.fld_start_date)<=DATE(NOW())';
                }
                if($uid1=='')
                {
                    $qry = $ObjDB->QueryObject("SELECT w.* FROM (SELECT 0 AS fld_module_id, a.fld_schedule_name AS schedulename, a.fld_id AS scheduleid, a.fld_end_date AS fld_enddate, fn_shortname(a.fld_schedule_name,1) AS modulename, 0 AS schtype, b.fld_id as mapid,NULL AS fldorder FROM itc_class_sigmath_master AS a LEFT JOIN itc_class_sigmath_student_mapping AS b ON a.fld_id=b.fld_sigmath_id LEFT JOIN itc_class_master AS c ON c.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND c.fld_delstatus='0' AND c.fld_lock='0' AND b.fld_flag='1' AND b.fld_student_id='".$uid."' ".$label." AND c.fld_delstatus='0' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."') 		
                    UNION ALL       SELECT b.fld_module_id, CONCAT(a.fld_schedule_name,' / Module') AS schedulename, a.fld_id AS scheduleid, g.fld_enddate, CONCAT(c.fld_module_name,' / Rotation ',b.fld_rotation-1) AS modulename, 1 AS schtype, b.fld_id as mapid,NULL AS fldorder FROM `itc_class_rotation_schedule_mastertemp` AS a LEFT JOIN `itc_class_rotation_schedulegriddet` AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_class_rotation_scheduledate as g on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND b.fld_type='1' AND d.fld_delstatus='0' AND d.fld_lock='0' AND a.fld_moduletype='1' AND b.fld_student_id='".$uid."' AND DATE(a.fld_startdate) <= DATE(NOW()) ".$label4." AND a.fld_moduletype='1' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."') AND b.fld_flag='1'       
                    UNION ALL       SELECT b.fld_module_id, CONCAT(a.fld_schedule_name,' / Math Module') AS schedulename, a.fld_id AS scheduleid, g.fld_enddate, CONCAT(c.fld_mathmodule_name,' / Rotation ',b.fld_rotation-1) AS modulename, 4 AS schtype, b.fld_id as mapid,NULL AS fldorder FROM `itc_class_rotation_schedule_mastertemp` AS a LEFT JOIN `itc_class_rotation_schedulegriddet` AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_class_rotation_scheduledate as g on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation LEFT JOIN itc_mathmodule_master AS c ON b.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND b.fld_type='2' AND d.fld_lock='0' AND a.fld_moduletype='2' AND b.fld_student_id='".$uid."' AND DATE(a.fld_startdate) <= DATE(NOW()) ".$label4." AND a.fld_moduletype='2' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."') AND b.fld_flag='1' 		
                    UNION ALL       SELECT a.fld_module_id, CONCAT(a.fld_schedule_name,' / Ind Module') AS schedulename, a.fld_id AS scheduleid, a.fld_enddate, CONCAT(c.fld_module_name,' / Individual Module ') AS modulename, 5 AS schtype, b.fld_id as mapid,NULL AS fldorder FROM `itc_class_indassesment_master` AS a LEFT JOIN `itc_class_indassesment_student_mapping` AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND a.fld_moduletype='1' AND b.fld_student_id='".$uid."' ".$label2." AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."') AND b.fld_flag='1'				
                    UNION ALL       SELECT a.fld_module_id, CONCAT(a.fld_schedule_name,' / Ind Math Module') AS schedulename, a.fld_id AS scheduleid, a.fld_enddate, CONCAT(c.fld_mathmodule_name,' / Individual Math Module ') AS modulename, 6 AS schtype, b.fld_id as mapid,NULL AS fldorder FROM `itc_class_indassesment_master` AS a LEFT JOIN `itc_class_indassesment_student_mapping` AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_mathmodule_master AS c ON a.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND a.fld_moduletype='2' AND b.fld_student_id='".$uid."' ".$label2." AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."') AND b.fld_flag='1'		
                    UNION ALL       SELECT b.fld_module_id, CONCAT(a.fld_schedule_name,' / Dyad') AS schedulename, a.fld_id AS scheduleid, b.fld_enddate, CONCAT(c.fld_module_name,' / Rotation ',b.fld_rotation) AS modulename, 2 AS schtype, b.fld_id as mapid,NULL AS fldorder FROM `itc_class_dyad_schedulemaster` AS a LEFT JOIN `itc_class_dyad_schedulegriddet` AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id LEFT JOIN itc_class_dyad_schedule_studentmapping AS e ON (e.fld_schedule_id=b.fld_schedule_id AND e.fld_student_id='".$uid."') WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND (b.fld_student_id='".$uid."' OR b.fld_rotation='0') AND DATE(a.fld_startdate) <= DATE(NOW()) ".$label1." AND e.fld_flag='1' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."') AND b.fld_flag='1'		
                    UNION ALL       SELECT b.fld_module_id, CONCAT(a.fld_schedule_name,' / Triad') AS schedulename, a.fld_id AS scheduleid, b.fld_enddate, CONCAT(c.fld_module_name,' / Rotation ',b.fld_rotation) AS modulename, 3 AS schtype, b.fld_id as mapid,NULL AS fldorder FROM `itc_class_triad_schedulemaster` AS a LEFT JOIN `itc_class_triad_schedulegriddet` AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id LEFT JOIN itc_class_triad_schedule_studentmapping AS e ON (e.fld_schedule_id=b.fld_schedule_id AND e.fld_student_id='".$uid."') WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND (b.fld_student_id='".$uid."' OR b.fld_rotation='0') AND DATE(a.fld_startdate) <= DATE(NOW()) ".$label1." AND e.fld_flag='1' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."') AND b.fld_flag='1'		
                    
                    UNION ALL   SELECT a.fld_module_id, CONCAT(a.fld_schedule_name,' / Quest') AS schedulename, a.fld_id AS scheduleid, a.fld_enddate, CONCAT(c.fld_module_name,' / Individual Quest ') AS modulename, 7 AS schtype, b.fld_id as mapid,NULL AS fldorder FROM `itc_class_indassesment_master` AS a LEFT JOIN `itc_class_indassesment_student_mapping` AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND a.fld_moduletype='7' AND b.fld_student_id='".$uid."' ".$label2." AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."')		
                    UNION ALL   SELECT b.fld_module_id, CONCAT(a.fld_schedule_name,' / Module') AS schedulename, 
                                a.fld_id AS scheduleid, g.fld_enddate, CONCAT(c.fld_contentname,' / Rotation ',b.fld_rotation-1) AS modulename, 
                                8 AS schtype,b.fld_id as mapid,NULL AS fldorder 
                                FROM itc_class_rotation_schedule_mastertemp AS a
                                LEFT JOIN itc_class_rotation_schedulegriddet AS b ON a.fld_id=b.fld_schedule_id 
                                LEFT JOIN itc_class_rotation_scheduledate as g 
                                on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation
                                LEFT JOIN itc_customcontent_master as c ON b.fld_module_id=c.fld_id 
                                LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id
                                WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND b.fld_type='8'
                                AND b.fld_student_id='".$uid."' AND a.fld_moduletype='1' AND DATE(a.fld_startdate) <= DATE(NOW()) ".$label4." 
                                AND b.fld_flag='1'
                    UNION ALL  SELECT a.fld_module_id,CONCAT(a.fld_schedule_name, ' / Ind Custom') AS schedulename,a.fld_id AS scheduleid,a.fld_enddate,CONCAT(c.fld_contentname,' / Individual Custom ') AS modulename,
                                17 AS schtype,b.fld_id as mapid,NULL AS fldorder
                                FROM itc_class_indassesment_master AS a
                                LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id = b.fld_schedule_id
                                LEFT JOIN itc_customcontent_master as c ON a.fld_module_id = c.fld_id
                                LEFT JOIN itc_class_master AS d ON d.fld_id = a.fld_class_id
                                    WHERE a.fld_delstatus = '0' AND d.fld_delstatus = '0' AND d.fld_lock = '0' AND b.fld_student_id ='".$uid."'
                                    AND a.fld_moduletype = '17' ".$label2." AND b.fld_flag = '1' AND a.fld_flag = '1'
                    
                    UNION ALL   SELECT a.fld_exp_id AS fld_module_id, CONCAT(a.fld_schedule_name,' / Expedition') AS schedulename, a.fld_id AS scheduleid, a.fld_enddate, CONCAT(c.fld_exp_name,' / Individual Expedition ') AS modulename, 15 AS schtype, b.fld_id as mapid,NULL AS fldorder FROM `itc_class_indasexpedition_master` AS a LEFT JOIN `itc_class_exp_student_mapping` AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_exp_master AS c ON a.fld_exp_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND b.fld_flag='1' AND d.fld_delstatus='0' AND c.fld_delstatus='0' AND d.fld_lock='0' AND b.fld_student_id='".$uid."' ".$label2." AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."')
                    UNION ALL   SELECT a.fld_mis_id AS fld_module_id, CONCAT(a.fld_schedule_name,' / Mission') AS schedulename, a.fld_id AS scheduleid, a.fld_enddate, CONCAT(c.fld_mis_name,' / Individual Mission ') AS modulename, 18 AS schtype, b.fld_id as mapid,NULL AS fldorder FROM `itc_class_indasmission_master` AS a LEFT JOIN `itc_class_mission_student_mapping` AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_mission_master AS c ON a.fld_mis_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND b.fld_flag='1' AND d.fld_delstatus='0' AND d.fld_lock='0' AND b.fld_student_id='".$uid."' ".$label2." AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."')	
	            UNION ALL   SELECT b.fld_expedition_id, CONCAT(a.fld_schedule_name,' / Expedition') AS schedulename, a.fld_id AS scheduleid, g.fld_enddate, 
                                CONCAT(c.fld_exp_name,' / Rotation ',b.fld_rotation-1) AS modulename, 19 AS schtype, b.fld_id as mapid,NULL AS fldorder 
                                FROM `itc_class_rotation_expschedule_mastertemp` AS a LEFT JOIN `itc_class_rotation_expschedulegriddet` AS b ON a.fld_id=b.fld_schedule_id 
                                LEFT JOIN itc_class_rotation_expscheduledate as g on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation 
                                LEFT JOIN itc_exp_master AS c ON b.fld_expedition_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id 
                                WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND b.fld_student_id='".$uid."' 
                                AND DATE(a.fld_startdate) <= DATE(NOW()) ".$label4."  AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track 
                                WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."') 
                                AND b.fld_flag='1'  		
                                
                    UNION ALL   SELECT b.fld_module_id, CONCAT(a.fld_schedule_name,' / Expedition') AS schedulename, a.fld_id AS scheduleid, g.fld_enddate, 
                                CONCAT(c.fld_exp_name,' / Rotation ',b.fld_rotation-1) AS modulename, 20 AS schtype, b.fld_id as mapid,NULL AS fldorder 
                                FROM `itc_class_rotation_modexpschedule_mastertemp` AS a LEFT JOIN `itc_class_rotation_modexpschedulegriddet` AS b ON a.fld_id=b.fld_schedule_id 
                                LEFT JOIN itc_class_rotation_modexpscheduledate as g on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation 
                                LEFT JOIN itc_exp_master AS c ON b.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id 
                                WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND b.fld_student_id='".$uid."' 
                                AND DATE(a.fld_startdate) <= DATE(NOW()) ".$label4."  AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track 
                                WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."') 
                                AND b.fld_flag='1' AND b.fld_type='2'
                                
                    UNION ALL   SELECT b.fld_module_id, CONCAT(a.fld_schedule_name,' / Module ') AS schedulename, a.fld_id AS scheduleid, g.fld_enddate, 
                                CONCAT(c.fld_module_name,' / Rotation ',b.fld_rotation-1) AS modulename, 21 AS schtype, b.fld_id as mapid,NULL AS fldorder 
                                FROM `itc_class_rotation_modexpschedule_mastertemp` AS a LEFT JOIN `itc_class_rotation_modexpschedulegriddet` AS b ON a.fld_id=b.fld_schedule_id 
                                LEFT JOIN itc_class_rotation_modexpscheduledate as g on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation 
                                LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id 
                                WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND b.fld_student_id='".$uid."' 
                                AND DATE(a.fld_startdate) <= DATE(NOW()) ".$label4."  AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track 
                                WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."') 
                                AND b.fld_flag='1' AND b.fld_type='1'
                                
                    UNION ALL   SELECT b.fld_module_id, CONCAT(a.fld_schedule_name,' / Custom') AS schedulename, 
                                a.fld_id AS scheduleid, g.fld_enddate, CONCAT(c.fld_contentname,' / Rotation ',b.fld_rotation-1) AS modulename, 
                                22 AS schtype,b.fld_id as mapid,NULL AS fldorder 
                                FROM itc_class_rotation_modexpschedule_mastertemp AS a
                                LEFT JOIN itc_class_rotation_modexpschedulegriddet AS b ON a.fld_id=b.fld_schedule_id 
                                LEFT JOIN itc_class_rotation_modexpscheduledate as g 
                                on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation
                                LEFT JOIN itc_customcontent_master as c ON b.fld_module_id=c.fld_id 
                                LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id
                                WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND b.fld_type='8'
                                AND b.fld_student_id='".$uid."' AND DATE(a.fld_startdate) <= DATE(NOW()) ".$label4." 
                                AND b.fld_flag='1'
                                
                    UNION ALL   SELECT CONCAT(b.fld_mission_id,'-',b.fld_rotation) AS fld_module_id, CONCAT(a.fld_schedule_name,' / Mission') AS schedulename, a.fld_id AS scheduleid, g.fld_enddate, 
                                CONCAT(c.fld_mis_name,' / Rotation ',b.fld_rotation-1) AS modulename, 23 AS schtype, b.fld_id as mapid,NULL AS fldorder 
                                FROM `itc_class_rotation_mission_mastertemp` AS a LEFT JOIN `itc_class_rotation_mission_schedulegriddet` AS b ON a.fld_id=b.fld_schedule_id 
                                LEFT JOIN itc_class_rotation_missionscheduledate as g on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation 
                                LEFT JOIN itc_mission_master AS c ON b.fld_mission_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id 
                                WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND b.fld_student_id='".$uid."' 
                                AND DATE(a.fld_startdate) <= DATE(NOW()) ".$label4."  AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track 
                                WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."') 
                                AND b.fld_flag='1'
                    
                    UNION ALL		SELECT a.fld_max_attempts AS fld_module_id, a.fld_test_name AS schedulename, a.fld_id AS scheduleid, b.fld_end_date AS fld_enddate, b.fld_max_attempts AS modulename, 9 AS schtype, b.fld_id as mapid,NULL AS fldorder FROM itc_test_master AS a, itc_test_student_mapping AS b LEFT JOIN itc_class_master AS c ON b.fld_class_id=c.fld_id WHERE a.fld_id=b.`fld_test_id` AND c.fld_delstatus='0' AND c.fld_lock='0' AND b.fld_student_id ='".$uid."' AND b.fld_flag='1' AND a.fld_delstatus='0' ".$act." 		
                    UNION ALL		SELECT 0 AS fld_module_id, a.fld_activity_name AS schedulename, a.fld_id AS scheduleid, b.fld_end_date AS fld_enddate, 0 AS modulename, 10 AS schtype, b.fld_id as mapid,NULL AS fldorder FROM itc_activity_master AS a, itc_activity_student_mapping AS b LEFT JOIN itc_class_master AS c ON b.fld_class_id=c.fld_id WHERE a.fld_id=b.`fld_activity_id` AND c.fld_delstatus='0' AND c.fld_lock='0' AND b.fld_student_id ='".$uid."' AND b.fld_flag='1' AND a.fld_delstatus='0' ".$act." 
                    
                    UNION ALL      SELECT d.fld_lesson_id AS fld_module_id, a.fld_schedule_name AS schedulename, a.fld_id AS scheduleid, a.fld_end_date AS fld_enddate,e.fld_pd_name AS modulename, 16 AS schtype, b.fld_id as mapid,d.fld_order AS fldorder 
                                                FROM itc_class_pdschedule_master AS a 
                                                LEFT JOIN itc_class_pdschedule_student_mapping AS b ON a.fld_id=b.fld_pdschedule_id 
                                                LEFT JOIN itc_class_master AS c ON c.fld_id=a.fld_class_id LEFT JOIN itc_class_pdschedule_lesson_mapping as d on d.fld_pdschedule_id=b.fld_pdschedule_id LEFT JOIN itc_pd_master as e on e.fld_id=d.fld_lesson_id WHERE a.fld_delstatus='0' AND c.fld_delstatus='0' AND c.fld_lock='0' AND b.fld_flag='1' AND b.fld_student_id='".$uid."' ".$label." AND c.fld_delstatus='0' AND d.fld_flag='1' AND e.fld_delstatus='0'
        AND a.fld_start_date <= '".date("Y-m-d")."' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."')) AS w ORDER BY w.fld_enddate, w.fldorder");
                    
                    
                }
                
                else
                {
                   
                    $qrycountstudents = $ObjDB->SelectSingleValueInt("SELECT count(*) FROM(SELECT c.fld_id FROM itc_class_indasexpedition_master AS c 
                                                                        LEFT JOIN itc_class_exp_student_mapping AS b ON c.fld_id=b.fld_schedule_id 
                                                                        LEFT JOIN itc_exp_master AS a ON c.fld_exp_id=a.fld_id 
                                                                        LEFT JOIN itc_class_master AS d ON d.fld_id=c.fld_class_id 
                                                                        LEFT JOIN itc_license_track AS e ON c.fld_license_id=e.fld_license_id
                                                                        WHERE c.fld_delstatus='0' AND d.fld_delstatus='0' AND a.fld_delstatus='0' AND d.fld_lock='0' ".$label3." 
                                                                        AND DATE(c.fld_startdate)<=DATE(NOW()) AND DATE(c.fld_enddate) >= DATE(NOW())
                                                                        AND e.fld_school_id='".$schoolid."' AND e.fld_user_id='".$indid."' AND e.fld_start_date<='".date("Y-m-d")."' 
                                                                        AND e.fld_end_date >='".date("Y-m-d")."' AND b.fld_flag='1' 
                                                                        AND b.fld_student_id IN('".$uid."','".$uid1."') GROUP BY c.fld_id having count(c.fld_id) > 1) AS t");
                    
                    
                    
                    
                
                    $sqlquery = "SELECT w.* FROM (
                                                    SELECT a.`fld_schedule_id` AS scheduleid, a.`fld_module_id`, CONCAT(c.fld_schedule_name,' / Module') AS schedulename, 
                                                        g.fld_enddate, CONCAT(d.fld_module_name,' / Rotation ', a.fld_rotation-1) AS modulename, 1 AS schtype 
                                                    FROM itc_class_rotation_schedulegriddet a
                                                    JOIN itc_class_rotation_schedulegriddet b ON  b.`fld_student_id`='".$uid."' AND b.`fld_schedule_id`=a.`fld_schedule_id` 
                                                        AND b.`fld_module_id`=a.`fld_module_id` AND b.`fld_rotation`=a.`fld_rotation`
                                                        AND a.`fld_student_id`='".$uid1."' AND b.fld_flag='1' AND a.fld_flag='1'
                                                    LEFT JOIN itc_class_rotation_scheduledate as g on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation 
                                                    LEFT JOIN itc_class_rotation_schedule_mastertemp c ON c.fld_id=b.fld_schedule_id
                                                    LEFT JOIN itc_module_master d ON a.fld_module_id=d.fld_id
                                                    LEFT JOIN itc_class_master e ON e.fld_id=a.fld_class_id
                                                    LEFT JOIN itc_license_track f ON f.`fld_license_id`=c.`fld_license_id`
                                                    WHERE c.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND e.fld_lock='0' AND b.fld_type='1'
                                                        AND DATE(c.fld_startdate) <= DATE(NOW()) ".$label4." AND c.fld_moduletype='1' AND f.fld_school_id='".$schoolid."' 
                                                        AND f.fld_user_id='".$indid."' AND f.fld_start_date<='".date("Y-m-d")."' AND f.fld_end_date >='".date("Y-m-d")."'
                                                    GROUP BY a.`fld_rotation`, a.`fld_module_id`,  a.`fld_schedule_id`
                                                            UNION ALL 
                                                    SELECT a.`fld_schedule_id` AS scheduleid, a.`fld_module_id`, CONCAT(c.fld_schedule_name,' / Math Module') AS schedulename, 
                                                        g.fld_enddate, CONCAT(d.fld_mathmodule_name,' / Rotation ', a.fld_rotation-1) AS modulename, 4 AS schtype 
                                                    FROM itc_class_rotation_schedulegriddet a
                                                    JOIN itc_class_rotation_schedulegriddet b ON  b.`fld_student_id`='".$uid."' AND b.`fld_schedule_id`=a.`fld_schedule_id` 
                                                        AND b.`fld_module_id`=a.`fld_module_id` AND b.`fld_rotation`=a.`fld_rotation`
                                                        AND a.`fld_student_id`='".$uid1."' AND b.fld_flag='1' AND a.fld_flag='1'
                                                    LEFT JOIN itc_class_rotation_scheduledate as g on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation 
                                                    LEFT JOIN itc_class_rotation_schedule_mastertemp c ON c.fld_id=b.fld_schedule_id
                                                    LEFT JOIN itc_mathmodule_master d ON a.fld_module_id=d.fld_id
                                                    LEFT JOIN itc_class_master e ON e.fld_id=a.fld_class_id
                                                    LEFT JOIN itc_license_track f ON f.`fld_license_id`=c.`fld_license_id`
                                                    WHERE c.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND e.fld_lock='0' AND b.fld_type='2'
                                                        AND DATE(c.fld_startdate) <= DATE(NOW()) ".$label4." AND c.fld_moduletype='2' AND f.fld_school_id='".$schoolid."'
                                                        AND f.fld_user_id='".$indid."' AND f.fld_start_date<='".date("Y-m-d")."' AND f.fld_end_date >='".date("Y-m-d")."'
                                                    GROUP BY a.`fld_rotation`, a.`fld_module_id`,  a.`fld_schedule_id`
                                                            UNION ALL
                                                    SELECT a.`fld_schedule_id` AS scheduleid, a.`fld_module_id`, CONCAT(c.fld_schedule_name,' / Dyad') AS schedulename, 
                                                        a.fld_enddate, CONCAT(d.fld_module_name,' / Rotation ', a.fld_rotation) AS modulename, 2 AS schtype 
                                                    FROM itc_class_dyad_schedulegriddet a
                                                    JOIN itc_class_dyad_schedulegriddet b ON (b.fld_student_id='".$uid."' OR b.fld_rotation='0') 
                                                        AND b.`fld_schedule_id`=a.`fld_schedule_id` AND b.`fld_module_id`=a.`fld_module_id` AND b.`fld_rotation`=a.`fld_rotation`
                                                        AND (a.`fld_student_id`='".$uid1."' OR a.fld_rotation='0') AND b.fld_flag='1' AND a.fld_flag='1'
                                                    LEFT JOIN itc_class_dyad_schedulemaster c ON c.fld_id=b.fld_schedule_id
                                                    LEFT JOIN itc_module_master d ON a.fld_module_id=d.fld_id
                                                    LEFT JOIN itc_class_master e ON e.fld_id=a.fld_class_id
                                                    LEFT JOIN itc_license_track f ON f.`fld_license_id`=c.`fld_license_id`
                                                    LEFT JOIN itc_class_dyad_schedule_studentmapping AS g ON (g.fld_schedule_id=a.fld_schedule_id AND g.fld_student_id='".$uid."')
                                                    JOIN itc_class_dyad_schedule_studentmapping AS h ON (h.fld_schedule_id=a.fld_schedule_id AND h.fld_student_id='".$uid1."')
                                                    WHERE c.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND e.fld_lock='0'
                                                        AND g.fld_flag='1' AND h.fld_flag='1'
                                                        AND DATE(c.fld_startdate) <= DATE(NOW()) ".$label1." AND f.fld_school_id='".$schoolid."' AND f.fld_user_id='".$indid."' 
                                                        AND f.fld_start_date<='".date("Y-m-d")."' AND f.fld_end_date >='".date("Y-m-d")."'
                                                    GROUP BY a.`fld_rotation`, a.`fld_module_id`,  a.`fld_schedule_id`
                                                            UNION ALL
                                                    SELECT a.`fld_schedule_id` AS scheduleid, a.`fld_module_id`, CONCAT(c.fld_schedule_name,' / Triad') AS schedulename,
                                                        a.fld_enddate, CONCAT(d.fld_module_name,' / Rotation ', a.fld_rotation) AS modulename, 3 AS schtype 
                                                    FROM itc_class_triad_schedulegriddet a
                                                    JOIN itc_class_triad_schedulegriddet b ON (b.fld_student_id='".$uid."' OR b.fld_rotation='0') 
                                                        AND b.`fld_schedule_id`=a.`fld_schedule_id` AND b.`fld_module_id`=a.`fld_module_id` AND b.`fld_rotation`=a.`fld_rotation`
                                                        AND (a.`fld_student_id`='".$uid1."' OR a.fld_rotation='0') AND b.fld_flag='1' AND a.fld_flag='1'
                                                    LEFT JOIN itc_class_triad_schedulemaster c ON c.fld_id=b.fld_schedule_id
                                                    LEFT JOIN itc_module_master d ON a.fld_module_id=d.fld_id
                                                    LEFT JOIN itc_class_master e ON e.fld_id=a.fld_class_id
                                                    LEFT JOIN itc_license_track f ON f.`fld_license_id`=c.`fld_license_id`
                                                    LEFT JOIN itc_class_triad_schedule_studentmapping AS g ON (g.fld_schedule_id=a.fld_schedule_id AND g.fld_student_id='".$uid."')
                                                    JOIN itc_class_triad_schedule_studentmapping AS h ON (h.fld_schedule_id=a.fld_schedule_id AND h.fld_student_id='".$uid1."')
                                                    WHERE c.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND e.fld_lock='0' 
                                                        AND g.fld_flag='1' AND h.fld_flag='1'
                                                        AND DATE(c.fld_startdate) <= DATE(NOW()) ".$label1." AND f.fld_school_id='".$schoolid."' AND f.fld_user_id='".$indid."' 
                                                        AND f.fld_start_date<='".date("Y-m-d")."' AND f.fld_end_date >='".date("Y-m-d")."'
                                                    GROUP BY a.`fld_rotation`, a.`fld_module_id`,  a.`fld_schedule_id`
                                                            UNION ALL
                                                    SELECT a.`fld_schedule_id` AS scheduleid, c.`fld_module_id`, CONCAT(c.fld_schedule_name,' / Ind Module') AS schedulename,
                                                        c.fld_enddate, CONCAT(d.fld_module_name,' / Individual Module') AS modulename, 5 AS schtype 
                                                    FROM itc_class_indassesment_student_mapping a
                                                    JOIN itc_class_indassesment_student_mapping b ON  b.`fld_student_id`='".$uid."' AND b.`fld_schedule_id`=a.`fld_schedule_id`
                                                        AND a.`fld_student_id`='".$uid1."' AND b.fld_flag='1' AND a.fld_flag='1'
                                                    LEFT JOIN itc_class_indassesment_master c ON c.fld_id=b.fld_schedule_id
                                                    LEFT JOIN itc_module_master d ON c.fld_module_id=d.fld_id
                                                    LEFT JOIN itc_class_master e ON e.fld_id=c.fld_class_id
                                                    LEFT JOIN itc_license_track f ON f.`fld_license_id`=c.`fld_license_id`
                                                    WHERE c.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND e.fld_lock='0'
                                                        ".$label3." AND c.fld_moduletype='1' AND f.fld_school_id='".$schoolid."' AND f.fld_user_id='".$indid."' 
                                                        AND f.fld_start_date<='".date("Y-m-d")."' AND f.fld_end_date >='".date("Y-m-d")."'
                                                    GROUP BY a.`fld_schedule_id`
                                                            UNION ALL
                                                    SELECT a.`fld_schedule_id` AS scheduleid, c.`fld_module_id`, CONCAT(c.fld_schedule_name,' / Ind MathModule') AS schedulename, 
                                                        c.fld_enddate, CONCAT(d.fld_mathmodule_name,' / Individual MathModule') AS modulename, 6 AS schtype 
                                                    FROM itc_class_indassesment_student_mapping a
                                                    JOIN itc_class_indassesment_student_mapping b ON  b.`fld_student_id`='".$uid."' AND b.`fld_schedule_id`=a.`fld_schedule_id`
                                                        AND a.`fld_student_id`='".$uid1."' AND b.fld_flag='1' AND a.fld_flag='1'
                                                    LEFT JOIN itc_class_indassesment_master c ON c.fld_id=b.fld_schedule_id
                                                    LEFT JOIN itc_mathmodule_master d ON c.fld_module_id=d.fld_id
                                                    LEFT JOIN itc_class_master e ON e.fld_id=c.fld_class_id
                                                    LEFT JOIN itc_license_track f ON f.`fld_license_id`=c.`fld_license_id`
                                                    WHERE c.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND e.fld_lock='0'
                                                        ".$label3." AND c.fld_moduletype='2' AND f.fld_school_id='".$schoolid."' AND f.fld_user_id='".$indid."' 
                                                        AND f.fld_start_date<='".date("Y-m-d")."' AND f.fld_end_date >='".date("Y-m-d")."'
                                                    GROUP BY a.`fld_schedule_id`
                                                            UNION ALL
                                                    SELECT a.`fld_schedule_id` AS scheduleid, c.`fld_module_id`, CONCAT(c.fld_schedule_name,' / Ind Quest') AS schedulename,
                                                        c.fld_enddate, CONCAT(d.fld_module_name,' / Individual Quest') AS modulename, 7 AS schtype 
                                                    FROM itc_class_indassesment_student_mapping a
                                                    JOIN itc_class_indassesment_student_mapping b ON  b.`fld_student_id`='".$uid."' AND b.`fld_schedule_id`=a.`fld_schedule_id`
                                                        AND a.`fld_student_id`='".$uid1."' AND b.fld_flag='1' AND a.fld_flag='1'
                                                    LEFT JOIN itc_class_indassesment_master c ON c.fld_id=b.fld_schedule_id
                                                    LEFT JOIN itc_module_master d ON c.fld_module_id=d.fld_id
                                                    LEFT JOIN itc_class_master e ON e.fld_id=c.fld_class_id
                                                    LEFT JOIN itc_license_track f ON f.`fld_license_id`=c.`fld_license_id`
                                                    WHERE c.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND e.fld_lock='0'
                                                        ".$label3." AND c.fld_moduletype='7' AND f.fld_school_id='".$schoolid."' AND f.fld_user_id='".$indid."' 
                                                        AND f.fld_start_date<='".date("Y-m-d")."' AND f.fld_end_date >='".date("Y-m-d")."'
                                                    GROUP BY a.`fld_schedule_id`
															
                                                            UNION ALL
                                                    SELECT a.`fld_schedule_id` AS scheduleid, a.`fld_module_id`, CONCAT(c.fld_schedule_name,' / Custom Module') AS schedulename, 
                                                        g.fld_enddate, CONCAT(d.fld_contentname,' / Rotation ', a.fld_rotation-1) AS modulename, 8 AS schtype 
                                                    FROM itc_class_rotation_schedulegriddet a
                                                    JOIN itc_class_rotation_schedulegriddet b ON  b.`fld_student_id`='".$uid."' AND b.`fld_schedule_id`=a.`fld_schedule_id` 
                                                        AND b.`fld_module_id`=a.`fld_module_id` AND b.`fld_rotation`=a.`fld_rotation`
                                                        AND a.`fld_student_id`='".$uid1."' AND b.fld_flag='1' AND a.fld_flag='1'
                                                    LEFT JOIN itc_class_rotation_scheduledate as g on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation 
                                                    LEFT JOIN itc_class_rotation_schedule_mastertemp c ON c.fld_id=b.fld_schedule_id
                                                    LEFT JOIN itc_customcontent_master d ON a.fld_module_id=d.fld_id
                                                    LEFT JOIN itc_class_master e ON e.fld_id=a.fld_class_id
                                                    LEFT JOIN itc_license_track f ON f.`fld_license_id`=c.`fld_license_id`
                                                    WHERE c.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND e.fld_lock='0' AND b.fld_type='8'
                                                        AND DATE(c.fld_startdate) <= DATE(NOW()) ".$label4." AND c.fld_moduletype='1' AND f.fld_school_id='".$schoolid."' 
                                                        AND f.fld_user_id='".$indid."' AND f.fld_start_date<='".date("Y-m-d")."' AND f.fld_end_date >='".date("Y-m-d")."'
                                                    GROUP BY a.`fld_rotation`, a.`fld_module_id`,  a.`fld_schedule_id`	

                                                   UNION ALL 
                                                        SELECT a.`fld_schedule_id` AS scheduleid, b.`fld_module_id`, CONCAT(b.fld_schedule_name,' / Custom Module') AS schedulename, 
                                                            b.fld_enddate, CONCAT(c.fld_contentname,' / Individual Custom ') AS modulename, 17 AS schtype 
                                                            FROM itc_class_indassesment_student_mapping AS a
                                                            JOIN itc_class_indassesment_student_mapping AS e ON a.`fld_student_id`='".$uid."' AND e.`fld_schedule_id` = a.`fld_schedule_id`
                                                            AND a.`fld_student_id`='".$uid1."' AND e.fld_flag='1' AND a.fld_flag='1'

                                                            LEFT JOIN itc_class_indassesment_master AS b ON b.fld_id=a.fld_schedule_id AND b.fld_id=e.fld_schedule_id
                                                            LEFT JOIN itc_customcontent_master as c ON b.fld_module_id = c.fld_id
                                                            LEFT JOIN itc_class_master AS d ON d.fld_id = b.fld_class_id
                                                            WHERE b.fld_delstatus = '0' AND d.fld_delstatus = '0' AND d.fld_lock = '0'
                                                                    AND b.fld_moduletype = '17'
                                                                    AND DATE(b.fld_startdate) <= DATE(NOW())
                                                                            ".$label1."
                                                                    AND b.fld_flag = '1' AND a.fld_flag = '1'
                                                                    
                                                   UNION ALL
                                                        SELECT 
                                                a.`fld_schedule_id` AS scheduleid,
                                                a.`fld_expedition_id`,
                                                CONCAT(c.fld_schedule_name, ' / Expedition') AS schedulename,
                                                g.fld_enddate,
                                                CONCAT(d.fld_exp_name,
                                                        ' / Rotation ',
                                                        a.fld_rotation - 1) AS modulename,
                                                19 AS schtype
                                            FROM
                                                itc_class_rotation_expschedulegriddet a
                                                    JOIN
                                                itc_class_rotation_expschedulegriddet b ON b.`fld_student_id` = '".$uid."'
                                                    AND b.`fld_schedule_id` = a.`fld_schedule_id`
                                                    AND b.`fld_expedition_id` = a.`fld_expedition_id`
                                                    AND b.`fld_rotation` = a.`fld_rotation`
                                                    AND a.`fld_student_id` = '".$uid1."'
                                                    AND b.fld_flag = '1'
                                                    AND a.fld_flag = '1'
                                                    LEFT JOIN
                                                itc_class_rotation_expscheduledate AS g ON b.fld_schedule_id = g.fld_schedule_id
                                                    AND b.fld_rotation = g.fld_rotation
                                                    LEFT JOIN
                                                itc_class_rotation_expschedule_mastertemp c ON c.fld_id = b.fld_schedule_id
                                                    LEFT JOIN
                                                itc_exp_master d ON a.fld_expedition_id = d.fld_id
                                                    LEFT JOIN
                                                itc_class_master e ON e.fld_id = a.fld_class_id
                                                    LEFT JOIN
                                                itc_license_track f ON f.`fld_license_id` = c.`fld_license_id`
                                            WHERE
                                                c.fld_delstatus = '0'
                                                    AND d.fld_delstatus = '0'
                                                    AND e.fld_delstatus = '0'
                                                    AND e.fld_lock = '0'
                                                    AND DATE(c.fld_startdate) <= DATE(NOW()) ".$label4."
                                                    AND f.fld_school_id = '".$schoolid."'
                                                    AND f.fld_user_id = '".$indid."'
                                                    AND f.fld_start_date <= '".date("Y-m-d")."'
                                                    AND f.fld_end_date >= '".date("Y-m-d")."'
                                            GROUP BY a.fld_rotation , a.fld_expedition_id , a.fld_schedule_id
                                            
                                                  UNION ALL
                                                        SELECT 
                                                a.`fld_schedule_id` AS scheduleid,
                                                a.`fld_module_id`,
                                                CONCAT(c.fld_schedule_name, ' / Expedition') AS schedulename,
                                                g.fld_enddate,
                                                CONCAT(d.fld_exp_name,
                                                        ' / Rotation ',
                                                        a.fld_rotation - 1) AS modulename,
                                                20 AS schtype
                                            FROM
                                                itc_class_rotation_modexpschedulegriddet a
                                                    JOIN
                                                itc_class_rotation_modexpschedulegriddet b ON b.`fld_student_id` = '".$uid."'
                                                    AND b.`fld_schedule_id` = a.`fld_schedule_id`
                                                    AND b.`fld_module_id` = a.`fld_module_id`
                                                    AND b.`fld_rotation` = a.`fld_rotation`
                                                    AND a.`fld_student_id` = '".$uid1."'
                                                    AND b.fld_flag = '1'
                                                    AND a.fld_flag = '1'
                                                    LEFT JOIN
                                                itc_class_rotation_modexpscheduledate AS g ON b.fld_schedule_id = g.fld_schedule_id
                                                    AND b.fld_rotation = g.fld_rotation
                                                    LEFT JOIN
                                                itc_class_rotation_modexpschedule_mastertemp c ON c.fld_id = b.fld_schedule_id
                                                    LEFT JOIN
                                                itc_exp_master d ON a.fld_module_id = d.fld_id
                                                    LEFT JOIN
                                                itc_class_master e ON e.fld_id = a.fld_class_id
                                                    LEFT JOIN
                                                itc_license_track f ON f.`fld_license_id` = c.`fld_license_id`
                                            WHERE
                                                c.fld_delstatus = '0'
                                                    AND d.fld_delstatus = '0'
                                                    AND e.fld_delstatus = '0'
                                                    AND e.fld_lock = '0'
                                                    AND b.fld_type='2'
                                                    AND DATE(c.fld_startdate) <= DATE(NOW()) ".$label4."
                                                    AND f.fld_school_id = '".$schoolid."'
                                                    AND f.fld_user_id = '".$indid."'
                                                    AND f.fld_start_date <= '".date("Y-m-d")."'
                                                    AND f.fld_end_date >= '".date("Y-m-d")."'
                                            GROUP BY a.fld_rotation , a.fld_module_id , a.fld_schedule_id
                                            
                                            UNION ALL 
                                            SELECT a.`fld_schedule_id` AS scheduleid, a.`fld_module_id`, CONCAT(c.fld_schedule_name,' / Module') AS schedulename, 
                                                        g.fld_enddate, CONCAT(d.fld_module_name,' / Rotation ', a.fld_rotation-1) AS modulename, 21 AS schtype 
                                                    FROM itc_class_rotation_modexpschedulegriddet a
                                                    JOIN itc_class_rotation_modexpschedulegriddet b ON  b.`fld_student_id`='".$uid."' AND b.`fld_schedule_id`=a.`fld_schedule_id` 
                                                        AND b.`fld_module_id`=a.`fld_module_id` AND b.`fld_rotation`=a.`fld_rotation`
                                                        AND a.`fld_student_id`='".$uid1."' AND b.fld_flag='1' AND a.fld_flag='1'
                                                    LEFT JOIN itc_class_rotation_modexpscheduledate as g on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation 
                                                    LEFT JOIN itc_class_rotation_modexpschedule_mastertemp c ON c.fld_id=b.fld_schedule_id
                                                    LEFT JOIN itc_module_master d ON a.fld_module_id=d.fld_id
                                                    LEFT JOIN itc_class_master e ON e.fld_id=a.fld_class_id
                                                    LEFT JOIN itc_license_track f ON f.`fld_license_id`=c.`fld_license_id`
                                                    WHERE c.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND e.fld_lock='0' AND b.fld_type='1'
                                                        AND DATE(c.fld_startdate) <= DATE(NOW()) ".$label4." AND f.fld_school_id='".$schoolid."' 
                                                        AND f.fld_user_id='".$indid."' AND f.fld_start_date<='".date("Y-m-d")."' AND f.fld_end_date >='".date("Y-m-d")."'
                                                    GROUP BY a.`fld_rotation`, a.`fld_module_id`,  a.`fld_schedule_id`
                                                    
                                                    UNION ALL
                                                    SELECT a.`fld_schedule_id` AS scheduleid, a.`fld_module_id`, CONCAT(c.fld_schedule_name,' / Custom Module') AS schedulename, 
                                                        g.fld_enddate, CONCAT(d.fld_contentname,' / Rotation ', a.fld_rotation-1) AS modulename, 22 AS schtype 
                                                    FROM itc_class_rotation_modexpschedulegriddet a
                                                    JOIN itc_class_rotation_modexpschedulegriddet b ON  b.`fld_student_id`='".$uid."' AND b.`fld_schedule_id`=a.`fld_schedule_id` 
                                                        AND b.`fld_module_id`=a.`fld_module_id` AND b.`fld_rotation`=a.`fld_rotation`
                                                        AND a.`fld_student_id`='".$uid1."' AND b.fld_flag='1' AND a.fld_flag='1'
                                                    LEFT JOIN itc_class_rotation_modexpscheduledate as g on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation 
                                                    LEFT JOIN itc_class_rotation_modexpschedule_mastertemp c ON c.fld_id=b.fld_schedule_id
                                                    LEFT JOIN itc_customcontent_master d ON a.fld_module_id=d.fld_id
                                                    LEFT JOIN itc_class_master e ON e.fld_id=a.fld_class_id
                                                    LEFT JOIN itc_license_track f ON f.`fld_license_id`=c.`fld_license_id`
                                                    WHERE c.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND e.fld_lock='0' AND b.fld_type='8'
                                                        AND DATE(c.fld_startdate) <= DATE(NOW()) ".$label4." AND f.fld_school_id='".$schoolid."' 
                                                        AND f.fld_user_id='".$indid."' AND f.fld_start_date<='".date("Y-m-d")."' AND f.fld_end_date >='".date("Y-m-d")."'
                                                    GROUP BY a.`fld_rotation`, a.`fld_module_id`,  a.`fld_schedule_id`
                                                    
                                                    UNION ALL 
                                                    SELECT 
a.fld_id AS scheduleid, 
a.fld_mis_id AS fld_module_id,
CONCAT(a.fld_schedule_name,' / Ind Mission') AS schedulename,  
 a.fld_enddate,  
CONCAT(c.fld_mis_name,' / Individual Mission ') AS modulename, 
18 AS schtype    
																FROM itc_class_indasmission_master AS a 
																LEFT JOIN itc_class_mission_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
																LEFT JOIN itc_mission_master AS c ON a.fld_mis_id=c.fld_id 
																LEFT JOIN itc_class_master AS d ON d.fld_id=a.fld_class_id 
																LEFT JOIN itc_license_track AS e ON a.fld_license_id=e.fld_license_id
																WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND c.fld_delstatus='0' AND d.fld_lock='0' 
																	AND DATE(a.fld_startdate)<=DATE(NOW()) AND a.fld_enddate>='".date("Y-m-d")."' 
																	AND e.fld_school_id='".$schoolid."' 
																	AND e.fld_start_date<='".date("Y-m-d")."' AND e.fld_end_date >='".date("Y-m-d")."' 
																	AND b.fld_flag='1' AND a.fld_startdate<='".date("Y-m-d")."'  
                                                                    AND (b.fld_student_id = '".$uid."' OR b.fld_student_id = '".$uid1."') GROUP BY scheduleid ";
                                                   
                        if($qrycountstudents != 0)
                    
                        {
                                $sqlquery12 = "SELECT c.fld_id AS scheduleid, c.fld_exp_id AS fld_module_id, CONCAT(c.fld_schedule_name,' / Ind Expedition') AS schedulename, 
                                c.fld_enddate,CONCAT(a.fld_exp_name,' / Individual Expedition') AS modulename,
                                15 AS schtype FROM 
                                itc_class_indasexpedition_master AS c LEFT JOIN 
                                itc_class_exp_student_mapping AS b 
                                ON c.fld_id=b.fld_schedule_id 
                                LEFT JOIN itc_exp_master AS a 
                                ON c.fld_exp_id=a.fld_id LEFT JOIN itc_class_master AS d 
                                ON d.fld_id=c.fld_class_id 
                                LEFT JOIN itc_license_track AS e ON c.fld_license_id=e.fld_license_id
                                WHERE c.fld_delstatus='0' AND d.fld_delstatus='0' AND a.fld_delstatus='0'
                                AND d.fld_lock='0' ".$label3." AND DATE(c.fld_startdate) <= DATE(NOW()) 
                                AND DATE(c.fld_enddate) >= DATE(NOW()) AND e.fld_school_id='".$schoolid."' 
                                AND e.fld_user_id='".$indid."' AND
                                e.fld_start_date<='".date("Y-m-d")."' AND e.fld_end_date >='".date("Y-m-d")."'
                                AND b.fld_flag='1'
                                AND b.fld_student_id IN('".$uid."','".$uid1."') GROUP BY scheduleid having count(scheduleid) > 1";

                                $commonqry=$sqlquery." UNION ALL ".$sqlquery12." ) AS w ORDER BY w.fld_enddate";


                }
                        else
                        {
                                 $commonqry=$sqlquery.") AS w ORDER BY w.fld_enddate";
                        }
                        $qry = $ObjDB->QueryObject($commonqry);
                }
				?>
                <div style="max-height:400px;width:100%;" id="tablecontents4" >
                    <table style="margin-bottom:0px;" class='table table-hover table-striped table-bordered bordertopradiusremove'>
                        <tbody>
                       	<?php
                        if($qry->num_rows>0){
							while($row = $qry->fetch_assoc())
							{
								extract($row);
								if($schtype==2 || $schtype==3)
									$typeids = 1;
								else if($schtype==1 || $schtype==4 || $schtype==5 || $schtype==6 || $schtype==21)
									$typeids = 2;
									
								if($schtype==0)
								{
									$sid = $scheduleid;
									$currentqry = $ObjDB->QueryObject("SELECT fld_id AS maxid, fld_lesson_id AS lessonid, fld_type AS type, fld_status AS status 
																	FROM itc_assignment_sigmath_master 
																	WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$uid."' AND fld_status=0 AND fld_delstatus='0' 
																	ORDER BY fld_id DESC 
																	LIMIT 0,1");
									$flag=0;
									$completed=0;
									if($currentqry->num_rows>0){
										$current_res = $currentqry->fetch_assoc();
										extract($current_res);
										
										//check is the lesson is abailable or not 
										$chklesson = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) 
																				FROM itc_class_sigmath_lesson_mapping AS a 
																				LEFT JOIN itc_ipl_master AS b ON a.fld_lesson_id=b.fld_id 
																				WHERE a.fld_sigmath_id='".$sid."' AND a.fld_lesson_id='".$lessonid."' AND a.fld_flag='1' 
																					AND b.fld_access='1' AND b.fld_delstatus='0'");		
														
										if($chklesson>0){
											$flag=1;
											if($type==1){
												$function = "fn_diagnosticstart(".$sid.",".$lessonid.",1)";
											}
											else if($type==2){
												$orientationid = $ObjDB->SelectSingleValueInt("SELECT fld_id 
																							FROM itc_ipl_master 
																							WHERE fld_lesson_type='2' AND fld_delstatus='0' AND fld_id='".$lessonid."'");
												if($orientationid==0 || $orientationid=='')
													$function = "fn_lessonplay(".$sid.",".$lessonid.",".$maxid.")";
												else
													$function = "fn_lessonplay(".$sid.",".$lessonid.",".$maxid.",1)";
											}
											else if($type==3){
												$function = "fn_startmastery1(".$sid.",".$lessonid.",".$maxid.")";
											}
											else if($type==4){
												$function = "fn_startmastery2(".$sid.",".$lessonid.",".$maxid.")";
											}
											else if($type==6){
												$function = "fn_diagfinish(".$sid.",".$lessonid.",0,".$maxid.")";
											}
											else if($type==5 or $type==7){
												$function = "fn_mastery1finish(".$sid.",".$lessonid.",0,".$maxid.")";
											}
											else if($type==8){
												$function = "fn_mastery2finish(".$sid.",".$lessonid.",0,".$maxid.")";
											}
										}
									}									
									
									if($flag!=1){
										//get the lesson with out previously attend										
										$orientationid = $ObjDB->SelectSingleValueInt("SELECT fld_id 
																						FROM itc_ipl_master 
																						WHERE fld_lesson_type='2' AND fld_delstatus='0'");	
																						
										if($orientationid=='') $orientationid=0;																																							
										$lessonid = $ObjDB->SelectSingleValueInt("SELECT fld_lesson_id FROM itc_class_sigmath_lesson_mapping 
												 WHERE fld_sigmath_id='".$sid."' AND fld_flag='1' AND ".$orientationid." NOT IN(SELECT fld_lesson_id FROM itc_assignment_sigmath_master 
												 WHERE (fld_status=1 or fld_status=2) AND fld_student_id='".$uid."' AND fld_schedule_id='".$sid."' AND fld_test_type='1' 
												 AND fld_delstatus='0' ) AND fld_lesson_id='".$orientationid."'");
										
										//$function = "fn_lessonplay(".$sid.",".$lessonid.",1,1)";	
										$function = "fn_diagnosticstart(".$sid.",".$lessonid.",1)";	
										if($lessonid==0 || $lessonid=='')
										{
											$lessonid = $ObjDB->SelectSingleValueInt("SELECT fld_lesson_id 
													 FROM itc_class_sigmath_lesson_mapping 
													 WHERE fld_sigmath_id='".$sid."' AND fld_flag='1' AND fld_lesson_id NOT IN(SELECT fld_lesson_id FROM itc_assignment_sigmath_master 
													 WHERE (fld_status=1 or fld_status=2) AND fld_student_id='".$uid."' AND fld_schedule_id='".$sid."' AND fld_test_type='1' 
													 AND fld_delstatus='0')
													 ORDER BY fld_order LIMIT 0,1");
										
											if($lessonid==0){
												$completed=1;	
																	
											}
											$function = "fn_diagnosticstart(".$sid.",".$lessonid.",1)";
										}
									}
									$modulename = $ObjDB->SelectSingleValue("SELECT fld_ipl_name FROM itc_ipl_master WHERE fld_id='".$lessonid."'");
									if($completed==1)
										$modulename = "IPL Assignment - Completed";		
									$call = "removesections('#home');showpages('assignment-sigmath-test','assignment/sigmath/assignment-sigmath-test.php?id=".$sid."~".$lessonid."~1~1')";
								}
                                                                else if($schtype==8)
								{
									$values = $scheduleid."~".$fld_module_id."~".$schtype;
									$call = "removesections('#assignment'); showpageswithpostmethod('assignment-science-sessions','assignment/science/assignment-science-sessions.php','id=".$values."')";
								}
								else if($schtype==9)
								{
									if($fld_module_id>$modulename)
									{
										$call = "removesections('#assignment'); showpages('assignment-assignmentengine-gototest','assignment/assignmentengine/assignment-assignmentengine-gototest.php?id=".$scheduleid."~".$mapid."')";
										$modulename = 'Assessment';
									}
									else
									{
										$completed=1;
										$modulename = 'Assessment - Completed';
									}
									
								}
								else if($schtype==10)
								{
									$modulename = 'Activity';
									$call = "removesections('#assignment'); showpages('library-activities-viewactivity','library/activities/library-activities-viewactivity.php?id=".$scheduleid."~".$mapid."')";
								}
								else if($schtype==15 or $schtype==19 or $schtype==20)
                                {
                                    $values = $scheduleid."~".$fld_module_id."~".$schtype;
                                    $expuiid = $ObjDB->SelectSingleValueInt("SELECT fld_ui_id FROM itc_exp_master WHERE fld_id='".$fld_module_id."'");
                                    if($expuiid==1)
                                            $expuiname = "preview";
                                    else
                                            $expuiname = "show";
                                    $call = "removesections('#assignment'); showpageswithpostmethod('assignment-expedition-".$expuiname."','assignment/expedition/assignment-expedition-".$expuiname.".php','id=".$values."')";
                                }
                                                                else if($schtype==18)
                                                                {
                                                                    $values = $scheduleid."~".$fld_module_id."~".$schtype;
                                                                    $misuiid = $ObjDB->SelectSingleValueInt("SELECT fld_ui_id FROM itc_mission_master WHERE fld_id='".$fld_module_id."'");
                                                                    if($misuiid==1)
                                                                            $misuiname = "preview";
                                                                    else
                                                                            $misuiname = "show";
                                                                    $call = "removesections('#assignment'); showpageswithpostmethod('assignment-mission-".$misuiname."','assignment/mission/assignment-mission-".$misuiname.".php','id=".$values."')";
                                                                }
                                                                else if($schtype==23)
                                                                {
                                                                    
                                                                    $misuiid = $ObjDB->SelectSingleValueInt("SELECT fld_ui_id FROM itc_mission_master WHERE fld_id='".$fld_module_id."'");
                                                                    if($misuiid==1)
                                                                            $misuiname = "preview";
                                                                    else
                                                                            $misuiname = "show";
                                                                    
                                                                    $values = $scheduleid."~".$fld_module_id."~".$schtype."~".$misuiname;
                                                                    
                                                                    $call = "removesections('#assignment');showpageswithpostmethod('assignment-missionassignment','assignment/mission/assignment-missionassignment.php','id=".$values."')";
                                                                }
                                                                else if($schtype==16)//for PD content
								{ 
                                                                    
                                                                        $pdfoldername = $ObjDB->SelectSingleValue("SELECT fld_zip_name FROM itc_pd_version_track WHERE fld_pd_id='".$fld_module_id."' AND fld_delstatus='0'");
                                                                        $pdfoldername1=basename($pdfoldername,".zip");//get file name without file extension 
                                                                        $filename=  explode('_', $pdfoldername1);
                                                                        $x=1;
                                                                        $final=$pdfoldername1.",".$x.",".$filename[0];
                                                                    
                                                                    $call = "showstudentlessonpd('".$pdfoldername1."',".$fld_module_id.",'".$filename[0]."','".$scheduleid."')";
                                                                    $modulename.=" / PD Schedule";
                                                                }
                                                                else if($schtype==17)
								{
                                                                   
									$values = $scheduleid."~".$fld_module_id."~".$schtype;
									$call = "removesections('#assignment'); showpageswithpostmethod('assignment-science-sessions','assignment/science/assignment-science-sessions.php','id=".$values."')";
								}
								else
								{
									$values = $scheduleid."~".$fld_module_id."~".$schtype;
									$call = "removesections('#assignment'); showpageswithpostmethod('assignment-science-sessions','assignment/science/assignment-science-sessions.php','id=".$values."')";
								}
								if($modulename=="Orientation / Rotation 0")
									$modulename = "Orientation";
								?>
                                <tr onclick="<?php echo $call; ?>" <?php if($completed==1) {?>class="dim"<?php }?> >
                                    <td width="40%"><?php echo $schedulename;?></td>
                                    <td width="35%"><?php echo $modulename; ?></td>
                                    <td class='centerText'><?php echo date("m/d/Y",strtotime($fld_enddate));?></td> 
                                </tr>
                                <?php
                                                            // Mission for test
                                                            if($schtype==18){
                                                                $qrymistest = $ObjDB->QueryObject("SELECT e.fld_id AS fld_module_id, CONCAT(e.fld_test_name) AS schedulename1,f.fld_sch_id AS scheduleid,
                                                                                                    'Mission Assessment' AS modulename, 18 AS schtype, f.fld_id as mapid
                                                                                                    FROM itc_test_master as e
                                                                                                    left join itc_mis_ass as f on e.fld_id=f.fld_test_id
                                                                                                    WHERE f.fld_flag='1' and e.fld_delstatus = '0' and f.fld_sch_id='".$scheduleid."' and f.fld_schtype_id='18' and f.fld_flag='1'");
                                                                if ($qrymistest->num_rows > 0) {
                                                                    while($rowmistest = $qrymistest->fetch_assoc()){
                                                                        extract($rowmistest);
                                                                        $tempmis="mis";
                                                                        $stucpstatus = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM `itc_test_student_answer_track` WHERE  fld_student_id='".$uid."' and fld_test_id='".$fld_module_id."' and fld_schedule_id='".$scheduleid."' and fld_schedule_type='18' and fld_delstatus='0' and fld_retake='0'");
                                                                        $call1 = "removesections('#assignment'); showpages('assignment-assignmentengine-gototest','assignment/assignmentengine/assignment-assignmentengine-gototest.php?id=".$fld_module_id."~".$tempmis."~".$scheduleid."~18"."')";
                                                                        ?>
                                                                        <tr onclick="<?php echo $call1; ?>" <?php if($stucpstatus>=1) {?>class="dim"<?php }?> >
                                                                            <?php $tempname=explode("/",$schedulename);?>
                                                                            <td width="40%"><?php echo $schedulename1."/".$tempname[0];?></td>
                                                                            <td width="35%"><?php echo $modulename; ?></td>
                                                                            <td class='centerText'><?php echo date("m/d/Y",strtotime($fld_enddate));?></td> 
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                            // Mission Sch for test
                                                            if($schtype==23){
                                                                $qrymistest = $ObjDB->QueryObject("SELECT e.fld_id AS fld_module_id, CONCAT(e.fld_test_name) AS schedulename1,f.fld_sch_id AS scheduleid,
                                                                                                    'Mission Assessment' AS modulename, 20 AS schtype, f.fld_id as mapid
                                                                                                    FROM itc_test_master as e
                                                                                                    left join itc_mis_ass as f on e.fld_id=f.fld_test_id
                                                                                                    WHERE f.fld_flag='1' and e.fld_delstatus = '0' and f.fld_sch_id='".$scheduleid."' and f.fld_schtype_id='20' and f.fld_mis_id='".$fld_module_id."' and f.fld_flag='1'");
                                                                if ($qrymistest->num_rows > 0) {
                                                                    while($rowmistest = $qrymistest->fetch_assoc()){
                                                                        extract($rowmistest);
                                                                        $tempmis="mis";
                                                                        $stucpstatus1 = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM `itc_test_student_answer_track` WHERE  fld_student_id='".$uid."' and fld_test_id='".$fld_module_id."' and fld_schedule_id='".$scheduleid."' and fld_schedule_type='20' and fld_delstatus=0 and fld_retake=0");
                                                                        $call2 = "removesections('#assignment'); showpages('assignment-assignmentengine-gototest','assignment/assignmentengine/assignment-assignmentengine-gototest.php?id=".$fld_module_id."~".$tempmis."~".$scheduleid."~20"."')";
                                                                        ?>
                                                                        <tr onclick="<?php echo $call2; ?>" <?php if($stucpstatus1>=1) {?>class="dim"<?php }?> >
                                                                            <?php $tempname=explode("/",$schedulename);?>
                                                                            <td width="40%"><?php echo $schedulename1."/".$tempname[0];?></td>
                                                                            <td width="35%"><?php echo $modulename; ?></td>
                                                                            <td class='centerText'><?php echo date("m/d/Y",strtotime($fld_enddate));?></td> 
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                }
                                                            }
								$completed=0;
					   		}
                        }
						else {?>
                        <tr>
                            <td colspan="3">No Schedules Found</td>
                        </tr>
                        <?php }?>
                    	</tbody>
                	</table>  
                </div>
            </div>            
        </div>    
    </div>
</section>
<?php
@include("footer.php");