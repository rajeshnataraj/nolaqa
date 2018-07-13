<?php 

	@include("sessioncheck.php");
	$oper = isset($method['oper']) ? $method['oper'] : '';
	$date=date("Y-m-d H:i:s");
	error_reporting(E_ALL);
	ini_set("display_errors","1");

if($oper == "category" and $oper != '')
{		
	$cid = isset($method['cid']) ? $method['cid'] : '0'; 
	$catname = isset($method['catname']) ? $method['catname'] : '';
	$catcode = isset($method['catcode']) ? $method['catcode'] : '';
	$defield = isset($method['defield']) ? $method['defield'] : '';
	$addfield = isset($method['fldval']) ? $method['fldval'] : '';
	$add = explode(",",$addfield);
	if($cid!='0' && $$cid!='undefined')
	{
		
		
	}
	else
	{
		$maxid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_sim_category(fld_category_name, fld_category_code, fld_created_by, fld_created_date) VALUES ('".$catname."','".$catcode."','".$uid."','".$date."')");
		
		if($add !='')
		{
			for($i = 0; $i <sizeof($add);$i++)
			{
				$m=$i+1;
				$ObjDB->NonQuery("INSERT INTO itc_sim_destination(fld_cat_id, fld_define_field, fld_created_by, fld_created_date,fld_field_id) VALUES ('".$maxid."','".$add[$i]."','".$uid."','".$date."','".$m."')");
			}
		}
		
		fn_taginsert($catname,40,$maxid,$maxid);
	}
	
	echo "success~".$maxid;
	
					
}

?>