<?php 
	@include("sessioncheck.php");
	
	

	$date = date("Y-m-d H:i:s");
	$oper = isset($method['oper']) ? $method['oper'] : '';
	
	/*--- Save archive Class  ---*/
	
	if($oper == "savearchiveclass" and $oper != '') 
	{
             $list2 = isset($method['list2']) ? $method['list2'] : '';
	     $list2=explode(",",$list2);
		try {
				
                         $ObjDB->NonQuery("UPDATE itc_class_master set fld_archive_class ='0'
                                                                                WHERE fld_updated_by = '".$uid."'");
			if($list2[0] != '') {
                            
				for($i=0;$i<sizeof($list2);$i++)
				{
					                              
                                   
                                        $ObjDB->NonQuery("UPDATE itc_class_master set fld_archive_class ='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
                                                                                WHERE fld_id='".$list2[$i]."'");
                                        echo "success";
				}
			}

		}
		catch(Exception $e){
                echo "fail";
		}
	}	
	
	
	@include("footer.php");
