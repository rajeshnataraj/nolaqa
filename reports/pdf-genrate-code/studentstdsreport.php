<?php
@include("sessioncheck.php");

require_once('../tcpdf/config/lang/eng.php');
require_once('../tcpdf/tcpdf.php');

$method = $_REQUEST;

$id = isset($method['id']) ? $method['id'] : '0';
$ids = explode(",",$id);
$filename = isset($method['filename']) ? $method['filename'] : '';
$url=$domainame;
$pointsearned ='';
$pointspossible=''; 

class MYPDF extends TCPDF {
	
	public function Header() { // Page header
		
		$method = $_REQUEST;
		global $ObjDB;

		$id = isset($method['id']) ? $method['id'] : '0';
		$ids=$id;
        $ids = explode(",",$ids);
		
                $name = $ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,' ',fld_lname) FROM itc_user_master WHERE fld_id='".$ids[3]."'");
		$title = "Student Progress for ".$name;
			
		$qryclass = $ObjDB->QueryObject("SELECT fld_class_name AS classname, fld_period AS period 
										FROM itc_class_master 
										WHERE fld_id='".$ids[2]."'");
			
		$row=$qryclass->fetch_assoc();
		extract($row);
		
		$ends = array('th','st','nd','rd','th','th','th','th','th','th');
		if (($period %100) >= 11 && ($period%100) <= 13)
		   $abbreviation = $period. 'th';
		else
		   $abbreviation = $period. $ends[$period % 10];

		$this->SetTextColor(0,0,0);
		$this->SetFont('arialblack', '', 18);
		$this->Text(10, 18, $title);
		$this->Image('scans/../report.png', 10, 40, 19, 8, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);		
		$this->SetFont('arialblack', '', 13);		
		$style=array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
		$this->Line(10,48,190,48, $style);		
		$this->SetFont('arial', '', 10);
		$name = $ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,' ',fld_lname) FROM itc_user_master WHERE fld_id='".$ids[3]."'");
		$this->Text(10, 50, 'Class : '.$classname);
                
