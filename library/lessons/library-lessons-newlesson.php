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
	
	$lessonid = (isset($_POST['id']) and $_POST['id'] != '') ? $_POST['id'] : 0;
	
	$createbtn = "Create Lesson";
	$cancelbtn = "Cancel";	
	$unitid = '';	
        $unitid1=0;
	$unitname = "Select Unit";
	$pageTitle = "New Lesson";
	$webversion="";
	$ipadversion="";
	$ipadremversion="";
	$cancelclick="fn_cancel('library-lessons')";
	$ipliconname = "no-image.png";
	$lessontypename = "Normal";
	$lessontype = "1";
	$lessonname = $iplpoints = $ipldays = $iplminutes = $assetid = $ipldescr ='';
	$timestamp = time();
	$weblessonname = "";
	 	
	if($lessonid != 0){
				
		$createbtn = "Update this Lesson";
		$cancelbtn = "Cancel";
		$pageTitle = "Edit Lesson";
		$cancelclick="fn_cancel('library-lessons-actions');";
		
		$qry_lessondetails = $ObjDB->QueryObject("SELECT a.fld_lesson_type as lessontype, a.fld_id , a.fld_unit_id AS unitid, a.fld_ipl_name AS lessonname, a.fld_ipl_descr AS ipldescr, a.fld_ipl_points AS iplpoints, a.fld_ipl_minutes AS iplminutes, a.fld_ipl_days AS ipldays, a.fld_ipl_icon AS ipliconname,  fn_shortname(a.fld_ipl_icon,2) AS tempipliconname, b.fld_version AS versions, b.fld_zip_type AS ziptype, b.fld_zip_name AS zipname, fn_shortname(b.fld_zip_name,2) AS zipshortname, e.fld_unit_name AS unitname, a.fld_asset_id AS assetid FROM itc_ipl_master AS a LEFT JOIN itc_ipl_version_track AS b ON a.fld_id=b.fld_ipl_id LEFT JOIN itc_unit_master AS e ON e.fld_id=a.fld_unit_id  WHERE a.fld_id='".$lessonid."' AND b.fld_delstatus='0'");
		
		if($qry_lessondetails->num_rows>0){
			while($res_lessondetails = $qry_lessondetails->fetch_assoc()){			
				extract($res_lessondetails);
				if($ziptype==1){
					$weblessonname=$zipname;
					$tempweblessonname = $zipshortname;
					$webversion = $versions;				
                                        $unitid1=$unitid;
				}
			}
			if($lessontype==1){
				$lessontypename = "Normal";
			} else{
				$lessontypename = "Orientation";
			}
		}	
	}	
