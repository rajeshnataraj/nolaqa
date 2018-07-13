<?php 
@include("table.class.php");
@include("comm_func.php");
$method = $_REQUEST;
$id = isset($method['id']) ? $method['id'] : '0';
$id = explode(",",$id);

$start = isset($method['start']) ? $method['start'] : '0';
$end = isset($method['end']) ? $method['end'] : '10';

$status=0;
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
	.master{
		color:#090;
	}
</style>
<?php 
$qrydetails = '';
if($id[1]==0)
{
	$qrydetails = "SELECT a.fld_unit_id AS ids, 0 AS rotation, c.fld_unit_name AS modunnames, b.fld_start_date AS startdate, 
						b.fld_end_date AS enddate 
					FROM itc_class_sigmath_unit_mapping AS a 
					LEFT JOIN itc_class_sigmath_master AS b ON b.fld_id=a.fld_sigmath_id 
					LEFT JOIN itc_unit_master AS c ON a.fld_unit_id=c.fld_id
					WHERE a.fld_sigmath_id='".$id[0]."' AND a.fld_flag='1' AND b.fld_flag='1' AND b.fld_delstatus='0' 
						AND c.fld_delstatus='0'
					LIMIT ".$start.",".$end."";
	$testtype='1';
}
else if($id[1]==1)
{
	$qrydetails = "SELECT a.fld_module_id AS ids, CONCAT(b.fld_module_name,' ',c.fld_version) AS modunnames, 
						a.fld_rotation AS rotation, d.fld_startdate AS startdate, d.fld_enddate AS enddate 
					FROM itc_class_rotation_schedulegriddet AS a 
                                        LEFT JOIN itc_class_rotation_scheduledate AS d ON a.fld_schedule_id=d.fld_schedule_id AND a.fld_rotation=d.fld_rotation
					LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
					LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
					WHERE a.fld_class_id='".$id[2]."' AND a.fld_schedule_id='".$id[0]."' AND a.fld_student_id='".$id[3]."' 
						AND a.fld_flag='1' AND b.fld_delstatus='0' AND c.fld_delstatus='0' AND d.fld_flag='1'
					ORDER BY a.fld_startdate
					LIMIT ".$start.",".$end."";
}
else if($id[1]==2)
{
	$qrydetails = "SELECT a.fld_module_id AS ids, CONCAT(b.fld_module_name,' ',c.fld_version) AS modunnames, a.fld_rotation AS rotation, 
						a.fld_startdate AS startdate, a.fld_enddate AS enddate 
					FROM itc_class_dyad_schedulegriddet AS a 
					LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
					LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
					WHERE a.fld_class_id='".$id[2]."' AND a.fld_schedule_id='".$id[0]."' AND (a.fld_student_id='".$id[3]."' OR a.fld_rotation='0')
						AND a.fld_flag='1' AND b.fld_delstatus='0' AND c.fld_delstatus='0'
					ORDER BY fld_startdate
					LIMIT ".$start.",".$end."";
}
else if($id[1]==3)
{
	$qrydetails = "SELECT a.fld_module_id AS ids, CONCAT(b.fld_module_name,' ',c.fld_version) AS modunnames, a.fld_rotation AS rotation, 
						a.fld_startdate AS startdate, a.fld_enddate AS enddate 
					FROM itc_class_triad_schedulegriddet AS a 
					LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
					LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
					WHERE a.fld_class_id='".$id[2]."' AND a.fld_schedule_id='".$id[0]."' AND (a.fld_student_id='".$id[3]."' OR a.fld_rotation='0')
						AND a.fld_flag='1' AND b.fld_delstatus='0' AND c.fld_delstatus='0'
					ORDER BY fld_startdate
					LIMIT ".$start.",".$end."";
}
else if($id[1]==4)
{
	$qrydetails = "SELECT a.fld_module_id AS ids, CONCAT(b.fld_mathmodule_name,' ',c.fld_version) AS modunnames, 
						a.fld_rotation AS rotation, d.fld_startdate AS startdate, d.fld_enddate AS enddate 
					FROM itc_class_rotation_schedulegriddet AS a 
                                        LEFT JOIN itc_class_rotation_scheduledate AS d ON a.fld_schedule_id=d.fld_schedule_id AND a.fld_rotation=d.fld_rotation
					LEFT JOIN itc_mathmodule_master AS b ON a.fld_module_id=b.fld_id 
					LEFT JOIN itc_module_version_track AS c ON b.fld_module_id=c.fld_mod_id 
					WHERE a.fld_class_id='".$id[2]."' AND a.fld_schedule_id='".$id[0]."' AND a.fld_student_id='".$id[3]."' 
						AND a.fld_flag='1' AND b.fld_delstatus='0' AND c.fld_delstatus='0' AND d.fld_flag='1'
					ORDER BY a.fld_startdate
					LIMIT ".$start.",".$end."";
	$testtype='2';
}
else if($id[1]==5)
{
	$qrydetails = "SELECT a.fld_module_id AS ids, CONCAT(c.fld_module_name,' ',d.fld_version) AS modunnames, 0 AS rotation, 
						a.fld_startdate AS startdate, a.fld_enddate AS enddate 
					FROM itc_class_indassesment_master AS a
					LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id
					LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id 
					LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id 
					WHERE a.fld_class_id='".$id[2]."' AND a.fld_id='".$id[0]."' AND b.fld_student_id='".$id[3]."' AND b.fld_flag='1'
						AND a.fld_flag='1' AND c.fld_delstatus='0' AND d.fld_delstatus='0'
					ORDER BY fld_startdate
					LIMIT ".$start.",".$end."";
}
else if($id[1]==6)
{
	$qrydetails = "SELECT a.fld_module_id AS ids, CONCAT(c.fld_mathmodule_name,' ',d.fld_version) AS modunnames, 0 AS rotation, 
						a.fld_startdate AS startdate, a.fld_enddate AS enddate 
					FROM itc_class_indassesment_master AS a
					LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id
					LEFT JOIN itc_mathmodule_master AS c ON a.fld_module_id=c.fld_id 
					LEFT JOIN itc_module_version_track AS d ON c.fld_module_id=d.fld_mod_id 
					WHERE a.fld_class_id='".$id[2]."' AND a.fld_id='".$id[0]."' AND b.fld_student_id='".$id[3]."' AND b.fld_flag='1'
						AND a.fld_flag='1' AND c.fld_delstatus='0' AND d.fld_delstatus='0'
					ORDER BY fld_startdate
					LIMIT ".$start.",".$end."";
	$testtype='5';
}
else if($id[1]==7)
{
	$qrydetails = "SELECT a.fld_module_id AS ids, CONCAT(c.fld_module_name,' ',d.fld_version) AS modunnames, 0 AS rotation, 
						a.fld_startdate AS startdate, a.fld_enddate AS enddate 
					FROM itc_class_indassesment_master AS a
					LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id
					LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id 
					LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id 
					WHERE a.fld_class_id='".$id[2]."' AND a.fld_id='".$id[0]."' AND b.fld_student_id='".$id[3]."' AND b.fld_flag='1'
						AND a.fld_flag='1' AND c.fld_delstatus='0' AND d.fld_delstatus='0'
					ORDER BY fld_startdate
					LIMIT ".$start.",".$end."";
}
else if($id[1]==15)
{
	$qrydetails = "SELECT a.fld_exp_id AS ids, CONCAT(c.fld_exp_name,' ',d.fld_version) AS modunnames, 0 AS rotation, 
						a.fld_startdate AS startdate, a.fld_enddate AS enddate 
					FROM itc_class_indasexpedition_master AS a
					LEFT JOIN itc_class_exp_student_mapping AS b ON a.fld_id=b.fld_schedule_id
					LEFT JOIN itc_exp_master AS c ON a.fld_exp_id=c.fld_id 
					LEFT JOIN itc_exp_version_track AS d ON c.fld_id=d.fld_exp_id 
					WHERE a.fld_class_id='".$id[2]."' AND a.fld_id='".$id[0]."' AND b.fld_student_id='".$id[3]."' AND b.fld_flag='1'
						AND a.fld_flag='1' AND c.fld_delstatus='0' AND d.fld_delstatus='0'
					ORDER BY fld_startdate
					LIMIT ".$start.",".$end."";
}

