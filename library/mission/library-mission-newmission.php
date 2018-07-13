<?php
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '0';
$id = explode(",",$id);
$assetid ='';
$filetype='';
$missionid = $id[0];

$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid, a.fld_tag_name AS tagname 
                              FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
							  WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='3' 
							  AND b.fld_access='1' AND a.fld_created_by='".$uid."' 
							  AND a.fld_delstatus='0' AND b.fld_item_id='".$missionid."'   
							  GROUP BY a.fld_id ");


if($missionid==0){
	$createbtn = "Create Mission";
	$cancelbtn = "Cancel";
	$expeditionname = "";
	$missionversion = "";
	$filename = "";
	$type = "";
	$msg = "New Mission";
	$cancelclick="fn_cancel('library-mission')";
        $expdescr ='';
         $expuiid=1;
         $expuiname="Standard UI";
}
else{
	$createbtn = "Update Mission";
	$cancelbtn = "Cancel";

	$expeditionqry = $ObjDB->QueryObject("SELECT a.fld_mis_name, a.fld_asset_id, a.fld_ui_id, b.fld_version, b.fld_file_name, b.fld_zip_type,a.fld_mis_desc
											FROM itc_mission_master AS a 
											LEFT JOIN itc_mission_version_track AS b ON a.fld_id = b.fld_mis_id 
											WHERE a.fld_id='".$missionid."' AND a.fld_delstatus='0' 
												AND b.fld_delstatus='0' GROUP BY a.fld_mis_name");

	while($rowexpedition=$expeditionqry->fetch_assoc())
	{
		extract($rowexpedition);
		$expeditionname = $fld_mis_name;
		$missionversion = $fld_version;
		$filename = $fld_file_name;
		$type = $fld_zip_type;
		$assetid = $fld_asset_id;
		$expdescr= $fld_mis_desc;
                $expuiid = $fld_ui_id;
		
		if($expuiid==1)
			$expuiname="Standard UI";
		else if($expuiid==2)
			$expuiname="Map UI";
		
		if($type==0)
			$filetype=".sbook";
		else if($type==1)
			$filetype=".zip";
			
		$fullfilename = $filename.$filetype;
	}
	if($id[1]==1)
		$msg = "View ".$expeditionname;
	else
		$msg = "Edit ".$expeditionname;
	$cancelclick="fn_cancel('library-expedition-actions')";			
}
if($id[1]!=1)
{?>
<!--Script for the Tag Well-->
<script language="javascript" type="text/javascript" charset="utf-8">
		
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_exp', 
		{
			unique: true, plugins: {autocomplete: {}},
			bitsOptions:{editable:{addKeys: [188]}}	});
		<?php 
                    if($missionid != '' and $missionid!='0'){
				$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname 
				                              FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
											  WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='38' 
											        AND b.fld_access='1' AND a.fld_created_by='".$uid."' 
													AND a.fld_delstatus='0' AND b.fld_item_id='".$missionid."'");
			if($qrytag->num_rows > 0) {
				while($restag = $qrytag->fetch_assoc()){
					extract($restag);
		?>
				t4.add('<?php echo $ObjDB->EscapeStrAll($tagname); ?>','<?php echo $tagid; ?>');				
		<?php 	}
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
				elements : "misdescription",
				theme_advanced_toolbar_location :"hide",
				theme_advanced_toolbar_align : "left",
				theme_advanced_buttons1 :"bold,italic,underline,strikethrough,bullist,numlist,separator,"
				+ "justifyleft,justifycenter,justifyright,justifyfull,link,unlink,spellchecker,forecolor,pdw_toggle",
				theme_advanced_resizing : false,
                                theme_advanced_statusbar_location : "none",
                                theme_advanced_buttons2 :"formatselect,fontselect,fontsizeselect,anchor,image,separator,undo,redo,cleanup,code,sub,cut ,copy,paste,forecolorpicker,backcolorpicker"+" sup,charmap,outdent,indent,hr",
				statusbar : false,		
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

<?php }?>
<section data-type='2home' id='library-mission-newmission'>
    <div class='container'>
    	<!--Load the Expedition Name / New expedition-->
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $msg; ?></p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <!--Load the Expedition Form-->
        <div class='row formBase'>
            <div class='eleven columns centered insideForm'>
                <form name="expeditionforms" id="expeditionforms">
                    <?php 
                    if($id[1]!=1)
                    {?>
                    <!--Expedition Name and AssetID Textboxes-->
                    <div class='row'>
                        <div class='six columns'>
                         	Select Mission Content<span class="fldreq">*</span>
                            <div class='row'>
                            	<input type="file" name="file_upload_mission" id="file_upload_mission" />
                                <div id="queue" ></div>
                                <div class="profile-preview" style="float:left;margin-left:25px;text-align:left;width:100%;"><?php echo $filename.$filetype; ?></div>
                                
                            </div>
                        </div>
                        
                        <!--Version For Expedition-->
                        <div class='three columns'>
                        	Version<span class="fldreq">*</span>
                            <div class='row'>
                                <div class="selectbox" id="expeditionselectbox">
                                    <input type="hidden" name="selectversion" class="required" id="selectversion" value="<?php echo $missionversion;?>" >
                                    <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                        <span class="selectbox-option input-medium" data-option="<?php echo $missionversion;?>" id="versions"  style="width:95%;">Version <?php echo $missionversion;?></span>
                                        <b class="caret1"></b>
                                    </a>
                                    <?php if($missionid!=0){?>
                                    <div class="selectbox-options">			    
                                        <ul role="options">
                                           <?php $qry = $ObjDB->QueryObject("SELECT fld_version FROM itc_mission_version_track WHERE fld_mis_id='".$missionid."'");
                                            while($res = $qry->fetch_object()){?>
                                               <li><a  href="#" data-option="<?php echo $res->fld_version; ?>" onclick="fn_changemissionname(<?php echo $res->fld_version;?>,<?php echo $missionid;?>)">Version <?php echo $res->fld_version; ?></a></li>
                                            <?php }?>                                                   
                                        </ul>
                                    </div>
                                    <?php }?> 
                                </div>
                                
                            </div>
                        </div>
                        
                        <!--Upload Dialog(Rule)-->
                        <div class='three columns'>
                        	<span class="fldreq"></span>
                            <div class='row'>
                                <p class='lableRight'>Upload your Expedition using one of the following formats: .zip, .sbook</p>
                            </div>
                        </div>
                    </div>
                    
                    
                    <!--Expedition Name and AssetID Textboxes-->
                    <div class='row  rowspacer'>
                        <div class='six columns'>
                        	New Mission Name<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='New Mission Name' type='text' id="txtexpname" name="txtexpname" value="<?php echo $expeditionname;?>" onBlur="$(this).valid();" />
                                </dt>
                            </dl>
                       
                    	
                        	Asset ID<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                <input placeholder='Asset ID' type='text' id="txtexpassetid" name="txtexpassetid" value="<?php echo $assetid ;?>" onBlur="$(this).valid();" onkeypress="return IsAlphaNumeric(event);" ondrop="return false;"/>
                                </dt>
                            </dl>
                      
                            Select UI<span class="fldreq">*</span>
                            <div class='field row'>
                                <div class="selectbox">
                                    <input type="hidden" name="selectui" class="required" id="selectui" value="<?php echo $expuiid;?>" >
                                    <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                        <span class="selectbox-option input-medium" data-option="<?php echo $expuiid;?>" style="width:95%;"><?php echo $expuiname;?></span>
                                        <b class="caret1"></b>
                                    </a>
                                    <div class="selectbox-options" >			    
                                        <ul role="options" >
                                        	<li><a tabindex="-1" href="#" data-option="1">Standard UI</a></li>
                                            <li><a tabindex="-1" href="#" data-option="2">Map UI</a></li>
                                        </ul>
                        </div>
                                </div>
                                
                            </div>
                      
                        </div>
                        <div class='six columns'> <!-- Textarea - Lesson Description -->
                      Description
                             <dl class='field row'>
                                <dt class='textarea'>
                                    <textarea placeholder='Tell us about your new expedition' id="misdescription" name="misdescription"  style="height:315px; width:100%; border-color:#FFF; resize:none;"
><?php echo htmlentities($expdescr); ?></textarea>
                                
                                </dt>                                
                             </dl>
                                
                            </div>
                        </div>
                    
                    <!--Style to Display the uploaded Filename-->
                    <style>
                    .module-upload-success
                    {
                        background-color: #FFFFFF;
                        color: #FFFFFF;
                        width: 380px;
                    }
                    </style>     
                    
                    <!--Script to Upload Expedition-->                
                    <script language="javascript" type="text/javascript">
                         					
                        $(document).ready(function() { 
						
						  <?php $timestamp = time();?>
						  $('#file_upload_mission').uploadify({
								'swf'  : 'uploadify/uploadify.swf',
								'uploader'    : '<?php echo _CONTENTURL_;?>uploadify.php',
								'formData'     : {
										'timestamp' : '<?php echo $timestamp;?>',
										'token'     : '<?php echo md5('nanonino' . $timestamp);?>',
										'oper'      : 'mission' 
									},
								'method'   : 'post',
								'fileTypeExts'  : '*.zip;*.sbook',
								'fileTypeDesc'  : 'Mission Conent',
								'buttonText' : 'Select Mission Content',
								'preventCaching' : false,
								'auto'      : true,
								'height'      : 33,
								'width' : 417,
								'queueSizeLimit' : 1,
								'removeCompleted' : true,
								'queueID'        : 'queue',
								'onFallback' : function() {
									alert('Flash was not detected or flash version is not supgoported.');
									window.location="http://www.adobe.com/go/getflashplayer";
								},
								 'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
                                                                $('#createmissionbtn').addClass('dim');   
                                    },
								'onUploadSuccess'  : function(file, data, response) {
									 console.log(data);
									 
									 $('#createmissionbtn').removeClass('dim');
									 var upres = data.split("~");
									 if(upres[0] == 'success')
									 {
										$('#selectversion').val(upres[2]);
										$('#versions').html("Version "+upres[2]);
                                                                                $('#txtexpname').val(upres[3]);

                                                                                tinyMCE.get('misdescription').setContent(upres[4]);

										$('.profile-preview').html('');
										$('.profile-preview').html(upres[1]);
										$('#hiduploadfile').val(upres[1]);
                                                                                $('#hidflag').val(1);
									 }
									
									if(upres[0] != 'success') {
										  
										 if(upres[0] == "invalid") {
										 	msg="Please upload a valid Mission Content File";		 
										 }
										 else {
											msg="Upload failed. Please try again";	 
										 }
										 
										 $.Zebra_Dialog(msg,
										  {  			'buttons': [
															         {  caption: 'ok', 
																	    callback: function() {} 
																	 }
																   ]
										  });
										
										 $('.profile-preview').html("");
									 }		
									 
									
								}
						  });
						});
                    </script>
                    
                 <script type="text/javascript" language="javascript">
								<?php if($missionid!=0){ ?>								
								<?php }?>
								$(function() {
									$('#testrailvisible0').slimscroll({
										width: '410px',
										height:'366px',
										size: '7px',
                                                                                alwaysVisible: true,
                                                                                wheelstep: 1,
										railVisible: true,
										allowPageScroll: false,
										railColor: '#F4F4F4',
										opacity: 1,
										color: '#d9d9d9',
										
									});
									$('#testrailvisible1').slimscroll({
										width: '410px',
										height:'366px',
										size: '7px',
                                                                                alwaysVisible: true,
                                                                                wheelstep: 1,
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
                                                                                        WHERE a.fld_id NOT IN(SELECT fld_license_id FROM itc_license_mission_mapping WHERE fld_mis_id='".$missionid."'
                                                                                         AND fld_delstatus='0')
                                                                                        AND a.fld_delstatus='0' AND a.fld_license_type='1' 
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
                                        <div class="dragWell" id="testrailvisible0" >
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
                                    <?php }?> 
                                </div>
                                        <div class="dragAllLink"  onclick="fn_movealllistitems('list9','list10',0,0);">add all licenses</div>
                            </div>
                        </div>
                            <div class='six columns'>
                                    <div class="dragndropcol">
                                        <div class="dragtitle">License with this Expedition </div>
                                        <div class="draglinkleftSearch" id="s_list10" >
                                           <dl class='field row'>
                                                <dt class='text'>
                                                    <input placeholder='Search' type='text' id="list_10_search" name="list_10_search" onKeyUp="search_list(this,'#list10');" />
                                                </dt>
                                            </dl>
                                        </div>
                                         <div class="dragWell" id="testrailvisible1">
                                            <div id="list10" class="dragleftinner droptrue1">
                                             <?php 
                        
                                             //echo $missionid;
                                              $qrystudent= $ObjDB->QueryObject("SELECT a.fld_id,a.fld_license_name AS licensename, 
                                                                                            a.fld_license_name AS shortname 
                                                                                            FROM itc_license_master As a
                                                                                            WHERE a.fld_id IN(SELECT fld_license_id FROM itc_license_mission_mapping WHERE fld_mis_id='".$missionid."'
                                                                                            AND fld_delstatus='0')
                                                                                            AND a.fld_delstatus='0' AND a.fld_license_type='1' 
                                                                                            ORDER BY licensename ASC");
						
                                                if($qrystudent->num_rows > 0){													
                                                    while($rowsstudent = $qrystudent->fetch_assoc()){
                                                        extract($rowsstudent);
                                                         $getlicenseholderqry = $ObjDB->SelectSingleValueInt("SELECT SUM(a.cnt) AS coun 
														FROM( SELECT COUNT(DISTINCT(fld_district_id)) AS cnt 
																FROM itc_license_track 
																WHERE fld_license_id='".$fld_id."' AND fld_school_id=0 AND fld_user_id=0 
																AND fld_delstatus='0' AND fld_district_id IN(SELECT fld_id FROM itc_district_master 
																WHERE fld_delstatus='0') 
														UNION ALL SELECT COUNT(DISTINCT(fld_school_id)) AS cnt FROM itc_license_track WHERE 
																fld_license_id='".$fld_id."' AND fld_user_id=0 AND fld_delstatus='0' AND fld_school_id
																IN(SELECT fld_id FROM itc_school_master WHERE fld_delstatus='0') 
														UNION ALL SELECT COUNT(DISTINCT(fld_user_id)) AS cnt FROM itc_license_track 
																WHERE fld_license_id='".$fld_id."' AND fld_school_id=0 AND fld_delstatus='0' 
																AND fld_user_id IN(SELECT fld_id FROM itc_user_master WHERE fld_delstatus='0')) AS a");
                                                    ?>
                                                            <div class="draglinkright <?php if($getlicenseholderqry!=0) echo " dim";?>" id="list10_<?php echo $fld_id; ?>">
                                                                <div class="dragItemLable tooltip" id="<?php echo $fld_id; ?>" title="<?php echo $licensename;?>"><?php echo $shortname; ?></div>
                                                                <div class="clickable" id="clck_<?php echo $fld_id; ?>" onclick="fn_movealllistitems('list9','list10',<?php echo $fld_id; ?>);"></div>
                            </div>
                                            <?php 	}
                                                }
                                             
                                            ?>
                        </div>
                    </div>
                                        <div class="dragAllLink" onclick="fn_movealllistitems('list10','list9',0,0);">remove all licenses</div>
                                    </div>
                                </div>
                            </div>  
                    
                    <!--Create New Tag-->
                    <div class='field row rowspacer' style="margin-top:40px;">
                        To create a new tag, type a name and press Enter.
                        <div class="tag_well">
                            <input type="text" name="form_tags_exp" value="" id="form_tags_exp" />
                        </div>	
                    </div>
                    <?php 
                    if($id[1]!=1)
                    {?>
                    <!--Cancel and Create Buttons-->
                    <div class='row'>
                        <div class='four columns btn primary push_two noYes'>
                            <a onclick="<?php echo $cancelclick;?>"><?php echo $cancelbtn;?></a>
                        </div>
                        <div id="createmodulebtn" class='four columns btn secondary yesNo'>
                            <a onclick="fn_createmission(<?php echo $missionid;?>)"><?php echo $createbtn;?></a>
                        </div>
                    </div>
                    <?php }?>
                </form>
                <input type="hidden" id="hiduploadfile" name="hiduploadfile" value="<?php echo $filename.$filetype;?>" />
                
                <!--Script to Validate the Moduleform & Numbers for Textbox-->
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
               
    //Function to validate the form
					$("#expeditionforms").validate({
						ignore: "",
						errorElement: "dd",
						errorPlacement: function(error, element) {
							$(element).parents('dl').addClass('error');
							error.appendTo($(element).parents('dl'));	
							error.addClass('msg');
						},
						rules: {
							txtexpname: { required: true },
							txtexpassetid:{ required: true }
						}, 
						messages: { 
							txtexpname: { required: "Please type Expedition Name" }, 
							txtexpassetid: { required: "Please type Asset ID" }
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
					
                </script>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");