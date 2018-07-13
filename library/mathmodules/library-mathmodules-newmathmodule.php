<?php
/*------
	Page - library-mathmodules-newmathmodules
	Description:
		Form to add a new mathmodule details or edit an existing mathmodule details
		
	Actions Performed:	
		Create and Edit
	
	History:	
		
------*/

@include("sessioncheck.php");

$mathmoduleid = isset($method['id']) ? $method['id'] : 0;

$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid, a.fld_tag_name AS tagname 
								FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
					    		WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='23' AND b.fld_access='1' AND a.fld_created_by='".$uid."' 
									AND a.fld_delstatus='0' AND b.fld_item_id='".$mathmoduleid."'");

if($mathmoduleid == 0){
	$createbtn = "Create Math Module";
	$cancelbtn = "Cancel";
	$mathmodulename = "";
	$modulephase = "1";
	$modulephasename = "No Phase";
	$sessday1name = "Diagnostic Day 1 After Session?";
	$sessday2name = "Diagnostic Day 2 After Session?";
	$iplday1 = "0";
	$iplday2 = "0";
	$moduleminutes = "";
	$modulename = "";
	$moduledays = "";
	$modulename = "Select Module";
	$msg = "New Math Module";
	$cancelclick="fn_cancel('library-mathmodules')";
	$sessday2 = "";
	$mathmoduledescr ='';
}
else{
	$createbtn = "Update Math Module";
	$cancelbtn = "Cancel";
        
       
	$moduleqry = $ObjDB->QueryObject("SELECT a.fld_mathmodule_name, a.fld_phase, a.fld_minutes, a.fld_module_id, a.fld_days, a.fld_mathmodule_descr ,a.fld_session_day1,a.fld_session_day2, a.fld_ipl_day1, a.fld_ipl_day2, CONCAT(b.fld_module_name,' ',c.fld_version) AS modulename, c.fld_file_name 
		 FROM itc_mathmodule_master AS a 
			  LEFT JOIN itc_module_master AS b ON b.fld_id = a.fld_module_id 
			  LEFT JOIN itc_module_version_track AS c ON c.fld_mod_id=a.fld_module_id 
		WHERE a.fld_id='".$mathmoduleid."' AND a.fld_delstatus='0' AND b.fld_delstatus='0' AND c.fld_delstatus='0'");

	$rowmodule=$moduleqry->fetch_assoc();
	extract($rowmodule);

	$modulename = $modulename;
	$filename = $fld_file_name;
	$mathmodulename = $fld_mathmodule_name;
	$modulephase = $fld_phase;
	$moduleminutes = $fld_minutes;
	$moduledays = $fld_days;
	$moduleid = $fld_module_id;
	$sessday1 = $fld_session_day1;
	$sessday2 = $fld_session_day2;
	$iplday1 = $fld_ipl_day1;
	$iplday2 = $fld_ipl_day2;
	$sessday1name = "Session ".$sessday1;
	$sessday2name = "Session ".$sessday2;
	 $mathmoduledescr= $fld_mathmodule_descr;
	
	if($modulephase==1)
		$modulephasename="No Phase";
	else if($modulephase==2)
		$modulephasename="Phase 2";
	else if($modulephase==3)
		$modulephasename="Phase 3";
		
	$msg = "Edit ".$mathmodulename;		
	$cancelclick="fn_cancel('library-mathmodules-actions')";
}		
?>
<!--Script for the Tag Well-->
<script language="javascript" type="text/javascript" charset="utf-8">		
	$(document).ready(function() {
		$('.multicheck').click(function(e) {  
			var cnt = 0;
			$(this).toggleClass("dragWellmod"); 
			$(this).toggleClass("checkedokmod");
			$("div[id^='chk_']").each(function() {
			if($(this).hasClass('checkedokmod')) {
					cnt++;				
				}
			});
			if(cnt==4)
			{
				var iplids = [];
				$("div[id^='chk_']").each(function() {
					if($(this).hasClass('checkedokmod')) {
						iplids.push($(this).attr('id').replace('chk_',''));				
					}
				});
				$('#iplday1').val(iplids);
				$('#testrailvisible8').removeClass('dim');
				fn_showiplday2(iplids,<?php echo $iplday2; ?>)
			}
			else if(cnt>4)
			{
				$(this).toggleClass("checkedokmod");
				$(this).toggleClass("dragWellmod");
				showloadingalert("Select Only Four IPLs");	
				setTimeout('closeloadingalert()',1000);
				return false;
			}
			else
			{
				$('#testrailvisible8').addClass('dim');
			}
			return false;
		});
		
		$('.multicheck1').click(function(e) {  
			var cnt = 0;
			$(this).toggleClass("dragWellmod"); 
			$(this).toggleClass("checkedokmod");
			$("div[id^='chk1_']").each(function() {
			if($(this).hasClass('checkedokmod')) {
					cnt++;				
				}
			});
			
			if(cnt==4)
			{
				var iplids1 = [];
				$("div[id^='chk1_']").each(function() {
					if($(this).hasClass('checkedokmod')) {
						iplids1.push($(this).attr('id').replace('chk1_',''));				
					}
				});
				$('#iplday2').val(iplids1);
			}
			else if(cnt>4)
			{
				$(this).toggleClass("checkedokmod");
				$(this).toggleClass("dragWellmod"); 
				showloadingalert("Select Only Four IPLs");	
				setTimeout('closeloadingalert()',1000);
				return false;
			}
			return false;
		});
	});


	$(function(){				
		var t4 = new $.TextboxList('#form_tags_mathmod', 
		{
			unique: true, plugins: {autocomplete: {}},
			bitsOptions:{editable:{addKeys: [188]}}	});
		<?php 
			if($qrytag->num_rows > 0) {
				while($restag = $qrytag->fetch_assoc()){
					extract($restag);
		?>
				t4.add('<?php echo $tagname; ?>','<?php echo $tagid; ?>');				
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
				elements : "mathmoduledescription",
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

<section data-type='2home' id='library-mathmodules-newmathmodule'>
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
                <form name="mathmoduleforms" id="mathmoduleforms">
                    <!--Math Module Name-->
                    <div class='row'>
                    	<div class='six columns'>
                        New Math Module Name<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                <input placeholder='New Math Module Name' type='text' tabindex="1" id="txtmathmodname" name="txtmathmodname" value="<?php echo $mathmodulename ;?>" onBlur="$(this).valid();"/>
                                </dt>
                            </dl>
                    
                    
                    <!--Module and Phase Dropdown-->
                    
                        Select Module<span class="fldreq">*</span>
                            <dl class='field row'>
                            	<dt class="dropdown">
                                    <div class="selectbox">
                                        <input type="hidden" name="selectmodule" id="selectmodule" value="<?php echo $moduleid;?>" onchange="$(this).valid();">
                                        <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#" tabindex="2">
                                            <span class="selectbox-option input-medium" data-option="<?php echo $moduleid;?>"><?php echo $modulename; ?></span>
                                            <b class="caret1"></b>
                                        </a>
                                        <div class="selectbox-options">
                                            <input type="text" class="selectbox-filter" placeholder="Search Module" value="">
                                            <ul role="options">
                                                <?php 
                                                $qrymodule = $ObjDB->QueryObject("SELECT CONCAT(a.fld_module_name,' ',(SELECT fld_version FROM         	                                                             itc_module_version_track WHERE fld_mod_id=a.fld_id AND fld_delstatus='0')) AS modulename, a.fld_id                                                              AS moduleid 
												        FROM itc_module_master AS a 
														WHERE a.fld_delstatus='0' ORDER BY a.fld_module_name");
														
                                                while($resmodule=$qrymodule->fetch_assoc()){ extract($resmodule);?>
                                                    <li><a tabindex="-1" href="#" data-option="<?php echo $moduleid;?>" ><?php echo $modulename; ?></a></li>
                                                <?php 
                                                }
                                                ?>       
                                            </ul>
                                        </div>
                                    </div>
                                </dt>
                            </dl>
                        
                        
                        Select phase<span class="fldreq">*</span>  
                            <dl class='field row'>
                                <div class="selectbox">
                                    <input type="hidden" name="selectphase" id="selectphase" value="<?php echo $modulephase ;?>">
                                    <a href="#" class="selectbox-toggle" role="button" data-toggle="selectbox" tabindex="3">
                                        <span class="selectbox-option input-medium" data-option="<?php echo $modulephase ;?>"><?php echo $modulephasename ;?></span>
                                        <b class="caret1"></b>
                                    </a>
                                    <div class="selectbox-options" >			    
                                        <ul role="options" >
                                        	<li><a tabindex="-1" href="#" data-option="1">No Phase</a></li>
                                            <li><a tabindex="-1" href="#" data-option="2">Phase 2</a></li>
                                            <li><a tabindex="-1" href="#" data-option="3">Phase 3</a></li>
                                        </ul>
                                    </div>
                                </div> 
                            </dl>
                        </div>
                            <div class='six columns'> <!-- Textarea - Lesson Description -->
                         Description
                             <dl class='field row'>
                                <dt class='textarea'>
                                    <textarea placeholder='Tell us about your new math module' id="mathmoduledescription" name="mathmoduledescription" style="height:315px; width:100%; border-color:#FFF; resize:none;"
><?php echo htmlentities($mathmoduledescr); ?></textarea>
                                </dt>                                
                             </dl>
                                
                    </div>
                    
                    </div>
                    
                    <!--Day 1 & Day 2 Sessions Dropdown-->
                    <div class='row rowspacer'>
                        <div class='six columns'>
                         Select Day 1 session<span class="fldreq">*</span>  
                            <dl class='field row'>
                            	<dt class="dropdown">
                                    <div class="selectbox">
                                        <input type="hidden" name="sessday1" id="sessday1" value="<?php echo $sessday1 ;?>" onchange="$(this).valid();">
                                        <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#" tabindex="4">
                                            <span class="selectbox-option input-medium" data-option="<?php echo $sessday1 ;?>"><?php echo $sessday1name ;?></span>
                                            <b class="caret1"></b>
                                        </a>
                                        <div class="selectbox-options" >			    
                                            <ul role="options" >
                                                <?php for($i=1;$i<8;$i++) {?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $i; ?>" onclick="fn_showsessday2(<?php echo $i;?>)">Session <?php echo $i; ?></a></li>
                                                <?php }?>
                                            </ul>
                                        </div>
                                    </div> 
                                </dt>
                            </dl>
                        </div>
                        <div class='six columns'>   
                         Select Day 2 session<span class="fldreq">*</span>  
                            <dl class='field row' id='sessid'>
                            	<dt class="dropdown">
                                    <div class="selectbox"  id="dsessday2">
                                        <input type="hidden" name="sessday2" id="sessday2" value="<?php echo $sessday2 ;?>" onchange="$(this).valid();">
                                        <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#" tabindex="5">
                                            <span class="selectbox-option input-medium" data-option="<?php echo $sessday2 ;?>"><?php echo $sessday2name ;?></span>
                                            <b class="caret1"></b>
                                        </a>
                                        <?php if($sessday2!='') {?>
                                        <div class="selectbox-options" >			    
                                            <ul role="options" >
                                                <?php for($i=$sessday2;$i<8;$i++) {?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $i; ?>">Session <?php echo $i; ?></a></li>
                                                <?php }?>
                                            </ul>
                                        </div>
                                        <?php }?>
                                    </div> 
                                </dt>
                            </dl>
                        </div>
                    </div>
                    
                    <script language="javascript" type="text/javascript">
						$(function(){
							$('#testrailvisible7').slimscroll({
								width: '415px',
								height:'333px',
								size: '10px',
                                                                alwaysVisible: true,
                                                                wheelstep: 1,
								railVisible: true,
								allowPageScroll: false,
								railColor: '#F4F4F4',
								opacity: 1,
								color: '#d9d9d9',
							});
							
							$('#testrailvisible8').slimscroll({
								width: '415px',
								height:'333px',
								size: '10px',
                                                                alwaysVisible: true,
                                                                wheelstep: 1,
								railVisible: true,
								allowPageScroll: false,
								railColor: '#F4F4F4',
								opacity: 1,
								color: '#d9d9d9',
							});							
							
						});
					</script>

                    <div class='row rowspacer'>
                        <div class='six columns'>
                            <div class="dragndropcol">
                                <div class="dragtitle">Select IPLS for Day 1<span class="fldreq">*</span></div>
                                <div class="dragWell" id="testrailvisible7" >
                                    <div id="list7" class="dragleftinner droptrue">
                                        <?php 
										$qrylessons=$ObjDB->QueryObject("SELECT CONCAT(a.fld_ipl_name,' ',b.fld_version) AS iplname, a.fld_id AS iplid
										            FROM itc_ipl_master AS a 
											            LEFT JOIN itc_ipl_version_track AS b ON b.fld_ipl_id=a.fld_id
										            WHERE a.fld_delstatus='0' AND a.fld_lesson_type='1' AND b.fld_delstatus='0' AND b.fld_zip_type='1' ORDER BY a.fld_ipl_name");
											   
										if($qrylessons->num_rows > 0){
											$sessiplid = array();
											$sessiplname = array();
											while($reslesson=$qrylessons->fetch_assoc()){
												extract($reslesson);
												$sessiplid[] = $iplid;
												$sessiplname[$iplid] = $iplname;
											}
										}
										$ipl1 = explode(",",$iplday1);
										
										$orderedipl = sortArrayByArray($ipl1,$sessiplid);
										for($w=0;$w<sizeof($orderedipl);$w++)
										{
											$count=0;
											for($i=0;$i<sizeof($ipl1);$i++) { if($ipl1[$i] == $orderedipl[$w]) { $count = 1; } }
											?>
											<div class="multicheck <?php if($count==1){?>checkedokmod<?php } else {?>dragWellmod<?php }?>" id="chk_<?php echo $orderedipl[$w];?>">
												<div class="dragItemLable" id="<?php echo $orderedipl[$w]; ?>"><?php echo $sessiplname[$orderedipl[$w]]; ?></div>
											</div> 
											<?php                               
										}
                                        ?>    
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class='six columns'>
                            <div class="dragndropcol">
                                <div class="dragtitle" id="diplday2">Select IPLS for Day 2<span class="fldreq">*</span></div>
                                <div class="dragWell <?php if($iplday2==0) {?>dim<?php }?>" id="testrailvisible8" >
                                    <div id="list8" class="dragleftinner droptrue">
                                        <?php 
                                            $qrylessons=$ObjDB->QueryObject("SELECT CONCAT(a.fld_ipl_name,' ',b.fld_version) AS iplname, a.fld_id AS iplid FROM itc_ipl_master AS a 
										                   LEFT JOIN itc_ipl_version_track AS b ON b.fld_ipl_id=a.fld_id
										WHERE a.fld_delstatus='0' AND a.fld_lesson_type='1' AND b.fld_delstatus='0' AND b.fld_zip_type='1' AND a.fld_id NOT IN (".$iplday1.") ORDER BY a.fld_ipl_name");
											 
										if($qrylessons->num_rows > 0){
											$sessiplid = array();
											$sessiplname = array();
											while($reslesson=$qrylessons->fetch_assoc()){
												extract($reslesson);
												$sessiplid[] = $iplid;
												$sessiplname[$iplid] = $iplname;
											}
										}
										$ipl1 = explode(",",$iplday2);
										
										$orderedipl = sortArrayByArray($ipl1,$sessiplid);
										$count=1;
										for($w=0;$w<sizeof($orderedipl);$w++)
										{
											?>
											<div class="multicheck1 <?php if($count<=4 and $mathmoduleid!=0){?>checkedokmod<?php } else {?>dragWellmod<?php }?>" id="chk1_<?php echo $orderedipl[$w];?>">
												<div class="dragItemLable" id="<?php echo $orderedipl[$w]; ?>"><?php echo $sessiplname[$orderedipl[$w]]; ?></div>
											</div> 
											<?php 
											$count++;                              
										}
                                        ?>    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Day 1 & Day 2 IPLs Dropdown-->
                    
                    <input type="hidden" name="iplday1" id="iplday1" value="<?php echo $iplday1 ;?>">
                    <input type="hidden" name="iplday2" id="iplday2" value="<?php echo $iplday2 ;?>">
                    <!--Minutes and Days Textboxes-->
                    <div class='row rowspacer'>
                    	<div class='six columns'>
                         Minutes<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='Minutes' maxlength='3' type='text' tabindex="8" id="txtmodminutes" name="txtmodminutes" value="<?php echo $moduleminutes ;?>" onBlur="$(this).valid();" />
                                </dt>
                            </dl>
                        </div>
                        <div class='six columns'>
                        Days<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='Days' maxlength='2' type='text' tabindex="9" id="txtmoddays" name="txtmoddays" value="<?php echo $moduledays ;?>" onBlur="$(this).valid();" />
                                </dt>
                            </dl>
                        </div>
                    </div>
                    
                    <!-- Create a new License -->
                    
                     <script type="text/javascript" language="javascript">
								<?php if($moduleid!=0){ ?>								
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
                                                                                                WHERE a.fld_id NOT IN(SELECT fld_license_id FROM itc_license_mod_mapping WHERE fld_module_id='".$mathmoduleid."'
                                                                                                AND fld_active='1' AND fld_type='2')
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
                                        <div class="dragtitle">License with this Math Module </div>
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
                                                                                            WHERE a.fld_id IN(SELECT fld_license_id FROM itc_license_mod_mapping WHERE fld_module_id='$mathmoduleid'
                                                                                            AND fld_active='1' AND fld_type='2')
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
                        To create new tag, type name and press "Enter" key
                        <div class="tag_well">
                            <input type="text" name="form_tags_mathmod" value="" id="form_tags_mathmod" />
                        </div>	
                    </div>
                    
                    <!--Cancel and Create Buttons-->
                    <div class='row'>
                        <div class='five columns btn primary push_one noYes'>
                            <a onclick="<?php echo $cancelclick;?>"><?php echo $cancelbtn;?></a>
                        </div>
                        <div class='five columns btn secondary yesNo'>
                            <a onclick="fn_createmathmodule(<?php echo $mathmoduleid;?>)"><?php echo $createbtn;?></a>
                        </div>
                    </div>
                </form>
                
                <!--Script to Validate the Moduleform & Numbers for Textbox-->
                <script type="text/javascript" language="javascript">
					//Function to enter only numbers in textbox
                    $("#txtmodminutes,#txtmoddays").keypress(function (e) {
						if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
							return false;
						}
					});
                    
                    //Function to validate the form
                    $(function(){
                        $("#mathmoduleforms").validate({
                            ignore: "",
                            errorElement: "dd",
							errorPlacement: function(error, element) {
								$(element).parents('dl').addClass('error');
								error.appendTo($(element).parents('dl'));
								error.addClass('msg');
								window.scroll(0,($('dd').offset().top)-50);
							},
                            rules: { 
                                txtmathmodname: { required: true, lettersonly: true, 
								remote:
								{ 
									url: "library/mathmodules/library-mathmodules-ajax.php",
									type:"POST",  
									data: {  
											mid: function() {
											return '<?php echo $mathmoduleid;?>';},
											oper: function() {
											return 'checkmodulename';}
													  
										},	
										 async:false } 
                                },
								selectmodule: { required: true },
                                sessday1: { required: true },
								sessday2: { required: true },
                                txtmodminutes:{ required: true },
                                txtmoddays:{ required: true }
                            }, 
							
                            messages: { 
                                txtmathmodname: { required: "Please Type Math Module Name", remote: "Math Module Name Already Exists" },
								selectmodule: { required: "Please Select Module" },
                                sessday1: { required: "Please Select Session For Day1" },
								sessday2: { required: "Please Select Session For Day2" },
                                txtmodminutes:{ required: "Please Type Module Minutes" },
                                txtmoddays:{ required: "Please Type Module Days" }
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
                </script>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");