<?php
/*
	Page - licenses-newlicense
	Description:
	Show the license form from licenses.php
	
	Actions Performed:
	
	
	History:
*/

	@include("sessioncheck.php");		
    
	$licenseid = (isset($method['id'])) ? $method['id'] : 0;
	$licensetypename="normal"; 
	$durationtypename="month";
	$newlicense="New License";	
	$createbtn="Create License";
	$cancelbtn = "Cancel";
	$licensename = '';
	$duration = '';
	$amount = '';
        $sales='';
	$durationtype = 1;
        $contenttype=1;
	$licenseholders = 0;
	//If license exists
	if($licenseid != 0)
	{
		$licenseholders = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_license_id) FROM itc_license_track WHERE fld_license_id='".$licenseid."' AND fld_delstatus='0'");
		//Get existing license details	
		$licenseqry = $ObjDB->QueryObject("SELECT a.fld_content_type as contenttype,a.fld_license_name AS licensename,a.fld_license_type AS licensetype,a.fld_duration_type AS durationtype, 
												a.fld_duration AS duration,(CASE WHEN a.fld_license_type = 1 THEN 'normal' WHEN a.fld_license_type = 2 
												THEN 'trial' END) AS licensetypename,(CASE WHEN (a.fld_duration_type = 1 AND a.fld_duration>1) THEN 'Months' 
												WHEN (a.fld_duration_type = 1 AND a.fld_duration=1) THEN 'Month' WHEN (a.fld_duration_type = 2 
												AND a.fld_duration>1) THEN 'Years' WHEN (a.fld_duration_type = 2 AND a.fld_duration=1) THEN 'Year' END) 
												AS durationtypename, a.fld_amount AS amount,a.fld_status AS status,a.fld_salesorder AS sales
										 FROM itc_license_master AS a WHERE a.fld_id='".$licenseid."'");
    	
		$newlicense="Update License";
		$createbtn="Update License";
		$cancelbtn = "Delete this License";
		$rowlicenseqry=$licenseqry->fetch_assoc();
		extract($rowlicenseqry);		
	}
