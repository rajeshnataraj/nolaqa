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

$id = explode(",",$ids);

$classid=$id[1];

$schid=explode("-",$id[2]);
$schid=$schid[0];
$schtype=$schid[1];

if($id[0]==8)
{
    $name="Class_Mission";	

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

    
    $schname = $ObjDB->SelectSingleValue("SELECT fld_schedule_name FROM itc_class_indasmission_master WHERE fld_id='".$schid."'");
    
    $qryexp = $ObjDB->QueryObject("SELECT a.fld_mis_name AS misname, a.fld_id AS misid FROM itc_mission_master AS a 
                                            LEFT JOIN itc_class_indasmission_master AS b ON a.fld_id=b.fld_mis_id 
                                            WHERE b.fld_id='".$schid."'");
    $rowqryexp=$qryexp->fetch_assoc();
    extract($rowqryexp);
                
              
    $out .= "Class Name : ,".$classname.' '.$abbreviation.' Period';
    
   
    $out .= "\n";
    $out .= "Schedule Name : ,".$schname;
    $out .= "\n";
    $out .= "Mission Name : ,".$misname;
    $out .= "\n\n";
    
    $misid = $ObjDB->SelectSingleValueINT("SELECT a.fld_mis_id  FROM itc_class_indasmission_master as a 
                                                    where a.fld_id='".$schid."' AND a.fld_delstatus='0' AND a.fld_flag='1'");

    $schoolid = $ObjDB->SelectSingleValueINT("SELECT a.fld_school_id FROM itc_user_master as a 
                                                        where a.fld_id='".$uid."' AND a.fld_delstatus='0' ");

    $sendistid = $ObjDB->SelectSingleValueINT("SELECT a.fld_district_id FROM itc_user_master as a 
                                                        where a.fld_id='".$uid."' AND a.fld_delstatus='0' ");

    $rubricname=array();
    $rubricid=array();
    $typeids=array();
    $schids=array();

    $qry = $ObjDB->QueryObject("SELECT a.fld_schedule_id AS scheduleid, a.fld_rubric_id AS rubricids, fn_shortname(CONCAT(a.fld_rubric_name,' / Rubric'),1) AS rubnam, 
                                    CONCAT(a.fld_rubric_name) AS nam, 1 AS type, NULL AS expid, NULL AS questype FROM itc_class_expmis_rubricmaster AS a 
                                    LEFT JOIN itc_mis_rubric_name_master AS b ON a.fld_rubric_id=b.fld_id
                                    LEFT JOIN  itc_mission_master AS c ON a.fld_expmisid = c.fld_id
                                    LEFT JOIN itc_class_indasmission_master AS d ON a.fld_schedule_id=d.fld_id 
                                            WHERE a.fld_class_id='".$id[1]."' AND a.fld_schedule_id='".$schid."' AND fld_schedule_type='18' AND a.fld_delstatus='0'  AND d.fld_delstatus='0'  AND a.fld_schedule_type='18'
                                                    AND b.fld_delstatus='0' AND c.fld_delstatus = '0' AND b.fld_district_id IN (0,".$sendistid.") 
                                                    AND b.fld_school_id IN(0,".$schoolid.")
                                UNION ALL 
                                    SELECT NULL AS scheduleid, a.fld_test_id AS rubricids, b.fld_total_question AS rubnam, b.fld_test_name as nam, 
                                    2 AS type, a.fld_mis_id AS expid,b.fld_question_type AS questype FROM itc_mis_ass AS a
                                    LEFT JOIN itc_test_master AS b ON b.fld_id=a.fld_test_id 
                                    WHERE a.fld_class_id='".$id[1]."' AND a.fld_sch_id='".$schid."' AND a.fld_schtype_id='18' AND a.fld_flag='1' 
                                        order by rubricids ASC");
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
    
    $out .= "Student,Participation,";
                
    for($j=0;$j<sizeof($rubricname);$j++)
    {
        $out .= $rubricname[$j].",";
        if($typeids[$j]=='2'){
            $out .= " Correct / Total".",";    
        }
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
    
    $qrystudent=$ObjDB->QueryObject("SELECT fld_student_id AS stuid FROM itc_class_mission_student_mapping WHERE fld_schedule_id='".$schid."'");
    if($qrystudent->num_rows>0)
    { ///Question IF condition
        while($rowstu=$qrystudent->fetch_assoc())
        { ///Question While Loop
            extract($rowstu);

            $pointsearnedfortest=0;
            $possiblepointfortest1=0;
            $possiblepointfortest=0;
            $username = $ObjDB->SelectSingleValue("SELECT concat(fld_lname,' ',fld_fname) FROM itc_user_master WHERE fld_id='".$stuid."' AND fld_delstatus='0'");

            $gradepointsearned = $ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned AS pointsearned FROM itc_mis_points_master 
                                                                    WHERE fld_student_id='".$stuid."' AND fld_mis_id='".$misid."' 
                                                                        AND fld_schedule_id='".$schid."' AND fld_schedule_type='18' AND fld_mistype='4'
                                                                        AND fld_grade='1' AND fld_delstatus='0'");

            $gradepointspossible = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_points_possible) AS pointspossible FROM itc_mis_points_master 
                                                                        WHERE fld_student_id='".$stuid."' AND fld_mis_id='".$misid."' 
                                                                            AND fld_schedule_id='".$schid."' AND fld_schedule_type='18'  AND fld_mistype='4' 
                                                                            AND fld_grade='1' AND fld_delstatus='0'");
    
            $out .= $username.",";
            
            if($gradepointsearned==0)
            {
                 $out .= " - / - ,";
            }
            else
            {
                 $out .= $gradepointsearned." / ".$gradepointspossible.",";
            }
            
            $totalpointsearned=0;
            $totalpointspoints=0;
            for($i=0;$i<sizeof($rubricname);$i++)
            {
                if($typeids[$i]=='1')
                {
                    $destinationids = $ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_mis_rubric_dest_master WHERE fld_rubric_name_id='".$rubricid[$i]."' AND fld_mis_id='".$misid."'"); 

                    $totscore=$ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_master 
                                                                            WHERE fld_mis_id='".$misid."' AND fld_rubric_id='".$rubricid[$i]."' AND fld_delstatus='0'");    

                    $rubricrptid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_mis_rubric_rpt WHERE fld_mis_id='".$misid."'  
                                                                        AND fld_rubric_nameid ='".$rubricid[$i]."' AND fld_class_id='".$id[1]."' AND fld_schedule_id='".$schid."'
                                                                        AND fld_delstatus='0' "); 

                    $studentscore = $ObjDB->SelectSingleValueInt("SELECT sum(fld_score) as score FROM itc_mis_rubric_rpt_statement 
                                                                        WHERE fld_student_id='".$stuid."' AND fld_mis_id='".$misid."' AND fld_dest_id IN (".$destinationids.") AND fld_delstatus='0'
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
                else if($typeids[$i]=='2')  /************* Test Code Start Here**************/
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
                                                                    WHERE b.fld_mist = '".$expid."' AND a.fld_student_id = '".$stuid."' 
                                                                    AND a.fld_test_id='".$rubricid[$i]."'  AND a.fld_schedule_id='".$schid."' 
                                                                    AND a.fld_schedule_type='18'  AND b.fld_delstatus = '0' 
                                                                        AND a.fld_delstatus = '0'");//AND a.fld_show = '1' 

                    if($qrycorrectcount->num_rows>0)
                    {
                        while($rowqrycorrectcount = $qrycorrectcount->fetch_assoc())
                        {
                            extract($rowqrycorrectcount);
                            $correctcountstu=$correctcountstu+$crctcount;
                            $crctcntstu=$crctcntstu+$crctcount;
                        }
                    }
                    
                    
                    $tchpointcnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_points_master WHERE fld_student_id='".$stuid."' AND fld_mis_id='".$expid."' 
                                                                            AND fld_schedule_id='".$schid."' AND fld_schedule_type='18' 
                                                                            AND fld_grade='1' AND fld_res_id='".$rubricid[$i]."' AND fld_mistype='3'");

                    if($tchpointcnt==0)
                    {
                        if($crctcntstu=='0')
                        {
                            $pointsearnedfortest = '0';
                        }
                        else if($crctcntstu=='-')
                        {
                            $pointsearnedfortest = '-';
                        }
                        else 
                        {
                            $pointsearnedfortest = $crctcntstu*($possiblepointfortest1/$quescounttest);
                        }
                    }
                    else
                    {
                        $tchpointearn = $ObjDB->SelectSingleValueInt("SELECT (CASE
                                                                                WHEN fld_lock = '0' THEN fld_points_earned
                                                                                WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                END) AS pointsearned FROM itc_mis_points_master WHERE fld_student_id='".$stuid."'
                                                                                AND fld_mis_id='".$expid."' AND fld_schedule_id='".$schid."' 
                                                                                AND fld_schedule_type='18' AND fld_grade='1' AND fld_res_id='".$rubricid[$i]."' 
                                                                                AND fld_mistype='3'");
                        if($tchpointearn !=0)
                        {
                            $possiblepointfortest=$possiblepointfortest1;
                            $pointsearnedfortest = $tchpointearn;
                        }
                        else 
                        {
                            $pointsearnedfortest = '';
                            $possiblepointfortest = '';
                        }

                    }
                    if($correctcountstu>='0')
                    {
                        $stucorrectcount=$correctcountstu." / ".$quescounttest;
                    }
                    else if($correctcountstu=='-')
                    {
                        $stucorrectcount='';
                    }
                    if($pointsearnedfortest==0)
                    {
                        $out .= $pointsearnedfortest." / ".$possiblepointfortest1.",".$stucorrectcount.",";
                        $totalpointsearned+=$pointsearnedfortest;
                        $totalpointspoints+=$possiblepointfortest1;
                    }
                    else if($pointsearnedfortest=='-')
                    {
                        $out .= " - / - ,";
                    }
                    else
                    {
                        $out .= $pointsearnedfortest." / ".$possiblepointfortest1.",".$stucorrectcount.",";
                        $totalpointsearned+=$pointsearnedfortest;
                        $totalpointspoints+=$possiblepointfortest1;
                    }
                    
                }
                 /************* Test Code End Here**************/
                
            }
            
            if($totalpointsearned!='-' || $totalpointsearned!='0')
            {
               $tpearned=$totalpointsearned+$gradepointsearned;
               $tppossible=$totalpointspoints+$gradepointspossible;
                
                $out .= $tpearned." / ".$tppossible.",";
            }
            else
            {
                $out .= " - / - ,";
            }
            $out .= "\n";
        }
    }
    //Now we're ready to create a file. This method generates a filename based on the current date & time.
}   

$filename = $name."_".date("Y-m-d_H-i",time());

//Generate the CSV file header
header('Content-Encoding: UTF-8,UTF-16LE');
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header("Content-Disposition: csv" . date("Y-m-d") . ".csv");
header("Content-Disposition: attachment; filename=".$filename.".csv");

echo $out;
//Print the contents of out to the generated file.

//print chr(255) . chr(254) . mb_convert_encoding($out, 'UTF-16LE', 'UTF-8');

//Exit the script
include("footer.php");
exit;   
