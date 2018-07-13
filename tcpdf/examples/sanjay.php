<?php
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';

$filename = isset($_REQUEST['filename']) ? $_REQUEST['filename'] : '';


require_once('../tcpdf/config/lang/eng.php');
require_once('../tcpdf/tcpdf.php');
@include("../../sessioncheck.php");

class MYPDF extends TCPDF {
	
	public function Header() { // Page header
		$encryptkey = 'ef800b4cf626d5c14a0c65ce2d90c15c';
		
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
		$ids=$id;
        $ids = explode(",",$ids);
		
		$classname = "JJ"; //Table::SelectSingleValue("SELECT fld_class_name FROM itc_class_master WHERE fld_id='".$ids[1]."'");
		$period = "1"; //Table::SelectSingleValue("SELECT fld_period FROM itc_class_master WHERE fld_id='".$ids[1]."'");
		
		$ends = array('th','st','nd','rd','th','th','th','th','th','th');
		if (($period %100) >= 11 && ($period%100) <= 13)
		   $abbreviation = $period. 'th';
		else
		   $abbreviation = $period. $ends[$period % 10];

		$this->SetTextColor(0,0,0);
		$this->SetFont('helvetica', 'B', 18);
		$this->Text(10, 18, 'Individual IPL Question Report');
		$this->Image('report.png', 10, 31, 44, 15, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
		$this->SetTextColor(96,96,96);
		$this->SetFont('helvetica', 'B', 13);
		$this->Text(120, 41, $classname.', '.$abbreviation.' Period');
		$style=array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
		$this->Line(10,48,190,48, $style);
		$this->SetTextColor(96,96,96);
		$this->SetFont('helvetica', 'B', 10);
		/*$qry = Table::QueryObject("SELECT a.fld_class_name AS classname, b.fld_schedule_name AS assignmentname, CONCAT(c.fld_fname,'',c.fld_lname) AS username, d.fld_ipl_name AS iplname FROM itc_class_master AS a JOIN itc_class_sigmath_master AS b JOIN itc_user_master AS c JOIN itc_ipl_master AS d WHERE a.fld_id='".$ids[1]."' AND b.fld_id='".$ids[3]."' AND c.fld_id='".$ids[2]."' AND d.fld_id='".$ids[4]."'");
		$row=$qry->fetch_object();
		$this->Text(10, 50, 'Class : '.$row->classname);
		$this->Text(10, 55, 'Student : '.$row->username);
		$this->Text(120, 50, 'Assignment : '.$row->assignmentname);
		$this->Text(120, 55, 'IPL : '.$row->iplname);*/
	}
	
	public function Footer() { // Page footer
		$date = date("Y/m/d H:i:s A");
		// Position at 15 mm from bottom
		$this->SetY(-15);
		$this->SetTextColor(96,96,96);
		// Set font
		$this->SetFont('helvetica', 'B', 10);
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


$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set font
//$pdf->SetFont('times', 'B', 12);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
// add a page
$pdf->AddPage();


$dat=date('l, F, d, Y');
$i='';

//$html = file_get_contents(__HOSTADDR__.'reports/pdf-genrate-code/indiplquestionreport1.php?id='.$id.'');

$html = '<table cellpadding="0" cellspacing="0">
            	<tr><td class="tdmiddle">Test</td><td class="tdmiddle" align="right">Testing</td></tr>
            </table>'; 

//$html = <<<EOD
//$html
//EOD;

$pdf->writeHTML($html, true, false, true, false, '');
//$pdf->writeHTMLCell($w=0, $h=0, $x='10', $y='70', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);


//Close and output PDF document
$pdf->Output('pdf/'.$filename.'.pdf', 'F');