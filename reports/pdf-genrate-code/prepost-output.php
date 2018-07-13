<?php 
error_reporting(0);
@include("table.class.php");
@include("comm_func.php");
$method = $_REQUEST;

$id = isset($method['id']) ? $method['id'] : '0';
$id = explode(",",$id);

$start = isset($method['start']) ? $method['start'] : '0';
$end = isset($method['end']) ? $method['end'] : '4';

$limit = $start.",".$end;

$scheduleid = $id[1];
$classid = $id[2];
$schtype = $id[3];
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
if($schtype==1 || $schtype==4)
{
	$query = "SELECT fld_rotation AS rotation, fld_rotation-1 AS realrotation 
				FROM itc_class_rotation_schedulegriddet 
				WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1' 
				GROUP BY fld_rotation 
				ORDER BY fld_rotation
				LIMIT ".$limit." ";	
				
	$querystudent = "SELECT a.fld_id AS studentid, CONCAT(a.fld_fname,' ',a.fld_lname) AS studentname
						FROM itc_user_master AS a 
						LEFT JOIN itc_class_rotation_schedulegriddet AS b ON a.fld_id=b.fld_student_id 
						WHERE b.fld_schedule_id = '".$scheduleid."' AND b.fld_class_id = '".$classid."' 
							AND fld_flag='1' AND a.fld_delstatus='0' AND a.fld_activestatus='1'
						GROUP BY studentid
						ORDER BY studentid";
							
	$tablename = "itc_class_rotation_schedulegriddet";
}
else if($schtype==2)
{
	$query = "SELECT fld_rotation AS rotation, fld_rotation AS realrotation 
				FROM itc_class_dyad_schedulegriddet 
				WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1' 
				GROUP BY fld_rotation 
				ORDER BY fld_rotation
				LIMIT ".$limit." ";
				
	$querystudent = "SELECT a.fld_id AS studentid, CONCAT(a.fld_fname,' ',a.fld_lname) AS studentname
						FROM itc_user_master AS a 
						LEFT JOIN itc_class_dyad_schedulegriddet AS b ON a.fld_id=b.fld_student_id 
						WHERE b.fld_schedule_id = '".$scheduleid."' AND b.fld_class_id = '".$classid."' 
							AND fld_flag='1' AND a.fld_delstatus='0' AND a.fld_activestatus='1'
						GROUP BY studentid
						ORDER BY studentid";
						
	$tablename = "itc_class_dyad_schedulegriddet";
}
else if($schtype==3)
{
	$query = "SELECT fld_rotation AS rotation, fld_rotation AS realrotation 
				FROM itc_class_triad_schedulegriddet 
				WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1' 
				GROUP BY fld_rotation 
				ORDER BY fld_rotation
				LIMIT ".$limit." ";
				
	$querystudent = "SELECT a.fld_id AS studentid, CONCAT(a.fld_fname,' ',a.fld_lname) AS studentname
						FROM itc_user_master AS a 
						LEFT JOIN itc_class_triad_schedulegriddet AS b ON a.fld_id=b.fld_student_id 
						WHERE b.fld_schedule_id = '".$scheduleid."' AND b.fld_class_id = '".$classid."' 
							AND fld_flag='1' AND a.fld_delstatus='0' AND a.fld_activestatus='1'
						GROUP BY studentid
						ORDER BY studentid";
						
	$tablename = "itc_class_triad_schedulegriddet";
}

$qryrot = $ObjDB->QueryObject($query);
$qrystudent = $ObjDB->QueryObject($querystudent);

