 <?php 
@include("sessioncheck.php");

/*
	Created By - Muthukumar. D
	Page - reports-gradereports-gradeajax.php
	Updated By: Mohan M	
	History:
	

*/

$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';


//error_reporting(E_ALL);
//ini_set('display_errors', '1');
/*--- Load Table ---*/
if($oper=="showtable" and $oper != " " )
{
    $classid = isset($method['classid']) ? $method['classid'] : '';
    $oriencunt = 1;
    ?>
    <div class="gridtableouter" style="margin-left:45px; margin-bottom:20px;" >
    <table class="fancyTable" id="myTable05" cellpadding="0" cellspacing="0" style="padding-bottom:20px;">
        <thead>
            <tr>
                <?php
                $roundflag = $ObjDB->SelectSingleValue("SELECT fld_roundflag 
                                                                FROM itc_class_grading_scale_mapping 
                                                                WHERE fld_class_id = '".$classid."' AND fld_flag = '1' 
                                                                GROUP BY fld_roundflag");

                $qryhead = $ObjDB->QueryObject("SELECT fld_id, fld_grade_name, fn_shortname (fld_grade_name, 1) AS shortname, fld_start_date, fld_end_date 
                                                        FROM itc_reports_gradebook_master 
                                                        WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_created_by='".$uid."'");
                ?>
                <th align="center" class="stuhead" <?php if($qryhead->num_rows<=0){?> style="width:410px; height:39px; border-bottom:0;" <?php } ?>><span style="font-size:14px;">Student Name</span></th>
                <th align="center" class="stuhead" style="cursor:pointer; height:40px; width:<?php if($qryhead->num_rows<=0) {?>410px;<?php }else {?>300px<?php }?>" onclick="removesections('#reports-gradebook');  fn_show(0,<?php echo $classid; ?>,0,0);"><span style="font-size:14px;" class="tooltip" original-title="Click to Expand Grade Details">Overall</span></th>
                <?php 
                if ($qryhead->num_rows > 0) {
                    $cnt = 0;
                    while ($rowqryhead = $qryhead->fetch_assoc()) { // show the module based on number of copies
                        extract($rowqryhead);
                        $startdate[$cnt] = $fld_start_date;
                        $enddate[$cnt] = $fld_end_date;
                        $gradeid[$cnt] = $fld_id;
                        $cnt++;
                        ?>
                            <th align="center" class="stuhead" style="width:300px;">
                            <div style="width: 85%; cursor:pointer" onclick="removesections('#reports-gradebook'); fn_show(<?php echo $fld_id; ?>,<?php echo $classid; ?>,0,0);">
                                    <span style="font-size:14px;" class="tooltip" original-title="Click to Expand Grade Details"><?php echo $shortname; ?></span>
                                </div>
                                <div style="margin-top: -35px; ">
                                    <div class="icon-synergy-edit too-small-icon" title="Edit <?php echo $fld_grade_name; ?>" style="text-align:right; margin-left:180px; height:14px; margin-top:-22px; width: 15px; cursor:pointer" onclick="removesections('#reports-gradebook'); fn_showupdate(<?php echo $fld_id; ?>);"></div>
                                    <div class="icon-synergy-trash too-small-icon" title="Delete <?php echo $fld_grade_name; ?>" style="text-align:right; width: 15px; margin-left:180px; cursor:pointer" onclick="removesections('#reports-gradebook'); fn_remove(<?php echo $fld_id; ?>);"></div>
                                </div>
                        </th>
                    <?php
                }
            }
            ?>
            </tr>
        </thead>
        <tbody id="body">
            <?php
            $qrystudent = $ObjDB->QueryObject("SELECT a.fld_student_id AS studentid, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname 
                                                FROM itc_class_student_mapping AS a 
                                                LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                WHERE a.fld_class_id = '".$classid."' AND a.fld_flag = '1' AND b.fld_activestatus = '1' 
                                                        AND b.fld_delstatus = '0' 
                                                ORDER BY studentname");
            if($qrystudent->num_rows>0)
            {
                    while($rowqrystudent = $qrystudent->fetch_assoc()) // show the module based on number of copies
                    {
                        extract($rowqrystudent);

                        ?>
                        <tr>
                            <td id="student" name="<?php echo $studentid;?>" class="studentth" align="center" style="font-size:14px;"><div><?php echo $studentname;?></div></td>
                            <?php
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
                                    //$sqry = "AND (b.fld_start_date BETWEEN '".$startdate[$i-1]."' AND '".$enddate[$i-1]."' OR b.fld_end_date BETWEEN '".$startdate[$i-1]."' AND '".$enddate[$i-1]."')";
                                    //$sqry1 = " AND (b.fld_startdate BETWEEN '".$startdate[$i-1]."' AND '".$enddate[$i-1]."' OR b.fld_enddate BETWEEN '".$startdate[$i-1]."' AND '".$enddate[$i-1]."')";
                                    $sqry = "AND ('".$startdate[$i-1]."' BETWEEN b.fld_start_date AND b.fld_end_date OR '".$enddate[$i-1]."' BETWEEN b.fld_start_date AND b.fld_end_date OR b.fld_start_date BETWEEN '".$startdate[$i-1]."' AND '".$enddate[$i-1]."' OR b.fld_end_date BETWEEN '".$startdate[$i-1]."' AND '".$enddate[$i-1]."')";
                                    $sqry1 = " AND ('".$startdate[$i-1]."' BETWEEN b.fld_startdate AND b.fld_enddate OR '".$enddate[$i-1]."' BETWEEN b.fld_startdate AND b.fld_enddate OR b.fld_startdate BETWEEN '".$startdate[$i-1]."' AND '".$enddate[$i-1]."' OR b.fld_enddate BETWEEN '".$startdate[$i-1]."' AND '".$enddate[$i-1]."')";
                                    $sqry2 = " AND ('".$startdate[$i-1]."' BETWEEN c.fld_startdate AND c.fld_enddate OR '".$enddate[$i-1]."' BETWEEN c.fld_startdate AND c.fld_enddate OR c.fld_startdate BETWEEN '".$startdate[$i-1]."' AND '".$enddate[$i-1]."' OR c.fld_enddate BETWEEN '".$startdate[$i-1]."' AND '".$enddate[$i-1]."')";
                                }

                                $expearned = '';
                                $exppossible = '';
                                $testearned = '';
                                $testpossible = '';
					
$qryexp = $ObjDB->QueryObject("SELECT b.fld_id AS scheduleid, b.fld_exp_id AS expid FROM itc_exp_master AS a 
                                    LEFT JOIN itc_class_indasexpedition_master AS b ON b.fld_exp_id=a.fld_id 
                                    WHERE b.fld_class_id='".$classid."' AND b.fld_flag='1' AND b.fld_delstatus='0' 
                                            AND a.fld_delstatus='0'   ".$sqry1."
                                            GROUP BY b.fld_id");
if($qryexp->num_rows>0)
{
    $exptearned = '';
    $exptpossible = '';
    while($rowqryexp = $qryexp->fetch_assoc())
    {
        extract($rowqryexp);
		
        /*************EXP pre test or POST test Code Start Here**************/
        $pointsearnedfortest=0;
        $possiblepointfortest1=0;
        $possiblepointfortest=0;
		
        $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                            WHERE fld_class_id='".$classid."' AND fld_sch_id='".$scheduleid."' AND fld_schtype_id='15'");

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
                            $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master 
                                                                                                    where fld_id='".$testid."' and fld_delstatus='0';");
                            $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master 
                                                                                WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$expid."' 
                                                                                AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='15' 
                                                                                AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                            if(trim($tchpointcnt)=='')
                            {
                                $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                        WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$studentid."' 
                                                                                        AND a.fld_test_id='".$testid."'  AND a.fld_schedule_id='".$scheduleid."' 
                                                                                        AND a.fld_schedule_type='15'  AND b.fld_delstatus = '0' 
                                                                                AND a.fld_delstatus = '0'");

                                if(trim($correctcountfortestattend) != '')
                                {
                                    $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                        WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$studentid."' 
                                                                                        AND a.fld_test_id='".$testid."'  AND a.fld_schedule_id='".$scheduleid."' 
                                                                                        AND a.fld_schedule_type='15'  AND b.fld_delstatus = '0' AND a.fld_show = '1' 
                                                                                AND a.fld_delstatus = '0'");
                                    
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
                                                                                    AND fld_schedule_type='15' AND fld_grade='1' AND fld_res_id='".$testid."' 
                                                                                    AND fld_exptype='3'");
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
                            $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master 
                                                                                                    where fld_id='".$testid."' and fld_delstatus='0';");
                            $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master 
                                                                                WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$expid."' 
                                                                                AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='15' 
                                                                                AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                            if(trim($tchpointcnt)=='')
                            {
                                $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                        WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$studentid."' 
                                                                                        AND a.fld_test_id='".$testid."'  AND a.fld_schedule_id='".$scheduleid."' 
                                                                                        AND a.fld_schedule_type='15'  AND b.fld_delstatus = '0'
                                                                                AND a.fld_delstatus = '0'");

                                if(trim($correctcountfortestattend) != '')
                                {
                                    $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                        WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$studentid."' 
                                                                                        AND a.fld_test_id='".$testid."'  AND a.fld_schedule_id='".$scheduleid."' 
                                                                                        AND a.fld_schedule_type='15'  AND b.fld_delstatus = '0' AND a.fld_show = '1' 
                                                                                AND a.fld_delstatus = '0'");
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
                                                                                    AND fld_schedule_type='15' AND fld_grade='1' AND fld_res_id='".$testid."' 
                                                                                    AND fld_exptype='3'");
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
                                                        LEFT JOIN itc_class_indasexpedition_master AS d ON a.fld_schedule_id=d.fld_id 
                                                                WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                                        AND a.fld_schedule_type='15' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                                        AND b.fld_school_id IN(0,".$schoolid.")");

        if($qryrub->num_rows>0)
        {
                while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
                {
                        extract($rowqryrub);

                        $destinationids = $ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_exp_rubric_dest_master WHERE fld_rubric_name_id='".$rubricids."' AND fld_exp_id='".$expid."'"); 

                        $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master 
                                                                                                                WHERE fld_exp_id='".$expid."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                        $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_rpt WHERE fld_exp_id='".$expid."'  
                                                                                                                 AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$classid."' 
                                                                                                                 AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' "); 

                        $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_rpt_statement
                                                                                                                        WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$expid."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
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
        //echo $pointsearned."-".$pointspossible."<br>";
        $exptearned=$exptearned+$pointsearned;
        $exptpossible=$exptpossible+$pointspossible;
    }
    /*************EXP pre test or POST test Code Start Here**************/
    
   // echo $exptearned."".$exptpossible."<br>";
    /*************EXP pre test or POST test Code End Here**************/
}
else
{
    $exptearned = '';
    $exptpossible = '';
}

/**************************Expedition schedule start here by Naren But Now MOhan M**********************/
$qryexp= $ObjDB->QueryObject("SELECT d.fld_id AS scheduleid,b.fld_expedition_id AS expid FROM itc_class_rotation_expschedulegriddet AS b
				LEFT JOIN itc_class_rotation_expscheduledate AS c ON b.fld_schedule_id = c.fld_schedule_id AND b.fld_rotation = c.fld_rotation
				LEFT JOIN itc_class_rotation_expschedule_mastertemp AS d ON c.fld_schedule_id = d.fld_id
				WHERE b.fld_student_id = '".$studentid."' AND b.fld_class_id = '".$classid."' AND b.fld_flag = '1'
					AND d.fld_delstatus = '0' ".$sqry2."");
if($qryexp->num_rows>0)
{
    $schexptearned = '';
    $schexptpossible = '';
    $pointsearnedfortest=0;
    $possiblepointfortest1=0;
    $possiblepointfortest=0;
    $pointsearnedrubric=0;
    $pointspossiblerubric=0;
    while($rowqryexp = $qryexp->fetch_assoc())
    {
        extract($rowqryexp);
        /*************EXP pre test or POST test Code Start Here**************/
        $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                        WHERE fld_class_id='".$classid."' AND fld_sch_id='".$scheduleid."' AND fld_texpid='".$expid."' AND fld_schtype_id='19'");

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
                                                                                            AND a.fld_schedule_type='19'
                                                                                    AND a.fld_test_id='".$testid."' AND b.fld_delstatus = '0' 
                                                                                    AND a.fld_delstatus = '0'");

                                if(trim($correctcountfortestattend) != '')
                                {
                                    $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                    LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                    WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                    AND a.fld_schedule_id='".$scheduleid."' 
                                                                                            AND a.fld_schedule_type='19'
                                                                                    AND a.fld_test_id='".$testid."' AND b.fld_delstatus = '0' AND a.fld_show = '1' 
                                                                                    AND a.fld_delstatus = '0'");
                                    
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
                                                                                    AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                                                            AND a.fld_schedule_type='19' AND b.fld_delstatus = '0' 
                                                                                    AND a.fld_delstatus = '0'");

                                if(trim($correctcountfortestattend) != '')
                                {
                                    $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                    LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                    WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$studentid."' 
                                                                                    AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                                                            AND a.fld_schedule_type='19' AND b.fld_delstatus = '0' AND a.fld_show = '1' 
                                                                                    AND a.fld_delstatus = '0'");
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
        $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                                        CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                                                LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                                                LEFT JOIN itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                                                                LEFT JOIN itc_class_rotation_expschedule_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
                                                                                        WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND b.fld_exp_id='".$expid."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                                                                AND a.fld_schedule_type='17' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                                                                AND b.fld_school_id IN(0,".$schoolid.")");

        if($qryrub->num_rows>0)
        {
            while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
            {
                extract($rowqryrub);

                $destinationids = $ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_exp_rubric_dest_master WHERE fld_rubric_name_id='".$rubricids."' AND fld_exp_id='".$expid."'"); 

                $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master 
                                                                                                                WHERE fld_exp_id='".$expid."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_expsch_rubric_rpt WHERE fld_exp_id='".$expid."'  
                                                                                                                 AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$classid."' 
                                                                                                                 AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' "); 

                $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_expsch_rubric_rpt_statement
                                                                                                                        WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$expid."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
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
/*************************Expedition schedule End here**********************************/
							
/**************************Expedition and Module schedule Code start here by Mohan M 9-4-2016**********************/
$qryexpmod= $ObjDB->QueryObject("SELECT d.fld_id AS scheduleid,b.fld_module_id AS expid FROM itc_class_rotation_modexpschedulegriddet AS b
									LEFT JOIN itc_class_rotation_modexpscheduledate AS c ON b.fld_schedule_id = c.fld_schedule_id AND b.fld_rotation = c.fld_rotation
									LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS d ON c.fld_schedule_id = d.fld_id
								WHERE b.fld_student_id = '".$studentid."' AND b.fld_class_id = '".$classid."' AND b.fld_flag = '1'
									AND c.fld_flag = '1' AND b.fld_type='2' AND d.fld_delstatus = '0' ".$sqry2."");
if($qryexpmod->num_rows>0)
{
    $expmodschexptearned = '';
    $expmodschexptpossible = '';
    $pointsearnedfortest=0;
    $possiblepointfortest1=0;
    $possiblepointfortest=0;
    $pointsearnedrubric=0;
    $pointspossiblerubric=0;
    while($expmodrowqryexp = $qryexpmod->fetch_assoc())
    {
        extract($expmodrowqryexp);
        /*************EXP pre test or POST test Code Start Here**************/

        $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                    WHERE fld_class_id='".$classid."' AND fld_sch_id='".$scheduleid."' AND fld_texpid='".$expid."' AND fld_schtype_id='20'");

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
                            $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master 
                                                                                        where fld_id='".$testid."' and fld_delstatus='0';");

                            $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master 
                                                                                WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$expid."' 
                                                                                AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='20' 
                                                                                AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                            if(trim($tchpointcnt)=='')
                            {
                                $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                    LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                    WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                    AND a.fld_schedule_id='".$scheduleid."' AND a.fld_schedule_type='20'
                                                                                    AND a.fld_test_id='".$testid."' AND b.fld_delstatus = '0' 
                                                                                    AND a.fld_delstatus = '0'");

                                if(trim($correctcountfortestattend) != '')
                                {
                                     $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                    LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                    WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                    AND a.fld_schedule_id='".$scheduleid."' AND a.fld_schedule_type='20'
                                                                                    AND a.fld_test_id='".$testid."' AND b.fld_delstatus = '0' AND a.fld_show = '1' 
                                                                                    AND a.fld_delstatus = '0'");
                                    
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
                                                                                        AND fld_schedule_type='20' AND fld_grade='1' AND fld_res_id='".$testid."' 
                                                                                        AND fld_exptype='3'");
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
                            $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master 
                                                                                        where fld_id='".$testid."' and fld_delstatus='0';");

                            $tchpointcnt = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master 
                                                                                WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$expid."' 
                                                                                AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='20' 
                                                                                AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                            if(trim($tchpointcnt)=='')
                            {
                                $correctcountfortestattend = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_test_student_answer_track AS a
                                                                                                    LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                    WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                    AND a.fld_schedule_id='".$scheduleid."' AND a.fld_schedule_type='20'
                                                                                    AND a.fld_test_id='".$testid."' AND b.fld_delstatus = '0' 
                                                                                    AND a.fld_delstatus = '0'");

                                if(trim($correctcountfortestattend) != '')
                                {
                                    $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                    LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                    WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$studentid."' 
                                                                                                    AND a.fld_schedule_id='".$scheduleid."' AND a.fld_schedule_type='20'
                                                                                    AND a.fld_test_id='".$testid."' AND b.fld_delstatus = '0' AND a.fld_show = '1' 
                                                                                    AND a.fld_delstatus = '0'");
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
                                                                                        AND fld_schedule_type='20' AND fld_grade='1' AND fld_res_id='".$testid."' 
                                                                                        AND fld_exptype='3'");
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
        }
        /************** Pre/Post test code end here ***************/   

        /************** Rubric code start here ***************/

        $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                                        CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                                                        LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                                                        LEFT JOIN itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                                                                        LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
                                        WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND b.fld_exp_id='".$expid."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                        AND a.fld_schedule_type='20' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                        AND b.fld_school_id IN(0,".$schoolid.")");;

        if($qryrub->num_rows>0)
        {
            while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
            {
                extract($rowqryrub);

                $destinationids = $ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_exp_rubric_dest_master WHERE fld_rubric_name_id='".$rubricids."' AND fld_exp_id='".$expid."'"); 

                $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master 
                                                                WHERE fld_exp_id='".$expid."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_expmodsch_rubric_rpt WHERE fld_exp_id='".$expid."'  
                                                                        AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$classid."' 
                                                                        AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' "); 

                $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_expmodsch_rubric_rpt_statement
                                                                    WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$expid."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
                                                                    AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                $pointsearnedrubric = $pointsearnedrubric+$studentscore;
                if($studentscore!=0)
                {
                    $pointspossiblerubric = $pointspossiblerubric+$totscore;
                }
            }
        }
        /************** Rubric code end here ***************/
        $expmodschexptearned=round($pointsearnedfortest + $pointsearnedrubric,2);
        $expmodschexptpossible=$possiblepointfortest + $pointspossiblerubric;

    }
}
else
{
	$expmodschexptearned = '';
	$expmodschexptpossible = '';
}
/**************************Expedition and Module schedule Code End here by Mohan**********************/  							
  
/*********Mission report Code Start Here  Developed By Mohan M 16-7-2015  UPDATED BY 13/11/2015**************/
$qrymis = $ObjDB->QueryObject("SELECT b.fld_id AS scheduleid, b.fld_mis_id AS misid FROM itc_mission_master AS a 
                                    LEFT JOIN itc_class_indasmission_master AS b ON b.fld_mis_id=a.fld_id 
                                            WHERE b.fld_class_id='".$classid."' AND b.fld_flag='1' AND b.fld_delstatus='0' 
                                                    AND a.fld_delstatus='0'   ".$sqry1."
                                                    GROUP BY b.fld_id");
if($qrymis->num_rows>0)
{
    $mistearned='';
    $mistpossible='';
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
        $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                        CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                        LEFT JOIN itc_mis_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                        LEFT JOIN itc_mission_master AS c ON a.fld_expmisid = c.fld_id
                                        LEFT JOIN itc_class_indasmission_master AS d ON a.fld_schedule_id=d.fld_id 
                                                WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                        AND a.fld_schedule_type='18' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                        AND b.fld_school_id IN(0,".$schoolid.")");

        if($qryrub->num_rows>0)
        {
            while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
            {
                extract($rowqryrub);
                
                $destinationids = $ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_mis_rubric_dest_master WHERE fld_rubric_name_id='".$rubricids."' AND fld_mis_id='".$misid."'"); 

                $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_master 
                                                            WHERE fld_mis_id='".$misid."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_rubric_rpt WHERE fld_mis_id='".$misid."'  
                                                                    AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$classid."' 
                                                                    AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' "); 

                $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_rpt_statement
                                                                    WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$misid."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
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
		
        $qrytest = $ObjDB->QueryObject("SELECT fld_test_id AS pretest,fld_mis_id AS misid FROM itc_mis_ass
                                            WHERE fld_class_id='".$classid."' AND fld_sch_id='".$scheduleid."' AND fld_schtype_id='18' AND fld_flag='1'");

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
                                                                        AND a.fld_delstatus = '0'");

                            if(trim($correctcountfortestattend) != '')
                            {
                                 $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                        WHERE b.fld_mist = '".$misid."' AND a.fld_student_id = '".$studentid."' 
                                                                                        AND a.fld_test_id='".$testid."'  AND a.fld_schedule_id='".$scheduleid."' 
                                                                                        AND a.fld_schedule_type='18'  AND b.fld_delstatus = '0' AND a.fld_show = '1' 
                                                                        AND a.fld_delstatus = '0'");
                                
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
/*********Mission report Code End Here Developed By Mohan M 16-7-2015 UPDATED BY 13/11/2015*************/								

/**************************Mission schedule start by MOhan M************/
$qryexp= $ObjDB->QueryObject("SELECT d.fld_id AS scheduleid,b.fld_mission_id AS misid,23 AS typeids FROM itc_class_rotation_mission_schedulegriddet AS b
                                LEFT JOIN itc_class_rotation_missionscheduledate AS c ON b.fld_schedule_id = c.fld_schedule_id AND b.fld_rotation = c.fld_rotation
                                LEFT JOIN itc_class_rotation_mission_mastertemp AS d ON c.fld_schedule_id = d.fld_id
                                WHERE b.fld_student_id = '".$studentid."' AND b.fld_class_id = '".$classid."' AND b.fld_flag = '1'
                                        AND d.fld_delstatus = '0' ".$sqry2."");
if($qryexp->num_rows>0)
{
    $misschexptearned = '';
    $misschexptpossible = '';

    while($rowqryexp = $qryexp->fetch_assoc())
    {
        extract($rowqryexp);

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

            $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                        LEFT JOIN itc_mis_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                        LEFT JOIN itc_mission_master AS c ON a.fld_expmisid = c.fld_id
                                                        LEFT JOIN itc_class_rotation_mission_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
                                                            WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND b.fld_mis_id='".$misid."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                                    AND a.fld_schedule_type='19' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                                    AND b.fld_school_id IN(0,".$schoolid.")");

            if($qryrub->num_rows>0)
            {
                $totscore=0;
                while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
                {
                    extract($rowqryrub);
                    
                    $destinationids = $ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_mis_rubric_dest_master WHERE fld_rubric_name_id='".$rubricids."' AND fld_mis_id='".$misid."'"); 


                   $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_master 
                                                                        WHERE fld_mis_id='".$misid."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    
                   

                    $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_missch_rubric_rpt WHERE fld_mis_id='".$misid."'  
                                                                        AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$classid."' 
                                                                        AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' "); 

                    $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_missch_rubric_rpt_statement
                                                                        WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$misid."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
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

            $qrytest = $ObjDB->QueryObject("SELECT fld_test_id AS pretest,fld_mis_id AS misid FROM itc_mis_ass
                                                    WHERE fld_class_id='".$classid."' AND fld_sch_id='".$scheduleid."' AND fld_mis_id='".$misid."' AND fld_schtype_id='20' AND fld_flag='1'");

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
                                                                            AND a.fld_delstatus = '0'");

                                if(trim($correctcountfortestattend) != '')
                                {
                                    $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                            WHERE b.fld_mist = '".$misid."' AND a.fld_student_id = '".$studentid."' 
                                                                                            AND a.fld_test_id='".$testid."'  AND a.fld_schedule_id='".$scheduleid."' 
                                                                                            AND a.fld_schedule_type='20'  AND b.fld_delstatus = '0' AND a.fld_show = '1' 
                                                                            AND a.fld_delstatus = '0'");
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

            $pointsearned = $pointearned1 + $pointsearnedrubric + $pointsearnedfortest;
            $pointspossible = $pointpossible1 + $pointspossiblerubric + $possiblepointfortest;
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
/**************************Mission schedule End by MOhan M************/			
                                                    
//Assessment Starts Modified on 5th december 2014 for including the opensource score with overall column in table
/* changes made by vijayalakshmi PHP Programmer Updated By MOhan M*/
$qrytest = $ObjDB->QueryObject("SELECT a.fld_id AS testid, a.fld_score AS testscore, a.fld_total_question AS ques, 
								IFNULL(b.fld_teacher_points_earned,'-') AS tearned, a.fld_question_type AS testtype
							FROM itc_test_master AS a 
							LEFT JOIN itc_test_student_mapping AS b ON b.fld_test_id=a.fld_id 
							WHERE b.fld_class_id='".$classid."' AND a.fld_flag='1' AND a.fld_delstatus='0' 
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
							
/* changed on 5th december 2014 by Vijayalakshmi PHP Programmer  **/				
//Assessment ends  
                                                        
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
                                left join itc_class_rotation_schedule_mastertemp as d on a.fld_schedule_id=d.fld_id
                                WHERE b.fld_class_id='".$classid."' AND a.fld_student_id='".$studentid."' and b.fld_student_id='".$studentid."' AND a.fld_test_type = '2' AND b.fld_flag='1' AND a.fld_delstatus='0' and b.fld_type='2'
                                        AND (a.fld_status='1' OR a.fld_status='2' OR a.fld_lock='1') AND c.fld_flag='1' AND d.fld_delstatus='0' ".$sqry2.")  
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
                                left join itc_class_rotation_schedule_mastertemp as d on a.fld_schedule_id=d.fld_id
                                WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND (a.fld_points_earned <> '' or a.fld_teacher_points_earned <>'')
                                AND a.fld_grade<>'0' AND a.fld_schedule_type IN (1,4,8) AND c.fld_flag='1' AND d.fld_delstatus='0' ".$sqry2.") 		
                        UNION ALL		
                                (SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
                                THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible 
                                FROM itc_module_points_master AS a 
                                LEFT JOIN `itc_class_dyad_schedulegriddet` AS b ON (a.fld_schedule_id=b.fld_schedule_id 
                                AND a.fld_module_id=b.`fld_module_id` AND (a.fld_student_id=b.fld_student_id OR b.fld_rotation='0')) 
                                left join itc_class_dyad_schedulemaster as d on a.fld_schedule_id=d.fld_id
                                WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND (a.fld_points_earned <> '' or a.fld_teacher_points_earned <>'')
                                AND a.fld_grade<>'0' AND a.fld_schedule_type='2' AND d.fld_delstatus='0' ".$sqry1.") 		
                        UNION ALL		
                                (SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
                                THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible 
                                FROM itc_module_points_master AS a 
                                LEFT JOIN `itc_class_triad_schedulegriddet` AS b ON (a.fld_schedule_id=b.fld_schedule_id
                                AND a.fld_module_id=b.`fld_module_id` AND (a.fld_student_id=b.fld_student_id OR b.fld_rotation='0')) 
                                left join itc_class_triad_schedulemaster as d on a.fld_schedule_id=d.fld_id
                                WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND (a.fld_points_earned <> '' or a.fld_teacher_points_earned <>'')
                                        AND a.fld_grade<>'0' AND a.fld_schedule_type='3' AND d.fld_delstatus='0' ".$sqry1.") 		
                        UNION ALL 		
                                (SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
                                THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible 
                                FROM itc_module_points_master AS a 
                                LEFT JOIN itc_class_indassesment_master AS b ON (a.fld_schedule_id=b.fld_id 
                                AND a.fld_module_id=b.fld_module_id) 
                                LEFT JOIN itc_class_indassesment_student_mapping AS c ON (a.fld_schedule_id=c.fld_schedule_id 
                                AND a.fld_student_id=c.fld_student_id) 
                                WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$classid."' AND a.fld_delstatus='0' 
                                AND b.fld_flag='1' AND c.fld_flag='1' AND a.fld_grade<>'0' AND (a.fld_points_earned <> '' or a.fld_teacher_points_earned <>'') AND a.fld_schedule_type IN (5,6,7,17) AND b.fld_delstatus='0'  ".$sqry1.") 
                        UNION ALL 		
                                (SELECT IFNULL(b.fld_points_earned,'-') AS pointsearned, a.fld_activity_points AS pointspossible
                                FROM itc_activity_master AS a 
                                LEFT JOIN itc_activity_student_mapping AS b ON b.fld_activity_id=a.fld_id 
                                WHERE b.fld_student_id='".$studentid."' AND b.fld_class_id='".$classid."' AND b.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_points_earned<>'' ".$sqry.") 
                        UNION ALL		
                                (SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
                                        THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible 
                                        FROM itc_module_points_master AS a 
                                        LEFT JOIN itc_class_rotation_modexpschedulegriddet AS b ON (a.fld_schedule_id=b.fld_schedule_id 
                                        AND a.fld_module_id=b.`fld_module_id` AND a.fld_student_id=b.fld_student_id) 
                                        LEFT JOIN itc_class_rotation_modexpscheduledate AS c ON b.fld_schedule_id=c.fld_schedule_id and b.fld_rotation=c.fld_rotation
                                        left join itc_class_rotation_modexpschedule_mastertemp as d on a.fld_schedule_id=d.fld_id
                                        WHERE a.fld_student_id='".$studentid."' AND b.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND b.fld_flag='1'
                                        AND a.fld_grade<>'0' AND a.fld_schedule_type IN (21)  AND b.fld_type='1'  AND c.fld_flag='1' AND d.fld_delstatus='0' ".$sqry2.")  


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

                            //echo $exptearned."~".$exptpossible."~".$pointsearned." ! <br>";
                            //echo $pointsearned."~".$exptearned."~".$testearned." ! <br>";+ $rubearned + $rubexpschearned + $modexprubexpschearned
                            //echo $rubmisearned." 1 ".$rubexpschearned." 2 ".$expmodschexptearned." 3 ".$modexprubexpschearned."4 ".$misschexptearned." END <br>" ;+ $modexprubexpschpossible + $rubexpschpossible+ $rubpossible 
                            //$finalpointsearned = $pointsearned + $exptearned + $testearned + $mistearned + $schexptearned + $rubmisearnedwca + $expmodschexptearned + $misschexptearned;//rubric
                            //$finalpointspossible = $pointspossible + $exptpossible + $testpossible + $mistpossible + $schexptpossible + $rubmispossiblewca + $expmodschexptpossible + $misschexptpossible;//rubric

                            $finalpointsearned = $pointsearned + $testearned + $exptearned + $schexptearned + $expmodschexptearned + $mistearned + $misschexptearned;
                            $finalpointspossible = $pointspossible + $testpossible + $exptpossible + $schexptpossible + $expmodschexptpossible + $mistpossible + $misschexptpossible;

                            if($finalpointspossible=='' or $finalpointspossible=='-')
                            {
                                $finalpointsearned = " - ";
                                $finalpointspossible = " - ";
                                $percentage = " - ";
                                $grade = " N/A ";
                            }
                            else
                            {
                                $finalpointsearned = round($finalpointsearned);
                                if($roundflag==0)
                                        $percentage = round(($finalpointsearned/$finalpointspossible)*100,2);
                                else
                                        $percentage = round(($finalpointsearned/$finalpointspossible)*100);
                                //$percentage = round(($pointsearned/$pointspossible)*100,2);
                                $perarray = explode('.',$percentage);

                                $grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
                                                                    FROM itc_class_grading_scale_mapping 
                                                                    WHERE fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' 
                                                                            AND fld_class_id='".$classid."' AND fld_flag='1'");
                            }
                            if($percentage==0)
                            {
                                $finalpointsearned = " - ";
                                $finalpointspossible = " - ";
                                $percentage = " - ";
                                $grade = " N/A ";
                            }
                            ?>
                            <td align="center" style="font-size:14px; font-weight:bold; cursor:pointer" onclick="removesections('#reports-gradebook');  fn_show(<?php if($i==0) echo '0'; else echo $gradeid[$i-1];?>,<?php echo $classid; ?>,0,0);">
                                <div style="text-align:center"><?php echo $percentage.' % '.$grade;?></div>
                                <div style="text-align:center"><?php echo $finalpointsearned.' / '.$finalpointspossible;?></div>
                            </td>
                            <?php 
                        }
                        ?>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
    </div>
	
    <div class='four columns'>&nbsp;</div>

    <input type="hidden" id="hidrowvalue" value="<?php echo $qryhead->num_rows;?>" name="hidrowvalue" />
    <script language="javascript" type="text/javascript">
            $('#myTable05').fixedHeaderTable({ fixedColumns: 1 });
            $('#myTable05').fixedHeaderTable('destroy');	
            $('#myTable05').fixedHeaderTable({fixedColumn: true });
            $('#myTable05').parent().parent().parent().find(".fht-tbody").css("min-height", "420px");
            $('#myTable05').parent().parent().parent().css("min-height", "420px");
            $('#myTable05').parent().parent().parent().parent().css("min-height", "420px");
            <?php if($qryhead->num_rows<=0){?>
                    $('#student').css('width','405px');
                    $(".studentth").css('height','39px');
            <?php }
            else
            {
            ?>
             $(".stuhead").css({"width":"209.5px"});
             <?php
            }
            ?>
    </script>
    <?php
}

/*--- Save Points ---*/
if($oper=="savepoints" and $oper != " " )
{
	$type = isset($method['type']) ? $method['type'] : '';
	$classid = isset($method['classid']) ? $method['classid'] : '';
	$studentid = isset($method['studentid']) ? $method['studentid'] : '';
	$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '';
	$unitmodid = isset($method['unitmodid']) ? $method['unitmodid'] : '';
	$sessiplids = isset($method['sessiplids']) ? $method['sessiplids'] : '';
	$pointsearned = isset($method['pointsearned']) ? $method['pointsearned'] : '';
	$pointspossible = isset($method['pointspossible']) ? $method['pointspossible'] : '';
	$rubricsids = isset($method['rubricsids']) ? $method['rubricsids'] : '';
	$rubricspointsearned = isset($method['rubricspointsearned']) ? $method['rubricspointsearned'] : '';
	$rubricspointspossible = isset($method['rubricspointspossible']) ? $method['rubricspointspossible'] : '';
	$diagnosticids = isset($method['diagids']) ? $method['diagids'] : '';
	$diagpointsearned = isset($method['diagpointsearned']) ? $method['diagpointsearned'] : '';
	$diagpointspossible = isset($method['diagpointspossible']) ? $method['diagpointspossible'] : '';
	$actid = isset($method['actid']) ? $method['actid'] : '';
	$actpoint = isset($method['actpoint']) ? $method['actpoint'] : '';
	$actpossible = isset($method['actpossible']) ? $method['actpossible'] : '';
	$cgamark = isset($method['cgamark']) ? $method['cgamark'] : '';
	$testid = isset($method['testid']) ? $method['testid'] : '';
	$testpoint = isset($method['testpoint']) ? $method['testpoint'] : '';
	$testpossible = isset($method['testpossible']) ? $method['testpossible'] : '';
	$contid = isset($method['contid']) ? $method['contid'] : '';
	$contpoint = isset($method['contpoint']) ? $method['contpoint'] : '';
	$contpossible = isset($method['contpossible']) ? $method['contpossible'] : '';
	$questid = isset($method['questid']) ? $method['questid'] : '';
	$questpoint = isset($method['questpoint']) ? $method['questpoint'] : '';
	$questpossible = isset($method['questpossible']) ? $method['questpossible'] : '';
	$questsessid = isset($method['questsessid']) ? $method['questsessid'] : '';
	$questtype = isset($method['questtype']) ? $method['questtype'] : '';

	//$exptypeid = isset($method['exptypeid']) ? $method['exptypeid'] : '';
	//$exptype = isset($method['exptype']) ? $method['exptype'] : '';
	//$exppoint = isset($method['exppoint']) ? $method['exppoint'] : '';
	//$exppossible = isset($method['exppossible']) ? $method['exppossible'] : '';
	
	/*****Teacher Points earned code Start here for pre/post test*****/
	$exptesttypeid = isset($method['exptesttypeid']) ? $method['exptesttypeid'] : '';
	$exptesttype = isset($method['exptesttype']) ? $method['exptesttype'] : '';
	$exptestpoint = isset($method['exptestpoint']) ? $method['exptestpoint'] : '';
	$exptestpossible = isset($method['exptestpossible']) ? $method['exptestpossible'] : '';
        /*****Teacher Points earned code End here for pre/post test*****/
        
    /*********Mission report Code Start Here Developed By Mohan M 16-7-2015 UPDATED BY 13/11/2015*************/  
	$mistypeid = isset($method['mistypeid']) ? $method['mistypeid'] : '';
	$mistype = isset($method['mistype']) ? $method['mistype'] : '';
	$mispoint = isset($method['mispoint']) ? $method['mispoint'] : '';
	$mispossible = isset($method['mispossible']) ? $method['mispossible'] : '';
	
		/*****Teacher Points earned code Start here for pre/post test*****/
		$mistesttypeid = isset($method['mistesttypeid']) ? $method['mistesttypeid'] : '';
		$mistesttype = isset($method['mistesttype']) ? $method['mistesttype'] : '';
		$mistestpoint = isset($method['mistestpoint']) ? $method['mistestpoint'] : '';
		$mistestpossible = isset($method['mistestpossible']) ? $method['mistestpossible'] : '';
		/*****Teacher Points earned code End here for pre/post test*****/
	
    /*********Mission report Code Start Here Developed By Mohan M 16-7-2015 UPDATED BY 13/11/2015*************/
    
	/**************************Expedition and Module schedule Code start here by Mohan**********************/  
	
		/**************Expedition schedule*****************/
		//$expmodtype = isset($method['expmodtype']) ? $method['expmodtype'] : '';
		//$expmodpoint = isset($method['expmodpoint']) ? $method['expmodpoint'] : '';
		//$expmodpossible = isset($method['expmodpossible']) ? $method['expmodpossible'] : '';
		

		/*****Teacher Points earned code Start here for pre/post test*****/
		$expmodtesttypeid = isset($method['expmodtesttypeid']) ? $method['expmodtesttypeid'] : '';
		$expmodtesttype = isset($method['expmodtesttype']) ? $method['expmodtesttype'] : '';
		$expmodtestpoint = isset($method['expmodtestpoint']) ? $method['expmodtestpoint'] : '';
		$expmodtestpossible = isset($method['expmodtestpossible']) ? $method['expmodtestpossible'] : '';
		/*****Teacher Points earned code End here for pre/post test*****/
		

		/**********Module**********/
		$modsessiplids = isset($method['modsessiplids']) ? $method['modsessiplids'] : '';
		$modpointsearned = isset($method['modpointsearned']) ? $method['modpointsearned'] : '';
		$modpointspossible = isset($method['modpointspossible']) ? $method['modpointspossible'] : '';
		$modsessiplids = explode(",",$modsessiplids);
	 	$modsessiplids1 = explode(",",$modsessiplids);
        $modpointsearned1 = explode(",",$modpointsearned);
		$modpointsearned = explode(",",$modpointsearned);
		$modpointspossible = explode(",",$modpointspossible);

		/******Custom Content******/
		$modcontid = isset($method['modcontid']) ? $method['modcontid'] : '';
		$modcontpoint = isset($method['modcontpoint']) ? $method['modcontpoint'] : '';
		$modcontpossible = isset($method['modcontpossible']) ? $method['modcontpossible'] : '';
	
		$modorcustom = isset($method['modorcustom']) ? $method['modorcustom'] : '';
	
	/**************************Expedition and Module schedule Code End here by Mohan**********************/
	
        $sessiplids1 = explode(",",$sessiplids);
        $pointsearned1 = explode(",",$pointsearned);
        
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        
        
        $cal=1;////new line
        $graphcal=20;////new line
        $orient=42;////new line
        
	if($type!=0)
	{
		$sessiplids = explode(",",$sessiplids);
		$pointsearned = explode(",",$pointsearned);
		$pointspossible = explode(",",$pointspossible);
		
		$diagids = explode("~",$diagnosticids);
		$diagpointsearned = explode("~",$diagpointsearned);
		$diagpointspossible = explode("~",$diagpointspossible);
	}
	else
	{
		$sessiplids = explode("~",$sessiplids);
		$pointsearned = explode("~",$pointsearned);
		$pointspossible = explode("~",$pointspossible);
		
		$rubricsids = explode("~",$rubricsids);
		$rubricspointsearned = explode("~",$rubricspointsearned);
		$rubricspointspossible = explode("~",$rubricspointspossible);
	}
        //teacher can deleted the the test mark , the particular student is attend the test in first question
        //echo "points".sizeof($pointsearned);
        //echo "ips".sizeof($sessiplids);
      
        
        //purpose to use teacher reset the test mark. The student start the first question from the particular modules.
        if($type==1 or $type==2 or $type==3  or $type==4 or $type==5 or $type==6)
        {
            for($a=0;$a<sizeof($sessiplids1);$a++)
            {
                $sessiplids2 = explode("~",$sessiplids1[$a]);
                $pointsearned2 = explode("~",$pointsearned1[$a]);
                for($b=0;$b<sizeof($sessiplids2);$b++)
                {
                    if($sessiplids2[$b] !=''){
                        if($pointsearned2[$b] =='1111'){
                           $ObjDB->NonQuery("update itc_module_play_track  
                                                set fld_read_status ='0' 
                                                where fld_tester_id='".$studentid."' and fld_schedule_id='".$scheduleid."' and fld_schedule_type='".$type."' 
                                                        and fld_module_id='".$unitmodid."' and fld_section_id='".$sessiplids2[$b]."' and fld_page_id='0' and fld_delstatus='0'");
                        }

                    } 
                }

            }
        }
		
	if($type==0)
	{
		for($i=0;$i<sizeof($sessiplids);$i++)
		{
			if($sessiplids[$i]!='')
			{
				if($pointsearned[$i]=='1111')
				{
					$lock = '0';
					$pointsearned[$i]='';
					//$status = 0;
					
					$stcnt = $ObjDB->SelectSingleValueInt("SELECT fld_points_earned 
															FROM itc_assignment_sigmath_master 
															WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' 
																AND fld_unit_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
																AND fld_lesson_id='".$sessiplids[$i]."'");
					
					if($fld_points_earned==100)
						$status = 1;
					else if($fld_points_earned=='')
						$status = 0;
					else
						$status = 2;
				}
				
				else
				{
					$lock='1';
					if($pointsearned[$i]==0)
						$status = 2;
					else
						$status = 1;
				}
				
				$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
													FROM itc_assignment_sigmath_master 
													WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' 
														AND fld_unit_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
														AND fld_lesson_id='".$sessiplids[$i]."' AND fld_delstatus='0'");
				
				$gradepoint = $ObjDB->QueryObject("SELECT fld_points AS points,fld_grade AS grade
													FROM itc_class_sigmath_grade
													WHERE fld_schedule_id='".$scheduleid."' AND fld_lesson_id='".$sessiplids[$i]."' AND fld_flag='1'");
				if($gradepoint->num_rows>0){
					extract($gradepoint->fetch_assoc());
				}
				else
				{
					$grade = '1';
					$points = $pointspossible[$i];
				}
				
				if($cnt!='')
				{
					if($status!=0)
						$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master SET fld_teacher_points_earned='".$pointsearned[$i]."', fld_points_possible='".$pointspossible[$i]."', fld_lock='".$lock."', fld_status='".$status."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_grade='".$grade."' WHERE fld_id='".$cnt."'");
					else if($status==0)
						$ObjDB->NonQuery("DELETE FROM itc_assignment_sigmath_master WHERE fld_id='".$cnt."'");
				}
				else
				{
					$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_master (fld_class_id, fld_schedule_id, fld_student_id, fld_unit_id, fld_lesson_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_status,fld_grade, fld_created_by, fld_created_date) VALUES('".$classid."', '".$scheduleid."', '".$studentid."', '".$unitmodid."', '".$sessiplids[$i]."', '".$pointsearned[$i]."', '".$points."', '".$lock."', '".$status."', '".$grade."', '".$uid."', '".date("Y-m-d H:i:s")."')");
				}
			}
		}
//math connection
                
                 if($unitmodid!=$cal && $unitmodid!=$graphcal && $unitmodid!=$orient  )////new line
                { 
		$cgacnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
												FROM itc_assignment_sigmath_master 
												WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' 
													AND fld_unit_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
													AND fld_unitmark='1' AND fld_delstatus='0' AND fld_lock='1'");
           $cgapossible = $ObjDB->SelectSingleValueInt("SELECT  fld_mpoints 
                                                                                        FROM itc_class_sigmath_grademapping 
                                                                                        WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                                AND fld_unit_id='".$unitmodid."' AND fld_flag = '1'"); 
                 $cgagrades = $ObjDB->SelectSingleValueInt("SELECT  fld_mgrade
                                                                        FROM itc_class_sigmath_grademapping 
                                                                        WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                AND fld_unit_id='".$unitmodid."' AND fld_flag = '1'"); 
         
		if($cgacnt!='')
		{
			if($cgamark!='')
				$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master SET fld_teacher_points_earned='".$cgamark."', fld_lock='1', fld_grade='".$cgagrades."', fld_points_possible='".$cgapossible."', fld_unitmark='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cgacnt."'");
			else
				$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master SET fld_delstatus='1', fld_grade='".$cgagrades."', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cgacnt."'");
		}
		else
		{
			if($cgamark!='')
				$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_master (fld_class_id, fld_schedule_id, fld_student_id, fld_unit_id, fld_teacher_points_earned, fld_points_possible, fld_unitmark, fld_lock, fld_grade, fld_created_by, fld_created_date) VALUES('".$classid."', '".$scheduleid."', '".$studentid."', '".$unitmodid."', '".$cgamark."', '".$cgapossible."', '1', '1', '".$cgagrades."', '".$uid."', '".date("Y-m-d H:i:s")."')");
		}
                }////new line
		
    //math connection            
		
		for($i=0;$i<sizeof($rubricsids);$i++)
		{
			if($rubricsids[$i]!='')
			{
				$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
													FROM itc_assignment_sigmath_master 
													WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' 
														AND fld_unit_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
														AND fld_rubrics_id='".$rubricsids[$i]."'");
				if($cnt!='')
				{
					$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master SET fld_teacher_points_earned='".$rubricspointsearned[$i]."', fld_points_possible='".$rubricspointspossible[$i]."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");
				}
				else
				{
					$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_master (fld_class_id, fld_schedule_id, fld_student_id, fld_unit_id, fld_rubrics_id, fld_teacher_points_earned, fld_points_possible, fld_created_by, fld_created_date) VALUES('".$classid."', '".$scheduleid."', '".$studentid."', '".$unitmodid."', '".$rubricsids[$i]."', '".$rubricspointsearned[$i]."', '".$rubricspointspossible[$i]."', '".$uid."', '".date("Y-m-d H:i:s")."')");
				}
			}
		}
	}
	
	else if($type==10)
	{
		$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
											FROM itc_activity_student_mapping 
											WHERE fld_class_id='".$classid."' AND fld_activity_id='".$unitmodid."' 
												AND fld_student_id='".$studentid."' AND fld_flag='1'");
		
		$ObjDB->NonQuery("UPDATE itc_activity_student_mapping SET fld_points_earned='".$actpoint."', fld_points_possible='".$actpossible."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");
		
	}
	
	else if($type==8)
	{
		$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
											FROM itc_module_points_master 
											WHERE fld_schedule_type='".$type."' AND fld_schedule_id='".$scheduleid."' 
												AND fld_module_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
												AND fld_session_id='0' AND fld_type='0' AND fld_preassment_id='0' AND fld_delstatus='0'");
		
		if($cnt!='')
			$ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_teacher_points_earned='".$contpoint."', fld_points_possible='".$contpossible."', fld_lock='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");

		else
			$ObjDB->NonQuery("INSERT INTO itc_module_points_master (fld_schedule_type, fld_schedule_id, fld_student_id, fld_module_id, fld_session_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_type, fld_preassment_id, fld_grade, fld_created_by, fld_created_date) VALUES('".$type."', '".$scheduleid."', '".$studentid."', '".$unitmodid."', '0', '".$contpoint."', '".$contpossible."', '1', '0', '0', '1', '".$uid."', '".date("Y-m-d H:i:s")."')");
		
	}
	
	else if($type==17)
	{
		$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
                                                                    FROM itc_module_points_master 
                                                                    WHERE fld_schedule_type='".$type."' AND fld_schedule_id='".$scheduleid."' 
                                                                            AND fld_module_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
                                                                            AND fld_session_id='0' AND fld_type='0' AND fld_preassment_id='0' AND fld_delstatus='0'");
	
                
		
		if($cnt!='')
                    
			$ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_teacher_points_earned='".$contpoint."', fld_points_possible='".$contpossible."', fld_lock='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");

		else
			$ObjDB->NonQuery("INSERT INTO itc_module_points_master (fld_schedule_type, fld_schedule_id, fld_student_id, fld_module_id, fld_session_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_type, fld_preassment_id, fld_grade, fld_created_by, fld_created_date) VALUES('".$type."', '".$scheduleid."', '".$studentid."', '".$unitmodid."', '0', '".$contpoint."', '".$contpossible."', '1', '0', '0', '1', '".$uid."', '".date("Y-m-d H:i:s")."')");
		
	}
	
	else if($type==15) // WCA Expedition
	{
		/*$exptypeid = explode("~",$exptypeid);
		$exptype = explode("~",$exptype);
		$exppoint = explode("~",$exppoint);
		$exppossible = explode("~",$exppossible);
		
		for($i=0;$i<sizeof($exptypeid);$i++)
		{
			if($exptypeid[$i]!='')
			{
				$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
													FROM itc_exp_points_master 
													WHERE fld_schedule_type='".$type."' AND fld_schedule_id='".$scheduleid."' 
														AND fld_exp_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
														AND fld_exptype='".$exptype[$i]."' AND fld_res_id='".$exptypeid[$i]."' AND fld_delstatus='0'");

				if($cnt!='')
					$ObjDB->NonQuery("UPDATE itc_exp_points_master SET fld_teacher_points_earned='".$exppoint[$i]."', fld_points_possible='".$exppossible[$i]."', fld_lock='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");

				else
					$ObjDB->NonQuery("INSERT INTO itc_exp_points_master (fld_schedule_type, fld_schedule_id, fld_student_id, fld_exp_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_exptype, fld_res_id, fld_grade, fld_created_by, fld_created_date) VALUES('".$type."', '".$scheduleid."', '".$studentid."', '".$unitmodid."', '".$exppoint[$i]."', '".$exppossible[$i]."', '1', '".$exptype[$i]."', '".$exptypeid[$i]."', '1', '".$uid."', '".date("Y-m-d H:i:s")."')");
			}
		}*/
	
		/*****Teacher Points earned code Start here for pre/post test*****/  
		$exptesttypeid = explode("~",$exptesttypeid);
		$exptesttype = explode("~",$exptesttype);
		$exptestpoint = explode("~",$exptestpoint);
		$exptestpossible = explode("~",$exptestpossible);

		for($j=0;$j<sizeof($exptesttypeid);$j++)
		{
                    if($exptesttypeid[$j]!='')
                    {
                        if($exptestpoint[$j]!='')
                        {
                            $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
                                                                    FROM itc_exp_points_master 
                                                                    WHERE fld_schedule_type='".$type."' AND fld_schedule_id='".$scheduleid."' 
                                                                        AND fld_exp_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
                                                                        AND fld_exptype='".$exptesttype[$j]."' AND fld_res_id='".$exptesttypeid[$j]."' AND fld_delstatus='0'");

                            if($cnt!='')
                            {
                                $ObjDB->NonQuery("UPDATE itc_exp_points_master SET fld_teacher_points_earned='".$exptestpoint[$j]."', fld_points_possible='".$exptestpossible[$j]."', fld_lock='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");
                            }
                            else
                            {
                                $ObjDB->NonQuery("INSERT INTO itc_exp_points_master (fld_schedule_type, fld_schedule_id, fld_student_id, fld_exp_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_exptype, fld_res_id, fld_grade, fld_created_by, fld_created_date) VALUES('".$type."', '".$scheduleid."', '".$studentid."', '".$unitmodid."', '".$exptestpoint[$j]."', '".$exptestpossible[$j]."', '1', '".$exptesttype[$j]."', '".$exptesttypeid[$j]."', '1', '".$uid."', '".date("Y-m-d H:i:s")."')");
                            } 
                        }
                    }
                    if($exptestpoint[$j]==""){
                        //echo $studentid."/".$scheduleid."/".$type."/".$exptesttypeid[$j];
                        $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
                                                                    FROM itc_exp_points_master 
                                                                    WHERE fld_schedule_type='".$type."' AND fld_schedule_id='".$scheduleid."' 
                                                                        AND fld_exp_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
                                                                        AND fld_exptype='".$exptesttype[$j]."' AND fld_res_id='".$exptesttypeid[$j]."' AND fld_delstatus='0'");

                        if($cnt!='')
                        {
                            $ObjDB->NonQuery("UPDATE itc_exp_points_master SET fld_teacher_points_earned='".$exptestpoint[$j]."', fld_points_possible='".$exptestpossible[$j]."', fld_lock='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");
                        }
                            
                        $qrydet = $ObjDB->QueryObject("SELECT fld_expt as rtexpid,fld_destid as rtdestid,fld_taskid as rttaskid,fld_resid as rtresid
                                                        FROM itc_test_master
                                                        WHERE fld_id = '".$exptesttypeid[$j]."' AND fld_delstatus = '0'");
                        $rowqrydet = $qrydet->fetch_assoc();
                        extract($rowqrydet);
                        //echo $rtexpid."/".$rtdestid."/".$rttaskid."/".$rtresid;
                        // For exp test
                        if($rtexpid !=0 and $rtdestid==0 and $rttaskid==0 and $rtresid==0){
                            $exptestcont = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_testplay_track 
                                                                        WHERE fld_exp_test_id='".$exptesttypeid[$j]."' AND fld_exp_id='".$rtexpid."'
                                                                        AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."' AND fld_student_id='".$studentid."'");
                            if($exptestcont==1){
                                $ObjDB->NonQuery("UPDATE itc_exp_testplay_track SET fld_retake='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' 
                                                    WHERE fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."' AND fld_student_id='".$studentid."' 
                                                        AND fld_exp_test_id='".$exptesttypeid[$j]."' AND fld_exp_id='".$rtexpid."'");

                                $ObjDB->NonQuery("UPDATE itc_test_student_answer_track SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'  
                                                WHERE fld_test_id='".$exptesttypeid[$j]."' AND fld_student_id='".$studentid."' AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."'");
                            }
                        }
                        // For dest test
                        if($rtexpid !=0 and $rtdestid!=0 and $rttaskid==0 and $rtresid==0){
                            $desttestcont = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_dest_testplay_track 
                                                                        WHERE fld_dest_test_id='".$exptesttypeid[$j]."' AND fld_exp_id='".$rtexpid."' AND fld_dest_id='".$rtdestid."'
                                                                        AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."' AND fld_student_id='".$studentid."'");
                            if($desttestcont==1){
                                $ObjDB->NonQuery("UPDATE itc_exp_dest_testplay_track SET fld_retake='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' 
                                                WHERE fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."' AND fld_student_id='".$studentid."' 
                                                    AND fld_dest_test_id='".$exptesttypeid[$j]."' AND fld_exp_id='".$rtexpid."' AND fld_dest_id='".$rtdestid."'");

                                $ObjDB->NonQuery("UPDATE itc_test_student_answer_track SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'  
                                                WHERE fld_test_id='".$exptesttypeid[$j]."' AND fld_student_id='".$studentid."' AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."'");
                            }
                        }
                        // For task test
                        if($rtexpid !=0 and $rtdestid!=0 and $rttaskid!=0 and $rtresid==0){
                            $tasktestcont = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_task_testplay_track 
                                                                        WHERE fld_task_test_id='".$exptesttypeid[$j]."' AND fld_exp_id='".$rtexpid."' AND fld_dest_id='".$rtdestid."' AND fld_task_id='".$rttaskid."'
                                                                        AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."' AND fld_student_id='".$studentid."'");
                            if($tasktestcont==1){
                                $ObjDB->NonQuery("UPDATE itc_exp_task_testplay_track SET fld_retake='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' 
                                                WHERE fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."' AND fld_student_id='".$studentid."' 
                                                    AND fld_task_test_id='".$exptesttypeid[$j]."' AND fld_exp_id='".$rtexpid."' AND fld_dest_id='".$rtdestid."' AND fld_task_id='".$rttaskid."'");

                                $ObjDB->NonQuery("UPDATE itc_test_student_answer_track SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'  
                                                WHERE fld_test_id='".$exptesttypeid[$j]."' AND fld_student_id='".$studentid."' AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."'");
                            }
                        }
                        // For Res test
                        if($rtexpid !=0 and $rtdestid!=0 and $rttaskid!=0 and $rtresid!=0){
                            $restestcont = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_testplay_track 
                                                                        WHERE fld_res_test_id='".$exptesttypeid[$j]."' AND fld_exp_id='".$rtexpid."' AND fld_dest_id='".$rtdestid."' AND fld_task_id='".$rttaskid."'
                                                                        AND fld_res_id='".$rtresid."' AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."' AND fld_student_id='".$studentid."'");
                            if($restestcont==1){
                                $ObjDB->NonQuery("UPDATE itc_exp_res_testplay_track SET fld_retake='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' 
                                                WHERE fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."' AND fld_student_id='".$studentid."' 
                                                    AND fld_res_test_id='".$exptesttypeid[$j]."' AND fld_exp_id='".$rtexpid."' AND fld_dest_id='".$rtdestid."' AND fld_task_id='".$rttaskid."' AND fld_res_id='".$rtresid."'");

                                $ObjDB->NonQuery("UPDATE itc_test_student_answer_track SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'  
                                                WHERE fld_test_id='".$exptesttypeid[$j]."' AND fld_student_id='".$studentid."' AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."'");
                            }
                        }
                    }
		}
		/*****Teacher Points earned code End here for pre/post test*****/
	}
        
	else if($type==19) //Expedition schedule
	{
		
		/*if($exppoint!='')
		{
			$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
												FROM itc_exp_points_master 
												WHERE fld_schedule_type='".$type."' AND fld_schedule_id='".$scheduleid."' 
													AND fld_exp_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
													AND fld_exptype='".$exptype."' AND fld_delstatus='0'");

			if($cnt!='')
			{
				$ObjDB->NonQuery("UPDATE itc_exp_points_master SET fld_teacher_points_earned='".$exppoint."', fld_points_possible='".$exppossible."', fld_lock='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");
			}
			else
			{
				$ObjDB->NonQuery("INSERT INTO itc_exp_points_master (fld_schedule_type, fld_schedule_id, fld_student_id, fld_exp_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_exptype, fld_grade, fld_created_by, fld_created_date) VALUES('".$type."', '".$scheduleid."', '".$studentid."', '".$unitmodid."', '".$exppoint."', '".$exppossible."', '1', '".$exptype."', '1', '".$uid."', '".date("Y-m-d H:i:s")."')");
			}
		}
		*/
		/*****Teacher Points earned code Start here for pre/post test*****/  
		$exptesttypeid = explode("~",$exptesttypeid);
		$exptesttype = explode("~",$exptesttype);
		$exptestpoint = explode("~",$exptestpoint);
		$exptestpossible = explode("~",$exptestpossible);

		for($j=0;$j<sizeof($exptesttypeid);$j++)
		{
			if($exptesttypeid[$j]!='')
			{
                            if($exptestpoint[$j]!='')
                            {
                                $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
                                                                     FROM itc_exp_points_master 
                                                                     WHERE fld_schedule_type='".$type."' AND fld_schedule_id='".$scheduleid."' 
                                                                                     AND fld_exp_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
                                                                                     AND fld_exptype='".$exptesttype[$j]."' AND fld_res_id='".$exptesttypeid[$j]."' AND fld_delstatus='0'");

                                if($cnt!='')
                                {
                                    $ObjDB->NonQuery("UPDATE itc_exp_points_master SET fld_teacher_points_earned='".$exptestpoint[$j]."', fld_points_possible='".$exptestpossible[$j]."', fld_lock='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");
                                }
                                else
                                {
                                    $ObjDB->NonQuery("INSERT INTO itc_exp_points_master (fld_schedule_type, fld_schedule_id, fld_student_id, fld_exp_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_exptype, fld_res_id, fld_grade, fld_created_by, fld_created_date) VALUES('".$type."', '".$scheduleid."', '".$studentid."', '".$unitmodid."', '".$exptestpoint[$j]."', '".$exptestpossible[$j]."', '1', '".$exptesttype[$j]."', '".$exptesttypeid[$j]."', '1', '".$uid."', '".date("Y-m-d H:i:s")."')");
                                }
                            }
                            
                            if($exptestpoint[$j]==""){
                                $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
                                                                     FROM itc_exp_points_master 
                                                                     WHERE fld_schedule_type='".$type."' AND fld_schedule_id='".$scheduleid."' 
                                                                                     AND fld_exp_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
                                                                                     AND fld_exptype='".$exptesttype[$j]."' AND fld_res_id='".$exptesttypeid[$j]."' AND fld_delstatus='0'");

                                if($cnt!='')
                                {
                                    $ObjDB->NonQuery("UPDATE itc_exp_points_master SET fld_teacher_points_earned='".$exptestpoint[$j]."', fld_points_possible='".$exptestpossible[$j]."', fld_lock='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");
                                }
                                
                                $qrydet = $ObjDB->QueryObject("SELECT fld_expt as rtexpid,fld_destid as rtdestid,fld_taskid as rttaskid,fld_resid as rtresid
                                                                FROM itc_test_master
                                                                WHERE fld_id = '".$exptesttypeid[$j]."' AND fld_delstatus = '0'");
                                $rowqrydet = $qrydet->fetch_assoc();
                                extract($rowqrydet);

                                // For exp test
                                if($rtexpid !=0 and $rtdestid==0 and $rttaskid==0 and $rtresid==0){
                                    $exptestcont = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_testplay_track 
                                                                                WHERE fld_exp_test_id='".$exptesttypeid[$j]."' AND fld_exp_id='".$rtexpid."'
                                                                                AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."' AND fld_student_id='".$studentid."'");
                                    if($exptestcont==1){
                                        $ObjDB->NonQuery("UPDATE itc_exp_testplay_track SET fld_retake='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' 
                                                            WHERE fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."' AND fld_student_id='".$studentid."' 
                                                                AND fld_exp_test_id='".$exptesttypeid[$j]."' AND fld_exp_id='".$rtexpid."'");

                                        $ObjDB->NonQuery("UPDATE itc_test_student_answer_track SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'  
                                                        WHERE fld_test_id='".$exptesttypeid[$j]."' AND fld_student_id='".$studentid."' AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."'");
                                    }
                                }
                                
                                // For dest test
                                if($rtexpid !=0 and $rtdestid!=0 and $rttaskid==0 and $rtresid==0){
                                    $desttestcont = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_dest_testplay_track 
                                                                                WHERE fld_dest_test_id='".$exptesttypeid[$j]."' AND fld_exp_id='".$rtexpid."' AND fld_dest_id='".$rtdestid."'
                                                                                AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."' AND fld_student_id='".$studentid."'");
                                    if($desttestcont==1){
                                        $ObjDB->NonQuery("UPDATE itc_exp_dest_testplay_track SET fld_retake='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' 
                                                        WHERE fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."' AND fld_student_id='".$studentid."' 
                                                            AND fld_dest_test_id='".$exptesttypeid[$j]."' AND fld_exp_id='".$rtexpid."' AND fld_dest_id='".$rtdestid."'");

                                        $ObjDB->NonQuery("UPDATE itc_test_student_answer_track SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'  
                                                        WHERE fld_test_id='".$exptesttypeid[$j]."' AND fld_student_id='".$studentid."' AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."'");
                                    }
                                }
                                
                                // For task test
                                if($rtexpid !=0 and $rtdestid!=0 and $rttaskid!=0 and $rtresid==0){
                                    $tasktestcont = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_task_testplay_track 
                                                                                WHERE fld_task_test_id='".$exptesttypeid[$j]."' AND fld_exp_id='".$rtexpid."' AND fld_dest_id='".$rtdestid."' AND fld_task_id='".$rttaskid."'
                                                                                AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."' AND fld_student_id='".$studentid."'");
                                    if($tasktestcont==1){
                                        $ObjDB->NonQuery("UPDATE itc_exp_task_testplay_track SET fld_retake='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' 
                                                        WHERE fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."' AND fld_student_id='".$studentid."' 
                                                            AND fld_task_test_id='".$exptesttypeid[$j]."' AND fld_exp_id='".$rtexpid."' AND fld_dest_id='".$rtdestid."' AND fld_task_id='".$rttaskid."'");

                                        $ObjDB->NonQuery("UPDATE itc_test_student_answer_track SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'  
                                                        WHERE fld_test_id='".$exptesttypeid[$j]."' AND fld_student_id='".$studentid."' AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."'");
                                    }
                                }
                                
                                // For Res test
                                if($rtexpid !=0 and $rtdestid!=0 and $rttaskid!=0 and $rtresid!=0){
                                    $restestcont = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_testplay_track 
                                                                                WHERE fld_res_test_id='".$exptesttypeid[$j]."' AND fld_exp_id='".$rtexpid."' AND fld_dest_id='".$rtdestid."' AND fld_task_id='".$rttaskid."'
                                                                                AND fld_res_id='".$rtresid."' AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."' AND fld_student_id='".$studentid."'");
                                    if($restestcont==1){
                                        $ObjDB->NonQuery("UPDATE itc_exp_res_testplay_track SET fld_retake='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' 
                                                        WHERE fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."' AND fld_student_id='".$studentid."' 
                                                            AND fld_res_test_id='".$exptesttypeid[$j]."' AND fld_exp_id='".$rtexpid."' AND fld_dest_id='".$rtdestid."' AND fld_task_id='".$rttaskid."' AND fld_res_id='".$rtresid."'");

                                        $ObjDB->NonQuery("UPDATE itc_test_student_answer_track SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'  
                                                        WHERE fld_test_id='".$exptesttypeid[$j]."' AND fld_student_id='".$studentid."' AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."'");
                                    }
                                }
                            }
			}
		}
		/*****Teacher Points earned code End here for pre/post test*****/
	}
        
	/*********Mission report Code Start Here Developed By Mohan M 16-7-2015 UPDATED BY 13/11/2015*************/	
	else if($type==18)
	{
	 	/*********Participation code End here*******/
		$mistypeid = explode("~",$mistypeid);
		$mistype = explode("~",$mistype);
		$mispoint = explode("~",$mispoint);
		$mispossible = explode("~",$mispossible);
                echo sizeof($mistypeid);
		for($i=0;$i<sizeof($mistypeid);$i++)
		{
                    if($mistypeid[$i]!='')
                    {
                        if($mispoint[$i]!='')
                        {
                            $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_points_master 
                                                                    WHERE fld_schedule_type='".$type."' AND fld_schedule_id='".$scheduleid."' 
                                                                            AND fld_mis_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
                                                                            AND fld_mistype='".$mistype[$i]."' AND fld_res_id='".$mistypeid[$i]."' AND fld_delstatus='0'");

                            if($cnt!='')
                                $ObjDB->NonQuery("UPDATE itc_mis_points_master SET fld_teacher_points_earned='".$mispoint[$i]."', fld_points_possible='".$mispossible[$i]."', fld_lock='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");
                            else
                                $ObjDB->NonQuery("INSERT INTO itc_mis_points_master (fld_schedule_type, fld_schedule_id, fld_student_id, fld_mis_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_mistype, fld_res_id, fld_grade, fld_created_by, fld_created_date) VALUES('".$type."', '".$scheduleid."', '".$studentid."', '".$unitmodid."', '".$mispoint[$i]."', '".$mispossible[$i]."', '1', '".$mistype[$i]."', '".$mistypeid[$i]."', '1', '".$uid."', '".date("Y-m-d H:i:s")."')");
                        }
                    }
                        
                        
		}
	 	/*********Participation code End here*******/
		
		/*****Teacher Points earned code Start here for pre/post test*****/  
		$mistesttypeid = explode("~",$mistesttypeid);
		$mistesttype = explode("~",$mistesttype);
		$mistestpoint = explode("~",$mistestpoint);
		$mistestpossible = explode("~",$mistestpossible);

		for($j=0;$j<sizeof($mistesttypeid);$j++)
		{
                    if($mistesttypeid[$j]!='')
                    {
                        if($mistestpoint[$j]!='')
                        {
                            $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_points_master 
                                                                    WHERE fld_schedule_type='".$type."' AND fld_schedule_id='".$scheduleid."' 
                                                                            AND fld_mis_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
                                                                            AND fld_mistype='".$mistesttype[$j]."' AND fld_res_id='".$mistesttypeid[$j]."' 
                                                                            AND fld_delstatus='0'");

                            if($cnt!='')
                            {
                                    $ObjDB->NonQuery("UPDATE itc_mis_points_master SET fld_teacher_points_earned='".$mistestpoint[$j]."', fld_points_possible='".$mistestpossible[$j]."', fld_lock='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");
                            }
                            else
                            {
                                    $ObjDB->NonQuery("INSERT INTO itc_mis_points_master (fld_schedule_type, fld_schedule_id, fld_student_id, fld_mis_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_mistype, fld_res_id, fld_grade, fld_created_by, fld_created_date) VALUES('".$type."', '".$scheduleid."', '".$studentid."', '".$unitmodid."', '".$mistestpoint[$j]."', '".$mistestpossible[$j]."', '1', '".$mistesttype[$j]."', '".$mistesttypeid[$j]."', '1', '".$uid."', '".date("Y-m-d H:i:s")."')");
                            } 
                        }
                            /************************************** Nullifing the points earned task code starts. Code Updated by Karthi ********************************/
                            if($mistestpoint[$j]==''){
                                $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_points_master 
                                                                    WHERE fld_schedule_type='".$type."' AND fld_schedule_id='".$scheduleid."' 
                                                                            AND fld_mis_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
                                                                            AND fld_mistype='".$mistesttype[$j]."' AND fld_res_id='".$mistesttypeid[$j]."' 
                                                                            AND fld_delstatus='0'");

                                if($cnt!='')
                                {
                                        $ObjDB->NonQuery("UPDATE itc_mis_points_master SET fld_teacher_points_earned='".$mistestpoint[$j]."', fld_points_possible='".$mistestpossible[$j]."', fld_lock='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");
                                }
                            
                                $ObjDB->NonQuery("UPDATE itc_test_student_answer_track SET fld_retake='1', fld_delstatus = '1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'   
                                                WHERE fld_test_id='".$mistesttypeid[$j]."' AND fld_student_id='".$studentid."' AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."' and fld_retake='0' and fld_delstatus = '0'");
                            }
                            /************************************** Nullifing the points earned task code Ends. Code Updated by Karthi ********************************/
			}
		}
		/*****Teacher Points earned code End here for pre/post test*****/
	}
	
	else if($type==23) // Mission Schedule
	{
	 	/*********Participation code End here*******/
		$mistypeid = explode("~",$mistypeid);
		$mistype = explode("~",$mistype);
		$mispoint = explode("~",$mispoint);
		$mispossible = explode("~",$mispossible);

		for($i=0;$i<sizeof($mistypeid);$i++)
		{
                    if($mistypeid[$i]!='')
                    {
                        if($mispoint[$i]!='')
                        {
                            $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_points_master 
                                                                            WHERE fld_schedule_type='".$type."' AND fld_schedule_id='".$scheduleid."' 
                                                                                    AND fld_mis_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
                                                                                    AND fld_mistype='".$mistype[$i]."' AND fld_res_id='".$mistypeid[$i]."' AND fld_delstatus='0'");

                            if($cnt!='')
                                $ObjDB->NonQuery("UPDATE itc_mis_points_master SET fld_teacher_points_earned='".$mispoint[$i]."', fld_points_possible='".$mispossible[$i]."', fld_lock='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");
                            else
                                $ObjDB->NonQuery("INSERT INTO itc_mis_points_master (fld_schedule_type, fld_schedule_id, fld_student_id, fld_mis_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_mistype, fld_res_id, fld_grade, fld_created_by, fld_created_date) VALUES('".$type."', '".$scheduleid."', '".$studentid."', '".$unitmodid."', '".$mispoint[$i]."', '".$mispossible[$i]."', '1', '".$mistype[$i]."', '".$mistypeid[$i]."', '1', '".$uid."', '".date("Y-m-d H:i:s")."')");
                        }
                    }
		}
	 	/*********Participation code End here*******/
		
		/*****Teacher Points earned code Start here for pre/post test*****/  
		$mistesttypeid = explode("~",$mistesttypeid);
		$mistesttype = explode("~",$mistesttype);
		$mistestpoint = explode("~",$mistestpoint);
		$mistestpossible = explode("~",$mistestpossible);

		for($j=0;$j<sizeof($mistesttypeid);$j++)
		{
			if($mistesttypeid[$j]!='')
			{
                            if($mistestpoint[$j]!='')
                            {
                                $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_points_master 
                                                                        WHERE fld_schedule_type='20' AND fld_schedule_id='".$scheduleid."' 
                                                                                AND fld_mis_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
                                                                                AND fld_mistype='".$mistesttype[$j]."' AND fld_res_id='".$mistesttypeid[$j]."' 
                                                                                AND fld_delstatus='0'");

                                if($cnt!='')
                                {
                                        $ObjDB->NonQuery("UPDATE itc_mis_points_master SET fld_teacher_points_earned='".$mistestpoint[$j]."', fld_points_possible='".$mistestpossible[$j]."', fld_lock='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");
                                }
                                else
                                {
                                        $ObjDB->NonQuery("INSERT INTO itc_mis_points_master (fld_schedule_type, fld_schedule_id, fld_student_id, fld_mis_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_mistype, fld_res_id, fld_grade, fld_created_by, fld_created_date) VALUES('20', '".$scheduleid."', '".$studentid."', '".$unitmodid."', '".$mistestpoint[$j]."', '".$mistestpossible[$j]."', '1', '".$mistesttype[$j]."', '".$mistesttypeid[$j]."', '1', '".$uid."', '".date("Y-m-d H:i:s")."')");
                                } 

                            }
                                /************************************** Nullifing the points earned task code starts. Code Updated by Karthi ********************************/
                                if($mistestpoint[$j]==''){
                                    $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_points_master 
                                                                        WHERE fld_schedule_type='20' AND fld_schedule_id='".$scheduleid."' 
                                                                                AND fld_mis_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
                                                                                AND fld_mistype='".$mistesttype[$j]."' AND fld_res_id='".$mistesttypeid[$j]."' 
                                                                                AND fld_delstatus='0'");

                                    if($cnt!='')
                                    {
                                            $ObjDB->NonQuery("UPDATE itc_mis_points_master SET fld_teacher_points_earned='".$mistestpoint[$j]."', fld_points_possible='".$mistestpossible[$j]."', fld_lock='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");
                                    }
                                    
                                    $ObjDB->NonQuery("UPDATE itc_test_student_answer_track SET fld_retake='1', fld_delstatus = '1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'   
                                                    WHERE fld_test_id='".$mistesttypeid[$j]."' AND fld_student_id='".$studentid."' AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."' and fld_retake='0' and fld_delstatus = '0'");
                                }
                                /************************************** Nullifing the points earned task code Ends. Code Updated by Karthi ********************************/
			}
		}
		/*****Teacher Points earned code End here for pre/post test*****/
	}
	/*********Mission report Code Start Here Developed By Mohan M 16-7-2015 UPDATED BY 13/11/2015*************/	
	
 	/**************************Expedition and Module schedule Code start here by Mohan**********************/
	else if($type==20) // Mod or Expedition
	{
		/*if($expmodpoint!='')
		{
			$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
                                                                    FROM itc_exp_points_master 
                                                                    WHERE fld_schedule_type='".$type."' AND fld_schedule_id='".$scheduleid."' 
                                                                            AND fld_exp_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
                                                                            AND fld_exptype='".$expmodtype."' AND fld_delstatus='0'");

			if($cnt!='')
			{
				$ObjDB->NonQuery("UPDATE itc_exp_points_master SET fld_teacher_points_earned='".$expmodpoint."', fld_points_possible='".$expmodpossible."', fld_lock='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");
			}
			else
			{
				$ObjDB->NonQuery("INSERT INTO itc_exp_points_master (fld_schedule_type, fld_schedule_id, fld_student_id, fld_exp_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_exptype, fld_grade, fld_created_by, fld_created_date) VALUES('".$type."', '".$scheduleid."', '".$studentid."', '".$unitmodid."', '".$expmodpoint."', '".$expmodpossible."', '1', '".$expmodtype."', '1', '".$uid."', '".date("Y-m-d H:i:s")."')");
			}
		}
                */


		/*****Teacher Points earned code Start here for pre/post test*****/  
		$expmodtesttypeid = explode("~",$expmodtesttypeid);
		$expmodtesttype = explode("~",$expmodtesttype);
		$expmodtestpoint = explode("~",$expmodtestpoint);
		$expmodtestpossible = explode("~",$expmodtestpossible);

		for($j=0;$j<sizeof($expmodtesttypeid);$j++)
		{
			if($expmodtesttypeid[$j]!='')
			{
                            if($expmodtestpoint[$j]!='')
                            {
                                $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
                                                                             FROM itc_exp_points_master 
                                                                             WHERE fld_schedule_type='".$type."' AND fld_schedule_id='".$scheduleid."' 
                                                                                             AND fld_exp_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
                                                                                             AND fld_exptype='".$expmodtesttype[$j]."' AND fld_res_id='".$expmodtesttypeid[$j]."' AND fld_delstatus='0'");

                                if($cnt!='')
                                {
                                    $ObjDB->NonQuery("UPDATE itc_exp_points_master SET fld_teacher_points_earned='".$expmodtestpoint[$j]."', fld_points_possible='".$expmodtestpossible[$j]."', fld_lock='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");
                                }
                                else
                                {
                                    $ObjDB->NonQuery("INSERT INTO itc_exp_points_master (fld_schedule_type, fld_schedule_id, fld_student_id, fld_exp_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_exptype, fld_res_id, fld_grade, fld_created_by, fld_created_date) VALUES('".$type."', '".$scheduleid."', '".$studentid."', '".$unitmodid."', '".$expmodtestpoint[$j]."', '".$expmodtestpossible[$j]."', '1', '".$expmodtesttype[$j]."', '".$expmodtesttypeid[$j]."', '1', '".$uid."', '".date("Y-m-d H:i:s")."')");
                                }
                             }
                            
                            if($expmodtestpoint[$j]==""){
                                $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
                                                                             FROM itc_exp_points_master 
                                                                             WHERE fld_schedule_type='".$type."' AND fld_schedule_id='".$scheduleid."' 
                                                                                             AND fld_exp_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
                                                                                             AND fld_exptype='".$expmodtesttype[$j]."' AND fld_res_id='".$expmodtesttypeid[$j]."' AND fld_delstatus='0'");

                                if($cnt!='')
                                {
                                    $ObjDB->NonQuery("UPDATE itc_exp_points_master SET fld_teacher_points_earned='".$expmodtestpoint[$j]."', fld_points_possible='".$expmodtestpossible[$j]."', fld_lock='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");
                                }
                                
                                $qrydet = $ObjDB->QueryObject("SELECT fld_expt as rtexpid,fld_destid as rtdestid,fld_taskid as rttaskid,fld_resid as rtresid
                                                                FROM itc_test_master
                                                                WHERE fld_id = '".$expmodtesttypeid[$j]."' AND fld_delstatus = '0'");
                                $rowqrydet = $qrydet->fetch_assoc();
                                extract($rowqrydet);

                                // For exp test
                                if($rtexpid !=0 and $rtdestid==0 and $rttaskid==0 and $rtresid==0){
                                    $exptestcont = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_testplay_track 
                                                                                WHERE fld_exp_test_id='".$expmodtesttypeid[$j]."' AND fld_exp_id='".$rtexpid."'
                                                                                AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."' AND fld_student_id='".$studentid."'");
                                    if($exptestcont==1){
                                        $ObjDB->NonQuery("UPDATE itc_exp_testplay_track SET fld_retake='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' 
                                                            WHERE fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."' AND fld_student_id='".$studentid."' 
                                                                AND fld_exp_test_id='".$expmodtesttypeid[$j]."' AND fld_exp_id='".$rtexpid."'");

                                        $ObjDB->NonQuery("UPDATE itc_test_student_answer_track SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'  
                                                        WHERE fld_test_id='".$expmodtesttypeid[$j]."' AND fld_student_id='".$studentid."' AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."'");
                                    }
                                }

                                // For dest test
                                if($rtexpid !=0 and $rtdestid!=0 and $rttaskid==0 and $rtresid==0){
                                    $desttestcont = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_dest_testplay_track 
                                                                                WHERE fld_dest_test_id='".$expmodtesttypeid[$j]."' AND fld_exp_id='".$rtexpid."' AND fld_dest_id='".$rtdestid."'
                                                                                AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."' AND fld_student_id='".$studentid."'");
                                    if($desttestcont==1){
                                        $ObjDB->NonQuery("UPDATE itc_exp_dest_testplay_track SET fld_retake='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' 
                                                        WHERE fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."' AND fld_student_id='".$studentid."' 
                                                            AND fld_dest_test_id='".$expmodtesttypeid[$j]."' AND fld_exp_id='".$rtexpid."' AND fld_dest_id='".$rtdestid."'");

                                        $ObjDB->NonQuery("UPDATE itc_test_student_answer_track SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'  
                                                        WHERE fld_test_id='".$expmodtesttypeid[$j]."' AND fld_student_id='".$studentid."' AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."'");
                                    }
                                }

                                // For task test
                                if($rtexpid !=0 and $rtdestid!=0 and $rttaskid!=0 and $rtresid==0){
                                    $tasktestcont = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_task_testplay_track 
                                                                                WHERE fld_task_test_id='".$expmodtesttypeid[$j]."' AND fld_exp_id='".$rtexpid."' AND fld_dest_id='".$rtdestid."' AND fld_task_id='".$rttaskid."'
                                                                                AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."' AND fld_student_id='".$studentid."'");
                                    if($tasktestcont==1){
                                        $ObjDB->NonQuery("UPDATE itc_exp_task_testplay_track SET fld_retake='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' 
                                                        WHERE fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."' AND fld_student_id='".$studentid."' 
                                                            AND fld_task_test_id='".$expmodtesttypeid[$j]."' AND fld_exp_id='".$rtexpid."' AND fld_dest_id='".$rtdestid."' AND fld_task_id='".$rttaskid."'");

                                        $ObjDB->NonQuery("UPDATE itc_test_student_answer_track SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'  
                                                        WHERE fld_test_id='".$expmodtesttypeid[$j]."' AND fld_student_id='".$studentid."' AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."'");
                                    }
                                }

                                // For Res test
                                if($rtexpid !=0 and $rtdestid!=0 and $rttaskid!=0 and $rtresid!=0){
                                    $restestcont = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_testplay_track 
                                                                                WHERE fld_res_test_id='".$expmodtesttypeid[$j]."' AND fld_exp_id='".$rtexpid."' AND fld_dest_id='".$rtdestid."' AND fld_task_id='".$rttaskid."'
                                                                                AND fld_res_id='".$rtresid."' AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."' AND fld_student_id='".$studentid."'");
                                    if($restestcont==1){
                                        $ObjDB->NonQuery("UPDATE itc_exp_res_testplay_track SET fld_retake='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' 
                                                        WHERE fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."' AND fld_student_id='".$studentid."' 
                                                            AND fld_res_test_id='".$expmodtesttypeid[$j]."' AND fld_exp_id='".$rtexpid."' AND fld_dest_id='".$rtdestid."' AND fld_task_id='".$rttaskid."' AND fld_res_id='".$rtresid."'");

                                        $ObjDB->NonQuery("UPDATE itc_test_student_answer_track SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'  
                                                        WHERE fld_test_id='".$expmodtesttypeid[$j]."' AND fld_student_id='".$studentid."' AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$type."'");
                                    }
                                }
                            }
			}
		}
		/*****Teacher Points earned code End here for pre/post test*****/
	}
	else if($type==21) // Module and Custom Content
	{
		if($modorcustom=='1') //Module
		{
			for($a=0;$a<sizeof($modsessiplids1);$a++)
			{
				$modsessiplids = explode("~",$modsessiplids1[$a]);
				$modpointsearned2 = explode("~",$modpointsearned1[$a]);
				for($b=0;$b<sizeof($modsessiplids);$b++)
				{
					if($modsessiplids[$b] !='')
					{
						if($modpointsearned2[$b] =='1111')
						{
						   $ObjDB->NonQuery("update itc_module_play_track  
												set fld_read_status ='0' 
												where fld_tester_id='".$studentid."' and fld_schedule_id='".$scheduleid."' and fld_schedule_type='".$type."' 
												and fld_module_id='".$unitmodid."' and fld_section_id='".$modsessiplids[$b]."' and fld_page_id='0' and fld_delstatus='0'");
						}

					} 
				}
			}
			$qry = $ObjDB->QueryObject("SELECT fld_id 
												FROM itc_module_performance_master 
												WHERE fld_module_id='".$unitmodid."' AND fld_delstatus='0' AND fld_performance_name<>'Attendance' 
												AND fld_performance_name<>'Participation' AND fld_performance_name<>'Total Pages' order by fld_id ASC");

			$cnt=0;
			while($row=$qry->fetch_object())
			{
				$performance[$cnt] = $row->fld_id;
				$cnt++;
			}
			$k=0;
			for($i=0;$i<3;$i++)
			{

				$sessids = explode("~",$modsessiplids[$i]);
				$earned = explode("~",$modpointsearned[$i]);
				$possible = explode("~",$modpointspossible[$i]);	
				for($j=0;$j<sizeof($sessids);$j++)
				{

					if($sessids[$j]!='')
					{
						if($earned[$j]=='1111')
						{
							$lock='0';
							$earned[$j]='';
						}
						else
						{
							$lock='1';
						}
						if($sessids[$j]!='')
						{
							if($sessids[$j]=='7')
							{

								$newschtype = 1;

								$createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");	

								$grade = $ObjDB->SelectSingleValue("SELECT a.fld_grade 
																	FROM itc_module_wca_grade AS a 
																	LEFT JOIN itc_module_performance_master AS b 
																		ON a.fld_page_title=b.fld_performance_name 
																	WHERE a.fld_flag='1' AND a.fld_module_id='".$unitmodid."' AND a.fld_schedule_id='".$scheduleid."' 
																		AND a.fld_type='3' AND b.fld_id='".$performance[$k]."'");
								if($grade=='')
								{
									$grade = $ObjDB->SelectSingleValue("SELECT a.fld_grade 
																			FROM itc_module_wca_grade AS a 
																			LEFT JOIN itc_module_performance_master AS b 
																				ON a.fld_page_title=b.fld_performance_name 
																			WHERE a.fld_flag='1' AND a.fld_module_id='".$unitmodid."' AND a.fld_school_id='".$schoolid."' 
																				AND a.fld_user_id='".$indid."' AND a.fld_type='3' 
																				AND b.fld_id='".$performance[$k]."' AND a.fld_schedule_type='".$newschtype."'");
									if($grade=='')	
									{	
										$grade = $ObjDB->SelectSingleValue("SELECT a.fld_grade 
																			FROM itc_module_wca_grade AS a 
																			LEFT JOIN itc_module_performance_master AS b 
																				ON a.fld_page_title=b.fld_performance_name 
																			WHERE a.fld_flag='1' AND a.fld_module_id='".$unitmodid."' AND a.fld_created_by IN (".$createdids.") 
																				AND a.fld_type='3' AND b.fld_id='".$performance[$k]."' AND a.fld_schedule_type='".$newschtype."'");

										if($grade=='')						
											$grade = '1';									
									}								
								}
								$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
																	FROM itc_module_points_master 
																	WHERE fld_schedule_type='".$type."' AND fld_schedule_id='".$scheduleid."' 
																		AND fld_module_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
																		AND fld_session_id='0' AND fld_type='3' AND fld_delstatus='0'
																		AND fld_preassment_id='".$performance[$k]."'");
								if($cnt!='')
								{
									if($earned[$j]=='')
										$ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_teacher_points_earned='".$earned[$j]."', fld_points_earned='".$earned[$j]."', fld_points_possible='".$possible[$j]."', fld_lock='".$lock."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_grade='".$grade."' WHERE fld_id='".$cnt."'");
									else
										$ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_teacher_points_earned='".$earned[$j]."', fld_points_possible='".$possible[$j]."', fld_lock='".$lock."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_grade='".$grade."' WHERE fld_id='".$cnt."'");
								}
								else
								{
									$ObjDB->NonQuery("INSERT INTO itc_module_points_master (fld_schedule_type, fld_schedule_id, fld_student_id, fld_module_id, fld_session_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_type, fld_preassment_id, fld_grade, fld_created_by, fld_created_date) VALUES('".$type."', '".$scheduleid."', '".$studentid."', '".$unitmodid."', '0', '".$earned[$j]."', '".$possible[$j]."', '".$lock."', '3', '".$performance[$k]."', '".$grade."', '".$uid."', '".date("Y-m-d H:i:s")."')");
								}
							}
							else
							{
								$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
																	FROM itc_module_points_master 
																	WHERE fld_schedule_type='".$type."' AND fld_schedule_id='".$scheduleid."' 
																		AND fld_module_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
																		AND fld_session_id='".$sessids[$j]."' AND fld_type='".$k."' 
																		AND fld_preassment_id='0' AND fld_delstatus='0'");

								$newschtype = 1;

								$createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");	
								if($k==0)
								{
									$grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
																			FROM itc_module_wca_grade 
																			WHERE fld_flag='1' AND fld_module_id='".$unitmodid."' AND fld_schedule_type='".$type."'
																				AND fld_schedule_id='".$scheduleid."' AND fld_type='0' 
																				AND fld_session_id='".$sessids[$j]."'");
									if($grade=='')
									{
										$grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
																			FROM itc_module_wca_grade 
																			WHERE fld_flag='1' AND fld_module_id='".$unitmodid."' AND fld_type='0' AND fld_schedule_type='".$newschtype."'
																				AND fld_session_id='".$sessids[$j]."' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."'");
										if($grade=='')
										{						
											$grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
																				FROM itc_module_wca_grade 
																				WHERE fld_flag='1' AND fld_module_id='".$unitmodid."' AND fld_created_by IN (".$createdids.")
																					AND fld_schedule_type='".$newschtype."' AND fld_session_id='".$sessids[$j]."' AND fld_type='0'");

										}
									}
								}
								else
								{
									$grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
																			FROM itc_module_wca_grade 
																			WHERE fld_flag='1' AND fld_module_id='".$unitmodid."' AND fld_schedule_type='".$type."'
																				AND fld_schedule_id='".$scheduleid."' AND fld_type='".$k."'");

									if($grade=='')
									{
										$grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
																			FROM itc_module_wca_grade 
																			WHERE fld_flag='1' AND fld_module_id='".$unitmodid."' AND fld_school_id='".$schoolid."' 
																				AND fld_user_id='".$indid."' AND fld_type='".$k."' AND fld_schedule_type='".$newschtype."'");
										if($grade=='')
										{
											$grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
																				FROM itc_module_wca_grade 
																				WHERE fld_flag='1' AND fld_module_id='".$unitmodid."' AND fld_schedule_type='".$newschtype."' 
																					AND fld_type='".$k."' AND fld_created_by IN (".$createdids.")");

										}
									}
								}
								if($grade=='')
								{
									if($sessids[$j]==0 && $k==0)
										$grade = '1'; //0 MOhan Changed
									else if($k==1)
										$grade = $ObjDB->SelectSingleValue("select fld_grade from itc_module_performance_master where fld_module_id='".$unitmodid."' and fld_performance_name='Attendance' and fld_delstatus='0' group by fld_performance_name");
									else
										$grade = '1';
								}

								if($cnt!='')
								{
									if($earned[$j]=='')
									{
										$ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_teacher_points_earned='".$earned[$j]."', fld_points_earned='".$earned[$j]."', fld_points_possible='".$possible[$j]."', fld_lock='".$lock."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_grade='".$grade."' WHERE fld_id='".$cnt."'");									
									}
									else
									{
										$ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_teacher_points_earned='".$earned[$j]."', fld_points_possible='".$possible[$j]."', fld_lock='".$lock."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_grade='".$grade."' WHERE fld_id='".$cnt."'");									
									}
								}
								else
								{
									$ObjDB->NonQuery("INSERT INTO itc_module_points_master (fld_schedule_type, fld_schedule_id, fld_student_id, fld_module_id, fld_session_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_type, fld_preassment_id, fld_grade, fld_created_by, fld_created_date) VALUES('".$type."', '".$scheduleid."', '".$studentid."', '".$unitmodid."', '".$sessids[$j]."', '".$earned[$j]."', '".$possible[$j]."', '".$lock."', '".$k."', 0, '".$grade."', '".$uid."', '".date("Y-m-d H:i:s")."')");								
								}
							}
						}
					}
				}
				$k++;			
			}
		}
		else if($modorcustom=='8')//Custom Content
		{
			$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
												FROM itc_module_points_master 
												WHERE fld_schedule_type='22' AND fld_schedule_id='".$scheduleid."' 
													AND fld_module_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
													AND fld_session_id='0' AND fld_type='0' AND fld_preassment_id='0' AND fld_delstatus='0'");

			if($cnt!='')
				$ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_teacher_points_earned='".$modcontpoint."', fld_points_possible='".$modcontpossible."', fld_lock='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");

			else
				$ObjDB->NonQuery("INSERT INTO itc_module_points_master (fld_schedule_type, fld_schedule_id, fld_student_id, fld_module_id, fld_session_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_type, fld_preassment_id, fld_grade, fld_created_by, fld_created_date) VALUES('22', '".$scheduleid."', '".$studentid."', '".$unitmodid."', '0', '".$modcontpoint."', '".$modcontpossible."', '1', '0', '0', '1', '".$uid."', '".date("Y-m-d H:i:s")."')");

		}
	}
 	/**************************Expedition and Module schedule Code End here by Mohan**********************/
	
	
	else if($type==9)
	{
		$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
											FROM itc_test_student_mapping 
											WHERE fld_class_id='".$classid."' AND fld_test_id='".$unitmodid."' 
											AND fld_student_id='".$studentid."' AND fld_flag='1'");
		
		$ObjDB->NonQuery("UPDATE itc_test_student_mapping SET fld_teacher_points_earned='".$testpoint."', fld_points_possible='".$testpossible."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");
		
		if($testpoint==''){
                    
                    /************************************** Nullifing the points earned task code starts. Code Updated by SenthilNathan ********************************/
                    $maxattepmt = $ObjDB->SelectSingleValueInt("SELECT fld_max_attempts	FROM itc_test_student_mapping 
                                                                                    WHERE fld_class_id='".$classid."' AND fld_test_id='".$unitmodid."' 
                                                                                    AND fld_student_id='".$studentid."' AND fld_flag='1'");
                    if($maxattepmt > 0)
                    {
                        $maxattepmtnew = $maxattepmt - 1;
                        //echo ("UPDATE itc_test_student_mapping SET fld_max_attempts='".$maxattepmtnew."' WHERE fld_id='".$cnt."'");   
                        $ObjDB->NonQuery("UPDATE itc_test_student_mapping SET fld_max_attempts='".$maxattepmtnew."' WHERE fld_id='".$cnt."'");    
                        $ObjDB->NonQuery("UPDATE itc_test_student_answer_track SET fld_retake='1', fld_retake = '1' WHERE fld_test_id='".$unitmodid."' AND fld_student_id='".$studentid."' AND fld_attempts = '".$maxattepmt."'");
                    }
                    
                    /************************************** Nullifing the points earned task code ends. Code Updated by SenthilNathan ********************************/
                        
                    $ObjDB->NonQuery("UPDATE itc_test_student_answer_track SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'  WHERE fld_test_id='".$unitmodid."' AND fld_student_id='".$studentid."'");
                }
	}
	
	else if($type==7)
	{
		$questids = explode("~",$questid);
		$questpoints = explode("~",$questpoint);
		$questpossibles = explode("~",$questpossible);
		$questsessids = explode("~",$questsessid);
		$questtypes = explode("~",$questtype);
		
		for($i=0;$i<sizeof($questsessids);$i++)
		{
			if($questpoints[$i]=='1111')
			{
				$lock='0';
				$questpoints[$i]='';
			}
			else
			{
				$lock='1';
			}
					
			$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
												FROM itc_module_points_master 
												WHERE fld_schedule_type='".$type."' AND fld_schedule_id='".$scheduleid."' 
													AND fld_module_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
													AND fld_session_id='".$questsessids[$i]."' AND fld_type='".$questtypes[$i]."' 
													AND fld_preassment_id='".$questids[$i]."' AND fld_delstatus='0'");
			
			if($cnt!='')
			{
				if($questpoints[$i]=='')
					$ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_teacher_points_earned='".$questpoints[$i]."', fld_points_earned='".$questpoints[$i]."', fld_points_possible='".$questpossibles[$i]."', fld_lock='".$lock."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");
				else
					$ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_teacher_points_earned='".$questpoints[$i]."', fld_points_possible='".$questpossibles[$i]."', fld_lock='".$lock."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");
			}
			else
			{
				if($questtypes[$i]!=3)
				{
					$pername = $ObjDB->SelectSingleValue("SELECT fld_page_title FROM itc_module_quest_details WHERE fld_page_id='".$questids[$i]."' AND fld_flag='1'");
					$newses = $questsessids[$i];
				}
				else
				{
					$pername = $ObjDB->SelectSingleValue("SELECT fld_performance_name FROM itc_module_performance_master WHERE fld_id='".$questids[$i]."' AND fld_delstatus='0'");
					$newses = 0;
				}
				
				$grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
													FROM itc_module_wca_grade 
													WHERE fld_flag='1' AND fld_module_id='".$unitmodid."' 
													AND fld_page_title='".addslashes($pername)."' AND fld_schedule_type='7' 
													AND fld_session_id='".$newses."' AND fld_schedule_id='".$scheduleid."' ");
				if($grade=='')		
				{
					$grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
														FROM itc_module_wca_grade 
														WHERE fld_flag='1' AND fld_module_id='".$unitmodid."' 
														AND fld_page_title='".addslashes($pername)."' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' 
														AND fld_session_id='".$newses."' AND fld_schedule_type='7'");
					
					if($grade=='')		
					{
						$createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
						
						$grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
															FROM itc_module_wca_grade 
															WHERE fld_flag='1' AND fld_module_id='".$unitmodid."' 
															AND fld_page_title='".addslashes($pername)."' AND fld_created_by IN (".$createdids.")
															AND fld_session_id='".$newses."' AND fld_schedule_type='7'");
					}
				}
					
				$ObjDB->NonQuery("INSERT INTO itc_module_points_master (fld_schedule_type, fld_schedule_id, fld_student_id, fld_module_id, fld_session_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_type, fld_preassment_id, fld_grade, fld_created_by, fld_created_date) VALUES('".$type."', '".$scheduleid."', '".$studentid."', '".$unitmodid."', '".$questsessids[$i]."', '".$questpoints[$i]."', '".$questpossibles[$i]."', '".$lock."', '".$questtypes[$i]."', '".$questids[$i]."', '".$grade."', '".$uid."', '".date("Y-m-d H:i:s")."')");
			}
			
		}
		$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
											FROM itc_test_student_mapping 
											WHERE fld_class_id='".$classid."' AND fld_test_id='".$unitmodid."' 
												AND fld_student_id='".$studentid."' AND fld_flag='1'");
		
		$ObjDB->NonQuery("UPDATE itc_test_student_mapping SET fld_teacher_points_earned='".$testpoint."', fld_points_possible='".$testpossible."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");
		
		if($testpoint=='')
			$ObjDB->NonQuery("UPDATE itc_test_student_answer_track SET fld_delstatus='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_test_id='".$unitmodid."' AND fld_student_id='".$studentid."'");
	}
	
	else
	{
		if($type==4 or $type==6)
			$qry = $ObjDB->QueryObject("SELECT a.fld_id 
										FROM itc_module_performance_master AS a 
										LEFT JOIN itc_mathmodule_master AS b ON a.fld_module_id=b.fld_module_id 
										WHERE b.fld_id='".$unitmodid."' AND b.fld_delstatus='0' AND a.fld_delstatus='0' 
											AND a.fld_performance_name<>'Attendance' AND a.fld_performance_name<>'Participation' 
											AND a.fld_performance_name<>'Total Pages' ");
		else
			$qry = $ObjDB->QueryObject("SELECT fld_id 
										FROM itc_module_performance_master 
										WHERE fld_module_id='".$unitmodid."' AND fld_delstatus='0' AND fld_performance_name<>'Attendance' 
										AND fld_performance_name<>'Participation' AND fld_performance_name<>'Total Pages' order by fld_id ASC");
			
		$cnt=0;
		while($row=$qry->fetch_object())
		{
			$performance[$cnt] = $row->fld_id;
			$cnt++;
		}
		$k=0;
		for($i=0;$i<3;$i++)
		{
			
			$sessids = explode("~",$sessiplids[$i]);
			$earned = explode("~",$pointsearned[$i]);
			$possible = explode("~",$pointspossible[$i]);	
			for($j=0;$j<sizeof($sessids);$j++)
			{
				
				if($sessids[$j]!='')
				{
					if($earned[$j]=='1111')
					{
						$lock='0';
						$earned[$j]='';
					}
					else
					{
						$lock='1';
					}
					if($sessids[$j]!='')
					{
						if($sessids[$j]=='7')
						{
							if($type==4 or $type==6)
								$newschtype = 2;
							else
								$newschtype = 1;
							
							$createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");	
							
							$grade = $ObjDB->SelectSingleValue("SELECT a.fld_grade 
																FROM itc_module_wca_grade AS a 
																LEFT JOIN itc_module_performance_master AS b 
																	ON a.fld_page_title=b.fld_performance_name 
																WHERE a.fld_flag='1' AND a.fld_module_id='".$unitmodid."' AND a.fld_schedule_id='".$scheduleid."' 
																	AND a.fld_type='3' AND b.fld_id='".$performance[$k]."'");
							if($grade=='') {
								$grade = $ObjDB->SelectSingleValue("SELECT a.fld_grade 
																		FROM itc_module_wca_grade AS a 
																		LEFT JOIN itc_module_performance_master AS b 
																			ON a.fld_page_title=b.fld_performance_name 
																		WHERE a.fld_flag='1' AND a.fld_module_id='".$unitmodid."' AND a.fld_school_id='".$schoolid."' 
																			AND a.fld_user_id='".$indid."' AND a.fld_type='3' 
																			AND b.fld_id='".$performance[$k]."' AND a.fld_schedule_type='".$newschtype."'");
								if($grade=='')	
								{	
									$grade = $ObjDB->SelectSingleValue("SELECT a.fld_grade 
																		FROM itc_module_wca_grade AS a 
																		LEFT JOIN itc_module_performance_master AS b 
																			ON a.fld_page_title=b.fld_performance_name 
																		WHERE a.fld_flag='1' AND a.fld_module_id='".$unitmodid."' AND a.fld_created_by IN (".$createdids.") 
																			AND a.fld_type='3' AND b.fld_id='".$performance[$k]."' AND a.fld_schedule_type='".$newschtype."'");
																			
									if($grade=='')						
										$grade = '1';									
								}								
							}
							$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
																FROM itc_module_points_master 
																WHERE fld_schedule_type='".$type."' AND fld_schedule_id='".$scheduleid."' 
																	AND fld_module_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
																	AND fld_session_id='0' AND fld_type='3' AND fld_delstatus='0'
																	AND fld_preassment_id='".$performance[$k]."'");
							if($cnt!='')
							{
								if($earned[$j]=='')
									$ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_teacher_points_earned='".$earned[$j]."', fld_points_earned='".$earned[$j]."', fld_points_possible='".$possible[$j]."', fld_lock='".$lock."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_grade='".$grade."' WHERE fld_id='".$cnt."'");
								else
									$ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_teacher_points_earned='".$earned[$j]."', fld_points_possible='".$possible[$j]."', fld_lock='".$lock."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_grade='".$grade."' WHERE fld_id='".$cnt."'");
							}
							else
							{
                                                            $countmodperid=0;
                                                            $countmodperid=$ObjDB->SelectSingleValueInt("select fld_id from itc_module_performance_master where fld_module_id='".$unitmodid."' and fld_id='".$performance[$k]."' and fld_delstatus='0'");
//                            
                                                           if($countmodperid>0)
                                                            {
                                                               $ObjDB->NonQuery("INSERT INTO itc_module_points_master (fld_schedule_type, fld_schedule_id, fld_student_id, fld_module_id, fld_session_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_type, fld_preassment_id, fld_grade, fld_created_by, fld_created_date) VALUES('".$type."', '".$scheduleid."', '".$studentid."', '".$unitmodid."', '0', '".$earned[$j]."', '".$possible[$j]."', '".$lock."', '3', '".$performance[$k]."', '".$grade."', '".$uid."', '".date("Y-m-d H:i:s")."')");
                                                            }
								
							}
						}
						else
						{
							$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
																FROM itc_module_points_master 
																WHERE fld_schedule_type='".$type."' AND fld_schedule_id='".$scheduleid."' 
																	AND fld_module_id='".$unitmodid."' AND fld_student_id='".$studentid."' 
																	AND fld_session_id='".$sessids[$j]."' AND fld_type='".$k."' 
																	AND fld_preassment_id='0' AND fld_delstatus='0'");
							
							if($type==4 or $type==6)
								$newschtype = 2;
							else
								$newschtype = 1;
							
							$createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");	
							if($k==0){
								$grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
																		FROM itc_module_wca_grade 
																		WHERE fld_flag='1' AND fld_module_id='".$unitmodid."' AND fld_schedule_type='".$type."'
																			AND fld_schedule_id='".$scheduleid."' AND fld_type='0' 
																			AND fld_session_id='".$sessids[$j]."'");
								if($grade=='')
								{
									$grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
																		FROM itc_module_wca_grade 
																		WHERE fld_flag='1' AND fld_module_id='".$unitmodid."' AND fld_type='0' AND fld_schedule_type='".$newschtype."'
																			AND fld_session_id='".$sessids[$j]."' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."'");
									if($grade=='')
									{						
										$grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
																			FROM itc_module_wca_grade 
																			WHERE fld_flag='1' AND fld_module_id='".$unitmodid."' AND fld_created_by IN (".$createdids.")
																				AND fld_schedule_type='".$newschtype."' AND fld_session_id='".$sessids[$j]."' AND fld_type='0'");

									}
								}
							}
							else
							{
								$grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
																		FROM itc_module_wca_grade 
																		WHERE fld_flag='1' AND fld_module_id='".$unitmodid."' AND fld_schedule_type='".$type."'
																			AND fld_schedule_id='".$scheduleid."' AND fld_type='".$k."'");

								if($grade=='')
								{
									$grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
																		FROM itc_module_wca_grade 
																		WHERE fld_flag='1' AND fld_module_id='".$unitmodid."' AND fld_school_id='".$schoolid."' 
																			AND fld_user_id='".$indid."' AND fld_type='".$k."' AND fld_schedule_type='".$newschtype."'");
									if($grade=='')
									{
										$grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
																			FROM itc_module_wca_grade 
																			WHERE fld_flag='1' AND fld_module_id='".$unitmodid."' AND fld_schedule_type='".$newschtype."' 
																				AND fld_type='".$k."' AND fld_created_by IN (".$createdids.")");

									}
								}
							}
							if($grade==''){
								if($sessids[$j]==0 && $k==0)
									$grade = '0';
                                                                else if($k==1)
                                                                    $grade = $ObjDB->SelectSingleValue("select fld_grade from itc_module_performance_master where fld_module_id='".$unitmodid."' and fld_performance_name='Attendance' and fld_delstatus='0' group by fld_performance_name");
								else
									$grade = '1';
							}
								
							if($cnt!='')
							{
								if($earned[$j]==''){
									$ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_teacher_points_earned='".$earned[$j]."', fld_points_earned='".$earned[$j]."', fld_points_possible='".$possible[$j]."', fld_lock='".$lock."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_grade='".$grade."' WHERE fld_id='".$cnt."'");									
								}
								else{
									$ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_teacher_points_earned='".$earned[$j]."', fld_points_possible='".$possible[$j]."', fld_lock='".$lock."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_grade='".$grade."' WHERE fld_id='".$cnt."'");									
								}
							}
							
							else
							{
								$ObjDB->NonQuery("INSERT INTO itc_module_points_master (fld_schedule_type, fld_schedule_id, fld_student_id, fld_module_id, fld_session_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_type, fld_preassment_id, fld_grade, fld_created_by, fld_created_date) VALUES('".$type."', '".$scheduleid."', '".$studentid."', '".$unitmodid."', '".$sessids[$j]."', '".$earned[$j]."', '".$possible[$j]."', '".$lock."', '".$k."', 0, '".$grade."', '".$uid."', '".date("Y-m-d H:i:s")."')");								
							}
						}
					}
				}
			}
			$k++;			
		}
		
		if($type==4 || $type==6)
		{
			for($i=0;$i<sizeof($diagids);$i++)
			{
				$diagid = explode(",",$diagids[$i]);
				$diagearned = $diagpointsearned[$i];
				$diagpossible = $diagpointspossible[$i];
				
				if($diagearned=='1111')
				{
					$lock='0';
					$diagearned='';
				}
				else
				{
					$lock='1';
				}
				
				for($j=0;$j<sizeof($diagid);$j++)
				{
					if($type==4)
						$newtesttype = 2;
					else
						$newtesttype = 5;
					$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master SET fld_delstatus='1',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_student_id='".$studentid."' AND fld_module_id='".$unitmodid."' AND fld_lesson_id='".$diagid[$j]."' AND fld_test_type='".$newtesttype."' AND fld_delstatus='0'");
					
					$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_master (fld_class_id, fld_schedule_id, fld_student_id, fld_module_id, fld_lesson_id, fld_teacher_points_earned, fld_points_possible, fld_test_type, fld_lock, fld_grade, fld_created_by, fld_created_date) VALUES('".$classid."', '".$scheduleid."', '".$studentid."', '".$unitmodid."', '".$diagid[$j]."', '".$diagearned."', '".$diagpossible."', '".$newtesttype."', '".$lock."','1', '".$uid."', '".date("Y-m-d H:i:s")."')");
				}
			}
		}
	}
}

/*--- Check Rubrics Name Duplication ---*/
if($oper=="checkrubricsname" and $oper != " ")
{
	$rubricsid = isset($method['rid']) ? $method['rid'] : '0';
	$scheduleid = isset($method['sid']) ? $method['sid'] : '0';
	$rubricsname = isset($method['txtrubricsname']) ? fnEscapeCheck($method['txtrubricsname']) : '';
	
	$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
											FROM itc_rubrics_master 
											WHERE MD5(LCASE(REPLACE(fld_rubrics_name,' ','')))='".$rubricsname."' 
												AND fld_delstatus='0' AND fld_id<>'".$rubricsid."'");
	if($count == 0){ echo "true"; }	else { echo "false"; }
}

if($oper=="saverubrics" and $oper != " " )
{
	$rubricsid = isset($method['rubricsid']) ? $method['rubricsid'] : '';
	$classid = isset($method['classid']) ? $method['classid'] : '';
	$studentid = isset($method['studentid']) ? $method['studentid'] : '';
	$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '';
	$unitmodid = isset($method['unitmodid']) ? $method['unitmodid'] : '';
	$rubricsname = isset($method['rubricsname']) ? $method['rubricsname'] : '';
	$pointspossible = isset($method['pointspossible']) ? $method['pointspossible'] : '';
		
	if($rubricsid==0)
	{
		$ObjDB->NonQuery("INSERT INTO itc_rubrics_master (fld_class_id, fld_schedule_id, fld_student_id, fld_unit_id, fld_rubrics_name, fld_points_possible, fld_created_by, fld_created_date) VALUES('".$classid."', '".$scheduleid."', '".$studentid."', '".$unitmodid."', '".$rubricsname."', '".$pointspossible."', '".$uid."', '".date("Y-m-d H:i:s")."')");
		
		echo "success";
	}

	else
	{
		$ObjDB->NonQuery("UPDATE itc_rubrics_master SET fld_class_id='".$classid."', fld_schedule_id='".$scheduleid."', fld_student_id='".$studentid."', fld_unit_id='".$unitmodid."', fld_rubrics_name='".$rubricsname."', fld_points_possible='".$pointspossible."', fld_updated_by='".$uid."', fld_updated_date='".$date("Y-m-d H:i:s")."' WHERE fld_id='".$rubricsid."'");
		
		echo "success";
	}
}

if($oper == "showperformance" and $oper != '')
{
    $classid = isset($method['classid']) ? $method['classid'] : '';
    $rotationid = isset($method['rotationid']) ? $method['rotationid'] : '';
    $scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '';
    $scheduletype = isset($method['scheduletype']) ? $method['scheduletype'] : '';
    // $checkboxeid = isset($method['checkboxeid']) ? $method['checkboxeid'] : '';
    $checkboxeid = isset($method['checkboxeid']) ? $method['checkboxeid'] : '';
    $customid = isset($method['customid']) ? $method['customid'] : '';
    ?>
    <script language="javascript">
        $('#tablecontents3').slimscroll({
            height:'auto',
            railVisible: false,
            allowPageScroll: false,
            railColor: '#F4F4F4',
            opacity: 9,
            color: '#88ABC2',
             wheelStep: 1
        });
        $('.slimScrollDiv').css("height","200px");
        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
               return false;
            }
            return true;
        }
    </script>
    <?php
    /**************Activity code developed by Mohan M**************/
    if($scheduletype==10) 
    {
            $customname = $ObjDB->SelectSingleValue("SELECT fld_activity_name FROM itc_activity_master WHERE fld_id='".$customid."'");
            $custompossible = $ObjDB->SelectSingleValue("SELECT fld_activity_points FROM itc_activity_master WHERE fld_id='".$customid."'");
            ?>
            <p style="text-align:center" class="darkTitle">Max Points</p>
            <p style="text-align:left;padding-left:20px;">Activity Name: <?php echo $customname; ?></p>

            <div class='row'>
                    <div class='eleven columns' style="padding-left:20px;">
                            <br>
                            Assign Points:&nbsp;         
                            <input id="activitytextvlaue" type="text" name="points" onkeypress="return isNumber(event);" onkeyup="ChkValidChar(this.id);" maxlength="3" min="0" max="<?php echo $custompossible; ?>" style="text-align: center; width:5%;">
                            <input type="button" value="Assign Points" onclick="fn_assignactivitypoints(<?php echo $custompossible; ?>);">
                            <input type="button" value="Assign Maximum Points (<?php echo $custompossible; ?>)" onclick="fn_activitymaxpoints(<?php echo $custompossible; ?>);">
                    </div>
            </div>

            <br>
            <div id="assessmenttable" style="text-align:center">
                    &nbsp;
                     <table class='table table-striped table-bordered setbordertopradius' id="mytable" >
                             <thead class='tableHeadText' >
                             <tr>
                                    <th width="50%" class='centerText'>Student Name</th>
                                    <th width="50%" class='centerText'>Points Earned</th>
                            </tr>
                             </thead>
                     </table>
              </div>
            <div style="max-height:200px;width:100%;" id="tablecontents3" >
                    <table style="margin-bottom:0px;" class='table table-striped table-bordered bordertopradiusremove' id="mytabledata">
                            <tbody>        
                                    <?php  
                               $qrystudent = $ObjDB->QueryObject("SELECT a.fld_student_id as studentid, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname 
                                                                                                     FROM itc_activity_student_mapping AS a 
                                                                                                     LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                                                                     WHERE a.fld_class_id = '".$classid."' AND a.fld_flag = '1' AND b.fld_activestatus = '1' 
                                                                                                     AND b.fld_delstatus = '0' 
                                                                                                     ORDER BY studentname");
                                    $totstucount=$qrystudent->num_rows;
                                    if($qrystudent->num_rows>0)
                                    {
                                            $i=1;
                                            while($rowqrystudent = $qrystudent->fetch_assoc()) // show the module based on number of copies
                                            {
                                                    extract($rowqrystudent);
                                                    $qrypointsearn = $ObjDB->SelectSingleValueInt("SELECT fld_points_earned
                                                                                                                FROM itc_activity_student_mapping 
                                                                                                                WHERE fld_activity_id='".$customid."' AND fld_student_id='".$studentid."' 
                                                                                                                AND fld_class_id = '".$classid."' AND fld_created_by='".$uid."'  AND fld_flag='1'");

                                                    ?>  
                                                    <input type="hidden" name="activitystucount" id="activitystucount" value="<?php echo $totstucount; ?>">
                                                    <input type="hidden" name="activitymaxpoints" id="activitymaxpoints" value="<?php echo $custompossible; ?>">
                                                    <tr>         
                                                            <td width="50%" id="student" name="<?php echo $studentid;?>" class='centerText'><?php echo $studentname;?></td>
                                                            <td width="50%" class='centerText'>
                                                             <input type="text" id="activitytxt_<?php echo $i; ?>" maxlength="3" onkeypress="return isNumber(event)" style="text-align: center; width:15%;" value="<?php echo $qrypointsearn; ?>">

                                                       </td>
                                                    </tr>
                                                    <?php
                                                    $i++;
                                            }
                                    }
                                    ?>
                            </tbody>
                    </table>
            </div>
            &nbsp;
       <div class="row">
                    <div class="four columns">
                            <input type="button" value="Close" onclick="$.fancybox.close(); removesections('#reports-gradebook'); fn_showtable(<?php echo $classid;?>,$('.fht-fixed-body').children('.fht-tbody').scrollLeft(),$('.fht-fixed-body').children('.fht-tbody').scrollTop(),0,<?php echo $classid;?>,0,0,1); " class="darkButton" />
                            <input id="savebutt" type="button"  value="Save" class="darkButton" onclick="fn_saveperformance(<?php echo $classid.",".$customid.",".$scheduleid.",".$scheduletype; ?>); $.fancybox.close(); removesections('#reports-gradebook'); fn_showtable(<?php echo $classid;?>,$('.fht-fixed-body').children('.fht-tbody').scrollLeft(),$('.fht-fixed-body').children('.fht-tbody').scrollTop(),0,<?php echo $classid;?>,0,0,1);" />
               </div>
            </div>
    <?php
    } 
    /**************Activity code developed by Mohan M**************/

    else if($scheduletype==17)
    {
        $customname = $ObjDB->SelectSingleValue("SELECT fld_contentname FROM itc_customcontent_master WHERE fld_id='".$customid."'");

             $custompossible = $ObjDB->SelectSingleValue("SELECT fld_pointspossible FROM itc_customcontent_master WHERE fld_id='".$customid."'");
            ?>
         <p style="text-align:center" class="darkTitle">Max Points</p>
         <p style="text-align:left;padding-left:20px;">Custom Content: <?php echo $customname; ?></p>

        <div class='row'>
            <div class='eleven columns' style="padding-left:20px;">
                <br>
                Assign Points:&nbsp;         
                <input id="customtextvlaue" type="text" name="points" onkeypress="return isNumber(event)" maxlength="3" style="text-align: center; width:5%;">
                <input type="button" value="Assign Points" onclick="fn_assigncustompoints();">
                <input type="button" value="Assign Maximum Points (<?php echo $custompossible; ?>)" onclick="fn_custommaxpoints();">
            </div>
        </div>

        <br>
        <div id="assessmenttable" style="text-align:center">
        &nbsp;
         <table class='table table-striped table-bordered setbordertopradius' id="mytable" >
             <thead class='tableHeadText' >
             <tr>
                <th width="50%" class='centerText'>Student Name</th>
                <th width="50%" class='centerText'>Points Earned</th>
            </tr>
             </thead>
         </table>
        </div>
        <div style="max-height:200px;width:100%;" id="tablecontents3" >
        <table style="margin-bottom:0px;" class='table table-striped table-bordered bordertopradiusremove' id="mytabledata">
            <tbody>        
                <?php  
               $qrystudent = $ObjDB->QueryObject("SELECT a.fld_student_id as studentid, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname 
                                                             FROM itc_class_student_mapping AS a 
                                                             LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                             WHERE a.fld_class_id = '".$classid."' AND a.fld_flag = '1' AND b.fld_activestatus = '1' 
                                                             AND b.fld_delstatus = '0' 
                                                             ORDER BY studentname");
                $totstucount=$qrystudent->num_rows;
                if($qrystudent->num_rows>0)
                {
                    $i=1;
                    while($rowqrystudent = $qrystudent->fetch_assoc()) // show the module based on number of copies
                    {

                        extract($rowqrystudent);

                         $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
                                                        FROM itc_module_points_master 
                                                        WHERE fld_schedule_type='".$scheduletype."' AND fld_schedule_id='".$scheduleid."' 
                                                        AND fld_module_id='".$customid."' AND fld_student_id='".$studentid."' 
                                                        AND fld_session_id='0' AND fld_type='0' AND fld_preassment_id='0' AND fld_delstatus='0'");

                             $qrypointsearn = $ObjDB->SelectSingleValueInt("SELECT (CASE WHEN fld_lock='0' THEN fld_points_earned WHEN 
                                                                            fld_lock='1' THEN fld_teacher_points_earned END) 
                                                                            FROM itc_module_points_master 
                                                                            WHERE fld_schedule_type='".$scheduletype."' AND fld_schedule_id='".$scheduleid."' 
                                                                            AND fld_module_id='".$customid."' AND fld_student_id='".$studentid."' 
                                                                            AND fld_session_id='0' AND fld_type='0' AND fld_preassment_id='0' AND fld_delstatus='0'");

                      ?>  
                        <input type="hidden" name="stucount" id="stucount" value="<?php echo $totstucount; ?>">
                        <input type="hidden" name="custommaxpoints" id="custommaxpoints" value="<?php echo $custompossible; ?>">
                        <tr>         
                            <td width="50%" id="student" name="<?php echo $studentid;?>" class='centerText'><?php echo $studentname;?></td>
                            <td width="50%" class='centerText'>
                             <input type="text" id="customtext_<?php echo $i; ?>" maxlength="3" onkeypress="return isNumber(event)" style="text-align: center; width:15%;" value="<?php echo $qrypointsearn; ?>">

                           </td>
                        </tr>
                    <?php

                    $i++;
                    }
                }

                ?>
            </tbody>
        </table>
        </div>
        &nbsp;
        <div class="row">
            <div class="four columns">
                <input type="button" value="Close" onclick="$.fancybox.close(); removesections('#reports-gradebook'); fn_showtable(<?php echo $classid;?>,$('.fht-fixed-body').children('.fht-tbody').scrollLeft(),$('.fht-fixed-body').children('.fht-tbody').scrollTop(),0,<?php echo $classid;?>,0,0,1); " class="darkButton" />
                <input id="savebutt" type="button"  value="Save" class="darkButton" onclick="fn_saveperformance(<?php echo $classid.",".$customid.",".$scheduleid.",".$scheduletype; ?>); $.fancybox.close(); removesections('#reports-gradebook'); fn_showtable(<?php echo $classid;?>,$('.fht-fixed-body').children('.fht-tbody').scrollLeft(),$('.fht-fixed-body').children('.fht-tbody').scrollTop(),0,<?php echo $classid;?>,0,0,1);" />
            </div>
        </div>
        <?php
    } // if ends for the custom content
    
    /******Mission and Mission Schedule code developed by Mohan M***********************/
    else if($scheduletype==18)
    {
        $customname = $ObjDB->SelectSingleValue("SELECT fld_mis_name FROM itc_mission_master WHERE fld_id='".$customid."'");
        $classname = $ObjDB->SelectSingleValue("SELECT fld_class_name FROM itc_class_master WHERE fld_id='".$classid."'");
        
//        $misgtypeid = $ObjDB->SelectSingleValue("SELECT fld_pointspossible FROM itc_class_mission_grade 
//                                                                WHERE fld_schedule_id='".$scheduleid."' AND fld_mis_id='".$customid."' AND fld_mistype='2' AND fld_flag='1'");
        
        $misgtypeid = 100;
                
         $i=1;
        ?>
        <p style="text-align:center" class="darkTitle">Max Points</p>
        <p style="text-align:left;padding-left:20px;">Activity Name: <?php echo $customname; ?></p>
         <input type="hidden" name="misid" id="misid" value="<?php echo $customid; ?>">
        <br>
        <div class='row'>
            <div class='twelve columns' style="padding-left:10px;">     
                <input id="gradingrubric_1" class="gradingrubric_1" type="checkbox"  onclick="fn_chekgvalue('<?php echo $i;?>');" value="<?php echo $i;?>" >
                Participation
            </div>
            <input type="hidden" name="checkgval" id="checkgval" value="0">
            <input type="hidden" name="checkgdval" id="checkgdval" value="0">
             <input type="hidden" name="teachertype" id="teachertype" value="0">
        </div>
       
        <div class='row'>
            <div class='eleven columns' style="padding-left:36px;">
                Assign Points:&nbsp;         
                <input id="textvlaueg" type="text" name="points" maxlength="3" min="0" max="<?php echo $misgtypeid; ?>" onkeyup="ChkValidCharmis(this.id);" onkeypress="return isNumber(event)" style="text-align: center; width:5%;">
                <input type="button" value="Assign Points" onclick="fn_assignpointsgmis(1,<?php echo $misgtypeid; ?>);">
                <input type="button" value="Assign Maximum Points (<?php echo $misgtypeid; ?>)" onclick="fn_maxpointsmis(1,<?php echo $misgtypeid; ?>);">
                <input type="hidden" name="maxpointsmis" id="maxpointsmis" value="<?php echo $misgtypeid; ?>">
                 <input type="hidden" name="checkdgval" id="checkdgval" value="0">
            </div>
        </div>
        <br>
       
         <div id="assessmenttable" style="text-align:center">
            &nbsp;
             <table class='table table-striped table-bordered setbordertopradius' id="mytable" >
                <thead class='tableHeadText' >
                <tr>
                    <th width="20%" class='centerText'>Student Name</th>
                    <th width="40%" class='centerText'>Mission</th>
                    <th width="20%" class='centerText'>Participation</th>
                   <!-- <th width="20%" class='centerText'>Debrief</th> -->
                    
                </tr>
                </thead>
             </table>
        </div>
        <div style="max-height:210px;width:100%;" id="tablecontents3" >
            <table style="margin-bottom:0px;" class='table table-striped table-bordered bordertopradiusremove' id="mytabledata">
                <tbody> 
                    <?php  
                    $limit = $performanceid - 1;
                    $sqry = "fld_id";
                    
                    $stucount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                FROM itc_class_mission_student_mapping 
                                                                WHERE fld_schedule_id='".$scheduleid."' 
                                                                        AND fld_flag='1'");

                    $qrystudent = $ObjDB->QueryObject("SELECT a.fld_student_id as studentid, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname 
                                                                    FROM itc_class_mission_student_mapping AS a 
                                                                    LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                                    WHERE a.fld_schedule_id = '".$scheduleid."' AND a.fld_flag = '1' AND b.fld_activestatus = '1' 
                                                                    AND b.fld_delstatus = '0' 
                                                                    ORDER BY studentname");
                    $stuid=array();
                    $stuname=array();
                    $period=array();
                    $possibleperformpoints=array();
                    if($qrystudent->num_rows>0)
                    {   $m=0;
                        while($rowqrystudent = $qrystudent->fetch_assoc()) // show the module based on number of copies
                        {
                            extract($rowqrystudent);
                            
                                    ?>
                                    <tr>         
                                        <td width="20%" id="student1" name="<?php echo $studentid;?>" class='centerText'><?php echo $studentname;?></td>
                                        <td width="40%" class='centerText'><?php echo $customname; ?></td>
                                        <?php
                                        for($i=2;$i<3;$i++)
                                        {
                                            $pointsearned = $ObjDB->SelectSingleValueInt("SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
                                                                                            THEN fld_teacher_points_earned END) AS pointsearned
                                                                                            FROM itc_mis_points_master 
                                                                                            WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$customid."' 
                                                                                            AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$scheduletype."' 
                                                                                            AND (fld_points_earned<>'' OR fld_teacher_points_earned<>'') AND fld_grade='1' AND fld_mistype='4' AND fld_delstatus='0' ");

                                            $fld_id=$i;
                                            $mistype=$i;
                                            if($pointsearned=='0')
                                            {
                                                $pointsearned='';
                                            }
                                            ?>
                                        
                                            <td width="20%" class='centerText1'>
                                                <input id="perfmark_<?php echo $m."_".$mistype."_".$studentid."_".$customid;?>" onkeyup="ChkValidCharmis(this.id);" maxlength="3" min="0" max="100" type="text" name="perfmark" value="<?php echo $pointsearned; ?>" style="text-align: center; width:50%;" onkeypress="return isNumber(event);" >
                                            </td>
                                             <input type="hidden" name="rubricscore" id="rubricscore_<?php echo $mistype."_".$studentid."_".$customid;?>" value="<?php echo $pointsearned; ?>">
                                            <?php
                                        }
                                        ?>
                                   
                                   </tr>    
                                  <?php
                                
                               
                            $m++;
                        } // student while loop
                        ?>
                            <input type="hidden" name="modcountmis" id="modcountmis" value="<?php echo $stucount; ?>">
                        <?php
                    } //student if condition
                    ?>
                </tbody>
            </table>
        </div>
        &nbsp;
        <div class="row">
            <div class="four columns">
                <input type="button" value="Close" onclick="$.fancybox.close(); removesections('#reports-gradebook'); fn_showtable(<?php echo $classid;?>,$('.fht-fixed-body').children('.fht-tbody').scrollLeft(),$('.fht-fixed-body').children('.fht-tbody').scrollTop(),0,<?php echo $classid;?>,0,0,1); " class="darkButton" />
                <input id="savebutt" style="display:none;" type="button"  value="Save" class="darkButton" onclick="fn_saveperformance(<?php echo $classid.",".$rotationid.",".$scheduleid.",".$scheduletype; ?>); $.fancybox.close(); removesections('#reports-gradebook'); fn_showtable(<?php echo $classid;?>,$('.fht-fixed-body').children('.fht-tbody').scrollLeft(),$('.fht-fixed-body').children('.fht-tbody').scrollTop(),0,<?php echo $classid;?>,0,0,1);" />
            </div>
        </div>       
        <?php
    }
    else if($scheduletype==23)
    { 
        $classname = $ObjDB->SelectSingleValue("SELECT fld_class_name FROM itc_class_master WHERE fld_id='".$classid."'");
        $rot=$rotationid+1;

         $count = 5;
         $i=1;
        ?>
        <p style="text-align:center" class="darkTitle">Max Points</p>
        <p style="text-align:left;padding-left:20px;">Class Name: <?php echo $classname; ?> &nbsp;&nbsp;&nbsp; Rotation Number: <?php echo $rotationid; ?></p>
        <br>
        <div class='row'>
            <div class='twelve columns' style="padding-left:10px;">     
                <input id="gradingrubric_1" class="gradingrubric_1" type="checkbox"  onclick="fn_chekgvalue('<?php echo $i;?>');" value="<?php echo $i;?>" >
                        Participation
            </div>
            <input type="hidden" name="checkgval" id="checkgval" value="0">
            <input type="hidden" name="checkgdval" id="checkgdval" value="0">
             <input type="hidden" name="teachertype" id="teachertype" value="0">
        </div>
       
        <div class='row'>
            <div class='eleven columns' style="padding-left:36px;">
                Assign Points:&nbsp;         
                <input id="textvlaueg" type="text" name="points" maxlength="3" min="0" max="100" onkeyup="ChkValidCharmis(this.id);" onkeypress="return isNumber(event)" style="text-align: center; width:5%;">
                <input type="button" value="Assign Points" onclick="fn_assignpointsgmis(1,100);">
                <input type="button" value="Assign Maximum Points (100)" onclick="fn_maxpointsmis(1,100);">
                <input type="hidden" name="maxpointsmis" id="maxpointsmis" value="100">
            </div>
        </div>
        <br>
        
        <!-- <div class='row'>
            <div class='twelve columns' style="padding-left:10px;">     
                <input id="debrief_1" class="debrief_1" type="checkbox" onclick="fn_chekdvalue('<?php echo $i;?>');" value="<?php echo $i;?>" >
                Debrief
            </div>
            <input type="hidden" name="checkdval" id="checkdval" value="0">
             <input type="hidden" name="checkdgval" id="checkdgval" value="0">
        </div>
       
        <div class='row'>
            <div class='eleven columns' style="padding-left:36px;">
                Assign Points:&nbsp;         
                <input id="textvlaued" type="text" name="points" maxlength="3" min="0" max="100" onkeyup="ChkValidCharmis(this.id);" onkeypress="return isNumber(event)" style="text-align: center; width:5%;">
                <input type="button" value="Assign Points" onclick="fn_assignpointsgmis(2,100);">
                <input type="button" value="Assign Maximum Points (100)" onclick="fn_maxpointsmis(2,100);">
            </div>
        </div> -->

         <div id="assessmenttable" style="text-align:center">
            &nbsp;
             <table class='table table-striped table-bordered setbordertopradius' id="mytable" >
                <thead class='tableHeadText' >
                <tr>
                    <th width="20%" class='centerText'>Student Name</th>
                    <th width="40%" class='centerText'>Mission</th>
                    <th width="20%" class='centerText'>Participation</th>
                    <!--<th width="20%" class='centerText'>Debrief</th> -->
                    
                </tr>
                </thead>
             </table>
        </div>
        <div style="max-height:210px;width:100%;" id="tablecontents3" >
            <table style="margin-bottom:0px;" class='table table-striped table-bordered bordertopradiusremove' id="mytabledata">
                <tbody> 
                    <?php  
                    $limit = $performanceid - 1;
                    $sqry = "fld_id";
                    
                    $stucount = $ObjDB->SelectSingleValueInt("SELECT count(a.fld_student_id) as studentid
                                                                    FROM itc_class_rotation_mission_student_mappingtemp AS a 
                                                                    WHERE a.fld_schedule_id = '".$scheduleid."' AND a.fld_flag = '1'");

                    $qrystudent = $ObjDB->QueryObject("SELECT a.fld_student_id as studentid, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname 
                                                                    FROM itc_class_rotation_mission_student_mappingtemp AS a 
                                                                    LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                                    WHERE a.fld_schedule_id = '".$scheduleid."' AND a.fld_flag = '1' AND b.fld_activestatus = '1' 
                                                                    AND b.fld_delstatus = '0' 
                                                                    ORDER BY studentname");
                    $stuid=array();
                    $stuname=array();
                    $period=array();
                    $possibleperformpoints=array();
                    if($qrystudent->num_rows>0)
                    {   $m=0;
                        while($rowqrystudent = $qrystudent->fetch_assoc()) // show the module based on number of copies
                        {
                            extract($rowqrystudent);
                            $qrymodname = $ObjDB->QueryObject("SELECT a.fld_mission_id as modids,b.fld_mis_name as modname
                                                        FROM itc_class_rotation_mission_schedulegriddet as a
                                                        LEFT JOIN itc_mission_master as b on a.fld_mission_id=b.fld_id
                                                        WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                        AND a.fld_rotation='".$rot."' AND a.fld_student_id='".$studentid."' AND a.fld_flag='1' ");
                            if($qrymodname->num_rows>0)
                            {
                                while($rowqrymod = $qrymodname->fetch_assoc()) // show the module based on number of copies
                                {
                                    extract($rowqrymod);
                                    ?>
                                    <tr>         
                                        <td width="20%" id="student1" name="<?php echo $studentid;?>" class='centerText'><?php echo $studentname;?></td>
                                        <td width="40%" class='centerText'><?php echo $modname; ?></td>
                                        <?php
                                        for($i=2;$i<3;$i++)
                                        {
                                            $pointsearned = $ObjDB->SelectSingleValueInt("SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
                                                                                            THEN fld_teacher_points_earned END) AS pointsearned
                                                                                            FROM itc_mis_points_master 
                                                                                            WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$modids."' 
                                                                                            AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$scheduletype."' 
                                                                                            AND (fld_points_earned<>'' OR fld_teacher_points_earned<>'') AND fld_grade='1' AND fld_mistype='4' AND fld_delstatus='0'");

                                            $fld_id=$i;
                                            $mistype=$i;
                                            if($pointsearned=='0')
                                            {
                                                $pointsearned='';
                                            }
                                            ?>
                                        
                                            <td width="20%" class='centerText1'>
                                                <input id="perfmark_<?php echo $m."_".$mistype."_".$studentid."_".$modids;?>" onkeyup="ChkValidCharmis(this.id);" maxlength="3" min="0" max="100" type="text" name="perfmark" value="<?php echo $pointsearned; ?>" style="text-align: center; width:50%;" onkeypress="return isNumber(event);" >
                                            </td>
                                             <input type="hidden" name="rubricscore" id="rubricscore_<?php echo $mistype."_".$studentid."_".$modids;?>" value="<?php echo $pointsearned; ?>">
                                            <?php
                                        }
                                        ?>
                                   
                                   </tr>    
                                  <?php
                                
                                } //mod id while loop
                                
                            } //mod id if
                            $m++;
                        } // student while loop
                        ?>
                            <input type="hidden" name="modcountmis" id="modcountmis" value="<?php echo $stucount; ?>">
                        <?php
                    } //student if condition
                    ?>
                </tbody>
            </table>
        </div>
        &nbsp;
        <div class="row">
            <div class="four columns">
                <input type="button" value="Close" onclick="$.fancybox.close(); removesections('#reports-gradebook'); fn_showtable(<?php echo $classid;?>,$('.fht-fixed-body').children('.fht-tbody').scrollLeft(),$('.fht-fixed-body').children('.fht-tbody').scrollTop(),0,<?php echo $classid;?>,0,0,1); " class="darkButton" />
                <input id="savebutt" style="display:none;" type="button"  value="Save" class="darkButton" onclick="fn_saveperformance(<?php echo $classid.",".$rotationid.",".$scheduleid.",".$scheduletype; ?>); $.fancybox.close(); removesections('#reports-gradebook'); fn_showtable(<?php echo $classid;?>,$('.fht-fixed-body').children('.fht-tbody').scrollLeft(),$('.fht-fixed-body').children('.fht-tbody').scrollTop(),0,<?php echo $classid;?>,0,0,1);" />
            </div>
        </div>       
        <?php
    }
    /******Mission and Mission Schedule code developed by Mohan M***********************/
	
    else if($scheduletype==0)
    {
        $iplid=$customid;
        $classname = $ObjDB->SelectSingleValue("SELECT fld_class_name FROM itc_class_master WHERE fld_id='".$classid."'");

        $iplname = $ObjDB->SelectSingleValue("SELECT c.fld_unit_name 
                                                            FROM itc_class_sigmath_master AS a 
                                                            LEFT JOIN itc_class_sigmath_unit_mapping AS b ON a.fld_id = b.fld_sigmath_id 
                                                            LEFT JOIN itc_unit_master AS c ON c.fld_id = b.fld_unit_id 
                                                            WHERE a.fld_class_id = '".$classid."' and a.fld_id='".$scheduleid."' AND c.fld_id='".$iplid."' AND a.fld_flag = '1' AND a.fld_delstatus = '0' AND b.fld_flag = '1' AND c.fld_activestatus = '0' 
                                                              AND c.fld_delstatus = '0'");
        ?>
        <p style="text-align:center" class="darkTitle">Max Points</p>
        <p style="text-align:left;padding-left:20px;">Class Name: <?php echo $classname; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; IPL: <?php echo $iplname; ?></p>

        <div class='row'>
            <div class='eleven columns' style="padding-left:20px;">
                <br>
                Assign Points:&nbsp;         
                <input id="ipltextvlaue" type="text" name="points" onkeypress="return isNumber(event)" maxlength="3" style="text-align: center; width:5%;">
                <input type="button" value="Assign Points" onclick="fn_assigniplpoints();">
                <input type="button" value="Assign Maximum Points (100)" onclick="fn_iplmaxpoints();">
             </div>
        </div>

        <br>
        <div id="assessmenttable" style="text-align:center">
            &nbsp;
            <table class='table table-striped table-bordered setbordertopradius' id="mytable" >
                <thead class='tableHeadText' >
                    <tr>
                        <th width="50%" class='centerText'>Student Name</th>
                        <th width="50%" class='centerText'>Points Earned</th>
                   </tr>
                </thead>
            </table>
        </div>
        <div style="max-height:200px;width:100%;" id="tablecontents3" >
            <table style="margin-bottom:0px;" class='table table-striped table-bordered bordertopradiusremove' id="mytabledata">
                <tbody>        
                    <?php

                     $checkflag = $ObjDB->SelectSingleValueInt("SELECT  fld_flag 
                                                                        FROM itc_class_sigmath_grademapping 
                                                                        WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                   AND fld_unit_id='".$iplid."' AND fld_flag = '1'");    
                    if($checkflag !='' )
                    // if($flagvalue==1)
                    {
                    $qrystudent = $ObjDB->QueryObject("SELECT a.fld_student_id as studentid, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname 
                                                                    FROM itc_class_sigmath_student_mapping AS a 
                                                                    LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                                    WHERE a.fld_sigmath_id = '".$scheduleid."' AND a.fld_flag = '1' AND b.fld_activestatus = '1' 
                                                                    AND b.fld_delstatus = '0' 
                                                                    ORDER BY studentname");
                    $totstucount=$qrystudent->num_rows;
                    if($qrystudent->num_rows>0)
                    {
                        $i=1;
                        while($rowqrystudent = $qrystudent->fetch_assoc()) // show the module based on number of copies
                        {
                            extract($rowqrystudent);
                            $qrypointsearn = $ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned 
                                                                                   FROM itc_assignment_sigmath_master 
                                                                                   WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                   AND fld_unit_id='".$iplid."' AND fld_unitmark='1' 
                                                                                   AND fld_student_id='".$studentid."' AND fld_delstatus='0'");

                            ?>  
                            <input type="hidden" name="stucount" id="stucount" value="<?php echo $totstucount; ?>">

                            <tr>         
                                    <td width="50%" id="student" name="<?php echo $studentid;?>" class='centerText'><?php echo $studentname;?></td>
                                    <td width="50%" class='centerText'>
                                     <input type="text" id="ipltext_<?php echo $i; ?>" maxlength="3" onkeypress="return isNumber(event)" style="text-align: center; width:15%;" value="<?php echo $qrypointsearn; ?>">

                               </td>
                            </tr>
                            <?php
                            $i++;
                        }
                    }
                }
                else
                {
                    ?>
                    <p>There is no Math connection for this IPL or Math Connection may unchecked</p>
                    <?php
                }
                ?>
            </tbody>
        </table>
        </div>
        &nbsp;
        <div class="row">
            <div class="four columns">
                <input type="button" value="Close" onclick="$.fancybox.close(); removesections('#reports-gradebook'); fn_showtable(<?php echo $classid;?>,$('.fht-fixed-body').children('.fht-tbody').scrollLeft(),$('.fht-fixed-body').children('.fht-tbody').scrollTop(),0,<?php echo $classid;?>,0,0,1); " class="darkButton" />
                <input id="savebutt" type="button"  value="Save" class="darkButton" onclick="fn_saveperformance(<?php echo $classid.",".$iplid.",".$scheduleid.",".$scheduletype; ?>); $.fancybox.close(); removesections('#reports-gradebook'); fn_showtable(<?php echo $classid;?>,$('.fht-fixed-body').children('.fht-tbody').scrollLeft(),$('.fht-fixed-body').children('.fht-tbody').scrollTop(),0,<?php echo $classid;?>,0,0,1);" />
            </div>
        </div>
        <?php
    }
    else
    {
        $classname = $ObjDB->SelectSingleValue("SELECT fld_class_name FROM itc_class_master WHERE fld_id='".$classid."'");
        $rot=$rotationid+1;
        if($scheduletype==7)
        {
            $qry = $ObjDB->QueryObject("SELECT a.fld_performance_name AS pername
                                                    FROM itc_module_performance_master AS a 
                                                LEFT JOIN itc_class_indassesment_master AS b ON a.fld_module_id=b.fld_module_id 
                                                WHERE b.fld_id='".$scheduleid."' AND a.fld_performance_name<>'Total Pages' 
                                                        AND a.fld_performance_name<>'Attendance' AND a.fld_performance_name<>'Participation' 
                                                        AND a.fld_delstatus='0' AND b.fld_delstatus='0' AND b.fld_flag='1'
                                                ORDER BY a.fld_session_id");
            $count = $qry->num_rows;
            $cnt = 1;
            while($rowqry = $qry->fetch_assoc())
            {
                extract($rowqry);
                $name[$cnt] = $pername;
                $cnt++;
            }
        }
        else
        {
            $count = 3;
        }
        ?>

        <p style="text-align:center" class="darkTitle">Max Points</p>
        <p style="text-align:left;padding-left:20px;">Class Name: <?php echo $classname; ?> &nbsp;&nbsp;&nbsp; Rotation Number: <?php echo $rotationid; ?></p>

        <br>
        <div class='row'>
            <div class='twelve columns' style="padding-left:10px;">                
                <?php 
                for($i=1;$i<=$count;$i++) 
                {
                    if($scheduletype==7)
                        $performname = $name[$i];
                    else
                        $performname = "Performance Assessment ".$i;?>             
                    <div style="float:left;padding-left:10px;">
                        <!--onclick="fn_performance(<?php //echo $classid.",".$rotationid.",".$scheduleid.",".$scheduletype; ?>);" -->
                        <input id="performancechck_<?php echo $i;?>" class="performcheck_<?php echo $i; ?>" type="checkbox" onclick="fn_chekvalue('<?php echo $i;?>');" value="<?php echo $i;?>" >
                        <?php echo $performname;?>
                    </div>
                     <input type="hidden" name="checkdval" id="checkdval" value="<?php echo $i; ?>">
                    <?php 
                }
                ?>
            </div>
        </div>
        <div class='row'>
            <div class='eleven columns' style="padding-left:20px;">
                <br>
                Assign Points:&nbsp;         
                <input id="textvlaue" type="text" name="points" maxlength="2" min="0" max="20" onkeyup="ChkValidChar(this.id);" onkeypress="return isNumber(event)" style="text-align: center; width:5%;">
                <input type="button" value="Assign Points" onclick="fn_assignpoints();">
                <input type="button" value="Assign Maximum Points (20)" onclick="fn_maxpoints();">
                <input type="hidden" name="activitymaxpoints" id="activitymaxpoints" value="20">
            </div>
        </div>
        <div id="assessmenttable" style="text-align:center">
            &nbsp;
             <table class='table table-striped table-bordered setbordertopradius' id="mytable" >
                <thead class='tableHeadText' >
                <tr>
                    <th width="20%" class='centerText'>Student Name</th>
                    <th width="30%" class='centerText'>Module</th>
                    <th width="15%" class='centerText'>Assessment 1</th>
                    <th width="15%" class='centerText'>Assessment 2</th>
                    <th width="15%" class='centerText'>Assessment 3</th>
                </tr>
                </thead>
             </table>
        </div>
        <div style="max-height:210px;width:100%;" id="tablecontents3" >
            <table style="margin-bottom:0px;" class='table table-striped table-bordered bordertopradiusremove' id="mytabledata">
                <tbody>        
                    <?php  
                    $limit = $performanceid - 1;
                    if($scheduletype==7)
                            $sqry = "fld_session_id";
                    else
                            $sqry = "fld_id";
                    
                    
                    if($scheduletype==22)
                    {
                         $qrystudent = $ObjDB->QueryObject("SELECT a.fld_student_id as studentid, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname 
                                                                FROM itc_class_rotation_modexpschedulegriddet AS a 
                                                                    LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                                    WHERE a.fld_class_id = '".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND  a.fld_type='1' AND a.fld_flag = '1' AND b.fld_activestatus = '1' 
                                                                    AND b.fld_delstatus = '0' GROUP BY studentid ORDER BY studentname");
                    }
                    else
                    {

                        $qrystudent = $ObjDB->QueryObject("SELECT a.fld_student_id as studentid, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname 
                                                                        FROM itc_class_student_mapping AS a 
                                                                        LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                                        WHERE a.fld_class_id = '".$classid."' AND a.fld_flag = '1' AND b.fld_activestatus = '1' 
                                                                        AND b.fld_delstatus = '0' 
                                                                        ORDER BY studentname");
                    }
                    
                    $stuid=array();
                    $stuname=array();
                    $period=array();
                    $possibleperformpoints=array();
                    if($qrystudent->num_rows>0)
                    {
                        while($rowqrystudent = $qrystudent->fetch_assoc()) // show the module based on number of copies
                        {
                            extract($rowqrystudent);

                            // for module 
                            if($scheduletype==1)
                            { 
                                $qrymodname = $ObjDB->QueryObject("SELECT a.fld_module_id as modids,b.fld_module_name as modname
                                                                    FROM itc_class_rotation_schedulegriddet as a
                                                                    LEFT JOIN itc_module_master as b on a.fld_module_id=b.fld_id
                                                                        WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                                        AND a.fld_rotation='".$rot."' AND a.fld_student_id='".$studentid."' AND a.fld_flag='1' AND a.fld_type = '1'");
                            }
                            // for math module 
                            if($scheduletype==4)
                            {
                                $qrymodname = $ObjDB->QueryObject("SELECT b.fld_module_id as mmids,b.fld_id as modids,b.fld_mathmodule_name as modname
                                                                    FROM itc_class_rotation_schedulegriddet as a
                                                                    LEFT JOIN itc_mathmodule_master as b on a.fld_module_id=b.fld_id
                                                                    WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                                    AND a.fld_rotation='".$rot."' AND a.fld_student_id='".$studentid."' AND a.fld_flag='1' AND a.fld_type = '2'");

                            }
                            
                            // for module 
                            if($scheduletype==22) //exp/mod schedule
                            { 
                                $qrymodname = $ObjDB->QueryObject("SELECT a.fld_module_id as modids,b.fld_module_name as modname
                                                                    FROM itc_class_rotation_modexpschedulegriddet as a
                                                                    LEFT JOIN itc_module_master as b on a.fld_module_id=b.fld_id
                                                                        WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                                        AND a.fld_rotation='".$rot."' AND a.fld_student_id='".$studentid."' AND a.fld_flag='1' AND a.fld_type = '1'");
                            }
                            
                            
                            // for dyad		    				
                            if($scheduletype==2)
                            {
                                if($rotationid==0)
                                {
                                    $qrymodname = $ObjDB->QueryObject("SELECT b.fld_module_id as modids,c.fld_module_name as modname
                                                                                    FROM itc_class_dyad_schedule_studentmapping AS a 
                                                                                    LEFT JOIN itc_class_dyad_schedulegriddet AS b ON a.fld_schedule_id=b.fld_schedule_id
                                                                                    LEFT JOIN itc_module_master as c on b.fld_module_id=c.fld_id
                                                                                    WHERE b.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                                                        AND b.fld_rotation='".$rotationid."' AND a.fld_student_id='".$studentid."' AND a.fld_flag='1' AND b.fld_flag='1'");
                                }
                                else
                                {
                                    $qrymodname = $ObjDB->QueryObject("SELECT a.fld_module_id as modids,b.fld_module_name as modname
                                                                            FROM itc_class_dyad_schedulegriddet as a
                                                                            LEFT JOIN itc_module_master as b on a.fld_module_id=b.fld_id 
                                                                            WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                                                AND a.fld_rotation='".$rotationid."' AND a.fld_student_id='".$studentid."' AND a.fld_flag='1'");
                                }
                            }
                            // for triad								
                            if($scheduletype==3)
                            {
                                if($rotationid==0)
                                {
                                    $qrymodname = $ObjDB->QueryObject("SELECT b.fld_module_id as modids,c.fld_module_name as modname
                                                                                FROM itc_class_triad_schedule_studentmapping AS a 
                                                                                LEFT JOIN itc_class_triad_schedulegriddet AS b ON a.fld_schedule_id=b.fld_schedule_id
                                                                                LEFT JOIN itc_module_master as c on b.fld_module_id=c.fld_id
                                                                                WHERE b.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."'  
                                                                                 AND b.fld_rotation='".$rotationid."' AND a.fld_flag='1' AND a.fld_student_id='".$studentid."' 
                                                                                                        AND b.fld_flag='1'");
                                }
                                else
                                {
                                    $qrymodname = $ObjDB->QueryObject("SELECT a.fld_module_id as modids,b.fld_module_name as modname
                                                                                FROM itc_class_triad_schedulegriddet as a
                                                                                LEFT JOIN itc_module_master as b on a.fld_module_id=b.fld_id
                                                                                WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                                                                AND a.fld_rotation='".$rotationid."' AND a.fld_student_id='".$studentid."' AND a.fld_flag='1'");
                                }
                            }
                            // for wca									
                            if($scheduletype==5 || $scheduletype==6 || $scheduletype==7)
                            {

                                $qrymodname = $ObjDB->QueryObject("SELECT a.fld_module_id as modids,c.fld_module_name as modname
                                                                        FROM itc_class_indassesment_master AS a 
                                                                        LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id
                                                                        LEFT JOIN itc_module_master as c on a.fld_module_id=c.fld_id
                                                                         WHERE a.fld_class_id='".$classid."' AND b.fld_schedule_id='".$scheduleid."' AND b.fld_student_id='".$studentid."'
                                                                            AND b.fld_flag='1' AND a.fld_flag='1' AND a.fld_delstatus='0'");


                            }
                            if($qrymodname->num_rows>0)
                            {
                                while($rowqrymod = $qrymodname->fetch_assoc()) // show the module based on number of copies
                                {
                                    extract($rowqrymod);

                                    $qryper = $ObjDB->QueryObject("SELECT fld_id AS perid, fld_session_id AS sesid, fld_performance_name AS pername
                                                                        FROM itc_module_performance_master 
                                                                        WHERE fld_module_id='".$modids."' AND fld_delstatus='0' 
                                                                                AND fld_performance_name<>'Attendance' 
                                                                                AND fld_performance_name<>'Participation' 
                                                                                AND fld_performance_name<>'Total Pages' 
                                                                        ORDER BY ".$sqry." ");

                                    $pointpossiresu=array();
                                    if ($qryper->num_rows>0)
                                    {
                                        while($rowqryper = $qryper->fetch_assoc())
                                        {
                                            extract($rowqryper);
                                            $pername = addslashes(preg_replace('/\s+/', '', $pername));
                                            if($sesid == '')
                                                $sesid = 0;

                                            if($scheduletype==1 || $scheduletype==3 || $scheduletype==2 || $scheduletype==5 || $scheduletype==6 || $scheduletype==7)
                                            {
                                                $point = $ObjDB->SelectSingleValue("SELECT fld_id 
                                                                                     FROM itc_module_points_master 
                                                                                     WHERE fld_schedule_id='".$scheduleid."' AND fld_module_id='".$modids."' 
                                                                                         AND fld_student_id='".$studentid."' AND fld_schedule_type='".$scheduletype."' 
                                                                                         AND fld_preassment_id='".$perid."' AND fld_delstatus='0' AND fld_type='3' 
                                                                                         AND fld_session_id='0'");
                                            }
                                            else if($scheduletype==4)
                                            {

                                                $point = $ObjDB->SelectSingleValue("SELECT fld_id 
                                                                                       FROM itc_module_points_master 
                                                                                       WHERE fld_schedule_id='".$scheduleid."' AND fld_module_id='".$modids."' 
                                                                                           AND fld_student_id='".$studentid."' AND fld_schedule_type='".$scheduletype."' 
                                                                                           AND fld_preassment_id='".$perid."' AND fld_delstatus='0' AND fld_type='3' 
                                                                                           AND fld_session_id='0'");

                                            }
                                            else if($scheduletype==22) //exp/mod schedule
                                            {
                                                $point = $ObjDB->SelectSingleValue("SELECT fld_id 
                                                                                     FROM itc_module_points_master 
                                                                                     WHERE fld_schedule_id='".$scheduleid."' AND fld_module_id='".$modids."' 
                                                                                         AND fld_student_id='".$studentid."' AND fld_schedule_type='21' 
                                                                                         AND fld_preassment_id='".$perid."' AND fld_delstatus='0' AND fld_type='3' 
                                                                                         AND fld_session_id='0'");

                                            }

                                            $pointresu[]=$point;
                                            $pername=$ObjDB->EscapeStr($pername);
                                            $qrypoints = $ObjDB->QueryObject("SELECT fld_points AS possiblepoint, fld_grade AS grade
                                                                                FROM itc_module_wca_grade
                                                                                WHERE fld_schedule_id='".$scheduleid."' AND fld_module_id='".$modids."' 	
                                                                                AND REPLACE(fld_page_title, ' ', '')='".addslashes($pername)."' AND fld_flag='1' AND fld_type='3'");
                                            if($qrypoints->num_rows <= 0)
                                            {
                                                //show performance name 
                                                $qrypoints = $ObjDB->QueryObject("SELECT fld_points AS possiblepoint, fld_grade AS grade
                                                                                    FROM itc_module_wca_grade
                                                                                    WHERE fld_module_id='".$modids."' AND fld_user_id='".$indid."' AND fld_school_id='".$schoolid."' 
                                                                                    AND REPLACE(fld_page_title, ' ', '')='".addslashes($pername)."' AND fld_flag='1' AND fld_type='3'");
                                                if($qrypoints->num_rows <= 0)
                                                {
                                                    $createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");

                                                    $qrypoints = $ObjDB->QueryObject("SELECT fld_points AS possiblepoint, fld_grade AS grade
                                                                                        FROM itc_module_wca_grade
                                                                                        WHERE fld_module_id='".$modids."' AND fld_created_by IN (".$createdids.")
                                                                                        AND REPLACE(fld_page_title, ' ', '')='".addslashes($pername)."' AND fld_flag='1' AND fld_type='3' ");
                                                    if($qrypoints->num_rows <= 0)	

                                                    $qrypoints = $ObjDB->QueryObject("SELECT fld_points_possible AS possiblepoint, '1' AS grade
                                                                                        FROM itc_module_performance_master
                                                                                        WHERE fld_module_id='".$modids."' AND fld_id='".$perid."' AND fld_delstatus='0'");
                                                }
                                            }
                                            if($qrypoints->num_rows>0)
                                            {
                                                while($rowqrypoints = $qrypoints->fetch_assoc())
                                                {
                                                    extract($rowqrypoints);
                                                    $possibleperformpoints[]=$possiblepoint;
                                                    $period[]=$perid;
                                                } // while ends $rowqrypoints
                                            } //if ends $qrypoints

                                        } // while ends $rowqryper
                                    } // if ends $qryper
                                    $moduname[]=$modname;
                                    $moduid[]=$modids;
                                    $stuid[]=$studentid;
                                    $stuname[]=$studentname;
                                    ?>
                                    <input type="hidden" name="modids" id="modids" value="<?php echo $modids; ?>">
                                    <?php
                                } //while ends of $rowqrymod
                            } //if ends of $qrymodname
                            ?>
                            <input type="hidden" name="studeids" id="studeids" value="<?php echo $studentid; ?>">
                            <?php
                        } // while ends$rowqrystudent
                    } // if ends $qrystudent

                    $posible=implode(",",$possibleperformpoints);
                    ?>
                    <input type="hidden" name="perfrpoints" id="perfrpoints" value="<?php echo $posible; ?>">
                    <?php
                    $finalres=array_chunk($period,3);
                    $pointfina=array_chunk($pointresu,3);

                    for($y=0;$y<sizeof($moduid);$y++)
                    {
                        ?>
                        <tr>         
                            <td width="20%" id="student1" name="<?php echo $stuid[$y];?>" class='centerText'><?php echo $stuname[$y];?></td>
                            <td width="30%" class='centerText'><?php if($moduname[$y]==''){ echo "No Modules"; }else{ echo $moduname[$y]; } ?></td>
                            <input type="hidden" name="peridsofmod" id="peridsofmod" value="<?php echo $finalres[$y]; ?>">
                            <?php
                            $pointsear=array();
                            for($t=0;$t<sizeof($finalres[$y]);$t++)
                            {
                                $pointvlfinal=explode("~",$finalres[$y][$t]);
                                ?>
                                <input type="hidden" name="periodids" id="periodids" value="<?php echo $finalres[$y][$t]; ?>">

                                <?php

                                if($scheduletype==1 || $scheduletype==3 || $scheduletype==2 || $scheduletype==5 || $scheduletype==6 || $scheduletype==7)
                                {
                                    $qrypointsearn = $ObjDB->QueryObject("SELECT (CASE WHEN fld_lock='0' THEN fld_points_earned WHEN 
                                                                                fld_lock='1' THEN fld_teacher_points_earned END) AS pointsearned 
                                                                                FROM itc_module_points_master 
                                                                                WHERE fld_schedule_id='".$scheduleid."' AND fld_module_id='".$moduid[$y]."' 
                                                                                AND fld_student_id='".$stuid[$y]."' AND fld_schedule_type='".$scheduletype."' 
                                                                                AND fld_preassment_id='".$pointvlfinal[0]."' AND fld_delstatus='0' AND fld_type='3' 
                                                                                AND fld_session_id='0'");
                                 }
                                else if($scheduletype==4)
                                {
                                    $qrypointsearn = $ObjDB->QueryObject("SELECT (CASE WHEN fld_lock='0' THEN fld_points_earned WHEN 
                                                                            fld_lock='1' THEN fld_teacher_points_earned END) AS pointsearned 
                                                                            FROM itc_module_points_master 
                                                                            WHERE fld_schedule_id='".$scheduleid."' AND fld_module_id='".$moduid[$y]."' 
                                                                            AND fld_student_id='".$stuid[$y]."' AND fld_schedule_type='".$scheduletype."' 
                                                                            AND fld_preassment_id='".$pointvlfinal[0]."' AND fld_delstatus='0' AND fld_type='3' 
                                                                            AND fld_session_id='0'");
                                }
                                else if($scheduletype==22)  //exp/mod schedule
                                { 
                                    $qrypointsearn = $ObjDB->QueryObject("SELECT (CASE WHEN fld_lock='0' THEN fld_points_earned WHEN 
                                                                            fld_lock='1' THEN fld_teacher_points_earned END) AS pointsearned 
                                                                            FROM itc_module_points_master 
                                                                                WHERE fld_schedule_id='".$scheduleid."' AND fld_module_id='".$moduid[$y]."' 
                                                                                    AND fld_student_id='".$stuid[$y]."' AND fld_schedule_type='21' 
                                                                                    AND fld_preassment_id='".$pointvlfinal[0]."' AND fld_delstatus='0' AND fld_type='3' 
                                                                                    AND fld_session_id='0'");
                                }

                                if($qrypointsearn->num_rows>0)
                                {
                                    while($rowqrypointsearn = $qrypointsearn->fetch_assoc())
                                    {
                                        extract($rowqrypointsearn);
                                        $pointsear[]=$pointsearned."~".$stuid[$y];
                                        ?>
                                        <td width="15%" class='centerText1'><input id="perfmark<?php echo $y+1; ?>_<?php echo $t+1; ?>" maxlength="2" type="text" name="perfmark" value="<?php echo $pointsearned; ?>" 
                                        style="text-align: center; width:50%;" onkeypress="return isNumber(event);" onblur="fn_cleartextval(1,$(this).val());" ></td>
                                        <?php

                                    } // while ends $rowqrypoints
                                } //if ends $qrypoints
                                else
                                {
                                    ?>
                                    <td width="15%" class='centerText1'><input id="perfmark<?php echo $y+1; ?>_<?php echo $t+1; ?>" maxlength="2" type="text" name="perfmark" value="" 
                                    <?php if($flg!=''){ ?>readonly="readonly"<?php }?> style="text-align: center; width:50%;" onkeypress="return isNumber(event);" onblur="fn_cleartextval(1,$(this).val());" ></td>
                                     <?php   
                                }
                            } //for ends $t 
                            ?>
                        </tr>
                        <?php  
                    } // ends of for loop $y 
                    $perforperiods=implode(",",$totperiods);
                    ?>
                </tbody>
            </table>
        </div>
        &nbsp;
        <div class="row">
            <div class="four columns">
                <input type="button" value="Close" onclick="$.fancybox.close(); removesections('#reports-gradebook'); fn_showtable(<?php echo $classid;?>,$('.fht-fixed-body').children('.fht-tbody').scrollLeft(),$('.fht-fixed-body').children('.fht-tbody').scrollTop(),0,<?php echo $classid;?>,0,0,1); " class="darkButton" />
                <input id="savebutt" style="display:none;" type="button"  value="Save" class="darkButton" onclick="fn_saveperformance(<?php echo $classid.",".$rotationid.",".$scheduleid.",".$scheduletype; ?>); $.fancybox.close(); removesections('#reports-gradebook'); fn_showtable(<?php echo $classid;?>,$('.fht-fixed-body').children('.fht-tbody').scrollLeft(),$('.fht-fixed-body').children('.fht-tbody').scrollTop(),0,<?php echo $classid;?>,0,0,1);" />
            </div>
        </div>
        <?php 	
    }
}

if($oper == "saveperformance" and $oper != '')
{
    $classid = isset($method['classid']) ? $method['classid'] : '';
    $rotationid = isset($method['rotationid']) ? $method['rotationid'] : '';
    $scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '';
    $scheduletype = isset($method['scheduletype']) ? $method['scheduletype'] : '';
    $assepoints = isset($method['assepoints']) ? $method['assepoints'] : '';
    $checkboxeid = isset($method['checkboxeid']) ? $method['checkboxeid'] : '';

    $assessmentpoints=  explode(",",$assepoints);
    /**************Activity code developed by Mohan M**************/
    if($scheduletype==10)
    {
            $custommoduleid=$rotationid;
            $custompossible = $ObjDB->SelectSingleValue("SELECT fld_activity_points FROM itc_activity_master WHERE fld_id='".$custommoduleid."'");
            $totalstud=array();
            $qrystudent = $ObjDB->QueryObject("SELECT a.fld_student_id as studentid, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname 
                                                                                             FROM itc_activity_student_mapping AS a 
                                                                                             LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                                                             WHERE a.fld_class_id = '".$classid."' AND fld_activity_id='".$custommoduleid."' 
                                                                                             AND a.fld_flag = '1' AND b.fld_activestatus = '1'  AND b.fld_delstatus = '0' 
                                                                                             ORDER BY studentname");

            if($qrystudent->num_rows>0)
            {
                    while($rowqrystudent = $qrystudent->fetch_assoc())
                    {
                            extract($rowqrystudent);
                            $totalstud[]=$studentid;
                    }
            }
             for($ss=0;$ss<sizeof($assessmentpoints);$ss++)
             {
                     $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
                                                                                                    FROM itc_activity_student_mapping 
                                                                                                    WHERE fld_activity_id='".$custommoduleid."' AND fld_student_id='".$totalstud[$ss]."' 
                                                                                                    AND fld_class_id = '".$classid."' AND fld_created_by='".$uid."'  AND fld_flag='1' ");

                    if($cnt!='')
                    { 
                            if($assessmentpoints[$ss]<=$custompossible)
                            {
                                    if($assessmentpoints[$ss]=='')
                                    {
                                             $assessmentpoints[$ss]=0;
                                    }
                                    $ObjDB->NonQuery("UPDATE itc_activity_student_mapping SET fld_points_earned='".$assessmentpoints[$ss]."', fld_points_possible='".$custompossible."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");
                            }
                            else
                            {
                                    $ObjDB->NonQuery("UPDATE itc_activity_student_mapping SET fld_points_earned='".$custompossible."', fld_points_possible='".$custompossible."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");

                            }
                    }
                    else
                    {
                      /* if($assessmentpoints[$ss]<=$custompossible)
                            {
                             $ObjDB->NonQuery("INSERT INTO itc_activity_student_mapping (fld_schedule_type, fld_schedule_id, fld_student_id, fld_module_id, fld_session_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_type, fld_preassment_id, fld_grade, fld_created_by, fld_created_date) VALUES('".$scheduletype."', '".$scheduleid."', '".$totalstud[$ss]."', '".$custommoduleid."', '0', '".$assessmentpoints[$ss]."', '".$custompossible."', '1', '0', '0', '1', '".$uid."', '".date("Y-m-d H:i:s")."')");
                            }
                            else
                            {
                              $ObjDB->NonQuery("INSERT INTO itc_activity_student_mapping (fld_schedule_type, fld_schedule_id, fld_student_id, fld_module_id, fld_session_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_type, fld_preassment_id, fld_grade, fld_created_by, fld_created_date) VALUES('".$scheduletype."', '".$scheduleid."', '".$totalstud[$ss]."', '".$custommoduleid."', '0', '".$custompossible."', '".$custompossible."', '1', '0', '0', '1', '".$uid."', '".date("Y-m-d H:i:s")."')");
                            } */
                    }
            }
    }
    /**************Activity code developed by Mohan M**************/
    else if($scheduletype==17)
    {
            $custommoduleid=$rotationid;

             $custompossible = $ObjDB->SelectSingleValue("SELECT fld_pointspossible FROM itc_customcontent_master WHERE fld_id='".$custommoduleid."'");

            $totalstud=array();
             $qrystudent = $ObjDB->QueryObject("SELECT b.fld_student_id as stuid,CONCAT(c.fld_lname, ' ', c.fld_fname) AS studentname, a.fld_module_id as modid
                                                                                            FROM itc_class_indassesment_master AS a 
                                                                                            LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id
                                                                                            LEFT JOIN itc_user_master as c ON b.fld_student_id=c.fld_id
                                                                                            WHERE a.fld_class_id='".$classid."' AND b.fld_schedule_id='".$scheduleid."' 
                                                                                            AND b.fld_flag='1' AND a.fld_flag='1' AND a.fld_delstatus='0'
                                                                                            AND c.fld_activestatus = '1' AND c.fld_delstatus = '0'
                                                                                            ORDER BY studentname");

             if($qrystudent->num_rows>0)
             {
              while($rowqrystudent = $qrystudent->fetch_assoc())
               {
                    extract($rowqrystudent);
                    $totalstud[]=$stuid;


               }
             }


             for($ss=0;$ss<sizeof($assessmentpoints);$ss++)
             {

                     $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
                                                                                                    FROM itc_module_points_master 
                                                                                                    WHERE fld_schedule_type='".$scheduletype."' AND fld_schedule_id='".$scheduleid."' 
                                                                                                    AND fld_module_id='".$custommoduleid."' AND fld_student_id='".$totalstud[$ss]."' 
                                                                                                    AND fld_session_id='0' AND fld_type='0' AND fld_preassment_id='0' AND fld_delstatus='0'");

               if($cnt!='')
               { 
                            if($assessmentpoints[$ss]<=$custompossible)
                            {
                                    if($assessmentpoints[$ss]=='')
                                    {
                                             $assessmentpoints[$ss]=0;
                                    }

                               $ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_teacher_points_earned='".$assessmentpoints[$ss]."', fld_points_possible='".$custompossible."', fld_lock='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");
                            }
                            else
                            {
                               $ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_teacher_points_earned='".$custompossible."', fld_points_possible='".$custompossible."', fld_lock='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");
                            }
               }
               else
                    {
                       if($assessmentpoints[$ss]<=$custompossible)
                            {
                             $ObjDB->NonQuery("INSERT INTO itc_module_points_master (fld_schedule_type, fld_schedule_id, fld_student_id, fld_module_id, fld_session_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_type, fld_preassment_id, fld_grade, fld_created_by, fld_created_date) VALUES('".$scheduletype."', '".$scheduleid."', '".$totalstud[$ss]."', '".$custommoduleid."', '0', '".$assessmentpoints[$ss]."', '".$custompossible."', '1', '0', '0', '1', '".$uid."', '".date("Y-m-d H:i:s")."')");
                            }
                            else
                            {
                              $ObjDB->NonQuery("INSERT INTO itc_module_points_master (fld_schedule_type, fld_schedule_id, fld_student_id, fld_module_id, fld_session_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_type, fld_preassment_id, fld_grade, fld_created_by, fld_created_date) VALUES('".$scheduletype."', '".$scheduleid."', '".$totalstud[$ss]."', '".$custommoduleid."', '0', '".$custompossible."', '".$custompossible."', '1', '0', '0', '1', '".$uid."', '".date("Y-m-d H:i:s")."')");
                            }
                    }
            }

    }

    else if($scheduletype==18 || $scheduletype==23) //  Mission and Mission Schedule
    {
        $modorstuidsg = isset($method['modorstuidsg']) ? $method['modorstuidsg'] : '0';
        $modorstuidsd = isset($method['modorstuidsd']) ? $method['modorstuidsd'] : '0';
        $chkvalg = isset($method['chkvalg']) ? $method['chkvalg'] : '';
        $chkvald = isset($method['chkvald']) ? $method['chkvald'] : '';
        $rotid = $rotationid + 1;

        $modorstuidsg= explode(",",$modorstuidsg);
        $modorstuidsd= explode(",",$modorstuidsd);

        if($scheduletype==18)
        {
            $misgpossible = 100;
            $misgtypeid =4;

        }
        else
        {
            $misgpossible=100;
            $misdpossible=100;
        }

     
		//print_r($modorstuidsg);
		for($i=0;$i<sizeof($modorstuidsg);$i++)
		{
			$modorstuid= explode("_",$modorstuidsg[$i]);
			//echo $modorstuid[0]."-".$modorstuid[1]."-".$modorstuid[2]."-".$modorstuid[3]."-".$modorstuid[4]."<br>";

			if($scheduletype==18)
			{
				
				$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
														FROM itc_mis_points_master 
														WHERE fld_schedule_type='".$scheduletype."' AND fld_schedule_id='".$scheduleid."' 
															AND fld_mis_id='".$modorstuid[3]."' AND fld_student_id='".$modorstuid[2]."' 
															AND fld_mistype='".$misgtypeid."' AND fld_res_id='0'  AND fld_delstatus='0'");//  

				if($cnt!='')
				{
					$ObjDB->NonQuery("UPDATE itc_mis_points_master SET fld_teacher_points_earned='".$modorstuid[4]."', fld_points_possible='".$misgpossible."', fld_lock='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");

				}    
				else
				{
					$ObjDB->NonQuery("INSERT INTO itc_mis_points_master (fld_schedule_type, fld_schedule_id, fld_student_id, fld_mis_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_mistype, fld_grade, fld_created_by, fld_created_date, fld_res_id) VALUES('".$scheduletype."', '".$scheduleid."', '".$modorstuid[2]."', '".$modorstuid[3]."', '".$modorstuid[4]."', '".$misgpossible."', '1', '".$misgtypeid."', '1', '".$uid."', '".date("Y-m-d H:i:s")."','0')");
				}
			}
			else 
			{
				$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
														FROM itc_mis_points_master 
														WHERE fld_schedule_type='".$scheduletype."' AND fld_schedule_id='".$scheduleid."' 
															AND fld_mis_id='".$modorstuid[3]."' AND fld_student_id='".$modorstuid[2]."' 
															AND fld_mistype='4' AND fld_res_id='0' AND fld_delstatus='0'");// 

				if($cnt!='')
				{
					$ObjDB->NonQuery("UPDATE itc_mis_points_master SET fld_teacher_points_earned='".$modorstuid[4]."', fld_points_possible='".$misgpossible."', fld_lock='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");
				}    
				else
				{
					$ObjDB->NonQuery("INSERT INTO itc_mis_points_master (fld_schedule_type, fld_schedule_id, fld_student_id, fld_mis_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_mistype, fld_res_id, fld_grade, fld_created_by, fld_created_date) VALUES('".$scheduletype."', '".$scheduleid."', '".$modorstuid[2]."', '".$modorstuid[3]."', '".$modorstuid[4]."', '".$misgpossible."', '1', '4', '0', '1', '".$uid."', '".date("Y-m-d H:i:s")."')");
				}
			}
		}
      
          
    }
    else if($scheduletype==0)
    {
            $iplid=$rotationid;

             $mathconnectionpossible = 100;

            $totalstud=array();
             $qrystudent = $ObjDB->QueryObject("SELECT a.fld_student_id as studentid, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname 
                                                                                                             FROM itc_class_sigmath_student_mapping AS a 
                                                                                                             LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                                                                             WHERE a.fld_sigmath_id = '".$scheduleid."' AND a.fld_flag = '1' AND b.fld_activestatus = '1' 
                                                                                                             AND b.fld_delstatus = '0' 
                                                                                                             ORDER BY studentname");


             if($qrystudent->num_rows>0)
             {
              while($rowqrystudent = $qrystudent->fetch_assoc())
               {
                    extract($rowqrystudent);
                    $totalstud[]=$studentid;


               }
             }


             for($ss=0;$ss<sizeof($assessmentpoints);$ss++)
             {
                       $cgagrades = $ObjDB->SelectSingleValueInt("SELECT  fld_mgrade
                                                                                                                                    FROM itc_class_sigmath_grademapping 
                                                                                                                                    WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                                                                                    AND fld_unit_id='".$iplid."' AND fld_flag = '1'"); 
                     $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
                                                                                                    FROM itc_assignment_sigmath_master 
                                                                                                    WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' 
                                                                                                    AND fld_unit_id='".$iplid."' AND fld_unitmark='1' 
                                                                                                    AND fld_student_id='".$totalstud[$ss]."' AND fld_delstatus='0'");



               if($cnt!='')
               { 

                            if($assessmentpoints[$ss]<=$mathconnectionpossible)
                            {
                               if($assessmentpoints[$ss]=='')
                                    {
                                             $assessmentpoints[$ss]=0;
                                    }
                               $ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master SET fld_teacher_points_earned='".$assessmentpoints[$ss]."', fld_lock='1', fld_grade='".$cgagrades."', fld_points_possible='".$mathconnectionpossible."', fld_unitmark='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");
                            }
                            else
                            {

                               $ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master SET fld_teacher_points_earned='".$mathconnectionpossible."', fld_lock='1', fld_grade='".$cgagrades."', fld_points_possible='".$mathconnectionpossible."', fld_unitmark='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'");
                            }
               }
               else
                    {
                       if($assessmentpoints[$ss]<=$mathconnectionpossible)
                            {
                             $ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_master (fld_class_id, fld_schedule_id, fld_student_id, fld_unit_id, fld_teacher_points_earned, fld_points_possible, fld_unitmark, fld_lock, fld_grade, fld_created_by, fld_created_date) VALUES('".$classid."', '".$scheduleid."', '".$totalstud[$ss]."', '".$iplid."', '".$assessmentpoints[$ss]."', '".$mathconnectionpossible."', '1', '1', '".$cgagrades."', '".$uid."', '".date("Y-m-d H:i:s")."')");
                            }
                            else
                            {
                              $ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_master (fld_class_id, fld_schedule_id, fld_student_id, fld_unit_id, fld_teacher_points_earned, fld_points_possible, fld_unitmark, fld_lock, fld_grade, fld_created_by, fld_created_date) VALUES('".$classid."', '".$scheduleid."', '".$totalstud[$ss]."', '".$iplid."', '".$mathconnectionpossible."', '".$mathconnectionpossible."', '1', '1', '".$cgagrades."', '".$uid."', '".date("Y-m-d H:i:s")."')");
                            }
                    }
            }
    }
    else
    {
        $rotid = $rotationid + 1;
        if($scheduletype==7)
            $sqry = "fld_session_id";
        else
            $sqry = "fld_id";

        if($scheduletype==1)
        { 
             $qrystudent = $ObjDB->QueryObject("select a.fld_student_id as studentid, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname
                                                    FROM itc_class_rotation_schedulegriddet as a
                                                    LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                    where a.fld_class_id='".$classid."' and a.fld_schedule_id='".$scheduleid."' and a.fld_type='1' 
                                                    and a.fld_rotation='".$rotid."' and a.fld_flag='1' AND b.fld_activestatus = '1' 
                                                                             AND b.fld_delstatus = '0' 
                                                    GROUP BY studentid ORDER BY studentname");
        }
        if($scheduletype==2)
        {
            $qrystudent = $ObjDB->QueryObject("select a.fld_student_id as studentid, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname
                                                FROM itc_class_dyad_schedulegriddet as a
                                                LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                where a.fld_class_id='".$classid."' and a.fld_schedule_id='".$scheduleid."'  
                                                and a.fld_rotation='".$rotid."' and a.fld_flag='1' AND b.fld_activestatus = '1' 
                                                AND b.fld_delstatus = '0' 
                                                GROUP BY studentid ORDER BY studentname");
        }
        if($scheduletype==3)
        {
                $qrystudent = $ObjDB->QueryObject("select a.fld_student_id as studentid, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname
                                                        FROM itc_class_triad_schedulegriddet as a
                                                        LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                        where a.fld_class_id='".$classid."' and a.fld_schedule_id='".$scheduleid."'  
                                                        and a.fld_rotation='".$rotid."' and a.fld_flag='1' AND b.fld_activestatus = '1' 
                                                        AND b.fld_delstatus = '0' 
                                                        GROUP BY studentid ORDER BY studentname");
        }
        if($scheduletype==4)
        { 
                $qrystudent = $ObjDB->QueryObject("select a.fld_student_id as studentid, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname
                                                        FROM itc_class_rotation_schedulegriddet as a
                                                        LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                        where a.fld_class_id='".$classid."' and a.fld_schedule_id='".$scheduleid."' and a.fld_type='2' 
                                                        and a.fld_rotation='".$rotid."' and a.fld_flag='1' AND b.fld_activestatus = '1' 
                                                        AND b.fld_delstatus = '0' 
                                                        GROUP BY studentid ORDER BY studentname");
        }
        if($scheduletype==5 || $scheduletype==6 || $scheduletype==7)
        {
            $qrystudent = $ObjDB->QueryObject("select a.fld_student_id as studentid, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname
                                                  FROM itc_class_indassesment_student_mapping as a
                                                  LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                  where a.fld_schedule_id='".$scheduleid."'  
                                                  and a.fld_flag='1' AND b.fld_activestatus = '1' 
                                                  AND b.fld_delstatus = '0' 
                                                  GROUP BY studentid ORDER BY studentname");
       }
        if($scheduletype==22) //mod/exp schedule
        { 
             $qrystudent = $ObjDB->QueryObject("select a.fld_student_id as studentid, CONCAT(b.fld_lname, ' ', b.fld_fname) AS studentname
                                                FROM itc_class_rotation_modexpschedulegriddet as a
                                                    LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                        where a.fld_class_id='".$classid."' and a.fld_schedule_id='".$scheduleid."' and a.fld_type='1' 
                                                        and a.fld_rotation='".$rotid."' and a.fld_flag='1' AND b.fld_activestatus = '1' AND b.fld_delstatus = '0' 
                                                        GROUP BY studentid ORDER BY studentname");
        }
        $studenids=array();
        $stuname=array();
        $period=array();
        $possibleperformpoints=array();
        if($qrystudent->num_rows>0)
        {
            while($rowqrystudent = $qrystudent->fetch_assoc()) // show the module based on number of copies
            {
                extract($rowqrystudent);
                $studenids[]=$studentid;
                // for module 
                if($scheduletype==1)
                { 
                    $qrymodname = $ObjDB->QueryObject("SELECT a.fld_module_id as modids,b.fld_module_name as modname
                                                        FROM itc_class_rotation_schedulegriddet as a
                                                                        LEFT JOIN itc_module_master as b on a.fld_module_id=b.fld_id
                                                        WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                        AND a.fld_rotation='".$rotid."' AND a.fld_student_id='".$studentid."' AND a.fld_flag='1' AND a.fld_type = '1'");
                }
                // for math module 
                if($scheduletype==4)
                {
                    $qrymodname = $ObjDB->QueryObject("SELECT b.fld_module_id as mmids,b.fld_id as modids,b.fld_mathmodule_name as modname
                                                        FROM itc_class_rotation_schedulegriddet as a
                                                        LEFT JOIN itc_mathmodule_master as b on a.fld_module_id=b.fld_id
                                                        WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                        AND a.fld_rotation='".$rotid."' AND a.fld_student_id='".$studentid."' AND a.fld_flag='1' AND a.fld_type = '2'");

                }
                // for dyad							
                if($scheduletype==2)
                {
                    if($rotationid==0)
                    {
                        $qrymodname = $ObjDB->QueryObject("SELECT b.fld_module_id as modids,c.fld_module_name as modname
                                                                    FROM itc_class_dyad_schedule_studentmapping AS a 
                                                                    LEFT JOIN itc_class_dyad_schedulegriddet AS b ON a.fld_schedule_id=b.fld_schedule_id
                                                                    LEFT JOIN itc_module_master as c on b.fld_module_id=c.fld_id
                                                                    WHERE b.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                                                    AND b.fld_rotation='".$rotationid."' AND a.fld_student_id='".$studentid."' AND a.fld_flag='1' AND b.fld_flag='1'");
                    }
                    else
                    {
                        $qrymodname = $ObjDB->QueryObject("SELECT a.fld_module_id as modids,b.fld_module_name as modname
                                                                FROM itc_class_dyad_schedulegriddet as a
                                                                LEFT JOIN itc_module_master as b on a.fld_module_id=b.fld_id 
                                                                WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                                                AND a.fld_rotation='".$rotationid."' AND a.fld_student_id='".$studentid."' AND a.fld_flag='1'");
                    }
                }
                // for triad								
                if($scheduletype==3)
                {
                    if($rotationid==0)
                    {
                        $qrymodname = $ObjDB->QueryObject("SELECT b.fld_module_id as modids,c.fld_module_name as modname
                                                                    FROM itc_class_triad_schedule_studentmapping AS a 
                                                                    LEFT JOIN itc_class_triad_schedulegriddet AS b ON a.fld_schedule_id=b.fld_schedule_id
                                                                    LEFT JOIN itc_module_master as c on b.fld_module_id=c.fld_id
                                                                    WHERE b.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."'  
                                                                     AND b.fld_rotation='".$rotationid."' AND a.fld_flag='1' AND a.fld_student_id='".$studentid."' 
                                                                                            AND b.fld_flag='1'");
                    }
                    else
                    {
                        $qrymodname = $ObjDB->QueryObject("SELECT a.fld_module_id as modids,b.fld_module_name as modname
                                                                FROM itc_class_triad_schedulegriddet as a
                                                                LEFT JOIN itc_module_master as b on a.fld_module_id=b.fld_id
                                                                WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                                AND a.fld_rotation='".$rotationid."' AND a.fld_student_id='".$studentid."' AND a.fld_flag='1'");
                    }
                }
                // for wca									
                if($scheduletype==5 || $scheduletype==6 || $scheduletype==7)
                {
                    $qrymodname = $ObjDB->QueryObject("SELECT a.fld_module_id as modids,c.fld_module_name as modname
                                                        FROM itc_class_indassesment_master AS a 
                                                        LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id
                                                        LEFT JOIN itc_module_master as c on a.fld_module_id=c.fld_id
                                                        WHERE a.fld_class_id='".$classid."' AND b.fld_schedule_id='".$scheduleid."' AND b.fld_student_id='".$studentid."'
                                                            AND b.fld_flag='1' AND a.fld_flag='1' AND a.fld_delstatus='0'");


                }
                if($scheduletype==22) //mod/exp schedule
                { 
                    $qrymodname = $ObjDB->QueryObject("SELECT a.fld_module_id as modids,b.fld_module_name as modname
                                                        FROM itc_class_rotation_modexpschedulegriddet as a
                                                        LEFT JOIN itc_module_master as b on a.fld_module_id=b.fld_id
                                                            WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' 
                                                            AND a.fld_rotation='".$rotid."' AND a.fld_student_id='".$studentid."' AND a.fld_flag='1' AND a.fld_type = '1'");
                }

                if($qrymodname->num_rows>0)
                {
                    while($rowqrymod = $qrymodname->fetch_assoc()) // show the module based on number of copies
                    {
                        extract($rowqrymod);

                        $modulids[]=$modids;
                        $qryper = $ObjDB->QueryObject("SELECT fld_id AS perid, fld_session_id AS sesid, fld_performance_name AS pername
                                                            FROM itc_module_performance_master 
                                                            WHERE fld_module_id='".$modids."' AND fld_delstatus='0' 
                                                                    AND fld_performance_name<>'Attendance' 
                                                                    AND fld_performance_name<>'Participation' 
                                                                    AND fld_performance_name<>'Total Pages' 
                                                            ORDER BY ".$sqry." ");

                        $pointpossiresu=array();
                        if ($qryper->num_rows>0)
                        {
                            while($rowqryper = $qryper->fetch_assoc())
                            {
                                extract($rowqryper);
                                $periodid[]=$perid;
                                $sessid[]=$sesid;
                                $pername = preg_replace('/\s+/', '', $pername);
                                if($sesid == '')
                                $sesid = 0;

                                if($scheduletype==1 || $scheduletype==3 || $scheduletype==2 || $scheduletype==5 || $scheduletype==6 || $scheduletype==7)
                                {
                                    $point = $ObjDB->SelectSingleValue("SELECT fld_id 
                                                                        FROM itc_module_points_master 
                                                                        WHERE fld_schedule_id='".$scheduleid."' AND fld_module_id='".$modids."' 
                                                                            AND fld_student_id='".$studentid."' AND fld_schedule_type='".$scheduletype."' 
                                                                            AND fld_preassment_id='".$perid."' AND fld_delstatus='0' AND fld_type='3' 
                                                                            AND fld_session_id='0'");
                                }
                                else if($scheduletype==4)
                                {
                                    $point = $ObjDB->SelectSingleValue("SELECT fld_id 
                                                                       FROM itc_module_points_master 
                                                                       WHERE fld_schedule_id='".$scheduleid."' AND fld_module_id='".$modids."' 
                                                                                AND fld_student_id='".$studentid."' AND fld_schedule_type='".$scheduletype."' 
                                                                                AND fld_preassment_id='".$perid."' AND fld_delstatus='0' AND fld_type='3' 
                                                                                AND fld_session_id='0'");

                                }
                                else if($scheduletype==22)  //mod/exp schedule
                                {
                                    $point = $ObjDB->SelectSingleValue("SELECT fld_id 
                                                                        FROM itc_module_points_master 
                                                                        WHERE fld_schedule_id='".$scheduleid."' AND fld_module_id='".$modids."' 
                                                                            AND fld_student_id='".$studentid."' AND fld_schedule_type='21' 
                                                                            AND fld_preassment_id='".$perid."' AND fld_delstatus='0' AND fld_type='3' 
                                                                            AND fld_session_id='0'");

                                }
                                $pointresu[]=$point;

                                $qrypoints = $ObjDB->QueryObject("SELECT fld_points AS possiblepoint, fld_grade AS grade
                                                                    FROM itc_module_wca_grade
                                                                    WHERE fld_schedule_id='".$scheduleid."' AND fld_module_id='".$modids."' 	
                                                                        AND REPLACE(fld_page_title, ' ', '')='".addslashes($pername)."' AND fld_flag='1' AND fld_type='3'");
                                if($qrypoints->num_rows <= 0)
                                {
                                    //show performance name 
                                    $qrypoints = $ObjDB->QueryObject("SELECT fld_points AS possiblepoint, fld_grade AS grade
                                                                        FROM itc_module_wca_grade
                                                                        WHERE fld_module_id='".$modids."' AND fld_user_id='".$indid."' AND fld_school_id='".$schoolid."' 
                                                                            AND REPLACE(fld_page_title, ' ', '')='".addslashes($pername)."' AND fld_flag='1' AND fld_type='3'");
                                    if($qrypoints->num_rows <= 0)
                                    {
                                        $createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");

                                        $qrypoints = $ObjDB->QueryObject("SELECT fld_points AS possiblepoint, fld_grade AS grade
                                                                            FROM itc_module_wca_grade
                                                                            WHERE fld_module_id='".$modids."' AND fld_created_by IN (".$createdids.")
                                                                                            AND REPLACE(fld_page_title, ' ', '')='".addslashes($pername)."' AND fld_flag='1' AND fld_type='3' ");
                                        if($qrypoints->num_rows <= 0)	

                                            $qrypoints = $ObjDB->QueryObject("SELECT fld_points_possible AS possiblepoint, '1' AS grade
                                                                        FROM itc_module_performance_master
                                                                        WHERE fld_module_id='".$modids."' AND fld_id='".$perid."' AND fld_delstatus='0'");
                                    }
                                }
                                if($qrypoints->num_rows>0){
                                    while($rowqrypoints = $qrypoints->fetch_assoc()){
                                        extract($rowqrypoints);
                                        $grades[]=$grade;
                                        $points[]=$point;
                                        $psblepoints[]=$possiblepoint;

                                    } // while ends $rowqrypoints
                                } //if ends $qrypoints
                            } // while ends $rowqryper
                        } // if ends $qryper
                    } //while ends of $rowqrymod
                 } //if ends of $qrymodname
            } // while ends$rowqrystudent
        } // if ends $qrystudent
        $finalres=array_chunk($assessmentpoints,3);
        $finalperiod=array_chunk($periodid,3);
        $finalgrades=array_chunk($grades,3);
        $finalpoints=array_chunk($points,3);
        $finalpsblepoints=array_chunk($psblepoints,3);
        $finalsessid=array_chunk($sessid,3);
       
        for($stu=0;$stu<sizeof($modulids);$stu++)
        {
            for($ss=0;$ss<sizeof($finalres[$stu]);$ss++)
            {
                if($finalpoints[$stu][$ss]!='')
                {
                    if($finalres[$stu][$ss]<=$finalpsblepoints[$stu][$ss])
                    {
                        $ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_teacher_points_earned='".$finalres[$stu][$ss]."', fld_points_possible='".$finalpsblepoints[$stu][$ss]."', 
                                                fld_lock='1', fld_grade='".$finalgrades[$stu][$ss]."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
                                                WHERE fld_id='".$finalpoints[$stu][$ss]."'");
                    }
                    else
                    {
                        $ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_teacher_points_earned='".$finalpsblepoints[$stu][$ss]."', fld_points_possible='".$finalpsblepoints[$stu][$ss]."', 
                                                fld_lock='1', fld_grade='".$finalgrades[$stu][$ss]."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'
                                                WHERE fld_id='".$finalpoints[$stu][$ss]."'");
                    }
                }
                else if($finalpoints[$stu][$ss]=='')
                {
                    if($finalres[$stu][$ss]<=$finalpsblepoints[$stu][$ss])
                    {
                        if($finalsessid[$stu][$ss] == '')
                        {
                            $sessid = 0;
                        }
                        
                        
                        if($scheduletype==22)
                        {
                            $countmodperid=0;
                            $countmodperid=$ObjDB->SelectSingleValueInt("select fld_id from itc_module_performance_master where fld_module_id='".$modulids[$stu]."' and fld_id='".$finalperiod[$stu][$ss]."' and fld_delstatus='0'");
                            
                            if($countmodperid>0)
                            {
                                $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
                                                                                FROM itc_module_points_master 
                                                                                WHERE fld_schedule_type='".$scheduletype."' AND fld_schedule_id='".$scheduleid."' 
                                                                                        AND fld_module_id='".$modulids[$stu]."' AND fld_student_id='".$studenids[$stu]."' 
                                                                                        AND fld_session_id='0' AND fld_type='3' AND fld_delstatus='0'
                                                                                        AND fld_preassment_id='".$finalperiod[$stu][$ss]."'");
                               if($cnt=='')
                               {
                                        $ObjDB->NonQuery("INSERT INTO itc_module_points_master (fld_schedule_type, fld_schedule_id, fld_student_id, fld_module_id, 
                                            fld_session_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_type, fld_preassment_id, 
                                            fld_grade, fld_created_by, fld_created_date) 
                                            VALUES('21', '".$scheduleid."', '".$studenids[$stu]."', '".$modulids[$stu]."', '".$sessid."', 
                                            '".$finalres[$stu][$ss]."', '".$finalpsblepoints[$stu][$ss]."', '1', '3', '".$finalperiod[$stu][$ss]."', '".$finalgrades[$stu][$ss]."', 
                                            '".$uid."', '".date("Y-m-d H:i:s")."')");
                               }
                               else
                               {
                                   $ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_teacher_points_earned='".$finalres[$stu][$ss]."', fld_points_possible='".$finalpsblepoints[$stu][$ss]."', fld_lock='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_grade='".$finalgrades[$stu][$ss]."' WHERE fld_id='".$cnt."'");
                               }
                            }
                        }
                        else
                        {
                            $countmodperid=0;
                            $countmodperid=$ObjDB->SelectSingleValueInt("select fld_id from itc_module_performance_master where fld_module_id='".$modulids[$stu]."' and fld_id='".$finalperiod[$stu][$ss]."' and fld_delstatus='0'");
                            if($countmodperid>0)
                            {
                                $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
                                                                                FROM itc_module_points_master 
                                                                                WHERE fld_schedule_type='".$scheduletype."' AND fld_schedule_id='".$scheduleid."' 
                                                                                        AND fld_module_id='".$modulids[$stu]."' AND fld_student_id='".$studenids[$stu]."' 
                                                                                        AND fld_session_id='0' AND fld_type='3' AND fld_delstatus='0'
                                                                                        AND fld_preassment_id='".$finalperiod[$stu][$ss]."'");
                               if($cnt=='')
                               {
                             
                                    $ObjDB->NonQuery("INSERT INTO itc_module_points_master (fld_schedule_type, fld_schedule_id, fld_student_id, fld_module_id, 
                                                   fld_session_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_type, fld_preassment_id, 
                                                   fld_grade, fld_created_by, fld_created_date) 
                                                   VALUES('".$scheduletype."', '".$scheduleid."', '".$studenids[$stu]."', '".$modulids[$stu]."', '".$sessid."', 
                                                   '".$finalres[$stu][$ss]."', '".$finalpsblepoints[$stu][$ss]."', '1', '3', '".$finalperiod[$stu][$ss]."', '".$finalgrades[$stu][$ss]."', 
                                                   '".$uid."', '".date("Y-m-d H:i:s")."')");
                               }
                               else
                               {
                                   $ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_teacher_points_earned='".$finalres[$stu][$ss]."', fld_points_possible='".$finalpsblepoints[$stu][$ss]."', fld_lock='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_grade='".$finalgrades[$stu][$ss]."' WHERE fld_id='".$cnt."'");
                               }
                            }
                        }

                       
                    }
                    else
                    {
                        if($finalsessid[$stu][$ss] == '')
                        {
                            $sessid = 0;
                        }
                       
                        if($scheduletype==22)
                        {
                            $countmodperid=0;
                            $countmodperid=$ObjDB->SelectSingleValueInt("select fld_id from itc_module_performance_master where fld_module_id='".$modulids[$stu]."' and fld_id='".$finalperiod[$stu][$ss]."' and fld_delstatus='0'");
                            
                            if($countmodperid>0)
                            {
                                $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
                                                                                FROM itc_module_points_master 
                                                                                WHERE fld_schedule_type='".$scheduletype."' AND fld_schedule_id='".$scheduleid."' 
                                                                                        AND fld_module_id='".$modulids[$stu]."' AND fld_student_id='".$studenids[$stu]."' 
                                                                                        AND fld_session_id='0' AND fld_type='3' AND fld_delstatus='0'
                                                                                        AND fld_preassment_id='".$finalperiod[$stu][$ss]."'");
                               if($cnt=='')
                               {
                                        $ObjDB->NonQuery("INSERT INTO itc_module_points_master (fld_schedule_type, fld_schedule_id, fld_student_id, fld_module_id, 
                                            fld_session_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_type, fld_preassment_id, 
                                            fld_grade, fld_created_by, fld_created_date) 
                                            VALUES('21', '".$scheduleid."', '".$studenids[$stu]."', '".$modulids[$stu]."', '".$sessid."',
                                            '".$finalpsblepoints[$stu][$ss]."', '".$finalpsblepoints[$stu][$ss]."', '1', '3', '".$finalperiod[$stu][$ss]."', '".$finalgrades[$stu][$ss]."', 
                                            '".$uid."', '".date("Y-m-d H:i:s")."')");
                               }
                               else
                               {
                                   $ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_teacher_points_earned='".$finalres[$stu][$ss]."', fld_points_possible='".$finalpsblepoints[$stu][$ss]."', fld_lock='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_grade='".$finalgrades[$stu][$ss]."' WHERE fld_id='".$cnt."'");
                               }
                            }
                        }
                        else
                        {
                            $countmodperid=0;
                            $countmodperid=$ObjDB->SelectSingleValueInt("select fld_id from itc_module_performance_master where fld_module_id='".$modulids[$stu]."' and fld_id='".$finalperiod[$stu][$ss]."' and fld_delstatus='0'");
                            
                            if($countmodperid>0)
                            {
                                $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
                                                                                FROM itc_module_points_master 
                                                                                WHERE fld_schedule_type='".$scheduletype."' AND fld_schedule_id='".$scheduleid."' 
                                                                                        AND fld_module_id='".$modulids[$stu]."' AND fld_student_id='".$studenids[$stu]."' 
                                                                                        AND fld_session_id='0' AND fld_type='3' AND fld_delstatus='0'
                                                                                        AND fld_preassment_id='".$finalperiod[$stu][$ss]."'");
                               if($cnt=='')
                               {
                                    $ObjDB->NonQuery("INSERT INTO itc_module_points_master (fld_schedule_type, fld_schedule_id, fld_student_id, fld_module_id, 
                                                                    fld_session_id, fld_teacher_points_earned, fld_points_possible, fld_lock, fld_type, fld_preassment_id, 
                                                                    fld_grade, fld_created_by, fld_created_date) 
                                                                    VALUES('".$scheduletype."', '".$scheduleid."', '".$studenids[$stu]."', '".$modulids[$stu]."', '".$sessid."',
                                                                    '".$finalpsblepoints[$stu][$ss]."', '".$finalpsblepoints[$stu][$ss]."', '1', '3', '".$finalperiod[$stu][$ss]."', '".$finalgrades[$stu][$ss]."', 
                                                                    '".$uid."', '".date("Y-m-d H:i:s")."')");
                               }
                               else
                               {
                                   $ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_teacher_points_earned='".$finalres[$stu][$ss]."', fld_points_possible='".$finalpsblepoints[$stu][$ss]."', fld_lock='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_grade='".$finalgrades[$stu][$ss]."' WHERE fld_id='".$cnt."'");
                               }
                            }

                        }
                        
                    }
                }
            }
        } // student for ends
    } // else ends 
}
/*--- Check Grade Period Name Duplication ---*/
if($oper=="checkgradename" and $oper != " ")
{
	$classid = isset($method['classid']) ? $method['classid'] : '0';
	$gradename = isset($method['txtgradename']) ? fnEscapeCheck($method['txtgradename']) : '';
	
	$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
											FROM itc_reports_gradebook_master 
											WHERE MD5(LCASE(REPLACE(fld_grade_name,' ','')))='".$gradename."' 
												AND fld_delstatus='0' AND fld_class_id='".$classid."'");
	if($count == 0){ echo "true"; }	else { echo "false"; }
}

if($oper == "savegradeperiod" and $oper != '')
{
	$classid = isset($_REQUEST['classid']) ? $_REQUEST['classid'] : '0';
	$gradename = isset($_REQUEST['gradename']) ? $_REQUEST['gradename'] : '0';
	$startdate1 = isset($_REQUEST['startdate1']) ? $_REQUEST['startdate1'] : '0';
	$enddate1 = isset($_REQUEST['enddate1']) ? $_REQUEST['enddate1'] : '0';
	$editid = isset($_REQUEST['editid']) ? $_REQUEST['editid'] : '0';
	
	$startdate = date('Y-m-d',strtotime($startdate1));
	$enddate = date('Y-m-d',strtotime($enddate1));
	
	if($editid==0)
		$ObjDB->NonQuery("INSERT INTO itc_reports_gradebook_master (fld_class_id, fld_grade_name, fld_start_date, fld_end_date, fld_created_by, fld_created_date) VALUES('".$classid."', '".$gradename."', '".$startdate."', '".$enddate."', '".$uid."', '".$date."')");
		
	else
		$ObjDB->NonQuery("UPDATE itc_reports_gradebook_master SET fld_grade_name='".$gradename."', fld_start_date='".$startdate."', fld_end_date='".$enddate."', fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_id='".$editid."'");
}

if($oper=="remove" and $oper != " ")
{
	$id = isset($method['id']) ? $method['id'] : '0';
	
	$ObjDB->NonQuery("UPDATE itc_reports_gradebook_master SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".$date."' WHERE fld_id='".$id."'");
}


/*--- Save and Update the  Rubric Code Start Here For EXPEDITION MISSION EXPEDITION SCHEDULE Developed by Mohan M****************/
if($oper=="saverubric" and $oper != " ")
{
    try{
        $rubid = isset($method['rubnameid']) ? $method['rubnameid'] : '0'; 
        $expid = isset($method['expid']) ? $method['expid'] : '0'; 
        $classid = isset($method['classid']) ? $method['classid'] : '0';
        
	 	$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
        
        $stuid = isset($method['studentid']) ? ($method['studentid']) : '0'; 
        $score = isset($method['txtscore']) ? $method['txtscore'] : '0'; 
        $score = isset($method['txtcomment']) ? $method['txtcomment'] : '0'; 
        $ruborderid = isset($method['ruborderid']) ? $method['ruborderid'] : '0'; 
        $destid = isset($method['destid']) ? $method['destid'] : '0'; 
		
	 	$typeid = isset($method['typeid']) ? $method['typeid'] : '0'; 
		$cellid = isset($method['cellid']) ? $method['cellid'] : '0'; 
		
        $sco=explode(",",$score);
       
		if($typeid=='16')
		{
			//save class Name
			$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_rpt WHERE fld_exp_id='".$expid."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'  
														AND fld_rubric_nameid='".$rubid."' AND fld_created_by='".$uid."' AND fld_delstatus = '0'");
			if($cnt==0)
			{
			   $maxid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_exp_rubric_rpt (fld_class_id, fld_exp_id, fld_rubric_nameid, fld_created_by, fld_created_date, fld_district_id, fld_school_id, fld_user_id,fld_profile_id,fld_schedule_id )
														VALUES ('".$classid."', '".$expid."', '".$rubid."', '".$uid."', '".$date."','".$sendistid."','".$schoolid."','".$indid."','".$sessmasterprfid."','".$scheduleid."')");
			}
			else
			{
			   $ObjDB->NonQuery("UPDATE itc_exp_rubric_rpt SET fld_class_id='".$classid."', fld_exp_id='".$expid."', fld_updated_by='".$uid."', 
															 fld_updated_date='".$date."' WHERE fld_rubric_nameid='".$rubid."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_delstatus = '0' ");
			   $maxid=$cnt;
			}

		   /*rubric stmt*/


			$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_rubric_rpt_statement 
													WHERE fld_dest_id='".$destid."' AND fld_rubric_id='".$ruborderid."' AND fld_rubric_rpt_id='".$maxid."'
													AND fld_exp_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid."'");
			if($cnt==0)
			{
					$ObjDB->NonQuery("INSERT INTO itc_exp_rubric_rpt_statement (fld_rubric_rpt_id, fld_dest_id, fld_rubric_id, fld_score, fld_created_by, fld_created_date, fld_rubric_nameid, fld_exp_id, fld_student_id, fld_hightlight_cell) 
										 VALUES ('".$maxid."', '".$destid."', '".$ruborderid."', '".$score."', '".$uid."', '".$date."', '".$rubid."', '".$expid."', '".$stuid."', '".$cellid."')");
			}
			else
			{
				   $ObjDB->NonQuery("UPDATE itc_exp_rubric_rpt_statement 
										SET fld_score='".$score."', fld_delstatus='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."', fld_hightlight_cell = '".$cellid."' 
										WHERE fld_dest_id='".$destid."' AND fld_rubric_id='".$ruborderid."' AND fld_rubric_rpt_id='".$maxid."'
										AND fld_exp_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid."'");
			}
		}
		else if($typeid=='21')
		{
			//save class Name
			$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_expsch_rubric_rpt WHERE fld_exp_id='".$expid."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' 
														AND fld_rubric_nameid='".$rubid."' AND fld_created_by='".$uid."' AND fld_delstatus = '0'");
			if($cnt==0)
			{
			   $maxid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_expsch_rubric_rpt (fld_class_id, fld_exp_id, fld_rubric_nameid, fld_created_by, fld_created_date, fld_district_id, fld_school_id, fld_user_id,fld_profile_id,fld_schedule_id) 
														VALUES ('".$classid."', '".$expid."', '".$rubid."', '".$uid."', '".$date."','".$sendistid."','".$schoolid."','".$indid."','".$sessmasterprfid."','".$scheduleid."')");
			}
			else
			{
			   $ObjDB->NonQuery("UPDATE itc_expsch_rubric_rpt SET fld_class_id='".$classid."', fld_exp_id='".$expid."', fld_updated_by='".$uid."', 
															 fld_updated_date='".$date."' WHERE fld_rubric_nameid='".$rubid."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_delstatus = '0' ");
			   $maxid=$cnt;
			}

		   /*rubric stmt*/


			$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_expsch_rubric_rpt_statement 
													WHERE fld_dest_id='".$destid."' AND fld_rubric_id='".$ruborderid."' AND fld_rubric_rpt_id='".$maxid."'
													AND fld_exp_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid."'");
			if($cnt==0)
			{
					$ObjDB->NonQuery("INSERT INTO itc_expsch_rubric_rpt_statement (fld_rubric_rpt_id, fld_dest_id, fld_rubric_id, fld_score, fld_created_by, fld_created_date, fld_rubric_nameid, fld_exp_id, fld_student_id, fld_hightlight_cell) 
																 VALUES ('".$maxid."', '".$destid."', '".$ruborderid."', '".$score."', '".$uid."', '".$date."', '".$rubid."', '".$expid."', '".$stuid."', '".$cellid."')");
			}
			else
			{
				   $ObjDB->NonQuery("UPDATE itc_expsch_rubric_rpt_statement 
										SET fld_score='".$score."', fld_delstatus='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."', fld_hightlight_cell = '".$cellid."' 
										WHERE fld_dest_id='".$destid."' AND fld_rubric_id='".$ruborderid."' AND fld_rubric_rpt_id='".$maxid."'
										AND fld_exp_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid."'");
			}
		}
		else if($typeid=='24')
		{
			//save class Name
			$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_missch_rubric_rpt WHERE fld_mis_id='".$expid."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' 
																									AND fld_rubric_nameid='".$rubid."' AND fld_created_by='".$uid."' AND fld_delstatus = '0'");
			if($cnt==0)
			{
			   $maxid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_missch_rubric_rpt (fld_class_id, fld_mis_id, fld_rubric_nameid, fld_created_by, fld_created_date, fld_district_id, fld_school_id, fld_user_id,fld_profile_id,fld_schedule_id) 
																									VALUES ('".$classid."', '".$expid."', '".$rubid."', '".$uid."', '".$date."','".$sendistid."','".$schoolid."','".$indid."','".$sessmasterprfid."','".$scheduleid."')");
			}
			else
			{
			   $ObjDB->NonQuery("UPDATE itc_missch_rubric_rpt SET fld_class_id='".$classid."', fld_mis_id='".$expid."', fld_updated_by='".$uid."', 
																											 fld_updated_date='".$date."' WHERE fld_rubric_nameid='".$rubid."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_delstatus = '0' ");
			   $maxid=$cnt;
			}

   			/*rubric stmt*/
			$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_missch_rubric_rpt_statement 
													WHERE fld_dest_id='".$destid."' AND fld_rubric_id='".$ruborderid."' AND fld_rubric_rpt_id='".$maxid."'
													AND fld_mis_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid."'");
			if($cnt==0)
			{
					$ObjDB->NonQuery("INSERT INTO itc_missch_rubric_rpt_statement(fld_rubric_rpt_id, fld_dest_id, fld_rubric_id, fld_score, fld_created_by, fld_created_date, fld_rubric_nameid, fld_mis_id, fld_student_id, fld_hightlight_cell) 
																 VALUES ('".$maxid."', '".$destid."', '".$ruborderid."', '".$score."', '".$uid."', '".$date."', '".$rubid."', '".$expid."', '".$stuid."', '".$cellid."')");
			}
			else
			{
				   $ObjDB->NonQuery("UPDATE itc_missch_rubric_rpt_statement
										SET fld_score='".$score."', fld_delstatus='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' , fld_hightlight_cell = '".$cellid."'  
										WHERE fld_dest_id='".$destid."' AND fld_rubric_id='".$ruborderid."' AND fld_rubric_rpt_id='".$maxid."'
										AND fld_mis_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid."'");
			}
			
		}
		else if($typeid=='25')
		{
			//save class Name
			$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_expmodsch_rubric_rpt WHERE fld_exp_id='".$expid."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' 
														AND fld_rubric_nameid='".$rubid."' AND fld_created_by='".$uid."' AND fld_delstatus = '0'");
			if($cnt==0)
			{
			   $maxid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_expmodsch_rubric_rpt (fld_class_id, fld_exp_id, fld_rubric_nameid, fld_created_by, fld_created_date, fld_district_id, fld_school_id, fld_user_id,fld_profile_id,fld_schedule_id) 
														VALUES ('".$classid."', '".$expid."', '".$rubid."', '".$uid."', '".$date."','".$sendistid."','".$schoolid."','".$indid."','".$sessmasterprfid."','".$scheduleid."')");
			}
			else
			{
			   $ObjDB->NonQuery("UPDATE itc_expmodsch_rubric_rpt SET fld_class_id='".$classid."', fld_exp_id='".$expid."', fld_updated_by='".$uid."', 
															 fld_updated_date='".$date."' WHERE fld_rubric_nameid='".$rubid."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_delstatus = '0' ");
			   $maxid=$cnt;
			}

		   /*rubric stmt*/


			$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_expmodsch_rubric_rpt_statement 
													WHERE fld_dest_id='".$destid."' AND fld_rubric_id='".$ruborderid."' AND fld_rubric_rpt_id='".$maxid."'
													AND fld_exp_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid."'");
			if($cnt==0)
			{
					$ObjDB->NonQuery("INSERT INTO itc_expmodsch_rubric_rpt_statement (fld_rubric_rpt_id, fld_dest_id, fld_rubric_id, fld_score, fld_created_by, fld_created_date, fld_rubric_nameid, fld_exp_id, fld_student_id, fld_hightlight_cell) 
							 	VALUES ('".$maxid."', '".$destid."', '".$ruborderid."', '".$score."', '".$uid."', '".$date."', '".$rubid."', '".$expid."', '".$stuid."', '".$cellid."')");
			}
			else
			{
				   $ObjDB->NonQuery("UPDATE itc_expmodsch_rubric_rpt_statement 
										SET fld_score='".$score."', fld_delstatus='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."', fld_hightlight_cell = '".$cellid."' 
										WHERE fld_dest_id='".$destid."' AND fld_rubric_id='".$ruborderid."' AND fld_rubric_rpt_id='".$maxid."'
										AND fld_exp_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid."'");
			}
		}
		else
		{
			//save class Name
			$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_rubric_rpt WHERE fld_mis_id='".$expid."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' 
																									AND fld_rubric_nameid='".$rubid."' AND fld_created_by='".$uid."' AND fld_delstatus = '0'");
			if($cnt==0)
			{
			   $maxid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_mis_rubric_rpt (fld_class_id, fld_mis_id, fld_rubric_nameid, fld_created_by, fld_created_date, fld_district_id, fld_school_id, fld_user_id,fld_profile_id,fld_schedule_id) 
																									VALUES ('".$classid."', '".$expid."', '".$rubid."', '".$uid."', '".$date."','".$sendistid."','".$schoolid."','".$indid."','".$sessmasterprfid."','".$scheduleid."')");
			}
			else
			{
			   $ObjDB->NonQuery("UPDATE itc_mis_rubric_rpt SET fld_class_id='".$classid."', fld_mis_id='".$expid."', fld_updated_by='".$uid."', 
																											 fld_updated_date='".$date."' WHERE fld_rubric_nameid='".$rubid."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_delstatus = '0' ");
			   $maxid=$cnt;
			}

		   	/*rubric stmt*/
			$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_rubric_rpt_statement 
													WHERE fld_dest_id='".$destid."' AND fld_rubric_id='".$ruborderid."' AND fld_rubric_rpt_id='".$maxid."'
													AND fld_mis_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid."'");
			if($cnt==0)
			{
					$ObjDB->NonQuery("INSERT INTO itc_mis_rubric_rpt_statement(fld_rubric_rpt_id, fld_dest_id, fld_rubric_id, fld_score, fld_created_by, fld_created_date, fld_rubric_nameid, fld_mis_id, fld_student_id, fld_hightlight_cell) 
																 VALUES ('".$maxid."', '".$destid."', '".$ruborderid."', '".$score."', '".$uid."', '".$date."', '".$rubid."', '".$expid."', '".$stuid."', '".$cellid."')");
			}
			else
			{
				   $ObjDB->NonQuery("UPDATE itc_mis_rubric_rpt_statement
										SET fld_score='".$score."', fld_delstatus='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."', fld_hightlight_cell = '".$cellid."'  
										WHERE fld_dest_id='".$destid."' AND fld_rubric_id='".$ruborderid."' AND fld_rubric_rpt_id='".$maxid."'
										AND fld_mis_id='".$expid."' AND fld_rubric_nameid='".$rubid."' AND fld_student_id='".$stuid."'");
			}
		}
        echo "success";
    }
    catch(Exception $e)
    {
        echo "fail";
    }
}
/*--- Save and Update the  Rubric Code Start Here For EXPEDITION MISSION EXPEDITION SCHEDULE Developed by Mohan M****************/




@include("footer.php");