<?php
error_reporting(0);
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '0';
$id = explode(",",$id);
//Please remove the option of a Math Connection from these three units 1.Orientation,2.Calculators I,3.Graphing Calculators
$cal=1;
$graphcal=20;
$orient=42;
?>
<section data-type='2home' id='reports-gradebook-edit'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Student Name: <?php echo $studentname = $ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,' ',fld_lname) 
                                                                                                                FROM itc_user_master 
                                                                                                                WHERE fld_id='".$id[2]."' 
                                                                                                                ORDER BY fld_lname"); ?></p>
            </div>
        </div>
        
        <div class='row rowspacer'>
            <table class='table table-hover table-striped table-bordered'>
                <thead class='tableHeadText'>
                    <tr>
                    <?php
                    $unitname = $ObjDB->SelectSingleValue("SELECT fld_unit_name 
                                                                        FROM itc_unit_master 
                                                                        WHERE fld_id='".$id[3]."'");
                    ?>
                    <th style="cursor:default;" width="250px" style="font-size:18px"><?php if($id[0]==0) { echo $unitname; } 
                        else if($id[0]==10) { echo "Activity"; } 
                        else if($id[0]==9) { echo "Assessment"; } 
                        else if($id[0]==8) { echo $ObjDB->SelectSingleValue("SELECT fld_contentname 
                                                                                    FROM itc_customcontent_master 
                                                                                    WHERE fld_id='".$id[3]."'"); } 

                        else if($id[0]==15 OR $id[0]==19) { echo $ObjDB->SelectSingleValue("SELECT fld_exp_name 
                                                                                                    FROM itc_exp_master 
                                                                                                    WHERE fld_id='".$id[3]."'"); }

                        else if($id[0]==18) { echo $ObjDB->SelectSingleValue("SELECT fld_mis_name 
                                                                                        FROM itc_mission_master 
                                                                                        WHERE fld_id='".$id[3]."'"); }                                                                                        

                        else if($id[0]==16) { echo $ObjDB->SelectSingleValue("SELECT fld_rub_name AS fullnam FROM itc_exp_rubric_name_master
                                                                                    WHERE fld_exp_id='".$id[3]."' AND fld_id='".$id[4]."' AND fld_delstatus='0'"); } //rubric

                        else if($id[0]==4 || $id[0]==6) { echo $ObjDB->SelectSingleValue("SELECT fld_mathmodule_name 
                                                                                                        FROM itc_mathmodule_master 
                                                                                                        WHERE fld_id='".$id[3]."'"); } 

                        else if($id[0]==17) { echo $ObjDB->SelectSingleValue("SELECT fld_contentname 
                                                                                            FROM itc_customcontent_master 
                                                                                            WHERE fld_id='".$id[3]."'"); }
                        /**************************Expedition and Module schedule Code start here by Mohan**********************/
                        else if($id[0]==20)
                        {
                                echo $ObjDB->SelectSingleValue("SELECT fld_exp_name FROM itc_exp_master WHERE fld_id='".$id[3]."'");
                        }
                        else if($id[0]==21)
                        {
                                if($id[5]==1)
                                {
                                        echo $ObjDB->SelectSingleValue("SELECT fld_module_name FROM itc_module_master WHERE fld_id='".$id[3]."'"); 
                                }
                                else
                                {
                                        echo $ObjDB->SelectSingleValue("SELECT fld_contentname FROM itc_customcontent_master WHERE fld_id='".$id[3]."'");
                                }
                        }
                        /**************************Expedition and Module schedule Code end here by Mohan**********************/

                        else if($id[0]==23) { echo $ObjDB->SelectSingleValue("SELECT fld_mis_name 
                                                                                FROM itc_mission_master 
                                                                                WHERE fld_id='".$id[3]."'"); }                                                                                        


                        else { echo $ObjDB->SelectSingleValue("SELECT fld_module_name 
                                                                        FROM itc_module_master 
                                                                        WHERE fld_id='".$id[3]."'"); }?>
                    </th>
                    <th style="cursor:default;">Points Earned</th>
                    <th style="cursor:default;">Points Possible</th>
                    <?php 
                    if($id[0]==1 OR $id[0]==2 OR $id[0]==3 OR $id[0]==4 OR $id[0]==6 OR $id[0]==9 OR $id[0]==15 OR $id[0]==19 OR $id[0]==20 OR $id[0]==18 OR $id[0]==21 OR $id[0]==23)
                    {   ?>
                        <th>Correct / Total</th>
                        <?php 
                    }   ?>
                </tr>
            </thead>
            <?php
            if($id[0]==1 || $id[0]==2 || $id[0]==3 || $id[0]==4 || $id[0]==6 || $id[0]==7 ) 
            {   ?>
                </table>
                <div class="scroll">
                <table class='table table-hover table-striped table-bordered'>
                <?php
            }
            ?>
            <tbody>
            <?php 
if($id[0]==0) // IPL
{
    $totcount=0;
    $totpointsearn=0;
    $totpointspossi=0;
    
    $qry = $ObjDB->QueryObject("SELECT a.fld_lesson_id, b.fld_ipl_name, b.fld_ipl_points as fld_points_possible, 
                                                    0 as fld_points_earned 
                                            FROM `itc_class_sigmath_lesson_mapping` AS a 
                                            LEFT JOIN itc_ipl_master AS b ON b.fld_id=a.fld_lesson_id 
                                            WHERE a.fld_sigmath_id='".$id[4]."' AND a.fld_flag='1' AND b.fld_unit_id='".$id[3]."' 
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
                                            WHERE b.fld_class_id='".$id[1]."' AND b.fld_student_id='".$id[2]."' 
                                                    AND a.fld_id='".$fld_lesson_id."' AND b.fld_schedule_id='".$id[4]."' 
                                                    AND (b.fld_status='1' OR b.fld_status='2')");

            $earnedpoints='';
            if($qrypoints->num_rows>0)
            {
                $rowqrypoints = $qrypoints->fetch_assoc();
                extract($rowqrypoints);
            }
            $gradepoint = $ObjDB->QueryObject("SELECT fld_points AS points,fld_grade 
                                                FROM itc_class_sigmath_grade 
                                                WHERE fld_schedule_id='".$id[4]."' AND fld_lesson_id='".$fld_lesson_id."' AND fld_flag='1'");
            if($gradepoint->num_rows>0){
                extract($gradepoint->fetch_assoc());							
            }
            else
            {
                $fld_grade = '1';
                $points=$ObjDB->SelectSingleValueInt("SELECT fld_ipl_points FROM itc_ipl_master WHERE fld_id='".$fld_lesson_id."'");	
            }
                //new line for ipl start
            $checkflag1 = $ObjDB->SelectSingleValueInt("SELECT  fld_flag 
                                FROM itc_class_sigmath_grade 
                                WHERE fld_class_id='".$id[1]."' AND fld_schedule_id='".$id[4]."' 
                                       AND fld_lesson_id='".$fld_lesson_id."' AND fld_flag = '1'");  
             if($checkflag1 !='' )
             {
                $totcount=$totcount+1;
                $totpointsearn=$totpointsearn+$earnedpoints;
                $totpointspossi=$totpointspossi+$points;
                 
                ?>
                <tr>
                    <td style="cursor:default;"><?php echo $fld_ipl_name;?></td>
                    <td style="cursor:default;">
                        <input type="text" name="earned_<?php echo $fld_lesson_id;?>" id="earned_<?php echo $fld_lesson_id;?>" value="<?php echo $earnedpoints;?>" onkeyup="ChkValidChar(this.id);" onchange="$('#teacher_'+<?php echo $fld_lesson_id; ?>).val(1);"/>
                    </td>
                    <td style="cursor:default;">
                        <input type="text" name="possible_<?php echo $fld_lesson_id;?>" id="possible_<?php echo $fld_lesson_id;?>" value="<?php echo $points;?>" readonly />
                    </td>
                </tr>
                <input type="hidden" name="teacher_<?php echo $fld_lesson_id;?>" id="teacher_<?php echo $fld_lesson_id;?>" value="" />
                <?php
             }//new line for ipl end
        }
    }
        
    $checkflag = $ObjDB->SelectSingleValueInt("SELECT  fld_flag 
                                                    FROM itc_class_sigmath_grademapping 
                                                    WHERE fld_class_id='".$id[1]."' AND fld_schedule_id='".$id[4]."' 
                                                           AND fld_unit_id='".$id[3]."' AND fld_flag = '1'");    
    if($checkflag !='' )
    {
        if($id[3]!=$cal && $id[3]!=$graphcal && $id[3]!=$orient  )////new line
        {   
            ?>
            <tr style="cursor:default">
                <td style="cursor:default;"><?php echo "Math Connection: ".$unitname;?></td>
                <td style="cursor:default;">
                    <?php 
                    $cgaearned = $ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned 
                                                                        FROM itc_assignment_sigmath_master 
                                                                        WHERE fld_class_id='".$id[1]."' AND fld_schedule_id='".$id[4]."' 
                                                                                AND fld_unit_id='".$id[3]."' AND fld_unitmark='1' 
                                                                                        AND fld_student_id='".$id[2]."' AND fld_delstatus='0'");

                    $cgapossible = $ObjDB->SelectSingleValueInt("SELECT  fld_mpoints 
                                                                                FROM itc_class_sigmath_grademapping 
                                                                                WHERE fld_class_id='".$id[1]."' AND fld_schedule_id='".$id[4]."' 
                                                                                        AND fld_unit_id='".$id[3]."' AND fld_flag = '1'"); 


                    ?> 
                    <input type="hidden" name="" id="checkflag" value="<?php echo $checkflag;?>" />
                    <!--                            change place-->
                    <input type="text" name="cgaearned" id="cgaearned" value="<?php if($cgaearned !='') { echo $cgaearned;} //else{echo "0";}?>" onkeyup="ChkValidChar(this.id);" />
                </td>
                <td style="cursor:default;">
                    <input type="text" name="cgapossible" id="cgapossible" value="<?php if($cgapossible !='') {echo $cgapossible;} else{echo "100";}?>" readonly />
                </td>
            </tr>
            <?php
            
            $totcount=$totcount+1;
            $totpointsearn=$totpointsearn+$cgaearned;
            $totpointspossi=$totpointspossi+$cgapossible;
        } //new line
    }

    $qryrubrics = $ObjDB->QueryObject("SELECT fld_id, fld_rubrics_name, fld_points_possible 
                                                                            FROM itc_rubrics_master 
                                                                            WHERE fld_class_id='".$id[1]."' AND fld_student_id='".$id[2]."' 
                                                                                    AND fld_schedule_id='".$id[4]."' AND fld_unit_id='".$id[3]."' 
                                                                                    AND fld_delstatus='0' AND fld_created_by='".$uid."'");
    if($qryrubrics->num_rows>0)
    {
        while($rowqryrubrics = $qryrubrics->fetch_assoc()) 
        {
            extract($rowqryrubrics);
            $rubricspointsearned = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned 
                                                                    FROM itc_assignment_sigmath_master 
                                                                    WHERE fld_rubrics_id='".$fld_id."' AND fld_delstatus='0' 
                                                                            AND fld_unitmark='0'");
            
            $totcount=$totcount+1;
            $totpointsearn=$totpointsearn+$rubricspointsearned;
            $totpointspossi=$totpointspossi+$fld_points_possible;
            ?>
            <tr>
                <td onclick="fn_addrubrics(<?php echo $fld_id; ?>,<?php echo $id[1]; ?>,<?php echo $id[2]; ?>,<?php echo $id[3]; ?>,<?php echo $id[4]; ?>);"><?php echo $fld_rubrics_name;?></td>
                <td>
                    <input type="text" name="rearned_<?php echo $fld_id;?>" id="rearned_<?php echo $fld_id;?>" value="<?php if($rubricspointsearned=='') { echo "0"; } else { echo $rubricspointsearned; }?>" onkeyup="ChkValidChar(this.id);" onchange="$('#rteacher_'+<?php echo $fld_id; ?>).val(1);"/>
                </td>
                <td>
                    <input type="text" name="rpossible_<?php echo $fld_id;?>" id="rpossible_<?php echo $fld_id;?>" value="<?php echo $fld_points_possible;?>" readonly />
                </td>
            </tr>
            <input type="hidden" name="rteacher_<?php echo $fld_id;?>" id="rteacher_<?php echo $fld_id;?>" value="" />
            <?php
        }
    }

    if($totcount>1)
    {
        ?>
        <tr>
            <td style="cursor:default;">
                <b>Total</b>
            </td>
            <td style="cursor:default;">
                <b><?php echo round($totpointsearn);?></b>
            </td>
            <td style="cursor:default;">
                <b><?php echo $totpointspossi;?></b>
            </td>
         </tr>    
        <?php
    }
} 

else if($id[0]==9)  // Regule Assessment code Start here
{
    $qry = $ObjDB->QueryObject("SELECT b.fld_id, b.fld_test_name, b.fld_score AS pointspossible, 
                                                b.fld_total_question AS totalques, b.fld_question_type AS testtype  
                                        FROM itc_test_student_mapping AS a 
                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id=b.fld_id 
                                        WHERE b.fld_delstatus='0' AND a.fld_test_id='".$id[3]."' 
                                                AND a.fld_student_id='".$id[2]."' AND a.fld_class_id='".$id[1]."' AND a.fld_flag='1' 
                                        GROUP BY b.fld_id");

    if($qry->num_rows>0)
    {
        while($rowqry = $qry->fetch_assoc())
        {
            extract($rowqry);

            $teacherpoint=='';

            $qcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                            FROM itc_test_student_answer_track 
                                                            WHERE fld_student_id='".$id[2]."' 
                                                                            AND fld_test_id='".$id[3]."' 
                                                                            AND fld_delstatus='0' AND fld_schedule_id='0' AND fld_schedule_type='0'");

            $teacherpoint = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned 
                                                                    FROM itc_test_student_mapping 
                                                                    WHERE fld_student_id='".$id[2]."' AND fld_test_id='".$id[3]."' 
                                                                                    AND fld_flag='1' AND fld_class_id='".$id[1]."'");

            if($teacherpoint=='')
            {
                //echo "Student points";
                if($testtype == '1')
                {
                    $correctcountstu="-";
                    $crctcntstu='-';
                    $qrycorrectcount = $ObjDB->QueryObject("SELECT fld_correct_answer AS crctcount FROM itc_test_student_answer_track 
                                                                    WHERE fld_student_id='".$id[2]."' AND fld_test_id='".$id[3]."' 
                                                                        AND fld_delstatus='0' AND fld_schedule_id='0' 
                                                                        AND fld_schedule_type='0'"); //AND fld_correct_answer='1' 
                    if($qrycorrectcount->num_rows>0)
                    {
                        while($rowqrycorrectcount = $qrycorrectcount->fetch_assoc())
                        {
                            extract($rowqrycorrectcount);
                            $correctcountstu=$correctcountstu+$crctcount;
                            $crctcntstu=$crctcntstu+$crctcount;
                        }
                    }
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
                         $pointsearned = round(($crctcntstu/$totalques)*$pointspossible,2);
                    }
                    
                    
                    if($correctcountstu>='0')
                    {
                        $stucorrectcount=$correctcountstu." / ".$totalques;
                        $totpercentage=round((($correctcountstu/$totalques)*100))." %";
                    }
                    else if($correctcountstu=='-')
                    {
                        $stucorrectcount='';
                        $totpercentage='';
                    }
                    
                }
                else if($testtype == '2')
                {
                   $qryrandomtest = $ObjDB->QueryObject("SELECT fld_id AS testtagid, fld_pct_section AS percent, fld_qn_assign AS totques
                                                            FROM itc_test_random_questionassign
                                                            WHERE fld_rtest_id='".$id[3]."' AND fld_delstatus='0' 
                                                            ORDER BY fld_order_by");
                    if($qryrandomtest->num_rows>0)
                    {
                        $correctcountstu="-";
                        $stutotqus='';
                        while($rowqryrandomtest = $qryrandomtest->fetch_assoc())
                        {
                            extract($rowqryrandomtest);

                            $perscore = ($percent / 100)*$pointspossible;
                            $stutotqus+=$totques;
                            $qrycorrectcount = $ObjDB->QueryObject("SELECT fld_correct_answer AS crctcount
                                                                            FROM itc_test_student_answer_track 
                                                                            WHERE fld_student_id='".$id[2]."' AND fld_test_id='".$id[3]."' AND fld_tag_id='".$testtagid."'
                                                                            AND fld_delstatus='0' AND fld_schedule_id='0' 
                                                                        AND fld_schedule_type='0'");//AND fld_correct_answer='1' 
                            if($qrycorrectcount->num_rows>0)
                            {
                                while($rowqrycorrectcount = $qrycorrectcount->fetch_assoc())
                                {
                                    extract($rowqrycorrectcount);
                                    $correctcountstu=$correctcountstu+$crctcount;
                                    $pointsearned = $pointsearned + round($crctcount*($perscore/$totques));
                                }
                            }
                        }
                    }
                    
                    if($correctcountstu>='0')
                    {
                        $stucorrectcount=$correctcountstu." / ".$stutotqus;
                        $totpercentage=round((($correctcountstu/$stutotqus)*100))." %";
                    }
                    else if($correctcountstu=='-')
                    {
                        $stucorrectcount='';
                        $totpercentage='';
                    }
                    
                }
            }

            if($qcount==0)
                $pointsearned = '';

            if($teacherpoint!='')
            {
               // echo "Teacher points";
                $pointsearned = $teacherpoint;
                
                if($testtype == '1')
                {
                    $correctcountstu="-";
                    $crctcntstu='-';
                    $qrycorrectcount = $ObjDB->QueryObject("SELECT fld_correct_answer AS crctcount FROM itc_test_student_answer_track 
                                                                    WHERE fld_student_id='".$id[2]."' AND fld_test_id='".$id[3]."' 
                                                                        AND fld_delstatus='0' AND fld_schedule_id='0' 
                                                                        AND fld_schedule_type='0'"); //AND fld_correct_answer='1' 
                    if($qrycorrectcount->num_rows>0)
                    {
                        while($rowqrycorrectcount = $qrycorrectcount->fetch_assoc())
                        {
                            extract($rowqrycorrectcount);
                            $correctcountstu=$correctcountstu+$crctcount;
                            $crctcntstu=$crctcntstu+$crctcount;
                        }
                    }
                    
                    if($correctcountstu>='0')
                    {
                        $stucorrectcount=$correctcountstu." / ".$totalques;
                        $totpercentage=round((($correctcountstu/$totalques)*100))." %";
                    }
                    else if($correctcountstu=='-')
                    {
                        $stucorrectcount='';
                        $totpercentage='';
                    }
                    
                }
                else if($testtype == '2')
                {
                    $qryrandomtest = $ObjDB->QueryObject("SELECT fld_id AS testtagid, fld_pct_section AS percent, fld_qn_assign AS totques
                                                            FROM itc_test_random_questionassign
                                                            WHERE fld_rtest_id='".$id[3]."' AND fld_delstatus='0' 
                                                            ORDER BY fld_order_by");
                    if($qryrandomtest->num_rows>0)
                    {
                        $correctcountstu="-";
                        $stutotqus='';
                        while($rowqryrandomtest = $qryrandomtest->fetch_assoc())
                        {
                            extract($rowqryrandomtest);

                            $perscore = ($percent / 100)*$pointspossible;
                            $stutotqus+=$totques;
                            $qrycorrectcount = $ObjDB->QueryObject("SELECT fld_correct_answer AS crctcount
                                                                            FROM itc_test_student_answer_track 
                                                                            WHERE fld_student_id='".$id[2]."' AND fld_test_id='".$id[3]."' AND fld_tag_id='".$testtagid."'
                                                                            AND fld_delstatus='0' AND fld_schedule_id='0' 
                                                                        AND fld_schedule_type='0'");//AND fld_correct_answer='1' 
                            if($qrycorrectcount->num_rows>0)
                            {
                                while($rowqrycorrectcount = $qrycorrectcount->fetch_assoc())
                                {
                                    extract($rowqrycorrectcount);
                                    $correctcountstu=$correctcountstu+$crctcount;
                                    $pointsearned = $pointsearned + round($crctcount*($perscore/$totques));
                                }
                            }
                        }
                    }
                    
                    if($correctcountstu>='0')
                    {
                        $stucorrectcount=$correctcountstu." / ".$stutotqus;
                        $totpercentage=round((($correctcountstu/$stutotqus)*100))." %";
                    }
                    else if($correctcountstu=='-')
                    {
                        $stucorrectcount='';
                        $totpercentage='';
                    }
                }
            }
            ?>
            <tr>
                <td style="cursor:default;"><?php echo $fld_test_name;?></td>
                <td style="cursor:default;">
                    <input type="text" name="testearned_<?php echo $fld_id;?>" id="testearned_<?php echo $fld_id;?>" value="<?php echo $pointsearned;?>" onkeyup="ChkValidChar(this.id);" onchange="$('#testteacher_'+<?php echo $fld_id; ?>).val(1);" maxlength="3"/>
                </td>
                <td style="cursor:default;">
                    <input type="text" name="testpossible_<?php echo $fld_id;?>" id="testpossible_<?php echo $fld_id;?>" value="<?php echo $pointspossible;?>" readonly />
                </td>
                <td style="cursor:default;">
                    <?php echo $stucorrectcount; ?>&nbsp;&nbsp;&nbsp;<?php echo $totpercentage; ?>
                </td>
            </tr>
            <input type="hidden" name="testteacher_<?php echo $fld_id;?>" id="testteacher_<?php echo $fld_id;?>" value="" />
            <?php
        }
    }
}
// Regule Assessment code End here

else if($id[0]==10) 
{
    $qry = $ObjDB->QueryObject("SELECT b.fld_id, b.fld_activity_name, b.fld_activity_points AS pointspossible, 
                                                                    a.fld_points_earned AS fld_points_earned 
                                                            FROM itc_activity_student_mapping AS a 
                                                            LEFT JOIN itc_activity_master AS b ON a.fld_activity_id=b.fld_id 
                                                            WHERE b.fld_delstatus='0' AND a.fld_activity_id='".$id[3]."' 
                                                                    AND a.fld_class_id='".$id[1]."' AND a.fld_created_by='".$uid."' 
                                                                    AND a.fld_flag='1' AND a.fld_student_id='".$id[2]."'  ");

    if($qry->num_rows>0)
    {
        $rowqry = $qry->fetch_assoc(); 
        extract($rowqry);

        ?>
        <tr style="cursor:default">
            <td><?php echo $fld_activity_name;?></td>
            <td>
                <input type="text" name="actearned_<?php echo $fld_id;?>" id="actearned_<?php echo $fld_id;?>" value="<?php echo $fld_points_earned;?>" onkeyup="ChkValidChar(this.id);" onchange="$('#actteacher_'+<?php echo $fld_id; ?>).val(1);" maxlength="3"/>
            </td>
            <td>
                <input type="text" name="actpossible_<?php echo $fld_id;?>" id="actpossible_<?php echo $fld_id;?>" value="<?php echo $pointspossible;?>" readonly />
            </td>
        </tr>
        <input type="hidden" name="actteacher_<?php echo $fld_id;?>" id="actteacher_<?php echo $fld_id;?>" value="" />
        <?php
    }
}

else if($id[0]==8) 
{
    $qry = $ObjDB->QueryObject("SELECT a.fld_id, a.fld_contentname AS customname, a.fld_pointspossible AS possiblepoint, 
                                                                    (SELECT fld_teacher_points_earned 
                                                                    FROM itc_module_points_master 
                                                                    WHERE fld_schedule_type='".$id[0]."' AND fld_student_id='".$id[2]."' 
                                                                            AND fld_module_id='".$id[3]."' AND fld_schedule_id='".$id[4]."' AND fld_delstatus='0') AS pointsearned 
                                                            FROM itc_customcontent_master AS a 
                                                            LEFT JOIN itc_class_rotation_schedulegriddet AS b ON a.fld_id=b.fld_module_id 
                                                            WHERE b.fld_module_id='".$id[3]."' AND a.fld_delstatus='0' 
                                                                    AND b.fld_class_id='".$id[1]."' AND b.fld_schedule_id='".$id[4]."' 
                                                                    AND b.fld_flag='1' AND b.fld_type='8' 
                                                            GROUP BY a.fld_id");

    if($qry->num_rows>0)
    {
        $rowqry = $qry->fetch_assoc(); 
        extract($rowqry);

        ?>
        <tr style="cursor:default">
            <td><?php echo $customname;?></td>
            <td>
                    <input type="text" name="contearned_<?php echo $fld_id;?>" id="contearned_<?php echo $fld_id;?>" value="<?php echo $pointsearned;?>" onkeyup="ChkValidChar(this.id);" onchange="$('#contteacher_'+<?php echo $fld_id; ?>).val(1);" maxlength="3"/>
            </td>
            <td>
                    <input type="text" name="contpossible_<?php echo $fld_id;?>" id="contpossible_<?php echo $fld_id;?>" value="<?php echo $possiblepoint;?>" readonly />
            </td>
        </tr>
        <input type="hidden" name="contteacher_<?php echo $fld_id;?>" id="contteacher_<?php echo $fld_id;?>" value="" />
        <?php
    }
}
else if($id[0]==17)
{

    $qry = $ObjDB->QueryObject("SELECT a.fld_id, a.fld_contentname AS customname, a.fld_pointspossible AS possiblepoint, 
                                        (SELECT fld_teacher_points_earned 
                                        FROM itc_module_points_master 
                                        WHERE fld_schedule_type='".$id[0]."' AND fld_student_id='".$id[2]."' 
                                                AND fld_module_id='".$id[3]."' AND fld_schedule_id='".$id[4]."') AS pointsearned 
                                FROM itc_customcontent_master AS a 
                                LEFT JOIN itc_class_indassesment_master AS b ON a.fld_id=b.fld_module_id 
                                WHERE b.fld_module_id='".$id[3]."' AND a.fld_delstatus='0' 
                                        AND b.fld_class_id='".$id[1]."' AND b.fld_id='".$id[4]."' 
                                        AND b.fld_flag='1' AND b.fld_moduletype='17' 
                                GROUP BY a.fld_id");

    if($qry->num_rows>0)
    {
        $rowqry = $qry->fetch_assoc(); 
        extract($rowqry);

        ?>
        <tr style="cursor:default">
            <td><?php echo $customname;?></td>
            <td>
                    <input type="text" name="contearned_<?php echo $fld_id;?>" id="contearned_<?php echo $fld_id;?>" value="<?php echo $pointsearned;?>" onkeyup="ChkValidChar(this.id);" onchange="$('#contteacher_'+<?php echo $fld_id; ?>).val(1);" maxlength="3"/>
            </td>
            <td>
                    <input type="text" name="contpossible_<?php echo $fld_id;?>" id="contpossible_<?php echo $fld_id;?>" value="<?php echo $possiblepoint;?>" readonly />
            </td>
        </tr>
        <input type="hidden" name="contteacher_<?php echo $fld_id;?>" id="contteacher_<?php echo $fld_id;?>" value="" />
        <?php
    }
}

/************Exp code start here*****************/                        
else if($id[0]==15)
{
    
    $totcount=0;
    $totpointsearn=0;
    $totpointspossi=0;
    
    $qry = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(b.fld_rub_name,' / Rubric'),1) AS nam, 
                                            CONCAT(b.fld_rub_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                   LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                   LEFT JOIN  itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                                   LEFT JOIN itc_class_indasexpedition_master AS d ON a.fld_schedule_id=d.fld_id 
                                                           WHERE a.fld_class_id='".$id[1]."' AND a.fld_schedule_id='".$id[4]."' AND a.fld_expmisid='".$id[3]."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  AND a.fld_schedule_type='15'
                                                                   AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                                   AND b.fld_school_id IN(0,".$schoolid.")");
    if($qry->num_rows>0)
    {
        while($rowqry = $qry->fetch_assoc()) // show the module based on number of copies
        {
            extract($rowqry);
            $destinationids = $ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_exp_rubric_dest_master WHERE fld_rubric_name_id='".$rubricids."' AND fld_exp_id='".$id[3]."'"); 

            $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master 
                                                            WHERE fld_exp_id='".$id[3]."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

            $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_rpt WHERE fld_exp_id='".$id[3]."'  
                                                            AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$id[1]."' AND fld_schedule_id='".$scheduleid."'
                                                            AND fld_delstatus='0' "); 
            $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_rpt_statement 
                                                                    WHERE fld_student_id='".$id[2]."' AND fld_exp_id='".$id[3]."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
                                                                    AND fld_rubric_rpt_id='".$rubricrptid."'"); 

            if($studentscore=='0')
            {
                $studentscore='';
            }
            $totcount=$totcount+1;
            $totpointsearn=$totpointsearn+$studentscore;
            $totpointspossi=$totpointspossi+$totscore;
            ?>
            <tr style="cursor:pointer;" title="Click here to Grade Rubric" onclick="fn_showrubricpoints(<?php echo "16,".$id[1].",".$id[2].",".$id[3].",".$rubricids.",".$scheduleid; ?>);">
                <td>
                    <?php echo $rubnam; ?>
                </td>
                <td>
                    <input type="text" name="expearned_<?php echo $rubricids."_".$scheduleid;?>" id="expearned_<?php echo $rubricids."_".$scheduleid;?>" value="<?php echo $studentscore;?>" onkeyup="ChkValidChar(this.id);" onchange="$('#expteacher_'+<?php echo $rubricids; ?>+'_'+<?php echo $scheduleid; ?>).val(1);" maxlength="3" readonly />
                </td>
                <td>
                    <input type="text" name="exppossible_<?php echo $rubricids."_".$scheduleid;?>" id="exppossible_<?php echo $rubricids."_".$scheduleid;?>" value="<?php echo $totscore;?>" readonly />
                </td>
                <td></td>
            </tr>
            <input type="hidden" name="expteacher_<?php echo $rubricids."_".$scheduleid;?>" id="expteacher_<?php echo $rubricids."_".$scheduleid;?>" value="" />
            <?php

        }
    }
    
    /************** Pre/Post test code start here ***************/
    $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                       WHERE fld_class_id='".$id[1]."' AND fld_sch_id='".$id[4]."' AND fld_schtype_id='".$id[0]."'");

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
                        $correctcountstu="-";
                        $crctcntstu='-';
                        $qrycorrectcount = $ObjDB->QueryObject("SELECT a.fld_correct_answer AS crctcount FROM itc_test_student_answer_track AS a
                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                   WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$id[2]."' AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$id[4]."' AND a.fld_schedule_type='".$id[0]."'
                                                                                   AND b.fld_delstatus = '0' AND a.fld_delstatus = '0'");
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
                                                                            WHERE fld_schedule_type='".$id[0]."' AND fld_student_id='".$id[2]."' 
                                                                                    AND fld_exp_id='".$expid."' AND fld_schedule_id='".$id[4]."' AND fld_exptype='3' AND fld_res_id='".$testid."' AND fld_delstatus = '0'");
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
                            $totpercentage=round((($correctcountstu/$quescount)*100))." %";
                        }
                        else if($correctcountstu=='-')
                        {
                            $stucorrectcount='';
                            $totpercentage='';
                        }
                        
                        $totcount=$totcount+1;
                        $totpointsearn=$totpointsearn+$pointsearned;
                        $totpointspossi=$totpointspossi+$possiblepoint;                      

                        ?>
                        <tr>
                            <td style="cursor:default;">
                                <?php echo $testname;?>
                            </td>
                            <td style="cursor:default;">
                                <input type="text" name="exptestearned_<?php echo $testid."_".$exptype;?>" id="exptestearned_<?php echo $testid."_".$exptype;?>" value="<?php echo $pointsearned;?>" onkeyup="ChkValidChar(this.id);"  onchange="$('#exptestteacher_'+<?php echo $testid; ?>+'_'+<?php echo $exptype; ?>).val(1);" maxlength="3"/>
                            </td>
                            <td style="cursor:default;">
                                <input type="text" name="exptestpossible_<?php echo $testid."_".$exptype;?>" id="exptestpossible_<?php echo $testid."_".$exptype;?>" value="<?php echo $possiblepoint;?>" readonly />
                            </td>
                            <td style="cursor:default;">
                                <?php echo $stucorrectcount; ?>&nbsp;&nbsp;&nbsp;<?php echo $totpercentage; ?>
                            </td>
                        </tr>
                        <input type="hidden" name="exptestteacher_<?php echo $testid."_".$exptype;?>" id="exptestteacher_<?php echo $testid."_".$exptype;?>" value="" />
                        <?php
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
                        $correctcountstu="-";
                        $crctcntstu='-';
                        $qrycorrectcount = $ObjDB->QueryObject("SELECT a.fld_correct_answer AS crctcount FROM itc_test_student_answer_track AS a
                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                               WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$id[2]."' AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$id[4]."' AND a.fld_schedule_type='".$id[0]."'
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
                                                                            WHERE fld_schedule_type='".$id[0]."' AND fld_student_id='".$id[2]."' 
                                                                                    AND fld_exp_id='".$expid."' AND fld_schedule_id='".$id[4]."' AND fld_exptype='3' AND fld_res_id='".$testid."' AND fld_delstatus = '0'");
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
                            $totpercentage=round((($correctcountstu/$quescount)*100))." %";
                        }
                        else if($correctcountstu=='-')
                        {
                            $stucorrectcount='';
                            $totpercentage='';
                        }
                        
                        $totcount=$totcount+1;
                        $totpointsearn=$totpointsearn+$pointsearned;
                        $totpointspossi=$totpointspossi+$possiblepoint; 
                        ?>
                        <tr>
                            <td style="cursor:default;">
                                <?php echo $testname;?>
                            </td>
                            <td style="cursor:default;">
                                <input type="text" name="exptestearned_<?php echo $testid."_".$exptype;?>" id="exptestearned_<?php echo $testid."_".$exptype;?>" value="<?php echo $pointsearned;?>" onkeyup="ChkValidChar(this.id);"  onchange="$('#exptestteacher_'+<?php echo $testid; ?>+'_'+<?php echo $exptype; ?>).val(1);" maxlength="3"/>
                            </td>
                            <td style="cursor:default;">
                                <input type="text" name="exptestpossible_<?php echo $testid."_".$exptype;?>" id="exptestpossible_<?php echo $testid."_".$exptype;?>" value="<?php echo $possiblepoint;?>" readonly />
                            </td>
                            <td style="cursor:default;">
                                <?php echo $stucorrectcount; ?>&nbsp;&nbsp;&nbsp;<?php echo $totpercentage; ?>
                            </td>
                        </tr>
                        <input type="hidden" name="exptestteacher_<?php echo $testid."_".$exptype;?>" id="exptestteacher_<?php echo $testid."_".$exptype;?>" value="" />
                        <?php
                    }
                }
                
            }// Post test end
            /*********Post Test Code End Here*********/
        }
    }
    /************** Pre/Post test code start here ***************/    
    if($totcount>1)
    {
        ?>
        <tr>
            <td style="cursor:default;">
               <b>Total</b>
            </td>
            <td style="cursor:default;">
                <b><?php echo round($totpointsearn);?></b>
            </td>
            <td style="cursor:default;">
                <b><?php echo $totpointspossi;?></b>
            </td>
            <td style="cursor:default;">
                &nbsp;&nbsp;&nbsp;
            </td>
         </tr>    
        <?php
    }
}
/************Exp code end here*****************/   

/************Expedition Schedule code start here*****************/   
else if($id[0]==19) //Expedition Schedule
{
    $totcount=0;
    $totpointsearn=0;
    $totpointspossi=0;
    
    $qry = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(b.fld_rub_name,' / Rubric'),1) AS nam, 
                                    CONCAT(b.fld_rub_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                    LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                    LEFT JOIN itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                    LEFT JOIN itc_class_rotation_expschedule_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
                                        WHERE a.fld_class_id='".$id[1]."' AND a.fld_schedule_id='".$id[4]."' AND b.fld_exp_id='".$id[3]."'  AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  AND a.fld_schedule_type='17'
                                            AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                            AND b.fld_school_id IN(0,".$schoolid.")");

    if($qry->num_rows>0)
    {
        while($rowqry = $qry->fetch_assoc()) // show the module based on number of copies
        {
            extract($rowqry);

            $destinationids = $ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_exp_rubric_dest_master WHERE fld_rubric_name_id='".$rubricids."' AND fld_exp_id='".$id[3]."'"); 

            $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master 
                                                            WHERE fld_exp_id='".$id[3]."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

            $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_expsch_rubric_rpt WHERE fld_exp_id='".$id[3]."'  
                                                                AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$id[1]."' AND fld_schedule_id='".$scheduleid."'
                                                                AND fld_delstatus='0' "); 

            $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_expsch_rubric_rpt_statement
                                                            WHERE fld_student_id='".$id[2]."' AND fld_exp_id='".$id[3]."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
                                                            AND fld_rubric_rpt_id='".$rubricrptid."'"); 

            if($studentscore=='0')
            {
                $studentscore='';
            }
            $totcount=$totcount+1;
            $totpointsearn=$totpointsearn+$studentscore;
            $totpointspossi=$totpointspossi+$totscore;

            ?>
            <tr title="Click here to Grade Rubric" onclick="fn_showrubricpoints(<?php echo "21,".$id[1].",".$id[2].",".$id[3].",".$rubricids.",".$scheduleid; ?>);" style="cursor:pointer;">
                <td>
                    <?php echo $rubnam; ?>
                </td>
                <td>
                    <input type="text" name="expearned_<?php echo $rubricids."_".$scheduleid;?>" id="expearned_<?php echo $rubricids."_".$scheduleid;?>" value="<?php echo $studentscore;?>" onkeyup="ChkValidChar(this.id);" onchange="$('#expteacher_'+<?php echo $rubricids; ?>+'_'+<?php echo $scheduleid; ?>).val(1);" maxlength="3" readonly />
                </td>
                <td>
                    <input type="text" name="exppossible_<?php echo $rubricids."_".$scheduleid;?>" id="exppossible_<?php echo $rubricids."_".$scheduleid;?>" value="<?php echo $totscore;?>" readonly />
                </td>
                <td></td>
            </tr>
            <input type="hidden" name="expteacher_<?php echo $rubricids."_".$scheduleid;?>" id="expteacher_<?php echo $rubricids."_".$scheduleid;?>" value="" />
            <?php

        }
    }
    /************** Pre/Post test code start here ***************/
    $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                            WHERE fld_class_id='".$id[1]."' AND fld_sch_id='".$id[4]."' AND fld_texpid='".$id[3]."' AND fld_schtype_id='".$id[0]."'");

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
                        
                        $correctcountstu="-";
                        $crctcntstu='-';
                        $qrycorrectcount = $ObjDB->QueryObject("SELECT a.fld_correct_answer AS crctcount FROM itc_test_student_answer_track AS a
                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                               WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$id[2]."' AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$id[4]."' AND a.fld_schedule_type='".$id[0]."'
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
                                                                            WHERE fld_schedule_type='".$id[0]."' AND fld_student_id='".$id[2]."' 
                                                                                AND fld_exp_id='".$expid."' AND fld_schedule_id='".$id[4]."' AND fld_exptype='3' AND fld_res_id='".$testid."' AND fld_delstatus = '0'");
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
                            $totpercentage=round((($correctcountstu/$quescount)*100))." %";
                        }
                        else if($correctcountstu=='-')
                        {
                            $stucorrectcount='';
                            $totpercentage='';
                        }
                        
                        $totcount=$totcount+1;
                        $totpointsearn=$totpointsearn+$pointsearned;
                        $totpointspossi=$totpointspossi+$possiblepoint; 
                        ?>
                        <tr>
                            <td style="cursor:default;">
                                <?php echo $testname;?>
                            </td>
                            <td style="cursor:default;">
                                <input type="text" name="exptestearned_<?php echo $testid."_".$exptype;?>" id="exptestearned_<?php echo $testid."_".$exptype;?>" value="<?php echo $pointsearned;?>" onkeyup="ChkValidChar(this.id);"  onchange="$('#exptestteacher_'+<?php echo $testid; ?>+'_'+<?php echo $exptype; ?>).val(1);" maxlength="3"/>
                            </td>
                            <td style="cursor:default;">
                                <input type="text" name="exptestpossible_<?php echo $testid."_".$exptype;?>" id="exptestpossible_<?php echo $testid."_".$exptype;?>" value="<?php echo $possiblepoint;?>" readonly />
                            </td>
                            <td style="cursor:default;">
                                <?php echo $stucorrectcount; ?>&nbsp;&nbsp;&nbsp;<?php echo $totpercentage; ?>
                            </td>
                        </tr>
                        <input type="hidden" name="exptestteacher_<?php echo $testid."_".$exptype;?>" id="exptestteacher_<?php echo $testid."_".$exptype;?>" value="" />
                        <?php
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
                        
                        $correctcountstu="-";
                        $crctcntstu='-';
                        $qrycorrectcount = $ObjDB->QueryObject("SELECT a.fld_correct_answer AS crctcount FROM itc_test_student_answer_track AS a
                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$id[2]."' AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$id[4]."' AND a.fld_schedule_type='".$id[0]."'
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
                                                                            WHERE fld_schedule_type='".$id[0]."' AND fld_student_id='".$id[2]."' 
                                                                                AND fld_exp_id='".$expid."' AND fld_schedule_id='".$id[4]."' AND fld_exptype='3' AND fld_res_id='".$testid."' AND fld_delstatus = '0'");
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
                            $totpercentage=round((($correctcountstu/$quescount)*100))." %";
                        }
                        else if($correctcountstu=='-')
                        {
                            $stucorrectcount='';
                            $totpercentage='';
                        }
                        
                        $totcount=$totcount+1;
                        $totpointsearn=$totpointsearn+$pointsearned;
                        $totpointspossi=$totpointspossi+$possiblepoint; 
                        ?>
                        <tr>
                            <td style="cursor:default;">
                                <?php echo $testname;?>
                            </td>
                            <td style="cursor:default;">
                                <input type="text" name="exptestearned_<?php echo $testid."_".$exptype;?>" id="exptestearned_<?php echo $testid."_".$exptype;?>" value="<?php echo $pointsearned;?>" onkeyup="ChkValidChar(this.id);"  onchange="$('#exptestteacher_'+<?php echo $testid; ?>+'_'+<?php echo $exptype; ?>).val(1);" maxlength="3"/>
                            </td>
                            <td style="cursor:default;">
                                <input type="text" name="exptestpossible_<?php echo $testid."_".$exptype;?>" id="exptestpossible_<?php echo $testid."_".$exptype;?>" value="<?php echo $possiblepoint;?>" readonly />
                            </td>
                            <td style="cursor:default;">
                                 <?php echo $stucorrectcount; ?>&nbsp;&nbsp;&nbsp;<?php echo $totpercentage; ?>
                            </td>
                        </tr>
                        <input type="hidden" name="exptestteacher_<?php echo $testid."_".$exptype;?>" id="exptestteacher_<?php echo $testid."_".$exptype;?>" value="" />
                        <?php
                    }
                }
            }
            /*********Post Test Code End Here*********/
        }
    }
    /************** Pre/Post test code start here ***************/
    if($totcount>1)
    {
        ?>
        <tr>
            <td style="cursor:default;">
               <b>Total</b>
            </td>
            <td style="cursor:default;">
                <b><?php echo round($totpointsearn);?></b>
            </td>
            <td style="cursor:default;">
                <b><?php echo $totpointspossi;?></b>
            </td>
            <td style="cursor:default;">
                &nbsp;&nbsp;&nbsp;
            </td>
         </tr>    
        <?php
    }

}
/************Expedition Schedule code end here*****************/  

/*********Mission report Code Start Here Developed By Mohan M 16-7-2015*************/	
else if($id[0]==18)
{
    $pointsearned=$ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned FROM itc_mis_points_master 
                                                WHERE fld_schedule_id='".$id[4]."' AND fld_mis_id='".$id[3]."' AND fld_student_id='".$id[2]."'
                                                AND fld_schedule_type='".$id[0]."' AND fld_mistype='4' AND fld_grade='1' AND fld_delstatus='0'");

  
    $possiblepoint='100';
    $mistype=4;
    $fld_id=0;
    $totcount=0;
    $totpointsearn=0;
    $totpointspossi=0;
    
    
    $totcount=$totcount+1;
    $totpointsearn=$totpointsearn+$pointsearned;
    $totpointspossi=$totpointspossi+$possiblepoint; 
    ?>
    <tr>
        <td style="cursor:default;">
            Participation
        </td>
        <td style="cursor:default;">
            <input type="text" name="misearned_<?php echo $fld_id."_".$mistype;?>" id="misearned_<?php echo $fld_id."_".$mistype;?>" value="<?php echo $pointsearned;?>" onkeyup="ChkValidChar(this.id);" onchange="$('#misteacher_'+<?php echo $fld_id; ?>+'_'+<?php echo $mistype; ?>).val(1);" maxlength="3"/>
        </td>
        <td style="cursor:default;">
            <input type="text" name="mispossible_<?php echo $fld_id."_".$mistype;?>" id="mispossible_<?php echo $fld_id."_".$mistype;?>" value="<?php echo $possiblepoint;?>" readonly />
        </td>
         <td style="cursor:default;"></td>
    </tr>
    <input type="hidden" name="misteacher_<?php echo $fld_id."_".$mistype;?>" id="misteacher_<?php echo $fld_id."_".$mistype;?>" value="" />
    <?php
       
    
    /************Rubric Code Start Here*************/	
    $qry = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                    CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                        LEFT JOIN itc_mis_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                        LEFT JOIN itc_mission_master AS c ON a.fld_expmisid = c.fld_id
                                        LEFT JOIN itc_class_indasmission_master AS d ON a.fld_schedule_id=d.fld_id 
                                            WHERE a.fld_class_id='".$id[1]."' AND a.fld_schedule_id='".$id[4]."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                AND a.fld_schedule_type='18' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                AND b.fld_school_id IN(0,".$schoolid.")");
    if($qry->num_rows>0)
    {
        while($rowqry = $qry->fetch_assoc()) // show the module based on number of copies
        {
            extract($rowqry);
            
            $destinationids = $ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_mis_rubric_dest_master WHERE fld_rubric_name_id='".$rubricids."' AND fld_mis_id='".$id[3]."'"); 

            $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_master 
                                                            WHERE fld_mis_id='".$id[3]."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

            $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_rubric_rpt WHERE fld_mis_id='".$id[3]."'  
                                                                AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$id[1]."' AND fld_schedule_id='".$scheduleid."'
                                                                AND fld_delstatus='0' "); 

            $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_rpt_statement 
                                                                    WHERE fld_student_id='".$id[2]."' AND fld_mis_id='".$id[3]."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
                                                                    AND fld_rubric_rpt_id='".$rubricrptid."'"); 

            if($studentscore=='0')
            {
                $studentscore='';
            }
            
            $totcount=$totcount+1;
            $totpointsearn=$totpointsearn+$studentscore;
            $totpointspossi=$totpointspossi+$totscore;

            ?>
            <tr title="Click here to Grade Rubric" onclick="fn_showrubricpoints(<?php echo "20,".$id[1].",".$id[2].",".$id[3].",".$rubricids.",".$scheduleid; ?>);" style="cursor:pointer;">
                <td>
                    <?php echo $rubnam; ?>
                </td>
                <td>
                    <input type="text" name="expearned_<?php echo $rubricids."_".$scheduleid;?>" id="expearned_<?php echo $rubricids."_".$scheduleid;?>" value="<?php echo $studentscore;?>" onkeyup="ChkValidChar(this.id);" onchange="$('#expteacher_'+<?php echo $rubricids; ?>+'_'+<?php echo $scheduleid; ?>).val(1);" maxlength="3" readonly />
                </td>
                <td>
                    <input type="text" name="exppossible_<?php echo $rubricids."_".$scheduleid;?>" id="exppossible_<?php echo $rubricids."_".$scheduleid;?>" value="<?php echo $totscore;?>" readonly />
                </td>
                <td></td>
            </tr>
            <input type="hidden" name="expteacher_<?php echo $rubricids."_".$scheduleid;?>" id="expteacher_<?php echo $rubricids."_".$scheduleid;?>" value="" />
            <?php
        }
    }
    /************Rubric Code End Here*************/	
	
	/************** test code start here ***************/
    $qrytestmis = $ObjDB->QueryObject("SELECT fld_test_id AS pretest,fld_mis_id AS misid FROM itc_mis_ass 
                                       WHERE fld_class_id='".$id[1]."' AND fld_sch_id='".$id[4]."' AND fld_schtype_id='".$id[0]."' AND fld_flag='1'");

    if($qrytestmis->num_rows>0)
    {
        while($rowqrytestmis = $qrytestmis->fetch_assoc())
        {
            extract($rowqrytestmis);
			
            $mistype='3';

            $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,
                                            fld_score AS possiblepoint,fld_question_type AS questype FROM itc_test_master WHERE fld_id='".$pretest."' 
                                                    AND fld_delstatus='0'");

            if($qry->num_rows>0)
            {
                while($rowqry = $qry->fetch_assoc())
                {
                    extract($rowqry);
                    if($questype==2)
                    {
                        $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                    }
                    /*****Teacher Points earned code start here for pre/post test*****/
                    $correctcountstu="-";
                    $crctcntstu='-';
                    $qrycorrectcount = $ObjDB->QueryObject("SELECT a.fld_correct_answer AS crctcount FROM itc_test_student_answer_track AS a
                                                                                LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                   WHERE b.fld_mist = '".$misid."' AND a.fld_student_id = '".$id[2]."' 
                                                                                   AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$id[4]."' 
                                                                                   AND a.fld_schedule_type='".$id[0]."'
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
                    
                    $tpointsearned = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned 
                                                                                FROM itc_mis_points_master 
                                                                                WHERE fld_schedule_type='".$id[0]."' AND fld_student_id='".$id[2]."' 
                                                                        AND fld_mis_id='".$misid."' AND fld_schedule_id='".$id[4]."' 
                                                                        AND fld_mistype='3' AND fld_res_id='".$testid."' AND fld_delstatus = '0'");
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
                        $totpercentage=round((($correctcountstu/$quescount)*100))." %";
                    }
                    else if($correctcountstu=='-')
                    {
                        $stucorrectcount='';
                        $totpercentage='';
                    }
                    
                    $totcount=$totcount+1;
                    $totpointsearn=$totpointsearn+$pointsearned;
                    $totpointspossi=$totpointspossi+$possiblepoint; 
                    ?>
                    <tr>
                        <td style="cursor:default;">
                            <?php echo $testname;?>
                        </td>
                        <td style="cursor:default;">
                            <input type="text" name="mistestearned_<?php echo $testid."_".$mistype;?>" id="mistestearned_<?php echo $testid."_".$mistype;?>" value="<?php echo $pointsearned;?>" onkeyup="ChkValidChar(this.id);"  onchange="$('#mistestteacher_'+<?php echo $testid; ?>+'_'+<?php echo $mistype; ?>).val(1);" maxlength="3"/>
                        </td>
                        <td style="cursor:default;">
                            <input type="text" name="mistestpossible_<?php echo $testid."_".$mistype;?>" id="mistestpossible_<?php echo $testid."_".$mistype;?>" value="<?php echo $possiblepoint;?>" readonly />
                        </td>
                        <td style="cursor:default;">
                            <?php echo $stucorrectcount; ?>&nbsp;&nbsp;&nbsp;<?php echo $totpercentage; ?>
                        </td>
                    </tr>
                    <input type="hidden" name="mistestteacher_<?php echo $testid."_".$mistype;?>" id="mistestteacher_<?php echo $testid."_".$mistype;?>" value="" />
                    <?php
                }
            }
        }
    }
    /************** test code End here ***************/
    
    if($totcount>1)
    {
        ?>
        <tr>
            <td style="cursor:default;">
               <b>Total</b>
            </td>
            <td style="cursor:default;">
                <b><?php echo round($totpointsearn);?></b>
            </td>
            <td style="cursor:default;">
                <b><?php echo $totpointspossi;?></b>
            </td>
            <td style="cursor:default;">
                &nbsp;&nbsp;&nbsp;
            </td>
         </tr>    
        <?php
    }
}

else if($id[0]==23)
{
    $pointsearned = $ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned FROM itc_mis_points_master 
                                                        WHERE fld_schedule_id='".$id[4]."' AND fld_mis_id='".$id[3]."' AND fld_student_id='".$id[2]."'
                                                        AND fld_schedule_type='".$id[0]."' AND fld_mistype='4' AND fld_grade='1' AND fld_delstatus='0'");

    $fld_id=0;
    $mistype=4;
    $possiblepoint='100';
    if($pointsearned=='0')
    {
        $pointsearned='';
    }
    
    $totcount=0;
    $totpointsearn=0;
    $totpointspossi=0;

    $totcount=$totcount+1;
    $totpointsearn=$totpointsearn+$pointsearned;
    $totpointspossi=$totpointspossi+$possiblepoint;
    
    ?>
    <tr>
        <td style="cursor:default;">
            Participation
        </td>
        <td style="cursor:default;">
            <input type="text" name="misearned_<?php echo $fld_id."_".$mistype;?>" id="misearned_<?php echo $fld_id."_".$mistype;?>" value="<?php echo $pointsearned;?>" onkeyup="ChkValidChar(this.id);" onchange="$('#misteacher_'+<?php echo $fld_id; ?>+'_'+<?php echo $mistype; ?>).val(1);" maxlength="3"/>
        </td>
        <td style="cursor:default;">
            <input type="text" name="mispossible_<?php echo $fld_id."_".$mistype;?>" id="mispossible_<?php echo $fld_id."_".$mistype;?>" value="<?php echo $possiblepoint;?>" readonly />
        </td>
         <td style="cursor:default;"></td>
    </tr>
    <input type="hidden" name="misteacher_<?php echo $fld_id."_".$mistype;?>" id="misteacher_<?php echo $fld_id."_".$mistype;?>" value="" />
    <?php
	
    /************** Rubric code start here ***************/
    $qry = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
									CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
										LEFT JOIN itc_mis_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
										LEFT JOIN itc_mission_master AS c ON a.fld_expmisid = c.fld_id
										LEFT JOIN itc_class_rotation_mission_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
											WHERE a.fld_class_id='".$id[1]."' AND a.fld_schedule_id='".$id[4]."' AND b.fld_mis_id='".$id[3]."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
												AND a.fld_schedule_type='19' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
												AND b.fld_school_id IN(0,".$schoolid.")");

    if($qry->num_rows>0)
    {
        while($rowqry = $qry->fetch_assoc()) // show the module based on number of copies
        {
            extract($rowqry);
            
            $destinationids = $ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_mis_rubric_dest_master WHERE fld_rubric_name_id='".$rubricids."' AND fld_mis_id='".$id[3]."'"); 

            $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_master 
                                                            WHERE fld_mis_id='".$id[3]."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

            $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_missch_rubric_rpt WHERE fld_mis_id='".$id[3]."'  
                                                                AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$id[1]."' AND fld_schedule_id='".$scheduleid."'
                                                                AND fld_delstatus='0' "); 

            $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_missch_rubric_rpt_statement
                                                                WHERE fld_student_id='".$id[2]."' AND fld_mis_id='".$id[3]."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
                                                                AND fld_rubric_rpt_id='".$rubricrptid."'"); 

            if($studentscore=='0')
            {
                $studentscore='';
            }
            $totcount=$totcount+1;
            $totpointsearn=$totpointsearn+$studentscore;
            $totpointspossi=$totpointspossi+$totscore;

            ?>
            <tr title="Click here to Grade Rubric" onclick="fn_showrubricpoints(<?php echo "24,".$id[1].",".$id[2].",".$id[3].",".$rubricids.",".$scheduleid; ?>);" style="cursor:pointer;">
                <td>
                    <?php echo $rubnam; ?>
                </td>
                <td>
                    <input type="text" name="expearned_<?php echo $rubricids."_".$scheduleid;?>" id="expearned_<?php echo $rubricids."_".$scheduleid;?>" value="<?php echo $studentscore;?>" onkeyup="ChkValidChar(this.id);" onchange="$('#expteacher_'+<?php echo $rubricids; ?>+'_'+<?php echo $scheduleid; ?>).val(1);" maxlength="3" readonly />
                </td>
                <td>
                    <input type="text" name="exppossible_<?php echo $rubricids."_".$scheduleid;?>" id="exppossible_<?php echo $rubricids."_".$scheduleid;?>" value="<?php echo $totscore;?>" readonly />
                </td>
                <td></td>
            </tr>
            <input type="hidden" name="expteacher_<?php echo $rubricids."_".$scheduleid;?>" id="expteacher_<?php echo $rubricids."_".$scheduleid;?>" value="" />
            <?php
        }
    }
    /************** Rubric code end here ***************/
	
	/************** test code start here ***************/
    $qrytestmis = $ObjDB->QueryObject("SELECT fld_test_id AS pretest,fld_mis_id AS misid FROM itc_mis_ass 
                                       WHERE fld_class_id='".$id[1]."' AND fld_sch_id='".$id[4]."' AND fld_mis_id='".$id[3]."' AND fld_schtype_id='20' AND fld_flag='1'");

    if($qrytestmis->num_rows>0)
    {
        while($rowqrytestmis = $qrytestmis->fetch_assoc())
        {
            extract($rowqrytestmis);
			
            $mistype='3';

            $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,
                                                                            fld_score AS possiblepoint,fld_question_type AS questype FROM itc_test_master WHERE fld_id='".$pretest."' 
                                                                                    AND fld_delstatus='0'");

            if($qry->num_rows>0)
            {
                while($rowqry = $qry->fetch_assoc())
                {
                    extract($rowqry);
                    if($questype==2)
                    {
                        $quescount = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$testid."' AND fld_delstatus='0'");
                    }
                    /*****Teacher Points earned code start here for pre/post test*****/
                    $correctcountstu="-";
                    $crctcntstu='-';
                    $qrycorrectcount = $ObjDB->QueryObject("SELECT a.fld_correct_answer AS crctcount FROM itc_test_student_answer_track AS a
                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                               WHERE b.fld_mist = '".$misid."' AND a.fld_student_id = '".$id[2]."' 
                                                                               AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$id[4]."' 
                                                                               AND a.fld_schedule_type='20'
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
                     
                    $tpointsearned = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned 
                                                                                    FROM itc_mis_points_master 
                                                                                    WHERE fld_schedule_type='20' AND fld_student_id='".$id[2]."' 
                                                                            AND fld_mis_id='".$misid."' AND fld_schedule_id='".$id[4]."' 
                                                                            AND fld_mistype='3' AND fld_res_id='".$testid."' AND fld_delstatus = '0'");
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
                        $totpercentage=round((($correctcountstu/$quescount)*100))." %";
                    }
                    else if($correctcountstu=='-')
                    {
                        $stucorrectcount='';
                        $totpercentage='';
                    }
                    
                    $totcount=$totcount+1;
                    $totpointsearn=$totpointsearn+$pointsearned;
                    $totpointspossi=$totpointspossi+$possiblepoint; 
                    ?>
                    <tr>
                        <td style="cursor:default;">
                            <?php echo $testname;?>
                        </td>
                        <td style="cursor:default;">
                            <input type="text" name="mistestearned_<?php echo $testid."_".$mistype;?>" id="mistestearned_<?php echo $testid."_".$mistype;?>" value="<?php echo $pointsearned;?>" onkeyup="ChkValidChar(this.id);"  onchange="$('#mistestteacher_'+<?php echo $testid; ?>+'_'+<?php echo $mistype; ?>).val(1);" maxlength="3"/>
                        </td>
                        <td style="cursor:default;">
                            <input type="text" name="mistestpossible_<?php echo $testid."_".$mistype;?>" id="mistestpossible_<?php echo $testid."_".$mistype;?>" value="<?php echo $possiblepoint;?>" readonly />
                        </td>
                        <td style="cursor:default;">
                            <?php echo $stucorrectcount; ?>&nbsp;&nbsp;&nbsp;<?php echo $totpercentage; ?>
                        </td>
                    </tr>
                    <input type="hidden" name="mistestteacher_<?php echo $testid."_".$mistype;?>" id="mistestteacher_<?php echo $testid."_".$mistype;?>" value="" />
                    <?php
                }
            }
        }
    }
    /************** test code End here ***************/
    if($totcount>1)
    {
        ?>
        <tr>
            <td style="cursor:default;">
               <b>Total</b>
            </td>
            <td style="cursor:default;">
                <b><?php echo round($totpointsearn);?></b>
            </td>
            <td style="cursor:default;">
                <b><?php echo $totpointspossi;?></b>
            </td>
            <td style="cursor:default;">
                &nbsp;&nbsp;&nbsp;
            </td>
         </tr>    
        <?php
    }
    
}
/*********Mission report Code End Here Developed By Mohan M 16-7-2015*************/	

/**************************Expedition and Module schedule Code start here by Mohan**********************/
else if($id[0]==20) ///Expedition
{
    $totcount=0;
    $totpointsearn=0;
    $totpointspossi=0;
    $qry = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                    CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                    LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                    LEFT JOIN itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                    LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
                                     WHERE a.fld_class_id='".$id[1]."' AND a.fld_schedule_id='".$id[4]."' AND b.fld_exp_id='".$id[3]."'  AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  AND a.fld_schedule_type='20'
                                         AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                         AND b.fld_school_id IN(0,".$schoolid.")");

    if($qry->num_rows>0)
    {
        while($rowqry = $qry->fetch_assoc()) // show the module based on number of copies
        {
            extract($rowqry);
            
            $destinationids = $ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_exp_rubric_dest_master WHERE fld_rubric_name_id='".$rubricids."' AND fld_exp_id='".$id[3]."'"); 
			           
            $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master 
                                                                WHERE fld_exp_id='".$id[3]."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

            $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_expmodsch_rubric_rpt WHERE fld_exp_id='".$id[3]."'  
                                                                    AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$id[1]."' AND fld_schedule_id='".$scheduleid."'
                                                                    AND fld_delstatus='0' "); 

            $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_expmodsch_rubric_rpt_statement
                                                                        WHERE fld_student_id='".$id[2]."' AND fld_exp_id='".$id[3]."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
                                                                        AND fld_rubric_rpt_id='".$rubricrptid."'"); 

            if($studentscore=='0')
            {
                $studentscore='';
            }
            
            $totcount=$totcount+1;
            $totpointsearn=$totpointsearn+$studentscore;
            $totpointspossi=$totpointspossi+$totscore;
            ?>
            <tr title="Click here to Grade Rubric" onclick="fn_showrubricpoints(<?php echo "25,".$id[1].",".$id[2].",".$id[3].",".$rubricids.",".$scheduleid; ?>);" style="cursor:pointer;">
                <td>
                    <?php echo $rubnam; ?>
                </td>
                <td>
                    <input type="text" name="expearned_<?php echo $rubricids."_".$scheduleid;?>" id="expearned_<?php echo $rubricids."_".$scheduleid;?>" value="<?php echo $studentscore;?>" onkeyup="ChkValidChar(this.id);" onchange="$('#expteacher_'+<?php echo $rubricids; ?>+'_'+<?php echo $scheduleid; ?>).val(1);" maxlength="3" readonly />
                </td>
                <td>
                    <input type="text" name="exppossible_<?php echo $rubricids."_".$scheduleid;?>" id="exppossible_<?php echo $rubricids."_".$scheduleid;?>" value="<?php echo $totscore;?>" readonly />
                </td>
                <td></td>
            </tr>
            <input type="hidden" name="expteacher_<?php echo $rubricids."_".$scheduleid;?>" id="expteacher_<?php echo $rubricids."_".$scheduleid;?>" value="" />
            <?php

        }
    }
           
    /************** Pre/Post test code start here ***************/
    $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                            WHERE fld_class_id='".$id[1]."' AND fld_sch_id='".$id[4]."' AND fld_texpid='".$id[3]."' AND fld_schtype_id='".$id[0]."'");

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
                        $correctcountstu="-";
                        $crctcntstu='-';
                        $qrycorrectcount = $ObjDB->QueryObject("SELECT a.fld_correct_answer AS crctcount FROM itc_test_student_answer_track AS a
                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                            WHERE b.fld_expt = '".$id[3]."' AND a.fld_student_id = '".$id[2]."' AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$id[4]."' AND a.fld_schedule_type='".$id[0]."'
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
                                                                            WHERE fld_schedule_type='".$id[0]."' AND fld_student_id='".$id[2]."' 
                                                                                    AND fld_exp_id='".$id[3]."' AND fld_schedule_id='".$id[4]."' AND fld_exptype='3' AND fld_res_id='".$testid."' AND fld_delstatus = '0'");
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
                            $totpercentage=round((($correctcountstu/$quescount)*100))." %";
                        }
                        else if($correctcountstu=='-')
                        {
                            $stucorrectcount='';
                            $totpercentage='';
                        }
                        
                        
                        $totcount=$totcount+1;
                        $totpointsearn=$totpointsearn+$pointsearned;
                        $totpointspossi=$totpointspossi+$possiblepoint; 
                        ?>
                        <tr>
                            <td style="cursor:default;">
                                <?php echo $testname;?>
                            </td>
                            <td style="cursor:default;">
                                <input type="text" name="expmodtestearned_<?php echo $testid."_".$exptype;?>" id="expmodtestearned_<?php echo $testid."_".$exptype;?>" value="<?php echo $pointsearned;?>" onkeyup="ChkValidChar(this.id);"  onchange="$('#expmodtestteacher_'+<?php echo $testid; ?>+'_'+<?php echo $exptype; ?>).val(1);" maxlength="3"/>
                            </td>
                            <td style="cursor:default;">
                                <input type="text" name="expmodtestpossible_<?php echo $testid."_".$exptype;?>" id="expmodtestpossible_<?php echo $testid."_".$exptype;?>" value="<?php echo $possiblepoint;?>" readonly />
                            </td>
                            <td style="cursor:default;">
                                <?php echo $stucorrectcount; ?>&nbsp;&nbsp;&nbsp;<?php echo $totpercentage; ?>
                            </td>
                        </tr>
                        <input type="hidden" name="expmodtestteacher_<?php echo $testid."_".$exptype;?>" id="expmodtestteacher_<?php echo $testid."_".$exptype;?>" value="" />
                        <?php
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
                        $correctcountstu="-";
                        $crctcntstu='-';
                        $qrycorrectcount = $ObjDB->QueryObject("SELECT a.fld_correct_answer AS crctcount FROM itc_test_student_answer_track AS a
                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                            WHERE b.fld_expt = '".$id[3]."' AND a.fld_student_id = '".$id[2]."' AND a.fld_test_id='".$testid."' AND a.fld_schedule_id='".$id[4]."' AND a.fld_schedule_type='".$id[0]."'
                                                                            AND b.fld_delstatus = '0' AND a.fld_delstatus = '0'"); //AND a.fld_show = '1' 
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
                                                                            WHERE fld_schedule_type='".$id[0]."' AND fld_student_id='".$id[2]."' 
                                                                                    AND fld_exp_id='".$id[3]."' AND fld_schedule_id='".$id[4]."' AND fld_exptype='3' AND fld_res_id='".$testid."' AND fld_delstatus = '0'");
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
                            $totpercentage=round((($correctcountstu/$quescount)*100))." %";
                        }
                        else if($correctcountstu=='-')
                        {
                            $stucorrectcount='';
                            $totpercentage='';
                        }
                        
                        $totcount=$totcount+1;
                        $totpointsearn=$totpointsearn+$pointsearned;
                        $totpointspossi=$totpointspossi+$possiblepoint; 
                        ?>
                        <tr>
                            <td style="cursor:default;">
                                <?php echo $testname;?>
                            </td>
                            <td style="cursor:default;">
                                <input type="text" name="expmodtestearned_<?php echo $testid."_".$exptype;?>" id="expmodtestearned_<?php echo $testid."_".$exptype;?>" value="<?php echo $pointsearned;?>" onkeyup="ChkValidChar(this.id);"  onchange="$('#expmodtestteacher_'+<?php echo $testid; ?>+'_'+<?php echo $exptype; ?>).val(1);" maxlength="3"/>
                            </td>
                            <td style="cursor:default;">
                                <input type="text" name="expmodtestpossible_<?php echo $testid."_".$exptype;?>" id="expmodtestpossible_<?php echo $testid."_".$exptype;?>" value="<?php echo $possiblepoint;?>" readonly />
                            </td>
                            <td style="cursor:default;">
                                <?php echo $stucorrectcount; ?>&nbsp;&nbsp;&nbsp;<?php echo $totpercentage; ?>
                            </td>
                        </tr>
                        <input type="hidden" name="expmodtestteacher_<?php echo $testid."_".$exptype;?>" id="expmodtestteacher_<?php echo $testid."_".$exptype;?>" value="" />
                        <?php
                    }
                }
                
            }
            /*********Post Test Code End Here*********/
        }
    }
    /************** Pre/Post test code start here ***************/
    
    if($totcount>1)
    {
        ?>
        <tr>
            <td style="cursor:default;">
               <b>Total</b>
            </td>
            <td style="cursor:default;">
                <b><?php echo round($totpointsearn);?></b>
            </td>
            <td style="cursor:default;">
                <b><?php echo $totpointspossi;?></b>
            </td>
            <td style="cursor:default;">
                &nbsp;&nbsp;&nbsp;
            </td>
         </tr>    
        <?php
    }
}

else if($id[0]==21) /// mOdule and Custom Content
{
    if($id[5]=='1')
    {
        $totcount=0;
        $totpointsearn=0;
        $totpointspossi=0;
        $totstucount=0;
        $totquescount=0;
        for($i=0;$i<8;$i++)
        {
            $j=$i;	$j++;
            $stucorrectcount='';
            $sessquescount='';
            $correctortotal='';
            if($i==7)
            {
                $qry = $ObjDB->QueryObject("SELECT a.fld_performance_name, a.fld_points_possible,fld_grade, (SELECT (CASE WHEN fld_lock='0' THEN fld_points_earned 
                                                                WHEN fld_lock='1' THEN fld_teacher_points_earned END) FROM `itc_module_points_master` 
                                                                WHERE fld_delstatus='0' AND fld_student_id='".$id[2]."' AND fld_schedule_id='".$id[4]."' 
                                                                AND fld_schedule_type='".$id[0]."' AND fld_module_id='".$id[3]."' 
                                                                AND fld_preassment_id=a.fld_id) AS points 
                                        FROM `itc_module_performance_master` AS a WHERE a.fld_module_id='".$id[3]."' AND a.fld_delstatus='0' 
                                        AND a.fld_performance_name<>'Attendance' AND a.fld_performance_name<>'Participation' 
                                        AND a.fld_performance_name<>'Total Pages' order by a.fld_id ASC");
                $pername = array();
                $pointpossible = array();
                $pointsearned = array();
                $cnt=0;
                while($row=$qry->fetch_assoc())
                {
                    extract($row);
                    $pername[$cnt] = $fld_performance_name;
                    $pergrade=$ObjDB->SelectSingleValueInt("SELECT fld_grade FROM itc_module_wca_grade WHERE fld_page_title='".addslashes($fld_performance_name)."' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_schedule_id='".$id[4]."' AND fld_type='3'");
                    $pointpossible[$cnt] = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade 
                                                                                                WHERE fld_page_title='".addslashes($fld_performance_name)."' AND fld_flag='1' 
                                                                                                AND fld_module_id='".$id[3]."' AND fld_schedule_id='".$id[4]."'	AND fld_type='3'");
                    if($pointpossible[$cnt] == 0 or $pointpossible[$cnt] == '')
                    {
                        $pointpossible[$cnt] = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade
                                                                                        WHERE fld_page_title='".addslashes($fld_performance_name)."' AND fld_flag='1' 
                                                                                        AND fld_module_id='".$id[3]."' AND fld_type='3' 
                                                                                        AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."'");
                        $pergrade= $ObjDB->SelectSingleValueInt("SELECT fld_grade FROM itc_module_wca_grade WHERE fld_page_title='".addslashes($fld_performance_name)."' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_type='3' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."'");
                        if($pointpossible[$cnt] == 0 or $pointpossible[$cnt] == '')
                        {
                            $createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
                            $pergrade =$ObjDB->SelectSingleValueInt("SELECT fld_grade FROM itc_module_wca_grade WHERE fld_page_title='".addslashes($fld_performance_name)."' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_type='3' AND fld_created_by IN (".$createdids.")");
                            $pointpossible[$cnt] = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade
                                                                                        WHERE fld_page_title='".addslashes($fld_performance_name)."' AND fld_flag='1' 
                                                                                        AND fld_module_id='".$id[3]."' AND fld_type='3' 
                                                                                        AND fld_created_by IN (".$createdids.")");

                            if($pointpossible[$cnt] == 0 or $pointpossible[$cnt] == '')
                            $pointpossible[$cnt] = $fld_points_possible;
                        }
                    }

                    if($pergrade==0)
                    {
                        if($fld_grade==0)
                        {
                      $pointpossible[$cnt]=0; 
                    }
                    }
                
                    $pointsearned[$cnt] = $points;									
                    $cnt++;
                }
            }
            else if($i<7)
            {
                $newschtype = '1';
                $newmodid = $id[3];

                if($id[0]==21) 
                {
                        $qry = "SELECT a.fld_session_id, a.fld_type, (CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN 
                                                a.fld_lock='1' THEN a.fld_teacher_points_earned END) AS pointsearned, 
                                                fld_points_possible AS possiblepoint, a.fld_points_earned AS stpointsearned 
                                        FROM `itc_module_points_master` AS a 
                                        LEFT JOIN `itc_class_rotation_modexpschedulegriddet` AS b 
                                                ON (a.fld_module_id=b.fld_module_id) 
                                        WHERE a.fld_student_id='".$id[2]."' AND a.fld_module_id='".$id[3]."' 
                                                AND a.fld_schedule_id='".$id[4]."' AND a.fld_schedule_type='".$id[0]."' AND a.fld_delstatus='0'
                                                AND b.fld_class_id='".$id[1]."' AND a.fld_session_id='".$i."' AND b.fld_flag='1' 
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
                        {
                            $pointsearned[0] = $row->pointsearned;
                            $pointsearned[3] = $row->stpointsearned;
                        }
                        else if($row->fld_type==1)
                        {
                            $pointsearned[1] = $row->pointsearned;
                        }
                        else if($row->fld_type==2)
                        {
                            $pointsearned[2] = $row->pointsearned;
                        }

                        $cnt++;
                    }
                }
            }

            $createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");

            $sesspossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_session_id='".$i."' AND fld_page_title='".$pagetitle."' 
                                                                        AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_schedule_id='".$id[4]."' AND fld_type='0'");
            $sessgrade = $ObjDB->SelectSingleValueInt("SELECT fld_grade FROM itc_module_wca_grade WHERE fld_session_id='".$i."' AND fld_page_title='".$pagetitle."' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_schedule_id='".$id[4]."' AND fld_type='0'");



            $attenpossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_page_title='Attendance' AND fld_flag='1' 
                                                            AND fld_module_id='".$id[3]."' AND fld_schedule_id='".$id[4]."' AND fld_type='1'");
            $attengrade= $ObjDB->SelectSingleValueInt("SELECT fld_grade FROM itc_module_wca_grade WHERE fld_page_title='Attendance' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_schedule_id='".$id[4]."' AND fld_type='1'");


            $partipossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_page_title='Participation' AND fld_flag='1' 
                                                                AND fld_module_id='".$id[3]."' AND fld_schedule_id='".$id[4]."' AND fld_type='2'");
             $pargrade= $ObjDB->SelectSingleValueInt("SELECT fld_grade FROM itc_module_wca_grade WHERE fld_page_title='Participation' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_schedule_id='".$id[4]."' AND fld_type='2'");


            if($sesspossible==0)
            {
                    $sesspossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_session_id='".$i."' AND fld_page_title='".$pagetitle."' 
                                                                        AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_school_id='".$schoolid."' 
                                                                        AND fld_user_id='".$indid."' AND fld_type='0' AND fld_schedule_type='".$newschtype."'");
                    $sessgrade = $ObjDB->SelectSingleValueInt("SELECT fld_grade FROM itc_module_wca_grade WHERE fld_session_id='".$i."' AND fld_page_title='".$pagetitle."' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_type='0' AND fld_schedule_type='".$newschtype."'");


                    if($sesspossible==0)
                    {
                        $sesspossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_session_id='".$i."' AND fld_page_title='".$pagetitle."' 
                                                                                AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_type='0' 
                                                                                AND fld_schedule_type='".$newschtype."' AND fld_created_by IN (".$createdids.")");
                        $sessgrade = $ObjDB->SelectSingleValueInt("SELECT fld_grade FROM itc_module_wca_grade WHERE fld_session_id='".$i."' AND fld_page_title='".$pagetitle."' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_type='0' AND fld_schedule_type='".$newschtype."' AND fld_created_by IN (".$createdids.")");


                        if($sesspossible==0)
                        {
                            $sesspossible = $ObjDB->SelectSingleValueInt("SELECT fld_points 
                                                                            FROM itc_module_grade 
                                                                            WHERE fld_page_title='".$pagetitle."' AND fld_flag='1' AND fld_session_id='".$i."'
                                                                            AND fld_module_id='".$newmodid."'");
                            $sessgrade = $ObjDB->SelectSingleValueInt("SELECT fld_grade 
                                                                            FROM itc_module_grade 
                                                                            WHERE fld_page_title='".$pagetitle."' AND fld_flag='1' AND fld_session_id='".$i."'
                                                                            AND fld_module_id='".$newmodid."'");

                            
                        }
                    }

                    if($attenpossible==0)
                    {
                        $attenpossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_page_title='Attendance' AND fld_flag='1' 
                                                                                    AND fld_module_id='".$id[3]."'  AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' 
                                                                                    AND fld_type='1' AND fld_schedule_type='".$newschtype."'");
                        $attengrade= $ObjDB->SelectSingleValueInt("SELECT fld_grade FROM itc_module_wca_grade WHERE fld_page_title='Attendance' AND fld_flag='1' AND fld_module_id='".$id[3]."'  AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_type='1' AND fld_schedule_type='".$newschtype."'");

                        if($attenpossible==0)
                        {
                            $attenpossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_page_title='Attendance' AND fld_flag='1' 
                                                                                    AND fld_module_id='".$id[3]."' AND fld_type='1' AND fld_schedule_type='".$newschtype."' 
                                                                                    AND fld_created_by IN (".$createdids.")");
                            $attengrade= $ObjDB->SelectSingleValueInt("SELECT fld_grade FROM itc_module_wca_grade WHERE fld_page_title='Attendance' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_type='1' AND fld_schedule_type='".$newschtype."' AND fld_created_by IN (".$createdids.")");

                            if($attenpossible==0)
                            {
                                $attenpossible = $ObjDB->SelectSingleValueInt("SELECT fld_points_possible 
                                                                                        FROM itc_module_performance_master 
                                                                                        WHERE fld_session_id='".$i."'
                                                                                        AND fld_module_id='".$newmodid."'");
                                 $attengrade=$ObjDB->SelectSingleValueInt("SELECT fld_grade 
                                                                                FROM itc_module_performance_master 
                                                                                WHERE fld_session_id='".$i."'
                                                                                AND fld_module_id='".$newmodid."'");
                            }
                        }
                    }

                    if($partipossible==0)
                    {
                        $partipossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_page_title='Participation' AND fld_flag='1' 
                                                                                        AND fld_module_id='".$id[3]."'  AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' 
                                                                                        AND fld_type='2' AND fld_schedule_type='".$newschtype."'");
                         $pargrade= $ObjDB->SelectSingleValueInt("SELECT fld_grade FROM itc_module_wca_grade WHERE fld_page_title='Participation' AND fld_flag='1' AND fld_module_id='".$id[3]."'  AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_type='2' AND fld_schedule_type='".$newschtype."'");

                        if($partipossible==0)
                        {
                            $partipossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_page_title='Participation' AND fld_flag='1' 
                                                                                    AND fld_module_id='".$id[3]."' AND fld_type='2' AND fld_schedule_type='".$newschtype."' 
                                                                                    AND fld_created_by IN (".$createdids.")");
                            $pargrade= $ObjDB->SelectSingleValueInt("SELECT fld_grade FROM itc_module_wca_grade WHERE fld_page_title='Participation' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_type='2' AND fld_schedule_type='".$newschtype."' AND fld_created_by IN (".$createdids.")");

                            if($partipossible==0)
                            {
                                $partipossible = $ObjDB->SelectSingleValueInt("SELECT fld_points_possible FROM itc_module_performance_master 
                                                                                    WHERE fld_session_id='".$i."' AND fld_module_id='".$newmodid."'");
                                $pargrade=$ObjDB->SelectSingleValueInt("SELECT fld_grade
                                                                        FROM itc_module_performance_master 
                                                                        WHERE fld_session_id='".$i."'
                                                                        AND fld_module_id='".$newmodid."'");

                            }
                        }
                    }
            }
            ?>
            <tr style="font-weight:bold; cursor:default"><td colspan="4"><?php if($i<7) { echo "Session ".$j; } else { echo "Performance"; } ?></td></tr>
            <?php if($i!=5) 
            {
                //RCA
                $totcount=$totcount+1;
                //$totpointsearn=$totpointsearn+$pointsearned[0];
                
                
                if($i==0)
                {
                    if($pointsearned[3] !='')
                    {
                        $stucorrectcount=$pointsearned[3];
                        $sessquescount=$sesspossible;
                        $totstucount+=$stucorrectcount;
                        $totquescount+=$sessquescount;
                        $correctortotal=$stucorrectcount." / ".$sessquescount;
                    }
                }
                else if($i==1 || $i==2 || $i==3 || $i==4 || $i==6)
                {
                    if($pointsearned[3] !='')
                    {
                        $stucorrectcount=round($pointsearned[3]/10);
                        $sessquescount=$sesspossible/10;
                        $totstucount+=$stucorrectcount;
                        $totquescount+=$sessquescount;
                        $correctortotal=$stucorrectcount." / ".$sessquescount;
                    }
                }
                
                 if($sessgrade==0)
                {
                   $sesspossible=0; 
                   
                   if($pointsearned[0]>0 and $i<7)
                    {
                        $pointsearned[0]=0;
                }
                }

                if($attengrade==0)
                {
                   $attenpossible=0; 
                }

                if($pargrade==0)
                {
                   $partipossible=0; 
                }
            
                if($i<7) 
                {
                    $totpointspossi=$totpointspossi+$sesspossible;
                }
                else 
                {
                    $totpointspossi=$totpointspossi+$pointpossible[0];
                }

                if($i==7)
                {
                   if($pointpossible[0]==0)
                   {
                      $pointsearned[0]=0; 
                   }
                }
                
                
                ?>
                <tr style="cursor:default">
                    <td style="cursor:default;" width='200px'><?php if($i<7) { echo $pagetitle; } else { echo $pername[0]; } ?></td>
                    <td style="cursor:default;"  width='100px'>
                        <input type="text" name="earned1_<?php echo $i;?>" id="earned1_<?php echo $i;?>" value="<?php echo $pointsearned[0];?>" onkeyup="ChkValidChar(this.id);" maxlength="3" onchange="$('#teacher1_'+<?php echo $i; ?>).val(1);"/>
                    </td>
                    <td style="cursor:default;"  width='150px'><input type="text" name="possible1_<?php echo $i;?>" id="possible1_<?php echo $i;?>" value="<?php if($i<7) { echo $sesspossible; } else { echo $pointpossible[0]; } ?>" readonly /></td>
                     <td style="cursor:default;"  width='150px'><?php if($i==0 || $i==1 || $i==2 || $i==3 || $i==4 || $i==6) { echo $correctortotal; } ?></td>
                </tr>
                <?php 
            }	
            
            if($sessgrade==0)
            {
               $sesspossible=0; 
            }

            if($attengrade==0)
            {
               $attenpossible=0; 
                if($pointsearned[1]>0 and $i<7)
                {
                    $pointsearned[1]=0;
            }
            }

            if($pargrade==0)
            {
               $partipossible=0; 
               if($pointsearned[2]>0 and $i<7)
                {
                    $pointsearned[2]=0;
            }
            }
            
            //Attendance
            //$totpointsearn=$totpointsearn+$pointsearned[1];
            if($i<7) 
            {
                $totpointspossi=$totpointspossi+$attenpossible;
            }
            else 
            {
                $totpointspossi=$totpointspossi+$pointpossible[1];
            }

            //Participation
           // $totpointsearn=$totpointsearn+$pointsearned[2];
            if($i<7) 
            {
                $totpointspossi=$totpointspossi+$partipossible;
            }
            else 
            {
                $totpointspossi=$totpointspossi+$pointpossible[2];
            }
            
            if($i==7)
            {
               if($pointpossible[1]==0)
               {
                  $pointsearned[1]=0; 
               }
               
               if($pointpossible[2]==0)
               {
                  $pointsearned[2]=0; 
               }
            }
            ?>

            <tr style="cursor:default">
                <td style="cursor:default;" width='200px'><?php if($i<7) { echo "Attendance"; } else { echo $pername[1]; } ?></td>
                <td style="cursor:default;" width='100px'><input type="text" name="earned2_<?php echo $i;?>" id="earned2_<?php echo $i;?>" value="<?php echo $pointsearned[1]; ?>" onkeyup="ChkValidChar(this.id);" onchange="$('#teacher2_'+<?php echo $i; ?>).val(1);" /></td>
                <td style="cursor:default;" width='150px'><input type="text" name="possible2_<?php echo $i;?>" id="possible2_<?php echo $i;?>" value="<?php if($i<7) { echo $attenpossible; } else { echo $pointpossible[1]; } ?>" readonly /></td>
                <td width='150px'></td>
            </tr>

            <tr style="cursor:default">
                <td style="cursor:default;" width='200px'><?php if($i<7) { echo "Participation"; } else { echo $pername[2]; } ?></td>
                <td style="cursor:default;" width='100px'><input type="text" name="earned3_<?php echo $i;?>" id="earned3_<?php echo $i;?>" value="<?php echo $pointsearned[2]; ?>" onkeyup="ChkValidChar(this.id);" onchange="$('#teacher3_'+<?php echo $i; ?>).val(1);"/></td>
                <td style="cursor:default;" width='150px'><input type="text" name="possible3_<?php echo $i;?>" id="possible3_<?php echo $i;?>" value="<?php if($i<7) { echo $partipossible; } else { echo $pointpossible[2]; } ?>" readonly /></td>
                <td width='150px'></td>
            </tr>
            <input type="hidden" name="teacher1_<?php echo $i;?>" id="teacher1_<?php echo $i;?>" value="" />
            <input type="hidden" name="teacher2_<?php echo $i;?>" id="teacher2_<?php echo $i;?>" value="" />
            <input type="hidden" name="teacher3_<?php echo $i;?>" id="teacher3_<?php echo $i;?>" value="" />
            <?php 
        }//for Loop End Here
        if($totcount>1)
        {
            $totapointsearned='';
            $totapointspossible='';

            $qrypointsmod = $ObjDB->QueryObject("SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END) AS totapointsearned FROM itc_module_points_master 
                                                    WHERE fld_student_id='".$id[2]."' AND fld_schedule_id='".$id[4]."' 
                                                        AND fld_schedule_type='".$id[0]."' AND fld_module_id='".$id[3]."'
                                                        AND (fld_points_earned<>'' OR fld_teacher_points_earned<>'') AND fld_grade<>'0' 
                                                        AND fld_delstatus='0'");

            if($qrypointsmod->num_rows>0)
            {
                $rowqrypointsmod = $qrypointsmod->fetch_assoc();
                extract($rowqrypointsmod);
            }
            
            

            ?>
            <tr>
                <td style="cursor:default;">
                   <b>Total</b>
                </td>
                <td style="cursor:default;">
                    <b><?php echo round($totapointsearned+$totpointsearn);?></b>
                </td>
                <td style="cursor:default;">
                    <b><?php echo $totpointspossi;?></b>
                </td>
                <td style="cursor:default;"><?php if($totquescount!=0){ echo $totstucount." / ".$totquescount; } ?></td>
             </tr>    
            <?php
        }
        
    }
    else if($id[5]=='8')
    {
        $qry = $ObjDB->QueryObject("SELECT a.fld_id, a.fld_contentname AS customname, a.fld_pointspossible AS possiblepoint, 
                                                                        (SELECT fld_teacher_points_earned 
                                                                        FROM itc_module_points_master 
                                                                        WHERE fld_schedule_type='22' AND fld_student_id='".$id[2]."' 
                                                                                AND fld_module_id='".$id[3]."' AND fld_schedule_id='".$id[4]."' AND fld_delstatus='0') AS pointsearned 
                                                                FROM itc_customcontent_master AS a 
                                                                LEFT JOIN itc_class_rotation_schedulegriddet AS b ON a.fld_id=b.fld_module_id 
                                                                WHERE b.fld_module_id='".$id[3]."' AND a.fld_delstatus='0' 
                                                                        AND b.fld_class_id='".$id[1]."' AND b.fld_schedule_id='".$id[4]."' 
                                                                        AND b.fld_flag='1' AND b.fld_type='22' 
                                                                GROUP BY a.fld_id");

        if($qry->num_rows>0)
        {
                $rowqry = $qry->fetch_assoc(); 
                extract($rowqry);

                ?>
                <tr style="cursor:default">
                        <td><?php echo $customname;?></td>
                        <td>
                                <input type="text" name="expmodcontearned_<?php echo $fld_id;?>" id="expmodcontearned_<?php echo $fld_id;?>" value="<?php echo $pointsearned;?>" onkeyup="ChkValidChar(this.id);" onchange="$('#expmodcontteacher_'+<?php echo $fld_id; ?>).val(1);" maxlength="3"/>
                        </td>
                        <td>
                                <input type="text" name="expmodcontpossible_<?php echo $fld_id;?>" id="expmodcontpossible_<?php echo $fld_id;?>" value="<?php echo $possiblepoint;?>" readonly />
                        </td>
                </tr>
                <input type="hidden" name="expmodcontteacher_<?php echo $fld_id;?>" id="expmodcontteacher_<?php echo $fld_id;?>" value="" />
                <?php
        }
    }
}
/**************************Expedition and Module schedule Code End here by Mohan**********************/     

else if($id[0]==7) //QUEST
{
    $totcount=0;
    $totpointsearn=0;
    $totpointspossi=0;
    
    $totalchapters = $ObjDB->SelectSingleValueInt("SELECT MAX(fld_session_id)+1 
                                                        FROM itc_module_performance_master 
                                                        WHERE fld_module_id='".$id[3]."'");
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
                                                        AND b.fld_student_id='".$id[2]."' 
                                                        AND b.fld_schedule_id='".$id[4]."' 
                                                        AND b.fld_schedule_type='".$id[0]."' AND b.fld_delstatus='0') AS pointsearned, 
                                                        a.fld_type AS typename
                                                        FROM itc_module_wca_grade AS a 
                                                        LEFT JOIN itc_class_indassesment_master AS c ON (a.fld_module_id=c.fld_module_id)
                                                        WHERE a.fld_module_id='".$id[3]."' AND a.fld_session_id='".$i."' AND a.fld_schedule_id='".$id[4]."' 
                                                                AND a.fld_flag='1' AND c.fld_class_id='".$id[1]."' AND c.fld_flag='1' 
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
                                                            AND b.fld_student_id='".$id[2]."' 
                                                            AND b.fld_schedule_id='".$id[4]."' 
                                                            AND b.fld_schedule_type='".$id[0]."' AND b.fld_delstatus='0') AS pointsearned, 
                                                    a.fld_type AS typename
                                                    FROM itc_module_wca_grade AS a 
                                                    LEFT JOIN itc_class_indassesment_master AS c ON (a.fld_module_id=c.fld_module_id)
                                                    WHERE a.fld_module_id='".$id[3]."' AND a.fld_session_id='".$i."' AND a.fld_flag='1' AND c.fld_class_id='".$id[1]."' 
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
                                                            AND b.fld_student_id='".$id[2]."' 
                                                            AND b.fld_schedule_id='".$id[4]."' 
                                                            AND b.fld_schedule_type='".$id[0]."' AND b.fld_delstatus='0') AS pointsearned, 
                                                                a.fld_type AS typename
                                                                FROM itc_module_wca_grade AS a 
                                                                LEFT JOIN itc_class_indassesment_master AS c ON (a.fld_module_id=c.fld_module_id)
                                                                WHERE a.fld_module_id='".$id[3]."' AND a.fld_session_id='".$i."' AND c.fld_flag='1' 
                                                                        AND a.fld_flag='1' AND c.fld_class_id='".$id[1]."' AND a.fld_created_by IN (".$createdids.")
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
                                                                AND b.fld_student_id='".$id[2]."' 
                                                                AND b.fld_schedule_id='".$id[4]."' 
                                                                AND b.fld_schedule_type='".$id[0]."' AND b.fld_delstatus='0') AS pointsearned, 
                                                                            '0' AS typename
                                                                    FROM itc_module_quest_details AS a 
                                                                    LEFT JOIN itc_class_indassesment_master AS c ON (a.fld_module_id=c.fld_module_id)
                                                                    WHERE a.fld_module_id='".$id[3]."' AND a.fld_section_id='".$i."' 
                                                                            AND a.fld_flag='1' AND c.fld_class_id='".$id[1]."' AND c.fld_flag='1' GROUP BY a.fld_page_id 
                                                                    ORDER BY a.fld_page_id)
                                                                                    UNION ALL
                                                                    (SELECT a.fld_id AS ids, a.fld_performance_name AS titlename, a.fld_points_possible 
                                                                            AS possiblepoint, (SELECT (CASE WHEN b.fld_lock='0' THEN b.fld_points_earned
                                                                            WHEN b.fld_lock='1' THEN b.fld_teacher_points_earned END) 
                                                                            FROM itc_module_points_master AS b 
                                                                            WHERE a.fld_module_id=b.fld_module_id 
                                                                            AND a.fld_session_id=b.fld_session_id 
                                                                            AND a.fld_id=b.fld_preassment_id AND b.fld_type='3' 
                                                                            AND b.fld_student_id='".$id[2]."' 
                                                                            AND b.fld_schedule_id='".$id[4]."' 
                                                                            AND b.fld_schedule_type='".$id[0]."' AND b.fld_delstatus='0') AS pointsearned, 
                                                                    '3' AS typename 
                                                                    FROM itc_module_performance_master AS a 
                                                                    WHERE a.fld_module_id='".$id[3]."' AND a.fld_delstatus='0' 
                                                                            AND a.fld_performance_name<>'Attendance' 
                                                                            AND a.fld_performance_name<>'Participation' 
                                                                            AND a.fld_performance_name<>'Total Pages' AND a.fld_session_id='".$i."' GROUP BY a.fld_id)");
                }
            }
        }

        if($qry->num_rows>0)
        {
            ?>
            <tr style="font-weight:bold; cursor:default"><td colspan="3" style="cursor:default;"><?php echo "Chapter ".$j; ?></td></tr>
            <?php
            while($row=$qry->fetch_assoc())
            {
                extract($row);

                $totcount=$totcount+1;
                $totpointsearn=$totpointsearn+$pointsearned;
                $totpointspossi=$totpointspossi+$possiblepoint;
                ?>
                <tr style="cursor:default">
                    <td style="cursor:default;"><?php echo $titlename;?></td>
                    <td style="cursor:default;">
                        <input type="text" name="qusetearned_<?php echo $ids."-".$i."-".$typename;?>" id="qusetearned_<?php echo $ids."-".$i."-".$typename;?>" value="<?php echo $pointsearned;?>" onkeyup="ChkValidChar(this.id);" onchange="$('#qusetteacher_<?php echo $ids."-".$i."-".$typename; ?>').val(1);" maxlength="3"/>
                    </td>
                    <td style="cursor:default;">
                        <input type="text" name="qusetpossible_<?php echo $ids."-".$i."-".$typename;?>" id="qusetpossible_<?php echo $ids."-".$i."-".$typename;?>" value="<?php echo $possiblepoint;?>" readonly />
                    </td>
                </tr>
                <input type="hidden" name="qusetteacher_<?php echo $ids."-".$i."-".$typename;?>" id="qusetteacher_<?php echo $ids."-".$i."-".$typename;?>" value="" />
                <?php
            }
        }
    }
    if($totcount>1)
    {
        ?>
        <tr>
            <td style="cursor:default;">
               <b>Total</b>
            </td>
            <td style="cursor:default;">
                <b><?php echo round($totpointsearn);?></b>
            </td>
            <td style="cursor:default;">
                <b><?php echo $totpointspossi;?></b>
            </td>
            <td style="cursor:default;">
                &nbsp;&nbsp;&nbsp;
            </td>
         </tr>    
        <?php
    }
} 

else  //Module,Math Module
{
    $totcount=0;
    $totpointsearn=0;
    $totpointspossi=0;
    $totstucount=0;
    $totquescount=0;
    for($i=0;$i<8;$i++)
    {
        $j=$i;	$j++;
        $stucorrectcount='';
        $sessquescount='';
        $correctortotal='';
        if($i==7)
        {
            if($id[0]==4 || $id[0]==6)
                $qry = $ObjDB->QueryObject("SELECT a.fld_performance_name, a.fld_points_possible,fld_grade, (SELECT (CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END) FROM `itc_module_points_master` WHERE fld_delstatus='0' AND fld_student_id='".$id[2]."' AND fld_schedule_id='".$id[4]."' AND fld_schedule_type='".$id[0]."' AND fld_module_id='".$id[3]."' AND fld_preassment_id=a.fld_id) AS points FROM `itc_module_performance_master` AS a WHERE a.fld_module_id = (SELECT fld_module_id FROM itc_mathmodule_master WHERE fld_id='".$id[3]."' AND fld_delstatus='0') AND a.fld_delstatus='0' AND  a.fld_performance_name<>'Attendance' AND  a.fld_performance_name<>'Participation' AND a.fld_performance_name<>'Total Pages'");
            else
                $qry = $ObjDB->QueryObject("SELECT a.fld_performance_name, a.fld_points_possible,fld_grade, (SELECT (CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END) FROM `itc_module_points_master` WHERE fld_delstatus='0' AND fld_student_id='".$id[2]."' AND fld_schedule_id='".$id[4]."' AND fld_schedule_type='".$id[0]."' AND fld_module_id='".$id[3]."' AND fld_preassment_id=a.fld_id) AS points FROM `itc_module_performance_master` AS a WHERE a.fld_module_id='".$id[3]."' AND a.fld_delstatus='0' AND a.fld_performance_name<>'Attendance' AND a.fld_performance_name<>'Participation' AND a.fld_performance_name<>'Total Pages' order by a.fld_id ASC");
            $pername = array();
            $pointpossible = array();
            $pointsearned = array();
            $cnt=0;
            while($row=$qry->fetch_assoc())
            {
                extract($row);
                $pername[$cnt] = $fld_performance_name;
                $pergrade=$ObjDB->SelectSingleValueInt("SELECT fld_grade FROM itc_module_wca_grade WHERE fld_page_title='".addslashes($fld_performance_name)."' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_schedule_id='".$id[4]."' AND fld_type='3'");
                $pointpossible[$cnt] = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_page_title='".addslashes($fld_performance_name)."' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_schedule_id='".$id[4]."' AND fld_type='3'");
                if($pointpossible[$cnt] == 0 or $pointpossible[$cnt] == '')
                {
                    $pergrade= $ObjDB->SelectSingleValueInt("SELECT fld_grade FROM itc_module_wca_grade WHERE fld_page_title='".addslashes($fld_performance_name)."' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_type='3' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."'");
                    $pointpossible[$cnt] = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_page_title='".addslashes($fld_performance_name)."' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_type='3' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."'");
                    if($pointpossible[$cnt] == 0 or $pointpossible[$cnt] == '')
                    {
                        $createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
                        $pergrade =$ObjDB->SelectSingleValueInt("SELECT fld_grade FROM itc_module_wca_grade WHERE fld_page_title='".addslashes($fld_performance_name)."' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_type='3' AND fld_created_by IN (".$createdids.")");
                        $pointpossible[$cnt] = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_page_title='".addslashes($fld_performance_name)."' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_type='3' AND fld_created_by IN (".$createdids.")");

                        if($pointpossible[$cnt] == 0 or $pointpossible[$cnt] == '')
                        $pointpossible[$cnt] = $fld_points_possible;
                    }
                }

                if($pergrade==0)
                {
                    if($fld_grade==0)
                    {
                   $pointpossible[$cnt]=0; 
                }
                }

                $pointsearned[$cnt] = $points;									
                $cnt++;
            }
        }
        else if($i<7)
        {
            if($id[0]==4 or $id[0]==6)
            {
                $newschtype = '2';
                $newmodid = $ObjDB->SelectSingleValueInt("SELECT fld_module_id FROM itc_mathmodule_master WHERE fld_id='".$id[3]."' AND fld_delstatus='0'");
            }
            else
            {
                $newschtype = '1';
                $newmodid = $id[3];
            }
            if($id[0]==1 || $id[0]==4) 
            {
                $qry = "SELECT a.fld_session_id, a.fld_type, (CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN 
                                        a.fld_lock='1' THEN a.fld_teacher_points_earned END) AS pointsearned, 
                                        fld_points_possible AS possiblepoint, a.fld_points_earned AS stpointsearned 
                                FROM `itc_module_points_master` AS a 
                                LEFT JOIN `itc_class_rotation_schedulegriddet` AS b 
                                        ON (a.fld_module_id=b.fld_module_id) 
                                WHERE a.fld_student_id='".$id[2]."' AND a.fld_module_id='".$id[3]."' 
                                        AND a.fld_schedule_id='".$id[4]."' AND a.fld_schedule_type='".$id[0]."' AND a.fld_delstatus='0'
                                        AND b.fld_class_id='".$id[1]."' AND a.fld_session_id='".$i."' AND b.fld_flag='1' 
                                GROUP BY a.fld_type";
            }
            else if($id[0]==2) 
            {
                $qry = "SELECT a.fld_session_id, a.fld_type, (CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN 
                                        a.fld_lock='1' THEN a.fld_teacher_points_earned END) AS pointsearned, 
                                        fld_points_possible AS possiblepoint, a.fld_points_earned AS stpointsearned  
                                FROM `itc_module_points_master` AS a 
                                LEFT JOIN `itc_class_dyad_schedulegriddet` AS b ON (a.fld_module_id=b.fld_module_id) 
                                WHERE a.fld_student_id='".$id[2]."' AND a.fld_module_id='".$id[3]."' 
                                        AND a.fld_schedule_id='".$id[4]."' AND a.fld_schedule_type='".$id[0]."' AND a.fld_delstatus='0' 
                                        AND b.fld_class_id='".$id[1]."' AND a.fld_session_id='".$i."' AND b.fld_flag='1' 
                                GROUP BY a.fld_type";
            }
            else if($id[0]==3) 
            {
                $qry = "SELECT a.fld_session_id, a.fld_type, (CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN 
                                        a.fld_lock='1' THEN a.fld_teacher_points_earned END) AS pointsearned, 
                                        fld_points_possible AS possiblepoint , a.fld_points_earned AS stpointsearned 
                                FROM `itc_module_points_master` AS a 
                                LEFT JOIN `itc_class_triad_schedulegriddet` AS b ON (a.fld_module_id=b.fld_module_id) 
                                WHERE a.fld_student_id='".$id[2]."' AND a.fld_module_id='".$id[3]."' 
                                        AND a.fld_schedule_id='".$id[4]."' AND a.fld_schedule_type='".$id[0]."' AND a.fld_delstatus='0' 
                                        AND b.fld_class_id='".$id[1]."' AND a.fld_session_id='".$i."' AND b.fld_flag='1' 
                                GROUP BY a.fld_type";
            }
            else if($id[0]==5 || $id[0]==6) 
            {
                $qry = "SELECT a.fld_session_id, a.fld_type, (CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN 
                                        a.fld_lock='1' THEN a.fld_teacher_points_earned END) AS pointsearned, 
                                        fld_points_possible AS possiblepoint, a.fld_points_earned AS stpointsearned 
                                FROM `itc_module_points_master` AS a 
                                LEFT JOIN `itc_class_indassesment_master` AS b ON (a.fld_module_id=b.fld_module_id) 
                                WHERE a.fld_student_id='".$id[2]."' AND a.fld_module_id='".$id[3]."' 
                                        AND a.fld_schedule_id='".$id[4]."' AND a.fld_schedule_type='".$id[0]."' AND a.fld_delstatus='0' 
                                        AND b.fld_class_id='".$id[1]."' AND a.fld_session_id='".$i."' AND b.fld_flag='1' 
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
                    {
                            $pointsearned[0] = $row->pointsearned;
                        $pointsearned[3] = $row->stpointsearned;
                    }
                    else if($row->fld_type==1)
                    {
                            $pointsearned[1] = $row->pointsearned;
                    }
                    else if($row->fld_type==2)
                    {
                            $pointsearned[2] = $row->pointsearned;
                    }

                    $cnt++;
                }
            }
        }

        $createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");

        $sesspossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_session_id='".$i."' AND fld_page_title='".$pagetitle."' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_schedule_id='".$id[4]."' AND fld_type='0'");

        $sessgrade = $ObjDB->SelectSingleValueInt("SELECT fld_grade FROM itc_module_wca_grade WHERE fld_session_id='".$i."' AND fld_page_title='".$pagetitle."' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_schedule_id='".$id[4]."' AND fld_type='0'");

        $attenpossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_page_title='Attendance' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_schedule_id='".$id[4]."' AND fld_type='1'");

        $attengrade= $ObjDB->SelectSingleValueInt("SELECT fld_grade FROM itc_module_wca_grade WHERE fld_page_title='Attendance' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_schedule_id='".$id[4]."' AND fld_type='1'");

        $partipossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_page_title='Participation' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_schedule_id='".$id[4]."' AND fld_type='2'");

        $pargrade=$ObjDB->SelectSingleValueInt("SELECT fld_grade FROM itc_module_wca_grade WHERE fld_page_title='Participation' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_schedule_id='".$id[4]."' AND fld_type='2'");

        if($sesspossible==0)
        {
            $sesspossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_session_id='".$i."' AND fld_page_title='".$pagetitle."' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_type='0' AND fld_schedule_type='".$newschtype."'");
            
            $sessgrade = $ObjDB->SelectSingleValueInt("SELECT fld_grade FROM itc_module_wca_grade WHERE fld_session_id='".$i."' AND fld_page_title='".$pagetitle."' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_type='0' AND fld_schedule_type='".$newschtype."'");
            
            if($sesspossible==0)
            {
                $sesspossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_session_id='".$i."' AND fld_page_title='".$pagetitle."' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_type='0' AND fld_schedule_type='".$newschtype."' AND fld_created_by IN (".$createdids.")");

                $sessgrade = $ObjDB->SelectSingleValueInt("SELECT fld_grade FROM itc_module_wca_grade WHERE fld_session_id='".$i."' AND fld_page_title='".$pagetitle."' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_type='0' AND fld_schedule_type='".$newschtype."' AND fld_created_by IN (".$createdids.")");

                if($sesspossible==0)
                {
                    $sesspossible = $ObjDB->SelectSingleValueInt("SELECT fld_points 
                                                                            FROM itc_module_grade 
                                                                            WHERE fld_page_title='".$pagetitle."' AND fld_flag='1' AND fld_session_id='".$i."'
                                                                            AND fld_module_id='".$newmodid."'");
                    $sessgrade = $ObjDB->SelectSingleValueInt("SELECT fld_grade 
                                                                            FROM itc_module_grade 
                                                                            WHERE fld_page_title='".$pagetitle."' AND fld_flag='1' AND fld_session_id='".$i."'
                                                                            AND fld_module_id='".$newmodid."'");
                }
            }

            if($attenpossible==0)
            {
                $attenpossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_page_title='Attendance' AND fld_flag='1' AND fld_module_id='".$id[3]."'  AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_type='1' AND fld_schedule_type='".$newschtype."'");
                
                $attengrade= $ObjDB->SelectSingleValueInt("SELECT fld_grade FROM itc_module_wca_grade WHERE fld_page_title='Attendance' AND fld_flag='1' AND fld_module_id='".$id[3]."'  AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_type='1' AND fld_schedule_type='".$newschtype."'");
                if($attenpossible==0)
                {
                    $attenpossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_page_title='Attendance' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_type='1' AND fld_schedule_type='".$newschtype."' AND fld_created_by IN (".$createdids.")");
                    
                    $attengrade= $ObjDB->SelectSingleValueInt("SELECT fld_grade FROM itc_module_wca_grade WHERE fld_page_title='Attendance' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_type='1' AND fld_schedule_type='".$newschtype."' AND fld_created_by IN (".$createdids.")");
                    
                    if($attenpossible==0)
                    {
                        if($id[0]==5 || $id[0]==6)
                        {
                            $attenpossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_page_title='Attendance' AND fld_flag='1' AND fld_module_id='".$id[3]."'  AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_type='1' AND fld_schedule_type='".$newschtype."'");
                
                            $attengrade= $ObjDB->SelectSingleValueInt("SELECT fld_grade FROM itc_module_wca_grade WHERE fld_page_title='Attendance' AND fld_flag='1' AND fld_module_id='".$id[3]."'  AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_type='1' AND fld_schedule_type='".$newschtype."'");
                
                        }
                        else
                        {
                            $attenpossible = $ObjDB->SelectSingleValueInt("SELECT fld_points_possible 
                                                                                    FROM itc_module_performance_master 
                                                                                    WHERE fld_session_id='".$i."'
                                                                                    AND fld_module_id='".$newmodid."'");
                            $attengrade=$ObjDB->SelectSingleValueInt("SELECT fld_grade 
                                                                                FROM itc_module_performance_master 
                                                                                WHERE fld_session_id='".$i."'
                                                                                AND fld_module_id='".$newmodid."'");
                        }
                    }
                }
            }

            if($partipossible==0)
            {
                $partipossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_page_title='Participation' AND fld_flag='1' AND fld_module_id='".$id[3]."'  AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_type='2' AND fld_schedule_type='".$newschtype."'");
                
                $pargrade= $ObjDB->SelectSingleValueInt("SELECT fld_grade FROM itc_module_wca_grade WHERE fld_page_title='Participation' AND fld_flag='1' AND fld_module_id='".$id[3]."'  AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_type='2' AND fld_schedule_type='".$newschtype."'");
                if($partipossible==0)
                {
                    $partipossible = $ObjDB->SelectSingleValueInt("SELECT fld_points FROM itc_module_wca_grade WHERE fld_page_title='Participation' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_type='2' AND fld_schedule_type='".$newschtype."' AND fld_created_by IN (".$createdids.")");
                    
                    $pargrade= $ObjDB->SelectSingleValueInt("SELECT fld_grade FROM itc_module_wca_grade WHERE fld_page_title='Participation' AND fld_flag='1' AND fld_module_id='".$id[3]."' AND fld_type='2' AND fld_schedule_type='".$newschtype."' AND fld_created_by IN (".$createdids.")");
                    
                    if($partipossible==0)
                    {
                        $partipossible = $ObjDB->SelectSingleValueInt("SELECT fld_points_possible 
                                                                        FROM itc_module_performance_master 
                                                                        WHERE fld_session_id='".$i."'
                                                                        AND fld_module_id='".$newmodid."'");
                        $pargrade=$ObjDB->SelectSingleValueInt("SELECT fld_grade
                                                                        FROM itc_module_performance_master 
                                                                        WHERE fld_session_id='".$i."'
                                                                        AND fld_module_id='".$newmodid."'");
                    }
                }
            }
        }
        
        ?>
        <tr style="font-weight:bold; cursor:default"><td style="cursor:default;" colspan="4"><?php if($i<7) { echo "Session ".$j; } else { echo "Performance"; } ?></td></tr>
        <?php 
        if($i!=5) 
        {
            //RCA
            $totcount=$totcount+1;
           // $totpointsearn=$totpointsearn+$pointsearned[0];
            if($i==0)
            {
                if($pointsearned[3] !='')
                {
                    $stucorrectcount=$pointsearned[3];
                    $sessquescount=$sesspossible;
                    $totstucount+=$stucorrectcount;
                    $totquescount+=$sessquescount;
                    $correctortotal=$stucorrectcount." / ".$sessquescount;
                }
            }
            else if($i==1 || $i==2 || $i==3 || $i==4 || $i==6)
            {
                if($pointsearned[3] !='')
                {
                    $stucorrectcount=round($pointsearned[3]/10);
                    $sessquescount=$sesspossible/10;
                    $totstucount+=$stucorrectcount;
                    $totquescount+=$sessquescount;
                    $correctortotal=$stucorrectcount." / ".$sessquescount;
                }
            }
            
            if($sessgrade==0)
            {
               $sesspossible=0; 
               
               if($pointsearned[0]>0 and $i<7)
               {
                   $pointsearned[0]=0;
            }

            }

            if($attengrade==0)
            {
               $attenpossible=0; 
            }
            
            if($pargrade==0)
            {
               $partipossible=0; 
            }
            
            if($i<7) 
            {
                $totpointspossi=$totpointspossi+$sesspossible;
            }
            else 
            {
                $totpointspossi=$totpointspossi+$pointpossible[0];
            }
          
            if($i==7)
            {
               if($pointpossible[0]==0)
               {
                  $pointsearned[0]=0; 
               }
            }
            
            ?>
            <tr style="cursor:default">
                <td style="cursor:default;" width='200px'><?php if($i<7) { echo $pagetitle; } else { echo $pername[0]; } ?></td>
                <td style="cursor:default;"  width='100px'>
                    <input type="text" name="earned1_<?php echo $i;?>" id="earned1_<?php echo $i;?>" value="<?php echo $pointsearned[0];?>" onkeyup="ChkValidChar(this.id);" maxlength="3" onchange="$('#teacher1_'+<?php echo $i; ?>).val(1);"/>
                </td>
                <td style="cursor:default;"  width='150px'><input type="text" name="possible1_<?php echo $i;?>" id="possible1_<?php echo $i;?>" value="<?php if($i<7) { echo $sesspossible; } else { echo $pointpossible[0]; } ?>" readonly /></td>
                <td style="cursor:default;"  width='150px'><?php if($i==0 || $i==1 || $i==2 || $i==3 || $i==4 || $i==6) { echo $correctortotal; } ?></td>
            </tr>
            <?php 
        }  
        
        if($sessgrade==0)
        {
           $sesspossible=0; 
        }

        if($attengrade==0)
        {
           $attenpossible=0; 
           if($pointsearned[1]>0 and $i<7)
            {
                $pointsearned[1]=0;
        }
        }

        if($pargrade==0)
        {
           $partipossible=0; 
           if($pointsearned[2]>0 and $i<7)
            {
                $pointsearned[2]=0;
        }
        }
        
        //Attendance
        //$totpointsearn=$totpointsearn+$pointsearned[1];
        if($i<7) 
        {
            $totpointspossi=$totpointspossi+$attenpossible;
        }
        else 
        {
            $totpointspossi=$totpointspossi+$pointpossible[1];
        }
        
        //Participation
        //$totpointsearn=$totpointsearn+$pointsearned[2];
        if($i<7) 
        {
            $totpointspossi=$totpointspossi+$partipossible;
        }
        else 
        {
            $totpointspossi=$totpointspossi+$pointpossible[2];
        }
        
            if($i==7)
            {
               if($pointpossible[1]==0)
               {
                  $pointsearned[1]=0; 
               }
               
               if($pointpossible[2]==0)
               {
                  $pointsearned[2]=0; 
               }
            }
        ?>

        <tr style="cursor:default">
            <td style="cursor:default;" width='200px'><?php if($i<7) { echo "Attendance"; } else { echo $pername[1]; } ?></td>
            <td style="cursor:default;" width='100px'><input type="text" name="earned2_<?php echo $i;?>" id="earned2_<?php echo $i;?>" value="<?php echo $pointsearned[1]; ?>" onkeyup="ChkValidChar(this.id);" onchange="$('#teacher2_'+<?php echo $i; ?>).val(1);" /></td>
            <td style="cursor:default;" width='150px'><input type="text" name="possible2_<?php echo $i;?>" id="possible2_<?php echo $i;?>" value="<?php if($i<7) { echo $attenpossible; } else { echo $pointpossible[1]; } ?>" readonly /></td>
            <td width='150px'></td>
        </tr>

        <tr style="cursor:default">
            <td style="cursor:default;" width='200px'><?php if($i<7) { echo "Participation"; } else { echo $pername[2]; } ?></td>
            <td style="cursor:default;" width='100px'><input type="text" name="earned3_<?php echo $i;?>" id="earned3_<?php echo $i;?>" value="<?php echo $pointsearned[2]; ?>" onkeyup="ChkValidChar(this.id);" onchange="$('#teacher3_'+<?php echo $i; ?>).val(1);"/></td>
            <td style="cursor:default;" width='150px'><input type="text" name="possible3_<?php echo $i;?>" id="possible3_<?php echo $i;?>" value="<?php if($i<7) { echo $partipossible; } else { echo $pointpossible[2]; } ?>" readonly /></td>
             <td width='150px'></td>
        </tr>
        <input type="hidden" name="teacher1_<?php echo $i;?>" id="teacher1_<?php echo $i;?>" value="" />
        <input type="hidden" name="teacher2_<?php echo $i;?>" id="teacher2_<?php echo $i;?>" value="" />
        <input type="hidden" name="teacher3_<?php echo $i;?>" id="teacher3_<?php echo $i;?>" value="" />
        <?php 
        if($id[0]==4 || $id[0]==6) 
        {
            if($id[0]==4)
                    $testtype = 2;
            if($id[0]==6)
                    $testtype = 5;
            $home = $i; $home++;
            $qrymath = $ObjDB->QueryObject("SELECT fld_session_day1, fld_session_day2, fld_ipl_day1, fld_ipl_day2 
                                                            FROM itc_mathmodule_master 
                                                            WHERE fld_id='".$id[3]."'");
            $rowqrymath=$qrymath->fetch_assoc();
            extract($rowqrymath);

            $sessids = $home;
            if($sessids==$fld_session_day1)
            {
                $day = "Diagnostic Day1"; 
                $earnedqry = $ObjDB->QueryObject("SELECT ROUND(SUM(CASE WHEN fld_lock='0' THEN fld_points_earned 
                    WHEN fld_lock='1' THEN fld_teacher_points_earned END)/4) as earned  
                    FROM itc_assignment_sigmath_master 
                    WHERE fld_schedule_id='".$id[4]."' AND fld_student_id='".$id[2]."' 
                    AND fld_test_type='".$testtype."' AND fld_class_id='".$id[1]."' 
                    AND fld_lesson_id IN (".$fld_ipl_day1.") AND fld_delstatus='0' AND fld_module_id=".$id[3]."
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
                    WHERE fld_schedule_id='".$id[4]."' AND fld_student_id='".$id[2]."' 
                    AND fld_test_type='".$testtype."' AND fld_class_id='".$id[1]."' 
                    AND fld_lesson_id IN (".$fld_ipl_day2.") AND fld_delstatus='0' AND fld_module_id=".$id[3]."
                    AND fld_unitmark='0' AND (fld_status='1' OR fld_status='2' OR fld_lock='1')");
                $rowearnedqry=$earnedqry->fetch_assoc();
                extract($rowearnedqry);

                $diagids = $fld_ipl_day2;
                $dtype="2";
            }
            if($sessids==$fld_session_day1 || $sessids==$fld_session_day2)
            {   
                $totpointsearn=$totpointsearn+$earned;
                $totpointspossi=$totpointspossi+100;
                
                ?>
                <tr style="font-weight:bold; cursor:default"><td colspan="4"><?php echo $day; ?></td></tr>
                <tr style="cursor:default">
                    <td><?php echo $day; ?></td>
                    <td><input type="text" name="dearned_<?php echo $dtype;?>" id="dearned_<?php echo $diagids;?>" value="<?php echo $earned; ?>" onkeyup="ChkValidChar(this.id);"onchange="$('#dteacher_'+<?php echo $dtype; ?>).val(1);"/></td>
                    <td><input type="text" name="dpossible_<?php echo $dtype;?>" id="dpossible_<?php echo $diagids;?>" value="100" readonly /></td>
                     <td></td>
                </tr>
                <input type="hidden" name="dteacher_<?php echo $dtype;?>" id="dteacher_<?php echo $dtype;?>" value="" />
                <?php
            }
        } // Math Module D1 and D2 end here
    } //for Lopp End Here
    
    if($totcount>1)
    {
        $totapointsearned='';
        $totapointspossible='';

        $qrypointsmod = $ObjDB->QueryObject("SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END) AS totapointsearned FROM itc_module_points_master 
                                                WHERE fld_student_id='".$id[2]."' AND fld_schedule_id='".$id[4]."' 
                                                    AND fld_schedule_type='".$id[0]."' AND fld_module_id='".$id[3]."'
                                                    AND (fld_points_earned<>'' OR fld_teacher_points_earned<>'') AND fld_grade<>'0' 
                                                    AND fld_delstatus='0'");

        if($qrypointsmod->num_rows>0)
        {
            $rowqrypointsmod = $qrypointsmod->fetch_assoc();
            extract($rowqrypointsmod);
        }
        
        
        ?>
        <tr>
            <td style="cursor:default;">
               <b>Total</b>
            </td>
            <td style="cursor:default;">
                <b><?php echo round($totapointsearned+$totpointsearn);?></b>
            </td>
            <td style="cursor:default;">
                <b><?php echo $totpointspossi;?></b>
            </td>
            <td style="cursor:default;"><?php if($totquescount!=0){ echo $totstucount." / ".$totquescount; } ?></td>
         </tr>    
        <?php
    }
} 
?>
                </tbody>
                </table>
            </div>
        </div>
        
    <script type="text/javascript" language="javascript">
        $(function(){
                var tabindex = 1;
                $('input,select').each(function() {
                        if (this.type != "hidden") {
                                var $input = $(this);
                                $input.attr("tabindex", tabindex);
                                tabindex++;
                        }
                });
        });

        //Function to enter only numbers in textbox
        $("input[id^=cgaearned],input[id^=earned_],input[id^=expearned_],input[id^=earned1_],input[id^=earned2_],input[id^=earned3_],input[id^=actearned_],input[id^=rearned_],input[id^=dearned_],input[id^=testearned_],input[id^=contearned_]").keypress(function (e) {
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {					
            return false;
        }
        });

        //Function to set the max & min values for the textbox
        String.prototype.startsWith = function (str) {
                return (this.indexOf(str) === 0);
        }
        function ChkValidChar(id) {
                var nextid = id.replace('earned','possible');
                var txtbx = document.getElementById(id).value;
                var nexttxtbx = document.getElementById(nextid).value;
                if(parseInt(txtbx) > parseInt(nexttxtbx)) // true
                {
                   
                    var data ="The Points Earned cannot be greater than Points Possible. Please enter a value less than or equal to Points Possible.";	  

                    $.Zebra_Dialog("<div style='text-align:left'>"+data+"</div>",
                    {
                        'type':     'confirmation',
                        'buttons':  [
                                        {caption: 'OK', callback: function() {
                                            document.getElementById(id).value = "";
                                            document.getElementById(id).focus();
                                        }},
                                    ]
                    });
                    $('.ZebraDialog').css({"posiion":"absolute","left":"50%","top":"50%","transform":"translate(-50%,-50%)","width":"598px"});
                    closeloadingalert();
                   
                   
                       
                }
        }
    </script>
        
        <div class='row rowspacer'>
            <div class='four columns'>&nbsp;</div>
            <!--            <div id="save" class='four columns btn secondary yesNo'>
            <a onclick="fn_gradebookexport(<?php //echo "3,".$id[1].","."'".$studentname."',".$id[2].",".$id[3].",".$id[4].",".$id[0];?>);">Export as csv</a>
            </div>-->
            <?php if($id[0]!=16){ ?>
                <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right; <?php if($id[0]==1 || $id[0]==2 || $id[0]==3 || $id[0]==4 || $id[0]==6 || $id[0]==7 ) { ?> margin-top:-92px;<?php }?>" value="Save" onClick="fn_savepoints(<?php echo $id[0];?>,$('.fht-fixed-body').children('.fht-tbody').scrollLeft(),$('.fht-fixed-body').children('.fht-tbody').scrollTop());" />
            <?php } ?>
        </div>
        
        <input type="hidden" name="hidclassid" id="hidclassid" value="<?php echo $id[1];?>"  />
        <input type="hidden" name="hidscheduleid" id="hidscheduleid" value="<?php echo $id[4];?>"  />
        <input type="hidden" name="hidscheduletype" id="hidscheduletype" value="<?php echo $id[0];?>"  />
        <input type="hidden" name="hidstudentid" id="hidstudentid" value="<?php echo $id[2];?>"  />
        <input type="hidden" name="hidunitmodid" id="hidunitmodid" value="<?php echo $id[3];?>"  />
        <input type="hidden" name="hidmodcustom" id="hidmodcustom" value="<?php echo $id[5];?>"  />
    </div>
</section>
<style>
    .scroll{
        height: 458px;
        overflow-x: auto;
        overflow-y: auto;
        margin-bottom: 100px;
        margin-top: -19px;
    }
</style>
<?php //$('#scrollleft').val($('.fht-fixed-body').children('.fht-tbody').scrollLeft()); $('#scrolltop').val($('.fht-fixed-body').children('.fht-tbody').scrollTop()); 
@include("footer.php");