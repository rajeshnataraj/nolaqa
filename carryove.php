<?php
@include("includes/table.class.php");
@include("includes/comm_func.php");
@require_once('config.php');

//date_default_timezone_set('America/Denver');
echo date("Y-m-d");
//carry over seat
$licenseqry = $ObjDB->QueryObject("SELECT fld_id AS lid, fld_license_id, fld_district_id, fld_school_id, fld_user_id FROM itc_license_track WHERE fld_delstatus='0' AND DATE(fld_start_date) = DATE(NOW())");
if($licenseqry->num_rows>0)
{
	while($rowlicenseqry = $licenseqry->fetch_assoc())
	{
		extract($rowlicenseqry);
		
		$fieldid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_license_track WHERE fld_license_id='".$fld_license_id."' AND fld_district_id='".$fld_district_id."' AND fld_school_id='".$fld_school_id."' AND fld_user_id='".$fld_user_id."' AND fld_delstatus='0' AND fld_carry='0' AND fld_id<>'".$lid."' ORDER BY fld_id DESC LIMIT 0,1");
		
		if($fieldid!='' && $fieldid!=0)
		{
			$remainingusers = $ObjDB->SelectSingleValueInt("SELECT fld_remain_users FROM itc_license_track WHERE fld_id='".$fieldid."'");
			
			$usersqry = $ObjDB->QueryObject("SELECT fld_remain_users, fld_no_of_users FROM itc_license_track WHERE fld_id='".$lid."'");
			$rowusersqry = $usersqry->fetch_assoc();
			extract($rowusersqry);
			$totalusers = $fld_no_of_users + $remainingusers;
			$totalusersrem = $fld_remain_users + $remainingusers;
			
			$ObjDB->NonQuery("UPDATE itc_license_track SET fld_remain_users='".$totalusersrem."', fld_no_of_users='".$totalusers."' WHERE fld_id='".$lid."'");
			
			$ObjDB->NonQuery("UPDATE itc_license_track SET fld_carry='1' WHERE fld_id='".$fieldid."'");
		}
	}
}



//auto renewal non district user(school purchase,Individual)
$renewalqry = $ObjDB->QueryObject("SELECT fld_id AS lid, fld_license_id, fld_district_id, fld_school_id, fld_user_id, fld_remain_users, fld_no_of_users, fld_end_date, fld_ipl_count, fld_mod_count, fld_renewal_count FROM itc_license_track WHERE fld_delstatus='0' AND fld_auto_renewal='1' AND DATE(fld_end_date) = '".date("Y-m-d",strtotime('-1 day'))."' AND fld_district_id='0'");