                $roundflag = $ObjDB->SelectSingleValue("SELECT fld_roundflag 
										FROM itc_class_grading_scale_mapping 
										WHERE fld_class_id = '".$id[2]."' AND fld_flag = '1' 
										GROUP BY fld_roundflag");
		if($ids[1]==0)
		{
                        $qry = $ObjDB->QueryObject("SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
                                            THEN fld_teacher_points_earned END) AS pointsearned, SUM(fld_points_possible) AS pointspossible 
                                            FROM itc_assignment_sigmath_master 
                                            WHERE fld_class_id='".$ids[2]."' AND fld_student_id='".$ids[3]."' AND fld_test_type='1' and fld_schedule_id='".$ids[0]."' and fld_unitmark='0'
                                            AND (fld_status='1' OR fld_status='2' OR fld_lock='1') AND fld_delstatus='0' AND fld_grade<>'0'");
           
			$schedulename = $ObjDB->SelectSingleValue("SELECT fld_schedule_name AS schedulename 
									FROM itc_class_sigmath_master
									WHERE fld_id='".$ids[0]."'");
			
		}
		else if($ids[1]==1 || $ids[1]==4 || $ids[1]==8)
		{
			$qry = $ObjDB->QueryObject("SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
                                                    THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible 
                                                    FROM itc_module_points_master AS a 
                                                    LEFT JOIN itc_class_rotation_schedulegriddet AS b ON (a.fld_schedule_id=b.fld_schedule_id 
                                                            AND a.fld_module_id=b.`fld_module_id` AND a.fld_student_id=b.fld_student_id) 
                                                    WHERE a.fld_student_id='".$ids[3]."' AND b.fld_class_id='".$ids[2]."' AND a.fld_delstatus='0' 
                                                            AND b.fld_flag='1' AND a.fld_grade<>'0' and a.fld_schedule_id='".$ids[0]."' AND a.fld_schedule_type = '".$ids[1]."'");
                       
                        $schedulename = $ObjDB->SelectSingleValue("SELECT fld_schedule_name AS schedulename 
									FROM itc_class_rotation_schedule_mastertemp
									WHERE fld_id='".$ids[0]."'");
		}
		else if($ids[1]==2)
		{
			$qry = $ObjDB->QueryObject("SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
                                                            THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible 
                                                    FROM itc_module_points_master AS a 
                                                    LEFT JOIN `itc_class_dyad_schedulegriddet` AS b ON (a.fld_schedule_id=b.fld_schedule_id 
                                                            AND a.fld_module_id=b.`fld_module_id` AND (a.fld_student_id=b.fld_student_id OR b.fld_rotation='0')) 
                                                    WHERE a.fld_student_id='".$ids[3]."' AND b.fld_class_id='".$ids[2]."' AND a.fld_delstatus='0' 
                                                            AND b.fld_flag='1' AND a.fld_grade<>'0' and a.fld_schedule_id='".$ids[0]."' AND a.fld_schedule_type='".$ids[1]."'");
                        $schedulename = $ObjDB->SelectSingleValue("SELECT fld_schedule_name AS schedulename 
									FROM itc_class_dyad_schedulemaster
									WHERE fld_id='".$ids[0]."'");
		}
		else if($ids[1]==3)
		{
			$qry = $ObjDB->QueryObject("SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
                                                            THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible 
                                                    FROM itc_module_points_master AS a 
                                                    LEFT JOIN `itc_class_triad_schedulegriddet` AS b ON (a.fld_schedule_id=b.fld_schedule_id
                                                            AND a.fld_module_id=b.`fld_module_id` AND (a.fld_student_id=b.fld_student_id OR b.fld_rotation='0')) 
                                                    WHERE a.fld_student_id='".$ids[3]."' AND b.fld_class_id='".$id[2]."' AND a.fld_delstatus='0' 
                                                            AND b.fld_flag='1' AND a.fld_grade<>'0' and a.fld_schedule_id='".$ids[0]."' AND a.fld_schedule_type='".$ids[1]."'");
                        $schedulename = $ObjDB->SelectSingleValue("SELECT fld_schedule_name AS schedulename
									FROM itc_class_triad_schedulemaster
									WHERE fld_id='".$ids[0]."'");
		}
		else if($ids[1]==5 || $ids[1]==6 || $ids[1]==7)
		{
			$qry = $ObjDB->QueryObject("SELECT SUM(CASE WHEN a.fld_lock='0' THEN a.fld_points_earned WHEN a.fld_lock='1' 
                                                            THEN a.fld_teacher_points_earned END) AS pointsearned, SUM(a.fld_points_possible) AS pointspossible 
                                                    FROM itc_module_points_master AS a 
                                                    LEFT JOIN itc_class_indassesment_master AS b ON (a.fld_schedule_id=b.fld_id 
                                                            AND a.fld_module_id=b.fld_module_id) 
                                                    LEFT JOIN itc_class_indassesment_student_mapping AS c ON (a.fld_schedule_id=c.fld_schedule_id 
                                                            AND a.fld_student_id=c.fld_student_id) 
                                                    WHERE a.fld_student_id='".$ids[3]."' AND b.fld_class_id='".$id[2]."' AND a.fld_delstatus='0' 
                                                            AND b.fld_flag='1' AND a.fld_grade<>'0' and a.fld_schedule_id='".$ids[0]."' AND a.fld_schedule_type='".$ids[1]."'  AND b.fld_delstatus='0' AND c.fld_flag='1'");
                        $schedulename = $ObjDB->SelectSingleValue("SELECT fld_schedule_name AS schedulename
										FROM itc_class_indassesment_master
										WHERE fld_id='".$ids[0]."'");
		}
		
                    $rowqry = $qry->fetch_assoc();
                    extract($rowqry);
                if($pointsearned !='')
                {
                    if($roundflag==0)
				$percentage = round(($pointsearned/$pointspossible)*100,2);
			else
				$percentage = round(($pointsearned/$pointspossible)*100);
                        
			
			$perarray = explode('.',$percentage);			
			$grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
												FROM itc_class_grading_scale_mapping 
												WHERE fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' 
												AND fld_class_id='".$ids[2]."' AND fld_flag='1'");
                }
                else{
                    $pointsearned = " - ";
                    $pointspossible = " - ";
                    $grade = "N/A";
                }
                        		
                        $this->Text(10, 55, 'Schedule : '.$schedulename);
                        $this->Text(10, 60, 'Points : '.$pointsearned);
                        $this->Text(150, 50, 'Hours : '.$abbreviation);
			$this->Text(150, 55, 'Grade : '.$grade);
			$this->Text(150, 60, 'Points Possible : '.$pointspossible);
		
	}
	
	public function Footer() { // Page footer
		$date = date("m/d/Y"); // H:i:s A
		// Position at 15 mm from bottom
		$this->SetY(-15);		
		// Set font
		$this->SetFont('arialblack', '', 10);
		// Page number
		
		$this->Cell(30, 10, $date, 0, false, 'C', 0, '', 0, false, 'T', 'M');
		$this->Cell(280, 10, $this->getAliasNumPage(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle("Progress Report");
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->SetMargins(PDF_MARGIN_LEFT, 70, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set font
$pdf->SetPrintHeader(true);
$pdf->SetPrintFooter(true); 


		$pdf->AddPage();
		$html = file_get_contents($url.'reports/pdf-genrate-code/studentstdsreport-output.php?id='.$id);
		$html = <<<EOD
		$html 
EOD;
		$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
 		


@include("footer.php");
//Close and output PDF document
$pdf->Output('pdf/'.$filename.'.pdf', 'F');