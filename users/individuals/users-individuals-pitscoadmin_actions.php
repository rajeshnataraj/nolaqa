<?php 
@include("../../sessioncheck.php");

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

$id=explode(",",$id);

?>
<section data-type='#users-homepurchase' id='users-individuals-pitscoadmin_actions'>
  <div class='container'>
    <div class='row'>
      <div class='twelve columns'>
      	<p class="dialogTitle"><?php echo $id[1]." Actions";?></p>
		<p class="dialogSubTitleLight">&nbsp;</p>
      </div>
    </div>
    <div class='row buttons'>
		<a class='skip btn mainBtn' href='#users-individuals-pitscoadmin_newpitscoadmin' id='btnusers-individuals-pitscoadmin_newpitscoadmin' name='<?php echo $id[0];?>'>
        	<div class="icon-synergy-edit"></div>
        	<div class='onBtn'>Edit</div>
      	</a>
      	<a class='skip btn main' href='#users-individuals-pitscoadmin_newpitscoadmin' onclick="fn_deletpitscoadmin(<?php echo $id[0];?>)">
        	<div class="icon-synergy-trash"></div>
        	<div class='onBtn'>Delete</div>
      	</a>
		<?php $status = Table::SelectSingleValueInt("SELECT fld_activestatus FROM itc_user_master WHERE fld_id='".$id[0]."' AND fld_delstatus='0'");?>
        <a class='skip btn main <?php if($status !=1){ echo "dim"; } ?>' href='#users-individuals-pitscoadmin_newpitscoadmin' onclick="fn_resetpa(<?php echo $id[0];?>)">
         	<div class='icon-synergy-key'></div>
			<div class='onBtn'>Reset <br/> Password</div>
        </a>
         <?php  if($status==0) { ?>
            <a class='skip btn main' href='#users-individuals-teacheradmin_newteacheradmin' onclick="fn_resendmail(<?php echo $id[0];?>)">
                <div class='icon-synergy-mail'></div>
                <div class='onBtn'>Resend <br/> Invitation</div>
            </a>
		<?php } ?>
    </div>
  </div>
</section>
