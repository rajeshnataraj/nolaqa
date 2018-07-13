<?php 

@include("sessioncheck.php"); 
$date = date("Y-m-d H:i:s");
$oper= isset($method['oper']) ? $method['oper'] : '';

/*****this opertion can perform to show the form of getting extend text****/
if($oper=='extendtxtform' and  $oper!='')
{
	$expid= isset($method['md_id']) ? $method['md_id'] : '';
	$extendid= isset($method['extid']) ? $method['extid'] : '0';
	$type= isset($method['type']) ? $method['type'] : '';
	$extendtxt='';
	
	$qryforgetmoduledetails= $ObjDB->QueryObject("SELECT fld_exp_name as expname,fld_id as decryptmoduleid 
	                                             FROM itc_exp_master 
	                                             WHERE fld_id='".$expid."'");
										   
	$rowqryforgetmoduledetails = $qryforgetmoduledetails->fetch_assoc();
	extract($rowqryforgetmoduledetails);
	
	if($type=='rename' or $type=='copy')
	{
		$extendtxt= $ObjDB->SelectSingleValue("SELECT fld_extend_text 
		                                       FROM itc_exp_extendtext_master WHERE fld_id='".$extendid."'");
	}
	switch($type)
	{
            case 'new':
               $typename='Extending';
                break;
            case 'rename':
                $typename='Renaming';
                break;
            case 'copy':
                $typename='Copying';
                break;
	}
	?>
    <div class="four columns">
         <div class="row rowspacer" style="min-width:400px;">
            <center><span style="font-size:24px;" class="darkTitle"><?php echo $typename; ?> <?php echo $expname ?></span></center>
         </div>
         <div class="row rowspacer" style="min-width:400px;">  
            <form name="moduleextendforms" id="moduleextendforms" >
                <div class="eleven columns" style="float: left; font-weight: bold; font-size: 15px;margin-left:15px" >
                    <span style="color:red" >*</span>Extension Name: &nbsp;&nbsp;&nbsp;
                    <dl class="field row">
                       <dt class="text">
                          <input type="text" onblur="$(this).valid();" value="<?php echo $extendtxt; ?>" name="txtextensionname" id="txtextensionname" placeholder="Extension Name" />
                       </dt> 
                    </dl>
                </div>
            </form>
         </div>     
        <div class="row rowspacer" style="min-width:400px;">
             <div style="margin-left:220px;margin-right:10px;" >
                <input style="margin-right:10px;width:90px" onclick="fn_saveextendform('<?php echo $decryptmoduleid; ?>','<?php echo $extendid; ?>','<?php echo $type; ?>');" type="button" class="module-extend-button"   value="Save"   /> 
                <input type="button" style="width:90px" onclick="fn_cancelextendform();" class="module-extend-button"   value="Cancel"   /> 
            </div>
        </div>
    </div>
       <script type="text/javascript" language="javascript" >
	   $('#txtextensionname').keypress(function(event) {
			if (event.which == 13) {
				event.preventDefault();
				fn_saveextendform('<?php echo $decryptmoduleid; ?>','<?php echo $extendid; ?>','<?php echo $type; ?>');
			}
		});
       $("#moduleextendforms").validate({
                            ignore: "",
                            errorElement: "dd",
							errorPlacement: function(error, element) {
								$(element).parents('dl').addClass('error');
								error.appendTo($(element).parents('dl'));
								error.addClass('msg');
								window.scroll(0,($('dd').offset().top)-50);
							},
                            rules: { 
                                txtextensionname: { required: true }
                           }, 
                            messages: { 
                                txtextensionname: { required: "please type extension name" }, //, remote: "Module Name already exists"
                            },
                             highlight: function(element, errorClass, validClass) {
                                $(element).parents('dl').addClass(errorClass);
                                $(element).addClass(errorClass).removeClass(validClass);
                            },
                            unhighlight: function(element, errorClass, validClass) {
                                if($(element).attr('class') == 'error'){
                                    $(element).parents('dl').removeClass(errorClass);
                                    $(element).removeClass(errorClass).addClass(validClass);
                                }
                            },
                            onkeyup: false,
                            onblur: true
                        });
                  
       </script>
       
	<?php
  	
}

/*****this opertion can perform to save the form details of getting extend text in database****/
if($oper=='saveextendtxt' and  $oper!='')
{
	try {
         
	$expid= isset($method['md_id']) ? $method['md_id'] : '';
	$extendtext= isset($method['extendtxt']) ? ($method['extendtxt']) : '';
	$extendid= isset($method['extid']) ? $method['extid'] : '0';
	$type= isset($method['type']) ? $method['type'] : '';

	/**validation for the parameters and these below functions are validate to return true or false***/
	$validate_expid=true;
	$validate_extendid=true;
	
	if($expid!=0)  $validate_expid=validate_datatype($expid,'int');
	if($extendid!=0)  $validate_extendid=validate_datatype($extendid,'int');
	
		/**for purpose remove unwanted scripts****/
		$extendtext = $ObjDB->EscapeStrAll($extendtext);
		
	if($validate_expid and  $validate_extendid)
	{	
		if($type=='new' or $type=='copy')
		{
                    $created_name=$ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,' ',fld_lname) AS createdname 
                                                            FROM itc_user_master 
                                                            WHERE fld_id='".$uid."' AND fld_delstatus='0' ");
			
					if($type=='copy')
					{
						$qryselectcopy_map =$ObjDB->QueryObject("SELECT fld_extend_content_text as extentcontent, fld_exp_id as expid, fld_dest_id as destid,
																	fld_task_id as taskid, fld_rs_id as resourcid FROM itc_exp_extendtext_master_mapping_text															
																	WHERE fld_extend_id='".$extendid."' AND fld_delstatus='0'");

						$extendid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_exp_extendtext_master
																				(fld_exp_id,fld_extend_text,fld_school_id,fld_user_id,fld_created_by,fld_created_date,fld_created_name)
																				 	VALUES('".$expid."','".$extendtext."','".$schoolid."','".$indid."','".$uid."','".$date."','".$created_name."')");
						
						if($qryselectcopy_map->num_rows > 0)   
						{

							while($row=$qryselectcopy_map->fetch_assoc())
							{
								extract($row);
								$expmatlistid = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_exp_extendtext_master_mapping_text (fld_extend_content_text,fld_extend_id,fld_exp_id, fld_dest_id,fld_rs_id,fld_task_id,fld_created_by,fld_created_date)
                                                                             VALUES ('".$extentcontent."','".$extendid."','".$expid."','".$destid."','".$resourcid."','".$taskid."','".$uid."','".$date."')");
							}
						}
					}
					else
					{
	                   $extendid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_exp_extendtext_master
                                                          (fld_exp_id,fld_extend_text,fld_school_id,fld_user_id,fld_created_by,fld_created_date,fld_created_name)
                                                                                              VALUES('".$expid."','".$extendtext."','".$schoolid."','".$indid."','".$uid."','".$date."','".$created_name."')");

                    
					}
                    echo "sucess~".$extendid."~".$created_name."~".$uid."~".md5($expid)."~".$expid."~".$modulename."~".$modulefilename."~".$uid;
		}
		else
		{
			$ObjDB->NonQuery("UPDATE itc_exp_extendtext_master 
			                 SET fld_extend_text='".$extendtext."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
							 WHERE fld_id='".$extendid."'");
			echo "sucess";
		}
	}
	else
	{
		echo "fail";
	}
	  
	}
	catch(Exception  $e)
	{
		 echo "fail";
	}
	
}
if($oper=='deleteextend' and  $oper!='')
{
	$extendid= isset($method['ex_id']) ? $method['ex_id'] : '';
	$schflag= isset($method['schflag']) ? $method['schflag'] : '';
	
	$validate_extendid=true;
	
	if($extendid!=0)  $validate_expid=validate_datatype($extendid,'int');
	
	try {
		if($validate_extendid)
		{
		$ObjDB->NonQuery("UPDATE itc_exp_extendtext_master 
		                           SET fld_delstatus='1',fld_deleted_by='".$uid."',fld_deleted_date='".$date."'  
								   WHERE fld_id='".$extendid."'");
								   
		$ObjDB->NonQuery("UPDATE itc_exp_extendtext_master_mapping_text 
		                 SET fld_delstatus='1',fld_deleted_by='".$uid."',fld_deleted_date='".$date."' 
						 WHERE fld_extend_id='".$extendid."'  ");
						 
                if($schflag==1)
                {
                       $ObjDB->NonQuery("UPDATE itc_class_indasexpedition_expextcontent_mapping SET fld_active='0', fld_updatedby='".$uid."', fld_updateddate='".date("Y-m-d H:i:s")."' where fld_ext_id='".$extendid."'"); 
                }
	  echo "sucess";
		}
		else
		{
			echo "fail";
		}
	}
	catch(Exception  $e)
	{
		 echo "fail";
	}
	
}

/*****this opertion can perform to shosw the form of getting extend text****/
if($oper=='saveextendguidetips' and  $oper!='')
{
	$expid= isset($method['expid']) ? $method['expid'] : '';
	$extendid= isset($method['ex_id']) ? $method['ex_id'] : '0';
	$sectionid= isset($method['sectionid']) ? $method['sectionid'] : '';
	$pageid= isset($method['pageid']) ? $method['pageid'] : '';
	$contents= isset($method['contents']) ? $method['contents'] : '';
	$id= isset($method['id']) ? $method['id'] : '0';
	
	/**validation for the parameters and these below functions are validate to return true or false***/
	$validate_expid=true;
	$validate_extendid=true;
	
	if($expid!=0)  $validate_exp=validate_datatype($expid,'int');
	if($extendid!=0)  $validate_extendid=validate_datatype($extendid,'int');
	
	try
	{
		if($validate_expid and  $validate_extendid){
			if($id==0){
				$id=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_exp_extendtext_master_mapping_text(fld_exp_id,fld_extend_content_text,
											 fld_section_id,fld_extend_id,fld_page_id,fld_created_by,fld_created_date)
											 VALUES ('".$expid."','".$contents."','".$sectionid."','".$extendid."','".$pageid."','".$uid."','".$date."')");
			}
			else{
				$ObjDB->NonQuery("UPDATE itc_exp_extendtext_master_mapping_text 
								 SET fld_extend_content_text='".$contents."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
								 WHERE fld_id='".$id."' ");
				
			}
	     	echo "success~".$id;
		}
		else
		{
			echo "fail";
		}
	}
	catch(Exception $e)
	{
		echo "fail";
	}
}
if($oper=='getextendguidetips' and  $oper!='')
{
	$expid= isset($method['expid']) ? $method['expid'] : '0';
	$extendid= isset($method['ex_id']) ? $method['ex_id'] : '0';
	$sectionid= isset($method['sectionid']) ? $method['sectionid'] : '0';
	$pageid= isset($method['pageid']) ? $method['pageid'] : '0';
	
	$qryforextenttxt=$ObjDB->QueryObject("SELECT fld_extend_content_text,fld_id,fld_created_by 
	                                     FROM itc_exp_extendtext_master_mapping_text 
										 WHERE fld_exp_id='".$expid."' AND fld_page_id='".$pageid."' 
										 AND fld_section_id='".$sectionid."' AND fld_extend_id='".$extendid."' AND  fld_delstatus='0'");
	if($qryforextenttxt->num_rows>0)
	{
		$dataqryforextenttxt = $qryforextenttxt->fetch_assoc();
		extract($dataqryforextenttxt);	
		echo $fld_extend_content_text."~".$fld_id."~".$fld_created_by;
	}
	else
	{
		echo "fail";
	}
	
	
}

if($oper=='editextendguidetips' and  $oper!='')
{
	$expid= isset($method['expid']) ? $method['expid'] : '0';
	$extendid= isset($method['ex_id']) ? $method['ex_id'] : '0';
	$sectionid= isset($method['sectionid']) ? $method['sectionid'] : '0';
	$pageid= isset($method['pageid']) ? $method['pageid'] : '0';
	$id= isset($method['id']) ? $method['id'] : '0';
	
	
	$qryforextenttxt=$ObjDB->QueryObject("SELECT fld_extend_content_text,fld_id 
	                                     FROM itc_exp_extendtext_master_mapping_text 
										 WHERE fld_exp_id='".$expid."' AND fld_page_id='".$pageid."' 
										 AND fld_section_id='".$sectionid."' AND fld_extend_id='".$extendid."' AND fld_delstatus='0'");
	if($qryforextenttxt->num_rows>0)
	{
		$dataqryforextenttxt = $qryforextenttxt->fetch_assoc();
		extract($dataqryforextenttxt);	
		echo $fld_extend_content_text."~".$id;
	}
	else
	{
	  echo "fail";
	}
}

if($oper=='deleteextendguidetips' and  $oper!='')
{
	$id= isset($method['id']) ? $method['id'] : '0';
	
	try{
	  
	  $ObjDB->NonQuery("UPDATE itc_exp_extendtext_master_mapping_text 
	                   SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."' 
	                   WHERE fld_id='".$id."'");
	  echo "success";
	}
	catch(Exception $e)
	{
		echo "fail";
	}
}

if($oper=='checkextendcontent' and  $oper!='')
{
	$id= isset($method['ex_id']) ? $method['ex_id'] : '0';
	
	try{

	 $chk = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM `itc_class_indasexpedition_expextcontent_mapping` 
                                                 WHERE fld_ext_id='".$id."' AND fld_active='1'");
	
		if($chk==0)
		{
			echo "success";
		}
		else
		{
			echo "fail";
		}
	  
	}
	catch(Exception $e)
	{
		echo "fail"; 
	}
}

if($oper=="extendpages" and $oper != "" ){
	$extendid = isset($method['extendid']) ? $method['extendid'] : '';
	$sessionid = isset($method['sessionid']) ? $method['sessionid'] : '';
	$expid = isset($method['expid']) ? $method['expid'] : '';
	
	$extend = $ObjDB->QueryObject("SELECT IFNULL(GROUP_CONCAT(fld_page_id),'-') AS pageids
									FROM itc_exp_extendtext_master_mapping_text
									WHERE fld_section_id='".$sessionid."' AND fld_exp_id='".$expid."' AND fld_extend_id='".$extendid."' AND fld_delstatus='0'");
											
	if($extend->num_rows > 0){
		$rowextend = $extend->fetch_assoc();
		extract($rowextend);
		
		$extendids = $pageids;
	}
	else
	{
		$extendids = '';
	}
	
	echo $extendids;					
}

@include("footer.php");

