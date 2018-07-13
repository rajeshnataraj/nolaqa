<?php
@include("sessioncheck.php");

$oper = isset($method['oper']) ? $method['oper'] : '';
/*--- Check School Name ---*/
	if($oper=="checkshlname" and $oper != " " )
	{
		$sid = isset($method['sid']) ? $method['sid'] : '0';
		$shlname = isset($method['shlname']) ? $ObjDB->EscapeStrAll($method['shlname']) : '';
		
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_school_master WHERE LCASE(REPLACE(fld_school_name,' ',''))='".str_replace(' ','',$shlname)."' AND fld_delstatus='0' AND fld_id<>'".$sid."'");

		if($count == 0){ echo "true"; }	else { echo "false"; }
	}
/*--- Select the city based the satate ---*/
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
													WHERE fld_statevalue='".$statevalue."' AND fld_delstatus=0 ORDER BY fld_cityname ASC");
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
/*--- Select the zipcode based on satate and city ---*/	
if($oper == "changezip" and $oper != ""){
		$cityvalue =  isset($method['cityvalue']) ? $method['cityvalue'] : '';
		$statevalue =  isset($method['statevalue']) ? $method['statevalue'] : '';
		?>
            <div class="selectbox">
              <input type="hidden" name="ddlzip" id="ddlzip" value="" onchange="$('#ddlzip').valid();">
              <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                <span class="selectbox-option input-medium" data-option=""> Select zip</span>
                <b class="caret1"></b>
              </a>
              <div class="selectbox-options">
                <input type="text" class="selectbox-filter" placeholder="Search zip" >
                <ul role="options">
                    <?php 
                        $zipqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_zipcode) AS zipcode 
													FROM itc_state_city 
													WHERE fld_cityname='".$cityvalue."' AND fld_statevalue='".$statevalue."' AND fld_delstatus=0 ORDER BY fld_zipcode ASC");
                       while($rowzip = $zipqry->fetch_assoc()){
						   extract($rowzip);
						   ?>
                                <li><a tabindex="1" href="#" data-option="<?php echo $zipcode;?>"><?php echo $zipcode;?></a></li>
                        <?php 
                        }?>       
                </ul>
              </div>
            </div>
		<?php
	}
/*--- Select the city based the satate ---*/		
if($oper == "changecity1" and $oper != ""){
		$statevalue =  isset($method['statevalue']) ? $method['statevalue'] : '';
		
		?>
            <div class="selectbox">
              <input type="hidden" name="ddlcity1" id="ddlcity1" value="" onchange="fn_changezip1(this.value);" >
              <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                <span class="selectbox-option input-medium" data-option=""> Select city</span>
                <b class="caret1"></b>
              </a>
              <div class="selectbox-options">
                <input type="text" class="selectbox-filter" placeholder="Search city" >
                <ul role="options">
                    <?php 
                        $cityqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_cityname) FROM itc_state_city WHERE fld_statevalue='".$statevalue."' AND fld_delstatus=0 ORDER BY fld_cityname ASC");
                       while($rowcity = $cityqry->fetch_object()){?>
                                <li><a tabindex="1" href="#" data-option="<?php echo $rowcity->fld_cityname;?>"><?php echo  ucwords(strtolower($rowcity->fld_cityname))?></a></li>
                        <?php 
                        }?>       
                </ul>
              </div>
            </div>
       <?php
	}
/*--- Select the zipcode based on satate and city ---*/	
if($oper == "changezip1" and $oper != ""){
		$cityvalue =  isset($method['cityvalue']) ? $method['cityvalue'] : '';
		$statevalue =  isset($method['statevalue']) ? $method['statevalue'] : '';
		?>
            <div class="selectbox">
              <input type="hidden" name="ddlzip1" id="ddlzip1" value="">
              <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                <span class="selectbox-option input-medium" data-option=""> Select zip</span>
                <b class="caret1"></b>
              </a>
              <div class="selectbox-options">
                <input type="text" class="selectbox-filter" placeholder="Search zip" >
                <ul role="options">
                    <?php 
                        $zipqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_zipcode) FROM itc_state_city WHERE fld_cityname='".$cityvalue."' AND fld_statevalue='".$statevalue."' AND fld_delstatus=0 ORDER BY fld_zipcode ASC");
                       while($rowzip = $zipqry->fetch_object()){?>
                                <li><a tabindex="1" href="#" data-option="<?php echo $rowzip->fld_zipcode;;?>"><?php echo $rowzip->fld_zipcode;?></a></li>
                        <?php 
                        }?>       
                </ul>
              </div>
            </div>
		<?php
	}	
