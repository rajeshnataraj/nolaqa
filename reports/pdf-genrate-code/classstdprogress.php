<?php
error_reporting(0);
ini_set('display_errors', 1);
@include("sessioncheck.php");

require_once('../tcpdf/config/lang/eng.php');
require_once('../tcpdf/tcpdf.php');

$method = $_REQUEST;

$id = isset($method['id']) ? $method['id'] : '0';
$ids = explode(",",$id);
$filename = isset($method['filename']) ? $method['filename'] : '';


 $url=$domainame;

/*
 * starts to select class std progress
 */

/*
* for lesson progress completed report reg. unit
*/
$qrydetails = '';
if($ids[1]==0)
{

         $qrydetails ="SELECT a.fld_sigmath_id,b.fld_unit_id as unitid FROM itc_class_sigmath_student_mapping AS a 
                        LEFT JOIN itc_class_sigmath_unit_mapping AS b ON b.fld_sigmath_id =a.fld_sigmath_id 
                        WHERE a.fld_sigmath_id='".$ids[0]."' AND a.fld_flag='1' AND b.fld_flag='1' group by b.fld_unit_id order by b.fld_unit_id";
					
	$testtype='1';
        $tot_no_ofstudent = $ObjDB->SelectSingleValue("SELECT count(fld_id) FROM itc_class_sigmath_student_mapping where fld_sigmath_id ='".$ids[0]."' AND fld_flag=1");
}
// /*
//  * for Dyad completed progress report
//  */
else if($ids[1]==2)
{
    $qrydetails = "SELECT A.fld_schedule_id as schid, A.fld_module_id as modid
                        FROM itc_class_dyad_schedule_modulemapping A
                        WHERE A.fld_schedule_id = '".$ids[0]."' group by A.fld_module_id order by A.fld_module_id ";
    $tablenamefirst='itc_class_dyad_schedule_modulemapping';
    $tablenamesec='itc_class_dyad_schedule_studentmapping';
    $tot_no_ofstudent = $ObjDB->SelectSingleValue("SELECT count(fld_id) FROM ".$tablenamesec." where fld_schedule_id ='".$ids[0]."' AND fld_flag=1");
}
 /*
  * for Triad completed progress report
  */
else if($ids[1]==3)
{
    $qrydetails = "SELECT A.fld_schedule_id as schid, A.fld_module_id as modid
                        FROM itc_class_triad_schedule_modulemapping A
                        WHERE A.fld_schedule_id = '".$ids[0]."' group by A.fld_module_id order by A.fld_module_id ";
    $tablenamefirst='itc_class_triad_schedule_modulemapping ';
    $tablenamesec='itc_class_triad_schedule_studentmapping';
    $tot_no_ofstudent = $ObjDB->SelectSingleValue("SELECT count(fld_id) FROM ".$tablenamesec." where fld_schedule_id ='".$ids[0]."' AND fld_flag=1");
}
  /*
  * for modules and math modules completed progress report
  */
 else if($ids[1]==1 || $ids[1]==4)
{
    	
     $qrydetails = "SELECT A.fld_schedule_id as schid, A.fld_module_id as modid
                        FROM itc_class_rotation_moduledet A
                        WHERE A.fld_schedule_id = '".$ids[0]."' group by A.fld_module_id order by A.fld_module_id ";
     $tablenamefirst='itc_class_rotation_moduledet';
     $tablenamesec='itc_class_rotation_schedule_student_mappingtemp';
        if($ids[1]==4)
            $testtype='2';
        $tot_no_ofstudent = $ObjDB->SelectSingleValue("SELECT count(fld_id) FROM ".$tablenamesec." where fld_schedule_id ='".$ids[0]."' AND fld_flag=1");
      
}
/*
  * for individual modules, math modules and quest completed progress report
  */
else if($ids[1]==5 || $ids[1]==6 || $ids[1]==7)
{
    $qrydetails = "SELECT A.fld_id as schid, A.fld_module_id as modid
                        FROM itc_class_indassesment_master A
                        WHERE A.fld_id = '".$ids[0]."' group by A.fld_module_id order by A.fld_module_id ";
    $tablenamefirst='itc_class_indassesment_master';
    $tablenamesec='itc_class_indassesment_student_mapping';
    if($ids[1]==6)
        $testtype='3';
    $tot_no_ofstudent = $ObjDB->SelectSingleValue("SELECT count(fld_id) FROM ".$tablenamesec." where fld_schedule_id ='".$ids[0]."' AND fld_flag=1");
    
}
$qryschedules = $ObjDB->QueryObject($qrydetails);
 $lessonpart =array();
 $studentlist = array();
 
