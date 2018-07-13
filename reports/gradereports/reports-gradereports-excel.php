<?php
error_reporting(0);
@include("sessioncheck.php");

require_once '../../PHPExcel.php';

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

$ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$id = explode(",",$ids);


$classid=$id[1];

$schexp = explode("-",$id[2]);
$schid=$schexp[0];
$schtype=$schexp[1];


//Class Expedition report
if($id[0]==7)
{
    $name="Class_Expedition_Schedule";	

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

    $schname = $ObjDB->SelectSingleValue("SELECT fld_schedule_name FROM itc_class_rotation_expschedule_mastertemp WHERE fld_id='".$schid."'");

    $qryexpsch=$ObjDB->QueryObject("SELECT a.fld_exp_name AS expname, a.fld_id AS expid FROM itc_exp_master AS a 
                                            LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_id=b.fld_exp_id 
                                            WHERE b.fld_id='".$schid."'");
    
    $rowqryexp=$qryexpsch->fetch_assoc();
    extract($rowqryexp);
    
    $csv_hdr = "";
    $out .= $csv_hdr;

    $out .= "Class Name : ,".$classname.' '.$abbreviation.' Period';
    $out .= "\n";
    $out .= "Schedule Name : ,".$schname;
    $out .= "\n";
    $out .= "Title : ,".$expname;
    $out .= "\n\n";
    
    
    $expid = $ObjDB->SelectSingleValueINT("SELECT a.fld_exp_id  FROM itc_class_indasexpedition_master as a 
                                                        where a.fld_id='".$schid."' AND a.fld_delstatus='0' AND a.fld_flag='1'");

    $schoolid = $ObjDB->SelectSingleValueINT("SELECT a.fld_school_id FROM itc_user_master as a 
                                                        where a.fld_id='".$uid."' AND a.fld_delstatus='0' ");

    $sendistid = $ObjDB->SelectSingleValueINT("SELECT a.fld_district_id FROM itc_user_master as a 
                                                    where a.fld_id='".$uid."' AND a.fld_delstatus='0' ");
    
    
    $rubricname=array();
    $rubricid=array();
    $typeids=array();
    $schids=array();
    $quescount=array();
    $questypes=array();

    $qry = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS rubnam, 
                                        CONCAT(a.fld_rubric_name) AS nam, 1 AS type, c.fld_id AS expid, NULL AS questype FROM itc_class_expmis_rubricmaster AS a 
                                        LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                        LEFT JOIN  itc_exp_master AS c ON a.fld_expmisid = c.fld_id
                                        LEFT JOIN itc_class_indasexpedition_master AS d ON a.fld_schedule_id=d.fld_id 
                                                WHERE a.fld_class_id='".$id[1]."' AND a.fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  AND a.fld_schedule_type='15'
                                                        AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                        AND b.fld_school_id IN(0,".$schoolid.")
                                UNION ALL 
                                    SELECT NULL AS scheduleid, a.fld_pretest AS rubricids, b.fld_total_question AS rubnam, b.fld_test_name as nam, 
                                    2 AS type, a.fld_texpid AS expid,b.fld_question_type AS questype FROM itc_exp_ass AS a
                                    LEFT JOIN itc_test_master AS b ON b.fld_id=a.fld_pretest 
                                    WHERE a.fld_class_id='".$id[1]."' AND a.fld_sch_id='".$schid."' AND a.fld_schtype_id='".$schtype."' AND a.fld_pretest NOT IN(0)
                                UNION ALL
                                    SELECT NULL AS scheduleid, a.fld_posttest AS rubricids, b.fld_total_question AS rubnam, b.fld_test_name as nam,
                                    3 AS type, a.fld_texpid AS expid,b.fld_question_type AS questype FROM itc_exp_ass AS a
                                    LEFT JOIN itc_test_master AS b ON b.fld_id=a.fld_posttest 
                                    WHERE a.fld_class_id='".$id[1]."' AND a.fld_sch_id='".$schid."' AND a.fld_schtype_id='".$schtype."' AND a.fld_posttest NOT IN(0)
                                ");
    if($qry->num_rows>0)
    {
        while($rowqry = $qry->fetch_assoc()) // show the module based on number of copies
        {
            extract($rowqry);

            $rubricname[]=$nam;
            $schids[]=$scheduleid;
            $rubricid[]=$rubricids;
            $typeids[]=$type;
            $quescount[]=$rubnam;
            $questypes[]=$questype;
        }
    }
    
    $out .= "Student,";
                
    for($j=0;$j<sizeof($rubricname);$j++)
    {
        $out .= $rubricname[$j].",";
    } 
    if(sizeof($rubricname)!='0')
    {
        $out .= "Total Earned";
    }
    else
    {
        $out .= "";
    }
     $out .= "\n";
                   
    $qrystudent=$ObjDB->QueryObject("SELECT fld_student_id AS stuid FROM itc_class_exp_student_mapping WHERE fld_schedule_id='".$schid."';");
    if($qrystudent->num_rows>0)
    { ///Question IF condition
        while($rowstu=$qrystudent->fetch_assoc())
        { ///Question While Loop
            extract($rowstu);

            $pointsearnedfortest=0;
            $possiblepointfortest1=0;
            $possiblepointfortest=0;
            $username = $ObjDB->SelectSingleValue("SELECT concat(fld_lname,' ',fld_fname) FROM itc_user_master WHERE fld_id='".$stuid."' AND fld_delstatus='0'");
    
            $out .= $username.",";
    
            $totalpointsearned=0;
            $totalpointspoints=0;
            for($i=0;$i<sizeof($rubricname);$i++)
            {
                if($typeids[$i]=='1') //Rubric
                {
                    $destinationids = $ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_exp_rubric_dest_master WHERE fld_rubric_name_id='".$rubricid[$i]."' AND fld_exp_id='".$expid."'"); 

                    $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_master 
                                                                    WHERE fld_exp_id='".$expid."' AND fld_rubric_id='".$rubricid[$i]."' AND fld_delstatus='0'");    

                    $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_rubric_rpt WHERE fld_exp_id='".$expid."'  
                                                                        AND fld_rubric_nameid ='".$rubricid[$i]."' AND fld_class_id='".$id[1]."' AND fld_schedule_id='".$schid."'
                                                                        AND fld_delstatus='0' "); 

                    $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_exp_rubric_rpt_statement 
                                                                        WHERE fld_student_id='".$stuid."' AND fld_exp_id='".$expid."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
                                                                        AND fld_rubric_rpt_id='".$rubricrptid."'"); 
                    if($studentscore!=0)
                    {
                        $out .= $studentscore." / ".$totscore.",";
                        $totalpointsearned+=$studentscore;
                        $totalpointspoints+=$totscore;
                    }
                    else
                    {
                         $out .= " - / - ,";
                    }
                }
                else if($typeids[$i]=='2') //Pre Test
                {
                    $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$rubricid[$i]."' and fld_delstatus='0';");
                        
                    if($questypes[$i]==2)
                    {
                        $quescounttest = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$rubricid[$i]."' AND fld_delstatus='0'");
                    }
                    else
                    {
                        $quescounttest=$quescount[$i];
                    }

                    $correctcountstu="-";
                    $crctcntstu='-';
                    $qrycorrectcount = $ObjDB->QueryObject("SELECT a.fld_correct_answer AS crctcount FROM itc_test_student_answer_track AS a
                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$stuid."' 
                                                                AND a.fld_schedule_id='".$schid."' AND a.fld_schedule_type='15'  
                                                                AND a.fld_test_id='".$rubricid[$i]."' AND b.fld_delstatus = '0' AND a.fld_delstatus = '0' AND a.fld_retake='0'");//AND a.fld_show = '1' 

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
                        $tpointsearned = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master 
                                                                    WHERE fld_schedule_type='15' AND fld_student_id='".$stuid."' 
                                                                    AND fld_exp_id='".$expid."' AND fld_schedule_id='".$schid."' AND fld_exptype='3' AND fld_res_id='".$rubricid[$i]."' AND fld_delstatus = '0'");
                        if(trim($tpointsearned)=='')// ||  $tpointsearned=='0'
                        {
                            if($crctcntstu=='0')
                            {
                                $pointsearnedfortest = '0';
                            }
                            else if($crctcntstu=='-')
                            {
                                 $pointsearnedfortest = '';
                            }
                            else 
                            {
                                $pointsearnedfortest = round($crctcntstu*($possiblepointfortest1/$quescounttest),2);
                            }
                        }
                        else
                        {
                            $pointsearnedfortest=$tpointsearned;
                        }
                    /*****Teacher Points earned code End here for pre/post test*****/

                    if($pointsearnedfortest=='0')
                    {
                        $out .= round($pointsearnedfortest,2)." / ".$possiblepointfortest1.",";
                        $totalpointsearned+=$pointsearnedfortest;
                        $totalpointspoints+=$possiblepointfortest1;
                    }
                    else if($pointsearnedfortest=='-' || $pointsearnedfortest=='')
                    {
                        $out .= " - / - ,";
                    }
                    else
                    {
                        $out .= round($pointsearnedfortest,2)." / ".$possiblepointfortest1.",";
                        $totalpointsearned+=$pointsearnedfortest;
                        $totalpointspoints+=$possiblepointfortest1;
                    }
                }
                else if($typeids[$i]=='3')
                {
                    $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$rubricid[$i]."' and fld_delstatus='0';");
                        
                    if($questypes[$i]==2)
                    {
                        $quescounttest = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_avl_questions) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$rubricid[$i]."' AND fld_delstatus='0'");
                    }
                    else
                    {
                        $quescounttest=$quescount[$i];
                    }

                    $correctcountstu="-";
                    $crctcntstu='-';
                    $qrycorrectcount = $ObjDB->QueryObject("SELECT a.fld_correct_answer AS crctcount FROM itc_test_student_answer_track AS a
                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                WHERE b.fld_expt = '".$expid."' AND a.fld_student_id = '".$stuid."' 
                                                                AND a.fld_schedule_id='".$schid."' AND a.fld_schedule_type='15'  
                                                                AND a.fld_test_id='".$rubricid[$i]."' AND b.fld_delstatus = '0' AND a.fld_delstatus = '0' AND a.fld_retake='0'");//AND a.fld_show = '1' 
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
                        $tpointsearned = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master 
                                                                    WHERE fld_schedule_type='15' AND fld_student_id='".$stuid."' 
                                                                    AND fld_exp_id='".$expid."' AND fld_schedule_id='".$schid."' AND fld_exptype='3' AND fld_res_id='".$rubricid[$i]."' AND fld_delstatus = '0'");
                        if(trim($tpointsearned)=='')// ||  $tpointsearned=='0'
                        {
                            if($crctcntstu=='0')
                            {
                                $pointsearnedfortest = '0';
                            }
                            else if($crctcntstu=='-')
                            {
                                 $pointsearnedfortest = '';
                            }
                            else 
                            {
                                $pointsearnedfortest = round($crctcntstu*($possiblepointfortest1/$quescounttest),2);
                            }
                        }
                        else
                        {
                            $pointsearnedfortest=$tpointsearned;
                        }
                    /*****Teacher Points earned code End here for pre/post test*****/

                    if($pointsearnedfortest=='0')
                    {
                        $out .= round($pointsearnedfortest,2)." / ".$possiblepointfortest1.",";
                        $totalpointsearned+=$pointsearnedfortest;
                        $totalpointspoints+=$possiblepointfortest1;
                    }
                    else if($pointsearnedfortest=='-' || $pointsearnedfortest=='')
                    {
                        $out .= " - / - ,";
                    }
                    else
                    {
                        $out .= round($pointsearnedfortest,2)." / ".$possiblepointfortest1.",";
                        $totalpointsearned+=$pointsearnedfortest;
                        $totalpointspoints+=$possiblepointfortest1;
                    }
                }
            } //for loop

            if($totalpointsearned!='-' || $totalpointsearned!='0')
            {
                 $out .= round($totalpointsearned)." / ".$totalpointspoints.",";
            }
            else
            {
               $out .= " - / - ,";
            }
            $out .= "\n";
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
        
       
}

include("footer.php");
exit;