<?php
@include("sessioncheck.php");

$oper = isset($method['oper']) ? $method['oper'] : '';

/*--- Check School Name ---*/
if($oper=="checkshlname" and $oper != " " )
{
	$sid = isset($method['sid']) ? $method['sid'] : '0';
	$shlname = isset($method['shlname']) ? $ObjDB->EscapeStrAll($method['shlname']) : '';
	
	$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
										FROM itc_school_master 
										WHERE LCASE(REPLACE(fld_school_name,' ',''))='".str_replace(' ','',$shlname)."' AND fld_delstatus='0' AND fld_id<>'".$sid."'");
	if($count == 0){ echo "true"; }	else { echo "false"; }
}
/*--- Select the city based the satate ---*/
if($oper == "changecity" and $oper != ""){
	$statevalue =  isset($method['statevalue']) ? $method['statevalue'] : '';
	?>
		<div class="selectbox">
		  <input type="hidden" name="ddlcity" id="ddlcity" value="" onchange="$('#ddlcity').valid();fn_loaddistrict(this.value);fn_changezip(this.value);" >
		  <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
			<span class="selectbox-option input-medium" data-option=""> Select city</span>
			<b class="caret1"></b>
		  </a>
		  <div class="selectbox-options">
			<input type="text" class="selectbox-filter" placeholder="Search city" >
			<ul role="options">
				<?php 
					$cityqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_cityname) AS cityname 
													FROM itc_state_city WHERE fld_statevalue='".$statevalue."' AND fld_delstatus=0 
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
													WHERE fld_cityname='".$cityvalue."' AND fld_statevalue='".$statevalue."' AND fld_delstatus=0 
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
/*--- Select the city based on satate ---*/			
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
/*--- Select the zipcode based on satate and city ---*/		
if($oper == "changezip1" and $oper != ""){
		$cityvalue =  isset($method['cityvalue']) ? $method['cityvalue'] : '';
		$statevalue =  isset($method['statevalue']) ? $method['statevalue'] : '';
		?>
        <!--<dl class='field row'>   -->   
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
                        $zipqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_zipcode) AS zipcode 
													FROM itc_state_city 
													WHERE fld_cityname='".$cityvalue."' AND fld_statevalue='".$statevalue."'  AND fld_delstatus=0 
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
                           <li><a tabindex="1" href="#" data-option="<?php echo $fld_id;?>" onclick="addlicshl(<?php echo $fld_id;?>)" title="<?php echo $districtname;?>"><?php echo $shortdname;?></a></li>
                    <?php 
                    }?>       
            </ul>
          </div>
        </div>
 <?php
}
/*--- Save the choolpurchase Details ---*/	
if($oper == "saveschoolpurchase" and $oper != ""){
	
	try /**Here starts with saving the details uster master and district master tables**/
		{
	
		$date=date("Y-m-d H:i:s");
		$editid =  isset($method['id']) ? $method['id'] : '0';
		$shlname =  isset($method['shlname']) ? $method['shlname'] : '';
		$address =  isset($method['address']) ? $method['address'] : '';
		$state =  isset($method['state']) ? $method['state'] : '';
		$city =  isset($method['city']) ? $method['city'] : '';
		$city =	ucwords(strtolower($city));
		$zipcode =  isset($method['zipcode']) ? $method['zipcode'] : '';
				
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
		$tags = isset($method['tags']) ? $method['tags'] : '';	
		
		/*lecense details getting----------*/
		$licensecount = isset($method['licensecount']) ? $method['licensecount'] : '';
		$ddllicense = isset($method['ddllicense']) ? $method['ddllicense'] : '';
		$numusers = isset($method['numusers']) ? $method['numusers'] : '';
		$startdate = isset($method['startdate']) ? $method['startdate'] : '';
		$enddate = isset($method['enddate']) ? $method['enddate'] : '';
		$graceipl = isset($method['graceipl']) ? $method['graceipl'] : '';
		$gracemod = isset($method['gracemod']) ? $method['gracemod'] : '';
		$renewal = isset($method['renewal']) ? $method['renewal'] : '';
		$rcount = isset($method['rcount']) ? $method['rcount'] : '';
		$ddllicense = explode('~',$ddllicense);
		$numusers = explode('~',$numusers);
		$startdate = explode('~',$startdate);
		$enddate = explode('~',$enddate);
		$graceipl = explode('~',$graceipl);
		$gracemod = explode('~',$gracemod);
		$renewal = explode('~',$renewal);
		$rcount = explode('~',$rcount);
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
			if($editid==0){
			
				$userid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_user_master (fld_uuid, fld_email, fld_fname, fld_lname, fld_profile_id, fld_role_id, 
									fld_profile_pic, fld_created_by, fld_created_date) 
								VALUES ('".$uguid."','".$email."','".$fname."','".$lname."','7','5','".$photo."','".$uid."','".$date."')");
								
				$shlid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_school_master (fld_school_name, fld_school_logo, fld_school_admin_id, 
														fld_street_address, fld_state, fld_city, fld_zipcode,  fld_created_date,fld_created_by) 
													VALUES ('".$shlname."','".$logo."','".$userid."','".$address."','".$state."','".$city."','".$zipcode."',
														'".$date."','".$uid."')");
				/*--Tags insert-----*/
				fn_taginsert($tags,17,$shlid,$uid);		
				
				$ObjDB->NonQuery("UPDATE itc_user_master SET fld_school_id = '".$shlid."',fld_updated_by='".$uid."' 
								WHERE fld_id='".$userid."' AND fld_delstatus='0'");
				$arr = array($officeno,$faxno,$mobileno,$homeno,$address1,$state1,$city1,$zipcode1);
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
				
				/*-------add license track for district----------*/
				for($i=0;$i<sizeof($ddllicense)-1;$i++){			
					$ObjDB->NonQuery("INSERT INTO itc_license_track (fld_license_id,fld_school_id, fld_no_of_users, fld_remain_users, fld_start_date, 
										fld_end_date, fld_ipl_count, fld_mod_count, fld_auto_renewal, fld_created_by,fld_created_date,fld_renewal_count) 
									VALUES('".$ddllicense[$i]."','".$shlid."','".$numusers[$i]."','".$numusers[$i]."',
										'".date('Y-m-d',strtotime($startdate[$i]))."', '".date('Y-m-d',strtotime($enddate[$i]))."',
										'".$graceipl[$i]."','".$gracemod[$i]."','".$renewal[$i]."','".$uid."','".$date."',
										'".$rcount[$i]."')");
				}
				/*-------Mail----------*/
				$html_txt = '';
				$headers = '';
				$mailtitle = "School Purchase";
				
				$subj = "You're invited to join our learning management system";
				$random_hash = md5(date('r', time())); 
								
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=utf-8" . "\r\n"; 
				$headers .= "From: Synergy2 <info@pitsco.info>" . "\r\n";  
			
				
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
			}
			else{
				/*---tags------*/
				fn_tagupdate($tags,17,$editid,$uid);
				
				$ObjDB->NonQuery("UPDATE itc_school_master 
								SET fld_school_name = '".$shlname."', fld_school_logo = '".$logo."', fld_street_address = '".$address."', fld_state = '".$state."', 
									fld_city = '".$city."', fld_zipcode = '".$zipcode."', fld_updated_by = '".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."' 
								WHERE fld_id = '".$editid."'");
					
				$shluserid = $ObjDB->SelectSingleValueInt("SELECT fld_school_admin_id 
														FROM itc_school_master 
														WHERE fld_id ='".$editid."' AND fld_district_id=0 AND fld_delstatus ='0'");
				
				$ObjDB->NonQuery("UPDATE itc_user_master SET fld_email = '".$email."', fld_fname = '".$fname."', fld_lname = '".$lname."', 
									fld_profile_pic = '".$photo."', fld_updated_by= '".$uid."', fld_updated_date = '".$date."' 
								WHERE fld_id = '".$shluserid."' AND fld_delstatus ='0'");
				
				$arr = array($officeno,$faxno,$mobileno,$homeno,$address1,$state1,$city1,$zipcode1);
				$j=3;
				for($i=0;$i<sizeof($arr);$i++)
				{
					if($arr[$i]!='')
					{
						$cnt=$ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_user_add_info 
														WHERE fld_user_id = '".$shluserid."' AND fld_field_id = '".$j."'");
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
				/*-------add license track for district----------*/
				for($i=0;$i<sizeof($ddllicense)-1;$i++){			
					
					$lid = explode(',',$ddllicense[$i]);//(licenseid,licensetrack fld_id)		
					
					$chk = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
														FROM itc_license_track 
														WHERE fld_school_id='".$editid."' AND fld_district_id=0 AND fld_license_id='".$lid[0]."' AND fld_id='".$lid[1]."'");
					if($chk==0){
						$prelid = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
																FROM itc_license_track 
																WHERE fld_user_id='0' AND fld_school_id='".$editid."' AND fld_license_id='".$lid[0]."' 
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
										SET fld_upgrade='0',fld_delstatus='0',fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."' 
										WHERE fld_school_id='".$editid."' AND fld_district_id='0' AND fld_license_id='".$lid[0]."'");
										
						$ObjDB->NonQuery("INSERT INTO itc_license_track (fld_license_id,fld_school_id, fld_no_of_users, fld_remain_users, 
											fld_start_date, fld_end_date,fld_created_by,fld_created_date,fld_ipl_count,fld_mod_count,
											fld_auto_renewal, fld_renewal_count) 
										VALUES('".$lid[0]."','".$editid."','".$numusers[$i]."','".$numusers[$i]."',
											'".date('Y-m-d',strtotime($startdate[$i]))."', '".date('Y-m-d',strtotime($enddate[$i]))."',
											'".$uid."','".$date."','".$graceipl[$i]."','".$gracemod[$i]."','".$renewal[$i]."','".$rcount[$i]."')");
						
						//send notifications to users
						$licensename = $ObjDB->SelectSingleValue("SELECT fld_license_name 
																FROM itc_license_master 
																WHERE fld_id='".$lid[0]."' AND fld_delstatus='0'");
						$html_txt = '';
						$headers = '';
						$up = "'";
						
						$qry = $ObjDB->QueryObject("SELECT fld_fname, fld_lname, fld_email, fld_username, fld_id, fld_profile_id 
													FROM itc_user_master 
													WHERE fld_district_id='0' AND fld_school_id='".$editid."' AND fld_user_id='0' AND 
														fld_profile_id<>10 AND fld_delstatus='0'");
						
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
									Automatic Renew: '.$auto.'<br />
									Available seats: '.$numusers[$i].'<br /><br />
									</td></tr>'.fn_getcontent($lid[0]).'</table>';						
									$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => $fld_email,'subject' => $subj, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
									$client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
									$client->call('SendJangoMailTransactional', $param, '', '', false, true);									
								}
							}
						}		
						//for pitsco admin
						$html_txt = '';
						$headers = '';		
						$subj = $licensename."-".$shlname."(School purchase) - ".$subject;
						$random_hash = md5(date('r', time())); 
										
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=utf-8" . "\r\n"; 		
						$headers .= "From: Synergy ITC <do_not_reply@pitsco.com>" . "\r\n";		
						$html_txt = '<table width="98%" cellpadding="10" cellspacing="0">'.$content.'
									<tr><td valign="top" align="left">Lease: '.$licensename.'<br />
									Start date: '.date("m/d/Y",strtotime($startdate[$i])).'<br />
									End date: '.date("m/d/Y",strtotime($enddate[$i])).'<br />
									Automatic Renew: '.$auto.'<br />
									Available seats: '.$numusers[$i].'<br /><br />
									</td></tr>'.fn_getcontent($lid[0]).'</table>';
						$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => 'systems_support@pitsco.com','subject' => $subj, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
						$client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
						$client->call('SendJangoMailTransactional', $param, '', '', false, true);
						//end notification
					}
					else {
						$getpreusers = $ObjDB->QueryObject("SELECT fld_no_of_users AS prevtotusers , fld_remain_users AS prevremainusers
																		FROM itc_license_track 
																		WHERE fld_id='".$lid[1]."'");
						extract($getpreusers->fetch_assoc());
						
						$totusers = $numusers[$i] - $prevtotusers;				
						$ObjDB->NonQuery("UPDATE itc_license_track 
										SET fld_delstatus='0',  fld_no_of_users='".($prevtotusers+$totusers)."', 
											fld_remain_users='".($prevremainusers+$totusers)."', fld_start_date='".date('Y-m-d',strtotime($startdate[$i]))."',
											fld_end_date='".date('Y-m-d',strtotime($enddate[$i]))."', fld_updated_by='".$uid."', 
											fld_updated_date='".$date."', fld_ipl_count='".$graceipl[$i]."',
											fld_mod_count='".$gracemod[$i]."', fld_auto_renewal='".$renewal[$i]."', fld_renewal_count='".$rcount[$i]."' 
										WHERE fld_district_id='0' AND fld_school_id='".$editid."' AND fld_license_id='".$lid[0]."' AND fld_id='".$lid[1]."'");		
						
					}
					
				}
			}
			echo "success";
		} // End validate if
		else{
			echo "fail";
		}
	} // Ends try
	catch(Exception $e)
	{
		 echo "fail";
	}
}

/*--- Delete schoolpurchase and related users and license Details ---*/
if($oper == "deletschoolpurchase" and $oper != ""){
	
	$editid =  isset($method['editid']) ? $method['editid'] : '';
	$validate_editid=true;
	if($editid!=0)$validate_editid=validate_datatype($editid,'int');
	if($validate_editid){	
		$ObjDB->NonQuery("UPDATE itc_school_master 
						SET fld_delstatus = '1', fld_deleted_by = '".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."' 
						WHERE fld_id = '".$editid."'");
						
		$ObjDB->NonQuery("UPDATE itc_user_master 
						SET fld_delstatus = '1', fld_deleted_by = '".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."' 
						WHERE fld_school_id = '".$editid."'");
						
		$ObjDB->NonQuery("UPDATE itc_license_track 
							SET fld_delstatus='1', fld_deleted_by = '".$uid."', fld_deleted_date = '".date("Y-m-d H:i:s")."' 
							WHERE fld_school_id='".$editid."'");
		echo "success";
	}
	else{
		echo "fail";
	}
}
/*---List license dropdownbox to the schoolpurchase ---*/
if($oper == "addlicensesp" and $oper != ""){
	$count =  isset($method['count']) ? $method['count'] : '';
	$licenseid =  isset($method['licenseid']) ? $method['licenseid'] : '';
	$licenseid = explode('~',$licenseid);	
	$existlicense = sizeof(array_unique($licenseid));
	?>	
    <div class="row" id="lic<?php echo $count;?>">
    	<div class="row">
            <div class="four columns">
                Licenses<span class="fldreq">*</span> 
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
                                $licqry = $ObjDB->QueryObject("SELECT fld_id,fld_license_name AS licensename, fn_shortname(fld_license_name,2) AS shortname
															FROM itc_license_master 
															WHERE fld_delstatus='0' AND fld_license_type='1' 
															ORDER BY licensename ASC");
                                $i=1;
                                while($row = $licqry->fetch_assoc()){
									extract($row);?>
                                        <li><a tabindex="17" href="#" data-option="<?php echo $fld_id;?>" onclick="fn_licenseclicksp(<?php echo $fld_id;?>,<?php echo $count;?>)" id="option<?php echo $fld_id;?>" title="<?php echo $licensename;?>" class="tooltip"><?php echo $shortname;?></a></li>
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
                        <input  id="noofusers<?php echo $count;?>" tabindex="18" name="noofusers<?php echo $count;?>" placeholder='users' type='text' readonly />
                    </dt>
                 </dl>
            </div>
            <div class="two columns">
                Start date<span class="fldreq">*</span> 
                 <dl class='field row'>
                    <dt class="text">
                        <input  id="sdate<?php echo $count;?>" tabindex="19" name="sdate<?php echo $count;?>" placeholder='Start Date'type='text' readonly />
                    </dt>
                 </dl>
            </div>
            <div class="two columns">
                End date<span class="fldreq">*</span> 
                <dl class='field row'>
                    <dt class='text'>
                    	<input placeholder='End Date' id="edate<?php echo $count;?>" name="edate<?php echo $count;?>" readonly />
                    </dt>
                </dl>
            </div>
            <div id="grace<?php echo $count; ?>"> </div>  
            <div class='one columns' id="remupgrade_<?php echo $count;?>" style=" padding-left:8px;">
            	remove
                <p class='btn twelve columns'>
                    <a onclick="fn_removesplicense(<?php echo $count;?>,0,0)" id="rmove"> - </a>
                </p>     
            </div>
         </div>
         <div class="row">
         	<div class='two columns'>
                <ul class="field row" onclick="fn_renewalcount(<?php echo $count; ?>)">
                    <li>
                        <label class="checkbox" for="checkbox<?php echo $count; ?>">
                        <input type="checkbox" id="checkbox<?php echo $count; ?>" style="display:none;" value="0" />
                        <span></span> Auto renewal
                        </label>
                    </li>
                </ul>
            </div>
            <div class='one columns' title="No of times for auto renewal" id="rcountdiv_<?php echo $count;?>" style="display:none;">
                <dl class='field row'>
                    <dt class='text'>
                       <input type="text" id="renewalcount_<?php echo $count; ?>" maxlength="2" value="" />
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
    <input type="hidden" id="currentlicense<?php echo $count;?>" value="" />~<?php if($existlicense==($i-1))echo "1"; else echo "0";?>        
<?php
}
/*---Reset password to the user ----*/	
if($oper=="resetsp" and $oper != '')
{
		$editid = (isset($method['editid'])) ? $method['editid'] : '';
		$userdetail = $ObjDB->QueryObject("SELECT a.fld_username AS uname, a.fld_fname AS fname, a.fld_lname AS lname, a.fld_email AS email, 
											b.fld_school_admin_id as shluid 
										FROM itc_user_master AS a, `itc_school_master` AS b 
										WHERE a.fld_id= b.fld_school_admin_id AND b.fld_id='".$editid."' AND a.fld_delstatus='0' AND a.fld_activestatus='1'");
		$rowuserdetail = $userdetail->fetch_assoc(); 
		extract($rowuserdetail);
		
		$subj = "Your pitsco password";
		$newpassword=generatePassword();
		echo $newpassword;
		
		$random_hash = md5(date('r', time())); 
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n"; 
		$headers .= "From: Synergy ITC <do_not_reply@pitsco.com>" . "\r\n"; 
				
		$html_txt = '<table cellpadding="0" cellspacing="0" border="0" width="98%"><tbody><tr><td>Your Synergy ITC Username And Password</td></tr><tr><td>&nbsp;</td></tr><tr><td>Hi '.$fname.',</td></tr><tr><td>Can&prime;t remember your password? Don&prime;t worry about it – it happens.</td></tr><tr><td>&nbsp;</td></tr><tr><td>Your username is: '.$uname.'</td></tr><tr><td>your password: '.$newpassword.'</td></tr><tr><td>&nbsp;</td></tr><tr><td>Didn&prime;t ask to reset your password?</td></tr><tr><td>If you didn&prime;t ask for your password, it&prime;s likely that another user entered your username or email address by mistake while trying to reset their password. If that&prime;s the case, you don&prime;t need to take any further action and can safely disregard this email.</td></tr></tbody></table>';
		
	$ObjDB->NonQuery("UPDATE itc_user_master 
						SET fld_password = '".fnEncrypt($newpassword,$encryptkey)."', fld_updated_by = '".$uid."', 
							fld_updated_date = '".date("Y-m-d H:i:s")."' 
						WHERE fld_id = '".$shluid."' AND fld_delstatus ='0'");	
						
	$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => $email,'subject' => $subj, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
	$client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
	$client->call('SendJangoMailTransactional', $param, '', '', false, true);
}	
/*---Listout the school license details ----*/
if($oper=="splicdet" and $oper != '')
{
	$spid = (isset($method['spid'])) ? $method['spid'] : '';
	$splic = (isset($method['splic'])) ? $method['splic'] : '';
		
	$userdetail = $ObjDB->QueryObject("SELECT a.`fld_id` AS licid, a.`fld_license_name` AS licname , b.fld_no_of_users AS nousers, 
										b.fld_remain_users AS nousers, b.fld_start_date AS sdate, b.fld_end_date AS edate 
									FROM `itc_license_master` AS a, `itc_license_track` AS b 
									WHERE a.fld_id = b.fld_license_id AND b.fld_district_id='0' AND b.fld_school_id='".$spid."' AND b.fld_user_id='0' 
										AND b.fld_license_id='".$splic."' AND a.fld_delstatus='0'");
	
		$rowuserdetail = $userdetail->fetch_assoc(); 
		extract($rowuserdetail);
		?>
        <div class='row'>
		<div class="twelve columns formBase">
            <div class='row'>
                <div class='eleven columns centered insideForm'>
                	<div class="row">
                    <div class="twelve columns">
                    <div class="wizardReportData"><?php echo $licname." "."District License Details";?></div>
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
/*---Upgrade the license in school ----*/
if($oper=="upgradelicense" and $oper != " " )
{
	$count = isset($_POST['count']) ? $_POST['count'] : 0;	
	$lid = isset($_POST['lid']) ? $_POST['lid'] : 0;
	$trackid = isset($_POST['trackid']) ? $_POST['trackid'] : 0;
		 
	if($lid!=0){
		$qry = $ObjDB->QueryObject("SELECT fld_end_date AS startdate, fld_no_of_users AS users, fld_ipl_count, fld_mod_count 
									FROM itc_license_track 
									WHERE fld_id='".$trackid."'");	
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
					$totalduration = date("m/d/Y", $date);
			}
			else {
					$date = strtotime("+".$rowlicense->fld_duration." month", strtotime($regdate));
					$totalduration = date("m/d/Y", $date); 
			}
		}
	}
	?>
    <div class='row' id="lic<?php echo $count;?>"> 
    	<div class='row'>	      
            <div class='four columns'>
                Licenses<span class="fldreq">*</span> 
                <dl class='field row'>
                    <dt class='dropdown'> 
                        <div class="selectbox">
                            <input type="hidden" name="ddllic<?php echo $count;?>" id="ddllic<?php echo $count;?>" value="<?php echo $lid; ?>" onchange="$(this).valid()" />
                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                <span class="selectbox-option input-medium" data-option=""><?php echo $ObjDB->SelectSingleValue("select fld_license_name from itc_license_master where fld_id='".$lid."'");?></span>
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
                        <input  id="noofusers<?php echo $count;?>" name="noofusers<?php echo $count;?>"  placeholder="users" type='text' value="<?php echo $users; ?>" /> <!--onblur="fn_chkusercount(<?php //echo $count;?>,0,0);"-->
                    </dt>                                            
                </dl>
            </div>   
            <div class='two columns'>
              Start date<span class="fldreq">*</span> 
              <dl class='field row'>
                  <dt class='text'>
                     <input  id="sdate<?php echo $count;?>" name="sdate<?php echo $count;?>"  placeholder='Start Date'type='text' value="<?php if($startdate!='') echo date("m/d/Y", strtotime("+1 day",strtotime($startdate)));?>" readonly />
                  </dt>                                          
              </dl> 
            </div> 
            <div class="two columns">
                End date<span class="fldreq">*</span> 
                <dl class='field row'>
                    <dt class='text'>
                    <div id="endate<?php echo $count;?>"><input placeholder='End Date' id="edate<?php echo $count;?>" name="edate<?php echo $count;?>" readonly value="<?php echo date("m/d/Y", strtotime("+1 day",strtotime($totalduration)));?>"></div>
                    </dt>
                </dl>
            </div>
            <div id="grace<?php echo $count; ?>"> 
            <?php 
                $iplcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
														FROM itc_license_cul_mapping 
														WHERE fld_license_id='".$lid."' AND fld_active='1'");
														
                $modcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
														FROM itc_license_mod_mapping 
														WHERE fld_license_id='".$lid."' AND fld_active='1'");
                if($iplcount>0){
                ?>
                
                <div class="one columns" style="padding-left:15px;" title="Significant Content Experience">
                    IPl<span class="fldreq">*</span>                        	
                    <dl class='field row'>
                        <dt class='text'>
                            <input  id="iplcount<?php echo $count; ?>" name="iplcount<?php echo $count; ?>" placeholder='IPL' tabindex="21" type='text' value="<?php echo $fld_ipl_count; ?>" maxlength="2" />
                        </dt>
                    </dl>
                </div>  
                <?php } if($modcount>0){?> 
                <div class="one columns" <?php if($iplcount==0){ ?>style="padding-left:15px;" <?php }?> title="Significant Content Experience">
                    Modules<span class="fldreq">*</span>                           	
                    <dl class='field row'>
                        <dt class='text'>
                            <input  id="modcount<?php echo $count; ?>" name="modcount<?php echo $count; ?>" placeholder='module' tabindex="22" type='text' value="<?php echo $fld_mod_count; ?>" maxlength="2" />
                        </dt>
                    </dl>
                </div> 
                <?php 
                }?>
                <script>
                    $("#iplcount<?php echo $count; ?>,#modcount<?php echo $count; ?>").keypress(function (e) {
                        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                            return false;
                        }
                    });		
                </script>   
            </div>
            <div class='one columns' style=" padding-left:8px;">
                remove
                <p class='btn twelve columns'>
                    <a onclick="fn_removesplicense(<?php echo $count;?>,0,<?php echo $trackid; ?>)" id="rmove"> - </a>
                </p>     
            </div>
            <input type="hidden" id="currentlicense<?php echo $count;?>" value="<?php echo $lid; ?>" />        
            <script>	
                 $( "#sdate<?php echo $count; ?>" ).datepicker({ 
                        <?php if($qry->num_rows>0){?>
                        minDate: new Date(<?php echo date("Y,m-1,d+1", strtotime($startdate));?>),
                        <?php }?>
                        onSelect: function(dateText,inst){							
                            $(this).parents().parents().removeClass('error');
                            fn_endate(<?php echo $count;?>);
                        }
                });
            </script>
            <script>
                $("#noofusers<?php echo $count; ?>").keypress(function (e) {
                    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                        return false;
                    }
                });		
            </script>
         </div>
         <div class="row">
         	<div class='two columns'>
                <ul class="field row" onclick="fn_renewalcount(<?php echo $count; ?>)">
                    <li>
                        <label class="checkbox" for="checkbox<?php echo $count; ?>">
                        <input type="checkbox" id="checkbox<?php echo $count; ?>" style="display:none;" value="0" />
                        <span></span> Auto renewal
                        </label>
                    </li>
                </ul>
            </div>
            <div class='one columns' title="No of times for auto renewal" id="rcountdiv_<?php echo $count;?>" style="display:none;">
                <dl class='field row'>
                    <dt class='text'>
                       <input type="text" id="renewalcount_<?php echo $count; ?>" maxlength="2" value="" />
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
    <?php 	
}

/*---Check user cout for particular license ----*/
if($oper=="chkusercount" and $oper != " " )
{	
	$shpid = isset($_POST['shpid']) ? $_POST['shpid'] : 0;
	$value = isset($method['value']) ? $method['value'] : 0;
	$trackid = isset($_POST['trackid']) ? $_POST['trackid'] : 0;
	$licenseid = isset($method['licenseid']) ? $method['licenseid'] : 0;
	
		$homeuserdet = $ObjDB->QueryObject("SELECT fld_no_of_users AS pvtotlusers, fld_remain_users AS pvremusers 
										FROM itc_license_track 
										WHERE fld_id='".$trackid."'");
		$res= $homeuserdet->fetch_assoc();
		extract($res);
		
		$difference = $pvtotlusers-$pvremusers;
		
		if($difference<=$value){
			$count =1;
		}
		if($count>0){
			echo "true";
		}
		else {
			echo "false";
		}
	
}
/*--- Loade the enddate base on the startdate and monthly/yearly ---*/
if($oper=="endtade" and $oper != " " )
{
	$sdate = isset($method['sdate']) ? $method['sdate'] : 0;
	$licenseid = isset($method['licenseid']) ? $method['licenseid'] : 0;
	
	$regdate = $sdate;	
	$licensedet = $ObjDB->QueryObject("SELECT fld_duration_type, fld_duration FROM itc_license_master WHERE fld_id='".$licenseid."'");
	$rowlicense = $licensedet->fetch_object();		
	if($rowlicense->fld_duration_type == 2) {		
		
			$date = strtotime("+".$rowlicense->fld_duration." year", strtotime($regdate));
			$totalduration = date("Y-m-d", $date);
	}
	else {
			$date = strtotime("+".$rowlicense->fld_duration." month", strtotime($regdate));
			$totalduration = date("Y-m-d", $date); 
	}
	echo date("m/d/Y",strtotime($totalduration));
}
/*--- Loade the grace period of the license ---*/
if($oper=="loadgrace" and $oper != " " )
{
	$lid = isset($method['lid']) ? $method['lid'] : 0;
	$count = isset($method['count']) ? $method['count'] : 0;
	$iplcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
											FROM itc_license_cul_mapping 
											WHERE fld_license_id='".$lid."' AND fld_active='1'");
	$modcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
											FROM itc_license_mod_mapping 
											WHERE fld_license_id='".$lid."' AND fld_active='1'");
	if($iplcount>0){
	?>
    
    <div class="one columns" style="padding-left:15px;" title="Significant Content Experience">
        IPl<span class="fldreq">*</span>                      	
        <dl class='field row'>
            <dt class='text'>
                <input  id="iplcount<?php echo $count; ?>" name="iplcount<?php echo $count; ?>" placeholder='IPL' tabindex="21" type='text' value="" maxlength="2" />
            </dt>
        </dl>
    </div>  
    <?php } if($modcount>0){?> 
    <div class="one columns" <?php if($iplcount==0){ ?>style="padding-left:15px;" <?php }?> title="Significant Content Experience">
        Modules<span class="fldreq">*</span>                          	
        <dl class='field row'>
            <dt class='text'>
                <input  id="modcount<?php echo $count; ?>" name="modcount<?php echo $count; ?>" placeholder='module' tabindex="22" type='text' value="" maxlength="2" />
            </dt>
        </dl>
    </div> 
    <?php 
	}?>
    <script>
		$("#iplcount<?php echo $count; ?>,#modcount<?php echo $count; ?>").keypress(function (e) {
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				return false;
			}
		});		
	</script>
    <?php 
}
/*--- Remove the license  ---*/
if($oper=="deletelicense" and $oper != " " )
{
	$trackid = isset($method['trackid']) ? $method['trackid'] : 0;
	$spid = isset($method['spid']) ? $method['spid'] : 0;
	
	$ObjDB->NonQuery("UPDATE itc_license_track 
					SET fld_delstatus='1',fld_deleted_by='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."'  
					WHERE fld_id='".$trackid."'");
	
	$chk = $ObjDB->SelectSingleValueInt("SELECT fld_id 
										FROM itc_license_track 
										WHERE fld_license_id 
										IN(SELECT fld_license_id FROM itc_license_track WHERE fld_id='".$trackid."') AND fld_district_id=0 
											AND fld_school_id='".$spid."' AND fld_user_id=0 AND fld_delstatus='0' 
										ORDER BY fld_id DESC LIMIT 0,1");
	if($chk!=0){
		$ObjDB->NonQuery("UPDATE itc_license_track 
						SET fld_upgrade='1',fld_updated_by='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."'  
						WHERE fld_id='".$chk."'");
	}
}
//Resend the mail to user 
if($oper=="resendteachermail" and $oper != '')
{
	$schoolid = (isset($method['mailid'])) ? $method['mailid'] : '';
	$userdetail = $ObjDB->QueryObject("SELECT b.fld_school_admin_id as userid,a.fld_username AS uname, a.fld_fname AS fname,a.fld_lname AS lname,a.fld_email AS email, a.fld_school_id as schoolidno
                                            FROM itc_user_master as a
                                            LEFT JOIN itc_school_master as b on a.fld_id=b.fld_school_admin_id
                                            WHERE b.fld_id='".$schoolid."' AND a.fld_delstatus='0' AND a.fld_activestatus='0'");
	 
	  while($rowshl = $userdetail->fetch_assoc())
	  { 
	 	 extract ($rowshl);
		 $shlname = $ObjDB->SelectSingleValue("SELECT fld_school_name 
		 									FROM itc_school_master 
											WHERE fld_id = '".$schoolidno."'");
	
	 
	
		$html_txt = '';
		$headers = '';
		$mailtitle = "School Purchase";
		
		$subj = "You're invited to join our learning management system";
		$random_hash = md5(date('r', time())); 
						
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=utf-8" . "\r\n"; 
		$headers .= "From: Synergy2 <info@pitsco.info>" . "\r\n";  
	
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
	}
}

	@include("footer.php");