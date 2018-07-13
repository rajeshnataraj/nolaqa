<?php
/*------
	Page - New Message
	Description:
		Creating new message
	History:	
------*/
	@include("sessioncheck.php");
	
	$newitemids= isset($method['id']) ? $method['id'] : '';
	$newitemid = explode(',',$newitemids);

	$catid = $newitemid[0];
	$desname = $newitemid[1];
	$proid = $newitemid[2];
	$itemid = $newitemid[3];
	$desitemid = $newitemid[4];
	
	if($desitemid==0)
	{
		$itemname = '';
		$message = '';
		$uploadname = '';
	}
	else 
	{
		$desitemname = $ObjDB->QueryObject("SELECT fld_id as ditemid,fld_item_name as itemname,fld_message_details as message,fld_upload_filename as uploadname FROM itc_sim_desitem WHERE fld_id='".$desitemid."' AND  fld_delstatus='0' ");
		$row = $desitemname->fetch_assoc();
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
		var t4 = new $.TextboxList('#form_tags_addnewitems', 
		{
			unique: true, plugins: {autocomplete: {}},
			bitsOptions:{editable:{addKeys: [188]}}	});
		<?php 
			if($desitemid != '' and $definefieldid!='undefined'){
				$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname 
				                              FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
											  WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='43' 
											        AND b.fld_access='1' AND a.fld_created_by='".$uid."' 
													AND a.fld_delstatus='0' AND b.fld_item_id='".$desitemid."'");
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
	$.getScript("sim/items/sim-items-items.js");
        
	/* file upload code start line */
	$('#pimUploader').uploadify({
		'formData'     : {
			'timestamp' : '<?php echo $timestamp;?>',
			'token'     : '<?php echo $fileuploadkey;?>',
			'oper'      : 'pim' 
		},
		 'height': 40,
		 'width':200,
		'fileSizeLimit' : '15MB',
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
			
			$('#additem').removeClass('dim');   
	
		 },
		 'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
		   $('#additem').addClass('dim');   
		}
		
	});
	/* file upload code start line */
</script>
<section data-type='#sim-items-addnewitem' id='sim-items-addnewitem'>
    <div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="dialogTitle">New Items</p>
                <p class="dialogSubTitleLight"></p>
            </div>
        </div>
		
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
                <form name="mailform" id="mailform">
					<div class="row">
                        <div class="six columns">
                            Name<span class="fldreq">*</span> 
                            <dl class='field row'>
                                <dt class='text'>
                                    <input id="itemname" name="itemname"  placeholder='name' tabindex="1" type='text' value="<?php echo $itemname; ?>">
                                </dt>
                            </dl> 
                        </div>
                    </div>
					<div class='row'>
						<div class='twelve columns'>
							Message<span class="fldreq">*</span>
							<dl class='field row' >
								<dt>
									<div contenteditable="true" id="message" name="message" class="messagesBody" style="height:350px; overflow-y:auto; word-wrap: break-word;"><?php echo $message; ?></div>

								</dt>
							</dl> 
							<div class='six columns'>	
								Uploaded file name: 
								<div id="uploadfilename"><?php if($desitemid!=0){ echo $uploadname; }else{ echo "No Files"; }?></div>
							</div>

							<div class='row  rowspacer'>
								<div class='twelve columns'>
									<div class='six columns'>
										<input id="pimUploader" name="pimUploader" type="file" multiple>
										<div id="queue"></div>
									</div>

								</div>    
							</div>

							<input type="hidden" id="pimfilename" name="pimfilename" value="<?php if($desitemid!=0){ echo $uploadname; } else { echo $filename; } ?>" />
							<input type="hidden" id="pimfileformat" name="pimfileformat" value="<?php echo $fileext;?>" /> 

						</div>
					</div>
					<!--start of new filter-->
        
                    <div class='row rowspacer'>
                        <div class='twelve columns'>
                        To create a new tag, type a name and press Enter.
                        <div class="tag_well">
                            <input type="text" name="test3" value="" id="form_tags_addnewitems" />
                        </div>
                        </div>
                    </div>
            
                    <!--end of new filter-->
                    
                </form>
                <div class='row' style="padding-top:20px;"  >
                        
                        <p  onClick="fn_additem('<?php echo $catid; ?>','<?php echo $desname; ?>','<?php echo $proid; ?>','<?php echo $itemid; ?>','<?php echo $ditemid; ?>');" id="msgsend" class='darkButton' style="float: right;height: 25px;margin-right: 5px;padding-bottom: 3px;padding-top: 4px;width: 125px;">
                            <a  id="additem">Add Item</a>
                        </p>
                     
               </div>
            </div>
			<script type="text/javascript" language="javascript">
				/***addd category validate****/
				$(function(){
					$("#mailform").validate({
						ignore: "",
						errorElement: "dd",
						errorPlacement: function(error, element) {
							$(element).parents('dl').addClass('error');
							error.appendTo($(element).parents('dl'));
							error.addClass('msg'); 
						},

						rules: {
							sendtype:{required: true},
							msgsubject:{required: true},
							hiddropdowntype:{required: true}
						},

						messages: {

							sendtype:{required: "please select  receiver type"},
							msgsubject:{required: "please enter subject"},
							hiddropdowntype:{required: "please select  receiver"}

						},
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
