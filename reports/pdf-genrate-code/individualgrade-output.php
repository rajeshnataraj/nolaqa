<?php
@include("table.class.php");
@include("comm_func.php");
$method = $_REQUEST;

$id = isset($method['id']) ? $method['id'] : '0';
$id = explode(",",$id);

$startdate = date('Y-m-d',strtotime($id[4]));
$enddate = date('Y-m-d',strtotime($id[5]));


$sqry = "AND ('".$startdate."' BETWEEN b.fld_start_date AND b.fld_end_date OR '".$enddate."' BETWEEN b.fld_start_date AND b.fld_end_date OR b.fld_start_date BETWEEN '".$startdate."' AND '".$enddate."' OR b.fld_end_date BETWEEN '".$startdate."' AND '".$enddate."')";
$sqry1 = " AND ('".$startdate."' BETWEEN b.fld_startdate AND b.fld_enddate OR '".$enddate."' BETWEEN b.fld_startdate AND b.fld_enddate OR b.fld_startdate BETWEEN '".$startdate."' AND '".$enddate."' OR b.fld_enddate BETWEEN '".$startdate."' AND '".$enddate."')";
$sqry2 = " AND ('".$startdate."' BETWEEN c.fld_startdate AND c.fld_enddate OR '".$enddate."' BETWEEN c.fld_startdate AND c.fld_enddate OR c.fld_startdate BETWEEN '".$startdate."' AND '".$enddate."' OR c.fld_enddate BETWEEN '".$startdate."' AND '".$enddate."')";
$sqry3 = " AND ('".$startdate."' BETWEEN a.fld_startdate AND a.fld_enddate OR '".$enddate."' BETWEEN a.fld_startdate AND a.fld_enddate OR a.fld_startdate BETWEEN '".$startdate."' AND '".$enddate."' OR a.fld_enddate BETWEEN '".$startdate."' AND '".$enddate."')";

$start = isset($method['start']) ? $method['start'] : '0';
$end = isset($method['end']) ? $method['end'] : '5';
$limit = $start.",".$end;
?>
<style>
	.title
	{
		font-size: 50px; font-weight:bold; font-family:Arial;
	}
	.trgray
	{
		font-size:30px; background-color:#CCCCCC; font-weight:normal; 
	}
	.trclass
	{
		font-size:30px; background-color:#FFFFFF; font-weight:normal;
	}
	.tdleft{
		border-top:1px solid #b4b4b4; border-left:1px solid #b4b4b4; border-bottom:1px solid #b4b4b4;
	}
	
	.tdmiddle{
		border-top:1px solid #b4b4b4; border-bottom:1px solid #b4b4b4;
	}
	
	.tdright{
		border-top:1px solid #b4b4b4; border-right:1px solid #b4b4b4; border-bottom:1px solid #b4b4b4;
	}
