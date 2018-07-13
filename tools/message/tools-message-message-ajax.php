<?php 
	@include("sessioncheck.php");
	$oper = isset($method['oper']) ? $method['oper'] : '';
	
/*----Send message to the correspond user----*/	
if($oper=="sendmsg" and $oper != " " )
{
	try /**Here starts with saving the details uster master and district master tables**/
	{
		$msgto = isset($method['msgto']) ? $method['msgto'] : '';
		$msgsubject = isset($method['msgsubject']) ? $ObjDB->EscapeStrAll($method['msgsubject']) : '';
                $message = isset($method['message']) ? $ObjDB->EscapeStr($method['message']) : '';
                $dropdowntype = isset($method['dropdowntype']) ? $method['dropdowntype'] : '';
		$chkalert = isset($method['chkalert']) ? $method['chkalert'] : '';
                /* File upload codeing start line */
                $upload = isset($method['messageupload']) ? $method['messageupload'] : '';
                $filetype = isset($method['filetype']) ? $method['filetype'] : '';
                $filesizes = isset($method['filesize']) ? $method['filesize'] : '';
              
                //file names
                $uploadfile = array($upload);
                $uploadfile = explode("~", $uploadfile[0]);
                $filesize= sizeof($uploadfile);
                //file type
                $uploadfiletype = array($filetype);
                $uploadfiletype = explode("~", $uploadfiletype[0]);
				//file size
				$uploadfilesize = array($filesizes);
                $uploadfilesize = explode("~", $uploadfilesize[0]);
                
                /* File upload codeing end line line */
                
		if($dropdowntype==4){
			$msg=explode(",",$msgto);

		}else{
			$msg=$msgto;
		}
		
		if($dropdowntype ==2 or $dropdowntype ==3)
		{
                    
			if($msgto == "all"){
				
				$userids=$ObjDB->QueryObject("SELECT fld_id AS id  
                                                            FROM itc_user_master 
                                                            WHERE fld_delstatus='0' AND fld_fname!='' 
                                                            AND fld_profile_id !=2 AND fld_profile_id !=10 
                                                            AND fld_profile_id !=11 ORDER BY id ASC");
			
					if($userids->num_rows>0){
						while($row = $userids->fetch_assoc())
						{
							extract($row);
						$maxid = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_message_master(fld_from,fld_to,fld_subject,
                                                                        fld_message,fld_readstatus,fld_alert,fld_created_by,fld_created_date) 
                                                                VALUES('".$uid."','".$id."','".$msgsubject."','".$message."','0','".$chkalert."','".$uid."',
                                                                        '".date("Y-m-d H:i:s")."' )");
							// file upload codeing
							if($upload!='')
							{
								for ($i=0;$i<sizeof($uploadfile);$i++)
								{
									$ObjDB->NonQuery("INSERT INTO itc_message_upload_mapping (fld_messageid,fld_file_name,fld_file_type, fld_filesize,fld_created_date)
																								   VALUES('".$maxid."','".$uploadfile[$i]."','".$uploadfiletype[$i]."','".$uploadfilesize[$i]."','".date("Y-m-d H:i:s")."')");
									if($sessmasterprfid == 6 or $sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid==9) 
									{ 
										$totsize=$ObjDB->SelectSingleValueInt("SELECT fld_bytes FROM itc_user_usedspace_details where fld_user_id='".$uid."'");
										$userid=$ObjDB->SelectSingleValueInt("SELECT fld_user_id  FROM itc_user_usedspace_details where fld_user_id='".$uid."'");

										$size=$totsize+$uploadfilesize[$i];
										if($userid == $uid)
										{
											$ObjDB->NonQuery("UPDATE itc_user_usedspace_details SET fld_bytes='".$size."' where fld_user_id='".$userid."'");
										}
									}
								}


							}
						}
                                                
                                                
						
				}
		
			}
			else{
				
				$maxid = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_message_master(fld_from,fld_to,fld_subject,fld_message,
								fld_readstatus,fld_alert,fld_created_by,fld_created_date) 
							VALUES('".$uid."','".$msg."','".$msgsubject."','".$message."','0','".$chkalert."','".$uid."',
								'".date("Y-m-d H:i:s")."' )");
                                
				// file upload codeing
				if($upload!='')
				{
					for ($i=0;$i<sizeof($uploadfile);$i++)
					{
						$ObjDB->NonQuery("INSERT INTO itc_message_upload_mapping (fld_messageid,fld_file_name,fld_file_type,fld_file_size,fld_created_date)
																					  VALUES('".$maxid."','".$uploadfile[$i]."','".$uploadfiletype[$i]."','".$uploadfilesize[$i]."','".date("Y-m-d H:i:s")."')");

						if($sessmasterprfid == 6 or $sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid==9) 
						{ 
							$totsize=$ObjDB->SelectSingleValueInt("SELECT fld_bytes FROM itc_user_usedspace_details where fld_user_id='".$uid."'");
							$userid=$ObjDB->SelectSingleValueInt("SELECT fld_user_id  FROM itc_user_usedspace_details where fld_user_id='".$uid."'");

							$size=$totsize+$uploadfilesize[$i];
							if($userid == $uid)
							{
								$ObjDB->NonQuery("UPDATE itc_user_usedspace_details SET fld_bytes='".$size."' where fld_user_id='".$userid."'");
							}
						}
					}
				}
			  
			}
		}
		else if($dropdowntype==1)
		{
                    
			$classstudcount=$ObjDB->QueryObject("SELECT  fld_student_id as studid  
                                                            FROM itc_class_student_mapping 
                                                            WHERE fld_flag=1 AND fld_class_id='".$msg."'");
			
					if($classstudcount->num_rows>0){
						while($row = $classstudcount->fetch_assoc())
						{
							extract($row);
						$maxid = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_message_master(fld_from,fld_to,fld_subject,
                                                                        fld_message,fld_readstatus,fld_alert,fld_created_by,fld_created_date) 
                                                                VALUES('".$uid."','".$studid."','".$msgsubject."','".$message."','0','".$chkalert."','".$uid."',
                                                                        '".date("Y-m-d H:i:s")."' )");
							// file upload codeing
							if($upload!='')
							{
								for ($i=0;$i<sizeof($uploadfile);$i++)
								{
								   $ObjDB->NonQuery("INSERT INTO itc_message_upload_mapping (fld_messageid,fld_file_name,fld_file_type,fld_file_size,fld_created_date)
																								  VALUES('".$maxid."','".$uploadfile[$i]."','".$uploadfiletype[$i]."','".$uploadfilesize[$i]."','".date("Y-m-d H:i:s")."')");
								}

								if($sessmasterprfid == 6 or $sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid==9) 
								{ 
									$totsize=$ObjDB->SelectSingleValueInt("SELECT fld_bytes FROM itc_user_usedspace_details where fld_user_id='".$uid."'");
									$userid=$ObjDB->SelectSingleValueInt("SELECT fld_user_id  FROM itc_user_usedspace_details where fld_user_id='".$uid."'");

									$size=$totsize+$uploadfilesize[$i];
									if($userid == $uid)
									{
										$ObjDB->NonQuery("UPDATE itc_user_usedspace_details SET fld_bytes='".$size."' where fld_user_id='".$userid."'");
									}
								}
							}
						}
						
				}
		}
                else if($dropdowntype==4)
		{
                    
                    for($m=0;$m<sizeof($msg);$m++){
				$maxid = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_message_master(fld_from,fld_to,fld_subject,fld_message,
								fld_readstatus,fld_alert,fld_created_by,fld_created_date) 
							VALUES('".$uid."','".$msg[$m]."','".$msgsubject."','".$message."','0','".$chkalert."','".$uid."',
								'".date("Y-m-d H:i:s")."' )");
                                
                    // file upload codeing
                    if($upload!='')
                    {
                        for ($i=0;$i<sizeof($uploadfile);$i++)
                        {
							$ObjDB->NonQuery("INSERT INTO itc_message_upload_mapping (fld_messageid,fld_file_name,fld_file_type,fld_file_size,fld_created_date)
                                                                                          VALUES('".$maxid."','".$uploadfile[$i]."','".$uploadfiletype[$i]."','".$uploadfilesize[$i]."','".date("Y-m-d H:i:s")."')");
							if($sessmasterprfid == 6 or $sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid==9) 
							{ 
								$totsize=$ObjDB->SelectSingleValueInt("SELECT fld_bytes FROM itc_user_usedspace_details where fld_user_id='".$uid."'");
								$userid=$ObjDB->SelectSingleValueInt("SELECT fld_user_id  FROM itc_user_usedspace_details where fld_user_id='".$uid."'");

								$size=$totsize+$uploadfilesize[$i];
								if($userid == $uid)
								{
									$ObjDB->NonQuery("UPDATE itc_user_usedspace_details SET fld_bytes='".$size."' where fld_user_id='".$userid."'");
								}
							}
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
/*----Move message to the archive----*/	
if($oper=="archivemsg" and $oper !="")
{
	$msgid = isset($method['msgid']) ? $method['msgid'] : '';
	$ObjDB->NonQuery("UPDATE itc_message_master 
					SET fld_readstatus='1', fld_archive_status='1' , fld_updated_by='".$uid."',
					fld_updated_date='".date("Y-m-d H:i:s")."' 
					WHERE fld_id='".$msgid."' AND fld_delstatus='0'");
	
}
/*----Reply message to the corresponding user----*/	
if($oper=="replymsg" and $oper!="")
{
	$messagereply = isset($method['messagereply']) ? $ObjDB->EscapeStr($method['messagereply']) : '';
	$subject = isset($method['subject']) ? $method['subject'] : '';
	$sender = isset($method['sender']) ? $method['sender'] : '';
	$msgid = isset($method['msgid']) ? $method['msgid'] : '';
	
	$ObjDB->NonQuery("INSERT INTO itc_message_master(fld_from,fld_to,fld_subject,fld_message,
						fld_readstatus,fld_created_by,fld_created_date) 
					VALUES('".$uid."','".$sender."','".$subject."','".$messagereply."','0','".$uid."',
					'".date("Y-m-d H:i:s")."' )");
	
}
/*----Forwarding the message to correspond user----*/		
if($oper=="forwardmsg" and $oper != " " )
{
	try /**Here starts with saving the details uster master and district master tables**/
	{
		
		$msgto = isset($method['msgto']) ? $method['msgto'] : '';
		$subject = isset($method['subject']) ? $method['subject'] : '';
		$fwdmessage = isset($method['fwdmessage']) ? $ObjDB->EscapeStr($method['fwdmessage']) : '';
		$dropdowntype = isset($method['dropdowntype']) ? $method['dropdowntype'] : '';
		if($dropdowntype!=1)
		{
			$ObjDB->NonQuery("INSERT INTO itc_message_master(fld_from,fld_to,fld_subject,fld_message,
								fld_readstatus,fld_created_by,fld_created_date) 
							VALUES('".$uid."','".$msgto."','".$subject."','".$fwdmessage."','0','".$uid."',
								'".date("Y-m-d H:i:s")."' )");
			
		}
		else
		{
			$classstudcount=$ObjDB->QueryObject("SELECT  fld_student_id as studid  
												FROM itc_class_student_mapping 
												WHERE fld_flag=1 AND fld_class_id='".$msgto."'");
			
					if($classstudcount->num_rows>0){
						while($row = $classstudcount->fetch_assoc())
						{
							extract($row);
						$ObjDB->NonQuery("INSERT INTO itc_message_master(fld_from,fld_to,fld_subject,
											fld_message,fld_readstatus,fld_created_by,fld_created_date) 
										VALUES('".$uid."','".$studid."','".$subject."','".$fwdmessage."','0','".$uid."',
											'".date("Y-m-d H:i:s")."' )");
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
/*----Delete message----*/	
if($oper=="deletemsg" and $oper!="")
{
	$msgid = isset($method['msgid']) ? $method['msgid'] : '';
	$type = isset($method['id']) ? $method['id'] : '';
	if($type!=1)
	{
		$extraqry="fld_todelstatus='1'";
	}
	if($type==1)
	{
		$extraqry="fld_fromdelstatus='1'";
	}
	if($type==2)
	{
		$extraqry="fld_archdelstatus='1'";
	}
	$ObjDB->NonQuery("UPDATE itc_message_master SET ".$extraqry." , fld_deleted_by='".$uid."', 
						fld_deleted_date='".date("Y-m-d H:i:s")."' 
					WHERE fld_id='".$msgid."' AND fld_delstatus='0'");
}


if($oper=="showusers" and $oper!="")
{
    
    $userid = isset($method['userid']) ? $method['userid'] : '';
    $list6 = isset($method['list6']) ? $method['list6'] : '';
    $userrow='';
    $userrowsub='';
    
                if($list6!='')
                {
                    $subqry="AND fld_id not in (".$list6.")";
                }
                else
                {
                    $subqry='';
                }
		
		$userqry = $ObjDB->QueryObject("SELECT fld_id AS id,CONCAT(fld_fname, ' ', fld_lname) AS username,fld_profile_id as prfid
                                                FROM itc_user_master
                                                WHERE fld_delstatus = '0' AND fld_fname != ''
                                                 AND fld_profile_id ='".$userid."' ".$subqry."  AND fld_id!='".$uid."'");
                
                
		
		?>

		<script type="text/javascript" language="javascript">
			$(function() {
				$('#testrailvisible11').slimscroll({
					width: '410px',
					height:'366px',
					size: '3px',
					railVisible: true,
					allowPageScroll: false,
					railColor: '#F4F4F4',
					opacity: 1,
					color: '#d9d9d9',
					wheelStep: 1,

				
				});
				
				$('#testrailvisible12').slimscroll({
					width: '410px',
					height:'370px',
					size: '3px',
					railVisible: true,
					allowPageScroll: false,
					railColor: '#F4F4F4',
					opacity: 1,
					color: '#d9d9d9',
					wheelStep: 1,
				});			
				$("#list5").sortable({
					connectWith: ".droptrue3",
					dropOnEmpty: true,
					items: "div[class='draglinkleft']",
					receive: function(event, ui) {
						$("div[class=draglinkright]").each(function(){ 
							if($(this).parent().attr('id')=='list5'){
								
								fn_movealllistitems('list5','list6',1,$(this).children(":first").attr('id'));
								
							}
						});
					}
				});			
				$("#list6" ).sortable({
					connectWith: ".droptrue3",
					dropOnEmpty: true,
					receive: function(event, ui) {
						$("div[class=draglinkleft]").each(function(){ 
							if($(this).parent().attr('id')=='list6'){
								fn_movealllistitems('list5','list6',1,$(this).children(":first").attr('id'));
								
							}
						});
					}
				});
			}); 
		</script>

		<div class='six columns'>
            <div class="dragndropcol">
                <div class="dragtitle">Users</div>
                <div class="dragWell" id="testrailvisible11" >
                    <div id="list5" class="dragleftinner droptrue3">
                    	<div class="draglinkleftSearch" id="s_list5" >
                           <dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='Search' type='text' id="list_5_search" name="list_5_search" onKeyUp="search_list(this,'#list5');" />
                                </dt>
                            </dl>
                        </div>
                    <?php 
							if($userqry->num_rows > 0){ 
							while($userrow = $userqry->fetch_assoc()){
							extract($userrow);
														
					?>
                            <div class="draglinkleft" id="list5_<?php echo $id; ?>" >
                                <div class="dragItemLable tooltip" title="<?php echo $username;?>" id="<?php echo $id; ?>"><?php echo $username;?></div>
                                <div class="clickable" id="clck_<?php echo $id; ?>" onclick="fn_movealllistitems('list5','list6',1,'<?php echo $id; ?>');"></div>
                            </div>
                    <?php
								
					}
						}
					?>    
            </div>
        </div>
                <div class="dragAllLink" onclick="fn_movealllistitems('list5','list6',0);"  style="cursor: pointer;cursor:hand;width: 130px;float:right; ">add all users</div>
            </div>
        </div>
        <div class='six columns'>
            <div class="dragndropcol">
                <div class="dragtitle">Selected users<span class="fldreq">*</span></div>
                <div class="dragWell" id="testrailvisible12">
                    <div id="list6" class="dragleftinner droptrue3">
                    	<?php
		        if($list6!='')
                        {
                                                $usersubqry = $ObjDB->QueryObject("SELECT fld_id AS id,CONCAT(fld_fname, ' ', fld_lname) AS username,fld_profile_id as prfid
                                                FROM itc_user_master
                                                WHERE fld_delstatus = '0' AND fld_fname != ''
                                                AND fld_id in (".$list6.")");
                    	
                                                if($usersubqry->num_rows > 0){ 
							while($userrowsub = $usersubqry->fetch_assoc()){
							extract($userrowsub);
		
					?>
                            <div class="draglinkright" id="list6_<?php echo $id; ?>" >
                                <div class="dragItemLable tooltip" title="<?php echo $username;?>" id="<?php echo $id; ?>"><?php echo $username;?></div>
                                <div class="clickable" id="clck_<?php echo $id; ?>" onclick="fn_movealllistitems('list5','list6',1,'<?php echo $id; ?>');"></div>
                            </div>
                    <?php
                             
					}
						}
                       
                        }
                ?>
                             
                       
                    </div>	
                </div>
                <div class="dragAllLink" onclick="fn_movealllistitems('list6','list5',0);"  style="cursor: pointer;cursor:hand;width: 160px;float: right;">remove all users</div>
            </div>
        </div>
<?php
}

/*******Delete All the Message code developed by Mohan M 21-11-2015************/
if($oper=="deleteallmsg" and $oper!="")
{
    $userid = isset($method['userid']) ? $method['userid'] : '';

    $extraqry="fld_fromdelstatus='1'";

    $qrymsg=$ObjDB->QueryObject("SELECT fld_id AS msgid
                                            FROM itc_message_master 
                                            WHERE fld_delstatus='0' AND fld_from = '".$userid."'
                                            ORDER BY fld_id DESC  ");
    if($qrymsg->num_rows>0)
    {
        while($row = $qrymsg->fetch_assoc())
        {	
            extract($row);
            $ObjDB->NonQuery("UPDATE itc_message_master SET ".$extraqry." , fld_deleted_by='".$uid."', 
                                    fld_deleted_date='".date("Y-m-d H:i:s")."' 
					WHERE fld_id='".$msgid."' AND fld_delstatus='0'");
			
        }
    }
}
/*******Delete All the Message code developed by Mohan M 21-11-2015************/

/***** created by chandru 10-06-2016 start line ******/
if($oper == "filedelete" and $oper != '')
{
	$filename = isset($method['filename']) ? $method['filename'] : '';
	$result = file_get_contents(_CONTENTURL_.'deletefile.php?file='.$filename.'&key=delete&foldername=message');
}
/***** created by chandru end line ******/

	@include("footer.php");
	
