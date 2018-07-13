<?php
//echo $fullpath=$_SERVER["DOCUMENT_ROOT"];
set_time_limit(0);
ini_set('memory_limit', '512M');
@include("/home/ubuntu/synergy/includes/table.class.php");
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

$time='';
$start='';
$finish='';
$total_time='';
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;



$prdqry = $ObjDB->QueryObject("SELECT fld_id as gradeid, fld_grade_guid AS assetid FROM itc_correlation_grades");
 
if($prdqry->num_rows > 0){
	while($prdrow = $prdqry->fetch_assoc()){
		extract($prdrow);
     
                $filename = '/home/ubuntu/synergy/reports/correlation/standards/'.$assetid.'.xml';
               
                if(file_exists($filename)) {
                    
                   
                    $url = "http://api.statestandards.com/services/rest/browse?api_key=q044Qjav7i8dzgCJ6riUWA&levels=0&guid=".$assetid."&format=json";	
                    
		
                    $contents = file_get_contents($url); 
                    $results = json_decode($contents,true); 
                 
                    $sub_grades_res = $results['itm'][0]['itm'][0]['itm'][0]['itm'][0]['itm'][0]['itm'];
                   
                    for($i=0;$i<sizeof($sub_grades_res);$i++)
                    {
                        for($j=0;$j<sizeof($sub_grades_res[$i]['itm']);$j++){
                    
                           // print_r($sub_grades_res[$i]['itm'][$j]['itm']);
                            for($k=0;$k<sizeof($sub_grades_res[$i]['itm'][$j]['itm']);$k++){
                                // print_r($sub_grades_res[$i]['itm'][$j]['itm'][$k]['itm']);
                                for($l=0;$l<sizeof($sub_grades_res[$i]['itm'][$j]['itm'][$k]['itm']);$l++){
                                    
                                    echo "TITLE".$sub_grades_res[$i]['itm'][$j]['itm'][$k]['guid']."</br>";
                                    echo $sub_grades_res[$i]['itm'][$j]['itm'][$k]['itm'][$l]['guid']."</br>";
                                    
                                    
                                 $deepinnerstandardid=$ObjDB->SelectSingleValueInt("SELECT fld_id
                                                                FROM  itc_correlation_deepinnerstandards 
                                                                WHERE fld_deepinnerstandard_guid='".$sub_grades_res[$i]['itm'][$j]['itm'][$k]['guid']."'");
                              
                       $cntforgrade=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id)
                                                                FROM  itc_correlation_subdeepinnerstandards 
                                                                WHERE fld_subdeepinnerstandard_guid='".$sub_grades_res[$i]['itm'][$j]['itm'][$k]['itm'][$l]['guid']."'");
                             
                                      if($cntforgrade==0)
                    {
                        $ObjDB->NonQuery("INSERT INTO itc_correlation_subdeepinnerstandards (fld_deepinnerstandard_id, fld_subdeepinnerstandard_guid, fld_subdeepinnerstandard_name, fld_subdeepinnerstandardname_id, fld_type, fld_created_date) "
                                . "VALUES ('".$deepinnerstandardid."','".$sub_grades_res[$i]['itm'][$j]['itm'][$k]['itm'][$l]['guid']."','".addslashes($sub_grades_res[$i]['itm'][$j]['itm'][$k]['itm'][$l]['meta']['descr']['content'])."','".$sub_grades_res[$i]['itm'][$j]['itm'][$k]['itm'][$l]['meta']['num']['content']."','".$sub_grades_res[$i]['itm'][$j]['itm'][$k]['itm'][$l]['type']."','".date("Y-m-d H:i:s")."')");
                        echo "success";
                    }
                    else
                    {
                        $ObjDB->NonQuery("UPDATE itc_correlation_subdeepinnerstandards SET fld_deepinnerstandard_id='".$deepinnerstandardid."',fld_subdeepinnerstandard_name='".addslashes($sub_grades_res[$i]['itm'][$j]['itm'][$k]['itm'][$l]['meta']['descr']['content'])."',fld_subdeepinnerstandardname_id='".$sub_grades_res[$i]['itm'][$j]['itm'][$k]['itm'][$l]['meta']['num']['content']."',fld_type='".$sub_grades_res[$i]['itm'][$j]['itm'][$k]['itm'][$l]['type']."',fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_subdeepinnerstandard_guid='".$sub_grades_res[$i]['itm'][$j]['itm'][$k]['itm'][$l]['guid']."'");
                        echo "updated";
                    }
                                    
                                }
                          
                       echo "</br>";
                                         
//                    if($cntforgrade==0)
//                    {
//                        $ObjDB->NonQuery("INSERT INTO itc_correlation_deepinnerstandards (fld_innerstandard_id, fld_deepinnerstandard_guid, fld_deepinnerstandard_name, fld_deepinnerstandardname_id, fld_type, fld_created_date) VALUES ('".$innerstandardid."','".$sub_grades_res[$i]['itm'][$j]['itm'][$k]['guid']."','".addslashes($sub_grades_res[$i]['itm'][$j]['itm'][$k]['meta']['descr']['content'])."','".$sub_grades_res[$i]['itm'][$j]['itm'][$k]['meta']['num']['content']."','".$sub_grades_res[$i]['itm'][$j]['itm'][$k]['type']."','".date("Y-m-d H:i:s")."')");
//                        echo "success";
//                    }
//                    else
//                    {
//                        $ObjDB->NonQuery("UPDATE itc_correlation_deepinnerstandards SET fld_innerstandard_id='".$innerstandardid."',fld_deepinnerstandard_name='".addslashes($sub_grades_res[$i]['itm'][$j]['itm'][$k]['meta']['descr']['content'])."',fld_deepinnerstandardname_id='".$sub_grades_res[$i]['itm'][$j]['itm'][$k]['meta']['num']['content']."',fld_type='".$sub_grades_res[$i]['itm'][$j]['itm'][$k]['type']."',fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_deepinnerstandard_guid='".$sub_grades_res[$i]['itm'][$j]['itm'][$k]['guid']."'");
//                        echo "updated";
//                    }
                               // } // for ends of $l
                        } // for ends of $k
                     } // for ends of $j
                  } // for ends of $i
                   
		}
		else 
                {
			//echo $assetid." -  Not exists<br />";	
		}
           
        } // while ends
       
} // if ends




//
//
//$prdqry = $ObjDB->QueryObject("SELECT fld_id, fld_grade_guid AS assetid FROM itc_correlation_grades");
// 
//if($prdqry->num_rows > 0){
//	while($prdrow = $prdqry->fetch_assoc()){
//		extract($prdrow);
//                
//		
//		
//	}
//}

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
echo 'Page generated in '.$total_time.' seconds.';

?>

	

