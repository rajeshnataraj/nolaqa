<?php 
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';

$id=explode(",",$id);

?>
<script>
	$.getScript("users/schools/users-schools-newschool.js");
</script>
<section data-type='#users-schools' id='users-schools-schools_actions'>
  <div class='container'>
    <div class='row'>
      <div class='twelve columns'>
      	<p class="dialogTitle"><?php echo $id[1]." Actions";?></p>
        <p class="dialogSubTitleLight">&nbsp;</p>
      </div>
    </div>
     <?php $userdet = $ObjDB->QueryObject("SELECT a.fld_activestatus as actstatus, a.fld_created_by as createdby FROM itc_user_master AS a, `itc_school_master` AS b WHERE a.fld_id= b.fld_school_admin_id AND b.fld_id='".$id[0]."' AND a.fld_delstatus='0'");
	 
	 $row = $userdet->fetch_assoc();
	 extract($row);
	 ?>
     
    <div class='row buttons'>
      <a class='skip btn mainBtn' href='#users-newschool' id='btnusers-schools-newschoolview' name='<?php echo $id[0];?>'>
        <div class='icon-synergy-view'></div>
        <div class='onBtn'>View</div>
      </a>
      <a class='skip btn mainBtn' href='#users-newschool' id='btnusers-schools-newschool' name='<?php echo $id[0].",".$id[2];?>'>
        <div class="icon-synergy-edit"></div>
        <div class='onBtn'>Edit</div>
      </a>
      <a class='skip btn main' href='#users-newschool' onclick="fn_deletschool(<?php echo $id[0];?>)">
       <div class="icon-synergy-trash"></div>
        <div class='onBtn'>Delete</div>
      </a>

        <?php $username = $ObjDB->SelectSingleValue("SELECT fld_username FROM itc_user_master 
	  												WHERE fld_id='".$id[0]."'");?>
      <a class='skip btn main <?php if($actstatus !=1){?> dim <?php } ?>' href='#users-newschool' onclick="fn_resets('<?php echo $username?>')">
         <div class='icon-synergy-key'></div>
        <div class='onBtn'>Reset <br/> Password</div>
      </a>
      <?php  if($actstatus==0) { ?>
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