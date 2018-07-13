<?php
//============================================================+
// File name   : example_003.php
// Begin       : 2008-03-04
// Last Update : 2010-08-08
//
// Description : Example 003 for TCPDF class
//               Custom Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               Manor Coach House, Church Hill
//               Aldershot, Hants, GU12 4RQ
//               UK
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Custom Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */
 
 

require_once('../config/lang/eng.php');
require_once('../tcpdf.php');

$html = file_get_contents('http://localhost/synergy/tcpdf/examples/sample.php');


class MYPDF extends TCPDF {

	
	public function Header() {
		$name="student1";
		$this->SetTextColor(0,0,0);
		$this->SetFont('helvetica', 'B', 18);
		$this->Text(16, 18, 'Individual Student Schedule');
		$this->Image('Untitled.png', 15, 25, 30, 30, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
		$this->SetTextColor(96,96,96);
		$this->SetFont('helvetica', 'B', 13);
		$this->Text(120, 50, 'New Test Class,2013,1st Period');
		$style=array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
		$this->Line(18,58,190,58, $style);
		$this->SetTextColor(96,96,96);
		$this->SetFont('helvetica', 'B', 10);
		$this->Text(18, 60, 'Student : '.$name.' ');
		$this->Text(18, 65, 'id:');
		$this->Text(150, 60, 'username : studne');
		$this->Text(150, 65, 'password : 1');
		
	}
	
// Page footer
	public function Footer() {
		
		$date = date("Y/m/d H:i:s A");
		// Position at 15 mm from bottom
		$this->SetY(-15);
		//
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
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);


// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set font
$pdf->SetFont('times', 'B', 12);
$pdf->SetPrintHeader(true);
$pdf->SetPrintFooter(true); 
// add a page
$pdf->AddPage();


$dat=date('l, F, d, Y');
$i='';
$html = <<<EOD
$html
EOD;

$pdf->writeHTMLCell($w=0, $h=0, $x='10', $y='80', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);


//Close and output PDF document
$pdf->Output('example_003.pdf', 'I');
