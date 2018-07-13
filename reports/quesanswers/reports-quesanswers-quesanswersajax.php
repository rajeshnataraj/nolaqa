<?php 
@include("sessioncheck.php");

/*
	Created By - Muthukumar. D
	Page - reports-gradereports-gradeajax.php
		
	History: updated By mohan kumar .v And Mohan M
 * For select all students 
	

*/

$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';

/*--- Load Student Dropdown ---*/
if($oper=="showstudent" and $oper != " " )
{
    $type = isset($method['type']) ? $method['type'] : '';
    $typename = isset($method['typename']) ? $method['typename'] : '';
    $classid = isset($method['classid']) ? $method['classid'] : '';
    $assignmentid = isset($method['assignmentid']) ? $method['assignmentid'] : '';
    ?>
    Student
	<div class="selectbox">
		<input type="hidden" name="studentid" id="studentid" value="">
		<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
			<span class="selectbox-option input-medium" data-option="" style="width:97%">Select Student</span>
			<b class="caret1"></b>
		</a>
		<div class="selectbox-options">
			<input type="text" class="selectbox-filter" placeholder="Search Student">
			<ul role="options" style="width:100%">
				<?php 
				if($type==1)
				{
                                    $qry =("SELECT w.* FROM (
                                                (SELECT CONCAT(c.fld_lname,' ',c.fld_fname) AS studentname, c.fld_id AS studentid
                                                FROM itc_class_sigmath_master AS a 
                                                LEFT JOIN itc_class_sigmath_student_mapping AS b ON a.fld_id=b.fld_sigmath_id 
                                                LEFT JOIN itc_user_master as c ON c.fld_id=b.fld_student_id
                                                WHERE b.fld_flag=1 AND a.fld_id='".$assignmentid."' AND a.fld_delstatus=0 AND a.fld_class_id='".$classid."'
                                                AND c.fld_activestatus='1' AND c.fld_delstatus='0') 
                                                UNION ALL	
                                                (SELECT CONCAT(c.fld_lname,' ',c.fld_fname) AS studentname, c.fld_id AS studentid
                                                FROM itc_class_rotation_schedule_mastertemp AS a 
                                                LEFT JOIN itc_class_rotation_schedule_student_mappingtemp AS b ON a.fld_id=b.fld_schedule_id 
                                                LEFT JOIN itc_user_master as c ON c.fld_id=b.fld_student_id
                                                WHERE b.fld_flag=1 AND a.fld_id='".$assignmentid."' AND a.fld_delstatus=0 AND a.fld_class_id='".$classid."'
                                                AND c.fld_activestatus='1' AND c.fld_delstatus='0' AND a.fld_moduletype='2')		
                                                UNION ALL 		
                                                (SELECT CONCAT(c.fld_lname,' ',c.fld_fname) AS studentname, c.fld_id AS studentid
                                                FROM itc_class_indassesment_master AS a 
                                                LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
                                                LEFT JOIN itc_user_master as c ON c.fld_id=b.fld_student_id
                                                WHERE b.fld_flag=1 AND a.fld_id='".$assignmentid."' AND a.fld_delstatus=0 AND a.fld_class_id='".$classid."'
                                                AND a.fld_moduletype='2' AND a.fld_flag='1' AND c.fld_activestatus='1' AND c.fld_delstatus='0')
                                                ) AS w 
                                                GROUP BY w.studentid ORDER BY studentname");
                                }
                                else if($type==2)
				{
                                    if($typename==20 || $typename==21)
                                    {
                                        $qry ="SELECT CONCAT(e.fld_lname,' ',e.fld_fname) AS studentname, e.fld_id AS studentid
                                                FROM itc_class_rotation_modexpschedulegriddet AS a 
                                                LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_id) 
                                                LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id
                                                LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id
                                                LEFT JOIN itc_user_master as e ON e.fld_id=a.fld_student_id
                                                WHERE a.fld_class_id='".$classid."' AND a.fld_module_id='".$assignmentid."' AND a.fld_flag='1' AND b.fld_flag='1' AND b.fld_delstatus='0' 
                                                                AND  c.fld_delstatus='0' AND d.fld_delstatus='0' 
                                                                 AND e.fld_activestatus='1' AND e.fld_delstatus='0' GROUP BY studentid ORDER BY studentname";

                                    }
                                    else
                                    {
                                        $qry =("SELECT w.* FROM (
                                                    (SELECT CONCAT(e.fld_lname,' ',e.fld_fname) AS studentname, e.fld_id AS studentid
                                                    FROM itc_class_rotation_schedulegriddet AS a 
                                                    LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_id) 
                                                    LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id
                                                    LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id
                                                    LEFT JOIN itc_user_master as e ON e.fld_id=a.fld_student_id
                                                    WHERE a.fld_class_id='".$classid."' AND a.fld_module_id='".$assignmentid."' AND a.fld_flag='1' AND b.fld_flag='1' AND b.fld_delstatus='0' 
                                                    AND b.fld_moduletype='1' AND c.fld_delstatus='0' AND d.fld_delstatus='0' AND a.fld_type='1'
                                                    AND e.fld_activestatus='1' AND e.fld_delstatus='0')
                                                UNION ALL	 
                                                    (SELECT CONCAT(f.fld_lname,' ',f.fld_fname) AS studentname, f.fld_id AS studentid
                                                    FROM itc_class_dyad_schedulegriddet AS a 
                                                    LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
                                                    LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
                                                    LEFT JOIN itc_class_dyad_schedule_studentmapping AS d ON (d.fld_schedule_id=a.fld_schedule_id)
                                                    LEFT JOIN itc_class_dyad_schedulemaster as e on a.fld_schedule_id = e.fld_id
                                                    LEFT JOIN itc_user_master as f ON f.fld_id=a.fld_student_id
                                                    WHERE  a.fld_class_id='".$classid."' AND a.fld_module_id='".$assignmentid."' AND a.fld_flag='1' AND d.fld_flag='1'
                                                    AND b.fld_delstatus='0' AND c.fld_delstatus='0'  AND e.fld_delstatus='0' AND f.fld_activestatus='1' AND f.fld_delstatus='0')
                                                UNION ALL	
                                                    (SELECT CONCAT(f.fld_lname,' ',f.fld_fname) AS studentname, f.fld_id AS studentid
                                                    FROM itc_class_triad_schedulegriddet AS a 
                                                    LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
                                                    LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
                                                    LEFT JOIN itc_class_triad_schedule_studentmapping AS d ON (d.fld_schedule_id=a.fld_schedule_id)
                                                    LEFT JOIN itc_class_triad_schedulemaster AS e ON a.fld_schedule_id = e.fld_id
                                                    LEFT JOIN itc_user_master as f ON f.fld_id=d.fld_student_id
                                                    WHERE  a.fld_class_id='".$classid."' AND a.fld_module_id='".$assignmentid."' AND a.fld_flag='1' AND d.fld_flag='1'
                                                    AND b.fld_delstatus='0' AND c.fld_delstatus='0'  AND e.fld_delstatus='0' AND f.fld_activestatus='1' AND f.fld_delstatus='0')
                                                UNION ALL		
                                                    (SELECT CONCAT(f.fld_lname,' ',f.fld_fname) AS studentname, f.fld_id AS studentid
                                                    FROM itc_class_rotation_schedulegriddet AS a 
                                                    LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_id) 
                                                    LEFT JOIN itc_mathmodule_master AS c ON a.fld_module_id=c.fld_id
                                                    LEFT JOIN itc_module_version_track AS d ON c.fld_module_id=d.fld_mod_id
                                                    LEFT JOIN itc_user_master as f ON f.fld_id=a.fld_student_id
                                                    WHERE a.fld_class_id='".$classid."' AND a.fld_module_id='".$assignmentid."'  AND a.fld_flag='1' AND b.fld_flag='1' AND b.fld_delstatus='0' 
                                                    AND b.fld_moduletype='2' AND c.fld_delstatus='0' AND d.fld_delstatus='0' AND a.fld_type='2' AND f.fld_activestatus='1' AND f.fld_delstatus='0')
                                                UNION ALL	
                                                    (SELECT CONCAT(f.fld_lname,' ',f.fld_fname) AS studentname, f.fld_id AS studentid
                                                    FROM itc_class_indassesment_master AS a 
                                                    LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
                                                    LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id 
                                                    LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id
                                                    LEFT JOIN itc_user_master as f ON f.fld_id=b.fld_student_id
                                                    WHERE a.fld_class_id='".$classid."' AND a.fld_module_id='".$assignmentid."'  AND a.fld_flag='1' AND b.fld_flag='1' AND a.fld_delstatus='0' 
                                                    AND a.fld_moduletype='1' AND c.fld_delstatus='0' AND d.fld_delstatus='0' AND f.fld_activestatus='1' AND f.fld_delstatus='0')
                                                UNION ALL	
                                                    (SELECT CONCAT(f.fld_lname,' ',f.fld_fname) AS studentname, f.fld_id AS studentid
                                                    FROM itc_class_indassesment_master AS a 
                                                    LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
                                                    LEFT JOIN itc_mathmodule_master AS c ON a.fld_module_id=c.fld_id 
                                                    LEFT JOIN itc_module_version_track AS d ON c.fld_module_id=d.fld_mod_id
                                                    LEFT JOIN itc_user_master as f ON f.fld_id = b.fld_student_id
                                                    WHERE a.fld_class_id='".$classid."' AND a.fld_module_id='".$assignmentid."' AND a.fld_flag='1' AND b.fld_flag='1' AND a.fld_delstatus='0' 
                                                    AND a.fld_moduletype='2' AND c.fld_delstatus='0' AND d.fld_delstatus='0' AND f.fld_activestatus='1' AND f.fld_delstatus='0')
                                                UNION ALL	
                                                    (SELECT CONCAT(f.fld_lname,' ',f.fld_fname) AS studentname, f.fld_id AS studentid
                                                    FROM itc_class_indassesment_master AS a 
                                                    LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
                                                    LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id 
                                                    LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id
                                                    LEFT JOIN itc_user_master as f ON f.fld_id = b.fld_student_id
                                                    WHERE a.fld_class_id='".$classid."' AND a.fld_module_id='".$assignmentid."' AND a.fld_flag='1' AND b.fld_flag='1' AND a.fld_delstatus='0' 
                                                    AND a.fld_moduletype='7' AND c.fld_delstatus='0' AND d.fld_delstatus='0' AND f.fld_activestatus='1' AND f.fld_delstatus='0')
                                                UNION ALL	
                                                    (SELECT CONCAT(f.fld_lname,' ',f.fld_fname) AS studentname, f.fld_id AS studentid
                                                    FROM itc_class_indasexpedition_master AS a 
                                                    LEFT JOIN itc_class_exp_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
                                                    LEFT JOIN itc_user_master as f ON f.fld_id = b.fld_student_id
                                                    WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_flag='1' AND a.fld_delstatus='0' 
                                                    AND f.fld_activestatus='1' AND f.fld_delstatus='0')
                                                UNION ALL	
                                                    (SELECT CONCAT(a.fld_fname,' ',a.fld_lname) AS studentname, a.fld_id AS studentid 
                                                    FROM itc_user_master AS a 
                                                    LEFT JOIN itc_class_rotation_expschedulegriddet AS b ON a.fld_id=b.fld_student_id 
                                                    WHERE a.fld_activestatus='1' AND a.fld_delstatus='0' 
                                                    AND b.fld_class_id='".$classid."' AND b.fld_flag='1') 
                                                

                                                )AS w 
                                                GROUP BY w.studentid ORDER BY studentname");
                                    }
				}
                                else if($type==3)
				{                      
                                    $qry =("SELECT w.* FROM (SELECT CONCAT(c.fld_lname,' ',c.fld_fname) AS studentname, c.fld_id AS studentid
                                                    FROM itc_test_master AS a 
                                                    LEFT JOIN itc_test_student_mapping AS b ON a.fld_id=b.fld_test_id
                                                    LEFT JOIN itc_user_master as c ON c.fld_id = b.fld_student_id 
                                                    WHERE a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_class_id='".$classid."' 
                                                      AND b.fld_test_id='".$assignmentid."' AND b.fld_flag='1' 
                                                             AND c.fld_activestatus='1' AND c.fld_delstatus='0'
                                                     ORDER BY a.fld_test_name) AS w GROUP BY w.studentid ORDER BY studentname"); //AND a.fld_ass_type='0'
				}
				$qrydetails = $ObjDB->QueryObject($qry);
					
				if($qrydetails->num_rows>0)
				{
					//Select All Students
					
                                    ?>
                                         <li><a tabindex="-1" href="#" data-option="0" onclick="$('#viewreportdiv').show();">All Students</a></li>
                                    <?php 
					while($row = $qrydetails->fetch_assoc())
					{
						extract($row);
						?>
						<li><a tabindex="-1" href="#" data-option="<?php echo $studentid;?>" onclick="$('#viewreportdiv').show();"><?php echo $studentname; ?></a></li>
						<?php
					}
				}	?>      
			</ul>
		</div>
	</div>
	<?php
}

