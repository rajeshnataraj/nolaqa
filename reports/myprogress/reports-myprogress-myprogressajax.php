<?php 
@include("sessioncheck.php");

/*
	Created By - Muthukumar. D
	Page - reports-classroom-classroomajax.php
		
	History:
	

*/

$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';

/*--- Load Student Dropdown ---*/
if($oper=="showschedule" and $oper != " " )
{
	$classid = isset($method['classid']) ? $method['classid'] : '';
	$studentid = isset($method['studentid']) ? $method['studentid'] : '';
	?>
	Schedule
	<div class="selectbox">
		<input type="hidden" name="scheduleid" id="scheduleid" value="">
		<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
			<span class="selectbox-option input-medium" data-option="" style="width:97%">Select Schedule</span>
			<b class="caret1"></b>
		</a>
		<div class="selectbox-options">
			<input type="text" class="selectbox-filter" placeholder="Search Schedule">
			<ul role="options" style="width:100%">
				<?php 
				$qry = $ObjDB->QueryObject("SELECT w.* FROM (
											SELECT a.fld_id AS schid, a.fld_schedule_name AS schname, 0 AS schtype 
											FROM itc_class_sigmath_master AS a 
											LEFT JOIN itc_class_sigmath_student_mapping AS b ON (a.fld_id=b.fld_sigmath_id) 
											WHERE a.fld_class_id='".$classid."' AND b.fld_student_id='".$studentid."' AND b.fld_flag='1' AND a.fld_delstatus='0' AND a.fld_flag='1'
												UNION ALL		
											SELECT a.fld_id AS schid, a.fld_schedule_name AS schname, (CASE WHEN a.fld_moduletype='1' 
													THEN '1' WHEN a.fld_moduletype='2' THEN '4' END) AS schtype 
											FROM itc_class_rotation_schedule_mastertemp AS a 
											LEFT JOIN itc_class_rotation_schedule_student_mappingtemp AS b ON (a.fld_id=b.fld_schedule_id) 
											WHERE a.fld_class_id='".$classid."' AND b.fld_student_id='".$studentid."' AND b.fld_flag='1' AND a.fld_delstatus='0' AND a.fld_flag='1'		
												UNION ALL			
											SELECT a.fld_id AS schid, a.fld_schedule_name AS schname, 2 AS schtype 
											FROM itc_class_dyad_schedulemaster AS a 
											LEFT JOIN itc_class_dyad_schedule_studentmapping AS b ON (a.fld_id=b.fld_schedule_id) 
											WHERE a.fld_class_id='".$classid."' AND b.fld_student_id='".$studentid."' AND b.fld_flag='1' AND a.fld_delstatus='0' AND a.fld_flag='1' 		
												UNION ALL		
											SELECT a.fld_id AS schid, a.fld_schedule_name AS schname, 3 AS schtype 
											FROM itc_class_triad_schedulemaster AS a 
											LEFT JOIN itc_class_triad_schedule_studentmapping AS b ON (a.fld_id=b.fld_schedule_id) 
											WHERE a.fld_class_id='".$classid."' AND b.fld_student_id='".$studentid."' AND b.fld_flag='1' AND a.fld_delstatus='0' AND a.fld_flag='1' 		
												UNION ALL			
											SELECT a.fld_id AS schid, a.fld_schedule_name AS schname, (CASE WHEN a.fld_moduletype='1' 
													THEN '5' WHEN a.fld_moduletype='2' THEN '6' WHEN a.fld_moduletype='7' THEN '7' END) AS schtype  
											FROM itc_class_indassesment_master AS a 
											LEFT JOIN itc_class_indassesment_student_mapping AS b ON (a.fld_id=b.fld_schedule_id) 
											WHERE a.fld_class_id='".$classid."' AND b.fld_student_id='".$studentid."' AND b.fld_flag='1' AND a.fld_delstatus='0' AND a.fld_flag='1'
												UNION ALL			
											SELECT a.fld_id AS schid, a.fld_schedule_name AS schname, a.fld_scheduletype AS schtype  
											FROM itc_class_indasexpedition_master AS a 
											LEFT JOIN itc_class_exp_student_mapping AS b ON (a.fld_id=b.fld_schedule_id) 
											WHERE a.fld_class_id='".$classid."' AND b.fld_student_id='".$studentid."' AND b.fld_flag='1' AND a.fld_delstatus='0' AND a.fld_flag='1'
                                                                                             UNION ALL			
											SELECT a.fld_id AS schid, a.fld_schedule_name AS schname, a.fld_scheduletype AS schtype  
											FROM itc_class_indasmission_master AS a 
											LEFT JOIN itc_class_mission_student_mapping AS b ON (a.fld_id=b.fld_schedule_id) 
											WHERE a.fld_class_id='".$classid."' AND b.fld_student_id='".$studentid."' AND b.fld_flag='1' AND a.fld_delstatus='0' AND a.fld_flag='1'
												 UNION ALL
											SELECT a.fld_id AS sid, a.fld_schedule_name AS sname,19 AS stype
											FROM itc_class_rotation_expschedule_mastertemp AS a 
											LEFT JOIN itc_class_rotation_expschedule_student_mappingtemp AS b ON b.fld_schedule_id=a.fld_id
											WHERE a.fld_class_id='".$classid."' AND b.fld_student_id='".$studentid."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_flag='1'	  
                                                 UNION ALL  
										  	SELECT a.fld_id AS sid, a.fld_schedule_name AS sname,20 AS stype
											FROM itc_class_rotation_modexpschedule_mastertemp AS a 
											LEFT JOIN itc_class_rotation_modexpschedule_student_mappingtemp AS b ON b.fld_schedule_id=a.fld_id
											WHERE a.fld_class_id='".$classid."' AND b.fld_student_id='".$studentid."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_flag='1'	  
                                                            UNION ALL
                                                                SELECT a.fld_id AS sid, a.fld_schedule_name AS sname,21 AS stype
                                                                FROM itc_class_rotation_mission_mastertemp AS a 
                                                                LEFT JOIN itc_class_rotation_mission_student_mappingtemp AS b ON b.fld_schedule_id=a.fld_id
                                                                WHERE a.fld_class_id='".$classid."' AND b.fld_student_id='".$studentid."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_flag='1'
										) AS w 
										ORDER BY w.schname");//w.schtype,   //Mohan M changed the name for alphabetical order
				if($qry->num_rows>0){
					while($row = $qry->fetch_assoc())
					{
						extract($row);
                                                if($schtype==1 || $schtype==4 || $schtype==19 || $schtype==20 || $schtype==21)
                                                {   ?>
                                                    <li><a tabindex="-1" href="#" data-option="<?php echo $schid.','.$schtype;?>" onclick="fn_showrotation(<?php echo $schid;?>,<?php echo $schtype;?>)"><?php echo $schname; ?></a></li>
                                                    <?php 
                                                } 
                                                else
                                                {   ?>
                                                    <li><a tabindex="-1" href="#" data-option="<?php echo $schid.','.$schtype;?>" onclick="$('#viewreportdiv').show();"><?php echo $schname; ?></a></li>
                                                    <?php  
                                                }
					}
				}?>      
			</ul>
		</div>
	</div>
	<?php
}

