<?php
/*------
	Page - User Information
	Description:
		User details
	History:	
------*/
	@include("sessioncheck.php");
	
	/****declaration part****/
	$fname='';
	$lname='';
	$email='';
	$arrcombine=array('','','','','','','','','','','','');
	
	$getdetails = $ObjDB->QueryObject("SELECT fld_id AS id, fld_username AS uname, fld_password AS password, fld_email AS usermail, 
										fld_fname AS fname, fld_lname AS lname, fld_profile_id AS usrprfid, fld_profile_pic AS pphoto 
									FROM itc_user_master 
									WHERE fld_id='".$uid."'"); 
	$rowgetdet = $getdetails->fetch_assoc();
	extract($rowgetdet);
	$password = fnDecrypt($password,$encryptkey);
	
	$btnclick = "fn_cancel('tools')";
		
	$arrfieldid=array();
	$arrfieldvalue=array();
	
	$optionaldet = $ObjDB->QueryObject("SELECT fld_field_id,fld_field_value FROM itc_user_add_info WHERE fld_user_id='".$id."'");
	$rows=$optionaldet->num_rows;
	if($rows !=0){
		while($rowoptionaldet=$optionaldet->fetch_object())
		{
			array_push($arrfieldid,$rowoptionaldet->fld_field_id);
			array_push($arrfieldvalue,$rowoptionaldet->fld_field_value);
		}
		 $arrcombine=array_combine($arrfieldid,$arrfieldvalue);
		 $arrcombine=getarrayvalues($arrfieldid,$arrcombine);
	}
	
	if($pphoto == 'no-image.png' or $pphoto == '')
		$pphoto1 = "<img src='img/no-image.png' />";
	else
		$pphoto1 = "<img src=thumb.php?src=".__CNTPPPATH__.$pphoto."  width='100' height='100' /> ";

?>
<script> $.getScript("tools/myinfo/tools-myinfo.js");</script>
<section data-type='2home' id='tools-myinfo'>
    <div class='container'>
    	<div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">My Information</p>
        		<p class="dialogSubTitleLight">&nbsp;</p>
            </div>
        </div>
         <div class='row formBase'>
       		<div class='eleven columns centered insideForm'>
                <form name="myinfoform" id="myinfoform">
                	<div class="row rowspacer">
                        <div class="six columns">
                        <div class="title-info">My information (required)</div>
                        	First name<span class="fldreq">*</span> 
                            <dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='Enter your first name' type='text' name="txtfirstname" id="txtfirstname" value="<?php echo $fname; ?>"  tabindex="2" />
                                </dt>
                            </dl>
                          </div>      
                          <div class="six columns">
                          <div class="title-info">My Additional information (optional)</div>
                          Street Address
                                <dl class='field row'>
                                    <dt class='text'>
                                       <input placeholder='Enter your street address' type='text' name="txtaddress" id="txtaddress" value="<?php echo $arrcombine[7]; ?>"  tabindex="2" />
                                    </dt>
                                </dl>     
                            </div>
                       </div>
                       <div class="row rowspacer">
                        <div class="six columns">
                        Last name<span class="fldreq">*</span> 
                            <dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='Enter your last name' type='text' name="txtlastname" id="txtlastname" value="<?php echo $lname; ?>" onBlur="$(this).valid();" tabindex="2" />
                                </dt>
                            </dl>
                          </div>      
                          <div class="six columns">
                          Select state
                                <dl class='field row '>
                            	<dt class="dropdown"> 
                            		<?php $statename = $ObjDB->SelectSingleValue("SELECT DISTINCT(fld_statename) FROM itc_state_city WHERE fld_statevalue='".$arrcombine[8]."'"); ?>    
                                	<div class="selectbox">
                                  		<input type="hidden" name="ddlstate" id="ddlstate" value="<?php echo $arrcombine[8];?>" onchange="$('#ddlstate').valid();fn_changecity(this.value);">
                                        <a class="selectbox-toggle" tabindex="3" role="button" data-toggle="selectbox" href="#">
	                                        <span class="selectbox-option input-medium" data-option="<?php echo $arrcombine[8];?>"><?php if($arrcombine[8] == ''){ echo "Select State";} else {echo $statename;}?></span><b class="caret1"></b>
                                        </a>
                                        <div class="selectbox-options">
                                        	<input type="text" class="selectbox-filter" placeholder="Search select" >
                                            <ul role="options">
                                                <?php 
                                                    $stateqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_statevalue), fld_statename FROM itc_state_city WHERE fld_delstatus=0 ORDER BY fld_statename ASC");
                                                    while($rowstate = $stateqry->fetch_object()){ ?>
                                                            <li><a href="#" data-option="<?php echo $rowstate->fld_statevalue;?>"><?php echo $rowstate->fld_statename;?></a></li>
                                                    <?php 
                                                    }?>       
                                            </ul>
                                        </div>
                                	</div>
                        		</dt>
                        	</dl>     
                            </div>
                       </div>
                       <div class="row rowspacer">
                        <div class="six columns">
                             <?php if($sessmasterprfid!=10){?>   
                             E-Mail<span class="fldreq">*</span>            
                            <dl class='field row '>
                                <dt class='text'>
                                    <input placeholder='Enter your Email' type='text' name="txtemail" id="txtemail" value="<?php echo $usermail; ?>" onBlur="$(this).valid();" tabindex="2" />
                                </dt>
                            </dl>  
                            <?php } ?> 
                          </div>      
                          <div class="six columns">
                          Select City
                                <dl class='field row '>
                            	<dt class='dropdown' id="divddlcity">
                                	<div class="selectbox">
                                  		<input type="hidden" name="ddlcity" id="ddlcity" value="<?php echo $arrcombine[9];?>" onchange="fn_changezip(this.value);" >
                                        <a class="selectbox-toggle" tabindex="11" role="button" data-toggle="selectbox" href="#">
                                        	<span class="selectbox-option input-medium" data-option="<?php echo $arrcombine[9];?>"><?php if($arrcombine[9] ==''){ echo "Select city"; } else {echo $arrcombine[9]; }?></span><b class="caret1"></b>
                                        </a>
                                	</div>
                            	</dt>
                        	</dl>     
                            </div>
                       </div>
                       <div class="row rowspacer">
                        <div class="six columns">
                        User name<span class="fldreq">*</span> 
                            <dl class='field row '>
                                <dt class='text'>
                                    <input placeholder='Enter your user name' type='text' name="txtusername" id="txtusername" <?php if($sessmasterprfid == 10) { ?> readonly title="username cannot be modified" class="tooltip"<?php }?> value="<?php echo $uname; ?>" onBlur="$(this).valid();" tabindex="2" />
                                </dt>
                            </dl>
                          </div>      
                          <div class="six columns">
                          Select zip
                                <dl class='field row '>
                            	<dt class='dropdown' id="divddlzip">
                                	<div class="selectbox">
                                      <input type="hidden" name="ddlzip" id="ddlzip" value="<?php echo $arrcombine[10];?>">
                                      <a class="selectbox-toggle" tabindex="12" role="button" data-toggle="selectbox" href="#">
                                        <span class="selectbox-option input-medium" data-option="<?php echo $arrcombine[10];?>"><?php if($arrcombine[10] ==''){ echo "Select zip"; } else {echo $arrcombine[10]; }?></span>
                                        <b class="caret1"></b>
                                      </a>
                                    </div>
                            	</dt>
                        	</dl>     
                            </div>
                       </div>
                       <div class="row rowspacer">
                        <div class="six columns">
                       	Password<span class="fldreq">*</span> 
                            <dl class='field row '>
                                <dt class='text'>
                                    <input placeholder='Enter your password' type="password" name="txtpassword" id="txtpassword" value="<?php echo $password; ?>" onBlur="$(this).valid();" tabindex="2" />
                                </dt>
                            </dl>
                          </div>      
                          <div class="six columns">
                          Office number
                                <dl class='field row'>
                                    <dt class='text'>
                                    	<input placeholder='Enter your office number' type='text' name="txtofficeno" id="txtofficeno" value="<?php echo $arrcombine[3]; ?>"  tabindex="2" />
                                	</dt>
                                </dl>     
                            </div>
                       </div>
                       <div class="row rowspacer">
                        <div class="six columns">
                        Confirm Password<span class="fldreq">*</span> 
                            <dl class='field row '>
                                <dt class='text'>
                                    <input placeholder='Enter your confirm password' type="password" name="txtconfirmpassword" id="txtconfirmpassword" value="<?php echo $password; ?>" onBlur="$(this).valid();" tabindex="2" />
                                </dt>
                            </dl>
                          </div>      
                          <div class="six columns">
                          Fax number
                                <dl class='field row'>
                                    <dt class='text'>
                                    	<input placeholder='Enter your fax number' type='text' name="txtfaxno" id="txtfaxno" value="<?php echo $arrcombine[4]; ?>"  tabindex="2" />
                                	</dt>
                                </dl>     
                            </div>
                       </div>
                       <div class="row rowspacer">
                        <div class="six columns">
                            <dl class='field row '>
                                <dl class='field row '>
                              	<div class="row">
                                	<div class="six columns">
                                        <p>
                                            <a id="imgphoto"></a>
                                        </p>
                                    </div>
                                	<div class="six columns" id="upload-phleft">
			                          	<?php echo $pphoto1;?>
                                    </div>
                                </div>
                                <input type="hidden" name="hiduploadfile" id="hiduploadfile" value="<?php echo $pphoto;?>" />   
                            </dl>
                            </dl>
                          </div>      
                          <div class="six columns">
                          Mobile number
                                <dl class='field row'>
                                    <dt class='text'>
                                    	<input placeholder='Enter your mobile number' type='text' name="txtmobileno" id="txtmobileno" value="<?php echo $arrcombine[5]; ?>"  tabindex="2" />
                                	</dt>
                                </dl>  
                          </div>
                          <div class="six columns">
                            <dl class='field row rowspacer'>
                             Home number
                                <dt class='text'>
                                	<input placeholder='Enter your home number' type='text' name="txthomeno" id="txthomeno" value="<?php echo $arrcombine[6]; ?>"  tabindex="2" />
                            	</dt>
                            </dl>   
                          </div>
                       </div>
                       
                   
                       <div class='row'>
                        <div class='four columns btn primary push_two noYes'>
                            <a onclick="<?php echo $btnclick;?>" tabindex="4">Cancel</a>
                        </div>
                        <div class='four columns btn secondary yesNo'>
                            <a onclick="fn_updatemyinfo(<?php echo $id;?>)" tabindex="3">Save Changes</a>
                        </div>
                    </div>
                    
                    
                       
                </form>
            </div>
         </div>       
    </div>