/*--- Load Assignment Dropdown For Module ---*/
if($oper=="loadmoduleassignments" and $oper != " " )
{
    error_reporting(E_ALL);
   ini_set('display_errors', '1');

        
	$classid = isset($method['classid']) ? $method['classid'] : '';	
	$type = isset($method['type']) ? $method['type'] : '';

	?>
    Assignment
	<div class="selectbox">
		<input type="hidden" name="moduleid" id="moduleid" value="">
		<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
			<span class="selectbox-option input-medium" data-option="" style="width:97%">Select Assignment</span>
			<b class="caret1"></b>
		</a>
		<div class="selectbox-options">
			<input type="text" class="selectbox-filter" placeholder="Search Assignment">
			<ul role="options" style="width:100%">
				<?php 
				$qry = $ObjDB->QueryObject("SELECT w.* FROM (
                                                                    (SELECT a.fld_schedule_id AS scheduleid, a.fld_module_id AS moduleid, 1 AS typename, CONCAT(c.fld_module_name,' ',d.fld_version,' / Module / ',b.fld_schedule_name) AS modulename 
                                                                    FROM itc_class_rotation_schedulegriddet AS a 
                                                                    LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_id) 
                                                                    LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id
                                                                    LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id
                                                                    LEFT JOIN  itc_class_rotation_schedule_student_mappingtemp AS e ON e.fld_schedule_id = b.fld_id
                                                                    WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_flag='1' AND b.fld_delstatus='0' 
                                                                    AND b.fld_moduletype='1' AND c.fld_delstatus='0' AND d.fld_delstatus='0' AND a.fld_type='1' AND e.fld_flag='1' )
                                                                UNION ALL	 
                                                                    (SELECT a.fld_schedule_id AS scheduleid, a.fld_module_id AS moduleid, 2 AS typename, CONCAT(b.fld_module_name,' ',c.fld_version,' / Dyad / ',e.fld_schedule_name) AS modulename 
                                                                    FROM itc_class_dyad_schedulegriddet AS a 
                                                                    LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
                                                                    LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
                                                                    LEFT JOIN itc_class_dyad_schedule_studentmapping AS d ON (d.fld_schedule_id=a.fld_schedule_id)
                                                                    LEFT JOIN itc_class_dyad_schedulemaster as e on a.fld_schedule_id = e.fld_id
                                                                    WHERE  a.fld_class_id='".$classid."' AND a.fld_flag='1' AND d.fld_flag='1'
                                                                    AND b.fld_delstatus='0' AND c.fld_delstatus='0'  AND e.fld_delstatus='0')
                                                                UNION ALL	
                                                                    (SELECT a.fld_schedule_id AS scheduleid, a.fld_module_id AS moduleid, 3 AS typename, CONCAT(b.fld_module_name,' ',c.fld_version,' / Triad / ',e.fld_schedule_name) AS modulename 
                                                                    FROM itc_class_triad_schedulegriddet AS a 
                                                                    LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
                                                                    LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
                                                                    LEFT JOIN itc_class_triad_schedule_studentmapping AS d ON (d.fld_schedule_id=a.fld_schedule_id)
                                                                    LEFT JOIN itc_class_triad_schedulemaster AS e ON a.fld_schedule_id = e.fld_id
                                                                    WHERE  a.fld_class_id='".$classid."' AND a.fld_flag='1' AND d.fld_flag='1'
                                                                    AND b.fld_delstatus='0' AND c.fld_delstatus='0'  AND e.fld_delstatus='0')
                                                                UNION ALL		
                                                                    (SELECT a.fld_schedule_id AS scheduleid, a.fld_module_id AS moduleid, 4 AS typename, CONCAT(c.fld_mathmodule_name,' ',d.fld_version,' / MM / ',b.fld_schedule_name) AS modulename 
                                                                    FROM itc_class_rotation_schedulegriddet AS a 
                                                                    LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_id) 
                                                                    LEFT JOIN itc_mathmodule_master AS c ON a.fld_module_id=c.fld_id
                                                                    LEFT JOIN itc_module_version_track AS d ON c.fld_module_id=d.fld_mod_id
                                                                    WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_flag='1' AND b.fld_delstatus='0' 
                                                                    AND b.fld_moduletype='2' AND c.fld_delstatus='0' AND d.fld_delstatus='0' AND a.fld_type='2')
                                                                UNION ALL	
                                                                    (SELECT a.fld_id AS scheduleid, a.fld_module_id AS moduleid, 5 AS typename, CONCAT(c.fld_module_name,' ',d.fld_version,' / Ind Module / ',a.fld_schedule_name) AS modulename 
                                                                    FROM itc_class_indassesment_master AS a 
                                                                    LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
                                                                    LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id 
                                                                    LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id
                                                                    WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_flag='1' AND a.fld_delstatus='0' 
                                                                    AND a.fld_moduletype='1' AND c.fld_delstatus='0' AND d.fld_delstatus='0')
                                                                UNION ALL	
                                                                    (SELECT a.fld_id AS scheduleid, a.fld_module_id AS moduleid, 6 AS typename, CONCAT(c.fld_mathmodule_name,' ',d.fld_version,' / Ind MM / ',a.fld_schedule_name) AS modulename 
                                                                    FROM itc_class_indassesment_master AS a 
                                                                    LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
                                                                    LEFT JOIN itc_mathmodule_master AS c ON a.fld_module_id=c.fld_id 
                                                                    LEFT JOIN itc_module_version_track AS d ON c.fld_module_id=d.fld_mod_id
                                                                    WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_flag='1' AND a.fld_delstatus='0' 
                                                                    AND a.fld_moduletype='2' AND c.fld_delstatus='0' AND d.fld_delstatus='0')
                                                                UNION ALL	
                                                                    (SELECT a.fld_id AS scheduleid, a.fld_module_id AS moduleid, 7 AS typename, CONCAT(c.fld_module_name,' ',d.fld_version,' / Ind Quest / ',a.fld_schedule_name) AS modulename 
                                                                    FROM itc_class_indassesment_master AS a 
                                                                    LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
                                                                    LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id 
                                                                    LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id
                                                                    WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_flag='1' AND a.fld_delstatus='0' 
                                                                    AND a.fld_moduletype='7' AND c.fld_delstatus='0' AND d.fld_delstatus='0')
                                                                UNION ALL	
                                                                    (SELECT a.fld_id AS scheduleid, a.fld_exp_id AS moduleid, 15 AS typename, CONCAT(c.fld_exp_name,' ',d.fld_version,' / Expedition / ',a.fld_schedule_name) AS modulename 
                                                                    FROM itc_class_indasexpedition_master AS a 
                                                                    LEFT JOIN itc_class_exp_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
                                                                    LEFT JOIN itc_exp_master AS c ON a.fld_exp_id=c.fld_id 
                                                                    LEFT JOIN itc_exp_version_track AS d ON c.fld_id=d.fld_exp_id
                                                                    WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_flag='1' AND a.fld_delstatus='0' 
                                                                    AND c.fld_delstatus='0' AND d.fld_delstatus='0')                                                                    
                                                                UNION ALL
                                                                    (SELECT a.fld_schedule_id AS scheduleid, a.fld_expedition_id AS moduleid, 19 AS typename,
                                                                        CONCAT(c.fld_exp_name, ' ', d.fld_version, ' / ', 'Expedition Sch / ',b.fld_schedule_name) AS modulename
                                                                    FROM itc_class_rotation_expschedulegriddet AS a
                                                                    LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON a.fld_schedule_id = b.fld_id
                                                                    LEFT JOIN itc_exp_master AS c ON c.fld_id = a.fld_expedition_id
                                                                    LEFT JOIN itc_exp_version_track AS d ON d.fld_exp_id = c.fld_id
                                                                    WHERE a.fld_class_id = '".$classid."' AND a.fld_flag = '1' AND b.fld_delstatus = '0' AND b.fld_flag = '1'
                                                                    AND c.fld_delstatus = '0' AND d.fld_delstatus = '0')
                                                                UNION ALL 
                                                                    (SELECT a.fld_schedule_id AS schduleid, a.fld_module_id AS moduleid,20 AS typename, CONCAT(c.fld_exp_name, ' ', d.fld_version, ' / ', 'Mod-Exp / ',b.fld_schedule_name) AS modulename
                                                                    FROM itc_class_rotation_modexpschedulegriddet AS a
                                                                    LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id = b.fld_id
                                                                    LEFT JOIN itc_exp_master AS c ON c.fld_id = a.fld_module_id
                                                                    LEFT JOIN itc_exp_version_track AS d ON d.fld_exp_id = c.fld_id
                                                                    WHERE a.fld_class_id = '".$classid."' AND a.fld_flag = '1' AND b.fld_delstatus = '0' AND b.fld_flag = '1' 
                                                                    AND a.fld_type='2' AND c.fld_delstatus = '0' AND d.fld_delstatus = '0' )
                                                                UNION ALL 
                                                                    (SELECT a.fld_schedule_id AS schduleid, a.fld_module_id AS moduleid,21 AS typename, CONCAT(c.fld_module_name, ' ', d.fld_version, ' / ', 'Mod-Exp / ',b.fld_schedule_name) AS modulename
                                                                    FROM itc_class_rotation_modexpschedulegriddet AS a
                                                                    LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id = b.fld_id
                                                                    LEFT JOIN itc_module_master AS c ON c.fld_id = a.fld_module_id
                                                                    LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id = c.fld_id
                                                                    WHERE a.fld_class_id = '".$classid."' AND a.fld_flag = '1' AND b.fld_delstatus = '0' AND b.fld_flag = '1' AND a.fld_type = '1'
                                                                    AND c.fld_delstatus = '0'  AND d.fld_delstatus = '0')


                                                            )AS w 
                                                            GROUP BY w.typename, w.modulename");
				if($qry->num_rows>0){
					while($row = $qry->fetch_assoc())
					{
						extract($row);
						?>
						<li><a tabindex="-1" href="#" data-option="<?php echo $scheduleid.'~'.$moduleid.'~'.$typename;?>" onclick="$('#studentdiv').show(); fn_showstudent(<?php echo $classid.",".$moduleid.",".$type.",".$typename; ?>); <?php if($typename==19){ ?> fn_dummyexpsch(1); <?php }else{ ?> fn_dummyexpsch(0); <?php } ?> "><?php echo $modulename; ?></a></li>
						<?php
					}
				}?>      
			</ul>
		</div>
	</div>
	<?php
}

