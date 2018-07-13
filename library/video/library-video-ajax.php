<?php 
	@include("sessioncheck.php");	
	$oper = isset($method['oper']) ? $method['oper'] : '';
	$date=date("Y-m-d H:i:s");
        
        
        
if($oper=="showphase" and $oper != " " )
{
    $phaseunitid = isset($method['phaseid']) ? $method['phaseid'] : '';
    ?>
    <div id="phasediv"> <!-- Unit -->   
        Phase<span class="fldreq">*</span>
        <dl class='field row' id='phaid'>  
           <dt class='dropdown'>  

                   <div class="selectbox">
                       <input type="hidden" name="videophaseid" id="videophaseid" value="<?php echo $phaseid;?>"  onchange="$(this).valid();"  />
                       <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                           <span class="selectbox-option input-medium" data-option="<?php echo $phaseunitid;?>" id="clearunit">
                               Select Phase
                           </span>
                           <b class="caret1"></b>
                       </a>                                           
                       <div class="selectbox-options">
                           <input type="text" class="selectbox-filter" placeholder="Search Phase">
                           <ul role="options">
                               <?php 
                               $unitqry = $ObjDB->QueryObject("SELECT fld_id AS phaseid, fld_phase_name AS phasename FROM itc_sosphase_master WHERE fld_unit_id='".$phaseunitid."' AND fld_delstatus= '0' ORDER BY fld_phase_name ");
                               if($unitqry->num_rows > 0)
                               {
                                   while($rowunit = $unitqry->fetch_assoc())
                                   {
                                                                                                   extract($rowunit);
                                   ?>
                                       <li><a tabindex="-1" href="#" data-option="<?php echo $phaseid;?>"><?php echo $phasename; ?></a></li>
                                   <?php
                                   }
                           }                                               
                           ?>       
                           </ul>
                       </div>
                   </div>

           </dt>
        </dl>
    </div>
    <?php
}
        
        
        
        
	/*--- Save Unit Details ---*/
	if($oper == "savevideo" and $oper != '')
	{		
		
		
		
                $videounitid = isset($method['videounitid']) ? $method['videounitid'] : '';
                $videophaseid = isset($method['videophaseid']) ? $method['videophaseid'] : '';
                $videoname = isset($method['videoname']) ? ($method['videoname']) : '';	
                $videoicon = isset($method['videoicon']) ? $method['videoicon'] : '';
                $videodescription = isset($method['videodescription']) ? $method['videodescription'] : '';
		$videoid = isset($method['videoid']) ? $method['videoid'] : '';
                $version = isset($method['version']) ? $method['version'] : '';
                $videotypename = isset($method['videotypename']) ? $method['videotypename'] : '';
		$tags = isset($method['tags']) ? $method['tags'] : '';	
                
		
		/**validation for the parameters and these below functions are validate to return true or false***/
		$validate_videoicon=true;
		$validate_videoid=true;
		$validate_videoname=true;
		$validate_videonamecheck=true;
               
		if($videoid!=0) $validate_videoid=validate_datatype($videoid,'int');		
		
		/**for purpose remove unwanted scripts****/
		$videoname = $ObjDB->EscapeStrAll($videoname);		
		
		
		if($videoicon!='')$validate_videoicon=isImage(__FULLCNTUNITICONPATH__.$videoicon);
		
			if($validate_videoicon and $validate_videoid)
			{
                         
			if($videoid == 0)
			{
                            
                          	$maxid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_sosvideo_master(fld_unit_id,fld_phase_id, fld_video_name, fld_video_icon, fld_video_descr,fld_video_type,fld_videofile_name,fld_version, fld_created_date, fld_created_by) 
				                                     VALUES ('".$videounitid."','".$videophaseid."','".$videoname."','".$videoicon."','".$videodescription."','0','".$videotypename."','".$version."','".$date."','".$uid."')");
				
				/*--Tags insert-----*/	
				
				fn_taginsert($tags,37,$maxid,$uid);
			}
			else
			{
				$ObjDB->NonQuery("UPDATE itc_sosvideo_master 
                                                    SET fld_unit_id='".$videounitid."',fld_phase_id='".$videophaseid."',fld_video_name='".$videoname."',
                                                    fld_video_icon='".$videoicon."',fld_video_descr='".$videodescription."',fld_videofile_name='".$videotypename."',
                                                    fld_version='".$version."',fld_updated_by='".$uid."' , fld_updated_date='".$date."' WHERE fld_id='".$videoid."'");
				
				/*---tags------*/
				$ObjDB->NonQuery("UPDATE itc_main_tag_mapping 
				                 SET fld_access='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
								 WHERE fld_tag_type='37' and fld_item_id='".$videoid."' AND 
								 fld_tag_id IN(select fld_id FROM itc_main_tag_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0' )");	
						
				fn_tagupdate($tags,37,$videoid,$uid);			
			}
			
			     echo "success";
				 
			}
			else
			{
				 echo "fail";
			}
		
		
	}
	
	/*--- Delete Unit Details ---*/
	if($oper == "deletevideo" and $oper != '')
	{		
		$videoid = isset($method['videoid']) ? $method['videoid'] : '0';
		$validate_videoid=true;
		if($videoid!=0)$validate_videoid=validate_datatype($videoid,'int');
		
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_sosvideo_master 
		                                      WHERE fld_id='".$videoid."' AND fld_delstatus='0'"); // this query to checking whether the unit have lessons are not
		
		if($validate_videoid){
			if($count!=0){
				$ObjDB->NonQuery("UPDATE itc_sosvideo_master 
				                 SET fld_delstatus='1', fld_deleted_date='".$date."', fld_deleted_by='".$uid."' 
								 WHERE fld_id='".$videoid."'");
				echo "success";
				
			}
		}
		else{
				
				echo "exists";
			}
	}
	

	
	/*--- Check the Unit Name Duplication ---*/
	if($oper=="checkvideoname" and $oper != " " )
	{
		$phaseid = isset($method['uid']) ? $method['uid'] : '0';
		$phasename = isset($method['phasename']) ?  fnEscapeCheck($method['phasename']) : '';
		
		$count = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_sosphase_master 
		                                      WHERE MD5(LCASE(REPLACE(fld_phase_name,' ','')))='".$phasename."' 
											  AND fld_delstatus='0' AND fld_id<>'".$phaseid."'");
											  
		if($count == 0){ echo "true"; }	else { echo "false"; }
	
	}

	@include("footer.php");