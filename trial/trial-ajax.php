<?php
	session_start(); 
	@include("table.class.php");
	@include("comm_func.php");
	
	$oper = isset($_REQUEST['oper']) ? $_REQUEST['oper'] : '0';
	
	if($oper == "changecity" and $oper != ""){
		$statevalue =  isset($_REQUEST['statevalue']) ? $_REQUEST['statevalue'] : '';		
		?>     
        <select id="ddlcity" name="ddlcity" class="dropdown-medium">
            <option value="">Select your City</option>
             <?php
                $cityqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_cityname) FROM itc_state_city WHERE fld_statevalue='".$statevalue."' AND fld_delstatus=0 ORDER BY fld_cityname ASC");
                while($rowcity = $cityqry->fetch_object()){
                    $cityname = ucfirst(strtolower($rowcity->fld_cityname));
                ?>
                    <option value="<?php echo $cityname; ?>"><?php echo $cityname; ?></option>
                <?php	
                }
            ?>
        </select>
		<?php
	}
	
	if($oper=="checkemail" and $oper != " " )
	{
		$email =  isset($_REQUEST['email']) ? fnEscapeCheck($_REQUEST['email']) : '';	
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_trial_users WHERE MD5(fld_email)='".$email."'");
		if($count == 0){ echo "true"; }	else { echo "false"; }
	}
	
	if($oper == "createtrialuser" and $oper != '') 
	{		
		$licenseid = 2;
		$fname = isset($_REQUEST['fname']) ? $ObjDB->EscapeStrAll($_REQUEST['fname']) : '';
		$lname = isset($_REQUEST['lname']) ? $ObjDB->EscapeStrAll($_REQUEST['lname']) : '';
		$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
		$state = isset($_REQUEST['state']) ? $_REQUEST['state'] : '';
		$city = isset($_REQUEST['city']) ? $_REQUEST['city'] : '';
		$zip = isset($_REQUEST['zip']) ? $_REQUEST['zip'] : '';
		$saddress = isset($_REQUEST['saddress']) ? $ObjDB->EscapeStrAll($_REQUEST['saddress']) : '';
		$pnumber = isset($_REQUEST['pnumber']) ? $_REQUEST['pnumber'] : '';
		$district = isset($_REQUEST['district']) ? $ObjDB->EscapeStrAll($_REQUEST['district']) : '';
		$school = isset($_REQUEST['school']) ? $ObjDB->EscapeStrAll($_REQUEST['school']) : '';
		$title = isset($_REQUEST['title']) ? $ObjDB->EscapeStrAll($_REQUEST['title']) : '';
		
		/*------------create new user account in LMS---------------*/
		$new_user_id = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_user_master (fld_email, fld_profile_id, fld_role_id, fld_created_date, fld_fname, fld_lname, fld_username, 
														fld_password, fld_activestatus) 
													VALUES ('".$email."', '5', '5', '".date("Y-m-d H:i:s")."','".$fname."','".$lname."','trialuser',
														'".fnEncrypt('123456',$encryptkey)."','1')");		
		$ObjDB->NonQuery("UPDATE itc_user_master SET fld_username='".$new_user_id."', fld_user_id='".$new_user_id."' WHERE fld_id='".$new_user_id."'");		
		
		$arr = array($officeno,$faxno,$pnumber,$homeno,$saddress,$state,$city,$zip);
		$j=3;
		for($i=0;$i<sizeof($arr);$i++)
		{
			if($arr[$i]!='')
			{
				$ObjDB->NonQuery("INSERT INTO itc_user_add_info (fld_user_id,fld_field_id,fld_field_value) VALUES ('".$new_user_id."','".$j."','".$arr[$i]."')");
			}
			$j++;
		}
		
		/*-----Create student account-------*/
		$uname = "studenttrial".$new_user_id;
		$pass = generatePassword();
		$studentid = $ObjDB->NonQueryWithMaxValue ("INSERT INTO itc_user_master(fld_username, fld_password, fld_profile_id,fld_role_id, fld_fname, fld_lname, fld_activestatus, 
														fld_user_id, fld_created_by, fld_created_date)
													VALUES('".$uname."','".fnEncrypt($pass,$encryptkey)."','10','5','".$fname."','".$lname."','1','".$new_user_id."',
														'".$new_user_id."','".date("y-m-d H:i:s")."')");	
														
		$userid = $studentid;	
		$indid = $new_user_id;
		$sessmasterprfid = 10;
		$schoolid = 0;
		/*------License details---------*/			
		$regdate = date("Y-m-d H:i:s");					
		$licensedet = $ObjDB->QueryObject("SELECT fld_duration_type as durationtype, fld_duration as duration FROM itc_license_master WHERE fld_id='".$licenseid."'");
		$rowlicense = $licensedet->fetch_assoc();
		extract($rowlicense);		
		if($durationtype == 2) {
				$date = strtotime("+".$duration." year", strtotime($regdate));
				$totalduration = date("Y-m-d H:i:s", $date); 
		}
		else {
				$date = strtotime("+".$duration." month", strtotime($regdate));
				$totalduration = date("Y-m-d H:i:s", $date);
		}
		
		$ObjDB->NonQuery("INSERT INTO itc_license_track (fld_license_id,fld_user_id, fld_no_of_users, fld_remain_users, fld_start_date, fld_end_date, fld_created_by, fld_created_date) 
						VALUES('".$licenseid."','".$new_user_id."','1','1','".date('Y-m-d',strtotime($regdate))."','".$totalduration."', '".$new_user_id."', '".date("Y-m-d H:i:s")."')");
		
		$ObjDB->NonQuery("INSERT INTO itc_trial_users (fld_user_id, fld_fname, fld_lname, fld_email, fld_created_by, fld_created_date, fld_district, fld_school, fld_title) 
						VALUES('".$new_user_id."','".$fname."','".$lname."','".$email."','".$new_user_id."', '".date("Y-m-d H:i:s")."',  '".$district."', '".$school."','".$title."')");
			
		/*----------sending mail to user-------------*/
		$units='';
		$qry_unit = $ObjDB->QueryObject("SELECT b.fld_unit_name AS unitname 
										FROM itc_license_cul_mapping AS a LEFT JOIN itc_unit_master AS b ON a.fld_unit_id=b.fld_id 
										WHERE a.fld_license_id='".$licenseid."' AND a.fld_active='1' GROUP BY a.fld_unit_id");				
		while($rowunit = $qry_unit->fetch_assoc()){
			  extract($rowunit);					  
			  $units.='<span style="padding-left:30px;">'.$unitname.'</span><br />';
			 }
		$lessons='';
		$qry_lesson = $ObjDB->QueryObject("SELECT b.fld_ipl_name AS lessonname 
											FROM itc_license_cul_mapping AS a LEFT JOIN itc_ipl_master AS b ON a.fld_lesson_id=b.fld_id 
											WHERE a.fld_license_id='".$licenseid."' AND a.fld_active='1' GROUP BY a.fld_lesson_id");				
		while($rowlesson = $qry_lesson->fetch_assoc()){
			  extract($rowlesson);	
			  $lessons.='<span style="padding-left:30px;">'.$lessonname.'</span><br />';				  
			}
		$subj = "Welcome to Synergy ITC";
				
		$random_hash = md5(date('r', time())); 
						
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n"; 
		$headers .= "From: Synergy ITC <do_not_reply@pitsco.com>" . "\r\n";
	
		$html_txt = '<table cellpadding="0" cellspacing="0" width="90%" align="center" style="font-size:12px;"><tr><td valign="top" align="left"><br />Hi '.stripcslashes($fname).', <br /><br /></td></tr><tr><td valign="top" align="left">Welcome to Synergy ITC.</td></tr><tr><td valign="top" align="left">Thank you for trying Synergy ITC</td></tr><tr><td valign="top" align="center"><p></p><table width="98%" cellpadding="20" cellspacing="0" bgcolor="#ebf3fe" border="thin" bordercolor="#8ec7e2"><tr><td style="font-size:14px;" colspan="2"><strong>Student Details:</strong><br />Student Username: '.$uname.'<br />Student Password: '.$pass.'</td></tr><tr><td style="font-size:14px;" colspan="2"><strong>Content Details:</strong><br />Unit: <br /> '.$units.'IPL: <br />'.$lessons.'</td></tr><tr><td style="font-size:14px;" colspan="2">Click this link to get started<br /><a href="itc.pitsco.com/login.php">itc.pitsco.com/login.php</a><br /></td></tr></table></td></tr><tr><td valign="top" align="left"><hr /><strong>Have questions?</strong> Contact our support team at <a href="mailto:support@pitsco.com">support@pitsco.com.</a></td></tr></table>';
		$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => $email,'subject' => $subj, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
		$client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
		$client->call('SendJangoMailTransactional', $param, '', '', false, true);
			$username = $uname;
			$encryptpass = fnEncrypt($pass,$encryptkey);
			$cookie = 1;
			$check = $ObjDB->Count("SELECT fld_username FROM itc_user_master 
										WHERE MD5(fld_username)='".md5($username)."' 
											AND fld_password='".$encryptpass."' 
											AND fld_delstatus='0' 
											AND fld_activestatus='1'");
											
			if($check > 0) {
				
				$userdetqry = $ObjDB->QueryObject("SELECT a.fld_id AS userid, a.fld_profile_id AS sessprofileid, a.fld_role_id AS sessroleid, 
														 b.fld_prf_main_id AS sessmasterprfid, b.fld_profile_name AS sessprofilename, 
														 CONCAT(a.fld_fname,' ',a.fld_lname) AS sessfname, a.fld_school_id AS schoolid,
														 a.fld_district_id AS distid, a.fld_user_id AS indid 
														 FROM itc_user_master AS a, itc_profile_master AS b 
														 WHERE a.fld_profile_id=b.fld_id 
															AND MD5(a.fld_username)='".md5($username)."' 
															AND a.fld_password='".$encryptpass."' 
															AND (b.fld_delstatus = 0 OR b.fld_delstatus=2) 
															AND a.fld_activestatus='1' 
															AND a.fld_delstatus='0'");
				$rowuserdet = $userdetqry->fetch_assoc();
				extract($rowuserdet);
				
				$sessionid = md5(generatePassword(8,8));
	
				$logindate = date("Y-m-d H:i:s");
				
				$chktrial=$ObjDB->Count("SELECT fld_id FROM itc_user_master 
											WHERE fld_id='".$userid."' 
												AND fld_created_by IN (SELECT fld_user_id FROM itc_trial_users)");
				
					$_SESSION['trialuser'] = 1;
				
				
				$_SESSION['is_user_login'] = true;
				$_SESSION['username'] = $username;
				$_SESSION['usr_full_name'] = $sessfname;
				$_SESSION['userid'] = $userid;
				$_SESSION['user_profile'] = $sessprofileid;
				$_SESSION['role_id'] = $sessroleid;
				$_SESSION['prf_main_id'] = $sessmasterprfid;
				$_SESSION['prf_name'] = $sessprofilename;
				$_SESSION['sessionid'] = $sessionid;	
				$_SESSION['schoolid'] = $schoolid;	
				$_SESSION['indid'] = $indid;
				$_SESSION['distid'] = $distid;	
				
				$_SESSION['username1'] = '';
				$_SESSION['usr_full_name1'] = '';
				$_SESSION['userid1'] = '';
					
				if($cookie == 1){
					setcookie("cookieflag", 1, time()+3600*24);
					setcookie("is_user_login", 1, time()+3600*24);
					setcookie("username", $username, time()+3600*24);	
					setcookie("usr_full_name", $sessfname, time()+3600*24);
					setcookie("userid", $userid, time()+3600*24);	
					setcookie("user_profile", $sessprofileid, time()+3600*24);	
					setcookie("role_id", $sessroleid, time()+3600*24);	
					setcookie("prf_main_id", $sessmasterprfid, time()+3600*24);	
					setcookie("prf_name", $sessprofilename, time()+3600*24);	
					setcookie("sessionid", $sessionid, time()+3600*24);
					setcookie("schoolid", $schoolid, time()+3600*24);
					setcookie("distid", $distid, time()+3600*24);
					setcookie("trialuser", '0', time()+3600*24);
					setcookie("indid", '0', time()+3600*24);
					
					setcookie("username1", '', time()+3600*24);	
					setcookie("usr_full_name1", '', time()+3600*24);
					setcookie("userid1", '', time()+3600*24);
				}
				
				$ObjDB->NonQuery("INSERT INTO itc_login_track_master (fld_user_id, fld_login_datetime, fld_current_datetime, fld_ipaddress, fld_sessionid, fld_browsername) VALUES ('".$userid."','".$logindate."','".$logindate."','".$_SERVER['REMOTE_ADDR']."','".$sessionid."','".$_SERVER['HTTP_USER_AGENT']."')");				
				echo "success";
			}	
		
		/*----------sending mail to PITSCO-------------*/
		$html_txt = '<table cellpadding="0" cellspacing="0" width="90%" align="center" style="font-size:12px;"><tr><td valign="top" align="left">The following user account was created<br /></td></tr><tr><td valign="top" align="left"><br />Student Username: '.$uname.'</td></tr><tr><td valign="top" align="left">Student Password: '.$pass.'</td></tr><tr><td valign="top" align="left"><br />'.stripcslashes($fname).' '.stripcslashes($lname).'</td></tr><tr><td valign="top" align="left">'.$email.'</td></tr><tr><td valign="top" align="left">'.$pnumber.'</td></tr><tr><td valign="top" align="left"><br />'.stripcslashes($district).'</td></tr><tr><td valign="top" align="left">'.stripcslashes($school).'</td></tr><tr><td valign="top" align="left">'.stripcslashes($title).'</td></tr><tr><td valign="top" align="left"><br />'.stripcslashes($saddress).'</td></tr><tr><td valign="top" align="left">'.$city.'</td></tr><tr><td valign="top" align="left">'.$state.'</td></tr><tr><td valign="top" align="left">'.$zip.'</td></tr></table>';			
		
		$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => 'cparenti@pitsco.com','subject' => 'New User registered - 30 days free trial', 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
		$client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
		$client->call('SendJangoMailTransactional', $param, '', '', false, true);
		$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => 'ccampbell@pitsco.com','subject' => 'New User registered - 30 days free trial', 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
		$client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
		$client->call('SendJangoMailTransactional', $param, '', '', false, true);	
	}
	
	if($oper=="sendfeedback" and $oper != " " )
	{		
		$username =  isset($_REQUEST['username']) ? fnEscapeCheck($_REQUEST['username']) : '';
		$msubject =  isset($_REQUEST['subject']) ? $_REQUEST['subject'] : '';
		$message =  isset($_REQUEST['message']) ? $_REQUEST['message'] : '';
		
		$createdby = $ObjDB->SelectSingleValue("SELECT fld_created_by  FROM itc_user_master WHERE md5(fld_username)='".$username."' AND fld_delstatus='0'");
		$qry = $ObjDB->QueryObject("SELECT fld_email AS email, fld_id AS userid, fld_fname AS fname, fld_lname AS lname, fld_created_by AS createdby FROM itc_user_master WHERE fld_id='".$createdby."' AND fld_delstatus='0'");
		
		if($qry->num_rows>0){
			$res = $qry->fetch_assoc();
			extract($res);
			
			$arrcombine = array('','','','','','','','','','','','','','','');
			$arrfieldid = array();
			$arrfieldvalue = array();
		
			$optionaldet = $ObjDB->QueryObject("SELECT fld_field_id, fld_field_value FROM itc_user_add_info WHERE fld_user_id='".$userid."'");
			$rows = $optionaldet->num_rows;
			if($rows > 0){
				while($rowoptionaldet=$optionaldet->fetch_object())
				{
					array_push($arrfieldid,$rowoptionaldet->fld_field_id);
					array_push($arrfieldvalue,$rowoptionaldet->fld_field_value);
				}
				$arrcombine=array_combine($arrfieldid,$arrfieldvalue);
				$arrcombine = getarrayvalues($arrfieldid,$arrcombine);
			}
		

			/*----------sending mail to PITSCO-------------*/
			$html_txt = '<table cellpadding="0" cellspacing="0" width="90%" align="center" style="font-size:12px;"><tr><td valign="top" align="left">The following feedback from the user<br /></td></tr><tr><td valign="top" align="left">Message:<br />'.$message.'</td></tr><tr><td valign="top" align="left"><br />User details:<br />'.$fname.' '.$lname.'</td></tr><tr><td valign="top" align="left">'.$email.'</td></tr>';
			
			$pnumber = ($arrcombine[6] != '') ? $arrcombine[6] : '';
			if($pnumber != ''){ 
				$html_txt .= '<tr><td valign="top" align="left">'.$pnumber.'</td></tr>'; 
			}
			
			$saddress = ($arrcombine[7] != '') ? $arrcombine[7] : '';
			if($saddress != ''){ 
				$html_txt .= '<tr><td valign="top" align="left"><br />'.$saddress.'</td></tr>';
			}
			
			$city = ($arrcombine[9] != '') ? $arrcombine[9] : '';
			if($city != ''){ 
				$html_txt .= '<tr><td valign="top" align="left">'.$city.'</td></tr>';
			}
			
			$state = ($arrcombine[8] != '') ? $arrcombine[8] : '';
			if($state != ''){ 
				$html_txt .= '<tr><td valign="top" align="left">'.$state.'</td></tr>';
			}
			
			$zip = ($arrcombine[10] != '') ? $arrcombine[10] : '';
			if($zip != ''){ 
				$html_txt .= '<tr><td valign="top" align="left">'.$zip.'</td></tr>';
			}
			
			$html_txt .= '</table>';
			
			$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => 'sanjay@nanonino.com','subject' => $msubject, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
			$client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
			$client->call('SendJangoMailTransactional', $param, '', '', false, true);
			echo "success";
			//msparlin@pitsco.com				
		}
	}
	
	@include("footer.php");