if($qryrot->num_rows>0){
	$cnt=0;
	$rotationids = array();
	$realrotationids = array();
	while($rowqryrot = $qryrot->fetch_assoc())
	{
		extract($rowqryrot);
		$rotationid[$cnt] = $rotation;
		$realrotationid[$cnt] = $realrotation;
		$cnt++;
	}
	?>
	<table cellpadding="3" cellspacing="0">
		<tr style="font-size:35px; font-weight:bold;">
			<th style="width:20%">Student Name</th>
			<?php
			for($i=0;$i<$cnt;$i++)
			{
				?>
				<th style="width:20%" align="center"><?php if($realrotationid[$i]==0) echo "Orientation"; else echo "Rotation ".$realrotationid[$i];?></th>
				<?php 
			}?>
		</tr>
	<?php
	
	while($rowqrystudent = $qrystudent->fetch_assoc())
	{
		extract($rowqrystudent);
		?>
		<tr class="trgray">
			<td class="tdleft"><?php echo $studentname;?></td>
			<?php 
			$moduleids = array();
			$schtypes = array();
			for($j=0;$j<$cnt;$j++)
			{
				$rotids = $rotationid[$j];
                                $modulename = '';
				if($schtype==1) 
				{
					$qrymod = $ObjDB->QueryObject("SELECT a.fld_module_id AS modids, CONCAT(b.fld_module_name,' ',c.fld_version) AS modulename, 1 AS newtype  
													FROM itc_class_rotation_schedulegriddet AS a 
													LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
													LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
													WHERE a.fld_class_id='".$classid."' AND a.fld_student_id='".$studentid."' AND a.fld_schedule_id='".$scheduleid."' 
														AND a.fld_rotation='".$rotids."' AND a.fld_flag='1' AND a.fld_type = '1' AND b.fld_delstatus='0' AND c.fld_delstatus='0'
															UNION ALL 		
													SELECT a.fld_id AS modids, a.fld_contentname AS modulename, 8 AS newtype 
													FROM itc_customcontent_master AS a 
													LEFT JOIN itc_class_rotation_schedulegriddet AS b ON b.fld_module_id = a.fld_id 
													WHERE b.fld_class_id='".$classid."' AND b.fld_student_id='".$studentid."' AND b.fld_schedule_id = '".$scheduleid."' 
														AND fld_rotation='".$rotids."' AND b.fld_type = '8' AND b.fld_flag = '1' AND a.fld_delstatus = '0' ");
				}
				else if($schtype==2) 
				{
					$qrymod = $ObjDB->QueryObject("SELECT a.fld_module_id AS modids, CONCAT(b.fld_module_name,' ',c.fld_version) AS modulename, 2 AS newtype  
													FROM itc_class_dyad_schedulegriddet AS a 
													LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
													LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
													WHERE a.fld_class_id='".$classid."' AND (a.fld_student_id='".$studentid."' OR a.fld_rotation='0') 
														AND a.fld_schedule_id='".$scheduleid."' 
														AND a.fld_rotation='".$rotids."' AND a.fld_flag='1' AND b.fld_delstatus='0' AND c.fld_delstatus='0'");
				}
				else if($schtype==3) 
				{
					$qrymod = $ObjDB->QueryObject("SELECT a.fld_module_id AS modids, CONCAT(b.fld_module_name,' ',c.fld_version) AS modulename, 3 AS newtype  
													FROM itc_class_triad_schedulegriddet AS a 
													LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
													LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
													WHERE a.fld_class_id='".$classid."' AND (a.fld_student_id='".$studentid."' OR a.fld_rotation='0') 
														AND a.fld_schedule_id='".$scheduleid."' 
														AND a.fld_rotation='".$rotids."' AND a.fld_flag='1' AND b.fld_delstatus='0' AND c.fld_delstatus='0'");
				}
				else if($schtype==4) 
				{
					$qrymod = $ObjDB->QueryObject("SELECT a.fld_module_id AS modids, CONCAT(b.fld_mathmodule_name,' ',c.fld_version) AS modulename, 4 AS newtype  
													FROM itc_class_rotation_schedulegriddet AS a 
													LEFT JOIN itc_mathmodule_master AS b ON a.fld_module_id=b.fld_id 
													LEFT JOIN itc_module_version_track AS c ON b.fld_module_id=c.fld_mod_id 
													WHERE a.fld_class_id='".$classid."' AND a.fld_student_id='".$studentid."' AND a.fld_schedule_id='".$scheduleid."' 
														AND a.fld_rotation='".$rotids."' AND a.fld_flag='1' AND a.fld_type = '2' AND b.fld_delstatus='0' AND c.fld_delstatus='0'
															UNION ALL 		
													SELECT a.fld_id AS modids, a.fld_contentname AS modulename, 8 AS newtype 
													FROM itc_customcontent_master AS a 
													LEFT JOIN itc_class_rotation_schedulegriddet AS b ON b.fld_module_id = a.fld_id 
													WHERE b.fld_class_id='".$classid."' AND b.fld_student_id='".$studentid."' AND b.fld_schedule_id = '".$scheduleid."' 
														AND fld_rotation='".$rotids."' AND b.fld_type = '8' AND b.fld_flag = '1' AND a.fld_delstatus = '0'");
				}
				
				
				$rowqrymod = $qrymod->fetch_assoc();
				extract($rowqrymod);
				
				$moduleids[] = $modids;
				$schtypes[] = $newtype;
				?>
				<td style="font-size:24px;" align="center" class="<?php if($j!=$cnt-1) { ?>tdmiddle<?php } else { ?>tdright<?php }?>"><?php if($modulename=='') echo "No Module"; else echo $modulename;?></td>
				<?php 
			}?>
		</tr>
		<tr class="trclass">
			<td class="tdleft">Module Guide / Posttest</td>
			<?php 
			for($j=0;$j<$cnt;$j++)
			{
				$qrypoints = $ObjDB->QueryObject("SELECT IFNULL(GROUP_CONCAT(CASE WHEN fld_lock='1' AND fld_session_id='0' THEN fld_teacher_points_earned 
														WHEN fld_lock='0' AND fld_session_id='0' THEN fld_points_earned END),'- ') AS moduleguide,
														IFNULL(GROUP_CONCAT(CASE WHEN fld_lock='1' AND fld_session_id='6' THEN fld_teacher_points_earned 
														WHEN fld_lock='0' AND fld_session_id='6' THEN fld_points_earned END),' -') AS pretest
													FROM itc_module_points_master 
													WHERE fld_module_id='".$moduleids[$j]."' AND fld_schedule_type='".$schtypes[$j]."' 
														AND fld_student_id='".$studentid."' AND fld_schedule_id='".$scheduleid."' AND fld_type='0'
														AND (fld_session_id='0' OR fld_session_id='6')");
				
				if($qrypoints->num_rows>0){
					$rowqrypoints = $qrypoints->fetch_assoc();
					extract($rowqrypoints);
					
					$points = $moduleguide.' / '.$pretest;
				}
				else
					$points = '- / -';
				?>
				<td align="center" class="<?php if($j!=$cnt-1) { ?>tdmiddle<?php } else { ?>tdright<?php }?>"><?php echo $points;?></td>
				<?php 
			}?>
		</tr>
		<?php
	}
	?>
	</table>
	<?php
}