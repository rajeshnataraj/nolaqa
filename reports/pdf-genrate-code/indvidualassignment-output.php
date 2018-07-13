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
if($id[5] == 0){
	$qry = "SELECT (CASE WHEN a.fld_unitmark = '0' THEN CONCAT(c.fld_ipl_name,' ',d.fld_version) WHEN a.fld_unitmark = '1' THEN 'CGA Unit' END) AS assignname, b.fld_unit_name AS unitname, 
					(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' THEN a.fld_teacher_points_earned END) AS pointsearned, 1 AS grade, 
					a.fld_points_possible AS pointspossible 
			FROM itc_assignment_sigmath_master AS a 
			LEFT JOIN itc_unit_master AS b ON b.fld_id=a.fld_unit_id 
			LEFT JOIN itc_ipl_master AS c ON c.fld_id=a.fld_lesson_id 
			LEFT JOIN itc_ipl_version_track AS d ON d.fld_ipl_id=c.fld_id
			WHERE a.fld_class_id='".$id[1]."' AND a.fld_test_type='1' AND a.fld_student_id='".$id[2]."' AND a.fld_rubrics_id='0' AND a.fld_schedule_id='".$id[3]."' 
					AND a.fld_unit_id='".$id[4]."' AND (a.fld_status='1' OR a.fld_status='2' OR a.fld_lock='1') AND c.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_zip_type='1'";
}
else if($id[5] != 7) {
	$qry = "SELECT fld_session_id, fld_type, (CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned
				END) AS pointsearned, fld_points_possible AS pointspossible, fld_grade AS grade, fld_preassment_id  
			 FROM itc_module_points_master 
			 WHERE fld_student_id='".$id[2]."' AND fld_module_id='".$id[4]."' AND fld_schedule_id='".$id[3]."' 
				AND fld_schedule_type='".$id[5]."' AND fld_type<>'3' AND fld_delstatus='0'
			 GROUP BY fld_session_id, fld_type
                         UNION ALL
               SELECT fld_session_id, fld_type, (CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned
                        END) AS pointsearned, fld_points_possible AS pointspossible, fld_grade AS grade, fld_preassment_id
                 FROM itc_module_points_master 
                 WHERE fld_student_id='".$id[2]."' AND fld_module_id='".$id[4]."' AND fld_schedule_id='".$id[3]."' 
                        AND fld_schedule_type='".$id[5]."' AND fld_type='3' AND fld_delstatus='0'";
}
else {
	$qry = "SELECT a.fld_session_id, a.fld_preassment_id, a.fld_type, (CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
				THEN a.fld_teacher_points_earned END) AS pointsearned, a.fld_points_possible AS pointspossible, a.fld_grade AS grade, 
				b.fld_page_title AS assignname  
			FROM itc_module_points_master AS a 
			LEFT JOIN itc_module_quest_details AS b ON (a.fld_module_id=b.fld_module_id AND a.fld_session_id=b.fld_section_id 
				AND a.fld_preassment_id=b.fld_page_id)
			WHERE a.fld_student_id='".$id[2]."' AND a.fld_module_id='".$id[4]."' AND a.fld_schedule_id='".$id[3]."' 
				AND a.fld_schedule_type='".$id[5]."' AND a.fld_delstatus='0' AND b.fld_flag='1' GROUP BY b.fld_page_id";
}

$qryindividual= $ObjDB->QueryObject($qry);

