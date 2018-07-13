<?php
/*------
	Page - library-lessons-newlessons
	Description:
		Form to add a new lesson details or edit an existing lesson details
		
	Actions Performed:	
		Create and Edit
	
	History:	
		
------*/
	
	@include("sessioncheck.php");
	
	 /****declaration part****/
	$docname='';
	$docicon='';
	
      
	$docid = isset($method['id']) ? $method['id'] : 0;	
	
	if($docid != '' and $docid!='undefined'){
		$pageTitle = "Edit Document";
		$btnclick = "fn_cancel('library-documents-actions')";
		$btnvalue = "Update Phase";
		$btncancel = "Cancel";
	}
	else{
		$pageTitle = "New Document";
		$btnclick = "fn_cancel('library-documents')";
		$btnvalue = "Create Document";
		$btncancel = "Cancel";
		$docid=0;
	}
	
	/* The following query used to get the subject id,name and course id,name and unit id,name and unit icon from tables */
	
	$qry_documentdetails = $ObjDB->QueryObject("SELECT fld_unit_id as docunitid, fld_phase_id AS docphaseid,fld_id as docid, 
                                                fld_document_name AS docname, fld_document_icon AS docicon,fld_document_descr as docdescription,fld_version as versionid,fld_docfile_name as docfilename
                                                FROM itc_sosdocument_master WHERE fld_id='".$docid."' AND fld_delstatus='0'");
	
	if($qry_documentdetails->num_rows > 0)
	{
		$documentdetails = $qry_documentdetails->fetch_assoc();		
		extract($documentdetails);	
	}
?>

<!-- Autocomplete script start -->

<script type="text/javascript" charset="utf-8">	
    
    $.getScript("library/documents/library-documents.js");
    
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_input_3', 
		{
			unique: true, plugins: {autocomplete: {}},
			bitsOptions:{editable:{addKeys: [188]}}	});
		<?php 
			if($docid != 0){
				$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='40' AND b.fld_access='1' AND a.fld_created_by='".$uid."' AND a.fld_delstatus='0' AND b.fld_item_id='".$docid."'");
				if($qrytag->num_rows > 0) {
					while($restag = $qrytag->fetch_assoc()){
						extract($restag);
		?>
					t4.add('<?php echo $ObjDB->EscapeStrAll($tagname); ?>','<?php echo $tagid; ?>');				
		<?php 		}
				}
			}
		?>				
		t4.getContainer().addClass('textboxlist-loading');				
		$.ajax({url: 'autocomplete.php', data: 'oper=new', dataType: 'json', success: function(r){
			t4.plugins['autocomplete'].setValues(r);
			t4.getContainer().removeClass('textboxlist-loading');					
		}});						
	});
</script>
<script type="text/javascript">
                
		
		function fn_loadeditor(){
			tinyMCE.init({
				script_url : "tiny_mce/tiny_mce.js",
				plugins : "asciimath,asciisvg",
				theme : "advanced",
				verify_html : false,
				relative_urls : false,
				remove_script_host : false,
				convert_urls : true,
				mode : "exact",
				elements : "docdescription",
				theme_advanced_toolbar_location :"hide",
				theme_advanced_toolbar_align : "left",
				theme_advanced_buttons1 :"bold,italic,underline,strikethrough,bullist,numlist,separator,"
				+ "justifyleft,justifycenter,justifyright,justifyfull,link,unlink,spellchecker,forecolor,pdw_toggle",
				 theme_advanced_resizing : false,
                                 theme_advanced_statusbar_location : "none",

				theme_advanced_buttons2 :"formatselect,fontselect,fontsizeselect,anchor,image,separator,undo,redo,cleanup,code,sub,cut ,copy,paste,forecolorpicker,backcolorpicker"+" sup,charmap,outdent,indent,hr",
						
				AScgiloc : '<?php echo __TINYPATH__;?>php/svgimg.php', //change me
				ASdloc : '<?php echo __TINYPATH__;?>plugins/asciisvg/js/d.svg', //change me	
                                
                                init_instance_callback: function(){
                                    ac = tinyMCE.activeEditor;
                                    ac.dom.setStyle(ac.getBody(), 'fontSize', '14px');
                                }

			});
		}
		
		setTimeout("fn_loadeditor()",2000);
		$('.textarea').css('border','none');
		$('.textarea').css('box-shadow','none');
	</script>

