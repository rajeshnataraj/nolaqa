<?php
@include("sessioncheck.php");

/*
* Created By:Vijayalakshmi (PHP Programmer)
* Created On: 23/1/2015
*id[0] = district id
*id[1] = schoolid;
*id[2] = classid;
*id[3] = scheduleid;
*id[4] = moduleid;
*id[5] = typename;
 * modified on :31/1/2015
 * Modified by: vijayalakshmi PHP Programmer
*
*/
$method = $_REQUEST;
$id = isset($method['id']) ? $method['id'] : '0';
$studentid = isset($method['studentlist']) ? $method['studentlist'] : '0';
$id = explode(",",$id);
$studentid = explode(",",$studentid);

require_once '../../PHPExcel.php';

require_once '../../PHPExcel/IOFactory.php';

// include 2 columns for stundent # and Total Score
$studentcount = count($studentid) + 2; 
$sessids[] ='';

if($id[5]==4 || $id[5]==6)

    $newmodid = $ObjDB->SelectSingleValueInt("SELECT fld_module_id FROM itc_mathmodule_master WHERE fld_id='".$id[4]."'");
else
    $newmodid = $id[4];

$moduleversion = $ObjDB->SelectSingleValue("SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id='".$newmodid."' AND fld_delstatus='0'");

$qryschedules = $ObjDB->QueryObject("SELECT fld_session_id, fld_question_text, fld_question_id 
                                    FROM itc_module_answer_track 
                                    WHERE fld_module_id='".$id[4]."' AND fld_schedule_id='".$id[3]."' 
                                        AND fld_schedule_type='".$id[5]."' AND fld_page_id=0 AND fld_session_id IN(0,6) AND fld_delstatus='0' GROUP BY fld_question_id ORDER BY fld_session_id, fld_question_id");

$count=0;
	if($qryschedules->num_rows > 0) {
		while($rowschedules=$qryschedules->fetch_assoc()){
			extract($rowschedules);

			$sessid[$count] = $fld_session_id;
			$count++;
		}
			$sessids = array_values(array_unique($sessid));
	}

/* starts to view Module Guide and Post test report to get total score for individual student base
*
*/
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
$sheet_count = 0;

for($b=0;$b<sizeof($sessids);$b++)
{
 	if ($sheet_count > 0) {

		// This creates the next sheet in the sequence
		// One sheet per "Test" in this example
		$sessiontest = 6;
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($sheet_count);

	}
	else {
		$sessiontest = 0;
	}
// Add tab label to the sheet
	if($b==0) {
	    $objPHPExcel->getActiveSheet()->setTitle('Module Guide Test');
	    $headerpart = "Module Guide Test";
	}
	if($b==1) {
	     $objPHPExcel->getActiveSheet()->setTitle('Post Test');
	     $headerpart = "Post Test";
	}

$qryschedules = $ObjDB->QueryObject("SELECT fld_session_id, fld_question_text, fld_question_id 
                                    FROM itc_module_answer_track 
                                    WHERE fld_module_id='".$id[4]."' AND fld_schedule_id='".$id[3]."' 
                                        AND fld_schedule_type='".$id[5]."' AND fld_page_id=0 AND fld_session_id IN('".$sessiontest."') AND fld_delstatus='0' 
					GROUP BY fld_question_id ORDER BY fld_session_id, fld_question_id");
	$count=0;
if($qryschedules->num_rows > 0) {
    while($rowschedules=$qryschedules->fetch_assoc()){
            extract($rowschedules);
        
            $sessid[$count] = $fld_session_id;
            $qtext[$count] = $fld_question_text;
            $qid[$count] = $fld_question_id;
            $count++;
    }
}
if($qryschedules->num_rows > 0)
{ 
$row = 1; // 1-based index
	for ($x = 0; $x <= $studentcount; $x++) {
	     $col = 0;
		if($row == 1) {
		    $value=$headerpart."     Enter a \"0\" for incorrect answers and a \"1\" for correct answers";
		    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 0, $value);
		    $range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
                    $objPHPExcel->setActiveSheetIndex($sheet_count)->mergeCells($range);
		    $objPHPExcel->getActiveSheet()->setCellValue(A1, $value);
		    $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
		    $style = array('font' =>
				                    array('color' =>
				                      array('rgb' => '000000'),
				                      'bold' => true,
				                    ),
				           'alignment' => array(
				                            
				                      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				                      'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
				                        ),
				     );
		    $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
		    $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(25);
		}  // ends of if($row == 1)
		elseif($row == 2) {
			for($c=0;$c<sizeof($qid);$c++) {
				$Questnid = $qid[$c];
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($c+1, $row, $Questnid);
				$style = array('font' =>
				                    array('color' =>
				                      array('rgb' => '000000'),
				                      'bold' => true,
				                    ),
				           'alignment' => array(
				                            
				                      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				                      'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
				                        ),
				     );
				$range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
				$objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
				$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
			}
      }   // ends of elseif($row == 2)
		elseif($row == 3) {
			$exactval = sizeof($qid)+1;
			for($c=0;$c<sizeof($qid)+2;$c++) {
		  		if($c == 0) {
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($c, $row, 'Student #' );
				} 
				elseif($c < $exactval){
					$Qtext = $qtext[$c-1];
					$split = str_word_count($Qtext, 1, 'àáãç3');
					$qlastword = end($split);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($c, $row, $qlastword );
					reset($qlastword);
				}
				elseif($exactval == $c) {
					reset($qlastword);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($c, $row, 'Total Score' );
				}
			$style = array('font' =>
						            array('color' =>
						              array('rgb' => '000000'),
						              'bold' => true,
						            ),
						   'alignment' => array(
						             
						              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
						                ),
			);
			$range = $objPHPExcel->setActiveSheetIndex($sheet_count)->calculateWorksheetDimension();
			$objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
			$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
        }      //ends of for($c=0;$c<sizeof($qid)+2;$c++)
      }    // ends of elseif($row ==3)
		else {
			$totalscore = 0;
		    	for ($y = 0; $y <= 11; $y++) {
			  if($y == 0) {
			        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $studentid[$row-4] );
			   } 
			   elseif($y == 11) {
			        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $totalscore);
			   }
			   else {

				$qryanswerstu = $ObjDB->SelectSingleValue("SELECT fld_correct FROM itc_module_answer_track WHERE fld_tester_id='".$studentid[$row-4]."' AND 
								fld_module_id='".$id[4]."' AND fld_schedule_id='".$id[3]."' AND 
								fld_schedule_type='".$id[5]."' AND fld_page_id=0 AND fld_delstatus='0' 
								AND fld_question_id='".$qid[$y-1]."'");
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $qryanswerstu);
				$totalscore=$totalscore + $qryanswerstu;

			    }
			$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
			$col++;
			}  // ends of for ($y = 0; $y <= 11; $y++)

        }         // ends of else part
$row++;
	}  // ends of for ($x = 0; $x <= 10; $x++)

}  //ends of if($qryschedules->num_rows > 0)

