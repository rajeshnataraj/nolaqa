<?php
error_reporting(0);
@include("sessioncheck.php");
require_once '../../PHPExcel.php';

$ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$id = explode(",",$ids);

$schlid = $id[0];
$scholid= explode("-",$schlid);
$schoolid=$scholid[0];
$distid=$scholid[1];

$clasid = $id[1];
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

$csv_hdr = "";
$out .= $csv_hdr;

if($clasid==0)
{
    $qryclass = $ObjDB->QueryObject("SELECT fld_id AS classid, fld_class_name AS classname, fld_period AS period  FROM itc_class_master 
                                                WHERE fld_school_id='".$schoolid."' AND fld_district_id='".$distid."' AND fld_delstatus='0' 
                                                GROUP BY classid ORDER BY fld_class_name");//LIMIT 0,10
}
else
{
    $qryclass = $ObjDB->QueryObject("SELECT fld_id AS classid, fld_class_name AS classname, fld_period AS period  FROM itc_class_master 
                                                WHERE fld_id='".$clasid."' AND fld_delstatus='0' 
                                                GROUP BY classid ORDER BY fld_class_name");
}

if($qryclass->num_rows > 0)
{ 
   // $out .= ",,,Rotation ,Module Guide ,Posttest";
    $out .= "School Name ,Class Name ,Schedule Name ,Rotation ,Module Guide ,Posttest";
    $out .= "\n";
    
    while($rowqryclass=$qryclass->fetch_assoc())
    {
        extract($rowqryclass);
        
        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        if (($period %100) >= 11 and ($period%100) <= 13)
           $abbreviation = $period. 'th';
        else
           $abbreviation = $period. $ends[$period % 10];
        
        $schoolname = $ObjDB->SelectSingleValue("SELECT fld_school_name FROM itc_school_master WHERE fld_id='".$schoolid."'");
      
        $qry = $ObjDB->QueryObject("SELECT w.* FROM (
                                        (SELECT CONCAT(a.fld_schedule_name,' / ',(CASE WHEN a.fld_moduletype='1' THEN 'Module' 
                                                WHEN a.fld_moduletype='2' THEN 'MM' END)) AS schedulename, a.fld_id AS scheduleid, 
                                                (CASE WHEN a.fld_moduletype='1' THEN '1' WHEN a.fld_moduletype='2' THEN '4' END) AS schtype 
                                                FROM itc_class_rotation_schedule_mastertemp as a
                                                LEFT JOIN itc_class_rotation_schedule_student_mappingtemp AS b ON b.fld_schedule_id=a.fld_id
                                                WHERE a.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND a.fld_flag='1' AND b.fld_flag='1' group by scheduleid) 	
                                        UNION ALL	
                                                (SELECT CONCAT(fld_schedule_name,' / Mod And Exp') AS schedulename, fld_id AS scheduleid,
                                                20 AS schtype FROM itc_class_rotation_modexpschedule_mastertemp 
                                                WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1')
                                        UNION ALL
                                            (SELECT CONCAT(fld_schedule_name,' / WCA') AS schedulename, fld_id AS scheduleid, 
                                               (CASE WHEN fld_moduletype='1' THEN '5' WHEN fld_moduletype='2' THEN '6' WHEN fld_moduletype='7' THEN '7' END) AS schtype 
                                               FROM itc_class_indassesment_master 
                                               WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1' AND fld_moduletype<>'17')
                                            ) AS w 
                                        ORDER BY w.schtype, w.schedulename");

        if($qry->num_rows>0)
        { 	
            while($row = $qry->fetch_assoc())
            {
                extract($row);
                if($schtype==1) //Module Rotation code star here
                {
                    $qryrot = $ObjDB->QueryObject("SELECT fld_rotation AS rotation, fld_rotation-1 AS realrotation 
                                                                                    FROM itc_class_rotation_schedulegriddet 
                                                                                            WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1' 
                                                                                                    GROUP BY fld_rotation ORDER BY fld_rotation");
                    if($qryrot->num_rows>0)
                    {
                        while($rowqryrot = $qryrot->fetch_assoc())
                        {
                            extract($rowqryrot);
                            
                            if($realrotation==0){ $rotname="Orientation"; }else{ $rotname="Rotation".$realrotation; }
                            
                            $out .= "School Name : ".$schoolname.",Class Name : ".$classname.' '.$abbreviation.' Period ,';
                            $out .= "Schedule Name : ".$schedulename.",";
                            $out .= $rotname.",";
                           
                            $qrystudentandmod = $ObjDB->QueryObject("SELECT b.fld_student_id AS studentid, b.fld_module_id AS modids, b.fld_type AS newtype
                                                                        FROM itc_class_rotation_schedulegriddet AS b 
                                                                                WHERE b.fld_schedule_id = '".$scheduleid."' AND b.fld_rotation='".$rotation."' 
                                                                                AND b.fld_class_id = '".$classid."' AND b.fld_flag='1'  
                                                                                GROUP BY studentid ORDER BY studentid");
                            if($qrystudentandmod->num_rows>0)
                            {
                                $totmodguide=0;
                                $totalpretest=0;

                                while($rowqrystudentandmod = $qrystudentandmod->fetch_assoc())
                                {
                                    extract($rowqrystudentandmod);

                                    $qrypoints = $ObjDB->QueryObject("SELECT IFNULL(GROUP_CONCAT(CASE WHEN fld_lock='0' AND fld_session_id='0' THEN fld_points_earned END),'-') AS moduleguide,
                                                                            IFNULL(GROUP_CONCAT(CASE WHEN fld_lock='0' AND fld_session_id='6' THEN fld_points_earned END),'-') AS pretest
                                                                    FROM itc_module_points_master 
                                                                    WHERE fld_module_id='".$modids."' AND fld_schedule_type='".$newtype."' 
                                                                            AND fld_student_id='".$studentid."' AND fld_schedule_id='".$scheduleid."' AND fld_type='0'
                                                                            AND (fld_session_id='0' OR fld_session_id='6')");

                                    if($qrypoints->num_rows>0)
                                    {
                                        $rowqrypoints = $qrypoints->fetch_assoc();
                                        extract($rowqrypoints);

                                        if($moduleguide!='-' AND $pretest!='-')
                                        {
                                            $totmodguide+=$moduleguide;
                                            $totalpretest+=$pretest;
                                        }
                                    }
                                    else
                                    {
                                        $totmodguide+=0;
                                        $totalpretest+=0;
                                    }

                                } //Student and Mod Loop End Here

                                //echo "<br>".$totmodguide."".$totalpretest."mm<br>";
                                if($totmodguide==0)
                                {
                                        $totmodguide=' - ';
                                        $totalpretest=' - ';
                                }

                                if($totalpretest==0)
                                {
                                        $totmodguide=' - ';
                                        $totalpretest=' - ';
                                }
                            }
                            $out .= $totmodguide.",".$totalpretest;
                            $out .= "\n";
                        } //Rotation WHile Loop End here
                    }
                } 
                else if($schtype==20) //Mod or Exp Schedule
                {
                    $out .= "School Name : ".$schoolname.",Class Name : ".$classname.' '.$abbreviation.' Period ,';
                    $out .= "Schedule Name : ".$schedulename.",";
                    $out .= $rotname.",";
                    
                    $qryrot = $ObjDB->QueryObject("SELECT fld_rotation AS rotation, fld_rotation-1 AS realrotation 
                                                                                    FROM itc_class_rotation_modexpschedulegriddet
                                                                                            WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1' AND fld_type='1'
                                                                                                    GROUP BY fld_rotation ORDER BY fld_rotation");
                    if($qryrot->num_rows>0)
                    {
                        while($rowqryrot = $qryrot->fetch_assoc())
                        {
                            extract($rowqryrot);

                            if($realrotation==0){ $rotname="Orientation"; }else{ $rotname="Rotation".$realrotation; }

                            $out .= $rotname.",";

                            $qrystudentandmod = $ObjDB->QueryObject("SELECT b.fld_student_id AS studentid, b.fld_module_id AS modids, 21 AS newtype
                                                                        FROM itc_class_rotation_modexpschedulegriddet AS b 
                                                                                WHERE b.fld_schedule_id = '".$scheduleid."' AND b.fld_rotation='".$rotation."' 
                                                                                AND b.fld_class_id = '".$classid."' AND b.fld_flag='1' AND b.fld_type='1'
                                                                                GROUP BY studentid ORDER BY studentid");
                            if($qrystudentandmod->num_rows>0)
                            {
                                $totmodguide=0;
                                $totalpretest=0;

                                while($rowqrystudentandmod = $qrystudentandmod->fetch_assoc())
                                {
                                    extract($rowqrystudentandmod);

                                    $qrypoints = $ObjDB->QueryObject("SELECT IFNULL(GROUP_CONCAT(CASE WHEN fld_lock='0' AND fld_session_id='0' THEN fld_points_earned END),'-') AS moduleguide,
                                                                                        IFNULL(GROUP_CONCAT(CASE WHEN fld_lock='0' AND fld_session_id='6' THEN fld_points_earned END),'-') AS pretest
                                                                                FROM itc_module_points_master 
                                                                                WHERE fld_module_id='".$modids."' AND fld_schedule_type='".$newtype."' 
                                                                                        AND fld_student_id='".$studentid."' AND fld_schedule_id='".$scheduleid."' AND fld_type='0'
                                                                                        AND (fld_session_id='0' OR fld_session_id='6')");

                                    if($qrypoints->num_rows>0)
                                    {
                                        $rowqrypoints = $qrypoints->fetch_assoc();
                                        extract($rowqrypoints);
                                        if($moduleguide!='-' AND $pretest!='-')
                                        {
                                            $totmodguide+=$moduleguide;
                                            $totalpretest+=$pretest;
                                        }
                                    }
                                    else
                                    {
                                        $totmodguide+=0;
                                        $totalpretest+=0;
                                    }

                                } //Student and Mod Loop End Here
                                if($totmodguide==0)
                                {
                                    $totmodguide=' - ';
                                    $totalpretest=' - ';
                                }

                                if($totalpretest==0)
                                {
                                    $totmodguide=' - ';
                                    $totalpretest=' - ';
                                }
                            }
                            $out .= $totmodguide.",".$totalpretest;
                            $out .= "\n";
                        } //Rotation WHile Loop End here
                    }
                }
                else if($schtype==5) //WCA Module
                {
                    $out .= "School Name : ".$schoolname.",Class Name : ".$classname.' '.$abbreviation.' Period ,';
                    $out .= "Schedule Name : ".$schedulename.",";
                    $rotname='';
                    $out .= $rotname.",";  
                    
                    if($schtype==5) //WCA Module
                    {
                        $qrystudentandmod = $ObjDB->QueryObject("SELECT b.fld_student_id AS studentid, c.fld_module_id AS modid
                                                                    FROM itc_class_indassesment_student_mapping AS b  
                                                                    LEFT JOIN itc_class_indassesment_master AS c ON c.fld_id = b.fld_schedule_id
                                                                    WHERE b.fld_schedule_id = '".$scheduleid."' 
                                                                            AND b.fld_flag='1' GROUP BY studentid ORDER BY studentid");
                        if($qrystudentandmod->num_rows>0)
                        {
                            $totmodguide=0;
                            $totalpretest=0;

                            while($rowqrystudentandmod = $qrystudentandmod->fetch_assoc())
                            {
                                extract($rowqrystudentandmod);

                                $qrypoints = $ObjDB->QueryObject("SELECT IFNULL(GROUP_CONCAT(CASE WHEN fld_lock='0' AND fld_session_id='0' THEN fld_points_earned END),'-') AS moduleguide,
                                                                    IFNULL(GROUP_CONCAT(CASE WHEN fld_lock='0' AND fld_session_id='6' THEN fld_points_earned END),'-') AS pretest
                                                                    FROM itc_module_points_master 
                                                                    WHERE fld_module_id='".$modid."' AND fld_schedule_type='".$schtype."' 
                                                                            AND fld_student_id='".$studentid."' AND fld_schedule_id='".$scheduleid."' AND fld_type='0'
                                                                            AND (fld_session_id='0' OR fld_session_id='6')");

                                if($qrypoints->num_rows>0)
                                {
                                    $rowqrypoints = $qrypoints->fetch_assoc();
                                    extract($rowqrypoints);

                                    if($moduleguide!='-' AND $pretest!='-')
                                    {
                                        $totmodguide+=$moduleguide;
                                        $totalpretest+=$pretest;
                                    }
                                }
                                else
                                {
                                    $totmodguide+=0;
                                    $totalpretest+=0;
                                }
                            }
                            if($totmodguide==0)
                            {
                                $totmodguide=' - ';
                                $totalpretest=' - ';
                            }

                            if($totalpretest==0)
                            {
                                $totalpretest=' - ';
                                $totmodguide=' - ';
                            }
                                
                            $out .= $totmodguide.",".$totalpretest;
                            $out .= "\n";
                        }
                        else
                        {
                            $out .="No Records";
                            $out .= "\n";
                        }
                    }
                }
                $out .= "\n";
            } //Schedule Loop End Here
        }//Schedule if End Here
        else
        {
            //$out .="No Schedules available for ".$classname." - Class";
            //$out .="No Records";
           // $out .= "\n";
        }
    } // $rowqryclass While Loop End Here
}// $qryclass if Condition end Here
 

//Now we're ready to create a file. This method generates a filename based on the current date & time.

$name="BackupReports".date('Y-m-d')."_".date('H:i:s');

include("footer.php");
//Generate the CSV file header
header('Content-Encoding: UTF-8,UTF-16LE');
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header("Content-Disposition: csv" . date("Y-m-d") . ".csv");
header("Content-Disposition: attachment; filename=".$name.".csv");

echo $out;
//Print the contents of out to the generated file.

//print chr(255) . chr(254) . mb_convert_encoding($out, 'UTF-16LE', 'UTF-8');

//Exit the script
exit;                

include("footer.php");
exit;