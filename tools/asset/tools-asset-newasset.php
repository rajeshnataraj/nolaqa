<?php
	@include("sessioncheck.php");
	
	$assetid = isset($method['id']) ? $method['id'] : $assetid;
	$assetname='';
	$filetype='';
	$filetypename='';
	$filename='';
	$filesize='';
	$timestamp = time();
	$fileuploadkey=md5('nanonino' . $timestamp);
	$filetypearray=array('xlsx','xls','txt','ppt','pptx','aac','ac3','frg','flp','m4b','aa3','doc','docx');
	$viewaccess=0;
	if($assetid==0){
		$createbtn = "Create Asset";
		$cancelbtn = "Cancel";
		$filetypename="Select File Type";
		$cancelclick="fn_cancel('tools-asset-asset')";
		$msg="Add Asset";
		$assetid=0;
	}
	else{
		$cancelclick="fn_cancel('tools-asset-asset')";
		$createbtn = "Update this asset";
		$cancelbtn = "Cancel";
		$msg="Edit Asset";
		$qryasset = $ObjDB->QueryObject("SELECT fld_id AS assetid, fld_asset_name AS assetname,fld_file_name AS filename,fld_file_size AS filesize, fn_shortname(fld_file_name,2) AS shorfilename
		                                FROM itc_asset_master WHERE fld_id = '".$assetid."' and fld_delstatus='0' GROUP BY fld_id");
		$res_asset = $qryasset->fetch_assoc();
		extract($res_asset);
		
		/**check file extension***/
		$path=__FULLCNTASSETPATH__.$filename;
		$fileext = pathinfo($path, PATHINFO_EXTENSION);
		if(in_array($fileext,$filetypearray)) $viewaccess=1; // if viewaccess is zero  preview button not enabled
	}	
?>
<script type='text/javascript'>
	$.getScript("tools/asset/tools-asset-newasset.js");
	
	$('#assetUploader').uploadify({
		'formData'     : {
			'timestamp' : '<?php echo $timestamp;?>',
			'token'     : '<?php echo $fileuploadkey;?>',
			'oper'      : 'asset' 
		},
		 'height': 40,
		 'width':420,
		'fileSizeLimit' : '100MB',
		'swf'      : 'uploadify/uploadify.swf',
		'uploader' : '<?php echo _CONTENTURL_; ?>uploadify.php',
		'multi':false,
		'buttonText' : 'Upload File',
		'removeCompleted' : true,
		'fileTypeExts' : '*.gif; *.jpg; *.png; *.jpeg; *.xls; *.xlsx; *.docx; *.doc; *.pdf; *.txt; *.ppt; *.pptx; *.aac; *.ac3; *.mp3; *.wav; *.wma;*.swf; *.avi; *.flv; *.mp4;*.mpeg;',
		'fileDesc'  : "Allowed Files only",
		'onFallback' : function() {
		alert('Flash was not detected or flash version is not supgoported.');
		window.location="http://www.adobe.com/go/getflashplayer";
		 },
		'onUploadSuccess' : function(file, data, response) {
			$('#btntools-asset-preview').removeClass('dim');
			$('#btntools-asset-download').removeClass('dim');
			
			filetype=file.type;
			filetype=filetype.replace('.','');
			/******File upload size checking created by chandru start line******/
			'<?php $totsize=$ObjDB->SelectSingleValueInt("SELECT fld_bytes FROM itc_user_usedspace_details where fld_user_id='".$uid."'"); ?>'
			var filesize=file.size;
			var totsize = '<?php echo $totsize; ?>';
			var maxsize = 262144000; // 250 MB
			var allowsize = parseInt(maxsize)-parseInt(totsize);
			if (parseInt(allowsize) >= 1073741824) 
			{
    			var bytes = (parseInt(allowsize) / 1073741824).toFixed(2) + ' GB';
			} 
			else if (parseInt(allowsize) >= 1048576) 
			{
   				var bytes = (parseInt(allowsize) / 1048576).toFixed(2) + ' MB';
			} 
			else if (parseInt(allowsize) >= 1024) 
			{
    			var bytes = (parseInt(allowsize) / 1024).toFixed(2); // KB
				bytes = (parseInt(bytes) / 1024).toFixed(2) + ' MB'; 
			} 
			else if (parseInt(allowsize) > 1) 
			{
    			var bytes = parseInt(allowsize) + ' bytes';
			} 
			else if (parseInt(allowsize) == 1) 
			{
    			var bytes = parseInt(allowsize) + ' byte';
			} 
			else 
			{
    			var bytes = '0 byte';
			}
			<?php
			if($sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid==9) 
		   	{
			?>
				if((parseInt(totsize) > '256901120') && (parseInt(totsize) < '262144000')) // check to greter then 245 MB and less then 250 MB   
				{
					if(parseInt(filesize) > parseInt(allowsize))
					{
						var dataparam = "oper=filedelete"+"&filename="+data;
						$.Zebra_Dialog('You have only '+bytes+' space limit.</br>Please try to upload a file with size '+bytes+' or less',
						{
							'type': 'confirmation',
							'buttons': [
							{caption: 'Ok', callback: function() {
								$.ajax({
								type: 'post',
								url: 'tools/asset/tools-asset-newasset-ajax.php',
								data: dataparam,
								beforeSend: function(){
								},
								success: function (data) {	
									$('#uploadfilename').hide();
								}
							});
							}}
							]
						});
					}
				}
				else if(parseInt(totsize) > '262144000') // check to greter then 10 mb
				{
					var dataparam = "oper=filedelete"+"&filename="+data;
					$.Zebra_Dialog('Unfortunately this file cannot be uploaded.</br>You have reached your limit 250 MB storage capacity',
					{
						'type': 'confirmation',
						'buttons': [
						{caption: 'Ok', callback: function() {
							$.ajax({
							type: 'post',
							url: 'tools/asset/tools-asset-newasset-ajax.php',
							data: dataparam,
							beforeSend: function(){
							},
							success: function (data) {	
								$('#uploadfilename').html('');
							}
						});
						}}
						]
					});
				}
				else
				{
					$('#uploadfilename').html(newname);
				}
				
			<?php
			} ?>
			/******File upload size checking created by chandru end line******/
			
			var downloadarray=['xlsx','xls','txt','ppt','pptx','aac','ac3','frg','flp','m4b','aa3','doc','docx'];
			typeaccess=$.inArray(filetype, downloadarray);
			if(typeaccess!=-1)
			{
			   $('#btntools-asset-preview').addClass('dim');
			}
			else
			{
			   $('#btntools-asset-preview').removeClass('dim');
			}
			var newname = data;
			if (newname.length > 300) {
	            newname = newname.substr(0, 27);
	            newname = newname+"...";
	        }
			$('#uploadfilename').html(newname);
			$('#assetfilename').val(data);
			$('#assetfileformat').val(filetype);
			$('#assetfilesize').val(filesize);
			
			$('#createassetbtn').removeClass('dim');   
	
		 },
		 'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
		   $('#createassetbtn').addClass('dim');   
		}
		
	});
	<?php 
	if($assetid!=0)
	{  ?>
		$('#btntools-asset-download').removeClass('dim');
			
	<?php if($viewaccess!=1)
	{ ?>
		$('#btntools-asset-preview').removeClass('dim');
	<?php }
	}
	?>				
