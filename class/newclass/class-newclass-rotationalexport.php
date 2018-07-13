
<?php
@include("sessioncheck.php");
/*
 This file will generate our CSV table. There is nothing to display on this page, it is simply used
 to generate our CSV file and then exit. That way we won't be re-directed after pressing the export
 to CSV button on the previous page.
*/

//First we'll generate an output variable called out. It'll have all of our text for the CSV file.
$out = '';

//Next we'll check to see if our variables posted and if they did we'll simply append them to out.
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

$id=explode(",",$id);


if($id[1]==2 or $id[1]==6)
{
$numberofrotation=$ObjDB->SelectSingleValueInt("SELECT fld_numberofrotation from itc_class_rotation_moduledet where fld_class_id='".$id[2]."' and fld_schedule_id='".$id[0]."' and fld_flag=1");

$moduletype=$ObjDB->SelectSingleValueInt("SELECT fld_moduletype from itc_class_rotation_schedule_mastertemp where fld_id='".$id[0]."' and fld_delstatus='0'");

$rotcount=$numberofrotation+1;

$csv_hdr='';

for($i=1;$i<=$numberofrotation;$i++)
{
	if($i==1)
	{
		$csv_hdr.=" ".",rotation ".$i.",";
	}
	else
	{
		$csv_hdr.="rotation ".$i.",";
	}
}

$out .= $csv_hdr;
$out .= "\n";

$qrymodulename=$ObjDB->NonQuery("SELECT fld_module_id as moduleid,fld_type as type,fld_module_name as modulename,fld_row_id as rowid from itc_class_rotation_moduledet where fld_class_id='".$id[2]."' and fld_schedule_id='".$id[0]."' and fld_flag=1 order by fld_row_id ASC");

$k=2;
while($rowmodule=$qrymodulename->fetch_assoc())
{
	$i='';
	$j='';
	
	extract($rowmodule);
	
	if($moduletype==1)
	{
            if($type==8) //Mohan M
            {
                $modulename=$ObjDB->SelectSingleValue("SELECT fld_contentname AS modulename  FROM itc_customcontent_master WHERE fld_id='".$moduleid."' AND fld_delstatus='0'");
            } //Mohan M
            else //Mohan M
            { //Mohan M
		$modulename=$ObjDB->SelectSingleValue("SELECT CONCAT(fld_module_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id='".$moduleid."' AND fld_delstatus='0')) AS modulename FROM itc_module_master WHERE fld_id='".$moduleid."' AND fld_delstatus='0'");
            } //Mohan M
		
	}
	else
	{
		$modulename=$ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_mathmodule_name,' ',b.fld_version) as modulename
				                            FROM itc_mathmodule_master AS a 
											LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_module_id
											WHERE a.fld_id='".$moduleid."' AND b.fld_delstatus='0' AND a.fld_delstatus='0'");
	}
	
	$modulename=str_replace(',','',$modulename);
	
	$qrytopcelldet=$ObjDB->NonQuery("SELECT fld_student_name,fld_cell_id from itc_class_rotation_schedulegriddet where fld_module_id='".$moduleid."' and fld_cell_id like 'seg1%' and fld_flag=1");
	
	$numrowstop=$qrytopcelldet->num_rows;

	
	if($numrowstop > 0)
	{
		$out .= $modulename.",";
		
		for($i=2;$i<=$rotcount;$i++)
		{
			$cellid="seg1_".$k."_".$i;
			$studentname=$ObjDB->SelectSingleValue("SELECT CONCAT(b.fld_fname,' ',b.fld_lname) AS studentname FROM itc_class_rotation_schedulegriddet AS a LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id where a.fld_cell_id='".$cellid."' and a.fld_flag='1' and a.fld_schedule_id='".$id[0]."'");
			if($studentname!='')
			{
				$out .=$studentname.",";
			}
			else
			{
				$out .=" ".",";
			}
			
		}
		
		$out .="\n";
		
	}
	else
	{
		$out .= $modulename."\n";
	}
	
	
	
	$qrybottomcelldet=$ObjDB->NonQuery("SELECT fld_student_name,fld_cell_id from itc_class_rotation_schedulegriddet where fld_module_id='".$moduleid."' and fld_cell_id like 'seg2%' and fld_flag=1");
	
	$numrowsbottom=$qrybottomcelldet->num_rows;
	
	if($numrowsbottom > 0)
	{
		$out .= " ".",";
		
		for($j=2;$j<=$rotcount;$j++)
		{
			$cellid="seg2_".$k."_".$j;
			$studentname=$ObjDB->SelectSingleValue("SELECT CONCAT(b.fld_fname,' ',b.fld_lname) AS studentname FROM itc_class_rotation_schedulegriddet AS a LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id where a.fld_cell_id='".$cellid."' and a.fld_flag='1' and a.fld_schedule_id='".$id[0]."'");
			if($studentname!='')
			{
				$out .=$studentname.",";
			}
			else
			{
				$out .=" ".",";
			}
		}
		
		$out .="\n";
	}
	else
	{
		$out .= " "."\n";
	}
   $k++;	
}
}

