<?php 
/*------
	Page - users-district-districts_actions
	Description:
		showing the buttons to perform Edit, Delete and view operation for a distirct
	History:	
------*/
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';
$id = explode(",",$id);

?>
<script type='text/javascript'>
$.getScript("users/districts/users-districts-newdistrict.js");
</script>
<section data-type='#users-districts' id='users-districts-districts_actions'>
  <div class='container'>
    <div class='row'>
      <div class='twelve columns'>
      	<p class="dialogTitle"><?php echo $id[1]." District Actions";?></p>
        <p class="dialogSubTitleLight">&nbsp;</p>
      </div>
    </div>
    
    <div class='row buttons'>
      <a class='skip btn mainBtn' href='#users-newdistrict' id='btnusers-districts-newdistrictview' name='<?php echo $id[0];?>'>
        <div class='icon-synergy-view'></div>
        <div class='onBtn'>View</div>
      </a>
      <a class='skip btn mainBtn' href='#users-newdistrict' id='btnusers-districts-newdistrict' name='<?php echo $id[0];?>'>
        <div class='icon-synergy-edit'></div>
        <div class='onBtn'>Edit</div>
      </a>
      <a class='skip btn main' href='#users-newdistrict' onclick="fn_deletdistrict(<?php echo $id[0];?>)">
         <div class='icon-synergy-trash'></div>
        <div class='onBtn'>Delete</div>
      </a>
      <?php $status = $ObjDB->SelectSingleValueInt("SELECT a.fld_activestatus 
	  												FROM itc_user_master AS a, `itc_district_master` AS b 
													WHERE a.fld_id= b.fld_district_admin_id AND b.fld_id='".$id[0]."' AND a.fld_delstatus='0'");?>

        <?php $username = $ObjDB->SelectSingleValue("SELECT itc_user_master.fld_username FROM itc_user_master LEFT JOIN itc_district_master ON itc_user_master.fld_id = itc_district_master.fld_district_admin_id 
	  												WHERE itc_district_master.fld_id='". intval($id[0])."'");
        ?>

      <a class='skip btn main <?php if($status !=1){?> dim <?php } ?>' href='#users-newdistrict' onclick="fn_resetd('<?php echo $username;?>')">
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
