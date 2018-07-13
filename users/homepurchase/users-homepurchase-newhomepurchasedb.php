<?php
@include("sessioncheck.php");

$oper = isset($method['oper']) ? $method['oper'] : '';

/*--- Select the city based the satate ---*/
if($oper == "changecity" and $oper != ""){
		$statevalue =  isset($method['statevalue']) ? $method['statevalue'] : '';
		?>
            <div class="selectbox">
              <input type="hidden" name="ddlcity" id="ddlcity" value="" onchange="$('#ddlcity').valid();fn_changezip(this.value);" >
              <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                <span class="selectbox-option input-medium" data-option=""> Select city</span>
                <b class="caret1"></b>
              </a>
              <div class="selectbox-options">
                <input type="text" class="selectbox-filter" placeholder="Search city" >
                <ul role="options">
                    <?php 
                        $cityqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_cityname) as cityname 
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
              <input type="hidden" name="ddlzip" id="ddlzip" value="" onchange="$('#ddlzip').valid();;">
              <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                <span class="selectbox-option input-medium" data-option=""> Select zip</span>
                <b class="caret1"></b>
              </a>
              <div class="selectbox-options">
                <input type="text" class="selectbox-filter" placeholder="Search zip" >
                <ul role="options">
                    <?php 
                        $zipqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_zipcode) as zipcode 
													FROM itc_state_city 
													WHERE fld_cityname='".$cityvalue."' AND fld_statevalue='".$statevalue."' AND 
														fld_delstatus=0
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
		<?php
	}	
	
