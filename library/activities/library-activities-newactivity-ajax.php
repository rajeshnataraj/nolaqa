<?php 
	@include("sessioncheck.php");
	$oper = isset($method['oper']) ? $method['oper'] : '';
	$date=date("Y-m-d H:i:s");
	
	if($oper=="checkactivityname" and $oper != " " )
	{
		$activityid = isset($method['uid']) ? $method['uid'] : '0';
		$activityname = isset($method['activityname']) ? fnEscapeCheck($method['activityname']) : '';
		
		$count = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_activity_master 
		                                      WHERE MD5(LCASE(REPLACE(fld_activity_name,' ','')))='".$activityname."' 
											  AND fld_delstatus='0' AND fld_id<>'".$activityid."'");
		
		if($count == 0){ echo "true"; }	else { echo "false"; }
	}
	
	if($oper == "saveactivity" and $oper != '')
	{	
		try
		{
		$tags = isset($method['tags']) ? $method['tags'] : '';
		$activityid = isset($method['activityid']) ? $method['activityid'] : '0';
		$unitid = isset($method['unitid']) ? $method['unitid'] : '';
		$activityname = isset($method['activityname']) ? ($method['activityname']) : '';
		$activitydescription = isset($method['description']) ? ($method['description']) : '';
		$points = isset($method['points']) ? $method['points'] : '';
		$filename = isset($method['activityfilename']) ? ($method['activityfilename']) : '';	
		$filetype = isset($method['filetype']) ? $method['filetype'] : '';	
		$filesize = isset($method['activityfilesize']) ? ($method['activityfilesize']) : '';
		
		$filename =array_filter(explode(',',$filename));
		$filetype =array_filter(explode(',',$filetype));
		$filesize =array_filter(explode(',',$filesize));
		
		/**validation for the parameters and these below functions are validate to return true or false***/
		$validate_activityid=true;
		$validate_unitid=true;
		$validate_repeatname=true;
		if($unitid!=0)  $validate_unitid=validate_datatype($unitid,'int');
		if($activityid!=0)  $validate_activityid=validate_datatype($activityid,'int');
		$checkactivityname = fnEscapeCheck($activityname);
	    $count = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_activity_master 
		                                      WHERE MD5(LCASE(REPLACE(fld_activity_name,' ','')))='".$checkactivityname."' 
											  AND fld_delstatus='0' AND fld_id<>'".$activityid."'");
		if($count == 0){ $validate_repeatname=true; }	else { $validate_repeatname=false; }									  
		
		
		/**for purpose remove unwanted scripts****/
		$activityname = $ObjDB->EscapeStrAll($activityname);
		$activitydescription = $ObjDB->EscapeStr($activitydescription);
		
		
		
		if($validate_activityid and  $validate_unitid and $validate_repeatname)  //validating the unit id and activity id
		{
		
		
		if($activityid!='' and $activityid!=0  and $activityid!='undefined'){	
		
		    /*---tags------*/
			$ObjDB->NonQuery("UPDATE itc_main_tag_mapping SET fld_access='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'  
			                 WHERE fld_tag_type='2' AND fld_item_id='".$activityid."' AND fld_tag_id 
			                 IN(SELECT fld_id FROM  itc_main_tag_master 
							 WHERE fld_created_by='".$uid."' AND fld_delstatus='0')");
			
			fn_tagupdate($tags,2,$activityid,$uid);			
			
			$ObjDB->NonQuery("UPDATE itc_activity_master SET fld_unit_id='".$unitid."', fld_activity_name='".$activityname."', 
			                                                fld_activity_description='".$activitydescription."', fld_activity_points='".$points."', 
															fld_updated_by='".$uid."',fld_updated_date='".$date."' 
							WHERE fld_id='".$activityid."'");
     		
			}
			
		else{	
			$activityid= $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_activity_master(fld_unit_id, fld_activity_name, fld_activity_description, 
			                                                                          fld_activity_points, fld_created_by, fld_created_date) 
													 VALUES ('".$unitid."', '".$activityname."', '".$activitydescription."', '".$points."','".$uid."','".$date."')");
		
			fn_taginsert($tags,2,$activityid,$uid);			 	
		}
		
		if(sizeof($filename) != 0) { 
			    for($f=0;$f<sizeof($filename);$f++)
				{
				         $ObjDB->NonQuery("INSERT INTO itc_activity_file_mapping(fld_activity_id, fld_file_name, 
		                                                        fld_file_type,fld_file_size, fld_created_by,fld_created_date) 
						 VALUES('".$activityid."','".$filename[$f]."','".$filetype[$f]."','".$filesize[$f]."','".$uid."','".$date."')");
					
						if($sessmasterprfid == 6 or $sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid==9) 
						{ 
							$totsize=$ObjDB->SelectSingleValueInt("SELECT fld_bytes FROM itc_user_usedspace_details where fld_user_id='".$uid."'");
							$userid=$ObjDB->SelectSingleValueInt("SELECT fld_user_id  FROM itc_user_usedspace_details where fld_user_id='".$uid."'");

							$size=$totsize+$filesize[$f];
							if($userid == $uid)
							{
								$ObjDB->NonQuery("UPDATE itc_user_usedspace_details SET fld_bytes='".$size."' where fld_user_id='".$userid."'");
							}
						}
	   
				}
			}
					
		     echo "success";
		}
		else
		{
			echo "fail";
		}
		}
		catch(Exception $e)
		{
			echo "fail".$e;
			
		}
	}
	
	if($oper == "deleteactivity" and $oper != '')
	{	
		$activityid = isset($method['id']) ? $method['id'] : '';
		
		$ObjDB->NonQuery("UPDATE itc_activity_master 
		                SET fld_delstatus='1', fld_deleted_by='".$uid."', 
		                fld_deleted_date='".$date."'  
						WHERE fld_id='".$activityid."'");
						
		$ObjDB->NonQuery("UPDATE itc_activity_file_mapping 
		                 SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".$date."'  
		                 WHERE fld_activity_id='".$activityid."'");
                
                if($sessmasterprfid == 6 or $sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid==9) 
                { 
                    $totsize=$ObjDB->SelectSingleValueInt("SELECT fld_bytes FROM itc_user_usedspace_details where fld_user_id='".$uid."'");

                    $filename=$ObjDB->SelectSingleValue("SELECT fld_file_name FROM itc_activity_file_mapping where fld_activity_id='".$activityid."'");

                    $filesize=$ObjDB->SelectSingleValueInt("SELECT fld_file_size FROM itc_activity_file_mapping where fld_activity_id='".$activityid."'");

                    $size=$totsize-$filesize;
                    
                    if($size<0)
                    {
                       $size=0; 
                    }

                    $ObjDB->NonQuery("UPDATE itc_user_usedspace_details SET fld_bytes='".$size."' WHERE fld_user_id='".$uid."'");

                    $result = file_get_contents(_CONTENTURL_.'deletefile.php?file='.$filename.'&key=delete&foldername=activity');
                }
		
		echo "success";
	}
	
	
	if($oper == "deleteactivityfiles" and $oper != '')
	{	
		$activityidfileid = isset($method['activityfileid']) ? $method['activityfileid'] : '';
		$ObjDB->NonQuery("UPDATE itc_activity_file_mapping 
		                 SET fld_delstatus='1', fld_deleted_by='".$uid."', 
						     fld_deleted_date='".$date."'  
						 WHERE fld_id='".$activityidfileid."'");
		
		echo "success";
	}
	
	if($oper == "unlinkactivityfiles" and $oper != '')
	{	
		$filename = isset($method['filename']) ? $method['filename'] : '';
		unlink(__FULLCNTACTIVITYPATH__.$filename);
		echo "success";
	}
	
	
	
	if($oper == "showstudentlists" and $oper != '')
	{ 
		$classid = (isset($method['classid'])) ? $method['classid'] : 0;
		$activityid = (isset($method['activityid'])) ? $method['activityid'] : 0;
		$startdate = (isset($method['startdate'])) ? $method['startdate'] : 0;
		?>
		<script language="javascript" type="text/javascript">
			$(function() {
				$('#testrailvisible1').slimscroll({
					width: '410px',
					height:'366px',
					size: '7px',
                                        alwaysVisible: true,
                                        wheelstep: 1,
					railVisible: true,
					allowPageScroll: false,
					railColor: '#F4F4F4',
					opacity: 1,
					color: '#d9d9d9',
					
				});
				
				$('#testrailvisible2').slimscroll({
					width: '410px',
					height:'366px',
					size: '7px',
                                        alwaysVisible: true,
                                        wheelstep: 1,
					railVisible: true,
					allowPageScroll: false,
					railColor: '#F4F4F4',
					opacity: 1,
					color: '#d9d9d9',
				});
				
				$("#list1").sortable({
					connectWith: ".droptrue",
					dropOnEmpty: true,
					receive: function(event, ui) {
						$("div[class=draglinkright]").each(function(){ 
							if($(this).parent().attr('id')=='list1'){
								fn_movealllistitems('list1','list2',$(this).children(":first").attr('id'));
								fn_hideshowassignbtn();
							}
						});
					}
				});
				
				$( "#list2" ).sortable({
					connectWith: ".droptrue",
					dropOnEmpty: true,
					receive: function(event, ui) {
						$("div[class=draglinkleft]").each(function(){ 
							if($(this).parent().attr('id')=='list2'){
								fn_movealllistitems('list1','list2',$(this).children(":first").attr('id'));
								fn_hideshowassignbtn();
							}
						});
					}
				});
				$("#list1, #list2").disableSelection();
			});
		</script>
        <div class='six columns'>
            Students<span class="fldreq">*</span>
                <div class="dragndropcol">
                <?php
					$qrystudent= $ObjDB->QueryObject("SELECT CONCAT(b.fld_fname,' ',b.fld_lname) AS studentname, b.fld_id AS studentid 
							                                 FROM itc_class_student_mapping AS a 
															 LEFT JOIN  itc_user_master AS b ON a.fld_student_id = b.fld_id 
															 WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_id NOT IN 
															 (SELECT fld_student_id FROM itc_activity_student_mapping 
															   WHERE fld_activity_id='".$activityid."' AND fld_class_id='".$classid."' 
															   AND fld_start_date='".$startdate."' AND  fld_flag='1') ");
				?>
                    <div class="dragtitle">Students Available (<span id="nostudentleftdiv"> <?php echo $qrystudent->num_rows;?></span>)</div>
                    <div class="dragWell" id="testrailvisible1" >
                        <dl class='field row'>
                        <dt class='text'>
                            <input placeholder='Search' type='text' id="list_9_search" name="list_9_search" onKeyUp="search_list(this,'#list1');" />
                          </dt>
                      </dl>
                        <div id="list1" class="dragleftinner droptrue">
                            <?php 
                            if($qrystudent->num_rows > 0){
                                while($rowsqry = $qrystudent->fetch_assoc()){
                                    extract($rowsqry);
                                    ?>
                                        <div class="draglinkleft" id="list1_<?php echo $studentid; ?>" >
                                            <div class="dragItemLable" id="<?php echo $studentid; ?>"><?php echo $studentname; ?></div>
                                            <div class="clickable" id="clck_<?php echo $studentid; ?>" onclick="fn_movealllistitems('list1','list2',<?php echo $studentid; ?>);fn_hideshowassignbtn();"></div>
                                        </div> 
                                    <?php
                                }
                            }?>    
                        </div>
                    </div>
                    <div class="dragAllLink"  onclick="fn_movealllistitems('list1','list2',0);fn_hideshowassignbtn();">add all students</div>
                    </div>
        </div>
        
        <div class='six columns'>
            <span class="fldreq"></span>
            <div class="dragndropcol">
            <?php
				$qryclassmap=$ObjDB->QueryObject("SELECT a.fld_id, a.fld_student_id as studentid, CONCAT(fld_fname,' ',fld_lname) AS studentname 
						                                 FROM itc_activity_student_mapping AS a
														 LEFT JOIN itc_user_master AS b on a.fld_student_id=b.fld_id
														 WHERE a.fld_activity_id='".$activityid."' AND a.fld_class_id='".$classid."' AND a.fld_start_date='".$startdate."' 
														 AND a.fld_flag='1' GROUP BY a.fld_student_id ");
			?>
                <div class="dragtitle">Students in Activity (<span id="nostudentrightdiv"> <?php echo $qryclassmap->num_rows;?></span>)</div>
                <div class="dragWell" id="testrailvisible2">
                    <dl class='field row'>
                        <dt class='text'>
                            <input placeholder='Search' type='text' id="list_9_search" name="list_9_search" onKeyUp="search_list(this,'#list2');" />
                          </dt>
                      </dl>
                    <div id="list2" class="dragleftinner droptrue">
                        <?php 
                        if($qryclassmap->num_rows > 0){
                            while($rowqryclassmap = $qryclassmap->fetch_assoc()){
                                extract($rowqryclassmap);
                                ?> 
                                    <div class="draglinkright" id="list2_<?php echo $studentid; ?>">
                                        <div class="dragItemLable" id="<?php echo $studentid; ?>"><?php echo $studentname;?></div>
                                        <div class="clickable" id="clck_<?php echo $studentid; ?>" onclick="fn_movealllistitems('list1','list2',<?php echo $studentid; ?>);fn_hideshowassignbtn();"></div>
                                    </div>
                                <?php 
                            }
                        }?>
                    </div>
                </div>
                <div class="dragAllLink" onclick="fn_movealllistitems('list2','list1',0);fn_hideshowassignbtn();">remove all students</div>
                </div>
        </div>
        <?php 
	}  
	
	
	if($oper == "maptoactivity" and $oper != '')
	{	
		$activityid = (isset($method['activityid'])) ? $method['activityid'] : 0;
		$list3 = isset($method['list1']) ? $method['list1'] : '0';
		$list4 = isset($method['list2']) ? $method['list2'] : '0';
		$clasid= isset($method['clasid']) ? $method['clasid'] : '0';
		$sdate1 =(isset( $method['sdate1'])) ?  $method['sdate1'] : '';
		$edate1 =(isset( $method['edate1'])) ?  $method['edate1'] : '';
		$predate =(isset( $method['predate'])) ?  $method['predate'] : '';
		$flag =(isset( $method['flag'])) ?  $method['flag'] : '0';
		
		$list3=explode(",",$list3);
		$list4=explode(",",$list4);
		
		// Student mapping start
		for($i=0;$i<sizeof($list3);$i++)
		{
			$id = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_activity_student_mapping 
			                                    WHERE fld_activity_id='".$activityid."' AND fld_student_id='".$list3[$i]."' 
												AND fld_flag='1' AND fld_class_id='".$clasid."' 
												AND fld_start_date='".date('Y-m-d',strtotime($sdate1))."' ");
      
	  		$ObjDB->NonQuery("UPDATE itc_activity_student_mapping 
			                 SET fld_flag='0', fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_id='".$id."'");
		}
		
		if($list4[0] !=''){
			for($i=0;$i<sizeof($list4);$i++)
			{
				$id = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_activity_student_mapping 
				                                    WHERE fld_activity_id='".$activityid."' AND fld_student_id='".$list4[$i]."' 
													AND fld_flag='1' AND fld_class_id='".$clasid."' 
													AND fld_start_date='".date('Y-m-d',strtotime($predate))."' ");
				
				$ObjDB->NonQuery("UPDATE itc_activity_student_mapping SET fld_flag='0', fld_updated_by='".$uid."', fld_updated_date='".$date."' 
				                 WHERE fld_id='".$id."'");
				
				$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_activity_student_mapping 
				                                      WHERE fld_activity_id='".$activityid."' AND fld_student_id='".$list4[$i]."' 
													  AND fld_class_id='".$clasid."' AND fld_start_date='".date('Y-m-d',strtotime($sdate1))."' ");
				if($count==0 or $count=='')
				{
					$ObjDB->NonQuery("INSERT INTO itc_activity_student_mapping
					                   (fld_activity_id, fld_student_id, fld_created_by, fld_flag, fld_class_id, fld_start_date, fld_end_date, fld_created_date) 
									 VALUES ('".$activityid."', '".$list4[$i]."', '".$uid."' , '1', '".$clasid."', 
									 '".date('Y-m-d',strtotime($sdate1))."', '".date('Y-m-d',strtotime($edate1))."', '".$date."')");
				}
				else
				{
					$ObjDB->NonQuery("UPDATE itc_activity_student_mapping 
					                 SET fld_flag='1', fld_updated_by='".$uid."', fld_updated_date='".$date."', fld_start_date='".date('Y-m-d',strtotime($sdate1))."', fld_end_date='".date('Y-m-d',strtotime($edate1))."'
									 WHERE fld_id='".$count."'");
				}
			}
		}
	}
	
	if($oper == "deletestudent" and $oper != '')
	{		
		$fieldid = isset($method['fieldid']) ? $method['fieldid'] : '0';
		
		$ObjDB->NonQuery("UPDATE itc_activity_student_mapping SET fld_flag='0', fld_deleted_by='".$uid."', fld_deleted_date='".$date."' WHERE fld_id='".$fieldid."'");
			
		echo "success";
	}

	/***** created by chandru start line ******/
	if($oper == "filedelete" and $oper != '')
	{
		$filename = isset($method['filename']) ? $method['filename'] : '';
		$result = file_get_contents(_CONTENTURL_.'deletefile.php?file='.$filename.'&key=delete&foldername=activity');
	}
	/***** created by chandru end line ******/
	
	@include("footer.php");