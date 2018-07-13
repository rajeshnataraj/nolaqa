<?php
@include("sessioncheck.php");

$oper = isset($method['oper']) ? $method['oper'] : '';
/*--- Check student username already extis or not ---*/
if($oper=="checkstdname" and $oper != " " )
	{
		$stdid = isset($method['stdid']) ? $method['stdid'] : '0';
		$uname = isset($method['uname']) ? $ObjDB->EscapeStrAll($method['uname']) : '';
		
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
											FROM itc_user_master 
											WHERE LCASE(REPLACE(fld_username,' ',''))='".str_replace(' ','',$uname)."' AND fld_delstatus='0' AND fld_id<>'".$stdid."'");

		if($count == 0){ echo "true"; }	else { echo "false"; }
	}

/*--- Select city based on satate ---*/	
if($oper == "changecity" and $oper != ""){
	$statevalue =  isset($method['statevalue']) ? $method['statevalue'] : '';
	
	?>
		
		<div class="selectbox">
		  <input type="hidden" name="ddlcity" id="ddlcity" value="" onchange="$('#ddlcity').valid();fn_loaddistrict(this.value);" >
		  <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
			<span class="selectbox-option input-medium" data-option=""> Select city</span>
			<b class="caret1"></b>
		  </a>
		  <div class="selectbox-options">
			<input type="text" class="selectbox-filter" placeholder="Search city" >
			<ul role="options">
				<?php 
					$cityqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_cityname) AS cityname 
													FROM itc_state_city 
													WHERE fld_statevalue='".$statevalue."' AND fld_delstatus=0 
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
        
   <?php
}
/*--- Select district based on state and city ---*/
if($oper == "changedistrict" and $oper != ""){
		$statevalue =  isset($method['statevalue']) ? $method['statevalue'] : '';
		$cityname =  isset($method['cityname']) ? $method['cityname'] : '';
		?>
        <div class="selectbox">
          <input type="hidden" name="ddldist" id="ddldist" value="" onchange="$('#ddldist').valid();fn_loadschool('<?php echo $statevalue;?>','<?php echo $cityname;?>');">
          <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
            <span class="selectbox-option input-medium" data-option="">Select district</span>
            <b class="caret1"></b>
          </a>
          <div class="selectbox-options">
            <input type="text" class="selectbox-filter" placeholder="Search district" style="width: 83%;" />
            <ul role="options">
            	<li><a tabindex="1" href="#" data-option="sp"><?php echo "School Purchase"?></a></li>
                <?php 
                    $distqry = $ObjDB->QueryObject("SELECT fld_id,fld_district_name AS districtname 
													FROM itc_district_master 
													WHERE fld_state='".$statevalue."' AND fld_city='".$cityname."' AND fld_delstatus='0' 
													ORDER BY fld_district_name ASC");
                    while($rowdist = $distqry->fetch_assoc()){ 
					extract($rowdist);
					?>
                           <li><a tabindex="1" href="#" data-option="<?php echo $fld_id;?>"><?php echo $districtname;?></a></li>
                    <?php 
                    }?>       
            </ul>
          </div>
        </div>
 <?php
}
/*--- select school based on the distruct ---*/
if($oper == "changeschool" and $oper != ""){
		$distid =  isset($method['distid']) ? $method['distid'] : '';
		$state =  isset($method['state']) ? $method['state'] : '';
		$city =  isset($method['city']) ? $method['city'] : '';
		?>
        <div class="selectbox">
          <input type="hidden" name="ddlshl" id="ddlshl" value="" onchange="$('#ddlshl').valid();">
          <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
            <span class="selectbox-option input-medium" data-option="">Select school</span>
            <b class="caret1"></b>
          </a>
          <div class="selectbox-options">
            <input type="text" class="selectbox-filter" placeholder="Search school" >
            <ul role="options">
               <?php
				if($distid == "sp"){
					$shlph = $ObjDB->QueryObject("SELECT fld_id, fld_school_name AS schoolname 
												FROM itc_school_master 
												WHERE fld_district_id ='0' AND fld_state='".$state."' AND fld_city='".$city."' AND fld_delstatus='0' 
												ORDER BY fld_school_name ASC");
                    while($rowshlp = $shlph->fetch_assoc()){ 
					extract($rowshlp);
					?>
                           <li><a tabindex="1" href="#" data-option="<?php echo $fld_id;?>"><?php echo $schoolname;?></a></li>
                    <?php 
                    } 
				}
				else{
                    $shlqry = $ObjDB->QueryObject("SELECT fld_id, fld_school_name AS schoolname 
												FROM itc_school_master 
												WHERE fld_district_id ='".$distid."' AND fld_delstatus='0' 
												ORDER BY fld_school_name ASC ");
                    while($rowshl = $shlqry->fetch_assoc()){ 
					extract($rowshl);
					?>
                           <li><a tabindex="1" href="#" data-option="<?php echo $fld_id;?>"><?php echo $schoolname;?></a></li>
					<?php 
                    } 
                }?>           
            </ul>
          </div>
        </div>
<?php
}
/*--- Loaded the student count ---*/
if($oper == "loadusercount" and $oper != ""){
		$schoolid =  isset($method['schid']) ? $method['schid'] : 0;
	
		$prevnoofusers = $ObjDB->SelectSingleValueInt("SELECT fld_no_of_users 
													FROM itc_license_track 
													WHERE fld_school_id='".$schoolid."'");
		$prevremainusers = $ObjDB->SelectSingleValueInt("SELECT fld_remain_users 
														FROM itc_license_track 
														WHERE fld_school_id='".$schoolid."'");
		
		echo $prevremainusers;
	}
	
/*--- Select city based on satate ---*/
if($oper == "changestatecity" and $oper != ""){
		$schid =  isset($method['schid']) ? $method['schid'] : '';			
		$qry = $ObjDB->QueryObject("SELECT fld_state AS state, fld_city AS cityname, fld_district_id AS did 
									FROM itc_school_master 
									WHERE fld_id='".$schid."'");
		extract($qry->fetch_assoc());		
		$statename = $ObjDB->SelectSingleValue("SELECT DISTINCT(fld_statename) 
												FROM itc_state_city 
												WHERE fld_statevalue='".$state."'");	
		?>
        <dt class='text'>
            <input type='text' disabled="disabled" value="<?php echo $statename;?>">
        </dt>
        <input type="hidden" name="ddlstate" id="ddlstate" value="<?php echo $state;?> " />~
        <dt class='text'>
            <input type='text' disabled="disabled" value="<?php echo $cityname;?>">
        </dt>
        <input type="hidden" name="ddlcity" id="ddlcity" value="<?php echo $cityname; ?> " />~
         
       <dt class='text'>
            <input type='text' disabled="disabled" value="<?php if($did!=0) echo $ObjDB->SelectSingleValue("SELECT fld_district_name FROM itc_district_master WHERE fld_id='".$did."'"); else echo "School Purchase";?>">
        </dt>
        <input type="hidden" name="ddldist" id="ddldist" value="<?php if($did!=0) echo $did; else echo "sp";?>" /> 
        
 <?php
}
/*--- Select city based on satate ---*/
if($oper == "changecity1" and $oper != ""){
		$statevalue =  isset($method['statevalue']) ? $method['statevalue'] : '';
		
		?>
            <div class="selectbox">
              <input type="hidden" name="ddlcity1" id="ddlcity1" value="" onchange="fn_changezip1(this.value);" >
              <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                <span class="selectbox-option input-medium" data-option="" style="width: 95%;"> Select city</span>
                <b class="caret1"></b>
              </a>
              <div class="selectbox-options">
                <input type="text" class="selectbox-filter" placeholder="Search city" style="width: 83%;" />
                <ul role="options">
                    <?php 
                        $cityqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_cityname) AS cityname 
														FROM itc_state_city 
														WHERE fld_statevalue='".$statevalue."' AND fld_delstatus=0 
														ORDER BY fld_cityname ASC");
                       while($rowcity = $cityqry->fetch_assoc()){
						   extract($rowcity);
						   ?>
                                <li><a tabindex="1" href="#" data-option="<?php echo $cityname;?>"><?php echo  ucwords(strtolower($cityname))?></a></li>
                        <?php 
                        }?>       
                </ul>
              </div>
            </div>
       <?php
	}
/*--- Select zipcode based on city ---*/	
if($oper == "changezip1" and $oper != ""){
		$cityvalue =  isset($method['cityvalue']) ? $method['cityvalue'] : '';
		$statevalue =  isset($method['statevalue']) ? $method['statevalue'] : '';
		?>
            <div class="selectbox">
              <input type="hidden" name="ddlzip1" id="ddlzip1" value="">
              <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                <span class="selectbox-option input-medium" data-option="" style="width: 95%;"> Select zip</span>
                <b class="caret1"></b>
              </a>
              <div class="selectbox-options">
                <input type="text" class="selectbox-filter" placeholder="Search zip" style="width: 83%;" />
                <ul role="options">
                    <?php 
                        $zipqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_zipcode) AS zipcode 
													FROM itc_state_city 
													WHERE fld_cityname='".$cityvalue."' AND fld_statevalue='".$statevalue."' AND fld_delstatus=0 
													ORDER BY fld_zipcode ASC");
                       while($rowzip = $zipqry->fetch_assoc()){
						   extract($rowzip);
						   ?>
                                <li><a tabindex="1" href="#" data-option="<?php echo $zipcode;?>"><?php echo $zipcode;?></a></li>
                        <?php 
                        }?>       
                </ul>
              </div>
            </div>
		<!--</dl>-->
		<?php
	}				
/*--- Save and update the sutudents details ---*/
if($oper == "savestudent" and $oper != ""){

    try /**Here starts with saving the details uster master and district master tables**/
    {
        $rows='';
        $parentid='';
        $date=date("Y-m-d H:i:s");
        $editid =  isset($method['id']) ? $method['id'] : '0';
        $state =  isset($method['state']) ? $method['state'] : '';
        $city =  isset($method['city']) ? $method['city'] : '';
        $city =	ucwords(strtolower($city));
        $distid =  isset($method['distid']) ? $method['distid'] : '';
        $shlid =  isset($method['shlid']) ? $method['shlid'] : '';

        $fname =  isset($method['fname']) ? $method['fname'] : '';
        $lname =  isset($method['lname']) ? $method['lname'] : '';
        $uname =  isset($method['uname']) ? $method['uname'] : '';
        $password =  isset($method['password']) ? $method['password'] : '';
        $ddlgrade =  isset($method['ddlgrade']) ? $method['ddlgrade'] : '';
        $photo =  isset($method['photo']) ? $method['photo'] : '';

        $gfname =  isset($method['gfname']) ? $method['gfname'] : '';
        $glname =  isset($method['glname']) ? $method['glname'] : '';
        $email =  isset($method['email']) ? $method['email'] : '';
        $address1 =  isset($method['address1']) ? $method['address1'] : '';
        $state1 =  isset($method['state1']) ? $method['state1'] : '';
        $city1 =  isset($method['city1']) ? $method['city1'] : '';
        $city1 =	ucwords(strtolower($city1));
        $zipcode1 =  isset($method['zipcode1']) ? $method['zipcode1'] : '';
        $officeno =  isset($method['officeno']) ? $method['officeno'] : '';
        $faxno =  isset($method['faxno']) ? $method['faxno'] : '';
        $mobileno =  isset($method['mobileno']) ? $method['mobileno'] : '';
        $homeno =  isset($method['homeno']) ? $method['homeno'] : '';
        $tags = isset($method['tags']) ? $method['tags'] : '';
        $uguid = gen_uuid();

        /**validation for the parameters and these below functions are validate to return true or false***/
        $validate_editid=true;
        $validate_state=true;
        $validate_city=true;
        $validate_fname=true;
        $validate_lname=true;

        if($editid!=0) $validate_editid=validate_datatype($editid,'int');
        $validate_address1=validate_datas($address1,'letterswithbasicpunc');
        $validate_fname=validate_datas($fname,'lettersonly');
        $validate_lname=validate_datas($lname,'lettersonly');
        $validate_email=validate_datatype($email,'email');

        $fname = $ObjDB->EscapeStrAll($fname);
        $lname = $ObjDB->EscapeStrAll($lname);
        $address1 = $ObjDB->EscapeStrAll($address1);
        $tags = $ObjDB->EscapeStrAll($tags);
        $uname = $ObjDB->EscapeStrAll($uname);
        $gfname = $ObjDB->EscapeStrAll($gfname);
        $glname = $ObjDB->EscapeStrAll($glname);

        if($validate_fname and $validate_lname)
        {
            if($editid==0){
                if($sessmasterprfid == 9 and $indid !=0){
                    $invuid = $ObjDB->SelectSingleValue("SELECT fld_user_id FROM itc_user_master WHERE fld_id='".$uid."'");
                }
                else{
                    $invuid = 0;
                }

                if($sessmasterprfid == 5){
                    $userid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_user_master (fld_uuid, fld_username, fld_password, fld_fname, fld_lname, 
															fld_profile_id, fld_role_id, fld_profile_pic, fld_district_id, fld_school_id, fld_created_by, 
															fld_user_id, fld_activestatus, fld_created_date) 
														VALUES ('".$uguid."', '".$uname."','".fnEncrypt($password,$encryptkey)."','".$fname."','".$lname."','10','5',
															'".$photo."','0','0','".$uid."','".$uid."','1','".$date."')");
                }
                else{
                    $userid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_user_master (fld_uuid, fld_username, fld_password, fld_fname, fld_lname, 
															fld_profile_id, fld_role_id, fld_profile_pic, fld_district_id, fld_school_id, fld_activestatus,
															fld_user_id, fld_created_by, fld_created_date) 
														VALUES ('".$uguid."','".$uname."','".fnEncrypt($password,$encryptkey)."','".$fname."','".$lname."','10','5',
															'".$photo."','".$distid."','".$shlid."','1','".$invuid."','".$uid."','".$date."')");
                }
                /*--Tags insert-----*/
                fn_taginsert($tags,7,$userid,$uid);
                if($gfname !="" or $email !="" or $glname !="" ){
                    $uguid = gen_uuid();
                    if($sessmasterprfid == 5){
                        $parentid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_user_master (fld_uuid, fld_email, fld_fname, fld_lname, fld_profile_id, 
																	fld_role_id, fld_district_id, fld_school_id, fld_user_id, fld_created_by, fld_created_date) 
																VALUES ('".$uguid."','".$email."','".$gfname."','".$glname."','11','5','0','0','".$uid."','".$uid."',
																	'".$date."')");
                    }
                    else{
                        $parentid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_user_master (fld_uuid, fld_email, fld_fname, fld_lname, fld_profile_id, 
																	fld_role_id, fld_district_id, fld_school_id, fld_user_id, fld_created_by, fld_created_date) 
																VALUES ('".$uguid."','".$email."','".$gfname."','".$glname."','11','5','".$distid."','".$shlid."',
																	'".$invuid."','".$uid."','".$date."')");
                    }

                    /*-------Mail----------*/
                    $html_txt = '';
                    $headers = '';
                    $mailtitle = "Pitsco Admin";

                    $subj = "You're invited to join our learning management system";
                    $random_hash = md5(date('r', time()));

                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=utf-8" . "\r\n";
                    $headers .= "From: Synergy ITC <do_not_reply@pitsco.com>" . "\r\n";

                    $html_txt = '<table cellpadding="0" cellspacing="0" width="90%" align="center" style="font-size:12px;"><tr><td valign="top" align="left"><strong style="font-size:24px;">You&lsquo;re invited to join our PITSCO Learning Management System</strong></td></tr><tr><td valign="top" align="left"><br />Hi '.$gfname.', <br /><br /></td></tr><tr><td valign="top" align="center"><p></p><table width="98%" cellpadding="20" cellspacing="0" bgcolor="#ebf3fe" border="thin" bordercolor="#8ec7e2"><tr><td valign="top" align="left" style="font-size:14px;"><strong>All you need to do is choose a username and password.</strong><br />It only takes a few seconds.<br /><br /><strong>Click this link to get started:</strong><br /><a href="'.$domainame.'register.php?e='.md5($parentid).'">'.$domainame.'register.php?e='.md5($parentid).'</a><br /></td></tr></table><p></p></td></tr><tr><td valign="top" align="left"><hr /><strong>Have questions?</strong> Contact your account administrator - at <a href="mailto:support@pitsco.com">support@pitsco.com</a></td></tr></table>';


                }
                $arr = array($officeno,$faxno,$mobileno,$homeno,$address1,$state1,$city1,$zipcode1,$parentid,$ddlgrade);
                $j=3;
                for($i=0;$i<sizeof($arr);$i++)
                {
                    if($arr[$i]!='')
                    {
                        $ObjDB->NonQuery("INSERT INTO itc_user_add_info (fld_user_id,fld_field_id,fld_field_value) 
										VALUES ('".$userid."','".$j."','".$arr[$i]."')");
                    }
                    $j++;
                }
            }
            else{
                /*---tags------*/
                $ObjDB->NonQuery("UPDATE itc_main_tag_mapping 
								SET fld_access='0' WHERE fld_tag_type='7' AND fld_item_id='".$editid."' AND fld_tag_id IN(SELECT fld_id 
								FROM itc_main_tag_master 
								WHERE fld_created_by='".$uid."' AND fld_delstatus='0')");
                fn_tagupdate($tags,7,$editid,$uid);
                $ObjDB->NonQuery("UPDATE itc_user_master 
								SET fld_username = '".$uname."', fld_password = '".fnEncrypt($password,$encryptkey)."', fld_fname = '".$fname."', 
									fld_lname = '".$lname."', fld_profile_pic = '".$photo."', fld_updated_by = '".$uid."', fld_updated_date = '".$date."'
								WHERE fld_id = '".$editid."' AND fld_delstatus ='0'");

                if($gfname !="" or $email !="" or $glname !="" ){
                    $parentid = $ObjDB->SelectSingleValueInt("SELECT fld_field_value 
															FROM itc_user_add_info 
															WHERE fld_user_id='".$editid."' AND fld_field_id=11");
                    if($rows !=0){
                        $ObjDB->NonQuery("UPDATE itc_user_master 
										SET fld_email = '".$email."', fld_fname = '".$gfname."', fld_lname = '".$glname."', fld_updated_by = '".$uid."', 
											fld_updated_date = '".$date."'
										WHERE fld_id = '".$parentid."' AND fld_delstatus ='0'");
                    }
                    else{

                        if($sessmasterprfid == 9 and $indid !=0){
                            $invuid = $ObjDB->SelectSingleValue("SELECT fld_user_id 
															FROM itc_user_master 
															WHERE fld_id='".$uid."'");
                        }
                        else{
                            $invuid = 0;
                        }
                        $parentid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_user_master (fld_email, fld_fname, fld_lname, fld_profile_id, fld_role_id, 
																	fld_district_id, fld_school_id, fld_user_id, fld_created_by, fld_created_date) 
																VALUES ('".$email."','".$gfname."','".$glname."','11','5','".$distid."','".$shlid."','".$invuid."',
																	'".$uid."','".$date."')");
                    }
                }

                $arr = array($officeno,$faxno,$mobileno,$homeno,$address1,$state1,$city1,$zipcode1,$parentid,$ddlgrade);
                $j=3;
                for($i=0;$i<sizeof($arr);$i++)
                {
                    if($arr[$i]!='')
                    {
                        $cnt=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) 
														FROM  itc_user_add_info 
														WHERE fld_user_id = '".$editid."' AND  fld_field_id = '".$j."'");
                        if($cnt>0)
                        {
                            $ObjDB->NonQuery("UPDATE itc_user_add_info 
										SET fld_field_value = '".$arr[$i]."' 
										WHERE fld_user_id = '".$editid."' AND fld_field_id = '".$j."' AND fld_delstatus ='0'");
                        }
                        else if($cnt==0)
                        {
                            $ObjDB->NonQuery("INSERT INTO itc_user_add_info (fld_user_id,fld_field_id,fld_field_value) 
											VALUES ('".$editid."','".$j."','".$arr[$i]."')");
                        }
                    }
                    $j++;
                }
            }
            echo "success";
        }
        else{
            echo "fail";
        }
    }
    catch(Exception $e)
    {
        echo "fail";
    }
}

if($oper == "updatestudent" and $oper != ""){

    try /**Here starts with saving the details uster master and district master tables**/
    {
        $rows='';
        $parentid='';
        $date=date("Y-m-d H:i:s");
        $editid =  isset($method['id']) ? $method['id'] : '0';
        $state =  isset($method['state']) ? $method['state'] : '';
        $city =  isset($method['city']) ? $method['city'] : '';
        $city =	ucwords(strtolower($city));
        $distid =  isset($method['distid']) ? $method['distid'] : '';
        $shlid =  isset($method['shlid']) ? $method['shlid'] : '';

        $fname =  isset($method['fname']) ? $method['fname'] : '';
        $lname =  isset($method['lname']) ? $method['lname'] : '';
        $uname =  isset($method['uname']) ? $method['uname'] : '';
        $password =  isset($method['password']) ? $method['password'] : '';
        $ddlgrade =  isset($method['ddlgrade']) ? $method['ddlgrade'] : '';
        $photo =  isset($method['photo']) ? $method['photo'] : '';

        $gfname =  isset($method['gfname']) ? $method['gfname'] : '';
        $glname =  isset($method['glname']) ? $method['glname'] : '';
        $email =  isset($method['email']) ? $method['email'] : '';
        $address1 =  isset($method['address1']) ? $method['address1'] : '';
        $state1 =  isset($method['state1']) ? $method['state1'] : '';
        $city1 =  isset($method['city1']) ? $method['city1'] : '';
        $city1 =	ucwords(strtolower($city1));
        $zipcode1 =  isset($method['zipcode1']) ? $method['zipcode1'] : '';
        $officeno =  isset($method['officeno']) ? $method['officeno'] : '';
        $faxno =  isset($method['faxno']) ? $method['faxno'] : '';
        $mobileno =  isset($method['mobileno']) ? $method['mobileno'] : '';
        $homeno =  isset($method['homeno']) ? $method['homeno'] : '';
        $tags = isset($method['tags']) ? $method['tags'] : '';
        $uguid = gen_uuid();

        /**validation for the parameters and these below functions are validate to return true or false***/
        $validate_editid=true;
        $validate_state=true;
        $validate_city=true;
        $validate_fname=true;
        $validate_lname=true;

        if($editid!=0) $validate_editid=validate_datatype($editid,'int');
        $validate_address1=validate_datas($address1,'letterswithbasicpunc');
        $validate_fname=validate_datas($fname,'lettersonly');
        $validate_lname=validate_datas($lname,'lettersonly');
        $validate_email=validate_datatype($email,'email');

        $fname = $ObjDB->EscapeStrAll($fname);
        $lname = $ObjDB->EscapeStrAll($lname);
        $address1 = $ObjDB->EscapeStrAll($address1);
        $tags = $ObjDB->EscapeStrAll($tags);
        $uname = $ObjDB->EscapeStrAll($uname);
        $gfname = $ObjDB->EscapeStrAll($gfname);
        $glname = $ObjDB->EscapeStrAll($glname);

        if($validate_fname and $validate_lname)
        {
            if($editid==0){
                if($sessmasterprfid == 9 and $indid !=0){
                    $invuid = $ObjDB->SelectSingleValue("SELECT fld_user_id FROM itc_user_master WHERE fld_id='".$uid."'");
                }
                else{
                    $invuid = 0;
                }

                if($sessmasterprfid == 5){
                    $userid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_user_master (fld_uuid, fld_fname, fld_lname, 
															fld_profile_id, fld_role_id, fld_profile_pic, fld_district_id, fld_school_id, fld_created_by, 
															fld_user_id, fld_activestatus, fld_created_date) 
														VALUES ('".$uguid."','".$fname."','".$lname."','10','5',
															'".$photo."','0','0','".$uid."','".$uid."','1','".$date."')");
                }
                else{
                    $userid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_user_master (fld_uuid, fld_fname, fld_lname, 
															fld_profile_id, fld_role_id, fld_profile_pic, fld_district_id, fld_school_id, fld_activestatus,
															fld_user_id, fld_created_by, fld_created_date) 
														VALUES ('".$uguid."','".$fname."','".$lname."','10','5',
															'".$photo."','".$distid."','".$shlid."','1','".$invuid."','".$uid."','".$date."')");
                }
                /*--Tags insert-----*/
                fn_taginsert($tags,7,$userid,$uid);
                if($gfname !="" or $email !="" or $glname !="" ){
                    $uguid = gen_uuid();
                    if($sessmasterprfid == 5){
                        $parentid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_user_master (fld_uuid, fld_fname, fld_lname, fld_profile_id, 
																	fld_role_id, fld_district_id, fld_school_id, fld_user_id, fld_created_by, fld_created_date) 
																VALUES ('".$uguid."','".$gfname."','".$glname."','11','5','0','0','".$uid."','".$uid."',
																	'".$date."')");
                    }
                    else{
                        $parentid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_user_master (fld_uuid, fld_fname, fld_lname, fld_profile_id, 
																	fld_role_id, fld_district_id, fld_school_id, fld_user_id, fld_created_by, fld_created_date) 
																VALUES ('".$uguid."','".$gfname."','".$glname."','11','5','".$distid."','".$shlid."',
																	'".$invuid."','".$uid."','".$date."')");
                    }

                    /*-------Mail----------*/
                    $html_txt = '';
                    $headers = '';
                    $mailtitle = "Pitsco Admin";

                    $subj = "You're invited to join our learning management system";
                    $random_hash = md5(date('r', time()));

                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=utf-8" . "\r\n";
                    $headers .= "From: Synergy ITC <do_not_reply@pitsco.com>" . "\r\n";

                    $html_txt = '<table cellpadding="0" cellspacing="0" width="90%" align="center" style="font-size:12px;"><tr><td valign="top" align="left"><strong style="font-size:24px;">You&lsquo;re invited to join our PITSCO Learning Management System</strong></td></tr><tr><td valign="top" align="left"><br />Hi '.$gfname.', <br /><br /></td></tr><tr><td valign="top" align="center"><p></p><table width="98%" cellpadding="20" cellspacing="0" bgcolor="#ebf3fe" border="thin" bordercolor="#8ec7e2"><tr><td valign="top" align="left" style="font-size:14px;"><strong>All you need to do is choose a username and password.</strong><br />It only takes a few seconds.<br /><br /><strong>Click this link to get started:</strong><br /><a href="'.$domainame.'register.php?e='.md5($parentid).'">'.$domainame.'register.php?e='.md5($parentid).'</a><br /></td></tr></table><p></p></td></tr><tr><td valign="top" align="left"><hr /><strong>Have questions?</strong> Contact your account administrator - at <a href="mailto:support@pitsco.com">support@pitsco.com</a></td></tr></table>';


                }
                $arr = array($officeno,$faxno,$mobileno,$homeno,$address1,$state1,$city1,$zipcode1,$parentid,$ddlgrade);
                $j=3;
                for($i=0;$i<sizeof($arr);$i++)
                {
                    if($arr[$i]!='')
                    {
                        $ObjDB->NonQuery("INSERT INTO itc_user_add_info (fld_user_id,fld_field_id,fld_field_value) 
										VALUES ('".$userid."','".$j."','".$arr[$i]."')");
                    }
                    $j++;
                }
            }
            else{
                /*---tags------*/
                $ObjDB->NonQuery("UPDATE itc_main_tag_mapping 
								SET fld_access='0' WHERE fld_tag_type='7' AND fld_item_id='".$editid."' AND fld_tag_id IN(SELECT fld_id 
								FROM itc_main_tag_master 
								WHERE fld_created_by='".$uid."' AND fld_delstatus='0')");
                fn_tagupdate($tags,7,$editid,$uid);
                $ObjDB->NonQuery("UPDATE itc_user_master 
								SET fld_fname = '".$fname."', 
									fld_lname = '".$lname."', fld_profile_pic = '".$photo."', fld_updated_by = '".$uid."', fld_updated_date = '".$date."'
								WHERE fld_id = '".$editid."' AND fld_delstatus ='0'");

                if($gfname !="" or $email !="" or $glname !="" ){
                    $parentid = $ObjDB->SelectSingleValueInt("SELECT fld_field_value 
															FROM itc_user_add_info 
															WHERE fld_user_id='".$editid."' AND fld_field_id=11");
                    if($rows !=0){
                        $ObjDB->NonQuery("UPDATE itc_user_master 
										SET fld_fname = '".$gfname."', fld_lname = '".$glname."', fld_updated_by = '".$uid."', 
											fld_updated_date = '".$date."'
										WHERE fld_id = '".$parentid."' AND fld_delstatus ='0'");
                    }
                    else{

                        if($sessmasterprfid == 9 and $indid !=0){
                            $invuid = $ObjDB->SelectSingleValue("SELECT fld_user_id 
															FROM itc_user_master 
															WHERE fld_id='".$uid."'");
                        }
                        else{
                            $invuid = 0;
                        }
                        $parentid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_user_master (fld_fname, fld_lname, fld_profile_id, fld_role_id, 
																	fld_district_id, fld_school_id, fld_user_id, fld_created_by, fld_created_date) 
																VALUES ('".$gfname."','".$glname."','11','5','".$distid."','".$shlid."','".$invuid."',
																	'".$uid."','".$date."')");
                    }
                }

                $arr = array($officeno,$faxno,$mobileno,$homeno,$address1,$state1,$city1,$zipcode1,$parentid,$ddlgrade);
                $j=3;
                for($i=0;$i<sizeof($arr);$i++)
                {
                    if($arr[$i]!='')
                    {
                        $cnt=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) 
														FROM  itc_user_add_info 
														WHERE fld_user_id = '".$editid."' AND  fld_field_id = '".$j."'");
                        if($cnt>0)
                        {
                            $ObjDB->NonQuery("UPDATE itc_user_add_info 
										SET fld_field_value = '".$arr[$i]."' 
										WHERE fld_user_id = '".$editid."' AND fld_field_id = '".$j."' AND fld_delstatus ='0'");
                        }
                        else if($cnt==0)
                        {
                            $ObjDB->NonQuery("INSERT INTO itc_user_add_info (fld_user_id,fld_field_id,fld_field_value) 
											VALUES ('".$editid."','".$j."','".$arr[$i]."')");
                        }
                    }
                    $j++;
                }
            }
            echo "success";
        }
        else{
            echo "fail";
        }
    }
    catch(Exception $e)
    {
        echo "fail";
    }
}
/*--- Delete the student details  ---*/
if($oper == "deletstudent" and $oper != ""){
	
	$editid =  isset($method['editid']) ? $method['editid'] : '';
	$ObjDB->NonQuery("UPDATE itc_user_master 
					SET fld_delstatus = '1', fld_deleted_by = '".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' 
					WHERE fld_id = '".$editid."'");
	$ObjDB->NonQuery("UPDATE itc_license_assign_student 
					SET fld_flag = '0' 
					WHERE fld_student_id = '".$editid."'");
	$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_studentmapping 
					SET fld_flag = '0' 
					WHERE fld_student_id = '".$editid."'");
	$ObjDB->NonQuery("UPDATE itc_class_triad_schedule_studentmapping 
					SET fld_flag = '0' 
					WHERE fld_student_id = '".$editid."'");
	$ObjDB->NonQuery("UPDATE itc_class_student_mapping 
					SET fld_flag = '0' 
					WHERE fld_student_id = '".$editid."'");
	$ObjDB->NonQuery("UPDATE itc_class_sigmath_student_mapping 
					SET fld_flag = '0' 
					WHERE fld_student_id = '".$editid."'");
	$ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_student_mappingtemp 
					SET fld_flag = '0' 
					WHERE fld_student_id = '".$editid."'");
        $ObjDB->NonQuery("UPDATE itc_class_rotation_expschedule_student_mappingtemp 
					SET fld_flag = '0' 
					WHERE fld_student_id = '".$editid."'");
        $ObjDB->NonQuery("UPDATE itc_class_rotation_mission_student_mappingtemp 
					SET fld_flag = '0' 
					WHERE fld_student_id = '".$editid."'");
        $ObjDB->NonQuery("UPDATE itc_class_rotation_modexpschedule_student_mappingtemp 
					SET fld_flag = '0' 
					WHERE fld_student_id = '".$editid."'");
         $ObjDB->NonQuery("UPDATE itc_class_rotation_mission_student_mappingtemp 
					SET fld_flag = '0' 
					WHERE fld_student_id = '".$editid."'");
	$ObjDB->NonQuery("UPDATE itc_class_indassesment_student_mapping 
					SET fld_flag = '0' 
					WHERE fld_student_id = '".$editid."'");
        $ObjDB->NonQuery("UPDATE itc_class_exp_student_mapping 
					SET fld_flag = '0' 
					WHERE fld_student_id = '".$editid."'");
         $ObjDB->NonQuery("UPDATE itc_class_pdschedule_student_mapping 
					SET fld_flag = '0' 
					WHERE fld_student_id = '".$editid."'");
         $ObjDB->NonQuery("UPDATE itc_class_mission_student_mapping 
					SET fld_flag = '0' 
					WHERE fld_student_id = '".$editid."'");

}
 //sort/filter the student list other than the “tag�? feature START
if($oper == "clsstudents" and $oper != '') 
{
    $classid = isset($_REQUEST['classid']) ? $_REQUEST['classid'] : '0';
?>
     <div class='row buttons' >
        <a class='skip btn mainBtn' href='#users-individuals-student_newstudent' id='btnusers-individuals-student_newstudent' name='0'>
          <div class="icon-synergy-add-dark"></div>
          <div class='onBtn'>New Student</div>
        </a>
         <a class='skip btn mainBtn' href='#users-individuals-student_delstudent' id='btnusers-individuals-student_delstudent' name='0' onclick="fn_selradio();">
        <div class="icon-synergy-add-dark"></div>
        <div class='onBtn'>Delete Students</div>
      </a>
            <?php
        $qryclassstudentmap=$ObjDB->QueryObject("SELECT a.fld_id AS studentid
                                               FROM itc_user_master AS a LEFT JOIN itc_class_student_mapping AS b ON a.fld_id=b.fld_student_id 
                                               WHERE b.fld_class_id='".$classid."' AND b.fld_flag=1 AND a.fld_activestatus='1' AND a.fld_delstatus='0'");

        $studeid=array();
        if($qryclassstudentmap->num_rows > 0)
        {
            while($rowsqry = $qryclassstudentmap->fetch_assoc())
            {
                extract($rowsqry);
                $studeid[]=$studentid;
            }
        }
        for($i=0;$i<sizeof($studeid);$i++)
        {
            $sqry = $ObjDB->QueryObject("SELECT fld_id AS id, CONCAT(fld_lname,' ',fld_fname) AS fullname, 
                                                                fn_shortname(CONCAT(fld_lname,' ',fld_fname),1) AS shortname, fld_profile_pic AS photo, fld_district_id AS sdistid, 
                                                                fld_school_id AS sshlid, fld_user_id AS suserid 
                                                        FROM itc_user_master 
                                                        WHERE fld_profile_id= '10' AND fld_id='".$studeid[$i]."' AND fld_school_id='".$schoolid."' AND fld_district_id='".$sendistid."' AND  fld_delstatus='0' 
                                                        ORDER BY fullname ASC");
            $studid=array();
            while($stures = $sqry->fetch_assoc())
            {
                    extract($stures);
                    ?>
                <a class='skip btn main' href='#' onclick="fn_profile(this)" name="<?php echo $id.",".$sdistid.",".$sshlid.",".$suserid;?>">
                    <div class="icon-synergy-user">
                        <?php if($photo != "no-image.png" && $photo != ''){ ?>
                            <img class="thumbimg" src="thumb.php?src=<?php echo __CNTPPPATH__.$photo; ?>&w=40&h=40&q=100" />
                        <?php } ?>
                    </div>
                    <div class='onBtn tooltip' title="<?php echo $fullname;?>"><?php echo $shortname;?></div>
                </a>
            <?php    
            }   
        }?>
    </div>
        <?php
}
 //sort/filter the student list other than the “tag�? feature end

 //sort/filter the student list grade level feature START
if($oper == "gradestudents" and $oper != '') 
{
   

    $gradeid = isset($_REQUEST['gradeid']) ? $_REQUEST['gradeid'] : '0';
    $distid = isset($_REQUEST['distid']) ? $_REQUEST['distid'] : '0';
    $schoolid = isset($_REQUEST['schoolid']) ? $_REQUEST['schoolid'] : '0';
    
    if($sessmasterprfid == 2)
    {
          $qry="AND b.fld_district_id=".$_SESSION['inddistid']." AND b.fld_school_id=".$_SESSION['indschoolid'].""; 
    }
    else
    {
         $qry="AND b.fld_district_id=".$districtid." AND b.fld_school_id=".$senshlid."";
    }
?>
     <div class='row buttons'>
        <a class='skip btn mainBtn' href='#users-individuals-student_newstudent' id='btnusers-individuals-student_newstudent' name='0'>
          <div class="icon-synergy-add-dark"></div>
          <div class='onBtn'>New Student</div>
        </a>
         
         <?php 
              if($sessmasterprfid != 2)
              {
             ?>
              
         <a class='skip btn mainBtn' href='#users-individuals-student_delstudent' id='btnusers-individuals-student_delstudent' name='0' onclick="fn_selradio();">
        <div class="icon-synergy-add-dark"></div>
        <div class='onBtn'>Delete Students</div>
      </a>
         <?php
              }
              ?>
            <?php
            
            
            $sqry = $ObjDB->QueryObject("SELECT b.fld_id AS id, CONCAT(b.fld_lname,' ',b.fld_fname) AS fullname, 
                                            fn_shortname(CONCAT(b.fld_fname,' ',b.fld_lname),1) AS shortname, b.fld_profile_pic AS photo, b.fld_district_id AS sdistid, 
                                            b.fld_school_id AS sshlid, b.fld_user_id AS suserid 
                                            FROM itc_user_add_info as a 
                                            LEFT JOIN itc_user_master as b ON a.fld_user_id=b.fld_id
                                            WHERE a.fld_field_id='12' AND a.fld_field_value='".$gradeid."' AND a.fld_delstatus='0'
                                            AND b.fld_activestatus='1' AND b.fld_delstatus='0' AND b.fld_profile_id='10'
                                            $qry ORDER BY fullname ASC");
            
            while($stures = $sqry->fetch_assoc())
            {
                    extract($stures);
                    ?>

                <a class='skip btn main' href='#' onclick="fn_profile(this)" name="<?php echo $id.",".$sdistid.",".$sshlid.",".$suserid;?>">
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
        <?php
}
//sort/filter the student list grade level feature END

/*---selected students to Delete ---*/
if($oper == "seldelstudents") 
{
 
      $studid =  isset($method['studid']) ? $method['studid'] : '';
    $radval =  isset($method['radval']) ? $method['radval'] : '';
   

        if($radval==5){

            if($studid!=''){

              $qrystudent = $ObjDB->QueryObject("SELECT b.fld_fname AS firstname, b.fld_lname AS lastname,b.fld_username as username,b.fld_id AS studentid 
                                                FROM itc_main_tag_mapping as a
                                                LEFT JOIN itc_user_master as b ON a.fld_item_id=b.fld_id
                                                WHERE a.fld_tag_id='".$studid."' AND a.fld_tag_type='7' AND a.fld_access='1'
                                                AND b.fld_profile_id= '10' AND b.fld_school_id='".$schoolid."' AND b.fld_district_id='".$sendistid."' AND b.fld_user_id='".$indid."'  AND b.fld_delstatus='0'");
            }
            else{
                $qrystudent = $ObjDB->QueryObject("SELECT fld_fname AS firstname, fld_lname AS lastname,fld_username as username,fld_id AS studentid 
                                            FROM itc_user_master 
                                            WHERE fld_profile_id= '10' AND fld_school_id='".$schoolid."' AND fld_district_id='".$sendistid."' AND fld_user_id='".$indid."' AND  fld_delstatus='0'
                                            ORDER BY firstname ASC");

            }

        }
        else if($radval==6){

            if($studid!=''){

            $qrystudent = $ObjDB->QueryObject("SELECT fld_fname AS firstname,fld_lname AS lastname,fld_username as username,fld_id AS studentid
                                                FROM itc_user_master
                                                WHERE fld_profile_id = '10' AND fld_school_id = '".$schoolid."' AND fld_district_id = '".$sendistid."' AND fld_user_id='".$indid."'
                                                AND fld_id = '".$studid."' AND fld_delstatus = '0' GROUP BY studentid order by username");
            }
            else{
                $qrystudent = $ObjDB->QueryObject("SELECT fld_fname AS firstname, fld_lname AS lastname,fld_username as username,fld_id AS studentid 
                                            FROM itc_user_master 
                                            WHERE fld_profile_id= '10' AND fld_school_id='".$schoolid."' AND fld_district_id='".$sendistid."' AND fld_user_id='".$indid."' AND  fld_delstatus='0'
                                            ORDER BY firstname ASC");

            }

        }
        else if($radval==7){
            
            if($studid!=''){

                $qrystudent=$ObjDB->QueryObject("SELECT a.fld_id AS studentid,a.fld_fname AS firstname, a.fld_lname AS lastname,a.fld_username as username
                                             FROM itc_user_master AS a LEFT JOIN itc_class_student_mapping AS b ON a.fld_id=b.fld_student_id 
                                             WHERE b.fld_class_id='".$studid."' AND b.fld_flag=1 AND a.fld_activestatus='1' AND a.fld_delstatus='0'");
            }
            else{

                $qrystudent = $ObjDB->QueryObject("SELECT fld_fname AS firstname, fld_lname AS lastname,fld_username as username,fld_id AS studentid 
                                         FROM itc_user_master 
                                         WHERE fld_profile_id= '10' AND fld_school_id='".$schoolid."' AND fld_district_id='".$sendistid."' AND fld_user_id='".$indid."' AND  fld_delstatus='0'
                                         ORDER BY firstname ASC");

            }

        }
        else if($radval==8){

            if($studid!=''){
                                        
   
               $qrystudent = $ObjDB->QueryObject("SELECT b.fld_fname AS firstname,b.fld_lname AS lastname,b.fld_username as username,b.fld_id AS studentid 
                                            FROM itc_user_add_info as a 
                                            LEFT JOIN itc_user_master as b ON a.fld_user_id=b.fld_id
                                            WHERE a.fld_field_id='12' AND a.fld_field_value='".$studid."' AND a.fld_delstatus='0'
                                            AND b.fld_activestatus='1' AND b.fld_delstatus='0' AND b.fld_profile_id='10'
                                            AND fld_school_id='".$schoolid."' AND fld_district_id='".$sendistid."' GROUP BY studentid order by username");
            }
            else{
                $qrystudent = $ObjDB->QueryObject("SELECT fld_fname AS firstname, fld_lname AS lastname,fld_username as username,fld_id AS studentid 
                                            FROM itc_user_master 
                                            WHERE fld_profile_id= '10' AND fld_school_id='".$schoolid."' AND fld_district_id='".$sendistid."' AND fld_user_id='".$indid."' AND  fld_delstatus='0'
                                            ORDER BY firstname ASC");

            }


        }

                                        
   
    ?>
    <script language="javascript" type="text/javascript">
        $(function() {
                 $('div[id^="testrailvisible"]').each(function(index, element) {
                         $(this).slimscroll({ /*------- Scroll for Modules Left Box ------*/
                                 width: '410px',
                                 height:'366px',
                                 size: '7px',
                                 alwaysVisible: true,
                                 railVisible: true,
                                 allowPageScroll: false,
                                 railColor: '#F4F4F4',
                                 opacity: 1,
                                 color: '#d9d9d9',
                                 wheelStep: 1
                         });
                 });

                 $("#list3").sortable({
                         connectWith: ".droptrue1",
                         dropOnEmpty: true,
                         items: "div[class='draglinkleft']",
                         receive: function(event, ui) {
                                 $("div[class=draglinkright]").each(function(){ 
                                         if($(this).parent().attr('id')=='list3'){
                                                 fn_movealllistitems('list3','list4',$(this).children(":first").attr('id'));
                                         }
                                 });
                         }
                 });

                 $( "#list4" ).sortable({
                         connectWith: ".droptrue1",
                         dropOnEmpty: true,
                         receive: function(event, ui) {
                                 $("div[class=draglinkleft]").each(function(){ 
                                         if($(this).parent().attr('id')=='list4'){
                                                 fn_movealllistitems('list3','list4',$(this).children(":first").attr('id'));
                                         }
                                 });
                         }
                 });
         });
    </script>
  <div class='row buttons' id="loadstudentstodelete">  
    <div class='six columns'>
           <div class="dragndropcol">
              <div class="dragtitle">   &nbsp;&nbsp;&nbsp;Students available (<span id="nostudentleftdiv"> <?php echo $qrystudent->num_rows;?></span>)</div>
                <div class="dragWell" id="testrailvisible3" >
                  <div id="list3" class="dragleftinner droptrue1">
                <div class="draglinkleftSearch" id="s_list3" >
                    <dl class='field row'>
                        <dt class='text'>
                            <input placeholder='Search' type='text' id="list_3_search" name="list_3_search" onKeyUp="search_list(this,'#list3');" />
                        </dt>
                    </dl>
                </div>
               
                    
               <?php 
                 if($qrystudent->num_rows > 0){
                    while($rowqryclassstudentmap = $qrystudent->fetch_assoc()){
                            extract($rowqryclassstudentmap);
                            ?>
                       <div class="draglinkleft" id="list3_<?php echo $studentid; ?>" >
                            <div class="dragItemLable tooltip" id="<?php echo $studentid; ?>" title="<?php echo $username;?>"><?php echo $lastname." ".$firstname; ?></div>
                            <div class="clickable" id="clck_<?php echo $studentid;?>" onclick="fn_movealllistitems('list3','list4',1,<?php echo $studentid;?>);"></div>
                       </div> 
                           <?php } }?>    
                    </div>
                </div>
                <div class="dragAllLink"  onclick="fn_movealllistitems('list3','list4',0);">add all Students</div>
            </div>
       </div>

        <div class='six columns'>
            <div class="dragndropcol">
                <div class="dragtitle">Selected Students to Delete</div> 
                <div class="dragWell" id="testrailvisible4">
                    <div id="list4" class="dragleftinner droptrue1">
                            <div class="draglinkleftSearch" id="s_list4" >
                                <dl class='field row'>
                                    <dt class='text'>
                                        <input placeholder='Search' type='text' id="list_4_search" name="list_4_search" onKeyUp="search_list(this,'#list4');" />
                                    </dt>
                                </dl>
                            </div>
                            <?php 
                               if($qrystudent->num_rows > 0){
                                while($rowqryclassstudentmap = $qrystudent->fetch_assoc()){
                                        extract($rowqryclassstudentmap);
                                            ?> 
                                <div class="draglinkright" id="list4_<?php echo $studentid; ?>">
                                    <div class="dragItemLable tooltip" id="<?php echo $studentid; ?>" title="<?php echo $username;?>"><?php echo $lastname." ".
$firstname;?></div>
                                    <div class="clickable" id="clck_<?php echo $studentid; ?>" onclick="fn_movealllistitems('list3','list4',<?php echo $studentid; ?>);"> </div>
                                </div>
                              <?php }
                            }?>
                    </div>
                </div>
                <div class="dragAllLink" onclick="fn_movealllistitems('list4','list3',0);">remove all Students</div>
            </div>
        </div>
         &nbsp;
        <div class="row rowspacer">
            <div class="tRight">
                 <input type="button" id="btnstep" class="darkButton" style="width:200px; height:42px;float:right;" value="Delete Students" 
onClick="fn_deletestudents();" />
            </div>
        </div>
    </div>  
    <?php
                                 
    
}
/*--- Delete the student details  ---*/
if($oper == "delstudents" and $oper != ""){
	
	$studid =  isset($method['sid']) ? $method['sid'] : '';
        
        $studid=  explode(",", $studid);
        
        for($a=0;$a<sizeof($studid);$a++){
            
	$ObjDB->NonQuery("UPDATE itc_user_master 
					SET fld_delstatus = '1', fld_deleted_by = '".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' 
					WHERE fld_id = '".$studid[$a]."'");
	$ObjDB->NonQuery("UPDATE itc_license_assign_student 
					SET fld_flag = '0' 
					WHERE fld_student_id = '".$studid[$a]."'");
	$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_studentmapping 
					SET fld_flag = '0' 
					WHERE fld_student_id = '".$studid[$a]."'");
	$ObjDB->NonQuery("UPDATE itc_class_triad_schedule_studentmapping 
					SET fld_flag = '0' 
					WHERE fld_student_id = '".$studid[$a]."'");
	$ObjDB->NonQuery("UPDATE itc_class_student_mapping 
					SET fld_flag = '0' 
					WHERE fld_student_id = '".$studid[$a]."'");
	$ObjDB->NonQuery("UPDATE itc_class_sigmath_student_mapping 
					SET fld_flag = '0' 
					WHERE fld_student_id = '".$studid[$a]."'");
	$ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_student_mappingtemp 
					SET fld_flag = '0' 
					WHERE fld_student_id = '".$studid[$a]."'");
	$ObjDB->NonQuery("UPDATE itc_class_indassesment_student_mapping 
					SET fld_flag = '0' 
					WHERE fld_student_id = '".$studid[$a]."'");
        }

}

if($oper=="showschools" and $oper != " " )
{
    $distid = isset($method['distid']) ? $method['distid'] : ''; 
	?>
    
    <p class="lightSubTitle">School</p>
    <dl class='field row'>   
                        <dt class='dropdown'>
                            <style>
                                .dropdown .caret1
                                {

                                    float: left;
                                   margin-top: 10px;
                                }
                                .selectbox-options
                                {
                                    width:59%;
                                }
                                .selectbox .selectbox-toggle{
                                     width:59%;
                                }
                            </style>   
                        <div class="selectbox">
                            <input type="hidden" name="schoolid" id="schoolid" value="">
                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#"> <span class="selectbox-option input-medium" data-option="" style="width:97%">Select School</span> <b class="caret1"></b> </a>
                            <div class="selectbox-options">
                                <input type="text" class="selectbox-filter" placeholder="Search School">
                                <ul role="options" style="width:97%">
                                                                            <?php 
                                    $qry = $ObjDB->QueryObject("SELECT fld_id as schoolid, fld_school_name AS schoolname 
                                                                                            FROM itc_school_master 
                                                                                            WHERE fld_district_id ='".$distid."' AND fld_delstatus='0' 
                                                                                            ORDER BY fld_school_name ASC ");
                                        if($qry->num_rows>0){
                                        while($row = $qry->fetch_assoc())
                                        {
                                                extract($row);
                                                ?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $schoolid;?>" onclick="javascript:$('#RadioGroup').show();$('#Types6').show();fn_session(<?php echo $schoolid;?>,<?php echo $distid;?>);fn_showstudents(<?php echo $schoolid;?>,<?php echo $distid;?>);"><?php echo $schoolname; ?></a></li>
                                                <?php
                                        }
                                    }?>
                                </ul>
                            </div>
                          </div>
                        </dt>                                       
                    </dl>
             
            <?php 
} 

if($oper == "showstudents" and $oper != '') 
{
    $distid = isset($_REQUEST['distid']) ? $_REQUEST['distid'] : '0';
    $schoolid = isset($_REQUEST['schoolid']) ? $_REQUEST['schoolid'] : '0';
?>
     <div class='row buttons' id="loadstudents">
        <a class='skip btn mainBtn' href='#users-individuals-student_newstudent' id='btnusers-individuals-student_newstudent' name='0'>
          <div class="icon-synergy-add-dark"></div>
          <div class='onBtn'>New Student</div>
        </a>
        
            <?php
       
            $sqry = $ObjDB->QueryObject("SELECT fld_id AS id, CONCAT(fld_lname,' ',fld_fname) AS fullname, 
		  								fn_shortname(CONCAT(fld_lname,' ',fld_fname),1) AS shortname, fld_profile_pic AS photo, fld_district_id AS sdistid, 
										fld_school_id AS sshlid, fld_user_id AS suserid 
									FROM itc_user_master 
									WHERE fld_profile_id= '10' AND fld_school_id='".$schoolid."' AND fld_district_id='".$distid."' AND  fld_delstatus='0' 
									ORDER BY fullname ASC");
            
            while($stures = $sqry->fetch_assoc())
            {
                    extract($stures);
                    ?>
                <a class='skip btn main' href='#' onclick="fn_profile(this)" name="<?php echo $id.",".$sdistid.",".$sshlid.",".$suserid;?>">
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
        <?php
}


if($oper == "session" and $oper != '') 
{
    $distid = isset($_REQUEST['distid']) ? $_REQUEST['distid'] : '0';
    $schoolid = isset($_REQUEST['schoolid']) ? $_REQUEST['schoolid'] : '0';

    $_SESSION['inddistid']=$distid;
    $_SESSION['indschoolid']=$schoolid;

}


if($oper=="schoolpurchase" and $oper != " " )
{
?>
        <p class="lightSubTitle">School</p>
	<div class="selectbox" style="width:100%">
            <input type="hidden" name="schoolid" id="schoolid" value="">
            <a class="selectbox-toggle" style="width:60%" role="button" data-toggle="selectbox" href="#"> <span class="selectbox-option input-medium" data-option="">Select School</span> <b class="caret1"></b> </a>
            <div class="selectbox-options">
                <input type="text" class="selectbox-filter" placeholder="Search School">
                <ul role="options" style="width:97%">
                                                            <?php 
                    $qry = $ObjDB->QueryObject("SELECT fld_id as schoolid, fld_school_name AS schoolname 
                                                                            FROM itc_school_master 
                                                                            WHERE fld_district_id ='0' AND fld_delstatus='0' 
                                                                            ORDER BY fld_school_name ASC ");
                        if($qry->num_rows>0){
                        while($row = $qry->fetch_assoc())
                        {
                                extract($row);
                                ?>
                                <li><a tabindex="-1" href="#" data-option="<?php echo $schoolid;?>" onclick="javascript:$('#RadioGroup').show();$('#Types6').show();fn_session(<?php echo $schoolid;?>,0);fn_showstudents(<?php echo $schoolid;?>,0);"><?php echo $schoolname; ?></a></li>
                                <?php
                        }
                    }?>
                </ul>
            </div>

        </div>

	<?php 
} 


if($oper == "homepurchasestu" and $oper != '') 
{
   ?>
     <div class='row buttons' id="loadstudents">
        <a class='skip btn mainBtn' href='#users-individuals-student_newstudent' id='btnusers-individuals-student_newstudent' name='0'>
          <div class="icon-synergy-add-dark"></div>
          <div class='onBtn'>New Student</div>
        </a>
        
            <?php
       
            $sqry = $ObjDB->QueryObject("SELECT fld_id AS id, CONCAT(fld_lname,' ',fld_fname) AS fullname, 
		  								fn_shortname(CONCAT(fld_lname,' ',fld_fname),1) AS shortname, fld_profile_pic AS photo, fld_district_id AS sdistid, 
										fld_school_id AS sshlid, fld_user_id AS suserid 
									FROM itc_user_master 
									WHERE fld_profile_id= '10' AND fld_school_id='0' AND fld_district_id='0' AND fld_user_id<>'0' AND  fld_delstatus='0' 
									ORDER BY fullname ASC");
            
            while($stures = $sqry->fetch_assoc())
            {
                    extract($stures);
                    ?>
                <a class='skip btn main' href='#' onclick="fn_profile(this)" name="<?php echo $id.",".$sdistid.",".$sshlid.",".$suserid;?>">
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
        <?php
}
/* Details icons codes start line */

if($oper == "firsname" and $oper != ""){

    $filter = isset($_REQUEST['filter']) ? $_REQUEST['filter'] : '0';
    $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '0';
    $radval = isset($_REQUEST['radval']) ? $_REQUEST['radval'] : '0';
    $classid = isset($_REQUEST['classid']) ? $_REQUEST['classid'] : '0';
    $gradeid = isset($_REQUEST['gradeid']) ? $_REQUEST['gradeid'] : '0';
    
    if($radval=='5' || $radval=='6')
    {
        
        
        if($status=='0') // first name
        {

            if($filter=='0')
            {
                $orderquery = "ORDER BY fld_fname ASC";
            }
            else
            {
               $orderquery = "ORDER BY fld_fname DESC"; 
            }
        }

        else if($status=='1') //last name
        {

            if($filter=='0')
            {
                $orderquery = "ORDER BY fld_lname ASC";
            }
            else
            {
               $orderquery = "ORDER BY fld_lname DESC"; 
            }
        }

        else if($status=='2') //user name
        {

            if($filter=='0')
            {
                $orderquery = "ORDER BY username ASC";
            }
            else
            {
               $orderquery = "ORDER BY username DESC"; 
            }
        }

        else if($status=='3') //password
        {

            if($filter=='0')
            {
                $orderquery = "ORDER BY password ASC";
            }
            else
            {
               $orderquery = "ORDER BY password DESC"; 
            }
        }
   
    
   
    if($sessmasterprfid == 5 or ($sessmasterprfid == 9 and $indid !=0))
        {
            if($sessmasterprfid == 9 and $indid !=0){
                    $uid1 = $ObjDB->SelectSingleValue("SELECT fld_user_id 
                                                                                          FROM itc_user_master 
                                                                                          WHERE fld_id='".$uid."'");
            }
            else{
                   $uid1 = $uid; 
            }
            
            
             $qry1 = $ObjDB->QueryObject("SELECT fld_id AS id,fld_username AS username, fld_fname AS firstname,fld_lname AS lastname, 
		  								fn_shortname(CONCAT(fld_lname,' ',fld_fname),1) AS shortname,fld_password AS password, fld_profile_pic AS photo, 
										fld_district_id AS sdistid, fld_school_id AS sshlid, fld_user_id AS suserid 
									FROM itc_user_master 
									WHERE fld_profile_id= '10' AND fld_user_id='".$uid1."' AND fld_delstatus='0' ".$sqry." 
									".$orderquery."");
        }
        else if($sessmasterprfid == 7 or $sessmasterprfid == 9 or $sessmasterprfid == 8)
        {
           
            
             $qry1 = $ObjDB->QueryObject("SELECT fld_id AS id,fld_username AS username, fld_fname AS firstname,fld_lname AS lastname, 
		  								fn_shortname(CONCAT(fld_lname,' ',fld_fname),1) AS shortname,fld_password AS password, fld_profile_pic AS photo, fld_district_id AS sdistid, 
										fld_school_id AS sshlid, fld_user_id AS suserid 
									FROM itc_user_master 
									WHERE fld_profile_id= '10' AND fld_school_id='".$schoolid."' AND fld_district_id='".$sendistid."' AND  fld_delstatus='0' ".$sqry." 
									".$orderquery."");
        }
         
        if($sqry!='' AND $sessmasterprfid == 2)
        {
            
             $qry1 = $ObjDB->QueryObject("SELECT fld_id AS id,fld_username AS username, fld_fname AS firstname,fld_lname AS lastname, 
                                                                               fn_shortname(CONCAT(fld_lname,' ',fld_fname),1) AS shortname, fld_profile_pic AS photo, fld_district_id AS sdistid, 
                                                                               fld_school_id AS sshlid, fld_user_id AS suserid 
                                                                       FROM itc_user_master 
                                                                       WHERE fld_profile_id= '10' AND fld_delstatus='0' ".$sqry." 
                                                                       ".$orderquery."");
         } ?>
        
        <style>
            .ScrollStyle
            {
                max-height: 250px;
                overflow-x: hidden; /*for horizontal scroll bar */
                overflow-y: auto;
                width: 710px;
                margin-left: 0px;
                margin-top: 0px;
            }
        </style>
        <?php
        if($sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid == 9)
        { ?>
        <div id="details_icon_recordlist_desc">
            <div class="ScrollStyle" id="first_click"> <?php
                while($row1 = $qry1->fetch_assoc())
                {
                    extract($row1);
                    ?>
                    <a  href='#users-individuals-student_newstudent' id='btnusers-individuals-student_newstudent' name="<?php echo $id.",".$sdistid.",".$sshlid.",".$suserid;?>">
                        <div class="row">
                            <div class="row btnbox" onclick="fn_studentclick(<?php echo $id.",".$sdistid.",".$sshlid.",".$suserid;?>);">
                                <li class="liststyle"><?php echo $firstname;?></li>
                                <li class="liststyle"><?php echo $lastname;?></li>
                                <li class="liststyle"><?php echo $username;?></li>
                                <li class="liststyle"><?php echo $firstname; ?></li>
                            </div>
                        </div>
                    </a>
                    <?php
                }
                ?> 
            </div>  
        </div> 
        <?php
    }
    }
    
    else if($radval=='7')
    {
        if($status=='0') // first name
        {
            if($filter=='0')
            {
                $orderquery = "ORDER BY fld_fname ASC";
            }
            else
            {
               $orderquery = "ORDER BY fld_fname DESC"; 
            }
        }
        else if($status=='1') //last name
        {
            if($filter=='0')
            {
                $orderquery = "ORDER BY fld_lname ASC";
            }
            else
            {
               $orderquery = "ORDER BY fld_lname DESC"; 
            }
        }
        else if($status=='2') //user name
        {
            if($filter=='0')
            {
                $orderquery = "ORDER BY username ASC";
            }
            else
            {
               $orderquery = "ORDER BY username DESC"; 
            }
        }
        else if($status=='3') //password
        {
            if($filter=='0')
            {
                $orderquery = "ORDER BY password ASC";
            }
            else
            {
               $orderquery = "ORDER BY password DESC"; 
            }
        }
        ?>
        <style>
            .textcenter{
                padding-top:14px;
                padding-bottom: 15px;
                width: 144px;
                color: white;
            }
            .floatleft{
                float:left;
            }
            .heading{
                margin-right: 90px;
            }
            .btnbox{
                width: 710px;
                height: 1%;
                margin-left: 0px;
                background-color: white;
            }
            .liststyle{
                list-style: none;
                width: 176px;
                float: left;
                text-align: center;
                padding-top: 5px;

            }
            .ScrollStyle
             {
                 max-height: 250px;
                 overflow-x: hidden; /*for horizontal scroll bar */
                 overflow-y: auto;
                 width: 710px;
                 margin-left: 0px;
                 margin-top: -23px;
             }
            .symbals
            {
                margin-right:4px;
                margin-top:14px;
                height:24px;
            }
            
        </style>                 
        <?php
        if($sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid == 9)
        { ?>
        <div class='row buttons' id="details_icon_loadstudents">
           <a  href='#users-individuals-student_newstudent' id='btnusers-individuals-student_newstudent' name='0'>
               <div style="margin-left:233px; float: left;">
                   <div class="floatleft"><img src="img/add.jpg" class="symbals"/></div>
                   <div class="textcenter" onclick="fn_newstudent();">New Student</div>
               </div>
           </a>
           <a  href='#users-individuals-student_delstudent' id='btnusers-individuals-student_delstudent' name='0'>
               <div style="margin-left:10px; float: left;">
                   <div class="floatleft"><img src="img/delete2.jpg" class="symbals"/></div>
                   <div class="textcenter " onclick="fn_delstudent();">Delete Students</div>
               </div>
           </a>

           <div class="row rowspacer" id="details_icon_titleview" style="margin-left:135px;padding-top:36px;">
               <div class="row btnbox" style="background-color:#EEECE1;">
                   <li class="liststyle mouse" id="firstval" onclick="fn_first(0);">First Name</li>
                   <li class="liststyle mouse" id="lastval" onclick="fn_first(1);">Last Name</li>
                   <li class="liststyle mouse" id="userval" onclick="fn_first(2);">User Name</li>
                   <li class="liststyle mouse" id="passval" >Password</li>
               </div>
           </div>
            <?php
                }

             $qryclassstudentmap=$ObjDB->QueryObject("SELECT a.fld_id AS studentid,a.fld_username AS username,a.fld_fname AS firstname,a.fld_lname AS lastname,a.fld_password AS password
                                                  FROM itc_user_master AS a LEFT JOIN itc_class_student_mapping AS b ON a.fld_id=b.fld_student_id 
                                                  WHERE b.fld_class_id='".$classid."' AND b.fld_flag=1 AND a.fld_activestatus='1' AND a.fld_delstatus='0' ".$orderquery."");

           $studeid=array();
           if($qryclassstudentmap->num_rows > 0)
           {
               while($rowsqry = $qryclassstudentmap->fetch_assoc())
               {
                   extract($rowsqry);
                   $studeid[]=$studentid;
               }
           }
           ?>
           <div id="details_icon_recordlist" style="margin-left:135px;padding-top:23px;">
               <div class="ScrollStyle" id="first_click"> <?php
                   for($i=0;$i<sizeof($studeid);$i++)
                   {
                       $sqry = $ObjDB->QueryObject("SELECT fld_id AS id,fld_username AS username, fld_fname AS firstname, fld_lname AS lastname,fld_password AS password, 
                                                                       fn_shortname(CONCAT(fld_lname,' ',fld_fname),1) AS shortname, fld_profile_pic AS photo, fld_district_id AS sdistid, 
                                                                       fld_school_id AS sshlid, fld_user_id AS suserid 
                                                               FROM itc_user_master 
                                                               WHERE fld_profile_id= '10' AND fld_id='".$studeid[$i]."' AND fld_school_id='".$schoolid."' AND fld_district_id='".$sendistid."' AND  fld_delstatus='0' 
                                                               ORDER BY firstname ASC");

                         $studid=array();
                         while($stures = $sqry->fetch_assoc())
                         {
                                 extract($stures);
                                 ?>
                                 <a  href='#users-individuals-student_newstudent' id='btnusers-individuals-student_newstudent' name="<?php echo $id.",".$sdistid.",".$sshlid.",".$suserid;?>">
                                     <div class="row" style="paddind-top:20px;">
                                         <div class="row btnbox" onclick="fn_studentclick(<?php echo $id.",".$sdistid.",".$sshlid.",".$suserid;?>);">
                                             <li class="liststyle"><?php echo $firstname;?></li>
                                             <li class="liststyle"><?php echo $lastname;?></li>
                                             <li class="liststyle"><?php echo $username;?></li>
                                             <li class="liststyle"></li>
                                         </div>
                                     </div>
                                 </a>
                           <?php    
                         } 
                    }
                    ?>
               </div>
           </div>
       </div>
        <?php
    }
    
    else if($radval=='8')
    {
        if($status=='0') // first name
        {
            if($filter=='0')
            {
                $orderquery = "ORDER BY firstname ASC";
            }
            else
            {
               $orderquery = "ORDER BY firstname DESC"; 
            }
        }
        else if($status=='1') //last name
        {
            if($filter=='0')
            {
                $orderquery = "ORDER BY lastname ASC";
            }
            else
            {
               $orderquery = "ORDER BY lastname DESC"; 
            }
        }
        else if($status=='2') //user name
        {
            if($filter=='0')
            {
                $orderquery = "ORDER BY username ASC";
            }
            else
            {
               $orderquery = "ORDER BY username DESC"; 
            }
        }
        else if($status=='3') //password
        {
            if($filter=='0')
            {
                $orderquery = "ORDER BY password ASC";
            }
            else
            {
               $orderquery = "ORDER BY password DESC"; 
            }
        }
        
        if($sessmasterprfid == 2)
        {
              $qry="AND b.fld_district_id=".$_SESSION['inddistid']." AND b.fld_school_id=".$_SESSION['indschoolid'].""; 
        }
        else
        {
             $qry="AND b.fld_district_id=".$districtid." AND b.fld_school_id=".$senshlid."";
        }
        ?>
        
		
    <div class='row buttons'>
    <a  href='#users-individuals-student_newstudent' id='btnusers-individuals-student_newstudent' name='0'>
            <div style="margin-left:233px; float: left;">
              <div class="icon-synergy-add-dark floatleft"></div>
              <div class="textcenter" onclick="fn_newstudent();">New Student</div>
            </div>
    </a>
         
        <?php 
        if($sessmasterprfid != 2)
        {
            ?>
            <a  href='#users-individuals-student_delstudent' id='btnusers-individuals-student_delstudent' name='0'>
            <div style="margin-left:10px; float: left;">
                <div class="floatleft"><img src="img/delete2.jpg" class="symbals"/></div>
                <div class="textcenter " onclick="fn_delstudent();">Delete Students</div>
            </div>
            </a>
  <?php }
        
        if($sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid == 9)
        { ?>
       
        <div class="row rowspacer" id="details_icon_titleview" style="margin-left:135px;padding-top:36px;">
            <div class="row btnbox" style="background-color:#EEECE1;">
            <li class="liststyle mouse" id="firstval" onclick="fn_first(0);">First Name</li>
            <li class="liststyle mouse" id="lastval" onclick="fn_first(1);">Last Name</li>
            <li class="liststyle mouse" id="userval" onclick="fn_first(2);">User Name</li>
            <li class="liststyle mouse" id="passval" >Password</li>
            </div>
        </div>
			  
        <?php
        }

        $sqry = $ObjDB->QueryObject("SELECT b.fld_id AS id,b.fld_username AS username,b.fld_fname AS firstname,b.fld_lname AS lastname,b.fld_password AS password,
                                        fn_shortname(CONCAT(b.fld_fname,' ',b.fld_lname),1) AS shortname, b.fld_profile_pic AS photo, b.fld_district_id AS sdistid, 
                                        b.fld_school_id AS sshlid, b.fld_user_id AS suserid 
                                        FROM itc_user_add_info as a 
                                        LEFT JOIN itc_user_master as b ON a.fld_user_id=b.fld_id
                                        WHERE a.fld_field_id='12' AND a.fld_field_value='".$gradeid."' AND a.fld_delstatus='0'
                                        AND b.fld_activestatus='1' AND b.fld_delstatus='0' AND b.fld_profile_id='10'
                                        $qry ".$orderquery."");
        if($sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid == 9)
        { 
        ?>
        <div id="details_icon_recordlist" style="margin-left:135px;padding-top:23px;">
            <div class="ScrollStyle" id="first_click"> <?php
				while($stures = $sqry->fetch_assoc())
				{
					extract($stures);
					?>
					<a  href='#users-individuals-student_newstudent' id='btnusers-individuals-student_newstudent' name="<?php echo $id.",".$sdistid.",".$sshlid.",".$suserid;?>">
						<div class="row" style="paddind-top:20px;">
							<div class="row btnbox" onclick="fn_studentclick(<?php echo $id.",".$sdistid.",".$sshlid.",".$suserid;?>);">
								<li class="liststyle"><?php echo $firstname;?></li>
								<li class="liststyle"><?php echo $lastname;?></li>
								<li class="liststyle"><?php echo $username;?></li>
								<li class="liststyle"></li>
							</div>
						</div>
					</a>
					<?php    
				} 

				?>
            </div>
        </div>
    <?php } ?>
    </div>
    <?php
    }
    
}


if($oper == "detailsviewicon" and $oper != '') 
{
    $classid = isset($_REQUEST['classid']) ? $_REQUEST['classid'] : '0';
?>
<style>
    .textcenter{
        padding-top:14px;
        padding-bottom: 15px;
        width: 144px;
        color: white;
    }
    .floatleft{
        float:left;
    }
    .heading{
        margin-right: 90px;
    }
    .btnbox{
        width: 710px;
        height: 1%;
        margin-left: 0px;
        background-color: white;
    }
    .liststyle{
        list-style: none;
        width: 176px;
        float: left;
        text-align: center;
        padding-top: 5px;

    }
    .ScrollStyle
     {
         max-height: 250px;
         overflow-x: hidden; /*for horizontal scroll bar */
         overflow-y: auto;
         width: 710px;
         margin-left: 0px;
         margin-top: -23px;
     }
    .symbals
    {
        margin-right:4px;
        margin-top:14px;
        height:24px;
    }
</style>                 
                
     <div class='row buttons'>
        <a  href='#users-individuals-student_newstudent' id='btnusers-individuals-student_newstudent' name='0'>
            <div style="margin-left:233px; float: left;">
                <div class="floatleft"><img src="img/add.jpg" class="symbals"/></div>
                <div class="textcenter" onclick="fn_newstudent();">New Student</div>
            </div>
        </a>
        <a  href='#users-individuals-student_delstudent' id='btnusers-individuals-student_delstudent' name='0'>
            <div style="margin-left:10px; float: left;">
                <div class="floatleft"><img src="img/delete2.jpg" class="symbals"/></div>
                <div class="textcenter " onclick="fn_delstudent();">Delete Students</div>
            </div>
        </a>
        <?php
        if($sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid == 9)
        { 
        ?>
        <div class="row rowspacer" id="details_icon_titleview" style="margin-left:135px;padding-top:36px;">
            <div class="row btnbox" style="background-color:#EEECE1;">
                <li class="liststyle mouse" id="firstval" onclick="fn_first(0);">First Name</li>
                <li class="liststyle mouse" id="lastval" onclick="fn_first(1);">Last Name</li>
                <li class="liststyle mouse" id="userval" onclick="fn_first(2);">User Name</li>
                <li class="liststyle mouse" id="passval" >Password</li>
            </div>
        </div>
            <?php
        }
            
            
        $qryclassstudentmap=$ObjDB->QueryObject("SELECT a.fld_id AS studentid,a.fld_username AS username,a.fld_fname AS firstname,a.fld_lname AS lastname,a.fld_password AS password
                                               FROM itc_user_master AS a LEFT JOIN itc_class_student_mapping AS b ON a.fld_id=b.fld_student_id 
                                               WHERE b.fld_class_id='".$classid."' AND b.fld_flag=1 AND a.fld_activestatus='1' AND a.fld_delstatus='0' ".$orderquery."");

        $studeid=array();
        if($qryclassstudentmap->num_rows > 0)
        {
            while($rowsqry = $qryclassstudentmap->fetch_assoc())
            {
                extract($rowsqry);
                $studeid[]=$studentid;
            }
        }
        if($sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid == 9)
        { 
       
        ?>
        <div id="details_icon_recordlist" style="margin-left:135px;padding-top:23px;">
            <div class="ScrollStyle" id="first_click"> <?php
                for($i=0;$i<sizeof($studeid);$i++)
                {
                    $sqry = $ObjDB->QueryObject("SELECT fld_id AS id,fld_username AS username, fld_fname AS firstname, fld_lname AS lastname,fld_password AS password, 
                                                                    fn_shortname(CONCAT(fld_lname,' ',fld_fname),1) AS shortname, fld_profile_pic AS photo, fld_district_id AS sdistid, 
                                                                    fld_school_id AS sshlid, fld_user_id AS suserid 
                                                            FROM itc_user_master 
                                                            WHERE fld_profile_id= '10' AND fld_id='".$studeid[$i]."' AND fld_school_id='".$schoolid."' AND fld_district_id='".$sendistid."' AND  fld_delstatus='0' 
                                                            ORDER BY firstname ASC");

                      $studid=array();
                      while($stures = $sqry->fetch_assoc())
                      {
                              extract($stures);
                              ?>
                              <a  href='#users-individuals-student_newstudent' id='btnusers-individuals-student_newstudent' name="<?php echo $id.",".$sdistid.",".$sshlid.",".$suserid;?>">
                                  <div class="row" style="paddind-top:20px;">
                                      <div class="row btnbox" onclick="fn_studentclick(<?php echo $id.",".$sdistid.",".$sshlid.",".$suserid;?>);">
                                          <li class="liststyle"><?php echo $firstname;?></li>
                                          <li class="liststyle"><?php echo $lastname;?></li>
                                          <li class="liststyle"><?php echo $username;?></li>
                                          <li class="liststyle"></li>
                                      </div>
                                  </div>
                              </a>
                        <?php    
                      } ?>

          <?php }?>
            </div>
        </div>
        <?php } ?>
    </div>
        <?php
}


if($oper == "gradestudentsdetails" and $oper != '') 
{
   

    $gradeid = isset($_REQUEST['gradeid']) ? $_REQUEST['gradeid'] : '0';
    $distid = isset($_REQUEST['distid']) ? $_REQUEST['distid'] : '0';
    $schoolid = isset($_REQUEST['schoolid']) ? $_REQUEST['schoolid'] : '0';
    
    if($sessmasterprfid == 2)
    {
          $qry="AND b.fld_district_id=".$_SESSION['inddistid']." AND b.fld_school_id=".$_SESSION['indschoolid'].""; 
    }
    else
    {
         $qry="AND b.fld_district_id=".$districtid." AND b.fld_school_id=".$senshlid."";
    }
?>
<div class='row buttons'>
       <a  href='#users-individuals-student_newstudent' id='btnusers-individuals-student_newstudent' name='0'>
              <div style="margin-left:233px; float: left;">
                  <div class="floatleft"><img src="img/add.jpg" class="symbals"/></div>
                  <div class="textcenter" onclick="fn_newstudent();">New Student</div>
              </div>
       </a>
         
        <?php 
        if($sessmasterprfid != 2)
        {
            ?>
            <a  href='#users-individuals-student_delstudent' id='btnusers-individuals-student_delstudent' name='0'>
            <div style="margin-left:10px; float: left;">
                <div class="floatleft"><img src="img/delete2.jpg" class="symbals"/></div>
                <div class="textcenter " onclick="fn_delstudent();">Delete Students</div>
            </div>
            </a>
  <?php }
        if($sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid == 9)
        { 
     
        ?>
        <div class="row rowspacer" id="details_icon_titleview" style="margin-left:135px;padding-top:36px;">
            <div class="row btnbox" style="background-color:#EEECE1;">
            <li class="liststyle mouse" id="firstval" onclick="fn_first(0);">First Name</li>
            <li class="liststyle mouse" id="lastval" onclick="fn_first(1);">Last Name</li>
            <li class="liststyle mouse" id="userval" onclick="fn_first(2);">User Name</li>
            <li class="liststyle mouse" id="passval" >Password</li>
            </div>
        </div>
			  
        <?php
        }

        $sqry = $ObjDB->QueryObject("SELECT b.fld_id AS id,b.fld_username AS username,b.fld_fname AS firstname,b.fld_lname AS lastname,b.fld_password AS password,
                                        fn_shortname(CONCAT(b.fld_fname,' ',b.fld_lname),1) AS shortname, b.fld_profile_pic AS photo, b.fld_district_id AS sdistid, 
                                        b.fld_school_id AS sshlid, b.fld_user_id AS suserid 
                                        FROM itc_user_add_info as a 
                                        LEFT JOIN itc_user_master as b ON a.fld_user_id=b.fld_id
                                        WHERE a.fld_field_id='12' AND a.fld_field_value='".$gradeid."' AND a.fld_delstatus='0'
                                        AND b.fld_activestatus='1' AND b.fld_delstatus='0' AND b.fld_profile_id='10'
                                        $qry ORDER BY firstname ASC");
        if($sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid == 9)
        {
        ?>
        <div id="details_icon_recordlist" style="margin-left:135px;padding-top:23px;">
            <div class="ScrollStyle" id="first_click"> <?php
            while($stures = $sqry->fetch_assoc())
            {
                extract($stures);
                ?>
                <a  href='#users-individuals-student_newstudent' id='btnusers-individuals-student_newstudent' name="<?php echo $id.",".$sdistid.",".$sshlid.",".$suserid;?>">
                    <div class="row" style="paddind-top:20px;">
                        <div class="row btnbox" onclick="fn_studentclick(<?php echo $id.",".$sdistid.",".$sshlid.",".$suserid;?>);">
                            <li class="liststyle"><?php echo $firstname;?></li>
                            <li class="liststyle"><?php echo $lastname;?></li>
                            <li class="liststyle"><?php echo $username;?></li>
                            <li class="liststyle"></li>
                        </div>
                    </div>
                </a>
                <?php    
            } 

            ?>
            </div>
        </div>
<?php  } ?>
    </div>
        <?php
}


/* Details icon codes end line*/


	@include("footer.php");