<?php 
@include("sessioncheck.php");
$method=$_REQUEST;
$id = isset($method['id']) ? $method['id'] : '0';
$oper = isset($method['oper']) ? $method['oper'] : '';
$filename = isset($method['filename']) ? $method['filename'] : '';


ob_start();

$content = ob_get_clean();

require_once('../tcpdf/config/lang/eng.php');
require_once('../tcpdf/tcpdf.php');


$html = file_get_contents(__HOSTADDR__.'test/pdf-genrated-code/assquestion.php?id='.$id.'');

class MYPDF extends TCPDF {
	
	public function Header() { // Page header
		$encryptkey = 'ef800b4cf626d5c14a0c65ce2d90c15c';
                global $ObjDB;
		$method = $_REQUEST;
		$id = isset($method['id']) ? $method['id'] : '0';
		$assname = $ObjDB->SelectSingleValue("SELECT fld_test_name AS testname FROM itc_test_master WHERE fld_id='".$id."' AND fld_delstatus='0'");			
		$this->SetTextColor(0,0,0);
		
                $this->Text(127, 10, 'Name: _____________________');
                $this->Text(127, 18, 'Class : _____________________');
		$this->SetFont('arialblack', '', 18);
		$this->Text(10, 18, $assname);
		
		
		
		$style=array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
		$this->Line(10,30,190,30, $style);
	}
	
	public function Footer() { // Page footer
		$date = date("m/d/Y H:i:s A");
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

$pdf->SetMargins(PDF_MARGIN_LEFT, 38, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);


// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set font
$pdf->SetPrintHeader(true);
$pdf->SetPrintFooter(true); 
// add a page
$pdf->AddPage();


$dat=date('l, F, d, Y');
$i='';
$html = <<<EOD
$html
EOD;

$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);


//Close and output PDF document
$pdf->Output('pdf/'.$filename.'.pdf', 'F');
?>

<section data-type='#test' id='test-pdfviewer'>
	<div class='container'>
    	<div class='row formBase'>
            <div class='eleven columns centered insideForm'>
            	<input type="hidden" id="hidpdfile" name="hidpdfile" value="../../../../test/pdf/<?php echo $filename;?>.pdf" />
                <div id="loadImg" style="height:0px;"><img src="<?php echo __HOSTADDR__; ?>img/iframe-loader.gif"/></div>
            	<iframe src="../reports/pdfviewer/generic/web/viewer.php?hidpdf=../../../../test/pdf/<?php echo $filename;?>.pdf" width="100%" style="min-height:900px;" id="ifr_pdf" onload="$('#loadImg').remove();autoResize('ifr_pdf');"></iframe>		
            </div>
   		</div>
    </div>
</section>
<?php
	@include("footer.php");