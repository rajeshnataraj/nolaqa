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

$id = explode("~",$ids);

$assid = explode(",",$id[3]);

//print_r($id);
//Class Schedule report
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
	   
	   	foreach ($assid as $value) {
         $indid = explode("-",$value);
         
         $id[5]=$indid[2];
         $id[3]=$indid[0];
         $id[4]=$indid[1];
		
		$out .= $indid[3];
		$out .= "\n";
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
                                        $tpointsearned = $ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned 
                                                                                        FROM itc_exp_points_master 
                                                                                        WHERE fld_schedule_type='".$id[5]."' AND fld_student_id='".$id[2]."' 
                                                                                            AND fld_exp_id='".$id[4]."' AND fld_schedule_id='".$id[3]."' AND fld_exptype='3' AND fld_res_id='".$testid."' AND fld_delstatus = '0'");
                                        if($tpointsearned=='' ||  $tpointsearned=='0')
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
                                        $tpointsearned = $ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned 
                                                                                        FROM itc_exp_points_master 
                                                                                        WHERE fld_schedule_type='".$id[5]."' AND fld_student_id='".$id[2]."' 
                                                                                            AND fld_exp_id='".$id[4]."' AND fld_schedule_id='".$id[3]."' AND fld_exptype='3' AND fld_res_id='".$testid."' AND fld_delstatus = '0'");
                                        if($tpointsearned=='' ||  $tpointsearned=='0')
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
						$out .= "Total".",".$totpointsearned.",".$totpointspossible;
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
                                    $tpointsearned = $ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned 
                                                                                    FROM itc_mis_points_master
                                                                                    WHERE fld_student_id='".$id[2]."' AND (fld_schedule_type='18' OR fld_schedule_type='20')
                                                                                        AND fld_mis_id='".$id[4]."' AND fld_schedule_id='".$id[3]."' AND fld_mistype='3' AND fld_res_id='".$testid."' AND fld_delstatus = '0'");
                                    if($tpointsearned=='' ||  $tpointsearned=='0')
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
						$out .= "Total".",".$totpointsearned.",".$totpointspossible;
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
					if($grade==1)
                                        {
                                            $totpointsearned=$totpointsearned+$pointsearned;
                                            $totpointspossible=$totpointspossible+$pointspossible;
                                        }
					// created by chandra 
					if($counts==$qryindividual->num_rows)
					{
						$out .= "\n";
						$out .= "Total".",".$totpointsearned.",".$totpointspossible;
						$out .= "\n";
					}
                }

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

                            if($earned!='')
                            {
                                $out .= $day." , Points Earned , Points Possible";
                                $out .= "\n";

                                $out .= $day." , ".$earned." , 100";
                                $out .= "\n\n";
                            }
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