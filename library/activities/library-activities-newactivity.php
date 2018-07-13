<?php
	@include("sessioncheck.php");
	
	$activityid = isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
	$activityname ='';
	$points ='';
	$activitydescription ='';
	$filenames='';
	$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
	                              WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='2' 
								  AND b.fld_access='1' AND a.fld_created_by='".$uid."' AND a.fld_delstatus='0' AND b.fld_item_id='".$activityid."'");
	
	$filetypename = "Select File Type";
	
	if($activityid==0){
		$createbtn = "Create Activity";
		$cancelbtn = "Cancel";		
		$unitid =0;		
		$unitname = "Select Unit";
		$msg = "New Activity";
		$cancelclick="fn_cancel('library-activities')";
		$activityid=0;
	}
	else{
		$cancelclick="fn_cancel('library-activities-actions')";
		$createbtn = "Update this Activity";
		$cancelbtn = "Cancel";
		   
		   $qry="SELECT a.fld_unit_id AS unitid,a.fld_activity_name AS activityname,a.fld_activity_description AS 
                          activitydescription,a.fld_activity_points AS points,b.fld_unit_name AS unitname,
						  GROUP_CONCAT(c.fld_file_name) AS filenames,GROUP_CONCAT(c.fld_file_type) AS filetypes,GROUP_CONCAT(c.fld_id) AS fiids
                  FROM itc_activity_master AS a 
				  LEFT JOIN itc_unit_master AS b ON a.fld_unit_id=b.fld_id
				  LEFT JOIN itc_activity_file_mapping AS c ON a.fld_id=c.fld_activity_id
				  WHERE a.fld_id='".$activityid."' AND a.fld_delstatus='0' AND c.fld_activity_id='".$activityid."' AND c.fld_delstatus='0' ";

		$qry_lessondetails = $ObjDB->QueryObject($qry);
		$res_lessondetails = $qry_lessondetails->fetch_assoc();
		extract($res_lessondetails);
		$msg = "Edit Activity";		
		
		$filenames=array_values(array_filter(explode(',',$filenames)));	
		$filetypes=array_values(array_filter(explode(',',$filetypes)));	
	    $filid=array_values(array_filter(explode(',',$fiids)));	
		
	}	
	
?>

