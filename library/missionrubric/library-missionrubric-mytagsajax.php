<?php
@include("sessioncheck.php");
	
$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';

/* Content tagging oper * **/
if($oper == "savecontenttagdetails" and $oper != '')
{		
	try{
                $misid = isset($method['expid']) ? $method['expid'] : '';
		$rubricid = isset($method['rubricid']) ? $method['rubricid'] : '';
		$categoryid = isset($method['categoryid']) ? $method['categoryid'] : '';
		$categorystatus = isset($method['categorystatus']) ? $method['categorystatus'] : '';
		
		$categoryid = explode('~',$categoryid);
		$categorystatus = explode('~',$categorystatus);
		
                for($i=0;$i<sizeof($categoryid);$i++)
		{
                    $ObjDB->NonQuery("UPDATE itc_main_tag_mapping 
				                 SET fld_access='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
								 WHERE fld_tag_type='39' and fld_item_id='".$categoryid[$i]."' AND 
								 fld_tag_id IN(select fld_id FROM itc_main_tag_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0' )");	
						
                    fn_tagupdate($categorystatus[$i],39,$categoryid[$i],$uid);
                                        
		} 
                  
            echo "success";
               
	}
	catch(Exception $e){
		echo "invalid";
	}
}


@include("footer.php");