<?php 
	@include("sessioncheck.php");
	$sid = isset($method['sid']) ? $method['sid'] : '0';
        $taid = isset($method['taid']) ? $method['taid'] : '0';
	$sqry='';
	if($sid!=0){
		$sid = explode(',',$sid);
		for($i=0;$i<sizeof($sid);$i++){		
			$itemqry = $ObjDB->QueryObject("SELECT fld_item_id FROM itc_main_tag_mapping WHERE fld_tag_id='".$sid[$i]."' AND fld_access='1' AND fld_tag_type='9'");
			$sqry = "and (";
			$j=1;
			while($itemres = $itemqry->fetch_assoc()){
				extract($itemres);
				if($j==$itemqry->num_rows){
					$sqry.=" fld_id=".$fld_item_id.")";
				}
				else{
					$sqry.=" fld_id=".$fld_item_id." or";
				}
				$j++;
			}
		}
	}
        
        if($taid!=0){
	$taid = explode(',',$taid);
		for($i=0;$i<sizeof($taid);$i++){		
			$itemqry = $ObjDB->QueryObject("SELECT fld_id FROM itc_user_master 
								            WHERE fld_id='".$taid[$i]."' AND fld_delstatus='0'");
			$sqry = "and (";
			$j=1;
			while($itemres = $itemqry->fetch_assoc()){
				extract($itemres);
				if($j==$itemqry->num_rows){
					$sqry.=" fld_id=".$fld_id.")";
				}
				else{
					$sqry.=" fld_id=".$fld_id." or";
				}
				$j++;
			}
		}
	}
?>
<script type="text/javascript" charset="utf-8">		
	$.getScript("users/individuals/users-individuals-teacheradmin_newteacheradmin.js");	
	
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_teacheradmin', {
			startEditableBit: false,
			inBetweenEditableBits: false,
			plugins: {
				autocomplete: {
					onlyFromValues:true,
					queryRemote: true,
					remote: { url: 'autocomplete.php', extraParams: "oper=search&tag_type=9" },
					placeholder: ''
				}
			},
			bitsOptions:{editable:{addKeys: [188]}}													
		});																	
		
		t4.addEvent('bitAdd',function(bit) {
			fn_loadteachadmin();
		});
		
		t4.addEvent('bitRemove',function(bit) {
			fn_loadteachadmin();
		});					
			
	});	

	function fn_loadteachadmin(){
				var sid = $('#form_tags_teacheradmin').val();
				$("#teacheradminlist").load("users/individuals/users-individuals-teacheradmin.php #teacheradminlist > *",{'sid':sid});
				removesections('#users-individuals-teacheradmin');
			}
                        
        $(function(){				
		var t4 = new $.TextboxList('#form_tags_teacheradminname', {
			startEditableBit: false,
			inBetweenEditableBits: false,
			plugins: {
				autocomplete: {
					onlyFromValues:true,
					queryRemote: true,
					remote: { url: 'autocomplete.php', extraParams: "oper=searchteacheradminname&tag_type=9" },
					placeholder: ''
				}
			},
			bitsOptions:{editable:{addKeys: [188]}}													
		});																	
		
		t4.addEvent('bitAdd',function(bit) {
			fn_loadteachadminname();
		});
		
		t4.addEvent('bitRemove',function(bit) {
			fn_loadteachadminname();
		});					
			
	});	

	function fn_loadteachadminname(){
				var sid = $('#form_tags_teacheradminname').val();
				$("#teacheradminlist").load("users/individuals/users-individuals-teacheradmin.php #teacheradminlist > *",{'taid':sid});
				removesections('#users-individuals-teacheradmin');
			}
                        
                        /* for radio button options in title  */
		$("input[name='types']").click(function() {  
                    var test = $(this).val();
                    $("div.sdesc").hide();
                    $("#types" + test).show(); 
                });
</script>