/*--- Save the home purchase details ---*/	
if($oper == "savehomepurchase" and $oper != ""){
	try /**Here starts with saving the details uster master and district master tables**/
		{
			
			$date=date("Y-m-d H:i:s");
			$editid =  isset($method['id']) ? $method['id'] : '0';
			$fname =  isset($method['fname']) ? $method['fname'] : '';
			$lname =  isset($method['lname']) ? $method['lname'] : '';
			$email =  isset($method['email']) ? $method['email'] : '';
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
			$validate_address1=validate_datas($address1,'letterswithbasicpunc');
			$validate_fname=validate_datas($fname,'lettersonly');
			$validate_lname=validate_datas($lname,'lettersonly');
			$validate_email=validate_datatype($email,'email');
			
			$fname =  $ObjDB->EscapeStrAll($fname);
			$lname =  $ObjDB->EscapeStrAll($lname);
			$address1 =   $ObjDB->EscapeStrAll($address1);
			$tags = $ObjDB->EscapeStrAll($tags);
			
			if($validate_fname and $validate_lname and $validate_email)
			{
				if($editid==0){
					$userid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_user_master (fld_uuid, fld_email, fld_fname,
														 fld_lname,fld_profile_id,fld_role_id,fld_profile_pic, fld_created_by, fld_created_date)
														  VALUES ('".$uguid."','".$email."','".$fname."','".$lname."','5','5','".$photo."',
														  	'".$uid."','".$date."')");
					/*--Tags insert-----*/	
					fn_taginsert($tags,16,$uid,$userid);	
					$ObjDB->NonQuery("UPDATE itc_user_master SET fld_user_id='".$userid."' where fld_id='".$userid."'");
					
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
						$ObjDB->NonQuery("INSERT INTO itc_license_track (fld_license_id,fld_user_id, fld_no_of_users, 
											fld_remain_users, fld_start_date, fld_end_date, fld_ipl_count, fld_mod_count, 
											fld_auto_renewal, fld_created_by,fld_created_date,fld_renewal_count) 
										VALUES('".$ddllicense[$i]."','".$userid."','".$numusers[$i]."','".$numusers[$i]."',
											'".date('Y-m-d',strtotime($startdate[$i]))."','".date('Y-m-d',strtotime($enddate[$i]))."',
											'".$graceipl[$i]."','".$gracemod[$i]."','".$renewal[$i]."','".$uid."','".$date."','".$rcount[$i]."')");	
					}
					/*-------Mail----------*/
					$html_txt = '';
					$headers = '';
					$mailtitle = "Home Purchase";
					
					$subj = "You're invited to join our learning management system";
					$random_hash = md5(date('r', time())); 
									
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=utf-8" . "\r\n"; 
					$headers .= "From: Synergy ITC <do_not_reply@pitsco.com>" . "\r\n";   
				
					$html_txt = '<table cellpadding="0" cellspacing="0" width="90%" align="center" style="font-size:12px;"><tr><td valign="top" align="left"><strong style="font-size:24px;">'.$fname.' '.$lname.' (Home purchase admin)<br />You&lsquo;re invited to join our PITSCO Learning Management System</strong></td></tr><tr><td valign="top" align="left"><br />Hi '.$fname.', <br /><br /></td></tr><tr><td valign="top" align="center"><p></p><table width="98%" cellpadding="20" cellspacing="0" bgcolor="#ebf3fe" border="thin" bordercolor="#8ec7e2"><tr><td valign="top" align="left" style="font-size:14px;"><strong>All you need to do is choose a username and password.</strong><br />It only takes a few seconds.<br /><br /><strong>Click this link to get started:</strong><br /><a href="'.$domainame.'register.php?e='.md5($userid).'">'.$domainame.'register.php?e='.md5($userid).'</a><br /> <p><b>Access your <font style="font-style: italic;">Synergy ITC</font> account: &nbsp;&nbsp; By clicking on this link and logging into ITC, you agree the following agreement.</b><br><a href="'.__HOSTADDR__.'" target="_blank">'.__HOSTADDR__.'</a></p>
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
<p>If you are in need of technical support, please don&lsquo;t hesitate to contact our industry-leading customer support line at 800-774-4552.</p><p>Thank you,</p><p><strong>Pitsco Education</strong><br>800-774-4552<br>www.pitsco.com</p><p align="center"  style="font-style: italic;">Thank you for being a loyal Pitsco customer! <br>We appreciate all you do for students!</p></td></tr></table><p></p></td></tr><tr><td valign="top" align="left"><hr /><strong>Have questions?</strong> Contact your account administrator - at <a href="mailto:support@pitsco.com">support@pitsco.com</a></td></tr></table>';
					$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => $email,'subject' => $subj, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
					$client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
					$client->call('SendJangoMailTransactional', $param, '', '', false, true);
				}
				else{
					fn_tagupdate($tags,16,$editid,$uid);
					$ObjDB->NonQuery("UPDATE itc_user_master 
									SET fld_email = '".$email."', fld_fname = '".$fname."', fld_lname = '".$lname."', 
										fld_profile_pic = '".$photo."', fld_updated_by = '".$uid."', fld_updated_date = '".$date."' 
									WHERE fld_id = '".$editid."' AND fld_delstatus ='0'");
					
					$arr = array($officeno,$faxno,$mobileno,$homeno,$address1,$state1,$city1,$zipcode1);
					$j=3;
					for($i=0;$i<sizeof($arr);$i++)
					{
						if($arr[$i]!='')
						{
							$cnt=$ObjDB->SelectSingleValue("select count(fld_id) 
														from  itc_user_add_info where fld_user_id = '".$editid."' and  
															fld_field_id = '".$j."' ");
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
					/*-------add license track for district----------*/
					for($i=0;$i<sizeof($ddllicense)-1;$i++){
						$lid = explode(',',$ddllicense[$i]);//(licenseid,licensetrack fld_id)	
						
						$chk = $ObjDB->SelectSingleValueInt("select count(fld_id) 
															from itc_license_track 
															where fld_user_id='".$editid."' and fld_school_id=0 and 
																fld_license_id='".$lid[0]."' and fld_id='".$lid[1]."'");
						if($chk==0){
							$prelid = $ObjDB->SelectSingleValueInt("select count(fld_id) 
																from itc_license_track 
																where fld_user_id='".$editid."' and fld_school_id='0' and 
																fld_license_id='".$lid[0]."' and fld_delstatus='0'");
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
							$ObjDB->NonQuery("update itc_license_track 
											set fld_upgrade='0',fld_delstatus='0', fld_updated_by='".$uid."', fld_updated_date='".$date."' 
											where fld_user_id='".$editid."' and fld_school_id='0' and fld_license_id='".$lid[0]."'");
							
							$ObjDB->NonQuery("insert into itc_license_track (fld_license_id,fld_user_id, fld_no_of_users, fld_remain_users,
											fld_start_date,fld_end_date,fld_created_by,fld_created_date,fld_ipl_count,fld_mod_count,
											fld_auto_renewal,fld_renewal_count) 
											values('".$lid[0]."','".$editid."','".$numusers[$i]."','".$numusers[$i]."',
												'".date('Y-m-d',strtotime($startdate[$i]))."','".date('Y-m-d',strtotime($enddate[$i]))."',
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
														WHERE fld_district_id='0' AND fld_school_id='0' AND fld_user_id='".$editid."' 
															AND fld_profile_id<>10 AND fld_delstatus='0'");
							
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
							$subj = $licensename."-".$fname." ".$lname."(Home purchase) - ".$subject;
							$random_hash = md5(date('r', time())); 
											
							$headers = "MIME-Version: 1.0" . "\r\n";
							$headers .= "Content-type:text/html;charset=utf-8" . "\r\n"; 		
							$headers .= "From: Synergy2 <info@pitsco.info>" . "\r\n";		
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
						}
						else {
							$prevtotusers = $ObjDB->SelectSingleValueInt("SELECT fld_no_of_users 
																		FROM itc_license_track 
																		WHERE fld_id='".$lid[1]."'");
							$prevremainusers = $ObjDB->SelectSingleValueInt("SELECT fld_remain_users 
																			FROM itc_license_track 
																			WHERE fld_id='".$lid[1]."'");
							
							$totusers = $numusers[$i] - $prevtotusers;		
							$ObjDB->NonQuery("update itc_license_track set fld_delstatus='0',  
												fld_no_of_users='".($prevtotusers+$totusers)."', 
												fld_remain_users='".($prevremainusers+$totusers)."', 
												fld_start_date='".date('Y-m-d',strtotime($startdate[$i]))."', 
												fld_end_date='".date('Y-m-d',strtotime($enddate[$i]))."', 
												fld_updated_by='".$uid."', fld_ipl_count='".$graceipl[$i]."', 
												fld_mod_count='".$gracemod[$i]."', fld_auto_renewal='".$renewal[$i]."', 
												fld_renewal_count='".$rcount[$i]."', fld_updated_date='".date("Y-m-d H:i:s")."' 
											where fld_user_id='".$editid."' and fld_school_id=0 and fld_license_id='".$lid[0]."' 
												and fld_id='".$lid[1]."'");
						}
						
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
			 echo "fail1";
		}
}
/*--- Delete homepurchase Details ---*/
if($oper == "delethomepurchase" and $oper != ""){
	
	$editid =  isset($method['editid']) ? $method['editid'] : '';
	$validate_editid=true;
	if($editid!=0)$validate_editid=validate_datatype($editid,'int');
		if($validate_editid){
			$ObjDB->NonQuery("UPDATE itc_user_master 
							SET fld_delstatus = '1', fld_deleted_by = '".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."' 
							WHERE fld_id = '".$editid."'");
							
			$ObjDB->NonQuery("UPDATE itc_license_track 
							SET fld_delstatus='1', fld_deleted_by = '".$uid."', fld_deleted_date = '".date("Y-m-d H:i:s")."' 
							WHERE fld_user_id='".$editid."'");
			echo "success";
		}
		else{
			echo "fail";
		}
}
/*---Add license from hoem ---*/
if($oper == "addlicensehome" and $oper != ""){
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
                                $licqry = $ObjDB->QueryObject("SELECT fld_id,fld_license_name AS licensename 
															FROM itc_license_master 
															WHERE fld_delstatus='0' AND fld_license_type='1' 
															ORDER BY licensename ASC");
                                $i=1;
                                while($row = $licqry->fetch_assoc()){
									extract($row);
									?>
                                        <li><a tabindex="17" href="#" data-option="<?php echo $fld_id;?>" onclick="fn_licenseclick(<?php echo $fld_id;?>,<?php echo $count;?>)" id="option<?php echo $fld_id;?>" title="<?php echo $licensename;?>" class="tooltip"><?php echo $licensename;?></a></li>
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
                        <input  id="noofusers<?php echo $count;?>" tabindex="18" name="noofusers<?php echo $count;?>" placeholder='users' type='text' value="" readonly />
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
            <div class='one columns' id="remupgrade_<?php echo $count;?>"  style=" padding-left:8px;">
            	remove
                <p class='btn twelve columns'>
                    <a onclick="fn_removehplicense(<?php echo $count;?>,0,0)" id="rmove"> - </a>
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


// Reset password to the user	
if($oper=="resethp" and $oper != '')
{
		$editid = (isset($method['editid'])) ? $method['editid'] : '';
		$userdetail = $ObjDB->QueryObject("SELECT fld_username AS uname, fld_fname AS fname,fld_lname AS lname,fld_email AS email 
										FROM itc_user_master 
										WHERE fld_id='".$editid."' AND fld_delstatus='0' AND fld_activestatus='1'");
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
							SET fld_password = '".fnEncrypt($newpassword,$encryptkey)."', fld_updated_by = '".$uid."', 
								fld_updated_date = '".date("Y-m-d H:i:s")."' 
							WHERE fld_id = '".$editid."' AND fld_delstatus ='0'");
		$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => $email,'subject' => $subj, 'plainTex' => '','html' => wordwrap($html_txt),'options' => '','groupID' => '805014','log' => 'True');
		$client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
		$client->call('SendJangoMailTransactional', $param, '', '', false, true);		
}	
/*---View the home license details ---*/
if($oper=="homelicdet" and $oper != '')
{
	$homeid = (isset($method['homeid'])) ? $method['homeid'] : '';
	$homelic = (isset($method['homelic'])) ? $method['homelic'] : '';
		
	$userdetail = $ObjDB->QueryObject("SELECT a.`fld_id` AS licid, a.`fld_license_name` AS licname , b.fld_no_of_users AS nousers,
										b.fld_remain_users AS nousers, b.fld_start_date AS sdate, b.fld_end_date AS edate 
									FROM `itc_license_master` AS a, `itc_license_track` AS b 
									WHERE a.fld_id = b.fld_license_id AND b.fld_district_id='0' AND b.fld_school_id='0' 
										AND b.fld_user_id='".$homeid."' AND b.fld_license_id='".$homelic."' AND a.fld_delstatus='0'");
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
                               <a onclick="fn_closhomelic();">close</a>
                            </p>
                        </div>
                    </div>
                    
                    
                
                </div>
            </div>
        </div>
        </div>
<?php		
}
/*---Checking user count from home ---*/
if($oper=="chkusercount" and $oper != " " )
{	
	$homeid = isset($_POST['homeid']) ? $_POST['homeid'] : 0;
	$value = isset($method['value']) ? $method['value'] : 0;
	$trackid = isset($_POST['trackid']) ? $_POST['trackid'] : 0;
	$licenseid = isset($method['licenseid']) ? $method['licenseid'] : 0;
	
		$homeuserdet = $ObjDB->QueryObject("SELECT fld_no_of_users AS pvtotlusers, fld_remain_users AS pvremusers 
											FROM itc_license_track WHERE fld_user_id='".$homeid."' and fld_school_id=0 
												AND fld_license_id='".$licenseid."' and fld_id='".$trackid."'");
		$res= $homeuserdet->fetch_assoc();
		extract($res);
		
		$difference = $pvtotlusers-$pvremusers;
		
		if($difference<$value){
			$count =1;
		}
		if($count>0){
			echo "true";
		}
		else {
			echo "false";
		}
	
}

//add adtional license for district
if($oper=="upgradelicense" and $oper != " " )
{
	$count = isset($_POST['count']) ? $_POST['count'] : 0;
	$hpid = isset($_POST['hpid']) ? $_POST['hpid'] : 0;
	$lid = isset($_POST['lid']) ? $_POST['lid'] : 0;
	$trackid = isset($_POST['trackid']) ? $_POST['trackid'] : 0;
		 
	if($lid!=0){
		$qry = $ObjDB->QueryObject("SELECT fld_end_date AS startdate, fld_no_of_users AS users, fld_ipl_count, fld_mod_count 
									FROM itc_license_track WHERE fld_id='".$trackid."'");	
		if($qry->num_rows>0){
			$res = $qry->fetch_assoc();
			extract($res);			
			$regdate = $startdate;	
			$licensedet = $ObjDB->QueryObject("SELECT fld_duration_type, fld_duration 
											FROM itc_license_master WHERE fld_id='".$lid."'");
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
                                <span class="selectbox-option input-medium" data-option=""><?php echo $ObjDB->SelectSingleValue("SELECT fld_license_name FROM itc_license_master WHERE fld_id='".$lid."'");?></span>
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
                $iplcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_license_cul_mapping 
															WHERE fld_license_id='".$lid."' AND fld_active='1'");
                $modcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_license_mod_mapping 
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
                    <a onclick="fn_removehplicense(<?php echo $count;?>,0,<?php echo $trackid; ?>)" id="rmove"> - </a>
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
/*--- Fine the enddate using startdate ---*/
if($oper=="endtade" and $oper != " " )
{
	$sdate = isset($method['sdate']) ? $method['sdate'] : 0;
	$licenseid = isset($method['licenseid']) ? $method['licenseid'] : 0;
	
	$regdate = $sdate;	
	$licensedet = $ObjDB->QueryObject("SELECT fld_duration_type, fld_duration 
									FROM itc_license_master WHERE fld_id='".$licenseid."'");
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
/*---Find the grace period of the home license ---*/
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
        IPl                       	
        <dl class='field row'>
            <dt class='text'>
                <input  id="iplcount<?php echo $count; ?>" name="iplcount<?php echo $count; ?>" placeholder='IPL' tabindex="21" type='text' value="" maxlength="2" />
            </dt>
        </dl>
    </div>  
    <?php } if($modcount>0){?> 
    <div class="one columns" <?php if($iplcount==0){ ?>style="padding-left:15px;" <?php }?> title="Significant Content Experience">
        Modules                          	
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
/*---Remove the license from hoem ---*/
if($oper=="deletelicense" and $oper != " " )
{
	$trackid = isset($method['trackid']) ? $method['trackid'] : 0;
	$hpid = isset($method['hpid']) ? $method['hpid'] : 0;
	
	$ObjDB->NonQuery("update itc_license_track 
					set fld_delstatus='1',fld_deleted_by='".$uid."' 
					where fld_id='".$trackid."'");
					
	$chk = $ObjDB->SelectSingleValueInt("SELECT fld_id 
										FROM itc_license_track 
										WHERE fld_license_id IN(SELECT fld_license_id FROM itc_license_track WHERE fld_id='".$trackid."') 
											AND fld_district_id='0' AND fld_school_id=0 AND fld_user_id='".$hpid."' AND fld_delstatus='0' 
										ORDER BY fld_id DESC LIMIT 0,1");
	if($chk!=0){
		$ObjDB->NonQuery("update itc_license_track 
						set fld_upgrade='1',fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
						where fld_id='".$chk."'");
	}
}
/*----Resend the mail to user-----*/ 
if($oper=="resendmail" and $oper != '')
{
	$userid = (isset($method['mailid'])) ? $method['mailid'] : '';
	
	$userdetail = $ObjDB->QueryObject("SELECT fld_username AS uname, fld_fname AS fname,fld_lname AS lname,fld_email AS email, 
										fld_school_id as schoolidno 
									FROM itc_user_master 
									WHERE fld_id='".$userid."' AND fld_delstatus='0' AND fld_activestatus='0'");
	 
	  while($rowshl = $userdetail->fetch_assoc())
	  { 
	 	extract ($rowshl);
		$html_txt = '';
		$headers = '';
		$mailtitle = "Home Purchase";;
		
		$subj = "You're invited to join our learning management system";
		$random_hash = md5(date('r', time())); 
						
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=utf-8" . "\r\n"; 
		$headers .= "From: Synergy2 <info@pitsco.info>" . "\r\n";  
	
		$html_txt = '<table cellpadding="0" cellspacing="0" width="90%" align="center" style="font-size:12px;"><tr><td valign="top" align="left"><strong style="font-size:24px;">'.$fname.' '.$lname.' (Home purchase admin)<br />You&lsquo;re invited to join our PITSCO Learning Management System</strong></td></tr><tr><td valign="top" align="left"><br />Hi '.$fname.', <br /><br /></td></tr><tr><td valign="top" align="center"><p></p><table width="98%" cellpadding="20" cellspacing="0" bgcolor="#ebf3fe" border="thin" bordercolor="#8ec7e2"><tr><td valign="top" align="left" style="font-size:14px;"><strong>All you need to do is choose a username and password.</strong><br />It only takes a few seconds.<br /><br /><strong>Click this link to get started:</strong><br /><a href="'.$domainame.'register.php?e='.md5($userid).'">'.$domainame.'register.php?e='.md5($userid).'</a><br /> <p><b>Access your <font style="font-style: italic;">Synergy ITC</font> account: &nbsp;&nbsp; By clicking on this link and logging into ITC, you agree the following agreement.</b><br><a href="'.__HOSTADDR__.'" target="_blank">'.__HOSTADDR__.'</a></p>
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
<p>If you are in need of technical support, please don&lsquo;t hesitate to contact our industry-leading customer support line at 800-774-4552.</p><p>Thank you,</p><p><strong>Pitsco Education</strong><br>800-774-4552<br>www.pitsco.com</p><p align="center"  style="font-style: italic;">Thank you for being a loyal Pitsco customer! <br>We appreciate all you do for students!</p></td></tr></table><p></p></td></tr><tr><td valign="top" align="left"><hr /><strong>Have questions?</strong> Contact your account administrator - at <a href="mailto:support@pitsco.com">support@pitsco.com</a></td></tr></table>';
		
		$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => $email,'subject' => $subj, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
		$client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
		$client->call('SendJangoMailTransactional', $param, '', '', false, true);
	}
}

	@include("footer.php");