<?php
error_reporting(0);
@include("sessioncheck.php");
/*
 This file will generate our CSV table. There is nothing to display on this page, it is simply used
 to generate our CSV file and then exit. That way we won't be re-directed after pressing the export
 to CSV button on the previous page.
*/

//First we'll generate an output variable called out. It'll have all of our text for the CSV file.
$out = '';

//Next we'll check to see if our variables posted and if they did we'll simply append them to out.
$ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

$id=explode(",",$ids);

//print_r($id);
//Class Schedule report
if($id[0]==1)
{
	$rotid = explode("~",$id[3]);
        if($rotid=='')
        {
           $rotid=0; 
        }
	$name="Class_Schedule";	
	
	$csv_hdr = "";
	$out .= $csv_hdr;
	
	$qryclass = $ObjDB->QueryObject("SELECT fld_class_name AS classname, fld_period AS period 
												FROM itc_class_master 
												WHERE fld_id='".$id[2]."'");
			
	$row=$qryclass->fetch_assoc();
	extract($row);
		
	$ends = array('th','st','nd','rd','th','th','th','th','th','th');
	if (($period %100) >= 11 and ($period%100) <= 13)
	   $abbreviation = $period. 'th';
	else
	   $abbreviation = $period. $ends[$period % 10];
	  
	$out .= "Class Name : ,".$classname.' '.$abbreviation.' Period';
	$out .= "\n\n";
	
	for($totcnt=0;$totcnt<sizeof($rotid);$totcnt++)
	{
		if($id[4]==1 or $id[4]==4 or $id[4]==20)
		{
			$newrot = $rotid[$totcnt];
			$newrot++;
		}
		else
			$newrot = $rotid[$totcnt];
		
                if($newrot==0 AND $id[4]<=4){$rotname="Orientation";}else if($newrot==0 AND $id[4]>4){$rotname="WCA";} else{ $rotname="Rotation ".$rotid[$totcnt];}
		
		$out .= $rotname;
        $out .= "\n\n";
		
		$out .= " , Student Name , Grade , Percentage , Attendance , Participation ,  Module Guide ,  RCA 2 ,  RCA 3 ,  RCA 4 ,  RCA 5 ,  Post Test ,  Performance assessment1 ,  Performance assessment2 ,  Performance assessment3 , ";
		if($id[4]==4 OR $id[4]==6) {
			$out .= "Diagnostic Day1 , Diagnostic Day2 ,  ";
		}
		$out .= "Total ";
		$out .= "\n";
		
		
		$roundflag = $ObjDB->SelectSingleValue("SELECT fld_roundflag 
												FROM itc_class_grading_scale_mapping 
												WHERE fld_class_id = '".$id[2]."' AND fld_flag = '1' 
												GROUP BY fld_roundflag");
		
		$qrysub = '';
		
		if($id[4]==1)
			$qrysub = "SELECT a.fld_row_id, a.fld_module_id, CONCAT(b.fld_module_name,' ',c.fld_version) AS modulename 
						FROM itc_class_rotation_schedulegriddet AS a 
						LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
						LEFT JOIN itc_module_version_track AS c ON a.fld_module_id=c.fld_mod_id 
						WHERE a.fld_schedule_id ='".$id[1]."' AND a.fld_class_id ='".$id[2]."' AND a.fld_flag='1' 
							AND a.fld_rotation ='".$newrot."' AND b.fld_delstatus='0' AND c.fld_delstatus='0' 
						GROUP BY a.fld_row_id ORDER BY modulename";
		
		else if($id[4]==2)
			$qrysub = "SELECT a.fld_row_id, a.fld_module_id, CONCAT(b.fld_module_name,' ',c.fld_version) AS modulename 
						FROM itc_class_dyad_schedulegriddet AS a 
						LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
						LEFT JOIN itc_module_version_track AS c ON a.fld_module_id=c.fld_mod_id
						WHERE a.fld_schedule_id ='".$id[1]."' AND a.fld_class_id ='".$id[2]."' AND a.fld_flag='1' 
							AND a.fld_rotation ='".$newrot."' AND b.fld_delstatus='0' AND c.fld_delstatus='0' 
						GROUP BY a.fld_row_id ORDER BY modulename";
		
		else if($id[4]==3)
			$qrysub = "SELECT a.fld_row_id, a.fld_module_id, CONCAT(b.fld_module_name,' ',c.fld_version) AS modulename 
						FROM itc_class_triad_schedulegriddet AS a 
						LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
						LEFT JOIN itc_module_version_track AS c ON a.fld_module_id=c.fld_mod_id
						WHERE a.fld_schedule_id ='".$id[1]."' AND a.fld_class_id ='".$id[2]."' AND a.fld_flag='1' 
							AND a.fld_rotation ='".$newrot."' AND b.fld_delstatus='0' AND c.fld_delstatus='0' 
						GROUP BY a.fld_row_id ORDER BY modulename";
		
		else if($id[4]==4)
			$qrysub = "SELECT a.fld_row_id, a.fld_module_id, CONCAT(b.fld_mathmodule_name,' ',c.fld_version) AS modulename 
						FROM itc_class_rotation_schedulegriddet AS a 
						LEFT JOIN itc_mathmodule_master AS b ON a.fld_module_id=b.fld_id 
						LEFT JOIN itc_module_version_track AS c ON b.fld_module_id=c.fld_mod_id
						WHERE a.fld_schedule_id ='".$id[1]."' AND a.fld_class_id ='".$id[2]."' AND a.fld_flag='1' 
							AND a.fld_rotation ='".$newrot."' AND b.fld_delstatus='0' AND c.fld_delstatus='0' 
						GROUP BY a.fld_row_id ORDER BY modulename";
		else if($id[4]==5)
		
			$qrysub = "SELECT  a.fld_module_id, CONCAT(b.fld_module_name,' ',c.fld_version) AS modulename 
							FROM itc_class_indassesment_master AS a 
							LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
							LEFT JOIN itc_module_version_track AS c ON a.fld_module_id=c.fld_mod_id 
							WHERE a.fld_id ='".$id[1]."' AND a.fld_class_id ='".$id[2]."' AND a.fld_flag='1' 
						        AND b.fld_delstatus='0' AND c.fld_delstatus='0'";
                
		else if($id[4]==6)
			$qrysub = "SELECT  a.fld_module_id, CONCAT(b.fld_mathmodule_name,' ',c.fld_version) AS modulename 
							FROM itc_class_indassesment_master AS a 
							LEFT JOIN itc_mathmodule_master AS b ON a.fld_module_id=b.fld_id 
							LEFT JOIN itc_module_version_track AS c ON b.fld_module_id=c.fld_mod_id
							WHERE a.fld_id ='".$id[1]."' AND a.fld_class_id ='".$id[2]."' AND a.fld_flag='1' 
								 AND b.fld_delstatus='0' AND c.fld_delstatus='0'";
		else if($id[4]==7)
	            $qrysub = "SELECT  a.fld_module_id, CONCAT(b.fld_module_name,' ',c.fld_version) AS modulename 
							FROM itc_class_indassesment_master AS a 
							LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
							LEFT JOIN itc_module_version_track AS c ON a.fld_module_id=c.fld_mod_id 
							WHERE a.fld_id ='".$id[1]."' AND a.fld_class_id ='".$id[2]."' AND a.fld_flag='1' 
						        AND b.fld_delstatus='0' AND c.fld_delstatus='0' AND b.fld_module_type='7'";
		else if($id[4]==20)
				$qrysub = "SELECT a.fld_row_id, a.fld_module_id, CONCAT(b.fld_module_name,' ',c.fld_version) AS modulename 
							FROM itc_class_rotation_modexpschedulegriddet AS a 
							LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
							LEFT JOIN itc_module_version_track AS c ON a.fld_module_id=c.fld_mod_id 
							WHERE a.fld_schedule_id ='".$id[1]."' AND a.fld_class_id ='".$id[2]."' AND a.fld_flag='1' 
								AND a.fld_rotation ='".$newrot."' AND b.fld_delstatus='0' AND c.fld_delstatus='0' AND a.fld_type='1'
							GROUP BY a.fld_row_id ORDER BY modulename";   
			
                
		
		$qrymodule = $ObjDB->QueryObject($qrysub);
		
		if($qrymodule->num_rows>0)
                {
			while($rowmodule=$qrymodule->fetch_assoc())
			{
				extract($rowmodule);
				if($id[4]==1 or $id[4]==4)
					$tablename = "itc_class_rotation_schedulegriddet";
				
				else if($id[4]==2)
				{
					$tablename = "itc_class_dyad_schedulegriddet";
					$stutablename = "itc_class_dyad_schedule_studentmapping";
				}
				
				else if($id[4]==3)
				{	
					$tablename = "itc_class_triad_schedulegriddet";
					$stutablename = "itc_class_triad_schedule_studentmapping";
				}
				else if($id[4]==20)
				{
					$tablename = "itc_class_rotation_modexpschedulegriddet";
				}
				else
				{
					$tablename = "itc_class_indassesment_student_mapping";
				}
				
				if($newrot==0)
					$qrystudent = $ObjDB->QueryObject("SELECT a.fld_id AS studentid, CONCAT(a.fld_fname,' ',a.fld_lname) AS studentname
														FROM itc_user_master AS a 
														LEFT JOIN ".$tablename." AS b ON a.fld_id=b.fld_student_id 
														WHERE b.fld_schedule_id = '".$id[1]."' AND b.fld_flag='1' AND a.fld_delstatus='0' AND a.fld_activestatus='1'
														GROUP BY a.fld_id");
				
				else
					$qrystudent = $ObjDB->QueryObject("SELECT a.fld_id AS studentid, CONCAT(a.fld_fname,' ',a.fld_lname) AS studentname
														FROM itc_user_master AS a 
														LEFT JOIN ".$tablename." AS b ON a.fld_id=b.fld_student_id 
														WHERE b.fld_schedule_id = '".$id[1]."' AND b.fld_class_id = '".$id[2]."' 
															AND b.fld_rotation = '".$newrot."' AND b.fld_module_id = '".$fld_module_id."'  
															AND b.fld_row_id='".$fld_row_id."' AND b.fld_flag='1' AND a.fld_delstatus='0' 
															AND a.fld_activestatus='1'");
				
				$count=$qrystudent->num_rows;
				
				$out .= $modulename;
				
				$i=1;
				if($qrystudent->num_rows>0)
				{
					while($rowstudent=$qrystudent->fetch_assoc())
					{
						extract($rowstudent);
						
						if($id[4]==4 OR $id[4]==6)
						{
                                                    if($id[4]==6)
                                                    {
                                                        $ids=5;
                                                    }
                                                    else
                                                    {
                                                        $ids=4;
                                                    }
                                                    
							$qryipl1ids = $ObjDB->QueryObject("SELECT fld_ipl_day1, fld_ipl_day2 FROM itc_mathmodule_master WHERE fld_id='".$fld_module_id."' AND fld_delstatus='0'");
							
							if($qryipl1ids->num_rows>0)
							{
								while($rowqryipl1ids=$qryipl1ids->fetch_assoc())
								{
									extract($rowqryipl1ids);
									$ipl1ids = $fld_ipl_day1;
									$ipl2ids = $fld_ipl_day2;
									$mathipls = $ipl1ids.",".$ipl2ids;
								}
							}
							
							$qrypoints = $ObjDB->QueryObject("SELECT SUM(w.earnedpoints) AS earnedpoints, SUM(w.pointspossible) AS pointspossible FROM (
																(SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END) 
																	AS earnedpoints, SUM(fld_points_possible) AS pointspossible 
																FROM itc_module_points_master 
																WHERE fld_module_id='".$fld_module_id."' AND fld_schedule_id='".$id[1]."' AND fld_student_id='".$studentid."' 
																	AND fld_schedule_type='".$ids."' AND fld_delstatus='0' AND fld_grade<>'0') 		
																		UNION ALL		
																(SELECT ROUND(SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END)/4) 
																	AS earnedpoints, ROUND(SUM(fld_points_possible)/4) AS pointspossible 
																FROM itc_assignment_sigmath_master 
																WHERE fld_schedule_id='".$id[1]."' AND fld_student_id='".$studentid."' AND fld_module_id='".$fld_module_id."' AND fld_test_type='2' AND fld_class_id='".$id[2]."' AND fld_delstatus='0' 
																																		AND (fld_status='1' OR fld_status='2' OR fld_lock='1') AND fld_lesson_id IN (".$mathipls.")) 		
															) AS w");
							
							$qryiplvalues = $ObjDB->QueryObject("SELECT ROUND(SUM(CASE WHEN fld_lock='0' AND fld_lesson_id IN (".$ipl1ids.") THEN fld_points_earned WHEN fld_lock='1' 
																	AND fld_lesson_id IN (".$ipl1ids.") THEN fld_teacher_points_earned END)/4) AS pointsipl1, ROUND(SUM(CASE WHEN 
																	fld_lock='0' AND fld_lesson_id IN (".$ipl2ids.") THEN fld_points_earned WHEN fld_lock='1' 
																	AND fld_lesson_id IN (".$ipl2ids.") THEN fld_teacher_points_earned END)/4) AS pointsipl2  
																FROM itc_assignment_sigmath_master 
																WHERE fld_schedule_id='".$id[1]."'  AND fld_module_id='".$fld_module_id."' AND fld_student_id='".$studentid."' AND fld_test_type='2' AND fld_delstatus='0' ");
							
							if($qryiplvalues->num_rows>0)
							{
								while($rowqryiplvalues=$qryiplvalues->fetch_assoc())
								{
									extract($rowqryiplvalues);
									$day1 = $pointsipl1;
									$day2 = $pointsipl2;
								}
							}									
						}
						else
						{
							if($id[4]==20)
							{
								$qrypoints = $ObjDB->QueryObject("SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
																THEN fld_teacher_points_earned END) AS earnedpoints, 
																SUM(fld_points_possible) AS pointspossible, fld_grade AS grade 
																FROM itc_module_points_master 
																WHERE fld_module_id='".$fld_module_id."' AND fld_schedule_id='".$id[1]."' 
																	AND fld_student_id='".$studentid."' AND fld_schedule_type='21' 
																	AND fld_delstatus='0' AND fld_grade<>'0'");
							}
							else
							{
								$qrypoints = $ObjDB->QueryObject("SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
																THEN fld_teacher_points_earned END) AS earnedpoints, 
																SUM(fld_points_possible) AS pointspossible, fld_grade AS grade 
																FROM itc_module_points_master 
																WHERE fld_module_id='".$fld_module_id."' AND fld_schedule_id='".$id[1]."' 
																	AND fld_student_id='".$studentid."' AND fld_schedule_type='".$id[4]."' 
																	AND fld_delstatus='0' AND fld_grade<>'0'");
							}
						}
						
						if($qrypoints->num_rows>0)
						{
							$rowpoints=$qrypoints->fetch_assoc();
							extract($rowpoints);
							
							if($earnedpoints!='')
							{
								if($roundflag==0)
									$percentage = round(($earnedpoints/$pointspossible)*100,2);
								else
									$percentage = round(($earnedpoints/$pointspossible)*100);
								$perarray = explode('.',$percentage);
								$grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
																	FROM itc_class_grading_scale_mapping 
																	WHERE fld_class_id='".$id[2]."' AND fld_lower_bound<='".$perarray[0]."' 
																		AND fld_flag='1' AND fld_upper_bound>='".$perarray[0]."'"); 
							}
							else
							{
								$earnedpoints = " - ";
								$percentage = " - ";
								$grade = " - "; 
							}
						}
						
						$out .= " , ".$studentname." , ".$grade." , ".$percentage."% , ";
						
						for($j=1;$j<3;$j++) 
						{
							if($id[4]==6)
							{
								$id[4]=5;
								$ids=6;
							}
							
							if($id[4]==20)
							{
								$values = $ObjDB->SelectSingleValue("SELECT IFNULL(SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END),' - ') FROM `itc_module_points_master` WHERE fld_module_id='".$fld_module_id."' AND fld_schedule_id='".$id[1]."' AND fld_student_id='".$studentid."' AND fld_schedule_type='21' AND fld_type='".$j."' AND fld_delstatus='0'");
							}
							else
							{
								$values = $ObjDB->SelectSingleValue("SELECT IFNULL(SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END),' - ') FROM `itc_module_points_master` WHERE fld_module_id='".$fld_module_id."' AND fld_schedule_id='".$id[1]."' AND fld_student_id='".$studentid."' AND fld_schedule_type='".$id[4]."' AND fld_type='".$j."' AND fld_delstatus='0'");
							}
							$out .= $values." , ";
						}
						
						for($k=0;$k<7;$k++) {
							if($k!=5) {
								
								if($id[4]==20)
								{
									$values1 = $ObjDB->SelectSingleValue("SELECT IFNULL(SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END),' - ') FROM `itc_module_points_master` WHERE fld_module_id='".$fld_module_id."' AND fld_schedule_id='".$id[1]."' AND fld_student_id='".$studentid."' AND fld_schedule_type='21' AND fld_session_id='".$k."' AND fld_delstatus='0' AND fld_type='0' AND fld_preassment_id='0'");
								}
								else
								{
									$values1 = $ObjDB->SelectSingleValue("SELECT IFNULL(SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END),' - ') FROM `itc_module_points_master` WHERE fld_module_id='".$fld_module_id."' AND fld_schedule_id='".$id[1]."' AND fld_student_id='".$studentid."' AND fld_schedule_type='".$id[4]."' AND fld_session_id='".$k."' AND fld_delstatus='0' AND fld_type='0' AND fld_preassment_id='0'");
								}
								$out .= $values1." , ";
							} 
						}
						
						if($id[4]==4 OR $ids==6)
							$newmodid = $ObjDB->SelectSingleValueInt("SELECT fld_module_id FROM `itc_mathmodule_master` WHERE fld_id='".$fld_module_id."' AND fld_delstatus='0'");
						else
							$newmodid = $fld_module_id;
						
						for($l=0;$l<3;$l++) {
							if($id[4]==20)
							{
								$values2 = $ObjDB->SelectSingleValue("SELECT IFNULL(SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END),' - ') FROM `itc_module_points_master` WHERE fld_module_id='".$fld_module_id."' AND fld_schedule_id='".$id[1]."' AND fld_student_id='".$studentid."' AND fld_schedule_type='21' AND fld_delstatus='0' AND fld_type='3' AND fld_preassment_id = (SELECT fld_id FROM `itc_module_performance_master` WHERE fld_module_id='".$newmodid."' AND fld_delstatus='0' AND fld_performance_name<>'Participation' AND fld_performance_name<>'Attendance' AND fld_performance_name<>'Total Pages' LIMIT ".$l.",1 )");
							}
							else
							{
								$values2 = $ObjDB->SelectSingleValue("SELECT IFNULL(SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END),' - ') FROM `itc_module_points_master` WHERE fld_module_id='".$fld_module_id."' AND fld_schedule_id='".$id[1]."' AND fld_student_id='".$studentid."' AND fld_schedule_type='".$id[4]."' AND fld_delstatus='0' AND fld_type='3' AND fld_preassment_id = (SELECT fld_id FROM `itc_module_performance_master` WHERE fld_module_id='".$newmodid."' AND fld_delstatus='0' AND fld_performance_name<>'Participation' AND fld_performance_name<>'Attendance' AND fld_performance_name<>'Total Pages' LIMIT ".$l.",1 )");
							}
							$out .= $values2." , ";
						}
						
						if($id[4]==4 OR $ids==6) {
							if($day1=='') $values3=" - "; else $values3=$day1;
							if($day2=='') $values4=" - "; else $values4=$day2;
							$out .= $values3." , ".$values4." , ";							
						}
						$out .= $earnedpoints;
						$out .= "\n";
						$i++;
					}
				}
			}
		}
		$out .="\n\n\n";
	}
}

//Individual Grade report
if($id[0]==2)
{
    
    $name="Individual_Grade";

    $startdate = date('Y-m-d',strtotime($id[4]));
    $enddate = date('Y-m-d',strtotime($id[5]));

    $sqry = "AND ('".$startdate."' BETWEEN b.fld_start_date AND b.fld_end_date OR '".$enddate."' BETWEEN b.fld_start_date AND b.fld_end_date OR b.fld_start_date BETWEEN '".$startdate."' AND '".$enddate."' OR b.fld_end_date BETWEEN '".$startdate."' AND '".$enddate."')";
    $sqry1 = " AND ('".$startdate."' BETWEEN b.fld_startdate AND b.fld_enddate OR '".$enddate."' BETWEEN b.fld_startdate AND b.fld_enddate OR b.fld_startdate BETWEEN '".$startdate."' AND '".$enddate."' OR b.fld_enddate BETWEEN '".$startdate."' AND '".$enddate."')";
    $sqry2 = " AND ('".$startdate."' BETWEEN c.fld_startdate AND c.fld_enddate OR '".$enddate."' BETWEEN c.fld_startdate AND c.fld_enddate OR c.fld_startdate BETWEEN '".$startdate."' AND '".$enddate."' OR c.fld_enddate BETWEEN '".$startdate."' AND '".$enddate."')";
    $sqry3 = " AND ('".$startdate."' BETWEEN a.fld_startdate AND a.fld_enddate OR '".$enddate."' BETWEEN a.fld_startdate AND a.fld_enddate OR a.fld_startdate BETWEEN '".$startdate."' AND '".$enddate."' OR a.fld_enddate BETWEEN '".$startdate."' AND '".$enddate."')";
    
    $sqry4 = " AND ('".$startdate."' BETWEEN a.fld_startdate AND a.fld_enddate OR '".$enddate."' BETWEEN a.fld_startdate AND a.fld_enddate OR a.fld_startdate BETWEEN '".$startdate."' AND '".$enddate."' OR a.fld_enddate BETWEEN '".$startdate."' AND '".$enddate."')";


    $csv_hdr = "";
    $out .= $csv_hdr;

    $qryclass = $ObjDB->QueryObject("SELECT fld_class_name AS classname, fld_period AS period 
                                                                                            FROM itc_class_master 
                                                                                            WHERE fld_id='".$id[2]."'");

    $row=$qryclass->fetch_assoc();
    extract($row);

    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if (($period %100) >= 11 and ($period%100) <= 13)
       $abbreviation = $period. 'th';
    else
       $abbreviation = $period. $ends[$period % 10];

    $out .= "Class Name : ,".$classname.' '.$abbreviation.' Period';
    $out .= "\n\n";

    if($id[1]==0)
    {
            $qrystudents = $ObjDB->QueryObject("SELECT CONCAT(a.fld_fname,' ',a.fld_lname) AS studentname, a.fld_id AS studentid 
                                                                FROM itc_user_master AS a 
                                                                LEFT JOIN itc_class_student_mapping AS b ON a.fld_id=b.fld_student_id 
                                                                WHERE a.fld_activestatus='1' AND a.fld_delstatus='0' 
                                                                AND b.fld_class_id='".$id[2]."' AND b.fld_flag='1' 
                                                                ORDER BY a.fld_lname");
    }
    else
    {
            $qrystudents = $ObjDB->QueryObject("SELECT CONCAT(fld_fname,' ',fld_lname) AS studentname, fld_id AS studentid 
                                                    FROM itc_user_master 
                                                    WHERE fld_id='".$id[1]."'");
    }

    $roundflag = $ObjDB->SelectSingleValue("SELECT fld_roundflag 
                                                FROM itc_class_grading_scale_mapping 
                                                WHERE fld_class_id = '".$id[2]."' AND fld_flag = '1' 
                                                GROUP BY fld_roundflag");

    if($qrystudents->num_rows > 0)
    { 
        $count = 0;	 
        $cnt=0;
        while($rowqrystudents=$qrystudents->fetch_assoc())
        {
            extract($rowqrystudents);	

            $expearned = '';
            $exppossible = '';

            $qryexp = $ObjDB->QueryObject("SELECT a.fld_id AS scheduleid, a.fld_exp_id AS assid, '0' AS maxids, 
                                                fn_shortname(CONCAT(b.fld_exp_name,' / Expedition'),1) AS nam, 
                                                CONCAT(b.fld_exp_name,' / Expedition') AS fullnam, 15 AS typeids, a.fld_schedule_name AS schname, a.fld_startdate AS startdate, a.fld_enddate AS enddate 
                                                FROM itc_class_indasexpedition_master AS a 
                                                LEFT JOIN itc_exp_master AS b ON a.fld_exp_id=b.fld_id
                                                WHERE a.fld_class_id='".$id[2]."' AND a.fld_flag='1' AND a.fld_delstatus='0' 
                                                AND b.fld_delstatus='0' ".$sqry4."
                                                GROUP BY a.fld_id");
              if($qryexp->num_rows>0)
              {
                    $exptearned = '';
                    $exptpossible = '';
                    while($rowqryexp = $qryexp->fetch_assoc())
                    {
                          extract($rowqryexp);

                          /************** Pre/Post test code start here ***************/
                          $pointsearnedfortest=0;
                          $possiblepointfortest1=0;
                          $possiblepointfortest=0;

                            $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                                                WHERE fld_class_id='".$id[2]."' AND fld_sch_id='".$scheduleid."' AND fld_schtype_id='".$typeids."'");

                            if($qrytest->num_rows>0)
                            {
                                while($rowqrytest = $qrytest->fetch_assoc())
                                {
                                    extract($rowqrytest);
                                    $exptype='3';
                                    /*********Pre Test Code start Here*********/
                                    if($pretest!='0')
                                    {
                                        $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                                        FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_delstatus='0'");

                                        if($qry->num_rows>0)
                                        {
                                            while($rowqry = $qry->fetch_assoc())
                                            {
                                                extract($rowqry);

                                                $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                                if($questype==2)
                                                {
                                                    $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                                }

                                                $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                                $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."' 
                                                                                                    AND fld_exp_id='".$assid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                                    AND fld_schedule_type='".$typeids."' AND fld_grade='1' AND fld_res_id='".$testid."' 
                                                                                                    AND fld_exptype='3'");

                                                if(trim($tchpointcnt)=='')
                                                {
                                                    $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                            WHERE b.fld_expt = '".$assid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                            AND a.fld_schedule_id='".$scheduleid."' AND a.fld_schedule_type='".$typeids."'
                                                                                                            AND a.fld_test_id='".$testid."' AND b.fld_delstatus = '0'  AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                    if(trim($correctcountfortestattend) != '')
                                                    {
                                                        $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                            WHERE b.fld_expt = '".$assid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                            AND a.fld_schedule_id='".$scheduleid."' AND a.fld_schedule_type='".$typeids."'
                                                                                                            AND a.fld_test_id='".$testid."' AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                        $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                                        $possiblepointfortest+=$possiblepointfortest1;
                                                    }
                                                }
                                                else
                                                {
                                                    $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                                    WHEN fld_lock = '0' THEN fld_points_earned
                                                                                                    WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                            END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."'
                                                                                                    AND fld_exp_id='".$assid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                                    AND fld_schedule_type='".$typeids."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");
                                                    if($tchpointearn!='')
                                                    {
                                                        $possiblepointfortest+=$possiblepointfortest1;
                                                        $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    /*********Pre Test Code End Here*********/

                                    /*********Post Test Code start Here*********/
                                    if($posttest!='0')
                                    {
                                        $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                                        FROM itc_test_master WHERE fld_id='".$posttest."' AND fld_delstatus='0'");

                                        if($qry->num_rows>0)
                                        {
                                            while($rowqry = $qry->fetch_assoc())
                                            {
                                                extract($rowqry);

                                                if($questype==2)
                                                {
                                                    $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                                }

                                                $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                                $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."' 
                                                                                                    AND fld_exp_id='".$assid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                                    AND fld_schedule_type='".$typeids."' AND fld_grade='1' AND fld_res_id='".$testid."' 
                                                                                                    AND fld_exptype='3'");

                                                if(trim($tchpointcnt)=='')
                                                {
                                                    $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                        WHERE b.fld_expt = '".$assid."' AND a.fld_student_id = '".$studentid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                                                                        AND a.fld_schedule_type='".$typeids."' AND a.fld_test_id='".$testid."' AND b.fld_delstatus = '0' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                    if(trim($correctcountfortestattend) != '')
                                                    {
                                                        $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                                LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                                WHERE b.fld_expt = '".$assid."' AND a.fld_student_id = '".$studentid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                                                                                AND a.fld_schedule_type='".$typeids."' AND a.fld_test_id='".$testid."' AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                        $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                                        $possiblepointfortest+=$possiblepointfortest1;
                                                    }
                                                }
                                                else
                                                {
                                                    $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                                    WHEN fld_lock = '0' THEN fld_points_earned
                                                                                                    WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                            END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."'
                                                                                                    AND fld_exp_id='".$assid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                                    AND fld_schedule_type='".$typeids."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");
                                                    if($tchpointearn!='')
                                                    {
                                                        $possiblepointfortest+=$possiblepointfortest1;
                                                        $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                                    }

                                                }
                                            }
                                        }
                                    }
                                }
                            }

                          /************** Pre/Post test code end here ***************/   

                          /************** Rubric code start here ***************/
                        $pointsearnedrubric=0;
                        $pointspossiblerubric=0;
                          $schoolid=$ObjDB->SelectSingleValueInt("SELECT fld_school_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 

                          $sendistid=$ObjDB->SelectSingleValueInt("SELECT fld_district_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 

                          $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                                CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                              LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                              LEFT JOIN itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                                              LEFT JOIN itc_class_indasexpedition_master AS d ON a.fld_schedule_id=d.fld_id 
                                                                  WHERE a.fld_class_id='".$id[2]."'  AND a.fld_schedule_id='".$scheduleid."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                                  AND a.fld_schedule_type='15' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                                  AND b.fld_school_id IN(0,".$schoolid.")");

                          if($qryrub->num_rows>0)
                          {
                                  while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
                                  {
                                          extract($rowqryrub);

                                          $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master 
                                                                                            WHERE fld_exp_id='".$assid."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                                          $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_rpt WHERE fld_exp_id='".$assid."'  
                                                                                            AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$id[2]."' 
                                                                                            AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' "); 

                                          $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_rpt_statement
                                                                                                        WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$assid."' AND fld_delstatus='0'
                                                                                                        AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                                          $pointsearnedrubric = $pointsearnedrubric+$studentscore;
                                          if($studentscore!=0)
                                          {
                                                  $pointspossiblerubric = $pointspossiblerubric+$totscore;
                                          }
                                  }
                          }
                          /************** Rubric code end here ***************/
                          
                          
                        $pointsearned=round($pointsearnedfortest + $pointsearnedrubric,2);
                        $pointspossible=$possiblepointfortest + $pointspossiblerubric;
                        $exptearned = $exptearned + $pointsearned;
                        $exptpossible = $exptpossible + $pointspossible;
                         

                    }
              }
              else
              {
                    $exptearned = '';
                    $exptpossible = '';
              }

              $qryexpsch= $ObjDB->QueryObject("SELECT 
                                      d.fld_id AS scheduleid,
                                      b.fld_expedition_id AS expid,
                                      IFNULL((SELECT 
                                                      CASE
                                                              WHEN fld_lock = '0' THEN fld_points_earned
                                                              WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                          END AS pointsearned
                                                  FROM
                                                      itc_exp_points_master
                                                  WHERE
                                                      fld_student_id = '".$studentid."'
                                                          AND fld_exp_id = b.fld_expedition_id
                                                          AND fld_schedule_id = d.fld_id
                                                          AND fld_schedule_type = '19'
                                                          AND fld_exptype='2'
                                                          AND (fld_points_earned <> ''
                                                          OR fld_teacher_points_earned <> '')),
                                              '-') AS pearned
                                  FROM
                                      itc_class_rotation_expschedulegriddet AS b
                                          LEFT JOIN
                                      itc_class_rotation_expscheduledate AS c ON b.fld_schedule_id = c.fld_schedule_id
                                          AND b.fld_rotation = c.fld_rotation
                                          LEFT JOIN
                                      itc_class_rotation_expschedule_mastertemp AS d ON c.fld_schedule_id = d.fld_id
                                      WHERE
                                      b.fld_student_id = '".$studentid."'
                                          AND b.fld_class_id = '".$id[2]."'
                                          AND b.fld_flag = '1'
                                          AND c.fld_flag = '1'
                                          AND d.fld_delstatus = '0' ".$sqry2."");

              if($qryexpsch->num_rows>0)
              {
                    $schexptearned = '';
                    $schexptpossible = '';
                    $pointsearnedfortest=0;
                    $possiblepointfortest1=0;
                    $possiblepointfortest=0;
                    $pointsearnedrubric=0;
                    $pointspossiblerubric=0;
                    while($rowqryexpsch = $qryexpsch->fetch_assoc())
                    {
                          extract($rowqryexpsch);

                          $pointspossible=$ObjDB->SelectSingleValueInt("select fld_pointspossible from itc_class_exp_grade where fld_exp_id='".$expid."' AND fld_flag='1' AND fld_exptype='2'");

                          if($pointspossible=='0' OR $pointspossible=='')
                          {
                             $pointspossible=100; 
                          }

                          /*************EXP pre test or POST test Code Start Here**************/
                          

                          $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                                          WHERE fld_class_id='".$id[2]."' AND fld_sch_id='".$scheduleid."' AND fld_texpid='".$expid."' AND fld_schtype_id='19'");

                          if($qrytest->num_rows>0)
                          {
                              while($rowqrytest = $qrytest->fetch_assoc())
                              {
                                  extract($rowqrytest);
                                  $exptype='3';

                                  /*********Pre Test Code start Here*********/
                                  if($pretest!='0')
                                  {
                                      $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_question_type AS questype
                                                                          FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_delstatus='0'");
                                      if($qry->num_rows>0)
                                      {
                                          while($rowqry = $qry->fetch_assoc())
                                          {
                                                extract($rowqry);
                                                if($questype==2)
                                                {
                                                    $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                                }
                                                $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                                $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$expid."' AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='19' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                                if(trim($tchpointcnt)=='')
                                                {
                                                    $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                                LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                                WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$studentid."' AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_schedule_type='19'
                                                                                                                AND b.fld_delstatus = '0' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                    if(trim($correctcountfortestattend) != '')
                                                    {
                                                        $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                                LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                                WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$studentid."' AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_schedule_type='19'
                                                                                                                AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                        $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                                        $possiblepointfortest+=$possiblepointfortest1;
                                                    }
                                                }
                                                else
                                                {
                                                    $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                                    WHEN fld_lock = '0' THEN fld_points_earned
                                                                                                    WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                            END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."'
                                                                                                    AND fld_exp_id='".$expid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                                    AND fld_schedule_type='19' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");
                                                    if($tchpointearn!='')
                                                    {
                                                        $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                                        $possiblepointfortest+=$possiblepointfortest1;
                                                    }

                                                }
                                          }
                                      }
                                  }
                                   /*********Pre Test Code End Here*********/

                                  /*********Post Test Code start Here*********/
                                  if($posttest!='0')
                                  {
                                      $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_question_type AS questype
                                                                          FROM itc_test_master WHERE fld_id='".$posttest."' AND fld_delstatus='0'");
                                      if($qry->num_rows>0)
                                      {
                                          while($rowqry = $qry->fetch_assoc())
                                          {
                                              extract($rowqry);

                                                if($questype==2)
                                                {
                                                    $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                                }
                                                $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                                $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$expid."' AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='19' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                                if(trim($tchpointcnt)=='')
                                                {
                                                    $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                                LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                                WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$studentid."' AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_schedule_type='19'
                                                                                                                AND b.fld_delstatus = '0' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                    if(trim($correctcountfortestattend) != '')
                                                    {
                                                        $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                                LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                                WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$studentid."' AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_schedule_type='19'
                                                                                                                AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                        $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                                        $possiblepointfortest+=$possiblepointfortest1;
                                                    }
                                                }
                                                else
                                                {
                                                    $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                                    WHEN fld_lock = '0' THEN fld_points_earned
                                                                                                    WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                            END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."'
                                                                                                    AND fld_exp_id='".$expid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                                    AND fld_schedule_type='19' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");
                                                    if($tchpointearn!='')
                                                    {
                                                        $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                                        $possiblepointfortest+=$possiblepointfortest1;
                                                    }

                                                }
                                          }
                                      }
                                  }
                              }
                          }
                          /************** Pre/Post test code end here ***************/ 


                      /************** Rubric code start here ***************/
                       $schoolid=$ObjDB->SelectSingleValueInt("SELECT fld_school_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 

                      $sendistid=$ObjDB->SelectSingleValueInt("SELECT fld_district_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 

                      $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                            CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                            LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                            LEFT JOIN itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                                            LEFT JOIN itc_class_rotation_expschedule_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
                                                                    WHERE a.fld_class_id='".$id[2]."'  AND a.fld_schedule_id='".$scheduleid."' AND b.fld_exp_id='".$expid."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                                            AND a.fld_schedule_type='17' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                                            AND b.fld_school_id IN(0,".$schoolid.")");

                      if($qryrub->num_rows>0)
                      {
                              while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
                              {
                                      extract($rowqryrub);

                                      $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master 
                                                                                        WHERE fld_exp_id='".$expid."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                                      $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_expsch_rubric_rpt WHERE fld_exp_id='".$expid."'  
                                                                                        AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$id[2]."' 
                                                                                        AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' "); 

                                      $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_expsch_rubric_rpt_statement
                                                                                        WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$expid."' AND fld_delstatus='0'
                                                                                        AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                                      $pointsearnedrubric = $pointsearnedrubric+$studentscore;
                                      if($studentscore!=0)
                                      {
                                              $pointspossiblerubric = $pointspossiblerubric+$totscore;
                                      }
                              }
                      }

                      /************** Rubric code end here ***************/

                    }
                    $pointsearned=round($pointsearnedfortest + $pointsearnedrubric,2);
                    $pointspossible=$possiblepointfortest + $pointspossiblerubric;
                    $schexptearned = $schexptearned + $pointsearned;
                    $schexptpossible = $schexptpossible + $pointspossible;
              }
              else
              {
                      $schexptearned = '';
                      $schexptpossible = '';
              }


              /*********Expedition and Module Schedule Developed by Mohan M 22-3-2016*************/                                                
              $qryexpormod= $ObjDB->QueryObject("SELECT 
                                      d.fld_id AS scheduleid,
                                      b.fld_module_id AS expid,
                                      IFNULL((SELECT 
                                                      CASE
                                                              WHEN fld_lock = '0' THEN fld_points_earned
                                                              WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                          END AS pointsearned
                                                  FROM
                                                      itc_exp_points_master
                                                  WHERE
                                                      fld_student_id = '".$studentid."'
                                                          AND fld_exp_id = b.fld_module_id
                                                          AND fld_schedule_id = d.fld_id
                                                          AND fld_schedule_type = '20'
                                                          AND fld_exptype='2'
                                                          AND (fld_points_earned <> ''
                                                          OR fld_teacher_points_earned <> '')),
                                              '-') AS pearned
                                  FROM
                                      itc_class_rotation_modexpschedulegriddet AS b
                                          LEFT JOIN
                                      itc_class_rotation_modexpscheduledate AS c ON b.fld_schedule_id = c.fld_schedule_id
                                          AND b.fld_rotation = c.fld_rotation
                                          LEFT JOIN
                                      itc_class_rotation_modexpschedule_mastertemp AS d ON c.fld_schedule_id = d.fld_id
                                      WHERE
                                      b.fld_student_id = '".$studentid."' AND b.fld_type='2'
                                          AND b.fld_class_id = '".$id[2]."'
                                          AND b.fld_flag = '1'
                                          AND c.fld_flag = '1'
                                          AND d.fld_delstatus = '0' ".$sqry2."");

              if($qryexpormod->num_rows>0)
              {
                      $expmodschexptearned = '';
                      $expmodschexptpossible = ''; 
                      while($rowqryexpormod = $qryexpormod->fetch_assoc())
                      {
                              extract($rowqryexpormod);

                              $pointspossible=$ObjDB->SelectSingleValueInt("select fld_pointspossible from itc_class_exp_grade where fld_exp_id='".$expid."' AND fld_flag='1' AND fld_exptype='2'");

                              if($pointspossible=='0' OR $pointspossible=='')
                              {
                                 $pointspossible=100; 
                              }

                              /*************EXP pre test or POST test Code Start Here**************/
                              
                              $pointsearnedtest=0;
                              $pointsearnedfortest=0;
                              $possiblepointfortest1=0;
                              $possiblepointfortest=0;
                              $pointsearnedrubric=0;
                              $pointspossiblerubric=0;
                             
                            
                              $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                                                    WHERE fld_class_id='".$id[2]."' AND fld_sch_id='".$scheduleid."' AND fld_texpid='".$expid."' AND fld_schtype_id='20'");

                                if($qrytest->num_rows>0)
                                {
                                    while($rowqrytest = $qrytest->fetch_assoc())
                                    {
                                        extract($rowqrytest);
                                        $exptype='3';

                                        /*********Pre Test Code start Here*********/
                                        if($pretest!='0')
                                        {
                                            $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                                                FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_delstatus='0'");
                                            if($qry->num_rows>0)
                                            {
                                                while($rowqry = $qry->fetch_assoc())
                                                {
                                                    extract($rowqry);

                                                    if($questype==2)
                                                    {
                                                        $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                                    }
                                                    $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                                    $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."' 
                                                                                                        AND fld_exp_id='".$expid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                                        AND fld_schedule_type='20' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");
                                                    if(trim($tchpointcnt)=='')
                                                    {
                                                        $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                        WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                        AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                                                                        AND a.fld_schedule_type='20' AND b.fld_delstatus = '0' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                         if(trim($correctcountfortestattend) != '')
                                                        {
                                                            $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                                LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                                WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                                AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                                                                                AND a.fld_schedule_type='20' AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                            $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                                            $possiblepointfortest+=$possiblepointfortest1;
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                                            WHEN fld_lock = '0' THEN fld_points_earned
                                                                                                            WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                                    END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."'
                                                                                                            AND fld_exp_id='".$expid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                                            AND fld_schedule_type='20' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");


                                                        if($tchpointearn!='')
                                                        {
                                                            $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                                            $possiblepointfortest+=$possiblepointfortest1;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        /*********Pre Test Code End Here*********/

                                        /*********Post Test Code start Here*********/
                                        if($posttest!='0')
                                        {
                                            $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                                                FROM itc_test_master WHERE fld_id='".$posttest."' AND fld_delstatus='0'");
                                            if($qry->num_rows>0)
                                            {
                                                while($rowqry = $qry->fetch_assoc())
                                                {
                                                    extract($rowqry);

                                                    if($questype==2)
                                                    {
                                                        $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                                    }
                                                    $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                                    $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."' 
                                                                                                        AND fld_exp_id='".$expid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                                        AND fld_schedule_type='20' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");
                                                    if(trim($tchpointcnt)=='')
                                                    {
                                                        $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                        WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                        AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                                                                        AND a.fld_schedule_type='20' AND b.fld_delstatus = '0' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                         if(trim($correctcountfortestattend) != '')
                                                        {
                                                            $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                                LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                                WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                                AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                                                                                AND a.fld_schedule_type='20' AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                            $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                                            $possiblepointfortest+=$possiblepointfortest1;
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                                            WHEN fld_lock = '0' THEN fld_points_earned
                                                                                                            WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                                    END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."'
                                                                                                            AND fld_exp_id='".$expid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                                            AND fld_schedule_type='20' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");


                                                        if($tchpointearn!='')
                                                        {
                                                            $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                                            $possiblepointfortest+=$possiblepointfortest1;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                              
                              /*************EXP pre test or POST test Code End Here****************/ 

                              /************** Rubric code start here ***************/
                              $schoolid=$ObjDB->SelectSingleValueInt("SELECT fld_school_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 

                              $sendistid=$ObjDB->SelectSingleValueInt("SELECT fld_district_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 

                              $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                                CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                                LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                                LEFT JOIN itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                                                LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
                                                                WHERE a.fld_class_id='".$id[2]."'  AND a.fld_schedule_id='".$scheduleid."' AND b.fld_exp_id='".$expid."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                                        AND a.fld_schedule_type='20' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                                        AND b.fld_school_id IN(0,".$schoolid.")");

                              if($qryrub->num_rows>0)
                              {
                                              while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
                                              {
                                                    extract($rowqryrub);

                                                    $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master 
                                                                                              WHERE fld_exp_id='".$expid."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                                                    $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_expmodsch_rubric_rpt WHERE fld_exp_id='".$expid."'  
                                                                                                      AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$id[2]."' 
                                                                                                      AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' "); 

                                                    $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_expmodsch_rubric_rpt_statement
                                                                                                  WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$expid."' AND fld_delstatus='0'
                                                                                                  AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                                                    $pointsearnedrubric = $pointsearnedrubric+$studentscore;
                                                    if($studentscore!=0)
                                                    {
                                                        $pointspossiblerubric = $pointspossiblerubric+$totscore;
                                                    }
                                              }
                              }
                             
                            $pointsearned=round($pointsearnedfortest + $pointsearnedrubric,2);
                            $pointspossible=$possiblepointfortest + $pointspossiblerubric;
                            $expmodschexptearned = $expmodschexptearned + $pointsearned;
                            $expmodschexptpossible = $expmodschexptpossible + $pointspossible;
                      }

              }
              else
              {
                      $expmodschexptearned = '';
                      $expmodschexptpossible = '';
              }

              /*********Expedition and Module Schedule Developed by Mohan M 22-3-2016*************/       


              /************WCA Mission Developed by Mohan M 24-5-2016*************/
              $qrymis = $ObjDB->QueryObject("SELECT a.fld_id AS scheduleid, a.fld_mis_id AS misid, '0' AS maxids, 
                                                fn_shortname(CONCAT(b.fld_mis_name,' / Mission'),1) AS nam, 
                                                CONCAT(b.fld_mis_name,' / Mission') AS fullnam, 18 AS typeids, a.fld_schedule_name AS schname, a.fld_startdate AS startdate, a.fld_enddate AS enddate 
                                                FROM itc_class_indasmission_master AS a 
                                                LEFT JOIN itc_mission_master AS b ON a.fld_mis_id=b.fld_id
                                                WHERE a.fld_class_id='".$id[2]."' AND a.fld_flag='1' AND a.fld_delstatus='0' 
                                                AND b.fld_delstatus='0' ".$sqry4." GROUP BY a.fld_id");
              if($qrymis->num_rows>0)
              {
                  $mistearned = '';
                  $mistpossible = '';
                  while($rowqrymis = $qrymis->fetch_assoc())
                  {
                      extract($rowqrymis);

                      $gradepointsearned = $ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned AS pointsearned FROM itc_mis_points_master 
                                                                WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$misid."' 
                                                                    AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='18' AND fld_mistype='4'
                                                                    AND fld_grade='1' AND fld_delstatus='0'");

                        $gradepointspossible = $ObjDB->SelectSingleValueInt("SELECT fld_points_possible AS pointspossible FROM itc_mis_points_master 
                                                                                WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$misid."' 
                                                                                    AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='18' AND fld_mistype='4'
                                                                                    AND fld_grade='1' AND fld_delstatus='0'");

                        /************** Rubric code start here ***************/
                        $pointsearnedrubric=0;
                        $pointspossiblerubric=0;
                        
                        $schoolid=$ObjDB->SelectSingleValueInt("SELECT fld_school_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 

                        $sendistid=$ObjDB->SelectSingleValueInt("SELECT fld_district_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 
                        
                        $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                        CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                        LEFT JOIN itc_mis_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                        LEFT JOIN itc_mission_master AS c ON a.fld_expmisid = c.fld_id
                                                        LEFT JOIN itc_class_indasmission_master AS d ON a.fld_schedule_id=d.fld_id 
                                                                WHERE a.fld_class_id='".$id[2]."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                                        AND a.fld_schedule_type='18' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                                        AND b.fld_school_id IN(0,".$schoolid.")");

                        if($qryrub->num_rows>0)
                        {
                            while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
                            {
                                extract($rowqryrub);

                                $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_master 
                                                                            WHERE fld_mis_id='".$misid."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                                $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_rubric_rpt WHERE fld_mis_id='".$misid."'  
                                                                                    AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$id[2]."' 
                                                                                    AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' "); 

                                $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_rpt_statement
                                                                                    WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$misid."' AND fld_delstatus='0'
                                                                                    AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                                $pointsearnedrubric = $pointsearnedrubric+$studentscore;
                                if($studentscore!=0)
                                {
                                    $pointspossiblerubric = $pointspossiblerubric+$totscore;
                                }
                            }
                        }
                        /************** Rubric code end here ***************/
                        
                        /************* Test Code Start Here**************/
                        $pointsearnedfortest=0;
                        $possiblepointfortest1=0;
                        $possiblepointfortest=0;

                        $qrytestmis = $ObjDB->QueryObject("SELECT fld_test_id AS pretest,fld_mis_id AS misid FROM itc_mis_ass
                                                            WHERE fld_class_id='".$id[2]."' AND fld_sch_id='".$scheduleid."' AND fld_schtype_id='18' AND fld_flag='1'");

                        if($qrytestmis->num_rows>0)
                        {
                            while($rowqrytestmis = $qrytestmis->fetch_assoc())
                            {
                                extract($rowqrytestmis);
                                $exptype='3';

                                $qrytes = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                                                        FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_delstatus='0'");
                                if($qrytes->num_rows>0)
                                {
                                    while($rowqrytes = $qrytes->fetch_assoc())
                                    {
                                        extract($rowqrytes);

                                        if($questype==2)
                                        {
                                            $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                        }
                                        $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master 
                                                                                                                where fld_id='".$testid."' and fld_delstatus='0';");

                                        $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_mis_points_master 
                                                                                            WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$misid."' 
                                                                                            AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='18' 
                                                                                            AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_mistype='3'");

                                        if(trim($tchpointcnt)=='')
                                        {
                                            $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                        WHERE b.fld_mist = '".$misid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                        AND a.fld_test_id='".$testid."'  AND a.fld_schedule_id='".$scheduleid."' 
                                                                                                        AND a.fld_schedule_type='18'  AND b.fld_delstatus = '0' 
                                                                                        AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                            if(trim($correctcountfortestattend) != '')
                                            {
                                                $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                        WHERE b.fld_mist = '".$misid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                        AND a.fld_test_id='".$testid."'  AND a.fld_schedule_id='".$scheduleid."' 
                                                                                                        AND a.fld_schedule_type='18'  AND b.fld_delstatus = '0' AND a.fld_show = '1' 
                                                                                        AND a.fld_delstatus = '0' AND a.fld_retake='0'");
                                                
                                                $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                                $possiblepointfortest+=$possiblepointfortest1;
                                            }
                                        }
                                        else
                                        {
                                            $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                                WHEN fld_lock = '0' THEN fld_points_earned
                                                                                                WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                END) AS pointsearned FROM itc_mis_points_master WHERE fld_student_id='".$studentid."'
                                                                                                AND fld_mis_id='".$misid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                                AND fld_schedule_type='18' AND fld_grade='1' AND fld_res_id='".$testid."' 
                                                                                                AND fld_mistype='3'");
                                            if($tchpointearn!='')
                                            {
                                                $possiblepointfortest+=$possiblepointfortest1;
                                            }
                                            $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                        }
                                    }
                                }
                            }
                        }
                        /************** Test code end here ***************/
                        
                        $pointsearned=$gradepointsearned+$pointsearnedrubric+$pointsearnedfortest;
                        $pointspossible=$gradepointspossible+$pointspossiblerubric+$possiblepointfortest;

                        if($pointsearned=='-')
                        {
                            $misearned = '';
                            $mispossible = '';
                        }
                        else
                        {
                            $mistearned = $pointsearned;
                            $mistpossible = $pointspossible;
                        }
                    }
                }
                else
                {
                    $mistearned = '';
                    $mistpossible = '';
                }

                $qrymissch= $ObjDB->QueryObject("SELECT d.fld_id AS scheduleid,b.fld_mission_id AS misid,23 AS typeids FROM itc_class_rotation_mission_schedulegriddet AS b
                                                       LEFT JOIN itc_class_rotation_missionscheduledate AS c ON b.fld_schedule_id = c.fld_schedule_id AND b.fld_rotation = c.fld_rotation
                                                       LEFT JOIN itc_class_rotation_mission_mastertemp AS d ON c.fld_schedule_id = d.fld_id
                                                       WHERE b.fld_student_id = '".$studentid."' AND b.fld_class_id = '".$id[2]."' AND b.fld_flag = '1'
                                                               AND d.fld_delstatus = '0' ".$sqry2."");
                if($qrymissch->num_rows>0)
                {
                    $misschtearned = '';
                    $misschtpossible = '';
                    while($rowqrymis = $qrymissch->fetch_assoc())
                    {
                        extract($rowqrymis);

                        $pointearned1=0;
                        $pointpossible1=0;
                        $expstudentcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                                FROM itc_class_rotation_mission_student_mappingtemp
                                                                                WHERE fld_schedule_id='".$scheduleid."' 
                                                                                        AND fld_student_id='".$studentid."' 
                                                                                        AND fld_flag='1'");
                        if($expstudentcount!=0)
                        {
                            $rotid=$z+1;                             
                            $pointearned1 = $ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned AS pointsearned FROM itc_mis_points_master 
                                                                            WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$misid."' 
                                                                                AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$typeids."' 
                                                                                AND fld_grade='1' AND fld_mistype='4'");

                            $pointpossible1 = $ObjDB->SelectSingleValueInt("SELECT fld_points_possible AS pointsearned FROM itc_mis_points_master 
                                                                            WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$misid."' 
                                                                                AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$typeids."' 
                                                                                AND fld_grade='1' AND fld_mistype='4'");


                            /************** Rubric code start here ***************/
                            $pointsearnedrubric=0;
                            $pointspossiblerubric=0;

                            $schoolid=$ObjDB->SelectSingleValueInt("SELECT fld_school_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 

                            $sendistid=$ObjDB->SelectSingleValueInt("SELECT fld_district_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 
                            $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                                CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                                        LEFT JOIN itc_mis_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                                        LEFT JOIN itc_mission_master AS c ON a.fld_expmisid = c.fld_id
                                                                        LEFT JOIN itc_class_rotation_mission_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
                                                                            WHERE a.fld_class_id='".$id[2]."' AND a.fld_schedule_id='".$scheduleid."' AND b.fld_mis_id='".$misid."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                                                    AND a.fld_schedule_type='19' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                                                    AND b.fld_school_id IN(0,".$schoolid.")");

                            if($qryrub->num_rows>0)
                            {
                                $totscore=0;
                                while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
                                {
                                    extract($rowqryrub);

                                   $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_master 
                                                                                        WHERE fld_mis_id='".$misid."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    


                                    $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_missch_rubric_rpt WHERE fld_mis_id='".$misid."'  
                                                                                        AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$id[2]."' 
                                                                                        AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' "); 

                                    $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_missch_rubric_rpt_statement
                                                                                        WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$misid."' AND fld_delstatus='0'
                                                                                        AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                                    $pointsearnedrubric = $pointsearnedrubric+$studentscore;
                                    if($studentscore!=0)
                                    {
                                        $pointspossiblerubric = $pointspossiblerubric+$totscore;
                                    }
                                }
                            }

                            /************** Rubric code end here ***************/

                          /************* Test Code Start Here**************/
                            $pointsearnedfortest=0;
                            $possiblepointfortest1=0;
                            $possiblepointfortest=0;

                            $qrytestmis = $ObjDB->QueryObject("SELECT fld_test_id AS pretest,fld_mis_id AS misid FROM itc_mis_ass
                                                                WHERE fld_class_id='".$id[2]."' AND fld_sch_id='".$scheduleid."' AND fld_mis_id='".$misid."' AND fld_schtype_id='20' AND fld_flag='1'");

                            if($qrytestmis->num_rows>0)
                            {
                                while($rowqrytestmis = $qrytestmis->fetch_assoc())
                                {
                                    extract($rowqrytestmis);
                                    $exptype='3';

                                    $qrytes = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                                                            FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_delstatus='0'");
                                    if($qrytes->num_rows>0)
                                    {
                                        while($rowqrytes = $qrytes->fetch_assoc())
                                        {
                                            extract($rowqrytes);

                                            if($questype==2)
                                            {
                                                $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                            }
                                            $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master 
                                                                                                                    where fld_id='".$testid."' and fld_delstatus='0';");

                                            $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_mis_points_master 
                                                                                                WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$misid."' 
                                                                                                AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='20' 
                                                                                                AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_mistype='3'");

                                            if(trim($tchpointcnt)=='')
                                            {
                                                $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                            WHERE b.fld_mist = '".$misid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                            AND a.fld_test_id='".$testid."'  AND a.fld_schedule_id='".$scheduleid."' 
                                                                                                            AND a.fld_schedule_type='20'  AND b.fld_delstatus = '0' 
                                                                                            AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                if(trim($correctcountfortestattend) != '')
                                                {
                                                    $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                            WHERE b.fld_mist = '".$misid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                            AND a.fld_test_id='".$testid."'  AND a.fld_schedule_id='".$scheduleid."' 
                                                                                                            AND a.fld_schedule_type='20'  AND b.fld_delstatus = '0' AND a.fld_show = '1' 
                                                                                            AND a.fld_delstatus = '0' AND a.fld_retake='0'");
                                                    
                                                    $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                                    $possiblepointfortest+=$possiblepointfortest1;
                                                }
                                            }
                                            else
                                            {
                                                $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                                    WHEN fld_lock = '0' THEN fld_points_earned
                                                                                                    WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                    END) AS pointsearned FROM itc_mis_points_master WHERE fld_student_id='".$studentid."'
                                                                                                    AND fld_mis_id='".$misid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                                    AND fld_schedule_type='20' AND fld_grade='1' AND fld_res_id='".$testid."' 
                                                                                                    AND fld_mistype='3'");
                                                if($tchpointearn!='')
                                                {
                                                    $possiblepointfortest+=$possiblepointfortest1;
                                                }
                                                $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                            }
                                        }
                                    }
                                }
                            }
                            /************** Test code end here ***************/


                            $pointsearned = $pointearned1 + $pointsearnedrubric  + $pointsearnedfortest;
                            $pointspossible = $pointpossible1 + $pointspossiblerubric  + $possiblepointfortest;
                            $misschtearned = $misschtearned + $pointsearned;
                            $misschtpossible = $misschtpossible + $pointspossible;
                        }
                    }
                }
                else
                {
                    $misschtearned = '';
                    $misschtpossible = '';
                }
            /************WCA Mission Developed by Mohan M 24-5-2016*************/
			
            $qrypoints = $ObjDB->QueryObject("SELECT SUM(a.pointsearned) AS earned, SUM(a.pointspossible) AS possible FROM (
                                                (SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible FROM itc_assignment_sigmath_master AS a LEFT JOIN itc_class_sigmath_master AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_id) WHERE b.fld_class_id='".$id[2]."' AND a.fld_student_id='".$studentid."' AND a.fld_test_type='1' AND b.fld_flag='1' AND b.fld_delstatus='0' AND (a.fld_status='1' OR a.fld_status='2' OR a.fld_lock='1' OR a.fld_unitmark='1') AND a.fld_delstatus='0' AND a.fld_grade<>'0' ".$sqry.") 		
                                            UNION ALL		
                                                (SELECT ROUND(SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
                                                THEN a.fld_teacher_points_earned END)/4) AS pointsearned, ROUND(SUM(a.fld_points_possible)/4) AS pointspossible 
                                                FROM `itc_assignment_sigmath_master` AS a
                                                LEFT JOIN itc_class_rotation_schedulegriddet AS b ON (a.fld_class_id=b.fld_class_id 
                                                AND a.fld_schedule_id=b.fld_schedule_id and a.fld_module_id=b.fld_module_id) 
                                                LEFT JOIN itc_class_rotation_scheduledate AS c ON b.fld_schedule_id=c.fld_schedule_id and b.fld_rotation=c.fld_rotation 
                                                WHERE b.fld_class_id = '".$id[2]."' AND a.fld_student_id = '".$studentid."' 
                                                AND b.fld_student_id='".$studentid."' AND a.fld_test_type='2' AND b.fld_flag='1' 
                                                AND a.fld_delstatus='0' and b.fld_type='2' AND (a.fld_status='1' OR a.fld_status='2' OR a.fld_lock='1') AND c.fld_flag='1' ".$sqry2."
                                                GROUP BY a.fld_schedule_id)		
                                            UNION ALL
                                                (SELECT ROUND(SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
                                                THEN a.fld_teacher_points_earned END)/4) AS pointsearned, ROUND(SUM(a.fld_points_possible)/4) AS pointspossible 
                                                FROM itc_assignment_sigmath_master AS a 
                                                LEFT JOIN itc_class_indassesment_master AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_id)
                                                WHERE b.fld_class_id='".$id[2]."' AND a.fld_student_id='".$studentid."' AND a.fld_test_type = '5' 
                                                AND b.fld_moduletype='2' AND b.fld_flag='1' AND b.fld_delstatus='0' 
                                                AND a.fld_delstatus='0' AND (a.fld_status='1' OR a.fld_status='2' OR a.fld_lock='1') ".$sqry1." GROUP BY a.fld_schedule_id)
                                            UNION ALL		
                                                (SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
                                                THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible 
                                                FROM itc_module_points_master AS a 
                                                LEFT JOIN itc_class_rotation_schedulegriddet AS b ON (a.fld_schedule_id=b.fld_schedule_id 
                                                AND a.fld_module_id=b.`fld_module_id` AND a.fld_student_id=b.fld_student_id) 
                                                LEFT JOIN itc_class_rotation_scheduledate AS c ON b.fld_schedule_id=c.fld_schedule_id 
                                                and b.fld_rotation=c.fld_rotation 
                                                WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$id[2]."' AND a.fld_delstatus='0' AND (fld_points_earned <> ''
                                                OR fld_teacher_points_earned <> '') 
                                                AND b.fld_flag='1' AND a.fld_grade<>'0' AND a.fld_schedule_type IN (1,4,8) AND c.fld_flag='1' ".$sqry2.") 		
                                            UNION ALL		
                                                (SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
                                                THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible 
                                                FROM itc_module_points_master AS a 
                                                LEFT JOIN `itc_class_dyad_schedulegriddet` AS b ON (a.fld_schedule_id=b.fld_schedule_id 
                                                AND a.fld_module_id=b.`fld_module_id` AND (a.fld_student_id=b.fld_student_id OR b.fld_rotation='0')) 
                                                WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$id[2]."' AND a.fld_delstatus='0' 
                                                AND b.fld_flag='1' AND a.fld_grade<>'0' AND a.fld_schedule_type='2' ".$sqry1.") 		
                                            UNION ALL		
                                                (SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
                                                THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible 
                                                FROM itc_module_points_master AS a 
                                                LEFT JOIN `itc_class_triad_schedulegriddet` AS b ON (a.fld_schedule_id=b.fld_schedule_id
                                                AND a.fld_module_id=b.`fld_module_id` AND (a.fld_student_id=b.fld_student_id OR b.fld_rotation='0')) 
                                                WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$id[2]."' AND a.fld_delstatus='0' 
                                                AND b.fld_flag='1' AND a.fld_grade<>'0' AND a.fld_schedule_type='3' ".$sqry1.") 		
                                            UNION ALL 		
                                               (SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
                                                THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible 
                                                FROM itc_module_points_master AS a 
                                                LEFT JOIN itc_class_indassesment_master AS b ON (a.fld_schedule_id=b.fld_id 
                                                AND a.fld_module_id=b.fld_module_id) 
                                                LEFT JOIN itc_class_indassesment_student_mapping AS c ON (a.fld_schedule_id=c.fld_schedule_id 
                                                AND a.fld_student_id=c.fld_student_id) 
                                                WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$id[2]."' AND a.fld_delstatus='0' 
                                                AND b.fld_flag='1' AND a.fld_grade<>'0' AND a.fld_schedule_type IN (5,6,7,17) AND b.fld_delstatus='0' AND c.fld_flag='1' ".$sqry1.") 	
                                            UNION ALL		
                                                (SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
                                                THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible 
                                                FROM itc_module_points_master AS a 
                                                LEFT JOIN itc_class_rotation_modexpschedulegriddet AS b ON (a.fld_schedule_id=b.fld_schedule_id AND a.fld_module_id=b.`fld_module_id` AND a.fld_student_id=b.fld_student_id) 
                                                LEFT JOIN itc_class_rotation_modexpscheduledate AS c ON b.fld_schedule_id=c.fld_schedule_id and b.fld_rotation=c.fld_rotation 
                                                WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$id[2]."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_grade<>'0' AND a.fld_schedule_type IN (21,22) AND c.fld_flag='1' ".$sqry2.") 	

                                            ) 
                                            AS a ");
            $rowqrypoints = $qrypoints->fetch_assoc();
            extract($rowqrypoints);

            $pointsearned = $earned+$exptearned+$schexptearned+$expmodschexptearned+$mistearned+$misschtearned;
            $pointspossible = $possible+$exptpossible+$schexptpossible+$expmodschexptpossible+$mistpossible+$misschtpossible;

            if($pointsearned!='')
            {
                $pointsearned = round($pointsearned);
                if($roundflag==0)
                                $percentage = round(($pointsearned/$pointspossible)*100,2);
                else
                                $percentage = round(($pointsearned/$pointspossible)*100);

                $perarray = explode('.',$percentage);
                $grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
                                                        FROM itc_class_grading_scale_mapping 
                                                        WHERE fld_class_id = '".$id[2]."' AND fld_lower_bound <= '".$perarray[0]."' 
                                                            AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
            }
            else
            {
                $pointsearned = " - ";
                $pointspossible = " - ";
                $percentage = " - ";
                $grade = "N/A";
            }
					
            $out .= "Student :".$studentname." , , , , ".$grade;
            $out .="\n";
            $out .= " , , , , ".$percentage." % (".$pointsearned." / ".$pointspossible.")";
            $out .="\n\n";

            $out .= "Assignment Name , Points Earned , Points Possible , Percentage , Grade , ";
            $out .="\n";


            $qry = $ObjDB->QueryObject("SELECT w.* FROM (
                                        (SELECT a.fld_unit_id AS ids, a.fld_schedule_id AS schid, c.fld_unit_name AS assignname, 0 AS typename, 0 AS rotation
                                        FROM itc_assignment_sigmath_master AS a 
                                        LEFT JOIN itc_class_sigmath_master AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_id) 
                                        LEFT JOIN itc_unit_master AS c ON a.fld_unit_id=c.fld_id 
                                        WHERE a.fld_class_id='".$id[2]."' AND a.fld_student_id='".$studentid."' AND a.fld_test_type='1' 
                                            AND c.fld_delstatus='0' AND b.fld_flag='1' AND b.fld_delstatus='0' ".$sqry." GROUP BY ids)
                                    UNION ALL	
                                        (SELECT a.fld_module_id AS ids, a.fld_schedule_id AS schid, CONCAT(d.fld_module_name,' ',e.fld_version,
                                            ' Rotation ',a.fld_rotation - 1) AS assignname, 1 AS typename, a.fld_rotation AS rotation 
                                        FROM itc_class_rotation_schedulegriddet AS a 
                                        LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
                                        LEFT JOIN itc_class_rotation_scheduledate AS c ON a.fld_schedule_id=c.fld_schedule_id and a.fld_rotation=c.fld_rotation 
                                        LEFT JOIN itc_module_master AS d ON a.fld_module_id=d.fld_id
                                        LEFT JOIN itc_module_version_track AS e ON d.fld_id=e.fld_mod_id 
                                        WHERE a.fld_student_id='".$studentid."' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' 
                                            AND b.fld_moduletype='1'  AND b.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND c.fld_flag='1' ".$sqry2."
                                        GROUP BY ids)
                                    UNION ALL
                                        (SELECT a.fld_expedition_id AS ids, a.fld_schedule_id AS schid, CONCAT(d.fld_exp_name,' ',e.fld_version,
                                            ' Rotation ',a.fld_rotation - 1) AS assignname, 19 AS typename, a.fld_rotation AS rotation 
                                        FROM itc_class_rotation_expschedulegriddet AS a 
                                        LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
                                        LEFT JOIN itc_class_rotation_expscheduledate AS c ON a.fld_schedule_id=c.fld_schedule_id and a.fld_rotation=c.fld_rotation 
                                        LEFT JOIN itc_exp_master AS d ON a.fld_expedition_id=d.fld_id
                                        LEFT JOIN itc_exp_version_track AS e ON d.fld_id=e.fld_exp_id 
                                        WHERE a.fld_student_id='".$studentid."' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' 
                                        AND b.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND c.fld_flag='1' ".$sqry2."
                                        GROUP BY ids)
                                    UNION ALL	
                                        (SELECT a.fld_module_id AS ids, a.fld_schedule_id AS schid, CONCAT(b.fld_module_name,' ',c.fld_version,' 
                                            Dyad Rotation ',(CASE WHEN fld_rotation='0' THEN '' WHEN fld_rotation<>'0' THEN fld_rotation END)) AS assignname, 2 AS typename, fld_rotation AS rotation 
                                        FROM itc_class_dyad_schedulegriddet AS a 
                                        LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
                                        LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
                                        LEFT JOIN itc_class_dyad_schedule_studentmapping AS d ON (d.fld_student_id='".$studentid."' AND d.fld_schedule_id=a.fld_schedule_id)
                                        LEFT JOIN itc_class_dyad_schedulemaster AS e ON a.fld_schedule_id = e.fld_id
                                        WHERE (a.fld_student_id='".$studentid."' OR a.fld_rotation='0') AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' 
                                            AND b.fld_delstatus='0' AND c.fld_delstatus='0' AND d.fld_flag='1' AND e.fld_delstatus='0' ".$sqry3." GROUP BY ids)
                                    UNION ALL
                                        (SELECT a.fld_module_id AS ids, a.fld_schedule_id AS schid, CONCAT(b.fld_module_name,' ',c.fld_version,' 
                                            Triad Rotation ',(CASE WHEN fld_rotation='0' THEN '' WHEN fld_rotation<>'0' THEN fld_rotation END)) AS assignname, 3 AS typename, fld_rotation AS rotation 
                                        FROM itc_class_triad_schedulegriddet AS a 
                                        LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
                                        LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
                                        LEFT JOIN itc_class_triad_schedule_studentmapping AS d ON (d.fld_student_id='".$studentid."' AND d.fld_schedule_id=a.fld_schedule_id)
                                        LEFT JOIN itc_class_triad_schedulemaster AS e ON a.fld_schedule_id = e.fld_id
                                        WHERE (a.fld_student_id='".$studentid."' OR a.fld_rotation='0') AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' 
                                            AND b.fld_delstatus='0' AND c.fld_delstatus='0' AND d.fld_flag='1' AND e.fld_delstatus='0' ".$sqry3." GROUP BY ids)
                                    UNION ALL
                                        (SELECT a.fld_module_id AS ids, a.fld_schedule_id AS schid, CONCAT(d.fld_mathmodule_name,' ',e.fld_version,
                                            ' Rotation ',a.fld_rotation - 1) AS assignname, 4 AS typename, a.fld_rotation AS rotation 
                                        FROM itc_class_rotation_schedulegriddet AS a 
                                        LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
                                        LEFT JOIN itc_class_rotation_scheduledate AS c ON a.fld_schedule_id=c.fld_schedule_id and a.fld_rotation=c.fld_rotation 
                                        LEFT JOIN itc_mathmodule_master AS d ON a.fld_module_id=d.fld_id
                                        LEFT JOIN itc_module_version_track AS e ON d.fld_module_id=e.fld_mod_id 
                                        WHERE a.fld_student_id='".$studentid."' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' 
                                            AND b.fld_moduletype='2'  AND b.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND c.fld_flag='1' ".$sqry2."
                                        GROUP BY ids)
                                    UNION ALL
                                        (SELECT a.fld_module_id AS ids, a.fld_id AS schid, CONCAT(c.fld_module_name,' ',d.fld_version,' Ind Module') 
                                            AS assignname, 5 AS typename, 0 AS rotation 
                                        FROM itc_class_indassesment_master AS a 
                                        LEFT JOIN itc_class_indassesment_student_mapping AS b ON b.fld_schedule_id=a.fld_id 
                                        LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id 
                                        LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id
                                        WHERE b.fld_student_id='".$studentid."' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' AND d.fld_delstatus='0'
                                            AND a.fld_moduletype='1' AND b.fld_flag='1' AND a.fld_delstatus='0' AND c.fld_delstatus='0' ".$sqry3." GROUP BY ids) 
                                    UNION ALL
                                        (SELECT a.fld_module_id AS ids, a.fld_id AS schid, CONCAT(c.fld_mathmodule_name,' ',d.fld_version,' Ind MM') 
                                            AS assignname, 6 AS typename, 0 AS rotation 
                                        FROM itc_class_indassesment_master AS a 
                                        LEFT JOIN itc_class_indassesment_student_mapping AS b ON b.fld_schedule_id=a.fld_id 
                                        LEFT JOIN itc_mathmodule_master AS c ON a.fld_module_id=c.fld_id 
                                        LEFT JOIN itc_module_version_track AS d ON c.fld_module_id=d.fld_mod_id
                                        WHERE b.fld_student_id='".$studentid."' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' AND d.fld_delstatus='0' 
                                            AND a.fld_moduletype='2' AND b.fld_flag='1' AND a.fld_delstatus='0' AND c.fld_delstatus='0' ".$sqry3." GROUP BY ids)
                                    UNION ALL
                                        (SELECT a.fld_module_id AS ids, a.fld_id AS schid, CONCAT(c.fld_module_name,' ',d.fld_version,' Ind Quest') 
                                            AS assignname, 7 AS typename, 0 AS rotation 
                                        FROM itc_class_indassesment_master AS a 
                                        LEFT JOIN itc_class_indassesment_student_mapping AS b ON b.fld_schedule_id=a.fld_id 
                                        LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id 
                                        LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id
                                        WHERE b.fld_student_id='".$studentid."' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' AND d.fld_delstatus='0'
                                            AND a.fld_moduletype='7' AND b.fld_flag='1' AND a.fld_delstatus='0' AND c.fld_delstatus='0' ".$sqry3." GROUP BY ids) 
                                        UNION ALL
                                        (SELECT a.fld_module_id AS ids, a.fld_id AS schid, CONCAT(c.fld_contentname,' Ind Custom Content') 
                                            AS assignname, 17 AS typename, 0 AS rotation 
                                        FROM itc_class_indassesment_master AS a 
                                        LEFT JOIN itc_class_indassesment_student_mapping AS b ON b.fld_schedule_id=a.fld_id 
                                        LEFT JOIN itc_customcontent_master AS c ON a.fld_module_id=c.fld_id 
                                        WHERE b.fld_student_id='".$studentid."' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' 
                                            AND a.fld_moduletype='17' AND b.fld_flag='1' AND a.fld_delstatus='0' AND c.fld_delstatus='0' ".$sqry3." GROUP BY ids)              

                                    UNION ALL	
                                        (SELECT a.fld_module_id AS ids, a.fld_schedule_id AS schid, CONCAT(d.fld_contentname,' Custom Content'), 
                                            8 AS typename, 0 AS rotation 
                                        FROM itc_class_rotation_schedulegriddet AS a 
                                        LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
                                        LEFT JOIN itc_class_rotation_scheduledate AS c ON a.fld_schedule_id=c.fld_schedule_id and a.fld_rotation=c.fld_rotation 
                                        LEFT JOIN itc_customcontent_master AS d ON a.fld_module_id=d.fld_id
                                        WHERE a.fld_student_id='".$studentid."' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' AND a.fld_type='8'
                                            AND b.fld_moduletype='1'  AND b.fld_delstatus='0' AND d.fld_delstatus='0' AND c.fld_flag='1' ".$sqry2." GROUP BY ids)
                                    UNION ALL	
                                        (SELECT a.fld_exp_id AS ids, a.fld_id AS scheduleid, CONCAT(b.fld_exp_name,' / Expedition') AS assignname, 15 AS typeids, 0 AS rotation 
                                        FROM itc_class_indasexpedition_master AS a 
                                        LEFT JOIN itc_exp_master AS b ON a.fld_exp_id=b.fld_id
                                        LEFT JOIN itc_class_exp_student_mapping AS c ON (a.fld_id=c.fld_schedule_id)
                                        WHERE c.fld_student_id='".$studentid."' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_delstatus='0' ".$sqry3."
                                        GROUP BY a.fld_id) 
                                     UNION ALL
                                        (SELECT a.fld_module_id AS ids, a.fld_schedule_id AS schid, CONCAT(d.fld_exp_name,' ',e.fld_version,
                                                ' Rotation ',a.fld_rotation - 1) AS assignname, 20 AS typename, a.fld_rotation AS rotation 
                                        FROM itc_class_rotation_modexpschedulegriddet AS a 
                                        LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
                                        LEFT JOIN itc_class_rotation_modexpscheduledate AS c ON a.fld_schedule_id=c.fld_schedule_id and a.fld_rotation=c.fld_rotation 
                                        LEFT JOIN itc_exp_master AS d ON a.fld_module_id=d.fld_id
                                        LEFT JOIN itc_exp_version_track AS e ON d.fld_id=e.fld_exp_id 
                                        WHERE a.fld_student_id='".$studentid."' AND a.fld_type='2' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' 
                                        AND b.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND c.fld_flag='1' ".$sqry2."
                                        GROUP BY ids)	
                                    UNION ALL
                                        (SELECT a.fld_module_id AS ids, a.fld_schedule_id AS schid, CONCAT(d.fld_module_name,' ',e.fld_version,
                                                ' Rotation ',a.fld_rotation - 1) AS assignname, 21 AS typename, a.fld_rotation AS rotation 
                                        FROM itc_class_rotation_modexpschedulegriddet AS a 
                                        LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
                                        LEFT JOIN itc_class_rotation_modexpscheduledate AS c ON a.fld_schedule_id=c.fld_schedule_id and a.fld_rotation=c.fld_rotation 
                                        LEFT JOIN itc_module_master AS d ON a.fld_module_id=d.fld_id
                                        LEFT JOIN itc_module_version_track AS e ON d.fld_id=e.fld_mod_id 
                                        WHERE a.fld_student_id='".$studentid."' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' 
                                                AND a.fld_type='1' AND b.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND c.fld_flag='1' ".$sqry2."
                                        GROUP BY ids)
                                    UNION ALL
                                        (SELECT a.fld_module_id AS ids, a.fld_schedule_id AS schid, CONCAT(d.fld_contentname,' Custom Content'), 
                                        22 AS typename, 0 AS rotation 
                                        FROM itc_class_rotation_modexpschedulegriddet AS a 
                                        LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
                                        LEFT JOIN itc_class_rotation_modexpscheduledate AS c ON a.fld_schedule_id=c.fld_schedule_id and a.fld_rotation=c.fld_rotation 
                                        LEFT JOIN itc_customcontent_master AS d ON a.fld_module_id=d.fld_id
                                        WHERE a.fld_student_id='".$studentid."' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' AND a.fld_type='8'
                                                AND b.fld_delstatus='0' AND d.fld_delstatus='0' AND c.fld_flag='1' ".$sqry2." GROUP BY ids)	
                                    UNION ALL	
                                        (SELECT a.fld_mis_id AS ids, a.fld_id AS schid, CONCAT(b.fld_mis_name,' / Mission') AS assignname, 18 AS typename, 0 AS rotation 
                                        FROM itc_class_indasmission_master AS a 
                                        LEFT JOIN itc_mission_master AS b ON a.fld_mis_id=b.fld_id
                                        LEFT JOIN itc_class_mission_student_mapping AS c ON (a.fld_id=c.fld_schedule_id)
                                        WHERE c.fld_student_id='".$studentid."' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_delstatus='0' ".$sqry3."
                                        GROUP BY a.fld_id)
                                    UNION ALL	
                                        (SELECT a.fld_mission_id AS ids, a.fld_schedule_id AS schid, CONCAT(d.fld_mis_name,' ',e.fld_version,
                                            ' Rotation ',a.fld_rotation - 1) AS assignname, 23 AS typename, a.fld_rotation AS rotation 
                                        FROM itc_class_rotation_mission_schedulegriddet AS a 
                                        LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
                                        LEFT JOIN itc_class_rotation_missionscheduledate AS c ON a.fld_schedule_id=c.fld_schedule_id and a.fld_rotation=c.fld_rotation 
                                        LEFT JOIN itc_mission_master AS d ON a.fld_mission_id=d.fld_id
                                        LEFT JOIN itc_mission_version_track AS e ON d.fld_id=e.fld_mis_id 
                                        WHERE a.fld_student_id='".$studentid."' AND a.fld_class_id='".$id[2]."' AND a.fld_flag='1' 
                                        AND b.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND c.fld_flag='1' ".$sqry2."
                                        )
                                    ) AS w ORDER BY w.rotation");  //w.typename, w.schid,  GROUP BY ids
                
            if($qry->num_rows > 0)
            { 	 
                $cnt=0;
                while($row=$qry->fetch_assoc())
                {
                    extract($row);
                    $pointsearnedrubric=0;
                    $pointspossiblerubric=0;
                    $pointsearned=''; $pointspossible='';
                    
                    $qrydetails = '';
                    if($typename == 0)
                    {
                            $qrydetails = "SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END)
                                                            AS pointsearned, SUM(fld_points_possible) AS pointspossible 
                                            FROM itc_assignment_sigmath_master 
                                            WHERE fld_class_id='".$id[2]."' AND fld_student_id='".$studentid."' AND fld_schedule_id='".$schid."' 
                                                            AND fld_unit_id='".$ids."' AND fld_test_type='1' AND (fld_status='1' OR fld_status='2') 
                                                            AND fld_grade<>'0'";
                    }
                    
                    /*************Expedition Code Start here Developed by Mohan M 24-5-2016***************/
                    else if($typename == 15)
                    {
                        /************** Pre/Post test code start here ***************/
                        $pointsearnedfortest=0;
                        $possiblepointfortest1=0;
                        $possiblepointfortest=0;

                        $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                                                        WHERE fld_class_id='".$id[2]."' AND fld_sch_id='".$schid."' AND fld_schtype_id='".$typename."'");
                        if($qrytest->num_rows>0)
                        {
                            while($rowqrytest = $qrytest->fetch_assoc())
                            {
                                extract($rowqrytest);
                                $exptype='3';
                                /*********Pre Test Code start Here*********/
                                if($pretest!='0')
                                {
                                    $qryexp = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                                        FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_delstatus='0'");
                                    if($qryexp->num_rows>0)
                                    {
                                        while($rowqryexp = $qryexp->fetch_assoc())
                                        {
                                            extract($rowqryexp);

                                            if($questype==2)
                                            {
                                                $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                            }
                                            $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                            $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."' 
                                                                                            AND fld_exp_id='".$ids."' AND fld_schedule_id='".$schid."' 
                                                                                            AND fld_schedule_type='".$typename."' AND fld_grade='1' AND fld_res_id='".$testid."' 
                                                                                            AND fld_exptype='3'");

                                            if(trim($tchpointcnt)=='')
                                            {
                                                $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                            WHERE b.fld_expt = '".$ids."' AND a.fld_student_id = '".$studentid."' 
                                                                                                                AND a.fld_test_id='".$testid."'  AND a.fld_schedule_id='".$schid."' 
                                                                                                                AND a.fld_schedule_type='".$typename."' AND b.fld_delstatus = '0' 
                                                                                                                AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                 if(trim($correctcountfortestattend) != '')
                                                {
                                                    $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                            WHERE b.fld_expt = '".$ids."' AND a.fld_student_id = '".$studentid."' 
                                                                                                                AND a.fld_test_id='".$testid."'  AND a.fld_schedule_id='".$schid."' 
                                                                                                                AND a.fld_schedule_type='".$typename."' AND b.fld_delstatus = '0' 
                                                                                                                AND a.fld_show = '1' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                    $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                                    $possiblepointfortest+=$possiblepointfortest1;
                                                }
                                            }
                                            else
                                            {
                                                $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                                WHEN fld_lock = '0' THEN fld_points_earned
                                                                                                WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                            END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."'
                                                                                                AND fld_exp_id='".$ids."' AND fld_schedule_id='".$schid."' 
                                                                                                AND fld_schedule_type='".$typename."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");
                                                if($tchpointearn!='')
                                                {
                                                    $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                                    $possiblepointfortest+=$possiblepointfortest1;
                                                }

                                            }
                                        }
                                    }
                                }
                                /*********Pre Test Code End Here*********/

                                /*********Post Test Code start Here*********/
                                if($posttest!='0')
                                {
                                    $qryexp = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                                        FROM itc_test_master WHERE fld_id='".$posttest."' AND fld_delstatus='0'");
                                    if($qryexp->num_rows>0)
                                    {
                                        while($rowqryexp = $qryexp->fetch_assoc())
                                        {
                                            extract($rowqryexp);

                                            if($questype==2)
                                            {
                                                $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                            }
                                            $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                            $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."' 
                                                                                            AND fld_exp_id='".$ids."' AND fld_schedule_id='".$schid."' 
                                                                                            AND fld_schedule_type='".$typename."' AND fld_grade='1' AND fld_res_id='".$testid."' 
                                                                                            AND fld_exptype='3'");

                                            if(trim($tchpointcnt)=='')
                                            {
                                                $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                            WHERE b.fld_expt = '".$ids."' AND a.fld_student_id = '".$studentid."' 
                                                                                                                AND a.fld_test_id='".$testid."'  AND a.fld_schedule_id='".$schid."' 
                                                                                                                AND a.fld_schedule_type='".$typename."' AND b.fld_delstatus = '0' 
                                                                                                                AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                 if(trim($correctcountfortestattend) != '')
                                                {
                                                    $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                            WHERE b.fld_expt = '".$ids."' AND a.fld_student_id = '".$studentid."' 
                                                                                                                AND a.fld_test_id='".$testid."'  AND a.fld_schedule_id='".$schid."' 
                                                                                                                AND a.fld_schedule_type='".$typename."' AND b.fld_delstatus = '0' 
                                                                                                                AND a.fld_show = '1' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                    $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                                    $possiblepointfortest+=$possiblepointfortest1;
                                                }
                                            }
                                            else
                                            {
                                                $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                                WHEN fld_lock = '0' THEN fld_points_earned
                                                                                                WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                            END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."'
                                                                                                AND fld_exp_id='".$ids."' AND fld_schedule_id='".$schid."' 
                                                                                                AND fld_schedule_type='".$typename."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");
                                                if($tchpointearn!='')
                                                {
                                                    $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                                    $possiblepointfortest+=$possiblepointfortest1;
                                                }

                                            }
                                        }
                                    }
                                }
                            }
                        }
                        /************** Pre/Post test code end here ***************/   

                        /************** Rubric code start here ***************/
                        $schoolid=$ObjDB->SelectSingleValueInt("SELECT fld_school_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 

                        $sendistid=$ObjDB->SelectSingleValueInt("SELECT fld_district_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 

                        $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                    CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                        LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                        LEFT JOIN itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                                        LEFT JOIN itc_class_indasexpedition_master AS d ON a.fld_schedule_id=d.fld_id 
                                                            WHERE a.fld_class_id='".$id[2]."'  AND a.fld_schedule_id='".$schid."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                                AND a.fld_schedule_type='15' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                                AND b.fld_school_id IN(0,".$schoolid.")");

                        if($qryrub->num_rows>0)
                        {
                            while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
                            {
                                extract($rowqryrub);

                                $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master 
                                                                            WHERE fld_exp_id='".$ids."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                                $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_rpt WHERE fld_exp_id='".$ids."'  
                                                                             AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$id[2]."' 
                                                                             AND fld_schedule_id='".$schid."' AND fld_delstatus='0' "); 

                                $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_rpt_statement
                                                                                WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$ids."' AND fld_delstatus='0'
                                                                                AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                                $pointsearnedrubric = $pointsearnedrubric+$studentscore;
                                if($studentscore!=0)
                                {
                                    $pointspossiblerubric = $pointspossiblerubric+$totscore;
                                }
                            }
                        }
                        /************** Rubric code end here ***************/
                        $exptearned=round($pointsearnedfortest + $pointsearnedrubric);
                        $exptpossible=$possiblepointfortest + $pointspossiblerubric;

                        $pointsearned = $exptearned;
                        $pointspossible = $exptpossible;
                    }
                    /*************Expedition Code End here Developed by Mohan M 24-5-2016***************/
                    
                    /************Expedition Schedule Code Start Here Developed by Mohan M 24-5-2016*****************/
                    else if($typename == 19)
                    {
                        /*************EXP pre test or POST test Code Start Here**************/
                        $pointsearnedtest=0;
                        $pointsearnedfortest=0;
                        $possiblepointfortest1=0;
                        $possiblepointfortest=0;

                        $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                                                        WHERE fld_class_id='".$id[2]."' AND fld_sch_id='".$schid."' AND fld_texpid='".$ids."' AND fld_schtype_id='".$typename."'");

                        if($qrytest->num_rows>0)
                        {
                            while($rowqrytest = $qrytest->fetch_assoc())
                            {
                                extract($rowqrytest);
                                $exptype='3';

                                /*********Pre Test Code start Here*********/
                                if($pretest!='0')
                                {
                                    $qryexpsch = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_question_type AS questype
                                                                            FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_delstatus='0'");
                                    if($qryexpsch->num_rows>0)
                                    {
                                        while($rowqryexpsch = $qryexpsch->fetch_assoc())
                                        {
                                            extract($rowqryexpsch);

                                            if($questype==2)
                                            {
                                                $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                            }
                                            $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                            $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."' 
                                                                                        AND fld_exp_id='".$ids."' AND fld_schedule_id='".$schid."' AND fld_schedule_type='19' 
                                                                                        AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                            if(trim($tchpointcnt)=='')
                                            {
                                                $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                    LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                    WHERE b.fld_expt = '".$ids."' AND a.fld_student_id = '".$studentid."' AND a.fld_test_id='".$testid."' 
                                                                                                    AND a.fld_schedule_id='".$schid."' AND a.fld_schedule_type='".$typename."' AND b.fld_delstatus = '0' 
                                                                                                    AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                if(trim($correctcountfortestattend) != '')
                                                {
                                                     $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                    LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                    WHERE b.fld_expt = '".$ids."' AND a.fld_student_id = '".$studentid."' AND a.fld_test_id='".$testid."' 
                                                                                                    AND a.fld_schedule_id='".$schid."' AND a.fld_schedule_type='".$typename."' AND b.fld_delstatus = '0' 
                                                                                                    AND a.fld_show = '1' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                    $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                                    $possiblepointfortest+=$possiblepointfortest1;
                                                }
                                            }
                                            else
                                            {
                                                $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                                WHEN fld_lock = '0' THEN fld_points_earned
                                                                                                WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                            END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."'
                                                                                                AND fld_exp_id='".$ids."' AND fld_schedule_id='".$schid."' 
                                                                                                AND fld_schedule_type='".$typename."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");
                                                if($tchpointearn!='')
                                                {
                                                    $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                                    $possiblepointfortest+=$possiblepointfortest1;
                                                }

                                            }
                                        }
                                    }
                                }
                                /*********Pre Test Code End Here*********/

                                /*********Post Test Code start Here*********/
                                if($posttest!='0')
                                {
                                    $qryexpsch = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_question_type AS questype
                                                                            FROM itc_test_master WHERE fld_id='".$posttest."' AND fld_delstatus='0'");
                                    if($qryexpsch->num_rows>0)
                                    {
                                        while($rowqryexpsch = $qryexpsch->fetch_assoc())
                                        {
                                            extract($rowqryexpsch);

                                            if($questype==2)
                                            {
                                                $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                            }
                                            $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                            $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."' 
                                                                                        AND fld_exp_id='".$ids."' AND fld_schedule_id='".$schid."' AND fld_schedule_type='19' 
                                                                                        AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                            if(trim($tchpointcnt)=='')
                                            {
                                                $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                    LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                    WHERE b.fld_expt = '".$ids."' AND a.fld_student_id = '".$studentid."' AND a.fld_test_id='".$testid."' 
                                                                                                    AND a.fld_schedule_id='".$schid."' AND a.fld_schedule_type='".$typename."' AND b.fld_delstatus = '0' 
                                                                                                    AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                if(trim($correctcountfortestattend) != '')
                                                {
                                                     $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                    LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                    WHERE b.fld_expt = '".$ids."' AND a.fld_student_id = '".$studentid."' AND a.fld_test_id='".$testid."' 
                                                                                                    AND a.fld_schedule_id='".$schid."' AND a.fld_schedule_type='".$typename."' AND b.fld_delstatus = '0' 
                                                                                                    AND a.fld_show = '1' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                    $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                                    $possiblepointfortest+=$possiblepointfortest1;
                                                }
                                            }
                                            else
                                            {
                                                $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                                WHEN fld_lock = '0' THEN fld_points_earned
                                                                                                WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                            END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."'
                                                                                                AND fld_exp_id='".$ids."' AND fld_schedule_id='".$schid."' 
                                                                                                AND fld_schedule_type='".$typename."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");
                                                if($tchpointearn!='')
                                                {
                                                    $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                                    $possiblepointfortest+=$possiblepointfortest1;
                                                }

                                            }
                                        }
                                    }
                                }
                            }
                        }

                        /************** Rubric code start here ***************/
                        $schoolid=$ObjDB->SelectSingleValueInt("SELECT fld_school_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 

                        $sendistid=$ObjDB->SelectSingleValueInt("SELECT fld_district_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 

                        $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                        CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                        LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                        LEFT JOIN itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                                        LEFT JOIN itc_class_rotation_expschedule_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
                                                            WHERE a.fld_class_id='".$id[2]."'  AND a.fld_schedule_id='".$schid."' AND b.fld_exp_id='".$ids."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                            AND a.fld_schedule_type='17' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                            AND b.fld_school_id IN(0,".$schoolid.")");

                        if($qryrub->num_rows>0)
                        {
                            while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
                            {
                                extract($rowqryrub);

                                $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master 
                                                                            WHERE fld_exp_id='".$ids."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                                $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_expsch_rubric_rpt WHERE fld_exp_id='".$ids."'  
                                                                             AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$id[2]."' 
                                                                             AND fld_schedule_id='".$schid."' AND fld_delstatus='0' "); 

                                $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_expsch_rubric_rpt_statement
                                                                                WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$ids."' AND fld_delstatus='0'
                                                                                AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                                $pointsearnedrubric = $pointsearnedrubric+$studentscore;
                                if($studentscore!=0)
                                {
                                    $pointspossiblerubric = $pointspossiblerubric+$totscore;
                                }
                            }
                        }

                        /************** Rubric code end here ***************/
                        $schexptearned=round($pointsearnedfortest + $pointsearnedrubric);
                        $schexptpossible=$possiblepointfortest + $pointspossiblerubric;

                        $pointsearned = $schexptearned;
                        $pointspossible = $schexptpossible;
                    }
                    /************Expedition Schedule Code End Here Developed by Mohan M 24-5-2016*****************/
                    
                    /**************Expedition and Module Schedule Developed by Mohan M 24-5-2016******************/
                    else if($typename==20)
                    {
                        /*************EXP pre test or POST test Code Start Here**************/
                        $pointsearnedtest=0;
                        $pointsearnedfortest=0;
                        $possiblepointfortest1=0;
                        $possiblepointfortest=0;
                        
                        $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                                                        WHERE fld_class_id='".$id[2]."' AND fld_sch_id='".$schid."' AND fld_texpid='".$ids."' AND fld_schtype_id='".$typename."'");

                        if($qrytest->num_rows>0)
                        {
                            while($rowqrytest = $qrytest->fetch_assoc())
                            {
                                extract($rowqrytest);
                                $exptype='3';

                                /*********Pre Test Code start Here*********/
                                if($pretest!='0')
                                {
                                    $qrypretest = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                                        FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_delstatus='0'");
                                    if($qrypretest->num_rows>0)
                                    {
                                        while($rowqrypretest = $qrypretest->fetch_assoc())
                                        {
                                            extract($rowqrypretest);

                                            if($questype==2)
                                            {
                                                $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                            }
                                            $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                            $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."' 
                                                                                                    AND fld_exp_id='".$ids."' AND fld_schedule_id='".$schid."' 
                                                                                                    AND fld_schedule_type='".$typename."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");
                                            if(trim($tchpointcnt)=='')
                                            {
                                                $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                            WHERE b.fld_expt = '".$ids."' AND a.fld_student_id = '".$studentid."' 
                                                                                                            AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$schid."' 
                                                                                                            AND a.fld_schedule_type='".$typename."' AND b.fld_delstatus = '0' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                if(trim($correctcountfortestattend) != '')
                                                {
                                                    $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                            WHERE b.fld_expt = '".$ids."' AND a.fld_student_id = '".$studentid."' 
                                                                                                            AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$schid."' 
                                                                                                            AND a.fld_schedule_type='".$typename."' AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                    $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                                    $possiblepointfortest+=$possiblepointfortest1;
                                                }
                                            }
                                            else
                                            {
                                                $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                                WHEN fld_lock = '0' THEN fld_points_earned
                                                                                                WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                        END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."'
                                                                                                AND fld_exp_id='".$ids."' AND fld_schedule_id='".$schid."' 
                                                                                                AND fld_schedule_type='".$typename."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");


                                                if($tchpointearn!='')
                                                {
                                                    $possiblepointfortest+=$possiblepointfortest1;
                                                    $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                                }
                                            }
                                        }
                                    }
                                }
                                /*********Pre Test Code End Here*********/

                                /*********Post Test Code start Here*********/
                                if($posttest!='0')
                                {
                                    $qryposttest = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                                        FROM itc_test_master WHERE fld_id='".$posttest."' AND fld_delstatus='0'");
                                    if($qryposttest->num_rows>0)
                                    {
                                        while($rowqryposttest = $qryposttest->fetch_assoc())
                                        {
                                            extract($rowqryposttest);

                                            if($questype==2)
                                            {
                                                $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                            }
                                            $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                            $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."' 
                                                                                                    AND fld_exp_id='".$ids."' AND fld_schedule_id='".$schid."' 
                                                                                                    AND fld_schedule_type='".$typename."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");
                                            if(trim($tchpointcnt)=='')
                                            {
                                                $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                            WHERE b.fld_expt = '".$ids."' AND a.fld_student_id = '".$studentid."' 
                                                                                                            AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$schid."' 
                                                                                                            AND a.fld_schedule_type='".$typename."' AND b.fld_delstatus = '0' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                if(trim($correctcountfortestattend) != '')
                                                {
                                                    $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                            WHERE b.fld_expt = '".$ids."' AND a.fld_student_id = '".$studentid."' 
                                                                                                            AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$schid."' 
                                                                                                            AND a.fld_schedule_type='".$typename."' AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                    $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                                    $possiblepointfortest+=$possiblepointfortest1;
                                                }
                                            }
                                            else
                                            {
                                                $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                                WHEN fld_lock = '0' THEN fld_points_earned
                                                                                                WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                        END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."'
                                                                                                AND fld_exp_id='".$ids."' AND fld_schedule_id='".$schid."' 
                                                                                                AND fld_schedule_type='".$typename."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");


                                                if($tchpointearn!='')
                                                {
                                                    $possiblepointfortest+=$possiblepointfortest1;
                                                    $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        
                        /*************EXP pre test or POST test Code End Here****************/

                        /************** Rubric code start here ***************/
                        $schoolid=$ObjDB->SelectSingleValueInt("SELECT fld_school_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 

                        $sendistid=$ObjDB->SelectSingleValueInt("SELECT fld_district_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 

                        $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                        CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                        LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                        LEFT JOIN itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                                        LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
                                                        WHERE a.fld_class_id='".$id[2]."'  AND a.fld_schedule_id='".$schid."' AND b.fld_exp_id='".$ids."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                                AND a.fld_schedule_type='20' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                                AND b.fld_school_id IN(0,".$schoolid.")");

                        if($qryrub->num_rows>0)
                        {
                            while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
                            {
                                extract($rowqryrub);

                                $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master 
                                                                            WHERE fld_exp_id='".$ids."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                                $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_expmodsch_rubric_rpt WHERE fld_exp_id='".$ids."'  
                                                                                AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$id[2]."' 
                                                                                AND fld_schedule_id='".$schid."' AND fld_delstatus='0' "); 

                                $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_expmodsch_rubric_rpt_statement
                                                                                    WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$ids."' AND fld_delstatus='0'
                                                                                    AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                               $pointsearnedrubric = $pointsearnedrubric+$studentscore;
                                if($studentscore!=0)
                                {
                                    $pointspossiblerubric = $pointspossiblerubric+$totscore;
                                }
                            }
                        }
                        $expmodschexptearned=round($pointsearnedfortest + $pointsearnedrubric);
                        $expmodschexptpossible=$possiblepointfortest + $pointspossiblerubric;

                        $pointsearned = $expmodschexptearned;
                        $pointspossible = $expmodschexptpossible;
                    }
                    /**************Expedition and Module Schedule Developed by Mohan M 24-5-2016******************/
		    /*******WCA Mission Code Developed by Mohan M 24-5-2016**********/
                    else if($typename==18)
                    {
                        /*********Participation code Start here*******/
                        $gradepointsearned = $ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned AS pointsearned FROM itc_mis_points_master 
                                                                WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$ids."' 
                                                                    AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$typename."' AND fld_mistype='4'
                                                                    AND fld_grade='1' AND fld_delstatus='0'");

                        $gradepointspossible = $ObjDB->SelectSingleValueInt("SELECT fld_points_possible AS pointspossible FROM itc_mis_points_master 
                                                    WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$ids."' 
                                                        AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$typename."' AND fld_mistype='4'
                                                        AND fld_grade='1' AND fld_delstatus='0'");
                        /*********Participation code Start here*******/
                        /************** Rubric code start here ***************/
                        $schoolid=$ObjDB->SelectSingleValueInt("SELECT fld_school_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 

                        $sendistid=$ObjDB->SelectSingleValueInt("SELECT fld_district_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 
                        $pointsearnedrubric=0;
                        $pointspossiblerubric=0;
                        $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                        CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                        LEFT JOIN itc_mis_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                        LEFT JOIN itc_mission_master AS c ON a.fld_expmisid = c.fld_id
                                                        LEFT JOIN itc_class_indasmission_master AS d ON a.fld_schedule_id=d.fld_id 
                                                                WHERE a.fld_class_id='".$id[2]."' AND a.fld_schedule_id='".$schid."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                                        AND a.fld_schedule_type='18' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                                        AND b.fld_school_id IN(0,".$schoolid.")");

                        if($qryrub->num_rows>0)
                        {
                            while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
                            {
                                extract($rowqryrub);

                                $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_master 
                                                                            WHERE fld_mis_id='".$ids."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                                $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_rubric_rpt WHERE fld_mis_id='".$ids."'  
                                                                                    AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$id[2]."' 
                                                                                    AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' "); 

                                $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_rpt_statement
                                                                                    WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$ids."' AND fld_delstatus='0'
                                                                                    AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                                $pointsearnedrubric = $pointsearnedrubric+$studentscore;
                                if($studentscore!=0)
                                {
                                    $pointspossiblerubric = $pointspossiblerubric+$totscore;
                                }
                            }
                        }
                        /************** Rubric code end here ***************/
                        
                        /************** Test code start here ***************/
                        $pointsearnedfortest=0;
                        $possiblepointfortest1=0;
                        $possiblepointfortest=0;

                        $qrytest = $ObjDB->QueryObject("SELECT fld_test_id AS pretest,fld_mis_id AS misid FROM itc_mis_ass
                                                            WHERE fld_class_id='".$id[2]."' AND fld_sch_id='".$schid."' AND fld_schtype_id='".$typename."' AND fld_flag='1'");
                        if($qrytest->num_rows>0)
                        {
                            while($rowqrytest = $qrytest->fetch_assoc())
                            {
                                extract($rowqrytest);
                                $exptype='3';

                                $qryexp = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                                    FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_delstatus='0'");
                                if($qryexp->num_rows>0)
                                {
                                    while($rowqryexp = $qryexp->fetch_assoc())
                                    {
                                        extract($rowqryexp);

                                        if($questype==2)
                                        {
                                            $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                        }
                                        $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                        $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_mis_points_master WHERE fld_student_id='".$studentid."' 
                                                                                        AND fld_mis_id='".$misid."' AND fld_schedule_id='".$schid."' 
                                                                                        AND fld_schedule_type='".$typename."' AND fld_grade='1' AND fld_res_id='".$testid."' 
                                                                                        AND fld_mistype='3'");

                                        if(trim($tchpointcnt)=='')
                                        {
                                            $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                    LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                        WHERE b.fld_mist = '".$misid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                            AND a.fld_test_id='".$testid."'  AND a.fld_schedule_id='".$schid."' 
                                                                                                            AND a.fld_schedule_type='".$typename."' AND b.fld_delstatus = '0' 
                                                                                                            AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                            if(trim($correctcountfortestattend) != '')
                                            {
                                                $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                    LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                        WHERE b.fld_mist = '".$misid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                            AND a.fld_test_id='".$testid."'  AND a.fld_schedule_id='".$schid."' 
                                                                                                            AND a.fld_schedule_type='".$typename."' AND b.fld_delstatus = '0' 
                                                                                                            AND a.fld_show = '1' AND a.fld_delstatus = '0' AND a.fld_retake='0'");
                                                $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                                $possiblepointfortest+=$possiblepointfortest1;
                                            }
                                        }
                                        else
                                        {
                                            $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                            WHEN fld_lock = '0' THEN fld_points_earned
                                                                                            WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                        END) AS pointsearned FROM itc_mis_points_master WHERE fld_student_id='".$studentid."'
                                                                                            AND fld_mis_id='".$misid."' AND fld_schedule_id='".$schid."' 
                                                                                            AND fld_schedule_type='".$typename."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_mistype='3'");
                                            if($tchpointearn!='')
                                            {
                                                $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                                $possiblepointfortest+=$possiblepointfortest1;
                                            }

                                        }
                                    }
                                }
                            }
                        }
                        /**************Test code end here ***************/

                        $pointsearned=$gradepointsearned+$pointsearnedrubric+$pointsearnedfortest;
                        $pointspossible=$gradepointspossible+$pointspossiblerubric+$possiblepointfortest;
                    }
                    else if($typename==23)
                    {
                        $gradepointsearned = $ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned AS pointsearned FROM itc_mis_points_master 
                                                    WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$ids."' 
                                                        AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$typename."' AND fld_mistype='4'
                                                        AND fld_grade='1' AND fld_delstatus='0'");

                        $gradepointspossible = $ObjDB->SelectSingleValueInt("SELECT fld_points_possible AS pointspossible FROM itc_mis_points_master 
                                                    WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$ids."' 
                                                        AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$typename."' AND fld_mistype='4'
                                                        AND fld_grade='1' AND fld_delstatus='0'");

                        /************** Rubric code start here ***************/
                        $schoolid=$ObjDB->SelectSingleValueInt("SELECT fld_school_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 

                        $sendistid=$ObjDB->SelectSingleValueInt("SELECT fld_district_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 
                        $pointsearnedrubric=0;
                        $pointspossiblerubric=0;
                        $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                    CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                            LEFT JOIN itc_mis_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                            LEFT JOIN itc_mission_master AS c ON a.fld_expmisid = c.fld_id
                                            LEFT JOIN itc_class_rotation_mission_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
                                                WHERE a.fld_class_id='".$id[2]."' AND a.fld_schedule_id='".$schid."' AND b.fld_mis_id='".$ids."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                        AND a.fld_schedule_type='19' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                        AND b.fld_school_id IN(0,".$schoolid.")");

                        if($qryrub->num_rows>0)
                        {
                            $totscore=0;
                            while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
                            {
                                extract($rowqryrub);

                               $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_master 
                                                                                    WHERE fld_mis_id='".$ids."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    


                                $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_missch_rubric_rpt WHERE fld_mis_id='".$ids."'  
                                                                                    AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$id[2]."' 
                                                                                    AND fld_schedule_id='".$schid."' AND fld_delstatus='0' "); 

                                $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_missch_rubric_rpt_statement
                                                                                    WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$ids."' AND fld_delstatus='0'
                                                                                    AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                                $pointsearnedrubric = $pointsearnedrubric+$studentscore;
                                if($studentscore!=0)
                                {
                                    $pointspossiblerubric = $pointspossiblerubric+$totscore;
                                }
                            }
                        }
                        /************** Rubric code end here ***************/
                       
                        /************** Test code start here ***************/
                        $pointsearnedfortest=0;
                        $possiblepointfortest1=0;
                        $possiblepointfortest=0;

                        $qrytest = $ObjDB->QueryObject("SELECT fld_test_id AS pretest,fld_mis_id AS misid FROM itc_mis_ass
                                                            WHERE fld_class_id='".$id[2]."' AND fld_sch_id='".$schid."'  AND fld_mis_id='".$ids."' AND fld_schtype_id='20' AND fld_flag='1'");
                        if($qrytest->num_rows>0)
                        {
                            while($rowqrytest = $qrytest->fetch_assoc())
                            {
                                extract($rowqrytest);
                                $exptype='3';

                                $qryexp = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                                    FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_delstatus='0'");
                                if($qryexp->num_rows>0)
                                {
                                    while($rowqryexp = $qryexp->fetch_assoc())
                                    {
                                        extract($rowqryexp);

                                        if($questype==2)
                                        {
                                            $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                        }
                                        $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0'");

                                        $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_mis_points_master WHERE fld_student_id='".$studentid."' 
                                                                                        AND fld_mis_id='".$misid."' AND fld_schedule_id='".$schid."' 
                                                                                        AND fld_schedule_type='20' AND fld_grade='1' AND fld_res_id='".$testid."' 
                                                                                        AND fld_mistype='3'");

                                        if(trim($tchpointcnt)=='')
                                        {
                                            $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                    LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                        WHERE b.fld_mist = '".$misid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                            AND a.fld_test_id='".$testid."'  AND a.fld_schedule_id='".$schid."' 
                                                                                                            AND a.fld_schedule_type='20' AND b.fld_delstatus = '0' 
                                                                                                            AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                            if(trim($correctcountfortestattend) != '')
                                            {
                                                $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                    LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                        WHERE b.fld_mist = '".$misid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                            AND a.fld_test_id='".$testid."'  AND a.fld_schedule_id='".$schid."' 
                                                                                                            AND a.fld_schedule_type='20' AND b.fld_delstatus = '0' 
                                                                                                            AND a.fld_show = '1' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                                $possiblepointfortest+=$possiblepointfortest1;
                                            }
                                        }
                                        else
                                        {
                                            $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                            WHEN fld_lock = '0' THEN fld_points_earned
                                                                                            WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                        END) AS pointsearned FROM itc_mis_points_master WHERE fld_student_id='".$studentid."'
                                                                                            AND fld_mis_id='".$misid."' AND fld_schedule_id='".$schid."' 
                                                                                            AND fld_schedule_type='20' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_mistype='3'");
                                            if($tchpointearn!='')
                                            {
                                                $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                                $possiblepointfortest+=$possiblepointfortest1;
                                            }

                                        }
                                    }
                                }
                            }
                        }
                        /**************Test code end here ***************/

                        $pointsearned=$gradepointsearned+$pointsearnedrubric + $pointsearnedfortest;
                        $pointspossible=$gradepointspossible+$pointspossiblerubric+ $possiblepointfortest;
                    }
                                
                    
                    
                    /*******WCA Mission Code Developed by Mohan M 24-5-2016**********/
                    
					
                    else if($typename == 4 or $typename == 6)
                    {
                            if($typename==4)
                                    $testtype=2;
                            else if($typename==6)
                                    $testtype=5;

                            $qryiplids = $ObjDB->QueryObject("SELECT fld_ipl_day1, fld_ipl_day2 
                                                                        FROM itc_mathmodule_master 
                                                                        WHERE fld_id='".$ids."'  AND fld_delstatus='0'");
                            $rowqryiplids=$qryiplids->fetch_assoc();
                            extract($rowqryiplids);

                            $qrydetails = "SELECT SUM(w.earnedpoints) AS pointsearned, SUM(w.pointspossible) AS pointspossible 
                                                FROM (SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
                                                THEN fld_teacher_points_earned END) AS earnedpoints, SUM(fld_points_possible) AS pointspossible 
                                                FROM itc_module_points_master 
                                                WHERE fld_module_id='".$ids."' AND fld_schedule_id='".$schid."' AND fld_student_id='".$studentid."' 
                                                AND fld_schedule_type='".$typename."' AND fld_delstatus='0' AND fld_grade<>'0'	
                                            UNION ALL		
                                                SELECT ROUND(SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN 
                                                fld_teacher_points_earned END)/4) AS earnedpoints, ROUND(SUM(fld_points_possible)/4) AS pointspossible 
                                                FROM itc_assignment_sigmath_master 
                                                WHERE fld_schedule_id='".$schid."' AND fld_student_id='".$studentid."' AND fld_test_type='".$testtype."' 
                                                AND fld_module_id='".$ids."'
                                                AND fld_class_id='".$id[2]."' AND fld_delstatus='0' AND (fld_status='1' OR fld_status='2' OR fld_lock='1') 
                                                AND (fld_lesson_id IN (".$fld_ipl_day1.") OR fld_lesson_id IN (".$fld_ipl_day2."))
                                                ) AS w";
                    }
                    else 
                    {
                        $qrydetails = "SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END)
                                                                        AS pointsearned, SUM(fld_points_possible) AS pointspossible 
                                                        FROM itc_module_points_master 
                                                        WHERE fld_student_id='".$studentid."' AND fld_delstatus='0' AND fld_schedule_type='".$typename."'
                                                                        AND fld_schedule_id='".$schid."' AND fld_module_id='".$ids."' AND fld_grade<>'0'";
                    }

                    if($typename != 15 AND $typename != 19 AND $typename != 20 AND $typename != 18 AND $typename != 23) 
                    {
                        $qryscore = $ObjDB->QueryObject($qrydetails);

                        $rowqry=$qryscore->fetch_assoc();
                        extract($rowqry);
                    } 
                    if($pointsearned!='')
                    {
                        if($roundflag==0)
                            $percentage = round(($pointsearned/$pointspossible)*100,2);
                        else
                            $percentage = round(($pointsearned/$pointspossible)*100);

                        $perarray = explode('.',$percentage);
                        $grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
                                                                    FROM itc_class_grading_scale_mapping 
                                                                    WHERE fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' 
                                                                                    AND fld_class_id='".$id[2]."' AND fld_flag='1'");
                        $percentage = $percentage." %";
                        $pointsearned = round($pointsearned);
                    }
                    else
                    {
                        $pointsearned= " - ";
                        $pointspossible= " - ";
                        $percentage= " - ";
                        $grade= " N/A ";
                    }

                    $out .= $assignname." , ".$pointsearned." , ".$pointspossible." , ".$percentage." , ".$grade." , ";
                    $out .= "\n\n";						
                }
            }
            else
            { 
                $out .= "No Records";
                $out .= "\n\n\n\n";
            } 			
            $count++;
            $out .="\n\n\n\n";
        }
    }        
}

//Class report
if($id[0]==3)
{
    $name="Class_report";

    $startdate = date('Y-m-d',strtotime($id[2]));
    $enddate = date('Y-m-d',strtotime($id[3]));

    $sqry = "AND ('".$startdate."' BETWEEN b.fld_start_date AND b.fld_end_date OR '".$enddate."' BETWEEN b.fld_start_date AND b.fld_end_date OR b.fld_start_date BETWEEN '".$startdate."' AND '".$enddate."' OR b.fld_end_date BETWEEN '".$startdate."' AND '".$enddate."')";
    $sqry1 = " AND ('".$startdate."' BETWEEN b.fld_startdate AND b.fld_enddate OR '".$enddate."' BETWEEN b.fld_startdate AND b.fld_enddate OR b.fld_startdate BETWEEN '".$startdate."' AND '".$enddate."' OR b.fld_enddate BETWEEN '".$startdate."' AND '".$enddate."')";
    $sqry2 = " AND ('".$startdate."' BETWEEN c.fld_startdate AND c.fld_enddate OR '".$enddate."' BETWEEN c.fld_startdate AND c.fld_enddate OR c.fld_startdate BETWEEN '".$startdate."' AND '".$enddate."' OR c.fld_enddate BETWEEN '".$startdate."' AND '".$enddate."')";
    $sqry3 = " AND ('".$startdate."' BETWEEN a.fld_startdate AND a.fld_enddate OR '".$enddate."' BETWEEN a.fld_startdate AND a.fld_enddate OR a.fld_startdate BETWEEN '".$startdate."' AND '".$enddate."' OR a.fld_enddate BETWEEN '".$startdate."' AND '".$enddate."')";
    
    $csv_hdr = "";
    $out .= $csv_hdr;

    $qryclass = $ObjDB->QueryObject("SELECT fld_class_name AS classname, fld_period AS period 
                                                        FROM itc_class_master 
                                                        WHERE fld_id='".$id[1]."'");

    $row=$qryclass->fetch_assoc();
    extract($row);

    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if (($period %100) >= 11 and ($period%100) <= 13)
       $abbreviation = $period. 'th';
    else
       $abbreviation = $period. $ends[$period % 10];

    $out .= "Class Name : ,".$classname.' '.$abbreviation.' Period';
    $out .= "\n\n";

    $qrystudent = $ObjDB->QueryObject("SELECT a.fld_student_id AS studentid, CONCAT(b.fld_fname,' ',b.fld_lname) AS studentname,fld_username as username,fld_password as password
                                                    FROM `itc_class_student_mapping` AS a 
                                                    LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                    WHERE a.fld_class_id='".$id[1]."' AND a.fld_flag='1' AND b.`fld_activestatus`='1' AND b.`fld_delstatus`='0' 
                                                    ORDER BY b.fld_lname"); 

    if($id[4]=='1')
    {
        $thname="Student Name";
    }
    else if($id[4]=='2')
    {
        $thname="Username";
    }
    else if($id[4]=='3')
    {
        $thname="Password";
    }

    $roundflag = $ObjDB->SelectSingleValue("SELECT fld_roundflag 
                                                    FROM itc_class_grading_scale_mapping 
                                                    WHERE fld_class_id = '".$id[1]."' AND fld_flag = '1' 
                                                    GROUP BY fld_roundflag"); 

    $out .= $thname." , Points Earned , Points Possible , Percentage , Grade ";
    $out .="\n";

    if($qrystudent->num_rows > 0)
    { 	 
        $cnt=0;
        while($row=$qrystudent->fetch_assoc())
        {
            extract($row);

            if($id[4]=='1')
            {
                $stuname=$studentname;
            }
            else if($id[4]=='2')
            {
                $stuname=$username;
            }
            else if($id[4]=='3')
            {
                $stuname=fnDecrypt($password,$encryptkey);
            }
            
            $expearned = '';
            $exppossible = '';
            $testearned = '';
            $testpossible = '';
            
            /**********Expedition code developed by Mohan M 22-3-2016****************/
            $qryexp = $ObjDB->QueryObject("SELECT a.fld_id AS scheduleid, a.fld_exp_id AS assid, '0' AS maxids, 
                                            fn_shortname(CONCAT(b.fld_exp_name,' / Expedition'),1) AS nam, 
                                            CONCAT(b.fld_exp_name,' / Expedition') AS fullnam, 15 AS typeids, a.fld_schedule_name AS schname, a.fld_startdate AS startdate, a.fld_enddate AS enddate 
                                            FROM itc_class_indasexpedition_master AS a 
                                            LEFT JOIN itc_exp_master AS b ON a.fld_exp_id=b.fld_id
                                            WHERE a.fld_class_id='".$id[1]."' AND a.fld_flag='1' AND a.fld_delstatus='0' 
                                            AND b.fld_delstatus='0' ".$sqry3."
                                            GROUP BY a.fld_id");
            if($qryexp->num_rows>0)
            {
                $exptearned = '';
                $exptpossible = '';
                while($rowqryexp = $qryexp->fetch_assoc())
                {
                    extract($rowqryexp);

                    /************** Pre/Post test code start here ***************/
                    $pointsearnedfortest=0;
                    $possiblepointfortest1=0;
                    $possiblepointfortest=0;
                    
                    $pointsearnedrubric=0;
                    $pointspossiblerubric=0;

                    $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                            WHERE fld_class_id='".$id[1]."' AND fld_sch_id='".$scheduleid."' AND fld_schtype_id='".$typeids."'");

                    if($qrytest->num_rows>0)
                    {
                        while($rowqrytest = $qrytest->fetch_assoc())
                        {
                            extract($rowqrytest);
                            $exptype='3';
                            /*********Pre Test Code start Here*********/
                            if($pretest!='0')
                            {
                                $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                                FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_delstatus='0'");
                                if($qry->num_rows>0)
                                {
                                    while($rowqry = $qry->fetch_assoc())
                                    {
                                        extract($rowqry);
                                        if($questype==2)
                                        {
                                            $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                        }
                                        
                                        $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                        $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."' 
                                                                                        AND fld_exp_id='".$assid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                        AND fld_schedule_type='".$typeids."' AND fld_grade='1' AND fld_res_id='".$testid."' 
                                                                                        AND fld_exptype='3'");

                                        if(trim($tchpointcnt)=='')
                                        {
                                            $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                    LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                    WHERE b.fld_expt = '".$assid."' AND a.fld_student_id = '".$studentid."' 
                                                                                            AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                                                        AND a.fld_schedule_type='".$typeids."' AND b.fld_delstatus = '0' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                            if(trim($correctcountfortestattend) != '')
                                            {
                                                $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                    LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                    WHERE b.fld_expt = '".$assid."' AND a.fld_student_id = '".$studentid."' 
                                                                                            AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                                                        AND a.fld_schedule_type='".$typeids."' AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                                $possiblepointfortest+=$possiblepointfortest1;
                                            }
                                        }
                                        else
                                        {
                                            $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                            WHEN fld_lock = '0' THEN fld_points_earned
                                                                                            WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                        END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."'
                                                                                            AND fld_exp_id='".$assid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                            AND fld_schedule_type='".$typeids."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");
                                            if($tchpointearn!='')
                                            {
                                                $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                                $possiblepointfortest+=$possiblepointfortest1;
                                            }
                                        }
                                    }
                                }
                            }
                             /*********Pre Test Code End Here*********/
            
                            /*********Post Test Code start Here*********/
                            if($posttest!='0')
                            {
                                $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                                FROM itc_test_master WHERE fld_id='".$posttest."' AND fld_delstatus='0'");
                                if($qry->num_rows>0)
                                {
                                    while($rowqry = $qry->fetch_assoc())
                                    {
                                        extract($rowqry);
                                        if($questype==2)
                                        {
                                            $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                        }
                                        $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                        $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."' 
                                                                                        AND fld_exp_id='".$assid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                        AND fld_schedule_type='".$typeids."' AND fld_grade='1' AND fld_res_id='".$testid."' 
                                                                                        AND fld_exptype='3'");

                                        if(trim($tchpointcnt)=='')
                                        {
                                            $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                    LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                    WHERE b.fld_expt = '".$assid."' AND a.fld_student_id = '".$studentid."' 
                                                                                            AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                                                        AND a.fld_schedule_type='".$typeids."' AND b.fld_delstatus = '0' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                            if(trim($correctcountfortestattend) != '')
                                            {
                                                $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                    LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                    WHERE b.fld_expt = '".$assid."' AND a.fld_student_id = '".$studentid."' 
                                                                                            AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                                                        AND a.fld_schedule_type='".$typeids."' AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                                $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                                $possiblepointfortest+=$possiblepointfortest1;
                                            }
                                        }
                                        else
                                        {
                                            $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                            WHEN fld_lock = '0' THEN fld_points_earned
                                                                                            WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                        END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."'
                                                                                            AND fld_exp_id='".$assid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                            AND fld_schedule_type='".$typeids."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");
                                            if($tchpointearn!='')
                                            {
                                                $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                                $possiblepointfortest+=$possiblepointfortest1;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                   
                    /************** Pre/Post test code end here ***************/   

                    /************** Rubric code start here ***************/
                    $schoolid=$ObjDB->SelectSingleValueInt("SELECT fld_school_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 
            
                    $sendistid=$ObjDB->SelectSingleValueInt("SELECT fld_district_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 

                    $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                    LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                    LEFT JOIN itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                                    LEFT JOIN itc_class_indasexpedition_master AS d ON a.fld_schedule_id=d.fld_id 
                                                        WHERE a.fld_class_id='".$id[1]."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                            AND a.fld_schedule_type='15' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                            AND b.fld_school_id IN(0,".$schoolid.")");

                    if($qryrub->num_rows>0)
                    {
                        while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
                        {
                            extract($rowqryrub);

                            $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master 
                                                                        WHERE fld_exp_id='".$assid."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                            $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_rpt WHERE fld_exp_id='".$assid."'  
                                                                         AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$id[1]."' 
                                                                         AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' "); 

                            $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_rpt_statement
                                                                            WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$assid."' AND fld_delstatus='0'
                                                                            AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                            $pointsearnedrubric = $pointsearnedrubric+$studentscore;
                            if($studentscore!=0)
                            {
                                $pointspossiblerubric = $pointspossiblerubric+$totscore;
                            }
                        }
                    }
                    /************** Rubric code end here ***************/
                    $pointsearned=round($pointsearnedfortest + $pointsearnedrubric,2);
                    $pointspossible=$possiblepointfortest + $pointspossiblerubric;
                    $exptearned = $exptearned + $pointsearned;
                    $exptpossible = $exptpossible + $pointspossible;

                }
            }
            else
            {
                    $exptearned = '';
                    $exptpossible = '';
            }


            $qryexpsch= $ObjDB->QueryObject("SELECT 
                                        d.fld_id AS scheduleid,
                                        b.fld_expedition_id AS expid
                                        
                                    FROM
                                        itc_class_rotation_expschedulegriddet AS b
                                            LEFT JOIN
                                        itc_class_rotation_expscheduledate AS c ON b.fld_schedule_id = c.fld_schedule_id
                                            AND b.fld_rotation = c.fld_rotation
                                            LEFT JOIN
                                        itc_class_rotation_expschedule_mastertemp AS d ON c.fld_schedule_id = d.fld_id
                                        WHERE
                                        b.fld_student_id = '".$studentid."'
                                            AND b.fld_class_id = '".$id[1]."'
                                            AND b.fld_flag = '1'
                                            AND c.fld_flag = '1'
                                            AND d.fld_delstatus = '0' ".$sqry2."");

        if($qryexpsch->num_rows>0)
        {
            $schexptearned = '';
            $schexptpossible = '';
            $pointsearnedfortest=0;
            $possiblepointfortest1=0;
            $possiblepointfortest=0;
            $pointsearnedrubric=0;
            $pointspossiblerubric=0;
            while($rowqryexpsch = $qryexpsch->fetch_assoc())
            {
                extract($rowqryexpsch);

                /*************EXP pre test or POST test Code Start Here**************/
                $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                            WHERE fld_class_id='".$id[1]."' AND fld_sch_id='".$scheduleid."' AND fld_texpid='".$expid."' AND fld_schtype_id='19'");

                if($qrytest->num_rows>0)
                {
                    while($rowqrytest = $qrytest->fetch_assoc())
                    {
                        extract($rowqrytest);
                        $exptype='3';

                        /*********Pre Test Code start Here*********/
                        if($pretest!='0')
                        {
                            $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_question_type AS questype
                                                    FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_delstatus='0'");
                            if($qry->num_rows>0)
                            {
                                while($rowqry = $qry->fetch_assoc())
                                {
                                    extract($rowqry);
                                    if($questype==2)
                                    {
                                        $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                    }
                                    $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master 
                                                                                            where fld_id='".$testid."' and fld_delstatus='0';");

                                    $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master 
                                                                                    WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$expid."' 
                                                                                    AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='19' 
                                                                                    AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                    if(trim($tchpointcnt)=='')
                                    {
                                        $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                            WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                            AND a.fld_schedule_id='".$scheduleid."' 
                                                                                            AND a.fld_test_id='".$testid."' AND a.fld_schedule_type='19' AND b.fld_delstatus = '0' 
                                                                                            AND a.fld_delstatus = '0' AND a.fld_retake='0' ");

                                        if(trim($correctcountfortestattend) != '')
                                        {
                                            $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                            WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                            AND a.fld_schedule_id='".$scheduleid."' 
                                                                                            AND a.fld_test_id='".$testid."' AND a.fld_schedule_type='19' AND b.fld_delstatus = '0' AND a.fld_show = '1' 
                                                                                            AND a.fld_delstatus = '0' AND a.fld_retake='0' ");

                                            $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                            $possiblepointfortest+=$possiblepointfortest1;
                                        }
                                    }
                                    else
                                    {
                                        $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                            WHEN fld_lock = '0' THEN fld_points_earned
                                                                                            WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                    END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."'
                                                                                            AND fld_exp_id='".$expid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                            AND fld_schedule_type='19' AND fld_grade='1' AND fld_res_id='".$testid."' 
                                                                                            AND fld_exptype='3'");
                                        if($tchpointearn!='')
                                        {
                                             $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                            $possiblepointfortest+=$possiblepointfortest1;
                                        }
                                    }
                                }
                            }
                        }
                         /*********Pre Test Code End Here*********/

                        /*********Post Test Code start Here*********/
                        if($posttest!='0')
                        {
                            $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_question_type AS questype
                                                    FROM itc_test_master WHERE fld_id='".$posttest."' AND fld_delstatus='0'");
                            if($qry->num_rows>0)
                            {
                                while($rowqry = $qry->fetch_assoc())
                                {
                                    extract($rowqry);
                                    if($questype==2)
                                    {
                                        $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                    }
                                    $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master 
                                                                                            where fld_id='".$testid."' and fld_delstatus='0';");

                                    $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master 
                                                                                    WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$expid."' 
                                                                                    AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='19' 
                                                                                    AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                    if(trim($tchpointcnt)=='')
                                    {
                                        $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                            WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                            AND a.fld_schedule_id='".$scheduleid."' 
                                                                                            AND a.fld_test_id='".$testid."' AND a.fld_schedule_type='19' AND b.fld_delstatus = '0' 
                                                                                            AND a.fld_delstatus = '0' AND a.fld_retake='0' ");

                                        if(trim($correctcountfortestattend) != '')
                                        {
                                            $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                            WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                            AND a.fld_schedule_id='".$scheduleid."' 
                                                                                            AND a.fld_test_id='".$testid."' AND a.fld_schedule_type='19' AND b.fld_delstatus = '0' AND a.fld_show = '1' 
                                                                                            AND a.fld_delstatus = '0' AND a.fld_retake='0' ");

                                            $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                            $possiblepointfortest+=$possiblepointfortest1;
                                        }
                                    }
                                    else
                                    {
                                        $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                            WHEN fld_lock = '0' THEN fld_points_earned
                                                                                            WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                    END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."'
                                                                                            AND fld_exp_id='".$expid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                            AND fld_schedule_type='19' AND fld_grade='1' AND fld_res_id='".$testid."' 
                                                                                            AND fld_exptype='3'");
                                        if($tchpointearn!='')
                                        {
                                             $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                            $possiblepointfortest+=$possiblepointfortest1;
                                        }
                                    }
                                }
                            }
                        }
                        /*********Post Test Code End Here*********/
                    }
                }
                /************** Pre/Post test code end here ***************/   

                /************** Rubric code start here ***************/
                $schoolid=$ObjDB->SelectSingleValueInt("SELECT fld_school_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 

                $sendistid=$ObjDB->SelectSingleValueInt("SELECT fld_district_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 

                $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                    CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                        LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                        LEFT JOIN itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                                        LEFT JOIN itc_class_rotation_expschedule_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
                                                            WHERE a.fld_class_id='".$id[1]."' AND a.fld_schedule_id='".$scheduleid."' AND b.fld_exp_id='".$expid."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                                AND a.fld_schedule_type='17' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                                AND b.fld_school_id IN(0,".$schoolid.")");

                if($qryrub->num_rows>0)
                {
                    while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
                    {
                        extract($rowqryrub);

                        $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master 
                                                                            WHERE fld_exp_id='".$expid."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                        $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_expsch_rubric_rpt WHERE fld_exp_id='".$expid."'  
                                                                                AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$id[1]."' 
                                                                                AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' "); 

                        $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_expsch_rubric_rpt_statement
                                                                                WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$expid."' AND fld_delstatus='0'
                                                                                AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                        $pointsearnedrubric = $pointsearnedrubric+$studentscore;
                        if($studentscore!=0)
                        {

                                $pointspossiblerubric = $pointspossiblerubric+$totscore;

                        }
                    }
                }
                /************** Rubric code end here ***************/
                $schexptearned=round($pointsearnedfortest + $pointsearnedrubric,2);
                $schexptpossible=$possiblepointfortest + $pointspossiblerubric;
            }
        }
        else
        {
                $schexptearned = '';
                $schexptpossible = '';
        }
            
    /**********Expedition and Module Schedule code developed by Mohan M 22-3-2016****************/
    $qryexpormod= $ObjDB->QueryObject("SELECT 
                                        d.fld_id AS scheduleid,
                                        b.fld_module_id AS expid
                                    FROM
                                        itc_class_rotation_modexpschedulegriddet AS b
                                            LEFT JOIN
                                        itc_class_rotation_modexpscheduledate AS c ON b.fld_schedule_id = c.fld_schedule_id
                                            AND b.fld_rotation = c.fld_rotation
                                            LEFT JOIN
                                        itc_class_rotation_modexpschedule_mastertemp AS d ON c.fld_schedule_id = d.fld_id
                                        WHERE
                                        b.fld_student_id = '".$studentid."' AND b.fld_type='2'
                                            AND b.fld_class_id = '".$id[1]."'
                                            AND b.fld_flag = '1'
                                            AND c.fld_flag = '1'
                                            AND d.fld_delstatus = '0' ".$sqry2."");

    if($qryexpormod->num_rows>0)
    {
        $expmodschexptearned = '';
	$expmodschexptpossible = '';
	$pointsearnedfortest=0;
	$possiblepointfortest1=0;
	$possiblepointfortest=0;
	$pointsearnedrubric=0;
	$pointspossiblerubric=0;
        while($rowqryexpormod = $qryexpormod->fetch_assoc())
        {
            extract($rowqryexpormod);
            
            /*************EXP pre test or POST test Code Start Here**************/
            $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                    WHERE fld_class_id='".$id[1]."' AND fld_sch_id='".$scheduleid."' AND fld_texpid='".$expid."' AND fld_schtype_id='20'");

            if($qrytest->num_rows>0)
            {
                while($rowqrytest = $qrytest->fetch_assoc())
                {
                    extract($rowqrytest);
                    $exptype='3';

                    /*********Pre Test Code start Here*********/
                    if($pretest!='0')
                    {
                        $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                        FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_delstatus='0'");
                        if($qry->num_rows>0)
                        {
                            while($rowqry = $qry->fetch_assoc())
                            {
                                extract($rowqry);
                                if($questype==2)
                                {
                                    $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                }
                                $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."' 
                                                                                    AND fld_exp_id='".$expid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                    AND fld_schedule_type='20' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");
                                if(trim($tchpointcnt)=='')
                                {
                                    $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                         WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$studentid."' 
                                                                                         AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                                                         AND a.fld_schedule_type='20' AND b.fld_delstatus = '0' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                    if(trim($correctcountfortestattend) != '')
                                    {
                                        $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                         WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$studentid."' 
                                                                                         AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                                                         AND a.fld_schedule_type='20' AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                        
                                        $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                        $possiblepointfortest+=$possiblepointfortest1;
                                    }
                                }
                                else
                                {
                                    $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                WHEN fld_lock = '0' THEN fld_points_earned
                                                                                WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                            END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."'
                                                                                AND fld_exp_id='".$expid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                AND fld_schedule_type='20' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");


                                    if($tchpointearn!='')
                                    {
                                        $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                        $possiblepointfortest+=$possiblepointfortest1;
                                    }
                                }
                            }
                        }
                    }
                    /*********Pre Test Code End Here*********/

                    /*********Post Test Code start Here*********/
                    if($posttest!='0')
                    {
                        $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                        FROM itc_test_master WHERE fld_id='".$posttest."' AND fld_delstatus='0'");
                        if($qry->num_rows>0)
                        {
                            while($rowqry = $qry->fetch_assoc())
                            {
                                extract($rowqry);
                                if($questype==2)
                                {
                                    $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                }
                                $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."' 
                                                                                    AND fld_exp_id='".$expid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                    AND fld_schedule_type='20' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");
                                if(trim($tchpointcnt)=='')
                                {
                                    $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                         WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$studentid."' 
                                                                                         AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                                                         AND a.fld_schedule_type='20' AND b.fld_delstatus = '0' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                    if(trim($correctcountfortestattend) != '')
                                    {
                                        $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                         WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$studentid."' 
                                                                                         AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                                                         AND a.fld_schedule_type='20' AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                        
                                        $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                        $possiblepointfortest+=$possiblepointfortest1;
                                    }
                                }
                                else
                                {
                                    $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                WHEN fld_lock = '0' THEN fld_points_earned
                                                                                WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                            END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$studentid."'
                                                                                AND fld_exp_id='".$expid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                AND fld_schedule_type='20' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");


                                    if($tchpointearn!='')
                                    {
                                        $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                        $possiblepointfortest+=$possiblepointfortest1;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            /*************EXP pre test or POST test Code End Here****************/ 
            
            /************** Rubric code start here ***************/
            $schoolid=$ObjDB->SelectSingleValueInt("SELECT fld_school_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 
            
            $sendistid=$ObjDB->SelectSingleValueInt("SELECT fld_district_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 

            $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                            CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                LEFT JOIN itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                                LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
                                                WHERE a.fld_class_id='".$id[1]."' AND a.fld_schedule_id='".$scheduleid."' AND b.fld_exp_id='".$expid."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                    AND a.fld_schedule_type='20' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                    AND b.fld_school_id IN(0,".$schoolid.")");

            if($qryrub->num_rows>0)
            {
                    while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
                    {
                            extract($rowqryrub);

                            $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master 
                                                    WHERE fld_exp_id='".$expid."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                            $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_expmodsch_rubric_rpt WHERE fld_exp_id='".$expid."'  
                                                            AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$id[1]."' 
                                                            AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' "); 

                            $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_expmodsch_rubric_rpt_statement
                                                                WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$expid."' AND fld_delstatus='0'
                                                                AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                            $pointsearnedrubric = $pointsearnedrubric+$studentscore;
                            if($studentscore!=0)
                            {
                                    $pointspossiblerubric = $pointspossiblerubric+$totscore;
                            }
                    }
            }
            $expmodschexptearned=round($pointsearnedfortest + $pointsearnedrubric,2);
            $expmodschexptpossible=$possiblepointfortest + $pointspossiblerubric;
        }
    }
    else
    {
        $expmodschexptearned = '';
        $expmodschexptpossible = '';
    }
    /**********Expedition and Module Schedule code developed by Mohan M 22-3-2016****************/           

    /************WCA Mission Developed by Mohan M 24-5-2016*************/
    $qrymis = $ObjDB->QueryObject("SELECT a.fld_id AS scheduleid, a.fld_mis_id AS misid 
                                    FROM itc_class_indasmission_master AS a 
                                    LEFT JOIN itc_mission_master AS b ON a.fld_mis_id=b.fld_id
                                    WHERE a.fld_class_id='".$id[1]."' AND a.fld_flag='1' AND a.fld_delstatus='0' 
                                    AND b.fld_delstatus='0' ".$sqry3."
                                    GROUP BY a.fld_id");
    if($qrymis->num_rows>0)
    {
        $mistearned = '';
        $mistpossible = '';
        while($rowqrymis = $qrymis->fetch_assoc())
        {
            extract($rowqrymis);

            $gradepointsearned = $ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned AS pointsearned FROM itc_mis_points_master 
                                                        WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$misid."' 
                                                            AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='18' AND fld_mistype='4'
                                                            AND fld_grade='1' AND fld_delstatus='0'");

            $gradepointspossible = $ObjDB->SelectSingleValueInt("SELECT fld_points_possible AS pointspossible FROM itc_mis_points_master 
                                                                    WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$misid."' 
                                                                        AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='18' AND fld_mistype='4'
                                                                        AND fld_grade='1' AND fld_delstatus='0'");

            /************** Rubric code start here ***************/
            $pointsearnedrubric=0;
            $pointspossiblerubric=0;

            $schoolid=$ObjDB->SelectSingleValueInt("SELECT fld_school_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 

            $sendistid=$ObjDB->SelectSingleValueInt("SELECT fld_district_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 

            $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                            CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                            LEFT JOIN itc_mis_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                            LEFT JOIN itc_mission_master AS c ON a.fld_expmisid = c.fld_id
                                            LEFT JOIN itc_class_indasmission_master AS d ON a.fld_schedule_id=d.fld_id 
                                                    WHERE a.fld_class_id='".$id[1]."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                            AND a.fld_schedule_type='18' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                            AND b.fld_school_id IN(0,".$schoolid.")");

            if($qryrub->num_rows>0)
            {
                while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
                {
                    extract($rowqryrub);

                    $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_master 
                                                                WHERE fld_mis_id='".$misid."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                    $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_rubric_rpt WHERE fld_mis_id='".$misid."'  
                                                                        AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$id[1]."' 
                                                                        AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' "); 

                    $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_rpt_statement
                                                                        WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$misid."' AND fld_delstatus='0'
                                                                        AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                    $pointsearnedrubric = $pointsearnedrubric+$studentscore;
                    if($studentscore!=0)
                    {
                        $pointspossiblerubric = $pointspossiblerubric+$totscore;
                    }
                }
            }
            /************** Rubric code end here ***************/
            /************** Test code start here ***************/
            $pointsearnedfortest=0;
            $possiblepointfortest1=0;
            $possiblepointfortest=0;

            $qrytest = $ObjDB->QueryObject("SELECT fld_test_id AS pretest,fld_mis_id AS misid FROM itc_mis_ass
                                                WHERE fld_class_id='".$id[1]."' AND fld_sch_id='".$scheduleid."' AND fld_schtype_id='18' AND fld_flag='1'");
            if($qrytest->num_rows>0)
            {
                while($rowqrytest = $qrytest->fetch_assoc())
                {
                    extract($rowqrytest);
                    $exptype='3';

                    $qryexp = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                        FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_delstatus='0'");
                    if($qryexp->num_rows>0)
                    {
                        while($rowqryexp = $qryexp->fetch_assoc())
                        {
                            extract($rowqryexp);
                            if($questype==2)
                            {
                                $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                            }
                            $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0'");

                            $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_mis_points_master WHERE fld_student_id='".$studentid."' 
                                                                            AND fld_mis_id='".$misid."' AND fld_schedule_id='".$scheduleid."' 
                                                                            AND fld_schedule_type='18' AND fld_grade='1' AND fld_res_id='".$testid."' 
                                                                            AND fld_mistype='3'");

                            if(trim($tchpointcnt)=='')
                            {
                                $correctcountfortestattend = $ObjDB->SelectSingleValueInt("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                            WHERE b.fld_mist = '".$misid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                AND a.fld_test_id='".$testid."'  AND a.fld_schedule_id='".$scheduleid."' 
                                                                                                AND a.fld_schedule_type='18' AND b.fld_delstatus = '0' 
                                                                                                AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                if(trim($correctcountfortestattend) != '')
                                {
                                    $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                            WHERE b.fld_mist = '".$misid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                AND a.fld_test_id='".$testid."'  AND a.fld_schedule_id='".$scheduleid."' 
                                                                                                AND a.fld_schedule_type='18' AND b.fld_delstatus = '0' 
                                                                                                AND a.fld_show = '1' AND a.fld_delstatus = '0' AND a.fld_retake='0'");
                                    
                                    $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                    $possiblepointfortest+=$possiblepointfortest1;
                                }
                            }
                            else
                            {
                                $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                WHEN fld_lock = '0' THEN fld_points_earned
                                                                                WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                            END) AS pointsearned FROM itc_mis_points_master WHERE fld_student_id='".$studentid."'
                                                                                AND fld_mis_id='".$misid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                AND fld_schedule_type='18' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_mistype='3'");
                                if($tchpointearn!='')
                                {
                                    $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                    $possiblepointfortest+=$possiblepointfortest1;
                                }
                            }
                        }
                    }
                }
            }
            /**************Test code end here ***************/
            
            $pointsearned=$gradepointsearned+$pointsearnedrubric + $pointsearnedfortest;;
            $pointspossible=$gradepointspossible+$pointspossiblerubric + $possiblepointfortest;

            if($pointsearned=='-')
            {
                $misearned = '';
                $mispossible = '';
            }
            else
            {
                $mistearned = $mistearned+$pointsearned;
                $mistpossible = $mistpossible+$pointspossible;
            }
        }
    }
    else
    {
        $mistearned = '';
        $mistpossible = '';
    }
    /************WCA Mission Developed by Mohan M 24-5-2016*************/

    $qrymissch= $ObjDB->QueryObject("SELECT d.fld_id AS scheduleid,b.fld_mission_id AS misid,23 AS typeids FROM itc_class_rotation_mission_schedulegriddet AS b
                                           LEFT JOIN itc_class_rotation_missionscheduledate AS c ON b.fld_schedule_id = c.fld_schedule_id AND b.fld_rotation = c.fld_rotation
                                           LEFT JOIN itc_class_rotation_mission_mastertemp AS d ON c.fld_schedule_id = d.fld_id
                                           WHERE b.fld_student_id = '".$studentid."' AND b.fld_class_id = '".$id[1]."' AND b.fld_flag = '1'
                                                   AND d.fld_delstatus = '0' ".$sqry2."");
    if($qrymissch->num_rows>0)
    {
        $misschexptearned = '';
        $misschexptpossible = '';
        while($rowqrymis = $qrymissch->fetch_assoc())
        {
            extract($rowqrymis);
            $pointearned1=0;
            $pointpossible1=0;
            $expstudentcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                    FROM itc_class_rotation_mission_student_mappingtemp
                                                                    WHERE fld_schedule_id='".$scheduleid."' 
                                                                            AND fld_student_id='".$studentid."' 
                                                                            AND fld_flag='1'");
            if($expstudentcount!=0)
            {
                $rotid=$z+1;                             
                $pointearned1 = $ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned AS pointsearned FROM itc_mis_points_master 
                                                                WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$misid."' 
                                                                    AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$typeids."' 
                                                                    AND fld_grade='1' AND fld_mistype='4'");

                $pointpossible1 = $ObjDB->SelectSingleValueInt("SELECT fld_points_possible AS pointsearned FROM itc_mis_points_master 
                                                                WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$misid."' 
                                                                    AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$typeids."' 
                                                                    AND fld_grade='1' AND fld_mistype='4'");


                /************** Rubric code start here ***************/
                $pointsearnedrubric=0;
                $pointspossiblerubric=0;

                $schoolid=$ObjDB->SelectSingleValueInt("SELECT fld_school_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 

                $sendistid=$ObjDB->SelectSingleValueInt("SELECT fld_district_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 
                $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                    CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                            LEFT JOIN itc_mis_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                            LEFT JOIN itc_mission_master AS c ON a.fld_expmisid = c.fld_id
                                                            LEFT JOIN itc_class_rotation_mission_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
                                                                WHERE a.fld_class_id='".$id[1]."' AND a.fld_schedule_id='".$scheduleid."' AND b.fld_mis_id='".$misid."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                                        AND a.fld_schedule_type='19' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                                        AND b.fld_school_id IN(0,".$schoolid.")");

                if($qryrub->num_rows>0)
                {
                    $totscore=0;
                    while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
                    {
                        extract($rowqryrub);

                       $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_master 
                                                                            WHERE fld_mis_id='".$misid."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    


                        $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_missch_rubric_rpt WHERE fld_mis_id='".$misid."'  
                                                                            AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$id[1]."' 
                                                                            AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' "); 

                        $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_missch_rubric_rpt_statement
                                                                            WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$misid."' AND fld_delstatus='0'
                                                                            AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                        $pointsearnedrubric = $pointsearnedrubric+$studentscore;
                        if($studentscore!=0)
                        {
                            $pointspossiblerubric = $pointspossiblerubric+$totscore;
                        }
                    }
                }
                /************** Rubric code end here ***************/
                
                /************** Test code start here ***************/
                $pointsearnedfortest=0;
                $possiblepointfortest1=0;
                $possiblepointfortest=0;

                $qrytest = $ObjDB->QueryObject("SELECT fld_test_id AS pretest,fld_mis_id AS misid FROM itc_mis_ass
                                                    WHERE fld_class_id='".$id[1]."' AND fld_sch_id='".$scheduleid."' AND fld_mis_id='".$misid."' AND fld_schtype_id='20' AND fld_flag='1'");
                if($qrytest->num_rows>0)
                {
                    while($rowqrytest = $qrytest->fetch_assoc())
                    {
                        extract($rowqrytest);
                        $exptype='3';

                        $qryexp = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                            FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_delstatus='0'");
                        if($qryexp->num_rows>0)
                        {
                            while($rowqryexp = $qryexp->fetch_assoc())
                            {
                                extract($rowqryexp);
                                if($questype==2)
                                {
                                    $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                }
                                $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0'");

                                $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_mis_points_master WHERE fld_student_id='".$studentid."' 
                                                                                AND fld_mis_id='".$misid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                AND fld_schedule_type='20' AND fld_grade='1' AND fld_res_id='".$testid."' 
                                                                                AND fld_mistype='3'");

                                if(trim($tchpointcnt)=='')
                                {
                                    $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                WHERE b.fld_mist = '".$misid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                    AND a.fld_test_id='".$testid."'  AND a.fld_schedule_id='".$scheduleid."' 
                                                                                                    AND a.fld_schedule_type='20' AND b.fld_delstatus = '0' 
                                                                                                    AND a.fld_delstatus = '0' AND a.fld_retake='0'");

                                    if(trim($correctcountfortestattend) != '')
                                    {
                                         $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                WHERE b.fld_mist = '".$misid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                    AND a.fld_test_id='".$testid."'  AND a.fld_schedule_id='".$scheduleid."' 
                                                                                                    AND a.fld_schedule_type='20' AND b.fld_delstatus = '0' 
                                                                                                    AND a.fld_show = '1' AND a.fld_delstatus = '0' AND a.fld_retake='0'");
                                        
                                        $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                        $possiblepointfortest+=$possiblepointfortest1;
                                    }
                                }
                                else
                                {
                                    $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                    WHEN fld_lock = '0' THEN fld_points_earned
                                                                                    WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                END) AS pointsearned FROM itc_mis_points_master WHERE fld_student_id='".$studentid."'
                                                                                    AND fld_mis_id='".$misid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                    AND fld_schedule_type='20' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_mistype='3'");
                                    if($tchpointearn!='')
                                    {
                                        $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                        $possiblepointfortest+=$possiblepointfortest1;
                                    }
                                }
                            }
                        }
                    }
                }
                /**************Test code end here ***************/

                $pointsearned = $pointearned1 + $pointsearnedrubric + $pointsearnedfortest;
                $pointspossible = $pointpossible1 + $pointspossiblerubric+ $possiblepointfortest;
                $misschexptearned = $misschexptearned + $pointsearned;
                $misschexptpossible = $misschexptpossible + $pointspossible;
            }

        }
    }
    else
    {
        $misschexptearned = '';
        $misschexptpossible = '';
    }

    $qrytest = $ObjDB->QueryObject("SELECT a.fld_id AS testid, a.fld_score AS testscore, a.fld_total_question AS ques, 
								IFNULL(b.fld_teacher_points_earned,'-') AS tearned, a.fld_question_type AS testtype
							FROM itc_test_master AS a 
							LEFT JOIN itc_test_student_mapping AS b ON b.fld_test_id=a.fld_id 
							WHERE b.fld_class_id='".$id[1]."' AND a.fld_flag='1' AND a.fld_delstatus='0' 
								 AND b.fld_student_id='".$studentid."' AND b.fld_flag='1' ".$sqry."");//AND a.fld_ass_type='0'
    if($qrytest->num_rows>0)
    {
        $pointsearned = '';
        while($rowqrytest = $qrytest->fetch_assoc())
        {
            extract($rowqrytest);
            if($tearned==='-' or $tearned==='')
            {

                $qcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) 
                                                            FROM itc_test_student_answer_track 
                                                            WHERE fld_student_id='".$studentid."' AND fld_test_id='".$testid."' AND fld_delstatus='0'  AND fld_schedule_id='0' AND fld_schedule_type='0'");

                if($testtype === '1')
                {
                    $correctcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) 
                                                                    FROM itc_test_student_answer_track 
                                                                    WHERE fld_student_id='".$studentid."' AND fld_test_id='".$testid."' 
                                                                    AND fld_correct_answer='1' AND fld_delstatus='0' AND fld_result_flag<>'2'  AND fld_schedule_id='0' AND fld_schedule_type='0'");

                    $parialanscnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) FROM itc_test_student_answer_track 
                                                                    WHERE fld_student_id='".$studentid."' AND fld_test_id='".$testid."' AND fld_delstatus='0' AND fld_result_flag='2'  AND fld_schedule_id='0' AND fld_schedule_type='0'");

                    $parialcnt = $ObjDB->QueryObject("SELECT fld_correct_answer as partans FROM itc_test_student_answer_track 
                                                        WHERE fld_student_id='".$studentid."' AND fld_test_id='".$testid."' AND fld_delstatus='0' AND fld_result_flag='2'  AND fld_schedule_id='0' AND fld_schedule_type='0'");

                    if($parialcnt->num_rows > 0) {
                        $dummyans_pt = 0;
                        while($rowqrypartialscore = $parialcnt->fetch_assoc())
                        {
                            extract($rowqrypartialscore);

                            $dummyans_pt = $dummyans_pt + $partans;
                        }
                    }

                    $testearned = $testearned+round((($correctcount/$ques)*$testscore));

                    if($dummyans_pt != 0) {
                        $testearned = $testearned + $dummyans_pt;
                    }
                }
                else if($testtype === '2')
                {
                    $randm_questn_earned = '';
                    $qryrandomtest = $ObjDB->QueryObject("SELECT fld_id AS testtagid, fld_pct_section AS percent, fld_qn_assign AS totques
                                                                        FROM itc_test_random_questionassign
                                                                        WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0' 
                                                                        ORDER BY fld_order_by");
                    if($qryrandomtest->num_rows>0)
                    {
                        $perscore = 0;
                        while($rowqryrandomtest = $qryrandomtest->fetch_assoc())
                        {
                            extract($rowqryrandomtest);                                                                                        
                            $perscore = $perscore + $percent;

                            $correctcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) 
                                                                                FROM itc_test_student_answer_track 
                                                                                WHERE fld_student_id='".$studentid."' AND fld_test_id='".$testid."' AND fld_tag_id='".$testtagid."'
                                                                                                AND fld_correct_answer='1' AND fld_delstatus='0' AND fld_result_flag<>'2'  AND fld_schedule_id='0' AND fld_schedule_type='0'");

                            $parialanscnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) FROM itc_test_student_answer_track 
                                                                            WHERE fld_student_id='".$studentid."' AND fld_test_id='".$testid."' AND fld_tag_id='".$testtagid."' AND fld_delstatus='0' AND fld_result_flag='2'  AND fld_schedule_id='0' AND fld_schedule_type='0'");

                            $parialcnt = $ObjDB->QueryObject("SELECT fld_correct_answer as partans FROM itc_test_student_answer_track 
                                                            WHERE fld_student_id='".$studentid."' AND fld_test_id='".$testid."' AND fld_tag_id='".$testtagid."' AND fld_delstatus='0' AND fld_result_flag='2'  AND fld_schedule_id='0' AND fld_schedule_type='0'");


                            if($parialcnt->num_rows > 0) {
                                $dummyans_pt_rand = 0;
                                while($rowqrypartialscore = $parialcnt->fetch_assoc())
                                {
                                    extract($rowqrypartialscore);
                                    $dummyans_pt_rand = $dummyans_pt_rand + $partans;

                                }
                            }


                            $randm_questn_earned = $randm_questn_earned+($correctcount*($percent/$totques));   


                            if($dummyans_pt_rand != 0) {
                                $randm_questn_earned = $randm_questn_earned + $dummyans_pt_rand;
                            }

                            $dummyans_pt_rand =0;                                                                             
                        }
                    }
                    $testearned = $testearned + round(($randm_questn_earned/$perscore) * $testscore);
                }  

                if($qcount===0)
                    $pointspossible = '';
                else
                    $pointspossible = $testscore;

                $testpossible = $testpossible+$pointspossible;
            }
            else
            {
                $testearned = $testearned+$tearned;
                $testpossible = $testpossible+$testscore;
            }
        }
    }
    else
    {
        $testearned = '';
        $testpossible = '';
    }
            
    $qryoverallpoints= $ObjDB->QueryObject("SELECT SUM(q.pointsearned) AS earned, SUM(q.pointspossible) AS possible FROM 
            (SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' THEN a.fld_teacher_points_earned END) AS pointsearned, 
                SUM(a.fld_points_possible) AS pointspossible FROM itc_assignment_sigmath_master AS a LEFT JOIN itc_class_sigmath_master AS b ON (a.fld_class_id=b.fld_class_id 
                AND a.fld_schedule_id=b.fld_id) WHERE b.fld_class_id='".$id[1]."' AND a.fld_student_id='".$studentid."' AND a.fld_test_type='1' AND b.fld_flag='1' 
                AND b.fld_delstatus='0' AND (a.fld_status='1' OR a.fld_status='2' OR a.fld_lock='1' OR a.fld_unitmark='1') AND a.fld_delstatus='0' AND a.fld_grade<>'0' ".$sqry."		
        UNION ALL			
            (SELECT ROUND(SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' THEN a.fld_teacher_points_earned END)/4) AS pointsearned, 
                ROUND(SUM(a.fld_points_possible)/4) AS pointspossible FROM `itc_assignment_sigmath_master` AS a 
                LEFT JOIN itc_class_rotation_schedulegriddet AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_schedule_id and a.fld_module_id=b.fld_module_id) 
                LEFT JOIN itc_class_rotation_scheduledate AS c ON b.fld_schedule_id=c.fld_schedule_id and b.fld_rotation=c.fld_rotation 
                WHERE b.fld_class_id = '".$id[1]."' AND a.fld_student_id = '".$studentid."' AND b.fld_student_id='".$studentid."' AND a.fld_test_type='2' 
                AND b.fld_flag='1' AND a.fld_delstatus='0' and b.fld_type='2' AND (a.fld_status='1' OR a.fld_status='2' OR a.fld_lock='1') 
                AND c.fld_flag='1' ".$sqry2." GROUP BY a.fld_schedule_id)
        UNION ALL		
            (SELECT ROUND(SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' THEN a.fld_teacher_points_earned END)/4) AS pointsearned, 
                ROUND(SUM(a.fld_points_possible)/4) AS pointspossible FROM itc_assignment_sigmath_master AS a 
                LEFT JOIN itc_class_indassesment_master AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_id) 
                WHERE b.fld_class_id='".$id[1]."' AND a.fld_student_id='".$studentid."' AND a.fld_test_type = '5' AND b.fld_moduletype='2' AND b.fld_flag='1' AND b.fld_delstatus='0' 
                AND a.fld_delstatus='0' AND (a.fld_status='1' OR a.fld_status='2' OR a.fld_lock='1') ".$sqry1." GROUP BY a.fld_schedule_id) 	
        UNION ALL		
            (SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' THEN a.fld_teacher_points_earned END) AS pointsearned, 
                SUM(a.fld_points_possible) AS pointspossible FROM itc_module_points_master AS a 
                LEFT JOIN `itc_class_rotation_schedulegriddet` AS b ON (a.fld_schedule_id=b.fld_schedule_id AND a.fld_module_id=b.`fld_module_id` AND a.fld_student_id = b.fld_student_id) 
                LEFT JOIN itc_class_rotation_scheduledate AS c ON b.fld_schedule_id=c.fld_schedule_id and b.fld_rotation=c.fld_rotation 
                WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$id[1]."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_grade<>'0' AND (fld_points_earned <> ''
                OR fld_teacher_points_earned <> '')
                AND a.fld_schedule_type IN ('1','4','8') AND c.fld_flag='1' ".$sqry2.")
        UNION ALL
        
            (SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' THEN a.fld_teacher_points_earned END) AS pointsearned,
                SUM(a.fld_points_possible) AS pointspossible FROM itc_module_points_master AS a 
                LEFT JOIN `itc_class_dyad_schedulegriddet` AS b ON (a.fld_schedule_id=b.fld_schedule_id AND a.fld_module_id=b.`fld_module_id` AND (a.fld_student_id=b.fld_student_id OR b.fld_rotation='0')) 
                 LEFT JOIN itc_class_dyad_schedulemaster as d on a.fld_schedule_id=d.fld_id
                WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$id[1]."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND (a.fld_points_earned <> '' or a.fld_teacher_points_earned <>'') AND a.fld_grade<>'0' 
                AND a.fld_schedule_type='2' AND d.fld_delstatus='0' ".$sqry1.") 		
        UNION ALL		
            (SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' THEN a.fld_teacher_points_earned END) AS pointsearned,
                SUM(a.fld_points_possible) AS pointspossible FROM itc_module_points_master AS a 
                LEFT JOIN `itc_class_triad_schedulegriddet` AS b ON (a.fld_schedule_id=b.fld_schedule_id AND a.fld_module_id=b.`fld_module_id` AND (a.fld_student_id=b.fld_student_id OR b.fld_rotation='0')) 
                LEFT JOIN itc_class_triad_schedulemaster as d on a.fld_schedule_id=d.fld_id
                WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$id[1]."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_grade<>'0' AND (a.fld_points_earned <> '' or a.fld_teacher_points_earned <>'')
                AND a.fld_schedule_type='3' AND d.fld_delstatus='0' ".$sqry1.") 
        UNION ALL 
            (SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' THEN a.fld_teacher_points_earned END) AS pointsearned, 
                SUM(a.fld_points_possible) AS pointspossible FROM itc_module_points_master AS a 
                LEFT JOIN itc_class_indassesment_master AS b ON (a.fld_schedule_id=b.fld_id AND a.fld_module_id=b.fld_module_id) 
                LEFT JOIN itc_class_indassesment_student_mapping AS c 
                ON (a.fld_schedule_id=c.fld_schedule_id AND a.fld_student_id=c.fld_student_id) WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$id[1]."' 
                        AND a.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_grade<>'0'  AND (a.fld_points_earned <> '' or a.fld_teacher_points_earned <>'') AND a.fld_schedule_type IN (5,6,7,17) AND c.fld_flag='1' AND b.fld_delstatus='0' ".$sqry1.") 
         UNION ALL 		
            (SELECT IFNULL(b.fld_points_earned,'-') AS pointsearned, a.fld_activity_points AS pointspossible
            FROM itc_activity_master AS a 
            LEFT JOIN itc_activity_student_mapping AS b ON b.fld_activity_id=a.fld_id 
            WHERE b.fld_student_id='".$studentid."' AND b.fld_class_id='".$id[1]."' AND b.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_points_earned<>'' ".$sqry.") 
        UNION ALL 
            (SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' THEN a.fld_teacher_points_earned END) AS pointsearned, 
                SUM(a.fld_points_possible) AS pointspossible FROM itc_module_points_master AS a 
                LEFT JOIN itc_class_rotation_modexpschedulegriddet AS b ON (a.fld_schedule_id=b.fld_schedule_id AND a.fld_module_id=b.`fld_module_id` AND a.fld_student_id = b.fld_student_id) 
                LEFT JOIN itc_class_rotation_modexpscheduledate AS c ON b.fld_schedule_id=c.fld_schedule_id and b.fld_rotation=c.fld_rotation
                LEFT JOIN itc_class_rotation_modexpschedule_mastertemp as d on a.fld_schedule_id=d.fld_id
                WHERE a.fld_student_id='".$studentid."'  AND b.fld_class_id='".$id[1]."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_grade<>'0'
                AND a.fld_schedule_type IN ('21','22') AND c.fld_flag='1' AND d.fld_delstatus='0' ".$sqry2.")
                ) AS q  
            ");
			
            $rowqryoverallpoints = $qryoverallpoints->fetch_object();

            if($rowqryoverallpoints->possible!='')
            {
                $pointsearned = round($rowqryoverallpoints->earned);
                $pointspossible = $rowqryoverallpoints->possible;
            }
            else
            {
                $pointsearned = " - ";
                $pointspossible = " - ";
            }
            $totalearned = $pointsearned+$exptearned+$schexptearned+$expmodschexptearned+$mistearned+$misschexptearned+$testearned;
            $totalpossible = $pointspossible+$exptpossible+$schexptpossible+$expmodschexptpossible+$mistpossible+$misschexptpossible+$testpossible ;
            if($totalearned!='')
            {
                    $totalearned = round($totalearned);
                    if($roundflag==0)
                            $percentage = round(($totalearned/$totalpossible)*100,2);
                    else
                            $percentage = round(($totalearned/$totalpossible)*100);

                    $perarray = explode('.',$percentage);
                    $grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
                                                                FROM itc_class_grading_scale_mapping 
                                                                WHERE fld_class_id = '".$id[1]."' AND fld_lower_bound <= '".$perarray[0]."' 
                                                                        AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
            }
            else
            {
                    $percentage = "-";
                    $grade = " N/A ";
            }

            if($totalearned!='') { $showearned = $totalearned; } else { $showearned = " - "; }
            if($totalpossible!='') { $showpossible = $totalpossible; } else { $showpossible = " - "; }

            $out .= $stuname." , ".$showearned." , ".$showpossible." , ".$percentage." % , ".$grade;
            $out .= "\n\n";
            if($cnt==0)
                    $cnt=1;
            else if($cnt==1)
                    $cnt=0;
        }		
    }
    else
    { 
            $out .= "No Records";
            $out .= "\n\n";
    }
}

//Individual Assignment report
if($id[0]==4)
{
    
	$name="Individual_Assignment";
	
	$csv_hdr = "";
	$out .= $csv_hdr;
	
	$qryclass = $ObjDB->QueryObject("SELECT fld_class_name AS classname, fld_period AS period 
												FROM itc_class_master 
												WHERE fld_id='".$id[1]."'");
			
	$row=$qryclass->fetch_assoc();
	extract($row);
		
	$ends = array('th','st','nd','rd','th','th','th','th','th','th');
	if (($period %100) >= 11 and ($period%100) <= 13)
	   $abbreviation = $period. 'th';
	else
	   $abbreviation = $period. $ends[$period % 10];
	  
	$out .= "Class Name : ,".$classname.' '.$abbreviation.' Period';
	$out .= "\n\n";
        
	if($id[2]==0)
	{
		$qrystudents = $ObjDB->QueryObject("SELECT a.fld_id AS stuids, CONCAT(a.fld_fname,' ',a.fld_lname) AS stunames
                                                            FROM itc_user_master AS a 
                                                            LEFT JOIN itc_class_student_mapping AS b ON a.fld_id=b.fld_student_id 
                                                            WHERE a.fld_activestatus='1' AND a.fld_delstatus='0' AND b.fld_class_id='".$id[1]."' AND b.fld_flag='1'
                                                            GROUP BY stuids ORDER BY a.fld_lname");
	}
	else
	{
		$qrystudents = $ObjDB->QueryObject("SELECT fld_id AS stuids, CONCAT(fld_fname,' ',fld_lname) AS stunames
                                                                FROM itc_user_master 
                                                                WHERE fld_id='".$id[2]."'");
	}
        
       
        if($qrystudents->num_rows > 0)
	{
		$scnt = 0;
		while($row=$qrystudents->fetch_assoc())
		{
			extract($row);
			$stuid[$scnt] = $stuids;
			$stuname[$scnt] = $stunames;
			$scnt++;
		}
	}
        
   for($stucnt=0;$stucnt<$scnt;$stucnt++)
   {
	$studentid = $stuid[$stucnt];
	$studentname = $stuname[$stucnt];
	
        if($id[6]==1 || $id[6]==0) 
        {
            $out .= $studentname;
            $out .= "\n\n";
        }
	if($id[5] == 0)
	{
		$qry = "SELECT (CASE WHEN a.fld_unitmark = '0' THEN CONCAT(c.fld_ipl_name,' ',d.fld_version) WHEN a.fld_unitmark = '1' THEN 'CGA Unit' END) AS assignname, b.fld_unit_name AS unitname, 
					(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' THEN a.fld_teacher_points_earned END) AS pointsearned, 1 AS grade, 
					a.fld_points_possible AS pointspossible 
				FROM itc_assignment_sigmath_master AS a 
				LEFT JOIN itc_unit_master AS b ON b.fld_id=a.fld_unit_id 
				LEFT JOIN itc_ipl_master AS c ON c.fld_id=a.fld_lesson_id 
				LEFT JOIN itc_ipl_version_track AS d ON d.fld_ipl_id=c.fld_id
				WHERE a.fld_class_id='".$id[1]."' AND a.fld_test_type='1' AND a.fld_student_id='".$studentid."' AND a.fld_rubrics_id='0' 
					AND a.fld_schedule_id='".$id[3]."' AND a.fld_unit_id='".$id[4]."' AND (a.fld_status='1' OR a.fld_status='2' OR a.fld_lock='1') 
					AND c.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_zip_type='1'";
	}
	else if($id[5]!=7 AND $id[5]!=15 AND $id[5]!=18 AND $id[5]!=19 AND $id[5]!= 20 AND $id[5]!=21 AND $id[5]!=22 AND $id[5]!=23) 
	{
		$qry = "SELECT fld_session_id, fld_type, (CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned
                                        END) AS pointsearned, fld_points_possible AS pointspossible, fld_grade AS grade, fld_preassment_id  
                                 FROM itc_module_points_master 
                                 WHERE fld_student_id='".$studentid."' AND fld_module_id='".$id[4]."' AND fld_schedule_id='".$id[3]."' 
                                        AND fld_schedule_type='".$id[5]."' AND fld_type<>'3' AND fld_delstatus='0'
                                 GROUP BY fld_session_id, fld_type
                                 UNION ALL
                         SELECT fld_session_id, fld_type, (CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned
                                END) AS pointsearned, fld_points_possible AS pointspossible, fld_grade AS grade, fld_preassment_id
                         FROM itc_module_points_master 
                         WHERE fld_student_id='".$studentid."' AND fld_module_id='".$id[4]."' AND fld_schedule_id='".$id[3]."' 
                                AND fld_schedule_type='".$id[5]."' AND fld_type='3' AND fld_delstatus='0'";
	}
        else if($id[5]==15)//Expedition
	{
            $qry = "SELECT fld_exp_name FROM itc_exp_master WHERE fld_id='".$id[4]."' AND fld_delstatus='0'";
	}
        else if($id[5]==18)//Mission
	{
            $qry = "SELECT fld_mis_name FROM itc_mission_master WHERE fld_id='".$id[4]."' AND fld_delstatus='0'";
	}
	else if($id[5]==19)
	{
            $qry = "SELECT fld_exp_name FROM itc_exp_master WHERE fld_id='".$id[4]."' AND fld_delstatus='0'";
	}
        else if($id[5]==20)
	{
            $qry = "SELECT fld_exp_name FROM itc_exp_master WHERE fld_id='".$id[4]."' AND fld_delstatus='0'";
	}
	else if($id[5]==21 || $id[5]==22)
	{
            $qry = "SELECT fld_session_id, fld_type, (CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned
                                    END) AS pointsearned, fld_points_possible AS pointspossible, fld_grade AS grade, fld_preassment_id  
                                    FROM itc_module_points_master 
                                            WHERE fld_student_id='".$studentid."' AND fld_module_id='".$id[4]."' AND fld_schedule_id='".$id[3]."' 
                                                    AND fld_schedule_type='".$id[5]."' AND fld_type<>'3' AND fld_delstatus='0'
                                                    GROUP BY fld_session_id, fld_type
                            UNION ALL
                                    SELECT fld_session_id, fld_type, (CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned
                                    END) AS pointsearned, fld_points_possible AS pointspossible, fld_grade AS grade, fld_preassment_id
                                            FROM itc_module_points_master 
                                                    WHERE fld_student_id='".$studentid."' AND fld_module_id='".$id[4]."' AND fld_schedule_id='".$id[3]."' 
                                                    AND fld_schedule_type='".$id[5]."' AND fld_type='3' AND fld_delstatus='0'";
	}
        else if($id[5]==23)//Mission
	{
            $qry = "SELECT fld_mis_name FROM itc_mission_master WHERE fld_id='".$id[4]."' AND fld_delstatus='0'";
	}
	else
	{
            $qry = "SELECT a.fld_session_id, a.fld_preassment_id, a.fld_type, (CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
                            THEN a.fld_teacher_points_earned END) AS pointsearned, a.fld_points_possible AS pointspossible, a.fld_grade AS grade, 
                            b.fld_page_title AS assignname  
                    FROM itc_module_points_master AS a 
                    LEFT JOIN itc_module_quest_details AS b ON (a.fld_module_id=b.fld_module_id AND a.fld_session_id=b.fld_section_id 
                            AND a.fld_preassment_id=b.fld_page_id)
                    WHERE a.fld_student_id='".$studentid."' AND a.fld_module_id='".$id[4]."' AND a.fld_schedule_id='".$id[3]."' 
                            AND a.fld_schedule_type='".$id[5]."' AND a.fld_delstatus='0' AND b.fld_flag='1' GROUP BY b.fld_page_id";
	}
	
	$qryindividual= $ObjDB->QueryObject($qry);

	$cntchk="";
		
	if($qryindividual->num_rows > 0)
	{ 
            if($id[5] == 0)
            {
                    $out .= "\n";
                    $out .= "IPLs , Points Earned , Points Possible";
                    $out .= "\n";
            }
            if($id[5]==15 || $id[5] == 19 || $id[5]==20 || $id[5]==18 || $id[5]==23) // || $id[5]==21
            {
                    $out .= "\n";
                    $out .= "Assesment Name , Points Earned , Points Possible";
                    $out .= "\n";
            }
            if($id[5]==4 || $id[5]==6)
            {
                    $qrymath = $ObjDB->QueryObject("SELECT fld_session_day1, fld_session_day2, fld_ipl_day1, fld_ipl_day2 
                                                                FROM itc_mathmodule_master 
                                                                WHERE fld_id='".$id[4]."'");
                    $rowqrymath=$qrymath->fetch_assoc();
                    extract($rowqrymath);
            }
            if($id[5]==8 || $id[5]==22)
            {
                    $out .= "\n";
                    $out .= "Custom Content , Points Earned , Points Possible";
                    $out .= "\n";

                    $assignname = $ObjDB->SelectSingleValue("SELECT fld_contentname
                                                                                FROM itc_customcontent_master 
                                                                                WHERE fld_id='".$id[4]."'");
                    $grade = 1;
            }

            $cnt=0;

            $counts = 0;
            $totpointsearned=0;
            $totpointspossible=0;
			
			$cal=1;
			$graphcal=20;
			$orient=42;
		
            while($rowqryindividual=$qryindividual->fetch_assoc())
            {	
                $counts++;			
                extract($rowqryindividual);
                if($id[5] == 15 || $id[5] == 19  || $id[5] == 20) // Expedition
                {
                    /************** Rubric code start here ***************/
                    $schoolid=$ObjDB->SelectSingleValueInt("SELECT fld_school_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 

                    $sendistid=$ObjDB->SelectSingleValueInt("SELECT fld_district_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 
                    if($id[5] == 15)
                    {
                        $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                        CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                        LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                        LEFT JOIN itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                        LEFT JOIN itc_class_indasexpedition_master AS d ON a.fld_schedule_id=d.fld_id 
                                            WHERE a.fld_class_id='".$id[1]."' AND a.fld_schedule_id='".$id[3]."' AND b.fld_exp_id='".$id[4]."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                AND a.fld_schedule_type='15' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                AND b.fld_school_id IN(0,".$schoolid.")");

                    }
                    else if($id[5] == 19)
                    {
                        $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                        CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                                LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                                LEFT JOIN itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                                                LEFT JOIN itc_class_rotation_expschedule_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
                                                                        WHERE a.fld_class_id='".$id[1]."' AND a.fld_schedule_id='".$id[3]."' AND b.fld_exp_id='".$id[4]."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                                                AND a.fld_schedule_type='17' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                                                AND b.fld_school_id IN(0,".$schoolid.")");
                    }
                    else if($id[5] == 20)
                    {
                        $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                            CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                                    LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                                    LEFT JOIN itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                                                    LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
                                                                    WHERE a.fld_class_id='".$id[1]."' AND a.fld_schedule_id='".$id[3]."' AND b.fld_exp_id='".$id[4]."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                                            AND a.fld_schedule_type='20' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                                            AND b.fld_school_id IN(0,".$schoolid.")");					

                    }
                    if($qryrub->num_rows>0)
                    {
                        while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
                        {
                            extract($rowqryrub);

                            $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master 
                                                                        WHERE fld_exp_id='".$id[4]."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    
                            
                            $destinationids = $ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_exp_rubric_dest_master WHERE fld_rubric_name_id='".$rubricids."' AND fld_exp_id='".$id[4]."'"); 
                            
                            if($id[5] == 15)
                            {
                                
                                $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_rpt WHERE fld_exp_id='".$id[4]."'  
                                                                                        AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$id[1]."' 
                                                                                        AND fld_schedule_id='".$id[3]."' AND fld_delstatus='0' "); 

                                $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_rpt_statement
                                                                                        WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$id[4]."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
                                                                                        AND fld_rubric_rpt_id='".$rubricrptid."'"); 
                            }
                            else if($id[5] == 19)
                            {
                                $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_expsch_rubric_rpt WHERE fld_exp_id='".$id[4]."'  
                                                                                    AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$id[1]."' 
                                                                                    AND fld_schedule_id='".$id[3]."' AND fld_delstatus='0' "); 

                                $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_expsch_rubric_rpt_statement
                                                                                    WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$id[4]."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
                                                                                        AND fld_rubric_rpt_id='".$rubricrptid."'"); 
                            }
                            else if($id[5] == 20)
                            {
                                $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_expmodsch_rubric_rpt WHERE fld_exp_id='".$id[4]."'  
                                                                                AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$id[1]."' 
                                                                                AND fld_schedule_id='".$id[3]."' AND fld_delstatus='0' "); 

                                $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_expmodsch_rubric_rpt_statement
                                                                                    WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$id[4]."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
                                                                                    AND fld_rubric_rpt_id='".$rubricrptid."'"); 			

                            }

                            if($studentscore==0)
                            {
                                $pointsearned = '-';
                            }
                            else
                            {
                                $pointsearned=$studentscore;
                            }
                            
                            $totpointsearned=$totpointsearned+$pointsearned;
                            $totpointspossible=$totpointspossible+$totscore;

                            $out .= $rubnam." ,". $pointsearned." ,". $totscore;
                            $out .= "\n";
                        }
                    }
                    /************** Rubric code end here ***************/
                    /*************EXP pre test or POST test Code Start Here**************/
                    $exptype='3';

                     $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                            WHERE fld_class_id='".$id[1]."' AND fld_sch_id='".$id[3]."' AND fld_texpid='".$id[4]."' AND fld_schtype_id='".$id[5]."'");

                    if($qrytest->num_rows>0)
                    {
                        while($rowqrytest = $qrytest->fetch_assoc())
                        {
                            extract($rowqrytest);
                            $exptype='3';
                            /*********Pre Test Code start Here*********/
                            if($pretest!='0')
                            {
                                 $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                                FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_delstatus='0'");

                                if($qry->num_rows>0)
                                {
                                     while($rowqry = $qry->fetch_assoc())
                                     {
                                        extract($rowqry);
                                        if($questype==2)
                                        {
                                            $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                        }
                                        $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");


                                        $correctcountstu="-";
                                        $crctcntstu='-';
                                        
                                        $qrycorrectcount = $ObjDB->QueryObject("SELECT a.fld_correct_answer AS crctcount FROM itc_test_student_answer_track AS a
                                                                                    LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                       WHERE b.fld_expt = '".$id[4]."' AND a.fld_student_id = '".$id[2]."' AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$id[3]."' AND a.fld_schedule_type='".$id[5]."'
                                                                                       AND b.fld_delstatus = '0'  AND a.fld_delstatus = '0' AND a.fld_retake='0'");//AND a.fld_show = '1'

                                        if($qrycorrectcount->num_rows>0)
                                        {
                                            while($rowqrycorrectcount = $qrycorrectcount->fetch_assoc())
                                            {
                                                extract($rowqrycorrectcount);
                                                $correctcountstu=$correctcountstu+$crctcount;
                                                $crctcntstu=$crctcntstu+$crctcount;
                                            }
                                        }
                                        
                                        /*****Teacher Points earned code start here for pre/post test*****/
                                        $tpointsearned = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned 
                                                                                        FROM itc_exp_points_master 
                                                                                        WHERE fld_schedule_type='".$id[5]."' AND fld_student_id='".$id[2]."' 
                                                                                            AND fld_exp_id='".$id[4]."' AND fld_schedule_id='".$id[3]."' AND fld_exptype='3' AND fld_res_id='".$testid."' AND fld_delstatus = '0'");
                                        if(trim($tpointsearned)=='')// ||  $tpointsearned=='0'
                                        {

                                            if($crctcntstu=='0')
                                            {
                                                $pointsearned = '0';
                                            }
                                            else if($crctcntstu=='-')
                                            {
                                                $pointsearned = '';
                                            }
                                            else 
                                            {
                                                $pointsearned = round($crctcntstu*($possiblepoint/$quescount),2);
                                            }

                                        }
                                        else
                                        {
                                            $pointsearned=$tpointsearned;
                                        }
                                        
                                        $totpointsearned=$totpointsearned+$pointsearned;
                                        $totpointspossible=$totpointspossible+$possiblepointfortest1;

                                        $out .= $testname." ,". $pointsearned ." ,". $possiblepointfortest1;
                                        $out .= "\n";
                                     }
                                }
                            }
                            /*********Pre Test Code End Here*********/
            
                            /*********Post Test Code start Here*********/
                            if($posttest!='0')
                            {
                                $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                                FROM itc_test_master WHERE fld_id='".$posttest."' AND fld_delstatus='0'");

                                if($qry->num_rows>0)
                                {
                                     while($rowqry = $qry->fetch_assoc())
                                     {
                                        extract($rowqry);
                                        if($questype==2)
                                        {
                                            $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                        }
                                        $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                        $correctcountstu="-";
                                        $crctcntstu='-';
                                        
                                        $qrycorrectcount = $ObjDB->QueryObject("SELECT a.fld_correct_answer AS crctcount FROM itc_test_student_answer_track AS a
                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                               WHERE b.fld_expt = '".$id[4]."' AND a.fld_student_id = '".$id[2]."' AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$id[3]."' AND a.fld_schedule_type='".$id[5]."'
                                                                                               AND b.fld_delstatus = '0'  AND a.fld_delstatus = '0' AND a.fld_retake='0'");//AND a.fld_show = '1'

                                        if($qrycorrectcount->num_rows>0)
                                        {
                                            while($rowqrycorrectcount = $qrycorrectcount->fetch_assoc())
                                            {
                                                extract($rowqrycorrectcount);
                                                $correctcountstu=$correctcountstu+$crctcount;
                                                $crctcntstu=$crctcntstu+$crctcount;
                                            }
                                        }
                                        
                                        /*****Teacher Points earned code start here for pre/post test*****/
                                        $tpointsearned = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned 
                                                                                        FROM itc_exp_points_master 
                                                                                        WHERE fld_schedule_type='".$id[5]."' AND fld_student_id='".$id[2]."' 
                                                                                            AND fld_exp_id='".$id[4]."' AND fld_schedule_id='".$id[3]."' AND fld_exptype='3' AND fld_res_id='".$testid."' AND fld_delstatus = '0'");
                                        if(trim($tpointsearned)=='')// ||  $tpointsearned=='0'
                                        {
                                            if($crctcntstu=='0')
                                            {
                                                $pointsearned = '0';
                                            }
                                            else if($crctcntstu=='-')
                                            {
                                                 $pointsearned = '';
                                            }
                                            else 
                                            {
                                                $pointsearned = round($crctcntstu*($possiblepoint/$quescount),2);
                                            }
                                        }
                                        else
                                        {
                                            $pointsearned=$tpointsearned;
                                        }
                                        
                                        $totpointsearned=$totpointsearned+$pointsearned;
                                        $totpointspossible=$totpointspossible+$possiblepointfortest1;

                                        $out .= $testname." ,". $pointsearned ." ,". $possiblepointfortest1;
                                        $out .= "\n";
                                     }
                                }
                            }
                             /*********Post Test Code End Here*********/
                        }
                    }
					
                    // created by chandra Expedition

                    if($counts==$qryindividual->num_rows)
                    {
                            $out .= "\n";
                            $out .= "Total".",".round($totpointsearned).",".$totpointspossible;
                            $out .= "\n";
                    }
                    
                    }
                    if($id[5]==18 || $id[5]==23) // Mission
                    {
                        $schoolid=$ObjDB->SelectSingleValueInt("SELECT fld_school_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 

                        $sendistid=$ObjDB->SelectSingleValueInt("SELECT fld_district_id FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'"); 

                        $gradepointsearned = $ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned AS pointsearned FROM itc_mis_points_master 
                                                                                            WHERE fld_student_id='".$id[2]."' AND fld_mis_id='".$id[4]."' 
                                                                                                AND fld_schedule_id='".$id[3]."' AND fld_schedule_type='".$id[5]."' AND fld_mistype='4'
                                                                                                AND fld_grade='1' AND fld_delstatus='0'");

                        $gradepointspossible = 100;

                        $totpointsearned=$totpointsearned+$gradepointsearned;
                        $totpointspossible=$totpointspossible+$gradepointspossible;

                        if($gradepointsearned==0)
                                $gradepointsearned = '-';
                            else
                                $gradepointspossible=$gradepointspossible;

                            $out .= "Participation ,". $gradepointsearned." ,". $gradepointspossible;
                            $out .= "\n";


                        if($id[5] == 18)
                        {
                            $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                            CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                            LEFT JOIN itc_mis_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                            LEFT JOIN itc_mission_master AS c ON a.fld_expmisid = c.fld_id
                                                            LEFT JOIN itc_class_indasmission_master AS d ON a.fld_schedule_id=d.fld_id 
                                                                WHERE a.fld_class_id='".$id[1]."' AND b.fld_mis_id='".$id[4]."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                                    AND a.fld_schedule_type='18' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                                    AND b.fld_school_id IN(0,".$schoolid.")");

                        }
                        else if($id[5] == 23)
                        {

                            $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                            CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                            LEFT JOIN itc_mis_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                            LEFT JOIN itc_mission_master AS c ON a.fld_expmisid = c.fld_id
                                                            LEFT JOIN itc_class_rotation_mission_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
                                                                WHERE a.fld_class_id='".$id[1]."' AND b.fld_mis_id='".$id[4]."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                                    AND a.fld_schedule_type='19' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                                    AND b.fld_school_id IN(0,".$schoolid.")");
                        }

                        if($qryrub->num_rows>0)
                        {
                            while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
                            {
                                extract($rowqryrub);

                                $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_master 
                                                                                WHERE fld_mis_id='".$id[4]."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                                if($id[5] == 18)
                                {

                                    $destinationids = $ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_mis_rubric_dest_master WHERE fld_rubric_name_id='".$rubricids."' AND fld_mis_id='".$id[4]."'"); 

                                    $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_rubric_rpt WHERE fld_mis_id='".$id[4]."'  
                                                                                            AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$id[1]."'  
                                                                                            AND fld_schedule_id='".$id[3]."' AND fld_delstatus='0' "); 

                                    $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_rpt_statement
                                                                                            WHERE fld_student_id='".$id[2]."' AND fld_mis_id='".$id[4]."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
                                                                                            AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                                }
                                else if($id[5] == 23)
                                {

                                    $destinationids = $ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_mis_rubric_dest_master WHERE fld_rubric_name_id='".$rubricids."' AND fld_mis_id='".$id[4]."'"); 

                                    $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_missch_rubric_rpt WHERE fld_mis_id='".$id[4]."'  
                                                                                        AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$id[1]."' 
                                                                                        AND fld_schedule_id='".$id[3]."' AND fld_delstatus='0' "); 

                                    $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_missch_rubric_rpt_statement
                                                                                        WHERE fld_student_id='".$id[2]."' AND fld_mis_id='".$id[4]."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
                                                                                        AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                                }

                                $totpointsearned=$totpointsearned+$studentscore;
                                $totpointspossible=$totpointspossible+$totscore;

                                if($studentscore==0)
                                        $pointsearned = '-';
                                else
                                        $pointsearned=$studentscore;

                                $out .= $rubnam." ,". $pointsearned." ,". $totscore;
                                $out .= "\n";

                            }
                        }
                        /************** Rubric code end here ***************/

                        /************* Test Code Start Here**************/
                        $exptype='3';

                        $qrytest = $ObjDB->QueryObject("SELECT fld_test_id AS pretest,fld_mis_id AS misid FROM itc_mis_ass
                                                WHERE fld_class_id='".$id[1]."' AND fld_sch_id='".$id[3]."' AND fld_mis_id='".$id[4]."' AND fld_flag='1' AND (fld_schtype_id='18' OR fld_schtype_id='20')");

                        if($qrytest->num_rows>0)
                        {
                            while($rowqrytest = $qrytest->fetch_assoc())
                            {
                                extract($rowqrytest);
                                $exptype='3';
                                /*********Pre Test Code start Here*********/

                                $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                                FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_delstatus='0'");

                                if($qry->num_rows>0)
                                {
                                    while($rowqry = $qry->fetch_assoc())
                                    {
                                        extract($rowqry);
                                        if($questype==2)
                                        {
                                            $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                        }
                                        $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                        $correctcountstu="-";
                                        $crctcntstu='-';
                                        $qrycorrectcount = $ObjDB->QueryObject("SELECT a.fld_correct_answer AS crctcount FROM itc_test_student_answer_track AS a
                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                               WHERE b.fld_mist = '".$id[4]."' AND a.fld_student_id = '".$id[2]."' AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$id[3]."' AND (fld_schedule_type='18' OR fld_schedule_type='20')
                                                                                               AND b.fld_delstatus = '0' AND a.fld_delstatus = '0' AND a.fld_retake='0'");//AND a.fld_show = '1' 
                                        if($qrycorrectcount->num_rows>0)
                                        {
                                            while($rowqrycorrectcount = $qrycorrectcount->fetch_assoc())
                                            {
                                                extract($rowqrycorrectcount);
                                                $correctcountstu=$correctcountstu+$crctcount;
                                                $crctcntstu=$crctcntstu+$crctcount;
                                            }
                                        }

                                        /*****Teacher Points earned code start here for pre/post test*****/
                                        $tpointsearned = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned 
                                                                                        FROM itc_mis_points_master
                                                                                        WHERE fld_student_id='".$id[2]."' AND (fld_schedule_type='18' OR fld_schedule_type='20')
                                                                                            AND fld_mis_id='".$id[4]."' AND fld_schedule_id='".$id[3]."' AND fld_mistype='3' AND fld_res_id='".$testid."' AND fld_delstatus = '0'");
                                        if(trim($tpointsearned)=='')// ||  $tpointsearned=='0'
                                        {
                                            if($crctcntstu=='0')
                                            {
                                                $pointsearned = '0';
                                            }
                                            else if($crctcntstu=='-')
                                            {
                                                 $pointsearned = '';
                                            }
                                            else 
                                            {
                                                $pointsearned = round($crctcntstu*($possiblepoint/$quescount),2);
                                            }
                                        }
                                        else
                                        {
                                            $pointsearned=$tpointsearned;
                                        }

                                        $totpointsearned=$totpointsearned+$pointsearned;
                                        $totpointspossible=$totpointspossible+$possiblepointfortest1;

                                        $out .= $testname." ,". $pointsearned ." ,". $possiblepointfortest1;
                                        $out .= "\n";
                                    }
                                }
                                else 
                                {
                                   ?>
                                    <tr class="trclass">
                                        <td colspan="3" class="tdleft tdright">No Test</td>

                                    </tr>
                                    <?php 
                                }
                            }
                        }
					
                        if($counts==$qryindividual->num_rows)
                        {
                                $out .= "\n";
                                $out .= "Total".",".round($totpointsearned).",".$totpointspossible;
                                $out .= "\n";
                        }
                    /************* Test Code end Here**************/
                }

                if($id[5]!=0 and $id[5]!=8 and $id[5]!=19 and $id[5]!=20 and $id[5]!=15 and $id[5]!=18 and $id[5]!=23)
                {
                    if($fld_type==1)
                    {
                        $assignname = "Attendance";
                    }
                    else if($fld_type==2)
                    {
                        $assignname = "Participation";
                    }
                    else if($fld_type==3)
                    {
                        $assignname = $ObjDB->SelectSingleValue("SELECT fld_performance_name FROM itc_module_performance_master WHERE fld_id='".$fld_preassment_id."'");
                    }
                    else if($fld_type==0 and $id[5] != 7)
                    {
                        if($fld_session_id==0)
                                $assignname = "Module Guide";
                        else if($fld_session_id==1)
                                $assignname = "RCA 2";
                        else if($fld_session_id==2)
                                $assignname = "RCA 3";
                        else if($fld_session_id==3)
                                $assignname = "RCA 4";
                        else if($fld_session_id==4)
                                $assignname = "RCA 5";
                        else if($fld_session_id==6)
                                $assignname = "Post Test";
                    }

                    if($cntchk!=$fld_session_id)
                    {
                        $sessid=$fld_session_id+1; 
                        if($id[5] == 7)
                        {
                                $title = "Chapter ".$sessid;
                        }
                        else
                        {
                                if($fld_type==3)
                                        $title = "Performance Assessments";
                                else
                                        $title = "Session ".$sessid;
                        }

                        $out .= "\n";
                        $sessid=$fld_session_id+1;
                        $out .= $title." , Points Earned , Points Possible";
                        $out .= "\n";

                        $cntchk=$fld_session_id;
                        $cnt=0;
                    }
                    


                    if($grade==0) $notgrade = "  (Not Graded)"; else $notgrade = "  ";
                    $out .= $assignname.$notgrade." , ".$pointsearned." , ".$pointspossible;
                    $out .= "\n";
					
                    $totpointsearned=$totpointsearned+$pointsearned;
                    $totpointspossible=$totpointspossible+$pointspossible;

                    // created by chandra 
                    if($counts==$qryindividual->num_rows)
                    {
                            $out .= "\n";
                            $out .= "Total".",".round($totpointsearned).",".$totpointspossible;
                            $out .= "\n";
                    }
                }
				
                /* Created by chandra start here*/
                if($id[5] == 0) // IPL
                {
                    $totpointsearned=$totpointsearned+$pointsearned;
                    $totpointspossible=$totpointspossible+$pointspossible;

                    $out .= $assignname." ,". $pointsearned ." ,". $pointspossible;
                    $out .= "\n";

                    if($counts==$qryindividual->num_rows)
                    {
                        $checkflag = $ObjDB->SelectSingleValueInt("SELECT  fld_flag 
                                                                        FROM itc_class_sigmath_grademapping 
                                                                        WHERE fld_class_id='".$id[1]."' AND fld_schedule_id='".$id[3]."' 
                                                                                   AND fld_unit_id='".$id[4]."' AND fld_flag = '1'");
                        if($checkflag !='' )
                        {
                            if($id[4]!=$cal && $id[4]!=$graphcal && $id[4]!=$orient  )////new line
                            { 
                                $pointsearned = $ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned 
                                                                                    FROM itc_assignment_sigmath_master 
                                                                                    WHERE fld_class_id='".$id[1]."' AND fld_schedule_id='".$id[3]."' 
                                                                                                    AND fld_unit_id='".$id[4]."' AND fld_unitmark='1' 
                                                                                                                    AND fld_student_id='".$id[2]."' AND fld_delstatus='0'");

                                $pointspossible = $ObjDB->SelectSingleValueInt("SELECT  fld_mpoints 
                                                                                            FROM itc_class_sigmath_grademapping 
                                                                                    WHERE fld_class_id='".$id[1]."' AND fld_schedule_id='".$id[3]."' 
                                                                                                    AND fld_unit_id='".$id[4]."' AND fld_flag = '1'"); 

                                $totpointsearned=$totpointsearned+$pointsearned;
                                $totpointspossible=$totpointspossible+$pointspossible;

                                $out .= "Math Connection: ".$unitname." ,". $pointsearned ." ,". $pointspossible;
                                $out .= "\n";

                            }
                        }

                        $out .= "\n";
                        $out .= "Total".",".$totpointsearned.",".$totpointspossible;
                        $out .= "\n";

                    }

                }
                /* Created by chandra end here*/

                if($cnt==0)
                    $cnt=1;
                else if($cnt==1)
                    $cnt=0;

                if($id[5]==4 || $id[5]==6)
                {
                    if($id[5]==4)
                            $diagtype = '2';
                    else if($id[5]==6)
                            $diagtype = '5';

                    $sessids = $fld_session_id+1;
                    if($fld_session_day1 == $sessids or $fld_session_day2 == $sessids or $counts==$qryindividual->num_rows)
                    {
                        $daycount = $ObjDB->SelectSingleValueInt("SELECT fld_type 
                                                                        FROM itc_module_points_master 
                                                                        WHERE fld_student_id='".$studentid."' AND fld_module_id='".$id[4]."' AND fld_schedule_id='".$id[3]."'
                                                                                AND fld_schedule_type='".$id[5]."' AND fld_type<>'3' AND fld_delstatus='0' AND fld_session_id='".$fld_session_id."' 
                                                                        GROUP BY fld_session_id, fld_type DESC LIMIT 0,1");

                        if(($sessids==$fld_session_day1  or $fld_session_day1>$counts) and $fld_type==$daycount)
                        { 
                            $day = "Diagnostic Day1"; 
                            $earned = $ObjDB->SelectSingleValueInt("SELECT ROUND(SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END)/4) 
                                                                            FROM itc_assignment_sigmath_master 
                                                                            WHERE fld_schedule_id='".$id[3]."' AND fld_student_id='".$studentid."' AND fld_test_type='".$diagtype."' AND fld_module_id='".$id[4]."' AND fld_class_id='".$id[1]."' 
                                                                            AND fld_lesson_id IN (".$fld_ipl_day1.") AND fld_delstatus='0' AND (fld_status='1' OR fld_status='2' OR fld_lock='1')");

                            $totpointsearned=$totpointsearned+$earned;
                            $totpointspossible=$totpointspossible+100;
                            
                            if($earned!='')
                            {
                                $out .= $day." , Points Earned , Points Possible";
                                $out .= "\n";

                                $out .= $day." , ".$earned." , 100";
                                $out .= "\n\n";
                            }
                        } 

                        if(($sessids==$fld_session_day2  or ($fld_session_day2>$counts and $qryindividual->num_rows==$counts)) and $fld_type==$daycount)
                        { 
                            $day = "Diagnostic Day2"; 
                            $earned = $ObjDB->SelectSingleValueInt("SELECT ROUND(SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END)/4) 
                                                                        FROM itc_assignment_sigmath_master 
                                                                        WHERE fld_schedule_id='".$id[3]."' AND fld_student_id='".$studentid."' AND fld_test_type='".$diagtype."' AND fld_class_id='".$id[1]."' 
                                                                        AND fld_lesson_id IN (".$fld_ipl_day2.") AND fld_delstatus='0' AND (fld_status='1' OR fld_status='2')");

                            $totpointsearned=$totpointsearned+$earned;
                            $totpointspossible=$totpointspossible+100;
                            
                            if($earned!='')
                            {
                                $out .= $day." , Points Earned , Points Possible";
                                $out .= "\n";

                                $out .= $day." , ".$earned." , 100";
                                $out .= "\n\n";
                            }
                        }
                        
                        // created by Mohan 
                        if($counts==$qryindividual->num_rows)
                        {
                            $out .= "\n";
                            $out .= "Total".",".round($totpointsearned).",".$totpointspossible;
                            $out .= "\n";
                        }
                    }
                }
				
            }
		
	}
	else
	{ 
		$out .= "No Records";
		$out .= "\n\n";
	}
        $out .= "\n\n\n";
	   
	   	
   }
}

//Assessment report
if($id[0]==5)
{
	$name="Assessment_report";
	
	$csv_hdr = "";
	$out .= $csv_hdr;
	
	$classid = $id[2];
	
	$qryclass = $ObjDB->QueryObject("SELECT fld_class_name AS classname, fld_period AS period 
												FROM itc_class_master 
												WHERE fld_id='".$classid."'");
			
	$row=$qryclass->fetch_assoc();
	extract($row);
		
	$ends = array('th','st','nd','rd','th','th','th','th','th','th');
	if (($period %100) >= 11 and ($period%100) <= 13)
	   $abbreviation = $period. 'th';
	else
	   $abbreviation = $period. $ends[$period % 10];
	  
	$out .= "Class Name : ,".$classname.' '.$abbreviation.' Period';
	$out .= "\n\n";
	
	if($id[1]==0)
	{
		$qrystudents = $ObjDB->QueryObject("SELECT a.fld_id AS stuids, CONCAT(a.fld_fname,' ',a.fld_lname) AS stunames
											FROM itc_user_master AS a 
											LEFT JOIN itc_test_student_mapping AS b ON a.fld_id=b.fld_student_id 
											WHERE a.fld_activestatus='1' AND a.fld_delstatus='0' AND b.fld_class_id='".$classid."' AND b.fld_flag='1'
											GROUP BY stuids ORDER BY a.fld_lname");
	}
	else
	{
		$qrystudents = $ObjDB->QueryObject("SELECT fld_id AS stuids, CONCAT(fld_fname,' ',fld_lname) AS stunames
											FROM itc_user_master 
											WHERE fld_id='".$id[1]."'");
	}
	if($qrystudents->num_rows > 0)
	{
		$scnt = 0;
		while($row=$qrystudents->fetch_assoc())
		{
			extract($row);
			$stuid[$scnt] = $stuids;
			$stuname[$scnt] = $stunames;
			$scnt++;
		}
	}
	$roundflag = $ObjDB->SelectSingleValue("SELECT fld_roundflag 
											FROM itc_class_grading_scale_mapping 
											WHERE fld_class_id = '".$classid."' AND fld_flag = '1' 
											GROUP BY fld_roundflag");
    
	for($stucnt=0;$stucnt<$scnt;$stucnt++)
	{
		$studentid = $stuid[$stucnt];
		$studentname = $stuname[$stucnt];
		
		$qryassement = $ObjDB->QueryObject("SELECT a.fld_id, a.fld_test_name, a.fld_score AS pointspossible, a.fld_total_question, a.fld_question_type AS testtype
											FROM itc_test_master AS a 
											LEFT JOIN itc_test_student_mapping AS b ON a.fld_id=b.fld_test_id 
											WHERE a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_student_id='".$studentid."' 
												AND b.fld_class_id='".$classid."' AND b.fld_flag='1' AND (a.fld_ass_type = '0'
        											or a.fld_ass_type = '1' or a.fld_ass_type = '2' )");    
		
		$qry = $ObjDB->QueryObject("SELECT a.fld_id, a.fld_score AS score, a.fld_total_question, a.fld_question_type 
									FROM itc_test_master AS a 
									LEFT JOIN itc_test_student_mapping AS b ON a.fld_id=b.fld_test_id 
									WHERE a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_student_id='".$studentid."' 
										AND b.fld_class_id='".$classid."' AND b.fld_flag='1'  AND (a.fld_ass_type = '0'
        											or a.fld_ass_type = '1' or a.fld_ass_type = '2' )");
		
		$totalpoints = '';
		$totalearned = '';
		while($rowqry = $qry->fetch_object())
		{
			$pointsearned = '';
			$pointspossible = $rowqry->score;
			$totalques = $rowqry->fld_total_question;
			$testtype = $rowqry->fld_question_type;
		
			$qcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
													FROM itc_test_student_answer_track 
													WHERE fld_student_id='".$studentid."' 
														AND fld_test_id='".$rowqry->fld_id."' AND fld_delstatus='0'");
		
			$teacherpoint = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned 
														FROM itc_test_student_mapping 
														WHERE fld_student_id='".$studentid."' AND fld_test_id='".$rowqry->fld_id."' 
															AND fld_flag='1' AND fld_class_id='".$classid."'");
			if($teacherpoint==='')
			{	
				if($testtype == '1')
				{
					$correctcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) 
																	FROM itc_test_student_answer_track 
																	WHERE fld_student_id='".$studentid."' AND fld_test_id='".$rowqry->fld_id."' 
																	AND fld_correct_answer='1' AND fld_delstatus='0'");
					
					$pointsearned = round(($correctcount/$totalques)*$pointspossible,2);
				}
				else if($testtype == '2')
				{
					$qryrandomtest = $ObjDB->QueryObject("SELECT fld_id AS testtagid, fld_pct_section AS percent, fld_qn_assign AS totques
															FROM itc_test_random_questionassign
															WHERE fld_rtest_id='".$rowqry->fld_id."' AND fld_delstatus='0' 
															ORDER BY fld_order_by");
					if($qryrandomtest->num_rows>0)
					{
						while($rowqryrandomtest = $qryrandomtest->fetch_assoc())
						{
							extract($rowqryrandomtest);
							
							$perscore = ($percent / 100)*$pointspossible;
							
							$correctcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) 
																			FROM itc_test_student_answer_track 
																			WHERE fld_student_id='".$studentid."' AND fld_test_id='".$rowqry->fld_id."' 
																				AND fld_tag_id='".$testtagid."' AND fld_correct_answer='1' 
																				AND fld_delstatus='0'");
							
							$pointsearned = $pointsearned + round($correctcount*($perscore/$totques));													
						}
					}
				}
			}
			else
			{
				$pointsearned = $teacherpoint;
			}
						
			if($pointsearned!='')
				$totalearned = $totalearned+$pointsearned;
			else
				$totalearned = $totalearned;
			
			if($qcount!=0)
			{
				$totalpoints = $totalpoints+$pointspossible;
				if($roundflag==0)
					$percentage = round(($totalearned/$totalpoints)*100,2);
				else
					$percentage = round(($totalearned/$totalpoints)*100);
				
				$perarray = explode('.',$percentage);
				
				$grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$classid."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
				
				$earnedtotal = round($totalearned,2);
				$pointstotal = $totalpoints;
			}
			else
			{
				if($totalearned=='')
				{
					$pointstotal = " - ";
					$earnedtotal = " - ";
					$percentage = " - ";
					$grade = " N/A ";
				}
			}
		}	
		
		$out .="\n";
		$out .= "Student :".$studentname." , , , , ".$grade;
		$out .="\n";
		$out .= " , , , , ".$percentage." % (".$earnedtotal." / ".$pointstotal.")";
		$out .="\n\n";	  
		
		$out .= "Assessment Name , Points Earned , Points Possible , Percentage , Grade ";
		$out .="\n";
		
		if($qryassement->num_rows > 0)
		{ 	 
			$cnt=0;
			$pointsearned = '';
			
			while($row=$qryassement->fetch_assoc())
			{
				$grade='';
				extract($row);
				
				$qcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
														FROM itc_test_student_answer_track 
														WHERE fld_student_id='".$studentid."' AND fld_test_id='".$fld_id."' AND fld_delstatus='0'");
		
				$teacherpoint = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned 
															FROM itc_test_student_mapping 
															WHERE fld_student_id='".$studentid."' AND fld_test_id='".$fld_id."' 
																AND fld_flag='1' AND fld_class_id='".$classid."'");
				if($teacherpoint=='')
				{
					if($testtype == '1')
					{
						$correctcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) 
																		FROM itc_test_student_answer_track 
																		WHERE fld_student_id='".$studentid."' AND fld_test_id='".$fld_id."' 
																			AND fld_correct_answer='1' AND fld_delstatus='0'");
						$pointsearned = round(($correctcount/$fld_total_question)*$pointspossible,2);				
					}
					else if($testtype == '2')
					{
						$qryrandomtest = $ObjDB->QueryObject("SELECT fld_id AS testtagid, fld_pct_section AS percent, fld_qn_assign AS totques
																FROM itc_test_random_questionassign
																WHERE fld_rtest_id='".$fld_id."' AND fld_delstatus='0' 
																ORDER BY fld_order_by");
						if($qryrandomtest->num_rows>0)
						{
							while($rowqryrandomtest = $qryrandomtest->fetch_assoc())
							{
								extract($rowqryrandomtest);
								
								$perscore = ($percent / 100)*$pointspossible;
								
								$correctcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) 
																				FROM itc_test_student_answer_track 
																				WHERE fld_student_id='".$studentid."' AND fld_test_id='".$fld_id."' 
																					AND fld_tag_id='".$testtagid."' AND fld_correct_answer='1' 
																					AND fld_delstatus='0'");
								
								$pointsearned = $pointsearned + round($correctcount*($perscore/$totques));
							}
						}
					}
					$showcount = $qcount;
				}
				else
				{
					$pointsearned = $teacherpoint;
					$showcount = 1;
				}
		
				if($showcount==0)
				{
					$pointsearned = "-";
					$pointspossible = "-";
					$percentage = "-";
					$grade = "NA";
				}
				else
				{
					if($roundflag==0)
						$percentage = round(($pointsearned/$pointspossible)*100,2);
					else
						$percentage = round(($pointsearned/$pointspossible)*100);
					
					$perarray = explode('.',$percentage);
					$grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
														FROM itc_test_grading_scale_mapping 
														WHERE fld_test_id='".$fld_id."' AND fld_flag='1' 
														AND fld_lower_bound<='".$perarray[0]."' AND fld_upper_bound>='".$perarray[0]."'");
				}
				
				$out .= $fld_test_name." , ".$pointsearned." , ".$pointspossible." , ".$percentage." , ".$grade." , ";
				$out .= "\n";
				
				if($cnt==0)
					$cnt=1;
				else if($cnt==1)
					$cnt=0;
			}
		}
		else
		{ 
			$out .= "No Records";
			$out .= "\n\n\n";
		} 
		$out .= "\n\n\n";
	}
}

//Pre/Post report
if($id[0]==6)
{
    $name="Pre_Post_report";

    $csv_hdr = "";
    $out .= $csv_hdr;

    $scheduleid = $id[1];
    $classid = $id[2];
    $schtype = $id[3];

    $qryclass = $ObjDB->QueryObject("SELECT fld_class_name AS classname, fld_period AS period 
                                                                                            FROM itc_class_master 
                                                                                            WHERE fld_id='".$classid."'");

    $row=$qryclass->fetch_assoc();
    extract($row);

    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if (($period %100) >= 11 and ($period%100) <= 13)
       $abbreviation = $period. 'th';
    else
       $abbreviation = $period. $ends[$period % 10];

    $out .= "Class Name : ,".$classname.' '.$abbreviation.' Period';
    $out .= "\n";

    if($schtype==1 or $schtype==4)
            $schtablename = "itc_class_rotation_schedule_mastertemp";
    else if($schtype==2)
            $schtablename = "itc_class_dyad_schedulemaster";
    else if($schtype==3)
            $schtablename = "itc_class_triad_schedulemaster";
    else if($schtype==5 || $schtype==6 || $schtype==7) /*****Mohan M****/
            $schtablename = "itc_class_indassesment_master";
    else if($schtype==15)
            $schtablename = "itc_class_indasexpedition_master";
    else if($schtype==18)
            $schtablename = "itc_class_indasmission_master";
    else if($schtype==19)
            $schtablename = "itc_class_rotation_expschedule_mastertemp";
    else if($schtype==20)
            $schtablename = "itc_class_rotation_modexpschedule_mastertemp";
     /***********Mohan M*********/
    $qrysch = $ObjDB->QueryObject("SELECT fld_schedule_name AS schname, fld_startdate AS startdate, fld_enddate AS enddate FROM ".$schtablename." WHERE fld_id='".$scheduleid."'");

    $rowqrysch = $qrysch->fetch_assoc();
    extract($rowqrysch);

    $out .= "Schedule Name : ,".$schname;
    $out .= "\n";
    $out .= "Date : ,".date("F d Y",strtotime($startdate)).' - '.date("F d Y",strtotime($enddate));
    $out .= "\n\n";
    if($schtype==20)
    {
        for($m=1;$m<=2;$m++)
        {
            if($m==1) // Module
            {
                $query = "SELECT fld_rotation AS rotation, fld_rotation-1 AS realrotation 
                                                FROM itc_class_rotation_modexpschedulegriddet 
                                                WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1' 
                                                GROUP BY fld_rotation 
                                                ORDER BY fld_rotation
                                                ";	//LIMIT ".$limit." 

                $querystudent = "SELECT a.fld_id AS studentid, CONCAT(a.fld_lname,' ',a.fld_fname) AS studentname
                                                                FROM itc_user_master AS a 
                                                                LEFT JOIN itc_class_rotation_modexpschedulegriddet AS b ON a.fld_id=b.fld_student_id 
                                                                WHERE b.fld_schedule_id = '".$scheduleid."' AND b.fld_class_id = '".$classid."' 
                                                                        AND b.fld_flag='1' AND a.fld_delstatus='0' AND a.fld_activestatus='1'
                                                                AND b.fld_type='".$m."' GROUP BY studentid
                                                                ORDER BY studentid";

                $tablename = "itc_class_rotation_modexpschedulegriddet";

                $out .= "Module Schedule";

                $qryrot = $ObjDB->QueryObject($query);
                $qrystudent = $ObjDB->QueryObject($querystudent);
                if($qryrot->num_rows>0)
                {
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
                    $out .= "\n";

                    $out .= "Student Name ";
                    for($i=0;$i<$cnt;$i++)
                    {
                            if($realrotationid[$i]==0) $titlename="Orientation"; else $titlename="Rotation ".$realrotationid[$i];
                            $out .=  " , ".$titlename;
                    }

                    $out .= "\n";

                    while($rowqrystudent = $qrystudent->fetch_assoc())
                    {
                        extract($rowqrystudent);
                         $out .= "\n";
                        $out .= $studentname;
                        $moduleids = array();
                        $schtypes = array();
                        for($j=0;$j<$cnt;$j++)
                        {
                            $rotids = $rotationid[$j];
                            $modulename = '';

                            $qrymod = $ObjDB->QueryObject("SELECT a.fld_module_id AS modids, CONCAT(b.fld_module_name,' ',c.fld_version) AS modulename, 21 AS newtype  
                                                                FROM itc_class_rotation_modexpschedulegriddet AS a 
                                                                LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
                                                                LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
                                                                WHERE a.fld_class_id='".$classid."' AND a.fld_student_id='".$studentid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                                        AND a.fld_rotation='".$rotids."' AND a.fld_flag='1' AND a.fld_type = '1' AND b.fld_delstatus='0' AND c.fld_delstatus='0'
                                                                                UNION ALL 		
                                                                SELECT a.fld_id AS modids, a.fld_contentname AS modulename, 22 AS newtype 
                                                                FROM itc_customcontent_master AS a 
                                                                LEFT JOIN itc_class_rotation_modexpschedulegriddet AS b ON b.fld_module_id = a.fld_id 
                                                                WHERE b.fld_class_id='".$classid."' AND b.fld_student_id='".$studentid."' AND b.fld_schedule_id = '".$scheduleid."' 
                                                                        AND fld_rotation='".$rotids."' AND b.fld_type = '8' AND b.fld_flag = '1' AND a.fld_delstatus = '0' ");

                            if($qrymod->num_rows>0)
                            {
                                while($rowqrymod = $qrymod->fetch_assoc())
                                {
                                    extract($rowqrymod);
                                    $moduleids[] = $modids;
                                    $schtypes[] = $newtype;

                                    if($modulename=='' AND $m!='2'){ $scname="No Module"; }else if($modulename=='' AND $m=='2'){ $scname="No Expedition";}else { $scname=$modulename; } 
                                    $out .= " , ".$scname;	
                                }
                            }
                            else
                            {
                                if($modulename=='' AND $m!='2'){ $scname="No Module"; }else if($modulename=='' AND $m=='2'){ $scname="No Expedition";}else { $scname=$modulename; }
                                $out .= " , ".$scname;	
                            }
                        }
                        $out .= "\n";
                        $out .= "Module Guide / Posttest ";
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

                            if($qrypoints->num_rows>0)
                            {
                                $rowqrypoints = $qrypoints->fetch_assoc();
                                extract($rowqrypoints);

                                $points = $moduleguide.' / '.$pretest;
                            }
                            else
                                $points = '- / -';
                            $out .= " , ".$points;
                        }
                    }
                    $out .= "\n";
                }
            }
            else if($m==2) // Expedition
            {
                $out .= "Expedition Schedule";
                $out .= "\n";

                $qryrotexpmodsch = $ObjDB->QueryObject("SELECT fld_rotation AS rotation, fld_rotation-1 AS realrotation 
                                        FROM itc_class_rotation_modexpschedulegriddet 
                                            WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1' 
                                            GROUP BY fld_rotation 
                                            ORDER BY fld_rotation
                                            ");//LIMIT ".$limit." 

                if($qryrotexpmodsch->num_rows>0)
                {
                    while($rowqryrotexpmodsch = $qryrotexpmodsch->fetch_assoc())
                    {
                        extract($rowqryrotexpmodsch);
                        $querystuexpmodsch = $ObjDB->QueryObject("SELECT a.fld_id AS studentid, CONCAT(a.fld_lname,' ',a.fld_fname) AS studentname
                                                                        FROM itc_user_master AS a 
                                                                        LEFT JOIN itc_class_rotation_modexpschedulegriddet AS b ON a.fld_id=b.fld_student_id 
                                                                        WHERE b.fld_schedule_id = '".$scheduleid."' AND b.fld_class_id = '".$classid."' AND fld_rotation='".$rotation."'
                                                                                AND b.fld_flag='1' AND a.fld_delstatus='0' AND a.fld_activestatus='1'
                                                                        AND b.fld_type='".$m."' GROUP BY studentid
                                                                        ORDER BY studentid");
                        if($querystuexpmodsch->num_rows>0)
                        {
                            while($rowquerystuexpmodsch = $querystuexpmodsch->fetch_assoc())
                            {
                                extract($rowquerystuexpmodsch);
                                if($realrotation==0) $rotname="Orientation"; else $rotname="Rotation ".$realrotation;
                                $out .= "\n";
                                $out .= "Rotation :,".$rotname;
                                $out .= "\n";
                                $out .= "Student Name : ,".$studentname;
                                $out .= "\n";
                                
                                $rotids = $rotation;

                                $qrymod = $ObjDB->QueryObject("SELECT a.fld_module_id AS modids, CONCAT(b.fld_exp_name,' ',c.fld_version) AS modulename, 20 AS newtype  
                                                                FROM itc_class_rotation_modexpschedulegriddet AS a 
                                                                LEFT JOIN itc_exp_master AS b ON a.fld_module_id=b.fld_id 
                                                                LEFT JOIN itc_exp_version_track AS c ON b.fld_id=c.fld_exp_id 
                                                                WHERE a.fld_class_id='".$classid."' AND (a.fld_student_id='".$studentid."' OR a.fld_rotation='0') 
                                                                        AND a.fld_schedule_id='".$scheduleid."' 
                                                                        AND a.fld_rotation='".$rotids."' AND a.fld_flag='1' AND a.fld_type = '2' AND b.fld_delstatus='0' AND c.fld_delstatus='0'");
                                if($qrymod->num_rows>0)
                                {
                                    while($rowqrymod = $qrymod->fetch_assoc())
                                    {
                                        extract($rowqrymod);
                                        $out .= $modulename;
                                        $out .= ", Points, Correct";
                                        $out .= "\n";
                                        
                                        /************** Pre/Post test code start here ***************/
                                        $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                                                            WHERE fld_class_id='".$classid."' AND fld_sch_id='".$scheduleid."' AND fld_texpid='".$modids."' AND fld_schtype_id='20'");

                                        if($qrytest->num_rows>0)
                                        {
                                            while($rowqrytest = $qrytest->fetch_assoc())
                                            {
                                                extract($rowqrytest);
                                                $exptype='3';

                                                /*********Pre Test Code start Here*********/
                                                if($pretest!='0')
                                                {
                                                    $qryexp = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                                                        FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_prepostid='1' AND fld_delstatus='0'");

                                                    if($qryexp->num_rows>0)
                                                    {
                                                        while($rowqry = $qryexp->fetch_assoc())
                                                        {
                                                            extract($rowqry);
                                                            if($questype==2)
                                                            {
                                                                $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                                            }
                                                            $ppost='Pretest';

                                                            $correctcountstu="-";
                                                            $crctcntstu='-';
                                                            $qrycorrectcount = $ObjDB->QueryObject("SELECT a.fld_correct_answer AS crctcount FROM itc_test_student_answer_track AS a
                                                                                                                LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                    WHERE b.fld_expt = '".$modids."' AND a.fld_student_id = '".$studentid."' AND a.fld_test_id='".$testid."' and a.fld_schedule_id='".$scheduleid."' and a.fld_schedule_type='20'
                                                                                                    AND b.fld_delstatus = '0' AND a.fld_delstatus = '0' AND a.fld_retake='0'");// AND a.fld_show = '1'
                                                            if($qrycorrectcount->num_rows>0)
                                                            {
                                                                while($rowqrycorrectcount = $qrycorrectcount->fetch_assoc())
                                                                {
                                                                    extract($rowqrycorrectcount);
                                                                    $correctcountstu=$correctcountstu+$crctcount;
                                                                    $crctcntstu=$crctcntstu+$crctcount;
                                                                }
                                                            }

                                                            /*****Teacher Points earned code start here for pre/post test*****/
                                                            $tpointsearned = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned 
                                                                                                            FROM itc_exp_points_master 
                                                                                                            WHERE fld_schedule_type='20' AND fld_student_id='".$studentid."' 
                                                                                                                AND fld_exp_id='".$modids."' AND fld_schedule_id='".$scheduleid."' AND fld_exptype='3' AND fld_res_id='".$testid."' AND fld_delstatus = '0'");
                                                            if(trim($tpointsearned)=='')// ||  $tpointsearned=='0'
                                                            {
                                                                if($crctcntstu=='0')
                                                                {
                                                                    $pointsearned = '0';
                                                                }
                                                                else if($crctcntstu=='-')
                                                                {
                                                                     $pointsearned = '';
                                                                }
                                                                else 
                                                                {
                                                                    $pointsearned = round($crctcntstu*($possiblepoint/$quescount),2);
                                                                }
                                                            }
                                                            else
                                                            {
                                                                $pointsearned=$tpointsearned;
                                                            }
                                                            /*****Teacher Points earned code End here for pre/post test*****/

                                                            if($correctcountstu>='0')
                                                            {
                                                                $stucorrectcount=$correctcountstu." / ".$quescount;
                                                                $totpercentage=round((($correctcountstu/$quescount)*100),2)." %";
                                                            }
                                                            else if($correctcountstu=='-')
                                                            {
                                                                $stucorrectcount='- / -';
                                                                $totpercentage='';
                                                            }

                                                            if($pointsearned=='')
                                                            {
                                                                $pointsearned='-';
                                                                $possiblepoint='-';
                                                            }
                                                            $titlename=$testname." / ".$ppost;
                                                            $points=$pointsearned." / ".$possiblepoint;
                                                            $out .= $titlename;
                                                            $out .= " , ".$points." , ".$stucorrectcount;
                                                            $out .= "\n";
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $out .= "No Pretest Assessments";
                                                        $out .= " , "." ";
                                                        $out .= "\n";
                                                    }
                                                }
                                                /*********Pre Test Code End Here*********/

                                                /*********Post Test Code start Here*********/
                                                if($posttest!='0')
                                                {
                                                    $qryexp = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                                                        FROM itc_test_master WHERE fld_id='".$posttest."' AND fld_prepostid='2' AND fld_delstatus='0'");

                                                    if($qryexp->num_rows>0)
                                                    {
                                                        while($rowqry = $qryexp->fetch_assoc())
                                                        {
                                                            extract($rowqry);
                                                            if($questype==2)
                                                            {
                                                                $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                                            }
                                                            $ppost='Posttest';

                                                            $correctcountstu="-";
                                                            $crctcntstu='-';
                                                            $qrycorrectcount = $ObjDB->QueryObject("SELECT a.fld_correct_answer AS crctcount FROM itc_test_student_answer_track AS a
                                                                                                                LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                    WHERE b.fld_expt = '".$modids."' AND a.fld_student_id = '".$studentid."' AND a.fld_test_id='".$testid."' and a.fld_schedule_id='".$scheduleid."' and a.fld_schedule_type='20'
                                                                                                    AND b.fld_delstatus = '0' AND a.fld_delstatus = '0'");//AND a.fld_show = '1' 

                                                            if($qrycorrectcount->num_rows>0)
                                                            {
                                                                while($rowqrycorrectcount = $qrycorrectcount->fetch_assoc())
                                                                {
                                                                    extract($rowqrycorrectcount);
                                                                    $correctcountstu=$correctcountstu+$crctcount;
                                                                    $crctcntstu=$crctcntstu+$crctcount;
                                                                }
                                                            }

                                                            /*****Teacher Points earned code start here for pre/post test*****/
                                                            $tpointsearned = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned 
                                                                                                            FROM itc_exp_points_master 
                                                                                                            WHERE fld_schedule_type='20' AND fld_student_id='".$studentid."' 
                                                                                                                AND fld_exp_id='".$modids."' AND fld_schedule_id='".$scheduleid."' AND fld_exptype='3' AND fld_res_id='".$testid."' AND fld_delstatus = '0'");
                                                            if(trim($tpointsearned)=='')// ||  $tpointsearned=='0'
                                                            {
                                                                if($crctcntstu=='0')
                                                                {
                                                                    $pointsearned = '0';
                                                                }
                                                                else if($crctcntstu=='-')
                                                                {
                                                                     $pointsearned = '';
                                                                }
                                                                else 
                                                                {
                                                                    $pointsearned = round($crctcntstu*($possiblepoint/$quescount),2);
                                                                }
                                                            }
                                                            else
                                                            {
                                                                $pointsearned=$tpointsearned;
                                                            }
                                                            /*****Teacher Points earned code End here for pre/post test*****/
                                                            if($correctcountstu>='0')
                                                            {
                                                                $stucorrectcount=$correctcountstu." / ".$quescount;
                                                                $totpercentage=round((($correctcountstu/$quescount)*100),2)." %";
                                                            }
                                                            else if($correctcountstu=='-')
                                                            {
                                                                $stucorrectcount='- / -';
                                                                $totpercentage='';
                                                            }

                                                            if($pointsearned=='')
                                                            {
                                                                $pointsearned='-';
                                                                $possiblepoint='-';
                                                            }
                                                            $titlename=$testname." / ".$ppost;
                                                            $points=$pointsearned." / ".$possiblepoint;
                                                            $out .= $titlename;
                                                            $out .= " , ".$points." , ".$stucorrectcount;
                                                            $out .= "\n";
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $out .= "No Post Test Assessments";
                                                        $out .= " , "." ";
                                                        $out .= "\n";
                                                    }
                                                }
                                            }
                                        } //test if condition
                                        else
                                        {
                                            $out .= "No Assessments";
                                            $out .= " , "." ";
                                            $out .= "\n";
                                        }
                                    }
                                } //Exp ids If Conditions
                            }
                        } //Stu If Condition

                    }
                }   // Rot If Condition
            }
        }// for Loop ENd Here
    }
    else
    {		
        if($schtype==1 || $schtype==4)
        {
            $query = "SELECT fld_rotation AS rotation, fld_rotation-1 AS realrotation 
                                    FROM itc_class_rotation_schedulegriddet 
                                    WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1' 
                                    GROUP BY fld_rotation 
                                    ORDER BY fld_rotation";	

            $querystudent = "SELECT a.fld_id AS studentid, CONCAT(a.fld_fname,' ',a.fld_lname) AS studentname
                                                    FROM itc_user_master AS a 
                                                    LEFT JOIN itc_class_rotation_schedulegriddet AS b ON a.fld_id=b.fld_student_id 
                                                    WHERE b.fld_schedule_id = '".$scheduleid."' AND b.fld_class_id = '".$classid."' 
                                                            AND b.fld_flag='1' AND a.fld_delstatus='0' AND a.fld_activestatus='1'
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
                                    ORDER BY fld_rotation";

            $querystudent = "SELECT a.fld_id AS studentid, CONCAT(a.fld_fname,' ',a.fld_lname) AS studentname
                                                    FROM itc_user_master AS a 
                                                    LEFT JOIN itc_class_dyad_schedulegriddet AS b ON a.fld_id=b.fld_student_id 
                                                    WHERE b.fld_schedule_id = '".$scheduleid."' AND b.fld_class_id = '".$classid."' 
                                                            AND b.fld_flag='1' AND a.fld_delstatus='0' AND a.fld_activestatus='1'
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
                                    ORDER BY fld_rotation";

            $querystudent = "SELECT a.fld_id AS studentid, CONCAT(a.fld_fname,' ',a.fld_lname) AS studentname
                                                    FROM itc_user_master AS a 
                                                    LEFT JOIN itc_class_triad_schedulegriddet AS b ON a.fld_id=b.fld_student_id 
                                                    WHERE b.fld_schedule_id = '".$scheduleid."' AND b.fld_class_id = '".$classid."' 
                                                            AND b.fld_flag='1' AND a.fld_delstatus='0' AND a.fld_activestatus='1'
                                                    GROUP BY studentid
                                                    ORDER BY studentid";

            $tablename = "itc_class_triad_schedulegriddet";
        }

        /*********WCA Expedition and Mission updated by Mohan M Code start here**********/
        else if($schtype==5 || $schtype==6 || $schtype==7)
        {

           $querystudent1 = "SELECT a.fld_id AS studentid, CONCAT(a.fld_fname,' ',a.fld_lname) AS studentname,c.fld_module_id AS modid
                                        FROM itc_user_master AS a 
                                        LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_student_id 
                                        LEFT JOIN itc_class_indassesment_master AS c ON c.fld_id = b.fld_schedule_id
                                        WHERE b.fld_schedule_id = '".$scheduleid."' 
                                                        AND b.fld_flag='1' AND a.fld_delstatus='0' AND a.fld_activestatus='1'
                                        GROUP BY studentid
                                        ORDER BY studentid";
        }
        else if($schtype==15)
        {
            $querystudent1 = "SELECT a.fld_id AS studentid, CONCAT(a.fld_fname,' ',a.fld_lname) AS studentname,c.fld_exp_id AS modid,c.fld_createdby As uid
                                            FROM itc_user_master AS a 
                                            LEFT JOIN itc_class_exp_student_mapping AS b ON a.fld_id=b.fld_student_id 
                                             LEFT JOIN itc_class_indasexpedition_master AS c ON c.fld_id = b.fld_schedule_id
                                            WHERE b.fld_schedule_id = '".$scheduleid."' 
                                                            AND b.fld_flag='1' AND a.fld_delstatus='0' AND a.fld_activestatus='1'
                                            GROUP BY studentid
                                                ORDER BY studentid";



        }
        else if($schtype==18)
        {
                $querystudent1 = "SELECT a.fld_id AS studentid, CONCAT(a.fld_fname,' ',a.fld_lname) AS studentname,c.fld_mis_id AS modid,c.fld_createdby As uid
                                                FROM itc_user_master AS a 
                                                LEFT JOIN itc_class_mission_student_mapping AS b ON a.fld_id=b.fld_student_id 
                                                 LEFT JOIN itc_class_indasmission_master AS c ON c.fld_id = b.fld_schedule_id
                                                WHERE b.fld_schedule_id = '".$scheduleid."' 
                                                                AND b.fld_flag='1' AND a.fld_delstatus='0' AND a.fld_activestatus='1'
                                                GROUP BY studentid
                                                ORDER BY studentid";


        }
        /*********WCA Expedition and Mission updated by Mohan M Code End here**********/     

        $qryrot = $ObjDB->QueryObject($query);
        $qrystudent = $ObjDB->QueryObject($querystudent);

        if($qryrot->num_rows>0)
        {
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

            $out .= "Student Name ";
            for($i=0;$i<$cnt;$i++)
            {
                if($realrotationid[$i]==0) $titlename="Orientation"; else $titlename="Rotation ".$realrotationid[$i];
                $out .=  " , ".$titlename;
            }

            $out .= "\n";
            while($rowqrystudent = $qrystudent->fetch_assoc())
            {
                extract($rowqrystudent);

                $out .= $studentname;

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
                                                            WHERE a.fld_class_id='".$classid."' AND a.fld_student_id='".$studentid."' 
                                                                    AND a.fld_schedule_id='".$scheduleid."' AND c.fld_delstatus='0'
                                                                    AND a.fld_rotation='".$rotids."' AND a.fld_flag='1' AND a.fld_type = '1' AND b.fld_delstatus='0' 														
                                                                            UNION ALL 		
                                                            SELECT a.fld_id AS modids, a.fld_contentname AS modulename, 8 AS newtype 
                                                            FROM itc_customcontent_master AS a 
                                                            LEFT JOIN itc_class_rotation_schedulegriddet AS b ON b.fld_module_id = a.fld_id 
                                                            WHERE b.fld_class_id='".$classid."' AND b.fld_student_id='".$studentid."' 
                                                                    AND b.fld_schedule_id = '".$scheduleid."' 
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
                                                            WHERE a.fld_class_id='".$classid."' AND a.fld_student_id='".$studentid."' 
                                                                    AND a.fld_schedule_id='".$scheduleid."' AND c.fld_delstatus='0'
                                                                    AND a.fld_rotation='".$rotids."' AND a.fld_flag='1' AND a.fld_type = '2' AND b.fld_delstatus='0' 														
                                                                            UNION ALL 		
                                                            SELECT a.fld_id AS modids, a.fld_contentname AS modulename, 8 AS newtype 
                                                            FROM itc_customcontent_master AS a 
                                                            LEFT JOIN itc_class_rotation_schedulegriddet AS b ON b.fld_module_id = a.fld_id 
                                                            WHERE b.fld_class_id='".$classid."' AND b.fld_student_id='".$studentid."' 
                                                                    AND b.fld_schedule_id = '".$scheduleid."' 
                                                                    AND fld_rotation='".$rotids."' AND b.fld_type = '8' AND b.fld_flag = '1' AND a.fld_delstatus = '0'");
                    }
                    else if($schtype==19) 
                    {
                        $qrymod = $ObjDB->QueryObject("SELECT a.fld_expedition_id AS modids, CONCAT(b.fld_exp_name,' ',c.fld_version) AS modulename, 19 AS newtype  
                                                            FROM itc_class_rotation_expschedulegriddet AS a 
                                                            LEFT JOIN itc_exp_master AS b ON a.fld_expedition_id=b.fld_id 
                                                            LEFT JOIN itc_exp_version_track AS c ON b.fld_id=c.fld_exp_id 
                                                            WHERE a.fld_class_id='".$classid."' AND (a.fld_student_id='".$studentid."' OR a.fld_rotation='0') 
                                                                    AND a.fld_schedule_id='".$scheduleid."' 
                                                                    AND a.fld_rotation='".$rotids."' AND a.fld_flag='1' AND b.fld_delstatus='0' AND c.fld_delstatus='0'");
                    }

                    $rowqrymod = $qrymod->fetch_assoc();
                    extract($rowqrymod);

                    $moduleids[] = $modids;
                    $schtypes[] = $newtype;

                    if($modulename=='' AND $schtype!=19){ $scname="No Module";}else if($modulename=='' AND $schtype==19){ $scname="No Expedition";} else $scname=$modulename;
                    $out .= " , ".$scname;
                }

                $out .= "\n";
                if($schtype!=19)
                {
                    $out .= "Module Guide / Posttest ";
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

                        $out .= " , ".$points;
                    }
                }
                else
                {
                    /************** Pre/Post test code start here ***************/
                    $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                            WHERE fld_class_id='".$classid."' AND fld_sch_id='".$scheduleid."' AND fld_texpid='".$modids."' AND fld_schtype_id='19'");

                    if($qrytest->num_rows>0)
                    {
                        while($rowqrytest = $qrytest->fetch_assoc())
                        {
                            extract($rowqrytest);
                            $exptype='3';

                            /*********Pre Test Code start Here*********/
                            if($pretest!='0')
                            {
                                 $qryexp = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint
                                                                    FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_prepostid='1' AND fld_delstatus='0'");

                                if($qryexp->num_rows>0)
                                {
                                    while($rowqry = $qryexp->fetch_assoc())
                                    {
                                        extract($rowqry);

                                         $ppost='Pretest';
                                        /*****Teacher Points earned code start here for pre/post test*****/
                                        $tpointsearned = $ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned 
                                                                                             FROM itc_exp_points_master 
                                                                                             WHERE fld_schedule_type='$schtype' AND fld_student_id='".$studentid."' 
                                                                                                             AND fld_exp_id='".$modids."' AND fld_schedule_id='".$scheduleid."' AND fld_exptype='3' AND fld_res_id='".$testid."' AND fld_delstatus = '0'");
                                        if($tpointsearned=='' ||  $tpointsearned=='0')
                                        {

                                             $correctcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                             LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                 WHERE b.fld_expt = '".$modids."' AND a.fld_student_id = '".$studentid."' AND a.fld_test_id='".$testid."' and a.fld_schedule_id='".$scheduleid."' and a.fld_schedule_type='19'
                                                                                                 AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");
                                             if($correctcount=='')
                                                 $pointsearned = '';
                                             else
                                                 $pointsearned = round($correctcount*($possiblepoint/$quescount),2);
                                        }
                                        else
                                        {
                                                $pointsearned=$tpointsearned;
                                        }
                                        /*****Teacher Points earned code End here for pre/post test*****/
                                        if($pointsearned=='')
                                        {
                                            $pointsearned='-';
                                            $possiblepoint='-';
                                        }

                                        $titlename=$testname." / ".$ppost;
                                        $points=$pointsearned." / ".$possiblepoint;
                                        $out .= $titlename;
                                        $out .= " , ".$points;
                                        $out .= "\n";
                                    }
                                }
                                else
                                {
                                         $out .= "No Assessments";
                                         $out .= " , "." ";
                                         $out .= "\n";

                                }
                            }
                            /*********Pre Test Code End Here*********/

                            /*********Post Test Code start Here*********/
                            if($posttest!='0')
                            {
                                $qryexp = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint
                                                                    FROM itc_test_master WHERE fld_id='".$posttest."' AND fld_prepostid='2' AND fld_delstatus='0'");

                                if($qryexp->num_rows>0)
                                {
                                    while($rowqry = $qryexp->fetch_assoc())
                                    {
                                        extract($rowqry);

                                        $ppost='Posttest';

                                        /*****Teacher Points earned code start here for pre/post test*****/
                                        $tpointsearned = $ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned 
                                                                                             FROM itc_exp_points_master 
                                                                                             WHERE fld_schedule_type='$schtype' AND fld_student_id='".$studentid."' 
                                                                                                             AND fld_exp_id='".$modids."' AND fld_schedule_id='".$scheduleid."' AND fld_exptype='3' AND fld_res_id='".$testid."' AND fld_delstatus = '0'");
                                        if($tpointsearned=='' ||  $tpointsearned=='0')
                                        {

                                             $correctcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                             LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                 WHERE b.fld_expt = '".$modids."' AND a.fld_student_id = '".$studentid."' AND a.fld_test_id='".$testid."' and a.fld_schedule_id='".$scheduleid."' and a.fld_schedule_type='19'
                                                                                                 AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");
                                             if($correctcount=='')
                                                 $pointsearned = '';
                                             else
                                                 $pointsearned = round($correctcount*($possiblepoint/$quescount),2);
                                        }
                                        else
                                        {
                                                $pointsearned=$tpointsearned;
                                        }
                                        /*****Teacher Points earned code End here for pre/post test*****/
                                        if($pointsearned=='')
                                        {
                                            $pointsearned='-';
                                            $possiblepoint='-';
                                        }

                                        $titlename=$testname." / ".$ppost;
                                        $points=$pointsearned." / ".$possiblepoint;
                                        $out .= $titlename;
                                        $out .= " , ".$points;
                                        $out .= "\n";
                                    }
                                }
                                else
                                {
                                    $out .= "No Assessments";
                                    $out .= " , "." ";
                                    $out .= "\n";
                                }
                            }
                        }
                    }
                }
                $out .= "\n";
            }
        }

        /*********WCA Expedition and Mission updated by Mohan M Code start here**********/
        $querystudent1 = $ObjDB->QueryObject($querystudent1);

        if($querystudent1->num_rows>0)
        {
            if($schtype==5)
            {
                $moduletype='1';
                $modulename=$ObjDB->SelectSingleValue("SELECT a.fld_module_name FROM  itc_module_master AS a
                                                            LEFT JOIN itc_class_indassesment_master AS b ON a.fld_id=b.fld_module_id
                                                            WHERE b.fld_class_id='".$classid."' AND b.fld_id='".$scheduleid."' AND b.fld_moduletype='".$moduletype."'
                                                             AND a.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_delstatus='0'");  
            }
            else if($schtype==6)
            {
                $moduletype='2';

                $modulename=$ObjDB->SelectSingleValue("SELECT a.fld_mathmodule_name FROM itc_mathmodule_master AS a
                                                    LEFT JOIN itc_class_indassesment_master AS b ON a.fld_id=b.fld_module_id
                                                          WHERE b.fld_class_id='".$classid."' AND b.fld_id='".$scheduleid."' AND b.fld_moduletype='".$moduletype."'
                                                           AND a.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_delstatus='0'");  
            }
            else if($schtype==7)
            {
                $moduletype='7';

                $modulename=$ObjDB->SelectSingleValue("SELECT a.fld_module_name FROM itc_module_master AS a
                                                            LEFT JOIN itc_class_indassesment_master AS b ON a.fld_id=b.fld_module_id
                                                            WHERE b.fld_class_id='".$classid."' AND b.fld_id='".$scheduleid."' AND b.fld_moduletype='".$moduletype."'
                                                             AND a.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_delstatus='0'");  
            }
            else if($schtype==15)
            {
                $modulename=$ObjDB->SelectSingleValue("SELECT a.fld_exp_name FROM  itc_exp_master AS a
                                                            LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_id=b.fld_exp_id
                                                            WHERE b.fld_class_id='".$classid."' AND b.fld_id='".$scheduleid."' AND b.fld_scheduletype='".$schtype."'
                                                             AND a.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_delstatus='0'");  
            }
            else if($schtype==18)
            {
                $modulename=$ObjDB->SelectSingleValue("SELECT a.fld_mis_name FROM  itc_mission_master AS a
                                                            LEFT JOIN itc_class_indasmission_master AS b ON a.fld_id=b.fld_mis_id
                                                            WHERE b.fld_class_id='".$classid."' AND b.fld_id='".$scheduleid."' AND b.fld_scheduletype='".$schtype."'
                                                             AND a.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_delstatus='0'");  
            }

            $out .= "Student Name ";
            $out .=  " , ".$modulename;
            $out .= "\n";
            while($rowquery = $querystudent1->fetch_assoc())
            {
                extract($rowquery);
                $out .= "\n";
                if($schtype==15)
                { 	
                    $out .= $studentname;
                    $out .= ", Points, Correct";
                    $out .= "\n";
                }
                else
                {
                    $out .= $studentname;
                    $out .= " , "." ";
                    $out .= "\n";
                }

                if($schtype==5 || $schtype==6 || $schtype==7)
                {	
                    $qrypoints = $ObjDB->QueryObject("SELECT IFNULL(GROUP_CONCAT(CASE WHEN fld_lock='1' AND fld_session_id='0' THEN fld_teacher_points_earned 
                                                                                                                                    WHEN fld_lock='0' AND fld_session_id='0' THEN fld_points_earned END),'- ') AS moduleguide,
                                                                                                                                    IFNULL(GROUP_CONCAT(CASE WHEN fld_lock='1' AND fld_session_id='0' THEN fld_points_possible 
                                                                                                                                    WHEN fld_lock='0' AND fld_session_id='0' THEN fld_points_possible END),' -') AS pretest
                                                                                                                    FROM itc_module_points_master 
                                                                                                                    WHERE fld_module_id='".$modid."' AND fld_schedule_type='".$schtype."' 
                                                                                                                                    AND fld_student_id='".$studentid."' AND fld_schedule_id='".$scheduleid."' AND fld_type='0'
                                                                                                                                    AND (fld_session_id='0' OR fld_session_id='6')");

                    if($qrypoints->num_rows>0)
                    {
                            $rowqrypoints = $qrypoints->fetch_assoc();
                            extract($rowqrypoints);
                            $points = $moduleguide.' / '.$pretest;
                    }
                    else
                            $points = '- / -';

                    $out .= "Module Guide / Posttest ";
                    $out .= " , ".$points;
                    $out .= "\n";
                }
                else if($schtype==15)
                {
                    /************** Pre/Post test code start here ***************/
                    $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                             WHERE fld_class_id='".$classid."' AND fld_sch_id='".$scheduleid."' AND fld_texpid='".$modid."'  AND fld_schtype_id='15'");

                     if($qrytest->num_rows>0)
                     {
                         while($rowqrytest = $qrytest->fetch_assoc())
                         {
                             extract($rowqrytest);
                             $exptype='3';
                             /*********Pre Test Code start Here*********/
                             if($pretest!='0')
                             {
                                $qryexp = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                    FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_prepostid='1' AND fld_delstatus='0'");

                                if($qryexp->num_rows>0)
                                {
                                    while($rowqry = $qryexp->fetch_assoc())
                                    {
                                        extract($rowqry);

                                        $ppost='Pretest';

                                        if($questype==2)
                                        {
                                            $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                        }
                                        $correctcountstu="-";
                                        $crctcntstu='-';
                                        $qrycorrectcount = $ObjDB->QueryObject("SELECT a.fld_correct_answer AS crctcount FROM itc_test_student_answer_track AS a
                                                                                                LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                       WHERE b.fld_expt = '".$modid."' AND a.fld_student_id = '".$studentid."' AND a.fld_test_id='".$testid."' and a.fld_schedule_id='".$scheduleid."' and a.fld_schedule_type='15'
                                                                                                       AND b.fld_delstatus = '0' AND a.fld_delstatus = '0'");//AND a.fld_show = '1' 
                                        if($qrycorrectcount->num_rows>0)
                                        {
                                            while($rowqrycorrectcount = $qrycorrectcount->fetch_assoc())
                                            {
                                                extract($rowqrycorrectcount);
                                                $correctcountstu=$correctcountstu+$crctcount;
                                                $crctcntstu=$crctcntstu+$crctcount;
                                            }
                                        }

                                        /*****Teacher Points earned code start here for pre/post test*****/
                                        $tpointsearned = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned 
                                                                                            FROM itc_exp_points_master 
                                                                                            WHERE fld_schedule_type='$schtype' AND fld_student_id='".$studentid."' 
                                                                                                    AND fld_exp_id='".$modid."' AND fld_schedule_id='".$scheduleid."' AND fld_exptype='3' AND fld_res_id='".$testid."' AND fld_delstatus = '0'");
                                        if(trim($tpointsearned)=='')// ||  $tpointsearned=='0'
                                        {
                                            if($crctcntstu=='0')
                                            {
                                                $pointsearned = '0';
                                            }
                                            else if($crctcntstu=='-')
                                            {
                                                 $pointsearned = '';
                                            }
                                            else 
                                            {
                                                $pointsearned = round($crctcntstu*($possiblepoint/$quescount),2);
                                            }
                                        }
                                        else
                                        {
                                            $pointsearned=$tpointsearned;
                                        }
                                        /*****Teacher Points earned code End here for pre/post test*****/

                                        if($correctcountstu>='0')
                                        {
                                            $stucorrectcount=$correctcountstu." / ".$quescount;
                                            $totpercentage=round((($correctcountstu/$quescount)*100),2)." %";
                                        }
                                        else if($correctcountstu=='-')
                                        {
                                            $stucorrectcount='- / -';
                                            $totpercentage='';
                                        }

                                        if($pointsearned==''){
                                            $pointsearned='-';
                                            $possiblepoint='-';
                                        }

                                        $titlename=$testname." / ".$ppost;
                                        $points=$pointsearned." / ".$possiblepoint;
                                        $out .= $titlename;
                                        $out .= " , ".$points." , ".$stucorrectcount;
                                        $out .= "\n";
                                    }
                                }
                                else
                                {
                                    $out .= "No Pretest Assessments";
                                    $out .= " , "." ";
                                    $out .= "\n";
                                }
                            }
                            /*********Pre Test Code End Here*********/

                            /*********Post Test Code start Here*********/
                            if($posttest!='0')
                            {
                                $qryexp = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                    FROM itc_test_master WHERE fld_id='".$posttest."' AND fld_prepostid='2' AND fld_delstatus='0'");

                                if($qryexp->num_rows>0)
                                {
                                    while($rowqry = $qryexp->fetch_assoc())
                                    {
                                        extract($rowqry);

                                        $ppost='Posttest';

                                        if($questype==2)
                                        {
                                            $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                        }
                                        $correctcountstu="-";
                                        $crctcntstu='-';
                                        $qrycorrectcount = $ObjDB->QueryObject("SELECT a.fld_correct_answer AS crctcount FROM itc_test_student_answer_track AS a
                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                WHERE b.fld_expt = '".$modid."' AND a.fld_student_id = '".$studentid."' AND a.fld_test_id='".$testid."' and a.fld_schedule_id='".$scheduleid."' and a.fld_schedule_type='15'
                                                                                                AND b.fld_delstatus = '0' AND a.fld_delstatus = '0'");//AND a.fld_show = '1' 
                                        if($qrycorrectcount->num_rows>0)
                                        {
                                            while($rowqrycorrectcount = $qrycorrectcount->fetch_assoc())
                                            {
                                                extract($rowqrycorrectcount);
                                                $correctcountstu=$correctcountstu+$crctcount;
                                                $crctcntstu=$crctcntstu+$crctcount;
                                            }
                                        }
                                        /*****Teacher Points earned code start here for pre/post test*****/
                                        $tpointsearned = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned 
                                                                                            FROM itc_exp_points_master 
                                                                                            WHERE fld_schedule_type='".$schtype."' AND fld_student_id='".$studentid."' 
                                                                                                    AND fld_exp_id='".$modid."' AND fld_schedule_id='".$scheduleid."' AND fld_exptype='3' AND fld_res_id='".$testid."' AND fld_delstatus = '0'");
                                        if(trim($tpointsearned)=='')// ||  $tpointsearned=='0'
                                        {
                                            if($crctcntstu=='0')
                                            {
                                                $pointsearned = '0';
                                            }
                                            else if($crctcntstu=='-')
                                            {
                                                 $pointsearned = '';
                                            }
                                            else 
                                            {
                                                $pointsearned = round($crctcntstu*($possiblepoint/$quescount),2);
                                            }
                                        }
                                        else
                                        {
                                            $pointsearned=$tpointsearned;
                                        }
                                        /*****Teacher Points earned code End here for pre/post test*****/

                                        if($correctcountstu>='0')
                                        {
                                            $stucorrectcount=$correctcountstu." / ".$quescount;
                                            $totpercentage=round((($correctcountstu/$quescount)*100),2)." %";
                                        }
                                        else if($correctcountstu=='-')
                                        {
                                            $stucorrectcount='- / -';
                                            $totpercentage='';
                                        }

                                        if($pointsearned=='')
                                        {
                                            $pointsearned='-';
                                            $possiblepoint='-';
                                        }
                                        $titlename=$testname." / ".$ppost;
                                        $points=$pointsearned." / ".$possiblepoint;
                                        $out .= $titlename;
                                        $out .= " , ".$points.", ".$stucorrectcount;
                                        $out .= "\n";
                                    }
                                }
                                else
                                {
                                    $out .= "No Post Test Assessments";
                                    $out .= " , "." ";
                                    $out .= "\n";
                                }
                            }
                        }
                    }
                } //Expedition code end here
                else if($schtype==18)
                {
                    /************** Test code start here ***************/
                    $qrytest = $ObjDB->QueryObject("SELECT fld_test_id AS pretest,fld_mis_id AS misid FROM itc_mis_ass
                                                        WHERE fld_class_id='".$classid."' AND fld_sch_id='".$scheduleid."' AND fld_mis_id='".$modid."' AND fld_flag='1'  AND fld_schtype_id='18'");

                    if($qrytest->num_rows>0)
                    {
                        while($rowqrytest = $qrytest->fetch_assoc())
                        {
                            extract($rowqrytest);
                            $exptype='3';

                            $qryexp = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint
                                                                FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_delstatus='0'");

                            if($qryexp->num_rows>0)
                            {
                                while($rowqry = $qryexp->fetch_assoc())
                                {
                                    extract($rowqry);
                                    $ppost='';


                                    /*****Teacher Points earned code start here for pre/post test*****/
                                    $tpointsearned = $ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned FROM itc_mis_points_master 
                                                                                        WHERE fld_schedule_type='$schtype' AND fld_student_id='".$studentid."' 
                                                                                            AND fld_mis_id='".$modid."' AND fld_schedule_id='".$scheduleid."' AND fld_mistype='3' "
                                            . "                                             AND fld_res_id='".$testid."' AND fld_delstatus = '0'");
                                    if($tpointsearned=='' ||  $tpointsearned=='0')
                                    {
                                            $correctcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                       WHERE b.fld_mist = '".$modid."' AND a.fld_student_id = '".$studentid."' AND a.fld_test_id='".$testid."' and a.fld_schedule_id='".$scheduleid."' and a.fld_schedule_type='18'
                                                                                                       AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");
                                            if($correctcount=='')
                                                $pointsearned = '';
                                            else
                                                $pointsearned = round($correctcount*($possiblepoint/$quescount),2);

                                    }
                                    else
                                    {
                                        $pointsearned=$tpointsearned;
                                    }
                                    /*****Teacher Points earned code End here for pre/post test*****/
                                    if($pointsearned==''){
                                            $pointsearned='-';
                                            $possiblepoint='-';
                                    }
                                    $titlename=$testname." / ".$ppost;
                                    $points=$pointsearned." / ".$possiblepoint;
                                    $out .= $titlename;
                                    $out .= " , ".$points;
                                    $out .= "\n";
                                }
                            }
                            else
                            {
                                $out .= "No Assessments";
                                $out .= " , "." ";
                                $out .= "\n";
                            }
                        }
                    }
                    /************** Test code start here ***************/
                }
            }
        }

        /*********WCA Expedition and Mission updated by Mohan M Code End here**********/

        /*********Expedition Schedule Developed by Mohan M Code start here 05-10-2016**********/  

        $qryrotexpsch = $ObjDB->QueryObject("SELECT fld_rotation AS rotation, fld_rotation-1 AS realrotation 
                                       FROM itc_class_rotation_expschedulegriddet 
                                       WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1' 
                                       GROUP BY fld_rotation 
                                       ORDER BY fld_rotation");	//LIMIT ".$limit." 
        if($qryrotexpsch->num_rows>0)
        {
            while($rowqryrotexpsch = $qryrotexpsch->fetch_assoc())
            {
                extract($rowqryrotexpsch);

                $qrystudentexpsch = $ObjDB->QueryObject("SELECT a.fld_id AS studentid, CONCAT(a.fld_lname,' ',a.fld_fname) AS studentname
                                                FROM itc_user_master AS a 
                                                LEFT JOIN itc_class_rotation_expschedulegriddet AS b ON a.fld_id=b.fld_student_id 
                                                WHERE b.fld_schedule_id = '".$scheduleid."' AND b.fld_class_id = '".$classid."' AND fld_rotation='".$rotation."'
                                                        AND b.fld_flag='1' AND a.fld_delstatus='0' AND a.fld_activestatus='1'
                                                GROUP BY studentid
                                                ORDER BY studentid");

                while($rowqrystudentexpsch = $qrystudentexpsch->fetch_assoc())
                {
                    extract($rowqrystudentexpsch);
                    if($realrotation==0) $rotname="Orientation"; else $rotname="Rotation ".$realrotation;
                    $out .= "\n";
                    $out .= "Rotation : ,".$rotname;
                    $out .= "\n";
                    $out .= "Student Name : ,".$studentname;
                    $out .= "\n";

                    $rotids = $rotation;
                    $qrymod = $ObjDB->QueryObject("SELECT a.fld_expedition_id AS modids, CONCAT(b.fld_exp_name,' ',c.fld_version) AS modulename, 19 AS newtype  
                                                        FROM itc_class_rotation_expschedulegriddet AS a 
                                                        LEFT JOIN itc_exp_master AS b ON a.fld_expedition_id=b.fld_id 
                                                        LEFT JOIN itc_exp_version_track AS c ON b.fld_id=c.fld_exp_id 
                                                        WHERE a.fld_class_id='".$classid."' AND (a.fld_student_id='".$studentid."' OR a.fld_rotation='0') 
                                                                AND a.fld_schedule_id='".$scheduleid."' 
                                                                AND a.fld_rotation='".$rotids."' AND a.fld_flag='1' AND b.fld_delstatus='0' AND c.fld_delstatus='0'");
                    if($qrymod->num_rows>0)
                    {
                        while($rowqrymod = $qrymod->fetch_assoc())
                        {
                            extract($rowqrymod);

                            $out .= $modulename;
                            $out .= ", Points, Correct";
                            $out .= "\n";

                            /************** Pre/Post test code start here ***************/
                            $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                                    WHERE fld_class_id='".$classid."' AND fld_sch_id='".$scheduleid."' AND fld_texpid='".$modids."' AND fld_schtype_id='19'");

                            if($qrytest->num_rows>0)
                            {
                                while($rowqrytest = $qrytest->fetch_assoc())
                                {
                                    extract($rowqrytest);
                                    $exptype='3';

                                    /*********Pre Test Code start Here*********/
                                    if($pretest!='0')
                                    {
                                        $qryexp = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                                                FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_prepostid='1' AND fld_delstatus='0'");

                                        if($qryexp->num_rows>0)
                                        {
                                            while($rowqry = $qryexp->fetch_assoc())
                                            {
                                                extract($rowqry);
                                                if($questype==2)
                                                {
                                                    $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                                }
                                                $ppost='Pretest';

                                                $correctcountstu="-";
                                                $crctcntstu='-';
                                                $qrycorrectcount = $ObjDB->QueryObject("SELECT a.fld_correct_answer AS crctcount FROM itc_test_student_answer_track AS a
                                                                                                    LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                               WHERE b.fld_expt = '".$modids."' AND a.fld_student_id = '".$studentid."' AND a.fld_test_id='".$testid."' and a.fld_schedule_id='".$scheduleid."' and a.fld_schedule_type='19'
                                                                                                               AND b.fld_delstatus = '0' AND a.fld_delstatus = '0'");//AND a.fld_show = '1' 
                                                if($qrycorrectcount->num_rows>0)
                                                {
                                                    while($rowqrycorrectcount = $qrycorrectcount->fetch_assoc())
                                                    {
                                                        extract($rowqrycorrectcount);
                                                        $correctcountstu=$correctcountstu+$crctcount;
                                                        $crctcntstu=$crctcntstu+$crctcount;
                                                    }
                                                }

                                                /*****Teacher Points earned code start here for pre/post test*****/
                                                $tpointsearned = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned 
                                                                                                    FROM itc_exp_points_master 
                                                                                                    WHERE fld_schedule_type='".$schtype."' AND fld_student_id='".$studentid."' 
                                                                                                                    AND fld_exp_id='".$modids."' AND fld_schedule_id='".$scheduleid."' AND fld_exptype='3' AND fld_res_id='".$testid."' AND fld_delstatus = '0'");
                                                if(trim($tpointsearned)=='')// ||  $tpointsearned=='0'
                                                {
                                                    if($crctcntstu=='0')
                                                    {
                                                        $pointsearned = '0';
                                                    }
                                                    else if($crctcntstu=='-')
                                                    {
                                                         $pointsearned = '';
                                                    }
                                                    else 
                                                    {
                                                        $pointsearned = round($crctcntstu*($possiblepoint/$quescount),2);
                                                    }
                                                }
                                                else
                                                {
                                                    $pointsearned=$tpointsearned;
                                                }
                                                /*****Teacher Points earned code End here for pre/post test*****/

                                                if($correctcountstu>='0')
                                                {
                                                    $stucorrectcount=$correctcountstu." / ".$quescount;
                                                    $totpercentage=round((($correctcountstu/$quescount)*100),2)." %";
                                                }
                                                else if($correctcountstu=='-')
                                                {
                                                    $stucorrectcount='- / -';
                                                    $totpercentage='';
                                                }


                                                if($pointsearned=='')
                                                {
                                                    $pointsearned='-';
                                                    $possiblepoint='-';
                                                }

                                                $titlename=$testname." / ".$ppost;
                                                $points=$pointsearned." / ".$possiblepoint;
                                                $out .= $titlename;
                                                $out .= " , ".$points." , ".$stucorrectcount;
                                                $out .= "\n";

                                            }
                                        }
                                        else
                                        {   
                                            $out .= "No Pretest Assessments";
                                            $out .= " , "." ";
                                            $out .= "\n";
                                        }
                                    }
                                    /*********Pre Test Code End Here*********/

                                    /*********Post Test Code start Here*********/
                                    if($posttest!='0')
                                    {
                                        $qryexp = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint,fld_question_type AS questype
                                                                                FROM itc_test_master WHERE fld_id='".$posttest."' AND fld_prepostid='2' AND fld_delstatus='0'");

                                        if($qryexp->num_rows>0)
                                        {
                                            while($rowqry = $qryexp->fetch_assoc())
                                            {
                                                extract($rowqry);
                                                $ppost='Posttest';

                                                if($questype==2)
                                                {
                                                    $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                                                }
                                                $correctcountstu="-";
                                                $crctcntstu='-';
                                                $qrycorrectcount = $ObjDB->QueryObject("SELECT a.fld_correct_answer AS crctcount FROM itc_test_student_answer_track AS a
                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                            WHERE b.fld_expt = '".$modids."' AND a.fld_student_id = '".$studentid."' AND a.fld_test_id='".$testid."' and a.fld_schedule_id='".$scheduleid."' and a.fld_schedule_type='19'
                                                                                            AND b.fld_delstatus = '0'  AND a.fld_delstatus = '0'");//AND a.fld_show = '1'
                                                if($qrycorrectcount->num_rows>0)
                                                {
                                                    while($rowqrycorrectcount = $qrycorrectcount->fetch_assoc())
                                                    {
                                                        extract($rowqrycorrectcount);
                                                        $correctcountstu=$correctcountstu+$crctcount;
                                                        $crctcntstu=$crctcntstu+$crctcount;
                                                    }
                                                }
                                                
                                                /*****Teacher Points earned code start here for pre/post test*****/
                                                $tpointsearned = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned 
                                                                                                    FROM itc_exp_points_master 
                                                                                                    WHERE fld_schedule_type='".$schtype."' AND fld_student_id='".$studentid."' 
                                                                                                                    AND fld_exp_id='".$modids."' AND fld_schedule_id='".$scheduleid."' AND fld_exptype='3' AND fld_res_id='".$testid."' AND fld_delstatus = '0'");
                                                if(trim($tpointsearned)=='')// ||  $tpointsearned=='0'
                                                {
                                                    if($crctcntstu=='0')
                                                    {
                                                        $pointsearned = '0';
                                                    }
                                                    else if($crctcntstu=='-')
                                                    {
                                                         $pointsearned = '';
                                                    }
                                                    else 
                                                    {
                                                        $pointsearned = round($crctcntstu*($possiblepoint/$quescount),2);
                                                    }
                                                }
                                                else
                                                {
                                                    $pointsearned=$tpointsearned;
                                                }
                                                /*****Teacher Points earned code End here for pre/post test*****/
                                                
                                                if($correctcountstu>='0')
                                                {
                                                    $stucorrectcount=$correctcountstu." / ".$quescount;
                                                    $totpercentage=round((($correctcountstu/$quescount)*100),2)." %";
                                                }
                                                else if($correctcountstu=='-')
                                                {
                                                    $stucorrectcount='- / -';
                                                    $totpercentage='';
                                                }

                                                if($pointsearned=='')
                                                {
                                                    $pointsearned='-';
                                                    $possiblepoint='-';
                                                }
                                                $titlename=$testname." / ".$ppost;
                                                $points=$pointsearned." / ".$possiblepoint;
                                                $out .= $titlename;
                                                $out .= " , ".$points." , ".$stucorrectcount;
                                                $out .= "\n";
                                            }
                                        }
                                        else
                                        {  
                                            $out .= "No Pretest Assessments";
                                            $out .= " , "." ";
                                            $out .= "\n";
                                        }
                                    }
                                }
                            } 
                        }
                    }
                } // Student While Loop
            } // Rotation While Loop
        }
        /*********Expedition Schedule Developed by Mohan M Code End here 05-10-2016**********/    
    }
}

//Now we're ready to create a file. This method generates a filename based on the current date & time.

$filename = $name."_".date("Y-m-d_H-i",time());

include("footer.php");
//Generate the CSV file header
header('Content-Encoding: UTF-8,UTF-16LE');
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header("Content-Disposition: csv" . date("Y-m-d") . ".csv");
header("Content-Disposition: attachment; filename=".$filename.".csv");

echo $out;
//Print the contents of out to the generated file.

//print chr(255) . chr(254) . mb_convert_encoding($out, 'UTF-16LE', 'UTF-8');

//Exit the script
exit;