<section data-type='#users-individuals' id='users-individuals-teacheradmin'>
	<div class='container'>
    	<div class='row'>
      		<div class='twelve columns'>
            	<p class="dialogTitle">Teacher Admin</p>
				<p class="dialogSubTitleLight">&nbsp;</p>
      		</div>
    	</div>
            
        <?php 
          if($sessmasterprfid == 2) 
          { 
        ?>
                   <div class="row rowspacer" style="padding-bottom:40px;"> 
                        <div class='four columns'>   
                            <p class="lightSubTitle">Category</p>
                         <dl class='field row'>
                            <div class="selectbox">
                                    <input type="hidden" name="categoryid" id="categoryid" value="Select Category">
                                    <a class="selectbox-toggle"  role="button" data-toggle="selectbox" href="#"><span class="selectbox-option input-medium" data-option="">Select Category</span><b class="caret1"></b></a>
                                <div class="selectbox-options">
                                                <input type="text" class="selectbox-filter" placeholder="Search Category">		    
                                            <ul role="options">
                                                <li>
                                                    <a tabindex="-1" href="#" data-option="1" onclick="fn_schoolpurchaseteacher();">School Purchase</a>
                                                </li>
                                                <li>
                                                    <a tabindex="-1" href="#" data-option="2" onclick="javascript:$('#districtind').hide();$('#shpurchase').hide();$('#RadioGroup').show();$('#types6').show();fn_session(0,0);fn_homepurchaseteacher();">Home Purchase</a>
                                                </li>
                                                <li>
                                                    <a tabindex="-1" href="#" data-option="3" onclick="javascript:$('#districtind').show();$('#shpurchase').hide();">District</a>
                                                </li>
                                    </ul>
                                </div>
                            </div>
                        </dl>
                    </div>
                  </div>
                   
      
                 <div class="row rowspacer" id="shpurchase" style="padding-bottom:40px;display:none;">
                    <div class='four columns'> 
                        <div id="purchasediv"></div>
                    </div>
                  </div> 
                   
                    <div class="row" style="padding-bottom:40px;display:none;" id="districtind"> 
                        <div class='four columns'> <p class="lightSubTitle">District</p>
                         <dl class='field row'>
                            <div class="selectbox">
                                <input type="hidden" name="districtid" id="districtid" value="">
                                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#"> <span class="selectbox-option input-medium" data-option="">Select District</span> <b class="caret1"></b> </a>
                                <div class="selectbox-options">
                                    <input type="text" class="selectbox-filter" placeholder="Search District">
                                    <ul role="options">
                                        <?php 

                                        $qry = $ObjDB->QueryObject("SELECT fld_id AS districtid, fld_district_name AS districtname FROM itc_district_master WHERE fld_delstatus='0' ORDER BY fld_district_name");
                                        if($qry->num_rows>0){
                                            while($row = $qry->fetch_assoc())
                                            {
                                                extract($row);
                                                ?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $districtid;?>" onclick="fn_showschoolind(<?php echo $districtid;?>)"><?php echo $districtname; ?></a></li>
                                                <?php
                                            }
                                        }?>
                                    </ul>
                                </div>
                            </div>
                        </dl>
                    </div>
                
                    <div class='two columns'> 
                    </div>
                    <!--Shows school names dropdown-->
                    <div class='four columns'> 
                        <!--Shows School Dropdown-->
                        <div id="schooldiv" style="display:none">
                            
                        </div>
                    </div>
            	</div>
             
        <?php }
          if($sessmasterprfid == 2) 
          { 
        ?>
            <div class="row" id="RadioGroup" <?php if($sessmasterprfid == 2){?> style="display:none;"<?php } ?>>
        	<div class='twelve columns'>
                <font style="color:white">Sort/filter the Teacher Admin list using: 
                <input type="radio" id="tag" name="types"  value="5" <?php if($sessmasterprfid == 2){?>onclick="fn_showteacheradmin(0,0);"<?php } ?>/>Tag
                <input type="radio" id="search" name="types" checked="checked" value="6" <?php if($sessmasterprfid == 2){?>onclick="fn_showteacheradmin(0,0);"<?php } ?>/>Teacher Admin Name
                </font>
                 &nbsp;&nbsp;
            </div>
        </div> 
    <?php 
        }  
        ?>
            
        <div class='sdesc row'  id="types5" <?php if($sessmasterprfid == 2){ ?> style="display:none;padding-bottom:20px;" <?php }else{ ?> style="padding-bottom:20px;" <?php } ?>>
        	<div class='twelve columns'>
        		<!--<p class="filterLightTitle">To filter this list, search by Tag Name.</p>-->
                        <p class="filterLightTitle">To search for a specific Teacher Admin, search by name, Tag name, or browse through the list of admins below.</p>
                <div class="tag_well">
                    <input type="text" name="test3" value="" id="form_tags_teacheradmin" />
                </div>
        	</div>
    	</div>
            
       <div class='sdesc row' style="padding-bottom:20px;display:none;" id="types6">
        	<div class='twelve columns'>
        		<!--<p class="filterLightTitle">To filter this list, search by Teacher Admin Name.</p>-->
                        <p class="filterLightTitle">To search for a specific Teacher Admin, search by name, Tag name, or browse through the list of admins below.</p>
                <div class="tag_well">
                    <input type="text" name="test3" value="" id="form_tags_teacheradminname" />
                </div>
        	</div>
    	</div>
            
    	<div class='row buttons' id="teacheradminlist">
      		<a class='skip btn mainBtn' href='#users-individuals-teacheradmin_newteacheradmin' id='btnusers-individuals-teacheradmin_newteacheradmin' name='0'>
       			<div class="icon-synergy-add-dark"></div>
        		<div class='onBtn'>New Teacher<br />Admin</div>
      		</a>
			<?php 
				if($sessmasterprfid == 7){ 
					$qry = $ObjDB->QueryObject("SELECT fld_id AS id, CONCAT(fld_fname,' ',fld_lname) AS fullname, 
												fn_shortname(CONCAT(fld_fname,' ',fld_lname),1) AS shortname, fld_profile_pic AS photo, 
												fld_district_id AS tadistid, fld_school_id AS tashlid 
											FROM itc_user_master WHERE fld_profile_id= '8' AND fld_school_id='".$senshlid."' AND fld_delstatus='0' ".$sqry."  
											ORDER BY fullname ASC");
				}
                                
				if($sqry!='' AND $sessmasterprfid == 2)
                                {
					 $qry = $ObjDB->QueryObject("SELECT fld_id AS id, CONCAT(fld_fname,' ',fld_lname) AS fullname, 
					 								fn_shortname(CONCAT(fld_fname,' ',fld_lname),1) AS shortname, fld_profile_pic AS photo, 
													fld_district_id AS tadistid, fld_school_id AS tashlid, fld_user_id AS tauserid 
												FROM itc_user_master WHERE fld_profile_id= '8' AND fld_delstatus='0' ".$sqry." 
												ORDER BY fullname ASC");
				}
               
			    while($row=$qry->fetch_assoc())
                {
					extract($row);
			?>
            		<a class='skip btn mainBtn' href='#users-individuals-teacheradmin_newteacheradmin' id='btnusers-individuals-teacheradmin_newteacheradmin' name="<?php echo $id.",".$tadistid.",".$tashlid.",".$tauserid;?>">
                    	<div class="icon-synergy-user">
                        	<?php if($photo != "no-image.png" && $photo != ''){ ?>
                        		<img class="thumbimg" src="thumb.php?src=<?php echo __CNTPPPATH__.$photo; ?>&w=40&h=40&q=100" />
                            <?php } ?>
                        </div>
                        <div class='onBtn tooltip' title="<?php echo $fullname;?>"><?php echo $shortname;?></div>
                    </a>
                    <?php
                }
                ?>
    	</div>
	</div>
</section>
<?php
	@include("footer.php");
