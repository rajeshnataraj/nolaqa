<?php
@include("sessioncheck.php");
?>
<section data-type='2home' id='reports-correlation'>
<?php

$id = isset($method['id']) ? $method['id'] : 0;
$url=$domainame;

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

$ObjDB->NonQuery("UPDATE itc_correlation_report_data SET fld_step_id='4', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$id."'");
$qry=$ObjDB->QueryObject("SELECT  fld_sec_std_add_summary,
						  fld_sec_bench_add_summary,
						  fld_sec_corr_by_std,
						  fld_sec_corr_by_title,
						  fld_sec_std_not_add,
						  fld_sec_prod_description 
						FROM
						  itc_correlation_report_data 
						WHERE fld_id = '".$id."' 
						  AND fld_delstatus = '0' ");
if($qry->num_rows > 0){
		$rowqry = $qry->fetch_assoc();
       	extract($rowqry);
		$standardgraph=$fld_sec_std_add_summary;
		$benchgraph=$fld_sec_bench_add_summary;
		$stdpoints=$fld_sec_corr_by_std;
		$stdpointsbytitle=$fld_sec_corr_by_title;
		$stdpointsnotadde=$fld_sec_std_not_add;
		}
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

	
	public function Header() {
		$this->SetFont('helvetica', '', 20);
		$this->SetTextColor(80,80,80);
		$this->SetFont('helvetica', '', 11);		
		$this->Cell(87, 10, 'Pitsco Education Standards Correlation Report', 0, false, 'C', 0, '', 0, false, 'T', 'M');
		 $this->top_margin = $this->GetY() + 20; // padding for second page
	}
	
// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		//
		$this->SetTextColor(80,80,80);
		// Set font
		$this->SetFont('helvetica', '', 8);
		// Page number
		
		$this->Cell(57, 10, '© 2013 Pitsco Education. All rights reserved', 0, false, 'C', 0, '', 0, false, 'T', 'M');
		$this->Cell(250, 10, $this->getAliasNumPage(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}

}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);


// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set font
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false); 
// add a page
$pdf->AddPage();


$html = file_get_contents($url.'reports/correlation/CorrelationReport-Output.php?id='.$id.'&uid='.$uid.'&sessmasterprfid='.$sessmasterprfid.'&oper=page1');
// print a block of text using Write()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
$pdf->SetPrintHeader(true);
$pdf->SetPrintFooter(true); 

$pdf->AddPage();
$html = file_get_contents($url.'reports/correlation/CorrelationReport-Output.php?id='.$id.'&uid='.$uid.'&sessmasterprfid='.$sessmasterprfid.'&oper=page2');
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
$pdf->SetMargins(10, 20, 10, true);

$pdf->AddPage();
$html = file_get_contents($url.'reports/correlation/CorrelationReport-Output.php?id='.$id.'&oper=page3&stdgrapg='.$standardgraph.'&bchgraph='.$benchgraph.'&p1='.$stdpoints.'&p2='.$stdpointsbytitle.'&p3='.$stdpointsnotadde);
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
$pdf->SetMargins(10, 20, 10, true);


//Close and output PDF document
$pdf->Output('correlationreports/correlation_report_'.$id.'.pdf', 'F');

?>

</section>    