if($oper=="showclass" and $oper != " " )
{
	$studentid = isset($method['studentid']) ? $method['studentid'] : '';
	?>
	Class
	<dl class='field row'>
        <div class="selectbox">
            <input type="hidden" name="classid" id="classid" value="">
            <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Class</span>
                <b class="caret1"></b>
            </a>
            <div class="selectbox-options">
                <input type="text" class="selectbox-filter" placeholder="Search Class">
                <ul role="options" style="width:100%">
                    <?php 
                    $qry = $ObjDB->QueryObject("SELECT a.fld_id AS classid, a.fld_class_name AS classname 
												FROM itc_class_master AS a 
												LEFT JOIN itc_class_student_mapping AS b ON a.fld_id=b.fld_class_id 
												WHERE a.fld_delstatus='0' AND b.fld_student_id='".$studentid."' AND b.fld_flag='1'
												ORDER BY a.fld_class_name");
                    if($qry->num_rows>0){
                        while($row = $qry->fetch_assoc())
                        {
                            extract($row);
                            ?>
                            <li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>" onclick="fn_showschedule(<?php echo $classid;?>,<?php echo $studentid;?>)"><?php echo $classname; ?></a></li>
                            <?php
                        }
                    }?>      
                </ul>
            </div>
        </div> 
    </dl>
	<?php
}


/***********Show Rotation Developed By MOhan M 1-2-2016****************/
if($oper=="showrotation" and $oper != " " )
{
	$shuid = isset($method['schudid']) ? $method['schudid'] : '';
	$schtype = isset($method['schtype']) ? $method['schtype'] : '';
	$studentid = isset($method['studentid']) ? $method['studentid'] : '';
	?> 
	Rotation
	<div class="selectbox">
        <input type="hidden" name="hidrotid" id="hidrotid" value="">
        <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
            <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Rotation</span>
            <b class="caret1"></b>
        </a>
        <div class="selectbox-options">
            <input type="text" class="selectbox-filter" placeholder="Search Rotation">
            <ul role="options" style="width:100%">
                <?php 
			 	if($schtype==1 || $schtype==4)  //rotation
				{
					$tablename = "itc_class_rotation_schedulegriddet";
					$tablefield = "a.fld_schedule_id";
				}
				else if($schtype==19) //rotation
				{
					$tablename = "itc_class_rotation_expschedulegriddet";
					$tablefield = "a.fld_schedule_id";
				}
				else if($schtype==20) //rotation
				{
					$tablename = "itc_class_rotation_modexpschedulegriddet";
					$tablefield = "a.fld_schedule_id";
				}			
                                else if($schtype==21) // Mis rotation
				{
					$tablename = "itc_class_rotation_mission_schedulegriddet";
					$tablefield = "a.fld_schedule_id";
				}
	
	
				$qry = $ObjDB->QueryObject("SELECT MIN(a.fld_rotation - 1) AS minids, MAX(a.fld_rotation - 1) AS maxids,'Rotation ' AS nam, 'Rotation ' AS fullnam
											FROM ".$tablename." AS a 
											WHERE ".$tablefield."='".$shuid."' AND a.fld_flag='1' AND a.fld_flag = '1' GROUP BY a.fld_schedule_id");
				if($qry->num_rows>0)
				{ //Select All Students
					while($row = $qry->fetch_assoc())
					{
						extract($row);
				
						for($i=$minids;$i<=$maxids;$i++)
						{												
							$increment = $i+1;
							?>
								 <li><a tabindex="-1" href="#" data-option="<?php echo $increment; ?>" onclick="$('#viewreportdiv').show();"><?php echo $nam.' '.$i; ?></a></li>						
							<?php
						}
					}
				}
				?>
			</ul>
        </div>
	</div>
	<?php
}
/***********Show Rotation Developed By MOhan M 1-2-2016****************/


@include("footer.php");