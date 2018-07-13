<?php 

@include("table.class.php");
@include("comm_func.php");
$method = $_REQUEST;
$id = isset($method['id']) ? $method['id'] : '0';
$id = explode(",",$id);

$status=0;
$lessonstatus =0;

?>
<style>
	.title
	{
		font-size: 50px; color:#808080; font-family:Arial;
	}
	.trgray
	{
		font-size:30px; background-color:#CCCCCC; font-weight:normal; 
	}
	.trclass
	{
		font-size:30px; background-color:#FFFFFF; font-weight:normal;
	}
	.tdleft{
		border-top:1px solid #b4b4b4; border-left:1px solid #b4b4b4; border-bottom:1px solid #b4b4b4;
	}
	
	.tdmiddle{
		border-top:1px solid #b4b4b4; border-bottom:1px solid #b4b4b4;
	}
	.tdright{
		border-top:1px solid #b4b4b4; border-right:1px solid #b4b4b4; border-bottom:1px solid #b4b4b4;
	}
	.master{
		color:#090;
	}
</style>

<?php 
error_reporting(0);
$qrydetails = '';
if($id[1]==0)
{
     ?> <p style="font-weight:bold;font-family:Arial;font-size:50px" >Lesson Completed</p> 
     <table>
          
            <?php 
          
                $qrymoduledet = $ObjDB->QueryObject("SELECT a.fld_id as pids,CONCAT(a.fld_ipl_name,' ',c.fld_version) as modunnames 
                                                        FROM itc_ipl_master as a
                                                        left join  itc_assignment_sigmath_master as b on a.fld_id=b.fld_lesson_id 
                                                        left join  itc_ipl_version_track as c on a.fld_id=c.fld_ipl_id 
                                                        WHERE b.fld_class_id='".$id[2]."' AND b.fld_student_id='".$id[3]."' and b.fld_schedule_id='".$id[0]."' 
                                                            AND (b.fld_status='1' OR b.fld_status='2' OR b.fld_lock='1') AND b.fld_delstatus='0' 
                                                            AND c.fld_zip_type='1' AND c.fld_delstatus='0'");
                
                while($rowipl=$qrymoduledet->fetch_assoc())
                    {
                            extract($rowipl);
                            $lessonstatus =1;
                            ?>
                                <tr>
                                    <td style="font-size:35px;">&nbsp;&nbsp;&nbsp;<?php echo $modunnames;?></td>	
                                </tr>
                            <?php
                    }
                    if($lessonstatus==0){
                       ?>
                        <tr>
                            <td style="font-size:35px;">&nbsp;&nbsp;&nbsp;<?php echo "No Lesson completed";?></td>	
                        </tr>
                    <?php
                }
            ?>
    </table>
    <?php
}
else 
{
 ?>
    <table>
            <tr>
                <td style="font-weight:bold;font-family:Arial;font-size:50px">Modules Completed</td>	
            </tr>
            <?php
                $totallistmodules= array();
                $listmodulescom = array();
                $listmodulesuncom = array();
              if($id[1]==1){
                    $qrymoduledet = $ObjDB->QueryObject("SELECT a.fld_module_id AS ids, CONCAT(b.fld_module_name,' ',c.fld_version) AS modunnames, 
                                                                a.fld_rotation AS rotation, d.fld_startdate AS startdate, d.fld_enddate AS enddate 
                                                        FROM itc_class_rotation_schedulegriddet AS a 
                                                        LEFT JOIN itc_class_rotation_scheduledate AS d ON a.fld_schedule_id=d.fld_schedule_id AND a.fld_rotation=d.fld_rotation
                                                        LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
                                                        LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
                                                        WHERE a.fld_class_id='".$id[2]."' AND a.fld_schedule_id='".$id[0]."' AND a.fld_student_id='".$id[3]."' 
                                                                AND a.fld_flag='1' AND b.fld_delstatus='0' AND c.fld_delstatus='0' AND d.fld_flag='1'");
                }
                else if($id[1]==2)
                {
                    $qrymoduledet = $ObjDB->QueryObject("SELECT a.fld_module_id AS ids, CONCAT(b.fld_module_name,' ',c.fld_version) AS modunnames, a.fld_rotation AS rotation, 
                                                            a.fld_startdate AS startdate, a.fld_enddate AS enddate 
                                                    FROM itc_class_dyad_schedulegriddet AS a 
                                                    LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
                                                    LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
                                                    WHERE a.fld_class_id='".$id[2]."' AND a.fld_schedule_id='".$id[0]."' AND (a.fld_student_id='".$id[3]."' OR a.fld_rotation='0')
                                                            AND a.fld_flag='1' AND b.fld_delstatus='0' AND c.fld_delstatus='0'");
                }
                else if($id[1]==3)
                {
                    $qrymoduledet = $ObjDB->QueryObject("SELECT a.fld_module_id AS ids, CONCAT(b.fld_module_name,' ',c.fld_version) AS modunnames, a.fld_rotation AS rotation, 
                                                                a.fld_startdate AS startdate, a.fld_enddate AS enddate 
                                                        FROM itc_class_triad_schedulegriddet AS a 
                                                        LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
                                                        LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
                                                        WHERE a.fld_class_id='".$id[2]."' AND a.fld_schedule_id='".$id[0]."' AND (a.fld_student_id='".$id[3]."' OR a.fld_rotation='0')
                                                                AND a.fld_flag='1' AND b.fld_delstatus='0' AND c.fld_delstatus='0'");
                }
                else if($id[1]==4)
                {
                    $qrymoduledet = $ObjDB->QueryObject("SELECT a.fld_module_id AS ids, CONCAT(b.fld_mathmodule_name,' ',c.fld_version) AS modunnames, 
                                                                a.fld_rotation AS rotation, d.fld_startdate AS startdate, d.fld_enddate AS enddate 
                                                        FROM itc_class_rotation_schedulegriddet AS a 
                                                        LEFT JOIN itc_class_rotation_scheduledate AS d ON a.fld_schedule_id=d.fld_schedule_id AND a.fld_rotation=d.fld_rotation
                                                        LEFT JOIN itc_mathmodule_master AS b ON a.fld_module_id=b.fld_id 
                                                        LEFT JOIN itc_module_version_track AS c ON b.fld_module_id=c.fld_mod_id 
                                                        WHERE a.fld_class_id='".$id[2]."' AND a.fld_schedule_id='".$id[0]."' AND a.fld_student_id='".$id[3]."' 
                                                                AND a.fld_flag='1' AND b.fld_delstatus='0' AND c.fld_delstatus='0' AND d.fld_flag='1'");
                }
                else if($id[1]==5)
                {
                    $qrymoduledet = $ObjDB->QueryObject("SELECT a.fld_module_id AS ids, CONCAT(c.fld_module_name,' ',d.fld_version) AS modunnames, 0 AS rotation, 
                                                                a.fld_startdate AS startdate, a.fld_enddate AS enddate 
                                                        FROM itc_class_indassesment_master AS a
                                                        LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id
                                                        LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id 
                                                        LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id 
                                                        WHERE a.fld_class_id='".$id[2]."' AND a.fld_id='".$id[0]."' AND b.fld_student_id='".$id[3]."' AND b.fld_flag='1'
                                                                AND a.fld_flag='1' AND c.fld_delstatus='0' AND d.fld_delstatus='0'");
                }
                else if($id[1]==6)
                {
                    $qrymoduledet = $ObjDB->QueryObject("SELECT a.fld_module_id AS ids, CONCAT(c.fld_mathmodule_name,' ',d.fld_version) AS modunnames, 0 AS rotation, 
                                                                a.fld_startdate AS startdate, a.fld_enddate AS enddate 
                                                        FROM itc_class_indassesment_master AS a
                                                        LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id
                                                        LEFT JOIN itc_mathmodule_master AS c ON a.fld_module_id=c.fld_id 
                                                        LEFT JOIN itc_module_version_track AS d ON c.fld_module_id=d.fld_mod_id 
                                                        WHERE a.fld_class_id='".$id[2]."' AND a.fld_id='".$id[0]."' AND b.fld_student_id='".$id[3]."' AND b.fld_flag='1'
                                                                AND a.fld_flag='1' AND c.fld_delstatus='0' AND d.fld_delstatus='0'");
               }
               else if($id[1]==7)
                {
                   $qrymoduledet = $ObjDB->QueryObject("SELECT a.fld_module_id AS ids, CONCAT(c.fld_module_name,' ',d.fld_version) AS modunnames, 0 AS rotation, 
                                                                a.fld_startdate AS startdate, a.fld_enddate AS enddate 
                                                        FROM itc_class_indassesment_master AS a
                                                        LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id
                                                        LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id 
                                                        LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id 
                                                        WHERE a.fld_class_id='".$id[2]."' AND a.fld_id='".$id[0]."' AND b.fld_student_id='".$id[3]."' AND b.fld_flag='1'
                                                                AND a.fld_flag='1' AND c.fld_delstatus='0' AND d.fld_delstatus='0'");
               }
                while($rowipl=$qrymoduledet->fetch_assoc())
                    {
                            extract($rowipl);
                            array_push($totallistmodules,$ids);
                            $sesscompleted = 0;
                            if($id[1]==7)
                            {
                                    $totalchapters = $ObjDB->SelectSingleValueInt("SELECT MAX(fld_session_id)+1 
                                                                                                                            FROM itc_module_performance_master 
                                                                                                                            WHERE fld_module_id='".$ids."'");
                            }
                            else
                            {
                                    $totalchapters = 7;
                            }
                            
                            if($id[1]==4 || $id[1]==6)
				$newmodid = $ObjDB->SelectSingleValueInt("SELECT fld_module_id FROM itc_mathmodule_master WHERE fld_id='".$ids."'");
                            else
				$newmodid = $ids;
                            for($i=0;$i<$totalchapters;$i++)
                            {	
                                $sesscount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_module_points_master WHERE fld_schedule_id='".$id[0]."' AND fld_module_id='".$ids."' AND fld_student_id='".$id[3]."' AND fld_schedule_type='".$id[1]."' AND fld_preassment_id='0' AND fld_session_id='".$i."' AND fld_delstatus='0' AND fld_type<>'0'");

                                $viewedpages = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_module_play_track WHERE fld_schedule_id='".$id[0]."' AND fld_module_id='".$ids."' AND fld_tester_id='".$id[3]."' AND fld_schedule_type='".$id[1]."' AND fld_section_id='".$i."' AND fld_delstatus='0'");

                                $totalpages = $ObjDB->SelectSingleValueInt("SELECT fld_points_possible FROM itc_module_performance_master WHERE fld_module_id='".$newmodid."' AND fld_session_id='".$i."' AND fld_delstatus='0' AND fld_performance_name='Total Pages'");

                                $totalsess = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_module_performance_master WHERE (fld_performance_name = 'Attendance' OR fld_performance_name = 'Participation') AND fld_module_id='".$newmodid."' AND fld_delstatus='0' AND fld_session_id='".$i."'");
                             
                                     
                                if($sesscount==$totalsess && $viewedpages>=$totalpages)
                                        $sesscompleted++;
                            }

                            if($sesscompleted==$totalchapters){
                                $status=1;
                                 array_push($listmodulescom,$ids);
                            ?>
                                <tr>
                                    <td style="font-size:35px;">&nbsp;&nbsp;&nbsp;<?php echo $modunnames;?></td>	
                                </tr>
                            <?php
                            }
                            else{
                                array_push($listmodulesuncom,$ids);
                            }
                            
                    }
                    if($status==0){
                       ?>
                        <tr>
                            <td style="font-size:35px;">&nbsp;&nbsp;&nbsp;<?php echo "No Module completed";?></td>	
                        </tr>
                    <?php
                }
             ?>
    </table>
<?php
}
    
    $pid=array();
    $productid=array();
    $productname=array();
    
    $productid1=array();
    $productname1=array();
    
    $productid2=array();
    $productname2=array();
   
    $statename = $ObjDB->SelectSingleValue("SELECT fld_name FROM itc_standards_bodies WHERE fld_id='".$id[4]."'");
    $qryproductdetcompleted=array(); 
    $qryproductdetuncompleted=array();
    $qryproductdetcom=array();
       
   if($id[1]==0)
       {
       
        $qryproductdetcom[]=1;  
        $qryproductdetcompleted[] = 1;
        $qryproductdetuncompleted[]=1;
        
        $qryproductdetcom1 = $ObjDB->QueryObject("SELECT CONCAT(a.fld_ipl_name,' ',c.fld_version) as prodctname,d.fld_prd_asset_id as assetid 
                                                            FROM itc_ipl_master as a
                                                            left join  itc_assignment_sigmath_master as b on a.fld_id=b.fld_lesson_id 
                                                            left join  itc_ipl_version_track as c on a.fld_id=c.fld_ipl_id
                                                            left join  itc_correlation_products as d on a.fld_asset_id=d.fld_prd_id 
                                                            WHERE b.fld_class_id='".$id[2]."' AND b.fld_student_id='".$id[3]."' and b.fld_schedule_id='".$id[0]."' 
                                                                AND b.fld_delstatus='0' AND c.fld_zip_type='1' AND c.fld_delstatus='0'");
        
        $qryproductdetcompleted1 = $ObjDB->QueryObject("SELECT CONCAT(a.fld_ipl_name,' ',c.fld_version) as prodctname1,d.fld_prd_asset_id as assetid1 
                                                            FROM itc_ipl_master as a
                                                            left join  itc_assignment_sigmath_master as b on a.fld_id=b.fld_lesson_id 
                                                            left join  itc_ipl_version_track as c on a.fld_id=c.fld_ipl_id
                                                            left join  itc_correlation_products as d on a.fld_asset_id=d.fld_prd_id 
                                                            WHERE b.fld_class_id='".$id[2]."' AND b.fld_student_id='".$id[3]."' and b.fld_schedule_id='".$id[0]."' 
                                                                AND (b.fld_status='1' OR b.fld_status='2' OR b.fld_lock='1') 
                                                                AND b.fld_delstatus='0' AND c.fld_zip_type='1' AND c.fld_delstatus='0'");
        
        $qryproductdetuncompleted1 = $ObjDB->QueryObject("SELECT CONCAT(a.fld_ipl_name,' ',c.fld_version) as prodctname2,d.fld_prd_asset_id as assetid2 
                                                            FROM itc_ipl_master as a
                                                            left join  itc_assignment_sigmath_master as b on a.fld_id=b.fld_lesson_id 
                                                            left join  itc_ipl_version_track as c on a.fld_id=c.fld_ipl_id
                                                            left join  itc_correlation_products as d on a.fld_asset_id=d.fld_prd_id 
                                                            WHERE b.fld_class_id='".$id[2]."' AND b.fld_student_id='".$id[3]."' and b.fld_schedule_id='".$id[0]."' 
                                                                AND (b.fld_status !='1' and b.fld_status !='2' and b.fld_lock !='1') 
                                                                AND b.fld_delstatus='0' AND c.fld_zip_type='1' AND c.fld_delstatus='0'");
        
         
        }
        else{
           
           $qryproductdetcom=$totallistmodules;  
           $qryproductdetcompleted = $listmodulescom;
           $qryproductdetuncompleted = $listmodulesuncom;
          //print_r($listmodulescom);
                    
        }
        
        $standardname = $ObjDB->SelectSingleValue("SELECT fld_doc_title FROM itc_correlation_documents WHERE fld_id='".$id[6]."'");
        $standardids = $id[7];
        $finalstandardlist=array();
	$arraynum=array();
	$arraycontent=array();
	$arrayguids=array();
	$unorderedlist=array();
	$orderedlist=array();
	$standardsname=array();
	$standardindividualcount=array();
	
       
	if($standardids !='')
	{ 		
		$string = file_get_contents("../correlation/standards/".$standardids.".xml");
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
	
	 /*-----Addressed on current shuedule---------*/
        ?> <p style="font-weight:bold;font-family:Arial;font-size:50px" >Standard Addressed based on Current Schedule</p> <?php
        if(sizeof($qryproductdetcompleted)!=0){
             if($id[1]==0){
                while($rowqry1 = $qryproductdetcompleted1->fetch_assoc())
                {
                extract($rowqry1);
                  $productname1[]=$prodctname1;
                  $productid1[]=$assetid1;
                }
             }
             else{
                  for($a=0;$a<sizeof($qryproductdetcompleted);$a++){
                     $qry = $ObjDB->QueryObject("select a.fld_id as mid, b.fld_prd_name as prodctname1, b.fld_prd_asset_id as assetid1 
                                                                from itc_module_master AS a
                                                                left join itc_correlation_products as b on a.fld_asset_id=b.fld_prd_id 
                                                                WHERE a.fld_id='".$qryproductdetcompleted[$a]."' AND a.fld_delstatus='0'");                     
                                                                                   
                     while($rowqrya = $qry->fetch_assoc())
                    {
                    extract($rowqrya);
                      $productname1[]=$prodctname1;
                      $productid1[]=$assetid1;
                    }
                   }
                  
                  
             }            
           
           
            $productarray1=array();
            $productrel1=array();
            $prdctrel=array();

            for($h=0;$h<sizeof($productid1);$h++)
            {               
                    $string = file_get_contents("../correlation/products/".$productid1[$h].".xml");                  
                    $stddocs = array();

                    $doc = new DOMDocument();
                    $doc->loadXML($string);
                    $xpath = new DOMXpath($doc);
                    $items = $xpath->query("//itm");
                    $salign1 = array();
                    if($items->length > 0) {
                            $i = 0;
                            foreach($items as $item)
                            {
                                    $guid = $item->getAttribute('guid');
                                    $rel= $item->getAttribute('rel');
                                    $salign1[$i] = $guid;
                                    $prdctrel[$i]=$rel;				
                                    $i++;
                            }
                    }

                    $productarray1[]=array($productname1[$h]=>$salign1);
                    $productrel1[]=array($productname1[$h]=>$prdctrel);
                    

            }           
            $comstatus1 =0;
            $unolist=array();
            $olist=array();
            if(sizeof($productid1)!=0){
                for($z=0;$z<sizeof($finalstandardlist);$z++)
                { 
		?>
			<p style="font-family:Arial;color:#606060;font-size:40px;font-weight:bold" ><?php echo key($finalstandardlist[$z]); ?> </p>
			
			<table width="100%" border="0.5" cellpadding="8">
				<thead>
					<tr style="background-color:#00cc00;" > 
						<td align="center" width="25%" ></td> 
						<td  width="75%" style="color:#FFF;font-weight:bold;"><?php echo key($finalstandardlist[$z]); ?><br/><?php echo $statename."|".$standardname ?></td> 
					</tr>
				</thead> 
				<?php 
                                 
					for($c=0;$c<sizeof($finalstandardlist[$z][key($finalstandardlist[$z])][0]);$c++)
					{
                                             
						$cnt=0;	
						$cnt=$c+1;
						$prdctname1='';
						$fontcolorcnt=0;
						for($g=0;$g<sizeof($productarray1);$g++)
						{
                                                    $comstatus1 =1;
                                                   
							$key = array_search($finalstandardlist[$z][key($finalstandardlist[$z])][2]['guid'.$cnt],$productarray1[$g][key($productarray1[$g])]);								
                                                        if($key!='')
								{
                                                                   
									$prdctname1.="<li>".key($productarray1[$g])."</li>";
                                                                        
									$fontcolorcnt++;
									if($finalstandardlist[$z][key($finalstandardlist[$z])][0]['num'.$cnt]!='')
									{
									  $olist[]= array(key($productarray1[$g])=>array("num"=>$finalstandardlist[$z][key($finalstandardlist[$z])][0]['num'.$cnt],"content"=>$finalstandardlist[$z][key($finalstandardlist[$z])][1]['content'.$cnt],"guid"=>$finalstandardlist[$z][key($finalstandardlist[$z])][2]['guid'.$cnt],"standard"=> key($finalstandardlist[$z])));
									}
								}
						}
                                              
						if($fontcolorcnt==0)
						{
							$color="#606060";
							$unolist[]= $cnt;
						
						}
						else{
							$color="#000000"; 
                                                        
						}
                                               
                                                if($prdctname1!=''){
                                                    ?>
                                                    <tr nobr="true"  <?php if($finalstandardlist[$z][key($finalstandardlist[$z])][0]['num'.$cnt]=='')  {?> style="background-color:#606060;" <?php } ?>>
                                                            <td width="25%" align="center" style="color:<?php echo $color; ?>" ><?php echo $finalstandardlist[$z][key($finalstandardlist[$z])][0]['num'.$cnt]; ?></td>
                                                            <td width="75%" ><?php  echo $new = htmlspecialchars($finalstandardlist[$z][key($finalstandardlist[$z])][1]['content'.$cnt], ENT_QUOTES); ?></td> 
                                                    </tr>
                                                    <?php
                                                }                                               
						
					}
					$unorderedlist[]=$unolist;					
					unset($unolist);
				?>
			</table>
		<?php 
		}
        }
		?>
            <?php if($comstatus1 == 0){?>
                <p style="font-family:Arial;" >Not to be Addressed in current Schedule </p>  
             <?php } ?>  
            <div style="page-break-before: always;">&nbsp;</div>
            <?php
        }
        else{ 
            ?>
            <p style="font-family:Arial;" >Not Addressed in current Schedule </p>  <?php 
        }
        
        /*----- To be Addressed on current shuedule---------*/
        ?> <p style="font-weight:bold;font-family:Arial;font-size:50px" >Standard to be Addressed based on Current Schedule</p> <?php
        if(sizeof($qryproductdetuncompleted)!=0){
             if($id[1]==0){
                while($rowqry2 = $qryproductdetuncompleted1->fetch_assoc())
                {
                extract($rowqry2);
                  $productname2[]=$prodctname2;
                  $productid2[]=$assetid2;
                }
             }
             
             else{
                 for($b=0;$b<sizeof($qryproductdetuncompleted);$b++){
                     $qry = $ObjDB->QueryObject("select a.fld_id as mid, b.fld_prd_name as prodctname2, b.fld_prd_asset_id as assetid2 
                                                                from itc_module_master AS a
                                                                left join itc_correlation_products as b on a.fld_asset_id=b.fld_prd_id 
                                                                WHERE a.fld_id='".$qryproductdetuncompleted[$b]."' AND a.fld_delstatus='0'");
                     while($rowqrya = $qry->fetch_assoc())
                    {
                    extract($rowqrya);
                      $productname2[]=$prodctname2;
                      $productid2[]=$assetid2;
                    }
                   }                   
             }
            $productarray2=array();
            $productrel2=array();

            for($h=0;$h<sizeof($productid2);$h++)
            {               
                $string = file_get_contents("../correlation/products/".$productid1[$h].".xml");
                $stddocs = array();

                $doc = new DOMDocument();
                $doc->loadXML($string);
                $xpath = new DOMXpath($doc);
                $items = $xpath->query("//itm");
                $salign2 = array();
                if($items->length > 0) {
                        $i = 0;
                        foreach($items as $item)
                        {
                                $guid = $item->getAttribute('guid');
                                $rel= $item->getAttribute('rel');
                                $salign2[$i] = $guid;
                                $prdctrel[$i]=$rel;				
                                $i++;
                        }
                }

                $productarray2[]=array($productname2[$h]=>$salign2);
                $productrel2[]=array($productname2[$h]=>$prdctrel);
           }
            
                $comstatus =0;        
                $unolist=array();
                $olist2=array();
                if(sizeof($productid2)!=0){
                    for($z=0;$z<sizeof($finalstandardlist);$z++)
                    { 
            ?>
                    <p style="font-family:Arial;color:#606060;font-size:40px;font-weight:bold" ><?php echo key($finalstandardlist[$z]); ?> </p>
                    
                    <table width="100%" border="0.5" cellpadding="8">
                            <thead>
                                    <tr style="background-color:#00cc00" > 
                                            <td align="center" width="25%" ></td> 
                                            <td  width="75%" style="color:#FFF;font-weight:bold;"><?php echo key($finalstandardlist[$z]); ?><br/><?php echo $statename."|".$standardname ?></td> 
                                    </tr>
                            </thead> 
                            <?php 
                               
                                    for($c=0;$c<sizeof($finalstandardlist[$z][key($finalstandardlist[$z])][0]);$c++)
                                    {

                                            $cnt=0;	
                                            $cnt=$c+1;
                                            $prdctname2='';
                                            $fontcolorcnt=0;
                                            for($g=0;$g<sizeof($productarray2);$g++)
                                            {
                                                $comstatus =1;

                                                    $key = array_search($finalstandardlist[$z][key($finalstandardlist[$z])][2]['guid'.$cnt],$productarray2[$g][key($productarray2[$g])]);
                                                    if($key!='')
                                                            {

                                                                    $prdctname2.="<li>".key($productarray2[$g])."</li>";

                                                                    $fontcolorcnt++;
                                                                    if($finalstandardlist[$z][key($finalstandardlist[$z])][0]['num'.$cnt]!='')
                                                                    {
                                                                      $olist2[]= array(key($productarray2[$g])=>array("num"=>$finalstandardlist[$z][key($finalstandardlist[$z])][0]['num'.$cnt],"content"=>$finalstandardlist[$z][key($finalstandardlist[$z])][1]['content'.$cnt],"guid"=>$finalstandardlist[$z][key($finalstandardlist[$z])][2]['guid'.$cnt],"standard"=> key($finalstandardlist[$z])));
                                                                    }
                                                            }
                                            }                                            
                                            if($fontcolorcnt==0)
                                            {
                                                    $color="#606060";
                                                    $unolist[]= $cnt;

                                            }
                                            else{
                                                    $color="#000000"; 
                                            }                                            
                                                if($prdctname2!=''){
                                                    ?>
                                                    <tr nobr="true"  <?php if($finalstandardlist[$z][key($finalstandardlist[$z])][0]['num'.$cnt]=='')  {?> style="background-color:#606060;" <?php } ?>>
                                                            <td width="25%" align="center" style="color:<?php echo $color; ?>" ><?php echo $finalstandardlist[$z][key($finalstandardlist[$z])][0]['num'.$cnt]; ?></td>
                                                            <td width="75%" ><?php  echo $new = htmlspecialchars($finalstandardlist[$z][key($finalstandardlist[$z])][1]['content'.$cnt], ENT_QUOTES); ?></td>
                                                    </tr>
                                                    <?php
                                                }                                                
                                    }
                                    $unorderedlist[]=$unolist;                                    
                                    unset($unolist);
                            ?>
                    </table>
            <?php 
            }
        }
        ?>
         <?php if($comstatus == 0){?>
                     <p style="font-family:Arial;" >Not to be Addressed in current Schedule </p>  
         <?php } ?>                       
        <div style="page-break-before: always;">&nbsp;</div>
        <?php
    }
    else{ 
        ?>
        <p style="font-family:Arial;" >Not to be Addressed in current Schedule </p>  <?php 
    }
        
        /*----- Not Addressed ---------*/
        if(sizeof($qryproductdetcom)!=0){
            if($id[1]==0){
                while($rowqry = $qryproductdetcom1->fetch_assoc())
               {
               extract($rowqry);
                 $productname[]=$prodctname;
                 $productid[]=$assetid;
               }
            }
            else{
                for($c=0;$c<sizeof($qryproductdetcom);$c++){
                     $qry = $ObjDB->QueryObject("select a.fld_id as mid, b.fld_prd_name as prodctname, b.fld_prd_asset_id as assetid 
                                                                from itc_module_master AS a
                                                                left join itc_correlation_products as b on a.fld_asset_id=b.fld_prd_id 
                                                                WHERE a.fld_id='".$qryproductdetcom[$c]."' AND a.fld_delstatus='0'");
                     while($rowqrya = $qry->fetch_assoc())
                    {
                    extract($rowqrya);
                      $productname[]=$prodctname;
                      $productid[]=$assetid;
                    }
                   }
            }
            $productarray=array();
            $productrel=array();

            for($h=0;$h<sizeof($productid);$h++)
            {                    
                    $string = file_get_contents("../correlation/products/".$productid[$h].".xml");
                    $stddocs = array();

                    $doc = new DOMDocument();
                    $doc->loadXML($string);
                    $xpath = new DOMXpath($doc);
                    $items = $xpath->query("//itm");
                    $salign = array();
                    if($items->length > 0) {
                            $i = 0;
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
            $unolist=array();
            $olist1=array();
                for($z=0;$z<sizeof($finalstandardlist);$z++)
			{ 
		
                            for($c=0;$c<sizeof($finalstandardlist[$z][key($finalstandardlist[$z])][0]);$c++)
                            {
                                    $cnt=0;	
                                    $cnt=$c+1;
                                    $prdctname='';
                                    $fontcolorcnt=0;
                                    for($g=0;$g<sizeof($productarray);$g++)
                                    {
                                            $key = array_search($finalstandardlist[$z][key($finalstandardlist[$z])][2]['guid'.$cnt],$productarray[$g][key($productarray[$g])]);
                                                    if($key!='')
                                                    {
                                                            $prdctname.="<li>".key($productarray[$g])."</li>";
                                                            $fontcolorcnt++;
                                                            if($finalstandardlist[$z][key($finalstandardlist[$z])][0]['num'.$cnt]!='')
                                                            {
                                                              $olist1[]= array(key($productarray[$g])=>array("num"=>$finalstandardlist[$z][key($finalstandardlist[$z])][0]['num'.$cnt],"content"=>$finalstandardlist[$z][key($finalstandardlist[$z])][1]['content'.$cnt],"guid"=>$finalstandardlist[$z][key($finalstandardlist[$z])][2]['guid'.$cnt],"standard"=> key($finalstandardlist[$z])));
                                                            }
                                                    }
                                    }
                                    if($fontcolorcnt==0)
                                    {
                                            $color="#606060";
                                            $unolist[]= $cnt;

                                    }
                                    else{
                                            $color="#000000"; 
                                    }
                            ?>

                    <?php	
                            }
                            $unorderedlist[]=$unolist;                            
                            unset($unolist);
				
		}
		?>
                
                <p style="font-weight:bold;font-family:Arial;font-size:50px" >Standard not Addressed based on Current Schedule</p>
		<?php
			for($z=0;$z<sizeof($finalstandardlist);$z++)
			{ 
		?>
			<p style="font-family:Arial;color:#606060;font-size:40px;font-weight:bold" ><?php echo key($finalstandardlist[$z]); ?></p>
			<table width="100%"  border="0.5"  cellpadding="8">
				<thead>
					<tr style="background-color:#00cc00" > 
						<td align="center" width="25%" ></td> 
						<td width="75%" style="color:#FFF;font-weight:bold;"><?php echo key($finalstandardlist[$z]); ?><br/><?php echo $statename."|".$standardname ?></td> 
					</tr>
				</thead>  
			<?php 
				for($c=0;$c<sizeof($unorderedlist[$z]);$c++)
				{
					$cnt = 0;
					$nextcnt = 0;	
					$cnt = $unorderedlist[$z][$c];
					$nextcnt = $c+1;
					$headingcontainspoint=true;
					if($finalstandardlist[$z][key($finalstandardlist[$z])][0]['num'.$unorderedlist[$z][$nextcnt]]=='')
					{
						$headingcontainspoint=false; 
					}
					
					if($finalstandardlist[$z][key($finalstandardlist[$z])][0]['num'.$cnt]!='')  {
			?>
					<tr nobr="true" >       
						<td  align="center" width="25%"><?php echo $finalstandardlist[$z][key($finalstandardlist[$z])][0]['num'.$cnt]; ?></td>
						<td  width="75%" ><?php echo htmlspecialchars($finalstandardlist[$z][key($finalstandardlist[$z])][1]['content'.$cnt]);?></td>
					</tr>
				<?php 		
					} else if($finalstandardlist[$z][key($finalstandardlist[$z])][0]['num'.$cnt]=='' and $finalstandardlist[$z][key($finalstandardlist[$z])][1]['content'.$cnt]!='' and $headingcontainspoint ){ 
				?>
					<tr nobr="true" style="background-color:#606060">   
						<td  align="center" width="25%"></td>
                                                <td  width="75%"><?php echo htmlspecialchars($finalstandardlist[$z][key($finalstandardlist[$z])][1]['content'.$cnt]); ?></td>
					</tr>
				<?php 
					} 
				} 
			?>
		</table>
		<?php
			}
        }


	
       
   


