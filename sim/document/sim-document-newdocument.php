<?php
/*------
	Page - New Message
	Description:
		Creating new message
	History:	
------*/
	@include("sessioncheck.php");
	
	$id= isset($method['id']) ? $method['id'] : '0';
	$id = explode(',',$id);

	$docid = $id[0];
	$catid = $id[1];
	$proid = $id[2];
	$listicon = $id[3];
	if($listicon == '')
	{
		$listicon = $id[4];
	}
	
	if($docid==0)
	{
		$documentname = '';
		$uploadname = '';
	}
	else 
	{
		$docname = $ObjDB->QueryObject("SELECT fld_id as docid,fld_document_name as documentname,fld_upload_filename as uploadname,fld_global_status as globaldoc FROM itc_sim_document WHERE fld_id='".$docid."' AND  fld_delstatus='0' ");
		$row = $docname->fetch_assoc();
		extract($row);
	}
	
	/* file upload codeing start line*/
	$repositoryname='';
	$filetype='';
	$filetypename='';
	$filename='';
	$timestamp = time();
	$fileuploadkey=md5('nanonino' . $timestamp);
	$filetypearray=array('jpg','jpeg','png','xlsx','xls','doc','docx','pdf','ppt','pptx','odp','stl');
	/**check file extension***/
	$path=__FULLCNTASSETPATH__.$filename;
	$fileext = pathinfo($path, PATHINFO_EXTENSION);
	/* file upload codeing end line */
?>
<!-- Autocomplete script start -->

