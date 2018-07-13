<?php
@include("sessioncheck.php");

require_once('../tcpdf/config/lang/eng.php');
require_once('../tcpdf/tcpdf.php');

$method = $_REQUEST;

$id = isset($method['id']) ? $method['id'] : '0';
$ids = explode(",",$id);
$filename = isset($method['filename']) ? $method['filename'] : '';

if($ids[4]==1)
	$qry = "SELECT a.fld_student_id, CONCAT(b.fld_fname,' ',b.fld_lname) AS nam 
			FROM itc_class_sigmath_student_mapping AS a 
			LEFT JOIN itc_user_master AS b ON a.fld_student_id = b.fld_id
			WHERE a.fld_sigmath_id='".$ids[2]."' AND a.fld_flag='1' AND b.fld_delstatus='0'"; 
else
	$qry = "SELECT a.fld_student_id, CONCAT(c.fld_fname,' ',c.fld_lname) AS nam 
			FROM itc_class_sigmath_student_mapping AS a 
			LEFT JOIN itc_assignment_sigmath_master AS b ON (a.fld_student_id=b.fld_student_id AND a.fld_sigmath_id=b.fld_schedule_id) 
			LEFT JOIN itc_user_master AS c ON a.fld_student_id = c.fld_id
			WHERE b.fld_schedule_id='".$ids[2]."' AND a.fld_flag='1' AND b.fld_delstatus='0' AND c.fld_delstatus='0'
			GROUP BY a.fld_student_id"; 

$qrystudent = $ObjDB->QueryObject($qry);
$rowcount = $qrystudent->num_rows;

class MYPDF extends TCPDF {
	
	public function Header() { // Page header
		$encryptkey = 'ef800b4cf626d5c14a0c65ce2d90c15c';
		
		$method = $_REQUEST;
		global $ObjDB;
		$id = isset($method['id']) ? $method['id'] : '0';
		$ids=$id;
        $ids = explode(",",$ids);
		
		$qryclass = $ObjDB->QueryObject("SELECT fld_class_name AS classname, fld_period AS period 
												FROM itc_class_master 
												WHERE fld_id='".$ids[1]."'");
			
		$row=$qryclass->fetch_assoc();
		extract($row);
		
		$ends = array('th','st','nd','rd','th','th','th','th','th','th');
		if (($period %100) >= 11 && ($period%100) <= 13)
		   $abbreviation = $period. 'th';
		else
		   $abbreviation = $period. $ends[$period % 10];

		$this->SetTextColor(0,0,0);
		$this->SetFont('arialblack', '', 18);
		$this->Text(10, 18, 'IPL Progress Summary');
		$this->Image('scans/../report.png', 10, 40, 19, 8, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);		
		$this->SetFont('arialblack', '', 13);
		$this->Text(120, 41, $classname.', '.$abbreviation.' Period');
		$style=array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
		$this->Line(10,48,190,48, $style);		
		$this->SetFont('arial', '', 10);
		$name = $ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,' ',fld_lname) FROM itc_user_master WHERE fld_id='".$ids[3]."'");
		$assname = $ObjDB->SelectSingleValue("SELECT fld_schedule_name FROM itc_class_sigmath_master WHERE fld_id='".$ids[2]."'");
		$this->Text(10, 50, 'Class : '.$classname);
		$this->Text(10, 55, 'Teacher : '.$name);
		$this->Text(120, 50, 'Assignment Name : '.$assname);
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
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 003');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
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
		$html = file_get_contents(__HOSTADDR__.'reports/pdf-genrate-code/iplsummaryreport-output.php?id='.$id.'&start='.$start.'&end=1');
		$html = <<<EOD
		$html 
EOD;
		$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
 		$j=$j+1;
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