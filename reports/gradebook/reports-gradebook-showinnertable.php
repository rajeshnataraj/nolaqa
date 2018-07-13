<?php
error_reporting(0);
@include("sessioncheck.php");


$id = isset($method['id']) ? $method['id'] : '0';
$id = explode(",",$id);
$gradeperiodid = $id[0];
$classid = $id[1];
$top = $id[2];
$left = $id[3];

$sqry='';
$sqry1='';
$sqry2='';
$sqry3='';
$sqry4='';

if($gradeperiodid!=0)
{
	$qrygradeperiod = $ObjDB->QueryObject("SELECT fld_grade_name, fld_start_date, fld_end_date 
												FROM itc_reports_gradebook_master 
												WHERE fld_id='".$gradeperiodid."' AND fld_delstatus='0'");
	
	$rowqrygradeperiod = $qrygradeperiod->fetch_assoc();
	extract($rowqrygradeperiod);
	
        $sqry4 = "AND ('".$fld_start_date."' BETWEEN b.fld_start_date AND b.fld_end_date OR '".$fld_end_date."' BETWEEN b.fld_start_date AND b.fld_end_date OR b.fld_start_date BETWEEN '".$fld_start_date."' AND '".$fld_end_date."' OR b.fld_end_date BETWEEN '".$fld_start_date."' AND '".$fld_end_date."')";
        $sqry = "AND ('".$fld_start_date."' BETWEEN a.fld_start_date AND a.fld_end_date OR '".$fld_end_date."' BETWEEN a.fld_start_date AND a.fld_end_date OR a.fld_start_date BETWEEN '".$fld_start_date."' AND '".$fld_end_date."' OR a.fld_end_date BETWEEN '".$fld_start_date."' AND '".$fld_end_date."')";
        $sqry1 = " AND ('".$fld_start_date."' BETWEEN a.fld_startdate AND a.fld_enddate OR '".$fld_end_date."' BETWEEN a.fld_startdate AND a.fld_enddate OR a.fld_startdate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."' OR a.fld_enddate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."')";
        $sqry2 = "AND ('".$fld_start_date."' BETWEEN b.fld_startdate AND b.fld_enddate OR '".$fld_end_date."' BETWEEN b.fld_startdate AND b.fld_enddate OR b.fld_startdate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."' OR b.fld_enddate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."')";
        $sqry3 = " AND ('".$fld_start_date."' BETWEEN fld_startdate AND fld_enddate OR '".$fld_end_date."' BETWEEN fld_startdate AND fld_enddate OR fld_startdate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."' OR fld_enddate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."')";
        $sqry5 = "AND ('".$fld_start_date."' BETWEEN d.fld_startdate AND d.fld_enddate OR '".$fld_end_date."' BETWEEN d.fld_startdate AND d.fld_enddate OR d.fld_startdate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."' OR d.fld_enddate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."')";
	
        //$sqry = " AND (a.fld_start_date BETWEEN '".$fld_start_date."' AND '".$fld_end_date."' OR a.fld_end_date BETWEEN '".$fld_start_date."' AND '".$fld_end_date."')";
	//$sqry1 = " AND (a.fld_startdate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."' OR a.fld_enddate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."')";
	//$sqry2 = " AND (b.fld_startdate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."' OR b.fld_enddate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."')";
	//$sqry3 = " AND (fld_startdate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."' OR fld_enddate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."')";
}
?>
<section data-type='2home' id='reports-gradebook-showinnertable'>
	<div class='container'>
    	<div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php if($gradeperiodid!=0) echo $fld_grade_name; else echo "Overall Grade Book";?></p>
            </div>
        </div>
        <input type="hidden" name="hidgradeperiodid" id="hidgradeperiodid" value="<?php echo $gradeperiodid;?>"  />
        <div class="row formBase rowspacer">
            <div class='row rowspacer' id="innergradebook1" style="padding-top:20px;">
                <div class="gridtableouter" style="margin-left:45px; margin-bottom:20px;">
                    <table class="fancyTable" id="myTable06" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <?php
                                $roundflag = $ObjDB->SelectSingleValue("SELECT fld_roundflag 
                                                                        FROM itc_class_grading_scale_mapping 
                                                                        WHERE fld_class_id = '".$classid."' AND fld_flag = '1' 
                                                                        GROUP BY fld_roundflag");
                                
                                
                                $qryhead = $ObjDB->QueryObject("(SELECT a.fld_id AS scheduleid, b.fld_unit_id AS minids, '0' AS maxids, fn_shortname (c.fld_unit_name, 1) AS nam, c.fld_unit_name AS fullnam, 
0 AS typeids, a.fld_schedule_name AS schname, a.fld_start_date AS startdate, a.fld_end_date AS enddate
FROM itc_class_sigmath_master AS a 
LEFT JOIN itc_class_sigmath_unit_mapping AS b ON a.fld_id = b.fld_sigmath_id 
LEFT JOIN itc_unit_master AS c ON c.fld_id = b.fld_unit_id 
WHERE a.fld_class_id = '".$classid."' AND a.fld_flag = '1' AND a.fld_delstatus = '0' AND b.fld_flag = '1' AND c.fld_activestatus = '0' 
  AND c.fld_delstatus = '0' ".$sqry.") 	

UNION ALL		
(SELECT a.fld_schedule_id AS scheduleid, MIN(a.fld_rotation-1) AS minids, MAX(a.fld_rotation-1) AS maxids,'Rotation ' AS nam, 'Rotation ' AS fullnam, 
(CASE WHEN  b.fld_type='1' THEN '1' WHEN  b.fld_type='2' 
THEN '4' WHEN b.fld_type='8' THEN '8' END) AS typeids, c.fld_schedule_name AS schname, d.fld_startdate AS startdate, d.fld_enddate AS enddate 
FROM itc_class_rotation_schedulegriddet AS a 
LEFT JOIN itc_class_rotation_moduledet AS b ON b.fld_schedule_id=a.fld_schedule_id 
left join itc_class_rotation_schedule_mastertemp as c on a.fld_schedule_id=c.fld_id
LEFT JOIN itc_class_rotation_scheduledate AS d ON a.fld_schedule_id=d.fld_schedule_id and a.fld_rotation=d.fld_rotation
LEFT JOIN itc_class_rotation_schedule_student_mappingtemp as e ON a.fld_schedule_id=e.fld_schedule_id
WHERE a.fld_class_id='".$classid."' AND b.fld_flag='1' AND a.fld_flag='1' AND e.fld_flag='1' and c.fld_delstatus='0' AND d.fld_flag='1' ".$sqry5." 
GROUP BY a.fld_schedule_id ) 		

UNION ALL
(SELECT a.fld_id AS scheduleid, (MIN(DISTINCT(b.fld_rotation))) AS minids, (MAX(DISTINCT(b.fld_rotation))) AS maxids, 'Rotation ' AS nam, 'Rotation ' AS fullnam, 2 AS typeids, a.fld_schedule_name AS schname, b.fld_startdate AS startdate, b.fld_enddate AS enddate 
FROM itc_class_dyad_schedulemaster AS a 
LEFT JOIN itc_class_dyad_schedulegriddet AS b ON b.fld_schedule_id=a.fld_id
WHERE a.fld_class_id='".$classid."' AND b.fld_flag='1' AND a.fld_delstatus='0' ".$sqry2."
GROUP BY a.fld_id )

UNION ALL
(SELECT a.fld_id AS scheduleid, (MIN(DISTINCT(b.fld_rotation))) AS minids, (MAX(DISTINCT(b.fld_rotation))) AS maxids, 'Rotation ' AS nam, 'Rotation ' AS fullnam, 3 AS typeids, a.fld_schedule_name AS schname, b.fld_startdate AS startdate, b.fld_enddate AS enddate 
FROM itc_class_triad_schedulemaster AS a 
LEFT JOIN itc_class_triad_schedulegriddet AS b ON b.fld_schedule_id=a.fld_id
WHERE a.fld_class_id='".$classid."' AND b.fld_flag='1' AND a.fld_delstatus='0' ".$sqry2."
GROUP BY a.fld_id ) 		

UNION ALL
(SELECT a.fld_id AS scheduleid, a.fld_module_id AS minids, '0' AS maxids, fn_shortname(CONCAT(b.fld_module_name,' / Ind Module'),1) AS nam, 
CONCAT(b.fld_module_name,' / Ind Module') AS fullnam, 5 AS typeids, a.fld_schedule_name AS schname, a.fld_startdate AS startdate, a.fld_enddate AS enddate 
FROM itc_class_indassesment_master AS a 
LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
LEFT JOIN itc_class_indassesment_student_mapping as c ON a.fld_id=c.fld_schedule_id
WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND a.fld_delstatus='0' 
AND a.fld_moduletype='1' AND b.fld_delstatus='0' AND c.fld_flag='1' ".$sqry1." 
GROUP BY a.fld_id )  		

UNION ALL		
(SELECT a.fld_id AS scheduleid, a.fld_module_id AS ids, '0' AS maxids,  
fn_shortname(CONCAT(b.fld_mathmodule_name,' / Ind MathModule'),1) AS nam, 
CONCAT(b.fld_mathmodule_name,' / Ind MathModule') AS fullnam, 6 AS typeids, a.fld_schedule_name AS schname, a.fld_startdate AS startdate, a.fld_enddate AS enddate
FROM itc_class_indassesment_master AS a 
LEFT JOIN itc_mathmodule_master AS b ON a.fld_module_id=b.fld_id
WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND a.fld_delstatus='0' 
AND a.fld_moduletype='2' AND b.fld_delstatus='0' ".$sqry1."
GROUP BY a.fld_id)

UNION ALL	
(SELECT a.fld_id AS scheduleid, a.fld_module_id AS minids, '0' AS maxids, 
fn_shortname(CONCAT(b.fld_module_name,' / Quest'),1) AS nam, 
CONCAT(b.fld_module_name,' / Quest') AS fullnam, 7 AS typeids, a.fld_schedule_name AS schname, a.fld_startdate AS startdate, a.fld_enddate AS enddate 
FROM itc_class_indassesment_master AS a 
LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id
WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND a.fld_delstatus='0' 
AND a.fld_moduletype='7' AND b.fld_delstatus='0' ".$sqry1."
GROUP BY a.fld_id) 

UNION ALL	
(SELECT a.fld_id AS scheduleid,a.fld_module_id AS minids,'0' AS maxids,fn_shortname(CONCAT(b.fld_contentname, ' / Custom content'), 1) AS nam,
    CONCAT(b.fld_contentname, ' / Custom content') AS fullnam,17 AS typeids,a.fld_schedule_name AS schname,a.fld_startdate AS startdate,
    a.fld_enddate AS enddate
FROM itc_class_indassesment_master AS a
LEFT JOIN itc_customcontent_master AS b ON a.fld_module_id = b.fld_id
WHERE a.fld_class_id = '".$classid."' AND a.fld_flag = '1' AND a.fld_delstatus = '0' AND a.fld_moduletype = '17'
AND b.fld_delstatus = '0' ".$sqry1." GROUP BY a.fld_id)

UNION ALL	
(SELECT a.fld_id AS scheduleid, a.fld_exp_id AS minids, '0' AS maxids, 
fn_shortname(CONCAT(b.fld_exp_name,' / Expedition'),1) AS nam, 
CONCAT(b.fld_exp_name,' / Expedition') AS fullnam, 15 AS typeids, a.fld_schedule_name AS schname, a.fld_startdate AS startdate, a.fld_enddate AS enddate 
FROM itc_class_indasexpedition_master AS a 
LEFT JOIN itc_exp_master AS b ON a.fld_exp_id=b.fld_id
WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND a.fld_delstatus='0' 
AND b.fld_delstatus='0' ".$sqry1."
GROUP BY a.fld_id) 

UNION ALL		
(SELECT a.fld_schedule_id AS scheduleid, MIN(a.fld_rotation-1) AS minids, MAX(a.fld_rotation-1) AS maxids,'Rotation ' AS nam, 'Rotation ' AS fullnam, 
19 AS typeids, c.fld_schedule_name AS schname, d.fld_startdate AS startdate, d.fld_enddate AS enddate 
FROM itc_class_rotation_expschedulegriddet AS a 
LEFT JOIN itc_class_rotation_expmoduledet AS b ON b.fld_schedule_id=a.fld_schedule_id 
left join itc_class_rotation_expschedule_mastertemp as c on a.fld_schedule_id=c.fld_id
LEFT JOIN itc_class_rotation_expscheduledate AS d ON a.fld_schedule_id=d.fld_schedule_id and a.fld_rotation=d.fld_rotation
LEFT JOIN itc_class_rotation_expschedule_student_mappingtemp as e ON a.fld_schedule_id=e.fld_schedule_id
WHERE a.fld_class_id='".$classid."' AND b.fld_flag='1' AND a.fld_flag='1' AND e.fld_flag='1' and c.fld_delstatus='0' AND d.fld_flag='1' ".$sqry5." 
GROUP BY a.fld_schedule_id )

UNION ALL 		
(SELECT a.fld_class_id AS scheduleid, b.fld_id AS minids, '0' AS maxids, fn_shortname(b.fld_test_name,1) 
AS nam, b.fld_test_name AS fullnam, 9 AS typeids, 'Test' AS schname, a.fld_start_date AS startdate, a.fld_end_date AS enddate 
FROM itc_test_student_mapping AS a 
LEFT JOIN itc_test_master AS b ON a.fld_test_id=b.fld_id 
WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_delstatus='0' AND (b.fld_ass_type='0' or b.fld_ass_type = '1' or b.fld_ass_type='2')
".$sqry." GROUP BY b.fld_id) 		

UNION ALL	
(SELECT a.fld_class_id AS scheduleid, a.fld_activity_id AS minids, '0' AS maxids, 
fn_shortname(b.fld_activity_name,1) AS nam, b.fld_activity_name AS fullnam, 
10 AS typeids, 'Activity' AS schname, a.fld_start_date AS startdate, a.fld_end_date AS enddate 
FROM itc_activity_student_mapping AS a 
LEFT JOIN itc_activity_master AS b ON a.fld_activity_id=b.fld_id 
WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_delstatus='0' ".$sqry."
GROUP BY a.fld_activity_id) 

UNION ALL	
(SELECT a.fld_id AS scheduleid, a.fld_mis_id AS minids, '0' AS maxids, 
fn_shortname(CONCAT(b.fld_mis_name,' / Mission'),1) AS nam, 
CONCAT(b.fld_mis_name,' / Mission') AS fullnam, 18 AS typeids, a.fld_schedule_name AS schname, a.fld_startdate AS startdate, a.fld_enddate AS enddate 
FROM itc_class_indasmission_master AS a 
LEFT JOIN itc_mission_master AS b ON a.fld_mis_id=b.fld_id
WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND a.fld_delstatus='0' 
AND b.fld_delstatus='0' ".$sqry1."
GROUP BY a.fld_id) 

UNION ALL
(SELECT a.fld_schedule_id AS scheduleid, MIN(a.fld_rotation-1) AS minids, MAX(a.fld_rotation-1) AS maxids,'Rotation ' AS nam, 'Rotation ' AS fullnam, 
22 AS typeids, c.fld_schedule_name AS schname, d.fld_startdate AS startdate, d.fld_enddate AS enddate 
FROM itc_class_rotation_modexpschedulegriddet AS a 
LEFT JOIN itc_class_rotation_modexpmoduledet AS b ON b.fld_schedule_id=a.fld_schedule_id 
left join itc_class_rotation_modexpschedule_mastertemp as c on a.fld_schedule_id=c.fld_id
LEFT JOIN itc_class_rotation_modexpscheduledate AS d ON a.fld_schedule_id=d.fld_schedule_id and a.fld_rotation=d.fld_rotation
LEFT JOIN itc_class_rotation_modexpschedule_student_mappingtemp as e ON a.fld_schedule_id=e.fld_schedule_id
WHERE a.fld_class_id='".$classid."'  AND c.fld_flag = '1' AND b.fld_flag='1' AND a.fld_flag='1' AND e.fld_flag='1' and c.fld_delstatus='0' AND d.fld_flag='1' ".$sqry5." 
GROUP BY a.fld_schedule_id)

UNION ALL
(SELECT a.fld_schedule_id AS scheduleid, MIN(a.fld_rotation-1) AS minids, MAX(a.fld_rotation-1) AS maxids,'Rotation ' AS nam, 'Rotation ' AS fullnam, 
23 AS typeids, c.fld_schedule_name AS schname, d.fld_startdate AS startdate, d.fld_enddate AS enddate 
FROM itc_class_rotation_mission_schedulegriddet AS a 
LEFT JOIN itc_class_rotation_missiondet AS b ON b.fld_schedule_id=a.fld_schedule_id 
left join itc_class_rotation_mission_mastertemp as c on a.fld_schedule_id=c.fld_id
LEFT JOIN itc_class_rotation_missionscheduledate AS d ON a.fld_schedule_id=d.fld_schedule_id and a.fld_rotation=d.fld_rotation
LEFT JOIN itc_class_rotation_mission_student_mappingtemp as e ON a.fld_schedule_id=e.fld_schedule_id
WHERE a.fld_class_id='".$classid."'  AND c.fld_flag = '1' AND b.fld_flag='1' AND a.fld_flag='1' AND e.fld_flag='1' and c.fld_delstatus='0' AND d.fld_flag='1' ".$sqry5." 
GROUP BY a.fld_schedule_id)
");
                            ?>
                            <th align="center" id="teststu" class="teststu gradehead" style=" height:40px;"><span style="font-size:14px;">Student Name</span></th>
                            <?php
                            if($qryhead->num_rows>0)
                            {
                                $unitids = array();
                                $cnt=0;
                                while($rowqryhead = $qryhead->fetch_assoc()) // show the module based on number of copies
                                {
                                    extract($rowqryhead);
                                    $assid[$cnt]=$minids;
                                    $maxassid[$cnt]=$maxids;
                                    $scheduleids[$cnt]=$scheduleid;
                                    $type[$cnt]=$typeids;
                                    if($typeids==0 || $typeids==10 || $typeids==5 || $typeids==6 || $typeids==7 || $typeids==17 || $typeids==9 || $typeids==15 || $typeids==18 || $typeids==20 || $typeids==24)//rubric || $typeids==16 || $typeids==21   || $typeids==25
                                    {
                                        if($typeids==5 || $typeids==6 || $typeids==7)
                                        {
                                            $tooltipvalue="Click to Enter Performance Assessments.";
                                        }
                                        else if($typeids==18)
                                        {
                                            $tooltipvalue="Click to Assign Max Points.";
                                        }
                                        else
                                        {
                                            $tooltipvalue = $fullnam."<br />/ ".$schname."<br />".$startdate." To ".$enddate;
                                        }
                                        ?>
                                        <th id="testipl" class="testipl tooltip" align="center" style="height:40px;     margin-bottom: 1px; overflow:hidden; font-size:23px;<?php if($typeids==0 || $typeids==17 or $typeids==5 or $typeids==6 or $typeids==7 or $typeids==10 or $typeids==18 ) { ?> cursor:pointer<?php }?>" title="<?php echo $tooltipvalue; ?>" <?php if($typeids==0 || $typeids==17 or $typeids==5 or $typeids==6 or $typeids==7 or $typeids==10 or $typeids==18) { ?>onclick="fn_saveallperformance(<?php echo $classid; ?>,0,<?php echo $typeids; ?>,<?php echo $scheduleid; ?>,<?php echo $minids; ?>)"<?php }?>><span style="margin-top:-8px;"><?php echo $nam;?></span></th>
                                        <?php
                                    }
                                    else
                                    {
                                        $oriencunt = 0;
                                        if($typeids==2 or $typeids==3)
                                        {
                                            if($typeids==2)
                                            {
                                                $schegridtable = "itc_class_dyad_schedulegriddet";										
                                                $schname = "Dyad";
                                            }
                                            if($typeids==3)
                                            {
                                                $schegridtable = "itc_class_triad_schedulegriddet";										
                                                $schname = "Triad";
                                            }

                                            $oriencunt = $ObjDB->SelectSingleValue("SELECT COUNT(fld_rotation) FROM ".$schegridtable." 
                                                                                        WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_flag='1' 
                                                                                                            AND fld_rotation='0' ".$sqry3."");
                                        }
                                        for($i=$minids;$i<=$maxids;$i++)
                                        {												
                                            $increment = $i;

                                            if($i == 0 and $oriencunt==1)
                                                $rotname = "Orientation / ".$schname;
                                            else
                                                $rotname = $nam.' '.$increment;

                                                if($typeids==19 ) //Mohan M || $typeids==23  || $typeids==22  AND $typeids!=22  AND $typeids!=22
                                                {
                                                    $tooltipvalue = $rotname."<br />".$schname."<br />".$startdate." To ".$enddate;
                                                }
                                                else if($typeids==23)
                                                {
                                                    $tooltipvalue="Click to Assign Max Points.";
                                                }
                                                else
                                                {
                                                    $tooltipvalue="Click to Enter Performance Assessments.";
                                                }
                                            ?>
                                            <th id="testmod" class="centerText testmod tooltip" style="font-size:24px;<?php if($typeids!=19){?> cursor:pointer <?php } ?>" original-title="<?php echo $tooltipvalue; ?>" <?php if($typeids!=19){?> onclick="fn_saveallperformance(<?php echo $classid; ?>,<?php echo $increment; ?>,<?php echo $typeids; ?>,<?php echo $scheduleid; ?>)" <?php } ?>><span style="font-size:14px;"><?php echo $rotname;?></span></th>
                                            <?php
                                        }
                                    }
                                    $cnt++;
                                }
                            }
                            else
                            {
                                ?>
                                <th align="center" id="testrec"><span style="font-size:14px;">No Record</span></th>
                                <?php
                            }
                            ?>
                            <th align="center" style="width:100px;" id="totalearned" class="totalearned"><span style="font-size:14px;">Total Points Earned</span></th>
                            <th align="center" style="width:100px;" id="totalearned" class="totalearned"><span style="font-size:14px;">Total Points Possible</span></th>
                            <th align="center" style="width:100px;" id="totalearned" class="totalearned"><span style="font-size:14px;">Percentage</span></th>
                            <th align="center" style="width:100px;" id="totalearned" class="totalearned"><span style="font-size:14px;">Grade</span></th>
                        </tr>
                    </thead>
                    <tbody id="body">
                        <?php
                        $qrystudent = $ObjDB->QueryObject("SELECT a.fld_student_id, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname 
                                                                                                FROM itc_class_student_mapping AS a 
                                                                                                LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                                                                WHERE a.fld_class_id = '".$classid."' AND a.fld_flag = '1' AND b.fld_activestatus = '1' 
                                                                                                        AND b.fld_delstatus = '0' 
                                                                                                ORDER BY studentname");
                        if($qrystudent->num_rows>0)
                        {
                            while($rowqrystudent = $qrystudent->fetch_assoc()) // show the module based on number of copies
                            {
                                extract($rowqrystudent);
                                $totalpointsearned= 0;
                                $totalpointspossible= 0;
                                $pointsearned=0;
                                $pointspossible=0;
                                ?>
                                <tr>
                                    <td id="student1" name="<?php echo $fld_student_id;?>" align="center" style="font-size:14px; font-weight:bold"><div><?php echo $studentname;?></div></td>
                                    <?php 
                                    if($qryhead->num_rows>0)
                                    {
                                        for($j=0;$j<sizeof($assid);$j++) 
                                        { 
if($type[$j]==0) 
{
    $studentcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                        FROM itc_class_sigmath_student_mapping 
                                                        WHERE fld_sigmath_id='".$scheduleids[$j]."' AND fld_flag='1'
                                                                AND fld_student_id='".$fld_student_id."'");
    if($studentcount!=0)
    {
        $qrypoints = $ObjDB->QueryObject("SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned 
                                                    WHEN fld_lock='1' THEN fld_teacher_points_earned END) 
                                                    AS pointsearned, SUM(fld_points_possible) AS pointspossible 
                                            FROM `itc_assignment_sigmath_master` 
                                            WHERE fld_class_id='".$classid."' AND fld_delstatus='0' 
                                                    AND fld_student_id='".$fld_student_id."' AND fld_grade<>'0' 
                                                    AND fld_schedule_id='".$scheduleids[$j]."' 
                                                    AND fld_unit_id='".$assid[$j]."' AND (fld_points_earned<>'' 
                                                    OR fld_teacher_points_earned<>'') ");
        if($qrypoints->num_rows>0)
        {
            while($rowqrypoints = $qrypoints->fetch_assoc()) // show the module based on number of copies
            {
                extract($rowqrypoints);
                if($pointspossible=='')
                        $pointspossible = "-";

                if($pointsearned=='')
                {
                    $pointsearned = "-";
                    //$pointspossible = "-";
                    $percentage = "-";
                    $grade = "NA";
                }
                else
                {
                    $totalpointsearned = $totalpointsearned + $pointsearned;
                    if($roundflag==0)
                        $percentage = round(($pointsearned/$pointspossible)*100,2);
                    else
                        $percentage = round(($pointsearned/$pointspossible)*100);

                    $perarray = explode('.',$percentage);
                    $grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
                                                            FROM itc_class_grading_scale_mapping 
                                                            WHERE fld_class_id = '".$classid."' 
                                                                    AND fld_lower_bound <= '".$perarray[0]."' 
                                                                    AND fld_upper_bound >= '".$perarray[0]."' 
                                                                    AND fld_flag = '1'");
                }
                if($pointspossible=='')
                    $pointspossible = "-";
                else
                    $totalpointspossible = $totalpointspossible + $pointspossible;

                ?>
                <td onclick="fn_showiplpoints(<?php echo $type[$j].",".$classid.",".$fld_student_id.",".$assid[$j].",".$scheduleids[$j] ?>);" style="cursor:pointer; height:39px;">
                    <div>
                        <div style="text-align:center"><?php echo $percentage." % ".$grade;?></div>
                        <div style="text-align:center"><?php echo $pointsearned." / ".$pointspossible;?></div>
                    </div>
                </td>
                <?php
            }
        }
    }
    else
    {   ?>
        <td align="center">No IPLs</td>
        <?php
    }
} 
//ipl end   

else if($type[$j]==5 || $type[$j]==6 || $type[$j]==7 || $type[$j]==17) 
{ 
    $studentcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                        FROM itc_class_indassesment_student_mapping 
                                                        WHERE fld_schedule_id='".$scheduleids[$j]."' 
                                                                AND fld_student_id='".$fld_student_id."' 
                                                                AND fld_flag='1'");
    if($studentcount!=0)
    {
        $qryinddetails = '';
        if($type[$j]==6)
        {
            $qryinddetails = "SELECT SUM(w.earned) AS pointsearned, SUM(w.possible) AS pointspossible FROM (
                                              (SELECT SUM(CASE WHEN fld_lock = '0' THEN fld_points_earned WHEN 
                                                    fld_lock = '1' THEN fld_teacher_points_earned END) AS earned, 
                                                    SUM(fld_points_possible) AS possible 
                                              FROM itc_module_points_master 
                                              WHERE fld_student_id='".$fld_student_id."' AND fld_module_id='".$assid[$j]."' 
                                                      AND fld_schedule_id='".$scheduleids[$j]."' AND fld_grade<>'0' AND fld_delstatus='0'
                                                      AND fld_schedule_type='".$type[$j]."' AND (fld_points_earned<>'' 
                                                      OR fld_teacher_points_earned<>''))
                            UNION ALL 		
                                     (SELECT ROUND(SUM(CASE WHEN a.fld_lock = '0' THEN a.fld_points_earned WHEN 
                                    a.fld_lock = '1' THEN a.fld_teacher_points_earned END)/4) AS earned, 
                                    ROUND(SUM(a.fld_points_possible)/4) AS possible 
                                    FROM itc_assignment_sigmath_master AS a  
                                    WHERE a.fld_student_id = '".$fld_student_id."' and a.fld_module_id='".$assid[$j]."'
                                    AND a.fld_test_type='5' AND a.fld_schedule_id = '".$scheduleids[$j]."' 
                                    AND (a.fld_points_earned<>'' OR a.fld_teacher_points_earned<>'') AND a.fld_delstatus='0' AND (a.fld_status='1' OR a.fld_status='2' OR a.fld_lock='1')
                                    AND a.fld_unitmark='0')
                            ) AS w";
        }
        else if($type[$j]==17)
        {
            $qryinddetails = "SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
                                            THEN fld_teacher_points_earned END) AS pointsearned, 
                                            SUM(fld_points_possible) AS pointspossible 
                                    FROM itc_module_points_master 
                                    WHERE fld_student_id='".$fld_student_id."' AND fld_module_id='".$assid[$j]."' 
                                    AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."' 
                                    AND (fld_points_earned<>'' OR fld_teacher_points_earned<>'') AND fld_grade<>'0'";

        }
        else
        {
            $qryinddetails = "SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
                                        THEN fld_teacher_points_earned END) AS pointsearned, 
                                        SUM(fld_points_possible) AS pointspossible 
                                FROM itc_module_points_master 
                                WHERE fld_student_id='".$fld_student_id."' AND fld_module_id='".$assid[$j]."' 
                                AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."' 
                                AND (fld_points_earned<>'' OR fld_teacher_points_earned<>'') AND fld_grade<>'0' AND fld_delstatus='0'";
        }
        $qrypoints = $ObjDB->QueryObject($qryinddetails);
        if($qrypoints->num_rows>0)
        {
            while($rowqrypoints = $qrypoints->fetch_assoc())
            {
                extract($rowqrypoints);
                if($pointsearned=='')
                {
                    $pointsearned = "-";
                    $percentage = "-";
                    $grade = "NA";
                }
                else
                {
                    $totalpointsearned = $totalpointsearned + $pointsearned;
                    if($roundflag==0)
                            $percentage = round(($pointsearned/$pointspossible)*100,2);
                    else
                            $percentage = round(($pointsearned/$pointspossible)*100);

                    $perarray = explode('.',$percentage);
                    $grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$classid."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
                }

                if($pointspossible=='')
                    $pointspossible = "-";
                else
                    $totalpointspossible = $totalpointspossible + $pointspossible;
                ?>                                                
                <td onclick="fn_showiplpoints(<?php echo $type[$j].",".$classid.",".$fld_student_id.",".$assid[$j].",".$scheduleids[$j] ?>);" style="cursor:pointer; height:39px;">
                    <div>
                        <div style="text-align:center"><?php echo $percentage." % ".$grade;?></div>
                        <div style="text-align:center"><?php echo $pointsearned." / ".$pointspossible;?></div>
                    </div>
                </td>
                <?php
            }
        }																				
    }
    else
    { ?>
            <td align="center">
                    <?php if($type[$j]==6) echo "No Ind MathModule"; else if($type[$j]==5) echo "No Ind Module"; else if($type[$j]==7) echo "No Ind Quest"; else if($type[$j]==17) echo "No Ind Custom";?>
            </td><?php
    }
}

/**********expedition code start here***********/
else if($type[$j]==15) 
{ 
    $studentcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                    FROM itc_class_exp_student_mapping 
                                                    WHERE fld_schedule_id='".$scheduleids[$j]."' 
                                                            AND fld_student_id='".$fld_student_id."' 
                                                            AND fld_flag='1'");
    if($studentcount!=0)
    {
        /************** Pre/Post test code start here ***************/
        $pointsearnedfortest=0;
        $possiblepointfortest1=0;
        $possiblepointfortest=0;
        
        $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                            WHERE fld_class_id='".$classid."' AND fld_sch_id='".$scheduleids[$j]."' AND fld_schtype_id='".$type[$j]."'");

        if($qrytest->num_rows>0)
        {
            while($rowqrytest = $qrytest->fetch_assoc())
            {
                extract($rowqrytest);
                $exptype='3';
                /*********Pre Test Code start Here*********/
                if($pretest!='0')
                {
                    $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                    FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_delstatus='0'");
                    if($qry->num_rows>0)
                    {
                        while($rowqry = $qry->fetch_assoc())
                        {
                            extract($rowqry);
                            if($questype==2)
                            {
                                $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                            }
                            $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0'");

                            $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master 
                                                                            WHERE fld_student_id='".$fld_student_id."' AND fld_exp_id='".$expid."' 
                                                                            AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."' 
                                                                            AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                            if(trim($tchpointcnt)=='')
                            {
                                $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                        WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                        AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."' 
                                                                                        AND a.fld_schedule_type='".$type[$j]."' AND b.fld_delstatus = '0' AND a.fld_delstatus = '0'");

                                if(trim($correctcountfortestattend) != '')
                                {
                                    $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                        WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                        AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."' 
                                                                                        AND a.fld_schedule_type='".$type[$j]."' AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");
                                    $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                    $possiblepointfortest+=$possiblepointfortest1;
                                }
                            }
                            else
                            {
                                $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                    WHEN fld_lock = '0' THEN fld_points_earned
                                                                                    WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                            END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$fld_student_id."'
                                                                                    AND fld_exp_id='".$expid."' AND fld_schedule_id='".$scheduleids[$j]."' 
                                                                                    AND fld_schedule_type='".$type[$j]."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");
                                if(trim($tchpointearn)!='')
                                {
                                    $possiblepointfortest+=$possiblepointfortest1;
                                }
                                $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                            }
                        }
                    }
                }
                /*********Pre Test Code End Here*********/

                /*********Post Test Code start Here*********/
                if($posttest!='0')
                {
                    $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                    FROM itc_test_master WHERE fld_id='".$posttest."' AND fld_delstatus='0'");
                    if($qry->num_rows>0)
                    {
                        while($rowqry = $qry->fetch_assoc())
                        {
                            extract($rowqry);

                            if($questype==2)
                            {
                                $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                            }
                            $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0'");

                            $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master 
                                                                            WHERE fld_student_id='".$fld_student_id."' AND fld_exp_id='".$expid."' 
                                                                            AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."' 
                                                                            AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                            if(trim($tchpointcnt)=='')
                            {
                                $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                        WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                        AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."' 
                                                                                        AND a.fld_schedule_type='".$type[$j]."' AND b.fld_delstatus = '0' AND a.fld_delstatus = '0'");

                               if(trim($correctcountfortestattend) != '')
                               {
                                    $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                        WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                        AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."' 
                                                                                        AND a.fld_schedule_type='".$type[$j]."' AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");
                                    
                                    $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                    $possiblepointfortest+=$possiblepointfortest1;
                               }
                            }
                            else
                            {
                                $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                    WHEN fld_lock = '0' THEN fld_points_earned
                                                                                    WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                            END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$fld_student_id."'
                                                                                    AND fld_exp_id='".$expid."' AND fld_schedule_id='".$scheduleids[$j]."' 
                                                                                    AND fld_schedule_type='".$type[$j]."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");
                                if(trim($tchpointearn)!='')
                                {
                                    $possiblepointfortest+=$possiblepointfortest1;
                                }
                                $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                            }
                        }
                    }
                }
            }
        }
        /************** Pre/Post test code end here ***************/   

        /************** Rubric code start here ***************/
        $pointsearnedrubric=0;
        $pointspossiblerubric=0;
        $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                        CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                        LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                        LEFT JOIN itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                        LEFT JOIN itc_class_indasexpedition_master AS d ON a.fld_schedule_id=d.fld_id 
                                        WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleids[$j]."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                AND a.fld_schedule_type='15' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                AND b.fld_school_id IN(0,".$schoolid.")");

        if($qryrub->num_rows>0)
        {
            while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
            {
                extract($rowqryrub);
                
                 $destinationids = $ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_exp_rubric_dest_master WHERE fld_rubric_name_id='".$rubricids."' AND fld_exp_id='".$assid[$j]."'"); 

                $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master 
                                                            WHERE fld_exp_id='".$assid[$j]."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_rpt WHERE fld_exp_id='".$assid[$j]."'  
                                                                AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$classid."' 
                                                                AND fld_schedule_id='".$scheduleids[$j]."' AND fld_delstatus='0' "); 

                $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_rpt_statement
                                                                    WHERE fld_student_id='".$fld_student_id."' AND fld_exp_id='".$assid[$j]."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
                                                                    AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                $pointsearnedrubric = $pointsearnedrubric+$studentscore;
                if($studentscore!=0)
                {
                    $pointspossiblerubric = $pointspossiblerubric+$totscore;
                }
            }
        }
        /************** Rubric code end here ***************/
        $pointsearned=round($pointsearnedfortest + $pointsearnedrubric);
        $pointspossible=$possiblepointfortest + $pointspossiblerubric;

        //percentage code start here
        if($pointsearned=='' AND $pointspossible!='0')
        {
            $pointsearned = "-";
            $percentage = "-";
            $grade = "NA";
            $pointspossible = "-";
        }
        else
        {
            if($roundflag==0)
                $percentage = round(($pointsearned/$pointspossible)*100,2);
            else
                $percentage = round(($pointsearned/$pointspossible)*100);

            $perarray = explode('.',$percentage);

            $grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$classid."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
        }
        if($percentage==0)
        {
            $percentage = "-";
            $grade = "NA";
            $pointsearned = "-";
            $pointspossible = "-";
        }
        //percentage code end here

        ?>                                                
        <td onclick="fn_showiplpoints(<?php echo $type[$j].",".$classid.",".$fld_student_id.",".$assid[$j].",".$scheduleids[$j] ?>);" style="cursor:pointer; height:39px;">
                <div>
                        <div style="text-align:center"><?php echo $percentage." % ".$grade;?></div>
                        <div style="text-align:center"><?php echo $pointsearned." / ".$pointspossible;?></div>
                </div>
        </td>
        <?php
        $totalpointsearned = $totalpointsearned + $pointsearned;
        $totalpointspossible = $totalpointspossible + $pointspossible;																		
    }
    else
    { ?>
            <td align="center">
                    <?php echo "No Expedition";?>
            </td><?php
    }
}
/***********Expedition code end here************/

/*******EXPEDITION sCHEDULE cODE START HERE*******/
else if($type[$j]==19) 
{ 
    $incrementcountexp = $assid[$j];
    $totalcntexp = $maxassid[$j];

    for($z=$incrementcountexp;$z<=$totalcntexp;$z++)
    { 
        $pointsearned=0;
        $pointspossible=0;
        $expstudentcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                FROM itc_class_rotation_expschedule_student_mappingtemp
                                                                WHERE fld_schedule_id='".$scheduleids[$j]."' 
                                                                        AND fld_student_id='".$fld_student_id."' 
                                                                        AND fld_flag='1'");
        if($expstudentcount!=0)
        {
            $rotid=$z+1;                             
            $qryexpsch = $ObjDB->QueryObject("SELECT fld_expedition_id AS expid, (SELECT fld_exp_name FROM itc_exp_master WHERE fld_id=fld_expedition_id) AS expname 
                                                      FROM `itc_class_rotation_expschedulegriddet` WHERE fld_class_id='".$classid."' AND fld_student_id='".$fld_student_id."' 
                                                      AND fld_schedule_id='".$scheduleids[$j]."' AND fld_rotation='".$rotid."' AND fld_flag='1'  LIMIT 0,1");
            $schexpid='';
            $schexpname='';
            if($qryexpsch->num_rows>0)
            {
                $rowschexp=$qryexpsch->fetch_assoc();
                extract($rowschexp);
                $schexpid=$expid;
                $schexpname=$expname;
            }

            /************** Pre/Post test code start here ***************/
            $pointsearnedfortest=0;
            $possiblepointfortest1=0;
            $possiblepointfortest=0;
            $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                            WHERE fld_class_id='".$classid."' AND fld_sch_id='".$scheduleids[$j]."' AND fld_texpid='".$schexpid."' AND fld_schtype_id='".$type[$j]."'");
          
            if($qrytest->num_rows>0)
            {
                while($rowqrytest = $qrytest->fetch_assoc())
                {
                    extract($rowqrytest);
                    $exptype='3';
                    /*********Pre Test Code start Here*********/
                    if($pretest!='0')
                    {
                        $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                        FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_delstatus='0'");
                        if($qry->num_rows>0)
                        {
                            while($rowqry = $qry->fetch_assoc())
                            {
                                extract($rowqry);
                                if($questype==2)
                                {
                                    $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                }

                                $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_student_id='".$fld_student_id."' AND fld_exp_id='".$schexpid."' AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                 if(trim($tchpointcnt)=='')
                                {
                                    $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                            WHERE b.fld_expt = '".$schexpid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                            AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."' 
                                                                                            AND a.fld_schedule_type='".$type[$j]."'
                                                                                            AND b.fld_delstatus = '0' AND a.fld_delstatus = '0'");

                                   if(trim($correctcountfortestattend) != '')
                                   {
                                        $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                            WHERE b.fld_expt = '".$schexpid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                            AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."' 
                                                                                            AND a.fld_schedule_type='".$type[$j]."'
                                                                                            AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");
                                       
                                       
                                        $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                        $possiblepointfortest+=$possiblepointfortest1;
                                   }
                                }
                                else
                                {
                                    $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                    WHEN fld_lock = '0' THEN fld_points_earned
                                                                                    WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                            END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$fld_student_id."'
                                                                                    AND fld_exp_id='".$schexpid."' AND fld_schedule_id='".$scheduleids[$j]."' 
                                                                                    AND fld_schedule_type='".$type[$j]."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                    if($tchpointearn!='')
                                    {
                                        $possiblepointfortest+=$possiblepointfortest1;
                                    }

                                    $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                }
                            }
                        }
                    }
                    /*********Pre Test Code End Here*********/

                    /*********Post Test Code start Here*********/
                    if($posttest!='0')
                    {
                        $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                        FROM itc_test_master WHERE fld_id='".$posttest."' AND fld_delstatus='0'");
                        if($qry->num_rows>0)
                        {
                            while($rowqry = $qry->fetch_assoc())
                            {
                                extract($rowqry);

                                if($questype==2)
                                {
                                    $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                }
                                $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_student_id='".$fld_student_id."' AND fld_exp_id='".$schexpid."' AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                if(trim($tchpointcnt)=='')
                                {
                                    $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                           LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                           WHERE b.fld_expt = '".$schexpid."' AND a.fld_student_id = '".$fld_student_id."' AND a.fld_test_id='".$testid."' AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."'
                                                                                           AND b.fld_delstatus = '0' AND a.fld_delstatus = '0'");

                                   if(trim($correctcountfortestattend) != '')
                                   {
                                        $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                           LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                           WHERE b.fld_expt = '".$schexpid."' AND a.fld_student_id = '".$fld_student_id."' AND a.fld_test_id='".$testid."' AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."'
                                                                                           AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");
                                        
                                        $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                        $possiblepointfortest+=$possiblepointfortest1;
                                   }
                                }
                                else
                                {
                                    $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                    WHEN fld_lock = '0' THEN fld_points_earned
                                                                                    WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                            END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$fld_student_id."'
                                                                                    AND fld_exp_id='".$schexpid."' AND fld_schedule_id='".$scheduleids[$j]."' 
                                                                                    AND fld_schedule_type='".$type[$j]."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                    if($tchpointearn!='')
                                    {
                                        $possiblepointfortest+=$possiblepointfortest1;
                                    }

                                    $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                }
                            }
                        }
                    }
                    /*********Post Test Code End Here*********/
                }
            }
          /************** Pre/Post test code end here ***************/

          /************** Rubric code start here ***************/
          $pointsearnedrubric=0;
          $pointspossiblerubric=0;
          $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                              CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                              LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                              LEFT JOIN itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                              LEFT JOIN itc_class_rotation_expschedule_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
                                              WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleids[$j]."' AND b.fld_exp_id='".$schexpid."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                      AND a.fld_schedule_type='17' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                      AND b.fld_school_id IN(0,".$schoolid.")");

          if($qryrub->num_rows>0)
          {
              while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
              {
                  extract($rowqryrub);

                   $destinationids = $ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_exp_rubric_dest_master WHERE fld_rubric_name_id='".$rubricids."' AND fld_exp_id='".$schexpid."'"); 
                  
                  $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master 
                                                              WHERE fld_exp_id='".$schexpid."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                  $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_expsch_rubric_rpt WHERE fld_exp_id='".$schexpid."'  
                                                                              AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$classid."' 
                                                                              AND fld_schedule_id='".$scheduleids[$j]."' AND fld_delstatus='0' "); 

                  $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_expsch_rubric_rpt_statement
                                                                          WHERE fld_student_id='".$fld_student_id."' AND fld_exp_id='".$schexpid."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
                                                                          AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                  $pointsearnedrubric = $pointsearnedrubric+$studentscore;
                  if($studentscore!=0)
                  {
                      $pointspossiblerubric = $pointspossiblerubric+$totscore;
                  }
              }
          }

          /************** Rubric code end here ***************/
          $pointsearned=round($pointsearnedfortest + $pointsearnedrubric);
          $pointspossible=$possiblepointfortest + $pointspossiblerubric;


          //percentage code start here
          if($pointsearned=='' AND $pointspossible!='0')
          {
              $pointsearned = "-";
              $pointspossible="-";
              $percentage = "-";
              $grade = "NA";
          }
          else
          {
              if($roundflag==0)
                      $percentage = round(($pointsearned/$pointspossible)*100,2);
              else
                      $percentage = round(($pointsearned/$pointspossible)*100);

              $perarray = explode('.',$percentage);
              $grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$classid."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
          }
          if($percentage==0)
          {
              $percentage = "-";
              $grade = "NA";
              $pointsearned = "-";
              $pointspossible="-";
          }
          //percentage code end here

          ?>                                                
          <td onclick="fn_showiplpoints(<?php echo $type[$j].",".$classid.",".$fld_student_id.",".$schexpid.",".$scheduleids[$j] ?>);" style="cursor:pointer; height:39px;">
              <div>
                  <div style="text-align:center"><?php echo $schexpname;?></div>
                  <div style="text-align:center"><?php echo $percentage." % ".$grade;?></div>
                  <div style="text-align:center"><?php echo $pointsearned." / ".$pointspossible;?></div>
              </div>
          </td>
          <?php
          $totalpointsearned = $totalpointsearned + $pointsearned;
          $totalpointspossible = $totalpointspossible + $pointspossible;																			
        }
        else
        {   ?>
            <td align="center">
                    <?php echo "No Expedition";?>
            </td><?php
        }
    }
}
/*******EXPEDITION sCHEDULE cODE End HERE*******/		

												
/**************************Expedition and Module schedule Code start here by Mohan**********************/
else if($type[$j]==22) 
{ 
    $incrementcountexp = $assid[$j];
    $totalcntexp = $maxassid[$j];

    for($z=$incrementcountexp;$z<=$totalcntexp;$z++)
    { 
        $rotid=$z+1;   
        $exptype='20';
        $modtype='21';
        $qryexpmodtype = $ObjDB->QueryObject("SELECT fld_module_id AS ids,fld_type AS typeid FROM itc_class_rotation_modexpschedulegriddet 
                                                            WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleids[$j]."' AND fld_rotation='".$rotid."' 
                                                            AND fld_student_id='".$fld_student_id."' AND fld_flag='1'");


        if($qryexpmodtype->num_rows > 0)
        { 	
            while($rowexpmodtype=$qryexpmodtype->fetch_assoc())
            {
                extract($rowexpmodtype);
                if($typeid=='1') //Module
                {
                    $l=$z;
                    $l++;

                    $qrymodexp = $ObjDB->QueryObject("SELECT fld_module_id AS modids,
                                                                (SELECT fld_module_name FROM itc_module_master WHERE fld_id=fld_module_id) AS modulename, 
                                                                1 AS newtype  FROM `itc_class_rotation_modexpschedulegriddet` 
                                                                WHERE fld_class_id='".$classid."' AND fld_student_id='".$fld_student_id."'  AND fld_schedule_id='".$scheduleids[$j]."' 
                                                                AND fld_module_id='".$ids."'  AND fld_rotation='".$l."' AND fld_flag='1' AND fld_type = '1' LIMIT 0,1
                                                      UNION ALL 	
                                                              SELECT a.fld_id AS modids, a.fld_contentname AS modulename, 8 AS newtype FROM itc_customcontent_master AS a 
                                                                      LEFT JOIN itc_class_rotation_modexpschedulegriddet AS b ON b.fld_module_id = a.fld_id 
                                                                      WHERE b.fld_class_id='".$classid."' AND b.fld_student_id='".$fld_student_id."' 
                                                                      AND b.fld_schedule_id = '".$scheduleids[$j]."'  AND fld_module_id='".$ids."' AND fld_rotation='".$l."' 
                                                                      AND b.fld_type = '8' AND b.fld_flag = '1' AND a.fld_delstatus = '0' ");

                    if($qrymodexp->num_rows>0)
                    {
                            while($rowqrymodexp = $qrymodexp->fetch_assoc()) // show the module based on number of copies
                            {
                                    extract($rowqrymodexp);

                                    $qrymodexppoints = $ObjDB->QueryObject("SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1'
                                                                                    THEN fld_teacher_points_earned END) AS pointsearned, SUM(fld_points_possible) AS pointspossible
                                                                                    FROM itc_module_points_master 
                                                                                    WHERE fld_student_id='".$fld_student_id."' AND fld_schedule_id='".$scheduleids[$j]."' 
                                                                                    AND fld_schedule_type='".$modtype."' 
                                                                                    AND fld_module_id='".$modids."' AND (fld_points_earned<>'' OR fld_teacher_points_earned<>'') 
                                                                                    AND fld_grade<>'0' AND fld_delstatus='0'");

                                    if($qrymodexppoints->num_rows>0)
                                    {
                                            $rowqrymodexppoints = $qrymodexppoints->fetch_assoc();
                                            extract($rowqrymodexppoints);
                                    }
                                    if($pointsearned=='')
                                    {
                                            $pointsearned = "-";
                                            $percentage = "-";
                                            $grade = "N/A";
                                    }
                                    else if($pointspossible!='')
                                    {
                                            if($roundflag==0)
                                                    $percentage = round(($pointsearned/$pointspossible)*100,2);
                                            else
                                                    $percentage = round(($pointsearned/$pointspossible)*100);

                                            $perarray = explode('.',$percentage);
                                            $grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
                                                                                                                    FROM itc_class_grading_scale_mapping 
                                                                                                                    WHERE fld_class_id = '".$classid."' 
                                                                                                                            AND fld_lower_bound <= '".$perarray[0]."' 
                                                                                                                            AND fld_upper_bound >= '".$perarray[0]."' 
                                                                                                                            AND fld_flag = '1'");
                                    }

                                    if($pointspossible=='')
                                            $pointspossible = "-";
                                    else
                                    {
                                            $totalpointsearned = $totalpointsearned + $pointsearned;
                                            $totalpointspossible = $totalpointspossible + $pointspossible;
                                    }
                                    ?>                                                
                                    <td onclick="fn_showiplpoints(<?php echo $modtype.",".$classid.",".$fld_student_id.",".$modids.",".$scheduleids[$j].",".$newtype;?>);" style="cursor:pointer; height:39px;">
                                            <div>
                                                    <div style="text-align:center"><?php echo $modulename;?></div>
                                                    <div style="text-align:center"><?php echo $percentage." % ".$grade;?></div>
                                                    <div style="text-align:center"><?php echo $pointsearned." / ".$pointspossible;?></div>
                                            </div>
                                    </td>
                                    <?php
                            }
                    }
                    else
                    { ?>
                            <td align="center">
                                    No Modules
                            </td><?php
                    }
                }
                else if($typeid=='2') //Expedition
                {
                    $pointsearned=0;
                    $pointspossible=0;
                    $expmodstudentcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                                FROM itc_class_rotation_modexpschedule_student_mappingtemp
                                                                                WHERE fld_schedule_id='".$scheduleids[$j]."' 
                                                                                                AND fld_student_id='".$fld_student_id."' 
                                                                                                AND fld_flag='1'");
                    if($expmodstudentcount!=0)
                    {
                        $expmodqry = $ObjDB->QueryObject("SELECT fld_module_id AS expid, 
                                                            (SELECT fld_exp_name FROM itc_exp_master WHERE fld_id=fld_module_id) AS expname  
                                                            FROM `itc_class_rotation_modexpschedulegriddet` 
                                                            WHERE fld_class_id='".$classid."' AND fld_module_id='".$ids."' AND fld_student_id='".$fld_student_id."' 
                                                            AND fld_schedule_id='".$scheduleids[$j]."' AND fld_rotation='".$rotid."'  AND fld_type='".$typeid."' 
                                                            AND fld_flag='1'  LIMIT 0,1");
                        $schexpid='';
                        $schexpname='';
                        if($expmodqry->num_rows>0)
                        {
                            $rowschexp=$expmodqry->fetch_assoc();
                            extract($rowschexp);
                            $schexpid=$expid;
                            $schexpname=$expname;
                        }

                        /************** Pre/Post test code start here ***************/
                        $pointsearnedfortest=0;
                        $possiblepointfortest1=0;
                        $possiblepointfortest=0;
                        
                        $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                            WHERE fld_class_id='".$classid."' AND fld_sch_id='".$scheduleids[$j]."' AND fld_texpid='".$schexpid."' AND fld_schtype_id='".$exptype."'");
          
                        if($qrytest->num_rows>0)
                        {
                            while($rowqrytest = $qrytest->fetch_assoc())
                            {
                                extract($rowqrytest);
                              
                                /*********Pre Test Code start Here*********/
                                if($pretest!='0')
                                {
                                    $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                                    FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_delstatus='0'");
                                    if($qry->num_rows>0)
                                    {
                                        while($rowqry = $qry->fetch_assoc())
                                        {
                                            extract($rowqry);
                                            if($questype==2)
                                            {
                                                $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                            }
                                            $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                            $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master 
                                                                                            WHERE fld_student_id='".$fld_student_id."' AND fld_exp_id='".$schexpid."' 
                                                                                            AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$exptype."' 
                                                                                            AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                            if(trim($tchpointcnt)=='')
                                            {
                                                $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                        WHERE b.fld_expt = '".$schexpid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                                        AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."'
                                                                                                        AND a.fld_schedule_type='".$exptype."'
                                                                                                        AND b.fld_delstatus = '0' AND a.fld_delstatus = '0'");

                                               if(trim($correctcountfortestattend) != '')
                                               {
                                                   $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                        WHERE b.fld_expt = '".$schexpid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                                        AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."'
                                                                                                        AND a.fld_schedule_type='".$exptype."'
                                                                                                        AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");
                                                   
                                                    $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                                    $possiblepointfortest+=$possiblepointfortest1;
                                               }
                                            }
                                            else
                                            {
                                                $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                                WHEN fld_lock = '0' THEN fld_points_earned
                                                                                                WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$fld_student_id."'
                                                                                                AND fld_exp_id='".$schexpid."' AND fld_schedule_id='".$scheduleids[$j]."' 
                                                                                                AND fld_schedule_type='".$exptype."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                                if($tchpointearn!='')
                                                {
                                                    $possiblepointfortest+=$possiblepointfortest1;
                                                }

                                                $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                            }
                                        }
                                    }
                                }
                                /*********Pre Test Code End Here*********/

                                /*********Post Test Code start Here*********/
                                if($posttest!='0')
                                {
                                    $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                                    FROM itc_test_master WHERE fld_id='".$posttest."' AND fld_delstatus='0'");
                                    if($qry->num_rows>0)
                                    {
                                        while($rowqry = $qry->fetch_assoc())
                                        {
                                            extract($rowqry);
                                            if($questype==2)
                                            {
                                                $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                            }
                                            $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                            $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master 
                                                                                            WHERE fld_student_id='".$fld_student_id."' AND fld_exp_id='".$schexpid."' 
                                                                                            AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$exptype."' 
                                                                                            AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                            if(trim($tchpointcnt)=='')
                                            {
                                                $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                        WHERE b.fld_expt = '".$schexpid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                                        AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."'
                                                                                                        AND a.fld_schedule_type='".$exptype."'
                                                                                                        AND b.fld_delstatus = '0' AND a.fld_delstatus = '0'");

                                               if(trim($correctcountfortestattend) != '')
                                               {
                                                   $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                        WHERE b.fld_expt = '".$schexpid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                                        AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."'
                                                                                                        AND a.fld_schedule_type='".$exptype."'
                                                                                                        AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");
                                                   
                                                    $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                                    $possiblepointfortest+=$possiblepointfortest1;
                                               }
                                            }
                                            else
                                            {
                                                $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                                WHEN fld_lock = '0' THEN fld_points_earned
                                                                                                WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$fld_student_id."'
                                                                                                AND fld_exp_id='".$schexpid."' AND fld_schedule_id='".$scheduleids[$j]."' 
                                                                                                AND fld_schedule_type='".$exptype."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                                if($tchpointearn!='')
                                                {
                                                    $possiblepointfortest+=$possiblepointfortest1;
                                                }

                                                $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                            }
                                        }
                                    }
                                }
                                /*********Post Test Code End Here*********/
                            }
                        }
                        /************** Pre/Post test code end here ***************/

                        /************** Rubric code start here ***************/
                        $pointsearnedrubric=0;
                        $pointspossiblerubric=0;

                        $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                            CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                            LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                            LEFT JOIN itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                                            LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
                                                                WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleids[$j]."' AND b.fld_exp_id='".$schexpid."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                                    AND a.fld_schedule_type='20' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                                    AND b.fld_school_id IN(0,".$schoolid.")");

                        if($qryrub->num_rows>0)
                        {
                            while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
                            {
                                extract($rowqryrub);
                                
                                 $destinationids = $ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_exp_rubric_dest_master WHERE fld_rubric_name_id='".$rubricids."' AND fld_exp_id='".$schexpid."'"); 

                                $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master 
                                                                        WHERE fld_exp_id='".$schexpid."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                                $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_expmodsch_rubric_rpt WHERE fld_exp_id='".$schexpid."'  
                                                                                    AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$classid."' 
                                                                                    AND fld_schedule_id='".$scheduleids[$j]."' AND fld_delstatus='0' "); 

                                $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_expmodsch_rubric_rpt_statement
                                                                            WHERE fld_student_id='".$fld_student_id."' AND fld_exp_id='".$schexpid."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
                                                                            AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                                $pointsearnedrubric = $pointsearnedrubric+$studentscore;
                                if($studentscore!=0)
                                {
                                    $pointspossiblerubric = $pointspossiblerubric+$totscore;
                                }
                            }
                        }

                        /************** Rubric code end here ***************/
                        $pointsearned=round($pointsearnedfortest + $pointsearnedrubric);
                        $pointspossible=$possiblepointfortest + $pointspossiblerubric;


                        //percentage code start here
                        if($pointsearned=='' AND $pointspossible!='0')
                        {
                            $pointsearned = "-";
                            $pointspossible="-";
                            $percentage = "-";
                            $grade = "NA";
                        }
                        else
                        {
                            if($roundflag==0)
                                $percentage = round(($pointsearned/$pointspossible)*100,2);
                            else
                                $percentage = round(($pointsearned/$pointspossible)*100);

                            $perarray = explode('.',$percentage);
                            $grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$classid."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
                        }
                        if($percentage==0)
                        {
                            $percentage = "-";
                            $grade = "NA";
                            $pointsearned = "-";
                            $pointspossible="-";
                        }
                        //percentage code end here

                        ?>                                                
                        <td onclick="fn_showiplpoints(<?php echo $exptype.",".$classid.",".$fld_student_id.",".$schexpid.",".$scheduleids[$j] ?>);" style="cursor:pointer; height:39px;">
                            <div>
                                <div style="text-align:center"><?php echo $schexpname;?></div>
                                <div style="text-align:center"><?php echo $percentage." % ".$grade;?></div>
                                <div style="text-align:center"><?php echo $pointsearned." / ".$pointspossible;?></div>
                            </div>
                        </td>
                        <?php																				
                    }
                    else
                    {   ?>
                        <td align="center">
                                <?php echo "No Expedition";?>
                        </td><?php
                    }
                    $totalpointsearned = $totalpointsearned + $pointsearned;
                    $totalpointspossible = $totalpointspossible + $pointspossible;
                    //   echo $totalpointsearned."|".$totalpointspossible."<br>";  
                }
            }
        }
        else
        {   ?>
            <td align="center">
                <div>
                    <div style="text-align:center">No</div>
                    <div style="text-align:center">Expeditions/Modules</div>
                </div>
            </td>
            <?php
        }
    }
}
/**************************Expedition and Module schedule Code End here by Mohan**********************/												
												
                                                                                                
/*********Mission report Code Start Here Developed By Mohan M 16-7-2015*************/	
else if($type[$j]==18) 
{ 
    $studentcountmis = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                FROM itc_class_mission_student_mapping 
                                                                WHERE fld_schedule_id='".$scheduleids[$j]."' 
                                                                                AND fld_student_id='".$fld_student_id."' 
                                                                                AND fld_flag='1'");
    
    if($studentcountmis!=0)
    {
		
        /*********Participation code Start here*******/
        $gradepointsearned = $ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned AS pointsearned FROM itc_mis_points_master 
                                                            WHERE fld_student_id='".$fld_student_id."' AND fld_mis_id='".$assid[$j]."' 
                                                                AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."' AND fld_mistype='4'
                                                                AND fld_grade='1' AND fld_delstatus='0'");

        $gradepointspossible = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_points_possible) AS pointspossible FROM itc_mis_points_master 
                                                                WHERE fld_student_id='".$fld_student_id."' AND fld_mis_id='".$assid[$j]."' 
                                                                    AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."'  AND fld_mistype='4' 
                                                                    AND fld_grade='1' AND fld_delstatus='0'");
        /*********Participation code end here*******/

        /************** Rubric code start here ***************/
        $pointsearnedrubric=0;
        $pointspossiblerubric=0;
        $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                            CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                LEFT JOIN itc_mis_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                LEFT JOIN itc_mission_master AS c ON a.fld_expmisid = c.fld_id
                                                LEFT JOIN itc_class_indasmission_master AS d ON a.fld_schedule_id=d.fld_id 
                                                    WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleids[$j]."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                        AND a.fld_schedule_type='18' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                        AND b.fld_school_id IN(0,".$schoolid.")");

        if($qryrub->num_rows>0)
        {
            while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
            {
                extract($rowqryrub);
                
                $destinationids = $ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_mis_rubric_dest_master WHERE fld_rubric_name_id='".$rubricids."' AND fld_mis_id='".$assid[$j]."'"); 

                $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_master 
                                                        WHERE fld_mis_id='".$assid[$j]."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_rubric_rpt WHERE fld_mis_id='".$assid[$j]."'  
                                                                AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$classid."' 
                                                                AND fld_schedule_id='".$scheduleids[$j]."' AND fld_delstatus='0' "); 

                $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_rpt_statement
                                                                    WHERE fld_student_id='".$fld_student_id."' AND fld_mis_id='".$assid[$j]."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
                                                                    AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                $pointsearnedrubric = $pointsearnedrubric+$studentscore;
                if($studentscore!=0)
                {
                    $pointspossiblerubric = $pointspossiblerubric+$totscore;
                }
            }
        }
        /************** Rubric code end here ***************/
		
        /************** Test code start here ***************/
        $pointsearnedfortest=0;
        $possiblepointfortest1=0;
        $possiblepointfortest=0;
        
        $qrytest = $ObjDB->QueryObject("SELECT fld_test_id AS pretest,fld_mis_id AS misid FROM itc_mis_ass
                                            WHERE fld_class_id='".$classid."' AND fld_sch_id='".$scheduleids[$j]."' 
											AND fld_schtype_id='".$type[$j]."' AND fld_flag='1'");

        if($qrytest->num_rows>0)
        {
            while($rowqrytest = $qrytest->fetch_assoc())
            {
                extract($rowqrytest);
                $exptype='3';
                
                $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                    FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_delstatus='0'");
                if($qry->num_rows>0)
                {
                    while($rowqry = $qry->fetch_assoc())
                    {
                        extract($rowqry);
                        if($questype==2)
                        {
                            $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                        }
                        $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0'");

                        $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_mis_points_master 
                                                                            WHERE fld_student_id='".$fld_student_id."' AND fld_mis_id='".$misid."' 
                                                                            AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."' 
                                                                            AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_mistype='3'");

                        if(trim($tchpointcnt)=='')
                        {
                            $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                        WHERE b.fld_mist = '".$misid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                        AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."' 
                                                                                        AND a.fld_schedule_type='".$type[$j]."' AND b.fld_delstatus = '0' AND a.fld_delstatus = '0'");

                            if(trim($correctcountfortestattend) != '')
                            {
                                $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                        WHERE b.fld_mist = '".$misid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                        AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."' 
                                                                                        AND a.fld_schedule_type='".$type[$j]."' AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");
                                
                                $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                $possiblepointfortest+=$possiblepointfortest1;
                            }
                        }
                        else
                        {
                            $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                WHEN fld_lock = '0' THEN fld_points_earned
                                                                                WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                END) AS pointsearned FROM itc_mis_points_master WHERE fld_student_id='".$fld_student_id."'
                                                                                AND fld_mis_id='".$misid."' AND fld_schedule_id='".$scheduleids[$j]."' 
                                                                                AND fld_schedule_type='".$type[$j]."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_mistype='3'");
                            if($tchpointearn!='')
                            {
                                $possiblepointfortest+=$possiblepointfortest1;
                            }
                            $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                        }
                    }
                }
            }
        }
        /************** Test code end here ***************/ 
		
        $pointsearned=$gradepointsearned+$pointsearnedrubric+$pointsearnedfortest;
        $pointspossible=$gradepointspossible+$pointspossiblerubric+$possiblepointfortest;

        if($pointsearned=='')
        {
            $pointsearned = "-";
            $percentage = "-";
            $grade = "NA";
        }
        else
        {
            $pointsearned = round($pointsearned);
            $totalpointsearned = $totalpointsearned + $pointsearned;

            if($roundflag==0)
                $percentage = round(($pointsearned/$pointspossible)*100,2);
            else
                $percentage = round(($pointsearned/$pointspossible)*100);

            $perarray = explode('.',$percentage);

            $grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$classid."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
        }

        if($pointspossible=='')
            $pointspossible = "-";
        else
            $totalpointspossible = $totalpointspossible + $pointspossible;
        ?>                                                
        <td onclick="fn_showiplpoints(<?php echo $type[$j].",".$classid.",".$fld_student_id.",".$assid[$j].",".$scheduleids[$j] ?>);" style="cursor:pointer; height:39px;">
            <div>
                <div style="text-align:center"><?php echo $percentage." % ".$grade;?></div>
                <div style="text-align:center"><?php echo $pointsearned." / ".$pointspossible;?></div>
            </div>
        </td>
        <?php
    }
    else
    { ?>
        <td align="center">
                <?php echo "No Mission";?>
        </td><?php
    }

}
												
else if($type[$j]==23) 
{ 
    $incrementcountexp = $assid[$j];
    $totalcntexp = $maxassid[$j];

    for($z=$incrementcountexp;$z<=$totalcntexp;$z++)
    { 
        $pointearned1=0;$pointearned2=0;
        $pointspossible1=0;$pointspossible2=0;
        $expstudentcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                FROM itc_class_rotation_mission_student_mappingtemp
                                                                WHERE fld_schedule_id='".$scheduleids[$j]."' 
                                                                        AND fld_student_id='".$fld_student_id."' 
                                                                        AND fld_flag='1'");
        if($expstudentcount!=0)
        {
            $rotid=$z+1;                             
            $qryexpsch = $ObjDB->QueryObject("SELECT fld_mission_id AS misid, (SELECT fld_mis_name FROM itc_mission_master WHERE fld_id=fld_mission_id) AS expname 
                                                    FROM itc_class_rotation_mission_schedulegriddet WHERE fld_class_id='".$classid."' AND fld_student_id='".$fld_student_id."' 
                                                    AND fld_schedule_id='".$scheduleids[$j]."' AND fld_rotation='".$rotid."' AND fld_flag='1'  LIMIT 0,1");
            if($qryexpsch->num_rows>0)
            {
                while($rowqryexpsch = $qryexpsch->fetch_assoc())
                {
                    extract($rowqryexpsch);

                    $pointearned1 = $ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned AS pointsearned FROM itc_mis_points_master 
                                                                    WHERE fld_student_id='".$fld_student_id."' AND fld_mis_id='".$misid."' 
                                                                        AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."' 
                                                                        AND fld_grade='1' AND fld_mistype='4'");
                  
                    /************** Rubric code start here ***************/
                    $pointsearnedrubric=0;
                    $pointspossiblerubric=0;

                    $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                        CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                        LEFT JOIN itc_mis_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                        LEFT JOIN itc_mission_master AS c ON a.fld_expmisid = c.fld_id
                                                        LEFT JOIN itc_class_rotation_mission_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
                                                            WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleids[$j]."' AND b.fld_mis_id='".$misid."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                                AND a.fld_schedule_type='19' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                                AND b.fld_school_id IN(0,".$schoolid.")");

                    if($qryrub->num_rows>0)
                    {
                        while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
                        {
                            extract($rowqryrub);
                            
                            $destinationids = $ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_mis_rubric_dest_master WHERE fld_rubric_name_id='".$rubricids."' AND fld_mis_id='".$misid."'"); 

                            $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_master 
                                                                        WHERE fld_mis_id='".$misid."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                            $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_missch_rubric_rpt WHERE fld_mis_id='".$misid."'  
                                                                            AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$classid."' 
                                                                            AND fld_schedule_id='".$scheduleids[$j]."' AND fld_delstatus='0' "); 

                            $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_missch_rubric_rpt_statement
                                                                            WHERE fld_student_id='".$fld_student_id."' AND fld_mis_id='".$misid."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
                                                                            AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                            $pointsearnedrubric = $pointsearnedrubric+$studentscore;
                            if($studentscore!=0)
                            {
								$pointspossiblerubric = $pointspossiblerubric+$totscore;
                            }
                        }
                    }

                    /************** Rubric code end here ***************/
					
					
                    /************** Test code start here ***************/
                    $pointsearnedfortest=0;
                    $possiblepointfortest1=0;
                    $possiblepointfortest=0;

                    $qrytest = $ObjDB->QueryObject("SELECT fld_test_id AS pretest,fld_mis_id AS misid FROM itc_mis_ass
                                                    WHERE fld_class_id='".$classid."' AND fld_sch_id='".$scheduleids[$j]."'  AND fld_mis_id='".$misid."'
                                                    AND fld_schtype_id='20' AND fld_flag='1'");

                    if($qrytest->num_rows>0)
                    {
                        while($rowqrytest = $qrytest->fetch_assoc())
                        {
                            extract($rowqrytest);
                            $exptype='3';

                            $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                                    FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_delstatus='0'");
                            if($qry->num_rows>0)
                            {
                                while($rowqry = $qry->fetch_assoc())
                                {
                                    extract($rowqry);
                                    if($questype==2)
                                    {
                                        $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                    }
                                    $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0'");

                                    $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_mis_points_master 
                                                                                        WHERE fld_student_id='".$fld_student_id."' AND fld_mis_id='".$misid."' 
                                                                                        AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='20' 
                                                                                        AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_mistype='3'");

                                    if(trim($tchpointcnt)=='')
                                    {
                                        $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                WHERE b.fld_mist = '".$misid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                                AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."' 
                                                                                                AND a.fld_schedule_type='20' AND b.fld_delstatus = '0' AND a.fld_delstatus = '0'");

                                        if(trim($correctcountfortestattend) != '')
                                        {
                                            $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                WHERE b.fld_mist = '".$misid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                                AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."' 
                                                                                                AND a.fld_schedule_type='20' AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");

                                                $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                                $possiblepointfortest+=$possiblepointfortest1;
                                       }
                                    }
                                    else
                                    {
                                        $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                                WHEN fld_lock = '0' THEN fld_points_earned
                                                                                                WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                END) AS pointsearned FROM itc_mis_points_master WHERE fld_student_id='".$fld_student_id."'
                                                                                                AND fld_mis_id='".$misid."' AND fld_schedule_id='".$scheduleids[$j]."' 
                                                                                                AND fld_schedule_type='20' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_mistype='3'");
                                        if($tchpointearn!='')
                                        {
                                                $possiblepointfortest+=$possiblepointfortest1;
                                                $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    /************** Test code end here ***************/
					
                    if($pointearned1=='0' || $pointearned1=='')
                    {
                        $peflag1=0;
                        $pointearned1='0';		
                    }
                    else
                    {
                        $peflag1=1;
                        $pointpossible1=100;	
                    }
					
                    $pointsearned = $pointearned1 + $pointsearnedrubric + $pointsearnedfortest;
                    $pointspossible = $pointpossible1 + $pointspossiblerubric + $possiblepointfortest;

                    if($pointsearned == '' || $pointsearned == '0')
                    {
                        $pointsearned = "-";
                        $percentage = "-";
                        $grade = "NA";
                        $pointspossible = "-";
                    }
                    else
                    {
                        $pointsearned = round($pointsearned);

                        if($roundflag==0)
                                $percentage = round(($pointsearned/$pointspossible)*100,2);
                        else
                                $percentage = round(($pointsearned/$pointspossible)*100);

                        $perarray = explode('.',$percentage);

                        $grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$classid."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
                    }

                     ?>                                                
                    <td onclick="fn_showiplpoints(<?php echo $type[$j].",".$classid.",".$fld_student_id.",".$misid.",".$scheduleids[$j] ?>);" style="cursor:pointer; height:39px;">
                        <div>
                            <div style="text-align:center"><?php echo $expname;?></div>
                            <div style="text-align:center"><?php echo $percentage." % ".$grade;?></div>
                            <div style="text-align:center"><?php echo $pointsearned." / ".$pointspossible;?></div>
                        </div>
                    </td>
                    <?php
                }	

            }
            $totalpointsearned = $totalpointsearned + $pointsearned;
            $totalpointspossible = $totalpointspossible + $pointspossible;
        }
        else
        {   ?>
            <td align="center">
                <?php echo "No Mission";?>
            </td>
            <?php
        }
    }
}												
/*********Mission report Code End Here Developed By Mohan M 16-7-2015*************/

else if($type[$j]==9) 
{
    $studentcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) 
                                                    FROM itc_test_student_mapping AS a
                                                    WHERE a.fld_class_id='".$classid."' 
                                                            AND a.fld_test_id='".$assid[$j]."' AND a.fld_flag='1'
                                                            AND a.fld_student_id='".$fld_student_id."' ".$sqry."");

    if($studentcount!=0)
    {
        $qrypoints = $ObjDB->QueryObject("SELECT a.fld_score AS score, a.fld_total_question,  a.fld_question_type 
                                                                        FROM itc_test_master AS a 
                                                                        LEFT JOIN itc_test_student_mapping AS b ON a.fld_id=b.fld_test_id 
                                                                        WHERE a.fld_flag='1' AND a.fld_delstatus='0' 
                                                                                AND b.fld_student_id='".$fld_student_id."' 
                                                                                AND b.fld_class_id='".$classid."' AND b.fld_flag='1' 
                                                                                AND a.fld_id='".$assid[$j]."'");
        if($qrypoints->num_rows>0)
        {
            $pointsearned = '';
            while($rowqrypoints = $qrypoints->fetch_object())
            {
                $pointspossible = $rowqrypoints->score;
                $totalques = $rowqrypoints->fld_total_question;
                $testtype = $rowqrypoints->fld_question_type;

                $qcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                    FROM itc_test_student_answer_track 
                                                                    WHERE fld_student_id='".$fld_student_id."' 
                                                                            AND fld_test_id='".$assid[$j]."' 
                                                                            AND fld_delstatus='0'  AND fld_schedule_id='0' AND fld_schedule_type='0'");

                $teacherpoint = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned 
                                                                    FROM itc_test_student_mapping 
                                                                    WHERE fld_student_id='".$fld_student_id."' 
                                                                            AND fld_test_id='".$assid[$j]."' 
                                                                            AND fld_flag='1' AND fld_class_id='".$classid."'");

                if($teacherpoint=='')
                {
                    if($testtype == '1')
                    {
                        /*  changes made from here by vijayalakshmi on 4th december 2014 **/
                        $correctcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) 
                                                                        FROM itc_test_student_answer_track 
                                                                        WHERE fld_student_id='".$fld_student_id."' 
                                                                                AND fld_test_id='".$assid[$j]."' 
                                                                                AND fld_correct_answer='1' 
                                                                                AND fld_delstatus='0' AND fld_result_flag<>'2'  AND fld_schedule_id='0' AND fld_schedule_type='0'");

                        $parialanscnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) FROM itc_test_student_answer_track 
                                                                            WHERE fld_student_id='".$fld_student_id."' AND fld_test_id='".$assid[$j]."' AND fld_delstatus='0' AND fld_result_flag='2'  AND fld_schedule_id='0' AND fld_schedule_type='0'");

                        $parialcnt = $ObjDB->QueryObject("SELECT fld_correct_answer as partans FROM itc_test_student_answer_track 
                                                                WHERE fld_student_id='".$fld_student_id."' AND fld_test_id='".$assid[$j]."' AND fld_delstatus='0' AND fld_result_flag='2'  AND fld_schedule_id='0' AND fld_schedule_type='0'");
                        if ($parialcnt->num_rows > 0) {
                            $dummyans_pt = 0;
                            while ($rowqrypartialscore = $parialcnt->fetch_assoc()) {
                                extract($rowqrypartialscore);

                                $dummyans_pt = $dummyans_pt + $partans;
                            }
                        }

                        $pointsearned = round(($correctcount / $totalques) * $pointspossible);

                        if ($dummyans_pt != 0) {
                            $pointsearned = $pointsearned + $dummyans_pt;
                        }
                        //$dummyans_pt = 0;
                    }
                    else if($testtype == '2')
                    {

                        $qryrandomtest = $ObjDB->QueryObject("SELECT fld_id AS testtagid, fld_pct_section AS percent, fld_qn_assign AS totques
                                                                FROM itc_test_random_questionassign
                                                                WHERE fld_rtest_id='".$assid[$j]."' AND fld_delstatus='0' 
                                                                ORDER BY fld_order_by");
                        if($qryrandomtest->num_rows>0)
                        {

                            $perscore = 0;
                            while($rowqryrandomtest = $qryrandomtest->fetch_assoc())
                            {
                                extract($rowqryrandomtest);
                               // $perscore = ($percent / 100)*$pointspossible;
                                $perscore = $perscore + $percent;

                                $correctcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) 
                                                                                FROM itc_test_student_answer_track 
                                                                                WHERE fld_student_id='".$fld_student_id."' AND fld_test_id='".$assid[$j]."' AND fld_tag_id='".$testtagid."'
                                                                                        AND fld_correct_answer='1' AND fld_delstatus='0' AND fld_result_flag<>'2'  AND fld_schedule_id='0' AND fld_schedule_type='0'");

                                $parialanscnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) FROM itc_test_student_answer_track 
                                                                                WHERE fld_student_id='".$fld_student_id."' AND fld_test_id='".$assid[$j]."' AND fld_tag_id='".$testtagid."' AND fld_delstatus='0' AND fld_result_flag='2'  AND fld_schedule_id='0' AND fld_schedule_type='0'");

                                $parialcnt = $ObjDB->QueryObject("SELECT fld_correct_answer as partans FROM itc_test_student_answer_track 
                                                                    WHERE fld_student_id='".$fld_student_id."' AND fld_test_id='".$assid[$j]."' AND fld_tag_id='".$testtagid."' AND fld_delstatus='0' AND fld_result_flag='2'  AND fld_schedule_id='0' AND fld_schedule_type='0'");

                                if ($parialcnt->num_rows > 0) {
                                    $dummyans_pt_rand = 0;
                                    while ($rowqrypartialscore = $parialcnt->fetch_assoc()) {
                                        extract($rowqrypartialscore);

                                        $dummyans_pt_rand = $dummyans_pt_rand + $partans;
                                    }
                                }

                                $pointsearned = $pointsearned + round($correctcount * ($percent / $totques));

                                if ($dummyans_pt_rand != 0) {
                                    $pointsearned = $pointsearned + $dummyans_pt_rand;
                                }

                                $dummyans_pt_rand = 0;
                            }
                        }
                        $pointsearned = round(($pointsearned/$perscore) * $pointspossible);
                        /*  changes made from here by vijayalakshmi on 4th december 2014 (end) **/

                    }
                    $showcount = $qcount;
                }
                else
                {
                    $pointsearned = $teacherpoint;
                    $showcount = 1;
                }

                if($showcount==0)
                {
                    $pointsearned = "-";
                    $pointspossible = "-";
                    $percentage = "-";
                    $grade = "NA";
                }
                else
                {
                    if($roundflag==0)
                        $percentage = round(($pointsearned/$pointspossible)*100,2);
                    else
                        $percentage = round(($pointsearned/$pointspossible)*100);

                    $perarray = explode('.',$percentage);
                    $grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$classid."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
                }
            }
            if($qcount!=0 || $teacherpoint!='')
            {
                $totalpointsearned = $totalpointsearned + $pointsearned;
                $totalpointspossible = $totalpointspossible + $pointspossible;
            }                                                                                                                                
            ?>
            <td onclick="fn_showiplpoints(<?php echo $type[$j].",".$classid.",".$fld_student_id.",".$assid[$j].",".$scheduleids[$j] ?>);" style="cursor:pointer; height:39px;">
                <div>
                    <div style="text-align:center"><?php echo $percentage." %  ".$grade;?></div>
                    <div style="text-align:center"><?php echo $pointsearned." / ".$pointspossible;?></div>
                </div>
            </td>
            <?php
        }
    }
    else
    {   ?>
        <td align="center">
            No Assessment
        </td><?php
    }
}

else if($type[$j]==10) 
{
    $studentcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_activity_student_mapping WHERE fld_class_id='".$classid."' AND fld_student_id='".$fld_student_id."' AND fld_activity_id='".$assid[$j]."' AND fld_flag='1'");

    if($studentcount!=0)
    {
        $qrypoints = $ObjDB->QueryObject("SELECT SUM(fld_points_earned) AS pointsearned, SUM(fld_points_possible) AS pointspossible FROM itc_activity_student_mapping WHERE fld_class_id='".$classid."' AND fld_student_id='".$fld_student_id."' AND fld_activity_id='".$assid[$j]."' AND fld_flag='1' AND fld_points_earned<>''");
        if($qrypoints->num_rows>0)
        {
            while($rowqrypoints = $qrypoints->fetch_assoc()) // show the module based on number of copies
            {
                extract($rowqrypoints);
                if($pointsearned=='')
                {
                    $pointsearned = "-";
                    $percentage = "-";
                    $grade = "NA";
                }
                else
                {
                    $totalpointsearned = $totalpointsearned + $pointsearned;
                    if($roundflag==0)
                        $percentage = round(($pointsearned/$pointspossible)*100,2);
                    else
                        $percentage = round(($pointsearned/$pointspossible)*100);

                    $perarray = explode('.',$percentage);
                    $grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$classid."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
                }

                if($pointspossible=='')
                        $pointspossible = "-";
                else
                        $totalpointspossible = $totalpointspossible + $pointspossible;
                ?>
                <td onclick="fn_showiplpoints(<?php echo $type[$j].",".$classid.",".$fld_student_id.",".$assid[$j].",".$scheduleids[$j] ?>);" style="cursor:pointer; height:39px;">
                    <div>
                        <div style="text-align:center"><?php echo $percentage." %  ".$grade;?></div>
                        <div style="text-align:center"><?php echo $pointsearned." / ".$pointspossible;?></div>
                    </div>
                </td>
                <?php
            }
        }
    }
    else
    {   ?>
        <td align="center">
            No Activity
        </td><?php
    }
} 
                                                                                                
else 
{
    $oriencunt = 0;
    if($type[$j]==2)
    {
        $schegridtable = "itc_class_dyad_schedulegriddet";
        $schestudenttable = "itc_class_dyad_schedule_studentmapping";										
        $schname = "Dyad";
    }
    if($type[$j]==3)
    {
        $schegridtable = "itc_class_triad_schedulegriddet";
        $schestudenttable = "itc_class_triad_schedule_studentmapping";										
        $schname = "Triad";
    }
    if($type[$j]==2 or $type[$j]==3)
    {
            $oriencunt = $ObjDB->SelectSingleValue("SELECT COUNT(a.fld_rotation) FROM ".$schegridtable." AS a 
                                                        LEFT JOIN ".$schestudenttable." AS b ON (a.fld_schedule_id=b.fld_schedule_id 
                                                                        AND b.fld_student_id='".$fld_student_id."')
                                                        WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleids[$j]."' AND a.fld_flag='1' 
                                                                AND a.fld_rotation='0' AND b.fld_flag='1' ".$sqry1."");
    }

    if($oriencunt==1)
    {
        $incrementcount = $assid[$j];
        $totalcnt = $maxassid[$j];
    }
    else
    {
        $incrementcount = $assid[$j];
        $totalcnt = $maxassid[$j];
    }

    for($k=$incrementcount;$k<=$totalcnt;$k++)
    {
        if($type[$j]==1 OR $type[$j]==8) 
        {
            $l=$k;
            $l++;

            $qrymod = $ObjDB->QueryObject("SELECT fld_module_id AS modids, (SELECT fld_module_name FROM itc_module_master WHERE fld_id=fld_module_id) AS modulename, 1 AS newtype  FROM `itc_class_rotation_schedulegriddet` WHERE fld_class_id='".$classid."' AND fld_student_id='".$fld_student_id."' AND fld_schedule_id='".$scheduleids[$j]."' AND fld_rotation='".$l."' AND fld_flag='1' AND fld_type = '1'

            UNION ALL 		SELECT a.fld_id AS modids, a.fld_contentname AS modulename, 8 AS newtype FROM itc_customcontent_master AS a LEFT JOIN itc_class_rotation_schedulegriddet AS b ON b.fld_module_id = a.fld_id WHERE b.fld_class_id='".$classid."' AND b.fld_student_id='".$fld_student_id."' AND b.fld_schedule_id = '".$scheduleids[$j]."' AND fld_rotation='".$l."' AND b.fld_type = '8' AND b.fld_flag = '1' AND a.fld_delstatus = '0' ");
        }
        else if($type[$j]==2) 
        {
            $dyad=$k;
            $qrymod = $ObjDB->QueryObject("SELECT fld_module_id AS modids, (SELECT fld_module_name FROM itc_module_master WHERE fld_id=fld_module_id) AS modulename, 2 AS newtype FROM `itc_class_dyad_schedulegriddet` WHERE fld_class_id='".$classid."' AND (fld_student_id='".$fld_student_id."' OR fld_rotation='0') AND fld_schedule_id='".$scheduleids[$j]."' AND fld_rotation='".$dyad."' AND fld_flag='1' LIMIT 0,1");
        }
        else if($type[$j]==3) 
        {
            $triad=$k;
            $qrymod = $ObjDB->QueryObject("SELECT fld_module_id AS modids, (SELECT fld_module_name FROM itc_module_master WHERE fld_id=fld_module_id) AS modulename, 3 AS newtype FROM `itc_class_triad_schedulegriddet` WHERE fld_class_id='".$classid."' AND (fld_student_id='".$fld_student_id."' OR fld_rotation='0') AND fld_schedule_id='".$scheduleids[$j]."' AND fld_rotation='".$triad."' AND fld_flag='1' LIMIT 0,1");
        }
        else if($type[$j]==4) 
        {
            $l=$k;
            $l++;

            $qrymod = $ObjDB->QueryObject("SELECT a.fld_module_id AS modids, CONCAT((SELECT fld_mathmodule_name FROM itc_mathmodule_master WHERE fld_id=a.fld_module_id),' MM') AS modulename, 4 AS newtype FROM `itc_class_rotation_schedulegriddet` AS a WHERE a.fld_class_id='".$classid."' AND a.fld_student_id='".$fld_student_id."' AND a.fld_schedule_id='".$scheduleids[$j]."' AND a.fld_rotation='".$l."' AND a.fld_flag='1' AND a.fld_type='2' LIMIT 0,1
            UNION ALL 		SELECT a.fld_id AS modids, a.fld_contentname AS modulename, 8 AS newtype FROM itc_customcontent_master AS a LEFT JOIN itc_class_rotation_schedulegriddet AS b ON b.fld_module_id = a.fld_id WHERE b.fld_class_id='".$classid."' AND b.fld_student_id='".$fld_student_id."' AND b.fld_schedule_id = '".$scheduleid[$j]."' AND fld_rotation='".$l."' AND b.fld_type = '8' AND b.fld_flag = '1' AND a.fld_delstatus = '0'");
        }
        if($qrymod->num_rows>0)
        {
            while($rowqrymod = $qrymod->fetch_assoc()) // show the module based on number of copies
            {
                extract($rowqrymod);

                if($newtype==4)
                {
                    $qrymath = $ObjDB->QueryObject("SELECT fld_ipl_day1 AS ipld1, fld_ipl_day2 AS ipld2
                                                                                    FROM itc_mathmodule_master 
                                                                                    WHERE fld_id='".$modids."'");
                    $rowqrymath=$qrymath->fetch_assoc();
                    extract($rowqrymath);
                }

                if($newtype==4)
                {
                    $qrypoints = $ObjDB->QueryObject("SELECT SUM(w.earned) AS pointsearned, SUM(w.possible) AS pointspossible FROM ((SELECT SUM(CASE WHEN fld_lock = '0' THEN fld_points_earned WHEN fld_lock = '1' THEN fld_teacher_points_earned END) AS earned, SUM(fld_points_possible) AS possible FROM itc_module_points_master WHERE fld_student_id = '".$fld_student_id."' AND fld_schedule_id = '".$scheduleids[$j]."' AND fld_schedule_type = '".$newtype."' AND fld_module_id = '".$modids."' AND (fld_points_earned<>'' OR fld_teacher_points_earned<>'') AND fld_grade<>'0' AND fld_delstatus='0')	
                                                        UNION ALL 		
                                                        (SELECT ROUND(SUM(CASE WHEN a.fld_lock = '0' THEN a.fld_points_earned WHEN a.fld_lock = '1' 
                                                            THEN a.fld_teacher_points_earned END) / 4) AS earned, ROUND(SUM(a.fld_points_possible) / 4) AS possible 
                                                            FROM itc_assignment_sigmath_master AS a 
                                                            WHERE a.fld_student_id = '".$fld_student_id."' AND a.fld_test_type = '2' 
                                                            AND a.fld_schedule_id = '".$scheduleids[$j]."' and a.fld_module_id='".$modids."'
                                                            AND (a.fld_lesson_id IN (".$ipld1.") OR a.fld_lesson_id IN (".$ipld2.")) 
                                                            AND (a.fld_points_earned <> '' OR a.fld_teacher_points_earned <> '') AND a.fld_delstatus='0' 
                                                            AND (a.fld_status='1' OR a.fld_status='2' OR a.fld_lock='1') AND a.fld_unitmark = '0')) AS w");
                }

                else
                    $qrypoints = $ObjDB->QueryObject("SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END) AS pointsearned, SUM(fld_points_possible) AS pointspossible FROM itc_module_points_master WHERE fld_student_id='".$fld_student_id."' AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$newtype."' AND fld_module_id='".$modids."' AND (fld_points_earned<>'' OR fld_teacher_points_earned<>'') AND fld_grade<>'0' AND fld_delstatus='0'");

                if($qrypoints->num_rows>0)
                {
                    $rowqrypoints = $qrypoints->fetch_assoc();
                    extract($rowqrypoints);
                }
                if($pointsearned=='')
                {
                    $pointsearned = "-";
                    $percentage = "-";
                    $grade = "N/A";
                }
                else if($pointspossible!='')
                {
                    if($roundflag==0)
                            $percentage = round(($pointsearned/$pointspossible)*100,2);
                    else
                            $percentage = round(($pointsearned/$pointspossible)*100);

                    $perarray = explode('.',$percentage);
                    $grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
                                                                FROM itc_class_grading_scale_mapping 
                                                                WHERE fld_class_id = '".$classid."' 
                                                                        AND fld_lower_bound <= '".$perarray[0]."' 
                                                                        AND fld_upper_bound >= '".$perarray[0]."' 
                                                                        AND fld_flag = '1'");
                }

                if($pointspossible=='')
                    $pointspossible = "-";
                else
                {
                    $totalpointsearned = $totalpointsearned + $pointsearned;
                    $totalpointspossible = $totalpointspossible + $pointspossible;
                }
                ?>                                                
                <td onclick="fn_showiplpoints(<?php echo $newtype.",".$classid.",".$fld_student_id.",".$modids.",".$scheduleids[$j] ?>);" style="cursor:pointer; height:39px;">
                    <div>
                        <div style="text-align:center"><?php echo $modulename;?></div>
                        <div style="text-align:center"><?php echo $percentage." % ".$grade;?></div>
                        <div style="text-align:center"><?php echo $pointsearned." / ".$pointspossible;?></div>
                    </div>
                </td>
                <?php
            }
        }
        else
        {   ?>
            <td align="center">
                    No Modules
            </td><?php
        }
    }
}
                                        }
                                    }
                                    else
                                    {
                                        ?>
                                        <td align="center">No Record</td>
                                        <?php
                                    }
                                    if($totalpointsearned=='')
                                    {
                                        $totalpointsearned = "-";
                                        $totalpercentage = "-";
                                        $totalgrade = "NA";
                                    }
                                    else
                                    {
                                        if($roundflag==0)
                                            $totalpercentage = round(($totalpointsearned/$totalpointspossible)*100,2);
                                        else
                                            $totalpercentage = round(($totalpointsearned/$totalpointspossible)*100);

                                        $perarray = explode('.',$totalpercentage);
                                        $totalgrade = $ObjDB->SelectSingleValue("SELECT fld_grade 
                                                                                    FROM itc_class_grading_scale_mapping 
                                                                                    WHERE fld_class_id = '".$classid."' 
                                                                                                    AND fld_lower_bound <= '".$perarray[0]."' 
                                                                                                    AND fld_upper_bound >= '".$perarray[0]."' 
                                                                                                    AND fld_flag = '1'");
                                    }
                                    if($totalpointspossible=='')
                                        $totalpointspossible = "-";
                                    ?>
                                    <td align="center" style="font-size:14px; font-weight:bold"><div><div>&nbsp;</div><div style="text-align:center"><?php echo $totalpointsearned;?></div></div></td>
                                    <td align="center" style="font-size:14px; font-weight:bold"><div><div>&nbsp;</div><div style="text-align:center"><?php echo $totalpointspossible;?></div></div></td>
                                    <td align="center" style="font-size:14px; font-weight:bold"><div><div>&nbsp;</div><div style="text-align:center"><?php echo $totalpercentage." %";?></div></div></td>
                                    <td align="center" style="font-size:14px; font-weight:bold"><div><div>&nbsp;</div><div style="text-align:center"><?php echo $totalgrade;?></div></div></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            	</div>
                <script language="javascript" type="text/javascript">
                    
                        $('#myTable06').fixedHeaderTable({ fixedColumns: 1 });
                        $('#myTable06').fixedHeaderTable('destroy');	
                        $('#myTable06').fixedHeaderTable({fixedColumn: true });
                        if($("#myTable06 th").length == 2)
                        {
                            $('.teststu').css('width','410px');
                            $('#student1').css('width','414px');
                            $('.testipl').css('width','410px');
                            $('.testmod').css('width','410px');
                            $('#testrec').css('width','565px');
                            $('.fht-tbody').css('height','315.5px');
                            $('.fht-fixed-column').css('width','422.5px');                                            
                        }

                        else if($("#myTable06 th").length == 3)
                        {
                            $('#student1').css('width','274px');
                            $('#teststu').css('width','300px');
                            $('.testipl').css('width','300px');
                            $('.testmod').css('width','300px');
                            $('.fht-tbody').css('height','315.5px');
                        }
                        
                        setTimeout("$('#myTable06').parent('div').scrollTop(<?php echo $top;?>)",1000)
			setTimeout("$('#myTable06').parent('div').scrollLeft(<?php echo $left;?>)",1200);
                        
                        $('#myTable05').fixedHeaderTable({ fixedColumns: 1 });
                        $('#myTable05').fixedHeaderTable('destroy');	
                        $('#myTable05').fixedHeaderTable({fixedColumn: true });
                        if($('#hidrowvalue').val()<='0')
                        {
                            $('#student').css('width','405px');
                        }
                        
                        $(".gradehead").css({"width":"212px"});
                </script>
            </div>
            
            <div class='four columns'>&nbsp;</div>
            <div id="save" class='four columns btn secondary yesNo'>
                <?php if($gradeperiodid!=0) $gradename=$fld_grade_name; else $gradename="Overall Grade Book";?>
                <a onclick="fn_gradebookexport(<?php echo "2,".$classid.","."'".$gradename."',".$gradeperiodid ;?>);">Export as csv</a>
        </div>
    </div>
    </div>
    
    <script type="text/javascript" language="javascript">
        $("#startdate").datepicker( {
                onSelect: function(dateText,inst){
                        $(this).parents().parents().removeClass('error');
                }
        });

        $("#enddate").datepicker( {
                onSelect: function(dateText,inst){
                        $(this).parents().parents().removeClass('error');
                }
        });

        //Function to validate the form
        $(function(){
                $("#frmgrade1").validate({
                        ignore: "",
                        errorElement: "dd",
                        errorPlacement: function(error, element) {
                                $(element).parents('dl').addClass('error');
                                error.appendTo($(element).parents('dl'));
                                error.addClass('msg');
                                window.scroll(0,($('dd').offset().top)-50);
                        },
                        rules: { 
                                txtgradename1: { required: true, lettersonly: true },
                                startdate: { required: true },
                                enddate: { required: true, greaterThan: "#startdate" }
                        }, 
                        messages: { 
                                txtgradename1: { required: "Please type Grade Period Name", lettersonly:"Please enter letters and numbers only" },
                                startdate:{  required: "Select the start date" },
                                enddate:{  required: "Select the end date", greaterThan: "Must be greater than Start date." }
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

        $(function(){
                var tabindex = 1;
                $('input,select').each(function() {
                        if (this.type != "hidden") {
                                var $input = $(this);
                                $input.attr("tabindex", tabindex);
                                tabindex++;
                        }
                });
        });
    </script>
</section>
<?php
@include("footer.php");