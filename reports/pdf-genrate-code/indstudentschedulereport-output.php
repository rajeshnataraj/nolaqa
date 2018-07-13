<?php 
@include("../../includes/table.class.php");
@include("../../includes/comm_func.php");
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
if($id[7]==1)
{
	$qryschedules = $ObjDB->QueryObject("SELECT fld_schedule_name AS sigmathschdule, fld_id AS schid, 1 AS typename FROM itc_class_dyad_schedulemaster WHERE fld_class_id='".$id[2]."' AND fld_delstatus='0' AND fld_dyadtableflg='1'"); 
}
else if($id[7]==2)
{
	$qryschedules = $ObjDB->QueryObject("SELECT fld_schedule_name AS sigmathschdule, fld_id AS schid, 2 AS typename FROM itc_class_triad_schedulemaster WHERE fld_class_id='".$id[2]."' AND fld_delstatus='0' AND fld_triadtableflg='1'"); 
}
$b = 0;		
if($qryschedules->num_rows > 0)
{ 	 
	while($rowschedules=$qryschedules->fetch_assoc())
	{
		extract($rowschedules);
		if($typename==1)
		{
			$stagetable = "itc_class_dyad_schedule_insstagemap";
			$moddettable = "itc_class_dyad_moduledet";
			$schgridtable = "itc_class_dyad_schedulegriddet";
			$triadcount = 2;
		}
		else if($typename==2)
		{
			$stagetable = "itc_class_triad_schedule_insstagemap";
			$moddettable = "itc_class_triad_moduledet";
			$schgridtable = "itc_class_triad_schedulegriddet";
			$triadcount = 3;
		}
		
		if($b!=0) { ?>
        <div style="page-break-before: always;">
        <?php }?>
		<span class="title" ><?php echo $sigmathschdule;?></span>
		<br />
		<br />
		<table cellpadding="1" cellspacing="0" border="1" align="center">
			<tr style="font-size:35px; font-weight:bold;">
				<th rowspan="3">&nbsp;</th>
				<?php
				$b++;
				for($j=2;$j<=5;$j++)
				{
					$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM ".$stagetable." 
															WHERE fld_schedule_id='".$schid."' AND fld_flag='1' AND fld_stagetype='3' 
																AND fld_startdate<>'0000-00-00' AND fld_stagevalue='".$j."'");
					if($count>0)
					{
						$colcount = $count*$triadcount;
						?>
						<th colspan="<?php echo $colcount; ?>"><div style="width: 100px;"><?php echo "Stage ".$j;?></div></th>
						<?php
					}
				}?>
			</tr>
			<tr style="font-size:35px; font-weight:bold;" class="trgray">
				<?php
				$qrydyadtriad = $ObjDB->QueryObject("SELECT fld_stagename, fld_id 
														FROM ".$stagetable." 
														WHERE fld_schedule_id='".$schid."' AND fld_flag='1' 
															AND fld_stagetype='3' AND fld_startdate<>'0000-00-00'");
				
				if($qrydyadtriad->num_rows > 0)
				{ 	 
					while($rowqrydyadtriad=$qrydyadtriad->fetch_assoc())
					{
						extract($rowqrydyadtriad);
						?>
						<th colspan="<?php echo $triadcount;?>"><div style="width: 100px;"><?php echo $fld_stagename;?></div></th>
						<?php
					}
				}?>
			</tr>
			<tr style="font-size:35px; font-weight:bold;">
				<?php
				for($i=1;$i<=$qrydyadtriad->num_rows*$triadcount;$i++)
				{ 	 
					?>
					<th><div style="width: 100px;"><?php echo "Rotation ".$i;?></div></th>
					<?php
				}?>
			</tr>
		
			<tbody>
				<tr class="trgray">
					<td>
						<?php echo $ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,' ',fld_lname) FROM itc_user_master WHERE fld_id='".$id[1]."'");?>
					</td>
					<?php
					for($i=1;$i<=$qrydyadtriad->num_rows*$triadcount;$i++)
					{ 	
						?>
						<td>
							<?php echo $ObjDB->SelectSingleValue("SELECT CONCAT(c.fld_module_name,' ',d.fld_version) AS fld_module_name,
																		a.fld_module_id AS modid, a.fld_row_id 
																	FROM ".$moddettable." AS a 
																	LEFT JOIN ".$schgridtable." AS b 
																		ON (a.fld_module_id=b.fld_module_id AND a.fld_class_id=b.fld_class_id
																		AND a.fld_schedule_id=b.fld_schedule_id 
																		AND a.fld_row_id=b.fld_row_id) 
																	LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id 
																	LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id 
																	WHERE b.fld_schedule_id='".$schid."' AND b.fld_class_id='".$id[2]."' 
																		AND b.fld_flag='1' AND b.fld_student_id='".$id[1]."' 
																		AND b.fld_rotation='".$i."' AND c.fld_delstatus='0' 
																		AND a.fld_flag='1' AND d.fld_delstatus='0'");
							?>
						</td>
						<?php
					}
					?>
				</tr>
			</tbody>
		</table>
		<?php
	} 
}
else
{
	echo "No Records";
}

@include("footer.php");	