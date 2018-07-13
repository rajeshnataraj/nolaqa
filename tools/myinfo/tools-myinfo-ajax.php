<?php
	@include("sessioncheck.php");
	
	$oper = isset($method['oper']) ? $method['oper'] : '';
	
/*-------- Update the personal details---------*/
if($oper == "update" and $oper != '')
	{ 
		$date=date("Y-m-d H:i:s");
		$fname = (isset($method['fname'])) ? $ObjDB->EscapeStrAll($method['fname']) : '';
		$lname = (isset($method['lname'])) ? $ObjDB->EscapeStrAll($method['lname']) : '';
		$email = (isset($method['email'])) ? $method['email'] : '';
		$myusername = (isset($method['username'])) ? $ObjDB->EscapeStrAll($method['username']) : '';
		$password = (isset($method['password'])) ? $method['password'] : '';
		$photo =  isset($method['hidimage']) ? $method['hidimage'] : '';
		
		$office = (isset($method['office'])) ? $method['office'] : '';
		$fax = (isset($method['fax'])) ? $method['fax'] : '';
		$mobile = (isset($method['mobile'])) ? $method['mobile'] : '';
		$home = (isset($method['home'])) ? $method['home'] : '';
		
		$address = (isset($method['address'])) ? $ObjDB->EscapeStrAll($method['address']) : '';
		$state = (isset($method['state'])) ? $method['state'] : '';
		$city = (isset($method['city'])) ? $method['city'] : '';
		$zipcode = (isset($method['zipcode'])) ? $method['zipcode'] : '';
		
			
		$ObjDB->NonQuery("UPDATE itc_user_master 
						SET fld_email = '".$email."',fld_username='".$myusername."',fld_password = '".fnEncrypt($password,$encryptkey)."', fld_fname = '".$fname."', 
							fld_lname = '".$lname."', fld_profile_pic = '".$photo."', fld_updated_by = '".$uid."', fld_updated_date = '".$date."' 
							WHERE fld_id = '".$uid."' AND fld_delstatus ='0'");

        require_once('../../includes/UserManager.php');
        $userrow = UserManager::db_fetch_userid($uid);
        $user = new UserManager($userrow);
        $temp =  $user->update_account_info($password, $email);
		$arr = array($office,$fax,$mobile,$home,$address,$state,$city,$zipcode);
		$j=3;
		for($i=0;$i<sizeof($arr);$i++)
		{
			if($arr[$i]!='')
			{
				$cnt=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) 
												FROM  itc_user_add_info 
												WHERE fld_user_id = '".$uid."' AND  fld_field_id = '".$j."'");
				if($cnt>0)
				{
					$ObjDB->NonQuery("UPDATE itc_user_add_info 
									SET fld_field_value = '".$arr[$i]."' 
									WHERE fld_user_id = '".$uid."' AND fld_field_id = '".$j."' AND fld_delstatus ='0'");
				}
				else if($cnt==0)
				{
					$ObjDB->NonQuery("INSERT INTO itc_user_add_info (fld_user_id,fld_field_id,fld_field_value) 
									VALUES ('".$uid."','".$j."','".$arr[$i]."')");
				}
			}
			$j++;
	}
	echo "success";
}
/*-------- Select the city based on the state---------*/
if($oper == "changecity" and $oper != "")
{
	$statevalue =  isset($method['statevalue']) ? $method['statevalue'] : '';
	?>
		<div class="selectbox">
		  <input type="hidden" name="ddlcity" id="ddlcity" value="" onchange="$('#ddlcity').valid();fn_changezip(this.value);" >
		  <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
			<span class="selectbox-option input-medium" data-option=""> Select city</span>
			<b class="caret1"></b>
		  </a>
		  <div class="selectbox-options">
			<input type="text" class="selectbox-filter" placeholder="Search select" >
			<ul role="options">
				<?php 
					$cityqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_cityname) AS cityname 
													FROM itc_state_city 
													WHERE fld_statevalue='".$statevalue."' AND fld_delstatus=0 
													ORDER BY fld_cityname ASC");
				   while($rowcity = $cityqry->fetch_assoc()){
					   extract($rowcity);
					   ?>
							<li><a tabindex="1" href="#" data-option="<?php echo ucfirst(strtolower($cityname));?>"><?php echo  ucfirst(strtolower($cityname))?></a></li>
					<?php 
					}?>       
			</ul>
		  </div>
		</div>
   <?php
}
/*-------- Select the zipcode based on the city---------*/	
if($oper == "changezip" and $oper != ""){
	$cityvalue =  isset($method['cityvalue']) ? $method['cityvalue'] : '';
	?>
		<div class="selectbox">
		  <input type="hidden" name="ddlzip" id="ddlzip" value="">
		  <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
			<span class="selectbox-option input-medium" data-option=""> Select zip</span>
			<b class="caret1"></b>
		  </a>
		  <div class="selectbox-options">
			<input type="text" class="selectbox-filter" placeholder="Search select" >
			<ul role="options">
				<?php 
					$zipqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_zipcode) AS zipcode 
												FROM itc_state_city 
												WHERE fld_cityname='".$cityvalue."' AND fld_delstatus=0 
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
	<?php
}				

/*--- Check student username already extis or not ---*/
if($oper=="checkstdname" and $oper != " " )
	{
		$stdid = isset($method['stdid']) ? $method['stdid'] : '0';
		$uname = isset($method['txtusername']) ? $ObjDB->EscapeStrAll($method['txtusername']) : '';
		
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
											FROM itc_user_master 
											WHERE LCASE(REPLACE(fld_username,' ',''))='".str_replace(' ','',$uname)."' AND fld_delstatus='0' AND fld_id<>'".$stdid."'");

		if($count == 0){ echo "true"; }	else { echo "false"; }
	}

	@include("footer.php");