$cntchk="";
?>
<table cellpadding="0" cellspacing="0" >
	<?php
	if($qryindividual->num_rows > 0){ 
		if($id[5] == 0)
		{
			?>
			<br />
			<tr style="font-size:35px; font-weight:bold">
				<td>IPLs</td>
				<td>Points Earned</td>
				<td>Points Possible</td>
			</tr>
			<?php 
		}
		if($id[5]==4 || $id[5]==6)
		{
			$qrymath = $ObjDB->QueryObject("SELECT fld_session_day1, fld_session_day2, fld_ipl_day1, fld_ipl_day2 
											FROM itc_mathmodule_master 
											WHERE fld_id='".$id[4]."'");
			$rowqrymath=$qrymath->fetch_assoc();
			extract($rowqrymath);
		}
		if($id[5]==8)
		{
			?>
			<br />
			<tr style="font-size:35px; font-weight:bold">
				<td>Custom Content</td>
				<td>Points Earned</td>
				<td>Points Possible</td>
			</tr>
			<?php 
			$assignname = $ObjDB->SelectSingleValue("SELECT fld_contentname
													FROM itc_customcontent_master 
													WHERE fld_id='".$id[4]."'");
			$grade = 1;
		}

		$cnt=0;
		
		$counts = 0;
		while($rowqryindividual=$qryindividual->fetch_assoc())
		{	
			$counts++;			
			extract($rowqryindividual);
			
			if($id[5]!=0 and $id[5]!=8)
			{
				if($fld_type==1)
				{
					$assignname = "Attendance";
				}
				else if($fld_type==2)
				{
					$assignname = "Participation";
				}
                                else if($fld_type==3)
				{
					$assignname = $ObjDB->SelectSingleValue("SELECT fld_performance_name FROM itc_module_performance_master WHERE fld_id='".$fld_preassment_id."'");
				}
				else if($fld_type==0 and $id[5] != 7)
				{
					if($fld_session_id==0)
						$assignname = "Module Guide";
					else if($fld_session_id==1)
						$assignname = "RCA 2";
					else if($fld_session_id==2)
						$assignname = "RCA 3";
					else if($fld_session_id==3)
						$assignname = "RCA 4";
					else if($fld_session_id==4)
						$assignname = "RCA 5";
					else if($fld_session_id==6)
						$assignname = "Post Test";
				}

				if($cntchk!=$fld_session_id)
				{
                                        $sessid=$fld_session_id+1; 
					if($id[5] == 7)
                                        {
                                            $title = "Chapter ".$sessid;
                                        }
					else
                                        {
                                            if($fld_type==3)
                                                $title = "Performance Assessments";
                                            else
                                                $title = "Session ".$sessid;
                                        }
					?>
					<br />
					<tr style="font-size:35px; font-weight:bold">
						<td><?php echo $title; ?></td>
						<td>Points Earned</td>
						<td>Points Possible</td>
					</tr>
					<?php 
					$cntchk=$fld_session_id;
					$cnt=0;
				}
			}
			?> 
			<tr class="<?php if($cnt==0) { ?>trclass<?php } else if($cnt==1) { ?>trgray<?php }?>">
				<td class="tdleft"><?php echo $assignname; if($grade==0) { echo "  (Not Graded)"; } ?></td>
				<td class="tdmiddle"><?php echo $pointsearned; ?></td>
				<td class="tdright"><?php echo $pointspossible; ?></td>
			</tr>
			<?php 
			if($cnt==0)
				$cnt=1;
			else if($cnt==1)
				$cnt=0;
				
			if($id[5]==4 || $id[5]==6)
			{
				if($id[5]==4)
					$diagtype = '2';
				else if($id[5]==6)
					$diagtype = '5';
					
				$sessids = $fld_session_id+1;
				if($fld_session_day1 == $sessids or $fld_session_day2 == $sessids or $counts==$qryindividual->num_rows)
				{
					$daycount = $ObjDB->SelectSingleValueInt("SELECT fld_type 
																FROM itc_module_points_master 
																WHERE fld_student_id='".$id[2]."' AND fld_module_id='".$id[4]."' AND fld_schedule_id='".$id[3]."'
																	AND fld_schedule_type='".$id[5]."' AND fld_type<>'3' AND fld_delstatus='0' AND fld_session_id='".$fld_session_id."' 
																GROUP BY fld_session_id, fld_type DESC LIMIT 0,1");
																
					if(($sessids==$fld_session_day1  or $fld_session_day1>$counts) and $fld_type==$daycount)
					{ 
						$day = "Diagnostic Day1"; 
						$earned = $ObjDB->SelectSingleValueInt("SELECT ROUND(SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END)/4) 
																FROM itc_assignment_sigmath_master 
																WHERE fld_schedule_id='".$id[3]."' AND fld_student_id='".$id[2]."' AND fld_test_type='".$diagtype."' AND fld_module_id='".$id[4]."' AND fld_class_id='".$id[1]."' 
																AND fld_lesson_id IN (".$fld_ipl_day1.") AND fld_delstatus='0' AND (fld_status='1' OR fld_status='2' OR fld_lock='1')");
						
						if($earned!='')
						{
							?>
							<br />
							<tr style="font-size:35px; font-weight:bold">
								<td><?php echo $day; ?></td>
								<td>Points Earned</td>
								<td>Points Possible</td>
							</tr>
							<tr class="trclass">
								<td class="tdleft"><?php echo $day; ?></td>
								<td class="tdmiddle"><?php echo $earned; ?></td>
								<td class="tdright">100</td>
							</tr>
							<?php
						}
					} 
					
					if(($sessids==$fld_session_day2  or ($fld_session_day2>$counts and $qryindividual->num_rows==$counts)) and $fld_type==$daycount)
					{ 
						$day = "Diagnostic Day2"; 
						$earned = $ObjDB->SelectSingleValueInt("SELECT ROUND(SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END)/4) 
																FROM itc_assignment_sigmath_master 
																WHERE fld_schedule_id='".$id[3]."' AND fld_student_id='".$id[2]."' AND fld_test_type='".$diagtype."' AND fld_class_id='".$id[1]."' 
																AND fld_lesson_id IN (".$fld_ipl_day2.") AND fld_delstatus='0' AND (fld_status='1' OR fld_status='2')");
						
						if($earned!='')
						{
							?>
							<br />
							<tr style="font-size:35px; font-weight:bold">
								<td><?php echo $day; ?></td>
								<td>Points Earned</td>
								<td>Points Possible</td>
							</tr>
							<tr class="trclass">
								<td class="tdleft"><?php echo $day; ?></td>
								<td class="tdmiddle"><?php echo $earned; ?></td>
								<td class="tdright">100</td>
							</tr>
							<?php
						}
					}
				}
			}
		}
	}
	else
	{ ?>
		<tr class="trgray">
			<td style="border:1px solid #b4b4b4;" colspan="3">No Records</td>
		</tr>
		<?php
	} ?>
</table>