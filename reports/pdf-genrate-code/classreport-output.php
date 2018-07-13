<?php 
@include("table.class.php");
@include("comm_func.php");
$method = $_REQUEST;

$id = isset($method['id']) ? $method['id'] : '0';
$id = explode(",",$id);
?>
<style>
	.title
	{
		font-size: 50px; color:#808080; font-family:Arial;
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

$startdate = date('Y-m-d',strtotime($id[2]));
$enddate = date('Y-m-d',strtotime($id[3]));

$sqry = "AND ('".$startdate."' BETWEEN b.fld_start_date AND b.fld_end_date OR '".$enddate."' BETWEEN b.fld_start_date AND b.fld_end_date OR b.fld_start_date BETWEEN '".$startdate."' AND '".$enddate."' OR b.fld_end_date BETWEEN '".$startdate."' AND '".$enddate."')";
$sqry1 = " AND ('".$startdate."' BETWEEN b.fld_startdate AND b.fld_enddate OR '".$enddate."' BETWEEN b.fld_startdate AND b.fld_enddate OR b.fld_startdate BETWEEN '".$startdate."' AND '".$enddate."' OR b.fld_enddate BETWEEN '".$startdate."' AND '".$enddate."')";
$sqry2 = " AND ('".$startdate."' BETWEEN c.fld_startdate AND c.fld_enddate OR '".$enddate."' BETWEEN c.fld_startdate AND c.fld_enddate OR c.fld_startdate BETWEEN '".$startdate."' AND '".$enddate."' OR c.fld_enddate BETWEEN '".$startdate."' AND '".$enddate."')";

$qrystudent = $ObjDB->QueryObject("SELECT a.fld_student_id AS studentid, CONCAT(b.fld_fname,' ',b.fld_lname) AS studentname 
								FROM `itc_class_student_mapping` AS a 
								LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
								WHERE a.fld_class_id='".$id[1]."' AND a.fld_flag='1' AND b.`fld_activestatus`='1' AND b.`fld_delstatus`='0' 
								ORDER BY b.fld_lname"); 

$roundflag = $ObjDB->SelectSingleValue("SELECT fld_roundflag 
										FROM itc_class_grading_scale_mapping 
										WHERE fld_class_id = '".$id[1]."' AND fld_flag = '1' 
										GROUP BY fld_roundflag"); 
?>
<table cellpadding="0" cellspacing="0" >
	<tr style="font-size:35px; font-weight:bold">
		<th style="width:23%;">Student Name</th>
		<th style="width:25%;">Points Earned</th>
		<th style="width:25%;">Points Possible</th>
		<th style="width:18%;">Percentage</th>
		<th style="width:10%;">Grade</th>
	</tr>

	<tbody>
	<?php
	if($qrystudent->num_rows > 0)
	{ 	 
		$cnt=0;
		while($row=$qrystudent->fetch_assoc())
		{
			extract($row);
			
                        $expearned = '';
                        $exppossible = '';
                        
                        $qryexp = $ObjDB->QueryObject("SELECT b.fld_id AS scheduleid, b.fld_exp_id AS expid, c.fld_pointspossible, c.fld_exptype, 
                                                                IFNULL((SELECT CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
                                                                        THEN fld_teacher_points_earned END AS pointsearned
                                                                FROM itc_exp_points_master 
                                                                WHERE fld_student_id='".$studentid."' AND fld_exp_id=b.fld_exp_id AND fld_grade='1' 
                                                                AND fld_exptype=c.fld_exptype AND fld_schedule_id=b.fld_id AND fld_schedule_type='15' 
                                                                AND (fld_points_earned<>'' OR fld_teacher_points_earned<>'')),'-') AS pearned
                                                        FROM itc_exp_master AS a 
                                                        LEFT JOIN itc_class_indasexpedition_master AS b ON b.fld_exp_id=a.fld_id 
                                                        LEFT JOIN itc_class_exp_grade AS c ON (c.fld_schedule_id=b.fld_id AND c.fld_exp_id=b.fld_exp_id)
                                                        WHERE b.fld_class_id='".$id[1]."' AND b.fld_flag='1' AND b.fld_delstatus='0' 
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

			$qrypoints= $ObjDB->QueryObject("SELECT SUM(q.pointsearned) AS earned, SUM(q.pointspossible) AS possible FROM 
(SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible FROM itc_assignment_sigmath_master AS a LEFT JOIN itc_class_sigmath_master AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_id) WHERE b.fld_class_id='".$id[1]."' AND a.fld_student_id='".$studentid."' AND a.fld_test_type='1' AND b.fld_flag='1' AND b.fld_delstatus='0' AND (a.fld_status='1' OR a.fld_status='2' OR a.fld_lock='1' OR a.fld_unitmark='1') AND a.fld_delstatus='0' AND a.fld_grade<>'0' ".$sqry."		
UNION ALL			
(SELECT ROUND(SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' THEN a.fld_teacher_points_earned END)/4) AS pointsearned, ROUND(SUM(a.fld_points_possible)/4) AS pointspossible FROM `itc_assignment_sigmath_master` AS a LEFT JOIN itc_class_rotation_schedulegriddet AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_schedule_id and a.fld_module_id=b.fld_module_id) LEFT JOIN itc_class_rotation_scheduledate AS c ON b.fld_schedule_id=c.fld_schedule_id and b.fld_rotation=c.fld_rotation WHERE b.fld_class_id = '".$id[1]."' AND a.fld_student_id = '".$studentid."' AND b.fld_student_id='".$studentid."' AND a.fld_test_type='2' AND b.fld_flag='1' AND a.fld_delstatus='0' and b.fld_type='2' AND (a.fld_status='1' OR a.fld_status='2' OR a.fld_lock='1') AND c.fld_flag='1' ".$sqry2." GROUP BY a.fld_schedule_id)
UNION ALL		
(SELECT ROUND(SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' THEN a.fld_teacher_points_earned END)/4) AS pointsearned, ROUND(SUM(a.fld_points_possible)/4) AS pointspossible FROM itc_assignment_sigmath_master AS a LEFT JOIN itc_class_indassesment_master AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_id) WHERE b.fld_class_id='".$id[1]."' AND a.fld_student_id='".$studentid."' AND a.fld_test_type = '5' AND b.fld_moduletype='2' AND b.fld_flag='1' AND b.fld_delstatus='0' AND a.fld_delstatus='0' AND (a.fld_status='1' OR a.fld_status='2' OR a.fld_lock='1')  ".$sqry1." GROUP BY a.fld_schedule_id) 	
UNION ALL		
(SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible FROM itc_module_points_master AS a LEFT JOIN `itc_class_rotation_schedulegriddet` AS b ON (a.fld_schedule_id=b.fld_schedule_id AND a.fld_module_id=b.`fld_module_id` AND a.fld_student_id = b.fld_student_id) LEFT JOIN itc_class_rotation_scheduledate AS c ON b.fld_schedule_id=c.fld_schedule_id and b.fld_rotation=c.fld_rotation WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$id[1]."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_grade<>'0' AND a.fld_schedule_type IN ('1','4','8') AND c.fld_flag='1' ".$sqry2.")
UNION ALL		
(SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible FROM itc_module_points_master AS a LEFT JOIN `itc_class_dyad_schedulegriddet` AS b ON (a.fld_schedule_id=b.fld_schedule_id AND a.fld_module_id=b.`fld_module_id` AND (a.fld_student_id=b.fld_student_id OR b.fld_rotation='0')) WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$id[1]."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_grade<>'0' AND a.fld_schedule_type='2' ".$sqry1.") 		
UNION ALL		
(SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible FROM itc_module_points_master AS a LEFT JOIN `itc_class_triad_schedulegriddet` AS b ON (a.fld_schedule_id=b.fld_schedule_id AND a.fld_module_id=b.`fld_module_id` AND (a.fld_student_id=b.fld_student_id OR b.fld_rotation='0')) WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$id[1]."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_grade<>'0' AND a.fld_schedule_type='3' ".$sqry1.") 		
UNION ALL 		
(SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible FROM itc_module_points_master AS a LEFT JOIN itc_class_indassesment_master AS b ON (a.fld_schedule_id=b.fld_id AND a.fld_module_id=b.fld_module_id) LEFT JOIN itc_class_indassesment_student_mapping AS c ON (a.fld_schedule_id=c.fld_schedule_id AND a.fld_student_id=c.fld_student_id) WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$id[1]."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_grade<>'0' AND a.fld_schedule_type='5' AND c.fld_flag='1' AND b.fld_delstatus='0' ".$sqry1.") 		
UNION ALL 		
(SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible FROM itc_module_points_master AS a LEFT JOIN itc_class_indassesment_master AS b ON (a.fld_schedule_id=b.fld_id AND a.fld_module_id=b.fld_module_id) LEFT JOIN itc_class_indassesment_student_mapping AS c ON (a.fld_schedule_id=c.fld_schedule_id AND a.fld_student_id=c.fld_student_id) WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$id[1]."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_grade<>'0' AND a.fld_schedule_type='6' AND c.fld_flag='1' AND b.fld_delstatus='0' ".$sqry1.")
UNION ALL 		
(SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible FROM itc_module_points_master AS a LEFT JOIN itc_class_indassesment_master AS b ON (a.fld_schedule_id=b.fld_id AND a.fld_module_id=b.fld_module_id) LEFT JOIN itc_class_indassesment_student_mapping AS c ON (a.fld_schedule_id=c.fld_schedule_id AND a.fld_student_id=c.fld_student_id) WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$id[1]."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_grade<>'0' AND a.fld_schedule_type='7' AND c.fld_flag='1' AND b.fld_delstatus='0' ".$sqry1.")
) AS q  ");
			
			$rowqry=$qrypoints->fetch_assoc();
			extract($rowqry);
                        
                        $totalearned = $earned+$exptearned;
                        $totalpossible = $possible+$exptpossible;
			if($totalearned!='')
			{
				$totalearned = round($totalearned);
				if($roundflag==0)
					$percentage = round(($totalearned/$totalpossible)*100,2);
				else
					$percentage = round(($totalearned/$totalpossible)*100);
				
				$perarray = explode('.',$percentage);
				$grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
													FROM itc_class_grading_scale_mapping 
													WHERE fld_class_id = '".$id[1]."' AND fld_lower_bound <= '".$perarray[0]."' 
														AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
			}
			else
			{
				$percentage = "-";
				$grade = " N/A ";
			}
			?>
			<tr class="<?php if($cnt==0) { ?>trgray<?php } else if($cnt==1) { ?>trclass<?php }?>">
				<td class="tdleft"><?php echo $studentname; ?></td>
				<td class="tdmiddle"><?php if($totalearned!='') { echo $totalearned; } else { echo " - "; } ?></td>
				<td class="tdmiddle"><?php if($totalpossible!='') { echo $totalpossible; } else { echo " - "; } ?></td>
				<td class="tdmiddle"><?php echo $percentage." %"; ?></td>
				<td class="tdright"><?php echo $grade; ?></td>
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
			<td style="border-top:1px solid #b4b4b4; border:1px solid #b4b4b4;" colspan="5">No records</td>
		</tr>
	<?php 
	} ?>
	</tbody>
</table>