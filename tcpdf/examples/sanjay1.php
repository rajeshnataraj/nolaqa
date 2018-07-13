<?php
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';

$filename = isset($_REQUEST['filename']) ? $_REQUEST['filename'] : '';


require_once('../tcpdf/config/lang/eng.php');
require_once('../tcpdf/tcpdf.php');
@include("../../sessioncheck.php");

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
		
		$encryptkey = 'ef800b4cf626d5c14a0c65ce2d90c15c';
		
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
		$ids=$id;
        $ids = explode(",",$ids);
		
        $classname = Table::SelectSingleValue("SELECT fld_class_name FROM itc_class_master WHERE fld_id='".$ids[1]."'");
		$period = Table::SelectSingleValue("SELECT fld_period FROM itc_class_master WHERE fld_id='".$ids[1]."'");
		
		$ends = array('th','st','nd','rd','th','th','th','th','th','th');
		if (($period %100) >= 11 && ($period%100) <= 13)
		   $abbreviation = $period. 'th';
		else
		   $abbreviation = $period. $ends[$period % 10];
		
		//$this->startTemplate(100, 5);
		
		$this->SetTextColor(0,0,0);
		$this->SetFont('arialblack', '', 16);
		$this->Cell(0, 20, 'Individual IPL Question Report', 0, 2, 'L', 0, '', 0);
		//$this->Image('report.png', 10, 31, 44, 15, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
		$this->Cell(0, 0, $classname.', '.$abbreviation.' Period', 'B', 2, 'R', 0, '', 0);
		$qry = Table::QueryObject("SELECT a.fld_class_name AS classname, b.fld_schedule_name AS assignmentname, CONCAT(c.fld_fname,'',c.fld_lname) AS username, d.fld_ipl_name AS iplname FROM itc_class_master AS a JOIN itc_class_sigmath_master AS b JOIN itc_user_master AS c JOIN itc_ipl_master AS d WHERE a.fld_id='".$ids[1]."' AND b.fld_id='".$ids[3]."' AND c.fld_id='".$ids[2]."' AND d.fld_id='".$ids[4]."'");
		$row=$qry->fetch_object();
		//$this->Text(10, 50, 'Class : '.$row->classname);
		//$this->Text(10, 55, 'Student : '.$row->username);
		//$this->Text(120, 50, 'Assignment : '.$row->assignmentname);
		//$this->Text(120, 55, 'IPL : '.$row->iplname);
		
		$this->SetFont('arial', '', 12);
		
		$this->MultiCell(0, 0, 'Class : '.$row->classname, 0, 'L', 0, 2, '', '', true);
		$this->MultiCell(0, 0, 'Assignment : '.$row->assignmentname, 0, 'R', 0, 2, '', '', true);
		$this->Ln(2);
		$this->MultiCell(0, 0, 'Student : '.$row->username, 0, 'L', 0, 0, '', '', true);
		$this->MultiCell(0, 0, 'IPL : '.$row->iplname, 0, 'R', 0, 0, '', '', true);
		
		//$this->endTemplate();
		/*$this->Image('report.png', 10, 31, 44, 15, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
		$this->SetTextColor(96,96,96);
		$this->SetFont('helvetica', 'B', 13);
		$this->Text(120, 41, $classname.', '.$abbreviation.' Period');
		$style=array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
		$this->Line(10,48,190,48, $style);
		$this->SetTextColor(96,96,96);
		$this->SetFont('helvetica', 'B', 10);*/
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

//$pdf->addTTFfont('arial.ttf', 'Arial', '', 32);
//$pdf->addTTFfont('ArialBlack.ttf', 'ArialBlack', '', 32);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 003');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 50, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// ---------------------------------------------------------

// add a page
$pdf->AddPage();


$html = file_get_contents(__HOSTADDR__.'reports/pdf-genrate-code/indiplquestionreport1.php?id='.$id.'');

$html = <<<EOD
$html
EOD;

//$pdf->writeHTML($html, true, false, true, false, '');
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('pdf/'.$filename.'.pdf', 'F');

