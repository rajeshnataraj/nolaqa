<?php 
	@include("sessioncheck.php");
	
	$oper = isset($method['oper']) ? $method['oper'] : '';
	
	if($oper=="managetags" and $oper != " " )
	{
		$type = isset($method['type']) ? $method['type'] : '';
		$tagids = isset($method['tagids']) ? $ObjDB->EscapeStr($method['tagids']) : '';
		$itemids = isset($method['itemids']) ? $method['itemids'] : '';
		$itemid=explode(',',$itemids);
		if($type=='add'){
			$chkid= $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_main_tag_master WHERE fld_tag_name='".$tagids."' AND fld_delstatus='0' AND fld_created_by='".$uid."'");
			if($chkid==0){
				$chkid = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_main_tag_master (fld_tag_name, fld_created_by, fld_created_date) 
													  VALUES('".$tagids."','".$uid."','".date("Y-m-d H:i:s")."')");				
				for($i=0;$i<sizeof($itemid);$i++){				
					$qry = $ObjDB->QueryObject("SELECT fld_tag_type, fld_item_id FROM itc_main_tag_mapping WHERE fld_id='".$itemid[$i]."'");
					$res = $qry->fetch_assoc();
					extract($res);
					$ObjDB->NonQuery("INSERT INTO itc_main_tag_mapping (fld_tag_id, fld_tag_type, fld_item_id, fld_access) 
									 VALUES('".$chkid."','".$fld_tag_type."','".$fld_item_id."','1')");
				}
			}
			else{
				for($i=0;$i<sizeof($itemid);$i++){				
					$qry = $ObjDB->QueryObject("SELECT fld_tag_type, fld_item_id FROM itc_main_tag_mapping WHERE fld_id='".$itemid[$i]."'");
					$res = $qry->fetch_assoc();
					extract($res);
					$chk = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
														FROM itc_main_tag_mapping 
														WHERE fld_tag_id='".$chkid."' AND fld_tag_type='".$fld_tag_type."' AND fld_item_id='".$fld_item_id."'");
					if($chk==0){
						$ObjDB->NonQuery("INSERT INTO itc_main_tag_mapping (fld_tag_id, fld_tag_type, fld_item_id, fld_access,fld_created_by, fld_created_date) 
										 VALUES('".$chkid."','".$fld_tag_type."','".$fld_item_id."','1','".$uid."','".date("Y-m-d H:i:s")."')");
					}
					else{
						$ObjDB->NonQuery("UPDATE itc_main_tag_mapping 
										 SET fld_access='1',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' 
										 WHERE fld_tag_id='".$chkid."' AND fld_tag_type='".$fld_tag_type."' AND fld_item_id='".$fld_item_id."'");
					}	
				}
			}
		}
		else if($type=='remove'){
			for($i=0;$i<sizeof($itemid);$i++){
				$qry = $ObjDB->QueryObject("SELECT fld_tag_type, fld_item_id FROM itc_main_tag_mapping WHERE fld_id='".$itemid[$i]."'");
				$res = $qry->fetch_assoc();
				extract($res);
				$ObjDB->NonQuery("UPDATE itc_main_tag_mapping SET fld_access='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_tag_id='".$tagids."' AND fld_tag_type='".$fld_tag_type."' AND fld_item_id='".$fld_item_id."'");
			}
		}
	}
	
	/*--- Check Tag Name Duplication ---*/
	if($oper=="checktagname" and $oper != " " )
	{
		$tagid = isset($method['id']) ? $method['id'] : '0';
		$tagname = isset($method['tagname']) ? fnEscapeCheck($method['tagname']) : '';
		
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
											  FROM itc_main_tag_master 
											  WHERE MD5(LCASE(REPLACE(fld_tag_name,' ','')))='".$tagname."' AND fld_delstatus='0' AND fld_id<>'".$tagid."' AND fld_created_by='".$uid."'");
		if($count == 0){ echo "true"; }	else { echo "false"; }
	}
	
	/*--- Tag Rename---*/
	if($oper=="tagrename" and $oper != " " )
	{
		$tagid = isset($method['id']) ? $method['id'] : '0';
		$tagname = isset($method['tagname']) ? $ObjDB->EscapeStr($method['tagname']) : '';
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
											  FROM itc_main_tag_master 
											  WHERE MD5(LCASE(REPLACE(fld_tag_name,' ','')))='".fnEscapeCheck($tagname)."' AND fld_delstatus='0' AND fld_id<>'".$tagid."' 
											  AND fld_created_by='".$uid."'");	
		if($count==0){	
			$ObjDB->NonQuery("UPDATE itc_main_tag_master SET fld_tag_name='".$tagname."',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_id='".$tagid."' AND fld_created_by='".$uid."'");
			echo "success";
		}
		else{
			echo "fail";
		}
	}
	
	/*--- Tag delete---*/
	if($oper=="deletetag" and $oper != " " )
	{
		$tagids = isset($method['tagids']) ? $method['tagids'] : '0';
		$ObjDB->NonQuery("UPDATE itc_main_tag_master SET fld_delstatus='1',fld_deleted_by='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$tagids."'");
		echo "success";
	}
	
	if($oper=="deleteitem" and $oper != " " )
	{
		$id = isset($method['id']) ? $method['id'] : '0';
		$tagids = isset($method['tagids']) ? $method['tagids'] : '';
		$tagid=explode(',',$tagids);
		$qry = $ObjDB->QueryObject("SELECT fld_tag_type, fld_item_id FROM itc_main_tag_mapping WHERE fld_id='".$id."'");
		$res = $qry->fetch_assoc();
		extract($res);		
		for($i=0;$i<sizeof($tagid);$i++){			
			if(!is_numeric($tagid[$i])){
				$tagid[$i] = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_main_tag_master WHERE fld_tag_name='".$tagid[$i]."' AND fld_delstatus='0' AND fld_created_by='".$uid."'");
			}			
			$ObjDB->NonQuery("UPDATE itc_main_tag_mapping SET fld_access='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_tag_id='".$tagid[$i]."' AND fld_tag_type='".$fld_tag_type."' AND fld_item_id='".$fld_item_id."'");			
		}
		echo "success";
	}
	if($oper=="changetagtype" and $oper != " " )
	{
		$id = isset($method['id']) ? $method['id'] : '0';
		$tagtype = isset($method['tagtype']) ? $method['tagtype'] : '0';
		$ObjDB->NonQuery("UPDATE itc_main_tag_master SET fld_tag_type='".$tagtype."',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_id='".$id."'");
	}

	@include("footer.php");