if($renewalqry->num_rows>0)
{
	while($rowrenewalqry = $renewalqry->fetch_assoc())
	{
		extract($rowrenewalqry);
		$profile='';
		if($fld_school_id=='0' && $fld_user_id=='0')
			$profile = 6;	
		else if($fld_user_id=='0' && $fld_district_id=='0')
			$profile = 7;
		else if($fld_district_id=='0' && $fld_school_id=='0')
			$profile = 5;	
		if($profile!=''){	
			$adminqry = $ObjDB->QueryObject("SELECT fld_fname AS afname, fld_lname AS alname, fld_email AS aemail, 
(SELECT a.fld_field_value FROM itc_user_add_info AS a WHERE a.fld_user_id=fld_id AND a.fld_field_id=3 AND a.fld_delstatus='0') AS aphone FROM itc_user_master 
WHERE fld_district_id='".$fld_district_id."' AND fld_school_id='".$fld_school_id."' AND fld_user_id='".$fld_user_id."'
 AND fld_profile_id='".$profile."' AND fld_delstatus='0' ORDER BY fld_id LIMIT 0,1"); 
			if($adminqry->num_rows>0){
				extract($adminqry->fetch_assoc());
			}
		}
		$autorenewal=1;
		$regdate = $fld_end_date;	
		$licensedet = $ObjDB->QueryObject("SELECT fld_duration_type, fld_duration FROM itc_license_master WHERE fld_id='".$fld_license_id."'");
		$rowlicense = $licensedet->fetch_object();		
		if($rowlicense->fld_duration_type == 2) {		
			$date = strtotime("+".$rowlicense->fld_duration." year", strtotime($regdate));
			$totalduration = date("m/d/Y", $date);
		}
		else {
			$date = strtotime("+".$rowlicense->fld_duration." month", strtotime($regdate));
			$totalduration = date("m/d/Y", $date); 
		}
		
		$start = date("Y-m-d", strtotime("+1 day",strtotime($fld_end_date)));
		$end = date("Y-m-d", strtotime("+1 day",strtotime($totalduration)));
		
		$totalusers = $fld_no_of_users + $fld_remain_users;
		$totalusersrem = $fld_remain_users + $fld_remain_users;
		if($fld_renewal_count>0){
			if($fld_renewal_count<=1) $autorenewal=0;
			$newrenewalcount = $fld_renewal_count-1;
			
			$ObjDB->NonQuery("INSERT INTO itc_license_track(fld_license_id, fld_district_id, fld_school_id, fld_user_id, fld_remain_users, fld_no_of_users, fld_start_date, fld_end_date, fld_auto_renewal, fld_upgrade, fld_ipl_count, fld_mod_count, fld_renewal_count) values('".$fld_license_id."', '".$fld_district_id."', '".$fld_school_id."', '".$fld_user_id."', '".$totalusers."', '".$totalusers."', '".$start."', '".$end."', '".$autorenewal."', '1','".$fld_ipl_count."','".$fld_mod_count."','".$newrenewalcount."')");
			
			$ObjDB->NonQuery("UPDATE itc_license_track SET fld_carry='1', fld_upgrade='0' WHERE fld_id='".$lid."'");
			$licensename = $ObjDB->SelectSingleValue("SELECT fld_license_name FROM itc_license_master WHERE fld_id='".$fld_license_id."' AND fld_delstatus='0'");		
			
			
			$up = "'";
			
			$qry = $ObjDB->QueryObject("SELECT fld_fname, fld_lname, fld_email, fld_username, fld_id, fld_profile_id FROM itc_user_master WHERE fld_district_id='".$fld_district_id."' AND fld_school_id='".$fld_school_id."' AND fld_user_id='".$fld_user_id."' AND fld_profile_id<>10 AND fld_profile_id<>11 AND fld_delstatus='0'");
			
			if($qry->num_rows>0)
			{
				while($rowqry = $qry->fetch_assoc())
				{
					extract($rowqry);
					
					if($fld_email!='')
					{
						
						$subj = $licensename." - Lease Renewal";
						$random_hash = md5(date('r', time())); 
										
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=utf-8" . "\r\n"; 
						$headers .= "From: Synergy2 <info@pitsco.info>" . "\r\n"; 
						
						$admindetails='';
						
						$auto="yes";
						$content = '<tr><td valign="top" align="left">The License below has been renewed:</td></tr>';
						if($fld_school_id=='0' && $fld_user_id=='0' && $fld_profile_id==6){							
							$orname = $ObjDB->SelectSingleValue("select fld_district_name from itc_district_master where fld_id='".$fld_district_id."'")."(District purchase)";
						}
						else if($fld_user_id=='0' && $fld_district_id=='0' && $fld_profile_id==7){						
							$orname = $ObjDB->SelectSingleValue("select fld_school_name from itc_school_master where fld_id='".$fld_school_id."'")."(School purchase)";
						}
						else if($fld_district_id=='0' && $fld_school_id=='0' && $fld_profile_id==5){						
							$orname = $ObjDB->SelectSingleValue("select concat(fld_fname,' ',fld_lname) as iname from itc_user_master where fld_id='".$fld_user_id."'")."(Home purchase)";
						}
						else{					
							$admindetails= 'Administrator: '.$afname.' '.$alname.'<br />Phone: '.$aphone.'<br />Email: '.$aemail.'';							
						}
						
						
						$html_txt = '<table width="98%" cellpadding="10" cellspacing="0"><tr><td valign="top" align="left"><br />Hi '.$fld_fname.', <br /></td></tr>'.$content.'
						<tr><td valign="top" align="left">Lease: '.$licensename.'<br />
						Start date: '.date("m/d/Y",strtotime($start)).'<br />
						End date: '.date("m/d/Y",strtotime($end)).'<br />
						Automatic Renew: '.$auto.' / '.$newrenewalcount.' times<br />
						Available seats: '.$totalusers.'<br /><br />
						'.$admindetails.'</td></tr></table>';						
						$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => $fld_email,'subject' => $subj, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
						$client = new nusoap_client( PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
					}
				}
			}		
			//for pitsco admin
			$html_txt = '';
			$headers = '';		
			$subj = $licensename."-".$orname." - Lease Renewal";
			$random_hash = md5(date('r', time())); 
							
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=utf-8" . "\r\n"; 		
			$headers .= "From: Synergy2 <info@pitsco.info>" . "\r\n";		
			$html_txt = '<table width="98%" cellpadding="10" cellspacing="0">'.$content.'
						<tr><td valign="top" align="left">Lease: '.$licensename.'<br />
						Start date: '.date("m/d/Y",strtotime($start)).'<br />
						End date: '.date("m/d/Y",strtotime($end)).'<br />
						Automatic Renew: '.$auto.' / '.$newrenewalcount.' times<br />
						Available seats: '.$totalusers.'<br /><br />
						</td></tr></table>';
			$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => 'systems_support@pitsco.com','subject' => $subj, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
		}
	}
}



//auto renewal for district
$renewalqrydist = $ObjDB->QueryObject("SELECT fld_id AS lid, fld_license_id, fld_district_id, fld_school_id, fld_user_id, fld_remain_users, fld_no_of_users, fld_end_date, fld_ipl_count, fld_mod_count, fld_renewal_count FROM itc_license_track WHERE fld_delstatus='0' AND fld_auto_renewal='1' AND DATE(fld_end_date) = '".date("Y-m-d",strtotime('-1 day'))."' AND fld_school_id='0' AND fld_user_id='0'");

if($renewalqrydist->num_rows>0)
{
	while($rowrenewalqrydist = $renewalqrydist->fetch_assoc())
	{
		extract($rowrenewalqrydist);
		$profile='';
		if($fld_school_id=='0' && $fld_user_id=='0')
			$profile = 6;	
		else if($fld_user_id=='0' && $fld_district_id=='0')
			$profile = 7;
		else if($fld_district_id=='0' && $fld_school_id=='0')
			$profile = 5;	
		if($profile!=''){	
			$adminqry = $ObjDB->QueryObject("SELECT fld_fname AS afname, fld_lname AS alname, fld_email AS aemail, 
(SELECT a.fld_field_value FROM itc_user_add_info AS a WHERE a.fld_user_id=fld_id AND a.fld_field_id=3 AND a.fld_delstatus='0') AS aphone FROM itc_user_master 
WHERE fld_district_id='".$fld_district_id."' AND fld_school_id='".$fld_school_id."' AND fld_user_id='".$fld_user_id."'
 AND fld_profile_id='".$profile."' AND fld_delstatus='0' ORDER BY fld_id LIMIT 0,1"); 
			if($adminqry->num_rows>0){
				extract($adminqry->fetch_assoc());
			}
		}
		$licensename= $ObjDB->SelectSingleValue("select fld_license_name from itc_license_master where fld_id='".$fld_license_id."'");
		$autorenewal=1;
		$regdate = $fld_end_date;	
		$licensedet = $ObjDB->QueryObject("SELECT fld_duration_type, fld_duration FROM itc_license_master WHERE fld_id='".$fld_license_id."'");
		$rowlicense = $licensedet->fetch_object();		
		if($rowlicense->fld_duration_type == 2) {		
			$date = strtotime("+".$rowlicense->fld_duration." year", strtotime($regdate));
			$totalduration = date("m/d/Y", $date);
		}
		else {
			$date = strtotime("+".$rowlicense->fld_duration." month", strtotime($regdate));
			$totalduration = date("m/d/Y", $date); 
		}
		
		$start = date("Y-m-d", strtotime("+1 day",strtotime($fld_end_date)));
		$end = date("Y-m-d", strtotime("+1 day",strtotime($totalduration)));
		
		$totalusers = $fld_no_of_users + $fld_remain_users;
		$totalusersrem = $fld_remain_users + $fld_remain_users;
		
		if($fld_renewal_count>0){
			if($fld_renewal_count<=1) $autorenewal=0;
			$newrenewalcount = $fld_renewal_count-1;
			
			$ObjDB->NonQuery("INSERT INTO itc_license_track(fld_license_id, fld_district_id, fld_school_id, fld_user_id, fld_remain_users, fld_no_of_users, fld_start_date, fld_end_date, fld_auto_renewal, fld_upgrade, fld_ipl_count, fld_mod_count, fld_renewal_count) values('".$fld_license_id."', '".$fld_district_id."', '".$fld_school_id."', '".$fld_user_id."', '".$totalusers."', '".$totalusers."', '".$start."', '".$end."', '".$autorenewal."', '1','".$fld_ipl_count."','".$fld_mod_count."','".$newrenewalcount."')");
			
			$maxfld = $ObjDB->SelectSingleValueInt("SELECT MAX(fld_id) FROM itc_license_track");
			
			$ObjDB->NonQuery("UPDATE itc_license_track SET fld_carry='1', fld_upgrade='0' WHERE fld_id='".$lid."'");
			
                        $up = "'";
			
			$renewalqrysch = $ObjDB->QueryObject("SELECT fld_id AS schid, fld_license_id, fld_district_id, fld_school_id, fld_user_id, fld_remain_users, fld_no_of_users, fld_end_date FROM itc_license_track WHERE fld_delstatus='0' AND fld_distlictrack_id='".$lid."'");
			if($renewalqrysch->num_rows>0)
			{
				while($rowrenewalqrysch = $renewalqrysch->fetch_assoc())
				{
					extract($rowrenewalqrysch);
					
					$totaluserssch = $fld_no_of_users + $fld_remain_users;
					$totalusersremsch = $fld_remain_users + $fld_remain_users;
					
					$ObjDB->NonQuery("INSERT INTO itc_license_track(fld_license_id, fld_district_id, fld_school_id, fld_user_id, fld_remain_users, fld_no_of_users, fld_start_date, fld_end_date, fld_auto_renewal, fld_upgrade, fld_distlictrack_id) values('".$fld_license_id."', '".$fld_district_id."', '".$fld_school_id."', '".$fld_user_id."', '".$totalusersremsch."', '".$totaluserssch."', '".$start."', '".$end."', '".$autorenewal."', '1', '".$maxfld."')");
					
					$ObjDB->NonQuery("UPDATE itc_license_track SET fld_carry='1', fld_upgrade='0' WHERE fld_id='".$schid."'");
									
					$qry = $ObjDB->QueryObject("SELECT fld_fname, fld_lname, fld_email, fld_username, fld_id, fld_profile_id FROM itc_user_master WHERE fld_district_id='".$fld_district_id."' AND fld_school_id='".$fld_school_id."' AND fld_user_id='".$fld_user_id."' AND fld_profile_id<>10 AND fld_profile_id<>11 AND fld_delstatus='0' ");
					
					if($qry->num_rows>0)
					{
						while($rowqry = $qry->fetch_assoc())
						{
							extract($rowqry);
							
							if($fld_email!='')
							{							
								
								$subj = $licensename." - Lease Renewal";
								$random_hash = md5(date('r', time())); 
												
								$headers = "MIME-Version: 1.0" . "\r\n";
								$headers .= "Content-type:text/html;charset=utf-8" . "\r\n"; 
								$headers .= "From: Synergy2 <info@pitsco.info>" . "\r\n";  							
								$admindetails='';
								
								$auto="yes";
								$content = '<tr><td valign="top" align="left">The License below has been renewed:</td></tr>';											
								$admindetails= 'Administrator: '.$afname.' '.$alname.'<br />Phone: '.$aphone.'<br />Email: '.$aemail.'';						
								
								$html_txt = '<table width="98%" cellpadding="10" cellspacing="0"><tr><td valign="top" align="left"><br />Hello '.$fld_fname.', <br /></td></tr>'.$content.'
								<tr><td valign="top" align="left">Lease: '.$licensename.'<br />
								Start date: '.date("m/d/Y",strtotime($start)).'<br />
								End date: '.date("m/d/Y",strtotime($end)).'<br />
								Automatic Renew: '.$auto.' / '.$newrenewalcount.' times<br />
								Available seats: '.$totalusers.'<br /><br />
								'.$admindetails.'</td></tr></table>';						
								$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => $fld_email,'subject' => $subj, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
							}
						}
					}		
					//for pitsco admin
					$html_txt = '';
					$headers = '';		
					$subj = $licensename."-".$orname." - Lease Renewal";
					$random_hash = md5(date('r', time())); 
									
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=utf-8" . "\r\n"; 		
					$headers .= "From: Synergy2 <info@pitsco.info>" . "\r\n";		
					$html_txt = '<table width="98%" cellpadding="10" cellspacing="0">'.$content.'
								<tr><td valign="top" align="left">Lease: '.$licensename.'<br />
								Start date: '.date("m/d/Y",strtotime($start)).'<br />
								End date: '.date("m/d/Y",strtotime($end)).'<br />
								Automatic Renew: '.$auto.' / '.$newrenewalcount.' times<br />
								Available seats: '.$totalusers.'<br /><br />
								</td></tr></table>';
					$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => 'systems_support@pitsco.com','subject' => $subj, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
				}
			}
		}
	}
}


//Notifiaction mail send to users
$maillicenseqry = $ObjDB->QueryObject("SELECT 90 AS days,fld_id AS lid, fld_license_id, fld_district_id, fld_school_id, fld_user_id, fld_remain_users, fld_no_of_users, (fld_no_of_users-fld_remain_users) AS used, fld_end_date, fld_auto_renewal, fld_renewal_count FROM itc_license_track 
WHERE fld_delstatus='0' AND fld_end_date = DATE_ADD(DATE(NOW()), INTERVAL 90 DAY) 		UNION ALL SELECT 30 AS days,fld_id AS lid, fld_license_id, fld_district_id, fld_school_id, fld_user_id, fld_remain_users, fld_no_of_users, (fld_no_of_users-fld_remain_users) AS used, fld_end_date, fld_auto_renewal, fld_renewal_count FROM itc_license_track  WHERE fld_delstatus='0' AND fld_end_date = DATE_ADD(DATE(NOW()), INTERVAL 30 DAY) 		UNION ALL SELECT 15 AS days,fld_id AS lid, fld_license_id, fld_district_id, fld_school_id, fld_user_id, fld_remain_users, fld_no_of_users, (fld_no_of_users-fld_remain_users) AS used, fld_end_date, fld_auto_renewal, fld_renewal_count FROM itc_license_track WHERE fld_delstatus='0' AND fld_end_date = DATE_ADD(DATE(NOW()), INTERVAL 15 DAY)");

if($maillicenseqry->num_rows>0)
{
	while($rowmaillicenseqry = $maillicenseqry->fetch_assoc())
	{
		extract($rowmaillicenseqry);
		$profile='';
		
		if(($fld_school_id=='0' && $fld_user_id=='0')){
			$profile = 6;				
		}
		else if($fld_user_id=='0' && $fld_district_id=='0')
			$profile = 7;
		else if($fld_district_id=='0' && $fld_school_id=='0')
			$profile = 5;	
		if($profile!=''){	
			$adminqry = $ObjDB->QueryObject("SELECT fld_fname AS afname, fld_lname AS alname, fld_email AS aemail, 
(SELECT a.fld_field_value FROM itc_user_add_info AS a WHERE a.fld_user_id=fld_id AND a.fld_field_id=3 AND a.fld_delstatus='0') AS aphone FROM itc_user_master 
WHERE fld_district_id='".$fld_district_id."' AND fld_school_id='".$fld_school_id."' AND fld_user_id='".$fld_user_id."'
 AND fld_profile_id='".$profile."' AND fld_delstatus='0' ORDER BY fld_id LIMIT 0,1");
			if($adminqry->num_rows>0){
				extract($adminqry->fetch_assoc());
			}
		}
		
		$licensename = $ObjDB->SelectSingleValue("SELECT fld_license_name FROM itc_license_master WHERE fld_id='".$fld_license_id."' AND fld_delstatus='0'");
		
		
		$up = "'";		
		if(($fld_school_id=='0' && $fld_user_id=='0')){			
			$orname = $ObjDB->SelectSingleValue("select fld_district_name from itc_district_master where fld_id='".$fld_district_id."'")."(District purchase)";			
		}
		else if($fld_user_id=='0' && $fld_district_id=='0'){			
			$orname = $ObjDB->SelectSingleValue("select fld_school_name from itc_school_master where fld_id='".$fld_school_id."'")."(School purchase)";			
		}
		else if($fld_district_id=='0' && $fld_school_id=='0'){			
			$orname = $ObjDB->SelectSingleValue("select concat(fld_fname,' ',fld_lname) as iname from itc_user_master where fld_id='".$fld_user_id."'")."(Home purchase)";			
		}		
		$auto='';
		if($profile!=''){			
			$qry = $ObjDB->QueryObject("SELECT fld_fname, fld_lname, fld_email, fld_username, fld_id, fld_profile_id FROM itc_user_master WHERE fld_district_id='".$fld_district_id."' AND fld_school_id='".$fld_school_id."' AND fld_user_id='".$fld_user_id."' AND fld_profile_id<>10 AND fld_profile_id<>11 AND fld_delstatus='0'");
			
			if($qry->num_rows>0)
			{
				while($rowqry = $qry->fetch_assoc())
				{
					extract($rowqry);
					
					if($fld_email!='')
					{
						$content='';
						$lastcontent = "";
						$admindetails='';
						if($fld_auto_renewal==0){
							if($fld_profile_id==6){
								$content = '<tr><td valign="top" align="left">The License below will expire in '.$days.' days, please don'.$up.'t hesitate to contact our sales support staff at 800-828-5787 to renew your license.</td></tr>';								
							}
							else if($fld_profile_id==7 && $fld_district_id==0){
								$content = '<tr><td valign="top" align="left">The License below will expire in '.$days.' days, please don'.$up.'t hesitate to contact our sales support staff at 800-828-5787 to renew your license.</td></tr>';								
							}
							else if($fld_profile_id==5){
								$content = '<tr><td valign="top" align="left">The License below will expire in '.$days.' days, please don'.$up.'t hesitate to contact our sales support staff at 800-828-5787 to renew your license.</td></tr>';								
							}
							else {
								$content = '<tr><td valign="top" align="left">The License below will expire in '.$days.' days, please contact your administrator to renew your license.</td></tr>';
								$admindetails= 'Administrator: '.$afname.' '.$alname.'<br />Phone: '.$aphone.'<br />Email: '.$aemail.'';								
								}
							$auto="no";
						}
						else{
							$content = '<tr><td valign="top" align="left">The Synergy ITC license below will automatically renew in '.$days.' days. No action is required on your behalf.</td></tr>';
							$auto="yes / ".$fld_renewal_count;													
								$lastcontent = '<tr><td valign="top" align="left">If you are in need of any additional information about this license, please don'.$up.'t hesitate to contact our sales support staff at 800-828-5787. <br /> If you need technical support, please don'.$up.'t hesitate to contact Customer Support at 800-774-4552. <p>Thank you,</p><p><strong>Pitsco Education</strong><br>800-774-4552<br>www.pitsco.com</p><p align="center"  style="font-style: italic;">Thank you for being a loyal Pitsco customer! <br>We appreciate all you do for students!</p></td></tr>';							
						}
						
						
						$html_txt = '';
						$headers = '';
						
						$subj = $licensename." - License about to expire";
						$random_hash = md5(date('r', time())); 
										
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=utf-8" . "\r\n"; 
						$headers .= "From: Synergy2 <info@pitsco.info>" . "\r\n"; 												
						$html_txt = '<table width="98%" cellpadding="10" cellspacing="0"><tr><td valign="top" align="left"><br />Hello '.$fld_fname.', <br /></td></tr>'.$content.'
						<tr><td valign="top" align="left">Lease: '.$licensename.'<br />
						End date: '.date("m/d/Y",strtotime($fld_end_date)).'<br />
						Automatic Renew: '.$auto.'<br />
						Licenses Seats occupied:</strong> '.$used.'/'.$fld_no_of_users.'<br />
						Licenses Seats available:</strong>'.$fld_remain_users.'/'.$fld_no_of_users.'(available seats from the upcoming lease period)<br />
						'.$admindetails.'</td></tr>'.$lastcontent.'</table>';						
						$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => $fld_email,'subject' => $subj, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
						$client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
						$client->call('SendJangoMailTransactional', $param, '', '', false, true);						
					}
				}
			}
		}
		//for pitsco admin
		$html_txt = '';
		$headers = '';		
		$subj = $licensename."-".$orname." - License about to expire";
		$random_hash = md5(date('r', time())); 
						
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=utf-8" . "\r\n"; 		
		$headers .= "From: Synergy2 <info@pitsco.info>" . "\r\n";
		if($fld_auto_renewal==1)
			$content = '<tr><td valign="top" align="left">The Synergy ITC license below will automatically renew in '.$days.' days. No action is required on your behalf.</td></tr>';
		else
			$content = '<tr><td valign="top" align="left">The License below will expire in '.$days.' days.</td></tr>';
		$html_txt = '<table width="98%" cellpadding="10" cellspacing="0">'.$content.'
		<tr><td valign="top" align="left">Lease: '.$licensename.'<br />
		End date: '.date("m/d/Y",strtotime($fld_end_date)).'<br />
		Automatic Renew: '.$auto.'<br />
		Licenses Seats occupied:</strong> '.$used.'/'.$fld_no_of_users.'<br />
		Licenses Seats available:</strong>'.$fld_remain_users.'/'.$fld_no_of_users.'(available seats from the upcoming lease period)<br />
		</td></tr></table>';
		$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => 'systems_support@pitsco.com','subject' => $subj, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
		$client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
		$client->call('SendJangoMailTransactional', $param, '', '', false, true);
	}
}

	@include("includes/footer.php");
?>