$qryschedules = $ObjDB->QueryObject($qrydetails);

if($qryschedules->num_rows > 0)
{ 	
	$rowcount=0; 
	while($rowschedules=$qryschedules->fetch_assoc())
	{
		$rowcount++;
		extract($rowschedules);
		$status=0;
		if($id[1]==0)
		{
			$iplqry = $ObjDB->QueryObject("SELECT a.fld_lesson_id 
											FROM itc_class_sigmath_lesson_mapping AS a 
											LEFT JOIN itc_ipl_master AS b ON a.fld_lesson_id=b.fld_id 
											WHERE a.fld_sigmath_id='".$id[0]."' AND a.fld_flag='1' AND b.fld_unit_id='".$ids."' 
												AND b.fld_access='1' AND b.fld_delstatus='0' 
											ORDER BY a.fld_order");
			
			$completesiplcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_lesson_id) 
																FROM itc_assignment_sigmath_master 
																WHERE fld_schedule_id='".$id[0]."' AND fld_unit_id='".$ids."' 
																	AND (fld_status='1' OR fld_status='2') AND fld_test_type='".$testtype."' 
																	AND fld_delstatus='0' AND fld_student_id='".$id[3]."'");
			
			$mastered = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_lesson_id) 
														FROM itc_assignment_sigmath_master 
														WHERE fld_schedule_id='".$id[0]."' AND fld_unit_id='".$ids."' AND fld_status='1' 
															AND fld_test_type='".$testtype."' AND fld_delstatus='0' 
															AND fld_student_id='".$id[3]."'");
			
			if($iplqry->num_rows==$completesiplcount) 
				$status=1;
		}
		else if($id[1]==15)
		{
			$qrydest = $ObjDB->QueryObject("SELECT a.fld_dest_id FROM itc_license_exp_mapping AS a 
											LEFT JOIN itc_class_indasexpedition_master AS b ON (a.fld_exp_id=b.fld_exp_id AND a.fld_license_id=b.fld_license_id) 
											WHERE a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_flag='1' AND b.fld_delstatus='0' AND b.fld_id='".$id[0]."'");
			
			$readdestination = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_dest_id) 
														FROM itc_exp_dest_play_track 
														WHERE fld_schedule_id='".$id[0]."' AND fld_exp_id='".$ids."' 
															AND fld_read_status='1' AND fld_schedule_type='15' AND fld_delstatus='0' AND fld_student_id='".$id[3]."'");
			
			if($qrydest->num_rows == $readdestination)
				$status=1;
		}
		else
		{
			$completessesscount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_session_id) 
																FROM itc_module_points_master 
																WHERE fld_schedule_id='".$id[0]."' AND fld_module_id='".$ids."' 
																	AND fld_student_id='".$id[3]."' AND fld_schedule_type='".$id[1]."' 
																	AND fld_delstatus='0'");
			
			$sesscompleted = 0;
			
			if($id[1]==7)
			{
				$totalchapters = $ObjDB->SelectSingleValueInt("SELECT MAX(fld_session_id)+1 
															FROM itc_module_performance_master 
															WHERE fld_module_id='".$ids."'");
			}
			else
			{
				$totalchapters = 7;
			}
			
			if($id[1]==4 || $id[1]==6)
				$newmodid = $ObjDB->SelectSingleValueInt("SELECT fld_module_id FROM itc_mathmodule_master WHERE fld_id='".$ids."'");
			else
				$newmodid = $ids;
			for($i=0;$i<$totalchapters;$i++)
			{	
				$sesscount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_module_points_master WHERE fld_schedule_id='".$id[0]."' AND fld_module_id='".$ids."' AND fld_student_id='".$id[3]."' AND fld_schedule_type='".$id[1]."' AND fld_preassment_id='0' AND fld_session_id='".$i."' AND fld_delstatus='0' AND fld_type<>'0'");
				
				$viewedpages = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_module_play_track WHERE fld_schedule_id='".$id[0]."' AND fld_module_id='".$ids."' AND fld_tester_id='".$id[3]."' AND fld_schedule_type='".$id[1]."' AND fld_section_id='".$i."' AND fld_delstatus='0'");
				
				$totalpages = $ObjDB->SelectSingleValueInt("SELECT fld_points_possible FROM itc_module_performance_master WHERE fld_module_id='".$newmodid."' AND fld_session_id='".$i."' AND fld_delstatus='0' AND fld_performance_name='Total Pages'");
				
				$totalsess = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_module_performance_master WHERE (fld_performance_name = 'Attendance' OR fld_performance_name = 'Participation') AND fld_module_id='".$newmodid."' AND fld_delstatus='0' AND fld_session_id='".$i."'");
				
				if($sesscount==$totalsess && $viewedpages>=$totalpages)
					$sesscompleted++;
			}
				
			if($completessesscount==23 && $sesscompleted==$totalchapters)
				$status=1;
		}
		if($id[1]==0)
		{
			?>
			<table cellpadding="2" cellspacing="0" >
				<tr class="trgray">
					<td style="font-size:35px; font-weight:bold; border-top:1px solid #b4b4b4; border-left:1px solid #b4b4b4;">&nbsp;&nbsp;&nbsp;<?php echo $modunnames; ?></td>
					<td style="border-top:1px solid #b4b4b4;"></td>
					<td style="border-top:1px solid #b4b4b4; border-right:1px solid #b4b4b4; text-align:right">Total IPLs: <b><?php echo $iplqry->num_rows;?></b>&nbsp;&nbsp;&nbsp;</td>
				</tr>
				<tr class="trgray">
					<td style="border-left:1px solid #b4b4b4;font-size:25px;">&nbsp;&nbsp;&nbsp;<?php echo date("F d, Y",strtotime($startdate)).' - '.date("F d, Y",strtotime($enddate)); ?></td>
					<td></td>
					<td style="border-right:1px solid #b4b4b4; text-align:right">Completed: <b><?php echo $completesiplcount;?></b>&nbsp;&nbsp;&nbsp;</td>
				</tr>
				<tr class="trgray">
					<td style="border-bottom:1px solid #b4b4b4; border-left:1px solid #b4b4b4;"></td>
					<td style="border-bottom:1px solid #b4b4b4;"></td>
					<td style="border-bottom:1px solid #b4b4b4; border-right:1px solid #b4b4b4; text-align:right">Mastered: <b><?php echo $mastered;?></b>&nbsp;&nbsp;&nbsp;</td>
				</tr>
			</table>
			
			<table cellpadding="2" cellspacing="0">
				<tbody>
					<?php 
					while($rowiplqry=$iplqry->fetch_assoc())
					{
						extract($rowiplqry); 
						
						$status = $ObjDB->SelectSingleValueInt("SELECT fld_status 
																FROM itc_assignment_sigmath_master 
																WHERE fld_schedule_id='".$id[0]."' AND fld_unit_id='".$ids."' 
																	AND fld_lesson_id='".$fld_lesson_id."' AND fld_test_type='".$testtype."' 
																	AND fld_delstatus='0' AND fld_student_id='".$id[3]."'");
						?>
						<tr class="trclass">
							<td class="tdleft" colspan="2">&nbsp;&nbsp;&nbsp;
							<?php echo $ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_ipl_name,' ',b.fld_version) 
																	FROM itc_ipl_master AS a 
																	LEFT JOIN itc_ipl_version_track AS b ON a.fld_id=b.fld_ipl_id 
																	WHERE a.fld_id='".$fld_lesson_id."' AND b.fld_zip_type='1' 
																		AND b.fld_delstatus='0'"); ?></td>
							<td class="tdmiddle"></td>
							<td class="tdright <?php if($status==1) {?>master<?php }?>"><?php if($status==1) { echo "Mastered"; } else if($status==2) { echo "Not Mastered"; } else { echo "Not Completed";}?></td>
						</tr>
						<?php
					} ?>
					
				</tbody>
			</table>
			<?php if($qryschedules->num_rows!=$rowcount) {?>
			<br />
			<br />
			<?php }
		}
		else if($id[1]==15)
		{
			?>
            <table cellpadding="2" cellspacing="0">
				<tbody>
                	<tr class="trgray">
						<td class="tdleft" style="font-weight:bold; width:40%"><?php echo $modunnames; ?></td>
						<td class="tdmiddle" style="width:20%"><?php echo date("F d, Y",strtotime($startdate)); ?></td>
						<td class="tdmiddle" style="width:20%"><?php echo date("F d, Y",strtotime($enddate)); ?></td>
						<td class="tdright <?php if($status==1) {?>master<?php }?>" style="width:20%"><?php if($status==1) { echo "Completed"; } else { echo "Not Completed";}?></td>
					</tr>
					<?php 
					while($rowqrydest=$qrydest->fetch_assoc())
					{
						extract($rowqrydest); 
						
						$status = $ObjDB->SelectSingleValueInt("SELECT fld_read_status 
																FROM itc_exp_dest_play_track 
																WHERE fld_schedule_id='".$id[0]."' AND fld_dest_id='".$fld_dest_id."' AND fld_schedule_type='15' 
																	AND fld_delstatus='0' AND fld_student_id='".$id[3]."'");
						?>
						<tr class="trclass">
							<td class="tdleft" colspan="2">&nbsp;&nbsp;&nbsp;
							<?php echo $ObjDB->SelectSingleValue("SELECT fld_dest_name 
																	FROM itc_exp_destination_master
																	WHERE fld_id='".$fld_dest_id."' AND fld_delstatus='0'"); ?></td>
							<td class="tdmiddle">Destination</td>
							<td class="tdright <?php if($status==1) {?>master<?php }?>"><?php if($status==1) { echo "Completed"; } else { echo "Not Completed";}?></td>
						</tr>
						<?php
						$qrytask = $ObjDB->QueryObject("SELECT fld_id, fld_task_name FROM itc_exp_task_master 
														WHERE fld_flag='1' AND fld_delstatus='0' AND fld_dest_id='".$fld_dest_id."' ORDER BY fld_order");
												
						while($rowqrytask=$qrytask->fetch_assoc())
						{
							extract($rowqrytask); 
							
							$status = $ObjDB->SelectSingleValueInt("SELECT fld_read_status 
																	FROM itc_exp_task_play_track 
																	WHERE fld_schedule_id='".$id[0]."' AND fld_task_id='".$fld_id."' AND fld_schedule_type='15' 
																		AND fld_delstatus='0' AND fld_student_id='".$id[3]."'");
							?>
							<tr class="trclass">
								<td class="tdleft" colspan="2">&nbsp;&nbsp;&nbsp;<?php echo $fld_task_name; ?></td>
								<td class="tdmiddle">Task</td>
								<td class="tdright <?php if($status==1) {?>master<?php }?>"><?php if($status==1) { echo "Completed"; } else { echo "Not Completed";}?></td>
							</tr>
							<?php
							$qryres = $ObjDB->QueryObject("SELECT fld_id, fld_res_name FROM itc_exp_resource_master 
															WHERE fld_flag='1' AND fld_delstatus='0' AND fld_task_id='".$fld_id."' ORDER BY fld_order");
							
							while($rowqryres=$qryres->fetch_assoc())
							{
								extract($rowqryres); 
								
								$status = $ObjDB->SelectSingleValueInt("SELECT fld_read_status 
																		FROM itc_exp_res_play_track 
																		WHERE fld_schedule_id='".$id[0]."' AND fld_res_id='".$fld_id."' AND fld_schedule_type='15' 
																			AND fld_delstatus='0' AND fld_student_id='".$id[3]."'");
								?>
								<tr class="trclass">
									<td class="tdleft" colspan="2">&nbsp;&nbsp;&nbsp;<?php echo $fld_res_name; ?></td>
									<td class="tdmiddle">Resource</td>
									<td class="tdright <?php if($status==1) {?>master<?php }?>"><?php if($status==1) { echo "Completed"; } else { echo "Not Completed";}?></td>
								</tr>
								<?php
							}
						}
					} ?>
					
				</tbody>
			</table>
			<?php
            if($qryschedules->num_rows!=$rowcount) {?>
			<br />
			<br />
			<?php }
		}
		else if($id[1]==7)
		{
			?>
			<table cellpadding="2" cellspacing="0" >
				<tbody>
					<tr class="trgray">
						<td class="tdleft" style="font-weight:bold; width:40%"><?php echo $modunnames; ?></td>
						<td class="tdmiddle" style="width:20%"><?php echo date("F d, Y",strtotime($startdate)); ?></td>
						<td class="tdmiddle" style="width:20%"><?php echo date("F d, Y",strtotime($enddate)); ?></td>
						<td class="tdright <?php if($status==1) {?>master<?php }?>" style="width:20%"><?php if($status==1) { echo "Completed"; } else { echo "Not Completed";}?></td>
					</tr>
					<?php
                    for($i=0;$i<$totalchapters;$i++)
                    {
                        $sesscompleted = 0;
                        $sess=$i;
                        $sess++;
                        
                        $viewedpages = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_module_play_track WHERE fld_schedule_id='".$id[0]."' AND fld_module_id='".$ids."' AND fld_tester_id='".$id[3]."' AND fld_schedule_type='".$id[1]."' AND fld_section_id='".$i."' AND fld_delstatus='0'");
                        
                        $totalpages = $ObjDB->SelectSingleValueInt("SELECT fld_points_possible FROM itc_module_performance_master WHERE fld_module_id='".$ids."' AND fld_session_id='".$i."' AND fld_delstatus='0' AND fld_performance_name='Total Pages'");
                        
                        $sesscount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_module_points_master WHERE fld_schedule_id='".$id[0]."' AND fld_module_id='".$ids."' AND fld_student_id='".$id[3]."' AND fld_schedule_type='".$id[1]."' AND fld_session_id='".$i."' AND fld_delstatus='0' AND fld_teacher_points_earned<>'' AND fld_points_earned<>''");
                        
                        $totalsess = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_module_wca_grade WHERE fld_schedule_id='".$id[0]."' AND fld_module_id='".$ids."' AND fld_session_id='".$i."' AND fld_flag='1'");
                        
                        if($sesscount==$totalsess && $viewedpages>=$totalpages)
                            $sesscompleted = 1;
                        else
                            $sesscompleted = 0;
                    	?>
            			<tr class="trgray">
							<td class="tdleft" colspan="2">&nbsp;&nbsp;&nbsp;<b> <?php echo "Chapter ".$sess; ?></b></td>
							<td class="tdmiddle"></td>
							<td class="tdright <?php if($sesscompleted==1) {?>master<?php }?>"><?php if($sesscompleted==1) { echo "Completed"; } else { echo "Not Completed";}?></td>
						</tr>
						<?php 
						$qry = $ObjDB->QueryObject("(SELECT a.fld_page_title AS pagetitle, a.fld_points AS possiblepoint, 
														(SELECT (CASE WHEN b.fld_lock='0' THEN b.fld_points_earned 
															WHEN b.fld_lock='1' THEN b.fld_teacher_points_earned END) 
														FROM itc_module_points_master AS b 
														WHERE b.fld_module_id=a.fld_module_id AND b.fld_type='0' 
															AND b.fld_session_id=a.fld_session_id 
															AND a.fld_preassment_id=b.fld_preassment_id 
															AND b.fld_student_id='".$id[3]."' 
															AND b.fld_schedule_id='".$id[0]."' 
															AND b.fld_schedule_type='".$id[1]."') AS pointsearned 		
													FROM itc_module_wca_grade AS a 
													LEFT JOIN itc_class_indassesment_master AS c ON (a.fld_module_id=c.fld_module_id)
													WHERE a.fld_module_id='".$ids."' AND a.fld_session_id='".$i."' AND a.fld_schedule_id='".$id[0]."'
														AND a.fld_flag='1' AND c.fld_class_id='".$id[2]."' AND c.fld_flag='1' 
													GROUP BY a.fld_preassment_id 
													ORDER BY a.fld_type, a.fld_preassment_id)");
													
						if($qry->num_rows>0)
						{
							while($row=$qry->fetch_assoc())
							{
								extract($row);
								if($pointsearned != '')
									$completecount = 1;
								else
                                                                {
									$pointsearned = '-';
									$completecount = 0;									
                                                                }
                                                                
                                                                $possible = $ObjDB->SelectSingleValue("SELECT fld_points FROM itc_module_wca_grade WHERE fld_schedule_id='".$id[0]."' AND fld_page_title='".$pagetitle."' AND fld_flag='1' AND fld_module_id='".$ids."'");
                                                                
								?>
                                <tr class="trclass">
                                    <td class="tdleft" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $pagetitle; ?></td>
                                    <td class="tdmiddle" align="right"><?php echo $pointsearned."/".$possiblepoint; ?></td>
                                    <td class="tdright <?php if($completecount==1) {?>master<?php }?>"><?php if($completecount==1) { echo "Completed"; } else { echo "Not Completed";}?></td>
                                </tr>
                                <?php
							}
						}
					}
					?>
                    </tbody>
			</table>
			<?php
            if($qryschedules->num_rows!=$rowcount) {?>
			<br />
			<br />
			<?php }
		}
		else
		{
			?>
			<table cellpadding="2" cellspacing="0" >
				<tbody>
					<tr class="trgray">
						<td class="tdleft" style="font-weight:bold; width:40%"><?php echo $modunnames; ?></td>
						<td class="tdmiddle" style="width:20%"><?php echo date("F d, Y",strtotime($startdate)); ?></td>
						<td class="tdmiddle" style="width:20%"><?php echo date("F d, Y",strtotime($enddate)); ?></td>
						<td class="tdright <?php if($status==1) {?>master<?php }?>" style="width:20%"><?php if($status==1) { echo "Completed"; } else { echo "Not Completed";}?></td>
					</tr>
					
					<?php 
					for($i=0;$i<8;$i++)
					{
						$sesscompleted = 0;
						$sess=$i;
						$sess++;
						
						if($id[1]==6 || $id[1]==4)
						{
							$qrymath = $ObjDB->QueryObject("SELECT fld_session_day1, fld_session_day2, fld_ipl_day1, fld_ipl_day2 
															FROM itc_mathmodule_master 
															WHERE fld_id='".$ids."'");
							$rowqrymath=$qrymath->fetch_assoc();
							extract($rowqrymath);
				
							if($i==$fld_session_day1)
							{ 
								$lessoncount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
											FROM itc_assignment_sigmath_master 
											WHERE fld_schedule_id='".$id[0]."' 
												AND fld_student_id='".$id[3]."' AND fld_test_type='".$testtype."' AND fld_module_id='".$ids."'
												AND fld_class_id='".$id[2]."' AND fld_lesson_id IN (".$fld_ipl_day1.") 
												AND fld_delstatus='0' AND (fld_status='1' OR fld_status='2' OR fld_lock='1')");
								
								$ipl1ids = explode(",",$fld_ipl_day1);
								?>
								<tr class="trgray">
									<td class="tdleft" style="font-weight:bold">&nbsp;&nbsp;&nbsp;Diagnostic Day1</td>
									<td class="tdmiddle"></td>
									<td class="tdmiddle"></td>
									<td class="tdright <?php if($lessoncount==4) {?>master<?php }?>"><?php if($lessoncount==4) { echo "Completed"; } else { echo "Not Completed";}?></td>
								</tr>
								<?php
								for($l=0;$l<sizeof($ipl1ids);$l++)
								{
									$comlesson = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
												FROM itc_assignment_sigmath_master 
												WHERE fld_schedule_id='".$id[0]."' AND fld_student_id='".$id[3]."' 
													AND fld_test_type='".$testtype."' AND fld_class_id='".$id[2]."' AND fld_module_id='".$ids."'
													AND fld_lesson_id = '".$ipl1ids[$l]."' AND fld_delstatus='0' AND fld_status='1'");
									?>
									<tr class="trclass">
										<td class="tdleft" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<?php echo $ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_ipl_name,' ',b.fld_version) 
																		FROM itc_ipl_master AS a 
																		LEFT JOIN itc_ipl_version_track AS b ON a.fld_id=b.fld_ipl_id 
																		WHERE a.fld_id='".$ipl1ids[$l]."' AND b.fld_zip_type='1' 
																			AND b.fld_delstatus='0' AND a.fld_access='1' ");?></td>
										<td class="tdmiddle"></td>
										<td class="tdright <?php if($comlesson==1){?>master<?php }?>"><?php if($comlesson==1) { echo "Completed"; } else { echo "Not Completed";}?></td>
									</tr>
									<?php
								}
							} 
								
							if($i==$fld_session_day2)
							{ 
								$lessoncount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
										FROM itc_assignment_sigmath_master 
										WHERE fld_schedule_id='".$id[0]."' AND fld_student_id='".$id[3]."' AND fld_test_type='".$testtype."' AND fld_module_id='".$ids."' 
											AND fld_class_id='".$id[2]."' AND fld_lesson_id IN (".$fld_ipl_day2.") AND fld_delstatus='0' AND (fld_status='1' OR fld_status='2' OR fld_lock='1')");
								
								$ipl2ids = explode(",",$fld_ipl_day2);
								?>
								<tr class="trgray">
									<td class="tdleft" style="font-weight:bold">&nbsp;&nbsp;&nbsp;Diagnostic Day2</td>
									<td class="tdmiddle"></td>
									<td class="tdmiddle"></td>
									<td class="tdright <?php if($lessoncount==4) {?>master<?php }?>"><?php if($lessoncount==4) { echo "Completed"; } else { echo "Not Completed";}?></td>
								</tr>
								<?php
								for($m=0;$m<4;$m++)
								{
									$com2lesson = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
											FROM itc_assignment_sigmath_master
											WHERE fld_schedule_id='".$id[0]."' AND fld_student_id='".$id[3]."' 
												AND fld_test_type='".$testtype."' AND fld_class_id='".$id[2]."' AND fld_module_id='".$ids."'
												AND fld_lesson_id = '".$ipl2ids[$m]."' AND fld_delstatus='0' AND fld_status='1'");
									?>
									<tr class="trclass">
										<td class="tdleft" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<?php echo $ObjDB->SelectSingleValue("SELECT CONCAT(fld_ipl_name,' ',b.fld_version) 
																		FROM itc_ipl_master AS a 
																		LEFT JOIN itc_ipl_version_track AS b ON a.fld_id=b.fld_ipl_id 
																		WHERE a.fld_id='".$ipl2ids[$m]."' AND b.fld_zip_type='1' 
																			AND b.fld_delstatus='0' AND a.fld_access='1' ");?></td>
										<td class="tdmiddle"></td>
										<td class="tdright <?php if($com2lesson==1){?>master<?php }?>"><?php if($com2lesson==1) { echo "Completed"; } else { echo "Not Completed";}?></td>
									</tr>
									<?php
								}
							}
						}
				
						if($i<7)
						{
							if($id[1]==4)
								$newmodid = $ObjDB->SelectSingleValueInt("SELECT fld_module_id FROM itc_mathmodule_master WHERE fld_id='".$ids."'");
							else
								$newmodid = $ids;
								
							$sesscount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_module_points_master WHERE fld_schedule_id='".$id[0]."' AND fld_module_id='".$ids."' AND fld_student_id='".$id[3]."' AND fld_schedule_type='".$id[1]."' AND fld_preassment_id='0' AND fld_session_id='".$i."' AND fld_delstatus='0' AND fld_type<>'0'");
							
							$viewedpages = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_module_play_track WHERE fld_schedule_id='".$id[0]."' AND fld_module_id='".$ids."' AND fld_tester_id='".$id[3]."' AND fld_schedule_type='".$id[1]."' AND fld_section_id='".$i."' AND fld_delstatus='0'");
							
							$totalpages = $ObjDB->SelectSingleValueInt("SELECT fld_points_possible FROM itc_module_performance_master WHERE fld_module_id='".$newmodid."' AND fld_session_id='".$i."' AND fld_delstatus='0' AND fld_performance_name='Total Pages'");
							
							$totalsess = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_module_performance_master WHERE (fld_performance_name = 'Attendance' OR fld_performance_name = 'Participation') AND fld_module_id='".$newmodid."' AND fld_delstatus='0' AND fld_session_id='".$i."'");
							
							if($sesscount==$totalsess && $viewedpages>=$totalpages)
								$sesscompleted = 1;
							else
								$sesscompleted = 0;
						}
						else
						{
							$sesscount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_module_points_master WHERE fld_schedule_id='".$id[0]."' AND fld_module_id='".$ids."' AND fld_student_id='".$id[3]."' AND fld_schedule_type='".$id[1]."' AND fld_preassment_id<>'0' AND fld_session_id='0' AND fld_delstatus='0'");
							if($sesscount==3)
								$sesscompleted = 1;
							else
								$sesscompleted = 0;
						}
						
						
						?>
						<tr class="trgray">
							<td class="tdleft" colspan="2">&nbsp;&nbsp;&nbsp;<b> <?php if($i<7) { echo "Session ".$sess; } else { echo "Performance Assessments"; }?></b></td>
							<td class="tdmiddle"></td>
							<td class="tdright <?php if($sesscompleted==1) {?>master<?php }?>"><?php if($sesscompleted==1) { echo "Completed"; } else { echo "Not Completed";}?></td>
						</tr>
                        <?php
						if($i<7)
						{
							for($j=0;$j<3;$j++)
							{
								$pagecount = 0;
								if(($sess==6 && $j!=0) || $sess<6 || $sess==7)
								{
									$qrypoint = $ObjDB->QueryObject("SELECT COUNT(fld_id) AS pagecount, (CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END) AS earnedpoint FROM itc_module_points_master WHERE fld_schedule_id='".$id[0]."' AND fld_module_id='".$ids."' AND fld_student_id='".$id[3]."' AND fld_schedule_type='".$id[1]."' AND fld_preassment_id='0' AND fld_session_id='".$i."' AND fld_type='".$j."' AND fld_delstatus='0' AND (fld_points_earned<>'' OR fld_teacher_points_earned<>'')");
									
									if($qrypoint->num_rows>0)
									{
										$rowqrypoint=$qrypoint->fetch_assoc();
										extract($rowqrypoint);
									}
									else
									{
										$pagecount = 0;
										$earnedpoint = '';
									}
									
									if($i==0 && $j==0)
										$pagetitle='Module Guide';
									if($i==1 && $j==0)
										$pagetitle='RCA 2';
									if($i==2 && $j==0)
										$pagetitle='RCA 3';
									if($i==3 && $j==0)
										$pagetitle='RCA 4';
									if($i==4 && $j==0)
										$pagetitle='RCA 5';
									if($i==5 && $j==0)
										$pagetitle='RCA 6';
									if($i==6 && $j==0)
										$pagetitle='Post Test';
									if($j==1)
										$pagetitle='Attendance';
									if($j==2)
										$pagetitle='Participation';
									
									if($id[1]>4) //4
									{
										$possible = $ObjDB->SelectSingleValue("SELECT fld_points FROM itc_module_wca_grade WHERE fld_schedule_id='".$id[0]."' AND fld_page_title='".addslashes($pagetitle)."' AND fld_flag='1' AND fld_module_id='".$ids."'");
									}
									else
									{
                                                                            if($id[1]==4)
                                                                                    $newschtype = 2;
                                                                            else
                                                                                    $newschtype = 1;
                                                                            
									    $schoolid = $ObjDB->SelectSingleValue("SELECT fld_school_id FROM itc_user_master WHERE fld_id='".$id[3]."'");
                                                                            $indid = $ObjDB->SelectSingleValue("SELECT fld_user_id FROM itc_user_master WHERE fld_id='".$id[3]."'");
                                                                            
                                                                            $possible = $ObjDB->SelectSingleValue("SELECT fld_points FROM itc_module_wca_grade WHERE fld_schedule_id='0' AND fld_page_title='".addslashes($pagetitle)."' AND fld_flag='1' AND fld_module_id='".$ids."' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_schedule_type='".$newschtype."'");

                                                                            if($possible == '')
                                                                            {
                                                                                $createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
                                                                                
                                                                                $possible = $ObjDB->SelectSingleValue("SELECT fld_points FROM itc_module_wca_grade WHERE fld_schedule_id='0' AND fld_page_title='".addslashes($pagetitle)."' AND fld_flag='1' AND fld_schedule_type='".$newschtype."' AND fld_module_id='".$ids."' AND fld_created_by IN (".$createdids.")");
                                                                                if($possible=='')
                                                                                {
                                                                                    if($j==0)
                                                                                        $possible = $ObjDB->SelectSingleValue("SELECT fld_points FROM itc_module_grade WHERE fld_page_title='".addslashes($pagetitle)."' AND fld_flag='1' AND fld_module_id='".$ids."'");
                                                                                    else
                                                                                        $possible = $ObjDB->SelectSingleValue("SELECT fld_points_possible FROM itc_module_performance_master WHERE fld_performance_name='".addslashes($pagetitle)."' AND fld_delstatus='0' AND fld_module_id='".$ids."'");
                                                                                }
                                                                            }
									}
									
									if($earnedpoint=='' && $pagecount!=1)
										$earnedpoint = '-';
									?>
									<tr class="trclass">
										<td class="tdleft" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $pagetitle; ?></td>
										<td class="tdmiddle" align="right"><?php echo $earnedpoint."/".$possible; ?></td>
										<td class="tdright <?php if($pagecount==1) {?>master<?php }?>"><?php if($pagecount==1) { echo "Completed"; } else { echo "Not Completed";}?></td>
									</tr>
									<?php
								}
							}
						}
						else
						{
							if($id[1]==4 || $id[1]==6)
								$modules = $ObjDB->SelectSingleValueInt("SELECT fld_module_id FROM itc_mathmodule_master WHERE fld_id='".$ids."'");
							else
								$modules = $ids;
								
							$perqry = $ObjDB->QueryObject("SELECT fld_performance_name, fld_id, fld_points_possible FROM itc_module_performance_master WHERE fld_module_id='".$modules."' AND fld_delstatus='0' AND fld_performance_name<>'Attendance' AND fld_performance_name<>'Participation' AND fld_performance_name<>'Total Pages'");
							while($rowper=$perqry->fetch_assoc())
							{
								extract($rowper); 
								
								$qrypoint = $ObjDB->QueryObject("SELECT COUNT(fld_id) AS percount, (CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END) AS earnedpoints FROM itc_module_points_master WHERE fld_schedule_id='".$id[0]."' AND fld_module_id='".$ids."' AND fld_student_id='".$id[3]."' AND fld_schedule_type='".$id[1]."' AND fld_preassment_id='".$fld_id."' AND fld_session_id='0' AND fld_delstatus='0' AND (fld_points_earned<>'' OR fld_teacher_points_earned<>'')");
									
								if($qrypoint->num_rows>0)
								{
									$rowqrypoint=$qrypoint->fetch_assoc();
									extract($rowqrypoint);
								}
								else
								{
									$percount = 0;
									$earnedpoints = '';
								}
								if($earnedpoints=='' && $percount!=1)
									$earnedpoints = '-';
								
								if($id[1]<5)
                                                                {
                                                                    $schoolid = $ObjDB->SelectSingleValue("SELECT fld_school_id FROM itc_user_master WHERE fld_id='".$id[3]."'");
                                                                    $indid = $ObjDB->SelectSingleValue("SELECT fld_user_id FROM itc_user_master WHERE fld_id='".$id[3]."'");
                                                                    $possible = $ObjDB->SelectSingleValue("SELECT fld_points FROM itc_module_wca_grade WHERE fld_schedule_id='0' AND fld_page_title='".addslashes($fld_performance_name)."' AND fld_flag='1' AND fld_module_id='".$modules."' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."'");

                                                                    if($possible == '')
                                                                    {
                                                                        $createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
                                                                        $possible = $ObjDB->SelectSingleValue("SELECT fld_points FROM itc_module_wca_grade WHERE fld_schedule_id='0' AND fld_page_title='".addslashes($fld_performance_name)."' AND fld_flag='1' AND fld_module_id='".$modules."' AND fld_created_by IN (".$createdids.")");
                                                                        if($possible=='')
                                                                        {
                                                                            $possible = $ObjDB->SelectSingleValue("SELECT fld_points_possible FROM itc_module_performance_master WHERE fld_performance_name='".addslashes($fld_performance_name)."' AND fld_module_id='".$modules."'");
                                                                        }
                                                                    }
                                                                }
								else
									$possible = $ObjDB->SelectSingleValue("SELECT fld_points FROM itc_module_wca_grade WHERE fld_schedule_id='".$id[0]."' AND fld_page_title='".addslashes($fld_performance_name)."' AND fld_flag='1' AND fld_module_id='".$modules."'");
								?>
								<tr class="trclass">
									<td class="tdleft" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $fld_performance_name;?></td>
									<td class="tdmiddle" align="right" style="margin-right:20px;"><?php echo $earnedpoints."/".$possible;?></td>
									<td class="tdright <?php if($percount==1) {?>master<?php }?>"><?php if($percount==1) { echo "Completed"; } else { echo "Not Completed";}?></td>
								</tr>
								<?php
							}
						}
					}
					?>
				</tbody>
			</table>
			<?php if($qryschedules->num_rows!=$rowcount) {?>
			<br />
			<br />
			<?php }
		}
	} 
}
else
{
	echo "No Records";
}	