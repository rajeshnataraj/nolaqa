<?php 
@include("table.class.php");
@include("comm_func.php");
$method = $_REQUEST;
$id = isset($method['id']) ? $method['id'] : '0';
$id = explode(",",$id);

$start = isset($method['start']) ? $method['start'] : '0';
$end = isset($method['end']) ? $method['end'] : '1';
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
	
	.tdrighthead{
		border-right:1px solid #b4b4b4; border-bottom:1px solid #b4b4b4;
	}
</style>
<?php 
$qry = '';
if($id[4]==1)
	$qry = "SELECT a.fld_student_id, CONCAT(b.fld_fname,' ',b.fld_lname) AS nam 
			FROM itc_class_sigmath_student_mapping AS a 
			LEFT JOIN itc_user_master AS b ON a.fld_student_id = b.fld_id
			WHERE a.fld_sigmath_id='".$id[2]."' AND a.fld_flag='1' AND b.fld_delstatus='0' 
			LIMIT ".$start.",".$end.""; 
else
	$qry = "SELECT a.fld_student_id, CONCAT(c.fld_fname,' ',c.fld_lname) AS nam 
			FROM itc_class_sigmath_student_mapping AS a 
			LEFT JOIN itc_assignment_sigmath_master AS b ON (a.fld_student_id=b.fld_student_id AND a.fld_sigmath_id=b.fld_schedule_id) 
			LEFT JOIN itc_user_master AS c ON a.fld_student_id = c.fld_id
			WHERE b.fld_schedule_id='".$id[2]."' AND a.fld_flag='1' AND b.fld_delstatus='0' AND c.fld_delstatus='0'
			GROUP BY a.fld_student_id
			LIMIT ".$start.",".$end.""; 

