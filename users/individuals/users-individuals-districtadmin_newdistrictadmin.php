
<?php
	@include("sessioncheck.php");
	
	$oper = isset($method['oper']) ? $method['oper'] : '';
	$editid =  isset($method['id']) ? $method['id'] : '';
	
	/****declaration part****/
	$fname='';
	$lname='';
	$email='';
    $username='';
	$pphoto='';
	$arrcombine=array('','','','','','','','','','','','','','','');
	
	$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname 
								FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
								WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='11' AND b.fld_access='1' AND a.fld_created_by='".$uid."' 
									AND a.fld_delstatus='0' AND b.fld_item_id='".$editid."'");
	if($editid != 0){
		
		$selectdet = $ObjDB->QueryObject("SELECT a.fld_username AS username, a.fld_email AS email,a.fld_fname AS fname,a.fld_lname AS lname,a.fld_district_id AS distid,a.fld_school_id AS shlid,
											a.fld_profile_pic AS pphoto,b.fld_district_name AS distname, b.fld_city AS city,b.fld_state AS state,c.fld_statename AS statename 
										FROM itc_user_master AS a RIGHT JOIN itc_district_master AS b ON a.fld_district_id=b.fld_id 
										RIGHT JOIN itc_state_city AS c ON b.fld_state=c.fld_statevalue
										WHERE a.fld_id= '".$editid."' AND a.fld_delstatus='0' AND c.fld_statename <>'' GROUP BY a.fld_id");
			
		$row=$selectdet->fetch_assoc();
		extract($row);
		
		if($pphoto == '' or $pphoto == "undefined" or $pphoto == "no-image.png"){ $pphoto1 = "<img src='img/no-image.png'/>"; }
		else{ $pphoto1 = "<img src=thumb.php?src=".__CNTPPPATH__.$pphoto."  width='100' height='100' /> "; }
		
		$arrfieldid=array();
		$arrfieldvalue=array();
		
		$optionaldet = $ObjDB->QueryObject("SELECT fld_field_id,fld_field_value FROM itc_user_add_info WHERE fld_user_id='".$editid."'");
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
		
	}
?>
<script type="text/javascript" charset="utf-8">	
	
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_newdistrict', 
		{
			unique: true, plugins: {autocomplete: {}},
			bitsOptions:{editable:{addKeys: [188]}}	});
		<?php 
			if($qrytag->num_rows > 0) {
				while($restag = $qrytag->fetch_assoc()){
					extract($restag);
		?>
				t4.add('<?php echo $ObjDB->EscapeStrAll($tagname); ?>','<?php echo $tagid; ?>');				
		<?php 	}
			}
		?>				
		t4.getContainer().addClass('textboxlist-loading');				
		$.ajax({url: 'autocomplete.php', data: 'oper=new', dataType: 'json', success: function(r){
			t4.plugins['autocomplete'].setValues(r);
			t4.getContainer().removeClass('textboxlist-loading');					
		}});						
	});