if($qryschedules->num_rows > 0)
{
    $rowcount=0;
    $studentcount = 0;
    // starts the qryschedules loop
    while($rowschedules=$qryschedules->fetch_assoc())
	{
              	$rowcount++;
		extract($rowschedules);
               
		$status=0;
              
                /*
                 * for lesson progress completed report reg. unit
                 */
                if($ids[1]==0)
                {
                   
                       $qryind_lessons=$ObjDB->QueryObject("SELECT a.fld_lesson_id as lessons,b.fld_unit_id as units FROM itc_class_sigmath_lesson_mapping AS a 
                                                            LEFT JOIN itc_ipl_master AS b ON a.fld_lesson_id=b.fld_id WHERE a.fld_sigmath_id='".$ids[0]."'
                                                            AND a.fld_flag='1' AND b.fld_unit_id='".$unitid."' 
                                                            AND b.fld_access='1' AND b.fld_delstatus='0' ORDER BY a.fld_lesson_id");
                    while($row_qryind_lessonss=$qryind_lessons->fetch_assoc())
                {
                    
                  
                   extract($row_qryind_lessonss);
                    array_push($lessonpart, $lessons); 
                    array_push($lessonpart,'~');             
                   $count_ind_lesson=$ObjDB->QueryObject("SELECT fld_student_id as studentid FROM itc_assignment_sigmath_master 
                                                                        WHERE fld_schedule_id='".$ids[0]."' AND fld_unit_id='".$unitid."' 
                                                                        AND (fld_status='1' OR fld_status='2') AND fld_test_type='".$testtype."' 
                                                                        AND fld_lesson_id='".$lessons."' AND fld_delstatus='0' GROUP BY fld_student_id ORDER BY fld_student_id");
                   while($row_qrygroup_students=$count_ind_lesson->fetch_assoc())
                {
                  
                   extract($row_qrygroup_students);
                   array_push($studentlist, $studentid); 
                   
                }
                array_push($studentlist,'~');
              }
                
      
        
        }
        /*
         * for modules,mathmodules,dyad completed progress report
         */
 else  {
          if($ids[1]==5 || $ids[1]==6 || $ids[1]==7)
          {
             
                $groupquerydet = $ObjDB->QueryObject("SELECT A.fld_id as schid, A.fld_module_id as moduleid,X.fld_student_id as studentid
                                                        FROM itc_class_indassesment_master A
                                                        LEFT OUTER JOIN itc_class_indassesment_student_mapping X on A.fld_id = X.fld_schedule_id 
                                                        WHERE A.fld_id = '".$ids[0]."' AND A.fld_module_id='".$modid."'"); 
          }
          /*modules and mathmodules repor */
          else
          {
              $groupquerydet = $ObjDB->QueryObject("SELECT A.fld_schedule_id as schid, A.fld_module_id as moduleid,X.fld_student_id as studentid
                                                        FROM ".$tablenamefirst." A
                                                        LEFT OUTER JOIN ".$tablenamesec." X on A.fld_schedule_id = X.fld_schedule_id 
                                                        WHERE A.fld_schedule_id = '".$ids[0]."' AND A.fld_module_id='".$modid."'"); 
          }
             
               while($rowgroupsmodule=$groupquerydet->fetch_assoc())
	{
                extract($rowgroupsmodule);
		$sesscompleted = 0;
                $totalchapters = 7;
                if($ids[1]==4)
                    $newmodid = $ObjDB->SelectSingleValueInt("SELECT fld_module_id FROM itc_mathmodule_master WHERE fld_id='".$moduleid."'");
                else
                $newmodid = $moduleid;
              
                $newstudentid = $studentid;
                
                for($i=0;$i<$totalchapters;$i++)
                {
                    
                    $sesscount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_module_points_master WHERE fld_schedule_id='".$ids[0]."' AND fld_module_id='".$moduleid."' AND fld_student_id='".$newstudentid."' AND fld_schedule_type='".$ids[1]."' AND fld_preassment_id='0' AND fld_session_id='0' AND fld_delstatus='0' AND fld_type<>'0'");
         
                    $viewedpages = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_module_play_track WHERE fld_schedule_id='".$ids[0]."' AND fld_module_id='".$moduleid."' AND fld_tester_id='".$newstudentid."' AND fld_schedule_type='".$ids[1]."' AND fld_section_id='".$i."' AND fld_delstatus='0'");

                    $totalpages = $ObjDB->SelectSingleValueInt("SELECT fld_points_possible FROM itc_module_performance_master WHERE fld_module_id='".$newmodid."' AND fld_session_id='".$i."' AND fld_delstatus='0' AND fld_performance_name='Total Pages'");

                    $totalsess = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_module_performance_master WHERE (fld_performance_name = 'Attendance' OR fld_performance_name = 'Participation') AND fld_module_id='".$newmodid."' AND fld_delstatus='0' AND fld_session_id='".$i."'");

                   if($sesscount==$totalsess && $viewedpages>=$totalpages)
                   {
                       $sesscompleted++;
                   }
            }
                  if($sesscompleted==$totalchapters)
                 {
                      $status = 1;
                      $ind_module = $moduleid;
                      array_push($studentlist, $newstudentid); 
                  }
       
        }
        if(isset($ind_module))
        {
        if($moduleid==$ind_module )
        {
            array_push($lessonpart, $ind_module);
            array_push($studentlist,'~');
            array_push($lessonpart,'~');
        }
      
        }

        
 }
        }   // // ends the qryschedules loop
     
}

/*
 * start to display the pdf file reg. products and standards name
 */

class MYPDF extends TCPDF {
    public function Header() { // Page header
        $method = $_REQUEST;
	global $ObjDB;
        $id = isset($method['id']) ? $method['id'] : '0';
	$ids=$id;
        $ids = explode(",",$ids);
        $title = "Class Standards Progress";
        $this->SetTextColor(0,0,0);
        $this->SetFont('arialblack', '', 18);
        $this->Text(10, 18, $title);
        $this->Image('scans/../report.png', 10, 40, 19, 8, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);      
        $this->SetFont('arialblack', '', 13);
        
        $style=array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        $this->Line(10,48,190,48, $style);   
        $this->SetFont('arial', '', 10);
        
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

  
    $total_cnt_studentlist=count($studentlist);   

    $elemenatelast_val = array_pop($lessonpart);
    $comma_separated = implode("", $lessonpart);
    $split_lessonid=explode("~",$comma_separated);
    $comma_seprted_studlist = implode(",", $studentlist);
    $split_students=explode("~",$comma_seprted_studlist);
    $productid=array();
    $productname=array();
    $arraynum=array();
    $arraycontent=array();
    $arrayguids=array();
    $finalstandardlist=array();
    $$standardindividualcount=array();
    $standardsname=array();
    if($ids[1]==0)
    {
        $prdtesttype ='1';
    }
     if($ids[1]==1)
     {
         $prdtesttype ='3';
     }
     if($ids[1]==4)
     {
         $prdtesttype ='4';
     }
 /*
  * select state,standard document name and grade from tables
  */    
    $statename = $ObjDB->SelectSingleValue("SELECT fld_name FROM itc_standards_bodies WHERE fld_id='".$ids[3]."'");

    $standardname = $ObjDB->SelectSingleValue("SELECT A.fld_doc_title FROM itc_correlation_documents as A
                                                left join itc_correlation_doc_subject as b 
                                                on b.fld_doc_id = A.fld_id where b.fld_sub_guid='".$ids[4]."'");

    $gradename =$ObjDB->SelectSingleValue("SELECT fld_grade_name FROM itc_correlation_grades where fld_grade_guid='".$ids[5]."'"); 

foreach ($split_lessonid as $value) {
   
    $prod_details = $ObjDB->QueryObject("SELECT fld_prd_asset_id as producttid, fld_prd_name as prodctname
                                            FROM itc_correlation_products WHERE fld_prd_sys_id='".$value."' AND fld_prd_type='".$prdtesttype."'");
  
   
    if($prod_details->num_rows > 0){
        while($rowqry = $prod_details->fetch_assoc())
        {
        extract($rowqry);

        $productid[]=$producttid;
        $productname[]=$prodctname;

        }
    }
}
/*  if prod_detais is more than one */
   if($prod_details->num_rows > 0)
   {
       $standardids=$ids[5];
     
       if($standardids !='')
       {      
        $string = file_get_contents($url."reports/correlation/standards/".$standardids.".xml");
       
        $doc = new DOMDocument();
        $doc->loadXML($string);
        $xpath = new DOMXpath($doc);
        $coursecnt=$xpath->evaluate('count(//itm[@type="course"])');
        $gradecnt=$xpath->evaluate('count(//itm[@type="grade"])');
        if($coursecnt!=0)
        {
        $typ='course';
        }
        else if($gradecnt!=0)
        {
        $typ='grade';
        }
        $grades = $xpath->query("//itm[@type='".$typ."']//itm[@type='standard']/meta");
        $guids = $xpath->query("//itm[@type='".$typ."']//itm[@type='standard']");
        $gradenameqry = $xpath->evaluate('//itm[@type="'.$typ.'"]');
        $gradename=$gradenameqry->item(0)->getAttribute("title");
        $standardindividualcount[]=$guids->length;
        $standardsname[]=$gradenameqry->item(0)->getAttribute("title");
        $i=0;
        $j=1;
        $k=1;
        foreach($grades as $seat)
        {
           if($i%2==0)
            {
                //Main category list from standards document list id(name="num"),listname(name="descr"),list gradeid(guid="" for type )
            $arraynum['num'.$j]=$seat->nodeValue;
            $j++;
            }
            else if($i%2!=0)
            {
                
            $arraycontent['content'.$k]=$seat->nodeValue;
            $k++;          
            }
            $i++;
        }
       
        $a=1;
        foreach($guids as $guid)
        {
        $arrayguids['guid'.$a]=$guid->getAttribute("guid");
        $a++;
        }
        $finalstandardlist[]=array($gradename=>array($arraynum,$arraycontent,$arrayguids));
        unset($arraynum);
        unset($arraycontent);
        unset($arrayguids);
       }
        $productarray=array();
        $productrel=array();
        for($h=0;$h<sizeof($productid);$h++)
        {            
            $string = file_get_contents($url."reports/correlation/products/".$productid[$h].".xml");
            $stddocs = array();

            $doc = new DOMDocument();
            $doc->loadXML($string);
            $xpath = new DOMXpath($doc);
            $items = $xpath->query("//itm");
            $salign = array();
            if($items->length > 0) {
            $i = 0;
            //selecting guid and rel from prodcuts xml file
            foreach($items as $item)
            {
            $guid = $item->getAttribute('guid');
            $rel= $item->getAttribute('rel');
            $salign[$i] = $guid;
            $prdctrel[$i]=$rel;
            $i++;
            }
            }
            $productarray[]=array($productname[$h]=>$salign);
            $productrel[]=array($productname[$h]=>$prdctrel);
            
        }
 
   }   //end the prod_details > 0

/*
 * ends to select class std progress
 */


/////

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle("Progress Report");
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
        $pdf->AddPage();
         $html='% of students who have addressed the following standards:';
         $pdf->writeHTML($html, true, false, true, false, '');
         $pdf->SetTextColor(0,0,0);
         $pdf->SetFont('arial', '', 16);
         $html='<p>'.$statename.'&nbsp;&nbsp;'.$standardname.'&nbsp;&nbsp;'.$gradename.'</p>';

       $pdf->writeHTML($html, true, false, true, false, '');
        $unolist=array();
        $olist=array();
       $pdf->SetTextColor(0,0,0); 
       $headingcontainspoint =0;  
        for($z=0;$z<sizeof($finalstandardlist);$z++)
          {
            $numItems = count($finalstandardlist[$z][key($finalstandardlist[$z])][0]) -1;
            for($c=0;$c<sizeof($finalstandardlist[$z][key($finalstandardlist[$z])][0]);$c++)
            {
                $cnt=0;	
                $cnt=$c+1;
                $prdctname='';
               $lset_prodname='';
                if($finalstandardlist[$z][key($finalstandardlist[$z])][0]['num'.$cnt] == '')
                  {
                       $storeparentdescr=$finalstandardlist[$z][key($finalstandardlist[$z])][1]['content'.$cnt];
                  }
                for($g=0;$g<sizeof($productarray);$g++)
                {

                   $key = array_search($finalstandardlist[$z][key($finalstandardlist[$z])][2]['guid'.$cnt],$productarray[$g][key($productarray[$g])]);
                   if($key!='')
                          {
                              $prdctname.="<li>".key($productarray[$g])."</li>";
                              $lset_prodname.=key($productarray[$g]).",";
                          }
                }  // ends loop $productarray count($g)



//To display child node with product name(sub title)
             if($prdctname!='')  {
                  if($storeparentdescr != $swap_TitleDesc) {
                    $pdf->SetFont('arial', '', 15);
              
                  $html='<br/><p>'.$storeparentdescr.'</p>';
                  $pdf->writeHTML($html, true, false, true, false, '');
                }
                 $swap_TitleDesc =  $storeparentdescr;
                 $pdf->SetFont('arial', '', 12);
                 $html='<p></p><p>'.$finalstandardlist[$z][key($finalstandardlist[$z])][0]['num'.$cnt].'&nbsp;&nbsp;&nbsp;';
                  $new = htmlspecialchars($finalstandardlist[$z][key($finalstandardlist[$z])][1]['content'.$cnt], ENT_QUOTES);
                  $html.=$new.'</p><p></p>';

                  if($finalstandardlist[$z][key($finalstandardlist[$z])][0]['num'.$cnt]!='' and $prdctname!='') {                     
                    }
                    
                        $set_prodcut_list = trim($lset_prodname, ',');
                        $sep_products = explode(",",$set_prodcut_list);
                        $stud_array=array();
                        $result_unique_studentlist=array();
                        for($w=0;$w<sizeof($sep_products);$w++)
                        {
                            $get_lessonid = $ObjDB->SelectSingleValue("SELECT fld_prd_sys_id FROM itc_correlation_products WHERE fld_prd_name='".strip_tags($sep_products[$w])."'");
                            $key_lessonid = array_search($get_lessonid, $split_lessonid);
                            $sep_student_list = trim($split_students[$key_lessonid], ',');
                           if($sep_student_list != '')
                            {
                            $sep_pieces_students = explode(",", $sep_student_list);
                            $total_studentlist = array_merge($stud_array, $sep_pieces_students);
                            $stud_array=$total_studentlist;
                            $result_unique_studentlist = array_unique($stud_array);
                                 $total_students = count($result_unique_studentlist);
                        }
                   
                        }
                
                        $total_students = count($result_unique_studentlist);
                    
                        $percg_4class_positive = round(($total_students/$tot_no_ofstudent)*100);
                        $percg_4class_negative = 100 - $percg_4class_positive;
                     
                        $pdf->writeHTML($html, true, false, true, false, '');
                        if($percg_4class_positive > 15)
                        {
                            $pdf->SetFont('times', 'BI', 20);
                        }
                        elseif($percg_4class_positive > 9 && $percg_4class_positive < 16 )
                        {
                           $pdf->SetFont('times', 'BI', 17); 
                        }
                        else
                        {
                            $pdf->SetFont('times', 'BI', 10);
                        }
                         if($percg_4class_negative ==100 && $percg_4class_positive == 0)
                        {
                            $pdf->Ln();
                             $pdf->SetFont('times', 'BI', 20);
                           $pdf->SetLeftMargin(50);
                           $pdf->SetRightMargin(60);
                           $pdf->SetFillColor(255,255,255);
                            $pdf->SetTextColor(0,0,0);
                           $pdf->MultiCell(100, 10, '100%', 1, 'C', 1, 1, '', '', true);
                           $pdf->SetMargins(PDF_MARGIN_LEFT, 70, PDF_MARGIN_RIGHT);
                        }
                      else if($percg_4class_positive == 100 && $percg_4class_negative ==0)
                        {                   
                             $pdf->Ln();
                             $pdf->SetFont('times', 'BI', 20);
                           $pdf->SetLeftMargin(50);
                           $pdf->SetRightMargin(60);
                           $pdf->SetFillColor(0,153,76);
                            $pdf->SetTextColor(0,0,0);
                           $pdf->MultiCell(100, 10, '100%', 1, 'C', 1, 1, '', '', true);
                           $pdf->SetMargins(PDF_MARGIN_LEFT, 70, PDF_MARGIN_RIGHT);
                       
                        }
                        else
                        {
                             $pdf->SetMargins(50,70,50,FALSE);
                        if($percg_4class_positive != 0) {
                          
                        
                        $pdf->Ln();
                        $text=$percg_4class_positive."%";
                        $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
                        $pdf->SetFillColor(0,153,76);
                        $pdf->SetTextColor(0,0,0);
                        $pdf->MultiCell($percg_4class_positive, 10, $text, 1, 'C', 1, 0);
                        }
                        if($percg_4class_negative >9)
                        {
                            $pdf->SetFont('times', 'BI', 20);
                        }
                        else
                        {
                            $pdf->SetFont('times', 'BI', 10);
                        } 
                        
                        if($percg_4class_negative !=100)
                        {
                        $pdf->SetMargins(50,70,50,FALSE);
                        $text1=$percg_4class_negative."%";
                        $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
                        $pdf->SetFillColor(255,255,255);
                        $pdf->SetTextColor(0,0,0);
                        $pdf->MultiCell($percg_4class_negative, 10, $text1, 1, 'C', 1, 1);
                        $pdf->SetMargins(PDF_MARGIN_LEFT, 70, PDF_MARGIN_RIGHT);
                        }
                        }
                       

             }  // endif $prdctname!=''


            }   //ends loop $finalstandardlist($c count)

          }   //ends loop $finalstandardlist($z count)

}
else
{
	$pdf->AddPage();
	$html = "no records";
	$pdf->writeHTML($html, true, false, true, false, '');
}

 

@include("footer.php");
//Close and output PDF document
$pdf->Output('pdf/'.$filename.'.pdf', 'F');