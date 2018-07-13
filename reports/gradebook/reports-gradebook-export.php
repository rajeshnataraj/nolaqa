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

$classid = $id[1];

if($id[0]==1)
{
    $roundflag = $ObjDB->SelectSingleValue("SELECT fld_roundflag 
                                                FROM itc_class_grading_scale_mapping 
                                                WHERE fld_class_id = '".$classid."' AND fld_flag = '1' 
                                                GROUP BY fld_roundflag");

    $qryhead = $ObjDB->QueryObject("SELECT fld_id, fld_grade_name, fn_shortname (fld_grade_name, 1) AS shortname, fld_start_date, fld_end_date 
                                    FROM itc_reports_gradebook_master 
                                    WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_created_by='".$uid."'");

    $csv_hdr='Student Name , Overall , ';
    
    if($qryhead->num_rows>0){ 
        $cnt = 0;
        while($rowqryhead = $qryhead->fetch_assoc())
        {
            extract($rowqryhead);
            $startdate[$cnt]=$fld_start_date;
            $enddate[$cnt]=$fld_end_date;
            $gradeid[$cnt]=$fld_id;
            $cnt++;
            $csv_hdr.= $fld_grade_name." , ";
        }
    }
    $out .= $csv_hdr;
    $out .= "\n\n";
    
    $qrystudent = $ObjDB->QueryObject("SELECT a.fld_student_id AS studentid, CONCAT(b.fld_fname, ' ', b.fld_lname) AS studentname 
                                        FROM itc_class_student_mapping AS a 
                                        LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                        WHERE a.fld_class_id = '".$classid."' AND a.fld_flag = '1' AND b.fld_activestatus = '1' 
                                                AND b.fld_delstatus = '0' 
                                        ORDER BY b.fld_lname");
    
    if($qrystudent->num_rows>0)
    {
        while($rowqrystudent = $qrystudent->fetch_assoc())
        {
            extract($rowqrystudent);
            $out .= $studentname;
            
            for($i=0;$i<=sizeof($startdate);$i++)
            {
                if($i==0)
                {
                    $sqry='';
                    $sqry1='';
                    $sqry2='';
                }
                else
                {
                    $sqry = "AND ('".$startdate[$i-1]."' BETWEEN b.fld_start_date AND b.fld_end_date OR '".$enddate[$i-1]."' BETWEEN b.fld_start_date AND b.fld_end_date OR b.fld_start_date BETWEEN '".$startdate[$i-1]."' AND '".$enddate[$i-1]."' OR b.fld_end_date BETWEEN '".$startdate[$i-1]."' AND '".$enddate[$i-1]."')";
                    $sqry1 = " AND ('".$startdate[$i-1]."' BETWEEN b.fld_startdate AND b.fld_enddate OR '".$enddate[$i-1]."' BETWEEN b.fld_startdate AND b.fld_enddate OR b.fld_startdate BETWEEN '".$startdate[$i-1]."' AND '".$enddate[$i-1]."' OR b.fld_enddate BETWEEN '".$startdate[$i-1]."' AND '".$enddate[$i-1]."')";
                    $sqry2 = " AND ('".$startdate[$i-1]."' BETWEEN c.fld_startdate AND c.fld_enddate OR '".$enddate[$i-1]."' BETWEEN c.fld_startdate AND c.fld_enddate OR c.fld_startdate BETWEEN '".$startdate[$i-1]."' AND '".$enddate[$i-1]."' OR c.fld_enddate BETWEEN '".$startdate[$i-1]."' AND '".$enddate[$i-1]."')";
                }

                $expearned = '';
                $exppossible = '';
                $testearned = '';
                $testpossible = '';

                $qryexp = $ObjDB->QueryObject("SELECT b.fld_id AS scheduleid, b.fld_exp_id AS expid, c.fld_pointspossible, c.fld_exptype, 
                                                        IFNULL((SELECT CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
                                                                THEN fld_teacher_points_earned END AS pointsearned
                                                        FROM itc_exp_points_master 
                                                        WHERE fld_student_id='".$studentid."' AND fld_exp_id=b.fld_exp_id AND fld_grade='1' 
                                                        AND fld_exptype=c.fld_exptype AND fld_schedule_id=b.fld_id AND fld_schedule_type='15' 
                                                        AND (fld_points_earned<>'' OR fld_teacher_points_earned<>'') ),'-') AS pearned
                                                FROM itc_exp_master AS a 
                                                LEFT JOIN itc_class_indasexpedition_master AS b ON b.fld_exp_id=a.fld_id 
                                                LEFT JOIN itc_class_exp_grade AS c ON (c.fld_schedule_id=b.fld_id AND c.fld_exp_id=b.fld_exp_id)
                                                WHERE b.fld_class_id='".$classid."' AND b.fld_flag='1' AND b.fld_delstatus='0' 
                                                        AND a.fld_delstatus='0' AND c.fld_flag='1' AND c.fld_exptype<>'1'  ".$sqry1."
                                                GROUP BY b.fld_id, c.fld_exptype");
                
                if($qryexp->num_rows>0)
                {
					$exptearned = '';
                    $exptpossible = '';
                    while($rowqryexp = $qryexp->fetch_assoc())
                    {
                        extract($rowqryexp);

                        if($pearned==='-')
                        {
                            if($fld_exptype==3)
                            {
                                $qryques = $ObjDB->QueryObject("SELECT IFNULL(b.fld_total_question,'-') AS quescount, COUNT(a.fld_id) AS correctcount FROM itc_test_student_answer_track AS a LEFT JOIN itc_test_master AS b ON a.fld_test_id=b.fld_id WHERE b.fld_expt='".$expid."' AND a.fld_student_id='".$studentid."' AND b.fld_delstatus='0' AND a.fld_show='1' AND a.fld_delstatus='0'");

                                if($qryques->num_rows>0)
                                {
                                    $rowqryques = $qryques->fetch_assoc();
                                    extract($rowqryques);

                                    if($quescount==='-')
                                    {
                                        $pointsearned = '';
                                        $pointspossible = '';
                                    }
                                    else {
                                        $expearned = $expearned+$correctcount*($fld_pointspossible/$quescount);
                                        $exppossible = $exppossible+$fld_pointspossible;
                                    }
                                }
                            }
                            else
                            {
                                $expearned = '';
                                $exppossible = '';
                            }
                        }
                        else
                        {
                                $exptearned = $exptearned+$expearned+$pearned;
                                $exptpossible = $exptpossible+$exppossible+$fld_pointspossible;		
                        }
                    }
                }
                else
                {
                    $exptearned = '';
                    $exptpossible = '';
                }

                $qrytest = $ObjDB->QueryObject("SELECT a.fld_id AS testid, a.fld_score AS testscore, a.fld_total_question AS ques, 
                                                        IFNULL(b.fld_teacher_points_earned,'-') AS tearned, a.fld_question_type AS testtype
                                                FROM itc_test_master AS a 
                                                LEFT JOIN itc_test_student_mapping AS b ON b.fld_test_id=a.fld_id 
                                                WHERE b.fld_class_id='".$classid."' AND a.fld_flag='1' AND a.fld_delstatus='0' 
                                                        AND a.fld_ass_type='0' AND b.fld_student_id='".$studentid."' AND b.fld_flag='1' ".$sqry."");
                if($qrytest->num_rows>0)
                {
                    while($rowqrytest = $qrytest->fetch_assoc())
                    {
                        extract($rowqrytest);

                        if($tearned==='-' or $tearned==='')
                        {
                            $pointsearned = '';

                            $qcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) 
                                                                        FROM itc_test_student_answer_track 
                                                                        WHERE fld_student_id='".$studentid."' AND fld_test_id='".$testid."' AND fld_delstatus='0'");

                            if($testtype === '1')
                            {
                                $correctcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) 
                                                                            FROM itc_test_student_answer_track 
                                                                            WHERE fld_student_id='".$studentid."' AND fld_test_id='".$testid."' 
                                                                                    AND fld_correct_answer='1' AND fld_delstatus='0'");

                                $testearned = $testearned+$correctcount*($testscore/$ques);
                            }
                            else if($testtype === '2')
                            {
                                $qryrandomtest = $ObjDB->QueryObject("SELECT fld_id AS testtagid, fld_pct_section AS percent, fld_qn_assign AS totques
                                                                        FROM itc_test_random_questionassign
                                                                        WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0' 
                                                                        ORDER BY fld_order_by");
                                if($qryrandomtest->num_rows>0)
                                {
                                    while($rowqryrandomtest = $qryrandomtest->fetch_assoc())
                                    {
                                        extract($rowqryrandomtest);

                                        $perscore = ($percent / 100)*$testscore;

                                        $correctcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) 
                                                                                        FROM itc_test_student_answer_track 
                                                                                        WHERE fld_student_id='".$studentid."' AND fld_test_id='".$testid."' AND fld_tag_id='".$testtagid."'
                                                                                                AND fld_correct_answer='1' AND fld_delstatus='0'");

                                        $testearned = $testearned+$correctcount*($perscore/$totques);                                                                                        
                                    }
                                }
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


                $qryoverallpoints = $ObjDB->QueryObject("SELECT SUM(a.pointsearned) AS earned, SUM(a.pointspossible) AS possible FROM (
                                                            (SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
                                                                    THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible 
                                                            FROM itc_assignment_sigmath_master AS a 
                                                            LEFT JOIN itc_class_sigmath_master AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_id)
                                                            WHERE b.fld_class_id='".$classid."' AND a.fld_student_id='".$studentid."' AND a.fld_test_type='1'
                                                                    AND b.fld_flag='1' AND b.fld_delstatus='0' AND (a.fld_status='1' OR a.fld_status='2' OR a.fld_lock='1' OR a.fld_unitmark='1') 
                                                                    AND a.fld_delstatus='0' AND a.fld_grade<>'0' ".$sqry.") 		
                                                                            UNION ALL		
                                                            (SELECT ROUND(SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
                                                                THEN a.fld_teacher_points_earned END)/4) AS pointsearned, ROUND(SUM(a.fld_points_possible)/4) AS pointspossible 
                                                            FROM itc_assignment_sigmath_master AS a 
                                                            LEFT JOIN itc_class_rotation_schedulegriddet AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_schedule_id and a.fld_module_id=b.fld_module_id) 
                                                            LEFT JOIN itc_class_rotation_scheduledate AS c ON b.fld_schedule_id=c.fld_schedule_id and b.fld_rotation=c.fld_rotation
                                                            WHERE b.fld_class_id='".$classid."' AND a.fld_student_id='".$studentid."' and b.fld_student_id='".$studentid."' AND a.fld_test_type = '2' AND b.fld_flag='1' AND a.fld_delstatus='0' and b.fld_type='2'
                                                                    AND (a.fld_status='1' OR a.fld_status='2' OR a.fld_lock='1') AND c.fld_flag='1' ".$sqry2.")  
                                                                            UNION ALL		
                                                            (SELECT ROUND(SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
                                                                    THEN a.fld_teacher_points_earned END)/4) AS pointsearned, ROUND(SUM(a.fld_points_possible)/4) AS pointspossible 
                                                            FROM itc_assignment_sigmath_master AS a 
                                                            LEFT JOIN itc_class_indassesment_master AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_id)
                                                            WHERE b.fld_class_id='".$classid."' AND a.fld_student_id='".$studentid."' AND a.fld_test_type = '5' 
                                                                    AND b.fld_moduletype='2' AND b.fld_flag='1' AND b.fld_delstatus='0' 
                                                                    AND a.fld_delstatus='0' AND (a.fld_status='1' OR a.fld_status='2' OR a.fld_lock='1') ".$sqry1.") 		
                                                                            UNION ALL		
                                                            (SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
                                                            THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible 
                                                            FROM itc_module_points_master AS a 
                                                            LEFT JOIN itc_class_rotation_schedulegriddet AS b ON (a.fld_schedule_id=b.fld_schedule_id 
                                                                    AND a.fld_module_id=b.`fld_module_id` AND a.fld_student_id=b.fld_student_id) 
                                                            LEFT JOIN itc_class_rotation_scheduledate AS c ON b.fld_schedule_id=c.fld_schedule_id and b.fld_rotation=c.fld_rotation
                                                            WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND (a.fld_points_earned <> '' or a.fld_teacher_points_earned <>'')
                                                                    AND a.fld_grade<>'0' AND a.fld_schedule_type IN (1,4,8) AND c.fld_flag='1' ".$sqry2.") 		
                                                                            UNION ALL		
                                                            (SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
                                                                    THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible 
                                                            FROM itc_module_points_master AS a 
                                                            LEFT JOIN `itc_class_dyad_schedulegriddet` AS b ON (a.fld_schedule_id=b.fld_schedule_id 
                                                                    AND a.fld_module_id=b.`fld_module_id` AND (a.fld_student_id=b.fld_student_id OR b.fld_rotation='0')) 
                                                            WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND (a.fld_points_earned <> '' or a.fld_teacher_points_earned <>'')
                                                                    AND a.fld_grade<>'0' AND a.fld_schedule_type='2'  ".$sqry1.") 		
                                                                            UNION ALL		
                                                            (SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
                                                                    THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible 
                                                            FROM itc_module_points_master AS a 
                                                            LEFT JOIN `itc_class_triad_schedulegriddet` AS b ON (a.fld_schedule_id=b.fld_schedule_id
                                                                    AND a.fld_module_id=b.`fld_module_id` AND (a.fld_student_id=b.fld_student_id OR b.fld_rotation='0')) 
                                                            WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND (a.fld_points_earned <> '' or a.fld_teacher_points_earned <>'')
                                                                    AND a.fld_grade<>'0' AND a.fld_schedule_type='3'  ".$sqry1.") 		
                                                                            UNION ALL 		
                                                            (SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
                                                                    THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible 
                                                            FROM itc_module_points_master AS a 
                                                            LEFT JOIN itc_class_indassesment_master AS b ON (a.fld_schedule_id=b.fld_id 
                                                                    AND a.fld_module_id=b.fld_module_id) 
                                                            LEFT JOIN itc_class_indassesment_student_mapping AS c ON (a.fld_schedule_id=c.fld_schedule_id 
                                                                    AND a.fld_student_id=c.fld_student_id) 
                                                            WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$classid."' AND a.fld_delstatus='0' 
                                                                    AND b.fld_flag='1' AND c.fld_flag='1' AND a.fld_grade<>'0' AND (a.fld_points_earned <> '' or a.fld_teacher_points_earned <>'') AND a.fld_schedule_type IN (5,6,7) AND b.fld_delstatus='0'  ".$sqry1.") 
                                                                            UNION ALL 		
                                                            (SELECT IFNULL(b.fld_points_earned,'-') AS pointsearned, a.fld_activity_points AS pointspossible
                                                            FROM itc_activity_master AS a 
                                                            LEFT JOIN itc_activity_student_mapping AS b ON b.fld_activity_id=a.fld_id 
                                                            WHERE b.fld_student_id='".$studentid."' AND b.fld_class_id='".$classid."' AND b.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_points_earned<>'' ".$sqry.") 
                                                        ) AS a");
                
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

                $finalpointsearned = $pointsearned + $exptearned + $testearned;
                $finalpointspossible = $pointspossible + $exptpossible + $testpossible;

                if($finalpointspossible=='' or $finalpointspossible=='-')
                {
                    $finalpointsearned = " - ";
                    $finalpointspossible = " - ";
                    $percentage = " - ";
                    $grade = " N/A ";
                }
                else
                {
                    $finalpointsearned = round($finalpointsearned,2);
                    if($roundflag==0)
                            $percentage = round(($finalpointsearned/$finalpointspossible)*100,2);
                    else
                            $percentage = round(($finalpointsearned/$finalpointspossible)*100);

                    $perarray = explode('.',$percentage);

                    $grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
                                                        FROM itc_class_grading_scale_mapping 
                                                        WHERE fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' 
                                                                AND fld_class_id='".$classid."' AND fld_flag='1'");
                }
                $out .= ', '.$percentage.' % '.$grade;
                $newout .= ', '.$finalpointsearned.' / '.$finalpointspossible;
            }
            $out .= "\n";
            $out .= " ".$newout."\n\n";
            $newout = '';
        }
    }
}

if($id[0]==2)
{
    if($id[3]==0)
    {
        $sqry='';
        $sqry1='';
        $sqry2='';
        $sqry3='';
        $sqry4='';
    }
    else
    {
        $qrygradeperiod = $ObjDB->QueryObject("SELECT fld_start_date, fld_end_date 
                                                FROM itc_reports_gradebook_master 
                                                WHERE fld_id='".$id[3]."' AND fld_delstatus='0'");
	
	$rowqrygradeperiod = $qrygradeperiod->fetch_assoc();
	extract($rowqrygradeperiod);
        
        $sqry4 = "AND ('".$fld_start_date."' BETWEEN b.fld_start_date AND b.fld_end_date OR '".$fld_end_date."' BETWEEN b.fld_start_date AND b.fld_end_date OR b.fld_start_date BETWEEN '".$fld_start_date."' AND '".$fld_end_date."' OR b.fld_end_date BETWEEN '".$fld_start_date."' AND '".$fld_end_date."')";
        $sqry = "AND ('".$fld_start_date."' BETWEEN a.fld_start_date AND a.fld_end_date OR '".$fld_end_date."' BETWEEN a.fld_start_date AND a.fld_end_date OR a.fld_start_date BETWEEN '".$fld_start_date."' AND '".$fld_end_date."' OR a.fld_end_date BETWEEN '".$fld_start_date."' AND '".$fld_end_date."')";
        $sqry1 = " AND ('".$fld_start_date."' BETWEEN a.fld_startdate AND a.fld_enddate OR '".$fld_end_date."' BETWEEN a.fld_startdate AND a.fld_enddate OR a.fld_startdate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."' OR a.fld_enddate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."')";
        $sqry2 = "AND ('".$fld_start_date."' BETWEEN b.fld_startdate AND b.fld_enddate OR '".$fld_end_date."' BETWEEN b.fld_startdate AND b.fld_enddate OR b.fld_startdate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."' OR b.fld_enddate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."')";
        $sqry3 = " AND ('".$fld_start_date."' BETWEEN fld_startdate AND fld_enddate OR '".$fld_end_date."' BETWEEN fld_startdate AND fld_enddate OR fld_startdate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."' OR fld_enddate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."')";
        $sqry5 = "AND ('".$fld_start_date."' BETWEEN d.fld_startdate AND d.fld_enddate OR '".$fld_end_date."' BETWEEN d.fld_startdate AND d.fld_enddate OR d.fld_startdate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."' OR d.fld_enddate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."')";
    }
    
    $roundflag = $ObjDB->SelectSingleValue("SELECT fld_roundflag 
                                                FROM itc_class_grading_scale_mapping 
                                                WHERE fld_class_id = '".$classid."' AND fld_flag = '1' 
                                                GROUP BY fld_roundflag");

    $qryhead = $ObjDB->QueryObject("(SELECT a.fld_id AS scheduleid, b.fld_unit_id AS minids, '0' AS maxids, fn_shortname (c.fld_unit_name, 1) AS nam, c.fld_unit_name AS fullnam, 0 AS typeids 
                                    FROM itc_class_sigmath_master AS a 
                                    LEFT JOIN itc_class_sigmath_unit_mapping AS b ON a.fld_id = b.fld_sigmath_id 
                                    LEFT JOIN itc_unit_master AS c ON c.fld_id = b.fld_unit_id 
                                    WHERE a.fld_class_id = '".$classid."' AND a.fld_flag = '1' AND a.fld_delstatus = '0' AND b.fld_flag = '1' AND c.fld_activestatus = '0' 
                                            AND c.fld_delstatus = '0' ".$sqry.") 	
                                UNION ALL		
                                    (SELECT a.fld_schedule_id AS scheduleid, MIN(a.fld_rotation-1) AS minids, MAX(a.fld_rotation-1) AS maxids,'Rotation ' AS nam, 'Rotation ' AS fullnam, 
                                        (CASE WHEN a.fld_type='1' AND b.fld_type='1' THEN '1' WHEN a.fld_type='2' AND b.fld_type='2' THEN '4' WHEN b.fld_type='8' THEN '8' END) AS typeids 
                                    FROM itc_class_rotation_schedulegriddet AS a 
                                    LEFT JOIN itc_class_rotation_moduledet AS b ON b.fld_schedule_id=a.fld_schedule_id 
                                    left join itc_class_rotation_schedule_mastertemp as c on a.fld_schedule_id=c.fld_id
                                    LEFT JOIN itc_class_rotation_scheduledate AS d ON a.fld_schedule_id=d.fld_schedule_id and a.fld_rotation=d.fld_rotation
                                    WHERE a.fld_class_id='".$classid."' AND b.fld_flag='1' AND a.fld_flag='1' and c.fld_delstatus='0' AND d.fld_flag='1' ".$sqry5." 
                                    GROUP BY a.fld_schedule_id ) 		
                                UNION ALL
                                    (SELECT a.fld_id AS scheduleid, (MIN(DISTINCT(b.fld_rotation))) AS minids, (MAX(DISTINCT(b.fld_rotation))) AS maxids, 'Rotation ' AS nam, 'Rotation ' AS fullnam, 2 AS typeids 
                                    FROM itc_class_dyad_schedulemaster AS a 
                                    LEFT JOIN itc_class_dyad_schedulegriddet AS b ON b.fld_schedule_id=a.fld_id
                                    WHERE a.fld_class_id='".$classid."' AND b.fld_flag='1' AND a.fld_delstatus='0' ".$sqry2."
                                    GROUP BY a.fld_id )
                                UNION ALL
                                    (SELECT a.fld_id AS scheduleid, (MIN(DISTINCT(b.fld_rotation))) AS minids, (MAX(DISTINCT(b.fld_rotation))) AS maxids, 'Rotation ' AS nam, 'Rotation ' AS fullnam, 3 AS typeids 
                                    FROM itc_class_triad_schedulemaster AS a 
                                    LEFT JOIN itc_class_triad_schedulegriddet AS b ON b.fld_schedule_id=a.fld_id
                                    WHERE a.fld_class_id='".$classid."' AND b.fld_flag='1' AND a.fld_delstatus='0' ".$sqry2."
                                    GROUP BY a.fld_id ) 		
                                UNION ALL
                                    (SELECT a.fld_id AS scheduleid, a.fld_module_id AS minids, '0' AS maxids, fn_shortname(CONCAT(b.fld_module_name,' / Ind Module'),1) AS nam, 
                                        CONCAT(b.fld_module_name,' / Ind Module') AS fullnam, 5 AS typeids 
                                    FROM itc_class_indassesment_master AS a 
                                    LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
                                    WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND a.fld_moduletype='1' AND b.fld_delstatus='0' ".$sqry1." 
                                    GROUP BY a.fld_id )  		
                                UNION ALL		
                                    (SELECT a.fld_id AS scheduleid, a.fld_module_id AS ids, '0' AS maxids,  fn_shortname(CONCAT(b.fld_mathmodule_name,' / Ind MathModule'),1) AS nam, 
                                        CONCAT(b.fld_mathmodule_name,' / Ind MathModule') AS fullnam, 6 AS typeids 
                                    FROM itc_class_indassesment_master AS a 
                                    LEFT JOIN itc_mathmodule_master AS b ON a.fld_module_id=b.fld_id
                                    WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND a.fld_moduletype='2' AND b.fld_delstatus='0' ".$sqry1."
                                    GROUP BY a.fld_id)
                                UNION ALL	
                                    (SELECT a.fld_id AS scheduleid, a.fld_module_id AS minids, '0' AS maxids, fn_shortname(CONCAT(b.fld_module_name,' / Quest'),1) AS nam, 
                                        CONCAT(b.fld_module_name,' / Quest') AS fullnam, 7 AS typeids 
                                    FROM itc_class_indassesment_master AS a 
                                    LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id
                                    WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND a.fld_moduletype='7' AND b.fld_delstatus='0' ".$sqry1."
                                    GROUP BY a.fld_id) 
                                UNION ALL	
                                    (SELECT a.fld_id AS scheduleid, a.fld_exp_id AS minids, '0' AS maxids, fn_shortname(CONCAT(b.fld_exp_name,' / Expedition'),1) AS nam, 
                                        CONCAT(b.fld_exp_name,' / Expedition') AS fullnam, 15 AS typeids 
                                    FROM itc_class_indasexpedition_master AS a 
                                    LEFT JOIN itc_exp_master AS b ON a.fld_exp_id=b.fld_id
                                    WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_delstatus='0' ".$sqry1."
                                    GROUP BY a.fld_id) 
                                UNION ALL 		
                                    (SELECT a.fld_class_id AS scheduleid, b.fld_id AS minids, '0' AS maxids, fn_shortname(b.fld_test_name,1) AS nam, b.fld_test_name AS fullnam, 9 AS typeids 
                                    FROM itc_test_student_mapping AS a 
                                    LEFT JOIN itc_test_master AS b ON a.fld_test_id=b.fld_id 
                                    WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_delstatus='0' AND (b.fld_ass_type='0' or b.fld_ass_type = '1' or b.fld_ass_type='2') ".$sqry."
                                    GROUP BY b.fld_id) 		
                                UNION ALL	
                                    (SELECT a.fld_class_id AS scheduleid, a.fld_activity_id AS minids, '0' AS maxids, fn_shortname(b.fld_activity_name,1) AS nam, b.fld_activity_name AS fullnam, 
                                        10 AS typeids 
                                    FROM itc_activity_student_mapping AS a 
                                    LEFT JOIN itc_activity_master AS b ON a.fld_activity_id=b.fld_id 
                                    WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_delstatus='0' ".$sqry."
                                    GROUP BY a.fld_activity_id)
                                    
                                UNION ALL		
                                    (SELECT a.fld_schedule_id AS scheduleid, MIN(a.fld_rotation-1) AS minids, MAX(a.fld_rotation-1) AS maxids,'Rotation ' AS nam, 'Rotation ' AS fullnam, 
                                    19 AS typeids
                                    FROM itc_class_rotation_expschedulegriddet AS a 
                                    LEFT JOIN itc_class_rotation_expmoduledet AS b ON b.fld_schedule_id=a.fld_schedule_id 
                                    left join itc_class_rotation_expschedule_mastertemp as c on a.fld_schedule_id=c.fld_id
                                    LEFT JOIN itc_class_rotation_expscheduledate AS d ON a.fld_schedule_id=d.fld_schedule_id and a.fld_rotation=d.fld_rotation
                                    LEFT JOIN itc_class_rotation_expschedule_student_mappingtemp as e ON a.fld_schedule_id=e.fld_schedule_id
                                    WHERE a.fld_class_id='".$classid."' AND b.fld_flag='1' AND a.fld_flag='1' AND e.fld_flag='1' and c.fld_delstatus='0' AND d.fld_flag='1' ".$sqry5." 
                                    GROUP BY a.fld_schedule_id )

                                UNION ALL	
                                    (SELECT a.fld_id AS scheduleid, a.fld_mis_id AS minids, '0' AS maxids, 
                                    fn_shortname(CONCAT(b.fld_mis_name,' / Mission'),1) AS nam, 
                                    CONCAT(b.fld_mis_name,' / Mission') AS fullnam, 18 AS typeids
                                    FROM itc_class_indasmission_master AS a 
                                    LEFT JOIN itc_mission_master AS b ON a.fld_mis_id=b.fld_id
                                    WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND a.fld_delstatus='0' 
                                    AND b.fld_delstatus='0' ".$sqry1."
                                    GROUP BY a.fld_id) 

                                UNION ALL
                                    (SELECT a.fld_schedule_id AS scheduleid, MIN(a.fld_rotation-1) AS minids, MAX(a.fld_rotation-1) AS maxids,'Rotation ' AS nam, 'Rotation ' AS fullnam, 
                                    22 AS typeids
                                    FROM itc_class_rotation_modexpschedulegriddet AS a 
                                    LEFT JOIN itc_class_rotation_modexpmoduledet AS b ON b.fld_schedule_id=a.fld_schedule_id 
                                    left join itc_class_rotation_modexpschedule_mastertemp as c on a.fld_schedule_id=c.fld_id
                                    LEFT JOIN itc_class_rotation_modexpscheduledate AS d ON a.fld_schedule_id=d.fld_schedule_id and a.fld_rotation=d.fld_rotation
                                    LEFT JOIN itc_class_rotation_modexpschedule_student_mappingtemp as e ON a.fld_schedule_id=e.fld_schedule_id
                                    WHERE a.fld_class_id='".$classid."'  AND c.fld_flag = '1' AND b.fld_flag='1' AND a.fld_flag='1' AND e.fld_flag='1' and c.fld_delstatus='0' AND d.fld_flag='1' ".$sqry5." 
                                    GROUP BY a.fld_schedule_id)

                                UNION ALL
                                    (SELECT a.fld_schedule_id AS scheduleid, MIN(a.fld_rotation-1) AS minids, MAX(a.fld_rotation-1) AS maxids,'Rotation ' AS nam, 'Rotation ' AS fullnam, 
                                    23 AS typeids
                                    FROM itc_class_rotation_mission_schedulegriddet AS a 
                                    LEFT JOIN itc_class_rotation_missiondet AS b ON b.fld_schedule_id=a.fld_schedule_id 
                                    left join itc_class_rotation_mission_mastertemp as c on a.fld_schedule_id=c.fld_id
                                    LEFT JOIN itc_class_rotation_missionscheduledate AS d ON a.fld_schedule_id=d.fld_schedule_id and a.fld_rotation=d.fld_rotation
                                    LEFT JOIN itc_class_rotation_mission_student_mappingtemp as e ON a.fld_schedule_id=e.fld_schedule_id
                                    WHERE a.fld_class_id='".$classid."'  AND c.fld_flag = '1' AND b.fld_flag='1' AND a.fld_flag='1' AND e.fld_flag='1' and c.fld_delstatus='0' AND d.fld_flag='1' ".$sqry5." 
                                    GROUP BY a.fld_schedule_id)

                                    ");
    
    $csv_hdr = "Student Name ,";
    if($qryhead->num_rows>0)
    {
        $unitids = array();
        $cnt=0;
        while($rowqryhead = $qryhead->fetch_assoc()) // show the module based on number of copies
        {
            extract($rowqryhead);
            $assid[$cnt]=$minids;
            $maxassid[$cnt]=$maxids;
            $scheduleids[$cnt]=$scheduleid;
            $type[$cnt]=$typeids;
            if($typeids==0 || $typeids==10 || $typeids==5 || $typeids==6 || $typeids==7 || $typeids==9 || $typeids==15 || $typeids==18 || $typeids==20 || $typeids==24)
            {
                $csv_hdr .= $fullnam.", ";
            }
            else
            {
                $oriencunt = 0;
                if($typeids==2 or $typeids==3)
                {
                    if($typeids==2)
                    {
                        $schegridtable = "itc_class_dyad_schedulegriddet";										
                        $schname = "Dyad";
                    }
                    if($typeids==3)
                    {
                        $schegridtable = "itc_class_triad_schedulegriddet";										
                        $schname = "Triad";
                    }

                    $oriencunt = $ObjDB->SelectSingleValue("SELECT COUNT(fld_rotation) FROM ".$schegridtable." 
                                                            WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_flag='1' 
                                                                    AND fld_rotation='0' ".$sqry3."");
                }
                for($i=$minids;$i<=$maxids;$i++)
                {												
                    $increment = $i;

                    if($i == 0 and $oriencunt==1)
                        $rotname = "Orientation / ".$schname;
                    else
                        $rotname = $nam.' '.$increment;
                    
                    $csv_hdr .= $rotname.", ";
                }
            }
            $cnt++;
        }
    }
    $csv_hdr .= "Total Points Earned , Total Points Possible, Percentage, Grade";
    $out .= $csv_hdr;
    $out .= "\n\n";
    
    $qrystudent = $ObjDB->QueryObject("SELECT a.fld_student_id, CONCAT(b.fld_fname, ' ', b.fld_lname) AS studentname 
                                        FROM itc_class_student_mapping AS a 
                                        LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                        WHERE a.fld_class_id = '".$classid."' AND a.fld_flag = '1' AND b.fld_activestatus = '1' 
                                                AND b.fld_delstatus = '0' 
                                        ORDER BY b.fld_lname");
    if($qrystudent->num_rows>0)
    {
        while($rowqrystudent = $qrystudent->fetch_assoc()) // show the module based on number of copies
        {
            extract($rowqrystudent);
            
            $totalpointsearned= 0;
            $totalpointspossible= 0;
            $pointsearned=0;
            $pointspossible=0;
            
            $out .= $studentname;
            
            if($qryhead->num_rows>0)
            {
                for($j=0;$j<sizeof($assid);$j++) 
                { 
                    if($type[$j]==0) 
                    {
                        $studentcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                        FROM itc_class_sigmath_student_mapping 
                                                                        WHERE fld_sigmath_id='".$scheduleids[$j]."' AND fld_flag='1'
                                                                                AND fld_student_id='".$fld_student_id."'");
                        if($studentcount!=0)
                        {
                            $qrypoints = $ObjDB->QueryObject("SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned 
                                                                        WHEN fld_lock='1' THEN fld_teacher_points_earned END) 
                                                                        AS pointsearned, SUM(fld_points_possible) AS pointspossible 
                                                                FROM `itc_assignment_sigmath_master` 
                                                                WHERE fld_class_id='".$classid."' AND fld_delstatus='0' 
                                                                        AND fld_student_id='".$fld_student_id."' AND fld_grade<>'0' 
                                                                        AND fld_schedule_id='".$scheduleids[$j]."' 
                                                                        AND fld_unit_id='".$assid[$j]."' AND (fld_points_earned<>'' 
                                                                        OR fld_teacher_points_earned<>'') ");
                            if($qrypoints->num_rows>0)
                            {
                                while($rowqrypoints = $qrypoints->fetch_assoc()) // show the module based on number of copies
                                {
                                    extract($rowqrypoints);
                                    if($pointspossible=='')
                                        $pointspossible = "-";

                                    if($pointsearned=='')
                                    {
                                        $pointsearned = "-";
                                        $pointspossible = "-";
                                        $percentage = "-";
                                        $grade = "NA";
                                    }
                                    else
                                    {
                                        $totalpointsearned = $totalpointsearned + $pointsearned;
                                        if($roundflag==0)
                                            $percentage = round(($pointsearned/$pointspossible)*100,2);
                                        else
                                            $percentage = round(($pointsearned/$pointspossible)*100);

                                        $perarray = explode('.',$percentage);
                                        $grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
                                                                            FROM itc_class_grading_scale_mapping 
                                                                            WHERE fld_class_id = '".$classid."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
                                    }
                                    
                                    if($pointspossible=='')
                                        $pointspossible = "-";
                                    else
                                        $totalpointspossible = $totalpointspossible + $pointspossible;
                                    
                                    $out .= ', '.$percentage.' % '.$grade;
                                    $newout .= ', '.$pointsearned.' / '.$pointspossible;
                                    $newout1 .= ', ';
                                }
                            }
                        }
                        else
                        { 
                            $out .= ', ';
                            $newout .= ', No IPLs';
                            $newout1 .= ', ';
                        }
                    } 
                    
                    else if($type[$j]==5 || $type[$j]==6 || $type[$j]==7 || $type[$j]==17) 
                    { 
                        $studentcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                        FROM itc_class_indassesment_student_mapping 
                                                                        WHERE fld_schedule_id='".$scheduleids[$j]."' 
                                                                                AND fld_student_id='".$fld_student_id."' 
                                                                                AND fld_flag='1'");
                        if($studentcount!=0)
                        {
                            $qryinddetails = '';
                            if($type[$j]==6)
                            {
                                $qryinddetails = "SELECT SUM(w.earned) AS pointsearned, SUM(w.possible) AS pointspossible FROM (
                                                    (SELECT SUM(CASE WHEN fld_lock = '0' THEN fld_points_earned WHEN 
                                                        fld_lock = '1' THEN fld_teacher_points_earned END) AS earned, SUM(fld_points_possible) AS possible 
                                                    FROM itc_module_points_master 
                                                    WHERE fld_student_id='".$fld_student_id."' AND fld_module_id='".$assid[$j]."' AND fld_schedule_id='".$scheduleids[$j]."'
                                                        AND fld_grade<>'0' AND fld_schedule_type='".$type[$j]."' AND (fld_points_earned<>'' OR fld_teacher_points_earned<>''))
                                                                    UNION ALL 		
                                                    (SELECT ROUND(SUM(CASE WHEN a.fld_lock = '0' THEN a.fld_points_earned WHEN a.fld_lock = '1' THEN a.fld_teacher_points_earned END)/4) AS earned, 
                                                        ROUND(SUM(a.fld_points_possible)/4) AS possible 
                                                    FROM itc_assignment_sigmath_master AS a  
                                                    WHERE a.fld_student_id = '".$fld_student_id."' and a.fld_module_id='".$assid[$j]."' AND a.fld_test_type='5' AND a.fld_schedule_id = '".$scheduleids[$j]."' 
                                                        AND (a.fld_points_earned<>'' OR a.fld_teacher_points_earned<>'') AND a.fld_delstatus='0' AND (a.fld_status='1' OR a.fld_status='2' OR a.fld_lock='1')
                                                        AND a.fld_unitmark='0')
                                                 ) AS w";
                            }
                            else if($type[$j]==17)
                            {
                                $qryinddetails = "SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
                                                                THEN fld_teacher_points_earned END) AS pointsearned, 
                                                                SUM(fld_points_possible) AS pointspossible 
                                                        FROM itc_module_points_master 
                                                        WHERE fld_student_id='".$fld_student_id."' AND fld_module_id='".$assid[$j]."' 
                                                        AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."' 
                                                        AND (fld_points_earned<>'' OR fld_teacher_points_earned<>'') AND fld_grade<>'0'";

                            }
                            else
                            {

                                $qryinddetails = "SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
                                                        THEN fld_teacher_points_earned END) AS pointsearned, SUM(fld_points_possible) AS pointspossible 
                                                    FROM itc_module_points_master 
                                                    WHERE fld_student_id='".$fld_student_id."' AND fld_module_id='".$assid[$j]."'  AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."' 
                                                        AND (fld_points_earned<>'' OR fld_teacher_points_earned<>'') AND fld_grade<>'0'";
                            }
                            
                            $qrypoints = $ObjDB->QueryObject($qryinddetails);
                            if($qrypoints->num_rows>0)
                            {
                                while($rowqrypoints = $qrypoints->fetch_assoc())
                                {
                                    extract($rowqrypoints);
                                    if($pointsearned=='')
                                    {
                                        $pointsearned = "-";
                                        $percentage = "-";
                                        $grade = "NA";
                                    }
                                    else
                                    {
                                        $totalpointsearned = $totalpointsearned + $pointsearned;
                                        if($roundflag==0)
                                            $percentage = round(($pointsearned/$pointspossible)*100,2);
                                        else
                                            $percentage = round(($pointsearned/$pointspossible)*100);

                                        $perarray = explode('.',$percentage);
                                        $grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$classid."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
                                    }

                                     if($pointspossible=='')
                                        $pointspossible = "-";
                                    else
                                        $totalpointspossible = $totalpointspossible + $pointspossible;
                                    
                                    $out .= ', '.$percentage.' % '.$grade;
                                    $newout .= ', '.$pointsearned.' / '.$pointspossible;
                                    $newout1 .= ', ';
                                }
                            }																				
                        }
                        else
                        { 
                            if($type[$j]==6) $names="No Ind MathModule"; else if($type[$j]==5) $names="No Ind Module"; else if($type[$j]==7) $names="No Ind Quest"; else if($type[$j]==5) $names="No Ind Custom";
                            $out .= ', ';
                            $newout .= ', '.$names;
                            $newout1 .= ', ';
                        }
                    }
                    
                    else if($type[$j]==15) 
                    { 
                        $studentcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                        FROM itc_class_exp_student_mapping 
                                                                        WHERE fld_schedule_id='".$scheduleids[$j]."' AND fld_student_id='".$fld_student_id."' AND fld_flag='1'");
                        
                        if($studentcount!=0)
                        {

                            /************** Pre/Post test code start here ***************/
                            $pointsearnedfortest=0;
                            $possiblepointfortest1=0;
                            $possiblepointfortest=0;

                            $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                                                WHERE fld_class_id='".$classid."' AND fld_sch_id='".$scheduleids[$j]."' AND fld_schtype_id='".$type[$j]."'");

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
                                                $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0'");

                                                $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master 
                                                                            WHERE fld_student_id='".$fld_student_id."' AND fld_exp_id='".$expid."' 
                                                                            AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."' 
                                                                            AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                                if(trim($tchpointcnt)=='')
                                                {
                                                    $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                            WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                                            AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."' 
                                                                                                            AND a.fld_schedule_type='".$type[$j]."' AND b.fld_delstatus = '0' AND a.fld_delstatus = '0'");

                                                    if(trim($correctcountfortestattend) != '')
                                                    {
                                                        $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                            WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                                            AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."' 
                                                                                                            AND a.fld_schedule_type='".$type[$j]."' AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");
                                                        $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                                        $possiblepointfortest+=$possiblepointfortest1;
                                                    }
                                                }
                                                else
                                                {
                                                    $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                                        WHEN fld_lock = '0' THEN fld_points_earned
                                                                                                        WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                                END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$fld_student_id."'
                                                                                                        AND fld_exp_id='".$expid."' AND fld_schedule_id='".$scheduleids[$j]."' 
                                                                                                        AND fld_schedule_type='".$type[$j]."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");
                                                    if(trim($tchpointearn)!='')
                                                    {
                                                        $possiblepointfortest+=$possiblepointfortest1;
                                                    }
                                                    $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
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
                                                $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0'");

                                                $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master 
                                                                                                WHERE fld_student_id='".$fld_student_id."' AND fld_exp_id='".$expid."' 
                                                                                                AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."' 
                                                                                                AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                                if(trim($tchpointcnt)=='')
                                                {
                                                    $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                            WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                                            AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."' 
                                                                                                            AND a.fld_schedule_type='".$type[$j]."' AND b.fld_delstatus = '0' AND a.fld_delstatus = '0'");

                                                   if(trim($correctcountfortestattend) != '')
                                                   {
                                                        $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                            WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                                            AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."' 
                                                                                                            AND a.fld_schedule_type='".$type[$j]."' AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");

                                                        $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                                        $possiblepointfortest+=$possiblepointfortest1;
                                                   }
                                                }
                                                else
                                                {
                                                    $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                                        WHEN fld_lock = '0' THEN fld_points_earned
                                                                                                        WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                                END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$fld_student_id."'
                                                                                                        AND fld_exp_id='".$expid."' AND fld_schedule_id='".$scheduleids[$j]."' 
                                                                                                        AND fld_schedule_type='".$type[$j]."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");
                                                    if(trim($tchpointearn)!='')
                                                    {
                                                        $possiblepointfortest+=$possiblepointfortest1;
                                                    }
                                                    $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
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
                            $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                            CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                            LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                            LEFT JOIN itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                                            LEFT JOIN itc_class_indasexpedition_master AS d ON a.fld_schedule_id=d.fld_id 
                                                            WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleids[$j]."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                                    AND a.fld_schedule_type='15' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                                    AND b.fld_school_id IN(0,".$schoolid.")");

                            if($qryrub->num_rows>0)
                            {
                                while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
                                {
                                    extract($rowqryrub);

                                    $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master 
                                                                                WHERE fld_exp_id='".$assid[$j]."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                                    $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_rpt WHERE fld_exp_id='".$assid[$j]."'  
                                                                                    AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$classid."' 
                                                                                    AND fld_schedule_id='".$scheduleids[$j]."' AND fld_delstatus='0' "); 

                                    $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_rpt_statement
                                                                                        WHERE fld_student_id='".$fld_student_id."' AND fld_exp_id='".$assid[$j]."' AND fld_delstatus='0'
                                                                                        AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                                    $pointsearnedrubric = $pointsearnedrubric+$studentscore;
                                    if($studentscore!=0)
                                    {
                                        $pointspossiblerubric = $pointspossiblerubric+$totscore;
                                    }
                                }
                            }
                            /************** Rubric code end here ***************/
                            $pointsearned=round($pointsearnedfortest + $pointsearnedrubric);
                            $pointspossible=$possiblepointfortest + $pointspossiblerubric;

                            //percentage code start here
                            if($pointsearned=='' AND $pointspossible!='0')
                            {
                                $pointsearned = "-";
                                $percentage = "-";
                                $grade = "NA";
                                $pointspossible = "-";
                            }
                            else
                            {
                                if($roundflag==0)
                                    $percentage = round(($pointsearned/$pointspossible)*100,2);
                                else
                                    $percentage = round(($pointsearned/$pointspossible)*100);

                                $perarray = explode('.',$percentage);

                                $grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$classid."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
                            }
                            if($percentage==0)
                            {
                                $percentage = "-";
                                $grade = "NA";
                                $pointsearned = "-";
                                $pointspossible = "-";
                            }
                            //percentage code end here
                            $out .= ', '.$percentage.' % '.$grade;
                            $newout .= ', '.$pointsearned.' / '.$pointspossible;
                            $newout1 .= ', ';
                            
                            $totalpointsearned = $totalpointsearned + $pointsearned;
                            $totalpointspossible = $totalpointspossible + $pointspossible;	
                               																			
                        }
                        else
                        { 
                            $out .= ', ';
                            $newout .= ', No Expedition';
                            $newout1 .= ', ';
                        }
                    }
                    
/*******EXPEDITION sCHEDULE cODE START HERE*******/
else if($type[$j]==19) 
{ 
    $incrementcountexp = $assid[$j];
    $totalcntexp = $maxassid[$j];

    for($z=$incrementcountexp;$z<=$totalcntexp;$z++)
    { 
        $pointsearned=0;
        $pointspossible=0;
        $expstudentcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                FROM itc_class_rotation_expschedule_student_mappingtemp
                                                                WHERE fld_schedule_id='".$scheduleids[$j]."' 
                                                                        AND fld_student_id='".$fld_student_id."' 
                                                                        AND fld_flag='1'");
        if($expstudentcount!=0)
        {
            $rotid=$z+1;                             
            $qryexpsch = $ObjDB->QueryObject("SELECT fld_expedition_id AS expid, (SELECT fld_exp_name FROM itc_exp_master WHERE fld_id=fld_expedition_id) AS expname 
                                                      FROM `itc_class_rotation_expschedulegriddet` WHERE fld_class_id='".$classid."' AND fld_student_id='".$fld_student_id."' 
                                                      AND fld_schedule_id='".$scheduleids[$j]."' AND fld_rotation='".$rotid."' AND fld_flag='1'  LIMIT 0,1");
            $schexpid='';
            $schexpname='';
            if($qryexpsch->num_rows>0)
            {
                $rowschexp=$qryexpsch->fetch_assoc();
                extract($rowschexp);
                $schexpid=$expid;
                $schexpname=$expname;
            }

            /************** Pre/Post test code start here ***************/
            $pointsearnedfortest=0;
            $possiblepointfortest1=0;
            $possiblepointfortest=0;
            $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                            WHERE fld_class_id='".$classid."' AND fld_sch_id='".$scheduleids[$j]."' AND fld_texpid='".$schexpid."' AND fld_schtype_id='".$type[$j]."'");
          
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

                                $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_student_id='".$fld_student_id."' AND fld_exp_id='".$schexpid."' AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                 if(trim($tchpointcnt)=='')
                                {
                                    $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                            WHERE b.fld_expt = '".$schexpid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                            AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."' 
                                                                                            AND a.fld_schedule_type='".$type[$j]."'
                                                                                            AND b.fld_delstatus = '0' AND a.fld_delstatus = '0'");

                                   if(trim($correctcountfortestattend) != '')
                                   {
                                        $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                            WHERE b.fld_expt = '".$schexpid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                            AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."' 
                                                                                            AND a.fld_schedule_type='".$type[$j]."'
                                                                                            AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");
                                       
                                       
                                        $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                        $possiblepointfortest+=$possiblepointfortest1;
                                   }
                                }
                                else
                                {
                                    $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                    WHEN fld_lock = '0' THEN fld_points_earned
                                                                                    WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                            END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$fld_student_id."'
                                                                                    AND fld_exp_id='".$schexpid."' AND fld_schedule_id='".$scheduleids[$j]."' 
                                                                                    AND fld_schedule_type='".$type[$j]."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                    if($tchpointearn!='')
                                    {
                                        $possiblepointfortest+=$possiblepointfortest1;
                                    }

                                    $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
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

                                $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_student_id='".$fld_student_id."' AND fld_exp_id='".$schexpid."' AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                if(trim($tchpointcnt)=='')
                                {
                                    $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                           LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                           WHERE b.fld_expt = '".$schexpid."' AND a.fld_student_id = '".$fld_student_id."' AND a.fld_test_id='".$testid."' AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."'
                                                                                           AND b.fld_delstatus = '0' AND a.fld_delstatus = '0'");

                                   if(trim($correctcountfortestattend) != '')
                                   {
                                        $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                           LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                           WHERE b.fld_expt = '".$schexpid."' AND a.fld_student_id = '".$fld_student_id."' AND a.fld_test_id='".$testid."' AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."'
                                                                                           AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");
                                        
                                        $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                        $possiblepointfortest+=$possiblepointfortest1;
                                   }
                                }
                                else
                                {
                                    $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                    WHEN fld_lock = '0' THEN fld_points_earned
                                                                                    WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                            END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$fld_student_id."'
                                                                                    AND fld_exp_id='".$schexpid."' AND fld_schedule_id='".$scheduleids[$j]."' 
                                                                                    AND fld_schedule_type='".$type[$j]."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                    if($tchpointearn!='')
                                    {
                                        $possiblepointfortest+=$possiblepointfortest1;
                                    }

                                    $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                }
                            }
                        }
                    }
                    /*********Post Test Code End Here*********/
                }
            }
            /************** Pre/Post test code end here ***************/

            /************** Rubric code start here ***************/
            $pointsearnedrubric=0;
            $pointspossiblerubric=0;
            $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                LEFT JOIN itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                                LEFT JOIN itc_class_rotation_expschedule_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
                                                WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleids[$j]."' AND b.fld_exp_id='".$schexpid."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                        AND a.fld_schedule_type='17' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                        AND b.fld_school_id IN(0,".$schoolid.")");

            if($qryrub->num_rows>0)
            {
                while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
                {
                    extract($rowqryrub);

                    $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master 
                                                                WHERE fld_exp_id='".$schexpid."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                    $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_expsch_rubric_rpt WHERE fld_exp_id='".$schexpid."'  
                                                                                AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$classid."' 
                                                                                AND fld_schedule_id='".$scheduleids[$j]."' AND fld_delstatus='0' "); 

                    $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_expsch_rubric_rpt_statement
                                                                            WHERE fld_student_id='".$fld_student_id."' AND fld_exp_id='".$schexpid."' AND fld_delstatus='0'
                                                                            AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                    $pointsearnedrubric = $pointsearnedrubric+$studentscore;
                    if($studentscore!=0)
                    {
                        $pointspossiblerubric = $pointspossiblerubric+$totscore;
                    }
                }
            }

            /************** Rubric code end here ***************/
            $pointsearned=round($pointsearnedfortest + $pointsearnedrubric);
            $pointspossible=$possiblepointfortest + $pointspossiblerubric;


            //percentage code start here
            if($pointsearned=='' AND $pointspossible!='0')
            {
                $pointsearned = "-";
                $pointspossible="-";
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
                $grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$classid."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
            }
            if($percentage==0)
            {
                $percentage = "-";
                $grade = "NA";
                $pointsearned = "-";
                $pointspossible="-";
            }
            //percentage code end here
              $out .= ', '.$percentage.' % '.$grade;
              $newout .= ', '.$pointsearned.' / '.$pointspossible;
              $newout1 .= ', ';

              $totalpointsearned = $totalpointsearned + $pointsearned;
              $totalpointspossible = $totalpointspossible + $pointspossible;																			
        }
        else
        {   
            $out .= ', ';
            $newout .= ', No Expedition';
            $newout1 .= ', ';
        }
    }
}
/*******EXPEDITION sCHEDULE cODE End HERE*******/

/**************************Expedition and Module schedule Code start here by Mohan**********************/
else if($type[$j]==22) 
{ 
    $incrementcountexp = $assid[$j];
    $totalcntexp = $maxassid[$j];

    for($z=$incrementcountexp;$z<=$totalcntexp;$z++)
    { 
        $rotid=$z+1;   
        $exptype='20';
        $modtype='21';
        $qryexpmodtype = $ObjDB->QueryObject("SELECT fld_module_id AS ids,fld_type AS typeid FROM itc_class_rotation_modexpschedulegriddet 
                                                            WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleids[$j]."' AND fld_rotation='".$rotid."' 
                                                            AND fld_student_id='".$fld_student_id."' AND fld_flag='1'");


        if($qryexpmodtype->num_rows > 0)
        { 	
            while($rowexpmodtype=$qryexpmodtype->fetch_assoc())
            {
                extract($rowexpmodtype);
                if($typeid=='1') //Module
                {
                    $l=$z;
                    $l++;

                    $qrymodexp = $ObjDB->QueryObject("SELECT fld_module_id AS modids,
                                                            (SELECT fld_module_name FROM itc_module_master WHERE fld_id=fld_module_id) AS modulename, 
                                                            1 AS newtype  FROM `itc_class_rotation_modexpschedulegriddet` 
                                                            WHERE fld_class_id='".$classid."' AND fld_student_id='".$fld_student_id."'  AND fld_schedule_id='".$scheduleids[$j]."' 
                                                            AND fld_module_id='".$ids."'  AND fld_rotation='".$l."' AND fld_flag='1' AND fld_type = '1' LIMIT 0,1
                                                  UNION ALL 	
                                                          SELECT a.fld_id AS modids, a.fld_contentname AS modulename, 8 AS newtype FROM itc_customcontent_master AS a 
                                                                  LEFT JOIN itc_class_rotation_modexpschedulegriddet AS b ON b.fld_module_id = a.fld_id 
                                                                  WHERE b.fld_class_id='".$classid."' AND b.fld_student_id='".$fld_student_id."' 
                                                                  AND b.fld_schedule_id = '".$scheduleids[$j]."'  AND fld_module_id='".$ids."' AND fld_rotation='".$l."' 
                                                                  AND b.fld_type = '8' AND b.fld_flag = '1' AND a.fld_delstatus = '0' ");

                    if($qrymodexp->num_rows>0)
                    {
                        while($rowqrymodexp = $qrymodexp->fetch_assoc()) // show the module based on number of copies
                        {
                            extract($rowqrymodexp);

                            $qrymodexppoints = $ObjDB->QueryObject("SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1'
                                                                        THEN fld_teacher_points_earned END) AS pointsearned, SUM(fld_points_possible) AS pointspossible
                                                                        FROM itc_module_points_master 
                                                                        WHERE fld_student_id='".$fld_student_id."' AND fld_schedule_id='".$scheduleids[$j]."' 
                                                                        AND fld_schedule_type='".$modtype."' 
                                                                        AND fld_module_id='".$modids."' AND (fld_points_earned<>'' OR fld_teacher_points_earned<>'') 
                                                                        AND fld_grade<>'0' AND fld_delstatus='0'");

                            if($qrymodexppoints->num_rows>0)
                            {
                                $rowqrymodexppoints = $qrymodexppoints->fetch_assoc();
                                extract($rowqrymodexppoints);
                            }
                            if($pointsearned=='')
                            {
                                $pointsearned = "-";
                                $percentage = "-";
                                $grade = "N/A";
                            }
                            else if($pointspossible!='')
                            {
                                if($roundflag==0)
                                        $percentage = round(($pointsearned/$pointspossible)*100,2);
                                else
                                        $percentage = round(($pointsearned/$pointspossible)*100);

                                $perarray = explode('.',$percentage);
                                $grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
                                                                        FROM itc_class_grading_scale_mapping 
                                                                        WHERE fld_class_id = '".$classid."' 
                                                                                AND fld_lower_bound <= '".$perarray[0]."' 
                                                                                AND fld_upper_bound >= '".$perarray[0]."' 
                                                                                AND fld_flag = '1'");
                            }

                            if($pointspossible=='')
                                $pointspossible = "-";
                            else
                            {
                                $totalpointsearned = $totalpointsearned + $pointsearned;
                                $totalpointspossible = $totalpointspossible + $pointspossible;
                            }
                            $out .= ', '.$percentage.' % '.$grade;
                            $newout .= ', '.$pointsearned.' / '.$pointspossible;
                            $newout1 .= ', ';

                        }
                    }
                    else
                    { 
                        $out .= ', ';
                        $newout .= ', No Modules';
                        $newout1 .= ', ';
                    }
                }
                else if($typeid=='2') //Expedition
                {
                    $pointsearned=0;
                    $pointspossible=0;
                    $expmodstudentcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                                FROM itc_class_rotation_modexpschedule_student_mappingtemp
                                                                                WHERE fld_schedule_id='".$scheduleids[$j]."' 
                                                                                                AND fld_student_id='".$fld_student_id."' 
                                                                                                AND fld_flag='1'");
                    if($expmodstudentcount!=0)
                    {
                        $expmodqry = $ObjDB->QueryObject("SELECT fld_module_id AS expid, 
                                                            (SELECT fld_exp_name FROM itc_exp_master WHERE fld_id=fld_module_id) AS expname  
                                                            FROM `itc_class_rotation_modexpschedulegriddet` 
                                                            WHERE fld_class_id='".$classid."' AND fld_module_id='".$ids."' AND fld_student_id='".$fld_student_id."' 
                                                            AND fld_schedule_id='".$scheduleids[$j]."' AND fld_rotation='".$rotid."'  AND fld_type='".$typeid."' 
                                                            AND fld_flag='1'  LIMIT 0,1");
                        $schexpid='';
                        $schexpname='';
                        if($expmodqry->num_rows>0)
                        {
                            $rowschexp=$expmodqry->fetch_assoc();
                            extract($rowschexp);
                            $schexpid=$expid;
                            $schexpname=$expname;
                        }

                        /************** Pre/Post test code start here ***************/
                        $pointsearnedfortest=0;
                        $possiblepointfortest1=0;
                        $possiblepointfortest=0;
                        
                        $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                            WHERE fld_class_id='".$classid."' AND fld_sch_id='".$scheduleids[$j]."' AND fld_texpid='".$schexpid."' AND fld_schtype_id='".$exptype."'");
          
                        if($qrytest->num_rows>0)
                        {
                            while($rowqrytest = $qrytest->fetch_assoc())
                            {
                                extract($rowqrytest);
                              
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

                                            $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master 
                                                                                            WHERE fld_student_id='".$fld_student_id."' AND fld_exp_id='".$schexpid."' 
                                                                                            AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$exptype."' 
                                                                                            AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                            if(trim($tchpointcnt)=='')
                                            {
                                                $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                        WHERE b.fld_expt = '".$schexpid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                                        AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."'
                                                                                                        AND a.fld_schedule_type='".$exptype."'
                                                                                                        AND b.fld_delstatus = '0' AND a.fld_delstatus = '0'");

                                               if(trim($correctcountfortestattend) != '')
                                               {
                                                   $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                        WHERE b.fld_expt = '".$schexpid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                                        AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."'
                                                                                                        AND a.fld_schedule_type='".$exptype."'
                                                                                                        AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");
                                                   
                                                    $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                                    $possiblepointfortest+=$possiblepointfortest1;
                                               }
                                            }
                                            else
                                            {
                                                $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                                WHEN fld_lock = '0' THEN fld_points_earned
                                                                                                WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$fld_student_id."'
                                                                                                AND fld_exp_id='".$schexpid."' AND fld_schedule_id='".$scheduleids[$j]."' 
                                                                                                AND fld_schedule_type='".$exptype."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                                if($tchpointearn!='')
                                                {
                                                    $possiblepointfortest+=$possiblepointfortest1;
                                                }

                                                $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
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

                                            $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master 
                                                                                            WHERE fld_student_id='".$fld_student_id."' AND fld_exp_id='".$schexpid."' 
                                                                                            AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$exptype."' 
                                                                                            AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                            if(trim($tchpointcnt)=='')
                                            {
                                                $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                        WHERE b.fld_expt = '".$schexpid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                                        AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."'
                                                                                                        AND a.fld_schedule_type='".$exptype."'
                                                                                                        AND b.fld_delstatus = '0' AND a.fld_delstatus = '0'");

                                               if(trim($correctcountfortestattend) != '')
                                               {
                                                   $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                        WHERE b.fld_expt = '".$schexpid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                                        AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."'
                                                                                                        AND a.fld_schedule_type='".$exptype."'
                                                                                                        AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");
                                                   
                                                    $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                                    $possiblepointfortest+=$possiblepointfortest1;
                                               }
                                            }
                                            else
                                            {
                                                $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                                WHEN fld_lock = '0' THEN fld_points_earned
                                                                                                WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$fld_student_id."'
                                                                                                AND fld_exp_id='".$schexpid."' AND fld_schedule_id='".$scheduleids[$j]."' 
                                                                                                AND fld_schedule_type='".$exptype."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                                if($tchpointearn!='')
                                                {
                                                    $possiblepointfortest+=$possiblepointfortest1;
                                                }

                                                $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                            }
                                        }
                                    }
                                }
                                /*********Post Test Code End Here*********/
                            }
                        }
                        /************** Pre/Post test code end here ***************/

                        /************** Rubric code start here ***************/
                        $pointsearnedrubric=0;
                        $pointspossiblerubric=0;

                        $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                            CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                            LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                            LEFT JOIN itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                                            LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
                                                                WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleids[$j]."' AND b.fld_exp_id='".$schexpid."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                                    AND a.fld_schedule_type='20' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                                    AND b.fld_school_id IN(0,".$schoolid.")");

                        if($qryrub->num_rows>0)
                        {
                            while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
                            {
                                extract($rowqryrub);

                                $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master 
                                                                        WHERE fld_exp_id='".$schexpid."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                                $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_expmodsch_rubric_rpt WHERE fld_exp_id='".$schexpid."'  
                                                                                    AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$classid."' 
                                                                                    AND fld_schedule_id='".$scheduleids[$j]."' AND fld_delstatus='0' "); 

                                $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_expmodsch_rubric_rpt_statement
                                                                            WHERE fld_student_id='".$fld_student_id."' AND fld_exp_id='".$schexpid."' AND fld_delstatus='0'
                                                                            AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                                $pointsearnedrubric = $pointsearnedrubric+$studentscore;
                                if($studentscore!=0)
                                {
                                    $pointspossiblerubric = $pointspossiblerubric+$totscore;
                                }
                            }
                        }

                        /************** Rubric code end here ***************/
                        $pointsearned=round($pointsearnedfortest + $pointsearnedrubric);
                        $pointspossible=$possiblepointfortest + $pointspossiblerubric;


                        //percentage code start here
                        if($pointsearned=='' AND $pointspossible!='0')
                        {
                            $pointsearned = "-";
                            $pointspossible="-";
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
                            $grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$classid."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
                        }
                        if($percentage==0)
                        {
                            $percentage = "-";
                            $grade = "NA";
                            $pointsearned = "-";
                            $pointspossible="-";
                        }
                        //percentage code end here

                        $out .= ', '.$percentage.' % '.$grade;
                        $newout .= ', '.$pointsearned.' / '.$pointspossible;
                        $newout1 .= ', ';
                    }
                    else
                    {   
                        $out .= ', ';
                        $newout .= ', No Expeditions';
                        $newout1 .= ', ';
                    }
                    $totalpointsearned = $totalpointsearned + $pointsearned;
                    $totalpointspossible = $totalpointspossible + $pointspossible;
                    //   echo $totalpointsearned."|".$totalpointspossible."<br>";  
                }
            }
        }
        else
        {   
            $out .= ', ';
            $newout .= ', No Expeditions/Modules';
            $newout1 .= ', ';
        }
    }
}
/**************************Expedition and Module schedule Code End here by Mohan**********************/	                  
 
/*********Mission report Code Start Here Developed By Mohan M 16-7-2015*************/	
else if($type[$j]==18) 
{ 
    $studentcountmis = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                FROM itc_class_mission_student_mapping 
                                                                WHERE fld_schedule_id='".$scheduleids[$j]."' 
                                                                                AND fld_student_id='".$fld_student_id."' 
                                                                                AND fld_flag='1'");
    
    if($studentcountmis!=0)
    {
        /*********Participation code Start here*******/
        $gradepointsearned = $ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned AS pointsearned FROM itc_mis_points_master 
                                                            WHERE fld_student_id='".$fld_student_id."' AND fld_mis_id='".$assid[$j]."' 
                                                                AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."' AND fld_mistype='4'
                                                                AND fld_grade='1' AND fld_delstatus='0'");

        $gradepointspossible = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_points_possible) AS pointspossible FROM itc_mis_points_master 
                                                                WHERE fld_student_id='".$fld_student_id."' AND fld_mis_id='".$assid[$j]."' 
                                                                    AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."'  AND fld_mistype='4' 
                                                                    AND fld_grade='1' AND fld_delstatus='0'");
        /*********Participation code end here*******/
        
        /************** Rubric code start here ***************/
        $pointsearnedrubric=0;
        $pointspossiblerubric=0;
        $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                            CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                LEFT JOIN itc_mis_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                LEFT JOIN itc_mission_master AS c ON a.fld_expmisid = c.fld_id
                                                LEFT JOIN itc_class_indasmission_master AS d ON a.fld_schedule_id=d.fld_id 
                                                    WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleids[$j]."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                        AND a.fld_schedule_type='18' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                        AND b.fld_school_id IN(0,".$schoolid.")");

        if($qryrub->num_rows>0)
        {
            while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
            {
                extract($rowqryrub);

                $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_master 
                                                        WHERE fld_mis_id='".$assid[$j]."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_rubric_rpt WHERE fld_mis_id='".$assid[$j]."'  
                                                                AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$classid."' 
                                                                AND fld_schedule_id='".$scheduleids[$j]."' AND fld_delstatus='0' "); 

                $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_rpt_statement
                                                                    WHERE fld_student_id='".$fld_student_id."' AND fld_mis_id='".$assid[$j]."' AND fld_delstatus='0'
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
                                            WHERE fld_class_id='".$classid."' AND fld_sch_id='".$scheduleids[$j]."' 
											AND fld_schtype_id='".$type[$j]."' AND fld_flag='1'");

        if($qrytest->num_rows>0)
        {
            while($rowqrytest = $qrytest->fetch_assoc())
            {
                extract($rowqrytest);
                $exptype='3';
                
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
                        $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0'");

                        $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_mis_points_master 
                                                                            WHERE fld_student_id='".$fld_student_id."' AND fld_mis_id='".$misid."' 
                                                                            AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."' 
                                                                            AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_mistype='3'");

                        if(trim($tchpointcnt)=='')
                        {
                            $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                        WHERE b.fld_mist = '".$misid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                        AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."' 
                                                                                        AND a.fld_schedule_type='".$type[$j]."' AND b.fld_delstatus = '0' AND a.fld_delstatus = '0'");

                            if(trim($correctcountfortestattend) != '')
                            {
                                $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                        WHERE b.fld_mist = '".$misid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                        AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."' 
                                                                                        AND a.fld_schedule_type='".$type[$j]."' AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");
                                
                                $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                $possiblepointfortest+=$possiblepointfortest1;
                            }
                        }
                        else
                        {
                            $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                WHEN fld_lock = '0' THEN fld_points_earned
                                                                                WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                END) AS pointsearned FROM itc_mis_points_master WHERE fld_student_id='".$fld_student_id."'
                                                                                AND fld_mis_id='".$misid."' AND fld_schedule_id='".$scheduleids[$j]."' 
                                                                                AND fld_schedule_type='".$type[$j]."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_mistype='3'");
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

        if($pointsearned=='')
        {
            $pointsearned = "-";
            $percentage = "-";
            $grade = "NA";
        }
        else
        {
            $pointsearned = round($pointsearned);
            $totalpointsearned = $totalpointsearned + $pointsearned;

            if($roundflag==0)
                $percentage = round(($pointsearned/$pointspossible)*100,2);
            else
                $percentage = round(($pointsearned/$pointspossible)*100);

            $perarray = explode('.',$percentage);

            $grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$classid."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
        }

        if($pointspossible=='')
            $pointspossible = "-";
        else
            $totalpointspossible = $totalpointspossible + $pointspossible;
        $out .= ', '.$percentage.' % '.$grade;
        $newout .= ', '.$pointsearned.' / '.$pointspossible;
        $newout1 .= ', ';
    }
    else
    { 
        $out .= ', ';
        $newout .= ', No Mission';
        $newout1 .= ', ';
    }

}
												
else if($type[$j]==23) 
{ 
    $incrementcountexp = $assid[$j];
    $totalcntexp = $maxassid[$j];

    for($z=$incrementcountexp;$z<=$totalcntexp;$z++)
    { 
        $pointearned1=0;$pointearned2=0;
        $pointspossible1=0;$pointspossible2=0;
        $expstudentcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                FROM itc_class_rotation_mission_student_mappingtemp
                                                                WHERE fld_schedule_id='".$scheduleids[$j]."' 
                                                                        AND fld_student_id='".$fld_student_id."' 
                                                                        AND fld_flag='1'");
        if($expstudentcount!=0)
        {
            $rotid=$z+1;                             
            $qryexpsch = $ObjDB->QueryObject("SELECT fld_mission_id AS misid, (SELECT fld_mis_name FROM itc_mission_master WHERE fld_id=fld_mission_id) AS expname 
                                                    FROM itc_class_rotation_mission_schedulegriddet WHERE fld_class_id='".$classid."' AND fld_student_id='".$fld_student_id."' 
                                                    AND fld_schedule_id='".$scheduleids[$j]."' AND fld_rotation='".$rotid."' AND fld_flag='1'  LIMIT 0,1");
            if($qryexpsch->num_rows>0)
            {
                while($rowqryexpsch = $qryexpsch->fetch_assoc())
                {
                    extract($rowqryexpsch);

                    $pointearned1 = $ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned AS pointsearned FROM itc_mis_points_master 
                                                                    WHERE fld_student_id='".$fld_student_id."' AND fld_mis_id='".$misid."' 
                                                                        AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."' 
                                                                        AND fld_grade='1' AND fld_mistype='4'");
                  
                    /************** Rubric code start here ***************/
                    $pointsearnedrubric=0;
                    $pointspossiblerubric=0;

                    $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                        CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                        LEFT JOIN itc_mis_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                        LEFT JOIN itc_mission_master AS c ON a.fld_expmisid = c.fld_id
                                                        LEFT JOIN itc_class_rotation_mission_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
                                                            WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleids[$j]."' AND b.fld_mis_id='".$misid."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                                AND a.fld_schedule_type='19' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                                AND b.fld_school_id IN(0,".$schoolid.")");

                    if($qryrub->num_rows>0)
                    {
                        while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
                        {
                            extract($rowqryrub);

                            $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_master 
                                                                        WHERE fld_mis_id='".$misid."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                            $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_missch_rubric_rpt WHERE fld_mis_id='".$misid."'  
                                                                            AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$classid."' 
                                                                            AND fld_schedule_id='".$scheduleids[$j]."' AND fld_delstatus='0' "); 

                            $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_missch_rubric_rpt_statement
                                                                            WHERE fld_student_id='".$fld_student_id."' AND fld_mis_id='".$misid."' AND fld_delstatus='0'
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
                                                    WHERE fld_class_id='".$classid."' AND fld_sch_id='".$scheduleids[$j]."'  AND fld_mis_id='".$misid."'
                                                    AND fld_schtype_id='20' AND fld_flag='1'");

                    if($qrytest->num_rows>0)
                    {
                        while($rowqrytest = $qrytest->fetch_assoc())
                        {
                            extract($rowqrytest);
                            $exptype='3';

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
                                    $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0'");

                                    $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_mis_points_master 
                                                                                        WHERE fld_student_id='".$fld_student_id."' AND fld_mis_id='".$misid."' 
                                                                                        AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='20' 
                                                                                        AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_mistype='3'");

                                    if(trim($tchpointcnt)=='')
                                    {
                                        $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                WHERE b.fld_mist = '".$misid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                                AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."' 
                                                                                                AND a.fld_schedule_type='20' AND b.fld_delstatus = '0' AND a.fld_delstatus = '0'");

                                        if(trim($correctcountfortestattend) != '')
                                        {
                                            $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                WHERE b.fld_mist = '".$misid."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                                AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleids[$j]."' 
                                                                                                AND a.fld_schedule_type='20' AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");

                                                $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                                $possiblepointfortest+=$possiblepointfortest1;
                                       }
                                    }
                                    else
                                    {
                                        $tchpointearn = $ObjDB->SelectSingleValue("SELECT (CASE
                                                                                                WHEN fld_lock = '0' THEN fld_points_earned
                                                                                                WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                END) AS pointsearned FROM itc_mis_points_master WHERE fld_student_id='".$fld_student_id."'
                                                                                                AND fld_mis_id='".$misid."' AND fld_schedule_id='".$scheduleids[$j]."' 
                                                                                                AND fld_schedule_type='20' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_mistype='3'");
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
                    /************** Test code end here ***************/
                    
                    if($pointearned1=='0')
                    {
                        $peflag1=0;
                        $pointpossible1='0';		
                    }
                    else
                    {
                        $peflag1=1;
                        $pointpossible1=100;	
                    }

                    $pointsearned = $pointearned1 + $pointsearnedrubric + $pointsearnedfortest;;
                    $pointspossible = $pointpossible1 + $pointspossiblerubric  + $possiblepointfortest;;

                    if($pointsearned == '' || $pointsearned == '0')
                    {
                        $pointsearned = "-";
                        $percentage = "-";
                        $grade = "NA";
                        $pointspossible = "-";
                    }
                    else
                    {
                        $pointsearned = round($pointsearned);

                        if($roundflag==0)
                                $percentage = round(($pointsearned/$pointspossible)*100,2);
                        else
                                $percentage = round(($pointsearned/$pointspossible)*100);

                        $perarray = explode('.',$percentage);

                        $grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$classid."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
                    }

                    $out .= ', '.$percentage.' % '.$grade;
                    $newout .= ', '.$pointsearned.' / '.$pointspossible;
                    $newout1 .= ', ';
                }	

            }
            $totalpointsearned = $totalpointsearned + $pointsearned;
            $totalpointspossible = $totalpointspossible + $pointspossible;
        }
        else
        { 
            $out .= ', ';
            $newout .= ', No Mission';
            $newout1 .= ', ';
        }
    }
}												
/*********Mission report Code End Here Developed By Mohan M 16-7-2015*************/
                    
                    else if($type[$j]==9) 
                    {
                        $studentcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) 
                                                                        FROM itc_test_student_mapping AS a
                                                                        WHERE a.fld_class_id='".$classid."' AND a.fld_test_id='".$assid[$j]."' AND a.fld_flag='1' AND a.fld_student_id='".$fld_student_id."' ".$sqry."");

                      if($studentcount!=0)
                        {
                            $qrypoints = $ObjDB->QueryObject("SELECT a.fld_score AS score, a.fld_total_question,  a.fld_question_type 
                                                                                            FROM itc_test_master AS a 
                                                                                            LEFT JOIN itc_test_student_mapping AS b ON a.fld_id=b.fld_test_id 
                                                                                            WHERE a.fld_flag='1' AND a.fld_delstatus='0' 
                                                                                                    AND b.fld_student_id='".$fld_student_id."' 
                                                                                                    AND b.fld_class_id='".$classid."' AND b.fld_flag='1' 
                                                                                                    AND a.fld_id='".$assid[$j]."'");
                            if($qrypoints->num_rows>0)
                            {
                                $pointsearned = '';
                                while($rowqrypoints = $qrypoints->fetch_object())
                                {
                                    $pointspossible = $rowqrypoints->score;
                                    $totalques = $rowqrypoints->fld_total_question;
                                    $testtype = $rowqrypoints->fld_question_type;

                                    $qcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                                        FROM itc_test_student_answer_track 
                                                                                        WHERE fld_student_id='".$fld_student_id."' 
                                                                                                AND fld_test_id='".$assid[$j]."' 
                                                                                                AND fld_delstatus='0' AND fld_schedule_id='0' AND fld_schedule_type='0'");

                                    $teacherpoint = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned 
                                                                                        FROM itc_test_student_mapping 
                                                                                        WHERE fld_student_id='".$fld_student_id."' 
                                                                                                AND fld_test_id='".$assid[$j]."' 
                                                                                                AND fld_flag='1' AND fld_class_id='".$classid."'");

                                    if($teacherpoint=='')
                                    {
                                        if($testtype == '1')
                                        {
                                            /*  changes made from here by vijayalakshmi on 4th december 2014 **/
                                            $correctcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) 
                                                                                            FROM itc_test_student_answer_track 
                                                                                            WHERE fld_student_id='".$fld_student_id."' 
                                                                                                    AND fld_test_id='".$assid[$j]."' 
                                                                                                    AND fld_correct_answer='1' 
                                                                                                    AND fld_delstatus='0' AND fld_result_flag<>'2' AND fld_schedule_id='0' AND fld_schedule_type='0'");

                                            $parialanscnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) FROM itc_test_student_answer_track 
                                                                                                WHERE fld_student_id='".$fld_student_id."' AND fld_test_id='".$assid[$j]."' AND fld_delstatus='0' AND fld_result_flag='2' AND fld_schedule_id='0' AND fld_schedule_type='0'");

                                            $parialcnt = $ObjDB->QueryObject("SELECT fld_correct_answer as partans FROM itc_test_student_answer_track 
                                                                                WHERE fld_student_id='".$fld_student_id."' AND fld_test_id='".$assid[$j]."' AND fld_delstatus='0' AND fld_result_flag='2' AND fld_schedule_id='0' AND fld_schedule_type='0'");
                                            if ($parialcnt->num_rows > 0) {
                                                $dummyans_pt = 0;
                                                while ($rowqrypartialscore = $parialcnt->fetch_assoc()) {
                                                    extract($rowqrypartialscore);

                                                    $dummyans_pt = $dummyans_pt + $partans;
                                                }
                                            }

                                            $pointsearned = round(($correctcount / $totalques) * $pointspossible);

                                            if ($dummyans_pt != 0) {
                                                $pointsearned = $pointsearned + $dummyans_pt;
                                            }
                                            //$dummyans_pt = 0;
                                        }
                                        else if($testtype == '2')
                                        {

                                            $qryrandomtest = $ObjDB->QueryObject("SELECT fld_id AS testtagid, fld_pct_section AS percent, fld_qn_assign AS totques
                                                                                    FROM itc_test_random_questionassign
                                                                                    WHERE fld_rtest_id='".$assid[$j]."' AND fld_delstatus='0' 
                                                                                    ORDER BY fld_order_by");
                                            if($qryrandomtest->num_rows>0)
                                            {

                                                $perscore = 0;
                                                while($rowqryrandomtest = $qryrandomtest->fetch_assoc())
                                                {
                                                    extract($rowqryrandomtest);
                                                   // $perscore = ($percent / 100)*$pointspossible;
                                                    $perscore = $perscore + $percent;

                                                    $correctcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) 
                                                                                                    FROM itc_test_student_answer_track 
                                                                                                    WHERE fld_student_id='".$fld_student_id."' AND fld_test_id='".$assid[$j]."' AND fld_tag_id='".$testtagid."'
                                                                                                            AND fld_correct_answer='1' AND fld_delstatus='0' AND fld_result_flag<>'2' AND fld_schedule_id='0' AND fld_schedule_type='0'");

                                                    $parialanscnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) FROM itc_test_student_answer_track 
                                                                                                    WHERE fld_student_id='".$fld_student_id."' AND fld_test_id='".$assid[$j]."' AND fld_tag_id='".$testtagid."' AND fld_delstatus='0' AND fld_result_flag='2' AND fld_schedule_id='0' AND fld_schedule_type='0'");

                                                    $parialcnt = $ObjDB->QueryObject("SELECT fld_correct_answer as partans FROM itc_test_student_answer_track 
                                                                                        WHERE fld_student_id='".$fld_student_id."' AND fld_test_id='".$assid[$j]."' AND fld_tag_id='".$testtagid."' AND fld_delstatus='0' AND fld_result_flag='2' AND fld_schedule_id='0' AND fld_schedule_type='0'");

                                                    if ($parialcnt->num_rows > 0) {
                                                        $dummyans_pt_rand = 0;
                                                        while ($rowqrypartialscore = $parialcnt->fetch_assoc()) {
                                                            extract($rowqrypartialscore);

                                                            $dummyans_pt_rand = $dummyans_pt_rand + $partans;
                                                        }
                                                    }

                                                    $pointsearned = $pointsearned + round($correctcount * ($percent / $totques));

                                                    if ($dummyans_pt_rand != 0) {
                                                        $pointsearned = $pointsearned + $dummyans_pt_rand;
                                                    }

                                                    $dummyans_pt_rand = 0;
                                                }
                                            }
                                            $pointsearned = round(($pointsearned/$perscore) * $pointspossible);
                                            /*  changes made from here by vijayalakshmi on 4th december 2014 (end) **/

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
                                        $grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$classid."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
                                    }
                                }
                                if($qcount!=0 || $teacherpoint!='')
                                {
                                    $totalpointsearned = $totalpointsearned + $pointsearned;
                                    $totalpointspossible = $totalpointspossible + $pointspossible;
                                }                                                                                                                                
                                $out .= ', '.$percentage.' % '.$grade;
                                $newout .= ', '.$pointsearned.' / '.$pointspossible;
                                $newout1 .= ', ';
                            }
                        }
                        else
                        { 
                            $out .= ', ';
                            $newout .= ', No Assessment';
                            $newout1 .= ', ';
                        }
                    }
                    
                    else if($type[$j]==10) 
                    {
                        $studentcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_activity_student_mapping WHERE fld_class_id='".$classid."' AND fld_student_id='".$fld_student_id."' AND fld_activity_id='".$assid[$j]."' AND fld_flag='1'");

                        if($studentcount!=0)
                        {
                            $qrypoints = $ObjDB->QueryObject("SELECT SUM(fld_points_earned) AS pointsearned, SUM(fld_points_possible) AS pointspossible FROM itc_activity_student_mapping WHERE fld_class_id='".$classid."' AND fld_student_id='".$fld_student_id."' AND fld_activity_id='".$assid[$j]."' AND fld_flag='1' AND fld_points_earned<>''");
                            if($qrypoints->num_rows>0)
                            {
                                while($rowqrypoints = $qrypoints->fetch_assoc()) // show the module based on number of copies
                                {
                                    extract($rowqrypoints);
                                    
                                    if($pointsearned=='')
                                    {
                                        $pointsearned = "-";
                                        $percentage = "-";
                                        $grade = "NA";
                                    }
                                    else
                                    {
                                        $totalpointsearned = $totalpointsearned + $pointsearned;
                                        if($roundflag==0)
                                            $percentage = round(($pointsearned/$pointspossible)*100,2);
                                        else
                                            $percentage = round(($pointsearned/$pointspossible)*100);

                                        $perarray = explode('.',$percentage);
                                        $grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$classid."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
                                    }

                                    if($pointspossible=='')
                                            $pointspossible = "-";
                                    else
                                            $totalpointspossible = $totalpointspossible + $pointspossible;
                                    
                                    $out .= ', '.$percentage.' % '.$grade;
                                    $newout .= ', '.$pointsearned.' / '.$pointspossible;
                                    $newout1 .= ', ';
                                }
                            }
                        }
                        else
                        { 
                            $out .= ', ';
                            $newout .= ', No Activity';
                            $newout1 .= ', ';
                        }
                    } 
                    
                    else 
                    {
                        $oriencunt = 0;
                        if($type[$j]==2)
                        {
                            $schegridtable = "itc_class_dyad_schedulegriddet";
                            $schestudenttable = "itc_class_dyad_schedule_studentmapping";										
                            $schname = "Dyad";
                        }
                        if($type[$j]==3)
                        {
                            $schegridtable = "itc_class_triad_schedulegriddet";
                            $schestudenttable = "itc_class_triad_schedule_studentmapping";										
                            $schname = "Triad";
                        }
                        if($type[$j]==2 or $type[$j]==3)
                        {
                            $oriencunt = $ObjDB->SelectSingleValue("SELECT COUNT(a.fld_rotation) FROM ".$schegridtable." AS a 
                                                                    LEFT JOIN ".$schestudenttable." AS b ON (a.fld_schedule_id=b.fld_schedule_id 
                                                                        AND b.fld_student_id='".$fld_student_id."')
                                                                    WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleids[$j]."' AND a.fld_flag='1' 
                                                                        AND a.fld_rotation='0' AND b.fld_flag='1' ".$sqry1."");
                        }

                        if($oriencunt==1)
                        {
                            $incrementcount = $assid[$j];
                            $totalcnt = $maxassid[$j];
                        }
                        else
                        {
                            $incrementcount = $assid[$j];
                            $totalcnt = $maxassid[$j];
                        }

                        for($k=$incrementcount;$k<=$totalcnt;$k++)
                        {
                            if($type[$j]==1) 
                            {
                                $l=$k;
                                $l++;

                                $qrymod = $ObjDB->QueryObject("SELECT fld_module_id AS modids, (SELECT fld_module_name FROM itc_module_master WHERE fld_id=fld_module_id) AS modulename, 1 AS newtype  FROM `itc_class_rotation_schedulegriddet` WHERE fld_class_id='".$classid."' AND fld_student_id='".$fld_student_id."' AND fld_schedule_id='".$scheduleids[$j]."' AND fld_rotation='".$l."' AND fld_flag='1' AND fld_type = '1' 
                                UNION ALL 		SELECT a.fld_id AS modids, a.fld_contentname AS modulename, 8 AS newtype FROM itc_customcontent_master AS a LEFT JOIN itc_class_rotation_schedulegriddet AS b ON b.fld_module_id = a.fld_id WHERE b.fld_class_id='".$classid."' AND b.fld_student_id='".$fld_student_id."' AND b.fld_schedule_id = '".$scheduleids[$j]."' AND fld_rotation='".$l."' AND b.fld_type = '8' AND b.fld_flag = '1' AND a.fld_delstatus = '0' ");
                            }
                            else if($type[$j]==2) 
                            {
                                $dyad=$k;
                                $qrymod = $ObjDB->QueryObject("SELECT fld_module_id AS modids, (SELECT fld_module_name FROM itc_module_master WHERE fld_id=fld_module_id) AS modulename, 2 AS newtype FROM `itc_class_dyad_schedulegriddet` WHERE fld_class_id='".$classid."' AND (fld_student_id='".$fld_student_id."' OR fld_rotation='0') AND fld_schedule_id='".$scheduleids[$j]."' AND fld_rotation='".$dyad."' AND fld_flag='1'");
                            }
                            else if($type[$j]==3) 
                            {
                                $triad=$k;
                                $qrymod = $ObjDB->QueryObject("SELECT fld_module_id AS modids, (SELECT fld_module_name FROM itc_module_master WHERE fld_id=fld_module_id) AS modulename, 3 AS newtype FROM `itc_class_triad_schedulegriddet` WHERE fld_class_id='".$classid."' AND (fld_student_id='".$fld_student_id."' OR fld_rotation='0') AND fld_schedule_id='".$scheduleids[$j]."' AND fld_rotation='".$triad."' AND fld_flag='1'");
                            }
                            else if($type[$j]==4) 
                            {
                                $l=$k;
                                $l++;

                                $qrymod = $ObjDB->QueryObject("SELECT a.fld_module_id AS modids, CONCAT((SELECT fld_mathmodule_name FROM itc_mathmodule_master WHERE fld_id=a.fld_module_id),' MM') AS modulename, 4 AS newtype FROM `itc_class_rotation_schedulegriddet` AS a WHERE a.fld_class_id='".$classid."' AND a.fld_student_id='".$fld_student_id."' AND a.fld_schedule_id='".$scheduleids[$j]."' AND a.fld_rotation='".$l."' AND a.fld_flag='1' AND a.fld_type='2'
                                UNION ALL 		SELECT a.fld_id AS modids, a.fld_contentname AS modulename, 8 AS newtype FROM itc_customcontent_master AS a LEFT JOIN itc_class_rotation_schedulegriddet AS b ON b.fld_module_id = a.fld_id WHERE b.fld_class_id='".$classid."' AND b.fld_student_id='".$fld_student_id."' AND b.fld_schedule_id = '".$scheduleid[$j]."' AND fld_rotation='".$l."' AND b.fld_type = '8' AND b.fld_flag = '1' AND a.fld_delstatus = '0'");
                            }
                            
                            if($qrymod->num_rows>0)
                            {
                                while($rowqrymod = $qrymod->fetch_assoc()) // show the module based on number of copies
                                {
                                    extract($rowqrymod);

                                    if($newtype==4)
                                    {
                                        $qrymath = $ObjDB->QueryObject("SELECT fld_ipl_day1 AS ipld1, fld_ipl_day2 AS ipld2
                                                                                                        FROM itc_mathmodule_master 
                                                                                                        WHERE fld_id='".$modids."'");
                                        $rowqrymath=$qrymath->fetch_assoc();
                                        extract($rowqrymath);
                                    }

                                    if($newtype==4)
                                    {
                                        $qrypoints = $ObjDB->QueryObject("SELECT SUM(w.earned) AS pointsearned, SUM(w.possible) AS pointspossible FROM ((SELECT SUM(CASE WHEN fld_lock = '0' THEN fld_points_earned WHEN fld_lock = '1' THEN fld_teacher_points_earned END) AS earned, SUM(fld_points_possible) AS possible FROM itc_module_points_master WHERE fld_student_id = '".$fld_student_id."' AND fld_schedule_id = '".$scheduleids[$j]."' AND fld_schedule_type = '".$newtype."' AND fld_module_id = '".$modids."' AND (fld_points_earned<>'' OR fld_teacher_points_earned<>'') AND fld_grade<>'0')	
                                                                                    UNION ALL 		
                                                                            (SELECT ROUND(SUM(CASE WHEN a.fld_lock = '0' THEN a.fld_points_earned WHEN a.fld_lock = '1' 
                                                                                    THEN a.fld_teacher_points_earned END) / 4) AS earned, ROUND(SUM(a.fld_points_possible) / 4) AS possible 
                                                                                FROM itc_assignment_sigmath_master AS a 
                                                                                WHERE a.fld_student_id = '".$fld_student_id."' AND a.fld_test_type = '2' 
                                                                                    AND a.fld_schedule_id = '".$scheduleids[$j]."' and a.fld_module_id='".$modids."'
                                                                                    AND (a.fld_lesson_id IN (".$ipld1.") OR a.fld_lesson_id IN (".$ipld2.")) 
                                                                                    AND (a.fld_points_earned <> '' OR a.fld_teacher_points_earned <> '') AND a.fld_delstatus='0' 
                                                                                    AND (a.fld_status='1' OR a.fld_status='2' OR a.fld_lock='1') AND a.fld_unitmark = '0')) AS w");
                                    }

                                    else
                                        $qrypoints = $ObjDB->QueryObject("SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END) AS pointsearned, SUM(fld_points_possible) AS pointspossible FROM itc_module_points_master WHERE fld_student_id='".$fld_student_id."' AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$newtype."' AND fld_module_id='".$modids."' AND (fld_points_earned<>'' OR fld_teacher_points_earned<>'') AND fld_grade<>'0'");

                                    if($qrypoints->num_rows>0)
                                    {
                                        $rowqrypoints = $qrypoints->fetch_assoc();
                                        extract($rowqrypoints);
                                    }
                                    if($pointsearned=='')
                                    {
                                        $pointsearned = "-";
                                        $percentage = "-";
                                        $grade = "N/A";
                                    }
                                    else if($pointspossible!='')
                                    {
                                        if($roundflag==0)
                                            $percentage = round(($pointsearned/$pointspossible)*100,2);
                                        else
                                            $percentage = round(($pointsearned/$pointspossible)*100);

                                        $perarray = explode('.',$percentage);
                                        $grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
                                                                            FROM itc_class_grading_scale_mapping 
                                                                            WHERE fld_class_id = '".$classid."' AND fld_lower_bound <= '".$perarray[0]."' 
                                                                                    AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
                                    }

                                    if($pointspossible=='')
                                        $pointspossible = "-";
                                    else
                                    {
                                        $totalpointsearned = $totalpointsearned + $pointsearned;
                                        $totalpointspossible = $totalpointspossible + $pointspossible;
                                    }
                                    $out .= ', '.$modulename;
                                    $newout .= ', '.$percentage.' % '.$grade;
                                    $newout1 .= ', '.$pointsearned.' / '.$pointspossible;
                                }
                            }
                            else
                            { 
                                $out .= ', ';
                                $newout .= ', No Modules';
                                $newout1 .= ', ';
                            }
                        }
                    }
                }
                
            }
            
            if($totalpointsearned=='')
            {
                $totalpointsearned = "-";
                $totalpercentage = "-";
                $totalgrade = "NA";
            }
            else
            {
                if($roundflag==0)
                    $totalpercentage = round(($totalpointsearned/$totalpointspossible)*100,2);
                else
                    $totalpercentage = round(($totalpointsearned/$totalpointspossible)*100);

                $perarray = explode('.',$totalpercentage);
                $totalgrade = $ObjDB->SelectSingleValue("SELECT fld_grade 
                                                            FROM itc_class_grading_scale_mapping 
                                                            WHERE fld_class_id = '".$classid."' 
                                                                            AND fld_lower_bound <= '".$perarray[0]."' 
                                                                            AND fld_upper_bound >= '".$perarray[0]."' 
                                                                            AND fld_flag = '1'");
            }
            if($totalpointspossible=='')
                $totalpointspossible = "-";
            
            
            $out .= ', ';
            $newout .= ','.$totalpointsearned.','.$totalpointspossible.','.$totalpercentage.' %,'.$totalgrade;
            $newout1 .= ', ';
            
            $out .= "\n";
            $out .= " ".$newout."\n".$newout1."\n\n";
            $newout = '';
            $newout1 = '';
        }
    }
}

if($id[0]==3)
{
	$unitname = $ObjDB->SelectSingleValue("SELECT fld_unit_name FROM itc_unit_master WHERE fld_id='".$id[4]."'");
	
	if($id[6]==0) { $msg = $unitname; } 
	else if($id[6]==10) { $msg = "Activity"; } 
	else if($id[6]==9) { $msg = "Assessment"; } 
	else if($id[6]==8) { $msg = $ObjDB->SelectSingleValue("SELECT fld_contentname FROM itc_customcontent_master WHERE fld_id='".$id[4]."'"); } 
	else if($id[6]==15) { $msg = $ObjDB->SelectSingleValue("SELECT fld_exp_name FROM itc_exp_master WHERE fld_id='".$id[4]."'"); } 
	else if($id[6]==4 || $id[6]==6) { $msg = $ObjDB->SelectSingleValue("SELECT fld_mathmodule_name FROM itc_mathmodule_master WHERE fld_id='".$id[4]."'"); } 
	else { $msg = $ObjDB->SelectSingleValue("SELECT fld_module_name FROM itc_module_master WHERE fld_id='".$id[4]."'"); }
	
	$csv_hdr = 'Student Name, '.$id[2];
	$out .= $csv_hdr;
        $out .= "\n\n";
	$out .= $msg.', Points Earned, Points Possible';
	$out .= "\n\n";
	
	if($id[6]==0) {
		$qry = $ObjDB->QueryObject("SELECT a.fld_lesson_id, b.fld_ipl_name, b.fld_ipl_points as fld_points_possible, 
										0 as fld_points_earned 
									FROM `itc_class_sigmath_lesson_mapping` AS a 
									LEFT JOIN itc_ipl_master AS b ON b.fld_id=a.fld_lesson_id 
									WHERE a.fld_sigmath_id='".$id[5]."' AND a.fld_flag='1' AND b.fld_unit_id='".$id[4]."' 
									ORDER BY a.fld_order");
		
		if($qry->num_rows>0)
		{
			while($rowqry = $qry->fetch_assoc()) 
			{
				extract($rowqry);
				$qrypoints = $ObjDB->QueryObject("SELECT a.fld_ipl_points as fld_points_possible, (CASE WHEN b.fld_lock='0' 
													THEN b.fld_points_earned WHEN b.fld_lock='1' 
													THEN b.fld_teacher_points_earned END) AS earnedpoints 
												FROM itc_ipl_master AS a 
												LEFT JOIN itc_assignment_sigmath_master AS b ON b.fld_lesson_id=a.fld_id 
												WHERE b.fld_class_id='".$classid."' AND b.fld_student_id='".$id[3]."' 
													AND a.fld_id='".$fld_lesson_id."' AND b.fld_schedule_id='".$id[5]."' 
													AND (b.fld_status='1' OR b.fld_status='2')");
				$earnedpoints='';
				if($qrypoints->num_rows>0)
				{
					$rowqrypoints = $qrypoints->fetch_assoc();
					extract($rowqrypoints);
				}
				
				$out .= $fld_ipl_name.', '.$earnedpoints.', '.$fld_points_possible;		
				$out .="\n";		
			}
		}
		
		$out .= "Math Connection: ".$unitname.', ';
		
		$cgaearned = $ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned 
													FROM itc_assignment_sigmath_master 
													WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$id[5]."' 
														AND fld_unit_id='".$id[4]."' AND fld_unitmark='1' 
														AND fld_student_id='".$id[3]."' AND fld_delstatus='0'");
		
		$out .= $cgaearned.', 100';
		$out .= "\n\n";
	} 
	
	else if($id[6]==9) {
		$qry = $ObjDB->QueryObject("SELECT b.fld_id, b.fld_test_name, b.fld_score AS pointspossible, 
										b.fld_total_question AS totalques, b.fld_question_type AS testtype  
									FROM itc_test_student_mapping AS a 
									LEFT JOIN itc_test_master AS b ON a.fld_test_id=b.fld_id 
									WHERE b.fld_delstatus='0' AND a.fld_test_id='".$id[4]."' 
										AND a.fld_student_id='".$id[3]."' AND a.fld_class_id='".$classid."' AND a.fld_flag='1' 
									GROUP BY b.fld_id");
		
		if($qry->num_rows>0)
		{
			while($rowqry = $qry->fetch_assoc())
			{
				extract($rowqry);
				
				$teacherpoint=='';
				
				$qcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
														FROM itc_test_student_answer_track 
														WHERE fld_student_id='".$id[3]."' AND fld_test_id='".$id[4]."' AND fld_delstatus='0'");

				$teacherpoint = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned 
															FROM itc_test_student_mapping 
															WHERE fld_student_id='".$id[3]."' AND fld_test_id='".$id[4]."' 
																	AND fld_flag='1' AND fld_class_id='".$classid."'");
				
				if($teacherpoint=='')
				{
					if($testtype == '1')
					{
						$correctcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) 
																		FROM itc_test_student_answer_track 
																		WHERE fld_student_id='".$id[3]."' AND fld_test_id='".$id[4]."' 
																				AND fld_correct_answer='1' AND fld_delstatus='0'");
						$pointsearned = round(($correctcount/$totalques)*$pointspossible,2);
					}
					else if($testtype == '2')
					{
						$qryrandomtest = $ObjDB->QueryObject("SELECT fld_id AS testtagid, fld_pct_section AS percent, fld_qn_assign AS totques
																FROM itc_test_random_questionassign
																WHERE fld_rtest_id='".$id[4]."' AND fld_delstatus='0' 
																ORDER BY fld_order_by");
						if($qryrandomtest->num_rows>0)
						{
							while($rowqryrandomtest = $qryrandomtest->fetch_assoc())
							{
								extract($rowqryrandomtest);

								$perscore = ($percent / 100)*$pointspossible;

								$correctcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) 
																				FROM itc_test_student_answer_track 
																				WHERE fld_student_id='".$id[3]."' AND fld_test_id='".$id[4]."' AND fld_tag_id='".$testtagid."'
																						AND fld_correct_answer='1' AND fld_delstatus='0'");

								$pointsearned = $pointsearned + round($correctcount*($perscore/$totques));
							}
						}
					}
				}
												
				if($qcount==0)
					$pointsearned = '';
												
				if($teacherpoint!='')
					$pointsearned = $teacherpoint;
				
				$out .= $fld_test_name.', '.$pointsearned.', '.$pointspossible;
				$out .="\n";
			}
		}
	}
	
	else if($id[6]==10) 
	{
		$qry = $ObjDB->QueryObject("SELECT b.fld_id, b.fld_activity_name, b.fld_activity_points AS pointspossible, 
										a.fld_points_earned AS fld_points_earned 
									FROM itc_activity_student_mapping AS a 
									LEFT JOIN itc_activity_master AS b ON a.fld_activity_id=b.fld_id 
									WHERE b.fld_delstatus='0' AND a.fld_activity_id='".$id[4]."' 
										AND a.fld_class_id='".$classid."' AND a.fld_created_by='".$uid."' 
										AND a.fld_flag='1' AND a.fld_student_id='".$id[3]."'  ");
		
		if($qry->num_rows>0)
		{
			$rowqry = $qry->fetch_assoc(); 
			extract($rowqry);
			
			$out .= $fld_activity_name.', '.$fld_points_earned.', '.$pointspossible;
			$out .="\n";
		}
	}
	
	else if($id[6]==8) 
	{
		$qry = $ObjDB->QueryObject("SELECT a.fld_id, a.fld_contentname AS customname, a.fld_pointspossible AS possiblepoint, 
										(SELECT fld_teacher_points_earned 
										FROM itc_module_points_master 
										WHERE fld_schedule_type='".$id[6]."' AND fld_student_id='".$id[3]."' 
											AND fld_module_id='".$id[4]."' AND fld_schedule_id='".$id[5]."') AS pointsearned 
									FROM itc_customcontent_master AS a 
									LEFT JOIN itc_class_rotation_schedulegriddet AS b ON a.fld_id=b.fld_module_id 
									WHERE b.fld_module_id='".$id[4]."' AND a.fld_delstatus='0' 
										AND b.fld_class_id='".$classid."' AND b.fld_schedule_id='".$id[5]."' 
										AND b.fld_flag='1' AND b.fld_type='8' 
									GROUP BY a.fld_id");
		
		if($qry->num_rows>0)
		{
			$rowqry = $qry->fetch_assoc(); 
			extract($rowqry);
			
			$out .= $customname.', '.$pointsearned.', '.$possiblepoint;
			$out .="\n";
		}
	}
	
	else if($id[6]==15) 
	{
		for($i=2;$i<4;$i++) {
			$qry = $ObjDB->QueryObject("SELECT a.fld_id, a.fld_exptype AS exptype, a.fld_pointspossible AS possiblepoint, 
											(SELECT fld_teacher_points_earned 
											FROM itc_exp_points_master 
											WHERE fld_schedule_type='".$id[6]."' AND fld_student_id='".$id[3]."' 
												AND fld_exp_id='".$id[4]."' AND fld_schedule_id='".$id[5]."' AND fld_exptype='".$i."') AS pointsearned 
										FROM itc_class_exp_grade AS a
										WHERE a.fld_exp_id='".$id[4]."'
											AND a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$id[5]."' 
											AND a.fld_flag='1' AND a.fld_exptype='".$i."'
										ORDER BY a.fld_exptype");
			
			if($qry->num_rows>0)
			{
				while($rowqry = $qry->fetch_assoc())
				{
					extract($rowqry);
					
					if($i==3 and $pointsearned=='')
					{
						$qryques = $ObjDB->QueryObject("SELECT IFNULL(b.fld_total_question,'-') AS quescount, COUNT(a.fld_id) AS correctcount FROM itc_test_student_answer_track AS a LEFT JOIN itc_test_master AS b ON a.fld_test_id=b.fld_id WHERE b.fld_expt='".$id[4]."' AND a.fld_student_id='".$id[3]."' AND b.fld_delstatus='0' AND a.fld_show='1' AND a.fld_delstatus='0'");
									
						if($qryques->num_rows>0)
						{
							$rowqryques = $qryques->fetch_assoc();
							extract($rowqryques);
							
							if($quescount==='-')
								$pointsearned = '';
							else
								$pointsearned = round($correctcount*($possiblepoint/$quescount),2);
						}
					}
					
					if($exptype==2) $title = "Performance Assesment"; else if($exptype==3) $title = "Summative Assesment";
					$out .= $title.', '.$pointsearned.', '.$possiblepoint;
					$out .="\n";
				}
			}
		}
	}
	
	else if($id[6]==7)
	{
		$totalchapters = $ObjDB->SelectSingleValueInt("SELECT MAX(fld_session_id)+1 
														FROM itc_module_performance_master 
														WHERE fld_module_id='".$id[4]."'");
		$j=0;
		for($i=0;$i<$totalchapters;$i++)
		{
			$j++;
			
			$qry = $ObjDB->QueryObject("(SELECT a.fld_preassment_id AS ids, a.fld_page_title AS titlename, a.fld_points 
										AS possiblepoint, (SELECT (CASE WHEN b.fld_lock='0' THEN b.fld_points_earned 
															WHEN b.fld_lock='1' THEN b.fld_teacher_points_earned END) 
															FROM itc_module_points_master AS b 
															WHERE b.fld_module_id=a.fld_module_id 
																AND b.fld_session_id=a.fld_session_id 
																AND a.fld_preassment_id=b.fld_preassment_id 
																AND b.fld_student_id='".$id[3]."' 
																AND b.fld_schedule_id='".$id[5]."' 
																AND b.fld_schedule_type='".$id[6]."' AND b.fld_delstatus='0') AS pointsearned, 
									a.fld_type AS typename
									FROM itc_module_wca_grade AS a 
									LEFT JOIN itc_class_indassesment_master AS c ON (a.fld_module_id=c.fld_module_id)
									WHERE a.fld_module_id='".$id[4]."' AND a.fld_session_id='".$i."' AND a.fld_schedule_id='".$id[5]."' 
										AND a.fld_flag='1' AND c.fld_class_id='".$classid."' AND c.fld_flag='1' 
									GROUP BY a.fld_preassment_id 
									ORDER BY typename, a.fld_preassment_id)");
			
			if($qry->num_rows <= 0)
			{
				$qry = $ObjDB->QueryObject("(SELECT a.fld_preassment_id AS ids, a.fld_page_title AS titlename, a.fld_points 
											AS possiblepoint, (SELECT (CASE WHEN b.fld_lock='0' THEN b.fld_points_earned 
																WHEN b.fld_lock='1' THEN b.fld_teacher_points_earned END) 
																FROM itc_module_points_master AS b 
																WHERE b.fld_module_id=a.fld_module_id 
																	AND b.fld_session_id=a.fld_session_id 
																	AND a.fld_preassment_id=b.fld_preassment_id 
																	AND b.fld_student_id='".$id[3]."' 
																	AND b.fld_schedule_id='".$id[5]."' 
																	AND b.fld_schedule_type='".$id[6]."') AS pointsearned, 
										a.fld_type AS typename
										FROM itc_module_wca_grade AS a 
										LEFT JOIN itc_class_indassesment_master AS c ON (a.fld_module_id=c.fld_module_id)
										WHERE a.fld_module_id='".$id[4]."' AND a.fld_session_id='".$i."' AND a.fld_flag='1' AND c.fld_class_id='".$classid."' 
											AND c.fld_flag='1'  AND a.fld_school_id='".$schoolid."' AND a.fld_user_id='".$indid."'
										GROUP BY a.fld_preassment_id 
										ORDER BY typename, a.fld_preassment_id)");
									
				if($qry->num_rows <= 0)
				{
					$createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
					
					$qry = $ObjDB->QueryObject("(SELECT a.fld_preassment_id AS ids, a.fld_page_title AS titlename, a.fld_points 
												AS possiblepoint, (SELECT (CASE WHEN b.fld_lock='0' THEN b.fld_points_earned 
																	WHEN b.fld_lock='1' THEN b.fld_teacher_points_earned END) 
																	FROM itc_module_points_master AS b 
																	WHERE b.fld_module_id=a.fld_module_id 
																		AND b.fld_session_id=a.fld_session_id 
																		AND a.fld_preassment_id=b.fld_preassment_id 
																		AND b.fld_student_id='".$id[3]."' 
																		AND b.fld_schedule_id='".$id[5]."' 
																		AND b.fld_schedule_type='".$id[0]."') AS pointsearned, 
												a.fld_type AS typename
												FROM itc_module_wca_grade AS a 
												LEFT JOIN itc_class_indassesment_master AS c ON (a.fld_module_id=c.fld_module_id)
												WHERE a.fld_module_id='".$id[4]."' AND a.fld_session_id='".$i."' AND c.fld_flag='1' 
													AND a.fld_flag='1' AND c.fld_class_id='".$classid."' AND a.fld_created_by IN (".$createdids.")
												GROUP BY a.fld_preassment_id 
												ORDER BY typename, a.fld_preassment_id)");
					
					if($qry->num_rows <= 0)
					{
						$qry = $ObjDB->QueryObject("(SELECT a.fld_page_id AS ids, a.fld_page_title AS titlename, a.fld_points 
													AS possiblepoint, (SELECT (CASE WHEN b.fld_lock='0' THEN b.fld_points_earned 
																		WHEN b.fld_lock='1' THEN b.fld_teacher_points_earned END) 
																		FROM itc_module_points_master AS b 
																		WHERE b.fld_module_id=a.fld_module_id AND b.fld_type='0' 
																			AND b.fld_session_id=a.fld_section_id 
																			AND a.fld_page_id=b.fld_preassment_id 
																			AND b.fld_student_id='".$id[3]."' 
																			AND b.fld_schedule_id='".$id[5]."' 
																			AND b.fld_schedule_type='".$id[0]."') AS pointsearned, 
													'0' AS typename
												FROM itc_module_quest_details AS a 
												LEFT JOIN itc_class_indassesment_master AS c ON (a.fld_module_id=c.fld_module_id)
												WHERE a.fld_module_id='".$id[4]."' AND a.fld_section_id='".$i."' 
													AND a.fld_flag='1' AND c.fld_class_id='".$classid."' AND c.fld_flag='1' GROUP BY a.fld_page_id 
												ORDER BY a.fld_page_id)
														UNION ALL
												(SELECT a.fld_id AS ids, a.fld_performance_name AS titlename, a.fld_points_possible 
													AS possiblepoint, (SELECT (CASE WHEN b.fld_lock='0' THEN b.fld_points_earned
																		WHEN b.fld_lock='1' THEN b.fld_teacher_points_earned END) 
																		FROM itc_module_points_master AS b 
																		WHERE a.fld_module_id=b.fld_module_id 
																		AND a.fld_session_id=b.fld_session_id 
																		AND a.fld_id=b.fld_preassment_id AND b.fld_type='3' 
																		AND b.fld_student_id='".$id[3]."' 
																		AND b.fld_schedule_id='".$id[5]."' 
																		AND b.fld_schedule_type='".$id[6]."') AS pointsearned, 
												'3' AS typename 
												FROM itc_module_performance_master AS a 
												WHERE a.fld_module_id='".$id[4]."' AND a.fld_delstatus='0' 
													AND a.fld_performance_name<>'Attendance' 
													AND a.fld_performance_name<>'Participation' 
													AND a.fld_performance_name<>'Total Pages' AND a.fld_session_id='".$i."' GROUP BY a.fld_id)");
					}
				}
			}
			
			if($qry->num_rows>0)
			{
				$out .= "Chapter ".$j;
				$out .="\n";
				while($row=$qry->fetch_assoc())
				{
					extract($row);
					
					$out .= $titlename.', '.$pointsearned.', '.$possiblepoint;
					$out .="\n";
				}
			}
			$out .="\n\n";
		}
	} 
	
	else 
	{
		for($i=0;$i<8;$i++)
		{
			$j=$i;	$j++;
			
			if($i==7)
			{
				if($id[0]==4 || $id[0]==6)
					$qry = $ObjDB->QueryObject("SELECT a.fld_performance_name, a.fld_points_possible, (SELECT (CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END) FROM `itc_module_points_master` WHERE fld_student_id='".$id[3]."' AND fld_schedule_id='".$id[5]."' AND fld_schedule_type='".$id[6]."' AND fld_module_id='".$id[4]."' AND fld_preassment_id=a.fld_id) AS points FROM `itc_module_performance_master` AS a WHERE a.fld_module_id = (SELECT fld_module_id FROM itc_mathmodule_master WHERE fld_id='".$id[4]."' AND fld_delstatus='0') AND a.fld_delstatus='0' AND  a.fld_performance_name<>'Attendance' AND  a.fld_performance_name<>'Participation' AND a.fld_performance_name<>'Total Pages'");
				else
					$qry = $ObjDB->QueryObject("SELECT a.fld_performance_name, a.fld_points_possible, (SELECT (CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END) FROM `itc_module_points_master` WHERE fld_student_id='".$id[3]."' AND fld_schedule_id='".$id[5]."' AND fld_schedule_type='".$id[6]."' AND fld_module_id='".$id[4]."' AND fld_preassment_id=a.fld_id) AS points FROM `itc_module_performance_master` AS a WHERE a.fld_module_id='".$id[4]."' AND a.fld_delstatus='0' AND a.fld_performance_name<>'Attendance' AND a.fld_performance_name<>'Participation' AND a.fld_performance_name<>'Total Pages'");
				$pername = array();
				$pointpossible = array();
				$pointsearned = array();
				$cnt=0;
				while($row=$qry->fetch_assoc())
				{
					extract($row);
					$pername[$cnt] = $fld_performance_name;
					if($fld_points_possible=='')
					{
						$pointpossible[$cnt] = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_page_title='".$fld_performance_name."' AND fld_flag='1' AND fld_module_id='".$id[4]."' AND fld_schedule_id='".$id[5]."' AND fld_type='3'");
						if($pointpossible[$cnt] == 0 or $pointpossible[$cnt] == '')
						{
							$pointpossible[$cnt] = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_page_title='".$fld_performance_name."' AND fld_flag='1' AND fld_module_id='".$id[4]."' AND fld_type='3' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."'");
							if($pointpossible[$cnt] == 0 or $pointpossible[$cnt] == '')
							{
								$createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
								
								$pointpossible[$cnt] = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_page_title='".$fld_performance_name."' AND fld_flag='1' AND fld_module_id='".$id[4]."' AND fld_type='3' AND fld_created_by IN (".$createdids.")");
								
								if($pointpossible[$cnt] == 0 or $pointpossible[$cnt] == '')
									$pointpossible[$cnt] = '20';
							}
						}
					}
					else
						$pointpossible[$cnt] = $fld_points_possible;
					
					$pointsearned[$cnt] = $points;									
					$cnt++;
				}
			}
			else if($i<7)
			{
				if($id[6]==4 or $id[6]==6)
				{
					$newschtype = '2';
				}
				else
				{
					$newschtype = '1';
				}
				if($id[6]==1 || $id[6]==4) 
				{
					$qry = "SELECT a.fld_session_id, a.fld_type, (CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN 
								a.fld_lock='1' THEN a.fld_teacher_points_earned END) AS pointsearned, 
								fld_points_possible AS possiblepoint 
							FROM `itc_module_points_master` AS a 
							LEFT JOIN `itc_class_rotation_schedulegriddet` AS b 
								ON (a.fld_module_id=b.fld_module_id) 
							WHERE a.fld_student_id='".$id[3]."' AND a.fld_module_id='".$id[4]."' 
								AND a.fld_schedule_id='".$id[5]."' AND a.fld_schedule_type='".$id[6]."' 
								AND b.fld_class_id='".$classid."' AND a.fld_session_id='".$i."' AND b.fld_flag='1' 
							GROUP BY a.fld_type";
				}
				else if($id[6]==2) 
				{
					$qry = "SELECT a.fld_session_id, a.fld_type, (CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN 
								a.fld_lock='1' THEN a.fld_teacher_points_earned END) AS pointsearned, 
								fld_points_possible AS possiblepoint 
							FROM `itc_module_points_master` AS a 
							LEFT JOIN `itc_class_dyad_schedulegriddet` AS b ON (a.fld_module_id=b.fld_module_id) 
							WHERE a.fld_student_id='".$id[3]."' AND a.fld_module_id='".$id[4]."' 
								AND a.fld_schedule_id='".$id[5]."' AND a.fld_schedule_type='".$id[6]."' 
								AND b.fld_class_id='".$classid."' AND a.fld_session_id='".$i."' AND b.fld_flag='1' 
							GROUP BY a.fld_type";
				}
				else if($id[0]==3) 
				{
					$qry = "SELECT a.fld_session_id, a.fld_type, (CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN 
								a.fld_lock='1' THEN a.fld_teacher_points_earned END) AS pointsearned, 
								fld_points_possible AS possiblepoint 
							FROM `itc_module_points_master` AS a 
							LEFT JOIN `itc_class_triad_schedulegriddet` AS b ON (a.fld_module_id=b.fld_module_id) 
							WHERE a.fld_student_id='".$id[3]."' AND a.fld_module_id='".$id[4]."' 
								AND a.fld_schedule_id='".$id[5]."' AND a.fld_schedule_type='".$id[6]."' 
								AND b.fld_class_id='".$classid."' AND a.fld_session_id='".$i."' AND b.fld_flag='1' 
							GROUP BY a.fld_type";
				}
				else if($id[0]==5 || $id[0]==6) 
				{
					$qry = "SELECT a.fld_session_id, a.fld_type, (CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN 
								a.fld_lock='1' THEN a.fld_teacher_points_earned END) AS pointsearned, 
								fld_points_possible AS possiblepoint 
							FROM `itc_module_points_master` AS a 
							LEFT JOIN `itc_class_indassesment_master` AS b ON (a.fld_module_id=b.fld_module_id) 
							WHERE a.fld_student_id='".$id[3]."' AND a.fld_module_id='".$id[4]."' 
								AND a.fld_schedule_id='".$id[5]."' AND a.fld_schedule_type='".$id[6]."' 
								AND b.fld_class_id='".$classid."' AND a.fld_session_id='".$i."' AND b.fld_flag='1' 
							GROUP BY a.fld_type";
				}
				if($i==0)
					$pagetitle='Module Guide';
				if($i==1)
					$pagetitle='RCA 2';
				if($i==2)
					$pagetitle='RCA 3';
				if($i==3)
					$pagetitle='RCA 4';
				if($i==4)
					$pagetitle='RCA 5';
				if($i==6)
					$pagetitle='Post Test';
				$pointsearned = array();
				$cnt=0;
				
				$qrydetails = $ObjDB->QueryObject($qry);
				if($qrydetails->num_rows>0)
				{
					while($row=$qrydetails->fetch_object())
					{
						if($row->fld_type==0)
							$pointsearned[0] = $row->pointsearned;
						else if($row->fld_type==1)
							$pointsearned[1] = $row->pointsearned;
						else if($row->fld_type==2)
							$pointsearned[2] = $row->pointsearned;
							
						$cnt++;
					}
				}
			}
			
			$createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
			
			$sesspossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_session_id='".$i."' AND fld_page_title='".$pagetitle."' AND fld_flag='1' AND fld_module_id='".$id[4]."' AND fld_schedule_id='".$id[5]."' AND fld_type='0'");
			
			$attenpossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_page_title='Attendance' AND fld_flag='1' AND fld_module_id='".$id[4]."' AND fld_schedule_id='".$id[5]."' AND fld_type='1'");
			
			$partipossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_page_title='Participation' AND fld_flag='1' AND fld_module_id='".$id[4]."' AND fld_schedule_id='".$id[5]."' AND fld_type='2'");
			
			if($sesspossible==0)
			{
				$sesspossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_session_id='".$i."' AND fld_page_title='".$pagetitle."' AND fld_flag='1' AND fld_module_id='".$id[4]."' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_type='0' AND fld_schedule_type='".$newschtype."'");
				if($sesspossible==0)
				{
					$sesspossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_session_id='".$i."' AND fld_page_title='".$pagetitle."' AND fld_flag='1' AND fld_module_id='".$id[4]."' AND fld_type='0' AND fld_schedule_type='".$newschtype."' AND fld_created_by IN (".$createdids.")");
					
					if($sesspossible==0)
					{
						$sesspossible = $ObjDB->SelectSingleValueInt("SELECT fld_points 
																	FROM itc_module_grade 
																	WHERE fld_page_title='".$pagetitle."' AND fld_flag='1' AND fld_session_id='".$i."'
																	AND fld_module_id='".$id[4]."'");
					}
				}
				
				if($attenpossible==0)
				{
					$attenpossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_page_title='Attendance' AND fld_flag='1' AND fld_module_id='".$id[4]."'  AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_type='1' AND fld_schedule_type='".$newschtype."'");
					if($attenpossible==0)
					{
						$attenpossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_page_title='Attendance' AND fld_flag='1' AND fld_module_id='".$id[4]."' AND fld_type='1' AND fld_schedule_type='".$newschtype."' AND fld_created_by IN (".$createdids.")");
						if($attenpossible==0)
						{
							$attenpossible = $ObjDB->SelectSingleValueInt("SELECT fld_points_possible 
																		FROM itc_module_performance_master 
																		WHERE fld_session_id='".$i."'
																		AND fld_module_id='".$id[4]."'");
						}
					}
				}
				
				if($partipossible==0)
				{
					$partipossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_page_title='Participation' AND fld_flag='1' AND fld_module_id='".$id[4]."'  AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_type='2' AND fld_schedule_type='".$newschtype."'");
					if($partipossible==0)
					{
						$partipossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_page_title='Participation' AND fld_flag='1' AND fld_module_id='".$id[4]."' AND fld_type='2' AND fld_schedule_type='".$newschtype."' AND fld_created_by IN (".$createdids.")");
						if($partipossible==0)
						{
							$partipossible = $ObjDB->SelectSingleValueInt("SELECT fld_points_possible 
																	FROM itc_module_performance_master 
																	WHERE fld_session_id='".$i."'
																	AND fld_module_id='".$id[4]."'");
						}
					}
				}
			}
			
			if($i<7) { $title = "Session ".$j; } else { $title = "Performance"; }
			$out .= $title;
			$out .="\n";
			
			if($i!=5) {
				if($i<7) { $titlename = $pagetitle; $sesposmark = $sesspossible;} else { $titlename = $pername[0]; $sesposmark = $pointpossible[0];} 
				$out .= $titlename.', '.$pointsearned[0].', '.$sesposmark;
				$out .="\n";
			}
			
			if($i<7) { $atttitle = "Attendance"; $attposmark = $attenpossible;} else { $atttitle = $pername[1]; $attposmark = $pointpossible[1];}
			$out .= $atttitle.', '.$pointsearned[1].', '.$attposmark;
			$out .="\n";
			
			if($i<7) { $partititle = "Participation"; $partiposmark = $partipossible;} else { $partititle = $pername[2]; $partiposmark = $pointpossible[2];}
			$out .= $partititle.', '.$pointsearned[2].', '.$partiposmark;
			$out .="\n";
			
			
			if($id[6]==4 || $id[6]==6) {
				if($id[6]==4)
					$testtype = 2;
				if($id[6]==6)
					$testtype = 5;
				$home = $i; $home++;
				$qrymath = $ObjDB->QueryObject("SELECT fld_session_day1, fld_session_day2, fld_ipl_day1, fld_ipl_day2 
												FROM itc_mathmodule_master 
												WHERE fld_id='".$id[4]."'");
				$rowqrymath=$qrymath->fetch_assoc();
				extract($rowqrymath);
				
				$sessids = $home;
				if($sessids==$fld_session_day1)
				{
					$day = "Diagnostic Day1"; 
					
					$earnedqry = $ObjDB->QueryObject("SELECT ROUND(SUM(CASE WHEN fld_lock='0' THEN fld_points_earned 
															WHEN fld_lock='1' THEN fld_teacher_points_earned END)/4) as earned  
														FROM itc_assignment_sigmath_master 
														WHERE fld_schedule_id='".$id[5]."' AND fld_student_id='".$id[3]."' 
															AND fld_test_type='".$testtype."' AND fld_class_id='".$classid."' 
															AND fld_lesson_id IN (".$fld_ipl_day1.") AND fld_delstatus='0' AND fld_module_id=".$id[4]."
															AND fld_unitmark='0' AND (fld_status='1' OR fld_status='2' OR fld_lock='1')");
					$rowearnedqry=$earnedqry->fetch_assoc();
					extract($rowearnedqry);
				
					$diagids = $fld_ipl_day1;
					$dtype="1";
				}
				else if($sessids==$fld_session_day2)
				{
					$day = "Diagnostic Day2"; 
					
					$earnedqry = $ObjDB->QueryObject("SELECT ROUND(SUM(CASE WHEN fld_lock='0' THEN fld_points_earned 
															WHEN fld_lock='1' THEN fld_teacher_points_earned END)/4) as earned  
														FROM itc_assignment_sigmath_master 
														WHERE fld_schedule_id='".$id[5]."' AND fld_student_id='".$id[3]."' 
															AND fld_test_type='".$testtype."' AND fld_class_id='".$classid."' 
															AND fld_lesson_id IN (".$fld_ipl_day2.") AND fld_delstatus='0' AND fld_module_id=".$id[4]."
															AND fld_unitmark='0' AND (fld_status='1' OR fld_status='2' OR fld_lock='1')");
					$rowearnedqry=$earnedqry->fetch_assoc();
					extract($rowearnedqry);
					
					$diagids = $fld_ipl_day2;
					$dtype="2";
				}
				if($sessids==$fld_session_day1 || $sessids==$fld_session_day2)
				{
					$out .= $day.', '.$earned.', 100';
					$out .="\n";
				}
			}
			
			$out .="\n\n";
		}
	} 
}

//Now we're ready to create a file. This method generates a filename based on the current date & time.
$name=str_replace(' ','_',$id[2]);
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