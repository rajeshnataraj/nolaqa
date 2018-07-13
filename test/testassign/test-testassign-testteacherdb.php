<?php 
/******Page Developed By Mohan M******/

@include("sessioncheck.php");

$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';

        
/*--- Save/Update student for test  ---*/
if($oper == "showselect" and $oper != '')
{	
    $schooldistid = isset($_REQUEST['schooldistid']) ? $_REQUEST['schooldistid'] : '0';
    $teacherid = isset($_REQUEST['teacherid']) ? $_REQUEST['teacherid'] : '0';

    $assessmentid=explode(",",$schooldistid);
    $oteacherassid=explode(",",$teacherid);
    if($assessmentid!='')
    {
        for($i=0;$i<sizeof($assessmentid);$i++)
        {
            
            $schoolordist = $ObjDB->SelectSingleValueInt("SELECT fld_school_id FROM itc_test_master WHERE fld_id='".$assessmentid[$i]."' AND fld_delstatus='0'");
            if($schoolordist==0)
            {
                $schooladmin='1';
            }
            else
            {
                 $schooladmin='2';
            }

            $qryfortest= $ObjDB->QueryObject("SELECT fld_test_name AS testname, fld_test_des AS testdes,fld_ass_type as asstype, fld_expt as assexp, fld_mist as assmis,
                                                fld_destid as destid,fld_taskid as taskid,fld_resid as resid,fld_prepostid as prepostid,fld_time_limit AS timelimit,fld_score AS score,
                                                fld_max_attempts AS attempts,fld_step_id AS stepid, fld_flag AS flaag,fld_total_question AS totquest,fld_school_id AS schoolid,
                                                fld_question_type AS questtype, fld_nextflag AS nxtflag,fld_grade AS fgrade FROM itc_test_master 
                                                WHERE fld_id='".$assessmentid[$i]."' AND fld_delstatus='0'");
            if($qryfortest->num_rows > 0)
            {
                while($rowsqryfortest = $qryfortest->fetch_assoc())
                {
                    extract($rowsqryfortest);

                    $testid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_test_master(fld_test_name, fld_test_des,fld_ass_type, fld_expt,fld_mist,fld_destid, fld_taskid,fld_resid,fld_prepostid,
                                                                fld_time_limit, fld_score, fld_max_attempts, fld_step_id, fld_flag, fld_total_question, fld_school_id, fld_question_type, fld_nextflag, fld_grade,
                                                                fld_created_by, fld_created_date,fld_otherteach_profile_id)
                                                        VALUES('".$testname."','".$testdes."','".$asstype."','".$assexp."','".$assmis."','".$destid."','".$taskid."',
                                                                '".$resid."','".$prepostid."','".$timelimit."','".$score."','".$attempts."','".$stepid."','".$flaag."','".$totquest."','".$schoolid."',
                                                                '".$questtype."','".$nxtflag."','".$fgrade."','".$uid."','".$date."','".$schooladmin."')");

                    /**************Grade Options Code Start here**************/
                        $qryfortesgrade= $ObjDB->QueryObject("select fld_grade AS grade,fld_lower_bound AS lb, fld_upper_bound AS ub, fld_boxid AS bid, fld_roundflag AS roundflag 
                                                                        from itc_test_grading_scale_mapping where fld_test_id='".$assessmentid[$i]."' AND fld_flag='1'");
                        if($qryfortesgrade->num_rows > 0)
                        {
                            while($rowsqryfortestgrade = $qryfortesgrade->fetch_assoc())
                            {
                                extract($rowsqryfortestgrade);

                                $ObjDB->NonQuery("INSERT INTO itc_test_grading_scale_mapping(fld_test_id, fld_boxid, fld_upper_bound, fld_lower_bound, 
                                                           fld_grade, fld_roundflag,fld_flag)
                                                           VALUES('".$testid."','".$bid."','".$ub."','".$lb."','".$grade."','".$roundflag."','1')");

                            }

                        }
                    /**************Grade Options Code End here**************/

                    /***************Test Question Assign Code Start here*************/
                        $qryfortesgrade= $ObjDB->QueryObject("SELECT fld_question_id AS qusid,fld_section_id AS fsectionid, fld_tag_id AS tagid,fld_order_by AS orderby,fld_rflag as flag,fld_order_bytags AS orderbytag
                                                                    FROM itc_test_questionassign WHERE fld_test_id='".$assessmentid[$i]."' AND fld_delstatus='0'");
                        if($qryfortesgrade->num_rows > 0)
                        {
                            while($rowsqryfortestgrade = $qryfortesgrade->fetch_assoc())
                            {
                                extract($rowsqryfortestgrade);

                                $ObjDB->NonQuery("INSERT INTO itc_test_questionassign(fld_test_id, fld_question_id,fld_section_id,fld_tag_id, fld_order_by,fld_order_bytags,fld_rflag, fld_created_by,fld_created_date)
                                                        VALUES('".$testid."','".$qusid."','".$fsectionid."','".$tagid."','".$orderby."','".$orderbytag."','".$flag."','".$uid."','".$date."')");

                            }
                        }
                    /***************Test Question Assign Code End here*************/


                    /****************Random Question Assign Code Start here************/
                        $randomcnt = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$assessmentid[$i]."' AND fld_delstatus='0'");

                        if($randomcnt!='0')
                        {
                            $qryfortesgrade= $ObjDB->QueryObject("SELECT fld_tag_id AS tagids,fld_avl_questions AS qncounts,fld_qn_assign AS qnassign, 
                                                                        fld_pct_section AS pctsection,fld_order_by AS orderby
                                                                        FROM itc_test_random_questionassign WHERE fld_rtest_id='".$assessmentid[$i]."' AND fld_delstatus='0'");
                            if($qryfortesgrade->num_rows > 0)
                            {
                                while($rowsqryfortestgrade = $qryfortesgrade->fetch_assoc())
                                {
                                    extract($rowsqryfortestgrade);

                                    $ObjDB->NonQuery("INSERT INTO itc_test_random_questionassign (fld_rtest_id, fld_tag_id, fld_avl_questions,
                                                        fld_qn_assign,fld_pct_section,fld_order_by,fld_created_by, fld_created_date)
                                                        VALUES ('".$testid."','".$tagids."','".$qncounts."','".$qnassign."','".$pctsection."','".$orderby."',
                                                        '".$uid."','".$date."')");

                                }
                            }
                        }
                    /****************Random Question Assign Code Start here************/
                        
                    /**** Inline Exp for Mapping for students and class code start here*****/
                        if($asstype == 1)
                        {  
                            $ObjDB->NonQuery("UPDATE itc_test_inline_student_mapping 
							 SET fld_flag='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
							 WHERE fld_test_id='".$testid."'");  
		

                            $scheduleqry = $ObjDB->QueryObject("SELECT fld_class_id AS clsid, fld_id AS scheduleid FROM itc_class_indasexpedition_master
                                                                    WHERE fld_exp_id='".$assexp."' AND fld_createdby='".$uid."' AND fld_delstatus='0' AND fld_flag='1'");
                            if($scheduleqry->num_rows>0)
                            {
                               while($rowsch = $scheduleqry->fetch_assoc())
                               {
                                    extract($rowsch);

                                    $stuqry = $ObjDB->QueryObject("SELECT fld_student_id As studentids FROM itc_class_exp_student_mapping WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1'");
                                    if($stuqry->num_rows>0)
                                    {
                                        while($rowstu = $stuqry->fetch_assoc())
                                        {
                                            extract($rowstu);
                                            $possiblepointfortest = $ObjDB->SelectSingleValueInt("SELECT a.fld_pointspossible AS possiblepoint 
                                                                                            FROM itc_class_exp_grade AS a
                                                                                            WHERE a.fld_exp_id='".$assexp."'
                                                                                                    AND a.fld_class_id='".$clsid."'
                                                                                                    AND a.fld_flag='1' AND a.fld_exptype='3'
                                                                                            ORDER BY a.fld_exptype");


                                            $count=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_test_inline_student_mapping WHERE fld_test_id='".$testid."' AND fld_exp_id='".$assexp."' AND fld_class_id='".$clsid."' AND fld_schedule_id='".$scheduleid."' AND fld_student_id='".$studentids."'  AND fld_created_by='".$uid."'");

                                            if($count == 0){	

                                                $ObjDB->NonQuery("INSERT INTO itc_test_inline_student_mapping(fld_test_id, fld_exp_id, fld_class_id, fld_schedule_id, 
                                                                                                         fld_student_id, fld_points_possible, fld_flag,fld_created_by, 
                                                                                                                fld_created_date)
                                                                                VALUES('".$testid."','".$assexp."','".$clsid."','".$scheduleid."','".$studentids."','".$possiblepointfortest."','1','".$uid."','".date('Y-m-d H:i:s')."')");

                                            }
                                            else{
                                                $ObjDB->NonQuery("UPDATE itc_test_inline_student_mapping SET fld_flag='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."'
                                                                                WHERE fld_test_id='".$testid."' and fld_id='".$count."'");
                                            }

                                        }
                                    }
                               }
                            }
                        }
                        /**** Inline Exp for Mapping for students and class code End here*****/
                        
                        /*********Mission report Code Start Here Developed By Mohan M 16-7-2015********/
                        if($asstype == 2)
                        {    
                              $ObjDB->NonQuery("UPDATE itc_test_mission_inline_student_mapping 
                                                         SET fld_flag='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
                                                         WHERE fld_test_id='".$testid."'");  
                   

                            $misscheduleqry = $ObjDB->QueryObject("SELECT fld_class_id AS clsid, fld_id AS scheduleid FROM itc_class_indasmission_master
                                                                    WHERE fld_mis_id='".$assmis."' AND fld_createdby='".$uid."' AND fld_delstatus='0' AND fld_flag='1'");
                            if($misscheduleqry->num_rows>0)
                            {
                               while($rowmissch = $misscheduleqry->fetch_assoc())
                               {
                                    extract($rowmissch);

                                    $stuqrymis = $ObjDB->QueryObject("SELECT fld_student_id As studentids FROM itc_class_mission_student_mapping WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1'");
                                    if($stuqrymis->num_rows>0)
                                    {
                                        while($rowstu = $stuqrymis->fetch_assoc())
                                        {
                                            extract($rowstu);
                                            $possiblepointfortest = $ObjDB->SelectSingleValueInt("SELECT a.fld_pointspossible AS possiblepoint 
                                                                                            FROM itc_class_mission_grade AS a
                                                                                            WHERE a.fld_mis_id='".$assmis."'
                                                                                                    AND a.fld_class_id='".$clsid."'
                                                                                                    AND a.fld_flag='1' AND a.fld_mistype='3'
                                                                                            ORDER BY a.fld_mistype");

                                            $count=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_test_mission_inline_student_mapping WHERE fld_test_id='".$testid."' AND fld_mis_id='".$assmis."' AND fld_class_id='".$clsid."' AND fld_schedule_id='".$scheduleid."' AND fld_student_id='".$studentids."'  AND fld_created_by='".$uid."'");

                                            if($count == 0)
                                            {	
                                                $ObjDB->NonQuery("INSERT INTO itc_test_mission_inline_student_mapping(fld_test_id, fld_mis_id, fld_class_id, fld_schedule_id, 
                                                                                fld_student_id, fld_points_possible, fld_flag,fld_created_by, fld_created_date)
                                                                                VALUES('".$testid."','".$assmis."','".$clsid."','".$scheduleid."','".$studentids."','".$possiblepointfortest."','1','".$uid."','".date('Y-m-d H:i:s')."')");

                                            }
                                            else
                                            {
                                                $ObjDB->NonQuery("UPDATE itc_test_mission_inline_student_mapping SET fld_flag='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."'
                                                                                WHERE fld_test_id='".$testid."' and fld_id='".$count."'");
                                            }

                                        }
                                    }
                               }
                            }
                        }
                        /*********Mission report Code End Here Developed By Mohan M 16-7-2015*************/
                        
                        
                        
                }
            }
        } //for loop end here
    }
    /*****Other Teacher Assessment Code Start Here****/
    if($oteacherassid!='')
    {
        for($j=0;$j<sizeof($oteacherassid);$j++)
        {
            $schooladmin='3';

            $qryfortest= $ObjDB->QueryObject("SELECT fld_test_name AS testname, fld_test_des AS testdes,fld_ass_type as asstype, fld_expt as assexp, fld_mist as assmis,
                                                    fld_destid as destid,fld_taskid as taskid,fld_resid as resid,fld_prepostid as prepostid,fld_time_limit AS timelimit,fld_score AS score,
                                                    fld_max_attempts AS attempts,fld_step_id AS stepid, fld_flag AS flaag,fld_total_question AS totquest,fld_school_id AS schoolid,
                                                    fld_question_type AS questtype, fld_nextflag AS nxtflag,fld_grade AS fgrade FROM itc_test_master 
                                                    WHERE fld_id='".$oteacherassid[$j]."' AND fld_delstatus='0'");
            if($qryfortest->num_rows > 0)
            {
                while($rowsqryfortest = $qryfortest->fetch_assoc())
                {
                    extract($rowsqryfortest);

                    $testid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_test_master(fld_test_name, fld_test_des,fld_ass_type, fld_expt,fld_mist,fld_destid, fld_taskid,fld_resid,fld_prepostid,
                                                                fld_time_limit, fld_score, fld_max_attempts, fld_step_id, fld_flag, fld_total_question, fld_school_id, fld_question_type, fld_nextflag, fld_grade,
                                                                fld_created_by, fld_created_date,fld_otherteach_profile_id)
                                                        VALUES('".$testname."','".$testdes."','".$asstype."','".$assexp."','".$assmis."','".$destid."','".$taskid."',
                                                                '".$resid."','".$prepostid."','".$timelimit."','".$score."','".$attempts."','".$stepid."','".$flaag."','".$totquest."','".$schoolid."',
                                                                '".$questtype."','".$nxtflag."','".$fgrade."','".$uid."','".$date."','".$schooladmin."')");

                    /**************Grade Options Code Start here**************/
                    $qryfortesgrade= $ObjDB->QueryObject("select fld_grade AS grade,fld_lower_bound AS lb, fld_upper_bound AS ub, fld_boxid AS bid, fld_roundflag AS roundflag 
                                                                    from itc_test_grading_scale_mapping where fld_test_id='".$oteacherassid[$j]."' AND fld_flag='1'");
                    if($qryfortesgrade->num_rows > 0)
                    {
                        while($rowsqryfortestgrade = $qryfortesgrade->fetch_assoc())
                        {
                            extract($rowsqryfortestgrade);
                            
                            $ObjDB->NonQuery("INSERT INTO itc_test_grading_scale_mapping(fld_test_id, fld_boxid, fld_upper_bound, fld_lower_bound, 
                                                       fld_grade, fld_roundflag,fld_flag)
                                                       VALUES('".$testid."','".$bid."','".$ub."','".$lb."','".$grade."','".$roundflag."','1')");

                        }

                    }
                    /**************Grade Options Code End here**************/

                    /***************Test Question Assign Code Start here*************/
                    $qryfortesgrade= $ObjDB->QueryObject("SELECT fld_question_id AS qusid,fld_section_id AS fsectionid, fld_tag_id AS tagid,fld_order_by AS orderby,fld_rflag as flag,fld_order_bytags AS orderbytag
                                                                FROM itc_test_questionassign WHERE fld_test_id='".$oteacherassid[$j]."' AND fld_delstatus='0'");
                    if($qryfortesgrade->num_rows > 0)
                    {
                        while($rowsqryfortestgrade = $qryfortesgrade->fetch_assoc())
                        {
                            extract($rowsqryfortestgrade);
                            
                            $ObjDB->NonQuery("INSERT INTO itc_test_questionassign(fld_test_id, fld_question_id,fld_section_id,fld_tag_id, fld_order_by,fld_order_bytags,fld_rflag, fld_created_by,fld_created_date)
                                                    VALUES('".$testid."','".$qusid."','".$fsectionid."','".$tagid."','".$orderby."','".$orderbytag."','".$flag."','".$uid."','".$date."')");

                        }
                    }
                    /***************Test Question Assign Code End here*************/


                    /****************Random Question Assign Code Start here************/
                    $randomcnt = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$oteacherassid[$j]."' AND fld_delstatus='0'");

                    if($randomcnt!='0')
                    {
                        $qryfortesgrade= $ObjDB->QueryObject("SELECT fld_tag_id AS tagids,fld_avl_questions AS qncounts,fld_qn_assign AS qnassign, 
                                                                    fld_pct_section AS pctsection,fld_order_by AS orderby
                                                                    FROM itc_test_random_questionassign WHERE fld_rtest_id='".$oteacherassid[$j]."' AND fld_delstatus='0'");
                        if($qryfortesgrade->num_rows > 0)
                        {
                            while($rowsqryfortestgrade = $qryfortesgrade->fetch_assoc())
                            {
                                extract($rowsqryfortestgrade);
                                
                                $ObjDB->NonQuery("INSERT INTO itc_test_random_questionassign (fld_rtest_id, fld_tag_id, fld_avl_questions,
                                                        fld_qn_assign,fld_pct_section,fld_order_by,fld_created_by, fld_created_date)
                                                        VALUES ('".$testid."','".$tagids."','".$qncounts."','".$qnassign."','".$pctsection."','".$orderby."',
                                                        '".$uid."','".$date."')");

                            }
                        }
                    }
                    /****************Random Question Assign Code Start here************/
                }
            }
        } //for loop end here
    }//other teacher test
    
        echo "success";
}
