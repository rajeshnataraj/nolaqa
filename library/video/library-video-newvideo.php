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
	$videoname='';
	$videoicon='';
	
      
	$videoid = isset($method['id']) ? $method['id'] : 0;	
	
	if($videoid != '' and $videoid!='undefined'){
		$pageTitle = "Edit Video";
		$btnclick = "fn_cancel('library-video-actions')";
		$btnvalue = "Update Phase";
		$btncancel = "Cancel";
	}
	else{
		$pageTitle = "New Video";
		$btnclick = "fn_cancel('library-video')";
		$btnvalue = "Create Video";
		$btncancel = "Cancel";
		$videoid=0;
	}
	
	/* The following query used to get the subject id,name and course id,name and unit id,name and unit icon from tables */
	
	$qry_videodetails = $ObjDB->QueryObject("SELECT fld_unit_id as videounitid, fld_phase_id AS videophaseid,fld_id as videoid, 
                                                fld_video_name AS videoname, fld_video_icon AS videoicon,fld_video_descr as videodescription,fld_version as versionid,fld_videofile_name as videofilename
                                                FROM itc_sosvideo_master WHERE fld_id='".$videoid."' AND fld_delstatus='0'");
	
	if($qry_videodetails->num_rows > 0)
	{
		$videodetails = $qry_videodetails->fetch_assoc();		
		extract($videodetails);	
	}
?>

<!-- Autocomplete script start -->

<script type="text/javascript" charset="utf-8">	
    
    $.getScript("library/video/library-video.js");
    
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_input_3', 
		{
			unique: true, plugins: {autocomplete: {}},
			bitsOptions:{editable:{addKeys: [188]}}	});
		<?php 
			if($phaseid != 0){
				$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='1' AND b.fld_access='1' AND a.fld_created_by='".$uid."' AND a.fld_delstatus='0' AND b.fld_item_id='".$phaseid."'");
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
				elements : "videodescription",
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


<section data-type='2home' id='library-video-newvideo'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle"><?php echo $pageTitle; ?></p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
            	<form id="videoform" name="videoform">
                	<div class='row rowspacer'>
                        
                        <div class="six columns">
                            Video Name<span class="fldreq">*</span> 
                            <dl class='field row'>
                                <dt class='text'>
                                	<input placeholder='New Video Name' type='text' id="videoname" name="videoname" value="<?php echo $videoname; ?>" onBlur="$(this).valid();">
                                </dt>
                            </dl>
                             Unit<span class="fldreq">*</span>
                            <dl class='field row' id='unid'>  
                                <dt class='dropdown'>  
                                    <div id="unit"> <!-- Unit -->   
                                        <div class="selectbox">
                                            <input type="hidden" name="videounitid" id="videounitid" value="<?php echo $videounitid;?>"  onchange="$(this).valid();"  />
                                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option="<?php echo $videounitid; ?>" id="clearunit">
                                                    <?php
                                                if($videounitid!=''){
                                                $videounitname = $ObjDB->SelectSingleValue("SELECT fld_unit_name FROM itc_sosunit_master 
		                                      WHERE fld_id='".$videounitid."' AND fld_delstatus='0'"); 
                                                
                                                echo $videounitname; 
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
                                                    $unitqry = $ObjDB->QueryObject("SELECT fld_id AS videounitid, fld_unit_name AS unitname FROM itc_sosunit_master WHERE fld_delstatus= '0' ORDER BY fld_unit_name ");
                                                    if($unitqry->num_rows > 0)
                                                    {
                                                        while($rowunit = $unitqry->fetch_assoc())
                                                        {
															extract($rowunit);
                                                        ?>
                                                            <li><a tabindex="-1" href="#" data-option="<?php echo $videounitid;?>"  onclick="fn_showphase(<?php echo $videounitid;?>)"><?php echo $unitname; ?></a></li>
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
                                            <input type="hidden" name="videophaseid" id="videophaseid" value="<?php echo $videophaseid;?>"  onchange="$(this).valid();"  />
                                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                            	<span class="selectbox-option input-medium" data-option="<?php echo $videophaseid;?>" id="clearunit">
                                                    <?php
                                                if($videophaseid!=''){
                                                $phaseunitname = $ObjDB->SelectSingleValue("SELECT fld_phase_name FROM itc_sosphase_master 
		                                      WHERE fld_id='".$videophaseid."' AND fld_delstatus='0'"); 
                                                
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
                                                    $phaseqry = $ObjDB->QueryObject("SELECT fld_id AS videophaseid, fld_phase_name AS phasename FROM itc_sosphase_master WHERE fld_delstatus= '0' ORDER BY fld_phase_name ");
                                                    if($phaseqry->num_rows > 0)
                                                    {
                                                        while($rowphase = $phaseqry->fetch_assoc())
                                                        {
                                                            extract($rowphase);
                                                            
                                                        ?>
                                                            <li><a tabindex="-1" href="#" data-option="<?php echo $videophaseid;?>"><?php echo $phasename; ?></a></li>
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
                                    <textarea placeholder='Tell us about your new video' id="videodescription"  name="videodescription" style="height:170px; width:100%; border-color:#FFF; resize:none;"><?php echo htmlentities($videodescription); ?></textarea>
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
                                    if($videofilename != '') {
                                        echo "<br /><br />".$videofilename; 
                                    ?>
                                	<input type="button" id="lessons-preview" value="Preview" onClick="fn_viewtheactivity('<?php echo $videofilename; ?>',<?php echo $videoid; ?>);" name="lessons-preview" align="right" />
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
                        	<p class='lableRight'>Upload your video in Mp4 format and assign a version number.</p>	
                        </div>
                    </div>
                    
                    <div class='row rowspacer' style="margin-top:50px;"> 
                    	<div class='five columns'> 
                        	<input id="file_upload" name="file_upload" type="file" multiple/>	
                            <div id="queueicon"></div>
                        </div>
                        <div class='three columns'> 
                           <div   id="uniticon" >
                            <?php if($videoicon==''){?><div class="iconPreview"></div><?php } ?>
                                    <?php 
                                    if($videoicon!=''){
                                        ?><img src="thumb.php?src=<?php echo __CNTUNITICONPATH__.$videoicon; ?>&w=100&h=106&q=100" /><?php } ?></div> 
	                            <input type="hidden" name="hiduploadfile" id="hiduploadfile" value="<?php echo $videoicon;?>" />
                        </div>
                        <div class='four columns'> <!-- IPL Icon 3rd Column -->
                        	<p class='lableRight'>Upload an image to use as this lesson's icon. (jpg, png, bmp)</p>	
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
                                <a onclick="fn_createvideo(<?php echo $videoid;?>);"><?php echo $btnvalue;?></a>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>  
          <input type="hidden" id="webhid" name="webhid" value="<?php echo $videofilename; ?>" />
        

        <script type="text/javascript" language="javascript">
			/*-------form validation-------*/
		$(function(){						
                /*--------web ipl----------*/
                $('#sosUploader').uploadify({
                        'formData'     : {
                                'timestamp' : '<?php echo $timestamp; ?>',
                                'token'     : '<?php echo md5('nanonino' . $timestamp);?>',
                                'oper' : 'sosvideo'
                        },
                        'swf'      : 'uploadify/uploadify.swf',
                        'uploader' : '<?php echo _CONTENTURL_; ?>uploadify.php',
                        'buttonClass' : 'btn',
                        'buttonText' : 'Select an Video',
                        'fileTypeExts' : '*.mp4; *.zip;',
                        'queueID'  : 'queue',
                        'queueSizeLimit' : 1,
                        'width' : 300,
                        'onUploadSuccess' : function(file, data, response) {
                           
                                if(data == '' || data == undefined || data == 'invalid'){
                                        alert("Please upload a valid file");
                                        return false;
                                }else{
                                        var fileformat = data.split('.').pop();

                                       if(fileformat=='zip'){
                                           alert(fileformat);
                                       $('#webhid').val(data);
                                       $('#queue').html('<br /><br />'+data+' <input type="button" id="lessons-preview" value="Preview" onClick="fn_viewtheactivity(\''+data+'\',<?php echo $videoid; ?>);" name="lessons-preview" align="right" />'); 
                                         $('#queue').html('<br /><br />'+data); 
                                        }
                                        else{
                                            $('#webhid').val(data);
                                       $('#queue').html('<br /><br />'+data+' <input type="button" id="lessons-preview" value="Preview" onClick="fn_viewtheactivity(\''+data+'\',<?php echo $videoid; ?>);" name="lessons-preview" align="right" />'); 

                                        }
                                }
                                if(<?php echo $videoid;?> != 0){
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
				$("#videoform").validate({
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
						videoname: { required: true },
						remote:{ 
												url: "library/video/library-video-ajax.php", 
												type:"POST",  
												data: {  
														uid: function() {
														return '<?php echo $videoid;?>';},
														oper: function() {
														return 'checkvideoname';}
														  
												 },
												 async:false 
										   }
					}, 
					messages: { 
							   						  
						unitid: {  required: "Please Select Unit" },
                                                phaseid: { required: "Please Select Phase" },
						videoname: { required: "Please Type video Name"/*, remote: "Lesson Name Already Exists"*/	},
						
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