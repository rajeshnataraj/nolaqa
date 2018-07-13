<?php
@require_once('config.php');
@include('table.class.php');
@include('comm_func.php');
$method=$_POST;

$oper = isset($_REQUEST['oper']) ? $_REQUEST['oper'] : '';

/*------------ Check user name is already exits or not-------------------*/
if($oper == "checkusername" and $oper != ""){
	$txtusername =  isset($method['txtusername']) ? $method['txtusername'] : '';
	
	$checkemail = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) FROM itc_user_master WHERE MD5(LCASE(fld_username))='".md5(preg_replace( '/\s+/', '', strtolower($txtusername)))."' AND fld_activestatus='1' AND fld_delstatus='0'");
	
	if($checkemail > 0 ){ echo "false"; } else { echo "true"; }
}

/*------------ Insert yhe the username and password to the table and cofirmation mail to the user -------------------*/
if($oper == "register" and $oper != '') 
{ 
	$firstname = (isset($method['firstname'])) ? $ObjDB->EscapeStrAll($method['firstname']) : '';
	$lastname = (isset($method['lastname'])) ? $ObjDB->EscapeStrAll($method['lastname']) : '';
	$usernamec = (isset($method['usernamec'])) ? $method['usernamec'] : '';
	$password = (isset($method['password'])) ? $method['password'] : '';	
	$userid = (isset($method['usersid'])) ? $method['usersid'] : '';

	$query = $ObjDB->NonQuery("UPDATE itc_user_master SET fld_fname = '".$firstname."', fld_lname = '".$lastname."', fld_username = '".$usernamec."', fld_password = '".fnEncrypt($password,$encryptkey)."', fld_activestatus='1' where fld_id = '".$userid."'");
	
	$userdet = $ObjDB->QueryObject("SELECT fld_email, fld_role_id, fld_profile_id, fld_school_id, fld_district_id 
									FROM itc_user_master 
									WHERE fld_id = '".$userid."'");
	
	$row = $userdet->fetch_assoc();
	extract($row);
	
	
	if($fld_role_id == 1 or $fld_role_id == 2 or $fld_role_id == 3) {
		$headtitle = "PITSCO";
	}
	else if($fld_role_id == 4) {
		$headtitle = $ObjDB->SelectSingleValue("SELECT fld_district_name 
												FROM itc_district_master 
												WHERE fld_id= '".$fld_district_id."'");
	}
	else if($fld_profile_id == 5)
	{
		$headtitle = "Home Purchase";		
	}
	
	else {
		$headtitle = "School Purchase";	
		if($fld_school_id != 0) {
			$headtitle = $ObjDB->SelectSingleValue("SELECT fld_school_name 
													FROM itc_school_master 
													WHERE fld_id= '".$fld_school_id."'");;	
		}
	}
	
	$subj = "Your account has been created";
	$random_hash = md5(date('r', time())); 
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n"; 	
	$headers .= "From: do_not_reply@pitsco.com" . "\r\n";  

	$html_txt = '<table cellpadding="0" cellspacing="0" border="0" width="98%"><tbody><tr><td style="padding:15px;padding-top:10px;padding-bottom:40px;font-family:Helvetica,Arial,sans-serif;font-size:16px;
color:#222222;text-align:left" valign="top"><h1 style="font-family:Helvetica,Arial,sans-serif;color:#222222;font-size:28px;line-height:normal;letter-spacing:-1px">
Synergy ITC account creation successful</h1><p>Hello <font style="font-style: italic;">'.$headtitle.':</font></p><p>Welcome to <font style="font-style: italic;">Synergy ITC</font>. Your account has been successfully created. Please log in to activate your account. Below you will find your login credentials along with a link to the <font style="font-style: italic;">Synergy ITC</font> login portal.</p><p><b>Username:</b> '.$usernamec.'</p><p><b>Password :</b>   '.$password.'</p><p><b>Access your <font style="font-style: italic;">Synergy ITC</font> account:</b><br><a href="'.__HOSTADDR__.'" target="_blank">'.__HOSTADDR__.'</a></p><p>If you have questions or difficulties logging in, please contact Customer Support at <br>800-774-4552. </p><p>Thank you,</p>
<p><strong>Pitsco Education</strong><br>800-774-4552<br>www.pitsco.com</p><p align="center"  style="font-style: italic;">Thank you for being a loyal Pitsco customer! <br>We appreciate all you do for students!</p>
</td></tr></tbody></table>';
        
        
	$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => $fld_email,'subject' => $subj, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
	$client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
	$client->call('SendJangoMailTransactional', $param, '', '', false, true);	
		echo "success";	
}

@include("footer.php");
