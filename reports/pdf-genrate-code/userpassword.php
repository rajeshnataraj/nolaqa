<?php
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
$filename = isset($_REQUEST['filename']) ? $_REQUEST['filename'] : '';
@include("sessioncheck.php");
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

$html = file_get_contents(__HOSTADDR__.'reports/pdf-genrate-code/userpassword-output.php?id='.$id.'&sessmasterprfid='.$sessmasterprfid.'&schoolid='.$schoolid.'');
class MYPDF extends TCPDF {
	
	public function Header() { // Page header
		global $ObjDB;
		
		$ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
		$id=$ids;
		$id = explode("~",$id);
		
		if($id[0] != '' and $id[0] != 'school' and  $id[0] != 'home' and $id[1] == '') // for all district user reports 
		{ 
			$districtname = $ObjDB->SelectSingleValue("SELECT fld_district_name 
														FROM itc_district_master 
														WHERE fld_id='".$id[0]."'");
			$filename = $districtname." - District Report";
		}
		
		else if($id[0] != '' and $id[0] != 'school' and  $id[0] != 'home' and $id[1] !='') // for all school user reports 
		{
			$districtname = $ObjDB->SelectSingleValue("SELECT fld_school_name 
														FROM itc_school_master 
														WHERE fld_id ='".$id[1]."'");
			$filename = $districtname." - School Report";
		}
		
		else if($id[0] == 'school' and $id[1] !='') // for all school purchase reports 
		{
			$districtname = $ObjDB->SelectSingleValue("SELECT fld_school_name 
														FROM itc_school_master 
														WHERE fld_id ='".$id[1]."' and  fld_district_id='0' ");
			$filename = $districtname." - School Purchase Report";
		}
		
		else if($id[0] == 'home' and $id[1] !='') // for all home purchase reports 
		{
			$districtname = $ObjDB->SelectSingleValue("SELECT  CONCAT(`fld_fname`,' ',`fld_lname`) AS fullname 
														FROM `itc_user_master` 
														WHERE fld_district_id='0' AND fld_school_id='0' AND fld_profile_id='5' 
															AND fld_user_id='".$id[1]."' ");
			$filename = $districtname." - Home Purchase Report";
		}
		   
		$this->SetTextColor(0,0,0);
		$this->SetFont('arialblack', '', 18);
		$this->Text(10, 18, 'Password Report');
		$this->Image('scans/../report.png', 160, 18, 19, 8, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
		$style=array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
		$this->Line(10,28,190,28, $style);
		$this->SetFont('arialblack', '', 13);
		$this->Text(10, 35, $filename);
	}
	
	public function Footer() { // Page footer
		$date = date("m/d/Y"); //H:i:s A
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

$pdf->SetMargins(PDF_MARGIN_LEFT, 50, PDF_MARGIN_RIGHT);
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