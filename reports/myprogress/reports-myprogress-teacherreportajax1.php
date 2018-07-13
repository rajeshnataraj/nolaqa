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
											WHEN fld_moduletype='2' THEN '6' END) AS schtype 
											FROM itc_class_indassesment_master 
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
				else if($schtype==5 || $schtype==6)
				{
					$tablename = "itc_class_indassesment_student_mapping";
					$tablefield = "a.fld_schedule_id";
				}
				
				$qry = $ObjDB->QueryObject("SELECT a.fld_student_id AS studid, CONCAT(b.fld_fname,' ',b.fld_lname) AS studname 
											FROM ".$tablename." AS a 
											LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id 
											WHERE ".$tablefield."='".$shuid."' AND a.fld_flag='1' AND b.fld_activestatus='1' AND b.fld_delstatus='0'
											ORDER BY b.fld_lname");
				
				if($qry->num_rows>0){
					while($row = $qry->fetch_assoc())
					{
						extract($row);?>
						<li><a tabindex="-1" href="#" data-option="<?php echo $studid;?>" onclick="$('#viewreportdiv').show();"><?php echo $studname; ?></a></li>
					<?php }
                }?>      
            </ul>
        </div>
	</div>
	<?php
}

	@include("footer.php");