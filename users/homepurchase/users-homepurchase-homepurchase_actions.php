<?php 
/*------
	Page - users-homepurchase-homepurchase_actions
	Description:
		showing the buttons to perform Edit, Delete and view operation for a homepurchase
	
	History:	
		
------*/
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';
$id = explode(",",$id);

?>
<script> $.getScript("users/homepurchase/users-homepurchase-newhomepurchase.js");</script>
<section data-type='#users-homepurchase' id='users-homepurchase-homepurchase_actions'>
  <div class='container'>
    <div class='row'>
      <div class='twelve columns'>
      	<p class="lightTitle"><?php echo $id[1]." Actions";?></p>
        <p class="lightSubTitle">&nbsp;</p>
      </div>
    </div>
    <div class='row buttons'>
	  <a class='skip btn mainBtn' href='#users-newhomepurchase' id='btnusers-homepurchase-newhomepurchaseview' name='<?php echo $id[0];?>'>
        <div class="icon-synergy-view"></div>
        <div class='onBtn'>View</div>
      </a>	
      <a class='skip btn mainBtn' href='#users-newhomepurchase' id='btnusers-homepurchase-newhomepurchase' name='<?php echo $id[0];?>'>
        <div class="icon-synergy-edit"></div>
        <div class='onBtn'>Edit</div>
      </a>
      <a class='skip btn main' href='#users-newhomepurchase' onclick="fn_delethomepurchase(<?php echo $id[0];?>)">
        <div class="icon-synergy-trash"></div>
        <div class='onBtn'>Delete</div>
      </a>
        <?php $status = $ObjDB->SelectSingleValueInt("SELECT fld_activestatus FROM itc_user_master 
	  												WHERE fld_id='".$id[0]."' AND fld_delstatus='0'");?>
        <?php $username = $ObjDB->SelectSingleValue("SELECT fld_username FROM itc_user_master 
	  												WHERE fld_id='".$id[0]."' AND fld_delstatus='0'");?>
      <a class='skip btn main <?php if($status !=1){ echo "dim"; } ?>' href='#users-newhomepurchase' onclick="fn_resethp('<?php echo $username;?>')">
         <div class='icon-synergy-key'></div>
         <div class='onBtn'>Reset<br/>Password</div>
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
<?php
	@include("footer.php");