<section data-type='2home' id='library-documents-newdocuments'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle"><?php echo $pageTitle; ?></p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
            	<form id="documentform" name="documentform">
                	<div class='row rowspacer'>
                        
                        <div class="six columns">
                            Document Name<span class="fldreq">*</span> 
                            <dl class='field row'>
                                <dt class='text'>
                                	<input placeholder='New Document Name' type='text' id="documentname" name="documentname" value="<?php echo $docname; ?>" onBlur="$(this).valid();">
                                </dt>
                            </dl>
                             Unit<span class="fldreq">*</span>
                            <dl class='field row' id='unid'>  
                                <dt class='dropdown'>  
                                    <div id="unit"> <!-- Unit -->   
                                        <div class="selectbox">
                                            <input type="hidden" name="docunitid" id="docunitid" value="<?php echo $docunitid;?>"  onchange="$(this).valid();"  />
                                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option="<?php echo $docunitid; ?>" id="clearunit">
                                                    <?php
                                                if($docunitid!=''){
                                                $docunitname = $ObjDB->SelectSingleValue("SELECT fld_unit_name FROM itc_sosunit_master 
		                                      WHERE fld_id='".$docunitid."' AND fld_delstatus='0'"); 
                                                
                                                echo $docunitname; 
                                                }
                                                else{
                                                    echo "Select Unit";
                                                }
                                                ?>
                                                </span>
                                                <b class="caret1"></b>
                                            </a>                                           
                                            <div class="selectbox-options">
                                                <input type="text" class="selectbox-filter" placeholder="Search Unit">
                                                <ul role="options">
                                                    <?php 
                                                    $unitqry = $ObjDB->QueryObject("SELECT fld_id AS docunitid, fld_unit_name AS unitname FROM itc_sosunit_master WHERE fld_delstatus= '0' ORDER BY fld_unit_name ");
                                                    if($unitqry->num_rows > 0)
                                                    {
                                                        while($rowunit = $unitqry->fetch_assoc())
                                                        {
															extract($rowunit);
                                                        ?>
                                                            <li><a tabindex="-1" href="#" data-option="<?php echo $docunitid;?>"  onclick="fn_showphase(<?php echo $docunitid;?>)"><?php echo $unitname; ?></a></li>
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
                                <div id="phasediv"> <!-- Unit -->   
                             Phase<span class="fldreq">*</span>
                            <dl class='field row' id='phaid'>  
                                <dt class='dropdown'>  
                                 
                                        <div class="selectbox">
                                            <input type="hidden" name="docphaseid" id="docphaseid" value="<?php echo $docphaseid;?>"  onchange="$(this).valid();"  />
                                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                            	<span class="selectbox-option input-medium" data-option="<?php echo $docphaseid;?>" id="clearunit">
                                                    <?php
                                                if($docphaseid!=''){
                                                $phaseunitname = $ObjDB->SelectSingleValue("SELECT fld_phase_name FROM itc_sosphase_master 
		                                      WHERE fld_id='".$docphaseid."' AND fld_delstatus='0'"); 
                                                
                                                echo $phaseunitname; 
                                                }
                                                else{
                                                   echo "Select Phase"; 
                                                }
                                                ?>
                                                </span><b class="caret1"></b>
                                            </a>                                           
                                            <div class="selectbox-options">
                                                <input type="text" class="selectbox-filter" placeholder="Search Phase">
                                                <ul role="options">
                                                    <?php 
                                                    $phaseqry = $ObjDB->QueryObject("SELECT fld_id AS docphaseid, fld_phase_name AS phasename FROM itc_sosphase_master WHERE fld_delstatus= '0' ORDER BY fld_phase_name ");
                                                    if($phaseqry->num_rows > 0)
                                                    {
                                                        while($rowphase = $phaseqry->fetch_assoc())
                                                        {
                                                            extract($rowphase);
                                                            
                                                        ?>
                                                            <li><a tabindex="-1" href="#" data-option="<?php echo $docphaseid;?>"><?php echo $phasename; ?></a></li>
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
                        </div>
                        <div class='six columns'> 
                         	Description
                            <dl class='field row'>
                                <dt class='textarea'>
                                    <textarea placeholder='Tell us about your new document' id="docdescription"  name="docdescription" style="height:170px; width:100%; border-color:#FFF; resize:none;"><?php echo htmlentities($docdescription); ?></textarea>
                                </dt>                                
                            </dl>
                        </div>
                        
                    </div>  	
                   
                    <div class='row rowspacer'> <!-- Tag Well -->
                    	<div class='twelve columns'>
                        	To create a new tag, type a name and press Enter.
                            <div class="tag_well">
                                <input type="text" name="form_tags_input_3" value="" id="form_tags_input_3" />
                            </div>
                        </div>
                    </div>
                    
                    <div class='row rowspacer' style="margin-top:25px;"> <!-- Web IPL -->
                    	<div class='five columns'> <!-- Web IPL 1st column -->
                            <input id="sosUploader" name="sosUploader" type="file" value="" />	<!-- Web IPL Upload Button -->
                            <div id="queue">
                            	<?php 
                                    if($docfilename != '') {
                                        echo "<br /><br />".$docfilename; 
                                    ?>
                                	<input type="button" id="lessons-preview" value="Preview" onClick="fn_viewthedocument('<?php echo $docfilename; ?>',<?php echo $docid; ?>);" name="lessons-preview" align="right" />
                                <?php
                                                            }
								?>
                            </div>
                        </div>
                        
                        <div class='three columns' id="webipltextbox"> 
                        	<dl class='field row'>
                                <dt class='text'>
                                	<input placeholder='version' type='text' id="webversiontxt" name="webversiontxt" value="<?php echo $versionid; ?>" />
                                </dt>
                            </dl>
                        </div>
                        <div class='four columns'> 
                        	<p class='lableRight'>Upload your document in PDF format and assign a version number.</p>	
                        </div>
                    </div>
                    
                    <div class='row rowspacer' style="margin-top:50px;"> 
                    	<div class='five columns'> 
                        	<input id="file_upload" name="file_upload" type="file" multiple/>	
                            <div id="queueicon"></div>
                        </div>
                        <div class='three columns'> 
                           <div   id="uniticon" >
                            <?php if($docicon==''){?><div class="iconPreview"></div><?php } ?>
                                    <?php 
                                    if($docicon!=''){
                                        ?><img src="thumb.php?src=<?php echo __CNTUNITICONPATH__.$docicon; ?>&w=100&h=106&q=100" /><?php } ?></div> 
	                            <input type="hidden" name="hiduploadfile" id="hiduploadfile" value="<?php echo $docicon;?>" />
                        </div>
                        <div class='four columns'> <!-- IPL Icon 3rd Column -->
                        	<p class='lableRight'>Upload an image to use as this document icon. (jpg, png, bmp)</p>	
                        </div>
                    </div>
                    
                    <div class='row rowspacer' id="unitbtn" style="padding-top:20px;">
                        <div class='six columns'>
                            <p class='btn primary twelve columns'>
                                <a onclick="<?php echo $btnclick; ?>"><?php echo $btncancel;?></a>
                            </p>
                        </div>
                        <div class='six columns'>
                            <p class='btn secondary twelve columns'>
                                <a onclick="fn_createdocument(<?php echo $docid;?>);"><?php echo $btnvalue;?></a>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>  
          <input type="hidden" id="webhid" name="webhid" value="<?php echo $docfilename; ?>" />
        

        <script type="text/javascript" language="javascript">
			/*-------form validation-------*/
		$(function(){						
                /*--------web ipl----------*/
                $('#sosUploader').uploadify({
                        'formData'     : {
                                'timestamp' : '<?php echo $timestamp; ?>',
                                'token'     : '<?php echo md5('nanonino' . $timestamp);?>',
                                'oper' : 'sosdocuments'
                        },
                        'swf'      : 'uploadify/uploadify.swf',
                        'uploader' : '<?php echo _CONTENTURL_; ?>uploadify.php',
                        'buttonClass' : 'btn',
                        'buttonText' : 'Select a Document',
                        'fileTypeExts' : '*.pdf;',
                        'queueID'  : 'queue',
                        'queueSizeLimit' : 1,
                        'width' : 300,
                        'onUploadSuccess' : function(file, data, response) {
                           
                                if(data == '' || data == undefined || data == 'invalid'){
                                        alert("Please upload a valid file");
                                        return false;
                                }else{
                                        var fileformat = data.split('.').pop();
                                        
                                       if(fileformat==="pdf")
                                       {
                                            $('#webhid').val(data);
                                            $('#queue').html('<br /><br />'+data+' <input type="button" id="lessons-preview" value="Preview" onClick="fn_viewtheactivity(\''+data+'\',<?php echo $docid; ?>);" name="lessons-preview" align="right" />'); 
                                            $('#queue').html('<br /><br />'+data); 
                                       }
                                       
                                }
                                if(<?php echo $docid;?> != 0){
                                        $('#webipldropdown').remove();										
                                        $('#webipltextbox').show();	
                                        $('#webflag').val('1');								
                                }
                        }
                });

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
				
				/****form validate*****/
				$("#documentform").validate({
					ignore: "",
					errorElement: "dd",
					errorPlacement: function(error, element) {
						$(element).parents('dl').addClass('error');
						error.appendTo($(element).parents('dl'));	
						error.addClass('msg');
					},
					rules: {
						unitid: { required: true },
                                                phaseid: { required: true },
						documentname: { required: true },
						remote:{ 
												url: "library/documents/library-documents-ajax.php", 
												type:"POST",  
												data: {  
														uid: function() {
														return '<?php echo $docid;?>';},
														oper: function() {
														return 'checkdocumentname';}
														  
												 },
												 async:false 
										   }
					}, 
					messages: { 
							   						  
						unitid: {  required: "Please Select Unit" },
                                                phaseid: { required: "Please Select Phase" },
						documentname: { required: "Please Type document Name"/*, remote: "Lesson Name Already Exists"*/	},
						
					},
					highlight: function(element, errorClass, validClass) {
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
</section>
<?php
	@include("footer.php");