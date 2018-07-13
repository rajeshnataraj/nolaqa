<?php
error_reporting(0);
@include("sessioncheck.php");

require_once '../../PHPExcel.php';

$ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$id=explode(",",$ids);

$exqry="";
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$extraqry = '';

$classid = $id[1];

//Inner Grade Book
if($id[0]==2)
{
	$name=str_replace(' ','_',$id[2]);
        $filename = $name."_".date("Y-m-d_H-i",time());
	
	$objPHPExcel->getProperties()->setCreator("PITSCO")
                                    ->setLastModifiedBy("PITSCO")
                                    ->setTitle($name)
                                    ->setSubject($name)
                                    ->setDescription($name)
                                    ->setKeywords($name)
                                    ->setCategory($name);
        
        $styleThinBlackBorderOutline = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('argb' => 'FF000000'),
			)
		),
	); 
        
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

        $qryhead = $ObjDB->QueryObject("(SELECT a.fld_id AS scheduleid, b.fld_unit_id AS minids, '0' AS maxids, fn_shortname (c.fld_unit_name, 1) AS nam, c.fld_unit_name AS fullnam, 
                                        0 AS typeids, a.fld_schedule_name AS schname, a.fld_start_date AS startdate, a.fld_end_date AS enddate
                                        FROM itc_class_sigmath_master AS a 
                                        LEFT JOIN itc_class_sigmath_unit_mapping AS b ON a.fld_id = b.fld_sigmath_id 
                                        LEFT JOIN itc_unit_master AS c ON c.fld_id = b.fld_unit_id 
                                        WHERE a.fld_class_id = '".$classid."' AND a.fld_flag = '1' AND a.fld_delstatus = '0' AND b.fld_flag = '1' AND c.fld_activestatus = '0' AND c.fld_delstatus = '0' ".$sqry.") 	
                                                        UNION ALL		
                                        (SELECT a.fld_schedule_id AS scheduleid, MIN(a.fld_rotation-1) AS minids, MAX(a.fld_rotation-1) AS maxids,'Rotation ' AS nam, 'Rotation ' AS fullnam, 
                                        (CASE WHEN a.fld_type='1' AND b.fld_type='1' THEN '1' WHEN a.fld_type='2' AND b.fld_type='2' 
                                        THEN '4' WHEN b.fld_type='8' THEN '8' END) AS typeids, c.fld_schedule_name AS schname, d.fld_startdate AS startdate, d.fld_enddate AS enddate 
                                        FROM itc_class_rotation_schedulegriddet AS a 
                                        LEFT JOIN itc_class_rotation_moduledet AS b ON b.fld_schedule_id=a.fld_schedule_id 
                                        left join itc_class_rotation_schedule_mastertemp as c on a.fld_schedule_id=c.fld_id
                                        LEFT JOIN itc_class_rotation_scheduledate AS d ON a.fld_schedule_id=d.fld_schedule_id and a.fld_rotation=d.fld_rotation
                                        WHERE a.fld_class_id='".$classid."' AND b.fld_flag='1' AND a.fld_flag='1' and c.fld_delstatus='0' AND d.fld_flag='1' ".$sqry5." 
                                        GROUP BY a.fld_schedule_id ) 		
                                                        UNION ALL
                                        (SELECT a.fld_id AS scheduleid, (MIN(DISTINCT(b.fld_rotation))) AS minids, (MAX(DISTINCT(b.fld_rotation))) AS maxids, 'Rotation ' AS nam, 'Rotation ' AS fullnam, 2 AS typeids, a.fld_schedule_name AS schname, b.fld_startdate AS startdate, b.fld_enddate AS enddate 
                                        FROM itc_class_dyad_schedulemaster AS a 
                                        LEFT JOIN itc_class_dyad_schedulegriddet AS b ON b.fld_schedule_id=a.fld_id
                                        WHERE a.fld_class_id='".$classid."' AND b.fld_flag='1' AND a.fld_delstatus='0' ".$sqry2."
                                        GROUP BY a.fld_id )
                                                        UNION ALL
                                        (SELECT a.fld_id AS scheduleid, (MIN(DISTINCT(b.fld_rotation))) AS minids, (MAX(DISTINCT(b.fld_rotation))) AS maxids, 'Rotation ' AS nam, 'Rotation ' AS fullnam, 3 AS typeids, a.fld_schedule_name AS schname, b.fld_startdate AS startdate, b.fld_enddate AS enddate 
                                        FROM itc_class_triad_schedulemaster AS a 
                                        LEFT JOIN itc_class_triad_schedulegriddet AS b ON b.fld_schedule_id=a.fld_id
                                        WHERE a.fld_class_id='".$classid."' AND b.fld_flag='1' AND a.fld_delstatus='0' ".$sqry2."
                                        GROUP BY a.fld_id ) 		
                                                        UNION ALL
                                        (SELECT a.fld_id AS scheduleid, a.fld_module_id AS minids, '0' AS maxids, fn_shortname(CONCAT(b.fld_module_name,' / Ind Module'),1) AS nam, 
                                        CONCAT(b.fld_module_name,' / Ind Module') AS fullnam, 5 AS typeids, a.fld_schedule_name AS schname, a.fld_startdate AS startdate, a.fld_enddate AS enddate 
                                        FROM itc_class_indassesment_master AS a 
                                        LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
                                        WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND a.fld_moduletype='1' AND b.fld_delstatus='0' ".$sqry1." 
                                        GROUP BY a.fld_id )  		
                                                        UNION ALL		
                                        (SELECT a.fld_id AS scheduleid, a.fld_module_id AS ids, '0' AS maxids, fn_shortname(CONCAT(b.fld_mathmodule_name,' / Ind MathModule'),1) AS nam, 
                                        CONCAT(b.fld_mathmodule_name,' / Ind MathModule') AS fullnam, 6 AS typeids, a.fld_schedule_name AS schname, a.fld_startdate AS startdate, a.fld_enddate AS enddate
                                        FROM itc_class_indassesment_master AS a 
                                        LEFT JOIN itc_mathmodule_master AS b ON a.fld_module_id=b.fld_id
                                        WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND a.fld_moduletype='2' AND b.fld_delstatus='0' ".$sqry1."
                                        GROUP BY a.fld_id)
                                                        UNION ALL	
                                        (SELECT a.fld_id AS scheduleid, a.fld_module_id AS minids, '0' AS maxids, fn_shortname(CONCAT(b.fld_module_name,' / Quest'),1) AS nam, 
                                        CONCAT(b.fld_module_name,' / Quest') AS fullnam, 7 AS typeids, a.fld_schedule_name AS schname, a.fld_startdate AS startdate, a.fld_enddate AS enddate 
                                        FROM itc_class_indassesment_master AS a 
                                        LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id
                                        WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND a.fld_moduletype='7' AND b.fld_delstatus='0' ".$sqry1."
                                        GROUP BY a.fld_id) 
                                                        UNION ALL	
                                        (SELECT a.fld_id AS scheduleid, a.fld_exp_id AS minids, '0' AS maxids, fn_shortname(CONCAT(b.fld_exp_name,' / Expedition'),1) AS nam, 
                                        CONCAT(b.fld_exp_name,' / Expedition') AS fullnam, 15 AS typeids, a.fld_schedule_name AS schname, a.fld_startdate AS startdate, a.fld_enddate AS enddate 
                                        FROM itc_class_indasexpedition_master AS a 
                                        LEFT JOIN itc_exp_master AS b ON a.fld_exp_id=b.fld_id
                                        WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_delstatus='0' ".$sqry1."
                                        GROUP BY a.fld_id) 
                                        
                                                        UNION ALL 		
					(SELECT a.fld_schedule_id AS scheduleid, MIN(a.fld_rotation-1) AS minids, MAX(a.fld_rotation-1) AS maxids,'Rotation ' AS nam, 'Rotation ' AS fullnam, 
                                        19 AS typeids, c.fld_schedule_name AS schname, d.fld_startdate AS startdate, d.fld_enddate AS enddate 
                                        FROM itc_class_rotation_expschedulegriddet AS a 
                                        LEFT JOIN itc_class_rotation_expmoduledet AS b ON b.fld_schedule_id=a.fld_schedule_id 
                                        left join itc_class_rotation_expschedule_mastertemp as c on a.fld_schedule_id=c.fld_id
                                        LEFT JOIN itc_class_rotation_expscheduledate AS d ON a.fld_schedule_id=d.fld_schedule_id and a.fld_rotation=d.fld_rotation
                                        LEFT JOIN itc_class_rotation_expschedule_student_mappingtemp as e ON a.fld_schedule_id=e.fld_schedule_id
                                        WHERE a.fld_class_id='".$classid."' AND b.fld_flag='1' AND a.fld_flag='1' AND e.fld_flag='1' and c.fld_delstatus='0' AND d.fld_flag='1' ".$sqry5." 
                                        GROUP BY a.fld_schedule_id )
                                                        UNION ALL 		
                                        (SELECT a.fld_class_id AS scheduleid, b.fld_id AS minids, '0' AS maxids, fn_shortname(b.fld_test_name,1) 
                                        AS nam, b.fld_test_name AS fullnam, 9 AS typeids, 'Test' AS schname, a.fld_start_date AS startdate, a.fld_end_date AS enddate 
                                        FROM itc_test_student_mapping AS a 
                                        LEFT JOIN itc_test_master AS b ON a.fld_test_id=b.fld_id 
                                        WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_delstatus='0' AND b.fld_ass_type='0' ".$sqry."
                                        GROUP BY b.fld_id) 		
                                                        UNION ALL	
                                        (SELECT a.fld_class_id AS scheduleid, a.fld_activity_id AS minids, '0' AS maxids, 
                                        fn_shortname(b.fld_activity_name,1) AS nam, b.fld_activity_name AS fullnam, 
                                        10 AS typeids, 'Activity' AS schname, a.fld_start_date AS startdate, a.fld_end_date AS enddate 
                                        FROM itc_activity_student_mapping AS a 
                                        LEFT JOIN itc_activity_master AS b ON a.fld_activity_id=b.fld_id 
                                        WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_delstatus='0' ".$sqry."
                                        GROUP BY a.fld_activity_id) 
                                                        UNION ALL	
                                        (SELECT a.fld_id AS scheduleid, a.fld_exp_id AS minids, b.fld_id AS maxids, 
                                        fn_shortname(CONCAT(b.fld_rub_name,' / Rubric'),1) AS nam, 
                                         CONCAT(b.fld_rub_name,' / ', c.fld_exp_name, ' / Rubric ') AS fullnam, 16 AS typeids, a.fld_schedule_name AS schname, a.fld_startdate AS startdate, a.fld_enddate AS enddate 
                                        FROM itc_class_indasexpedition_master AS a 
                                        LEFT JOIN itc_exp_rubric_name_master AS b ON a.fld_exp_id=b.fld_exp_id
                                         LEFT JOIN  itc_exp_master AS c ON a.fld_exp_id = c.fld_id
                                        WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND c.fld_delstatus='0'
                                        AND b.fld_delstatus='0' ".$sqry1.")"); //rubric
        
        if($qryhead->num_rows>0)
        {
            $unitids = array();
            $cnt=0;
            $rowid = 5;
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValueByColumnAndRow('2','5', "Student Name ");
            $colid = 3;
            while($rowqryhead = $qryhead->fetch_assoc()) // show the module based on number of copies
            {
                extract($rowqryhead);
                $assid[$cnt]=$minids;
                $maxassid[$cnt]=$maxids;
                $scheduleids[$cnt]=$scheduleid;
                $type[$cnt]=$typeids;
                if($typeids==0 || $typeids==10 || $typeids==5 || $typeids==6 || $typeids==7 || $typeids==9 || $typeids==15  || $typeids==16) //rubric
                {
                    $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValueByColumnAndRow($colid,$rowid, $fullnam."\n".$schname."\n".$startdate." To ".$enddate);
                    $colid++;
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
                        
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValueByColumnAndRow($colid,$rowid, $rotname."\n".$schname."\n".$startdate." To ".$enddate);
                        $colid++;                        
                    }
                }                
                $cnt++;
            }
            
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValueByColumnAndRow($colid,$rowid, "Total \nPoints Earned");
            $colid++; 
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValueByColumnAndRow($colid,$rowid, "Total \nPoints Possible");            
            $colid++; 
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValueByColumnAndRow($colid,$rowid, "Percentage");
            $colid++; 
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValueByColumnAndRow($colid,$rowid, "Grade");
            $colid++; 
            $objPHPExcel->getActiveSheet()->getStyle(PHPExcel_Cell::stringFromColumnIndex(2).$rowid.":".PHPExcel_Cell::stringFromColumnIndex($colid).$rowid)->getFont()->setBold(true);
        }
        
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
                $colid = 2;
                $rowid++;
                extract($rowqrystudent);
                $totalpointsearned = 0;
                $totalpointspossible = 0;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValueByColumnAndRow($colid,$rowid, $studentname);
                $colid ++;

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
                                        
                                        $objPHPExcel->setActiveSheetIndex(0)
                                                    ->setCellValueByColumnAndRow($colid,$rowid, $percentage.' % '.$grade."\n".$pointsearned.' / '.$pointspossible);
                                        $colid++;
                                    }
                                }
                            }
                            else
                            { 
                                $objPHPExcel->setActiveSheetIndex(0)
                                            ->setCellValueByColumnAndRow($colid,$rowid, "No IPLs");
                                $colid++;
                            }
                        } 

                        else if($type[$j]==5 || $type[$j]==6 || $type[$j]==7) 
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
                                        $objPHPExcel->setActiveSheetIndex(0)
                                                    ->setCellValueByColumnAndRow($colid,$rowid, $percentage.' % '.$grade."\n".$pointsearned.' / '.$pointspossible);
                                        $colid++;
                                    }
                                }																				
                            }
                            else
                            { 
                                if($type[$j]==6) $names="No Ind MathModule"; else if($type[$j]==5) $names="No Ind Module"; else if($type[$j]==7) $names="No Ind Quest";
                                $objPHPExcel->setActiveSheetIndex(0)
                                            ->setCellValueByColumnAndRow($colid,$rowid, $names);
                                $colid++;
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

                                $qry = $ObjDB->QueryObject("select a.fld_exptestid as testid,b.fld_test_name as testname,b.fld_total_question AS quescount from itc_exptest_toogle as a
                                                                left join itc_test_master as b on b.fld_id=a.fld_exptestid
                                                                left join itc_exp_master as c on c.fld_id=a.fld_texpid
                                                                where a.fld_texpid='".$assid[$j]."' and a.fld_flag='1' and b.fld_delstatus='0' 
                                                                and c.fld_delstatus='0' and a.fld_created_by='".$uid."' AND a.fld_status IN (1,2) order by testid ASC");
                                if($qry->num_rows>0)
                                {
                                    while($rowqry = $qry->fetch_assoc())
                                    {
                                        extract($rowqry);

                                        $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");

                                        $tchpointcnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_points_master WHERE fld_student_id='".$fld_student_id."' AND fld_exp_id='".$assid[$j]."' AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");

                                        if($tchpointcnt==0)
                                        {
                                            $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                    LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                    WHERE b.fld_expt = '".$assid[$j]."' AND a.fld_student_id = '".$fld_student_id."' 
                                                                                                    AND a.fld_test_id='".$testid."' AND fld_schedule_id='".$scheduleids[$j]."' 
                                                                                                    AND fld_schedule_type='".$type[$j]."' AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");

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
                                                                                        END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$fld_student_id."'
                                                                                                AND fld_exp_id='".$assid[$j]."' AND fld_schedule_id='".$scheduleids[$j]."' 
                                                                                                AND fld_schedule_type='".$type[$j]."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");
                                            if($tchpointearn !=0)
                                            {
                                                $possiblepointfortest+=$possiblepointfortest1;
                                            }
                                            $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
                                        }
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
                                 $objPHPExcel->setActiveSheetIndex(0)
                                             ->setCellValueByColumnAndRow($colid,$rowid, $percentage.' % '.$grade."\n".$pointsearned.' / '.$pointspossible);
                                $colid++;
                                
                                $totalpointsearned = $totalpointsearned + $pointsearned;
                                $totalpointspossible = $totalpointspossible + $pointspossible;	
                                
                            }
                            else
                            { 
                                $objPHPExcel->setActiveSheetIndex(0)
                                            ->setCellValueByColumnAndRow($colid,$rowid, "No Expedition");
                                $colid++;
                            }
                        }

                        else if($type[$j]==19) 
                        { 
                                    $expstudentcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
										    FROM itc_class_rotation_expschedule_student_mappingtemp
                                                                                    WHERE fld_schedule_id='".$scheduleids[$j]."' 
                                                                                            AND fld_student_id='".$fld_student_id."' 
                                                                                            AND fld_flag='1'");
                                    
							if($expstudentcount!=0)
                                                        {
                                                                                                            
                                                                              $rotid=$assid[$j]+1;                             
                                                                              $qryexpsch = $ObjDB->QueryObject("SELECT fld_expedition_id AS expid, (SELECT fld_exp_name FROM itc_exp_master WHERE fld_id=fld_expedition_id) AS expname  FROM `itc_class_rotation_expschedulegriddet` WHERE fld_class_id='".$classid."' AND fld_student_id='".$fld_student_id."' AND fld_schedule_id='".$scheduleids[$j]."' AND fld_rotation='".$rotid."' AND fld_flag='1'  LIMIT 0,1");
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
$qrytestcount = $ObjDB->SelectSingleValueInt("select count(a.fld_exptestid) from itc_exptest_toogle as a
                                                            left join itc_test_master as b on b.fld_id=a.fld_exptestid
                                                            left join itc_exp_master as c on c.fld_id=a.fld_texpid
                                                            where a.fld_texpid='".$schexpid."' and a.fld_flag='1' and b.fld_delstatus='0' 
                                                                    and c.fld_delstatus='0' and a.fld_created_by='".$uid."' AND a.fld_status IN (1,2)"); 

/************** Pre/Post test code end here ***************/
														$qrypoints = $ObjDB->QueryObject("SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
																								THEN fld_teacher_points_earned END) AS pointsearned, 
																								SUM(fld_points_possible) AS pointspossible 
																							FROM itc_exp_points_master 
																							WHERE fld_student_id='".$fld_student_id."' AND fld_exp_id='".$schexpid."' 
																							AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."' 
                                                                             AND (fld_points_earned<>'' OR fld_teacher_points_earned<>'') AND fld_grade='1' AND fld_exptype='2'");
														if($qrypoints->num_rows>0)
														{
															while($rowqrypoints = $qrypoints->fetch_assoc())
															{
																extract($rowqrypoints);
/************** Pre/Post test code start here ***************/
                $pointsearnedtest=0;
                $pointsearnedfortest=0;
                $possiblepointfortest1=0;
                $possiblepointfortest=0;
																	
                $qry = $ObjDB->QueryObject("select a.fld_exptestid as testid,b.fld_test_name as testname,b.fld_total_question AS quescount from itc_exptest_toogle as a
                                                    left join itc_test_master as b on b.fld_id=a.fld_exptestid
                                                    left join itc_exp_master as c on c.fld_id=a.fld_texpid
                                                    where a.fld_texpid='".$schexpid."' and a.fld_flag='1' and b.fld_delstatus='0' 
                                                            and c.fld_delstatus='0' and a.fld_created_by='".$uid."' AND a.fld_status IN (1,2) order by testid ASC");
                if($qry->num_rows>0)
																	{
                    
                    while($rowqry = $qry->fetch_assoc())
                    {
                            extract($rowqry);
                        $possiblepointfortest1 = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master where fld_id='".$testid."' and fld_delstatus='0';");
                        $possiblepointfortest+=$possiblepointfortest1;

                        $tchpointcnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_points_master WHERE fld_student_id='".$fld_student_id."' AND fld_exp_id='".$schexpid."' AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");
                        if($tchpointcnt==0)
                        {
                       $correctcountfortest = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_test_student_answer_track AS a
                                                                                                                                            LEFT JOIN itc_test_master AS b ON a.fld_test_id = b.fld_id
                                                                                                                                                       WHERE b.fld_expt = '".$schexpid."' AND a.fld_student_id = '".$fld_student_id."' AND a.fld_test_id='".$testid."' AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."'
                                                                                                                                                       AND b.fld_delstatus = '0' AND a.fld_show = '1' AND a.fld_delstatus = '0'");
                     
                            if($correctcountfortest != '0')
																		{
                                $pointsearnedfortest = $pointsearnedfortest+$correctcountfortest*($possiblepointfortest1/$quescount);
                      
																		}
                        }
                            else
                            {
                            $tchpointearn = $ObjDB->SelectSingleValueInt("SELECT (CASE
                                                                                    WHEN fld_lock = '0' THEN fld_points_earned
                                                                                    WHEN fld_lock = '1' THEN fld_teacher_points_earned
                                                                                END) AS pointsearned FROM itc_exp_points_master WHERE fld_student_id='".$fld_student_id."'
                                                                                    AND fld_exp_id='".$schexpid."' AND fld_schedule_id='".$scheduleids[$j]."' 
                                                                                    AND fld_schedule_type='".$type[$j]."' AND fld_grade='1' AND fld_res_id='".$testid."' AND fld_exptype='3'");
                            $pointsearnedfortest = $pointsearnedfortest+$tchpointearn;
																		}
																	}
																}
                
                $pointsearnedtest=($pointsearnedfortest/$possiblepointfortest)*100;
                /****/
      
$teachpointsearn = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_points_possible)
                                                                                     FROM itc_exp_points_master 
                                                                             WHERE fld_student_id='".$fld_student_id."' AND fld_exp_id='".$schexpid."' 
                                                                             AND fld_schedule_id='".$scheduleids[$j]."' AND fld_schedule_type='".$type[$j]."' 
                                                                             AND (fld_points_earned<>'' OR fld_teacher_points_earned<>'') AND fld_grade='1' AND fld_exptype='2'");

                if($teachpointsearn=='0')
                {
                    $pointspossible="-";
                    $pointsearned="-";
                    $percentage = "-";
                    $grade = "NA";
}
/****/               

                if($pointspossible=='' AND $pointspossible!='0'){
                    $pointspossible = "-";
                   
                }
                else
                {
                    if($pointsearnedtest!='0')
                    {
                        $possiblepointfortest=100;
                            $pointsearned=round($pointsearnedtest + $pointsearned,2);
                            $pointspossible=$possiblepointfortest + $pointspossible;
                            
                    }
                }

            //percentage code start here
                if($pointsearned==='' AND $pointspossible!='0')
                {
                        $pointsearned = "-";
                        $percentage = "-";
                        $grade = "NA";
                }
                else
                {
                        if($roundflag==0)
                        $percentage = round(($pointsearned/$pointspossible)*100,2);
                        else
                        $percentage = round(($pointsearned/$pointspossible)*100);
                        
                        $totalpointsearned = $totalpointsearned + $pointsearned;
                        $totalpointspossible = $totalpointspossible + $pointspossible;
                                 
                        $perarray = explode('.',$percentage);
                        $grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$classid."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
                }
                if($percentage==0){
                        $percentage = "-";
                        $grade = "NA";
                }
                                                    $objPHPExcel->setActiveSheetIndex(0)
                                                    ->setCellValueByColumnAndRow($colid,$rowid, $percentage.' % '.$grade."\n".$pointsearned.' / '.$pointspossible);
                                                    $colid++;
                                }
                        }																				
                }
                else
                { 
                    $objPHPExcel->setActiveSheetIndex(0)
                                            ->setCellValueByColumnAndRow($colid,$rowid, "No Expedition");
                                $colid++;
                }
           
        }

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
                                                                    WHERE a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_student_id='".$fld_student_id."' AND b.fld_class_id='".$classid."' AND b.fld_flag='1' 
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
                                                                                WHERE fld_student_id='".$fld_student_id."' AND fld_test_id='".$assid[$j]."' AND fld_delstatus='0'");

                                        $teacherpoint = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned 
                                                                                    FROM itc_test_student_mapping 
                                                                                    WHERE fld_student_id='".$fld_student_id."' AND fld_test_id='".$assid[$j]."' AND fld_flag='1' AND fld_class_id='".$classid."'");

                                        if($teacherpoint=='')
                                        {
                                            if($testtype == '1')
                                            {
                                                $correctcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) 
                                                                                                FROM itc_test_student_answer_track 
                                                                                                WHERE fld_student_id='".$fld_student_id."' AND fld_test_id='".$assid[$j]."' 
                                                                                                        AND fld_correct_answer='1' AND fld_delstatus='0'");
                                                $pointsearned = round(($correctcount/$totalques)*$pointspossible,2);
                                            }
                                            else if($testtype == '2')
                                            {
                                                $qryrandomtest = $ObjDB->QueryObject("SELECT fld_id AS testtagid, fld_pct_section AS percent, fld_qn_assign AS totques
                                                                                        FROM itc_test_random_questionassign
                                                                                        WHERE fld_rtest_id='".$assid[$j]."' AND fld_delstatus='0' 
                                                                                        ORDER BY fld_order_by");
                                                if($qryrandomtest->num_rows>0)
                                                {
                                                    while($rowqryrandomtest = $qryrandomtest->fetch_assoc())
                                                    {
                                                        extract($rowqryrandomtest);

                                                        $perscore = ($percent / 100)*$pointspossible;

                                                        $correctcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) 
                                                                                                        FROM itc_test_student_answer_track 
                                                                                                        WHERE fld_student_id='".$fld_student_id."' AND fld_test_id='".$assid[$j]."' AND fld_tag_id='".$testtagid."'
                                                                                                                AND fld_correct_answer='1' AND fld_delstatus='0'");

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
                                            $grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$classid."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
                                        }
                                        if($qcount!=0 || $teacherpoint!='')
                                        {
                                            $totalpointsearned = $totalpointsearned + $pointsearned;
                                            $totalpointspossible = $totalpointspossible + $pointspossible;
                                        }  
                                        
                                        $objPHPExcel->setActiveSheetIndex(0)
                                                    ->setCellValueByColumnAndRow($colid,$rowid, $percentage.' % '.$grade."\n".$pointsearned.' / '.$pointspossible);
                                        $colid++;
                                    }
                                }
                            }
                            else
                            { 
                                $objPHPExcel->setActiveSheetIndex(0)
                                            ->setCellValueByColumnAndRow($colid,$rowid, "No Assessment");
                                $colid++;
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
                                        
                                        $objPHPExcel->setActiveSheetIndex(0)
                                                    ->setCellValueByColumnAndRow($colid,$rowid, $percentage.' % '.$grade."\n".$pointsearned.' / '.$pointspossible);
                                        $colid++;
                                    }
                                }
                            }
                            else
                            { 
                                $objPHPExcel->setActiveSheetIndex(0)
                                            ->setCellValueByColumnAndRow($colid,$rowid, "No Activity");
                                $colid++;
                            }
                        } 

                       //rubric start
