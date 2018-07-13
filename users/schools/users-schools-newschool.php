<?php
	@include("sessioncheck.php");
	
	$oper = isset($method['oper']) ? $method['oper'] : '';
	$editid =  isset($method['id']) ? $method['id'] : '';
	
	/****declaration part****/
	$distname='';
	$shlname='';
	$staddress='';
	$fname='';
	$lname='';
	$email='';
	$pphoto='';
	$hubid="";
	$logo='';
	$shldistid='';
	$arrcombine=array('','','','','','','','','','','','','','');

	$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname 
								FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
								WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='14' AND b.fld_access='1' AND a.fld_created_by='".$uid."' AND a.fld_delstatus='0' 
									AND b.fld_item_id='".$editid."'");
	
	
	
	$licensecount=1;
	$totalhidlicense=0;
	
	if($editid != 0){
		$id=explode(",",$editid);
		$editid = $id[0];
		$shldistid = $id[1];
		
		if($shldistid == 0){	
			$selectshl = $ObjDB->QueryObject("SELECT a.fld_district_id AS distid, a.fld_school_name AS shlname, a.fld_hubid AS hubid, a.fld_school_admin_id AS shluid, a.fld_school_logo AS logo, 
												a.fld_street_address AS staddress, a.fld_state AS state, a.fld_city AS city, a.fld_zipcode AS zipcode, 
												c.fld_statename AS statename,d.fld_email AS email,d.fld_fname AS fname, d.fld_lname AS lname, fld_profile_pic AS pphoto 
											FROM itc_school_master AS a 
											RIGHT JOIN itc_state_city AS c ON a.fld_state=c.fld_statevalue
											RIGHT JOIN itc_user_master AS d ON a.fld_school_admin_id=d.fld_id 
											WHERE a.fld_id='".$editid."' AND a.fld_delstatus='0' 
											GROUP BY a.fld_id");
		
		$distname = "School Purchase";	
		}
		else{			
			$selectshl = $ObjDB->QueryObject("SELECT a.fld_district_id AS distid, a.fld_school_name AS shlname, a.fld_hubid AS hubid, a.fld_school_admin_id AS shluid, a.fld_school_logo AS logo, 
												a.fld_street_address AS staddress, a.fld_state AS state, a.fld_city AS city, a.fld_zipcode AS zipcode,
												b.fld_district_name AS distname,c.fld_statename AS statename,d.fld_email AS email, d.fld_fname AS fname, 
												d.fld_lname AS lname, fld_profile_pic AS pphoto 
											FROM itc_school_master AS a 
											RIGHT JOIN itc_district_master AS b ON a.fld_district_id=b.fld_id 
											RIGHT JOIN itc_state_city AS c ON a.fld_state=c.fld_statevalue 
											RIGHT JOIN itc_user_master AS d ON a.fld_school_admin_id=d.fld_id
											WHERE a.fld_id='".$editid."' AND a.fld_delstatus='0' 
											GROUP BY a.fld_id");
		}
			
			
			
			$row=$selectshl->fetch_assoc();
			extract($row);
			$totalhidlicense = $ObjDB->SelectSingleValueInt("SELECT COUNT(DISTINCT(fld_license_id)) 
															FROM itc_license_track 
															WHERE fld_district_id='".$shldistid."' AND fld_school_id=0 AND fld_delstatus='0' AND '".date("Y-m-d")."' 
															BETWEEN fld_start_date AND fld_end_date");
			
			$licensecount = $ObjDB->SelectSingleValueInt("SELECT COUNT(DISTINCT(fld_license_id)) 
														FROM itc_license_track 
														WHERE fld_district_id='".$shldistid."' AND fld_school_id='".$editid."' AND fld_delstatus='0'");
			
			if($logo == '' or $logo == 'no-image.png'){ $logo1 = "<img src='img/no-image.png'/>";}
			else{ $logo1 = "<img src=thumb.php?src=".__CNTSLPATH__.$logo."  width='100' height='100' /> "; }
			
			if($pphoto == '' or $pphoto == 'no-image.png'){ $pphoto1 = "<img src='img/no-image.png'/>"; }
			else{ $pphoto1 = "<img src=thumb.php?src=".__CNTPPPATH__.$pphoto."  width='100' height='100' /> "; }
			
			
			
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
	
	if($sessmasterprfid == 6){
		$selectdist = $ObjDB->QueryObject("SELECT fld_id as id, fld_district_name as distname, fld_state AS distsatate , fld_city AS distcity, fld_zipcode AS distzip 
										FROM itc_district_master 
										WHERE fld_id='".$sendistid."' AND fld_delstatus='0'");
		$row1=$selectdist->fetch_assoc();
		extract($row1);
		
		$diststatename = $ObjDB->SelectSingleValue("SELECT DISTINCT(fld_statename) 
													FROM itc_state_city 
													WHERE fld_statevalue='".$distsatate."'");
		
	}
	
	if($sessmasterprfid == 6){
		$temp = 1;
	}
	else{
		$temp = 0;
	}
?>
<script type="text/javascript" charset="utf-8">		
	$.getScript("users/schools/users-schools-newschool.js").done(function(script, textStatus) {
  		<?php if($sessmasterprfid == 6 and $editid==0) {?>
			addlicshl(<?php echo $id;?>,1);
		<?php } ?>
	});
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_newschool', 
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
<section data-type='#users' id='users-schools-newschool'>
	<div class='container'>
        <div class='row'>
          <div class='twelve columns'>
          	<p class="dialogTitle"><?php if($editid == 0){ echo "New School";} else { echo $shlname." "."School";} ?></p>
            <p class="dialogSubTitleLight">&nbsp;</p>
          </div>
        </div>
    
        <div class='row formBase'>
            <div class='eleven columns centered insideForm'>
                <form method='post' name="shlval" id="shlval">
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
                                <input  id="address1" name="address1" placeholder='Street address' tabindex="10" type='text' value="<?php echo $arrcombine[7];?>">
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
                                    <a class="selectbox-toggle" tabindex="11" role="button" data-toggle="selectbox" href="#">
                                    <span class="selectbox-option input-medium" data-option="<?php if($editid==0){ echo "0";} else {echo $arrcombine[8];}?>"><?php if($editid == 0 or $arrcombine[8] == ''){ echo "Select state";} else {echo $statename1;}?></span>
                                    <b class="caret1"></b>
                                    </a>
                                    <div class="selectbox-options">
                                    <input type="text" class="selectbox-filter" placeholder="Search state" >
                                        <ul role="options">
                                        <?php 
                                            $stateqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_statevalue) AS statevalue, fld_statename AS statename2 
																			FROM itc_state_city 
																			WHERE fld_delstatus=0 
																			ORDER BY fld_statename ASC");
                                            while($rowstate = $stateqry->fetch_assoc()){ 
											extract($rowstate);
											?>
                                                    <li><a href="#" data-option="<?php echo $statevalue;?>"><?php echo $statename2;?></a></li>
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
                                <?php if($sessmasterprfid == 6){ ?>
                                    <div class="selectbox">
                                        <input type="hidden" name="ddlstate" id="ddlstate" value="<?php echo $distsatate;?>">
                                        <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                            <span class="selectbox-option input-medium" data-option=""><?php echo $diststatename;?></span>
                                        </a>
                                    </div> 
                                <?php } 
                                else {
                                ?>
                                    <div class="selectbox">
                                        <input type="hidden" name="ddlstate" id="ddlstate" value="<?php echo $state;?>" onchange="$('#ddlstate').valid();fn_changecity(this.value);">
                                        <a class="selectbox-toggle"  tabindex="3" role="button" data-toggle="selectbox" href="#">
                                        <span class="selectbox-option input-medium" data-option="<?php if($editid==0){ echo "0";} else {echo $state;}?>"><?php if($editid == 0){ echo "Select state";} else {echo $statename;}?></span>
                                        <b class="caret1"></b>
                                        </a>
                                        <?php if($editid ==0) { ?>
                                            <div class="selectbox-options">
                                            <input type="text" class="selectbox-filter" placeholder="Search state" >
                                                <ul role="options">
                                                <?php 
                                                $stateqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_statevalue) AS statevalue, fld_statename AS statename 
																				FROM itc_state_city 
																				WHERE fld_delstatus=0 
																				ORDER BY fld_statename ASC");
                                                while($rowstate = $stateqry->fetch_assoc()){ 
												extract($rowstate);
												?>
                                                        <li><a href="#" data-option="<?php echo $statevalue;?>"><?php echo $statename;?></a></li>
                                                <?php 
                                                }?>       
                                                </ul>
                                            </div>
                                        <?php } ?>
                                    </div> 
                                <?php } ?>
                            </dt>
                        </dl> 
                    </div>
                    <div class="six columns">
                    Select city
                        <dl class='field row'>
                            <dt class='dropdown'>
                                <?php if($editid == 0){ ?>
                                    <div id="divddlcity1">
                                        <div class="selectbox">
                                            <input type="hidden" name="ddlcity1" id="ddlcity1" value="" >
                                            <a class="selectbox-toggle" tabindex="12" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option=""> Select city</span>
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
                                                $cityqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_cityname) AS cityname  
																				FROM itc_state_city 
																				WHERE fld_statevalue='".$arrcombine[8]."' AND fld_delstatus=0 
																				ORDER BY fld_cityname ASC");
                                               while($rowcity = $cityqry->fetch_assoc()){
												   extract($rowcity);
												   ?>
                                                        <li><a tabindex="1" href="#" data-option="<?php echo ucwords(strtolower($cityname));?>"><?php echo  ucwords(strtolower($cityname))?></a></li>
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
                        <?php if($sessmasterprfid == 6){ ?>
                          <dl class='field row'>
                                <dt class="dropdown">     
                                <div class="selectbox">
                                  <input type="hidden" name="ddlcity" id="ddlcity" value="<?php echo $distcity;?>">
                                  <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                    <span class="selectbox-option input-medium" data-option=""><?php echo $distcity;?></span>
                                    <b class="caret1"></b>
                                    </a>
                                </div> 
                                </dt>
                            </dl>
                        <?php } 
                        else {?>
                             <dl class='field row' id="cit">
                                <dt class="dropdown">
                                    <div id="divddlcity">
                                        <div class="selectbox" >
                                          <input type="hidden" name="ddlcity" id="ddlcity" value="<?php echo $city;?>" >
                                          <a class="selectbox-toggle"  tabindex="5" role="button" data-toggle="selectbox" href="#">
                                            <span class="selectbox-option input-medium" data-option=""><?php if($editid == 0) { echo "Select city"; } else { echo $city; }?></span>
                                            <b class="caret1"></b>
                                          </a>
                                        </div>
                                    </div>
                                </dt>
                            </dl>
                        <?php } ?>  
                    </div>
                    <div class="six columns">
                     Select zip
                        <dl class='field row'>
                            <dt class='dropdown'>
                                 <?php if($editid == 0){ ?>
                                  <div id="divddlzip1">
                                        <div class="selectbox" >
                                          <input type="hidden" name="ddlzip1" id="ddlzip1" value="" disabled="disabled" >
                                          <a class="selectbox-toggle" tabindex="14" role="button" data-toggle="selectbox" href="#">
                                            <span class="selectbox-option input-medium"  data-option="">Select zip</span>
                                            <b class="caret1"></b>
                                          </a>
                                        </div>
                                  </div>
                                  <?php } else { ?>
                                    <div id="divddlzip1">
                                        <div class="selectbox">
                                          <input type="hidden" name="ddlzip1" id="ddlzip1" value="<?php echo $arrcombine[10];?>">
                                          <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                            <span class="selectbox-option input-medium" data-option="<?php echo $arrcombine[10];?>"><?php if($arrcombine[10] ==''){ echo "Select zip"; } else {echo $arrcombine[10]; }?></span>
                                            <b class="caret1"></b>
                                          </a>
                                          <div class="selectbox-options">
                                            <input type="text" class="selectbox-filter" placeholder="Search zip" >
                                            <ul role="options">
                                                <?php 
                                                    $zipqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_zipcode) AS zipcode 
																					FROM itc_state_city 
																					WHERE fld_cityname='".$arrcombine[9]."' AND fld_delstatus=0 
																					ORDER BY fld_zipcode ASC");
                                                   while($rowzip = $zipqry->fetch_assoc()){
													   extract($rowzip);
													   ?>
                                                            <li><a tabindex="1" href="#" data-option="<?php echo $zipcode;;?>"><?php echo $zipcode;?></a></li>
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
                    Select district<span class="fldreq">*</span> 
                        <dl class='field row' id="dit">
                            <?php if($sessmasterprfid == 6){ ?>
                            <dt class="dropdown">     
                            <div class="selectbox">
                              <input type="hidden" name="ddldist" id="ddldist" value="<?php echo $sendistid;?>">
                              <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#" >
                                <span class="selectbox-option input-medium" data-option=""><?php echo $distname;?></span>
                                <b class="caret1"></b>
                                </a>
                            </div> 
                            </dt>
                            <?php } 
                            else {?>
                            <dt class="dropdown">
                                <div id="divddldist">
                                <div class="selectbox">
                                  <input type="hidden" name="ddldist" id="ddldist" value="<?php echo $distid;?>">
                                  <a class="selectbox-toggle"  tabindex="6" role="button" data-toggle="selectbox" href="#">
                                    <span class="selectbox-option input-medium"  data-option=""><?php if($editid == 0){ echo "Select district"; } else { echo $distname; } ?> </span>
                                    <b class="caret1"></b>
                                  </a>
                                </div>
                                </div>
                            </dt>
                         <?php } ?> 
                        </dl> 
                    </div>
                    <div class="six columns">
                    Office number
                        <dl class='field row'>
                            <dt class='text'>
                                 <input  id="officeno" name="officeno" placeholder='Office number'  tabindex="15" type='text' value="<?php echo $arrcombine[3];?>" >
                            </dt>
                        </dl>
                    </div>
                </div>
                
                <div class="row rowspacer">
                    <div class="six columns">
                    First name<span class="fldreq">*</span>
                        <dl class='field row'>
                            <dt class='text'>
                             <input id="fname" name="fname"  placeholder='First name' tabindex="7" type='text' value="<?php echo $fname;?>">
                            </dt>
                        </dl>
                    </div>
                    <div class="six columns">
                    Fax number
                        <dl class='field row'>
                            <dt class='text'>
                                <input id="faxno" name="faxno" placeholder='Fax number' tabindex="16" type='text' value="<?php echo $arrcombine[4];?>">
                            </dt>
                        </dl>
                    </div>
                </div>
                
                <div class="row rowspacer">
                    <div class="six columns">
                    Last name<span class="fldreq">*</span>
                        <dl class='field row'>
                            <dt class='text'>
                                <input id="lname" name="lname" placeholder='Last name' tabindex="8" type='text' value="<?php echo $lname;?>">
                            </dt>
                        </dl>
                    </div>
                    <div class="six columns">
                    Mobile number
                        <dl class='field row'>
                            <dt class='text'>
                                <input id="mobileno" name="mobileno" placeholder='Mobile number' tabindex="17" type='text' value="<?php echo $arrcombine[5];?>">
                            </dt>
                        </dl>
                    </div>
                </div>  
                
                <div class="row rowspacer">
                    <div class="six columns">
                    Email-id<span class="fldreq">*</span>
                        <dl class='field row'>
                            <dt class='text'>
                                 <input id="email" name="email" placeholder='Email-id' tabindex="9" type="text" value="<?php echo $email;?>">
                            </dt>
                        </dl>
                    </div>
                    <div class="six columns">
                    Home number
                        <dl class='field row'>
                            <dt class='text'>
                                <input id="homeno" name="homeno" placeholder='Home number' tabindex="18" type='text' value="<?php echo $arrcombine[6];?>">
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
                                <p><a id="shllogo"> </a></p><br />
                                 <div id="queue1"> </div>
                                <input type="hidden" name="hiduploadfilelogo" id="hiduploadfilelogo" value="<?php echo $logo;?>" />
                            </dt>
                        </dl>
                    </div>
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
                                <p><a id="imgphoto"> </a></p><br />
                                 <div id="queue"> </div>
                                 <input type="hidden" name="hiduploadfile" id="hiduploadfile" value="<?php echo $pphoto;?>" />
                            </dt>
                        </dl>
                    </div>
                </div>
                
                <div class="row rowspacer">
                    <dl class='field row'>
                        <div class="title-info">School licenses</div>
                    </dl>
                    <?php 
					$count = 0;
                        if($editid != 0) {					
                            $distqry = $ObjDB->QueryObject("SELECT fld_id as trackid, fld_license_id AS licenseid, fld_distlictrack_id as dtrackid, 
														(SELECT a.fld_license_name FROM itc_license_master AS a WHERE a.fld_id=fld_license_id) AS licensename,
														(SELECT fn_shortname(a.fld_license_name,2) FROM itc_license_master AS a WHERE a.fld_id=fld_license_id) AS shortname, 
															fld_no_of_users AS totusers, fld_start_date AS startdate, fld_end_date AS enddate, 
															fld_upgrade AS upgradeflag, fld_ipl_count AS iplcount, fld_mod_count AS modcount, 
															fld_auto_renewal AS renewal 
														FROM itc_license_track 
														WHERE fld_school_id='".$editid."' AND fld_user_id='0' AND fld_delstatus='0'");						
						while($res = $distqry->fetch_assoc()){
							extract($res);
							$count++;
						?>                      
                    	<div class="row" id="lic<?php echo $count; ?>">
                            <div class="row">
                                <div class="four columns">                                 
                                 Licenses<span class="fldreq">*</span> &nbsp;&nbsp;Available student seats: <?php echo $ObjDB->SelectSingleValueInt("SELECT fld_remain_users FROM itc_license_track WHERE fld_id='".$dtrackid."'");?>
                                    <dl class='field row'>
                                        <dt class='dropdown'>
                                            <div class="selectbox">
                                                <input type="hidden" name="ddllic<?php echo $count; ?>" id="ddllic<?php echo $count; ?>" value="<?php echo $licenseid; ?>,<?php echo $trackid; ?>,<?php echo $dtrackid; ?>" onchange="$(this).valid()" />
                                                <a class="selectbox-toggle" tabindex="17" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option="" title="<?php echo $licensename;?>"><?php echo $shortname; ?></span><b class="caret1"></b>
                                                </a>                                                
                                            </div>
                                        </dt>
                                    </dl>
                                </div>
                                <div class="one columns">
                                    Seats<span class="fldreq">*</span> 
                                    <dl class='field row'>
                                        <dt class='text'>
                                        <input  id="noofusers<?php echo $count; ?>" name="noofusers<?php echo $count; ?>" placeholder='users' tabindex="18" type='text' value="<?php echo $totusers; ?>" onblur="fn_chkusercountshl(<?php echo $count.",".$trackid; ?>)" />
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
                                <?php $drenewal = $ObjDB->SelectSingleValueInt("SELECT fld_auto_renewal 
																				FROM itc_license_track 
																				WHERE fld_id='".$dtrackid."'");
								
									  $upgradeid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_license_track 
									  											WHERE fld_license_id='".$licenseid."' AND fld_district_id='".$distid."' 
																					AND fld_school_id=0 AND fld_delstatus='0' 
																					AND fld_start_date > '".date("Y-m-d",strtotime($enddate))."' 
																				ORDER BY fld_id DESC LIMIT 0,1");	
								if($count!=1 && date("Y-m-d", strtotime($startdate)) > date("Y-m-d")){ ?> 
                                    <div class='one columns'>
                                        remove
                                        <p class='btn twelve columns'>
                                            <a onclick="if(confirm('Are you sure want to delete this license?')){fn_removeshllicense(<?php echo $count;?>,1,<?php echo $trackid; ?>);}" id="rmove"> - </a>
                                        </p>     
                                    </div> 
                                <?php } else if($upgradeflag==1 && date("Y-m-d", strtotime($startdate)) < date("Y-m-d") && $drenewal==0 && $sessmasterprfid==2 && $upgradeid!=0){?>
                                    <div class='one columns' id="upgrade_<?php echo $upgradeid; ?>">
                                        upgrade
                                        <p class='btn twelve columns'>
                                        	<a onclick="fn_upgrade(<?php echo $licenseid.",".$upgradeid.",".$trackid; ?>)"> ↑ </a>
                                        </p>     
                                    </div> 

                                <?php }?>
                            </div>                                                          
                            <input type="hidden" id="currentlicense<?php echo $count; ?>" value="<?php echo $licenseid; ?>" />
                            <input type="hidden" id="errorcount<?php echo $count; ?>" value="0" />                           
                        </div>
						<script>
							$("#noofusers<?php echo $count; ?>").keypress(function (e) {
								if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
									return false;
								}
							});		
						</script>
                    <?php }
                        } // if ends
                    ?>
                </div>
                <div class="row rowspacer" id="addlicenseshl">
                </div>
                <div class="row rowspacer">
                    <div class="four columns">
                        <p class='btn medium <?php if($totalhidlicense==$licensecount or $editid==0) echo "dim";?>' id="add">
                            <a onclick="addlicshl($('#ddldist').val(),$('#hidaddlicense').val());">Add another License</a>
                        </p> 
                    </div>
                </div>
                <input type="hidden" id="hidaddlicense" value="<?php if($editid==0)echo '1'; else echo $count;?>" />
                <input type="hidden" id="hidtotallicense" value="<?php echo $totalhidlicense;?>" />
                <input type="hidden" id="hidschoolid" value="<?php echo $editid; ?>" />
                <div class="row rowspacer">
                    <div class='twelve columns'>
                        To create new tag, type a name and press Enter.
                        <dl class='field row'>
                            <div class="tag_well">
                                <input type="text" name="test3" value="" id="form_tags_newschool" />
                            </div>
                        </dl>
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
									 'queueID' : 'queue1',
									'fileSizeLimit' : '2MB',
									'swf'      : 'uploadify/uploadify.swf',
									'uploader' : '<?php echo _CONTENTURL_;?>uploadify.php',
									'multi':false,
									'buttonText' : 'Upload Logo',
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
                          <a onclick="fn_cancel('users-schools')">Cancel</a>
                        </p>
                    </div>
                    <div class="six columns" id="userphoto">
                        <p class='btn secondary twelve columns'>
                             <?php if($editid == 0){ ?>
                              <a id="butstatus" onclick="fn_createschool(<?php echo $temp;?>,<?php echo $editid; ?>);">Create School</a>
                            <?php } else {?> 
                              <a onclick="fn_createschool(<?php echo $temp;?>,<?php echo $editid; ?>);">Update School</a>
                            <?php }?>
                        </p>
                    </div>
                </div>
                </form>
                
                 <script type="text/javascript" language="javascript">
                    $("#noofusers1").ForceNumericOnly();
                    $(function(){
                        $("#shlval").validate({
                            ignore: "",
                            errorElement: "dd",
                            errorPlacement: function(error, element) {
                                if($(element).attr("class").replace(" hasDatepicker",'') == "quantity error"){	
                                    var temp = $(element).attr('id');
                                    if(temp.charAt(0)=='n'){
                                        var msg ='Enter no of users';
                                        var style = 1;
                                    }
                                    else if(temp.charAt(0)=='d'){
                                        var msg ='Select the license';
                                        var style = 3;
                                    }
                                    else {
                                        var msg ='Select start date';
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
                                    error.html(msg);
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
											url: "users/schools/users-schools-newschooldb.php", 
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
                                hubid: { required: false, number: true },
                                ddlstate : { required: true },
                                ddlcity : { required: true },
                                ddldist : { required: true }
                            },
                            messages: {
                                shlname: { required: "please enter the school name", remote: "School name already exists" },
                                address: { required: "please enter the school address" },
                                fname: { required: "please enter the first name" },
                                lname: { required: "please enter the last name" },
                                email: { required: "please enter the Email-id", email: "Invalid email-id" },
                                ddlstate : { required: "please select state" },
                                ddlcity : { required: "please select city" },
                                ddldist : { required: "please select district" }
                                
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


