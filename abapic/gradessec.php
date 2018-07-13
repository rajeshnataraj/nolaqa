<?php
set_time_limit(0);
@include("/home/ubuntu/synergy/includes/table.class.php");
error_reporting(E_ALL); 
ini_set('display_errors', '1');

/**
 * To get grades of each subject
 */
$time='';
$start='';
$finish='';
$total_time='';
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

$tot=$ObjDB->SelectSingleValueInt("SELECT count(*) 
			    FROM itc_correlation_doc_subject");

$split=round($tot/2);

$qry = $ObjDB->QueryObject("SELECT fld_id AS subid, fld_sub_guid AS subguid 
			    FROM itc_correlation_doc_subject limit ".$split.",".$tot."");
if($qry->num_rows > 0)
{
	while($row = $qry->fetch_assoc())
        {
		extract($row);
		
		$url = "http://api.statestandards.com/services/rest/browse?api_key=q044Qjav7i8dzgCJ6riUWA&levels=1&guid=".$subguid."&format=json";
		$contents = file_get_contents($url); 
		$results = json_decode($contents,true); 
		
		$sub_grades_res = $results['itm'][0]['itm'][0]['itm'][0]['itm'][0]['itm'];
		
		for($i=0;$i<sizeof($sub_grades_res);$i++)
                {
                    $cntforgrade=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id)
                                                                FROM  itc_correlation_grades 
                                                                WHERE fld_grade_guid='".$sub_grades_res[$i]['guid']."'");
                    if($cntforgrade==0)
                    {
                        $ObjDB->NonQuery("INSERT INTO itc_correlation_grades (fld_sub_id, fld_grade_name, fld_grade_guid, fld_grade_hi, fld_grade_lo, fld_type, fld_grade_chld, fld_created_date) VALUES ('".$subid."','".addslashes($sub_grades_res[$i]['title'])."','".$sub_grades_res[$i]['guid']."','".$sub_grades_res[$i]['meta']['hi']."','".$sub_grades_res[$i]['meta']['lo']."','".$sub_grades_res[$i]['type']."','".$sub_grades_res[$i]['chld']."','".date("Y-m-d H:i:s")."')");
                    }
                    else
                    {
                        $ObjDB->NonQuery("UPDATE itc_correlation_grades SET fld_sub_id='".$subid."',fld_grade_name='".addslashes($sub_grades_res[$i]['title'])."',fld_grade_hi='".$sub_grades_res[$i]['meta']['hi']."',fld_grade_lo='".$sub_grades_res[$i]['meta']['lo']."',fld_type='".$sub_grades_res[$i]['type']."',fld_grade_chld='".$sub_grades_res[$i]['chld']."',fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_grade_guid='".$sub_grades_res[$i]['guid']."'");
                    }
		}
	}
}

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
echo 'Page generated in '.$total_time.' seconds.';


$msg="Gradessec are inserted/updated in the corressponding tables on gradessec.php and the process has been completed successfully";
mail("dineshkumar@nanonino.in","Developer Alert",$msg);
?>