$sheet_count++;
}

$endvalue = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();;

for($col = 'A'; $col !== 'L'; $col++) {
    $objPHPExcel->getActiveSheet()
        ->getColumnDimension($col)
        ->setAutoSize(true);
}

// Create a new worksheet, after the default sheet
$objPHPExcel->createSheet();
// Add some data to the second sheet(For post test), resembling some different data types
$objPHPExcel->setActiveSheetIndex(1);

$qryschedules1 = $ObjDB->QueryObject("SELECT fld_session_id, fld_question_text, fld_question_id 
                                    FROM itc_module_answer_track 
                                    WHERE fld_module_id='".$id[4]."' AND fld_schedule_id='".$id[3]."' 
                                        AND fld_schedule_type='".$id[5]."' AND fld_page_id=0 AND fld_session_id IN(6) AND fld_delstatus='0' GROUP BY fld_question_id ORDER BY fld_question_id");

$count1=0;
if($qryschedules1->num_rows > 0) {
    
	while($rowschedules1=$qryschedules1->fetch_assoc()){
			extract($rowschedules1);
		
			$sessid1[$count1] = $fld_session_id;
			$qtext1[$count1] = $fld_question_text;
			$qid1[$count1] = $fld_question_id;
			$count1++;
		    }
}

if($qryschedules1->num_rows > 0)
{
     $row1 = 1; // 1-based index
     for ($x1 = 0; $x1 <= $studentcount; $x1++) {
      $col1 = 0;
      if($row1 == 1) {
	$value1="Post Test     Enter a \"0\" for incorrect answers and a \"1\" for correct answers";
        $objPHPExcel->getActiveSheet()->setCellValue('A1', $value1);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:L1');
         $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
         $style1 = array('font' =>
                        array('color' =>
                          array('rgb' => '000000'),
                          'bold' => true,
                        ),
               'alignment' => array(

                          'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                          'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                            ),
         );
        $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->applyFromArray($style1);
        $objPHPExcel->getActiveSheet()->getRowDimension($row1)->setRowHeight(25);
       }  // ends of if($row == 1)
       elseif($row1 == 2) {
           $letters = range('A','L');
           $cnt =0;
           $cell_name="";
           for($c1=0;$c1<sizeof($qid1);$c1++) {
                $cnt++;
               $cell_name = $letters[$cnt]."2";
               $Questnid1 = $qid1[$c1];
               $objPHPExcel->getActiveSheet()->SetCellValue($cell_name, $Questnid1);
               $style1 = array('font' =>
                            array('color' =>
                              array('rgb' => '000000'),
                              'bold' => true,
                            ),
                   'alignment' => array(

                              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                ),
             );
            $objPHPExcel->getActiveSheet()->getStyle('A2:L2')->applyFromArray($style1);
            $objPHPExcel->getActiveSheet()->getRowDimension($row1)->setRowHeight(20);
           }
       }  //ends of elseif($row1 == 2)
       elseif($row1 == 3) {
           $letters1 = range('A','L');
           $cnt1 =0;
           $cell_name1="";
           $exactval = sizeof($qid1)+1;
           for($c2=0;$c2<(sizeof($qid1)+2);$c2++) {
               $cell_name1 = $letters1[$cnt1]."3"; 
               $cnt1++;
               if($c2 == 0) {
                   $objPHPExcel->getActiveSheet()->SetCellValue($cell_name1, 'Student #' );
               }
               elseif($c2 < $exactval){
           		$Qtext = $qtext1[$c2-1];
			$split = str_word_count($Qtext, 1, 'àáãç30123456789()');
                        $qlastword = end($split);
                        $objPHPExcel->getActiveSheet()->setCellValue($cell_name1, $qlastword );
                        reset($qlastword);
               }
               elseif($exactval == $c2) {
                        $objPHPExcel->getActiveSheet()->SetCellValue($cell_name1, 'Total Score' );
               }
           }  
           $style1 = array('font' =>
						            array('color' =>
						              array('rgb' => '000000'),
						              'bold' => true,
						            ),
						   'alignment' => array(
						             
						              'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
						                ),
			);
			
			$objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style1);
			$objPHPExcel->getActiveSheet()->getRowDimension('A3:L3')->setRowHeight(20);
       }   //ends of elseif($row1 == 3)
       else{
           $totalscore1 = 0;
           $colcnt = $x1 +1;
           for ($y1 = 0; $y1 <= 11; $y1++) {
               if($y1 == 0) {
                  $cell_colname = "A".$colcnt; 
                  $objPHPExcel->getActiveSheet()->setCellValue($cell_colname, $studentid[$row1-4] );
               }
               elseif($y1 == 11) {
                    $result_col = "L".$colcnt;
                    $objPHPExcel->getActiveSheet()->setCellValue($result_col, $totalscore1);
               }
               else{
                  
                     $letters3 = range('B','K');
                     $cell_colname = $letters3[$y1-1].$colcnt;
                   
                       $qryanswerstu = $ObjDB->SelectSingleValue("SELECT fld_correct FROM itc_module_answer_track WHERE fld_tester_id='".$studentid[$row1-4]."' AND 
                                                     fld_module_id='".$id[4]."' AND fld_schedule_id='".$id[3]."' AND 
                                                     fld_schedule_type='".$id[5]."' AND fld_page_id=0 AND fld_delstatus='0' 
                                                     AND fld_question_id='".$qid1[$y1-1]."'");
                       $objPHPExcel->getActiveSheet()->setCellValue($cell_colname, $qryanswerstu);
                       $totalscore1=$totalscore1 + $qryanswerstu;
                     
               }
                   $col1++;
           }
       }
     $row1++;
    }
}
for($col = 'A'; $col !== 'L'; $col++) {
    $objPHPExcel->getActiveSheet()
        ->getColumnDimension($col)
        ->setAutoSize(true);
}

// Rename 2nd sheet
$objPHPExcel->getActiveSheet()->setTitle('Post Test');
// Create a first sheet, representing sales data
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="name_of_file.xls"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

