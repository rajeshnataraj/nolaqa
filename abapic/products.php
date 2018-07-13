<?php
set_time_limit(0);


@include("/home/ubuntu/synergy/includes/table.class.php");
error_reporting(E_ALL); 
ini_set('display_errors', '1');


/**
 * To get all products
 */

$time='';
$start='';
$finish='';
$total_time='';
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

$qryipls="SELECT a.fld_id AS id, CONCAT(a.fld_ipl_name,' ',b.fld_version) AS nam, 1 AS typ, a.fld_asset_id AS assetid 
                                        FROM itc_ipl_master  AS a
                                        LEFT JOIN itc_ipl_version_track AS b ON b.fld_ipl_id=a.fld_id
                                        WHERE a.fld_access='1' AND a.fld_delstatus='0' AND b.fld_delstatus='0' AND b.fld_zip_type='1'";
	  
$qryunits="SELECT fld_id AS id, fld_unit_name AS nam, 2 AS typ, fld_asset_id as assetid FROM itc_unit_master WHERE fld_delstatus='0'";

$qrymodules="SELECT a.fld_id AS id, CONCAT(a.fld_module_name,' ',b.fld_version) AS nam, 3 AS typ, a.fld_asset_id AS assetid 
                                            FROM itc_module_master AS a 
                                            LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_id
                                            WHERE a.fld_delstatus='0' AND b.fld_delstatus='0'";

$qrymathmodules="SELECT a.fld_id AS id, CONCAT(a.fld_mathmodule_name,' ',b.fld_version) AS nam, 4 AS typ, a.fld_asset_id AS assetid 
                                                FROM itc_mathmodule_master AS a 
                                                LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_module_id
                                                WHERE a.fld_delstatus='0' AND b.fld_delstatus='0'";
				

$qryexpedition="SELECT a.fld_id AS id, CONCAT(a.fld_exp_name, ' ', b.fld_version) AS nam, 5 AS typ, a.fld_asset_id as assetid
							  FROM itc_exp_master AS a 
							  LEFT JOIN itc_exp_version_track AS b ON b.fld_exp_id = a.fld_id 
							  WHERE a.fld_delstatus = '0' AND b.fld_delstatus = '0'";
				
$qry = $qryipls." union all ".$qryunits." union all ".$qrymodules." union all ".$qrymathmodules." union all ".$qryexpedition;

$prdqry = $ObjDB->QueryObject($qry);
				
