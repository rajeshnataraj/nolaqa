<?php 

	@include("sessioncheck.php");
	$oper = isset($method['oper']) ? $method['oper'] : '';
	$date=date("Y-m-d H:i:s");
	/*error_reporting(E_ALL);
	ini_set("display_errors","1");*/

if($oper == "document" and $oper !='')
{
	$documentname = isset($method['documentname']) ? $method['documentname'] : '';
	$upload = isset($method['upload']) ? $method['upload'] : '';
	$catid = isset($method['catid']) ? $method['catid'] : '';
	$proid = isset($method['proid']) ? $method['proid'] : '';
	$docid = isset($method['docid']) ? $method['docid'] : '';
	$globaldoc = isset($method['globaldoc']) ? $method['globaldoc'] : '';
	$tags = isset($method['tags']) ? $method['tags'] : '';
	$listicon = isset($method['listicon']) ? $method['listicon'] : '0';

	if($docid ==0)
	{
		$docid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_sim_document(fld_cat_id,fld_pro_id,fld_document_name,fld_upload_filename,fld_global_status,fld_created_by,fld_created_date) VALUES ('".$catid."','".$proid."','".addslashes($documentname)."','".$upload."','".$globaldoc."','".$uid."','".$date."')");
		
		fn_taginsert($tags,43,$docid,$uid);
	}
	else
	{
		$ObjDB->NonQuery("UPDATE itc_sim_document
							 SET fld_document_name='".$documentname."', fld_upload_filename='".$upload."', fld_global_status='".$globaldoc."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'
							 WHERE fld_id='".$docid."' AND fld_cat_id='".$catid."'");
		
		/*---tags------*/
		$ObjDB->NonQuery("UPDATE itc_main_tag_mapping 
						 SET fld_access='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
						 WHERE fld_tag_type='43' and fld_item_id='".$docid."' AND 
						 fld_tag_id IN(select fld_id FROM itc_main_tag_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0' )");	
		
		fn_tagupdate($tags,43,$docid,$uid);
	}
	
	$productname = $ObjDB->SelectSingleValue("SELECT fld_product_name FROM itc_sim_product WHERE fld_id='".$proid."' ANd fld_delstatus='0'");
	
	echo "success~".$docid."~".$productname."~".$listicon;
}

/*--- Delete the Items ---*/
if($oper=="deletedocument" and $oper != " " )
{
	try
	{
		$docid = isset($method['docid']) ? $method['docid'] : ''; 
		$catid = isset($method['catid']) ? $method['catid'] : '';
		$proid = isset($method['proid']) ? $method['proid'] : '';
		$listicon = isset($method['listicon']) ? $method['listicon'] : '0';
		
		$documentname = $ObjDB->SelectSingleValue("SELECT fld_document_name FROM itc_sim_document WHERE fld_id='".$docid."' And fld_delstatus='0'");
		$productname = $ObjDB->SelectSingleValue("SELECT fld_product_name FROM itc_sim_product WHERE fld_id='".$proid."' And fld_delstatus='0'");
		
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_sim_document 
											  WHERE fld_id='".$docid."' 
											  AND fld_delstatus='0'");

		if($count==1)
		{
			$ObjDB->NonQuery("UPDATE itc_sim_document 
							 SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'
							 WHERE fld_id='".$docid."'");
			echo "success~".$documentname."~".$productname."~".$listicon;
		}
		else
		{
			echo "exists";
		}
	}
	catch(Exception $e)
	{
		echo "fail";
	}
}

if($oper == "viewdoc" and $oper != '')
{
	$docid= isset($method['docid']) ? $method['docid'] : '0';
	$uploadfile = $ObjDB->SelectSingleValue("SELECT fld_upload_filename FROM itc_sim_document WHERE fld_id='".$docid."' AND  fld_delstatus='0' ");
	echo $uploadfile;
}


?>