else if($type[$j]==16) 
{ 
   $studentcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                        FROM itc_class_exp_student_mapping 
                                                                        WHERE fld_schedule_id='".$scheduleids[$j]."' 
                                                                                AND fld_student_id='".$fld_student_id."' 
                                                                                AND fld_flag='1'");


    if($studentcount!=0)
    {
            $qrypoints = $ObjDB->QueryObject("SELECT sum(a.fld_score)pointsearned,(SELECT sum(fld_score) FROM itc_exp_rubric_master 
                                            WHERE fld_exp_id='".$assid[$j]."' AND fld_delstatus='0' AND fld_rubric_id='".$maxassid[$j]."') as pointspossible 
                                            FROM itc_exp_rubric_rpt_statement AS a
                                            LEFT JOIN  itc_exp_rubric_rpt AS b ON b.fld_id=a.fld_rubric_rpt_id
                                            WHERE a.fld_rubric_nameid = '".$maxassid[$j]."'  AND a.fld_student_id = '".$fld_student_id."'  AND a.fld_delstatus = '0' 
                                            AND b.fld_class_id='".$classid."' AND a.fld_created_by='".$uid."'");
            
     
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
                                $pointspossible= "-";
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
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValueByColumnAndRow($colid,$rowid, $percentage.' % '.$grade."\n".$pointsearned.' / '.$pointspossible);
                        $colid++;
                }
        }
    }
    else
    { 
         $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValueByColumnAndRow($colid,$rowid, "No Rubric");
         $colid++;
       
    }
    
}
//rubric end                     
                        
                        
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
                                        
                                        $objPHPExcel->setActiveSheetIndex(0)
                                                    ->setCellValueByColumnAndRow($colid,$rowid, $modulename."\n".$percentage.' % '.$grade."\n".$pointsearned.' / '.$pointspossible);
                                        $colid++;
                                    }
                                }
                                else
                                { 
                                    $objPHPExcel->setActiveSheetIndex(0)
                                                ->setCellValueByColumnAndRow($colid,$rowid, "No Modules");
                                    $colid++;
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
                
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValueByColumnAndRow($colid,$rowid, $totalpointsearned);
                $colid++;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValueByColumnAndRow($colid,$rowid, $totalpointspossible);
                $colid++;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValueByColumnAndRow($colid,$rowid, $totalpercentage);
                $colid++;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValueByColumnAndRow($colid,$rowid, $totalgrade);
                $colid++;
            }
        }
        
        for($j=5;$j<=$rowid;$j++)
        {
            for($k=2;$k<=$colid;$k++)
            {
                $objPHPExcel->getActiveSheet()->getStyle(PHPExcel_Cell::stringFromColumnIndex($k).$j)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle(PHPExcel_Cell::stringFromColumnIndex($k).$j)->applyFromArray($styleThinBlackBorderOutline);
                $objPHPExcel->getActiveSheet()->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($k))->setAutoSize(true);
            }
        }
        
        $objPHPExcel->getActiveSheet()->getStyle("C5:".PHPExcel_Cell::stringFromColumnIndex($colid).$rowid)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("C5:".PHPExcel_Cell::stringFromColumnIndex($colid).$rowid)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('Simple');
	
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	// Redirect output to a client's web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$filename.'.csv"');
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
}

include("footer.php");
exit;