if($prdqry->num_rows > 0)
{
	while($prdrow = $prdqry->fetch_assoc())
        {
		extract($prdrow);
                $cntforprod=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id)
                                                          FROM  itc_correlation_products 
                                                          WHERE fld_prd_id='".$assetid."' AND fld_prd_type='".$typ."'");
		if($cntforprod==0)
                {
                $ObjDB->NonQuery("INSERT INTO itc_correlation_products (fld_prd_sys_id, fld_prd_type, fld_prd_name, fld_exp_type, fld_prd_id, fld_created_date) VALUES ('".$id."', '".$typ."', '".addslashes($nam)."','0','".$assetid."','".date("Y-m-d H:i:s")."')");
                }
                else
                {
                $ObjDB->NonQuery("UPDATE itc_correlation_products SET fld_prd_sys_id='".$id."',fld_prd_type='".$typ."', fld_exp_type='0',fld_prd_name='".addslashes($nam)."',fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_prd_id='".$assetid."' AND fld_prd_type='".$typ."' AND fld_exp_type='0'"); 
                }
            
            // Resources Assestid insert
            if($typ==5){
                
             $qrydest = $ObjDB->QueryObject("SELECT fld_id AS destid, fld_dest_name AS destname, 1 as destype
                                            FROM itc_exp_destination_master
                                            WHERE fld_exp_id = '".$id."' AND fld_delstatus = '0'
                                            GROUP BY destid
                                            ORDER BY fld_order");
           if($qrydest->num_rows>0){
            while($resdest = $qrydest->fetch_assoc()){
              extract($resdest);
              
              $qrytask= $ObjDB->QueryObject("SELECT fld_id AS taskid, fld_task_name AS taskname, 2 as tasktype
                                                FROM itc_exp_task_master
                                                WHERE fld_dest_id='".$destid."' AND fld_flag='1' AND fld_delstatus='0' ORDER BY fld_order");
                            if($qrytask->num_rows > 0){													
                              while($rowstask = $qrytask->fetch_assoc()){
                                  extract($rowstask);
                                  
                                  
                                  $qryres=$ObjDB->QueryObject("SELECT a.fld_id AS resoid, a.fld_res_name AS resoname, 3 as restype,fld_expres_id as prdid
                                                                FROM itc_exp_resource_master AS a 
                                                                LEFT JOIN itc_exp_res_status AS b on a.fld_id = b.fld_res_id                                                      
                                                                WHERE a.fld_task_id='".$taskid."' AND a.fld_flag='1' AND a.fld_delstatus='0' 
                                                                AND b.fld_status='1' GROUP BY resoid ORDER BY a.fld_order");
                                  
                                     if($qryres->num_rows > 0){													
                              while($rowsres = $qryres->fetch_assoc()){
                                  extract($rowsres);
                               
                                  $cnt=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id)
                                                          FROM  itc_correlation_products 
                                                          WHERE fld_prd_sys_id='".$resoid."' AND fld_prd_id='".$prdid."' AND fld_prd_type='5' AND fld_exp_type='".$restype."'");
                                  
                                  
                                    if($cnt==0)
                                    {

                                    $ObjDB->NonQuery("INSERT INTO itc_correlation_products (fld_prd_sys_id, fld_prd_type, fld_prd_id, fld_prd_name, fld_exp_type, fld_created_date) VALUES ('".$resoid."', '5', '".$prdid."', '".addslashes($resoname)."','".$restype."','".date("Y-m-d H:i:s")."')");
                                    
                                    
      }
                                 else
                                   {

                                    $ObjDB->NonQuery("UPDATE itc_correlation_products SET fld_prd_id='".$prdid."', fld_prd_sys_id='".$resoid."',fld_prd_name='".addslashes($resoname)."',fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_prd_sys_id='".$resoid."' AND fld_prd_type='5' AND fld_exp_type='".$restype."'"); 
                                   
                                    
}

                                   }// while ends res 
                              } // if ends res
                              
                               $cnttask=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id)
                                                          FROM  itc_correlation_products 
                                                          WHERE fld_prd_sys_id='".$taskid."' AND fld_prd_type='5' AND fld_exp_type='".$tasktype."'");
                                  
                                  
                                    if($cnttask==0)
                                    {

                                    $ObjDB->NonQuery("INSERT INTO itc_correlation_products (fld_prd_sys_id, fld_prd_type, fld_prd_name, fld_exp_type, fld_created_date) VALUES ('".$taskid."', '5', '".addslashes($taskname)."','".$tasktype."','".date("Y-m-d H:i:s")."')");
                                    
                                   
                                    }
                                 else
                                   {

                                    $ObjDB->NonQuery("UPDATE itc_correlation_products SET fld_prd_sys_id='".$taskid."',fld_prd_name='".addslashes($taskname)."',fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_prd_sys_id='".$taskid."' AND fld_prd_type='5' AND fld_exp_type='".$tasktype."'"); 
                                   
                                   
                                   }
                                  
                        }// while ends task
                    } // if ends task
        
                     $cntdest=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id)
                                                          FROM  itc_correlation_products 
                                                          WHERE fld_prd_sys_id='".$destid."' AND fld_prd_type='5' AND fld_exp_type='".$destype."'");
                                  
                                  
                                    if($cntdest==0)
                                    {

                                    $ObjDB->NonQuery("INSERT INTO itc_correlation_products (fld_prd_sys_id, fld_prd_type, fld_prd_name,fld_exp_type, fld_created_date) VALUES ('".$destid."', '5', '".addslashes($destname)."','".$destype."','".date("Y-m-d H:i:s")."')");
                                    
                                    
                                    }
                                 else
                                   {

                                    $ObjDB->NonQuery("UPDATE itc_correlation_products SET fld_prd_sys_id='".$destid."',fld_prd_name='".addslashes($destname)."',fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_prd_sys_id='".$destid."' AND fld_prd_type='5' AND fld_exp_type='".$destype."'"); 
                                   
                                   
                                   }
                                   
                }// while ends dest
              } // if ends dest
          
             
            } // if type
            
      }
}


$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
echo 'Page generated in '.$total_time.' seconds.';

$msg="Products are inserted/updated in the corressponding tables on products.php and the process has been completed successfully";
mail("dineshkumar@nanonino.in","Developer Alert",$msg);

?>
