<?php
@include("sessioncheck.php");

/*
	Created By - Muthukumar. D
	Page - library-modules-newmodule
	Description:
		Show the Phase & version dropdowns, Module name, Module Description, Minutes & Days textboxes, Upload button, Tag to Create/Edit the Diagnostic-Mastery Details
		View - Shows only the Session Buttons(1-7).
		Edit/Create - Shows everything except the Session Buttons(1-7).
		
	Actions Performed:
		Upload - Uploads the module content.
		Tag - Creates a new tag to save the Module details.
		
	History:
	

*/
$id = isset($method['id']) ? $method['id'] : '0';
$id = explode(",",$id);
$assetid ='';
$filetype='';
//$id[1] - Type (View/Edit)
//$id[1] = 1 - View Type
//$id[1] = 0 - Edit/Create Type

$moduleid = $id[0];

$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid, a.fld_tag_name AS tagname 
                              FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
							  WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='3' 
							  AND b.fld_access='1' AND a.fld_created_by='".$uid."' 
							  AND a.fld_delstatus='0' AND b.fld_item_id='".$moduleid."'   
							  GROUP BY a.fld_id ");

if($moduleid==0){
	$createbtn = "Create Module";
	$cancelbtn = "Cancel";
	$modulename = "";
	$modulephase = "1";
	$modulephasename = "No Phase";
	$moduletypes = "1";
	$moduletypesname = "Normal";
	$moduleminutes = "45";
	$moduledesc = "";
	$moduledays = "7";
	$moduleversion = "";
	$filename = "";
	$type = "";
	$msg = "New Module";
	$cancelclick="fn_cancel('library-modules')";
         $moduledescr ='';
       
}
else{
	$createbtn = "Update this Module";
	$cancelbtn = "Cancel";
	$moduleqry = $ObjDB->QueryObject("SELECT a.fld_module_name, a.fld_phase, a.fld_minutes, 
	                                         a.fld_days, a.fld_asset_id, a.fld_module_descr ,
											a.fld_module_type, b.fld_version, b.fld_file_name, b.fld_file_type 
									FROM itc_module_master AS a 
									LEFT JOIN itc_module_version_track AS b ON a.fld_id = b.fld_mod_id 
									WHERE a.fld_id='".$moduleid."' AND a.fld_delstatus='0' 
									AND b.fld_delstatus='0' GROUP BY a.fld_module_name");

	while($rowmodule=$moduleqry->fetch_assoc())
	{
		extract($rowmodule);
		$modulename = $fld_module_name;
		$modulephase = $fld_phase;
		$moduleminutes = $fld_minutes;
		$moduledays = $fld_days;
		$moduleversion = $fld_version;
		$filename = $fld_file_name;
		$type = $fld_file_type;
		$moduletypes = $fld_module_type;
		$assetid = $fld_asset_id;
                $moduledescr= $fld_module_descr;
		
		if($type==1)
			$filetype=".sbook";
		else if($type==0)
			$filetype=".zip";
			
		if($modulephase==1)
			$modulephasename = "No Phase";
		else if($modulephase==2)
			$modulephasename = "Phase 2";
		else if($modulephase==3)
			$modulephasename = "Phase 3";
		
		if($moduletypes==1)
			$moduletypesname = "Normal";
		else if($moduletypes==2)
			$moduletypesname = "Orientation Module";
		else if($moduletypes==3)
			$moduletypesname = "Orientation Math Module";
			
		$fullfilename = $filename.$filetype;
	}
	if($id[1]==1)
		$msg = "View ".$modulename;
	else
		$msg = "Edit ".$modulename;
	$cancelclick="fn_cancel('library-modules-actions')";			
}
if($id[1]!=1)
{?>
<!--Script for the Tag Well-->
<script language="javascript" type="text/javascript" charset="utf-8">
		
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_mod', 
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
				elements : "moduledescription",
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
<section data-type='2home' id='library-modules-newmodule'>
    <div class='container'>
    	<!--Load the Module Name / New module-->
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $msg; ?></p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <!--Load the Module Form-->
        <div class='row formBase'>
            <div class='eleven columns centered insideForm'>
                <form name="moduleforms" id="moduleforms">
                    <?php 
                    if($id[1]!=1)
                    {?>
                    
                    <!--Upload Content-->
                    <div class='row'>
                    	<!--Upload Module-->
                        <div class='six columns'>
                         	Select Module Content<span class="fldreq">*</span>
                            <div class='row'>
                            	<input type="file" name="file_upload_module" id="file_upload_module" />
                                <div id="queue" ></div>
                                <div class="profile-preview" style="float:left;margin-left:25px;text-align:left;width:100%;"><?php echo $filename.$filetype; ?></div>
                                
                            </div>
                        </div>
                        
                        <!--Version For Module-->
                        <div class='three columns'>
                        	Version<span class="fldreq">*</span>
                            <div class='row'>
                                <div class="selectbox" id="moduleselectbox">
                                    <input type="hidden" name="selectversion" class="required" id="selectversion" value="<?php echo $moduleversion;?>" >
                                    <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                        <span class="selectbox-option input-medium" data-option="<?php echo $moduleversion;?>" id="versions"  style="width:95%;">Version <?php echo $moduleversion;?></span>
                                        <b class="caret1"></b>
                                    </a>
                                    <?php if($moduleid!=0){?>
                                    <div class="selectbox-options">			    
                                        <ul role="options">
                                           <?php $qry = $ObjDB->QueryObject("select fld_version from itc_module_version_track where fld_mod_id='".$moduleid."' AND fld_delstatus='0'");
                                            while($res = $qry->fetch_object()){?>
                                               <li><a  href="#" data-option="<?php echo $res->fld_version; ?>" onclick="fn_changemodulename(<?php echo $res->fld_version;?>)">Version <?php echo $res->fld_version; ?></a></li>
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
                                <p class='lableRight'>Upload your Module using one of the following formats: .zip, .sbook</p>
                            </div>
                        </div>
                    </div>
                    
                    
                    <!--Module Name Textbox and Phase Dropdown-->
                    <div class='row '>
                        <div class='six columns'>
                        	New Module Name<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                <input placeholder='New Module Name' type='text' id="txtmodname" name="txtmodname" value="<?php echo $modulename;?>" onBlur="$(this).valid(); "/>
                                </dt>
                            </dl>
                       Select Phase
                            <dl class='field row'>
                                <div class="selectbox">
                                    <input type="hidden" name="selectphase" id="selectphase" value="<?php echo $modulephase ;?>">
                                    <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                        <span class="selectbox-option input-medium" data-option="<?php echo $modulephase ;?>"><?php echo $modulephasename ;?></span>
                                        <b class="caret1"></b>
                                    </a>
                                </div>
                              </dl>
                                    <dl class='field row'>
                                    <div class="selectbox-options" >
                                    	<input type="text" class="selectbox-filter" placeholder="Search Phase">			    
                                        <ul role="options" >
                                        	<li><a tabindex="-1" href="#" data-option="1">No Phase</a></li>
                                            <li><a tabindex="-1" href="#" data-option="2">Phase 2</a></li>
                                            <li><a tabindex="-1" href="#" data-option="3">Phase 3</a></li>
                                        </ul>
                                    </div>
                            </dl>
                    
                    <!--Minutes and Days Textboxes-->
                   
                       Minutes
                            <dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='Minutes' maxlength='3' type='text' id="txtmodminutes" name="txtmodminutes" value="<?php echo $moduleminutes ;?>" onBlur="$(this).valid();" />
                                </dt>
                            </dl>
                       
                        Days
                            <dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='Days' maxlength='2' type='text' id="txtmoddays" name="txtmoddays" value="<?php echo $moduledays ;?>" onBlur="$(this).valid();" />
                                </dt>
                            </dl>
                    
                    <!--Asset Id and Module Type-->
                        	Asset ID<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='Asset ID' type='text' id="txtassetid" name="txtassetid" value="<?php echo $assetid ;?>" onBlur="$(this).valid();" onkeypress="return IsAlphaNumeric(event);" ondrop="return false;"/>
                                </dt>
                            </dl>
                        
                       Module Types
                            <dl class='field row'>
                                <div class="selectbox">
                                    <input type="hidden" name="moduletypes" id="moduletypes" value="<?php echo $moduletypes ;?>">
                                    <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                        <span class="selectbox-option input-medium" data-option="<?php echo $moduletypes ;?>"><?php echo $moduletypesname ;?></span>
                                        <b class="caret1"></b>
                                    </a>
                                    <?php
									
									$qryfortypeoforientation = $ObjDB->QueryObject("SELECT COUNT(fld_id) AS modorientation ,
									                                              (SELECT COUNT(fld_id) FROM itc_module_master 
																				  WHERE fld_module_type=3 AND fld_delstatus='0') AS mathmodorientation 
																				  FROM itc_module_master WHERE fld_module_type=2  AND fld_delstatus='0'");
									
									$rowqryfortypeoforientation=$qryfortypeoforientation->fetch_assoc();
									extract($rowqryfortypeoforientation);
									
									if($modorientation==0 || $mathmodorientation==0)
									{
									?>
                                    <div class="selectbox-options" >			    
                                        <ul role="options" >
                                        	<li><a tabindex="-1" href="#" data-option="1">Normal</a></li>
                                            <?php if($modorientation==0) { ?>
                                            <li><a tabindex="-1" href="#" data-option="2">Orientation Module</a></li>
                                            <?php } if($mathmodorientation==0) { ?>
                                            <li><a tabindex="-1" href="#" data-option="3">Orientation Math Module</a></li>
                                            <?php }?>
                                        </ul>
                                    </div>
                                    <?php
									}
									?>
                                </div> 
                            </dl>
                        </div>
                            <div class='six columns'> <!-- Textarea - Lesson Description -->
                      Description
                             <dl class='field row'>
                                <dt class='textarea'>
                                    <textarea placeholder='Tell us about your new lesson' id="moduledescription" name="moduledescription" style="height:315px; width:100%; border-color:#FFF; resize:none;"><?php echo htmlentities($moduledescr); ?></textarea>
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
                    
                    <!--Script to Upload Module-->                
                    <script language="javascript" type="text/javascript">
                         					
                        $(document).ready(function() { 
						
						  <?php $timestamp = time();?>
						  $('#file_upload_module').uploadify({
								'swf'  : 'uploadify/uploadify.swf',
								'uploader'    : '<?php echo _CONTENTURL_;?>uploadify.php',
								'formData'     : {
										'timestamp' : '<?php echo $timestamp;?>',
										'token'     : '<?php echo md5('nanonino' . $timestamp);?>',
										'oper'      : 'modules' 
									},
								'method'   : 'post',
								'fileTypeExts'  : '*.zip;*.sbook',
								'fileTypeDesc'  : 'Module Conent',
								'buttonText' : 'Select Module Content',
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
                                       $('#createmodulebtn').addClass('dim');   
                                    },
								'onUploadSuccess'  : function(file, data, response) {
									
									 console.log(data);
									 
									 $('#createmodulebtn').removeClass('dim');
									 var upres = data.split("~");
									 if(upres[0] == 'success')
									 {
										$('#selectversion').val(upres[2]);
										$('#versions').html("Version "+upres[2]);
										$('.profile-preview').html('');
										$('.profile-preview').html(upres[1]);
										$('#hiduploadfile').val(upres[1]);
										$('#performance').val(upres[3]);
										$('#points').val(upres[4]);
										$('#quesid').val(upres[5]);
										$('#ansid').val(upres[6]);
										$('#anstext').val(upres[7]);
										$('#correct').val(upres[8]);
										$('#sectiontitle').val(upres[9]);
										$('#attenpoints').val(upres[10]);
										$('#partipoints').val(upres[11]);
										$('#pagecnt').val(upres[12]);
										$('#hidgrade').val(upres[13]);
										$('#hidtitle').val(upres[14]);
                                                                                $('#hidqcount').val(upres[15]);
                                                                                $('#txtmodname').val(upres[16]);
									 }
									
									if(upres[0] != 'success') {
										  
										 if(upres[0] == "invalid") {
										 	msg="Please upload a valid Module Content File";		 
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
								<?php if($moduleid!=0){ ?>								
								<?php }?>
								$(function() {
									$('#testrailvisible0').slimscroll({
										width: '410px',
										height:'366px',
										size: '3px',
										railVisible: true,
										allowPageScroll: false,
										railColor: '#F4F4F4',
										opacity: 1,
										color: '#d9d9d9',
										
									});
									$('#testrailvisible1').slimscroll({
										width: '410px',
										height:'366px',
										size: '3px',
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
                                                                                                WHERE a.fld_id NOT IN(SELECT fld_license_id FROM itc_license_mod_mapping WHERE fld_module_id='".$moduleid."'
                                                                                                AND fld_active='1' AND fld_type='1')
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
                                        <div class="dragtitle">License with this Module </div>
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
                                                                                            WHERE a.fld_id IN(SELECT fld_license_id FROM itc_license_mod_mapping WHERE fld_module_id='$moduleid'
                                                                                            AND fld_active='1' AND fld_type='1')
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
                            <input type="text" name="form_tags_mod" value="" id="form_tags_mod" />
                        </div>	
                    </div>
                    <?php }
                    if($id[1]==1)
                    {
                    ?>
                    <!--Session Buttons in View-->
                    	<div class='row'>
                        	<span>Preview Sessions: </span>
                  		</div>
                        <div class='row rowspacer'>
							<?php
                            $j=1;
                            for($i=0;$i<8;$i++)
                            { 
                                $tempe = $j;
                                if($tempe=='8'){
                                    $tempe='e';
                                        } 
                                ?>
                                <a class='skip btn sm<?php echo $tempe; ?> main' href="javascript:void(0);" id='btnlibrary-modules-player' onClick="showfullscreenmodule('<?php echo $i.",".$moduleid.",0,".$uid;?>',2);">
                                    <div class="onBtn"></div>
                                </a><?php
                                $j++;
                            }
                            ?>
	                    </div>
                    <?php 
                    }
                    if($id[1]!=1)
                    {?>
                    <!--Cancel and Create Buttons-->
                    <div class='row'>
                        <div class='four columns btn primary push_two noYes'>
                            <a onclick="<?php echo $cancelclick;?>"><?php echo $cancelbtn;?></a>
                        </div>
                        <div id="createmodulebtn" class='four columns btn secondary yesNo'>
                            <a onclick="fn_createmodule(<?php echo $moduleid;?>)"><?php echo $createbtn;?></a>
                        </div>
                    </div>
                    <?php }?>
                </form>
                <input type="hidden" id="hiduploadfile" name="hiduploadfile" value="<?php echo $filename.$filetype;?>" />
                <input type="hidden" id="performance" name="performance" value="" />
                <input type="hidden" id="points" name="points" value="" />
                <input type="hidden" name="checkversions" id="checkversions" value="">
                <input type="hidden" name="quesid" id="quesid" value="">
                <input type="hidden" name="ansid" id="ansid" value="">
                <input type="hidden" name="anstext" id="anstext" value="">
                <input type="hidden" name="correct" id="correct" value="">
                <input type="hidden" name="sectiontitle" id="sectiontitle" value="">
                <input type="hidden" name="attenpoints" id="attenpoints" value="">
                <input type="hidden" name="partipoints" id="partipoints" value="">
                <input type="hidden" name="pagecnt" id="pagecnt" value="">
                <input type="hidden" name="hidtitle" id="hidtitle" value="">
                <input type="hidden" name="hidgrade" id="hidgrade" value="">
                <input type="hidden" name="hidqcount" id="hidqcount" value="">
                
                <!--Script to Validate the Moduleform & Numbers for Textbox-->
                <script type="text/javascript" language="javascript">
					//Function to enter only numbers in textbox
                    $("#txtmodminutes,#txtmoddays").keypress(function (e) {
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

                    
                    //Function to validate the form
                    $(function(){
                        $("#moduleforms").validate({
                            ignore: "",
                            errorElement: "dd",
							errorPlacement: function(error, element) {
								$(element).parents('dl').addClass('error');
								error.appendTo($(element).parents('dl'));
								error.addClass('msg');								
								
							},
                            rules: { 
                                                                
                                                                txtmodname: { required: true },
								txtassetid:{ required: true}
												 },
                            messages: { 
                                                                txtmodname: { required: "Please type Module Name" }, 
                                                                txtassetid: { required: "Please type Asset ID"}
                            },
                             highlight: function(element, errorClass, validClass) {
                                $(element).parents('dl').addClass(errorClass);
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
					
					$(function(){
						var tabindex = 1;
						$('input,select').each(function() {
							if (this.type != "hidden") {
								var $input = $(this);
								$input.attr("tabindex", tabindex);
								tabindex++;
							}
						});
					});
                </script>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");