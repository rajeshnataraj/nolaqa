<?php

/*
	Created By - Karthick. S
	Page - library-expedition-ajax.php
	
	History:
*/
@include("sessioncheck.php");
	
$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';

/*--- Check Expedition Name ---*/
if($oper=="checkexpeditionname" and $oper != " " )
{
  try{
		$expeditionid = isset($method['expid']) ? $method['expid'] : '0';
		
		/**declartion for validade expeditionid***/
		$validate_expeditionid=true;
		
		if($expeditionid!=0)  $validate_expeditionid=validate_datatype($expeditionid,'int');
		$expeditionname = isset($method['txtexpname']) ? fnEscapeCheck($method['txtexpname']) : '';
		
		if($validate_expeditionid)
		{
			$count = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) from itc_exp_master 
												  WHERE MD5(LCASE(REPLACE(fld_exp_name,' ','')))='".$expeditionname."' 
												  AND fld_delstatus='0' AND fld_id<>'".$expeditionid."'");
			if($count == 0){ echo "true"; }	else { echo "false"; }
		}
		else
		{
			echo "false";
		}
  }
  catch(Exception $e)
  {
	  echo "false";
  }
}


/*--- Check Asset ID ---*/
if($oper=="checkassetid" and $oper != " " )
{
	try
	{
	$expeditionid = isset($method['eid']) ? $method['eid'] : '0';
	
	/**declartion for validade expedition id***/
		$validate_expeditionid=true;
		if($expeditionid!=0)  $validate_expeditionid=validate_datatype($expeditionid,'int');
		
		if($validate_moduleid)
		{
			$assetid = isset($method['txtassetid']) ? fnEscapeCheck($method['txtassetid']) : '0';
	 		$count = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) from itc_exp_master 
												  WHERE MD5(LCASE(REPLACE(fld_asset_id,' ','')))='".$assetid."' 
												  AND fld_delstatus='0' AND fld_id<>'".$expeditionid."'");
			 if($count == 0){ echo "true"; }	else { echo "false"; }
		}
	}
	catch(Exception $e)
	{
		echo "fail";
	}
}

