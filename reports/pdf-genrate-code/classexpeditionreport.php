<?php
@include("sessioncheck.php");

require_once('../tcpdf/config/lang/eng.php');
require_once('../tcpdf/tcpdf.php');

$method = $_REQUEST;

$id = isset($method['id']) ? $method['id'] : '0';
$filename = isset($method['filename']) ? $method['filename'] : '';

$html = file_get_contents(__HOSTADDR__.'reports/pdf-genrate-code/classexpeditionreport-output.php?id='.$id.'');

class MYPDF extends TCPDF {
	
	public function Header() { // Page header
		$encryptkey = 'ef800b4cf626d5c14a0c65ce2d90c15c';
                global $ObjDB;
		$method = $_REQUEST;
		$id = isset($method['id']) ? $method['id'] : '0';
		$ids=$id;
                $ids = explode(",",$ids);
		
		$qryclass = $ObjDB->QueryObject("SELECT fld_class_name AS classname, fld_period AS period 
												FROM itc_class_master 
												WHERE fld_id='".$ids[1]."'");
			
		$row=$qryclass->fetch_assoc();
		extract($row);
		
		$ends = array('th','st','nd','rd','th','th','th','th','th','th');
		if (($period %100) >= 11 and ($period%100) <= 13)
		   $abbreviation = $period. 'th';
		else
		   $abbreviation = $period. $ends[$period % 10];

		$this->SetTextColor(0,0,0);
		$this->SetFont('arialblack', '', 18);
		$this->Text(10, 18, 'Class Expedition');
		$this->Image('scans/../report.png', 10, 40, 19, 8, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);		
		$this->SetFont('arialblack', '', 13);
		$len = strlen ($classname.', '.$abbreviation.' Period');
		if($len>30)
			$length = 180;
		else
			$length = 200;
		$this->Text($length, 41, $classname.', '.$abbreviation.' Period');
		$style=array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
		$this->Line(10,48,290,48, $style);		
		$this->SetFont('arial', '', 10);
		
                $name = $ObjDB->SelectSingleValue("SELECT fld_schedule_name FROM itc_class_indasexpedition_master WHERE fld_id='".$ids[2]."'");
                $this->Text(10, 50, 'Schedule : '.$name);
                $expname = $ObjDB->SelectSingleValue("SELECT a.fld_exp_name FROM itc_exp_master AS a LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_id=b.fld_exp_id WHERE b.fld_id='".$ids[2]."'");
                $leng = strlen ($expname);
		if($leng>30)
			$length1 = 180;
		else
			$length1 = 200;
                $this->Text($length1, 50, 'Title : '.$expname);
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
$pdf->SetTitle('Class Expedition');
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->SetMargins(PDF_MARGIN_LEFT, 70, PDF_MARGIN_RIGHT); //70
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set font
$pdf->SetPrintHeader(true);
$pdf->SetPrintFooter(true); 
// add a page
$pdf->AddPage('L','A4');

$dat=date('l, F, d, Y');
$i='';
$html = <<<EOD
$html
EOD;

$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

@include("footer.php");
//Close and output PDF document
$pdf->Output('pdf/'.$filename.'.pdf', 'F');