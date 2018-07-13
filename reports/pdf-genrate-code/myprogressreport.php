<?php
@include("sessioncheck.php");

require_once('../tcpdf/config/lang/eng.php');
require_once('../tcpdf/tcpdf.php');

$method = $_REQUEST;

$id = isset($method['id']) ? $method['id'] : '0';
$ids = explode(",",$id);
$filename = isset($method['filename']) ? $method['filename'] : '';

if($ids[1]==0)
{
	$qryschedules = $ObjDB->QueryObject("SELECT a.fld_unit_id AS ids, 0 AS rotation, (SELECT fld_unit_name FROM itc_unit_master WHERE fld_id=a.fld_unit_id AND fld_delstatus='0') AS modunnames, b.fld_start_date AS startdate, b.fld_end_date AS enddate FROM itc_class_sigmath_unit_mapping AS a LEFT JOIN itc_class_sigmath_master AS b ON b.fld_id=a.fld_sigmath_id WHERE a.fld_sigmath_id='".$ids[0]."' AND a.fld_flag='1' AND b.fld_flag='1' AND b.fld_delstatus='0'");
	$testtype='1';
}
else if($ids[1]==1)
{
	$qryschedules = $ObjDB->QueryObject("SELECT fld_module_id AS ids, (SELECT CONCAT(a.fld_module_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id=a.fld_id AND fld_delstatus='0')) FROM itc_module_master AS a WHERE a.fld_id=fld_module_id) AS modunnames, fld_rotation AS rotation, fld_startdate AS startdate, fld_enddate AS enddate FROM itc_class_rotation_schedulegriddet WHERE fld_class_id='".$ids[2]."' AND fld_schedule_id='".$ids[0]."' AND fld_student_id='".$ids[3]."' AND fld_flag='1' ORDER BY fld_startdate");
}
else if($ids[1]==2)
{
	$qryschedules = $ObjDB->QueryObject("SELECT fld_module_id AS ids, (SELECT CONCAT(a.fld_module_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id=a.fld_id AND fld_delstatus='0')) FROM itc_module_master AS a WHERE a.fld_id=fld_module_id) AS modunnames, fld_rotation AS rotation, fld_startdate AS startdate, fld_enddate AS enddate FROM itc_class_dyad_schedulegriddet WHERE fld_class_id='".$ids[2]."' AND fld_schedule_id='".$ids[0]."' AND fld_student_id='".$ids[3]."' AND fld_flag='1' ORDER BY fld_startdate");
}
else if($ids[1]==3)
{
	$qryschedules = $ObjDB->QueryObject("SELECT fld_module_id AS ids, (SELECT CONCAT(a.fld_module_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id=a.fld_id AND fld_delstatus='0')) FROM itc_module_master AS a WHERE a.fld_id=fld_module_id) AS modunnames, fld_rotation AS rotation, fld_startdate AS startdate, fld_enddate AS enddate FROM itc_class_triad_schedulegriddet WHERE fld_class_id='".$ids[2]."' AND fld_schedule_id='".$ids[0]."' AND fld_student_id='".$ids[3]."' AND fld_flag='1' ORDER BY fld_startdate");
}
else if($ids[1]==4)
{
	$qryschedules = $ObjDB->QueryObject("SELECT a.fld_module_id AS ids, (SELECT CONCAT(fld_mathmodule_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id=fld_module_id AND fld_delstatus='0')) FROM itc_mathmodule_master WHERE fld_id=a.fld_module_id) AS modunnames, a.fld_rotation AS rotation, a.fld_startdate AS startdate, a.fld_enddate AS enddate FROM itc_class_rotation_schedulegriddet AS a WHERE a.fld_class_id='".$ids[2]."' AND a.fld_schedule_id='".$ids[0]."' AND a.fld_student_id='".$ids[3]."' AND a.fld_flag='1' ORDER BY a.fld_startdate ");
	$testtype='2';
}
else if($ids[1]==5)
{
	$qryschedules = $ObjDB->QueryObject("SELECT fld_module_id AS ids, (SELECT CONCAT(a.fld_module_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id=a.fld_id AND fld_delstatus='0')) FROM itc_module_master AS a WHERE a.fld_id=fld_module_id) AS modunnames, 0 AS rotation, fld_startdate AS startdate, fld_enddate AS enddate FROM itc_class_indassesment_master WHERE fld_class_id='".$ids[2]."' AND fld_id='".$ids[0]."' AND fld_flag='1' AND fld_delstatus='0' ORDER BY fld_startdate");
}
else if($ids[1]==6)
{
	$qryschedules = $ObjDB->QueryObject("SELECT a.fld_module_id AS ids, (SELECT CONCAT(fld_mathmodule_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id=fld_module_id AND fld_delstatus='0')) FROM itc_mathmodule_master WHERE fld_id=a.fld_module_id) AS modunnames, 0 AS rotation, a.fld_startdate AS startdate, a.fld_enddate AS enddate FROM itc_class_indassesment_master AS a WHERE a.fld_class_id='".$ids[2]."' AND a.fld_id='".$ids[0]."' AND fld_flag='1' AND fld_delstatus='0' ORDER BY a.fld_startdate");
	$testtype='3';
}
else if($ids[1]==7)
{
	$qryschedules = $ObjDB->QueryObject("SELECT fld_module_id AS ids, (SELECT CONCAT(a.fld_module_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id=a.fld_id AND fld_delstatus='0')) FROM itc_module_master AS a WHERE a.fld_id=fld_module_id) AS modunnames, 0 AS rotation, fld_startdate AS startdate, fld_enddate AS enddate FROM itc_class_indassesment_master WHERE fld_class_id='".$ids[2]."' AND fld_id='".$ids[0]."' AND fld_flag='1' AND fld_delstatus='0' ORDER BY fld_startdate");
}
else if($ids[1]==15)
{
	$qryschedules = $ObjDB->QueryObject("SELECT fld_exp_id AS ids, (SELECT CONCAT(a.fld_exp_name,' ',(SELECT fld_version FROM itc_exp_version_track WHERE fld_exp_id=a.fld_id AND fld_delstatus='0')) FROM itc_exp_master AS a WHERE a.fld_id=fld_exp_id) AS modunnames, 0 AS rotation, fld_startdate AS startdate, fld_enddate AS enddate FROM itc_class_indasexpedition_master WHERE fld_class_id='".$ids[2]."' AND fld_id='".$ids[0]."' AND fld_flag='1' AND fld_delstatus='0' ORDER BY fld_startdate");
}

