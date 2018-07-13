<?php 
@include("sessioncheck.php");
$id = isset($method['id']) ? $method['id'] : '0';
$oper = isset($method['oper']) ? $method['oper'] : '';
ob_start();
include('reports-pdf-template.php');
$content = ob_get_clean();
// convert in PDF
require_once __PDFPATH__.'html2pdf.class.php';
try
{
	$type='P';
	$sheet='A4';
	
	$html2pdf = new HTML2PDF($type,$sheet,'fr');
	$html2pdf->setDefaultFont('Arial');
	$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
	$filename = $oper.'_'.time().'.pdf';	
	$html2pdf->Output('assetreport.pdf');
}
catch(HTML2PDF_exception $e) {
	echo $e;
	exit;
}

@include("footer.php");