/*--- Select the district based on satate and city ---*/		
if($oper == "changedistrict" and $oper != ""){
		$statevalue =  isset($method['statevalue']) ? $method['statevalue'] : '';
		$cityname =  isset($method['cityname']) ? $method['cityname'] : '';
		?>
        <div class="selectbox">
          <input type="hidden" name="ddldist" id="ddldist" value="" onchange="$('#ddldist').valid();">
          <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
            <span class="selectbox-option input-medium" data-option="">Select district</span>
            <b class="caret1"></b>
          </a>
          <div class="selectbox-options">
            <input type="text" class="selectbox-filter" placeholder="Search district" >
            <ul role="options">
                <?php 
                    $distqry = $ObjDB->QueryObject("SELECT fld_id,fld_district_name AS districtname, fn_shortname(fld_district_name,2) AS shortdname 
													FROM itc_district_master 
													WHERE fld_state='".$statevalue."' AND fld_city='".$cityname."' AND fld_delstatus='0' 
													ORDER BY fld_district_name ASC");
                    while($rowdist = $distqry->fetch_assoc()){ 
					extract($rowdist);
					?>
                           <li><a tabindex="1" href="#" data-option="<?php echo $fld_id;?>" onclick="addlicshl(<?php echo $fld_id;?>,1)" title="<?php echo $districtname;?>"><?php echo $shortdname;?></a></li>
                    <?php 
                    }?>       
            </ul>
          </div>
        </div>
 <?php
}
				
/*--- Save school details ---*/		
if($oper == "saveschool" and $oper != ""){
	
	try /**Here starts with saving the details uster master and district master tables**/
		{
		$date=date("Y-m-d H:i:s");
		$shlname =  isset($method['shlname']) ? $method['shlname'] : '';
		$address =  isset($method['address']) ? $method['address'] : '';
		$distid =  isset($method['distid']) ? $method['distid'] : '';
		$state =  isset($method['state']) ? $method['state'] : '';
		$city =  isset($method['city']) ? $method['city'] : '';
		$city =	ucwords(strtolower($city));
		$zipcode =  (isset($method['zipcode']) and $method['zipcode'] != 'undefined') ? $method['zipcode'] : '';
				
		$fname =  isset($method['fname']) ? $method['fname'] : '';
		$lname =  isset($method['lname']) ? $method['lname'] : '';
		$email =  isset($method['email']) ? $method['email'] : '';
		$logo =  isset($method['logo']) ? $method['logo'] : '';
        $photo =  isset($method['photo']) ? $method['photo'] : '';
        $hubid =  isset($method['hubid']) ? $method['hubid'] : '';
		
	
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
		
		/*lecense details getting----------*/
		$licensecount = isset($method['licensecount']) ? $method['licensecount'] : '';
		$ddllicense = isset($method['ddllicense']) ? $method['ddllicense'] : '';
		$numusers = isset($method['numusers']) ? $method['numusers'] : '';
		$startdate = isset($method['startdate']) ? $method['startdate'] : '';
		$enddate = isset($method['enddate']) ? $method['enddate'] : '';	
		$ddllicense = explode('~',$ddllicense);
		$numusers = explode('~',$numusers);
		$startdate = explode('~',$startdate);
		$enddate = explode('~',$enddate);	
		$uguid = gen_uuid();
		
		/**validation for the parameters and these below functions are validate to return true or false***/
		$validate_editid=true;
		$validate_shlname=true;
		$validate_address=true;
		$validate_state=true;
		$validate_city=true;
		$validate_zipcode=true;
		$validate_fname=true;
		$validate_lname=true;
		$validate_email=true;
		
		$validate_ddllicense=true;
		$validate_numusers=true;
		$validate_startdate=true;
		$validate_enddate=true;
		
		$validate_shlname=validate_datas($shlname,'lettersonly');
		$validate_address=validate_datas($address,'letterswithbasicpunc');
		$validate_fname=validate_datas($fname,'lettersonly');
		$validate_lname=validate_datas($lname,'lettersonly');
		$validate_email=validate_datatype($email,'email');
		
		$shlname =   $ObjDB->EscapeStrAll($shlname);
		$address = $ObjDB->EscapeStrAll($address);
		$fname =  $ObjDB->EscapeStrAll($fname);
		$lname =  $ObjDB->EscapeStrAll($lname);
		$address1 =   $ObjDB->EscapeStrAll($address1);
		$tags = $ObjDB->EscapeStrAll($tags);
		
		if($validate_shlname and $validate_address and $validate_fname and $validate_lname and $validate_email)
			{
			$userid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_user_master (fld_uuid, fld_email, fld_fname, fld_lname, fld_profile_id, fld_role_id, 
													fld_profile_pic, fld_district_id, fld_created_by, fld_created_date) 
												VALUES ('".$uguid."','".$email."','".$fname."','".$lname."','7','5','".$photo."','".$distid."','".$uid."','".$date."')");
				
			$shlid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_school_master (fld_district_id, fld_hubid, fld_school_name, fld_school_logo, fld_school_admin_id, 
													fld_street_address, fld_state, fld_city, fld_zipcode,  fld_created_date,fld_created_by) 
												VALUES ('".$distid."','".$hubid."','".$shlname."','".$logo."','".$userid."','".$address."','".$state."',
													'".$city."','".$zipcode."','".$date."','".$uid."')");
			/*--Tags insert-----*/	
			fn_taginsert($tags,14,$shlid,$uid);	
			
			$ObjDB->NonQuery("UPDATE itc_user_master SET fld_school_id = '".$shlid."',fld_updated_by='".$uid."' WHERE fld_id='".$userid."' AND fld_delstatus='0'");
			
			$arr = array($officeno,$faxno,$mobileno,$homeno,$address1,$state1,$city1,$zipcode1);
			$j=3;
			for($i=0;$i<sizeof($arr);$i++)
			{
				if($arr[$i]!='')
				{
					$ObjDB->NonQuery("INSERT INTO itc_user_add_info (fld_user_id,fld_field_id,fld_field_value) VALUES ('".$userid."','".$j."','".$arr[$i]."')");
				}
				$j++;
			}
			
			/*-------add license track for district----------*/
			for($i=0;$i<sizeof($ddllicense)-1;$i++){
				
				
				$lid = explode(',',$ddllicense[$i]);//(licenseid,0,distlicensetrack)
				$distqry = $ObjDB->QueryObject("SELECT a.fld_remain_users AS distremainusers, b.`fld_duration_type` AS lictype, b.fld_duration as licduration 
												FROM itc_license_track AS a, `itc_license_master` AS b 
												WHERE a.`fld_license_id`=b.`fld_id` AND a.fld_district_id = '".$distid."' AND a.fld_license_id='".$lid[0]."' 
													AND a.fld_school_id ='0' and a.fld_id='".$lid[2]."'"); 
				$res = $distqry->fetch_assoc();
				extract($res);			
				$pcount = $distremainusers;
				$remusers = $pcount- $numusers[$i];
				
				$ObjDB->NonQuery("UPDATE itc_license_track SET fld_remain_users ='".$remusers."', fld_updated_by='".$uid."', 
									fld_updated_date='".date("Y-m-d H:i:s")."'  
								WHERE fld_district_id='".$distid."' AND fld_license_id='".$lid[0]."' AND fld_school_id=0 and fld_id='".$lid[2]."'");
						
				$ObjDB->NonQuery("INSERT INTO itc_license_track (fld_license_id,fld_district_id, fld_school_id, fld_distlictrack_id, 
									fld_no_of_users, fld_remain_users,fld_start_date, fld_end_date, fld_created_by,fld_created_date) 
								VALUES('".$lid[0]."','".$distid."','".$shlid."','".$lid[2]."','".$numusers[$i]."','".$numusers[$i]."',
									'".date('Y-m-d',strtotime($startdate[$i]))."','".date('Y-m-d',strtotime($enddate[$i]))."','".$uid."',
									'".date("Y-m-d H:i:s")."')");
				
			}
			
			/*-------Mail----------*/
			$html_txt = '';
			$headers = '';
			$mailtitle = $shlname;
			
			$subj = "You're invited to join our learning management system";
			$random_hash = md5(date('r', time())); 
							
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=utf-8" . "\r\n"; 
			$headers .= "From: Synergy ITC <do_not_reply@pitsco.com>" . "\r\n";	
		
			$html_txt = '<table cellpadding="0" cellspacing="0" border="0" width="98%"><tbody><tr><td style="padding:15px;padding-top:10px;padding-bottom:40px;font-family:Helvetica,Arial,sans-serif;font-size:16px;
color:#222222;text-align:left" valign="top">
<h1 style="font-family:Helvetica,Arial,sans-serif;color:#222222;font-size:28px;line-height:normal;letter-spacing:-1px">
Invitation to create a Synergy ITC account</h1><p>Hello <font style="font-style: italic;">'.$shlname.':</font></p><p>Thank you for choosing Pitsco Education and Synergy ITC. In order to activate your account, click on the link below to choose a user name and password.</p><p><strong>Click this link to get started:</strong><br /><a href="'.$domainame.'register.php?e='.md5($userid).'">'.$domainame.'register.php?e='.md5($userid).'</a></p>
    <p><b>Access your <font style="font-style: italic;">Synergy ITC</font> account: &nbsp;&nbsp; By clicking on this link and logging into ITC, you agree the following agreement.</b><br><a href="'.__HOSTADDR__.'" target="_blank">'.__HOSTADDR__.'</a></p>
    <p align="center"><br><br>
          <b>PITSCO, INC. SYNERGY ITC LICENSE AGREEMENT</b><br>
39527<br><br>



<p style="margin-left:150px;width:70%;text-align:left;">YOU SHOULD CAREFULLY READ THE FOLLOWING TERMS AND CONDITIONS BEFORE ACCESSING THE SOFTWARE. ACCESSING THE SOFTWARE INDICATES YOUR ACCEPTANCE OF THESE TERMS AND CONDITIONS.<br><br> 

Pitsco, Inc. provides these programs and licenses for use directly or through authorized dealers. You assume responsibility for the selection of the programs to achieve your intended results, and for the installation, use, and results obtained from the programs.<br><br>

<b>LICENSE</b><br>
Purchase or annual license of Synergy ITC entitles you to:<br><br>

a.	Access of Synergy ITC from the web.<br>
b.	Access of Synergy ITC by classroom or location equal to the number of Synergy ITC licenses purchased.<br><br>

YOU MAY NOT USE, COPY , MODIFY, OR TRANSFER THE PROGRAMS, OR ANY COPY, MODIFICATION, OR MERGED PORTION, IN WHOLE OR IN PART, EXCEPT AS EXPRESSLY PROVIDED FOR IN THIS LICENSE. IF YOU OTHERWISE USE OR TRANSFER POSSESSION OR ANY COPY, MODIFICATION, OR MERGED PORTION OF THE PROGRAMS TO ANOTHER PARTY, YOUR LICENSE IS AUTOMATICALLY TERMINATED. <br><br>

<b>TERM</b><br> 
The license is effective until terminated. You may terminate it at any time by destroying the programs together with all copies, modification, and merged portions in any form. It will also terminate upon conditions set forth elsewhere in this Agreement or if you fail to comply with any term or condition of this Agreement. You agree upon such termination to destroy the programs together with all copies, modifications, and merged portions in any form. If you purchased an annual license, the term shall expire in one year. <br><br>

LIMITED WARRANTY<br>
THE PROGRAMS ARE PROVIDED �AS IS� WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESS OR IMPLIED, INCLUDING, BUT NOT LIMITED TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. THE ENTIRE RISK AS TO THE QUALITY AND PERFORMANCE OF THE PROGRAMS IS WITH YOU. SHOULD THE PROGRAMS PROVE DEFECTIVE, YOU (AND NOT PITSCO EDUCATION) ASSUME THE ENTIRE COST OF ALL NECESSARY SERVICING, REPAIR, OR CORRECTION.<br><br>

SOME STATES AND COUNTRIES DO NOT ALLOW THE EXCLUSION OF IMPLIED WARRANTIES, SO THE ABOVE EXCLUSION MAY NOT APPLY TO YOU. THIS WARRANTY GIVES YOU SPECIFIC LEGAL RIGHTS AND YOU MAY ALSO HAVE OTHER RIGHTS WHICH VARY BY STATE OR COUNTRY.<br><br>

Pitsco, Inc. does not warrant that the functions contained in the programs will meet your requirements or that the operation of the programs will be uninterrupted or error-free.<br><br>

However, Pitsco, Inc. warrants the software on which the programs are furnished, to be free from defects in materials and workmanship under normal use for a period of ninety (90) days from the date of delivery to you as evidenced by a copy of your receipt. <br><br>
 

<b>LIMITATIONS OF REMEDIES</b><br>
Pitsco, Inc.�s entire liability and your exclusive remedy shall be:<br><br>

1.	The replacement of any software not meeting Pitsco, Inc.�s �Limited Warranty� and which is returned to Pitsco, Inc. <br><br>

IN NO EVENT WILL PITSCO, INC. BE LIABLE TO YOU FOR ANY DAMAGES, INCLUDING ANY LOST PROFITS, LOST SAVINGS, OR OTHER INCIDENTAL OR CONSEQUENTIAL DAMAGES ARISING OUT OF THE USE OR INABILITY TO USE SUCH PROGRAMS EVEN IF PITSCO, INC. HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES, OR FOR ANY CLAIM BY ANY OTHER PARTY.<br><br>

SOME STATES DO NOT ALLOW THE LIMITATION OR EXCLUSION OF LIABILITY FOR INCIDENTAL OR CONSEQUENTIAL DAMAGES SO THE ABOVE LIMITATION OR EXCLUSION MAY NOT APPLY TO YOU.<br><br>

<b>GENERAL</b><br>
You may not sublicense, assign, or transfer the license or the programs except as expressly provided in this Agreement. Any attempt otherwise to sublicense, assign, or transfer any of the rights, duties, or obligations hereunder is void.<br><br>

This Agreement will be governed by the laws of the country in which you bought the license.<br><br>

YOU ACKNOWLEDGE THAT YOU HAVE READ THIS AGREEMENT, UNDERSTAND IT AND AGREE TO BE BOUND BY ITS TERMS AND CONDITIONS. YOU FURTHER AGREE THAT IT IS THE COMPLETE AND EXCLUSIVE STATEMENT OF THE AGREEMENT BETWEEN US WHICH SUPERSEDES ANY PROPOSAL OR PRIOR AGREEMENT, ORAL OR WRITTEN, AND ANY OTHER COMMUNICATIONS BETWEEN US RELATING TO THE SUBJECT MATTER OF THIS AGREEMENT.<br><br>

    </p>
<p>If you are in need of technical support, please don&lsquo;t hesitate to contact our industry-leading customer support line at 800-774-4552.</p><p>Thank you,</p><p><strong>Pitsco Education</strong><br>800-774-4552<br>www.pitsco.com</p><p align="center"  style="font-style: italic;">Thank you for being a loyal Pitsco customer! <br>We appreciate all you do for students!</p>
</td></tr></tbody></table>';
			$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => $email,'subject' => $subj, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
			$client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
			$client->call('SendJangoMailTransactional', $param, '', '', false, true);
			echo "success";
			} // End validate if
			else{
				echo "fail";
			}
			
	} // try ends
	catch(Exception $e)
	{
		 echo "fail";
	}
}
/*--- Update school details ---*/	
if($oper == "updateschool" and $oper != ""){

	try /**Here starts with saving the details uster master and district master tables**/
		{	
		$date=date("Y-m-d H:i:s");
		$editid =  isset($method['id']) ? $method['id'] : '0';
		$shlname =  isset($method['shlname']) ? $method['shlname'] : '';
		$address =  isset($method['address']) ? $method['address'] : '';
		$distid =  isset($method['distid']) ? $method['distid'] : '';
		$state =  isset($method['state']) ? $method['state'] : '';
		$city =  isset($method['city']) ? $method['city'] : '';
		$city =	ucwords(strtolower($city));
		$zipcode =  isset($method['zipcode']) ? $method['zipcode'] : '';
            $hubid =  isset($method['hubid']) ? $method['hubid'] : '';
				
		$fname =  isset($method['fname']) ? $method['fname'] : '';
		$lname =  isset($method['lname']) ? $method['lname'] : '';
		$email =  isset($method['email']) ? $method['email'] : '';
		$logo =  isset($method['logo']) ? $method['logo'] : '';
		$photo =  isset($method['photo']) ? $method['photo'] : '';
		
		$address1 =  isset($method['address1']) ? $method['address1'] : '';
		$state1 =  isset($method['state1']) ? $method['state1'] : '';
		$city1 =  isset($method['city1']) ? $method['city1'] : '';
		$city1 =	ucwords(strtolower($city1));
		$zipcode1 =  isset($method['zipcode1']) ? $method['zipcode1'] : '';
		$officeno =  isset($method['officeno']) ? $method['officeno'] : '';
		$faxno =  isset($method['faxno']) ? $method['faxno'] : '';
		$mobileno =  isset($method['mobileno']) ? $method['mobileno'] : '';
		$homeno =  isset($method['homeno']) ? $method['homeno'] : '';
		
		/*lecense details getting----------*/
		$licensecount = isset($method['licensecount']) ? $method['licensecount'] : '';
		$ddllicense = isset($method['ddllicense']) ? $method['ddllicense'] : '';
		$numusers = isset($method['numusers']) ? $method['numusers'] : '';
		$startdate = isset($method['startdate']) ? $method['startdate'] : '';
		$enddate = isset($method['enddate']) ? $method['enddate'] : '';	
		$ddllicense = explode('~',$ddllicense);
		$numusers = explode('~',$numusers);
		$startdate = explode('~',$startdate);
		$enddate = explode('~',$enddate);
		$uguid = gen_uuid();	
		$tags = isset($method['tags']) ? $method['tags'] : '';
		
		/**validation for the parameters and these below functions are validate to return true or false***/
		$validate_editid=true;
		$validate_shlname=true;
		$validate_address=true;
		$validate_state=true;
		$validate_city=true;
		$validate_zipcode=true;
		$validate_fname=true;
		$validate_lname=true;
		$validate_email=true;
		
		$validate_ddllicense=true;
		$validate_numusers=true;
		$validate_startdate=true;
		$validate_enddate=true;
		
		if($editid!=0) $validate_editid=validate_datatype($editid,'int');
		$validate_shlname=validate_datas($shlname,'lettersonly');
		$validate_address=validate_datas($address,'letterswithbasicpunc');
		$validate_fname=validate_datas($fname,'lettersonly');
		$validate_lname=validate_datas($lname,'lettersonly');
		$validate_email=validate_datatype($email,'email');
		
		$shlname =   $ObjDB->EscapeStrAll($shlname);
		$address = $ObjDB->EscapeStrAll($address);
		$fname =  $ObjDB->EscapeStrAll($fname);
		$lname =  $ObjDB->EscapeStrAll($lname);
		$address1 =   $ObjDB->EscapeStrAll($address1);
		$tags = $ObjDB->EscapeStrAll($tags);
		
		if($validate_shlname and $validate_address and $validate_fname and $validate_lname and $validate_email)
				{
				/*---tags------*/
				fn_tagupdate($tags,14,$editid,$uid);
						
				$ObjDB->NonQuery("UPDATE itc_school_master 
								SET fld_school_name = '".$shlname."', fld_hubid = '".$hubid."', fld_school_logo = '".$logo."', fld_street_address = '".$address."', 
									fld_state = '".$state."',fld_city = '".$city."', fld_zipcode = '".$zipcode."', 
									fld_updated_by = '".$uid."', fld_updated_date='".$date."' 
								WHERE fld_id = '".$editid."'");
				
				$shluserid = $ObjDB->SelectSingleValueInt("SELECT fld_school_admin_id 
														FROM itc_school_master 
														WHERE fld_id ='".$editid."' AND fld_delstatus ='0'");	
				
				$ObjDB->NonQuery("UPDATE itc_user_master 
								SET fld_email = '".$email."', fld_fname = '".$fname."', fld_lname = '".$lname."', fld_profile_pic = '".$photo."', 
									fld_updated_by= '".$uid."', fld_updated_date = '".$date."' 
								WHERE fld_id = '".$shluserid."' AND fld_delstatus ='0'");
				
				$arr = array($officeno,$faxno,$mobileno,$homeno,$address1,$state1,$city1,$zipcode1);
				$j=3;
				for($i=0;$i<sizeof($arr);$i++)
				{
					if($arr[$i]!='')
					{
						$cnt=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) 
														FROM  itc_user_add_info 
														WHERE fld_user_id = '".$shluserid."' AND  fld_field_id = '".$j."'");
						if($cnt>0)
						{
						$ObjDB->NonQuery("UPDATE itc_user_add_info SET fld_field_value = '".$arr[$i]."' 
										WHERE fld_user_id = '".$shluserid."' AND fld_field_id = '".$j."' AND fld_delstatus ='0'");
						}
						else if($cnt==0)
						{
							$ObjDB->NonQuery("INSERT INTO itc_user_add_info (fld_user_id,fld_field_id,fld_field_value) 
											VALUES ('".$shluserid."','".$j."','".$arr[$i]."')");
						}
					}
					$j++;
				}
				
				/*-------update license track for School----------*/
				for($i=0;$i<sizeof($ddllicense)-1;$i++){
					
					$lid = explode(',',$ddllicense[$i]);//(licenseid,licensetrack,distlictrackid)
					$distqry = $ObjDB->QueryObject("SELECT fld_start_date, fld_end_date 
													FROM itc_license_track 
													WHERE fld_district_id = '".$distid."' AND fld_school_id ='0' AND fld_license_id='".$lid[0]."' AND fld_id='".$lid[2]."'"); 
					$res = $distqry->fetch_Object();
					
					$chk = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
														FROM itc_license_track 
														WHERE fld_district_id='".$distid."' AND fld_school_id='".$editid."' AND fld_license_id='".$lid[0]."' 
															AND fld_id='".$lid[1]."'");
					if($chk==0){
							$prelid = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
																	FROM itc_license_track 
																	WHERE fld_district_id='".$distid."' AND fld_school_id='".$editid."' AND fld_license_id='".$lid[0]."' 
																		AND fld_delstatus='0'");
							if($renewal[$i]==1)
								$auto="yes / ".$rcount[$i]." times";
							else
								$auto="no";
							if($prelid>0){
								$subject = "Lease Renewal";					
								$content = '<tr><td valign="top" align="left">The License below has been renewed:</td></tr>';
							}
							else{
								$subject = " Assigned Lease";
								$content = '<tr><td valign="top" align="left">The License below has been assigned:</td></tr>';
							}
						$ObjDB->NonQuery("UPDATE itc_license_track 
										SET fld_upgrade='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
										WHERE fld_school_id='".$editid."' AND fld_district_id='".$distid."' AND fld_license_id='".$lid[0]."'");
					
						$licensedet = $ObjDB->QueryObject("SELECT a.fld_remain_users AS distremainusers, b.`fld_duration_type` AS lictype, 
															b.fld_duration as licduration 
														FROM itc_license_track AS a, `itc_license_master` AS b 
														WHERE a.`fld_license_id`=b.`fld_id` AND a.fld_district_id = '".$distid."' 
															AND a.fld_license_id='".$lid[0]."' AND a.fld_school_id ='0' and a.fld_id='".$lid[2]."'"); 
						$rowlicense = $licensedet->fetch_assoc();
						extract($rowlicense);			
						
						
						$pcount = $distremainusers;
						$remusers = $pcount- $numusers[$i];
						
						$ObjDB->NonQuery("UPDATE itc_license_track 
										SET fld_remain_users ='".$remusers."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'  
										WHERE fld_district_id='".$distid."' AND fld_license_id='".$lid[0]."' AND fld_school_id=0 and fld_id='".$lid[2]."'");
								
						$ObjDB->NonQuery("INSERT INTO itc_license_track (fld_license_id,fld_district_id, fld_school_id, fld_distlictrack_id, 
											fld_no_of_users, fld_remain_users, fld_start_date, fld_end_date, fld_created_by,fld_created_date) 
										VALUES('".$lid[0]."','".$distid."','".$editid."','".$lid[2]."','".$numusers[$i]."','".$numusers[$i]."',
											'".date('Y-m-d',strtotime($startdate[$i]))."','".date('Y-m-d',strtotime($enddate[$i]))."','".$uid."','".$date."')");
						

						//send notifications to users
							$licensename = $ObjDB->SelectSingleValue("SELECT fld_license_name 
																	FROM itc_license_master 
																	WHERE fld_id='".$lid[0]."' AND fld_delstatus='0'");
							
							$html_txt = '';
							$headers = '';
							$up = "'";
							
							$qry = $ObjDB->QueryObject("SELECT fld_fname, fld_lname, fld_email, fld_username, fld_id, fld_profile_id 
														FROM itc_user_master 
														WHERE fld_district_id='".$distid."' AND fld_school_id='".$editid."' 
															AND fld_user_id='0' AND fld_profile_id<>10 AND fld_delstatus='0'");
							
							if($qry->num_rows>0)
							{
								while($rowqry = $qry->fetch_assoc())
								{
									extract($rowqry);
									
									if($fld_email!='')
									{
										
										$subj = $licensename." - ".$subject;
										$random_hash = md5(date('r', time())); 
														
										$headers = "MIME-Version: 1.0" . "\r\n";
										$headers .= "Content-type:text/html;charset=utf-8" . "\r\n"; 
										$headers .= "From: Synergy ITC <do_not_reply@pitsco.com>" . "\r\n";													
										$html_txt = '<table width="98%" cellpadding="10" cellspacing="0"><tr><td valign="top" align="left"><br />Hi '.$fld_fname.', <br /></td></tr>'.$content.'
										<tr><td valign="top" align="left">Lease: '.$licensename.'<br />
										Start date: '.date("m/d/Y",strtotime($startdate[$i])).'<br />
										End date: '.date("m/d/Y",strtotime($enddate[$i])).'<br />							
										Available seats: '.$numusers[$i].'<br /><br />
										</td></tr>'.fn_getcontent($lid[0]).'</table>';						
										$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => $fld_email,'subject' => $subj, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
										$client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
										$client->call('SendJangoMailTransactional', $param, '', '', false, true);
									}
								}
							}	
							//end notification
					}
					else {
						
						/*--------- Tracking User Count ---------*/			
						$shldet = $ObjDB->QueryObject("SELECT a.fld_no_of_users as prevtotusers, a.fld_remain_users as prevremainusers, 
														b.`fld_duration_type` AS lictype, b.fld_duration as licduration 
													FROM itc_license_track AS a, `itc_license_master` AS b 
													WHERE a.`fld_license_id`=b.`fld_id` AND a.fld_school_id='".$editid."' and a.fld_district_id='".$distid."' 
														and a.fld_license_id='".$lid[0]."' and a.fld_id='".$lid[1]."' ");
						$res=$shldet->fetch_assoc();
						extract($res);
						
						if($prevtotusers < $numusers[$i]) {
							$curradditionalusers = 	($numusers[$i] - $prevtotusers);
							$finaltotusers = ($prevtotusers + $curradditionalusers);
							$finalremusers = ($prevremainusers + $curradditionalusers);
						}
						else {
							$curradditionalusers = 	($prevtotusers - $numusers[$i]);
							$finaltotusers = ($prevtotusers - $curradditionalusers);
							$finalremusers = ($prevremainusers - $curradditionalusers);
						}
						
							$ObjDB->NonQuery("UPDATE itc_license_track SET fld_no_of_users='".$finaltotusers."', fld_remain_users='".$finalremusers."', 
												fld_start_date='".date('Y-m-d',strtotime($startdate[$i]))."', 
												fld_end_date='".date('Y-m-d',strtotime($enddate[$i]))."',
												fld_updated_by='".$uid."', fld_delstatus='0', fld_updated_date='".date("Y-m-d H:i:s")."' 
											WHERE fld_school_id='".$editid."' and fld_district_id='".$distid."' and fld_license_id='".$lid[0]."' and fld_id='".$lid[1]."'");
						
						/*--------- Decrease User Count in Distirct Table ---------*/
							$totalusers = $ObjDB->SelectSingleValueInt("SELECT fld_remain_users 
																		FROM itc_license_track 
																		WHERE fld_district_id='".$distid."' AND fld_school_id='0' and fld_license_id='".$lid[0]."' 
																			and fld_id='".$lid[2]."'");
							
							if($prevtotusers < $numusers[$i]) {
								$finaldistusers = ($totalusers - $curradditionalusers);
							}
							else {
								$finaldistusers = ($totalusers + $curradditionalusers);
							}
						
							if($curradditionalusers != 0){
								$ObjDB->NonQuery("UPDATE itc_license_track 
												SET fld_remain_users='".$finaldistusers."', fld_updated_by='".$uid."', 
													fld_updated_date='".date("Y-m-d H:i:s")."' 
												WHERE fld_district_id='".$distid."' AND fld_school_id='0' and fld_license_id='".$lid[0]."' 
													and fld_id='".$lid[2]."'");
							}
									
					}
				}
				echo "success";
			} // End validate if
			else{
				echo "fail";
			} 
		}// Ends try
		catch(Exception $e)
		{
			 echo "fail1";
		}
}
/*--- Delete school details ---*/	
if($oper == "deletschool" and $oper != ""){
	
	$delid =  isset($method['editid']) ? $method['editid'] : '';
	$validate_delid=true;
	if($editid!=0)$validate_delid=validate_datatype($delid,'int');
	if($validate_delid){
		$ObjDB->NonQuery("UPDATE itc_school_master 
						SET fld_delstatus = '1', fld_deleted_by = '".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' 
						WHERE fld_id = '".$delid."'");
		$ObjDB->NonQuery("UPDATE itc_user_master 
						SET fld_delstatus = '1', fld_deleted_by = '".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' 
						WHERE fld_school_id = '".$delid."'");
		
		$ObjDB->NonQuery("UPDATE itc_license_track 
						SET fld_delstatus='1', fld_deleted_by = '".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."'  
						WHERE fld_school_id='".$delid."'");	
			
		$distirctid = $ObjDB->SelectSingleValueInt("SELECT fld_district_id 
													FROM itc_school_master 
													WHERE fld_id='".$delid."'");
		if($distirctid != 0){
			$prevremainusersdist = $ObjDB->SelectSingleValueInt("SELECT fld_remain_users 
																FROM itc_license_track 
																WHERE fld_district_id='".$distirctid."' AND fld_school_id='0'");
			$prevtotusers = $ObjDB->SelectSingleValueInt("SELECT fld_no_of_users 
														FROM itc_license_track 
														WHERE fld_school_id='".$delid."'");
			
			
			$ObjDB->NonQuery("UPDATE itc_license_track 
							SET fld_remain_users='".($prevremainusersdist+$prevtotusers)."',fld_updated_date='".date("Y-m-d H:i:s")."'  
							WHERE fld_district_id='".$distirctid."' AND fld_school_id='0'");	
		}
		echo "success";
	}
	else{
		echo "fail";
	}
}
/*--- Add license to the school ---*/	
if($oper == "addlicenseshl" and $oper != ""){
	$distid =  isset($method['distid']) ? $method['distid'] : '';
	$count =  isset($method['count']) ? $method['count'] : '0';
	
	$totalhidlicense = $ObjDB->SelectSingleValue("SELECT COUNT(DISTINCT(fld_license_id)) 
												FROM itc_license_track 
												WHERE fld_district_id='".$distid."' AND fld_school_id=0 AND fld_delstatus='0' AND '".date("Y-m-d")."' 
												BETWEEN fld_start_date AND fld_end_date");				 
	
	if($totalhidlicense == 1){
		$distlicdetails = $ObjDB->QueryObject("SELECT b.fld_remain_users as remain, a.fld_id as distlicid,a.fld_license_name as licname, b.fld_id as disttrackid,
												 b.fld_start_date as sdate, b.fld_end_date as edate 
												FROM itc_license_master AS a,itc_license_track AS b 
												WHERE a.fld_id=b.fld_license_id AND fld_district_id='".$distid."' AND a.fld_delstatus='0' AND b.fld_delstatus='0' 
													AND b.fld_end_date>=NOW() AND fld_school_id=0");
					$row=$distlicdetails->fetch_assoc();
					extract($row);
	?>
    	<div class="row" id="lic<?php echo $count; ?>">
			<div class="four columns">
            	Licenses<span class="fldreq">*</span>&nbsp;&nbsp;Available student seats: <?php echo $remain;?>
				<dl class='field row'>
					<dt class='dropdown'>                    
						<div class="selectbox">
							<input type="hidden" name="ddllic<?php echo $count; ?>" id="ddllic<?php echo $count; ?>" value="<?php echo $distlicid.",".'0'.",".$disttrackid; ?>">
							<a class="selectbox-toggle" tabindex="19" role="button" data-toggle="selectbox" href="#">
							<span class="selectbox-option input-medium" data-option="<?php echo $distlicid; ?>"><?php echo $licname;?></span>
							<b class="caret1"></b>
							</a>
						</div>
					</dt>
				</dl> 
			</div>
			<div class="one columns">
            	Seats<span class="fldreq">*</span> 
				<dl class='field row'>
					<dt class='text'>
						<input  id="noofusers<?php echo $count; ?>" name="noofusers<?php echo $count; ?>"  class="quantity" onblur="fn_chkusercountshl(<?php echo $count;?>,0)" placeholder='Number of users' tabindex="20" type='text' value="" >
					</dt>
				</dl>
			</div>
			<div class="two columns">
                    Start date<span class="fldreq">*</span> 
                     <dl class='field row'>
                        <dt class="text">
                            <input  id="sdate<?php echo $count;?>" tabindex="19" name="sdate<?php echo $count;?>" placeholder='Start Date'type='text' value="<?php echo date("m/d/Y", strtotime($sdate));?>" readonly />
                        </dt>
                     </dl>
                </div>
                <div class="two columns">
                    End date<span class="fldreq">*</span> 
                    <dl class='field row'>
                        <dt class='text'>
                            <input placeholder='End Date' id="edate<?php echo $count;?>" name="edate<?php echo $count;?>" value="<?php echo date("m/d/Y", strtotime($edate));?>" readonly />
                        </dt>
                    </dl>
                </div> 
                 </div>                    
            </div>                     
            <input type="hidden" id="currentlicense<?php echo $count;?>" value="" />
            <input type="hidden" id="errorcount<?php echo $count;?>" value="0" />
            <script>
				$("#noofusers<?php echo $count; ?>").keypress(function (e) {
					if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
						return false;
					}
				});		
			</script>
    <?php
	}
	else{		
		?>
        <div class="row" id="lic<?php echo $count;?>">
            <div class="row">
                <div class="four columns">
                    Licenses<span class="fldreq">*</span> &nbsp;&nbsp;<span id="remainusers<?php echo $count; ?>"></span>
                     <dl class='field row'>
                        <dt class="dropdown">
                            <div class="selectbox">
                            <input type="hidden" name="ddllic<?php echo $count;?>" id="ddllic<?php echo $count;?>" value="" onchange="$(this).valid()" />
                                <a class="selectbox-toggle" tabindex="17" role="button" data-toggle="selectbox" href="#">
                                <span class="selectbox-option input-medium" data-option="">Select License</span>
                                <b class="caret1"></b>
                                </a>
                                <div class="selectbox-options">
                                <input type="text" class="selectbox-filter" placeholder="Search select" />
                                    <ul role="options">
                                    <?php 						
                                    $licqry = $ObjDB->QueryObject("SELECT a.fld_id,a.fld_license_name AS licensename,b.fld_id as disttrackid  
																FROM itc_license_master AS a,itc_license_track AS b 
																WHERE a.fld_id=b.fld_license_id AND fld_district_id='".$distid."' AND a.fld_delstatus='0' 
																	AND b.fld_delstatus='0' AND b.fld_end_date>=NOW() AND b.fld_school_id=0  
																ORDER BY licensename ASC");
                                    $i=1;
                                    while($row = $licqry->fetch_assoc()){
										extract($row);
										?>
                                            <li><a tabindex="19" href="#" data-option="<?php echo $fld_id.",".'0'.",".$disttrackid;?>" onclick="fn_licenseclick(<?php echo $fld_id;?>,<?php echo $count;?>,<?php echo $disttrackid; ?>)" id="option<?php echo $fld_id;?>" title="<?php echo $licensename;?>" class="tooltip"><?php echo $licensename;?> </a></li>
                                    <?php 
                                    $i++;
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
                        <dt class="text">
                            <input  id="noofusers<?php echo $count;?>" tabindex="18" name="noofusers<?php echo $count;?>" placeholder='users' type='text' value=""  readonly="readonly" onblur="fn_chkusercountshl(<?php echo $count;?>,0)" />
                        </dt>
                     </dl>
                </div>
                <div class="two columns">
                    Start date<span class="fldreq">*</span> 
                     <dl class='field row'>
                        <dt class="text">
                            <input  id="sdate<?php echo $count;?>" tabindex="19" name="sdate<?php echo $count;?>" placeholder='Start Date'type='text' value="" readonly />
                        </dt>
                     </dl>
                </div>
                <div class="two columns">
                    End date<span class="fldreq">*</span> 
                    <dl class='field row'>
                        <dt class='text'>
                            <input placeholder='End Date' id="edate<?php echo $count;?>" name="edate<?php echo $count;?>" value="" readonly />
                        </dt>
                    </dl>
                </div>
                <div id="grace<?php echo $count; ?>"> </div> 
                <?php if($count!=2){?> 
                <div class='one columns' id="remupgrade_<?php echo $count;?>">
                    remove
                    <p class='btn twelve columns'>
                        <a onclick="fn_removeshllicense(<?php echo $count;?>,0,0)" id="rmove"> - </a>
                    </p>     
                </div>
                <?php }?>
             </div>                
        </div>                     
        <input type="hidden" id="currentlicense<?php echo $count;?>" value="" />
        <input type="hidden" id="errorcount<?php echo $count;?>" value="0" />
        <script>
			$("#noofusers<?php echo $count; ?>").keypress(function (e) {
				if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
					return false;
				}
			});		
		</script>	
	<?php
	}
	echo "~".$totalhidlicense;
}

/*--- Upgrade license to the school ---*/	
if($oper=="upgradelicense" and $oper != " " )
{
	$count = isset($_POST['count']) ? $_POST['count'] : 0;	
	$lid = isset($_POST['lid']) ? $_POST['lid'] : 0;
	$dtrackid = isset($_POST['dtrackid']) ? $_POST['dtrackid'] : 0;		 
	$strackid = isset($_POST['strackid']) ? $_POST['strackid'] : 0;		 
	
	?>
    <div class='row' id="lic<?php echo $count;?>">       
        <div class='four columns'>
        	Licenses<span class="fldreq">*</span>&nbsp;&nbsp;Available student seats: 
				<?php echo $ObjDB->SelectSingleValueInt("SELECT fld_remain_users 
														FROM itc_license_track 
														WHERE fld_id='".$dtrackid."'");?> 
            <dl class='field row'>
                <dt class='dropdown'> 
                    <div class="selectbox">
                        <input type="hidden" name="ddllic<?php echo $count;?>" id="ddllic<?php echo $count;?>" value="<?php echo $lid; ?>,0,<?php echo $dtrackid; ?>" onchange="$(this).valid()" />
                        <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                            <span class="selectbox-option input-medium" data-option=""><?php echo $ObjDB->SelectSingleValue("SELECT fld_license_name 
																															FROM itc_license_master 
																															WHERE fld_id='".$lid."'");?>
                            </span>
                            <b class="caret1"></b>                                                
                        </a> 
                    </div>  
                </dt> 
            </dl>                                 
        </div>
        <div class='one columns'>
        	Seats<span class="fldreq">*</span> 
            <dl class='field row'>
                <dt class='text'>
                    <input  id="noofusers<?php echo $count;?>" name="noofusers<?php echo $count;?>"  placeholder="users" type='text' value="<?php echo $ObjDB->SelectSingleValueInt("SELECT fld_no_of_users FROM itc_license_track WHERE fld_id='".$strackid."'"); ?>" onblur="fn_chkusercountshl(<?php echo $count;?>,0)" /> <!--onblur="fn_chkusercount(<?php //echo $count;?>,0,0);"-->
                </dt>                                            
            </dl>
        </div>   
        <div class='two columns'>
          Start date<span class="fldreq">*</span> 
          <dl class='field row'>
              <dt class='text'>
                 <input  id="sdate<?php echo $count;?>" name="sdate<?php echo $count;?>"  placeholder='Start Date' type='text' value="<?php echo date("m/d/Y",strtotime($ObjDB->SelectSingleValue("SELECT fld_start_date FROM itc_license_track WHERE fld_id='".$dtrackid."'")));?>" readonly />
              </dt>                                          
          </dl> 
        </div> 
        <div class="two columns">
        	End date<span class="fldreq">*</span> 
            <dl class='field row'>
                <dt class='text'>
                	<input placeholder='End Date' id="edate<?php echo $count;?>" name="edate<?php echo $count;?>" readonly value="<?php echo date("m/d/Y",strtotime($ObjDB->SelectSingleValue("SELECT fld_end_date FROM itc_license_track WHERE fld_id='".$dtrackid."'")));?>">
                </dt>
            </dl>
        </div>
        <div class='one columns'>
            remove
            <p class='btn twelve columns'>
                <a onclick="fn_removeshllicense(<?php echo $count;?>,0,<?php echo $dtrackid; ?>)" id="rmove"> - </a>
            </p>     
        </div>
        <input type="hidden" id="currentlicense<?php echo $count;?>" value="<?php echo $lid; ?>" /> 
     </div>
    <?php 	
}
/*--- Check usecount to thwe school ---*/	
if($oper=="chkusercountshl" and $oper != " ")
{	
	$editid = isset($method['editid']) ? $method['editid'] : 0; 
	$distid = isset($method['distid']) ? $method['distid'] : 0;
	$value = isset($method['value']) ? $method['value'] : 0;
	$licenseid = isset($method['licenseid']) ? $method['licenseid'] : 0;
	$trackid = isset($method['trackid']) ? $method['trackid'] : 0;
	$strackid = isset($method['strackid']) ? $method['strackid'] : 0;
	$upgrade = isset($method['upgrade']) ? $method['upgrade'] : 0;
		if($value !=0){	
			$scount=0;
			$chkcount= $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
													FROM itc_license_track 
													WHERE fld_id='".$strackid."'");
									
			if($chkcount>0){				
				$prevusers = $ObjDB->SelectSingleValueInt("SELECT fld_no_of_users 
															FROM itc_license_track 
															WHERE fld_id='".$strackid."'");
				
				$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
														FROM itc_license_track 
														WHERE (fld_remain_users+".$prevusers.")>=".$value." AND ((fld_remain_users+".$prevusers.")-".$value.")>=0 
															and fld_id='".$trackid."'");
							
				$schlremainusers = $ObjDB->SelectSingleValueInt("SELECT fld_remain_users 
																FROM itc_license_track 
																WHERE fld_school_id='".$editid."' AND fld_license_id='".$licenseid."' AND fld_delstatus='0' 
																	AND fld_id='".$strackid."' AND fld_distlictrack_id='".$trackid."'");
				
				if($value<($prevusers-$schlremainusers)){
					$count=0;
				}
				else{
					$scount = 1;
				}
				
			}
			else{				
				$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
														FROM itc_license_track 
														WHERE fld_id='".$trackid."' AND fld_remain_users>='".$value."'");				
			}				
		
		if($count>0){
			echo "true";
		}
		else {
			if($scount==1)
				echo "false";
			else
				echo "false1";
		}
	}
	else{
		echo "false2";
	}
}

/*------ Reset password to the user ---*/	
if($oper=="resets" and $oper != '')
{
		$editid = (isset($method['editid'])) ? $method['editid'] : '';
		$userdetail = $ObjDB->QueryObject("SELECT a.fld_username AS uname, a.fld_fname AS fname, a.fld_lname AS lname, a.fld_email AS email, 
											b.fld_school_admin_id as shluid 
										FROM itc_user_master AS a, `itc_school_master` AS b 
										WHERE a.fld_id= b.fld_school_admin_id AND b.fld_id='".$editid."' AND a.fld_delstatus='0' AND a.fld_activestatus='1'");
		$rowuserdetail = $userdetail->fetch_assoc(); 
		extract($rowuserdetail);
		
		$subj = "your pitsco password";
		$newpassword=generatePassword();
		echo $newpassword;
		$random_hash = md5(date('r', time())); 
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n"; 
		$headers .= "From: Synergy ITC <do_not_reply@pitsco.com>" . "\r\n";	
				
		$html_txt = '<table cellpadding="0" cellspacing="0" border="0" width="98%"><tbody><tr><td style="padding:15px;font-family:Helvetica,Arial,sans-serif;font-size:16px;text-align:left"><img src="http://development.pitsco.info/images/pitsco-logo-n.png"/></td></tr><tr><td style="padding:15px;padding-top:10px;padding-bottom:40px;font-family:Helvetica,Arial,sans-serif;font-size:16px;text-align:left" valign="top"><h1 style="font-family:Helvetica,Arial,sans-serif;color:#222222;font-size:28px;line-height:normal;letter-spacing:-1px">Your Synergy ITC Username And Password</h1><p>Hi '.$fname.',</p><p>Can&lsquo;t remember your password? Don&lsquo;t worry about it &ndash; it happens.</p><p>Your username is: <strong>'.$uname.'</strong></p><p> your password:<strong>'.$newpassword.'</strong><br></p><hr style="margin-top:30px;border:none;border-top:1px solid #ccc"><p style="font-size:13px;line-height:1.3em"><b>Didn&lsquo;t ask to reset your password?</b><br><p>If you didn&lsquo;t ask for your password, it&lsquo;s likely that another user entered your username or email address by mistake while trying to reset their password. If that&lsquo;s the case, you don&lsquo;t need to take any further action and can safely disregard this email.</p></td></tr></tbody></table>';

	$ObjDB->NonQuery("UPDATE itc_user_master 
						SET fld_password = '".fnEncrypt($newpassword,$encryptkey)."', fld_updated_by = '".$uid."', fld_updated_date = '".date("Y-m-d H:i:s")."' 
						WHERE fld_id = '".$shluid."' AND fld_delstatus ='0'");	
	$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => $email,'subject' => $subj, 'plainTex' => '','html' => wordwrap($html_txt),'options' => '','groupID' => '805014','log' => 'True');
	$client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
	$client->call('SendJangoMailTransactional', $param, '', '', false, true);
}	
/*--- View the school license details to the school ---*/	
if($oper=="shllicdet" and $oper != '')
{
	$shlid = (isset($method['shlid'])) ? $method['shlid'] : '';
	$shllic = (isset($method['shllic'])) ? $method['shllic'] : '';
		
	$userdetail = $ObjDB->QueryObject("SELECT a.`fld_id` AS licid, a.`fld_license_name` AS licname , b.fld_no_of_users AS nousers, b.fld_remain_users AS nousers,
										 	b.fld_start_date AS sdate, b.fld_end_date AS edate 
										FROM `itc_license_master` AS a, `itc_license_track` AS b 
										WHERE a.fld_id = b.fld_license_id AND b.fld_school_id='".$shlid."' AND b.fld_user_id='0' AND b.fld_license_id='".$shllic."' 
											AND a.fld_delstatus='0'");
	
		$rowuserdetail = $userdetail->fetch_assoc(); 
		extract($rowuserdetail);
		?>
        <div class='row'>
		<div class="twelve columns formBase">
            <div class='row'>
                <div class='eleven columns centered insideForm'>
                	<div class="row">
                    <div class="twelve columns">
                    <div class="wizardReportData"><?php echo $licname." "."School License Details";?></div>
                </div>
                    </div>
                    <div class="row" >
                        <div class="six columns">
                                <div class="wizardReportDesc">License Name:</div>
                                    <div class="wizardReportData"><?php echo $licname;?></div>
                                <div class="wizardReportDesc">No.of Seats:</div>
                                    <div class="wizardReportData"><?php echo $nousers;?></div>
                                <div class="wizardReportDesc">Remaining Seats:</div>
                                    <div class="wizardReportData"><?php echo $nousers;?></div>
                                <div class="wizardReportDesc">Start Date:</div>
                                    <div class="wizardReportData"><?php echo $sdate;?></div>
                                <div class="wizardReportDesc">End Date:</div>
                                    <div class="wizardReportData"><?php echo $edate;?></div>                    
                        </div>
                    </div>
                    
                    <div class='row rowspacer'>
                        <div class="six columns">
                            <span class="wizardReportDesc">Unit Name:</span>
                                
                                <?php 
                                    $qry_unit = $ObjDB->QueryObject("SELECT a.fld_unit_name AS unitname, a.fld_id AS unitid 
                                                                    FROM itc_unit_master as a, itc_license_cul_mapping as b
                                                                    WHERE a.fld_id=b.fld_unit_id and b.fld_license_id='".$licid."' AND a.fld_delstatus='0' group by a.fld_id");	
                                    $unitids = '';	
                                    if($qry_unit->num_rows > 0) {
                                        while($rowunit = $qry_unit->fetch_assoc()){
                                            extract($rowunit);
                                            if($unitids == '')
                                                $unitids = $unitid;
                                            else 
                                                $unitids .= ",".$unitid;	
                                    ?>		
                                        <div class="wizardReportData"><?php echo $unitname; ?></div>
                                    <?php
                                        }
                                    }
                                ?>	                
                        </div>
                        
                        <div class="six columns">
                            <span class="wizardReportDesc">Lesson Name:</span>
                                <?php 
                                    $qry_lesson = $ObjDB->QueryObject("SELECT a.fld_ipl_name AS lessonname 
                                                                        FROM itc_ipl_master as a, itc_license_cul_mapping as b
                                                                        WHERE a.fld_id=b.fld_lesson_id and b.fld_license_id='".$licid."' AND a.fld_delstatus='0' group by a.fld_id");				
                                    if($qry_lesson->num_rows > 0) {
                                        while($rowlesson = $qry_lesson->fetch_assoc()){
                                            extract($rowlesson);	
                                    ?>		
                                        <div class="wizardReportData"><?php echo $lessonname; ?></div>
                                    <?php
                                        }
                                    }
                                ?>	
                        </div>
                    </div>
                    
                    <div class='row spacer' style="padding-top:20px;">
                        <div class='row'>
                            <p class='btn secondary four columns' style="margin-left:31%">
                               <a onclick="fn_clossplic();">close</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
<?php		
}

//add addtional license for home purchase
if($oper=="addlicense" and $oper != " " )
{
	$count = isset($_POST['count']) ? $_POST['count'] : 0;
	$shllicid = isset($_POST['shllicid']) ? $_POST['shllicid'] : 0;
	$lid = isset($_POST['lid']) ? $_POST['lid'] : 0;
	$trackid = isset($_POST['trackid']) ? $_POST['trackid'] : 0;
	$distid = isset($_POST['distid']) ? $_POST['distid'] : 0;
		 
	if($lid!=0){
		$qry = $ObjDB->QueryObject("SELECT fld_end_date AS startdate, fld_no_of_users AS users 
									FROM itc_license_track 
									WHERE fld_license_id='".$lid."' AND fld_id='".$trackid."'");
		
		$currentdistlictrack = $ObjDB->SelectSingleValueInt("SELECT fld_id 
															FROM itc_license_track 
															WHERE fld_district_id='".$distid."' AND fld_school_id=0 AND fld_delstatus='0' AND fld_license_id='".$lid."' 
																AND fld_start_date<='".$startdate."'");
		
				
		if($qry->num_rows>0){
			$res = $qry->fetch_assoc();
			extract($res);
			$regdate = $startdate;	
			$licensedet = $ObjDB->QueryObject("SELECT fld_duration_type, fld_duration 
												FROM itc_license_master 
												WHERE fld_id='".$lid."'");
			$rowlicense = $licensedet->fetch_object();		
			if($rowlicense->fld_duration_type == 2) {		
				
					$date = strtotime("+".$rowlicense->fld_duration." year", strtotime($regdate));
					$totalduration = date("Y-m-d", $date);
			}
			else {
					$date = strtotime("+".$rowlicense->fld_duration." month", strtotime($regdate));
					$totalduration = date("Y-m-d", $date); 
			}
		}
		$currentdistlictrack = $ObjDB->SelectSingleValueInt("SELECT fld_id 
															FROM itc_license_track 
															WHERE fld_district_id='".$distid."' AND fld_school_id=0 AND fld_delstatus='0' AND fld_license_id='".$lid."' 
																AND fld_end_date>'".$startdate."'");
	}
	?>
    <div class='row' id="lic<?php echo $count;?>">       
        <div class='four columns'>
            <dl class='field row'>
                <dt class='dropdown'> 
                    <div class="selectbox">
                        <input type="hidden" name="ddllic<?php echo $count;?>" class="quantity" id="ddllic<?php echo $count;?>" value="<?php echo $lid; ?>,0,<?php echo $currentdistlictrack;?>" onchange="$(this).valid()" />
                        <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                            <span class="selectbox-option input-medium" data-option=""><?php echo $ObjDB->SelectSingleValue("SELECT fld_license_name 
																															FROM itc_license_master 
																															WHERE fld_id='".$lid."'");?>
                            </span>
                            <b class="caret1"></b>                                                
                        </a> 
                    </div>  
                </dt> 
            </dl>                                 
        </div>
        <div class='two columns'>
            <dl class='field row'>
                <dt class='text'>
                    <input  id="noofusers<?php echo $count;?>" name="noofusers<?php echo $count;?>"  onblur="fn_chkusercountshl(<?php echo $count;?>,0,<?php echo $trackid;?>,<?php echo $currentdistlictrack;?>,'upgrade');" placeholder="Number of users" type='text' value="<?php echo $users; ?>" class="quantity" />
                </dt>                                            
            </dl>
        </div>
        <div class='two columns'>
              <dl class='field row'>
                  <dt class='text'>
                     <input  id="sdate<?php echo $count;?>" name="sdate<?php echo $count;?>"  placeholder='Start Date'type='text' value="<?php if($startdate!='') echo date("Y-m-d", strtotime($startdate));?>" class="quantity" />
                  </dt>                                          
              </dl> 
        </div>
        <div class="two columns">
            <dl class='field row'>
                <dt class='text'>
                <div id="endate<?php echo $count;?>"><input placeholder='End Date' id="edate<?php echo $count;?>" name="edate<?php echo $count;?>" disabled="disabled" value="<?php echo $totalduration;?>"></div>
                </dt>
            </dl>
        </div> 
        <input type="hidden" id="currentlicense<?php echo $count;?>" value="" />
        <input type="hidden" id="usercount<?php echo $count;?>" value="0" />
       	<script>	
			 $( "#sdate<?php echo $count; ?>" ).datepicker({ 
			 		<?php if($qry->num_rows>0){?>
					<?php }?>
					onSelect: function(dateText,inst){							
						$(this).parents().parents().removeClass('error');
						fn_endate(<?php echo $count;?>);
					}
			});
		</script>
     </div>
    <?php 	
}
/*--------- Track yhe end date of the license ---------------*/
if($oper=="endtade" and $oper != " " )
{
	$trackid = isset($method['trackid']) ? $method['trackid'] : 0;
	echo date("m/d/Y",strtotime($ObjDB->SelectSingleValue("SELECT fld_start_date FROM itc_license_track WHERE fld_id='".$trackid."'")))."~";
	echo date("m/d/Y",strtotime($ObjDB->SelectSingleValue("SELECT fld_end_date FROM itc_license_track WHERE fld_id='".$trackid."'")));
}
/*--------- Delete the license of the school ---------------*/
if($oper=="deletelicense" and $oper != " " )
{
	$trackid = isset($method['trackid']) ? $method['trackid'] : 0;
	$sid = isset($method['sid']) ? $method['sid'] : 0;
	$ObjDB->NonQuery("UPDATE itc_license_track 
					SET fld_delstatus='1',fld_deleted_by='".$uid."' 
					WHERE fld_id='".$trackid."'");
	
	$chk = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_license_track 
										WHERE fld_license_id IN(SELECT fld_license_id 
										FROM itc_license_track WHERE fld_id='".$trackid."') AND fld_school_id='".$sid."' AND fld_user_id=0 AND fld_delstatus='0' 
										ORDER BY fld_id DESC LIMIT 0,1");
	if($chk!=0){
		$ObjDB->NonQuery("UPDATE itc_license_track 
						SET fld_upgrade='1',fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
						WHERE fld_id='".$chk."'");
	}
}
/*---------Calculate the remaing use of the school ---------------*/
if($oper=="remainusers" and $oper != " " )
{
	$trackid = isset($method['trackid']) ? $method['trackid'] : 0;
	echo "Available student seats: ".$ObjDB->SelectSingleValueInt("SELECT fld_remain_users FROM itc_license_track WHERE fld_id='".$trackid."'");
}

/*----Resend the mail to user-----*/ 
if($oper=="saveschoolmail" and $oper != '')
{
	$userid = (isset($method['mailid'])) ? $method['mailid'] : '';
	$userdetail = $ObjDB->QueryObject("SELECT a.fld_username AS uname, a.fld_fname AS fname,a.fld_lname AS lname,
											a.fld_email AS email,b.fld_school_name AS shlname,b.fld_school_admin_id AS schooluidid 
										FROM itc_user_master AS a
										LEFT JOIN itc_school_master AS b ON b.fld_school_admin_id=a.fld_id
										WHERE b.fld_id='".$userid."' AND b.fld_delstatus='0' AND a.fld_delstatus='0' AND a.fld_activestatus='0'");
	  while($rowshl = $userdetail->fetch_assoc())
	  { 
	 	extract ($rowshl);
		$html_txt = '';
		$headers = '';
		$mailtitle = $shlname;
		
		$subj = "You're invited to join our learning management system";
		$random_hash = md5(date('r', time())); 
						
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=utf-8" . "\r\n"; 
		$headers .= "From: Synergy ITC <do_not_reply@pitsco.com>" . "\r\n";	 
	
		$html_txt = '<table cellpadding="0" cellspacing="0" border="0" width="98%"><tbody><tr><td style="padding:15px;padding-top:10px;padding-bottom:40px;font-family:Helvetica,Arial,sans-serif;font-size:16px;
color:#222222;text-align:left" valign="top">
<h1 style="font-family:Helvetica,Arial,sans-serif;color:#222222;font-size:28px;line-height:normal;letter-spacing:-1px">
Invitation to create a Synergy ITC account</h1><p>Hello <font style="font-style: italic;">'.$shlname.':</font></p><p>Thank you for choosing Pitsco Education and Synergy ITC. In order to activate your account, click on the link below to choose a user name and password.</p><p><strong>Click this link to get started:</strong><br /><a href="'.$domainame.'register.php?e='.md5($schooluidid).'">'.$domainame.'register.php?e='.md5($schooluidid).'</a></p>
    <p><b>Access your <font style="font-style: italic;">Synergy ITC</font> account: &nbsp;&nbsp; By clicking on this link and logging into ITC, you agree the following agreement.</b><br><a href="'.__HOSTADDR__.'" target="_blank">'.__HOSTADDR__.'</a></p>
    <p align="center"><br><br>
          <b>PITSCO, INC. SYNERGY ITC LICENSE AGREEMENT</b><br>
39527<br><br>



<p style="margin-left:150px;width:70%;text-align:left;">YOU SHOULD CAREFULLY READ THE FOLLOWING TERMS AND CONDITIONS BEFORE ACCESSING THE SOFTWARE. ACCESSING THE SOFTWARE INDICATES YOUR ACCEPTANCE OF THESE TERMS AND CONDITIONS.<br><br> 

Pitsco, Inc. provides these programs and licenses for use directly or through authorized dealers. You assume responsibility for the selection of the programs to achieve your intended results, and for the installation, use, and results obtained from the programs.<br><br>

<b>LICENSE</b><br>
Purchase or annual license of Synergy ITC entitles you to:<br><br>

a.	Access of Synergy ITC from the web.<br>
b.	Access of Synergy ITC by classroom or location equal to the number of Synergy ITC licenses purchased.<br><br>

YOU MAY NOT USE, COPY , MODIFY, OR TRANSFER THE PROGRAMS, OR ANY COPY, MODIFICATION, OR MERGED PORTION, IN WHOLE OR IN PART, EXCEPT AS EXPRESSLY PROVIDED FOR IN THIS LICENSE. IF YOU OTHERWISE USE OR TRANSFER POSSESSION OR ANY COPY, MODIFICATION, OR MERGED PORTION OF THE PROGRAMS TO ANOTHER PARTY, YOUR LICENSE IS AUTOMATICALLY TERMINATED. <br><br>

<b>TERM</b><br> 
The license is effective until terminated. You may terminate it at any time by destroying the programs together with all copies, modification, and merged portions in any form. It will also terminate upon conditions set forth elsewhere in this Agreement or if you fail to comply with any term or condition of this Agreement. You agree upon such termination to destroy the programs together with all copies, modifications, and merged portions in any form. If you purchased an annual license, the term shall expire in one year. <br><br>

LIMITED WARRANTY<br>
THE PROGRAMS ARE PROVIDED �AS IS� WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESS OR IMPLIED, INCLUDING, BUT NOT LIMITED TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. THE ENTIRE RISK AS TO THE QUALITY AND PERFORMANCE OF THE PROGRAMS IS WITH YOU. SHOULD THE PROGRAMS PROVE DEFECTIVE, YOU (AND NOT PITSCO EDUCATION) ASSUME THE ENTIRE COST OF ALL NECESSARY SERVICING, REPAIR, OR CORRECTION.<br><br>

SOME STATES AND COUNTRIES DO NOT ALLOW THE EXCLUSION OF IMPLIED WARRANTIES, SO THE ABOVE EXCLUSION MAY NOT APPLY TO YOU. THIS WARRANTY GIVES YOU SPECIFIC LEGAL RIGHTS AND YOU MAY ALSO HAVE OTHER RIGHTS WHICH VARY BY STATE OR COUNTRY.<br><br>

Pitsco, Inc. does not warrant that the functions contained in the programs will meet your requirements or that the operation of the programs will be uninterrupted or error-free.<br><br>

However, Pitsco, Inc. warrants the software on which the programs are furnished, to be free from defects in materials and workmanship under normal use for a period of ninety (90) days from the date of delivery to you as evidenced by a copy of your receipt. <br><br>
 

<b>LIMITATIONS OF REMEDIES</b><br>
Pitsco, Inc.�s entire liability and your exclusive remedy shall be:<br><br>

1.	The replacement of any software not meeting Pitsco, Inc.�s �Limited Warranty� and which is returned to Pitsco, Inc. <br><br>

IN NO EVENT WILL PITSCO, INC. BE LIABLE TO YOU FOR ANY DAMAGES, INCLUDING ANY LOST PROFITS, LOST SAVINGS, OR OTHER INCIDENTAL OR CONSEQUENTIAL DAMAGES ARISING OUT OF THE USE OR INABILITY TO USE SUCH PROGRAMS EVEN IF PITSCO, INC. HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES, OR FOR ANY CLAIM BY ANY OTHER PARTY.<br><br>

SOME STATES DO NOT ALLOW THE LIMITATION OR EXCLUSION OF LIABILITY FOR INCIDENTAL OR CONSEQUENTIAL DAMAGES SO THE ABOVE LIMITATION OR EXCLUSION MAY NOT APPLY TO YOU.<br><br>

<b>GENERAL</b><br>
You may not sublicense, assign, or transfer the license or the programs except as expressly provided in this Agreement. Any attempt otherwise to sublicense, assign, or transfer any of the rights, duties, or obligations hereunder is void.<br><br>

This Agreement will be governed by the laws of the country in which you bought the license.<br><br>

YOU ACKNOWLEDGE THAT YOU HAVE READ THIS AGREEMENT, UNDERSTAND IT AND AGREE TO BE BOUND BY ITS TERMS AND CONDITIONS. YOU FURTHER AGREE THAT IT IS THE COMPLETE AND EXCLUSIVE STATEMENT OF THE AGREEMENT BETWEEN US WHICH SUPERSEDES ANY PROPOSAL OR PRIOR AGREEMENT, ORAL OR WRITTEN, AND ANY OTHER COMMUNICATIONS BETWEEN US RELATING TO THE SUBJECT MATTER OF THIS AGREEMENT.<br><br>

    </p>
<p>If you are in need of technical support, please don&lsquo;t hesitate to contact our industry-leading customer support line at 800-774-4552.</p><p>Thank you,</p><p><strong>Pitsco Education</strong><br>800-774-4552<br>www.pitsco.com</p><p align="center"  style="font-style: italic;">Thank you for being a loyal Pitsco customer! <br>We appreciate all you do for students!</p>
</td></tr></tbody></table>';
		$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => $email,'subject' => $subj, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
		$client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
		$client->call('SendJangoMailTransactional', $param, '', '', false, true);
	}
}

	@include("footer.php");