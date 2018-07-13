<?php 
/*
	Created By - Vijayalakshmi PHP Programmer
	Page - library-extend-ajax
	Description:
	   
	   This page can work on the depends oper which means opertions here few opertions going to do it . like saving extend module details 
	   
	History:
	 no - update

*/
/** This sessioncheck. php file will give the database connectivity ,seesion variable and some functions are added in comm_fun.php its also included in this file *****/

@include("sessioncheck.php"); 
$date = date("Y-m-d H:i:s");
$oper= isset($method['oper']) ? $method['oper'] : '';

/*****this opertion  performs to show the form for getting extend text(insert the extend name ****/

if($oper=='extendtxtform' and  $oper!='')
{
    
	$lessonid= isset($method['ln_id']) ? $method['ln_id'] : '';
	$extendid= isset($method['extid']) ? $method['extid'] : '0';
	$type= isset($method['type']) ? $method['type'] : '';
	$extendtxt='';
	
	$qryforgetlessondetails= $ObjDB->QueryObject("SELECT fld_ipl_name as lessonname,fld_id as decryptlessonid 
	                                             FROM itc_ipl_master 
	                                             WHERE md5(fld_id)='".$lessonid."'");
										   
	$rowqryforgetlessondetails = $qryforgetlessondetails->fetch_assoc();
	extract($rowqryforgetlessondetails);
	
	if($type=='rename' or $type=='copy')
	{
		$extendtxt= $ObjDB->SelectSingleValue("SELECT fld_extend_text 
		                                       FROM itc_ipl_extendtext_master WHERE fld_id='".$extendid."'");
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
            <center><span style="font-size:24px;" class="darkTitle"><?php echo $typename; ?> <?php echo $lessonname; ?></span></center>
         </div>
         <div class="row rowspacer" style="min-width:400px;">  
            <form name="lessonextendforms" id="lessonextendforms" >
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
                <input style="margin-right:10px;width:90px" onclick="fn_saveextendlessonform('<?php echo $decryptlessonid; ?>','<?php echo $extendid; ?>','<?php echo $type; ?>');" type="button" class="lesson-extend-button"   value="Save"   /> 
           <input type="button" style="width:90px" onclick="fn_cancelextendform();" class="lesson-extend-button"   value="Cancel"   /> 
            </div>
        </div>
    </div>
       <script type="text/javascript" language="javascript" >
	   $('#txtextensionname').keypress(function(event) {
               
			if (event.which == 13) {
				event.preventDefault();
				fn_saveextendform('<?php echo $decryptlessonid; ?>','<?php echo $extendid; ?>','<?php echo $type; ?>');
			}
		});
       $("#lessonextendforms").validate({
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
                                txtextensionname: { required: "please type extension name" }, //, remote: "Lesson Name already exists"
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
         
	$lessonid= isset($method['ln_id']) ? $method['ln_id'] : '';
	$extendtext= isset($method['extendtxt']) ? ($method['extendtxt']) : '';
	$extendid= isset($method['extid']) ? $method['extid'] : '0';
	$type= isset($method['type']) ? $method['type'] : '';

	/**validation for the parameters and these below functions are validate to return true or false***/
	$validate_lessonid=true;
	$validate_extendid=true;
	
	if($lessonid!=0)  $validate_lessonid=validate_datatype($lessonid,'int');
	if($extendid!=0)  $validate_extendid=validate_datatype($extendid,'int');
	
		/**for purpose remove unwanted scripts****/
		$extendtext = $ObjDB->EscapeStrAll($extendtext);
		
	if($validate_lessonid AND  $validate_extendid)
	{
         
		if($type=='new' or $type=='copy')
		{
	    
		 $created_name=$ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,' ',fld_lname) AS createdname 
		                                         FROM itc_user_master 
		                                         WHERE fld_id='".$uid."' AND fld_delstatus='0' ");
		
		$extendid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_ipl_extendtext_master
		                                       (fld_lesson_id,fld_extend_text,fld_school_id,fld_user_id,fld_created_by,fld_created_date,fld_created_name)
											   VALUES('".$lessonid."','".$extendtext."','".$schoolid."','".$indid."','".$uid."','".$date."','".$created_name."')");
		
		
		$qryforgetlessondetails= $ObjDB->QueryObject("SELECT iplmaster.fld_ipl_name AS lessonname,iplversion.fld_zip_name AS zipname FROM itc_ipl_master AS iplmaster 
                                      LEFT JOIN itc_ipl_version_track AS iplversion ON iplmaster.fld_id=iplversion.fld_ipl_id
                                      WHERE iplmaster.fld_id='".$lessonid."' AND iplversion.fld_delstatus='0' AND iplmaster.fld_delstatus='0'");
                
                
               
		$rowqryforgetlessondetails = $qryforgetlessondetails->fetch_assoc();
	    extract($rowqryforgetlessondetails);
	$access=true;
		
		   echo "sucess~".$extendid."~".$created_name."~".$uid."~".md5($lessonid)."~".$lessonid."~".$lessonname."~".$zipname."~".$uid."~".$access; 
		}
		else
		{
			$ObjDB->NonQuery("UPDATE itc_ipl_extendtext_master 
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

/*****this opertion can perform to delete the existing extend text name in database****/

if($oper=='checkextendcontent' and  $oper!='')
{
	$id= isset($method['ex_id']) ? $method['ex_id'] : '0';
	
	try{

	 $chk = $ObjDB->SelectSingleValueInt("SELECT (SELECT COUNT(fld_id) FROM `itc_class_rotation_extcontent_mapping` 
 WHERE fld_ext_id='".$id."' AND fld_active='1')+ (SELECT COUNT(fld_id) FROM `itc_class_indassesment_extcontent_mapping`  WHERE fld_ext_id='".$id."' AND fld_active='1') + (SELECT COUNT(fld_id) FROM `itc_class_ipl_extcontent_mapping`  WHERE fld_ext_id='".$id."' AND fld_active='1')");
	
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


if($oper=='deleteextend' and  $oper!='')
{
	$extendid= isset($method['ex_id']) ? $method['ex_id'] : '';
	$schflag= isset($method['schflag']) ? $method['schflag'] : '';
	
	$validate_extendid=true;
	
	if($extendid!=0)  $validate_moduleid=validate_datatype($extendid,'int');
	
	try {
		if($validate_extendid)
		{
		$ObjDB->NonQuery("UPDATE itc_ipl_extendtext_master SET fld_delstatus='1',fld_deleted_by='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."'  
                                    WHERE fld_id='".$extendid."'");
								   
		$ObjDB->NonQuery("UPDATE itc_ipl_extendtext_master_mappingtext SET fld_delstatus='1',fld_deleted_by='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' 
						 WHERE fld_extend_id='".$extendid."'  ");
						 
                    if($schflag==1)
                    {                           
                           $ObjDB->NonQuery("UPDATE itc_class_ipl_extcontent_mapping SET fld_active='0', fld_updatedby='".$uid."', fld_updateddate='".date("Y-m-d H:i:s")."' where fld_ext_id='".$extendid."'"); 
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

@include("footer.php");
