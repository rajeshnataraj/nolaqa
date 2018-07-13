<?php
	@include("sessioncheck.php");
	
	$oper = isset($method['oper']) ? $method['oper'] : '';
	$editid =  isset($method['id']) ? $method['id'] : '';
	
	/****declaration part****/
	$uname='';
	$fname='';
	$lname='';
	$email='';
	$pphoto='';
	$gfname='';
	$glname='';
	$suserid='';
	$arrcombine=array('','','','','','','','','','','','','','','');
	$arrcombine1=array('','','','','','','','','','','','','','','');
	$password = generatePassword();

	if($editid != 0){
		$id=explode(",",$editid);
		$editid = $id[0];
		$sdistid = $id[1];
		$sshlid = $id[2];
		$suserid = $id[3];
               
                $qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname 
									FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
									WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='7' AND b.fld_access='1' 
										AND a.fld_delstatus='0' AND b.fld_item_id='".$editid."'");
                
		if($suserid !=0){
			if($sessmasterprfid == 9){
				$uid1 = $ObjDB->SelectSingleValue("SELECT fld_user_id FROM itc_user_master WHERE fld_id='".$uid."'");
			}
			else{
				$uid1 = $suserid;
			}
			$distname = $ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,' ',fld_lname) FROM itc_user_master WHERE fld_id='".$uid1."'");
			$arrfieldid=array();
			$arrfieldvalue=array();
			
			$optionaldet1 = $ObjDB->QueryObject("SELECT fld_field_id,fld_field_value FROM itc_user_add_info WHERE fld_user_id='".$uid1."'");
			$rows1=$optionaldet1->num_rows;
				while($rowoptionaldet1=$optionaldet1->fetch_object())
				{
					array_push($arrfieldid,$rowoptionaldet1->fld_field_id);
					array_push($arrfieldvalue,$rowoptionaldet1->fld_field_value);
				}
				 $arrcombine1=array_combine($arrfieldid,$arrfieldvalue);
				 $arrcombine1=getarrayvalues($arrfieldid,$arrcombine);				 
				 $state = $arrcombine1[8];
				 $statename = $ObjDB->SelectSingleValue("SELECT DISTINCT(fld_statename) FROM itc_state_city WHERE fld_statevalue='".$arrcombine1[8]."'");
				 $city = $arrcombine1[9];
			
			$selectdet = $ObjDB->QueryObject("SELECT fld_username AS uname,fld_password,fld_fname AS fname,fld_lname AS lname,fld_profile_pic AS pphoto 
											FROM itc_user_master 
											WHERE fld_id='".$editid."' ");
		}
		else{
			 if(($sessmasterprfid == 2 or $sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid == 9) and $sdistid == 0){
				$selectdet = $ObjDB->QueryObject("SELECT a.fld_username AS uname,a.fld_password,a.fld_fname AS fname,a.fld_lname AS lname, a.fld_flag AS chktea,
													a.fld_school_id AS shlid,a.fld_profile_pic AS pphoto, d.fld_city AS city,d.fld_state AS state,
													d.fld_school_name AS shlname,c.fld_statename AS statename,a.fld_flag AS chktea 
												FROM itc_user_master AS a,itc_state_city AS c,itc_school_master AS d 
												WHERE a.fld_id= '".$editid."' AND a.fld_school_id=d.fld_id AND d.fld_state=c.fld_statevalue 
													AND a.fld_delstatus='0' AND c.fld_statename <>'' 
												GROUP BY a.fld_id");
				$distname = "School Purchase";	
			}
			else{
				$selectdet = $ObjDB->QueryObject("SELECT a.fld_username as uname,a.fld_password,a.fld_fname AS fname,a.fld_lname AS lname,a.fld_flag AS chktea,
													a.fld_district_id AS distid,a.fld_school_id AS shlid,a.fld_profile_pic AS pphoto,b.fld_district_name AS distname, 			
													b.fld_city AS city,b.fld_state AS state,d.fld_school_name AS shlname,c.fld_statename AS statename,a.fld_flag AS chktea
												 FROM itc_user_master AS a,itc_district_master AS b,itc_state_city AS c,itc_school_master AS d 
												 WHERE a.fld_id= '".$editid."' AND a.fld_district_id=b.fld_id AND a.fld_school_id=d.fld_id  
												 	AND b.fld_state=c.fld_statevalue AND a.fld_delstatus='0' AND c.fld_statename <>'' 
												GROUP BY a.fld_id");
			}
		}
		
			$row=$selectdet->fetch_assoc();
			extract($row);
			
			$password=fnDecrypt($fld_password,$encryptkey);
			if($pphoto == '' or $pphoto == 'no-image.png'){ $pphoto1 = "<img src='img/no-image.png'/>"; }
			else{ $pphoto1 = "<img src=thumb.php?src=".__CNTPPPATH__.$pphoto."  width='100' height='100' /> "; }
					
			$arrfieldid=array();
			$arrfieldvalue=array();
			
			$optionaldet = $ObjDB->QueryObject("SELECT fld_field_id,fld_field_value FROM itc_user_add_info WHERE fld_user_id='".$editid."'");
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
			
			if($arrcombine[11] !=""){
				$parentdet = $ObjDB->QueryObject("SELECT fld_email as email,fld_fname as gfname,fld_lname as glname 
												FROM itc_user_master 
												WHERE fld_id= '".$arrcombine[11]."' AND fld_delstatus='0'");
				
						
				$row1=$parentdet->fetch_assoc();
				extract($row1);
			}
		
	}
	
	if($sessmasterprfid == 5 or ($sessmasterprfid == 9 and $indid !=0)){
		if($sessmasterprfid == 9 and $indid !=0){
			$uid1 = $ObjDB->SelectSingleValue("SELECT fld_user_id FROM itc_user_master WHERE fld_id='".$uid."'");
		}
		else{
			$uid1 = $uid;
		}
		$distname = $ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,' ',fld_lname) FROM itc_user_master WHERE fld_id='".$uid1."'");
		$distid = 0;
		$arrfieldid=array();
		$arrfieldvalue=array();
		
		$optionaldet1 = $ObjDB->QueryObject("SELECT fld_field_id,fld_field_value FROM itc_user_add_info WHERE fld_user_id='".$uid1."'");
		$rows1=$optionaldet1->num_rows;
			while($rowoptionaldet1=$optionaldet1->fetch_object())
			{
				array_push($arrfieldid,$rowoptionaldet1->fld_field_id);
				array_push($arrfieldvalue,$rowoptionaldet1->fld_field_value);
			}
			 $arrcombine1=array_combine($arrfieldid,$arrfieldvalue);
			 $arrcombine1=getarrayvalues($arrfieldid,$arrcombine1);	
						 
			 $state = $arrcombine1[8];
			 $statename = $ObjDB->SelectSingleValue("SELECT DISTINCT(fld_statename) FROM itc_state_city WHERE fld_statevalue='".$arrcombine1[8]."'");
			 $city = $arrcombine1[9];
	}
	if(($sessmasterprfid == 7 or $sessmasterprfid == 9 or $sessmasterprfid == 8) and $editid==0 and $indid ==0){
				if($sendistid !=0){
					$selectshl = $ObjDB->QueryObject("SELECT a.fld_district_id AS distid, a.fld_school_name AS shlname,a.fld_state AS state, 
														a.fld_city AS city, b.fld_district_name AS distname, c.fld_statename AS statename 
													FROM itc_school_master AS a, itc_district_master AS b, itc_state_city AS c 
													WHERE a.fld_id='".$senshlid."' AND a.fld_district_id=b.fld_id AND a.fld_state=c.fld_statevalue 
														AND c.fld_statename <>'' 
													GROUP BY a.fld_id");
					$resshl = $selectshl->fetch_assoc();
					extract($resshl);
				}
				else{
					$selectshl = $ObjDB->QueryObject("SELECT a.fld_district_id AS distid, a.fld_school_name AS shlname,a.fld_state AS state, a.fld_city AS city, 
														b.fld_statename AS statename 
													FROM itc_school_master AS a, itc_state_city AS b 
													WHERE a.fld_id='".$senshlid."' AND a.fld_district_id=0 AND a.fld_state=b.fld_statevalue 
														AND b.fld_statename <>''  
													GROUP BY a.fld_id");
					$resshl = $selectshl->fetch_assoc();
					extract($resshl);
					$distid =0;
					$distname = "School Purchase";
				}
				
			}
?>
<script type="text/javascript" charset="utf-8">		
	
	$(function(){				
		var t4 = new $.TextboxList('#form_tags_newstudent', 
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
<section data-type='#users-individuals' id='users-individuals-student_newstudent'>
	<div class='container'>
    	<div class='row'>
      		<div class='twelve columns'>
            	<p class="dialogTitle"><?php if($editid == 0){ echo "New Student";} else { echo $fname." ".$lname." "."Student";} ?></p>
				<p class="dialogSubTitleLight">&nbsp;</p>
        		<h1></h1>
      		</div>
    	</div>
    
        <div class='row formBase'>
            <div class='eleven columns centered insideForm'>
                <form method='post' name="validate" id="validate">
                    <div class="row">
                        <div class="six columns">
                            <div class="title-info">Student Information (Required)</div>
                            First Name<span class="fldreq">*</span> 
                            <dl class='field row'>
                                <dt class='text'>
                                    <input id="fname" name="fname"  placeholder='First name' tabindex="1" type='text' value="<?php echo $fname;?>" onkeyup="">
                                </dt>
                            </dl> 
                        </div>
                        <div class="six columns">
                            <div class="title-info">Guardian Information (Optional)</div>
                            First Name
                            <dl class='field row'>
                                <dt class='text'>
                                    <input id="gfname" name="gfname" placeholder='First name' tabindex="11" type='text' value="<?php echo $gfname;?>">
                                </dt>
                            </dl>
                        </div>
                    </div>
                    
                    <div class="row rowspacer">
                        <div class="six columns">
                        Last Name<span class="fldreq">*</span> 
                            <dl class='field row'>
                                <dt class='text'>
                                    <input id="lname" name="lname" placeholder='Last name' tabindex="2" type='text' value="<?php echo $lname;?>" onkeyup="">
                                </dt>
                            </dl> 
                        </div>
                        <div class="six columns">
                        Last Name
                            <dl class='field row'>
                                <dt class='text'>
                                    <input id="glname" name="glname" placeholder='Last name' tabindex="12" type='text' value="<?php echo $glname;?>">
                                </dt>
                            </dl>
                        </div>
                    </div>

                        <?php
                            if($editid==0){
                                echo '
                    
                    <div class="row rowspacer">
                        <div class="six columns">
                            User Name<span class="fldreq">*</span> 
                            <dl class="field row">
                                <dt class="text">
                                   <input id="uname" name="uname"  onblur="$(this).valid();" placeholder="User name" tabindex="3" type="text" value="'.$uname.'">
                                </dt>
                            </dl> 
                        </div>
                        <div class="six columns">
                            Email
                            <dl class="field row">
                                <dt class="text">
                                    <input id="email" name="email" placeholder="Email" tabindex="13" type="email"  value="'.$email.'">
                                </dt>
                            </dl>
                        </div>
                    </div>';
                            }
                        ?>
                    
                    <div class="row rowspacer">
                        <?php
                            if($editid==0){
                                echo '
                                <div class="six columns">
                                 Password<span class="fldreq">*</span>
                                    <dl class="field row">
                                        <dt class="text">
                                           <input id="txtpassword" name="txtpassword" placeholder="Password" tabindex="4" type="text" value="'.$password.'">
                                        </dt>
                                    </dl>
                                </div>';
                            }
                        ?>
                        <div class="six columns">
                        Street Address
                            <dl class='field row'>
                                <dt class='text'>
                                    <input  id="address1" name="address1" placeholder='Street address' tabindex="14" type='text' value="<?php echo $arrcombine[7];?>">
                                </dt>
                            </dl>
                        </div>
                    </div>
                    
                    <div class="row rowspacer">
                        <?php
                        if($editid==0){
                            echo '

                            <div class="six columns">
                             Verify Password<span class="fldreq">*</span>
                                <dl class="field row">
                                    <dt class="text">
                                       <input id="txtconfirmpassword" name="txtconfirmpassword" placeholder="Verify Password" tabindex="5" type="text" value="">
                                    </dt>
                                </dl> 
                            </div>';
                        }
                        ?>
                        <div class="three columns">
                         Select state
                            <dl class='field row'>
                                <dt class='dropdown'>
                                    <div class="selectbox">
                                   <?php $statename1 = $ObjDB->SelectSingleValue("SELECT DISTINCT(fld_statename) 
								   												FROM itc_state_city 
																				WHERE fld_statevalue='".$arrcombine[8]."'"); ?>
                                                                                
                                      <input type="hidden" name="ddlstate1" id="ddlstate1" value="<?php echo $arrcombine[8];?>" onchange="fn_changecity1(this.value);">
                                      <a class="selectbox-toggle" tabindex="15" role="button" data-toggle="selectbox" href="#">
                                        <span class="selectbox-option input-medium" data-option="<?php if($editid==0){ echo "0";} else {echo $arrcombine[8];}?>" style="width:95%;"><?php if($editid == 0 or $arrcombine[8] == ''){ echo "Select state";} else {echo $statename1;}?></span>
                                        <b class="caret1"></b>
                                      </a>
                                      <div class="selectbox-options">
                                        <input type="text" class="selectbox-filter" placeholder="Search state" style="width: 83%;" />
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
                        <div class="three columns">
                        Select city
                            <dl class='field row'>
                                <dt class='dropdown'>
                                    <?php if($editid == 0){ ?>
                                        <div id="divddlcity1">
                                            <div class="selectbox">
                                            <input type="hidden" name="ddlcity1" id="ddlcity1" value="" disabled="disabled" >
                                            <a class="selectbox-toggle" tabindex="16" role="button" data-toggle="selectbox" href="#">
                                            <span class="selectbox-option input-medium" data-option="<?php echo $arrcombine[9];?>" style="width:95%;"><?php if($arrcombine[9] ==''){ echo "Select city"; } else {echo $arrcombine[9]; }?></span>
                                            <b class="caret1"></b>
                                            </a>
                                            </div>
                                        </div>
                                    <?php } else {?>
                                        <div id="divddlcity1">
                                            <div class="selectbox">
                                            <input type="hidden" name="ddlcity1" id="ddlcity1" value="<?php echo $arrcombine[9];?>" onchange="fn_changezip1(this.value);" >
                                            <a class="selectbox-toggle"  tabindex="17" role="button" data-toggle="selectbox" href="#">
                                            <span class="selectbox-option input-medium" data-option="<?php echo $arrcombine[9];?>" style="width:95%;"><?php if($arrcombine[9] ==''){ echo "Select city"; } else {echo $arrcombine[9]; }?></span>
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
                                                                    <li><a href="#" data-option="<?php echo ucwords(strtolower($cityname));?>"><?php echo  ucwords(strtolower($cityname))?></a></li>
                                                            <?php }?>       
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
                         Select grade<!--<span class="fldreq">*</span>-->
                            <dl class='field row'>
                                <dt class='dropdown'>
                                    <div class="selectbox">
                                     <input type="hidden" name="ddlgrade" id="ddlgrade" value="<?php echo $arrcombine[12];?>" onchange="$('#ddlgrade').valid();">
                                    <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                    <span class="selectbox-option input-medium" data-option="<?php if($editid==0){ echo "0";} else {echo $arrcombine[12];}?>"><?php if($editid == 0 or $arrcombine[12] == ''){ echo "Select grade";} else {echo $arrcombine[12];}?></span>
                                    <b class="caret1"></b>
                                    </a>
                                        <div class="selectbox-options" >
                                        <input type="text" class="selectbox-filter" placeholder="Search grade" >
                                            <ul role="options">
                                                <?php 
                                                   for($j=1;$j<=12;$j++){ ?>
                                                            <li><a tabindex="1" href="#" data-option="<?php echo $j;?>"><?php echo $j;?></a></li>
                                                    <?php 
                                                    }?>       
                                            </ul>
                                        </div>
                                    </div>
                                </dt>
                            </dl> 
                        </div>
                        <div class="three columns">
                        Select zip
                            <dl class='field row'>
                                <dt class='dropdown'>
                                    <?php if($editid == 0){ ?>
                                      <div id="divddlzip1">
                                            <div class="selectbox">
                                              <input type="hidden" name="ddlzip1" id="ddlzip1" value="" disabled="disabled" >
                                              <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option="" style="width:95%;"> Select zip</span>
                                                <b class="caret1"></b>
                                              </a>
                                            </div>
                                      </div>
                                      <?php } else { ?>
                                        <div id="divddlzip1">
                                            <div class="selectbox">
                                              <input type="hidden" name="ddlzip1" id="ddlzip1" value="<?php echo $arrcombine[10];?>">
                                              <a class="selectbox-toggle"  tabindex="18" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option="<?php echo $arrcombine[10];?>" style="width:95%;"><?php if($arrcombine[10] ==''){ echo "Select zip"; } else {echo $arrcombine[10]; }?></span>
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
                                                                <li><a href="#" data-option="<?php echo $zipcode;?>"><?php echo $zipcode;?></a></li>
                                                        <?php }?>       
                                                </ul>
                                              </div>
                                            </div>
                                    </div>
                                   <?php } ?>
                                </dt>
                            </dl>
                        </div>
                        <div class="three columns">
                        Office number
                            <dl class='field row'>
                                <dt class='text'>
                                   <input id="officeno" name="officeno" placeholder='Office number' tabindex="19" type='text' value="<?php echo $arrcombine[3];?>">
                                </dt>
                            </dl>
                        </div>
                    </div>
                    
                    <div class="row rowspacer">
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
                                    <p><a id="imgphoto"> </a></p><br />
                                     <div id="queue"> </div>
                                    </div>
                                </dt>
                            </dl>
                            <input type="hidden" name="hiduploadfile" id="hiduploadfile" value="<?php echo $pphoto;?>" />  
                        </div>
                        <div class="three columns">
                        Mobile number
                            <dl class='field row'>
                                <dt class='text'>
                                    <input id="mobileno" name="mobileno" placeholder='Mobile number' tabindex="20" type='text' value="<?php echo $arrcombine[5];?>">
                                </dt>
                            </dl> 
                        </div>
                        <div class="three columns">
                        Home number
                            <dl class='field row'>
                                <dt class='text'>
                                     <input id="homeno" name="homeno" placeholder='Home number' tabindex="21" type='text' value="<?php echo $arrcombine[6];?>">
                                </dt>
                            </dl>  
                        </div>
                    </div>
                    
                    <?php if($sessmasterprfid !=5) { ?>
                    <div class="row rowspacer">
                        <div class="six columns">
                        	<div class="title-info">School Information</div>
                        	Select school<span class="fldreq">*</span>
                            <dl class='field row' id="shl">
                               <?php if($sessmasterprfid !=5 and  $suserid ==0 and $indid ==0){?>
                                <?php if($sessmasterprfid ==2 and $editid == 0){?>
                                    <dt class="dropdown">
                                        <div id="divddlshl">
                                            <div class="selectbox">
                                              <input type="hidden" name="ddlshl" id="ddlshl" value="" onchange="$('#ddlshl').valid(); fn_changestatecity();">
                                              <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option="">Select school</span>
                                                <b class="caret1"></b>
                                              </a>
                                              <div class="selectbox-options">
                                                <input type="text" class="selectbox-filter" placeholder="Search school" >
                                                <ul role="options">
                                                   <?php                                                    
                                                        $shlqry = $ObjDB->QueryObject("SELECT fld_id, fld_school_name AS schoolname 
																					FROM itc_school_master 
																					WHERE fld_delstatus='0' 
																					ORDER BY fld_school_name ASC ");
                                                        while($rowshl = $shlqry->fetch_assoc()){ 
														extract($rowshl);
														?>
                                                               <li><a tabindex="1" href="#" data-option="<?php echo $fld_id;?>"><?php echo $schoolname;?></a></li>
                                                        <?php 
                                                        } 
                                                    ?>           
                                                </ul>
                                              </div>
                                            </div>
                                        </div>
                                    </dt>
                               <?php }
                                    else {?>
                                    <dt class='text'>
                                        <input type='text' disabled="disabled" value="<?php echo $shlname;?>">
                                    </dt>
                                        <input type="hidden" name="ddlshl" id="ddlshl" value="<?php echo $senshlid?>">
                                    <?php } ?>
                            <?php } ?>
                            </dl> 
                        </div>
                        
                          </div>
                    <?php } ?>
                    
                    <div class="row rowspacer">
                        <div class="six columns">
                        	State                      
                            <dl class='field row' id="stat">
                                <?php if($sessmasterprfid ==2 and $editid == 0){?>
                                <dt class="dropdown">
                                    <div class="selectbox">
                                         <input type="hidden" name="ddlstate" id="ddlstate" value="<?php echo $state;?>" onchange="$('#ddlstate').valid();fn_changecity(this.value);">
                                        <a class="selectbox-toggle" tabindex="7"  role="button" data-toggle="selectbox" >
                                        <span class="selectbox-option input-medium" data-option="<?php if($editid==0){ echo "0";} else {echo $state;}?>"><?php if($editid == 0){ echo "Select State";} else {echo $statename;}?></span>
                                        <b class="caret1"></b>
                                        </a>                                    
                                    </div>
                                </dt>
                                <?php } 
                                else {?>
                                    <dt class='text'>
                                        <input type='text' disabled="disabled" value="<?php echo $statename;?>">
                                    </dt>
                                <?php } ?>
                            </dl> 
                        </div>
                    </div>
                    
                    <div class="row rowspacer">
                        <div class="six columns">
                        	City
                            <dl class='field row' id="cit">
                                <?php if($sessmasterprfid ==2 and $editid == 0){?>
                                    <dt class="dropdown">
                                      <div id="divddlcity">
                                            <div class="selectbox"> 
                                              <input type="hidden" name="ddlcity" id="ddlcity" value="<?php echo $city;?>" >
                                              <a class="selectbox-toggle" tabindex="8"  role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option=""><?php if($editid == 0) { echo "Select city"; } else { echo $city; }?></span>
                                                <b class="caret1"></b>
                                              </a>
                                            </div>
                                      </div>
                                    </dt>
                                <?php }
                                else {?>
                                    <dt class='text'>
                                        <input type='text' disabled="disabled" value="<?php echo $city;?>">
                                    </dt>
                                <?php } ?>
                            </dl> 
                        </div>
                    </div>
                     
                    <div class="row rowspacer">
                        <div class="six columns">
                        	District
                            <dl class='field row' id="dit">
                                <?php if($sessmasterprfid ==2 and $editid == 0){?>
                                <dt class="dropdown">
                                    <div id="divddldist">
                                        <div class="selectbox">
                                            <input type="hidden" name="ddldist" id="ddldist" value="<?php echo $distid ?> ">
                                            <a class="selectbox-toggle" tabindex="9"  role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option=""><?php if($editid == 0){ echo "Select district"; } else { echo $distname; } ?> </span>
                                           
                                            <b class="caret1"></b>
                                            </a>
                                        </div>
                                    </div>
                                </dt>
                                <?php }
                                else {?>
                                    <dt class='text'>
                                        <input type='text' disabled="disabled" value="<?php echo $distname;?>">
                                    </dt>
                                    <input type="hidden" name="ddldist" id="ddldist" value="<?php if ($suserid !=0 or $sendistid == 0){ echo "0"; } else {echo $distid;} ?> ">
                                <?php } ?>
                            </dl> 
                        </div>
                     </div> 
                    
                    <div class="row rowspacer">
                        <div class='twelve columns'>
                            To create new tag, type a name and press Enter.
                            <div class="tag_well">
                                <input type="text" name="test3" tabindex="22" value="" id="form_tags_newstudent" />
                            </div>
                        </div>
                    </div>
                    
                    <script language="javascript" type="text/javascript">
						function fn_generateuname()
						{		
							$('#uname').val($('#fname').val().toLowerCase().substring(0,1)+$('#lname').val().toLowerCase());		
						}
						function fn_checkusername()
						{
							var dataparam = "oper=checkstdname&uname="+$('#uname').val();	
							$.ajax({
								type: "POST",
								url: 'users/individuals/users-individuals-student_newstudentdb.php',
								data: dataparam,
								success: function(data)
								{
									if(trim(data)=='false')
										$('#uname').val(Math.floor(Math.random() * 8) + 1+$('#fname').val().toLowerCase().substring(0,1)+$('#lname').val().toLowerCase());
								}
							});
						}
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
						$('#officeno,#mobileno,#homeno').mask('(999) 999-9999');	
                    </script>
                    
                    <div class="row rowspacer">
                        <div class="six columns">
                            <p class='btn primary twelve columns'>
                            	 <?php if($editid == 0){ ?>
                               		<a tabindex="23" onclick="fn_cancel('users-individuals-student');">Cancel</a>
                                 <?php } else { ?>
                                 	<a tabindex="23" onclick="fn_deletstudent(<?php echo $editid;?>)">Delete</a>
                                 <?php } ?>   
                            </p>
                        </div>
                        <div class="six columns" id="userphoto">
                            <p class='btn secondary twelve columns'>
                                <?php
                                    if($editid==0){
                                       echo '<a tabindex="24" onclick="fn_createstudent('.$editid.')">Create Student</a>';
                                    }else{
                                        echo '<a tabindex="24" onclick="fn_updatestudent('.$editid.')">Update Student</a>';
                                    }
                                ?>

                            </p>
                        </div>
                    </div>
                </form>
            
                <script type="text/javascript" language="javascript">
                     $(function(){
                        $("#validate").validate({
                            ignore: "",
                            errorElement: "dd",
                            errorPlacement: function(error, element) {
                                $(element).parents('dl').addClass('error');
                                error.appendTo($(element).parents('dl'));
                                error.addClass('msg');
                            },
                            rules: {
								uname: { required: true, lettersonly: true, 
									remote:{ 
											url: "users/individuals/users-individuals-student_newstudentdb.php", 
											type:"POST",  
											data: {  
													stdid: function() {
													return '<?php echo $editid;?>';},
													oper: function() {
													return 'checkstdname';}
													  
											 },
											 async:false 
									   }},
								address1: { letterswithbasicpunc:true },
                                fname: { required: true, lettersonly: true },
                                lname: { required: true, lettersonly: true },
                                txtpassword: { required: true },
					txtconfirmpassword: { required: true, equalTo: "#txtpassword" },
                                email: { email: true },
                                <?php if(($sessmasterprfid != 5 and $sessmasterprfid != 7)){?>
									<?php if($sessmasterprfid != 2){?>
										ddlstate : { required: true },
										ddlcity : { required: true },
										ddldist : { required: true },
									<?php } ?>
                                ddlshl : { required: true },
                                
                                gfname : { lettersonly: true },
                                glname : { lettersonly: true }
                                <?php } ?>
                            },
                            messages: {
                                fname: { required: "Please enter the first name" },
                                lname: { required: "Please enter the last name" },
                                uname: { required: "Please enter the User name", remote: "Student username already exists" },
                                txtpassword: { required: "please enter paswword" },
					            txtconfirmpassword: { required: "please type subject name", equalTo: "Password Mismatch"},
                                email: { email: "Invalid email-id" },
                                ddlstate : { required: "Please select state" },
                                ddlcity : { required: "Please select city" },
                                ddldist : { required: "Please select district" },
                                ddlshl : { required: "Please select school" }
                                
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