/*--- Delete the Expedition ---*/
if($oper=="deleteexpedition" and $oper != " " )
{
	try
	{
		$expeditionid = isset($method['expid']) ? $method['expid'] : ''; 
		
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_license_exp_mapping 
											  WHERE fld_exp_id='".$expeditionid."' 
											  AND fld_delstatus='0' AND fld_flag='1'");
		

		if($count==0)
		{
			$ObjDB->NonQuery("UPDATE itc_exp_master 
							 SET fld_delstatus='1', fld_flag='0', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'
							 WHERE fld_id='".$expeditionid."'");
			echo "success";
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

/*--- Save and Update the Expedition ---*/
if($oper=="saveexpedition" and $oper != " " )
{
	try{		
		$expdetionid = isset($method['editid']) ? $method['editid'] : '0'; 
		$expdetionname = isset($method['expname']) ? ($method['expname']) : ''; 
		$assetid = isset($method['assetid']) ? $method['assetid'] : ''; 
		$filename = isset($method['filename']) ? $method['filename'] : ''; 
                $expuiid = isset($method['expuiid']) ? $method['expuiid'] : ''; 
                $tags = isset($method['tags']) ? $method['tags'] : '';	              
                $flag = 1;
		$expdescription = isset($_POST['expdescription']) ? $ObjDB->EscapeStr($_POST['expdescription']) : '';
                $list10 = isset($method['list10']) ? $method['list10'] : '';
                $list10=explode(",",$list10);
		
		$filename = explode('.',$filename);
		if($filename[1]=='zip')
			$filetype = "1";
		else if($filename[1]=='sbook')
			$filetype = "0";
		
		$json = json_decode(file_get_contents(__CNTPATH__.'expedition/'.$filename[0].'/output.json'), true);
		
		$expmainarray=$json[key($json)];
		$expdetionuniqueid=$expmainarray['exp_id'];
		$expdetiondesc=$expmainarray['exp_desc'];
		$expdetionversion=$expmainarray['version'];
                $expdetionstatus=$expmainarray['exp_status'];
                $expdetiontoggle=$expmainarray['exp_toggle'];
                
                $updateexpicount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_version_track WHERE fld_file_name='".$filename[0]."' AND fld_exp_id='".$expdetionid."' AND fld_delstatus='0'");
                
		if($expdetionid!='0' && $expdetionid!='undefined')
		{
			$ObjDB->NonQuery("UPDATE itc_exp_master 
							 SET fld_expunique_id='".$expdetionuniqueid."', fld_exp_name='".$ObjDB->EscapeStr($expdetionname)."', fld_exp_desc='".$expdescription."', 
                                                   fld_updated_by='".$uid."', fld_updated_date='".$date."', fld_asset_id='".$assetid."', fld_ui_id='".$expuiid."', fld_exp_status='".$expdetionstatus."', fld_toggle_status='".$expdetiontoggle."'
							 WHERE fld_id='".$expdetionid."'");
			
			$ObjDB->NonQuery("UPDATE itc_exp_version_track 
									 SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".$date."' 
									 WHERE fld_exp_id='".$expdetionid."'");
									 
			$cntversion=$ObjDB->SelectSingleValueInt("SELECT fld_id 
                                                                    FROM itc_exp_version_track 
                                                                    WHERE fld_version='".$expdetionversion."' AND fld_zip_type='".$filetype."' 
                                                                              AND fld_file_name='".$filename[0]."' AND fld_exp_id='".$expdetionid."'"); // check count
                        /*---tags update------*/
                        $ObjDB->NonQuery("UPDATE itc_main_tag_mapping 
                                         SET fld_access='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
                                                         WHERE fld_tag_type='28' and fld_item_id='".$expdetionid."' AND 
                                                         fld_tag_id IN(select fld_id FROM itc_main_tag_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0' )");	
			
                        fn_tagupdate($tags,28,$expdetionid,$uid);
			
                        if($cntversion!='0' and $cntversion!=''){				
				$ObjDB->NonQuery("UPDATE itc_exp_version_track 
								 SET fld_version='".$expdetionversion."', fld_zip_type='".$filetype."', 
									 fld_file_name='".$filename[0]."', fld_updated_by='".$uid."', fld_updated_date='".$date."', fld_delstatus='0' 
								 WHERE fld_id='".$cntversion."'"); //update already exist
			}  
			else{
				$ObjDB->NonQuery("INSERT INTO itc_exp_version_track(fld_exp_id, fld_version, fld_zip_type, fld_file_name, fld_created_by, fld_created_date) 
								VALUES('".$expdetionid."', '".$expdetionversion."', '".$filetype."', '".$filename[0]."', '".$uid."', '".$date."')"); 
			}
		}
		else
		{
			$expdetionid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_exp_master(fld_expunique_id, fld_exp_name, fld_exp_desc, fld_asset_id, fld_created_by, fld_created_date, fld_ui_id, fld_exp_status, fld_toggle_status) 
                                                                    VALUES('".$expdetionuniqueid."', '".$ObjDB->EscapeStr($expdetionname)."', '".$expdescription."', '".$assetid."', '".$uid."', '".$date."', '".$expuiid."','".$expdetionstatus."','".$expdetiontoggle."')"); 
			
			$ObjDB->NonQuery("INSERT INTO itc_exp_version_track(fld_exp_id, fld_version, fld_zip_type, fld_file_name, fld_created_by, fld_created_date) 
								VALUES('".$expdetionid."', '".$expdetionversion."', '".$filetype."', '".$filename[0]."', '".$uid."', '".$date."')"); 
                        
                        /*--Tags insert-----*/	
			fn_taginsert($tags,28,$maxid,$expdetionid);
		}
		
                if($updateexpicount == 0){
                    if($flag==1)
                    {
                        /****expedtion media array****/
                        $ObjDB->NonQuery("UPDATE itc_exp_media_master 
                                                                         SET fld_delstatus='1', fld_deleted_by='".$uid."', 
                                                                                 fld_deleted_date='".$date."' 
                                                                         WHERE fld_exp_dest_task_id='".$expdetionid."' AND fld_media_category='1'");

                        $expedtionmediaarray=$expmainarray['exp_media'];

                        if($expedtionmediaarray[key($expedtionmediaarray)]!='NA') {		
                                foreach($expedtionmediaarray as $data => $media)
                                {
                                        $medianame=$media['media_name'];
                                        $mediaid=$media['media_id'];
                                        $mediatype=$media['media_type'];
                                        $mediafiles=$media['media_file'];
                                        $mediadesc=$media['media_desc'];

                                        if($mediatype=='HTML5')
                                                $mtype = '1';
                                        else if($mediatype=='VIDEO')
                                                $mtype = '2';
                                        else if($mediatype=='AUDIO')
                                                $mtype = '3';
                                        else if($mediatype=='DOC')
                                                $mtype = '4';
                                        /*****insert query will be here ***/
                                        $ObjDB->NonQuery("INSERT INTO itc_exp_media_master(fld_exp_dest_task_id, fld_media_id, fld_media_name, fld_media_file_type, fld_media_file_name, fld_media_category, fld_media_desc, fld_created_by, fld_created_date) 
                                                                                VALUES('".$expdetionid."', '".$mediaid."', '".$ObjDB->EscapeStr($medianame)."', '".$mtype."', '".$mediafiles."', '1', '".$ObjDB->EscapeStr($mediadesc)."', '".$uid."', '".$date."')"); 
                                }
                        }

                        /*****get  destination ********/
                        $ObjDB->NonQuery("UPDATE itc_exp_destination_master 
                                                                         SET fld_delstatus='1', fld_deleted_by='".$uid."', 
                                                                                 fld_deleted_date='".$date."' 
                                                                         WHERE fld_exp_id='".$expdetionid."'");

                        $expdestionmainarray=$expmainarray['destination'];
                        $destorder = '0';

                        foreach($expdestionmainarray as $data => $des)
                        {
                                $destinationname=$des['destination_name'];
                                $destid=$des['destination_id'];
                                $destinationdesc=$des['destination_desc'];
                                $destinationstatus=$des['status'];
                                $destinationtaskarray=$des['task'];
                                $deststatus=$des['destination_status'];
                                $desttoggle=$des['destination_toggle'];
                                if($destinationstatus=='required')
                                        $status = '1';
                                else if($destinationstatus=='not required')
                                        $status = '0';

                                $nextdestorder = $destorder;
                                $nextdestorder++;
                                /*******insert destiantion details****/
                                $destaintionid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_exp_destination_master(fld_exp_id, fld_destunique_id, fld_dest_name, fld_dest_desc, fld_status, fld_created_by, fld_created_date, fld_order, fld_next_order, fld_dest_status, fld_toggle_status) 
                                                                VALUES('".$expdetionid."', '".$destid."', '".$ObjDB->EscapeStr($destinationname)."', '".$ObjDB->EscapeStr($destinationdesc)."', '".$status."', '".$uid."', '".$date."', '".$destorder."', '".$nextdestorder."','".$deststatus."','".$desttoggle."')"); 
                                $ObjDB->NonQuery("INSERT INTO itc_exp_res_status (fld_status, fld_exp_id, fld_dest_id, fld_created_by, fld_created_date) VALUES('".$deststatus."', '".$expdetionid."', '".$destaintionid."', '".$uid."', '".$date."')"); // insert destination status in res_status
                                $destorder++; 

                                $destinationmediaarray=$des['destination_media'];

                                /****save destination media details****/
                                $ObjDB->NonQuery("UPDATE itc_exp_media_master 
                                                                         SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".$date."' 
                                                                         WHERE fld_exp_dest_task_id='".$destaintionid."' AND fld_media_category='2'");

                                if($destinationmediaarray[key($destinationmediaarray)]!='NA') {							 
                                        foreach($destinationmediaarray as $data => $mediades)
                                        {
                                                $medianame=$mediades['media_name'];
                                                $mediaid=$mediades['media_id'];
                                                $mediatype=$mediades['media_type'];
                                                $mediafiles=$mediades['media_file'];

                                                if($mediatype=='HTML5')
                                                    $mtype = '1';
                                                else if($mediatype=='VIDEO')
                                                    $mtype = '2';
                                                else if($mediatype=='AUDIO')
                                                    $mtype = '3';
                                                else if($mediatype=='DOC')
                                                    $mtype = '4';
                                                /******insert query***/
                                                $ObjDB->NonQuery("INSERT INTO itc_exp_media_master(fld_exp_dest_task_id, fld_media_id, fld_media_name, fld_media_file_type, fld_media_file_name, fld_media_category, fld_media_desc, fld_created_by, fld_created_date) 
                                                                        VALUES('".$destaintionid."', '".$mediaid."', '".$ObjDB->EscapeStr($medianame)."', '".$mtype."', '".$mediafiles."', '2', '".$ObjDB->EscapeStr($mediadesc)."', '".$uid."', '".$date."')"); 
                                        }
                                }

                                /****save task of its detaintion details*****/
                                $ObjDB->NonQuery("UPDATE itc_exp_task_master 
                                                                         SET fld_delstatus='1', fld_deleted_by='".$uid."', 
                                                                                 fld_deleted_date='".$date."' 
                                                                         WHERE fld_dest_id='".$destaintionid."'");

                                $taskorder = '0';

                                foreach($destinationtaskarray as $data => $task)
                                {
                                        $taskname=$task['task_name'];
                                        $tasid=$task['task_id'];
                                        $taskdesc=$task['task_desc'];
                                        $taskstatus=$task['status'];
                                        $taskmedia=$task['task_media'];
                                        $resourcesmainarray=$task['resources'];
                                        $tasstatus=$task['task_status'];
                                        $tastoggle=$task['task_toggle'];
                                        if($taskstatus=='required')
                                                $status = '1';
                                        else if($taskstatus=='not required')
                                                $status = '0';

                                        $nexttaskorder = $taskorder;
                                        $nexttaskorder++;
                                        /******insert task details query***/
                                        $taskid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_exp_task_master(fld_exp_id,fld_dest_id, fld_task_id, fld_task_name, fld_task_desc, fld_status, fld_created_by, fld_created_date, fld_order, fld_next_order, fld_task_status, fld_toggle_status) 
                                                                                VALUES('".$expdetionid."','".$destaintionid."', '".$tasid."', '".$ObjDB->EscapeStr($taskname)."', '".$ObjDB->EscapeStr($taskdesc)."', '".$status."', '".$uid."', '".$date."', '".$taskorder."', '".$nexttaskorder."','".$tasstatus."','".$tastoggle."')"); 
                                        $ObjDB->NonQuery("INSERT INTO itc_exp_res_status (fld_status, fld_exp_id, fld_task_id, fld_created_by, fld_created_date) VALUES('".$tasstatus."', '".$expdetionid."', '".$taskid."', '".$uid."', '".$date."')"); 

                                        $taskorder++;
                                        /****save task media details****/
                                        $ObjDB->NonQuery("UPDATE itc_exp_media_master 
                                                                         SET fld_delstatus='1', fld_deleted_by='".$uid."', 
                                                                                 fld_deleted_date='".$date."' 
                                                                         WHERE fld_exp_dest_task_id='".$taskid."' AND fld_media_category='3'");
                                        if($taskmedia[key($taskmedia)]!='NA') {		
                                                foreach($taskmedia as $data => $taskmed)
                                                {
                                                        $medianame=$taskmed['media_name'];
                                                        $mediaid=$taskmed['media_id'];
                                                        $mediatype=$taskmed['media_type'];
                                                        $mediafiles=$taskmed['media_file'];

                                                        if($mediatype=='HTML5')
                                                            $mtype = '1';
                                                        else if($mediatype=='VIDEO')
                                                            $mtype = '2';
                                                        else if($mediatype=='AUDIO')
                                                            $mtype = '3';
                                                        else if($mediatype=='DOC')
                                                            $mtype = '4';
                                                        /******insert query***/
                                                        $ObjDB->NonQuery("INSERT INTO itc_exp_media_master(fld_exp_dest_task_id, fld_media_id, fld_media_name, fld_media_file_type, fld_media_file_name, fld_media_category, fld_media_desc, fld_created_by, fld_created_date) 
                                                                        VALUES('".$taskid."', '".$mediaid."', '".$ObjDB->EscapeStr($medianame)."', '".$mtype."', '".$mediafiles."', '3', '".$ObjDB->EscapeStr($mediadesc)."', '".$uid."', '".$date."')"); 
                                                }
                                        }

                                        /****save resources for its tasks*****/ 
                                        $ObjDB->NonQuery("UPDATE itc_exp_resource_master 
                                                                                 SET fld_delstatus='1', fld_deleted_by='".$uid."', 
                                                                                         fld_deleted_date='".$date."' 
                                                                                 WHERE fld_task_id='".$taskid."'");

                                        $resorder = '0';	

                                        foreach($resourcesmainarray as $data => $resources)
                                        {
                                                $resourcename=$resources['resource_name'];
                                                $resourceid=$resources['resource_id'];
                                                $resourcetype=$resources['resource_type'];
                                                $resourcefiles=$resources['resource_file'];
                                                $resourcestatus=$resources['resource_status'];
                                                $resstatus=$resources['res_status'];
                                                $restoggle=$resources['resource_toggle'];
                                                $restype=$resources['res_typeof'];
                                                $resassetid=$resources['res_assetid'];

                                                if($resourcetype=='HTML5')
                                                        $rtype = '1';
                                                else if($resourcetype=='VIDEO')
                                                        $rtype = '2';
                                                else if($resourcetype=='AUDIO')
                                                        $rtype = '3';
                                                else if($resourcetype=='DOC')
                                                        $rtype = '4';
                                                else if($resourcetype=='IMAGE')
                                                        $rtype = '5';
                                                else if($resourcetype=='LINK')
                                                        $rtype = '6';
                                                else if($resourcetype=='3RD-PARTY-LINK')
                                                        $rtype = '7';
                                                else if($resourcetype=='IPL')
                                                        $rtype = '10';
                                                else if($resourcetype=='LRESOURCE')
                                                        $rtype = '11';

                                                if($resourcestatus=='required')
                                                        $status = '1';
                                                else if($resourcestatus=='not required')
                                                        $status = '0';

                                                if($restype=='INSTRUCTIONAL')
                                                        $newrtype = '1';
                                                else if($restype=='ACTIVITY')
                                                        $newrtype = '2';

                                $nextresorder = $resorder;                                              
                                $nextresorder++;                                                
                                                
                                                $maxresid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_exp_resource_master(fld_exp_id,fld_dest_id,fld_task_id, fld_res_id, fld_expres_id, fld_res_name, fld_res_status, fld_res_file_type, fld_res_file_name, fld_created_by, fld_created_date, fld_typeof_res, fld_order, fld_next_order, fld_resource_status, fld_toggle_status) 
                                                                                        VALUES('".$expdetionid."','".$destaintionid."','".$taskid."', '".$resourceid."', '".$resassetid."', '".$ObjDB->EscapeStr($resourcename)."', '".$status."', '".$rtype."', '".$resourcefiles."', '".$uid."', '".$date."', '".$newrtype."', '".$resorder."', '".$nextresorder."','".$resstatus."','".$restoggle."')"); 
                                                $ObjDB->NonQuery("INSERT INTO itc_exp_res_status (fld_status, fld_exp_id, fld_res_id, fld_created_by, fld_created_date) VALUES('".$resstatus."', '".$expdetionid."', '".$maxresid."', '".$uid."', '".$date."')");
                                                $resorder++;
                                        }
                                }                               
                        }		
                    }
                }
                //Expeditions insert/update
                
                $ObjDB->NonQuery("UPDATE itc_license_exp_mapping 
                                               SET fld_flag='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."'  
                                               WHERE fld_exp_id='".$expdetionid."'");



                $destination=$ObjDB->NonQuery("SELECT fld_id as destid FROM itc_exp_destination_master WHERE fld_exp_id='".$expdetionid."' and fld_delstatus='0'");                 
                      
                       while($rowdestination=$destination->fetch_assoc())
                        {
                            extract($rowdestination);
                           if($list10[0] != '')
                              {

                                for($i=0;$i<sizeof($list10);$i++)
                                    {
                                       

                                        $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                                    FROM itc_license_exp_mapping 
                                                                                    WHERE fld_license_id='".$list10[$i]."' AND fld_exp_id='".$expdetionid."' AND fld_dest_id='".$destid."'");



                                       if($cnt==0)
                                        {

                                                 $ObjDB->NonQuery("INSERT INTO itc_license_exp_mapping (fld_license_id,fld_exp_id,fld_flag,fld_dest_id, fld_created_by, fld_created_date)
                                                                                        VALUES('".$list10[$i]."','".$expdetionid."','1','".$destid."', '".$uid."', '".$date."')");

                                        }
                                        else
                                        {
                                             
                                                $ObjDB->NonQuery("UPDATE itc_license_exp_mapping 
                                                                                        SET fld_flag='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."'  
                                                                                        WHERE fld_license_id='".$list10[$i]."' AND fld_exp_id='".$expdetionid."' AND fld_dest_id='".$destid."'");
                                         }
                                     } 
                              }
                          }
                echo "success";
	}
	catch(Exception $e)
	{
		echo "fail";
	}
}

/*--- Load Version dropdown for Expedition ---*/
if($oper=="expeditionversion" and $oper != " ")
{
	$expid = isset($method['expid']) ? $method['expid'] : '';
	$currentversion = $ObjDB->SelectSingleValue("SELECT MAX(FORMAT(fld_version,1)) 
	                                            FROM itc_exp_version_track WHERE fld_exp_id='".$expid."'");
	$newversion = $currentversion+0.1;
	?>        
	<input type="hidden" name="selectversion" class="required" id="selectversion" value="<?php echo number_format($newversion,1);?>">
	<a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#" style="width:110px;">
		<span class="selectbox-option input-medium" data-option="<?php echo number_format($newversion,1);?>" style="width:100px;">Version <?php echo number_format($newversion,1);?></span>
		<b class="caret1"></b>
	</a>
	<div class="selectbox-options" style="min-width: 118px;">			    
		<ul role="options" style="width:118px;">
		<?php $qry = $ObjDB->QueryObject("select fld_version from itc_exp_version_track where fld_exp_id='".$expid."'");
			while($res = $qry->fetch_object()){?>
			   <li><a  href="#" data-option="<?php echo $res->fld_version; ?>" onclick="fn_changemodulename(<?php echo number_format($res->fld_version,1);?>)">Version <?php echo number_format($res->fld_version,1); ?></a></li>
			<?php }?>
		</ul>
	</div>
	<?php
}

/*--- Change the expedition name according to version ---*/
if($oper=="changeexpeditionname" and $oper != " ")
{
	$expeditionname = isset($method['expname']) ? fnEscapeCheck($method['expname']) : '';
	$expeditionid = isset($method['expid']) ? $method['expid'] : '';
	$version = isset($method['expversion']) ? $method['expversion'] : '';
	
	$count = $ObjDB->SelectSingleValueInt("SELECT count(a.fld_id) FROM itc_exp_version_track AS a 
									  LEFT JOIN itc_exp_master AS b ON a.fld_exp_id=b.fld_id 
									  WHERE MD5(LCASE(REPLACE(b.fld_exp_name,' ','')))='".$expeditionname."' 
										  AND b.fld_delstatus='0' AND a.fld_version='".$version."' 
										  AND b.fld_id<>'".$expeditionid."'");
	
	if($count>0)
		echo "fail";
	else if($count==0)
		echo "success";
}

if($oper == "saveorder" and $oper != '')
{		
	try{
		$destid = isset($method['destid']) ? $method['destid'] : '';
		$nextdestid = isset($method['nextdestid']) ? $method['nextdestid'] : '';
		$taskid = isset($method['taskid']) ? $method['taskid'] : '';
		$nexttaskid = isset($method['nexttaskid']) ? $method['nexttaskid'] : '';
		$resid = isset($method['resid']) ? $method['resid'] : '';
		$nextresid = isset($method['nextresid']) ? $method['nextresid'] : '';	
		
		$destid = explode('~',$destid);
		$nextdestid = explode('~',$nextdestid);
		$taskid = explode('~',$taskid);
		$nexttaskid = explode('~',$nexttaskid);
		$resid = explode('~',$resid);
		$nextresid = explode('~',$nextresid);
		
		for($i=0;$i<sizeof($destid);$i++)
		{
			$ObjDB->NonQuery("UPDATE itc_exp_destination_master SET fld_next_order='".$nextdestid[$i]."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$destid[$i]."'");
		}
		
		for($i=0;$i<sizeof($taskid);$i++)
		{
			$ObjDB->NonQuery("UPDATE itc_exp_task_master SET fld_next_order='".$nexttaskid[$i]."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$taskid[$i]."'");
		}
		
		for($i=0;$i<sizeof($resid);$i++)
		{
			$ObjDB->NonQuery("UPDATE itc_exp_resource_master SET fld_next_order='".$nextresid[$i]."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$resid[$i]."'");
		}
		echo "success";		
	}
	catch(Exception $e){
		echo "invalid";
	}
}

// Toggling Status of Expedition Components
if($oper == "savestatus" and $oper != '')
{		
	try{
                $expid = isset($method['expid']) ? $method['expid'] : '';
		$destid = isset($method['destid']) ? $method['destid'] : '';
		$deststatus = isset($method['deststatus']) ? $method['deststatus'] : '';
		$taskid = isset($method['taskid']) ? $method['taskid'] : '';
		$taskstatus = isset($method['taskstatus']) ? $method['taskstatus'] : '';
		$resid = isset($method['resid']) ? $method['resid'] : '';
		$resstatus = isset($method['resstatus']) ? $method['resstatus'] : '';	
		
		$destid = explode('~',$destid);
		$deststatus = explode('~',$deststatus);
		$taskid = explode('~',$taskid);
		$taskstatus = explode('~',$taskstatus);
		$resid = explode('~',$resid);
		$resstatus = explode('~',$resstatus);

		// Toggling Destinations
		for($i=0;$i<sizeof($destid);$i++)
		{
            // PITSCO ADMIN
            if($sessmasterprfid==2){
                $destfield = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_dest_id='".$destid[$i]."' AND fld_school_id='0' AND fld_profile_id='2' AND fld_user_id='0'");
                if($destfield!='' and $destfield!='0')
                {
                    $ObjDB->NonQuery("UPDATE itc_exp_res_status SET fld_flag='1', fld_status='".$deststatus[$i]."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$destfield."'");

                    if($sessprofileid =='2'){
                        if($deststatus[$i] =='3'){

                            $ObjDB->NonQuery("UPDATE itc_exp_destination_master SET fld_toggle_status='1', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$destid[$i]."'");
                            $ObjDB->NonQuery("UPDATE itc_exp_res_status SET fld_status='".$deststatus[$i]."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_dest_id='".$destid[$i]."'");
                        }
                        else{
                            $togglests = $ObjDB->SelectSingleValueInt("SELECT fld_toggle_status FROM itc_exp_destination_master WHERE fld_id='".$destid[$i]."'");
                            if($togglests =='1'){
                                $ObjDB->NonQuery("UPDATE itc_exp_destination_master SET fld_toggle_status='2', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$destid[$i]."'");
                                $ObjDB->NonQuery("UPDATE itc_exp_res_status SET fld_status='".$deststatus[$i]."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_dest_id='".$destid[$i]."'");
                            }
                        }
                    }
                }
                else
                {
                    $ObjDB->NonQuery("INSERT INTO itc_exp_res_status (fld_status, fld_exp_id, fld_dest_id, fld_flag, fld_created_by, fld_created_date, fld_school_id, fld_user_id,fld_profile_id) VALUES('".$deststatus[$i]."', '".$expid."', '".$destid[$i]."', '1', '".$uid."', '".date("Y-m-d H:i:s")."','".$schoolid."','".$indid."','".$sessmasterprfid."')");
                }
            }

            // All other users
            else{
                $destfield = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_dest_id='".$destid[$i]."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                if($destfield!='' and $destfield!='0')
                {
                    $ObjDB->NonQuery("UPDATE itc_exp_res_status SET fld_flag='1', fld_status='".$deststatus[$i]."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$destfield."'");
                    if($sessprofileid =='2'){
                        if($deststatus[$i] =='3'){

                            $ObjDB->NonQuery("UPDATE itc_exp_destination_master SET fld_toggle_status='1', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$destid[$i]."'");
                            $ObjDB->NonQuery("UPDATE itc_exp_res_status SET fld_status='".$deststatus[$i]."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_dest_id='".$destid[$i]."'");
                }
                        else{
                            $togglests = $ObjDB->SelectSingleValueInt("SELECT fld_toggle_status FROM itc_exp_destination_master WHERE fld_id='".$destid[$i]."'");
                            if($togglests =='1'){
                                $ObjDB->NonQuery("UPDATE itc_exp_destination_master SET fld_toggle_status='2', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$destid[$i]."'");
                                $ObjDB->NonQuery("UPDATE itc_exp_res_status SET fld_status='".$deststatus[$i]."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_dest_id='".$destid[$i]."'");
                            }
                        }
                    }
                }
                else
                {
                    $ObjDB->NonQuery("INSERT INTO itc_exp_res_status (fld_status, fld_exp_id, fld_dest_id, fld_flag, fld_created_by, fld_created_date, fld_school_id, fld_user_id,fld_profile_id) VALUES('".$deststatus[$i]."', '".$expid."', '".$destid[$i]."', '1', '".$uid."', '".date("Y-m-d H:i:s")."','".$schoolid."','".$indid."','".$sessmasterprfid."')");
                }

            }
		}

        // Toggling Tasks
		for($i=0;$i<sizeof($taskid);$i++)
		{
            // PITSCO ADMIN
            if($sessmasterprfid==2){
                $taskfield = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_task_id='".$taskid[$i]."' AND fld_school_id='0' AND fld_profile_id='2' AND fld_user_id='0'");
                if($taskfield!='' and $taskfield!='0')
                {
                    $ObjDB->NonQuery("UPDATE itc_exp_res_status SET fld_flag='1', fld_status='".$taskstatus[$i]."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$taskfield."'");
                    if($sessprofileid =='2'){
                        if($taskstatus[$i] =='3'){

                            $ObjDB->NonQuery("UPDATE itc_exp_task_master SET fld_toggle_status='1', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$taskid[$i]."'");
                            $ObjDB->NonQuery("UPDATE itc_exp_res_status SET fld_status='".$taskstatus[$i]."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_task_id='".$taskid[$i]."'");
                }
                        else{
                            $togglestst = $ObjDB->SelectSingleValueInt("SELECT fld_toggle_status FROM itc_exp_task_master WHERE fld_id='".$taskid[$i]."'");
                            if($togglestst =='1'){
                                $ObjDB->NonQuery("UPDATE itc_exp_task_master SET fld_toggle_status='2', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$taskid[$i]."'");
                                $ObjDB->NonQuery("UPDATE itc_exp_res_status SET fld_status='".$taskstatus[$i]."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_task_id='".$taskid[$i]."'");
                            }
                        }
                    }
                }
                else
                {
                    $ObjDB->NonQuery("INSERT INTO itc_exp_res_status (fld_status, fld_exp_id, fld_task_id, fld_flag, fld_created_by, fld_created_date, fld_school_id, fld_user_id,fld_profile_id) VALUES('".$taskstatus[$i]."', '".$expid."', '".$taskid[$i]."', '1', '".$uid."', '".date("Y-m-d H:i:s")."','".$schoolid."','".$indid."','".$sessmasterprfid."')");
                }

            }

            // All other users
            else{
                $taskfield = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_task_id='".$taskid[$i]."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                if($taskfield!='' and $taskfield!='0')
                {
                    $ObjDB->NonQuery("UPDATE itc_exp_res_status SET fld_flag='1', fld_status='".$taskstatus[$i]."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$taskfield."'");
                    if($sessprofileid =='2'){
                        if($taskstatus[$i] =='3'){

                            $ObjDB->NonQuery("UPDATE itc_exp_task_master SET fld_toggle_status='1', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$taskid[$i]."'");
                            $ObjDB->NonQuery("UPDATE itc_exp_res_status SET fld_status='".$taskstatus[$i]."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_task_id='".$taskid[$i]."'");
                }
                        else{
                            $togglestst = $ObjDB->SelectSingleValueInt("SELECT fld_toggle_status FROM itc_exp_task_master WHERE fld_id='".$taskid[$i]."'");
                            if($togglestst =='1'){
                                $ObjDB->NonQuery("UPDATE itc_exp_task_master SET fld_toggle_status='2', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$taskid[$i]."'");
                                $ObjDB->NonQuery("UPDATE itc_exp_res_status SET fld_status='".$taskstatus[$i]."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_task_id='".$taskid[$i]."'");
                            }
                        }
                    }
                }
                else
                {
                    $ObjDB->NonQuery("INSERT INTO itc_exp_res_status (fld_status, fld_exp_id, fld_task_id, fld_flag, fld_created_by, fld_created_date, fld_school_id, fld_user_id,fld_profile_id) VALUES('".$taskstatus[$i]."', '".$expid."', '".$taskid[$i]."', '1', '".$uid."', '".date("Y-m-d H:i:s")."','".$schoolid."','".$indid."','".$sessmasterprfid."')");
                }
            }
                    
		}

        // Toggling Resources
		for($i=0;$i<sizeof($resid);$i++)
		{
            // PITSCO ADMIN
            if($sessmasterprfid==2){
                $resfield = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_res_id='".$resid[$i]."' AND fld_school_id='0' AND fld_profile_id='2' AND fld_user_id='0'");
                if($resfield!='' and $resfield!='0')
                {
                    $ObjDB->NonQuery("UPDATE itc_exp_res_status SET fld_flag='1', fld_status='".$resstatus[$i]."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$resfield."'");

                    if($sessprofileid =='2'){
                        if($resstatus[$i] =='3'){

                            $ObjDB->NonQuery("UPDATE itc_exp_resource_master SET fld_toggle_status='1', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$resid[$i]."'");
                            $ObjDB->NonQuery("UPDATE itc_exp_res_status SET fld_status='".$resstatus[$i]."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_res_id='".$resid[$i]."'");
                        }
                        else{
                            $togglestsr = $ObjDB->SelectSingleValueInt("SELECT fld_toggle_status FROM itc_exp_resource_master WHERE fld_id='".$resid[$i]."'");
                            if($togglestsr =='1'){
                                $ObjDB->NonQuery("UPDATE itc_exp_resource_master SET fld_toggle_status='2', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$resid[$i]."'");
                                $ObjDB->NonQuery("UPDATE itc_exp_res_status SET fld_status='".$resstatus[$i]."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_res_id='".$resid[$i]."'");
                            }
                        }
                    }

                    $rtaskid = $ObjDB->SelectSingleValue("SELECT fld_task_id FROM itc_exp_resource_master WHERE fld_id='".$resid[$i]."'");
                    $rdestid = $ObjDB->SelectSingleValue("SELECT fld_dest_id FROM itc_exp_task_master WHERE fld_id='".$rtaskid."'");
                    $qrystudentid = $ObjDB->QueryObject("SELECT a.fld_student_id AS studentids
                                                        FROM itc_exp_res_play_track as a 
                                                        JOIN itc_user_master as b 
                                                        on a.fld_student_id=b.fld_id 
                                                        Join itc_exp_res_status as c 
                                                        on b.fld_school_id=c.fld_school_id 
                                                        WHERE c.fld_res_id='".$resid[$i]."' AND c.fld_created_by='".$uid."'
                                                        GROUP BY a.fld_student_id");
                    if($qrystudentid->num_rows > 0){
                        while ($rowstudentid = $qrystudentid->fetch_assoc()) {
                            extract($rowstudentid);
                                if($resstatus[$i] == 1){ //optional to required from toggle status
                                $chkcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id='".$resid[$i]."' AND fld_student_id='".$studentids."'");
                                if($chkcount == 0){
                                    $ObjDB->NonQuery("UPDATE itc_exp_task_play_track SET fld_read_status='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_task_id='".$rtaskid."' AND fld_student_id='".$studentids."'");
                                    $ObjDB->NonQuery("UPDATE itc_exp_dest_play_track SET fld_read_status='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_dest_id='".$rdestid."' AND fld_student_id='".$studentids."'");
                                }
                            }// resstatus if ends
                            if($resstatus[$i] == 2){
                                $chkcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id='".$resid[$i]."' AND fld_read_status='0' AND fld_student_id='".$studentids."'");
                                if($chkcount == 1){
                                    $ObjDB->NonQuery("UPDATE itc_exp_task_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_task_id='".$rtaskid."' AND fld_student_id='".$studentids."'");
                                    $ObjDB->NonQuery("UPDATE itc_exp_dest_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_dest_id='".$rdestid."' AND fld_student_id='".$studentids."'");
                                    $ObjDB->NonQuery("DELETE FROM itc_exp_res_play_track  WHERE fld_res_id='".$resid[$i]."' AND fld_read_status='0' AND fld_student_id='".$studentids."'");
                                }
                            }
                            if($resstatus[$i] == 3){
                                $chkcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id='".$resid[$i]."' AND fld_read_status='0' AND fld_student_id='".$studentids."'");
                                if($chkcount == 1){
                                    $ObjDB->NonQuery("UPDATE itc_exp_task_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_task_id='".$rtaskid."' AND fld_student_id='".$studentids."'");
                                    $ObjDB->NonQuery("UPDATE itc_exp_dest_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_dest_id='".$rdestid."' AND fld_student_id='".$studentids."'");
                                    $ObjDB->NonQuery("DELETE FROM itc_exp_res_play_track  WHERE fld_res_id='".$resid[$i]."' AND fld_read_status='0' AND fld_student_id='".$studentids."'");
                                }
                            }
                        }// while ends
                    } // main if ends
                }
                else
                {
                    $ObjDB->NonQuery("INSERT INTO itc_exp_res_status (fld_status, fld_exp_id, fld_res_id, fld_flag, fld_created_by, fld_created_date, fld_school_id, fld_user_id,fld_profile_id) VALUES('".$resstatus[$i]."', '".$expid."', '".$resid[$i]."', '1', '".$uid."', '".date("Y-m-d H:i:s")."','".$schoolid."','".$indid."','".$sessmasterprfid."')");
                }
            }

            // All other users
            else{
                $resfield = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_res_id='".$resid[$i]."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                if($resfield!='' and $resfield!='0')
                {
                    $ObjDB->NonQuery("UPDATE itc_exp_res_status SET fld_flag='1', fld_status='".$resstatus[$i]."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$resfield."'");

                    if($sessprofileid =='2'){
                        if($resstatus[$i] =='3'){

                            $ObjDB->NonQuery("UPDATE itc_exp_resource_master SET fld_toggle_status='1', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$resid[$i]."'");
                            $ObjDB->NonQuery("UPDATE itc_exp_res_status SET fld_status='".$resstatus[$i]."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_res_id='".$resid[$i]."'");
                        }
                        else{
                            $togglestsr = $ObjDB->SelectSingleValueInt("SELECT fld_toggle_status FROM itc_exp_resource_master WHERE fld_id='".$resid[$i]."'");
                            if($togglestsr =='1'){
                                $ObjDB->NonQuery("UPDATE itc_exp_resource_master SET fld_toggle_status='2', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$resid[$i]."'");
                                $ObjDB->NonQuery("UPDATE itc_exp_res_status SET fld_status='".$resstatus[$i]."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_res_id='".$resid[$i]."'");
                            }
                        }
                    }

                    $rtaskid = $ObjDB->SelectSingleValue("SELECT fld_task_id FROM itc_exp_resource_master WHERE fld_id='".$resid[$i]."'");
                    $rdestid = $ObjDB->SelectSingleValue("SELECT fld_dest_id FROM itc_exp_task_master WHERE fld_id='".$rtaskid."'");
                    $qrystudentid = $ObjDB->QueryObject("SELECT a.fld_student_id AS studentids
                                                        FROM itc_exp_res_play_track as a 
                                                        JOIN itc_user_master as b 
                                                        on a.fld_student_id=b.fld_id 
                                                        Join itc_exp_res_status as c 
                                                        on b.fld_school_id=c.fld_school_id 
                                                        WHERE c.fld_res_id='".$resid[$i]."' AND c.fld_created_by='".$uid."'
                                                        GROUP BY a.fld_student_id");
                    if($qrystudentid->num_rows > 0){
                        while ($rowstudentid = $qrystudentid->fetch_assoc()) {
                            extract($rowstudentid);
                                if($resstatus[$i] == 1){ //optional to required from toggle status
                                $chkcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id='".$resid[$i]."' AND fld_student_id='".$studentids."'");
                                if($chkcount == 0){
                                    $ObjDB->NonQuery("UPDATE itc_exp_task_play_track SET fld_read_status='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_task_id='".$rtaskid."' AND fld_student_id='".$studentids."'");
                                    $ObjDB->NonQuery("UPDATE itc_exp_dest_play_track SET fld_read_status='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_dest_id='".$rdestid."' AND fld_student_id='".$studentids."'");
                                }
                            }// resstatus if ends
                            if($resstatus[$i] == 2){
                                $chkcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id='".$resid[$i]."' AND fld_read_status='0' AND fld_student_id='".$studentids."'");
                                if($chkcount == 1){
                                    $ObjDB->NonQuery("UPDATE itc_exp_task_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_task_id='".$rtaskid."' AND fld_student_id='".$studentids."'");
                                    $ObjDB->NonQuery("UPDATE itc_exp_dest_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_dest_id='".$rdestid."' AND fld_student_id='".$studentids."'");
                                    $ObjDB->NonQuery("DELETE FROM itc_exp_res_play_track  WHERE fld_res_id='".$resid[$i]."' AND fld_read_status='0' AND fld_student_id='".$studentids."'");
                                }
                            }
                            if($resstatus[$i] == 3){
                                $chkcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id='".$resid[$i]."' AND fld_read_status='0' AND fld_student_id='".$studentids."'");
                                if($chkcount == 1){
                                    $ObjDB->NonQuery("UPDATE itc_exp_task_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_task_id='".$rtaskid."' AND fld_student_id='".$studentids."'");
                                    $ObjDB->NonQuery("UPDATE itc_exp_dest_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_dest_id='".$rdestid."' AND fld_student_id='".$studentids."'");
                                    $ObjDB->NonQuery("DELETE FROM itc_exp_res_play_track  WHERE fld_res_id='".$resid[$i]."' AND fld_read_status='0' AND fld_student_id='".$studentids."'");
                                }
                            }
                        }// while ends
                    } // main if ends
                }
                else
                {
                    $ObjDB->NonQuery("INSERT INTO itc_exp_res_status (fld_status, fld_exp_id, fld_res_id, fld_flag, fld_created_by, fld_created_date, fld_school_id, fld_user_id,fld_profile_id) VALUES('".$resstatus[$i]."', '".$expid."', '".$resid[$i]."', '1', '".$uid."', '".date("Y-m-d H:i:s")."','".$schoolid."','".$indid."','".$sessmasterprfid."')");
                }

            }

		}
		echo "success";		
	}
	catch(Exception $e){
		echo "invalid";
	}
}


if($oper == "resetdft" and $oper != '')
{		
	try{
            $expid = isset($method['expid']) ? $method['expid'] : '';
            $uid = isset($method['uid']) ? $method['uid'] : '';
            $schoolid = isset($method['schid']) ? $method['schid'] : '';
            $indid = isset($method['indid']) ? $method['indid'] : '';
            $qrydet = $ObjDB->QueryObject("SELECT fld_exp_id, fld_dest_id, fld_task_id, fld_res_id, fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_profile_id='2' AND fld_school_id='0' AND fld_user_id='0'");

            if($qrydet->num_rows>0) {
                while($rowqrydet = $qrydet->fetch_assoc()){
                        extract($rowqrydet);
                        $ObjDB->NonQuery("UPDATE itc_exp_res_status SET fld_status='".$fld_status."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_dest_id='".$fld_dest_id."' and fld_task_id='".$fld_task_id."' and fld_res_id='".$fld_res_id."' and fld_school_id='".$schoolid."' and fld_created_by='".$uid."' and fld_exp_id='".$expid."' AND fld_user_id='".$indid."'");
                        
                }
            }
             echo "success";	
            	
	}
	catch(Exception $e){
		echo "invalid";
	}
}



if($oper == "savetoggleassesment" and $oper != '')
{		
	try{
                $expid = isset($method['expid']) ? $method['expid'] : '';
                $expeditionid = isset($method['expeditionid']) ? $method['expeditionid'] : '';
		$expstatus = isset($method['expstatus']) ? $method['expstatus'] : '';	
                $destid = isset($method['destid']) ? $method['destid'] : '';
		$deststatus = isset($method['deststatus']) ? $method['deststatus'] : '';
		$taskid = isset($method['taskid']) ? $method['taskid'] : '';
		$taskstatus = isset($method['taskstatus']) ? $method['taskstatus'] : '';
		$resid = isset($method['resid']) ? $method['resid'] : '';
		$resstatus = isset($method['resstatus']) ? $method['resstatus'] : '';	
		
                $expeditionid = explode('~',$expeditionid);
		$expstatus = explode('~',$expstatus);
		$destid = explode('~',$destid);
		$deststatus = explode('~',$deststatus);
		$taskid = explode('~',$taskid);
		$taskstatus = explode('~',$taskstatus);
		$resid = explode('~',$resid);
		$resstatus = explode('~',$resstatus);
                
                
                
                for($i=0;$i<sizeof($expeditionid);$i++)
		{
                    $exptestid=explode('_',$expeditionid[$i]);
                    
                    $exppretestid = $ObjDB->SelectSingleValueInt("select a.fld_id from itc_test_master as a
                                                                left join itc_exptest_toogle as b on a.fld_id = b.fld_exptestid 
                                                                where a.fld_destid ='0' and a.fld_prepostid ='1' and b.fld_tprepost ='1' and b.fld_flag=1 and b.fld_texpid='".$expid."'
                                                                and b.fld_ttaskid='0' and b.fld_tresid='0' and b.fld_tdestid='0' and b.fld_created_by='".$uid."' and b.fld_status ='1' and a.fld_delstatus ='0'");
                    
                    $expfield = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exptest_toogle WHERE fld_texpid='".$expid."'  AND fld_exptestid='".$exptestid[0]."' AND fld_created_by='".$uid."'");
                    
                    
                    if($expfield!='' and $expfield!='0')
                    {
                       $ObjDB->NonQuery("UPDATE itc_exptest_toogle SET fld_flag='1', fld_status='".$expstatus[$i]."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_exptestid='".$exptestid[0]."'");
                    }
                    
                    $qrystudentide = $ObjDB->QueryObject("SELECT a.fld_student_id AS studentids
                                                        FROM itc_exp_res_play_track as a 
                                                        JOIN itc_user_master as b 
                                                        on a.fld_student_id=b.fld_id 
                                                        Join itc_exptest_toogle as c 
                                                        on b.fld_school_id=c.fld_school_id 
                                                        WHERE c.fld_texpid='".$expid."'
                                                        GROUP BY a.fld_student_id");
                    if($qrystudentide->num_rows > 0){
                        while ($rowstudentide = $qrystudentide->fetch_assoc()) {
                        extract($rowstudentide);
                        $exptesttype = $ObjDB->SelectSingleValueInt("SELECT fld_tprepost FROM itc_exptest_toogle WHERE fld_texpid='".$expid."'  AND fld_exptestid='".$exptestid[0]."' AND fld_status='1' AND fld_created_by='".$uid."'");
                            if($exptesttype == '1'){
                                $destfirstid = $ObjDB->SelectSingleValue("SELECT a.fld_id 
                                                        FROM itc_exp_destination_master As a
                                                        LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_dest_id
                                                        WHERE a.fld_exp_id='".$expid."' and b.fld_created_by='".$uid."' AND a.fld_delstatus='0' AND b.fld_school_id='".$schoolid."' AND b.fld_user_id='".$indid."' AND (b.fld_status='1') order by a.fld_id asc");
                                $chkcounte = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_testplay_track WHERE fld_exp_test_id='".$exptestid[0]."' AND fld_student_id='".$studentids."'");
                                if($chkcounte =='0'){
                                    $ObjDB->NonQuery("UPDATE itc_exp_dest_play_track SET fld_read_status='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_dest_id='".$destfirstid."' AND fld_student_id='".$studentids."'");
                                }
                            }
                            if($exptesttype =='2'){
                                $destlastid = $ObjDB->SelectSingleValue("SELECT a.fld_id 
                                                        FROM itc_exp_destination_master As a
                                                        LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_dest_id
                                                        WHERE a.fld_exp_id='".$expid."' AND a.fld_delstatus='0' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$uid."' AND b.fld_user_id='".$indid."' AND (b.fld_status='1') order by a.fld_id desc");
                                $chkcounte = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_testplay_track WHERE fld_exp_test_id='".$exptestid[0]."' AND fld_student_id='".$studentids."'");
                                if($chkcounte =='0'){
                                    $ObjDB->NonQuery("UPDATE itc_exp_dest_play_track SET fld_read_status='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_dest_id='".$destlastid."' AND fld_student_id='".$studentids."'");
                                }
                            }
                        }
                    }
                }
                
                
		for($i=0;$i<sizeof($destid);$i++)
		{
                    $destestid=explode('_',$destid[$i]);
                    
                    $destfield = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exptest_toogle WHERE fld_texpid='".$expid."' AND fld_tdestid='".$destestid[1]."'  AND fld_exptestid='".$destestid[0]."' AND fld_created_by='".$uid."'");
                    
                    
                    if($destfield!='' and $destfield!='0')
                    {
                       $ObjDB->NonQuery("UPDATE itc_exptest_toogle SET fld_flag='1', fld_status='".$deststatus[$i]."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_exptestid='".$destestid[0]."'");
                    }
                    
                    $qrystudentiddest = $ObjDB->QueryObject("SELECT a.fld_student_id AS studentids
                                                            FROM itc_exp_res_play_track as a 
                                                            JOIN itc_user_master as b 
                                                            on a.fld_student_id=b.fld_id 
                                                            Join itc_exptest_toogle as c 
                                                            on b.fld_school_id=c.fld_school_id 
                                                            WHERE c.fld_texpid='".$expid."'
                                                            GROUP BY a.fld_student_id");   
                        
                    if($qrystudentiddest->num_rows > 0){
                        while ($rowstudentiddest = $qrystudentiddest->fetch_assoc()) {
                            extract($rowstudentiddest);
                            if($deststatus[$i] =='1'){ //optional to required from toggle status
                                $chkcountdest = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_dest_testplay_track WHERE fld_exp_id='".$expid."' AND fld_dest_id='".$destestid[1]."' AND fld_dest_test_id='".$destestid[0]."' AND fld_student_id='".$studentids."'");
                                if($chkcountdest =='0'){
                                    $ObjDB->NonQuery("UPDATE itc_exp_dest_play_track SET fld_read_status='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_dest_id='".$destestid[1]."' AND fld_student_id='".$studentids."'");
                                }

                            }
                        }
                    }
                }
                
		
		for($i=0;$i<sizeof($taskid);$i++)
		{
                    $tasktestid=explode('_',$taskid[$i]);
                    
                    $taskfield = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exptest_toogle WHERE fld_texpid='".$expid."' AND fld_ttaskid='".$tasktestid[2]."'  AND fld_exptestid='".$tasktestid[0]."' AND fld_created_by='".$uid."'");
                    
                    if($taskfield!='' and $taskfield!='0')
                    {
			$ObjDB->NonQuery("UPDATE itc_exptest_toogle SET fld_flag='1', fld_status='".$taskstatus[$i]."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_exptestid='".$tasktestid[0]."'");
                    }
                    $qrystudentidtask = $ObjDB->QueryObject("SELECT a.fld_student_id AS studentids
                                                            FROM itc_exp_res_play_track as a 
                                                            JOIN itc_user_master as b 
                                                            on a.fld_student_id=b.fld_id 
                                                            Join itc_exptest_toogle as c 
                                                            on b.fld_school_id=c.fld_school_id 
                                                            WHERE c.fld_texpid='".$expid."'
                                                            GROUP BY a.fld_student_id"); 
                    if($qrystudentidtask->num_rows > 0){
                        while ($rowstudentidtask = $qrystudentidtask->fetch_assoc()) {
                            extract($rowstudentidtask);
                            if($taskstatus[$i] =='1'){ //optional to required from toggle status
                                $chkcounttask = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_task_testplay_track WHERE fld_exp_id='".$expid."' AND fld_dest_id='".$tasktestid[1]."' AND fld_task_id='".$tasktestid[2]."' AND fld_task_test_id='".$tasktestid[0]."' AND fld_student_id='".$studentids."'");
                                if($chkcounttask =='0'){
                                    $ObjDB->NonQuery("UPDATE itc_exp_dest_play_track SET fld_read_status='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_dest_id='".$tasktestid[1]."' AND fld_student_id='".$studentids."'");
                                }

                            }
                        }
                    }
                    
		}
                
                for($i=0;$i<sizeof($resid);$i++)
		{
                    $restestid=explode('_',$resid[$i]);
                    
                    $resourcefield = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_exptest_toogle WHERE fld_texpid='".$expid."' AND fld_tresid='".$restestid[1]."'  AND fld_exptestid='".$restestid[0]."' AND fld_created_by='".$uid."'");
                    
                    if($resourcefield!='' and $resourcefield!='0')
                    {
			$ObjDB->NonQuery("UPDATE itc_exptest_toogle SET fld_flag='1', fld_status='".$resstatus[$i]."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_exptestid='".$restestid[0]."'");
                    }
                    
                    $qrystudentidres = $ObjDB->QueryObject("SELECT a.fld_student_id AS studentids
                                                            FROM itc_exp_res_play_track as a 
                                                            JOIN itc_user_master as b 
                                                            on a.fld_student_id=b.fld_id 
                                                            Join itc_exptest_toogle as c 
                                                            on b.fld_school_id=c.fld_school_id 
                                                            WHERE c.fld_texpid='".$expid."'
                                                            GROUP BY a.fld_student_id"); 
                    if($qrystudentidres->num_rows > 0){
                        while ($rowstudentidres = $qrystudentidres->fetch_assoc()) {
                            extract($rowstudentidres);
                            
                            if($resstatus[$i] =='1'){ //optional to required from toggle status
                                $chkcountres = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_testplay_track WHERE fld_exp_id='".$expid."' AND fld_res_id='".$restestid[1]."' AND fld_res_test_id='".$restestid[0]."' AND fld_student_id='".$studentids."'");
                                $rstatus = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' AND fld_res_id='".$restestid[1]."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                                if($chkcountres =='0' and $rstatus =='1'){
                                    $ObjDB->NonQuery("UPDATE itc_exp_task_play_track SET fld_read_status='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_task_id='".$restestid[2]."' AND fld_student_id='".$studentids."'");
                                    $ObjDB->NonQuery("UPDATE itc_exp_dest_play_track SET fld_read_status='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_dest_id='".$restestid[3]."' AND fld_student_id='".$studentids."'");
                                }

                            }
                        }
                    }
                    
		}
		
		
		echo "success";		
	}
	catch(Exception $e){
		echo "invalid";
	}
}

if($oper=="resstatus" and $oper != " " )
{
        $id = isset($method['id']) ? $method['id'] : '0';
        $type = isset($method['type']) ? $method['type'] : '';
        
        $ObjDB->NonQuery("UPDATE itc_exp_master SET fld_res_onoff_status='".$type."',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_id='".$id."' AND fld_delstatus='0'"); 
}

/* Content tagging oper
 * **/
if($oper == "savecontenttagdetails" and $oper != '')
{		
	try{
                $expid = isset($method['expid']) ? $method['expid'] : '';
		$destid = isset($method['destid']) ? $method['destid'] : '';
		$deststatus = isset($method['deststatus']) ? $method['deststatus'] : '';
		$taskid = isset($method['taskid']) ? $method['taskid'] : '';
		$taskstatus = isset($method['taskstatus']) ? $method['taskstatus'] : '';
		$resid = isset($method['resid']) ? $method['resid'] : '';
		$resstatus = isset($method['resstatus']) ? $method['resstatus'] : '';	
		
		$destid = explode('~',$destid);
		$deststatus = explode('~',$deststatus);
		$taskid = explode('~',$taskid);
		$taskstatus = explode('~',$taskstatus);
		$resid = explode('~',$resid);
		$resstatus = explode('~',$resstatus);
                
               /**Destination for loop Starts **/
                for($i=0;$i<sizeof($destid);$i++)
		{
                    $ObjDB->NonQuery("UPDATE itc_main_tag_mapping 
				                 SET fld_access='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
								 WHERE fld_tag_type='31' and fld_item_id='".$destid[$i]."' AND 
								 fld_tag_id IN(select fld_id FROM itc_main_tag_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0' )");	
						
                    fn_tagupdate($deststatus[$i],31,$destid[$i],$uid);
                                        
		} // for ends $i
                
		/**Destination for loop Ends **/
                
                /**Task for loop Starts **/
		for($j=0;$j<sizeof($taskid);$j++)
		{
                    $ObjDB->NonQuery("UPDATE itc_main_tag_mapping 
				                 SET fld_access='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
								 WHERE fld_tag_type='32' and fld_item_id='".$taskid[$j]."' AND 
								 fld_tag_id IN(select fld_id FROM itc_main_tag_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0' )");	
						
                    fn_tagupdate($taskstatus[$j],32,$taskid[$j],$uid);
                                        
                  } // for loop ends $j
                  
		/**Task for loop Ends **/
                
                /**Resource for loop Starts **/
                for($k=0;$k<sizeof($resid);$k++)
		{
                    
                    $ObjDB->NonQuery("UPDATE itc_main_tag_mapping 
				                 SET fld_access='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
								 WHERE fld_tag_type='33' and fld_item_id='".$resid[$k]."' AND 
								 fld_tag_id IN(select fld_id FROM itc_main_tag_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0' )");	
						
                    fn_tagupdate($resstatus[$k],33,$resid[$k],$uid);
                } // for ends $k
                echo "success";
               
	}
	catch(Exception $e){
		echo "invalid";
	}
}

if($oper == "toggledigitallogbook"){
    $expid = isset($method['expid']) ? intval($method['expid']) : '';
    $resstatus = isset($method['resstatus']) ? $method['resstatus'] : '';
    $schoolid = $_SESSION['schoolid'];
    if ($resstatus == "disabled"){
        $toggle_on_or_off = 0;
    }else{
        $toggle_on_or_off = 1;
    }

    try{
        $resStatusIDs = $ObjDB->QueryObject("SELECT fld_toggle_status FROM itc_digital_logbook_toggle_status WHERE fld_exp_id='".$expid."' AND fld_school_id='".$schoolid."' AND fld_teacher_id='".$uid."'");
        if ($resStatusIDs->num_rows>0){
            while($resStatusID = $resStatusIDs->fetch_assoc()) {
                $update_statement = "UPDATE itc_digital_logbook_toggle_status SET fld_toggle_status = '$toggle_on_or_off' WHERE fld_exp_id='$expid' AND fld_school_id='$schoolid' AND fld_teacher_id='$uid'";
                $ObjDB->NonQuery($update_statement);
            }
        }else{
            $insert_statement = "INSERT INTO itc_digital_logbook_toggle_status(fld_school_id, fld_exp_id,fld_teacher_id, fld_toggle_status) VALUES($schoolid, $expid, $uid, $toggle_on_or_off)";
            $ObjDB->NonQuery($insert_statement);
        }

        echo "success";
    }catch(Exception $e){
        echo "invalid";
    }
}

@include("footer.php");