<?php
error_reporting(0);
@include("sessioncheck.php");


$ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$id = explode(",",$ids);

$schlid = $id[0];
$scholid= explode("-",$schlid);
$schoolid=$scholid[0];
$distid=$scholid[1];

$clasid = $id[1];


error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../../PHPExcel.php';
require_once '../../PHPExcel/IOFactory.php';

$classids=array();
$classnames=array();
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
    while($rowqryclass=$qryclass->fetch_assoc())
    {
        extract($rowqryclass);
        $classids[] = $classid;
        $classnames[] = $classname;
    }
}

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
$sheet_count = 0;

for($b=0;$b<sizeof($classids);$b++)
{
    $classid=$classids[$b];
    $classname=$classnames[$b];
    
    $objPHPExcel->createSheet();
    $objPHPExcel->setActiveSheetIndex($sheet_count);
    $schoolname = $ObjDB->SelectSingleValue("SELECT fld_school_name FROM itc_school_master WHERE fld_id='".$schoolid."'");


    $objPHPExcel->getActiveSheet()->setTitle($classname);


    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if (($period %100) >= 11 and ($period%100) <= 13)
       $abbreviation = $period. 'th';
    else
       $abbreviation = $period. $ends[$period % 10];

    $flagmod=0;

    $schid=array();
    $schtyp=array();
    $schname=array();
    
    $qrymod = $ObjDB->QueryObject("SELECT w.* FROM (
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

    if($qrymod->num_rows>0)
    { 	
        while($rowmodsch = $qrymod->fetch_assoc())
        {
            extract($rowmodsch);
            $schid[]=$scheduleid;
            $schtyp[]=$schtype;
            $schname[]=$schedulename;
            $flagmod++;
        }
    }
   // print_r($schid);

    $title=array('Rotation ','Module Guide','Posttest');

    $titlewca=array('Schedule Name','Module Guide','Posttest');

    if($flagmod!='0') //Mod Schedule
    {
        $row = 1; 
        for($x=0;$x<=2;$x++) 
        {
            $col = 0;
            if($row == 1) 
            {
                $value="School Name : ".$schoolname;
                $column=0;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $value);
                $range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
                 $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
                $style = array('font' =>
                                array('color' =>
                                  array('rgb' => '000000'),
                                  'bold' => true,
                                ),
                       'alignment' => array(

                                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                    ),
                 );
                $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(25);

            }  // ends of if($row == 1)
            else if($row == 2) 
            {
               $value="Class Name : ".$classname;
                $column=0;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $value);
                $style = array('font' =>
                                                array('color' =>
                                                  array('rgb' => '000000'),
                                                  'bold' => true,
                                                ),
                           'alignment' => array(

                                                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                                        ),
                 );
                $range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
                $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20); 
            }  // ends of elseif($row == 2)  
            else if($row == 3) 
            {   
                for($i=0;$i<sizeof($schid);$i++)
                {
                    if($schtyp[$i]==1) //Module Rotation code star here
                    {
                        $row++;   
                        $value="Schedule Name : ".$schname[$i];
                        $column=0;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $value);
                        $style = array('font' =>
                                               array('color' =>
                                                 array('rgb' => '000000'),
                                                 'bold' => true,
                                               ),
                                      'alignment' => array(

                                                 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                                   ),
                                );
                        $range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
                        $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
                        $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);

                        $row++;   
                        for($c=0;$c<3;$c++) 
                        {
                            $Questnid = $title[$c];
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($c, $row, $Questnid);
                            $style = array('font' =>
                                                array('color' =>
                                                  array('rgb' => '000000'),
                                                  'bold' => true,
                                                ),
                                       'alignment' => array(

                                                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                                    ),
                                 );
                            $range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
                            $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
                            $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);

                        }
                        $row++;    
                       // echo $schid[$i]."-".$schtyp[$i]."<br>";

                        $qryrot = $ObjDB->QueryObject("SELECT fld_rotation AS rotation, fld_rotation-1 AS realrotation 
                                                         FROM itc_class_rotation_schedulegriddet 
                                                             WHERE fld_schedule_id='".$schid[$i]."' AND fld_flag='1' 
                                                                 GROUP BY fld_rotation ORDER BY fld_rotation");
                        if($qryrot->num_rows>0)
                        {
                            while($rowqryrot = $qryrot->fetch_assoc())
                            {
                                extract($rowqryrot);
                                $column=0;
                                if($realrotation==0){ $rotname="Orientation"; }else{ $rotname="Rotation ".$realrotation; }

                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $rotname);
                                                        $style = array('font' =>
                                                                                                array('color' =>
                                                                                                  array('rgb' => '000000'),
                                                                                                  'bold' => true,
                                                                                                ),
                                                                           'alignment' => array(

                                                                                                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                                                                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                                                                                        ),
                                                                 );
                                $range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
                                $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
                                $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);

                                $qrystudentandmod = $ObjDB->QueryObject("SELECT b.fld_student_id AS studentid, b.fld_module_id AS modids, b.fld_type AS newtype
                                                                        FROM itc_class_rotation_schedulegriddet AS b 
                                                                                WHERE b.fld_schedule_id = '".$schid[$i]."' AND b.fld_rotation='".$rotation."' 
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
                                                                                AND fld_student_id='".$studentid."' AND fld_schedule_id='".$schid[$i]."' AND fld_type='0'
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
                                $column=$column+1;
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $totmodguide);
                                $style = array('font' =>
                                    array('color' =>
                                      array('rgb' => '000000'),
                                      'bold' => true,
                                    ),
                                    'alignment' => array(

                                      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                      'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                            ),
                                         );
                                $range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
                                $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
                                $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);

                                $column=$column+1;
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $totalpretest);
                                $style = array('font' =>
                                        array('color' =>
                                          array('rgb' => '000000'),
                                          'bold' => true,
                                        ),
                                        'alignment' => array(

                                          'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                          'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                                ),
                                         );
                                $range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
                                $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
                                $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
                                $row++;    
                            } //Rotation WHile Loop End here
                        }
                    } //Module Rotation code End here

                    else if($schtyp[$i]==20) //Mod or Exp Schedule
                    {
                        $row++;   
                        $value="Schedule Name : ".$schname[$i];
                        $column=0;
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $value);
                        $style = array('font' =>
                                               array('color' =>
                                                 array('rgb' => '000000'),
                                                 'bold' => true,
                                               ),
                                      'alignment' => array(

                                                 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                                   ),
                                );
                        $range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
                        $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
                        $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);

                        $row++;   
                        for($c=0;$c<3;$c++) 
                        {
                            $Questnid = $title[$c];
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($c, $row, $Questnid);
                            $style = array('font' =>
                                                array('color' =>
                                                  array('rgb' => '000000'),
                                                  'bold' => true,
                                                ),
                                       'alignment' => array(

                                                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                                    ),
                                 );
                            $range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
                            $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
                            $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);

                        }
                        $row++;

                        $qryrot = $ObjDB->QueryObject("SELECT fld_rotation AS rotation, fld_rotation-1 AS realrotation 
                                                                                                FROM itc_class_rotation_modexpschedulegriddet
                                                                                                        WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1' AND fld_type='1'
                                                                                                                GROUP BY fld_rotation ORDER BY fld_rotation");
                        if($qryrot->num_rows>0)
                        {
                            while($rowqryrot = $qryrot->fetch_assoc())
                            {
                                extract($rowqryrot);
                                $column=0;
                                if($realrotation==0){ $rotname="Orientation"; }else{ $rotname="Rotation".$realrotation; }
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $rotname);
                                                        $style = array('font' =>
                                                                                                array('color' =>
                                                                                                  array('rgb' => '000000'),
                                                                                                  'bold' => true,
                                                                                                ),
                                                                           'alignment' => array(

                                                                                                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                                                                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                                                                                        ),
                                                                 );
                                $range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
                                $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
                                $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
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

                                $column=$column+1;
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $totmodguide);
                                                        $style = array('font' =>
                                                                                                array('color' =>
                                                                                                  array('rgb' => '000000'),
                                                                                                  'bold' => true,
                                                                                                ),
                                                                           'alignment' => array(

                                                                                                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                                                                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                                                                                        ),
                                                                 );
                                $range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
                                $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
                                $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);


                                $column=$column+1;
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $totalpretest);
                                $style = array('font' =>
                                                array('color' =>
                                                  array('rgb' => '000000'),
                                                  'bold' => true,
                                                ),
                                       'alignment' => array(

                                                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                                    ),
                                 );
                                $range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
                                $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
                                $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
                                $row++;

                            } //Rotation WHile Loop End here
                        }
                    } //Mod or Exp Schedule

                    else if($schtyp[$i]==5) //WCA Module
                    {
                        $row++;   
                        for($c=0;$c<3;$c++) 
                        {
                            $Questnid = $titlewca[$c];
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($c, $row, $Questnid);
                            $style = array('font' =>
                                                array('color' =>
                                                  array('rgb' => '000000'),
                                                  'bold' => true,
                                                ),
                                       'alignment' => array(

                                                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                                    ),
                                 );
                            $range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
                            $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
                            $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);

                        }
                        $row++;    
                       // echo $schid[$i]."-".$schtyp[$i]."<br>".$schname[$i]."<br>";

                        $qrystudentandmod = $ObjDB->QueryObject("SELECT b.fld_student_id AS studentid, c.fld_module_id AS modid
                                                                                FROM itc_class_indassesment_student_mapping AS b  
                                                                                LEFT JOIN itc_class_indassesment_master AS c ON c.fld_id = b.fld_schedule_id
                                                                                WHERE b.fld_schedule_id = '".$schid[$i]."' 
                                                                                        AND b.fld_flag='1' GROUP BY studentid ORDER BY studentid");
                        if($qrystudentandmod->num_rows>0)
                        {
                            $totmodguide=0;
                            $totalpretest=0;

                            while($rowqrystudentandmod = $qrystudentandmod->fetch_assoc())
                            {
                                extract($rowqrystudentandmod);

                                $column=0;
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $schname[$i]);
                                                       $style = array('font' =>
                                                                                               array('color' =>
                                                                                                 array('rgb' => '000000'),
                                                                                                 'bold' => true,
                                                                                               ),
                                                                          'alignment' => array(

                                                                                                 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                                                                 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                                                                                       ),
                                                                 );
                                $range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
                                $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
                                $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);

                                $qrypoints = $ObjDB->QueryObject("SELECT IFNULL(GROUP_CONCAT(CASE WHEN fld_lock='0' AND fld_session_id='0' THEN fld_points_earned END),'-') AS moduleguide,
                                                                        IFNULL(GROUP_CONCAT(CASE WHEN fld_lock='0' AND fld_session_id='6' THEN fld_points_earned END),'-') AS pretest
                                                                        FROM itc_module_points_master 
                                                                        WHERE fld_module_id='".$modid."' AND fld_schedule_type='".$schtyp[$i]."' 
                                                                                AND fld_student_id='".$studentid."' AND fld_schedule_id='".$schid[$i]."' AND fld_type='0'
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
                            $column=$column+1;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $totmodguide);
                                                        $style = array('font' =>
                                                                                                array('color' =>
                                                                                                  array('rgb' => '000000'),
                                                                                                  'bold' => true,
                                                                                                ),
                                                                           'alignment' => array(

                                                                                                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                                                                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                                                                                        ),
                                                                 );
                            $range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
                            $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
                            $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);


                            $column=$column+1;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $totalpretest);
                            $style = array('font' =>
                                                array('color' =>
                                                  array('rgb' => '000000'),
                                                  'bold' => true,
                                                ),
                                       'alignment' => array(

                                                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                                    ),
                                 );
                            $range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
                            $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
                            $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
                             $row++;
                        }
                        else
                        {
                            $norec="No Records";
                            $column=0;
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $norec);
                                                   $style = array('font' =>
                                                                                           array('color' =>
                                                                                             array('rgb' => '000000'),
                                                                                             'bold' => true,
                                                                                           ),
                                                                      'alignment' => array(

                                                                                             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                                                             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                                                                                   ),
                                                             );
                            $range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
                            $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
                            $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);

                        }
                    } //WCA Module
                }// Sch id For loop ENd 
            }
            $row++;    
        }
    }
    else
    {
        $norec="No Records";
           $row = 1; 
        $column=0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $norec);
                               $style = array('font' =>
                                                                       array('color' =>
                                                                         array('rgb' => '000000'),
                                                                         'bold' => true,
                                                                       ),
                                                  'alignment' => array(

                                                                         'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                                         'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                                                               ),
                                         );
        $range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
        $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);

    }
    
    $endvalue = $objPHPExcel->setActiveSheetIndex($sheet_count)->getHighestColumn();
    for($col = 'A'; $col !== 'L'; $col++) {
        $objPHPExcel->getActiveSheet()
            ->getColumnDimension($col)
            ->setAutoSize(true);
    }
    $sheet_count++;
}

$name="BackupReports".date('Y-m-d')."_".date('H:i:s').".xls";

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=".$name."");
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
