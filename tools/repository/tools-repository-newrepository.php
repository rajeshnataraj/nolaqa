<?php
	@include("sessioncheck.php");
	
	$repositoryid = isset($method['id']) ? $method['id'] : $repositoryid;
	$repositoryname='';
	$filetype='';
	$filetypename='';
	$filename='';
	$timestamp = time();
	$fileuploadkey=md5('nanonino' . $timestamp);
	$filetypearray=array('xlsx','xls','doc','docx','pdf');
	$viewaccess=1;
	if($repositoryid==0){
		$createbtn = "Create Repository";
		$cancelbtn = "Cancel";
		$filetypename="Select File Type";
		$cancelclick="fn_cancel('tools-repository-repository')";
		$msg="Add Repository";
		$repositoryid=0;
	}
	else{
		$cancelclick="fn_cancel('tools-repository-actions')";
		$createbtn = "Update this Repository";
		$cancelbtn = "Cancel";
		$msg="Edit Repository";
		$qryrepository = $ObjDB->QueryObject("SELECT fld_id AS repositoryid, fld_repository_name AS repositoryname,fld_file_name AS filename, fn_shortname(fld_file_name,2) AS shorfilename
		                                FROM itc_repository_master WHERE fld_id = '".$repositoryid."' and fld_delstatus='0' GROUP BY fld_id");
		$res_repository = $qryrepository->fetch_assoc();
		extract($res_repository);
		
		/**check file extension***/
		$path=__FULLCNTASSETPATH__.$filename;
		$fileext = pathinfo($path, PATHINFO_EXTENSION);
		if(in_array($fileext,$filetypearray)) $viewaccess=0; // if viewaccess is zero  preview button not enabled
                if($fileext=='docx' || $fileext=='doc' || $fileext=='xls' || $fileext=='xlsx')
                {
                     $viewaccess=1;
                }
	}	
?>
<script type='text/javascript'>
	$.getScript("tools/repository/tools-repository-newrepository.js");
	
	$('#repositoryUploader').uploadify({
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
		'fileTypeExts' : '*.xls; *.xlsx; *.docx; *.doc; *.pdf;',
		'fileDesc'  : "Allowed Files only",
		'onFallback' : function() {
                    alert('Flash was not detected or flash version is not supgoported.');
                    window.location="http://www.adobe.com/go/getflashplayer";
		 },
		'onUploadSuccess' : function(file, data, response) {
			$('#btntools-repository-preview').removeClass('dim');
			$('#btntools-repository-download').removeClass('dim');
			
			filetype=file.type;
			filetype=filetype.replace('.','');
			var downloadarray=['xlsx','xls','txt','doc','docx'];
			typeaccess=$.inArray(filetype, downloadarray);
			if(typeaccess!=-1)
			{
			   $('#btntools-repository-preview').addClass('dim');
			}
			else
			{
			   $('#btntools-repository-preview').removeClass('dim');
			}
			var newname = data;
			if (newname.length > 30) {
	            newname = newname.substr(0, 27);
	            newname = newname+"...";
	        }
			
			$('#uploadfilename').html(newname);
			$('#repositoryfilename').val(data);
			$('#repositoryfileformat').val(filetype);
			
			$('#createrepositorybtn').removeClass('dim');   
	
		 },
		 'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
		   $('#createrepositorybtn').addClass('dim');   
		}
		
	});
	<?php 
	if($repositoryid!=0)
	{  ?>
		$('#btntools-repository-download').removeClass('dim');
			
	<?php if($viewaccess!=1)
	{ ?>
		$('#btntools-repository-preview').removeClass('dim');
	<?php }
	}
	?>				
</script>

<section data-type='2home' id='tools-repository-newrepository'>
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
                <form method='post' id="repositoryform" name="repositoryform">
                	<div class='row'>
		              <div class='six columns'>	
                   Repository Name<span class="fldreq">*</span> 
                      	<dl class='field row'>
                          <dt class='text'>
                            <input placeholder='Repository Name' required='' type='text' id="repositoryname" name="repositoryname" onBlur="$(this).valid();" value="<?php echo $repositoryname ;?>">
                          </dt>
                        </dl>
                        </div>
                        <div class='six columns'>	
                            Uploaded file name: 
                            <div id="uploadfilename"><?php if($repositoryid!=0){ echo $shorfilename; }else{ echo "No Files"; }?></div>
                      </div>
                   	</div>	
                   <div class='row  rowspacer'>
                   <div class='twelve columns'>
                    	<div class='six columns'>
                            <p class='lableRight'>Upload your repository using one of the following formats: .pdf, .xls, .doc. </p>
                            <dl class='field row'>
                         <input id="repositoryUploader" name="repositoryUploader" type="file" multiple>
                                <div id="queue"></div>
                            </dl>
                        </div>
                         <div class='six columns' style="margin-top:30px;">
                             <p>  </p>
                            <input type="button" id="btntools-repository-preview" class="module-extend-button dim" value="Preview" style="margin-right:10px;"  onClick="viewtheactivity();" />                          
                            <input type="button" id="btntools-repository-download" class="module-extend-button dim" value="Download" onclick="fn_downloaddoc();" />
                        	</div>   
                        </div>    
                    </div>
                    
                    
                    <div class='row rowspacer'>
                        <div class='four columns btn primary push_two noYes'>
                            <a onclick="<?php echo $cancelclick;?>" tabindex="4">Cancel</a>
                        </div>
                        <div class='btn secondary yesNo'>
                            <a id="createrepositorybtn" onclick="fn_createrepository(<?php echo $repositoryid; ?>)" tabindex="3"><?php echo $createbtn;?></a>
                        </div>
                    </div>
                    
                    <input type="hidden" id="repositoryfilename" name="repositoryfilename" value="<?php echo $filename;?>" />
                    <input type="hidden" id="repositoryfileformat" name="repositoryfileformat" value="<?php echo $fileext;?>" />
                    
                    
                </form>
                <script type="text/javascript" language="javascript">
					
					/***addd category validate****/
					$(function(){
						$("#repositoryform").validate({
							ignore: "",
							errorElement: "dd",
							errorPlacement: function(error, element) {
								$(element).parents('dl').addClass('error');
								error.appendTo($(element).parents('dl'));
								error.addClass('msg');
							},
							rules: {
								repositoryname: { required: true },
								formatid: { required: true }
							},
						
							messages: {
								repositoryname: {   required: "please type repository name"},
								formatid: { required: "please select repository type" }
								
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