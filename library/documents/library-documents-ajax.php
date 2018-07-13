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
                       <input type="hidden" name="docphaseid" id="docphaseid" value="<?php echo $phaseid;?>"  onchange="$(this).valid();"  />
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
	if($oper == "savedocument" and $oper != '')
	{		
		
                $docunitid = isset($method['docunitid']) ? $method['docunitid'] : '';
                $docphaseid = isset($method['docphaseid']) ? $method['docphaseid'] : '';
                $docname = isset($method['documentname']) ? ($method['documentname']) : '';	
                $docicon = isset($method['docicon']) ? $method['docicon'] : '';
                $docdescription = isset($method['docdescription']) ? $method['docdescription'] : '';
		$docid = isset($method['docid']) ? $method['docid'] : '';
                $version = isset($method['version']) ? $method['version'] : '';
                $doctypename = isset($method['doctypename']) ? $method['doctypename'] : '';
		$tags = isset($method['tags']) ? $method['tags'] : '';	
                
		
		/**validation for the parameters and these below functions are validate to return true or false***/
		$validate_docicon=true;
		$validate_docid=true;
		$validate_docname=true;
		$validate_docnamecheck=true;
               
		if($docid!=0) $validate_docid=validate_datatype($docid,'int');
		
		/**for purpose remove unwanted scripts****/
		$docname = $ObjDB->EscapeStrAll($docname);		
		
		
		if($docicon!='')$validate_docicon=isImage(__FULLCNTUNITICONPATH__.$docicon);
		
			if($validate_docicon and $validate_docid)
			{
                         
			if($docid == 0)
			{
                            
                          	$maxid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_sosdocument_master(fld_unit_id,fld_phase_id, fld_document_name, fld_document_icon, fld_document_descr,fld_docfile_name,fld_version, fld_created_date, fld_created_by) 
				                                     VALUES ('".$docunitid."','".$docphaseid."','".$docname."','".$docicon."','".$docdescription."','".$doctypename."','".$version."','".$date."','".$uid."')");
				
				/*--Tags insert-----*/	
				
				fn_taginsert($tags,40,$maxid,$uid);
			}
			else
			{
				$ObjDB->NonQuery("UPDATE itc_sosdocument_master 
                                                    SET fld_unit_id='".$docunitid."',fld_phase_id='".$docphaseid."',fld_document_name='".$docname."',
                                                    fld_document_icon='".$docicon."',fld_document_descr='".$docdescription."',fld_docfile_name='".$doctypename."',
                                                    fld_version='".$version."',fld_updated_by='".$uid."' , fld_updated_date='".$date."' WHERE fld_id='".$docid."'");
				
				/*---tags------*/
				$ObjDB->NonQuery("UPDATE itc_main_tag_mapping 
				                 SET fld_access='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
								 WHERE fld_tag_type='40' and fld_item_id='".$docid."' AND 
								 fld_tag_id IN(select fld_id FROM itc_main_tag_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0' )");	
						
				fn_tagupdate($tags,40,$docid,$uid);			
			}
			
			     echo "success";
				 
			}
			else
			{
				 echo "fail";
			}
		
		
	}
	
	/*--- Delete document Details ---*/
	if($oper == "deletedocument" and $oper != '')
	{		
		$docid = isset($method['docid']) ? $method['docid'] : '0';
		$validate_docid=true;
		if($docid!=0)$validate_docid=validate_datatype($docid,'int');
		
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_sosdocument_master 
		                                      WHERE fld_id='".$docid."' AND fld_delstatus='0'"); 
		
		if($validate_docid){
			if($count!=0){
				$ObjDB->NonQuery("UPDATE itc_sosdocument_master 
				                 SET fld_delstatus='1', fld_deleted_date='".$date."', fld_deleted_by='".$uid."' 
								 WHERE fld_id='".$docid."'");
				echo "success";
				
			}
		}
		else{
				
				echo "exists";
			}
	}
	

	
	/*--- Check the document Name Duplication ---*/
	if($oper=="checkdocumentname" and $oper != " " )
	{
		$docid = isset($method['uid']) ? $method['uid'] : '0';
		$docname = isset($method['documentname']) ?  fnEscapeCheck($method['documentname']) : '';
		
		$count = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_sosdocument_master 
		                                      WHERE MD5(LCASE(REPLACE(fld_document_name,' ','')))='".$docname."' 
											  AND fld_delstatus='0' AND fld_id<>'".$docid."'");
											  
		if($count == 0){ echo "true"; }	else { echo "false"; }
	
	}

	@include("footer.php");