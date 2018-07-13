<?php
error_reporting(0);


$method=$_REQUEST; 
@include("table.class.php");
@include("comm_func.php");

$selereq = isset($method['selereq']) ? $method['selereq'] : 0;
$id = isset($method['id']) ? $method['id'] : 0;
$maxrecom = isset($method['maxrecom']) ? $method['maxrecom'] : 0;
$chckbox = isset($method['chckbox']) ? $method['chckbox'] : 0;
$notitle= isset($method['notitles']) ? $method['notitles'] : 0;
$totcombi= isset($method['totcombi']) ? $method['totcombi'] : 0;
$docid=  isset($method['docid']) ? $method['docid'] : 0;
$uid = isset($method['uid']) ? $method['uid'] : 0;
$sessmasterprfid = isset($method['sessmasterprfid']) ? $method['sessmasterprfid'] : 0;
$url=$domainame;

require_once('graphgenerator/SVGGraph.php');

$oper = isset($method['oper']) ? $method['oper'] : '';
$gradname=array();
$standardids=array();
$pid=array();
$productid=array();
$productname=array();
	
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
			
			$string = file_get_contents($url."reports/correlation/standards/".$standardids[$g].".xml");				
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
			
			$string = file_get_contents($url."reports/correlation/products/".$productid[$h].".xml");	
			
		
			$stddocs = array();
			
			//$string = file_get_contents($url);	
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
		$tempstdarray = array();
		$mainstdarray = array();
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
			
			unset($percent);
			unset($newarray);
			unset($resultarray);
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
	}
        
	$colors=array("red","#5255a5","#066434","#603a96","#00539f","#00539f");
	$colorlight=array("#fdddce","#dddaee","#d1d8ce","#dcd6eb","#d1d7e5","#ccddee");
        
      	
          
        $qry=$ObjDB->QueryObject("SELECT a.fld_doc_name AS standardname, a.fld_doc_id AS standardid, a.fld_created_date, a.fld_std_body,
                                b.fld_name AS statename, c.fld_report_style AS style,c.fld_prepared_on as date 
                                FROM itc_bestfit_rpt_doc_mapping AS a
                                LEFT JOIN itc_standards_bodies AS b on a.fld_std_body=b.fld_id
                                LEFT JOIN itc_bestfit_report_data AS c on a.fld_best_id=c.fld_id
                                WHERE c.fld_id='".$id."' AND a.fld_doc_id='".$docid."'");
	
        if($qry->num_rows > 0){
           $rowqry = $qry->fetch_assoc();
		extract($rowqry);
                $standrdid=$standardid;
            
                
	}                
	 
	
	$colorstyle=$style-1;		
	$qryforgrade=$ObjDB->QueryObject("SELECT b.fld_guid as guid, b.fld_grade as grad,a.fld_sub_guid as standaid 
                                            FROM itc_correlation_doc_subject as a 
                                            LEFT JOIN itc_bestfit_rpt_std_grades AS b on a.fld_id=b.fld_sub_id
                                            WHERE fld_rpt_data_id='".$id."' AND fld_delstatus='0'");
        
       
	$grdes='';
	$b=0;
	if($qryforgrade->num_rows > 0){
		while($rowqryforgrade = $qryforgrade->fetch_assoc())
		{
        	extract($rowqryforgrade);
                
                if($standrdid==$standaid)
				{
                   
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
	}
	

      if($chckbox==0){
                
                $ex=explode(",",$selereq);
                
                for($ddd=0;$ddd<sizeof($ex);$ddd++)
                {

                    $qryforproducts=$ObjDB->QueryObject("SELECT b.fld_prd_name AS prodctname, b.fld_prd_id AS produtid, b.fld_prd_asset_id AS assetid
                                                        FROM itc_bestfit_rpt_products as a
                                                        LEFT JOIN itc_correlation_products as b ON b.fld_id=a.fld_prd_id
                                                        WHERE a.fld_rpt_data_id='".$id."' AND b.fld_prd_id='".$ex[$ddd]."' AND a.fld_delstatus='0'
                                                        ORDER BY b.fld_prd_name ASC");

                    if($qryforproducts->num_rows > 0){
                        while($rowqryforproducts = $qryforproducts->fetch_assoc())
                        {
                        extract($rowqryforproducts);
                                $pid[]=$prductid;
                                $productname[]=$prodctname;
                                $productid[]=$assetid;
                        }
                    }
                }
                
       }
      
        else
        {
            
            $reqcom=explode(",",$selereq);
           
		   for($ccc=0;$ccc<$notitle;$ccc++){
                          
                           
                            $separ= explode("~", $reqcom[$ccc]);
                           
                      
$qryforreqproducts=$ObjDB->QueryObject("SELECT b.fld_prd_name AS prodctname, b.fld_prd_id AS produtid, b.fld_prd_asset_id AS assetid
										FROM itc_bestfit_rpt_reqproducts as a 
										LEFT JOIN itc_correlation_products as b ON b.fld_id=a.fld_prod_id
										WHERE a.fld_rpt_data_id='".$id."' AND a.fld_product_sys_id='".$separ[0]."' AND a.fld_type='".$separ[1]."' AND a.fld_delstatus='0' 
										ORDER BY b.fld_prd_name ASC");
							
							
							
                            
                           
                            if($qryforreqproducts->num_rows > 0){
                                while($rowqryforreqproducts = $qryforreqproducts->fetch_assoc())
                                {
                                  
                                    extract($rowqryforreqproducts);
                                        $pid[]=$produtid;
                                        $productname[]=$prodctname;
                                        $productid[]=$assetid;
                                       
                              }
                            }                        
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
		$string = file_get_contents($url."reports/correlation/standards/".$standardids[$x].".xml");	
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
	
	if($oper=="page1" and $oper != " " )
	{ 
	?>

		<p align="center" ><img src="reportimages/header.jpg"/></p>
		<p align="right" ><img src="reportimages/centerim.jpg"/></p>
<?php 
$schoolname=$ObjDB->SelectSingleValue("SELECT b.fld_school_name AS schoolname FROM itc_user_master AS a LEFT JOIN
				 itc_school_master AS b ON b.fld_id = a.fld_school_id WHERE a.fld_id = '".$uid."'");

 if($sessmasterprfid==7 or $sessmasterprfid==8 or $sessmasterprfid==9 ) {
 
	?>
		
		<p align="right" style="color:<?php echo $colors[$colorstyle];?>;"><h1><?php echo $schoolname; ?></h1></p>
		</p> </br>

<?php } elseif ($sessmasterprfid==2) {
	
	$qryschoolname = $ObjDB->QueryObject("SELECT a.fld_school_name AS schoolname,a.fld_id AS schoolid 
									from itc_school_master AS a 
									left join itc_bestfit_report_data  AS b on  b.fld_schoolid = a.fld_id
									WHERE b.fld_delstatus='0' AND b.fld_id='".$id."'");
						while($res = $qryschoolname->fetch_assoc()){
					extract($res);

						?>
<p align="right" style="color:<?php echo $colors[$colorstyle];?>;"><h1><?php echo $schoolname; ?></h1></p>

	<?php }}?>
		<p align="right" style="margin:0;line-height:0;"><h4><?php echo date('l, F d, Y',strtotime($date)); ?></h4></p>
		
		<p align="center" style="line-height:22px;"><img  height="41" width="92" src="reportimages/Branding_Box3.png"></img></p>
	<?php 
	} 
	
	if($oper=="page2" and $oper != " " )
	{
	    $sub=explode('|',$standardname);
            
	?>
		<p align="left" style="font-size:135px;color:#606060;line-height:0;"><b>SUMMARY</b></p>
		<p align="left" style="font-size:35px;color:#606060;line-height:-4;">This report was prepared using the following information:</p>
		
		<table style="background-color:<?php echo $colors[$colorstyle];?>;color:white;" cellspacing="0" cellpadding="8">
			<tr>
            	<td><b>STANDARD SETS</b></td>
                <td><b>TITLE SET</b></td>
          	</tr>
		</table>
		
		<table>
			<tr><td>&nbsp;</td><td>&nbsp;</td></tr>	
            <tr>
                    <td>
                	<b>Standards Body:</b>&nbsp;&nbsp;<font style="color:#606060;" ><?php echo $statename ?></font><br />
                	&nbsp;&nbsp;<b>Document:</b>&nbsp;&nbsp;<font style="color:#606060;" ><?php echo $sub[0]; ?></font><br />
                   	&nbsp;&nbsp;<b>Subject:</b>&nbsp;&nbsp;<font style="color:#606060;" ><?php echo strstr($sub[1], '(', true); ?></font><br />
                    &nbsp;&nbsp;<b>Version:</b>&nbsp;&nbsp;<font style="color:#606060;" > <?php  $version=strpbrk($sub[1], '('); $symbols = array("(", ")"); 
					echo $vers = str_replace($symbols, "",$version); ?></font><br />
                    &nbsp;&nbsp;<b>Grades:</b>&nbsp;&nbsp;
					<?php
          
                        for($gr=0;$gr<sizeof($gradname);$gr++)
                        {
							if($gr>0) {
								echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							}
                                                         
                        ?>
                        <font style="color:#606060;" ><?php echo $gradname[$gr]; ?></font><br />
                        <?php
                        
                        }
                        ?>
		       </td>
				<td>
                	<?php
                      
                    	for($pro=0;$pro<sizeof($productname);$pro++)
						{
							if($pro>0) {
								echo "&nbsp;";
							}
					?>
					<font style="color:#606060;" ><?php echo $productname[$pro]; ?></font><br />
                    <?php
						}
					?>
                </td>
			</tr>
            <tr><td>&nbsp;</td><td>&nbsp;</td></tr>	
		</table>


		<p align="left" style="line-height:0;font-size:35px;color:#606060;"><b>Please Note</b></p>
		<p>In this report, two categories of curriculum statements are listed: standards and benchmarks. Standards should be read as the parents, with benchmarks being the children. Only the lowest level of statement is considered a benchmark (child). For example, if there are three levels of
statements, the top two levels are listed as standards, with the third level being the benchmark. Depending on the specific report being viewed, the accounting of the standards and benchmarks will vary.</p>
         
	<?php  
	} 
	
	if($oper=="page3" and $oper != " " )
            
	{	
           
		$stdgrapg = (isset($_REQUEST['stdgrapg'])) ? $_REQUEST['stdgrapg'] : 0;
        $bchgraph = (isset($_REQUEST['bchgraph'])) ? $_REQUEST['bchgraph'] : 0;
		$p1 = (isset($_REQUEST['p1'])) ? $_REQUEST['p1'] : 0;
		$p2 = (isset($_REQUEST['p2'])) ? $_REQUEST['p2'] : 0;
		$p3 = (isset($_REQUEST['p3'])) ? $_REQUEST['p3'] : 0;
	
		$percenatageforstd=getpercentage($standardids,$productid,$fld_std_body,$id,1);
                $percenatageforben=getpercentage($standardids,$productid,$fld_std_body,$id,2);
		$benchtotalcnt=getpercentage($standardids,$productid,$fld_std_body,$id,3);                
                
		/** for the purpose of calculting percenatge**/
		$finalpercentage=array();
		$finalbenchstpercentage=array();
		for($per=0;$per<sizeof($standardids);$per++)
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
		
		for($pr=0;$pr<sizeof($productid);$pr++)
		{                 
			if($percenatageforben[$pr]!=0)
			{
			$finalbenchstpercentage[]=round(($percenatageforben[$pr]/$benchtotalcnt[$pr])*100);                        
			}
			else
			{
				$finalbenchstpercentage[]=0;
			}
                        
		}		

		$settings = array(
		  'back_colour' => '#fff',  'stroke_colour' => '#000',
		  'back_stroke_width' => 0, 'back_stroke_colour' => '#eee',
		  'axis_colour' => '#333',  'axis_overlap' => 2,
		  'axis_font' => 'Georgia', 'axis_font_size' => 10,
		  'grid_colour' => '#666',  'label_colour' => '#000',
		  'pad_right' => 20,        'pad_left' => 20,
		  'link_base' => '/',       'link_target' => '_top',
		  'minimum_grid_spacing' => 20,"show_grid_v"=>false,"axis_text_angle_h" => "-90","graph_title"=>"percent covered","graph_title_position"=>"left",
		  "axis_min_v" => "0","axis_max_v" => "100","grid_division_v" => "10"
		);
		
		/***first graph***/
		$values = array();
		$insidesvaules=array();
		for($grph=0;$grph<sizeof($standardids);$grph++)
		{
		
			if($finalpercentage[$grph]==0)
			{
				$final=0.1;
                                
			}
			else
			{
				$final=$finalpercentage[$grph];
                                
			}
			
			$insidesvaules[$standardsname[$grph]]=$final;                        
		}
		
		$values[]=$insidesvaules;
		$colours = array(array($colors[$colorstyle],'white'));
		$links = array('Dough' => 'jpegsaver.php', 'Ray' => 'crcdropper.php', 'Me' => 'svggraph.php');
		
		$graph = new SVGGraph(400, 400, $settings);	
		$graph->colours = $colours;
		$graph->Values($values);
		$graph->Links($links);
		$graph->Render('BarGraph',TRUE,TRUE,FALSE,1,$id);
		
		/***second graph***/
		$values2 = array();
		$insidesvaules=array();
		for($grph=0;$grph<sizeof($standardids);$grph++)
		{
			if($finalbenchstpercentage[$grph]==0)
			{
				$finalbench=0.1;                               
			}
			else
			{
				$finalbench=$finalbenchstpercentage[$grph];
                                
			}
			$insidesvaules[$standardsname[$grph]]=$finalbench;                        
		}
		
		$values2[]=$insidesvaules;
		$colours = array(array($colors[$colorstyle],'white'));
		$links = array('Dough' => 'jpegsaver.php', 'Ray' => 'crcdropper.php', 'Me' => 'svggraph.php');
		 
		$graph = new SVGGraph(400, 400, $settings);
		$graph->colours = $colours;
		 
		$graph->Values($values2);
                $graph->Links($links);
		$graph->Render('BarGraph',TRUE,TRUE,FALSE,2,$id);
	
	
	  
	if($stdgrapg == 1) {           
           
           
?>
	<table width="100%" style="background-color:<?php echo $colors[$colorstyle];?>;color:white;">
        <tr><td><b>STANDARDS/BENCHMARKS ADDRESSED SUMMARY</b></td></tr>
	</table>
	<p style="font-weight:bold;font-family:'Arial';color:#606060" align="left">How to Interpet:</p>
	<p  style="font-family:'Arial';" >When reviewing the "Standards/Benchmarks Addressed Summary, " all curriculum statements from your organization are considered in the accounting of items addressed. Under this reporting structure, if a child statement (bench mark) is considered "addressed," its parent statement (standard) is also considered addressed. in cases where there are three or more levels of statements (i'e.grandparent;parent;child),all levels above the lowest level that is addressed are also considered addressed .Reporting from this analysis consider each statement as being of equal value.</p>
	<ul>
		<?php for($pg=0;$pg<sizeof($standardids);$pg++){ ?>
			<li style="margin:2px;"><?php echo $gradname[$pg]; ?> standards covered :<?php echo $percenatageforstd[$pg]; ?> of <?php echo $standardindividualcount[$pg]; ?> (<?php  echo $finalpercentage[$pg];?>%)</li>
		<?php } ?>
	</ul>
	<p align="center" style="font-family:Arial;font-weight:bold;" ><h3>Standards/Benchmarks Addressed</h3></p>
	<img src="reportimages/someFile1_<?php echo $id; ?>.svg"  height="350" width="400"/>
	<div style="page-break-before: always;">&nbsp;</div>
<?php 
	} // end of page 3 

	if($bchgraph == 1)
	{
?>
	<table width="100%" style="background-color:<?php echo $colors[$colorstyle];?>;color:white;">
		<tr><td style="font-size:10px;" ></td></tr>
		<tr><td style="height:20px;font-weight:bold;font-family:'Arial';">BENCHMARKS ADDRESSED SUMMARY </td></tr>
	</table>
	<p style="font-weight:bold;font-family:'Arial';color:#606060" align="left">How to Interpet:</p>
	<p  style="font-family:'Arial';" >Benchmarks are considered the statements at the lowest level of the document.When reviewing the "Benchmarks Addressed Summary," only the curriculum statements at the lowest level are being reported</p>
	<ul>
		<?php for($pg=0;$pg<sizeof($standardids);$pg++){ ?>
			<li style="margin:2px;"><?php echo $gradname[$pg]; ?> standards covered :<?php echo $percenatageforben[$pg]; ?> of <?php echo $benchtotalcnt[$pg]; ?> (<?php  echo round(($percenatageforben[$pg]/$benchtotalcnt[$pg])*100);?>%)</li>
		<?php } ?>
	</ul>
	<p align="center" style="font-family:Arial;font-weight:bold;" ><h3>Benchmarks Addressed</h3></p>
	<img src="reportimages/someFile2_<?php echo $id; ?>.svg"  height="350" width="400" />
	<div style="page-break-before: always;">&nbsp;</div>
<?php 
	} 

	if($p1 == 1) 
	{ 
?> 
		<p style="font-weight:bold;font-family:Arial;font-size:40px" >COVERAGE REPORTS ORGANIZED BY STANDARDS/BENCHMARK </p>
		<p>This section of the reports lists each curriculum statement in the set choosen for this report and the the titles that address them .Statements that are colored gray are not aaddressed by any title in the title set chosen for this report</p>
		<?php
			$unolist=array();
			$olist=array();
			for($z=0;$z<sizeof($finalstandardlist);$z++)
			{ 
		?>
			<p style="font-family:Arial;color:#606060;font-size:40px;font-weight:bold" ><?php echo key($finalstandardlist[$z]); ?> </p>
			<ul>
				<li style="margin:2px;"><?php echo $gradname[$z]; ?> standards covered :<?php echo $percenatageforstd[$z]; ?> of <?php echo $standardindividualcount[$z]; ?> (<?php  echo $finalpercentage[$z];?>%)</li>
				<li style="margin:2px;"><?php echo $gradname[$z]; ?> standards covered :<?php echo $percenatageforben[$z]; ?> of <?php echo $benchtotalcnt[$z]; ?> (<?php  echo $finalbenchstpercentage[$z];?>%)</li>
			</ul>
			<table width="100%" border="0.5" cellpadding="8">
				<thead>
					<tr style="background-color:<?php echo $colors[$colorstyle] ?>" > 
						<td align="center" width="25%" ></td> 
						<td  width="75%" style="color:#FFF;font-weight:bold;"><?php echo key($finalstandardlist[$z]); ?><br/><?php echo $statename."|".$standardname ?></td> 
					</tr>
				</thead> 
				<?php 
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
									  $olist[]= array(key($productarray[$g])=>array("num"=>$finalstandardlist[$z][key($finalstandardlist[$z])][0]['num'.$cnt],"content"=>$finalstandardlist[$z][key($finalstandardlist[$z])][1]['content'.$cnt],"guid"=>$finalstandardlist[$z][key($finalstandardlist[$z])][2]['guid'.$cnt],"standard"=> key($finalstandardlist[$z])));
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
					<tr nobr="true"  <?php if($finalstandardlist[$z][key($finalstandardlist[$z])][0]['num'.$cnt]=='')  {?> style="background-color:<?php echo $colorlight[$colorstyle] ?>;" <?php } ?>>
						<td width="25%" align="center" style="color:<?php echo $color; ?>" ><?php echo $finalstandardlist[$z][key($finalstandardlist[$z])][0]['num'.$cnt]; ?></td>
						<td width="75%" ><?php  echo $new = htmlspecialchars($finalstandardlist[$z][key($finalstandardlist[$z])][1]['content'.$cnt], ENT_QUOTES); ?><?php if($finalstandardlist[$z][key($finalstandardlist[$z])][0]['num'.$cnt]!='' and $prdctname!='')  {?><ul><?php  echo $prdctname; ?></ul><?php } ?></td>
					</tr>
				<?php	
					}
					$unorderedlist[]=$unolist;					
					unset($unolist);
				?>
			</table>
		<?php 
		}
		?>
		<div style="page-break-before: always;">&nbsp;</div>
<?php
	} // end of P1
	
	if($p2 == 1) 
	{  
?>
		<p style="font-weight:bold;font-family:Arial;font-size:50px" >COVERAGE REPORT ORGANIZED BY PRODUCT TITLE</p>
		<p>This section of the reports lists each curriculum statement in the set choosen for this report and the titles that address them .Statements that are colored gray are not addressed by any title in the title set chosen for this report</p>
		<?php 
			$odata = array();
			for($q=0;$q<sizeof($productname);$q++){                             
				$percenatageforstdinv=getpercentage($standardids,array($productid[$q]),$fld_std_body,$id,1);  
				$unipoit=getpercentage($standardids,array($productid[$q]),$fld_std_body,$id,5);
				$uniarray=getpercentage($standardids,array($productid[$q]),$fld_std_body,$id,4);				
  /*for each page title in next page */
                                if($q==0){ ?>

				<p style="font-family:Arial;color:#606060;font-size:40px;font-weight:bold" ><?php echo $productname[$q]; ?> </p>
				

<?php 
                                }
                                else { ?>
                                <div style="page-break-before: always;"></div>
                <p style="font-family:Arial;color:#606060;font-size:40px;font-weight:bold" ><?php echo $productname[$q]; ?> </p>
                                                
                                <?php

                                }
$desccheck =$ObjDB->SelectSingleValueInt("SELECT fld_sec_prod_description
									  	  FROM itc_bestfit_report_data where fld_id='".$id."'");
if($desccheck==1){
    

		$titletype=$ObjDB->SelectSingleValueInt("SELECT fld_prd_type FROM itc_correlation_products 
			WHERE LCASE(REPLACE(fld_prd_name,' ',' '))='".$productname[$q]."'");
                        


		if($titletype=='1'){
			
				$titlename=$ObjDB->SelectSingleValue("SELECT a.fld_ipl_descr FROM                           
                                                                itc_correlation_rpt_products AS b
                                                                LEFT JOIN itc_ipl_master AS a ON a.fld_asset_id=b.fld_product_id WHERE  
                                                                b.fld_product_id='".$pid[$q]."' AND a.fld_delstatus='0' GROUP BY a.fld_asset_id"); 


				}
				else if($titletype=='3')
    			{
			
  			$titlename=$ObjDB->SelectSingleValue("SELECT a.fld_module_descr 
                                                        FROM itc_correlation_rpt_products AS b
                                                        LEFT JOIN itc_module_master AS a ON a.fld_asset_id=b.fld_product_id WHERE
                                                        b.fld_product_id='".$pid[$q]."' AND a.fld_delstatus='0' GROUP BY a.fld_asset_id");
    			}
			    else if($titletype=='4')
			    {
	    	
	       $titlename=$ObjDB->SelectSingleValue("SELECT a.fld_mathmodule_descr  FROM
                                            itc_correlation_rpt_products AS b 
                                            LEFT JOIN itc_mathmodule_master AS a ON a.fld_asset_id=b.fld_product_id WHERE
                                             b.fld_product_id='".$pid[$q]."' AND a.fld_delstatus='0' GROUP BY a.fld_asset_id");
			    }

	
   ?>
	<p font style="color:#000000;" ><?php if($titlename !='undefined'){ echo $titlename; }?></p>
	
				<?php
			}

?>
				<ul>
					<?php for($u=0;$u<sizeof($standardsname);$u++){ 					
					?>
					<li style="margin:2px;"><?php echo $standardsname[$u]; ?> standards covered :<?php echo $percenatageforstdinv[$u]; ?> of <?php echo $standardindividualcount[$u]; ?> (<?php  echo round(($percenatageforstdinv[$u]/$standardindividualcount[$u])*100);?>%) (<?php echo $unipoit[$u]; ?> unique)</li>
					<?php } ?>
				</ul>
		<?php 
			for($f=0;$f<sizeof($standardsname);$f++)
			{
				$count=0;
				$cnt=0;
		?>
				<p style="font-family:Arial;color:#606060;font-size:40px;font-weight:bold" ><?php echo $standardsname[$f]; ?> </p>
		<?php 
			for($pr=0;$pr<sizeof($productname);$pr++)
			{
				for($o=0;$o<sizeof($olist);$o++)
				{
					if($olist[$o][$productname[$pr]]['standard']==$standardsname[$f])
					{
						$cnt++;
					}
				}
			} 
		?>
		<?php if( $cnt>0){ ?>
			<table width="100%"  border="0.5"  cellpadding="8" >
				<thead>
					<tr style="background-color:<?php echo $colors[$colorstyle] ?>" > 
						<td align="center" width="25%" ></td> 
						<td width="75%" style="color:#FFF;font-weight:bold;"><?php echo $standardsname[$f]; ?><br/><?php echo $statename."|".$standardname ?></td> 
					</tr>
				</thead>  
				<?php 
				for($p=0;$p<sizeof($olist);$p++)
				{	 
					if($olist[$p][$productname[$q]]['standard']==$standardsname[$f])
					{
				?>
					<tr nobr="true" > 
						<td height="30px"  align="center" width="25%" ><?php if(array_search($olist[$p][$productname[$q]]['guid'],$uniarray[$f])) { echo "*";}  ?><?php echo $olist[$p][$productname[$q]]['num']; ?></td>
						<td  width="75%"  height="30px" ><?php echo htmlspecialchars($olist[$p][$productname[$q]]['content']); ?></td>
					</tr> 
				<?php 
					}
					else
					{
						$count++;
					}
				}
				?>
			</table>
		<?php } else { ?> 
			<p style="font-family:Arial;" >No correlations are available for this product using the selected report criteria</p>  <?php } ?> 
		<?php } ?>
		<?php 
			} 
		?>
		<div style="page-break-before: always;">&nbsp;</div>
<?php
	} // end of p2 
	
	if($p3 == 1) 
	{
?>
		<p style="font-weight:bold;font-family:Arial;font-size:40px" >STANDARDS/BENCHMARKS NOT ADDRESSED SUMMARY</p>
		<p>This section of the report shows all standards that are not addressed by the set of titles used to create this report</p>
		<?php
			for($z=0;$z<sizeof($finalstandardlist);$z++)
			{ 
		?>
			<p style="font-family:Arial;color:#606060;font-size:40px;font-weight:bold" ><?php echo key($finalstandardlist[$z]); ?></p>
			<table width="100%"  border="0.5"  cellpadding="8">
				<thead>
					<tr style="background-color:<?php echo $colors[$colorstyle] ?>" > 
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
					<tr nobr="true" >   
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
		} // end of p3 if
	} // end of page 3 if 

	@include("footer.php");