/*--- Load Assignments Dropdown For IPL ---*/
if($oper=="showiplassignments" and $oper != " " )
{
	$classid = isset($method['classid']) ? $method['classid'] : '';
	$type = isset($method['type']) ? $method['type'] : '';		
	?>
    Assignment
	<div class="selectbox">
		<input type="hidden" name="assignmentid" id="assignmentid" value="">
		<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
			<span class="selectbox-option input-medium" data-option="" style="width:97%">Select Assignment</span>
			<b class="caret1"></b>
		</a>
		<div class="selectbox-options">
			<input type="text" class="selectbox-filter" placeholder="Search Assignment">
			<ul role="options" style="width:100%">
				<?php 
				
				$qry = $ObjDB->QueryObject("SELECT w.* FROM (
											(SELECT a.fld_id AS assignmentid, a.fld_schedule_name AS assignmentname, 1 AS typename 
											FROM itc_class_sigmath_master AS a 
											LEFT JOIN itc_class_sigmath_student_mapping AS b ON a.fld_id=b.fld_sigmath_id 
											WHERE b.fld_flag=1 AND a.fld_delstatus=0 AND a.fld_class_id='".$classid."') 
												UNION ALL	
											(SELECT a.fld_id AS assignmentid, CONCAT(a.fld_schedule_name,' / MM') AS assignmentname, 2 AS typename 
											FROM itc_class_rotation_schedule_mastertemp AS a 
											LEFT JOIN itc_class_rotation_schedule_student_mappingtemp AS b ON a.fld_id=b.fld_schedule_id 
											WHERE b.fld_flag=1 AND a.fld_delstatus=0 AND a.fld_class_id='".$classid."' AND a.fld_moduletype='2')		
												UNION ALL 		
											(SELECT a.fld_id AS assignmentid, CONCAT(a.fld_schedule_name,' / Ind MM') AS assignmentname, 5 AS typename 
											FROM itc_class_indassesment_master AS a 
											LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
											WHERE b.fld_flag=1 AND a.fld_delstatus=0 AND a.fld_class_id='".$classid."' AND a.fld_moduletype='2' 
													AND a.fld_flag='1')
										) AS w 
										GROUP BY w.typename, w.assignmentname");
				if($qry->num_rows>0){
					while($row = $qry->fetch_assoc())
					{
						extract($row);
						?>
						<li><a tabindex="-1" href="#" data-option="<?php echo $assignmentid;?>"  onclick="$('#iplunitdiv').show(); fn_showipl(<?php echo $assignmentid.",".$typename.",".$classid.",".$type; ?>)"><?php echo $assignmentname; ?></a></li>
						<?php
					}
				}?>      
			</ul>
		</div>
	</div>
	<?php
}