</section>
<script language="javascript" type="text/javascript">

	<?php $timestamp = time();?>
		$('#imgphoto').uploadify({
					'formData'     : {
						'timestamp' : '<?php echo $timestamp;?>',
						'token'     : '<?php echo md5('nanonino' . $timestamp);?>',
						'oper'      : 'profile-pic' 
					},
					 'height': 40,
					 'width':160,
					'fileSizeLimit' : '2MB',
					'swf'      : 'uploadify/uploadify.swf',
					'uploader' : '<?php echo _CONTENTURL_;?>uploadify.php',
					'multi':false,
					'buttonText' : 'Upload Photo',
					'removeCompleted' : true,
					'fileTypeExts' : '*.gif; *.jpg; *.png; *.jpeg; *.bmp;',
					'onUploadSuccess' : function(file, data, response) {
						$('#hiduploadfile').val(data);
						$('#upload-phleft').html('<img src="thumb.php?src=<?php echo __CNTPPPATH__; ?>'+data+'&w=100&h=106&q=100" />');
						$('#userphoto').removeClass('dim');   
			   
					 },
					 'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
					   $('#userphoto').addClass('dim');   
					}
					
				});
		
		$('#txtfaxno,#txtofficeno,#txtmobileno,#txthomeno').mask('(999) 999-9999');

			$(function(){
			$("#myinfoform").validate({
				ignore: "",
				errorElement: "dd",
				errorPlacement: function(error, element) {
					$(element).parents('dl').addClass('error');
					error.appendTo($(element).parents('dl'));
					error.addClass('msg'); 
				},
				rules: {
				 	txtfirstname: { required: true, lettersonly:true },
					txtlastname: { required: true, lettersonly:true },
					txtemail: { required: true, email: true },
					txtusername: { required: true, lettersonly:true, 
                                            remote:{ 
                                                        url: "tools/myinfo/tools-myinfo-ajax.php", 
                                                        type:"POST",  
                                                        data: {  
                                                                        stdid: function() {
                                                                        return '<?php echo $uid;?>';},
                                                                        oper: function() {
                                                                        return 'checkstdname';}

                                                         },
                                                         async:false 
                                                     }},
					txtpassword: { required: true },
					txtconfirmpassword: { required: true, equalTo: "#txtpassword" }
					
				},
			
				messages: {
					txtfirstname: { required: "please enter firstname" },
					txtlasttname: { required: "please enter lastname" },
					txtemail: { required: "please enter email", email: "Invalid email-id"},
					txtusername: { required: "please enter user name", remote: "username already exists" },
					txtpassword: { required: "please enter password" },
					txtconfirmpassword: { required: "please type subject name", equalTo: "Password Mismatch"}
					
				} ,
				highlight: function(element, errorClass, validClass) {
					$(element).parent('dl').addClass(errorClass);
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
		});
	</script>
<?php
	@include("footer.php");
						