<?php
/*------
	Page - users-schoolpurchase-newschoolpurchase
	Description:
		Form to create a new schoolpurchase with required details.
	History:	
------*/
	@include("sessioncheck.php");
	
	$oper = isset($method['oper']) ? $method['oper'] : '';
	$editid =  isset($method['id']) ? $method['id'] : '';
	
	/****declaration part****/
	$shlname='';
	$staddress='';
	$fname='';
	$lname='';
	$email='';
    $hubid="";
	$pphoto='';
	$logo='';
	$arrcombine=array('','','','','','','','','','','','','','');
	
	$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname 
								FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
								WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='17' AND b.fld_access='1' 
									AND a.fld_created_by='".$uid."' AND a.fld_delstatus='0' AND b.fld_item_id='".$editid."'");
	
	$totalhidlicense = $ObjDB->SelectSingleValue("SELECT count(fld_id) FROM itc_license_master 
												WHERE fld_delstatus='0' AND fld_license_type='1'");
	$licensecount=2;
	if($editid != 0){
		$licensecount= $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_license_track 
													WHERE fld_school_id='".$editid."' AND fld_district_id=0 AND fld_delstatus='0'");
		
		$selectshlp = $ObjDB->QueryObject("SELECT a.fld_district_id AS distid, a.fld_school_name AS shlname, a.fld_hubid AS hubid, a.fld_school_admin_id AS shluid, 
											a.fld_school_logo AS logo,a.fld_street_address AS staddress, 
											a.fld_state AS state, a.fld_city AS city, a.fld_zipcode AS zipcode,c.fld_statename AS statename,d.fld_email AS email, 
											d.fld_fname AS fname, d.fld_lname AS lname, fld_profile_pic AS pphoto 
										FROM itc_school_master AS a 
										RIGHT JOIN  itc_state_city AS c ON a.fld_state=c.fld_statevalue
										RIGHT JOIN itc_user_master AS d ON a.fld_school_admin_id=d.fld_id
										WHERE a.fld_id='".$editid."'  AND a.fld_delstatus='0' 
										GROUP BY a.fld_id");
			
		$row=$selectshlp->fetch_assoc();
		extract($row);
		if($logo == '' or $logo == 'no-image.png'){ $logo1 = "<img src='img/no-image.png'/>";}
		else{ $logo1 = "<img src='thumb.php?src=".__CNTSLPATH__.$logo."&w=100&h=100&zc=2'   /> "; }
		
		if($pphoto == '' or $pphoto == 'no-image.png'){ $pphoto1 = "<img src='img/no-image.png'/>"; }
		else{ $pphoto1 = "<img src='thumb.php?src=".__CNTPPPATH__.$pphoto."&w=100&h=100&zc=2' /> "; }
		
		
		$arrfieldid=array();
		$arrfieldvalue=array();
		
		$optionaldet = $ObjDB->QueryObject("SELECT fld_field_id,fld_field_value 
										FROM itc_user_add_info 
										WHERE fld_user_id='".$shluid."'");
		$rows=$optionaldet->num_rows;
		if($rows !=0){
			while($rowoptionaldet=$optionaldet->fetch_object())
			{
				array_push($arrfieldid,$rowoptionaldet->fld_field_id);
				array_push($arrfieldvalue,$rowoptionaldet->fld_field_value);
			}
			 $arrcombine=array_combine($arrfieldid,$arrfieldvalue);
			 $arrcombine=getarrayvalues($arrfieldid,$arrcombine);
		}
	}
?>
<script type="text/javascript" charset="utf-8">	
	$.getScript("users/schoolpurchase/users-schoolpurchase-newschoolpurchase.js");	
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_newschoolp', 
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
<section data-type='users' id='users-schoolpurchase-newschoolpurchase'>
  <div class='container'>
    <div class='row'>
      <div class='twelve columns'>
      	<p class="darkTitle"><?php if($editid == 0){ echo "New School Purchase";} else { echo $shlname." "."School Purchase";} ?></p>
        <p class="darkSubTitleLight">&nbsp;</p>
      </div>
    </div>
    
    <div class='row formBase'>
        <div class='eleven columns centered insideForm'>
        	<form method='post' name="shlp" id="shlp">
                <div class="row">
                    <div class="six columns">
                        <div class="title-info">School Information (Required)</div>
                        School name<span class="fldreq">*</span> 
                        <dl class='field row'>
                            <dt class='text'>
                                <input id="shlname" name="shlname" onblur="$(this).valid();" placeholder='School name' tabindex="1" type='text' value="<?php echo $shlname;?>">
                            </dt>
                        </dl> 
                    </div>
                    <div class="six columns">
                        <div class="title-info">School Administrator Information (Optional)</div>
                        Street address
                        <dl class='field row'>
                            <dt class='text'>
                                <input  id="address1" name="address1" placeholder='Street address' tabindex="9" type='text' value="<?php echo $arrcombine[7];?>">
                            </dt>
                        </dl>
                    </div>
                </div>
                <div class="row rowspacer">
                    <div class="six columns">
                        Hub ID<span class="fldreq">*</span>
                        <dl class='field row'>
                            <dt class='text'>
                                <input id="hubid" name="hubid" placeholder='HUB ID' tabindex="88" type='text' value="<?php echo $hubid;?>">
                            </dt>
                        </dl>
                    </div>
                </div>
                <div class="row rowspacer">
                    <div class="six columns">
                    School street address<span class="fldreq">*</span> 
                        <dl class='field row'>
                            <dt class='text'>
                             	<input id="address" name="address" placeholder='School street address' tabindex="2" type='text' value="<?php echo $staddress;?>">
                            </dt>
                        </dl> 
                    </div>
                  	<div class="six columns">
                    Select state
                    	<dl class='field row'>
                            <dt class='dropdown'>
                                <div class="selectbox">
                                	<?php $statename1 = $ObjDB->SelectSingleValue("SELECT DISTINCT(fld_statename) 
																				FROM itc_state_city 
																				WHERE fld_statevalue='".$arrcombine[8]."'"); ?>
                                	<input type="hidden" name="ddlstate1" id="ddlstate1" value="<?php echo $arrcombine[8];?>" onchange="fn_changecity1(this.value);">
                                    <a class="selectbox-toggle" tabindex="10" role="button" data-toggle="selectbox" href="#">
                                    	<span class="selectbox-option input-medium" data-option="<?php if($editid==0){ echo "0";} else {echo $arrcombine[8];}?>"><?php if($editid == 0 or $arrcombine[8] == ''){ echo "Select state";} else {echo $statename1;}?></span><b class="caret1"></b>
                                    </a>
                                    <div class="selectbox-options">
                                    	<input type="text" class="selectbox-filter" placeholder="Search state" >
                                        <ul role="options">
                                        <?php 
                                            $stateqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_statevalue), fld_statename 
																			FROM itc_state_city 
																			WHERE fld_delstatus=0 
																			ORDER BY fld_statename ASC");
                                            while($rowstate = $stateqry->fetch_object()){ ?>
                                                    <li><a href="#" data-option="<?php echo $rowstate->fld_statevalue;?>"><?php echo $rowstate->fld_statename;?></a></li>
                                            <?php 
                                            }?>       
                                        </ul>
                                    </div>
                                </div>
                            </dt>
                        </dl>
                  </div>
                </div>
                
                <div class="row rowspacer">
                    <div class="six columns">
                    Select state<span class="fldreq">*</span> 
                        <dl class='field row'>
                            <dt class='dropdown'>
                                <div class="selectbox">
                                	<input type="hidden" name="ddlstate" id="ddlstate" value="<?php echo $state;?>" onchange="$('#ddlstate').valid();fn_changecity(this.value);">
                                    <a class="selectbox-toggle" tabindex="3" role="button" data-toggle="selectbox" href="#" >
                                    	<span class="selectbox-option input-medium" data-option="<?php if($editid==0){ echo "0";} else {echo $state;}?>"><?php if($editid == 0){ echo "Select state";} else {echo $statename;}?></span><b class="caret1"></b>
                                    </a>
									<?php if($editid ==0) { ?>
                                    <div class="selectbox-options">
                                        <input type="text" class="selectbox-filter" placeholder="Search state" >
                                        <ul role="options">
                                            <?php 
                                                $stateqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_statevalue), fld_statename 
																				FROM itc_state_city 
																				WHERE fld_delstatus=0 
																				ORDER BY fld_statename ASC");
                                                while($rowstate = $stateqry->fetch_object()){ ?>
                                                        <li><a tabindex="1" href="#" data-option="<?php echo $rowstate->fld_statevalue;?>"><?php echo $rowstate->fld_statename;?></a></li>
                                    		<?php 
                                            	}
											?>       
                                        </ul>
                                    </div>
                                    <?php } ?>
                                </div>
                            </dt>
                        </dl> 
                    </div>
                    <div class="six columns">
                    Select city
                        <dl class='field row'>
                            <dt class='dropdown'>
								<?php if($editid == 0){ ?>
                                    <div id="divddlcity1">
                                        <div class="selectbox" >
                                            <input type="hidden" name="ddlcity1" id="ddlcity1" value="" disabled="disabled" >
                                            <a class="selectbox-toggle" tabindex="11" role="button" data-toggle="selectbox" href="#" >
                                            <span class="selectbox-option input-medium" data-option="">Select city</span>
                                            <b class="caret1"></b>
                                            </a>
                                        </div>
                                    </div>
                                <?php } else {?>
                                    <div id="divddlcity1">
                                        <div class="selectbox">
                                        <input type="hidden" name="ddlcity1" id="ddlcity1" value="<?php echo $arrcombine[9];?>" onchange="fn_changezip1(this.value);" >
                                        <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#" >
                                        <span class="selectbox-option input-medium" data-option="<?php echo $arrcombine[9];?>"><?php if($arrcombine[9] ==''){ echo "Select city"; } else {echo $arrcombine[9]; }?></span>
                                        <b class="caret1"></b>
                                        </a>
                                            <div class="selectbox-options">
                                            <input type="text" class="selectbox-filter" placeholder="Search city" >
                                                <ul role="options">
                                                    <?php 
                                                        $cityqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_cityname) 
																						FROM itc_state_city 
																						WHERE fld_statevalue='".$arrcombine[8]."' AND fld_delstatus=0 
																						ORDER BY fld_cityname ASC");
                                                       while($rowcity = $cityqry->fetch_object()){?>
                                                                <li><a href="#" data-option="<?php echo ucwords(strtolower($rowcity->fld_cityname));?>"><?php echo  ucwords(strtolower($rowcity->fld_cityname))?></a></li>
                                                        <?php 
                                                        }?>       
                                                </ul>
                                            </div>
                                        </div>
                                    </div> 
                                <?php } ?>
                            </dt>
                        </dl>
                    </div>
                </div>
                
                <div class="row rowspacer">
                    <div class="six columns">
                    Select city<span class="fldreq">*</span> 
                        <dl class='field row' id="cit">
                            <dt class='dropdown'>
                                <div id="divddlcity">
                                    <div class="selectbox">
                                    <input type="hidden" name="ddlcity" id="ddlcity" value="<?php echo $city;?>" >
                                    <a class="selectbox-toggle" tabindex="4" role="button" data-toggle="selectbox" href="#">
                                    <span class="selectbox-option input-medium" data-option=""><?php if($editid == 0) { echo "Select city"; } else { echo $city; }?></span>
                                    <b class="caret1"></b>
                                    </a>
                                    </div>
                                </div>
                            </dt>
                        </dl> 
                    </div>
                    <div class="six columns">
                    Select zip
                        <dl class='field row'>
                            <dt class='dropdown'>
								<?php if($editid == 0){ ?>
                                    <div id="divddlzip1">
                                        <div class="selectbox">
                                        <input type="hidden" name="ddlzip1" id="ddlzip1" value="" disabled="disabled" >
                                        <a class="selectbox-toggle" tabindex="12" role="button" data-toggle="selectbox" href="#">
                                        <span class="selectbox-option input-medium" data-option=""> Select zip</span>
                                        <b class="caret1"></b>
                                        </a>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div id="divddlzip1">
                                        <div class="selectbox">
                                        <input type="hidden" name="ddlzip1" id="ddlzip1" value="<?php echo $arrcombine[10];?>">
                                        <a class="selectbox-toggle" tabindex="12" role="button" data-toggle="selectbox" href="#">
                                        <span class="selectbox-option input-medium" data-option="<?php echo $arrcombine[10];?>"><?php if($arrcombine[10] ==''){ echo "Select zip"; } else {echo $arrcombine[10]; }?></span>
                                        <b class="caret1"></b>
                                        </a>
                                            <div class="selectbox-options">
                                            <input type="text" class="selectbox-filter" placeholder="Search zip" >
                                                <ul role="options">
                                                    <?php 
                                                        $zipqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_zipcode) 
																					FROM itc_state_city 
																					WHERE fld_cityname='".$arrcombine[9]."' AND fld_delstatus=0 
																					ORDER BY fld_zipcode ASC");
                                                       while($rowzip = $zipqry->fetch_object()){?>
                                                                <li><a  href="#" data-option="<?php echo $rowzip->fld_zipcode;;?>"><?php echo $rowzip->fld_zipcode;?></a></li>
                                                        <?php 
                                                        }?>       
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </dt>
                        </dl>
                    </div>
                </div>

                <div class="row rowspacer">
                    <div class="six columns">
                    Select zip<span class="fldreq">*</span> 
                        <dl class='field row' id="zip">
                            <dt class='dropdown'>
                                <div id="divddlzip">
                                    <div class="selectbox">
                                      <input type="hidden" name="ddlzip" id="ddlzip" value="<?php echo $zipcode;?>">
                                      <a class="selectbox-toggle" tabindex="5" role="button" data-toggle="selectbox" href="#">
                                        <span class="selectbox-option input-medium" data-option=""><?php if($editid==0){ echo "Select zip";}else { echo $zipcode; }?></span>
                                        <b class="caret1"></b>
                                      </a>
                                    </div>
                                </div>
                            </dt>
                        </dl> 
                    </div>
                    <div class="six columns">
                    Office number
                        <dl class='field row'>
                            <dt class='text'>
                                <input  id="officeno" name="officeno" placeholder='Office number' tabindex="13" type='text' value="<?php echo $arrcombine[3];?>" >
                            </dt>
                        </dl>
                    </div>
                </div>
                
                <div class="row rowspacer">
                    <div class="six columns">
                    First name<span class="fldreq">*</span> 
                        <dl class='field row'>
                            <dt class='text'>
                                <input id="fname" name="fname"  placeholder='First name' tabindex="6" type='text' value="<?php echo $fname;?>">
                            </dt>
                        </dl> 
                    </div>
                    <div class="six columns">
                    Fax number
                        <dl class='field row'>
                            <dt class='text'>
                                <input id="faxno" name="faxno" placeholder='Fax number' tabindex="14" type='text' value="<?php echo $arrcombine[4];?>">
                            </dt>
                        </dl>
                    </div>
                </div>
                
                <div class="row rowspacer">
                    <div class="six columns">
                    Last name<span class="fldreq">*</span> 
                        <dl class='field row'>
                            <dt class='text'>
                                 <input id="lname" name="lname" placeholder='Last name' tabindex="7" type='text' value="<?php echo $lname;?>">
                            </dt>
                        </dl> 
                    </div>
                    <div class="six columns">
                    Mobile number
                        <dl class='field row'>
                            <dt class='text'>
                                <input id="mobileno" name="mobileno" placeholder='Mobile number' tabindex="15" type='text' value="<?php echo $arrcombine[5];?>">
                            </dt>
                        </dl>
                    </div>
                </div>
                
                <div class="row rowspacer">
                    <div class="six columns">
                    Email-id<span class="fldreq">*</span> 
                        <dl class='field row'>
                            <dt class='text'>
                                 <input id="email" name="email" placeholder='Email-id' tabindex="8" type="text" value="<?php echo $email;?>">
                            </dt>
                        </dl> 
                    </div>
                    <div class="six columns">
                    Home number
                        <dl class='field row'>
                            <dt class='text'>
                               <input id="homeno" name="homeno" placeholder='Home number ' tabindex="16" type='text' value="<?php echo $arrcombine[6];?>">
                            </dt>
                        </dl>
                    </div>
                </div>
                
                <div class="row rowspacer">
                    <div class="three columns">
                        <dl class='field row'>
                            <dt>
                                <div class="upload-ph">
                                    <div class="upload-phleft"><?php if($editid == 0){ ?><img src="img/no-image.png" /> <?php } else { echo $logo1;}?> </div>
                                </div>
                            </dt>
                        </dl>
                    </div>
                    <div class="three columns">
                        <dl class='field row'>
                            <dt>
                                <div>
                                <p><a id="shllogo"> </a></p><br />
                                <div id="queue"> </div>
                                </div>
                            </dt>
                        </dl>
                    </div>
                 	<input type="hidden" name="hiduploadfilelogo" id="hiduploadfilelogo" value="<?php echo $logo;?>" />
                    <div class="three columns">
                        <dl class='field row'>
                            <dt>
                                <div class="upload-ph">
                                    <div class="upload-phright"><?php if($editid == 0){ ?><img src="img/no-image.png" /> <?php } else { echo $pphoto1;}?> </div>
                                </div>
                            </dt>
                        </dl>
                    </div>
                    <div class="three columns">
                        <dl class='field row'>
                            <dt>
                                <div>
                                <p><a id="imgphoto"> </a></p>
                                </div>
                            </dt>
                        </dl>
                    </div>
                 	<input type="hidden" name="hiduploadfile" id="hiduploadfile" value="<?php echo $pphoto;?>" />
            	</div>
                
                <div class="row rowspacer">    
                	<dl class='field row'><div class="title-info">Home licenses</div></dl>
                </div>
                <div class="row" id="addlicensehomeshl"> 
                	<?php 
						$count = 0;
						if($editid==0){?>
                        <div class="row" id="lic1">
                            <div class="row">
                                <div class="four columns">
                                 Licenses<span class="fldreq">*</span> 
                                    <dl class='field row'>
                                        <dt class='dropdown'>
                                            <div class="selectbox">
                                                <input type="hidden" name="ddllic1" id="ddllic1" value="" onchange="$(this).valid()" />
                                                <a class="selectbox-toggle" tabindex="17" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option="">Select License</span><b class="caret1"></b>
                                                </a>
                                                <div class="selectbox-options">
                                                    <input type="text" class="selectbox-filter" placeholder="Search select" />
                                                    <ul role="options">
                                                        <?php 
                                                            $licqry = $ObjDB->QueryObject("SELECT fld_id,fld_license_name AS licensename, 
																							fn_shortname(fld_license_name,2) AS shortname 
																						FROM itc_license_master 
																						WHERE fld_delstatus='0' AND fld_license_type='1' 
																						ORDER BY licensename ASC");
                                                            $i=1;
                                                           while($row = $licqry->fetch_assoc()){
															   extract($row);
															   ?>
                                                                    <li><a tabindex="17" href="#" data-option="<?php echo $fld_id;?>" onclick="fn_licenseclicksp(<?php echo $fld_id;?>,1)" id="option<?php echo $fld_id;?>" title="<?php echo $licensename;?>" class="tooltip"><?php echo $shortname;?> </a></li>
                                                            <?php  $i++;
                                                            }?>       
                                                    </ul>
                                                </div>
                                            </div>
                                        </dt>
                                    </dl>
                                </div>
                                <div class="one columns">
                                    Seats<span class="fldreq">*</span> 
                                    <dl class='field row'>
                                        <dt class='text'>
                                        <input  id="noofusers1" name="noofusers1" placeholder='users' tabindex="18" type='text' value="" onkeyup="ChkValidChar();" readonly />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="two columns">
                                    Start date<span class="fldreq">*</span> 
                                    <dl class='field row'>
                                        <dt class='text'>
                                            <input  id="sdate1" name="sdate1" placeholder='Start Date' tabindex="19" type='text' value="" readonly />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="two columns">
                                    End date<span class="fldreq">*</span> 
                                    <dl class='field row'>
                                        <dt class='text'>
                                            <input  id="edate1" name="edate1" placeholder='End Date' tabindex="20" type='text' value="" readonly />
                                        </dt>
                                    </dl>
                                </div>
                                <div id="grace1"> 
                                                          
                                </div>  
                            </div>                               
                            <input type="hidden" id="currentlicense1" value="" />
                            <div class="row">
                            	<div class='two columns'>
                                    <ul class="field row" onclick="fn_renewalcount('1')">
                                        <li>
                                            <label class="checkbox" for="checkbox1">
                                            <input type="checkbox" id="checkbox1" style="display:none;" value="0" />
                                            <span></span> Auto renewal
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                                <div class='one columns' title="No of times for auto renewal" id="rcountdiv_1" style="display:none;">
                                	<dl class='field row'>
                                        <dt class='text'>
                                           <input type="text" id="renewalcount_1" maxlength="2" value="" />
                                        </dt>
                                    </dl> 
                                    <script>
										$("#renewalcount_1").keypress(function (e) {
											if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
												return false;
											}
										});		
									</script>                               	
                                </div>
                            </div> 
                        </div>
                    <?php }else{
						$distqry = $ObjDB->QueryObject("SELECT a.fld_id AS trackid, a.fld_license_id AS licenseid, a.fld_renewal_count,b.fld_license_name AS licensename,
															a.fld_no_of_users AS totusers, a.fld_start_date AS startdate, 
															a.fld_end_date AS enddate, a.fld_upgrade AS upgradeflag, a.fld_ipl_count AS iplcount, 
															a.fld_mod_count AS modcount, a.fld_auto_renewal AS renewal 
														FROM itc_license_track AS a 
														LEFT JOIN itc_license_master AS b ON b.fld_id=a.fld_license_id
														WHERE a.fld_district_id='0' AND a.fld_school_id='".$editid."' AND a.fld_user_id='0' AND a.fld_delstatus='0'");						
						while($res = $distqry->fetch_assoc()){
							extract($res);
							$count++;
						?>                      
                    	<div class="row" id="lic<?php echo $count; ?>">
                            <div class="row">
                                <div class="four columns">
                                 Licenses<span class="fldreq">*</span> 
                                    <dl class='field row'>
                                        <dt class='dropdown'>
                                            <div class="selectbox">
                                                <input type="hidden" name="ddllic<?php echo $count; ?>" id="ddllic<?php echo $count; ?>" value="<?php echo $licenseid; ?>,<?php echo $trackid; ?>" onchange="$(this).valid()" />
                                                <a class="selectbox-toggle" tabindex="17" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option="" title="<?php echo $licensename;?>"><?php echo $licensename;?></span><b class="caret1"></b>
                                                </a>                                                
                                            </div>
                                        </dt>
                                    </dl>
                                </div>
                                <div class="one columns">
                                    Seats<span class="fldreq">*</span> 
                                    <dl class='field row'>
                                        <dt class='text'>
                                        <input  id="noofusers<?php echo $count; ?>" name="noofusers<?php echo $count; ?>" placeholder='users' tabindex="18" type='text' value="<?php echo $totusers; ?>" onblur="fn_chkusercount(<?php echo $count.",".$trackid; ?>)" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="two columns">
                                    Start date<span class="fldreq">*</span> 
                                    <dl class='field row'>
                                        <dt class='text'>
                                            <input  id="sdate<?php echo $count; ?>" name="sdate<?php echo $count; ?>" placeholder='Start Date' tabindex="19" type='text' value="<?php echo date("m/d/Y", strtotime($startdate)); ?>" readonly />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="two columns">
                                    End date<span class="fldreq">*</span> 
                                    <dl class='field row'>
                                        <dt class='text'>
                                            <input  id="edate<?php echo $count; ?>" name="edate<?php echo $count; ?>" placeholder='End Date' tabindex="20" type='text' value="<?php echo date("m/d/Y", strtotime($enddate)); ?>" readonly />
                                        </dt>
                                    </dl>
                                </div>
                                
<!-- updated by Mohan M 30-4-2015 -->                              
<script>							
    $( "#sdate"+<?php echo $count; ?>).datepicker({
        
         onSelect: function(dateText,inst){	
                var ssdate=$("#sdate"+<?php echo $count; ?>).val();
                var eedate=$("#edate"+<?php echo $count; ?>).val();
               // alert(ssdate+" "+eedate); 
                if( (new Date(ssdate).getTime() > new Date(eedate).getTime()))
                {
                    var data ="Start date is greated then End date. Try again";	  

                    $.Zebra_Dialog("<div style='text-align:left'>"+data+"</div>",
                    {
                    'type':     'confirmation',
                    'buttons':  [
                              {caption: 'OK', callback: function() {
                                    $("#sdate"+<?php echo $count; ?>).val('');
                              }},
                          ]
                    });
                    $('.ZebraDialog').css({"posiion":"absolute","left":"50%","top":"50%","transform":"translate(-50%,-50%)","width":"443px"});
                    closeloadingalert();
            }
          }
        
        }
    );
    
    $( "#edate"+<?php echo $count; ?>).datepicker({
       minDate: '-currentdate',
            onSelect: function(selected){
             $(this).parents().parents().removeClass('error');
            }
    });
</script>
  <!-- updated by Mohan M 30-4-2015 --> 
                                <div id="grace<?php echo $count; ?>"> 
                                <?php                                       
                                     if($iplcount>0){
                                    ?>
                                    
                                    <div class="one columns" style="padding-left:15px;" title="Significant Content Experience">
                                        IPl<span class="fldreq">*</span>                       	
                                        <dl class='field row'>
                                            <dt class='text'>
                                                <input  id="iplcount<?php echo $count; ?>" name="iplcount<?php echo $count; ?>" placeholder='IPL' tabindex="21" type='text' value="<?php echo $iplcount; ?>" maxlength="2" />
                                            </dt>
                                        </dl>
                                    </div>  
                                    <?php } if($modcount>0){?> 
                                    <div class="one columns" <?php if($iplcount==0){ ?>style="padding-left:15px;" <?php }?> title="Significant Content Experience">
                                        Modules<span class="fldreq">*</span>                          	
                                        <dl class='field row'>
                                            <dt class='text'>
                                                <input  id="modcount<?php echo $count; ?>" name="modcount<?php echo $count; ?>" placeholder='module' tabindex="22" type='text' value="<?php echo $modcount; ?>" maxlength="2" />
                                            </dt>
                                        </dl>
                                    </div> 
                                    <?php 
                                    }?>                    
                                </div> 
                                <?php if($count!=1 && date("Y-m-d", strtotime($startdate)) > date("Y-m-d")){ ?> 
                                    <div class='one columns'  style=" <?php if($iplcount==0 && $modcount!=0){ ?> padding-left:8px; <?php } if($renewal==1){?>display:none;<?php } ?>">
                                        remove
                                        <p class='btn twelve columns'>
                                            <a onclick="if(confirm('Are you sure want to delete this license?')){fn_removesplicense(<?php echo $count;?>,1,<?php echo $trackid; ?>);}" id="rmove"> - </a>
                                        </p>     
                                    </div> 
                                <?php } else if($upgradeflag==1 && date("Y-m-d", strtotime($startdate)) < date("Y-m-d")){?>
                                    <div class='one columns' id="upgrade_<?php echo $trackid; ?>" <?php if($renewal==1){?> style="display:none;"<?php } ?>  style=" <?php if($iplcount==0 && $modcount!=0){ ?> padding-left:8px; <?php } if($renewal==1){?>display:none;<?php } ?>">
                                        upgrade
                                        <p class='btn twelve columns'>
                                        	<a onclick="fn_upgrade(<?php echo $licenseid.",".$trackid; ?>)" id="rmove"> ↑ </a>
                                        </p>     
                                    </div> 

                                <?php }?>
                            </div>                                                          
                            <input type="hidden" id="currentlicense<?php echo $count; ?>" value="<?php echo $licenseid; ?>" />
                            <input type="hidden" id="errorcount<?php echo $count; ?>" value="0" />
                            <div class="row">
                            	<div class='two columns'> 
                                    <ul class="field row" onclick="fn_clickrenewal(<?php echo $count; ?>,<?php echo $trackid; ?>);fn_renewalcount(<?php echo $count; ?>)">
                                        <li>
                                            <label class="checkbox <?php if($renewal==1) echo "checked";?>" for="checkbox<?php echo $count; ?>">
                                            <input type="checkbox" id="checkbox<?php echo $count; ?>" style="display:none;" value="0" <?php if($renewal==1){?>checked="checked"<?php }?> />
                                            <span></span> Auto renewal
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                                <div class='one columns' title="No of times for auto renewal" id="rcountdiv_<?php echo $count;?>" <?php if($renewal==0){?>style="display:none;"<?php }?>>
                                	<dl class='field row'>
                                        <dt class='text'>
                                           <input type="text" id="renewalcount_<?php echo $count; ?>" maxlength="2" value="<?php echo $fld_renewal_count; ?>" />
                                        </dt>
                                    </dl> 
                                    <script>
										$("#renewalcount_<?php echo $count; ?>").keypress(function (e) {
											if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
												return false;
											}
										});		
									</script>                               	
                                </div>
                            </div> 
                        </div>
                        <script>
							$("#noofusers<?php echo $count; ?>").keypress(function (e) {
								if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
									return false;
								}
							});		
						</script>
						<?php if(date("Y-m-d", strtotime($startdate)) > date("Y-m-d")){?>
							<script>							
                                $( "#sdate"+<?php echo $count; ?>).datepicker({
                                    minDate: new Date,
                                    onSelect: function(dateText,inst){	
                                        fn_endate(<?php echo $count; ?>);
                                    }
                                });
                            </script>
                        <?php }?>
                    <?php }
					}?>
                </div>
                    
                <div class="row rowspacer">
                    <div class="four columns">
                        <p class='btn medium <?php if($totalhidlicense==$licensecount or $editid==0) echo "dim";?>' id="add">
                            <a onclick="addlicsp($('#hidaddlicense').val());">Add another License</a>
                        </p> 
                    </div>
                </div>
            	<input type="hidden" id="hidaddlicense" value="<?php if($editid==0)echo '1'; else echo $count;?>" />
                <input type="hidden" id="hidspid" value="<?php echo $editid; ?>" />
                <input type="hidden" id="hidtotallicense" value="<?php echo $totalhidlicense; ?>" /> 
                    
                <div class='row rowspacer'>
                    <div class='twelve columns'>
                    	To create new tag, type a name and press Enter.
                        <div class="tag_well">
                            <input type="text" name="test3" tabindex="21" value="" id="form_tags_newschoolp" />
                        </div>
                    </div>
                </div>

				<script language="javascript" type="text/javascript">
				/* ----- For Profile picture ------*/
					<?php $timestamp = time();?>
						$('#imgphoto').uploadify({
									'formData'     : {
										'timestamp' : '<?php echo $timestamp;?>',
										'token'     : '<?php echo md5('nanonino' . $timestamp);?>',
										'oper'      : 'profile-pic' 
									},
									 'height': 40,
									 'width':160,
									 'queueID' : 'queue',
									'fileSizeLimit' : '2MB',
									'swf'      : 'uploadify/uploadify.swf',
									'uploader' : '<?php echo _CONTENTURL_;?>uploadify.php',
									'multi':false,
									'buttonText' : 'Upload Photo',
									'removeCompleted' : true,
									'fileTypeExts' : '*.gif; *.jpg; *.png; *.jpeg; *.bmp;',
									'onUploadSuccess' : function(file, data, response) {
										$('#hiduploadfile').val(data);
										$('.upload-phright').html('<img src="thumb.php?src=<?php echo __CNTPPPATH__; ?>'+data+'&w=100&h=106&q=100" />');
										$('#userphoto').removeClass('dim');   
                               
                                     },
									 'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
                                       $('#userphoto').addClass('dim');   
                                    }
									
								});
				/* ----- For School Logo ------*/			
					$('#shllogo').uploadify({
									'formData'     : {
										'timestamp' : '<?php echo $timestamp;?>',
										'token'     : '<?php echo md5('nanonino' . $timestamp);?>',
										'oper'      : 'school-logo' 
									},
									 'height': 40,
									 'width':160,
									'fileSizeLimit' : '2MB',
									'swf'      : 'uploadify/uploadify.swf',
									'uploader' : '<?php echo _CONTENTURL_;?>uploadify.php',
									'multi':false,
									'buttonText' : 'Upload Photo',
									'removeCompleted' : true,
									'fileTypeExts' : '*.gif; *.jpg; *.png; *.jpeg; *.bmp;',
									'onUploadSuccess' : function(file, data, response) {
										$('#hiduploadfilelogo').val(data);
										$('.upload-phleft').html('<img src="thumb.php?src=<?php echo __CNTSLPATH__; ?>'+data+'&w=100&h=106&q=100" />');
										$('#userphoto').removeClass('dim');   
                               
                                     },
									 'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
                                       $('#userphoto').addClass('dim');   
                                    }
									
								});
					$('#officeno,#faxno,#mobileno,#homeno').mask('(999) 999-9999');			
                </script>
                <div class="row rowspacer">
                    <div class="six columns">
                        <p class='btn primary twelve columns'>
                          <a tabindex="20" onclick="fn_cancel('users-schoolpurchase');">Cancel</a>
                        </p>
                    </div>
                    <div class="six columns" id="userphoto">
                        <p class='btn secondary twelve columns'>                             
                              <a tabindex="21" onclick="fn_createschoolpurchase(<?php echo $editid; ?>);"><?php if($editid==0) echo "Create School"; else echo "Update School"; ?></a>
                        </p>
                    </div>
                </div>
            </form>            
            <script type="text/javascript" language="javascript">
            	$("#noofusers1").ForceNumericOnly();
				$(function(){
					$("#shlp").validate({
						ignore: "",
						errorElement: "dd",
						errorPlacement: function(error, element) {					
							
							if($(element).attr("class").replace(" hasDatepicker",'') == "quantity error"){	
								var temp = $(element).attr('id');
								if(temp.charAt(0)=='n'){
									var msg ='Please enter no of users';
									var style = 1;
								}
								else if(temp.charAt(0)=='d'){
									var msg ='Please select the license';
									var style = 3;
								}
								else {
									var msg ='Please select start date';
									var style = 2;
								}				
								$(element).parents('dl').addClass('error');
								error.appendTo($(element).parents('dl'));
								if(style==1){
									error.addClass('msg');
								}
								else if(style==3){
									error.addClass('msg');
								}
								else{
									error.addClass('msg');
								}							
								error.html(msg+'<span class="caret"></span>');
							}
							else {
								$(element).parents('dl').addClass('error');
								error.appendTo($(element).parents('dl'));
								error.addClass('msg');
							}	
						},
						rules: {
							shlname: { required: true, lettersonly: true, 
									remote:{ 
											url: "users/schoolpurchase/users-schoolpurchase-newschoolpurchasedb.php", 
											type:"POST",  
											data: {  
													sid: function() {
													return '<?php echo $editid;?>';},
													oper: function() {
													return 'checkshlname';}
													  
											 },
											 async:false 
									   }},
							address: { required: true,letterswithbasicpunc:true },
							address1: { letterswithbasicpunc:true },
							fname: { required: true, lettersonly: true },
							lname: { required: true, lettersonly: true },
							email: { required: true, email: true },
                            hubid: { required: false, number: true},
							ddlstate : { required: true },
							ddlcity : { required: true },
							ddlzip : { required: true }
						},
						messages: {
							shlname: { required: "please enter the school name", remote: "School name already exists" },
							address: { required: "please enter the school address" },
							fname: { required: "please enter the first name" },
							lname: { required: "please enter the last name" },
							email: { required: "please enter the Email-id", email: "Invalid email-id" },
							ddlstate : { required: "please select state" },
							ddlcity : { required: "please select city" },
							ddlzip : { required: "please select zip" }
							
						},
						highlight: function(element, errorClass, validClass) {
							$(element).parents('dl').addClass(errorClass);
							$(element).addClass(errorClass).removeClass(validClass);
						},
						unhighlight: function(element, errorClass, validClass) {
						if($(element).attr('class') == 'error' || $(element).attr('class') == 'quantity error'){
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