</script>

<section data-type='2home' id='tools-asset-newasset'>
  <div class='container'>
    <div class='row'>
        <div class="span10">
            <p class="dialogTitle"><?php echo $msg; ?></p>
            <p class="dialogSubTitleLight">&nbsp;</p>
        </div>
    </div>
    <div class='row'>
        <div class='twelve columns formBase'>
            <div class='row'>
                <div class='eleven columns centered insideForm'>
                <form method='post' id="assetform" name="assetform">
                	<div class='row'>
		              <div class='six columns'>	
                      Asset Name<span class="fldreq">*</span> 
                      	<dl class='field row'>
                          <dt class='text'>
                            <input placeholder='Asset Name' required='' type='text' id="assetname" name="assetname" onBlur="$(this).valid();" value="<?php echo $assetname ;?>">
                          </dt>
                        </dl>
                        </div>
                        <div class='six columns'>	
                            Uploaded file name: 
                            <div id="uploadfilename"><?php if($assetid!=0){ echo $shorfilename; }else{ echo "No Files"; }?></div>
                      </div>
                   	</div>	
                   <div class='row  rowspacer'>
                   <div class='twelve columns'>
                    	<div class='six columns'>
                         <input id="assetUploader" name="assetUploader" type="file" multiple>
                                <div id="queue"></div>
								<span>You can upload files in any of the following formats: .jpeg, .png, .doc, .pdf, .xls</span>
                        </div>
                         <div class='six columns'>
                            <input type="button" id="btntools-asset-preview" class="module-extend-button dim" value="Preview" style="margin-right:10px;"  onClick="viewtheactivity();" />                          
                            <input type="button" id="btntools-asset-download" class="module-extend-button dim" value="Download" onclick="fn_downloaddoc();" />
                        	</div>   
                        </div>    
                    </div>
                    
                    
                    <div class='row rowspacer'>
                        <div class='four columns btn primary push_two noYes'>
                            <a onclick="<?php echo $cancelclick;?>" tabindex="4">Cancel</a>
                        </div>
                        <div class='four columns btn secondary yesNo'>
                            <a id="createassetbtn" onclick="fn_createasset(<?php echo $assetid; ?>)" tabindex="3"><?php echo $createbtn;?></a>
                        </div>
                    </div>
                    
                    <input type="hidden" id="assetfilename" name="assetfilename" value="<?php echo $filename;?>" />
                    <input type="hidden" id="assetfileformat" name="assetfileformat" value="<?php echo $fileext;?>" />
					<input type="hidden" id="assetfilesize" name="assetfilesize" value="<?php echo $filesize;?>" />
                    
                    
                </form>
                <script type="text/javascript" language="javascript">
					
					/***addd category validate****/
					$(function(){
						$("#assetform").validate({
							ignore: "",
							errorElement: "dd",
							errorPlacement: function(error, element) {
								$(element).parents('dl').addClass('error');
								error.appendTo($(element).parents('dl'));
								error.addClass('msg');
							},
							rules: {
								assetname: { required: true },
								formatid: { required: true }
							},
						
							messages: {
								assetname: {   required: "please type asset name"},
								formatid: { required: "please select asset type" }
								
							} ,
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
            </div>
        </div>
    </div>
  </div>
</section>
<?php
	@include("footer.php");