</script>
<section data-type='#users-individuals' id='users-individuals-districtadmin_newdistrictadmin'>
    <div class='container'>
        <div class='row'>
          <div class='twelve columns'>
          	<p class="dialogTitle"><?php if($editid == 0){ echo "New District Admin";} else { echo $fname." ".$lname." "."District Admin";} ?></p>
            <p class="dialogSubTitleLight">&nbsp;</p>
          </div>
        </div>
    
    <div class='row formBase'>
        <div class='eleven columns centered insideForm'>
           <form method='post' name="dadminval" id="dadminval">
           <div class="row">
                <div class="six columns">
                	<div class="title-info">District Admin Information (Required)</div>
                     Select state<span class="fldreq">*</span> 
                    <dl class='field row'>
                        <dt class='dropdown'>
                            <div class="selectbox">
                             <input type="hidden" name="ddlstate" id="ddlstate" value="<?php echo $state;?>" onchange="$('#ddlstate').valid();fn_changecity(this.value);">
                            <a class="selectbox-toggle" tabindex="1" role="button" data-toggle="selectbox" href="#">
                            <span class="selectbox-option input-medium" data-option="<?php if($editid==0){ echo "0";} else {echo $state;}?>"><?php if($editid == 0){ echo "Select state";} else {echo $statename;}?></span>
                            <b class="caret1"></b>
                            </a>
                            <?php if($editid ==0) { ?>
                                <div class="selectbox-options">
                                <input type="text" class="selectbox-filter" placeholder="Search state" >
                                    <ul role="options">
                                        <?php 
                                            $stateqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_statevalue), fld_statename FROM itc_state_city WHERE fld_delstatus=0 ORDER BY fld_statename ASC");
                                            while($rowstate = $stateqry->fetch_object()){ ?>
                                                    <li><a href="#" data-option="<?php echo $rowstate->fld_statevalue;?>"><?php echo $rowstate->fld_statename;?></a></li>
                                            <?php 
                                            }?>       
                                    </ul>
                                </div>
                            <?php } ?>
                            </div>
                        </dt>
                    </dl> 
                </div>
                <div class="six columns">
                	<div class="title-info">District Admin Additional Information (Optional)</div>
                    Select state
                    <dl class='field row'>
                        <dt class='dropdown'>
                            <div class="selectbox">
                            <?php $statename1 = $ObjDB->SelectSingleValue("SELECT DISTINCT(fld_statename) FROM itc_state_city WHERE fld_statevalue='".$arrcombine[8]."'"); ?>
                            <input type="hidden" name="ddlstate1" id="ddlstate1" value="<?php echo $arrcombine[8];?>" onchange="fn_changecity1(this.value);">
                            <a class="selectbox-toggle" tabindex="7" role="button" data-toggle="selectbox" href="#">
                            <span class="selectbox-option input-medium" data-option="<?php if($editid==0){ echo "0";} else {echo $arrcombine[8];}?>"><?php if($editid == 0 or $arrcombine[8] == ''){ echo "Select state";} else {echo $statename1;}?></span>
                            <b class="caret1"></b>
                            </a>
                                <div class="selectbox-options">
                                <input type="text" class="selectbox-filter" placeholder="Search state" >
                                    <ul role="options">
                                    <?php 
                                    $stateqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_statevalue), fld_statename FROM itc_state_city WHERE fld_delstatus=0 ORDER BY fld_statename ASC");
                                    while($rowstate = $stateqry->fetch_object()){ ?>
                                            <li><a  href="#" data-option="<?php echo $rowstate->fld_statevalue;?>"><?php echo $rowstate->fld_statename;?></a></li>
                                    <?php 
                                    }?>       
                                    </ul>
                                </div>
                            </div>
                        </dt>
                    </dl>
                </div>
            </div>
            
            <div class='field row'> </div> 
            <div class="row">
                <div class="six columns">
                 Select city<span class="fldreq">*</span> 
                    <dl class='field row' id="cit">
                        <dt class='dropdown'>
                            <div id="divddlcity">
                                <div class="selectbox">
                                  <input type="hidden" name="ddlcity" id="ddlcity" value="<?php echo $city;?>" >
                                  <a class="selectbox-toggle" tabindex="2" role="button" data-toggle="selectbox" href="#">
                                    <span class="selectbox-option input-medium" data-option=""><?php if($editid == 0) { echo "Select City"; } else { echo $city; }?></span>
                                    <b class="caret1"></b>
                                  </a>
                                </div>
                            </div>
                        </dt>
                    </dl> 
                </div>
                <div class="six columns">
                Select city
                    <dl class='field row'>
                        <dt class='dropdown'>
                            <?php if($editid == 0){ ?>
                            <div id="divddlcity1">
                                <div class="selectbox">
                                  <input type="hidden" name="ddlcity1" id="ddlcity1" value="" disabled="disabled" >
                                  <a class="selectbox-toggle" tabindex="8" role="button" data-toggle="selectbox" href="#">
                                    <span class="selectbox-option input-medium" data-option=""> Select city</span>
                                    <b class="caret1"></b>
                                  </a>
                                </div>
                            </div>
                            <?php } else {?>
                            <div id="divddlcity1">
                                <div class="selectbox" >
                                  <input type="hidden" name="ddlcity1" id="ddlcity1" value="<?php echo $arrcombine[9];?>" onchange="fn_changezip1(this.value);" >
                                  <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                    <span class="selectbox-option input-medium" data-option="<?php echo $arrcombine[9];?>"><?php if($arrcombine[9] ==''){ echo "Select city"; } else {echo $arrcombine[9]; }?></span>
                                    <b class="caret1"></b>
                                  </a>
                                  <div class="selectbox-options">
                                    <input type="text" class="selectbox-filter" placeholder="Search city" >
                                    <ul role="options">
                                        <?php 
                                            $cityqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_cityname) FROM itc_state_city WHERE fld_statevalue='".$arrcombine[8]."' AND fld_delstatus=0 ORDER BY fld_cityname ASC");
                                           while($rowcity = $cityqry->fetch_object()){?>
                                                    <li><a tabindex="1" href="#" data-option="<?php echo ucwords(strtolower($rowcity->fld_cityname));?>"><?php echo  ucwords(strtolower($rowcity->fld_cityname))?></a></li>
                                            <?php 
                                            }?>       
                                    </ul>
                                  </div>
                                </div>
                            </div> 
                            <?php } ?>  
                        </dt>
                    </dl>
                </div>
            </div>
            
            <div class='field row'> </div> 
            <div class="row">
                <div class="six columns">
                 Select district<span class="fldreq">*</span> 
                    <dl class='field row' id="dit">
                        <dt class='dropdown'>
                            <div id="divddldist">
                                <div class="selectbox">
                                  <input type="hidden" name="ddldist" id="ddldist" value="<?php echo $distid ?> ">
                                  <a class="selectbox-toggle" tabindex="3" role="button" data-toggle="selectbox" href="#">
                                    <span class="selectbox-option input-medium" data-option=""><?php if($editid == 0){ echo "Select district"; } else { echo $distname; } ?> </span>
                                    <b class="caret1"></b>
                                  </a>
                                </div>
                            </div>
                        </dt>
                    </dl> 
                </div>
                <div class="six columns">
                Street Address
                    <dl class='field row'>
                        <dt class='text'>
                           <input  id="address" name="address" placeholder='Street address' tabindex="9" type='text' value="<?php echo $arrcombine[7];?>">
                        </dt>
                    </dl>
                </div>
            </div>
            
            <div class="row">
                <div class="six columns">
                First Name<span class="fldreq">*</span> 
                    <dl class='field row'>
                        <dt class='text'>
                           <input id="fname" name="fname"  placeholder='First name' tabindex="4" type='text' value="<?php echo $fname;?>">
                        </dt>
                    </dl> 
                </div>
                <div class="six columns">
                Select zip
                    <dl class='field row'>
                        <dt class='dropdown'>
                            <?php if($editid == 0){ ?>
                            <div id="divddlzip1">
                                <div class="selectbox">
                                  <input type="hidden" name="ddlzip1" id="ddlzip1" value="" disabled="disabled" >
                                  <a class="selectbox-toggle" tabindex="10" role="button" data-toggle="selectbox" href="#">
                                    <span class="selectbox-option input-medium" data-option=""> Select zip</span>
                                    <b class="caret1"></b>
                                  </a>
                                </div>
                            </div>
                            <?php } else { ?>
                            <div id="divddlzip1">
                                <div class="selectbox">
                                  <input type="hidden" name="ddlzip1" id="ddlzip1" value="<?php echo $arrcombine[10];?>">
                                  <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#" >
                                    <span class="selectbox-option input-medium" data-option="<?php echo $arrcombine[10];?>"><?php if($arrcombine[10] ==''){ echo "Select zip"; } else {echo $arrcombine[10]; }?></span>
                                    <b class="caret1"></b>
                                  </a>
                                  <div class="selectbox-options">
                                    <input type="text" class="selectbox-filter" placeholder="Search zip" >
                                    <ul role="options">
                                        <?php 
                                            $zipqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_zipcode) FROM itc_state_city WHERE fld_cityname='".$arrcombine[9]."' AND fld_delstatus=0 ORDER BY fld_zipcode ASC");
                                           while($rowzip = $zipqry->fetch_object()){?>
                                                    <li><a tabindex="1" href="#" data-option="<?php echo $rowzip->fld_zipcode;;?>"><?php echo $rowzip->fld_zipcode;?></a></li>
                                            <?php 
                                            }?>       
                                    </ul>
                                  </div>
                                </div>
                            </div>
                            <?php } ?>
                        </dt>
                    </dl>
                </div>
            </div>
            
            <div class="row">
                <div class="six columns">
              	Last Name<span class="fldreq">*</span> 
                    <dl class='field row'>
                        <dt class='text'>
                            <input id="lname" name="lname" placeholder='Last name' tabindex="5" type='text' value="<?php echo $lname;?>">
                        </dt>
                    </dl> 
                </div>
                <div class="six columns">
                Office Number
                    <dl class='field row'>
                        <dt class='text'>
                            <input id="officeno" name="officeno" tabindex="11" placeholder='Office number' type='text' value="<?php echo $arrcombine[3];?>">
                        </dt>
                    </dl>
                </div>
            </div>
            
            <div class="row">
                <div class="six columns">
                Email-id<span class="fldreq">*</span> 
                    <dl class='field row'>
                        <dt class='text'>
                            <input id="email" name="email" placeholder='Email-id' tabindex="6" type="email" value="<?php echo $email;?>">
                        </dt>
                    </dl> 
                </div>
                <div class="six columns">
                Fax Number
                    <dl class='field row'>
                        <dt class='text'>
                            <input id="faxno" name="faxno" placeholder='Fax number' tabindex="12" type='text' value="<?php echo $arrcombine[4];?>">
                        </dt>
                    </dl>
                </div>
            </div>
            
            <div class="row">
                <div class="six columns">
                    Username
                    <dl class='field row'>
                        <dt class='text'>
                            <input id="username" name="username" value="<?= $username;?>" disabled>
                        </dt>
                    </dl>
                </div>
                <div class="six columns">
                Mobile Number
                    <dl class='field row'>
                        <dt class='text'>
                            <input id="mobileno" name="mobileno" placeholder='Mobile number' tabindex="13" type='text' value="<?php echo $arrcombine[5];?>">
                        </dt>
                    </dl>
                </div>
            </div>
            
            <div class="row">
                <div class="three columns">
                	<dl class='field row'>
                        <dt>
                            <div class="upload-ph">
                                <div class="upload-phright"><?php if($editid == 0){ ?><img src="img/no-image.png" /> <?php } else { echo $pphoto1;}?> </div>
                            </div>
                        </dt>
                    </dl>
                </div>
                <div class="three columns">
                	<dl class='field row'>
                        <dt>
                            <div>
                            <p><a id="imgphoto"> </a></p><br />
                             <div id="queue"> </div>
                            </div>
                        </dt>
                    </dl>
                </div>
                <div class="three columns">
                </div>
                <div class="three columns">
                    
                </div>
                <input type="hidden" name="hiduploadfile" id="hiduploadfile" value="<?php echo $pphoto;?>" />
            </div>
            
            <div class="row">
                <div class='twelve columns'>
                	To create new tag, type a name and press Enter.
                    <dl class='field row'>
                        <dt>
                            <div class="tag_well">
                                <input type="text" name="test3" value="" id="form_tags_newdistrict" />
                            </div>
                        </dt>                        
                    </dl>
                </div>
            </div>
            <div class='row spacer'></div>
            <script language="javascript" type="text/javascript">
			/* ----- For Profile picture ------*/
					<?php $timestamp = time();?>
					$('#imgphoto').uploadify({
								'formData'     : {
									'timestamp' : '<?php echo $timestamp;?>',
									'token'     : '<?php echo md5('nanonino' . $timestamp);?>',
									'oper'      : 'profile-pic' 
								},
								 'height': 40,
								 'width':160,
								 'queueID' : 'queue',
								'fileSizeLimit' : '2MB',
								'swf'      : 'uploadify/uploadify.swf',
								'uploader' : '<?php echo _CONTENTURL_;?>uploadify.php',
								'multi':false,
								'buttonText' : 'Upload Photo',
								'removeCompleted' : true,
								'fileTypeExts' : '*.gif; *.jpg; *.png; *.jpeg; *.bmp;',
								'onUploadSuccess' : function(file, data, response) {
									$('#hiduploadfile').val(data);
									$('.upload-phright').html('<img src="thumb.php?src=<?php echo __CNTPPPATH__; ?>'+data+'&w=100&h=106&q=100" />');
									$('#userphoto').removeClass('dim');   
						   
								 },
								 'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
								   $('#userphoto').addClass('dim');   
								}
								
							});
					$('#officeno,#faxno,#mobileno,#homeno').mask('(999) 999-9999');	
            </script>
            <div class="row rowspacer">
				<?php 
                    $status = $ObjDB->SelectSingleValueInt("SELECT fld_activestatus FROM itc_user_master WHERE fld_id='".$editid."' AND fld_delstatus='0'");
                    if($editid != 0){ ?>
                    <div class="six columns <?php if($status !=1){ echo "dim"; } ?>">
                        <p class='btn secondary twelve columns'>
                          <a tabindex="14" onclick="fn_resetda('<?php echo $username;?>')">Reset Password</a>
                        </p>
                    </div>
                 <?php }
                    if($editid != 0 and $status ==0){
                 ?>
                <div class="six columns">
                    <p class='btn secondary twelve columns'>
                       <a tabindex="15" onclick="fn_resendmail(<?php echo $editid;?>);">Resend Invitation</a>
                    </p>
                </div>
                <?php }?>
            </div>
            <div class="row">
                    <div class="six columns">
                        <p class='btn primary twelve columns'>
							 <?php if($editid == 0){ ?>
                                <a tabindex="17" onclick="fn_cancel('users-individuals-districtadmin');">Cancel</a>
                             <?php } else { ?>
                                <a tabindex="17" onclick="fn_deletdistrictadmin(<?php echo $editid;?>)">Delete</a>
                             <?php } ?>   
                        </p>
                    </div>
                    <div class="six columns" id="userphoto">
                        <p class='btn secondary twelve columns'>
                        	<a tabindex="18" onclick="fn_createdistrictadmin(<?php echo $editid;?>);"><?php if($editid==0) echo "Create District Admin"; else echo "Update District Admin";?></a>
                        </p>
                    </div>
                </div>
            </form>
        
         <script type="text/javascript" language="javascript">
            
            $(function(){
                $("#dadminval").validate({
                    ignore: "",
                    errorElement: "dd",
                    errorPlacement: function(error, element) {
                        $(element).parents('dl').addClass('error');
                        error.appendTo($(element).parents('dl'));
                        error.addClass('msg');
                    },
                    rules: {
						address: { letterswithbasicpunc:true },
                        fname: { required: true, lettersonly: true },
                        lname: { required: true, lettersonly: true },
                        email: { required: true, email: true },
                        ddlstate : { required: true },
                        ddlcity : { required: true },
                        ddldist : { required: true }
                    },
                    messages: {
                        fname: { required: "Please enter the first name" },
                        lname: { required: "Please enter the last name" },
                        email: { required: "Please enter the Email-id", email: "Invalid email-id" },
                        ddlstate : { required: "Please select state" },
                        ddlcity : { required: "Please select city" },
                        ddldist : { required: "Please select district" }
                        
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
            });
         </script>	
        </div>
    </div>
    </div>
</section>
<?php
	@include("footer.php");
