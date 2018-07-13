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
	
	.tdrighthead{
		border-right:1px solid #b4b4b4; border-bottom:1px solid #b4b4b4;
	}
</style>
<?php 
$scheduleid = $id[2];

$qryunits = $ObjDB->QueryObject("SELECT a.fld_unit_id AS unitid, b.fld_unit_name AS unitname 
								FROM itc_class_sigmath_unit_mapping AS a
								LEFT JOIN itc_unit_master AS b ON b.fld_id=a.fld_unit_id 
								WHERE a.fld_sigmath_id='".$scheduleid."' AND a.fld_flag='1' AND b.fld_delstatus='0'");
								
if($qryunits->num_rows > 0)
{ 	 
	while($rowqryunits=$qryunits->fetch_assoc())
	{
		extract($rowqryunits);
		?>
		<table cellpadding="5" cellspacing="0" >
			<tr>
				<th class="tdrighthead" style="font-size:35px; font-weight:bold; width:30%; text-align:center"><?php echo $unitname; ?></th>
				<th class="tdrighthead" style="font-size:20px; width:10%">In Progress/ Not Started</th>
				<th class="tdrighthead" style="font-size:20px; width:10%">Not Mastered</th>
				<th class="tdrighthead" style="font-size:20px; width:10%">Mastered (Diagnostic)</th>
				<th class="tdrighthead" style="font-size:20px; width:10%">Mastered (Mastery 1)</th>
				<th class="tdrighthead" style="font-size:20px; width:10%">Mastered (Mastery 2)</th>
				<th class="tdrighthead" style="font-size:20px; width:10%">Mastered (Teacher Entry)</th>
				<th class="tdrighthead" style="font-size:20px; width:10%">NotMastered (Teacher Entry)</th>
			</tr>
		
			<tbody>
				<?php
				$qryipls = $ObjDB->QueryObject("SELECT a.fld_lesson_id, CONCAT(b.fld_ipl_name,' ',c.fld_version) AS lessonname 
												FROM itc_class_sigmath_lesson_mapping AS a 
												LEFT JOIN itc_ipl_master AS b ON b.fld_id=a.fld_lesson_id 
												LEFT JOIN itc_ipl_version_track AS c ON b.fld_id=c.fld_ipl_id 
												WHERE a.fld_sigmath_id='".$scheduleid."'  AND b.fld_unit_id='".$unitid."' AND a.fld_flag='1' AND b.fld_access='1' AND b.fld_delstatus='0' 
													 AND c.fld_zip_type='1' AND c.fld_delstatus='0'
												ORDER BY a.fld_order"); 

				if($qryipls->num_rows > 0)
				{ 
					while($rowqryipls=$qryipls->fetch_assoc())
					{
						extract($rowqryipls);
						?>
						<tr>
							<td class="tdright"><?php echo $lessonname; ?></td>
							
							<td class="tdmiddle"><?php echo $ObjDB->SelectSingleValueInt("SELECT (SELECT COUNT(fld_student_id) FROM `itc_class_sigmath_student_mapping` WHERE fld_sigmath_id='".$scheduleid."' AND fld_flag='1')-(SELECT COUNT(fld_student_id)  FROM `itc_assignment_sigmath_master` WHERE fld_class_id='".$id[1]."' AND fld_test_type='1' AND fld_schedule_id='".$scheduleid."' AND fld_unit_id='".$unitid."' AND fld_lesson_id='".$fld_lesson_id."' AND fld_status<>'0' ) AS a"); ?></td>
							
							<td class="tdmiddle"><?php echo $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_student_id) FROM `itc_assignment_sigmath_master` WHERE fld_class_id='".$id[1]."' AND fld_test_type='1' AND fld_schedule_id='".$scheduleid."' AND fld_unit_id='".$unitid."' AND fld_lesson_id='".$fld_lesson_id."' AND fld_status='2' AND fld_lock<>'1' "); ?></td>
							
							<td class="tdmiddle"><?php echo $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_student_id)  FROM `itc_assignment_sigmath_master` WHERE fld_class_id='".$id[1]."' AND fld_test_type='1' AND fld_schedule_id='".$scheduleid."' AND fld_unit_id='".$unitid."' AND fld_lesson_id='".$fld_lesson_id."' AND fld_status='1' AND fld_type='1' AND fld_lock<>'1' "); ?></td>
							
							<td class="tdmiddle"><?php echo $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_student_id)  FROM `itc_assignment_sigmath_master` WHERE fld_class_id='".$id[1]."' AND fld_test_type='1' AND fld_schedule_id='".$scheduleid."' AND fld_unit_id='".$unitid."' AND fld_lesson_id='".$fld_lesson_id."' AND fld_status='1' AND fld_type='3' AND fld_lock<>'1' "); ?></td>
							
							<td class="tdmiddle"><?php echo $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_student_id)  FROM `itc_assignment_sigmath_master` WHERE fld_class_id='".$id[1]."' AND fld_test_type='1' AND fld_schedule_id='".$scheduleid."' AND fld_unit_id='".$unitid."' AND fld_lesson_id='".$fld_lesson_id."' AND fld_status='1' AND fld_type='4' AND fld_lock<>'1' "); ?></td>
							
							<td class="tdmiddle"><?php echo $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_student_id)  FROM `itc_assignment_sigmath_master` WHERE fld_class_id='".$id[1]."' AND fld_test_type='1' AND fld_schedule_id='".$scheduleid."' AND fld_unit_id='".$unitid."' AND fld_lesson_id='".$fld_lesson_id."' AND fld_status='1' AND fld_lock='1' "); ?></td>
                            
							<td class="tdmiddle"><?php echo $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_student_id)  FROM `itc_assignment_sigmath_master` WHERE fld_class_id='".$id[1]."' AND fld_test_type='1' AND fld_schedule_id='".$scheduleid."' AND fld_unit_id='".$unitid."' AND fld_lesson_id='".$fld_lesson_id."' AND fld_status='2'  AND fld_lock='1' "); ?></td>
						</tr>
					<?php
					}
				} 
				?>
			</tbody>
		</table> 
		<?php
	}
}