if($id[1]==17)
{
$numberofrotation=$ObjDB->SelectSingleValueInt("SELECT fld_numberofrotation from itc_class_rotation_expmoduledet where fld_class_id='".$id[2]."' and fld_schedule_id='".$id[0]."' and fld_flag=1");

$rotcount=$numberofrotation+1;

$csv_hdr='';

for($i=1;$i<=$numberofrotation;$i++)
{
	if($i==1)
	{
		$csv_hdr.=" ".",rotation ".$i.",";
	}
	else
	{
		$csv_hdr.="rotation ".$i.",";
	}
}

$out .= $csv_hdr;
$out .= "\n";

$qryexpname=$ObjDB->NonQuery("SELECT fld_exp_id as expid,fld_row_id as rowid from itc_class_rotation_expmoduledet where fld_class_id='".$id[2]."' and fld_schedule_id='".$id[0]."' and fld_flag=1 order by fld_row_id ASC");

$k=2;
while($rowexp=$qryexpname->fetch_assoc())
{
	$i='';
	$j='';
	
	extract($rowexp);
	
	
		$expname=$ObjDB->SelectSingleValue("SELECT 
                                                    CONCAT(a.fld_exp_name, ' ', b.fld_version) 
                                                    FROM
                                                    itc_exp_master AS a
                                                        LEFT JOIN
                                                    itc_exp_version_track AS b ON b.fld_exp_id = '".$expid."'
                                                    WHERE a.fld_id='".$expid."' AND b.fld_delstatus='0' AND a.fld_delstatus='0'");
	
	
	$expname=str_replace(',','',$expname);
	
	$qrytopcelldet=$ObjDB->NonQuery("SELECT fld_student_name,fld_cell_id from itc_class_rotation_expschedulegriddet where fld_expedition_id='".$expid."' and fld_cell_id like 'seg1%' and fld_flag=1");
	
	$numrowstop=$qrytopcelldet->num_rows;

	
	if($numrowstop > 0)
	{
		$out .= $expname.",";
		
		for($i=2;$i<=$rotcount;$i++)
		{
			$cellid="seg1_".$k."_".$i;
			$studentname=$ObjDB->SelectSingleValue("SELECT CONCAT(b.fld_fname,' ',b.fld_lname) AS studentname FROM itc_class_rotation_expschedulegriddet AS a LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id where a.fld_cell_id='".$cellid."' and a.fld_flag='1' and a.fld_schedule_id='".$id[0]."'");
			if($studentname!='')
			{
				$out .=$studentname.",";
			}
			else
			{
				$out .=" ".",";
			}
			
		}
		
		$out .="\n";
		
	}
	else
	{
		$out .= $expname."\n";
	}
	
	
	
	$qrybottomcelldet=$ObjDB->NonQuery("SELECT fld_student_name,fld_cell_id from itc_class_rotation_expschedulegriddet where fld_expedition_id='".$expid."' and fld_cell_id like 'seg2%' and fld_flag=1");
	
	$numrowsbottom=$qrybottomcelldet->num_rows;
	
	if($numrowsbottom > 0)
	{
		$out .= " ".",";
		
		for($j=2;$j<=$rotcount;$j++)
		{
			$cellid="seg2_".$k."_".$j;
			$studentname=$ObjDB->SelectSingleValue("SELECT CONCAT(b.fld_fname,' ',b.fld_lname) AS studentname FROM itc_class_rotation_expschedulegriddet AS a LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id where a.fld_cell_id='".$cellid."' and a.fld_flag='1' and a.fld_schedule_id='".$id[0]."'");
			if($studentname!='')
			{
				$out .=$studentname.",";
			}
			else
			{
				$out .=" ".",";
			}
		}
		
		$out .="\n";
	}
	else
	{
		$out .= " "."\n";
	}
   $k++;	
}
}

if($id[1]==19)
{
$numberofrotation=$ObjDB->SelectSingleValueInt("SELECT fld_numberofrotation from itc_class_rotation_modexpmoduledet where fld_class_id='".$id[2]."' and fld_schedule_id='".$id[0]."' and fld_flag=1");

$rotcount=$numberofrotation+1;

$csv_hdr='';

for($i=1;$i<=$numberofrotation;$i++)
{
	if($i==1)
	{
		$csv_hdr.=" ".",rotation ".$i.",";
	}
	else
	{
		$csv_hdr.="rotation ".$i.",";
	}
}

$out .= $csv_hdr;
$out .= "\n";

$qryexpname=$ObjDB->NonQuery("SELECT fld_module_id as modexpid,fld_type as type,fld_row_id as rowid from itc_class_rotation_modexpmoduledet where fld_class_id='".$id[2]."' and fld_schedule_id='".$id[0]."' and fld_flag=1 order by fld_row_id ASC");

$k=2;
while($rowexp=$qryexpname->fetch_assoc())
{
	$i='';
	$j='';
	
	extract($rowexp);
	
	        if($type==1)
                {
                        $modexpname= $ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_module_name,' ',b.fld_version)
                                      FROM itc_module_master AS a 
                                                                  LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id='".$modexpid."'    
                                                                  WHERE a.fld_id='".$modexpid."' AND b.fld_delstatus='0' AND a.fld_delstatus='0'");
                }
                else if($type==8)
                {
                        $modexpname= $ObjDB->SelectSingleValue("SELECT fld_contentname from itc_customcontent_master WHERE fld_id='".$modexpid."' AND fld_delstatus='0'");
                }
                else if($type==2)
                {
                    
                    $modexpname=$ObjDB->SelectSingleValue("SELECT 
                                                    CONCAT(a.fld_exp_name, ' ', b.fld_version) 
                                                    FROM
                                                    itc_exp_master AS a
                                                        LEFT JOIN
                                                    itc_exp_version_track AS b ON b.fld_exp_id = '".$modexpid."'
                                                    WHERE a.fld_id='".$modexpid."' AND b.fld_delstatus='0' AND a.fld_delstatus='0'");
                }
	
	
	$modexpname=str_replace(',','',$modexpname);
	
	$qrytopcelldet=$ObjDB->NonQuery("SELECT fld_student_name,fld_cell_id from itc_class_rotation_modexpschedulegriddet where fld_module_id='".$expid."' and fld_cell_id like 'seg1%' and fld_flag=1");
	
	$numrowstop=$qrytopcelldet->num_rows;

	
	if($numrowstop > 0)
	{
		$out .= $modexpname.",";
		
		for($i=2;$i<=$rotcount;$i++)
		{
			$cellid="seg1_".$k."_".$i;
			$studentname=$ObjDB->SelectSingleValue("SELECT CONCAT(b.fld_lname,' ',b.fld_fname) AS studentname FROM itc_class_rotation_modexpschedulegriddet AS a LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id where a.fld_cell_id='".$cellid."' and a.fld_flag='1' and a.fld_schedule_id='".$id[0]."'");
			if($studentname!='')
			{
				$out .=$studentname.",";
			}
			else
			{
				$out .=" ".",";
			}
			
		}
		
		$out .="\n";
		
	}
	else
	{
		$out .= $modexpname."\n";
	}
	
	
	
	$qrybottomcelldet=$ObjDB->NonQuery("SELECT fld_student_name,fld_cell_id from itc_class_rotation_modexpschedulegriddet where fld_module_id='".$modexpid."' and fld_cell_id like 'seg2%' and fld_flag=1");
	
	$numrowsbottom=$qrybottomcelldet->num_rows;
	
	if($numrowsbottom > 0)
	{
		$out .= " ".",";
		
		for($j=2;$j<=$rotcount;$j++)
		{
			$cellid="seg2_".$k."_".$j;
			$studentname=$ObjDB->SelectSingleValue("SELECT CONCAT(b.fld_lname,' ',b.fld_fname) AS studentname FROM itc_class_rotation_modexpschedulegriddet AS a LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id where a.fld_cell_id='".$cellid."' and a.fld_flag='1' and a.fld_schedule_id='".$id[0]."'");
			if($studentname!='')
			{
				$out .=$studentname.",";
			}
			else
			{
				$out .=" ".",";
			}
		}
		
		$out .="\n";
	}
	else
	{
		$out .= " "."\n";
	}
   $k++;	
}
}

if($id[1]==20)
{
$numberofrotation=$ObjDB->SelectSingleValueInt("SELECT fld_numberofrotation from itc_class_rotation_missiondet where fld_class_id='".$id[2]."' and fld_schedule_id='".$id[0]."' and fld_flag=1");

$rotcount=$numberofrotation+1;

$csv_hdr='';

for($i=1;$i<=$numberofrotation;$i++)
{
	if($i==1)
	{
		$csv_hdr.=" ".",rotation ".$i.",";
	}
	else
	{
		$csv_hdr.="rotation ".$i.",";
	}
}

$out .= $csv_hdr;
$out .= "\n";

$qryexpname=$ObjDB->NonQuery("SELECT fld_mission_id as misid,fld_row_id as rowid from itc_class_rotation_missiondet where fld_class_id='".$id[2]."' and fld_schedule_id='".$id[0]."' and fld_flag=1 order by fld_row_id ASC");

$k=2;
while($rowexp=$qryexpname->fetch_assoc())
{
	$i='';
	$j='';
	
	extract($rowexp);
	
	
		$misname=$ObjDB->SelectSingleValue("SELECT 
                                                    CONCAT(a.fld_mis_name, ' ', b.fld_version) 
                                                    FROM
                                                    itc_mission_master AS a
                                                        LEFT JOIN
                                                    itc_mission_version_track AS b ON b.fld_mis_id = '".$misid."'
                                                    WHERE a.fld_id='".$misid."' AND b.fld_delstatus='0' AND a.fld_delstatus='0'");
	
	
	$misname=str_replace(',','',$misname);
	
	$qrytopcelldet=$ObjDB->NonQuery("SELECT fld_student_name,fld_cell_id from itc_class_rotation_mission_schedulegriddet where fld_mission_id='".$misid."' and fld_cell_id like 'seg1%' and fld_flag=1");
	
	$numrowstop=$qrytopcelldet->num_rows;

	
	if($numrowstop > 0)
	{
		$out .= $misname.",";
		
		for($i=2;$i<=$rotcount;$i++)
		{
			$cellid="seg1_".$k."_".$i;
			$studentname=$ObjDB->SelectSingleValue("SELECT CONCAT(b.fld_fname,' ',b.fld_lname) AS studentname FROM itc_class_rotation_mission_schedulegriddet AS a LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id where a.fld_cell_id='".$cellid."' and a.fld_flag='1' and a.fld_schedule_id='".$id[0]."'");
			if($studentname!='')
			{
				$out .=$studentname.",";
			}
			else
			{
				$out .=" ".",";
			}
			
		}
		
		$out .="\n";
		
	}
	else
	{
		$out .= $misname."\n";
	}
	
	
	
	$qrybottomcelldet=$ObjDB->NonQuery("SELECT fld_student_name,fld_cell_id from itc_class_rotation_mission_schedulegriddet where fld_mission_id='".$misid."' and fld_cell_id like 'seg2%' and fld_flag=1");
	
	$numrowsbottom=$qrybottomcelldet->num_rows;
	
	if($numrowsbottom > 0)
	{
		$out .= " ".",";
		
		for($j=2;$j<=$rotcount;$j++)
		{
			$cellid="seg2_".$k."_".$j;
			$studentname=$ObjDB->SelectSingleValue("SELECT CONCAT(b.fld_fname,' ',b.fld_lname) AS studentname FROM itc_class_rotation_mission_schedulegriddet AS a LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id where a.fld_cell_id='".$cellid."' and a.fld_flag='1' and a.fld_schedule_id='".$id[0]."'");
			if($studentname!='')
			{
				$out .=$studentname.",";
			}
			else
			{
				$out .=" ".",";
			}
		}
		
		$out .="\n";
	}
	else
	{
		$out .= " "."\n";
	}
        
        $qrybottomcelldet=$ObjDB->NonQuery("SELECT fld_student_name,fld_cell_id from itc_class_rotation_mission_schedulegriddet where fld_mission_id='".$misid."' and fld_cell_id like 'seg2%' and fld_flag=1");
	
	$numrowsbottom=$qrybottomcelldet->num_rows;
	
	if($numrowsbottom > 0)
	{
		$out .= " ".",";
		
		for($j=2;$j<=$rotcount;$j++)
		{
			$cellid="seg2_".$k."_".$j;
			$studentname=$ObjDB->SelectSingleValue("SELECT CONCAT(b.fld_fname,' ',b.fld_lname) AS studentname FROM itc_class_rotation_mission_schedulegriddet AS a LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id where a.fld_cell_id='".$cellid."' and a.fld_flag='1' and a.fld_schedule_id='".$id[0]."'");
			if($studentname!='')
			{
				$out .=$studentname.",";
			}
			else
			{
				$out .=" ".",";
			}
		}
		
		$out .="\n";
	}
	else
	{
		$out .= " "."\n";
	}
        
        $qrymidcelldet=$ObjDB->NonQuery("SELECT fld_student_name,fld_cell_id from itc_class_rotation_mission_schedulegriddet where fld_mission_id='".$misid."' and fld_cell_id like 'seg3%' and fld_flag=1");
	
	$numrowsmid=$qrymidcelldet->num_rows;
	
	if($numrowsmid > 0)
	{
		$out .= " ".",";
		
		for($j=2;$j<=$rotcount;$j++)
		{
			$cellid="seg3_".$k."_".$j;
			$studentname=$ObjDB->SelectSingleValue("SELECT CONCAT(b.fld_fname,' ',b.fld_lname) AS studentname FROM itc_class_rotation_mission_schedulegriddet AS a LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id where a.fld_cell_id='".$cellid."' and a.fld_flag='1' and a.fld_schedule_id='".$id[0]."'");
			if($studentname!='')
			{
				$out .=$studentname.",";
			}
			else
			{
				$out .=" ".",";
			}
		}
		
		$out .="\n";
	}
	else
	{
		$out .= " "."\n";
	}
        
        $qrylastcelldet=$ObjDB->NonQuery("SELECT fld_student_name,fld_cell_id from itc_class_rotation_mission_schedulegriddet where fld_mission_id='".$misid."' and fld_cell_id like 'seg4%' and fld_flag=1");
	
	$numrowslast=$qrylastcelldet->num_rows;
	
	if($numrowslast > 0)
	{
		$out .= " ".",";
		
		for($j=2;$j<=$rotcount;$j++)
		{
			$cellid="seg4_".$k."_".$j;
			$studentname=$ObjDB->SelectSingleValue("SELECT CONCAT(b.fld_fname,' ',b.fld_lname) AS studentname FROM itc_class_rotation_mission_schedulegriddet AS a LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id where a.fld_cell_id='".$cellid."' and a.fld_flag='1' and a.fld_schedule_id='".$id[0]."'");
			if($studentname!='')
			{
				$out .=$studentname.",";
			}
			else
			{
				$out .=" ".",";
			}
		}
		
		$out .="\n";
	}
	else
	{
		$out .= " "."\n";
	}
   $k++;	
}
}

if($id[1]==3)
{
	$out='';
	
	$numberofrotation=$ObjDB->SelectSingleValueInt("SELECT fld_numberofrotation from itc_class_dyad_moduledet where fld_class_id='".$id[2]."' and fld_schedule_id='".$id[0]."' and fld_flag=1");

$rotcount=$numberofrotation+1;

$csv_hdr='';

for($i=1;$i<=$numberofrotation;$i++)
{
	if($i==1)
	{
		$csv_hdr.="   ,"."  ,"."rotation ".$i.",";
	}
	else
	{
		$csv_hdr.="rotation ".$i.",";
	}
}

$out .= $csv_hdr;
$out .= "\n";

$qrymodulename=$ObjDB->NonQuery("SELECT fld_module_id as moduleid,fld_module_name as modulename,fld_row_id as rowid from itc_class_dyad_moduledet where fld_class_id='".$id[2]."' and fld_schedule_id='".$id[0]."' and fld_flag=1 order by fld_row_id ASC");

$ins=1;
while($rowmodule=$qrymodulename->fetch_assoc())
{
	$i='';
	$j='';
	
	extract($rowmodule);
	
	$modulename=$ObjDB->SelectSingleValue("SELECT CONCAT(fld_module_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id='".$moduleid."' AND fld_delstatus='0')) AS modulename FROM itc_module_master WHERE fld_id='".$moduleid."' AND fld_delstatus='0'");
	
	$qrytopcelldet=$ObjDB->NonQuery("SELECT fld_student_name,fld_cell_id from itc_class_dyad_schedulegriddet where fld_module_id='".$moduleid."' and fld_cell_id like 'seg1%' and fld_flag=1");
	
	$numrowstop=$qrytopcelldet->num_rows;

	$modulename=str_replace(',','',$modulename);
		
	if($numrowstop > 0)
	{
		$out .= "  ,".$modulename.",";
		
		for($i=1;$i<=$rotcount-1;$i++)
		{
			$cellid="seg1_".$rowid."_".$i;
			$studentname=$ObjDB->SelectSingleValue("SELECT CONCAT(b.fld_fname,' ',b.fld_lname) AS studentname FROM itc_class_dyad_schedulegriddet AS a LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id where a.fld_cell_id='".$cellid."' and a.fld_flag='1' and a.fld_schedule_id='".$id[0]."'");
			if($studentname!='')
			{
				$out .=$studentname.",";
			}
			else
			{
				$out .=" ".",";
			}
			
		}
		
		$out .="\n";
		
	}
	else
	{
		$out .= $modulename."\n";
	}
	
	
	
	$qrybottomcelldet=$ObjDB->NonQuery("SELECT fld_student_name,fld_cell_id from itc_class_dyad_schedulegriddet where fld_module_id='".$moduleid."' and fld_cell_id like 'seg2%' and fld_flag=1");
	
	$stagename=$ObjDB->SelectSingleValue("SELECT a.fld_name FROM itc_class_definedyads AS a LEFT JOIN itc_class_dyad_schedule_modulemapping AS b ON b.fld_dyad_id=a.fld_id WHERE b.fld_module_id='".$moduleid."' AND b.fld_schedule_id='".$id[0]."' AND a.fld_delstatus='0'");
	
	if($ins%2!=0)
	{
		$name=$stagename;
	}
	else
	{
		$name=" ";
	}
	
	$numrowsbottom=$qrybottomcelldet->num_rows;
	
	if($numrowsbottom > 0)
	{
		$out .= $name.","." ".",";
		
		for($j=1;$j<=$rotcount-1;$j++)
		{
			$cellid="seg2_".$rowid."_".$j;
			$studentname=$ObjDB->SelectSingleValue("SELECT CONCAT(b.fld_fname,' ',b.fld_lname) AS studentname FROM itc_class_dyad_schedulegriddet AS a LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id where a.fld_cell_id='".$cellid."' and a.fld_flag='1' and a.fld_schedule_id='".$id[0]."'");
			if($studentname!='')
			{
				$out .=$studentname.",";
			}
			else
			{
				$out .=" ".",";
			}
		}
		
		$out .="\n";
	}
	else
	{
		$out .= " "."\n";
	}
	
	$ins++;
	
}	
}
if($id[1]==4)
{
	$out='';
	
	$numberofrotation=$ObjDB->SelectSingleValueInt("SELECT SUM(fld_numberofrotation) FROM itc_class_triad_schedule_insstagemap WHERE fld_startdate<>'0000-00-00' AND fld_enddate<>'0000-00-00' AND fld_stagetype='3' AND fld_schedule_id='".$id[0]."' AND fld_flag='1'");

$rotcount=$numberofrotation+1;

$csv_hdr='';

for($i=1;$i<=$numberofrotation;$i++)
{
	if($i==1)
	{
		$csv_hdr.=" ".","." ".","."rotation ".$i.",";
	}
	else
	{
		$csv_hdr.="rotation ".$i.",";
	}
}

$out.= $csv_hdr;
$out.= "\n";

$qrymodulename=$ObjDB->NonQuery("SELECT fld_module_id as moduleid,fld_module_name as modulename,fld_row_id as rowid from itc_class_triad_moduledet where fld_class_id='".$id[2]."' and fld_schedule_id='".$id[0]."' and fld_flag=1 order by fld_row_id ASC");

$ins=1;
while($rowmodule=$qrymodulename->fetch_assoc())
{
	$i='';
	$j='';
	
	extract($rowmodule);
	
	$modulename=$ObjDB->SelectSingleValue("SELECT CONCAT(fld_module_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id='".$moduleid."' AND fld_delstatus='0')) AS modulename FROM itc_module_master WHERE fld_id='".$moduleid."' AND fld_delstatus='0'");
	
	$qrytopcelldet=$ObjDB->NonQuery("SELECT fld_student_name,fld_cell_id from itc_class_triad_schedulegriddet where fld_module_id='".$moduleid."' and fld_cell_id like 'seg1%' and fld_flag=1");
	
	$numrowstop=$qrytopcelldet->num_rows;
	
	$stagename=$ObjDB->SelectSingleValue("SELECT a.fld_name FROM itc_class_definetriads AS a LEFT JOIN itc_class_triad_schedule_modulemapping AS b ON b.fld_triad_id=a.fld_id WHERE b.fld_module_id='".$moduleid."' AND b.fld_schedule_id='".$id[0]."' AND a.fld_delstatus='0'");
	
	if($ins%3==2)
	{
		$name=$stagename;
	}
	else
	{
		$name=" ";
	}

	$modulename=str_replace(',','',$modulename);
	
	if($numrowstop > 0)
	{
		$out .= $name.",".$modulename.",";
		
		for($i=1;$i<=$rotcount-1;$i++)
		{
			$cellid="seg1_".$rowid."_".$i;
			$studentname=$ObjDB->SelectSingleValue("SELECT CONCAT(b.fld_fname,' ',b.fld_lname) AS studentname FROM itc_class_triad_schedulegriddet AS a LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id where a.fld_cell_id='".$cellid."' and a.fld_flag='1' and a.fld_schedule_id='".$id[0]."'");
			if($studentname!='')
			{
				$out .=$studentname.",";
			}
			else
			{
				$out .=" ".",";
			}
			
		}
		
		$out .="\n";
		
	}
	else
	{
		$out .= $modulename."\n";
	}
	
	
	
	$qrybottomcelldet=$ObjDB->NonQuery("SELECT fld_student_name,fld_cell_id from itc_class_triad_schedulegriddet where fld_module_id='".$moduleid."' and fld_cell_id like 'seg2%' and fld_flag=1");
	
	$numrowsbottom=$qrybottomcelldet->num_rows;
	
	
	
	if($numrowsbottom > 0)
	{
		
		$out .= " ".","." ".",";
		
		for($j=1;$j<=$rotcount-1;$j++)
		{
			$cellid="seg2_".$rowid."_".$j;
			
			$studentname=$ObjDB->SelectSingleValue("SELECT CONCAT(b.fld_fname,' ',b.fld_lname) AS studentname FROM itc_class_triad_schedulegriddet AS a LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id where a.fld_cell_id='".$cellid."' and a.fld_flag='1' and a.fld_schedule_id='".$id[0]."'");
			
			if($studentname!='')
			{
				$out .= $studentname.",";
			}
			else
			{
				$out .=" ".",";
			}
		}
		
		$out .="\n";
	}
	else
	{
		$out .= " "."\n";
	}
	
	$ins++;
	
}	
}

//$out = urldecode($out);

//Now we're ready to create a file. This method generates a filename based on the current date & time.
$name=str_replace(' ','_',isset($id[3]));
$filename = $name."_".date("Y-m-d_H-i",time());


@include("footer.php");
//Generate the CSV file header

header('Content-Encoding: UTF-8,UTF-16LE');
header('Content-type:application/vnd.ms-excel; charset=UTF-8');
header("Content-disposition: csv" . date("Y-m-d") . ".csv");
header("Content-disposition: attachment;filename=".$filename.".csv");

echo $out;
//Print the contents of out to the generated file.

//print chr(255) . chr(254) . mb_convert_encoding($out, 'UTF-16LE', 'UTF-8');

//Exit the script
exit;
?>
