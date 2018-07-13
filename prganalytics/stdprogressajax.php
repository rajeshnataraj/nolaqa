<?php
@include("../sessioncheck.php");
/******Updated Bu Mohan M 26-11-2015**********/

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
	
$date = date("Y-m-d H:i:s");
$oper = isset($_REQUEST['oper']) ? $_REQUEST['oper'] : '';
if($oper=="showassignments" and $oper != " " )
{
    $prgtaskid = (isset($_REQUEST['taskid'])) ? $_REQUEST['taskid'] : '';
    $stdtaskid= explode("~",$prgtaskid);
    
     /*****IPL Code Start Here Developed By Mohan M 25-11-2015*******/
    if($stdtaskid[3]==1) 
    {
        $qryscheduledetailsipl1=$ObjDB->QueryObject("SELECT a.fld_schedule_name AS schname,a.fld_id AS scheduleid,
                                                        DATE_FORMAT(a.fld_start_date, '%d-%m-%Y') AS sdate,DATE_FORMAT(a.fld_end_date, '%d-%m-%Y') AS edate    
                                                        FROM itc_class_sigmath_master AS a 
                                                        LEFT JOIN  itc_class_sigmath_student_mapping AS b ON a.fld_id=b.fld_sigmath_id
                                                        WHERE a.fld_class_id='".$stdtaskid[2]."' AND b.fld_flag='1' AND a.fld_delstatus='0' GROUP BY b.fld_sigmath_id");
        if($qryscheduledetailsipl1->num_rows>0)
        {
            $rowexpdetailsipl = $qryscheduledetailsipl1->fetch_assoc();
            extract($rowexpdetailsipl);
        }
        
        $roundflag = $ObjDB->SelectSingleValue("SELECT fld_roundflag
                                            FROM itc_class_grading_scale_mapping
                                            WHERE fld_class_id = '".$stdtaskid[2]."' AND fld_flag = '1'
                                            GROUP BY fld_roundflag");

        $qrypoints=$ObjDB->QueryObject("SELECT SUM(a.fld_ipl_points) as pointspossible, SUM(CASE WHEN b.fld_lock='0' 
                                                THEN b.fld_points_earned WHEN b.fld_lock='1' 
                                                THEN b.fld_teacher_points_earned END) AS pointsearned
                                        FROM itc_ipl_master AS a 
                                        LEFT JOIN itc_assignment_sigmath_master AS b ON b.fld_lesson_id=a.fld_id 
                                        WHERE b.fld_class_id='".$stdtaskid[2]."' AND b.fld_student_id='".$stdtaskid[1]."' 
                                                AND b.fld_schedule_id='".$stdtaskid[0]."' 
                                                AND (b.fld_status='1' OR b.fld_status='2')"); 
        if($qrypoints->num_rows>0)
        {
            $rowqrypoints = $qrypoints->fetch_assoc();
            extract($rowqrypoints);

            if($roundflag==0)
                $pointsaverage = round(($pointsearned/$pointspossible)*100,2);
            else
                $pointsaverage = round(($pointsearned/$pointspossible)*100);

            $pointsaverage=$pointsaverage."%";

            $perarray = explode('.',$pointsaverage);
            $grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_class_id='".$stdtaskid[2]."' AND fld_flag='1'");

            if($pointspossible=='' or $pointspossible=='-')
            {
                $pointsearned = " - ";
                $pointspossible = " - ";
                $pointsaverage = " - ";
                $grade = " N/A ";
            }
        }
        
        $lessoncompleted = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                                        FROM itc_assignment_sigmath_master 
                                                                                        WHERE fld_schedule_id='".$stdtaskid[0]."' AND fld_class_id='".$stdtaskid[2]."' AND fld_student_id='".$stdtaskid[1]."' AND fld_status<>'0' AND fld_delstatus='0'");
                        
        $totallesson = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_class_sigmath_lesson_mapping WHERE fld_sigmath_id='".$stdtaskid[0]."' AND fld_flag='1'");

        if($totallesson==0)
        {
            $completeprogressipl=0;
        }
        else if($totallesson!=0)
        {
            $completeprogressipl=($lessoncompleted/$totallesson)*100;
            $completeprogressipl=round($completeprogressipl,2);
        }
        
        ?>
        <style>
            .progressMeterBase 
            {
                position: relative;
                background: #c2c2a3;
            }
            .progressMeter 
            {
                position: relative;
                background: #4c89b3;
                height: 20px;
            }
        </style>
        <div style="width:580px; margin-top: 50px; margin-bottom: 50px;">
            <table cellpadding="0" border="0" cellspacing="0" width="75%" align="center">
                <tr>
                    <td  style="width:165px;">
                        <b>Schedule Name</b>
                    </td>
                      <td>:&nbsp;</td>
                    <td style="width:275px;"> 
                        <?php echo $schname; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        <b>Start Date</b>
                    </td>
                      <td>:&nbsp;</td>
                    <td>
                        <?php echo $sdate; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                       <b> End Date</b>
                    </td>
                      <td>:&nbsp;</td>
                    <td>
                        <?php echo $edate; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        <b>Assignment Marks</b>
                    </td>
                      <td>:&nbsp;</td>
                    <td>
                        <?php echo $pointsearned."/".$pointspossible; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        <b>Assignment Percentage</b>
                    </td>
                      <td>:&nbsp;</td>
                    <td>
                        <?php echo $pointsaverage; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        <b>Grade</b>
                    </td>
                      <td>:&nbsp;</td>
                    <td>
                        <?php echo $grade; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        <b>Complete Progress</b>
                    </td>
                      <td>:&nbsp;</td>
                    <td>
                        <?php echo $completeprogressipl."%"; ?>
                    </td>
                </tr>
            </table>
        </div>
        <?php
         /*********Unit Code Start Here***********/
        $qryunits = $ObjDB->QueryObject("SELECT a.fld_unit_id, b.fld_unit_name AS unitname,fn_shortname(b.fld_unit_name,1) AS unitshortname 
												FROM itc_class_sigmath_unit_mapping AS a LEFT JOIN itc_unit_master AS b ON b.fld_id=a.fld_unit_id 
												WHERE a.fld_sigmath_id='".$stdtaskid[0]."' AND a.fld_flag='1' 
												ORDER BY a.fld_order");
        if($qryunits->num_rows>0)
        {
            $m=0;
            ?>
            <div style="height:400px; overflow:auto;">
                <table cellpadding="0" border="0" cellspacing="0" width="75%" align="center">
                    <?php
                    while($rowunits=$qryunits->fetch_assoc())
                    {
                        $m++;
                        extract($rowunits);
                        ?>
                        <tr>
                            <td  style="width:225px;">
                                <b title="<?php echo $unitname; ?>">Unit Name : <?php echo $unitshortname; ?></b>
                            </td>
                            <td></td>
                            <?php 
                            $iplcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) 
                                                                            FROM itc_class_sigmath_lesson_mapping AS a LEFT JOIN itc_ipl_master AS b ON a.fld_lesson_id=b.fld_id 
                                                                            WHERE a.fld_sigmath_id='".$stdtaskid[0]."' AND a.fld_flag='1' AND b.fld_unit_id='".$fld_unit_id."' AND b.fld_delstatus='0'");
                            
                            $progcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) 
                                                                                FROM itc_assignment_sigmath_master AS a LEFT JOIN itc_class_sigmath_lesson_mapping AS b ON 
                                                                                      a.fld_lesson_id=b.fld_lesson_id AND a.fld_schedule_id=b.fld_sigmath_id 
                                                                                WHERE a.fld_class_id='".$stdtaskid[2]."' AND a.fld_schedule_id='".$stdtaskid[0]."' AND a.fld_student_id='".$stdtaskid[1]."' 
                                                                                AND a.fld_status<>'0' AND a.fld_test_type='1' AND a.fld_delstatus='0' AND a.fld_unit_id='".$fld_unit_id."'"); 
                            $percentage=0;
                            if($iplcount!=0)
                                $percentage = round(($progcount/$iplcount)* 100,2);
                            ?>
                            <td>
                              
                            </td>
                        </tr>
                       
                        <?php
                        /*********Lessons Code Start Here***********/
                        $qryipls = $ObjDB->QueryObject("SELECT a.fld_lesson_id, b.fld_ipl_name AS iplname,fn_shortname(b.fld_ipl_name,1) AS iplshortname
                                                                                                           FROM itc_class_sigmath_lesson_mapping AS a LEFT JOIN itc_ipl_master AS b ON a.fld_lesson_id=b.fld_id 
                                                                                                           WHERE a.fld_sigmath_id='".$stdtaskid[0]."' AND a.fld_flag='1' AND b.fld_unit_id='".$fld_unit_id."' AND b.fld_delstatus='0'  
                                                                                                           ORDER BY a.fld_order");
                        if($qryipls->num_rows>0)
                        {
                            $n=0;
                            while($rowipls=$qryipls->fetch_assoc())
                            {
                                $n++;
                                extract($rowipls);
                                ?>
                                <tr>
                                    <td style="width:100px; margin-left: 10px;">
                                         &nbsp;&nbsp;<?php echo $iplname; ?>
                                    </td>
                                     <td>: &nbsp;</td>
                                        <?php
                                        $counts = '';
                                        $count = '';
                                        $qrycount = $ObjDB->QueryObject("SELECT fld_status AS count 
                                                                            FROM itc_assignment_sigmath_master WHERE fld_class_id='".$stdtaskid[2]."' AND fld_schedule_id='".$stdtaskid[0]."' 
                                                                            AND fld_lesson_id='".$fld_lesson_id."' AND fld_student_id='".$stdtaskid[1]."' AND fld_test_type='1' 
                                                                            AND fld_delstatus='0'");
                                        if($qrycount->num_rows>0)
                                        {
                                            $row=$qrycount->fetch_assoc();
                                            extract($row);
                                            $counts = $count;
                                        }
                                        else
                                        {
                                            $counts = '';
                                        }   ?>
                                    <td>
                                        <?php if($counts==2 || $counts==1) { echo " Completed"; } else if($counts=='0') { echo " In progress"; } else if($counts==''){ echo " Not Started"; }?>
                                    </td>
                                </tr>
                                <?php    
                            } //Lesson While Loop ?><tr><td>&nbsp;</td></tr>
                            <?php   
                        }//Lesson If COndition
                        /*********Lessons Code Start Here***********/
                    }//Unit While Loop
                    ?>
                </table>
            </div>
            <?php
        }//Unit If COndition
        /*********Unit Code End Here***********/
    } 
     /*****IPL Code Start Here Developed By Mohan M 25-11-2015*******/
    
    /*****Rotational Code Start Here Developed By naren 3-12-2015*******/
    else if($stdtaskid[3]==2) 
    {
       if($stdtaskid[5]==21)
       {
            $qryscheduledetailsrot=$ObjDB->QueryObject("SELECT fld_schedule_name AS schedulename,
                                                            DATE_FORMAT(fld_startdate, '%d-%m-%Y') AS sch_stdaterot,DATE_FORMAT(fld_enddate, '%d-%m-%Y') AS sch_enddaterot   
                                                            FROM itc_class_rotation_modexpschedule_mastertemp where fld_id='".$stdtaskid[0]."' and fld_delstatus='0'");
       }
       else if($stdtaskid[5]==24)// dyad
       {
           $qryscheduledetailsrot=$ObjDB->QueryObject("SELECT a.fld_schedule_name AS schedulename,
                                                        DATE_FORMAT(b.fld_startdate, '%d-%m-%Y') AS sch_stdaterot,DATE_FORMAT(b.fld_enddate, '%d-%m-%Y') AS sch_enddaterot   
                                                        FROM itc_class_dyad_schedulemaster AS a 
                                                        LEFT JOIN itc_class_dyad_schedulegriddet AS b ON b.fld_schedule_id=a.fld_id
                                                        where a.fld_id='".$stdtaskid[0]."' and a.fld_delstatus='0' group by schedulename");
       }
       else if($stdtaskid[5]==25) // triad
       {
           $qryscheduledetailsrot=$ObjDB->QueryObject("SELECT a.fld_schedule_name AS schedulename,
                                                        DATE_FORMAT(b.fld_startdate, '%d-%m-%Y') AS sch_stdaterot,DATE_FORMAT(b.fld_enddate, '%d-%m-%Y') AS sch_enddaterot   
                                                        FROM itc_class_triad_schedulemaster AS a 
                                                        LEFT JOIN itc_class_triad_schedulegriddet AS b ON b.fld_schedule_id=a.fld_id
                                                        where a.fld_id='".$stdtaskid[0]."' and a.fld_delstatus='0' group by schedulename");
       }
       else
       {
           $qryscheduledetailsrot=$ObjDB->QueryObject("SELECT fld_schedule_name AS schedulename,
                                                            DATE_FORMAT(fld_startdate, '%d-%m-%Y') AS sch_stdaterot,DATE_FORMAT(fld_enddate, '%d-%m-%Y') AS sch_enddaterot   
                                                            FROM itc_class_rotation_schedule_mastertemp where fld_id='".$stdtaskid[0]."' and fld_delstatus='0'");
       }
       
        if($qryscheduledetailsrot->num_rows>0)
        {
            $rowschdet=$qryscheduledetailsrot->fetch_assoc();
            extract($rowschdet);
        }

        if($stdtaskid[5]==1 OR $stdtaskid[5]==21 OR $stdtaskid[5]==24  OR $stdtaskid[5]==25)
        {
               $modulename=$ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_module_name,' ',b.fld_version)
                                                        FROM itc_module_master AS a 
                                                            LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id='".$stdtaskid[4]."'    
                                                            WHERE a.fld_id='".$stdtaskid[4]."' AND b.fld_delstatus='0' AND a.fld_delstatus='0'");
        }
        else if($stdtaskid[5]==4)
        {
                $modulename=$ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_mathmodule_name,' ',b.fld_version)
                                                        FROM itc_mathmodule_master AS a 
                                                                                    LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_module_id
                                                                                    WHERE a.fld_id='".$stdtaskid[4]."' AND b.fld_delstatus='0' AND a.fld_delstatus='0'");
        }

        if($stdtaskid[5]==24)//dyad
        {
             $qryinddetails = "SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
                          THEN fld_teacher_points_earned END) AS pointsearned, 
                          SUM(fld_points_possible) AS pointspossible 
                          FROM itc_module_points_master 
                          WHERE fld_student_id='".$stdtaskid[1]."' AND fld_module_id='".$stdtaskid[4]."' 
                          AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='2' 
                          AND (fld_points_earned<>'' OR fld_teacher_points_earned<>'') AND fld_grade<>'0' AND fld_delstatus='0'";
        }
        else if($stdtaskid[5]==25) //triad
        {
             $qryinddetails = "SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
                          THEN fld_teacher_points_earned END) AS pointsearned, 
                          SUM(fld_points_possible) AS pointspossible 
                          FROM itc_module_points_master 
                          WHERE fld_student_id='".$stdtaskid[1]."' AND fld_module_id='".$stdtaskid[4]."' 
                          AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='3' 
                          AND (fld_points_earned<>'' OR fld_teacher_points_earned<>'') AND fld_grade<>'0' AND fld_delstatus='0'";
        }
        else
        {
             $qryinddetails = "SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
                          THEN fld_teacher_points_earned END) AS pointsearned, 
                          SUM(fld_points_possible) AS pointspossible 
                          FROM itc_module_points_master 
                          WHERE fld_student_id='".$stdtaskid[1]."' AND fld_module_id='".$stdtaskid[4]."' 
                          AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='".$stdtaskid[5]."' 
                          AND (fld_points_earned<>'' OR fld_teacher_points_earned<>'') AND fld_grade<>'0' AND fld_delstatus='0'";
        }

       

        $roundflag = $ObjDB->SelectSingleValue("SELECT fld_roundflag 
                                                    FROM itc_class_grading_scale_mapping 
                                                    WHERE fld_class_id = '".$stdtaskid[2]."' AND fld_flag = '1' 
                                                    GROUP BY fld_roundflag");

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
                    $stupointsearned = $pointsearned;
                    if($roundflag==0)
                        $percentage = round(($pointsearned/$pointspossible)*100,2);
                    else
                        $percentage = round(($pointsearned/$pointspossible)*100);

                    $perarray = explode('.',$percentage);
                    $grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$stdtaskid[2]."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
                }
            }
        }
       ?>                                               
														        
        <div style="width:580px; margin-top: 50px; margin-bottom: 50px;">
            <table cellpadding="0" border="0" cellspacing="0" width="60%" align="center">
                <tr height="20%">
                    <td style="width:165px;">
                        Schedule Name:
                    </td>
                    <td>
                       <?php echo $schedulename; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Start Date:
                    </td>
                    <td>
                        <?php echo $sch_stdaterot; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        End Date:
                    </td>
                    <td>
                        <?php echo $sch_enddaterot; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Assignment Marks:
                    </td>
                    <td>
                        <?php if($stupointsearned>0){ echo $stupointsearned; }else{ echo "0";} ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Assignment Percentage:
                    </td>
                    <td>
                        <?php if($percentage>0){ echo $percentage;}else{ echo "0%"; } ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Grade:
                    </td>
                    <td>
                      <?php if($grade!=''){ echo $grade; }else{ echo "-";} ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Complete Progress:
                    </td>
                    <td>
                        <?php echo ($stdtaskid[6]*100)." %";?>
                    </td>
                </tr>
                
                <tr>
                    <td style="width:165px;">
                        Module Name :
                    </td>
                    <td>
                        <?php echo $modulename; ?>
                    </td>
                </tr>
                <tr>
                    <td style="width:165px;">
                        <br>
                        <?php
                             for($i=1;$i<=7;$i++)
                             {
                                 echo "Session ".$i."</br>";
                             }
                        ?>
                    </td>
                </tr>
            </table>
        </div>
      <?php
    }
    /*****Rotational Code End Here Developed By naren 3-12-2015*******/
    
    /*****WCA Code Start Here Developed By naren 3-12-2015*******/
    else if($stdtaskid[3]==5 OR $stdtaskid[3]==6 OR $stdtaskid[3]==7) 
    {
        
        
            $qryscheduledetailswca=$ObjDB->QueryObject("SELECT fld_schedule_name AS schedulename,fld_module_id As moduleid,
                                                                                DATE_FORMAT(fld_startdate, '%d-%m-%Y') AS sch_stdatewca,DATE_FORMAT(fld_enddate, '%d-%m-%Y') AS sch_enddatewca    
                                                                                FROM itc_class_indassesment_master where fld_id='".$stdtaskid[0]."' and fld_delstatus='0'");
            if($qryscheduledetailswca->num_rows>0)
            {
                $rowschdet=$qryscheduledetailswca->fetch_assoc();
                extract($rowschdet);
            }

            if($stdtaskid[3]==5)
            {
                   $modulename=$ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_module_name,' ',b.fld_version)
			                              FROM itc_module_master AS a 
										  LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id='".$moduleid."'    
										  WHERE a.fld_id='".$moduleid."' AND b.fld_delstatus='0' AND a.fld_delstatus='0'");
            }
            else if($stdtaskid[3]==6)
            {
                    $modulename=$ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_mathmodule_name,' ',b.fld_version)
				                            FROM itc_mathmodule_master AS a 
											LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_module_id
											WHERE a.fld_id='".$moduleid."' AND b.fld_delstatus='0' AND a.fld_delstatus='0'");
            }
            else
            {
                   $modulename=$qry = $ObjDB->SelectSingleValue("SELECT CONCAT(fld_module_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE
                    fld_mod_id='".$moduleid."' AND fld_delstatus='0')) as modulename FROM itc_module_master WHERE fld_delstatus='0' AND fld_id='".$moduleid."' AND fld_module_type='7'");
            }
            
            
            $qryinddetails = "SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
                              THEN fld_teacher_points_earned END) AS pointsearned, 
                              SUM(fld_points_possible) AS pointspossible 
                              FROM itc_module_points_master 
                              WHERE fld_student_id='".$stdtaskid[1]."' AND fld_module_id='".$moduleid."' 
                              AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='".$stdtaskid[3]."' 
                              AND (fld_points_earned<>'' OR fld_teacher_points_earned<>'') AND fld_grade<>'0' AND fld_delstatus='0'";
            
            $roundflag = $ObjDB->SelectSingleValue("SELECT fld_roundflag 
                                                                        FROM itc_class_grading_scale_mapping 
                                                                        WHERE fld_class_id = '".$stdtaskid[2]."' AND fld_flag = '1' 
                                                                        GROUP BY fld_roundflag");
            
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
                                                        $stupointsearned = $pointsearned;
                                                        if($roundflag==0)
                                                                $percentage = round(($pointsearned/$pointspossible)*100,2);
                                                        else
                                                                $percentage = round(($pointsearned/$pointspossible)*100);

                                                        $perarray = explode('.',$percentage);
                                                        $grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$stdtaskid[2]."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
                                                }

                                                
                                        }
                                }
                                
                                
	   ?>                                               
														        
        <div style="width:580px; margin-top: 50px; margin-bottom: 50px;">
            <table cellpadding="0" border="0" cellspacing="0" width="60%" align="center">
                <tr height="20%">
                    <td style="width:165px;">
                        Schedule Name:
                    </td>
                    <td>
                       <?php echo $schedulename; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Start Date:
                    </td>
                    <td>
                        <?php echo $sch_stdatewca; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        End Date:
                    </td>
                    <td>
                        <?php echo $sch_enddatewca ; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Assignment Marks:
                    </td>
                    <td>
                        <?php if($stupointsearned>0){ echo $stupointsearned; }else{ echo "0";} ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Assignment Percentage:
                    </td>
                    <td>
                        <?php if($percentage>0){ echo $percentage;}else{ echo "0%"; } ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Grade:
                    </td>
                    <td>
                      <?php if($grade!=''){ echo $grade; }else{ echo "-";} ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Complete Progress:
                    </td>
                    <td>
                        <?php echo ($stdtaskid[4]*100)." %";?>
                    </td>
                </tr>
                
                <tr>
                    <td style="width:165px;">
                        Module Name :
                    </td>
                    <td>
                        <?php echo $modulename; ?>
                    </td>
                </tr>
                <tr>
                    <td style="width:165px;">
                        <?php
                             for($i=1;$i<=7;$i++)
                             {
                                 echo "Session ".$i."</br>";
                             }
                        ?>
                    </td>
                </tr>
            </table>
        </div>
      <?php
    }
    /*****WCA Code End Here Developed By naren 3-12-2015*******/
    
    //Expedition Code Start Here
    else if($stdtaskid[3]==4) 
    {
        $qryexpdetails=$ObjDB->QueryObject("SELECT a.fld_exp_id as expid, a.fld_startdate as sdate, a.fld_enddate as edate, a.fld_schedule_name as schname     
                                                FROM itc_class_indasexpedition_master AS a 
                                                LEFT JOIN  itc_class_exp_student_mapping AS b ON a.fld_id=b.fld_schedule_id
                                                WHERE b.fld_student_id='".$stdtaskid[1]."' AND b.fld_schedule_id='$stdtaskid[0]' AND b.fld_flag='1' AND a.fld_delstatus='0'");
        if($qryexpdetails->num_rows>0)
        {
            $rowexpdetails = $qryexpdetails->fetch_assoc();
            extract($rowexpdetails);
        }
        $roundflag = $ObjDB->SelectSingleValue("SELECT fld_roundflag
                                            FROM itc_class_grading_scale_mapping
                                            WHERE fld_class_id = '".$stdtaskid[2]."' AND fld_flag = '1'
                                            GROUP BY fld_roundflag");

        /************** Pre/Post test code start here ***************/
        $pointsearnedfortest=0;
        $possiblepointfortest1=0;
        $possiblepointfortest=0;

        $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                            WHERE fld_class_id='".$stdtaskid[2]."' AND fld_sch_id='".$stdtaskid[0]."' AND fld_schtype_id='15'");

        if($qrytest->num_rows>0)
        {
            while($rowqrytest = $qrytest->fetch_assoc())
            {
                extract($rowqrytest);
                $exptype='3';
                /*********Pre Test Code start Here*********/
                if($pretest!='0')
                {
                    $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint
                                                FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_delstatus='0'");
                    if($qry->num_rows>0)
                    {
                        while($rowqry = $qry->fetch_assoc())
                        {
                            extract($rowqry);

                            $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                            $tchpointcnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_points_master WHERE fld_student_id='".$stdtaskid[1]."' AND fld_exp_id='".$expid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='15' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                            if($tchpointcnt==0)
                            {
                                $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                        WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$stdtaskid[1]."' 
                                                                                        AND a.fld_test_id='".$testid."' AND fld_schedule_id='".$stdtaskid[0]."' 
                                                                                        AND fld_schedule_type='15' AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");

                                if($correctcountfortest != '0')
                                {
                                    $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                    $possiblepointfortest+=$possiblepointfortest1;
                                }
                            }
                            else
                            {
                                $tchpointearn = $ObjDB->SelectSingleValueInt("SELECT (CASE
                                                                                    WHEN fld_lock = '0' THEN fld_points_earned
                                                                                    WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                            END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$stdtaskid[1]."'
                                                                                    AND fld_exp_id='".$expid."' AND fld_schedule_id='".$stdtaskid[0]."' 
                                                                                    AND fld_schedule_type='15' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");
                                if($tchpointearn !=0)
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
                    $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint
                                                FROM itc_test_master WHERE fld_id='".$posttest."' AND fld_delstatus='0'");
                    if($qry->num_rows>0)
                    {
                        while($rowqry = $qry->fetch_assoc())
                        {
                            extract($rowqry);

                            $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                            $tchpointcnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_points_master WHERE fld_student_id='".$stdtaskid[1]."' AND fld_exp_id='".$expid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='15' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                            if($tchpointcnt==0)
                            {
                                $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                        WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$stdtaskid[1]."' 
                                                                                        AND a.fld_test_id='".$testid."' AND fld_schedule_id='".$stdtaskid[0]."' 
                                                                                        AND fld_schedule_type='15' AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");

                                if($correctcountfortest != '0')
                                {
                                    $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                    $possiblepointfortest+=$possiblepointfortest1;
                                }
                            }
                            else
                            {
                                $tchpointearn = $ObjDB->SelectSingleValueInt("SELECT (CASE
                                                                                    WHEN fld_lock = '0' THEN fld_points_earned
                                                                                    WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                            END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$stdtaskid[1]."'
                                                                                    AND fld_exp_id='".$expid."' AND fld_schedule_id='".$stdtaskid[0]."' 
                                                                                    AND fld_schedule_type='15' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");
                                if($tchpointearn !=0)
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
        
        

       // echo "test:".$pointsearnedfortest;
       // echo "<br>";
        /************** Pre/Post test code end here ***************/   

        /************** Rubric code start here ***************/
        $pointsearnedrubric=0;
        $pointspossiblerubric=0;
        $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                        CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                        LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                        LEFT JOIN itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                        LEFT JOIN itc_class_indasexpedition_master AS d ON a.fld_schedule_id=d.fld_id 
                                        WHERE a.fld_class_id='".$stdtaskid[2]."' AND a.fld_schedule_id='".$stdtaskid[0]."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
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
                                                                AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$stdtaskid[2]."' 
                                                                AND fld_schedule_id='".$stdtaskid[0]."' AND fld_delstatus='0' "); 

                $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_rpt_statement
                                                                    WHERE fld_student_id='".$stdtaskid[1]."' AND fld_exp_id='".$expid."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
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

            $grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$stdtaskid[2]."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
        }
        if($percentage==0)
        {
            $percentage = "-";
            $grade = "NA";
            $pointsearned = "-";
            $pointspossible = "-";
        }
        //percentage code end here

        $checkrstatusid = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_school_id='".$senshlid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
        if($checkrstatusid == '0')
        {
            $resourcegroupids=$ObjDB->SelectSingleValue("select 
                                                         GROUP_CONCAT(cnt.fld_id) 
                                                     from
                                                         (SELECT 
                                                             a.fld_id
                                                         FROM
                                                             itc_exp_resource_master AS a
                                                         LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                         LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                         LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                         LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                         LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                         LEFT JOIN itc_class_indasexpedition_master as g ON e.fld_license_id = g.fld_license_id
                                                         where
                                                             c.fld_exp_id = '".$expid."'
                                                                 AND g.fld_id = '".$stdtaskid[0]."'
                                                                 AND d.fld_school_id = '0'
                                                                 AND d.fld_user_id = '0'
                                                                 and d.fld_status = '1'
                                                                 and a.fld_delstatus = '0'
                                                                 and b.fld_delstatus = '0'
                                                                 and c.fld_delstatus = '0'
                                                         GROUP BY a.fld_id) as cnt");


            $rescomplete = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id IN (".$resourcegroupids.") AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='15' AND fld_delstatus='0' AND fld_student_id='".$stdtaskid[1]."' AND fld_read_status='1'");
            $totalresource=sizeof(explode(',',$resourcegroupids));

        }
        else
        {
            $resourcegroupids=$ObjDB->SelectSingleValue("select 
                                                        GROUP_CONCAT(cnt.fld_id) 
                                                    from
                                                        (SELECT 
                                                            a.fld_id
                                                        FROM
                                                            itc_exp_resource_master AS a
                                                        LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                        LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                        LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                        LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                        LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                        LEFT JOIN itc_class_indasexpedition_master as g ON e.fld_license_id = g.fld_license_id
                                                        where
                                                            c.fld_exp_id = '".$expid."'
                                                                AND g.fld_id = '".$stdtaskid[0]."'
                                                                AND d.fld_school_id = '".$senshlid."'
                                                                AND d.fld_created_by='".$uid."'
                                                                AND d.fld_user_id = '".$indid."'
                                                                and d.fld_status = '1'
                                                                and a.fld_delstatus = '0'
                                                                and b.fld_delstatus = '0'
                                                                and c.fld_delstatus = '0'
                                                        GROUP BY a.fld_id) as cnt");


           $rescomplete = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id IN (".$resourcegroupids.") AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='15' AND fld_delstatus='0' AND fld_student_id='".$stdtaskid[1]."' AND fld_read_status='1'");
           $totalresource=sizeof(explode(',',$resourcegroupids));
        }      
        if($totalresource==0)
        {
            $completeprogress=0;
        }
        else if($totalresource!=0)
        {
            $completeprogress=($rescomplete/$totalresource)*100;
            $completeprogress=round($completeprogress,2);
        }
        ?>
        <div style="width:580px; margin-top: 50px; margin-bottom: 50px;">
            <table cellpadding="0" border="0" cellspacing="0" width="60%" align="center">
                <tr>
                    <td style="width:165px;">
                        Schedule Name:
                    </td>
                    <td>
                        <?php echo $schname; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Start Date:
                    </td>
                    <td>
                        <?php echo $sdate; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        End Date:
                    </td>
                    <td>
                        <?php echo $edate; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Assignment Marks:
                    </td>
                    <td>
                        <?php echo $pointsearned."/".$pointspossible; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Assignment Percentage:
                    </td>
                    <td>
                        <?php echo $percentage; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Grade:
                    </td>
                    <td>
                        <?php echo $grade; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Complete Progress:
                    </td>
                    <td>
                        <?php echo $completeprogress."%"; ?>
                    </td>
                </tr>
            </table>
        </div>

        <?php   
        $qrydestdetails=$ObjDB->QueryObject("SELECT a.fld_id as destid, a.fld_dest_name AS destname
                                            FROM itc_exp_destination_master as a
                                            LEFT JOIN itc_license_exp_mapping AS b ON a.fld_id = b.fld_dest_id
                                            LEFT JOIN itc_license_track AS c ON b.fld_license_id = c.fld_license_id
                                            LEFT JOIN itc_class_indasexpedition_master as d on b.fld_license_id=d.fld_license_id
                                            WHERE a.fld_exp_id = '".$expid."' AND d.fld_id = '".$stdtaskid[0]."' AND c.fld_user_id='".$indid."' 
                                                AND c.fld_school_id='".$schoolid."' AND a.fld_delstatus = '0' GROUP BY destid");
        if($qrydestdetails->num_rows>0)
        {
            ?>
            <div style="height:400px; overflow:auto;">
                <?php
                while($rowdestdetails = $qrydestdetails->fetch_assoc())
                {
                    extract($rowdestdetails);

                    $checkshlstatus = $ObjDB->SelectSingleValue("select count(fld_id) from itc_exp_res_status where fld_school_id='".$schoolid."' and fld_created_by='".$uid."' and fld_exp_id='".$expid."'");
                    if($checkshlstatus == '0')
                    {
                        $schoolid ='0';
                    }

                    /* For checking deatination, task and resoures are completed whenever student is enter into expedition - Karthi*/ 
                    $fieldtask1 = 'CONCAT("\'",a.fld_id,"\'")';
                    $grouptaskids1 = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(".$fieldtask1.") 
                                            FROM itc_exp_task_master as a 
                                            LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_task_id
                                            WHERE a.fld_dest_id='".$destid."' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$uid."' AND b.fld_user_id='".$indid."' AND b.fld_status='1' AND a.fld_delstatus='0'");

                    $selecttasks = $ObjDB->QueryObject("SELECT a.fld_id AS taskid 
                                                        FROM itc_exp_task_master AS a
                                                        LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_task_id
                                                        WHERE a.fld_dest_id='".$destid."' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$uid."' AND b.fld_user_id='".$indid."' AND b.fld_status='1' AND a.fld_delstatus='0' ORDER BY a.fld_order");
                    if($selecttasks->num_rows>0) 
                    {
                        while ($rowselecttasks = $selecttasks->fetch_assoc()) 
                        {
                            extract($rowselecttasks);
                            $fieldresvar1 = 'CONCAT("\'",a.fld_id,"\'")';
                            $groupresids1 = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(".$fieldresvar1.") 
                                                                        FROM itc_exp_resource_master As a
                                                                        LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_res_id
                                                                        WHERE a.fld_task_id='".$taskid."' AND a.fld_delstatus='0' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$uid."' AND b.fld_user_id='".$indid."' AND b.fld_status='1'");

                            $resreadcnt1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id IN (".$groupresids1.") AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='15' AND fld_delstatus='0' AND fld_student_id='".$stdtaskid[1]."' AND fld_read_status='1'");

                            if($resreadcnt1 == sizeof(explode(',',$groupresids1)))
                            {
                                $ObjDB->NonQuery("UPDATE itc_exp_task_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_task_id='".$taskid."' AND fld_delstatus='0' AND fld_student_id='".$stdtaskid[1]."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='15'");

                                $taskreadcnt1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_task_play_track WHERE fld_task_id IN (".$grouptaskids1.") AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='15' AND fld_delstatus='0' AND fld_student_id='".$stdtaskid[1]."' AND fld_read_status='1'");

                                if($taskreadcnt1 === sizeof(explode(',',$grouptaskids1)))
                                {
                                    $ObjDB->NonQuery("UPDATE itc_exp_dest_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_dest_id='".$destid."' AND fld_delstatus='0' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='15' AND fld_student_id='".$stdtaskid[1]."'");
                                }
                            }
                        }
                    }
                    /* Ends */

                    $dstatusid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_dest_id='".$destid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");

                    if($dstatusid != ''  and $dstatusid != NULL and $dstatusid != '0')
                    {
                        $dstatus = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_id='".$dstatusid."' AND fld_dest_id='".$destid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                    }          
                    else if($dstatus=='' or $dstatus=='0' or $dstatus==NULL)
                    {
                        $createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
                        $dstatus = $ObjDB->SelectSingleValue("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_dest_id='".$destid."' AND fld_created_by IN (".$createdids.") AND fld_flag='1'");
                    }

                    if($dstatus == 1)
                    {
                        $taskcount=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_exp_dest_play_track 
                                                                    WHERE fld_student_id='".$stdtaskid[1]."' AND fld_exp_id='".$expid."' 
                                                                    AND fld_dest_id='".$destid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='15' AND fld_delstatus='0'");//AND fld_schedule_id='$stdtaskid[0]' 
                        if($taskcount>0)
                        {
                            $taskinpcnt=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_exp_dest_play_track WHERE fld_student_id='".$stdtaskid[1]."' AND fld_exp_id='".$expid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='15' AND fld_dest_id='".$destid."'
                                                                        AND fld_read_status='0' AND fld_delstatus='0'");//count 0=>Completed; 1=>Inprogress for destination checkbox  - AND fld_schedule_id='$stdtaskid[0]' 
                        }
                        else
                        {
                            $taskinpcnt=1;

                        }
                        ?>
                        <ul class="tree" style="margin-left: 15px;">
                            <li>
                                <input type="checkbox" value="0" class="destcls destselect" id="dest_<?php echo $destid; ?>" <?php if($taskinpcnt==0){ echo 'checked="checked"';}else{ echo 'disabled="disabled"';} ?>>
                                <label><?php echo $destname;?></label>
                                <ul class='expanded'>
                                    <?php
        
                                    $qrytaskdetails=$ObjDB->QueryObject("SELECT fld_id as taskid,fld_task_name AS taskname 
                                                                        FROM itc_exp_task_master 
                                                                        WHERE fld_dest_id='".$destid."' AND fld_delstatus='0'");
                                    if($qrytaskdetails->num_rows>0)
                                    {
                                        while($rowtaskdetails = $qrytaskdetails->fetch_assoc())
                                        {
                                            extract($rowtaskdetails);
                                            $tstatusid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_task_id='".$taskid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");

                                            if($tstatusid != ''  and $tstatusid != NULL and $tstatusid != '0')
                                            {
                                                $tstatus = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_id='".$tstatusid."' AND fld_task_id='".$taskid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                                            }
                                            else if($tstatus=='' or $tstatus=='0' or $tstatus==NULL)
                                            {
                                                $createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
                                                $tstatus = $ObjDB->SelectSingleValue("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_task_id='".$taskid."' AND fld_created_by IN (".$createdids.") AND fld_flag='1'");
                                            }

                                            if($tstatus == 1)
                                            { 
                                                $rescount=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_exp_task_play_track 
                                                                                    WHERE fld_student_id='".$stdtaskid[1]."' AND fld_exp_id='".$expid."'  
                                                                                    AND fld_dest_id='".$destid."' AND fld_task_id='".$taskid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='15' AND fld_delstatus='0'");//AND fld_schedule_id='".$stdtaskid[0]."'
                                                if($rescount>0)
                                                {
                                                    $resinpcnt=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_exp_task_play_track WHERE fld_student_id='".$stdtaskid[1]."' AND fld_exp_id='".$expid."' AND fld_dest_id='".$destid."' AND fld_task_id='".$taskid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='15'
                                                                                                AND fld_read_status='0' AND fld_delstatus='0'");//0=>Completed; 1=>Inprogress for task checkbox - AND fld_schedule_id='".$stdtaskid[0]."' 
                                                }
                                                else
                                                {
                                                    $resinpcnt=1;

                                                }

                                                ?>
                                                 <li>
                                                    <input type="checkbox"  class="tskcls select destselect_<?php echo $taskid; ?> destclsid_<?php echo $destid; ?>" id="tsk_<?php echo $taskid."~".$destid; ?>" <?php if($resinpcnt==0){ echo 'checked="checked"';}else{ echo 'disabled="disabled"';} ?>>
                                                    <label><?php echo $taskname;?></label>
                                                    <ul>
                                                    <?php 
            
                                                    $qryresourcedetails=$ObjDB->QueryObject("SELECT fld_id AS resid,fld_res_name AS resname
                                                                                           FROM itc_exp_resource_master
                                                                                           WHERE fld_task_id='".$taskid."' AND fld_delstatus='0'");

                                                    if($qryresourcedetails->num_rows>0)
                                                    {
                                                            while($rowresourcedetails = $qryresourcedetails->fetch_assoc())
                                                            {
                                                                extract($rowresourcedetails);
                                                                $rstatusid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_res_id='".$resid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                                                                if($rstatusid != ''  and $rstatusid != NULL and $rstatusid != '0'){
                                                                    $rstatus = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_id='".$rstatusid."' AND fld_res_id='".$resid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                                                                }
                                                                if($rstatus=='' or $rstatus=='0' or $rstatus==NULL)
                                                                {
                                                                    $createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");

                                                                    $rstatus = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_res_id='".$resid."' AND fld_created_by IN (".$createdids.") AND fld_flag='1'");
                                                                }
                                                                if($rstatus == 1)
                                                                {
                                                                    $rescount1=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_exp_res_play_track 
                                                                                    WHERE fld_student_id='".$stdtaskid[1]."' AND fld_exp_id='".$expid."'  
                                                                                    AND fld_dest_id='".$destid."' AND fld_task_id='".$taskid."' AND fld_res_id='".$resid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='15' AND fld_delstatus='0'");//AND fld_schedule_id='".$stdtaskid[0]."'
                                                                    if($rescount1>0)
                                                                    {
                                                                        $resinpcnt1=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_exp_res_play_track 
                                                                                                            WHERE fld_student_id='".$stdtaskid[1]."' AND fld_exp_id='".$expid."'  
                                                                                                            AND fld_dest_id='".$destid."' AND fld_task_id='".$taskid."' AND fld_res_id='".$resid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='15' AND fld_read_status='0' AND fld_delstatus='0'");//0=>Completed; 1=>Inprogress for task checkbox  AND fld_schedule_id='".$stdtaskid[0]."'
                                                                    }
                                                                    else
                                                                    {
                                                                        $resinpcnt1=1;

                                                                    }

                                                                    $qrysessiontime=$ObjDB->QueryObject("SELECT a.varValue AS sessiontime FROM itc_exp_scorm_track as a
                                                                                                            JOIN itc_exp_res_play_track as b on b.fld_id=a.SCOInstanceID
                                                                                                            WHERE a.varName='cmi.core.session_time' AND b.fld_student_id='".$stdtaskid[1]."' AND b.fld_res_id='".$resid."' AND b.fld_schedule_id='".$stdtaskid[0]."' AND b.fld_schedule_type='15' AND b.fld_read_status='1'");
                                                                    if($qrysessiontime->num_rows>0)
                                                                    {
                                                                        while($rowsessiontime=$qrysessiontime->fetch_assoc())
                                                                        {
                                                                            extract($rowsessiontime);
                                                                            $arrtime[]=$sessiontime;
                                                                        }
                                                                    }
                                                                    $sessiontime1='';
                                                                    for($i=0;$i<sizeof($arrtime);$i++)
                                                                    {  
                                                                        $j = $i+1;
                                                                        if($sessiontime1=='')
                                                                            $sessiontime1="$j. ".$arrtime[$i];
                                                                        else
                                                                            $sessiontime1=$sessiontime1."\n"."$j. ".$arrtime[$i];
                                                                    } 

                                                                    ?>
                                                                    <li>
                                                                        <input title="<?php echo $sessiontime1;?>" type="checkbox" class="resd select_<?php echo $taskid ;?> rescls_<?php echo $resid ;?> destresid_<?php echo $destid; ?>" id="res_<?php echo $taskid."~".$resid."~".$destid;?>" <?php if($resinpcnt1==0){ echo 'checked="checked"';}else{ echo 'disabled="disabled"';} ?>>
                                                                        <label title="<?php echo $sessiontime1;?>"><?php echo $resname;?></label>
                                                                    </li>
                                                                    <script>
                                                                        $(".select").click(function(e){ 
                                                                            var tid=this.id;
                                                                            var untask = tid.split("_");
                                                                            var tid=tid.replace("tsk_", "res_"); 
                                                                           $('.ZebraDialogOverlay').css("z-index","20000");
                                                                           $('.ZebraDialog').css("z-index","20000");
                                                                           $('.ZebraDialogOverlay').removeClass('ZebraDialogOverlay').addClass('ZebraDialogOverlay1');
                                                                           $('#gantt_here').css("opacity","0.40");
                                                                           $('#fancybox-wrap').css("opacity","0.40");
                                                                           $.Zebra_Dialog('Are you sure you want to assign this task again?',
                                                                           {
                                                                               'type': 'confirmation',
                                                                               'buttons': [
                                                                                   {caption: 'No', callback: function() { 
                                                                                       var tid1=tid.replace("res_", "");
                                                                                       tid1=tid1.split("~");
                                                                                       $('.destselect_'+tid1[0]).prop('checked', true);
                                                                                       $('.destselect_'+tid1[0]).prop('disabled', false);
                                                                                       $('.ZebraDialog').remove();
                                                                                       $('.ZebraDialogOverlay1').remove();
                                                                                       $('#gantt_here').css("opacity", "");
                                                                                       $('#fancybox-wrap').css("opacity", "");
                                                                                   }},
                                                                                   {caption: 'Yes', callback: function() {
                                                                                       fn_taskuncheck(untask[1],'<?php echo $stdtaskid[1];?>','<?php echo $stdtaskid[0];?>','<?php echo $expid;?>','<?php echo $stdtaskid[3];?>',15); 
                                                                                       $('.ZebraDialogOverlay').removeClass('ZebraDialogOverlay').addClass('ZebraDialogOverlay1');
                                                                                       $('.ZebraDialog').remove();
                                                                                       $('.ZebraDialogOverlay1').remove();
                                                                                       $('#gantt_here').css("opacity", "");
                                                                                       $('#fancybox-wrap').css("opacity", "");
                                                                                       var tid1=tid.replace("res_", "");
                                                                                       tid1=tid1.split("~");
                                                                                       $('.destselect_'+tid1[0]).prop('checked', false);
                                                                                       $('.destselect_'+tid1[0]).prop('disabled', true);
                                                                                       $('.select_'+tid1[0]).each(function(){
                                                                                       $('#dest_'+tid1[1]).prop('checked', false);
                                                                                       $('#dest_'+tid1[1]).prop('disabled', true);
                                                                                       $('.select_'+tid1[0]).prop('checked', false);
                                                                                       $('.select_'+tid1[0]).prop('disabled', true);
                                                                                      });
                                                                                   }},
                                                                               ]
                                                                           });
                                                                          });

                                                                       $(".resd").click(function(e){  
                                                                           var id=this.id;
                                                                           var unres = id.split("_");
                                                                           var reid=id.replace("res_", "");
                                                                           $('.ZebraDialogOverlay').css("z-index","20000");
                                                                           $('.ZebraDialog').css("z-index","20000");
                                                                           $('.ZebraDialogOverlay').removeClass('ZebraDialogOverlay').addClass('ZebraDialogOverlay1');
                                                                           $('#gantt_here').css("opacity","0.40");
                                                                           $('#fancybox-wrap').css("opacity","0.40");
                                                                           $.Zebra_Dialog('Are you sure you want to assign this resource again?',
                                                                           {
                                                                               'type': 'confirmation',
                                                                               'buttons': [
                                                                                   {caption: 'No', callback: function() {  
                                                                                       var resid1=reid.split("~");
                                                                                       $('.rescls_'+resid1[1]).prop('checked', true);
                                                                                       $('.rescls_'+resid1[1]).prop('disabled', false);
                                                                                       $('#tsk_<?php echo $taskid;?>').attr('checked', true);
                                                                                       $('#tsk_<?php echo $taskid;?>').attr('disabled', false);
                                                                                       $('#dest_<?php echo $destid; ?>').attr('checked', true);
                                                                                       $('#dest_<?php echo $destid; ?>').attr('disabled', false);
                                                                                       $('.ZebraDialog').remove();
                                                                                       $('.ZebraDialogOverlay1').remove();
                                                                                       $('#gantt_here').css("opacity", "");
                                                                                       $('#fancybox-wrap').css("opacity", "");
                                                                                   }},
                                                                                   {caption: 'Yes', callback: function() {
                                                                                       fn_resuncheck(unres[1],'<?php echo $stdtaskid[1];?>','<?php echo $stdtaskid[0];?>','<?php echo $expid;?>','<?php echo $stdtaskid[3];?>',15);
                                                                                       $('.ZebraDialogOverlay').removeClass('ZebraDialogOverlay').addClass('ZebraDialogOverlay1');
                                                                                       $('.ZebraDialog').remove();
                                                                                       $('.ZebraDialogOverlay1').remove();
                                                                                       $('#gantt_here').css("opacity", "");
                                                                                       $('#fancybox-wrap').css("opacity", ""); 
                                                                                       var reid=id.replace("res_", "");
                                                                                       var reid = reid.split("~");
                                                                                       $('.rescls_'+reid[1]).prop('checked', false);
                                                                                       $('.rescls_'+reid[1]).prop('disabled', true);
                                                                                       $('.destselect_'+reid[0]).prop('checked', false);
                                                                                       $('.destselect_'+reid[0]).prop('disabled', true);
                                                                                       $('#dest_'+reid[2]).attr('checked', false);
                                                                                       $('#dest_'+reid[2]).attr('disabled', true);
                                                                                   }},
                                                                               ]
                                                                           });
                                                                       });


                                                                       $(".destselect").click(function(e){ 
                                                                           var dstid=this.id;
                                                                           var undest = dstid.split("_");
                                                                           var dstid=dstid.replace("dest_", "tsk_");
                                                                           $('.ZebraDialogOverlay').css("z-index","20000");
                                                                           $('.ZebraDialog').css("z-index","20000");
                                                                           $('.ZebraDialogOverlay').removeClass('ZebraDialogOverlay').addClass('ZebraDialogOverlay1');
                                                                           $('#gantt_here').css("opacity","0.40");
                                                                           $('#fancybox-wrap').css("opacity","0.40");
                                                                           $.Zebra_Dialog('Are you sure you want to assign this destination again?',
                                                                           {
                                                                               'type': 'confirmation',
                                                                               'buttons': [
                                                                                   {caption: 'No', callback: function() {  

                                                                                      var destid1=dstid.replace("tsk_", "");
                                                                                       $('#dest_'+destid1).prop('checked', true);
                                                                                       $('#dest_'+destid1).prop('disabled', false);
                                                                                       $('.ZebraDialog').remove();
                                                                                       $('.ZebraDialogOverlay1').remove();
                                                                                       $('#gantt_here').css("opacity", "");
                                                                                       $('#fancybox-wrap').css("opacity", "");
                                                                                   }},
                                                                                   {caption: 'Yes', callback: function() {
                                                                                       fn_destuncheck(undest[1],'<?php echo $stdtaskid[1];?>','<?php echo $stdtaskid[0];?>','<?php echo $expid;?>','<?php echo $stdtaskid[3];?>',15);
                                                                                       $('.ZebraDialogOverlay').removeClass('ZebraDialogOverlay').addClass('ZebraDialogOverlay1');
                                                                                       $('.ZebraDialog').remove();
                                                                                       $('.ZebraDialogOverlay1').remove();
                                                                                       $('#gantt_here').css("opacity", "");
                                                                                       $('#fancybox-wrap').css("opacity", ""); 
                                                                                       var dstid1=dstid.replace("tsk_", "");
                                                                                       $('#dest_'+dstid1).prop('checked', false);
                                                                                       $('#dest_'+dstid1).prop('disabled', true);
                                                                                       $('.destclsid_'+dstid1).each(function(){
                                                                                            $('.destclsid_'+dstid1).prop('checked', false);
                                                                                            $('.destclsid_'+dstid1).prop('disabled', true);
                                                                                            $('.destresid_'+dstid1).prop('checked', false);
                                                                                            $('.destresid_'+dstid1).prop('disabled', true);
                                                                                       });
                                                                                   }},
                                                                               ]
                                                                           });
                                                                         });
                                                                    </script>
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </ul>
                                                </li>
                                            <?php
                                            } //$tstatus
                                        }
                                    }
                                    ?>
                                </ul>
                            </li>
                        </ul>
                        <?php
                    }
                }
                ?>
            </div>
            <?php
        }
    }  
    //Expedition Code Start Here
    
    //Expedition schedule Code Start Here
    else if($stdtaskid[3]==3) 
    {
        
        $expid=$stdtaskid[4];
        
        if($stdtaskid[5]==19)
        {
            $qryscheduledetailsrot=$ObjDB->QueryObject("SELECT fld_schedule_name AS schedulename,
                                                            DATE_FORMAT(fld_startdate, '%d-%m-%Y') AS sch_stdaterot,DATE_FORMAT(fld_enddate, '%d-%m-%Y') AS sch_enddaterot   
                                                            FROM itc_class_rotation_expschedule_mastertemp where fld_id='".$stdtaskid[0]."' and fld_delstatus='0'");
                
            /************** Pre/Post test code start here ***************/
            $pointsearnedfortest=0;
            $possiblepointfortest1=0;
            $possiblepointfortest=0;

            $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                        WHERE fld_class_id='".$stdtaskid[2]."' AND fld_sch_id='".$stdtaskid[0]."' AND fld_texpid='".$expid."' AND fld_schtype_id='19'");

            if($qrytest->num_rows>0)
            {
                while($rowqrytest = $qrytest->fetch_assoc())
                {
                    extract($rowqrytest);
                    $exptype='3';

                    /*********Pre Test Code start Here*********/
                    if($pretest!='0')
                    {
                        $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount
                                                    FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_delstatus='0'");
                        if($qry->num_rows>0)
                        {
                            while($rowqry = $qry->fetch_assoc())
                            {
                                extract($rowqry);

                                $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                $tchpointcnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_points_master WHERE fld_student_id='".$stdtaskid[1]."' AND fld_exp_id='".$expid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='".$stdtaskid[5]."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                if($tchpointcnt==0)
                                {
                                    $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                           LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                           WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$stdtaskid[1]."' AND a.fld_test_id='".$testid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='".$stdtaskid[5]."'
                                                                                           AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");

                                   if($correctcountfortest != '0')
                                   {
                                        $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                        $possiblepointfortest+=$possiblepointfortest1;
                                   }
                                }
                                else
                                {
                                    $tchpointearn = $ObjDB->SelectSingleValueInt("SELECT (CASE
                                                                                    WHEN fld_lock = '0' THEN fld_points_earned
                                                                                    WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                            END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$stdtaskid[1]."'
                                                                                    AND fld_exp_id='".$expid."' AND fld_schedule_id='".$stdtaskid[0]."' 
                                                                                    AND fld_schedule_type='".$stdtaskid[5]."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                    if($tchpointearn !=0)
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
                        $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount
                                                        FROM itc_test_master WHERE fld_id='".$posttest."' AND fld_delstatus='0'");
                        if($qry->num_rows>0)
                        {
                            while($rowqry = $qry->fetch_assoc())
                            {
                                extract($rowqry);

                                $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                $tchpointcnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_points_master WHERE fld_student_id='".$stdtaskid[1]."' AND fld_exp_id='".$expid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='".$stdtaskid[5]."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                if($tchpointcnt==0)
                                {
                                    $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                           LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                           WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$stdtaskid[1]."' AND a.fld_test_id='".$testid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='".$stdtaskid[5]."'
                                                                                           AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");

                                   if($correctcountfortest != '0')
                                   {
                                        $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                        $possiblepointfortest+=$possiblepointfortest1;
                                   }
                                }
                                else
                                {
                                    $tchpointearn = $ObjDB->SelectSingleValueInt("SELECT (CASE
                                                                                    WHEN fld_lock = '0' THEN fld_points_earned
                                                                                    WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                            END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$stdtaskid[1]."'
                                                                                    AND fld_exp_id='".$expid."' AND fld_schedule_id='".$stdtaskid[0]."' 
                                                                                    AND fld_schedule_type='".$stdtaskid[5]."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                    if($tchpointearn !=0)
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
            $pointsearnedrubric=0;
            $pointspossiblerubric=0;
            $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                LEFT JOIN itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                                LEFT JOIN itc_class_rotation_expschedule_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
                                                WHERE a.fld_class_id='".$stdtaskid[2]."' AND a.fld_schedule_id='".$stdtaskid[0]."' AND b.fld_exp_id='".$expid."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
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
                                                                                AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$stdtaskid[2]."' 
                                                                                AND fld_schedule_id='".$stdtaskid[0]."' AND fld_delstatus='0' "); 

                    $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_expsch_rubric_rpt_statement
                                                                            WHERE fld_student_id='".$stdtaskid[1]."' AND fld_exp_id='".$expid."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
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
                $grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$stdtaskid[2]."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
            }
            if($percentage==0)
            {
                $percentage = "-";
                $grade = "NA";
                $pointsearned = "-";
                $pointspossible="-";
            }
            //percentage code end here
        }
        else
        {
            $qryscheduledetailsrot=$ObjDB->QueryObject("SELECT fld_schedule_name AS schedulename,
                                                            DATE_FORMAT(fld_startdate, '%d-%m-%Y') AS sch_stdaterot,DATE_FORMAT(fld_enddate, '%d-%m-%Y') AS sch_enddaterot   
                                                            FROM itc_class_rotation_modexpschedule_mastertemp where fld_id='".$stdtaskid[0]."' and fld_delstatus='0'");
            
            /************** Pre/Post test code start here ***************/
            $pointsearnedfortest=0;
            $possiblepointfortest1=0;
            $possiblepointfortest=0;
            $exptype='20';

            $qrytest = $ObjDB->QueryObject("SELECT fld_pretest AS pretest,fld_posttest AS posttest,fld_texpid AS expid FROM itc_exp_ass 
                                    WHERE fld_class_id='".$stdtaskid[2]."' AND fld_sch_id='".$stdtaskid[0]."' AND fld_texpid='".$expid."' AND fld_schtype_id='20'");

            if($qrytest->num_rows>0)
            {
                while($rowqrytest = $qrytest->fetch_assoc())
                {
                    extract($rowqrytest);

                    /*********Pre Test Code start Here*********/
                    if($pretest!='0')
                    {
                        $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint
                                                    FROM itc_test_master WHERE fld_id='".$pretest."' AND fld_delstatus='0'");
                        if($qry->num_rows>0)
                        {
                            while($rowqry = $qry->fetch_assoc())
                            {
                                extract($rowqry);

                                $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                $tchpointcnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_points_master WHERE fld_student_id='".$stdtaskid[1]."' AND fld_exp_id='".$expid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='".$exptype."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                if($tchpointcnt==0)
                                {
                                    $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                    LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                    WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$stdtaskid[1]."' AND a.fld_test_id='".$testid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='".$exptype."'
                                                                                                    AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");

                                   if($correctcountfortest != '0')
                                   {
                                        $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                        $possiblepointfortest+=$possiblepointfortest1;
                                   }
                                }
                                else
                                {
                                    $tchpointearn = $ObjDB->SelectSingleValueInt("SELECT (CASE
                                                                                        WHEN fld_lock = '0' THEN fld_points_earned
                                                                                        WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                        END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$stdtaskid[1]."'
                                                                                        AND fld_exp_id='".$expid."' AND fld_schedule_id='".$stdtaskid[0]."' 
                                                                                        AND fld_schedule_type='".$exptype."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                    if($tchpointearn !=0)
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
                        $qry = $ObjDB->QueryObject("SELECT fld_id AS testid,fld_test_name as testname,fld_total_question AS quescount,fld_score AS possiblepoint
                                                    FROM itc_test_master WHERE fld_id='".$posttest."' AND fld_delstatus='0'");
                        if($qry->num_rows>0)
                        {
                            while($rowqry = $qry->fetch_assoc())
                            {
                                extract($rowqry);

                                $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                $tchpointcnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_points_master WHERE fld_student_id='".$stdtaskid[1]."' AND fld_exp_id='".$expid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='".$exptype."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                if($tchpointcnt==0)
                                {
                                    $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                    LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                    WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$stdtaskid[1]."' AND a.fld_test_id='".$testid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='".$exptype."'
                                                                                                    AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");

                                   if($correctcountfortest != '0')
                                   {
                                        $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                                        $possiblepointfortest+=$possiblepointfortest1;
                                   }
                                }
                                else
                                {
                                    $tchpointearn = $ObjDB->SelectSingleValueInt("SELECT (CASE
                                                                                        WHEN fld_lock = '0' THEN fld_points_earned
                                                                                        WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                        END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$stdtaskid[1]."'
                                                                                        AND fld_exp_id='".$expid."' AND fld_schedule_id='".$stdtaskid[0]."' 
                                                                                        AND fld_schedule_type='".$exptype."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                    if($tchpointearn !=0)
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
            $pointsearnedrubric=0;
            $pointspossiblerubric=0;

            $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                                CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                LEFT JOIN itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                                LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
                                                    WHERE a.fld_class_id='".$stdtaskid[2]."' AND a.fld_schedule_id='".$stdtaskid[0]."' AND b.fld_exp_id='".$expid."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                        AND a.fld_schedule_type='20' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                        AND b.fld_school_id IN(0,".$schoolid.")");

            if($qryrub->num_rows>0)
            {
                while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
                {
                    extract($rowqryrub);

                     $destinationids = $ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_exp_rubric_dest_master WHERE fld_rubric_name_id='".$rubricids."' AND fld_exp_id='".$expid."'"); 

                    $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master 
                                                            WHERE fld_exp_id='".$expid."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                    $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_expmodsch_rubric_rpt WHERE fld_exp_id='".$expid."'  
                                                                        AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$stdtaskid[2]."' 
                                                                        AND fld_schedule_id='".$stdtaskid[0]."' AND fld_delstatus='0' "); 

                    $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_expmodsch_rubric_rpt_statement
                                                                WHERE fld_student_id='".$stdtaskid[1]."' AND fld_exp_id='".$expid."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
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
                $grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$stdtaskid[2]."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
            }
            if($percentage==0)
            {
                $percentage = "-";
                $grade = "NA";
                $pointsearned = "-";
                $pointspossible="-";
            }
            //percentage code end here
        }
        
        
        if($qryscheduledetailsrot->num_rows>0)
        {
            $rowschdet=$qryscheduledetailsrot->fetch_assoc();
            extract($rowschdet);
        }
        
        $roundflag = $ObjDB->SelectSingleValue("SELECT fld_roundflag
                                            FROM itc_class_grading_scale_mapping
                                            WHERE fld_class_id = '".$stdtaskid[2]."' AND fld_flag = '1'
                                            GROUP BY fld_roundflag");


        $checkrstatusid = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_school_id='".$senshlid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
        if($checkrstatusid == '0')
        {
            $resourcegroupids=$ObjDB->SelectSingleValue("select 
                                                         GROUP_CONCAT(cnt.fld_id) 
                                                     from
                                                         (SELECT 
                                                             a.fld_id
                                                         FROM
                                                             itc_exp_resource_master AS a
                                                         LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                         LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                         LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                         LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                         LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                         LEFT JOIN itc_class_rotation_expschedule_mastertemp as g ON e.fld_license_id = g.fld_license_id
                                                         where
                                                             c.fld_exp_id = '".$expid."'
                                                                 AND g.fld_id = '".$stdtaskid[0]."'
                                                                 AND d.fld_school_id = '0'
                                                                 AND d.fld_user_id = '0'
                                                                 and d.fld_status = '1'
                                                                 and a.fld_delstatus = '0'
                                                                 and b.fld_delstatus = '0'
                                                                 and c.fld_delstatus = '0'
                                                         GROUP BY a.fld_id) as cnt");


            $rescomplete = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id IN (".$resourcegroupids.") AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='".$stdtaskid[5]."' AND fld_delstatus='0' AND fld_student_id='".$stdtaskid[1]."' AND fld_read_status='1'");
            $totalresource=sizeof(explode(',',$resourcegroupids));

        }
        else
        {
        $resourcegroupids=$ObjDB->SelectSingleValue("select 
                                                        GROUP_CONCAT(cnt.fld_id) 
                                                    from
                                                        (SELECT 
                                                            a.fld_id
                                                        FROM
                                                            itc_exp_resource_master AS a
                                                        LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                        LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                        LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                        LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                        LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                        LEFT JOIN itc_class_rotation_expschedule_mastertemp as g ON e.fld_license_id = g.fld_license_id
                                                        where
                                                            c.fld_exp_id = '".$expid."'
                                                                AND g.fld_id = '".$stdtaskid[0]."'
                                                                AND d.fld_school_id = '".$senshlid."'
                                                                AND d.fld_created_by='".$uid."'
                                                                AND d.fld_user_id = '".$indid."'
                                                                and d.fld_status = '1'
                                                                and a.fld_delstatus = '0'
                                                                and b.fld_delstatus = '0'
                                                                and c.fld_delstatus = '0'
                                                        GROUP BY a.fld_id) as cnt");


           $rescomplete = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id IN (".$resourcegroupids.") AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='".$stdtaskid[5]."' AND fld_delstatus='0' AND fld_student_id='".$stdtaskid[1]."' AND fld_read_status='1'");
           $totalresource=sizeof(explode(',',$resourcegroupids));
        }       
        if($totalresource==0)
        {
            $completeprogress=0;
        }
        else if($totalresource!=0)
        {
            $completeprogress=($rescomplete/$totalresource)*100;
            $completeprogress=round($completeprogress,2);
        }
        ?>
        <div style="width:580px; margin-top: 50px; margin-bottom: 50px;">
            <table cellpadding="0" border="0" cellspacing="0" width="60%" align="center">
                <tr>
                    <td style="width:165px;">
                        Schedule Name:
                    </td>
                    <td>
                        <?php echo $schedulename; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Start Date:
                    </td>
                    <td>
                        <?php echo $sch_stdaterot; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        End Date:
                    </td>
                    <td>
                        <?php echo $sch_enddaterot; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Assignment Marks:
                    </td>
                    <td>
                        <?php echo $pointsearned."/".$pointspossible; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Assignment Percentage:
                    </td>
                    <td>
                        <?php echo $percentage; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Grade:
                    </td>
                    <td>
                        <?php echo $grade; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Complete Progress:
                    </td>
                    <td>
                        <?php echo $completeprogress."%"; ?>
                    </td>
                </tr>
            </table>
        </div>

        <?php
        if($stdtaskid[5]==19)
        {
        $qrydestdetails=$ObjDB->QueryObject("SELECT a.fld_id as destid, a.fld_dest_name AS destname
                                            FROM itc_exp_destination_master as a
                                            LEFT JOIN itc_license_exp_mapping AS b ON a.fld_id = b.fld_dest_id
                                            LEFT JOIN itc_license_track AS c ON b.fld_license_id = c.fld_license_id
                                            LEFT JOIN itc_class_rotation_expschedule_mastertemp as d on b.fld_license_id=d.fld_license_id
                                            WHERE a.fld_exp_id = '".$expid."' AND d.fld_id = '".$stdtaskid[0]."' AND c.fld_user_id='".$indid."' 
                                                AND c.fld_school_id='".$schoolid."' AND a.fld_delstatus = '0' GROUP BY destid");
        }
        else
        {
            $qrydestdetails=$ObjDB->QueryObject("SELECT a.fld_id as destid, a.fld_dest_name AS destname
                                            FROM itc_exp_destination_master as a
                                            LEFT JOIN itc_license_exp_mapping AS b ON a.fld_id = b.fld_dest_id
                                            LEFT JOIN itc_license_track AS c ON b.fld_license_id = c.fld_license_id
                                            LEFT JOIN itc_class_rotation_modexpschedule_mastertemp as d on b.fld_license_id=d.fld_license_id
                                            WHERE a.fld_exp_id = '".$expid."' AND d.fld_id = '".$stdtaskid[0]."' AND c.fld_user_id='".$indid."' 
                                                AND c.fld_school_id='".$schoolid."' AND a.fld_delstatus = '0' GROUP BY destid");
        }
        if($qrydestdetails->num_rows>0)
        {
            ?>
            <div style="height:400px; overflow:auto;">
                <?php
                while($rowdestdetails = $qrydestdetails->fetch_assoc())
                {
                    extract($rowdestdetails);

                    $checkshlstatus = $ObjDB->SelectSingleValue("select count(fld_id) from itc_exp_res_status where fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' and fld_exp_id='".$expid."'");
                    if($checkshlstatus == '0')
                    {
                        $schoolid ='0';
                    }

                    /* For checking deatination, task and resoures are completed whenever student is enter into expedition - Karthi*/ 
                    $fieldtask1 = 'CONCAT("\'",a.fld_id,"\'")';
                    $grouptaskids1 = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(".$fieldtask1.") 
                                            FROM itc_exp_task_master as a 
                                            LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_task_id
                                            WHERE a.fld_dest_id='".$destid."' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$uid."' AND b.fld_user_id='".$indid."' AND b.fld_status='1' AND a.fld_delstatus='0'");

                    $selecttasks = $ObjDB->QueryObject("SELECT a.fld_id AS taskid 
                                                        FROM itc_exp_task_master AS a
                                                        LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_task_id
                                                        WHERE a.fld_dest_id='".$destid."' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$uid."' AND b.fld_user_id='".$indid."' AND b.fld_status='1' AND a.fld_delstatus='0' ORDER BY a.fld_order");
                    if($selecttasks->num_rows>0) 
                    {
                        while ($rowselecttasks = $selecttasks->fetch_assoc()) 
                        {
                            extract($rowselecttasks);
                            $fieldresvar1 = 'CONCAT("\'",a.fld_id,"\'")';
                            $groupresids1 = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(".$fieldresvar1.") 
                                                                        FROM itc_exp_resource_master As a
                                                                        LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_res_id
                                                                        WHERE a.fld_task_id='".$taskid."' AND a.fld_delstatus='0' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$uid."' AND b.fld_user_id='".$indid."' AND b.fld_status='1'");

                            $resreadcnt1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id IN (".$groupresids1.") AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='".$stdtaskid[5]."' AND fld_delstatus='0' AND fld_student_id='".$stdtaskid[1]."' AND fld_read_status='1'");

                            if($resreadcnt1 == sizeof(explode(',',$groupresids1)))
                            {
                                $ObjDB->NonQuery("UPDATE itc_exp_task_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_task_id='".$taskid."' AND fld_delstatus='0' AND fld_student_id='".$stdtaskid[1]."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='".$stdtaskid[5]."'");

                                $taskreadcnt1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_task_play_track WHERE fld_task_id IN (".$grouptaskids1.") AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='".$stdtaskid[5]."' AND fld_delstatus='0' AND fld_student_id='".$stdtaskid[1]."' AND fld_read_status='1'");

                                if($taskreadcnt1 === sizeof(explode(',',$grouptaskids1)))
                                {
                                    $ObjDB->NonQuery("UPDATE itc_exp_dest_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_dest_id='".$destid."' AND fld_delstatus='0' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='".$stdtaskid[5]."' AND fld_student_id='".$stdtaskid[1]."'");
                                }
                            }
                        }
                    }
                    /* Ends */

                    $dstatusid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_dest_id='".$destid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");

                    if($dstatusid != ''  and $dstatusid != NULL and $dstatusid != '0')
                    {
                        $dstatus = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_id='".$dstatusid."' AND fld_dest_id='".$destid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                    }          
                    else if($dstatus=='' or $dstatus=='0' or $dstatus==NULL)
                    {
                        $createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
                        $dstatus = $ObjDB->SelectSingleValue("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_dest_id='".$destid."' AND fld_created_by IN (".$createdids.") AND fld_flag='1'");
                    }

                    if($dstatus == 1)
                    {
                        $taskcount=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_exp_dest_play_track 
                                                                    WHERE fld_student_id='".$stdtaskid[1]."' AND fld_exp_id='".$expid."' 
                                                                    AND fld_dest_id='".$destid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='".$stdtaskid[5]."' AND fld_delstatus='0'");//AND fld_schedule_id='$stdtaskid[0]' 
                        if($taskcount>0)
                        {
                            $taskinpcnt=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_exp_dest_play_track WHERE fld_student_id='".$stdtaskid[1]."' AND fld_exp_id='".$expid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='".$stdtaskid[5]."' AND fld_dest_id='".$destid."'
                                                                        AND fld_read_status='0' AND fld_delstatus='0'");//count 0=>Completed; 1=>Inprogress for destination checkbox  - AND fld_schedule_id='$stdtaskid[0]' 
                        }
                        else
                        {
                            $taskinpcnt=1;

                        }
                        ?>
                        <ul class="tree" style="margin-left: 15px;">
                            <li>
                                <input type="checkbox" value="0" class="destcls destselect" id="dest_<?php echo $destid; ?>" <?php if($taskinpcnt==0){ echo 'checked="checked"';}else{ echo 'disabled="disabled"';} ?>>
                                <label><?php echo $destname;?></label>
                                <ul class='expanded'>
                                    <?php        
                                    $qrytaskdetails=$ObjDB->QueryObject("SELECT fld_id as taskid,fld_task_name AS taskname 
                                                                        FROM itc_exp_task_master 
                                                                        WHERE fld_dest_id='".$destid."' AND fld_delstatus='0'");
                                    if($qrytaskdetails->num_rows>0)
                                    {
                                        while($rowtaskdetails = $qrytaskdetails->fetch_assoc())
                                        {
                                            extract($rowtaskdetails);
                                            $tstatusid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_task_id='".$taskid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");

                                            if($tstatusid != ''  and $tstatusid != NULL and $tstatusid != '0')
                                            {
                                                $tstatus = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_id='".$tstatusid."' AND fld_task_id='".$taskid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                                            }
                                            else if($tstatus=='' or $tstatus=='0' or $tstatus==NULL)
                                            {
                                                $createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
                                                $tstatus = $ObjDB->SelectSingleValue("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_task_id='".$taskid."' AND fld_created_by IN (".$createdids.") AND fld_flag='1'");
                                            }

                                            if($tstatus == 1)
                                            { 
                                                $rescount=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_exp_task_play_track 
                                                                                    WHERE fld_student_id='".$stdtaskid[1]."' AND fld_exp_id='".$expid."'  
                                                                                    AND fld_dest_id='".$destid."' AND fld_task_id='".$taskid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='".$stdtaskid[5]."' AND fld_delstatus='0'");//AND fld_schedule_id='".$stdtaskid[0]."'
                                                if($rescount>0)
                                                {
                                                    $resinpcnt=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_exp_task_play_track WHERE fld_student_id='".$stdtaskid[1]."' AND fld_exp_id='".$expid."' AND fld_dest_id='".$destid."' AND fld_task_id='".$taskid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='".$stdtaskid[5]."'
                                                                                                AND fld_read_status='0' AND fld_delstatus='0'");//0=>Completed; 1=>Inprogress for task checkbox - AND fld_schedule_id='".$stdtaskid[0]."' 
                                                }
                                                else
                                                {
                                                    $resinpcnt=1;

                                                }

                                                ?>
                                                 <li>
                                                    <input type="checkbox"  class="tskcls select destselect_<?php echo $taskid; ?> destclsid_<?php echo $destid; ?>" id="tsk_<?php echo $taskid."~".$destid; ?>" <?php if($resinpcnt==0){ echo 'checked="checked"';}else{ echo 'disabled="disabled"';} ?>>
                                                    <label><?php echo $taskname;?></label>
                                                    <ul>
                                                    <?php 
            
                                                    $qryresourcedetails=$ObjDB->QueryObject("SELECT fld_id AS resid,fld_res_name AS resname
                                                                                           FROM itc_exp_resource_master
                                                                                           WHERE fld_task_id='".$taskid."' AND fld_delstatus='0'");

                                                    if($qryresourcedetails->num_rows>0)
                                                    {
                                                            while($rowresourcedetails = $qryresourcedetails->fetch_assoc())
                                                            {
                                                                extract($rowresourcedetails);
                                                                $rstatusid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_res_id='".$resid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                                                                if($rstatusid != ''  and $rstatusid != NULL and $rstatusid != '0'){
                                                                    $rstatus = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_id='".$rstatusid."' AND fld_res_id='".$resid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                                                                }
                                                                if($rstatus=='' or $rstatus=='0' or $rstatus==NULL)
                                                                {
                                                                    $createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");

                                                                    $rstatus = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_res_id='".$resid."' AND fld_created_by IN (".$createdids.") AND fld_flag='1'");
                                                                }
                                                                if($rstatus == 1)
                                                                {
                                                                    $rescount1=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_exp_res_play_track 
                                                                                    WHERE fld_student_id='".$stdtaskid[1]."' AND fld_exp_id='".$expid."'  
                                                                                    AND fld_dest_id='".$destid."' AND fld_task_id='".$taskid."' AND fld_res_id='".$resid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='".$stdtaskid[5]."' AND fld_delstatus='0'");//AND fld_schedule_id='".$stdtaskid[0]."'
                                                                    if($rescount1>0)
                                                                    {
                                                                        $resinpcnt1=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_exp_res_play_track 
                                                                                                            WHERE fld_student_id='".$stdtaskid[1]."' AND fld_exp_id='".$expid."'  
                                                                                                            AND fld_dest_id='".$destid."' AND fld_task_id='".$taskid."' AND fld_res_id='".$resid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='".$stdtaskid[5]."' AND fld_read_status='0' AND fld_delstatus='0'");//0=>Completed; 1=>Inprogress for task checkbox  AND fld_schedule_id='".$stdtaskid[0]."'
                                                                    }
                                                                    else
                                                                    {
                                                                        $resinpcnt1=1;

                                                                    }

                                                                    $qrysessiontime=$ObjDB->QueryObject("SELECT a.varValue AS sessiontime FROM itc_exp_scorm_track as a
                                                                                                            JOIN itc_exp_res_play_track as b on b.fld_id=a.SCOInstanceID
                                                                                                            WHERE a.varName='cmi.core.session_time' AND b.fld_student_id='".$stdtaskid[1]."' AND b.fld_res_id='".$resid."' AND b.fld_schedule_id='".$stdtaskid[0]."' AND b.fld_schedule_type='".$stdtaskid[5]."' AND b.fld_read_status='1'");
                                                                    if($qrysessiontime->num_rows>0)
                                                                    {
                                                                        while($rowsessiontime=$qrysessiontime->fetch_assoc())
                                                                        {
                                                                            extract($rowsessiontime);
                                                                            $arrtime[]=$sessiontime;
                                                                        }
                                                                    }
                                                                    $sessiontime1='';
                                                                    for($i=0;$i<sizeof($arrtime);$i++)
                                                                    {  
                                                                        $j = $i+1;
                                                                        if($sessiontime1=='')
                                                                            $sessiontime1="$j. ".$arrtime[$i];
                                                                        else
                                                                            $sessiontime1=$sessiontime1."\n"."$j. ".$arrtime[$i];
                                                                    } 

                                                                    ?>
                                                                    <li>
                                                                        <input title="<?php echo $sessiontime1;?>" type="checkbox" class="resd select_<?php echo $taskid ;?> rescls_<?php echo $resid ;?> destresid_<?php echo $destid; ?>" id="res_<?php echo $taskid."~".$resid."~".$destid;?>" <?php if($resinpcnt1==0){ echo 'checked="checked"';}else{ echo 'disabled="disabled"';} ?>>
                                                                        <label title="<?php echo $sessiontime1;?>"><?php echo $resname;?></label>
                                                                    </li>
                                                                    <script>
                                                                        $(".select").click(function(e){ 
                                                                            var tid=this.id;
                                                                            var untask = tid.split("_");
                                                                            var tid=tid.replace("tsk_", "res_"); 
                                                                           $('.ZebraDialogOverlay').css("z-index","20000");
                                                                           $('.ZebraDialog').css("z-index","20000");
                                                                           $('.ZebraDialogOverlay').removeClass('ZebraDialogOverlay').addClass('ZebraDialogOverlay1');
                                                                           $('#gantt_here').css("opacity","0.40");
                                                                           $('#fancybox-wrap').css("opacity","0.40");
                                                                           $.Zebra_Dialog('Are you sure you want to assign this task again?',
                                                                           {
                                                                               'type': 'confirmation',
                                                                               'buttons': [
                                                                                   {caption: 'No', callback: function() { 
                                                                                       var tid1=tid.replace("res_", "");
                                                                                       tid1=tid1.split("~");
                                                                                       $('.destselect_'+tid1[0]).prop('checked', true);
                                                                                       $('.destselect_'+tid1[0]).prop('disabled', false);
                                                                                       $('.ZebraDialog').remove();
                                                                                       $('.ZebraDialogOverlay1').remove();
                                                                                       $('#gantt_here').css("opacity", "");
                                                                                       $('#fancybox-wrap').css("opacity", "");
                                                                                   }},
                                                                                   {caption: 'Yes', callback: function() {
                                                                                       fn_taskuncheck(untask[1],'<?php echo $stdtaskid[1];?>','<?php echo $stdtaskid[0];?>','<?php echo $expid;?>','<?php echo $stdtaskid[3];?>','<?php echo $stdtaskid[5];?>'); 
                                                                                       $('.ZebraDialogOverlay').removeClass('ZebraDialogOverlay').addClass('ZebraDialogOverlay1');
                                                                                       $('.ZebraDialog').remove();
                                                                                       $('.ZebraDialogOverlay1').remove();
                                                                                       $('#gantt_here').css("opacity", "");
                                                                                       $('#fancybox-wrap').css("opacity", "");
                                                                                       var tid1=tid.replace("res_", "");
                                                                                       tid1=tid1.split("~");
                                                                                       $('.destselect_'+tid1[0]).prop('checked', false);
                                                                                       $('.destselect_'+tid1[0]).prop('disabled', true);
                                                                                       $('.select_'+tid1[0]).each(function(){
                                                                                       $('#dest_'+tid1[1]).prop('checked', false);
                                                                                       $('#dest_'+tid1[1]).prop('disabled', true);
                                                                                       $('.select_'+tid1[0]).prop('checked', false);
                                                                                       $('.select_'+tid1[0]).prop('disabled', true);
                                                                                      });
                                                                                   }},
                                                                               ]
                                                                           });
                                                                          });

                                                                       $(".resd").click(function(e){  
                                                                           var id=this.id;
                                                                           var unres = id.split("_");
                                                                           var reid=id.replace("res_", "");
                                                                           $('.ZebraDialogOverlay').css("z-index","20000");
                                                                           $('.ZebraDialog').css("z-index","20000");
                                                                           $('.ZebraDialogOverlay').removeClass('ZebraDialogOverlay').addClass('ZebraDialogOverlay1');
                                                                           $('#gantt_here').css("opacity","0.40");
                                                                           $('#fancybox-wrap').css("opacity","0.40");
                                                                           $.Zebra_Dialog('Are you sure you want to assign this resource again?',
                                                                           {
                                                                               'type': 'confirmation',
                                                                               'buttons': [
                                                                                   {caption: 'No', callback: function() {  
                                                                                       var resid1=reid.split("~");
                                                                                       $('.rescls_'+resid1[1]).prop('checked', true);
                                                                                       $('.rescls_'+resid1[1]).prop('disabled', false);
                                                                                       $('#tsk_<?php echo $taskid;?>').attr('checked', true);
                                                                                       $('#tsk_<?php echo $taskid;?>').attr('disabled', false);
                                                                                       $('#dest_<?php echo $destid; ?>').attr('checked', true);
                                                                                       $('#dest_<?php echo $destid; ?>').attr('disabled', false);
                                                                                       $('.ZebraDialog').remove();
                                                                                       $('.ZebraDialogOverlay1').remove();
                                                                                       $('#gantt_here').css("opacity", "");
                                                                                       $('#fancybox-wrap').css("opacity", "");
                                                                                   }},
                                                                                   {caption: 'Yes', callback: function() {
                                                                                       fn_resuncheck(unres[1],'<?php echo $stdtaskid[1];?>','<?php echo $stdtaskid[0];?>','<?php echo $expid;?>','<?php echo $stdtaskid[3];?>','<?php echo $stdtaskid[5];?>');
                                                                                       $('.ZebraDialogOverlay').removeClass('ZebraDialogOverlay').addClass('ZebraDialogOverlay1');
                                                                                       $('.ZebraDialog').remove();
                                                                                       $('.ZebraDialogOverlay1').remove();
                                                                                       $('#gantt_here').css("opacity", "");
                                                                                       $('#fancybox-wrap').css("opacity", ""); 
                                                                                       var reid=id.replace("res_", "");
                                                                                       var reid = reid.split("~");
                                                                                       $('.rescls_'+reid[1]).prop('checked', false);
                                                                                       $('.rescls_'+reid[1]).prop('disabled', true);
                                                                                       $('.destselect_'+reid[0]).prop('checked', false);
                                                                                       $('.destselect_'+reid[0]).prop('disabled', true);
                                                                                       $('#dest_'+reid[2]).attr('checked', false);
                                                                                       $('#dest_'+reid[2]).attr('disabled', true);
                                                                                   }},
                                                                               ]
                                                                           });
                                                                       });


                                                                       $(".destselect").click(function(e){ 
                                                                           var dstid=this.id;
                                                                           var undest = dstid.split("_");
                                                                           var dstid=dstid.replace("dest_", "tsk_");
                                                                           $('.ZebraDialogOverlay').css("z-index","20000");
                                                                           $('.ZebraDialog').css("z-index","20000");
                                                                           $('.ZebraDialogOverlay').removeClass('ZebraDialogOverlay').addClass('ZebraDialogOverlay1');
                                                                           $('#gantt_here').css("opacity","0.40");
                                                                           $('#fancybox-wrap').css("opacity","0.40");
                                                                           $.Zebra_Dialog('Are you sure you want to assign this destination again?',
                                                                           {
                                                                               'type': 'confirmation',
                                                                               'buttons': [
                                                                                   {caption: 'No', callback: function() { 

                                                                                      var destid1=dstid.replace("tsk_", "");
                                                                                       $('#dest_'+destid1).prop('checked', true);
                                                                                       $('#dest_'+destid1).prop('disabled', false);
                                                                                       $('.ZebraDialog').remove();
                                                                                       $('.ZebraDialogOverlay1').remove();
                                                                                       $('#gantt_here').css("opacity", "");
                                                                                       $('#fancybox-wrap').css("opacity", "");
                                                                                   }},
                                                                                   {caption: 'Yes', callback: function() {
                                                                                       fn_destuncheck(undest[1],'<?php echo $stdtaskid[1];?>','<?php echo $stdtaskid[0];?>','<?php echo $expid;?>','<?php echo $stdtaskid[3];?>','<?php echo $stdtaskid[5];?>');
                                                                                       $('.ZebraDialogOverlay').removeClass('ZebraDialogOverlay').addClass('ZebraDialogOverlay1');
                                                                                       $('.ZebraDialog').remove();
                                                                                       $('.ZebraDialogOverlay1').remove();
                                                                                       $('#gantt_here').css("opacity", "");
                                                                                       $('#fancybox-wrap').css("opacity", ""); 
                                                                                       var dstid1=dstid.replace("tsk_", "");
                                                                                       $('#dest_'+dstid1).prop('checked', false);
                                                                                       $('#dest_'+dstid1).prop('disabled', true);
                                                                                       $('.destclsid_'+dstid1).each(function(){
                                                                                            $('.destclsid_'+dstid1).prop('checked', false);
                                                                                            $('.destclsid_'+dstid1).prop('disabled', true);
                                                                                            $('.destresid_'+dstid1).prop('checked', false);
                                                                                            $('.destresid_'+dstid1).prop('disabled', true);
                                                                                       });
                                                                                   }},
                                                                               ]
                                                                           });
                                                                         });
                                                                    </script>
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </ul>
                                                </li>
                                            <?php
                                            } //$tstatus
                                        }
                                    }
                                    ?>
                                </ul>
                            </li>
                        </ul>
                        <?php
                    }
                }
                ?>
            </div>
            <?php
        }
    }  
    //Expedition schedule Code End Here
    
    /*****Mission Code Start Here Developed By Mohan M 27-11-2015*******/
    else if($stdtaskid[3]==23) 
    {
        
        $qryexpdetailsmis=$ObjDB->QueryObject("SELECT fld_schedule_name AS schedulename,
                                                                                DATE_FORMAT(fld_startdate, '%d-%m-%Y') AS sch_stdaterot,DATE_FORMAT(fld_enddate, '%d-%m-%Y') AS sch_enddaterot   
                                                                                FROM itc_class_rotation_mission_mastertemp where fld_id='".$stdtaskid[0]."' and fld_delstatus='0'");
        if($qryexpdetailsmis->num_rows>0)
        {
            $rowexpdetailsmis = $qryexpdetailsmis->fetch_assoc();
            extract($rowexpdetailsmis);
        }
        
        $rotdet=$ObjDB->QueryObject("SELECT DATE_FORMAT(fld_startdate, '%d-%m-%Y') AS startdaterot, DATE_FORMAT(fld_enddate, '%d-%m-%Y') AS enddaterot FROM itc_class_rotation_missionscheduledate where fld_schedule_id='".$stdtaskid[0]."' and fld_rotation='".$stdtaskid[5]."' and fld_flag='1'");
                                                       
            if($rotdet->num_rows>0)
            {
                $rowrotdet=$rotdet->fetch_assoc();
                extract($rowrotdet);
            }
        
        $misid=$stdtaskid[4];
                
        $roundflag = $ObjDB->SelectSingleValue("SELECT fld_roundflag
                                            FROM itc_class_grading_scale_mapping
                                            WHERE fld_class_id = '".$stdtaskid[2]."' AND fld_flag = '1'
                                            GROUP BY fld_roundflag");

        $pointearned1 = $ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned AS pointsearned FROM itc_mis_points_master 
                                                                    WHERE fld_student_id='".$stdtaskid[1]."' AND fld_mis_id='".$misid."' 
                                                                        AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='23' 
                                                                        AND fld_grade='1' AND fld_mistype='4'");
                  
        /************** Rubric code start here ***************/
        $pointsearnedrubric=0;
        $pointspossiblerubric=0;

        $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                            CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                            LEFT JOIN itc_mis_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                            LEFT JOIN itc_mission_master AS c ON a.fld_expmisid = c.fld_id
                                            LEFT JOIN itc_class_rotation_mission_mastertemp AS d ON a.fld_schedule_id=d.fld_id 
                                                WHERE a.fld_class_id='".$stdtaskid[2]."' AND a.fld_schedule_id='".$stdtaskid[0]."' AND b.fld_mis_id='".$misid."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
                                                    AND a.fld_schedule_type='19' AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                    AND b.fld_school_id IN(0,".$schoolid.")");

        if($qryrub->num_rows>0)
        {
            while($rowqryrub = $qryrub->fetch_assoc()) // show the module based on number of copies
            {
                extract($rowqryrub);

                $destinationids = $ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_mis_rubric_dest_master WHERE fld_rubric_name_id='".$rubricids."' AND fld_mis_id='".$misid."'"); 

                $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_master 
                                                            WHERE fld_mis_id='".$misid."' AND fld_rubric_id='".$rubricids."' AND fld_delstatus='0'");    

                $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_missch_rubric_rpt WHERE fld_mis_id='".$misid."'  
                                                                AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$stdtaskid[2]."' 
                                                                AND fld_schedule_id='".$stdtaskid[0]."' AND fld_delstatus='0' "); 

                $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_missch_rubric_rpt_statement
                                                                WHERE fld_student_id='".$stdtaskid[1]."' AND fld_mis_id='".$misid."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
                                                                AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                $pointsearnedrubric = $pointsearnedrubric+$studentscore;
                if($studentscore!=0)
                {
                        $pointspossiblerubric = $pointspossiblerubric+$totscore;
                }
            }
        }

        /************** Rubric code end here ***************/
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

        $pointsearned = $pointearned1 + $pointsearnedrubric;
        $pointspossible = $pointpossible1 + $pointspossiblerubric ;

        if($pointsearned == '' || $pointsearned == '0')
        {
            $pointsearned = "-";
            $percentage = "-";
            $grade = "NA";
            $pointspossible = "-";
        }
        else
        {
            $pointsearned = round($pointsearned,2);

            if($roundflag==0)
                    $percentage = round(($pointsearned/$pointspossible)*100,2);
            else
                    $percentage = round(($pointsearned/$pointspossible)*100);

            $perarray = explode('.',$percentage);

            $grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$stdtaskid[2]."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
        }

        $checkrstatusid = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_mis_res_status WHERE fld_mis_id='".$misid."' AND fld_school_id='".$senshlid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
        if($checkrstatusid == '0')
        {
            $resourcegroupids=$ObjDB->SelectSingleValue("select 
                                                                GROUP_CONCAT(cnt.fld_id) 
                                                        from
                                                            (SELECT 
                                                                a.fld_id
                                                            FROM
                                                                itc_mis_resource_master AS a
                                                            LEFT JOIN itc_mis_task_master AS b ON a.fld_task_id = b.fld_id
                                                            LEFT JOIN itc_mis_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                            LEFT JOIN itc_mis_res_status AS d ON d.fld_res_id = a.fld_id
                                                            LEFT JOIN itc_license_mission_mapping AS e ON c.fld_id = e.fld_dest_id
                                                            LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                            LEFT JOIN itc_class_indasmission_master as g ON e.fld_license_id = g.fld_license_id
                                                            where
                                                                c.fld_mis_id = '".$misid."'
                                                                    AND g.fld_id = '".$stdtaskid[0]."'
                                                                    AND d.fld_school_id = '0'
                                                                    AND d.fld_user_id = '0'
                                                                    and d.fld_status = '1'
                                                                    and a.fld_delstatus = '0'
                                                                    and b.fld_delstatus = '0'
                                                                    and c.fld_delstatus = '0'
                                                         GROUP BY a.fld_id) as cnt");


            $rescomplete = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_res_play_track WHERE fld_res_id IN (".$resourcegroupids.") AND fld_schedule_id='".$stdtaskid[0]."' AND fld_delstatus='0' AND fld_schedule_type='23' AND fld_student_id='".$stdtaskid[1]."' AND fld_read_status='1'");
            $totalresource=sizeof(explode(',',$resourcegroupids));

        }
        else
        {
        $resourcegroupids=$ObjDB->SelectSingleValue("select 
                                                        GROUP_CONCAT(cnt.fld_id) 
                                                    from
                                                        (SELECT 
                                                            a.fld_id
                                                        FROM
                                                            itc_mis_resource_master AS a
                                                        LEFT JOIN itc_mis_task_master AS b ON a.fld_task_id = b.fld_id
                                                        LEFT JOIN itc_mis_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                        LEFT JOIN itc_mis_res_status AS d ON d.fld_res_id = a.fld_id
                                                        LEFT JOIN itc_license_mission_mapping AS e ON c.fld_id = e.fld_dest_id
                                                        LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                        LEFT JOIN itc_class_indasmission_master as g ON e.fld_license_id = g.fld_license_id
                                                        where
                                                            c.fld_mis_id = '".$misid."'
                                                                AND g.fld_id = '".$stdtaskid[0]."'
                                                                AND d.fld_school_id = '".$senshlid."'
                                                                AND d.fld_created_by='".$uid."'
                                                                AND d.fld_user_id = '".$indid."'
                                                                and d.fld_status = '1'
                                                                and a.fld_delstatus = '0'
                                                                and b.fld_delstatus = '0'
                                                                and c.fld_delstatus = '0'
                                                        GROUP BY a.fld_id) as cnt");


           $rescomplete = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_res_play_track WHERE fld_res_id IN (".$resourcegroupids.") AND fld_schedule_id='".$stdtaskid[0]."' AND fld_delstatus='0' AND fld_schedule_type='23' AND fld_student_id='".$stdtaskid[1]."' AND fld_read_status='1'");
           $totalresource=sizeof(explode(',',$resourcegroupids));
        }
    
        if($totalresource==0)
        {
            $completeprogress=0;
        }
        else if($totalresource!=0)
        {
            $completeprogress=($rescomplete/$totalresource)*100;
            $completeprogress=round($completeprogress,2);
        }
        ?>
        <div style="width:580px; margin-top: 50px; margin-bottom: 50px;">
            <table cellpadding="0" border="0" cellspacing="0" width="60%" align="center">
                <tr>
                    <td style="width:165px;">
                        Schedule Name:
                    </td>
                    <td>
                        <?php echo $schedulename; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Start Date:
                    </td>
                    <td>
                        <?php echo $startdaterot; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        End Date:
                    </td>
                    <td>
                        <?php echo $enddaterot; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Assignment Marks:
                    </td>
                    <td>
                        <?php echo $pointsearned."/".$pointspossible; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Assignment Percentage:
                    </td>
                    <td>
                        <?php echo $percentage; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Grade:
                    </td>
                    <td>
                        <?php echo $grade; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Complete Progress:
                    </td>
                    <td>
                        <?php echo $completeprogress."%"; ?>
                    </td>
                </tr>
            </table>
        </div>

        <?php
       
        $qrydestdetails=$ObjDB->QueryObject("SELECT a.fld_id as destid, a.fld_dest_name AS destname
                                            FROM itc_mis_destination_master as a
                                            LEFT JOIN itc_license_mission_mapping AS b ON a.fld_id = b.fld_dest_id
                                            LEFT JOIN itc_license_track AS c ON b.fld_license_id = c.fld_license_id
                                            LEFT JOIN itc_class_rotation_mission_mastertemp as d on b.fld_license_id=d.fld_license_id
                                            WHERE a.fld_mis_id = '".$misid."' AND d.fld_id = '".$stdtaskid[0]."'  
                                                AND a.fld_delstatus = '0' AND c.fld_user_id='".$indid."' 
                                                AND c.fld_school_id='".$schoolid."' GROUP BY destid");
        if($qrydestdetails->num_rows>0)
        {
            ?>
            <div style="height:400px; overflow:auto;">
                <?php
                while($rowdestdetails = $qrydestdetails->fetch_assoc())
                {
                    extract($rowdestdetails);

                    $checkshlstatus = $ObjDB->SelectSingleValue("select count(fld_id) from itc_mis_res_status where fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' and fld_mis_id='".$misid."'");
                    if($checkshlstatus == '0')
                    {
                        $schoolid ='0';
                    }

                    /* For checking deatination, task and resoures are completed whenever student is enter into expedition - Karthi*/ 
                    $fieldtask1 = 'CONCAT("\'",a.fld_id,"\'")';
                    $grouptaskids1 = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(".$fieldtask1.") 
                                            FROM itc_mis_task_master as a 
                                            LEFT JOIN itc_mis_res_status as b on a.fld_id=b.fld_task_id
                                            WHERE a.fld_dest_id='".$destid."' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$uid."' AND b.fld_user_id='".$indid."' AND b.fld_status='1' AND a.fld_delstatus='0'");

                    $selecttasks = $ObjDB->QueryObject("SELECT a.fld_id AS taskid 
                                                        FROM itc_mis_task_master AS a
                                                        LEFT JOIN itc_mis_res_status as b on a.fld_id=b.fld_task_id
                                                        WHERE a.fld_dest_id='".$destid."' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$uid."' AND b.fld_user_id='".$indid."' AND b.fld_status='1' AND a.fld_delstatus='0' ORDER BY a.fld_order");
                    if($selecttasks->num_rows>0) 
                    {
                        while ($rowselecttasks = $selecttasks->fetch_assoc()) 
                        {
                            extract($rowselecttasks);
                            $fieldresvar1 = 'CONCAT("\'",a.fld_id,"\'")';
                            $groupresids1 = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(".$fieldresvar1.") 
                                                                        FROM itc_mis_resource_master As a
                                                                        LEFT JOIN itc_mis_res_status as b on a.fld_id=b.fld_res_id
                                                                        WHERE a.fld_task_id='".$taskid."' AND a.fld_delstatus='0' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$uid."' AND b.fld_user_id='".$indid."' AND b.fld_status='1'");

                            $resreadcnt1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_res_play_track WHERE fld_res_id IN (".$groupresids1.") AND fld_delstatus='0' AND fld_student_id='".$stdtaskid[1]."' AND fld_schedule_type='23' AND fld_read_status='1'");

                            if($resreadcnt1 == sizeof(explode(',',$groupresids1)))
                            {
                                $ObjDB->NonQuery("UPDATE itc_mis_task_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_task_id='".$taskid."' AND fld_delstatus='0' AND fld_student_id='".$stdtaskid[1]."' AND fld_schedule_type='23' AND fld_schedule_id='".$stdtaskid[0]."'");

                                $taskreadcnt1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_task_play_track WHERE fld_task_id IN (".$grouptaskids1.") AND fld_schedule_id='".$stdtaskid[0]."' AND fld_delstatus='0' AND fld_schedule_type='23' AND fld_student_id='".$stdtaskid[1]."' AND fld_read_status='1'");

                                if($taskreadcnt1 === sizeof(explode(',',$grouptaskids1)))
                                {
                                    $ObjDB->NonQuery("UPDATE itc_mis_dest_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_dest_id='".$destid."' AND fld_delstatus='0' AND fld_schedule_type='23' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_student_id='".$stdtaskid[1]."'");
                                }
                            }
                        }
                    }
                    /* Ends */

                    $dstatusid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_res_status WHERE fld_mis_id='".$misid."' AND fld_dest_id='".$destid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");

                    if($dstatusid != ''  and $dstatusid != NULL and $dstatusid != '0')
                    {
                        $dstatus = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_mis_res_status WHERE fld_mis_id='".$misid."' AND fld_id='".$dstatusid."' AND fld_dest_id='".$destid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                    }          
                    else if($dstatus=='' or $dstatus=='0' or $dstatus==NULL)
                    {
                        $createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
                        $dstatus = $ObjDB->SelectSingleValue("SELECT fld_status FROM itc_mis_res_status WHERE fld_mis_id='".$misid."' AND fld_dest_id='".$destid."' AND fld_created_by IN (".$createdids.") AND fld_flag='1'");
                    }

                    if($dstatus == 1)
                    {
                        $taskcount=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_mis_dest_play_track 
                                                                    WHERE fld_student_id='".$stdtaskid[1]."' AND fld_mis_id='".$misid."' 
                                                                    AND fld_dest_id='".$destid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='23' AND fld_delstatus='0'");//AND fld_schedule_id='$stdtaskid[0]' 
                        if($taskcount>0)
                        {
                            $taskinpcnt=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_mis_dest_play_track WHERE fld_student_id='".$stdtaskid[1]."' AND fld_mis_id='".$misid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_dest_id='".$destid."'
                                                                        AND fld_read_status='0' AND fld_delstatus='0' AND fld_schedule_type='23'");//count 0=>Completed; 1=>Inprogress for destination checkbox  - AND fld_schedule_id='$stdtaskid[0]' 
                        }
                        else
                        {
                            $taskinpcnt=1;

                        }
                        ?>
                        <ul class="tree" style="margin-left: 15px;">
                            <li>
                                <input type="checkbox" value="0" class="destcls destselect" id="dest_<?php echo $destid; ?>" <?php if($taskinpcnt==0){ echo 'checked="checked"';}else{ echo 'disabled="disabled"';} ?>>
                                <label><?php echo $destname;?></label>
                                <ul class='expanded'>
                                    <?php
        
                                    $qrytaskdetails=$ObjDB->QueryObject("SELECT fld_id as taskid,fld_task_name AS taskname 
                                                                        FROM itc_mis_task_master 
                                                                        WHERE fld_dest_id='".$destid."' AND fld_delstatus='0'");
                                    if($qrytaskdetails->num_rows>0)
                                    {
                                        while($rowtaskdetails = $qrytaskdetails->fetch_assoc())
                                        {
                                            extract($rowtaskdetails);
                                            $tstatusid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_res_status WHERE fld_mis_id='".$misid."' AND fld_task_id='".$taskid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");

                                            if($tstatusid != ''  and $tstatusid != NULL and $tstatusid != '0')
                                            {
                                                $tstatus = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_mis_res_status WHERE fld_mis_id='".$misid."' AND fld_id='".$tstatusid."' AND fld_task_id='".$taskid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                                            }
                                            else if($tstatus=='' or $tstatus=='0' or $tstatus==NULL)
                                            {
                                                $createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
                                                $tstatus = $ObjDB->SelectSingleValue("SELECT fld_status FROM itc_mis_res_status WHERE fld_mis_id='".$misid."' AND fld_task_id='".$taskid."' AND fld_created_by IN (".$createdids.") AND fld_flag='1'");
                                            }

                                            if($tstatus == 1)
                                            { 
                                                $rescount=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_mis_task_play_track 
                                                                                    WHERE fld_student_id='".$stdtaskid[1]."' AND fld_mis_id='".$misid."'  
                                                                                    AND fld_dest_id='".$destid."' AND fld_task_id='".$taskid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='23' AND fld_delstatus='0'");//AND fld_schedule_id='".$stdtaskid[0]."'
                                                if($rescount>0)
                                                {
                                                    $resinpcnt=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_mis_task_play_track WHERE fld_student_id='".$stdtaskid[1]."' AND fld_mis_id='".$misid."' AND fld_dest_id='".$destid."' AND fld_task_id='".$taskid."' AND fld_schedule_id='".$stdtaskid[0]."'
                                                                                                AND fld_read_status='0' AND fld_delstatus='0' AND fld_schedule_type='23'");//0=>Completed; 1=>Inprogress for task checkbox - AND fld_schedule_id='".$stdtaskid[0]."' 
                                                }
                                                else
                                                {
                                                    $resinpcnt=1;

                                                }

           
                                                    $qryresourcedetails=$ObjDB->QueryObject("SELECT fld_id AS resid,fld_res_name AS resname
                                                                                           FROM itc_mis_resource_master
                                                                                           WHERE fld_task_id='".$taskid."' AND fld_delstatus='0'");

                                                    if($qryresourcedetails->num_rows>0)
                                                    {
                                                            while($rowresourcedetails = $qryresourcedetails->fetch_assoc())
                                                            {
                                                                extract($rowresourcedetails);
                                                                $rstatusid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_res_status WHERE fld_mis_id='".$misid."' AND fld_res_id='".$resid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                                                                if($rstatusid != ''  and $rstatusid != NULL and $rstatusid != '0'){
                                                                    $rstatus = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_mis_res_status WHERE fld_mis_id='".$misid."' AND fld_id='".$rstatusid."' AND fld_res_id='".$resid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                                                                }
                                                                if($rstatus=='' or $rstatus=='0' or $rstatus==NULL)
                                                                {
                                                                    $createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");

                                                                    $rstatus = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_mis_res_status WHERE fld_mis_id='".$misid."' AND fld_res_id='".$resid."' AND fld_created_by IN (".$createdids.") AND fld_flag='1'");
                                                                }
                                                                if($rstatus == 1)
                                                                {
                                                                    $rescount1=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_mis_res_play_track 
                                                                                    WHERE fld_student_id='".$stdtaskid[1]."' AND fld_mis_id='".$misid."'  
                                                                                    AND fld_dest_id='".$destid."' AND fld_task_id='".$taskid."' AND fld_res_id='".$resid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='23' AND fld_delstatus='0'");//AND fld_schedule_id='".$stdtaskid[0]."'
                                                                    if($rescount1>0)
                                                                    {
                                                                        $resinpcnt1=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_mis_res_play_track 
                                                                                                            WHERE fld_student_id='".$stdtaskid[1]."' AND fld_mis_id='".$misid."'  
                                                                                                            AND fld_dest_id='".$destid."' AND fld_task_id='".$taskid."' AND fld_res_id='".$resid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='23' AND fld_read_status='0' AND fld_delstatus='0'");//0=>Completed; 1=>Inprogress for task checkbox  AND fld_schedule_id='".$stdtaskid[0]."'
                                                                    }
                                                                    else
                                                                    {
                                                                        $resinpcnt1=1;

                                                                    }

                                                                    $qrysessiontime=$ObjDB->QueryObject("SELECT a.varValue AS sessiontime FROM itc_mis_scorm_track as a
                                                                                                            JOIN itc_mis_res_play_track as b on b.fld_id=a.SCOInstanceID
                                                                                                            WHERE a.varName='cmi.core.session_time' AND b.fld_student_id='".$stdtaskid[1]."' AND b.fld_res_id='".$resid."' AND b.fld_schedule_id='".$stdtaskid[0]."' AND b.fld_schedule_type='23' AND b.fld_read_status='1'");
                                                                    if($qrysessiontime->num_rows>0)
                                                                    {
                                                                        while($rowsessiontime=$qrysessiontime->fetch_assoc())
                                                                        {
                                                                            extract($rowsessiontime);
                                                                            $arrtime[]=$sessiontime;
                                                                        }
                                                                    }
                                                                    $sessiontime1='';
                                                                    for($i=0;$i<sizeof($arrtime);$i++)
                                                                    {  
                                                                        $j = $i+1;
                                                                        if($sessiontime1=='')
                                                                            $sessiontime1="$j. ".$arrtime[$i];
                                                                        else
                                                                            $sessiontime1=$sessiontime1."\n"."$j. ".$arrtime[$i];
                                                                    } 

                                                                    ?>
                                                                    <li>
                                                                        <input title="<?php echo $sessiontime1;?>" type="checkbox" class="resd select_<?php echo $taskid ;?> rescls_<?php echo $resid ;?> destresid_<?php echo $destid; ?>" id="res_<?php echo $taskid."~".$resid."~".$destid;?>" <?php if($resinpcnt1==0){ echo 'checked="checked"';}else{ echo 'disabled="disabled"';} ?>>
                                                                        <label title="<?php echo $sessiontime1;?>"><?php echo $resname;?></label>
                                                                    </li>
                                                                    <script>
                                                                        $(".select").click(function(e){ 
                                                                            var tid=this.id;
                                                                            var untask = tid.split("_");
                                                                            var tid=tid.replace("tsk_", "res_"); 
                                                                           $('.ZebraDialogOverlay').css("z-index","20000");
                                                                           $('.ZebraDialog').css("z-index","20000");
                                                                           $('.ZebraDialogOverlay').removeClass('ZebraDialogOverlay').addClass('ZebraDialogOverlay1');
                                                                           $('#gantt_here').css("opacity","0.40");
                                                                           $('#fancybox-wrap').css("opacity","0.40");
                                                                           $.Zebra_Dialog('Are you sure you want to assign this task again?',
                                                                           {
                                                                               'type': 'confirmation',
                                                                               'buttons': [
                                                                                   {caption: 'No', callback: function() {  
                                                                                       var tid1=tid.replace("res_", "");
                                                                                       tid1=tid1.split("~");
                                                                                       $('.destselect_'+tid1[0]).prop('checked', true);
                                                                                       $('.destselect_'+tid1[0]).prop('disabled', false);
                                                                                       $('.ZebraDialog').remove();
                                                                                       $('.ZebraDialogOverlay1').remove();
                                                                                       $('#gantt_here').css("opacity", "");
                                                                                       $('#fancybox-wrap').css("opacity", "");
                                                                                   }},
                                                                                   {caption: 'Yes', callback: function() {
                                                                                       fn_taskuncheck(untask[1],'<?php echo $stdtaskid[1];?>','<?php echo $stdtaskid[0];?>','<?php echo $misid;?>','<?php echo $stdtaskid[3];?>');
                                                                                       $('.ZebraDialogOverlay').removeClass('ZebraDialogOverlay').addClass('ZebraDialogOverlay1');
                                                                                       $('.ZebraDialog').remove();
                                                                                       $('.ZebraDialogOverlay1').remove();
                                                                                       $('#gantt_here').css("opacity", "");
                                                                                       $('#fancybox-wrap').css("opacity", "");
                                                                                       var tid1=tid.replace("res_", "");
                                                                                       tid1=tid1.split("~");
                                                                                       $('.destselect_'+tid1[0]).prop('checked', false);
                                                                                       $('.destselect_'+tid1[0]).prop('disabled', true);
                                                                                       $('.select_'+tid1[0]).each(function(){
                                                                                       $('#dest_'+tid1[1]).prop('checked', false);
                                                                                       $('#dest_'+tid1[1]).prop('disabled', true);
                                                                                       $('.select_'+tid1[0]).prop('checked', false);
                                                                                       $('.select_'+tid1[0]).prop('disabled', true);
                                                                                      });
                                                                                   }},
                                                                               ]
                                                                           });
                                                                          });

                                                                       $(".resd").click(function(e){  
                                                                           var id=this.id;
                                                                           var unres = id.split("_");
                                                                           var reid=id.replace("res_", "");
                                                                           $('.ZebraDialogOverlay').css("z-index","20000");
                                                                           $('.ZebraDialog').css("z-index","20000");
                                                                           $('.ZebraDialogOverlay').removeClass('ZebraDialogOverlay').addClass('ZebraDialogOverlay1');
                                                                           $('#gantt_here').css("opacity","0.40");
                                                                           $('#fancybox-wrap').css("opacity","0.40");
                                                                           $.Zebra_Dialog('Are you sure you want to assign this resource again?',
                                                                           {
                                                                               'type': 'confirmation',
                                                                               'buttons': [
                                                                                   {caption: 'No', callback: function() { 
                                                                                       var resid1=reid.split("~");
                                                                                       $('.rescls_'+resid1[1]).prop('checked', true);
                                                                                       $('.rescls_'+resid1[1]).prop('disabled', false);
                                                                                       $('#tsk_<?php echo $taskid;?>').attr('checked', true);
                                                                                       $('#tsk_<?php echo $taskid;?>').attr('disabled', false);
                                                                                       $('#dest_<?php echo $destid; ?>').attr('checked', true);
                                                                                       $('#dest_<?php echo $destid; ?>').attr('disabled', false);
                                                                                       $('.ZebraDialog').remove();
                                                                                       $('.ZebraDialogOverlay1').remove();
                                                                                       $('#gantt_here').css("opacity", "");
                                                                                       $('#fancybox-wrap').css("opacity", "");
                                                                                   }},
                                                                                   {caption: 'Yes', callback: function() {
                                                                                       fn_resuncheck(unres[1],'<?php echo $stdtaskid[1];?>','<?php echo $stdtaskid[0];?>','<?php echo $misid;?>','<?php echo $stdtaskid[3];?>');
                                                                                       $('.ZebraDialogOverlay').removeClass('ZebraDialogOverlay').addClass('ZebraDialogOverlay1');
                                                                                       $('.ZebraDialog').remove();
                                                                                       $('.ZebraDialogOverlay1').remove();
                                                                                       $('#gantt_here').css("opacity", "");
                                                                                       $('#fancybox-wrap').css("opacity", ""); 
                                                                                       var reid=id.replace("res_", "");
                                                                                       var reid = reid.split("~");
                                                                                       $('.rescls_'+reid[1]).prop('checked', false);
                                                                                       $('.rescls_'+reid[1]).prop('disabled', true);
                                                                                       $('.destselect_'+reid[0]).prop('checked', false);
                                                                                       $('.destselect_'+reid[0]).prop('disabled', true);
                                                                                       $('#dest_'+reid[2]).attr('checked', false);
                                                                                       $('#dest_'+reid[2]).attr('disabled', true);
                                                                                   }},
                                                                               ]
                                                                           });
                                                                       });


                                                                       $(".destselect").click(function(e){ 
                                                                           var dstid=this.id;
                                                                           var undest = dstid.split("_");
                                                                           var dstid=dstid.replace("dest_", "tsk_");
                                                                           $('.ZebraDialogOverlay').css("z-index","20000");
                                                                           $('.ZebraDialog').css("z-index","20000");
                                                                           $('.ZebraDialogOverlay').removeClass('ZebraDialogOverlay').addClass('ZebraDialogOverlay1');
                                                                           $('#gantt_here').css("opacity","0.40");
                                                                           $('#fancybox-wrap').css("opacity","0.40");
                                                                           $.Zebra_Dialog('Are you sure you want to assign this destination again?',
                                                                           {
                                                                               'type': 'confirmation',
                                                                               'buttons': [
                                                                                   {caption: 'No', callback: function() { 

                                                                                      var destid1=dstid.replace("tsk_", "");
                                                                                       $('#dest_'+destid1).prop('checked', true);
                                                                                       $('#dest_'+destid1).prop('disabled', false);
                                                                                       $('.ZebraDialog').remove();
                                                                                       $('.ZebraDialogOverlay1').remove();
                                                                                       $('#gantt_here').css("opacity", "");
                                                                                       $('#fancybox-wrap').css("opacity", "");
                                                                                   }},
                                                                                   {caption: 'Yes', callback: function() {
                                                                                       fn_destuncheck(undest[1],'<?php echo $stdtaskid[1];?>','<?php echo $stdtaskid[0];?>','<?php echo $misid;?>','<?php echo $stdtaskid[3];?>'); 
                                                                                       $('.ZebraDialogOverlay').removeClass('ZebraDialogOverlay').addClass('ZebraDialogOverlay1');
                                                                                       $('.ZebraDialog').remove();
                                                                                       $('.ZebraDialogOverlay1').remove();
                                                                                       $('#gantt_here').css("opacity", "");
                                                                                       $('#fancybox-wrap').css("opacity", ""); 
                                                                                       var dstid1=dstid.replace("tsk_", "");
                                                                                       $('#dest_'+dstid1).prop('checked', false);
                                                                                       $('#dest_'+dstid1).prop('disabled', true);
                                                                                       $('.destclsid_'+dstid1).each(function(){
                                                                                            $('.destclsid_'+dstid1).prop('checked', false);
                                                                                            $('.destclsid_'+dstid1).prop('disabled', true);
                                                                                            $('.destresid_'+dstid1).prop('checked', false);
                                                                                            $('.destresid_'+dstid1).prop('disabled', true);
                                                                                       });
                                                                                   }},
                                                                               ]
                                                                           });
                                                                         });
                                                                    </script>
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                        
                                            } //$tstatus
                                        }
                                    }
                                    ?>
                                </ul>
                            </li>
                        </ul>
                        <?php
                    }
                }
                ?>
            </div>
            <?php
        }
    } 
    /*****Mission Code End Here Developed By Mohan M 27-11-2015*******/
    
    /*****Mission Code Start Here Developed By Mohan M 27-11-2015*******/
    else if($stdtaskid[3]==18) 
    {
        
        $qryexpdetailsmis=$ObjDB->QueryObject("SELECT a.fld_mis_id as misid, a.fld_startdate as sdate, a.fld_enddate as edate, a.fld_schedule_name as schname     
                                                FROM itc_class_indasmission_master AS a LEFT JOIN  itc_class_mission_student_mapping AS b ON a.fld_id=b.fld_schedule_id
                                                WHERE b.fld_student_id='".$stdtaskid[1]."' AND b.fld_schedule_id='$stdtaskid[0]' AND b.fld_flag='1' AND a.fld_delstatus='0'");
        if($qryexpdetailsmis->num_rows>0)
        {
            $rowexpdetailsmis = $qryexpdetailsmis->fetch_assoc();
            extract($rowexpdetailsmis);
        }
        $roundflag = $ObjDB->SelectSingleValue("SELECT fld_roundflag
                                            FROM itc_class_grading_scale_mapping
                                            WHERE fld_class_id = '".$stdtaskid[2]."' AND fld_flag = '1'
                                            GROUP BY fld_roundflag");

        
        $gradepointsearned = $ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned AS pointsearned FROM itc_mis_points_master 
                                                            WHERE fld_student_id='".$stdtaskid[1]."' AND fld_mis_id='".$misid."' 
                                                                AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='18' AND fld_mistype='4'
                                                                AND fld_grade='1' AND fld_delstatus='0'");

        $gradepointspossible = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_points_possible) AS pointspossible FROM itc_mis_points_master 
                                                                WHERE fld_student_id='".$stdtaskid[1]."' AND fld_mis_id='".$misid."' 
                                                                    AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='18'  AND fld_mistype='4' 
                                                                    AND fld_grade='1' AND fld_delstatus='0'");

        /************** Rubric code start here ***************/
        $pointsearnedrubric=0;
        $pointspossiblerubric=0;
        $qryrub = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS nam, 
                                            CONCAT(a.fld_rubric_name) AS rubnam FROM itc_class_expmis_rubricmaster AS a 
                                                LEFT JOIN itc_mis_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                                LEFT JOIN itc_mission_master AS c ON a.fld_expmisid = c.fld_id
                                                LEFT JOIN itc_class_indasmission_master AS d ON a.fld_schedule_id=d.fld_id 
                                                    WHERE a.fld_class_id='".$stdtaskid[2]."' AND a.fld_schedule_id='".$stdtaskid[0]."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  
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
                                                                AND fld_rubric_nameid ='".$rubricids."' AND fld_class_id='".$stdtaskid[2]."' 
                                                                AND fld_schedule_id='".$stdtaskid[0]."' AND fld_delstatus='0' "); 

                $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_rpt_statement
                                                                    WHERE fld_student_id='".$stdtaskid[1]."' AND fld_mis_id='".$misid."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
                                                                    AND fld_rubric_rpt_id='".$rubricrptid."'"); 

                $pointsearnedrubric = $pointsearnedrubric+$studentscore;
                if($studentscore!=0)
                {
                    $pointspossiblerubric = $pointspossiblerubric+$totscore;
                }
            }
        }
        /************** Rubric code end here ***************/
        $pointsearned=$gradepointsearned+$pointsearnedrubric;
        $pointspossible=$gradepointspossible+$pointspossiblerubric;

        if($pointsearned=='')
        {
            $pointsearned = "-";
             $pointspossible = "-";
            $percentage = "-";
            $grade = "NA";
        }
        else
        {
            $pointsearned = round($pointsearned,2);
            $totalpointsearned = $totalpointsearned + $pointsearned;

            if($roundflag==0)
                $percentage = round(($pointsearned/$pointspossible)*100,2);
            else
                $percentage = round(($pointsearned/$pointspossible)*100);

            $perarray = explode('.',$percentage);

            $grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$stdtaskid[2]."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
        }

        $checkrstatusid = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_mis_res_status WHERE fld_mis_id='".$misid."' AND fld_school_id='".$senshlid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
        if($checkrstatusid == '0')
        {
            $resourcegroupids=$ObjDB->SelectSingleValue("select 
                                                                GROUP_CONCAT(cnt.fld_id) 
                                                        from
                                                            (SELECT 
                                                                a.fld_id
                                                            FROM
                                                                itc_mis_resource_master AS a
                                                            LEFT JOIN itc_mis_task_master AS b ON a.fld_task_id = b.fld_id
                                                            LEFT JOIN itc_mis_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                            LEFT JOIN itc_mis_res_status AS d ON d.fld_res_id = a.fld_id
                                                            LEFT JOIN itc_license_mission_mapping AS e ON c.fld_id = e.fld_dest_id
                                                            LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                            LEFT JOIN itc_class_indasmission_master as g ON e.fld_license_id = g.fld_license_id
                                                            where
                                                                c.fld_mis_id = '".$misid."'
                                                                    AND g.fld_id = '".$stdtaskid[0]."'
                                                                    AND d.fld_school_id = '0'
                                                                    AND d.fld_user_id = '0'
                                                                    and d.fld_status = '1'
                                                                    and a.fld_delstatus = '0'
                                                                    and b.fld_delstatus = '0'
                                                                    and c.fld_delstatus = '0'
                                                         GROUP BY a.fld_id) as cnt");


            $rescomplete = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_res_play_track WHERE fld_res_id IN (".$resourcegroupids.") AND fld_schedule_id='".$stdtaskid[0]."' AND fld_delstatus='0' AND fld_schedule_type='18' AND fld_student_id='".$stdtaskid[1]."' AND fld_read_status='1'");
            $totalresource=sizeof(explode(',',$resourcegroupids));

        }
        else
        {
        $resourcegroupids=$ObjDB->SelectSingleValue("select 
                                                        GROUP_CONCAT(cnt.fld_id) 
                                                    from
                                                        (SELECT 
                                                            a.fld_id
                                                        FROM
                                                            itc_mis_resource_master AS a
                                                        LEFT JOIN itc_mis_task_master AS b ON a.fld_task_id = b.fld_id
                                                        LEFT JOIN itc_mis_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                        LEFT JOIN itc_mis_res_status AS d ON d.fld_res_id = a.fld_id
                                                        LEFT JOIN itc_license_mission_mapping AS e ON c.fld_id = e.fld_dest_id
                                                        LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                        LEFT JOIN itc_class_indasmission_master as g ON e.fld_license_id = g.fld_license_id
                                                        where
                                                            c.fld_mis_id = '".$misid."'
                                                                AND g.fld_id = '".$stdtaskid[0]."'
                                                                AND d.fld_school_id = '".$senshlid."'
                                                                AND d.fld_created_by='".$uid."'
                                                                AND d.fld_user_id = '".$indid."'
                                                                and d.fld_status = '1'
                                                                and a.fld_delstatus = '0'
                                                                and b.fld_delstatus = '0'
                                                                and c.fld_delstatus = '0'
                                                        GROUP BY a.fld_id) as cnt");


           $rescomplete = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_res_play_track WHERE fld_res_id IN (".$resourcegroupids.") AND fld_schedule_id='".$stdtaskid[0]."' AND fld_delstatus='0' AND fld_schedule_type='18' AND fld_student_id='".$stdtaskid[1]."' AND fld_read_status='1'");
           $totalresource=sizeof(explode(',',$resourcegroupids));
        }
    
        if($totalresource==0)
        {
            $completeprogress=0;
        }
        else if($totalresource!=0)
        {
            $completeprogress=($rescomplete/$totalresource)*100;
            $completeprogress=round($completeprogress,2);
        }
        ?>
        <div style="width:580px; margin-top: 50px; margin-bottom: 50px;">
            <table cellpadding="0" border="0" cellspacing="0" width="60%" align="center">
                <tr>
                    <td style="width:165px;">
                        Schedule Name:
                    </td>
                    <td>
                        <?php echo $schname; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Start Date:
                    </td>
                    <td>
                        <?php echo $sdate; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        End Date:
                    </td>
                    <td>
                        <?php echo $edate; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Assignment Marks:
                    </td>
                    <td>
                        <?php echo $pointsearned."/".$pointspossible; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Assignment Percentage:
                    </td>
                    <td>
                        <?php echo $percentage; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Grade:
                    </td>
                    <td>
                        <?php echo $grade; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width:165px;">
                        Complete Progress:
                    </td>
                    <td>
                        <?php echo $completeprogress."%"; ?>
                    </td>
                </tr>
            </table>
        </div>

        <?php
       
        $qrydestdetails=$ObjDB->QueryObject("SELECT a.fld_id as destid, a.fld_dest_name AS destname
                                            FROM itc_mis_destination_master as a
                                            LEFT JOIN itc_license_mission_mapping AS b ON a.fld_id = b.fld_dest_id
                                            LEFT JOIN itc_license_track AS c ON b.fld_license_id = c.fld_license_id
                                            LEFT JOIN itc_class_indasmission_master as d on b.fld_license_id=d.fld_license_id
                                            WHERE a.fld_mis_id = '".$misid."' AND d.fld_id = '".$stdtaskid[0]."'  
                                                AND a.fld_delstatus = '0' GROUP BY destid");
        if($qrydestdetails->num_rows>0)
        {
            ?>
            <div style="height:400px; overflow:auto;">
                <?php
                while($rowdestdetails = $qrydestdetails->fetch_assoc())
                {
                    extract($rowdestdetails);

                    $checkshlstatus = $ObjDB->SelectSingleValue("select count(fld_id) from itc_mis_res_status where fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' and fld_mis_id='".$misid."'");
                    if($checkshlstatus == '0')
                    {
                        $schoolid ='0';
                    }

                    /* For checking deatination, task and resoures are completed whenever student is enter into expedition - Karthi*/ 
                    $fieldtask1 = 'CONCAT("\'",a.fld_id,"\'")';
                    $grouptaskids1 = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(".$fieldtask1.") 
                                            FROM itc_mis_task_master as a 
                                            LEFT JOIN itc_mis_res_status as b on a.fld_id=b.fld_task_id
                                            WHERE a.fld_dest_id='".$destid."' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$uid."' AND b.fld_user_id='".$indid."' AND b.fld_status='1' AND a.fld_delstatus='0'");

                    $selecttasks = $ObjDB->QueryObject("SELECT a.fld_id AS taskid 
                                                        FROM itc_mis_task_master AS a
                                                        LEFT JOIN itc_mis_res_status as b on a.fld_id=b.fld_task_id
                                                        WHERE a.fld_dest_id='".$destid."' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$uid."' AND b.fld_user_id='".$indid."' AND b.fld_status='1' AND a.fld_delstatus='0' ORDER BY a.fld_order");
                    if($selecttasks->num_rows>0) 
                    {
                        while ($rowselecttasks = $selecttasks->fetch_assoc()) 
                        {
                            extract($rowselecttasks);
                            $fieldresvar1 = 'CONCAT("\'",a.fld_id,"\'")';
                            $groupresids1 = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(".$fieldresvar1.") 
                                                                        FROM itc_mis_resource_master As a
                                                                        LEFT JOIN itc_mis_res_status as b on a.fld_id=b.fld_res_id
                                                                        WHERE a.fld_task_id='".$taskid."' AND a.fld_delstatus='0' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$uid."' AND b.fld_user_id='".$indid."' AND b.fld_status='1'");

                            $resreadcnt1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_res_play_track WHERE fld_res_id IN (".$groupresids1.") AND fld_delstatus='0' AND fld_schedule_type='18' AND fld_student_id='".$stdtaskid[1]."' AND fld_read_status='1'");

                            if($resreadcnt1 == sizeof(explode(',',$groupresids1)))
                            {
                                $ObjDB->NonQuery("UPDATE itc_mis_task_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_task_id='".$taskid."' AND fld_delstatus='0' AND fld_schedule_type='18' AND fld_student_id='".$stdtaskid[1]."' AND fld_schedule_id='".$stdtaskid[0]."'");

                                $taskreadcnt1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_task_play_track WHERE fld_task_id IN (".$grouptaskids1.") AND fld_schedule_id='".$stdtaskid[0]."' AND fld_delstatus='0' AND fld_schedule_type='18' AND fld_student_id='".$stdtaskid[1]."' AND fld_read_status='1'");

                                if($taskreadcnt1 === sizeof(explode(',',$grouptaskids1)))
                                {
                                    $ObjDB->NonQuery("UPDATE itc_mis_dest_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_dest_id='".$destid."' AND fld_delstatus='0' AND fld_schedule_type='18' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_student_id='".$stdtaskid[1]."'");
                                }
                            }
                        }
                    }
                    /* Ends */

                    $dstatusid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_res_status WHERE fld_mis_id='".$misid."' AND fld_dest_id='".$destid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");

                    if($dstatusid != ''  and $dstatusid != NULL and $dstatusid != '0')
                    {
                        $dstatus = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_mis_res_status WHERE fld_mis_id='".$misid."' AND fld_id='".$dstatusid."' AND fld_dest_id='".$destid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                    }          
                    else if($dstatus=='' or $dstatus=='0' or $dstatus==NULL)
                    {
                        $createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
                        $dstatus = $ObjDB->SelectSingleValue("SELECT fld_status FROM itc_mis_res_status WHERE fld_mis_id='".$misid."' AND fld_dest_id='".$destid."' AND fld_created_by IN (".$createdids.") AND fld_flag='1'");
                    }

                    if($dstatus == 1)
                    {
                        $taskcount=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_mis_dest_play_track 
                                                                    WHERE fld_student_id='".$stdtaskid[1]."' AND fld_mis_id='".$misid."' 
                                                                    AND fld_dest_id='".$destid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_schedule_type='18' AND fld_delstatus='0'");//AND fld_schedule_id='$stdtaskid[0]' 
                        if($taskcount>0)
                        {
                            $taskinpcnt=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_mis_dest_play_track WHERE fld_student_id='".$stdtaskid[1]."' AND fld_mis_id='".$misid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_dest_id='".$destid."'
                                                                        AND fld_read_status='0' AND fld_delstatus='0' AND fld_schedule_type='18'");//count 0=>Completed; 1=>Inprogress for destination checkbox  - AND fld_schedule_id='$stdtaskid[0]' 
                        }
                        else
                        {
                            $taskinpcnt=1;

                        }
                        ?>
                        <ul class="tree" style="margin-left: 15px;">
                            <li>
                                <input type="checkbox" value="0" class="destcls destselect" id="dest_<?php echo $destid; ?>" <?php if($taskinpcnt==0){ echo 'checked="checked"';}else{ echo 'disabled="disabled"';} ?>>
                                <label><?php echo $destname;?></label>
                                <ul class='expanded'>
                                    <?php
        
                                    $qrytaskdetails=$ObjDB->QueryObject("SELECT fld_id as taskid,fld_task_name AS taskname 
                                                                        FROM itc_mis_task_master 
                                                                        WHERE fld_dest_id='".$destid."' AND fld_delstatus='0'");
                                    if($qrytaskdetails->num_rows>0)
                                    {
                                        while($rowtaskdetails = $qrytaskdetails->fetch_assoc())
                                        {
                                            extract($rowtaskdetails);
                                            $tstatusid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_res_status WHERE fld_mis_id='".$misid."' AND fld_task_id='".$taskid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");

                                            if($tstatusid != ''  and $tstatusid != NULL and $tstatusid != '0')
                                            {
                                                $tstatus = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_mis_res_status WHERE fld_mis_id='".$misid."' AND fld_id='".$tstatusid."' AND fld_task_id='".$taskid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                                            }
                                            else if($tstatus=='' or $tstatus=='0' or $tstatus==NULL)
                                            {
                                                $createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
                                                $tstatus = $ObjDB->SelectSingleValue("SELECT fld_status FROM itc_mis_res_status WHERE fld_mis_id='".$misid."' AND fld_task_id='".$taskid."' AND fld_created_by IN (".$createdids.") AND fld_flag='1'");
                                            }

                                            if($tstatus == 1)
                                            { 
                                                $rescount=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_mis_task_play_track 
                                                                                    WHERE fld_student_id='".$stdtaskid[1]."' AND fld_mis_id='".$misid."'  
                                                                                    AND fld_dest_id='".$destid."' AND fld_task_id='".$taskid."' AND fld_schedule_type='18' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_delstatus='0'");//AND fld_schedule_id='".$stdtaskid[0]."'
                                                if($rescount>0)
                                                {
                                                    $resinpcnt=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_mis_task_play_track WHERE fld_student_id='".$stdtaskid[1]."' AND fld_mis_id='".$misid."' AND fld_dest_id='".$destid."' AND fld_task_id='".$taskid."' AND fld_schedule_id='".$stdtaskid[0]."'
                                                                                                AND fld_read_status='0' AND fld_delstatus='0' AND fld_schedule_type='18'");//0=>Completed; 1=>Inprogress for task checkbox - AND fld_schedule_id='".$stdtaskid[0]."' 
                                                }
                                                else
                                                {
                                                    $resinpcnt=1;

                                                }

                                                    $qryresourcedetails=$ObjDB->QueryObject("SELECT fld_id AS resid,fld_res_name AS resname
                                                                                           FROM itc_mis_resource_master
                                                                                           WHERE fld_task_id='".$taskid."' AND fld_delstatus='0'");

                                                    if($qryresourcedetails->num_rows>0)
                                                    {
                                                            while($rowresourcedetails = $qryresourcedetails->fetch_assoc())
                                                            {
                                                                extract($rowresourcedetails);
                                                                $rstatusid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_res_status WHERE fld_mis_id='".$misid."' AND fld_res_id='".$resid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                                                                if($rstatusid != ''  and $rstatusid != NULL and $rstatusid != '0'){
                                                                    $rstatus = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_mis_res_status WHERE fld_mis_id='".$misid."' AND fld_id='".$rstatusid."' AND fld_res_id='".$resid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                                                                }
                                                                if($rstatus=='' or $rstatus=='0' or $rstatus==NULL)
                                                                {
                                                                    $createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");

                                                                    $rstatus = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_mis_res_status WHERE fld_mis_id='".$misid."' AND fld_res_id='".$resid."' AND fld_created_by IN (".$createdids.") AND fld_flag='1'");
                                                                }
                                                                if($rstatus == 1)
                                                                {
                                                                    $rescount1=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_mis_res_play_track 
                                                                                    WHERE fld_student_id='".$stdtaskid[1]."' AND fld_mis_id='".$misid."'  
                                                                                    AND fld_dest_id='".$destid."' AND fld_task_id='".$taskid."' AND fld_res_id='".$resid."' AND fld_schedule_type='18' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_delstatus='0'");//AND fld_schedule_id='".$stdtaskid[0]."'
                                                                    if($rescount1>0)
                                                                    {
                                                                        $resinpcnt1=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_mis_res_play_track 
                                                                                                            WHERE fld_student_id='".$stdtaskid[1]."' AND fld_mis_id='".$misid."'  
                                                                                                            AND fld_dest_id='".$destid."' AND fld_task_id='".$taskid."' AND fld_schedule_type='18' AND fld_res_id='".$resid."' AND fld_schedule_id='".$stdtaskid[0]."' AND fld_read_status='0' AND fld_delstatus='0'");//0=>Completed; 1=>Inprogress for task checkbox  AND fld_schedule_id='".$stdtaskid[0]."'
                                                                    }
                                                                    else
                                                                    {
                                                                        $resinpcnt1=1;

                                                                    }

                                                                    $qrysessiontime=$ObjDB->QueryObject("SELECT a.varValue AS sessiontime FROM itc_mis_scorm_track as a
                                                                                                            JOIN itc_mis_res_play_track as b on b.fld_id=a.SCOInstanceID
                                                                                                            WHERE a.varName='cmi.core.session_time' AND b.fld_student_id='".$stdtaskid[1]."' AND b.fld_res_id='".$resid."' AND b.fld_schedule_id='".$stdtaskid[0]."' AND b.fld_schedule_type='18' AND b.fld_read_status='1'");
                                                                    if($qrysessiontime->num_rows>0)
                                                                    {
                                                                        while($rowsessiontime=$qrysessiontime->fetch_assoc())
                                                                        {
                                                                            extract($rowsessiontime);
                                                                            $arrtime[]=$sessiontime;
                                                                        }
                                                                    }
                                                                    $sessiontime1='';
                                                                    for($i=0;$i<sizeof($arrtime);$i++)
                                                                    {  
                                                                        $j = $i+1;
                                                                        if($sessiontime1=='')
                                                                            $sessiontime1="$j. ".$arrtime[$i];
                                                                        else
                                                                            $sessiontime1=$sessiontime1."\n"."$j. ".$arrtime[$i];
                                                                    } 

                                                                    ?>
                                                                    <li>
                                                                        <input title="<?php echo $sessiontime1;?>" type="checkbox" class="resd select_<?php echo $taskid ;?> rescls_<?php echo $resid ;?> destresid_<?php echo $destid; ?>" id="res_<?php echo $taskid."~".$resid."~".$destid;?>" <?php if($resinpcnt1==0){ echo 'checked="checked"';}else{ echo 'disabled="disabled"';} ?>>
                                                                        <label title="<?php echo $sessiontime1;?>"><?php echo $resname;?></label>
                                                                    </li>
                                                                    <script>
                                                                        $(".select").click(function(e){ 
                                                                            var tid=this.id;
                                                                            var untask = tid.split("_");
                                                                            var tid=tid.replace("tsk_", "res_"); 
                                                                           $('.ZebraDialogOverlay').css("z-index","20000");
                                                                           $('.ZebraDialog').css("z-index","20000");
                                                                           $('.ZebraDialogOverlay').removeClass('ZebraDialogOverlay').addClass('ZebraDialogOverlay1');
                                                                           $('#gantt_here').css("opacity","0.40");
                                                                           $('#fancybox-wrap').css("opacity","0.40");
                                                                           $.Zebra_Dialog('Are you sure you want to assign this task again?',
                                                                           {
                                                                               'type': 'confirmation',
                                                                               'buttons': [
                                                                                   {caption: 'No', callback: function() { 
                                                                                       var tid1=tid.replace("res_", "");
                                                                                       tid1=tid1.split("~");
                                                                                       $('.destselect_'+tid1[0]).prop('checked', true);
                                                                                       $('.destselect_'+tid1[0]).prop('disabled', false);
                                                                                       $('.ZebraDialog').remove();
                                                                                       $('.ZebraDialogOverlay1').remove();
                                                                                       $('#gantt_here').css("opacity", "");
                                                                                       $('#fancybox-wrap').css("opacity", "");
                                                                                   }},
                                                                                   {caption: 'Yes', callback: function() {
                                                                                       fn_taskuncheck(untask[1],'<?php echo $stdtaskid[1];?>','<?php echo $stdtaskid[0];?>','<?php echo $misid;?>','<?php echo $stdtaskid[3];?>');
                                                                                       $('.ZebraDialogOverlay').removeClass('ZebraDialogOverlay').addClass('ZebraDialogOverlay1');
                                                                                       $('.ZebraDialog').remove();
                                                                                       $('.ZebraDialogOverlay1').remove();
                                                                                       $('#gantt_here').css("opacity", "");
                                                                                       $('#fancybox-wrap').css("opacity", "");
                                                                                       var tid1=tid.replace("res_", "");
                                                                                       tid1=tid1.split("~");
                                                                                       $('.destselect_'+tid1[0]).prop('checked', false);
                                                                                       $('.destselect_'+tid1[0]).prop('disabled', true);
                                                                                       $('.select_'+tid1[0]).each(function(){
                                                                                       $('#dest_'+tid1[1]).prop('checked', false);
                                                                                       $('#dest_'+tid1[1]).prop('disabled', true);
                                                                                       $('.select_'+tid1[0]).prop('checked', false);
                                                                                       $('.select_'+tid1[0]).prop('disabled', true);
                                                                                      });
                                                                                   }},
                                                                               ]
                                                                           });
                                                                          });

                                                                       $(".resd").click(function(e){  
                                                                           var id=this.id;
                                                                           var unres = id.split("_");
                                                                           var reid=id.replace("res_", "");
                                                                           $('.ZebraDialogOverlay').css("z-index","20000");
                                                                           $('.ZebraDialog').css("z-index","20000");
                                                                           $('.ZebraDialogOverlay').removeClass('ZebraDialogOverlay').addClass('ZebraDialogOverlay1');
                                                                           $('#gantt_here').css("opacity","0.40");
                                                                           $('#fancybox-wrap').css("opacity","0.40");
                                                                           $.Zebra_Dialog('Are you sure you want to assign this resource again?',
                                                                           {
                                                                               'type': 'confirmation',
                                                                               'buttons': [
                                                                                   {caption: 'No', callback: function() { 
                                                                                       var resid1=reid.split("~");
                                                                                       $('.rescls_'+resid1[1]).prop('checked', true);
                                                                                       $('.rescls_'+resid1[1]).prop('disabled', false);
                                                                                       $('#tsk_<?php echo $taskid;?>').attr('checked', true);
                                                                                       $('#tsk_<?php echo $taskid;?>').attr('disabled', false);
                                                                                       $('#dest_<?php echo $destid; ?>').attr('checked', true);
                                                                                       $('#dest_<?php echo $destid; ?>').attr('disabled', false);
                                                                                       $('.ZebraDialog').remove();
                                                                                       $('.ZebraDialogOverlay1').remove();
                                                                                       $('#gantt_here').css("opacity", "");
                                                                                       $('#fancybox-wrap').css("opacity", "");
                                                                                   }},
                                                                                   {caption: 'Yes', callback: function() {
                                                                                       fn_resuncheck(unres[1],'<?php echo $stdtaskid[1];?>','<?php echo $stdtaskid[0];?>','<?php echo $misid;?>','<?php echo $stdtaskid[3];?>');
                                                                                       $('.ZebraDialogOverlay').removeClass('ZebraDialogOverlay').addClass('ZebraDialogOverlay1');
                                                                                       $('.ZebraDialog').remove();
                                                                                       $('.ZebraDialogOverlay1').remove();
                                                                                       $('#gantt_here').css("opacity", "");
                                                                                       $('#fancybox-wrap').css("opacity", ""); 
                                                                                       var reid=id.replace("res_", "");
                                                                                       var reid = reid.split("~");
                                                                                       $('.rescls_'+reid[1]).prop('checked', false);
                                                                                       $('.rescls_'+reid[1]).prop('disabled', true);
                                                                                       $('.destselect_'+reid[0]).prop('checked', false);
                                                                                       $('.destselect_'+reid[0]).prop('disabled', true);
                                                                                       $('#dest_'+reid[2]).attr('checked', false);
                                                                                       $('#dest_'+reid[2]).attr('disabled', true);
                                                                                   }},
                                                                               ]
                                                                           });
                                                                       });


                                                                       $(".destselect").click(function(e){ 
                                                                           var dstid=this.id;
                                                                           var undest = dstid.split("_");
                                                                           var dstid=dstid.replace("dest_", "tsk_");
                                                                           $('.ZebraDialogOverlay').css("z-index","20000");
                                                                           $('.ZebraDialog').css("z-index","20000");
                                                                           $('.ZebraDialogOverlay').removeClass('ZebraDialogOverlay').addClass('ZebraDialogOverlay1');
                                                                           $('#gantt_here').css("opacity","0.40");
                                                                           $('#fancybox-wrap').css("opacity","0.40");
                                                                           $.Zebra_Dialog('Are you sure you want to assign this destination again?',
                                                                           {
                                                                               'type': 'confirmation',
                                                                               'buttons': [
                                                                                   {caption: 'No', callback: function() {  

                                                                                      var destid1=dstid.replace("tsk_", "");
                                                                                       $('#dest_'+destid1).prop('checked', true);
                                                                                       $('#dest_'+destid1).prop('disabled', false);
                                                                                       $('.ZebraDialog').remove();
                                                                                       $('.ZebraDialogOverlay1').remove();
                                                                                       $('#gantt_here').css("opacity", "");
                                                                                       $('#fancybox-wrap').css("opacity", "");
                                                                                   }},
                                                                                   {caption: 'Yes', callback: function() {
                                                                                       fn_destuncheck(undest[1],'<?php echo $stdtaskid[1];?>','<?php echo $stdtaskid[0];?>','<?php echo $misid;?>','<?php echo $stdtaskid[3];?>'); 
                                                                                       $('.ZebraDialogOverlay').removeClass('ZebraDialogOverlay').addClass('ZebraDialogOverlay1');
                                                                                       $('.ZebraDialog').remove();
                                                                                       $('.ZebraDialogOverlay1').remove();
                                                                                       $('#gantt_here').css("opacity", "");
                                                                                       $('#fancybox-wrap').css("opacity", ""); 
                                                                                       var dstid1=dstid.replace("tsk_", "");
                                                                                       $('#dest_'+dstid1).prop('checked', false);
                                                                                       $('#dest_'+dstid1).prop('disabled', true);
                                                                                       $('.destclsid_'+dstid1).each(function(){
                                                                                            $('.destclsid_'+dstid1).prop('checked', false);
                                                                                            $('.destclsid_'+dstid1).prop('disabled', true);
                                                                                            $('.destresid_'+dstid1).prop('checked', false);
                                                                                            $('.destresid_'+dstid1).prop('disabled', true);
                                                                                       });
                                                                                   }},
                                                                               ]
                                                                           });
                                                                         });
                                                                    </script>
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                        
                                            } //$tstatus
                                        }
                                    }
                                    ?>
                                </ul>
                            </li>
                        </ul>
                        <?php
                    }
                }
                ?>
            </div>
            <?php
        }
    } 
    /*****Mission Code End Here Developed By Mohan M 27-11-2015*******/
    
}


if($oper=="uncheckdest" and $oper != " " )
{
    
    $destid = (isset($_REQUEST['destid'])) ? $_REQUEST['destid'] : '';
    $studentid = (isset($_REQUEST['studentid'])) ? $_REQUEST['studentid'] : '';
    $schudleid = (isset($_REQUEST['schudleid'])) ? $_REQUEST['schudleid'] : '';
    $expid = (isset($_REQUEST['expid'])) ? $_REQUEST['expid'] : '';
    $schtype = (isset($_REQUEST['schtype'])) ? $_REQUEST['schtype'] : '';

    $expormis = (isset($_REQUEST['expormis'])) ? $_REQUEST['expormis'] : '';
    if($expormis=='4')
    {
        
        $ObjDB->NonQuery("UPDATE itc_exp_dest_play_track 
                      SET fld_read_status ='0', fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."'  
                      WHERE fld_student_id='".$studentid."'  AND fld_exp_id='".$expid."' AND fld_dest_id='".$destid."' AND fld_schedule_id='".$schudleid."' AND fld_schedule_type='".$schtype."' AND fld_delstatus='0'");//AND fld_schedule_id='".$schudleid."'

        $ObjDB->NonQuery("UPDATE itc_exp_task_play_track 
                      SET fld_read_status ='0', fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."'  
                      WHERE fld_student_id='".$studentid."'  AND fld_exp_id='".$expid."' AND fld_dest_id='".$destid."' AND fld_schedule_id='".$schudleid."' AND fld_schedule_type='".$schtype."' AND fld_delstatus='0'");//AND fld_schedule_id='".$schudleid."'
        
        
        $ObjDB->NonQuery("UPDATE itc_exp_res_play_track 
                      SET fld_delstatus ='1', fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."'  
                      WHERE fld_student_id='".$studentid."' AND fld_schedule_id='".$schudleid."'  AND fld_exp_id='".$expid."' AND fld_dest_id='".$destid."' AND fld_schedule_type='".$schtype."' AND fld_delstatus='0'");
        
    }
    else if ($expormis=='7') 
    { //Mission code 
        
        $ObjDB->NonQuery("UPDATE itc_mis_dest_play_track 
                      SET fld_read_status ='0', fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."'  
                      WHERE fld_student_id='".$studentid."'  AND fld_mis_id='".$expid."' AND fld_dest_id='".$destid."' AND fld_schedule_type='".$schtype."' AND fld_schedule_id='".$schudleid."' AND fld_delstatus='0'");//AND fld_schedule_id='".$schudleid."'

        $ObjDB->NonQuery("UPDATE itc_mis_task_play_track 
                      SET fld_read_status ='0', fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."'  
                      WHERE fld_student_id='".$studentid."'  AND fld_mis_id='".$expid."' AND fld_dest_id='".$destid."' AND fld_schedule_type='".$schtype."' AND fld_schedule_id='".$schudleid."' AND fld_delstatus='0'");//AND fld_schedule_id='".$schudleid."'
        
        
        $ObjDB->NonQuery("UPDATE itc_mis_res_play_track 
                      SET fld_delstatus ='1', fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."'  
                      WHERE fld_student_id='".$studentid."' AND fld_schedule_id='".$schudleid."'  AND fld_mis_id='".$expid."' AND fld_schedule_type='".$schtype."' AND fld_dest_id='".$destid."' AND fld_delstatus='0'");
        
        
    } //Mission code 
}
if($oper=="taskuncheck" and $oper != " " )
{
    
    $taskid = (isset($_REQUEST['taskid'])) ? $_REQUEST['taskid'] : '';
    $studentid = (isset($_REQUEST['studentid'])) ? $_REQUEST['studentid'] : '';
    $schudleid = (isset($_REQUEST['schudleid'])) ? $_REQUEST['schudleid'] : '';
    $expid = (isset($_REQUEST['expid'])) ? $_REQUEST['expid'] : '';
    $schtype = (isset($_REQUEST['schtype'])) ? $_REQUEST['schtype'] : '';
    
    $expormis = (isset($_REQUEST['expormis'])) ? $_REQUEST['expormis'] : '';
  
    if($expormis=='4')
    {
        $ObjDB->NonQuery("UPDATE itc_exp_task_play_track 
                                SET fld_read_status ='0', fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."'  
                                WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$expid."' AND fld_task_id='".$taskid."' AND fld_schedule_id='".$schudleid."' AND fld_schedule_type='".$schtype."' AND fld_delstatus='0'");//AND fld_schedule_id='".$schudleid."'

        $taskwithdest=$ObjDB->SelectSingleValue("SELECT fld_dest_id FROM itc_exp_task_play_track 
                                                    WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$expid."' AND fld_task_id='".$taskid."' AND fld_schedule_type='".$schtype."' AND fld_schedule_id='".$schudleid."' AND fld_delstatus='0'");//AND fld_schedule_id='".$schudleid."' 

                
        $ObjDB->NonQuery("UPDATE itc_exp_res_play_track 
                          SET fld_delstatus ='1', fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."'  
                          WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$expid."' AND fld_task_id='".$taskid."' AND fld_schedule_type='".$schtype."' AND fld_schedule_id='".$schudleid."' AND fld_delstatus='0'");
        
        $ObjDB->NonQuery("UPDATE itc_exp_dest_play_track 
                        SET fld_read_status ='0', fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."'  
                        WHERE fld_student_id='".$studentid."'  AND fld_exp_id='".$expid."' AND fld_dest_id='".$taskwithdest."' AND fld_schedule_type='".$schtype."' AND fld_schedule_id='".$schudleid."' AND fld_delstatus='0'");//AND fld_schedule_id='".$schudleid."'
  
    }
    else if ($expormis=='7') 
    { //Mission code 
        
        $ObjDB->NonQuery("UPDATE itc_mis_task_play_track 
                                SET fld_read_status ='0', fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."'  
                                WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$expid."' AND fld_task_id='".$taskid."' AND fld_schedule_type='".$schtype."' AND fld_schedule_id='".$schudleid."' AND fld_delstatus='0'");//AND fld_schedule_id='".$schudleid."'

        $taskwithdest=$ObjDB->SelectSingleValue("SELECT fld_dest_id FROM itc_mis_task_play_track 
                                                    WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$expid."' AND fld_schedule_type='".$schtype."' AND fld_task_id='".$taskid."' AND fld_delstatus='0'");//AND fld_schedule_id='".$schudleid."' 

                
        $ObjDB->NonQuery("UPDATE itc_mis_res_play_track 
                          SET fld_delstatus ='1', fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."'  
                          WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$expid."' AND fld_task_id='".$taskid."' AND fld_schedule_type='".$schtype."' AND fld_delstatus='0'");
        
        $ObjDB->NonQuery("UPDATE itc_mis_dest_play_track 
                        SET fld_read_status ='0', fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."'  
                        WHERE fld_student_id='".$studentid."'  AND fld_mis_id='".$expid."' AND fld_dest_id='".$taskwithdest."' AND fld_schedule_type='".$schtype."' AND fld_delstatus='0'");//AND fld_schedule_id='".$schudleid."'
  
        
    } //Mission code 
}
if($oper=="resuncheck" and $oper != " " )
{
    
    $resid = (isset($_REQUEST['resid'])) ? $_REQUEST['resid'] : '';
    $resid= explode("~",$resid);
    $studentid = (isset($_REQUEST['studentid'])) ? $_REQUEST['studentid'] : '';
    $schudleid = (isset($_REQUEST['schudleid'])) ? $_REQUEST['schudleid'] : '';
    $expid = (isset($_REQUEST['expid'])) ? $_REQUEST['expid'] : '';
    $schtype = (isset($_REQUEST['schtype'])) ? $_REQUEST['schtype'] : '';
    
    $expormis = (isset($_REQUEST['expormis'])) ? $_REQUEST['expormis'] : '';

    if($expormis=='4')
    {           
         
        
        $ObjDB->NonQuery("UPDATE itc_exp_res_play_track 
                            SET fld_delstatus ='1', fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."'  
                            WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$expid."' AND fld_task_id='".$resid[0]."' AND fld_schedule_id='".$schudleid."' AND fld_schedule_type='".$schtype."' AND fld_res_id='".$resid[1]."' AND fld_delstatus='0'");

        $ObjDB->NonQuery("UPDATE itc_exp_task_play_track 
                            SET fld_read_status ='0', fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."'  
                            WHERE fld_student_id='".$studentid."'  AND fld_exp_id='".$expid."' AND fld_task_id='".$resid[0]."' AND fld_schedule_id='".$schudleid."' AND fld_schedule_type='".$schtype."' AND fld_delstatus='0'");//AND fld_schedule_id='".$schudleid."'

        $ObjDB->NonQuery("UPDATE itc_exp_dest_play_track 
                            SET fld_read_status ='0', fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."'  
                            WHERE fld_student_id='".$studentid."' AND fld_exp_id='".$expid."' AND fld_dest_id='".$resid[2]."' AND fld_schedule_id='".$schudleid."' AND fld_schedule_type='".$schtype."' AND fld_delstatus='0'");//AND fld_schedule_id='".$schudleid."' 

    }
    else if ($expormis=='7') 
    { //Mission code 
        
        $ObjDB->NonQuery("UPDATE itc_mis_res_play_track 
                            SET fld_delstatus ='1', fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."'  
                            WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$expid."' AND fld_task_id='".$resid[0]."' AND fld_schedule_type='".$schtype."' AND fld_schedule_id='".$schudleid."' AND fld_res_id='".$resid[1]."' AND fld_delstatus='0'");

        $ObjDB->NonQuery("UPDATE itc_mis_task_play_track 
                            SET fld_read_status ='0', fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."'  
                            WHERE fld_student_id='".$studentid."'  AND fld_mis_id='".$expid."' AND fld_task_id='".$resid[0]."' AND fld_schedule_type='".$schtype."' AND fld_schedule_id='".$schudleid."' AND fld_delstatus='0'");//AND fld_schedule_id='".$schudleid."'

        $ObjDB->NonQuery("UPDATE itc_mis_dest_play_track 
                            SET fld_read_status ='0', fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."'  
                            WHERE fld_student_id='".$studentid."' AND fld_mis_id='".$expid."' AND fld_dest_id='".$resid[2]."' AND fld_schedule_type='".$schtype."' AND fld_schedule_id='".$schudleid."' AND fld_delstatus='0'");//AND fld_schedule_id='".$schudleid."' 
        
    } //Mission code 
}