?>
<script type="text/javascript" charset="utf-8">		
	$.getScript("licenses/newlicense/licenses-newlicense.js");	
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_license', 
		{
			plugins: {autocomplete: {}},
			bitsOptions:{editable:{addKeys: [188]}}	});
		<?php 
			//This part is used to fill the tag name which is using for this particular license
			$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname 
									FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
									WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='18' AND b.fld_access='1' AND a.fld_created_by='".$uid."' 
									AND a.fld_delstatus='0' AND b.fld_item_id='".$licenseid."'");
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
<section data-type='2home' id='licenses-newlicense'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle"><?php echo $newlicense;?></p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
		<script type="text/javascript">
            $(function(){
                
				$('div[id^="testrailvisible"]').each(function(index, element) {
					$(this).slimscroll({ /*------- Scroll for Modules Left Box ------*/
						width: '410px',
						height:'366px',
						railVisible: true,
						allowPageScroll: false,
						railColor: '#F4F4F4',
						opacity: 1,
						color: '#d9d9d9',
                                                size: '7px',
                                                alwaysVisible: true,
                                                wheelstep: 1
					});
				});
            
                $("#list3").sortable({ /*------- Modules Left Box ------*/
                    connectWith: ".droptrue",
                    dropOnEmpty: true,
					items: "div[class='draglinkleft']",
                    receive: function(event, ui) {                        
                        $("div[class='draglinkright']").each(function(){ 
                            if($(this).parent().attr('id')=='list3'){
                                fn_movealllistitems('list3','list4',1,$(this).children(":first").attr('id'));
                            }
                        });
                    }
                });
            
                $("#list4").sortable({ /*------- Modules Right Box ------*/
                    connectWith: ".droptrue",
                    dropOnEmpty: true,
                    receive: function(event, ui) {
                        $("div[class='draglinkleft']").each(function(){ 
                            if($(this).parent().attr('id')=='list4'){
                                fn_movealllistitems('list3','list4',1,$(this).children(":first").attr('id'));
                            }
                        });
                    }
                });
            
                $("#list5").sortable({  /*------- Units Left Box ------*/
                    connectWith: ".droptrue",
                    dropOnEmpty: true,
					items: "div[class='draglinkleft']",
                    receive: function(event, ui) {                        
                        $("div[class='draglinkright']").each(function(){ 
                            if($(this).parent().attr('id')=='list5'){
                                fn_movealllistitems('list5','list6',1,$(this).children(":first").attr('id'));
                            }
                        });
                    }
                });			
				
                $( "#list6" ).sortable({ /*------- Units Right Box ------*/
                    connectWith: ".droptrue",
                    dropOnEmpty: true,
                    receive: function(event, ui) {
                        $("div[class='draglinkleft']").each(function(){ 
                            if($(this).parent().attr('id')=='list6'){
                                fn_movealllistitems('list5','list6',1,$(this).children(":first").attr('id'));
                            }
                        });
                    }
                });            
            
                $("#list9").sortable({ /*------- Assessment left Box ------*/
                    connectWith: ".droptrue",
                    dropOnEmpty: true,
					items: "div[class='draglinkleft']",
                    receive: function(event, ui) {                        
                        $("div[class='draglinkright']").each(function(){ 
                            if($(this).parent().attr('id')=='list9'){
                                fn_movealllistitems('list9','list10',1,$(this).children(":first").attr('id'));
                            }
                        });
                    }
                });
            
                $( "#list10" ).sortable({ /*------- Assessment right Box ------*/
                    connectWith: ".droptrue",
                    dropOnEmpty: true,
                    receive: function(event, ui) {
                        $("div[class='draglinkleft']").each(function(){ 
                            if($(this).parent().attr('id')=='list10'){
                                fn_movealllistitems('list9','list10',1,$(this).children(":first").attr('id'));
                            }
                        });
                    }
                });
				$("#list11").sortable({ /*------- Quest left Box ------*/
                    connectWith: ".droptrue",
                    dropOnEmpty: true,
					items: "div[class='draglinkleft']",
                    receive: function(event, ui) {                        
                        $("div[class='draglinkright']").each(function(){ 
                            if($(this).parent().attr('id')=='list11'){
                                fn_movealllistitems('list11','list12',1,$(this).children(":first").attr('id'));
                            }
                        });
                    }
                });
            
                $("#list12" ).sortable({ /*------- Quest right Box ------*/
                    connectWith: ".droptrue",
                    dropOnEmpty: true,
                    receive: function(event, ui) {
                        $("div[class='draglinkleft']").each(function(){ 
                            if($(this).parent().attr('id')=='list12'){
                                fn_movealllistitems('list11','list12',1,$(this).children(":first").attr('id'));
                            }
                        });
                    }
                });
            
                //$("#list9, #list10").disableSelection();
				
				$("#list13").sortable({ /*------- expedition left Box ------*/
                    connectWith: ".droptrue",
                    dropOnEmpty: true,
					items: "div[class='draglinkleft']",
                    receive: function(event, ui) {                        
                        $("div[class='draglinkright']").each(function(){ 
                            if($(this).parent().attr('id')=='list13'){
                                fn_movealllistitems('list13','list14',1,$(this).children(":first").attr('id'));
                            }
                        });
                    }
                });
            
                $("#list14" ).sortable({ /*------- expedition right Box ------*/
                    connectWith: ".droptrue",
                    dropOnEmpty: true,
                    receive: function(event, ui) {
                        $("div[class='draglinkleft']").each(function(){ 
                            if($(this).parent().attr('id')=='list14'){
                                fn_movealllistitems('list13','list14',1,$(this).children(":first").attr('id'));
                            }
                        });
                    }
                });
                
                //pd
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
                //pd
                
                //SOS
                $("#list17").sortable({
                connectWith: ".droptrue1",
                dropOnEmpty: true,
                items: "div[class='draglinkleft']",
                receive: function(event, ui) { 
                        $("div[class=draglinkright]").each(function(){ 
                                if($(this).parent().attr('id')=='list17'){
                                        fn_movealllistitems('list17','list18',$(this).children(":first").attr('id'));
                                }
                    });
                    }
                });
                
                $( "#list18" ).sortable({
                connectWith: ".droptrue1",
                dropOnEmpty: true,
                receive: function(event, ui) { 
                        $("div[class=draglinkleft]").each(function(){ 
                                if($(this).parent().attr('id')=='list18'){
                                        fn_movealllistitems('list18','list17',$(this).children(":first").attr('id'));
                                }
            });
                        }
                    });
                //SOS
                
                
                //Mission
                $("#list25").sortable({
                connectWith: ".droptrue1",
                dropOnEmpty: true,
                items: "div[class='draglinkleft']",
                receive: function(event, ui) { 
                        $("div[class=draglinkright]").each(function(){ 
                                if($(this).parent().attr('id')=='list25'){
                                        fn_movealllistitems('list25','list26',$(this).children(":first").attr('id'));
                                }
            });
                    }
                });
                
                $( "#list26" ).sortable({
                connectWith: ".droptrue1",
                dropOnEmpty: true,
                receive: function(event, ui) { 
                        $("div[class=draglinkleft]").each(function(){ 
                                if($(this).parent().attr('id')=='list26'){
                                        fn_movealllistitems('list26','list25',$(this).children(":first").attr('id'));
                                }
            });
                        }
                    });
                //Mission
                
                
                //Nondigital
                $("#list31").sortable({
                connectWith: ".droptrue1",
                dropOnEmpty: true,
                items: "div[class='draglinkleft']",
                receive: function(event, ui) { 
                        $("div[class=draglinkright]").each(function(){ 
                                if($(this).parent().attr('id')=='list31'){
                                        fn_movealllistitems('list31','list32',$(this).children(":first").attr('id'));
                                }
            });
                    }
                });
                
                $( "#list32" ).sortable({
                connectWith: ".droptrue1",
                dropOnEmpty: true,
                receive: function(event, ui) { 
                        $("div[class=draglinkleft]").each(function(){ 
                                if($(this).parent().attr('id')=='list32'){
                                        fn_movealllistitems('list32','list31',$(this).children(":first").attr('id'));
                                }
            });
                        }
                    });
                //Nondigital
                
                
            });
			<?php  
			if($licenseid!=0 AND $contenttype==1){?>
				fn_load_lessons(<?php echo $licenseid; ?>);
				fn_load_pdlessons(<?php echo $licenseid; ?>);
				setTimeout("fn_load_product(<?php echo $licenseid; ?>,'edit')",2000); //sim product
                               
		  <?php }?>    	
                      
                      <?php  
			if($licenseid!=0 AND $contenttype==2){?>
				fn_load_phases(<?php echo $licenseid; ?>);
                                setTimeout('fn_load_video(<?php echo $licenseid; ?>);',1000);
                               
		  <?php }?>    
                      
                    $("input[name='types']").click(function() {  
                    var test = $(this).val();
                    if(test=="itccontent")
                    {
                        $('#contenttype').val(1);
                    }
                    else
                    {
                        $('#contenttype').val(2);
                    }
                    $("div.sdesc").hide();
                    $("#"+test).show(); 
                });
                
                <?php
                    if($contenttype==2)
                    {
                    ?>
                       $('#itccontent').hide();
                       $('#soscontent').show();
                    <?php
                    }
                    ?>
        </script>
        <div class='row formBase'>        	
            <div class='eleven columns centered insideForm'>
            	<form id="createlicense" name="createlicense">
                	<div class='row'> <?php // License name and License Type drop down ?>
                    	<div class='six columns'>
                        License Name<span class="fldreq">*</span> 
                        	<dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='License Name' id="licennsename" name="licennsename" type='text' value="<?php echo $licensename;?>" onblur="$(this).valid();"/>
                                </dt>                                            
                            </dl>
                        </div>
                        <div class='six columns'>
                        Select License Type<span class="fldreq">*</span> 
                        	<dl class='field row'>   
                                <dt class='dropdown'>
                                    <div class="selectbox" id="selecttype">                            
                                        <input type="hidden" name="hidlicensetype" id="hidlicensetype" value="1" onchange="$(this).valid();" />
                                        <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#" >
                                            <span class="selectbox-option input-medium" data-option="<?php echo $licensetype; ?>"><?php echo $licensetypename; ?></span>
                                            <b class="caret1"></b>
                                        </a>
                                        <div class="selectbox-options" style="min-width: 118px;padding-left:20px;">			    
                                            <ul role="options">
                                                <li><a tabindex="-1" href="#" data-option="1">normal</a></li>
                                                <li><a tabindex="-1" href="#" data-option="2">trial</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </dt>                                           
                            </dl>
                        </div>
                    </div>
                    
                    <div class='row rowspacer'>  <?php // License duration and License duration type drop down (month / year) ?>
                    	<div class='six columns'>
                        Duration<span class="fldreq">*</span> 
                        	<dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='Duration'  id="duration" name="duration" type='text' maxlength="3" value="<?php echo $duration;?>" onkeyup="ChkValidChar(this.id);" onblur="$(this).valid();" />
                                </dt>                                            
                            </dl>
                        </div>
                        <div class='six columns'>
                        Select Month/Year<span class="fldreq">*</span> 
                        	<dl class='field row'>   
                                <dt class='dropdown'>
                                    <div class="selectbox">
                                        <input type="hidden" name="hidmonth" id="hidmonth" value="<?php echo $durationtype;?>">
                                        <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#" >
                                            <span class="selectbox-option input-medium" data-option="<?php echo $durationtype;?>"><?php echo $durationtypename; ?></span>
                                            <b class="caret1"></b>
                                        </a>
                                        <div class="selectbox-options" style="min-width:118px;padding-left:20px;">			    
                                            <ul role="options">
                                                <li><a tabindex="-1" href="#" data-option="1">month</a></li>
                                                <li><a tabindex="-1" href="#" data-option="2">year</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </dt>                                           
                            </dl>
                        </div>
                    </div>
                    
                    <div class='row rowspacer'> <?php // Amount for a user using the content ?> 
                    	<div class='six columns'>
                        Amount<span class="fldreq"></span> 
                        	<dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='amount'  id="amount" name="amount" type='text'  value="<?php if($amount>0){echo $amount;}?>" onblur="$(this).valid();" />
                                </dt> 
                            </dl>
                        </div>
                        <span class="fldreq"></span>
                        <div class='six columns'>$ per user</div>
                    </div>
                     
                    <div class='row rowspacer'> <?php // Amount for a user using the content ?> 
                    	<div class='six columns'>
                        Sales Order<span class="fldreq">*</span>
                        	<dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='Sales Order'  id="sales" name="sales" type='text'  value="<?php echo $sales;?>" />
                                </dt> 
                            </dl>
                        </div>
                    </div>
                    
                    <div class='row rowspacer'> <!-- Tag Well -->
                    	<div class='twelve columns'>
                        	To create new tag, type a name and press Enter.
                            <div class="tag_well">
                                <input type="text" name="form_tags_license" value="" id="form_tags_license" />
                            </div>
                        </div>
                    </div>                    
                    
                    
  
                    <div class="row rowspacer <?php if($licenseid!=0){ echo "dim"; } ?>">
                        <div class='twelve columns'>
                            
                            <input type="radio" id="itc" name="types" <?php if($licenseid==0 OR $contenttype==1){?> checked="checked" <?php } ?> value="itccontent" />ITC Content
                            <input type="radio" id="sos" name="types" <?php if($contenttype==2){?> checked="checked" <?php } ?>  value="soscontent" />SOS Content
                            <input type="hidden" id="contenttype" name="contenttype" value="<?php echo $contenttype;?>">
                        </div>
                   </div> 
                    
                    <div id="itccontent" class="sdesc">
                    <div class='row rowspacer' id="units"> <!-- Units-->
                        <div class='six columns'>
                            <div class="dragndropcol">
                                <?php
                                        					//get unit names from unit master. If the license is new one get all the units, otherwise get the units except particular license units	
                                        	 $qryunit=$ObjDB->QueryObject("SELECT a.`fld_id` AS unitid, a.`fld_unit_name` AS unitname, ISNULL(b.fld_unit_id) AS chkunit
																			FROM itc_unit_master a
																			LEFT JOIN itc_ipl_master b ON a.`fld_id`=b.`fld_unit_id`
																			WHERE a.fld_delstatus='0' AND a.fld_id 
																			NOT IN (SELECT fld_unit_id FROM itc_license_unit_mapping 
																						WHERE fld_license_id='".$licenseid."' AND fld_access='1') 
																			GROUP BY a.`fld_id`
																			ORDER BY a.fld_unit_name");
                                ?>
                                <div class="dragtitle">Units available (<span id="leftunits"><?php echo $qryunit->num_rows;?></span>)</div>
                                <div class="draglinkleftSearch" id="s_list5" >

                                   <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Search' type='text' id="list_5_search" name="list_5_search" onKeyUp="search_list(this,'#list5');" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="dragWell" id="testrailvisible5" >
                                    <div id="list5" class="dragleftinner droptrue">
                                        <?php 
						
                                            if($qryunit->num_rows > 0){
                                                while($resunit=$qryunit->fetch_assoc()){
                                                    extract($resunit);
                                                    if($chkunit == 0){ //the unit have lessons
                                                ?>
                                                    <div class="draglinkleft" id="list5_<?php echo $unitid; ?>" >
                                                        <div class="dragItemLable" id="<?php echo $unitid; ?>"><?php echo $unitname; ?></div>
                                                        <div class="clickable" id="clck_<?php echo $unitid; ?>" onclick="fn_movealllistitems('list5','list6',1,<?php echo $unitid; ?>);"></div>
                                                    </div> 
                                                <?php
                                                    }
                                                    else{ // the unit dont have any lessons ?>
                                                        <div class="draglinkleft dim" >
                                                            <div class="dragItemLable"><?php echo $unitname; ?></div>
                                                        </div>
                                                        <?php 
                                                    }
                                                }
                                            }
                                        ?>    
                                    </div>
                                </div>
                                <div class="dragAllLink"  onclick="fn_movealllistitems('list5','list6',0,0);" style="cursor: pointer;cursor:hand;width:  95px;float: right; ">Add all Units.</div>
                            </div>
                        </div>
                        <div class='six columns'>
                            <div class="dragndropcol">
                                        <?php 
										//get the units which is mapping with the license
                                        $qryunitselect=$ObjDB->QueryObject("SELECT a.fld_id AS unitid, a.fld_unit_name AS unitname 
																			FROM itc_unit_master AS a LEFT JOIN itc_license_unit_mapping AS b ON a.fld_id=b.fld_unit_id
																			WHERE a.fld_delstatus='0' AND b.fld_license_id='".$licenseid."' AND b.fld_access='1' 
																			GROUP BY a.fld_id 
																			ORDER BY a.fld_unit_name");
                                                      //below this change line
                                        $qryunitunselect=$ObjDB->QueryObject("SELECT a.fld_unit_id as selectunitid FROM itc_class_sigmath_unit_mapping as a
                                                                                LEFT JOIN itc_class_sigmath_master AS b ON b.fld_id=a.fld_sigmath_id
                                                                                where a.fld_license_id='".$licenseid."' AND b.fld_delstatus='0'");
                                        $filter_greyout=array(); 
                                            while($unitunselect=$qryunitunselect->fetch_assoc()){
                                                extract($unitunselect);
                                                array_push($filter_greyout,$selectunitid);
                                            }      
                                ?>
                                <div class="dragtitle">Units in your license (<span id="rightunits"><?php echo $qryunitselect->num_rows;?></span>)</div>
                                <div class="draglinkleftSearch" id="s_list6" >
                                   <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Search' type='text' id="list_6_search" name="list_6_search" onKeyUp="search_list(this,'#list6');" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="dragWell" id="testrailvisible6">
                                    <div id="list6" class="dragleftinner droptrue">                                    	
                                        <?php 
                                              
                                              
                                            if($qryunitselect->num_rows > 0){
                                                while($resassignedunit=$qryunitselect->fetch_assoc()){
                                                    extract($resassignedunit);
                                                $dimunit = array_diff(array($unitid),$filter_greyout);
                                                    ?>
                                                        <div class="draglinkright<?php if(empty($dimunit)) { echo ' dim'; }?>" id="list6_<?php echo $unitid; ?>">
                                                            <div class="dragItemLable" id="<?php echo $unitid; ?>"><?php echo $unitname; ?></div>
                                                            <div class="clickable" id="clck_<?php echo $unitid; ?>" onclick="fn_movealllistitems('list5','list6',1,<?php echo $unitid; ?>);"></div>
                                                        </div>
                                                    <?php 
                                                }
                                            }
                                        ?>	
                                    </div>
                                </div>
                                <div class="dragAllLink" onclick="fn_movealllistitems('list6','list5',0,0);" style="cursor: pointer;cursor:hand;width:  126px;float: right; ">Remove all Units.</div>
                            </div>
                        </div>
                    </div>
                    <div class='row rowspacer' id="Ipls"> <!-- Shows Ipls list to select-->
                    </div>
                    <div class='row rowspacer'> <!-- Shows Modules list to select-->
                        <div class='six columns'>
                            <div class="dragndropcol">
                                        <?php 
											//If the license is new one get all the modules and mathmodules, otherwise get the modules, mathmodules except particular license  
                                        	 $qrymodule= $ObjDB->QueryObject("SELECT w.* FROM ((SELECT a.fld_id AS modid, CONCAT(a.fld_module_name,' ',b.fld_version) AS modname, 
																				fn_shortname(CONCAT(a.fld_module_name,' ',b.fld_version),1) AS shortname ,	
																					'Module' AS mtypename, '1' AS mtype
																				FROM itc_module_master AS a 
																				LEFT JOIN itc_module_version_track AS b ON a.fld_id=b.fld_mod_id
																				WHERE a.fld_id NOT IN(SELECT fld_module_id FROM itc_license_mod_mapping WHERE fld_license_id='".$licenseid."'
																					 AND fld_active='1' AND fld_type='1') AND a.fld_delstatus='0' AND b.fld_delstatus='0' AND 
																					 a.fld_module_type<>'7')
																				UNION 
																				  (SELECT a.fld_id AS modid, CONCAT(a.fld_mathmodule_name,' ',b.fld_version) AS modname, 
																				   fn_shortname(CONCAT(a.fld_mathmodule_name,' ',b.fld_version),1) 
																				   AS shortname , 'Math Module' AS mtypename, '2' AS mtype 
																				   FROM itc_mathmodule_master AS a LEFT JOIN itc_module_version_track AS b ON a.fld_module_id=b.fld_mod_id
																				   WHERE a.fld_id NOT IN(SELECT fld_module_id FROM itc_license_mod_mapping WHERE 
																				   fld_license_id='".$licenseid."' AND fld_active='1' AND fld_type='2') 
																					   AND a.fld_delstatus='0' AND b.fld_delstatus='0')) AS w 
																			  ORDER BY w.mtype, w.modname");
                                               
                                                ?>
                                <div class="dragtitle">Modules available(<span id="leftmoddiv"><?php echo $qrymodule->num_rows;?></span>)</div>
                                <div class="draglinkleftSearch" id="s_list3" >
                                   <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Search' type='text' id="list_3_search" name="list_3_search" onKeyUp="search_list(this,'#list3');" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="dragWell" id="testrailvisible3" >
                                    <div id="list3" class="dragleftinner droptrue">
                                        <?php 
                                            if($qrymodule->num_rows > 0){
                                                while($resmod=$qrymodule->fetch_assoc()){
                                                    extract($resmod);
                                                ?>
                                      
                                                    <div class="draglinkleft" id="list3_<?php echo $modid."_".$mtype; ?>" name="<?php echo $modid; ?>~<?php echo $mtype; ?>">
                                                        <div class="dragItemLable tooltip" id="<?php echo $modid."_".$mtype;?>" title="<?php echo $modname;?>"><?php echo $shortname." / ".$mtypename; ?></div>
                                                        <div class="clickable" id="clck_<?php echo $modid."_".$mtype; ?>" onclick="fn_movealllistitems('list3','list4',1,'<?php echo $modid."_".$mtype; ?>');"></div>
                                                    </div> 
                                                <?php                                                    
                                                }
                                            }
                                        ?>    
                                    </div>
                                </div>
                                <div class="dragAllLink"  onclick="fn_movealllistitems('list3','list4',0,0);" style="cursor: pointer;cursor:hand;width: 120px;float: right; ">Add all Modules.</div>
                            </div>
                        </div>
                        <div class='six columns'>
                            <div class="dragndropcol">
                                        <?php 
						
										//get the modules and mathmodules which is mapping with the license
                                        $qrymoduleselect= $ObjDB->QueryObject("SELECT w.* FROM ((SELECT a.fld_id AS modid, CONCAT(a.fld_module_name,' ',c.fld_version) AS modname, 
																				fn_shortname(CONCAT(a.fld_module_name,' ',c.fld_version),1) AS shortname , 'Module' AS mtypename, 
																				'1' AS mtype FROM itc_module_master AS a LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
																				LEFT JOIN itc_module_version_track AS c ON a.fld_id=c.fld_mod_id
																				WHERE b.fld_license_id='".$licenseid."' AND b.fld_active='1' AND b.fld_type='1' AND c.fld_delstatus='0')	
																			  UNION 
																				(SELECT a.fld_id AS modid, CONCAT(a.fld_mathmodule_name,' ',c.fld_version) AS modname, 
																				fn_shortname(CONCAT(a.fld_mathmodule_name,' ',c.fld_version),1) AS shortname , 
																				'Math Module' AS mtypename, '2' AS mtype 
																				FROM itc_mathmodule_master AS a LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
																				LEFT JOIN itc_module_version_track AS c 
																				ON a.fld_module_id=c.fld_mod_id WHERE b.fld_license_id='".$licenseid."' AND b.fld_active='1' 
																				AND b.fld_type='2' AND c.fld_delstatus='0')) AS w 
																			  ORDER BY w.mtype, w.modname");
                                        $qrymoduleunselect=$ObjDB->QueryObject("SELECT b.fld_module_id as module_id 
                                                                                FROM itc_class_rotation_schedule_mastertemp as a
                                                                                LEFT JOIN itc_class_rotation_schedule_module_mappingtemp AS b 
                                                                                ON a.fld_id = b.fld_schedule_id
                                                                                WHERE  a.fld_license_id = '".$licenseid."' 
                                                                                AND a.fld_delstatus='0' AND b.fld_flag='1'");
                                            $filter_greyout=array(); 
                                            while($moduleunselect=$qrymoduleunselect->fetch_assoc()){
                                            extract($moduleunselect);
                                            array_push($filter_greyout,$module_id);
                                            }  
                                            ?>
                                <div class="dragtitle">Modules in your license(<span id="rightmoddiv"><?php echo $qrymoduleselect->num_rows ;?></span>)</div>
                                <div class="draglinkleftSearch" id="s_list4" >
                                   <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Search' type='text' id="list_4_search" name="list_4_search" onKeyUp="search_list(this,'#list4');" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="dragWell" id="testrailvisible4">
                                    <div id="list4" class="dragleftinner droptrue">
                                       <?php
                                            if($qrymoduleselect->num_rows > 0){
                                                while($resassignedmod=$qrymoduleselect->fetch_assoc()){
                                                    extract($resassignedmod);
                                                      $dimmodule = array_diff(array($modid),$filter_greyout);
                                                    ?>
                                                        <div class="draglinkright<?php  //if(empty($dimmodule)) { echo ' dim'; }?>" id="list4_<?php echo $modid."_".$mtype; ?>" name="<?php echo $modid; ?>~<?php echo $mtype; ?>">
                                                            <div class="dragItemLable tooltip" id="<?php echo $modid."_".$mtype;?>" title="<?php echo $modname;?>"><?php echo $shortname." / ".$mtypename;?></div>
                                                            <div class="clickable" id="clck_<?php echo $modid."_".$mtype; ?>" onclick="fn_movealllistitems('list3','list4',1,'<?php echo $modid."_".$mtype; ?>');"></div>
                                                        </div>
                                                    <?php 
                                                }
                                            }
                                        ?>	
                                    </div>
                                </div>
                                <div class="dragAllLink" onclick="fn_movealllistitems('list4','list3',0,0);" style="cursor: pointer;cursor:hand;width:  153px;float: right; ">Remove all Modules.</div>
                            </div>
                        </div>
                    </div>
                    <div class='row rowspacer'> <!-- Quests-->
                        <div class='six columns'>
                            <div class="dragndropcol">
                                <?php
                                        $qryquest= $ObjDB->QueryObject("SELECT a.fld_id AS modid, CONCAT(a.fld_module_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id=a.fld_id AND fld_delstatus='0')) AS modname, fn_shortname(CONCAT(a.fld_module_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id=a.fld_id AND fld_delstatus='0')),1) AS shortname, 'Quests' AS mtypename, '7' AS mtype FROM itc_module_master AS a WHERE a.fld_id NOT IN(SELECT fld_module_id FROM itc_license_mod_mapping WHERE fld_license_id='".$licenseid."' AND fld_active='1' AND fld_type='7') AND a.fld_module_type='7' AND a.fld_delstatus='0'");
                                ?>
                                <div class="dragtitle">Quests available (<span id="leftquests"><?php echo $qryquest->num_rows ;?></span>)</div>
                                <div class="draglinkleftSearch" id="s_list11" >
                                   <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Search' type='text' id="list_11_search" name="list_11_search" onKeyUp="search_list(this,'#list11');" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="dragWell" id="testrailvisible11" >
                                    <div id="list11" class="dragleftinner droptrue">
                                        <?php 
                                        	 $qryquest= $ObjDB->QueryObject("SELECT a.fld_id AS modid, CONCAT(a.fld_module_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id=a.fld_id AND fld_delstatus='0')) AS modname, fn_shortname(CONCAT(a.fld_module_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id=a.fld_id AND fld_delstatus='0')),1) AS shortname, 'Quests' AS mtypename, '7' AS mtype FROM itc_module_master AS a WHERE a.fld_id NOT IN(SELECT fld_module_id FROM itc_license_mod_mapping WHERE fld_license_id='".$licenseid."' AND fld_active='1' AND fld_type='7') AND a.fld_module_type='7' AND a.fld_delstatus='0'");
                                            if($qryquest->num_rows > 0){
                                                while($resqryquest=$qryquest->fetch_assoc()){
                                                    extract($resqryquest);
                                                ?>
                                                    <div class="draglinkleft" id="list11_<?php echo $modid."_".$mtype; ?>" name="<?php echo $modid; ?>~<?php echo $mtype; ?>">
                                                        <div class="dragItemLable tooltip" id="<?php echo $modid."_".$mtype;?>" title="<?php echo $modname;?>"><?php echo $shortname." / ".$mtypename; ?></div>
                                                        <div class="clickable" id="clck_<?php echo $modid."_".$mtype; ?>" onclick="fn_movealllistitems('list11','list12',1,'<?php echo $modid."_".$mtype; ?>');"></div>
                                                    </div> 
                                                <?php                                                    
                                                }
                                            }
                                        ?>    
                                    </div>
                                </div>
                                <div class="dragAllLink"  onclick="fn_movealllistitems('list11','list12',0,0);" style="cursor: pointer;cursor:hand;width:  109px;float: right; ">Add all Quests.</div>
                            </div>
                        </div>
                        <div class='six columns'>
                            <div class="dragndropcol">
                                <?php
                                    $qryquestselect= $ObjDB->QueryObject("SELECT a.fld_id AS modid, CONCAT(a.fld_module_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id=a.fld_id AND fld_delstatus='0')) AS modname, fn_shortname(CONCAT(a.fld_module_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id=a.fld_id AND fld_delstatus='0')),1) AS shortname, 'Quests' AS mtypename, '7' AS mtype FROM itc_module_master AS a LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id WHERE b.fld_license_id='".$licenseid."' AND b.fld_active='1' AND b.fld_type='7' AND a.fld_module_type='7' AND a.fld_delstatus='0'");
                                ?>
                                <div class="dragtitle">Quests in your license (<span id="rightquests"><?php echo $qryquestselect->num_rows ;?></span>)</div>
                                <div class="draglinkleftSearch" id="s_list12" >
                                   <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Search' type='text' id="list_12_search" name="list_12_search" onKeyUp="search_list(this,'#list12');" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="dragWell" id="testrailvisible12">
                                    <div id="list12" class="dragleftinner droptrue">
                                        <?php 
                                        
                                        
                                        $qryquestunselect=$ObjDB->QueryObject("SELECT fld_module_id FROM itc_class_indassesment_master where fld_license_id='".$licenseid."' and fld_delstatus='0'");
                                            $filter_greyout=array(); 
                                            while($questunselect=$qryquestunselect->fetch_assoc()){
                                            extract($questunselect);
                                            array_push($filter_greyout,$fld_module_id);
                                            }  
                                            
                                            if($qryquestselect->num_rows > 0){
                                                while($resqryquestselect=$qryquestselect->fetch_assoc()){
                                                    extract($resqryquestselect);
                                                    $dimquest = array_diff(array($modid),$filter_greyout);
                                                    ?>
                                                        <div class="draglinkright<?php if(empty($dimquest)) { echo ' dim'; }?>" id="list12_<?php echo $modid."_".$mtype; ?>" name="<?php echo $modid; ?>~<?php echo $mtype; ?>">
                                                            <div class="dragItemLable tooltip" id="<?php echo $modid."_".$mtype;?>" title="<?php echo $modname;?>"><?php echo $shortname." / ".$mtypename;?></div>
                                                            <div class="clickable" id="clck_<?php echo $modid."_".$mtype; ?>" onclick="fn_movealllistitems('list11','list12',1,'<?php echo $modid."_".$mtype; ?>');"></div>
                                                        </div>
                                                    <?php 
                                                }
                                            }
                                        ?>	
                                    </div>
                                </div>
                                <div class="dragAllLink" onclick="fn_movealllistitems('list12','list11',0,0);"  style="cursor: pointer;cursor:hand;width:  140px;float: right; ">Remove all Quests.</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class='row rowspacer'> <!-- Shows Expedition list to select-->
                        <div class='six columns'>
                            <div class="dragndropcol">
                                        <?php
											//get expdetion and destionation 											
                                        	 $qrymodule= $ObjDB->QueryObject("SELECT a.fld_id AS destid, b.fld_id AS expid, 
											 									CONCAT(b.fld_exp_name,' ',(SELECT fld_version FROM itc_exp_version_track 
																				WHERE fld_exp_id=b.fld_id AND fld_delstatus='0')) AS expname, 
																				 fn_shortname(CONCAT(b.fld_exp_name,' ',(SELECT fld_version FROM itc_exp_version_track 
																				 WHERE fld_exp_id=b.fld_id AND fld_delstatus='0')),1) AS expshortname, 
																				 a.fld_dest_name AS destname, fn_shortname(a.fld_dest_name,1)AS destshortname 
																			  FROM `itc_exp_destination_master` AS a 
																			  	LEFT JOIN `itc_exp_master` AS b ON b.fld_id=a.fld_exp_id 
																			  WHERE a.fld_id NOT IN(SELECT fld_dest_id FROM itc_license_exp_mapping 
																			  	WHERE fld_license_id='".$licenseid."' AND fld_flag='1')
																				AND a.`fld_delstatus`='0' AND b.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_flag='1' ORDER BY b.fld_exp_name");
                                ?>
                                <div class="dragtitle">Expeditions available (<span id="leftexpeditions"><?php echo $qrymodule->num_rows ;?></span>)</div>
                                <div class="draglinkleftSearch" id="s_list13" > <!-- search for left box of expedition -->
                                   <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Search' type='text' id="list_13_search" name="list_13_search" onKeyUp="search_list(this,'#list13');" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="dragWell" id="testrailvisible13" >
                                    <div id="list13" class="dragleftinner droptrue">
                                        <?php
											
                                            if($qrymodule->num_rows > 0){
                                                while($resmod=$qrymodule->fetch_assoc()){
                                                    extract($resmod);
                                                ?>
                                                    <div class="draglinkleft" id="list13_<?php echo $expid."_".$destid; ?>" name="<?php echo $expid."~".'15'."~".$destid; ?>">
                                                        <div class="dragItemLable tooltip" id="<?php echo $expid."_".$destid;?>" title="<?php echo $expname." / ".$destname;?>"><?php echo $expshortname." / ".$destshortname; ?></div>
                                                        <div class="clickable" id="clck_<?php echo $expid."_".$destid; ?>" onclick="fn_movealllistitems('list13','list14',1,'<?php echo $expid."_".$destid; ?>');"></div>
                                                    </div> 
                                                <?php                                                    
                                                }
                                            }
                                        ?>    
                                    </div>
                                </div>
                                <div class="dragAllLink"  onclick="fn_movealllistitems('list13','list14',0,0);" style="cursor: pointer;cursor:hand;width:  153px;float: right; ">Add all Expeditions.</div>
                            </div>
                        </div>
                        <div class='six columns'>
                            <div class="dragndropcol">
                                        <?php 			
										//get expedition and destination from the license							
                                        $qrymoduleselect= $ObjDB->QueryObject("SELECT a.fld_id AS destid, b.fld_id AS expid, CONCAT(b.fld_exp_name,' ',
																				(SELECT fld_version FROM itc_exp_version_track WHERE fld_exp_id=b.fld_id 
																					AND fld_delstatus='0')) AS expname, fn_shortname(CONCAT(b.fld_exp_name,' ',
																					(SELECT fld_version FROM itc_exp_version_track WHERE fld_exp_id=b.fld_id AND 
																					fld_delstatus='0')),1) AS expshortname, a.fld_dest_name AS destname, 
																					fn_shortname(a.fld_dest_name,1)AS destshortname 
																				FROM `itc_exp_destination_master` AS a LEFT JOIN `itc_exp_master` AS b 
																						ON b.fld_id=a.fld_exp_id LEFT JOIN `itc_license_exp_mapping` AS c ON 
																						c.fld_dest_id=a.fld_id 
																				WHERE c.fld_license_id='".$licenseid."' AND a.`fld_delstatus`='0' 
																					AND b.fld_delstatus='0' AND c.fld_flag='1' AND b.fld_flag='1' AND a.fld_flag='1'");
                                        $qryexpunselect=$ObjDB->QueryObject("SELECT fld_exp_id FROM itc_class_indasexpedition_master where fld_license_id='".$licenseid."' and fld_delstatus='0'");
                                            $filter_greyout=array(); 
                                            while($expunselect=$qryexpunselect->fetch_assoc()){
                                            extract($expunselect);
                                            array_push($filter_greyout,$fld_exp_id);
                                            }  
                                ?>
                                <div class="dragtitle">Expeditions in your license (<span id="rightexpeditions"><?php echo $qrymoduleselect->num_rows ;?></span>)</div>
                                <div class="draglinkleftSearch" id="s_list14" ><!-- search for right box of expedition -->
                                   <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Search' type='text' id="list_14_search" name="list_14_search" onKeyUp="search_list(this,'#list14');" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="dragWell" id="testrailvisible14">
                                    <div id="list14" class="dragleftinner droptrue">
                                        <?php 			
                                            
                                            
                                            if($qrymoduleselect->num_rows > 0){
                                                while($resassignedexp=$qrymoduleselect->fetch_assoc()){
                                                    extract($resassignedexp);
                                                    $dimexp = array_diff(array($expid),$filter_greyout);
                                                    ?>
                                                        <div class="draglinkright<?php if(empty($dimexp)) { echo ' dim'; }?>" id="list14_<?php echo $expid."_".$destid; ?>" name="<?php echo $expid."~".'15'."~".$destid;?>">                                                           
                                                            <div class="dragItemLable tooltip" id="<?php echo $expid."_".$destid;?>" title="<?php echo $expname." / ".$destname;?>"><?php echo $expshortname." / ".$destshortname; ?></div>
                                                            <div class="clickable" id="clck_<?php echo $expid."_".$destid; ?>" onclick="fn_movealllistitems('list13','list14',1,'<?php echo $expid."_".$destid; ?>');"></div>
                                                        </div>
                                                    <?php 
                                                }
                                            }
                                        ?>	
                                    </div>
                                </div>
                                <div class="dragAllLink" onclick="fn_movealllistitems('list14','list13',0,0);" style="cursor: pointer;cursor:hand;width:  172px;float: right; ">Remove all Expeditions.</div>
                            </div>
                        </div>
                    </div>
                    
                        
                   <div class='row rowspacer' id="mission"> <!-- Missions-->
                        <div class='six columns'>
                            <div class="dragndropcol">
                                <?php
                                        $qrymission=$ObjDB->QueryObject("SELECT a.fld_id AS destid, b.fld_id AS misid, 
                                                                                        CONCAT(b.fld_mis_name,' ',(SELECT fld_version FROM itc_mission_version_track 
                                                                                        WHERE fld_mis_id=b.fld_id AND fld_delstatus='0')) AS misname, 
                                                                                         fn_shortname(CONCAT(b.fld_mis_name,' ',(SELECT fld_version FROM itc_mission_version_track 
                                                                                         WHERE fld_mis_id=b.fld_id AND fld_delstatus='0')),1) AS misshortname, 
                                                                                         a.fld_dest_name AS destname, fn_shortname(a.fld_dest_name,1)AS destshortname 
                                                                                  FROM `itc_mis_destination_master` AS a 
                                                                                        LEFT JOIN `itc_mission_master` AS b ON b.fld_id=a.fld_mis_id 
                                                                                  WHERE a.fld_id NOT IN(SELECT fld_dest_id FROM itc_license_mission_mapping 
                                                                                        WHERE fld_license_id='".$licenseid."' AND fld_flag='1')
                                                                                        AND a.`fld_delstatus`='0' AND b.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_flag='1'");
                                ?>
                                <div class="dragtitle">Missions available (<span id="leftmission"><?php echo $qrymission->num_rows ;?></span>)</div>
                                <div class="draglinkleftSearch" id="s_list25" >

                                   <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Search' type='text' id="list_25_search" name="list_25_search" onKeyUp="search_list(this,'#list25');" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="dragWell" id="testrailvisible25" >
                                    <div id="list25" class="dragleftinner droptrue">
                                        <?php 
											
                                            if($qrymission->num_rows > 0){
                                                while($resmission=$qrymission->fetch_assoc()){
                                                    extract($resmission);
                                                ?>
                                                    <div class="draglinkleft" id="list25_<?php echo $misid."_".$destid; ?>" name="<?php echo $misid."~".'18'."~".$destid; ?>">
                                                        <div class="dragItemLable tooltip" id="<?php echo $misid."_".$destid;?>" title="<?php echo $misname." / ".$destname;?>"><?php echo $misshortname." / ".$destshortname; ?></div>
                                                        <div class="clickable" id="clck_<?php echo $misid."_".$destid; ?>" onclick="fn_movealllistitems('list25','list26',1,'<?php echo $misid."_".$destid; ?>');"></div>
                                                    </div> 
                                                <?php
                                                }
                                            }
                                        ?>    
                                    </div>
                                </div>
                                <div class="dragAllLink"  onclick="fn_movealllistitems('list25','list26',0,0);" style="cursor: pointer;cursor:hand;width:  121px;float: right; ">Add all Missions.</div>
                            </div>
                        </div>
                        <div class='six columns'>
                            <div class="dragndropcol">
                                <?php
                                       
                                        $qrymissionselect= $ObjDB->QueryObject("SELECT a.fld_id AS destid, b.fld_id AS misid, CONCAT(b.fld_mis_name,' ',
																				(SELECT fld_version FROM itc_mission_version_track WHERE fld_mis_id=b.fld_id 
																					AND fld_delstatus='0')) AS misname, fn_shortname(CONCAT(b.fld_mis_name,' ',
																					(SELECT fld_version FROM itc_mission_version_track WHERE fld_mis_id=b.fld_id AND 
																					fld_delstatus='0')),1) AS misshortname, a.fld_dest_name AS destname, 
																					fn_shortname(a.fld_dest_name,1)AS destshortname 
																				FROM `itc_mis_destination_master` AS a LEFT JOIN `itc_mission_master` AS b 
																						ON b.fld_id=a.fld_mis_id LEFT JOIN `itc_license_mission_mapping` AS c ON 
																						c.fld_dest_id=a.fld_id 
																				WHERE c.fld_license_id='".$licenseid."' AND a.`fld_delstatus`='0' 
																					AND b.fld_delstatus='0' AND c.fld_flag='1' AND b.fld_flag='1' AND a.fld_flag='1'");
                                        $qrymissionunselect=$ObjDB->QueryObject("SELECT fld_mis_id FROM itc_class_indasmission_master where fld_license_id='".$licenseid."' and fld_delstatus='0'");
                                       
                                ?>
                                <div class="dragtitle">Missions in your license (<span id="rightmission"><?php echo $qrymissionselect->num_rows ;?></span>)</div>
                                <div class="draglinkleftSearch" id="s_list26" >
                                   <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Search' type='text' id="list_26_search" name="list_26_search" onKeyUp="search_list(this,'#list26');" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="dragWell" id="testrailvisible26">
                                    <div id="list26" class="dragleftinner droptrue">                                    	
                                        <?php 
                                            $filter_greyout=array(); 
                                            while($misunselect=$qrymissionunselect->fetch_assoc()){
                                            extract($misunselect);
                                            array_push($filter_greyout,$fld_mis_id);
                                            }  
                                            
                                            if($qrymissionselect->num_rows > 0){
                                                while($resassignedmis=$qrymissionselect->fetch_assoc()){
                                                    extract($resassignedmis);
                                                    $dimmis = array_diff(array($misid),$filter_greyout);
                                                    ?>
                                                        <div class="draglinkright<?php if(empty($dimmis)) { echo ' dim'; }?>" id="list26_<?php echo $misid."_".$destid; ?>" name="<?php echo $misid."~".'18'."~".$destid;?>">                                                           
                                                            <div class="dragItemLable tooltip" id="<?php echo $misid."_".$destid;?>" title="<?php echo $misname." / ".$destname;?>"><?php echo $misshortname." / ".$destshortname; ?></div>
                                                            <div class="clickable" id="clck_<?php echo $misid."_".$destid; ?>" onclick="fn_movealllistitems('list25','list26',1,'<?php echo $misid."_".$destid; ?>');"></div>
                                                        </div>
                                                    <?php 
                                                }
                                            }
                                        ?>	
                                    </div>
                                </div>
                                <div class="dragAllLink" onclick="fn_movealllistitems('list26','list25',0,0);" style="cursor: pointer;cursor:hand;width:  167px;float: right; ">Remove all Missions.</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class='row rowspacer' id="courses"> <!-- Courses-->
                        <div class='six columns'>
                            <div class="dragndropcol">
                                <?php
                                    //get course names from course master. If the license is new one get all the courses, otherwise get the courses except particular license courses
                                        	 $qrycourse=$ObjDB->QueryObject("SELECT a.`fld_id` AS courseid, a.`fld_course_name` AS coursename, b.fld_course_id AS chkcourse
																			FROM itc_course_master a
																			LEFT JOIN itc_pd_master b ON a.`fld_id`=b.`fld_course_id`
																			WHERE a.fld_delstatus='0' AND a.fld_id 
																			NOT IN (SELECT fld_course_id FROM itc_license_course_mapping 
																						WHERE fld_license_id='".$licenseid."' AND fld_flag='1') 
																			GROUP BY a.`fld_id`
																			ORDER BY a.fld_course_name");
                                ?>
                                <div class="dragtitle">Courses available (<span id="leftcourses"><?php echo $qrycourse->num_rows ;?></span>)</div>
                                <div class="draglinkleftSearch" id="s_list15" >

                                   <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Search' type='text' id="list_15_search" name="list_5_search" onKeyUp="search_list(this,'#list15');" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="dragWell" id="testrailvisible15" >
                                    <div id="list15" class="dragleftinner droptrue">
                                        <?php 
                                                  
											
                                            if($qrycourse->num_rows > 0){
                                                while($rescourse=$qrycourse->fetch_assoc()){
                                                    extract($rescourse);
                                                    if($chkcourse > 0){ //the course have lessons
                                                ?>
                                                    <div class="draglinkleft" id="list15_<?php echo $courseid; ?>" >
                                                        <div class="dragItemLable" id="<?php echo $courseid; ?>"><?php echo $coursename; ?></div>
                                                        <div class="clickable" id="clck_<?php echo $courseid; ?>" onclick="fn_movealllistitems('list15','list16',1,<?php echo $courseid; ?>);"></div>
                                                    </div> 
                                                <?php
                                                    }
                                                    else{ // the course dont have any lessons ?>
                                                        <div class="draglinkleft dim" >
                                                            <div class="dragItemLable"><?php echo $coursename; ?></div>
                                                        </div>
                                                        <?php 
                                                    }
                                                }
                                            }
                                        ?>    
                                    </div>
                                </div>
                                <div class="dragAllLink"  onclick="fn_movealllistitems('list15','list16',0,0);" style="cursor: pointer;cursor:hand;width:  150px;float: right; ">Add all Courses.</div>
                            </div>
                        </div>
                        <div class='six columns'>
                            <div class="dragndropcol">
                                        <?php 
										//get the courses which is mapping with the license
                                        $qrycourseselect=$ObjDB->QueryObject("SELECT a.fld_id AS courseid, a.fld_course_name AS coursename 
                                                                            FROM itc_course_master AS a LEFT JOIN itc_license_course_mapping AS b ON a.fld_id=b.fld_course_id
                                                                            WHERE a.fld_delstatus='0' AND b.fld_license_id='".$licenseid."' AND b.fld_flag='1' 
                                                                            GROUP BY a.fld_id 
                                                                            ORDER BY a.fld_course_name");
                                                      //below this change line
                                        $qrycourseunselect=$ObjDB->QueryObject("SELECT a.fld_course_id as selectcourseid FROM itc_class_pdschedule_course_mapping as a
                                                                                LEFT JOIN itc_class_pdschedule_master AS b ON b.fld_id=a.fld_pdschedule_id
                                                                                where a.fld_license_id='".$licenseid."' AND b.fld_delstatus='0'");
                                        $filter_greyout=array(); 
                                            while($courseunselect=$qrycourseunselect->fetch_assoc()){
                                                extract($courseunselect);
                                                array_push($filter_greyout,$selectcourseid);
                                            }      
                                ?>
                                <div class="dragtitle">Courses in your license (<span id="rightcourses"><?php echo $qrycourseselect->num_rows ;?></span>)</div>
                                <div class="draglinkleftSearch" id="s_list16" >
                                   <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Search' type='text' id="list_16_search" name="list_16_search" onKeyUp="search_list(this,'#list16');" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="dragWell" id="testrailvisible16">
                                    <div id="list16" class="dragleftinner droptrue">                                    	
                                        <?php 
                                              
                                              
                                            if($qrycourseselect->num_rows > 0){
                                                while($resassignedcourse=$qrycourseselect->fetch_assoc()){
                                                extract($resassignedcourse);
                                                $dimcourse = array_diff(array($courseid),$filter_greyout);
                                                    ?>
                                                        <div class="draglinkright<?php if(empty($dimcourse)) { echo ' dim'; }?>" id="list16_<?php echo $courseid; ?>">
                                                            <div class="dragItemLable" id="<?php echo $courseid; ?>"><?php echo $coursename; ?></div>
                                                            <div class="clickable" id="clck_<?php echo $unitid; ?>" onclick="fn_movealllistitems('list15','list16',1,<?php echo $courseid; ?>);"></div>
                                                        </div>
                                                    <?php 
              
                                                }
                                            }
                                        ?>	
                                    </div>
                                </div>
                                <div class="dragAllLink" onclick="fn_movealllistitems('list16','list15',0,0);" style="cursor: pointer;cursor:hand;width:  150px;float: right; ">Remove all Courses.</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class='row rowspacer' id="pdlessons"> <!-- Shows pdlessons list to select-->
                    </div>
                        
                    <div id="assessment" class='row rowspacer'>
                      <div class='six columns'>
                            <div class="dragndropcol">
                                <?php
                                        //If the license is new one get all the assessments which is created by pitscoadmin, otherwise get the assessment except particular license  
                                        	 $qryass=$ObjDB->QueryObject("SELECT a.fld_id AS assid, a.fld_test_name AS assname 
											 							 FROM itc_test_master AS a LEFT JOIN itc_user_master AS b ON a.fld_created_by=b.fld_id 
																		 WHERE a.fld_flag='1' AND a.fld_delstatus='0' AND a.fld_id NOT IN (SELECT fld_assessment_id FROM 
																		 		itc_license_assessment_mapping WHERE fld_license_id='".$licenseid."' AND fld_access='1') 
																				AND (b.fld_profile_id IN (2,3)) 
																		 ORDER BY a.fld_test_name");
                                ?>
                                <div class="dragtitle">Assessments available (<span id="leftassessments"><?php echo $qryass->num_rows ;?></span>)</div>
                                <div class="draglinkleftSearch" id="s_list9" >
                                   <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Search' type='text' id="list_9_search" name="list_9_search" onKeyUp="search_list(this,'#list9');" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="dragWell" id="testrailvisible9" >
                                    <div id="list9" class="dragleftinner droptrue">
                                        <?php 
										
                                            if($qryass->num_rows > 0){
                                                while($resass=$qryass->fetch_assoc()){
                                                    extract($resass);
                                                ?>
                                                    <div class="draglinkleft" id="list9_<?php echo $assid; ?>" >
                                                        <div class="dragItemLable" id="<?php echo $assid; ?>"><?php echo $assname; ?></div>
                                                        <div class="clickable" id="clck_<?php echo $assid; ?>" onclick="fn_movealllistitems('list9','list10',1,<?php echo $assid; ?>);"></div>
                                                    </div> 
                                                <?php
                                                }
                                            }
                                        ?>    
                                    </div>
                                </div>
                                <div class="dragAllLink"  onclick="fn_movealllistitems('list9','list10',0,0);" style="cursor: pointer;cursor:hand;width:  153px;float: right; ">Add all Assessments.</div>
                            </div>
                        </div>
                        <div class='six columns'>
                            <div class="dragndropcol">
                                        <?php 
										//get the assessment which is mapping with the license
                                        $qryunitselect=$ObjDB->QueryObject("SELECT a.fld_id as assid,a.fld_test_name as assname 
                                                                                FROM itc_test_master AS a 
                                                                               LEFT JOIN itc_license_assessment_mapping AS b ON a.fld_id=b.fld_assessment_id 
																			WHERE a.fld_delstatus='0' AND b.fld_license_id='".$licenseid."' AND b.fld_access='1' 
																			GROUP BY a.fld_id 
																			ORDER BY a.fld_test_name");
                                        $qryassunselect=$ObjDB->QueryObject("SELECT fld_test_id FROM itc_test_student_mapping where fld_flag = 1 AND fld_test_id 
                                                                            IN (select fld_assessment_id from itc_license_assessment_mapping where fld_license_id = '".$licenseid."' AND fld_access='1')");
                                        //below this change line
                                       $qryassunselect=$ObjDB->QueryObject("SELECT c.fld_test_id as assid
                                                                                FROM itc_test_master AS a 
                                                                               LEFT JOIN itc_license_assessment_mapping AS b ON a.fld_id=b.fld_assessment_id 
                                                                                LEFT JOIN itc_test_student_mapping AS c ON c.fld_test_id=b.fld_assessment_id 
                                                                                WHERE a.fld_delstatus='0' AND b.fld_license_id='".$licenseid."' AND b.fld_access='1' AND c.fld_flag = '1' 
                                                                                GROUP BY a.fld_id");
                                                 
                                            $filter_greyout=array(); 
                                            while($assunselect=$qryassunselect->fetch_assoc()){
                                            extract($assunselect);
                                            array_push($filter_greyout,$assid);
                                          }
                                ?>
                                <div class="dragtitle">Assessments in your license (<span id="rightassessments"><?php echo $qryunitselect->num_rows ;?></span>)</div>
                                <div class="draglinkleftSearch" id="s_list10" >
                                   <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Search' type='text' id="list_10_search" name="list_10_search" onKeyUp="search_list(this,'#list10');" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="dragWell" id="testrailvisible10">
                                    <div id="list10" class="dragleftinner droptrue">
                                        <?php 
										
                                            if($qryunitselect->num_rows > 0){
                                                while($resassignedunit=$qryunitselect->fetch_assoc()){
                                                    extract($resassignedunit);
                                                    $dimass = array_diff(array($assid),$filter_greyout);
                                                    ?>
                                                        <div class="draglinkright<?php if(empty($dimass)) { echo ' dim'; }?>" id="list10_<?php echo $assid; ?>">
                                                            <div class="dragItemLable" id="<?php echo $assid; ?>"><?php echo $assname; ?></div>
                                                            <div class="clickable" id="clck_<?php echo $assid; ?>" onclick="fn_movealllistitems('list9','list10',1,<?php echo $assid; ?>);"></div>
                                                        </div>
                                                    <?php 
                                                }
                                            }
                                        ?>	
                                    </div>
                                </div>
                                <div class="dragAllLink" onclick="fn_movealllistitems('list10','list9',0,0);" style="cursor: pointer;cursor:hand;width:  191px;float: right; ">Remove all Assessments.</div>
                            </div>
                        </div>
                    </div>
                        
                    <div class='row rowspacer' id="nondigital"> <!-- Non digital content -->
                        <div class='six columns'>
                            <div class="dragndropcol">
                                <?php
																					
                                        	 $qrynondigcontent=$ObjDB->QueryObject("SELECT a.fld_id as nondigiid,CONCAT(a.fld_product_name,' ',a.fld_version_number) as productname,fn_shortname(CONCAT(a.fld_product_name,' ',a.fld_version_number),1) as shortname,b.fld_category_name as catname FROM itc_nondigicontent_product as a left join itc_nondigicontent_category as b on b.fld_id=a.fld_nondigicat_id where a.fld_delstatus='0' and a.fld_id
																			NOT IN (SELECT fld_product_id FROM itc_license_nondigitalcontent_mapping 
																						WHERE fld_license_id='".$licenseid."' AND fld_access='1') 
																			GROUP BY a.`fld_id`
																			ORDER BY b.fld_category_name,a.fld_product_name");
                                ?>
                                <div class="dragtitle">Nondigital available (<span id="leftnondigi"><?php echo $qrynondigcontent->num_rows;?></span>)</div>
                                <div class="draglinkleftSearch" id="s_list31" >

                                   <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Search' type='text' id="list_31_search" name="list_31_search" onKeyUp="search_list(this,'#list31');" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="dragWell" id="testrailvisible31" >
                                    <div id="list31" class="dragleftinner droptrue">
                                        <?php 
						
                                            if($qrynondigcontent->num_rows > 0){
                                                while($resnon=$qrynondigcontent->fetch_assoc()){
                                                    extract($resnon);
                                                ?>
                                                    <div class="draglinkleft" id="list31_<?php echo $nondigiid; ?>" >
                                                        <div class="dragItemLable tooltip" title="<?php echo $productname.' / '.$catname; ?>" id="<?php echo $nondigiid; ?>"><?php echo $shortname." / ".$catname; ?></div>
                                                        <div class="clickable" id="clck_<?php echo $nondigiid; ?>" onclick="fn_movealllistitems('list31','list32',1,<?php echo $nondigiid; ?>);"></div>
                                                    </div> 
                                               
                                                        <?php 
                                                    }
                                                }
                                                ?>    
                                    </div>
                                </div>
                                <div class="dragAllLink"  onclick="fn_movealllistitems('list31','list32',0,0);" style="cursor: pointer;cursor:hand;width:  140px;float: right; ">Add all Nondigital.</div>
                            </div>
                        </div>
                        <div class='six columns'>
                            <div class="dragndropcol">
                                        <?php 
										
                                        $qrynondigiselect=$ObjDB->QueryObject("SELECT a.fld_nondigicat_id as catid,a.fld_id AS nondigiid, a.fld_product_name AS productname,c.fld_category_name as catname
																			FROM itc_nondigicontent_product AS a left join itc_nondigicontent_category as c on c.fld_id=a.fld_nondigicat_id LEFT JOIN itc_license_nondigitalcontent_mapping AS b ON a.fld_id=b.fld_product_id 
																			WHERE a.fld_delstatus='0' AND b.fld_license_id='".$licenseid."' AND b.fld_access='1' and c.fld_delstatus='0' 
																			GROUP BY a.fld_id 
																			ORDER BY c.fld_category_name,a.fld_product_name");
                                                      
                                ?>
                                <div class="dragtitle">Nondigital in your license (<span id="rightnondigi"><?php echo $qrynondigiselect->num_rows;?></span>)</div>
                                <div class="draglinkleftSearch" id="s_list32" >
                                   <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Search' type='text' id="list_32_search" name="list_32_search" onKeyUp="search_list(this,'#list32');" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="dragWell" id="testrailvisible32">
                                    <div id="list32" class="dragleftinner droptrue">                                    	
                                        <?php 
                                              
                                              
                                            if($qrynondigiselect->num_rows > 0){
                                                while($rownondigi=$qrynondigiselect->fetch_assoc()){
                                                    extract($rownondigi);
                                                    
                                                    
                                                
                                                    ?>
                                                        <div class="draglinkright" id="list32_<?php echo $nondigiid; ?>">
                                                            <div class="dragItemLable" id="<?php echo $nondigiid; ?>"><?php echo $productname." / ".$catname; ?></div>
                                                            <div class="clickable" id="clck_<?php echo $nondigiid; ?>" onclick="fn_movealllistitems('list31','list32',1,<?php echo $nondigiid; ?>);"></div>
                                                        </div>
                                                    <?php 
                                                }
                                            }
                                        ?>	
                                    </div>
                                </div>
                                <div class="dragAllLink" onclick="fn_movealllistitems('list32','list31',0,0);" style="cursor: pointer;cursor:hand;width:  161px;float: right; ">Remove all Nondigital.</div>
                            </div>
                        </div>
                    </div>
					
		    <div class='row rowspacer' id="product"></div> <!-- Shows Sim Product list to select-->	
						
                    <div class='row rowspacer'>
                        <div id="extenddiv" style="float:left;"> <!-- extend content -->
                            Do you want to extend the content of the modules / math modules / Expeditions that you have selected?                        
                            <br />
                            Please press the "Extend content button"
                        </div>
                        <div style="float:right;">
                            <input type="button" id="extendbtn" class="darkButton" value="Extend Content" onclick="fn_loadextendcontent(<?php echo $licenseid;?>)" /> 
                        </div>
                    </div>
                    <script>
						<?php if($licenseid!=0){?>
							fn_loadextendcontent(<?php echo $licenseid;?>,1);
						<?php }?>
					</script>
                    <div id="extendcontent" class='row rowspacer'>
                    </div>
                     
                </div>
                
                    
                    <div id="soscontent" class="sdesc" style="display:none;">
                        <div class='row rowspacer' id="sosunits"> <!-- Units-->
                        <div class='six columns'>
                            <div class="dragndropcol">
                                <?php
                                      //get unit names from sosunit master. If the license is new one get all the units, otherwise get the units except particular license units	
                                        	 $qrysosunit=$ObjDB->QueryObject("SELECT a.`fld_id` AS unitid, a.`fld_unit_name` AS unitname, ISNULL(b.fld_unit_id) AS chkunit
																			FROM itc_sosunit_master a
																			LEFT JOIN itc_sosphase_master b ON a.`fld_id`=b.`fld_unit_id`
																			WHERE a.fld_delstatus='0' AND a.fld_id 
																			NOT IN (SELECT fld_unit_id FROM itc_license_sosunit_mapping 
																						WHERE fld_license_id='".$licenseid."' AND fld_access='1') 
																			GROUP BY a.`fld_id`
																			ORDER BY a.fld_unit_name");
                                ?>
                                <div class="dragtitle">Units available (<span id="leftsosunits"><?php echo $qrysosunit->num_rows ;?></span>)</div>
                                <div class="draglinkleftSearch" id="s_list17" >

                                   <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Search' type='text' id="list_17_search" name="list_17_search" onKeyUp="search_list(this,'#list17');" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="dragWell" id="testrailvisible17" >
                                    <div id="list17" class="dragleftinner droptrue">
                                        <?php 
											
                                            if($qrysosunit->num_rows > 0){
                                                while($resunit=$qrysosunit->fetch_assoc()){
                                                    extract($resunit);
                                                    if($chkunit == 0){ //the unit have lessons
                                                ?>
                                                    <div class="draglinkleft" id="list17_<?php echo $unitid; ?>" >
                                                        <div class="dragItemLable" id="<?php echo $unitid; ?>"><?php echo $unitname; ?></div>
                                                        <div class="clickable" id="clck_<?php echo $unitid; ?>" onclick="fn_movealllistitems('list17','list18',1,<?php echo $unitid; ?>);"></div>
                                                    </div> 
                                                <?php
                                                    }
                                                    else{ // the unit dont have any lessons ?>
                                                        <div class="draglinkleft dim" >
                                                            <div class="dragItemLable"><?php echo $unitname; ?></div>
                                                        </div>
                                                        <?php 
                                                    }
                                                }
                                            }
                                        ?>    
                                    </div>
                                </div>
                                <div class="dragAllLink"  onclick="fn_movealllistitems('list17','list18',0,0);" style="cursor: pointer;cursor:hand;width:  94px;float: right; ">Add all units.</div>
                            </div>
                        </div>
                        <div class='six columns'>
                            <div class="dragndropcol">
                                <?php
                                       //get the units which is mapping with the license
                                        $qryunitselect=$ObjDB->QueryObject("SELECT a.fld_id AS unitid, a.fld_unit_name AS unitname 
                                                                            FROM itc_sosunit_master AS a LEFT JOIN itc_license_sosunit_mapping AS b ON a.fld_id=b.fld_unit_id
                                                                            WHERE a.fld_delstatus='0' AND b.fld_license_id='".$licenseid."' AND b.fld_access='1' 
                                                                            GROUP BY a.fld_id 
                                                                            ORDER BY a.fld_unit_name");
                                                      //below this change line
                                       
                                ?>
                                <div class="dragtitle">Units in your license (<span id="rightsosunits"><?php echo $qryunitselect->num_rows ;?></span>)</div>
                                <div class="draglinkleftSearch" id="s_list18" >
                                   <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Search' type='text' id="list_18_search" name="list_18_search" onKeyUp="search_list(this,'#list18');" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="dragWell" id="testrailvisible18">
                                    <div id="list18" class="dragleftinner droptrue">                                    	
                                        <?php 
                                       
                                              
                                            if($qryunitselect->num_rows > 0){
                                                while($resassignedunit=$qryunitselect->fetch_assoc()){
                                                extract($resassignedunit);
                                               
                                                    ?>
                                                        <div class="draglinkright" id="list18_<?php echo $unitid; ?>">
                                                            <div class="dragItemLable" id="<?php echo $unitid; ?>"><?php echo $unitname; ?></div>
                                                            <div class="clickable" id="clck_<?php echo $unitid; ?>" onclick="fn_movealllistitems('list17','list18',1,<?php echo $unitid; ?>);"></div>
                                                        </div>
                                                    <?php 
              
                                                }
                                            }
                                        ?>	
                                    </div>
                                </div>
                                <div class="dragAllLink" onclick="fn_movealllistitems('list18','list17',0,0);" style="cursor: pointer;cursor:hand;width:  123px;float: right; ">Remove all units.</div>
                            </div>
                        </div>
                    </div>
                    <div class='row rowspacer' id="phases"> <!-- Shows phases list to select-->
                    </div>
                    <div class='row rowspacer' id="video"> <!-- Shows phases list to select-->
                    </div>
                    <div class='row rowspacer' id="document"> <!-- Shows phases list to select-->
                    </div>
                </div>
                    
                    
                    <div class='row rowspacer' style="padding-top:20px;">
                        <div class='six columns'>
                                <p class='btn primary twelve columns'>
                                	<a onClick="fn_cancel('licenses')">Cancel</a>
                                </p>
                        </div>
                        <div class='six columns'>
                            <p class='btn secondary twelve columns'>
                                <a onclick="fn_createlicense(<?php echo $licenseid;?>)"><?php echo $createbtn; ?></a>
                            </p>
                        </div>
                    </div>
                </form>
                <input type="hidden" id="hidlicense" value="<?php echo $licenseid; ?>" />
                <input type="hidden" id="hidlicensename" value="<?php echo $licensename; ?>" />
                <script language="javascript" type="text/javascript">				
                     $("#duration").keypress(function (e) {
						if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
							return false;
						}
					});
					$("#amount").keypress(function (e) {
						if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) && e.which != 46) {
							return false;
						}
					});
                     
                    /***addd category validate*****/
                    $(function(){
	                    $("#createlicense").validate({
							ignore: "input[type='text']:hidden",
							errorElement: "dd",
                     		errorPlacement: function(error, element) {
                                    $(element).parents('dl').addClass('error');
                                    error.appendTo($(element).parents('dl'));
                                    error.addClass('msg');
                                    window.scroll(0,($('dd').offset().top)-80);
                    		},
                    		rules: { 
                    			licennsename: { required: true, lettersonly: true,
								remote:{ 
											url: "licenses/newlicense/licenses-newlicense-newlicenseajax.php", 
											type:"POST", 
											data: {  
													id: function() {
													return '<?php echo $licenseid;?>';},
													oper: function() {
													return 'checklicensename';}														  
											},
											async:false 
								}},                          		
								duration:{ required: true},                          
                                        sales:{ required: true },
                          		hidlicensetype:{ required: true}                    
                    		},
							messages: {                     
                          		licennsename: {   required: "please type license name", remote: "License name already exists" },
                         		duration:{ required: "please type license duration" },
                                        sales:{ required:  "please type the sales order "},
                          		hidlicensetype:{ required:  "please select type" }                    
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