<?php
set_time_limit(0);
@include("/home/ubuntu/synergy/includes/table.class.php");
error_reporting(E_ALL);
ini_set('display_errors', '1');

$time='';
$start='';
$finish='';
$total_time='';
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

exec("rm -rf /home/ubuntu/synergy/reports/correlation/standards/*.xml");

$prdqry = $ObjDB->QueryObject("SELECT fld_id, fld_grade_guid AS assetid FROM itc_correlation_grades");
 
if($prdqry->num_rows > 0){
	while($prdrow = $prdqry->fetch_assoc()){
		extract($prdrow);
                
		$filename = '/home/ubuntu/synergy/reports/correlation/standards/'.$assetid.'.xml';
                
		if(file_exists($filename)) {
			
		}
		else 
                {
			$url = "http://api.statestandards.com/services/rest/browse?api_key=q044Qjav7i8dzgCJ6riUWA&levels=0&guid=".$assetid;	
			$string = file_get_contents($url);	
			$string = file_put_contents("/home/ubuntu/synergy/reports/correlation/standards/".$assetid.".xml",$string);	
		}
		
	}
}

exec("sudo chmod 0775 -R /home/ubuntu/synergy/reports/correlation/standards/");
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
echo 'Page generated in '.$total_time.' seconds.';


$msg="Standardsxml are inserted/updated in the corressponding tables on standardsxml.php and the process has been completed successfully";
mail("dineshkumar@nanonino.in","Developer Alert",$msg);
?>

	

