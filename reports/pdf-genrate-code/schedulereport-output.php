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
$qryschedules = $ObjDB->QueryObject("(SELECT a.fld_schedule_name AS sigmathschdule, a.fld_id, 0 AS typename 
									FROM itc_class_sigmath_master AS a 
									LEFT JOIN itc_class_sigmath_student_mapping AS b ON a.fld_id=b.fld_sigmath_id 
									WHERE a.fld_class_id='".$id[2]."' AND b.fld_student_id='".$id[1]."' 
											AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_flag='1') 		
										UNION ALL		
									(SELECT a.fld_schedule_name AS sigmathschdule, a.fld_id, (CASE WHEN a.fld_moduletype='1' THEN '1' 
											WHEN fld_moduletype='2' THEN '4' END) AS typename  
									FROM itc_class_rotation_schedule_mastertemp AS a 
									LEFT JOIN itc_class_rotation_schedule_student_mappingtemp AS b ON a.fld_id=b.fld_schedule_id 
									WHERE a.fld_class_id='".$id[2]."' AND b.fld_student_id='".$id[1]."' AND a.fld_delstatus='0' 
											AND b.fld_flag='1' AND a.fld_flag='1') 		
										UNION ALL 		
									(SELECT a.fld_schedule_name AS sigmathschdule, a.fld_id, 2 AS typename 
									FROM itc_class_dyad_schedulemaster AS a 
									LEFT JOIN itc_class_dyad_schedule_studentmapping AS b ON a.fld_id=b.fld_schedule_id 
									WHERE a.fld_class_id='".$id[2]."' AND b.fld_student_id='".$id[1]."' 
											AND a.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_flag='1') 		
										UNION ALL 		
									(SELECT a.fld_schedule_name AS sigmathschdule, a.fld_id, 3 AS typename 
									FROM itc_class_triad_schedulemaster AS a 
									LEFT JOIN itc_class_triad_schedule_studentmapping AS b ON a.fld_id=b.fld_schedule_id 
									WHERE a.fld_class_id='".$id[2]."' AND b.fld_student_id='".$id[1]."' 
											AND a.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_flag='1') 		
										UNION ALL 		
									(SELECT a.fld_schedule_name AS sigmathschdule, a.fld_id, (CASE WHEN a.fld_moduletype='1' THEN '5' 
											WHEN fld_moduletype='2' THEN '6' WHEN a.fld_moduletype='7' THEN '7' END) AS typename  
									FROM itc_class_indassesment_master AS a 
									LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
									WHERE a.fld_class_id='".$id[2]."' AND b.fld_student_id='".$id[1]."' 
											AND a.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_flag='1')"); 
	