<section data-type='2home' id='library-activities-newactivity'> 

  <script type="text/javascript" charset="utf-8">		
		$(function(){				
			var t4 = new $.TextboxList('#form_tags_activity', 
			{
				unique: true, plugins: {autocomplete: {}},
				bitsOptions:{editable:{addKeys: [188]}}	});
				<?php 
				if($qrytag->num_rows > 0) {
					while($restag = $qrytag->fetch_assoc()){
						extract($restag);
						?>
						t4.add('<?php echo $ObjDB->EscapeStrAll($tagname); ?>','<?php echo $tagid; ?>');				
						<?php 	
					}
				}
				?>				
				t4.getContainer().addClass('textboxlist-loading');				
				$.ajax({url: 'autocomplete.php', data: 'oper=new', dataType: 'json', success: function(r){
					t4.plugins['autocomplete'].setValues(r);
					t4.getContainer().removeClass('textboxlist-loading');					
				}
			});						
		});
		
		
		function fn_loadeditor(){
			tinyMCE.init({
				script_url : "tiny_mce/tiny_mce.js",
				editor_css : "../css/tinymcecustomcss.css",
				plugins : "asciimath,asciisvg",
				theme : "advanced",
				verify_html : false,
				relative_urls : false,
				remove_script_host : false,
				convert_urls : true,
				mode : "exact",
				elements : "description",
				theme_advanced_toolbar_location :"hide",
				theme_advanced_toolbar_align : "left",
				theme_advanced_buttons1 :"bold,italic,underline,strikethrough,bullist,numlist,separator,"
				+ "justifyleft,justifycenter,justifyright,justifyfull,link,unlink,spellchecker,forecolor,pdw_toggle",
				theme_advanced_resizing : true,
				theme_advanced_buttons2 :"formatselect,fontselect,fontsizeselect,anchor,image,separator,undo,redo,cleanup,code,sub,cut ,copy,paste,forecolorpicker,backcolorpicker"+" sup,charmap,outdent,indent,hr",
				AScgiloc : '<?php echo __TINYPATH__;?>php/svgimg.php', //change me
				ASdloc : '<?php echo __TINYPATH__;?>plugins/asciisvg/js/d.svg' //change me	
			});
		}
		setTimeout("fn_loadeditor()",2000);
		$('.textarea').css('border','none');
		$('.textarea').css('box-shadow','none');
		
    </script>
    
  <div class='container'>
    <div class='row'>
      <div class='twelve columns'>
        <p class="darkTitle"><?php echo $msg; ?></p>
        <p class="darkSubTitle">&nbsp;</p>
      </div>
    </div>
    <div class='row rowspacer'>
      <div class='twelve columns formBase'>
        <div class='row'>
          <div class='eleven columns centered insideForm'>
            <form method='post' id="activityform" name="activityform">
              <div class='row'>
                <div class='six columns'> Activity Name<span class="fldreq">*</span>
                  <dl class='field row'>
                    <dt class='text'>
                      <input placeholder='Activity Name' required='' type='text' id="activityname" name="activityname" onBlur="$(this).valid();" value="<?php echo $activityname ;?>">
                    </dt>
                  </dl>
                  <?php if($sessmasterprfid==2){?>
                  Unit
                  <dl class='field row'>
                    <dt class='dropdown' id="unit">
                      <div class="selectbox">
                        <input type="hidden" name="unitid" id="unitid" value="<?php echo $unitid;?>"/>
                        <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#"> <span class="selectbox-option input-medium" data-option="<?php  if($unitid!='') {echo $unitid;} else {echo '0'; }?>" id="clearunit"><?php if($unitname!='') { echo $unitname; } else { echo "no units"; } ?></span><b class="caret1"></b> </a>
                        <?php if($activityid==0){?>
                        <div class="selectbox-options">
                          <input type="text" class="selectbox-filter" placeholder="Search Unit">
                          <ul role="options">
                            <?php 
											if($sessmasterprfid == 2 || $sessmasterprfid == 3)
											{
												$categoryqry = $ObjDB->QueryObject("SELECT fld_id AS unitid, fld_unit_name AS unitname FROM itc_unit_master 
												                                   WHERE fld_delstatus= '0' AND fld_id 
																				   NOT IN(SELECT fld_unit_id FROM itc_activity_master 
																				   WHERE fld_created_by='".$uid."' AND fld_delstatus='0') ORDER BY fld_unit_name");
											}
											else
											{
												$categoryqry = $ObjDB->QueryObject("SELECT a.fld_unit_id AS unitid, c.fld_unit_name AS unitname 
												                                   FROM itc_license_cul_mapping AS a 
																				   LEFT JOIN itc_license_track AS b ON a.fld_license_id = b.fld_license_id 
																				   RIGHT JOIN itc_unit_master AS c ON a.fld_unit_id=c.fld_id 
																				   WHERE b.fld_district_id='".$districtid."' AND b.fld_school_id='".$schoolid."' 
																				   AND b.fld_user_id='".$indid."' AND b.fld_delstatus='0' AND '".date("Y-m-d")."'
																				   BETWEEN b.fld_start_date AND b.fld_end_date AND a.fld_active='1' 
																				   AND c.fld_delstatus='0' AND c.fld_id 
																				   NOT IN(SELECT fld_unit_id FROM itc_activity_master WHERE fld_created_by='".$uid."') 
																				   GROUP BY a.fld_unit_id");
											}
											if($categoryqry->num_rows > 0)
											{
												while($rowcategory = $categoryqry->fetch_assoc()){
													extract($rowcategory);?>
                            <li><a tabindex="-1" href="#" data-option="<?php echo $unitid;?>"><?php echo $unitname; ?></a></li>
                            <?php }
											}?>
                          </ul>
                        </div>
                        <?php }?>
                      </div>
                    </dt>
                  </dl>
                  <?php }?>
                  Points<span class="fldreq">*</span>
                  <dl class='field row'>
                    <dt class='text'>
                      <input placeholder='Points' required='' onkeyup="ChkValidChar();" type='text' id="Points" name="Points" value="<?php echo $points; ?>" onBlur="$(this).valid();">
                      <input type="hidden" name="unitid" id="unitid" value="<?php echo $unitid;?>" onchange="$(this).valid();">
                    </dt>
                  </dl>
                  <dl class='field row rowspacer'>
                  </dl>
                 
                  <dl class='field row'>
				  	<p>You can upload files in any of the following formats: .jpeg, .png, .doc, .pdf, .xls</p><br>
                    <input id="file_upload" name="file_upload" type="file" multiple>
                    <script type="text/javascript">
							function generateuniqd()
							{
								var unqid = "<?php echo uniqid() ?>";
								return unqid;
							}
								Array.prototype.clear = function()
								{
									this.length = 0;
								};  
								
							<?php $timestamp = time();?>
							$(function() {
								var i=0;
								$('#file_upload').uploadify({
									'formData'     : {
										'timestamp' : '<?php echo $timestamp;?>',
										'token'     : '<?php echo md5('nanonino' . $timestamp);?>',
										'oper'      : 'activity' 
									},
									 'fileSizeLimit' : '50MB',
									 'swf'      : 'uploadify/uploadify.swf',
									'uploader' : '<?php echo _CONTENTURL_;?>uploadify.php',
									 'onSWFReady' : function() {
                                        },
									'multi':true,
									'buttonText' : 'Upload',
									'removeCompleted' : true,
									'fileTypeExts' : '*.gif; *.jpg; *.png; *.jpeg; *.xls; *.xlsx; *.docx; *.doc; *.pdf; *.txt; *.ppt; *.pptx; *.aac; *.ac3; *.mp3; *.wav; *.wma;*.swf; *.avi; *.flv; *.mp4;*.mpeg;',
									'onUploadError' : function(file, errorCode, errorMsg, errorString) {
           						          alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
       								},
									'onFallback' : function() {
									alert('Flash was not detected or flash version is not supgoported.');
									window.location="http://www.adobe.com/go/getflashplayer";
								   },
								  'onUploadSuccess' : function(file, data, response) {
										    unqid=i;
										   filetype=file.type
										   filetype=filetype.replace('.','').toUpperCase();
									  	   filesizes=file.size;
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
																url: 'library/activities/library-activities-newactivity-ajax.php',
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
												else if(parseInt(totsize) > '262144000') // check to greter then 250 mb
												{
													var dataparam = "oper=filedelete"+"&filename="+data;
													$.Zebra_Dialog('Unfortunately this file cannot be uploaded.</br>You have reached your limit 250 MB storage capacity',
													{
														'type': 'confirmation',
														'buttons': [
														{caption: 'Ok', callback: function() {
															$.ajax({
															type: 'post',
															url: 'library/activities/library-activities-newactivity-ajax.php',
															data: dataparam,
															beforeSend: function(){
															},
															success: function (data) {	
																$('#activityfilename').html('');
															}
														});
														}}
														]
													});

												}
									  			
											<?php
											} ?>
											/******File upload size checking created by chandru end line******/	
									  
											//file size
											var afsize = $('#multiactivityfilesize').val();
											if(afsize == '')
											{
												var first = $('#multiactivityfilesize').val(filesize);
											}
											else
											{
												var first = $('#multiactivityfilesize').val();
												var multi = first+","+filesize;
												var filesize = $('#multiactivityfilesize').val(multi);
											}

										   filename=data
										   filenames.push(filename);
										   filetypes.push(filetype);
									  	   $('#activityfilename').val(filenames);
										   $('#activityfiletype').val(filetypes);
										   $('#activityfilesize').val(filesize);
										   if($('#appendcontenttable').is('visible')==false)
				 							{
				   							 $('#appendcontenttable').show(1000); 
				 							} 
									
										  $('#appendcontenttable').append('<tr id="trrow_'+unqid+'" ><td>'+filename+'</td><td  class="centerText" >'+filetype+'</td><td  class="centerText"><a onClick="viewtheactivity(\''+filetype.toLowerCase()+'\',\''+filename+'\');" class="icon-synergy-view activity-view-deleteicon"></a>&nbsp;&nbsp;<a onClick="deleteactivityfile(1,0,\''+unqid+'\')" class="icon-synergy-trash activity-view-deleteicon" ></a></td></tr>');
										  $('#cratebtn').removeClass('dim'); 
										  i++;
										},
									   'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
                                          $('#cratebtn').addClass('dim');
                                       },
									   'onCancel' : function(file) {
         							    $('#cratebtn').removeClass('dim'); 
        								},
										'onSelectError' : function() {
									   alert("Invalid file format");
									    $('#cratebtn').removeClass('dim'); 
								    }
									
								});
							});
							<?php  if(sizeof($filenames)!=0 and $filenames!='' ){?> $('#appendcontenttable').show(1000); <?php } ?>
					    	</script>
                            
                    <div id="queue"></div>
                  </dl>
                </div>
                <div class='six columns'> <!-- Textarea - Lesson Description --> 
                  Description<span class="fldreq">*</span>
                  <dl class='field row'>
                    <dt class='text'>
                      <textarea placeholder='Tell us about your new activity' id="description" name="description" ><?php echo $activitydescription; ?></textarea>
                    </dt>
                  </dl>
                </div>
              </div>
              <div class='row rowspacer'>
                <div class='twelve columns'>
                  <table id="appendcontenttable" class='table table-hover table-striped table-bordered' style="display:none" >
                    <thead class='tableHeadText'>
                      <tr>
                        <th>File name</th>
                        <th class='centerText'>Type</th>
                        <th class='centerText'> Action </th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php if($activityid!=0 and sizeof($filenames)!=0){ 
					for($ff=0;$ff<sizeof($filenames);$ff++) {?>
                    
					 <tr id="trrow_<?php echo $filid[$ff]."_".$activityid;?>" >
                     <td><?php echo $filenames[$ff];?></td>
                     <td  class="centerText" ><?php echo $filetypes[$ff]; ?></td>
                     <td  class="centerText" ><a onClick="viewtheactivity('<?php echo strtolower($filetypes[$ff]); ?>','<?php echo $filenames[$ff]; ?>');" class="activity-view-deleteicon icon-synergy-view"></a>&nbsp;&nbsp;<a onClick="deleteactivityfile(2,<?php echo $filid[$ff]; ?>,'<?php echo $filid[$ff]."_".$activityid;?>')" class="activity-view-deleteicon icon-synergy-trash" ></a></td>
                     </tr>
					 <?php }
					}?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='row rowspacer'>
                <div class='twelve columns'> To create a new tag, type a name and press Enter.
                  <div class="tag_well">
                    <input type="text" name="form_tags_activity" value="" id="form_tags_activity"  />
                  </div>
                </div>
              </div>
              <div class='row rowspacer'>
                <div class='four columns btn primary push_two noYes'> <a onclick="<?php echo $cancelclick;?>" tabindex="4">Cancel</a> </div>
                <div id="cratebtn" class='four columns btn secondary yesNo'> <a onclick="fn_createactivity(<?php echo $activityid; ?>)" tabindex="3"><?php echo $createbtn;?></a> </div>
              </div>
              <input type="hidden" id="activityfilename" name="activityfilename" />
              <input type="hidden" id="activityfiletype" name="activityfiletype" />
              <input type="hidden" name="activityfilesize" id="activityfilesize" value="" />
			  <input type="hidden" name="multiactivityfilesize" id="multiactivityfilesize" value="" />
             
            </form>
            <script type="text/javascript" language="javascript">
					$("#Points").keypress(function (e) {
						if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
							return false;
						}
					});
					
					String.prototype.startsWith = function (str) {
						return (this.indexOf(str) === 0);
					}
					function ChkValidChar() {
						var txtbx = document.getElementById("Points").value;
						if ((txtbx.startsWith("0")) || (txtbx > 100)) // true
						{
							document.getElementById("Points").value = "";
							
						}
					}

					/***addd category validate****/
					$(function(){
						$("#activityform").validate({
							ignore: "",
							errorElement: "dd",
							errorPlacement: function(error, element) {
								$(element).parents('dl').addClass('error');
								error.appendTo($(element).parents('dl'));
								error.addClass('msg');
							},
							rules: {
								activityname: { required: true, remote:{ url: "library/activities/library-activities-newactivity-ajax.php",
								                                         data: {  
																		         uid: function() { 
																				      return '<?php echo $activityid;?>';},
																				 oper: function() {
																					   return 'checkactivityname';}
																				}, 
																		type:"post", 
																	    async:false } 
																	 },
								Points: { required: true }
								<?php if($sessmasterprfid==2){?> , unitid: { required: true }<?php }?>
							},
						
							messages: {
								activityname: {   required: "Please Type Activity Name",remote:"Activity Name Already Exist" },
								Points:{  required: "Please Type Points" }
								<?php if($sessmasterprfid==2){?> , unitid: {  required: "Please Select Unit" }<?php }?>	
								
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
