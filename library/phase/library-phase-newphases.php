<?php
/*------
		Page - library-sosunits-newunits
		Description:
		Create or update the sosunit using subject and course
		
		Actions Performed:
		Create - Create a new unit
		Update - Update the existing unit
		Cancel - Redirect to library-sosunits.php page ( show units list )
		
		
		History:

------*/
	
	@include("sessioncheck.php");
	
   /****declaration part****/
	$unitname='';
	$assetid='';
	$uniticon='';
	
	$phaseid = isset($method['id']) ? $method['id'] : 0;	
	
	if($phaseid != '' and $phaseid!='undefined'){
		$pageTitle = "Edit Phase";
		$btnclick = "fn_cancel('library-phase-actions')";
		$btnvalue = "Update Phase";
		$btncancel = "Cancel";
	}
	else{
		$pageTitle = "New Phase";
		$btnclick = "fn_cancel('library-phase')";
		$btnvalue = "Create Phase";
		$btncancel = "Cancel";
		$phaseid=0;
	}
	
	/* The following query used to get the subject id,name and course id,name and unit id,name and unit icon from tables */
	
	$qry_phasedetails = $ObjDB->QueryObject("SELECT fld_unit_id as phaseunitid, fld_id AS phaseid, fld_phase_name AS phasename, fld_phase_icon AS phaseicon 
	                                        FROM itc_sosphase_master WHERE fld_id='".$phaseid."' AND fld_delstatus='0'");
	
	if($qry_phasedetails->num_rows>0)
	{
		$phasedetails = $qry_phasedetails->fetch_assoc();		
		extract($phasedetails);	
	}
?>

<!-- Autocomplete script start -->

<script type="text/javascript" charset="utf-8">	
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_newunit', 
		{
			unique: true, plugins: {autocomplete: {}},
			bitsOptions:{editable:{addKeys: [188]}}	});
		<?php 
			if($phaseid != '' and $phaseid!='undefined'){
				$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname 
				                              FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
											  WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='36' 
											        AND b.fld_access='1' AND a.fld_created_by='".$uid."' 
													AND a.fld_delstatus='0' AND b.fld_item_id='".$phaseid."'");
				if($qrytag->num_rows > 0) {
					
					while($restag = $qrytag->fetch_assoc()){
						
						extract($restag); ?>
            			t4.add('<?php echo $ObjDB->EscapeStrAll($tagname); ?>');				
			      <?php }
				}
			}
		?>				
		t4.getContainer().addClass('textboxlist-loading');				
		$.ajax({url: 'autocomplete.php', data: 'oper=new', type:"POST", dataType: 'json', success: function(r){
			t4.plugins['autocomplete'].setValues(r);
			t4.getContainer().removeClass('textboxlist-loading');					
		}});						
	});
</script>

<!-- Autocomplete script end -->