if($qryschedules->num_rows > 0)
{ 	 
	while($rowschedules=$qryschedules->fetch_assoc())
	{
		extract($rowschedules);
		?>
		<span class="title"><?php echo $sigmathschdule;?></span>
		<br />
		<br />
		<table cellpadding="0" cellspacing="0" style="width:100%">
			<tr style="font-size:35px; font-weight:bold;" >
				<th style="width:50%">Assignment Name</th>
				<th style="width:25%">Start Date</th>
				<th style="width:25%">End Date</th>
			</tr>
		
			<tbody>
				<?php 
				$qry = '';
				if($typename==0)
				{
					$qry = "SELECT a.fld_unit_name AS assigmentname, c.fld_start_date AS startdate, c.fld_end_date AS enddate 
							FROM itc_unit_master AS a 
							LEFT JOIN itc_class_sigmath_unit_mapping AS b ON a.fld_id=b.fld_unit_id 
							LEFT JOIN itc_class_sigmath_master AS c ON c.fld_id=b.fld_sigmath_id 
							WHERE b.fld_sigmath_id='".$fld_id."' AND a.fld_activestatus='0' AND a.fld_delstatus='0' 
									AND b.fld_flag='1' AND c.fld_flag='1' AND c.fld_delstatus='0'";
				}
				else if($typename==1)
				{
					$qry = "SELECT CONCAT(c.fld_module_name,' ',d.fld_version,' Module') AS assigmentname, 
									e.fld_startdate AS startdate, e.fld_enddate AS enddate, b.fld_row_id 
							FROM itc_class_rotation_moduledet AS a 
							LEFT JOIN itc_class_rotation_schedulegriddet AS b ON (b.fld_row_id=a.fld_row_id AND b.fld_schedule_id=a.fld_schedule_id) 
                                                        LEFT JOIN itc_class_rotation_scheduledate AS e ON b.fld_schedule_id=e.fld_schedule_id AND b.fld_rotation=e.fld_rotation
							LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id
							LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id 
							WHERE b.fld_schedule_id='".$fld_id."' AND a.fld_flag=1 AND b.fld_student_id='".$id[1]."' AND b.fld_flag='1' AND e.fld_flag='1'
									AND c.fld_delstatus='0' AND d.fld_delstatus='0' 
							ORDER BY e.fld_startdate";
				} 
				else if($typename==2)
				{
					$qry = "SELECT CONCAT(c.fld_module_name,' ',d.fld_version,' Dyad') AS assigmentname, b.fld_startdate AS startdate, 
									b.fld_enddate AS enddate, b.fld_row_id 
							FROM itc_class_dyad_schedulegriddet AS b
							LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id 
							LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id 
							WHERE b.fld_schedule_id='".$fld_id."' AND (b.fld_student_id='".$id[1]."' OR b.fld_rotation='0') AND b.fld_flag='1' 
									AND c.fld_delstatus='0' AND d.fld_delstatus='0' 
							ORDER BY b.fld_startdate";
				} 
				else if($typename==3)
				{
					$qry = "SELECT CONCAT(c.fld_module_name,' ',d.fld_version,' Triad') AS assigmentname, b.fld_startdate AS startdate, 
									b.fld_enddate AS enddate, b.fld_row_id 
							FROM itc_class_triad_schedulegriddet AS b 
							LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id 
							LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id
							WHERE b.fld_schedule_id='".$fld_id."' AND (b.fld_student_id='".$id[1]."' OR b.fld_rotation='0') AND b.fld_flag='1' 
									AND c.fld_delstatus='0' AND d.fld_delstatus='0' 
							ORDER BY b.fld_startdate";
				}
				else if($typename==4)
				{
					$qry = "SELECT CONCAT(c.fld_mathmodule_name,' ',d.fld_version,' MM') AS assigmentname, e.fld_startdate AS startdate, 
									e.fld_enddate AS enddate, b.fld_row_id 
							FROM itc_class_rotation_moduledet AS a 
							LEFT JOIN itc_class_rotation_schedulegriddet AS b ON (b.fld_row_id=a.fld_row_id AND b.fld_schedule_id=a.fld_schedule_id) 
                                                        LEFT JOIN itc_class_rotation_scheduledate AS e ON b.fld_schedule_id=e.fld_schedule_id AND b.fld_rotation=e.fld_rotation
							LEFT JOIN itc_mathmodule_master AS c ON a.fld_module_id=c.fld_id 
							LEFT JOIN itc_module_version_track AS d ON c.fld_module_id=d.fld_mod_id
							WHERE b.fld_schedule_id='".$fld_id."' AND a.fld_flag=1 AND b.fld_student_id='".$id[1]."' AND a.fld_flag='1' 
									AND b.fld_flag='1' AND c.fld_delstatus='0' AND d.fld_delstatus='0'  AND e.fld_flag='1' 
							ORDER BY e.fld_startdate";
				} 
				else if($typename==5)
				{
					$qry = "SELECT CONCAT(b.fld_module_name,' ',c.fld_version,' Ind Module') AS assigmentname, a.fld_startdate AS startdate, a.fld_enddate AS enddate 
							FROM itc_class_indassesment_master AS a 
							LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
							LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id
							WHERE a.fld_id='".$fld_id."' AND a.fld_flag=1 AND b.fld_delstatus='0' AND c.fld_delstatus='0'
							ORDER BY a.fld_startdate";
				}
				else if($typename==6)
				{
					$qry = "SELECT CONCAT(b.fld_mathmodule_name,' ',c.fld_version,' Ind MM') AS assigmentname, a.fld_startdate AS startdate, a.fld_enddate AS enddate 
							FROM itc_class_indassesment_master AS a 
							LEFT JOIN itc_mathmodule_master AS b ON a.fld_module_id=b.fld_id 
							LEFT JOIN itc_module_version_track AS c ON b.fld_module_id=c.fld_mod_id
							WHERE a.fld_id='".$fld_id."' AND a.fld_flag=1 AND b.fld_delstatus='0' AND c.fld_delstatus='0'
							ORDER BY a.fld_startdate";
				}
				else if($typename==7)
				{
					$qry = "SELECT CONCAT(b.fld_module_name,' ',c.fld_version,' Ind Quest') AS assigmentname, a.fld_startdate AS startdate, a.fld_enddate AS enddate 
							FROM itc_class_indassesment_master AS a 
							LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
							LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id
							WHERE a.fld_id='".$fld_id."' AND a.fld_flag=1 AND b.fld_delstatus='0' AND c.fld_delstatus='0'
							ORDER BY a.fld_startdate";
				}  
				$qryvalues = $ObjDB->QueryObject($qry);
				
				if($qryvalues->num_rows > 0){
					$cnt=0;
					while($rowqryvalues=$qryvalues->fetch_assoc())
					{
						extract($rowqryvalues);  ?>
						<tr class="<?php if($cnt==0) { ?>trgray<?php } else if($cnt==1) { ?>trclass<?php }?>">
							<td class="tdleft"><?php echo $assigmentname; ?></td>
							<td class="tdmiddle"><?php echo date("F d, Y",strtotime($startdate)); ?></td>
							<td class="tdright"><?php echo date("F d, Y",strtotime($enddate)); ?></td>
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
						<td class="tdleft tdright" colspan="3">no records</td>
					</tr>
				<?php }
				?>
			</tbody>
		</table>
		<br />
		<br />
		<br />
		<?php
	} 
}
else
{
	echo "No Records";
}	