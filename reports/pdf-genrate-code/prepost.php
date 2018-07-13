<?php
@include("sessioncheck.php");

require_once('../tcpdf/config/lang/eng.php');
require_once('../tcpdf/tcpdf.php');

$method = $_REQUEST;

$id = isset($method['id']) ? $method['id'] : '0';
$ids = explode(",",$id);
$filename = isset($method['filename']) ? $method['filename'] : '';

if($ids[3]==1 || $ids[3]==4)
{
	$query = "SELECT fld_rotation AS rotation, fld_rotation-1 AS realrotation 
				FROM itc_class_rotation_schedulegriddet 
				WHERE fld_schedule_id='".$ids[1]."' AND fld_flag='1' 
				GROUP BY fld_rotation 
				ORDER BY fld_rotation";
}
else if($ids[3]==2)
{
	$query = "SELECT fld_rotation AS rotation, fld_rotation AS realrotation 
				FROM itc_class_dyad_schedulegriddet 
				WHERE fld_schedule_id='".$ids[1]."' AND fld_flag='1' 
				GROUP BY fld_rotation 
				ORDER BY fld_rotation";
}
else if($ids[3]==3)
{
	$query = "SELECT fld_rotation AS rotation, fld_rotation AS realrotation 
				FROM itc_class_triad_schedulegriddet 
				WHERE fld_schedule_id='".$ids[1]."' AND fld_flag='1' 
				GROUP BY fld_rotation 
				ORDER BY fld_rotation";
}
$rowcount = $ObjDB->QueryObject($query)->num_rows;

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
												WHERE fld_id='".$ids[2]."'");
			
		$row=$qryclass->fetch_assoc();
		extract($row);
		
		$ends = array('th','st','nd','rd','th','th','th','th','th','th');
		if (($period %100) >= 11 and ($period%100) <= 13)
		   $abbreviation = $period. 'th';
		else
		   $abbreviation = $period. $ends[$period % 10];
		
		$this->SetTextColor(0,0,0);
		$this->SetFont('arialblack', '', 18);
		$this->Text(10, 18, 'Pre/Post Scores');
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
		$this->SetFont('arial', '', 13);
		if($ids[3]==1 or $ids[3]==4)
			$schtablename = "itc_class_rotation_schedule_mastertemp";
		else if($ids[3]==2)
			$schtablename = "itc_class_dyad_schedulemaster";
		else if($ids[3]==3)
			$schtablename = "itc_class_triad_schedulemaster";
		$qrysch = $ObjDB->QueryObject("SELECT fld_schedule_name AS schname, fld_startdate AS startdate, fld_enddate AS enddate FROM ".$schtablename." WHERE fld_id='".$ids[1]."'");
		
		$rowqrysch = $qrysch->fetch_assoc();
		extract($rowqrysch);
		
		$this->Text(10, 50, 'Schedule Name : '.$schname);
		$this->Text(10, 60, 'Date : '.date("F d, Y",strtotime($startdate)).' - '.date("F d, Y",strtotime($enddate)));
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
$pdf->SetTitle('Pre/Post Scores');
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
		$html = file_get_contents(__HOSTADDR__.'reports/pdf-genrate-code/prepost-output.php?id='.$id.'&start='.$start.'&end=4');
		$html = <<<EOD
		$html 
EOD;
		$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
 		$j=$j+4;
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