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
$roundflag = $ObjDB->SelectSingleValue("SELECT fld_roundflag 
										FROM itc_class_grading_scale_mapping 
										WHERE fld_class_id = '".$id[2]."' AND fld_flag = '1' 
										GROUP BY fld_roundflag");
										
$qry = $ObjDB->QueryObject("SELECT w.* FROM (
							(SELECT a.fld_unit_id AS ids, a.fld_schedule_id AS schid, b.fld_unit_name AS assignname, 0 AS typename, 0 AS rotation
							FROM itc_assignment_sigmath_master AS a 
							LEFT JOIN itc_unit_master AS b ON a.fld_unit_id=b.fld_id 
							WHERE a.fld_class_id='".$id[2]."' AND a.fld_student_id='".$id[1]."' AND a.fld_test_type='1' 
								AND b.fld_delstatus='0')
									UNION ALL	
							(SELECT a.fld_module_id AS ids, a.fld_schedule_id AS schid, CONCAT(c.fld_module_name,' ',d.fld_version,
								' Rotation ',a.fld_rotation - 1) AS assignname, 1 AS typename, a.fld_rotation AS rotation 
							FROM itc_class_rotation_schedulegriddet AS a 
							LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
							LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id
							LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id 
							WHERE a.fld_student_id='".$id[1]."' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' 
								AND b.fld_moduletype='1'  AND b.fld_delstatus='0' AND c.fld_delstatus='0' AND d.fld_delstatus='0')
									UNION ALL	
							(SELECT a.fld_module_id AS ids, a.fld_schedule_id AS schid, CONCAT(b.fld_module_name,' ',c.fld_version,' 
								Dyad Rotation ',fld_rotation) AS assignname, 2 AS typename, fld_rotation AS rotation 
							FROM itc_class_dyad_schedulegriddet AS a 
							LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
							LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
							WHERE (a.fld_student_id='".$id[1]."' OR a.fld_rotation='0') AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' 
								AND b.fld_delstatus='0' AND c.fld_delstatus='0')
									UNION ALL
							(SELECT a.fld_module_id AS ids, a.fld_schedule_id AS schid, CONCAT(b.fld_module_name,' ',c.fld_version,' 
								Triad Rotation ',fld_rotation) AS assignname, 3 AS typename, fld_rotation AS rotation 
							FROM itc_class_triad_schedulegriddet AS a 
							LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
							LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
							WHERE (a.fld_student_id='".$id[1]."' OR a.fld_rotation='0') AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' 
								AND b.fld_delstatus='0' AND c.fld_delstatus='0')
									UNION ALL
							(SELECT a.fld_module_id AS ids, a.fld_schedule_id AS schid, CONCAT(c.fld_mathmodule_name,' ',d.fld_version,
								' Rotation ',a.fld_rotation - 1) AS assignname, 4 AS typename, a.fld_rotation AS rotation 
							FROM itc_class_rotation_schedulegriddet AS a 
							LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
							LEFT JOIN itc_mathmodule_master AS c ON a.fld_module_id=c.fld_id
							LEFT JOIN itc_module_version_track AS d ON c.fld_module_id=d.fld_mod_id 
							WHERE a.fld_student_id='".$id[1]."' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' 
								AND b.fld_moduletype='2'  AND b.fld_delstatus='0' AND c.fld_delstatus='0' AND d.fld_delstatus='0')
									UNION ALL
							(SELECT a.fld_module_id AS ids, a.fld_id AS schid, CONCAT(c.fld_module_name,' ',d.fld_version,' Ind Module') 
								AS assignname, 5 AS typename, 0 AS rotation 
							FROM itc_class_indassesment_master AS a 
							LEFT JOIN itc_class_indassesment_student_mapping AS b ON b.fld_schedule_id=a.fld_id 
							LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id 
							LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id
							WHERE b.fld_student_id='".$id[1]."' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' AND d.fld_delstatus='0'
								AND a.fld_moduletype='1' AND b.fld_flag='1' AND a.fld_delstatus='0' AND c.fld_delstatus='0') 
									UNION ALL
							(SELECT a.fld_module_id AS ids, a.fld_id AS schid, CONCAT(c.fld_mathmodule_name,' ',d.fld_version,' Ind MM') 
								AS assignname, 6 AS typename, 0 AS rotation 
							FROM itc_class_indassesment_master AS a 
							LEFT JOIN itc_class_indassesment_student_mapping AS b ON b.fld_schedule_id=a.fld_id 
							LEFT JOIN itc_mathmodule_master AS c ON a.fld_module_id=c.fld_id 
							LEFT JOIN itc_module_version_track AS d ON c.fld_module_id=d.fld_mod_id
							WHERE b.fld_student_id='".$id[1]."' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' AND d.fld_delstatus='0' 
								AND a.fld_moduletype='2' AND b.fld_flag='1' AND a.fld_delstatus='0' AND c.fld_delstatus='0')
									UNION ALL
							(SELECT a.fld_module_id AS ids, a.fld_id AS schid, CONCAT(c.fld_module_name,' ',d.fld_version,' Ind Quest') 
								AS assignname, 7 AS typename, 0 AS rotation 
							FROM itc_class_indassesment_master AS a 
							LEFT JOIN itc_class_indassesment_student_mapping AS b ON b.fld_schedule_id=a.fld_id 
							LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id 
							LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id
							WHERE b.fld_student_id='".$id[1]."' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' AND d.fld_delstatus='0'
								AND a.fld_moduletype='7' AND b.fld_flag='1' AND a.fld_delstatus='0' AND c.fld_delstatus='0') 
									UNION ALL	
							(SELECT a.fld_module_id AS ids, a.fld_schedule_id AS schid, CONCAT(c.fld_contentname,' Custom Content'), 
								8 AS typename, 0 AS rotation 
							FROM itc_class_rotation_schedulegriddet AS a 
							LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
							LEFT JOIN itc_customcontent_master AS c ON a.fld_module_id=c.fld_id
							WHERE a.fld_student_id='".$id[1]."' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' 
								AND b.fld_moduletype='1'  AND b.fld_delstatus='0' AND c.fld_delstatus='0')
						) AS w 
						ORDER BY w.typename, w.schid, w.rotation");  
?>
<table cellpadding="2" cellspacing="0" >
	<tr style="font-size:35px; font-weight:bold">
		<th style="width:33%;">Assignment Name</th>
		<th style="width:20%;">Points Earnedd</th>
		<th style="width:20%;">Points Possible</th>
		<th style="width:15%;">Percentage</th>
		<th style="width:10%;">Grade</th>
	</tr>

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
								WHERE fld_class_id='".$id[2]."' AND fld_student_id='".$id[1]."' AND fld_schedule_id='".$schid."' 
									AND fld_unit_id='".$ids."' AND fld_test_type='1' AND (fld_status='1' OR fld_status='2') 
									AND fld_grade<>'0'";
			}
			else if($typename == 4 or $typename == 6)
			{
				if($typename==4)
					$testtype=2;
				else if($typename==6)
					$testtype=5;
					
				$qrydetails = "SELECT SUM(w.earnedpoints) AS pointsearned, SUM(w.pointspossible) AS pointspossible 
								FROM (SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
										THEN fld_teacher_points_earned END) AS earnedpoints, SUM(fld_points_possible) AS pointspossible 
									FROM itc_module_points_master 
									WHERE fld_module_id='".$ids."' AND fld_schedule_id='".$schid."' AND fld_student_id='".$id[1]."' 
										AND fld_schedule_type='".$typename."' AND fld_delstatus='0' AND fld_grade<>'0'	
											UNION ALL		
									SELECT ROUND(SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN 
										fld_teacher_points_earned END)/4) AS earnedpoints, ROUND(SUM(fld_points_possible)/4) 
										AS pointspossible 
									FROM itc_assignment_sigmath_master 
									WHERE fld_schedule_id='".$schid."' AND fld_student_id='".$id[1]."' AND fld_test_type='".$testtype."' 
										AND fld_class_id='".$id[2]."' AND fld_delstatus='0' AND (fld_status='1' OR fld_status='2') 
										AND fld_grade<>'0'
								) AS w";
			}
			else 
			{
				$qrydetails = "SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END)
									AS pointsearned, SUM(fld_points_possible) AS pointspossible 
								FROM itc_module_points_master 
								WHERE fld_student_id='".$id[1]."' AND fld_delstatus='0' AND fld_schedule_type='".$typename."'
									AND fld_schedule_id='".$schid."' AND fld_module_id='".$ids."' AND fld_grade<>'0'";
			}
			
			$qryscore = $ObjDB->QueryObject($qrydetails);
			
			$rowqry=$qryscore->fetch_assoc();
			extract($rowqry);
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
			<td style="border:1px solid #b4b4b4;" colspan="5">no records</td>
		</tr>
	<?php 
	} ?>
	</tbody>
</table>