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
<br />
<?php 
if($id[2]==1)
{
    $qryschedules = $ObjDB->QueryObject("SELECT fld_schedule_name AS sigmathschdule, fld_id AS schid, 1 AS typename FROM itc_class_dyad_schedulemaster WHERE fld_class_id='".$id[1]."' AND fld_delstatus='0' AND fld_dyadtableflg='1'"); 
}
else if($id[2]==2)
{
    $qryschedules = $ObjDB->QueryObject("SELECT fld_schedule_name AS sigmathschdule, fld_id AS schid, 2 AS typename FROM itc_class_triad_schedulemaster WHERE fld_class_id='".$id[1]."' AND fld_delstatus='0' AND fld_triadtableflg='1'"); 
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
			$mulval = 2;
		}
		else if($typename==2)
		{
			$stagetable = "itc_class_triad_schedule_insstagemap";
			$moddettable = "itc_class_triad_moduledet";
			$schgridtable = "itc_class_triad_schedulegriddet";
			$mulval = 3;
		}
		
		if($b!=0) { ?>
        <div style="page-break-before: always;">
        <?php }?>
        <span class="title" ><?php echo $sigmathschdule;?></span>
        <br />
        <br />
        <table cellpadding="1" cellspacing="0" border="1" align="center">
            <tr style="font-size:35px; font-weight:bold;">
                <th rowspan="2">&nbsp;</th>
                <?php
				$b++;
                $qrydyadtriad = $ObjDB->QueryObject("SELECT fld_stagename, fld_id FROM ".$stagetable." 
														WHERE fld_schedule_id='".$schid."' AND fld_flag='1' AND fld_stagetype='3' 
															AND fld_stagevalue='".$id[3]."' AND fld_startdate<>'0000-00-00'");
                if($qrydyadtriad->num_rows > 0)
                { 	 
                    while($rowqrydyadtriad=$qrydyadtriad->fetch_assoc())
                    {
                        extract($rowqrydyadtriad);
                        ?>
                        <th colspan="<?php echo $mulval;?>"><div style="width: 100px;"><?php echo $fld_stagename;?></div></th>
                        <?php
                    }
                }?>
            </tr>
            <tr style="font-size:35px; font-weight:bold;">
                <?php
                for($i=1;$i<=$qrydyadtriad->num_rows*$mulval;$i++)
                { 	 
                    ?>
                    <th><div style="width: 100px;"><?php echo "Rotation ".$i;?></div></th>
                    <?php
                }?>
            </tr>
        
            <tbody>
                <?php
                $qrymodule = $ObjDB->QueryObject("SELECT CONCAT(b.fld_module_name,' ',c.fld_version) AS fld_module_name, 
														a.fld_module_id AS modid, a.fld_row_id 
													FROM ".$moddettable." AS a 
													LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
													LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
													WHERE a.fld_schedule_id='".$schid."' AND a.fld_class_id='".$id[1]."' 
														AND a.fld_flag='1' AND b.fld_delstatus='0' AND c.fld_delstatus='0'
													GROUP BY a.fld_row_id 
													ORDER BY fld_module_name");
                if($qrymodule->num_rows > 0)
                { 	
                    $cnt=0; 
                    while($rowqrymodule=$qrymodule->fetch_assoc())
                    {
                        extract($rowqrymodule);
                        ?>
                        <tr class="<?php if($cnt==0) { ?>trgray<?php } else if($cnt==1) { ?>trclass<?php }?>">
                            <td><div style="width: 150px;"><?php echo $fld_module_name;?></div></td>
                            <?php
                            for($i=1;$i<=$qrydyadtriad->num_rows*$mulval;$i++)
                            { 	
                                ?>
                                <td>
                                <?php 
                                $qrystudent = $ObjDB->QueryObject("SELECT CONCAT(a.fld_fname,' ',a.fld_lname) AS studentname 
																	FROM itc_user_master AS a 
																	LEFT JOIN ".$schgridtable." AS b 
																		ON a.fld_id=b.fld_student_id 
																	WHERE b.fld_schedule_id='".$schid."' AND b.fld_class_id='".$id[1]."' 
																		AND b.fld_rotation='".$i."' AND b.fld_module_id='".$modid."' 
																		AND b.fld_flag='1' AND b.fld_row_id='".$fld_row_id."' 
																		AND a.fld_delstatus='0' AND a.fld_activestatus='1'
																	ORDER BY a.fld_lname");
                                if($qrystudent->num_rows > 0)
                                { 	 
                                    while($rowqrystudent=$qrystudent->fetch_assoc())
                                    {
                                        extract($rowqrystudent);
                                        ?>
                                        <div><?php echo $studentname;?></div>
                                        <?php
                                    }
                                }
                                ?>
                                </td>
                                <?php
                            }
                            ?>
                        </tr>
                        <?php
                        if($cnt==0)
                            $cnt=1;
                        else if($cnt==1)
                            $cnt=0; 
                    }
                }
                else
                {
                    ?>
                    <tr class="trclass">
                        <td colspan="<?php echo $qrydyadtriad->num_rows*$mulval; ?>">no records</td>
                    </tr>
                    <?php
                }
                ?>
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