?>
<script type="text/javascript" charset="utf-8">		
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_input_3', 
		{
			unique: true, plugins: {autocomplete: {}},
			bitsOptions:{editable:{addKeys: [188]}}	});
		<?php 
			if($lessonid != 0){
				$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='1' AND b.fld_access='1' AND a.fld_created_by='".$uid."' AND a.fld_delstatus='0' AND b.fld_item_id='".$lessonid."'");
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
				elements : "ipldescription",
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


<section data-type='2home' id='library-lessons-newlesson'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle"><?php echo $pageTitle; ?></p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
            	<form id="lessonform" name="lessonform">
                	<div class='row'>
                        <div class='six columns'> <!-- Lesson Details -->
                        	Lesson<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='Lesson Name' required='' type='text' id="lessonsname" name="lessonsname" value="<?php echo $lessonname ;?>" onBlur="$(this).valid();">
                                </dt>                                
                            </dl>     
                            Unit<span class="fldreq">*</span>
                            <dl class='field row' id='unid'>  
                                <dt class='dropdown'>  
                                    <div id="unit"> <!-- Unit -->   
                                        <div class="selectbox">
                                            <input type="hidden" name="unitid" id="unitid" value="<?php echo $unitid;?>"  onchange="$(this).valid();"  />
                                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                            	<span class="selectbox-option input-medium" data-option="<?php echo $unitid;?>" id="clearunit"><?php echo $unitname; ?></span>
                                                <b class="caret1"></b>
                                            </a>                                           
                                            <div class="selectbox-options">
                                                <input type="text" class="selectbox-filter" placeholder="Search Unit">
                                                <ul role="options">
                                                    <?php 
                                                    $unitqry = $ObjDB->QueryObject("SELECT fld_id AS unitid, fld_unit_name AS unitname FROM itc_unit_master WHERE fld_delstatus= '0' ORDER BY fld_unit_name ");
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
                            Points<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                	<input placeholder='Points' required='' type='text' id="Points" name="Points" maxlength="3" value="<?php echo $iplpoints; ?>">
                                </dt>
                            </dl>
                            Days<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                	<input placeholder='Days' required='' type='text' id="Days" name="Days" maxlength="2" value="<?php echo $ipldays;?>">
                                </dt>
                            </dl>
                            Minutes<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                	<input placeholder='Minutes' required='' type='text' id="Minutes" name="Minutes" maxlength="3" value="<?php echo $iplminutes;?>">
                                </dt>
                            </dl>
                        </div>
                        <div class='six columns'> <!-- Textarea - Lesson Description -->
                        	Description
                            <dl class='field row'>
                                <dt class='textarea'>
                                    <textarea placeholder='Tell us about your new lesson' id="ipldescription" name="ipldescription" style="height:315px; width:100%; border-color:#FFF; resize:none;"><?php echo htmlentities($ipldescr); ?></textarea>
                                </dt>                                
                            </dl>	
                        </div>
                    </div>	
                    
                    <div class='row rowspacer'> <!-- Tag Well -->
                    	<div class='six columns'>
                        	Asset ID<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                	<input placeholder='Asset ID' required='' type='text' id="txtassetid" name="txtassetid" value="<?php echo $assetid;?>" onkeypress="return IsAlphaNumeric(event);" ondrop="return false;">
                                </dt>
                            </dl>
                        </div>
                        <div class='six columns'>
                        	Lesson Type<span class="fldreq">*</span>
                            <dl class='field row'>
                               <div class="selectbox">
                                    <input type="hidden" name="lessontype" id="lessontype" value="<?php echo $lessontype ;?>">
                                    <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                        <span class="selectbox-option input-medium" data-option="<?php echo $lessontype ;?>"><?php echo $lessontypename ;?></span>
                                        <b class="caret1"></b>
                                    </a>
                                    <?php
									$lessonorientation = $ObjDB->COUNT("SELECT fld_id FROM itc_ipl_master WHERE fld_lesson_type='2' AND fld_delstatus='0'");
										if($lessonorientation==0)
										{
									?>
                                            <div class="selectbox-options" >			    
                                                <ul role="options" >
                                                    <li><a tabindex="-1" href="#" data-option="1">Normal</a></li>												
                                                    <li><a tabindex="-1" href="#" data-option="2">Orientation</a></li>	
                                                </ul>
                                            </div>
									<?php
										}
									?>
                                </div>
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
                        	<input id="webiplUploader" name="webiplUploader" type="file" />	<!-- Web IPL Upload Button -->
                            <div id="queue">
                            	<?php 
									if($weblessonname != '') {
										echo "<br /><br />".$tempweblessonname; 
								?>
                                	<input type="button" id="lessons-preview" value="Preview" onClick="fn_previewlesson('<?php echo $weblessonname; ?>',<?php echo $lessonid; ?>);" name="lessons-preview" align="right" />
                                <?php
									}
								?>
                            </div>
                        </div>
                        <div class='three columns' id="webipldropdown" <?php if($lessonid==0){echo "style='display:none;'";}?>> <!-- Web IPL 2nd column -->
                        	<div class="dropdown" id="webiplversion">
                                <div class="selectbox" > <!-- Web IPL Version Dropdown -->
                                    <input type="hidden" name="webversion" id="webversion" value="<?php echo $webversion;?>" >
                                    <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">                      	
                                        <span class="selectbox-option" style="width:95%;" data-option="<?php echo $webversion;?>">Version <?php echo $webversion;?> </span>
                                        <b class="caret1"></b>
                                    </a>
                                    <div class="selectbox-options">
                                        <ul role="options">
                                        <?php 
											$qrygetvers = $ObjDB->QueryObject("SELECT fld_version FROM `itc_ipl_version_track` WHERE fld_ipl_id='".$lessonid."' and fld_zip_type='1'");							
											if($qrygetvers->num_rows > 0) {
                                          		while($res = $qrygetvers->fetch_object()){?>
                                                	<li><a  href="#" data-option="<?php echo $res->fld_version; ?>" onclick="fn_changewebiplname('<?php echo $res->fld_version;?>')">Version <?php echo $res->fld_version; ?></a></li>
                                        <?php 
												}
											}
											?>
                                        </ul>
                                    </div>                                    
                                </div>
                          	</div>
                        </div>
                        <div class='three columns' id="webipltextbox" <?php if($lessonid!=0){echo "style='display:none;'";}?>> 
                        	<dl class='field row'>
                                <dt class='text'>
                                	<input placeholder='version' type='text' id="webversiontxt" name="webversiontxt" value="" />
                                </dt>
                            </dl>
                        </div>
                        <div class='four columns'> <!-- Web IPL 3rd Column -->
                        	<p class='lableRight'>Upload your web IPL in ZIP format and assign a version number.</p>	
                        </div>
                    </div>
                    
                    <div class='row rowspacer' style="margin-top:50px;"> <!-- IPL Icon -->
                    	<div class='five columns'> <!-- IPL Icon 1st column -->
                        	<input id="ipliconUploader" name="ipliconUploader" type="file" />	<!-- Web IPL Upload Button -->
                            <div id="queueicon"></div>
                        </div>
                        <div class='three columns'> <!-- IPL Icon 2nd Column -->
                            <div class='iconPreview'> <!-- IPL Icon Image Preview -->
                            	<?php 
									if($ipliconname != '' and $ipliconname != 'no-image.png') {
								?>
                                	<img id="preview" height="100" width="100" src="<?php echo  __CNTICONPATH__.$ipliconname; ?>" />
                                <?php
									}
								?>
                            </div>
                        </div>
                        <div class='four columns'> <!-- IPL Icon 3rd Column -->
                        	<p class='lableRight'>Upload an image to use as this lesson's icon. (jpg, png, bmp)</p>	
                        </div>
                    </div>
                    
                      <script type="text/javascript" language="javascript">
								<?php if($lessonid!=0){ ?>								
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
                                                                                                WHERE a.fld_id NOT IN(SELECT fld_license_id FROM itc_license_cul_mapping WHERE fld_lesson_id='".$lessonid."' AND fld_unit_id='".$unitid1."'
                                                                                                AND fld_active='1')
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
                                        </div>
                                        <div class="dragAllLink"  onclick="fn_movealllistitems('list9','list10',0,0);">add all licenses</div>
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
                                         <div class="dragWell" id="testrailvisible1">
                                            <div id="list10" class="dragleftinner droptrue1">
                                             <?php                                            
                                              $qrystudent= $ObjDB->QueryObject("SELECT a.fld_id,a.fld_license_name AS licensename, 
                                                                                            a.fld_license_name AS shortname 
                                                                                            FROM itc_license_master As a
                                                                                            WHERE a.fld_id IN(SELECT fld_license_id FROM itc_license_cul_mapping WHERE fld_unit_id='".$unitid1."' AND fld_lesson_id='".$lessonid."'
                                                                                           AND fld_active='1')
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
                    
                    
                    <div class='row rowspacer' style="padding-top:20px;">
                        <div class='six columns'>
                            <p class='btn primary twelve columns'>
                                <a onclick="<?php echo $cancelclick; ?>"><?php echo $cancelbtn;?></a>
                            </p>
                        </div>
                        <div class='six columns'>
                            <p class='btn secondary twelve columns'>
                                <a onclick="fn_createlessons()"><?php echo $createbtn;?></a>
                            </p>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>        
        <input type="hidden" id="webhid" name="webhid" value="<?php echo $weblessonname; ?>" />
        <input type="hidden" id="iconhid" name="iconhid" value="<?php echo $ipliconname; ?>" />
        <input type="hidden" id="lessonid" name="lessonid" value="<?php echo $lessonid; ?>" />
        <input type="hidden" id="webflag" name="webflag" value="0" />

        <script type="text/javascript" language="javascript">
			/*-------form validation-------*/
			$("#Points,#Days,#Minutes").keypress(function (e) {
				if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
					return false;
				}
			});
                        
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


			$(function(){						
				/*--------web ipl----------*/
				$('#webiplUploader').uploadify({
					'formData'     : {
						'timestamp' : '<?php echo $timestamp; ?>',
						'token'     : '<?php echo md5('nanonino' . $timestamp);?>',
						'oper' : 'webipl'
					},
					'swf'      : 'uploadify/uploadify.swf',
					'uploader' : '<?php echo _CONTENTURL_; ?>uploadify.php',
					'buttonClass' : 'btn',
					'buttonText' : 'Select an IPL',
					'fileTypeExts' : '*.zip',
					'queueID'  : 'queue',
					'queueSizeLimit' : 1,
					'width' : 300,
					'onUploadSuccess' : function(file, data, response) {
						if(data == '' || data == undefined || data == 'invalid'){
							alert("Please upload a valid web lesson zip");
							return false;
						}else{
							$('#webhid').val(data);
							$('#queue').html('<br /><br />'+data+' <input type="button" id="lessons-preview" value="Preview" onClick="fn_previewlesson(\''+data+'\',<?php echo $lessonid; ?>);" name="lessons-preview" align="right" />'); 
						}
						if(<?php echo $lessonid;?> != 0){
							$('#webipldropdown').remove();										
							$('#webipltextbox').show();	
							$('#webflag').val('1');								
						}
					}
				});
				
				/*--------ipl icon----------*/
				$('#ipliconUploader').uploadify({
					'formData'     : {
						'timestamp' : '<?php echo $timestamp; ?>',
						'token'     : '<?php echo md5('nanonino' . $timestamp);?>',
						'oper' : 'iplicon'
					},
					'swf'      : 'uploadify/uploadify.swf',
					'uploader' : '<?php echo _CONTENTURL_;?>uploadify.php',
					'buttonClass' : 'btn',
					'buttonText' : 'Select an Icon',
					'fileTypeExts' : '*.png;*.jpg',
					'queueID'  : 'queueicon',
					'queueSizeLimit' : 1,
					'width' : 300,
					'onUploadSuccess' : function(file, data, response) {
						$('.iconPreview').html('<img id="preview" height="100" width="100" src="<?php echo __CNTICONPATH__; ?>'+data+'" />'); 
						$('#iconhid').val(data);
					}
				});
				
				/****form validate*****/
				$("#lessonform").validate({
					ignore: "",
					errorElement: "dd",
					errorPlacement: function(error, element) {
						$(element).parents('dl').addClass('error');
						error.appendTo($(element).parents('dl'));	
						error.addClass('msg');
					},
					rules: {
						Points: { required: true },
						Days: { required: true },
						Minutes: { required: true },
						unitid: { required: true },
						lessonsname: { required: true, lettersonly: true },
						txtassetid:  { required: true, remote:{ 
															url: "library/lessons/library-lessons-newlessons-ajax.php", 
															data: {   
																lid: function() { return '<?php echo $lessonid;?>';},
																oper: function() { return 'checkassetid'; }	
															},
															type:"post"
														}
									}
					}, 
					messages: { 
						Points:{  required:  "Please Type Points" },                
						Days:{  required: "Please Type Days" },
						Minutes:{ required: "Please Type Minutes" },		   						  
						unitid: {  required: "Please Select Unit" },
						lessonsname: { required: "Please Type Lesson Name"/*, remote: "Lesson Name Already Exists"*/	},
						txtassetid: { required: "Please Type Asset ID", remote: "Asset ID Already Exists" }
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