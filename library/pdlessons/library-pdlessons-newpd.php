<?php
/*------
	Page - library-pd-newlessons
	Description:
		Form to add a new pd details or edit an existing pd details
		
	Actions Performed:	
		Create and Edit
	
	History:	
		
------*/
       
        @include("sessioncheck.php");
	
	$pdid = (isset($_POST['id']) and $_POST['id'] != '') ? $_POST['id'] : 0;        
	
        
	$createbtn = "Create pdlesson";
	$cancelbtn = "Cancel";	
	$pageTitle = "New pdlesson";
        $coursename = "Select Course";
	$webversion="";
	$ipadversion="";
	$ipadremversion="";
	$cancelclick="fn_cancel('library-pdlesson')";
	$pdiconname = "no-image.png";
	$pdtypename = "Normal";
	$pdtype = "1";
	$pdname = $iplpoints = $assetid = $pddescr ='';
        $timestamp = time();
	$webpdname = "";
        
        $qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid, a.fld_tag_name AS tagname FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='30' AND b.fld_access='1' AND a.fld_created_by='".$uid."' AND a.fld_delstatus='0' AND b.fld_item_id='".$pdid."'");
		
	if($pdid != 0){
				
		$createbtn = "Update this pdlesson";
		$cancelbtn = "Cancel";
		$pageTitle = "Edit pdlesson";
		$cancelclick="fn_cancel('library-pdlessons-actions');";
		 
		$qry_pddetails = $ObjDB->QueryObject("SELECT a.fld_course_id as courseid,a.fld_lesson_type as pdtype, a.fld_id , a.fld_pd_name AS pdname, a.fld_pd_descr AS pddescr, a.fld_pd_icon AS pdiconname,  a.fld_pd_icon AS tempipliconname, b.fld_version AS versions, b.fld_zip_type AS ziptype, b.fld_zip_name AS zipname, b.fld_zip_name AS zipshortname,  a.fld_asset_id AS assetid,e.fld_course_name AS coursename FROM itc_pd_master AS a LEFT JOIN itc_pd_version_track AS b ON a.fld_id=b.fld_pd_id LEFT JOIN itc_course_master AS e ON e.fld_id=a.fld_course_id WHERE a.fld_id='".$pdid."' AND b.fld_delstatus='0' AND e.fld_delstatus='0'");
		if($qry_pddetails->num_rows>0){
			while($res_pddetails = $qry_pddetails->fetch_assoc()){			
				extract($res_pddetails);
				if($ziptype==1){
					$webpdname=$zipname;
					$tempwebpdname = $zipshortname;
					$webversion = $versions;				
				}
			}
			if($pdtype==1){
				$pdtypename = "Normal";
			} else{
				$pdtypename = "Orientation";
			}
		}	
	}	
