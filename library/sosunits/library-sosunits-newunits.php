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
	
	$unitsid = isset($method['id']) ? $method['id'] : 0;	
	
	if($unitsid != '' and $unitsid!='undefined'){
		$pageTitle = "Edit Unit";
		$btnclick = "fn_cancel('library-units-actions')";
		$btnvalue = "Update Unit";
		$btncancel = "Cancel";
	}
	else{
		$pageTitle = "New Unit";
		$btnclick = "fn_cancel('library-units')";
		$btnvalue = "Create Unit";
		$btncancel = "Cancel";
		$unitsid=0;
	}
	
	/* The following query used to get the subject id,name and course id,name and unit id,name and unit icon from tables */
	
	$qry_unitsdetails = $ObjDB->QueryObject("SELECT fld_id AS unitid, fld_unit_name AS unitname, fld_unit_icon AS uniticon 
	                                        FROM itc_sosunit_master WHERE fld_id='".$unitsid."' AND fld_delstatus='0'");
	
	if($qry_unitsdetails->num_rows>0)
	{
		$unitsdetails = $qry_unitsdetails->fetch_assoc();		
		extract($unitsdetails);	
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
			if($unitsid != '' and $unitsid!='undefined'){
				$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname 
				                              FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
											  WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='4' 
											        AND b.fld_access='1' AND a.fld_created_by='".$uid."' 
													AND a.fld_delstatus='0' AND b.fld_item_id='".$unitsid."'");
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

<section data-type='2home' id='library-sosunits-newunits'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle"><?php echo $pageTitle;?></p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>    
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
				<form name="unitforms" id="unitforms"> 
                     <!-- Unit name text box start -->            
                    <div class='row rowspacer'>
                        <div class='six columns'> 
                         	Unit Name<span class="fldreq">*</span> 
                            <dl class='field row'>
                                <dt class='text'>
                                	<input placeholder='New Unit Name' type='text' id="unitname" name="unitname" value="<?php echo $unitname; ?>" onBlur="$(this).valid();">
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
                            $("#unitforms").validate({
                                ignore: "",
                                errorElement: "dd",
                                errorPlacement: function(error, element) {
                                $(element).parents('dl').addClass('error');
                                error.appendTo($(element).parents('dl'));
                                error.addClass('msg');
                                },

                                rules: {                                    
                                    unitname: { required: true, 
									remote:{ 
												url: "library/sosunits/library-sosunits-ajax.php", 
												type:"POST",  
												data: {  
														uid: function() {
														return '<?php echo $unitsid;?>';},
														oper: function() {
														return 'checkunitname';}
														  
												 },
												 async:false 
										   }},
									
                                },
                            
                                messages: {                                   
                                    unitname: { required: "please type the unit name", remote: "unit name already exists" },
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
                            <?php if($uniticon==''){?><div class="iconPreview"></div><?php } ?>
								<?php if($uniticon!=''){?><img src="thumb.php?src=<?php echo __CNTUNITICONPATH__.$uniticon; ?>&w=100&h=106&q=100" /><?php } ?></div> 
	                            <input type="hidden" name="hiduploadfile" id="hiduploadfile" value="<?php echo $uniticon;?>" />
                        </div>
                    </div>
                    <script type="text/javascript" language="javascript">
                            $(function() {
                                    $('#soslic1').slimscroll({
                                            width: '410px',
                                            height:'366px',
                                            size: '3px',
                                            railVisible: true,
                                            allowPageScroll: false,
                                            railColor: '#F4F4F4',
                                            opacity: 1,
                                            color: '#d9d9d9',

                                    });
                                    $('#soslic2').slimscroll({
                                            width: '410px',
                                            height:'366px',
                                            size: '3px',
                                            railVisible: true,
                                            allowPageScroll: false,
                                            railColor: '#F4F4F4',
                                            opacity: 1,
                                            color: '#d9d9d9',
                                    });
                                    $("#list9").sortable({
                                            connectWith: ".droptrue1",
                                            dropOnEmpty: true,
                                            items: "div[class='draglinkleft']",
                                            receive: function(event, ui) { 
                                                    $("div[class=draglinkright]").each(function(){ 
                                                            if($(this).parent().attr('id')=='list9'){
                                                                    fn_movealllistitems('list9','list10',$(this).children(":first").attr('id'));
                                                            }
                                                    });											
                                            }
                                    });

                                    $( "#list10" ).sortable({
                                            connectWith: ".droptrue1",
                                            dropOnEmpty: true,
                                            receive: function(event, ui) { 
                                                    $("div[class=draglinkleft]").each(function(){ 
                                                            if($(this).parent().attr('id')=='list10'){
                                                                    fn_movealllistitems('list9','list10',$(this).children(":first").attr('id'));
                                                            }
                                                    });								
                                            }
                                    });
                                   
                            });																	
                    </script> 
                    
                    <div class="row rowspacer" id="studentlist">
                        <div class='six columns'>
                            <div class="dragndropcol">
                            <?php

                                $qrystudent= $ObjDB->QueryObject("SELECT a.fld_id,a.fld_license_name AS licensename, 
                                                                a.fld_license_name AS shortname 
                                                                FROM itc_license_master As a
                                                                WHERE a.fld_id NOT IN(SELECT fld_license_id FROM itc_license_sosunit_mapping WHERE fld_unit_id='".$unitsid."' AND fld_access='1')
                                                                AND a.fld_delstatus='0' AND a.fld_license_type='1' AND a.fld_content_type='2' 
                                                                ORDER BY licensename ASC");
                                                                ?>
                                <div class="dragtitle">License available</div>
                                <div class="draglinkleftSearch" id="s_list9" >
                                   <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Search' type='text' id="list_9_search" name="list_9_search" onKeyUp="search_list(this,'#list9');" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="dragWell" id="soslic1" >
                                    <div id="list9" class="dragleftinner droptrue1">
                                     <?php 		
                                       if($qrystudent->num_rows > 0){													
                                            while($rowsstudent = $qrystudent->fetch_assoc()){
                                                extract($rowsstudent);
                                                ?>
                                            <div class="draglinkleft" id="list9_<?php echo $fld_id; ?>" >
                                                <div class="dragItemLable tooltip" id="<?php echo $fld_id; ?>" title="<?php echo $licensename;?>"><?php echo $shortname; ?></div>
                                                <div class="clickable" id="clck_<?php echo $fld_id; ?>" onclick="fn_movealllistitems('list9','list10',<?php echo $fld_id; ?>);"></div>
                                            </div> 
                                    <?php 
                                            }
                                        }
                                    ?>
                                    </div>
                                </div>
                                <div class="dragAllLink"  onclick="fn_movealllistitems('list9','list10',0,0);" style="cursor: pointer;cursor:hand;width: 110px;float: right;">add all licenses</div>
                            </div>
                        </div>
                            <div class='six columns'>
                                    <div class="dragndropcol">
                                        <div class="dragtitle">License with this Lesson </div>
                                        <div class="draglinkleftSearch" id="s_list10" >
                                           <dl class='field row'>
                                                <dt class='text'>
                                                    <input placeholder='Search' type='text' id="list_10_search" name="list_10_search" onKeyUp="search_list(this,'#list10');" />
                                                </dt>
                                            </dl>
                                        </div>
                                         <div class="dragWell" id="soslic2">
                                            <div id="list10" class="dragleftinner droptrue1">
                                             <?php                                          
                                              $qrystudent= $ObjDB->QueryObject("SELECT a.fld_id,a.fld_license_name AS licensename, 
                                                                                            a.fld_license_name AS shortname 
                                                                                            FROM itc_license_master As a
                                                                                            WHERE a.fld_id IN(SELECT fld_license_id FROM itc_license_sosunit_mapping WHERE fld_unit_id='".$unitsid."' AND fld_access='1')
                                                                                            AND a.fld_delstatus='0' AND a.fld_license_type='1' AND a.fld_content_type='2'
                                                                                            ORDER BY licensename ASC");

                                                if($qrystudent->num_rows > 0){													
                                                    while($rowsstudent = $qrystudent->fetch_assoc()){
                                                        extract($rowsstudent);
                                                    ?>
                                                            <div class="draglinkright" id="list10_<?php echo $fld_id; ?>">
                                                                <div class="dragItemLable tooltip" id="<?php echo $fld_id; ?>" title="<?php echo $licensename;?>"><?php echo $shortname; ?></div>
                                                                <div class="clickable" id="clck_<?php echo $fld_id; ?>" onclick="fn_movealllistitems('list9','list10',<?php echo $fld_id; ?>);"></div>
                                                            </div>
                                            <?php 	}
                                                }

                                            ?>
                                            </div>
                                        </div>
                                        <div class="dragAllLink" onclick="fn_movealllistitems('list10','list9',0,0);"  style="cursor: pointer;cursor:hand;width: 140px;float: right;">remove all licenses</div>
                                    </div>
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
                                <a onclick="fn_createunits(<?php echo $unitsid;?>)"><?php echo $btnvalue;?></a>
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