$rowcount = $qryschedules->num_rows;
class MYPDF extends TCPDF {
	
	public function Header() { // Page header
		
		$method = $_REQUEST;
		global $ObjDB;

		$id = isset($method['id']) ? $method['id'] : '0';
		$ids=$id;
        $ids = explode(",",$ids);
		
		if($ids[4]==0)
			$title = "My Progress";
		else if($ids[4]==1)
			$title = "Student Progress";
			
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
		$len = strlen ($classname.', '.$abbreviation.' Period');
		if($len>30)
			$length = 100;
		else
			$length = 120;
		$this->Text($length, 41, $classname.', '.$abbreviation.' Period');
		$style=array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
		$this->Line(10,48,190,48, $style);		
		$this->SetFont('arial', '', 10);
		$name = $ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,' ',fld_lname) FROM itc_user_master WHERE fld_id='".$ids[3]."'");
		$this->Text(10, 50, 'Student : '.$name);
		if($ids[1]==0)
		{
			$qry = $ObjDB->QueryObject("SELECT fld_schedule_name AS schedulename, fld_start_date AS startdate, fld_end_date AS enddate 
									FROM itc_class_sigmath_master
									WHERE fld_id='".$ids[0]."'");
			$iplcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_lesson_id) 
													FROM itc_class_sigmath_lesson_mapping 
													WHERE fld_sigmath_id='".$ids[0]."' AND fld_flag='1'");
			$completedipls = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_lesson_id) 
														FROM itc_assignment_sigmath_master 
														WHERE fld_class_id='".$ids[2]."' AND fld_student_id='".$ids[3]."' 
															AND fld_schedule_id='".$ids[0]."' AND fld_delstatus='0' 
															AND fld_test_type='1' AND (fld_status='1' OR fld_status='2')");
			$masteredipls = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_lesson_id) 
														FROM itc_assignment_sigmath_master 
														WHERE fld_class_id='".$ids[2]."' AND fld_student_id='".$ids[3]."' 
															AND fld_schedule_id='".$ids[0]."' AND fld_delstatus='0' 
															AND fld_test_type='1' AND fld_status='1'");
		}
		else if($ids[1]==1 || $ids[1]==4 || $ids[1]==8)
		{
			$qry = $ObjDB->QueryObject("SELECT fld_schedule_name AS schedulename, fld_startdate AS startdate, fld_enddate AS enddate 
									FROM itc_class_rotation_schedule_mastertemp
									WHERE fld_id='".$ids[0]."'");
		}
		else if($ids[1]==2)
		{
			$qry = $ObjDB->QueryObject("SELECT fld_schedule_name AS schedulename, fld_startdate AS startdate, fld_enddate AS enddate 
									FROM itc_class_dyad_schedulemaster
									WHERE fld_id='".$ids[0]."'");
		}
		else if($ids[1]==3)
		{
			$qry = $ObjDB->QueryObject("SELECT fld_schedule_name AS schedulename, fld_startdate AS startdate, fld_enddate AS enddate 
									FROM itc_class_triad_schedulemaster
									WHERE fld_id='".$ids[0]."'");
		}
		else if($ids[1]==5 || $ids[1]==6 || $ids[1]==7)
		{
			$qry = $ObjDB->QueryObject("SELECT fld_schedule_name AS schedulename, fld_startdate AS startdate, fld_enddate AS enddate 
										FROM itc_class_indassesment_master
										WHERE fld_id='".$ids[0]."'");
		}
		else if($ids[1]==15)
		{
			$qry = $ObjDB->QueryObject("SELECT fld_schedule_name AS schedulename, fld_startdate AS startdate, fld_enddate AS enddate 
										FROM itc_class_indasexpedition_master
										WHERE fld_id='".$ids[0]."'");
		}
		
		$rowqry = $qry->fetch_assoc();
		extract($rowqry);
		
		$this->Text(10, 55, 'Schedule : '.$schedulename);
		
		if($enddate!='')
			$enddates = "  -  ".date("m-d-Y",strtotime($enddate));
		else
			$enddates = '';
		$this->Text(10, 60, 'Date : '.date("m-d-Y",strtotime($startdate)).$enddates);
		
		if($ids[1]==0)
		{
			$this->Text(150, 50, 'Total IPLs : '.$iplcount);
			$this->Text(150, 55, 'Completed IPLs : '.$completedipls);
			$this->Text(150, 60, 'Mastered IPLs : '.$masteredipls);
		}
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

if($rowcount>0)
{
	$j=0;
	while($j<$rowcount)
	{	
		$start=$j;
		$pdf->AddPage();
		$html = file_get_contents(__HOSTADDR__.'reports/pdf-genrate-code/myprogressreport-output.php?id='.$id.'&start='.$start.'&end=10');
		$html = <<<EOD
		$html 
EOD;
		$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
 		$j=$j+10;
 	}
}
else
{
	$pdf->AddPage();
	$html = "no records";
	$html = <<<EOD
	$html
EOD;
	$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
}

@include("footer.php");
//Close and output PDF document
$pdf->Output('pdf/'.$filename.'.pdf', 'F');