<section data-type='2home' id='library-phase-newphases'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle"><?php echo $pageTitle;?></p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>    
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
				<form name="phaseforms" id="phaseforms"> 
                     <!-- Unit name text box start -->            
                    <div class='row rowspacer'>
                        
                        <div class="six columns">
                             Unit<span class="fldreq">*</span>
                            <dl class='field row' id='unid'>  
                                <dt class='dropdown'>  
                                    <div id="unit"> <!-- Unit -->   
                                        <div class="selectbox">
                                            <input type="hidden" name="unitid" id="unitid" value="<?php echo $phaseunitid;?>"  onchange="$(this).valid();"  />
                                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                            	<span class="selectbox-option input-medium" data-option="<?php echo $phaseunitid;?>" id="clearunit">
                                                <?php
                                                if($phaseunitid!=''){
                                                $phaseunitname = $ObjDB->SelectSingleValue("SELECT fld_unit_name FROM itc_sosunit_master 
		                                      WHERE fld_id='".$phaseunitid."' AND fld_delstatus='0'"); 
                                                
                                                echo $phaseunitname; 
                                                }
                                                else{
                                                    echo "Select Unit";
                                                }
                                                ?>
                                                </span><b class="caret1"></b>
                                            </a>                                           
                                            <div class="selectbox-options">
                                                <input type="text" class="selectbox-filter" placeholder="Search Unit">
                                                <ul role="options">
                                                    <?php 
                                                    $unitqry = $ObjDB->QueryObject("SELECT fld_id AS unitid, fld_unit_name AS unitname FROM itc_sosunit_master WHERE fld_delstatus= '0' ORDER BY fld_unit_name ");
                                                    if($unitqry->num_rows > 0)
                                                    {
                                                        while($rowunit = $unitqry->fetch_assoc())
                                                        {
															extract($rowunit);
                                                        ?>
                                                            <li><a tabindex="-1" href="#" data-option="<?php echo $unitid;?>"><?php echo $unitname; ?></a></li>
                                                        <?php
                                                        }
                                                }                                               
                                                ?>       
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </dt>
                            </dl>
                        </div>
                        <div class='six columns'> 
                         	Phase Name<span class="fldreq">*</span> 
                            <dl class='field row'>
                                <dt class='text'>
                                	<input placeholder='New Phase Name' type='text' id="phasename" name="phasename" value="<?php echo $phasename; ?>" onBlur="$(this).valid();">
                                </dt>
                            </dl>
                        </div>
                        
                    </div>             
					<!-- Unit name text box End -->
                    
                    <!-- Upload unit icon start -->   
					<script type="text/javascript" language="javascript">
                        
                        //Asset ID should not accept all special symbols
                        var specialKeys = new Array();
                        specialKeys.push(8); //Backspace
                        specialKeys.push(9); //Tab
                        specialKeys.push(46); //Delete
                        specialKeys.push(36); //Home
                        specialKeys.push(35); //End
                        specialKeys.push(37); //Left
                        specialKeys.push(39); //Right
                        function IsAlphaNumeric(e) {
                        var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
                        var ret = ((keyCode >= 48 && keyCode <= 57 ) || (keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122) || (specialKeys.indexOf(e.keyCode) != -1 && e.charCode != e.keyCode) || (keyCode == 46));
                        return ret;
                        }

                        /***addd category validate****/
                        $(function(){
                            $("#phaseforms").validate({
                                ignore: "",
                                errorElement: "dd",
                                errorPlacement: function(error, element) {
                                $(element).parents('dl').addClass('error');
                                error.appendTo($(element).parents('dl'));
                                error.addClass('msg');
                                },

                                rules: {                                    
                                    phasename: { required: true,
									remote:{ 
												url: "library/phase/library-phase-ajax.php", 
												type:"POST",  
												data: {  
														uid: function() {
														return '<?php echo $phaseid;?>';},
														oper: function() {
														return 'checkphasename';}
														  
												 },
												 async:false 
										   }},
									
                                },
                            
                                messages: {                                   
                                    phasename: { required: "please type the phase name", remote: "phase name already exists" },
                                        } ,
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
							<?php $timestamp = time();?>
						$('#file_upload').uploadify({
									'formData'     : {
										'timestamp' : '<?php echo $timestamp;?>',
										'token'     : '<?php echo md5('nanonino' . $timestamp);?>',
										'oper'      : 'uniticon' 
									},
									 'height': 30,
									 'width':300,
									'fileSizeLimit' : '2MB',
									'swf'      : 'uploadify/uploadify.swf',
									'uploader' : '<?php echo _CONTENTURL_; ?>uploadify.php',
									'multi':false,
									'buttonText' : 'Upload',
									'removeCompleted' : true,
									'fileTypeExts' : '*.gif; *.jpg; *.png; *.jpeg; *.bmp;',
									'onUploadSuccess' : function(file, data, response) {
										//alert(data);
										$('#hiduploadfile').val(data);
                                        $('#uniticon').html('');
										$('#uniticon').html('<img src="thumb.php?src=<?php echo __CNTUNITICONPATH__; ?>'+data+'&w=100&h=106&q=100" />');
										$('#savebtnunits').removeClass('dim');   
                               
                                     },
									 'onUploadError' : function(file, errorCode, errorMsg, errorString) {
                                        alert('The file ' + file.name + ' could not be uploaded: ' + errorString+'  '+errorMsg+'  '+errorCode);
                                 },
									 'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
                                       $('#savebtnunits').addClass('dim');   
                                    }
									
								});
                    </script>
                    <div class='row rowspacer'>
                    	<div class='seven columns'>  
                        	<p class='lableRight'>Upload your icon using one of the following formats: .jpeg, .bmp, .gif, .png. </p>
                          
                             <dl class='field row'>                               
                                <input id="file_upload" name="file_upload" type="file" multiple>
                                <div id="queue"></div>
                            </dl>
                        </div>
                        
                        <div class='four columns'>  
                            <div   id="uniticon" >
                            <?php if($phaseicon==''){?><div class="iconPreview"></div><?php } ?>
                                    <?php 
                                    if($phaseicon!=''){
                                        ?><img src="thumb.php?src=<?php echo __CNTUNITICONPATH__.$phaseicon; ?>&w=100&h=106&q=100" /><?php } ?></div> 
	                            <input type="hidden" name="hiduploadfile" id="hiduploadfile" value="<?php echo $phaseicon;?>" />
                        </div>
                    </div>
                 
                    <!--start of new filter-->
        
                    <div class='row rowspacer'>
                        <div class='twelve columns'>
                        To create a new tag, type a name and press Enter.
                        <div class="tag_well">
                            <input type="text" name="test3" value="" id="form_tags_newunit" />
                        </div>
                        </div>
                    </div>
            
                    <!--end of new filter-->
                                    
                    <div class='row rowspacer' id="unitbtn">
                       <div class='row'>
                            <div class='four columns btn primary push_two noYes'>
                                <a onclick="<?php echo $btnclick;?>" tabindex="4"><?php echo $btncancel;?></a>
                            </div>
                            <div id="savebtnunits" class='four columns btn secondary yesNo'>
                                <a onclick="fn_createphase(<?php echo $phaseid;?>)"><?php echo $btnvalue;?></a>
                            </div>
                        </div>
                   	</div>
				</form>
        	</div>              
        </div> 
	</div>
</section>
<?php
	@include("footer.php");
