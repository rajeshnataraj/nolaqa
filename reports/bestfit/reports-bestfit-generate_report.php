<?php
set_time_limit(0);
error_reporting(0);

ini_set('memory_limit', '-1');
@include("sessioncheck.php");

$id=isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

$checbox=$ObjDB->SelectSingleValue("SELECT fld_flag 
									FROM  itc_bestfit_report_data 
									WHERE fld_id='".$id."'");

$gradname=array();
    $standardids=array();
$pid=array();
$productid=array();
    $productname=array();
$gstandard=array();

    
/**for purpse of calcuating standards address summary **/
	function getpercentage($standard,$proid,$statename,$repid,$type)
	{
		$standardids=$standard;
		$productid=$proid;
		$allstandardsguid=array();
		$bencharray=array();
		$stddocs = array();
		$finalbench=array();
		$std = array();
		
// To get the guid of grades for the selected standards
		for($g=0;$g<sizeof($standardids);$g++)
		{
			
			$string = file_get_contents("../correlation/standards/".$standardids[$g].".xml");			
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
			$grades = $xpath->query("//itm[@type='".$typ."']");
			$guids = $xpath->query("//itm[@type='".$typ."']//itm[@type='standard']/*");
                       
			// To get top standard and its child in an array
			$stdcnt = 0;
			$h=0;			
			
			foreach($guids as $guid)
			{
				$gud = $guid->getAttribute('guid')."</br>";
				$h++;
			}
	
			foreach($grades as $grade)
			{
                            
				$nodes = $xpath->query("itm[@type='standard']",$grade);
                               
				$nodecnt = $nodecnt + $nodes->length;                                
                        
				if($nodes->length > 0) { 	
					foreach ($nodes as $node) {
						
						$guid = $node->getAttribute('guid');
                                                
						$nodesinner = $xpath->query(".//itm[@type='standard']",$node);                                     
						$nodecnt = $nodecnt + $nodesinner->length;
						if($nodesinner->length > 0) { 
							$j=0;
							foreach ($nodesinner as $nodeinner) {								
								if($nodeinner->getAttribute('guid') != '') {
									$std[$j] = $nodeinner->getAttribute('guid');
									$j++;
									$stdcnt++;
								}
							}
							$stdproduct[$guid] = $std;
							unset($std);
						} //if ends
						$stdcnt++;
					}// foreach node ends
				} // if ends grades
			} // foreach grades ends		
			
			$allstandardsguid[$standardids[$g]]=$stdproduct;
			unset($stdproduct);
		
			foreach($grades as $grade)
			{
                            
				$nodes = $xpath->query("//itm[@type='standard'][not(descendant::itm[@type='standard'])] ",$grade);
				if($nodes->length > 0) { 	
					foreach ($nodes as $node) {
                                            $guid = $node->getAttribute('guid');
                                            $bencharray[] = $guid;
					}
				}
			} // foreach grades ends			
				
			$finalbench[]=$bencharray;
			$benchindivdualcount[]=sizeof($bencharray);
			unset($bencharray);
		} // for ends	

		
		
// To get all the standards
		$nodes = $xpath->query("//itm[@type='standard']");
		$nodecnt = $nodecnt + $nodes->length;
		
		if($nodes->length > 0) { 
			$j = 0;
			foreach ($nodes as $node) {
				$tempstd[$j] = $node->getAttribute('guid');
				$j++;
			}
		}
		
 // To get the alignment for the title set selected
		$productassets=array();			
		for($h=0;$h<sizeof($productid);$h++)
		{
			// To get alignment for the particular asset C4128B8C-3B53-11E0-B042-495E9DFF4B22
			
			$string = file_get_contents("../correlation/products/".$productid[$h].".xml");	
			$stddocs = array();			
			
			$doc = new DOMDocument();
			$doc->loadXML($string);
			
			$xpath = new DOMXpath($doc);
			$items = $xpath->query("//itm");
			
			$psalign = array();
			if($items->length > 0) {
				$i = 0;
				foreach($items as $item)
				{
					$guid = $item->getAttribute('guid');
					$psalign[$i] = $guid;
					$i++;
				}
			}
			$productassets[$productid[$h]]=$psalign;
			unset($psalign);
		} // for ends
		
		
	
// To calculate the standard/benchmark addressed summary 		
		$tempbstdarray = array();
		for($k=0;$k<sizeof($standardids);$k++)
		{
			$totalalign = 0;
			$tempstdarray = array();
		    	$mainstdarray = array();
			for($p=0;$p<sizeof($productid);$p++)
			{
				$stdproduct=$allstandardsguid[$standardids[$k]];				
				$psalign=$productassets[$productid[$p]];                               
				foreach($stdproduct as $key => $val) {
                                    
					$result = array_intersect($stdproduct[$key],$psalign);                                        
					$tempstdarray = array_merge($tempstdarray, $result);
					if(sizeof($result) > 0) {
						$totalalign++;
						$resultarray[]=array_values($result);                                                
						if(sizeof($stdproduct[$key]) > 0 ) {
							$mainstdarray[] = $key;
						}
					}
					unset($result);
				}
                                
				unset($stdproduct);
				unset($psalign);	
			} // for ends                        
			$finalresultarray[]=$resultarray;  
                       
			for($t=0;$t<sizeof($resultarray);$t++)
			{
				for($i=0;$i<sizeof($resultarray[$t]);$i++)
				{
					$newarray[]=$resultarray[$t][$i];
				}
			}			 
			if(sizeof($newarray)!=0)
			{
				$percent= sizeof(array_unique($tempstdarray)) + sizeof(array_unique($mainstdarray)); //sizeof(array_values(array_unique($newarray)))+$totalalign;

				$uni=array_values(array_unique($newarray));
				$unipoint=sizeof(array_values(array_unique($newarray)));
			}
			else
			{
				$percent=0;
				$uni=array(0);
				$unipoint=0;
			}
			
			$percentage[]=$percent;
			$uniquearray[]=$uni;
			$uniquepoints[]=$unipoint;
			
			unset($newarray);
			unset($resultarray);
			unset($percent);
			unset($uni);
			unset($unipoints);
		} // for ends
		
// To calculate the benchmarks addressed summary 
		$tempbmarr = array();
		for($benchst=0;$benchst<sizeof($standardids);$benchst++)
		{
			$c=0;
			for($pr=0;$pr<sizeof($productid);$pr++)
			{
				$bpsalign=$productassets[$productid[$pr]];				
				for($fin=0;$fin<sizeof($finalbench[$benchst]);$fin++)
				{
					$result = array_search($finalbench[$benchst][$fin],$bpsalign);
					if(($result != '') and (!in_array($finalbench[$benchst][$fin],$tempbmarr)))
					{
						$tempbmarr[] = $finalbench[$benchst][$fin];
						$c++;
					}
					
				}
			}
			$benpercenatge[]=$c;			
			unset($benresultarray);
			unset($bpsalign);
			unset($tempbmarr);
		}
	        
		if($type==1){
			return $percentage;
		}
		else if($type==2){
			return $benpercenatge;
		}
		else if($type==3){
			return $benchindivdualcount;
		}
		else if($type==4){
			return $uniquearray;
		}
		else if($type==5){
			return $uniquepoints;
		}
	}    //ends the function name getpercentage()

 	$qry=$ObjDB->QueryObject("SELECT b.fld_name as state, a.fld_std_body, a.fld_doc_name as docuname,a.fld_doc_id as docid
                                        FROM itc_bestfit_rpt_doc_mapping AS a
                                        LEFT JOIN itc_standards_bodies AS b ON b.fld_id=a.fld_std_body
                                        WHERE a.fld_best_id='".$id."'AND a.fld_delstatus='0'");

	if($qry->num_rows > 0){
		$rowqry = $qry->fetch_assoc();
		extract($rowqry);

		$fld_std_body=$std_body;
		$docid = $docid;
	}

/*For grade*/	
        
	$qryforgrade=$ObjDB->QueryObject("SELECT fld_guid as guid,fld_grade as grad 
										FROM itc_bestfit_rpt_std_grades 
										WHERE fld_rpt_data_id='".$id."' AND fld_delstatus='0'");
	$grdes='';
	$b=0;
	if($qryforgrade->num_rows > 0){
		while($rowqryforgrade = $qryforgrade->fetch_assoc())
		{
			extract($rowqryforgrade);
			$standardids[]=$guid;
			$gradname[]=$grad;
			$com = ",";
			$b++;
			if($b==$qryforgrade->num_rows)
			{
				$com="";
			}
			$grdes.=$grad.$com;

		}
	}

	$qryforproducts=$ObjDB->QueryObject("SELECT b.fld_prd_name AS prodctname, b.fld_prd_id AS prductid, b.fld_prd_asset_id AS assetid, a.fld_notitle as notitles,
                                                a.fld_maxrecom as maxrecomm, a.fld_totcombi as totcombi
                                                FROM itc_bestfit_rpt_products a
                                                LEFT JOIN itc_correlation_products b ON b.fld_id=a.fld_prd_id
                                                WHERE a.fld_rpt_data_id='".$id."' AND a.fld_delstatus='0' AND b.fld_prd_name<>''
                                                GROUP BY b.fld_prd_asset_id ORDER BY b.fld_prd_name ASC");

	if($qryforproducts->num_rows > 0){
		while($rowqryforproducts = $qryforproducts->fetch_assoc())
		{
            extract($rowqryforproducts);
            $pid[]=$prductid;
            $productname[]=$prodctname;
            $productid[]=$assetid;
            
		} 
	}

	$finalstandardlist=array();
	$arraynum=array();
	$arraycontent=array();
	$arrayguids=array();
	$unorderedlist=array();
	$orderedlist=array();
	$standardsname=array();
	$standardindividualcount=array();

	   for($x=0;$x<sizeof($standardids);$x++)
	{ 		
		$string = file_get_contents("../correlation/standards/".$standardids[$x].".xml");	
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
	} // ends of sizeof($standardids)in $x

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
		
	} // ends of sizeof($productid) in $h
        
 /** Percentage calculation for multiple standards with products **/

	$subid=$ObjDB->QueryObject("SELECT fld_sub_id as subidd FROM itc_bestfit_rpt_std_grades
 									WHERE fld_rpt_data_id='".$id."' AND fld_delstatus='0' GROUP BY fld_sub_id");
	// assigning the empty array variable	
	$common=array();
	$sele_common=array();    
	$indual_common=array();  
	$indper_common=array(); 
	 if($subid->num_rows > 0){ 
                while($docrow = $subid->fetch_assoc()){
                    extract($docrow);
                    $gstandard=array();
                    $seprt_common=array();
                    $Indsep_common=array();
                    $guid=$ObjDB->QueryObject("SELECT fld_guid as guidd FROM itc_bestfit_rpt_std_grades 
                                                   WHERE fld_rpt_data_id='".$id."' AND fld_sub_id='".$subidd."' AND fld_delstatus='0'");
                    if($guid->num_rows > 0){ 
                        while($docrow1 = $guid->fetch_assoc()){
                            extract($docrow1);
                            $gstandard[]=$guidd;
                        }
                    } 


                    

   // percentage for product titles
                    $lenprd=count($productname);
                    for($o=0;$o<$lenprd;$o++){

                        $percenatageforstdinv=getpercentage($gstandard,array($productid[$o]),$fld_std_body,$id,1);
                        $unipoit=getpercentage($gstandard,array($productid[$o]),$fld_std_body,$id,5);
                        $uniarray=getpercentage($gstandard,array($productid[$o]),$fld_std_body,$id,4);

					$perceforsel=array();

                        for($y=0;$y<sizeof($percenatageforstdinv);$y++)
                        {
                            $perceforsel[]=round(($percenatageforstdinv[$y]/$standardindividualcount[$y])*100); 
                        } 

                        $percen=array_sum($perceforsel);

                       
                        $common[]=$percen."~".$subidd."~".$productname[$o];   // total group of subject id
                       
                        $sele_common[]=$percen; 
                        $Indsep_common[]=$percen;

                       
                   }  // end of sizeof($productname) in $o          
 
               }   //end of while loop $docrow
          
          }  // ends of if $subid->num_rows

/* collect the products for all subjects print_r($common);  */
/* only percentage for product basis is applied here */

	arsort($sele_common);
	
	$maxs = array_keys($sele_common, max($sele_common));
	
	$exp_elem = explode("~",$common[$maxs[0]]); 
	
 if($checbox==0){     
            include "Combinatorics.php";
            $mycalc=new Combinatorics;
            $pronew =array();
            $combilist=array();
            $flag=0;

            $combwr=$mycalc->makeCombination(($productname),$notitles);
            $combwr = array_filter($combwr);
            foreach($combwr as $key=>$value){
                foreach ($value as $key2=>$value2){
                    if($flag==0)
                        $newval=$newval.$value2;
                    else
                        $newval=$newval.'@'.$value2;
                        $flag=1;
                } 
            $newval=$newval.'~';
            $flag=0;
            }
          $selecombi=ltrim($newval); 
       }  // ends of  if($checbox==0)
       else {  

            $req_produt=array();
            $reqprod_detail=$ObjDB->QueryObject("SELECT fld_rpt_data_id as rptid, fld_product_id as prodid,CONCAT(fld_product_sys_id,'~',fld_type) as list10, 
                                                                                    fld_req_notitle as notitles, fld_req_maxrecom as maxrecomm, fld_req_totcombi as totcombi
                                                                                    FROM itc_bestfit_rpt_reqproducts
                                                                                    WHERE fld_rpt_data_id='".$id."' AND fld_delstatus='0'");

            if($reqprod_detail->num_rows > 0){
                while($qryforreproductdetrow = $reqprod_detail->fetch_assoc()){
                    extract($qryforreproductdetrow);
                    $req_produt[]=array("notitle"=>$notitles,"producid"=>$prodid,"maxrecom"=>$maxrecomm,"id"=>$list10,"totcom"=>$totcombi);
                }
            }
            $pro=array();		 
            for($d=0; $d<sizeof($req_produt);$d++)
            {
                $pro[]=$req_produt[$d]['id'];
            }
            $cnt=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM  itc_bestfit_rpt_reqproducts 
                                                                            WHERE fld_rpt_data_id='".$id."'  AND fld_delstatus='0'");
            $sel_produt=array();
            $sel_sysid=$ObjDB->QueryObject("SELECT CONCAT(a.fld_product_system_id,'~',a.fld_type) as list9 From itc_bestfit_rpt_products as a        
                                                                        WHERE a.fld_rpt_data_id='".$id."' AND a.fld_delstatus=0  
                                                                        AND a.fld_product_id NOT IN (SELECT  b.fld_product_id 
                                                                        From itc_bestfit_rpt_reqproducts as b
                                                                        WHERE b.fld_rpt_data_id ='".$id."' AND b.fld_delstatus = 0)");

            if($sel_sysid->num_rows > 0){
                while($qryforsysidrow = $sel_sysid->fetch_assoc()){
                    extract($qryforsysidrow); 
                    $sel_produt[]=array("id"=>$list9);
                }
            }
            $prod=array();		 
            for($e=0; $e<sizeof($sel_produt);$e++)
            {
                 $prod[]=$sel_produt[$e]['id'];
            } 
 /* combinations for required products */
           $data =array();
            $arr=array();
            foreach($prod as $key => $val2){ 
            array_push($arr,$val2);
            }
            $t=sizeof($arr[0]);
            $rw=$notitles;
            $tcom=$totcombi;
            $length=$cnt; 
            $r=($rw-$length);
            $n = sizeof($arr)/$t;
            $parr = array();
            function printCombination($arr, $n, $r) {
                $data[$r];
                combinationUtil($arr, $data, 0, $n-1, 0, $r);
            }
            function combinationUtil($arr, $data, $start, $end, $index, $r) {
                global $parr;
                if($index == $r)
                {
                    $newval = array();
                for ($j=0; $j<$r; $j++)
                {
                    array_push($newval,$data[$j]);
                }
                    array_push($parr,$newval);
                    return $parr ;
                }
                for ($i=$start; $i<=$end && $end-$i+1 >= $r-$index; $i++)
                {
                    $data[$index] = $arr[$i];
                    combinationUtil($arr, $data, $i+1, $end, $index+1, $r);
                }
            }
            printCombination($arr, $n, $r);
            $list_pairset =array();
            $req=array();	
            foreach($pro as $key => $val1){ 
                array_push($req,$val1);
            } 
            $reqlength=count($req);
            for($cn=0;$cn<$tcom;$cn++)
            {
                $result=array_merge((array)$req,(array)$parr[$cn]);
                array_push($list_pairset,$result);
            }
       }   // ends of else part 
                 
 /** Get the grade guids of highest percentage standard **/       

 $qry_standard=array();
					$per_guid=$ObjDB->QueryObject("SELECT fld_guid as guidd FROM itc_bestfit_rpt_std_grades 
                                                        WHERE fld_rpt_data_id='".$id."' AND fld_sub_id='".$exp_elem[1]."' AND fld_delstatus='0'");
					if($per_guid->num_rows > 0){ 
						while($gr_docqry = $per_guid->fetch_assoc()){
							extract($gr_docqry);
							$qry_standard[]=$guidd;
						}
					}   

/** standardindividualcount calculation for max percentage standard **/	
	
		$standardindividualcount=array();
                $stdlen=count($qry_standard);
	   for($totalcnt=0;$totalcnt<$stdlen;$totalcnt++)
	{
 		
		$string = file_get_contents("../correlation/standards/".$qry_standard[$totalcnt].".xml");	
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


		$guids = $xpath->query("//itm[@type='".$typ."']//itm[@type='standard']");
		
		$standardindividualcount[]=$guids->length;
               
		
	} // ends of sizeof($qry_standard)in $totalcnt



/** for best fit highest recommendation calculation **/


if($checbox==0){

		
                               $exppro=explode("~",$selecombi);
                               $len=count($exppro);

		for($v=0;$v<$len-1;$v++){

				$ex=explode('@',$exppro[$v]); 
                                                                            
										$prd_assetid=array();
                                                                                $lenprd=count($ex);
							for($res=0;$res<$lenprd;$res++){
							
								$prd_assetid[]=$ObjDB->SelectSingleValue("SELECT fld_prd_asset_id FROM itc_correlation_products 
																					WHERE fld_prd_name='".$ex[$res]."'");	
								$select_prdassetid=$ObjDB->SelectSingleValue("SELECT fld_product_id FROM itc_bestfit_rpt_products
																					WHERE fld_product_name='".$ex[$res]."' 
																					AND fld_rpt_data_id='".$id."'");

								

							} //  end of for $res
										
										$percenatageforstd=getpercentage($qry_standard,$prd_assetid,$fld_std_body,$id,1); 
                                        $percenatageforben=getpercentage($qry_standard,$prd_assetid,$fld_std_body,$id,2);

										$depth= array();
										$depth= array_sum($percenatageforstd) + array_sum($percenatageforben);
                                                                               
										$finalpercentage=array();
                                                                                $lenstd=count($qry_standard);
										for($per=0;$per<$lenstd;$per++)
										{          
											if($percenatageforstd[$per]!=0)
											{
												$finalpercentage[]=round(($percenatageforstd[$per]/$standardindividualcount[$per])*100); 
											}
											else
											{
												$finalpercentage[]=0;
											} 
										}  


$breadth[]=array_sum($finalpercentage);



}  // end of for $v

arsort($breadth);

		 
} // end of if checkbox

else{

		$sellistpairset = count($list_pairset);

				$exppro = array();

					for($abc=0;$abc<$sellistpairset;$abc++){

					$single_mprod=array();

					for($ac=0;$ac<sizeof($list_pairset[$abc]);$ac++){

						$recomb=explode("~",$list_pairset[$abc][$ac]);
						
						$qryforcombn_set=$ObjDB->SelectSingleValue("SELECT fld_prd_name AS prodctname FROM itc_correlation_products 
						                                                WHERE fld_prd_sys_id='".$recomb[0]."' AND fld_prd_type='".$recomb[1]."'");
						$single_mprod[] = $qryforcombn_set;

					} // end of for $ac
					 
					$comma_separated = implode("@", $single_mprod); 
					$exppro[] = $comma_separated;
				} // end of for $abc


/** Arranging  required products combinations from highest to lowest **/

				
   					for($v=0;$v<sizeof($exppro);$v++){
				
			                    $ex=explode('@',$exppro[$v]); 

			                    $prd_assetid=array();

			                        for($res=0;$res<sizeof($ex);$res++){

			                    $prd_assetid[]=$ObjDB->SelectSingleValue("SELECT fld_prd_asset_id FROM itc_correlation_products 
			                                                                                                                            WHERE fld_prd_name='".$ex[$res]."'");	
			                    $select_prdassetid=$ObjDB->SelectSingleValue("SELECT fld_product_id FROM itc_bestfit_rpt_products
			                                                                                                                            WHERE fld_product_name='".$ex[$res]."' 
			                                                                                                                            AND fld_rpt_data_id='".$id."'");


		

							} //  end of for $res

					$percenatageforstd=getpercentage($qry_standard,$prd_assetid,$fld_std_body,$id,1); 
                    $percenatageforben=getpercentage($qry_standard,$prd_assetid,$fld_std_body,$id,2);

                                                    $depth= array();
                                                    $depth= array_sum($percenatageforstd) + array_sum($percenatageforben);

                                                    $finalpercentage=array();

                                                    for($per=0;$per<sizeof($qry_standard);$per++)
                                                    {          
                                                            if($percenatageforstd[$per]!=0)
                                                            {
                                                                    $finalpercentage[]=round(($percenatageforstd[$per]/$standardindividualcount[$per])*100); 
                                                            }
                                                            else
                                                            {
                                                                    $finalpercentage[]=0;
                                                            } 
                                                    }  


$breadth[]=array_sum($finalpercentage);



}  // end of for $v

arsort($breadth);

} // else checkbox
     ?>
<script language="javascript" type="text/javascript">
    $('#bbasicstandardinfo').removeClass("active-first");
    $('#bselectproduct').removeClass("active-mid");
    $('#bgenerate').addClass("active-mid").parent().removeClass("dim");
    $('#bviewreport').removeClass("active-last");
    setTimeout('$("#example-basic").treetable({ expandable: true, clickableNodeNames:true })',3000);
</script>

<section data-type="2home" id="reports-bestfit-generate_report">
    <div class="container">
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Step 3:Generate Report</p>
				<p class="dialogSubTitleLight">Using the fields below, generate your report. Press &ldquo;Generate&rdquo; to continue.</p>
                  <div class="row rowspacer"></div>
            </div>
        </div>
        
<div class='row'>
  <div class='twelve columns formBase'>
    <div class='row'>
       <div class='eleven columns centered insideForm'>
         <div class="row rowspacer">
          <table id="example-basic" class="table">
                          <?php     static $f=0;    ?>
                    <thead>
                    <tr>
                        <th>Best Fit Report<br/>
                              <br/>
						<?php

							$sel_docgid = $ObjDB->SelectSingleValue("SELECT fld_sub_guid FROM itc_correlation_doc_subject 
																		WHERE fld_id='".$exp_elem[1]."'");

							$seleprod_detail=$ObjDB->QueryObject("SELECT  a.fld_doc_name as docuname,b.fld_name as state FROM itc_bestfit_rpt_doc_mapping AS a
																	LEFT JOIN itc_standards_bodies AS b ON b.fld_id = a.fld_std_body
																	WHERE a.fld_doc_id='".$sel_docgid."' AND a.fld_best_id='".$id."' AND a.fld_delstatus='0'");
							if($seleprod_detail->num_rows > 0){ 
								$det=0;
								while($result = $seleprod_detail->fetch_assoc()){
									extract($result);
									$details=$state." | ". $docuname;
									if($det=='0')
									{
										echo $details;
									}
								}
							}  

						?>
                   		</th>
                    </tr>
                    </thead>
                         
                    <tbody>
				
		<?php    

                                     
				for($m=0;$m<$maxrecomm;$m++) {

                                    $disp_productasset=array();
			   ?>     
                        <tr data-tt-id="<?php echo $f; ?>">
                        <td class="progressUnitTitle progressHeaderFill">

                    <table>
                          
						<tr>
                        
	                        <td>Recommendation <?php echo $m+1;?></td>
	                        <td>Breadth:<?php echo "&nbsp&nbsp;";

							
$maxreqproduct= array_keys($breadth);
							
                                                 $ex=explode('@',$exppro[$maxreqproduct[$m]]); 

										$prd_assetid=array();

							for($res=0;$res<sizeof($ex);$res++){


										$prd_assetid[]=$ObjDB->SelectSingleValue("SELECT fld_prd_asset_id FROM itc_correlation_products 
																					WHERE fld_prd_name='".$ex[$res]."'");	
										$select_prdassetid=$ObjDB->SelectSingleValue("SELECT fld_product_id FROM itc_bestfit_rpt_products
																					WHERE fld_product_name='".$ex[$res]."' 
																					AND fld_rpt_data_id='".$id."'");
										$disp_productasset[] = $select_prdassetid;
							
								
							} //  end of for $res
							
										$percenatageforstd=getpercentage($qry_standard,$prd_assetid,$fld_std_body,$id,1); 
                                        $percenatageforben=getpercentage($qry_standard,$prd_assetid,$fld_std_body,$id,2);

										$depth= array();
										$depth= array_sum($percenatageforstd) + array_sum($percenatageforben);
                                                                               
										$finalpercentage=array();
                                                                                
										for($per=0;$per<sizeof($qry_standard);$per++)
										{          
											if($percenatageforstd[$per]!=0)
											{
												$finalpercentage[]=round(($percenatageforstd[$per]/$standardindividualcount[$per])*100); 
											}
											else
											{
												$finalpercentage[]=0;
											} 
										}      

								echo $breadth[$maxreqproduct[$m]];

                                                                ?>
							</td>
	                        <td>Depth:<?php echo "&nbsp&nbsp;";
						    	echo $depth; ?>
							</td>
	                        <td><input type="button" id="generate" value="Generate" onclick='fn_viewreport(<?php echo json_encode($disp_productasset);?>,<?php echo $id; ?>,<?php echo $maxrecomm; ?>,<?php echo $checbox; ?>,<?php echo $notitles; ?>,<?php echo $totcombi; ?>,"<?php echo $sel_docgid; ?>")'/></td>
						</tr>
                                     
                    </table>
                        </td>
                        </tr>

                         <tr data-tt-parent-id="<?php echo $f?>" data-tt-id="<?php echo $f?>.1" >
                            <td class="progressIplTitle">Grade</td>
                        <?php

                            $qryforgra=$ObjDB->QueryObject("SELECT a.fld_sub_guid AS standardid,b.fld_grade as grad
                                                                                    FROM itc_correlation_doc_subject as a 
                                                                                    LEFT JOIN itc_bestfit_rpt_std_grades AS b on a.fld_id=b.fld_sub_id
                                                                                    WHERE b.fld_rpt_data_id='".$id."' AND a.fld_id='".$exp_elem[1]."' AND 											    b.fld_delstatus='0'");
 				$j=1;
                             	$a=0;
                                                          
                             while($rowqryforgra = $qryforgra->fetch_assoc())
							{
			        			extract($rowqryforgra);    ?>
                        <tr data-tt-parent-id="<?php echo $f?>.1" data-tt-id="<?php echo $f?>.1.<?php echo $j;?>" >
                                 <td class="progressIplTitle"><?php  echo $grad; ?><?php echo "&nbsp&nbsp;"; 
					  
				 echo $finalpercentage[$a];
  				 echo "&nbsp"; ?>%</td>
                        </tr>
                        </tr>
                        <?php  $j++;
                               $a++;  }   ?>


                        <tr data-tt-parent-id="<?php echo $f?>" data-tt-id="<?php echo $f?>.2" >
                         <td class="progressIplTitle">Product</td>
                        <?php

						for($q=0;$q<sizeof($ex);$q++)
						{
							$co_productname = $ex[$q]; 	?>
							<tr data-tt-parent-id="<?php echo $f?>.2" data-tt-id="<?php echo $f?>.2.<?php echo $o;?>" ><td class="progressIplTitle"> 
							<?php 

							echo $co_productname."&nbsp&nbsp;"."(";
							$perce=array();
							$find_pid=$ObjDB->SelectSingleValue("SELECT fld_prd_asset_id FROM itc_correlation_products 
																	WHERE fld_prd_name='".$co_productname."'");							
							$percenatageforstdinv=getpercentage($qry_standard,array($find_pid),$fld_std_body,$id,1);
							$unipoit=getpercentage($qry_standard,array($find_pid),$fld_std_body,$id,5);
							$uniarray=getpercentage($qry_standard,array($find_pid),$fld_std_body,$id,4);
							for($y=0;$y<sizeof($percenatageforstdinv);$y++){
							$perce[]=round(($percenatageforstdinv[$y]/$standardindividualcount[$y])*100);
							} 
							echo array_sum($perce)."%)"."&nbsp&nbsp;"."(".array_sum($unipoit)."unique)";?> </td></tr><?php
						} unset($perce);
						   unset($unipoit);
					
					?>
                        </tr>


                        <?php                
   $f++; 
				}  // ends of for loop $i < $maxrec
                        
                         ?>

                      </tbody>
            </table>
              
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>       
</section>
<?php
	@include("footer.php");
