<?php
set_time_limit(0);
@include("/home/ubuntu/synergy/includes/table.class.php");
error_reporting(E_ALL); 
ini_set('display_errors', '1');


/**
 * To Get States List
 */

$time='';
$start='';
$finish='';
$total_time='';
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
   
$url = "http://api.statestandards.com/services/rest/browse?api_key=q044Qjav7i8dzgCJ6riUWA&levels=2&format=json";
$contents = file_get_contents($url); 
$results = json_decode($contents,true); 

$states_res = $results['itm'][0]['itm'];

 for($k=0;$k<sizeof($states_res);$k++)
 {
            $cnt=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id)
                                           FROM  itc_standards_bodies 
                                           WHERE fld_guid='".$states_res[$k]['guid']."'");
            if($cnt==0)
            {
                $ObjDB->NonQuery("INSERT INTO itc_standards_bodies (fld_name, fld_guid, fld_type, fld_chld,fld_created_date) VALUES ('".$states_res[$k]['title']."','".$states_res[$k]['guid']."','".$states_res[$k]['type']."','".$states_res[$k]['chld']."','".date("Y-m-d H:i:s")."')");
            }
            else
            {
                $ObjDB->NonQuery("UPDATE itc_standards_bodies SET fld_name='".$states_res[$k]['title']."',fld_type='".$states_res[$k]['type']."',fld_chld='".$states_res[$k]['chld']."',fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_guid='".$states_res[$k]['guid']."'");
            }
 }		
    

    
 /**
 *  To insert documents and document_sub
 */ 
$qry = $ObjDB->QueryObject("SELECT fld_id AS stdbid, fld_guid AS stguid, fld_name AS stdbody 
									FROM itc_standards_bodies");
if($qry->num_rows > 0)
{
	while($row = $qry->fetch_assoc())
        {
		extract($row);
		
		$url = "http://api.statestandards.com/services/rest/browse?api_key=q044Qjav7i8dzgCJ6riUWA&guid=".$stguid."&levels=2&format=json";
		$contents = file_get_contents($url); 
		$results = json_decode($contents,true); 
		
                $cor_doc = $results['itm'][0]['itm'][0]['itm'];
               
               for($i=0;$i<sizeof($cor_doc);$i++)
               {
		
                     $count=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id)
                                                            FROM  itc_correlation_documents 
                                                            WHERE fld_doc_guid='".$cor_doc[$i]['guid']."'");
                   if($count==0)
                   {
                       
			$doc_id = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_correlation_documents (fld_authority_id, fld_doc_title, fld_doc_guid, fld_created_date) VALUES ('".$stdbid."','".addslashes($cor_doc[$i]['title'])."','".$cor_doc[$i]['guid']."','".date("Y-m-d H:i:s")."')");
                        $doc_sub = $cor_doc[$i]['itm'];
                   
                        for($j=0;$j<sizeof($doc_sub);$j++){ // child for
                            
                            $count1=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id)
                                                                  FROM  itc_correlation_doc_subject 
                                                                  WHERE fld_sub_guid='".$doc_sub[$j]['guid']."'");
				 
                                if($count1==0)
                                {
                                    $ObjDB->NonQuery("INSERT INTO itc_correlation_doc_subject (fld_doc_id, fld_sub_title, fld_sub_guid, fld_sub_year, fld_created_date) VALUES ('".$doc_id."','".addslashes($doc_sub[$j]['title'])."','".$doc_sub[$j]['guid']."','".$doc_sub[$j]['meta']['content']."','".date("Y-m-d H:i:s")."')");
			        }
                        } unset($doc_sub);// child for end
                         
                   } //parent if
                  
                   else
                   {
                       $ObjDB->NonQuery("UPDATE itc_correlation_documents SET fld_authority_id='".$stdbid."',fld_doc_title='".addslashes($cor_doc[$i]['title'])."',fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_doc_guid='".$cor_doc[$i]['guid']."'");
                               
                       $doc_id=$ObjDB->SelectSingleValueInt("SELECT fld_id
                                                             FROM  itc_correlation_documents 
                                                             WHERE fld_doc_guid='".$cor_doc[$i]['guid']."'");
                        $doc_sub = $cor_doc[$i]['itm'];
                        
                       for($j=0;$j<sizeof($doc_sub);$j++)
                       {
                            
                                 $count1=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id)
                                                                       FROM  itc_correlation_doc_subject 
                                                                       WHERE fld_sub_guid='".$doc_sub[$j]['guid']."'");
				 
                                  if($count1==0)
                                  {
                                       $ObjDB->NonQuery("INSERT INTO itc_correlation_doc_subject (fld_doc_id, fld_sub_title, fld_sub_guid, fld_sub_year, fld_created_date) VALUES ('".$doc_id."','".addslashes($doc_sub[$j]['title'])."','".$doc_sub[$j]['guid']."','".$doc_sub[$j]['meta']['content']."','".date("Y-m-d H:i:s")."')");
			          }
                                  else
                                  {
                                        $ObjDB->NonQuery("UPDATE itc_correlation_doc_subject SET fld_doc_id='".$doc_id."',fld_sub_title='".addslashes($doc_sub[$j]['title'])."',fld_sub_year='".$doc_sub[$j]['meta']['content']."',fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_sub_guid='".$doc_sub[$j]['guid']."'");
                                  }
                       } unset($doc_sub);//for end
                   } // else end
               }unset($cor_doc);// parent for
                
             }
        }

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
echo 'Page generated in '.$total_time.' seconds.';


$msg="Statedocumentsubject are inserted/updated in the corressponding tables on statedocumentsubject.php and the process has been completed successfully";
mail("dineshkumar@nanonino.in","Developer Alert",$msg);
?>

