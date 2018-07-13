<?php 
@include("sessioncheck.php");

$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';

/*--- Load Student Dropdown ---*/
if($oper=="showschedule" and $oper != " " )
{
	$classid = isset($method['classid']) ? $method['classid'] : '';	
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
											SELECT fld_id AS schid, fld_schedule_name AS schname, 0 AS schtype 
											FROM itc_class_sigmath_master 
											WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1' 		
												UNION ALL		
											SELECT fld_id AS schid, fld_schedule_name AS schname, (CASE WHEN fld_moduletype='1' THEN '1' 
											WHEN fld_moduletype='2' THEN '4' END) AS schtype 
											FROM itc_class_rotation_schedule_mastertemp 
											WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1'		
												UNION ALL		
											SELECT fld_id AS schid, fld_schedule_name AS schname, 2 AS schtype 
											FROM itc_class_dyad_schedulemaster 
											WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1' 		
												UNION ALL	
											SELECT fld_id AS schid, fld_schedule_name AS schname, 3 AS schtype 
											FROM itc_class_triad_schedulemaster 
											WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1' 		
												UNION ALL	
											SELECT fld_id AS schid, fld_schedule_name AS schname, (CASE WHEN fld_moduletype='1' THEN '5' 
											WHEN fld_moduletype='2' THEN '6' WHEN fld_moduletype='7' THEN '7' END) AS schtype 
											FROM itc_class_indassesment_master 
											WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1'
												UNION ALL	
											SELECT fld_id AS schid, fld_schedule_name AS schname, fld_scheduletype AS schtype 
											FROM itc_class_indasexpedition_master 
											WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1'
                                                                                                UNION ALL	
											SELECT fld_id AS schid, fld_schedule_name AS schname, fld_scheduletype AS schtype 
											FROM itc_class_indasmission_master 
											WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1'
                                                                                            UNION ALL		
											SELECT fld_id AS schid, fld_schedule_name AS schname, 19 AS schtype 
											FROM itc_class_rotation_expschedule_mastertemp 
											WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1'
											UNION ALL		
											SELECT fld_id AS schid, fld_schedule_name AS schname, 20 AS schtype 
											FROM itc_class_rotation_modexpschedule_mastertemp 
											WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1'	
                                                UNION ALL		
                                                SELECT fld_id AS schid, fld_schedule_name AS schname, 21 AS schtype 
                                                FROM itc_class_rotation_mission_mastertemp 
                                                WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1'
											
										) AS w 
										ORDER BY w.schtype, w.schname");
                if($qry->num_rows>0){
					while($row = $qry->fetch_assoc())
					{
						extract($row);
						?>
						<li><a tabindex="-1" href="#" data-option="<?php echo $schid.','.$schtype;?>" onclick="fn_showstudent(<?php echo $schid;?>,<?php echo $schtype;?>)"><?php echo $schname; ?></a></li>
						<?php
					}
                }?>      
            </ul>
        </div>
	</div>
	<?php 
} 

if($oper=="showstudent" and $oper != " " )
{
	$shuid = isset($method['shudid']) ? $method['shudid'] : '';
	$schtype = isset($method['schtype']) ? $method['schtype'] : '';
	?> 
	Student
	<div class="selectbox">
        <input type="hidden" name="hidstudid" id="hidstudid" value="">
        <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
            <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Student</span>
            <b class="caret1"></b>
        </a>
        <div class="selectbox-options">
            <input type="text" class="selectbox-filter" placeholder="Search Student">
            <ul role="options" style="width:100%">
                <?php 
				if($schtype==0)
				{
					$tablename = "itc_class_sigmath_student_mapping";
					$tablefield = "a.fld_sigmath_id";
				}
				else if($schtype==1 || $schtype==4)
				{
					$tablename = "itc_class_rotation_schedule_student_mappingtemp";
					$tablefield = "a.fld_schedule_id";
				}
				else if($schtype==2)
				{
					$tablename = "itc_class_dyad_schedule_studentmapping";
					$tablefield = "a.fld_schedule_id";
				}
				else if($schtype==3)
				{
					$tablename = "itc_class_triad_schedule_studentmapping";
					$tablefield = "a.fld_schedule_id";
				}
				else if($schtype==5 || $schtype==6 || $schtype==7)
				{
					$tablename = "itc_class_indassesment_student_mapping";
					$tablefield = "a.fld_schedule_id";
				}
				else if($schtype==15)
				{
					$tablename = "itc_class_exp_student_mapping";
					$tablefield = "a.fld_schedule_id";
				}
				else if($schtype==18)
				{
					$tablename = "itc_class_mission_student_mapping";
					$tablefield = "a.fld_schedule_id";
				}
				else if($schtype==19) // Expedition Schedule
				{
					$tablename = "itc_class_rotation_expschedule_student_mappingtemp";
					$tablefield = "a.fld_schedule_id";
				}
				else if($schtype==20)
				{
					$tablename = "itc_class_rotation_modexpschedule_student_mappingtemp";
					$tablefield = "a.fld_schedule_id";
				}
                                else if($schtype==21)// Mission Schedule
				{
					$tablename = "itc_class_rotation_mission_student_mappingtemp";
					$tablefield = "a.fld_schedule_id";
				}
				
				$qry = $ObjDB->QueryObject("SELECT a.fld_student_id AS studid, CONCAT(b.fld_lname,' ',b.fld_fname) AS studname 
											FROM ".$tablename." AS a 
											LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id 
											WHERE ".$tablefield."='".$shuid."' AND a.fld_flag='1' AND b.fld_activestatus='1' AND b.fld_delstatus='0'
											ORDER BY studname");
				
				if($qry->num_rows>0)
				{	//Select All Students
					
                                    if(($schtype==1 || $schtype==4 || $schtype==19 || $schtype==20 || $schtype==21))
						{ 	?>
							<li><a tabindex="-1" href="#" data-option="0" onclick="fn_showrotation(<?php echo $shuid;?>,<?php echo $schtype;?>)">All Students</a></li>
							<?php
						}
						else
						{ 	?>
							<li><a tabindex="-1" href="#" data-option="0" onclick="$('#viewreportdiv').show();">All Students</a></li>
							<?php
						}
				 	//Select All Students
					while($row = $qry->fetch_assoc())
					{
						extract($row);
                                            if(($schtype==1 || $schtype==4 || $schtype==19 || $schtype==20  || $schtype==21))
						{ 	?>
							<li><a tabindex="-1" href="#" data-option="<?php echo $studid;?>" onclick="fn_showrotation(<?php echo $shuid;?>,<?php echo $schtype;?>)"><?php echo $studname; ?></a></li>
							<?php
						}
						else
						{ 	?>
							<li><a tabindex="-1" href="#" data-option="<?php echo $studid;?>" onclick="$('#viewreportdiv').show();"><?php echo $studname; ?></a></li>
							<?php
						}
				 	}
                                }   ?>      
            </ul>
        </div>
	</div>
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