</style>
<?php 
if($id[1]==0)
{
	$qrystudents = $ObjDB->QueryObject("SELECT CONCAT(a.fld_fname,' ',a.fld_lname) AS studentname, a.fld_id AS studentid 
										FROM itc_user_master AS a 
										LEFT JOIN itc_class_student_mapping AS b ON a.fld_id=b.fld_student_id 
										WHERE a.fld_activestatus='1' AND a.fld_delstatus='0' 
										AND b.fld_class_id='".$id[2]."' AND b.fld_flag='1' 
										ORDER BY a.fld_lname
										LIMIT ".$limit."");
}
else
{
	$qrystudents = $ObjDB->QueryObject("SELECT CONCAT(fld_fname,' ',fld_lname) AS studentname, fld_id AS studentid 
										FROM itc_user_master 
										WHERE fld_id='".$id[1]."'");
}
$roundflag = $ObjDB->SelectSingleValue("SELECT fld_roundflag 
										FROM itc_class_grading_scale_mapping 
										WHERE fld_class_id = '".$id[2]."' AND fld_flag = '1' 
										GROUP BY fld_roundflag");

if($qrystudents->num_rows > 0)
{ 
	$count = 0;	 
	$cnt=0;
	while($rowqrystudents=$qrystudents->fetch_assoc())
	{
		extract($rowqrystudents);	
		
                $expearned = '';
                $exppossible = '';

                $qryexp = $ObjDB->QueryObject("SELECT b.fld_id AS scheduleid, b.fld_exp_id AS expid, c.fld_pointspossible, c.fld_exptype, 
                                                        IFNULL((SELECT CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
                                                                THEN fld_teacher_points_earned END AS pointsearned
                                                        FROM itc_exp_points_master 
                                                        WHERE fld_student_id='".$studentid."' AND fld_exp_id=b.fld_exp_id AND fld_grade='1' 
                                                        AND fld_exptype=c.fld_exptype AND fld_schedule_id=b.fld_id AND fld_schedule_type='15' 
                                                        AND (fld_points_earned<>'' OR fld_teacher_points_earned<>'') ),'-') AS pearned
                                                FROM itc_exp_master AS a 
                                                LEFT JOIN itc_class_indasexpedition_master AS b ON b.fld_exp_id=a.fld_id 
                                                LEFT JOIN itc_class_exp_grade AS c ON (c.fld_schedule_id=b.fld_id AND c.fld_exp_id=b.fld_exp_id)
                                                WHERE b.fld_class_id='".$id[2]."' AND b.fld_flag='1' AND b.fld_delstatus='0' 
                                                        AND a.fld_delstatus='0' AND c.fld_flag='1' AND c.fld_exptype<>'1' ".$sqry1."
                                                GROUP BY b.fld_id, c.fld_exptype");
                if($qryexp->num_rows>0)
                {
                        $exptearned = '';
                        $exptpossible = '';
                        while($rowqryexp = $qryexp->fetch_assoc())
                        {
                                extract($rowqryexp);

                                if($pearned==='-')
                                {
                                        if($fld_exptype==3)
                                        {
                                                $qryques = $ObjDB->QueryObject("SELECT IFNULL(b.fld_total_question,'-') AS quescount, COUNT(a.fld_id) AS correctcount FROM itc_test_student_answer_track AS a LEFT JOIN itc_test_master AS b ON a.fld_test_id=b.fld_id WHERE b.fld_expt='".$expid."' AND a.fld_student_id='".$studentid."' AND b.fld_delstatus='0' AND a.fld_show='1' AND a.fld_delstatus='0'");

                                                if($qryques->num_rows>0)
                                                {
                                                        $rowqryques = $qryques->fetch_assoc();
                                                        extract($rowqryques);

                                                        if($quescount==='-')
                                                        {
                                                                $pointsearned = '';
                                                                $pointspossible = '';
                                                        }
                                                        else {
                                                                $expearned = $expearned+$correctcount*($fld_pointspossible/$quescount);
                                                                $exppossible = $exppossible+$fld_pointspossible;
                                                        }
                                                }
                                        }
                                        else
                                        {
                                                $expearned = '';
                                                $exppossible = '';
                                        }
                                }
                                else
                                {
                                        $exptearned = $exptearned+$expearned+$pearned;
                                        $exptpossible = $exptpossible+$exppossible+$fld_pointspossible;										
                                }
                        }
                }
                else
                {
                        $exptearned = '';
                        $exptpossible = '';
                }
                
		$qrypoints = $ObjDB->QueryObject("SELECT SUM(a.pointsearned) AS earned, SUM(a.pointspossible) AS possible FROM (
                                                    (SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible FROM itc_assignment_sigmath_master AS a LEFT JOIN itc_class_sigmath_master AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_id) WHERE b.fld_class_id='".$id[2]."' AND a.fld_student_id='".$studentid."' AND a.fld_test_type='1' AND b.fld_flag='1' AND b.fld_delstatus='0' AND (a.fld_status='1' OR a.fld_status='2' OR a.fld_lock='1' OR a.fld_unitmark='1') AND a.fld_delstatus='0' AND a.fld_grade<>'0' ".$sqry.") 		
												UNION ALL		
										(SELECT ROUND(SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
THEN a.fld_teacher_points_earned END)/4) AS pointsearned, ROUND(SUM(a.fld_points_possible)/4) AS pointspossible 
FROM `itc_assignment_sigmath_master` AS a
LEFT JOIN itc_class_rotation_schedulegriddet AS b ON (a.fld_class_id=b.fld_class_id 
AND a.fld_schedule_id=b.fld_schedule_id and a.fld_module_id=b.fld_module_id) 
                                                    LEFT JOIN itc_class_rotation_scheduledate AS c ON b.fld_schedule_id=c.fld_schedule_id and b.fld_rotation=c.fld_rotation 
WHERE b.fld_class_id = '".$id[2]."' AND a.fld_student_id = '".$studentid."' 
AND b.fld_student_id='".$studentid."' AND a.fld_test_type='2' AND b.fld_flag='1' 
                                                    AND a.fld_delstatus='0' and b.fld_type='2' AND (a.fld_status='1' OR a.fld_status='2' OR a.fld_lock='1') AND c.fld_flag='1' ".$sqry2."
GROUP BY a.fld_schedule_id)		
                                                                                                UNION ALL
                                                                                (SELECT ROUND(SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
THEN a.fld_teacher_points_earned END)/4) AS pointsearned, ROUND(SUM(a.fld_points_possible)/4) AS pointspossible 
FROM itc_assignment_sigmath_master AS a 
LEFT JOIN itc_class_indassesment_master AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_id)
WHERE b.fld_class_id='".$id[2]."' AND a.fld_student_id='".$studentid."' AND a.fld_test_type = '5' 
AND b.fld_moduletype='2' AND b.fld_flag='1' AND b.fld_delstatus='0' 
                                                    AND a.fld_delstatus='0' AND (a.fld_status='1' OR a.fld_status='2' OR a.fld_lock='1') ".$sqry1." GROUP BY a.fld_schedule_id)
												UNION ALL		
										(SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
										THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible 
										FROM itc_module_points_master AS a 
										LEFT JOIN itc_class_rotation_schedulegriddet AS b ON (a.fld_schedule_id=b.fld_schedule_id 
											AND a.fld_module_id=b.`fld_module_id` AND a.fld_student_id=b.fld_student_id) 
													LEFT JOIN itc_class_rotation_scheduledate AS c ON b.fld_schedule_id=c.fld_schedule_id 
													and b.fld_rotation=c.fld_rotation 
										WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$id[2]."' AND a.fld_delstatus='0' 
                                                    AND b.fld_flag='1' AND a.fld_grade<>'0' AND a.fld_schedule_type IN (1,4,8) AND c.fld_flag='1' ".$sqry2.") 		
												UNION ALL		
										(SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
											THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible 
										FROM itc_module_points_master AS a 
										LEFT JOIN `itc_class_dyad_schedulegriddet` AS b ON (a.fld_schedule_id=b.fld_schedule_id 
											AND a.fld_module_id=b.`fld_module_id` AND (a.fld_student_id=b.fld_student_id OR b.fld_rotation='0')) 
										WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$id[2]."' AND a.fld_delstatus='0' 
                                                    AND b.fld_flag='1' AND a.fld_grade<>'0' AND a.fld_schedule_type='2' ".$sqry1.") 		
												UNION ALL		
										(SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
											THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible 
										FROM itc_module_points_master AS a 
										LEFT JOIN `itc_class_triad_schedulegriddet` AS b ON (a.fld_schedule_id=b.fld_schedule_id
											AND a.fld_module_id=b.`fld_module_id` AND (a.fld_student_id=b.fld_student_id OR b.fld_rotation='0')) 
										WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$id[2]."' AND a.fld_delstatus='0' 
                                                    AND b.fld_flag='1' AND a.fld_grade<>'0' AND a.fld_schedule_type='3' ".$sqry1.") 		
												UNION ALL 		
										(SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
											THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible 
										FROM itc_module_points_master AS a 
										LEFT JOIN itc_class_indassesment_master AS b ON (a.fld_schedule_id=b.fld_id 
											AND a.fld_module_id=b.fld_module_id) 
										LEFT JOIN itc_class_indassesment_student_mapping AS c ON (a.fld_schedule_id=c.fld_schedule_id 
											AND a.fld_student_id=c.fld_student_id) 
										WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$id[2]."' AND a.fld_delstatus='0' 
                                                    AND b.fld_flag='1' AND a.fld_grade<>'0' AND a.fld_schedule_type IN (5,6,7) AND b.fld_delstatus='0' AND c.fld_flag='1' ".$sqry1.") 		
									) AS a");
		$rowqry=$qrypoints->fetch_assoc();
		extract($rowqry);
		
                $pointsearned = $earned+$exptearned;
                $pointspossible = $possible+$exptpossible;
                
                if($pointsearned!='')
		{
                        $pointsearned = round($pointsearned);
			if($roundflag==0)
				$percentage = round(($pointsearned/$pointspossible)*100,2);
			else
				$percentage = round(($pointsearned/$pointspossible)*100);

			$perarray = explode('.',$percentage);
			$grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
												FROM itc_class_grading_scale_mapping 
                                                            WHERE fld_class_id = '".$id[2]."' AND fld_lower_bound <= '".$perarray[0]."' 
                                                                    AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
		}
		else
		{
			$pointsearned = " - ";
			$pointspossible = " - ";
			$percentage = " - ";
			$grade = "N/A";
		}
		
		if($count > 0) {?>
			<div style="page-break-before: always;">&nbsp;</div>
		<?php }
		?>
        <table cellpadding="0" cellspacing="0">
        	<tr>
            	<td style="width:70%;">Student : <?php echo $studentname; ?></td>
                <td style="width:30%;" align="center">
                	<table>
                    	<tr style="font-size:35px; font-weight:bold">
                        	<td><?php echo $grade;?></td>
                        </tr>
                        <tr>
                        	<td><?php echo $percentage." % (".$pointsearned." / ".$pointspossible.")";?></td>
                        </tr>
                	</table>
                </td>
            </tr>
            <tr>
            	<td colspan="2">&nbsp;</td>
            </tr>
        </table>
        <table cellpadding="2" cellspacing="0">
			<tr style="font-size:35px; font-weight:bold;">
				<th style="width:33%;">Assignment Name</th>
				<th style="width:20%;">Points Earned</th>
				<th style="width:20%;">Points Possible</th>
				<th style="width:15%;">Percentage</th>
				<th style="width:10%;">Grade</th>
			</tr>
			<?php								
                        
            $qry = $ObjDB->QueryObject("SELECT w.* FROM (
                                        (SELECT a.fld_unit_id AS ids, a.fld_schedule_id AS schid, c.fld_unit_name AS assignname, 0 AS typename, 0 AS rotation
                                        FROM itc_assignment_sigmath_master AS a 
                                        LEFT JOIN itc_class_sigmath_master AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_id) 
                                        LEFT JOIN itc_unit_master AS c ON a.fld_unit_id=c.fld_id 
                                        WHERE a.fld_class_id='".$id[2]."' AND a.fld_student_id='".$studentid."' AND a.fld_test_type='1' 
                                            AND c.fld_delstatus='0' AND b.fld_flag='1' AND b.fld_delstatus='0' ".$sqry." GROUP BY ids)
                                                UNION ALL	
                                        (SELECT a.fld_module_id AS ids, a.fld_schedule_id AS schid, CONCAT(d.fld_module_name,' ',e.fld_version,
                                            ' Rotation ',a.fld_rotation - 1) AS assignname, 1 AS typename, a.fld_rotation AS rotation 
                                        FROM itc_class_rotation_schedulegriddet AS a 
                                        LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
                                        LEFT JOIN itc_class_rotation_scheduledate AS c ON a.fld_schedule_id=c.fld_schedule_id and a.fld_rotation=c.fld_rotation 
                                        LEFT JOIN itc_module_master AS d ON a.fld_module_id=d.fld_id
                                        LEFT JOIN itc_module_version_track AS e ON d.fld_id=e.fld_mod_id 
                                        WHERE a.fld_student_id='".$studentid."' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' 
                                            AND b.fld_moduletype='1'  AND b.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND c.fld_flag='1' ".$sqry2."
                                        GROUP BY ids)
                                                UNION ALL	
                                        (SELECT a.fld_module_id AS ids, a.fld_schedule_id AS schid, CONCAT(b.fld_module_name,' ',c.fld_version,' 
                                            Dyad Rotation ',(CASE WHEN fld_rotation='0' THEN '' WHEN fld_rotation<>'0' THEN fld_rotation END)) AS assignname, 2 AS typename, fld_rotation AS rotation 
                                        FROM itc_class_dyad_schedulegriddet AS a 
                                        LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
                                        LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
					LEFT JOIN itc_class_dyad_schedule_studentmapping AS d ON (d.fld_student_id='".$studentid."' AND d.fld_schedule_id=a.fld_schedule_id)
                                        LEFT JOIN itc_class_dyad_schedulemaster AS e ON a.fld_schedule_id = e.fld_id
                                        WHERE (a.fld_student_id='".$studentid."' OR a.fld_rotation='0') AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' 
                                            AND b.fld_delstatus='0' AND c.fld_delstatus='0' AND d.fld_flag='1' AND e.fld_delstatus='0' ".$sqry3." GROUP BY ids)
                                                UNION ALL
                                        (SELECT a.fld_module_id AS ids, a.fld_schedule_id AS schid, CONCAT(b.fld_module_name,' ',c.fld_version,' 
                                            Triad Rotation ',(CASE WHEN fld_rotation='0' THEN '' WHEN fld_rotation<>'0' THEN fld_rotation END)) AS assignname, 3 AS typename, fld_rotation AS rotation 
                                        FROM itc_class_triad_schedulegriddet AS a 
                                        LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
                                        LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
					LEFT JOIN itc_class_triad_schedule_studentmapping AS d ON (d.fld_student_id='".$studentid."' AND d.fld_schedule_id=a.fld_schedule_id)
                                        LEFT JOIN itc_class_triad_schedulemaster AS e ON a.fld_schedule_id = e.fld_id
                                        WHERE (a.fld_student_id='".$studentid."' OR a.fld_rotation='0') AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' 
                                            AND b.fld_delstatus='0' AND c.fld_delstatus='0' AND d.fld_flag='1' AND e.fld_delstatus='0' ".$sqry3." GROUP BY ids)
                                                UNION ALL
                                        (SELECT a.fld_module_id AS ids, a.fld_schedule_id AS schid, CONCAT(d.fld_mathmodule_name,' ',e.fld_version,
                                            ' Rotation ',a.fld_rotation - 1) AS assignname, 4 AS typename, a.fld_rotation AS rotation 
                                        FROM itc_class_rotation_schedulegriddet AS a 
                                        LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
                                        LEFT JOIN itc_class_rotation_scheduledate AS c ON a.fld_schedule_id=c.fld_schedule_id and a.fld_rotation=c.fld_rotation 
                                        LEFT JOIN itc_mathmodule_master AS d ON a.fld_module_id=d.fld_id
                                        LEFT JOIN itc_module_version_track AS e ON d.fld_module_id=e.fld_mod_id 
                                        WHERE a.fld_student_id='".$studentid."' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' 
                                            AND b.fld_moduletype='2'  AND b.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND c.fld_flag='1' ".$sqry2."
                                        GROUP BY ids)
                                                UNION ALL
                                        (SELECT a.fld_module_id AS ids, a.fld_id AS schid, CONCAT(c.fld_module_name,' ',d.fld_version,' Ind Module') 
                                            AS assignname, 5 AS typename, 0 AS rotation 
                                        FROM itc_class_indassesment_master AS a 
                                        LEFT JOIN itc_class_indassesment_student_mapping AS b ON b.fld_schedule_id=a.fld_id 
                                        LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id 
                                        LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id
                                        WHERE b.fld_student_id='".$studentid."' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' AND d.fld_delstatus='0'
                                            AND a.fld_moduletype='1' AND b.fld_flag='1' AND a.fld_delstatus='0' AND c.fld_delstatus='0' ".$sqry3." GROUP BY ids) 
                                                UNION ALL
                                        (SELECT a.fld_module_id AS ids, a.fld_id AS schid, CONCAT(c.fld_mathmodule_name,' ',d.fld_version,' Ind MM') 
                                            AS assignname, 6 AS typename, 0 AS rotation 
                                        FROM itc_class_indassesment_master AS a 
                                        LEFT JOIN itc_class_indassesment_student_mapping AS b ON b.fld_schedule_id=a.fld_id 
                                        LEFT JOIN itc_mathmodule_master AS c ON a.fld_module_id=c.fld_id 
                                        LEFT JOIN itc_module_version_track AS d ON c.fld_module_id=d.fld_mod_id
                                        WHERE b.fld_student_id='".$studentid."' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' AND d.fld_delstatus='0' 
                                            AND a.fld_moduletype='2' AND b.fld_flag='1' AND a.fld_delstatus='0' AND c.fld_delstatus='0' ".$sqry3." GROUP BY ids)
                                                UNION ALL
                                        (SELECT a.fld_module_id AS ids, a.fld_id AS schid, CONCAT(c.fld_module_name,' ',d.fld_version,' Ind Quest') 
                                            AS assignname, 7 AS typename, 0 AS rotation 
                                        FROM itc_class_indassesment_master AS a 
                                        LEFT JOIN itc_class_indassesment_student_mapping AS b ON b.fld_schedule_id=a.fld_id 
                                        LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id 
                                        LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id
                                        WHERE b.fld_student_id='".$studentid."' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' AND d.fld_delstatus='0'
                                            AND a.fld_moduletype='7' AND b.fld_flag='1' AND a.fld_delstatus='0' AND c.fld_delstatus='0' ".$sqry3." GROUP BY ids) 
                                                UNION ALL	
                                        (SELECT a.fld_module_id AS ids, a.fld_schedule_id AS schid, CONCAT(d.fld_contentname,' Custom Content'), 
                                            8 AS typename, 0 AS rotation 
                                        FROM itc_class_rotation_schedulegriddet AS a 
                                        LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
                                        LEFT JOIN itc_class_rotation_scheduledate AS c ON a.fld_schedule_id=c.fld_schedule_id and a.fld_rotation=c.fld_rotation 
                                        LEFT JOIN itc_customcontent_master AS d ON a.fld_module_id=d.fld_id
                                        WHERE a.fld_student_id='".$studentid."' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' AND a.fld_type='8'
                                            AND b.fld_moduletype='1'  AND b.fld_delstatus='0' AND d.fld_delstatus='0' AND c.fld_flag='1' ".$sqry2." GROUP BY ids)
                                                UNION ALL	
                                        (SELECT a.fld_exp_id AS ids, a.fld_id AS scheduleid, CONCAT(b.fld_exp_name,' / Expedition') AS assignname, 15 AS typeids, 0 AS rotation 
                                        FROM itc_class_indasexpedition_master AS a 
                                        LEFT JOIN itc_exp_master AS b ON a.fld_exp_id=b.fld_id
					LEFT JOIN itc_class_exp_student_mapping AS c ON (a.fld_id=c.fld_schedule_id)
                                        WHERE c.fld_student_id='".$studentid."' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_delstatus='0' ".$sqry3."
                                        GROUP BY a.fld_id) 
                                    ) AS w 
                                    ORDER BY w.typename, w.schid, w.rotation");  
            ?>
			<tbody>
			<?php
			if($qry->num_rows > 0)
			{ 	 
				$cnt=0;
				while($row=$qry->fetch_assoc())
				{
					extract($row);
					
					$qrydetails = '';
					if($typename == 0)
					{
						$qrydetails = "SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END)
											AS pointsearned, SUM(fld_points_possible) AS pointspossible 
										FROM itc_assignment_sigmath_master 
										WHERE fld_class_id='".$id[2]."' AND fld_student_id='".$studentid."' AND fld_schedule_id='".$schid."' 
											AND fld_unit_id='".$ids."' AND fld_test_type='1' AND (fld_status='1' OR fld_status='2') 
											AND fld_grade<>'0'";
					}
					else if($typename == 15)
					{
                                            $qryexp = $ObjDB->QueryObject("SELECT c.fld_pointspossible, c.fld_exptype, 
                                                                                    IFNULL((SELECT CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
                                                                                            THEN fld_teacher_points_earned END AS pointsearned
                                                                                    FROM itc_exp_points_master 
                                                                                    WHERE fld_student_id='".$studentid."' AND fld_exp_id=b.fld_exp_id AND fld_grade='1' 
                                                                                    AND fld_exptype=c.fld_exptype AND fld_schedule_id=b.fld_id AND fld_schedule_type='15' 
                                                                                    AND (fld_points_earned<>'' OR fld_teacher_points_earned<>'') ),'-') AS pearned
                                                                            FROM itc_exp_master AS a 
                                                                            LEFT JOIN itc_class_indasexpedition_master AS b ON b.fld_exp_id=a.fld_id 
                                                                            LEFT JOIN itc_class_exp_grade AS c ON (c.fld_schedule_id=b.fld_id AND c.fld_exp_id=b.fld_exp_id)
                                                                            WHERE b.fld_class_id='".$id[2]."' AND b.fld_flag='1' AND b.fld_delstatus='0' AND b.fld_exp_id='".$ids."'
                                                                                   AND b.fld_id='".$schid."' AND a.fld_delstatus='0' AND c.fld_flag='1' AND c.fld_exptype<>'1'");
                                            
                                            $exptearned = '';
                                            $exptpossible = '';
                                            if($qryexp->num_rows>0)
                                            {
                                                    while($rowqryexp = $qryexp->fetch_assoc())
                                                    {
                                                            extract($rowqryexp);
                                                            if($pearned==='-')
                                                            {
                                                                    if($fld_exptype==3)
                                                                    {
                                                                            $qryques = $ObjDB->QueryObject("SELECT IFNULL(b.fld_total_question,'-') AS quescount, COUNT(a.fld_id) AS correctcount FROM itc_test_student_answer_track AS a LEFT JOIN itc_test_master AS b ON a.fld_test_id=b.fld_id WHERE b.fld_expt='".$expid."' AND a.fld_student_id='".$studentid."' AND b.fld_delstatus='0' AND a.fld_show='1' AND a.fld_delstatus='0'");

                                                                            if($qryques->num_rows>0)
                                                                            {
                                                                                    $rowqryques = $qryques->fetch_assoc();
                                                                                    extract($rowqryques);

                                                                                    if($quescount==='-')
                                                                                    {
                                                                                            $pointsearned = '';
                                                                                            $pointspossible = '';
                                                                                    }
                                                                                    else {
                                                                                            $expearned = $expearned+$correctcount*($fld_pointspossible/$quescount);
                                                                                            $exppossible = $exppossible+$fld_pointspossible;
                                                                                    }
                                                                            }
                                                                    }
                                                                    else
                                                                    {
                                                                            $expearned = '';
                                                                            $exppossible = '';
                                                                    }
                                                            }
                                                            else
                                                            {
                                                                    $exptearned = $exptearned+$expearned+$pearned;
                                                                    $exptpossible = $exptpossible+$exppossible+$fld_pointspossible;										
                                                            }
                                                    }
                                            }
                                            $pointsearned = $exptearned;
                                            $pointspossible = $exptpossible;
					}
					else if($typename == 4 or $typename == 6)
					{
						if($typename==4)
							$testtype=2;
						else if($typename==6)
							$testtype=5;
						
						$qryiplids = $ObjDB->QueryObject("SELECT fld_ipl_day1, fld_ipl_day2 
														FROM itc_mathmodule_master 
														WHERE fld_id='".$ids."'  AND fld_delstatus='0'");
						$rowqryiplids=$qryiplids->fetch_assoc();
						extract($rowqryiplids);
							
						$qrydetails = "SELECT SUM(w.earnedpoints) AS pointsearned, SUM(w.pointspossible) AS pointspossible 
										FROM (SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
												THEN fld_teacher_points_earned END) AS earnedpoints, SUM(fld_points_possible) AS pointspossible 
											FROM itc_module_points_master 
											WHERE fld_module_id='".$ids."' AND fld_schedule_id='".$schid."' AND fld_student_id='".$studentid."' 
												AND fld_schedule_type='".$typename."' AND fld_delstatus='0' AND fld_grade<>'0'	
													UNION ALL		
											SELECT ROUND(SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN 
												fld_teacher_points_earned END)/4) AS earnedpoints, ROUND(SUM(fld_points_possible)/4) 
												AS pointspossible 
											FROM itc_assignment_sigmath_master 
											WHERE fld_schedule_id='".$schid."' AND fld_student_id='".$studentid."' AND fld_test_type='".$testtype."' 
                                                                                                 AND fld_module_id='".$ids."'
												AND fld_class_id='".$id[2]."' AND fld_delstatus='0' AND (fld_status='1' OR fld_status='2' OR fld_lock='1') 
												AND (fld_lesson_id IN (".$fld_ipl_day1.") OR fld_lesson_id IN (".$fld_ipl_day2."))
										) AS w";
					}
					else 
					{
						$qrydetails = "SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END)
											AS pointsearned, SUM(fld_points_possible) AS pointspossible 
										FROM itc_module_points_master 
										WHERE fld_student_id='".$studentid."' AND fld_delstatus='0' AND fld_schedule_type='".$typename."'
											AND fld_schedule_id='".$schid."' AND fld_module_id='".$ids."' AND fld_grade<>'0'";
					}
					
                                        if($typename != 15) {
					$qryscore = $ObjDB->QueryObject($qrydetails);
					
					$rowqry=$qryscore->fetch_assoc();
					extract($rowqry);
                                        }                                
					if($pointsearned!='')
					{
						if($roundflag==0)
							$percentage = round(($pointsearned/$pointspossible)*100,2);
						else
							$percentage = round(($pointsearned/$pointspossible)*100);						
						$perarray = explode('.',$percentage);
						$grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
															FROM itc_class_grading_scale_mapping 
															WHERE fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' 
						
																AND fld_class_id='".$id[2]."' AND fld_flag='1'");
						$percentage = $percentage." %";
						$pointsearned = round($pointsearned);
					}
					else
					{
						$pointsearned= " - ";
						$pointspossible= " - ";
						$percentage= " - ";
						$grade= " N/A ";
					}
					?>
					<tr class="<?php if($cnt==0) { ?>trgray<?php } else if($cnt==1) { ?>trclass<?php }?>">
						<td class="tdleft"><?php echo $assignname; ?></td>
						<td class="tdmiddle" align="center"><?php echo $pointsearned; ?></td>
						<td class="tdmiddle" align="center"><?php echo $pointspossible; ?></td>
						<td class="tdmiddle" align="center"><?php echo $percentage; ?></td>
						<td class="tdright" align="center"><?php echo $grade; ?></td>
					</tr>
					<?php
					if($cnt==0)
						$cnt=1;
					else if($cnt==1)
						$cnt=0;
				}
			}
			else
			{ ?>
				<tr class="trgray">
					<td style="border:1px solid #b4b4b4;" colspan="5">No Records</td>
				</tr>
			<?php 
			} ?>
			</tbody>
		</table>
        <?php
		$count++;
	}
}
?>