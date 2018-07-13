<?php
session_start();
@require_once('config.php');
@include('table.class.php');
@include('comm_func.php');
$method=$_POST;

$oper = (isset($method['oper'])) ? $method['oper'] : 0;

/*------------ Check user name is already exits or not-------------------*/
if($oper == "checkusername" and $oper != '')
{
	$username = (isset($method['username'])) ? $method['username'] : '';
		if( $username != ''){
			$check = $ObjDB->Count("SELECT fld_username FROM itc_user_master WHERE MD5(fld_username)='".md5($username)."' 
								   AND fld_delstatus='0' AND fld_activestatus='1'");	
			$studentcheck = $ObjDB->SelectSingleValue("SELECT fld_profile_id FROM itc_user_master 
													  WHERE MD5(fld_username)='".md5($username)."' AND fld_delstatus='0' AND fld_activestatus='1'");	
				if($studentcheck != 10)
				{
					if($check > 0){
					
						echo "available";
					}
					else{ 
					echo "exist";	
					}
				}
				else
				{
					echo "student";
				}
		}
	
}
/*------------ Veryfiy the code-------------------*/
if($oper == "checkverfication" and $oper != '') {
		$userenteredcode = (isset($method['code'])) ? $method['code'] : '';
		$generatdcode = $_SESSION['security_code'];
		if(md5($userenteredcode) == md5($generatdcode)) {
			echo 'verified';	
		}
		else {
			echo 'failed';	
		}
	}
/*------------Sent password via mail to the user-------------------*/	
if($oper == "sentpasswordtoemail" and $oper != '') {
	$_SESSION['fpemail'] = '';
	$username = (isset($method['username'])) ? $method['username'] : '';
	$senderdetail = $ObjDB->QueryObject("SELECT fld_username AS uname,fld_password AS password,fld_fname AS fname,fld_email AS email 
										FROM itc_user_master 
										WHERE MD5(fld_username)='".md5($username)."' AND fld_delstatus='0' AND fld_activestatus='1'");
	$rowsenddetail = $senderdetail->fetch_assoc();
	extract($rowsenddetail);
	$subj = "your pitsco password";
	$encryptpass = fnDecrypt($password,$encryptkey);
	$random_hash = md5(date('r', time())); 
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n"; 
	$headers .= "From: Synergy ITC <do_not_reply@pitsco.com>" . "\r\n"; 


	$html_txt = '<table cellpadding="0" cellspacing="0" border="0" width="98%"><tbody><tr><td style="padding:15px;font-family:Helvetica,Arial,sans-serif;font-size:16px;text-align:left"><img src="http://development.pitsco.info/images/pitsco-logo-n.png"/></td></tr><tr><td style="padding:15px;padding-top:10px;padding-bottom:40px;font-family:Helvetica,Arial,sans-serif;font-size:16px;color:#222222;text-align:left" valign="top"><h1 style="font-family:Helvetica,Arial,sans-serif;color:#222222;font-size:28px;line-height:normal;letter-spacing:-1px">Synergy ITC login credentials </h1><p>Hello '.$fname.',</p><p>Thank you for choosing Synergy ITC. We&lsquo;ve received a request for login information associated with the user name below.</p><p>If you continue to experience difficulties logging in, please contact Customer Support at 800-774-4552.</p><p><strong>User name: </strong>'.$uname.'</p><p><strong>Password: </strong> '.$encryptpass.'<br></p><hr style="margin-top:30px;border:none;border-top:1px solid #ccc"><p style="font-size:13px;line-height:1.3em"><b>Didn&lsquo;t ask to reset your password?</b><br><p>If you didn&lsquo;t ask for your password, it&lsquo;s possible that another user entered your user name or email address by mistake while trying to reset their password. If you have questions or concerns, please contact 800-774-4552.</p><p>Thank you,</p>
<p><strong>Pitsco Education</strong><br>800-774-4552<br>www.pitsco.com</p><p align="center"  style="font-style: italic;">Thank you for being a loyal Pitsco customer! <br>We appreciate all you do for students!</p></td></tr></tbody></table>';
	$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => $email,'subject' => $subj, 'plainTex' => '','html' => wordwrap($html_txt),'options' => '','groupID' => '805014','log' => 'True');
	$client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
	$client->call('SendJangoMailTransactional', $param, '', '', false, true);		
	echo "success";	
}		

if($oper == "recapimg" and $oper != '') {
	$_SESSION['username'] = $method['username'];
	echo $_SESSION['username'];
}		
		
@include("footer.php");