<script type="text/javascript" charset="utf-8">	
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_adddocument', 
		{
			unique: true, plugins: {autocomplete: {}},
			bitsOptions:{editable:{addKeys: [188]}}	});
		<?php 
			if($docid != ''){
				$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname 
				                              FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
											  WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='43' 
											        AND b.fld_access='1' AND a.fld_created_by='".$uid."' 
													AND a.fld_delstatus='0' AND b.fld_item_id='".$docid."'");
				if($qrytag->num_rows > 0) {
					
					while($restag = $qrytag->fetch_assoc()){
						
						extract($restag); ?>
            			t4.add('<?php echo $ObjDB->EscapeStrAll($tagname); ?>','<?php echo $tagid; ?>');				
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
<script type='text/javascript'>
	$.getScript('sim/document/sim-document-document.js');
        
	/* file upload code start line */
	$('#pimUploader').uploadify({
		'formData'     : {
			'timestamp' : '<?php echo $timestamp;?>',
			'token'     : '<?php echo $fileuploadkey;?>',
			'oper'      : 'sim' 
		},
		 'height': 40,
		 'width':200,
		'fileSizeLimit' : '30MB', 
		'swf'      : 'uploadify/uploadify.swf',
		'uploader' : '<?php echo _CONTENTURL_; ?>uploadify.php',
		'multi':false,
		'buttonText' : 'Attach',
		'removeCompleted' : true,
		'fileTypeExts' : '*.jpg; *.jpeg; *.png; *.xls; *.xlsx; *.docx; *.doc; *.pdf; *.ppt; *.pptx; *.odp; *.stl;',
		'fileDesc'  : "Allowed Files only",
		'onFallback' : function() {
                    alert('Flash was not detected or flash version is not supgoported.');
                    window.location="http://www.adobe.com/go/getflashplayer";
		 },
		'onUploadSuccess' : function(file, data, response) {
			
			filetype=file.type;
			filetype=filetype.replace('.','');
			
			var newname = data;
			if (newname.length > 30) {
	            newname = newname.substr(0, 50);
	            newname = newname+"...";
	        }
			
			$('#uploadfilename').html(newname);
			$('#pimfilename').val(data);
			$('#pimfileformat').val(filetype);
			
			$('#adddocument').removeClass('dim');   
	
		 },
		 'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
		   $('#adddocument').addClass('dim');   
		}
		
	});
	/* file upload code start line */
</script>
<section data-type='#sim-items-addnewitem' id='sim-items-addnewitem'>
    <div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="dialogTitle"><?php if($docid == 0){ echo "New Document";} else { echo "Edit ".$documentname;} ?></p>
                <p class="dialogSubTitleLight"></p>
            </div>
        </div>
		
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
                <form name="mailform" id="mailform">
					<div class="row">
                        <div class="six columns">
                           Document Name<span class="fldreq">*</span> 
                            <dl class='field row'>
                                <dt class='text'>
                                    <input id="documentname" name="documentname"  placeholder='Document Name' tabindex="1" type='text' value="<?php echo $documentname; ?>">
                                </dt>
                            </dl> 
                        </div>
						<div class="six columns">
                           <span class="fldreq"></span> 
                            <dl class='field row'>
                                <dt style="margin-top:7px;">
                                    <input id="globaldoc" name="globaldoc" type='checkbox' <?php if($globaldoc==1){?>checked disabled<?php } ?> value=""> <span> This is Global Expedition Document</span>
                                </dt>
                            </dl> 
                        </div>
                    </div>
					<div class='row'>
						<div class='twelve columns'>
							<div class='six columns'>	
								Uploaded file name: 
								<div id="uploadfilename"><?php if($docid!=0){ echo $uploadname; }else{ echo "No Files"; }?></div>
							</div>

							<div class='row  rowspacer'>
								<div class='twelve columns'>
									<div class='six columns'>
										<input id="pimUploader" name="pimUploader" type="file" multiple>
										<div id="queue"></div>
									</div>

								</div>    
							</div>

							<input type="hidden" id="pimfilename" name="pimfilename" value="<?php if($docid!=0){ echo $uploadname; } else { echo $filename; } ?>" />
							<input type="hidden" id="pimfileformat" name="pimfileformat" value="<?php echo $fileext;?>" /> 

						</div>
					</div>
					<!--start of new filter-->
        
                    <div class='row rowspacer'>
                        <div class='twelve columns'>
                        To create a new tag, type a name and press Enter.
                        <div class="tag_well">
                            <input type="text" name="test3" value="" id="form_tags_adddocument" />
                        </div>
                        </div>
                    </div>
            
                    <!--end of new filter-->
                    
                </form>
                <div class='row' style="padding-top:20px;"  >
                        
                        <p  onClick="fn_adddocument('<?php echo $docid; ?>','<?php echo $catid; ?>','<?php echo $proid; ?>','<?php echo $listicon; ?>');" id="msgsend" class='darkButton' style="float: right;height: 30px;margin-right: 5px;padding-bottom: 3px;padding-top: 10px;width: 175px;">
                            <a  id="adddocument"><?php if($docid == 0){ echo "Add Document";} else { echo "Update Document";} ?></a>
                        </p>
                        
                        <!--<p onClick="fn_cancel('<?php //echo $catid; ?>','<?php //echo $proid; ?>','<?php //echo $docid; ?>');" class='darkButton' style="float: right;height: 25px;margin-right: 5px;padding-bottom: 3px;padding-top: 4px;width: 125px;">
                            <a >cancel</a>
                        </p>-->
               </div>
            </div>
			
			<script type='text/javascript'>
				function fn_loadeditor1(){
					tinyMCE.init({
						mode: "exact",
						theme : "advanced",
						elements : "message",
						plugins : "",
						theme_advanced_buttons1 : "bold,italic,underline,fontselect,fontsizeselect,forecolor,backcolor",
						theme_advanced_buttons2 :"",
						theme_advanced_buttons3 : "",
						theme_advanced_toolbar_location : "top",
						theme_advanced_toolbar_align : "left",
						theme_advanced_resizing : false,
						theme_advanced_statusbar_location: "",
						relative_urls : false,
						remove_script_host : false,
						convert_urls : false
					});
				}
				setTimeout("fn_loadeditor1()",500);
			</script>
        </div>
    </div>
</section>
<?php
	@include("footer.php");
