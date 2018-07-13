<?php 

	@include("sessioncheck.php");
	$oper = isset($method['oper']) ? $method['oper'] : '';
	$date=date("Y-m-d H:i:s");
	error_reporting(E_ALL);
	ini_set("display_errors","1");

if($oper == "createproduct" and $oper != '')
{
	$proname = isset($method['pname']) ? $method['pname'] : '';
	$procode = isset($method['pcode']) ? $method['pcode'] : '';
	$vernumber = isset($method['vernumber']) ? $method['vernumber'] : '';
	$catid = isset($method['catid']) ? $method['catid'] : '';
	$productid = isset($method['pid']) ? $method['pid'] : '0';
	$tags = isset($method['tags']) ? $method['tags'] : '';
        
        //product insert
        $count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_nondigicontent_product WHERE fld_asset_id='".$procode."' AND fld_product_name='".$proname."' AND fld_delstatus='0'");
        
        if($count > 0)
        { 
            echo "exists"; 
        }	
        else 
        { 
            $productid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_nondigicontent_product(fld_nondigicat_id, fld_product_name, fld_asset_id, fld_version_number, fld_created_by, fld_created_date) VALUES ('".$catid."','".trim($proname)."','".trim($procode)."','".trim($vernumber)."','".$uid."','".$date."')");
            $catname = $ObjDB->SelectSingleValue("SELECT fld_category_name FROM itc_nondigicontent_category WHERE fld_id='".$catid."'");

            /*--Tags insert-----*/	
            fn_taginsert($tags,41,$productid,$uid);

            echo "success~".$productid."~".$catname;
        }
	
}

//edit product
if($oper == "editproduct" and $oper != '')
{
	$proname = isset($method['pname']) ? $method['pname'] : '';
	$procode = isset($method['pcode']) ? $method['pcode'] : '';
	$vernumber = isset($method['vernumber']) ? $method['vernumber'] : '';
	$catid = isset($method['catid']) ? $method['catid'] : '';
	$productid = isset($method['pid']) ? $method['pid'] : '0';
	$tags = isset($method['tags']) ? $method['tags'] : '';
        
        //product update code
        $ObjDB->NonQuery("UPDATE itc_nondigicontent_product
                                    SET fld_asset_id='".trim($procode)."', fld_product_name='".trim($proname)."', fld_version_number='".trim($vernumber)."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'
                                    WHERE fld_id='".$productid."' AND fld_nondigicat_id='".$catid."'");
        //get category name
        $catname = $ObjDB->SelectSingleValue("SELECT fld_category_name FROM itc_nondigicontent_category WHERE fld_id='".$catid."'");
        
        echo "success~".$productid."~".$catname;
	
}

/*--- Delete the Product ---*/
if($oper=="deleteproduct" and $oper != " " )
{
	try
	{
		$productid = isset($method['productid']) ? $method['productid'] : ''; 
		$catid = isset($method['catid']) ? $method['catid'] : '';
		
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_nondigicontent_product 
											  WHERE fld_id='".$productid."' 
											  AND fld_delstatus='0'");
		$catname = $ObjDB->SelectSingleValue("SELECT fld_category_name FROM itc_nondigicontent_category WHERE fld_id='".$catid."' AND fld_delstatus='0'");

		if($count==1)
		{
			$ObjDB->NonQuery("UPDATE itc_nondigicontent_product 
							 SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'
							 WHERE fld_id='".$productid."'");
			
			echo "success~".$catname;
		}
		else
		{
			echo "exists~";
		}
	}
	catch(Exception $e)
	{
		echo "fail~";
	}
}