$qrystudent = $ObjDB->QueryObject($qry);	
if($qrystudent->num_rows > 0)
{ 	 
	$b=1;
	while($rowqrystudent=$qrystudent->fetch_assoc())
	{
		extract($rowqrystudent);
		?>
		<table cellpadding="5" cellspacing="0">
			<tr style="font-weight:bold">
				<th style="width:50%"><?php echo $nam; ?></th>
                <th style="width:30%">Mastery Step</th>
                <th style="width:20%">Mastery Date</th>
			</tr>
		
			<tbody>
				<?php
				if($id[4]==1)
					$qryunit = $ObjDB->QueryObject("SELECT a.fld_unit_id, b.fld_unit_name AS unitname 
													FROM itc_class_sigmath_unit_mapping AS a 
													LEFT JOIN itc_unit_master AS b ON b.fld_id=a.fld_unit_id 
													WHERE a.fld_sigmath_id='".$id[2]."' AND a.fld_flag='1' AND b.fld_delstatus='0'
													ORDER BY a.fld_order");
				else
					$qryunit = $ObjDB->QueryObject("SELECT a.fld_unit_id, c.fld_unit_name AS unitname 
													FROM itc_class_sigmath_unit_mapping AS a 
													LEFT JOIN itc_assignment_sigmath_master AS b ON (a.fld_unit_id=b.fld_unit_id AND a.fld_sigmath_id=b.fld_schedule_id) 
													LEFT JOIN itc_unit_master AS c ON c.fld_id=a.fld_unit_id 
													WHERE b.fld_schedule_id='".$id[2]."' AND a.fld_flag='1' AND b.fld_delstatus='0' AND b.fld_student_id='".$fld_student_id."'
													GROUP BY a.fld_unit_id
													ORDER BY a.fld_order"); 
				
				
				if($qryunit->num_rows > 0)
				{ 
					while($rowqryunit=$qryunit->fetch_assoc())
					{
						extract($rowqryunit);
						?>
						<tr style="font-weight:bold">
							<td colsapn="3">&nbsp;&nbsp;&nbsp;<?php echo $unitname; ?></td>
                        </tr>
                        <?php
                        if($id[4]==1)
							$qryipl = $ObjDB->QueryObject("SELECT a.fld_lesson_id, b.fld_ipl_name AS lessonname 
															FROM itc_class_sigmath_lesson_mapping AS a 
															LEFT JOIN itc_ipl_master AS b ON b.fld_id=a.fld_lesson_id 
															WHERE a.fld_sigmath_id='".$id[2]."' AND b.fld_unit_id='".$fld_unit_id."' 
															ORDER BY a.fld_order");
						else
							$qryipl = $ObjDB->QueryObject("SELECT a.fld_lesson_id, c.fld_ipl_name AS lessonname 
															FROM itc_class_sigmath_lesson_mapping AS a 
															LEFT JOIN itc_assignment_sigmath_master AS b ON (a.fld_lesson_id=b.fld_lesson_id AND a.fld_sigmath_id=b.fld_schedule_id) 
															LEFT JOIN itc_ipl_master AS c ON a.fld_lesson_id=c.fld_id
															WHERE b.fld_schedule_id='".$id[2]."' AND b.fld_unit_id='".$fld_unit_id."' AND a.fld_flag='1' AND b.fld_delstatus='0' 
                                                                                                                            AND b.fld_student_id='".$fld_student_id."'
															GROUP BY a.fld_lesson_id 
															ORDER BY a.fld_order");
                        
                        if($qryipl->num_rows > 0)
                        {
                            while($rowqryipl=$qryipl->fetch_assoc())
                            {
                                extract($rowqryipl);
								?>
                                <tr>
									<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $lessonname; ?></td>
                                <?php
								$qrypoints = $ObjDB->QueryObject("SELECT fld_status, fld_type, fld_lock, (CASE WHEN fld_type=1 THEN 'Diagnostic' WHEN fld_type=3 THEN 'Mastery1' 
																	WHEN fld_type=4 THEN 'Mastery2' END) AS typename, fld_created_date, fld_updated_date 
																FROM itc_assignment_sigmath_master 
																WHERE fld_schedule_id='".$id[2]."' AND fld_test_type='1' AND fld_unit_id='".$fld_unit_id."' 
																	AND fld_lesson_id='".$fld_lesson_id."' AND fld_student_id='".$fld_student_id."'");
								if($qrypoints->num_rows > 0)
								{
									$rowqrypoints=$qrypoints->fetch_assoc();
									extract($rowqrypoints);
									$duedate = date("F d, Y",strtotime($fld_created_date));
									if($fld_lock==1)
									{
										$typename = "Teacher Entry";
                                                                                if($fld_updated_date == '0000-00-00 00:00:00')
                                                                                    $duedate = date("F d, Y",strtotime($fld_created_date));
                                                                                else
                                                                                    $duedate = date("F d, Y",strtotime($fld_updated_date));
									}
									if($fld_status==0) 
									{
										$status = "In Progress";
										$duedate = "--------";
									}
									else if($fld_status==1) 
										$status = "Mastered (".$typename.")";
									else if($fld_status==2) 
										$status = "Not Mastered (".$typename.")";
									
								}
								else
								{
									$status = "Not started";
									$duedate = "--------";
								}
								?>
									<td><?php echo $status; ?></td>
									<td><?php echo $duedate; ?></td>
                                </tr>
                                <?php        	
                            }
                        }
						else
						{
							?>
                            <tr>
                                <td colspan="3">No IPLs</td>
                            </tr>
                            <?php
						}
					}
				} 
				else
				{
					?>
					<tr>
						<td colspan="3">No Units</td>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>
        <?php
		$b++;
		if($qrystudent->num_rows!=$b-1) {?>
        <div style="page-break-before: always;">&nbsp;</div>
		<?php
		}
	}
}
else
{
	?>
	<tr>
		<td colspan="3">No Records</td>
	</tr>
	<?php
}

@include("footer.php");