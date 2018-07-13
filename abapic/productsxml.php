<?php
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', '1');

@include("/home/ubuntu/synergy/includes/table.class.php");

/**
 * To set asset ID 
 */

$time='';
$start='';
$finish='';
$total_time='';
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;


exec("rm -rf /home/ubuntu/synergy/reports/correlation/products/*.xml");
echo 'OK';

$prdqry = $ObjDB->QueryObject("SELECT fld_id, fld_prd_id AS assetid,fld_prd_type 
                                FROM itc_correlation_products 
                                WHERE fld_prd_id IS NOT NULL and fld_prd_id != ''");


if($prdqry->num_rows > 0){
	while($prdrow = $prdqry->fetch_assoc()){
		extract($prdrow);
		
		$url = "http://api.statestandards.com/services/rest/maintainAsset?api_key=q044Qjav7i8dzgCJ6riUWA&_cid=".$assetid."&_cname=Content%20%28AAED%29&format=json";
                
		$contents = file_get_contents($url);
                $results = json_decode($contents,true);

        $cntforprodass=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id)
                                                                FROM  itc_correlation_products 
                                                                WHERE fld_prd_asset_id='".$results['content']."' AND fld_prd_type='".$fld_prd_type."'");
               
             if($cntforprodass==0)
             {
                $ObjDB->NonQuery("UPDATE itc_correlation_products SET fld_prd_asset_id='".$results['content']."' WHERE fld_id='".$fld_id."'");
             }
	
		$url = "http://api.statestandards.com/services/rest/reviewHandpick?api_key=q044Qjav7i8dzgCJ6riUWA&asset=".$results['content'];	
                
                $string = file_get_contents($url);
                $stringg = file_put_contents("/home/ubuntu/synergy/reports/correlation/products/".$results['content'].".xml",$string);	

	}
}

exec("sudo chmod 0775 -R /home/ubuntu/synergy/reports/correlation/products/");

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
echo 'Page generated in '.$total_time.' seconds.';

$msg="Productsxml are inserted/updated in the corressponding tables on productsxml.php and the process has been completed successfully";
mail("dineshkumar@nanonino.in","Developer Alert",$msg);

?>