/*--- Load Assignments Dropdown For Test ---*/
if($oper=="loadtestassignments" and $oper != " " )
{
	$classid = isset($method['classid']) ? $method['classid'] : '';
	$type = isset($method['type']) ? $method['type'] : '';	
	?>
    Assignment
	<div class="selectbox">
		<input type="hidden" name="assignmentid" id="assignmentid" value="">
		<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
			<span class="selectbox-option input-medium" data-option="" style="width:97%">Select Assignment</span>
			<b class="caret1"></b>
		</a>
		<div class="selectbox-options">
			<input type="text" class="selectbox-filter" placeholder="Search Assignment">
			<ul role="options" style="width:100%">
				<?php 
				$qry = $ObjDB->QueryObject("SELECT a.fld_id AS assignmentid, a.fld_test_name AS assignmentname, a.fld_score, a.fld_total_question 
											FROM itc_test_master AS a 
											LEFT JOIN itc_test_student_mapping AS b ON a.fld_id=b.fld_test_id 
											WHERE a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_class_id='".$classid."' AND b.fld_flag='1' 
												
											GROUP BY a.fld_id 
											ORDER BY a.fld_test_name"); //AND a.fld_ass_type='0'
				if($qry->num_rows>0){
					while($row = $qry->fetch_assoc())
					{
						extract($row);
						?>
						<li><a tabindex="-1" href="#" data-option="<?php echo $assignmentid;?>" onclick="$('#studentdiv').show(); fn_showstudent(<?php echo $classid.",".$assignmentid.",".$type;?>);"><?php echo $assignmentname; ?></a></li>
						<?php
					}
				}?>      
			</ul>
		</div>
	</div>
	<?php
}

/*--- Load ipl Dropdown ---*/
if($oper=="showipl" and $oper != " " )
{
	$assignmentid = isset($method['assignmentid']) ? $method['assignmentid'] : '';
	$assignmentid = explode(",",$assignmentid);

	$iplid1 = '';
	$iplid2 = '';
	?>
    IPL Unit
	<div class="selectbox">
		<input type="hidden" name="iplid" id="iplid" value="">
		<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
			<span class="selectbox-option input-medium" data-option="" style="width:97%">Select IPL Unit</span>
			<b class="caret1"></b>
		</a>
		<div class="selectbox-options">
			<input type="text" class="selectbox-filter" placeholder="Search IPL Unit">
			<ul role="options" style="width:100%">
				<?php 
				$qryipl = '';
				if($assignmentid[1]==1)
				{
					$qryipl = "SELECT a.fld_id AS iplid, CONCAT(a.fld_ipl_name,' ',b.fld_version) AS iplname, 0 AS daytype 
								FROM itc_ipl_master AS a 
								LEFT JOIN itc_ipl_version_track AS b ON a.fld_id=b.fld_ipl_id
								LEFT JOIN itc_class_sigmath_lesson_mapping AS c ON a.fld_id=c.fld_lesson_id 
								WHERE c.fld_sigmath_id='".$assignmentid[0]."' AND c.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_delstatus='0'
								GROUP BY a.fld_id
								ORDER BY iplname";
				}
					
				else if($assignmentid[1]==2)
					$qryipl = "SELECT a.fld_id, CONCAT(a.fld_ipl_day1,'~1') AS iplid1, CONCAT(a.fld_ipl_day2,'~2') AS iplid2,CONCAT(a.fld_mathmodule_name,' ',c.fld_version) AS modiplname 
								FROM itc_mathmodule_master AS a 
								LEFT JOIN itc_class_rotation_schedulegriddet AS b ON a.fld_id=b.fld_module_id 
								LEFT JOIN itc_module_version_track AS c ON a.fld_module_id=c.fld_mod_id
								WHERE b.fld_schedule_id='".$assignmentid[0]."' AND b.fld_flag=1 AND a.fld_delstatus='0' AND c.fld_delstatus='0' 
								ORDER BY modiplname";
				
				else if($assignmentid[1]==5)
					$qryipl = "SELECT a.fld_id, CONCAT(a.fld_ipl_day1,'~5') AS iplid1, CONCAT(a.fld_ipl_day2,'~6') AS iplid2, CONCAT(a.fld_mathmodule_name,' ',d.fld_version) AS modiplname 
								FROM itc_mathmodule_master AS a 
								LEFT JOIN itc_class_indassesment_master AS b ON a.fld_id=b.fld_module_id 
								LEFT JOIN itc_class_indassesment_student_mapping AS c ON b.fld_id=c.fld_schedule_id 
								LEFT JOIN itc_module_version_track AS d ON a.fld_module_id=d.fld_mod_id
								WHERE b.fld_id='".$assignmentid[0]."' AND b.fld_flag=1 AND a.fld_delstatus='0' 
										AND c.fld_flag='1' AND b.fld_delstatus='0' AND d.fld_delstatus='0'
								ORDER BY modiplname";
				
				$qryipldetails = $ObjDB->QueryObject($qryipl);
					
				if($qryipldetails->num_rows>0){
					while($rowqryipldetails = $qryipldetails->fetch_assoc())
					{
						extract($rowqryipldetails);
						if($iplid1!='')
						{
							$iplsplit = explode('~',$iplid1);
							$iplid = $iplsplit[0];
							$daytype = $iplsplit[1];
							$iplname = "Diagnostic Day1 / ".$modiplname;
						}
						?>
                        <li><a tabindex="-1" href="#" data-option="<?php echo $iplid.",".$daytype;?>" onclick="$('#studentdiv').show(); fn_showstudent(<?php echo $assignmentid[2].",".$assignmentid[0].",".$assignmentid[3];?>)"><?php echo $iplname; ?></a></li>
                        <?php
						if($iplid2!='')
						{
							$iplsplit = explode('~',$iplid2);
							$iplid = $iplsplit[0];
							$daytype = $iplsplit[1];
							$iplname = "Diagnostic Day2 / ".$modiplname;
							?>
                        <li><a tabindex="-1" href="#" data-option="<?php echo $iplid.",".$daytype;?>" onclick="$('#studentdiv').show(); fn_showstudent(<?php echo $assignmentid[2].",".$assignmentid[0].",".$assignmentid[3];?>)"><?php echo $iplname; ?></a></li>
                        <?php
                        }
					}
				}?>      
			</ul>
		</div>
	</div>
	<?php
}

/*--- Load Assignment Dropdown ---*/
if($oper=="showunits" and $oper != " " )
{
	$classid = isset($method['classid']) ? $method['classid'] : '';
	?>
    Assignment
	<div class="selectbox">
		<input type="hidden" name="assignmentid" id="assignmentid" value="">
		<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
			<span class="selectbox-option input-medium" data-option="" style="width:97%">Select Assignment</span>
			<b class="caret1"></b>
		</a>
		<div class="selectbox-options">
			<input type="text" class="selectbox-filter" placeholder="Search Assignment">
			<ul role="options" style="width:100%">
				<?php 
				$qry = $ObjDB->QueryObject("SELECT a.fld_id AS scheduleid, b.fld_unit_id AS unitid, c.fld_unit_name AS unitname  
											FROM itc_class_sigmath_master AS a 
											LEFT JOIN itc_class_sigmath_unit_mapping AS b ON a.fld_id=b.fld_sigmath_id 
											LEFT JOIN itc_unit_master AS c ON b.fld_unit_id=c.fld_id 
											WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_flag='1' AND c.fld_delstatus='0'
											ORDER BY unitname");
				if($qry->num_rows>0){
					while($row = $qry->fetch_assoc())
					{
						extract($row);
						?>
						<li><a tabindex="-1" href="#" data-option="<?php echo $unitid."~".$scheduleid."~".$unitname;?>" onclick="$('#iplunitdiv').show();"><?php echo $unitname; ?></a></li>
						<?php
					}
				}?>      
			</ul>
		</div>
	</div>
	<?php
}

/*--- Load Assignment Dropdown ---*/
if($oper=="showschedules" and $oper != " " )
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
				$qry = $ObjDB->QueryObject("SELECT fld_id, fld_schedule_name 
											FROM itc_class_sigmath_master 
											WHERE fld_class_id='".$classid ."' AND fld_flag='1' AND fld_delstatus='0' 
											ORDER BY fld_schedule_name");
				if($qry->num_rows>0){
					while($row = $qry->fetch_assoc())
					{
						extract($row);
						?>
						<li><a tabindex="-1" href="#" data-option="<?php echo $fld_id;?>" onclick="$('#stupassdiv').show(); $('#viewreportdiv').show();"><?php echo $fld_schedule_name; ?></a></li>
						<?php
					}
				}?>      
			</ul>
		</div>
	</div>
	<?php
}




/*--- District/Pitsco ---*/
if($oper=="showteachers" and $oper != " " )
{
	$schoolid = isset($method['schoolid']) ? $method['schoolid'] : '';
	$individualid = isset($method['individualid']) ? $method['individualid'] : '';
	$val = isset($method['val']) ? $method['val'] : '';
	?>
	Teachers
	<div class="selectbox">
		<input type="hidden" name="teacherid" id="teacherid" value="">
		<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
			<span class="selectbox-option input-medium" data-option="" style="width:97%">Select Teacher</span>
			<b class="caret1"></b>
		</a>
		<div class="selectbox-options">
			<input type="text" class="selectbox-filter" placeholder="Search Teacher">
			<ul role="options" style="width:100%">
				<?php 
				$qry = $ObjDB->QueryObject("SELECT CONCAT(fld_lname,' ',fld_fname) AS teachername, fld_id AS teacherid 
											FROM itc_user_master 
											WHERE fld_activestatus='1' AND fld_delstatus='0' AND fld_profile_id IN (7,8,9) AND fld_school_id='".$schoolid."' 
													AND fld_user_id='".$individualid."' 
											ORDER BY teachername");
				if($qry->num_rows>0){
					while($row = $qry->fetch_assoc())
					{
						extract($row);
						?>
						<li><a tabindex="-1" href="#" data-option="<?php echo $teacherid;?>" onclick="fn_showclass(<?php echo $teacherid;?>,<?php echo $val;?>);"><?php echo $teachername; ?></a></li>
						<?php
					}
				}?>      
			</ul>
		</div>
	</div>
	<?php
}

if($oper=="showclass" and $oper != " " )
{
	$teacherid = isset($method['teacherid']) ? $method['teacherid'] : '';
	$schoolid = isset($method['schoolid']) ? $method['schoolid'] : '0';
	$indid = isset($method['indid']) ? $method['indid'] : '0';
	$val = isset($method['val']) ? $method['val'] : '0';
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
					$qry = $ObjDB->QueryObject("SELECT fld_id AS classid, fld_class_name AS classname 
												FROM itc_class_master 
												WHERE fld_delstatus='0' AND (fld_created_by='".$teacherid."' 
												OR fld_id IN (SELECT fld_class_id 
																FROM itc_class_teacher_mapping 
																WHERE fld_teacher_id='".$teacherid."' AND fld_flag='1')) 
												ORDER BY fld_class_name");
					if($qry->num_rows>0){
						while($row = $qry->fetch_assoc())
						{
							extract($row);
							if($val == 1)
								$function = "$('#studentdiv').show(); fn_showstudent(1,".$classid.");";
							if($val == 2)
								$function = "$('#schedulediv').show(); fn_load_schedules(".$classid.");";
							if($val == 3)
								$function = "$('#studentdiv').show(); fn_showstudent(2,".$classid.")";
							if($val == 4)
								$function = "$('#studentdiv').show(); fn_showstudent(3,".$classid.")";
							?>
							<li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>" onclick="<?php echo $function;?>"><?php echo $classname;?></a></li>
							<?php
						}
					}?>      
				</ul>
			</div>
		</div> 
	</dl>
	<?php
}

@include("footer.php");