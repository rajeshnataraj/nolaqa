<?php
require_once("comm_func.php");

if (isset($_POST["username"]) && !empty($_POST["username"])) {
    $username = addslashes($_POST["username"]);
}else{
    echo "Please enter a valid username.";
    exit();
}

require_once('../includes/UserManager.php');
$userrow = UserManager::db_fetch_user($username);
$user = new UserManager($userrow);
if($user->check_forget()){
    $today = date("Y-m-d H:i:s");
    $token = md5($user->email).strtotime($today);
    $link="<a target='_blank' class='btn btn-info block-center' style='text-align: center; color:#49708A; font-size:15px; line-height:1.6em; width:280px; margin:0 auto; padding:20px; font-weight:bold;' href='". ITC_URL ."/users/reset-password.php?userid=".$user->id."&token=".$token."'>Click here to reset password</a>";
    $user->helper->update("itc_user_master", array("fld_actkey"=>$token, "fld_updated_date"=>$today, "fld_updated_by"=>$user->id), array("fld_id"=>$user->id), array());

    require_once "../vendor/autoload.php";

    $body = '

<!--
Responsive Email Template by @keenthemes
A component of Metronic Theme - #1 Selling Bootstrap 3 Admin Theme in Themeforest: http://j.mp/metronictheme
Licensed under MIT
-->

<div id="mailsub" class="notification" align="center">

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="min-width: 320px;"><tr><td align="center" bgcolor="#49708A" style="min-width: 320px; padding: 25px 2%; -webkit-box-shadow: 0px 0px 16px 0px rgba(0,0,0,0.5);
-moz-box-shadow: 0px 0px 16px 0px rgba(0,0,0,0.5);
box-shadow: 0px 0px 16px 0px rgba(0,0,0,0.5);">


<!--[if gte mso 10]>
<table width="680" border="0" cellspacing="0" cellpadding="0">
<tr><td>
<![endif]-->

<table border="0" cellspacing="0" cellpadding="0" class="table_width_100" width="100%" style="max-width: 680px; min-width: 310px;">
    <tr><td>
	<!-- padding -->
	</td></tr>
	<!--header -->
	<tr><td align="center">
		<!-- padding -->
		<div style="height: 20px; line-height: 20px; font-size: 10px;"></div>
		<table width="90%" border="0" cellspacing="0" cellpadding="0">
			<tr><td align="center" bgcolor="#ffffff" style="border-radius: 5px 5px 0 0; padding:12px;">
			    		<a href="#" target="_blank" style="color: #357; font-family: Arial, Helvetica, sans-serif; float:left; width:100%; padding:12px;text-align:center; font-size: 13px;">
									<font face="Arial, Helvetica, sans-seri; font-size: 13px;" size="3" color="#468">
									<img src="<?=ITC_URL?>/pitsco.logo.login.png" width="70" height="20" style="margin-left:-50px;" alt="PITSCO" border="0"  /></font><br>
									<font face="Arial, Helvetica, sans-seri; font-size: 13px;" size="3" color="#468">
									<img src="<?=ITC_URL?>/img/synergy.logo.png" width="225" height="50"  alt="Synergy ITC" border="0"  /></font></a>
					</td>
					<td align="right">
				<!--[endif]--><!-- 

			</td>
			</tr>
		</table>
		<!-- padding -->
	</td></tr>
	<!--header END-->

	<!--content 1 -->
	<tr><td align="center" bgcolor="#fff"  width="100%">
		<table  border="0" cellspacing="0" cellpadding="0" bgcolor="#f3f4f6" width="86%" style="padding: 0 8px; -webkit-box-shadow: 0px 0px 4px 0px rgba(0,0,0,0.2);
-moz-box-shadow: 0px 0px 4px 0px rgba(0,0,0,0.2);
box-shadow: 0px 0px 4px 0px rgba(0,0,0,0.2); border-radius: 5px;" >
			<tr><td align="center">
				<!-- padding --><div style="height: 24px; line-height: 24px; font-size: 10px;"></div>
				<div style="line-height: 44px;">
					<font face="Arial, Helvetica, sans-serif" size="5" color="#57697e" style="font-size: 22px;">
					<span style="font-family: Arial, Helvetica, sans-serif; font-size: 22px; color: #57697e;">
						Reset Your Password
					</span></font>
				</div>
				<!-- padding --><div style="height: 12px; line-height: 12px; font-size: 10px;"></div>
			</td></tr>
			<tr><td align="center">
				<div style="line-height: 20px;">
					<font face="Arial, Helvetica, sans-serif" size="4" color="#57697e" style="font-size: 12.5px;">
					<span style="font-family: Arial, Helvetica, sans-serif; font-size: 12.5px; color: #356;">
						<p>You recently requested to reset your password for your Synergy Account. <br>Click the link below to set a new password.</p><br>
						
					</span></font>
				</div>
				<!-- padding --><div style="height: 4px; line-height: 4px; font-size: 4px;"></div>
			</td></tr>
			<tr><td align="center">
				<div style="line-height: 18px;">
					'. $link .'
				</div>
				<!-- padding --><div style="height: 40px; line-height: 40px; font-size: 10px;"></div>
			</td></tr>
		</table>		
	</td></tr>
	<!--content 1 END-->


	<!--footer -->
	<tr><td class="iage_footer" align="center" bgcolor="#ffffff" style="border-radius: 0 0 5px 5px;">

		
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr><td align="center" style="padding:36px 18px;float:left;width:100%; text-align:center;"><font face="Arial, Helvetica, sans-serif" size="3" color="#96a5b5" style="font-size: 13px;"><span style="font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #79B;">&#169; PITSCO Education. ALL Rights Reserved.
				</span></font>				
			</td></tr>			
		</table>
		

	</td></tr>
	<!--footer END-->
	<tr><td>

	</td></tr>
</table>
<!--[if gte mso 10]>
</td></tr>
</table>
<![endif]-->
 
</td></tr>
</table>
			';


    try{
        $param = array(
            'SiteID' => '30',
            'fromAddress' => 'info@pitsco.com',
            'fromName' => 'Synergy ITC',
            'toAddress' => $user->email,
            'subject' => "ITC Synergy Password Reset Request",
            'plainTex' => '',
            'html' => $body,
            'options' => '',
            'groupID' => '805014',
            'log' => 'True'
        );
        $client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
        $client->call('SendJangoMailTransactional', $param, '', '', false, true);
        echo "success";
    }
    catch(Exception $e){
        echo "Mail Error - >". $e->getMessage();
    }
}

?>