?>
<!--Script for the Tag Well-->
<script language="javascript" type="text/javascript" charset="utf-8">
       $(function(){				
            var t4 = new $.TextboxList('#form_tags_input_3', 
            {
                unique: true, plugins: {autocomplete: {}},
                bitsOptions:{editable:{addKeys: [188]}}	});
            <?php 
                if($qrytag->num_rows > 0) {
                    while($restag = $qrytag->fetch_assoc()){
                        extract($restag);
            ?>
                    t4.add('<?php echo $ObjDB->EscapeStrAll($tagname); ?>','<?php echo $tagid; ?>');				
            <?php 	}
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
				elements : "pddescription",
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

<section data-type='2home' id='library-pdlessons-newpd'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle"><?php echo $pageTitle; ?></p>
            </div>
        </div>
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
            	<form id="lessonform" name="lessonform">
                	<div class='row'>
                        <div class='six columns'> <!-- PD Details -->
                        	Pdlesson Name<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='Pdlesson Name' required='' type='text' id="pdname" name="pdname" value="<?php echo $pdname ;?>" onBlur="$(this).valid();">
                                </dt>                                
                            </dl>     
                      
                            Course<span class="fldreq">*</span>
                            <dl class='field row' id='courid'>  
                                <dt class='dropdown'>  
                                    <div id="course"> <!-- Unit -->   
                                        <div class="selectbox">
                                            <input type="hidden" name="courseid" id="courseid" value="<?php echo $courseid;?>"  onchange="$(this).valid();"  />
                                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                            	<span class="selectbox-option input-medium" data-option="<?php echo $courseid;?>" id="clearcourse"><?php echo $coursename; ?></span>
                                                <b class="caret1"></b>
                                            </a>                                           
                                            <div class="selectbox-options">
                                                <input type="text" class="selectbox-filter" placeholder="Search Course">
                                                <ul role="options">
                                                    <?php 
                                                    $courseqry = $ObjDB->QueryObject("SELECT fld_id AS courseid, fld_course_name AS coursename FROM itc_course_master WHERE fld_delstatus= '0' ORDER BY fld_course_name ");
                                                    if($courseqry->num_rows > 0)
                                                    {
                                                        while($rowcourse = $courseqry->fetch_assoc())
                                                        {
															extract($rowcourse);
                                                        ?>
                                                            <li><a tabindex="-1" href="#" data-option="<?php echo $courseid;?>"><?php echo $coursename; ?></a></li>
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
                            
                            Asset ID<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='Asset ID' required='' type='text' id="txtassetid" name="txtassetid" value="<?php echo $assetid;?>" onkeypress="return IsAlphaNumeric(event);" ondrop="return false;">
                                </dt>
                            </dl>
                            
                            Pdlesson Type<span class="fldreq">*</span>
                            <dl class='field row'>
                               <div class="selectbox">
                                    <input type="hidden" name="pdtype" id="pdtype" value="<?php echo $pdtype ;?>">
                                    <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                        <span class="selectbox-option input-medium" data-option="<?php echo $pdtype ;?>"><?php echo $pdtypename ;?></span>
                                        <b class="caret1"></b>
                                    </a>
                                    <?php
                                            $lessonorientation = $ObjDB->COUNT("SELECT fld_id FROM itc_pd_master WHERE fld_lesson_type='2' AND fld_delstatus='0'");
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
                        <div class='six columns'> <!-- Textarea - PD Description -->
                        	Description<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='textarea'>
                                    <textarea placeholder='Tell us about your new PD' id="pddescription" name="pddescription"  style="height:315px; width:100%;  resize:none;" ><?php echo htmlentities($pddescr); ?></textarea>
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
                    
                    <div class='row rowspacer' style="margin-top:25px;"> <!-- Web pd -->
                    	<div class='five columns'> <!-- Web pd 1st column -->
                        	<input id="webiplUploader" name="webiplUploader" type="file" />	<!-- Web pd Upload Button -->
                            <div id="queue">
                            	<?php 
                                    if($webpdname != '') {
                                            echo "<br /><br />".$tempwebpdname; 
                                                        ?>
                                <?php
                                        }
                                ?>
                            </div>
                        </div>
                        <div class='three columns' id="webipldropdown" <?php if($pdid==0){echo "style='display:none;'";}?>> <!-- Web pd 2nd column -->
                            <div class="dropdown" id="webiplversion">
                                <div class="selectbox" > <!-- Web pd Version Dropdown -->
                                    <input type="hidden" name="webversion" id="webversion" value="<?php echo $webversion;?>" >
                                    <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">                      	
                                        <span class="selectbox-option" style="width:95%;" data-option="<?php echo $webversion;?>">Version <?php echo $webversion;?> </span>
                                        <b class="caret1"></b>
                                    </a>
                                    <div class="selectbox-options">
                                        <ul role="options">
                                        <?php 
                                        $qrygetvers = $ObjDB->QueryObject("SELECT fld_version FROM `itc_pd_version_track` WHERE fld_pd_id='".$pdid."' and fld_zip_type='1'");							
                                        if($qrygetvers->num_rows > 0) {
                                        while($res = $qrygetvers->fetch_object()){?>
                                        <li><a  href="#" data-option="<?php echo $res->fld_version; ?>" onclick="fn_changewebpdname('<?php echo $res->fld_version;?>')">Version <?php echo $res->fld_version; ?></a></li>
                                         <?php 
                                                }
                                        }
                                         ?>
                                        </ul>
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                        <div class='three columns' id="webipltextbox" <?php if($pdid!=0){echo "style='display:none;'";}?>> 
                            <dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='version' type='text' id="webversiontxt" name="webversiontxt" value="" />
                                </dt>
                            </dl>
                        </div>
                        <div class='four columns'> <!-- Web pd 3rd Column -->
                            <p class='lableRight'></p>
                            <p class='lableRight'>Upload your web pd in ZIP format and assign a version number.</p>	
                        </div>
                    </div>
                    
                    <div class='row rowspacer' style="margin-top:50px;"> <!-- pd Icon -->
                    	<div class='five columns'> <!-- pd Icon 1st column -->
                            <input id="ipliconUploader" name="ipliconUploader" type="file" />	<!-- Web pd Upload Button -->
                            <div id="queueicon"></div>
                        </div>
                        <div class='three columns'> <!-- pd Icon 2nd Column -->
                            <div class='iconPreview'> <!-- pd Icon Image Preview -->
                            	<?php 
                                    if($pdiconname != '' and $pdiconname != 'no-image.png')
                                    {
                                        ?>
                                	<img id="preview" height="100" width="100" src="<?php echo  __CNTPDICONPATH__.$pdiconname; ?>" />
                                <?php
                                    }
                                    ?>
                            </div>
                        </div>
                        <div class='four columns'> <!-- pd Icon 3rd Column -->
                        	<p class='lableRight'>Upload an image to use as this pd's icon. (jpg, png, bmp)</p>	
                        </div>
                    </div>
                    
                    <script type="text/javascript" language="javascript">
                            <?php if($pdid!=0){ ?>                            
                            <?php }?>
                            $(function() {
                                    $('div[id^="testrailvisible"]').each(function(index, element) {
                                            $(this).slimscroll({ /*------- Scroll for Modules Left Box ------*/
                                                    width: '410px',
                                                    height:'366px',
                                                    size:'3px',
                                                    railVisible: true,
                                                    allowPageScroll: false,
                                                    railColor: '#F4F4F4',
                                                    opacity: 1,
                                                    color: '#d9d9d9',
                                            });
                                    });
                                    $("#list15").sortable({
                                            connectWith: ".droptrue1",
                                            dropOnEmpty: true,
                                            items: "div[class='draglinkleft']",
                                            receive: function(event, ui) { 
                                                    $("div[class=draglinkright]").each(function(){ 
                                                            if($(this).parent().attr('id')=='list15'){
                                                                    fn_movealllistitems('list15','list16',$(this).children(":first").attr('id'));
                                                            }
                                                    });											
                                            }
                                    });

                                    $( "#list16" ).sortable({
                                            connectWith: ".droptrue1",
                                            dropOnEmpty: true,
                                            receive: function(event, ui) { 
                                                    $("div[class=draglinkleft]").each(function(){ 
                                                            if($(this).parent().attr('id')=='list16'){
                                                                    fn_movealllistitems('list15','list16',$(this).children(":first").attr('id'));
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
                                    
                                        $qrypd= $ObjDB->QueryObject("SELECT a.fld_id,a.fld_license_name AS licensename, 
                                                                                                a.fld_license_name AS shortname 
                                                                                                FROM itc_license_master As a
                                                                                                WHERE a.fld_id NOT IN(SELECT fld_license_id FROM itc_license_pd_mapping WHERE fld_pd_id='".$pdid."'
                                                                                                AND fld_active='1')
                                                                                                AND a.fld_delstatus='0' AND a.fld_license_type='1' 
                                                                                                ORDER BY licensename ASC");
									?>
                                        <div class="dragtitle">License available</div>
                                        <div class="draglinkleftSearch" id="s_list15" >
                                           <dl class='field row'>
                                                <dt class='text'>
                                                    <input placeholder='Search' type='text' id="list_15_search" name="list_15_search" onKeyUp="search_list(this,'#list15');" />
                                                </dt>
                                            </dl>
                                        </div>
                                        <div class="dragWell" id="testrailvisible15" >
                                            <div id="list15" class="dragleftinner droptrue1">
                                             <?php 		
                                               if($qrypd->num_rows > 0){													
                                                    while($rowspd = $qrypd->fetch_assoc()){
                                                        extract($rowspd);
                                                        ?>
                                                    <div class="draglinkleft" id="list15_<?php echo $fld_id; ?>" >
                                                        <div class="dragItemLable tooltip" id="<?php echo $fld_id; ?>" title="<?php echo $pdname;?>"><?php echo $shortname; ?></div>
                                                        <div class="clickable" id="clck_<?php echo $fld_id; ?>" onclick="fn_movealllistitems('list15','list16',<?php echo $fld_id; ?>);"></div>
                                                    </div> 
                                            <?php 
                                                    }
                                                }
                                            ?>
                                            </div>
                                        </div>
                                        <div class="dragAllLink"  onclick="fn_movealllistitems('list15','list16',0,0);">add all licenses</div>
                                    </div>
                                </div>
                            <div class='six columns'>
                                    <div class="dragndropcol">
                                        <div class="dragtitle">License in your PD </div>
                                        <div class="draglinkleftSearch" id="s_list16" >
                                           <dl class='field row'>
                                                <dt class='text'>
                                                    <input placeholder='Search' type='text' id="list_16_search" name="list_16_search" onKeyUp="search_list(this,'#list16');" />
                                                </dt>
                                            </dl>
                                        </div>
                                         <div class="dragWell" id="testrailvisible16">
                                            <div id="list16" class="dragleftinner droptrue1">
                                             <?php                                            
                                              $qrypd= $ObjDB->QueryObject("SELECT a.fld_id,a.fld_license_name AS licensename, 
                                                                                                a.fld_license_name AS shortname 
                                                                                                FROM itc_license_master As a
                                                                                                WHERE a.fld_id IN(SELECT fld_license_id FROM itc_license_pd_mapping WHERE fld_pd_id='".$pdid."'
                                                                                                AND fld_active='1')
                                                                                                AND a.fld_delstatus='0' AND a.fld_license_type='1' 
                                                                                                ORDER BY licensename ASC");
						
                                                if($qrypd->num_rows > 0){													
                                                    while($rowspd = $qrypd->fetch_assoc()){
                                                        extract($rowspd);
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
                                                    <div class="draglinkright <?php if($getlicenseholderqry!=0) echo " dim";?>" id="list16_<?php echo $fld_id; ?>">
                                                        <div class="dragItemLable tooltip" id="<?php echo $fld_id; ?>" title="<?php echo $pdname;?>"><?php echo $shortname; ?></div>
                                                        <div class="clickable" id="clck_<?php echo $fld_id; ?>" onclick="fn_movealllistitems('list15','list16',<?php echo $fld_id; ?>);"></div>
                                                    </div>
                                            <?php 	}
                                                }
                                             
                                            ?>
                                            </div>
                                        </div>
                                        <div class="dragAllLink" onclick="fn_movealllistitems('list16','list15',0,0);">remove all licenses</div>
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
                                <a onclick="fn_createpd()"><?php echo $createbtn;?></a>
                            </p>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>        
        <input type="hidden" id="webhid" name="webhid" value="<?php echo $webpdname; ?>" />
        <input type="hidden" id="iconhid" name="iconhid" value="<?php echo $pdiconname; ?>" />
        <input type="hidden" id="pdid" name="pdid" value="<?php echo $pdid; ?>" />
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
				/*--------web pd----------*/
				$('#webiplUploader').uploadify({
					'formData'     : {
						'timestamp' : '<?php echo $timestamp; ?>',
						'token'     : '<?php echo md5('nanonino' . $timestamp);?>',
						'oper' : 'webpdlesson'
					},
					'swf'      : 'uploadify/uploadify.swf',
					'uploader' : '<?php echo _CONTENTURL_; ?>uploadify.php',
					'buttonClass' : 'btn',
					'buttonText' : 'Select a PD',
					'fileTypeExts' : '*.zip',
					'queueID'  : 'queue',
					'queueSizeLimit' : 1,
					'width' : 300,
					'onUploadSuccess' : function(file, data, response) {
						if(data == '' || data == undefined || data == 'invalid'){
							alert("Please upload a valid web pd zip");
							return false;
						}else{
							$('#webhid').val(data);
							$('#queue').html('<br /><br />'+data+' <input type="button" id="lessons-preview" value="Preview" onClick="fn_previewlesson(\''+data+'\',<?php echo $pdid; ?>);" name="lessons-preview" align="right" />'); 
                                                        $('#queue').html('<br /><br />'+data); 
						}
						if(<?php echo $pdid;?> != 0){
							$('#webipldropdown').remove();										
							$('#webipltextbox').show();	
							$('#webflag').val('1');								
						}
					}
				});
				
				/*--------pd icon----------*/
				$('#ipliconUploader').uploadify({
					'formData'     : {
						'timestamp' : '<?php echo $timestamp; ?>',
						'token'     : '<?php echo md5('nanonino' . $timestamp);?>',
						'oper' : 'pdlessonicon'
					},
					'swf'      : 'uploadify/uploadify.swf',
					'uploader' : '<?php echo _CONTENTURL_;?>uploadify.php', 
					'buttonClass' : 'btn',
					'buttonText' : 'Select a Icon',
					'fileTypeExts' : '*.png;*.jpg',
					'queueID'  : 'queueicon',
					'queueSizeLimit' : 1,
					'width' : 300,
					'onUploadSuccess' : function(file, data, response) {
						$('.iconPreview').html('<img id="preview" height="100" width="100" src="<?php echo __CNTPDICONPATH__; ?>'+data+'" />'); 
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
												
						courseid: { required: true },
						pdname: { required: true, lettersonly: true },
						txtassetid:  { required: true, remote:{  url: "library/pdlessons/library-pdlessons-newpd-ajax.php", 
                                                                 type:"post",
                                                                        data: {   
                                                                                lid: function() { return '<?php echo $pdid;?>';},
                                                                                oper: function() { return 'checkassetid'; }	
                                                                              },

                                                                       async:false }
                                                    }
					}, 
					messages: { 
								   									
						courseid: {  required: "Please Select Course" },
						pdname: { required: "Please Type Lesson Name"/*, remote: "Lesson Name Already Exists"*/	},
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